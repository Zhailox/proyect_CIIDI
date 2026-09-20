<?php
// core/Security/SessionManager.php

class SessionManager {
    
    /**
     * Inicia la sesión PHP aplicando configuraciones estrictas de seguridad si no está iniciada.
     */
    public function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            // Mitiga fijación de sesión (Session Fixation)
            ini_set('session.use_strict_mode', '1'); 
            
            // Configuración segura de cookies de sesión
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'), // cookie_secure
                'httponly' => true, // cookie_httponly: Bloquea lectura desde JS (XSS)
                'samesite' => 'Lax' // cookie_samesite: Bloquea envío cruzado (CSRF)
            ]);
            
            session_start();
        }
    }
}
