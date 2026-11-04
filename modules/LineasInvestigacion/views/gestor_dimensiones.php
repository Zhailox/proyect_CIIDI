<?php require_once __DIR__ . '/../../../core/Security/CSRF.php'; ?>
<?php
// modules/LineasInvestigacion/views/gestor_dimensiones.php
$isSuper = ($_SESSION['nivel_privilegio'] ?? 999) === 0;
?>

<div class="li-gestor-wrapper">

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
                    <i class="ph-bold ph-squares-four"></i> ESTRUCTURA ACADÉMICA
                </div>
                <h1 class="ag-header-title">Gestor de Dimensiones Operativas</h1>
                <p class="ag-header-desc">
                    Administra las dimensiones temáticas que agrupan proyectos dentro de cada línea de investigación.
                </p>
            </div>
            <div class="ag-header-right">
                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">

                <button type="button" onclick="abrirModalExportarPDF()" style="background: #ef4444; color: #ffffff; padding: 10px 18px; border: none; border-radius: 8px; font-weight: 800; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px; cursor:pointer; box-shadow: 0 4px 10px rgba(239,68,68,0.25); transition: 0.2s;" onmouseover="this.style.background='#dc2626'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#ef4444'; this.style.transform='translateY(0)'">
                    <i class="ph-bold ph-file-pdf"></i> Exportar PDF
                </button>

                    <button type="button" onclick="abrirModalCrearDimension()" style="background: #ffffff; color: #0f172a; padding: 10px 18px; border: none; border-radius: 8px; font-weight: 800; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px; cursor:pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.15); transition: 0.2s;" onmouseover="this.style.background='#f1f5f9'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#ffffff'; this.style.transform='translateY(0)'">
                        <i class="ph-bold ph-plus-circle"></i> Nueva Dimensión
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

    
    <!-- FILTROS Y BÚSQUEDA -->
    <div style="margin: 2rem 2rem -0.5rem 2rem; display: flex; gap: 1rem; flex-wrap: wrap; background: #ffffff; padding: 1.2rem; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
        <div style="flex: 1 1 250px;">
            <label style="font-size: 0.8rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 0.3rem; display: block;"><i class="ph-bold ph-magnifying-glass"></i> Buscar Dimensión</label>
            <input type="text" id="search-dim" placeholder="Escriba para buscar..." style="width: 100%; padding: 0.6rem 1rem; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; font-family: inherit; background: #f8fafc; color: #0f172a; font-weight: 600;">
        </div>
        <div style="flex: 1 1 200px;">
            <label style="font-size: 0.8rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 0.3rem; display: block;"><i class="ph-bold ph-graduation-cap"></i> Filtrar por PNF</label>
            <select id="filter-carrera" style="width: 100%; padding: 0.6rem 1rem; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; font-family: inherit; background: #f8fafc; color: #0f172a; font-weight: 600; cursor:pointer;">
                <option value="">Todas las Carreras</option>
            </select>
        </div>
        <div style="flex: 1 1 200px;">
            <label style="font-size: 0.8rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 0.3rem; display: block;"><i class="ph-bold ph-graph"></i> Filtrar por Línea</label>
            <select id="filter-linea" style="width: 100%; padding: 0.6rem 1rem; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; font-family: inherit; background: #f8fafc; color: #0f172a; font-weight: 600; cursor:pointer;">
                <option value="">Todas las Líneas</option>
            </select>
        </div>
    </div>
    
    <!-- TABLA DE DIMENSIONES -->
    <div style="padding: 2rem;">
        <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.03); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <tr>
                        <th style="padding: 1.2rem; font-weight: 800; font-size: 0.85rem; color: #64748b; text-transform: uppercase;">Dimensión Operativa</th>
                        <th style="padding: 1.2rem; font-weight: 800; font-size: 0.85rem; color: #64748b; text-transform: uppercase;">Línea Asociada</th>
                        <th style="padding: 1.2rem; font-weight: 800; font-size: 0.85rem; color: #64748b; text-transform: uppercase;">Programa (PNF)</th>
                        <th style="padding: 1.2rem; font-weight: 800; font-size: 0.85rem; color: #64748b; text-transform: uppercase;">Descripción</th>
                        <th style="padding: 1.2rem; font-weight: 800; font-size: 0.85rem; color: #64748b; text-transform: uppercase; text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($dimensiones)): ?>
                    <tr>
                        <td colspan="5" style="padding: 3rem; text-align: center; color: #94a3b8; font-weight: 600;">
                            <i class="ph-bold ph-squares-four" style="font-size: 3rem; opacity: 0.3; margin-bottom: 0.5rem; display: block;"></i>
                            No hay dimensiones registradas.
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($dimensiones as $dim): ?>
                        <tr class="dim-row" data-carrera="<?= htmlspecialchars($dim['carrera_nombre'] ?? 'General') ?>" data-linea="<?= htmlspecialchars($dim['linea_nombre'] ?? 'General') ?>" style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 1.2rem;">
                                <div style="font-weight: 800; color: #1e293b; font-size: 0.95rem; display: flex; align-items: center; gap: 8px;">
                                    <i class="ph-bold ph-cube" style="color: #505984;"></i> <?= htmlspecialchars($dim['nombre']) ?>
                                </div>
                            </td>
                            <td style="padding: 1.2rem;">
                                <span style="background: #f1f5f9; padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 700; color: #475569; display: inline-block;">
                                    <?= htmlspecialchars(mb_convert_case($dim['linea_nombre'] ?? 'General', MB_CASE_TITLE, 'UTF-8')) ?>
                                </span>
                            </td>
                            <td style="padding: 1.2rem;">
                                <span style="color: #64748b; font-weight: 700; font-size: 0.85rem;">
                                    <i class="ph-bold ph-graduation-cap"></i> <?= htmlspecialchars($dim['carrera_nombre'] ?? 'General') ?>
                                </span>
                            </td>
                            <td style="padding: 1.2rem; color: #475569; font-size: 0.85rem; max-width: 300px;">
                                <?= htmlspecialchars($dim['descripcion'] ?: 'Sin descripción') ?>
                            </td>
                            <td style="padding: 1.2rem; text-align: right;">
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                    <button type="button" onclick="abrirModalEditarDimension(<?= htmlspecialchars(json_encode($dim, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>)" title="Editar" style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e2e8f0; background: #ffffff; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s;" onmouseover="this.style.background='#f1f5f9'; this.style.color='#0f172a'" onmouseout="this.style.background='#ffffff'; this.style.color='#64748b'">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                    </button>
                                    
                                    <?php if ($isSuper): ?>
                                    <form method="POST" action="index.php?ruta=gestionar-dimensiones" style="display:inline;" onsubmit="return confirm('¿Eliminar esta dimensión?');">
                                        <?= CSRF::campoOculto() ?>
                                        <input type="hidden" name="accion" value="eliminar">
                                        <input type="hidden" name="id" value="<?= $dim['id'] ?>">
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const lineasList = <?= json_encode($lineas, JSON_UNESCAPED_UNICODE) ?>;

