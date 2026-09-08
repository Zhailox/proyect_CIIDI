<?php
$mensajeExito = $_SESSION['mensaje_exito'] ?? '';
$mensajeError = $_SESSION['mensaje_error'] ?? '';

if (!empty($mensajeExito)) { ?>
    <script>localStorage.removeItem('catalogos_articulos');</script>
    <?php unset($_SESSION['mensaje_exito']); }
if (!empty($mensajeError)) unset($_SESSION['mensaje_error']);

// Helper para mantener los parámetros GET al cambiar de página o buscar
function urlParam($nuevosParams) {
    $actual = $_GET;
    unset($actual['ruta']);
    return 'gestor-catalogos?' . http_build_query(array_merge($actual, $nuevosParams));
}

// Pestaña activa por defecto o según parámetro en GET
$tabActiva = $_GET['tab'] ?? 'cat';
?>

<div class="gestor-art-container">
    
    <!-- CABECERA DEL GESTOR -->
    <div class="gestor-art-header">
        <div class="gestor-art-title-box">
            <h1><i class="ph-bold ph-tags"></i> Gestor de Catálogos y Autores</h1>
            <p>Administra de manera limpia y modular las categorías, etiquetas, editoriales y autores del módulo.</p>
        </div>
        <a href="gestor-articulos" class="btn btn-secondary" style="padding: 0.45rem 0.85rem; font-size: 0.85rem; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.4rem;">
            <i class="ph-bold ph-arrow-left"></i> Volver al Gestor
        </a>
    </div>

    <?php if (!empty($mensajeExito)): ?>
        <div class="alert-success"><i class="ph-bold ph-check-circle"></i> <?= htmlspecialchars($mensajeExito) ?></div>
    <?php endif; ?>
    <?php if (!empty($mensajeError)): ?>
        <div class="alert-error"><i class="ph-bold ph-warning-circle"></i> <?= htmlspecialchars($mensajeError) ?></div>
    <?php endif; ?>

    <!-- NAVEGACIÓN MODULAR DE PESTAÑAS (ANTIGRAVITY) -->
    <div class="pst-config-nav-tabs">
        <button type="button" class="tab-btn-antigravity <?= $tabActiva === 'cat' ? 'active' : '' ?>" onclick="switchCatalogTab('tabCategorias', this)">
            <i class="ph-bold ph-folder-user"></i> Categorías (<?= $categorias['total'] ?>)
        </button>
        <button type="button" class="tab-btn-antigravity <?= $tabActiva === 'tag' ? 'active' : '' ?>" onclick="switchCatalogTab('tabEtiquetas', this)">
            <i class="ph-bold ph-hash"></i> Etiquetas (<?= $etiquetas['total'] ?>)
        </button>
        <button type="button" class="tab-btn-antigravity <?= $tabActiva === 'edit' ? 'active' : '' ?>" onclick="switchCatalogTab('tabEditoriales', this)">
            <i class="ph-bold ph-buildings"></i> Editoriales / Repositorios (<?= $editoriales['total'] ?>)
        </button>
        <button type="button" class="tab-btn-antigravity <?= $tabActiva === 'aut' ? 'active' : '' ?>" onclick="switchCatalogTab('tabAutores', this)">
            <i class="ph-bold ph-users-three"></i> Directorio de Autores (<?= $autores['total'] ?>)
        </button>
    </div>

    <!-- PESTAÑA 1: CATEGORÍAS -->
    <div id="tabCategorias" class="config-tab-pane" style="display: <?= $tabActiva === 'cat' ? 'block' : 'none' ?>;">
        <div class="gestor-art-card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1.2rem; flex-wrap:wrap; gap:0.5rem; border-bottom: 1px solid rgba(0,0,0,0.06); padding-bottom: 0.75rem;">
                <div>
                    <h3 class="text-tertiary" style="margin:0;"><i class="ph-bold ph-folder"></i> Categorías del Catálogo</h3>
                    <p class="text-muted" style="font-size:0.85rem; margin:0.2rem 0 0 0;">Cree y edite las áreas temáticas principales.</p>
                </div>
                
                <form action="gestor-catalogos" method="GET" style="display:flex; gap:0.4rem; margin:0;">
                    <input type="hidden" name="tab" value="cat">
                    <input type="text" name="q_cat" class="login-flat-input p-input" placeholder="Buscar categoría..." value="<?= htmlspecialchars($busquedas['q_cat']) ?>" style="padding: 0.45rem 0.75rem; font-size:0.85rem; border-radius:6px; min-width: 220px;">
                    <button type="submit" class="btn btn-secondary" style="padding: 0.45rem 0.85rem; font-size:0.85rem; border-radius:6px;">Buscar</button>
                </form>
            </div>

            <!-- Formulario de creación rápida -->
            <form action="gestor-catalogos" method="POST" style="margin-bottom: 1.25rem;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="accion" value="crear_categoria">
                <div style="display:flex; gap:0.5rem; max-width: 500px;">
                    <input type="text" name="nombre" class="login-flat-input w-100 p-input" placeholder="Nombre de la nueva categoría..." required style="padding: 0.5rem 0.75rem; font-size: 0.85rem; border-radius: 6px;">
                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem; border-radius: 6px; flex-shrink:0;">
                        <i class="ph-bold ph-plus"></i> Añadir
                    </button>
                </div>
            </form>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 0.85rem;">
                <?php if(empty($categorias['data'])): ?>
                    <p class="text-muted" style="grid-column: 1 / -1; text-align:center; padding: 2rem; font-style:italic;">No hay categorías registradas.</p>
                <?php endif; ?>
                <?php foreach ($categorias['data'] as $cat): ?>
                    <div class="citation-box-glass" style="margin-bottom:0; padding: 0.85rem 1rem; display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-weight: 600; font-size: 0.875rem; color: var(--texto-titulos);"><?= htmlspecialchars($cat['nombre']) ?></span>
                        
                        <div style="display:flex; gap: 0.35rem;">
                            <button type="button" class="btn-icon btn-edit" title="Editar categoría" onclick="abrirModalEdicion('actualizar_categoria', <?= (int)$cat['id'] ?>, '<?= htmlspecialchars($cat['nombre'], ENT_QUOTES) ?>')">
                                <i class="ph-bold ph-pencil-simple"></i>
                            </button>

                            <form action="gestor-catalogos" method="POST" style="margin:0;">
                                <input type="hidden" name="accion" value="eliminar_categoria">
                                <input type="hidden" name="id" value="<?= (int)$cat['id'] ?>">
                                <button type="submit" class="btn-icon btn-delete" title="Eliminar categoría" onclick="return confirm('¿Eliminar esta categoría?');">
                                    <i class="ph-bold ph-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if($categorias['paginas'] > 1): ?>
                <div class="pagination" style="margin-top:1.25rem;">
                    <?php if($categorias['pagina_actual'] > 1): ?>
                        <a href="<?= urlParam(['p_cat' => $categorias['pagina_actual'] - 1, 'tab' => 'cat']) ?>" class="page-link">← Anterior</a>
                    <?php endif; ?>
                    
                    <?php for($i=1; $i<=$categorias['paginas']; $i++): ?>
                        <a href="<?= urlParam(['p_cat' => $i, 'tab' => 'cat']) ?>" class="page-link <?= $i === (int)$categorias['pagina_actual'] ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php if($categorias['pagina_actual'] < $categorias['paginas']): ?>
                        <a href="<?= urlParam(['p_cat' => $categorias['pagina_actual'] + 1, 'tab' => 'cat']) ?>" class="page-link">Siguiente →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- PESTAÑA 2: ETIQUETAS -->
    <div id="tabEtiquetas" class="config-tab-pane" style="display: <?= $tabActiva === 'tag' ? 'block' : 'none' ?>;">
        <div class="gestor-art-card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1.2rem; flex-wrap:wrap; gap:0.5rem; border-bottom: 1px solid rgba(0,0,0,0.06); padding-bottom: 0.75rem;">
                <div>
                    <h3 class="text-tertiary" style="margin:0;"><i class="ph-bold ph-hash"></i> Etiquetas de Búsqueda</h3>
                    <p class="text-muted" style="font-size:0.85rem; margin:0.2rem 0 0 0;">Palabras clave específicas para indexación rápida.</p>
                </div>
                
                <form action="gestor-catalogos" method="GET" style="display:flex; gap:0.4rem; margin:0;">
                    <input type="hidden" name="tab" value="tag">
                    <input type="text" name="q_tag" class="login-flat-input p-input" placeholder="Buscar etiqueta..." value="<?= htmlspecialchars($busquedas['q_tag']) ?>" style="padding: 0.45rem 0.75rem; font-size:0.85rem; border-radius:6px; min-width: 220px;">
                    <button type="submit" class="btn btn-secondary" style="padding: 0.45rem 0.85rem; font-size:0.85rem; border-radius:6px;">Buscar</button>
                </form>
            </div>

            <form action="gestor-catalogos" method="POST" style="margin-bottom: 1.25rem;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="accion" value="crear_etiqueta">
                <div style="display:flex; gap:0.5rem; max-width: 500px;">
                    <input type="text" name="nombre" class="login-flat-input w-100 p-input" placeholder="Nombre de la nueva etiqueta..." required style="padding: 0.5rem 0.75rem; font-size: 0.85rem; border-radius: 6px;">
                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem; border-radius: 6px; flex-shrink:0;">
                        <i class="ph-bold ph-plus"></i> Añadir
                    </button>
                </div>
            </form>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 0.85rem;">
                <?php if(empty($etiquetas['data'])): ?>
                    <p class="text-muted" style="grid-column: 1 / -1; text-align:center; padding: 2rem; font-style:italic;">No hay etiquetas registradas.</p>
                <?php endif; ?>
                <?php foreach ($etiquetas['data'] as $tag): ?>
                    <div class="citation-box-glass" style="margin-bottom:0; padding: 0.85rem 1rem; display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-weight: 600; font-size: 0.875rem; color: var(--color-secundario);">#<?= htmlspecialchars($tag['nombre']) ?></span>
                        
                        <div style="display:flex; gap: 0.35rem;">
                            <button type="button" class="btn-icon btn-edit" title="Editar etiqueta" onclick="abrirModalEdicion('actualizar_etiqueta', <?= (int)$tag['id'] ?>, '<?= htmlspecialchars($tag['nombre'], ENT_QUOTES) ?>')">
                                <i class="ph-bold ph-pencil-simple"></i>
                            </button>

                            <form action="gestor-catalogos" method="POST" style="margin:0;">
                                <input type="hidden" name="accion" value="eliminar_etiqueta">
                                <input type="hidden" name="id" value="<?= (int)$tag['id'] ?>">
                                <button type="submit" class="btn-icon btn-delete" title="Eliminar etiqueta" onclick="return confirm('¿Eliminar esta etiqueta?');">
                                    <i class="ph-bold ph-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if($etiquetas['paginas'] > 1): ?>
                <div class="pagination" style="margin-top:1.25rem;">
                    <?php if($etiquetas['pagina_actual'] > 1): ?>
                        <a href="<?= urlParam(['p_tag' => $etiquetas['pagina_actual'] - 1, 'tab' => 'tag']) ?>" class="page-link">← Anterior</a>
                    <?php endif; ?>
                    
                    <?php for($i=1; $i<=$etiquetas['paginas']; $i++): ?>
                        <a href="<?= urlParam(['p_tag' => $i, 'tab' => 'tag']) ?>" class="page-link <?= $i === (int)$etiquetas['pagina_actual'] ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php if($etiquetas['pagina_actual'] < $etiquetas['paginas']): ?>
                        <a href="<?= urlParam(['p_tag' => $etiquetas['pagina_actual'] + 1, 'tab' => 'tag']) ?>" class="page-link">Siguiente →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- PESTAÑA 3: EDITORIALES -->
    <div id="tabEditoriales" class="config-tab-pane" style="display: <?= $tabActiva === 'edit' ? 'block' : 'none' ?>;">
        <div class="gestor-art-card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1.2rem; flex-wrap:wrap; gap:0.5rem; border-bottom: 1px solid rgba(0,0,0,0.06); padding-bottom: 0.75rem;">
                <div>
                    <h3 class="text-tertiary" style="margin:0;"><i class="ph-bold ph-buildings"></i> Editoriales y Repositorios Institucionales</h3>
                    <p class="text-muted" style="font-size:0.85rem; margin:0.2rem 0 0 0;">Instituciones que avalan las publicaciones.</p>
                </div>
                
                <form action="gestor-catalogos" method="GET" style="display:flex; gap:0.4rem; margin:0;">
                    <input type="hidden" name="tab" value="edit">
                    <input type="text" name="q_edit" class="login-flat-input p-input" placeholder="Buscar editorial..." value="<?= htmlspecialchars($busquedas['q_edit']) ?>" style="padding: 0.45rem 0.75rem; font-size:0.85rem; border-radius:6px; min-width: 220px;">
                    <button type="submit" class="btn btn-secondary" style="padding: 0.45rem 0.85rem; font-size:0.85rem; border-radius:6px;">Buscar</button>
                </form>
            </div>

            <form action="gestor-catalogos" method="POST" style="margin-bottom: 1.25rem;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="accion" value="crear_editorial">
                <div style="display:flex; gap:0.5rem; max-width: 500px;">
                    <input type="text" name="nombre" class="login-flat-input w-100 p-input" placeholder="Nombre del nuevo editorial/repositorio..." required style="padding: 0.5rem 0.75rem; font-size: 0.85rem; border-radius: 6px;">
                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem; border-radius: 6px; flex-shrink:0;">
                        <i class="ph-bold ph-plus"></i> Añadir
                    </button>
                </div>
            </form>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 0.85rem;">
                <?php if(empty($editoriales['data'])): ?>
                    <p class="text-muted" style="grid-column: 1 / -1; text-align:center; padding: 2rem; font-style:italic;">No hay editoriales registradas.</p>
                <?php endif; ?>
                <?php foreach ($editoriales['data'] as $edit): ?>
                    <div class="citation-box-glass" style="margin-bottom:0; padding: 0.85rem 1rem; display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-weight: 600; font-size: 0.875rem; color: var(--texto-titulos);"><?= htmlspecialchars($edit['nombre']) ?></span>
                        
                        <div style="display:flex; gap: 0.35rem;">
                            <button type="button" class="btn-icon btn-edit" title="Editar editorial" onclick="abrirModalEdicion('actualizar_editorial', <?= (int)$edit['id'] ?>, '<?= htmlspecialchars($edit['nombre'], ENT_QUOTES) ?>')">
                                <i class="ph-bold ph-pencil-simple"></i>
                            </button>

                            <form action="gestor-catalogos" method="POST" style="margin:0;">
                                <input type="hidden" name="accion" value="eliminar_editorial">
                                <input type="hidden" name="id" value="<?= (int)$edit['id'] ?>">
                                <button type="submit" class="btn-icon btn-delete" title="Eliminar editorial" onclick="return confirm('¿Eliminar esta editorial?');">
                                    <i class="ph-bold ph-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if($editoriales['paginas'] > 1): ?>
                <div class="pagination" style="margin-top:1.25rem;">
                    <?php if($editoriales['pagina_actual'] > 1): ?>
                        <a href="<?= urlParam(['p_edit' => $editoriales['pagina_actual'] - 1, 'tab' => 'edit']) ?>" class="page-link">← Anterior</a>
                    <?php endif; ?>
                    
                    <?php for($i=1; $i<=$editoriales['paginas']; $i++): ?>
                        <a href="<?= urlParam(['p_edit' => $i, 'tab' => 'edit']) ?>" class="page-link <?= $i === (int)$editoriales['pagina_actual'] ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php if($editoriales['pagina_actual'] < $editoriales['paginas']): ?>
                        <a href="<?= urlParam(['p_edit' => $editoriales['pagina_actual'] + 1, 'tab' => 'edit']) ?>" class="page-link">Siguiente →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- PESTAÑA 4: AUTORES (REDISEÑO MODULAR COMPACTO Y EDITABLE DE FORMA FÁCIL) -->
    <div id="tabAutores" class="config-tab-pane" style="display: <?= $tabActiva === 'aut' ? 'block' : 'none' ?>;">
        <div class="gestor-art-card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1.2rem; flex-wrap:wrap; gap:0.5rem; border-bottom: 1px solid rgba(0,0,0,0.06); padding-bottom: 0.75rem;">
                <div>
                    <h3 class="text-tertiary" style="margin:0;"><i class="ph-bold ph-users-three"></i> Directorio de Autores Registrados</h3>
                    <p class="text-muted" style="font-size:0.85rem; margin:0.2rem 0 0 0;">Gestión de la cédula y nombre de los investigadores vinculados a artículos.</p>
                </div>

                <form action="gestor-catalogos" method="GET" style="display:flex; gap:0.4rem; margin:0;">
                    <input type="hidden" name="tab" value="aut">
                    <input type="text" name="q_aut" class="login-flat-input p-input" placeholder="Nombre o cédula..." value="<?= htmlspecialchars($busquedas['q_aut']) ?>" style="padding: 0.45rem 0.75rem; font-size:0.85rem; border-radius:6px; min-width: 240px;">
                    <button type="submit" class="btn btn-secondary" style="padding: 0.45rem 0.85rem; font-size:0.85rem; border-radius:6px;">Buscar</button>
                </form>
            </div>

            <!-- Tabla Profesional de Autores -->
            <div class="table-responsive">
                <table class="gestor-art-table">
                    <thead>
                        <tr>
                            <th>Nombre del Autor</th>
                            <th>Cédula</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($autores['data'])): ?>
                            <tr>
                                <td colspan="3" class="gestor-art-empty">No hay autores registrados <?= !empty($busquedas['q_aut']) ? 'para "' . htmlspecialchars($busquedas['q_aut']) . '"' : '' ?>.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($autores['data'] as $autor): ?>
                                <tr>
                                    <td>
                                        <strong style="color: var(--texto-titulos); font-size: 0.9rem;">
                                            <i class="ph-bold ph-user" style="color: var(--color-terciario); margin-right: 0.3rem;"></i>
                                            <?= htmlspecialchars($autor['nombre_completo']) ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="var-badge" style="cursor: default;">
                                            <?= !empty($autor['cedula']) ? htmlspecialchars($autor['cedula']) : 'Sin Cédula' ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div style="display: inline-flex; gap: 0.35rem; align-items: center; justify-content: center;">
                                            <button type="button" class="btn btn-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.8rem; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.3rem;" onclick="abrirModalEdicionAutor(<?= (int)$autor['id'] ?>, '<?= htmlspecialchars($autor['nombre_completo'], ENT_QUOTES) ?>', '<?= htmlspecialchars($autor['cedula'] ?? '', ENT_QUOTES) ?>')">
                                                <i class="ph-bold ph-pencil-simple"></i> Editar
                                            </button>

                                            <form action="gestor-catalogos" method="POST" style="margin: 0; display: inline;">
                                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                                <input type="hidden" name="accion" value="eliminar_autor">
                                                <input type="hidden" name="tab" value="aut">
                                                <input type="hidden" name="id" value="<?= (int)$autor['id'] ?>">
                                                <button type="submit" class="btn-icon btn-delete" style="width: 30px; height: 30px;" title="Eliminar autor" onclick="return confirm('¿Está seguro de eliminar al autor «<?= htmlspecialchars($autor['nombre_completo'], ENT_QUOTES) ?>»?');">
                                                    <i class="ph-bold ph-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if(!empty($autores['paginas']) && $autores['paginas'] > 1): ?>
                <div class="pagination" style="margin-top: 1.25rem;">
                    <?php if($autores['pagina_actual'] > 1): ?>
                        <a href="<?= urlParam(['p_aut' => $autores['pagina_actual'] - 1, 'tab' => 'aut']) ?>" class="page-link">← Anterior</a>
                    <?php endif; ?>

                    <?php for($i=1; $i<=$autores['paginas']; $i++): ?>
                        <a href="<?= urlParam(['p_aut' => $i, 'tab' => 'aut']) ?>" class="page-link <?= $i === (int)$autores['pagina_actual'] ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php if($autores['pagina_actual'] < $autores['paginas']): ?>
                        <a href="<?= urlParam(['p_aut' => $autores['pagina_actual'] + 1, 'tab' => 'aut']) ?>" class="page-link">Siguiente →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- MODAL EDICIÓN RÁPIDA (CATEGORÍAS / ETIQUETAS / EDITORIALES) -->
