<?php
// modules/SuperAdmin/index.php

// Requerimos la interfaz base
require_once CORE_PATH . 'Interfaces/ModuleContract.php';

// Clase única para el módulo del Dios del Sistema
class SuperAdminModule implements ModuleContract {
    
    public function getNombre(): string {
        return 'Módulo de Super Administración (Sudoadmin)';
    }

    public function getRutas(): array {
        return [
            // Ruta principal: El dashboard administrativo
            'sudoadmin' => [
                'vista'  => __DIR__ . '/views/dashboard_admin.php', 
                'titulo' => 'Panel de Control - Sudoadmin',
                'css'    => ['SuperAdmin.css']
            ],
            // Ruta para gestionar los módulos encendidos/apagados
            'gestor-modulos' => [
                'vista'  => __DIR__ . '/views/gestor_modulos.php', 
                'titulo' => 'Gestor de Módulos - Configuración',
                'css'    => ['SuperAdmin.css']
            ],
            // Ruta para ver los logs de errores y accesos
            'visor-logs' => [
                'vista'  => __DIR__ . '/views/visor_logs.php', 
                'titulo' => 'Visor de Logs - Auditoría del Sistema',
                'css'    => ['SuperAdmin.css']
            ]
        ];
    }

    public function getMenuConfig(): array {
        return [
            [
                'tipo'        => 'parent',
                'titulo'      => 'SuperAdmin',
                'icono'       => 'ph-fill ph-terminal-window',
                'privilegio_minimo' => 2,
                'enlace'      => 'sudoadmin',
                // Rutas que mantienen iluminado el panel administrativo
                'activadores' => ['sudoadmin', 'gestor-modulos', 'visor-logs'], 
                'subitems'    => [
                    ['ruta' => 'sudoadmin', 'titulo' => 'Panel de Control'],
                    ['ruta' => 'gestor-modulos', 'titulo' => 'Gestor de Módulos'],
                    ['ruta' => 'visor-logs', 'titulo' => 'Visor de Logs']
                ]
            ]
        ];
    }
    public function getDescripcion(): string {
        return 'Panel de control para la administración del sistema. Gestión de usuarios, permisos y configuración general.';
    }
    public function getDependencias(): array {
        return [];
    }
    public function getHomeConfig(): array {
        // Módulo de infraestructura. No requiere tarjeta pública.
        return [];
    }
     public function getHeaderConfig(): array {
        return [];
    }
}

// Retornamos la instancia para el Kernel
return new SuperAdminModule();