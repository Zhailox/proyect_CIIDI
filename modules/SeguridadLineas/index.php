<?php
// modules/SeguridadLineas/index.php

require_once CORE_PATH . 'Interfaces/ModuleContract.php';
require_once CORE_PATH . 'Interfaces/RouteGuardContract.php';
require_once __DIR__ . '/services/SeguridadMotor.php';

/**
 * Seguridad del macro módulo "Líneas de Investigación".
 *
 * Protege automáticamente a todos los módulos que declaran 'grupo' => 'lineas-investigacion'
 * en su menú (Líneas, Investigaciones, Formación, Sector Productivo y este mismo módulo).
 */
class SeguridadLineasModule implements ModuleContract, RouteGuardContract {

    public function getNombre(): string {
        return 'Seguridad · Líneas de Investigación';
    }

    public function getRutas(): array {
        $ctrl = __DIR__ . '/controllers/SeguridadController.php';
        $css  = ['seguridad_lineas.css'];
        $js   = ['seguridad_lineas.js'];

        $pagina = fn(string $metodo, string $vista, string $titulo) => [
            'controlador_path' => $ctrl,
            'controlador'      => 'SeguridadController',
            'metodo'           => $metodo,
            'vista'            => __DIR__ . '/views/' . $vista,
            'titulo'           => $titulo . ' · Seguridad Líneas de Investigación',
            'css'              => $css,
            'js'               => $js,
            'seguridad_accion' => 'ver',
        ];
        $accion = fn(string $metodo, string $accionSeg) => [
            'controlador_path' => $ctrl,
            'controlador'      => 'SeguridadController',
            'metodo'           => $metodo,
            'es_api'           => true,
            'seguridad_accion' => $accionSeg,
        ];

        return [
            // ── Páginas ────────────────────────────────────────────────
            'seguridad-lineas'            => $pagina('soc', 'soc.php', 'Centro de Operaciones'),
            'seguridad-lineas-bitacora'   => $pagina('bitacora', 'bitacora.php', 'Bitácora de Eventos'),
            'seguridad-lineas-permisos'   => $pagina('permisos', 'permisos.php', 'Matriz de Permisos'),
            'seguridad-lineas-amenazas'   => $pagina('amenazas', 'amenazas.php', 'Amenazas y Bloqueos'),
            'seguridad-lineas-politicas'  => $pagina('politicas', 'politicas.php', 'Políticas'),

            // ── Lectura / auditoría ────────────────────────────────────
            'seguridad-lineas-api-feed'   => $accion('apiFeed', 'ver'),
            'seguridad-lineas-api-evento' => $accion('apiEvento', 'ver'),
            'seguridad-lineas-simular'    => $accion('simular', 'ver'),
            'seguridad-lineas-verificar'  => $accion('verificarIntegridad', 'exportar'),
            'seguridad-lineas-exportar'   => array_merge($accion('exportarCsv', 'exportar'), ['es_api' => false]),

            // ── Administración (requieren 'configurar') ────────────────
            'seguridad-lineas-guardar-matriz'      => $accion('guardarMatriz', 'configurar'),
            'seguridad-lineas-restablecer-matriz'  => $accion('restablecerMatriz', 'configurar'),
            'seguridad-lineas-buscar-usuario'      => $accion('buscarUsuario', 'configurar'),
            'seguridad-lineas-excepcion-guardar'   => $accion('guardarExcepcion', 'configurar'),
            'seguridad-lineas-excepcion-eliminar'  => $accion('eliminarExcepcion', 'configurar'),
            'seguridad-lineas-ruta-guardar'        => $accion('guardarRuta', 'configurar'),
            'seguridad-lineas-bloquear-ip'         => $accion('bloquearIp', 'configurar'),
            'seguridad-lineas-desbloquear-ip'      => $accion('desbloquearIp', 'configurar'),
            'seguridad-lineas-alerta-atender'      => $accion('atenderAlerta', 'configurar'),
            'seguridad-lineas-expulsar'            => $accion('expulsarUsuario', 'configurar'),
            'seguridad-lineas-politicas-guardar'   => $accion('guardarPoliticas', 'configurar'),
            'seguridad-lineas-purgar'              => $accion('purgarLogs', 'configurar'),
        ];
    }

    public function getMenuConfig(): array {
        return [
            [
                'tipo'            => 'parent',
                'titulo'          => 'Seguridad',
                'icono'           => 'ph-fill ph-shield-checkered',
                'orden'           => 70,
                'grupo'           => SeguridadMotor::GRUPO,
                'orden_en_grupo'  => 50,
                'enlace'          => 'seguridad-lineas',
                // La visibilidad real la decide el guardián (matriz: Seguridad → Ver)
                'privilegio_minimo' => 999,
                'activadores'     => [
                    'seguridad-lineas', 'seguridad-lineas-bitacora', 'seguridad-lineas-permisos',
                    'seguridad-lineas-amenazas', 'seguridad-lineas-politicas',
                ],
                'subitems' => [
                    ['ruta' => 'seguridad-lineas',           'titulo' => 'Centro de Operaciones', 'privilegio_minimo' => 999],
                    ['ruta' => 'seguridad-lineas-bitacora',  'titulo' => 'Bitácora de Eventos',   'privilegio_minimo' => 999],
                    ['ruta' => 'seguridad-lineas-permisos',  'titulo' => 'Matriz de Permisos',    'privilegio_minimo' => 999],
                    ['ruta' => 'seguridad-lineas-amenazas',  'titulo' => 'Amenazas y Bloqueos',   'privilegio_minimo' => 999],
                    ['ruta' => 'seguridad-lineas-politicas', 'titulo' => 'Políticas',             'privilegio_minimo' => 999],
                ],
            ],
        ];
    }

    public function getDescripcion(): string {
        return 'Seguridad propia del macro módulo Líneas de Investigación: matriz de permisos por nivel, excepciones por usuario, bitácora encadenada con HMAC, detección de ráfagas, bloqueo de IPs, Lockdown y Solo Lectura.';
    }

    public function getDependencias(): array {
        return ['Autenticacion'];
    }

    public function getHomeConfig(): array {
        // Módulo de infraestructura: no publica tarjeta en el inicio.
        return [];
    }

    public function getHeaderConfig(): array {
        return [];
    }

    /* ── RouteGuardContract ─────────────────────────────────────────── */

    public function interceptarRuta(string $ruta, array $configRuta, string $moduloOrigen, ?string $grupo): ?array {
        if ($grupo !== SeguridadMotor::GRUPO) {
            return null;
        }
        return SeguridadMotor::interceptar($ruta, $configRuta, $moduloOrigen);
    }

    public function esRutaVisible(string $ruta, array $configRuta, string $moduloOrigen, ?string $grupo): bool {
        if ($grupo !== SeguridadMotor::GRUPO) {
            return true;
        }
        return SeguridadMotor::rutaVisible($ruta, $configRuta, $moduloOrigen);
    }
}

return new SeguridadLineasModule();
