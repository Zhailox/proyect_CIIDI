<?php
// modules/Cursos/views/form_curso.php
// Variables disponibles: $curso (array|null), $docentes, $modo, $titulo_form, $error
//   $modo = 'crear' | 'editar'

$es_editar   = ($modo === 'editar');
$accion_url  = $es_editar ? '?ruta=cursos-procesar-editar' : '?ruta=cursos-procesar-crear';

// Pre-rellenar campos en modo edición
$f_titulo   = htmlspecialchars($curso['titulo']   ?? '');
$f_desc     = htmlspecialchars($curso['descripcion'] ?? '');
$f_img      = htmlspecialchars($curso['imagen_portada'] ?? '');
$f_estado   = $curso['estado'] ?? 'borrador';
$f_nota     = $curso['nota_minima_aprobacion'] ?? '70.00';
$f_docente  = $curso['id_docente'] ?? '';
$f_id       = $curso['id'] ?? '';
?>

<div class="cur-form-page">

    <!-- ── BREADCRUMB ──────────────────────────────────────── -->
    <nav class="cur-breadcrumb">
        <a href="?ruta=cursos">
            <i class="ph-fill ph-graduation-cap"></i>
            Catálogo de Cursos
        </a>
        <i class="ph-bold ph-caret-right"></i>
        <span><?= htmlspecialchars($titulo_form) ?></span>
    </nav>

    <!-- ── CARD PRINCIPAL ─────────────────────────────────── -->
    <div class="cur-form-card">

        <!-- Header de la card -->
        <div class="cur-form-header">
            <div class="cur-form-header-left">
                <div class="cur-form-header-icon <?= $es_editar ? 'icon--edit' : 'icon--create' ?>">
                    <i class="ph-fill <?= $es_editar ? 'ph-pencil-simple' : 'ph-plus-circle' ?>"></i>
                </div>
                <div class="cur-form-header-text">
                    <h2><?= htmlspecialchars($titulo_form) ?></h2>
                    <p><?= $es_editar
                            ? 'Modifica los datos del curso y guarda los cambios.'
                            : 'Completa todos los campos para añadir un curso al catálogo.'
                        ?></p>
                </div>
            </div>
            <a href="?ruta=cursos" class="cur-form-back-btn" title="Volver al catálogo">
                <i class="ph-bold ph-arrow-left"></i>
                <span>Volver</span>
            </a>
        </div>

        <!-- Alerta de error de validación -->
        <?php if (!empty($error)): ?>
        <div class="cur-form-alert cur-form-alert--error">
            <i class="ph-fill ph-warning-circle"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
        <?php endif; ?>

        <!-- ── FORMULARIO ──────────────────────────────────── -->
        <form method="POST" action="<?= $accion_url ?>" class="cur-form" id="form-curso" novalidate>

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
                        Título del Curso
                        <span class="cur-field-required">*</span>
                    </label>
                    <div class="cur-field-input-wrap">
                        <i class="ph-fill ph-text-aa cur-field-icon"></i>
                        <input
                            type="text"
                            id="titulo"
                            name="titulo"
                            class="cur-field-input"
                            value="<?= $f_titulo ?>"
                            placeholder="Ej: Fundamentos de Inteligencia Artificial"
                            maxlength="255"
                            required
                            autocomplete="off"
                        >
                    </div>
                </div>

                <!-- Docente + Estado (grid 2 col) -->
                <div class="cur-field-row">
                    <div class="cur-field-group">
                        <label for="id_docente" class="cur-field-label">
                            Docente Responsable
                            <span class="cur-field-required">*</span>
                        </label>
                        <div class="cur-field-input-wrap">
                            <i class="ph-fill ph-chalkboard-teacher cur-field-icon"></i>
                            <select id="id_docente" name="id_docente" class="cur-field-select" required>
                                <option value="">— Seleccionar docente —</option>
                                <?php foreach ($docentes as $doc): ?>
                                <option value="<?= (int)$doc['id'] ?>" <?= ($f_docente == $doc['id']) ? 'selected' : '' ?>>
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
                    <h3>Descripción</h3>
                </div>

                <div class="cur-field-group cur-field-group--full">
                    <label for="descripcion" class="cur-field-label">Descripción del Curso</label>
                    <textarea
                        id="descripcion"
                        name="descripcion"
                        class="cur-field-textarea"
                        rows="5"
                        placeholder="Describe los objetivos, contenidos, duración y el público objetivo del curso…"
                    ><?= $f_desc ?></textarea>
                    <div class="cur-field-counter">
                        <span id="desc-count">0</span> caracteres
                    </div>
                </div>
            </div>

            <!-- ════ SECCIÓN 3: Configuración Adicional ════ -->
            <div class="cur-form-section">
                <div class="cur-form-section-title">
                    <span class="cur-form-section-num">03</span>
                    <h3>Configuración Adicional</h3>
                </div>

                <div class="cur-field-row">
                    <!-- Imagen portada + preview -->
                    <div class="cur-field-group cur-field-group--img">
                        <label for="imagen_portada" class="cur-field-label">
                            URL de Imagen de Portada
                            <span class="cur-field-hint">(Opcional)</span>
                        </label>
                        <div class="cur-field-input-wrap">
                            <i class="ph-fill ph-image cur-field-icon"></i>
                            <input
                                type="url"
                                id="imagen_portada"
                                name="imagen_portada"
                                class="cur-field-input"
                                value="<?= $f_img ?>"
                                placeholder="https://…"
                            >
                        </div>

                        <!-- Preview de imagen -->
                        <div class="cur-img-preview-box" id="img-preview-box">
                            <div class="cur-img-preview-inner" id="img-preview"
                                 style="background-image: url('<?= $f_img ?>')">
                                <?php if (empty($f_img)): ?>
                                <div class="cur-img-placeholder">
                                    <i class="ph-fill ph-image"></i>
                                    <span>Vista previa</span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Nota mínima -->
                    <div class="cur-field-group">
                        <label for="nota_minima" class="cur-field-label">
                            Nota Mínima de Aprobación
                        </label>
                        <div class="cur-field-input-wrap">
                            <i class="ph-fill ph-medal cur-field-icon"></i>
                            <input
                                type="number"
                                id="nota_minima"
                                name="nota_minima_aprobacion"
                                class="cur-field-input"
                                value="<?= htmlspecialchars((string)$f_nota) ?>"
                                min="0"
                                max="100"
                                step="0.01"
                                placeholder="70.00"
                            >
                        </div>
                        <span class="cur-field-info">Escala de 0 a 100 puntos</span>

                        <!-- Indicador visual de nota -->
                        <div class="cur-nota-indicator" id="nota-indicator">
                            <div class="cur-nota-bar">
                                <div class="cur-nota-fill" id="nota-fill" style="width: <?= min(100, (float)$f_nota) ?>%"></div>
                            </div>
                            <span class="cur-nota-val" id="nota-val"><?= number_format((float)$f_nota, 1) ?>%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── ACCIONES ───────────────────────────────── -->
            <div class="cur-form-footer">
                <a href="?ruta=cursos" class="cur-form-btn cur-form-btn--cancel">
                    <i class="ph-bold ph-x-circle"></i>
                    Cancelar
                </a>
                <button type="submit" class="cur-form-btn cur-form-btn--submit" id="btn-submit">
                    <i class="ph-bold <?= $es_editar ? 'ph-floppy-disk' : 'ph-paper-plane-tilt' ?>"></i>
                    <span><?= $es_editar ? 'Guardar Cambios' : 'Registrar Curso' ?></span>
                    <span class="cur-form-btn-loader" style="display:none;">
                        <i class="ph-bold ph-spinner"></i>
                    </span>
                </button>
            </div>

        </form>
    </div><!-- /.cur-form-card -->
