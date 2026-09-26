<div class="sa-hero-header mb-2" style="background: #ffffff !important; border: 1px solid rgba(80, 89, 132, 0.18); padding: 1.5rem 2rem; border-radius: var(--radius-md); box-shadow: 0 4px 20px rgba(18, 26, 62, 0.04);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-terciario) !important; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 0.3rem;">
                <i class="ph-bold ph-clock-clockwise"></i> PROGRAMACIÓN AUTOMÁTICA & TAREAS EN SEGUNDO PLANO
            </div>
            <h1 style="font-size: 1.8rem; font-weight: 800; color: #121a3e !important; margin: 0;">
                Control de Tareas Programadas & Cron Jobs
            </h1>
            <p style="margin: 0.3rem 0 0 0; color: #64748b !important; font-size: 0.9rem;">
                Administración de procesos automáticos, limpiezas de temporales, scripts (.sh, .bat, .ps1, .py, .php, .sql) y comandos CLI.
            </p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <button onclick="abrirModalTarea()" class="sa-btn sa-btn-primary">
                <i class="ph-bold ph-plus-circle"></i> + Nueva Tarea
            </button>
            <a href="sudoadmin" class="sa-btn sa-btn-outline">
                <i class="ph-bold ph-arrow-left"></i> Volver al Centro de Mando
            </a>
        </div>
    </div>
</div>

<!-- ALERTAS DE ÉXITO O ERROR DE SESIÓN -->
<?php if (isset($_SESSION['mensaje_admin_exito'])): ?>
    <div class="sa-alert sa-alert-success">
        <i class="ph-bold ph-check-circle"></i>
        <?= htmlspecialchars($_SESSION['mensaje_admin_exito']) ?>
        <?php unset($_SESSION['mensaje_admin_exito']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['mensaje_admin_error'])): ?>
    <div class="sa-alert sa-alert-error">
        <i class="ph-bold ph-warning-circle"></i>
        <?= htmlspecialchars($_SESSION['mensaje_admin_error']) ?>
        <?php unset($_SESSION['mensaje_admin_error']); ?>
    </div>
<?php endif; ?>

<!-- PANEL DE DIAGNÓSTICO DEL ENTORNO CRON DEL SISTEMA OPERATIVO -->
<div class="glass-panel mb-2" style="padding: 1.25rem; border-radius: var(--radius-sm); border-left: 4px solid var(--color-secundario); background: rgba(255,255,255,0.95); backdrop-filter: blur(12px);">
    <h4 style="margin: 0 0 0.5rem 0; font-size: 0.95rem; font-weight: 700; color: var(--texto-titulos); display: flex; align-items: center; gap: 8px;">
        <i class="ph-bold ph-linux-logo" style="font-size: 1.2rem;"></i>
        Estado del Programador del Sistema Operativo
    </h4>
    <p style="margin: 0 0 0.75rem 0; font-size: 0.85rem; color: #475569;">
        <?= htmlspecialchars($diagnostico['detalles']) ?>
    </p>

    <div style="background: #0f172a; color: #38bdf8; padding: 12px 16px; border-radius: 8px; font-family: monospace; font-size: 0.85rem; overflow-x: auto; display: flex; justify-content: space-between; align-items: center; box-shadow: inset 0 2px 4px rgba(0,0,0,0.3);">
        <span><?= htmlspecialchars($diagnostico['es_windows'] ? $diagnostico['comando_sugerido_windows'] : $diagnostico['comando_sugerido_linux']) ?></span>
        <span style="font-size: 0.75rem; background: rgba(56, 189, 248, 0.15); color: #38bdf8; padding: 3px 10px; border-radius: 6px; font-weight: 700;"><?= $diagnostico['es_windows'] ? 'Task Scheduler Windows' : 'Sintaxis Linux Crontab' ?></span>
    </div>
</div>

