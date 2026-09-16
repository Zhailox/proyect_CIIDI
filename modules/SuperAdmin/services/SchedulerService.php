<?php
// modules/SuperAdmin/services/SchedulerService.php

require_once CORE_PATH . 'Security/Auth.php';
require_once __DIR__ . '/BackupService.php';

class SchedulerService {

    private static function getStorageDir(): string {
        $dir = defined('STORAGE_PATH') ? STORAGE_PATH : __DIR__ . '/../../../storage/';
        return $dir;
    }

    private static function getConfigFile(): string {
        return self::getStorageDir() . 'scheduler_tasks.json';
    }

    public static function getScriptsDir(): string {
        $dir = self::getStorageDir() . 'scheduler/scripts/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir;
    }

    public static function getLogsDir(): string {
        $dir = self::getStorageDir() . 'scheduler/logs/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir;
    }

    private static function getDefaultTasks(): array {
        return [
            'limpieza_temporales' => [
                'id' => 'limpieza_temporales',
                'nombre' => 'Limpieza de Archivos Temporales & Caché',
                'descripcion' => 'Elimina archivos temporales en storage/tmp/, cachés expiradas y residuos de descargas.',
                'expresion_cron' => '0 3 * * *',
                'tipo_ejecucion' => 'metodo_interno', // metodo_interno | script_archivo | comando_cli
                'script' => 'limpiarTemporales',
                'comando_custom' => '',
                'archivo_script' => '',
                'timeout_segundos' => 60,
                'estado' => 'activo',
                'ultima_ejecucion' => null,
                'resultado_ultimo' => 'Pendiente de primera ejecución'
            ],
            'backup_automatico_medianoche' => [
                'id' => 'backup_automatico_medianoche',
                'nombre' => 'Respaldo Automático de Medianoche (PostgreSQL Dump)',
                'descripcion' => 'Genera un backup completo comprimido (.sql.gz) de la base de datos y purga respaldos antiguos.',
                'expresion_cron' => '0 0 * * *',
                'tipo_ejecucion' => 'metodo_interno',
                'script' => 'ejecutarBackupAutomatico',
                'comando_custom' => '',
                'archivo_script' => '',
                'timeout_segundos' => 300,
                'estado' => 'activo',
                'ultima_ejecucion' => null,
                'resultado_ultimo' => 'Pendiente de primera ejecución'
            ],
            'purga_logs_viejos' => [
                'id' => 'purga_logs_viejos',
                'nombre' => 'Optimización y Rotación de Logs de Auditoría',
                'descripcion' => 'Mantener los logs de auditoría dentro de un límite saludable de almacenamiento.',
                'expresion_cron' => '0 4 * * 0',
                'tipo_ejecucion' => 'metodo_interno',
                'script' => 'purgarLogs',
                'comando_custom' => '',
                'archivo_script' => '',
                'timeout_segundos' => 60,
                'estado' => 'activo',
                'ultima_ejecucion' => null,
                'resultado_ultimo' => 'Pendiente de primera ejecución'
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

    /**
     * Guarda o actualiza una tarea programada soportando Scripts subidos, Comandos CLI y Métodos Internos.
     */
    public static function guardarTareaCompleta(array $datos, ?array $archivoSubido = null): array {
        $id = trim($datos['id'] ?? '');
        $nombre = trim($datos['nombre'] ?? '');
        $descripcion = trim($datos['descripcion'] ?? '');
        $cron = trim($datos['expresion_cron'] ?? '* * * * *');
        $tipoEjecucion = trim($datos['tipo_ejecucion'] ?? 'metodo_interno');
        $scriptMetodo = trim($datos['script'] ?? '');
        $comandoCustom = trim($datos['comando_custom'] ?? '');
        $timeout = (int)($datos['timeout_segundos'] ?? 60);
        $estado = trim($datos['estado'] ?? 'activo');

        if (empty($id)) {
            $id = 'tarea_' . time() . '_' . rand(100, 999);
        }

        $tareas = self::obtenerTareas();
        $archivoScriptNombre = $tareas[$id]['archivo_script'] ?? '';

        // Procesar subida de archivo script (.sh, .bat, .ps1, .php, .py)
        if ($tipoEjecucion === 'script_archivo' && $archivoSubido && $archivoSubido['error'] === UPLOAD_ERR_OK) {
            $origName = basename($archivoSubido['name']);
            $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
            $permitidas = ['sh', 'bat', 'ps1', 'php', 'py', 'sql'];

            if (!in_array($ext, $permitidas, true)) {
                return ['exito' => false, 'mensaje' => "Extensión '.{$ext}' no permitida. Formatos válidos: .sh, .bat, .ps1, .php, .py, .sql"];
            }

            $scriptDir = self::getScriptsDir();
            $targetName = "script_{$id}.{$ext}";
            $targetPath = $scriptDir . $targetName;

            if (move_uploaded_file($archivoSubido['tmp_name'], $targetPath)) {
                $archivoScriptNombre = $targetName;
                // Asignar permisos de ejecución en sistemas Unix / Linux
                if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
                    @chmod($targetPath, 0755);
                }
            } else {
                return ['exito' => false, 'mensaje' => 'Fallo al guardar el archivo de script en el servidor.'];
            }
        }

        $tareas[$id] = [
            'id'               => $id,
            'nombre'           => $nombre,
            'descripcion'      => $descripcion,
            'expresion_cron'   => $cron,
            'tipo_ejecucion'   => $tipoEjecucion,
            'script'           => $scriptMetodo,
            'comando_custom'   => $comandoCustom,
            'archivo_script'   => $archivoScriptNombre,
            'timeout_segundos' => $timeout > 0 ? $timeout : 60,
            'estado'           => $estado,
            'ultima_ejecucion' => $tareas[$id]['ultima_ejecucion'] ?? null,
            'resultado_ultimo' => $tareas[$id]['resultado_ultimo'] ?? 'Pendiente de primera ejecución'
        ];

        if (self::guardarTareas($tareas)) {
            AuditLogger::registrar('INFO', 'SuperAdmin', 'Guardar Tarea Programada', "Tarea '{$nombre}' ({$id}) guardada correctamente.");
            return ['exito' => true, 'mensaje' => "Tarea programada '{$nombre}' guardada exitosamente.", 'id' => $id];
        }

        return ['exito' => false, 'mensaje' => 'Error al escribir en el archivo de tareas.'];
    }

    public static function eliminarTarea(string $idTarea): bool {
        $tareas = self::obtenerTareas();
        if (isset($tareas[$idTarea])) {
            // Eliminar archivo de script subido si existía
            if (!empty($tareas[$idTarea]['archivo_script'])) {
                $scriptPath = self::getScriptsDir() . $tareas[$idTarea]['archivo_script'];
                if (file_exists($scriptPath)) @unlink($scriptPath);
            }
            // Eliminar log de ejecución si existía
            $logPath = self::getLogsDir() . "task_{$idTarea}.log";
            if (file_exists($logPath)) @unlink($logPath);

            unset($tareas[$idTarea]);
            return self::guardarTareas($tareas);
        }
        return false;
    }

    /**
     * Ejecuta una tarea manual o programada (Método Interno, Script Archivo o Comando CLI Multiplataforma).
     */
    public static function ejecutarTareaManual(string $idTarea): array {
        $tareas = self::obtenerTareas();
        if (!isset($tareas[$idTarea])) {
            return ['exito' => false, 'mensaje' => 'La tarea especificada no existe.'];
        }

        $tarea = $tareas[$idTarea];
        $tipo = $tarea['tipo_ejecucion'] ?? 'metodo_interno';
        $inicio = microtime(true);
        $res = ['exito' => false, 'mensaje' => 'Tipo de ejecución no reconocido.', 'salida' => ''];

        if ($tipo === 'metodo_interno') {
            $metodo = $tarea['script'] ?? '';
            if (method_exists(__CLASS__, $metodo)) {
                $res = self::$metodo();
            } else {
                $res = ['exito' => false, 'mensaje' => "El método interno '{$metodo}' no está definido.", 'salida' => ''];
            }
        } elseif ($tipo === 'script_archivo') {
            $archivo = $tarea['archivo_script'] ?? '';
            $scriptPath = self::getScriptsDir() . $archivo;
            if (file_exists($scriptPath)) {
                $res = self::ejecutarArchivoScript($scriptPath);
            } else {
                $res = ['exito' => false, 'mensaje' => "El archivo de script '{$archivo}' no existe en storage/scheduler/scripts/.", 'salida' => ''];
            }
        } elseif ($tipo === 'comando_cli') {
            $comando = $tarea['comando_custom'] ?? '';
            if (!empty($comando)) {
                $res = self::ejecutarComandoCLI($comando);
            } else {
                $res = ['exito' => false, 'mensaje' => 'No se especificó un comando CLI ejecutable.', 'salida' => ''];
            }
        }

        $duracion = round((microtime(true) - $inicio) * 1000, 2);
        $resultadoTexto = ($res['exito'] ? 'ÉXITO' : 'ERROR') . " ({$duracion}ms): " . $res['mensaje'];

        // Guardar log detallado de salida (stdout / stderr)
        $logPath = self::getLogsDir() . "task_{$idTarea}.log";
        $logContent = "========================================================\n"
                    . "REGISTRO DE EJECUCIÓN: {$tarea['nombre']} [{$idTarea}]\n"
                    . "FECHA/HORA: " . date('Y-m-d H:i:s') . "\n"
                    . "DURACIÓN: {$duracion} ms | ESTADO: " . ($res['exito'] ? 'OK' : 'FAIL') . "\n"
                    . "========================================================\n\n"
                    . "MENSAJE: {$res['mensaje']}\n\n"
                    . "--- SALIDA ESTÁNDAR / CONSOLA (STDOUT/STDERR) ---\n"
                    . ($res['salida'] ?? 'Sin salida por consola.') . "\n";
        file_put_contents($logPath, $logContent);

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
            'mensaje' => $res['mensaje'],
            'salida' => $res['salida'] ?? ''
        ];
    }

    /**
     * Ejecución multiplataforma (Windows/Linux) de scripts subidos (.sh, .bat, .ps1, .php, .py)
     */
    private static function ejecutarArchivoScript(string $rutaCompleta): array {
        $ext = strtolower(pathinfo($rutaCompleta, PATHINFO_EXTENSION));
        $esWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $cmd = '';

        switch ($ext) {
            case 'php':
                $phpBin = $esWindows ? (defined('PHP_BINARY') ? '"' . PHP_BINARY . '"' : 'php') : 'php';
                $cmd = "{$phpBin} \"{$rutaCompleta}\"";
                break;
            case 'py':
                $pythonBin = $esWindows ? 'python' : 'python3';
                $cmd = "{$pythonBin} \"{$rutaCompleta}\"";
                break;
            case 'bat':
            case 'cmd':
                $cmd = "cmd.exe /c \"{$rutaCompleta}\"";
                break;
            case 'ps1':
                $cmd = "powershell.exe -ExecutionPolicy Bypass -File \"{$rutaCompleta}\"";
                break;
            case 'sh':
                if ($esWindows) {
                    $cmd = "bash \"{$rutaCompleta}\"";
                } else {
                    @chmod($rutaCompleta, 0755);
                    $cmd = "/bin/bash \"{$rutaCompleta}\"";
                }
                break;
            case 'sql':
                $psqlPath = Connection::getPsqlPath();
                $creds = Connection::getCredentials();
                putenv("PGPASSWORD={$creds['pass']}");
                $cmd = "{$psqlPath} -h {$creds['host']} -p {$creds['port']} -U {$creds['user']} -d {$creds['db']} -f \"{$rutaCompleta}\"";
                break;
            default:
                $cmd = "\"{$rutaCompleta}\"";
                break;
        }

        return self::ejecutarComandoCLI($cmd);
    }

    /**
     * Ejecución de comandos CLI con captura de código de salida (exit code) y consola.
     */
    private static function ejecutarComandoCLI(string $comando): array {
        $salidaLines = [];
        $codigoRetorno = 0;

        $comandoConStderr = $comando . " 2>&1";
        exec($comandoConStderr, $salidaLines, $codigoRetorno);

        putenv("PGPASSWORD="); // Limpiar por seguridad
        $salidaTexto = implode("\n", $salidaLines);

        if ($codigoRetorno === 0) {
            return [
                'exito' => true,
                'mensaje' => "Comando ejecutado con éxito (Código 0).",
                'salida' => $salidaTexto ?: 'Ejecutado silenciosamente sin salida.'
            ];
        }

        return [
            'exito' => false,
            'mensaje' => "Error de ejecución CLI (Código {$codigoRetorno}).",
            'salida' => $salidaTexto ?: "El proceso retornó código de error {$codigoRetorno}."
        ];
    }

    public static function obtenerLogTarea(string $idTarea): string {
        $logPath = self::getLogsDir() . "task_{$idTarea}.log";
        if (file_exists($logPath)) {
            return file_get_contents($logPath);
        }
        return "No existe registro de consola histórico para la tarea ID '{$idTarea}'.";
    }

    /**
     * Evalúa si una expresión Cron debe ejecutarse según el tiempo transcurrido.
     */
    public static function correspondeEjecutar(string $cronExpr, ?string $ultimaEjecucion): bool {
        if (empty($ultimaEjecucion)) return true;

        $tsUltima = strtotime($ultimaEjecucion);
        $ahora = time();
        $diffSegundos = $ahora - $tsUltima;

        // Si se ejecutó hace menos de 55 segundos, omitir para evitar duplicidad en el mismo minuto
        if ($diffSegundos < 55) return false;

        $partes = explode(' ', trim($cronExpr));
        if (count($partes) !== 5) return ($diffSegundos >= 3600); // Fallback cada hora si cron no es válido

        list($min, $hora, $diaM, $mes, $diaS) = $partes;
        $cMin = (int)date('i');
        $cHora = (int)date('H');
        $cDiaM = (int)date('d');
        $cMes = (int)date('m');
        $cDiaS = (int)date('w');

        return self::matchCampoCron($min, $cMin) &&
               self::matchCampoCron($hora, $cHora) &&
               self::matchCampoCron($diaM, $cDiaM) &&
               self::matchCampoCron($mes, $cMes) &&
               self::matchCampoCron($diaS, $cDiaS);
    }

    private static function matchCampoCron(string $pattern, int $valActual): bool {
        if ($pattern === '*') return true;
        if (strpos($pattern, '*/') === 0) {
            $step = (int)substr($pattern, 2);
            return ($step > 0 && ($valActual % $step === 0));
        }
        if (strpos($pattern, ',') !== false) {
            $valores = array_map('intval', explode(',', $pattern));
            return in_array($valActual, $valores, true);
        }
        return ((int)$pattern === $valActual);
    }

    // =========================================================================
    // MÉTODOS INTERNOS LEGACY DEL CORE
    // =========================================================================

    public static function limpiarTemporales(): array {
        $storageDir = self::getStorageDir();
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

        $cacheFile = $storageDir . '.telemetry_cache.json';
        if (file_exists($cacheFile)) {
            @unlink($cacheFile);
            $archivosBorrados++;
        }

        return [
            'exito' => true,
            'mensaje' => "Limpieza completada. Depurados {$archivosBorrados} archivos temporales y cachés.",
            'salida' => "Archivos depurados en {$tmpDir}: {$archivosBorrados}"
        ];
    }

    public static function ejecutarBackupAutomatico(): array {
        $res = BackupService::crearBackup('sql.gz', false, null, 'auto_cron_backup');
        return [
            'exito' => $res['exito'],
            'mensaje' => $res['mensaje'],
            'salida' => "Backup generado: " . ($res['nombre'] ?? 'N/A')
        ];
    }

    public static function purgarLogs(): array {
        $storageDir = self::getStorageDir();
        $archivoAudit = $storageDir . 'system_audit.json';
        $purgados = 0;

        if (file_exists($archivoAudit)) {
            $logs = json_decode(file_get_contents($archivoAudit), true) ?: [];
            if (count($logs) > 2000) {
                $mantenidos = array_slice($logs, 0, 1000);
                $purgados = count($logs) - count($mantenidos);
                file_put_contents($archivoAudit, json_encode($mantenidos, JSON_PRETTY_PRINT));
            }
        }

        return [
            'exito' => true,
            'mensaje' => "Optimización de logs completada. {$purgados} registros obsoletos purgados.",
            'salida' => "Registros purgados de system_audit.json: {$purgados}"
        ];
    }

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
            $detalles = 'Entorno Windows detectado. El programador admite ejecuciones mediante Task Scheduler, PowerShell o CLI de PHP.';
        }

        $runnerPath = self::getStorageDir() . 'cron_runner.php';

        return [
            'es_windows' => $esWindows,
            'cron_activo' => $cronDetectado,
            'detalles' => $detalles,
            'runner_path' => $runnerPath,
            'comando_sugerido_linux' => "* * * * * php {$runnerPath} >/dev/null 2>&1",
            'comando_sugerido_windows' => "schtasks /create /tn \"CIIDI_CronRunner\" /tr \"php {$runnerPath}\" /sc minute /mo 1"
        ];
    }
}

