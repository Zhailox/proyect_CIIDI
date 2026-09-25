<?php
// core/Security/Auth.php
require_once CORE_PATH . 'Database/Connection.php';
require_once CORE_PATH . 'Security/SessionManager.php';

class Auth {
    
    public static function check(): bool {
        $sessionManager = new SessionManager();
        $sessionManager->start();
        
        if (!isset($_SESSION['usuario_id'])) {
            return false;
        }

        // --- VERIFICACIÓN DE SESIÓN REVOCADA / KILL SESSION ---
        $archivo_sesiones = defined('STORAGE_PATH') ? STORAGE_PATH . 'revoked_sessions.json' : CORE_PATH . '../storage/revoked_sessions.json';
        if (file_exists($archivo_sesiones)) {
            $revogadas = json_decode(file_get_contents($archivo_sesiones), true) ?: [];
            $usuarioIdStr = (string)$_SESSION['usuario_id'];
            
            if (isset($revogadas[$usuarioIdStr])) {
                $revocadoVal = $revogadas[$usuarioIdStr];
                $loginTime = (int)($_SESSION['login_time'] ?? 0);

                // Si hay un timestamp de revocación:
                // Solo se revoca si esta sesión se inició ANTES o DURANTE el momento de revocación.
                // Si la sesión se inició DESPUÉS de la revocación (nuevo inicio de sesión legítimo), no se expulsa.
                $debeRevocar = false;
                if (is_numeric($revocadoVal)) {
                    $revocadoTime = (int)$revocadoVal;
                    if ($loginTime <= $revocadoTime) {
                        $debeRevocar = true;
                    }
                } else {
                    // Valor booleano legado: solo revocar si no tiene login_time reciente
                    if ($loginTime === 0) {
                        $debeRevocar = true;
                    }
                }

                if ($debeRevocar) {
                    unset($revogadas[$usuarioIdStr]);
                    file_put_contents($archivo_sesiones, json_encode($revogadas, JSON_PRETTY_PRINT));
                    
                    session_unset();
                    session_destroy();
                    
                    $sessionManager->start();
                    session_regenerate_id(true);
                    
                    $_SESSION['error_login'] = "Tu sesión ha sido finalizada por el administrador por razones de seguridad.";
                    header("Location: login");
                    exit;
                } else {
                    // La sesión actual es posterior a la revocación (nuevo login exitoso posterior).
                    // Limpiamos la entrada obsoleta del JSON para no dejar pendientes.
                    unset($revogadas[$usuarioIdStr]);
                    file_put_contents($archivo_sesiones, json_encode($revogadas, JSON_PRETTY_PRINT));
                }
            }
        }

        // --- CONTROL DE TIEMPO DE INACTIVIDAD (SESSION TIMEOUT) ---
        $archivo_config = defined('STORAGE_PATH') ? STORAGE_PATH . 'system_config.json' : CORE_PATH . '../storage/system_config.json';
        $timeoutMinutos = 120;
        if (file_exists($archivo_config)) {
            $sysCfg = json_decode(file_get_contents($archivo_config), true) ?: [];
            $timeoutMinutos = (int)($sysCfg['seguridad']['timeout_minutos'] ?? 120);
        }
        $timeoutSegundos = max(300, $timeoutMinutos * 60);

        $ahora = time();
        if (isset($_SESSION['ultimo_acceso'])) {
            $inactividad = $ahora - (int)$_SESSION['ultimo_acceso'];
            if ($inactividad > $timeoutSegundos) {
                session_unset();
                session_destroy();
                $sessionManager->start();
                session_regenerate_id(true);
                $_SESSION['error_login'] = "Tu sesión ha expirado por inactividad ({$timeoutMinutos} min). Por favor, inicia sesión nuevamente.";
                header("Location: login");
                exit;
            }
        }
        $_SESSION['ultimo_acceso'] = $ahora;

        return true;
    }

    /**
     * Verifica si el usuario actual cumple con el nivel mínimo exigido o permiso dinámico RBAC.
     */
    public static function requierePrivilegioMinimo(int $nivelExigido, ?string $permisoRuta = null, ?string $moduloRuta = null, bool $redirect403 = true): bool {
        if (!self::check()) {
            if ($redirect403) { header("Location: login"); exit; }
            return false;
        }

        $nivelUsuario = (int)($_SESSION['nivel_privilegio'] ?? 999);
        $nivelDios = 0;

        if ($nivelUsuario === $nivelDios) {
            return true;
        }

        if ($permisoRuta !== null && $moduloRuta !== null) {
            $autorizado = false;
            
            try {
                $db = Connection::getInstance();
                if ($db) {
                    $stmt = $db->prepare("SELECT permisos FROM matriz_rbac WHERE nivel_privilegio = ? AND modulo = ?");
                    $stmt->execute([$nivelUsuario, $moduloRuta]);
                    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($fila && !empty($fila['permisos'])) {
                        $permisos = is_string($fila['permisos']) ? json_decode($fila['permisos'], true) : $fila['permisos'];
                        if (!empty($permisos[$permisoRuta])) {
                            $autorizado = true;
                        }
                    }
                }
            } catch (DatabaseConnectionException $e) {
                $autorizado = false;
            }

            if (!$autorizado) {
                if ($redirect403) {
                    self::render403();
                }
                return false;
            }

            return true;
        }

        if ($nivelUsuario > $nivelExigido) {
            if ($redirect403) {
                self::render403();
            }
            return false;
        }

        return true;
    }

    private static function render403(): void {
        if (!headers_sent()) {
            http_response_code(403);
        }
        $vista_modulo_path = CORE_VIEWS . '403.php';
        $titulo_pagina     = '403 - Acceso Denegado';
        
        if (headers_sent()) {
            if (file_exists($vista_modulo_path)) {
                include $vista_modulo_path;
            } else {
                echo "<div style='padding:40px;text-align:center;'><h2>Acceso Denegado (403)</h2></div>";
            }
            exit;
        }

        $layout_config     = ['header' => true, 'sidebar' => true, 'footer' => true];
        if (file_exists(CORE_VIEWS . 'master.php')) {
            include CORE_VIEWS . 'master.php';
        } else {
            echo "<h2>Acceso Denegado (403)</h2>";
        }
        exit;
    }

    public static function usuario(): ?array {
        if (!self::check()) return null;

        $nombre = $_SESSION['nombre'] 
            ?? $_SESSION['usuario_nombre'] 
            ?? $_SESSION['nombre_completo'] 
            ?? $_SESSION['usuario'] 
            ?? $_SESSION['emerg_user_nombre'] 
            ?? 'Usuario';

        $rol = $_SESSION['rol'] 
            ?? $_SESSION['rol_nombre'] 
            ?? $_SESSION['usuario_rol'] 
            ?? 'Usuario';

        return [
            'id' => $_SESSION['usuario_id'] ?? null,
            'nombre' => $nombre,
            'cedula' => $_SESSION['usuario_cedula'] ?? $_SESSION['cedula'] ?? '',
            'email' => $_SESSION['usuario_email'] ?? $_SESSION['email'] ?? '',
            'nivel' => $_SESSION['nivel_privilegio'] ?? 999,
            'rol' => $rol
        ];
    }
}