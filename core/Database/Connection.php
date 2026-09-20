<?php
// core/Database/Connection.php

require_once __DIR__ . '/Exceptions/DatabaseConnectionException.php';

class Connection {
    // La única instancia de la clase (Patrón Singleton)
    private static $instance = null;
    
    // El objeto PDO real
    private $pdo;

    // Credenciales por defecto
    private $host = 'localhost';
    private $port = '5432';
    private $db   = 'ciidi';
    private $user = 'miki';
    private $pass = '1234';

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
            $data = json_decode(file_get_contents($file), true) ?: [];
            if (!empty($data['host']) && (empty($this->host) || $this->host === 'localhost')) $this->host = $data['host'];
            if (!empty($data['port'])) $this->port = (string)$data['port'];
            if (!empty($data['db']))   $this->db   = $data['db'];
            if (!empty($data['user'])) $this->user = $data['user'];
            if (isset($data['pass']))  $this->pass = $data['pass'];
        }
    }

    /**
     * Devuelve las credenciales de la base de datos de forma estática.
     */
    public static function getCredentials(): array {
        $dummy = new self(true);
        return [
            'host' => $dummy->host,
            'port' => $dummy->port,
            'db'   => $dummy->db,
            'user' => $dummy->user,
            'pass' => $dummy->pass
        ];
    }

    /**
     * Guarda las credenciales de la base de datos en el archivo storage/db_config.json.
     */
    public static function saveCredentials(string $host, string $port, string $db, string $user, string $pass): bool {
        $file = self::getConfigPath();
        $dir = dirname($file);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        $saveData = [
            'host' => trim($host),
            'port' => trim($port),
            'db'   => trim($db),
            'user' => trim($user),
            'pass' => $pass,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        return file_put_contents($file, json_encode($saveData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }

    /**
     * Detecta la ruta ejecutable de pg_dump en el sistema operativo.
     */
    public static function getPgDumpPath(): string {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $rutasCandidatas = [
                'C:\Program Files\PostgreSQL\18\bin\pg_dump.exe',
                'C:\Program Files\PostgreSQL\17\bin\pg_dump.exe',
                'C:\Program Files\PostgreSQL\16\bin\pg_dump.exe',
                'C:\Program Files\PostgreSQL\15\bin\pg_dump.exe',
                'C:\Program Files\PostgreSQL\14\bin\pg_dump.exe',
                'C:\Program Files\PostgreSQL\13\bin\pg_dump.exe',
                'C:\wamp64\bin\postgresql\bin\pg_dump.exe'
            ];

            foreach ($rutasCandidatas as $ruta) {
                if (file_exists($ruta)) {
                    return '"' . $ruta . '"';
                }
            }

            return 'pg_dump';
        }

        return 'pg_dump';
    }

    /**
     * Detecta la ruta ejecutable de psql en el sistema operativo.
     */
    public static function getPsqlPath(): string {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $rutasCandidatas = [
                'C:\Program Files\PostgreSQL\18\bin\psql.exe',
                'C:\Program Files\PostgreSQL\17\bin\psql.exe',
                'C:\Program Files\PostgreSQL\16\bin\psql.exe',
                'C:\Program Files\PostgreSQL\15\bin\psql.exe',
                'C:\Program Files\PostgreSQL\14\bin\psql.exe',
                'C:\Program Files\PostgreSQL\13\bin\psql.exe',
                'C:\wamp64\bin\postgresql\bin\psql.exe'
            ];

            foreach ($rutasCandidatas as $ruta) {
                if (file_exists($ruta)) {
                    return '"' . $ruta . '"';
                }
            }

            return 'psql';
        }

        return 'psql';
    }

    private function __construct(bool $silencioso = false) {
        $this->cargarCredenciales();
        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db}";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
            
        } catch (PDOException $e) {
            if ($silencioso) {
                return;
            }

            self::logSystemError($e);

            $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
            $appDebug = class_exists('Env') ? Env::get('APP_DEBUG', false) : false;
            $detalleDev = $appDebug ? $e->getMessage() : "Imposible conectar con el servidor de datos. Notifique al administrador.";

            throw new DatabaseConnectionException(
                'Error de infraestructura de datos. Por favor reintente más tarde.',
                (int)$e->getCode(),
                $e,
                $isAjax,
                $detalleDev
            );
        }
    }

    public static function getInstance(bool $silencioso = false): ?PDO {
        if (self::$instance === null) {
            $conexionObjeto = new self($silencioso);
            self::$instance = $conexionObjeto->pdo;
        }
        return self::$instance;
    }

    public static function resetConnection(): void {
        self::$instance = null;
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