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
            <a href="exportar-config-sistema" class="sa-btn sa-btn-outline">
                <i class="ph-bold ph-download-simple"></i> Exportar Config System
            </a>

            <button type="button" onclick="document.getElementById('file-import-config').click()" class="sa-btn sa-btn-outline">
                <i class="ph-bold ph-upload-simple"></i> Importar Config
            </button>
            <form id="form-import-config" action="importar-config-sistema" method="POST" enctype="multipart/form-data" style="display: none;">
                <input type="file" id="file-import-config" name="config_file" accept=".json" onchange="document.getElementById('form-import-config').submit()">
            </form>

            <a href="sudoadmin" class="sa-btn sa-btn-outline">
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

            // Actualización dinámica del menú lateral (Sidebar) en tiempo real
            const targetNavs = document.querySelectorAll(`.sidebar [data-modulo="${moduloId}"]`);

            if (targetNavs.length > 0) {
                targetNavs.forEach(navEl => {
                    if (nuevoEstado === 'offline') {
                        navEl.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
                        navEl.style.opacity = '0';
                        navEl.style.transform = 'translateX(-10px)';
                        setTimeout(() => {
                            navEl.style.display = 'none';
                            navEl.style.transform = '';
                        }, 250);
                    } else {
                        navEl.style.display = '';
                        navEl.style.opacity = '0';
                        navEl.style.transform = 'translateX(-10px)';
                        void navEl.offsetWidth; // Forzar reflow para animación
                        navEl.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
                        navEl.style.opacity = '1';
                        navEl.style.transform = '';
                    }
                });
            } else if (nuevoEstado === 'online' && Array.isArray(data.menu) && data.menu.length > 0) {
                // Si el elemento no existía en el DOM al cargar la página, se inyecta dinámicamente
                const navContainer = document.querySelector('.sidebar .nav-menu');
                if (navContainer) {
                    data.menu.forEach(item => {
                        const iconoHtml = (item.icono && item.icono.includes('<i')) ? item.icono : `<i class="${item.icono || 'ph ph-app-window'}"></i>`;
                        if (item.tipo === 'link') {
                            const a = document.createElement('a');
                            a.href = item.enlace;
                            a.className = 'nav-item';
                            a.setAttribute('data-modulo', moduloId);
                            a.innerHTML = `<span class="nav-icon">${iconoHtml}</span><span class="nav-text">${item.titulo}</span>`;
                            a.style.opacity = '0';
                            a.style.transform = 'translateX(-10px)';
                            navContainer.appendChild(a);
                            void a.offsetWidth;
                            a.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
                            a.style.opacity = '1';
                            a.style.transform = '';
                        } else if (item.tipo === 'parent') {
                            const div = document.createElement('div');
                            div.className = 'nav-parent';
                            div.setAttribute('data-modulo', moduloId);
                            let subitemsHtml = '';
                            if (Array.isArray(item.subitems)) {
                                item.subitems.forEach(sub => {
                                    subitemsHtml += `<a href="${sub.ruta}" class="sub-nav-item"><span class="nav-text">${sub.titulo}</span></a>`;
                                });
                            }
                            div.innerHTML = `
                                <a href="${item.enlace}" class="nav-item nav-parent-link">
                                    <span class="nav-icon">${iconoHtml}</span>
                                    <span class="nav-text" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                        <span>${item.titulo}</span>
                                        ${subitemsHtml ? '<i class="ph-bold ph-caret-down nav-caret" style="font-size: 0.8rem; transition: transform 0.25s ease; cursor: pointer; padding: 4px;" onclick="toggleSidebarParent(event)"></i>' : ''}
                                    </span>
                                </a>
                                ${subitemsHtml ? `<div class="sub-menu">${subitemsHtml}</div>` : ''}
                            `;
                            div.style.opacity = '0';
                            div.style.transform = 'translateX(-10px)';
                            navContainer.appendChild(div);
                            void div.offsetWidth;
                            div.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
                            div.style.opacity = '1';
                            div.style.transform = '';
                        }
                    });
                }
            } else if (Array.isArray(data.rutas)) {
                // Fallback por enlaces si no se localiza por data-modulo
                data.rutas.forEach(ruta => {
                    const navItems = document.querySelectorAll(`.sidebar a[href="${ruta}"]`);
                    navItems.forEach(link => {
                        const parentNav = link.closest('.nav-parent') || link;
                        if (parentNav) {
                            if (nuevoEstado === 'offline') {
                                parentNav.style.transition = 'all 0.25s ease';
                                parentNav.style.opacity = '0';
                                setTimeout(() => { parentNav.style.display = 'none'; }, 250);
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