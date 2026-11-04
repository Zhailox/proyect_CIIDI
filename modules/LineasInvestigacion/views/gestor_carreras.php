<?php require_once __DIR__ . '/../../../core/Security/CSRF.php'; ?>
<?php
// modules/LineasInvestigacion/views/gestor_carreras.php
$isSuper = ($_SESSION['nivel_privilegio'] ?? 999) === 0;
?>

<div class="li-gestor-wrapper">

<style>
.ag-gestor-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 0;
    overflow-x: auto;
}
.ag-gestor-tabs a {
    padding: 0.75rem 1.5rem;
    text-decoration: none;
    color: #64748b;
    font-weight: 700;
    font-size: 0.95rem;
    border-bottom: 3px solid transparent;
    transition: all 0.2s;
    border-radius: 8px 8px 0 0;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 6px;
}
.ag-gestor-tabs a:hover {
    color: #0f172a;
    background: rgba(241, 245, 249, 0.5);
}
.ag-gestor-tabs a.active {
    color: #2b3453;
    border-bottom-color: #2b3453;
    background: #ffffff;
}

/* Fix for buttons wrapping nicely */
.ag-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
    position: relative;
    z-index: 1;
}
.ag-header-left {
    flex: 1 1 300px;
}
.ag-header-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 12px;
    z-index: 1;
    flex: 1 1 100%;
}
@media (min-width: 900px) {
    .ag-header-right {
        flex: 0 0 auto;
        align-items: flex-end;
    }
}
@media (max-width: 899px) {
    .ag-header-right {
        align-items: flex-start;
    }
}
</style>

<div class="ag-gestor-tabs">
    <a href="index.php?ruta=gestionar-carreras" class="<?= ($_GET['ruta']??'') == 'gestionar-carreras' ? 'active' : '' ?>"><i class="ph-bold ph-graduation-cap"></i> PNFs (Carreras)</a>
    <a href="index.php?ruta=gestionar-lineas" class="<?= ($_GET['ruta']??'') == 'gestionar-lineas' ? 'active' : '' ?>"><i class="ph-bold ph-graph"></i> Líneas de Investigación</a>
    <a href="index.php?ruta=gestionar-dimensiones" class="<?= ($_GET['ruta']??'') == 'gestionar-dimensiones' ? 'active' : '' ?>"><i class="ph-bold ph-squares-four"></i> Dimensiones</a>
