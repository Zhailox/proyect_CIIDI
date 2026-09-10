<div class="sa-hero-header mb-2" style="background: #ffffff !important; border: 1px solid rgba(80, 89, 132, 0.18); padding: 1.5rem 2rem; border-radius: var(--radius-md); box-shadow: 0 4px 20px rgba(18, 26, 62, 0.04);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-terciario) !important; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 0.3rem;">
                <i class="ph-bold ph-wrench"></i> ADMINISTRACIÓN DE INFRAESTRUCTURA & BD
            </div>
            <h1 style="font-size: 1.8rem; font-weight: 800; color: #121a3e !important; margin: 0;">
                Mantenimiento del Sistema & Respaldos BD
            </h1>
            <p style="margin: 0.3rem 0 0 0; color: #64748b !important; font-size: 0.9rem;">
                Gestión de copias de seguridad PostgreSQL, dump de tablas específicas y control de ventanas de mantenimiento.
            </p>
        </div>
        <a href="sudoadmin" class="btn btn-outline" style="border-color: var(--color-secundario); color: var(--color-secundario); text-decoration: none; padding: 8px 14px; border-radius: 6px; font-weight: 600; font-size: 0.85rem;">
            <i class="ph-bold ph-arrow-left"></i> Volver al Centro de Mando
        </a>
    </div>
</div>

<!-- TOAST CONTAINER LOCAL -->
<div id="sa-toast-container" class="sa-toast-container"></div>

<!-- MENSAJES DE ALERTA DE SESIÓN -->
<?php if (isset($_SESSION['mensaje_admin_exito'])): ?>
    <div style="background: rgba(16,185,129,0.12); color: #047857; border: 1px solid rgba(16,185,129,0.3); padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <i class="ph-bold ph-check-circle" style="font-size: 1.2rem;"></i>
        <?= htmlspecialchars($_SESSION['mensaje_admin_exito']) ?>
        <?php unset($_SESSION['mensaje_admin_exito']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['mensaje_admin_error'])): ?>
    <div style="background: rgba(239,68,68,0.12); color: #b91c1c; border: 1px solid rgba(239,68,68,0.3); padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <i class="ph-bold ph-warning-circle" style="font-size: 1.2rem;"></i>
        <?= htmlspecialchars($_SESSION['mensaje_admin_error']) ?>
        <?php unset($_SESSION['mensaje_admin_error']); ?>
    </div>
<?php endif; ?>

