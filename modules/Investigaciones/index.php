<?php
// modules/Investigaciones/index.php

require_once CORE_PATH . 'Interfaces/ModuleContract.php';

class InvestigacionesModule implements ModuleContract {

    public function getNombre(): string {
        return 'Módulo de Investigaciones';
    }

    public function getRutas(): array {
        $css = ['Investigaciones.css'];

        return [
            // 1. Showcase principal de proyectos activos de I+D
            'investigaciones' => [
                'vista'             => __DIR__ . '/views/showcase_investigaciones.php',
                'titulo'            => 'Cartelera I+D — Investigaciones Universitarias',
                'css'               => $css,
                'controlador'       => 'InvestigacionesController',
                'controlador_path'  => __DIR__ . '/controllers/InvestigacionesController.php',
                'metodo'            => 'showcase',
            ],
            // 2. Panel Kanban de vacantes con postulación a proyectos
            'postulaciones-investigacion' => [
                'vista'             => __DIR__ . '/views/panel_postulaciones.php',
                'titulo'            => '¿Pueden tus ideas cambiar el mundo? — Postulaciones I+D',
                'css'               => $css,
                'controlador'       => 'InvestigacionesController',
                'controlador_path'  => __DIR__ . '/controllers/InvestigacionesController.php',
                'metodo'            => 'postulaciones',
            ],
            // 3. Directorio de investigadores del CIIDI
            'directorio-investigadores' => [
                'vista'             => __DIR__ . '/views/ficha_investigador.php',
                'titulo'            => 'Directorio de Investigadores — CIIDI UPTTMBI',
                'css'               => $css,
                'controlador'       => 'InvestigacionesController',
                'controlador_path'  => __DIR__ . '/controllers/InvestigacionesController.php',
                'metodo'            => 'directorio',
            ],
            // 4. Cartelera de anuncios, convocatorias y resultados
            'cartelera-investigacion' => [
                'vista'             => __DIR__ . '/views/cartelera.php',
                'titulo'            => 'Cartelera I+D — Convocatorias y Noticias',
                'css'               => $css,
                'controlador'       => 'InvestigacionesController',
                'controlador_path'  => __DIR__ . '/controllers/InvestigacionesController.php',
                'metodo'            => 'cartelera',
            ],
            // 5. Endpoint POST para procesar postulaciones (sin vista propia — redirige)
            'aplicar-investigacion' => [
                'vista'             => null,
                'titulo'            => 'Procesando postulación...',
                'css'               => [],
                'controlador'       => 'InvestigacionesController',
                'controlador_path'  => __DIR__ . '/controllers/InvestigacionesController.php',
                'metodo'            => 'aplicar',
            ],
        ];
    }

    public function getMenuConfig(): array {
        return [
            [
                'tipo'        => 'parent',
                'titulo'      => 'Investigaciones',
                'icono'       => 'ph-fill ph-flask',
                'enlace'      => 'investigaciones',
                'activadores' => [
                    'investigaciones',
                    'postulaciones-investigacion',
                    'directorio-investigadores',
                    'cartelera-investigacion',
                ],
                'subitems' => [
                    ['ruta' => 'investigaciones',            'titulo' => 'Cartelera I+D'],
                    ['ruta' => 'postulaciones-investigacion','titulo' => 'Panel Postulaciones'],
                    ['ruta' => 'directorio-investigadores',  'titulo' => 'Directorio'],
                    ['ruta' => 'cartelera-investigacion',    'titulo' => 'Convocatorias'],
                ],
            ],
        ];
    }

    public function getDescripcion(): string {
        return 'Gestión de investigaciones y desarrollo universitario. Fichas de investigadores, top de proyectos, vacantes y postulaciones.';
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
            'destacado'   => false,
        ];
    }

    public function getHeaderConfig(): array {
        return [];
    }
}

return new InvestigacionesModule();