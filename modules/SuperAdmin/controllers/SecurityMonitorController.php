<?php
// modules/SuperAdmin/controllers/SecurityMonitorController.php

require_once CORE_PATH . 'Security/Auth.php';
require_once CORE_PATH . 'Security/RateLimiter.php';

class SecurityMonitorController {

    public function index() {
        Auth::requierePrivilegioMinimo(0);

        $intentos = RateLimiter::obtenerIntentos();
        $blacklist = RateLimiter::obtenerListaNegra();
        $whitelist = RateLimiter::obtenerListaBlanca();
        $verificacionIntegridad = AuditLogger::verificarIntegridadCadena();

        return [
            'intentos' => $intentos,
            'blacklist' => $blacklist,
            'whitelist' => $whitelist,
            'integridad' => $verificacionIntegridad
        ];
    }

    public function desbloquearIP() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ip = trim($_POST['ip'] ?? '');
            if (!empty($ip)) {
                RateLimiter::desbloquearIP($ip);
                AuditLogger::registrar('WARNING', 'SuperAdmin', 'Desbloquear IP', "La IP {$ip} fue removida de listas de restricción.");
                
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_admin_exito'] = "IP '{$ip}' desbloqueada/revisada exitosamente.";
            }
        }
        header("Location: visor-seguridad");
        exit;
    }

    public function bloquearListaNegra() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ip = trim($_POST['ip'] ?? '');
            $razon = trim($_POST['razon'] ?? 'Bloqueo manual desde el Monitor WAF');

            if (!empty($ip)) {
                RateLimiter::agregarListaNegra($ip, $razon);
                AuditLogger::registrar('CRITICAL', 'SuperAdmin', 'Lista Negra WAF', "La IP {$ip} fue añadida a la Lista Negra Global. Razón: {$razon}");

                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_admin_exito'] = "IP '{$ip}' agregada a la Lista Negra Global.";
            }
        }
        header("Location: visor-seguridad");
        exit;
    }

    public function agregarListaBlanca() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ip = trim($_POST['ip'] ?? '');
            $nota = trim($_POST['nota'] ?? 'IP de confianza UPTTMBI');

            if (!empty($ip)) {
                RateLimiter::agregarListaBlanca($ip, $nota);
                AuditLogger::registrar('INFO', 'SuperAdmin', 'Lista Blanca WAF', "La IP {$ip} fue añadida a la Lista Blanca (Whitelist). Nota: {$nota}");

                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_admin_exito'] = "IP '{$ip}' agregada a la Lista Blanca de Confianza.";
            }
        }
        header("Location: visor-seguridad");
        exit;
    }
}
