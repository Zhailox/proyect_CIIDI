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
    private $user = 'postgres';
    private $pass = '1234'; // Reemplaza esto

    // El constructor es privado para evitar que alguien use "new Connection()" desde afuera
    private function __construct() {
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
}