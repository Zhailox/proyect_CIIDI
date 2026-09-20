<?php
// core/Security/AuditLogger.php

require_once CORE_PATH . 'Database/Connection.php';

class AuditLogger {

    /**
     * Registra un evento en la tabla `system_audit_log` con encadenamiento criptográfico HMAC.
     */
    public static function registrar(
        string $nivel, 
        string $modulo, 
        string $accion, 
        string $detalles = ''
    ): bool {
        try {
            $db = Connection::getInstance();
            if (!$db) {
                return false;
            }

            $id = uniqid('log_', true);
            $fechaHora = date('Y-m-d H:i:s');
            $nivel = strtoupper($nivel);
            
            // Datos del usuario responsable
            $usuarioResponsable = 'Anónimo / Sistema';
            if (isset($_SESSION['usuario_nombre'])) {
                $idUser = $_SESSION['usuario_id'] ?? '0';
                $usuarioResponsable = $_SESSION['usuario_nombre'] . " (ID: {$idUser})";
            } elseif (isset($_SESSION['emerg_user_nombre'])) {
                $usuarioResponsable = $_SESSION['emerg_user_nombre'] . " (Cuenta Emergencia Break-Glass)";
            }

            // IP del cliente
            $ipOrigen = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            if (str_contains($ipOrigen, ',')) {
                $ipOrigen = trim(explode(',', $ipOrigen)[0]);
            }

            // Obtener el hash anterior para la cadena criptográfica
            $stmtPrev = $db->query("SELECT hash_registro FROM system_audit_log ORDER BY fecha_hora DESC, id DESC LIMIT 1");
            $hashAnterior = 'GENESIS_HASH_CIIDI_UPTTMBI';
            if ($stmtPrev) {
                $val = $stmtPrev->fetchColumn();
                if (!empty($val) && is_string($val)) {
                    $hashAnterior = $val;
                }
            }

            // Generar Hash criptográfico del registro actual
            $datosConcatenados = "{$id}|{$fechaHora}|{$nivel}|{$modulo}|{$accion}|{$detalles}|{$usuarioResponsable}|{$ipOrigen}|{$hashAnterior}";
            $hashRegistro = hash_hmac('sha256', $datosConcatenados, 'CIIDI_SECRET_KEY_AUDIT_LOG_2026');

            $stmt = $db->prepare("
                INSERT INTO system_audit_log 
                (id, fecha_hora, nivel, modulo, accion, detalles, usuario_responsable, ip_origen, hash_anterior, hash_registro) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            return $stmt->execute([
                $id,
                $fechaHora,
                $nivel,
                $modulo,
                $accion,
                $detalles,
                $usuarioResponsable,
                $ipOrigen,
                $hashAnterior,
                $hashRegistro
            ]);
        } catch (Throwable $e) {
            // Guardar en log alternativo si la base de datos no responde
            if (class_exists('Connection')) {
                Connection::logSystemError($e);
            }
            return false;
        }
    }

    /**
     * Verifica la integridad de la cadena de bloques de auditoría en la BD.
     */
    public static function verificarIntegridadCadena(): array {
        try {
            $db = Connection::getInstance();
            if (!$db) {
                return ['integro' => false, 'mensaje' => 'Sin conexión a base de datos'];
            }

            $stmt = $db->query("SELECT * FROM system_audit_log ORDER BY fecha_hora ASC, id ASC");
            $logs = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];

            $hashAnteriorEsperado = 'GENESIS_HASH_CIIDI_UPTTMBI';
            $corruptos = [];

            foreach ($logs as $index => $log) {
                $id = $log['id'] ?? '';
                $fechaHora = $log['fecha_hora'] ?? '';
                $nivel = $log['nivel'] ?? '';
                $modulo = $log['modulo'] ?? '';
                $accion = $log['accion'] ?? '';
                $detalles = $log['detalles'] ?? '';
                $usuarioResponsable = $log['usuario_responsable'] ?? ($log['usuario'] ?? 'Anónimo / Sistema');
                $ipOrigen = $log['ip_origen'] ?? ($log['ip'] ?? '127.0.0.1');
                $hashAnterior = $log['hash_anterior'] ?? '';
                $hashRegistro = $log['hash_registro'] ?? ($log['hash'] ?? '');

                // 1. Validar encadenamiento de hash anterior
                if ($hashAnterior !== $hashAnteriorEsperado) {
                    $corruptos[] = [
                        'id' => $id,
                        'motivo' => "Violación de cadena de hash anterior en registro #{$id}"
                    ];
                }

                // 2. Recalcular hash del registro
                $datosConcatenados = "{$id}|{$fechaHora}|{$nivel}|{$modulo}|{$accion}|{$detalles}|{$usuarioResponsable}|{$ipOrigen}|{$hashAnterior}";
                $hashCalculado = hash_hmac('sha256', $datosConcatenados, 'CIIDI_SECRET_KEY_AUDIT_LOG_2026');

                if (!empty($hashRegistro) && $hashCalculado !== $hashRegistro) {
                    $corruptos[] = [
                        'id' => $id,
                        'motivo' => "Firma criptográfica inválida (Datos alterados manualmente) en registro #{$id}"
                    ];
                }

                $hashAnteriorEsperado = !empty($hashRegistro) ? $hashRegistro : $hashAnteriorEsperado;
            }

            return [
                'integro' => empty($corruptos),
                'total' => count($logs),
                'corruptos' => count($corruptos),
                'total_registros' => count($logs),
                'registros_corruptos' => $corruptos,
                'mensaje' => empty($corruptos) ? 'Cadena criptográfica verificada sin alteraciones.' : 'Se detectaron incongruencias en la firma de auditoría.'
            ];
        } catch (Throwable $e) {
            return ['integro' => false, 'mensaje' => $e->getMessage(), 'total' => 0, 'corruptos' => 0];
        }
    }
}
