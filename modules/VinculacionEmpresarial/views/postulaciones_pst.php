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
    'Pendiente' => ['#fef9c3', '#854d0e', '#facc15', 'ph-hourglass-medium', 'Pendiente de revisión'],
    'Aprobado'  => ['#dcfce7', '#166534', '#22c55e', 'ph-seal-check', 'Avalada por el profesor'],
    'Devuelto'  => ['#fff1f2', '#9f1239', '#e11d48', 'ph-arrow-u-up-left', 'Devuelta al estudiante'],
];
$badgeEstado = [
    'Pendiente' => ['#f4f7fb', '#505984', 'En espera del Comité'],
    'Aceptado'  => ['#505984', '#ffffff', 'Asignada por el Comité'],
    'Rechazado' => ['#f1f5f9', '#94a3b8', 'Rechazada por el Comité'],
];
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
.pst-wrapper { padding: 2rem; width: 100%; margin: 0 auto; font-family: 'Inter', 'Segoe UI', sans-serif; }
.pst-header { display: flex; justify-content: space-between; align-items: flex-end; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 1rem; }
.pst-header h2 { color: #121a3e; margin: 0; font-size: 1.8rem; display: flex; align-items: center; gap: .5rem; }
.pst-header p { color: #64748b; margin: .5rem 0 0 0; }
.pst-kpis { display: flex; gap: .6rem; flex-wrap: wrap; }
.pst-kpi { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: .6rem 1rem; box-shadow: 0 2px 4px rgba(0,0,0,.02); text-align: center; min-width: 100px; }
.pst-kpi strong { display: block; color: #121a3e; font-size: 1.3rem; }
.pst-kpi span { color: #64748b; font-size: .75rem; text-transform: uppercase; font-weight: 700; letter-spacing: .04em; }
.pst-toggle { display: inline-flex; border: 1px solid #cbd5e1; border-radius: 8px; overflow: hidden; margin-bottom: 1rem; }
.pst-toggle a { padding: .55rem 1.1rem; text-decoration: none; color: #505984; font-weight: 600; font-size: .9rem; background: #fff; }
.pst-toggle a.active { background: #505984; color: #fff; }
.pst-filtros { display: flex; gap: .8rem; flex-wrap: wrap; align-items: flex-end; background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1rem; margin-bottom: 1.2rem; }
.pst-filtros label { font-size: .75rem; color: #94a3b8; font-weight: 700; display: block; margin-bottom: 4px; text-transform: uppercase; }
.pst-filtros select { padding: 8px 10px; border-radius: 8px; border: 1px solid #cbd5e1; background: #f8fafc; color: #121a3e; min-width: 150px; font-size: .9rem; }
.pst-btn { border: none; border-radius: 6px; padding: .6rem 1rem; font-weight: 700; cursor: pointer; font-size: .85rem; display: inline-flex; align-items: center; gap: 5px; text-decoration: none; }
.pst-btn-primary { background: #505984; color: #fff; }
.pst-btn-primary:hover { background: #3c456a; }
.pst-btn-outline { background: #fff; color: #505984; border: 1px solid #7090cb; }
.pst-btn-danger { background: #fff; color: #e11d48; border: 1px solid #fda4af; }
.pst-table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
.pst-table th { color: #64748b; font-weight: 600; padding: 0 1rem .5rem; text-align: left; border-bottom: 2px solid #e2e8f0; font-size: .85rem; }
.pst-table td { background: #fff; padding: 1rem; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; vertical-align: top; font-size: .9rem; }
.pst-table td:first-child { border-left: 1px solid #e2e8f0; border-radius: 8px 0 0 8px; }
.pst-table td:last-child { border-right: 1px solid #e2e8f0; border-radius: 0 8px 8px 0; }
.pst-chip { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 12px; font-size: .75rem; font-weight: 700; border: 1px solid transparent; }
.pst-muted { color: #64748b; font-size: .82rem; }
.pst-perfil { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1rem 1.2rem; margin-bottom: 1.5rem; }
.pst-perfil summary { cursor: pointer; font-weight: 700; color: #121a3e; display: flex; align-items: center; gap: .5rem; }
</style>

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
                                    <span class="pst-chip" style="background:#dcfce7; color:#166534; border-color:#86efac;" title="El profesor elegido dicta PST en este trayecto/trimestre"><i class="ph-bold ph-check-circle"></i> Dentro de los trayectos del profesor</span>
                                <?php else: ?>
                                    <span class="pst-chip" style="background:#fef9c3; color:#854d0e; border-color:#facc15;" title="El profesor elegido no tiene registrado este trayecto/trimestre"><i class="ph-bold ph-warning"></i> Fuera de los trayectos del profesor</span>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if (!$p['coincide_oferta']): ?>
                                <span class="pst-chip" style="background:#fff1f2; color:#9f1239; border-color:#fda4af;" title="La oferta requiere otro trayecto"><i class="ph-bold ph-warning-circle"></i> Difiere del nivel de la oferta</span>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <span class="pst-muted">(Postulación anterior sin trayecto/trimestre)</span>
                    <?php endif; ?>
                </td>
                <?php if ($ctx['esGestor'] && $vistaTodas): ?>
                <td>
                    <?php if ($p['profesor_nombre']): ?>
                        <strong style="color:#1e293b;"><?= htmlspecialchars($p['profesor_nombre']) ?></strong><br>
                        <span class="pst-muted"><?= htmlspecialchars($p['profesor_email'] ?? '') ?></span>
                    <?php else: ?>
                        <span class="pst-muted">Sin profesor asignado</span>
                    <?php endif; ?>
                </td>
                <?php endif; ?>
                <td>
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <span class="pst-chip" style="background:<?= $ba[0] ?>; color:<?= $ba[1] ?>; border-color:<?= $ba[2] ?>;"><i class="ph-bold <?= $ba[3] ?>"></i> Profesor: <?= $ba[4] ?></span>
                        <?php if (!empty($p['motivo_profesor'])): ?>
                            <span class="pst-muted" style="font-style:italic;">"<?= nl2br(htmlspecialchars($p['motivo_profesor'])) ?>"</span>
                        <?php endif; ?>
                        <span class="pst-chip" style="background:<?= $be[0] ?>; color:<?= $be[1] ?>; border-color:#505984;"><i class="ph-bold ph-users-three"></i> <?= $be[2] ?></span>
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
            : '<b style="color:#854d0e;">Atención:</b> el trayecto/trimestre declarado por el estudiante no figura entre los que usted dicta. ¿Desea avalarla de todas formas?',
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
