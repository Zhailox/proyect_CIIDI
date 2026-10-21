<?php
// modules/SeguridadLineas/views/bitacora.php
// Bitácora forense del macro módulo con filtros, paginación, exportación y verificación de la cadena.

require __DIR__ . '/_cabecera.php';

if (empty($slError)):
    $f      = $filtros ?? [];
    $pagina = (int) ($pagina ?? 1);
    $paginas = (int) ($paginas ?? 1);
    $desdeN = $total ? ($pagina - 1) * $porPagina + 1 : 0;
    $hastaN = min($total, $pagina * $porPagina);
    $accionesLog = array_merge(array_keys($slAcciones), ['csrf', 'verificar-cadena', 'auto-bloqueo', 'alerta', 'retencion']);
?>

<div class="sl-card">
    <div class="sl-card-head">
        <span class="sl-card-title"><i class="ph ph-funnel"></i>grep · filtros de búsqueda</span>
        <span class="sl-row">
            <?php if ($slPuede['exportar']): ?>
                <a class="sl-btn sl-btn-cyan sl-btn-sm" href="seguridad-lineas-exportar<?= sl_e(sl_qs($f)) ?>"><i class="ph ph-file-csv"></i>Exportar CSV</a>
                <button type="button" class="sl-btn sl-btn-sm" data-sl-verify><i class="ph ph-fingerprint"></i>Verificar cadena</button>
            <?php endif; ?>
        </span>
    </div>
    <form class="sl-form" method="get" action="seguridad-lineas-bitacora" autocomplete="off">
        <div class="sl-form-grid">
            <div class="sl-field" style="grid-column: span 2">
                <label for="slQ">Texto libre</label>
                <div class="sl-input-prompt"><input class="sl-input" id="slQ" name="q" value="<?= sl_e($f['q'] ?? '') ?>" placeholder="ruta, usuario, IP, detalle…"></div>
            </div>
            <div class="sl-field">
                <label>Severidad</label>
                <select class="sl-select" name="severidad">
                    <option value="">— todas —</option>
                    <?php foreach (SeguridadMotor::SEVERIDADES as $s): ?>
                        <option value="<?= $s ?>" <?= ($f['severidad'] ?? '') === $s ? 'selected' : '' ?>><?= $s ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="sl-field">
                <label>Resultado</label>
                <select class="sl-select" name="resultado">
                    <option value="">— todos —</option>
                    <?php foreach (SeguridadMotor::RESULTADOS as $r): ?>
                        <option value="<?= $r ?>" <?= ($f['resultado'] ?? '') === $r ? 'selected' : '' ?>><?= $r ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="sl-field">
                <label>Submódulo</label>
                <select class="sl-select" name="submodulo">
                    <option value="">— todos —</option>
                    <?php foreach ($slSubmodulos as $carpeta => $info): ?>
                        <option value="<?= sl_e($carpeta) ?>" <?= ($f['submodulo'] ?? '') === $carpeta ? 'selected' : '' ?>><?= sl_e($info['titulo']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="sl-field">
                <label>Acción</label>
                <select class="sl-select" name="accion">
                    <option value="">— todas —</option>
                    <?php foreach ($accionesLog as $a): ?>
                        <option value="<?= sl_e($a) ?>" <?= ($f['accion'] ?? '') === $a ? 'selected' : '' ?>><?= sl_e($slAcciones[$a]['etiqueta'] ?? $a) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="sl-field">
                <label>IP exacta</label>
                <input class="sl-input" name="ip" value="<?= sl_e($f['ip'] ?? '') ?>" placeholder="192.168.0.10">
            </div>
            <div class="sl-field">
                <label>ID usuario</label>
                <input class="sl-input" type="number" min="1" name="usuario_id" value="<?= sl_e($f['usuario_id'] ?? '') ?>">
            </div>
            <div class="sl-field">
                <label>Desde</label>
                <input class="sl-input" type="date" name="desde" value="<?= sl_e($f['desde'] ?? '') ?>">
            </div>
            <div class="sl-field">
                <label>Hasta</label>
                <input class="sl-input" type="date" name="hasta" value="<?= sl_e($f['hasta'] ?? '') ?>">
            </div>
        </div>
        <div class="sl-row">
            <button type="submit" class="sl-btn"><i class="ph ph-magnifying-glass"></i>Ejecutar consulta</button>
            <a class="sl-btn sl-btn-ghost" href="seguridad-lineas-bitacora"><i class="ph ph-eraser"></i>Limpiar</a>
            <span class="sl-help">
                <a href="seguridad-lineas-bitacora?resultado=DENEGADO">› solo denegados</a> ·
                <a href="seguridad-lineas-bitacora?severidad=CRITICO">› críticos</a> ·
                <a href="seguridad-lineas-bitacora?resultado=ADMIN">› acciones admin</a> ·
                <a href="seguridad-lineas-bitacora?desde=<?= date('Y-m-d') ?>">› hoy</a>
            </span>
        </div>
    </form>
    <div id="slVerifyOut" class="sl-mt-sm"></div>
</div>

<div class="sl-card sl-mt">
    <div class="sl-card-head">
        <span class="sl-card-title"><i class="ph ph-list-magnifying-glass"></i>Resultados</span>
        <span class="sl-help"><?= number_format((int) $total, 0, ',', '.') ?> evento(s) · mostrando <?= $desdeN ?>–<?= $hastaN ?></span>
    </div>
    <?php if (empty($logs)): ?>
        <div class="sl-empty"><i class="ph ph-file-dashed"></i>No hay eventos que coincidan con la consulta.</div>
    <?php else: ?>
        <div class="sl-table-wrap">
            <table class="sl-table">
                <thead>
                <tr><th>#</th><th>Fecha</th><th>Sev.</th><th>Resultado</th><th>Submódulo</th><th>Acción</th><th>Actor</th><th>IP</th><th>Ruta</th><th>Detalle</th></tr>
                </thead>
                <tbody>
                <?php foreach ($logs as $l): ?>
                    <tr class="is-click" data-sl-evento="<?= (int) $l['id'] ?>">
                        <td class="sl-dim">#<?= (int) $l['id'] ?></td>
                        <td class="sl-nowrap"><?= sl_e($l['fecha']) ?></td>
                        <td><?= sl_sev($l['severidad']) ?></td>
                        <td><?= sl_res($l['resultado']) ?></td>
                        <td class="sl-nowrap"><?= sl_e(sl_sub($l['submodulo'])) ?></td>
                        <td><span class="sl-badge sl-tag"><?= sl_e($l['accion']) ?></span></td>
                        <td class="sl-nowrap"><?= sl_e($l['usuario_nombre'] ?: 'anon') ?> <span class="sl-dim">· <?= sl_e(sl_nivel($l['nivel'])) ?></span></td>
                        <td class="sl-nowrap"><a href="seguridad-lineas-bitacora?ip=<?= urlencode((string) $l['ip']) ?>" onclick="event.stopPropagation()"><?= sl_e($l['ip']) ?></a></td>
                        <td class="sl-dim sl-nowrap"><?= sl_e($l['metodo']) ?> <?= sl_e($l['ruta']) ?></td>
                        <td class="sl-trunc sl-dim" title="<?= sl_e($l['detalles']) ?>"><?= sl_e($l['detalles']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($paginas > 1): ?>
            <nav class="sl-pager">
                <?php if ($pagina > 1): ?>
                    <a href="seguridad-lineas-bitacora<?= sl_e(sl_qs($f, ['p' => 1])) ?>">«</a>
                    <a href="seguridad-lineas-bitacora<?= sl_e(sl_qs($f, ['p' => $pagina - 1])) ?>">‹</a>
                <?php endif; ?>
                <?php for ($i = max(1, $pagina - 3); $i <= min($paginas, $pagina + 3); $i++): ?>
                    <?php if ($i === $pagina): ?>
                        <span class="is-current"><?= $i ?></span>
                    <?php else: ?>
                        <a href="seguridad-lineas-bitacora<?= sl_e(sl_qs($f, ['p' => $i])) ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                <?php if ($pagina < $paginas): ?>
                    <a href="seguridad-lineas-bitacora<?= sl_e(sl_qs($f, ['p' => $pagina + 1])) ?>">›</a>
                    <a href="seguridad-lineas-bitacora<?= sl_e(sl_qs($f, ['p' => $paginas])) ?>">»</a>
                <?php endif; ?>
                <span>pág. <?= $pagina ?>/<?= $paginas ?></span>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php endif; ?>

<?php require __DIR__ . '/_pie.php'; ?>
