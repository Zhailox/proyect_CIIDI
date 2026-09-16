<?php
require_once __DIR__ . '/../core/Database/Connection.php';
try {
    $pdo = Connection::getInstance();
    $pdo->exec("ALTER TABLE propuestas_empresa ADD COLUMN IF NOT EXISTS motivo_rechazo text;");
    echo "Column added successfully";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
