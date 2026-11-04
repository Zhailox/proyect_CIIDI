<?php
require_once __DIR__ . '/../core/Database/Connection.php';
try {
    $pdo = Connection::getInstance();
    $sql = "SELECT column_name AS column_name, data_type AS data_type FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'investigaciones_ofertadas';";
    print_r($pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