<!-- TARJETAS DE TAREAS PROGRAMADAS -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 1.5rem;" class="mb-2">
    <?php foreach ($tareas as $id => $tarea): ?>
        <?php 
            $tipoEj = $tarea['tipo_ejecucion'] ?? 'metodo_interno';
            $badgeTipo = 'Método Core';
            if ($tipoEj === 'script_archivo') $badgeTipo = 'Script Archivo (' . htmlspecialchars($tarea['archivo_script'] ?? '') . ')';
            if ($tipoEj === 'comando_cli') $badgeTipo = 'Comando Shell / CLI';
        ?>
        <div class="ag-scheduler-card">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                    <span class="<?= $tarea['estado'] === 'activo' ? 'sa-badge-active' : 'sa-badge-inactive' ?>">
                        <i class="ph-bold <?= $tarea['estado'] === 'activo' ? 'ph-check-circle' : 'ph-pause-circle' ?>"></i> <?= strtoupper($tarea['estado']) ?>
                    </span>
                    <div style="text-align: right;">
                        <span style="font-family: monospace; font-size: 0.8rem; background: #f8fafc; padding: 4px 10px; border-radius: var(--radius-sm); border: 1px solid #e2e8f0; font-weight: 700; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: inline-block;">
                            <?= htmlspecialchars($tarea['expresion_cron']) ?>
                        </span>
                        <div style="font-size: 0.72rem; color: #0284c7; font-weight: 700; margin-top: 3px; display: flex; align-items: center; justify-content: flex-end; gap: 4px;">
                            <i class="ph-bold ph-clock"></i> <?= htmlspecialchars(SchedulerService::describirCron($tarea['expresion_cron'])) ?>
                        </div>
                    </div>
                </div>

                <h3 style="margin: 0 0 0.4rem 0; font-size: 1.1rem; font-weight: 700; color: #1e293b;">
                    <?= htmlspecialchars($tarea['nombre']) ?>
                </h3>
                <p style="margin: 0; font-size: 0.83rem; color: #64748b; line-height: 1.4;">
                    <?= htmlspecialchars($tarea['descripcion']) ?>
                </p>
                
                <div style="font-size: 0.75rem; color: #64748b; margin-top: 8px; font-family: monospace; background: rgba(241, 245, 249, 0.7); padding: 6px 10px; border-radius: 6px; border: 1px solid #e2e8f0;">
                    <i class="ph-bold ph-terminal-window"></i> <b>Modo:</b> <?= $badgeTipo ?>
                </div>
            </div>

            <div style="border-top: 1px solid #f1f5f9; padding-top: 0.85rem;">
                <div style="font-size: 0.78rem; color: #475569; margin-bottom: 0.85rem;">
                    <div><b>Última ejecución:</b> <?= $tarea['ultima_ejecucion'] ? htmlspecialchars($tarea['ultima_ejecucion']) : 'Nunca' ?></div>
                    <div style="margin-top: 2px; color: #64748b; font-style: italic;" class="text-truncate">
                        <?= htmlspecialchars($tarea['resultado_ultimo']) ?>
                    </div>
                </div>

                <div style="display: flex; gap: 0.4rem; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                    <div style="display: flex; gap: 0.35rem; align-items: center;">
                        <button type="button" onclick='editarTarea(<?= json_encode($tarea) ?>)' class="ag-btn-icon" title="Editar Configuración">
                            <i class="ph-bold ph-pencil"></i>
                        </button>

                        <button type="button" onclick="verLogConsola('<?= htmlspecialchars($id) ?>', '<?= htmlspecialchars($tarea['nombre'], ENT_QUOTES) ?>')" class="ag-btn-icon" title="Ver Histórico stdout/stderr">
                            <i class="ph-bold ph-code-block"></i>
                        </button>

                        <form action="alternar-estado-tarea" method="POST" style="margin:0;">
                            <input type="hidden" name="tarea_id" value="<?= htmlspecialchars($id) ?>">
                            <input type="hidden" name="nuevo_estado" value="<?= $tarea['estado'] === 'activo' ? 'inactivo' : 'activo' ?>">
                            <button type="submit" class="ag-btn-standard ag-btn-secondary" style="height: 34px !important; padding: 0 12px !important; font-size: 0.78rem !important;">
                                <?= $tarea['estado'] === 'activo' ? 'Pausar' : 'Activar' ?>
                            </button>
                        </form>
                    </div>

                    <div style="display: flex; gap: 0.35rem; align-items: center;">
                        <form id="form_eliminar_<?= htmlspecialchars($id) ?>" action="eliminar-tarea-programada" method="POST" style="margin:0;">
                            <input type="hidden" name="tarea_id" value="<?= htmlspecialchars($id) ?>">
                            <button type="button" onclick="pedirConfirmacionEliminar('form_eliminar_<?= htmlspecialchars($id) ?>', '<?= htmlspecialchars($tarea['nombre'], ENT_QUOTES) ?>')" class="ag-btn-danger-icon" title="Eliminar Tarea">
                                <i class="ph-bold ph-trash"></i>
                            </button>
                        </form>

                        <form action="ejecutar-tarea-manual" method="POST" style="margin:0;">
                            <input type="hidden" name="tarea_id" value="<?= htmlspecialchars($id) ?>">
                            <button type="submit" class="ag-btn-standard ag-btn-primary" style="height: 34px !important; padding: 0 12px !important; font-size: 0.78rem !important;">
                                <i class="ph-bold ph-play"></i> Ejecutar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- MODAL CREAR / EDITAR TAREA PROGRAMADA MULTI-ENGINE -->
