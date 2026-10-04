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

    /**
     * Resuelve la ruta física absoluta de un archivo digital en el almacenamiento,
     * soportando coincidencia exacta o fallback inteligente por prefijo slug.
     *
     * @param string|null $archivoRelativo
     * @return string|null Ruta absoluta en disco si existe, o null
     */
    public static function resolverRutaArchivoFisico(?string $archivoRelativo): ?string {
        if (empty($archivoRelativo)) {
            return null;
        }

        $relPath = ltrim(str_replace(['\\', '/'], '/', trim($archivoRelativo)), '/');
        if (strpos($relPath, '..') !== false) {
            return null;
        }

        $base = defined('BASE_PATH') ? BASE_PATH : dirname(dirname(dirname(__DIR__)));
        $fullPath = $base . '/' . $relPath;
        if (is_file($fullPath)) {
            return $fullPath;
        }

        // Fallback 1: Buscar en storage/documentos/pst/ o storage/documentos/tmp/
        $carpetas = [
            $base . '/storage/documentos/pst/',
            $base . '/storage/documentos/tmp/'
        ];

        $baseName = pathinfo($relPath, PATHINFO_FILENAME);
        $ext = strtolower(pathinfo($relPath, PATHINFO_EXTENSION));

        foreach ($carpetas as $dir) {
            if (!is_dir($dir)) continue;

            // 1.1 Coincidencia directa con nombre base
            if ($baseName) {
                $candidates = glob($dir . '*' . preg_replace('/[^a-zA-Z0-9_\-]/', '', $baseName) . '*');
                if (!empty($candidates)) {
                    foreach ($candidates as $cand) {
                        if (is_file($cand)) return $cand;
                    }
                }
            }

            // 1.2 Coincidencia por prefijo slug (removiendo timestamp _1234567890_123)
            $cleanPrefix = preg_replace('/_[0-9]+_[0-9]+$/', '', $baseName);
            if (!empty($cleanPrefix) && $cleanPrefix !== $baseName) {
                $pattern = $dir . '*' . preg_replace('/[^a-zA-Z0-9_\-]/', '', $cleanPrefix) . '*';
                $candidates = glob($pattern);
                if (!empty($candidates)) {
                    // Priorizar misma extensión si existe
                    foreach ($candidates as $cand) {
                        if (is_file($cand) && strtolower(pathinfo($cand, PATHINFO_EXTENSION)) === $ext) {
                            return $cand;
                        }
                    }
                    foreach ($candidates as $cand) {
                        if (is_file($cand)) return $cand;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Comprueba si el archivo digital existe físicamente en el almacenamiento del servidor.
     *
     * @param string|null $archivoRelativo
     * @return bool
     */
    public static function existeArchivoFisico(?string $archivoRelativo): bool {
        return self::resolverRutaArchivoFisico($archivoRelativo) !== null;
    }

    /**
     * Determina si el usuario tiene permiso para descargar Y el archivo existe físicamente en disco.
     */
    public static function puedeDescargarDocumento(?string $archivoRelativo, ?int $nivelUsuario = null): bool {
        return self::puedeDescargar($nivelUsuario) && self::existeArchivoFisico($archivoRelativo);
    }
}

