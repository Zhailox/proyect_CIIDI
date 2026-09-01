<?php
// core/System/Kernel.php
require_once CORE_PATH . 'Interfaces/ModuleContract.php';

class Kernel {
    
    private $rutasGlobales = [];
    private $modulosInstalados = [];
    private $menuGlobal = []; // NUEVO: Aquí guardaremos el menú de todos los módulos

    public function __construct() {
        $this->cargarModulos();
    }

    private function cargarModulos() {
        $carpetas = array_diff(scandir(MODULES_PATH), array('.', '..'));
        
        // 1. Leemos el archivo de estados (Si no existe, asumimos que todos están online)
        $archivo_estados = __DIR__ . '/../../storage/modules.json';
        $estados = file_exists($archivo_estados) ? json_decode(file_get_contents($archivo_estados), true) : [];
        
        foreach ($carpetas as $carpeta) {
            // 2. Verificamos el estado del módulo (Autenticacion y SuperAdmin nunca se apagan)
            $estadoActual = $estados[$carpeta] ?? 'online';
            $esCore = in_array($carpeta, ['Autenticacion', 'SuperAdmin']);
            
            if ($estadoActual === 'offline' && !$esCore) {
                continue; // MAGIA: El Kernel ignora la carpeta. El módulo deja de existir.
            }

            $ruta_index_modulo = MODULES_PATH . $carpeta . '/index.php';
            
            if (file_exists($ruta_index_modulo)) {
                $modulo = require_once $ruta_index_modulo;
                
                if ($modulo instanceof ModuleContract) {
                    $this->modulosInstalados[$carpeta] = $modulo;
                    $this->rutasGlobales = array_merge($this->rutasGlobales, $modulo->getRutas());
                    
                    $config_menu_modulo = $modulo->getMenuConfig();
                    if (!empty($config_menu_modulo)) {
                        $this->menuGlobal = array_merge($this->menuGlobal, $config_menu_modulo);
                    }
                }
            }
        }
    }

public function run() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $ruta = isset($_GET['ruta']) ? $_GET['ruta'] : 'inicio';
        $archivo_mantenimiento = __DIR__ . '/../../storage/maintenance.json';
        if (file_exists($archivo_mantenimiento)) {
            $dataMantenimiento = json_decode(file_get_contents($archivo_mantenimiento), true);
            
            if (isset($dataMantenimiento['activo']) && $dataMantenimiento['activo'] === true) {
                $esAdmin = isset($_SESSION['nivel_privilegio']) && $_SESSION['nivel_privilegio'] >= 3;
                $rutasPermitidas = ['login', 'procesar-login', 'cerrar-sesion'];
                
                if (!$esAdmin && !in_array($ruta, $rutasPermitidas)) {
                    
                    // Extraemos el mensaje para que la vista lo consuma
                    $mensajeCustom = !empty($dataMantenimiento['mensaje']) ? $dataMantenimiento['mensaje'] : "Estamos realizando labores de optimización. Vuelve en un momento.";
                    
                    // Cargamos la vista oficial de forma limpia y detenemos el Kernel
                    require_once CORE_VIEWS . 'mantenimiento.php';
                    exit;
                }
            }
        }
        //Aquí si no está en mantenimiento
        
        $css_modulo = []; 
        $titulo_pagina = 'CIIDI';

        // 1. NATIVA: Interceptamos la ruta de inicio para que la maneje el Core
        if ($ruta === 'inicio') {
            $vista_modulo_path = CORE_VIEWS . 'home_bienvenida.php';
            $titulo_pagina     = 'Inicio - Sistema Integral UPTTMBI';
            $layout_config     = ['header' => true, 'sidebar' => true, 'footer' => true];
            // Recolectamos el CSS de las franjas personalizadas de los módulos
            $css_modulo        = $this->getHomeCss(); 
        } 
        // 2. DINÁMICA: Buscamos en las rutas de los módulos
        elseif (array_key_exists($ruta, $this->rutasGlobales)) {
            $configRuta = $this->rutasGlobales[$ruta];
            
            $titulo_pagina = $configRuta['titulo'] ?? 'Sistema Integral';
            $layout_config = $configRuta['layout'] ?? ['header' => true, 'sidebar' => true, 'footer' => true];
            $vista_modulo_path = $configRuta['vista'] ?? null;
            
            // MAGIA MVC: Si la ruta tiene un controlador, lo ejecutamos primero
            if (isset($configRuta['controlador']) && isset($configRuta['controlador_path'])) {
                require_once $configRuta['controlador_path'];
                $claseControlador = $configRuta['controlador'];
                $metodo = $configRuta['metodo'];
                
                $instancia = new $claseControlador();
                $datosVista = $instancia->$metodo();
                
                // Si el controlador devuelve variables, las "desempaquetamos" para que la vista las use
                if (is_array($datosVista)) {
                    extract($datosVista);
                } 
                // Si el controlador no devuelve nada (ej. hizo una redirección), detenemos el renderizado
                elseif ($datosVista === false) {
                    exit;
                }
            }
            
            $css_declarados = $configRuta['css'] ?? [];
            foreach ($css_declarados as $archivo_css) {
                foreach ($this->modulosInstalados as $nombre_carpeta => $instancia) {
                    if (array_key_exists($ruta, $instancia->getRutas())) {
                        $css_modulo[] = '../modules/' . $nombre_carpeta . '/assets/css/' . $archivo_css;
                        break;
                    }
                }
            }
        }
        // 3. ERROR 404
        else {
            $vista_modulo_path = CORE_VIEWS . '404.php';
            $titulo_pagina     = '404 - Página No Encontrada';
            $layout_config     = ['header' => true, 'sidebar' => true, 'footer' => true];
        }