<div id="modalTareaScheduler" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 9999; justify-content: center; align-items: center; padding: 1rem; transition: all 0.3s ease;">
    <div style="background: rgba(255, 255, 255, 0.98); border-radius: 16px; max-width: 630px; width: 100%; padding: 2rem; box-shadow: 0 24px 48px rgba(18, 26, 62, 0.18); border: 1px solid rgba(80, 89, 132, 0.2); max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 id="modalTareaTitulo" style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #121a3e;">
                Programar Tarea / Editar Horario
            </h3>
            <button type="button" onclick="cerrarModalTarea()" style="background: none; border: none; font-size: 1.2rem; color: #64748b; cursor: pointer; padding: 4px;">
                <i class="ph-bold ph-x"></i>
            </button>
        </div>
        
        <form action="guardar-tarea-programada" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="tarea_id" id="form_tarea_id" value="">

            <div style="margin-bottom: 1.1rem;">
                <label class="ag-form-label">Nombre de la Tarea</label>
                <input type="text" name="nombre" id="form_tarea_nombre" class="ag-form-input" placeholder="Ej: Respaldo Semanal / Script de Mantenimiento..." required>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                    <label class="ag-form-label" style="margin-bottom: 0;">Expresión Cron (Frecuencia de Ejecución)</label>
                    <span style="font-size: 0.72rem; color: #0284c7; font-weight: 700;">
                        <i class="ph-bold ph-lightning"></i> Atajos rápidos con 1 clic:
                    </span>
                </div>

                <!-- PRESETS / ATAJOS RÁPIDOS CON 1 CLIC -->
                <div style="display: flex; gap: 5px; flex-wrap: wrap; margin-bottom: 8px;">
                    <button type="button" class="cron-preset-btn" onclick="aplicarPresetCron('*/15 * * * *')">Cada 15 min</button>
                    <button type="button" class="cron-preset-btn" onclick="aplicarPresetCron('0 * * * *')">Cada hora</button>
                    <button type="button" class="cron-preset-btn" onclick="aplicarPresetCron('0 0 * * *')">Medianoche (00:00)</button>
                    <button type="button" class="cron-preset-btn" onclick="aplicarPresetCron('0 12 * * *')">Mediodía (12:00)</button>
                    <button type="button" class="cron-preset-btn" onclick="aplicarPresetCron('0 3 * * *')">Madrugada (03:00)</button>
                    <button type="button" class="cron-preset-btn" onclick="aplicarPresetCron('0 8 * * 1-5')">Lun-Vie (8:00 AM)</button>
                    <button type="button" class="cron-preset-btn" onclick="aplicarPresetCron('0 4 * * 0')">Domingos (4:00 AM)</button>
                    <button type="button" class="cron-preset-btn" onclick="aplicarPresetCron('0 0 1 * *')">1° de cada mes</button>
                </div>

                <!-- INPUT PRINCIPAL DE CRON -->
                <div style="position: relative;">
                    <input type="text" name="expresion_cron" id="form_tarea_cron" class="ag-form-input" 
                           style="font-family: monospace; font-size: 1.05rem; letter-spacing: 1.5px; font-weight: 800; color: #0284c7; padding-left: 2.25rem;" 
                           placeholder="Ej: 0 0 * * *" oninput="actualizarExplicacionCron()" required>
                    <i class="ph-bold ph-clock" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); font-size: 1.15rem; color: #0284c7;"></i>
                </div>

                <!-- TARJETA DE EXPLICACIÓN EN VIVO (HUMAN READABLE & FIELD BREAKDOWN) -->
                <div id="cron_live_card" style="background: linear-gradient(135deg, rgba(2, 132, 199, 0.06), rgba(56, 189, 248, 0.04)); border: 1px solid rgba(2, 132, 199, 0.25); border-radius: 10px; padding: 0.85rem 1rem; margin-top: 0.65rem; transition: all 0.2s ease;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                        <span style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #0284c7; display: flex; align-items: center; gap: 5px;">
                            <i class="ph-bold ph-sparkle"></i> ¿Cuándo se ejecutará esta tarea?
                        </span>
                        <span id="cron_badge_tipo" style="font-size: 0.68rem; font-weight: 700; padding: 2px 8px; border-radius: 12px; background: #e0f2fe; color: #0369a1;">
                            Diario
                        </span>
                    </div>

                    <div id="cron_texto_humano" style="font-size: 0.95rem; font-weight: 800; color: #0f172a; line-height: 1.35;">
                        Cargando interpretación...
                    </div>

                    <!-- DESGLOSE VISUAL DE LOS 5 CAMPOS CRON -->
                    <div style="margin-top: 0.65rem; padding-top: 0.55rem; border-top: 1px dashed rgba(2, 132, 199, 0.2); display: grid; grid-template-columns: repeat(5, 1fr); gap: 6px; text-align: center;">
                        <div class="cron-field-chip" style="background: #ffffff; padding: 5px 2px; border-radius: 6px; border: 1px solid #e2e8f0;">
                            <div style="font-size: 0.60rem; color: #64748b; font-weight: 700; text-transform: uppercase;">1. Minuto</div>
                            <div id="chip_val_minuto" style="font-family: monospace; font-size: 0.88rem; font-weight: 800; color: #0284c7;">0</div>
                            <div id="chip_desc_minuto" style="font-size: 0.63rem; color: #475569; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">En punto</div>
                        </div>
                        <div class="cron-field-chip" style="background: #ffffff; padding: 5px 2px; border-radius: 6px; border: 1px solid #e2e8f0;">
                            <div style="font-size: 0.60rem; color: #64748b; font-weight: 700; text-transform: uppercase;">2. Hora</div>
                            <div id="chip_val_hora" style="font-family: monospace; font-size: 0.88rem; font-weight: 800; color: #0284c7;">0</div>
                            <div id="chip_desc_hora" style="font-size: 0.63rem; color: #475569; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">12:00 AM</div>
                        </div>
                        <div class="cron-field-chip" style="background: #ffffff; padding: 5px 2px; border-radius: 6px; border: 1px solid #e2e8f0;">
                            <div style="font-size: 0.60rem; color: #64748b; font-weight: 700; text-transform: uppercase;">3. Día Mes</div>
                            <div id="chip_val_dia_mes" style="font-family: monospace; font-size: 0.88rem; font-weight: 800; color: #0284c7;">*</div>
                            <div id="chip_desc_dia_mes" style="font-size: 0.63rem; color: #475569; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Todos</div>
                        </div>
                        <div class="cron-field-chip" style="background: #ffffff; padding: 5px 2px; border-radius: 6px; border: 1px solid #e2e8f0;">
                            <div style="font-size: 0.60rem; color: #64748b; font-weight: 700; text-transform: uppercase;">4. Mes</div>
                            <div id="chip_val_mes" style="font-family: monospace; font-size: 0.88rem; font-weight: 800; color: #0284c7;">*</div>
                            <div id="chip_desc_mes" style="font-size: 0.63rem; color: #475569; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Todos</div>
                        </div>
                        <div class="cron-field-chip" style="background: #ffffff; padding: 5px 2px; border-radius: 6px; border: 1px solid #e2e8f0;">
                            <div style="font-size: 0.60rem; color: #64748b; font-weight: 700; text-transform: uppercase;">5. Día Sem</div>
                            <div id="chip_val_dia_sem" style="font-family: monospace; font-size: 0.88rem; font-weight: 800; color: #0284c7;">*</div>
                            <div id="chip_desc_dia_sem" style="font-size: 0.63rem; color: #475569; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Cualquiera</div>
                        </div>
                    </div>
                </div>

                <!-- GUÍA RÁPIDA COLAPSABLE DE SÍMBOLOS -->
                <details style="margin-top: 0.45rem; font-size: 0.73rem; color: #64748b;">
                    <summary style="font-weight: 700; color: #0284c7; cursor: pointer; outline: none; user-select: none; display: inline-flex; align-items: center; gap: 4px;">
                        <i class="ph-bold ph-question"></i> ¿Cómo funcionan los símbolos especiales? (Clic para ver)
                    </summary>
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; margin-top: 6px; line-height: 1.45; font-size: 0.72rem; color: #334155;">
                        <div style="margin-bottom: 3px;">• <b><code>*</code> (Asterisco):</b> Cualquier valor / siempre (ej: todos los minutos, todos los meses).</div>
                        <div style="margin-bottom: 3px;">• <b><code>*/N</code> (Paso / Intervalo):</b> Cada N unidades (ej: <code>*/15</code> en minuto = cada 15 min; <code>*/6</code> en hora = cada 6 horas).</div>
                        <div style="margin-bottom: 3px;">• <b><code>-</code> (Guion / Rango):</b> Rango continuo (ej: <code>1-5</code> en día-semana = de lunes a viernes).</div>
                        <div>• <b><code>,</code> (Coma / Lista):</b> Valores específicos (ej: <code>0,30</code> en minuto = en el min 0 y 30; <code>1,15</code> = días 1 y 15).</div>
                    </div>
                </details>
            </div>

            <div style="margin-bottom: 1.1rem;">
                <label class="ag-form-label">Tipo de Ejecución & Motor</label>
                <select name="tipo_ejecucion" id="form_tipo_ejecucion" class="ag-form-input" style="appearance: auto;" onchange="cambiarTipoEjecucion(this.value)" required>
                    <option value="metodo_interno">Método Interno de PHP (SchedulerService)</option>
                    <option value="script_archivo">Subir / Ejecutar Archivo Script (.sh, .bat, .ps1, .py, .php, .sql)</option>
                    <option value="comando_cli">Comando Personalizado CLI / Shell</option>
                </select>
            </div>

            <!-- SECCIÓN A: MÉTODO INTERNO -->
            <div id="sec_metodo_interno" style="margin-bottom: 1.1rem;">
                <label class="ag-form-label">Seleccionar Método Core</label>
                <select name="script" id="form_tarea_script" class="ag-form-input" style="appearance: auto;">
                    <option value="limpiarTemporales">limpiarTemporales (Archivos temporales/caché)</option>
                    <option value="ejecutarBackupAutomatico">ejecutarBackupAutomatico (PostgreSQL Dump .sql.gz)</option>
                    <option value="purgarLogs">purgarLogs (Rotación de auditoría activa)</option>
                </select>
            </div>

            <!-- SECCIÓN B: SCRIPT ARCHIVO -->
            <div id="sec_script_archivo" style="margin-bottom: 1.1rem; display: none; background: rgba(248, 250, 252, 0.9); padding: 1rem; border-radius: 10px; border: 1px dashed rgba(80, 89, 132, 0.3);">
                <label class="ag-form-label">Cargar Archivo de Script (.sh, .bat, .ps1, .php, .py, .sql)</label>
                <input type="file" name="archivo_script" id="form_archivo_script" class="ag-form-input" accept=".sh,.bat,.ps1,.php,.py,.sql">
                <div id="info_script_actual" style="font-size: 0.76rem; color: #0284c7; margin-top: 6px; font-weight: 600;"></div>
                <div style="font-size: 0.74rem; color: #64748b; margin-top: 4px; line-height: 1.3;">
                    El servidor ejecutará automáticamente el binario adecuado según la plataforma (Bash, PowerShell, Cmd, Python3, PHP CLI, psql).
                </div>
            </div>

            <!-- SECCIÓN C: COMANDO CLI -->
            <div id="sec_comando_cli" style="margin-bottom: 1.1rem; display: none;">
                <label class="ag-form-label">Comando de Consola / Shell CLI</label>
                <textarea name="comando_custom" id="form_comando_custom" class="ag-form-input" style="height: 70px; font-family: monospace; font-size: 0.82rem; color: #0f172a;" placeholder="Ej: php /var/www/script.php o pg_dumpall -U postgres"></textarea>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label class="ag-form-label">Descripción de la Tarea</label>
                <textarea name="descripcion" id="form_tarea_descripcion" class="ag-form-input" style="height: 65px; resize: vertical;" placeholder="Propósito o anotaciones de esta automatización..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem; border-top: 1px solid #e2e8f0; padding-top: 1rem;">
                <button type="button" onclick="cerrarModalTarea()" class="sa-btn sa-btn-cancel" style="min-width: 110px;">Cancelar</button>
                <button type="submit" class="sa-btn sa-btn-primary" style="min-width: 150px;">Guardar Configuración</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL VISOR DE LOGS DE CONSOLA (STDOUT / STDERR) -->
