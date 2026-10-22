<?php
require_once __DIR__ . '/../core/Database/Connection.php';
$db = Connection::getInstance();

try {
    $db->exec("ALTER TABLE lineas_investigacion ADD COLUMN activo BOOLEAN DEFAULT TRUE");
    echo "Agregado 'activo' a lineas_investigacion.\n";
} catch(Exception $e) {
    echo "Error o ya existe: " . $e->getMessage() . "\n";
}

try {
    $db->exec("ALTER TABLE dimensiones_operativas ADD COLUMN activo BOOLEAN DEFAULT TRUE");
    echo "Agregado 'activo' a dimensiones_operativas.\n";
} catch(Exception $e) {
    echo "Error o ya existe: " . $e->getMessage() . "\n";
}
