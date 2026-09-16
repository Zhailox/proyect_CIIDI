<?php
// core/Security/RateLimiter.php

class RateLimiter {

    private static function getStorageDir(): string {
        $dir = defined('STORAGE_PATH') ? STORAGE_PATH : __DIR__ . '/../../storage/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir;
    }

    private static function getAttemptsFile(): string {
        return self::getStorageDir() . 'login_attempts.json';
    }

    private static function getBlacklistFile(): string {
        return self::getStorageDir() . 'security_blacklist.json';
    }

    public static function obtenerIPCliente(): string {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        }
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    private static function getWhitelistFile(): string {
        return self::getStorageDir() . 'security_whitelist.json';
    }

    public static function estaBloqueada(string $ip = null): array {
        if ($ip === null) $ip = self::obtenerIPCliente();

        // 0. Revisar Lista Blanca (Whitelist) - Acceso prioritario garantizado
        $whitelist = self::obtenerListaBlanca();
        if (isset($whitelist[$ip])) {
            return ['bloqueada' => false, 'es_whitelist' => true];
        }

        // 1. Revisar Lista Negra Global (Bloqueo Permanente manual)
        $blacklist = self::obtenerListaNegra();
        if (isset($blacklist[$ip])) {
            return [
                'bloqueada' => true,
                'razon' => 'Lista Negra Global: ' . ($blacklist[$ip]['razon'] ?? 'Acceso denegado por administrador'),
                'tipo' => 'blacklist'
            ];
        }

        // 2. Revisar Rate Limiting (Bloqueo temporal por intentos fallidos)
        $attempts = self::obtenerIntentos();
        if (isset($attempts[$ip])) {
            $data = $attempts[$ip];
            $intentos = $data['intentos'] ?? 0;
            $bloqueadoHasta = $data['bloqueado_hasta'] ?? 0;

            if ($intentos >= 5 && time() < $bloqueadoHasta) {
                $segundosRestantes = $bloqueadoHasta - time();
                $minutosRestantes = ceil($segundosRestantes / 60);
                return [
                    'bloqueada' => true,
                    'razon' => "IP bloqueada temporalmente por fuerza bruta. Reintente en {$minutosRestantes} minuto(s).",
                    'tipo' => 'rate_limit',
                    'segundos_restantes' => $segundosRestantes
                ];
            }
        }

        return ['bloqueada' => false];
    }

    /**
     * Inspecciona los datos de la petición (GET, POST, COOKIES) buscando patrones de SQL Injection o XSS.
     */
    public static function inspeccionarPayloadsSeguridad(): array {
        $ip = self::obtenerIPCliente();
        
        // Si está en Whitelist, no se bloquea
        $whitelist = self::obtenerListaBlanca();
        if (isset($whitelist[$ip])) {
            return ['amenaza_detectada' => false];
        }

        $patronesSQLi = [
            '/\b(union\s+all\s+select|union\s+select)\b/i',
            '/\b(select\s+.*\s+from|insert\s+into|delete\s+from|drop\s+table|drop\s+database|alter\s+table)\b/i',
            '/(\'|\")\s*(or|and)\s*(\'|\")?\d+(\'|\")?\s*=\s*(\'|\")?\d+/i',
            '/\b(exec\s*\(|execute\s*\(|pg_sleep\(|sleep\()\b/i'
        ];

        $patronesXSS = [
            '/<script\b[^>]*>(.*?)<\/script>/is',
            '/javascript\s*:/i',
            '/onerror\s*=/i',
            '/onload\s*=/i',
            '/eval\s*\(/i'
        ];

        $datosRevisar = array_merge($_GET, $_POST);
        
        foreach ($datosRevisar as $clave => $valor) {
            if (is_array($valor)) continue;

            // Check SQL Injection
            foreach ($patronesSQLi as $pattern) {
                if (preg_match($pattern, (string)$valor)) {
                    self::agregarListaNegra($ip, "WAF: Intento de Inyección SQL detectado en parámetro '{$clave}'");
                    if (class_exists('AuditLogger')) {
                        AuditLogger::registrar('CRITICAL', 'WAF_Security', 'Inyección SQL Interceptada', "IP {$ip} bloqueada por SQLi en parámetro '{$clave}': " . htmlspecialchars(substr($valor, 0, 100)));
                    }
                    return ['amenaza_detectada' => true, 'tipo' => 'SQLi', 'parametro' => $clave];
                }
            }

            // Check XSS Malicioso
            foreach ($patronesXSS as $pattern) {
                if (preg_match($pattern, (string)$valor)) {
                    self::agregarListaNegra($ip, "WAF: Ataque Cross-Site Scripting (XSS) detectado en '{$clave}'");
                    if (class_exists('AuditLogger')) {
                        AuditLogger::registrar('CRITICAL', 'WAF_Security', 'Ataque XSS Interceptado', "IP {$ip} bloqueada por XSS en parámetro '{$clave}'");
                    }
                    return ['amenaza_detectada' => true, 'tipo' => 'XSS', 'parametro' => $clave];
                }
            }
        }

        return ['amenaza_detectada' => false];
    }

