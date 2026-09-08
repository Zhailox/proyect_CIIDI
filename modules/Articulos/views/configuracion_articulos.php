<div class="gestor-art-container" style="max-width: 1000px; margin: 0 auto;">
    
    <div class="gestor-art-header">
        <div class="gestor-art-title-box">
            <h1><i class="ph-bold ph-sliders"></i> Ajustes de la Revista Digital</h1>
            <p>Configuración de formatos de citación, límites de archivos y parámetros de visualización.</p>
        </div>
        <a href="gestor-articulos" class="btn btn-secondary gestor-art-btn-new" style="display:inline-flex; align-items:center; gap:0.5rem;">
            <i class="ph-bold ph-arrow-left"></i> Volver al Gestor
        </a>
    </div>

    <?php if (!empty($mensaje)): ?>
        <div class="alert-success"><i class="ph-bold ph-check-circle"></i> <?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert-error"><i class="ph-bold ph-warning-circle"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="" method="POST" id="configRevistaForm">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
        
        <div class="pst-config-nav-tabs">
            <button type="button" class="tab-btn-antigravity active" onclick="switchConfigTab('tabCitas', this)">
                <i class="ph-bold ph-quotes"></i> Formatos de Cita
            </button>
            <button type="button" class="tab-btn-antigravity" onclick="switchConfigTab('tabPaginacion', this)">
                <i class="ph-bold ph-list-numbers"></i> Paginación y Límites
            </button>
            <button type="button" class="tab-btn-antigravity" onclick="switchConfigTab('tabRecursos', this)">
                <i class="ph-bold ph-eye"></i> Visibilidad de Metadatos
            </button>
            <button type="button" class="tab-btn-antigravity" onclick="switchConfigTab('tabArchivos', this)">
                <i class="ph-bold ph-image"></i> Imágenes y Archivos
            </button>
        </div>

        <!-- TAB CITAS -->
        <div id="tabCitas" class="config-tab-pane active" style="display:block;">
            <div class="pst-config-card mb-2">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.06); padding-bottom: 0.8rem;">
                    <div>
                        <h3 class="text-tertiary" style="margin:0;"><i class="ph-bold ph-quotes"></i> Estilos de Cita Académica</h3>
                        <p class="text-muted" style="font-size:0.85rem; margin:0.2rem 0 0 0;">Defina cómo se formatearán las citas automáticas generadas para los investigadores.</p>
                    </div>
                </div>

                <div id="citasContainer">
                    <?php foreach (($config['citas']['estilos'] ?? []) as $slug => $estilo): ?>
                        <div class="citation-box-glass" id="citation_box_<?= htmlspecialchars($slug) ?>">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 0.8rem; flex-wrap:wrap; gap:0.5rem;">
                                <div style="display:flex; align-items:center; gap:0.6rem;">
                                    <span style="background:var(--color-secundario); color:white; font-size:0.75rem; padding:0.2rem 0.5rem; border-radius:4px; font-weight:bold; text-transform:uppercase;"><?= htmlspecialchars($slug) ?></span>
                                    <input type="text" name="citas_estilos[<?= htmlspecialchars($slug) ?>][nombre]" value="<?= htmlspecialchars($estilo['nombre']) ?>" class="login-flat-input p-input" style="font-weight:bold; min-width:200px;" required>
                                </div>
                                
                                <div style="display:flex; gap:1.2rem; align-items:center;">
                                    <label class="checkbox-label" style="font-weight:600; font-size:0.9rem;">
                                        <input type="checkbox" name="citas_estilos[<?= htmlspecialchars($slug) ?>][activo]" value="1" <?= !empty($estilo['activo']) ? 'checked' : '' ?>> Activo
                                    </label>
                                    <button type="button" class="btn-icon btn-delete" title="Eliminar estilo" onclick="eliminarFormatoCita('<?= htmlspecialchars($slug) ?>')">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <div style="margin-bottom: 0.6rem; display:flex; gap:0.4rem; flex-wrap:wrap; align-items:center;">
                                <small class="font-bold" style="color:var(--color-secundario);">Insertar variable:</small>
                                <?php foreach(['{autores}','{anio}','{titulo}','{editorial}','{volumen}','{numero}','{issn}'] as $var): ?>
                                    <span class="var-badge" onclick="insertarVar('input_tpl_<?= htmlspecialchars($slug) ?>', '<?= $var ?>')"><?= $var ?></span>
                                <?php endforeach; ?>
                            </div>
                            <input type="text" id="input_tpl_<?= htmlspecialchars($slug) ?>" name="citas_estilos[<?= htmlspecialchars($slug) ?>][plantilla]" value="<?= htmlspecialchars($estilo['plantilla']) ?>" class="login-flat-input w-100 p-input" style="font-family:monospace; font-size:0.85rem;">
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Nueva Cita -->
                <div class="mt-1-5" style="border-top: 1px dashed rgba(0,0,0,0.12); padding-top: 1.5rem;">
                    <h4 class="text-tertiary" style="margin-bottom:0.8rem;"><i class="ph-bold ph-plus-circle"></i> Agregar Nuevo Formato de Cita</h4>
                    <div class="grid-2-cols">
                        <div class="form-group">
                            <label class="font-bold" style="font-size:0.85rem;">Slug del Formato (Identificador único)</label>
                            <input type="text" name="nuevo_estilo_slug" placeholder="Ej. chicago, harvard" class="login-flat-input w-100 p-input">
                        </div>
                        <div class="form-group">
                            <label class="font-bold" style="font-size:0.85rem;">Nombre Visible</label>
                            <input type="text" name="nuevo_estilo_nombre" placeholder="Ej. Estilo Chicago 17ª Ed." class="login-flat-input w-100 p-input">
                        </div>
                    </div>
                    <div class="form-group mt-1">
                        <label class="font-bold" style="font-size:0.85rem;">Plantilla del Estilo</label>
                        <input type="text" id="nuevo_tpl" name="nuevo_estilo_plantilla" placeholder="Ej. {autores} ({anio}). {titulo}. Editorial {editorial}." class="login-flat-input w-100 p-input" style="font-family:monospace; font-size:0.85rem;">
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB PAGINACIÓN -->
        <div id="tabPaginacion" class="config-tab-pane" style="display:none;">
            <div class="pst-config-card">
                <h3 class="text-tertiary mb-1"><i class="ph-bold ph-list-numbers"></i> Parámetros de Paginación y Consultas</h3>
                <p class="text-muted mb-2" style="font-size:0.85rem;">Ajuste el número de elementos a recuperar del servidor por bloque para mantener el rendimiento ligero.</p>
                
                <div class="grid-2-cols">
                    <div class="form-group mb-1">
                        <label class="font-bold">Artículos en Catálogo Público (Grid Revista)</label>
                        <input type="number" name="limite_catalogo" value="<?= (int)($config['paginacion']['limite_catalogo'] ?? 16) ?>" min="1" class="login-flat-input w-100 p-input">
                        <small class="text-muted">Cantidad de artículos por página en la vitrina pública.</small>
                    </div>
                    <div class="form-group mb-1">
                        <label class="font-bold">Artículos en Gestor Administrativo (Tabla)</label>
                        <input type="number" name="limite_gestor" value="<?= (int)($config['paginacion']['limite_gestor'] ?? 15) ?>" min="1" class="login-flat-input w-100 p-input">
                        <small class="text-muted">Cantidad de filas a listar por página en la tabla del gestor.</small>
                    </div>
                    <div class="form-group mt-1">
                        <label class="font-bold">Máximo de Artículos Recomendados</label>
                        <input type="number" name="max_recomendados" value="<?= (int)($config['paginacion']['max_recomendados'] ?? 3) ?>" min="0" class="login-flat-input w-100 p-input">
                        <small class="text-muted">Artículos sugeridos en la barra lateral del lector.</small>
                    </div>
                    <div class="form-group mt-1">
                        <label class="font-bold">Año Mínimo en Filtro del Catálogo</label>
                        <input type="number" name="anio_minimo" value="<?= (int)($config['buscador']['anio_minimo'] ?? 2020) ?>" class="login-flat-input w-100 p-input">
                        <small class="text-muted">Límite inferior para el menú selector de años.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB METADATOS -->
        <div id="tabRecursos" class="config-tab-pane" style="display:none;">
            <div class="pst-config-card">
                <h3 class="text-tertiary mb-1"><i class="ph-bold ph-eye"></i> Visibilidad de Metadatos en la Ficha Técnica</h3>
                <p class="text-muted mb-2" style="font-size:0.85rem;">Active o desactive los distintivos informativos que se muestran en el catálogo e impresiones de artículos.</p>
                
                <div style="display:flex; flex-direction:column; gap:1rem;">
                    <label class="checkbox-label p-1 box-outlined" style="border-radius:8px; display:flex; align-items:center; gap:0.75rem; cursor:pointer;">
                        <input type="checkbox" name="mostrar_editorial" value="1" <?= !empty($config['recursos']['mostrar_editorial']) ? 'checked' : '' ?> style="transform:scale(1.2);">
                        <div>
                            <strong>Mostrar Editorial / Repositorio Institucional</strong>
                            <p class="text-muted" style="margin:0.1rem 0 0 0; font-size:0.8rem;">Muestra la fuente o la casa editorial que avala la investigación.</p>
                        </div>
                    </label>

                    <label class="checkbox-label p-1 box-outlined" style="border-radius:8px; display:flex; align-items:center; gap:0.75rem; cursor:pointer;">
                        <input type="checkbox" name="mostrar_volumen" value="1" <?= !empty($config['recursos']['mostrar_volumen']) ? 'checked' : '' ?> style="transform:scale(1.2);">
                        <div>
                            <strong>Mostrar Volumen y Número de Edición</strong>
                            <p class="text-muted" style="margin:0.1rem 0 0 0; font-size:0.8rem;">Imprime el distintivo del volumen (ej. Vol. 5 - Núm. 2) en cada tarjeta.</p>
                        </div>
                    </label>

                    <label class="checkbox-label p-1 box-outlined" style="border-radius:8px; display:flex; align-items:center; gap:0.75rem; cursor:pointer;">
                        <input type="checkbox" name="mostrar_issn" value="1" <?= !empty($config['recursos']['mostrar_issn']) ? 'checked' : '' ?> style="transform:scale(1.2);">
                        <div>
                            <strong>Mostrar Código de Registro ISSN</strong>
                            <p class="text-muted" style="margin:0.1rem 0 0 0; font-size:0.8rem;">Habilita la impresión del identificador estándar internacional.</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- TAB ARCHIVOS -->
        <div id="tabArchivos" class="config-tab-pane" style="display:none;">
            <div class="pst-config-card">
                <h3 class="text-tertiary mb-1"><i class="ph-bold ph-image"></i> Restricciones de Carga e Imágenes</h3>
                <p class="text-muted mb-2" style="font-size:0.85rem;">Gestione la seguridad y los límites de subida para prevenir saturación del almacenamiento.</p>

                <div class="grid-2-cols">
                    <div class="form-group">
                        <label class="font-bold">Peso Máximo por Imagen (MB)</label>
                        <input type="number" name="max_size_mb" value="<?= (int)($config['archivos']['max_size_mb'] ?? 5) ?>" min="1" class="login-flat-input w-100 p-input">
                        <small class="text-muted">Límite por portada subida en los formularios.</small>
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

        <button type="submit" class="btn btn-primary w-100 mt-2 btn-large justify-center" style="box-shadow: 0 10px 25px rgba(0, 34, 68, 0.2); transition: transform 0.2s ease-out;">
            <i class="ph-bold ph-floppy-disk"></i> Guardar Ajustes de la Revista
        </button>
    </form>
</div>

<script>
function switchConfigTab(tabId, btn) {
    document.querySelectorAll('.config-tab-pane').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.pst-config-nav-tabs button').forEach(b => {
        b.classList.remove('active');
    });
    
    document.getElementById(tabId).style.display = 'block';
    btn.classList.add('active');
}

function insertarVar(inputId, text) {
    const input = document.getElementById(inputId);
    input.value += text;
    input.focus();
}

function eliminarFormatoCita(slug) {
    if(confirm('¿Eliminar formato de cita? Se aplicará al guardar.')){
        const box = document.getElementById('citation_box_' + slug);
        box.style.opacity = '0.3';
        box.style.transform = 'scale(0.98)';
        const h = document.createElement('input');
        h.type = 'hidden'; h.name = 'eliminar_estilo'; h.value = slug;
        document.getElementById('configRevistaForm').appendChild(h);
    }
}
</script>