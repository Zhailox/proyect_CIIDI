<?php
// modules/Investigaciones/views/mis_investigaciones.php
// Variables disponibles: $investigaciones (array), $paginacion (array), $esSuperAdmin (bool), $alcance (string), $lineas (array), $responsablesDisponibles (array)
$esSuperAdmin = $esSuperAdmin ?? false;
$alcance = $alcance ?? 'mias';
$lineas = $lineas ?? [];
$responsablesDisponibles = $responsablesDisponibles ?? [];
?>
<div class="inv-wrapper">

    <div class="inv-header-gestion" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
        <div>
            <div style="display:flex; align-items:center; gap:0.65rem; flex-wrap:wrap;">
                <h1 style="margin:0;">
                    <?= ($esSuperAdmin && $alcance === 'todas') ? 'Todas las Investigaciones del Sistema' : 'Mis Proyectos de Investigación' ?>
                </h1>
                <?php if ($esSuperAdmin): ?>
                <span style="background:linear-gradient(135deg,#0f172a,#1e293b); color:#38bdf8; font-size:0.75rem; font-weight:800; padding:0.3rem 0.8rem; border-radius:50px; letter-spacing:0.5px; text-transform:uppercase; display:inline-flex; align-items:center; gap:0.35rem;">
                    <i class="ph-fill ph-crown-simple"></i> SuperAdmin
                </span>
                <?php endif; ?>
            </div>
            <p style="color: var(--inv-muted); margin-top:0.5rem; font-size:1.02rem; margin-bottom:0;">
                <?= ($esSuperAdmin && $alcance === 'todas')
                    ? 'Visualiza, mantén, modifica el estado, reasigna o elimina cualquier investigación subida por otros usuarios.'
                    : 'Gestiona los proyectos de los cuales eres responsable (Líder/Tutor).' ?>
            </p>
        </div>
        <div style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center;">
            <?php if ($esSuperAdmin): ?>
            <a href="?ruta=panel-investigaciones-admin" style="background:white; color:var(--inv-dark); border:1px solid var(--inv-border); padding:0.75rem 1.25rem; border-radius:50px; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:0.45rem; font-size:0.9rem; box-shadow:var(--inv-shadow-sm);">
                <i class="ph-fill ph-shield-check" style="color:#0369A1;"></i> Panel Admin I+D
            </a>
            <?php endif; ?>
            <a href="?ruta=crear-investigacion" class="inv-btn-create" style="text-decoration:none;">
                <i class="ph-bold ph-plus"></i> Nueva Investigación
            </a>
        </div>
    </div>

    <?php if ($esSuperAdmin): ?>
    <div style="background:white; border:1px solid var(--inv-border); border-radius:16px; padding:1rem 1.25rem; margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; box-shadow:var(--inv-shadow-sm);">
        <div style="display:inline-flex; background:#F1F5F9; padding:0.3rem; border-radius:50px; gap:0.25rem;">
            <a href="?ruta=mis-investigaciones&alcance=todas"
               style="padding:0.5rem 1.1rem; border-radius:50px; font-size:0.85rem; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:0.4rem;
                      <?= $alcance === 'todas' ? 'background:#0369A1; color:white; box-shadow:0 2px 8px rgba(3,105,161,0.25);' : 'color:var(--inv-muted);' ?>">
                <i class="ph-bold ph-globe"></i> Todas las Investigaciones
            </a>
            <a href="?ruta=mis-investigaciones&alcance=mias"
               style="padding:0.5rem 1.1rem; border-radius:50px; font-size:0.85rem; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:0.4rem;
                      <?= $alcance === 'mias' ? 'background:#0369A1; color:white; box-shadow:0 2px 8px rgba(3,105,161,0.25);' : 'color:var(--inv-muted);' ?>">
                <i class="ph-bold ph-user"></i> Solo Mis Investigaciones
            </a>
        </div>

        <form action="index.php" method="GET" style="display:flex; gap:0.6rem; flex-wrap:wrap; align-items:center;">
            <input type="hidden" name="ruta" value="mis-investigaciones">
            <input type="hidden" name="alcance" value="<?= htmlspecialchars($alcance) ?>">
            <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Buscar investigación..."
                   style="padding:0.5rem 0.9rem; border:1px solid var(--inv-border); border-radius:50px; font-size:0.85rem; outline:none; width:190px;">
            <select name="estado" onchange="this.form.submit()" style="padding:0.5rem 0.9rem; border:1px solid var(--inv-border); border-radius:50px; font-size:0.85rem; background:white; font-weight:600; cursor:pointer;">
                <option value="">Todos los estados</option>
                <option value="Abierta"       <?= ($_GET['estado'] ?? '') === 'Abierta'       ? 'selected' : '' ?>>Abierta</option>
                <option value="En Desarrollo" <?= ($_GET['estado'] ?? '') === 'En Desarrollo' ? 'selected' : '' ?>>En Desarrollo</option>
                <option value="Finalizada"    <?= ($_GET['estado'] ?? '') === 'Finalizada'    ? 'selected' : '' ?>>Finalizada</option>
                <option value="Cerrada"       <?= ($_GET['estado'] ?? '') === 'Cerrada'       ? 'selected' : '' ?>>Cerrada</option>
            </select>
            <?php if ($alcance === 'todas' && !empty($responsablesDisponibles)): ?>
            <select name="profesor" onchange="this.form.submit()" style="padding:0.5rem 0.9rem; border:1px solid var(--inv-border); border-radius:50px; font-size:0.85rem; background:white; font-weight:600; cursor:pointer; max-width:200px;">
                <option value="">Todos los docentes</option>
                <?php foreach ($responsablesDisponibles as $resp): ?>
                    <option value="<?= (int)$resp['id'] ?>" <?= ((int)($_GET['profesor'] ?? 0) === (int)$resp['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($resp['nombre_completo'] ?? $resp['nombre'] ?? '') ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php endif; ?>
            <button type="submit" style="background:#0369A1; color:white; border:none; padding:0.5rem 1rem; border-radius:50px; font-weight:700; cursor:pointer; font-size:0.84rem;">
                <i class="ph-bold ph-magnifying-glass"></i> Filtrar
            </button>
            <?php if (!empty($_GET['q']) || !empty($_GET['estado']) || !empty($_GET['profesor'])): ?>
            <a href="?ruta=mis-investigaciones&alcance=<?= urlencode($alcance) ?>" style="color:var(--inv-muted); font-size:0.82rem; font-weight:600; text-decoration:none;">
                Limpiar
            </a>
            <?php endif; ?>
        </form>
    </div>
    <?php endif; ?>

    <div class="inv-table-container">
        <table class="inv-table">
            <thead>
                <tr>
                    <th>Proyecto</th>
                    <?php if ($esSuperAdmin && $alcance === 'todas'): ?>
                    <th>Docente Responsable</th>
                    <?php endif; ?>
                    <th>Línea / Trayecto</th>
                    <th>Estado</th>
                    <th style="text-align: center;">Postulantes</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($investigaciones)): ?>
                <tr>
                    <td colspan="<?= ($esSuperAdmin && $alcance === 'todas') ? 6 : 5 ?>" style="text-align:center; padding:4rem; color:var(--inv-muted);">
                        <i class="ph-fill ph-folders" style="font-size:3rem; margin-bottom:1rem; color:var(--inv-primary-light);"></i><br>
                        No hay investigaciones registradas que coincidan con la vista actual.
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($investigaciones as $inv): ?>
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:0.4rem; margin-bottom:0.25rem;">
                                <span style="font-family:monospace; font-size:0.73rem; background:#F1F5F9; color:var(--inv-muted); padding:0.15rem 0.45rem; border-radius:4px; font-weight:700;">#<?= (int)$inv['id'] ?></span>
                                <?php if (!empty($inv['tag_que'])): ?>
                                <span style="font-size:0.72rem; font-weight:700; background:#E0F2FE; color:#0369A1; padding:0.15rem 0.5rem; border-radius:50px;">
                                    <?= htmlspecialchars($inv['tag_que']) ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            <strong style="color: var(--inv-dark); font-size: 1.02rem; display:block; margin-bottom: 0.3rem;">
                                <?= htmlspecialchars($inv['titulo'] ?? '') ?>
                            </strong>
                            <div style="font-size: 0.83rem; color: var(--inv-muted);">
                                <i class="ph-fill ph-users-three"></i> Cupos Totales: <?= (int)$inv['cupos_disponibles'] ?>
                            </div>
                        </td>
                        <?php if ($esSuperAdmin && $alcance === 'todas'): ?>
                        <td>
                            <div style="font-weight:700; color:var(--inv-dark); font-size:0.88rem; display:flex; align-items:center; gap:0.4rem;">
                                <i class="ph-fill ph-chalkboard-teacher" style="color:#0369A1;"></i>
                                <?= htmlspecialchars($inv['profesor'] ?? 'Sin asignar') ?>
                            </div>
                        </td>
                        <?php endif; ?>
                        <td>
                            <div style="font-weight:600; color:var(--inv-dark); font-size:0.88rem;">
                                <?= htmlspecialchars($inv['linea_nombre'] ?? 'Sin línea asignada') ?>
                            </div>
                            <span style="color: var(--inv-muted); text-transform: uppercase; font-size: 0.78rem; background:#F8FAFC; padding:0.1rem 0.4rem; border-radius:4px;">
                                <?= htmlspecialchars($inv['trayecto'] ?? 't4') ?>
                            </span>
                        </td>
                        <td>
                            <?php 
                            $clase_estado = 'inv-status-borrador';
                            if($inv['estado'] === 'Abierta') $clase_estado = 'inv-status-activo';
                            if($inv['estado'] === 'En Desarrollo') $clase_estado = 'inv-status-publicado';
                            if($inv['estado'] === 'Finalizada') $clase_estado = 'inv-status-archivado';
                            if($inv['estado'] === 'Cerrada') $clase_estado = 'inv-status-rechazado';
                            ?>
                            <?php if ($esSuperAdmin): ?>
                                <form action="?ruta=cambiar-estado-investigacion" method="POST" style="display:inline-flex; align-items:center; gap:0.3rem;">
                                    <input type="hidden" name="id" value="<?= (int)$inv['id'] ?>">
                                    <input type="hidden" name="from_mis_inv" value="1">
                                    <select name="estado" onchange="this.form.submit()" class="inv-status-pill <?= $clase_estado ?>" style="border:1px solid rgba(0,0,0,0.08); cursor:pointer; outline:none;">
                                        <option value="Abierta"       <?= $inv['estado'] === 'Abierta'       ? 'selected' : '' ?>>Abierta</option>
                                        <option value="En Desarrollo" <?= $inv['estado'] === 'En Desarrollo' ? 'selected' : '' ?>>En Desarrollo</option>
                                        <option value="Finalizada"    <?= $inv['estado'] === 'Finalizada'    ? 'selected' : '' ?>>Finalizada</option>
                                        <option value="Cerrada"       <?= $inv['estado'] === 'Cerrada'       ? 'selected' : '' ?>>Cerrada</option>
                                    </select>
                                </form>
                            <?php else: ?>
                                <span class="inv-status-pill <?= $clase_estado ?>">
                                    <?= htmlspecialchars($inv['estado']) ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;">
                            <?php if (($inv['postulantes_pendientes'] ?? 0) > 0): ?>
                                <a href="<?= $esSuperAdmin ? '?ruta=panel-investigaciones-admin' : '?ruta=mis-postulantes' ?>" style="display:inline-flex; align-items:center; gap:0.3rem; padding: 0.4rem 0.8rem; border-radius: 50px; background-color: #FEE2E2; color: #991B1B; text-decoration: none; font-weight: 700; font-size: 0.82rem;">
                                    <div style="width:8px; height:8px; background:#EF4444; border-radius:50%; animation:pulse 2s infinite;"></div> <?= (int)$inv['postulantes_pendientes'] ?> Nuevos
                                </a>
                            <?php else: ?>
                                <span style="color: var(--inv-muted); font-size: 0.82rem; background:#F1F5F9; padding:0.3rem 0.6rem; border-radius:50px;">0 Pendientes</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: right;">
                            <div class="inv-action-btns" style="justify-content:flex-end;">
                                <a href="?ruta=editar-investigacion&id=<?= (int)$inv['id'] ?>" class="inv-action-btn edit" title="Modificar Investigación" style="text-decoration:none;">
                                    <i class="ph-bold ph-pencil-simple"></i>
                                </a>
                                <form action="?ruta=eliminar-investigacion" method="POST" style="display:inline;" id="form-delete-<?= (int)$inv['id'] ?>">
                                    <input type="hidden" name="id" value="<?= (int)$inv['id'] ?>">
                                    <button type="button" class="inv-action-btn del" title="Eliminar Investigación" onclick="confirmarEliminacion(<?= (int)$inv['id'] ?>)">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINACIÓN -->
    <?php if (isset($paginacion) && $paginacion['paginas'] > 1): ?>
    <?php
    $pagActual = $paginacion['pagina'];
    $pagTotal  = $paginacion['paginas'];
    $queryParams = ['ruta' => 'mis-investigaciones'];
    if ($esSuperAdmin) $queryParams['alcance'] = $alcance;
    if (!empty($_GET['q'])) $queryParams['q'] = $_GET['q'];
    if (!empty($_GET['estado'])) $queryParams['estado'] = $_GET['estado'];
    if (!empty($_GET['profesor'])) $queryParams['profesor'] = $_GET['profesor'];
    $basePaginacion = '?' . http_build_query($queryParams) . '&page=';
    ?>
    <div class="inv-pagination">
        <div class="inv-pag-controls">
            <?php if ($pagActual > 1): ?>
            <a href="<?= $basePaginacion . ($pagActual - 1) ?>" class="inv-pag-btn">
                <i class="ph-bold ph-caret-left"></i>
            </a>
            <?php endif; ?>

            <?php for ($i = max(1, $pagActual - 2); $i <= min($pagTotal, $pagActual + 2); $i++): ?>
            <a href="<?= $basePaginacion . $i ?>" class="inv-pag-btn <?= $i === $pagActual ? 'inv-pag-btn--active' : '' ?>">
                <?= $i ?>
            </a>
            <?php endfor; ?>

            <?php if ($pagActual < $pagTotal): ?>
            <a href="<?= $basePaginacion . ($pagActual + 1) ?>" class="inv-pag-btn">
                <i class="ph-bold ph-caret-right"></i>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>
function mostrarModalSistema(tipo, titulo, mensaje, isConfirm = false, onConfirm = null) {
    const overlay = document.createElement('div');
    overlay.style.cssText = 'position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,34,68,0.8); z-index:99999; display:flex; align-items:center; justify-content:center; backdrop-filter:blur(4px);';
    
    let icon = tipo === 'success' ? '<i class="ph-bold ph-check-circle" style="color: #16a34a;"></i>' : '<i class="ph-bold ph-warning-circle" style="color: #dc2626;"></i>';
    let btnHtml = isConfirm 
        ? `<button type="button" class="btn btn-secondary" onclick="this.closest('div').parentElement.parentElement.remove()" style="margin-right:0.5rem; background:white; color:var(--inv-dark); border:1px solid var(--inv-border); padding:0.8rem 1.5rem; border-radius:50px; font-weight:600; cursor:pointer;">Cancelar</button>
           <button type="button" class="btn btn-primary" id="btn-confirm-modal" style="background:var(--inv-danger, #dc2626); color:white; border:none; padding:0.8rem 1.5rem; border-radius:50px; font-weight:600; cursor:pointer; box-shadow:0 4px 10px rgba(239, 68, 68, 0.3);">Sí, eliminar</button>`
        : `<button type="button" class="btn btn-primary w-100 justify-center" onclick="this.closest('div').parentElement.parentElement.remove()" style="background:var(--inv-primary, #0369A1); color:white; border:none; padding:0.8rem 1.5rem; border-radius:50px; font-weight:700; cursor:pointer; width:100%;">Entendido</button>`;

    if (isConfirm) {
        icon = '<i class="ph-bold ph-trash" style="color: #dc2626;"></i>';
    }

    overlay.innerHTML = `
        <div style="background: white; padding: 2.5rem 2rem; border-radius: 16px; max-width: 400px; width: 90%; text-align: center; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <div style="font-size: 3.5rem; margin-bottom: 1rem;">${icon}</div>
            <h3 style="margin: 0 0 0.5rem 0; color: #0f172a; font-size:1.4rem; font-weight:800;">${titulo}</h3>
            <p style="color: #475569; font-size: 0.95rem; margin-bottom: 2rem; line-height:1.5;">${mensaje}</p>
            <div style="display:flex; justify-content:center;">${btnHtml}</div>
        </div>
    `;
    
    document.body.appendChild(overlay);

    if (isConfirm && onConfirm) {
        document.getElementById('btn-confirm-modal').addEventListener('click', () => {
            overlay.remove();
            onConfirm();
        });
    }
}

function confirmarEliminacion(idInvestigacion) {
    mostrarModalSistema(
        'warning', 
        'Eliminar Proyecto', 
        '¿Está seguro de eliminar esta investigación? Se borrará el proyecto y todas sus postulaciones permanentemente.', 
        true, 
        () => document.getElementById('form-delete-' + idInvestigacion).submit()
    );
}
</script>
<script>
<?php if (isset($_SESSION['flash_success'])): ?>
    document.addEventListener("DOMContentLoaded", function() {
        mostrarModalSistema('success', 'Operación Exitosa', '<?= htmlspecialchars($_SESSION['flash_success']) ?>');
    });
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['flash_error'])): ?>
    document.addEventListener("DOMContentLoaded", function() {
        mostrarModalSistema('error', 'Ocurrió un Problema', '<?= htmlspecialchars($_SESSION['flash_error']) ?>');
    });
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>
</script>
