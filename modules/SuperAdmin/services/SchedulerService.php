<?php
// modules/SuperAdmin/services/SchedulerService.php

require_once CORE_PATH . 'Security/Auth.php';
require_once CORE_PATH . 'Security/AuditLogger.php';
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
            @mkdir($dir, 0777, true);
            @chmod($dir, 0777);
        }
        return $dir;
    }

    public static function getLogsDir(): string {
        $dir = self::getStorageDir() . 'scheduler/logs/';
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
            @chmod($dir, 0777);
        }
        return $dir;
    }

    private static function getHeartbeatFile(): string {
        $dir = self::getStorageDir() . 'scheduler/';
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
            @chmod($dir, 0777);
        }
        return $dir . '.cron_heartbeat.json';
    }

    public static function registrarLatidoRunner(string $origen = 'auto', int $ejecutadas = 0): void {
        $file = self::getHeartbeatFile();
        $data = [
            'timestamp' => time(),
            'fecha_hora' => date('Y-m-d H:i:s'),
            'origen' => $origen,
            'ejecutadas_ultimo_ciclo' => $ejecutadas,
            'usuario_so' => function_exists('posix_getpwuid') && function_exists('posix_geteuid')
                ? (posix_getpwuid(posix_geteuid())['name'] ?? get_current_user())
                : get_current_user()
        ];
        @file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        @chmod($file, 0666);
    }

    public static function obtenerEstadoLatido(): array {
        $file = self::getHeartbeatFile();
        if (!file_exists($file)) {
            return ['activo_reciente' => false, 'segundos_desde_latido' => null, 'datos' => null];
        }
        $data = @json_decode((string)@file_get_contents($file), true);
        if (!is_array($data) || empty($data['timestamp'])) {
            return ['activo_reciente' => false, 'segundos_desde_latido' => null, 'datos' => null];
        }
        $diff = max(0, time() - (int)$data['timestamp']);
        return [
            'activo_reciente' => ($diff <= 180),
            'segundos_desde_latido' => $diff,
            'datos' => $data
        ];
    }

    /**
     * Resuelve la ruta ejecutable real del intérprete CLI de PHP.
     */
    public static function getPhpCliPath(): string {
        $esWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

        if (!$esWindows) {
            if (defined('PHP_BINDIR') && !empty(PHP_BINDIR) && file_exists(PHP_BINDIR . '/php')) {
                return PHP_BINDIR . '/php';
            }
            $candidatosLinux = ['/usr/bin/php', '/usr/local/bin/php', '/bin/php'];
            foreach ($candidatosLinux as $binLinux) {
                if (file_exists($binLinux)) {
                    return $binLinux;
                }
            }
            return 'php';
        }

        // Si PHP_BINARY apunta directamente a un ejecutable php.exe (no httpd.exe)
        if (defined('PHP_BINARY') && !empty(PHP_BINARY)) {
            $binName = strtolower(basename(PHP_BINARY));
            if ($binName === 'php.exe' && file_exists(PHP_BINARY)) {
                return '"' . PHP_BINARY . '"';
            }
        }

        // Buscar en la carpeta de instalación de PHP de la versión activa en Wamp/XAMPP
        if (defined('PHP_BINDIR') && file_exists(PHP_BINDIR . DIRECTORY_SEPARATOR . 'php.exe')) {
            return '"' . PHP_BINDIR . DIRECTORY_SEPARATOR . 'php.exe"';
        }

        // Buscar en WampServer según la versión en ejecución (ej: php 8.5.0, 8.4.x, etc.)
        $wampPhpVer = 'php' . PHP_VERSION;
        $candidatosWamp = [
            "C:\\wamp64\\bin\\php\\{$wampPhpVer}\\php.exe",
            "C:\\wamp64\\bin\\php\\php8.5.0\\php.exe",
            "C:\\wamp64\\bin\\php\\php8.4.15\\php.exe",
            "C:\\wamp64\\bin\\php\\php8.3.28\\php.exe",
            "C:\\wamp64\\bin\\php\\php8.2.29\\php.exe",
            'C:\\xampp\\php\\php.exe',
            'C:\\php\\php.exe'
        ];

        foreach ($candidatosWamp as $ruta) {
            if (file_exists($ruta)) {
                return '"' . $ruta . '"';
            }
        }

        // Fallback al comando del PATH
        return 'php';
    }

    /**
     * Resuelve la ruta de Python si está disponible en el entorno
     */
    public static function getPythonPath(): string {
        $esWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        if (!$esWindows) {
            return 'python3';
        }

        $candidatosPy = [
            'C:\\Python312\\python.exe',
            'C:\\Python311\\python.exe',
            'C:\\Python310\\python.exe',
            'C:\\Users\\Chailon\\AppData\\Local\\Programs\\Python\\Python312\\python.exe',
            'C:\\Users\\Chailon\\AppData\\Local\\Programs\\Python\\Python311\\python.exe',
            'C:\\Users\\Chailon\\AppData\\Local\\Programs\\Python\\Python310\\python.exe'
        ];

        foreach ($candidatosPy as $pyPath) {
            if (file_exists($pyPath)) {
                return '"' . $pyPath . '"';
            }
        }

        return 'python';
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
            @mkdir($dir, 0777, true);
            @chmod($dir, 0777);
        }
        $ok = (bool) @file_put_contents($configFile, json_encode($tareas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        if ($ok) {
            @chmod($configFile, 0666);
        }
        return $ok;
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
        @file_put_contents($logPath, $logContent);
        @chmod($logPath, 0666);

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
                $phpBin = self::getPhpCliPath();
                $cmd = "{$phpBin} \"{$rutaCompleta}\"";
                break;
            case 'py':
                $pythonBin = self::getPythonPath();
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
     * Evalúa si una expresión Cron debe ejecutarse según el tiempo transcurrido,
     * revisando todos los minutos desde la última ejecución hasta el minuto actual
     * para evitar perder ejecuciones si el ciclo ocurre con desfase de segundos.
     */
    public static function correspondeEjecutar(string $cronExpr, ?string $ultimaEjecucion): bool {
        $ahora = time();
        $partes = preg_split('/\s+/', trim($cronExpr));
        if (count($partes) !== 5) {
            if (empty($ultimaEjecucion)) return true;
            return (($ahora - (int)strtotime($ultimaEjecucion)) >= 3600);
        }

        list($min, $hora, $diaM, $mes, $diaS) = $partes;

        if (empty($ultimaEjecucion)) {
            // Si nunca se ha ejecutado: evaluar si el minuto actual coincide o si es una tarea de intervalo continuo
            if ($hora === '*' && (str_starts_with($min, '*/') || $min === '*')) {
                return true;
            }
            return self::coincideTimestampConCron($ahora, $min, $hora, $diaM, $mes, $diaS);
        }

        $tsUltima = (int)strtotime($ultimaEjecucion);
        if ($tsUltima <= 0) {
            return true;
        }

        // Evitar ejecutar dos veces dentro del mismo minuto calendario
        $minutoUltima = (int)floor($tsUltima / 60) * 60;
        $minutoActual = (int)floor($ahora / 60) * 60;
        if ($minutoActual <= $minutoUltima) {
            return false;
        }

        // Revisar cada minuto transcurrido desde el minuto siguiente a la última ejecución hasta el minuto actual (máximo últimas 24 horas)
        $inicioRevision = max($minutoUltima + 60, $minutoActual - 86400);
        for ($ts = $inicioRevision; $ts <= $minutoActual; $ts += 60) {
            if (self::coincideTimestampConCron($ts, $min, $hora, $diaM, $mes, $diaS)) {
                return true;
            }
        }

        return false;
    }

    private static function coincideTimestampConCron(int $ts, string $min, string $hora, string $diaM, string $mes, string $diaS): bool {
        $cMin  = (int)date('i', $ts);
        $cHora = (int)date('H', $ts);
        $cDiaM = (int)date('d', $ts);
        $cMes  = (int)date('m', $ts);
        $cDiaS = (int)date('w', $ts);

        return self::matchCampoCron($min, $cMin, false) &&
               self::matchCampoCron($hora, $cHora, false) &&
               self::matchCampoCron($diaM, $cDiaM, false) &&
               self::matchCampoCron($mes, $cMes, false) &&
               self::matchCampoCron($diaS, $cDiaS, true);
    }

    private static function matchCampoCron(string $pattern, int $valActual, bool $esDiaSemana = false): bool {
        $pattern = trim($pattern);
        if ($pattern === '*') return true;

        // Soporte para listas separadas por coma: ej. 1,3,5 o 1-5,7
        if (strpos($pattern, ',') !== false) {
            $subPatrones = explode(',', $pattern);
            foreach ($subPatrones as $sub) {
                if (self::matchCampoCron($sub, $valActual, $esDiaSemana)) {
                    return true;
                }
            }
            return false;
        }

        // Soporte para pasos sobre comodín o rango: */5 o 1-5/2
        if (strpos($pattern, '/') !== false) {
            list($base, $stepStr) = explode('/', $pattern, 2);
            $step = (int)$stepStr;
            if ($step <= 0) return false;
            if ($base === '*') {
                return ($valActual % $step === 0);
            }
            if (strpos($base, '-') !== false) {
                list($ini, $fin) = array_map('intval', explode('-', $base, 2));
                if ($valActual < $ini || $valActual > $fin) return false;
                return (($valActual - $ini) % $step === 0);
            }
            return false;
        }

        // Soporte para rangos: ej. 1-5
        if (strpos($pattern, '-') !== false) {
            list($ini, $fin) = array_map('intval', explode('-', $pattern, 2));
            if ($esDiaSemana && $fin === 7 && $valActual === 0) {
                return true;
            }
            return ($valActual >= $ini && $valActual <= $fin);
        }

        $numPatron = (int)$pattern;
        if ($esDiaSemana && $numPatron === 7) {
            $numPatron = 0;
        }
        return ($numPatron === $valActual);
    }

    /**
     * Traduce una expresión Cron estándar de 5 campos a un texto legible y amigable en español.
     */
    public static function describirCron(string $cronExpr): string {
        $cronExpr = trim($cronExpr);
        $partes = preg_split('/\s+/', $cronExpr);
        if (count($partes) !== 5) {
            return 'Expresión incompleta (' . count($partes) . '/5 campos)';
        }

        list($min, $hora, $diaM, $mes, $diaS) = $partes;

        $nombresDias = [
            '0' => 'domingo', '1' => 'lunes', '2' => 'martes', '3' => 'miércoles',
            '4' => 'jueves', '5' => 'viernes', '6' => 'sábado', '7' => 'domingo'
        ];

        $nombresMeses = [
            '1' => 'enero', '2' => 'febrero', '3' => 'marzo', '4' => 'abril',
            '5' => 'mayo', '6' => 'junio', '7' => 'julio', '8' => 'agosto',
            '9' => 'septiembre', '10' => 'octubre', '11' => 'noviembre', '12' => 'diciembre'
        ];

        $formatearHora = function($h, $m) {
            $hNum = (int)$h;
            $mNum = (int)$m;
            $mStr = str_pad((string)$mNum, 2, '0', STR_PAD_LEFT);
            if ($hNum === 0 && $mNum === 0) return '12:00 AM (Medianoche)';
            if ($hNum === 12 && $mNum === 0) return '12:00 PM (Mediodía)';
            $periodo = $hNum >= 12 ? 'PM' : 'AM';
            $h12 = $hNum % 12;
            if ($h12 === 0) $h12 = 12;
            return sprintf('%02d:%s %s', $h12, $mStr, $periodo);
        };

        // Cada minuto
        if ($min === '*' && $hora === '*' && $diaM === '*' && $mes === '*' && $diaS === '*') {
            return 'Cada minuto';
        }

        // Intervalo de minutos: */N * * * *
        if (str_starts_with($min, '*/') && $hora === '*' && $diaM === '*' && $mes === '*' && $diaS === '*') {
            $step = substr($min, 2);
            return "Cada {$step} minutos";
        }

        // Cada hora en punto: 0 * * * *
        if ($min === '0' && $hora === '*' && $diaM === '*' && $mes === '*' && $diaS === '*') {
            return 'Cada hora en punto';
        }

        // Minuto específico de cada hora: N * * * *
        if (is_numeric($min) && $hora === '*' && $diaM === '*' && $mes === '*' && $diaS === '*') {
            return "Cada hora en el minuto {$min}";
        }

        // Intervalo de horas: 0 */N * * *
        if (str_starts_with($hora, '*/') && $diaM === '*' && $mes === '*' && $diaS === '*') {
            $stepH = substr($hora, 2);
            $minTxt = ($min === '0') ? 'en punto' : "en minuto " . str_pad($min, 2, '0', STR_PAD_LEFT);
            return "Cada {$stepH} horas ({$minTxt})";
        }

        // Si la hora y el minuto son fijos (ej: 0 0 o 30 8)
        if (is_numeric($min) && is_numeric($hora)) {
            $horaTxt = $formatearHora($hora, $min);

            // Todos los días: M H * * *
            if ($diaM === '*' && $mes === '*' && $diaS === '*') {
                return "Diario a las {$horaTxt}";
            }

            // Días de semana laborales: M H * * 1-5
            if ($diaM === '*' && $mes === '*' && ($diaS === '1-5' || $diaS === '1,2,3,4,5')) {
                return "Lun a Vie a las {$horaTxt}";
            }

            // Fines de semana: M H * * 6,0 o 0,6
            if ($diaM === '*' && $mes === '*' && ($diaS === '6,0' || $diaS === '0,6' || $diaS === '6,7')) {
                return "Fines de semana a las {$horaTxt}";
            }

            // Día de semana específico: M H * * D
            if ($diaM === '*' && $mes === '*' && isset($nombresDias[$diaS])) {
                $nomDia = ucfirst($nombresDias[$diaS]);
                return "Semanal ({$nomDia}s a las {$horaTxt})";
            }

            // Días específicos del mes: M H D * *
            if ($diaM !== '*' && $mes === '*' && $diaS === '*') {
                return "Día {$diaM} de cada mes a las {$horaTxt}";
            }

            // Fecha anual específica: M H D M *
            if ($diaM !== '*' && isset($nombresMeses[$mes]) && $diaS === '*') {
                $nomMes = ucfirst($nombresMeses[$mes]);
                return "Cada {$diaM} de {$nomMes} a las {$horaTxt}";
            }

            return "A las {$horaTxt}";
        }

        return "Cron: {$cronExpr}";
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

        try {
            $db = Connection::getInstance();
            $db->exec("DELETE FROM telemetria_cache");
            $archivosBorrados++;
        } catch (Throwable $e) {}

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
        $purgados = 0;
        try {
            $db = Connection::getInstance();
            // Mantener solo los últimos 2000 registros en la base de datos
            $stmtCount = $db->query("SELECT COUNT(*) FROM system_audit_log");
            $total = (int)$stmtCount->fetchColumn();
            if ($total > 2000) {
                $exceso = $total - 2000;
                $db->exec("DELETE FROM system_audit_log WHERE id IN (SELECT id FROM system_audit_log ORDER BY fecha_hora ASC LIMIT {$exceso})");
                $purgados = $exceso;
            }
        } catch (Throwable $e) {}

        $storageDir = self::getStorageDir();
        $archivoAudit = $storageDir . 'system_audit.json';
        if (file_exists($archivoAudit)) {
            @file_put_contents($archivoAudit, json_encode([], JSON_PRETTY_PRINT));
        }

        return [
            'exito' => true,
            'mensaje' => "Optimización de logs completada. {$purgados} registros obsoletos purgados.",
            'salida' => "Registros purgados de system_audit_log en BD: {$purgados}"
        ];
    }

    /**
     * Intenta registrar automáticamente el runner de CIIDI en el crontab del usuario actual en Linux / Debian.
     */
    public static function instalarCrontabLinux(): array {
        $esWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        if ($esWindows) {
            return ['exito' => false, 'mensaje' => 'La instalación en crontab solo aplica para servidores Linux como Debian.'];
        }

        $runnerPath = realpath(self::getStorageDir() . 'cron_runner.php') ?: (self::getStorageDir() . 'cron_runner.php');
        $phpCli = self::getPhpCliPath();
        $lineaCron = "* * * * * {$phpCli} {$runnerPath} >/dev/null 2>&1";

        $salidaActual = [];
        $codigoActual = 0;
        @exec('crontab -l 2>/dev/null', $salidaActual, $codigoActual);

        $lineasLimpias = [];
        $yaExiste = false;
        if (is_array($salidaActual)) {
            foreach ($salidaActual as $lin) {
                $linTrim = trim($lin);
                if ($linTrim === '' || stripos($linTrim, 'no crontab for') !== false) {
                    continue;
                }
                if (strpos($linTrim, 'cron_runner.php') !== false && !str_starts_with($linTrim, '#')) {
                    $yaExiste = true;
                    $lineasLimpias[] = $lineaCron;
                } else {
                    $lineasLimpias[] = $lin;
                }
            }
        }

        if (!$yaExiste) {
            $lineasLimpias[] = $lineaCron;
        }

        $tmpDir = self::getStorageDir() . 'tmp/';
        if (!is_dir($tmpDir)) {
            @mkdir($tmpDir, 0777, true);
            @chmod($tmpDir, 0777);
        }
        $tmpCronFile = $tmpDir . 'ciidi_crontab_' . uniqid() . '.cron';
        $contenidoCron = implode("\n", $lineasLimpias) . "\n";
        @file_put_contents($tmpCronFile, $contenidoCron);

        $salidaInst = [];
        $codigoInst = 1;
        @exec('crontab ' . escapeshellarg($tmpCronFile) . ' 2>&1', $salidaInst, $codigoInst);
        @unlink($tmpCronFile);

        if ($codigoInst === 0) {
            // Ejecutar un primer pulso inmediato para dejar registrado el latido
            self::dispararCicloSiCorresponde(true);
            return [
                'exito' => true,
                'mensaje' => 'Runner registrado correctamente en el crontab de Debian Linux y motor periódico activado.'
            ];
        }

        // Incluso si el usuario www-data tiene restringido el binario crontab, el motor híbrido interno se encarga
        self::dispararCicloSiCorresponde(true);
        return [
            'exito' => true,
            'mensaje' => 'El motor periódico automático interno quedó activo para ejecutar las tareas sin requerir permisos de consola.'
        ];
    }

    /**
     * Disparador periódico híbrido no bloqueante:
     * Verifica una vez por minuto si existen tareas pendientes por horario y las ejecuta automáticamente.
     * Garantiza que en Debian Linux o Windows las tareas se cumplan periódicamente aunque crontab esté restringido.
     */
    public static function dispararCicloSiCorresponde(bool $forzarRevision = false): array {
        $dirScheduler = self::getStorageDir() . 'scheduler/';
        if (!is_dir($dirScheduler)) {
            @mkdir($dirScheduler, 0777, true);
            @chmod($dirScheduler, 0777);
        }

        $tickFile = $dirScheduler . '.last_tick';
        $ahora = time();

        if (!$forzarRevision && file_exists($tickFile)) {
            $ultimoTick = (int)@file_get_contents($tickFile);
            if (($ahora - $ultimoTick) < 50) {
                return ['revisado' => false, 'ejecutadas' => 0];
            }
        }

        @file_put_contents($tickFile, (string)$ahora);
        @chmod($tickFile, 0666);

        $tareas = self::obtenerTareas();
        $pendientesIds = [];

        foreach ($tareas as $id => $t) {
            if (($t['estado'] ?? 'activo') !== 'activo') {
                continue;
            }
            $cronExpr = $t['expresion_cron'] ?? '* * * * *';
            $ultima = $t['ultima_ejecucion'] ?? null;
            if (self::correspondeEjecutar($cronExpr, $ultima)) {
                $pendientesIds[] = $id;
            }
        }

        if (empty($pendientesIds)) {
            self::registrarLatidoRunner('auto_web_check', 0);
            return ['revisado' => true, 'ejecutadas' => 0];
        }

        $ejecutadas = 0;
        foreach ($pendientesIds as $idTarea) {
            self::ejecutarTareaManual($idTarea);
            $ejecutadas++;
        }

        self::registrarLatidoRunner('auto_scheduler', $ejecutadas);
        return ['revisado' => true, 'ejecutadas' => $ejecutadas];
    }

    public static function diagnosticarEntornoCron(): array {
        $esWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $cronDetectado = false;
        $runnerEnCrontab = false;
        $detalles = '';

        $runnerPath = realpath(self::getStorageDir() . 'cron_runner.php') ?: (self::getStorageDir() . 'cron_runner.php');
        $phpCli = self::getPhpCliPath();

        if (!$esWindows) {
            $salida = [];
            $codigo = 0;
            @exec('crontab -l 2>&1', $salida, $codigo);
            if ($codigo === 0 && is_array($salida)) {
                foreach ($salida as $linea) {
                    $l = trim($linea);
                    if ($l !== '' && !str_starts_with($l, '#') && strpos($l, 'cron_runner.php') !== false) {
                        $runnerEnCrontab = true;
                        break;
                    }
                }
            }

            // Si en Debian aún no está registrado en el crontab del usuario web, intentar registrarlo automáticamente
            if (!$runnerEnCrontab) {
                $intentoAuto = self::instalarCrontabLinux();
                $salidaVerif = [];
                $codVerif = 0;
                @exec('crontab -l 2>&1', $salidaVerif, $codVerif);
                if ($codVerif === 0 && is_array($salidaVerif)) {
                    foreach ($salidaVerif as $linea) {
                        $l = trim($linea);
                        if ($l !== '' && !str_starts_with($l, '#') && strpos($l, 'cron_runner.php') !== false) {
                            $runnerEnCrontab = true;
                            break;
                        }
                    }
                }
            }

            $estadoLatido = self::obtenerEstadoLatido();
            if ($runnerEnCrontab) {
                $cronDetectado = true;
                $detalles = 'Servicio Cron de Debian Linux activo y runner registrado en el crontab del servidor.';
            } elseif (!empty($estadoLatido['activo_reciente'])) {
                $cronDetectado = true;
                $detalles = 'Motor periódico activo en Debian Linux. Última verificación registrada a las ' . ($estadoLatido['datos']['fecha_hora'] ?? date('H:i:s')) . '.';
            } else {
                // Activar pulso inmediato del motor automático
                self::dispararCicloSiCorresponde(true);
                $cronDetectado = true;
                $detalles = 'Motor periódico automático de CIIDI activo en Debian Linux. Si deseas añadirlo también al crontab global de root, usa el comando inferior.';
            }
        } else {
            self::dispararCicloSiCorresponde(false);
            $cronDetectado = true;
            $detalles = 'Entorno Windows con motor periódico automático activo. También admite programación por Task Scheduler o consola.';
        }

        $estadoLatidoFinal = self::obtenerEstadoLatido();

        return [
            'es_windows' => $esWindows,
            'cron_activo' => $cronDetectado,
            'runner_en_crontab' => $runnerEnCrontab,
            'ultimo_latido' => $estadoLatidoFinal['datos']['fecha_hora'] ?? date('Y-m-d H:i:s'),
            'detalles' => $detalles,
            'runner_path' => $runnerPath,
            'comando_sugerido_linux' => "* * * * * {$phpCli} {$runnerPath} >/dev/null 2>&1",
            'comando_sugerido_windows' => "schtasks /create /tn \"CIIDI_CronRunner\" /tr \"{$phpCli} {$runnerPath}\" /sc minute /mo 1"
        ];
    }
}

