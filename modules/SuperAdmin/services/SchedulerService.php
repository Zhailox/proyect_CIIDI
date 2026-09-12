<?php
// modules/SuperAdmin/services/SchedulerService.php

require_once CORE_PATH . 'Security/Auth.php';
require_once __DIR__ . '/BackupService.php';

class SchedulerService {

    private static function getConfigFile(): string {
        $dir = defined('STORAGE_PATH') ? STORAGE_PATH : __DIR__ . '/../../../storage/';
        return $dir . 'scheduler_tasks.json';
    }

    private static function getDefaultTasks(): array {
        return [
            'limpieza_temporales' => [
                'id' => 'limpieza_temporales',
                'nombre' => 'Limpieza de Archivos Temporales & Caché',
                'descripcion' => 'Elimina archivos temporales en storage/tmp/, cachés expiradas y residuos de descargas.',
                'expresion_cron' => '0 3 * * *', // Todos los días a las 3:00 AM
                'estado' => 'activo',
                'ultima_ejecucion' => null,
                'resultado_ultimo' => 'Pendiente de primera ejecución',
                'script' => 'limpiarTemporales'
            ],
            'backup_automatico_medianoche' => [
                'id' => 'backup_automatico_medianoche',
                'nombre' => 'Respaldo Automático de Medianoche (PostgreSQL Dump)',
                'descripcion' => 'Genera un backup completo comprimido (.sql.gz) de la base de datos y purga respaldos antiguos.',
                'expresion_cron' => '0 0 * * *', // Todos los días a las 00:00
                'estado' => 'activo',
                'ultima_ejecucion' => null,
                'resultado_ultimo' => 'Pendiente de primera ejecución',
                'script' => 'ejecutarBackupAutomatico'
            ],
            'purga_logs_viejos' => [
                'id' => 'purga_logs_viejos',
                'nombre' => 'Optimización y Rotación de Logs de Auditoría',
                'descripcion' => 'Mantener los logs de auditoría dentro de un límite saludable de almacenamiento.',
                'expresion_cron' => '0 4 * * 0', // Todos los domingos a las 4:00 AM
                'estado' => 'activo',
                'ultima_ejecucion' => null,
                'resultado_ultimo' => 'Pendiente de primera ejecución',
                'script' => 'purgarLogs'
            ]
        ];
    }

    public static function obtenerTareas(): array {
        $configFile = self::getConfigFile();
        if (!file_exists($configFile)) {
            $default = self::getDefaultTasks();
            self::guardarTareas($default);
            return $default;
        }

        $content = json_decode(file_get_contents($configFile), true);
        if (!is_array($content)) {
            return self::getDefaultTasks();
        }

        return array_merge(self::getDefaultTasks(), $content);
    }

