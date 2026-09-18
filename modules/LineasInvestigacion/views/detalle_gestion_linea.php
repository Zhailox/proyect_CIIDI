<?php
// modules/LineasInvestigacion/views/detalle_gestion_linea.php
?>



    <!-- BREADCRUMBS -->
    <div style="margin-bottom: 1rem; font-size: 0.85rem; font-weight: 600;">
        <a href="index.php?ruta=dashboard" style="color: var(--text-muted); text-decoration: none;"><i class="ph-bold ph-squares-four"></i> Dashboard</a>
        <span style="color: #cbd5e1; margin: 0 0.5rem;">/</span>
        <a href="index.php?ruta=gestionar-lineas" style="color: var(--text-muted); text-decoration: none;">Gestión de Líneas</a>
        <span style="color: #cbd5e1; margin: 0 0.5rem;">/</span>
        <span style="color: var(--li-indigo);">Detalle</span>
    </div>
    <div class="li-gestor-wrapper">

    <!-- ENCABEZADO -->
    <div class="ag-header-banner">
        <canvas id="li-nodes-canvas" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 0; opacity: 0.6;"></canvas>
        <div style="display: flex; gap: 1.8rem; align-items: flex-start; position: relative; z-index: 1;">
            <div style="width: 72px; height: 72px; border-radius: 16px; background: rgba(255,255,255, 0.1); border: 1px solid rgba(255,255,255, 0.2); display: flex; align-items: center; justify-content: center; font-size: 2.2rem; color: #ffffff; flex-shrink: 0; backdrop-filter: blur(4px);">
                <i class="ph-bold ph-graph"></i>
            </div>
            <div style="flex: 1;">
                <div class="ag-header-subtitle">
                    GESTOR DEDICADO DE LÍNEA • PNF EN <?= htmlspecialchars(mb_convert_case($linea['carrera_nombre'] ?? 'Sin Asignar', MB_CASE_UPPER, 'UTF-8')) ?>
                </div>
                <h1 class="ag-header-title">
                    <?= htmlspecialchars(mb_convert_case($linea['nombre'], MB_CASE_TITLE, 'UTF-8')) ?>
                </h1>
                <p class="ag-header-desc">
                    <?= htmlspecialchars($linea['descripcion']) ?>
                </p>
                
                <div style="display: flex; align-items: center; gap: 12px; margin-top: 1.5rem;">
                    <button type="button" onclick="abrirModalEditarLinea(<?= htmlspecialchars(json_encode($linea)) ?>)" style="background: rgba(255,255,255,0.15); color: #ffffff; border: 1px solid rgba(255,255,255,0.3); padding: 10px 20px; border-radius: 8px; font-weight: 700; font-size: 0.9rem; cursor: pointer; transition: 0.2s; display: inline-flex; align-items: center; gap: 6px;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                        <i class="ph-bold ph-pencil-simple"></i> Editar Línea
                    </button>
                    <a href="index.php?ruta=gestionar-lineas" style="background: rgba(255,255,255,0.1); color: #ffffff; border: 1px solid rgba(255,255,255,0.2); padding: 10px 20px; border-radius: 8px; font-weight: 700; font-size: 0.9rem; text-decoration: none; transition: 0.2s; display: inline-flex; align-items: center; gap: 6px;" onmouseover="this.style.background=\'rgba(255,255,255,0.2)\'" onmouseout="this.style.background=\'rgba(255,255,255,0.1)\'">
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
        <div class="ag-kpi-card" style="border-left-color: var(--color-secundario, #059669);">
            <div class="ag-kpi-label">Proyectos Vinculados</div>
            <div class="ag-kpi-value" style="color:var(--color-secundario, #059669);">
                <?= htmlspecialchars($linea['total_proyectos'] ?? 0) ?>
            </div>
        </div>
    </div>

    <!-- LISTA DE DIMENSIONES -->
    <div class="ag-list-container">
        
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:2px solid #e2e8f0; padding-bottom:1.2rem; margin-bottom:1.8rem;">
            <div>
                <h2 style="margin:0 0 0.3rem 0; font-size:1.4rem; color:var(--texto-titulos, #0f172a); font-weight: 800;"><i class="ph-bold ph-squares-four"></i> Dimensiones Operativas (Componentes)</h2>
                <p style="margin:0; font-size:0.95rem; color:var(--texto-silenciado, #64748b);">Gestione las dimensiones asociadas a esta línea de investigación.</p>
            </div>
            <button type="button" onclick="abrirModalCrearDimension(<?= htmlspecialchars($linea['id']) ?>)" style="background: #505984; color: #ffffff; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: 0.2s; box-shadow: 0 4px 12px rgba(80,89,132,0.3);" onmouseover="this.style.background='#3C456A'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#505984'; this.style.transform='translateY(0)'">
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
                    <div style="display:flex; gap:10px; margin-left:2rem;">
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
            <div style="text-align:center; padding: 4rem 0;">
                <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
                    <i class="ph-bold ph-ghost" style="font-size:2.5rem; color:#94a3b8;"></i>
                </div>
                <h3 style="color:#475569; margin:0 0 0.5rem 0; font-size: 1.2rem;">No hay dimensiones operativas</h3>
                <p style="color:#64748b; font-size:0.95rem;">Comience añadiendo la primera dimensión operativa para esta línea.</p>
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
    Swal.fire({
        title: 'Editar Línea de Investigación',
        customClass: { popup: 'ag-swal-popup' },
        html: `
            <div style="text-align: left; margin-top: 1rem;">
                <form id="form-edit-linea" method="POST" action="index.php?ruta=gestionar-lineas">
                    <input type="hidden" name="accion" value="editar">
                    <input type="hidden" name="id" value="${linea.id}">
                    <input type="hidden" name="id_carrera" value="${linea.id_carrera}">
                    <input type="hidden" name="redirect_to" value="detalle-gestion-linea&id=${linea.id}">
                    
                    <div style="margin-bottom:1.2rem;">
                        <label class="ag-modal-label">Nombre</label>
                        <input type="text" name="nombre" class="ag-modal-input" required value="${linea.nombre.replace(/"/g, '&quot;')}">
                    </div>
                    <div>
                        <label class="ag-modal-label">Descripción</label>
                        <textarea name="descripcion" class="ag-modal-input" style="height:220px; resize:vertical;" required>${linea.descripcion}</textarea>
                    </div>
                </form>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="ph-bold ph-floppy-disk"></i> Guardar Cambios',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#505984',
        cancelButtonColor: '#94a3b8',
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
        html: `Estás a punto de eliminar <strong>${nombre}</strong>. ¿Confirmas esta acción?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: { popup: 'ag-swal-popup' }
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
        customClass: { popup: 'ag-swal-popup' },
        html: `
            <div style="text-align: left; margin-top: 1rem;">
                <form id="form-create-dim" method="POST" action="index.php?ruta=gestionar-dimensiones">
                    <input type="hidden" name="accion" value="crear">
                    <input type="hidden" name="id_linea" value="${idLinea}">
                    <input type="hidden" name="redirect_to" value="detalle-gestion-linea&id=${idLinea}">
                    
                    <div style="margin-bottom:1.2rem;">
                        <label class="ag-modal-label">Nombre de la Dimensión</label>
                        <input type="text" name="nombre" class="ag-modal-input" required placeholder="Ej: Infraestructura Tecnológica">
                    </div>
                    <div>
                        <label class="ag-modal-label">Descripción</label>
                        <textarea name="descripcion" class="ag-modal-input" style="height:220px; resize:vertical;" required placeholder="Detalla el enfoque de la dimensión..."></textarea>
                    </div>
                </form>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="ph-bold ph-floppy-disk"></i> Guardar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#505984',
        cancelButtonColor: '#94a3b8',
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
        customClass: { popup: 'ag-swal-popup' },
        html: `
            <div style="text-align: left; margin-top: 1rem;">
                <form id="form-edit-dim" method="POST" action="index.php?ruta=gestionar-dimensiones">
                    <input type="hidden" name="accion" value="editar">
                    <input type="hidden" name="id" value="${dim.id}">
                    <input type="hidden" name="id_linea" value="${dim.id_linea}">
                    <input type="hidden" name="redirect_to" value="detalle-gestion-linea&id=${dim.id_linea}">
                    
                    <div style="margin-bottom:1.2rem;">
                        <label class="ag-modal-label">Nombre</label>
                        <input type="text" name="nombre" class="ag-modal-input" required value="${dim.nombre.replace(/"/g, '&quot;')}">
                    </div>
                    <div>
                        <label class="ag-modal-label">Descripción</label>
                        <textarea name="descripcion" class="ag-modal-input" style="height:220px; resize:vertical;" required>${dim.descripcion}</textarea>
                    </div>
                </form>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="ph-bold ph-floppy-disk"></i> Guardar Cambios',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#505984',
        cancelButtonColor: '#94a3b8',
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
