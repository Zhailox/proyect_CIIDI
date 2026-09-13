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

.ag-modules-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.ag-card {
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(80, 89, 132, 0.15) !important;
    border-radius: 14px !important;
    padding: 1.6rem !important;
    box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 1.25rem;
}

.ag-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 42px rgba(18, 26, 62, 0.08);
    border-color: rgba(80, 89, 132, 0.28) !important;
}

.ag-card-icon {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    background: rgba(80, 89, 132, 0.08);
    border: 1px solid rgba(80, 89, 132, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.7rem;
    color: var(--color-secundario);
    flex-shrink: 0;
}

.ag-btn-manage {
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

.ag-btn-manage:hover {
    background: #1d4ed8 !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.32);
    color: #ffffff !important;
}

.ag-badge-core {
    background: rgba(99, 102, 241, 0.1);
    color: #4f46e5;
    border: 1px solid rgba(99, 102, 241, 0.25);
    border-radius: 8px;
    padding: 4px 10px;
    font-weight: 800;
    font-size: 0.72rem;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.ag-badge-online {
    background: rgba(16, 185, 129, 0.1);
    color: #059669;
    border: 1px solid rgba(16, 185, 129, 0.25);
    border-radius: 8px;
    padding: 4px 10px;
    font-weight: 800;
    font-size: 0.72rem;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.ag-badge-offline {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
    border: 1px solid rgba(239, 68, 68, 0.25);
    border-radius: 8px;
    padding: 4px 10px;
    font-weight: 800;
    font-size: 0.72rem;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
</style>

<!-- ENCABEZADO PRINCIPAL GLASSMORPHIC -->
<div class="ag-header-banner">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.2rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-terciario); font-weight: 800; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 0.3rem;">
                <i class="ph-bold ph-squares-four"></i> SUBSISTEMAS DEL SISTEMA INTEGRAL
            </div>
            <h1 style="font-size: 1.85rem; font-weight: 800; color: var(--texto-titulos, #0f172a); margin: 0;">
                Gestor de Módulos
            </h1>
            <p style="margin: 0.3rem 0 0 0; color: var(--texto-silenciado, #64748b); font-size: 0.9rem;">
                Seleccione un módulo para gestionar sus rutas internas, feature flags y configuraciones específicas.
            </p>
        </div>
        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
            <a href="exportar-config-sistema" class="btn btn-outline" style="border-color: #059669; color: #059669; background: #ffffff; text-decoration: none; padding: 8px 14px; border-radius: 8px; font-weight: 700; font-size: 0.83rem; display: inline-flex; align-items: center; gap: 6px;">
                <i class="ph-bold ph-download-simple"></i> Exportar Config System
            </a>

            <button type="button" onclick="document.getElementById('file-import-config').click()" class="btn btn-outline" style="border-color: #7c3aed; color: #7c3aed; background: #ffffff; text-decoration: none; padding: 8px 14px; border-radius: 8px; font-weight: 700; font-size: 0.83rem; display: inline-flex; align-items: center; gap: 6px;">
                <i class="ph-bold ph-upload-simple"></i> Importar Config
            </button>
            <form id="form-import-config" action="importar-config-sistema" method="POST" enctype="multipart/form-data" style="display: none;">
                <input type="file" id="file-import-config" name="config_file" accept=".json" onchange="document.getElementById('form-import-config').submit()">
            </form>

            <a href="sudoadmin" class="btn btn-outline" style="border-color: var(--color-secundario); color: var(--color-secundario); background: #ffffff; text-decoration: none; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; transition: all 0.2s ease;">
                <i class="ph-bold ph-arrow-left"></i> Volver al Centro de Mando
            </a>
        </div>
    </div>
</div>

<!-- TOAST CONTAINER LOCAL -->
<div id="sa-toast-container" class="sa-toast-container"></div>

<!-- GRID DE TARJETAS DE MÓDULOS -->
<div class="ag-modules-grid">
    <?php foreach($modulosDelSistema as $mod): ?>
        <div class="ag-card" id="ag-card-mod-<?= htmlspecialchars($mod['id']) ?>">
            <!-- Parte Superior: Icono e Información del Módulo -->
            <div style="display: flex; gap: 1.1rem; align-items: flex-start;">
                <div class="ag-card-icon">
                    <i class="<?= htmlspecialchars($mod['icono']) ?>"></i>
                </div>
                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.5rem; margin-bottom: 0.3rem;">
                        <h3 style="color: var(--texto-titulos, #0f172a); font-weight: 800; font-size: 1.12rem; margin: 0; line-height: 1.3;">
                            <?= htmlspecialchars($mod['nombre']) ?>
                        </h3>
                    </div>
                    <p style="color: var(--texto-silenciado, #64748b); font-size: 0.86rem; margin: 0; line-height: 1.45;">
                        <?= htmlspecialchars($mod['descripcion']) ?>
                    </p>
                </div>
            </div>

            <!-- Parte Inferior: Estado, Switch y Botón de Gestión -->
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; border-top: 1px solid rgba(80, 89, 132, 0.1); padding-top: 1rem; margin-top: 0.2rem;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <?php if($mod['es_core']): ?>
                        <span class="ag-badge-core"><i class="ph-bold ph-shield-check"></i> CORE</span>
                    <?php else: ?>
                        <span class="badge-status-<?= $mod['id'] ?> <?= $mod['estado'] === 'online' ? 'ag-badge-online' : 'ag-badge-offline' ?>">
                            <i class="ph-bold <?= $mod['estado'] === 'online' ? 'ph-check-circle' : 'ph-x-circle' ?>"></i> <?= strtoupper($mod['estado']) ?>
                        </span>
                        <label class="toggle-switch cursor-pointer" title="Alternar Estado del Módulo">
                            <input type="checkbox" 
                                   id="chk-mod-gm-<?= htmlspecialchars($mod['id']) ?>" 
                                   <?= $mod['estado'] === 'online' ? 'checked' : '' ?> 
                                   onchange="toggleModuloAjaxGM('<?= htmlspecialchars($mod['id']) ?>', this)">
                            <span class="toggle-slider" style="border-radius: 6px;"></span>
                        </label>
                    <?php endif; ?>
                </div>

                <a href="detalle-modulo?id=<?= urlencode($mod['id']) ?>" class="ag-btn-manage" title="Abrir gestor dedicado del módulo">
                    <i class="ph-bold ph-gear-six"></i> Gestionar Módulo
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
function showSAToast(message, type = 'success') {
    const container = document.getElementById('sa-toast-container');
    if (!container) return;
    const toast = document.createElement('div');
    toast.className = `sa-toast ${type}`;
    toast.innerHTML = `<i class="ph-bold ${type === 'success' ? 'ph-check-circle' : 'ph-warning-circle'}"></i><span>${message}</span>`;
    container.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 10);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}

function toggleModuloAjaxGM(moduloId, inputEl) {
    const nuevoEstado = inputEl.checked ? 'online' : 'offline';
    const formData = new FormData();
    formData.append('modulo_id', moduloId);
    formData.append('nuevo_estado', nuevoEstado);

    fetch('alternar-modulo', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            const badge = document.querySelector(`.badge-status-${moduloId}`);
            if (badge) {
                const iconClass = nuevoEstado === 'online' ? 'ph-check-circle' : 'ph-x-circle';
                badge.innerHTML = `<i class="ph-bold ${iconClass}"></i> ${nuevoEstado.toUpperCase()}`;
                badge.className = `badge-status-${moduloId} ${nuevoEstado === 'online' ? 'ag-badge-online' : 'ag-badge-offline'}`;
            }

            // Actualización dinámica del menú lateral (Sidebar)
            if (Array.isArray(data.rutas)) {
                data.rutas.forEach(ruta => {
                    const navItems = document.querySelectorAll(`a[href="${ruta}"]`);
                    navItems.forEach(link => {
                        const parentNav = link.closest('.nav-item, .sub-nav-item, .nav-parent');
                        if (parentNav) {
                            if (nuevoEstado === 'offline') {
                                parentNav.style.transition = 'all 0.3s ease';
                                parentNav.style.opacity = '0';
                                setTimeout(() => { parentNav.style.display = 'none'; }, 300);
                            } else {
                                parentNav.style.display = '';
                                parentNav.style.opacity = '1';
                            }
                        }
                    });
                });
            }

            showSAToast(data.message, 'success');
        } else {
            inputEl.checked = !inputEl.checked;
            showSAToast(data.message || 'Error al modificar módulo', 'error');
        }
    })
    .catch(() => {
        inputEl.checked = !inputEl.checked;
        showSAToast('Error de conexión con el servidor', 'error');
    });
}
</script>