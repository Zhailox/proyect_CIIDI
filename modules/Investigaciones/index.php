<?php
// modules/Investigaciones/index.php

require_once CORE_PATH . 'Interfaces/ModuleContract.php';

class InvestigacionesModule implements ModuleContract {

    public function getNombre(): string {
        return 'Módulo de Investigaciones';
    }

    public function getRutas(): array {
        return [

            // ── PÚBLICO ───────────────────────────────────────────────
            'investigaciones' => [
                'controlador_path' => __DIR__ . '/controllers/InvestigacionController.php',
                'controlador'      => 'InvestigacionController',
                'metodo'           => 'mostrarCartelera',
                'vista'            => __DIR__ . '/views/showcase_investigaciones.php',
                'titulo'           => 'Cartelera — Investigaciones I+D',
                'css'              => ['Investigaciones.css'],
            ],
            'postulaciones-investigacion' => [
                'controlador_path' => __DIR__ . '/controllers/InvestigacionController.php',
                'controlador'      => 'InvestigacionController',
                'metodo'           => 'mostrarPanelPostulaciones',
                'vista'            => __DIR__ . '/views/panel_postulaciones.php',
                'titulo'           => '¿Pueden tus ideas cambiar al mundo?',
                'css'              => ['Investigaciones.css'],
            ],
            'investigadores' => [
                'controlador_path' => __DIR__ . '/controllers/InvestigacionController.php',
                'controlador'      => 'InvestigacionController',
                'metodo'           => 'mostrarInvestigadores',
                'vista'            => __DIR__ . '/views/ficha_investigador.php',
                'titulo'           => 'Directorio de Investigadores',
                'css'              => ['Investigaciones.css'],
            ],

            // ── ACCIONES AUTENTICADAS ─────────────────────────────────
            'postulaciones-procesar' => [
                'controlador_path' => __DIR__ . '/controllers/InvestigacionController.php',
                'controlador'      => 'InvestigacionController',
                'metodo'           => 'procesarPostulacion',
            ],

            // ── PROFESOR (nivel ≥ 1) ──────────────────────────────────
            'mis-investigaciones' => [
                'controlador_path' => __DIR__ . '/controllers/InvestigacionController.php',
                'controlador'      => 'InvestigacionController',
                'metodo'           => 'mostrarMisInvestigaciones',
                'vista'            => __DIR__ . '/views/mis_investigaciones.php',
                'titulo'           => 'Mis Investigaciones',
                'css'              => ['Investigaciones.css'],
            ],
            'crear-investigacion' => [
                'controlador_path' => __DIR__ . '/controllers/InvestigacionController.php',
                'controlador'      => 'InvestigacionController',
                'metodo'           => 'mostrarFormCrear',
                'vista'            => __DIR__ . '/views/form_investigacion.php',
                'titulo'           => 'Nueva Investigación',
                'css'              => ['Investigaciones.css'],
            ],
            'guardar-investigacion' => [
                'controlador_path' => __DIR__ . '/controllers/InvestigacionController.php',
                'controlador'      => 'InvestigacionController',
                'metodo'           => 'guardarInvestigacion',
            ],
            'editar-investigacion' => [
                'controlador_path' => __DIR__ . '/controllers/InvestigacionController.php',
                'controlador'      => 'InvestigacionController',
                'metodo'           => 'mostrarFormEditar',
                'vista'            => __DIR__ . '/views/form_investigacion.php',
                'titulo'           => 'Editar Investigación',
                'css'              => ['Investigaciones.css'],
            ],
            'actualizar-investigacion' => [
                'controlador_path' => __DIR__ . '/controllers/InvestigacionController.php',
                'controlador'      => 'InvestigacionController',
                'metodo'           => 'actualizarInvestigacion',
            ],
            'eliminar-investigacion' => [
                'controlador_path' => __DIR__ . '/controllers/InvestigacionController.php',
                'controlador'      => 'InvestigacionController',
                'metodo'           => 'eliminarInvestigacion',
            ],
            'mis-postulantes' => [
                'controlador_path' => __DIR__ . '/controllers/InvestigacionController.php',
                'controlador'      => 'InvestigacionController',
                'metodo'           => 'mostrarMisPostulantes',
                'vista'            => __DIR__ . '/views/postulantes_mis_proyectos.php',
                'titulo'           => 'Postulantes a Mis Proyectos',
                'css'              => ['Investigaciones.css'],
            ],
            'responder-postulacion' => [
                'controlador_path' => __DIR__ . '/controllers/InvestigacionController.php',
                'controlador'      => 'InvestigacionController',
                'metodo'           => 'responderPostulacion',
            ],

            // ── COMITÉ / ADMIN (nivel ≥ 2) ────────────────────────────
            'panel-investigaciones-admin' => [
                'controlador_path' => __DIR__ . '/controllers/InvestigacionController.php',
                'controlador'      => 'InvestigacionController',
                'metodo'           => 'mostrarPanelAdmin',
                'vista'            => __DIR__ . '/views/panel_admin_investigaciones.php',
                'titulo'           => 'Panel Administrativo — Investigaciones I+D',
                'css'              => ['Investigaciones.css'],
            ],
            'cambiar-estado-investigacion' => [
                'controlador_path' => __DIR__ . '/controllers/InvestigacionController.php',
                'controlador'      => 'InvestigacionController',
                'metodo'           => 'cambiarEstado',
            ],
        ];
    }

