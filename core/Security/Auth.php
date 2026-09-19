<?php
// core/Security/Auth.php
require_once CORE_PATH . 'Database/Connection.php';

class Auth {
    
    public static function check() {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.use_strict_mode', '1');
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
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
                
                // Reiniciar sesión de forma segura y regenerar el ID para evitar fijación
                ini_set('session.use_strict_mode', '1');
                session_set_cookie_params([
                    'lifetime' => 0,
                    'path' => '/',
                    'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]);
                session_start();
                session_regenerate_id(true);
                
                $_SESSION['error_login'] = "Tu sesión ha sido finalizada por el administrador por razones de seguridad.";
                header("Location: login");
                exit;
            }
        }

        return true;
    }

    /**
     * Verifica si el usuario actual cumple con el nivel mínimo exigido o permiso dinámico RBAC.
     * Nivel 0 = Administrador Supremo (nivelDios) | Nivel 1+ = Profesores, Bibliotecarios, Admins
     * Si $redirect403 es true, redirige a 403 / login. Si es false, devuelve simplemente un booleano (true/false).
     */
    public static function requierePrivilegioMinimo(int $nivelExigido, ?string $permisoRuta = null, ?string $moduloRuta = null, bool $redirect403 = true): bool {
        if (!self::check()) {
            if ($redirect403) { header("Location: login"); exit; }
            return false;
        }

        $nivelUsuario = (int)($_SESSION['nivel_privilegio'] ?? 999);
        $nivelDios = 0;

        // El nivel 0 (Administrador Supremo / nivelDios) siempre tiene acceso total absoluto
        if ($nivelUsuario === $nivelDios) {
            return true;
        }

        // 1. Si se especifican módulo y permiso, la matriz RBAC tiene prioridad absoluta
        if ($permisoRuta !== null && $moduloRuta !== null) {
            $autorizado = false;
            
            // Reemplazo de JSON por consulta SQL
            $db = Connection::getInstance();
            $stmt = $db->prepare("SELECT permisos FROM matriz_rbac WHERE nivel_privilegio = ? AND modulo = ?");
            $stmt->execute([$nivelUsuario, $moduloRuta]);
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($fila && !empty($fila['permisos'])) {
                $permisos = is_string($fila['permisos']) ? json_decode($fila['permisos'], true) : $fila['permisos'];
                if (!empty($permisos[$permisoRuta])) {
                    $autorizado = true;
                }
            }

            if (!$autorizado) {
                if ($redirect403) {
                    self::render403();
                }
                return false;
            }

            return true;
        }

        // 2. Fallback: Verificación de nivel numérico cuando no se especifica módulo/permiso
        if ($nivelUsuario > $nivelExigido) {
            if ($redirect403) {
                self::render403();
            }
            return false;
        }

        return true;
    }



    private static function render403() {
        if (!headers_sent()) {
            http_response_code(403);
        }
        $vista_modulo_path = CORE_VIEWS . '403.php';
        $titulo_pagina     = '403 - Acceso Denegado';
        
        // Si las cabeceras ya se enviaron, significa que master.php ya está cargado y estamos en medio de la vista.
        // Renderizamos solo el 403 para no duplicar el navbar y la barra lateral.
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
        $ip = class_exists('RateLimiter') ? RateLimiter::obtenerIPCliente() : ($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');

        $db = Connection::getInstance();
        // Obtener el Hash del registro inmediatamente anterior desde la BD
    $stmt = $db->query("SELECT hash_integridad FROM system_audit_log ORDER BY fecha_hora DESC LIMIT 1");
    $last = $stmt->fetch(PDO::FETCH_ASSOC);
    $hashAnterior = $last ? $last['hash_integridad'] : 'GENESIS_CIIDI_V1';
    
    $idLog = uniqid('log_');
    $fechaHora = date('Y-m-d H:i:s');

    // Generar Hash SHA-256 de integridad
    $payloadIntegridad = "{$idLog}|{$fechaHora}|{$nivel}|{$modulo}|{$accion}|{$detalles}|{$responsable}|{$ip}|{$hashAnterior}";
    $hashIntegridad = hash('sha256', $payloadIntegridad);

    $sql = "INSERT INTO system_audit_log (id, fecha_hora, nivel, modulo, accion, detalles, responsable, ip, hash_anterior, hash_integridad) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmtInsert = $db->prepare($sql);
    $stmtInsert->execute([$idLog, $fechaHora, $nivel, $modulo, $accion, $detalles, $responsable, $ip, $hashAnterior, $hashIntegridad]);
}
    /**
     * Valida la integridad criptográfica SHA-256 de todos los registros de auditoría almacenados en PostgreSQL.
     */
    public static function verificarIntegridadCadena(): array {
        try {
            $db = Connection::getInstance();
            $stmt = $db->query("SELECT id, fecha_hora, nivel, modulo, accion, detalles, responsable, ip, hash_anterior, hash_integridad FROM system_audit_log ORDER BY fecha_hora DESC");
            $logs = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Throwable $e) {
            // Fallback si la BD no responde
            $archivo = CORE_PATH . '../storage/system_audit.json';
            $logs = file_exists($archivo) ? (json_decode(file_get_contents($archivo), true) ?: []) : [];
        }

        if (empty($logs)) {
            return ['integro' => true, 'mensaje' => 'No existen eventos de auditoría registrados.', 'total' => 0, 'corruptos' => 0];
        }

        $corruptos = [];
        $total = count($logs);

        for ($i = 0; $i < $total; $i++) {
            $current = $logs[$i];
            if (!isset($current['hash_integridad'])) continue;

            $hashGuardado = $current['hash_integridad'];
            $hashAntGuardado = $current['hash_anterior'] ?? 'GENESIS_CIIDI_V1';

            $payload = "{$current['id']}|{$current['fecha_hora']}|{$current['nivel']}|{$current['modulo']}|{$current['accion']}|{$current['detalles']}|{$current['responsable']}|{$current['ip']}|{$hashAntGuardado}";
            $hashCalculado = hash('sha256', $payload);

            if ($hashCalculado !== $hashGuardado) {
                $corruptos[] = [
                    'id' => $current['id'],
                    'fecha_hora' => $current['fecha_hora'],
                    'accion' => $current['accion'],
                    'hash_esperado' => $hashCalculado,
                    'hash_guardado' => $hashGuardado
                ];
            }
        }

        $integro = count($corruptos) === 0;

        return [
            'integro'   => $integro,
            'total'     => $total,
            'corruptos' => count($corruptos),
            'detalles'  => $corruptos,
            'mensaje'   => $integro 
                ? "La cadena criptográfica de auditoría es 100% íntegra ({$total} registros firmados digitalmente)."
                : "¡ALERTA DE SEGURIDAD! Se detectaron " . count($corruptos) . " registros manipulados o con firmas alteradas."
        ];
    }
}