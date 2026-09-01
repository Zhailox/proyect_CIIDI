<?php
require_once __DIR__ . '/core/Database/Connection.php';

header('Content-Type: application/json');

try {
    $db = Connection::getInstance();
    
    // Check if table exists
    $stmt = $db->query("SELECT EXISTS (
        SELECT FROM information_schema.tables 
        WHERE table_schema = 'public' 
        AND table_name = 'propuestas_empresa'
    );");
    
    $exists = $stmt->fetchColumn();
    
    if (!$exists) {
        echo json_encode(['status' => 'error', 'message' => 'La tabla propuestas_empresa no existe.']);
        exit;
    }
    
    // Count records
    $stmt = $db->query("SELECT count(*) FROM propuestas_empresa");
    $count = $stmt->fetchColumn();
    
    // Get records
    $stmt = $db->query("SELECT * FROM propuestas_empresa");
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'status' => 'success',
        'table_exists' => true,
        'count' => $count,
        'records' => $records
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
