<?php
class SystemConfigService {
    private static ?string $configPath = null;

    private static function getPath(): string {
        if (self::$configPath === null) {
            self::$configPath = __DIR__ . '/../../../storage/system_config.json';
        }
        
        if (!file_exists(self::$configPath)) {
            $modulosDir = realpath(__DIR__ . '/../../'); // Carpeta base de modulos/
            $accesosDinamicos = [
                "superadmin" => ["admin" => 0],
                "autenticacion" => ["publico" => 998, "admin" => 0]
            ];

            if ($modulosDir && is_dir($modulosDir)) {
                foreach (array_diff(scandir($modulosDir), ['.', '..']) as $carpeta) {
                    if (is_dir($modulosDir . '/' . $carpeta)) {
                        // Convierte "RepositorioPST" a "repositorio_p_s_t" o "Cursos" a "cursos"
                        $slug = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $carpeta));
                        if (!isset($accesosDinamicos[$slug])) {
                            $accesosDinamicos[$slug] = ["publico" => 999, "admin" => 1];
                        }
                    }
                }
            }

            $default = [
                "paginacion" => ["logs" => 50, "usuarios" => 15, "docentes" => 15],
                "seguridad" => ["timeout_minutos" => 120, "intentos_login" => 5],
                "smtp" => ["host" => "", "port" => 587, "user" => "sistema@universidad.edu", "pass" => "", "from_email" => "sistema@universidad.edu"],
                "accesos_modulos" => $accesosDinamicos
            ];
            file_put_contents(self::$configPath, json_encode($default, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
        return self::$configPath;
    }

    public static function get(?string $key = null, $default = 0) {
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