<?php
// modules/SeguridadLineas/views/amenazas.php
// Firewall de aplicación: bloqueos de IP, alertas de seguridad e historial.

require __DIR__ . '/_cabecera.php';

if (empty($slError)):
    $rw = !empty($slPuede['configurar']);
    $abiertas = array_filter($alertas ?? [], fn($a) => $a['estado'] === 'abierta');
?>

<div class="sl-grid sl-grid-kpi">
    <div class="sl-card sl-kpi is-danger">
        <div class="sl-kpi-label">IPs bloqueadas <i class="ph ph-prohibit"></i></div>
        <div class="sl-kpi-value"><?= count($bloqueos ?? []) ?></div>
        <div class="sl-kpi-foot">Bloqueos vigentes</div>
    </div>
    <div class="sl-card sl-kpi is-warn">
        <div class="sl-kpi-label">Alertas abiertas <i class="ph ph-siren"></i></div>
        <div class="sl-kpi-value"><?= count($abiertas) ?></div>
        <div class="sl-kpi-foot"><?= count($alertas ?? []) ?> en el histórico reciente</div>
    </div>
    <div class="sl-card sl-kpi">
        <div class="sl-kpi-label">Umbral ráfaga <i class="ph ph-lightning"></i></div>
        <div class="sl-kpi-value"><?= (int) $slPol['umbral_denegaciones'] ?></div>
        <div class="sl-kpi-foot">denegaciones en <?= (int) $slPol['ventana_minutos'] ?> min</div>
    </div>
    <div class="sl-card sl-kpi is-info">
        <div class="sl-kpi-label">Auto-bloqueo <i class="ph ph-robot"></i></div>
        <div class="sl-kpi-value"><?= !empty($slPol['auto_bloqueo_ip']) ? 'ON' : 'OFF' ?></div>
        <div class="sl-kpi-foot"><?= (int) $slPol['duracion_bloqueo_min'] ?> min por incidente</div>
    </div>
</div>

<div class="sl-grid sl-grid-main sl-mt">
    <!-- Bloqueos activos -->
    <div class="sl-card is-danger">
        <div class="sl-card-head"><span class="sl-card-title"><i class="ph ph-wall"></i>Firewall · bloqueos activos</span></div>
        <?php if (empty($bloqueos)): ?>
            <div class="sl-empty"><i class="ph ph-shield-check"></i>Ninguna IP bloqueada en este momento.</div>
        <?php else: ?>
            <div class="sl-table-wrap">
                <table class="sl-table">
                    <thead><tr><th>#</th><th>IP</th><th>Origen</th><th>Desde</th><th>Expira</th><th>Intentos</th><th>Motivo</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($bloqueos as $b): ?>
                        <tr>
                            <td class="sl-dim">#<?= (int) $b['id'] ?></td>
                            <td><span class="sl-badge sl-tag is-violet"><?= sl_e($b['ip']) ?></span></td>
                            <td><span class="sl-badge sl-tag <?= $b['origen'] === 'auto' ? 'is-red' : 'is-amber' ?>"><?= sl_e(strtoupper($b['origen'])) ?></span></td>
                            <td class="sl-nowrap sl-dim"><?= sl_e($b['creado_en']) ?></td>
                            <td class="sl-nowrap"><?= $b['expira_en'] ? sl_e($b['expira_en']) : '<b style="color:var(--sl-red)">PERMANENTE</b>' ?></td>
                            <td><?= (int) $b['intentos'] ?></td>
                            <td class="sl-trunc sl-dim" title="<?= sl_e($b['motivo']) ?>"><?= sl_e($b['motivo']) ?><div class="sl-help">por <?= sl_e($b['creado_por']) ?></div></td>
                            <td><?php if ($rw): ?><button type="button" class="sl-btn sl-btn-sm" data-sl-desbloquear="<?= (int) $b['id'] ?>" data-ip="<?= sl_e($b['ip']) ?>"><i class="ph ph-lock-open"></i>Levantar</button><?php endif; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Nuevo bloqueo -->
    <div class="sl-card">
        <div class="sl-card-head"><span class="sl-card-title"><i class="ph ph-prohibit-inset"></i>Bloquear IP</span></div>
        <?php if (!$rw): ?>
            <div class="sl-empty"><i class="ph ph-lock"></i>Requiere permiso «Configurar».</div>
        <?php else: ?>
            <form class="sl-form" id="slFormBloqueo" autocomplete="off">
                <div class="sl-field">
                    <label>Dirección IP (v4 / v6)</label>
                    <div class="sl-input-prompt"><input class="sl-input" name="ip" required placeholder="203.0.113.45"></div>
                    <span class="sl-help">Tu IP actual (<?= sl_e($slCtx['ip']) ?>) está protegida contra auto-bloqueo.</span>
                </div>
                <div class="sl-field">
                    <label>Duración</label>
                    <select class="sl-select" name="minutos">
                        <option value="15">15 minutos</option>
                        <option value="60" selected>1 hora</option>
                        <option value="360">6 horas</option>
                        <option value="1440">24 horas</option>
                        <option value="10080">7 días</option>
                        <option value="0">Permanente</option>
                    </select>
                </div>
                <div class="sl-field">
                    <label>Motivo</label>
                    <textarea class="sl-textarea" name="motivo" required maxlength="500" placeholder="Escaneo de rutas, fuerza bruta…"></textarea>
                </div>
                <button type="submit" class="sl-btn sl-btn-danger"><i class="ph ph-prohibit"></i>Aplicar bloqueo</button>
            </form>
        <?php endif; ?>

        <?php if (!empty($topIps)): ?>
            <div class="sl-mt">
                <div class="sl-label">IPs con más denegaciones (24 h)</div>
                <div class="sl-hbars sl-mt-sm">
                    <?php $maxIp = max(1, ...array_map(fn($t) => (int) $t['total'], $topIps)); ?>
                    <?php foreach ($topIps as $t): ?>
                        <div>
                            <div class="sl-hbar-top">
                                <span><?= sl_e($t['ip']) ?></span>
                                <span>
                                    <?= (int) $t['total'] ?> ✕
                                    <?php if ($rw && $t['ip'] !== $slCtx['ip']): ?>
                                        · <a href="#" data-sl-prefill-ip="<?= sl_e($t['ip']) ?>">bloquear</a>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="sl-hbar"><i class="ko" style="width:<?= round($t['total'] * 100 / $maxIp, 1) ?>%"></i></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Alertas -->
