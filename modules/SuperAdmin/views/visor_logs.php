<div class="repo-internal-header glass-banner floating-element mb-2" style="background: #ffffff !important; border: 1px solid rgba(80, 89, 132, 0.12); padding: 1.5rem 2rem; border-radius: var(--radius-md); box-shadow: 0 4px 20px rgba(18, 26, 62, 0.04);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-terciario); font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 0.3rem;">
                <i class="ph-bold ph-shield-check"></i> MONITOREO Y MONITOREO DE EVENTOS
            </div>
            <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--color-principal); margin: 0;">
                Visor de Logs & Auditoría Activa
            </h1>
            <p style="margin: 0.3rem 0 0 0; color: var(--texto-silenciado); font-size: 0.9rem;">
                Supervisión estructurada de eventos, trazabilidad administrativa (Audit Trail) y exportación de reportes PDF/CSV.
            </p>
        </div>
        <a href="sudoadmin" class="btn btn-outline" style="border-color: var(--color-secundario); color: var(--color-secundario); text-decoration: none; padding: 8px 14px; border-radius: 6px; font-weight: 600; font-size: 0.85rem;">
            <i class="ph-bold ph-arrow-left"></i> Volver al Centro de Mando
        </a>
    </div>
</div>

<div class="inbox-filters mb-1" id="log-tabs-container">
    <button class="filter-pill tab-active cursor-pointer" onclick="cambiarPestana('trail', this)">
        <i class="ph-bold ph-shield-check" style="font-size: 1.1rem; margin-right: 4px;"></i>
        Auditoría Activa (Audit Trail)
    </button>
    <button class="filter-pill cursor-pointer" onclick="cambiarPestana('db', this)">
        <i class="ph-bold ph-database" style="font-size: 1.1rem; margin-right: 4px;"></i>
        Trazabilidad DB (Triggers)
    </button>
    <button class="filter-pill cursor-pointer" onclick="cambiarPestana('auth', this)">
        <i class="ph-bold ph-users-three" style="font-size: 1.1rem; margin-right: 4px;"></i>
        Control de Accesos
    </button>
</div>

<!-- 1. PANTALLA: AUDITORÍA ACTIVA (AUDIT TRAIL) -->
<div id="pantalla-trail" class="pantalla-log log-card-panel log-active-panel">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem;">
        <h3 class="log-panel-title" style="margin:0;">Histórico de Acciones Administrativas</h3>
        
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="exportar-logs?formato=csv&nivel=<?= urlencode($f_nivel) ?>&modulo=<?= urlencode($f_modulo) ?>&fecha_inicio=<?= urlencode($f_fecha_init) ?>&fecha_fin=<?= urlencode($f_fecha_fin) ?>" class="btn btn-outline" style="font-size: 0.82rem; border-color: #0284c7; color: #0284c7; padding: 6px 12px; text-decoration: none;">
                <i class="ph-bold ph-file-csv"></i> Exportar CSV
            </a>
            
            <a href="exportar-logs?formato=pdf&nivel=<?= urlencode($f_nivel) ?>&modulo=<?= urlencode($f_modulo) ?>&fecha_inicio=<?= urlencode($f_fecha_init) ?>&fecha_fin=<?= urlencode($f_fecha_fin) ?>" target="_blank" class="btn btn-outline" style="font-size: 0.82rem; border-color: #dc2626; color: #dc2626; padding: 6px 12px; text-decoration: none;">
                <i class="ph-bold ph-file-pdf"></i> Imprimir / PDF
            </a>

            <form action="limpiar-logs" method="POST" style="margin:0;">
                <button type="submit" class="btn" style="font-size: 0.82rem; background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-weight: 600;" onclick="return confirm('¿Está seguro de vaciar los registros de auditoría activa?');">
                    <i class="ph-bold ph-trash"></i> Limpiar Logs
                </button>
            </form>
        </div>
    </div>

    <!-- FORMULARIO DE FILTROS INTERACTIVO -->
    <form action="visor-logs" method="GET" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1rem; margin-bottom: 1.25rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 0.75rem; align-items: end;">
        <div>
            <label style="font-size: 0.78rem; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Nivel / Severidad</label>
            <select name="nivel" class="login-flat-input" style="padding: 6px 10px; font-size: 0.85rem;">
                <option value="">-- Todos --</option>
                <option value="INFO" <?= $f_nivel === 'INFO' ? 'selected' : '' ?>>🔵 INFO</option>
                <option value="WARNING" <?= $f_nivel === 'WARNING' ? 'selected' : '' ?>>🟡 WARNING</option>
                <option value="ERROR" <?= $f_nivel === 'ERROR' ? 'selected' : '' ?>>🔴 ERROR</option>
                <option value="CRITICAL" <?= $f_nivel === 'CRITICAL' ? 'selected' : '' ?>>🚨 CRITICAL</option>
            </select>
        </div>

        <div>
            <label style="font-size: 0.78rem; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Módulo Originador</label>
            <input type="text" name="modulo" class="login-flat-input" placeholder="Ej: SuperAdmin..." value="<?= htmlspecialchars($f_modulo) ?>" style="padding: 6px 10px; font-size: 0.85rem;">
        </div>

        <div>
            <label style="font-size: 0.78rem; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Desde Fecha</label>
            <input type="date" name="fecha_inicio" class="login-flat-input" value="<?= htmlspecialchars($f_fecha_init) ?>" style="padding: 6px 10px; font-size: 0.85rem;">
        </div>

        <div>
            <label style="font-size: 0.78rem; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Hasta Fecha</label>
            <input type="date" name="fecha_fin" class="login-flat-input" value="<?= htmlspecialchars($f_fecha_fin) ?>" style="padding: 6px 10px; font-size: 0.85rem;">
        </div>

        <div>
            <button type="submit" class="btn btn-solid" style="width: 100%; padding: 7px 12px; font-size: 0.85rem; background: var(--color-secundario, #002244); color: white;">
                <i class="ph-bold ph-funnel"></i> Filtrar
            </button>
        </div>
    </form>

    <!-- TABLA AUDIT TRAIL -->
    <div class="table-responsive">
        <table class="log-table">
            <thead>
                <tr>
                    <th>Fecha y Hora</th>
                    <th>Nivel</th>
                    <th>Módulo</th>
                    <th>Acción</th>
                    <th>Detalles del Evento</th>
                    <th>Responsable</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($audit_trail)): ?>
                    <tr><td colspan="6" class="log-empty-msg">No hay registros de auditoría que coincidan con los filtros.</td></tr>
                <?php else: ?>
                    <?php foreach ($audit_trail as $item): 
                        $claseNivel = $item['nivel'] === 'INFO' ? 'badge-info' : 
                                     ($item['nivel'] === 'WARNING' ? 'badge-warning' : 'badge-danger');
                    ?>
                        <tr>
                            <td class="log-date-col">
                                <span class="log-date-primary"><?= date('d/m/Y', strtotime($item['fecha_hora'])) ?></span>
                                <span class="log-time-secondary"><?= date('H:i:s', strtotime($item['fecha_hora'])) ?></span>
                            </td>
                            <td>
                                <span class="log-action-badge <?= $claseNivel ?>"><?= htmlspecialchars($item['nivel']) ?></span>
                            </td>
                            <td class="log-table-name"><?= htmlspecialchars($item['modulo']) ?></td>
                            <td class="log-bold-text"><?= htmlspecialchars($item['accion']) ?></td>
                            <td class="log-muted-text"><?= htmlspecialchars($item['detalles']) ?></td>
                            <td>
                                <span class="log-responsable"><?= htmlspecialchars($item['responsable']) ?></span>
                                <div style="font-size: 0.72rem; color: #94a3b8;">IP: <?= htmlspecialchars($item['ip']) ?></div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- 2. PANTALLA: CONTROL DE ACCESOS -->
