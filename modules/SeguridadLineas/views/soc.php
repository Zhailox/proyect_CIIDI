<?php
// modules/SeguridadLineas/views/soc.php
// Centro de Operaciones de Seguridad (SOC) del macro módulo Líneas de Investigación.

require __DIR__ . '/_cabecera.php';

if (empty($slError)):
    $k        = $kpis ?? [];
    $amenaza  = (int) ($k['amenaza'] ?? 0);
    $nivelAm  = $k['amenaza_nivel'] ?? 'BAJO';
    $colorAm  = ['BAJO' => 'var(--sl-green)', 'MODERADO' => 'var(--sl-amber)', 'ELEVADO' => '#ff7a45', 'CRITICO' => 'var(--sl-red)'][$nivelAm] ?? 'var(--sl-green)';
    $descAm   = [
        'BAJO'     => 'Actividad normal. Sin patrones hostiles relevantes.',
        'MODERADO' => 'Se observan denegaciones o eventos sensibles. Mantener vigilancia.',
        'ELEVADO'  => 'Ráfagas de denegaciones o alertas abiertas. Revisar la pestaña Amenazas.',
        'CRITICO'  => 'Múltiples indicadores hostiles. Considera activar Lockdown.',
    ][$nivelAm] ?? '';
    $maxSub = max(1, ...array_map(fn($s) => (int) $s['total'], $porSubmodulo ?: [['total' => 1]]));
?>

<!-- KPIs -->
<div class="sl-grid sl-grid-kpi">
    <div class="sl-card sl-kpi">
        <div class="sl-kpi-label">Eventos 24h <i class="ph ph-pulse"></i></div>
        <div class="sl-kpi-value" data-sl-kpi="eventos_24h"><?= (int) ($k['eventos_24h'] ?? 0) ?></div>
        <div class="sl-kpi-foot"><span data-sl-kpi="total_eventos"><?= (int) ($k['total_eventos'] ?? 0) ?></span> en la cadena</div>
        <i class="ph-fill ph-pulse sl-spark"></i>
    </div>
    <div class="sl-card sl-kpi is-danger">
        <div class="sl-kpi-label">Denegados 24h <i class="ph ph-hand-palm"></i></div>
        <div class="sl-kpi-value" data-sl-kpi="denegados_24h"><?= (int) ($k['denegados_24h'] ?? 0) ?></div>
        <div class="sl-kpi-foot">Tasa <span data-sl-kpi="tasa_denegacion"><?= sl_e($k['tasa_denegacion'] ?? 0) ?></span>%</div>
        <i class="ph-fill ph-hand-palm sl-spark"></i>
    </div>
    <div class="sl-card sl-kpi is-info">
        <div class="sl-kpi-label">Actores 15 min <i class="ph ph-users-three"></i></div>
        <div class="sl-kpi-value" data-sl-kpi="activos_15m"><?= (int) ($k['activos_15m'] ?? 0) ?></div>
        <div class="sl-kpi-foot"><span data-sl-kpi="ips_24h"><?= (int) ($k['ips_24h'] ?? 0) ?></span> IPs distintas hoy</div>
        <i class="ph-fill ph-users-three sl-spark"></i>
    </div>
    <div class="sl-card sl-kpi is-warn">
        <div class="sl-kpi-label">Alertas abiertas <i class="ph ph-siren"></i></div>
        <div class="sl-kpi-value" data-sl-kpi="alertas_abiertas"><?= (int) ($k['alertas_abiertas'] ?? 0) ?></div>
        <div class="sl-kpi-foot"><span data-sl-kpi="criticos_24h"><?= (int) ($k['criticos_24h'] ?? 0) ?></span> eventos críticos 24h</div>
        <i class="ph-fill ph-siren sl-spark"></i>
    </div>
    <div class="sl-card sl-kpi is-danger">
        <div class="sl-kpi-label">IPs bloqueadas <i class="ph ph-prohibit"></i></div>
        <div class="sl-kpi-value" data-sl-kpi="ips_bloqueadas"><?= (int) ($k['ips_bloqueadas'] ?? 0) ?></div>
        <div class="sl-kpi-foot">Firewall de aplicación</div>
        <i class="ph-fill ph-prohibit sl-spark"></i>
    </div>
    <div class="sl-card sl-kpi">
        <div class="sl-kpi-label">Excepciones <i class="ph ph-user-switch"></i></div>
        <div class="sl-kpi-value" data-sl-kpi="excepciones"><?= (int) ($k['excepciones'] ?? 0) ?></div>
        <div class="sl-kpi-foot">Reglas por usuario vigentes</div>
        <i class="ph-fill ph-user-switch sl-spark"></i>
    </div>
</div>

