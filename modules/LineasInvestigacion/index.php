<?php
// modules/LineasInvestigacion/index.php
require_once CORE_PATH . 'Interfaces/ModuleContract.php';
require_once CORE_PATH . 'Auth.php';

class LineasInvestigacionModule implements ModuleContract {
    public function inicializar() {
        // Nada que inicializar por defecto
    }

    public function getNombre(): string {
        return "Líneas de Investigación";
    }

    public function enrutar(string $rutaBase) {
        $rutaCompleta = $_GET['ruta'] ?? 'lineas-investigacion';
        $partes = explode('/', $rutaCompleta);
        $accion = $partes[0];

        switch ($accion) {
            case 'lineas-investigacion':
                require_once __DIR__ . '/controllers/ShowcaseController.php';
                $controller = new ShowcaseController();
                $controller->index();
                break;
                
            case 'detalle-linea':
                require_once __DIR__ . '/controllers/ShowcaseController.php';
                $controller = new ShowcaseController();
                $controller->detalle();
                break;

            case 'gestionar-lineas':
                Auth::requierePrivilegioMinimo(2);
                require_once __DIR__ . '/controllers/GestorController.php';
                $controller = new GestorController();
                if (isset($_GET['action'])) {
                    $action = $_GET['action'];
                    if ($action === 'create') $controller->crear();
                    elseif ($action === 'edit') $controller->editar();
                    elseif ($action === 'delete') $controller->eliminar();
                } else {
                    $controller->index();
                }
                break;

            case 'gestionar-dimensiones':
                Auth::requierePrivilegioMinimo(2);
                require_once __DIR__ . '/controllers/GestorController.php';
                $controller = new GestorController();
                if (isset($_GET['action'])) {
                    $action = $_GET['action'];
                    if ($action === 'create') $controller->dimensiones_crear();
                    elseif ($action === 'edit') $controller->dimensiones_editar();
                    elseif ($action === 'delete') $controller->dimensiones_eliminar();
                }
                break;

            case 'analitica':
                Auth::requierePrivilegioMinimo(2);
                require_once __DIR__ . '/controllers/AnaliticaController.php';
                $controller = new AnaliticaController();
                $controller->index();
                break;
                
            default:
                require_once __DIR__ . '/../../public/404.php';
                break;
        }
    }

    public function getMenuConfig(): array {
        $nivelPublico = -1;
        $nivelAdmin = 2; // Comité

        return [
            [
                'modulo'      => 'Líneas de Investigación',
                'icono'       => 'ph-fill ph-graph',
                'enlace'      => 'lineas-investigacion',
                'activadores' => ['lineas-investigacion', 'detalle-linea', 'gestionar-lineas', 'gestionar-dimensiones', 'analitica'],
                'privilegio_minimo' => $nivelPublico,
                'subitems'    => [
                    ['ruta' => 'lineas-investigacion',  'titulo' => 'Explorar Líneas', 'privilegio_minimo' => $nivelPublico],
                    ['ruta' => 'analitica',             'titulo' => 'Analítica IA', 'privilegio_minimo' => $nivelAdmin],
                    ['ruta' => 'gestionar-lineas',      'titulo' => 'Líneas y Dimensiones', 'privilegio_minimo' => $nivelAdmin],
                ]
            ]
        ];
    }
}
?>
