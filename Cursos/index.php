<?php
// modules/Cursos/index.php

require_once CORE_PATH . 'Interfaces/ModuleContract.php';

class CursosModule implements ModuleContract {

    public function getNombre(): string {
        return 'Módulo de Formación y Cursos';
    }

    public function getRutas(): array {
        $ctrl = __DIR__ . '/controllers/PromoController.php';
        return [
            // ── Catálogo público ──────────────────────────────────
            'cursos' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'PromoController',
                'metodo'           => 'mostrarCatalogo',
                'vista'            => __DIR__ . '/views/showcase_cursos.php',
                'titulo'           => 'Catálogo de Cursos — Oferta Formativa',
                'css'              => ['cursos.css'],
            ],

            // ── Crear curso ───────────────────────────────────────
            'cursos-crear' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'PromoController',
                'metodo'           => 'mostrarFormularioCrear',
                'vista'            => __DIR__ . '/views/form_curso.php',
                'titulo'           => 'Registrar Nuevo Curso',
                'css'              => ['cursos.css'],
            ],
            'cursos-procesar-crear' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'PromoController',
                'metodo'           => 'procesarCrear',
            ],

            // ── Editar curso ──────────────────────────────────────
            'cursos-editar' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'PromoController',
                'metodo'           => 'mostrarFormularioEditar',
                'vista'            => __DIR__ . '/views/form_curso.php',
                'titulo'           => 'Editar Curso',
                'css'              => ['cursos.css'],
            ],
            'cursos-procesar-editar' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'PromoController',
                'metodo'           => 'procesarEditar',
            ],

            // ── Eliminar curso ────────────────────────────────────
            'cursos-eliminar' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'PromoController',
                'metodo'           => 'procesarEliminar',
            ],

            // ── Ver Detalle de Curso ──────────────────────────────
            'cursos-detalle' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'PromoController',
                'metodo'           => 'verDetalle',
                'vista'            => __DIR__ . '/views/detalle_curso.php',
                'titulo'           => 'Detalle del Curso',
                'css'              => ['cursos.css'],
            ],

            // ── Gestión de Cursos (Profesores/Admin) ──────────────
            'cursos-gestion' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'PromoController',
                'metodo'           => 'mostrarGestion',
                'vista'            => __DIR__ . '/views/gestion_cursos.php',
                'titulo'           => 'Gestión de Cursos',
                'css'              => ['cursos.css'],
            ],

            // ── Configuración del módulo (SuperAdmin) ─────────────
            'cursos-config' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'PromoController',
                'metodo'           => 'mostrarConfig',
                'vista'            => __DIR__ . '/views/config_cursos.php',
                'titulo'           => 'Configuración del Módulo de Cursos',
                'css'              => ['cursos.css'],
            ],
            'cursos-config-guardar' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'PromoController',
                'metodo'           => 'guardarConfig',
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
                'activadores' => ['cursos', 'cursos-gestion', 'cursos-crear', 'cursos-editar', 'cursos-config'],
                'subitems'    => [
                    ['ruta' => 'cursos',         'titulo' => 'Oferta Formativa'],
                    ['ruta' => 'cursos-gestion', 'titulo' => 'Gestión de Cursos', 'nivel_minimo' => 1],
                    ['ruta' => 'cursos-config',  'titulo' => 'Configuración',     'nivel_minimo' => 3],
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