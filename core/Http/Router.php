<?php
// core/Http/Router.php

class Router {
    
    /**
     * Resuelve la ruta solicitada en la tabla de rutas globales.
     */
    public function resolve(string $ruta, array $rutasGlobales): array {
        if ($ruta === 'inicio') {
            return [
                'type' => 'native_home',
                'vista' => CORE_VIEWS . 'home_bienvenida.php',
                'titulo' => 'Inicio - Sistema Integral UPTTMBI',
                'layout' => ['header' => true, 'sidebar' => true, 'footer' => true]
            ];
        }

        if (array_key_exists($ruta, $rutasGlobales)) {
            $configRuta = $rutasGlobales[$ruta];
            return [
                'type' => 'module_route',
                'config' => $configRuta,
                'vista' => $configRuta['vista'] ?? null,
                'titulo' => $configRuta['titulo'] ?? 'Sistema Integral',
                'layout' => $configRuta['layout'] ?? ['header' => true, 'sidebar' => true, 'footer' => true]
            ];
        }

        return [
            'type' => '404',
            'vista' => CORE_VIEWS . '404.php',
            'titulo' => '404 - Página No Encontrada',
            'layout' => ['header' => true, 'sidebar' => true, 'footer' => true]
        ];
    }
}
