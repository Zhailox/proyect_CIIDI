<?php
require_once CORE_PATH . 'Security/Auth.php';

class ModulosController {
    
    private $archivo_estados;

    public function __construct() {
        // Ruta donde guardaremos si un módulo está encendido o apagado
        $this->archivo_estados = __DIR__ . '/../../../storage/modules.json';
    }

    public function index() {
        Auth::requierePrivilegioMinimo(3); 

        // Leemos los estados actuales
        $estados = file_exists($this->archivo_estados) ? json_decode(file_get_contents($this->archivo_estados), true) : [];

        $modulosReales = [];
        $carpetas = array_diff(scandir(MODULES_PATH), array('.', '..'));
        
        foreach ($carpetas as $carpeta) {
            $ruta_index = MODULES_PATH . $carpeta . '/index.php';
            
            if (file_exists($ruta_index)) {
                require_once $ruta_index;
                $claseModulo = $carpeta . 'Module'; 
                
                if (class_exists($claseModulo)) {
                    $instancia = new $claseModulo();
                    $esCore = in_array($carpeta, ['Autenticacion', 'SuperAdmin']);
                    
                    // Asignamos el estado real (online/offline)
                    $estado = $esCore ? 'core' : ($estados[$carpeta] ?? 'online');
                    
                    $modulosReales[] = [
                        'id' => $carpeta,
                        'nombre' => method_exists($instancia, 'getNombre') ? $instancia->getNombre() : $carpeta,
                        'descripcion' => method_exists($instancia, 'getDescripcion') ? $instancia->getDescripcion() : 'Módulo del sistema.',
                        'estado' => $estado,
                        'es_core' => $esCore,
                        'dependencias_count' => method_exists($instancia, 'getDependencias') ? count($instancia->getDependencias()) : 0,
                        'icono' => $esCore ? 'ph-bold ph-shield-check' : 'ph-bold ph-plugs-connected'
                    ];
                }
            }
        }

        return ['modulosDelSistema' => $modulosReales];
    }

    // Nuevo método que se activa cuando le das clic a un interruptor
    public function alternarEstado() {
        Auth::requierePrivilegioMinimo(3);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $carpeta = $_POST['modulo_id'] ?? '';
            $nuevoEstado = $_POST['nuevo_estado'] ?? 'online';

            // Evitamos que apaguen el núcleo
            if (in_array($carpeta, ['Autenticacion', 'SuperAdmin'])) {
                header("Location: gestor-modulos");
                exit;
            }

            // Actualizamos el JSON
            $estados = file_exists($this->archivo_estados) ? json_decode(file_get_contents($this->archivo_estados), true) : [];
            $estados[$carpeta] = $nuevoEstado;
            file_put_contents($this->archivo_estados, json_encode($estados, JSON_PRETTY_PRINT));

            header("Location: gestor-modulos");
            exit;
        }
    }
}