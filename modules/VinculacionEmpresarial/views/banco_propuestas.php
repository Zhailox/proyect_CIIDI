<?php
require_once __DIR__ . '/../models/PropuestaEmpresaModel.php';
require_once __DIR__ . '/../../../core/Database/Connection.php';

$modelo_pe = new PropuestaEmpresaModel();
$roles_profesor = ['Profesor', 'Super Administrador', 'Comite'];
$esProfesor = isset($_SESSION['rol_nombre']) && in_array($_SESSION['rol_nombre'], $roles_profesor);

$todas = $modelo_pe->getTodas();

// KPIs
$kpiNuevas = 0;
foreach($todas as $p) {
    if ($p['estado'] === 'pendiente') $kpiNuevas++;
}

$pdo = Connection::getInstance();
$lineas = $pdo->query("SELECT id, nombre FROM lineas_investigacion ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
$dimensiones = $pdo->query("SELECT id, id_linea, nombre FROM dimensiones_operativas ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);

// Extraer Trayectos y Estados únicos
$trayectos = [];
$estados = ['pendiente' => 0, 'aceptada' => 0, 'rechazada' => 0];
foreach ($todas as $p) {
    $nivel = !empty($p['nivel_trayecto']) ? $p['nivel_trayecto'] : 'Sin Asignar';
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

<style>
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

.ge-project-info { display: flex; flex-direction: column; gap: 0.3rem; }
.ge-project-title { font-weight: 600; color: #121a3e; }
.ge-company-badge { display: inline-block; background: #f4f7fb; color: #505984; border: 1px solid #7090cb; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.8rem; font-weight: bold; width: fit-content; margin-bottom: 5px; }

.ge-actions { display: flex; flex-direction: column; gap: 0.5rem; }
.ge-btn-aprobar { background: #505984; color: white; border: none; padding: 0.6rem 1rem; border-radius: 6px; cursor: pointer; font-weight: bold; transition: background 0.2s; width: 100%; text-align: center; }
.ge-btn-aprobar:hover { background: #3c456a; }

/* Modal styles */
.ve-modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(18,26,62,0.6); display: flex; align-items: center; justify-content: center; z-index: 9999; opacity: 0; pointer-events: none; transition: opacity 0.3s; }
.ve-modal-overlay.active { opacity: 1; pointer-events: auto; }
.ve-modal-content { background: white; width: 90%; max-width: 800px; padding: 2rem; border-radius: 12px; position: relative; max-height: 90vh; overflow-y: auto; }
.ve-modal-close { position: absolute; top: 1rem; right: 1rem; font-size: 1.5rem; cursor: pointer; color: #64748b; }
.ve-input, .ve-textarea { padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; font-family: inherit; }
.ve-btn-aceptar { background: #505984; color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 6px; cursor: pointer; font-weight: bold; transition: background 0.2s; }
.ve-btn-aceptar:hover { background: #3c456a; }
</style>

<div class="ge-wrapper">
    <div class="ge-header">
        <div>
            <h2><i class="ph-bold ph-folder-open"></i> Evaluación de Propuestas</h2>
            <p>Revisa y aprueba los requerimientos tecnológicos enviados por el sector productivo.</p>
        </div>
        <div style="background: #fff; padding: 0.8rem 1.5rem; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <strong style="color: #121a3e; font-size: 1.2rem;"><?php echo $kpiNuevas; ?></strong>
            <span style="color: #64748b;"> Nuevas Solicitudes</span>
        </div>
    </div>

    <?php if (empty($todas)): ?>
        <div style="text-align: center; padding: 5rem 2rem; background: white; border-radius: 12px; border: 1px dashed #cbd5e1;">
            <i class="ph-fill ph-check-circle" style="font-size: 4rem; color: #7090cb; margin-bottom: 1rem;"></i>
            <h3 style="color: #1e293b;">Todo al día</h3>
            <p style="color: #64748b;">No hay propuestas de empresas en este momento.</p>
        </div>
    <?php else: ?>
        
        <!-- Pestañas Filtro: Trayecto -->
        <div class="ge-tabs" id="filtro-trayecto">
            <span style="font-size:0.85rem; color:#94a3b8; font-weight:bold; padding: 0.8rem 0; margin-right: 1rem; text-transform:uppercase;">TRAYECTO:</span>
            <button class="ge-tab-btn active" onclick="setFilter('trayecto', 'Todas', this)">Todas <span style="background:#e2e8f0; padding:2px 6px; border-radius:10px; font-size:0.75rem; margin-left:5px;"><?php echo count($todas); ?></span></button>
            <button class="ge-tab-btn" onclick="setFilter('trayecto', 'Sin Asignar', this)">
                Sin Asignar <span style="background:#e2e8f0; padding:2px 6px; border-radius:10px; font-size:0.75rem; margin-left:5px;"><?= $trayectos['Sin Asignar'] ?? 0 ?></span>
            </button>
            <?php foreach ($trayectos as $nivel => $count): if($nivel === 'Sin Asignar') continue; ?>
                <button class="ge-tab-btn" onclick="setFilter('trayecto', '<?= htmlspecialchars($nivel) ?>', this)">
                    <?= htmlspecialchars($nivel) ?> <span style="background:#e2e8f0; padding:2px 6px; border-radius:10px; font-size:0.75rem; margin-left:5px;"><?= $count ?></span>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Pestañas Filtro: Estado -->
        <div class="ge-tabs" id="filtro-estado" style="border-bottom:none; margin-top:-10px;">
            <span style="font-size:0.85rem; color:#94a3b8; font-weight:bold; padding: 0.8rem 0; margin-right: 1rem; text-transform:uppercase;">ESTADO:</span>
            <button class="ge-tab-btn" onclick="setFilter('estado', 'Todos', this)">Todos</button>
            <button class="ge-tab-btn active" onclick="setFilter('estado', 'pendiente', this)">
                Pendientes <span style="background:#e2e8f0; padding:2px 6px; border-radius:10px; font-size:0.75rem; margin-left:5px;"><?= $estados['pendiente'] ?></span>
            </button>
            <button class="ge-tab-btn" onclick="setFilter('estado', 'aceptada', this)">
                Aceptadas <span style="background:#f4f7fb; color:#505984; padding:2px 6px; border-radius:10px; font-size:0.75rem; margin-left:5px; border:1px solid #7090cb;"><?= $estados['aceptada'] ?></span>
            </button>
            <button class="ge-tab-btn" onclick="setFilter('estado', 'rechazada', this)">
                Rechazadas <span style="background:#f4f7fb; color:#a9a8a6; padding:2px 6px; border-radius:10px; font-size:0.75rem; margin-left:5px; border:1px solid #a9a8a6;"><?= $estados['rechazada'] ?></span>
            </button>
        </div>

        <table class="ge-table" id="tablaPropuestas">
            <thead>
                <tr>
                    <th style="width: 25%;">Organización</th>
                    <th style="width: 35%;">Requerimiento Tecnológico</th>
                    <th style="width: 25%;">Detalles Adicionales</th>
                    <th style="width: 15%;">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($todas as $p): 
                    $nivelFila = !empty($p['nivel_trayecto']) ? $p['nivel_trayecto'] : 'Sin Asignar';
                    $estadoFila = $p['estado'];
                ?>
                <tr class="propuesta-row" data-trayecto="<?= htmlspecialchars($nivelFila) ?>" data-estado="<?= htmlspecialchars($estadoFila) ?>">
                    <td>
                        <div class="ge-project-info">
                            <span class="ge-company-badge"><i class="ph-bold ph-buildings"></i> <?php echo htmlspecialchars($p['nombre_empresa']); ?></span>
                            <div style="font-size:0.9rem; color:#475569; margin-top:10px;">
                                <b>Contacto:</b> <?php echo htmlspecialchars($p['persona_contacto'] ?? 'N/A'); ?><br>
                                <b>Teléfono:</b> <?php echo htmlspecialchars($p['telefono_contacto'] ?? 'N/A'); ?><br>
                                <b>Correo:</b> <?php echo htmlspecialchars($p['correo_contacto'] ?? 'N/A'); ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="ge-project-info">
                            <span class="ge-project-title" style="margin-bottom: 5px; font-size: 1.1rem;"><i class="ph-bold ph-lightbulb" style="color:#7090cb;"></i> <?php echo htmlspecialchars($p['area_afectada']); ?></span>
                            
                            <div style="background:#fff; padding:12px; border-radius:4px; border-left:4px solid #7090cb; margin-top:5px; border-top:1px solid #e2e8f0; border-right:1px solid #e2e8f0; border-bottom:1px solid #e2e8f0;">
                                <strong style="color:#475569; font-size:0.85rem; display:block; margin-bottom:5px; text-transform:uppercase;"><i class="ph-bold ph-warning-circle"></i> Problemática a Resolver:</strong>
                                <p style="margin:0; font-size:0.95rem; color:#334155; line-height:1.4;"><?php echo nl2br(htmlspecialchars($p['descripcion_problema'])); ?></p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="ge-project-info">
                            <span style="font-size: 0.85rem; color: #7090cb; font-weight:bold; margin-bottom: 2px;"><i class="ph-bold ph-graduation-cap"></i> <?php echo htmlspecialchars($nivelFila); ?></span>
                            <span style="font-size: 0.8rem; color: #64748b; font-family: monospace; margin-bottom: 2px;">Ref: <?php echo htmlspecialchars($p['codigo_seguimiento'] ?? 'N/A'); ?></span>
                            <span style="font-size: 0.8rem; color: #94a3b8; display:block;">
                                <i class="ph-bold ph-calendar"></i> Fecha: <?php echo date('d/m/Y', strtotime($p['fecha_creacion'])); ?>
                            </span>
                            
                            <?php if ($estadoFila === 'rechazada' && !empty($p['motivo_rechazo'])): ?>
                                <div style="margin-top:10px; font-size:0.85rem; color:#a9a8a6; background:#f4f7fb; padding:8px; border-radius:4px; border: 1px dashed #a9a8a6;">
                                    <b>Motivo del rechazo:</b><br>
                                    <i><?php echo htmlspecialchars($p['motivo_rechazo']); ?></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div class="ge-actions">
                            <?php if ($estadoFila === 'pendiente'): ?>
                                <button class="ge-btn-aprobar" onclick="abrirEvaluar(
                                    <?php echo $p['id']; ?>,
                                    '<?php echo htmlspecialchars(addslashes($p['nombre_empresa'])); ?>',
                                    '<?php echo htmlspecialchars(addslashes($p['persona_contacto'])); ?>',
                                    '<?php echo htmlspecialchars(addslashes($p['area_afectada'])); ?>',
                                    `<?php echo htmlspecialchars(addslashes(preg_replace('/\r|\n/', ' ', $p['descripcion_problema']))); ?>`
                                )">
                                    <i class="ph-bold ph-magnifying-glass"></i> Evaluar
                                </button>
                            <?php elseif ($estadoFila === 'aceptada'): ?>
                                <div style="background:#f4f7fb; color:#505984; padding:10px; border-radius:6px; text-align:center; font-weight:bold; font-size:0.9rem; border:1px solid #505984;">
                                    <i class="ph-bold ph-check-circle"></i> Aceptada
                                </div>
                            <?php elseif ($estadoFila === 'rechazada'): ?>
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

<!-- MODAL CRM PARA EVALUAR -->
<div class="ve-modal-overlay" id="proyectoModal">
    <div class="ve-modal-content">
        <span class="ve-modal-close" onclick="cerrarModal()">&times;</span>
        <div class="ve-modal-body">
            <h2 id="modalTitulo" style="margin-top:0; color:#121a3e;">Evaluar Propuesta</h2>
            
            <div style="background:#f8fafc; padding:15px; border-radius:8px; border:1px solid #e2e8f0; margin-bottom:1rem;">
                <p style="margin:0 0 5px 0;"><strong>Organización:</strong> <span id="modalEmpresa"></span></p>
                <p style="margin:0 0 5px 0;"><strong>Contacto:</strong> <span id="modalContacto"></span></p>
                <p style="margin:0;"><strong>Área:</strong> <span id="modalArea"></span></p>
            </div>
            
            <h4 style="color:#475569; margin-bottom:5px;">Planteamiento Tecnológico</h4>
            <div id="modalDescripcion" style="padding:15px; border-left:4px solid #7090cb; background:#f1f5f9; color:#334155; font-style:italic; margin-bottom:1.5rem;">
                Cargando...
            </div>
            
            <form action="?ruta=procesar-propuesta" method="POST" id="formEvaluacion">
                <input type="hidden" name="id_propuesta" id="modalIdPropuesta" value="">
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="font-weight: bold; margin-bottom:10px; display:block;">Decisión del Comité:</label>
                    <div style="display:flex; gap:10px;">
                        <button type="button" id="btnRadioAprobar" onclick="selectDecision('aprobar')" style="flex:1; padding:10px; border:2px solid #cbd5e1; background:white; border-radius:8px; cursor:pointer; font-weight:bold; color:#64748b; transition:all 0.2s;">Aprobar Proyecto</button>
                        <button type="button" id="btnRadioRechazar" onclick="selectDecision('rechazar')" style="flex:1; padding:10px; border:2px solid #cbd5e1; background:white; border-radius:8px; cursor:pointer; font-weight:bold; color:#64748b; transition:all 0.2s;">Rechazar</button>
                    </div>
                </div>

                <!-- Bloque Aprobar -->
                <div id="bloqueAprobar" style="display:none; background:#f4f7fb; padding:15px; border-radius:8px; border:1px solid #7090cb; margin-bottom:1.5rem;">
                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 100%;">
                            <label style="font-weight: 600; font-size: 0.9rem; margin-bottom: 5px; display: block; color:#505984;">Área Afectada o Tema del Proyecto:</label>
                            <input type="text" name="area_afectada" id="input_area_afectada" class="ve-input" style="width: 100%;" placeholder="Ej. Control de Inventario y Ventas" required>
                        </div>
                        <div style="flex: 1; min-width: 250px;">
                            <label style="font-weight: 600; font-size: 0.9rem; margin-bottom: 5px; display: block; color:#505984;">Asignar a Trayecto:</label>
                            <select name="nivel_trayecto" class="ve-input" style="width: 100%;">
                                <option value="Trayecto I">Trayecto I</option>
                                <option value="Trayecto II">Trayecto II</option>
                                <option value="Trayecto III">Trayecto III</option>
                                <option value="Trayecto IV">Trayecto IV</option>
                            </select>
                        </div>
                        <div style="flex: 1; min-width: 250px;">
                            <label style="font-weight: 600; font-size: 0.9rem; margin-bottom: 5px; display: block; color:#505984;">Límite de Integrantes:</label>
                            <input type="number" name="cupos_disponibles" value="3" min="1" max="10" class="ve-input" style="width: 100%;" required>
                        </div>
                        <div style="flex: 1; min-width: 100%; margin-top:10px;">
                            <label style="font-weight: 600; font-size: 0.9rem; margin-bottom: 5px; display: block; color:#505984;">Línea de Investigación:</label>
                            <select name="id_linea" id="select_linea" class="ve-input" style="width: 100%;" onchange="cargarDimensiones(this.value)">
                                <option value="">Seleccione una línea de investigación...</option>
                                <?php foreach ($lineas as $l): ?>
                                    <option value="<?php echo $l['id']; ?>"><?php echo htmlspecialchars($l['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div style="flex: 1; min-width: 100%; margin-top:10px;">
                            <label style="font-weight: 600; font-size: 0.9rem; margin-bottom: 5px; display: block; color:#505984;">Dimensión Operativa:</label>
                            <select name="id_dimension" id="select_dimension" class="ve-input" style="width: 100%;">
                                <option value="">Seleccione primero la línea de investigación...</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Bloque Rechazar -->
                <div id="bloqueRechazar" style="display:none; background:#f4f7fb; padding:15px; border-radius:8px; border:1px solid #a9a8a6; margin-bottom:1.5rem;">
                    <label style="font-weight: 600; font-size: 0.9rem; margin-bottom: 5px; display: block; color:#505984;">Motivo de Rechazo (Feedback para la empresa):</label>
                    <textarea name="motivo_rechazo" id="input_motivo_rechazo" class="ve-textarea" placeholder="Ej. El proyecto requiere de un alcance mayor al académico." style="width:100%; border-color:#a9a8a6;"></textarea>
                </div>

                <div style="text-align: right;">
                    <button type="submit" name="accion" id="btnSubmitFinal" value="" class="ve-btn-aceptar" style="display:none; width:100%; padding:15px; font-size:1.1rem;">Confirmar Decisión</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Data precargada en JS
const listadoDimensiones = <?php echo json_encode($dimensiones); ?>;

function cargarDimensiones(idLinea) {
    const dimSelect = document.getElementById('select_dimension');
    dimSelect.innerHTML = '<option value="">Seleccione una dimensión operativa...</option>';
    
    if(!idLinea) return;
    
    const dims = listadoDimensiones.filter(d => d.id_linea == idLinea);
    if(dims.length === 0) {
        dimSelect.innerHTML = '<option value="">Sin dimensiones registradas para esta línea</option>';
        return;
    }
    
    dims.forEach(d => {
        dimSelect.innerHTML += `<option value="${d.id}">${d.nombre}</option>`;
    });
}

// Filtros
let currentTrayecto = 'Todas';
let currentEstado = 'pendiente';

function setFilter(tipo, valor, btnElement) {
    if (tipo === 'trayecto') {
        currentTrayecto = valor;
        const botones = document.querySelectorAll('#filtro-trayecto .ge-tab-btn');
        botones.forEach(b => b.classList.remove('active'));
    } else {
        currentEstado = valor;
        const botones = document.querySelectorAll('#filtro-estado .ge-tab-btn');
        botones.forEach(b => b.classList.remove('active'));
    }
    btnElement.classList.add('active');
    aplicarFiltros();
}

function aplicarFiltros() {
    const filas = document.querySelectorAll('.propuesta-row');
    filas.forEach(fila => {
        const t = fila.getAttribute('data-trayecto');
        const e = fila.getAttribute('data-estado');
        
        let matchTrayecto = (currentTrayecto === 'Todas') || (t === currentTrayecto);
        let matchEstado = (currentEstado === 'Todos') || (e === currentEstado);
        
        if (matchTrayecto && matchEstado) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}

// Modal Logic
function abrirEvaluar(id, empresa, contacto, area, descripcion) {
    document.getElementById('modalIdPropuesta').value = id;
    document.getElementById('modalEmpresa').textContent = empresa;
    document.getElementById('modalContacto').textContent = contacto;
    document.getElementById('modalArea').textContent = area;
    document.getElementById('modalDescripcion').textContent = descripcion;
    
    // Rellenar input, si dice "Por evaluar", lo limpiamos para forzar que el comité lo escriba
    document.getElementById('input_area_afectada').value = area.startsWith('Por evaluar') ? '' : area;
    
    document.getElementById('proyectoModal').classList.add('active');
    
    // Reseteamos UI
    document.getElementById('btnSubmitFinal').style.display = 'none';
    document.getElementById('bloqueAprobar').style.display = 'none';
    document.getElementById('bloqueRechazar').style.display = 'none';
    
    document.getElementById('btnRadioAprobar').style.background = 'white';
    document.getElementById('btnRadioAprobar').style.color = '#64748b';
    document.getElementById('btnRadioAprobar').style.borderColor = '#cbd5e1';
    
    document.getElementById('btnRadioRechazar').style.background = 'white';
    document.getElementById('btnRadioRechazar').style.color = '#64748b';
    document.getElementById('btnRadioRechazar').style.borderColor = '#cbd5e1';

    // Hacer los select requeridos si aprueba, opcionales si no.
    document.getElementById('select_linea').removeAttribute('required');
    document.getElementById('select_dimension').removeAttribute('required');
    document.getElementById('input_area_afectada').removeAttribute('required');
    document.getElementById('input_motivo_rechazo').removeAttribute('required');
}

function cerrarModal() {
    document.getElementById('proyectoModal').classList.remove('active');
}

function selectDecision(decision) {
    const btnSubmit = document.getElementById('btnSubmitFinal');
    btnSubmit.style.display = 'block';
    btnSubmit.value = decision;
    
    const btnA = document.getElementById('btnRadioAprobar');
    const btnR = document.getElementById('btnRadioRechazar');
    const bA = document.getElementById('bloqueAprobar');
    const bR = document.getElementById('bloqueRechazar');
    
    if (decision === 'aprobar') {
        btnA.style.background = '#f4f7fb';
        btnA.style.color = '#505984';
        btnA.style.borderColor = '#505984';
        
        btnR.style.background = 'white';
        btnR.style.color = '#64748b';
        btnR.style.borderColor = '#cbd5e1';
        
        bA.style.display = 'block';
        bR.style.display = 'none';
        
        btnSubmit.textContent = 'Aprobar y Ofertar Proyecto';
        
        document.getElementById('select_linea').setAttribute('required', 'required');
        document.getElementById('select_dimension').setAttribute('required', 'required');
        document.getElementById('input_area_afectada').setAttribute('required', 'required');
        document.getElementById('input_motivo_rechazo').removeAttribute('required');
        
    } else {
        btnR.style.background = '#f4f7fb';
        btnR.style.color = '#505984';
        btnR.style.borderColor = '#a9a8a6';
        
        btnA.style.background = 'white';
        btnA.style.color = '#64748b';
        btnA.style.borderColor = '#cbd5e1';
        
        bR.style.display = 'block';
        bA.style.display = 'none';
        
        btnSubmit.textContent = 'Rechazar Postulación';
        
        document.getElementById('select_linea').removeAttribute('required');
        document.getElementById('select_dimension').removeAttribute('required');
        document.getElementById('input_area_afectada').removeAttribute('required');
        document.getElementById('input_motivo_rechazo').setAttribute('required', 'required');
    }
}

// Inicializar el primer filtro
document.addEventListener('DOMContentLoaded', () => {
    aplicarFiltros();
});

<?php if (isset($_SESSION['flash_success'])): ?>
    Swal.fire({
        icon: 'success',
        title: '¡Operación Exitosa!',
        text: '<?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES) ?>',
        confirmButtonColor: '#10b981'
    });
<?php unset($_SESSION['flash_success']); endif; ?>

<?php if (isset($_SESSION['flash_error'])): ?>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '<?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES) ?>',
        confirmButtonColor: '#ef4444'
    });
<?php unset($_SESSION['flash_error']); endif; ?>

</script>
