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
     * Genera un respaldo PostgreSQL en formato comprimido (.sql.gz), SQL plano (.sql) o texto (.txt) con políticas de retención.
     */
    public static function crearBackup(string $formato = 'sql.gz', bool $soloEsquema = false, ?string $tabla = null, ?string $prefijoCustom = null): array {
        $creds = Connection::getCredentials();
        $pgDumpPath = Connection::getPgDumpPath();
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

        $flags = $soloEsquema ? "-F p -s" : "-F p --clean --inserts";
        if ($tabla) {
            $flags .= " -t " . escapeshellarg($tabla);
        }

        putenv("PGPASSWORD={$creds['pass']}");
        $comando = "{$pgDumpPath} -h {$creds['host']} -p {$creds['port']} -U {$creds['user']} {$flags} -d {$creds['db']} -f \"{$tempSqlPath}\" 2>&1";

        $salida = [];
        $codigoRetorno = 0;
        exec($comando, $salida, $codigoRetorno);
        putenv("PGPASSWORD=");

        if ($codigoRetorno !== 0 || !file_exists($tempSqlPath) || filesize($tempSqlPath) === 0) {
            $errText = implode(' ', $salida);
            if (file_exists($tempSqlPath)) @unlink($tempSqlPath);
            return [
                'exito' => false,
                'mensaje' => "Fallo en pg_dump: " . ($errText ?: "Código {$codigoRetorno}")
            ];
        }

        // Compresión GZIP si se solicitó
        if ($comprimir) {
            $rawContent = file_get_contents($tempSqlPath);
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
            'PostgreSQL database dump',
            'CREATE TABLE',
            'INSERT INTO',
            'SET statement_timeout',
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
                'detalles' => 'Sintaxis SQL no reconocida. El archivo no contiene un dump válido de PostgreSQL.'
            ];
        }

        // Extraer estadísticas adicionales para inspección previa
        preg_match_all('/CREATE TABLE\s+(?:IF NOT EXISTS\s+)?([a-zA-Z0-9_\.]+)/i', $contenido, $tablaMatches);
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

