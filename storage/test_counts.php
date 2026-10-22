<?php
// storage/test_counts.php
define('BASE_PATH', __DIR__ . '/..');
define('CORE_PATH', BASE_PATH . '/core/');
require_once CORE_PATH . 'Database/Connection.php';
require_once BASE_PATH . '/modules/RepositorioPST/models/DocumentoModel.php';

$m = new DocumentoModel();
echo "--- Documentos PST ---\n";
$docs = $m->getPSTDocumentos([], 100, 0);
echo "Total docs recibidos: " . count($docs) . "\n";
foreach ($docs as $d) {
    echo "ID: {$d['id']} | Trayecto: '" . ($d['trayecto'] ?? 'NULL') . "'\n";
}

echo "--- PST Count By Trayecto ---\n";
var_dump($m->getPSTCountByTrayecto());
