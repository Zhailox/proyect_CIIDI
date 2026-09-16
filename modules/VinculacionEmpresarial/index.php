<?php
// modules/VinculacionEmpresarial/index.php

require_once CORE_PATH . 'Interfaces/ModuleContract.php';
require_once __DIR__ . '/../SuperAdmin/services/SystemConfigService.php';

class VinculacionEmpresarialModule implements ModuleContract {
    
    public function getNombre(): string {
        return 'Módulo de Vinculación Empresarial';
    }

    public function getRutas(): array {
        // Definimos la ruta del CSS específico de este módulo.
        $css_modulo = ['vinculacion.css'];

        return [
            'empresas-inicio' => [
                'vista'  => __DIR__ . '/views/landing_informativa.php', 
                'titulo' => 'Vinculación Empresarial - UPTTMBI',
                'css'    => $css_modulo
            ],
            // Tablero Unificado para el Comité de Proyectos
            'gestion-proyectos' => [
                'vista'  => __DIR__ . '/views/gestion_proyectos.php', 
                'titulo' => 'Gestión de Solicitudes',
                'css'    => $css_modulo
            ],
            'banco-propuestas' => [
                'vista'  => __DIR__ . '/views/gestion_proyectos.php', 
                'titulo' => 'Gestión de Solicitudes',
                'css'    => $css_modulo
            ],
            'gestion-equipos' => [
                'vista'  => __DIR__ . '/views/gestion_proyectos.php', 
                'titulo' => 'Gestión de Solicitudes',
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
            'seguimiento-empresa' => [
                'vista'  => __DIR__ . '/views/seguimiento_empresa.php', 
                'titulo' => 'Rastrea tu Propuesta - CIIDI',
                'css'    => $css_modulo
            ],
            'cartelera-oportunidades' => [
                'vista'  => __DIR__ . '/views/cartelera_oportunidades.php', 
                'titulo' => 'Cartelera de Oportunidades',
                'css'    => $css_modulo
            ],
            'postular-oportunidad' => [
                'vista' => __DIR__ . '/controllers/VinculacionController.php',
                'metodo' => 'postularOportunidad',
                'oculto' => true
            ],
            'procesar-asignacion' => [
                'vista' => __DIR__ . '/controllers/VinculacionController.php',
                'metodo' => 'procesarAsignacion',
                'oculto' => true
            ],
            'api-transparencia' => [
                'controlador'      => 'TransparenciaController',
                'controlador_path' => __DIR__ . '/controllers/TransparenciaController.php',
                'metodo'           => 'rastrear',
                'oculto'           => true
            ]
        ];
    }

    public function getMenuConfig(): array {
        $nivelAdmin = SystemConfigService::get('accesos_modulos.vinculacion_empresarial.admin', 0);
        $nivelPublico = SystemConfigService::get('accesos_modulos.vinculacion_empresarial.publico', 999);
        $nivelLogueado = SystemConfigService::get('accesos_modulos.autenticacion.publico', 999);
        return [
            [
                'tipo'        => 'parent',
                'titulo'      => 'Sector Productivo',
                'icono'       => 'ph-fill ph-buildings',
                'enlace'      => 'empresas-inicio',
                'privilegio_minimo' =>  $nivelPublico,
                'activadores' => ['empresas-inicio', 'seguimiento-empresa', 'cartelera-oportunidades', 'gestion-proyectos', 'banco-propuestas', 'gestion-equipos'], 
                'subitems'    => [
                    ['ruta' => 'empresas-inicio', 'titulo' => 'Conócenos', 'privilegio_minimo' => $nivelPublico,],
                    ['ruta' => 'cartelera-oportunidades', 'titulo' => 'Oportunidades PST', 'privilegio_minimo' => $nivelLogueado],
                    ['ruta' => 'seguimiento-empresa', 'titulo' => 'Seguimiento', 'privilegio_minimo' => $nivelPublico,],
                    ['ruta' => 'gestion-proyectos', 'titulo' => 'Gestión de Solicitudes', 'privilegio_minimo' => $nivelAdmin]
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
