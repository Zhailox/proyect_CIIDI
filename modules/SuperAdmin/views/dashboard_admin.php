<div class="welcome-banner admin-banner gradient">
    <h1>Centro de Mando - Sudoadmin</h1>
    <p>Supervisión global de la arquitectura. Monitoreo de recursos, usuarios y mantenimiento del servidor.</p>
</div>

<?php if (isset($_SESSION['mensaje_admin_exito'])): ?>
    <div class="mensaje-exito">
        <i class="ph-bold ph-check-circle" style="font-size: 1.2rem;"></i>
        <?= htmlspecialchars($_SESSION['mensaje_admin_exito']) ?>
    </div>
    <?php unset($_SESSION['mensaje_admin_exito']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['mensaje_admin_error'])): ?>
    <div class="mensaje-error">
        <i class="ph-bold ph-warning-circle" style="font-size: 1.2rem;"></i>
        <?= htmlspecialchars($_SESSION['mensaje_admin_error']) ?>
    </div>
    <?php unset($_SESSION['mensaje_admin_error']); ?>
<?php endif; ?>

<h3 class="admin-section-title">Monitor de Usuarios</h3>
<div class="metric-grid-v2">
    <div class="metric-card-v2 success">
        <div>
            <h4>Usuarios Activos</h4>
            <div class="metric-value"><?= $stats['usuarios_activos'] ?> <span class="metric-sub"><?= $stats['usuarios_online'] ?> Online</span></div>
        </div>
    </div>
    <div class="metric-card-v2 warning">
        <div>
            <h4>Pendientes de Aprobación</h4>
            <div class="metric-value"><?= $stats['empresas_pendientes'] ?> <span class="metric-sub">Empresas</span></div>
        </div>
    </div>
    <div class="metric-card-v2 danger">
        <div>
            <h4>Cuentas Deshabilitadas</h4>
            <div class="metric-value"><?= $stats['usuarios_bloqueados'] ?> <span class="metric-sub">Bloqueados</span></div>
        </div>
    </div>
    <div class="metric-card-v2">
        <div>
            <h4>Tractores (Docentes)</h4>
            <div class="metric-value"><?= $stats['docentes'] ?> <span class="metric-sub">Registrados</span></div>
        </div>
    </div>
</div>

