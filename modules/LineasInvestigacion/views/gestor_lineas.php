<?php require_once __DIR__ . '/../../../core/Security/CSRF.php'; ?>
<?php
// modules/LineasInvestigacion/views/gestor_lineas.php
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
                    <i class="ph-bold ph-squares-four"></i> SUBSISTEMAS DEL SISTEMA INTEGRAL
                </div>
                <h1 class="ag-header-title">Gestor de Líneas de Investigación</h1>
                <p class="ag-header-desc">
                    Gestione las líneas, asocie dimensiones operativas internamente y controle su configuración.
                </p>
            </div>
            <div class="ag-header-right">
            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">

                <button type="button" onclick="abrirModalExportarPDF()" style="background: #ef4444; color: #ffffff; padding: 10px 18px; border: none; border-radius: 8px; font-weight: 800; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px; cursor:pointer; box-shadow: 0 4px 10px rgba(239,68,68,0.25); transition: 0.2s;" onmouseover="this.style.background='#dc2626'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#ef4444'; this.style.transform='translateY(0)'">
                    <i class="ph-bold ph-file-pdf"></i> Exportar PDF
                </button>

                
                <button type="button" onclick="abrirModalCrearLinea()" style="background: #ffffff; color: #0f172a; padding: 10px 18px; border: none; border-radius: 8px; font-weight: 800; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px; cursor:pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.15); transition: 0.2s;" onmouseover="this.style.background='#f1f5f9'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#ffffff'; this.style.transform='translateY(0)'">
                    <i class="ph-bold ph-plus-circle"></i> Nueva Línea
                </button>
                <a href="index.php?ruta=lineas-investigacion" style="border: 1px solid rgba(255,255,255,0.3); color: #ffffff; background: rgba(255,255,255,0.1); text-decoration: none; padding: 10px 18px; border-radius: 8px; font-weight: 700; font-size: 0.9rem; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 6px;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                    <i class="ph-bold ph-eye"></i> Vista Pública
                </a>
            </div>
            
            <div style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); padding: 4px; border-radius: 8px; display: inline-flex; gap: 4px;">
                <button type="button" id="btnViewGrid" title="Vista Cuadrícula" onclick="setLineaViewMode('grid')" style="border:none; padding: 6px 12px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-weight: 600; transition: 0.2s; background: rgba(255,255,255,0.2); color: #ffffff;"><i class="ph-bold ph-squares-four" style="font-size: 1.1rem;"></i></button>
                <button type="button" id="btnViewList" title="Vista Lista" onclick="setLineaViewMode('list')" style="border:none; padding: 6px 12px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-weight: 600; transition: 0.2s; background: transparent; color: rgba(255,255,255,0.6);"><i class="ph-bold ph-list" style="font-size: 1.1rem;"></i></button>
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

    <?php
    $lineasPorCarrera = [];
    if (!empty($lineas)) {
        foreach ($lineas as $linea) {
            $carrera = !empty($linea['carrera_nombre']) ? $linea['carrera_nombre'] : 'Sin Asignar';
            $lineasPorCarrera[$carrera][] = $linea;
        }
        // Ordenar alfabéticamente por llave
        ksort($lineasPorCarrera);
    }
    ?>


    <!-- GRID DE TARJETAS DE LÍNEAS -->
    
    


    <?php if (empty($lineasPorCarrera)): ?>
        <div style="padding: 3rem; text-align: center; color: #94a3b8; font-weight: 600; background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; margin: 2rem;">
            <i class="ph-bold ph-graph" style="font-size: 3rem; opacity: 0.3; margin-bottom: 0.5rem; display: block;"></i>
            No hay líneas de investigación registradas.
        </div>
    <?php else: ?>
        <?php foreach($lineasPorCarrera as $carrera_nombre => $grupo_lineas): ?>
            
            <div style="margin: 2rem 2rem 1rem 2rem; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem;">
                <i class="ph-bold ph-graduation-cap" style="font-size: 1.5rem; color: #505984;"></i>
                <h2 style="margin: 0; font-size: 1.2rem; font-weight: 800; color: #1e293b;">PNF en <?= htmlspecialchars(mb_convert_case($carrera_nombre, MB_CASE_TITLE, 'UTF-8')) ?></h2>
                <span style="background: #f1f5f9; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 800; color: #64748b;"><?= count($grupo_lineas) ?> LÍNEAS</span>
            </div>

            <div class="ag-modules-grid" style="margin: 0 2rem 2rem 2rem;">
                <?php foreach($grupo_lineas as $li): ?>
            <div class="ag-card">
                <!-- Info de la Linea -->
                <div class="ag-card-content" style="display: flex; gap: 1.1rem; align-items: flex-start;">
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
                        <span style="background: rgba(80, 89, 132, 0.08); padding: 5px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 800; color: #505984;">
                            PNF EN <?= htmlspecialchars(mb_convert_case($li['carrera_nombre'] ?? 'Sin Asignar', MB_CASE_UPPER, 'UTF-8')) ?>
                        </span>
                    </div>
                </div>

                <!-- Botones Inferiores -->
                <div class="ag-card-actions" style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; border-top: 1px solid rgba(80, 89, 132, 0.1); padding-top: 1.2rem;">
                    
                    
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <?php if ($li['activo']): ?>
                            <button type="button" class="ag-btn-edit" style="background:#f59e0b; color:white; border-color:#f59e0b;" title="Ocultar Línea" onclick="ocultarLinea(<?= htmlspecialchars($li['id']) ?>, '<?= htmlspecialchars(addslashes($li['nombre'])) ?>')">
                                <i class="ph-bold ph-eye-slash"></i> Ocultar
                            </button>
                        <?php else: ?>
                            <button type="button" class="ag-btn-edit" style="background:#10b981; color:white; border-color:#10b981;" title="Mostrar Línea" onclick="mostrarLinea(<?= htmlspecialchars($li['id']) ?>)">
                                <i class="ph-bold ph-eye"></i> Mostrar
                            </button>
                        <?php endif; ?>
                        
                        <?php if ($isSuper): ?>
                        <button type="button" class="ag-btn-delete" title="Eliminar Definitivamente" onclick="eliminarLinea(<?= htmlspecialchars($li['id']) ?>, '<?= htmlspecialchars(addslashes($li['nombre'])) ?>')">
                            <i class="ph-bold ph-trash"></i>
                        </button>
                        <?php endif; ?>
                    </div>

                    <a href="index.php?ruta=detalle-gestion-linea&id=<?= urlencode($li['id']) ?>" class="ag-btn-manage" title="Abrir gestor dedicado de la línea">
                        <i class="ph-bold ph-gear-six"></i> Gestionar Línea
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

