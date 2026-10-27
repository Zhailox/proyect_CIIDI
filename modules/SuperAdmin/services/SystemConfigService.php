<?php
class SystemConfigService {
    private static ?string $configPath = null;
    private static ?array $cache = null; // Caché en RAM para no leer el disco múltiples veces

    public static function getDefaultFooter(): array {
        return [
            "columnas" => [
                [
                    "titulo" => "Ubícanos",
                    "icono"  => "ph-fill ph-map-pin",
                    "bloques_texto" => [
                        "<strong>Dirección:</strong> Núcleo San Luis (Sede Rectoral)\nAv. La Feria Sector San Luis Valera, Estado Trujillo - Venezuela",
                        "<strong><i class=\"ph-fill ph-phone\"></i> Teléfonos:</strong>\nRectorado: (58) 0271-221.29.27\nVicerrectorado Académico: 0271-221.51.47"
                    ],
                    "enlaces" => [],
                    "lema"    => ""
                ],
                [
                    "titulo" => "Núcleos",
                    "icono"  => "ph-fill ph-buildings",
                    "bloques_texto" => [
                        "<strong>Boconó:</strong> Calle Colón con Av. Independencia (Antiguo Hospital \"Rafael Rangel\"). Tel: 0272-652.31.11\n<strong>El Dividive:</strong> Autopista Panamericana, El Dividive. Tel: 0272-666.01.14\n<strong>La Beatriz:</strong> Urb. La Beatriz Parte Alta, al lado del Parque Botánico. Tel: 0271-231.11.10\n<strong>Trujillo:</strong> Av. Cristóbal Mendoza detrás del Mercado Municipal. Tel: 0272-236.31.61\n<strong>Ext. Carache:</strong> Calle Vargas entre Av.2 y Av.3, al lado Alcaldía. Tel: 0272-999.16.05"
                    ],
                    "enlaces" => [],
                    "lema"    => ""
                ],
                [
                    "titulo" => "Redes Sociales",
                    "icono"  => "ph-fill ph-share-network",
                    "bloques_texto" => [],
                    "enlaces" => [
                        ["icono" => "ph-fill ph-facebook-logo", "texto" => "Gestión UPTTMBI", "url" => "#"],
                        ["icono" => "ph-fill ph-twitter-logo", "texto" => "@upttmbi", "url" => "#"],
                        ["icono" => "ph-fill ph-instagram-logo", "texto" => "@upttmbi", "url" => "#"],
                        ["icono" => "ph-fill ph-youtube-logo", "texto" => "@upttmbi", "url" => "#"]
                    ],
                    "lema" => "\"Somos Excelencia Académica para Construir Patria...\""
                ]
            ],
            "bottom_izquierda" => "© 2026 UPTTMBI. Todos los derechos reservados.",
            "bottom_derecha"   => "Desarrollado por PNF Informática"
        ];
    }

    private static function getPath(): string {
        if (self::$configPath === null) {
            self::$configPath = __DIR__ . '/../../../storage/system_config.json';
        }
        
        if (!file_exists(self::$configPath)) {
            $modulosDir = realpath(__DIR__ . '/../../');$accesosDinamicos = [
                "superadmin" => ["admin" => 0],
                "autenticacion" => ["publico" => 998, "admin" => 0]
            ];

            if ($modulosDir && is_dir($modulosDir)) {
                foreach (array_diff(scandir($modulosDir), ['.', '..']) as$carpeta) {
                    if (is_dir($modulosDir . '/' . $carpeta)) {$slug = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2',$carpeta));
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
                "accesos_modulos" => $accesosDinamicos,
                "footer" => self::getDefaultFooter()
            ];
            file_put_contents(self::$configPath, json_encode($default, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            self::$cache =$default;
        }
        return self::$configPath;
    }

    public static function get(?string $key = null,$default = 0) {
        if (self::$cache === null) {
            self::$cache = json_decode(file_get_contents(self::getPath()), true) ?: [];
            if (!isset(self::$cache['footer'])) {
                self::$cache['footer'] = self::getDefaultFooter();
            }
        }
        
        if ($key === null) return self::$cache;
        
        $keys = explode('.',$key);
        $curr = self::$cache;
        foreach ($keys as$k) {
            if (is_array($curr) && array_key_exists($k, $curr)) {$curr = $curr[$k];
            } else {
                return $default;
            }
        }
        return $curr;
    }

    public static function save(array $data): bool {
        $ok = file_put_contents(self::getPath(), json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
        if ($ok) {
            self::$cache =$data;
        }
        return $ok;
    }
}