<?php
require_once __DIR__ . '/../core/Database/Connection.php';
$pdo = Connection::getInstance();

function sanitize_cp850_corruptions($text) {
    if (empty($text)) return $text;

    // Direct byte replacements for known CP850-in-UTF8 corruptions
    $replacements = [
        chr(0xA2) => 'ó',
        chr(0xA1) => 'í',
        chr(0xA0) => 'á',
        chr(0x82) => 'é',
        chr(0xA4) => 'ñ',
        chr(0xA3) => 'ú'
    ];
    
    foreach ($replacements as $badByte => $goodChar) {
        $text = str_replace($badByte, $goodChar, $text);
    }
    
    // Fallback word replacements in case the bytes were already transformed
    $words = [
        'informaci¢n' => 'información',
        ' mbito' => 'ámbito',
        '¢ptimo' => 'óptimo',
        'Tecnolog¡as' => 'Tecnologías',
        'Comunicaci¢n' => 'Comunicación',
        'as¡' => 'así',
        'educaci¢n' => 'educación',
        'inform ticas' => 'informáticas',
        'gesti¢n' => 'gestión',
        'tecnolog¡as' => 'tecnologías',
        'transmisi¢n' => 'transmisión',
        'tambi,n' => 'también',
        'detecci¢n' => 'detección',
        'dise¤o' => 'diseño',
        'Dise¤o' => 'Diseño',
        'evaluaci¢n' => 'evaluación',
        'Evaluaci¢n' => 'Evaluación',
        'aplicaci¢n' => 'aplicación',
        'Aplicaci¢n' => 'Aplicación',
        'organizaci¢n' => 'organización',
        'Organizaci¢n' => 'Organización',
        'resoluci¢n' => 'resolución',
        'computaci¢n' => 'computación',
        'acci¢n' => 'acción',
        'direcci¢n' => 'dirección',
        'innovaci¢n' => 'innovación'
    ];
    
    foreach ($words as $bad => $good) {
        $text = str_replace($bad, $good, $text);
    }
    
    return $text;
}

// Fix lineas (just in case any were missed)
$stmt = $pdo->query('SELECT id, nombre, descripcion FROM lineas_investigacion');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {
    $rawDesc = sanitize_cp850_corruptions($row['descripcion']);
    $rawNombre = sanitize_cp850_corruptions($row['nombre']);
    
    if ($rawDesc !== $row['descripcion'] || $rawNombre !== $row['nombre']) {
        $updateStmt = $pdo->prepare('UPDATE lineas_investigacion SET descripcion = ?, nombre = ? WHERE id = ?');
        $updateStmt->execute([$rawDesc, $rawNombre, $row['id']]);
    }
}

// Fix dimensiones
$stmt = $pdo->query('SELECT id, nombre, descripcion FROM dimensiones_operativas');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {
    $rawDesc = sanitize_cp850_corruptions($row['descripcion']);
    $rawNombre = sanitize_cp850_corruptions($row['nombre']);
    
    if ($rawDesc !== $row['descripcion'] || $rawNombre !== $row['nombre']) {
        $updateStmt = $pdo->prepare('UPDATE dimensiones_operativas SET descripcion = ?, nombre = ? WHERE id = ?');
        $updateStmt->execute([$rawDesc, $rawNombre, $row['id']]);
    }
}

echo "All corruptions fixed!";
?>
