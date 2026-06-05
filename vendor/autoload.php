<?php

// Autoloader manual compatible con PSR-4
spl_autoload_register(function ($class) {
    // Definimos el prefijo del namespace de nuestra aplicación
    $prefix = 'App\\';
    
    // Directorio base para el prefijo del namespace
    $base_dir = __DIR__ . '/../src/';
    
    // Verificamos si la clase usa el prefijo del namespace
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return; // No es nuestra clase
    }
    
    // Obtenemos el nombre relativo de la clase
    $relative_class = substr($class, $len);
    
    // Reemplazamos los separadores de namespace por separadores de directorio,
    // y añadimos la extensión .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // Si el archivo existe, lo requerimos
    if (file_exists($file)) {
        require $file;
    }
});