<div id="modal-edicion-catalogo" class="art-modal-overlay" style="display: none;">
    <div class="art-modal-box" style="border-radius: 8px; max-width: 420px; padding: 1.5rem;">
        <h3 class="text-secondary mt-0 box-title-border" style="font-size: 1.1rem; margin-bottom: 0.5rem;"><i class="ph-bold ph-pencil-simple"></i> Editar Registro</h3>
        <p class="text-muted mb-2" style="font-size:0.85rem;">Modifique la información. Se actualizará en todas las referencias vinculadas.</p>

        <form id="form-editar-catalogo" method="POST" action="gestor-catalogos" class="m-0">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="accion" id="edit-accion">
            <input type="hidden" name="id" id="edit-id">

            <div class="form-group mt-1">
                <label class="font-bold" style="font-size:0.85rem;">Nuevo Nombre *</label>
                <input type="text" name="nombre" id="edit-nombre" class="login-flat-input w-100 p-input" required style="padding: 0.55rem 0.75rem; border-radius:6px; font-size:0.875rem;">
            </div>

            <div class="modal-actions mt-1-5" style="display:flex; justify-content:flex-end; gap:0.5rem;">
                <button type="button" class="btn btn-secondary" onclick="cerrarModalEdicion()" style="padding: 0.45rem 1rem; border-radius:6px; font-size:0.85rem;">Cancelar</button>
                <button type="submit" class="btn btn-primary" style="padding: 0.45rem 1.2rem; border-radius:6px; font-size:0.85rem;">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDICIÓN AUTOR (NUEVO) -->
