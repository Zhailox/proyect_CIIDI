<?php
require_once __DIR__ . '/../core/Database/Connection.php';
$db = Connection::getInstance();
$q1 = $db->query("SELECT column_name FROM information_schema.columns WHERE table_name = 'lineas_investigacion'");
print_r($q1->fetchAll(PDO::FETCH_ASSOC));
$q2 = $db->query("SELECT column_name FROM information_schema.columns WHERE table_name = 'dimensiones_operativas'");
print_r($q2->fetchAll(PDO::FETCH_ASSOC));
