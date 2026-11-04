<?php
require_once __DIR__ . '/../core/Database/Connection.php';
$pdo = Connection::getInstance();
function getColumns($pdo, $table) {
    $stmt = $pdo->prepare("SELECT column_name AS column_name, data_type AS data_type FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = ?");
    $stmt->execute([$table]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$schema = [
    'usuarios' => getColumns($pdo, 'usuarios'),
    'postulaciones_estudiantes' => getColumns($pdo, 'postulaciones_estudiantes'),
    'postulaciones_empresas' => getColumns($pdo, 'postulaciones_empresas')
];
echo json_encode($schema);
?>
