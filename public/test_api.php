<?php
$ch = curl_init('http://localhost/proyect_CIIDI/proyect_CIIDI/public/index.php?ruta=api-transparencia');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['tipo' => 'codigo', 'codigo' => 'CIIDI-123']));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
curl_close($ch);
echo "RESPONSE:\n" . $response;
