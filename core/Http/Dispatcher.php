<?php
// core/Http/Dispatcher.php

class Dispatcher {
    private Kernel $kernel;

    public function __construct(Kernel $kernel) {
        $this->kernel = $kernel;
    }

    public function getTarjetasInicio(): array {
        return $this->kernel->getTarjetasInicio();
    }

    public function getControlesHeader(): array {
        return $this->kernel->getControlesHeader();
    }

    public function getGlobalCss(): array {
        return $this->kernel->getGlobalCss();
    }

    public function getHomeCss(): array {
        return $this->kernel->getHomeCss();
    }

    public function getInfoModulosAdmin(): array {
        return $this->kernel->getInfoModulosAdmin();
    }
    
    /**
     * Ejecuta el controlador si aplica y renderiza la plantilla maestra (master.php).
     */
    public function dispatch(
        string $ruta, 
        array $match, 
        array $modulosInstalados, 
        array $menuGlobal, 
        AssetResolver $assetResolver
    ): void {
        try {
            $type = $match['type'];
            $titulo_pagina = $match['titulo'];
            $layout_config = $match['layout'];
            $vista_modulo_path = $match['vista'];

            $css_modulo = [];
            $js_modulo = [];

            if ($type === 'native_home') {
                $css_modulo = $assetResolver->getHomeCss($modulosInstalados);
            } elseif ($type === 'module_route') {
                $configRuta = $match['config'];

                if (isset($configRuta['controlador'], $configRuta['controlador_path'])) {
                    require_once $configRuta['controlador_path'];
                    $claseControlador = $configRuta['controlador'];
                    $metodo = $configRuta['metodo'];

                    $instancia = new $claseControlador();
                    $datosVista = $instancia->$metodo();

                    if (is_array($datosVista)) {
                        extract($datosVista);
                    } elseif ($datosVista === false) {
                        exit;
                    }
                }

                $css_modulo = $assetResolver->resolveRouteCss($ruta, $configRuta, $modulosInstalados);
                $js_modulo = $assetResolver->resolveRouteJs($ruta, $configRuta, $modulosInstalados);
            } elseif ($type === '404') {
                http_response_code(404);
            }

            $menu_dinamico = $menuGlobal;

            if (file_exists(CORE_VIEWS . 'master.php')) {
                include CORE_VIEWS . 'master.php';
            } else {
                http_response_code(500);
                if (class_exists('Connection')) {
                    Connection::logSystemError(new Exception("Fallo Crítico: No se encuentra la plantilla maestra en " . CORE_VIEWS . 'master.php'));
                }
                echo "<div style='padding:40px;text-align:center;font-family:sans-serif;'><h2>Error 500: Plantilla del sistema no encontrada</h2></div>";
                exit;
            }
        } catch (DatabaseConnectionException $e) {
            $this->handleDatabaseException($e);
        }
    }

    /**
     * Maneja de forma centralizada las fallas de conexión a la Base de Datos.
     */
    private function handleDatabaseException(DatabaseConnectionException $e): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['db_connection_error'] = $e->getDebugDetails();

        if ($e->isAjax()) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'error' => true,
                'mensaje' => $e->getMessage(),
                'debug' => $e->getDebugDetails()
            ]);
            exit;
        }

        http_response_code(500);
        $dbErrorMsg = $e->getDebugDetails();
        $isStandalone = true;
        $standalone = true;
        $vista_modulo_path = defined('CORE_VIEWS') ? CORE_VIEWS . '500.php' : __DIR__ . '/../Views/500.php';

        if (file_exists($vista_modulo_path)) {
            include $vista_modulo_path;
        } else {
            echo "<div style='padding:40px;text-align:center;'><h2>Error 500: Servicio Temporalmente No Disponible</h2></div>";
        }
        exit;
    }
}
