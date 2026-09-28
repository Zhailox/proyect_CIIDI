<?php require_once __DIR__ . '/../services/ConfigService.php'; ?>
<?php
// Datos provienen de VinculacionController.php
?>
<style>
/* Estilos para Gestión de Equipos */
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

.ge-pagination-controls { display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e2e8f0; }
.ge-page-btn { background: #f8fafc; border: 1px solid #cbd5e1; padding: 5px 10px; cursor: pointer; border-radius: 4px; color: #475569; }
.ge-page-btn.active { background: #121a3e; color: white; border-color: #121a3e; }
.ge-page-btn:disabled { opacity: 0.5; cursor: not-allowed; }
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

    <?php if (empty($postulaciones)): ?>
        <div style="text-align: center; padding: 5rem 2rem; background: white; border-radius: 12px; border: 1px dashed #cbd5e1;">
            <i class="ph-fill ph-check-circle" style="font-size: 4rem; color: #7090cb; margin-bottom: 1rem;"></i>
            <h3 style="color: #1e293b;">Todo al día</h3>
            <p style="color: #64748b;">No hay postulaciones registradas.</p>
        </div>
    <?php else: ?>
        
        
<?php 
// Extraer líneas y dimensiones únicas para los filtros
$lineasArray = [];
$dimensionesArray = [];
foreach ($postulaciones as $p) {
    $ln = !empty($p['linea_nombre']) ? $p['linea_nombre'] : 'Sin Línea';
    $dn = !empty($p['dimension_nombre']) ? $p['dimension_nombre'] : 'Sin Dimensión';
    
    if(!isset($lineasArray[$ln])) $lineasArray[$ln] = 0;
    if(!isset($dimensionesArray[$dn])) $dimensionesArray[$dn] = 0;
    
    $lineasArray[$ln]++;
    $dimensionesArray[$dn]++;
}
ksort($lineasArray);
ksort($dimensionesArray);
?>

<!-- Pestañas Filtro: Trayecto -->
        <div class="ge-tabs" id="filtro-trayecto">
            <span style="font-size:0.85rem; color:#94a3b8; font-weight:bold; padding: 0.8rem 0; margin-right: 1rem; text-transform:uppercase;">TRAYECTO:</span>
            <button class="ge-tab-btn active" data-filter="trayecto" data-value="Todas">Todas <span style="background:#e2e8f0; padding:2px 6px; border-radius:10px; font-size:0.75rem; margin-left:5px;"><?php echo count($postulaciones); ?></span></button>
            <?php foreach ($trayectos as $nivel => $count): ?>
                <button class="ge-tab-btn" data-filter="trayecto" data-value="<?= htmlspecialchars($nivel) ?>">
                    <?= htmlspecialchars($nivel) ?> <span style="background:#e2e8f0; padding:2px 6px; border-radius:10px; font-size:0.75rem; margin-left:5px;"><?= $count ?></span>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Pestañas Filtro: Estado -->
        <div class="ge-tabs" id="filtro-estado" style="border-bottom:none; margin-top:-10px;">
            <span style="font-size:0.85rem; color:#94a3b8; font-weight:bold; padding: 0.8rem 0; margin-right: 1rem; text-transform:uppercase;">ESTADO:</span>
            <button class="ge-tab-btn" data-filter="estado" data-value="Todos">Todos</button>
            <button class="ge-tab-btn active" data-filter="estado" data-value="Pendiente">
                En Espera <span style="background:#e2e8f0; padding:2px 6px; border-radius:10px; font-size:0.75rem; margin-left:5px;"><?= $estados['Pendiente'] ?></span>
            </button>
            <button class="ge-tab-btn" data-filter="estado" data-value="Aceptado">
                Aceptadas <span style="background:#f4f7fb; color:#505984; padding:2px 6px; border-radius:10px; font-size:0.75rem; margin-left:5px; border:1px solid #7090cb;"><?= $estados['Aceptado'] ?></span>
            </button>
            <button class="ge-tab-btn" data-filter="estado" data-value="Rechazado">
                Rechazadas <span style="background:#f4f7fb; color:#a9a8a6; padding:2px 6px; border-radius:10px; font-size:0.75rem; margin-left:5px; border:1px solid #a9a8a6;"><?= $estados['Rechazado'] ?></span>
            </button>
        </div>

        <!-- Filtros Extendidos -->
        <div style="display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px;">
                <label style="font-size:0.85rem; color:#94a3b8; font-weight:bold; display:block; margin-bottom:5px; text-transform:uppercase;">Línea de Investigación:</label>
                <select id="filtro-linea" class="ge-extended-filter" style="width:100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; background: #f8fafc; font-size: 0.95rem; color: #121a3e; outline:none;">
                    <option value="Todas">Todas las Líneas</option>
                    <?php foreach($lineasArray as $ln => $c): ?>
                        <option value="<?= htmlspecialchars($ln) ?>"><?= htmlspecialchars($ln) ?> (<?= $c ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="flex: 1; min-width: 250px;">
                <label style="font-size:0.85rem; color:#94a3b8; font-weight:bold; display:block; margin-bottom:5px; text-transform:uppercase;">Dimensión Operativa:</label>
                <select id="filtro-dimension" class="ge-extended-filter" style="width:100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; background: #f8fafc; font-size: 0.95rem; color: #121a3e; outline:none;">
                    <option value="Todas">Todas las Dimensiones</option>
                    <?php foreach($dimensionesArray as $dn => $c): ?>
                        <option value="<?= htmlspecialchars($dn) ?>"><?= htmlspecialchars($dn) ?> (<?= $c ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
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
                <tr class="equipo-row" data-trayecto="<?= htmlspecialchars($nivelFila) ?>" data-estado="<?= htmlspecialchars($estadoFila) ?>" data-linea="<?= htmlspecialchars(!empty($p['linea_nombre']) ? $p['linea_nombre'] : 'Sin Línea') ?>" data-dimension="<?= htmlspecialchars(!empty($p['dimension_nombre']) ? $p['dimension_nombre'] : 'Sin Dimensión') ?>">
                    <td>
                        <div class="ge-student-info">
                            <span class="ge-student-name" title="Líder del Proyecto">
                                <i class="ph-fill ph-star" style="color:#7090cb;"></i> <?php echo htmlspecialchars($p['estudiante']); ?>
                            </span>
                            <span class="ge-student-email"><?php echo htmlspecialchars($p['correo']); ?></span>
                            <span class="ge-student-email">C.I: <?php echo htmlspecialchars($p['cedula'] ?? 'N/A'); ?></span>
                            
                            <?php if ($hasCompaneros): ?>
                                <div style="margin-top: 0.8rem;">
                                    <button type="button" class="btn-ver-equipo" style="font-size: 0.8rem; color: #505984; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; background: #f4f7fb; padding: 4px 10px; border-radius: 20px; font-weight: 600; border: 1px solid #7090cb; transition: background 0.2s;" data-html="<?php echo htmlspecialchars($listaEquipoHTML, ENT_QUOTES, 'UTF-8'); ?>">
                                        <i class="ph-bold ph-users-three"></i> +<?= count($equipoArray) ?> Compañeros
                                    </button>
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
                                <button type="button" class="btn-ver-proyecto" style="font-size: 0.8rem; color: #505984; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; background: #f4f7fb; padding: 4px 10px; border-radius: 20px; font-weight: 600; border: 1px solid #a9a8a6; transition: background 0.2s;" data-html="<?php echo htmlspecialchars($proyectoHTML, ENT_QUOTES, 'UTF-8'); ?>">
                                    <i class="ph-bold ph-info"></i> Detalles de Empresa
                                </button>
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
                                <form action="?ruta=procesar-asignacion" method="POST" class="form-aprobar">
                                    <input type="hidden" name="id_postulacion" value="<?php echo $p['id_postulacion']; ?>">
                                    <input type="hidden" name="id_investigacion" value="<?php echo $p['id_investigacion']; ?>">
                                    <input type="hidden" name="estado" value="Aceptado">
                                    <button type="button" class="ge-btn-aprobar btn-aprobar-action" data-html="<?php echo htmlspecialchars($listaEquipoHTML, ENT_QUOTES, 'UTF-8'); ?>" title="Aprobar y Asignar Equipo"><i class="ph-bold ph-check"></i> Asignar</button>
                                </form>
                                
                                <form action="?ruta=procesar-asignacion" method="POST" id="form-rechazar-<?php echo $p['id_postulacion']; ?>">
                                    <input type="hidden" name="id_postulacion" value="<?php echo $p['id_postulacion']; ?>">
                                    <input type="hidden" name="id_investigacion" value="<?php echo $p['id_investigacion']; ?>">
                                    <input type="hidden" name="estado" value="Rechazado">
                                    <input type="hidden" name="motivo_rechazo" id="motivo-rechazo-<?php echo $p['id_postulacion']; ?>" value="">
                                    <button type="button" class="ge-btn-rechazar" title="Rechazar Postulación" onclick="confirmarRechazo(<?php echo $p['id_postulacion']; ?>)"><i class="ph-bold ph-x"></i> Rechazar</button>
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
        
        <div id="ge-pagination-container" class="ge-pagination-controls"></div>
        
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Modales Seguros (XSS Prevented)
    const btnsEquipo = document.querySelectorAll('.btn-ver-equipo');
    btnsEquipo.forEach(btn => {
        btn.addEventListener('click', function() {
            Swal.fire({
                title: 'Detalles del Equipo',
                html: this.getAttribute('data-html'),
                icon: 'info',
                confirmButtonColor: '#505984',
                iconColor: '#505984'
            });
        });
    });

    const btnsProyecto = document.querySelectorAll('.btn-ver-proyecto');
    btnsProyecto.forEach(btn => {
        btn.addEventListener('click', function() {
            Swal.fire({
                title: 'Detalles de Empresa',
                html: this.getAttribute('data-html'),
                icon: 'info',
                confirmButtonColor: '#505984',
                iconColor: '#505984'
            });
        });
    });

    const btnsAprobar = document.querySelectorAll('.btn-aprobar-action');
    btnsAprobar.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.form-aprobar');
            const equipoHtml = this.getAttribute('data-html');
            
            Swal.fire({
                title: '¿Asignar este equipo?',
                html: `
                    <p style="font-size:0.95rem; color:#475569; margin-bottom:15px;">
                        Al aprobar esta postulación, los demás equipos que hayan aplicado a este mismo proyecto serán <strong>rechazados automáticamente</strong>.
                    </p>
                    ${equipoHtml}
                `,
                icon: 'warning',
                iconColor: '#505984',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Sí, aprobar y asignar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    window.confirmarRechazo = function(idPostulacion) {
        Swal.fire({
            title: '¿Rechazar postulación?',
            text: 'Ingresa el motivo del rechazo para informar al equipo:',
            input: 'textarea',
            inputPlaceholder: 'Ej: El equipo no cumple con los requerimientos técnicos.',
            icon: 'warning',
            iconColor: '#e11d48',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Sí, rechazar',
            cancelButtonText: 'Cancelar',
            preConfirm: (motivo) => {
                if (!motivo || motivo.trim() === '') {
                    Swal.showValidationMessage('El motivo del rechazo es obligatorio');
                }
                return motivo;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('motivo-rechazo-' + idPostulacion).value = result.value;
                document.getElementById('form-rechazar-' + idPostulacion).submit();
            }
        });
    };

    // 2. Lógica de Paginación y Filtros (Client-Side)
    const rows = Array.from(document.querySelectorAll('.equipo-row'));
    let filteredRows = [...rows];
    let currentPage = 1;
    const itemsPerPage = <?= htmlspecialchars(VinculacionConfigService::get('paginacion_gestion', 8)) ?>;
    const paginationContainer = document.getElementById('ge-pagination-container');
    

    let activeFilter = {
        'trayecto': 'Todas',
        'estado': 'Pendiente',
        'linea': 'Todas',
        'dimension': 'Todas'
    };

    // Listeners para selects
    document.getElementById('filtro-linea').addEventListener('change', function() {
        activeFilter.linea = this.value;
        currentPage = 1;
        renderTable();
    });
    
    document.getElementById('filtro-dimension').addEventListener('change', function() {
        activeFilter.dimension = this.value;
        currentPage = 1;
        renderTable();
    });


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
            const matchLinea = (activeFilter.linea === 'Todas') || (r.getAttribute('data-linea') === activeFilter.linea);
            const matchDimension = (activeFilter.dimension === 'Todas') || (r.getAttribute('data-dimension') === activeFilter.dimension);
            return matchTrayecto && matchEstado && matchLinea && matchDimension;
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
        
        html += `<button class="ge-page-btn" ${currentPage === 1 ? 'disabled' : ''} onclick="window.geGoToPage(${currentPage - 1})">Anterior</button>`;

        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || Math.abs(i - currentPage) <= 1) {
                const active = i === currentPage ? 'active' : '';
                html += `<button class="ge-page-btn ${active}" onclick="window.geGoToPage(${i})">${i}</button>`;
            } else if (Math.abs(i - currentPage) === 2) {
                html += `<span style="padding: 5px 10px; color: #94a3b8;">...</span>`;
            }
        }

        html += `<button class="ge-page-btn" ${currentPage === totalPages ? 'disabled' : ''} onclick="window.geGoToPage(${currentPage + 1})">Siguiente</button>`;
        html += `</div>`;
        
        paginationContainer.innerHTML = html;
    }

    window.geGoToPage = function(page) {
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
