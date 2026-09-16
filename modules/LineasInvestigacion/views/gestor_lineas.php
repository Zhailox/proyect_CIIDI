<?php
// modules/LineasInvestigacion/views/gestor_lineas.php
?>
<style>
/* ==========================================================================
   Antigravity UI & Motion Design Expert Style Guide
   ========================================================================== */
.ag-header-banner {
    background: rgba(255, 255, 255, 0.92) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(80, 89, 132, 0.16) !important;
    border-radius: var(--radius-md);
    padding: 1.6rem 2rem;
    box-shadow: 0 16px 36px rgba(18, 26, 62, 0.05);
    margin-bottom: 2rem;
}

.ag-modules-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.ag-card {
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(80, 89, 132, 0.15) !important;
    border-radius: 14px !important;
    padding: 1.6rem !important;
    box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 1.25rem;
}

.ag-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 42px rgba(18, 26, 62, 0.08);
    border-color: rgba(80, 89, 132, 0.28) !important;
}

.ag-card-icon {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    background: rgba(80, 89, 132, 0.08);
    border: 1px solid rgba(80, 89, 132, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    color: var(--color-primario);
}

.ag-btn-manage {
    background: rgba(80, 89, 132, 0.06);
    color: var(--color-primario);
    border: 1px solid rgba(80, 89, 132, 0.15);
    border-radius: 8px;
    padding: 8px 14px;
    font-weight: 700;
    font-size: 0.82rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
}
.ag-btn-manage:hover {
    background: var(--color-primario);
    color: white;
    box-shadow: 0 4px 12px rgba(18, 26, 62, 0.15);
}

.ag-btn-edit {
    color: #f59e0b;
    background: rgba(245, 158, 11, 0.1);
    border: none;
    padding: 6px;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.2s;
}
.ag-btn-edit:hover { background: #f59e0b; color: #fff; }

.ag-btn-delete {
    color: #ef4444;
    background: rgba(239, 68, 68, 0.1);
    border: none;
    padding: 6px;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.2s;
}
.ag-btn-delete:hover { background: #ef4444; color: #fff; }

.ag-dimensions-container {
    display: none;
    margin-top: 1rem;
    border-top: 1px dashed rgba(80, 89, 132, 0.2);
    padding-top: 1rem;
    animation: fadeIn 0.3s ease;
}
@keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }

.ag-dim-item {
    background: rgba(255, 255, 255, 0.6);
    border: 1px solid rgba(80, 89, 132, 0.1);
    border-radius: 8px;
    padding: 10px 14px;
    margin-bottom: 0.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.ag-dim-title {
    font-weight: 700;
    color: var(--texto-titulos);
    font-size: 0.9rem;
}

/* ==========================================================================
   FORMULARIO (Línea)
   ========================================================================== */
.li-card-form {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    margin-bottom: 2rem;
}
.li-form-group { margin-bottom: 1rem; }
.li-form-group label { display: block; font-weight: 700; margin-bottom: 0.4rem; color:#334155; font-size: 0.9rem; }
.li-form-group input, .li-form-group textarea, .li-form-group select {
    width: 100%;
    padding: 0.6rem 0.8rem;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-family: inherit;
    transition: 0.2s;
}
.li-form-group input:focus, .li-form-group textarea:focus, .li-form-group select:focus {
    border-color: var(--color-primario);
    outline: none;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}
.btn-primary-li {
    background: var(--color-primario);
    color: white;
    padding: 0.7rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.2s;
}
.btn-primary-li:hover { background: var(--color-primario-hover); transform: translateY(-2px); }

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

    <!-- ENCABEZADO PRINCIPAL GLASSMORPHIC -->
    <div class="ag-header-banner">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.2rem;">
            <div>
                <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-terciario); font-weight: 800; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 0.3rem;">
                    <i class="ph-bold ph-squares-four"></i> MÓDULO DE LÍNEAS
                </div>
                <h1 style="font-size: 1.85rem; font-weight: 800; color: var(--texto-titulos, #0f172a); margin: 0;">
                    Líneas de Investigación y Dimensiones
                </h1>
                <p style="margin: 0.3rem 0 0 0; color: var(--texto-silenciado, #64748b); font-size: 0.9rem;">
                    Gestione las líneas, asocie dimensiones operativas internamente y controle su configuración.
                </p>
            </div>
            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <a href="index.php?ruta=lineas-investigacion" class="btn btn-outline" style="border-color: #059669; color: #059669; background: #ffffff; text-decoration: none; padding: 8px 14px; border-radius: 8px; font-weight: 700; font-size: 0.83rem; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="ph-bold ph-eye"></i> Vista Pública
                </a>
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

    <!-- FORMULARIO NUEVA / EDITAR LINEA -->
    <div class="li-card-form">
        <?php if ($linea_editar): ?>
            <h2 style="margin-top:0; color:var(--texto-titulos);"><i class="ph-bold ph-pencil-simple"></i> Editar Línea de Investigación</h2>
        <?php else: ?>
            <h2 style="margin-top:0; color:var(--texto-titulos);"><i class="ph-bold ph-plus-circle"></i> Nueva Línea de Investigación</h2>
        <?php endif; ?>

        <form method="POST" action="index.php?ruta=gestionar-lineas" style="margin-top: 1rem;">
            <input type="hidden" name="accion" value="<?= $linea_editar ? 'editar' : 'crear' ?>">
            <?php if ($linea_editar): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($linea_editar['id']) ?>">
            <?php endif; ?>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:1rem;">
                <div class="li-form-group">
                    <label>Nombre de la Línea</label>
                    <input type="text" name="nombre" value="<?= $linea_editar ? htmlspecialchars(mb_convert_case($linea_editar['nombre'], MB_CASE_TITLE, 'UTF-8')) : '' ?>" required placeholder="Ej: Redes y Telecomunicaciones">
                </div>
                <div class="li-form-group">
                    <label>Programa de Formación (Carrera)</label>
                    <select name="id_carrera" required>
                        <option value="">-- Seleccione un PNF --</option>
                        <?php foreach($carreras as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($linea_editar && $linea_editar['id_carrera'] == $c['id']) ? 'selected' : '' ?>>
                                PNF en <?= htmlspecialchars(mb_convert_case($c['nombre'], MB_CASE_TITLE, 'UTF-8')) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="li-form-group">
                <label>Descripción</label>
                <textarea name="descripcion" rows="3" required placeholder="Breve descripción..."><?= $linea_editar ? htmlspecialchars($linea_editar['descripcion']) : '' ?></textarea>
            </div>
            
            <div style="display:flex; gap:1rem; align-items:center;">
                <button type="submit" class="btn-primary-li">
                    <i class="ph-bold <?= $linea_editar ? 'ph-floppy-disk' : 'ph-plus' ?>"></i> 
                    <?= $linea_editar ? 'Guardar Cambios' : 'Registrar Línea' ?>
                </button>
                <?php if ($linea_editar): ?>
                    <a href="index.php?ruta=gestionar-lineas" style="color:#64748b; text-decoration:none; font-weight:600;">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>


    <!-- GRID DE TARJETAS DE LÍNEAS -->
    <div class="ag-modules-grid">
        <?php foreach($lineas as $li): ?>
            <div class="ag-card">
                <!-- Info de la Linea -->
                <div style="display: flex; gap: 1.1rem; align-items: flex-start;">
                    <div class="ag-card-icon">
                        <i class="ph-fill ph-graph"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.5rem; margin-bottom: 0.3rem;">
                            <h3 style="color: var(--texto-titulos, #0f172a); font-weight: 800; font-size: 1.1rem; margin: 0; line-height: 1.3;">
                                <?= htmlspecialchars(mb_convert_case($li['nombre'], MB_CASE_TITLE, 'UTF-8')) ?>
                            </h3>
                            <!-- Acciones Rápidas -->
                            <div style="display:flex; gap:4px; flex-shrink:0;">
                                <a href="index.php?ruta=gestionar-lineas&editar=<?= urlencode($li['id']) ?>" class="ag-btn-edit" title="Editar Línea">
                                    <i class="ph-bold ph-pencil-simple"></i>
                                </a>
                                <button type="button" class="ag-btn-delete" title="Eliminar Línea" onclick="eliminarLinea(<?= htmlspecialchars($li['id']) ?>, '<?= htmlspecialchars(addslashes($li['nombre'])) ?>')">
                                    <i class="ph-bold ph-trash"></i>
                                </button>
                            </div>
                        </div>
                        <p style="color: var(--texto-silenciado, #64748b); font-size: 0.85rem; margin: 0 0 0.5rem 0; line-height: 1.45;">
                            <?= htmlspecialchars($li['descripcion']) ?>
                        </p>
                        <span style="background: rgba(80, 89, 132, 0.08); padding: 4px 10px; border-radius: 8px; font-size: 0.72rem; font-weight: 800; color: var(--color-primario);">
                            PNF EN <?= htmlspecialchars(mb_convert_case($li['carrera_nombre'] ?? 'Sin Asignar', MB_CASE_UPPER, 'UTF-8')) ?>
                        </span>
                    </div>
                </div>

                <!-- Botón de Expandir Dimensiones -->
                <div style="border-top: 1px solid rgba(80, 89, 132, 0.1); padding-top: 1rem; text-align: center;">
                    <?php 
                        // Filtrar dimensiones de esta linea
                        $dims_de_linea = array_filter($dimensiones, function($d) use ($li) {
                            return $d['id_linea'] == $li['id'];
                        });
                        $countDims = count($dims_de_linea);
                    ?>
                    <button type="button" class="ag-btn-manage" onclick="toggleDimensiones(<?= htmlspecialchars($li['id']) ?>)" style="width: 100%; justify-content: center;">
                        <i class="ph-bold ph-squares-four"></i> Gestionar Dimensiones (<?= $countDims ?>)
                    </button>
                </div>

                <!-- Contenedor Desplegable de Dimensiones -->
                <div id="dim-container-<?= htmlspecialchars($li['id']) ?>" class="ag-dimensions-container">
                    
                    <?php if ($countDims > 0): ?>
                        <?php foreach($dims_de_linea as $dim): ?>
                            <div class="ag-dim-item">
                                <div>
                                    <div class="ag-dim-title"><?= htmlspecialchars(mb_convert_case($dim['nombre'], MB_CASE_TITLE, 'UTF-8')) ?></div>
                                    <div style="font-size: 0.75rem; color:#64748b;"><?= htmlspecialchars(mb_substr($dim['descripcion'], 0, 50)) ?>...</div>
                                </div>
                                <div style="display:flex; gap:4px; flex-shrink:0;">
                                    <button type="button" class="ag-btn-edit" onclick="abrirModalEditarDimension(<?= htmlspecialchars(json_encode($dim)) ?>)"><i class="ph-bold ph-pencil-simple"></i></button>
                                    <button type="button" class="ag-btn-delete" onclick="eliminarDimension(<?= htmlspecialchars($dim['id']) ?>, '<?= htmlspecialchars(addslashes($dim['nombre'])) ?>')"><i class="ph-bold ph-trash"></i></button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="text-align:center; font-size:0.8rem; color:#94a3b8; margin-bottom:1rem;">No hay dimensiones operativas en esta línea.</p>
                    <?php endif; ?>

                    <button type="button" onclick="abrirModalCrearDimension(<?= htmlspecialchars($li['id']) ?>, '<?= htmlspecialchars(addslashes($li['nombre'])) ?>')" style="width:100%; background: #f8fafc; border: 1px dashed #cbd5e1; color: #475569; padding: 8px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s;">
                        <i class="ph-bold ph-plus"></i> Añadir Dimensión Operativa
                    </button>
                </div>

            </div>
        <?php endforeach; ?>
    </div>

</div>

<!-- Formularios Ocultos para Eliminación -->
<form id="formEliminarLinea" method="POST" action="index.php?ruta=gestionar-lineas" style="display:none;">
    <input type="hidden" name="accion" value="delete">
    <input type="hidden" name="id" id="deleteLineaId" value="">
</form>
<form id="formEliminarDimension" method="POST" action="index.php?ruta=gestionar-dimensiones" style="display:none;">
    <input type="hidden" name="accion" value="delete">
    <input type="hidden" name="id" id="deleteDimensionId" value="">
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function toggleDimensiones(id) {
    const container = document.getElementById('dim-container-' + id);
    if (container.style.display === 'block') {
        container.style.display = 'none';
    } else {
        container.style.display = 'block';
    }
}

function eliminarLinea(id, nombre) {
    Swal.fire({
        title: '¿Eliminar Línea?',
        html: `Estás a punto de eliminar <strong>${nombre}</strong>.<br>Esta acción también eliminará todas sus dimensiones operativas.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteLineaId').value = id;
            document.getElementById('formEliminarLinea').submit();
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

function abrirModalCrearDimension(idLinea, nombreLinea) {
    Swal.fire({
        title: 'Añadir Dimensión',
        html: `
            <div style="text-align: left;">
                <p style="margin-top:0; color:#64748b; font-size:0.9rem;">Línea: <strong>${nombreLinea}</strong></p>
                <form id="form-create-dim" method="POST" action="index.php?ruta=gestionar-dimensiones">
                    <input type="hidden" name="accion" value="create">
                    <input type="hidden" name="id_linea" value="${idLinea}">
                    
                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-weight:bold; margin-bottom:0.3rem;">Nombre</label>
                        <input type="text" name="nombre" class="swal2-input" style="width:90%; margin:0;" required placeholder="Nombre de la dimensión...">
                    </div>
                    <div>
                        <label style="display:block; font-weight:bold; margin-bottom:0.3rem;">Descripción</label>
                        <textarea name="descripcion" class="swal2-textarea" style="width:90%; margin:0; height:80px;" required placeholder="Descripción..."></textarea>
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
                    <input type="hidden" name="accion" value="edit">
                    <input type="hidden" name="id" value="${dim.id}">
                    <input type="hidden" name="id_linea" value="${dim.id_linea}">
                    
                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-weight:bold; margin-bottom:0.3rem;">Nombre</label>
                        <input type="text" name="nombre" class="swal2-input" style="width:90%; margin:0;" required value="${dim.nombre.replace(/"/g, '&quot;')}">
                    </div>
                    <div>
                        <label style="display:block; font-weight:bold; margin-bottom:0.3rem;">Descripción</label>
                        <textarea name="descripcion" class="swal2-textarea" style="width:90%; margin:0; height:80px;" required>${dim.descripcion}</textarea>
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
