<?php
// modules/SeguridadLineas/views/_helpers.php
// Funciones de presentación compartidas por las vistas del módulo de Seguridad.

if (!function_exists('sl_e')) {
    function sl_e($v): string {
        return htmlspecialchars((string) ($v ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('sl_sev')) {
    function sl_sev(?string $sev): string {
        $sev = strtoupper((string) $sev);
        $ico = ['INFO' => 'ph-info', 'AVISO' => 'ph-warning', 'ALERTA' => 'ph-warning-octagon', 'CRITICO' => 'ph-skull'][$sev] ?? 'ph-info';
        return '<span class="sl-badge sl-sev-' . sl_e(strtolower($sev)) . '"><i class="ph ' . $ico . '"></i>' . sl_e($sev) . '</span>';
    }
}

if (!function_exists('sl_res')) {
    function sl_res(?string $res): string {
        $res = strtoupper((string) $res);
        return '<span class="sl-badge sl-res-' . sl_e(strtolower($res)) . '">' . sl_e($res) . '</span>';
    }
}

if (!function_exists('sl_nivel')) {
    function sl_nivel($nivel): string {
        $n = (int) $nivel;
        if ($n === SeguridadMotor::NIVEL_VISITANTE) return 'VISITANTE';
        if ($n === 0) return 'N0 · ROOT';
        return 'N' . $n;
    }
}

if (!function_exists('sl_sub')) {
    function sl_sub(?string $carpeta): string {
        return SeguridadMotor::tituloSubmodulo((string) $carpeta);
    }
}

if (!function_exists('sl_hace')) {
    /** Tiempo relativo corto ("hace 3 min"). */
    function sl_hace(?string $fecha): string {
        if (!$fecha) return '—';
        $d = time() - strtotime($fecha);
        if ($d < 60) return 'hace ' . max(0, $d) . ' s';
        if ($d < 3600) return 'hace ' . floor($d / 60) . ' min';
        if ($d < 86400) return 'hace ' . floor($d / 3600) . ' h';
        return 'hace ' . floor($d / 86400) . ' d';
    }
}

if (!function_exists('sl_qs')) {
    /** Construye una query string conservando filtros y sobrescribiendo claves. */
    function sl_qs(array $base, array $cambios = []): string {
        $q = array_filter(array_merge($base, $cambios), fn($v) => $v !== '' && $v !== null);
        return $q ? '?' . http_build_query($q) : '';
    }
}
