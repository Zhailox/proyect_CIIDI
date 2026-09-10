<div class="repo-internal-header glass-banner floating-element mb-2" style="background: #ffffff !important; border: 1px solid rgba(80, 89, 132, 0.12); padding: 1.5rem 2rem; border-radius: var(--radius-md); box-shadow: 0 4px 20px rgba(18, 26, 62, 0.04);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-terciario); font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 0.3rem;">
                <i class="ph-bold ph-squares-four"></i> GESTIÓN DE SUBSISTEMAS Y RUTAS
            </div>
            <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--color-principal); margin: 0;">
                Gestor de Módulos & Feature Flags
            </h1>
            <p style="margin: 0.3rem 0 0 0; color: var(--texto-silenciado); font-size: 0.9rem;">
                Conmutadores de paquetes y restricción granular de rutas dinámicas por función.
            </p>
        </div>
        <a href="sudoadmin" class="btn btn-outline" style="border-color: var(--color-secundario); color: var(--color-secundario); text-decoration: none; padding: 8px 14px; border-radius: 6px; font-weight: 600; font-size: 0.85rem;">
            <i class="ph-bold ph-arrow-left"></i> Volver al Centro de Mando
        </a>
    </div>
</div>

<!-- TOAST CONTAINER LOCAL -->
<div id="sa-toast-container" class="sa-toast-container"></div>

<div class="modules-grid mb-2">
    <?php foreach($modulosDelSistema as $mod): ?>
        <div class="module-card-v2 glass-card <?= $mod['es_core'] ? 'is-core' : ($mod['estado'] === 'offline' ? 'offline-demo' : '') ?>" style="flex-direction: column; align-items: stretch; gap: 1rem; border-radius: var(--radius-sm);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem;">
                <div class="module-info">
                    <h3 style="color: var(--color-principal); font-weight: 700; font-size: 1.05rem;"><i class="<?= $mod['icono'] ?>" style="color: var(--color-secundario);"></i> <?= htmlspecialchars($mod['nombre']) ?></h3>
                    <p style="color: var(--texto-silenciado); font-size: 0.85rem; margin-top: 4px;"><?= htmlspecialchars($mod['descripcion']) ?></p>
                </div>
                
                <div class="module-toggle-group" style="display: flex; align-items: center; gap: 8px;">
                    <?php if($mod['es_core']): ?>
                        <span class="module-status locked" style="background: rgba(80,89,132,0.15); color: var(--color-secundario); border-radius: 6px; padding: 3px 8px; font-weight: 700; font-size: 0.72rem;">NÚCLEO</span>
                    <?php else: ?>
                        <span class="module-status badge-status-<?= $mod['id'] ?>" style="border-radius: 6px; padding: 3px 8px; font-weight: 700; font-size: 0.72rem; background: <?= $mod['estado'] === 'online' ? 'rgba(16,185,129,0.12)' : 'rgba(239,68,68,0.12)' ?>; color: <?= $mod['estado'] === 'online' ? '#10b981' : '#ef4444' ?>;"><?= strtoupper($mod['estado']) ?></span>
                        <label class="toggle-switch cursor-pointer" title="Alternar Estado del Módulo">
                            <input type="checkbox" 
                                   id="chk-mod-gm-<?= htmlspecialchars($mod['id']) ?>" 
                                   <?= $mod['estado'] === 'online' ? 'checked' : '' ?> 
                                   onchange="toggleModuloAjaxGM('<?= htmlspecialchars($mod['id']) ?>', this)">
                            <span class="toggle-slider" style="border-radius: 6px;"></span>
                        </label>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ACORDEÓN DE RUTAS INTERNAS DEL MÓDULO -->
            <?php if (!empty($mod['rutas'])): ?>
                <details class="routes-accordion" style="border-top: 1px solid rgba(0,0,0,0.06); padding-top: 0.75rem;">
                    <summary style="font-size: 0.82rem; font-weight: 700; color: var(--color-secundario); cursor: pointer; display: flex; align-items: center; justify-content: space-between;">
                        <span><i class="ph ph-tree-structure"></i> Configurar Rutas Internas (<?= count($mod['rutas']) ?>)</span>
                        <i class="ph ph-caret-down"></i>
                    </summary>
                    
                    <div style="margin-top: 0.75rem; display: flex; flex-direction: column; gap: 0.6rem;">
                        <?php foreach ($mod['rutas'] as $r): ?>
                            <div class="route-control-card" style="background: rgba(244,247,251,0.7); border: 1px solid rgba(80,89,132,0.15); border-radius: 6px; padding: 0.65rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                    <div>
                                        <span style="font-weight: 700; font-size: 0.82rem; color: var(--texto-titulos);"><?= htmlspecialchars($r['titulo']) ?></span>
                                        <code style="display: inline-block; background: rgba(80,89,132,0.1); color: var(--color-secundario); font-size: 0.73rem; padding: 2px 6px; border-radius: 4px; margin-left: 6px;">?ruta=<?= htmlspecialchars($r['clave']) ?></code>
                                    </div>
                                    
                                    <div>
                                        <select onchange="toggleRutaAjaxGM('<?= htmlspecialchars($r['clave']) ?>', this.value)" style="font-size: 0.76rem; padding: 4px 8px; border-radius: 6px; border: 1px solid #cbd5e1; font-weight: 600; background: #ffffff; cursor: pointer;">
                                            <option value="online" <?= $r['estado'] === 'online' ? 'selected' : '' ?>>🟢 Activa</option>
                                            <option value="solo_lectura" <?= $r['estado'] === 'solo_lectura' ? 'selected' : '' ?>>🟡 Solo Lectura</option>
                                            <option value="offline" <?= $r['estado'] === 'offline' ? 'selected' : '' ?>>🔴 Deshabilitada</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </details>
            <?php endif; ?>

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
                badge.textContent = nuevoEstado.toUpperCase();
                badge.style.background = nuevoEstado === 'online' ? 'rgba(16,185,129,0.12)' : 'rgba(239,68,68,0.12)';
                badge.style.color = nuevoEstado === 'online' ? '#10b981' : '#ef4444';
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

function toggleRutaAjaxGM(claveRuta, nuevoEstado) {
    const formData = new FormData();
    formData.append('ruta_clave', claveRuta);
    formData.append('nuevo_estado', nuevoEstado);

    fetch('alternar-estado-ruta', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            showSAToast(data.message, 'success');
        } else {
            showSAToast(data.message || 'Error al modificar la ruta', 'error');
        }
    })
    .catch(() => showSAToast('Error de red al modificar ruta', 'error'));
}
</script>