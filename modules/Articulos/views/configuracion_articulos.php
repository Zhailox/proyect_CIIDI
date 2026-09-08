<div class="gestor-art-container">

    
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
            <div class="pst-config-card mb-2">
                <h3 class="text-tertiary mb-1"><i class="ph-bold ph-sliders-horizontal"></i> Restricciones de Carga e Imágenes</h3>
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

            <!-- GALERÍA GRÁFICA DEL STORAGE -->
            <div class="pst-config-card">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1.2rem; flex-wrap:wrap; gap:0.5rem; border-bottom:1px solid rgba(0,0,0,0.06); padding-bottom:0.8rem;">
                    <div>
                        <h3 class="text-tertiary" style="margin:0;"><i class="ph-bold ph-folder-open"></i> Gestor del Almacenamiento de Imágenes</h3>
                        <p class="text-muted" style="font-size:0.85rem; margin:0.2rem 0 0 0;">Explore y administre gráficamente las portadas guardadas en <code>storage/uploads/articulos</code>.</p>
                    </div>
                    <span style="background:rgba(112, 144, 203, 0.15); color:var(--color-secundario); font-weight:bold; font-size:0.85rem; padding:0.3rem 0.8rem; border-radius:20px;">
                        Total: <?= count($imagenesStorage ?? []) ?> imágenes
                    </span>
                </div>

                <?php if (empty($imagenesStorage)): ?>
                    <p class="text-muted" style="text-align:center; padding:2rem; font-style:italic;">No hay imágenes almacenadas en el directorio de artículos.</p>
                <?php else: ?>
                    <div id="galeriaStorageContainer" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.25rem;">
                        <!-- Renderizado vía JS paginado -->
                    </div>

                    <div id="paginacionStorageControls" class="pagination" style="margin-top: 1.25rem;">
                        <!-- Botones de páginas -->
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary btn-large" style="border-radius: 6px; padding: 0.6rem 1.5rem; font-size: 0.85rem; box-shadow: 0 6px 16px rgba(80, 89, 132, 0.22); display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="ph-bold ph-floppy-disk"></i> Guardar Ajustes de la Revista
            </button>
        </div>
    </form>
</div>




<!-- MODAL VISOR DE IMAGEN (IN-SITU) -->
<div id="modalVisorImagenStorage" class="art-modal-overlay" style="display: none; z-index: 99999;">
    <div class="art-modal-box" style="max-width: 650px; text-align: center; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(16px); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.7);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(0,0,0,0.08); padding-bottom: 0.6rem; margin-bottom: 1rem;">
            <h4 id="modalVisorTitulo" style="margin: 0; font-size: 0.95rem; color: var(--texto-titulos); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 85%;">
                Vista Previa de Imagen
            </h4>
            <button type="button" onclick="cerrarModalVisorImagen()" style="background: none; border: none; font-size: 1.3rem; cursor: pointer; color: var(--texto-silenciado);">&times;</button>
        </div>
        
        <div style="width: 100%; max-height: 420px; overflow: hidden; border-radius: 8px; background: #0f172a; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
            <img id="modalVisorImgSrc" src="" alt="Vista previa" style="max-width: 100%; max-height: 420px; object-fit: contain;">
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
            <a id="modalVisorEnlaceExt" href="" target="_blank" class="btn btn-secondary" style="font-size: 0.85rem;">
                <i class="ph-bold ph-arrow-square-out"></i> Abrir en Pestaña Nueva
            </a>
            <button type="button" class="btn btn-primary" onclick="cerrarModalVisorImagen()" style="font-size: 0.85rem;">
                Cerrar
            </button>
        </div>
    </div>
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

async function eliminarImagenStorage(nombre, cardId) {
    if (!confirm(`¿Está seguro de eliminar la imágen "${nombre}" del almacenamiento del servidor? Esta acción no se puede deshacer.`)) {
        return;
    }

    const card = document.getElementById(cardId);
    if (card) card.style.opacity = '0.4';

    try {
        const csrfToken = document.querySelector('input[name="csrf_token"]').value;
        const res = await fetch('api-eliminar-imagen-articulos', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                csrf_token: csrfToken,
                nombre: nombre
            })
        });

        const data = await res.json();
        if (data.success) {
            if (card) {
                card.style.transform = 'scale(0.8)';
                card.style.opacity = '0';
                setTimeout(() => card.remove(), 300);
            }
        } else {
            alert('No se pudo eliminar la imagen: ' + (data.error || 'Error desconocido'));
            if (card) card.style.opacity = '1';
        }
    } catch (e) {
        alert('Ocurrió un error de red al intentar eliminar la imagen.');
        if (card) card.style.opacity = '1';
    }
}

function abrirModalVisorImagen(url, nombre) {
    document.getElementById('modalVisorTitulo').textContent = 'Vista Previa: ' + nombre;
    document.getElementById('modalVisorImgSrc').src = url;
    document.getElementById('modalVisorEnlaceExt').href = url;
    document.getElementById('modalVisorImagenStorage').style.display = 'flex';
}

function cerrarModalVisorImagen() {
    document.getElementById('modalVisorImagenStorage').style.display = 'none';
    document.getElementById('modalVisorImgSrc').src = '';
}

// Paginación JavaScript del Gestor de Almacenamiento
const imagenesDataStorage = <?= json_encode($imagenesStorage ?? [], JSON_UNESCAPED_UNICODE) ?>;
const itemsPorPaginaStorage = 8;
let paginaActualStorage = 1;

