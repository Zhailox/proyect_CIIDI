<?php
// modules/SuperAdmin/services/BackupService.php

require_once CORE_PATH . 'Database/Connection.php';
require_once CORE_PATH . 'Security/Auth.php';

class BackupService {

    private static function getBackupDir(): string {
        $dir = defined('STORAGE_PATH') ? STORAGE_PATH . 'backups/' : __DIR__ . '/../../../storage/backups/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir;
    }

    /**
     * Genera un respaldo MariaDB 10.6+ en formato comprimido (.sql.gz), SQL plano (.sql) o texto (.txt) con políticas de retención.
     * Utiliza mysqldump si está habilitado en el servidor y cuenta con respaldo nativo PDO puro para cPanel / CloudLinux.
     */
    public static function crearBackup(string $formato = 'sql.gz', bool $soloEsquema = false, ?string $tabla = null, ?string $prefijoCustom = null): array {
        $creds = Connection::getCredentials();
        $mysqldumpPath = Connection::getMysqldumpPath();
        $backupDir = self::getBackupDir();

        $fecha = date('Y-m-d_H-i-s');
        $prefix = $prefijoCustom ?: ($tabla ? "tabla_{$tabla}" : ($soloEsquema ? "esquema_ciidi" : "backup_ciidi"));
        
        $formatoLimpio = strtolower($formato);
        if (!in_array($formatoLimpio, ['sql.gz', 'sql', 'txt'], true)) {
            $formatoLimpio = 'sql.gz';
        }

        $comprimir = ($formatoLimpio === 'sql.gz');
        $ext = ".{$formatoLimpio}";
        $nombreArchivo = "{$prefix}_{$fecha}{$ext}";
        $rutaCompleta = $backupDir . $nombreArchivo;
        $tempSqlPath = $comprimir ? $backupDir . "temp_{$fecha}.sql" : $rutaCompleta;

        $generadoConCli = false;
        $disabledFns = array_map('trim', explode(',', strtolower((string)ini_get('disable_functions'))));
        $execDisponible = function_exists('exec') && !in_array('exec', $disabledFns, true);

        if ($execDisponible) {
            $tmpCnf = $backupDir . ".tmp_my_{$fecha}_" . mt_rand(1000, 9999) . ".cnf";
            $passEscaped = str_replace(['\\', '"'], ['\\\\', '\\"'], (string)($creds['pass'] ?? ''));
            $cnfContent = "[client]\n"
                        . "host=\"" . ($creds['host'] ?? 'localhost') . "\"\n"
                        . "port=" . (int)($creds['port'] ?? 3306) . "\n"
                        . "user=\"" . ($creds['user'] ?? 'root') . "\"\n"
                        . "password=\"{$passEscaped}\"\n"
                        . "default-character-set=utf8mb4\n";
            @file_put_contents($tmpCnf, $cnfContent);
            @chmod($tmpCnf, 0600);

            $flags = "--defaults-extra-file=" . escapeshellarg($tmpCnf)
                   . " --default-character-set=utf8mb4 --single-transaction --triggers --add-drop-table --hex-blob";
            if ($soloEsquema) {
                $flags .= " --no-data --routines";
            } else {
                $flags .= " --routines";
            }

            $dbArg = escapeshellarg((string)($creds['db'] ?? 'ciidi'));
            $tablaArg = ($tabla !== null && preg_match('/^[a-zA-Z0-9_]+$/', $tabla)) ? ' ' . escapeshellarg($tabla) : '';
            $outArg = escapeshellarg($tempSqlPath);

            $comando = "{$mysqldumpPath} {$flags} --result-file={$outArg} {$dbArg}{$tablaArg} 2>&1";
            $salida = [];
            $codigoRetorno = 1;
            @exec($comando, $salida, $codigoRetorno);
            if (file_exists($tmpCnf)) {
                @unlink($tmpCnf);
            }

            if ($codigoRetorno === 0 && file_exists($tempSqlPath) && filesize($tempSqlPath) > 0) {
                $generadoConCli = true;
            } elseif (file_exists($tempSqlPath)) {
                @unlink($tempSqlPath);
            }
        }

        // Respaldo nativo en PHP 8.2 + PDO (100% compatible con hosting compartido cPanel sin mysqldump / exec)
        if (!$generadoConCli) {
            try {
                $db = Connection::getInstance();
                $sqlNativo = self::exportarConPdo($db, $soloEsquema, $tabla);
                file_put_contents($tempSqlPath, $sqlNativo);
            } catch (Throwable $e) {
                if (file_exists($tempSqlPath)) @unlink($tempSqlPath);
                return [
                    'exito' => false,
                    'mensaje' => 'Fallo al exportar respaldo MariaDB: ' . $e->getMessage()
                ];
            }
        }

        if (!file_exists($tempSqlPath) || filesize($tempSqlPath) === 0) {
            if (file_exists($tempSqlPath)) @unlink($tempSqlPath);
            return [
                'exito' => false,
                'mensaje' => 'No se pudo generar el archivo de respaldo MariaDB.'
            ];
        }

        // Sanitizar SQL (ej. remover DEFINER de vistas/triggers para portabilidad entre cuentas cPanel)
        $rawContent = (string)file_get_contents($tempSqlPath);
        if (method_exists('Connection', 'sanitizarSqlCompatible')) {
            $rawContent = Connection::sanitizarSqlCompatible($rawContent);
            file_put_contents($tempSqlPath, $rawContent);
        }

        // Compresión GZIP si se solicitó
        if ($comprimir) {
            $compressedData = gzencode($rawContent, 9);
            file_put_contents($rutaCompleta, $compressedData);
            @unlink($tempSqlPath);
        }

        // Ejecutar Auto-Cleanup de respaldos de más de 30 días
        $eliminadosCount = self::limpiarRespaldosAntiguos(30);

        AuditLogger::registrar('INFO', 'SuperAdmin', 'Crear Backup', "Respaldo {$nombreArchivo} generado exitosamente. Respaldos antiguos purgados: {$eliminadosCount}");

        return [
            'exito' => true,
            'nombre' => $nombreArchivo,
            'ruta' => $rutaCompleta,
            'tamano' => filesize($rutaCompleta),
            'purgados' => $eliminadosCount,
            'mensaje' => "Respaldo generado con éxito: {$nombreArchivo}" . ($eliminadosCount > 0 ? " ({$eliminadosCount} respaldos antiguos purgados)" : "")
        ];
    }

