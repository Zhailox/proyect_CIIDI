<?php
// modules/LineasInvestigacion/index.php

require_once CORE_PATH . 'Interfaces/ModuleContract.php';
require_once __DIR__ . '/../SuperAdmin/services/SystemConfigService.php';

class LineasInvestigacionModule implements ModuleContract {

    public function getNombre(): string {
        return 'Módulo de Líneas de Investigación';
    }

    public function getRutas(): array {
        return [
            // Vista pública: Explorar todas las líneas de investigación
            'lineas-investigacion' => [
                'vista'            => __DIR__ . '/views/showcase_lineas.php',
                'titulo'           => 'Líneas de Investigación - CIIDI UPTTMBI',
                'css'              => ['LineasInvestigacion.css'],
                'controlador'      => 'ShowcaseLineasController',
                'controlador_path' => __DIR__ . '/controllers/ShowcaseController.php',
                'metodo'           => 'index'
            ],
            // Analítica Predictiva IA
            'analitica' => [
                'vista'            => __DIR__ . '/views/dashboard_analitica.php',
                'titulo'           => 'Analítica Predictiva IA',
                'css'              => ['LineasInvestigacion.css'],
                'js'               => ['analitica_ui.js'],
                'controlador'      => 'AnaliticaController',
                'controlador_path' => __DIR__ . '/controllers/AnaliticaController.php',
                'metodo'           => 'index'
            ],
            // Endpoints de IA y Analítica
            'api-prediccion-tendencias' => [
                'controlador'      => 'AnaliticaController',
                'controlador_path' => __DIR__ . '/controllers/AnaliticaController.php',
                'metodo'           => 'proyectarTendencias',
                'es_api'           => true 
            ],
            // Vista pública: Detalle de una línea específica
            'detalle-linea' => [
                'vista'            => __DIR__ . '/views/detalle_linea.php',
                'titulo'           => 'Detalle de Línea de Investigación',
                'css'              => ['LineasInvestigacion.css'],
                'controlador'      => 'DetalleLineaController',
                'controlador_path' => __DIR__ . '/controllers/DetalleController.php',
                'metodo'           => 'index'
            ],
            // Panel Admin: Gestión CRUD de líneas
            'gestionar-lineas' => [
                'vista'            => __DIR__ . '/views/gestor_lineas.php',
                'titulo'           => 'Gestión de Líneas de Investigación - Admin',
                'css'              => ['LineasInvestigacion.css'],
                'controlador'      => 'GestorLineasController',
                'controlador_path' => __DIR__ . '/controllers/GestorController.php',
                'metodo'           => 'index'
            ],
            // Panel Admin: Gestión CRUD de dimensiones operativas
            'gestionar-dimensiones' => [
                'vista'            => __DIR__ . '/views/gestor_dimensiones.php',
                'titulo'           => 'Gestión de Dimensiones Operativas - Admin',
                'css'              => ['LineasInvestigacion.css'],
                'controlador'      => 'GestorLineasController',
                'controlador_path' => __DIR__ . '/controllers/GestorController.php',
                'metodo'           => 'dimensiones'
            ],
        ];
    }

    public function getMenuConfig(): array {
        $nivelAdmin = SystemConfigService::get('accesos_modulos.lineas_investigacion.admin', 0);
        $nivelPublico = SystemConfigService::get('accesos_modulos.lineas_investigacion.publico', 999);
        return [
            [
                'tipo'        => 'parent',
                'titulo'      => 'Líneas I+D',
                'icono'       => 'ph-fill ph-graph',
                'enlace'      => 'lineas-investigacion',
                'activadores' => ['lineas-investigacion', 'detalle-linea', 'gestionar-lineas', 'gestionar-dimensiones', 'analitica'],
                'privilegio_minimo' => $nivelPublico,
                'subitems'    => [
                    ['ruta' => 'lineas-investigacion',  'titulo' => 'Explorar Líneas', 'privilegio_minimo' => $nivelPublico],
                    ['ruta' => 'analitica',             'titulo' => 'Analítica IA', 'privilegio_minimo' => $nivelAdmin],
                    ['ruta' => 'gestionar-lineas',      'titulo' => 'Gestionar Líneas', 'privilegio_minimo' => $nivelAdmin],
                    ['ruta' => 'gestionar-dimensiones', 'titulo' => 'Gestionar Dimensiones', 'privilegio_minimo' => $nivelAdmin],
                ]
            ]
        ];
    }

    public function getDescripcion(): string {
        return 'Gestión y visualización de las líneas de investigación del CIIDI y sus dimensiones operativas. Conecta proyectos con ejes temáticos estratégicos.';
    }

    public function getDependencias(): array {
        return ['Autenticacion'];
    }

    public function getHomeConfig(): array {
        return [
            'icono'       => 'ph-fill ph-graph',
            'titulo'      => 'Líneas de Investigación',
            'descripcion' => 'Explora los ejes estratégicos que articulan el conocimiento del PNF en Informática. Descubre dimensiones operativas, proyectos clasificados e investigaciones ofertadas.',
            'enlace'      => 'lineas-investigacion',
            'texto_boton' => 'EXPLORAR LÍNEAS',
            'destacado'   => false
        ];
    }

    public function getHeaderConfig(): array {
        return [];
    }
}

return new LineasInvestigacionModule();