<div id="pantalla-auth" class="pantalla-log log-card-panel">
    <h3 class="log-panel-title">Últimos Accesos de Usuarios</h3>
    <div class="table-responsive">
        <table class="log-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Cédula</th>
                    <th>Última Actividad</th>
                    <th>Total Accesos</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs_auth)): ?>
                    <tr><td colspan="4" class="log-empty-msg">No hay registros de acceso.</td></tr>
                <?php else: ?>
                    <?php foreach ($logs_auth as $acceso): ?>
                        <tr>
                            <td class="log-bold-text"><?= htmlspecialchars($acceso['nombre_completo']) ?></td>
                            <td class="log-muted-text"><?= htmlspecialchars($acceso['cedula']) ?></td>
                            <td>
                                <span class="log-badge-success">
                                    <?= date('d/m/Y H:i', strtotime($acceso['ultima_actividad'])) ?>
                                </span>
                            </td>
                            <td><span class="log-highlight-text"><?= $acceso['conteo_accesos'] ?></span> veces</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- 3. PANTALLA: AUDITORÍA BASE DE DATOS -->
<div id="pantalla-db" class="pantalla-log log-card-panel">
    <h3 class="log-panel-title">Trazabilidad de Base de Datos</h3>
    <div class="table-responsive">
        <table class="log-table">
            <thead>
                <tr>
                    <th>Fecha y Hora</th>
                    <th>Operación</th>
                    <th>Tabla</th>
                    <th>Detalles (JSON)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs_db)): ?>
                    <tr><td colspan="4" class="log-empty-msg">No hay movimientos registrados.</td></tr>
                <?php else: ?>
                    <?php foreach ($logs_db as $log): 
                        $clase_accion = strtolower($log['accion']) === 'delete' ? 'badge-danger' : 
                                       (strtolower($log['accion']) === 'insert' ? 'badge-success' : 'badge-warning');
                    ?>
                        <tr>
                            <td class="log-date-col">
                                <span class="log-date-primary"><?= date('d/m/Y', strtotime($log['fecha_hora'])) ?></span>
                                <span class="log-time-secondary"><?= date('H:i:s', strtotime($log['fecha_hora'])) ?></span>
                            </td>
                            <td>
                                <span class="log-action-badge <?= $clase_accion ?>"><?= htmlspecialchars($log['accion']) ?></span>
                                <div class="log-responsable">Por: <?= htmlspecialchars($log['responsable'] ?? 'Sistema') ?></div>
                            </td>
                            <td class="log-table-name"><?= htmlspecialchars($log['tabla_afectada']) ?> #<?= $log['id_registro'] ?></td>
                            <td>
                                <div class="sql-trace-box log-trace-viewer">
                                    <?php if ($log['datos_anteriores']): ?>
                                        <div class="trace-old"><b>Anterior:</b> <?= htmlspecialchars($log['datos_anteriores']) ?></div>
                                    <?php endif; ?>
                                    <?php if ($log['datos_nuevos']): ?>
                                        <div class="trace-new"><b>Nuevo:</b> <?= htmlspecialchars($log['datos_nuevos']) ?></div>
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

<script>
    function cambiarPestana(idTab, botonPresionado) {
        document.querySelectorAll('.pantalla-log').forEach(panel => {
            panel.classList.remove('log-active-panel');
        });
        document.querySelectorAll('.filter-pill').forEach(boton => {
            boton.classList.remove('tab-active');
        });
        document.getElementById('pantalla-' + idTab).classList.add('log-active-panel');
        botonPresionado.classList.add('tab-active');
    }
</script>