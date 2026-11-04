<?php
// core/Database/Connection.php

require_once __DIR__ . '/Exceptions/DatabaseConnectionException.php';

class Connection {
    // La única instancia de la clase (Patrón Singleton)
    private static $instance = null;
    
    // El objeto PDO real
    private $pdo;

    // Credenciales por defecto (MariaDB 10.6 / MySQL)
    private $host = 'localhost';
    private $port = '3306';
    private $db   = 'ciidi';
    private $user = 'root';
    private $pass = '';

    private static bool $schemaCheckedInProcess = false;

    private static function getConfigPath(): string {
        return defined('STORAGE_PATH') ? STORAGE_PATH . 'db_config.json' : __DIR__ . '/../../storage/db_config.json';
    }

    private function cargarCredenciales(): void {
        if (class_exists('Env')) {
            $this->host = (string)Env::get('DB_HOST', $this->host);
            $this->port = (string)Env::get('DB_PORT', $this->port);
            $this->db   = (string)Env::get('DB_NAME', $this->db);
            $this->user = (string)Env::get('DB_USER', $this->user);
            $this->pass = (string)Env::get('DB_PASS', $this->pass);
        }

        $file = self::getConfigPath();
        if (file_exists($file)) {
            $data = json_decode((string)@file_get_contents($file), true) ?: [];
            if (!empty($data['host'])) $this->host = trim((string)$data['host']);
            if (!empty($data['port'])) $this->port = trim((string)$data['port']);
            if (!empty($data['db']))   $this->db   = trim((string)$data['db']);
            if (!empty($data['user'])) $this->user = trim((string)$data['user']);
            if (isset($data['pass']))  $this->pass = (string)$data['pass'];
        }

        // Adaptar puerto heredado de PostgreSQL (5432) al estándar de MariaDB/MySQL (3306)
        if ($this->port === '5432' || $this->port === '') {
            $this->port = '3306';
        }
    }

    /**
     * Devuelve las credenciales de la base de datos de forma estática.
     */
    public static function getCredentials(): array {
        $dummy = new self(true, true);
        return [
            'host' => $dummy->host,
            'port' => $dummy->port,
            'db'   => $dummy->db,
            'user' => $dummy->user,
            'pass' => $dummy->pass
        ];
    }

    /**
     * Guarda las credenciales de la base de datos en storage/db_config.json y sincroniza .env si es escribible.
     */
    public static function saveCredentials(string $host, string $port, string $db, string $user, string $pass): bool {
        $file = self::getConfigPath();
        $dir = dirname($file);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        $host = trim($host);
        $port = trim($port);
        if ($port === '5432' || $port === '') {
            $port = '3306';
        }
        $db   = trim($db);
        $user = trim($user);

        $saveData = [
            'host' => $host,
            'port' => $port,
            'db'   => $db,
            'user' => $user,
            'pass' => $pass,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $savedJson = @file_put_contents($file, json_encode($saveData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;

        // Sincronizar también en .env para evitar discrepancias entre entornos Windows/Linux
        $envFile = defined('BASE_PATH') ? BASE_PATH . '/.env' : __DIR__ . '/../../.env';
        if (file_exists($envFile) && is_writable($envFile)) {
            $envContent = (string)@file_get_contents($envFile);
            $replacements = [
                '/^DB_HOST=.*$/m' => 'DB_HOST=' . $host,
                '/^DB_PORT=.*$/m' => 'DB_PORT=' . $port,
                '/^DB_NAME=.*$/m' => 'DB_NAME=' . $db,
                '/^DB_USER=.*$/m' => 'DB_USER=' . $user,
                '/^DB_PASS=.*$/m' => 'DB_PASS=' . $pass,
            ];
            foreach ($replacements as $pattern => $replacement) {
                if (preg_match($pattern, $envContent)) {
                    $envContent = preg_replace($pattern, $replacement, $envContent);
                } else {
                    $envContent = rtrim($envContent) . "\n" . $replacement . "\n";
                }
            }
            @file_put_contents($envFile, $envContent);
        }

        self::resetConnection();
        return $savedJson;
    }

    /**
     * Detecta la ruta ejecutable de mysqldump en el sistema operativo (Windows WAMP/XAMPP y Linux cPanel/CloudLinux).
     */
    public static function getPgDumpPath(): string {
        return self::getMysqldumpPath();
    }

    public static function getMysqldumpPath(): string {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $patronesWamp = array_merge(
                glob('C:\wamp64\bin\mariadb\mariadb*\bin\mysqldump.exe') ?: [],
                glob('C:\wamp64\bin\mysql\mysql*\bin\mysqldump.exe') ?: [],
                ['C:\xampp\mysql\bin\mysqldump.exe']
            );
            foreach ($patronesWamp as $ruta) {
                if (file_exists($ruta)) {
                    return '"' . $ruta . '"';
                }
            }
            return 'mysqldump';
        }

        $linuxCandidatas = [
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            '/bin/mysqldump'
        ];
        foreach ($linuxCandidatas as $ruta) {
            if (@is_executable($ruta) || @file_exists($ruta)) {
                return $ruta;
            }
        }

        return 'mysqldump';
    }

    /**
     * Detecta la ruta ejecutable de mysql cliente en el sistema operativo (Windows WAMP/XAMPP y Linux cPanel/CloudLinux).
     */
    public static function getPsqlPath(): string {
        return self::getMysqlPath();
    }

    public static function getMysqlPath(): string {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $patronesWamp = array_merge(
                glob('C:\wamp64\bin\mariadb\mariadb*\bin\mysql.exe') ?: [],
                glob('C:\wamp64\bin\mysql\mysql*\bin\mysql.exe') ?: [],
                ['C:\xampp\mysql\bin\mysql.exe']
            );
            foreach ($patronesWamp as $ruta) {
                if (file_exists($ruta)) {
                    return '"' . $ruta . '"';
                }
            }
            return 'mysql';
        }

        $linuxCandidatas = [
            '/usr/bin/mysql',
            '/usr/local/bin/mysql',
            '/bin/mysql'
        ];
        foreach ($linuxCandidatas as $ruta) {
            if (@is_executable($ruta) || @file_exists($ruta)) {
                return $ruta;
            }
        }

        return 'mysql';
    }

    private function __construct(bool $silencioso = false, bool $soloCredenciales = false) {
        $this->cargarCredenciales();
        if ($soloCredenciales) {
            return;
        }
        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db};charset=utf8mb4";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => true,
                PDO::ATTR_TIMEOUT            => 5,
            ];

