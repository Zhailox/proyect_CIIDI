<?php
// public/index.php

// 1. Definir rutas base absolutas del sistema (Constantes Globales)
define('BASE_PATH', dirname(__DIR__));
define('ROOT_PATH', BASE_PATH);
define('CORE_PATH', BASE_PATH . '/core/');
define('CORE_VIEWS', CORE_PATH . 'Views/');
define('MODULES_PATH', BASE_PATH . '/modules/');
define('STORAGE_PATH', BASE_PATH . '/storage/');

// 1.5. Cargar el autoloader de Composer si existe
if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
    require_once BASE_PATH . '/vendor/autoload.php';
}

// 1.6. Cargar Motor de Variables de Entorno (.env)
require_once CORE_PATH . 'System/Env.php';
Env::load(BASE_PATH . '/.env');

// 1.7. Configuración de Depuración según APP_DEBUG (.env Shield)
$appDebug = (bool) Env::get('APP_DEBUG', false);
if ($appDebug) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(0);
}

// 1.8. Aplicar directivas dinámicas de memoria y tiempo de ejecución sin contaminar clases globales
$cfgPstPath = MODULES_PATH . 'RepositorioPST/config_pst.json';
if (file_exists($cfgPstPath)) {
    $cfgPstData = @json_decode((string)@file_get_contents($cfgPstPath), true);
    $maxMbRuntime = max(20, (int)($cfgPstData['archivos']['max_size_mb'] ?? 200));
    $memMbRuntime = max(512, (int)ceil($maxMbRuntime * 2.5));
    @ini_set('memory_limit', $memMbRuntime . 'M');
    @ini_set('max_execution_time', '300');
    @ini_set('max_input_time', '300');
}

// 2. Importar el motor del sistema (Microkernel)
require_once CORE_PATH . 'System/Kernel.php';

// 3. Instanciar y ejecutar la aplicación
$app = new Kernel();
$app->run();