<div class="sl-card sl-mt">
    <div class="sl-card-head">
        <span class="sl-card-title"><i class="ph ph-siren"></i>Alertas de seguridad</span>
        <?php if ($rw && $abiertas): ?>
            <button type="button" class="sl-btn sl-btn-sm" data-sl-atender="todas"><i class="ph ph-checks"></i>Atender todas (<?= count($abiertas) ?>)</button>
        <?php endif; ?>
    </div>
    <?php if (empty($alertas)): ?>
        <div class="sl-empty"><i class="ph ph-bell-slash"></i>No se han generado alertas.</div>
    <?php else: ?>
        <div class="sl-table-wrap" style="max-height:480px">
            <table class="sl-table">
                <thead><tr><th>#</th><th>Fecha</th><th>Sev.</th><th>Tipo</th><th>Alerta</th><th>IP</th><th>Estado</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($alertas as $a): $ab = $a['estado'] === 'abierta'; ?>
                    <tr class="<?= $ab ? '' : 'is-muted' ?>">
                        <td class="sl-dim">#<?= (int) $a['id'] ?></td>
                        <td class="sl-nowrap"><?= sl_e($a['fecha']) ?></td>
                        <td><?= sl_sev($a['severidad']) ?></td>
                        <td><span class="sl-badge sl-tag"><?= sl_e($a['tipo']) ?></span></td>
                        <td><b><?= sl_e($a['titulo']) ?></b><div class="sl-dim"><?= sl_e($a['detalle']) ?></div></td>
                        <td class="sl-nowrap"><?= $a['ip'] ? '<a href="seguridad-lineas-bitacora?ip=' . urlencode($a['ip']) . '">' . sl_e($a['ip']) . '</a>' : '—' ?></td>
                        <td class="sl-nowrap">
                            <?php if ($ab): ?>
                                <span class="sl-badge sl-res-denegado">ABIERTA</span>
                            <?php else: ?>
                                <span class="sl-badge sl-tag">ATENDIDA</span>
                                <div class="sl-help"><?= sl_e($a['atendida_por']) ?> · <?= sl_e($a['atendida_en']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td><?php if ($rw && $ab): ?><button type="button" class="sl-btn sl-btn-sm" data-sl-atender="<?= (int) $a['id'] ?>"><i class="ph ph-check"></i></button><?php endif; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Historial -->
<div class="sl-card sl-mt">
    <div class="sl-card-head"><span class="sl-card-title"><i class="ph ph-clock-counter-clockwise"></i>Historial de bloqueos</span></div>
    <?php if (empty($historial)): ?>
        <div class="sl-empty"><i class="ph ph-archive"></i>Sin bloqueos finalizados.</div>
    <?php else: ?>
        <div class="sl-table-wrap">
            <table class="sl-table">
                <thead><tr><th>#</th><th>IP</th><th>Origen</th><th>Creado</th><th>Expiraba</th><th>Finalizado</th><th>Motivo</th></tr></thead>
                <tbody>
                <?php foreach ($historial as $h): ?>
                    <tr>
                        <td class="sl-dim">#<?= (int) $h['id'] ?></td>
                        <td><?= sl_e($h['ip']) ?></td>
                        <td><span class="sl-badge sl-tag"><?= sl_e(strtoupper($h['origen'])) ?></span></td>
                        <td class="sl-nowrap sl-dim"><?= sl_e($h['creado_en']) ?></td>
                        <td class="sl-nowrap sl-dim"><?= sl_e($h['expira_en'] ?: '∞') ?></td>
                        <td class="sl-nowrap"><?= $h['levantado_en'] ? sl_e($h['levantado_en']) . '<div class="sl-help">' . sl_e($h['levantado_por']) . '</div>' : '<span class="sl-dim">expiró</span>' ?></td>
                        <td class="sl-trunc sl-dim" title="<?= sl_e($h['motivo']) ?>"><?= sl_e($h['motivo']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php endif; ?>

<?php require __DIR__ . '/_pie.php'; ?>
