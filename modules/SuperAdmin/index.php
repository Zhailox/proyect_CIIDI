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
            // Nueva ruta para Gestión de Usuarios
            'gestor-usuarios' => [
                'controlador_path' => __DIR__ . '/controllers/GestorUsuariosController.php',
                'controlador'      => 'GestorUsuariosController',
                'metodo'           => 'index',
                'vista'            => __DIR__ . '/views/gestor_usuarios.php', 
                'titulo'           => 'Gestión de Usuarios - UPTTMBI',
                'css'              => ['SuperAdmin.css', 'gestor.css']
            ],
            'editar-usuario' => [
                'controlador_path' => __DIR__ . '/controllers/GestorUsuariosController.php',
                'controlador'      => 'GestorUsuariosController',
                'metodo'           => 'mostrarEdicion',
                'vista'            => __DIR__ . '/views/editar_usuario.php', 
                'titulo'           => 'Editar Usuario - Administración',
                'css'              => ['SuperAdmin.css']
            ],
            // Endpoint para procesar los datos de edición
            'procesar-edicion-usuario' => [
                'controlador_path' => __DIR__ . '/controllers/GestorUsuariosController.php',
                'controlador'      => 'GestorUsuariosController',
                'metodo'           => 'procesarEdicion'
            ],
            // Endpoint para alternar el estado (Suspender/Restaurar)
            'alternar-estado-usuario' => [
                'controlador_path' => __DIR__ . '/controllers/GestorUsuariosController.php',
                'controlador'      => 'GestorUsuariosController',
                'metodo'           => 'alternarEstado'
            ],
            'sudoadmin' => [
                'controlador_path' => __DIR__ . '/controllers/AdminController.php',
                'controlador'      => 'AdminController',
                'metodo'           => 'mostrarPanelAdministrativo',
                'vista'            => __DIR__ . '/views/dashboard_admin.php', 
                'titulo'           => 'Panel de Control - Sudoadmin',
                'css'              => ['SuperAdmin.css']
            ],
            // Ruta para gestionar los módulos encendidos/apagados
            'gestor-modulos' => [
                'controlador_path' => __DIR__ . '/controllers/ModulosController.php',
                'controlador'      => 'ModulosController',
                'metodo'           => 'index', // Este método lee el disco duro físico
                'vista'            => __DIR__ . '/views/gestor_modulos.php', 
                'titulo'           => 'Gestor de Módulos - Configuración',
                'css'              => ['SuperAdmin.css']
            ],
            'alternar-modulo' => [
                'controlador_path' => __DIR__ . '/controllers/ModulosController.php',
                'controlador'      => 'ModulosController',
                'metodo'           => 'alternarEstado',
                'vista'            => __DIR__ . '/views/gestor_modulos.php', 
                'titulo'           => 'Gestión de Módulos - Configuración',
                'css'              => ['SuperAdmin.css']
            ],
            // Ruta para ver los logs de errores y accesos
            'visor-logs' => [
                'controlador_path' => __DIR__ . '/controllers/LogsController.php',
                'controlador'      => 'LogsController',
                'metodo'           => 'index',
                'vista'            => __DIR__ . '/views/visor_logs.php', 
                'titulo'           => 'Visor de Logs - Auditoría del Sistema',
                'css'              => ['SuperAdmin.css','logs.css']
            ],
            'generar-backup' => [
                'controlador_path' => __DIR__ . '/controllers/AdminController.php',
                'controlador'      => 'AdminController',
                'metodo'           => 'generarBackup'
            ],
            'generar-backup-esquema' => [
                'controlador_path' => __DIR__ . '/controllers/AdminController.php',
                'controlador'      => 'AdminController',
                'metodo'           => 'generarBackupEsquema'
            ],
            'generar-backup-tabla' => [
                'controlador_path' => __DIR__ . '/controllers/AdminController.php',
                'controlador'      => 'AdminController',
                'metodo'           => 'generarBackupTabla'
            ],
            'alternar-mantenimiento' => [
                'controlador_path' => __DIR__ . '/controllers/AdminController.php',
                'controlador'      => 'AdminController',
                'metodo'           => 'alternarMantenimiento'
            ],
            'restaurar-backup' => [
                'controlador_path' => __DIR__ . '/controllers/AdminController.php',
                'controlador'      => 'AdminController',
                'metodo'           => 'restaurarBackup'
            ],
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
                'activadores' => ['sudoadmin', 'gestor-modulos', 'visor-logs', 'gestor-usuarios'], 
                'subitems'    => [
                    ['ruta' => 'sudoadmin', 'titulo' => 'Panel de Control'],
                    ['ruta' => 'gestor-usuarios', 'titulo' => 'Gestión de Usuarios'], // BOTÓN NUEVO
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