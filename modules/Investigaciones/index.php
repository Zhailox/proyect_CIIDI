<?php
// modules/Investigaciones/index.php

// Nos aseguramos de requerir el contrato del sistema
require_once CORE_PATH . 'Interfaces/ModuleContract.php';

// Nombre de clase totalmente único para este módulo de Investigación y Desarrollo
class InvestigacionesModule implements ModuleContract {
    
    public function getNombre(): string {
        return 'Módulo de Investigaciones';
    }

    public function getRutas(): array {
        return [
            // Ruta principal: Cartelera general de proyectos y el "Top 10"
            'investigaciones' => [
                'vista'  => __DIR__ . '/views/showcase_investigaciones.php', 
                'titulo' => 'Cartelera - Investigaciones Universitarias',
                'css'    => ['Investigaciones.css']
            ],
            // Ruta para la administración de las postulaciones a proyectos de investigación
            'postulaciones-investigacion' => [
                'vista'  => __DIR__ . '/views/panel_postulaciones.php', 
                'titulo' => 'Pueden tus ideas cambar al mundo',
                'css'    => ['Investigaciones.css']
            ]
        ];
    }

    public function getMenuConfig(): array {
        return [
            [
                'tipo'        => 'parent',
                'titulo'      => 'Investigaciones',
                'icono'       => 'ph-fill ph-flask',
                'enlace'      => 'investigaciones',
                // Rutas clave del módulo que mantendrán expandido e iluminado este menú padre
                'activadores' => ['investigaciones', 'postulaciones-investigacion'], 
                'subitems'    => [
                    ['ruta' => 'investigaciones', 'titulo' => 'Cartelera I+D'],
                    ['ruta' => 'postulaciones-investigacion', 'titulo' => 'Panel Postulaciones']
                ]
            ]
        ];
    }
    public function getDescripcion(): string {
        return 'Gestión de investigaciones y desarrollo universitario. Fichas de investigadores, top de proyectos y postulaciones.';
    }
    public function getDependencias(): array {
        return ['Autenticacion'];
    }
    public function getHomeConfig(): array {
        return [
            'icono'       => 'ph-fill ph-flask',
            'titulo'      => 'I+D Universitario',
            'descripcion' => 'Gestión de proyectos de investigación, postulaciones y perfiles de investigadores del PNF de Informática.',
            'enlace'      => 'investigaciones', 
            'texto_boton' => 'VER INVESTIGACIONES',
            'destacado'   => false
        ];
    }
     public function getHeaderConfig(): array {
        return [];
    }
}

// Retornamos la instancia configurada directamente al Kernel orquestador
return new InvestigacionesModule();