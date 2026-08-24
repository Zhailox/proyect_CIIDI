<div class="welcome-banner admin-banner gradient">
    <h1>Centro de Auditoría</h1>
    <p>Supervisión estructurada de eventos del sistema, trazabilidad de base de datos y control de accesos.</p>
</div>

<div class="inbox-filters mb-1" id="log-tabs-container">
    <button class="filter-pill tab-active cursor-pointer" onclick="cambiarPestana('none', this)">
        <svg class="svg-icon-log" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 9h-2V7h-2v5H6v2h2v5h2v-5h2v-2z"/></svg>
        Reposo
    </button>
    <button class="filter-pill cursor-pointer" onclick="cambiarPestana('auth', this)">
        <svg class="svg-icon-log" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
        Control de Accesos
    </button>
    <button class="filter-pill cursor-pointer" onclick="cambiarPestana('db', this)">
        <svg class="svg-icon-log" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
        Auditoría (Triggers DB)
    </button>
    <button class="filter-pill cursor-pointer" onclick="cambiarPestana('err', this)">
        <svg class="svg-icon-log" viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
        Errores del Sistema
    </button>
</div>

<div id="pantalla-none" class="pantalla-log log-active-panel log-monitor-reposo">
    <i class="ph-fill ph-shield-check icon-reposo"></i>
    <h3 class="title-reposo">Monitor en Reposo</h3>
    <p class="text-reposo">Seleccione una pestaña en la parte superior para cargar los registros.</p>
</div>

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

<div id="pantalla-err" class="pantalla-log log-card-panel">
    <h3 class="log-panel-title text-danger">Errores del Motor PHP</h3>
    <p class="log-muted-text">El log de errores físico está limpio o la ruta no está configurada.</p>
</div>

<script>
    function cambiarPestana(idTab, botonPresionado) {
        // Ocultar todos los paneles
        document.querySelectorAll('.pantalla-log').forEach(panel => {
            panel.classList.remove('log-active-panel');
        });

        // Quitar la clase "activa" de todos los botones
        document.querySelectorAll('.filter-pill').forEach(boton => {
            boton.classList.remove('tab-active');
        });

        // Mostrar el panel seleccionado y marcar el botón
        document.getElementById('pantalla-' + idTab).classList.add('log-active-panel');
        botonPresionado.classList.add('tab-active');
    }
</script>