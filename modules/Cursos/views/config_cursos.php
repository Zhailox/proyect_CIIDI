<?php
// modules/Cursos/views/config_cursos.php
// Estilo similar al módulo SuperAdmin (Antigravity Glassmorphism)
$c = $config_actual;
$apiExt = $c['api_externa'] ?? [];
$ultimoSync = $apiExt['ultimo_sync'] ?? null;

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/proyect_CIIDI/proyect_CIIDI/public/index.php')), '/');
$endpointRestUrl = $scheme . '://' . $host . $scriptDir . '/?ruta=api-cursos-externo';
?>
<div class="cur-wrapper" style="max-width: 1400px;">
    <div class="cur-header-gestion" style="margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 2.2rem; font-weight: 800; color: var(--cur-dark); margin-bottom: 0.5rem; display:flex; align-items:center; gap:0.5rem;">
                <i class="ph-fill ph-gear" style="color:var(--cur-primary);"></i> Configuración del Módulo
            </h1>
            <p style="color:var(--cur-muted); font-size:1.05rem;">Ajustes globales de paginación, imágenes, Google Forms y sincronización API REST con servidores externos.</p>
        </div>
        <a href="?ruta=cursos" class="cur-btn-secondary" style="border-radius:12px; padding:0.6rem 1.2rem;"><i class="ph-bold ph-arrow-left"></i> Volver al Catálogo</a>
    </div>

    <?php if ($mensaje_exito): ?>
        <div class="cur-flash cur-flash-success" style="border-radius:12px;"><i class="ph-fill ph-check-circle"></i> <?= htmlspecialchars($mensaje_exito) ?></div>
    <?php endif; ?>
    <?php if ($mensaje_error): ?>
        <div class="cur-flash cur-flash-error" style="border-radius:12px;"><i class="ph-fill ph-warning-circle"></i> <?= htmlspecialchars($mensaje_error) ?></div>
    <?php endif; ?>

    <form method="POST" action="?ruta=cursos-config-guardar" id="form-config">
        <?= $csrf_token ?>
        <!-- Nota: La protección CSRF ahora está siempre activa a nivel de código y no se puede desactivar por interfaz. -->
        <input type="hidden" name="csrf_activo" value="1">

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
            
            <!-- TARJETA: Paginación -->
            <div style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 2rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04);">
                <div style="display:flex; align-items:center; gap:0.8rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--cur-border); padding-bottom:1rem;">
                    <div style="width:40px; height:40px; border-radius:10px; background:var(--cur-primary-light); color:var(--cur-primary); display:flex; align-items:center; justify-content:center; font-size:1.3rem;">
                        <i class="ph-fill ph-list-numbers"></i>
                    </div>
                    <h3 style="font-weight:700; color:var(--cur-dark); font-size:1.2rem; margin:0;">Paginación del Catálogo</h3>
                </div>
                
                <div class="cur-field-group">
                    <label for="limite_catalogo" class="cur-field-label">Cursos por página (predeterminado)</label>
                    <div class="cur-field-input-wrap">
                        <i class="ph-bold ph-hash cur-field-icon"></i>
                        <input type="number" id="limite_catalogo" name="limite_catalogo" class="cur-field-input" value="<?= (int)($c['paginacion']['limite_catalogo'] ?? 9) ?>" min="1" max="50" style="border-radius:10px; padding:0.8rem 1rem 0.8rem 2.5rem;">
                    </div>
                    <small style="color:var(--cur-muted); display:block; margin-top:0.5rem;"><i class="ph-bold ph-info"></i> Ajusta cuántos cursos verán los usuarios simultáneamente antes de paginar.</small>
                </div>
            </div>

            <!-- TARJETA: Integración Moodle -->
            <div style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 2rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04);">
                <div style="display:flex; align-items:center; gap:0.8rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--cur-border); padding-bottom:1rem;">
                    <div style="width:40px; height:40px; border-radius:10px; background:#FEF3C7; color:#D97706; display:flex; align-items:center; justify-content:center; font-size:1.3rem;">
                        <i class="ph-fill ph-graduation-cap"></i>
                    </div>
                    <h3 style="font-weight:700; color:var(--cur-dark); font-size:1.2rem; margin:0;">Integración LMS (Moodle)</h3>
                </div>
                
                <div class="cur-field-group">
                    <label for="url_fallback" class="cur-field-label">URL de destino predeterminada (Fallback)</label>
                    <div class="cur-field-input-wrap">
                        <i class="ph-bold ph-link cur-field-icon"></i>
                        <input type="url" id="url_fallback" name="url_fallback" class="cur-field-input" value="<?= htmlspecialchars($c['moodle']['url_fallback'] ?? '') ?>" placeholder="https://ejemplo.com" style="border-radius:10px; padding:0.8rem 1rem 0.8rem 2.5rem;">
                    </div>
                    <small style="color:var(--cur-muted); display:block; margin-top:0.5rem;"><i class="ph-bold ph-info"></i> Si un curso no tiene enlace de Moodle, el botón redirigirá a esta dirección.</small>
                </div>
            </div>
            
            <!-- TARJETA: Gestión de Imágenes -->
            <div style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 2rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04); display: flex; flex-direction: column; grid-column: 1 / 2;">
                <div style="display:flex; align-items:center; gap:0.8rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--cur-border); padding-bottom:1rem;">
                    <div style="width:40px; height:40px; border-radius:10px; background:#DCFCE7; color:#166534; display:flex; align-items:center; justify-content:center; font-size:1.3rem;">
                        <i class="ph-fill ph-image"></i>
                    </div>
                    <h3 style="font-weight:700; color:var(--cur-dark); font-size:1.2rem; margin:0;">Recursos Multimedia</h3>
                </div>
                
                <div style="display:flex; flex-direction:column; gap:1.2rem; margin-bottom: 1.5rem;">
                    <div class="cur-field-group">
                        <label for="max_size_mb" class="cur-field-label">Límite de subida (MB)</label>
                        <div class="cur-field-input-wrap">
                            <i class="ph-bold ph-hard-drives cur-field-icon"></i>
                            <input type="number" id="max_size_mb" name="max_size_mb" class="cur-field-input" value="<?= (int)($c['imagenes']['max_size_mb'] ?? 5) ?>" min="1" max="20" style="border-radius:10px; padding:0.8rem 1rem 0.8rem 2.5rem;">
                        </div>
                    </div>
                    <div class="cur-field-group">
                        <label for="placeholder_url" class="cur-field-label">Imagen Placeholder (URL)</label>
                        <div class="cur-field-input-wrap">
                            <i class="ph-bold ph-image-square cur-field-icon"></i>
                            <input type="url" id="placeholder_url" name="placeholder_url" class="cur-field-input" value="<?= htmlspecialchars($c['imagenes']['placeholder_url'] ?? '') ?>" style="border-radius:10px; padding:0.8rem 1rem 0.8rem 2.5rem;">
                        </div>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:1rem; margin-top:auto;">
                    <label style="display:flex; align-items:center; gap:1rem; cursor:pointer; background:#F8FAFC; padding:1.2rem; border-radius:12px; border:1px solid var(--cur-border); transition:all 0.2s;">
                        <input type="checkbox" name="convertir_a_webp" <?= !empty($c['imagenes']['convertir_a_webp']) ? 'checked' : '' ?> style="width:1.4rem; height:1.4rem; accent-color:var(--cur-primary);">
                        <div>
                            <strong style="display:block; color:var(--cur-dark);">Optimización WebP</strong>
                            <span style="font-size:0.85rem; color:var(--cur-muted);">Comprime drásticamente sin perder calidad visual.</span>
                        </div>
                    </label>
                    <input type="hidden" name="lazy_load" value="1">
                </div>
            </div>

            <!-- TARJETA: Referencia de Roles -->
            <div style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 2rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04); display: flex; flex-direction: column; grid-column: 2 / 3;">
                <div style="display:flex; align-items:center; gap:0.8rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--cur-border); padding-bottom:1rem;">
                    <div style="width:40px; height:40px; border-radius:10px; background:#F1F5F9; color:#475569; display:flex; align-items:center; justify-content:center; font-size:1.3rem;">
                        <i class="ph-fill ph-users"></i>
                    </div>
                    <div>
                        <h3 style="font-weight:700; color:var(--cur-dark); font-size:1.2rem; margin:0;">Control de Accesos Actual</h3>
                        <p style="margin:0; font-size:0.85rem; color:var(--cur-muted);">La configuración de roles solo puede ser modificada a nivel de servidor (json).</p>
                    </div>
                </div>
                
                <div style="display:flex; flex-direction:column; gap:0.8rem; margin-top:auto;">
                    <?php
                    $roles_labels = [
                        'nivel_ver_catalogo'   => ['Ver catálogo', 'ph-eye'],
                        'nivel_crear_curso'    => ['Crear / editar', 'ph-pencil-simple'],
                        'nivel_eliminar_curso' => ['Eliminar cursos', 'ph-trash'],
                        'nivel_ver_config'     => ['Configuración', 'ph-gear'],
                    ];
                    $nivel_nombres = [-1 => 'Público', 0 => 'Estudiante', 1 => 'Profesor', 2 => 'Bibliotecario', 3 => 'Admin'];
                    foreach ($roles_labels as $key => [$label, $icon]):
                        $nivel_req = $c['roles'][$key] ?? 0;
                        $nombre = $nivel_nombres[$nivel_req] ?? "Nivel {$nivel_req}";
                    ?>
                    <div style="background:#F8FAFC; border:1px solid var(--cur-border); border-radius:10px; padding:0.8rem 1.2rem; display:flex; align-items:center; gap:0.8rem;">
                        <i class="ph-fill <?= $icon ?>" style="font-size:1.5rem; color:var(--cur-primary);"></i>
                        <div>
                            <strong style="color:var(--cur-dark); display:block; font-size:0.95rem;"><?= $label ?></strong>
                            <span style="color:var(--cur-muted); font-size:0.8rem;">Mínimo: <?= $nombre ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Nota: Cada curso define su propio enlace de formulario en su ficha de registro/edición -->
            <input type="hidden" name="google_form_habilitado" value="1">
            <input type="hidden" name="google_form_url" value="<?= htmlspecialchars($c['formulario_google']['url_default'] ?? '') ?>">
            <input type="hidden" name="google_form_titulo" value="<?= htmlspecialchars($c['formulario_google']['titulo'] ?? 'Inscripción Oficial de Curso CIIDI') ?>">
            <input type="hidden" name="google_form_modo" value="embebido">

            <!-- TARJETA: API REST y Sincronización con Servidor Externo (MySQL / Portal Remoto) -->
            <div style="background: rgba(255, 255, 255, 0.98); border: 1.5px solid rgba(16, 185, 129, 0.3); border-radius: 16px; padding: 2rem; box-shadow: 0 12px 32px rgba(18, 26, 62, 0.06); grid-column: 1 / -1;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 1.5rem; border-bottom: 1px solid var(--cur-border); padding-bottom:1rem; flex-wrap:wrap; gap:1rem;">
                    <div style="display:flex; align-items:center; gap:0.8rem;">
                        <div style="width:44px; height:44px; border-radius:12px; background:#D1FAE5; color:#059669; display:flex; align-items:center; justify-content:center; font-size:1.4rem;">
                            <i class="ph-fill ph-cloud-arrow-up"></i>
                        </div>
                        <div>
                            <h3 style="font-weight:800; color:var(--cur-dark); font-size:1.25rem; margin:0;">API REST y Sincronización Híbrida con Servidor Externo (MySQL)</h3>
                            <p style="margin:0; font-size:0.88rem; color:var(--cur-muted);">Expone el catálogo de Cursos y el Directorio de Investigadores vía JSON REST y actualiza automáticamente la base de datos MySQL del portal externo.</p>
                        </div>
                    </div>
                    <div style="display:flex; gap:0.8rem; flex-wrap:wrap;">
                        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; background:#ECFDF5; padding:0.5rem 1rem; border-radius:30px; border:1px solid #A7F3D0;">
                            <input type="checkbox" name="api_externa_habilitada" <?= (!isset($apiExt['habilitada']) || !empty($apiExt['habilitada'])) ? 'checked' : '' ?> style="width:1.15rem; height:1.15rem; accent-color:#059669;">
                            <span style="font-weight:700; font-size:0.85rem; color:#065F46;">API REST Activa</span>
                        </label>
                        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; background:#EFF6FF; padding:0.5rem 1rem; border-radius:30px; border:1px solid #BFDBFE;">
                            <input type="checkbox" name="api_externa_auto_push" <?= (!isset($apiExt['auto_push']) || !empty($apiExt['auto_push'])) ? 'checked' : '' ?> style="width:1.15rem; height:1.15rem; accent-color:#2563EB;">
                            <span style="font-weight:700; font-size:0.85rem; color:#1E40AF;">Auto-Push al Crear/Editar</span>
                        </label>
                    </div>
                </div>

                <!-- Endpoint de Lectura (Pull) -->
                <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px; padding:1.2rem; margin-bottom:1.5rem;">
                    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.8rem; margin-bottom:0.6rem;">
                        <strong style="color:#1E293B; font-size:0.92rem; display:flex; align-items:center; gap:0.5rem;">
                            <i class="ph-bold ph-plugs-connected" style="color:#059669;"></i> Endpoint Público API REST del CIIDI (Consumo GET desde el Servidor Externo):
                        </strong>
                        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                            <a href="?ruta=api-cursos-externo&recurso=todo" target="_blank" style="background:#fff; color:#059669; border:1px solid #A7F3D0; padding:0.35rem 0.8rem; border-radius:8px; font-size:0.8rem; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:0.35rem;">
                                <i class="ph-bold ph-file-code"></i> Ver JSON Completo
                            </a>
                            <a href="?ruta=api-cursos-externo&recurso=cursos" target="_blank" style="background:#fff; color:#4F46E5; border:1px solid #C7D2FE; padding:0.35rem 0.8rem; border-radius:8px; font-size:0.8rem; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:0.35rem;">
                                <i class="ph-bold ph-graduation-cap"></i> Solo Cursos
                            </a>
                            <a href="?ruta=api-cursos-externo&recurso=investigadores" target="_blank" style="background:#fff; color:#0284C7; border:1px solid #BAE6FD; padding:0.35rem 0.8rem; border-radius:8px; font-size:0.8rem; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:0.35rem;">
                                <i class="ph-bold ph-users-three"></i> Solo Investigadores
                            </a>
                        </div>
                    </div>
                    <div style="display:flex; gap:0.6rem; align-items:center;">
                        <input type="text" readonly id="ciidi-api-endpoint-input" value="<?= htmlspecialchars($endpointRestUrl) ?>" style="flex:1; background:#fff; border:1px solid #CBD5E1; border-radius:8px; padding:0.65rem 0.9rem; font-family:monospace; font-size:0.88rem; color:#0F172A;">
                        <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('ciidi-api-endpoint-input').value); this.innerHTML='<i class=\'ph-bold ph-check\'></i> Copiado';" style="background:#1E293B; color:#fff; border:none; border-radius:8px; padding:0.65rem 1.1rem; font-weight:700; font-size:0.85rem; cursor:pointer; display:inline-flex; align-items:center; gap:0.4rem;">
                            <i class="ph-bold ph-copy"></i> Copiar URL
                        </button>
                    </div>
                </div>

                <!-- Configuración de Push hacia el Servidor Externo MySQL -->
                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:1.5rem;">
                    <div class="cur-field-group">
                        <label for="api_externa_webhook_url" class="cur-field-label">URL del Receptor en el Servidor Externo (`api_sync.php`)</label>
                        <div class="cur-field-input-wrap">
                            <i class="ph-bold ph-globe-hemisphere-west cur-field-icon"></i>
                            <input type="url" id="api_externa_webhook_url" name="api_externa_webhook_url" class="cur-field-input" value="<?= htmlspecialchars($apiExt['webhook_url'] ?? 'https://ciidi.iutetvirtual.org.ve/api_sync.php') ?>" placeholder="https://ciidi.iutetvirtual.org.ve/api_sync.php" style="border-radius:10px; padding:0.8rem 1rem 0.8rem 2.5rem;">
                        </div>
                        <small style="color:var(--cur-muted); display:block; margin-top:0.4rem;"><i class="ph-bold ph-info"></i> Dirección a la que el CIIDI enviará automáticamente los cursos e investigadores actualizados para guardarlos en MySQL.</small>
                    </div>

                    <div class="cur-field-group">
                        <label for="api_externa_api_key" class="cur-field-label">Clave Secreta de Sincronización (`SYNC_API_KEY`)</label>
                        <div class="cur-field-input-wrap">
                            <i class="ph-bold ph-key cur-field-icon"></i>
                            <input type="text" id="api_externa_api_key" name="api_externa_api_key" class="cur-field-input" value="<?= htmlspecialchars($apiExt['api_key'] ?? 'ciidi_sync_secret_2026') ?>" placeholder="ciidi_sync_secret_2026" style="border-radius:10px; padding:0.8rem 1rem 0.8rem 2.5rem; font-family:monospace;">
                        </div>
                        <small style="color:var(--cur-muted); display:block; margin-top:0.4rem;"><i class="ph-bold ph-shield-check"></i> Firma criptográfica HMAC-SHA256 compartida entre el CIIDI y el `config.php` del servidor externo.</small>
                    </div>
                </div>

                <!-- Barra de Estado de Sincronización y Botón de Push Inmediato -->
                <div style="margin-top:1.5rem; background:#F0FDF4; border:1px solid #BBF7D0; border-radius:12px; padding:1.1rem 1.4rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
                    <div style="display:flex; align-items:center; gap:0.8rem;">
                        <i class="ph-fill <?= (!empty($ultimoSync['ok'])) ? 'ph-check-circle' : 'ph-arrows-clockwise' ?>" style="font-size:1.6rem; color:<?= (!empty($ultimoSync['ok'])) ? '#059669' : '#0284C7' ?>;"></i>
                        <div>
                            <?php if (is_array($ultimoSync)): ?>
                                <strong style="color:#064E3B; font-size:0.92rem; display:block;">
                                    Última sincronización: <?= htmlspecialchars($ultimoSync['fecha'] ?? '') ?>
                                    (<?= !empty($ultimoSync['ok']) ? 'Exitosa' : 'Con aviso' ?> — <?= (int)($ultimoSync['cursos_enviados'] ?? 0) ?> cursos y <?= (int)($ultimoSync['inv_enviados'] ?? 0) ?> investigadores)
                                </strong>
                                <span style="color:#047857; font-size:0.82rem;"><?= htmlspecialchars($ultimoSync['mensaje'] ?? '') ?></span>
                            <?php else: ?>
                                <strong style="color:#064E3B; font-size:0.92rem; display:block;">Sincronización lista para ejecutarse</strong>
                                <span style="color:#047857; font-size:0.82rem;">Pulsa el botón para enviar ahora mismo todos los cursos e investigadores al servidor externo MySQL.</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div style="display:flex; gap:0.7rem; flex-wrap:wrap;">
                        <button type="button" id="btn-sync-externo-now" onclick="ejecutarSyncExternoAhora()" style="background:#059669; color:#fff; border:none; padding:0.7rem 1.3rem; border-radius:10px; font-weight:700; font-size:0.9rem; cursor:pointer; display:inline-flex; align-items:center; gap:0.5rem; box-shadow:0 4px 12px rgba(5,150,105,0.25);">
                            <i class="ph-bold ph-arrows-clockwise"></i> Sincronizar Ahora con Servidor Externo
                        </button>
                    </div>
                </div>
                <div id="sync-externo-feedback" style="display:none; margin-top:0.8rem; padding:0.85rem 1.1rem; border-radius:10px; font-size:0.9rem; font-weight:600;"></div>
            </div>
        </div>

        <div style="display:flex; justify-content:flex-end; padding-top:1rem; padding-bottom:3rem;">
            <!-- BOTÓN GUARDAR HERMOSO -->
            <button type="submit" style="
                background: linear-gradient(135deg, rgba(80,89,132,0.95) 0%, rgba(112,144,203,0.9) 100%); 
                color: white; 
                border: none; 
                padding: 1rem 2.5rem; 
                border-radius: 50px; 
                font-weight: 700; 
                font-size: 1.15rem; 
                display:flex; 
                align-items:center; 
                gap: 0.8rem;
                box-shadow: 0 10px 25px rgba(80, 89, 132, 0.35);
                cursor: pointer;
                transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s;
            " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 15px 35px rgba(80, 89, 132, 0.45)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 25px rgba(80, 89, 132, 0.35)';">
                <i class="ph-bold ph-sparkle" style="font-size: 1.4rem;"></i> Aplicar y Guardar Cambios
            </button>
        </div>
    </form>
