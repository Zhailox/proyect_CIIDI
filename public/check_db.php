<?php
require_once __DIR__ . '/../core/Database/Connection.php';
$db = Connection::getInstance();
$inv = $db->query('SELECT * FROM investigaciones_ofertadas LIMIT 1')->fetchAll(PDO::FETCH_ASSOC);
print_r($inv);
