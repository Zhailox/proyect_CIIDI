<style>
/* ==========================================================================
   Antigravity UI & Motion Design Expert Style Guide (estilo.md)
   ========================================================================== */
.ag-header-banner {
    background: rgba(255, 255, 255, 0.92) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(80, 89, 132, 0.16) !important;
    border-radius: var(--radius-md);
    padding: 1.6rem 2rem;
    box-shadow: 0 16px 36px rgba(18, 26, 62, 0.05);
    margin-bottom: 2rem;
}

.ag-glass-card {
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(80, 89, 132, 0.15) !important;
    border-radius: 16px;
    padding: 1.6rem;
    box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.ag-glass-card:hover {
    box-shadow: 0 18px 40px rgba(18, 26, 62, 0.07);
}

.ag-btn-flat {
    background: var(--color-secundario) !important;
    color: #ffffff !important;
    border: none !important;
    padding: 8px 16px !important;
    border-radius: 8px !important;
    font-weight: 700 !important;
    font-size: 0.85rem !important;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    transition: all 0.25s ease !important;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
}

.ag-btn-flat:hover {
    background: #1d4ed8 !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.32);
    color: #ffffff !important;
}

.ag-btn-danger-flat {
    background: #dc2626 !important;
    color: #ffffff !important;
    border: none !important;
    padding: 8px 16px !important;
    border-radius: 8px !important;
    font-weight: 700 !important;
    font-size: 0.85rem !important;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
    transition: all 0.25s ease !important;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
}

.ag-btn-danger-flat:hover {
    background: #b91c1c !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(220, 38, 38, 0.32);
    color: #ffffff !important;
}

.ag-btn-outline-flat {
    background: #ffffff !important;
    color: var(--texto-titulos, #0f172a) !important;
    border: 1px solid rgba(80, 89, 132, 0.25) !important;
    padding: 8px 16px !important;
    border-radius: 8px !important;
    font-weight: 700 !important;
    font-size: 0.85rem !important;
    transition: all 0.25s ease !important;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
}

.ag-btn-outline-flat:hover {
    border-color: var(--color-secundario) !important;
    color: var(--color-secundario) !important;
    background: rgba(37, 99, 235, 0.04) !important;
}

.ag-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
    text-align: left;
}

.ag-table th {
    padding: 10px 14px;
    color: var(--texto-titulos, #0f172a);
    font-weight: 800;
    font-size: 0.82rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background: rgba(241, 245, 249, 0.9);
    border-bottom: 1px solid rgba(80, 89, 132, 0.15);
}

.ag-table td {
    padding: 10px 14px;
    border-bottom: 1px solid rgba(80, 89, 132, 0.08);
    color: var(--texto-titulos, #0f172a);
}

.ag-input {
    padding: 8px 12px;
    font-size: 0.86rem;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: var(--texto-titulos, #0f172a);
    transition: border-color 0.2s ease;
}

.ag-input:focus {
    outline: none;
    border-color: var(--color-secundario);
}

/* MODAL DE CONFIRMACIÓN CUSTOM (ANTIGRAVITY DESIGN) */
.ag-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.25s ease;
}

.ag-modal-overlay.active {
    opacity: 1;
    pointer-events: auto;
}

.ag-modal-box {
    background: #ffffff;
    border: 1px solid rgba(80, 89, 132, 0.2);
    border-radius: 16px;
    width: 90%;
    max-width: 460px;
    padding: 1.8rem;
    box-shadow: 0 24px 60px rgba(15, 23, 42, 0.25);
    transform: scale(0.92);
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

.ag-modal-overlay.active .ag-modal-box {
    transform: scale(1);
}
</style>

<!-- ENCABEZADO PRINCIPAL GLASSMORPHIC -->
<div class="ag-header-banner">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.2rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-terciario); font-weight: 800; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 0.3rem;">
                <i class="ph-bold ph-wrench"></i> INFRAESTRUCTURA & BASE DE DATOS
            </div>
            <h1 style="font-size: 1.85rem; font-weight: 800; color: var(--texto-titulos, #0f172a); margin: 0;">
                Mantenimiento & Respaldos BD
            </h1>
            <p style="margin: 0.3rem 0 0 0; color: var(--texto-silenciado, #64748b); font-size: 0.9rem;">
                Gestión de copias de seguridad PostgreSQL, compresión GZIP, auto-limpieza (30 días) y control de ventanas de mantenimiento.
            </p>
        </div>
        <a href="sudoadmin" class="ag-btn-outline-flat">
            <i class="ph-bold ph-arrow-left"></i> Volver al Centro de Mando
        </a>
    </div>
</div>

<!-- TOAST CONTAINER LOCAL -->
<div id="sa-toast-container" class="sa-toast-container"></div>

<!-- MENSAJES DE ALERTA DE SESIÓN -->
<?php if (isset($_SESSION['mensaje_admin_exito'])): ?>
    <div style="background: rgba(16,185,129,0.1); color: #047857; border: 1px solid rgba(16,185,129,0.25); padding: 0.9rem 1.25rem; border-radius: 10px; margin-bottom: 1.5rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
        <i class="ph-bold ph-check-circle" style="font-size: 1.3rem; color: #10b981;"></i>
        <?= htmlspecialchars($_SESSION['mensaje_admin_exito']) ?>
        <?php unset($_SESSION['mensaje_admin_exito']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['mensaje_admin_error'])): ?>
    <div style="background: rgba(239,68,68,0.1); color: #b91c1c; border: 1px solid rgba(239,68,68,0.25); padding: 0.9rem 1.25rem; border-radius: 10px; margin-bottom: 1.5rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
        <i class="ph-bold ph-warning-circle" style="font-size: 1.3rem; color: #ef4444;"></i>
        <?= htmlspecialchars($_SESSION['mensaje_admin_error']) ?>
        <?php unset($_SESSION['mensaje_admin_error']); ?>
    </div>
<?php endif; ?>

<!-- CONTENEDOR PRINCIPAL EN GRID GLASSMORPHIC -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 1.5rem;" class="mb-2">

    <!-- COLUMNA 1: GESTIÓN DE BASE DE DATOS Y RESPALDOS POSTGRESQL -->
    <div class="ag-glass-card">
        <h3 style="margin: 0 0 0.4rem 0; font-size: 1.15rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: flex; align-items: center; gap: 8px;">
            <i class="ph-bold ph-database" style="color: var(--color-secundario);"></i> Respaldos PostgreSQL GZIP
        </h3>
        <p style="font-size: 0.86rem; color: var(--texto-silenciado, #64748b); margin-bottom: 1.4rem; line-height: 1.45;">
            Genera copias comprimidas `.sql.gz` optimizadas, exporta esquemas DDL o tablas individuales con auto-limpieza de retención a 30 días.
        </p>

        <!-- BOTONES DE EXPORTACIÓN RÁPIDA -->
        <div style="display: flex; gap: 0.8rem; flex-wrap: wrap; margin-bottom: 1.4rem;">
            <a href="generar-backup" class="ag-btn-flat">
                <i class="ph-bold ph-file-zip"></i> Dump Completo (.sql.gz)
            </a>
            <a href="generar-backup-esquema" class="ag-btn-outline-flat">
                <i class="ph-bold ph-file-code"></i> Exportar Esquema (DDL)
            </a>
        </div>

        <!-- EXPORTAR TABLA ESPECÍFICA -->
        <form action="generar-backup-tabla" method="POST" style="display: flex; gap: 0.6rem; margin-bottom: 1.6rem;">
            <select name="nombre_tabla" class="ag-input" required style="flex-grow: 1; font-weight: 600;">
                <option value="" disabled selected>Seleccionar tabla para exportar...</option>
                <?php foreach ($tablas as $t): ?>
                    <option value="<?= htmlspecialchars($t) ?>"><?= htmlspecialchars(ucfirst($t)) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="ag-btn-flat" style="white-space: nowrap;">
                <i class="ph-bold ph-export"></i> Exportar
            </button>
        </form>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem; flex-wrap: wrap; gap: 8px;">
            <h4 style="font-size: 0.92rem; font-weight: 800; color: var(--texto-titulos, #0f172a); margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-hard-drives" style="color: var(--color-secundario);"></i> Respaldos (`storage/backups/`)
            </h4>
            <a href="limpiar-respaldos-antiguos" class="ag-btn-outline-flat" style="padding: 4px 10px !important; font-size: 0.76rem !important;" title="Eliminar automáticamente respaldos de más de 30 días">
                <i class="ph-bold ph-broom"></i> Auto-Limpieza (30 días)
            </a>
        </div>

        <!-- TABLA RESUMEN DE RESPALDOS ALMACENADOS -->
        <?php
        $directorioRespaldos = defined('STORAGE_PATH') ? STORAGE_PATH . 'backups/' : __DIR__ . '/../../../storage/backups/';
        $archivosBackup = file_exists($directorioRespaldos) ? glob($directorioRespaldos . '*.{sql,sql.gz}', GLOB_BRACE) : [];
        if ($archivosBackup) {
            usort($archivosBackup, function($a, $b) {
                return filemtime($b) - filemtime($a);
            });
        }
        ?>
        <div style="max-height: 250px; overflow-y: auto; border: 1px solid rgba(80,89,132,0.16); border-radius: 10px; background: #ffffff;">
            <table class="ag-table">
                <thead>
                    <tr>
                        <th>Archivo</th>
                        <th>Tamaño</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($archivosBackup)): ?>
                        <tr><td colspan="3" style="padding: 14px; text-align: center; color: var(--texto-silenciado, #64748b);">No se han generado respaldos en `storage/backups/`.</td></tr>
                    <?php else: ?>
                        <?php foreach ($archivosBackup as $pathB): ?>
                            <?php 
                            $nB = basename($pathB); 
                            $isGz = str_ends_with($nB, '.gz');
                            ?>
                            <tr>
                                <td style="font-weight: 700; color: var(--texto-titulos, #0f172a);">
                                    <?= htmlspecialchars($nB) ?>
                                    <?php if($isGz): ?>
                                        <span style="background: rgba(16,185,129,0.12); color: #059669; border-radius: 4px; padding: 2px 6px; font-size: 0.7rem; font-weight: 800; margin-left: 4px;">GZIP</span>
                                    <?php endif; ?>
                                </td>
                                <td style="color: var(--texto-silenciado, #64748b); font-weight: 600;"><?= round(filesize($pathB) / 1024, 1) ?> KB</td>
                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 6px; justify-content: center;">
                                        <a href="verificar-respaldo?archivo=<?= urlencode($nB) ?>" class="ag-btn-outline-flat" style="padding: 5px 9px !important; font-size: 0.78rem !important;" title="Verificar Integridad (Dry-Run)">
                                            <i class="ph-bold ph-shield-check" style="color: #059669;"></i>
                                        </a>
                                        <a href="descargar-backup?archivo=<?= urlencode($nB) ?>" class="ag-btn-outline-flat" style="padding: 5px 9px !important; font-size: 0.78rem !important;" title="Descargar copia">
                                            <i class="ph-bold ph-download-simple"></i>
                                        </a>
                                        <form action="eliminar-backup" method="POST" style="margin:0;" class="form-eliminar-backup">
                                            <input type="hidden" name="archivo" value="<?= htmlspecialchars($nB) ?>">
                                            <button type="button" class="ag-btn-danger-flat" style="padding: 5px 9px !important; font-size: 0.78rem !important;" onclick="confirmarEliminacionBackup(this.form, '<?= htmlspecialchars($nB) ?>')" title="Eliminar respaldo">
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

    <!-- COLUMNA 2: CONTROL DE MODO MANTENIMIENTO Y RESTAURACIÓN -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        
        <!-- TARJETA: CONTROL MANTENIMIENTO -->
        <div class="ag-glass-card">
            <h3 style="margin: 0 0 0.4rem 0; font-size: 1.15rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-timer" style="color: var(--color-secundario);"></i> Modo Mantenimiento Programado
            </h3>

            <?php
            $archivo_mant = __DIR__ . '/../../../storage/maintenance.json';
            $dataMant = file_exists($archivo_mant) ? json_decode(file_get_contents($archivo_mant), true) : ['activo' => false];
            $mantenimientoActivo = $dataMant['activo'] ?? false;
            ?>

            <p style="font-size: 0.86rem; color: var(--texto-silenciado, #64748b); margin-bottom: 1.4rem; line-height: 1.45;">
                Aísla la plataforma desplegando una pantalla informativa para usuarios. Solo los administradores conservan el acceso.
            </p>

            <form action="alternar-mantenimiento" method="POST" style="display: flex; flex-direction: column; gap: 1rem; margin: 0;">
                <?php if ($mantenimientoActivo == false): ?>
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: block; margin-bottom: 6px;">Mensaje Público Informativo</label>
                        <input type="text" name="mensaje" class="ag-input" placeholder="Ej: Realizando optimizaciones de base de datos..." style="width: 100%;">
                    </div>
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: block; margin-bottom: 6px;">Duración Estimada (Minutos)</label>
                        <input type="number" name="minutos_programados" min="0" class="ag-input" placeholder="Ej: 30" style="width: 100%;">
                    </div>
                <?php else: ?>
                    <?php if (!empty($dataMant['fecha_fin'])): ?>
                        <div style="background: rgba(245,158,11,0.12); color: #b45309; border: 1px solid rgba(245,158,11,0.3); padding: 0.9rem; border-radius: 10px; font-size: 0.88rem; font-weight: 800; display: flex; align-items: center; gap: 10px;">
                            <i class="ph-bold ph-clock" style="font-size: 1.3rem;"></i>
                            Mantenimiento activo hasta: <?= date('H:i - d/m/Y', strtotime($dataMant['fecha_fin'])) ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <button type="submit" class="<?= $mantenimientoActivo ? 'ag-btn-flat' : 'ag-btn-danger-flat' ?>" style="width: 100%; justify-content: center; padding: 10px !important; font-size: 0.9rem !important;">
                    <i class="<?= $mantenimientoActivo ? 'ph-bold ph-power' : 'ph-bold ph-warning-circle' ?>"></i>
                    <?= $mantenimientoActivo ? 'Restaurar Sistema (Abrir Plataforma)' : 'Activar Mantenimiento (Cerrar Plataforma)' ?>
                </button>
            </form>
        </div>

        <!-- TARJETA: RESTAURACIÓN CON VERIFICACIÓN PREVIA (DRY-RUN) -->
        <div class="ag-glass-card">
            <h3 style="margin: 0 0 0.4rem 0; font-size: 1.15rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-upload-simple" style="color: var(--color-secundario);"></i> Restaurar Copia de Seguridad
            </h3>
            <p style="font-size: 0.86rem; color: var(--texto-silenciado, #64748b); margin-bottom: 1.2rem; line-height: 1.45;">
                Ejecuta un script `.sql` o `.sql.gz` verificando previamente su sintaxis e integridad antes de aplicar los cambios en PostgreSQL.
            </p>
            <form id="formRestaurarBackup" action="restaurar-backup" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 0.9rem;">
                <input type="file" id="inputBackupFile" name="backup_file" accept=".sql,.gz" class="ag-input" style="padding: 8px;">
                <button type="button" class="ag-btn-outline-flat" style="justify-content: center;" onclick="confirmarRestauracionBD()">
                    <i class="ph-bold ph-arrows-counter-clockwise"></i> Restaurar Script Seleccionado
                </button>
            </form>
        </div>

    </div>

</div>

<!-- MODAL DE CONFIRMACIÓN MODERNO (REEMPLAZO DE ALERTS/CONFIRMS JS NATIVOS) -->
<div id="ag-modal-overlay" class="ag-modal-overlay">
    <div class="ag-modal-box">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 1rem;">
            <div id="ag-modal-icon" style="width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;"></div>
            <div>
                <h3 id="ag-modal-title" style="margin: 0; font-size: 1.15rem; font-weight: 800; color: var(--texto-titulos, #0f172a);"></h3>
            </div>
        </div>

        <p id="ag-modal-body" style="font-size: 0.88rem; color: var(--texto-silenciado, #64748b); margin: 0 0 1.5rem 0; line-height: 1.5;"></p>

        <div style="display: flex; justify-content: flex-end; gap: 10px;">
            <button type="button" class="ag-btn-outline-flat" onclick="closeAGModal()">Cancelar</button>
            <button type="button" id="ag-modal-confirm-btn" class="ag-btn-flat">Confirmar Acción</button>
        </div>
    </div>
</div>

<script>
let modalActionCallback = null;

function openAGModal({ title, body, iconClass, iconBg, iconColor, confirmText, confirmClass, onConfirm }) {
    document.getElementById('ag-modal-title').textContent = title;
    document.getElementById('ag-modal-body').textContent = body;

    const iconEl = document.getElementById('ag-modal-icon');
    iconEl.className = '';
    iconEl.innerHTML = `<i class="${iconClass}"></i>`;
    iconEl.style.background = iconBg;
    iconEl.style.color = iconColor;

    const confirmBtn = document.getElementById('ag-modal-confirm-btn');
    confirmBtn.textContent = confirmText || 'Confirmar';
    confirmBtn.className = confirmClass || 'ag-btn-flat';

    modalActionCallback = onConfirm;

    document.getElementById('ag-modal-overlay').classList.add('active');
}

function closeAGModal() {
    document.getElementById('ag-modal-overlay').classList.remove('active');
    modalActionCallback = null;
}

document.getElementById('ag-modal-confirm-btn').addEventListener('click', function() {
    if (typeof modalActionCallback === 'function') {
        const cb = modalActionCallback;
        closeAGModal();
        cb();
    }
});

function confirmarEliminacionBackup(formEl, nombreArchivo) {
    openAGModal({
        title: '¿Eliminar copia de respaldo?',
        body: `Confirma que desea eliminar el archivo de respaldo '${nombreArchivo}'. Esta acción no se puede deshacer.`,
        iconClass: 'ph-bold ph-trash',
        iconBg: 'rgba(239,68,68,0.12)',
        iconColor: '#ef4444',
        confirmText: 'Sí, Eliminar',
        confirmClass: 'ag-btn-danger-flat',
        onConfirm: () => formEl.submit()
    });
}

function confirmarRestauracionBD() {
    const fileInput = document.getElementById('inputBackupFile');
    if (!fileInput.files || fileInput.files.length === 0) {
        openAGModal({
            title: 'Archivo no seleccionado',
            body: 'Por favor selecciona un archivo de respaldo .sql o .sql.gz antes de proceder con la restauración.',
            iconClass: 'ph-bold ph-warning-circle',
            iconBg: 'rgba(245,158,11,0.12)',
            iconColor: '#f59e0b',
            confirmText: 'Entendido',
            confirmClass: 'ag-btn-flat',
            onConfirm: () => {}
        });
        return;
    }

    openAGModal({
        title: 'ADVERTENCIA: Restaurar Base de Datos',
        body: `La restauración analizará primero la integridad del script '${fileInput.files[0].name}' y reemplazará los datos actuales en PostgreSQL. ¿Deseas continuar?`,
        iconClass: 'ph-bold ph-warning-circle',
        iconBg: 'rgba(239,68,68,0.12)',
        iconColor: '#ef4444',
        confirmText: 'Sí, Restaurar Base de Datos',
        confirmClass: 'ag-btn-danger-flat',
        onConfirm: () => document.getElementById('formRestaurarBackup').submit()
    });
}
</script>
