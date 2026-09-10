<?php
require_once CORE_PATH . 'Security/Auth.php';

class ModulosController {
    
    private $archivo_config;
    private $archivo_legacy;

    public function __construct() {
        $this->archivo_config = __DIR__ . '/../../../storage/config_system.json';
        $this->archivo_legacy = __DIR__ . '/../../../storage/modules.json';
    }

    private function obtenerConfiguracion(): array {
        if (file_exists($this->archivo_config)) {
            $config = json_decode(file_get_contents($this->archivo_config), true);
            if (is_array($config)) return $config;
        }

        // Migración transparente si solo existe modules.json
        $config = ['modulos' => [], 'rutas' => []];
        if (file_exists($this->archivo_legacy)) {
            $legacy = json_decode(file_get_contents($this->archivo_legacy), true);
            if (is_array($legacy)) {
                foreach ($legacy as $mod => $est) {
                    $config['modulos'][$mod] = ['estado' => $est];
                }
            }
        }
        return $config;
    }

    private function guardarConfiguracion(array $config): bool {
        $directorio = dirname($this->archivo_config);
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }
        return (bool) file_put_contents($this->archivo_config, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function index() {
        Auth::requierePrivilegioMinimo(3); 

        $config = $this->obtenerConfiguracion();
        $estadosModulos = $config['modulos'] ?? [];
        $estadosRutas = $config['rutas'] ?? [];

        $modulosReales = [];
        $carpetas = array_diff(scandir(MODULES_PATH), array('.', '..'));
        
        foreach ($carpetas as $carpeta) {
            $ruta_index = MODULES_PATH . $carpeta . '/index.php';
            
            if (file_exists($ruta_index)) {
                // Importamos usando require_once para no redelinear la clase si el Kernel ya la cargó
                $modulo = require_once $ruta_index;
                
                // Si require_once retorna true porque ya fue cargado previamente, obtenemos la instancia por el nombre de la clase
                if ($modulo === true) {
                    $claseModulo = $carpeta . 'Module';
                    if (class_exists($claseModulo)) {
                        $modulo = new $claseModulo();
                    }
                }
                
                if ($modulo instanceof ModuleContract) {
                    $esCore = in_array($carpeta, ['Autenticacion', 'SuperAdmin']);
                    
                    $modConfig = $estadosModulos[$carpeta] ?? ['estado' => 'online'];
                    $estadoModulo = $esCore ? 'core' : ($modConfig['estado'] ?? 'online');
                    
                    $rutasModulo = $modulo->getRutas();
                    $rutasDetalle = [];

                    foreach ($rutasModulo as $claveRuta => $infoRuta) {
                        $configRuta = $estadosRutas[$claveRuta] ?? ['estado' => 'online', 'mensaje' => ''];
                        $rutasDetalle[] = [
                            'clave'       => $claveRuta,
                            'titulo'      => $infoRuta['titulo'] ?? $claveRuta,
                            'controlador' => $infoRuta['controlador'] ?? 'Directo',
                            'metodo'      => $infoRuta['metodo'] ?? 'N/A',
                            'estado'      => $configRuta['estado'] ?? 'online', // online | solo_lectura | offline
                            'mensaje'     => $configRuta['mensaje'] ?? ''
                        ];
                    }

                    $modulosReales[] = [
                        'id'                 => $carpeta,
                        'nombre'             => method_exists($modulo, 'getNombre') ? $modulo->getNombre() : $carpeta,
                        'descripcion'        => method_exists($modulo, 'getDescripcion') ? $modulo->getDescripcion() : 'Módulo del sistema.',
                        'estado'             => $estadoModulo,
                        'es_core'            => $esCore,
                        'dependencias_count' => method_exists($modulo, 'getDependencias') ? count($modulo->getDependencias()) : 0,
                        'nombres_dependencias' => method_exists($modulo, 'getDependencias') ? implode(', ', $modulo->getDependencias()) : '',
                        'icono'              => $esCore ? 'ph-bold ph-shield-check' : 'ph-bold ph-plugs-connected',
                        'rutas'              => $rutasDetalle
                    ];
                }
            }
        }

        return ['modulosDelSistema' => $modulosReales];
    }

    public function alternarEstado() {
        Auth::requierePrivilegioMinimo(3);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $carpeta = $_POST['modulo_id'] ?? '';
            $nuevoEstado = $_POST['nuevo_estado'] ?? 'online';

            if (in_array($carpeta, ['Autenticacion', 'SuperAdmin'])) {
                header("Location: gestor-modulos");
                exit;
            }

            $config = $this->obtenerConfiguracion();
            $config['modulos'][$carpeta] = ['estado' => $nuevoEstado];
            $this->guardarConfiguracion($config);

            header("Location: gestor-modulos");
            exit;
        }
    }

    public function alternarEstadoRuta() {
        Auth::requierePrivilegioMinimo(3);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $claveRuta = $_POST['ruta_clave'] ?? '';
            $nuevoEstado = $_POST['nuevo_estado'] ?? 'online'; // online | solo_lectura | offline
            $mensaje = trim($_POST['mensaje_personalizado'] ?? '');

            if (!empty($claveRuta)) {
                $config = $this->obtenerConfiguracion();
                
                if (!isset($config['rutas'][$claveRuta])) {
                    $config['rutas'][$claveRuta] = [];
                }
                
                $config['rutas'][$claveRuta]['estado'] = $nuevoEstado;
                if ($mensaje !== '') {
                    $config['rutas'][$claveRuta]['mensaje'] = $mensaje;
                }

                $this->guardarConfiguracion($config);
            }

            header("Location: gestor-modulos");
            exit;
        }
    }
}