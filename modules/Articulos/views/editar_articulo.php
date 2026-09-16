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

    <form action="actualizar-articulo" method="POST" enctype="multipart/form-data" class="art-form-layout" id="form-articulo" onsubmit="return validarFormulario()">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">    
        <input type="hidden" name="id_articulo" value="<?= (int)($articulo['id'] ?? 0) ?>">
        <input type="hidden" name="imagen_actual" value="<?= htmlspecialchars($articulo['imagen_portada'] ?? 'default_article.jpg') ?>">

        <div class="art-form-main">
            <div class="gestor-art-card mb-2">
                <h3 class="card-subtitle text-tertiary">Información General</h3>

                <div class="form-group mt-1">
                    <label class="font-bold">Título del Artículo * <span class="art-help-icon" title="El título oficial exacto del documento o publicación.">?</span></label>
                    <input type="text" name="titulo" class="login-flat-input w-100 p-input" value="<?= htmlspecialchars($articulo['titulo'] ?? '') ?>" required>
                </div>

                <div class="form-group mt-1">
                    <label class="font-bold">Resumen / Abstract * <span class="art-help-icon" title="Breve síntesis de la investigación. Se recomienda un máximo de 4 a 5 líneas.">?</span></label>
                    <textarea name="resumen" class="login-flat-input w-100 p-input" rows="4" required><?= htmlspecialchars($articulo['resumen'] ?? '') ?></textarea>
                </div>

                <div class="grid-2-cols mt-1">
                   <div class="form-group mt-1">
                        <label class="font-bold">Categorías del Artículo * <span class="art-help-icon" title="Clasificación temática principal. Puedes seleccionar varias opciones.">?</span></label>
                        <div class="checkbox-grid-box p-1" id="box-categorias">
                            
                        </div>
                        <div id="error-categorias" class="text-danger mt-sm" style="display:none; font-size:0.85rem;">
                            Debe seleccionar al menos una categoría.
                        </div>
                    
                </div>

                    <div class="form-group">
                        <label class="font-bold">Editorial / Repositorio <span class="art-help-icon" title="Institución, universidad o revista científica que avala la publicación original.">?</span></label>
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
                    <label class="font-bold">Enlace Oficial (URL) * <span class="art-help-icon" title="Link directo (URL o DOI) hacia el PDF original en internet.">?</span></label>
                    <input type="url" name="archivo_pdf" class="login-flat-input w-100 p-input" value="<?= htmlspecialchars($articulo['archivo_pdf'] ?? '') ?>" required>
                </div>
            </div>

            <div class="gestor-art-card">
                <h3 class="card-subtitle text-tertiary">Autores y Clasificación</h3>

                <div class="form-group mt-1 pos-relative">
                    <label class="font-bold">Buscar y Seleccionar Autores * <span class="art-help-icon" title="Escribe el nombre o cédula. Si el autor no existe en el sistema, usa el modal para crearlo.">?</span></label>
                    <input type="text" id="buscador-autores" class="login-flat-input w-100 p-input" placeholder="Escriba un nombre o cédula...">

                    <div id="resultados-autores" class="autocomplete-dropdown"></div>

                    <div id="autores-seleccionados" class="chips-container mt-1"></div>

                    <div id="error-autores" class="text-danger mt-sm" style="display:none; font-size:0.85rem;">
                        Debe seleccionar al menos un autor.
                    </div>

                    <div id="autores-hidden-inputs"></div>
                </div>

                <div class="form-group mt-1-5">
                    <label class="font-bold">Etiquetas del Artículo <span class="art-help-icon" title="Palabras clave específicas (ej. 'Machine Learning') para facilitar la búsqueda en el catálogo.">?</span></label>
                    <div class="checkbox-grid-box p-1" id="box-etiquetas">
                    
                    </div>
                </div>
            </div>
        </div>

        <div class="art-form-sidebar">
            <div class="gestor-art-card mb-2">
                <h3 class="card-subtitle text-secondary">Publicación</h3>

                <div class="form-group mt-1">
                    <label class="font-bold">Año <span class="art-help-icon" title="El año en el que se publicó oficialmente el artículo.">?</span></label>
                    <input type="number" name="anio_publicacion" class="login-flat-input w-100 p-input" value="<?= (int)($articulo['anio_publicacion'] ?? date('Y')) ?>" required>
                </div>

                <div class="grid-2-cols mt-1">
                    <div class="form-group">
                        <label class="font-bold">Volumen <span class="art-help-icon" title="Número de volumen de la revista científica (si aplica).">?</span></label>
                        <input type="text" name="volumen" class="login-flat-input w-100 p-input" value="<?= htmlspecialchars($articulo['volumen'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="font-bold">Número <span class="art-help-icon" title="Número de edición de la revista científica (si aplica).">?</span></label>
                        <input type="text" name="numero" class="login-flat-input w-100 p-input" value="<?= htmlspecialchars($articulo['numero'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group mt-1">
                    <label class="font-bold">ISSN <span class="art-help-icon" title="Código Internacional Normalizado de Publicaciones Seriadas (ej. 1234-5678).">?</span></label>
                    <input type="text" name="issn" class="login-flat-input w-100 p-input" value="<?= htmlspecialchars($articulo['issn'] ?? '') ?>">
                </div>
            </div>

            <div class="gestor-art-card mb-2">
                <h3 class="card-subtitle text-secondary">Portada</h3>
                
                <?php 
                // MAGIA: Comparamos el JSON con el php.ini del servidor
                $jsonMaxMb = (int)ConfigService::get('archivos.max_size_mb', 5);
                $phpMaxStr = ini_get('upload_max_filesize');
                $phpMaxMb = (int)preg_replace('/[^0-9]/', '', $phpMaxStr);
                
                // Determinamos cuál es el límite real y si es culpa del servidor
                $limiteRealMb = min($jsonMaxMb, $phpMaxMb);
                $esLimiteServidor = ($phpMaxMb < $jsonMaxMb) ? 'true' : 'false';

                $exts = ConfigService::get('archivos.extensiones_permitidas', ['.jpg', '.jpeg', '.png', '.webp']);
                $acceptStr = implode(',', $exts);
                
                if (isset($articulo)): 
                    $portadaActual = $articulo['imagen_portada'] ?? 'default_article.jpg';
                ?>
                    <div class="form-group mt-1">
                        <label class="font-bold">Portada Actual:</label>
                        <div class="text-muted art-text-truncate" title="<?= htmlspecialchars($portadaActual) ?>">
                            <i class="ph-bold ph-image"></i> <?= htmlspecialchars($portadaActual) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-group mt-1">
                    <label class="font-bold"><?= isset($articulo) ? 'Sustituir archivo físico:' : 'Subir archivo físico:' ?> <span class="art-help-icon" title="Sube una imagen representativa. El sistema la optimizará automáticamente a formato WebP.">?</span></label>
                    
                    <div id="dropzone-portada" data-max-mb="<?= $limiteRealMb ?>" data-server-limit="<?= $esLimiteServidor ?>" data-exts="<?= htmlspecialchars($acceptStr) ?>" style="border: 2px dashed rgba(112, 144, 203, 0.4); padding: 1.5rem 1rem; text-align: center; border-radius: 6px; cursor: pointer; background: rgba(244, 247, 251, 0.6); transition: all 0.25s;">
                        <input type="file" id="input_imagen_portada" name="imagen_portada" accept="<?= htmlspecialchars($acceptStr) ?>" style="display:none;">
                        <i class="ph-bold <?= isset($articulo) ? 'ph-upload-simple' : 'ph-image' ?>" style="font-size: 2rem; color: var(--color-terciario);"></i>
                        <h4 style="margin: 0.4rem 0; font-size: 0.85rem; color: var(--texto-titulos);">Arrastra una nueva portada o haz clic aquí</h4>
                        <p style="font-size: 0.75rem; color: var(--texto-silenciado); margin: 0;">Formatos permitidos: <?= htmlspecialchars(implode(', ', $exts)) ?> (Máx. <?= $limiteRealMb ?> MB)</p>
                        <div id="preview-image-name" class="art-text-truncate text-secondary font-bold mt-sm" style="display: none;"></div>
                    </div>
                </div>

                <div class="form-group mt-1">
                    <label class="font-bold">O ingresar URL externa: <span class="art-help-icon" title="Pega el enlace de una imagen de internet si no deseas subir un archivo desde tu computadora.">?</span></label>
                    <input type="url" name="url_imagen" class="login-flat-input w-100 p-input" placeholder="https://ejemplo.com/portada.jpg">
                    <small class="text-muted d-block mt-sm">Si subes un archivo físico, se ignorará esta URL.</small>
                </div>
            
            </div> 
            <button type="submit" class="btn btn-primary w-100 btn-large justify-center" style="border-radius: 6px; padding: 0.6rem 1.2rem; font-size: 0.875rem;">
                <i class="ph-bold ph-floppy-disk"></i> Guardar Cambios
            </button>
        </div>
    </form>
</div>
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
<script>
    window.DATA_AUTORES = <?= json_encode($autores ?? []) ?>;
    window.AUTORES_SELECCIONADOS = <?= json_encode(array_map('intval', $autoresSeleccionados ?? [])) ?>;
    window.CAT_SELECCIONADAS = <?= json_encode(array_map('intval', $categoriasSeleccionadas ?? [])) ?>;
    window.TAG_SELECCIONADAS = <?= json_encode(array_map('intval', $etiquetasSeleccionadas ?? [])) ?>;
</script>
<script src="../modules/Articulos/assets/js/gestor_autores.js"></script>
<script src="../modules/Articulos/assets/js/gestor_portadas.js"></script>
<script src="../modules/Articulos/assets/js/gestor_catalogos_cache.js"></script>
</div>