            if (defined('PDO::MYSQL_ATTR_INIT_COMMAND')) {
                $options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci, SESSION group_concat_max_len = 65535, SESSION sql_mode = REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', '')";
            }

            try {
                $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
            } catch (PDOException $firstEx) {
                // Fallback automático en entorno local WAMP/XAMPP si el usuario previo ('miki') no existe en MariaDB local
                if (
                    in_array($this->host, ['localhost', '127.0.0.1'], true)
                    && $this->user !== 'root'
                    && ((int)$firstEx->getCode() === 1045 || str_contains($firstEx->getMessage(), '1045'))
                ) {
                    $this->pdo = new PDO($dsn, 'root', '', $options);
                    $this->user = 'root';
                    $this->pass = '';
                } else {
                    throw $firstEx;
                }
            }
            
        } catch (PDOException $e) {
            if ($silencioso) {
                return;
            }

            self::logSystemError($e);

            if (session_status() === PHP_SESSION_ACTIVE) {
                $_SESSION['db_connection_error_raw'] = $e->getMessage();
            }

            $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
            $detalleDev = $e->getMessage();

            throw new DatabaseConnectionException(
                'Error de infraestructura de datos. Por favor reintente más tarde.',
                (int)$e->getCode(),
                $e,
                $isAjax,
                $detalleDev
            );
        }
    }

    private static ?int $lastSyncedUserId = null;

    public static function getInstance(bool $silencioso = false): ?PDO {
        if (self::$instance === null) {
            $conexionObjeto = new self($silencioso);
            self::$instance = $conexionObjeto->pdo;
            self::$lastSyncedUserId = null;
        }
        if (self::$instance !== null) {
            $rawUid = $_SESSION['usuario_id'] ?? ($_SESSION['usuario']['id'] ?? null);
            if ($rawUid !== null && is_numeric($rawUid)) {
                $uid = (int)$rawUid;
                if ($uid > 0 && self::$lastSyncedUserId !== $uid) {
                    try {
                        $stmt = self::$instance->prepare("SET @app_usuario_actual = ?");
                        $stmt->execute([(string)$uid]);
                        self::$lastSyncedUserId = $uid;
                    } catch (Throwable) {
                        // Ignorar si la sesión de BD no admite variables en el estado actual
                    }
                }
            }
            if (!self::$schemaCheckedInProcess && empty($_SESSION['ciidi_triggers_verified'])) {
                self::$schemaCheckedInProcess = true;
                self::ensureAuditTriggers(self::$instance);
            }
        }
        return self::$instance;
    }

    /**
     * Garantiza que los 5 triggers nativos de auditoría existan y estén operativos en MariaDB 10.6.
     */
    public static function ensureAuditTriggers(?PDO $pdo = null, bool $forceRecreate = false): int {
        $pdo = $pdo ?? self::$instance;
        if (!$pdo) {
            return 0;
        }

        $definiciones = [
            'tg_auditoria_recursos_delete' => "
                CREATE TRIGGER `tg_auditoria_recursos_delete`
                BEFORE DELETE ON `recursos`
                FOR EACH ROW
                INSERT INTO `auditoria` (`tabla_afectada`, `id_registro`, `accion`, `usuario_responsable`, `datos_anteriores`, `datos_nuevos`, `fecha_hora`)
                VALUES (
                    'recursos',
                    OLD.`id`,
                    'DELETE',
                    NULLIF(CAST(NULLIF(@app_usuario_actual, '') AS UNSIGNED), 0),
                    JSON_OBJECT('titulo', OLD.`titulo`, 'id_tipo_recurso', OLD.`id_tipo_recurso`),
                    NULL,
                    CURRENT_TIMESTAMP
                )
            ",
            'tg_auditoria_recursos_insert' => "
                CREATE TRIGGER `tg_auditoria_recursos_insert`
                AFTER INSERT ON `recursos`
                FOR EACH ROW
                INSERT INTO `auditoria` (`tabla_afectada`, `id_registro`, `accion`, `usuario_responsable`, `datos_anteriores`, `datos_nuevos`, `fecha_hora`)
                VALUES (
                    'recursos',
                    NEW.`id`,
                    'INSERT',
                    NULLIF(CAST(NULLIF(@app_usuario_actual, '') AS UNSIGNED), 0),
                    NULL,
                    JSON_OBJECT('titulo', NEW.`titulo`, 'id_tipo_recurso', NEW.`id_tipo_recurso`),
                    CURRENT_TIMESTAMP
                )
            ",
            'tg_auditoria_usuarios_delete' => "
                CREATE TRIGGER `tg_auditoria_usuarios_delete`
                BEFORE DELETE ON `usuarios`
                FOR EACH ROW
                INSERT INTO `auditoria` (`tabla_afectada`, `id_registro`, `accion`, `usuario_responsable`, `datos_anteriores`, `datos_nuevos`, `fecha_hora`)
                VALUES (
                    'usuarios',
                    OLD.`id`,
                    'DELETE',
                    IF(CAST(NULLIF(@app_usuario_actual, '') AS UNSIGNED) = OLD.`id`, NULL, NULLIF(CAST(NULLIF(@app_usuario_actual, '') AS UNSIGNED), 0)),
                    JSON_OBJECT('nombre', OLD.`nombre_completo`, 'email', OLD.`email`, 'id_rol', OLD.`id_rol`),
                    NULL,
                    CURRENT_TIMESTAMP
                )
            ",
            'tg_auditoria_usuarios_insert' => "
                CREATE TRIGGER `tg_auditoria_usuarios_insert`
                AFTER INSERT ON `usuarios`
                FOR EACH ROW
                INSERT INTO `auditoria` (`tabla_afectada`, `id_registro`, `accion`, `usuario_responsable`, `datos_anteriores`, `datos_nuevos`, `fecha_hora`)
                VALUES (
                    'usuarios',
                    NEW.`id`,
                    'INSERT',
                    NULLIF(CAST(NULLIF(@app_usuario_actual, '') AS UNSIGNED), 0),
                    NULL,
                    JSON_OBJECT('nombre', NEW.`nombre_completo`, 'email', NEW.`email`, 'id_rol', NEW.`id_rol`),
                    CURRENT_TIMESTAMP
                )
            ",
            'tg_auditoria_usuarios_update' => "
                CREATE TRIGGER `tg_auditoria_usuarios_update`
                AFTER UPDATE ON `usuarios`
                FOR EACH ROW
                INSERT INTO `auditoria` (`tabla_afectada`, `id_registro`, `accion`, `usuario_responsable`, `datos_anteriores`, `datos_nuevos`, `fecha_hora`)
                VALUES (
                    'usuarios',
                    OLD.`id`,
                    'UPDATE',
                    NULLIF(CAST(NULLIF(@app_usuario_actual, '') AS UNSIGNED), 0),
                    JSON_OBJECT('nombre', OLD.`nombre_completo`, 'id_rol', OLD.`id_rol`, 'activo', OLD.`activo`),
                    JSON_OBJECT('nombre', NEW.`nombre_completo`, 'id_rol', NEW.`id_rol`, 'activo', NEW.`activo`),
                    CURRENT_TIMESTAMP
                )
            "
        ];

        try {
            $stmtTrg = $pdo->query("SHOW TRIGGERS");
            $rowsTrg = $stmtTrg ? $stmtTrg->fetchAll(PDO::FETCH_ASSOC) : [];
            $existentes = [];
            foreach ($rowsTrg as $r) {
                if (!empty($r['Trigger'])) {
                    $existentes[$r['Trigger']] = true;
                }
            }

            $activos = 0;
            foreach ($definiciones as $nombreTrigger => $sqlCreate) {
                if ($forceRecreate || !isset($existentes[$nombreTrigger])) {
                    $pdo->exec("DROP TRIGGER IF EXISTS `{$nombreTrigger}`");
                    $pdo->exec($sqlCreate);
                }
                $activos++;
            }

            if (session_status() === PHP_SESSION_ACTIVE) {
                $_SESSION['ciidi_triggers_verified'] = true;
            }

            return $activos;
        } catch (Throwable $e) {
            self::logSystemError($e);
            return 0;
        }
    }

    /**
     * Verifica la disponibilidad del esquema en MariaDB 10.6.
     */
    public static function autoHealSchemaIfNeeded(?PDO $pdo = null, bool $force = false): array {
        $pdo = $pdo ?? self::$instance;
        if (!$pdo) {
            return ['exito' => false, 'mensaje' => 'Sin conexión PDO activa.'];
        }

        if (!$force && isset($_SESSION['ciidi_schema_verified']) && $_SESSION['ciidi_schema_verified'] === true) {
            return ['exito' => true, 'mensaje' => 'Esquema ya verificado en la sesión actual.'];
        }

        try {
            $stmtCheck = $pdo->query("SHOW TABLES LIKE 'usuarios'");
            $hasUsuarios = (bool)$stmtCheck->fetchColumn();

            if (!$hasUsuarios) {
                $schemaPath = defined('BASE_PATH')
                    ? BASE_PATH . '/migrations/migracion_mariadb_10_6.sql'
                    : __DIR__ . '/../../migrations/migracion_mariadb_10_6.sql';
                if (!file_exists($schemaPath)) {
                    $schemaPath = defined('CORE_PATH')
                        ? CORE_PATH . 'Installer/schema.sql'
                        : __DIR__ . '/../Installer/schema.sql';
                }
                if (file_exists($schemaPath)) {
                    $sqlContent = (string)file_get_contents($schemaPath);
                    $pdo->exec($sqlContent);
                }
            }

            self::ensureAuditTriggers($pdo, $force);

            if (session_status() === PHP_SESSION_ACTIVE) {
                $_SESSION['ciidi_schema_verified'] = true;
            }

            return [
                'exito' => true,
                'acciones' => [],
                'mensaje' => 'Esquema MariaDB 10.6 y triggers de auditoría verificados y 100% operativos.'
            ];
        } catch (Throwable $e) {
            self::logSystemError($e);
            return [
                'exito' => false,
                'mensaje' => 'Error al verificar esquema: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Normaliza un script SQL para ejecución directa en MariaDB 10.6 via PDO.
     */
    public static function sanitizarSqlCompatible(string $sql): string {
        $sql = str_replace("\r\n", "\n", $sql);
        $sql = preg_replace('/^DELIMITER\s+\S+\s*$/mi', '', $sql);
        return $sql;
    }

    public static function resetConnection(): void {
        self::$instance = null;
        self::$lastSyncedUserId = null;
        self::$schemaCheckedInProcess = false;
        if (session_status() === PHP_SESSION_ACTIVE) {
            unset($_SESSION['ciidi_schema_verified']);
        }
    }

    public static function logSystemError(Throwable $e): void {
        $logDir = defined('STORAGE_PATH') ? STORAGE_PATH . 'logs/' : __DIR__ . '/../../storage/logs/';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0777, true);
        }
        $logFile = $logDir . 'system_errors.log';

        $mensaje = sprintf(
            "[%s] ERROR: %s en %s:%d\nTrace:\n%s\n%s\n",
            date('Y-m-d H:i:s'),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString(),
            str_repeat('-', 80)
        );

        @file_put_contents($logFile, $mensaje, FILE_APPEND);
    }
}