<!-- CONTENEDOR PRINCIPAL CON 2 COLUMNAS LÓGICAS -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem;" class="mb-2">

    <!-- 1. GESTIÓN DE BASE DE DATOS Y RESPALDOS POSTGRESQL -->
    <div class="glass-panel" style="padding: 1.5rem; border-radius: var(--radius-sm);">
        <h3 class="admin-section-title" style="margin-top: 0; font-size: 1.05rem; display: flex; align-items: center; gap: 8px;">
            <i class="ph-bold ph-database" style="color: var(--color-terciario);"></i> Respaldos PostgreSQL & Exportación de Tablas
        </h3>
        <p style="font-size: 0.85rem; color: var(--texto-silenciado); margin-bottom: 1.25rem;">
            Genera copias completas en formato SQL, exporta solo la estructura de esquemas o descarga tablas individuales.
        </p>

        <!-- BOTONES DE EXPORTACIÓN RÁPIDA -->
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 1.25rem;">
            <a href="generar-backup" class="btn btn-secondary" style="font-size: 0.85rem; border-radius: 6px; padding: 8px 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="ph-bold ph-download-simple"></i> Dump Completo (.sql)
            </a>
            <a href="generar-backup-esquema" class="btn btn-secondary" style="font-size: 0.85rem; border-radius: 6px; padding: 8px 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="ph-bold ph-file-code"></i> Exportar Esquema (DDL)
            </a>
        </div>

        <!-- EXPORTAR TABLA ESPECÍFICA -->
        <form action="generar-backup-tabla" method="POST" style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem;">
            <select name="nombre_tabla" class="login-flat-input" required style="font-size: 0.85rem; padding: 8px 12px; border-radius: 6px; flex-grow: 1;">
                <option value="" disabled selected>Seleccionar tabla para exportar...</option>
                <?php foreach ($tablas as $t): ?>
                    <option value="<?= htmlspecialchars($t) ?>"><?= htmlspecialchars(ucfirst($t)) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-secondary" style="font-size: 0.85rem; border-radius: 6px; white-space: nowrap;">
                <i class="ph-bold ph-export"></i> Exportar
            </button>
        </form>

        <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--texto-titulos); margin: 0 0 0.75rem 0; display: flex; align-items: center; gap: 6px;">
            <i class="ph-bold ph-hard-drives"></i> Histórico de Respaldos Guardados (`storage/backups/`)
        </h4>

        <!-- TABLA RESUMEN DE RESPALDOS ALMACENADOS -->
        <?php
        $directorioRespaldos = __DIR__ . '/../../../storage/backups/';
        $archivosBackup = file_exists($directorioRespaldos) ? glob($directorioRespaldos . '*.sql') : [];
        ?>
        <div style="max-height: 220px; overflow-y: auto; border: 1px solid rgba(80,89,132,0.18); border-radius: 6px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.82rem; text-align: left;">
                <thead style="background: rgba(244,247,251,0.95); position: sticky; top: 0; border-bottom: 1px solid rgba(80,89,132,0.12);">
                    <tr>
                        <th style="padding: 8px 12px; color: var(--color-principal);">Archivo</th>
                        <th style="padding: 8px 12px; color: var(--color-principal);">Tamaño</th>
                        <th style="padding: 8px 12px; text-align: center; color: var(--color-principal);">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($archivosBackup)): ?>
                        <tr><td colspan="3" style="padding: 12px; text-align: center; color: #94a3b8;">No se han generado respaldos en `storage/backups/`.</td></tr>
                    <?php else: ?>
                        <?php foreach ($archivosBackup as $pathB): ?>
                            <?php $nB = basename($pathB); ?>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 8px 12px; font-weight: 600; color: var(--texto-titulos);"><?= htmlspecialchars($nB) ?></td>
                                <td style="padding: 8px 12px; color: #64748b;"><?= round(filesize($pathB) / 1024, 1) ?> KB</td>
                                <td style="padding: 8px 12px; text-align: center;">
                                    <div style="display: flex; gap: 6px; justify-content: center;">
                                        <a href="descargar-backup?archivo=<?= urlencode($nB) ?>" class="btn" style="background: rgba(112,144,203,0.15); color: var(--color-terciario); padding: 4px 8px; font-size: 0.78rem; border-radius: 4px; text-decoration: none;" title="Descargar copia">
                                            <i class="ph-bold ph-download-simple"></i>
                                        </a>
                                        <form action="eliminar-backup" method="POST" style="margin:0;">
                                            <input type="hidden" name="archivo" value="<?= htmlspecialchars($nB) ?>">
                                            <button type="submit" class="btn" style="background: rgba(239,68,68,0.12); color: #ef4444; padding: 4px 8px; font-size: 0.78rem; border-radius: 4px; border: none; cursor: pointer;" onclick="return confirm('¿Confirma eliminar este respaldo permanentemente?');" title="Eliminar respaldo">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. CONTROL DE MODO MANTENIMIENTO PROGRAMADO Y RESTAURACIÓN -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        
        <!-- CONTROL MANTENIMIENTO -->
        <div class="glass-panel" style="padding: 1.5rem; border-radius: var(--radius-sm);">
            <h3 class="admin-section-title" style="margin-top: 0; font-size: 1.05rem; display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-timer" style="color: var(--color-secundario);"></i> Modo Mantenimiento Programado
            </h3>

            <?php
            $archivo_mant = __DIR__ . '/../../../storage/maintenance.json';
            $dataMant = file_exists($archivo_mant) ? json_decode(file_get_contents($archivo_mant), true) : ['activo' => false];
            $mantenimientoActivo = $dataMant['activo'] ?? false;
            ?>

            <p style="font-size: 0.85rem; color: var(--texto-silenciado); margin-bottom: 1.25rem;">
                Aísla la plataforma desplegando una pantalla con temporizador en vivo para los usuarios. Solo los administradores conservan el acceso.
            </p>

            <form action="alternar-mantenimiento" method="POST" style="display: flex; flex-direction: column; gap: 0.85rem; margin: 0;">
                <?php if ($mantenimientoActivo == false): ?>
                    <div>
                        <label style="font-size: 0.78rem; font-weight: 700; color: var(--color-secundario); display: block; margin-bottom: 4px;">Mensaje Público Informativo</label>
                        <input type="text" name="mensaje" class="login-flat-input" placeholder="Ej: Realizando optimizaciones de base de datos..." style="padding: 0.75rem; font-size: 0.85rem; border-radius: 6px; width: 100%;">
                    </div>
                    <div>
                        <label style="font-size: 0.78rem; font-weight: 700; color: var(--color-secundario); display: block; margin-bottom: 4px;">Duración Estimada (Minutos)</label>
                        <input type="number" name="minutos_programados" min="0" class="login-flat-input" placeholder="Ej: 30" style="padding: 0.75rem; font-size: 0.85rem; border-radius: 6px; width: 100%;">
                    </div>
                <?php else: ?>
                    <?php if (!empty($dataMant['fecha_fin'])): ?>
                        <div style="background: rgba(245,158,11,0.15); color: #b45309; padding: 0.85rem; border-radius: 6px; font-size: 0.85rem; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                            <i class="ph-bold ph-clock" style="font-size: 1.2rem;"></i>
                            Mantenimiento activo hasta: <?= date('H:i - d/m/Y', strtotime($dataMant['fecha_fin'])) ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <button type="submit" class="btn <?= $mantenimientoActivo ? 'btn-secondary' : 'btn-danger' ?>" style="width: 100%; justify-content: center; padding: 0.85rem; font-size: 0.9rem; border-radius: 6px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                    <i class="<?= $mantenimientoActivo ? 'ph-bold ph-power' : 'ph-bold ph-warning-circle' ?>"></i>
                    <?= $mantenimientoActivo ? 'Restaurar Sistema (Abrir Plataforma)' : 'Activar Mantenimiento (Cerrar Plataforma)' ?>
                </button>
            </form>
        </div>

        <!-- RESTAURACIÓN MANUAL DE BASE DE DATOS -->
        <div class="glass-panel" style="padding: 1.5rem; border-radius: var(--radius-sm);">
            <h3 class="admin-section-title" style="margin-top: 0; font-size: 1.05rem; display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-upload-simple" style="color: var(--color-terciario);"></i> Restaurar Copia de Seguridad
            </h3>
            <p style="font-size: 0.85rem; color: var(--texto-silenciado); margin-bottom: 1rem;">
                Ejecuta un script `.sql` para restaurar el estado de la base de datos PostgreSQL.
            </p>
            <form action="restaurar-backup" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 0.75rem;">
                <input type="file" name="backup_file" accept=".sql" class="login-flat-input" style="padding: 6px; font-size: 0.82rem; border-radius: 6px;">
                <button type="submit" class="btn btn-outline" style="border-color: var(--color-terciario); color: var(--color-terciario); padding: 0.65rem; font-size: 0.85rem; border-radius: 6px; font-weight: 600;" onclick="return confirm('¿ADVERTENCIA: La restauración reemplazará datos existentes. Deseas continuar?');">
                    <i class="ph-bold ph-arrows-counter-clockwise"></i> Restaurar Script Seleccionado
                </button>
            </form>
        </div>

    </div>

</div>
