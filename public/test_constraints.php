<?php
require_once __DIR__ . '/../core/Database/Connection.php';
try {
    $pdo = Connection::getInstance();
    $sql = "SELECT CONSTRAINT_NAME AS conname FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'propuestas_empresa'";
    print_r($pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}