<?php
// modules/Cursos/views/config_cursos.php
// Estilo similar al módulo SuperAdmin (Antigravity Glassmorphism)
$c = $config_actual;
?>
<div class="cur-wrapper" style="max-width: 1400px;">
    <div class="cur-header-gestion" style="margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 2.2rem; font-weight: 800; color: var(--cur-dark); margin-bottom: 0.5rem; display:flex; align-items:center; gap:0.5rem;">
                <i class="ph-fill ph-gear" style="color:var(--cur-primary);"></i> Configuración del Módulo
            </h1>
            <p style="color:var(--cur-muted); font-size:1.05rem;">Ajustes globales de paginación, imágenes e integraciones institucionales.</p>
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

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            
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
            <div style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 2rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04); display: flex; flex-direction: column;">
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
            <div style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 2rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04); display: flex; flex-direction: column;">
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
        </div>

        <div style="display:flex; justify-content:flex-end; padding-top:1rem; padding-bottom:3rem;">
            <!-- BOTÓN GUARDAR HERMOSO -->
            <button type="submit" style="
                background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%); 
                color: white; 
                border: none; 
                padding: 1rem 2.5rem; 
                border-radius: 50px; 
                font-weight: 700; 
                font-size: 1.15rem; 
                display:flex; 
                align-items:center; 
                gap: 0.8rem;
                box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
                cursor: pointer;
                transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s;
            " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 15px 35px rgba(37, 99, 235, 0.5)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 25px rgba(37, 99, 235, 0.4)';">
                <i class="ph-bold ph-sparkle" style="font-size: 1.4rem;"></i> Aplicar y Guardar Cambios
            </button>
        </div>
    </form>
</div>