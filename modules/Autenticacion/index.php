<?php
// modules/Autenticacion/index.php

// Nos aseguramos de traer el contrato
require_once CORE_PATH . 'Interfaces/ModuleContract.php';

// Nombre de clase único para este módulo
class AutenticacionModule implements ModuleContract {
    
    public function getNombre(): string {
        return 'Módulo de Acceso y Seguridad';
    }

public function getRutas(): array {
        return [
            // Pantalla de Login
            'login' => [
                'controlador_path' => __DIR__ . '/controllers/LoginController.php',
                'controlador'      => 'LoginController',
                'metodo'           => 'mostrarFormulario',
                'vista'            => __DIR__ . '/views/login.php', 
                'titulo'           => 'Iniciar Sesión - Control de Acceso',
                'layout'           => ['header' => true, 'sidebar' => true, 'footer' => true],
                'css'              => ['autenticacion.css']
            ],
            // Endpoint invisible que procesa el formulario
            'procesar-login' => [
                'controlador_path' => __DIR__ . '/controllers/LoginController.php',
                'controlador'      => 'LoginController',
                'metodo'           => 'procesar',
                'vista'            => __DIR__ . '/views/transicion.php',
                'css'              => ['transicion.css']
            ],
            // Endpoint invisible para salir
            'cerrar-sesion' => [
                'controlador_path' => __DIR__ . '/controllers/LoginController.php',
                'controlador'      => 'LoginController',
                'metodo'           => 'cerrarSesion'
            ],
            // Pantalla del Dashboard (Protegida)
            'perfil' => [
                'controlador_path' => __DIR__ . '/controllers/PerfilController.php',
                'controlador'      => 'PerfilController',
                'metodo'           => 'mostrarDashboard',
                'vista'            => __DIR__ . '/views/mi_dashboard.php', 
                'titulo'           => 'Mi Perfil - Panel de Control',
                'css'              => ['autenticacion.css']
            ],
            'recuperar-cuenta' => [
                'controlador_path' => __DIR__ . '/controllers/LoginController.php',
                'controlador'      => 'LoginController',
                'metodo'           => 'mostrarRecuperar',
                'vista'            => __DIR__ . '/views/recuperar_cuenta.php', 
                'titulo'           => 'Recuperación de Credenciales',
                'css'              => ['autenticacion.css']
            ],
            // Pantalla de Registro
            'registro' => [
                'controlador_path' => __DIR__ . '/controllers/LoginController.php',
                'controlador'      => 'LoginController',
                'metodo'           => 'mostrarRegistro',
                'vista'            => __DIR__ . '/views/registro.php', 
                'titulo'           => 'Crear Cuenta - Sistema UPTTMBI',
                'layout'           => ['header' => true, 'sidebar' => true, 'footer' => true],
                'css'              => ['autenticacion.css']
            ],
            // Endpoint invisible que procesa la creación de la cuenta
            'procesar-registro' => [
                'controlador_path' => __DIR__ . '/controllers/LoginController.php',
                'controlador'      => 'LoginController',
                'metodo'           => 'procesarRegistro',
                'vista'            => __DIR__ . '/views/transicion.php',
                'layout'           => ['header' => false, 'sidebar' => false, 'footer' => false],
                'css'              => ['transicion.css']
            ]
        ];
    }

    public function getMenuConfig(): array {
        return [
            [
                'tipo'        => 'parent',
                'titulo'      => 'Perfil',
                'icono'       => 'ph-fill ph-user-circle',
                'enlace'      => 'perfil',
                // Estas rutas mantendrán encendido el contenedor padre en el sidebar
                'activadores' => ['perfil', 'recuperar-cuenta', 'login'], 
                'subitems'    => [
                    
                ]
            ]
        ];
    }
    public function getDescripcion(): string {
        return 'Control de acceso, tokens JWT, recuperación de contraseñas y permisos RBAC (Rol-Based Access Control).';
    }
    public function getDependencias(): array {
        return []; 
    }
    public function getHomeConfig(): array {
        // Módulo de infraestructura. No requiere tarjeta pública.
        return [];
    }
    public function getHeaderConfig(): array {
        require_once CORE_PATH . 'Security/Auth.php';

        // Si el usuario ESTÁ LOGUEADO
        if (Auth::check()) {
            return [
                // 1. Inyectamos el widget del perfil con su foto
                [
                    'tipo'       => 'custom_view',
                    'ruta_vista' => __DIR__ . '/views/perfil_header.php',
                    'css'        => 'perfil_header.css',
                    'orden'      => 90
                ],
                // 2. Inyectamos el botón de Salir
                [
                    'tipo'   => 'button',
                    'texto'  => 'Salir',
                    'icono'  => 'ph-bold ph-sign-out',
                    'enlace' => 'cerrar-sesion',
                    'clase'  => 'btn btn-outline',
                    'orden'      => 100
                ]
            ];
        } 
        // Si el usuario es un VISITANTE
        else {
            return [
                [
                    'tipo'   => 'button',
                    'texto'  => 'Acceder',
                    'icono'  => 'ph-bold ph-sign-in',
                    'enlace' => 'login',
                    'clase'  => 'btn btn-outline',
                    'orden'      => 100
                ]
            ];
        }
    }
}

// Retornamos la instancia para que el Microkernel la procese
return new AutenticacionModule();