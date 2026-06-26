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
                'activadores' => ['empresas-inicio', 'banco-propuestas'], 
                'subitems'    => [
                    ['ruta' => 'empresas-inicio', 'titulo' => 'Conócenos'],
                    ['ruta' => 'banco-propuestas', 'titulo' => 'Banco de Propuestas']
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
        return [
            'tipo'       => 'custom_view',
            // Widget de notificaciones de solicitudes empresariales (banco de propuestas PST)
            'ruta_vista' => __DIR__ . '/views/btn_notificaciones.php',
            'css'        => 'notificaciones.css',
            'orden'      => 80
        ];
    }
}

return new VinculacionEmpresarialModule();