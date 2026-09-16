<?php
// modules/LineasInvestigacion/views/gestor_lineas.php
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
    justify-content: center;
    gap: 6px;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
    width: 100%;
}
.ag-btn-manage:hover {
    background: var(--color-primario);
    color: white;
    box-shadow: 0 4px 12px rgba(18, 26, 62, 0.15);
}
.ag-btn-delete {
    color: #ef4444;
    background: rgba(239, 68, 68, 0.1);
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.2s;
    font-weight: bold;
    font-size: 0.8rem;
    display: inline-flex;
    align-items: center;
    gap: 4px;
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

    <!-- ENCABEZADO PRINCIPAL GLASSMORPHIC -->
    <div class="ag-header-banner">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.2rem;">
            <div>
                <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-terciario); font-weight: 800; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 0.3rem;">
                    <i class="ph-bold ph-squares-four"></i> SUBSISTEMAS DEL SISTEMA INTEGRAL
                </div>
                <h1 style="font-size: 1.85rem; font-weight: 800; color: var(--texto-titulos, #0f172a); margin: 0;">
                    Gestor de Líneas de Investigación
                </h1>
                <p style="margin: 0.3rem 0 0 0; color: var(--texto-silenciado, #64748b); font-size: 0.9rem;">
                    Seleccione una línea para gestionar sus dimensiones operativas y configuraciones específicas.
                </p>
            </div>
            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <button type="button" onclick="abrirModalCrearLinea()" class="btn btn-outline" style="border-color: #059669; color: #059669; background: #ffffff; text-decoration: none; padding: 8px 14px; border-radius: 8px; font-weight: 700; font-size: 0.83rem; display: inline-flex; align-items: center; gap: 6px; cursor:pointer;">
                    <i class="ph-bold ph-plus-circle"></i> Nueva Línea
                </button>
                <a href="index.php?ruta=lineas-investigacion" class="btn btn-outline" style="border-color: var(--color-secundario); color: var(--color-secundario); background: #ffffff; text-decoration: none; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; transition: all 0.2s ease;">
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
                        <h3 style="color: var(--texto-titulos, #0f172a); font-weight: 800; font-size: 1.1rem; margin: 0 0 0.3rem 0; line-height: 1.3;">
                            <?= htmlspecialchars(mb_convert_case($li['nombre'], MB_CASE_TITLE, 'UTF-8')) ?>
                        </h3>
                        <p style="color: var(--texto-silenciado, #64748b); font-size: 0.85rem; margin: 0 0 0.8rem 0; line-height: 1.45;">
                            <?= htmlspecialchars(mb_substr($li['descripcion'], 0, 90)) ?>...
                        </p>
                        <span style="background: rgba(80, 89, 132, 0.08); padding: 4px 10px; border-radius: 8px; font-size: 0.72rem; font-weight: 800; color: var(--color-primario);">
                            PNF EN <?= htmlspecialchars(mb_convert_case($li['carrera_nombre'] ?? 'Sin Asignar', MB_CASE_UPPER, 'UTF-8')) ?>
                        </span>
                    </div>
                </div>

                <!-- Botón de Expandir Dimensiones -->
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; border-top: 1px solid rgba(80, 89, 132, 0.1); padding-top: 1rem;">
                    
                    <button type="button" class="ag-btn-delete" title="Eliminar Línea" onclick="eliminarLinea(<?= htmlspecialchars($li['id']) ?>, '<?= htmlspecialchars(addslashes($li['nombre'])) ?>')">
                        <i class="ph-bold ph-trash"></i>
                    </button>

                    <a href="index.php?ruta=detalle-gestion-linea&id=<?= urlencode($li['id']) ?>" class="ag-btn-manage" title="Abrir gestor dedicado de la línea">
                        <i class="ph-bold ph-gear-six"></i> Gestionar Línea
                    </a>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Obtener el JSON de carreras para el select
const carrerasList = <?= json_encode($carreras) ?>;

function eliminarLinea(id, nombre) {
    Swal.fire({
        title: '¿Eliminar Línea?',
        html: `Estás a punto de eliminar la línea <strong>${nombre}</strong>.<br><br><span style="color:#ef4444; font-weight:bold;">¡ADVERTENCIA!</span> Esta acción eliminará también todas sus dimensiones operativas asociadas.`,
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

function abrirModalCrearLinea() {
    let selectOptions = '<option value="">-- Seleccione un PNF --</option>';
    carrerasList.forEach(c => {
        selectOptions += `<option value="${c.id}">PNF en ${c.nombre.toUpperCase()}</option>`;
    });

    Swal.fire({
        title: 'Nueva Línea de Investigación',
        html: `
            <div style="text-align: left;">
                <form id="form-create-linea" method="POST" action="index.php?ruta=gestionar-lineas">
                    <input type="hidden" name="accion" value="crear">
                    
                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-weight:bold; margin-bottom:0.3rem;">Nombre de la Línea</label>
                        <input type="text" name="nombre" class="swal2-input" style="width:90%; margin:0;" required placeholder="Ej: Redes y Telecomunicaciones">
                    </div>
                    
                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-weight:bold; margin-bottom:0.3rem;">Programa de Formación</label>
                        <select name="id_carrera" class="swal2-select" style="width:90%; margin:0; padding:10px; border-radius:8px;" required>
                            ${selectOptions}
                        </select>
                    </div>

                    <div>
                        <label style="display:block; font-weight:bold; margin-bottom:0.3rem;">Descripción</label>
                        <textarea name="descripcion" class="swal2-textarea" style="width:90%; margin:0; height:80px;" required placeholder="Breve descripción de la línea..."></textarea>
                    </div>
                </form>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="ph-bold ph-floppy-disk"></i> Registrar Línea',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const form = document.getElementById('form-create-linea');
            if (!form.nombre.value || !form.id_carrera.value || !form.descripcion.value) {
                Swal.showValidationMessage('Todos los campos son obligatorios');
                return false;
            }
            form.submit();
        }
    });
}
</script>
