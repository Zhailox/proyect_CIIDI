<?php
// modules/Cursos/index.php

require_once CORE_PATH . 'Interfaces/ModuleContract.php';

class CursosModule implements ModuleContract {

    public function getNombre(): string {
        return 'Módulo de Formación y Cursos';
    }

    public function getRutas(): array {
        $css = ['cursos.css'];

        return [
            // 1. Catálogo principal de cursos publicados
            'cursos' => [
                'vista'            => __DIR__ . '/views/showcase_cursos.php',
                'titulo'           => 'Catálogo de Cursos — Oferta Formativa',
                'css'              => $css,
                'controlador'      => 'PromoController',
                'controlador_path' => __DIR__ . '/controllers/PromoController.php',
                'metodo'           => 'catalogo',
            ],
            // 2. Detalle de un curso individual (lecciones + inscripción)
            'detalle-curso' => [
                'vista'            => __DIR__ . '/views/detalle_curso.php',
                'titulo'           => 'Detalle del Curso',
                'css'              => $css,
                'controlador'      => 'PromoController',
                'controlador_path' => __DIR__ . '/controllers/PromoController.php',
                'metodo'           => 'detalle',
            ],
            // 3. Endpoint POST de inscripción (sin vista — redirige siempre)
            'inscribirse-curso' => [
                'vista'            => null,
                'titulo'           => 'Procesando inscripción...',
                'css'              => [],
                'controlador'      => 'PromoController',
                'controlador_path' => __DIR__ . '/controllers/PromoController.php',
                'metodo'           => 'inscribirse',
            ],
            // 4. Formulario crear / editar curso (solo Profesor y Admin)
            'form-curso' => [
                'vista'            => __DIR__ . '/views/form_curso.php',
                'titulo'           => 'Gestión de Curso',
                'css'              => $css,
                'controlador'      => 'PromoController',
                'controlador_path' => __DIR__ . '/controllers/PromoController.php',
                'metodo'           => 'formCurso',
            ],
            // 5. POST que guarda crear/editar (redirige siempre)
            'guardar-curso' => [
                'vista'            => null,
                'titulo'           => 'Guardando curso...',
                'css'              => [],
                'controlador'      => 'PromoController',
                'controlador_path' => __DIR__ . '/controllers/PromoController.php',
                'metodo'           => 'guardarCurso',
            ],
            // 6. POST que elimina un curso (redirige siempre)
            'eliminar-curso' => [
                'vista'            => null,
                'titulo'           => 'Eliminando curso...',
                'css'              => [],
                'controlador'      => 'PromoController',
                'controlador_path' => __DIR__ . '/controllers/PromoController.php',
                'metodo'           => 'eliminarCurso',
            ],
        ];
    }

    public function getMenuConfig(): array {
        return [
            [
                'tipo'        => 'parent',
                'titulo'      => 'Cursos',
                'icono'       => 'ph-fill ph-graduation-cap',
                'enlace'      => 'cursos',
                'activadores' => ['cursos', 'detalle-curso'],
                'subitems'    => [
                    ['ruta' => 'cursos', 'titulo' => 'Oferta Formativa'],
                ],
            ],
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
            'destacado'   => false,
        ];
    }

    public function getHeaderConfig(): array {
        return [];
    }
}

return new CursosModule();