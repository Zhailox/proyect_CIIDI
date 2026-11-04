<?php
// modules/VinculacionEmpresarial/services/ConfigService.php

class VinculacionConfigService {
    private static ?string $configPath = null;
    private static ?array $cachedData = null;

    private static function getPath(): string {
        return self::$configPath ??= __DIR__ . '/../config_vinculacion.json';
    }

    public static function get(?string $key = null, mixed $default = null): mixed {
        if (self::$cachedData === null) {
            $path = self::getPath();
            if (!file_exists($path)) {
                return $default;
            }
            $jsonContent = file_get_contents($path);
            $data = json_decode($jsonContent, true);
            self::$cachedData = is_array($data) ? $data : [];
        }

        if ($key === null) {
            return self::$cachedData;
        }

        return self::$cachedData[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): bool {
        $data = self::get();
        $data[$key] = $value;
        self::$cachedData = $data;

        $path = self::getPath();
        $jsonContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        return file_put_contents($path, $jsonContent) !== false;
    }

    public static function setMultiple(array $values): bool {
        $data = self::get();
        foreach ($values as $key => $value) {
            $data[$key] = $value;
        }
        self::$cachedData = $data;

        $path = self::getPath();
        $jsonContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        return file_put_contents($path, $jsonContent) !== false;
    }
}
