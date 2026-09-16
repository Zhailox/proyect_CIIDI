<style>
/* ==========================================================================
   Antigravity UI & Motion Design Expert Style Guide (estilo.md)
   ========================================================================== */
.ag-header-banner {
    background: rgba(255, 255, 255, 0.92) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(80, 89, 132, 0.16) !important;
    border-radius: var(--radius-md, 16px);
    padding: 1.6rem 2rem;
    box-shadow: 0 16px 36px rgba(18, 26, 62, 0.05);
    margin-bottom: 1.5rem;
}

.ag-tabs-nav {
    display: flex;
    gap: 0.6rem;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(12px);
    padding: 6px;
    border-radius: 14px;
    border: 1px solid rgba(80, 89, 132, 0.15);
    margin-bottom: 1.5rem;
}

.ag-tab-btn {
    flex: 1;
    padding: 10px 16px;
    border-radius: 10px;
    border: none;
    background: transparent;
    color: var(--texto-silenciado, #64748b);
    font-weight: 700;
    font-size: 0.88rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

.ag-tab-btn:hover {
    color: var(--color-secundario, #2563eb);
    background: rgba(37, 99, 235, 0.05);
}

.ag-tab-btn.active {
    background: #ffffff;
    color: var(--color-secundario, #2563eb);
    box-shadow: 0 4px 14px rgba(18, 26, 62, 0.08);
}

.ag-tab-pane {
    display: none;
    animation: fadeInSlide 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.ag-tab-pane.active {
    display: block;
}

@keyframes fadeInSlide {
    from {
        opacity: 0;
        transform: translateY(8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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

.ag-btn-flat {
    background: var(--color-secundario) !important;
    color: #ffffff !important;
    border: none !important;
    padding: 9px 18px !important;
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
    padding: 9px 18px !important;
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
    padding: 9px 18px !important;
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
    padding: 9px 14px;
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

.ag-pagination-btn {
    padding: 5px 12px;
    border-radius: 6px;
    border: 1px solid rgba(80,89,132,0.2);
    background: #ffffff;
    font-weight: 700;
    font-size: 0.8rem;
    color: var(--texto-titulos, #0f172a);
    cursor: pointer;
    transition: all 0.2s ease;
}

.ag-pagination-btn:hover:not(:disabled) {
    border-color: var(--color-secundario);
    color: var(--color-secundario);
    background: rgba(37,99,235,0.04);
}

.ag-pagination-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

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
    max-width: 480px;
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
            <h1 style="font-size: 1.85rem; font-weight: 800; color: var(--texto-titulos, #0f172a); margin: 0; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                Mantenimiento & Respaldos BD
                <?php
                $archivo_mant_hdr = __DIR__ . '/../../../storage/maintenance.json';
                $dataMantHdr = file_exists($archivo_mant_hdr) ? json_decode(file_get_contents($archivo_mant_hdr), true) : ['activo' => false];
                $isAct = $dataMantHdr['activo'] ?? false;
                $isProg = $dataMantHdr['programado'] ?? false;
                ?>
                <?php if ($isAct): ?>
                    <span style="background: rgba(239,68,68,0.12); color: #dc2626; border: 1px solid rgba(239,68,68,0.3); padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 800; display: inline-flex; align-items: center; gap: 5px;">
                        <i class="ph-bold ph-power"></i> Mantenimiento Activo
                    </span>
                <?php elseif ($isProg && !empty($dataMantHdr['fecha_inicio'])): ?>
                    <span id="ag-countdown-badge" style="background: rgba(245,158,11,0.12); color: #b45309; border: 1px solid rgba(245,158,11,0.3); padding: 4px 12px; border-radius: 20px; font-size: 0.76rem; font-weight: 800; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="ph-bold ph-timer"></i> Mantenimiento Programado: <span id="ag-countdown-clock" style="font-family: monospace; font-size: 0.85rem; font-weight: 800;"><?= date('H:i - d/m', strtotime($dataMantHdr['fecha_inicio'])) ?></span>
                    </span>
                    <script>
                    (function() {
                        const targetTime = new Date("<?= date('Y-m-d\TH:i:s', strtotime($dataMantHdr['fecha_inicio'])) ?>").getTime();
                        function updateClock() {
                            const now = new Date().getTime();
                            const diff = targetTime - now;
                            const clockEl = document.getElementById('ag-countdown-clock');
                            if (!clockEl) return;
                            
                            if (diff <= 0) {
                                clockEl.textContent = "¡En progreso / listo!";
                                clockEl.parentElement.style.background = "rgba(239,68,68,0.15)";
                                clockEl.parentElement.style.color = "#dc2626";
                                return;
                            }
                            const hrs = Math.floor(diff / (1000 * 60 * 60));
                            const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                            const secs = Math.floor((diff % (1000 * 60)) / 1000);
                            clockEl.textContent = 
                                `${hrs.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                        }
                        updateClock();
                        setInterval(updateClock, 1000);
                    })();
                    </script>
                <?php else: ?>
                    <span style="background: rgba(16,185,129,0.12); color: #059669; border: 1px solid rgba(16,185,129,0.3); padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 800; display: inline-flex; align-items: center; gap: 5px;">
                        <i class="ph-bold ph-check-circle"></i> Plataforma Operativa
                    </span>
                <?php endif; ?>
            </h1>
            <p style="margin: 0.3rem 0 0 0; color: var(--texto-silenciado, #64748b); font-size: 0.9rem;">
                Centro unificado de exportación/importación PostgreSQL, pre-inspección de restauración y control de mantenimiento.
            </p>
        </div>
        <a href="sudoadmin" class="ag-btn-outline-flat">
            <i class="ph-bold ph-arrow-left"></i> Volver al Centro de Mando
        </a>
    </div>
</div>

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

<!-- NAVEGACIÓN CORREDIZA POR PESTAÑAS (TABS UNIFICADOS) -->
<div class="ag-tabs-nav">
    <button type="button" class="ag-tab-btn active" onclick="switchTab('tab-gestion-bd', this)">
        <i class="ph-bold ph-database"></i> 1. Respaldos e Importación BD
    </button>
    <button type="button" class="ag-tab-btn" onclick="switchTab('tab-config-bd', this)">
        <i class="ph-bold ph-gear-six"></i> 2. Configuración BD & Break-Glass
    </button>
    <button type="button" class="ag-tab-btn" onclick="switchTab('tab-mantenimiento', this)">
        <i class="ph-bold ph-timer"></i> 3. Ventana de Mantenimiento
    </button>
</div>

<!-- ==========================================
     PESTAÑA 1: GESTIÓN UNIFICADA DE RESPALDOS E IMPORTACIÓN
     ========================================== -->
<div id="tab-gestion-bd" class="ag-tab-pane active">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 1.5rem;">
        
        <!-- COLUMNA IZQUIERDA: EXPORTACIÓN Y ARCHIVO DE IMPORTACIÓN EXTERNO -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            
            <!-- TARJETA: GENERACIÓN DE RESPALDOS MULTI-FORMATO -->
            <div class="ag-glass-card">
                <h3 style="margin: 0 0 0.4rem 0; font-size: 1.15rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: flex; align-items: center; gap: 8px;">
                    <i class="ph-bold ph-export" style="color: var(--color-secundario);"></i> Exportar Base de Datos / Tablas
                </h3>
                <p style="font-size: 0.86rem; color: var(--texto-silenciado, #64748b); margin-bottom: 1.4rem; line-height: 1.45;">
                    Genera volcados en formato comprimido `.sql.gz`, texto `.sql` o `.txt`.
                </p>

                <!-- FORMULARIO 1: RESPALDO COMPLETO -->
                <form action="generar-backup" method="POST" style="background: rgba(241,245,249,0.5); padding: 1rem; border-radius: 12px; border: 1px solid rgba(80,89,132,0.12); margin-bottom: 1rem;">
                    <label style="font-size: 0.82rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: block; margin-bottom: 8px;">
                        <i class="ph-bold ph-hard-drives"></i> Dump Completo (Estructura + Datos)
                    </label>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <select name="formato" class="ag-input" style="font-weight: 600; min-width: 130px;">
                            <option value="sql.gz">.sql.gz (GZIP)</option>
                            <option value="sql">.sql (Plano)</option>
                            <option value="txt">.txt (Texto)</option>
                        </select>
                        <button type="submit" class="ag-btn-flat" style="flex-grow: 1; justify-content: center;">
                            <i class="ph-bold ph-download"></i> Exportar BD
                        </button>
                    </div>
                </form>

                <!-- FORMULARIO 2: EXPORTAR SOLO ESQUEMA -->
                <form action="generar-backup-esquema" method="POST" style="background: rgba(241,245,249,0.5); padding: 1rem; border-radius: 12px; border: 1px solid rgba(80,89,132,0.12); margin-bottom: 1rem;">
                    <label style="font-size: 0.82rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: block; margin-bottom: 8px;">
                        <i class="ph-bold ph-code"></i> Solo Esquema DDL (Estructura)
                    </label>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <select name="formato" class="ag-input" style="font-weight: 600; min-width: 130px;">
                            <option value="sql">.sql (Plano)</option>
                            <option value="sql.gz">.sql.gz (GZIP)</option>
                            <option value="txt">.txt (Texto)</option>
                        </select>
                        <button type="submit" class="ag-btn-outline-flat" style="flex-grow: 1; justify-content: center;">
                            <i class="ph-bold ph-file-code"></i> Exportar Esquema
                        </button>
                    </div>
                </form>

                <!-- FORMULARIO 3: EXPORTAR TABLA ESPECÍFICA -->
                <form action="generar-backup-tabla" method="POST" style="background: rgba(241,245,249,0.5); padding: 1rem; border-radius: 12px; border: 1px solid rgba(80,89,132,0.12);">
                    <label style="font-size: 0.82rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: block; margin-bottom: 8px;">
                        <i class="ph-bold ph-table"></i> Exportar Tabla Específica
                    </label>
                    <div style="display: flex; gap: 8px; flex-direction: column;">
                        <select name="nombre_tabla" class="ag-input" required style="font-weight: 600; width: 100%;">
                            <option value="" disabled selected>Seleccionar tabla...</option>
                            <?php foreach ($tablas as $t): ?>
                                <option value="<?= htmlspecialchars($t) ?>"><?= htmlspecialchars(ucfirst($t)) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div style="display: flex; gap: 8px;">
                            <select name="formato" class="ag-input" style="font-weight: 600; min-width: 130px;">
                                <option value="sql.gz">.sql.gz (GZIP)</option>
                                <option value="sql">.sql (Plano)</option>
                                <option value="txt">.txt (Texto)</option>
                            </select>
                            <button type="submit" class="ag-btn-flat" style="flex-grow: 1; justify-content: center;">
                                <i class="ph-bold ph-export"></i> Exportar Tabla
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- TARJETA: OPTIMIZACIÓN Y SALUD DE LA BASE DE DATOS (VACUUM ANALYZE) -->
            <div class="ag-glass-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem; flex-wrap: wrap; gap: 8px;">
                    <div>
                        <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: flex; align-items: center; gap: 8px;">
                            <i class="ph-bold ph-lightning" style="color: #f59e0b;"></i> Optimización PostgreSQL (VACUUM)
                        </h3>
                        <p style="margin: 2px 0 0 0; font-size: 0.8rem; color: var(--texto-silenciado, #64748b);">Reclama espacio muerto y actualiza estadísticas de consulta</p>
                    </div>
                    <form action="optimizar-bd" method="POST" style="margin:0;">
                        <button type="submit" class="ag-btn-flat" style="background: #059669 !important; padding: 7px 14px !important; font-size: 0.82rem !important;" title="Ejecutar VACUUM ANALYZE">
                            <i class="ph-bold ph-sparkle"></i> Optimizar BD Ahora
                        </button>
                    </form>
                </div>

                <?php if (!empty($metricas_tablas)): ?>
                    <div style="border: 1px solid rgba(80,89,132,0.14); border-radius: 10px; overflow: hidden; background: #ffffff;">
                        <table class="ag-table">
                            <thead>
                                <tr>
                                    <th>Tabla PostgreSQL</th>
                                    <th>Filas Est.</th>
                                    <th style="text-align: right;">Espacio Usado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($metricas_tablas, 0, 5) as $mt): ?>
                                    <tr>
                                        <td style="font-weight: 700; color: var(--texto-titulos, #0f172a);"><?= htmlspecialchars($mt['tabla']) ?></td>
                                        <td style="color: var(--texto-silenciado, #64748b); font-weight: 600;"><?= number_format((int)$mt['total_filas']) ?></td>
                                        <td style="text-align: right; font-weight: 800; color: var(--color-secundario);"><?= htmlspecialchars($mt['tamano']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- TARJETA: UPLOAD DE ARCHIVO DE RESPALDO EXTERNO -->
            <div class="ag-glass-card">
                <h3 style="margin: 0 0 0.4rem 0; font-size: 1.15rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: flex; align-items: center; gap: 8px;">
                    <i class="ph-bold ph-upload-simple" style="color: var(--color-secundario);"></i> Importar Script Externe (.sql, .gz, .txt)
                </h3>
                <p style="font-size: 0.86rem; color: var(--texto-silenciado, #64748b); margin-bottom: 1.2rem; line-height: 1.45;">
                    Carga un archivo externo. Se creará un <strong>Auto-Checkpoint de Seguridad</strong> antes de restaurar en PostgreSQL.
                </p>

                <form id="formRestaurarBackup" action="restaurar-backup" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1rem;">
                    <input type="file" id="inputBackupFile" name="backup_file" accept=".sql,.gz,.txt" class="ag-input" style="padding: 8px;">
                    <button type="button" class="ag-btn-flat" style="justify-content: center; padding: 10px !important;" onclick="confirmarRestauracionBD()">
                        <i class="ph-bold ph-arrows-counter-clockwise"></i> Pre-Inspeccionar e Importar
                    </button>
                </form>
            </div>

        </div>

        <!-- COLUMNA DERECHA: ALMACENAMIENTO DE RESPALDOS CON BOTÓN RESTAURAR DIRECTO Y PAGINADOR -->
        <div class="ag-glass-card" style="display: flex; flex-direction: column;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem; flex-wrap: wrap; gap: 8px;">
                <div>
                    <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: flex; align-items: center; gap: 8px;">
                        <i class="ph-bold ph-folder-open" style="color: var(--color-secundario);"></i> Almacenamiento Local (`storage/backups/`)
                    </h3>
                    <p style="margin: 2px 0 0 0; font-size: 0.8rem; color: var(--texto-silenciado, #64748b);">Restaura directamente desde cualquier copia guardada</p>
                </div>
                <a href="limpiar-respaldos-antiguos" class="ag-btn-outline-flat" style="padding: 5px 12px !important; font-size: 0.78rem !important;" title="Auto-limpieza de más de 30 días">
                    <i class="ph-bold ph-broom"></i> Limpiar (30 días)
                </a>
            </div>

            <?php
            $directorioRespaldos = defined('STORAGE_PATH') ? STORAGE_PATH . 'backups/' : __DIR__ . '/../../../storage/backups/';
            $archivosBackup = file_exists($directorioRespaldos) ? glob($directorioRespaldos . '*.{sql,sql.gz,txt}', GLOB_BRACE) : [];
            if ($archivosBackup) {
                usort($archivosBackup, function($a, $b) {
                    return filemtime($b) - filemtime($a);
                });
            }
            $totalRespaldos = count($archivosBackup);
            ?>

            <div style="flex-grow: 1; border: 1px solid rgba(80,89,132,0.16); border-radius: 10px; background: #ffffff; overflow: hidden; display: flex; flex-direction: column;">
                <div style="overflow-x: auto; flex-grow: 1;">
                    <table class="ag-table">
                        <thead>
                            <tr>
                                <th>Archivo / Formato</th>
                                <th>Tamaño</th>
                                <th style="text-align: center;">Acciones Directas</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-respaldos-body">
                            <?php if (empty($archivosBackup)): ?>
                                <tr><td colspan="3" style="padding: 20px; text-align: center; color: var(--texto-silenciado, #64748b);">No hay respaldos guardados en el almacenamiento.</td></tr>
                            <?php else: ?>
                                <?php foreach ($archivosBackup as $index => $pathB): ?>
                                    <?php 
                                    $nB = basename($pathB); 
                                    $isGz = str_ends_with($nB, '.gz');
                                    $isTxt = str_ends_with($nB, '.txt');
                                    ?>
                                    <tr class="fila-respaldo" data-index="<?= $index ?>" style="display: none;">
                                        <td style="font-weight: 700; color: var(--texto-titulos, #0f172a);">
                                            <?= htmlspecialchars($nB) ?>
                                            <?php if($isGz): ?>
                                                <span style="background: rgba(16,185,129,0.12); color: #059669; border-radius: 4px; padding: 2px 6px; font-size: 0.7rem; font-weight: 800; margin-left: 4px;">GZIP</span>
                                            <?php elseif($isTxt): ?>
                                                <span style="background: rgba(59,130,246,0.12); color: #2563eb; border-radius: 4px; padding: 2px 6px; font-size: 0.7rem; font-weight: 800; margin-left: 4px;">TXT</span>
                                            <?php else: ?>
                                                <span style="background: rgba(107,114,128,0.12); color: #4b5563; border-radius: 4px; padding: 2px 6px; font-size: 0.7rem; font-weight: 800; margin-left: 4px;">SQL</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="color: var(--texto-silenciado, #64748b); font-weight: 600;"><?= round(filesize($pathB) / 1024, 1) ?> KB</td>
                                        <td style="text-align: center;">
                                            <div style="display: flex; gap: 6px; justify-content: center; align-items: center;">
                                                <!-- BOTÓN RESTAURAR DIRECTO -->
                                                <form action="restaurar-backup" method="POST" style="margin:0;">
                                                    <input type="hidden" name="archivo_guardado" value="<?= htmlspecialchars($nB) ?>">
                                                    <button type="button" class="ag-btn-flat" style="padding: 5px 10px !important; font-size: 0.78rem !important; background: #2563eb !important;" onclick="confirmarRestauracionDirecta(this.form, '<?= htmlspecialchars($nB) ?>')" title="Restaurar este respaldo">
                                                        <i class="ph-bold ph-arrows-counter-clockwise"></i> Restaurar
                                                    </button>
                                                </form>

                                                <a href="verificar-respaldo?archivo=<?= urlencode($nB) ?>" class="ag-btn-outline-flat" style="padding: 5px 9px !important; font-size: 0.78rem !important;" title="Verificar Integridad">
                                                    <i class="ph-bold ph-shield-check" style="color: #059669;"></i>
                                                </a>
                                                <a href="descargar-backup?archivo=<?= urlencode($nB) ?>" class="ag-btn-outline-flat" style="padding: 5px 9px !important; font-size: 0.78rem !important;" title="Descargar">
                                                    <i class="ph-bold ph-download-simple"></i>
                                                </a>
                                                <form action="eliminar-backup" method="POST" style="margin:0;">
                                                    <input type="hidden" name="archivo" value="<?= htmlspecialchars($nB) ?>">
                                                    <button type="button" class="ag-btn-danger-flat" style="padding: 5px 9px !important; font-size: 0.78rem !important;" onclick="confirmarEliminacionBackup(this.form, '<?= htmlspecialchars($nB) ?>')" title="Eliminar">
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

                <!-- CONTROLES DE PAGINACIÓN -->
                <?php if ($totalRespaldos > 0): ?>
                    <div style="padding: 10px 14px; background: rgba(241,245,249,0.7); border-top: 1px solid rgba(80,89,132,0.12); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
                        <span id="paginacion-info" style="font-size: 0.8rem; font-weight: 700; color: var(--texto-silenciado, #64748b);"></span>
                        <div style="display: flex; gap: 6px;">
                            <button type="button" id="btn-prev-page" class="ag-pagination-btn" onclick="cambiarPaginaRespaldos(-1)">
                                <i class="ph-bold ph-caret-left"></i> Anterior
                            </button>
                            <button type="button" id="btn-next-page" class="ag-pagination-btn" onclick="cambiarPaginaRespaldos(1)">
                                Siguiente <i class="ph-bold ph-caret-right"></i>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<!-- ==========================================
     PESTAÑA 2: CONFIGURACIÓN DE BASE DE DATOS Y CUENTA DE EMERGENCIA
     ========================================== -->
<div id="tab-config-bd" class="ag-tab-pane">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 1.5rem;">
        
        <!-- TARJETA: MODIFICAR PARÁMETROS DE CONEXIÓN BD -->
        <div class="ag-glass-card">
            <h3 style="margin: 0 0 0.4rem 0; font-size: 1.15rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-database" style="color: var(--color-secundario);"></i> Conexión PostgreSQL Dinámica
            </h3>
            <p style="font-size: 0.86rem; color: var(--texto-silenciado, #64748b); margin-bottom: 1.4rem; line-height: 1.45;">
                Actualiza el Host, Puerto, Base de Datos, Usuario y Contraseña de PostgreSQL. Requiere confirmar la contraseña de tu cuenta actual de SuperAdmin.
            </p>

            <form action="guardar-configuracion-bd" method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 10px;">
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: block; margin-bottom: 6px;">Host del Servidor</label>
                        <input type="text" name="host" class="ag-input" value="<?= htmlspecialchars($db_creds['host'] ?? 'localhost') ?>" required style="width: 100%;">
                    </div>
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: block; margin-bottom: 6px;">Puerto</label>
                        <input type="text" name="port" class="ag-input" value="<?= htmlspecialchars($db_creds['port'] ?? '5432') ?>" required style="width: 100%;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: block; margin-bottom: 6px;">Base de Datos</label>
                        <input type="text" name="db" class="ag-input" value="<?= htmlspecialchars($db_creds['db'] ?? 'ciidi') ?>" required style="width: 100%;">
                    </div>
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: block; margin-bottom: 6px;">Usuario PostgreSQL</label>
                        <input type="text" name="user" class="ag-input" value="<?= htmlspecialchars($db_creds['user'] ?? 'miki') ?>" required style="width: 100%;">
                    </div>
                </div>

                <div>
                    <label style="font-size: 0.8rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: block; margin-bottom: 6px;">Contraseña PostgreSQL</label>
                    <input type="password" name="pass" class="ag-input" value="<?= htmlspecialchars($db_creds['pass'] ?? '') ?>" placeholder="Contraseña de la BD" style="width: 100%;">
                </div>

                <hr style="border: 0; border-top: 1px solid rgba(80,89,132,0.15); margin: 0.5rem 0;">

                <div style="background: rgba(37,99,235,0.05); border: 1px solid rgba(37,99,235,0.2); padding: 1rem; border-radius: 10px;">
                    <label style="font-size: 0.82rem; font-weight: 800; color: var(--color-secundario); display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                        <i class="ph-bold ph-shield-check"></i> Confirmar con Tu Contraseña de SuperAdmin (Re-Autenticación)
                    </label>
                    <input type="password" name="admin_confirm_password" class="ag-input" placeholder="Ingresa tu contraseña actual de usuario" required style="width: 100%; border-color: rgba(37,99,235,0.4);">
                </div>

                <button type="submit" class="ag-btn-flat" style="justify-content: center; padding: 11px !important;">
                    <i class="ph-bold ph-floppy-disk"></i> Guardar Cambios de Conexión BD
                </button>
            </form>
        </div>

        <!-- TARJETA: CUENTA DE EMERGENCIA LOCAL (BREAK-GLASS ACCOUNT) -->
        <div class="ag-glass-card">
            <h3 style="margin: 0 0 0.4rem 0; font-size: 1.15rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-user-gear" style="color: #dc2626;"></i> Cuenta de Emergencia Local (Break-Glass)
            </h3>
            <p style="font-size: 0.86rem; color: var(--texto-silenciado, #64748b); margin-bottom: 1.4rem; line-height: 1.45;">
                Configura una cuenta de súper acceso independiente almacenada localmente en JSON. Se usará como salvavidas si la BD PostgreSQL sufre una caída total.
            </p>

            <?php if (!empty($emergency_data['usuario'])): ?>
                <div style="background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.25); padding: 0.85rem 1.1rem; border-radius: 10px; margin-bottom: 1.2rem; font-size: 0.84rem; color: #047857;">
                    <div style="font-weight: 800; display: flex; align-items: center; gap: 6px;">
                        <i class="ph-bold ph-check-circle"></i> Cuenta de Emergencia Configurada
                    </div>
                    <div style="margin-top: 4px;"><strong>Usuario Local:</strong> <?= htmlspecialchars($emergency_data['usuario']) ?></div>
                    <?php if (!empty($emergency_data['actualizado_el'])): ?>
                        <div style="font-size: 0.76rem; opacity: 0.85;">Última actualización: <?= htmlspecialchars($emergency_data['actualizado_el']) ?> por <?= htmlspecialchars($emergency_data['actualizado_por'] ?? 'SuperAdmin') ?></div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div style="background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.25); padding: 0.85rem 1.1rem; border-radius: 10px; margin-bottom: 1.2rem; font-size: 0.84rem; color: #b45309;">
                    <i class="ph-bold ph-warning"></i> No se ha configurado una cuenta de emergencia local todavía.
                </div>
            <?php endif; ?>

            <form action="guardar-cuenta-emergencia" method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
                <div>
                    <label style="font-size: 0.8rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: block; margin-bottom: 6px;">Usuario de Emergencia</label>
                    <input type="text" name="emergency_user" class="ag-input" value="<?= htmlspecialchars($emergency_data['usuario'] ?? 'admin_emergencia') ?>" required style="width: 100%;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: block; margin-bottom: 6px;">Contraseña de Emergencia</label>
                        <input type="password" name="emergency_pass" class="ag-input" placeholder="Nueva Contraseña" required style="width: 100%;">
                    </div>
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: block; margin-bottom: 6px;">Confirmar Contraseña</label>
                        <input type="password" name="emergency_pass_confirm" class="ag-input" placeholder="Repite Contraseña" required style="width: 100%;">
                    </div>
                </div>

                <hr style="border: 0; border-top: 1px solid rgba(80,89,132,0.15); margin: 0.5rem 0;">

                <div style="background: rgba(220,38,38,0.05); border: 1px solid rgba(220,38,38,0.2); padding: 1rem; border-radius: 10px;">
                    <label style="font-size: 0.82rem; font-weight: 800; color: #dc2626; display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                        <i class="ph-bold ph-shield-check"></i> Confirmar con Tu Contraseña de SuperAdmin
                    </label>
                    <input type="password" name="admin_confirm_password" class="ag-input" placeholder="Ingresa tu contraseña actual de usuario" required style="width: 100%; border-color: rgba(220,38,38,0.4);">
                </div>

                <button type="submit" class="ag-btn-danger-flat" style="justify-content: center; padding: 11px !important;">
                    <i class="ph-bold ph-key"></i> Configurar Cuenta Break-Glass
                </button>
            </form>
        </div>

    </div>
</div>

<!-- ==========================================
     PESTAÑA 3: VENTANA DE MANTENIMIENTO PROGRAMADO
     ========================================== -->
<div id="tab-mantenimiento" class="ag-tab-pane">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
        
        <!-- TARJETA 1: ACTIVACIÓN / DESACTIVACIÓN INMEDIATA -->
        <div class="ag-glass-card">
            <h3 style="margin: 0 0 0.4rem 0; font-size: 1.15rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-power" style="color: var(--color-secundario);"></i> Control de Aislamiento Inmediato
            </h3>
            <p style="font-size: 0.86rem; color: var(--texto-silenciado, #64748b); margin-bottom: 1.4rem; line-height: 1.45;">
                Fuerza la activación o apertura del sistema en tiempo real.
            </p>

            <?php
            $archivo_mant = defined('STORAGE_PATH') ? STORAGE_PATH . 'maintenance.json' : __DIR__ . '/../../../storage/maintenance.json';
            $dataMant = file_exists($archivo_mant) ? json_decode(file_get_contents($archivo_mant), true) : ['activo' => false];
            $mantenimientoActivo = $dataMant['activo'] ?? false;
            $agendaMantenimientos = $dataMant['agenda'] ?? [];
            ?>

            <form action="alternar-mantenimiento" method="POST" style="display: flex; flex-direction: column; gap: 1rem; margin: 0;">
                <?php if ($mantenimientoActivo == false): ?>
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: block; margin-bottom: 6px;">Mensaje Público Informativo</label>
                        <input type="text" name="mensaje" class="ag-input" placeholder="Ej: Realizando actualizaciones de infraestructura..." style="width: 100%;">
                    </div>
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: block; margin-bottom: 6px;">Duración Estimada (Minutos)</label>
                        <input type="number" name="minutos_programados" min="0" class="ag-input" placeholder="Ej: 30" style="width: 100%;">
                    </div>
                <?php else: ?>
                    <div style="background: rgba(239,68,68,0.08); color: #dc2626; border: 1px solid rgba(239,68,68,0.25); padding: 0.9rem; border-radius: 10px; font-size: 0.88rem; font-weight: 800; display: flex; align-items: center; gap: 10px;">
                        <i class="ph-bold ph-warning-circle" style="font-size: 1.3rem;"></i>
                        <div>
                            <div>Mantenimiento Actualmente ACTIVADO</div>
                            <?php if (!empty($dataMant['fecha_fin'])): ?>
                                <div style="font-size: 0.78rem; font-weight: 600; color: var(--texto-silenciado);">Expira a las: <?= date('H:i - d/m/Y', strtotime($dataMant['fecha_fin'])) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <button type="submit" class="<?= $mantenimientoActivo ? 'ag-btn-flat' : 'ag-btn-danger-flat' ?>" style="width: 100%; justify-content: center; padding: 11px !important; font-size: 0.9rem !important;">
                    <i class="<?= $mantenimientoActivo ? 'ph-bold ph-power' : 'ph-bold ph-warning-circle' ?>"></i>
                    <?= $mantenimientoActivo ? 'Desactivar Mantenimiento (Abrir Sistema)' : 'Activar Mantenimiento Ahora' ?>
                </button>
            </form>
        </div>

        <!-- TARJETA 2: PROGRAMACIÓN FUTURA DE MANTENIMIENTO -->
        <div class="ag-glass-card">
            <h3 style="margin: 0 0 0.4rem 0; font-size: 1.15rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-calendar-plus" style="color: var(--color-secundario);"></i> Agendar Ventana de Mantenimiento
            </h3>
            <p style="font-size: 0.86rem; color: var(--texto-silenciado, #64748b); margin-bottom: 1.4rem; line-height: 1.45;">
                Planifica ventanas de mantenimiento futuras con fecha y duración.
            </p>

            <form action="programar-mantenimiento" method="POST" style="display: flex; flex-direction: column; gap: 1rem; margin: 0;">
                <div>
                    <label style="font-size: 0.8rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: block; margin-bottom: 6px;">Fecha y Hora de Inicio</label>
                    <input type="datetime-local" name="fecha_inicio" class="ag-input" required style="width: 100%; font-weight: 600;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: block; margin-bottom: 6px;">Duración (Min)</label>
                        <input type="number" name="duracion_minutos" min="1" value="30" class="ag-input" required style="width: 100%;">
                    </div>
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: block; margin-bottom: 6px;">Mensaje</label>
                        <input type="text" name="mensaje" class="ag-input" placeholder="Ej: Actualización DDL" style="width: 100%;">
                    </div>
                </div>

                <button type="submit" class="ag-btn-flat" style="width: 100%; justify-content: center; padding: 11px !important;">
                    <i class="ph-bold ph-calendar-check"></i> Agendar Mantenimiento
                </button>
            </form>
        </div>

    </div>

    <!-- LISTA DE MANTENIMIENTOS PROGRAMADOS -->
    <div class="ag-glass-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 8px;">
            <div>
                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: flex; align-items: center; gap: 8px;">
                    <i class="ph-bold ph-list-checks" style="color: var(--color-secundario);"></i> Agenda de Mantenimientos Programados
                </h3>
                <p style="margin: 2px 0 0 0; font-size: 0.8rem; color: var(--texto-silenciado, #64748b);">Planificaciones pendientes de ejecución automática</p>
            </div>
            <?php if (!empty($agendaMantenimientos) || !empty($dataMant['fecha_inicio'])): ?>
                <form action="cancelar-mantenimiento" method="POST" style="margin:0;">
                    <button type="submit" class="ag-btn-outline-flat" style="color: #dc2626 !important; border-color: rgba(220,38,38,0.3) !important; padding: 5px 12px !important; font-size: 0.78rem !important;">
                        <i class="ph-bold ph-x-circle"></i> Cancelar Toda la Agenda
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <div style="border: 1px solid rgba(80,89,132,0.16); border-radius: 10px; background: #ffffff; overflow-x: auto;">
            <table class="ag-table">
                <thead>
                    <tr>
                        <th>Fecha & Hora Inicio</th>
                        <th>Duración</th>
                        <th>Mensaje Informativo</th>
                        <th>Estado</th>
                        <th style="text-align: center;">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($agendaMantenimientos) && empty($dataMant['fecha_inicio'])): ?>
                        <tr><td colspan="5" style="padding: 18px; text-align: center; color: var(--texto-silenciado, #64748b);">No hay ventanas de mantenimiento programadas en la agenda.</td></tr>
                    <?php else: ?>
                        <?php 
                        $listaMostrar = !empty($agendaMantenimientos) ? $agendaMantenimientos : [[
                            'id' => 'actual',
                            'fecha_inicio' => $dataMant['fecha_inicio'] ?? date('Y-m-d H:i:s'),
                            'duracion_minutos' => $dataMant['minutos'] ?? ($dataMant['duracion_minutos'] ?? 30),
                            'mensaje' => $dataMant['mensaje'] ?? 'Mantenimiento de infraestructura',
                            'activo' => $dataMant['activo'] ?? false
                        ]];
                        ?>
                        <?php foreach ($listaMostrar as $item): ?>
                            <tr>
                                <td style="font-weight: 700; color: var(--texto-titulos, #0f172a);">
                                    <i class="ph-bold ph-calendar" style="color: var(--color-secundario); margin-right: 4px;"></i>
                                    <?= date('d/m/Y - H:i', strtotime($item['fecha_inicio'])) ?>
                                </td>
                                <td style="font-weight: 600; color: var(--texto-silenciado);"><?= htmlspecialchars($item['duracion_minutos'] ?? 30) ?> min</td>
                                <td style="color: var(--texto-silenciado);"><?= htmlspecialchars($item['mensaje'] ?: 'Mantenimiento programado') ?></td>
                                <td>
                                    <?php if (!empty($item['activo'])): ?>
                                        <span style="background: rgba(220,38,38,0.1); color: #dc2626; border-radius: 4px; padding: 2px 8px; font-size: 0.72rem; font-weight: 800;">ACTIVO</span>
                                    <?php else: ?>
                                        <span style="background: rgba(37,99,235,0.1); color: #2563eb; border-radius: 4px; padding: 2px 8px; font-size: 0.72rem; font-weight: 800;">PROGRAMADO</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <form action="cancelar-mantenimiento" method="POST" style="margin:0;">
                                        <input type="hidden" name="id_agenda" value="<?= htmlspecialchars($item['id'] ?? '') ?>">
                                        <button type="submit" class="ag-btn-danger-flat" style="padding: 4px 8px !important; font-size: 0.76rem !important;" title="Cancelar esta ventana">
                                            <i class="ph-bold ph-trash"></i> Cancelar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL DE CONFIRMACIÓN CUSTOM (ANTIGRAVITY DESIGN) -->
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
function switchTab(tabId, btnEl) {
    document.querySelectorAll('.ag-tab-pane').forEach(pane => pane.classList.remove('active'));
    document.querySelectorAll('.ag-tab-btn').forEach(btn => btn.classList.remove('active'));
    
    document.getElementById(tabId).classList.add('active');
    btnEl.classList.add('active');
}

/* LÓGICA DE PAGINACIÓN JS PARA LA LISTA DE RESPALDOS */
const itemsPorPagina = 5;
let paginaActualRespaldos = 1;
const totalElementosRespaldos = <?= $totalRespaldos ?>;

function renderizarPaginacionRespaldos() {
    if (totalElementosRespaldos === 0) return;
    
    const totalPaginas = Math.ceil(totalElementosRespaldos / itemsPorPagina);
    const inicio = (paginaActualRespaldos - 1) * itemsPorPagina;
    const fin = Math.min(inicio + itemsPorPagina, totalElementosRespaldos);

    const filas = document.querySelectorAll('.fila-respaldo');
    filas.forEach(f => {
        const idx = parseInt(f.getAttribute('data-index'), 10);
        if (idx >= inicio && idx < fin) {
            f.style.display = '';
        } else {
            f.style.display = 'none';
        }
    });

    const infoEl = document.getElementById('paginacion-info');
    if (infoEl) {
        infoEl.textContent = `Mostrando ${inicio + 1} - ${fin} de ${totalElementosRespaldos} respaldos (Pág ${paginaActualRespaldos}/${totalPaginas})`;
    }

    const btnPrev = document.getElementById('btn-prev-page');
    const btnNext = document.getElementById('btn-next-page');

    if (btnPrev) btnPrev.disabled = (paginaActualRespaldos === 1);
    if (btnNext) btnNext.disabled = (paginaActualRespaldos === totalPaginas);
}

function cambiarPaginaRespaldos(delta) {
    const totalPaginas = Math.ceil(totalElementosRespaldos / itemsPorPagina);
    const nuevaPagina = paginaActualRespaldos + delta;
    if (nuevaPagina >= 1 && nuevaPagina <= totalPaginas) {
        paginaActualRespaldos = nuevaPagina;
        renderizarPaginacionRespaldos();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    renderizarPaginacionRespaldos();
});

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
            body: 'Por favor selecciona un archivo de respaldo (.sql, .sql.gz o .txt) antes de proceder.',
            iconClass: 'ph-bold ph-warning-circle',
            iconBg: 'rgba(245,158,11,0.12)',
            iconColor: '#f59e0b',
            confirmText: 'Entendido',
            confirmClass: 'ag-btn-flat',
            onConfirm: () => {}
        });
        return;
    }

    const file = fileInput.files[0];
    openAGModal({
        title: 'ADVERTENCIA: Importar & Restaurar BD',
        body: `Se verificará la sintaxis del archivo '${file.name}' (${(file.size/1024).toFixed(1)} KB), se generará una copia de seguridad automática 'pre_restore_checkpoint' y se aplicarán los cambios en PostgreSQL. ¿Deseas continuar?`,
        iconClass: 'ph-bold ph-warning-circle',
        iconBg: 'rgba(239,68,68,0.12)',
        iconColor: '#ef4444',
        confirmText: 'Sí, Ejecutar Restauración',
        confirmClass: 'ag-btn-danger-flat',
        onConfirm: () => document.getElementById('formRestaurarBackup').submit()
    });
}

function confirmarRestauracionDirecta(formEl, nombreArchivo) {
    openAGModal({
        title: 'Restaurar Respaldo Local',
        body: `Se restaurará la base de datos PostgreSQL utilizando el archivo '${nombreArchivo}'. Se generará una copia de respaldo automática 'pre_restore_checkpoint' previa. ¿Deseas proceder?`,
        iconClass: 'ph-bold ph-arrows-counter-clockwise',
        iconBg: 'rgba(37,99,235,0.12)',
        iconColor: '#2563eb',
        confirmText: 'Sí, Restaurar Ahora',
        confirmClass: 'ag-btn-flat',
        onConfirm: () => formEl.submit()
    });
}
</script>
