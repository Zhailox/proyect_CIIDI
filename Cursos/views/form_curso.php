<?php
// modules/Cursos/views/form_curso.php
// Variables: $curso, $meta, $docentes, $modo, $titulo_form, $error, $csrf_token, $config_vista

$es_editar  = ($modo === 'editar');
$accion_url = $es_editar ? '?ruta=cursos-procesar-editar' : '?ruta=cursos-procesar-crear';
$cfg        = $config_vista ?? [];
$img_max_mb = $cfg['imagenes']['max_size_mb'] ?? 5;
$img_exts   = implode(', ', $cfg['imagenes']['extensiones_permitidas'] ?? ['jpg','jpeg','png','webp']);
$lazy_load  = !empty($cfg['imagenes']['lazy_load']) ? 'lazy' : 'eager';

// Campos de la tabla cursos
$f_id      = $curso['id']       ?? '';
$f_titulo  = htmlspecialchars($curso['titulo']          ?? '');
$f_desc    = htmlspecialchars($curso['descripcion']     ?? '');
$f_img     = htmlspecialchars($curso['imagen_portada']  ?? '');
$f_estado  = $curso['estado']                           ?? 'borrador';
$f_nota    = $curso['nota_minima_aprobacion']           ?? '70.00';
$f_docente = $curso['id_docente']                       ?? '';

// Metadatos JSON
$m_moodle   = htmlspecialchars($meta['url_moodle']        ?? '');
$m_video    = htmlspecialchars($meta['url_video_preview'] ?? '');
$m_modal    = $meta['modalidad']    ?? 'Virtual';
$m_nivel    = $meta['nivel']        ?? 'Básico';
$m_duracion = htmlspecialchars($meta['duracion'] ?? '');
$m_cupo     = (int)($meta['cupo_maximo'] ?? 0);
?>

