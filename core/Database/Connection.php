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
    private $pass = '1234'; // Unificado con la rama main

    private static function getConfigPath(): string {
        return defined('STORAGE_PATH') ? STORAGE_PATH . 'db_config.json' : __DIR__ . '/../../storage/db_config.json';
    }

    private function cargarCredenciales() {
        $file = self::getConfigPath();
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true) ?: [];
            if (!empty($data['host'])) $this->host = $data['host'];
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
            
            // Forzar codificación UTF-8 para evitar caracteres extraños en la vista
            $this->pdo->exec("SET NAMES 'UTF8'");

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
                require_once $vista_modulo_path;
            } else {
                echo "<h1>Error Crítico del Sistema (500)</h1>";
                echo "<p>Imposible conectar a la base de datos. Por favor, verifique sus credenciales.</p>";
                echo "<p><em>Detalle: " . htmlspecialchars($e->getMessage()) . "</em></p>";
            }
            exit;
        }
    }

    // Método estático para obtener la instancia única
    public static function getInstance(bool $silencioso = false) {
        if (self::$instance === null) {
            self::$instance = new Connection($silencioso);
        }
        return self::$instance->pdo;
    }

    // Prevenir la clonación del objeto
    private function __clone() {}

    // Prevenir la deserialización del objeto
    public function __wakeup() {
        throw new Exception("No se puede deserializar una conexión a la base de datos.");
    }
}
?>
