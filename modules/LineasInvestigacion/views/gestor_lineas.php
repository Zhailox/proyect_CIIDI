<?php
// modules/LineasInvestigacion/views/gestor_lineas.php
?>

<style>
/* CLASES PARA VISTA TIPO LISTA */
.ag-view-list {
    grid-template-columns: 1fr !important;
}
.ag-view-list .ag-card {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
    padding: 1.5rem 2rem;
}
.ag-view-list .ag-card > div:first-child {
    flex: 1;
}
.ag-view-list .ag-card > div:last-child {
    border-top: none !important;
    padding-top: 0 !important;
    border-left: 1px solid rgba(80, 89, 132, 0.1);
    padding-left: 1.5rem;
    flex-direction: row-reverse;
    justify-content: flex-start;
}
.ag-view-list .ag-btn-manage {
    width: auto !important;
}
</style>

<style>
.ag-header-banner {
    background: linear-gradient(135deg, rgba(80, 89, 132, 0.95) 0%, rgba(30, 41, 59, 0.98) 100%) !important;
    color: #ffffff;
    border-radius: 14px;
    padding: 2rem 2.5rem;
    box-shadow: 0 16px 36px rgba(15, 23, 42, 0.15);
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}
.ag-header-banner::before {
    content: '';
    position: absolute;
    top: -50%; right: -10%;
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    border-radius: 50%;
}
.ag-header-subtitle {
    display: inline-flex; 
    align-items: center; 
    gap: 0.5rem; 
    color: #94a3b8; 
    font-weight: 800; 
    font-size: 0.8rem; 
    text-transform: uppercase; 
    letter-spacing: 1.5px; 
    margin-bottom: 0.5rem;
}
.ag-header-title {
    font-size: 2rem; 
    font-weight: 800; 
    margin: 0; 
    color: #ffffff;
    letter-spacing: -0.5px;
}
.ag-header-desc {
    margin: 0.5rem 0 0 0; 
    color: #cbd5e1; 
    font-size: 1rem;
    max-width: 600px;
}

