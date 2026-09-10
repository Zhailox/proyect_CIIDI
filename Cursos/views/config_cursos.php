<?php
// modules/Cursos/views/config_cursos.php
// Variables: $config_actual (array), $csrf_token (string), $mensaje_exito, $mensaje_error
$c = $config_actual;
?>

<div class="cur-form-page">

    <!-- Breadcrumb -->
    <nav class="cur-breadcrumb">
        <a href="?ruta=cursos"><i class="ph-fill ph-graduation-cap"></i> Catálogo de Cursos</a>
        <i class="ph-bold ph-caret-right"></i>
        <span>Configuración del Módulo</span>
    </nav>

    <!-- Mensajes flash -->
    <?php if ($mensaje_exito): ?>
        <div class="cur-flash cur-flash--ok" style="margin-bottom:1rem;">
            <i class="ph-fill ph-check-circle"></i> <?= htmlspecialchars($mensaje_exito, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>
    <?php if ($mensaje_error): ?>
        <div class="cur-flash cur-flash--err" style="margin-bottom:1rem;">
            <i class="ph-fill ph-warning-circle"></i> <?= htmlspecialchars($mensaje_error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="cur-form-card">

        <!-- Header -->
        <div class="cur-form-header">
            <div class="cur-form-header-left">
                <div class="cur-form-header-icon icon--edit">
                    <i class="ph-fill ph-gear"></i>
                </div>
                <div class="cur-form-header-text">
                    <h2>Configuración del Módulo de Cursos</h2>
                    <p>Ajusta los parámetros de visualización, seguridad e integración. Los cambios se aplican al instante.</p>
                </div>
            </div>
            <a href="?ruta=cursos" class="cur-form-back-btn"><i class="ph-bold ph-arrow-left"></i> Volver</a>
        </div>

        <form method="POST" action="?ruta=cursos-config-guardar" class="cur-form" id="form-config">
            <?= $csrf_token ?>

            <!-- ══ SECCIÓN 1: PAGINACIÓN ══ -->
            <div class="cur-form-section">
                <div class="cur-form-section-title">
                    <span class="cur-form-section-num">01</span>
                    <h3>Paginación del Catálogo</h3>
                </div>
                <div class="cur-field-row">
                    <div class="cur-field-group">
                        <label for="limite_catalogo" class="cur-field-label">
                            Cursos por página (predeterminado)
                        </label>
                        <div class="cur-field-input-wrap">
                            <i class="ph-fill ph-list-numbers cur-field-icon"></i>
                            <input type="number" id="limite_catalogo" name="limite_catalogo"
                                   class="cur-field-input"
                                   value="<?= (int)($c['paginacion']['limite_catalogo'] ?? 9) ?>"
                                   min="1" max="50">
                        </div>
                        <span class="cur-field-info">
                            Opciones de selector disponibles: 
                            <?= implode(', ', $c['paginacion']['opciones_selector'] ?? [6,9,12,18]) ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- ══ SECCIÓN 2: IMÁGENES ══ -->
            <div class="cur-form-section">
                <div class="cur-form-section-title">
                    <span class="cur-form-section-num">02</span>
                    <h3>Gestión de Imágenes</h3>
                </div>

                <div class="cur-field-row">
                    <div class="cur-field-group">
                        <label for="max_size_mb" class="cur-field-label">Tamaño máximo de imagen (MB)</label>
                        <div class="cur-field-input-wrap">
                            <i class="ph-fill ph-database cur-field-icon"></i>
                            <input type="number" id="max_size_mb" name="max_size_mb"
                                   class="cur-field-input"
                                   value="<?= (int)($c['imagenes']['max_size_mb'] ?? 5) ?>"
                                   min="1" max="20">
                        </div>
                        <span class="cur-field-info">
                            Formatos permitidos: <?= implode(', ', $c['imagenes']['extensiones_permitidas'] ?? []) ?>
                        </span>
                    </div>

                    <div class="cur-field-group">
                        <label for="placeholder_url" class="cur-field-label">URL de imagen placeholder</label>
                        <div class="cur-field-input-wrap">
                            <i class="ph-fill ph-image cur-field-icon"></i>
                            <input type="url" id="placeholder_url" name="placeholder_url"
                                   class="cur-field-input"
                                   value="<?= htmlspecialchars($c['imagenes']['placeholder_url'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                   placeholder="https://...">
                        </div>
                    </div>
                </div>

                <div class="cur-field-row" style="gap:2rem; margin-top:0.5rem;">
                    <label style="display:flex; align-items:center; gap:0.6rem; cursor:pointer; font-size:0.95rem; color:var(--texto-comun);">
                        <input type="checkbox" name="convertir_a_webp"
                               <?= !empty($c['imagenes']['convertir_a_webp']) ? 'checked' : '' ?>>
                        <span><strong>Convertir imágenes a WebP</strong> (requiere extensión GD habilitada)</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:0.6rem; cursor:pointer; font-size:0.95rem; color:var(--texto-comun);">
                        <input type="checkbox" name="lazy_load"
                               <?= !empty($c['imagenes']['lazy_load']) ? 'checked' : '' ?>>
                        <span><strong>Carga diferida (lazy load)</strong> de imágenes en el catálogo</span>
                    </label>
                </div>
            </div>

            <!-- ══ SECCIÓN 3: MOODLE ══ -->
            <div class="cur-form-section">
                <div class="cur-form-section-title">
                    <span class="cur-form-section-num">03</span>
                    <h3>Integración con Moodle</h3>
                </div>
                <div class="cur-field-group cur-field-group--full">
                    <label for="url_fallback" class="cur-field-label">
                        URL de destino cuando no se ha configurado Moodle
                        <span class="cur-field-hint">(Ej: canal de YouTube institucional)</span>
                    </label>
                    <div class="cur-field-input-wrap">
                        <i class="ph-fill ph-graduation-cap cur-field-icon"></i>
                        <input type="url" id="url_fallback" name="url_fallback"
                               class="cur-field-input"
                               value="<?= htmlspecialchars($c['moodle']['url_fallback'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                               placeholder="https://www.youtube.com">
                    </div>
                    <div class="cur-moodle-tip">
                        <i class="ph-fill ph-info"></i>
                        <span>Cuando un curso no tiene URL de Moodle configurada, el botón "Ir al Curso" usará esta URL como destino en lugar de mostrar "Próximamente".</span>
                    </div>
                </div>
            </div>

            <!-- ══ SECCIÓN 4: SEGURIDAD ══ -->
            <div class="cur-form-section">
                <div class="cur-form-section-title">
                    <span class="cur-form-section-num">04</span>
                    <h3>Seguridad</h3>
                </div>
                <label style="display:flex; align-items:center; gap:0.6rem; cursor:pointer; font-size:0.95rem; color:var(--texto-comun);">
                    <input type="checkbox" name="csrf_activo"
                           <?= !empty($c['seguridad']['csrf_activo']) ? 'checked' : '' ?>>
                    <span><strong>Protección CSRF activa</strong> — Tokens de seguridad en todos los formularios</span>
                </label>
            </div>

            <!-- ══ SECCIÓN 5: ROLES ══ (solo lectura, informativa) -->
            <div class="cur-form-section">
                <div class="cur-form-section-title">
                    <span class="cur-form-section-num">05</span>
                    <h3>Roles y Privilegios</h3>
                    <p class="cur-form-section-title-hint">Referencia del sistema de niveles actual</p>
                </div>
                <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(260px,1fr)); gap:1rem;">
                    <?php
                    $roles_labels = [
                        'nivel_ver_catalogo'   => ['Ver catálogo de cursos',       'ph-eye'],
                        'nivel_crear_curso'    => ['Crear / editar cursos',         'ph-pencil-simple'],
                        'nivel_eliminar_curso' => ['Eliminar cursos',               'ph-trash'],
                        'nivel_ver_config'     => ['Acceder a Configuración',       'ph-gear'],
                    ];
                    $nivel_nombres = [-1 => 'Público (sin sesión)', 0 => 'Estudiante', 1 => 'Profesor', 2 => 'Bibliotecario', 3 => 'Administrador'];
                    foreach ($roles_labels as $key => [$label, $icon]):
                        $nivel_req = $c['roles'][$key] ?? 0;
                        $nombre    = $nivel_nombres[$nivel_req] ?? "Nivel {$nivel_req}";
                    ?>
                    <div style="background:#f8faff; border:1px solid rgba(80,89,132,0.12); border-radius:var(--radius-sm); padding:0.9rem 1rem; display:flex; align-items:center; gap:0.75rem;">
                        <i class="ph-fill <?= $icon ?>" style="font-size:1.3rem; color:var(--color-terciario); flex-shrink:0;"></i>
                        <div>
                            <span style="font-size:0.78rem; color:var(--texto-silenciado); text-transform:uppercase; letter-spacing:.5px;">Mínimo</span><br>
                            <strong style="font-size:0.9rem; color:var(--texto-titulos);"><?= $label ?></strong><br>
                            <span style="font-size:0.82rem; color:var(--color-secundario);"><?= $nombre ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <p style="margin-top:1rem; font-size:0.82rem; color:var(--texto-silenciado);">
                    <i class="ph-fill ph-info"></i> Los niveles de rol se definen en <code>config_cursos.json</code> y requieren edición directa del archivo para modificarlos.
                </p>
            </div>

            <!-- FOOTER -->
            <div class="cur-form-footer">
                <a href="?ruta=cursos" class="cur-form-btn cur-form-btn--cancel">
                    <i class="ph-bold ph-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="cur-form-btn cur-form-btn--submit" id="btn-guardar">
                    <i class="ph-bold ph-floppy-disk"></i>
                    <span>Guardar Configuración</span>
                </button>
            </div>

        </form>
    </div>
</div>