    public static function guardarTareas(array $tareas): bool {
        $configFile = self::getConfigFile();
        $dir = dirname($configFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return (bool) file_put_contents($configFile, json_encode($tareas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public static function alternarEstado(string $idTarea, string $nuevoEstado): bool {
        $tareas = self::obtenerTareas();
        if (isset($tareas[$idTarea])) {
            $tareas[$idTarea]['estado'] = $nuevoEstado;
            return self::guardarTareas($tareas);
        }
        return false;
    }

    public static function guardarTareaCustom(string $id, string $nombre, string $descripcion, string $cron, string $script, ?string $estado = 'activo'): bool {
        $tareas = self::obtenerTareas();
        $esNueva = !isset($tareas[$id]);

        $tareas[$id] = [
            'id'               => $id,
            'nombre'           => $nombre,
            'descripcion'      => $descripcion,
            'expresion_cron'   => $cron,
            'estado'           => $estado,
            'ultima_ejecucion' => $tareas[$id]['ultima_ejecucion'] ?? null,
            'resultado_ultimo' => $tareas[$id]['resultado_ultimo'] ?? 'Pendiente de primera ejecución',
            'script'           => $script
        ];

        return self::guardarTareas($tareas);
    }

    public static function eliminarTarea(string $idTarea): bool {
        $tareas = self::obtenerTareas();
        if (isset($tareas[$idTarea])) {
            unset($tareas[$idTarea]);
            return self::guardarTareas($tareas);
        }
        return false;
    }

    public static function ejecutarTareaManual(string $idTarea): array {
        $tareas = self::obtenerTareas();
        if (!isset($tareas[$idTarea])) {
            return ['exito' => false, 'mensaje' => 'La tarea especificada no existe.'];
        }

        $tarea = $tareas[$idTarea];
        $metodo = $tarea['script'];

        if (!method_exists(__CLASS__, $metodo)) {
            return ['exito' => false, 'mensaje' => "El script de ejecución '{$metodo}' no está definido."];
        }

        $inicio = microtime(true);
        $res = self::$metodo();
        $duracion = round((microtime(true) - $inicio) * 1000, 2);

        $resultadoTexto = ($res['exito'] ? 'ÉXITO' : 'ERROR') . " ({$duracion}ms): " . $res['mensaje'];

        $tareas[$idTarea]['ultima_ejecucion'] = date('Y-m-d H:i:s');
        $tareas[$idTarea]['resultado_ultimo'] = $resultadoTexto;
        self::guardarTareas($tareas);

        AuditLogger::registrar(
            $res['exito'] ? 'INFO' : 'ERROR',
            'SuperAdmin',
            'Ejecución Tarea Programada',
            "Tarea '{$tarea['nombre']}': {$resultadoTexto}"
        );

        return [
            'exito' => $res['exito'],
            'duracion' => $duracion,
            'mensaje' => $res['mensaje']
        ];
    }

    // =========================================================================
    // IMPLEMENTACIÓN DE LOS SCRIPTS DE LAS TAREAS DE FONDO
    // =========================================================================

    public static function limpiarTemporales(): array {
        $storageDir = defined('STORAGE_PATH') ? STORAGE_PATH : __DIR__ . '/../../../storage/';
        $tmpDir = $storageDir . 'tmp/';
        $archivosBorrados = 0;

        if (is_dir($tmpDir)) {
            $archivos = glob($tmpDir . '*');
            foreach ($archivos as $a) {
                if (is_file($a)) {
                    @unlink($a);
                    $archivosBorrados++;
                }
            }
        }

        // Borrar cachés del sistema
        $cacheFile = $storageDir . '.telemetry_cache.json';
        if (file_exists($cacheFile)) {
            @unlink($cacheFile);
            $archivosBorrados++;
        }

        return [
            'exito' => true,
            'mensaje' => "Limpieza completada. Se depuraron {$archivosBorrados} archivos temporales y cachés expiradas."
        ];
    }

    public static function ejecutarBackupAutomatico(): array {
        $res = BackupService::crearBackup(true, false);
        return [
            'exito' => $res['exito'],
            'mensaje' => $res['mensaje']
        ];
    }

    public static function purgarLogs(): array {
        $storageDir = defined('STORAGE_PATH') ? STORAGE_PATH : __DIR__ . '/../../../storage/';
        $archivoAudit = $storageDir . 'system_audit.json';
        $purgados = 0;

        if (file_exists($archivoAudit)) {
            $logs = json_decode(file_get_contents($archivoAudit), true) ?: [];
            if (count($logs) > 2000) {
                // Conservar únicamente los 1000 más recientes
                $mantenidos = array_slice($logs, -1000);
                $purgados = count($logs) - count($mantenidos);
                file_put_contents($archivoAudit, json_encode($mantenidos, JSON_PRETTY_PRINT));
            }
        }

        return [
            'exito' => true,
            'mensaje' => "Optimización de logs completada. {$purgados} registros obsoletos purgados de la auditoría."
        ];
    }

    /**
     * Comprueba la disponibilidad del sistema Cron o Task Scheduler
     */
    public static function diagnosticarEntornoCron(): array {
        $esWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $cronDetectado = false;
        $detalles = '';

        if (!$esWindows) {
            $salida = [];
            $codigo = 0;
            @exec('crontab -l 2>&1', $salida, $codigo);
            if ($codigo === 0) {
                $cronDetectado = true;
                $detalles = 'Servicio Cron de Linux activo y accesible.';
            } else {
                $detalles = 'Linux detectado. Se requiere añadir el runner al crontab del sistema.';
            }
        } else {
            $detalles = 'Entorno Windows detectado. El programador se ejecuta mediante invocaciones HTTP/CLI o Task Scheduler de Windows.';
        }

        $storageDir = defined('STORAGE_PATH') ? STORAGE_PATH : __DIR__ . '/../../../storage/';
        $runnerPath = realpath($storageDir . 'cron_runner.php') ?: ($storageDir . 'cron_runner.php');

        return [
            'es_windows' => $esWindows,
            'cron_activo' => $cronDetectado,
            'detalles' => $detalles,
            'runner_path' => $runnerPath,
            'comando_sugerido_linux' => "* * * * * php {$runnerPath} >/dev/null 2>&1"
        ];
    }
}
