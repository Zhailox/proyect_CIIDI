<?php
// modules/NombreDelNuevoModulo/index.php
require_once CORE_PATH . 'Interfaces/ModuleContract.php';

class NombreDelNuevoModulo implements ModuleContract {
    
    /**
     * 1. IDENTIFICACIÓN
     * El nombre formal del módulo para registros internos.
     */
    public function getNombre(): string {
        return 'Nombre del Módulo';
    }

    /**
     * 2. DESCRIPCIÓN PARA EL SUPERADMIN
     * Texto que explica qué hace este paquete.
     */
    public function getDescripcion(): string {
        return 'Descripción breve de la funcionalidad principal de este módulo.';
    }

    /**
     * 3. DEPENDENCIAS
     * Arreglo con los nombres de otros módulos que deben estar activos para que este funcione, 
     * se le avisará al superadmin si lo intenta apagar sin desactivar esos módulos dependientes
     */
    public function getDependencias(): array {
        return []; // Retornar arreglo vacío si es independiente
    }

    /**
     * 4. ENRUTAMIENTO Y VISTAS
     * Define las URLs que este módulo va a manejar y qué vistas/css va a cargar.
     */
    public function getRutas(): array {
        return [
            'ruta-de-ejemplo' => [
                'vista'  => __DIR__ . '/views/mi_vista.php',
                'titulo' => 'Título de la Pestaña',
                'css'    => ['mi_estilo.css'] // Solo el nombre del archivo que DEBE estar dentro de /assets/css/
                /** 'layout' => [
                *    'header'  => true,
                *    'sidebar' => true,
                *    'footer'  => true
                *], 
                * Layout es opcional y por defecto todo es true. 
                *Si quieres desactivar alguno para esta ruta, configúralo aquí. Ejemplo: 'header' => false para ocultar el header en esta vista específica.
                */
            ]
        ];
    }
    /**
     * 5. NAVEGACIÓN GLOBAL (SIDEBAR)
     * Configuración del botón que aparecerá en la sidebar
     */
    public function getMenuConfig(): array {
        return [
            [
                'tipo'        => 'link', // Usar 'link' para botón directo, 'parent' para desplegable
                'titulo'      => 'Mi Módulo',
                'icono'       => 'ph-fill ph-check-circle', // Clase de Phosphor Icons https://phosphoricons.com/
                'enlace'      => 'ruta-de-ejemplo',
                'activadores' => ['ruta-de-ejemplo', 'otra-ruta'], //Rutas que mantendrán el botón "activo" en el sidebar
                'subitems'    => [
                    ['ruta' => 'ruta-de-ejemplo', 'titulo' => 'El Ejemplo 1'],
                    ['ruta' => 'otro-ejemplo', 'titulo' => 'El Ejemplo 2']
                ] // Dejar vacío si el tipo es 'link'
            ]
        ];
    }
    /**
     * 6. TARJETA DE INICIO (HOME)
     * Configuración de la tarjeta que aparecerá en la pantalla de bienvenida.
     * Retornar vacío si no debe aparecer en el inicio con:
     * public function getHomeConfig(): array {
     *   return [];
    *  } 
     */
    public function getHomeConfig(): array {
        return [
            'icono'       => 'ph-fill ph-check-circle',
            'titulo'      => 'Mi Módulo',
            'descripcion' => 'Acceso directo a la funcionalidad de mi módulo.',
            'enlace'      => 'ruta-de-ejemplo',
            'texto_boton' => 'ENTRAR',
            'destacado'   => false
        ];
    }

    /**
     * 7. INYECCIÓN EN LA CABECERA (HEADER)
     * Controles adicionales (botones, buscadores) para la barra superior.
     * Retornar [] si no inyecta nada.
     */
    public function getHeaderConfig(): array {
        return []; 
    }
    /**
     * PARA TIPO BOTÓN COMO EL DEL LOGIN
     * public function getHeaderConfig(): array {
     *   return [
     *       'tipo'   => 'button',
     *       'texto'  => 'Acceder',
     *       'icono'  => 'ph-bold ph-sign-in',
     *       'enlace' => 'login',
     *       'clase'  => 'btn'
     *   ];
     *}
     * PARA TIPO BUSCADOR COMO EL DEL REPOSITORIO PST
     * public function getHeaderConfig(): array {
     *   return [
     *       'tipo'       => 'search',
     *       'placeholder' => 'Buscador Inteligente...',
     *       'icono'       => 'ph ph-magnifying-glass',
     *       'accion'      => 'buscador'
     *   ];
     *}
     * PARA VISTAS CUSTOMIZADAS (QUE INYECTAN SU PROPIO CSS) 
     * 
     * public function getHeaderConfig(): array {
     *   return [
     *       'tipo'       => 'custom_view',
     *       'ruta_vista' => __DIR__ . '/views/btn_notificaciones.php',
     *       'css'        => '../modules/Articulos/assets/css/notificaciones.css' 
     * NOTA IMPORTANTE: EL CSS DE AQUÍ SE CARGA DIRECTAMENTE AL HEADER, NO EN UNA VISTA ESPECÍFICA
     *   ];
     * }
     */
}

// OBLIGATORIO: El archivo debe retornar la instancia de la clase al final
return new NombreDelNuevoModulo();