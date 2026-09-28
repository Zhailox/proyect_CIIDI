<?php require_once __DIR__ . '/../services/ConfigService.php'; ?>
<?php
$roles_profesor = ['Profesor', 'Super Administrador', 'Comite'];
$esProfesor = isset($_SESSION['rol_nombre']) && in_array($_SESSION['rol_nombre'], $roles_profesor);
?>

<style>
.ge-wrapper { padding: 2rem; width: 100%; margin: 0 auto; font-family: 'Inter', 'Segoe UI', sans-serif; }
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
.ve-input, .ve-textarea { padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; font-family: inherit; width: 100%; box-sizing: border-box; }
.ve-btn-aceptar { background: #505984; color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 6px; cursor: pointer; font-weight: bold; transition: background 0.2s; }
.ve-btn-aceptar:hover { background: #3c456a; }

.ge-pagination-controls { display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e2e8f0; }
.ge-page-btn { background: #f8fafc; border: 1px solid #cbd5e1; padding: 5px 10px; cursor: pointer; border-radius: 4px; color: #475569; }
.ge-page-btn.active { background: #121a3e; color: white; border-color: #121a3e; }
.ge-page-btn:disabled { opacity: 0.5; cursor: not-allowed; }
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
            <button class="ge-tab-btn active" data-filter="trayecto" data-value="Todas">Todas <span style="background:#e2e8f0; padding:2px 6px; border-radius:10px; font-size:0.75rem; margin-left:5px;"><?php echo count($todas); ?></span></button>
            <button class="ge-tab-btn" data-filter="trayecto" data-value="Sin Asignar">
                Sin Asignar <span style="background:#e2e8f0; padding:2px 6px; border-radius:10px; font-size:0.75rem; margin-left:5px;"><?= $trayectos['Sin Asignar'] ?? 0 ?></span>
            </button>
            <?php foreach ($trayectos as $nivel => $count): if($nivel === 'Sin Asignar') continue; ?>
                <button class="ge-tab-btn" data-filter="trayecto" data-value="<?= htmlspecialchars($nivel) ?>">
                    <?= htmlspecialchars($nivel) ?> <span style="background:#e2e8f0; padding:2px 6px; border-radius:10px; font-size:0.75rem; margin-left:5px;"><?= $count ?></span>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Pestañas Filtro: Estado -->
        <div class="ge-tabs" id="filtro-estado" style="border-bottom:none; margin-top:-10px;">
            <span style="font-size:0.85rem; color:#94a3b8; font-weight:bold; padding: 0.8rem 0; margin-right: 1rem; text-transform:uppercase;">ESTADO:</span>
            <button class="ge-tab-btn" data-filter="estado" data-value="Todos">Todos</button>
            <button class="ge-tab-btn active" data-filter="estado" data-value="pendiente">
                Pendientes <span style="background:#e2e8f0; padding:2px 6px; border-radius:10px; font-size:0.75rem; margin-left:5px;"><?= $estados['pendiente'] ?></span>
            </button>
            <button class="ge-tab-btn" data-filter="estado" data-value="aceptada">
                Aceptadas <span style="background:#f4f7fb; color:#505984; padding:2px 6px; border-radius:10px; font-size:0.75rem; margin-left:5px; border:1px solid #7090cb;"><?= $estados['aceptada'] ?></span>
            </button>
            <button class="ge-tab-btn" data-filter="estado" data-value="rechazada">
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
                    
                    // XSS Safe Data
                    $evalData = [
                        'id' => $p['id'],
                        'empresa' => $p['nombre_empresa'],
                        'contacto' => $p['persona_contacto'],
                        'area' => $p['area_afectada'],
                        'problema' => $p['descripcion_problema']
                    ];
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
                                <button class="ge-btn-aprobar btn-evaluar" data-eval="<?php echo htmlspecialchars(json_encode($evalData), ENT_QUOTES, 'UTF-8'); ?>">
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
        
        <div id="bp-pagination-container" class="ge-pagination-controls"></div>
        
    <?php endif; ?>
</div>

<!-- MODAL CRM PARA EVALUAR -->
<div class="ve-modal-overlay" id="proyectoModal">
    <div class="ve-modal-content">
        <span class="ve-modal-close" id="btnCloseModal">&times;</span>
        <div class="ve-modal-body">
            <h2 id="modalTitulo" style="margin-top:0; color:#121a3e;">Evaluar Propuesta</h2>
            
            <div style="background:#f8fafc; padding:15px; border-radius:8px; border:1px solid #e2e8f0; margin-bottom:1rem;">
                <p style="margin:0 0 5px 0;"><strong>Organización:</strong> <span id="modalEmpresa"></span></p>
                <p style="margin:0 0 5px 0;"><strong>Contacto:</strong> <span id="modalContacto"></span></p>
                <p style="margin:0;"><strong>Área:</strong> <span id="modalArea"></span></p>
            </div>
            
            <h4 style="color:#475569; margin-bottom:5px;">Planteamiento Tecnológico</h4>
            <div id="modalDescripcion" style="padding:15px; border-left:4px solid #7090cb; background:#f1f5f9; color:#334155; font-style:italic; margin-bottom:1.5rem; max-height:150px; overflow-y:auto;">
                Cargando...
            </div>
            
            <form action="?ruta=procesar-propuesta" method="POST" id="formEvaluacion">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                <input type="hidden" name="id_propuesta" id="modalIdPropuesta" value="">
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="font-weight: bold; margin-bottom:10px; display:block;">Decisión del Comité:</label>
                    <div style="display:flex; gap:10px;">
                        <button type="button" id="btnRadioAprobar" style="flex:1; padding:10px; border:2px solid #cbd5e1; background:white; border-radius:8px; cursor:pointer; font-weight:bold; color:#64748b; transition:all 0.2s;">Aprobar Proyecto</button>
                        <button type="button" id="btnRadioRechazar" style="flex:1; padding:10px; border:2px solid #cbd5e1; background:white; border-radius:8px; cursor:pointer; font-weight:bold; color:#64748b; transition:all 0.2s;">Rechazar</button>
                    </div>
                </div>

                <!-- Bloque Aprobar -->
                <div id="bloqueAprobar" style="display:none; background:#f4f7fb; padding:15px; border-radius:8px; border:1px solid #7090cb; margin-bottom:1.5rem;">
                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 100%;">
                            <label style="font-weight: 600; font-size: 0.9rem; margin-bottom: 5px; display: block; color:#505984;">Área Afectada o Tema del Proyecto:</label>
                            <input type="text" name="area_afectada" id="input_area_afectada" class="ve-input" style="width: 100%;" placeholder="Ej. Control de Inventario y Ventas">
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
                            <input type="number" name="cupos_disponibles" value="3" min="1" max="10" class="ve-input" style="width: 100%;">
                        </div>
                        <div style="flex: 1; min-width: 100%; margin-top:10px;">
                            <label style="font-weight: 600; font-size: 0.9rem; margin-bottom: 5px; display: block; color:#505984;">Línea de Investigación:</label>
                            <select name="id_linea" id="select_linea" class="ve-input" style="width: 100%;">
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
document.addEventListener('DOMContentLoaded', function() {
    // 1. Inyección Segura (XSS Prevented)
    const botonesEvaluar = document.querySelectorAll('.btn-evaluar');
    const modal = document.getElementById('proyectoModal');
    const btnClose = document.getElementById('btnCloseModal');
    
    botonesEvaluar.forEach(btn => {
        btn.addEventListener('click', function() {
            const data = JSON.parse(this.getAttribute('data-eval'));
            document.getElementById('modalIdPropuesta').value = data.id;
            document.getElementById('modalEmpresa').textContent = data.empresa;
            document.getElementById('modalContacto').textContent = data.contacto;
            document.getElementById('modalArea').textContent = data.area;
            document.getElementById('input_area_afectada').value = data.area;
            document.getElementById('modalDescripcion').innerHTML = data.problema.replace(/\n/g, '<br>');
            
            // Reset
            selectDecision('');
            document.getElementById('select_linea').value = '';
            document.getElementById('select_dimension').innerHTML = '<option value="">Seleccione primero la línea...</option>';
            document.getElementById('input_motivo_rechazo').value = '';
            
            modal.classList.add('active');
        });
    });

    if(btnClose) {
        btnClose.addEventListener('click', () => modal.classList.remove('active'));
    }
    window.addEventListener('click', (e) => {
        if(e.target === modal) modal.classList.remove('active');
    });

    // 2. Selectores Dependientes
    const listadoDimensiones = <?php echo json_encode($dimensiones); ?>;
    const selectLinea = document.getElementById('select_linea');
    if (selectLinea) {
        selectLinea.addEventListener('change', function() {
            const idLinea = this.value;
            const dimSelect = document.getElementById('select_dimension');
            dimSelect.innerHTML = '<option value="">Seleccione una dimensión operativa...</option>';
            
            if(!idLinea) return;
            const dims = listadoDimensiones.filter(d => d.id_linea == idLinea);
            if(dims.length === 0) {
                dimSelect.innerHTML = '<option value="">Sin dimensiones registradas para esta línea</option>';
            } else {
                dims.forEach(d => {
                    dimSelect.innerHTML += `<option value="${d.id}">${d.nombre}</option>`;
                });
            }
        });
    }

    // 3. UI Lógica de Decisión
    const btnRadioAprobar = document.getElementById('btnRadioAprobar');
    const btnRadioRechazar = document.getElementById('btnRadioRechazar');
    const bloqueAprobar = document.getElementById('bloqueAprobar');
    const bloqueRechazar = document.getElementById('bloqueRechazar');
    const btnSubmitFinal = document.getElementById('btnSubmitFinal');
    
    window.selectDecision = function(decision) {
        btnRadioAprobar.style.borderColor = '#cbd5e1';
        btnRadioAprobar.style.color = '#64748b';
        btnRadioRechazar.style.borderColor = '#cbd5e1';
        btnRadioRechazar.style.color = '#64748b';
        
        if (decision === 'aprobar') {
            btnRadioAprobar.style.borderColor = '#10b981';
            btnRadioAprobar.style.color = '#10b981';
            bloqueAprobar.style.display = 'block';
            bloqueRechazar.style.display = 'none';
            btnSubmitFinal.style.display = 'block';
            btnSubmitFinal.value = 'aprobar';
            btnSubmitFinal.style.background = '#10b981';
            btnSubmitFinal.textContent = 'Confirmar Aprobación';
            
            document.getElementById('input_area_afectada').required = true;
            document.getElementById('select_linea').required = true;
            document.getElementById('select_dimension').required = true;
            document.getElementById('input_motivo_rechazo').required = false;
        } else if (decision === 'rechazar') {
            btnRadioRechazar.style.borderColor = '#ef4444';
            btnRadioRechazar.style.color = '#ef4444';
            bloqueAprobar.style.display = 'none';
            bloqueRechazar.style.display = 'block';
            btnSubmitFinal.style.display = 'block';
            btnSubmitFinal.value = 'rechazar';
            btnSubmitFinal.style.background = '#ef4444';
            btnSubmitFinal.textContent = 'Confirmar Rechazo';
            
            document.getElementById('input_area_afectada').required = false;
            document.getElementById('select_linea').required = false;
            document.getElementById('select_dimension').required = false;
            document.getElementById('input_motivo_rechazo').required = true;
        } else {
            bloqueAprobar.style.display = 'none';
            bloqueRechazar.style.display = 'none';
            btnSubmitFinal.style.display = 'none';
        }
    };
    
    if (btnRadioAprobar) btnRadioAprobar.addEventListener('click', () => selectDecision('aprobar'));
    if (btnRadioRechazar) btnRadioRechazar.addEventListener('click', () => selectDecision('rechazar'));

    // 4. Lógica de Paginación y Filtros (Client-Side)
    const rows = Array.from(document.querySelectorAll('.propuesta-row'));
    let filteredRows = [...rows];
    let currentPage = 1;
    const itemsPerPage = <?= htmlspecialchars(VinculacionConfigService::get('paginacion_gestion', 8)) ?>;
    const paginationContainer = document.getElementById('bp-pagination-container');
    
    let activeFilter = {
        'trayecto': 'Todas',
        'estado': 'pendiente'
    };

    function renderTable() {
        // Calcular conteos dinámicos
        const countsTrayecto = { 'Todas': 0 };
        const countsEstado = { 'Todos': 0 };
        
        // Inicializar contadores a 0 basados en los botones existentes
        document.querySelectorAll('.ge-tab-btn[data-filter="trayecto"]').forEach(b => {
            countsTrayecto[b.getAttribute('data-value')] = 0;
        });
        document.querySelectorAll('.ge-tab-btn[data-filter="estado"]').forEach(b => {
            countsEstado[b.getAttribute('data-value')] = 0;
        });

        // Contar las filas que harían match si se seleccionara ese filtro
        rows.forEach(r => {
            const tr = r.getAttribute('data-trayecto');
            const es = r.getAttribute('data-estado');
            
            // Para el filtro de Trayectos (dejamos fijo el estado actual)
            if (activeFilter.estado === 'Todos' || es === activeFilter.estado) {
                countsTrayecto['Todas']++;
                if (countsTrayecto[tr] !== undefined) countsTrayecto[tr]++;
            }
            
            // Para el filtro de Estados (dejamos fijo el trayecto actual)
            if (activeFilter.trayecto === 'Todas' || tr === activeFilter.trayecto) {
                countsEstado['Todos']++;
                if (countsEstado[es] !== undefined) countsEstado[es]++;
            }
        });

        // Actualizar el DOM de los botones (buscar el span interno)
        document.querySelectorAll('.ge-tab-btn[data-filter="trayecto"]').forEach(b => {
            const val = b.getAttribute('data-value');
            const span = b.querySelector('span');
            if (span) {
                span.textContent = countsTrayecto[val] || 0;
            }
        });
        document.querySelectorAll('.ge-tab-btn[data-filter="estado"]').forEach(b => {
            const val = b.getAttribute('data-value');
            const span = b.querySelector('span');
            if (span) {
                span.textContent = countsEstado[val] || 0;
            }
        });

        rows.forEach(r => r.style.display = 'none');
        
        filteredRows = rows.filter(r => {
            const matchTrayecto = (activeFilter.trayecto === 'Todas') || (r.getAttribute('data-trayecto') === activeFilter.trayecto);
            const matchEstado = (activeFilter.estado === 'Todos') || (r.getAttribute('data-estado') === activeFilter.estado);
            return matchTrayecto && matchEstado;
        });

        const totalPages = Math.ceil(filteredRows.length / itemsPerPage) || 1;
        if (currentPage > totalPages) currentPage = totalPages;

        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        
        filteredRows.slice(start, end).forEach(r => {
            r.style.display = 'table-row';
        });

        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        if (!paginationContainer) return;
        
        if (filteredRows.length === 0) {
            paginationContainer.innerHTML = '<span style="color:#64748b; font-size:0.9rem;">No hay resultados que coincidan.</span>';
            return;
        }

        let html = `<span style="color:#64748b; font-size:0.9rem;">Página ${currentPage} de ${totalPages} &bull; ${filteredRows.length} resultados</span>`;
        html += `<div style="display:flex; gap:5px;">`;
        
        html += `<button class="ge-page-btn" ${currentPage === 1 ? 'disabled' : ''} onclick="window.bpGoToPage(${currentPage - 1})">Anterior</button>`;

        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || Math.abs(i - currentPage) <= 1) {
                const active = i === currentPage ? 'active' : '';
                html += `<button class="ge-page-btn ${active}" onclick="window.bpGoToPage(${i})">${i}</button>`;
            } else if (Math.abs(i - currentPage) === 2) {
                html += `<span style="padding: 5px 10px; color: #94a3b8;">...</span>`;
            }
        }

        html += `<button class="ge-page-btn" ${currentPage === totalPages ? 'disabled' : ''} onclick="window.bpGoToPage(${currentPage + 1})">Siguiente</button>`;
        html += `</div>`;
        
        paginationContainer.innerHTML = html;
    }

    window.bpGoToPage = function(page) {
        currentPage = page;
        renderTable();
    };

    const filterBtns = document.querySelectorAll('.ge-tab-btn');
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filterType = this.getAttribute('data-filter');
            const filterVal = this.getAttribute('data-value');
            
            const siblings = this.parentElement.querySelectorAll('.ge-tab-btn');
            siblings.forEach(s => s.classList.remove('active'));
            this.classList.add('active');
            
            activeFilter[filterType] = filterVal;
            currentPage = 1;
            renderTable();
        });
    });

    renderTable();
});
</script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
