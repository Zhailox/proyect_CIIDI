<?php
// core/Security/Auth.php

class Auth {
    
    public static function check() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['usuario_id'])) {
            return false;
        }

        // --- VERIFICACIÓN DE SESIÓN REVOCADA / KILL SESSION ---
        $archivo_sesiones = CORE_PATH . '../storage/revoked_sessions.json';
        if (file_exists($archivo_sesiones)) {
            $revogadas = json_decode(file_get_contents($archivo_sesiones), true) ?: [];
            $usuarioIdStr = (string)$_SESSION['usuario_id'];
            
            // Verificamos si la ID del usuario actual está marcada como revocada
            if (isset($revogadas[$usuarioIdStr]) && $revogadas[$usuarioIdStr] === true) {
                unset($revogadas[$usuarioIdStr]);
                file_put_contents($archivo_sesiones, json_encode($revogadas, JSON_PRETTY_PRINT));
                
                session_unset();
                session_destroy();
                session_start();
                $_SESSION['error_login'] = "Tu sesión ha sido finalizada por el administrador por razones de seguridad.";
                header("Location: login");
                exit;
            }
        }

        return true;
    }

    /**
     * Verifica si el usuario actual cumple con el nivel mínimo exigido o permiso dinámico RBAC.
     * Nivel 0 = Estudiante | Nivel 1 = Profesor | Nivel 2 = Bibliotecario | Nivel 3 = Admin
     */
    public static function requierePrivilegioMinimo(int $nivelExigido, ?string $permisoRuta = null) {
        
        if (!self::check()) {
            header("Location: login"); 
            exit;
        }

        $nivelUsuario = (int)($_SESSION['nivel_privilegio'] ?? -1);

        // 1. Verificación de Nivel Estático Base (El SuperAdmin nivel 3 siempre pasa)
        if ($nivelUsuario < $nivelExigido && $nivelUsuario < 3) {
            self::render403();
        }

        // 2. Verificación Dinámica RBAC (Si se especifica un permiso/acción por ruta)
        if ($permisoRuta !== null && $nivelUsuario < 3) {
            $archivo_rbac = CORE_PATH . '../storage/rbac_matrix.json';
            if (file_exists($archivo_rbac)) {
                $matrix = json_decode(file_get_contents($archivo_rbac), true) ?: [];
                $rolNombre = $_SESSION['rol_nombre'] ?? '';
                
                // Si la matriz define permisos para este rol y la ruta está restringida
                if (isset($matrix[$rolNombre][$permisoRuta]) && $matrix[$rolNombre][$permisoRuta] === false) {
                    self::render403();
                }
            }
        }

        return true;
    }

    private static function render403() {
        http_response_code(403);
        $vista_modulo_path = CORE_VIEWS . '403.php';
        $titulo_pagina     = '403 - Acceso Denegado';
        $layout_config     = ['header' => true, 'sidebar' => true, 'footer' => true];
        if (file_exists(CORE_VIEWS . 'master.php')) {
            include CORE_VIEWS . 'master.php';
        } else {
            echo "<h2>Acceso Denegado (403)</h2>";
        }
        exit;
    }

    /**
     * Devuelve los datos del usuario activo para usarlos en el Header o Sidebar
     */
    public static function usuario() {
        if (!self::check()) return null;

        return [
            'id' => $_SESSION['usuario_id'],
            'nombre' => $_SESSION['nombre_usuario'],
            'rol' => $_SESSION['rol_nombre'],
            'nivel' => $_SESSION['nivel_privilegio']
        ];
    }
}