<div id="modalVisorLog" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 10000; justify-content: center; align-items: center; padding: 1rem;">
    <div style="background: #ffffff; border-radius: var(--radius-md); max-width: 720px; width: 100%; padding: 1.75rem; box-shadow: 0 24px 48px rgba(18, 26, 62, 0.2); border: 1px solid rgba(80, 89, 132, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <div>
                <h3 style="margin: 0; font-size: 1.2rem; font-weight: 800; color: #121a3e; display: flex; align-items: center; gap: 8px;">
                    <i class="ph-bold ph-terminal" style="color: var(--color-secundario);"></i>
                    Consola Output Log (stdout/stderr)
                </h3>
                <span id="logModalNombreTarea" style="font-size: 0.8rem; color: #64748b; font-weight: 600;"></span>
            </div>
            <button type="button" onclick="cerrarModalLog()" style="background: none; border: none; font-size: 1.2rem; color: #64748b; cursor: pointer; padding: 4px;">
                <i class="ph-bold ph-x"></i>
            </button>
        </div>
        
        <div id="logContentBox" style="background: #0f172a; color: #38bdf8; padding: 1.25rem; border-radius: 10px; font-family: monospace; font-size: 0.82rem; height: 340px; overflow-y: auto; white-space: pre-wrap; word-break: break-word; box-shadow: inset 0 2px 8px rgba(0,0,0,0.5);">
            Cargando historial de salida...
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: 1.25rem;">
            <button type="button" onclick="cerrarModalLog()" class="sa-btn sa-btn-cancel" style="min-width: 110px;">
                Cerrar Visor
            </button>
        </div>
    </div>