</div>

<script>
function ejecutarSyncExternoAhora() {
    const btn = document.getElementById('btn-sync-externo-now');
    const box = document.getElementById('sync-externo-feedback');
    const urlInput = document.getElementById('api_externa_webhook_url');
    const keyInput = document.getElementById('api_externa_api_key');

    btn.disabled = true;
    btn.innerHTML = '<i class="ph-bold ph-spinner ph-spin"></i> Sincronizando con Servidor Externo...';
    box.style.display = 'block';
    box.style.background = '#EFF6FF';
    box.style.color = '#1E40AF';
    box.style.border = '1px solid #BFDBFE';
    box.textContent = 'Enviando catálogo de cursos y directorio de investigadores al servidor externo...';

    const fd = new FormData();
    fd.append('ajax', '1');
    fd.append('webhook_url', urlInput ? urlInput.value : '');
    fd.append('api_key', keyInput ? keyInput.value : '');

    fetch('?ruta=cursos-sincronizar-externo', {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="ph-bold ph-arrows-clockwise"></i> Sincronizar Ahora con Servidor Externo';
        if (data && data.ok) {
            box.style.background = '#DCFCE7';
            box.style.color = '#166534';
            box.style.border = '1px solid #86EFAC';
            box.innerHTML = '<i class="ph-fill ph-check-circle"></i> ¡Sincronización completada! Se enviaron <strong>' + data.cursos_enviados + ' cursos</strong> y <strong>' + data.inv_enviados + ' investigadores</strong> al servidor externo. (' + (data.mensaje || '') + ')';
        } else {
            box.style.background = '#FEF2F2';
            box.style.color = '#991B1B';
            box.style.border = '1px solid #FECACA';
            box.innerHTML = '<i class="ph-fill ph-warning-circle"></i> ' + ((data && data.mensaje) ? data.mensaje : 'No se pudo conectar con el servidor externo.');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="ph-bold ph-arrows-clockwise"></i> Sincronizar Ahora con Servidor Externo';
        box.style.background = '#FEF2F2';
        box.style.color = '#991B1B';
        box.style.border = '1px solid #FECACA';
        box.textContent = 'Error de red al intentar sincronizar: ' + err.message;
    });
}
</script>
