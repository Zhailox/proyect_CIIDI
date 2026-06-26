<?php
// public/index.php

// 1. Definir rutas base absolutas del sistema (Constantes Globales)
define('BASE_PATH', dirname(__DIR__));
define('CORE_PATH', BASE_PATH . '/core/');
define('CORE_VIEWS', CORE_PATH . 'Views/');
define('MODULES_PATH', BASE_PATH . '/modules/');

// 2. Importar el motor del sistema (Microkernel)
require_once CORE_PATH . 'System/Kernel.php';

// 3. Instanciar y ejecutar la aplicación
$app = new Kernel();
$app->run();

