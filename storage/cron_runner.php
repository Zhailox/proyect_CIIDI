<?php
// storage/cron_runner.php
// Script ejecutor CLI para Linux Crontab o Windows Task Scheduler

define('CORE_PATH', __DIR__ . '/../core/');
define('STORAGE_PATH', __DIR__ . '/');

require_once CORE_PATH . 'Security/Auth.php';
require_once __DIR__ . '/../modules/SuperAdmin/services/SchedulerService.php';

echo "[CIIDI Scheduler Runner] Iniciando comprobación de tareas en segundo plano (" . date('Y-m-d H:i:s') . ")...\n";

$tareas = SchedulerService::obtenerTareas();
$ejecutadas = 0;

foreach ($tareas as $id => $t) {
    if ($t['estado'] === 'activo') {
        echo " -> Ejecutando tarea '{$t['nombre']}'...\n";
        $res = SchedulerService::ejecutarTareaManual($id);
        echo "    Resultado: " . ($res['exito'] ? 'OK' : 'ERROR') . " - {$res['mensaje']}\n";
        $ejecutadas++;
    }
}

echo "[CIIDI Scheduler Runner] Proceso finalizado. Total tareas procesadas: {$ejecutadas}.\n";