</div>

<!-- MODAL CONFIRMACIÓN DE ELIMINACIÓN -->
<div id="modalConfirmacionGenerico" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 10001; justify-content: center; align-items: center; padding: 1rem;">
    <div style="background: #ffffff; border-radius: var(--radius-md); max-width: 440px; width: 100%; padding: 1.75rem; box-shadow: 0 24px 48px rgba(18, 26, 62, 0.2); border: 1px solid rgba(80, 89, 132, 0.2); text-align: center;">
        <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(80, 89, 132, 0.12); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 1rem auto;">
            <i class="ph-bold ph-warning"></i>
        </div>
        
        <h3 style="margin: 0 0 0.5rem 0; font-size: 1.2rem; font-weight: 800; color: #121a3e;">
            Confirmación de Seguridad
        </h3>
        
        <p id="modalConfirmacionMensaje" style="margin: 0 0 1.5rem 0; font-size: 0.88rem; color: #64748b; line-height: 1.4;">
            ¿Está seguro de eliminar esta tarea programada?
        </p>

        <div style="display: flex; justify-content: center; gap: 0.75rem;">
            <button type="button" onclick="cerrarModalConfirmacion()" class="sa-btn sa-btn-cancel" style="min-width: 110px;">
                Cancelar
            </button>
            <button type="button" id="btnConfirmarAccionModal" class="sa-btn sa-btn-dark" style="min-width: 110px;">
                Eliminar
            </button>
        </div>
    </div>