<!-- Tráfico + amenaza -->
<div class="sl-grid sl-grid-main sl-mt">
    <div class="sl-card">
        <div class="sl-card-head">
            <span class="sl-card-title"><i class="ph ph-chart-bar"></i>Tráfico del macro módulo · 24 h</span>
            <span class="sl-legend">
                <span><i style="background:var(--sl-green)"></i>Permitidos</span>
                <span><i style="background:var(--sl-red)"></i>Denegados</span>
                <span><i style="background:var(--sl-violet)"></i>Admin/Sistema</span>
            </span>
        </div>
        <svg class="sl-chart" id="slChart" viewBox="0 0 720 220" preserveAspectRatio="none" role="img" aria-label="Tráfico por hora"></svg>
    </div>
    <div class="sl-card">
        <div class="sl-card-head"><span class="sl-card-title"><i class="ph ph-gauge"></i>Nivel de amenaza</span></div>
        <div class="sl-gauge">
            <div class="sl-gauge-ring" id="slGauge" style="--v:<?= $amenaza ?>;--c:<?= $colorAm ?>">
                <span><span><b data-sl-kpi="amenaza"><?= $amenaza ?></b><small>/ 100</small></span></span>
            </div>
            <div class="sl-gauge-info">
                <b id="slGaugeNivel" style="color:<?= $colorAm ?>"><?= sl_e($nivelAm) ?></b>
                <p id="slGaugeDesc"><?= sl_e($descAm) ?></p>
            </div>
        </div>
        <div class="sl-mt">
            <div class="sl-card-title" style="margin-bottom:8px"><i class="ph ph-link"></i>Cadena de custodia</div>
            <p class="sl-help">Cada evento se firma con HMAC-SHA256 y se enlaza con el anterior. Cualquier alteración o borrado manual en la BD rompe la cadena.</p>
            <div class="sl-row sl-mt-sm">
                <button type="button" class="sl-btn sl-btn-cyan" data-sl-verify <?= $slPuede['exportar'] ? '' : 'disabled' ?>><i class="ph ph-fingerprint"></i>Verificar integridad</button>
            </div>
            <div id="slVerifyOut" class="sl-mt-sm"></div>
        </div>
    </div>
</div>

<!-- Feed en vivo + alertas -->
<div class="sl-grid sl-grid-main sl-mt">
    <div class="sl-card">
        <div class="sl-card-head">
            <span class="sl-card-title"><i class="ph ph-terminal"></i>Feed en vivo</span>
            <span class="sl-row">
                <span class="sl-chip" id="slFeedEstado"><span class="sl-led"></span>Live · <?= (int) ($slPol['refresco_feed_seg'] ?? 5) ?>s</span>
                <button type="button" class="sl-btn sl-btn-ghost sl-btn-sm" id="slFeedPausa"><i class="ph ph-pause"></i>Pausar</button>
            </span>
        </div>
        <div class="sl-terminal" id="slTerminal" data-ultimo="<?= (int) ($k['ultimo_id'] ?? 0) ?>">
            <?php if (empty($eventos)): ?>
                <div class="sl-line res-sistema"><span class="t">[--:--:--]</span><span class="r">SISTEMA</span><span class="p">Esperando eventos del macro módulo…</span></div>
            <?php endif; ?>
            <?php foreach (array_reverse($eventos ?? []) as $ev): ?>
                <div class="sl-line res-<?= sl_e(strtolower($ev['resultado'])) ?> sev-<?= sl_e(strtolower($ev['severidad'])) ?>" data-id="<?= (int) $ev['id'] ?>" title="<?= sl_e($ev['detalles']) ?>">
                    <span class="t">[<?= sl_e(substr((string) $ev['fecha'], 11, 8)) ?>]</span>
                    <span class="r"><?= sl_e($ev['resultado']) ?></span>
                    <span class="u"><?= sl_e($ev['usuario_nombre'] ?: 'anon') ?></span>
                    <span class="ip"><?= sl_e($ev['ip']) ?></span>
                    <span class="p"><?= sl_e($ev['metodo']) ?> /<?= sl_e($ev['ruta'] ?: $ev['accion']) ?> · <?= sl_e(sl_sub($ev['submodulo'])) ?>:<?= sl_e($ev['accion']) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="sl-terminal-foot">
            <span>Clic en una línea para ver el detalle forense.</span>
            <span id="slFeedHora">sync <?= sl_e(date('H:i:s')) ?></span>
        </div>
    </div>

    <div class="sl-stack">
        <div class="sl-card is-danger">
            <div class="sl-card-head">
                <span class="sl-card-title"><i class="ph ph-siren"></i>Alertas abiertas</span>
                <a class="sl-btn sl-btn-ghost sl-btn-sm" href="seguridad-lineas-amenazas"><i class="ph ph-arrow-right"></i>Ver todas</a>
            </div>
            <div class="sl-alerts" id="slAlertas">
                <?php if (empty($alertas)): ?>
                    <div class="sl-empty"><i class="ph ph-shield-check"></i>Sin alertas abiertas.</div>
                <?php endif; ?>
                <?php foreach ($alertas ?? [] as $a): ?>
                    <div class="sl-alert-item" data-alerta="<?= (int) $a['id'] ?>">
                        <i class="ph ph-warning-octagon"></i>
                        <div>
                            <b><?= sl_e($a['titulo']) ?></b>
                            <small><?= sl_e($a['detalle']) ?></small>
                            <small><?= sl_e($a['fecha']) ?> <?= $a['ip'] ? '· ' . sl_e($a['ip']) : '' ?></small>
                        </div>
                        <?php if ($slPuede['configurar']): ?>
                            <button type="button" class="sl-btn sl-btn-sm" data-sl-atender="<?= (int) $a['id'] ?>" title="Marcar como atendida"><i class="ph ph-check"></i></button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="sl-card">
            <div class="sl-card-head"><span class="sl-card-title"><i class="ph ph-crosshair"></i>Top IPs hostiles · 24 h</span></div>
            <?php if (empty($topIps)): ?>
                <div class="sl-empty"><i class="ph ph-globe-hemisphere-west"></i>Ninguna IP con denegaciones.</div>
            <?php else: ?>
                <div class="sl-table-wrap">
                    <table class="sl-table">
                        <thead><tr><th>IP</th><th>Denegaciones</th><th>Última</th><th></th></tr></thead>
                        <tbody>
                        <?php foreach ($topIps as $t): ?>
                            <tr>
                                <td><span class="sl-tag sl-badge is-violet"><?= sl_e($t['ip']) ?></span></td>
                                <td><b style="color:var(--sl-red)"><?= (int) $t['total'] ?></b></td>
                                <td class="sl-dim sl-nowrap"><?= sl_e(sl_hace($t['ultima'])) ?></td>
                                <td>
                                    <?php if ($slPuede['configurar'] && $t['ip'] !== $slCtx['ip']): ?>
                                        <button type="button" class="sl-btn sl-btn-danger sl-btn-sm" data-sl-bloquear-rapido="<?= sl_e($t['ip']) ?>"><i class="ph ph-prohibit"></i></button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Submódulos + actores -->