    public function getMenuConfig(): array {
        $nivelAdmin = SystemConfigService::get('accesos_modulos.cursos.admin', 0);
        $nivelPublico = SystemConfigService::get('accesos_modulos.cursos.publico', 10);
        return [
            [
                'tipo'        => 'parent',
                'titulo'      => 'Investigaciones',
                'icono'       => 'ph-fill ph-flask',
                'enlace'      => 'investigaciones',
                'privilegio_minimo' => $nivelPublico,
                'activadores' => [
                    'investigaciones', 'postulaciones-investigacion', 'investigadores',
                    'mis-investigaciones', 'crear-investigacion', 'editar-investigacion',
                    'mis-postulantes', 'panel-investigaciones-admin',
                ],
                'subitems' => [
                    ['ruta' => 'investigaciones',             'titulo' => 'Cartelera I+D',  'privilegio_minimo' => $nivelPublico],
                    ['ruta' => 'investigadores',              'titulo' => 'Investigadores',  'privilegio_minimo' => $nivelPublico],
                    ['ruta' => 'mis-investigaciones',         'titulo' => 'Mis Investigaciones',  'privilegio_minimo' => $nivelAdmin],
                    ['ruta' => 'mis-postulantes',             'titulo' => 'Mis Postulantes',  'privilegio_minimo' => $nivelAdmin],
                    ['ruta' => 'panel-investigaciones-admin', 'titulo' => 'Panel Admin I+D',  'privilegio_minimo' => $nivelAdmin],
                ],
            ],
        ];
    }

    public function getDescripcion(): string {
        return 'Gestión de investigaciones y desarrollo universitario con sistema de roles: cartelera, postulaciones, panel de profesor y panel administrativo.';
    }
    public function getDependencias(): array {
        return ['Autenticacion'];
    }
    public function getHomeConfig(): array {
        return [
            'icono'       => 'ph-fill ph-flask',
            'titulo'      => 'I+D Universitario',
            'descripcion' => 'Proyectos de investigación activos, convocatorias abiertas y perfiles de investigadores del PNF de Informática.',
            'enlace'      => 'investigaciones',
            'texto_boton' => 'VER INVESTIGACIONES',
            'destacado'   => false
        ];
    }
    public function getHeaderConfig(): array {
        return [];
    }
}

return new InvestigacionesModule();