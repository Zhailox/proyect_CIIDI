<div class="main-content">
    <div class="pst-config-wrapper" style="max-width: 1000px; margin: 0 auto;">
        
        <div class="pst-config-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 2rem;">
            <div>
                <h1 class="text-secondary">Ajustes de la Revista Digital</h1>
                <p class="text-muted">Parámetros de citación, límites de imágenes y visualización de artículos.</p>
            </div>
            <a href="gestor-articulos" class="btn btn-secondary">
                <i class="ph ph-arrow-left"></i> Volver al Gestor
            </a>
        </div>

        <?php if (!empty($mensaje)): ?>
            <div class="alert-success" style="margin-bottom: 1rem;"><i class="ph-bold ph-check-circle"></i> <?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert-error" style="margin-bottom: 1rem;"><i class="ph-bold ph-warning-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="" method="POST" id="configRevistaForm">
            
            <div class="pst-config-nav-tabs" style="display:flex; gap:0.5rem; border-bottom: 2px solid rgba(0,0,0,0.05); padding-bottom:1rem; margin-bottom: 2rem;">
                <button type="button" class="btn btn-secondary" onclick="switchConfigTab('tabCitas', this)">Citas</button>
                <button type="button" class="btn btn-secondary" onclick="switchConfigTab('tabPaginacion', this)">Paginación</button>
                <button type="button" class="btn btn-secondary" onclick="switchConfigTab('tabRecursos', this)">Metadatos</button>
                <button type="button" class="btn btn-secondary" onclick="switchConfigTab('tabArchivos', this)">Imágenes y Límites</button>
            </div>

            <!-- TAB CITAS -->
            <div id="tabCitas" class="config-tab-pane active" style="display:block;">
                <div class="gestor-art-card mb-2" style="padding: 1.5rem;">
                    <h3 class="text-tertiary" style="margin-bottom: 1rem;">Estilos de Cita</h3>
                    <div id="citasContainer">
                        <?php foreach (($config['citas']['estilos'] ?? []) as $slug => $estilo): ?>
                            <div class="box-outlined mb-1" id="citation_box_<?= htmlspecialchars($slug) ?>" style="padding: 1rem;">
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1rem;">
                                    <input type="text" name="citas_estilos[<?= htmlspecialchars($slug) ?>][nombre]" value="<?= htmlspecialchars($estilo['nombre']) ?>" class="login-flat-input p-input" style="font-weight:bold; max-width:250px;" required>
                                    
                                    <div style="display:flex; gap:1rem; align-items:center;">
                                        <label class="checkbox-label">
                                            <input type="checkbox" name="citas_estilos[<?= htmlspecialchars($slug) ?>][activo]" value="1" <?= !empty($estilo['activo']) ? 'checked' : '' ?>> Activo
                                        </label>
                                        <button type="button" class="btn-icon btn-delete" onclick="eliminarFormatoCita('<?= htmlspecialchars($slug) ?>')"><i class="ph-bold ph-trash"></i></button>
                                    </div>
                                </div>

                                <div style="margin-bottom: 0.5rem; display:flex; gap:0.5rem; flex-wrap:wrap;">
                                    <small class="font-bold">Variables:</small>
                                    <?php foreach(['{autores}','{anio}','{titulo}','{editorial}','{volumen}','{numero}','{issn}'] as $var): ?>
                                        <span style="cursor:pointer; background:#f1f5f9; padding:0.1rem 0.4rem; border-radius:4px; font-size:0.8rem;" onclick="insertarVar('input_tpl_<?= htmlspecialchars($slug) ?>', '<?= $var ?>')"><?= $var ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <input type="text" id="input_tpl_<?= htmlspecialchars($slug) ?>" name="citas_estilos[<?= htmlspecialchars($slug) ?>][plantilla]" value="<?= htmlspecialchars($estilo['plantilla']) ?>" class="login-flat-input w-100 p-input">
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Nueva Cita -->
                    <div class="mt-1-5" style="border-top:1px dashed rgba(0,0,0,0.1); padding-top:1rem;">
                        <h4 class="text-tertiary">Agregar Nuevo Formato</h4>
                        <div class="grid-2-cols mt-1">
                            <input type="text" name="nuevo_estilo_slug" placeholder="Slug (ej. chicago)" class="login-flat-input p-input">
                            <input type="text" name="nuevo_estilo_nombre" placeholder="Nombre completo" class="login-flat-input p-input">
                        </div>
                        <input type="text" id="nuevo_tpl" name="nuevo_estilo_plantilla" placeholder="Plantilla con variables..." class="login-flat-input w-100 p-input mt-1">
                    </div>
                </div>
            </div>

            <!-- TAB PAGINACIÓN -->
            <div id="tabPaginacion" class="config-tab-pane" style="display:none;">
                <div class="gestor-art-card" style="padding: 1.5rem;">
                    <div class="grid-2-cols">
                        <div class="form-group">
                            <label class="font-bold">Catálogo Público (Grid Revistas)</label>
                            <input type="number" name="limite_catalogo" value="<?= (int)($config['paginacion']['limite_catalogo'] ?? 16) ?>" min="1" class="login-flat-input w-100 p-input">
                        </div>
                        <div class="form-group">
                            <label class="font-bold">Gestor Interno (Tabla)</label>
                            <input type="number" name="limite_gestor" value="<?= (int)($config['paginacion']['limite_gestor'] ?? 15) ?>" min="1" class="login-flat-input w-100 p-input">
                        </div>
                        <div class="form-group mt-1">
                            <label class="font-bold">Máximo de Artículos Relacionados</label>
                            <input type="number" name="max_recomendados" value="<?= (int)($config['paginacion']['max_recomendados'] ?? 3) ?>" min="0" class="login-flat-input w-100 p-input">
                            <small class="text-muted">A mostrar en la lectura del artículo.</small>
                        </div>
                        <div class="form-group mt-1">
                            <label class="font-bold">Año Mínimo en Filtro del Catálogo</label>
                            <input type="number" name="anio_minimo" value="<?= (int)($config['buscador']['anio_minimo'] ?? 2020) ?>" class="login-flat-input w-100 p-input">
                            <small class="text-muted">Límite inferior para el selector de fechas.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB METADATOS -->
            <div id="tabRecursos" class="config-tab-pane" style="display:none;">
                <div class="gestor-art-card" style="padding: 1.5rem;">
                    <h3 class="text-tertiary mb-1">Visibilidad en Ficha Técnica</h3>
                    
                    <label class="checkbox-label mb-1" style="display:block;">
                        <input type="checkbox" name="mostrar_editorial" value="1" <?= !empty($config['recursos']['mostrar_editorial']) ? 'checked' : '' ?>> Mostrar Editorial / Repositorio
                    </label>
                    <label class="checkbox-label mb-1" style="display:block;">
                        <input type="checkbox" name="mostrar_volumen" value="1" <?= !empty($config['recursos']['mostrar_volumen']) ? 'checked' : '' ?>> Mostrar Volumen y Número
                    </label>
                    <label class="checkbox-label mb-1" style="display:block;">
                        <input type="checkbox" name="mostrar_issn" value="1" <?= !empty($config['recursos']['mostrar_issn']) ? 'checked' : '' ?>> Mostrar Código ISSN
                    </label>
                </div>
            </div>

            <!-- TAB ARCHIVOS -->
            <div id="tabArchivos" class="config-tab-pane" style="display:none;">
                <div class="gestor-art-card" style="padding: 1.5rem;">
                    <div class="grid-2-cols">
                        <div class="form-group">
                            <label class="font-bold">Peso Máximo por Imagen de Portada (MB)</label>
                            <input type="number" name="max_size_mb" value="<?= (int)($config['archivos']['max_size_mb'] ?? 5) ?>" min="1" class="login-flat-input w-100 p-input">
                            <small class="text-muted">Evita saturar el servidor con fotos gigantes.</small>
                        </div>
                        <div class="form-group">
                            <label class="font-bold">Extensiones Permitidas (separadas por coma)</label>
                            <?php 
                            $extRaw = implode(', ', $config['archivos']['extensiones_permitidas'] ?? ['.jpg', '.jpeg', '.png', '.webp']);
                            ?>
                            <input type="text" name="extensiones_permitidas_raw" value="<?= htmlspecialchars($extRaw) ?>" class="login-flat-input w-100 p-input">
                            <small class="text-muted">Ejemplo: .jpg, .png, .webp</small>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-2 btn-large justify-center">
                <i class="ph-bold ph-floppy-disk"></i> Guardar Configuración de la Revista
            </button>
        </form>
    </div>
</div>

<script>
function switchConfigTab(tabId, btn) {
    document.querySelectorAll('.config-tab-pane').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.pst-config-nav-tabs button').forEach(b => {
        b.classList.remove('btn-primary');
        b.classList.add('btn-secondary');
    });
    
    document.getElementById(tabId).style.display = 'block';
    btn.classList.remove('btn-secondary');
    btn.classList.add('btn-primary');
}

function insertarVar(inputId, text) {
    const input = document.getElementById(inputId);
    input.value += text;
    input.focus();
}

function eliminarFormatoCita(slug) {
    if(confirm('¿Eliminar formato de cita? Se aplicará al guardar.')){
        document.getElementById('citation_box_' + slug).style.opacity = '0.3';
        const h = document.createElement('input');
        h.type = 'hidden'; h.name = 'eliminar_estilo'; h.value = slug;
        document.getElementById('configRevistaForm').appendChild(h);
    }
}
</script>