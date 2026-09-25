<?php
// core/System/ModuleLoader.php

require_once CORE_PATH . 'Interfaces/ModuleContract.php';

class ModuleLoader {
    private string $modulesPath;
    private string $configFilePath;
    private string $legacyFilePath;

    private array $modulosInstalados = [];
    private array $rutasGlobales = [];
    private array $menuGlobal = [];

    public function __construct(?string $modulesPath = null, ?string $configFilePath = null) {
        $this->modulesPath = $modulesPath ?? MODULES_PATH;
        $this->configFilePath = $configFilePath ?? (__DIR__ . '/../../storage/config_system.json');
        $this->legacyFilePath = __DIR__ . '/../../storage/modules.json';
    }

    /**
     * Escanea la carpeta de módulos y compila los módulos habilitados, sus rutas y menús.
     */
    public function loadModules(): void {
        if (!is_dir($this->modulesPath)) {
            return;
        }

        $carpetas = array_diff(scandir($this->modulesPath), ['.', '..']);
        $estadosModulos = $this->obtenerEstadosModulos();

        foreach ($carpetas as $carpeta) {
            $modConfig = $estadosModulos[$carpeta] ?? ['estado' => 'online'];
            $estadoActual = is_array($modConfig) ? ($modConfig['estado'] ?? 'online') : $modConfig;
            $esCore = in_array($carpeta, ['Autenticacion', 'SuperAdmin'], true);

            $rutaIndexModulo = rtrim($this->modulesPath, '/\\') . '/' . $carpeta . '/index.php';

            if (file_exists($rutaIndexModulo)) {
                $claseModulo = $carpeta . 'Module';
                if (class_exists($claseModulo)) {
                    $modulo = new $claseModulo();
                } else {
                    $modulo = require_once $rutaIndexModulo;
                    if ($modulo === true && class_exists($claseModulo)) {
                        $modulo = new $claseModulo();
                    }
                }

                if ($modulo instanceof ModuleContract) {
                    // Solo registrar como activo e instalar rutas si el módulo está online o es Core
                    if ($estadoActual !== 'offline' || $esCore) {
                        $this->modulosInstalados[$carpeta] = $modulo;
                        $this->rutasGlobales = array_merge($this->rutasGlobales, $modulo->getRutas());
                    }

                    // Siempre compilar el menú global etiquetado para soporte de reactivación dinámica en tiempo real
                    $configMenuModulo = $modulo->getMenuConfig();
                    if (!empty($configMenuModulo)) {
                        foreach ($configMenuModulo as &$itemMenu) {
                            $itemMenu['modulo_origen'] = $carpeta;
                            $itemMenu['modulo_estado'] = $esCore ? 'online' : $estadoActual;
                        }
                        unset($itemMenu);
                        $this->menuGlobal = array_merge($this->menuGlobal, $configMenuModulo);
                    }
                }
            }
        }
    }

    private function obtenerEstadosModulos(): array {
        if (file_exists($this->configFilePath)) {
            $config = json_decode(file_get_contents($this->configFilePath), true) ?: [];
            return $config['modulos'] ?? [];
        }

        if (file_exists($this->legacyFilePath)) {
            $legacy = json_decode(file_get_contents($this->legacyFilePath), true) ?: [];
            $modulos = [];
            foreach ($legacy as $mod => $est) {
                $modulos[$mod] = ['estado' => $est];
            }
            return $modulos;
        }

        return [];
    }

    public function getModulosInstalados(): array {
        return $this->modulosInstalados;
    }

    public function getRutasGlobales(): array {
        return $this->rutasGlobales;
    }

    public function getMenuGlobal(): array {
        return $this->menuGlobal;
    }

    public function getInfoModulosAdmin(): array {
        $infoModulos = [];
        $modulosIntocables = ['SuperAdmin', 'Autenticacion'];

        foreach ($this->modulosInstalados as $nombre => $instancia) {
            $menu = $instancia->getMenuConfig();
            $icono = (!empty($menu) && isset($menu[0]['icono'])) ? $menu[0]['icono'] : 'ph ph-gear';

            $descripcion = method_exists($instancia, 'getDescripcion') ? $instancia->getDescripcion() : 'Módulo del sistema.';
            $dependencias = method_exists($instancia, 'getDependencias') ? $instancia->getDependencias() : [];
            $esCore = in_array($nombre, $modulosIntocables, true);

            $infoModulos[] = [
                'id' => $nombre,
                'nombre' => $nombre,
                'icono' => $icono,
                'descripcion' => $descripcion,
                'es_core' => $esCore,
                'estado' => 'online',
                'dependencias_count' => count($dependencias),
                'nombres_dependencias' => implode(', ', $dependencias)
            ];
        }

        return $infoModulos;
    }
}
