<?php
// modules/LineasInvestigacion/views/detalle_gestion_linea.php
?>
<style>
.ag-header-banner {
    background: rgba(255, 255, 255, 0.92) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(80, 89, 132, 0.16) !important;
    border-radius: 14px;
    padding: 1.6rem 2rem;
    box-shadow: 0 16px 36px rgba(18, 26, 62, 0.05);
    margin-bottom: 2rem;
}
.ag-kpi-grid {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}
.ag-kpi-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 12px;
    padding: 1.25rem 2rem;
    border: 1px solid rgba(80, 89, 132, 0.14);
    box-shadow: 0 8px 24px rgba(18, 26, 62, 0.03);
    min-width: 200px;
    flex: 1;
}
.ag-kpi-label {
    font-size: 0.75rem;
    font-weight: 800;
    color: var(--texto-silenciado);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 0.5rem;
}
.ag-kpi-value {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--texto-titulos);
    line-height: 1;
}

.ag-list-container {
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(80, 89, 132, 0.14);
    border-radius: 14px;
    padding: 2rem;
    box-shadow: 0 8px 24px rgba(18, 26, 62, 0.03);
}

.ag-dim-item {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 1.25rem;
    margin-bottom: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: 0.2s;
}
.ag-dim-item:hover {
    background: #fff;
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    transform: translateY(-2px);
}
.ag-dim-title {
    font-weight: 800;
    color: var(--texto-titulos);
    font-size: 1.1rem;
    margin-bottom: 0.3rem;
}
.ag-dim-desc {
    color: var(--texto-silenciado);
    font-size: 0.9rem;
}

