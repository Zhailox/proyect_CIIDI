<div class="sa-hero-header mb-2" style="background: #ffffff !important; border: 1px solid rgba(80, 89, 132, 0.18); padding: 1.5rem 2rem; border-radius: var(--radius-md); box-shadow: 0 4px 20px rgba(18, 26, 62, 0.04);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-terciario) !important; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 0.3rem;">
                <i class="ph-bold ph-sliders"></i> INFRAESTRUCTURA DEL SISTEMA
            </div>
            <h1 style="font-size: 1.8rem; font-weight: 800; color: #121a3e !important; margin: 0;">
                Variables de Entorno y Configuración
            </h1>
            <p style="margin: 0.3rem 0 0 0; color: #64748b !important; font-size: 0.9rem;">
                Ajustes globales del sistema, seguridad, paginación y servidor de correos.
            </p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="sudoadmin" class="btn btn-outline" style="border-color: var(--color-secundario); color: var(--color-secundario) !important; text-decoration: none; padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 0.85rem;">
                <i class="ph-bold ph-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </div>
</div>

<?php if (!empty($mensajeExito)): ?>
    <div style="background: rgba(16,185,129,0.12); color: #047857; border: 1px solid rgba(16,185,129,0.3); padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
        <i class="ph-bold ph-check-circle" style="font-size: 1.2rem;"></i> <?= htmlspecialchars($mensajeExito) ?>
    </div>
<?php endif; ?>

<?php if (!empty($mensajeError)): ?>
    <div style="background: rgba(239,68,68,0.12); color: #b91c1c; border: 1px solid rgba(239,68,68,0.3); padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
        <i class="ph-bold ph-warning-circle" style="font-size: 1.2rem;"></i> <?= htmlspecialchars($mensajeError) ?>
    </div>
<?php endif; ?>

