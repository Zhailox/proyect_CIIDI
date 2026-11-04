<?php
require_once __DIR__ . '/../core/Database/Connection.php';
try {
    $pdo = Connection::getInstance();
    $sql = "SELECT constraint_name AS conname, constraint_type FROM information_schema.table_constraints WHERE table_schema = DATABASE() AND table_name = 'propuestas_empresa';";
    print_r($pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