.ag-btn-edit {
    color: #f59e0b;
    background: rgba(245, 158, 11, 0.1);
    border: none;
    padding: 8px 14px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    transition: 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.ag-btn-edit:hover { background: #f59e0b; color: #fff; }

.ag-btn-delete {
    color: #ef4444;
    background: rgba(239, 68, 68, 0.1);
    border: none;
    padding: 8px 14px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    transition: 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.ag-btn-delete:hover { background: #ef4444; color: #fff; }

.li-alert {
    padding: 1rem 1.25rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.li-alert.exito { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
.li-alert.error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
</style>

<div class="li-gestor-wrapper">

    <!-- ENCABEZADO -->
    <div class="ag-header-banner">
        <div style="display: flex; gap: 1.5rem; align-items: flex-start;">
            <div style="width: 64px; height: 64px; border-radius: 14px; background: rgba(80, 89, 132, 0.08); border: 1px solid rgba(80, 89, 132, 0.15); display: flex; align-items: center; justify-content: center; font-size: 2rem; color: var(--color-primario); flex-shrink: 0;">
                <i class="ph-bold ph-graph"></i>
            </div>
            <div style="flex: 1;">
                <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-terciario); font-weight: 800; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.3rem;">
                    GESTOR DEDICADO DE LÍNEA • PNF EN <?= htmlspecialchars(mb_convert_case($linea['carrera_nombre'] ?? 'Sin Asignar', MB_CASE_UPPER, 'UTF-8')) ?>
                </div>
                <h1 style="font-size: 1.85rem; font-weight: 800; color: var(--texto-titulos, #0f172a); margin: 0 0 0.4rem 0;">
                    <?= htmlspecialchars(mb_convert_case($linea['nombre'], MB_CASE_TITLE, 'UTF-8')) ?>
                </h1>
                <p style="margin: 0; color: var(--texto-silenciado, #64748b); font-size: 0.95rem; max-width: 800px; line-height: 1.5;">
                    <?= htmlspecialchars($linea['descripcion']) ?>
                </p>
                
                <div style="display: flex; align-items: center; gap: 12px; margin-top: 1.2rem;">
                    <button type="button" onclick="abrirModalEditarLinea(<?= htmlspecialchars(json_encode($linea)) ?>)" style="border: 1px solid #059669; color: #059669; background: rgba(5, 150, 105, 0.1); text-decoration: none; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; transition: 0.2s; cursor: pointer;" onmouseover="this.style.background='#059669'; this.style.color='#fff'" onmouseout="this.style.background='rgba(5, 150, 105, 0.1)'; this.style.color='#059669'">
                        <i class="ph-bold ph-pencil-simple"></i> Editar Línea
                    </button>
                    <a href="index.php?ruta=gestionar-lineas" style="border: 1px solid var(--color-secundario); color: var(--color-secundario); background: #ffffff; text-decoration: none; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; transition: all 0.2s ease; cursor: pointer;" onmouseover="this.style.background='var(--color-secundario)'; this.style.color='#fff'" onmouseout="this.style.background='#ffffff'; this.style.color='var(--color-secundario)'">
                        <i class="ph-bold ph-arrow-left"></i> Volver a Líneas
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ALERTAS -->
    <?php if (!empty($mensaje)): ?>
    <div class="li-alert <?= htmlspecialchars($tipo_mensaje) ?>">
        <i class="ph-bold <?= $tipo_mensaje === 'exito' ? 'ph-check-circle' : 'ph-warning-circle' ?>"></i>
        <?= $mensaje ?>
    </div>
    <?php endif; ?>

    <!-- KPIs -->
    <div class="ag-kpi-grid">
        <div class="ag-kpi-card">
            <div class="ag-kpi-label">Total Dimensiones</div>
            <div class="ag-kpi-value"><?= count($dimensiones) ?></div>
        </div>
        <div class="ag-kpi-card">
            <div class="ag-kpi-label">Proyectos Vinculados</div>
            <div class="ag-kpi-value" style="color:#059669;">
                <?= htmlspecialchars($linea['total_proyectos'] ?? 0) ?>
            </div>
        </div>
    </div>

    <!-- LISTA DE DIMENSIONES -->
    <div class="ag-list-container">
        
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:2px solid #e2e8f0; padding-bottom:1rem; margin-bottom:1.5rem;">
            <div>
                <h2 style="margin:0; font-size:1.3rem; color:var(--texto-titulos);"><i class="ph-bold ph-squares-four"></i> Dimensiones Operativas (Componentes)</h2>
                <p style="margin:0.2rem 0 0 0; font-size:0.9rem; color:var(--texto-silenciado);">Gestione las dimensiones asociadas a esta línea de investigación.</p>
            </div>
            <button type="button" onclick="abrirModalCrearDimension(<?= htmlspecialchars($linea['id']) ?>)" style="background: var(--color-primario); color: #ffffff; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                <i class="ph-bold ph-plus"></i> Añadir Dimensión
            </button>
        </div>

        <?php if (count($dimensiones) > 0): ?>
            <?php foreach($dimensiones as $dim): ?>
                <div class="ag-dim-item">
                    <div style="flex:1;">
                        <div class="ag-dim-title"><?= htmlspecialchars(mb_convert_case($dim['nombre'], MB_CASE_TITLE, 'UTF-8')) ?></div>
                        <div class="ag-dim-desc"><?= htmlspecialchars($dim['descripcion']) ?></div>
                    </div>
                    <div style="display:flex; gap:8px; margin-left:1.5rem;">
                        <button type="button" class="ag-btn-edit" onclick="abrirModalEditarDimension(<?= htmlspecialchars(json_encode($dim)) ?>)">
                            <i class="ph-bold ph-pencil-simple"></i> Editar
                        </button>
                        <button type="button" class="ag-btn-delete" onclick="eliminarDimension(<?= htmlspecialchars($dim['id']) ?>, '<?= htmlspecialchars(addslashes($dim['nombre'])) ?>')">
                            <i class="ph-bold ph-trash"></i> Eliminar
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align:center; padding: 3rem 0;">
                <i class="ph-bold ph-ghost" style="font-size:3rem; color:#cbd5e1; margin-bottom:1rem;"></i>
                <h3 style="color:#64748b; margin:0;">No hay dimensiones operativas</h3>
                <p style="color:#94a3b8; font-size:0.9rem;">Comience añadiendo la primera dimensión operativa para esta línea.</p>
            </div>
        <?php endif; ?>

    </div>

</div>

<!-- Formularios Ocultos -->
<form id="formEliminarDimension" method="POST" action="index.php?ruta=gestionar-dimensiones" style="display:none;">
    <input type="hidden" name="accion" value="eliminar">
    <input type="hidden" name="id" id="deleteDimensionId" value="">
    <input type="hidden" name="redirect_to" value="detalle-gestion-linea&id=<?= $linea['id'] ?>">
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function abrirModalEditarLinea(linea) {
    // We don't have the full carreras list here natively, but we can just use a simple form for name/desc if needed,
    // or fetch it. Actually, GestorController returns $carreras for `index`, but not for `detalleGestionLinea`.
    // We can just keep it simple or do a form post to redirect back to gestor-lineas.
    // For now, let's just let them edit Name and Description, or we can send an AJAX request.
    // Wait, the user can just edit Name and Description here!
    
    Swal.fire({
        title: 'Editar Línea de Investigación',
        html: `
            <div style="text-align: left;">
                <form id="form-edit-linea" method="POST" action="index.php?ruta=gestionar-lineas">
                    <input type="hidden" name="accion" value="editar">
                    <input type="hidden" name="id" value="${linea.id}">
                    <input type="hidden" name="id_carrera" value="${linea.id_carrera}">
                    <input type="hidden" name="redirect_to" value="detalle-gestion-linea&id=${linea.id}">
                    
                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-weight:bold; margin-bottom:0.3rem;">Nombre</label>
                        <input type="text" name="nombre" class="swal2-input" style="width:90%; margin:0;" required value="${linea.nombre.replace(/"/g, '&quot;')}">
                    </div>
                    <div>
                        <label style="display:block; font-weight:bold; margin-bottom:0.3rem;">Descripción</label>
                        <textarea name="descripcion" class="swal2-textarea" style="width:90%; margin:0; height:100px;" required>${linea.descripcion}</textarea>
                    </div>
                </form>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="ph-bold ph-floppy-disk"></i> Guardar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const form = document.getElementById('form-edit-linea');
            if (!form.nombre.value || !form.descripcion.value) {
                Swal.showValidationMessage('Todos los campos son obligatorios');
                return false;
            }
            form.submit();
        }
    });
}

function eliminarDimension(id, nombre) {
    Swal.fire({
        title: '¿Eliminar Dimensión?',
        html: `Estás a punto de eliminar <strong>${nombre}</strong>. ¿Confirmas?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteDimensionId').value = id;
            document.getElementById('formEliminarDimension').submit();
        }
    });
}

function abrirModalCrearDimension(idLinea) {
    Swal.fire({
        title: 'Añadir Dimensión',
        html: `
            <div style="text-align: left;">
                <form id="form-create-dim" method="POST" action="index.php?ruta=gestionar-dimensiones">
                    <input type="hidden" name="accion" value="crear">
                    <input type="hidden" name="id_linea" value="${idLinea}">
                    <input type="hidden" name="redirect_to" value="detalle-gestion-linea&id=${idLinea}">
                    
                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-weight:bold; margin-bottom:0.3rem;">Nombre</label>
                        <input type="text" name="nombre" class="swal2-input" style="width:90%; margin:0;" required placeholder="Nombre de la dimensión...">
                    </div>
                    <div>
                        <label style="display:block; font-weight:bold; margin-bottom:0.3rem;">Descripción</label>
                        <textarea name="descripcion" class="swal2-textarea" style="width:90%; margin:0; height:100px;" required placeholder="Descripción..."></textarea>
                    </div>
                </form>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="ph-bold ph-floppy-disk"></i> Guardar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const form = document.getElementById('form-create-dim');
            if (!form.nombre.value || !form.descripcion.value) {
                Swal.showValidationMessage('Todos los campos son obligatorios');
                return false;
            }
            form.submit();
        }
    });
}

function abrirModalEditarDimension(dim) {
    Swal.fire({
        title: 'Editar Dimensión',
        html: `
            <div style="text-align: left;">
                <form id="form-edit-dim" method="POST" action="index.php?ruta=gestionar-dimensiones">
                    <input type="hidden" name="accion" value="editar">
                    <input type="hidden" name="id" value="${dim.id}">
                    <input type="hidden" name="id_linea" value="${dim.id_linea}">
                    <input type="hidden" name="redirect_to" value="detalle-gestion-linea&id=${dim.id_linea}">
                    
                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-weight:bold; margin-bottom:0.3rem;">Nombre</label>
                        <input type="text" name="nombre" class="swal2-input" style="width:90%; margin:0;" required value="${dim.nombre.replace(/"/g, '&quot;')}">
                    </div>
                    <div>
                        <label style="display:block; font-weight:bold; margin-bottom:0.3rem;">Descripción</label>
                        <textarea name="descripcion" class="swal2-textarea" style="width:90%; margin:0; height:100px;" required>${dim.descripcion}</textarea>
                    </div>
                </form>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="ph-bold ph-floppy-disk"></i> Guardar Cambios',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const form = document.getElementById('form-edit-dim');
            if (!form.nombre.value || !form.descripcion.value) {
                Swal.showValidationMessage('Todos los campos son obligatorios');
                return false;
            }
            form.submit();
        }
    });
}
</script>