<div class="sl-grid sl-grid-2 sl-mt">
    <div class="sl-card">
        <div class="sl-card-head"><span class="sl-card-title"><i class="ph ph-tree-structure"></i>Actividad por submódulo · 24 h</span></div>
        <?php if (empty($porSubmodulo)): ?>
            <div class="sl-empty"><i class="ph ph-chart-bar-horizontal"></i>Aún no hay actividad registrada.</div>
        <?php else: ?>
            <div class="sl-hbars">
                <?php foreach ($porSubmodulo as $s): ?>
                    <div>
                        <div class="sl-hbar-top">
                            <span><?= sl_e(sl_sub($s['submodulo'])) ?></span>
                            <span><?= (int) $s['total'] ?> ev · <span style="color:var(--sl-red)"><?= (int) $s['denegados'] ?> ✕</span></span>
                        </div>
                        <div class="sl-hbar">
                            <i style="width:<?= round($s['total'] * 100 / $maxSub, 1) ?>%"></i>
                            <i class="ko" style="width:<?= round($s['denegados'] * 100 / $maxSub, 1) ?>%"></i>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="sl-card">
        <div class="sl-card-head"><span class="sl-card-title"><i class="ph ph-user-focus"></i>Sesiones activas · 15 min</span></div>
        <div id="slActivos">
            <?php if (empty($activos)): ?>
                <div class="sl-empty"><i class="ph ph-user-circle-dashed"></i>Sin usuarios autenticados recientes.</div>
            <?php else: ?>
                <div class="sl-table-wrap">
                    <table class="sl-table">
                        <thead><tr><th>Usuario</th><th>Nivel</th><th>IP</th><th>Ev.</th><th>Última</th><th></th></tr></thead>
                        <tbody>
                        <?php foreach ($activos as $u): ?>
                            <tr>
                                <td><?= sl_e($u['nombre']) ?></td>
                                <td><span class="sl-badge sl-tag is-cyan"><?= sl_e(sl_nivel($u['nivel'])) ?></span></td>
                                <td class="sl-dim"><?= sl_e($u['ip']) ?></td>
                                <td><?= (int) $u['eventos'] ?><?= (int) $u['denegados'] ? ' <span style="color:var(--sl-red)">(' . (int) $u['denegados'] . '✕)</span>' : '' ?></td>
                                <td class="sl-dim sl-nowrap"><?= sl_e(sl_hace($u['ultima'])) ?></td>
                                <td>
                                    <?php if ($slPuede['configurar'] && (int) $u['usuario_id'] !== (int) $slCtx['id']): ?>
                                        <button type="button" class="sl-btn sl-btn-danger sl-btn-sm" data-sl-expulsar="<?= (int) $u['usuario_id'] ?>" data-nombre="<?= sl_e($u['nombre']) ?>"><i class="ph ph-sign-out"></i>Expulsar</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script type="application/json" id="slData"><?= json_encode(['serie' => $serie ?? []], JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?></script>

<?php endif; ?>

<?php require __DIR__ . '/_pie.php'; ?>