</div>


    <!-- ENCABEZADO PRINCIPAL (COLORES DEL CORE) -->
    <div class="ag-header-banner">
        <canvas id="li-nodes-canvas" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 0; opacity: 0.6;"></canvas>
        <div class="ag-header-content">
            <div class="ag-header-left">
                <div class="ag-header-subtitle">
                    <i class="ph-bold ph-graduation-cap"></i> ESTRUCTURA ACADÉMICA
                </div>
                <h1 class="ag-header-title">Gestor de Programas de Formación (PNF)</h1>
                <p class="ag-header-desc">
                    Gestione las carreras o programas nacionales de formación para clasificar las líneas de investigación.
                </p>
            </div>
            <div class="ag-header-right">
                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">

                <button type="button" onclick="abrirModalExportarPDF()" style="background: #ef4444; color: #ffffff; padding: 10px 18px; border: none; border-radius: 8px; font-weight: 800; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px; cursor:pointer; box-shadow: 0 4px 10px rgba(239,68,68,0.25); transition: 0.2s;" onmouseover="this.style.background='#dc2626'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#ef4444'; this.style.transform='translateY(0)'">
                    <i class="ph-bold ph-file-pdf"></i> Exportar PDF
                </button>

                    <button type="button" onclick="abrirModalCrearCarrera()" style="background: #ffffff; color: #0f172a; padding: 10px 18px; border: none; border-radius: 8px; font-weight: 800; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px; cursor:pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.15); transition: 0.2s;" onmouseover="this.style.background='#f1f5f9'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#ffffff'; this.style.transform='translateY(0)'">
                        <i class="ph-bold ph-plus-circle"></i> Nuevo PNF
                    </button>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- ALERTA FLASH -->
    <?php if (!empty($mensaje)): ?>
    <div style="margin: 1.5rem 2rem 0; padding: 1rem 1.5rem; border-radius: 8px; font-weight: 700; display: flex; align-items: center; gap: 0.75rem; 
        <?= $tipo_mensaje === 'exito' ? 'background: #ecfdf5; color: #065f46; border: 1px solid #10b981;' : 'background: #fef2f2; color: #991b1b; border: 1px solid #ef4444;' ?>">
        <i class="ph-bold <?= $tipo_mensaje === 'exito' ? 'ph-check-circle' : 'ph-warning-circle' ?>" style="font-size: 1.4rem;"></i>
        <?= htmlspecialchars($mensaje) ?>
    </div>
    <?php endif; ?>

    <!-- TABLA DE CARRERAS -->
    <div style="padding: 2rem;">
        <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.03); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <tr>
                        <th style="padding: 1.2rem; font-weight: 800; font-size: 0.85rem; color: #64748b; text-transform: uppercase;">ID</th>
                        <th style="padding: 1.2rem; font-weight: 800; font-size: 0.85rem; color: #64748b; text-transform: uppercase;">Programa de Formación (PNF)</th>
                        <th style="padding: 1.2rem; font-weight: 800; font-size: 0.85rem; color: #64748b; text-transform: uppercase;">Descripción</th>
                        <th style="padding: 1.2rem; font-weight: 800; font-size: 0.85rem; color: #64748b; text-transform: uppercase; text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($carreras)): ?>
                    <tr>
                        <td colspan="4" style="padding: 3rem; text-align: center; color: #94a3b8; font-weight: 600;">
                            <i class="ph-bold ph-graduation-cap" style="font-size: 3rem; opacity: 0.3; margin-bottom: 0.5rem; display: block;"></i>
                            No hay programas de formación registrados.
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($carreras as $c): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 1.2rem; color: #64748b; font-weight: 700;">#<?= $c['id'] ?></td>
                            <td style="padding: 1.2rem;">
                                <div style="font-weight: 800; color: #1e293b; font-size: 0.95rem; display: flex; align-items: center; gap: 8px;">
                                    <i class="ph-bold ph-student" style="color: #505984;"></i> <?= htmlspecialchars($c['nombre']) ?>
                                </div>
                            </td>
                            <td style="padding: 1.2rem; color: #475569; font-size: 0.85rem; max-width: 300px;">
                                <?= htmlspecialchars($c['descripcion'] ?: 'Sin descripción adicional') ?>
                            </td>
                            <td style="padding: 1.2rem; text-align: right;">
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                    <button type="button" onclick='abrirModalEditarCarrera(<?= json_encode($c) ?>)' title="Editar" style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e2e8f0; background: #ffffff; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s;" onmouseover="this.style.background='#f1f5f9'; this.style.color='#0f172a'" onmouseout="this.style.background='#ffffff'; this.style.color='#64748b'">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                    </button>
                                    
                                    <?php if ($isSuper): ?>
                                    <form method="POST" action="index.php?ruta=gestionar-carreras" style="display:inline;" onsubmit="return confirm('¿Eliminar esta carrera? ¡Asegúrese de que no tenga líneas asociadas!');">
                                        <?= CSRF::campoOculto() ?>
                                        <input type="hidden" name="accion" value="eliminar">
                                        <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                        <button type="submit" title="Eliminar" style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid #fee2e2; background: #fef2f2; color: #ef4444; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s;" onmouseover="this.style.background='#fecaca'" onmouseout="this.style.background='#fef2f2'">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- SCRIPTS DE MODALES Y CANVAS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Modal Crear Carrera
