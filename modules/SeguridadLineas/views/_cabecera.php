<?php
// modules/SeguridadLineas/views/_cabecera.php
// Cabecera común (barra de terminal, hero, estado y pestañas) de todas las páginas del módulo.

require_once __DIR__ . '/_helpers.php';

$slPagina = $slPagina ?? 'soc';
$slCtx    = $slCtx ?? SeguridadMotor::contexto();
$slPol    = $slPol ?? SeguridadMotor::POLITICAS_DEFECTO;
$slPuede  = $slPuede ?? ['ver' => false, 'exportar' => false, 'configurar' => false];

$slTabs = [
    'soc'       => ['ruta' => 'seguridad-lineas',           'icono' => 'ph-monitor',          'titulo' => 'Centro de Operaciones', 'cmd' => 'soc --live'],
    'bitacora'  => ['ruta' => 'seguridad-lineas-bitacora',  'icono' => 'ph-terminal-window',  'titulo' => 'Bitácora',              'cmd' => 'tail -f /var/log/lineas.log'],
    'permisos'  => ['ruta' => 'seguridad-lineas-permisos',  'icono' => 'ph-lock-key',         'titulo' => 'Permisos',              'cmd' => 'acl --matrix --levels'],
    'amenazas'  => ['ruta' => 'seguridad-lineas-amenazas',  'icono' => 'ph-shield-warning',   'titulo' => 'Amenazas',              'cmd' => 'firewall --status --alerts'],
    'politicas' => ['ruta' => 'seguridad-lineas-politicas', 'icono' => 'ph-sliders-horizontal', 'titulo' => 'Políticas',           'cmd' => 'policy --edit'],
];
$slTabActual   = $slTabs[$slPagina] ?? $slTabs['soc'];
$slAlertasAb   = isset($kpis['alertas_abiertas']) ? (int) $kpis['alertas_abiertas'] : null;
$slMotorOk     = SeguridadMotor::disponible();
$slUsuarioCorto = preg_replace('/[^a-z0-9]+/', '', strtolower(@iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', explode(' ', (string) $slCtx['nombre'])[0]) ?: 'op')) ?: 'op';
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700;800&display=swap" rel="stylesheet">

<div class="sec-lineas" id="sec-lineas-app" data-sl-page="<?= sl_e($slPagina) ?>">

    <!-- Barra de terminal -->
    <div class="sl-topbar">
        <span class="sl-dots"><i></i><i></i><i></i></span>
        <span class="sl-prompt">
            <b><?= sl_e($slUsuarioCorto) ?>@ciidi-soc</b>:<span class="sl-path">~/lineas-investigacion/seguridad</span>$
            <span data-sl-type="<?= sl_e($slTabActual['cmd']) ?>"></span><span class="sl-cursor">█</span>
        </span>
        <span class="sl-clock" id="slClock">--:--:--</span>
    </div>

    <!-- Hero -->
    <header class="sl-hero">
        <canvas class="sl-rain" id="slRain"></canvas>
        <div class="sl-hero-inner">
            <div>
                <span class="sl-kicker">// MACRO MÓDULO · LÍNEAS DE INVESTIGACIÓN</span>
                <h1 class="sl-glitch" id="sec-lineas-title" style="cursor: pointer; user-select: none;" title="Toggle Hacker Mode" data-text="SEC::LINEAS">SEC::LINEAS</h1>
                <p class="sl-sub">Seguridad perimetral de <b>Líneas</b>, <b>Investigaciones</b>, <b>Formación</b> y <b>Sector Productivo</b> · <?= sl_e($slTabActual['titulo']) ?></p>
            </div>
            <div class="sl-status">
                <span class="sl-chip <?= $slMotorOk ? '' : 'is-danger' ?>"><span class="sl-led"></span>Motor <?= $slMotorOk ? 'online' : 'degradado' ?></span>
                <span class="sl-chip <?= !empty($slPol['modo_bloqueo']) ? 'is-danger' : 'is-off' ?>"><span class="sl-led"></span>Lockdown <?= !empty($slPol['modo_bloqueo']) ? 'activo' : 'off' ?></span>
                <span class="sl-chip <?= !empty($slPol['solo_lectura']) ? 'is-warn' : 'is-off' ?>"><span class="sl-led"></span>Solo lectura <?= !empty($slPol['solo_lectura']) ? 'on' : 'off' ?></span>
                <span class="sl-chip is-info"><span class="sl-led"></span><?= sl_e(sl_nivel($slCtx['nivel'])) ?> · <?= $slPuede['configurar'] ? 'RW' : 'RO' ?></span>
                <span class="sl-chip is-info"><span class="sl-led"></span>IP <?= sl_e($slCtx['ip']) ?></span>
            </div>
        </div>
    </header>

    <!-- Pestañas -->
    <nav class="sl-tabs">
        <?php foreach ($slTabs as $clave => $tab): ?>
            <a class="sl-tab <?= $clave === $slPagina ? 'is-active' : '' ?>" href="<?= sl_e($tab['ruta']) ?>">
                <i class="ph <?= sl_e($tab['icono']) ?>"></i><?= sl_e($tab['titulo']) ?>
                <?php if ($clave === 'amenazas' && $slAlertasAb): ?><span class="sl-count"><?= $slAlertasAb ?></span><?php endif; ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <section class="sl-body">
        <?php if (!empty($slError)): ?>
            <div class="sl-alert sl-alert-danger">
                <i class="ph ph-warning-octagon"></i>
                <div><b>Fallo del motor de seguridad.</b> <?= sl_e($slError) ?><br><small>El macro módulo sigue operando en modo degradado (fail-open). Revisa la conexión a la base de datos.</small></div>
            </div>
        <?php endif; ?>
        <?php if (!$slPuede['configurar'] && empty($slError)): ?>
            <div class="sl-alert sl-alert-info">
                <i class="ph ph-eye"></i>
                <div>Modo observador: tu nivel puede consultar pero no modificar la configuración de seguridad.</div>
            </div>
        <?php endif; ?>

