<?php
// core/Database/Connection.php

class Connection {
    // La única instancia de la clase (Patrón Singleton)
    private static $instance = null;
    
    // El objeto PDO real
    private $pdo;

    // Credenciales de acceso (Asegúrate de cambiar esto por los de tu entorno local)
    private $host = 'localhost';
    private $port = '5432'; // Puerto por defecto de PostgreSQL
    private $db   = 'ciidi'; // Reemplaza esto
    private $user = 'miki';
    private $pass = '1234'; // Reemplaza esto

    private static function getConfigPath(): string {
        return defined('STORAGE_PATH') ? STORAGE_PATH . 'db_config.json' : __DIR__ . '/../../storage/db_config.json';
    }

    private function cargarCredenciales() {
        // 1. Cargar preferentemente desde variables de entorno (.env)
        if (class_exists('Env')) {
            $this->host = (string)Env::get('DB_HOST', $this->host);
            $this->port = (string)Env::get('DB_PORT', $this->port);
            $this->db   = (string)Env::get('DB_NAME', $this->db);
            $this->user = (string)Env::get('DB_USER', $this->user);
            $this->pass = (string)Env::get('DB_PASS', $this->pass);
        }

        // 2. Fallback retrocompatible desde db_config.json si las vars de entorno están vacías
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

    // El constructor es privado para evitar que alguien use "new Connection()" desde afuera
    private function __construct(bool $silencioso = false) {
        $this->cargarCredenciales();
        try {
            // Construcción del DSN para PostgreSQL
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db}";
            
            // Opciones de seguridad y rendimiento
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza excepciones ante errores SQL
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve arreglos asociativos puros
                PDO::ATTR_EMULATE_PREPARES   => false,                  // Delega la seguridad de parámetros a PostgreSQL
            ];

            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
            
        } catch (PDOException $e) {
            if ($silencioso) {
                return;
            }

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['db_connection_error'] = $e->getMessage();

            // Si es una petición AJAX / API, devolver 500 JSON
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode(['error' => true, 'mensaje' => 'Error de conexión a la BD: ' . $e->getMessage()]);
                exit;
            }

            http_response_code(500);
            $dbErrorMsg = "Imposible conectar a la base de datos: " . $e->getMessage();
            $isStandalone = true;
            $vista_modulo_path = defined('CORE_VIEWS') ? CORE_VIEWS . '500.php' : __DIR__ . '/../Views/500.php';

            if (file_exists($vista_modulo_path)) {
                include $vista_modulo_path;
            } else {
                die("Error 500: Fallo Crítico del Kernel - Imposible conectar a la base de datos: " . $e->getMessage());
            }
            exit;
        }
    }

    // Método estático para obtener la conexión
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            self::$instance = new Connection();
        }
        return self::$instance->pdo;
    }

    // Prevenir la clonación del objeto
    private function __clone() {}
    
    // Prevenir la deserialización del objeto
    public function __wakeup() {
        throw new Exception("No se puede deserializar una conexión a base de datos.");
    }

    /**
     * Devuelve las credenciales de PostgreSQL centralizadas del Core sin forzar error 500 si falla la BD.
     */
    public static function getCredentials(): array {
        $conn = new self(true); // Pasar flag silencioso
        return [
            'host' => $conn->host,
            'port' => $conn->port,
            'db'   => $conn->db,
            'user' => $conn->user,
            'pass' => $conn->pass,
        ];
    }

    /**
     * Guarda las credenciales de la base de datos tanto en .env como en storage/db_config.json
     */
    public static function saveCredentials(string $host, string $port, string $db, string $user, string $pass): bool {
        $envPath = defined('BASE_PATH') ? BASE_PATH . '/.env' : __DIR__ . '/../../.env';
        
        // 1. Escribir o actualizar en .env
        $envContent = "# Configuración del Entorno CIIDI\n" .
                      "APP_ENV=production\n" .
                      "APP_DEBUG=false\n" .
                      "DB_HOST=" . trim($host) . "\n" .
                      "DB_PORT=" . trim($port) . "\n" .
                      "DB_NAME=" . trim($db) . "\n" .
                      "DB_USER=" . trim($user) . "\n" .
                      "DB_PASS=" . $pass . "\n";
                      
        @file_put_contents($envPath, $envContent);

        // 2. Guardar en storage/db_config.json de forma segura
        $file = self::getConfigPath();
        $dir = dirname($file);
        if (!is_dir($dir)) {
            mkdir($dir, 0750, true);
        }
        $data = [
            'host' => trim($host),
            'port' => trim($port),
            'db'   => trim($db),
            'user' => trim($user),
            'pass' => $pass,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $res = file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
        @chmod($file, 0640);
        return $res;
    }

    /**
     * Retorna la ruta ejecutable de pg_dump / psql según el SO (Linux / Windows)
     */
    public static function getPgDumpPath(): string {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return '"C:\\Program Files\\PostgreSQL\\18\\bin\\pg_dump.exe"';
        }
        return 'pg_dump';
    }

    public static function getPsqlPath(): string {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return '"C:\\Program Files\\PostgreSQL\\18\\bin\\psql.exe"';
        }
        return 'psql';
    }
}