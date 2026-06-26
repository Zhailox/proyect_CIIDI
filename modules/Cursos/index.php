<?php
// modules/Cursos/index.php

// Asegurarnos de que la interfaz esté disponible
require_once CORE_PATH . 'Interfaces/ModuleContract.php';

class CursosModule implements ModuleContract {
    
    public function getNombre(): string {
        return 'Módulo de Formación y Cursos';
    }

    public function getRutas(): array {
        return [
            'cursos' => [
                'vista'  => __DIR__ . '/views/showcase_cursos.php',
                'titulo' => 'Catálogo de Cursos - Oferta Formativa',
				'css'    => ['cursos.css']
            ]
        ];
    }

    // AQUÍ ESTÁ LA SOLUCIÓN: Cumplimos con el contrato entregando el plano del menú
    public function getMenuConfig(): array {
        return [
            [
                'tipo'        => 'parent',
                'titulo'      => 'Cursos',
                'icono'       => 'ph-fill ph-graduation-cap',
                'enlace'      => 'cursos', 
                'activadores' => ['cursos'], // Rutas que iluminan esta sección
                'subitems'    => [
                    ['ruta' => 'cursos', 'titulo' => 'Oferta Formativa']
                ]
            ]
        ];
    }
    public function getDescripcion(): string {
        return 'Cartelera de formación continua. Oferta de diplomados, talleres y promoción académica.';
    }
    public function getDependencias(): array {
        return [];
    }
    public function getHomeConfig(): array {
        return [
            'icono'       => 'ph-fill ph-graduation-cap',
            'titulo'      => 'Cartelera de Formación',
            'descripcion' => 'Oferta continua de diplomados, talleres y certificaciones para potenciar el desarrollo profesional.',
            'enlace'      => 'cursos',
            'texto_boton' => 'VER OFERTA ACADÉMICA',
            'destacado'   => false
        ];
    }
     public function getHeaderConfig(): array {
        return [];
    }
}

// Retornamos la instancia para que el Kernel la guarde
return new CursosModule();