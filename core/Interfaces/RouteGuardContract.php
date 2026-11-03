<?php
// core/Interfaces/RouteGuardContract.php

/**
 * Contrato opcional para módulos que actúan como guardianes de rutas.
 *
 * El core (RouteGuardMiddleware) consulta a todos los módulos instalados que implementen
 * esta interfaz ANTES de ejecutar el controlador de cualquier ruta de módulo.
 * Esto permite, por ejemplo, que un módulo de seguridad proteja a todos los módulos
 * de un macro módulo sin modificar sus controladores.
 */
interface RouteGuardContract {

    /**
     * Decide si la ruta puede ejecutarse.
     *
     * @param string      $ruta          Ruta solicitada (p. ej. 'cursos-crear').
     * @param array       $configRuta    Configuración de la ruta declarada por su módulo.
     * @param string      $moduloOrigen  Carpeta del módulo dueño de la ruta.
     * @param string|null $grupo         Macro módulo (grupo del sidebar) al que pertenece el módulo, si aplica.
     *
     * @return array|null  null para permitir. Para bloquear, un arreglo con:
     *   - status   (int)    Código HTTP (por defecto 403).
     *   - redirect (string) Opcional: redirige en lugar de renderizar.
     *   - json     (array)  Respuesta para peticiones AJAX/API.
     *   - vista    (string) Ruta absoluta de la vista de bloqueo.
     *   - titulo   (string) Título de la página.
     *   - css      (array)  Hojas de estilo (relativas a /public).
     *   - datos    (array)  Variables que se extraen para la vista.
     */
    public function interceptarRuta(string $ruta, array $configRuta, string $moduloOrigen, ?string $grupo): ?array;

    /**
     * Indica si un enlace del sidebar debe mostrarse al usuario actual.
     */
    public function esRutaVisible(string $ruta, array $configRuta, string $moduloOrigen, ?string $grupo): bool;
}
