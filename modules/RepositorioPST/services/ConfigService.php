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

    /**
     * Determina si el usuario actual tiene autorización para visualizar el botón de descarga
     * y descargar físicamente el documento digital según las políticas configuradas.
     *
     * @param int|null $nivelUsuario Nivel explícito a evaluar o null para el usuario de la sesión.
     * @return bool
     */
    public static function puedeDescargar(?int $nivelUsuario = null): bool {
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }

        $nivel = $nivelUsuario !== null ? $nivelUsuario : (int)($_SESSION['nivel_privilegio'] ?? 999);
        $estaAutenticado = isset($_SESSION['usuario_id']);

        // SuperAdmin (Nivel 0) siempre tiene acceso total
        if ($estaAutenticado && $nivel === 0) {
            return true;
        }

        // Si la descarga está desactivada globalmente, nadie excepto SuperAdmin puede descargar
        $permitirDescarga = (bool)self::get('visor_pdf.permitir_descarga', true);
        if (!$permitirDescarga) {
            return false;
        }

        $nivelMinimo = (int)self::get('visor_pdf.nivel_minimo_descarga', 999);

        // Nivel 999: Público General (incluso visitantes sin inicio de sesión)
        if ($nivelMinimo >= 999) {
            return true;
        }

        // Si se exige al menos usuario autenticado (998) o un rol específico
        if (!$estaAutenticado) {
            return false;
        }

        if ($nivelMinimo === 998) {
            return true;
        }

        // Para roles jerárquicos (menor número = mayor jerarquía en CIIDI)
        return $nivel <= $nivelMinimo;
    }
}

