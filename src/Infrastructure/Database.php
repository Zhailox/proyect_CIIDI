<?php
declare(strict_types=1);
namespace App\Infrastructure;
use PDO, PDOException;

class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): ?PDO {
        if (self::$instance === null) {
            try {
                $dsn = "mysql:host=127.0.0.1;dbname=uptmbi_investigacion;charset=utf8mb4";
                self::$instance = new PDO($dsn, 'root', '', [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                error_log("DB Error: " . $e->getMessage());
                return null;
            }
        }
        return self::$instance;
    }
}
