<?php
// core/Http/FeatureFlagMiddleware.php

class FeatureFlagMiddleware {
    private string $configFilePath;
    private array $rutasEsenciales = [
        'login', 'procesar-login', 'cerrar-sesion', 'sudoadmin', 
        'gestor-modulos', 'alternar-modulo', 'alternar-estado-ruta', 
        'gestor-usuarios', 'visor-logs'
    ];

    public function __construct(?string $configFilePath = null) {
        $this->configFilePath = $configFilePath ?? (__DIR__ . '/../../storage/config_system.json');
    }

    /**
     * Revisa si la ruta solicitada está deshabilitada (offline) o en modo solo lectura.
     * Retorna true si interceptó y mostró una vista de bloqueo.
     */
    public function intercept(string $ruta): bool {
        if ($ruta === 'inicio' || in_array($ruta, $this->rutasEsenciales, true)) {
            return false;
        }

        if (!file_exists($this->configFilePath)) {
            return false;
        }

        $configSistema = json_decode(file_get_contents($this->configFilePath), true) ?: [];
        $rutasConfig = $configSistema['rutas'] ?? [];

        if (isset($rutasConfig[$ruta])) {
            $estadoRuta = $rutasConfig[$ruta]['estado'] ?? 'online';
            $mensajeRutaDeshabilitada = $rutasConfig[$ruta]['mensaje'] ?? 'Esta funcionalidad se encuentra temporalmente deshabilitada por el administrador.';

            if ($estadoRuta === 'offline') {
                $modoRuta = 'desactivado';
                require_once CORE_VIEWS . 'deshabilitado.php';
                exit;
            }

            if ($estadoRuta === 'solo_lectura' && ($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
                $modoRuta = 'solo_lectura';
                $mensajeRutaDeshabilitada = !empty($rutasConfig[$ruta]['mensaje']) 
                    ? $rutasConfig[$ruta]['mensaje'] 
                    : 'El sistema se encuentra en modo Solo Lectura para esta función. No se permiten modificaciones en este momento.';
                require_once CORE_VIEWS . 'deshabilitado.php';
                exit;
            }
        }

        return false;
    }
}
