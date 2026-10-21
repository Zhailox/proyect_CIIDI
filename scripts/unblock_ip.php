<?php
// scripts/unblock_ip.php
// Script de utilidad para desbloquear IPs en desarrollo o contingencia

define('BASE_PATH', dirname(__DIR__));
define('CORE_PATH', BASE_PATH . '/core/');
define('STORAGE_PATH', BASE_PATH . '/storage/');

require_once CORE_PATH . 'Database/Connection.php';
require_once CORE_PATH . 'Security/RateLimiter.php';

$ipObjetivo = $argv[1] ?? 'all_local';

$ipsALimpiar = [];
if ($ipObjetivo === 'all_local' || empty($ipObjetivo)) {
    $ipsALimpiar = ['127.0.0.1', '::1', 'localhost'];
} else {
    $ipsALimpiar = [$ipObjetivo];
}

echo "=== DESBLOQUEO DE SEGURIDAD WAF (CIIDI) ===\n";

$db = Connection::getInstance();
if ($db) {
    $db->exec("DELETE FROM waf_rate_limiter WHERE tipo IN ('blacklist', 'attempt')");
    echo "[BD] Registros de bloqueo y lista negra eliminados de la base de datos PostgreSQL.\n";
}

@file_put_contents(STORAGE_PATH . 'security_blacklist.json', "{}\n");
@file_put_contents(STORAGE_PATH . 'login_attempts.json', "{}\n");
echo "[Archivos] Archivos JSON de intentos y lista negra restablecidos.\n";

echo "\n¡Desbloqueo completado exitosamente! El sistema está 100% libre para navegar.\n";