<div class="cur-form-page">

    <!-- Breadcrumb -->
    <nav class="cur-breadcrumb">
        <a href="?ruta=cursos"><i class="ph-fill ph-graduation-cap"></i> Catálogo de Cursos</a>
        <i class="ph-bold ph-caret-right"></i>
        <span><?= htmlspecialchars($titulo_form) ?></span>
    </nav>

    <!-- Card principal -->
    <div class="cur-form-card">

        <!-- Header -->
        <div class="cur-form-header">
            <div class="cur-form-header-left">
                <div class="cur-form-header-icon <?= $es_editar ? 'icon--edit' : 'icon--create' ?>">
                    <i class="ph-fill <?= $es_editar ? 'ph-pencil-simple' : 'ph-plus-circle' ?>"></i>
                </div>
                <div class="cur-form-header-text">
                    <h2><?= htmlspecialchars($titulo_form) ?></h2>
                    <p><?= $es_editar
                        ? 'Modifica los datos del curso y guarda los cambios.'
                        : 'Completa todos los campos para publicitar el curso en Moodle.'
                    ?></p>
                </div>
            </div>
            <a href="?ruta=cursos" class="cur-form-back-btn">
                <i class="ph-bold ph-arrow-left"></i> Volver
            </a>
        </div>

        <!-- Error de validación -->
        <?php if (!empty($error)): ?>
        <div class="cur-form-alert cur-form-alert--error">
            <i class="ph-fill ph-warning-circle"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
        <?php endif; ?>

        <!-- FORMULARIO -->
        <form
            method="POST"
            action="<?= $accion_url ?>"
            class="cur-form"
            id="form-curso"
            enctype="multipart/form-data"
            novalidate
        >
            <!-- CSRF Token -->
            <?= $csrf_token ?? '' ?>

            <?php if ($es_editar): ?>
                <input type="hidden" name="id" value="<?= (int)$f_id ?>">
            <?php endif; ?>

            <!-- ════ SECCIÓN 1: Información Principal ════ -->
            <div class="cur-form-section">
                <div class="cur-form-section-title">
                    <span class="cur-form-section-num">01</span>
                    <h3>Información Principal</h3>
                </div>

                <!-- Título -->
                <div class="cur-field-group cur-field-group--full">
                    <label for="titulo" class="cur-field-label">
                        Título del Curso <span class="cur-field-required">*</span>
                    </label>
                    <div class="cur-field-input-wrap">
                        <i class="ph-fill ph-text-aa cur-field-icon"></i>
                        <input
                            type="text" id="titulo" name="titulo"
                            class="cur-field-input"
                            value="<?= $f_titulo ?>"
                            placeholder="Ej: Fundamentos de Inteligencia Artificial Aplicada"
                            maxlength="255" required autocomplete="off"
                        >
                    </div>
                </div>

                <!-- Docente + Estado -->
                <div class="cur-field-row">
                    <div class="cur-field-group">
                        <label for="id_docente" class="cur-field-label">
                            Docente Responsable <span class="cur-field-required">*</span>
                        </label>
                        <div class="cur-field-input-wrap">
                            <i class="ph-fill ph-chalkboard-teacher cur-field-icon"></i>
                            <select id="id_docente" name="id_docente" class="cur-field-select" required>
                                <option value="">— Seleccionar docente —</option>
                                <?php foreach ($docentes as $doc): ?>
                                <option
                                    value="<?= (int)$doc['id'] ?>"
                                    <?= ($f_docente == $doc['id']) ? 'selected' : '' ?>
                                >
                                    <?= htmlspecialchars($doc['nombre_completo']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="cur-field-group">
                        <label for="estado" class="cur-field-label">Estado de Publicación</label>
                        <div class="cur-field-input-wrap">
                            <i class="ph-fill ph-toggle-right cur-field-icon"></i>
                            <select id="estado" name="estado" class="cur-field-select">
                                <option value="borrador"  <?= $f_estado === 'borrador'  ? 'selected' : '' ?>>Borrador</option>
                                <option value="publicado" <?= $f_estado === 'publicado' ? 'selected' : '' ?>>Publicado</option>
                                <option value="archivado" <?= $f_estado === 'archivado' ? 'selected' : '' ?>>Archivado</option>
                            </select>
                        </div>
                        <div class="cur-estado-pills" id="estado-pills">
                            <span class="cur-estado-pill cur-estado-pill--borrador  <?= $f_estado === 'borrador'  ? 'active' : '' ?>">✏️ Borrador</span>
                            <span class="cur-estado-pill cur-estado-pill--publicado <?= $f_estado === 'publicado' ? 'active' : '' ?>">✅ Publicado</span>
                            <span class="cur-estado-pill cur-estado-pill--archivado <?= $f_estado === 'archivado' ? 'active' : '' ?>">📦 Archivado</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ════ SECCIÓN 2: Descripción ════ -->
            <div class="cur-form-section">
                <div class="cur-form-section-title">
                    <span class="cur-form-section-num">02</span>
                    <h3>Descripción del Curso</h3>
                </div>

                <div class="cur-field-group cur-field-group--full">
                    <label for="descripcion" class="cur-field-label">Descripción completa</label>
                    <textarea
                        id="descripcion" name="descripcion"
                        class="cur-field-textarea" rows="6"
                        placeholder="Describe los objetivos, contenidos, audiencia objetivo y beneficios del curso. Esta información se mostrará en el catálogo y en la página de detalle…"
                    ><?= $f_desc ?></textarea>
                    <div class="cur-field-counter">
                        <span id="desc-count">0</span> caracteres
                    </div>
                </div>
            </div>

            <!-- ════ SECCIÓN 3: Integración con Moodle ════ -->
            <div class="cur-form-section">
                <div class="cur-form-section-title">
                    <span class="cur-form-section-num">03</span>
                    <h3>Integración con Moodle</h3>
                    <p class="cur-form-section-title-hint">Enlaza el curso con la plataforma</p>
                </div>

                <!-- Tip informativo -->
                <div class="cur-moodle-tip">
                    <i class="ph-fill ph-info"></i>
                    <span>
                        <strong>¿Cómo obtener la URL?</strong> Entra a Moodle, navega al curso que deseas enlazar
                        y copia la URL de la barra de direcciones (Ej: <em>https://moodle.universidad.edu/course/view.php?id=123</em>).
                    </span>
                </div>

                <!-- URL Moodle + URL Video -->
                <div class="cur-field-row">
                    <div class="cur-field-group cur-field-group--full">
                        <label for="url_moodle" class="cur-field-label">
                            URL del Curso en Moodle
                            <span class="cur-field-hint">(Recomendado para cursos publicados)</span>
                        </label>
                        <div class="cur-field-input-wrap">
                            <i class="ph-fill ph-graduation-cap cur-field-icon"></i>
                            <input
                                type="url" id="url_moodle" name="url_moodle"
                                class="cur-field-input"
                                value="<?= $m_moodle ?>"
                                placeholder="https://moodle.universidad.edu/course/view.php?id=…"
                            >
                        </div>
                    </div>

                    <div class="cur-field-group cur-field-group--full">
                        <label for="url_video_preview" class="cur-field-label">
                            URL de Video de Vista Previa
                            <span class="cur-field-hint">(YouTube o Vimeo — Opcional)</span>
                        </label>
                        <div class="cur-field-input-wrap">
                            <i class="ph-fill ph-play-circle cur-field-icon"></i>
                            <input
                                type="url" id="url_video_preview" name="url_video_preview"
                                class="cur-field-input"
                                value="<?= $m_video ?>"
                                placeholder="https://www.youtube.com/watch?v=…"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- ════ SECCIÓN 4: Detalles del Curso ════ -->
            <div class="cur-form-section">
                <div class="cur-form-section-title">
                    <span class="cur-form-section-num">04</span>
                    <h3>Detalles del Curso</h3>
                </div>

                <div class="cur-field-row">
                    <!-- Modalidad -->
                    <div class="cur-field-group">
                        <label for="modalidad" class="cur-field-label">Modalidad</label>
                        <div class="cur-field-input-wrap">
                            <i class="ph-fill ph-monitor cur-field-icon"></i>
                            <select id="modalidad" name="modalidad" class="cur-field-select">
                                <option value="Virtual"    <?= $m_modal === 'Virtual'    ? 'selected' : '' ?>>🖥️ Virtual</option>
                                <option value="Presencial" <?= $m_modal === 'Presencial' ? 'selected' : '' ?>>📍 Presencial</option>
                                <option value="Híbrido"    <?= $m_modal === 'Híbrido'    ? 'selected' : '' ?>>🔄 Híbrido</option>
                            </select>
                        </div>
                    </div>

                    <!-- Nivel -->
                    <div class="cur-field-group">
                        <label for="nivel" class="cur-field-label">Nivel</label>
                        <div class="cur-field-input-wrap">
                            <i class="ph-fill ph-chart-bar-horizontal cur-field-icon"></i>
                            <select id="nivel" name="nivel" class="cur-field-select">
                                <option value="Básico"      <?= $m_nivel === 'Básico'      ? 'selected' : '' ?>>🟢 Básico</option>
                                <option value="Intermedio"  <?= $m_nivel === 'Intermedio'  ? 'selected' : '' ?>>🟡 Intermedio</option>
                                <option value="Avanzado"    <?= $m_nivel === 'Avanzado'    ? 'selected' : '' ?>>🔴 Avanzado</option>
                                <option value="Todos los niveles" <?= $m_nivel === 'Todos los niveles' ? 'selected' : '' ?>>🌐 Todos los niveles</option>
                            </select>
                        </div>
                    </div>

                    <!-- Duración -->
                    <div class="cur-field-group">
                        <label for="duracion" class="cur-field-label">
                            Duración
                            <span class="cur-field-hint">(Texto libre)</span>
                        </label>
                        <div class="cur-field-input-wrap">
                            <i class="ph-fill ph-clock cur-field-icon"></i>
                            <input
                                type="text" id="duracion" name="duracion"
                                class="cur-field-input"
                                value="<?= $m_duracion ?>"
                                placeholder="Ej: 40 horas, 4 semanas…"
                                maxlength="80"
                            >
                        </div>
                    </div>

                    <!-- Cupo Máximo -->
                    <div class="cur-field-group">
                        <label for="cupo_maximo" class="cur-field-label">
                            Cupo Máximo
                            <span class="cur-field-hint">(0 = sin límite)</span>
                        </label>
                        <div class="cur-field-input-wrap">
                            <i class="ph-fill ph-users cur-field-icon"></i>
                            <input
                                type="number" id="cupo_maximo" name="cupo_maximo"
                                class="cur-field-input"
                                value="<?= $m_cupo ?>"
                                min="0" max="9999" step="1"
                                placeholder="30"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- ════ SECCIÓN 5: Imagen y Configuración ════ -->
            <div class="cur-form-section">
                <div class="cur-form-section-title">
                    <span class="cur-form-section-num">05</span>
                    <h3>Imagen y Evaluación</h3>
                </div>

                <div class="cur-field-row">
                    <!-- Imagen de portada — opción URL o archivo -->
                    <div class="cur-field-group cur-field-group--full">
                        <label class="cur-field-label">
                            Imagen de Portada
                            <span class="cur-field-hint">Elige URL externa <em>o</em> sube un archivo (el archivo tiene prioridad)</span>
                        </label>

                        <!-- Opción A: URL -->
                        <div class="cur-field-input-wrap" style="margin-bottom: 0.6rem;">
                            <i class="ph-fill ph-link cur-field-icon"></i>
                            <input
                                type="url" id="imagen_portada" name="imagen_portada"
                                class="cur-field-input"
                                value="<?= $f_img ?>"
                                placeholder="https://…/portada-curso.jpg"
                                oninput="actualizarPreview(this.value)"
                            >
                        </div>

                        <!-- Opción B: Archivo -->
                        <div class="cur-field-input-wrap">
                            <i class="ph-fill ph-upload cur-field-icon"></i>
                            <input
                                type="file"
                                id="imagen_portada_file"
                                name="imagen_portada_file"
                                class="cur-field-input"
                                style="padding: 0.5rem 0.75rem;"
                                accept="image/jpeg,image/png,image/gif,image/webp"
                                onchange="previsualizarArchivo(this)"
                            >
                        </div>
                        <span class="cur-field-info">
                            <i class="ph-fill ph-info"></i>
                            Formatos: <?= htmlspecialchars($img_exts, ENT_QUOTES, 'UTF-8') ?> — Máx. <?= $img_max_mb ?> MB.
                            <?php if (!empty($cfg['imagenes']['convertir_a_webp'])): ?>
                                Las imágenes se convierten automáticamente a <strong>WebP</strong>.
                            <?php endif; ?>
                        </span>

                        <!-- Preview de imagen -->
                        <div class="cur-img-preview-box" id="img-preview-box">
                            <div class="cur-img-preview-inner" id="img-preview"
                                 style="background-image: url('<?= $f_img ?>')">
                                <?php if (empty($f_img)): ?>
                                <div class="cur-img-placeholder">
                                    <i class="ph-fill ph-image"></i>
                                    <span>Vista previa de la portada</span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>


                    <!-- Nota mínima de aprobación -->
                    <div class="cur-field-group cur-field-group--full">
                        <label for="nota_minima" class="cur-field-label">
                            Nota Mínima de Aprobación
                        </label>
                        <div class="cur-field-input-wrap">
                            <i class="ph-fill ph-medal cur-field-icon"></i>
                            <input
                                type="number" id="nota_minima" name="nota_minima_aprobacion"
                                class="cur-field-input"
                                value="<?= htmlspecialchars((string)$f_nota) ?>"
                                min="0" max="100" step="0.01" placeholder="70.00"
                            >
                        </div>
                        <span class="cur-field-info">Escala de 0 a 100 puntos</span>

                        <!-- Indicador visual -->
                        <div class="cur-nota-indicator" id="nota-indicator">
                            <div class="cur-nota-bar">
                                <div class="cur-nota-fill" id="nota-fill" style="width: <?= min(100, (float)$f_nota) ?>%"></div>
                            </div>
                            <span class="cur-nota-val" id="nota-val"><?= number_format((float)$f_nota, 1) ?>%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer: acciones -->
            <div class="cur-form-footer">
                <a href="?ruta=cursos" class="cur-form-btn cur-form-btn--cancel">
                    <i class="ph-bold ph-x-circle"></i>
                    Cancelar
                </a>
                <button type="submit" class="cur-form-btn cur-form-btn--submit" id="btn-submit">
                    <i class="ph-bold <?= $es_editar ? 'ph-floppy-disk' : 'ph-paper-plane-tilt' ?>"></i>
                    <span id="btn-label"><?= $es_editar ? 'Guardar Cambios' : 'Registrar Curso' ?></span>
                    <span id="btn-spinner" style="display:none;"><span class="cur-spinner"></span></span>
                </button>
            </div>

        </form>
    </div><!-- /.cur-form-card -->

</div><!-- /.cur-form-page -->

<script>
(function () {
    // ── Contador de caracteres en descripción ──
    var textarea = document.getElementById('descripcion');
    var counter  = document.getElementById('desc-count');
    if (textarea && counter) {
        var update = function() { counter.textContent = textarea.value.length; };
        textarea.addEventListener('input', update);
        update();
    }

    // ── Preview de imagen en tiempo real ──
    var imgInput   = document.getElementById('imagen_portada');
    var imgPreview = document.getElementById('img-preview');
    if (imgInput && imgPreview) {
        imgInput.addEventListener('input', function () {
            var url = this.value.trim();
            var placeholder = imgPreview.querySelector('.cur-img-placeholder');
            if (url) {
                imgPreview.style.backgroundImage = "url('" + url + "')";
                if (placeholder) placeholder.style.display = 'none';
            } else {
                imgPreview.style.backgroundImage = 'none';
                if (placeholder) placeholder.style.display = 'flex';
            }
        });
    }

    // ── Indicador visual de nota mínima ──
    var notaInput = document.getElementById('nota_minima');
    var notaFill  = document.getElementById('nota-fill');
    var notaVal   = document.getElementById('nota-val');
    if (notaInput && notaFill && notaVal) {
        notaInput.addEventListener('input', function () {
            var val = Math.min(100, Math.max(0, parseFloat(this.value) || 0));
            notaFill.style.width = val + '%';
            notaVal.textContent  = val.toFixed(1) + '%';
            notaFill.className   = 'cur-nota-fill' +
                (val >= 70 ? ' ok' : val >= 50 ? ' warn' : ' low');
        });
        // Inicializar clase
        var initVal = parseFloat(notaInput.value) || 70;
        notaFill.className = 'cur-nota-fill' +
            (initVal >= 70 ? ' ok' : initVal >= 50 ? ' warn' : ' low');
    }

    // ── Pills de estado sincronizadas ──
    var estadoSelect = document.getElementById('estado');
    var pills        = document.querySelectorAll('.cur-estado-pill');
    if (estadoSelect && pills.length) {
        estadoSelect.addEventListener('change', function () {
            pills.forEach(function(p) { p.classList.remove('active'); });
            var active = document.querySelector('.cur-estado-pill--' + this.value);
            if (active) active.classList.add('active');
        });
    }

    // ── Efecto de carga al enviar ──
    var form     = document.getElementById('form-curso');
    var btnSub   = document.getElementById('btn-submit');
    var btnLabel = document.getElementById('btn-label');
    var btnSpin  = document.getElementById('btn-spinner');
    if (form && btnSub) {
        form.addEventListener('submit', function (e) {
            // Validación mínima de HTML5
            if (!form.checkValidity()) return;
            btnSub.disabled = true;
            if (btnLabel) btnLabel.style.display = 'none';
            if (btnSpin)  btnSpin.style.display  = 'inline-flex';
        });
    }

    // ── Validación visual en tiempo real del URL de Moodle ──
    var moodleInput = document.getElementById('url_moodle');
    if (moodleInput) {
        moodleInput.addEventListener('blur', function() {
            var val = this.value.trim();
            if (val && !val.startsWith('http')) {
                this.style.borderColor = '#ef4444';
                this.title = 'La URL debe empezar con http:// o https://';
            } else {
                this.style.borderColor = '';
                this.title = '';
            }
        });
    }
})();
</script>
