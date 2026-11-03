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

    // Metadatos para guardianes de rutas y macro módulos
    private array $modulosEscaneados = [];   // carpeta => instancia (online u offline)
    private array $rutaModulo = [];          // ruta => carpeta dueña (solo módulos activos)
    private array $grupoModulo = [];         // carpeta => id de macro módulo
    private array $menuModulo = [];          // carpeta => primer ítem de menú

    private static ?ModuleLoader $actual = null;

    public function __construct(?string $modulesPath = null, ?string $configFilePath = null) {
        $this->modulesPath = $modulesPath ?? MODULES_PATH;
        $this->configFilePath = $configFilePath ?? (__DIR__ . '/../../storage/config_system.json');
        $this->legacyFilePath = __DIR__ . '/../../storage/modules.json';
        self::$actual = $this;
    }

    /** Última instancia creada (acceso para servicios que no reciben el loader por inyección). */
    public static function actual(): ?ModuleLoader {
        return self::$actual;
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
                    $this->modulosEscaneados[$carpeta] = $modulo;

                    // Solo registrar como activo e instalar rutas si el módulo está online o es Core
                    if ($estadoActual !== 'offline' || $esCore) {
                        $this->modulosInstalados[$carpeta] = $modulo;
                        $rutasModulo = $modulo->getRutas();
                        $this->rutasGlobales = array_merge($this->rutasGlobales, $rutasModulo);
                        foreach (array_keys($rutasModulo) as $nombreRuta) {
                            $this->rutaModulo[$nombreRuta] = $carpeta;
                        }
                    }

                    // Siempre compilar el menú global etiquetado para soporte de reactivación dinámica en tiempo real
                    $configMenuModulo = $modulo->getMenuConfig();
                    if (!empty($configMenuModulo)) {
                        $prioridadDefecto = [
                            'RepositorioPST'         => 10,
                            'Articulos'              => 20,
                            'Investigaciones'        => 30,
                            'LineasInvestigacion'    => 40,
                            'Cursos'                 => 50,
                            'VinculacionEmpresarial' => 60,
                            'SuperAdmin'             => 900,
                            'Autenticacion'          => 990
                        ];

                        foreach ($configMenuModulo as &$itemMenu) {
                            $itemMenu['modulo_origen'] = $carpeta;
                            $itemMenu['modulo_estado'] = $esCore ? 'online' : $estadoActual;
                            if (!isset($itemMenu['orden'])) {
                                $itemMenu['orden'] = $prioridadDefecto[$carpeta] ?? 100;
                            }
                            if (!empty($itemMenu['grupo']) && !isset($this->grupoModulo[$carpeta])) {
                                $this->grupoModulo[$carpeta] = $itemMenu['grupo'];
                            }
                        }
                        unset($itemMenu);
                        $this->menuModulo[$carpeta] = $configMenuModulo[0];
                        $this->menuGlobal = array_merge($this->menuGlobal, $configMenuModulo);
                    }
                }
            }
        }
    }

    /** Carpeta del módulo activo que declara la ruta, o null. */
    public function getModuloDeRuta(string $ruta): ?string {
        return $this->rutaModulo[$ruta] ?? null;
    }

    /** Id del macro módulo al que pertenece un módulo, o null. */
    public function getGrupoDeModulo(string $carpeta): ?string {
        return $this->grupoModulo[$carpeta] ?? null;
    }

    /**
     * Módulos (online y offline) que forman parte de un macro módulo, ordenados como en el sidebar.
     * @return array carpeta => ['titulo', 'icono', 'estado', 'orden', 'rutas']
     */
    public function getMapaGrupo(string $idGrupo): array {
        $estados = $this->obtenerEstadosModulos();
        $mapa = [];
        foreach ($this->grupoModulo as $carpeta => $grupo) {
            if ($grupo !== $idGrupo || !isset($this->modulosEscaneados[$carpeta])) {
                continue;
            }
            $menu = $this->menuModulo[$carpeta] ?? [];
            $estado = $estados[$carpeta] ?? ['estado' => 'online'];
            $mapa[$carpeta] = [
                'titulo' => $menu['titulo_en_grupo'] ?? ($menu['titulo'] ?? $carpeta),
                'icono'  => $menu['icono'] ?? 'ph ph-app-window',
                'estado' => is_array($estado) ? ($estado['estado'] ?? 'online') : $estado,
                'orden'  => $menu['orden_en_grupo'] ?? ($menu['orden'] ?? 100),
                'rutas'  => $this->modulosEscaneados[$carpeta]->getRutas(),
            ];
        }
        uasort($mapa, fn($a, $b) => $a['orden'] <=> $b['orden']);
        return $mapa;
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

    /**
     * Definición de los Macro Módulos del sidebar.
     * Un módulo se anida dentro de un macro declarando en su getMenuConfig() la clave
     * 'grupo' => '<id-del-macro>'. Opcionalmente puede declarar:
     *   - 'titulo_en_grupo' => Título a mostrar cuando está anidado (por defecto 'titulo').
     *   - 'orden_en_grupo'  => Orden dentro del macro (por defecto 'orden').
     * Si el 'grupo' declarado no existe aquí, el módulo se muestra como entrada normal.
     */
    public static function getGruposMenu(): array {
        return [
            'lineas-investigacion' => [
                'titulo' => 'Líneas de Investigación',
                'icono'  => 'ph-fill ph-atom',
                'orden'  => 30,
            ],
        ];
    }

    public function getMenuGlobal(): array {
        $ordenar = function(string $clave) {
            return function($a, $b) use ($clave) {
                $pesoA = $a[$clave] ?? ($a['orden'] ?? 100);
                $pesoB = $b[$clave] ?? ($b['orden'] ?? 100);
                return $pesoA <=> $pesoB;
            };
        };

        $grupos = self::getGruposMenu();
        $menu = [];
        $hijosPorGrupo = [];

        foreach ($this->menuGlobal as $item) {
            $idGrupo = $item['grupo'] ?? null;
            if ($idGrupo !== null && isset($grupos[$idGrupo])) {
                $hijosPorGrupo[$idGrupo][] = $item;
            } else {
                $menu[] = $item;
            }
        }

        foreach ($hijosPorGrupo as $idGrupo => $hijos) {
            usort($hijos, $ordenar('orden_en_grupo'));

            // El macro se activa con cualquier ruta de sus módulos hijos
            $activadores = [];
            $algunoOnline = false;
            foreach ($hijos as $hijo) {
                $activadores = array_merge($activadores, $hijo['activadores'] ?? []);
                if (!empty($hijo['enlace'])) {
                    $activadores[] = $hijo['enlace'];
                }
                foreach ($hijo['subitems'] ?? [] as $sub) {
                    if (!empty($sub['ruta'])) {
                        $activadores[] = $sub['ruta'];
                    }
                }
                if (($hijo['modulo_estado'] ?? 'online') !== 'offline') {
                    $algunoOnline = true;
                }
            }

            $definicion = $grupos[$idGrupo];
            $menu[] = [
                'tipo'          => 'grupo',
                'id_grupo'      => $idGrupo,
                'titulo'        => $definicion['titulo'],
                'icono'         => $definicion['icono'] ?? 'ph ph-folders',
                'orden'         => $definicion['orden'] ?? 100,
                'activadores'   => array_values(array_unique($activadores)),
                'hijos'         => $hijos,
                'modulo_origen' => '',
                'modulo_estado' => $algunoOnline ? 'online' : 'offline',
            ];
        }

        usort($menu, $ordenar('orden'));
        return $menu;
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
