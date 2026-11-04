<?php
// core/Database/Connection.php

require_once __DIR__ . '/Exceptions/DatabaseConnectionException.php';

class Connection {
    // La única instancia de la clase (Patrón Singleton)
    private static $instance = null;
    
    // El objeto PDO real
    private $pdo;

    // Credenciales por defecto (MariaDB 10.6+)
    private $host = 'localhost';
    private $port = '3306';
    private $db   = 'ciidi';
    private $user = 'miki';
    private $pass = '1234';

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
     * Detecta la ruta ejecutable de mysqldump / mariadb-dump en Windows y Linux (cPanel / CloudLinux).
     */
    public static function getMysqldumpPath(): string {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $globs = array_merge(
                glob('C:\wamp64\bin\mariadb\*\bin\mysqldump.exe') ?: [],
                glob('C:\wamp64\bin\mysql\*\bin\mysqldump.exe') ?: [],
                glob('C:\xampp\mysql\bin\mysqldump.exe') ?: [],
                glob('C:\Program Files\MariaDB *\bin\mysqldump.exe') ?: [],
                glob('C:\Program Files\MySQL\MySQL Server *\bin\mysqldump.exe') ?: []
            );
            if (!empty($globs)) {
                rsort($globs, SORT_NATURAL);
                return '"' . $globs[0] . '"';
            }
            return 'mysqldump';
        }

        $linuxCandidatas = [
            '/usr/bin/mariadb-dump',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            '/usr/local/cpanel/3rdparty/bin/mysqldump',
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
     * Detecta la ruta ejecutable del cliente mysql / mariadb en Windows y Linux (cPanel / CloudLinux).
     */
    public static function getMysqlPath(): string {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $globs = array_merge(
                glob('C:\wamp64\bin\mariadb\*\bin\mysql.exe') ?: [],
                glob('C:\wamp64\bin\mysql\*\bin\mysql.exe') ?: [],
                glob('C:\xampp\mysql\bin\mysql.exe') ?: [],
                glob('C:\Program Files\MariaDB *\bin\mysql.exe') ?: [],
                glob('C:\Program Files\MySQL\MySQL Server *\bin\mysql.exe') ?: []
            );
            if (!empty($globs)) {
                rsort($globs, SORT_NATURAL);
                return '"' . $globs[0] . '"';
            }
            return 'mysql';
        }

        $linuxCandidatas = [
            '/usr/bin/mariadb',
            '/usr/bin/mysql',
            '/usr/local/bin/mysql',
            '/usr/local/cpanel/3rdparty/bin/mysql',
            '/bin/mysql'
        ];
        foreach ($linuxCandidatas as $ruta) {
            if (@is_executable($ruta) || @file_exists($ruta)) {
                return $ruta;
            }
        }

        return 'mysql';
    }

    /**
     * Alias de compatibilidad hacia atrás para llamadas heredadas.
     */
    public static function getPgDumpPath(): string {
        return self::getMysqldumpPath();
    }

    /**
     * Alias de compatibilidad hacia atrás para llamadas heredadas.
     */
    public static function getPsqlPath(): string {
        return self::getMysqlPath();
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
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_TIMEOUT            => 5,
            ];

            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
            $this->pdo->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci, SESSION group_concat_max_len = 1048576");
            
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