</div><!-- /.cur-form-page -->

<script>
(function () {
    // ── Contador de caracteres descripción ──
    const textarea  = document.getElementById('descripcion');
    const counter   = document.getElementById('desc-count');
    if (textarea && counter) {
        const update = () => { counter.textContent = textarea.value.length; };
        textarea.addEventListener('input', update);
        update();
    }

    // ── Vista previa de imagen en tiempo real ──
    const imgInput   = document.getElementById('imagen_portada');
    const imgPreview = document.getElementById('img-preview');
    if (imgInput && imgPreview) {
        imgInput.addEventListener('input', function () {
            const url = this.value.trim();
            const placeholder = imgPreview.querySelector('.cur-img-placeholder');
            if (url) {
                imgPreview.style.backgroundImage = `url('${url}')`;
                if (placeholder) placeholder.style.display = 'none';
            } else {
                imgPreview.style.backgroundImage = 'none';
                if (placeholder) placeholder.style.display = 'flex';
            }
        });
    }

    // ── Indicador visual de nota mínima ──
    const notaInput = document.getElementById('nota_minima');
    const notaFill  = document.getElementById('nota-fill');
    const notaVal   = document.getElementById('nota-val');
    if (notaInput && notaFill && notaVal) {
        notaInput.addEventListener('input', function () {
            const val = Math.min(100, Math.max(0, parseFloat(this.value) || 0));
            notaFill.style.width = val + '%';
            notaVal.textContent  = val.toFixed(1) + '%';
            notaFill.className   = 'cur-nota-fill' + (val >= 70 ? ' ok' : val >= 50 ? ' warn' : ' low');
        });
    }

    // ── Pills de estado sincronizadas con el select ──
    const estadoSelect = document.getElementById('estado');
    const pills        = document.querySelectorAll('.cur-estado-pill');
    if (estadoSelect && pills.length) {
        estadoSelect.addEventListener('change', function () {
            pills.forEach(p => p.classList.remove('active'));
            const active = document.querySelector(`.cur-estado-pill--${this.value}`);
            if (active) active.classList.add('active');
        });
    }

    // ── Efecto de carga al enviar ──
    const form      = document.getElementById('form-curso');
    const btnSubmit = document.getElementById('btn-submit');
    if (form && btnSubmit) {
        form.addEventListener('submit', function () {
            btnSubmit.disabled = true;
            const label  = btnSubmit.querySelector('span:first-of-type');
            const loader = btnSubmit.querySelector('.cur-form-btn-loader');
            if (label)  label.style.display  = 'none';
            if (loader) loader.style.display  = 'inline-flex';
        });
    }
})();
</script>