<form action="guardar-configuracion-sistema" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    
    <!-- Navegación de Pestañas (Estilo SuperAdmin) -->
    <div class="sa-tabs-header glass-panel mb-2" style="background: #ffffff; padding: 8px; border-radius: var(--radius-sm); border: 1px solid rgba(80,89,132,0.15);">
        <button type="button" class="sa-tab-btn tab-active" onclick="switchConfigTab('tabPaginacion', this)">
            <i class="ph-bold ph-list-numbers"></i> Paginación
        </button>
        <button type="button" class="sa-tab-btn" onclick="switchConfigTab('tabSeguridad', this)">
            <i class="ph-bold ph-shield-check"></i> Seguridad Base
        </button>
        <button type="button" class="sa-tab-btn" onclick="switchConfigTab('tabSmtp', this)">
            <i class="ph-bold ph-envelope-simple"></i> Servidor SMTP
        </button>
    </div>

    <!-- TAB PAGINACIÓN -->
    <div id="tabPaginacion" class="sa-tab-content active">
        <div class="glass-panel" style="padding: 1.5rem; border-radius: var(--radius-sm);">
            <h4 style="margin: 0 0 0.5rem 0; font-size: 1rem; font-weight: 700; color: var(--texto-titulos); display: flex; align-items: center; gap: 6px;">
                <i class="ph-bold ph-list-numbers" style="color: var(--color-terciario);"></i> Límites de Renderizado en Tablas
            </h4>
            <p style="font-size: 0.85rem; color: var(--texto-silenciado); margin-bottom: 1.25rem;">
                Controla cuántos elementos se cargan por defecto en las tablas para evitar saturación de memoria RAM.
            </p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Usuarios por página (Gestor)</label>
                    <input type="number" name="pag_usuarios" value="<?= (int)($config['paginacion']['usuarios'] ?? 15) ?>" min="5" class="sa-filter-input" style="width: 100%;">
                </div>
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Docentes por página</label>
                    <input type="number" name="pag_docentes" value="<?= (int)($config['paginacion']['docentes'] ?? 15) ?>" min="5" class="sa-filter-input" style="width: 100%;">
                </div>
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Logs de Auditoría por página</label>
                    <input type="number" name="pag_logs" value="<?= (int)($config['paginacion']['logs'] ?? 50) ?>" min="10" class="sa-filter-input" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>

    <!-- TAB SEGURIDAD -->
    <div id="tabSeguridad" class="sa-tab-content" style="display:none;">
        <div class="glass-panel" style="padding: 1.5rem; border-radius: var(--radius-sm);">
            <h4 style="margin: 0 0 0.5rem 0; font-size: 1rem; font-weight: 700; color: var(--texto-titulos); display: flex; align-items: center; gap: 6px;">
                <i class="ph-bold ph-shield-check" style="color: var(--color-terciario);"></i> Reglas del Entorno de Autenticación
            </h4>
            <p style="font-size: 0.85rem; color: var(--texto-silenciado); margin-bottom: 1.25rem;">
                Parámetros de defensa contra fuerza bruta y control de sesiones activas.
            </p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Tiempo de Inactividad (Minutos)</label>
                    <input type="number" name="seg_timeout" value="<?= (int)($config['seguridad']['timeout_minutos'] ?? 120) ?>" min="5" class="sa-filter-input" style="width: 100%;">
                </div>
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Intentos de Login Fallidos</label>
                    <input type="number" name="seg_intentos" value="<?= (int)($config['seguridad']['intentos_login'] ?? 5) ?>" min="1" class="sa-filter-input" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>

    <!-- TAB SMTP -->
    <div id="tabSmtp" class="sa-tab-content" style="display:none;">
        <div class="glass-panel" style="padding: 1.5rem; border-radius: var(--radius-sm);">
            <h4 style="margin: 0 0 0.5rem 0; font-size: 1rem; font-weight: 700; color: var(--texto-titulos); display: flex; align-items: center; gap: 6px;">
                <i class="ph-bold ph-envelope-simple" style="color: var(--color-terciario);"></i> Servidor de Correos (Notificaciones)
            </h4>
            <p style="font-size: 0.85rem; color: var(--texto-silenciado); margin-bottom: 1.25rem;">
                Credenciales del servidor para el envío de notificaciones automáticas y recuperación de contraseñas.
            </p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Servidor Host (SMTP)</label>
                    <input type="text" name="smtp_host" value="<?= htmlspecialchars($config['smtp']['host'] ?? '') ?>" placeholder="smtp.gmail.com" class="sa-filter-input" style="width: 100%;">
                </div>
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Puerto TLS/SSL</label>
                    <input type="number" name="smtp_port" value="<?= (int)($config['smtp']['port'] ?? 587) ?>" class="sa-filter-input" style="width: 100%;">
                </div>
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Usuario de Correo</label>
                    <input type="email" name="smtp_user" value="<?= htmlspecialchars($config['smtp']['user'] ?? '') ?>" class="sa-filter-input" style="width: 100%;">
                </div>
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Contraseña de Aplicación</label>
                    <input type="password" name="smtp_pass" placeholder="Dejar vacío para conservar actual" class="sa-filter-input" style="width: 100%;">
                </div>
                <div style="grid-column: 1 / -1;">
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Email Remitente Público (From)</label>
                    <input type="email" name="smtp_from" value="<?= htmlspecialchars($config['smtp']['from_email'] ?? '') ?>" class="sa-filter-input" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
        <button type="submit" class="btn sa-btn-primary" style="background: var(--color-secundario) !important; color: #ffffff !important; padding: 10px 24px; border-radius: 6px; font-weight: 700; font-size: 0.9rem; cursor: pointer; border: none;">
            <i class="ph-bold ph-floppy-disk"></i> Aplicar Cambios Globales
        </button>
    </div>
</form>
</div>

<script>
function switchConfigTab(tabId, btnElement) {
    // Ocultar contenidos
    const tabs = document.querySelectorAll('.sa-tab-content');
    tabs.forEach(tab => {
        tab.style.display = 'none';
        tab.classList.remove('active');
    });

    // Desactivar botones
    const buttons = document.querySelectorAll('.sa-tab-btn');
    buttons.forEach(button => button.classList.remove('tab-active'));

    // Activar seleccionado
    const selectedTab = document.getElementById(tabId);
    if (selectedTab) {
        selectedTab.style.display = 'block';
        selectedTab.classList.add('active');
    }

    if (btnElement) {
        btnElement.classList.add('tab-active');
    }

    // Guardar en persistencia
    sessionStorage.setItem('gestorConfigTab', tabId);
}

// Carga Inicial
document.addEventListener('DOMContentLoaded', () => {
    const parametros = new URLSearchParams(window.location.search);
    const tabDesdeUrl = parametros.get('tab');
    const tabGuardada = sessionStorage.getItem('gestorConfigTab');
    
    // Por defecto al Paginacion si no hay nada guardado
    const tabId = tabDesdeUrl || tabGuardada || 'tabPaginacion';
    
    const boton = document.querySelector(`.sa-tab-btn[onclick*="'${tabId}'"]`);
    if (boton) {
        switchConfigTab(tabId, boton);
    }
});
</script>