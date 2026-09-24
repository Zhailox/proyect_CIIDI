<?php
// core/Security/AuditLogger.php

require_once CORE_PATH . 'Database/Connection.php';

class AuditLogger {

    private const FALLBACK_LOG_PATH = __DIR__ . '/../../storage/logs/audit_fallback.jsonl';
    private const SECRET_KEY = 'CIIDI_SECRET_KEY_AUDIT_LOG_2026';
    private const GENESIS_HASH = 'GENESIS_HASH_CIIDI_UPTTMBI';

    /**
     * Registra un evento en la tabla `system_audit_log` con encadenamiento criptográfico HMAC.
     */
    public static function registrar(
        string $nivel, 
        string $modulo, 
        string $accion, 
        string $detalles = ''
    ): bool {
        // Intentar sincronizar cola de contingencia si existe y la BD está disponible
        try {
            if (file_exists(self::FALLBACK_LOG_PATH) && filesize(self::FALLBACK_LOG_PATH) > 0) {
                self::sincronizarFallback();
            }
        } catch (Throwable) {
            // Continuar con el registro normal
        }

        if (date_default_timezone_get() !== 'America/Caracas') {
            date_default_timezone_set('America/Caracas');
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

        $registroBase = [
            'id' => $id,
            'fecha_hora' => $fechaHora,
            'nivel' => $nivel,
            'modulo' => $modulo,
            'accion' => $accion,
            'detalles' => $detalles,
            'responsable' => $usuarioResponsable,
            'ip' => $ipOrigen
        ];

        try {
            $db = Connection::getInstance();
            if (!$db) {
                return self::guardarEnFallback($registroBase);
            }

            // Obtener el hash anterior para la cadena criptográfica
            $stmtPrev = $db->query("SELECT hash_integridad FROM system_audit_log ORDER BY ctid DESC LIMIT 1");
            $hashAnterior = self::GENESIS_HASH;
            if ($stmtPrev) {
                $val = $stmtPrev->fetchColumn();
                if (!empty($val) && is_string($val)) {
                    $hashAnterior = $val;
                }
            }

            // Generar Hash criptográfico del registro actual
            $datosConcatenados = "{$id}|{$fechaHora}|{$nivel}|{$modulo}|{$accion}|{$detalles}|{$usuarioResponsable}|{$ipOrigen}|{$hashAnterior}";
            $hashIntegridad = hash_hmac('sha256', $datosConcatenados, self::SECRET_KEY);

            $stmt = $db->prepare("
                INSERT INTO system_audit_log 
                (id, fecha_hora, nivel, modulo, accion, detalles, responsable, ip, hash_anterior, hash_integridad) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $resultado = $stmt->execute([
                $id,
                $fechaHora,
                $nivel,
                $modulo,
                $accion,
                $detalles,
                $usuarioResponsable,
                $ipOrigen,
                $hashAnterior,
                $hashIntegridad
            ]);

            if (!$resultado) {
                return self::guardarEnFallback($registroBase);
            }

            return true;
        } catch (Throwable $e) {
            // Guardar en log alternativo si la base de datos no responde
            if (class_exists('Connection')) {
                Connection::logSystemError($e);
            }
            return self::guardarEnFallback($registroBase);
        }
    }

    /**
     * Guarda el registro en el archivo de contingencia JSONL en disco cuando la BD no está disponible.
     */
    public static function guardarEnFallback(array $datos): bool {
        try {
            $dir = dirname(self::FALLBACK_LOG_PATH);
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            $linea = json_encode($datos, JSON_UNESCAPED_UNICODE) . PHP_EOL;
            return file_put_contents(self::FALLBACK_LOG_PATH, $linea, FILE_APPEND | LOCK_EX) !== false;
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Sincroniza los eventos encolados en fallback a la base de datos enlazándolos a la cadena criptográfica.
     */
    public static function sincronizarFallback(): int {
        if (!file_exists(self::FALLBACK_LOG_PATH) || filesize(self::FALLBACK_LOG_PATH) === 0) {
            return 0;
        }

        try {
            $db = Connection::getInstance();
            if (!$db) {
                return 0;
            }

            $tmpFile = self::FALLBACK_LOG_PATH . '.' . uniqid('sync_', true);
            if (!@rename(self::FALLBACK_LOG_PATH, $tmpFile)) {
                return 0;
            }

            $lineas = file($tmpFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if (empty($lineas)) {
                @unlink($tmpFile);
                return 0;
            }

            $stmtPrev = $db->query("SELECT hash_integridad FROM system_audit_log ORDER BY ctid DESC LIMIT 1");
            $hashAnterior = self::GENESIS_HASH;
            if ($stmtPrev) {
                $val = $stmtPrev->fetchColumn();
                if (!empty($val) && is_string($val)) {
                    $hashAnterior = $val;
                }
            }

            $stmt = $db->prepare("
                INSERT INTO system_audit_log 
                (id, fecha_hora, nivel, modulo, accion, detalles, responsable, ip, hash_anterior, hash_integridad) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $insertados = 0;
            $pendientesReintentar = [];

            foreach ($lineas as $linea) {
                $item = json_decode($linea, true);
                if (!is_array($item) || empty($item['id'])) {
                    continue;
                }

                $id = $item['id'];
                $fechaHora = $item['fecha_hora'] ?? date('Y-m-d H:i:s');
                $nivel = $item['nivel'] ?? 'INFO';
                $modulo = $item['modulo'] ?? 'Sistema';
                $accion = $item['accion'] ?? 'Accion';
                $detalles = $item['detalles'] ?? '';
                $responsable = $item['responsable'] ?? 'Anónimo / Sistema';
                $ip = $item['ip'] ?? '127.0.0.1';

                $datosConcatenados = "{$id}|{$fechaHora}|{$nivel}|{$modulo}|{$accion}|{$detalles}|{$responsable}|{$ip}|{$hashAnterior}";
                $hashIntegridad = hash_hmac('sha256', $datosConcatenados, self::SECRET_KEY);

                try {
                    $ok = $stmt->execute([
                        $id, $fechaHora, $nivel, $modulo, $accion, $detalles, $responsable, $ip, $hashAnterior, $hashIntegridad
                    ]);
                    if ($ok) {
                        $hashAnterior = $hashIntegridad;
                        $insertados++;
                    } else {
                        $pendientesReintentar[] = $linea;
                    }
                } catch (Throwable) {
                    $pendientesReintentar[] = $linea;
                }
            }

            @unlink($tmpFile);

            if (!empty($pendientesReintentar)) {
                $contenido = implode(PHP_EOL, $pendientesReintentar) . PHP_EOL;
                file_put_contents(self::FALLBACK_LOG_PATH, $contenido, FILE_APPEND | LOCK_EX);
            }

            return $insertados;
        } catch (Throwable $e) {
            if (class_exists('Connection')) {
                Connection::logSystemError($e);
            }
            return 0;
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

            $stmt = $db->query("SELECT * FROM system_audit_log ORDER BY ctid ASC");
            $logs = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];

            $hashAnteriorEsperado = null;
            $corruptos = [];

            foreach ($logs as $index => $log) {
                $id = $log['id'] ?? '';
                $fechaHora = $log['fecha_hora'] ?? '';
                $nivel = $log['nivel'] ?? '';
                $modulo = $log['modulo'] ?? '';
                $accion = $log['accion'] ?? '';
                $detalles = $log['detalles'] ?? '';
                $usuarioResponsable = $log['responsable'] ?? ($log['usuario_responsable'] ?? 'Anónimo / Sistema');
                $ipOrigen = $log['ip'] ?? ($log['ip_origen'] ?? '127.0.0.1');
                $hashAnterior = $log['hash_anterior'] ?? '';
                $hashRegistro = $log['hash_integridad'] ?? ($log['hash_registro'] ?? '');

                // 1. Validar encadenamiento de hash anterior
                if ($index === 0) {
                    if ($hashAnterior !== 'GENESIS_CIIDI_V1' && $hashAnterior !== self::GENESIS_HASH) {
                        $corruptos[] = [
                            'id' => $id,
                            'motivo' => "Violación de nodo génesis en registro inicial #{$id}"
                        ];
                    }
                } else {
                    if ($hashAnterior !== $hashAnteriorEsperado) {
                        $corruptos[] = [
                            'id' => $id,
                            'motivo' => "Violación de cadena de hash anterior en registro #{$id}"
                        ];
                    }
                }

                // 2. Recalcular hash del registro (compatible con HMAC y SHA-256 estándar previo)
                $datosConcatenados = "{$id}|{$fechaHora}|{$nivel}|{$modulo}|{$accion}|{$detalles}|{$usuarioResponsable}|{$ipOrigen}|{$hashAnterior}";
                $hashHmac = hash_hmac('sha256', $datosConcatenados, self::SECRET_KEY);
                $hashSha256 = hash('sha256', $datosConcatenados);

                if (!empty($hashRegistro) && $hashRegistro !== $hashHmac && $hashRegistro !== $hashSha256) {
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