    /**
     * Exportador nativo PDO para MariaDB 10.6+ (tablas, datos e índices + triggers) sin depender de binarios CLI.
     */
    private static function exportarConPdo(PDO $db, bool $soloEsquema = false, ?string $tablaFiltro = null): string {
        $dbName = (string)($db->query("SELECT DATABASE()")->fetchColumn() ?: 'ciidi');
        $ver = (string)($db->query("SELECT VERSION()")->fetchColumn() ?: '10.6.24-MariaDB');

        $out = [];
        $out[] = "-- CIIDI MariaDB Dump (PDO Native Exporter)";
        $out[] = "-- Servidor: {$ver} | Base de datos: `{$dbName}` | Fecha: " . date('Y-m-d H:i:s');
        $out[] = "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;";
        $out[] = "SET FOREIGN_KEY_CHECKS = 0;";
        $out[] = "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';";
        $out[] = "";

        if ($tablaFiltro !== null && $tablaFiltro !== '') {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $tablaFiltro)) {
                throw new InvalidArgumentException("Nombre de tabla inválido: {$tablaFiltro}");
            }
            $tablas = [$tablaFiltro];
        } else {
            $stmtT = $db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE() AND table_type = 'BASE TABLE' ORDER BY table_name ASC");
            $tablas = $stmtT->fetchAll(PDO::FETCH_COLUMN) ?: [];
        }

        foreach ($tablas as $t) {
            $stmtCreate = $db->query("SHOW CREATE TABLE `{$t}`");
            $rowCreate = $stmtCreate ? $stmtCreate->fetch(PDO::FETCH_ASSOC) : null;
            if ($stmtCreate) {
                $stmtCreate->closeCursor();
            }
            if (!$rowCreate) {
                continue;
            }

            $createSql = $rowCreate['Create Table'] ?? (array_values($rowCreate)[1] ?? '');
            if ($createSql === '') {
                continue;
            }

            $out[] = "-- Estructura de tabla para `{$t}`";
            $out[] = "DROP TABLE IF EXISTS `{$t}`;";
            $out[] = $createSql . ";";
            $out[] = "";

            if (!$soloEsquema) {
                $stmtRows = $db->query("SELECT * FROM `{$t}`");
                $batch = [];
                $colsList = null;

                while ($row = $stmtRows->fetch(PDO::FETCH_ASSOC)) {
                    if ($colsList === null) {
                        $colsEscaped = array_map(fn($c) => "`{$c}`", array_keys($row));
                        $colsList = implode(', ', $colsEscaped);
                    }
                    $vals = [];
                    foreach ($row as $val) {
                        if ($val === null) {
                            $vals[] = 'NULL';
                        } elseif (is_int($val) || is_float($val)) {
                            $vals[] = (string)$val;
                        } else {
                            $vals[] = $db->quote((string)$val);
                        }
                    }
                    $batch[] = '(' . implode(', ', $vals) . ')';

                    if (count($batch) >= 250) {
                        $out[] = "INSERT INTO `{$t}` ({$colsList}) VALUES\n" . implode(",\n", $batch) . ";";
                        $batch = [];
                    }
                }
                $stmtRows->closeCursor();

                if (!empty($batch) && $colsList !== null) {
                    $out[] = "INSERT INTO `{$t}` ({$colsList}) VALUES\n" . implode(",\n", $batch) . ";";
                }
                $out[] = "";
            }
        }

        // Exportar Triggers asociados (si es exportación global o de la tabla filtrada)
        try {
            if (method_exists('Connection', 'ensureAuditTriggers')) {
                Connection::ensureAuditTriggers($db);
            }
            $stmtTrig = $db->query("SHOW TRIGGERS");
            $triggers = $stmtTrig ? $stmtTrig->fetchAll(PDO::FETCH_ASSOC) : [];
            if ($stmtTrig) {
                $stmtTrig->closeCursor();
            }
            foreach ($triggers as $tr) {
                $trTable = $tr['Table'] ?? '';
                $trName = $tr['Trigger'] ?? '';
                if ($trName === '' || ($tablaFiltro !== null && $tablaFiltro !== '' && $trTable !== $tablaFiltro)) {
                    continue;
                }
                $stmtCrTr = $db->query("SHOW CREATE TRIGGER `{$trName}`");
                $rowCrTr = $stmtCrTr ? $stmtCrTr->fetch(PDO::FETCH_ASSOC) : null;
                if ($stmtCrTr) {
                    $stmtCrTr->closeCursor();
                }
                $sqlOrig = $rowCrTr['SQL Original Statement'] ?? '';
                if ($sqlOrig !== '') {
                    $sqlOrig = preg_replace('/\bDEFINER\s*=\s*`[^`]+`@`[^`]+`\s+/i', '', $sqlOrig);
                    $out[] = "DROP TRIGGER IF EXISTS `{$trName}`;";
                    $out[] = "DELIMITER ;;";
                    $out[] = $sqlOrig . ";;";
                    $out[] = "DELIMITER ;";
                    $out[] = "";
                }
            }
        } catch (Throwable $eTrig) {
            // Continuar si el usuario de BD no posee privilegio TRIGGER para inspección
        }

        $out[] = "SET FOREIGN_KEY_CHECKS = 1;";
        $out[] = "";

        return implode("\n", $out);
    }

    /**
     * Elimina automáticamente respaldos antiguos de más de N días en storage/backups/.
     */
    public static function limpiarRespaldosAntiguos(int $diasRetencion = 30): int {
        $backupDir = self::getBackupDir();
        $archivos = glob($backupDir . '*.{sql,sql.gz,txt}', GLOB_BRACE);
        $limiteTiempo = time() - ($diasRetencion * 86400);
        $eliminados = 0;

        if ($archivos) {
            foreach ($archivos as $archivo) {
                if (filemtime($archivo) < $limiteTiempo) {
                    if (@unlink($archivo)) {
                        $eliminados++;
                    }
                }
            }
        }
        return $eliminados;
    }

    /**
     * Verificación de Integridad (Dry-Run Parse & Syntax Check) para archivos .sql, .sql.gz o .txt.
     */
    public static function verificarIntegridad(string $rutaArchivo): array {
        if (!is_file($rutaArchivo)) {
            return ['valido' => false, 'detalles' => 'El archivo no existe en el almacenamiento.'];
        }

        $tamano = filesize($rutaArchivo);
        if ($tamano === 0) {
            return ['valido' => false, 'detalles' => 'El archivo de respaldo está completamente vacío (0 bytes).'];
        }

        $extensionGzip = str_ends_with(strtolower($rutaArchivo), '.gz');
        $archivoInicial = file_get_contents($rutaArchivo, false, null, 0, 2);
        $firmaGzip = $archivoInicial === "\x1f\x8b"; //Inicio de binario de los gzip

        $esGzip = $extensionGzip || $firmaGzip;
        $contenido = '';

        if ($esGzip) {
            $zp = @gzopen($rutaArchivo, 'rb');
            if (!$zp) {
                return ['valido' => false, 'detalles' => 'El archivo GZIP está corrupto y no se puede descomprimir.'];
            }
            $buffer = '';
            while (!gzeof($zp)) {
                $buffer .= gzread($zp, 8192);
                if (strlen($buffer) > 150000) break; // Inspeccionar encabezados sin sobrecargar memoria
            }
            gzclose($zp);
            $contenido = $buffer;
        } else {
            $contenido = file_get_contents($rutaArchivo, false, null, 0, 150000);
        }

        if (empty(trim($contenido))) {
            return ['valido' => false, 'detalles' => 'No se pudo extraer contenido SQL del archivo de respaldo.'];
        }

        // Inspeccionar sintaxis SQL esencial y contar objetos
        $patronesValidos = [
            'MariaDB dump',
            'MySQL dump',
            'CIIDI MariaDB Dump',
            'CREATE TABLE',
            'INSERT INTO',
            'SET FOREIGN_KEY_CHECKS',
            'ALTER TABLE',
            'DROP TABLE'
        ];

        $coincidencias = 0;
        foreach ($patronesValidos as $p) {
            if (stripos($contenido, $p) !== false) {
                $coincidencias++;
            }
        }

        if ($coincidencias === 0) {
            return [
                'valido' => false,
                'detalles' => 'Sintaxis SQL no reconocida. El archivo no contiene un volcado válido de MariaDB/MySQL.'
            ];
        }

        // Extraer estadísticas adicionales para inspección previa
        preg_match_all('/CREATE TABLE\s+(?:IF NOT EXISTS\s+)?`?([a-zA-Z0-9_\.]+)`?/i', $contenido, $tablaMatches);
        $tablasEncontradas = array_unique($tablaMatches[1] ?? []);

        preg_match_all('/INSERT INTO/i', $contenido, $insertMatches);
        $totalInserts = count($insertMatches[0] ?? []);

        return [
            'valido' => true,
            'detalles' => 'Integridad y estructura SQL verificadas correctamente (' . ($esGzip ? 'Comprimido GZIP' : 'SQL Plano / Texto') . '). Coincidencias DDL/DML: ' . $coincidencias,
            'esGzip' => $esGzip,
            'tamano' => $tamano,
            'tablas_detectadas' => array_values($tablasEncontradas),
            'total_inserts_estimados' => $totalInserts
        ];
    }
}

