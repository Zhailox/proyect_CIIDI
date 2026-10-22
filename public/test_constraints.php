<?php
require_once __DIR__ . '/../core/Database/Connection.php';
try {
    $pdo = Connection::getInstance();
    $sql = "SELECT conname FROM pg_constraint WHERE conrelid = 'public.propuestas_empresa'::regclass;";
    print_r($pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