function renderizarGaleriaStorage(pagina) {
    const container = document.getElementById('galeriaStorageContainer');
    const controls = document.getElementById('paginacionStorageControls');
    if (!container || !imagenesDataStorage.length) return;

    paginaActualStorage = pagina;
    const totalPaginas = Math.ceil(imagenesDataStorage.length / itemsPorPaginaStorage);
    const inicio = (pagina - 1) * itemsPorPaginaStorage;
    const fin = inicio + itemsPorPaginaStorage;
    const itemsPagina = imagenesDataStorage.slice(inicio, fin);

    let html = '';
    itemsPagina.forEach(img => {
        const badge = img.es_default 
            ? '<span style="background: #3b82f6; color: white; font-size: 0.65rem; font-weight: 700; padding: 0.15rem 0.4rem; border-radius: 4px; box-shadow:0 2px 4px rgba(0,0,0,0.15);">Sistema</span>'
            : (img.en_uso 
                ? '<span style="background: #10b981; color: white; font-size: 0.65rem; font-weight: 700; padding: 0.15rem 0.4rem; border-radius: 4px; box-shadow:0 2px 4px rgba(0,0,0,0.15);">En uso</span>'
                : '<span style="background: #f59e0b; color: white; font-size: 0.65rem; font-weight: 700; padding: 0.15rem 0.4rem; border-radius: 4px; box-shadow:0 2px 4px rgba(0,0,0,0.15);">Sin uso</span>');

        const btnBorrar = (!img.en_uso && !img.es_default)
            ? `<button type="button" class="btn-icon btn-delete" style="padding: 0.3rem 0.5rem;" title="Eliminar imagen huérfana" onclick="eliminarImagenStorage('${img.nombre}', 'card_img_${md5JS(img.nombre)}')"><i class="ph-bold ph-trash"></i></button>`
            : `<button type="button" class="btn-icon" style="padding: 0.3rem 0.5rem; opacity: 0.3; cursor: not-allowed;" title="${img.es_default ? 'Imagen predeterminada' : 'Pertenece a un artículo'}" disabled><i class="ph-bold ph-lock"></i></button>`;

        const btnArticulo = img.articulo_id 
            ? `<a href="leer-articulo?id=${img.articulo_id}" target="_blank" class="btn btn-secondary" style="padding: 0.3rem 0.5rem; font-size: 0.75rem; display:inline-flex; align-items:center; gap:0.2rem;" title="Ver artículo asignado"><i class="ph-bold ph-newspaper"></i> Artículo</a>`
            : '';

        html += `
            <div class="citation-box-glass" id="card_img_${md5JS(img.nombre)}" style="padding: 0.8rem; display:flex; flex-direction:column; justify-content:space-between; align-items:center; position:relative; overflow:hidden;">
                <div style="width: 100%; height: 130px; border-radius: 6px; overflow: hidden; background: #f1f5f9; margin-bottom: 0.6rem; position: relative;">
                    <img src="${img.url}" alt="${img.nombre}" style="width: 100%; height: 100%; object-fit: cover;">
                    <div style="position: absolute; top: 6px; right: 6px;">${badge}</div>
                </div>
                <div style="width: 100%; text-align: left; margin-bottom: 0.6rem;">
                    <strong style="font-size: 0.8rem; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: var(--texto-titulos);" title="${img.nombre}">${img.nombre}</strong>
                    <span class="text-muted" style="font-size: 0.72rem; display: block; margin-top: 0.15rem;">${img.peso_kb} KB • ${img.fecha}</span>
                </div>
                <div style="width: 100%; display: flex; gap: 0.3rem;">
                    <button type="button" class="btn btn-secondary" style="flex: 1; padding: 0.3rem 0.4rem; font-size: 0.75rem; justify-content: center;" onclick="abrirModalVisorImagen('${img.url}', '${img.nombre}')"><i class="ph-bold ph-eye"></i> Ver</button>
                    ${btnArticulo}
                    ${btnBorrar}
                </div>
            </div>
        `;
    });
    container.innerHTML = html;

    // Renderizado de Controles de Paginación
    if (controls && totalPaginas > 1) {
        let phtml = '';
        if (paginaActualStorage > 1) {
            phtml += `<button type="button" class="page-link" onclick="renderizarGaleriaStorage(${paginaActualStorage - 1})">← Anterior</button>`;
        }
        for (let i = 1; i <= totalPaginas; i++) {
            const activeClass = i === paginaActualStorage ? 'active' : '';
            phtml += `<button type="button" class="page-link ${activeClass}" onclick="renderizarGaleriaStorage(${i})">${i}</button>`;
        }
        if (paginaActualStorage < totalPaginas) {
            phtml += `<button type="button" class="page-link" onclick="renderizarGaleriaStorage(${paginaActualStorage + 1})">Siguiente →</button>`;
        }
        controls.innerHTML = phtml;
    } else if (controls) {
        controls.innerHTML = '';
    }
}

function md5JS(string) {
    let hash = 0;
    for (let i = 0; i < string.length; i++) {
        hash = (hash << 5) - hash + string.charCodeAt(i);
        hash |= 0;
    }
    return Math.abs(hash).toString(16);
}

document.addEventListener('DOMContentLoaded', () => {
    renderizarGaleriaStorage(1);
});
</script>
<script src="../modules/Articulos/assets/js/lazy_loading.js"></script>