        $menu_dinamico = $this->menuGlobal; 

        if (file_exists(CORE_VIEWS . 'master.php')) {
            include CORE_VIEWS . 'master.php';
        } else {
            die("Error Crítico: No se encuentra master.php");
        }
    }
    public function getInfoModulosAdmin(): array {
        $infoModulos = [];
        
        $modulosIntocables = [
            'SuperAdmin', // El nombre exacto que devuelve getNombre() en su index.php
            'Autenticacion'
        ];
        
        foreach ($this->modulosInstalados as $nombre => $instancia) {
            $menu = $instancia->getMenuConfig();
            $icono = (!empty($menu) && isset($menu[0]['icono'])) ? $menu[0]['icono'] : 'ph ph-gear';
            
            $descripcion = method_exists($instancia, 'getDescripcion') ? $instancia->getDescripcion() : 'Módulo del sistema.';
            $dependencias = method_exists($instancia, 'getDependencias') ? $instancia->getDependencias() : [];
            
            // 2. Comparamos el nombre actual contra nuestra lista de intocables
            $esCore = in_array($nombre, $modulosIntocables);
            
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
    public function getTarjetasInicio(): array {
        $tarjetas = [];
        
        foreach ($this->modulosInstalados as $modulo) {
            // Verificamos por seguridad que el método exista (por si algún módulo viejo no lo tiene aún)
            if (method_exists($modulo, 'getHomeConfig')) {
                $config = $modulo->getHomeConfig();
                
                // Si el módulo envió configuración (no está vacío), lo agregamos al inicio
                if (!empty($config)) {
                    $tarjetas[] = $config;
                }
            }
        }
        
        return $tarjetas;
    }
    // Agrega este método al final de Kernel.php
    public function getControlesHeader(): array {
        $controles = [];
        
        foreach ($this->modulosInstalados as $modulo) {
            if (method_exists($modulo, 'getHeaderConfig')) {
                $config = $modulo->getHeaderConfig();
                
                if (!empty($config)) {
                    // Verificamos si el módulo devuelve un solo control o un arreglo de varios
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
        
        // MAGIA DE ORDENAMIENTO: Ordenamos el arreglo basándonos en la llave 'orden'
        usort($controles, function($a, $b) {
            // Si el elemento no tiene la llave 'orden', le damos un peso de 50 (al medio)
            $pesoA = $a['orden'] ?? 50; 
            $pesoB = $b['orden'] ?? 50;
            
            // Operador de nave espacial (<=>) compara y ordena de menor a mayor
            return $pesoA <=> $pesoB;
        });
        
        return $controles;
    }
    public function getGlobalCss(): array {
        $cssGlobales = [];
        
        // Extraemos la llave $carpeta para armar la ruta
        foreach ($this->modulosInstalados as $carpeta => $modulo) {
            if (method_exists($modulo, 'getHeaderConfig')) {
                $config = $modulo->getHeaderConfig();
                
                if (!empty($config)) {
                    // Verificamos si el módulo envió 1 solo control o una lista de varios
                    $controles = isset($config['tipo']) ? [$config] : $config;
                    
                    foreach ($controles as $control) {
                        if (isset($control['css'])) {
                            // Generamos la ruta absoluta automática hacia el CSS
                            $cssGlobales[] = '../modules/' . $carpeta . '/assets/css/' . $control['css'];
                        }
                    }
                }
            }
        }
        
        return array_unique($cssGlobales);
    }
    #Esta vaina agarra el css específicamente para la pantalla de inicio
    public function getHomeCss(): array {
        $cssHome = [];
        foreach ($this->modulosInstalados as $carpeta => $modulo) {
            if (method_exists($modulo, 'getHomeConfig')) {
                $config = $modulo->getHomeConfig();
                // Si el módulo declara una vista custom para el inicio y tiene CSS propio
                if (!empty($config) && isset($config['css'])) {
                    $cssHome[] = '../modules/' . $carpeta . '/assets/css/' . $config['css'];
                }
            }
        }
        return array_unique($cssHome);
    }
}