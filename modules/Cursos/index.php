<?php
// modules/Cursos/index.php

require_once CORE_PATH . 'Interfaces/ModuleContract.php';
require_once __DIR__ . '/../SuperAdmin/services/SystemConfigService.php';

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
                'seguridad_accion' => 'ver',
            ],

            // ── Crear curso ───────────────────────────────────────
            'cursos-crear' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'PromoController',
                'metodo'           => 'mostrarFormularioCrear',
                'vista'            => __DIR__ . '/views/form_curso.php',
                'titulo'           => 'Registrar Nuevo Curso',
                'css'              => ['cursos.css'],
                'seguridad_accion' => 'crear',
            ],
            'cursos-procesar-crear' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'PromoController',
                'metodo'           => 'procesarCrear',
                'seguridad_accion' => 'crear',
            ],

            // ── Editar curso ──────────────────────────────────────
            'cursos-editar' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'PromoController',
                'metodo'           => 'mostrarFormularioEditar',
                'vista'            => __DIR__ . '/views/form_curso.php',
                'titulo'           => 'Editar Curso',
                'css'              => ['cursos.css'],
                'seguridad_accion' => 'editar',
            ],
            'cursos-procesar-editar' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'PromoController',
                'metodo'           => 'procesarEditar',
                'seguridad_accion' => 'editar',
            ],

            // ── Eliminar curso ────────────────────────────────────
            'cursos-eliminar' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'PromoController',
                'metodo'           => 'procesarEliminar',
                'seguridad_accion' => 'eliminar',
            ],

            // ── Ver Detalle de Curso (Público) ─────────────────────
            'cursos-detalle' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'PromoController',
                'metodo'           => 'verDetalle',
                'vista'            => __DIR__ . '/views/detalle_curso.php',
                'titulo'           => 'Detalle del Curso',
                'css'              => ['cursos.css'],
                'seguridad_accion' => 'ver',
            ],

            // ── Formulario de Google (Inscripción pública oficial) ──
            'cursos-formulario-google' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'PromoController',
                'metodo'           => 'mostrarFormularioGoogle',
                'vista'            => __DIR__ . '/views/vista_formulario_google.php',
                'titulo'           => 'Inscripción vía Formulario de Google',
                'css'              => ['cursos.css'],
                'seguridad_accion' => 'ver',
            ],

            // ── Redirección de preinscripción interna a Formulario Google ──
            'cursos-preinscripcion' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'PromoController',
                'metodo'           => 'mostrarPreinscripcion',
                'seguridad_accion' => 'ver',
            ],
            'cursos-procesar-preinscripcion' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'PromoController',
                'metodo'           => 'procesarPreinscripcion',
                'seguridad_accion' => 'ver',
            ],

            // ── Gestión de Cursos (Profesores/Admin) ──────────────
            'cursos-gestion' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'PromoController',
                'metodo'           => 'mostrarGestion',
                'vista'            => __DIR__ . '/views/gestion_cursos.php',
                'titulo'           => 'Gestión de Cursos',
                'css'              => ['cursos.css'],
                'seguridad_accion' => 'gestionar',
            ],

            // ── Configuración del módulo (SuperAdmin) ─────────────
            'cursos-config' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'PromoController',
                'metodo'           => 'mostrarConfig',
                'vista'            => __DIR__ . '/views/config_cursos.php',
                'titulo'           => 'Configuración del Módulo de Cursos',
                'css'              => ['cursos.css'],
                'seguridad_accion' => 'configurar',
            ],
            'cursos-config-guardar' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'PromoController',
                'metodo'           => 'guardarConfig',
                'seguridad_accion' => 'configurar',
            ],
        ];
    }

    public function getMenuConfig(): array {
        $nivelPublico = SystemConfigService::get('accesos_modulos.cursos.publico', 999);
        $nivelAdmin   = SystemConfigService::get('accesos_modulos.cursos.admin', 1);

        return [
            [
                'tipo'        => 'parent',
                'titulo'      => 'Formación',
                'icono'       => 'ph-fill ph-graduation-cap',
                'orden'       => 50,
                'grupo'       => 'lineas-investigacion',
                'orden_en_grupo' => 20,
                'enlace'      => 'cursos',
                'privilegio_minimo' => $nivelPublico,
                'activadores' => ['cursos', 'cursos-detalle', 'cursos-formulario-google', 'cursos-gestion', 'cursos-crear', 'cursos-editar', 'cursos-config'],
                'subitems'    => [
                    ['ruta' => 'cursos',                   'titulo' => 'Oferta Formativa',       'privilegio_minimo' => $nivelPublico],
                    ['ruta' => 'cursos-formulario-google', 'titulo' => 'Inscripción Google Form', 'privilegio_minimo' => $nivelPublico],
                    ['ruta' => 'cursos-gestion',           'titulo' => 'Gestión de Cursos',      'privilegio_minimo' => $nivelAdmin],
                    ['ruta' => 'cursos-config',            'titulo' => 'Configuración',          'privilegio_minimo' => $nivelAdmin],
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
