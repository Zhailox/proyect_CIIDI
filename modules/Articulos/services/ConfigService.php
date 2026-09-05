<?php
// modules/Articulos/services/ConfigService.php

class ConfigService {
    private static ?string $configPath = null;

    private static function getPath(): string {
        if (self::$configPath === null) {
            self::$configPath = __DIR__ . '/../config_articulos.json';
        }
        // Si no existe, creamos uno por defecto
        if (!file_exists(self::$configPath)) {
            $default = [
                "citas" => ["estilos" => [
                    "apa7" => ["nombre" => "Estilo APA", "activo" => true, "plantilla" => "{autores} ({anio}). {titulo}. {editorial}. Vol {volumen}({numero}). ISSN: {issn}"]
                ]],
                "paginacion" => ["limite_catalogo" => 16, "limite_gestor" => 15, "max_recomendados" => 3],
                "recursos" => ["mostrar_editorial" => true, "mostrar_volumen" => true, "mostrar_issn" => true],
                "buscador" => ["anio_minimo" => 2020, "resaltar_coincidencias" => true],
                "archivos" => ["max_size_mb" => 5, "max_autores" => 6]
            ];
            file_put_contents(self::$configPath, json_encode($default, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
        return self::$configPath;
    }

    public static function get(?string $key = null, $default = null) {
        $data = json_decode(file_get_contents(self::getPath()), true);
        if ($key === null) return $data;
        $keys = explode('.', $key);
        $curr = $data;
        foreach ($keys as $k) {
            if (is_array($curr) && array_key_exists($k, $curr)) { $curr = $curr[$k]; } 
            else { return $default; }
        }
        return $curr;
    }

    public static function save(array $data): bool {
        return file_put_contents(self::getPath(), json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }
}