function getLineasOptions(selectedId = null) {
    let opts = '<option value="">-- Seleccione una Línea --</option>';
    lineasList.forEach(L => {
        const isSelected = selectedId == L.id ? 'selected' : '';
        opts += `<option value="${L.id}" ${isSelected}>${L.nombre.toUpperCase()}</option>`;
    });
    return opts;
}


// Populating filters dynamically
document.addEventListener('DOMContentLoaded', () => {
    const carrerasSet = new Set();
    const lineasSet = new Set();
    
    document.querySelectorAll('.dim-row').forEach(row => {
        carrerasSet.add(row.getAttribute('data-carrera'));
        lineasSet.add(row.getAttribute('data-linea'));
    });

    const filterCarrera = document.getElementById('filter-carrera');
    Array.from(carrerasSet).sort().forEach(c => {
        if(c) filterCarrera.innerHTML += `<option value="${c}">${c}</option>`;
    });

    const filterLinea = document.getElementById('filter-linea');
    Array.from(lineasSet).sort().forEach(l => {
        if(l) filterLinea.innerHTML += `<option value="${l}">${l}</option>`;
    });

    // Event listeners
    const searchInput = document.getElementById('search-dim');
    
    function updateTable() {
        const term = searchInput.value.toLowerCase().trim();
        const carreraVal = filterCarrera.value;
        const lineaVal = filterLinea.value;
        
        document.querySelectorAll('.dim-row').forEach(row => {
            const txt = row.textContent.toLowerCase();
            const rowCarrera = row.getAttribute('data-carrera');
            const rowLinea = row.getAttribute('data-linea');
            
            const matchSearch = term === '' || txt.includes(term);
            const matchCarrera = carreraVal === '' || rowCarrera === carreraVal;
            const matchLinea = lineaVal === '' || rowLinea === lineaVal;
            
            if (matchSearch && matchCarrera && matchLinea) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', updateTable);
    filterCarrera.addEventListener('change', updateTable);
    filterLinea.addEventListener('change', updateTable);
});

// Modal Crear Dimensión
function abrirModalCrearDimension() {
    Swal.fire({
        title: 'Nueva Dimensión',
        width: '600px',
        html: `
            <form id="form-create-dim" method="POST" action="index.php?ruta=gestionar-dimensiones" style="text-align:left; margin-top:1rem;">
                <?= CSRF::campoOculto() ?>
                <input type="hidden" name="accion" value="crear">
                
                <div style="margin-bottom:1.2rem;">
                    <label style="display:block; font-weight:800; font-size:0.8rem; color:#64748b; margin-bottom:0.5rem; text-transform:uppercase;">Nombre de la Dimensión</label>
                    <input type="text" name="nombre" placeholder="Ej: Bases de Datos" style="width:100%; padding:0.75rem 1rem; border:1px solid #cbd5e1; border-radius:8px; font-family:inherit; font-size:0.9rem; box-sizing:border-box;" required>
                </div>
                
                <div style="margin-bottom:1.2rem;">
                    <label style="display:block; font-weight:800; font-size:0.8rem; color:#64748b; margin-bottom:0.5rem; text-transform:uppercase;">Línea de Investigación Asociada</label>
                    <select name="id_linea" style="width:100%; padding:0.75rem 1rem; border:1px solid #cbd5e1; border-radius:8px; font-family:inherit; font-size:0.9rem; box-sizing:border-box; background:#f8fafc;" required>
                        ${getLineasOptions()}
                    </select>
                </div>

                <div>
                    <label style="display:block; font-weight:800; font-size:0.8rem; color:#64748b; margin-bottom:0.5rem; text-transform:uppercase;">Descripción (Opcional)</label>
                    <textarea name="descripcion" rows="3" placeholder="Información adicional..." style="width:100%; padding:0.75rem 1rem; border:1px solid #cbd5e1; border-radius:8px; font-family:inherit; font-size:0.9rem; box-sizing:border-box; resize:vertical;"></textarea>
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
            const form = document.getElementById('form-create-dim');
            if (!form.nombre.value.trim() || !form.id_linea.value) {
                Swal.showValidationMessage('El nombre y la línea son obligatorios');
                return false;
            }
            form.submit();
        }
    });
}

// Modal Editar Dimensión
function abrirModalEditarDimension(dim) {
    Swal.fire({
        title: 'Editar Dimensión',
        width: '600px',
        html: `
            <form id="form-edit-dim" method="POST" action="index.php?ruta=gestionar-dimensiones" style="text-align:left; margin-top:1rem;">
                <?= CSRF::campoOculto() ?>
                <input type="hidden" name="accion" value="editar">
                <input type="hidden" name="id" value="${dim.id}">
                
                <div style="margin-bottom:1.2rem;">
                    <label style="display:block; font-weight:800; font-size:0.8rem; color:#64748b; margin-bottom:0.5rem; text-transform:uppercase;">Nombre de la Dimensión</label>
                    <input type="text" name="nombre" value="${dim.nombre.replace(/"/g, '&quot;')}" style="width:100%; padding:0.75rem 1rem; border:1px solid #cbd5e1; border-radius:8px; font-family:inherit; font-size:0.9rem; box-sizing:border-box;" required>
                </div>
                
                <div style="margin-bottom:1.2rem;">
                    <label style="display:block; font-weight:800; font-size:0.8rem; color:#64748b; margin-bottom:0.5rem; text-transform:uppercase;">Línea de Investigación Asociada</label>
                    <select name="id_linea" style="width:100%; padding:0.75rem 1rem; border:1px solid #cbd5e1; border-radius:8px; font-family:inherit; font-size:0.9rem; box-sizing:border-box; background:#f8fafc;" required>
                        ${getLineasOptions(dim.id_linea)}
                    </select>
                </div>

                <div>
                    <label style="display:block; font-weight:800; font-size:0.8rem; color:#64748b; margin-bottom:0.5rem; text-transform:uppercase;">Descripción</label>
                    <textarea name="descripcion" rows="3" style="width:100%; padding:0.75rem 1rem; border:1px solid #cbd5e1; border-radius:8px; font-family:inherit; font-size:0.9rem; box-sizing:border-box; resize:vertical;">${dim.descripcion ? dim.descripcion.replace(/</g, '&lt;') : ''}</textarea>
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
            const form = document.getElementById('form-edit-dim');
            if (!form.nombre.value.trim() || !form.id_linea.value) {
                Swal.showValidationMessage('El nombre y la línea son obligatorios');
                return false;
            }
            form.submit();
        }
    });
}

// CANVAS BACKGROUND EFFECT
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
        particles.forEach(p => { p.update(); p.draw(); });
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
        html: '<div class="ag-pdf-loading-wrap"><i class="ph-bold ph-spinner ph-spin ag-pdf-spinner"></i><br><br>Cargando filtros...</div>',
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