</div>

<script>
let formTargetParaEliminar = null;

function cambiarTipoEjecucion(tipo) {
    document.getElementById('sec_metodo_interno').style.display = tipo === 'metodo_interno' ? 'block' : 'none';
    document.getElementById('sec_script_archivo').style.display = tipo === 'script_archivo' ? 'block' : 'none';
    document.getElementById('sec_comando_cli').style.display = tipo === 'comando_cli' ? 'block' : 'none';
}

function abrirModalTarea() {
    document.getElementById('modalTareaTitulo').innerText = 'Crear Nueva Tarea Programada';
    document.getElementById('form_tarea_id').value = '';
    document.getElementById('form_tarea_nombre').value = '';
    document.getElementById('form_tarea_cron').value = '0 0 * * *';
    actualizarExplicacionCron();
    document.getElementById('form_tipo_ejecucion').value = 'metodo_interno';
    cambiarTipoEjecucion('metodo_interno');
    document.getElementById('form_tarea_descripcion').value = '';
    document.getElementById('form_comando_custom').value = '';
    document.getElementById('info_script_actual').innerText = '';
    document.getElementById('modalTareaScheduler').style.display = 'flex';
}

function editarTarea(tarea) {
    document.getElementById('modalTareaTitulo').innerText = 'Editar Tarea Programada';
    document.getElementById('form_tarea_id').value = tarea.id || '';
    document.getElementById('form_tarea_nombre').value = tarea.nombre || '';
    document.getElementById('form_tarea_cron').value = tarea.expresion_cron || '0 0 * * *';
    actualizarExplicacionCron();
    
    const tipo = tarea.tipo_ejecucion || 'metodo_interno';
    document.getElementById('form_tipo_ejecucion').value = tipo;
    cambiarTipoEjecucion(tipo);

    if (tarea.script) document.getElementById('form_tarea_script').value = tarea.script;
    if (tarea.comando_custom) document.getElementById('form_comando_custom').value = tarea.comando_custom;
    if (tarea.archivo_script) {
        document.getElementById('info_script_actual').innerText = 'Archivo actual cargado: ' + tarea.archivo_script;
    } else {
        document.getElementById('info_script_actual').innerText = '';
    }

    document.getElementById('form_tarea_descripcion').value = tarea.descripcion || '';
    document.getElementById('modalTareaScheduler').style.display = 'flex';
}

function aplicarPresetCron(expresion) {
    const input = document.getElementById('form_tarea_cron');
    if (!input) return;
    input.value = expresion;
    actualizarExplicacionCron();
    input.focus();
}

