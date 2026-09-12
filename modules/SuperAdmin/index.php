<?php
// modules/SuperAdmin/index.php

// Requerimos la interfaz base
require_once CORE_PATH . 'Interfaces/ModuleContract.php';

// Clase para el módulo de Super Administración
class SuperAdminModule implements ModuleContract {
    
    public function getNombre(): string {
        return 'Módulo de Super Administración';
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
            'guardar-matriz-rbac' => [
                'controlador_path' => __DIR__ . '/controllers/GestorUsuariosController.php',
                'controlador'      => 'GestorUsuariosController',
                'metodo'           => 'guardarMatrizRBAC'
            ],
            'actualizar-rol' => [
                'controlador_path' => __DIR__ . '/controllers/GestorUsuariosController.php',
                'controlador'      => 'GestorUsuariosController',
                'metodo'           => 'actualizarRol'
            ],
            'revocar-sesion' => [
                'controlador_path' => __DIR__ . '/controllers/GestorUsuariosController.php',
                'controlador'      => 'GestorUsuariosController',
                'metodo'           => 'revocarSesion'
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
            'crear-rol' => [
                'controlador_path' => __DIR__ . '/controllers/GestorUsuariosController.php',
                'controlador'      => 'GestorUsuariosController',
                'metodo'           => 'crearRolAction'
            ],
            // Endpoint para alternar el estado (Suspender/Restaurar)
            'alternar-estado-usuario' => [
                'controlador_path' => __DIR__ . '/controllers/GestorUsuariosController.php',
                'controlador'      => 'GestorUsuariosController',
                'metodo'           => 'alternarEstado'
            ],
            'crear-usuario' => [
                'controlador_path' => __DIR__ . '/controllers/GestorUsuariosController.php',
                'controlador'      => 'GestorUsuariosController',
                'metodo'           => 'crearUsuarioAction'
            ],
            'resetear-clave-usuario' => [
                'controlador_path' => __DIR__ . '/controllers/GestorUsuariosController.php',
                'controlador'      => 'GestorUsuariosController',
                'metodo'           => 'resetClaveRapido'
            ],
            'sudoadmin' => [
                'controlador_path' => __DIR__ . '/controllers/AdminController.php',
                'controlador'      => 'AdminController',
                'metodo'           => 'mostrarPanelAdministrativo',
                'vista'            => __DIR__ . '/views/dashboard_admin.php', 
                'titulo'           => 'Panel de Control - SuperAdmin',
                'css'              => ['SuperAdmin.css'],
                'js'               => ['chart.min.js']
            ],
            'testear-core' => [
                'controlador_path' => __DIR__ . '/controllers/AdminController.php',
                'controlador'      => 'AdminController',
                'metodo'           => 'testearCore'
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
            'alternar-estado-ruta' => [
                'controlador_path' => __DIR__ . '/controllers/ModulosController.php',
                'controlador'      => 'ModulosController',
                'metodo'           => 'alternarEstadoRuta'
            ],
            'detalle-modulo' => [
                'controlador_path' => __DIR__ . '/controllers/ModulosController.php',
                'controlador'      => 'ModulosController',
                'metodo'           => 'detalleModulo',
                'vista'            => __DIR__ . '/views/detalle_modulo.php', 
                'titulo'           => 'Gestión Individual de Módulo - SuperAdmin',
                'css'              => ['SuperAdmin.css']
            ],
            'guardar-config-modulo' => [
                'controlador_path' => __DIR__ . '/controllers/ModulosController.php',
                'controlador'      => 'ModulosController',
                'metodo'           => 'guardarConfiguracionEspecifica'
            ],
            'testear-ruta' => [
                'controlador_path' => __DIR__ . '/controllers/ModulosController.php',
                'controlador'      => 'ModulosController',
                'metodo'           => 'testearRuta'
            ],
            'purgar-cache-modulo' => [
                'controlador_path' => __DIR__ . '/controllers/ModulosController.php',
                'controlador'      => 'ModulosController',
                'metodo'           => 'purgarCacheModulo'
            ],
            'exportar-config-sistema' => [
                'controlador_path' => __DIR__ . '/controllers/ModulosController.php',
                'controlador'      => 'ModulosController',
                'metodo'           => 'exportarConfiguracion'
            ],
            'importar-config-sistema' => [
                'controlador_path' => __DIR__ . '/controllers/ModulosController.php',
                'controlador'      => 'ModulosController',
                'metodo'           => 'importarConfiguracion'
            ],
            // Ruta para gestionar Mantenimiento & Respaldos BD
            'gestor-mantenimiento' => [
                'controlador_path' => __DIR__ . '/controllers/AdminController.php',
                'controlador'      => 'AdminController',
                'metodo'           => 'mostrarMantenimiento', 
                'vista'            => __DIR__ . '/views/gestor_mantenimiento.php', 
                'titulo'           => 'Mantenimiento & Respaldos BD - SuperAdmin',
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
            'exportar-logs' => [
                'controlador_path' => __DIR__ . '/controllers/LogsController.php',
                'controlador'      => 'LogsController',
                'metodo'           => 'exportarLogs'
            ],
            'limpiar-logs' => [
                'controlador_path' => __DIR__ . '/controllers/LogsController.php',
                'controlador'      => 'LogsController',
                'metodo'           => 'limpiarLogs'
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
            'descargar-backup' => [
                'controlador_path' => __DIR__ . '/controllers/AdminController.php',
                'controlador'      => 'AdminController',
                'metodo'           => 'descargarBackup'
            ],
            'eliminar-backup' => [
                'controlador_path' => __DIR__ . '/controllers/AdminController.php',
                'controlador'      => 'AdminController',
                'metodo'           => 'eliminarBackup'
            ],
            'verificar-respaldo' => [
                'controlador_path' => __DIR__ . '/controllers/AdminController.php',
                'controlador'      => 'AdminController',
                'metodo'           => 'verificarRespaldo'
            ],
            'limpiar-respaldos-antiguos' => [
                'controlador_path' => __DIR__ . '/controllers/AdminController.php',
                'controlador'      => 'AdminController',
                'metodo'           => 'ejecutarLimpiezaRespaldos'
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
            'crear-nivel-privilegio' => [
                'controlador_path' => __DIR__ . '/controllers/GestorUsuariosController.php',
                'controlador'      => 'GestorUsuariosController',
                'metodo'           => 'crearNivelPrivilegioAction'
            ],
            'eliminar-rol' => [
                'controlador_path' => __DIR__ . '/controllers/GestorUsuariosController.php',
                'controlador'      => 'GestorUsuariosController',
                'metodo'           => 'eliminarRolAction'
            ],
            'eliminar-nivel-privilegio' => [
                'controlador_path' => __DIR__ . '/controllers/GestorUsuariosController.php',
                'controlador'      => 'GestorUsuariosController',
                'metodo'           => 'eliminarNivelPrivilegioAction'
            ],
            // Rutas para Tareas Programadas / System Scheduler
            'gestor-scheduler' => [
                'controlador_path' => __DIR__ . '/controllers/SchedulerController.php',
                'controlador'      => 'SchedulerController',
                'metodo'           => 'index',
                'vista'            => __DIR__ . '/views/gestor_scheduler.php',
                'titulo'           => 'Tareas Programadas & Cron - SuperAdmin',
                'css'              => ['SuperAdmin.css']
            ],
            'alternar-estado-tarea' => [
                'controlador_path' => __DIR__ . '/controllers/SchedulerController.php',
                'controlador'      => 'SchedulerController',
                'metodo'           => 'alternarEstado'
            ],
            'ejecutar-tarea-manual' => [
                'controlador_path' => __DIR__ . '/controllers/SchedulerController.php',
                'controlador'      => 'SchedulerController',
                'metodo'           => 'ejecutarManual'
            ],
            'guardar-tarea-programada' => [
                'controlador_path' => __DIR__ . '/controllers/SchedulerController.php',
                'controlador'      => 'SchedulerController',
                'metodo'           => 'guardarTarea'
            ],
            'eliminar-tarea-programada' => [
                'controlador_path' => __DIR__ . '/controllers/SchedulerController.php',
                'controlador'      => 'SchedulerController',
                'metodo'           => 'eliminarTarea'
            ],
            // Rutas para WAF & Monitor de Seguridad
            'visor-seguridad' => [
                'controlador_path' => __DIR__ . '/controllers/SecurityMonitorController.php',
                'controlador'      => 'SecurityMonitorController',
                'metodo'           => 'index',
                'vista'            => __DIR__ . '/views/visor_seguridad.php',
                'titulo'           => 'Monitor WAF & Seguridad - SuperAdmin',
                'css'              => ['SuperAdmin.css']
            ],
            'desbloquear-ip' => [
                'controlador_path' => __DIR__ . '/controllers/SecurityMonitorController.php',
                'controlador'      => 'SecurityMonitorController',
                'metodo'           => 'desbloquearIP'
            ],
            'bloquear-ip-lista-negra' => [
                'controlador_path' => __DIR__ . '/controllers/SecurityMonitorController.php',
                'controlador'      => 'SecurityMonitorController',
                'metodo'           => 'bloquearListaNegra'
            ],
            'agregar-lista-blanca' => [
                'controlador_path' => __DIR__ . '/controllers/SecurityMonitorController.php',
                'controlador'      => 'SecurityMonitorController',
                'metodo'           => 'agregarListaBlanca'
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
                // Rutas que mantienen iluminado y desplegado el panel administrativo en el Sidebar
                'activadores' => ['sudoadmin', 'gestor-modulos', 'detalle-modulo', 'gestor-mantenimiento', 'visor-logs', 'gestor-usuarios', 'gestor-scheduler', 'visor-seguridad'], 
                'subitems'    => [
                    ['ruta' => 'sudoadmin', 'titulo' => 'Panel de Control'],
                    ['ruta' => 'gestor-usuarios', 'titulo' => 'Gestión de Usuarios'],
                    ['ruta' => 'gestor-modulos', 'titulo' => 'Gestor de Módulos'],
                    ['ruta' => 'gestor-mantenimiento', 'titulo' => 'Mantenimiento & BD'],
                    ['ruta' => 'gestor-scheduler', 'titulo' => 'Tareas Programadas'],
                    ['ruta' => 'visor-seguridad', 'titulo' => 'Monitor WAF & IPs'],
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