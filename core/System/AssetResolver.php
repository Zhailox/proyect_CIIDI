<?php
// core/System/AssetResolver.php

class AssetResolver {
    
    /**
     * Resuelve los archivos CSS requeridos por una ruta específica.
     */
    public function resolveRouteCss(string $ruta, array $configRuta, array $modulosInstalados): array {
        $cssDeclarados = $configRuta['css'] ?? [];
        if (empty($cssDeclarados)) return [];

        $nombreCarpeta = $this->deducirCarpetaModulo($ruta, $configRuta, $modulosInstalados);
        if ($nombreCarpeta === null) return [];

        $cssModulo = [];
        foreach ($cssDeclarados as $archivoCss) {
            $cssModulo[] = '../modules/' . $nombreCarpeta . '/assets/css/' . $archivoCss;
        }
        return $cssModulo;
    }

    /**
     * Resuelve los archivos JS requeridos por una ruta específica.
     */
    public function resolveRouteJs(string $ruta, array $configRuta, array $modulosInstalados): array {
        $jsDeclarados = $configRuta['js'] ?? [];
        if (empty($jsDeclarados)) return [];

        $nombreCarpeta = $this->deducirCarpetaModulo($ruta, $configRuta, $modulosInstalados);
        if ($nombreCarpeta === null) return [];

        $jsModulo = [];
        foreach ($jsDeclarados as $archivoJs) {
            $jsModulo[] = '../modules/' . $nombreCarpeta . '/assets/js/' . $archivoJs;
        }
        return $jsModulo;
    }

    /**
     * Recolecta el CSS asignado específicamente para la pantalla de inicio.
     */
    public function getHomeCss(array $modulosInstalados): array {
        $cssHome = [];
        foreach ($modulosInstalados as $carpeta => $modulo) {
            if (is_object($modulo) && method_exists($modulo, 'getHomeConfig')) {
                $config = $modulo->getHomeConfig();
                if (!empty($config) && isset($config['css'])) {
                    $cssHome[] = '../modules/' . $carpeta . '/assets/css/' . $config['css'];
                }
            }
        }
        return array_unique($cssHome);
    }

    /**
     * Recolecta los controles del header inyectados por cada módulo.
     */
    public function getControlesHeader(array $modulosInstalados): array {
        $controles = [];

        foreach ($modulosInstalados as $modulo) {
            if (is_object($modulo) && method_exists($modulo, 'getHeaderConfig')) {
                $config = $modulo->getHeaderConfig();

                if (!empty($config)) {
                    if (isset($config['tipo'])) {
                        $controles[] = $config;
                    } else {
                        foreach ($config as $subConfig) {
                            $controles[] = $subConfig;
                        }
                    }
                }
            }
        }

        usort($controles, function($a, $b) {
            $pesoA = $a['orden'] ?? 50;
            $pesoB = $b['orden'] ?? 50;
            return $pesoA <=> $pesoB;
        });

        return $controles;
    }

    /**
     * Recolecta los archivos CSS globales inyectados por los controles del header.
     */
    public function getGlobalCss(array $modulosInstalados): array {
        $cssGlobales = [];

        foreach ($modulosInstalados as $carpeta => $modulo) {
            if (is_object($modulo) && method_exists($modulo, 'getHeaderConfig')) {
                $config = $modulo->getHeaderConfig();

                if (!empty($config)) {
                    $controles = isset($config['tipo']) ? [$config] : $config;

                    foreach ($controles as $control) {
                        if (isset($control['css'])) {
                            $cssGlobales[] = '../modules/' . $carpeta . '/assets/css/' . $control['css'];
                        }
                    }
                }
            }
        }

        return array_unique($cssGlobales);
    }

    /**
     * Recolecta las tarjetas de acceso rápido inyectadas en la pantalla de inicio.
     */
    public function getTarjetasInicio(array $modulosInstalados): array {
        $tarjetas = [];

        foreach ($modulosInstalados as $modulo) {
            if (is_object($modulo) && method_exists($modulo, 'getHomeConfig')) {
                $config = $modulo->getHomeConfig();
                if (!empty($config)) {
                    $tarjetas[] = $config;
                }
            }
        }

        return $tarjetas;
    }
    private function deducirCarpetaModulo(string $ruta, array $configRuta, array $modulosInstalados): ?string {
    // Estrategia 1: parsear controlador_path
    if (!empty($configRuta['controlador_path'])) {
        $path = str_replace('\\', '/', $configRuta['controlador_path']);
        if (preg_match('#/modules/([^/]+)/#i', $path, $m)) {
            return $m[1];
        }
    }

    // Estrategia 2: buscar en módulos instalados por ruta
    foreach ($modulosInstalados as $carpeta => $instancia) {
        if (is_object($instancia) && method_exists($instancia, 'getRutas')) {
            if (array_key_exists($ruta, $instancia->getRutas())) {
                return $carpeta;
            }
        }
    }

    return null;
}
}
