<style>
/* ==========================================================================
   Antigravity UI & Motion Design Expert Style Guide (estilo.md) - Scheduler
   ========================================================================== */
.ag-btn-standard {
    height: 38px !important;
    padding: 0 16px !important;
    border-radius: 8px !important;
    font-weight: 700 !important;
    font-size: 0.85rem !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6px !important;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
    cursor: pointer !important;
    border: none !important;
    text-decoration: none !important;
    box-sizing: border-box !important;
}

.ag-btn-icon {
    width: 34px !important;
    height: 34px !important;
    padding: 0 !important;
    border-radius: 8px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
    cursor: pointer !important;
    border: 1px solid rgba(80, 89, 132, 0.2) !important;
    background: #ffffff !important;
    color: #334155 !important;
}

.ag-btn-icon:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(18, 26, 62, 0.1);
    background: #f8fafc !important;
    border-color: var(--color-secundario) !important;
    color: var(--color-secundario) !important;
}

.ag-btn-danger-icon {
    width: 34px !important;
    height: 34px !important;
    padding: 0 !important;
    border-radius: 8px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
    cursor: pointer !important;
    border: 1px solid rgba(239, 68, 68, 0.25) !important;
    background: rgba(239, 68, 68, 0.08) !important;
    color: #dc2626 !important;
}

.ag-btn-danger-icon:hover {
    transform: translateY(-2px);
    background: #dc2626 !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
}

.ag-btn-primary {
    background: var(--color-secundario) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.2);
}

.ag-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(37, 99, 235, 0.32);
    background: #1d4ed8 !important;
}

.ag-btn-secondary {
    background: #ffffff !important;
    color: var(--color-secundario) !important;
    border: 1px solid rgba(80, 89, 132, 0.25) !important;
}

.ag-btn-secondary:hover {
    transform: translateY(-2px);
    background: #f8fafc !important;
    border-color: var(--color-secundario) !important;
    box-shadow: 0 4px 12px rgba(18, 26, 62, 0.06);
}

/* Glassmorphism Floating Cards */
.ag-scheduler-card {
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(80, 89, 132, 0.15) !important;
    border-radius: 14px !important;
    padding: 1.5rem !important;
    box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 1rem;
}

.ag-scheduler-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(18, 26, 62, 0.08);
    border-color: rgba(80, 89, 132, 0.28) !important;
}

/* Estilo de Inputs y Controles Formulario Premium */
.ag-form-input {
    width: 100%;
    padding: 10px 14px;
    border-radius: 10px;
    border: 1px solid rgba(80, 89, 132, 0.2);
    background: rgba(248, 250, 252, 0.8);
    font-size: 0.88rem;
    color: #0f172a;
    transition: all 0.25s ease-out;
    box-sizing: border-box;
}

.ag-form-input:focus {
    outline: none;
    border-color: #2563eb;
    background: #ffffff;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
}

.ag-form-label {
    font-size: 0.82rem;
    font-weight: 700;
    color: #334155;
    display: block;
    margin-bottom: 6px;
    letter-spacing: 0.2px;
}
</style>

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
                Administración de procesos automáticos, limpiezas de temporales y backups periódicos de medianoche.
            </p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <button onclick="abrirModalTarea()" class="ag-btn-standard ag-btn-primary">
                <i class="ph-bold ph-plus-circle"></i> + Nueva Tarea
            </button>
            <a href="sudoadmin" class="ag-btn-standard ag-btn-secondary">
                <i class="ph-bold ph-arrow-left"></i> Volver al Centro de Mando
            </a>
        </div>
    </div>
</div>

<!-- ALERTAS DE ÉXITO O ERROR DE SESIÓN -->
<?php if (isset($_SESSION['mensaje_admin_exito'])): ?>
    <div style="background: rgba(16,185,129,0.12); color: #047857; border: 1px solid rgba(16,185,129,0.3); padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
        <i class="ph-bold ph-check-circle" style="font-size: 1.2rem;"></i>
        <?= htmlspecialchars($_SESSION['mensaje_admin_exito']) ?>
        <?php unset($_SESSION['mensaje_admin_exito']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['mensaje_admin_error'])): ?>
    <div style="background: rgba(239,68,68,0.12); color: #b91c1c; border: 1px solid rgba(239,68,68,0.3); padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
        <i class="ph-bold ph-warning-circle" style="font-size: 1.2rem;"></i>
        <?= htmlspecialchars($_SESSION['mensaje_admin_error']) ?>
        <?php unset($_SESSION['mensaje_admin_error']); ?>
    </div>
