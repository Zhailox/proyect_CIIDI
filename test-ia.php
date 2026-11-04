<?php
// 1. Quitamos el límite de memoria temporalmente para que pueda descomprimir el DLL
ini_set('memory_limit', '-1');

require_once __DIR__ . '/vendor/autoload.php';

echo "<h3>Prueba de funcionamiento: El Monstruo</h3>";

try {
    echo "Verificando motor nativo C++... (Descargando si falta)<br>";
    
    // 2. Este es el salvavidas que buscará y descargará el onnxruntime.dll faltante
    \OnnxRuntime\Vendor::check();
    
    echo "Motor C++ listo.<br>";

    $ruta_modelo = __DIR__ . '/vendor/php-ai/rd-test/model.onnx';
    
    if (!file_exists($ruta_modelo)) {
        die("❌ Error: No encuentro el archivo del modelo en: " . $ruta_modelo);
    }

    if (class_exists('OnnxRuntime\Model')) {
        $modelo = new OnnxRuntime\Model($ruta_modelo);
    } elseif (class_exists('OnnxRuntime\InferenceSession')) {
        $modelo = new OnnxRuntime\InferenceSession($ruta_modelo);
    } else {
        die("❌ Clases no encontradas en el namespace.");
    }

    echo "<strong>✅ ¡ÉXITO ROTUNDO!</strong><br>";
    echo "El modelo cargó correctamente en memoria.";
} catch (Exception $e) {
    echo "<strong>❌ Fallo en la Matrix:</strong><br>";
    echo $e->getMessage();
}