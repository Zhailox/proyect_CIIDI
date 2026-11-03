<?php
// modules/SeguridadLineas/views/acceso_denegado.php
// Pantalla 403 emitida por el guardián del macro módulo Líneas de Investigación.

require_once __DIR__ . '/_helpers.php';

$d = $segDenegacion ?? [
    'codigo' => 'DENEGADO', 'motivo' => 'Acceso denegado.', 'incidente' => 'SEG-SIN-REGISTRO', 'ruta' => $_GET['ruta'] ?? '',
    'accion' => 'ver', 'submodulo' => 'Líneas de Investigación', 'ip' => $_SERVER['REMOTE_ADDR'] ?? '', 'logueado' => isset($_SESSION['usuario_id']),
    'usuario' => 'Visitante', 'fecha' => date('Y-m-d H:i:s'),
];
$titulos = [
    'IP_BLOQUEADA' => 'IP EN LISTA NEGRA',
    'LOCKDOWN'     => 'MÓDULO EN LOCKDOWN',
    'SOLO_LECTURA' => 'MODO SOLO LECTURA',
    'EXCEPCION'    => 'RESTRICCIÓN INDIVIDUAL',
    'MATRIZ'       => 'ACCESO NO AUTORIZADO',
];
$titulo = $titulos[$d['codigo']] ?? 'ACCESO DENEGADO';
$codigoHttp = $d['codigo'] === 'IP_BLOQUEADA' ? '403.6' : '403';
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700;800&display=swap" rel="stylesheet">

<div class="sec-lineas">
    <div class="sl-topbar">
        <span class="sl-dots"><i></i><i></i><i></i></span>
        <span class="sl-prompt"><b>guard@ciidi-soc</b>:<span class="sl-path">~/lineas-investigacion</span>$ access --route <?= sl_e($d['ruta']) ?> <span class="sl-cursor">█</span></span>
        <span class="sl-clock"><?= sl_e(substr((string) $d['fecha'], 11, 8)) ?></span>
    </div>

    <div class="sl-denied">
        <div>
            <i class="ph-fill ph-shield-warning sl-shield-icon"></i>
            <div class="sl-denied-code"><?= sl_e($codigoHttp) ?></div>
            <h1 class="sl-glitch" data-text="<?= sl_e($titulo) ?>"><?= sl_e($titulo) ?></h1>
            <p class="sl-sub" style="max-width:640px;margin:12px auto 0"><?= sl_e($d['motivo']) ?></p>

            <div class="sl-denied-term">
                <div class="sl-terminal">
<div><span class="sl-k">$</span> seclineas --evaluate</div>
<div>&gt; actor ........ <span class="sl-k"><?= sl_e($d['usuario']) ?></span></div>
<div>&gt; origen ....... <span class="sl-k"><?= sl_e($d['ip']) ?></span></div>
<div>&gt; submódulo .... <?= sl_e($d['submodulo']) ?></div>
<div>&gt; ruta ......... /<?= sl_e($d['ruta']) ?></div>
<div>&gt; acción ....... <?= sl_e(strtoupper((string) $d['accion'])) ?></div>
<div>&gt; regla ........ <span class="sl-ko"><?= sl_e($d['codigo']) ?></span></div>
<div>&gt; veredicto .... <span class="sl-ko">DENEGADO ✕</span></div>
<div>&gt; incidente .... <span class="sl-ok"><?= sl_e($d['incidente']) ?></span> registrado en la cadena de custodia</div>
<div>&gt; fecha ........ <?= sl_e($d['fecha']) ?></div>
<div><span class="sl-k">$</span> <span class="sl-cursor">█</span></div>
                </div>
            </div>

            <div class="sl-row">
                <a class="sec-lineas-btn sl-btn" href="inicio" style="color:var(--sl-green)"><i class="ph ph-house"></i>Volver al inicio</a>
                <?php if (empty($d['logueado'])): ?>
                    <a class="sl-btn sl-btn-cyan" href="login"><i class="ph ph-sign-in"></i>Iniciar sesión</a>
                <?php else: ?>
                    <a class="sl-btn sl-btn-ghost" href="javascript:history.back()"><i class="ph ph-arrow-left"></i>Regresar</a>
                <?php endif; ?>
            </div>
            <p class="sl-help sl-mt">Si crees que es un error, comunica el código <b><?= sl_e($d['incidente']) ?></b> al administrador de seguridad.</p>
        </div>
    </div>
</div>
