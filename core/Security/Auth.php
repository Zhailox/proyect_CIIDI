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
    public static function requierePrivilegioMinimo(int $nivelExigido, ?string $permisoRuta = null, ?string $moduloRuta = null) {
        if (!self::check()) { header("Location: login"); exit; }

        $nivelUsuario = (int)($_SESSION['nivel_privilegio'] ?? -1);
        $nivelDios = 999; 

        if ($nivelUsuario < $nivelExigido && $nivelUsuario < $nivelDios) {
            self::render403();
        }

        // Nueva verificación en 3D: Nivel -> Módulo -> Acción
        if ($permisoRuta !== null && $moduloRuta !== null && $nivelUsuario < $nivelDios) {
            $archivo_rbac = CORE_PATH . '../storage/rbac_matrix.json';
            if (file_exists($archivo_rbac)) {
                $matrix = json_decode(file_get_contents($archivo_rbac), true) ?: [];
                
                if (isset($matrix[$nivelUsuario][$moduloRuta][$permisoRuta]) && $matrix[$nivelUsuario][$moduloRuta][$permisoRuta] === false) {
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

/**
 * Helper Global de Auditoría Activa (System Monitoring & Audit Trail)
 */
class AuditLogger {
    
    public static function registrar(string $nivel, string $modulo, string $accion, string $detalles = '') {
        $usuario = Auth::usuario();
        $responsable = $usuario ? "{$usuario['nombre']} (ID: {$usuario['id']})" : "Sistema / Anónimo";
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

        $registro = [
            'id'          => uniqid('log_'),
            'fecha_hora'  => date('Y-m-d H:i:s'),
            'nivel'       => strtoupper($nivel), // INFO | WARNING | ERROR | CRITICAL
            'modulo'      => $modulo,
            'accion'      => $accion,
            'detalles'    => $detalles,
            'responsable' => $responsable,
            'ip'          => $ip
        ];

        $archivo = CORE_PATH . '../storage/system_audit.json';
        $directorio = dirname($archivo);
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $logs = file_exists($archivo) ? (json_decode(file_get_contents($archivo), true) ?: []) : [];
        array_unshift($logs, $registro); // Insertar al inicio para orden cronológico descendente
        
        // Conservar los últimos 1000 eventos en storage
        if (count($logs) > 1000) {
            $logs = array_slice($logs, 0, 1000);
        }

        file_put_contents($archivo, json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}