<div id="modal-edicion-autor" class="art-modal-overlay" style="display: none;">
    <div class="art-modal-box" style="border-radius: 8px; max-width: 440px; padding: 1.5rem;">
        <h3 class="text-secondary mt-0 box-title-border" style="font-size: 1.1rem; margin-bottom: 0.5rem;"><i class="ph-bold ph-user-focus"></i> Editar Datos de Autor</h3>
        <p class="text-muted mb-2" style="font-size:0.85rem;">Actualice la cédula o el nombre completo del investigador.</p>

        <form method="POST" action="gestor-catalogos" class="m-0">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="accion" value="actualizar_autor">
            <input type="hidden" name="id" id="edit-autor-id">

            <div class="form-group mt-1">
                <label class="font-bold" style="font-size:0.85rem;">Nombre Completo *</label>
                <input type="text" name="nombre_completo" id="edit-autor-nombre" class="login-flat-input w-100 p-input" required style="padding: 0.55rem 0.75rem; border-radius:6px; font-size:0.875rem;">
            </div>

            <div class="form-group mt-1">
                <label class="font-bold" style="font-size:0.85rem;">Cédula (Opcional)</label>
                <input type="text" name="cedula" id="edit-autor-cedula" class="login-flat-input w-100 p-input" placeholder="V-12345678" style="padding: 0.55rem 0.75rem; border-radius:6px; font-size:0.875rem;">
            </div>

            <div class="modal-actions mt-1-5" style="display:flex; justify-content:flex-end; gap:0.5rem;">
                <button type="button" class="btn btn-secondary" onclick="cerrarModalEdicionAutor()" style="padding: 0.45rem 1rem; border-radius:6px; font-size:0.85rem;">Cancelar</button>
                <button type="submit" class="btn btn-primary" style="padding: 0.45rem 1.2rem; border-radius:6px; font-size:0.85rem;">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
function switchCatalogTab(tabId, btn) {
    document.querySelectorAll('.config-tab-pane').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.pst-config-nav-tabs button').forEach(b => {
        b.classList.remove('active');
    });
    
    document.getElementById(tabId).style.display = 'block';
    btn.classList.add('active');
}

function abrirModalEdicion(accion, id, nombreActual) {
    document.getElementById('edit-accion').value = accion;
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-nombre').value = nombreActual;
    document.getElementById('modal-edicion-catalogo').style.display = 'flex';
    setTimeout(() => document.getElementById('edit-nombre').focus(), 100);
}

function cerrarModalEdicion() {
    document.getElementById('modal-edicion-catalogo').style.display = 'none';
}

function abrirModalEdicionAutor(id, nombre, cedula) {
    document.getElementById('edit-autor-id').value = id;
    document.getElementById('edit-autor-nombre').value = nombre;
    document.getElementById('edit-autor-cedula').value = cedula;
    document.getElementById('modal-edicion-autor').style.display = 'flex';
    setTimeout(() => document.getElementById('edit-autor-nombre').focus(), 100);
}

function cerrarModalEdicionAutor() {
    document.getElementById('modal-edicion-autor').style.display = 'none';
}
</script>
