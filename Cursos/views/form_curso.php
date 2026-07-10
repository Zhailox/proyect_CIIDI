<?php
// modules/Cursos/views/form_curso.php
// Variables inyectadas por PromoController::formCurso():
// $curso     — array|null (null = crear nuevo)
// $es_admin  — bool
// $usuario_id — int

$modo_edicion = $curso !== null;
$titulo_pagina = $modo_edicion ? 'Editar Curso' : 'Nuevo Curso';
$error_titulo  = isset($_GET['error']) && $_GET['error'] === 'titulo_vacio';
?>

<div class="frm-wrapper">

    <!-- HEADER -->
    <div class="frm-header">
        <div class="frm-header-left">
            <a href="<?= $modo_edicion ? '?ruta=detalle-curso&id=' . $curso['id'] : '?ruta=cursos' ?>" class="frm-back-link">
                <i class="ph-bold ph-arrow-left"></i> Volver
            </a>
            <h1>
                <i class="ph-fill ph-<?= $modo_edicion ? 'pencil-simple' : 'plus-circle' ?>"></i>
                <?= $titulo_pagina ?>
            </h1>
        </div>
        <?php if ($modo_edicion && $es_admin): ?>
        <form method="POST" action="?ruta=eliminar-curso" id="form-eliminar"
              onsubmit="return confirm('¿Eliminar permanentemente «<?= htmlspecialchars(addslashes($curso['titulo'])) ?>»? Esta acción no se puede deshacer.')">
            <input type="hidden" name="id_curso" value="<?= $curso['id'] ?>">
            <button type="submit" class="frm-btn-delete">
                <i class="ph-fill ph-trash"></i> Eliminar Curso
            </button>
        </form>
        <?php endif; ?>
    </div>

    <?php if ($error_titulo): ?>
    <div class="det-alert det-alert--error">
        <i class="ph-fill ph-warning-circle"></i>
        El título del curso es obligatorio.
    </div>
    <?php endif; ?>

    <!-- FORMULARIO -->
    <form method="POST" action="?ruta=guardar-curso" class="frm-form">
        <?php if ($modo_edicion): ?>
        <input type="hidden" name="id" value="<?= $curso['id'] ?>">
        <?php endif; ?>

        <div class="frm-grid">

            <!-- COLUMNA PRINCIPAL -->
            <div class="frm-main">

                <div class="frm-section">
                    <h2 class="frm-section-title">
                        <i class="ph-fill ph-info"></i> Información Básica
                    </h2>

                    <div class="frm-group">
                        <label class="frm-label" for="titulo">Título del Curso *</label>
                        <input
                            type="text"
                            id="titulo"
                            name="titulo"
                            class="frm-input"
                            placeholder="Ej: Fundamentos de Programación en Python"
                            value="<?= htmlspecialchars($curso['titulo'] ?? '') ?>"
                            required
                        >
                    </div>

                    <div class="frm-group">
                        <label class="frm-label" for="descripcion">Descripción</label>
                        <textarea
                            id="descripcion"
                            name="descripcion"
                            class="frm-input frm-textarea"
                            rows="6"
                            placeholder="Describe el contenido, objetivos y público objetivo del curso..."
                        ><?= htmlspecialchars($curso['descripcion'] ?? '') ?></textarea>
                    </div>

                    <div class="frm-group">
                        <label class="frm-label" for="imagen_portada">URL de Imagen de Portada</label>
                        <input
                            type="url"
                            id="imagen_portada"
                            name="imagen_portada"
                            class="frm-input"
                            placeholder="https://images.unsplash.com/photo-..."
                            value="<?= htmlspecialchars($curso['imagen_portada'] ?? '') ?>"
                        >
                        <span class="frm-hint">Si se deja vacío, se asignará una imagen automática.</span>

                        <!-- Preview de la imagen -->
                        <div class="frm-img-preview" id="img-preview" style="<?= empty($curso['imagen_portada'] ?? '') ? 'display:none' : '' ?>">
                            <img src="<?= htmlspecialchars($curso['imagen_portada'] ?? '') ?>" alt="Preview" id="img-preview-src">
                        </div>
                    </div>
                </div>

            </div>

            <!-- COLUMNA LATERAL -->
            <aside class="frm-sidebar">

                <div class="frm-section">
                    <h2 class="frm-section-title">
                        <i class="ph-fill ph-sliders"></i> Configuración
                    </h2>

                    <div class="frm-group">
                        <label class="frm-label" for="estado">Estado de publicación</label>
                        <select id="estado" name="estado" class="frm-select">
                            <option value="publicado"  <?= ($curso['estado'] ?? 'publicado') === 'publicado'  ? 'selected' : '' ?>>✅ Publicado</option>
                            <option value="borrador"   <?= ($curso['estado'] ?? '') === 'borrador'   ? 'selected' : '' ?>>📝 Borrador</option>
                            <option value="archivado"  <?= ($curso['estado'] ?? '') === 'archivado'  ? 'selected' : '' ?>>📦 Archivado</option>
                        </select>
                    </div>

                    <div class="frm-group">
                        <label class="frm-label" for="nota_minima">Nota Mínima de Aprobación (%)</label>
                        <input
                            type="number"
                            id="nota_minima"
                            name="nota_minima_aprobacion"
                            class="frm-input"
                            min="0" max="100" step="0.5"
                            value="<?= htmlspecialchars($curso['nota_minima_aprobacion'] ?? '70') ?>"
                        >
                    </div>

                    <?php if ($modo_edicion): ?>
                    <div class="frm-info-box">
                        <div class="frm-info-item">
                            <span class="frm-info-label">Docente</span>
                            <span class="frm-info-value"><?= htmlspecialchars($curso['docente_nombre']) ?></span>
                        </div>
                        <div class="frm-info-item">
                            <span class="frm-info-label">ID del curso</span>
                            <span class="frm-info-value">#<?= $curso['id'] ?></span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <button type="submit" class="frm-btn-submit">
                        <i class="ph-fill ph-floppy-disk"></i>
                        <?= $modo_edicion ? 'GUARDAR CAMBIOS' : 'CREAR CURSO' ?>
                    </button>

                    <a href="<?= $modo_edicion ? '?ruta=detalle-curso&id=' . $curso['id'] : '?ruta=cursos' ?>" class="frm-btn-cancel">
                        Cancelar
                    </a>
                </div>

            </aside>
        </div>
    </form>
</div>

<script>
// Preview de imagen al escribir la URL
document.getElementById('imagen_portada').addEventListener('input', function() {
    const preview = document.getElementById('img-preview');
    const img     = document.getElementById('img-preview-src');
    if (this.value.trim()) {
        img.src = this.value;
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
});
</script>
