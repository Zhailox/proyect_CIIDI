<?php
require_once __DIR__ . '/../models/PropuestaEmpresaModel.php';
$modelo_pe = new PropuestaEmpresaModel();
$roles_profesor = ['Profesor', 'Super Administrador', 'Comite'];
$esProfesor = isset($_SESSION['rol_nombre']) && in_array($_SESSION['rol_nombre'], $roles_profesor);

$todas = $modelo_pe->getTodas();

// KPIs
$kpiNuevas = 0;
$kpiAprobadas = 0;
$kpiRechazadas = 0;
foreach($todas as $p) {
    if ($p['estado'] === 'pendiente') $kpiNuevas++;
    elseif ($p['estado'] === 'aceptada') $kpiAprobadas++;
    elseif ($p['estado'] === 'rechazada') $kpiRechazadas++;
}
$tasa = ($kpiAprobadas + $kpiRechazadas) > 0 ? round(($kpiAprobadas / ($kpiAprobadas + $kpiRechazadas)) * 100) : 0;
?>

<style>
/* Dashboard Styles */
.ve-kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
.ve-kpi-card { background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 1rem; border: 1px solid #e2e8f0; }
.ve-kpi-icon { font-size: 2.5rem; color: #121a3e; background: #f1f5f9; padding: 0.8rem; border-radius: 10px; }
.ve-kpi-text h3 { margin: 0; font-size: 1.8rem; color: #121a3e; }
.ve-kpi-text p { margin: 0; color: #64748b; font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

/* Tabs */
.ve-nav-tabs { display: flex; gap: 1rem; border-bottom: 2px solid #e2e8f0; margin-bottom: 1.5rem; }
.ve-nav-btn { padding: 0.8rem 1.5rem; border: none; background: none; font-weight: bold; color: #94a3b8; cursor: pointer; font-size: 1rem; position: relative; }
.ve-nav-btn.active { color: #121a3e; }
.ve-nav-btn.active::after { content: ''; position: absolute; bottom: -2px; left: 0; width: 100%; height: 3px; background: #121a3e; border-radius: 3px 3px 0 0; }
.ve-tab-content { display: none; }
.ve-tab-content.active { display: block; }

/* Tables */
.ve-table-modern { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
.ve-table-modern th { background: transparent; color: #64748b; font-weight: 600; padding: 0 1rem 0.5rem; text-align: left; border-bottom: 2px solid #e2e8f0; }
.ve-table-modern td { background: white; padding: 1.2rem 1rem; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; }
.ve-table-modern td:first-child { border-left: 1px solid #e2e8f0; border-radius: 8px 0 0 8px; }
.ve-table-modern td:last-child { border-right: 1px solid #e2e8f0; border-radius: 0 8px 8px 0; }
.ve-table-modern tr { box-shadow: 0 2px 4px rgba(0,0,0,0.02); }

.ve-badge { padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: bold; }
.ve-badge.pendiente { background: #fef3c7; color: #d97706; }
.ve-badge.aceptada { background: #d1fae5; color: #059669; }
.ve-badge.rechazada { background: #fee2e2; color: #dc2626; }
</style>

<div class="ve-wrapper" style="min-height: 80vh; background: #f4f7fb; padding: 2rem;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="color: #121a3e; margin: 0;"><i class="ph-bold ph-briefcase"></i> Panel del Comité Evaluador</h2>
    </div>

    <!-- KPIs -->
    <div class="ve-kpi-grid">
        <div class="ve-kpi-card">
            <i class="ph-fill ph-envelope-simple ve-kpi-icon"></i>
            <div class="ve-kpi-text">
                <h3><?php echo $kpiNuevas; ?></h3>
                <p>Nuevas Solicitudes</p>
            </div>
        </div>
        <div class="ve-kpi-card">
            <i class="ph-fill ph-check-circle ve-kpi-icon" style="color: #10b981;"></i>
            <div class="ve-kpi-text">
                <h3><?php echo $kpiAprobadas; ?></h3>
                <p>Proyectos Ofertados</p>
            </div>
        </div>
        <div class="ve-kpi-card">
            <i class="ph-fill ph-chart-line-up ve-kpi-icon" style="color: #3b82f6;"></i>
            <div class="ve-kpi-text">
                <h3><?php echo $tasa; ?>%</h3>
                <p>Tasa de Aprobación</p>
            </div>
        </div>
    </div>

    <!-- TABS -->
    <div class="ve-nav-tabs">
        <button class="ve-nav-btn active" onclick="switchMainTab('pendientes')">
            <i class="ph-bold ph-tray"></i> Bandeja de Entrada (<?php echo $kpiNuevas; ?>)
        </button>
        <button class="ve-nav-btn" onclick="switchMainTab('aprobadas')">
            <i class="ph-bold ph-check-square"></i> Ofertas Activas
        </button>
        <button class="ve-nav-btn" onclick="switchMainTab('rechazadas')">
            <i class="ph-bold ph-archive"></i> Historial Archivadas
        </button>
    </div>

    <!-- TAB PENDIENTES -->
    <div id="tab-pendientes" class="ve-tab-content active">
        <table class="ve-table-modern">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Organización</th>
                    <th>Área / Requerimiento</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($todas as $p): if($p['estado'] !== 'pendiente') continue; ?>
                <tr>
                    <td><strong><?php echo $p['codigo_seguimiento'] ?? 'N/A'; ?></strong></td>
                    <td><?php echo htmlspecialchars($p['nombre_empresa']); ?></td>
                    <td><?php echo htmlspecialchars($p['area_afectada']); ?></td>
                    <td><?php echo date('d M, Y', strtotime($p['fecha_creacion'])); ?></td>
                    <td>
                        <button onclick="abrirRevision(<?php echo htmlspecialchars(json_encode($p)); ?>)" style="background:#121a3e; color:white; border:none; padding:8px 15px; border-radius:6px; cursor:pointer; font-weight:bold;">
                            Evaluar
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- TAB APROBADAS -->
    <div id="tab-aprobadas" class="ve-tab-content">
        <table class="ve-table-modern">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Organización</th>
                    <th>Nivel Asignado</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($todas as $p): if($p['estado'] !== 'aceptada') continue; ?>
                <tr>
                    <td><strong><?php echo $p['codigo_seguimiento'] ?? 'N/A'; ?></strong></td>
                    <td><?php echo htmlspecialchars($p['nombre_empresa']); ?></td>
                    <td><span class="ve-badge aceptada"><?php echo htmlspecialchars($p['nivel_trayecto']); ?></span></td>
                    <td><span class="ve-badge aceptada">OFERTADA</span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- TAB RECHAZADAS -->
    <div id="tab-rechazadas" class="ve-tab-content">
        <table class="ve-table-modern">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Organización</th>
                    <th>Motivo de Rechazo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($todas as $p): if($p['estado'] !== 'rechazada') continue; ?>
                <tr>
                    <td><strong><?php echo $p['codigo_seguimiento'] ?? 'N/A'; ?></strong></td>
                    <td><?php echo htmlspecialchars($p['nombre_empresa']); ?></td>
                    <td style="color:#ef4444;"><i><?php echo htmlspecialchars($p['motivo_rechazo'] ?? 'Sin motivo registrado'); ?></i></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- MODAL CRM PARA EVALUAR -->
<div class="ve-modal-overlay" id="proyectoModal">
    <div class="ve-modal-content" style="max-width: 600px;">
        <span class="ve-modal-close" onclick="cerrarModal()">&times;</span>
        <div class="ve-modal-body">
            <h2 id="modalTitulo" style="margin-top:0; color:#121a3e;">Evaluar Propuesta</h2>
            
            <div style="background:#f8fafc; padding:15px; border-radius:8px; border:1px solid #e2e8f0; margin-bottom:1rem;">
                <p style="margin:0 0 5px 0;"><strong>Organización:</strong> <span id="modalEmpresa"></span></p>
                <p style="margin:0 0 5px 0;"><strong>Contacto:</strong> <span id="modalContacto"></span></p>
                <p style="margin:0;"><strong>Área:</strong> <span id="modalArea"></span></p>
            </div>
            
            <h4 style="color:#475569; margin-bottom:5px;">Planteamiento Tecnológico</h4>
            <div id="modalDescripcion" style="padding:15px; border-left:4px solid #121a3e; background:#f1f5f9; color:#334155; font-style:italic; margin-bottom:1.5rem;">
                Cargando...
            </div>
            
            <form action="?ruta=procesar-propuesta" method="POST" id="formEvaluacion">
                <input type="hidden" name="id_propuesta" id="modalIdPropuesta" value="">
                
                <!-- Selector de Acción Rápida -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="font-weight: bold; margin-bottom:10px; display:block;">Decisión del Comité:</label>
                    <div style="display:flex; gap:10px;">
                        <button type="button" id="btnRadioAprobar" onclick="selectDecision('aprobar')" style="flex:1; padding:10px; border:2px solid #cbd5e1; background:white; border-radius:8px; cursor:pointer; font-weight:bold; color:#64748b;">Aprobar Proyecto</button>
                        <button type="button" id="btnRadioRechazar" onclick="selectDecision('rechazar')" style="flex:1; padding:10px; border:2px solid #cbd5e1; background:white; border-radius:8px; cursor:pointer; font-weight:bold; color:#64748b;">Rechazar</button>
                    </div>
                </div>

                <!-- Bloque Aprobar -->
                <div id="bloqueAprobar" style="display:none; background:#ecfdf5; padding:15px; border-radius:8px; border:1px solid #a7f3d0; margin-bottom:1.5rem;">
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px;">
                            <label style="font-weight: 600; font-size: 0.9rem; margin-bottom: 5px; display: block; color:#065f46;">Asignar a Trayecto:</label>
                            <select name="nivel_trayecto" class="ve-input" style="width: 100%;">
                                <option value="Trayecto I">Trayecto I</option>
                                <option value="Trayecto II">Trayecto II</option>
                                <option value="Trayecto III">Trayecto III</option>
                                <option value="Trayecto IV">Trayecto IV</option>
                            </select>
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <label style="font-weight: 600; font-size: 0.9rem; margin-bottom: 5px; display: block; color:#065f46;">Línea de Investigación:</label>
                            <select name="id_linea" class="ve-input" style="width: 100%;">
                                <?php 
                                require_once __DIR__ . '/../../../core/Database/Connection.php';
                                $pdo = Connection::getInstance();
                                $lineas = $pdo->query("SELECT id, nombre FROM lineas_investigacion ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($lineas as $l): ?>
                                    <option value="<?php echo $l['id']; ?>"><?php echo htmlspecialchars($l['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Bloque Rechazar -->
                <div id="bloqueRechazar" style="display:none; background:#fef2f2; padding:15px; border-radius:8px; border:1px solid #fecaca; margin-bottom:1.5rem;">
                    <label style="font-weight: 600; font-size: 0.9rem; margin-bottom: 5px; display: block; color:#991b1b;">Motivo de Rechazo (Feedback para la empresa):</label>
                    <textarea name="motivo_rechazo" class="ve-textarea" placeholder="Ej. El proyecto requiere de un alcance mayor al académico." style="width:100%; border-color:#fca5a5;"></textarea>
                </div>

                <div style="text-align: right;">
                    <button type="submit" name="accion" id="btnSubmitFinal" value="" class="ve-btn-aceptar" style="display:none; width:100%; padding:15px; font-size:1.1rem;">Confirmar Decisión</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function switchMainTab(tabId) {
    document.querySelectorAll('.ve-tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.ve-nav-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById('tab-' + tabId).classList.add('active');
    event.currentTarget.classList.add('active');
}

function abrirRevision(prop) {
    document.getElementById('modalIdPropuesta').value = prop.id;
    document.getElementById('modalEmpresa').innerText = prop.nombre_empresa;
    document.getElementById('modalContacto').innerText = prop.correo_contacto + ' | ' + prop.telefono_contacto;
    document.getElementById('modalArea').innerText = prop.area_afectada;
    document.getElementById('modalDescripcion').innerText = prop.descripcion_problema;
    
    // Reset form
    document.getElementById('bloqueAprobar').style.display = 'none';
    document.getElementById('bloqueRechazar').style.display = 'none';
    document.getElementById('btnSubmitFinal').style.display = 'none';
    document.getElementById('btnRadioAprobar').style = "flex:1; padding:10px; border:2px solid #cbd5e1; background:white; border-radius:8px; cursor:pointer; font-weight:bold; color:#64748b;";
    document.getElementById('btnRadioRechazar').style = "flex:1; padding:10px; border:2px solid #cbd5e1; background:white; border-radius:8px; cursor:pointer; font-weight:bold; color:#64748b;";
    
    document.getElementById('proyectoModal').classList.add('active');
}

function selectDecision(decision) {
    document.getElementById('btnSubmitFinal').style.display = 'block';
    document.getElementById('btnSubmitFinal').value = decision;
    
    if (decision === 'aprobar') {
        document.getElementById('bloqueAprobar').style.display = 'block';
        document.getElementById('bloqueRechazar').style.display = 'none';
        
        document.getElementById('btnRadioAprobar').style = "flex:1; padding:10px; border:2px solid #10b981; background:#ecfdf5; border-radius:8px; cursor:pointer; font-weight:bold; color:#065f46;";
        document.getElementById('btnRadioRechazar').style = "flex:1; padding:10px; border:2px solid #cbd5e1; background:white; border-radius:8px; cursor:pointer; font-weight:bold; color:#64748b;";
        
        document.getElementById('btnSubmitFinal').innerText = "Inyectar a la Cartelera de Ofertas";
        document.getElementById('btnSubmitFinal').style.background = "#10b981";
    } else {
        document.getElementById('bloqueAprobar').style.display = 'none';
        document.getElementById('bloqueRechazar').style.display = 'block';
        
        document.getElementById('btnRadioRechazar').style = "flex:1; padding:10px; border:2px solid #ef4444; background:#fef2f2; border-radius:8px; cursor:pointer; font-weight:bold; color:#991b1b;";
        document.getElementById('btnRadioAprobar').style = "flex:1; padding:10px; border:2px solid #cbd5e1; background:white; border-radius:8px; cursor:pointer; font-weight:bold; color:#64748b;";
        
        document.getElementById('btnSubmitFinal').innerText = "Archivar y Notificar Rechazo";
        document.getElementById('btnSubmitFinal').style.background = "#ef4444";
    }
}

function cerrarModal() {
    document.getElementById('proyectoModal').classList.remove('active');
}
</script>