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

        $nivelUsuario = (int)($_SESSION['nivel_privilegio'] ?? 999);
        $nivelDios = 0;

        if ($nivelUsuario > $nivelExigido && $nivelUsuario !== $nivelDios) {
            self::render403();
        }

        if ($permisoRuta !== null && $moduloRuta !== null && $nivelUsuario !== $nivelDios) {
            $archivo_rbac = CORE_PATH . '../storage/rbac_matrix.json';
            
            if (file_exists($archivo_rbac)) {
                $matrix = json_decode(file_get_contents($archivo_rbac), true) ?: [];
                
                // Si el permiso no está marcado como 'true' en la matriz para este nivel y módulo, se bloquea.
                if (empty($matrix[$nivelUsuario][$moduloRuta][$permisoRuta])) {
                    self::render403();
                }
            } else {
                // Si el archivo JSON no existe pero se exige un permiso, se deniega por seguridad (Fail-Safe)
                self::render403();
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
        $ip = class_exists('RateLimiter') ? RateLimiter::obtenerIPCliente() : ($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');

        $archivo = CORE_PATH . '../storage/system_audit.json';
        $directorio = dirname($archivo);
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $logs = file_exists($archivo) ? (json_decode(file_get_contents($archivo), true) ?: []) : [];

        // Obtener el Hash del registro inmediatamente anterior para el encadenamiento Tamper-Proof
        $hashAnterior = !empty($logs) && isset($logs[0]['hash_integridad']) ? $logs[0]['hash_integridad'] : 'GENESIS_CIIDI_V1';
        $idLog = uniqid('log_');
        $fechaHora = date('Y-m-d H:i:s');

        // Generar Hash SHA-256 de integridad (Cadena de Custodia Criptográfica)
        $payloadIntegridad = "{$idLog}|{$fechaHora}|{$nivel}|{$modulo}|{$accion}|{$detalles}|{$responsable}|{$ip}|{$hashAnterior}";
        $hashIntegridad = hash('sha256', $payloadIntegridad);

        $registro = [
            'id'              => $idLog,
            'fecha_hora'      => $fechaHora,
            'nivel'           => strtoupper($nivel), // INFO | WARNING | ERROR | CRITICAL
            'modulo'          => $modulo,
            'accion'          => $accion,
            'detalles'        => $detalles,
            'responsable'     => $responsable,
            'ip'              => $ip,
            'hash_anterior'   => $hashAnterior,
            'hash_integridad' => $hashIntegridad
        ];

        array_unshift($logs, $registro); // Insertar al inicio para orden cronológico descendente
        
        // Conservar los últimos 1000 eventos en storage
        if (count($logs) > 1000) {
            $logs = array_slice($logs, 0, 1000);
        }

        file_put_contents($archivo, json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Valida la integridad criptográfica SHA-256 de todos los registros de auditoría almacenados.
     */
    public static function verificarIntegridadCadena(): array {
        $archivo = CORE_PATH . '../storage/system_audit.json';
        if (!file_exists($archivo)) {
            return ['integro' => true, 'mensaje' => 'El archivo de auditoría está vacío o no ha sido generado.', 'total' => 0, 'corruptos' => 0];
        }

        $logs = json_decode(file_get_contents($archivo), true) ?: [];
        if (empty($logs)) {
            return ['integro' => true, 'mensaje' => 'No existen eventos de auditoría registrados.', 'total' => 0, 'corruptos' => 0];
        }

        $corruptos = [];
        $total = count($logs);

        for ($i = 0; $i < $total; $i++) {
            $current = $logs[$i];
            
            // Si el log viejo no tiene hash_integridad (legacy), lo omitimos de la falla estricta
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