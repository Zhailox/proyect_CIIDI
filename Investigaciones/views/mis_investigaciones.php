<?php
// modules/Investigaciones/views/mis_investigaciones.php
// Variables disponibles: $investigaciones (array)
?>
<div class="inv-wrapper">

    
    

    <div class="inv-header-gestion">
        <div>
            <h1>Mis Proyectos de Investigación</h1>
            <p style="color: var(--inv-muted); margin-top:0.5rem; font-size:1.1rem;">Gestiona los proyectos de los cuales eres responsable (Líder/Tutor).</p>
        </div>
        <div>
            <a href="?ruta=crear-investigacion" class="inv-btn-primary">
                <i class="ph-bold ph-plus"></i> Nueva Investigación
            </a>
        </div>
    </div>

    <div class="inv-table-container">
        <table class="inv-table">
            <thead>
                <tr>
                    <th>Proyecto</th>
                    <th>Línea / Trayecto</th>
                    <th>Estado</th>
                    <th style="text-align: center;">Postulantes</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($investigaciones)): ?>
                <tr>
                    <td colspan="5" style="text-align:center; padding:4rem; color:var(--inv-muted);">
                        <i class="ph-fill ph-folders" style="font-size:3rem; margin-bottom:1rem; color:var(--inv-primary-light);"></i><br>
                        No tienes investigaciones registradas aún.
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($investigaciones as $inv): ?>
                    <tr>
                        <td>
                            <strong style="color: var(--inv-dark); font-size: 1.05rem; display:block; margin-bottom: 0.3rem;">
                                <?= htmlspecialchars($inv['titulo']) ?>
                            </strong>
                            <div style="font-size: 0.85rem; color: var(--inv-muted);">
                                <i class="ph-fill ph-users-three"></i> Cupos Totales: <?= (int)$inv['cupos_disponibles'] ?>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight:600; color:var(--inv-dark); font-size:0.9rem;">
                                <?= htmlspecialchars($inv['linea_nombre']) ?>
                            </div>
                            <span style="color: var(--inv-muted); text-transform: uppercase; font-size: 0.8rem; background:#F8FAFC; padding:0.1rem 0.4rem; border-radius:4px;">
                                <?= htmlspecialchars($inv['trayecto']) ?>
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
                            <span class="inv-status-pill <?= $clase_estado ?>">
                                <?= htmlspecialchars($inv['estado']) ?>
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <?php if ($inv['postulantes_pendientes'] > 0): ?>
                                <a href="?ruta=mis-postulantes" style="display:inline-flex; align-items:center; gap:0.3rem; padding: 0.4rem 0.8rem; border-radius: 50px; background-color: #FEE2E2; color: #991B1B; text-decoration: none; font-weight: 700; font-size: 0.85rem;">
                                    <div style="width:8px; height:8px; background:#EF4444; border-radius:50%; animation:pulse 2s infinite;"></div> <?= $inv['postulantes_pendientes'] ?> Nuevos
                                </a>
                            <?php else: ?>
                                <span style="color: var(--inv-muted); font-size: 0.85rem; background:#F1F5F9; padding:0.3rem 0.6rem; border-radius:50px;">0 Pendientes</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: right;">
                            <div class="inv-action-btns" style="justify-content:flex-end;">
                                <a href="?ruta=editar-investigacion&id=<?= $inv['id'] ?>" class="inv-action-btn" title="Editar">
                                    <i class="ph-bold ph-pencil-simple"></i>
                                </a>
                                <form action="?ruta=eliminar-investigacion" method="POST" style="display:inline;"  id="form-delete-<?= $inv['id'] ?>">
                                    <input type="hidden" name="id" value="<?= $inv['id'] ?>">
                                    <button type="submit" class="inv-action-btn del" title="Eliminar">
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
</div>

<script>
function mostrarModalSistema(tipo, titulo, mensaje, isConfirm = false, onConfirm = null) {
    const overlay = document.createElement('div');
    overlay.style.cssText = 'position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,34,68,0.8); z-index:99999; display:flex; align-items:center; justify-content:center; backdrop-filter:blur(4px);';
    
    let icon = tipo === 'success' ? '<i class="ph-bold ph-check-circle" style="color: #16a34a;"></i>' : '<i class="ph-bold ph-warning-circle" style="color: #dc2626;"></i>';
    let btnHtml = isConfirm 
        ? `<button type="button" class="btn btn-secondary" onclick="this.closest('div').parentElement.parentElement.remove()" style="margin-right:0.5rem; background:white; color:var(--inv-dark); border:1px solid var(--inv-border); padding:0.8rem 1.5rem; border-radius:50px; font-weight:600; cursor:pointer;">Cancelar</button>
           <button type="button" class="btn btn-primary" id="btn-confirm-modal" style="background:var(--color-danger, #dc2626); color:white; border:none; padding:0.8rem 1.5rem; border-radius:50px; font-weight:600; cursor:pointer; box-shadow:0 4px 10px rgba(220, 38, 38, 0.3);">Sí, eliminar</button>`
        : `<button type="button" class="btn btn-primary w-100 justify-center" onclick="this.closest('div').parentElement.parentElement.remove()" style="background:var(--color-secundario, #0b1a30); color:white; border:none; padding:0.8rem 1.5rem; border-radius:50px; font-weight:700; cursor:pointer; width:100%;">Entendido</button>`;

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