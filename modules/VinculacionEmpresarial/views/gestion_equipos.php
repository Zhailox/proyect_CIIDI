<?php
require_once __DIR__ . '/../models/PropuestaEmpresaModel.php';
$modelo = new PropuestaEmpresaModel();
$postulaciones = $modelo->getPostulacionesEmpresariales();
?>
<style>
/* Estilos para Gestión de Equipos */
.ge-wrapper { padding: 2rem; width: 100%; margin: 0 auto; font-family: 'Segoe UI', system-ui, sans-serif; }
.ge-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 1rem; }
.ge-header h2 { color: #121a3e; margin: 0; font-size: 1.8rem; }
.ge-header p { color: #64748b; margin: 0.5rem 0 0 0; }

.ge-tabs { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 1.5rem; border-bottom: 1px solid #cbd5e1; padding-bottom: 0px; }
.ge-tab-btn { background: transparent; border: none; padding: 0.8rem 1.5rem; cursor: pointer; font-weight: bold; color: #64748b; border-bottom: 3px solid transparent; transition: all 0.2s; font-size: 1rem; }
.ge-tab-btn:hover { color: #121a3e; background: #f8fafc; }
.ge-tab-btn.active { color: #121a3e; border-bottom-color: #505984; }

.ge-table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
.ge-table th { background: transparent; color: #64748b; font-weight: 600; padding: 0 1rem 0.5rem; text-align: left; border-bottom: 2px solid #e2e8f0; }
.ge-table td { background: white; padding: 1.2rem 1rem; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
.ge-table td:first-child { border-left: 1px solid #e2e8f0; border-radius: 8px 0 0 8px; }
.ge-table td:last-child { border-right: 1px solid #e2e8f0; border-radius: 0 8px 8px 0; }
.ge-table tr { box-shadow: 0 2px 4px rgba(0,0,0,0.02); }

.ge-student-info { display: flex; flex-direction: column; gap: 0.2rem; }
.ge-student-name { font-weight: bold; color: #1e293b; font-size: 1.1rem; }
.ge-student-email { color: #64748b; font-size: 0.9rem; }

.ge-project-info { display: flex; flex-direction: column; gap: 0.3rem; }
.ge-project-title { font-weight: 600; color: #121a3e; }
.ge-company-badge { display: inline-block; background: #f4f7fb; color: #505984; border: 1px solid #7090cb; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.8rem; font-weight: bold; width: fit-content; margin-bottom: 5px; }

.ge-motivation { background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.9rem; color: #475569; font-style: italic; max-height: 150px; overflow-y: auto; }

.ge-actions { display: flex; flex-direction: column; gap: 0.5rem; }
.ge-btn-aprobar { background: #505984; color: white; border: none; padding: 0.6rem 1rem; border-radius: 6px; cursor: pointer; font-weight: bold; transition: background 0.2s; width: 100%; text-align: center; }
.ge-btn-aprobar:hover { background: #3c456a; }
.ge-btn-rechazar { background: white; color: #a9a8a6; border: 1px solid #a9a8a6; padding: 0.6rem 1rem; border-radius: 6px; cursor: pointer; font-weight: bold; transition: all 0.2s; width: 100%; text-align: center; }
.ge-btn-rechazar:hover { background: #f4f7fb; }

/* Tooltip eliminado, ahora usaremos SweetAlert para ver el equipo */
</style>

<div class="ge-wrapper">
    <div class="ge-header">
        <div>
            <h2><i class="ph-bold ph-users-three"></i> Asignación de Equipos</h2>
            <p>Revisa y aprueba los equipos estudiantiles que desean resolver retos empresariales.</p>
        </div>
        <div style="background: #fff; padding: 0.8rem 1.5rem; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <strong style="color: #121a3e; font-size: 1.2rem;"><?php echo count($postulaciones); ?></strong>
            <span style="color: #64748b;"> Solicitudes Registradas</span>
        </div>
    </div>

    <?php 
    // Extraer Trayectos y Estados únicos
    $trayectos = [];
    $estados = ['Pendiente' => 0, 'Aceptado' => 0, 'Rechazado' => 0];
    foreach ($postulaciones as $p) {
        $nivel = !empty($p['nivel_trayecto']) ? $p['nivel_trayecto'] : 'General';
        if (!isset($trayectos[$nivel])) {
            $trayectos[$nivel] = 0;
        }
        $trayectos[$nivel]++;
        
        $est = $p['estado'];
        if (isset($estados[$est])) {
            $estados[$est]++;
        } else {
            $estados[$est] = 1;
        }
    }
    ksort($trayectos);
    ?>

    <?php if (empty($postulaciones)): ?>
        <div style="text-align: center; padding: 5rem 2rem; background: white; border-radius: 12px; border: 1px dashed #cbd5e1;">
            <i class="ph-fill ph-check-circle" style="font-size: 4rem; color: #7090cb; margin-bottom: 1rem;"></i>
            <h3 style="color: #1e293b;">Todo al día</h3>
            <p style="color: #64748b;">No hay postulaciones registradas.</p>
        </div>
    <?php else: ?>
        
        <!-- Pestañas Filtro: Trayecto -->
        <div class="ge-tabs" id="filtro-trayecto">
            <span style="font-size:0.85rem; color:#94a3b8; font-weight:bold; padding: 0.8rem 0; margin-right: 1rem; text-transform:uppercase;">TRAYECTO:</span>
            <button class="ge-tab-btn active" onclick="setFilter('trayecto', 'Todas', this)">Todas <span style="background:#e2e8f0; padding:2px 6px; border-radius:10px; font-size:0.75rem; margin-left:5px;"><?php echo count($postulaciones); ?></span></button>
            <?php foreach ($trayectos as $nivel => $count): ?>
                <button class="ge-tab-btn" onclick="setFilter('trayecto', '<?= htmlspecialchars($nivel) ?>', this)">
                    <?= htmlspecialchars($nivel) ?> <span style="background:#e2e8f0; padding:2px 6px; border-radius:10px; font-size:0.75rem; margin-left:5px;"><?= $count ?></span>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Pestañas Filtro: Estado -->
        <div class="ge-tabs" id="filtro-estado" style="border-bottom:none; margin-top:-10px;">
            <span style="font-size:0.85rem; color:#94a3b8; font-weight:bold; padding: 0.8rem 0; margin-right: 1rem; text-transform:uppercase;">ESTADO:</span>
            <button class="ge-tab-btn" onclick="setFilter('estado', 'Todos', this)">Todos</button>
            <button class="ge-tab-btn active" onclick="setFilter('estado', 'Pendiente', this)">
                En Espera <span style="background:#e2e8f0; padding:2px 6px; border-radius:10px; font-size:0.75rem; margin-left:5px;"><?= $estados['Pendiente'] ?></span>
            </button>
            <button class="ge-tab-btn" onclick="setFilter('estado', 'Aceptado', this)">
                Aceptadas <span style="background:#f4f7fb; color:#505984; padding:2px 6px; border-radius:10px; font-size:0.75rem; margin-left:5px; border:1px solid #7090cb;"><?= $estados['Aceptado'] ?></span>
            </button>
            <button class="ge-tab-btn" onclick="setFilter('estado', 'Rechazado', this)">
                Rechazadas <span style="background:#f4f7fb; color:#a9a8a6; padding:2px 6px; border-radius:10px; font-size:0.75rem; margin-left:5px; border:1px solid #a9a8a6;"><?= $estados['Rechazado'] ?></span>
            </button>
        </div>

        <!-- Tabla Única con filtrado dinámico -->
        <table class="ge-table" id="tablaEquipos">
            <thead>
                <tr>
                    <th style="width: 30%;">Estudiantes (Equipo)</th>
                    <th style="width: 25%;">Proyecto Seleccionado</th>
                    <th style="width: 30%;">Razones de Postulación</th>
                    <th style="width: 15%;">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($postulaciones as $p): 
                    $nivelFila = !empty($p['nivel_trayecto']) ? $p['nivel_trayecto'] : 'General';
                    $estadoFila = $p['estado'];
                    
                    // Construir el HTML de los integrantes centralizado para reutilizar en botón y modal
                    $listaEquipoHTML = '<div style="display:flex; flex-direction:column; gap:5px; text-align:left; margin-top:10px; background:#f8fafc; padding:15px; border-radius:6px; border:1px solid #cbd5e1;">';
                    $listaEquipoHTML .= '<strong style="color:#1e293b; border-bottom:1px solid #e2e8f0; padding-bottom:5px; margin-bottom:5px; font-size:1.1rem;">Integrantes del Proyecto</strong>';
                    $listaEquipoHTML .= '<div style="color:#1e293b; font-size:1rem; margin-top:5px;"><i class="ph-fill ph-star" style="color:#7090cb;"></i> <b>Líder:</b> ' . htmlspecialchars($p['estudiante']) . ' (C.I: ' . htmlspecialchars($p['cedula'] ?? 'N/A') . ')</div>';
                    
                    $hasCompaneros = false;
                    $equipoArray = [];
                    if (!empty($p['equipo_extra'])) {
                        $equipoModal = json_decode($p['equipo_extra'], true);
                        if (is_array($equipoModal) && count($equipoModal) > 0) {
                            $hasCompaneros = true;
                            $equipoArray = $equipoModal;
                            $listaEquipoHTML .= '<div style="margin-top:10px; color:#475569; font-weight:bold; font-size:0.9rem;">Compañeros Extras (' . count($equipoArray) . '):</div>';
                            foreach ($equipoModal as $comp) {
                                $listaEquipoHTML .= '<div style="color:#475569; font-size:0.9rem; padding-left:15px; border-left:3px solid #cbd5e1; margin-bottom:5px; margin-top:5px;"><i class="ph-bold ph-user"></i> <b>' . htmlspecialchars($comp['nombre']) . '</b> <br><small>C.I: ' . htmlspecialchars($comp['cedula']) . ' | Telf: ' . htmlspecialchars($comp['telefono']) . '</small></div>';
                            }
                        }
                    }
                    $listaEquipoHTML .= '</div>';
                ?>
                <tr class="equipo-row" data-trayecto="<?= htmlspecialchars($nivelFila) ?>" data-estado="<?= htmlspecialchars($estadoFila) ?>">
                    <td>
                        <div class="ge-student-info">
                            <span class="ge-student-name" title="Líder del Proyecto">
                                <i class="ph-fill ph-star" style="color:#7090cb;"></i> <?php echo htmlspecialchars($p['estudiante']); ?>
                            </span>
                            <span class="ge-student-email"><?php echo htmlspecialchars($p['correo']); ?></span>
                            <span class="ge-student-email">C.I: <?php echo htmlspecialchars($p['cedula'] ?? 'N/A'); ?></span>
                            
                            <?php if ($hasCompaneros): ?>
                                <div style="margin-top: 0.8rem;">
                                    <span onclick="verEquipoModal(this)" style="font-size: 0.8rem; color: #505984; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; background: #f4f7fb; padding: 4px 10px; border-radius: 20px; font-weight: 600; border: 1px solid #7090cb; transition: background 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f4f7fb'">
                                        <i class="ph-bold ph-users-three"></i> +<?= count($equipoArray) ?> Compañeros (Ver detalles)
                                    </span>
                                    <input type="hidden" class="equipo-info-data" value="<?php echo htmlspecialchars($listaEquipoHTML, ENT_QUOTES); ?>">
                                </div>
                            <?php endif; ?>
                            
                            <span style="font-size: 0.8rem; color: #94a3b8; margin-top: 0.8rem; display:block;">
                                <i class="ph-bold ph-calendar"></i> Postulado: <?php echo date('d/m/Y', strtotime($p['fecha_postulacion'])); ?>
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="ge-project-info">
                            <span class="ge-company-badge"><i class="ph-bold ph-buildings"></i> <?php echo htmlspecialchars($p['nombre_empresa']); ?></span>
                            <span class="ge-project-title" style="margin-bottom: 5px;"><?php echo htmlspecialchars($p['titulo']); ?></span>
                            
                            <span style="font-size: 0.85rem; color: #7090cb; font-weight:bold; margin-bottom: 2px;"><i class="ph-bold ph-graduation-cap"></i> <?php echo htmlspecialchars($nivelFila); ?></span>
                            <span style="font-size: 0.8rem; color: #64748b; font-family: monospace; margin-bottom: 2px;">Ref: <?php echo htmlspecialchars($p['codigo_seguimiento'] ?? 'N/A'); ?></span>
                            <span style="font-size: 0.85rem; color: #505984; font-weight:bold; margin-top:3px;"><i class="ph-bold ph-users"></i> Cupos Límite: <?php echo htmlspecialchars($p['cupos_disponibles'] ?? 3); ?></span>
                            
                            <?php
                            $proyectoHTML = '<div style="display:flex; flex-direction:column; gap:5px; text-align:left; margin-top:10px; background:#f8fafc; padding:15px; border-radius:6px; border:1px solid #cbd5e1;">';
                            $proyectoHTML .= '<h4 style="margin:0 0 10px 0; color:#1e293b; border-bottom:1px solid #e2e8f0; padding-bottom:5px;"><i class="ph-bold ph-buildings"></i> ' . htmlspecialchars($p['nombre_empresa']) . '</h4>';
                            $proyectoHTML .= '<div style="font-size:0.95rem; color:#475569; margin-bottom:10px;">';
                            $proyectoHTML .= '<b>Contacto:</b> ' . htmlspecialchars($p['persona_contacto'] ?? 'N/A') . '<br>';
                            $proyectoHTML .= '<b>Teléfono:</b> ' . htmlspecialchars($p['telefono_contacto'] ?? 'N/A') . '<br>';
                            $proyectoHTML .= '<b>Correo:</b> ' . htmlspecialchars($p['correo_contacto'] ?? 'N/A') . '</div>';
                            
                            $proyectoHTML .= '<div style="background:#fff; padding:12px; border-radius:4px; border-left:4px solid #7090cb; margin-top:5px;">';
                            $proyectoHTML .= '<strong style="color:#475569; font-size:0.85rem; display:block; margin-bottom:5px; text-transform:uppercase;"><i class="ph-bold ph-warning-circle"></i> Problemática / Requerimiento:</strong>';
                            $proyectoHTML .= '<p style="margin:0; font-size:0.95rem; color:#334155; line-height:1.4;">' . nl2br(htmlspecialchars($p['descripcion_problema'] ?? 'No especificada')) . '</p>';
                            $proyectoHTML .= '</div>';
                            $proyectoHTML .= '</div>';
                            ?>
                            <div style="margin-top: 0.8rem;">
                                <span onclick="verProyectoModal(this)" style="font-size: 0.8rem; color: #505984; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; background: #f4f7fb; padding: 4px 10px; border-radius: 20px; font-weight: 600; border: 1px solid #a9a8a6; transition: background 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f4f7fb'">
                                    <i class="ph-bold ph-info"></i> Detalles de Empresa
                                </span>
                                <input type="hidden" class="proyecto-info-data" value="<?php echo htmlspecialchars($proyectoHTML, ENT_QUOTES); ?>">
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="ge-motivation">
                            <?php if (!empty(trim($p['mensaje_motivacion']))): ?>
                                "<?php echo nl2br(htmlspecialchars($p['mensaje_motivacion'])); ?>"
                            <?php else: ?>
                                <span style="color:#94a3b8;">(El equipo no proporcionó razones adicionales)</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div class="ge-actions">
                            <?php if ($estadoFila === 'Pendiente'): ?>
                                <form action="?ruta=procesar-asignacion" method="POST" onsubmit="return confirmarAprobacion(event, this);">
                                    <input type="hidden" name="id_postulacion" value="<?php echo $p['id_postulacion']; ?>">
                                    <input type="hidden" name="id_investigacion" value="<?php echo $p['id_investigacion']; ?>">
                                    <input type="hidden" name="estado" value="Aceptado">
                                    <input type="hidden" class="equipo-html-data" value="<?php echo htmlspecialchars($listaEquipoHTML, ENT_QUOTES); ?>">
                                    <button type="submit" class="ge-btn-aprobar" title="Aprobar y Asignar Equipo"><i class="ph-bold ph-check"></i> Asignar</button>
                                </form>
                                
                                <form action="?ruta=procesar-asignacion" method="POST">
                                    <input type="hidden" name="id_postulacion" value="<?php echo $p['id_postulacion']; ?>">
                                    <input type="hidden" name="id_investigacion" value="<?php echo $p['id_investigacion']; ?>">
                                    <input type="hidden" name="estado" value="Rechazado">
                                    <button type="submit" class="ge-btn-rechazar" title="Rechazar Postulación"><i class="ph-bold ph-x"></i> Rechazar</button>
                                </form>
                            <?php elseif ($estadoFila === 'Aceptado'): ?>
                                <div style="background:#f4f7fb; color:#505984; padding:10px; border-radius:6px; text-align:center; font-weight:bold; font-size:0.9rem; border:1px solid #505984;">
                                    <i class="ph-bold ph-check-circle"></i> Aceptada
                                </div>
                            <?php elseif ($estadoFila === 'Rechazado'): ?>
                                <div style="background:#f4f7fb; color:#a9a8a6; padding:10px; border-radius:6px; text-align:center; font-weight:bold; font-size:0.9rem; border:1px solid #a9a8a6;">
                                    <i class="ph-bold ph-x-circle"></i> Rechazada
                                </div>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
    <?php endif; ?>
</div>

<script>
let currentTrayecto = 'Todas';
let currentEstado = 'Pendiente';

function setFilter(type, value, btn) {
    if (type === 'trayecto') {
        currentTrayecto = value;
        const botones = document.querySelectorAll('#filtro-trayecto .ge-tab-btn');
        botones.forEach(b => b.classList.remove('active'));
    } else {
        currentEstado = value;
        const botones = document.querySelectorAll('#filtro-estado .ge-tab-btn');
        botones.forEach(b => b.classList.remove('active'));
    }
    btn.classList.add('active');
    aplicarFiltros();
}

function aplicarFiltros() {
    const filas = document.querySelectorAll('.equipo-row');
    filas.forEach(fila => {
        const filaTrayecto = fila.getAttribute('data-trayecto');
        const filaEstado = fila.getAttribute('data-estado');
        
        let matchTrayecto = (currentTrayecto === 'Todas' || filaTrayecto === currentTrayecto);
        let matchEstado = (currentEstado === 'Todos' || filaEstado === currentEstado);
        
        if (matchTrayecto && matchEstado) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}

// Aplicar filtros por defecto (Pendientes) al cargar
window.addEventListener('DOMContentLoaded', () => {
    aplicarFiltros();
});

function verEquipoModal(btn) {
    const teamHTML = btn.nextElementSibling.value;
    Swal.fire({
        title: 'Detalles del Equipo',
        html: teamHTML,
        icon: 'info',
        confirmButtonColor: '#121a3e',
        confirmButtonText: 'Cerrar'
    });
}

function verProyectoModal(btn) {
    const proyectoHTML = btn.nextElementSibling.value;
    Swal.fire({
        title: 'Detalles del Proyecto',
        html: proyectoHTML,
        icon: 'info',
        confirmButtonColor: '#121a3e',
        confirmButtonText: 'Cerrar'
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (isset($_SESSION['flash_success'])): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: '¡Operación Exitosa!',
        text: '<?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES) ?>',
        confirmButtonColor: '#10b981'
    });
</script>
<?php unset($_SESSION['flash_success']); endif; ?>

<?php if (isset($_SESSION['flash_error'])): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '<?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES) ?>',
        confirmButtonColor: '#ef4444'
    });
</script>
<?php unset($_SESSION['flash_error']); endif; ?>

<script>
function confirmarAprobacion(event, form) {
    event.preventDefault();
    let teamHTML = form.querySelector('.equipo-html-data').value;
    Swal.fire({
        title: '¿Confirmar Asignación?',
        html: `Al aprobar a este equipo, el proyecto empresarial pasará a estado <b>"En Desarrollo"</b> y se cerrarán los cupos en la Cartelera de Oportunidades.<br><br>` + teamHTML,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Sí, asignar equipo',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    return false;
}
</script>