<!-- Formularios Ocultos -->
    <form id="formExportarLineas" method="POST" action="index.php?ruta=gestionar-lineas" style="display:none;">
        <input type="hidden" name="accion" value="exportar">
        <?= CSRF::campoOculto() ?>
    </form>

<form id="formEliminarLinea" method="POST" action="index.php?ruta=gestionar-lineas" style="display:none;">
        <?= CSRF::campoOculto() ?>
    <input type="hidden" name="accion" value="eliminar">
    <input type="hidden" name="id" id="deleteLineaId" value="">
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const carrerasList = <?= json_encode($carreras) ?>;

function eliminarLinea(id, nombre) {
    Swal.fire({
        title: '¡ADVERTENCIA CRÍTICA!',
        html: `Estás a punto de eliminar definitivamente la línea <strong>${nombre}</strong> y todas sus dimensiones.<br><br>` + 
              `<span style="color:#ef4444; font-weight:bold;">¡ESTO DEJARÁ PROYECTOS HUÉRFANOS!</span><br>` +
              `Todos los proyectos, postulaciones e investigaciones vinculadas a esta línea o sus dimensiones perderán su referencia estructural, ` +
              `lo que podría causar errores en las estadísticas o pérdida de datos académicos históricos.<br><br>` + 
              `Para proceder, escribe la palabra <b>ELIMINAR</b> en mayúsculas:`,
        icon: 'error',
        input: 'text',
        inputPlaceholder: 'Escribe ELIMINAR',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="ph-bold ph-trash"></i> Ejecutar Borrado Crítico',
        cancelButtonText: 'Cancelar',
        customClass: { popup: 'ag-swal-popup' },
        preConfirm: (inputValue) => {
            if (inputValue !== 'ELIMINAR') {
                Swal.showValidationMessage('Debes escribir la palabra ELIMINAR para confirmar.');
                return false;
            }
            return true;
        }
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
        width: '800px',
        customClass: { popup: 'ag-swal-popup' },
        html: `
            <div style="text-align: left; margin-top: 1rem;">
                <form id="form-create-linea" method="POST" action="index.php?ruta=gestionar-lineas">
        <?= CSRF::campoOculto() ?>
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
                        <textarea name="descripcion" class="ag-modal-input" style="height:220px; resize:vertical;" required placeholder="Breve descripción de la línea..."></textarea>
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

<form id="formOcultarLinea" method="POST" action="index.php?ruta=gestionar-lineas" style="display:none;">
    <input type="hidden" name="accion" value="ocultar">
    <input type="hidden" name="id" id="inputIdOcultar">
    <?= CSRF::campoOculto() ?>
</form>

<form id="formMostrarLinea" method="POST" action="index.php?ruta=gestionar-lineas" style="display:none;">
    <input type="hidden" name="accion" value="mostrar">
    <input type="hidden" name="id" id="inputIdMostrar">
    <?= CSRF::campoOculto() ?>
</form>

<script>
function ocultarLinea(id, nombre) {
    Swal.fire({
        title: '¿Ocultar Línea?',
        html: `Estás a punto de ocultar la línea <strong>${nombre}</strong>.<br><br>Dejará de aparecer en las listas públicas, pero los proyectos y datos seguirán intactos.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, ocultar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('inputIdOcultar').value = id;
            document.getElementById('formOcultarLinea').submit();
        }
    });
}

function mostrarLinea(id) {
    document.getElementById('inputIdMostrar').value = id;
    document.getElementById('formMostrarLinea').submit();
}
</script>
</body>

<script>
function setLineaViewMode(mode) {
    const gridEl = document.querySelector('.ag-modules-grid');
    const btnGrid = document.getElementById('btnViewGrid');
    const btnList = document.getElementById('btnViewList');
    
    if (!gridEl) return;
    
    // reset styles
    btnGrid.style.background = 'transparent'; btnGrid.style.color = 'rgba(255,255,255,0.6)';
    btnList.style.background = 'transparent'; btnList.style.color = 'rgba(255,255,255,0.6)';

    if (mode === 'list') {
        gridEl.classList.add('ag-view-list');
        btnList.style.background = 'rgba(255,255,255,0.2)'; btnList.style.color = '#ffffff';
        localStorage.setItem('lineas_view_mode', 'list');
    } else {
        gridEl.classList.remove('ag-view-list');
        btnGrid.style.background = 'rgba(255,255,255,0.2)'; btnGrid.style.color = '#ffffff';
        localStorage.setItem('lineas_view_mode', 'grid');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const savedMode = localStorage.getItem('lineas_view_mode') || 'grid';
    setLineaViewMode(savedMode);
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
