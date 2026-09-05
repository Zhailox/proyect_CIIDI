<?php
// modules/Articulos/index.php

require_once CORE_PATH . 'Interfaces/ModuleContract.php';

class ArticulosModule implements ModuleContract {
    
    public function getNombre(): string {
        return 'Módulo de Revista Digital y Artículos';
    }

public function getRutas(): array {
        return [
            'articulos' => [
                'controlador_path' => __DIR__ . '/controllers/ArticulosController.php',
                'controlador'      => 'ArticulosController',
                'metodo'           => 'index',
                'vista'            => __DIR__ . '/views/grid_revista.php', 
                'titulo'           => 'Revista Digital - Portal de Artículos Científicos',
                'css'              => ['Articulos.css']
            ],
            'leer-articulo' => [
                'controlador_path' => __DIR__ . '/controllers/ArticulosController.php',
                'controlador'      => 'ArticulosController',
                'metodo'           => 'leer',
                'vista'            => __DIR__ . '/views/leer_articulo.php', 
                'titulo'           => 'Lectura de Artículo - UPTTMBI',
                'css'              => ['Articulos.css']
            ],
            // --- NUEVAS RUTAS ADMINISTRATIVAS ---
            'gestor-articulos' => [
                'controlador_path' => __DIR__ . '/controllers/ArticulosController.php',
                'controlador'      => 'ArticulosController',
                'metodo'           => 'gestor',
                'vista'            => __DIR__ . '/views/gestor_articulos.php', 
                'titulo'           => 'Gestor de Artículos - Administración',
                'css'              => ['gestor_articulos.css']
            ],
            'nuevo-articulo' => [
                'controlador_path' => __DIR__ . '/controllers/ArticulosController.php',
                'controlador'      => 'ArticulosController',
                'metodo'           => 'nuevo',
                'vista'            => __DIR__ . '/views/nuevo_articulo.php', 
                'titulo'           => 'Nuevo Artículo - Administración',
                'css'              => ['gestor_articulos.css']
            ],
            'procesar-articulo' => [
                'controlador_path' => __DIR__ . '/controllers/ArticulosController.php',
                'controlador'      => 'ArticulosController',
                'metodo'           => 'procesar'

            ],
            'editar-articulo' => [
            'controlador_path' => __DIR__ . '/controllers/ArticulosController.php',
            'controlador'      => 'ArticulosController',
            'metodo'           => 'editar',
            'vista'            => __DIR__ . '/views/editar_articulo.php',
            'titulo'           => 'Editar Artículo - Administración',
            'css'              => ['gestor_articulos.css']
            ],
            'actualizar-articulo' => [
                'controlador_path' => __DIR__ . '/controllers/ArticulosController.php',
                'controlador'      => 'ArticulosController',
                'metodo'           => 'actualizar'
            ],
            'eliminar-articulo' => [
                'controlador_path' => __DIR__ . '/controllers/ArticulosController.php',
                'controlador'      => 'ArticulosController',
                'metodo'           => 'eliminar'
            ],
            'gestor-catalogos' => [
                'controlador_path' => __DIR__ . '/controllers/ArticulosController.php',
                'controlador' => 'ArticulosController',
                'metodo' => 'gestorCatalogos',
                'vista' => __DIR__ . '/views/gestor_catalogos.php',
                'titulo' => 'Gestor de Catálogos - Administración',
                'css' => ['gestor_articulos.css']
            ],
            'configuracion-articulos' => [
                'controlador_path' => __DIR__ . '/controllers/ConfiguracionController.php',
                'controlador'      => 'ConfiguracionController',
                'metodo'           => 'index',
                'vista'            => __DIR__ . '/views/configuracion_articulos.php',
                'titulo'           => 'Ajustes de Revista Digital',
                'css'              => ['gestor_articulos.css']
            ],
            'api-catalogos-art' => [
                'controlador_path' => __DIR__ . '/controllers/ArticulosController.php',
                'controlador'      => 'ArticulosController',
                'metodo'           => 'apiCatalogos',
            ],
            'toggle-estado-articulo' => [
                'controlador_path' => __DIR__ . '/controllers/ArticulosController.php',
                'controlador'      => 'ArticulosController',
                'metodo'           => 'toggleEstado',
            ]
            
        ];
    }

    public function getMenuConfig(): array {
        return [
            [
                'tipo'        => 'parent',
                'titulo'      => 'Artículos',
                'icono'       => 'ph-fill ph-newspaper',
                'enlace'      => 'articulos',
                'activadores' => ['articulos', 'leer-articulo', 'gestor-articulos', 'nuevo-articulo', 'procesar-articulo', 'editar-articulo', 'actualizar-articulo', 'eliminar-articulo', 'gestor-catalogos', 'configuracion-articulos'],
                'privilegio_minimo' => 0, // El menú padre lo ven todos
                'subitems'    => [
                    ['ruta' => 'articulos', 'titulo' => 'Revista Digital', 'privilegio_minimo' => 0],
                    // Este sub-ítem solo lo verán los administradores/bibliotecarios
                    ['ruta' => 'gestor-articulos', 'titulo' => 'Gestor Interno', 'privilegio_minimo' => 2],
                    ['ruta' => 'configuracion-articulos', 'titulo' => 'Ajustes de Revista', 'privilegio_minimo' => 2]
                ]
            ]
        ];
    }


    public function getDescripcion(): string {
        return 'Vitrina digital universitaria. Promoción de artículos científicos, crónicas y boletines del PNF de Informática.';
    }

    public function getDependencias(): array {
        return []; 
    }

    public function getHomeConfig(): array {
        return [
            'tipo'        => 'standard',
            'icono'       => 'ph-fill ph-newspaper',
            'titulo'      => 'Revista Científica Universitaria',
            'descripcion' => 'Explora nuestras últimas publicaciones académicas, crónicas y artículos de investigación redactados por la comunidad.',
            'enlace'      => 'articulos',
            'texto_boton' => 'EXPLORAR CATÁLOGO'
        ];
    }

    public function getHeaderConfig(): array {
        return [];
    }
}

return new ArticulosModule();