function actualizarExplicacionCron() {
    const input = document.getElementById('form_tarea_cron');
    if (!input) return;
    const val = input.value.trim();
    const partes = val.split(/\s+/).filter(p => p.length > 0);

    const liveCard = document.getElementById('cron_live_card');
    const badgeTipo = document.getElementById('cron_badge_tipo');
    const txtHumano = document.getElementById('cron_texto_humano');

    const chipValMin = document.getElementById('chip_val_minuto');
    const chipDescMin = document.getElementById('chip_desc_minuto');
    const chipValHora = document.getElementById('chip_val_hora');
    const chipDescHora = document.getElementById('chip_desc_hora');
    const chipValDiaM = document.getElementById('chip_val_dia_mes');
    const chipDescDiaM = document.getElementById('chip_desc_dia_mes');
    const chipValMes = document.getElementById('chip_val_mes');
    const chipDescMes = document.getElementById('chip_desc_mes');
    const chipValDiaS = document.getElementById('chip_val_dia_sem');
    const chipDescDiaS = document.getElementById('chip_desc_dia_sem');

    if (!liveCard || !badgeTipo || !txtHumano) return;

    if (partes.length !== 5) {
        liveCard.style.borderColor = 'rgba(245, 158, 11, 0.4)';
        liveCard.style.background = 'linear-gradient(135deg, rgba(254, 243, 199, 0.4), rgba(253, 230, 138, 0.15))';
        badgeTipo.style.background = '#fef3c7';
        badgeTipo.style.color = '#92400e';
        badgeTipo.innerText = `Incompleto (${partes.length}/5 campos)`;
        txtHumano.innerHTML = `<span style="color: #b45309;"><i class="ph-bold ph-warning"></i> Expresión incompleta:</span> se requieren 5 campos separados por espacio (llevas <b>${partes.length}</b> de 5: <code>minuto hora día mes día-sem</code>).`;

        chipValMin.innerText = partes[0] || '-';
        chipDescMin.innerText = partes[0] ? 'Ingresado' : 'Falta';
        chipValHora.innerText = partes[1] || '-';
        chipDescHora.innerText = partes[1] ? 'Ingresado' : 'Falta';
        chipValDiaM.innerText = partes[2] || '-';
        chipDescDiaM.innerText = partes[2] ? 'Ingresado' : 'Falta';
        chipValMes.innerText = partes[3] || '-';
        chipDescMes.innerText = partes[3] ? 'Ingresado' : 'Falta';
        chipValDiaS.innerText = partes[4] || '-';
        chipDescDiaS.innerText = partes[4] ? 'Ingresado' : 'Falta';
        return;
    }

    liveCard.style.borderColor = 'rgba(2, 132, 199, 0.25)';
    liveCard.style.background = 'linear-gradient(135deg, rgba(2, 132, 199, 0.06), rgba(56, 189, 248, 0.04))';
    badgeTipo.style.background = '#e0f2fe';
    badgeTipo.style.color = '#0369a1';

    const [min, hora, diaM, mes, diaS] = partes;

    // Actualizar chips de valores
    chipValMin.innerText = min;
    chipValHora.innerText = hora;
    chipValDiaM.innerText = diaM;
    chipValMes.innerText = mes;
    chipValDiaS.innerText = diaS;

    const nombresDias = {
        '0': 'Domingos', '1': 'Lunes', '2': 'Martes', '3': 'Miércoles',
        '4': 'Jueves', '5': 'Viernes', '6': 'Sábados', '7': 'Domingos'
    };
    const nombresMeses = {
        '1': 'Enero', '2': 'Febrero', '3': 'Marzo', '4': 'Abril',
        '5': 'Mayo', '6': 'Junio', '7': 'Julio', '8': 'Agosto',
        '9': 'Septiembre', '10': 'Octubre', '11': 'Noviembre', '12': 'Diciembre'
    };

    // Subtítulo de cada chip individual
    if (min === '*') chipDescMin.innerText = 'Cualquier min';
    else if (min.startsWith('*/')) chipDescMin.innerText = `Cada ${min.slice(2)} min`;
    else chipDescMin.innerText = (min === '0') ? 'En punto' : `Minuto :${min.padStart(2, '0')}`;

    if (hora === '*') chipDescHora.innerText = 'Cualquier hora';
    else if (hora.startsWith('*/')) chipDescHora.innerText = `Cada ${hora.slice(2)}h`;
    else {
        const h = parseInt(hora, 10);
        if (h === 0) chipDescHora.innerText = '12:00 AM (00h)';
        else if (h === 12) chipDescHora.innerText = '12:00 PM (12h)';
        else chipDescHora.innerText = (h > 12) ? `${h - 12}:00 PM` : `${h}:00 AM`;
    }

    if (diaM === '*') chipDescDiaM.innerText = 'Todos';
    else chipDescDiaM.innerText = `Día ${diaM}`;

    if (mes === '*') chipDescMes.innerText = 'Todos';
    else chipDescMes.innerText = nombresMeses[mes] || `Mes ${mes}`;

    if (diaS === '*') chipDescDiaS.innerText = 'Cualquiera';
    else if (diaS === '1-5') chipDescDiaS.innerText = 'Lun a Vie';
    else if (diaS === '6,0' || diaS === '0,6' || diaS === '6,7') chipDescDiaS.innerText = 'Fin de sem';
    else chipDescDiaS.innerText = nombresDias[diaS] || `Día ${diaS}`;

    // Función auxiliar para formatear hora amigable
    function formatearHora(hStr, mStr) {
        const h = parseInt(hStr, 10);
        const m = parseInt(mStr, 10);
        const mPad = String(m).padStart(2, '0');
        if (isNaN(h) || isNaN(m)) return `${hStr}:${mStr}`;
        if (h === 0 && m === 0) return '12:00 AM (Medianoche)';
        if (h === 12 && m === 0) return '12:00 PM (Mediodía)';
        const ampm = h >= 12 ? 'PM' : 'AM';
        const h12 = (h % 12) === 0 ? 12 : (h % 12);
        return `${String(h12).padStart(2, '0')}:${mPad} ${ampm}`;
    }

    let explicacion = '';
    let categoria = 'Personalizado';

    if (min === '*' && hora === '*' && diaM === '*' && mes === '*' && diaS === '*') {
        explicacion = 'Se ejecutará <b>continuamente cada minuto</b> del día.';
        categoria = 'Cada Minuto';
    } else if (min.startsWith('*/') && hora === '*' && diaM === '*' && mes === '*' && diaS === '*') {
        const step = min.slice(2);
        explicacion = `Se ejecutará automáticamente <b>cada ${step} minutos</b>.`;
        categoria = 'Intervalo Frecuente';
    } else if (min === '0' && hora === '*' && diaM === '*' && mes === '*' && diaS === '*') {
        explicacion = 'Se ejecutará <b>cada hora en punto</b> (ej: 1:00, 2:00, 3:00...).';
        categoria = 'Cada Hora';
    } else if (/^\d+$/.test(min) && hora === '*' && diaM === '*' && mes === '*' && diaS === '*') {
        explicacion = `Se ejecutará <b>cada hora exactamente en el minuto ${min}</b>.`;
        categoria = 'Por Hora';
    } else if (hora.startsWith('*/') && diaM === '*' && mes === '*' && diaS === '*') {
        const stepH = hora.slice(2);
        const minTxt = (min === '0') ? 'en punto' : `en el minuto :${String(min).padStart(2, '0')}`;
        explicacion = `Se ejecutará <b>cada ${stepH} horas (${minTxt})</b>.`;
        categoria = 'Intervalo de Horas';
    } else if (/^\d+$/.test(min) && /^\d+$/.test(hora)) {
        const horaFormateada = formatearHora(hora, min);
        if (diaM === '*' && mes === '*' && diaS === '*') {
            explicacion = `Se ejecutará <b>todos los días a las ${horaFormateada}</b>.`;
            categoria = 'Diario';
        } else if (diaM === '*' && mes === '*' && (diaS === '1-5' || diaS === '1,2,3,4,5')) {
            explicacion = `Se ejecutará <b>de lunes a viernes a las ${horaFormateada}</b>.`;
            categoria = 'Días Laborales';
        } else if (diaM === '*' && mes === '*' && (diaS === '6,0' || diaS === '0,6' || diaS === '6,7')) {
            explicacion = `Se ejecutará los <b>fines de semana (sábado y domingo) a las ${horaFormateada}</b>.`;
            categoria = 'Fines de Semana';
        } else if (diaM === '*' && mes === '*' && nombresDias[diaS]) {
            explicacion = `Se ejecutará semanalmente los <b>${nombresDias[diaS]} a las ${horaFormateada}</b>.`;
            categoria = 'Semanal';
        } else if (diaM !== '*' && mes === '*' && diaS === '*') {
            explicacion = `Se ejecutará el <b>día ${diaM} de cada mes a las ${horaFormateada}</b>.`;
            categoria = 'Mensual';
        } else if (diaM !== '*' && nombresMeses[mes] && diaS === '*') {
            explicacion = `Se ejecutará cada año el <b>${diaM} de ${nombresMeses[mes]} a las ${horaFormateada}</b>.`;
            categoria = 'Anual';
        } else {
            explicacion = `Se ejecutará a las <b>${horaFormateada}</b> (Día mes: ${diaM}, Mes: ${mes}, Día sem: ${diaS}).`;
            categoria = 'Programado';
        }
    } else {
        explicacion = `Expresión personalizada: <code>${val}</code>`;
        categoria = 'Avanzado';
    }

    badgeTipo.innerText = categoria;
    txtHumano.innerHTML = explicacion;
}

