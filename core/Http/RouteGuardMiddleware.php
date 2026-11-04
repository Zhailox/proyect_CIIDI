<?php
// core/Http/RouteGuardMiddleware.php

require_once CORE_PATH . 'Interfaces/RouteGuardContract.php';

/**
 * Ejecuta los guardianes de rutas (módulos que implementan RouteGuardContract)
 * antes de despachar una ruta de módulo, y expone la visibilidad de enlaces al sidebar.
 */
class RouteGuardMiddleware {

    private static ?RouteGuardMiddleware $instancia = null;
    private static array $cacheVisibilidad = [];

    public function __construct(private ModuleLoader $moduleLoader) {
        self::$instancia = $this;
    }

    /** @return RouteGuardContract[] */
    private function guardianes(): array {
        $lista = [];
        foreach ($this->moduleLoader->getModulosInstalados() as $modulo) {
            if ($modulo instanceof RouteGuardContract) {
                $lista[] = $modulo;
            }
        }
        return $lista;
    }

    /**
     * @return array|null Respuesta de bloqueo del primer guardián que deniegue, o null.
     */
    public function intercept(string $ruta, array $configRuta): ?array {
        $modulo = $this->moduleLoader->getModuloDeRuta($ruta);
        if ($modulo === null) {
            return null;
        }
        $grupo = $this->moduleLoader->getGrupoDeModulo($modulo);

        foreach ($this->guardianes() as $guardian) {
            try {
                $respuesta = $guardian->interceptarRuta($ruta, $configRuta, $modulo, $grupo);
            } catch (Throwable $e) {
                if (class_exists('Connection')) {
                    Connection::logSystemError($e);
                }
                continue;
            }
            if (is_array($respuesta)) {
                return $respuesta;
            }
        }
        return null;
    }

    /**
     * Responde directamente cuando el bloqueo es una redirección o una petición AJAX/API.
     * Devuelve true si ya respondió; false si debe renderizarse la vista de bloqueo.
     */
    public function responderDirecto(array $bloqueo, array $configRuta): bool {
        $status = (int) ($bloqueo['status'] ?? 403);

        if (!empty($bloqueo['redirect'])) {
            header('Location: ' . $bloqueo['redirect']);
            return true;
        }

        $accept = strtolower((string) ($_SERVER['HTTP_ACCEPT'] ?? ''));
        $esAjax = strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')) === 'xmlhttprequest'
            || str_contains($accept, 'application/json')
            || !empty($configRuta['es_api']);

        if ($esAjax || empty($bloqueo['vista'])) {
            if (!headers_sent()) {
                http_response_code($status);
                header('Content-Type: application/json; charset=utf-8');
            }
            echo json_encode($bloqueo['json'] ?? ['status' => 'error', 'message' => 'Acceso denegado.'], JSON_UNESCAPED_UNICODE);
            return true;
        }
        return false;
    }

    /** Construye el "match" que el Dispatcher renderiza dentro de la plantilla maestra. */
    public static function construirMatch(array $bloqueo): array {
        return [
            'type'   => 'guard_block',
            'status' => (int) ($bloqueo['status'] ?? 403),
            'vista'  => $bloqueo['vista'],
            'titulo' => $bloqueo['titulo'] ?? '403 - Acceso Denegado',
            'layout' => $bloqueo['layout'] ?? ['header' => true, 'sidebar' => true, 'footer' => true],
            'css'    => $bloqueo['css'] ?? [],
            'datos'  => $bloqueo['datos'] ?? [],
        ];
    }

    /**
     * Visibilidad de un enlace del sidebar según los guardianes. Si no hay guardianes
     * o la ruta no pertenece a ningún módulo, siempre es visible.
     */
    public static function rutaVisible(string $ruta): bool {
        if (self::$instancia === null || $ruta === '' || $ruta === '#') {
            return true;
        }
        if (array_key_exists($ruta, self::$cacheVisibilidad)) {
            return self::$cacheVisibilidad[$ruta];
        }

        $loader = self::$instancia->moduleLoader;
        $modulo = $loader->getModuloDeRuta($ruta);
        if ($modulo === null) {
            return self::$cacheVisibilidad[$ruta] = true;
        }
        $grupo  = $loader->getGrupoDeModulo($modulo);
        $config = $loader->getRutasGlobales()[$ruta] ?? [];

        foreach (self::$instancia->guardianes() as $guardian) {
            try {
                if (!$guardian->esRutaVisible($ruta, $config, $modulo, $grupo)) {
                    return self::$cacheVisibilidad[$ruta] = false;
                }
            } catch (Throwable $e) {
                if (class_exists('Connection')) {
                    Connection::logSystemError($e);
                }
            }
        }
        return self::$cacheVisibilidad[$ruta] = true;
    }
}
