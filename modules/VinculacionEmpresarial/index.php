<?php
// modules/VinculacionEmpresarial/index.php

require_once CORE_PATH . 'Interfaces/ModuleContract.php';

class VinculacionEmpresarialModule implements ModuleContract {
    
    public function getNombre(): string {
        return 'Módulo de Vinculación Empresarial';
    }

    public function getRutas(): array {
        // Definimos la ruta del CSS específico de este módulo.
        // Asumo que crearás una carpeta 'assets' dentro del módulo para mantenerlo encapsulado.
        $css_modulo = ['vinculacion.css'];

        return [
            // 1. Landing page para informar a las empresas y mostrar casos de éxito
            'empresas-inicio' => [
                'vista'  => __DIR__ . '/views/landing_informativa.php', 
                'titulo' => 'Vinculación Empresarial - UPTTMBI',
                'css'    => $css_modulo
            ],
            // 2. Tablero interno para que estudiantes/profesores vean y se postulen a los PST
            'banco-propuestas' => [
                'vista'  => __DIR__ . '/views/banco_propuestas.php', 
                'titulo' => 'Banco de Propuestas PST',
                'css'    => $css_modulo
            ],
            'guardar-propuesta' => [
                'vista' => __DIR__ . '/controllers/VinculacionController.php',
                'metodo' => 'guardarPropuesta',
                'oculto' => true
            ],
            'procesar-propuesta' => [
                'vista' => __DIR__ . '/controllers/VinculacionController.php',
                'metodo' => 'procesarPropuesta',
                'oculto' => true
            ],
            // Portal de Transparencia (Seguimiento de Propuestas)
            'seguimiento-empresa' => [
                'vista'  => __DIR__ . '/views/seguimiento_empresa.php', 
                'titulo' => 'Rastrea tu Propuesta - CIIDI',
                'css'    => $css_modulo
            ],
            // Cartelera de Oportunidades (Estudiantes)
            'cartelera-oportunidades' => [
                'vista'  => __DIR__ . '/views/cartelera_oportunidades.php', 
                'titulo' => 'Cartelera de Oportunidades',
                'css'    => $css_modulo
            ],
            // API AJAX para buscar propuestas
            'api-transparencia' => [
                'controlador'      => 'TransparenciaController',
                'controlador_path' => __DIR__ . '/controllers/TransparenciaController.php',
                'metodo'           => 'rastrear',
                'oculto'           => true
            ]
        ];
    }

    public function getMenuConfig(): array {
        return [
            [
                'tipo'        => 'parent',
                'titulo'      => 'Sector Productivo',
                'icono'       => 'ph-fill ph-buildings',
                'enlace'      => 'empresas-inicio',
                'activadores' => ['empresas-inicio', 'seguimiento-empresa', 'cartelera-oportunidades', 'banco-propuestas'], 
                'privilegio_minimo' => 999,
                'subitems'    => [
                    ['ruta' => 'empresas-inicio', 'titulo' => 'Conócenos', 'privilegio_minimo' => 999],
                    ['ruta' => 'seguimiento-empresa', 'titulo' => 'Seguimiento', 'privilegio_minimo' => 999],
                    ['ruta' => 'cartelera-oportunidades', 'titulo' => 'Estudiantes', 'privilegio_minimo' => 5],
                    ['ruta' => 'banco-propuestas', 'titulo' => 'Evaluación de Propuestas', 'privilegio_minimo' => 1]
                ]
            ]
        ];
    }
    public function getDescripcion(): string {
        return 'Puente con el sector productivo. Banco de problemas y recepción de solicitudes de empresas externas.';
    }
    public function getDependencias(): array {
        return [];
    }
    public function getHomeConfig(): array {
        return [
            'icono'       => 'ph-fill ph-buildings',
            'titulo'      => 'Vinculación Empresarial',
            'descripcion' => 'Conexión directa entre las necesidades tecnológicas del sector productivo y las soluciones de nuestro talento académico.',
            'enlace'      => 'empresas-inicio', 
            'texto_boton' => 'SECTOR PRODUCTIVO',
            'destacado'   => false 
        ];
    }
     public function getHeaderConfig(): array {
        return [];
    }
}

return new VinculacionEmpresarialModule();