.ag-modules-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}
.ag-card {
    background: #ffffff;
    border: 1px solid rgba(80, 89, 132, 0.15);
    border-top: 4px solid #505984;
    border-radius: 12px;
    padding: 1.8rem;
    box-shadow: 0 10px 25px rgba(18, 26, 62, 0.03);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 1.5rem;
}
.ag-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(18, 26, 62, 0.08);
    border-color: rgba(80, 89, 132, 0.25);
}
.ag-card-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    background: rgba(80, 89, 132, 0.08);
    border: 1px solid rgba(80, 89, 132, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: #505984;
}
.ag-btn-manage {
    background: #505984;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    padding: 10px 16px;
    font-weight: 700;
    font-size: 0.9rem;
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
    background: #3C456A;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(80, 89, 132, 0.3);
}
.ag-btn-delete {
    color: #ef4444;
    background: rgba(239, 68, 68, 0.1);
    border: none;
    padding: 8px 14px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.2s;
    font-weight: bold;
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

/* Custom Modal Styles */
.ag-modal-label {
    display: block;
    font-weight: 700;
    margin-bottom: 0.4rem;
    color: #475569;
    font-size: 0.9rem;
}
.ag-modal-input {
    width: 100%;
    box-sizing: border-box;
    padding: 0.75rem 1rem;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-family: inherit;
    font-size: 0.95rem;
    transition: all 0.2s;
    background: #f8fafc;
}
.ag-modal-input:focus {
    outline: none;
    border-color: #505984;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(80, 89, 132, 0.2);
}
.ag-swal-popup {
    border-radius: 16px !important;
    padding: 1.5rem !important;
}
</style>

<div class="li-gestor-wrapper">

    <!-- ENCABEZADO PRINCIPAL (COLORES DEL CORE) -->
    <div class="ag-header-banner">
        <canvas id="li-nodes-canvas" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 0; opacity: 0.6;"></canvas>
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.2rem; position:relative; z-index: 1;">
            <div>
                <div class="ag-header-subtitle">
                    <i class="ph-bold ph-squares-four"></i> SUBSISTEMAS DEL SISTEMA INTEGRAL
                </div>
                <h1 class="ag-header-title">Gestor de Líneas de Investigación</h1>
                <p class="ag-header-desc">
                    Gestione las líneas, asocie dimensiones operativas internamente y controle su configuración.
                </p>
            </div>
            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <button type="button" onclick="abrirModalCrearLinea()" style="background: #ffffff; color: #0f172a; padding: 10px 18px; border: none; border-radius: 8px; font-weight: 800; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px; cursor:pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.15); transition: 0.2s;" onmouseover="this.style.background=\'#f1f5f9\'; this.style.transform=\'translateY(-2px)\'" onmouseout="this.style.background=\'#ffffff\'; this.style.transform=\'translateY(0)\'">
                    <i class="ph-bold ph-plus-circle"></i> Nueva Línea
                </button>
                <a href="index.php?ruta=lineas-investigacion" style="border: 1px solid rgba(255,255,255,0.3); color: #ffffff; background: rgba(255,255,255,0.1); text-decoration: none; padding: 10px 18px; border-radius: 8px; font-weight: 700; font-size: 0.9rem; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 6px;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
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
    
    <!-- BARRA DE CONTROLES (VISTA) -->
    <div style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
        <div style="background: #e2e8f0; padding: 4px; border-radius: 8px; display: inline-flex; gap: 4px;">
            <button type="button" id="btnViewGrid" title="Vista Cuadrícula" onclick="setLineaViewMode('grid')" style="border:none; padding: 6px 12px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 6px; font-weight: 600; font-size: 0.85rem; transition: 0.2s; background: #ffffff; color: #1e293b; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"><i class="ph-bold ph-squares-four" style="font-size: 1.1rem;"></i></button>
            <button type="button" id="btnViewList" title="Vista Lista" onclick="setLineaViewMode('list')" style="border:none; padding: 6px 12px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 6px; font-weight: 600; font-size: 0.85rem; transition: 0.2s; background: transparent; color: #64748b;"><i class="ph-bold ph-list" style="font-size: 1.1rem;"></i></button>
        </div>
    </div>

<div class="ag-modules-grid">
        <?php foreach($lineas as $li): ?>
            <div class="ag-card">
                <!-- Info de la Linea -->
                <div style="display: flex; gap: 1.1rem; align-items: flex-start;">
                    <div class="ag-card-icon">
                        <i class="ph-fill ph-graph"></i>
                    </div>
                    <div style="flex: 1;">
                        <h3 style="color: var(--texto-titulos, #0f172a); font-weight: 800; font-size: 1.15rem; margin: 0 0 0.4rem 0; line-height: 1.3;">
                            <?= htmlspecialchars(mb_convert_case($li['nombre'], MB_CASE_TITLE, 'UTF-8')) ?>
                        </h3>
                        <p style="color: var(--texto-silenciado, #64748b); font-size: 0.9rem; margin: 0 0 1rem 0; line-height: 1.5;">
                            <?= htmlspecialchars(mb_substr($li['descripcion'], 0, 100)) ?>...
                        </p>
                        <span style="background: rgba(80, 89, 132, 0.08); padding: 5px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 800; color: var(--color-primario, #1e293b);">
                            PNF EN <?= htmlspecialchars(mb_convert_case($li['carrera_nombre'] ?? 'Sin Asignar', MB_CASE_UPPER, 'UTF-8')) ?>
                        </span>
                    </div>
                </div>

                <!-- Botones Inferiores -->
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; border-top: 1px solid rgba(80, 89, 132, 0.1); padding-top: 1.2rem;">
                    
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

<!-- Formularios Ocultos -->
<form id="formEliminarLinea" method="POST" action="index.php?ruta=gestionar-lineas" style="display:none;">
    <input type="hidden" name="accion" value="eliminar">
    <input type="hidden" name="id" id="deleteLineaId" value="">
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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
        cancelButtonText: 'Cancelar',
        customClass: { popup: 'ag-swal-popup' }
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
        customClass: { popup: 'ag-swal-popup' },
        html: `
            <div style="text-align: left; margin-top: 1rem;">
                <form id="form-create-linea" method="POST" action="index.php?ruta=gestionar-lineas">
                    <input type="hidden" name="accion" value="crear">
                    
                    <div style="margin-bottom:1.2rem;">
                        <label class="ag-modal-label">Nombre de la Línea</label>
                        <input type="text" name="nombre" class="ag-modal-input" required placeholder="Ej: Redes y Telecomunicaciones">
                    </div>
                    
                    <div style="margin-bottom:1.2rem;">
                        <label class="ag-modal-label">Programa de Formación</label>
                        <select name="id_carrera" class="ag-modal-input" required>
                            ${selectOptions}
                        </select>
                    </div>

                    <div>
                        <label class="ag-modal-label">Descripción</label>
                        <textarea name="descripcion" class="ag-modal-input" style="height:100px; resize:none;" required placeholder="Breve descripción de la línea..."></textarea>
                    </div>
                </form>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="ph-bold ph-floppy-disk"></i> Registrar Línea',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#505984',
        cancelButtonColor: '#94a3b8',
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('li-nodes-canvas');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let width = canvas.width = canvas.offsetWidth;
        let height = canvas.height = canvas.offsetHeight;
        
        window.addEventListener('resize', () => {
            if (!canvas) return;
            width = canvas.width = canvas.offsetWidth;
            height = canvas.height = canvas.offsetHeight;
        });

        const particles = [];
        const numParticles = 40;

        for (let i = 0; i < numParticles; i++) {
            particles.push({
                x: Math.random() * width,
                y: Math.random() * height,
                vx: (Math.random() - 0.5) * 0.5,
                vy: (Math.random() - 0.5) * 0.5,
                radius: Math.random() * 2 + 1.2
            });
        }

        function animate() {
            ctx.clearRect(0, 0, width, height);

            for (let i = 0; i < numParticles; i++) {
                const p = particles[i];
                p.x += p.vx;
                p.y += p.vy;

                if (p.x < 0 || p.x > width) p.vx *= -1;
                if (p.y < 0 || p.y > height) p.vy *= -1;

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(112, 144, 203, 0.75)';
                ctx.fill();

                for (let j = i + 1; j < numParticles; j++) {
                    const p2 = particles[j];
                    const dx = p.x - p2.x;
                    const dy = p.y - p2.y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    
                    if (dist < 100) {
                        ctx.beginPath();
                        ctx.moveTo(p.x, p.y);
                        ctx.lineTo(p2.x, p2.y);
                        ctx.strokeStyle = `rgba(112, 144, 203, ${0.4 - dist/250})`;
                        ctx.lineWidth = 0.8;
                        ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(animate);
        }
        animate();
    }
});
</script>
</body>