    public static function registrarIntentoFallido(string $ip = null, string $cedula = ''): void {
        if ($ip === null) $ip = self::obtenerIPCliente();

        $attempts = self::obtenerIntentos();
        $ahora = time();

        if (!isset($attempts[$ip])) {
            $attempts[$ip] = [
                'ip' => $ip,
                'intentos' => 1,
                'primer_intento' => $ahora,
                'ultimo_intento' => $ahora,
                'bloqueado_hasta' => 0,
                'historial_cedulas' => [$cedula]
            ];
        } else {
            // Si el bloqueo previo ya pasó de 15 min, reiniciar contador
            if ($attempts[$ip]['bloqueado_hasta'] > 0 && $ahora > $attempts[$ip]['bloqueado_hasta']) {
                $attempts[$ip]['intentos'] = 1;
                $attempts[$ip]['bloqueado_hasta'] = 0;
            } else {
                $attempts[$ip]['intentos']++;
            }

            $attempts[$ip]['ultimo_intento'] = $ahora;
            if (!empty($cedula) && !in_array($cedula, $attempts[$ip]['historial_cedulas'])) {
                $attempts[$ip]['historial_cedulas'][] = $cedula;
            }

            // Si alcanza 5 intentos, bloquear por 15 minutos (900 segundos)
            if ($attempts[$ip]['intentos'] >= 5) {
                $attempts[$ip]['bloqueado_hasta'] = $ahora + 900;
                
                // Registrar evento de seguridad en auditoría
                if (class_exists('AuditLogger')) {
                    AuditLogger::registrar(
                        'CRITICAL',
                        'WAF_Security',
                        'Bloqueo Automático IP por Fuerza Bruta',
                        "IP {$ip} bloqueada por 15 min tras {$attempts[$ip]['intentos']} intentos fallidos."
                    );
                }
            }
        }

        self::guardarIntentos($attempts);
    }

    public static function limpiarIntentosExitosa(string $ip = null): void {
        if ($ip === null) $ip = self::obtenerIPCliente();
        $attempts = self::obtenerIntentos();
        if (isset($attempts[$ip])) {
            unset($attempts[$ip]);
            self::guardarIntentos($attempts);
        }
    }

    public static function desbloquearIP(string $ip): bool {
        $attempts = self::obtenerIntentos();
        $blacklist = self::obtenerListaNegra();
        $whitelist = self::obtenerListaBlanca();
        $modificado = false;

        if (isset($attempts[$ip])) {
            unset($attempts[$ip]);
            self::guardarIntentos($attempts);
            $modificado = true;
        }

        if (isset($blacklist[$ip])) {
            unset($blacklist[$ip]);
            self::guardarListaNegra($blacklist);
            $modificado = true;
        }

        if (isset($whitelist[$ip])) {
            unset($whitelist[$ip]);
            self::guardarListaBlanca($whitelist);
            $modificado = true;
        }

        return $modificado;
    }

    public static function agregarListaBlanca(string $ip, string $nota = 'IP confiable UPTTMBI'): bool {
        $whitelist = self::obtenerListaBlanca();
        $whitelist[$ip] = [
            'ip' => $ip,
            'nota' => $nota,
            'fecha_alta' => date('Y-m-d H:i:s'),
            'responsable' => isset($_SESSION['nombre_usuario']) ? $_SESSION['nombre_usuario'] : 'SuperAdmin'
        ];

        // Remover de la lista negra si existía
        $blacklist = self::obtenerListaNegra();
        if (isset($blacklist[$ip])) {
            unset($blacklist[$ip]);
            self::guardarListaNegra($blacklist);
        }

        return self::guardarListaBlanca($whitelist);
    }

    public static function obtenerListaBlanca(): array {
        $file = self::getWhitelistFile();
        if (!file_exists($file)) return [];
        return json_decode(file_get_contents($file), true) ?: [];
    }

    private static function guardarListaBlanca(array $whitelist): bool {
        return (bool) file_put_contents(self::getWhitelistFile(), json_encode($whitelist, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public static function agregarListaNegra(string $ip, string $razon = 'Bloqueo manual por administración'): bool {
        $blacklist = self::obtenerListaNegra();
        $blacklist[$ip] = [
            'ip' => $ip,
            'razon' => $razon,
            'fecha_bloqueo' => date('Y-m-d H:i:s'),
            'responsable' => isset($_SESSION['nombre_usuario']) ? $_SESSION['nombre_usuario'] : 'SuperAdmin'
        ];
        return self::guardarListaNegra($blacklist);
    }

    public static function obtenerIntentos(): array {
        $file = self::getAttemptsFile();
        if (!file_exists($file)) return [];
        return json_decode(file_get_contents($file), true) ?: [];
    }

    private static function guardarIntentos(array $attempts): bool {
        return (bool) file_put_contents(self::getAttemptsFile(), json_encode($attempts, JSON_PRETTY_PRINT));
    }

    public static function obtenerListaNegra(): array {
        $file = self::getBlacklistFile();
        if (!file_exists($file)) return [];
        return json_decode(file_get_contents($file), true) ?: [];
    }

    private static function guardarListaNegra(array $blacklist): bool {
        return (bool) file_put_contents(self::getBlacklistFile(), json_encode($blacklist, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