            if (self::$instance !== null && !self::$schemaCheckedInProcess) {
                self::$schemaCheckedInProcess = true;
                self::autoHealSchemaIfNeeded(self::$instance);
                if (empty($_SESSION['ciidi_triggers_verified'])) {
                    self::ensureAuditTriggers(self::$instance);
                }
            }
        }
        if (self::$instance !== null) {
            $rawUid = $_SESSION['usuario_id'] ?? ($_SESSION['usuario']['id'] ?? null);
            if ($rawUid !== null && is_numeric($rawUid)) {
                $uid = (int)$rawUid;
                if ($uid > 0 && self::$lastSyncedUserId !== $uid) {
                    try {
                        $stmt = self::$instance->prepare("SET @app_usuario_actual = ?");
                        $stmt->execute([$uid]);
                        self::$lastSyncedUserId = $uid;
                    } catch (Throwable) {
                        // Ignorar si la sesión de BD no admite el ajuste temporalmente
                    }
                }
            }
        }
        return self::$instance;
    }

    /**
     * Garantiza que la tabla `auditoria` y los 5 triggers nativos existan y estén operativos en MariaDB 10.6+.
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
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `auditoria` (
                    `id` INT NOT NULL AUTO_INCREMENT,
                    `tabla_afectada` VARCHAR(50) NOT NULL,
                    `id_registro` INT NOT NULL,
                    `accion` ENUM('INSERT', 'UPDATE', 'DELETE') NOT NULL,
                    `usuario_responsable` INT,
                    `ip_origen` VARCHAR(45),
                    `datos_anteriores` JSON,
                    `datos_nuevos` JSON,
                    `fecha_hora` DATETIME DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");

            $stmtTrg = $pdo->query("SHOW TRIGGERS");
            $rowsTrg = $stmtTrg ? $stmtTrg->fetchAll(PDO::FETCH_ASSOC) : [];
            if ($stmtTrg) {
                $stmtTrg->closeCursor();
            }
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
     * Verifica y repara automáticamente estructuras críticas en MariaDB 10.6+ (InnoDB / utf8mb4_unicode_ci).
     */
    public static function autoHealSchemaIfNeeded(?PDO $pdo = null, bool $force = false): array {
        $pdo = $pdo ?? self::$instance;
        if (!$pdo) {
            return ['exito' => false, 'mensaje' => 'Sin conexión PDO activa.'];
        }

        if (!$force && isset($_SESSION['ciidi_schema_verified']) && $_SESSION['ciidi_schema_verified'] === true) {
            return ['exito' => true, 'mensaje' => 'Esquema ya verificado en la sesión actual.'];
        }

        $acciones = [];

        try {
            $stmtCheck = $pdo->query("
                SELECT table_name
                FROM information_schema.tables
                WHERE table_schema = DATABASE()
                  AND table_name IN ('usuarios', 'recursos', 'detalles_proyectos', 'proyecto_carreras_vinculadas', 'niveles_academicos', 'investigadores')
            ");
            $existing = array_flip(array_map('strtolower', $stmtCheck->fetchAll(PDO::FETCH_COLUMN) ?: []));

            if (!isset($existing['usuarios']) || !isset($existing['recursos'])) {
                $candidatosSchema = [
                    defined('STORAGE_PATH') ? STORAGE_PATH . 'backups/migracion_mariadb_10_6.sql' : __DIR__ . '/../../storage/backups/migracion_mariadb_10_6.sql',
                    defined('CORE_PATH') ? CORE_PATH . 'Installer/schema.sql' : __DIR__ . '/../Installer/schema.sql'
                ];
                foreach ($candidatosSchema as $schemaPath) {
                    if (file_exists($schemaPath) && filesize($schemaPath) > 1000) {
                        $sqlContent = (string)file_get_contents($schemaPath);
                        if (stripos($sqlContent, 'PostgreSQL database dump') === false) {
                            self::ejecutarScriptSqlMariaDB($pdo, $sqlContent);
                            $acciones[] = 'Esquema MariaDB base importado automáticamente.';
                            break;
                        }
                    }
                }
            } else {
                if (!isset($existing['detalles_proyectos'])) {
                    $pdo->exec("
                        CREATE TABLE IF NOT EXISTS `detalles_proyectos` (
                            `id_recurso` INT NOT NULL,
                            `fecha_defensa` DATE,
                            `nivel_academico` VARCHAR(50) DEFAULT 'Pregrado',
                            `resumen` TEXT,
                            `id_carrera` INT,
                            `comunidad_beneficiada` TEXT,
                            `palabras_clave` TEXT,
                            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                            `id_investigacion_padre` INT,
                            `url_repositorio` TEXT,
                            `obj_general` TEXT,
                            `activo` TINYINT(1) DEFAULT 1,
                            `id_trayecto` INT,
                            `vistas` INT DEFAULT 0 NOT NULL,
                            PRIMARY KEY (`id_recurso`),
                            KEY `idx_detalles_proyectos_trayecto` (`id_trayecto`),
                            FULLTEXT KEY `idx_ft_detalles_proyectos` (`resumen`, `palabras_clave`, `obj_general`, `comunidad_beneficiada`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                    ");
                    $acciones[] = 'Tabla detalles_proyectos reconstruida en MariaDB InnoDB.';
                }

                if (!isset($existing['proyecto_carreras_vinculadas'])) {
                    $pdo->exec("
                        CREATE TABLE IF NOT EXISTS `proyecto_carreras_vinculadas` (
                            `id_recurso` INT NOT NULL,
                            `id_carrera` INT NOT NULL,
                            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                            PRIMARY KEY (`id_recurso`, `id_carrera`),
                            CONSTRAINT `fk_pcv_recurso` FOREIGN KEY (`id_recurso`) REFERENCES `recursos` (`id`) ON DELETE CASCADE,
                            CONSTRAINT `fk_pcv_carrera` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id`) ON DELETE CASCADE
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                    ");
                    $acciones[] = 'Tabla proyecto_carreras_vinculadas verificada.';
                }

                if (!isset($existing['niveles_academicos'])) {
                    $pdo->exec("
                        CREATE TABLE IF NOT EXISTS `niveles_academicos` (
                            `id` INT NOT NULL AUTO_INCREMENT,
                            `codigo` VARCHAR(50) NOT NULL,
                            `nombre` VARCHAR(100) NOT NULL,
                            `descripcion` TEXT,
                            `requiere_trayecto` TINYINT(1) DEFAULT 0,
                            `orden` INT DEFAULT 0,
                            `activo` TINYINT(1) DEFAULT 1,
                            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                            PRIMARY KEY (`id`),
                            UNIQUE KEY `niveles_academicos_codigo_key` (`codigo`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                    ");
                    $pdo->exec("
                        INSERT IGNORE INTO `niveles_academicos` (`codigo`, `nombre`, `descripcion`, `requiere_trayecto`, `orden`, `activo`)
                        VALUES 
                            ('Pregrado', 'Pregrado', 'Estudios conducentes a título universitario de pregrado o ingeniería', 1, 1, 1),
                            ('Especializacion', 'Especialización', 'Estudios de postgrado para especialización técnica', 0, 2, 1),
                            ('Maestria', 'Maestría', 'Estudios de postgrado de investigación científica y magíster', 0, 3, 1),
                            ('Doctorado', 'Doctorado', 'Máximo grado académico de investigación epistémica', 0, 4, 1)
                    ");
                    $acciones[] = 'Catálogo niveles_academicos verificado.';
                }

                if (!isset($existing['investigadores'])) {
                    $pdo->exec("
                        CREATE TABLE IF NOT EXISTS `investigadores` (
                            `id` INT NOT NULL AUTO_INCREMENT,
                            `nombre_completo` VARCHAR(150) NOT NULL,
                            `orcid` VARCHAR(50) NOT NULL,
                            `biografia` TEXT,
                            `pagina_web` VARCHAR(255),
                            PRIMARY KEY (`id`),
                            UNIQUE KEY `investigadores_orcid_key` (`orcid`),
                            FULLTEXT KEY `idx_ft_investigadores` (`nombre_completo`, `biografia`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                    ");
                    $pdo->exec("
                        CREATE TABLE IF NOT EXISTS `recurso_investigadores` (
                            `id_recurso` INT NOT NULL,
                            `id_investigador` INT NOT NULL,
                            PRIMARY KEY (`id_recurso`, `id_investigador`),
                            CONSTRAINT `fk_recurso_inv` FOREIGN KEY (`id_recurso`) REFERENCES `recursos` (`id`) ON DELETE CASCADE,
                            CONSTRAINT `fk_investigador` FOREIGN KEY (`id_investigador`) REFERENCES `investigadores` (`id`) ON DELETE CASCADE
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                    ");
                    $acciones[] = 'Tablas de investigadores verificadas.';
                }
            }

            self::ensureAuditTriggers($pdo, $force);

            if (session_status() === PHP_SESSION_ACTIVE) {
                $_SESSION['ciidi_schema_verified'] = true;
            }

            return [
                'exito' => true,
                'acciones' => $acciones,
                'mensaje' => empty($acciones)
                    ? 'Esquema MariaDB verificado y 100% operativo.'
                    : implode(' ', $acciones)
            ];
        } catch (Throwable $e) {
            self::logSystemError($e);
            return [
                'exito' => false,
                'mensaje' => 'Error al verificar/reparar esquema: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Sanitiza un script SQL para su ejecución segura sobre MariaDB 10.6+.
     */
    public static function sanitizarSqlCompatible(string $sql): string {
        $sql = str_replace("\r\n", "\n", $sql);
        // Remover BOM UTF-8 si existe
        if (str_starts_with($sql, "\xEF\xBB\xBF")) {
            $sql = substr($sql, 3);
        }
        // Remover cláusulas DEFINER de triggers/vistas exportadas desde otro usuario cPanel
        $sql = preg_replace('/\bDEFINER\s*=\s*`[^`]+`@`[^`]+`\s+/i', '', $sql);
        return $sql;
    }

    /**
     * Ejecuta un volcado SQL completo de MariaDB/MySQL sentencia por sentencia vía PDO,
     * soportando directivas DELIMITER para Triggers/Procedimientos sin requerir exec()/CLI.
     */
    public static function ejecutarScriptSqlMariaDB(PDO $pdo, string $sql): int {
        $sql = self::sanitizarSqlCompatible($sql);
        $lineas = explode("\n", $sql);
        $delimiter = ';';
        $buffer = '';
        $ejecutadas = 0;

        foreach ($lineas as $linea) {
            $trimmed = trim($linea);

            if ($buffer === '' && ($trimmed === '' || str_starts_with($trimmed, '-- ') || $trimmed === '--' || str_starts_with($trimmed, '#'))) {
                continue;
            }

            if (preg_match('/^DELIMITER\s+(\S+)\s*$/i', $trimmed, $m)) {
                $delimiter = $m[1];
                continue;
            }

            $buffer .= $linea . "\n";

            $rtrimmed = rtrim($buffer);
            if ($delimiter !== '' && str_ends_with($rtrimmed, $delimiter)) {
                // Verificar que no estemos dentro de comillas abiertas cuando el delimitador es ';'
                if ($delimiter !== ';' || self::sentenciaSqlBalanceada($rtrimmed)) {
                    $stmtSql = trim(substr($rtrimmed, 0, -strlen($delimiter)));
                    $buffer = '';
                    if ($stmtSql !== '') {
                        $stmt = $pdo->query($stmtSql);
                        if ($stmt instanceof PDOStatement) {
                            $stmt->closeCursor();
                        }
                        $ejecutadas++;
                    }
                }
            }
        }

        $restante = trim($buffer);
        if ($restante !== '') {
            $stmt = $pdo->query($restante);
            if ($stmt instanceof PDOStatement) {
                $stmt->closeCursor();
            }
            $ejecutadas++;
        }

        return $ejecutadas;
    }

    /**
     * Comprueba que una cadena SQL no termine dentro de un literal entrecomillado o comentario de bloque.
     */
    private static function sentenciaSqlBalanceada(string $sql): bool {
        $len = strlen($sql);
        $inSingle = false;
        $inDouble = false;
        $inBacktick = false;
        $inBlockComment = false;

        for ($i = 0; $i < $len; $i++) {
            $ch = $sql[$i];
            $next = ($i + 1 < $len) ? $sql[$i + 1] : '';

            if ($inBlockComment) {
                if ($ch === '*' && $next === '/') {
                    $inBlockComment = false;
                    $i++;
                }
                continue;
            }

            if ($inSingle) {
                if ($ch === '\\') {
                    $i++;
                } elseif ($ch === "'") {
                    if ($next === "'") {
                        $i++;
                    } else {
                        $inSingle = false;
                    }
                }
                continue;
            }

            if ($inDouble) {
                if ($ch === '\\') {
                    $i++;
                } elseif ($ch === '"') {
                    $inDouble = false;
                }
                continue;
            }

            if ($inBacktick) {
                if ($ch === '`') {
                    $inBacktick = false;
                }
                continue;
            }

            if ($ch === '/' && $next === '*' && ($i + 2 >= $len || $sql[$i + 2] !== '!')) {
                $inBlockComment = true;
                $i++;
            } elseif ($ch === "'") {
                $inSingle = true;
            } elseif ($ch === '"') {
                $inDouble = true;
            } elseif ($ch === '`') {
                $inBacktick = true;
            } elseif (($ch === '-' && $next === '-' && ($i + 2 >= $len || ctype_space($sql[$i + 2]))) || $ch === '#') {
                $nl = strpos($sql, "\n", $i);
                if ($nl === false) {
                    break;
                }
                $i = $nl;
            }
        }

        return !$inSingle && !$inDouble && !$inBacktick && !$inBlockComment;
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
