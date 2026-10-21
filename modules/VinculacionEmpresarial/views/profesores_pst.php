<?php
// modules/VinculacionEmpresarial/views/profesores_pst.php
// Gestión de Profesores PST (Comité / Super Administrador). Datos: ProfesorPSTController::gestionProfesores()
require_once CORE_PATH . 'Security/CSRF.php';
require_once __DIR__ . '/../../Autenticacion/services/PerfilPSTService.php';

$docentes       = $docentes ?? [];
$pendientes     = $pendientes ?? [];
$solicitudesPst = $solicitudesPst ?? [];
$csrfCampo      = CSRF::campoOculto();

$fmtTrayectos = function (array $trayectos): string {
    if (empty($trayectos)) return 'Sin trayectos registrados';
    $nombres = array_map(fn($t) => PerfilPSTService::nombreTrayecto((int)$t), $trayectos);
    return implode(', ', $nombres);
};
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
.ppst-wrapper { padding: 2rem; width: 100%; margin: 0 auto; font-family: 'Inter', 'Segoe UI', sans-serif; }
.ppst-header { border-bottom: 2px solid #e2e8f0; padding-bottom: 1rem; margin-bottom: 1.5rem; }
.ppst-header h2 { color: #121a3e; margin: 0; font-size: 1.8rem; display: flex; align-items: center; gap: .5rem; }
.ppst-header p { color: #64748b; margin: .5rem 0 0 0; }
.ppst-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1.2rem; margin-bottom: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,.02); }
.ppst-card h3 { margin: 0 0 1rem 0; color: #121a3e; font-size: 1.1rem; display: flex; align-items: center; gap: .5rem; }
.ppst-table { width: 100%; border-collapse: collapse; font-size: .9rem; }
.ppst-table th { text-align: left; color: #64748b; font-weight: 600; padding: .6rem; border-bottom: 2px solid #e2e8f0; }
.ppst-table td { padding: .7rem .6rem; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
.ppst-chip { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 12px; font-size: .75rem; font-weight: 700; border: 1px solid transparent; }
.ppst-btn { border: none; border-radius: 6px; padding: .5rem .9rem; font-weight: 700; cursor: pointer; font-size: .82rem; display: inline-flex; align-items: center; gap: 5px; }
.ppst-btn-primary { background: #505984; color: #fff; }
.ppst-btn-primary:hover { background: #3c456a; }
.ppst-btn-outline { background: #fff; color: #505984; border: 1px solid #7090cb; }
.ppst-btn-danger { background: #fff; color: #e11d48; border: 1px solid #fda4af; }
.ppst-muted { color: #64748b; font-size: .82rem; }
.ppst-search { padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; background: #f8fafc; min-width: 280px; }
.gp-main-tabs { display: flex; gap: 2rem; border-bottom: 2px solid #e2e8f0; margin-bottom: 1.5rem; }
.gp-main-tab { padding: 1rem 0; font-weight: 600; color: #94a3b8; text-decoration: none; border-bottom: 3px solid transparent; transition: all 0.2s; font-size: 1.05rem; display: flex; align-items: center; gap: 0.5rem; margin-bottom: -2px; }
.gp-main-tab:hover { color: #505984; border-bottom-color: #cbd5e1; }
.gp-main-tab.active { color: #505984; border-bottom-color: #505984; }
.gp-title { color: #121a3e; margin: 0; font-size: 1.8rem; display: flex; align-items: center; gap: 0.5rem; }
.gp-subtitle { color: #64748b; margin: 0.5rem 0 1.5rem 0; font-size: 0.95rem; }
</style>

<div class="ppst-wrapper">
    <div class="gp-header">
        <h2 class="gp-title"><i class="ph-bold ph-briefcase"></i> Gestión de Solicitudes</h2>
        <p class="gp-subtitle">Panel unificado para evaluar las propuestas de las empresas, asignar los equipos de estudiantes y acreditar docentes PST.</p>
        
        <div class="gp-main-tabs">
            <a href="?ruta=gestion-proyectos&tab=propuestas" class="gp-main-tab">
                <i class="ph-bold ph-folder-open"></i> Evaluación de Propuestas Empresariales
            </a>
            <a href="?ruta=gestion-proyectos&tab=equipos" class="gp-main-tab">
                <i class="ph-bold ph-users-three"></i> Asignación de Equipo Estudiantil
            </a>
            <a href="?ruta=profesores-pst" class="gp-main-tab active">
                <i class="ph-bold ph-chalkboard-teacher"></i> Acreditación Docentes PST
            </a>
        </div>
    </div>

    <!-- 1. SOLICITUDES DE ACREDITACIÓN DOCENTE PST -->
    <div class="ppst-card" style="border-left: 4px solid #505984;">
        <h3>
            <i class="ph-bold ph-certificate" style="color:#505984;"></i> Solicitudes de Acreditación PST pendientes
            <span class="ppst-chip" style="background:#f4f7fb; color:#505984; border-color:#7090cb;"><?= count($solicitudesPst) ?></span>
        </h3>
        <p class="ppst-muted" style="margin-top:-6px; margin-bottom:12px;">
            Docentes que han solicitado acreditarse como tutores de Proyecto Sociotecnológico o actualizar los trayectos que imparten.
        </p>
        <?php if (empty($solicitudesPst)): ?>
            <p class="ppst-muted" style="margin:0;">No hay solicitudes de acreditación PST pendientes.</p>
        <?php else: ?>
        <table class="ppst-table">
            <thead>
                <tr>
                    <th>Docente</th>
                    <th>Trayecto(s) solicitados</th>
                    <th>Fecha de solicitud</th>
                    <th style="width: 220px;">Acción</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($solicitudesPst as $s): ?>
                <tr>
                    <td>
                        <strong style="color:#1e293b;"><?= htmlspecialchars($s['nombre_completo']) ?></strong><br>
                        <span class="ppst-muted">C.I: <?= htmlspecialchars($s['cedula'] ?? '') ?> • <?= htmlspecialchars($s['email'] ?? '') ?></span>
                    </td>
                    <td>
                        <?php if ($s['es_profesor_pst'] && !empty($s['trayectos'])): ?>
                            <span class="ppst-chip" style="background:#e0e7ff; color:#3730a3; border-color:#c7d2fe;">
                                <i class="ph-bold ph-chalkboard-teacher"></i> Docente PST
                            </span>
                            <div style="font-weight: 600; color: #1e293b; margin-top: 4px;">
                                <?= htmlspecialchars($fmtTrayectos($s['trayectos'])) ?>
                            </div>
                        <?php else: ?>
                            <span class="ppst-chip" style="background:#fff1f2; color:#9f1239; border-color:#fda4af;">
                                Desea removerse de PST
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="ppst-muted">
                        <?= htmlspecialchars(date('d/m/Y H:i', strtotime($s['fecha_solicitud'] ?? 'now'))) ?>
                    </td>
                    <td>
                        <form action="?ruta=resolver-solicitud-pst" method="POST" id="form-sol-<?= (int)$s['id'] ?>" style="display:flex; gap:6px; flex-wrap:wrap;">
                            <?= $csrfCampo ?>
                            <input type="hidden" name="id_solicitud" value="<?= (int)$s['id'] ?>">
                            <input type="hidden" name="accion" value="">
                            <input type="hidden" name="motivo" value="">
                            <button type="button" class="ppst-btn ppst-btn-primary" onclick="ppstResolverSolicitud(<?= (int)$s['id'] ?>, 'aprobar')">
                                <i class="ph-bold ph-check"></i> Aprobar
                            </button>
                            <button type="button" class="ppst-btn ppst-btn-danger" onclick="ppstResolverSolicitud(<?= (int)$s['id'] ?>, 'rechazar')">
                                <i class="ph-bold ph-x"></i> Rechazar
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <!-- 2. SOLICITUDES DE REGISTRO DOCENTE EN LÍNEA -->
    <?php if (!empty($pendientes)): ?>
    <div class="ppst-card" style="border-left: 4px solid #7090cb;">
        <h3>
            <i class="ph-bold ph-user-plus" style="color:#7090cb;"></i> Solicitudes de registro de cuenta docente pendientes
            <span class="ppst-chip" style="background:#f4f7fb; color:#505984; border-color:#7090cb;"><?= count($pendientes) ?></span>
        </h3>
        <table class="ppst-table">
            <thead><tr><th>Docente</th><th>Perfil PST declarado</th><th style="width: 220px;">Acción</th></tr></thead>
            <tbody>
            <?php foreach ($pendientes as $d): ?>
                <tr>
                    <td>
                        <strong style="color:#1e293b;"><?= htmlspecialchars($d['nombre_completo']) ?></strong><br>
                        <span class="ppst-muted">C.I: <?= htmlspecialchars($d['cedula'] ?? '') ?> • <?= htmlspecialchars($d['email'] ?? '') ?></span>
                    </td>
                    <td>
                        <?php if ($d['es_profesor_pst']): ?>
                            <span class="ppst-chip" style="background:#dcfce7; color:#166534; border-color:#86efac;"><i class="ph-bold ph-check"></i> Profesor PST</span>
                            <div class="ppst-muted" style="margin-top:4px;"><?= htmlspecialchars($fmtTrayectos($d['trayectos'])) ?></div>
                        <?php else: ?>
                            <span class="ppst-muted">No declaró ser profesor PST</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form action="?ruta=resolver-registro-docente" method="POST" id="form-reg-<?= (int)$d['id'] ?>" style="display:flex; gap:6px; flex-wrap:wrap;">
                            <?= $csrfCampo ?>
                            <input type="hidden" name="id_usuario" value="<?= (int)$d['id'] ?>">
                            <input type="hidden" name="accion" value="">
                            <input type="hidden" name="motivo" value="">
                            <button type="button" class="ppst-btn ppst-btn-primary" onclick="ppstResolverRegistro(<?= (int)$d['id'] ?>, 'aprobar')"><i class="ph-bold ph-check"></i> Aprobar</button>
                            <button type="button" class="ppst-btn ppst-btn-danger" onclick="ppstResolverRegistro(<?= (int)$d['id'] ?>, 'rechazar')"><i class="ph-bold ph-x"></i> Rechazar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <!-- 3. PLANTEL DOCENTE Y PERFIL PST ACTUAL -->
    <div class="ppst-card">
        <h3 style="justify-content: space-between; flex-wrap: wrap;">
            <span><i class="ph-bold ph-chalkboard-teacher" style="color:#505984;"></i> Plantel docente y Acreditación PST</span>
            <input type="search" class="ppst-search" placeholder="Buscar por nombre, cédula o correo..." oninput="ppstFiltrar(this.value)">
        </h3>
        <?php if (empty($docentes)): ?>
            <p class="ppst-muted">No hay docentes registrados.</p>
        <?php else: ?>
        <table class="ppst-table" id="ppst-tabla-docentes">
            <thead><tr><th>Docente</th><th>Estado de Cuenta</th><th>Trayectos PST Acreditados</th><th style="width: 150px;">Gestionar</th></tr></thead>
            <tbody>
            <?php foreach ($docentes as $d): ?>
                <tr class="ppst-fila" data-buscar="<?= htmlspecialchars(mb_strtolower($d['nombre_completo'] . ' ' . $d['cedula'] . ' ' . $d['email'])) ?>">
                    <td>
                        <strong style="color:#1e293b;"><?= htmlspecialchars($d['nombre_completo']) ?></strong><br>
                        <span class="ppst-muted">C.I: <?= htmlspecialchars($d['cedula'] ?? '') ?> • <?= htmlspecialchars($d['email'] ?? '') ?></span><br>
                        <span class="ppst-muted">Rol: <?= htmlspecialchars($d['rol_nombre'] ?? 'Docente') ?></span>
                    </td>
                    <td>
                        <?php if ($d['estado_registro'] === 'pendiente'): ?>
                            <span class="ppst-chip" style="background:#fef9c3; color:#854d0e; border-color:#facc15;">Pendiente de aprobación</span>
                        <?php elseif ($d['estado_registro'] === 'rechazado'): ?>
                            <span class="ppst-chip" style="background:#fff1f2; color:#9f1239; border-color:#fda4af;">Registro rechazado</span>
                        <?php elseif ($d['activo']): ?>
                            <span class="ppst-chip" style="background:#dcfce7; color:#166534; border-color:#86efac;">Activa</span>
                        <?php else: ?>
                            <span class="ppst-chip" style="background:#f1f5f9; color:#64748b; border-color:#cbd5e1;">Inactiva / invitación pendiente</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($d['es_profesor_pst']): ?>
                            <span class="ppst-chip" style="background:#f4f7fb; color:#505984; border-color:#7090cb;"><i class="ph-bold ph-check"></i> Profesor PST</span>
                            <div style="margin-top:4px; font-weight:600; color:#1e293b; font-size:0.85rem;"><?= htmlspecialchars($fmtTrayectos($d['trayectos'])) ?></div>
                        <?php else: ?>
                            <span class="ppst-muted">No acreditado como profesor PST</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button type="button" class="ppst-btn ppst-btn-outline" onclick="document.getElementById('ppst-edit-<?= (int)$d['id'] ?>').style.display = (document.getElementById('ppst-edit-<?= (int)$d['id'] ?>').style.display === 'none' ? 'table-row' : 'none');">
                            <i class="ph-bold ph-pencil-simple"></i> Editar PST
                        </button>
                    </td>
                </tr>
                <tr id="ppst-edit-<?= (int)$d['id'] ?>" style="display:none;">
                    <td colspan="4" style="background:#f8fafc; padding: 1rem;">
                        <form action="?ruta=guardar-profesor-pst" method="POST">
                            <?= $csrfCampo ?>
                            <input type="hidden" name="id_profesor" value="<?= (int)$d['id'] ?>">
                            <?php
                            $pstIdPrefijo = 'doc' . (int)$d['id'];
                            $pstEsProfesor = $d['es_profesor_pst'];
                            $pstSeleccion = $d['trayectos'];
                            include __DIR__ . '/../../Autenticacion/views/partials/selector_pst.php';
                            ?>
                            <div style="margin-top: 10px; display:flex; justify-content: flex-end;">
                                <button type="submit" class="ppst-btn ppst-btn-primary"><i class="ph-bold ph-floppy-disk"></i> Guardar asignación PST</button>
                            </div>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<script>
function ppstFiltrar(txt) {
    const q = (txt || '').toLowerCase().trim();
    document.querySelectorAll('#ppst-tabla-docentes .ppst-fila').forEach(tr => {
        const ok = q === '' || tr.getAttribute('data-buscar').includes(q);
        tr.style.display = ok ? '' : 'none';
        const edit = tr.nextElementSibling;
        if (!ok && edit) edit.style.display = 'none';
    });
}

function ppstResolverSolicitud(id, accion) {
    const form = document.getElementById('form-sol-' + id);
    if (!form) return;
    const esAprobar = accion === 'aprobar';
    Swal.fire({
        title: esAprobar ? '¿Aprobar acreditación PST?' : '¿Rechazar acreditación?',
        text: esAprobar ? 'El docente quedará acreditado para los trayectos seleccionados y podrá ser elegido por estudiantes.' : 'Indique el motivo del rechazo (opcional) que se enviará al docente:',
        input: esAprobar ? undefined : 'textarea',
        icon: esAprobar ? 'question' : 'warning',
        iconColor: esAprobar ? '#505984' : '#e11d48',
        showCancelButton: true,
        confirmButtonColor: esAprobar ? '#505984' : '#e11d48',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: esAprobar ? 'Sí, aprobar' : 'Sí, rechazar',
        cancelButtonText: 'Cancelar'
    }).then((r) => {
        if (r.isConfirmed) {
            form.querySelector('[name="accion"]').value = accion;
            form.querySelector('[name="motivo"]').value = (typeof r.value === 'string') ? r.value : '';
            form.submit();
        }
    });
}

function ppstResolverRegistro(id, accion) {
    const form = document.getElementById('form-reg-' + id);
    if (!form) return;
    const esAprobar = accion === 'aprobar';
    Swal.fire({
        title: esAprobar ? '¿Aprobar cuenta docente?' : '¿Rechazar solicitud?',
        text: esAprobar ? 'La cuenta se activará y el docente podrá iniciar sesión.' : 'Indique el motivo (opcional) que se enviará al solicitante:',
        input: esAprobar ? undefined : 'textarea',
        icon: esAprobar ? 'question' : 'warning',
        iconColor: esAprobar ? '#505984' : '#e11d48',
        showCancelButton: true,
        confirmButtonColor: esAprobar ? '#505984' : '#e11d48',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: esAprobar ? 'Sí, aprobar' : 'Sí, rechazar',
        cancelButtonText: 'Cancelar'
    }).then((r) => {
        if (r.isConfirmed) {
            form.querySelector('[name="accion"]').value = accion;
            form.querySelector('[name="motivo"]').value = (typeof r.value === 'string') ? r.value : '';
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