function abrirModalCrearCarrera() {
    Swal.fire({
        title: 'Nuevo Programa de Formación',
        width: '600px',
        html: `
            <form id="form-create-carrera" method="POST" action="index.php?ruta=gestionar-carreras" style="text-align:left; margin-top:1rem;">
                <?= CSRF::campoOculto() ?>
                <input type="hidden" name="accion" value="crear">
                
                <div style="margin-bottom:1.2rem;">
                    <label style="display:block; font-weight:800; font-size:0.8rem; color:#64748b; margin-bottom:0.5rem; text-transform:uppercase;">Nombre del PNF</label>
                    <input type="text" name="nombre" placeholder="Ej: PNF en Ingeniería de Software" style="width:100%; padding:0.75rem 1rem; border:1px solid #cbd5e1; border-radius:8px; font-family:inherit; font-size:0.9rem; box-sizing:border-box;" required>
                </div>

                <div>
                    <label style="display:block; font-weight:800; font-size:0.8rem; color:#64748b; margin-bottom:0.5rem; text-transform:uppercase;">Descripción (Opcional)</label>
                    <textarea name="descripcion" rows="3" placeholder="Información adicional sobre la carrera..." style="width:100%; padding:0.75rem 1rem; border:1px solid #cbd5e1; border-radius:8px; font-family:inherit; font-size:0.9rem; box-sizing:border-box; resize:vertical;"></textarea>
                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="ph-bold ph-check"></i> Guardar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#2b3453',
        cancelButtonColor: '#94a3b8',
        customClass: { confirmButton: 'ag-swal-btn', cancelButton: 'ag-swal-btn' },
        preConfirm: () => {
            const form = document.getElementById('form-create-carrera');
            if (!form.nombre.value.trim()) {
                Swal.showValidationMessage('El nombre de la carrera es obligatorio');
                return false;
            }
            form.submit();
        }
    });
}

// Modal Editar Carrera
function abrirModalEditarCarrera(carrera) {
    Swal.fire({
        title: 'Editar Programa de Formación',
        width: '600px',
        html: `
            <form id="form-edit-carrera" method="POST" action="index.php?ruta=gestionar-carreras" style="text-align:left; margin-top:1rem;">
                <?= CSRF::campoOculto() ?>
                <input type="hidden" name="accion" value="editar">
                <input type="hidden" name="id" value="${carrera.id}">
                
                <div style="margin-bottom:1.2rem;">
                    <label style="display:block; font-weight:800; font-size:0.8rem; color:#64748b; margin-bottom:0.5rem; text-transform:uppercase;">Nombre del PNF</label>
                    <input type="text" name="nombre" value="${carrera.nombre.replace(/"/g, '&quot;')}" style="width:100%; padding:0.75rem 1rem; border:1px solid #cbd5e1; border-radius:8px; font-family:inherit; font-size:0.9rem; box-sizing:border-box;" required>
                </div>

                <div>
                    <label style="display:block; font-weight:800; font-size:0.8rem; color:#64748b; margin-bottom:0.5rem; text-transform:uppercase;">Descripción (Opcional)</label>
                    <textarea name="descripcion" rows="3" style="width:100%; padding:0.75rem 1rem; border:1px solid #cbd5e1; border-radius:8px; font-family:inherit; font-size:0.9rem; box-sizing:border-box; resize:vertical;">${carrera.descripcion ? carrera.descripcion.replace(/</g, '&lt;') : ''}</textarea>
                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="ph-bold ph-check"></i> Actualizar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#2b3453',
        cancelButtonColor: '#94a3b8',
        customClass: { confirmButton: 'ag-swal-btn', cancelButton: 'ag-swal-btn' },
        preConfirm: () => {
            const form = document.getElementById('form-edit-carrera');
            if (!form.nombre.value.trim()) {
                Swal.showValidationMessage('El nombre de la carrera es obligatorio');
                return false;
            }
            form.submit();
        }
    });
}

