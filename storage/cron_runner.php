<?php
// storage/cron_runner.php
// Micro-Runner CLI para Cron Job de Linux / Task Scheduler de Windows y Auto-Runner Interno

date_default_timezone_set('America/Caracas');

define('BASE_PATH', dirname(__DIR__));
define('ROOT_PATH', BASE_PATH);
define('CORE_PATH', BASE_PATH . '/core/');
define('MODULES_PATH', BASE_PATH . '/modules/');
define('STORAGE_PATH', BASE_PATH . '/storage/');

if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
    require_once BASE_PATH . '/vendor/autoload.php';
}

if (file_exists(CORE_PATH . 'System/Env.php')) {
    require_once CORE_PATH . 'System/Env.php';
    Env::load(BASE_PATH . '/.env');
}

require_once CORE_PATH . 'Security/Auth.php';
require_once BASE_PATH . '/modules/SuperAdmin/services/SchedulerService.php';

$termoColor = (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN');

function logCli($msg, $color = '0') {
    global $termoColor;
    if ($termoColor) {
        echo "\033[{$color}m[" . date('Y-m-d H:i:s') . "] {$msg}\033[0m\n";
    } else {
        echo "[" . date('Y-m-d H:i:s') . "] {$msg}\n";
    }
}

logCli("Iniciando Runner de Tareas Programadas CIIDI...", "32");

SchedulerService::registrarLatidoRunner('cron_cli');

$tareas = SchedulerService::obtenerTareas();
$ejecutadas = 0;

foreach ($tareas as $id => $t) {
    if (($t['estado'] ?? 'activo') !== 'activo') {
        continue;
    }

    $cronExpr = $t['expresion_cron'] ?? '* * * * *';
    $ultima = $t['ultima_ejecucion'] ?? null;

    if (SchedulerService::correspondeEjecutar($cronExpr, $ultima)) {
        logCli("Ejecutando tarea: {$t['nombre']} - ID: {$id}...", "33");
        $res = SchedulerService::ejecutarTareaManual($id);
        if ($res['exito']) {
            logCli("ÉXITO - {$res['duracion']}ms: {$res['mensaje']}", "32");
        } else {
            logCli("ERROR - {$res['duracion']}ms: {$res['mensaje']}", "31");
        }
        $ejecutadas++;
    }
}

SchedulerService::registrarLatidoRunner('cron_cli', $ejecutadas);

logCli("Proceso finalizado. Total tareas procesadas en este ciclo: {$ejecutadas}", "36");
