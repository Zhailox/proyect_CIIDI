<?php
// modules/RepositorioPST/index.php
require_once CORE_PATH . 'Interfaces/ModuleContract.php';

class RepositorioModule implements ModuleContract {
    
    public function getNombre(): string {
        return 'Repositorio PST';
    }

    public function getRutas(): array {
        return [
            'repositorio' => [
                'vista'  => __DIR__ . '/views/inicio_repositorio.php',
                'titulo' => 'Explorar Repositorio',
                'css'    => ['RepositorioPST.css', 'inicio_repo.css']
            ],
            'agregar-documento' => [
                'vista'  => __DIR__ . '/views/admin_subida_pst.php',
                'titulo' => 'Cargar Documento',
                'css'    => ['RepositorioPST.css']
            ],
            'buscador' => [
                'vista'  => __DIR__ . '/views/buscador_unificado.php',
                'titulo' => 'Explorar Buscador',
                'css'    => ['RepositorioPST.css']
            ],
            'detalles-pst' => [
                'vista'  => __DIR__ . '/views/detalles_pst.php',
                'titulo' => 'Detalles PST',
                'css'    => ['RepositorioPST.css']
            ],
            'dashboard-prediccion' => [
                'vista'  => __DIR__ . '/views/dashboard_prediccion.php',
                'titulo' => 'Modelo de Predicción',
                'css'    => ['RepositorioPST.css']
            ]
            
        ];
    }

    // NUEVO: El plano visual del menú para este módulo
    public function getMenuConfig(): array {
        return [
            // Podemos enviar múltiples botones, pero aquí enviamos un grupo desplegable
            [
                'tipo'        => 'parent', // 'parent' si tiene submenú, 'link' si es directo
                'titulo'      => 'Repositorio',
                'icono'       => 'ph-fill ph-book-open-text',
                'privilegio_minimo' => -1,
                'enlace'      => 'repositorio', // A dónde lleva el clic principal
                'activadores' => ['repositorio', 'agregar-documento', 'buscador', 'dashboard-prediccion'], // Rutas que iluminan el padre
                'subitems'    => [
                    ['ruta' => 'repositorio', 'titulo' => 'Explorar Proyectos'],
                    ['ruta' => 'agregar-documento', 'titulo' => 'Cargar Documento'],
                    ['ruta' => 'buscador', 'titulo' => 'Buscador Unificado'],
                    ['ruta' => 'dashboard-prediccion', 'titulo' => 'Modelo de Predicción']
                ]
            ]
        ];
    }
    public function getDescripcion(): string {
        return 'Motor de búsqueda neuronal, gestión documental y analítica predictiva de los Proyectos Socio-Tecnológicos.';
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

return new RepositorioModule();