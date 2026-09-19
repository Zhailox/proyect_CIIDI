<?php
// core/System/Env.php

class Env {
    private static bool $loaded = false;

    /**
     * Carga y parsea el archivo .env a variables de entorno ($_ENV, $_SERVER, getenv)
     */
    public static function load(string $path): void {
        if (!file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            
            // Ignorar líneas vacías o comentarios
            if (empty($line) || str_starts_with($line, '#') || str_starts_with($line, ';')) {
                continue;
            }

            if (!str_contains($line, '=')) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value, " \t\n\r\0\x0B\"'");

            if (!empty($name)) {
                putenv("{$name}={$value}");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }

        self::$loaded = true;
    }

    /**
     * Obtiene el valor de una variable de entorno con soporte para valor por defecto
     */
    public static function get(string $key, mixed $default = null): mixed {
        $val = getenv($key);
        if ($val !== false) {
            return self::parseValue($val);
        }

        if (isset($_ENV[$key])) {
            return self::parseValue($_ENV[$key]);
        }

        if (isset($_SERVER[$key])) {
            return self::parseValue($_SERVER[$key]);
        }

        return $default;
    }

    private static function parseValue(string $value): mixed {
        $lower = strtolower($value);
        if ($lower === 'true' || $lower === '(true)') return true;
        if ($lower === 'false' || $lower === '(false)') return false;
        if ($lower === 'empty' || $lower === '(empty)') return '';
        if ($lower === 'null' || $lower === '(null)') return null;
        return $value;
    }
}