<?php endif; ?>

<!-- PANEL DE DIAGNÓSTICO DEL ENTORNO CRON DEL SISTEMA OPERATIVO -->
<div class="glass-panel mb-2" style="padding: 1.25rem; border-radius: var(--radius-sm); border-left: 4px solid var(--color-secundario); background: rgba(255,255,255,0.95); backdrop-filter: blur(12px);">
    <h4 style="margin: 0 0 0.5rem 0; font-size: 0.95rem; font-weight: 700; color: var(--texto-titulos); display: flex; align-items: center; gap: 8px;">
        <i class="ph-bold ph-linux-logo" style="font-size: 1.2rem; color: var(--color-principal);"></i>
        Estado del Programador del Sistema Operativo
    </h4>
    <p style="margin: 0 0 0.75rem 0; font-size: 0.85rem; color: #475569;">
        <?= htmlspecialchars($diagnostico['detalles']) ?>
    </p>

    <div style="background: #0f172a; color: #38bdf8; padding: 12px 16px; border-radius: 8px; font-family: monospace; font-size: 0.85rem; overflow-x: auto; display: flex; justify-content: space-between; align-items: center; box-shadow: inset 0 2px 4px rgba(0,0,0,0.3);">
        <span><?= htmlspecialchars($diagnostico['comando_sugerido_linux']) ?></span>
        <span style="font-size: 0.75rem; background: rgba(56, 189, 248, 0.15); color: #38bdf8; padding: 3px 10px; border-radius: 6px; font-weight: 700;">Sintaxis Linux Crontab</span>
    </div>
</div>

<!-- TARJETAS DE TAREAS PROGRAMADAS -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 1.5rem;" class="mb-2">
    <?php foreach ($tareas as $id => $tarea): ?>
        <div class="ag-scheduler-card">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                    <span style="font-weight: 800; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px; padding: 4px 10px; border-radius: 6px; <?= $tarea['estado'] === 'activo' ? 'background: rgba(16, 185, 129, 0.12); color: #059669; border: 1px solid rgba(16, 185, 129, 0.25);' : 'background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0;' ?>">
                        <i class="ph-bold <?= $tarea['estado'] === 'activo' ? 'ph-check-circle' : 'ph-pause-circle' ?>"></i> <?= strtoupper($tarea['estado']) ?>
                    </span>
                    <span style="font-family: monospace; font-size: 0.8rem; background: #f8fafc; padding: 4px 10px; border-radius: 6px; border: 1px solid #e2e8f0; color: #0284c7; font-weight: 700; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                        <?= htmlspecialchars($tarea['expresion_cron']) ?>
                    </span>
                </div>

                <h3 style="margin: 0 0 0.4rem 0; font-size: 1.1rem; font-weight: 700; color: #1e293b;">
                    <?= htmlspecialchars($tarea['nombre']) ?>
                </h3>
                <p style="margin: 0; font-size: 0.83rem; color: #64748b; line-height: 1.4;">
                    <?= htmlspecialchars($tarea['descripcion']) ?>
                </p>
                <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 6px; font-family: monospace;">
                    Script ejecutor: <b><?= htmlspecialchars($tarea['script']) ?></b>
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
                        <button type="button" onclick='editarTarea(<?= json_encode($tarea) ?>)' class="ag-btn-icon" title="Editar Expresión Cron">
                            <i class="ph-bold ph-pencil"></i>
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