function cerrarModalTarea() {
    document.getElementById('modalTareaScheduler').style.display = 'none';
}

function verLogConsola(idTarea, nombreTarea) {
    document.getElementById('logModalNombreTarea').innerText = 'Tarea: ' + nombreTarea;
    document.getElementById('logContentBox').innerText = 'Cargando registro de salida desde el servidor...';
    document.getElementById('modalVisorLog').style.display = 'flex';

    fetch('ver-log-tarea?id=' + encodeURIComponent(idTarea))
        .then(response => response.text())
        .then(text => {
            document.getElementById('logContentBox').innerText = text;
        })
        .catch(err => {
            document.getElementById('logContentBox').innerText = 'Error al recuperar el archivo de log: ' + err;
        });
}

function cerrarModalLog() {
    document.getElementById('modalVisorLog').style.display = 'none';
}

function pedirConfirmacionEliminar(formId, nombreTarea) {
    formTargetParaEliminar = document.getElementById(formId);
    document.getElementById('modalConfirmacionMensaje').innerHTML = `¿Está seguro de eliminar la tarea programada <b>"${nombreTarea}"</b>? Esta acción no se puede deshacer.`;
    document.getElementById('modalConfirmacionGenerico').style.display = 'flex';
}

function cerrarModalConfirmacion() {
    document.getElementById('modalConfirmacionGenerico').style.display = 'none';
    formTargetParaEliminar = null;
}

document.getElementById('btnConfirmarAccionModal').addEventListener('click', function() {
    if (formTargetParaEliminar) {
        formTargetParaEliminar.submit();
    }
});
</script>
