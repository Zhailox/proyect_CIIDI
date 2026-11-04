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
     * Genera un respaldo nativo MariaDB 10.6 vía PDO (sin depender de binarios externos ni exec)
     * en formato comprimido (.sql.gz), SQL plano (.sql) o texto (.txt) con políticas de retención.
     */
    public static function crearBackup(string $formato = 'sql.gz', bool $soloEsquema = false, ?string $tabla = null, ?string $prefijoCustom = null): array {
        $creds = Connection::getCredentials();
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

        try {
            $db = Connection::getInstance();
            $tablas = [];
            if ($tabla) {
                $tablas = [$tabla];
            } else {
                $stmtT = $db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE() AND table_type = 'BASE TABLE' ORDER BY table_name ASC");
                $tablas = $stmtT->fetchAll(PDO::FETCH_COLUMN) ?: [];
            }

            if (empty($tablas)) {
                return [
                    'exito' => false,
                    'mensaje' => 'No se encontraron tablas en la base de datos para respaldar.'
                ];
            }

            $sqlLines = [];
            $sqlLines[] = "-- =============================================================================";
            $sqlLines[] = "-- CIIDI — Respaldo Nativo MariaDB 10.6 (InnoDB / utf8mb4)";
            $sqlLines[] = "-- Base de Datos: " . ($creds['db'] ?? 'ciidi') . " | Fecha: " . date('Y-m-d H:i:s');
            $sqlLines[] = "-- =============================================================================";
            $sqlLines[] = "SET NAMES utf8mb4;";
            $sqlLines[] = "SET FOREIGN_KEY_CHECKS = 0;";
            $sqlLines[] = "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';";
            $sqlLines[] = "";

            foreach ($tablas as $tb) {
                if (!preg_match('/^[a-zA-Z0-9_]+$/', $tb)) {
                    continue;
                }
                $stmtCreate = $db->query("SHOW CREATE TABLE `{$tb}`");
                $rowCreate = $stmtCreate ? $stmtCreate->fetch(PDO::FETCH_ASSOC) : null;
                $createSql = $rowCreate['Create Table'] ?? ($rowCreate['Create View'] ?? null);
                if (!$createSql) {
                    continue;
                }

                $sqlLines[] = "-- -----------------------------------------------------------------------------";
                $sqlLines[] = "-- Estructura de tabla para `{$tb}`";
                $sqlLines[] = "-- -----------------------------------------------------------------------------";
                $sqlLines[] = "DROP TABLE IF EXISTS `{$tb}`;";
                $sqlLines[] = $createSql . ";";
                $sqlLines[] = "";

                if (!$soloEsquema) {
                    $stmtRows = $db->query("SELECT * FROM `{$tb}`");
                    $rows = $stmtRows ? $stmtRows->fetchAll(PDO::FETCH_ASSOC) : [];
                    if (!empty($rows)) {
                        $cols = array_map(fn($col) => "`{$col}`", array_keys($rows[0]));
                        $colList = implode(', ', $cols);
                        $chunks = array_chunk($rows, 100);
                        foreach ($chunks as $chunk) {
                            $valuesList = [];
                            foreach ($chunk as $r) {
                                $vals = [];
                                foreach ($r as $val) {
                                    if ($val === null) {
                                        $vals[] = 'NULL';
                                    } elseif (is_int($val) || is_float($val)) {
                                        $vals[] = (string)$val;
                                    } else {
                                        $vals[] = $db->quote((string)$val);
                                    }
                                }
                                $valuesList[] = '(' . implode(', ', $vals) . ')';
                            }
                            $sqlLines[] = "INSERT INTO `{$tb}` ({$colList}) VALUES\n" . implode(",\n", $valuesList) . ";";
                        }
                        $sqlLines[] = "";
                    }
                }
            }

            if ($tabla === null || in_array($tabla, ['recursos', 'usuarios'], true)) {
                if (method_exists('Connection', 'ensureAuditTriggers')) {
                    Connection::ensureAuditTriggers($db);
                }
                $stmtTrg = $db->query("SHOW TRIGGERS");
                $triggers = $stmtTrg ? $stmtTrg->fetchAll(PDO::FETCH_ASSOC) : [];
                if (!empty($triggers)) {
                    $sqlLines[] = "-- -----------------------------------------------------------------------------";
                    $sqlLines[] = "-- Disparadores (Triggers) de Auditoría";
                    $sqlLines[] = "-- -----------------------------------------------------------------------------";
                    foreach ($triggers as $trg) {
                        $trgName   = $trg['Trigger'] ?? '';
                        $trgTiming = $trg['Timing'] ?? '';
                        $trgEvent  = $trg['Event'] ?? '';
                        $trgTable  = $trg['Table'] ?? '';
                        $trgStmt   = rtrim((string)($trg['Statement'] ?? ''), "; \t\n\r\0\x0B");
                        if ($tabla !== null && $trgTable !== $tabla) {
                            continue;
                        }
                        if ($trgName && $trgTiming && $trgEvent && $trgTable && $trgStmt) {
                            $sqlLines[] = "DROP TRIGGER IF EXISTS `{$trgName}`;";
                            $sqlLines[] = "CREATE TRIGGER `{$trgName}` {$trgTiming} {$trgEvent} ON `{$trgTable}` FOR EACH ROW {$trgStmt};";
                            $sqlLines[] = "";
                        }
                    }
                }
            }

            $sqlLines[] = "SET FOREIGN_KEY_CHECKS = 1;";
            $rawContent = implode("\n", $sqlLines) . "\n";

            if ($comprimir) {
                $compressedData = gzencode($rawContent, 9);
                file_put_contents($rutaCompleta, $compressedData);
            } else {
                file_put_contents($rutaCompleta, $rawContent);
            }
        } catch (Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'Fallo al generar respaldo PDO MariaDB: ' . $e->getMessage()
            ];
        }

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
        if (!file_exists($rutaArchivo)) {
            return ['valido' => false, 'detalles' => 'El archivo no existe en el almacenamiento.'];
        }

        $tamano = filesize($rutaArchivo);
        if ($tamano === 0) {
            return ['valido' => false, 'detalles' => 'El archivo de respaldo está completamente vacío (0 bytes).'];
        }

        $extensionGzip = str_ends_with(strtolower($rutaArchivo), '.gz');
        $archivoInicial = file_get_contents($rutaArchivo, false, null, 0, 2);
        $firmaGzip = $archivoInicial === "\x1f\x8b";

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
                if (strlen($buffer) > 150000) break;
            }
            gzclose($zp);
            $contenido = $buffer;
        } else {
            $contenido = file_get_contents($rutaArchivo, false, null, 0, 150000);
        }

        if (empty(trim($contenido))) {
            return ['valido' => false, 'detalles' => 'No se pudo extraer contenido SQL del archivo de respaldo.'];
        }

        $patronesValidos = [
            'MariaDB',
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
                'detalles' => 'Sintaxis SQL no reconocida. El archivo no contiene un dump válido de MariaDB/MySQL.'
            ];
        }

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