// CANVAS BACKGROUND EFFECT (Nodes)
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('li-nodes-canvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    
    let width, height;
    let particles = [];
    
    function resize() {
        width = canvas.parentElement.offsetWidth;
        height = canvas.parentElement.offsetHeight;
        canvas.width = width;
        canvas.height = height;
    }
    
    window.addEventListener('resize', resize);
    resize();
    
    class Particle {
        constructor() {
            this.x = Math.random() * width;
            this.y = Math.random() * height;
            this.vx = (Math.random() - 0.5) * 0.5;
            this.vy = (Math.random() - 0.5) * 0.5;
            this.radius = Math.random() * 2 + 1;
        }
        update() {
            this.x += this.vx;
            this.y += this.vy;
            if (this.x < 0 || this.x > width) this.vx *= -1;
            if (this.y < 0 || this.y > height) this.vy *= -1;
        }
        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(255, 255, 255, 0.4)';
            ctx.fill();
        }
    }
    
    for (let i = 0; i < 40; i++) particles.push(new Particle());
    
    function animate() {
        ctx.clearRect(0, 0, width, height);
        
        particles.forEach(p => {
            p.update();
            p.draw();
        });
        
        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                
                if (dist < 100) {
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.strokeStyle = `rgba(255, 255, 255, ${0.15 * (1 - dist/100)})`;
                    ctx.lineWidth = 1;
                    ctx.stroke();
                }
            }
        }
        
        requestAnimationFrame(animate);
    }
    
    animate();
});
</script>

<script>


// Modal Exportar PDF Global
function abrirModalExportarPDF() {
    Swal.fire({
        title: 'Generar Reporte PDF',
        html: '<div style="margin-top:20px;"><i class="ph-bold ph-spinner ph-spin" style="font-size:2rem;color:#3b82f6;"></i><br><br>Cargando filtros...</div>',
        showConfirmButton: false,
        allowOutsideClick: false
    });

    fetch('index.php?ruta=api-filtros-pdf')
    .then(res => res.json())
    .then(data => {
        let optCarreras = '<option value="">-- Todas las Carreras --</option>';
        data.carreras.forEach(c => {
            optCarreras += `<option value="${c.id}">${c.nombre}</option>`;
        });

        let optLineas = '<option value="">-- Todas las Líneas --</option>';
        data.lineas.forEach(l => {
            optLineas += `<option value="${l.id}">${l.nombre}</option>`;
        });

        Swal.fire({
            title: 'Configurar Reporte PDF',
            html: `
                <div style="text-align:left; font-size: 0.95rem;">
                    
                    <div style="margin-bottom: 1.5rem; background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <label style="display:block; font-weight:800; color:#475569; margin-bottom:5px; font-size:0.85rem; text-transform:uppercase;">Tipo de Reporte</label>
                        <select id="pdf-tipo" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px; font-weight:600; font-family:inherit;">
                            <option value="completo">Estructura Completa (PNFs + Líneas + Dimensiones)</option>
                            <option value="carreras">Solo Carreras y Líneas</option>
                            <option value="lineas">Directorio Detallado de Líneas</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="display:block; font-weight:800; color:#475569; margin-bottom:5px; font-size:0.85rem; text-transform:uppercase;">Filtrar por PNF Específico (Opcional)</label>
                        <select id="pdf-carrera" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px; font-family:inherit;">
                            ${optCarreras}
                        </select>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="display:block; font-weight:800; color:#475569; margin-bottom:5px; font-size:0.85rem; text-transform:uppercase;">Filtrar por Línea Específica (Opcional)</label>
                        <select id="pdf-linea" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px; font-family:inherit;">
                            ${optLineas}
                        </select>
                    </div>

                </div>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="ph-bold ph-printer"></i> Generar Reporte',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            width: '600px',
            preConfirm: () => {
                const tipo = document.getElementById('pdf-tipo').value;
                const id_carrera = document.getElementById('pdf-carrera').value;
                const id_linea = document.getElementById('pdf-linea').value;
                
                let url = 'index.php?ruta=reporte-pdf&tipo=' + tipo;
                if (id_carrera) url += '&id_carrera=' + id_carrera;
                if (id_linea) url += '&id_linea=' + id_linea;
                
                window.open(url, '_blank');
            }
        });
    });
}


</script>
