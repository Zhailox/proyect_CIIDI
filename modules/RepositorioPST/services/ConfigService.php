<?php
// modules/RepositorioPST/services/ConfigService.php

class ConfigService {
    private static ?string $configPath = null;
    private static ?array $cachedData = null;

    private static function getPath(): string {
        if (self::$configPath === null) {
            self::$configPath = __DIR__ . '/../config_pst.json';
        }
        return self::$configPath;
    }

    /**
     * Obtiene la estructura completa de configuración o una clave específica.
     */
    public static function get(?string $key = null, $default = null) {
        if (self::$cachedData === null) {
            $path = self::getPath();
            if (!file_exists($path)) {
                return $default;
            }
            $jsonContent = file_get_contents($path);
            $data = json_decode($jsonContent, true);
            if (!is_array($data)) {
                return $default;
            }
            self::$cachedData = $data;
        }

        $data = self::$cachedData;

        if ($key === null) {
            return $data;
        }

        $keys = explode('.', $key);
        $curr = $data;
        foreach ($keys as $k) {
            if (is_array($curr) && array_key_exists($k, $curr)) {
                $curr = $curr[$k];
            } else {
                return $default;
            }
        }

        return $curr;
    }

    /**
     * Guarda la configuración completa en el archivo JSON.
     */
    public static function save(array $data): bool {
        $path = self::getPath();
        $jsonContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $success = file_put_contents($path, $jsonContent, LOCK_EX) !== false;
        if ($success) {
            self::$cachedData = $data;
        }
        return $success;
    }
}
