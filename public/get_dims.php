<?php
require_once __DIR__ . '/../core/Database/Connection.php';
$pdo = Connection::getInstance();
$stmt = $pdo->query('SELECT id, nombre, descripcion FROM dimensiones_operativas');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);
?>