<h3 class="admin-section-title">Herramientas de Administración</h3>
<div class="actions-container">
    
    <details class="action-accordion">
        <summary>Exportar Base de Datos (PostgreSQL)</summary>
        <div class="action-content">
            <!-- Volcado Completo -->
            <a href="generar-backup" class="btn btn-secondary" style="text-decoration: none; display: block; margin-bottom: 0.5rem;">
                <i class="ph-bold ph-database"></i> Volcado Completo (Dump .sql)
            </a>
            
            <!-- Volcado de Solo Esquema -->
            <a href="generar-backup-esquema" class="btn btn-secondary" style="text-decoration: none; display: block; margin-bottom: 0.5rem;">
                <i class="ph-bold ph-file-code"></i> Exportar solo Esquema (Sin datos)
            </a>
            
            <!-- Volcado de Tabla Específica -->
            <form action="generar-backup-tabla" method="POST" style="display: flex; gap: 0.5rem; margin-top: 0.5rem; width: 100%; margin-bottom: 0;">
                <select name="nombre_tabla" class="login-flat-input" required style="margin-bottom: 0; flex-grow: 1; padding: 0.5rem; cursor: pointer;">
                    <option value="" disabled selected>Seleccione la tabla a exportar...</option>
                    <?php foreach ($tablas as $tabla): ?>
                        <option value="<?= htmlspecialchars($tabla) ?>">
                            <?= htmlspecialchars(ucfirst($tabla)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-secondary" style="white-space: nowrap;">
                    <i class="ph-bold ph-table"></i> Exportar Tabla
                </button>
            </form>
        </div>
    </details>

    <?php
    // Leemos el estado actual
    $archivo_mant = __DIR__ . '/../../../storage/maintenance.json';
    $dataMant = file_exists($archivo_mant) ? json_decode(file_get_contents($archivo_mant), true) : ['activo' => false];
    $mantenimientoActivo = $dataMant['activo'] ?? false;
    ?>
    <details class="action-accordion">
        <summary>Modo Mantenimiento & Programación de Tiempo</summary>
        <div class="action-content">
            <p style="width: 100%; font-size: 0.9rem; color: var(--texto-silenciado); margin-bottom: 1rem;">
                Muestra la pantalla de mantenimiento con temporizador opcional para la comunidad. Los administradores mantendrán acceso continuo.
            </p>
            
            <form action="alternar-mantenimiento" method="POST" style="display: flex; flex-direction: column; gap: 0.75rem; width: 100%; margin: 0;">
                <?php if ($mantenimientoActivo == false): ?>
                    <input type="text" name="mensaje" class="login-flat-input" placeholder="Mensaje para los usuarios (Opcional)..." style="padding: 0.6rem;">
                    
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="ph-bold ph-timer" style="font-size: 1.2rem; color: var(--color-secundario);"></i>
                        <input type="number" name="minutos_programados" min="0" class="login-flat-input" placeholder="Duración estimada en minutos (ej: 30)..." style="padding: 0.6rem; flex: 1;">
                    </div>
                <?php else: ?>
                    <?php if (!empty($dataMant['fecha_fin'])): ?>
                        <div style="background: #fef3c7; color: #92400e; padding: 0.75rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600;">
                            <i class="ph-bold ph-clock-afternoon"></i> Mantenimiento programado finaliza a las: <?= date('H:i - d/m/Y', strtotime($dataMant['fecha_fin'])) ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                
                <button type="submit" class="btn <?= $mantenimientoActivo ? 'btn-secondary' : 'btn-danger' ?>" style="width: 100%; justify-content: center;">
                    <i class="<?= $mantenimientoActivo ? 'ph-bold ph-power' : 'ph-bold ph-warning-circle' ?>"></i> 
                    <?= $mantenimientoActivo ? 'Restaurar Sistema (Abrir)' : 'Activar Mantenimiento (Cerrar)' ?>
                </button>
            </form>
        </div>
    </details>

    <details class="action-accordion">
        <summary style="color: #dc2626;"><i class="ph-bold ph-folder-simple-star"></i> Histórico de Respaldos & Restauración</summary>
        <div class="action-content" style="flex-direction: column;">
            <p style="width: 100%; font-size: 0.9rem; color: var(--texto-silenciado); margin-bottom: 1rem;">
                Administre los archivos <strong>.sql</strong> en `storage/backups/`. Puede descargarlos, eliminarlos o restaurarlos en 2 pasos.
            </p>
            
            <!-- LISTA DE ARCHIVOS DE RESPALDO EXISTENTES -->
            <?php
            $directorioRespaldos = __DIR__ . '/../../../storage/backups/';
            $archivosBackup = file_exists($directorioRespaldos) ? glob($directorioRespaldos . '*.sql') : [];
            ?>

            <?php if (!empty($archivosBackup)): ?>
                <div style="width: 100%; overflow-x: auto; margin-bottom: 1.5rem;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e2e8f0; background: #f8fafc;">
                                <th style="padding: 0.6rem 0.8rem; color: #475569;">Archivo SQL</th>
                                <th style="padding: 0.6rem 0.8rem; color: #475569;">Tamaño</th>
                                <th style="padding: 0.6rem 0.8rem; color: #475569;">Fecha Generación</th>
                                <th style="padding: 0.6rem 0.8rem; text-align: center; color: #475569;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($archivosBackup as $pathBackup): ?>
                                <?php 
                                $nombreBackup = basename($pathBackup);
                                $pesoKb = round(filesize($pathBackup) / 1024, 2);
                                $fechaBackup = date('d/m/Y H:i:s', filemtime($pathBackup));
                                ?>
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 0.6rem 0.8rem; font-weight: 600; color: #1e293b;">
                                        <i class="ph-bold ph-database" style="color: var(--color-secundario); margin-right: 4px;"></i>
                                        <?= htmlspecialchars($nombreBackup) ?>
                                    </td>
                                    <td style="padding: 0.6rem 0.8rem; color: #64748b;"><?= $pesoKb ?> KB</td>
                                    <td style="padding: 0.6rem 0.8rem; color: #64748b;"><?= $fechaBackup ?></td>
                                    <td style="padding: 0.6rem 0.8rem; text-align: center;">
                                        <div style="display: flex; gap: 0.3rem; justify-content: center;">
                                            <a href="descargar-backup?archivo=<?= urlencode($nombreBackup) ?>" class="btn" style="background: #e0f2fe; color: #0284c7; padding: 4px 8px; font-size: 0.78rem; text-decoration: none; border-radius: 6px;" title="Descargar Archivo">
                                                <i class="ph-bold ph-download-simple"></i>
                                            </a>

                                            <form action="restaurar-backup" method="POST" style="margin:0;">
                                                <input type="hidden" name="archivo_guardado" value="<?= htmlspecialchars($nombreBackup) ?>">
                                                <button type="submit" class="btn" style="background: #fef3c7; color: #d97706; border: 1px solid #fcd34d; padding: 4px 8px; font-size: 0.78rem; border-radius: 6px; cursor: pointer;" onclick="return confirm('Paso 1 de 2: ¿Desea restaurar este respaldo \'<?= htmlspecialchars($nombreBackup) ?>\'?') && confirm('Paso 2 de 2 (CONFIRMACIÓN DEFINITIVA): Esta acción sobreescribirá la Base de Datos actual. ¿Proceder?');" title="Restaurar este respaldo (2 Pasos)">
                                                    <i class="ph-bold ph-arrow-counter-clockwise"></i>
                                                </button>
                                            </form>

                                            <form action="eliminar-backup" method="POST" style="margin:0;">
                                                <input type="hidden" name="archivo" value="<?= htmlspecialchars($nombreBackup) ?>">
                                                <button type="submit" class="btn" style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; padding: 4px 8px; font-size: 0.78rem; border-radius: 6px; cursor: pointer;" onclick="return confirm('¿Eliminar el respaldo \'<?= htmlspecialchars($nombreBackup) ?>\'?');" title="Eliminar del Servidor">
                                                    <i class="ph-bold ph-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p style="font-size: 0.85rem; color: #94a3b8; margin-bottom: 1rem;">No hay archivos de respaldo guardados en `storage/backups/`.</p>
            <?php endif; ?>

            <!-- Cargar Respaldo Externo en SQL -->
            <form action="restaurar-backup" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 0.5rem; width: 100%; margin: 0; border-top: 1px solid #e2e8f0; padding-top: 1rem;">
                <label style="font-size: 0.85rem; font-weight: 600; color: #334155;">Subir y Restaurar un archivo SQL externo:</label>
                <input type="file" name="backup_file" accept=".sql" class="login-flat-input" style="padding: 0.6rem;" required>
                
                <button type="submit" class="btn btn-danger" style="width: 100%; justify-content: center;" onclick="return confirm('Paso 1 de 2: ¿Desea restaurar este respaldo externo?') && confirm('Paso 2 de 2: ¡PRECAUCIÓN! Esta acción reemplazará la Base de Datos actual. ¿Proceder?');">
                    <i class="ph-bold ph-upload-simple"></i> Subir y Restaurar BD
                </button>
            </form>
        </div>
    </details>

</div>