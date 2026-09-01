<?php
// modules/RepositorioPST/index.php
require_once CORE_PATH . 'Interfaces/ModuleContract.php';

class RepositorioPSTModule implements ModuleContract {
    
    public function getNombre(): string {
        return 'Repositorio PST';
    }

    public function getRutas(): array {
        return [
            'repositorio' => [
                'vista'  => __DIR__ . '/views/detalle_pst.php',
                'controlador' => 'DetallePSTController',
                'controlador_path' => __DIR__ . '/controllers/DetallePSTController.php',
                'metodo' => 'index',
                'titulo' => 'Explorar Repositorio',
                'css'    => ['RepositorioPST.css']
            ],
            'agregar-documento' => [
                'vista'  => __DIR__ . '/views/admin_subida_pst.php',
                'controlador' => 'DetallePSTController',
                'controlador_path' => __DIR__ . '/controllers/DetallePSTController.php',
                'metodo' => 'crear',
                'titulo' => 'Gestión Documental',
                'css'    => ['RepositorioPST.css']
            ],
            'buscador' => [
                'vista'  => __DIR__ . '/views/buscador_unificado.php',
                'controlador' => 'BusquedaGlobalController',
                'controlador_path' => __DIR__ . '/controllers/BusquedaGlobalController.php',
                'metodo' => 'index',
                'titulo' => 'Explorar Buscador',
                'css'    => ['RepositorioPST.css']
            ],
            'detalles-pst' => [
                'vista'  => __DIR__ . '/views/detalle_unico.php',
                'controlador' => 'DetallePSTController',
                'controlador_path' => __DIR__ . '/controllers/DetallePSTController.php',
                'metodo' => 'ver',
                'titulo' => 'Ficha PST',
                'css'    => ['RepositorioPST.css']
            ],
            'ver-pdf-pst' => [
                'controlador' => 'DetallePSTController',
                'controlador_path' => __DIR__ . '/controllers/DetallePSTController.php',
                'metodo' => 'verPdf'
            ],
            'configuracion-pst' => [
                'vista'  => __DIR__ . '/views/configuracion_pst.php',
                'controlador' => 'ConfiguracionController',
                'controlador_path' => __DIR__ . '/controllers/ConfiguracionController.php',
                'metodo' => 'index',
                'titulo' => 'Configuración de Repositorio',
                'css'    => ['RepositorioPST.css']
            ]
        ];
    }

    // NUEVO: El plano visual del menú para este módulo
    public function getMenuConfig(): array {
        return [
            [
                'tipo'        => 'parent',
                'titulo'      => 'Repositorio',
                'icono'       => 'ph-fill ph-book-open-text',
                'privilegio_minimo' => -1,
                'enlace'      => 'repositorio',
                'activadores' => ['repositorio', 'detalles-pst', 'agregar-documento', 'buscador', 'configuracion-pst'],
                'subitems'    => [
                    ['ruta' => 'repositorio', 'titulo' => 'Explorar Proyectos', 'privilegio_minimo' => -1],
                    ['ruta' => 'buscador', 'titulo' => 'Buscador Unificado', 'privilegio_minimo' => -1],
                    ['ruta' => 'agregar-documento', 'titulo' => 'Gestión Documental', 'privilegio_minimo' => 1],
                    ['ruta' => 'configuracion-pst', 'titulo' => 'Configuración Repositorio', 'privilegio_minimo' => 1]
                ]
            ]
        ];
    }
    public function getDescripcion(): string {
        return 'Motor de búsqueda y gestión documental de los Proyectos Socio-Tecnológicos.';
    }
    public function getDependencias(): array {
        return ['Autenticacion', 'Investigaciones']; // Requiere que el usuario exista para saber quién sube el PST
    }
    // Añadir a modules/RepositorioPST/index.php

    public function getHomeConfig(): array {
        return [
            'tipo'       => 'custom_view',
            'ruta_vista' => __DIR__ . '/views/home_seccion_repo.php',
            'css'        => 'home_repo.css' // Se inyectará en el <head> automáticamente
        ];
    }
    public function getHeaderConfig(): array {
        return [
            'tipo'       => 'search',
            'placeholder' => 'Buscador Inteligente...',
            'icono'       => 'ph ph-magnifying-glass',
            'accion'      => 'buscador',
            'orden'       => 10
        ];
    }
}

return new RepositorioPSTModule();