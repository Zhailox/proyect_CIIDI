<?php
// modules/VinculacionEmpresarial/views/postulaciones_pst.php
// Panel de Postulaciones PST. Datos provienen de ProfesorPSTController::panel()
require_once CORE_PATH . 'Security/CSRF.php';
require_once __DIR__ . '/../../Autenticacion/services/PerfilPSTService.php';

$ctx           = $ctx ?? ['esGestor' => false, 'esProfesor' => false, 'esAdmin' => false, 'id' => 0];
$postulaciones = $postulaciones ?? [];
$filtros       = $filtros ?? [];
$catTrayectos  = $catTrayectos ?? [];
$catTrimestres = $catTrimestres ?? [];
$csrfCampo     = CSRF::campoOculto();

// Query actual (para volver al mismo filtro tras una acción)
$queryActual = http_build_query(array_filter([
    'vista'      => ($ctx['esGestor'] && !$vistaTodas) ? 'mias' : null,
    'trayecto'   => $filtros['trayecto'] ?? null,
    'trimestre'  => $filtros['trimestre'] ?? null,
    'aprobacion' => $filtros['aprobacion'] ?? null,
    'estado'     => $filtros['estado'] ?? null,
    'profesor'   => $filtros['profesor'] ?? null,
]));

$badgeAprob = [
    'Pendiente' => ['ve-chip-pending', 'ph-hourglass-medium', 'Pendiente de revisión'],
    'Aprobado'  => ['ve-chip-ok', 'ph-seal-check', 'Avalada por el profesor'],
    'Devuelto'  => ['ve-chip-err', 'ph-arrow-u-up-left', 'Devuelta al estudiante'],
];
$badgeEstado = [
    'Pendiente' => ['ve-chip-est-pendiente', 'En espera del Comité'],
    'Aceptado'  => ['ve-chip-est-aceptado', 'Asignada por el Comité'],
    'Rechazado' => ['ve-chip-est-rechazado', 'Rechazada por el Comité'],
];
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="pst-wrapper">
    <div class="pst-header">
        <div>
            <h2><i class="ph-bold ph-chalkboard-teacher"></i> Postulaciones PST</h2>
            <p>
                <?php if ($ctx['esGestor'] && $vistaTodas): ?>
                    Vista global del Comité: postulaciones estudiantiles con su trayecto, trimestre, profesor PST elegido y la decisión del profesor.
                <?php else: ?>
                    Postulaciones de estudiantes que le eligieron como profesor PST. Verifique que pertenezcan a su trayecto/trimestre y avale o devuelva cada solicitud.
                <?php endif; ?>
            </p>
        </div>
        <div class="pst-kpis">
            <div class="pst-kpi"><strong><?= count($postulaciones) ?></strong><span>Total</span></div>
            <div class="pst-kpi"><strong><?= (int)($kpis['Pendiente'] ?? 0) ?></strong><span>Pendientes</span></div>
            <div class="pst-kpi"><strong><?= (int)($kpis['Aprobado'] ?? 0) ?></strong><span>Avaladas</span></div>
            <div class="pst-kpi"><strong><?= (int)($kpis['Devuelto'] ?? 0) ?></strong><span>Devueltas</span></div>
        </div>
    </div>

    <?php if ($ctx['esProfesor']): ?>
    <details class="pst-perfil" <?= (empty($miSeleccionPST) || !$miEsProfesorPST) ? 'open' : '' ?>>
        <summary><i class="ph-bold ph-identification-badge" style="color:#7090cb;"></i> Mi perfil de Profesor PST
            <span class="pst-muted" style="font-weight: 500;">
                <?php if ($miEsProfesorPST && !empty($miSeleccionPST)): ?>
                    — <?= count($miSeleccionPST) ?> trayecto(s)/trimestre(s) registrados
                <?php else: ?>
                    — Aún no figura como profesor PST: los estudiantes no podrán elegirle.
                <?php endif; ?>
            </span>
        </summary>
        <form action="?ruta=guardar-mi-perfil-pst" method="POST" style="margin-top: 1rem;">
            <?= $csrfCampo ?>
            <?php
            $pstIdPrefijo = 'mipst';
            $pstEsProfesor = $miEsProfesorPST;
            $pstSeleccion = $miSeleccionPST;
            include __DIR__ . '/../../Autenticacion/views/partials/selector_pst.php';
            ?>
            <button type="submit" class="pst-btn pst-btn-primary"><i class="ph-bold ph-floppy-disk"></i> Guardar mi perfil PST</button>
        </form>
    </details>
    <?php endif; ?>

    <?php if ($ctx['esGestor']): ?>
    <div class="pst-toggle">
        <a href="?ruta=postulaciones-pst" class="<?= $vistaTodas ? 'active' : '' ?>"><i class="ph-bold ph-globe"></i> Todas las postulaciones</a>
        <a href="?ruta=postulaciones-pst&vista=mias" class="<?= !$vistaTodas ? 'active' : '' ?>"><i class="ph-bold ph-user"></i> Dirigidas a mí</a>
    </div>
    <?php endif; ?>

    <form method="GET" class="pst-filtros">
        <input type="hidden" name="ruta" value="postulaciones-pst">
        <?php if ($ctx['esGestor'] && !$vistaTodas): ?><input type="hidden" name="vista" value="mias"><?php endif; ?>
        <div>
            <label>Trayecto</label>
            <select name="trayecto">
                <option value="">Todos</option>
                <?php foreach ($catTrayectos as $n => $nom): ?>
                    <option value="<?= (int)$n ?>" <?= ($filtros['trayecto'] ?? null) === (int)$n ? 'selected' : '' ?>><?= htmlspecialchars($nom) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Trimestre</label>
            <select name="trimestre">
                <option value="">Todos</option>
                <?php foreach ($catTrimestres as $n => $nom): ?>
                    <option value="<?= (int)$n ?>" <?= ($filtros['trimestre'] ?? null) === (int)$n ? 'selected' : '' ?>><?= htmlspecialchars($nom) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Decisión del profesor</label>
            <select name="aprobacion">
                <option value="">Todas</option>
                <?php foreach (['Pendiente' => 'Pendiente', 'Aprobado' => 'Avalada', 'Devuelto' => 'Devuelta'] as $v => $t): ?>
                    <option value="<?= $v ?>" <?= ($filtros['aprobacion'] ?? null) === $v ? 'selected' : '' ?>><?= $t ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Decisión del Comité</label>
            <select name="estado">
                <option value="">Todas</option>
                <?php foreach (['Pendiente' => 'En espera', 'Aceptado' => 'Asignada', 'Rechazado' => 'Rechazada'] as $v => $t): ?>
                    <option value="<?= $v ?>" <?= ($filtros['estado'] ?? null) === $v ? 'selected' : '' ?>><?= $t ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php if ($ctx['esGestor'] && $vistaTodas): ?>
        <div>
            <label>Profesor PST</label>
            <select name="profesor">
                <option value="">Todos</option>
                <?php foreach ($profesoresPST as $pr): ?>
                    <option value="<?= (int)$pr['id'] ?>" <?= ($filtros['profesor'] ?? null) === (int)$pr['id'] ? 'selected' : '' ?>><?= htmlspecialchars($pr['nombre_completo']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
        <div style="display:flex; gap:.5rem;">
            <button type="submit" class="pst-btn pst-btn-primary"><i class="ph-bold ph-funnel"></i> Filtrar</button>
            <a href="?ruta=postulaciones-pst<?= ($ctx['esGestor'] && !$vistaTodas) ? '&vista=mias' : '' ?>" class="pst-btn pst-btn-outline">Limpiar</a>
        </div>
    </form>

    <?php if (empty($postulaciones)): ?>
        <div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 12px; border: 1px dashed #cbd5e1;">
            <i class="ph-fill ph-tray" style="font-size: 3.5rem; color: #7090cb;"></i>
            <h3 style="color: #1e293b;">Sin postulaciones</h3>
            <p style="color: #64748b;">No hay postulaciones que coincidan con los filtros seleccionados.</p>
        </div>
    <?php else: ?>
    <table class="pst-table">
        <thead>
            <tr>
                <th style="width: 22%;">Estudiante (Líder)</th>
                <th style="width: 22%;">Oferta / Reto</th>
                <th style="width: 18%;">Trayecto / Trimestre</th>
                <?php if ($ctx['esGestor'] && $vistaTodas): ?><th style="width: 13%;">Profesor PST</th><?php endif; ?>
                <th style="width: 15%;">Estado</th>
                <th style="width: 10%;">Acción</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($postulaciones as $p):
            $equipo = !empty($p['equipo_extra']) ? (json_decode($p['equipo_extra'], true) ?: []) : [];
            $ba = $badgeAprob[$p['aprobacion_profesor']] ?? $badgeAprob['Pendiente'];
            $be = $badgeEstado[$p['estado']] ?? $badgeEstado['Pendiente'];
            $puedeDecidir = $p['estado'] === 'Pendiente' && ((int)$p['id_profesor_pst'] === (int)$ctx['id'] || $ctx['esAdmin']);
        ?>
            <tr>
                <td>
                    <strong style="color:#1e293b;"><i class="ph-fill ph-star" style="color:#7090cb;"></i> <?= htmlspecialchars($p['estudiante']) ?></strong><br>
                    <span class="pst-muted">C.I: <?= htmlspecialchars($p['cedula'] ?? 'N/A') ?></span><br>
                    <span class="pst-muted"><?= htmlspecialchars($p['correo'] ?? '') ?></span>
                    <?php if (!empty($equipo)): ?>
                        <div class="pst-muted" style="margin-top:6px;"><i class="ph-bold ph-users-three"></i> +<?= count($equipo) ?> compañero(s):
                            <?= htmlspecialchars(implode(', ', array_map(fn($m) => ($m['nombre'] ?? '') . ' (' . ($m['cedula'] ?? '') . ')', $equipo))) ?>
                        </div>
                    <?php endif; ?>
                    <div class="pst-muted" style="margin-top:6px;"><i class="ph-bold ph-calendar"></i> <?= date('d/m/Y', strtotime($p['fecha_postulacion'])) ?></div>
                </td>
                <td>
                    <span class="pst-chip" style="background:#f4f7fb; color:#505984; border-color:#7090cb;"><i class="ph-bold ph-buildings"></i> <?= htmlspecialchars($p['nombre_empresa'] ?? 'Investigación interna') ?></span>
                    <div style="font-weight:600; color:#121a3e; margin-top:6px;"><?= htmlspecialchars($p['titulo']) ?></div>
                    <?php if (!empty($p['nivel_trayecto'])): ?>
                        <div class="pst-muted" style="margin-top:4px;"><i class="ph-bold ph-graduation-cap"></i> Nivel requerido: <?= htmlspecialchars($p['nivel_trayecto']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty(trim($p['mensaje_motivacion'] ?? ''))): ?>
                        <div class="pst-muted" style="margin-top:6px; font-style:italic; max-height:80px; overflow:auto;">"<?= nl2br(htmlspecialchars($p['mensaje_motivacion'])) ?>"</div>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($p['trayecto']): ?>
                        <div style="font-weight:700; color:#121a3e;"><?= htmlspecialchars(PerfilPSTService::nombreTrayecto((int)$p['trayecto'])) ?></div>
                        <div class="pst-muted">Trimestre <?= (int)$p['trimestre'] ?></div>
                        <div style="margin-top:6px; display:flex; flex-direction:column; gap:4px;">
                            <?php if ($p['id_profesor_pst']): ?>
                                <?php if ($p['coincide_profesor']): ?>
                                    <span class="pst-chip ve-chip-ok" title="El profesor elegido dicta PST en este trayecto/trimestre"><i class="ph-bold ph-check-circle"></i> Dentro de los trayectos del profesor</span>
                                <?php else: ?>
                                    <span class="pst-chip ve-chip-pending" title="El profesor elegido no tiene registrado este trayecto/trimestre"><i class="ph-bold ph-warning"></i> Fuera de los trayectos del profesor</span>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if (!$p['coincide_oferta']): ?>
                                <span class="pst-chip ve-chip-err" title="La oferta requiere otro trayecto"><i class="ph-bold ph-warning-circle"></i> Difiere del nivel de la oferta</span>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <span class="pst-muted">(Postulación anterior sin trayecto/trimestre)</span>
                    <?php endif; ?>
                </td>
                <?php if ($ctx['esGestor'] && $vistaTodas): ?>
                <td>
                    <?php if ($p['profesor_nombre']): ?>
                        <strong><?= htmlspecialchars($p['profesor_nombre']) ?></strong><br>
                        <span class="pst-muted"><?= htmlspecialchars($p['profesor_email'] ?? '') ?></span>
                    <?php else: ?>
                        <span class="pst-muted">Sin profesor asignado</span>
                    <?php endif; ?>
                </td>
                <?php endif; ?>
                <td>
                    <div class="pst-col-stack">
                        <span class="pst-chip <?= $ba[0] ?>"><i class="ph-bold <?= $ba[1] ?>"></i> Profesor: <?= $ba[2] ?></span>
                        <?php if (!empty($p['motivo_profesor'])): ?>
                            <span class="pst-muted">"<?= nl2br(htmlspecialchars($p['motivo_profesor'])) ?>"</span>
                        <?php endif; ?>
                        <span class="pst-chip <?= $be[0] ?>"><i class="ph-bold ph-users-three"></i> <?= $be[1] ?></span>
                    </div>
                </td>
                <td>
                    <?php if ($puedeDecidir): ?>
                        <form action="?ruta=revisar-postulacion-pst" method="POST" id="form-pst-<?= (int)$p['id_postulacion'] ?>" style="display:flex; flex-direction:column; gap:6px;">
                            <?= $csrfCampo ?>
                            <input type="hidden" name="id_postulacion" value="<?= (int)$p['id_postulacion'] ?>">
                            <input type="hidden" name="volver_query" value="<?= htmlspecialchars($queryActual) ?>">
                            <input type="hidden" name="decision" value="">
                            <input type="hidden" name="motivo" value="">
                            <?php if ($p['aprobacion_profesor'] !== 'Aprobado'): ?>
                                <button type="button" class="pst-btn pst-btn-primary" onclick="pstDecidir(<?= (int)$p['id_postulacion'] ?>, 'Aprobado', <?= $p['coincide_profesor'] ? 'true' : 'false' ?>)"><i class="ph-bold ph-check"></i> Avalar</button>
                            <?php endif; ?>
                            <?php if ($p['aprobacion_profesor'] !== 'Devuelto'): ?>
                                <button type="button" class="pst-btn pst-btn-danger" onclick="pstDecidir(<?= (int)$p['id_postulacion'] ?>, 'Devuelto', true)"><i class="ph-bold ph-arrow-u-up-left"></i> Devolver</button>
                            <?php endif; ?>
                        </form>
                    <?php elseif ($p['estado'] !== 'Pendiente'): ?>
                        <span class="pst-muted">Decisión final del Comité emitida.</span>
                    <?php else: ?>
                        <span class="pst-muted">Solo el profesor elegido puede revisarla.</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<script>
function pstDecidir(id, decision, coincide) {
    const form = document.getElementById('form-pst-' + id);
    if (!form) return;
    if (decision === 'Devuelto') {
        Swal.fire({
            title: '¿Devolver postulación?',
            text: 'Indique el motivo para que el estudiante pueda corregir y reenviar su postulación:',
            input: 'textarea',
            inputPlaceholder: 'Ej: No perteneces a mi sección de Trayecto II - Trimestre 1.',
            icon: 'warning',
            iconColor: '#e11d48',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Sí, devolver',
            cancelButtonText: 'Cancelar',
            preConfirm: (motivo) => {
                if (!motivo || motivo.trim() === '') {
                    Swal.showValidationMessage('El motivo de la devolución es obligatorio');
                }
                return motivo;
            }
        }).then((r) => {
            if (r.isConfirmed) {
                form.querySelector('[name="decision"]').value = 'Devuelto';
                form.querySelector('[name="motivo"]').value = r.value;
                form.submit();
            }
        });
        return;
    }
    Swal.fire({
        title: '¿Avalar esta postulación?',
        html: coincide
            ? 'Confirma que el estudiante pertenece a su trayecto/trimestre. El Comité realizará la asignación final.'
            : '<b class="ve-swal-attn">Atención:</b> el trayecto/trimestre declarado por el estudiante no figura entre los que usted dicta. ¿Desea avalarla de todas formas?',
        icon: coincide ? 'question' : 'warning',
        iconColor: '#505984',
        showCancelButton: true,
        confirmButtonColor: '#505984',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Sí, avalar',
        cancelButtonText: 'Cancelar'
    }).then((r) => {
        if (r.isConfirmed) {
            form.querySelector('[name="decision"]').value = 'Aprobado';
            form.submit();
        }
    });
}
</script>

<?php if (isset($_SESSION['flash_success'])): ?>
<script>Swal.fire({ icon: 'success', title: '¡Éxito!', text: <?= json_encode($_SESSION['flash_success'], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?>, confirmButtonColor: '#121a3e' });</script>
<?php unset($_SESSION['flash_success']); endif; ?>
<?php if (isset($_SESSION['flash_error'])): ?>
<script>Swal.fire({ icon: 'error', title: 'Atención', text: <?= json_encode($_SESSION['flash_error'], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?>, confirmButtonColor: '#121a3e' });</script>
<?php unset($_SESSION['flash_error']); endif; ?>