<!-- MODAL CREAR / EDITAR TAREA PROGRAMADA CON GLASSMORPHISM Y TRANSICIONES CUIDADAS -->
<div id="modalTareaScheduler" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 9999; justify-content: center; align-items: center; padding: 1rem; transition: all 0.3s ease;">
    <div style="background: rgba(255, 255, 255, 0.98); border-radius: 16px; max-width: 520px; width: 100%; padding: 2rem; box-shadow: 0 24px 48px rgba(18, 26, 62, 0.18); border: 1px solid rgba(80, 89, 132, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 id="modalTareaTitulo" style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #121a3e;">
                Programar Tarea / Editar Horario
            </h3>
            <button type="button" onclick="cerrarModalTarea()" style="background: none; border: none; font-size: 1.2rem; color: #64748b; cursor: pointer; padding: 4px;">
                <i class="ph-bold ph-x"></i>
            </button>
        </div>
        
        <form action="guardar-tarea-programada" method="POST">
            <input type="hidden" name="tarea_id" id="form_tarea_id" value="">

            <div style="margin-bottom: 1.1rem;">
                <label class="ag-form-label">Nombre de la Tarea</label>
                <input type="text" name="nombre" id="form_tarea_nombre" class="ag-form-input" placeholder="Ej: Respaldo Semanal..." required>
            </div>

            <div style="margin-bottom: 1.1rem;">
                <label class="ag-form-label">Expresión Cron (Frecuencia de Ejecución)</label>
                <input type="text" name="expresion_cron" id="form_tarea_cron" class="ag-form-input" style="font-family: monospace; font-weight: 700; color: #0284c7;" placeholder="Ej: 0 0 * * *" required>
                <div style="font-size: 0.74rem; color: #64748b; margin-top: 6px; line-height: 1.3;">
                    Sintaxis Cron: <code>minuto hora día-mes mes día-semana</code> (Ej: <code>0 0 * * *</code> = Medianoche).
                </div>
            </div>

            <div style="margin-bottom: 1.1rem;">
                <label class="ag-form-label">Método Script Ejecutor (SchedulerService)</label>
                <select name="script" id="form_tarea_script" class="ag-form-input" style="appearance: auto;" required>
                    <option value="limpiarTemporales">limpiarTemporales (Archivos temporales/caché)</option>
                    <option value="ejecutarBackupAutomatico">ejecutarBackupAutomatico (PostgreSQL Dump .sql.gz)</option>
                    <option value="purgarLogs">purgarLogs (Rotación de auditoría activa)</option>
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label class="ag-form-label">Descripción</label>
                <textarea name="descripcion" id="form_tarea_descripcion" class="ag-form-input" style="height: 70px; resize: vertical;" placeholder="Descripción orientativa del propósito de la tarea..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" onclick="cerrarModalTarea()" class="ag-btn-standard ag-btn-secondary">Cancelar</button>
                <button type="submit" class="ag-btn-standard ag-btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL PERSONALIZADO DE CONFIRMACIÓN DE ACCIONES (ANTIGRAVITY DESIGN SYSTEM) -->
<div id="modalConfirmacionGenerico" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 10000; justify-content: center; align-items: center; padding: 1rem;">
    <div style="background: #ffffff; border-radius: 16px; max-width: 440px; width: 100%; padding: 1.75rem; box-shadow: 0 24px 48px rgba(18, 26, 62, 0.2); border: 1px solid rgba(80, 89, 132, 0.2); text-align: center;">
        <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(239, 68, 68, 0.12); color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 1rem auto;">
            <i class="ph-bold ph-warning"></i>
        </div>
        
        <h3 style="margin: 0 0 0.5rem 0; font-size: 1.2rem; font-weight: 800; color: #121a3e;">
            Confirmación de Seguridad
        </h3>
        
        <p id="modalConfirmacionMensaje" style="margin: 0 0 1.5rem 0; font-size: 0.88rem; color: #64748b; line-height: 1.4;">
            ¿Está seguro de eliminar esta tarea programada?
        </p>

        <div style="display: flex; justify-content: center; gap: 0.75rem;">
            <button type="button" onclick="cerrarModalConfirmacion()" class="ag-btn-standard ag-btn-secondary" style="min-width: 110px;">
                Cancelar
            </button>
            <button type="button" id="btnConfirmarAccionModal" class="ag-btn-standard" style="background: #dc2626 !important; color: white !important; min-width: 110px;">
                Eliminar
            </button>
        </div>
    </div>
</div>

<script>
let formTargetParaEliminar = null;

function abrirModalTarea() {
    document.getElementById('modalTareaTitulo').innerText = 'Crear Nueva Tarea Programada';
    document.getElementById('form_tarea_id').value = '';
    document.getElementById('form_tarea_nombre').value = '';
    document.getElementById('form_tarea_cron').value = '0 0 * * *';
    document.getElementById('form_tarea_descripcion').value = '';
    document.getElementById('modalTareaScheduler').style.display = 'flex';
}

function editarTarea(tarea) {
    document.getElementById('modalTareaTitulo').innerText = 'Editar Frecuencia & Tarea';
    document.getElementById('form_tarea_id').value = tarea.id;
    document.getElementById('form_tarea_nombre').value = tarea.nombre;
    document.getElementById('form_tarea_cron').value = tarea.expresion_cron;
    document.getElementById('form_tarea_script').value = tarea.script;
    document.getElementById('form_tarea_descripcion').value = tarea.descripcion;
    document.getElementById('modalTareaScheduler').style.display = 'flex';
}

function cerrarModalTarea() {
    document.getElementById('modalTareaScheduler').style.display = 'none';
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
