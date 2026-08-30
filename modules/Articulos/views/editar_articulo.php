<div class="gestor-art-container">
    <div class="gestor-art-header box-outlined">
        <div class="gestor-art-title-box">
            <h1 class="text-secondary">Editar Artículo</h1>
            <p class="text-muted">Modifica los mismos campos que en el alta.</p>
        </div>
        <a href="gestor-articulos" class="btn btn-secondary">
            <i class="ph-bold ph-arrow-left"></i> Volver al Gestor
        </a>
    </div>

    <form action="actualizar-articulo" method="POST" enctype="multipart/form-data" class="art-form-layout" onsubmit="return validarAutores()">
        <input type="hidden" name="id_articulo" value="<?= (int)($articulo['id'] ?? 0) ?>">
        <input type="hidden" name="imagen_actual" value="<?= htmlspecialchars($articulo['imagen_portada'] ?? 'default_article.jpg') ?>">

        <div class="art-form-main">
            <div class="gestor-art-card mb-2">
                <h3 class="card-subtitle text-tertiary">Información General</h3>

                <div class="form-group mt-1">
                    <label class="font-bold">Título del Artículo *</label>
                    <input type="text" name="titulo" class="login-flat-input w-100 p-input" value="<?= htmlspecialchars($articulo['titulo'] ?? '') ?>" required>
                </div>

                <div class="form-group mt-1">
                    <label class="font-bold">Resumen / Abstract *</label>
                    <textarea name="resumen" class="login-flat-input w-100 p-input" rows="4" required><?= htmlspecialchars($articulo['resumen'] ?? '') ?></textarea>
                </div>

                <div class="grid-2-cols mt-1">
                    <div class="form-group">
                        <label class="font-bold">Categoría</label>
                        <select name="id_categoria" class="login-flat-input w-100 p-input">
                            <option value="">Sin categoría</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= (int)$cat['id'] ?>" <?= (!empty($articulo['id_categoria']) && (int)$articulo['id_categoria'] === (int)$cat['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-bold">Editorial</label>
                        <select name="id_editorial" class="login-flat-input w-100 p-input">
                            <option value="">Sin editorial</option>
                            <?php foreach ($editoriales as $ed): ?>
                                <option value="<?= (int)$ed['id'] ?>" <?= (!empty($articulo['id_editorial']) && (int)$articulo['id_editorial'] === (int)$ed['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ed['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group mt-1">
                    <label class="font-bold">Enlace Oficial (URL del Repositorio) *</label>
                    <input type="url" name="archivo_pdf" class="login-flat-input w-100 p-input" value="<?= htmlspecialchars($articulo['archivo_pdf'] ?? '') ?>" required>
                </div>
            </div>

            <div class="gestor-art-card">
                <h3 class="card-subtitle text-tertiary">Autores y Clasificación</h3>

                <div class="form-group mt-1 pos-relative">
                    <label class="font-bold">Buscar y Seleccionar Autores *</label>
                    <input type="text" id="buscador-autores" class="login-flat-input w-100 p-input" placeholder="Escriba un nombre o cédula...">

                    <div id="resultados-autores" class="autocomplete-dropdown"></div>

                    <div id="autores-seleccionados" class="chips-container mt-1"></div>

                    <div id="error-autores" class="text-danger mt-sm" style="display:none; font-size:0.85rem;">
                        Debe seleccionar al menos un autor.
                    </div>

                    <div id="autores-hidden-inputs"></div>
                </div>

                <div class="form-group mt-1-5">
                    <label class="font-bold">Etiquetas del Artículo</label>
                    <div class="checkbox-grid-box p-1">
                        <?php foreach ($etiquetas as $tag): ?>
                            <label class="checkbox-label">
                                <input type="checkbox" name="etiquetas[]" value="<?= (int)$tag['id'] ?>" <?= in_array((int)$tag['id'], $etiquetasSeleccionadas, true) ? 'checked' : '' ?>>
                                <?= htmlspecialchars((string) $tag['nombre']) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="art-form-sidebar">
            <div class="gestor-art-card mb-2">
                <h3 class="card-subtitle text-secondary">Publicación</h3>

                <div class="form-group mt-1">
                    <label class="font-bold">Año</label>
                    <input type="number" name="anio_publicacion" class="login-flat-input w-100 p-input" value="<?= (int)($articulo['anio_publicacion'] ?? date('Y')) ?>" required>
                </div>

                <div class="grid-2-cols mt-1">
                    <div class="form-group">
                        <label class="font-bold">Volumen</label>
                        <input type="text" name="volumen" class="login-flat-input w-100 p-input" value="<?= htmlspecialchars($articulo['volumen'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="font-bold">Número</label>
                        <input type="text" name="numero" class="login-flat-input w-100 p-input" value="<?= htmlspecialchars($articulo['numero'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group mt-1">
                    <label class="font-bold">ISSN</label>
                    <input type="text" name="issn" class="login-flat-input w-100 p-input" value="<?= htmlspecialchars($articulo['issn'] ?? '') ?>">
                </div>
            </div>

            <div class="gestor-art-card mb-2">
                <h3 class="card-subtitle text-secondary">Portada</h3>
                <div class="form-group mt-1">
                    <label class="font-bold">Cambiar imagen de portada</label>
                    <input type="file" name="imagen_portada" class="login-flat-input w-100 p-input file-input-dashed">
                </div>
                <div class="form-group mt-1">
                    <label class="font-bold">O pegar URL de imagen</label>
                    <input
                        type="text"
                        name="url_imagen"
                        class="login-flat-input w-100 p-input"
                        value="<?= htmlspecialchars($articulo['imagen_portada'] ?? '') ?>"
                        placeholder="https://ejemplo.com/imagen.jpg"
                    >
                </div>
                

            <button type="submit" class="btn btn-primary w-100 btn-large justify-center">
                <i class="ph-bold ph-floppy-disk"></i> Guardar Cambios
            </button>
        </div>
    </form>
    <div id="modal-autor" class="art-modal-overlay" style="display: none;">
        <div class="art-modal-box">
            <h3 class="text-secondary mt-0" style="border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 1rem;">Registrar Nuevo Autor</h3>

            <p class="text-muted mb-2">El autor se vinculará a este artículo y se guardará en la base de datos al enviar el formulario.</p>

            <div class="form-group mt-1">
                <label class="font-bold">Nombre Completo *</label>
                <input type="text" id="modal-autor-nombre" class="login-flat-input w-100 p-input">
            </div>

            <div class="form-group mt-1">
                <label class="font-bold">Cédula</label>
                <div style="display: flex; gap: 0.5rem;">
                    <select id="modal-autor-nacionalidad" class="login-flat-input p-input" style="width: 80px; flex-shrink: 0; cursor: pointer;">
                        <option value="V-">V-</option>
                        <option value="E-">E-</option>
                    </select>
                    <input type="number" id="modal-autor-cedula" class="login-flat-input w-100 p-input" placeholder="Ej: 12345678" required min="1000000">
                </div>
                <small class="text-muted d-block mt-sm">Solo ingrese los números. La nacionalidad se añade automáticamente.</small>
            </div>

            <div class="mt-1-5" style="display: flex; justify-content: flex-end; gap: 1rem; padding-top: 1rem;">
                <button type="button" class="btn btn-secondary" onclick="cerrarModalAutor()">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="confirmarModalAutor()">Añadir Autor</button>
            </div>
        </div>
    </div>
<script>
    window.DATA_AUTORES = <?= json_encode($autores) ?>;
    window.AUTORES_SELECCIONADOS = <?= json_encode(array_map('intval', $autoresSeleccionados ?? [])) ?>;
</script>
<script src="../modules/Articulos/assets/js/gestor_autores.js"></script>
</div>