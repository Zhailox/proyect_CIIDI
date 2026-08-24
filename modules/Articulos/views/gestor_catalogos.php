<?php
$mensajeExito = $_SESSION['mensaje_exito'] ?? '';
$mensajeError = $_SESSION['mensaje_error'] ?? '';

if (!empty($mensajeExito)) unset($_SESSION['mensaje_exito']);
if (!empty($mensajeError)) unset($_SESSION['mensaje_error']);

// Helper para mantener los parámetros GET al cambiar de página o buscar
function urlParam($nuevosParams) {
    $actual = $_GET;
    unset($actual['ruta']); // Limpiamos la ruta si tu framework la inyecta
    return 'gestor-catalogos?' . http_build_query(array_merge($actual, $nuevosParams));
}
?>

<div class="gestor-art-container">
    <div class="gestor-art-header box-outlined">
        <div class="gestor-art-title-box">
            <h1 class="text-secondary">Gestor de Catálogos</h1>
            <p class="text-muted">Administra categorías, etiquetas, editoriales y autores.</p>
        </div>
        <a href="gestor-articulos" class="btn btn-secondary">
            <i class="ph-bold ph-arrow-left"></i> Volver al gestor
        </a>
    </div>

    <?php if (!empty($mensajeExito)): ?>
        <div class="alert-success"><i class="ph-bold ph-check-circle"></i> <?= htmlspecialchars($mensajeExito) ?></div>
    <?php endif; ?>
    <?php if (!empty($mensajeError)): ?>
        <div class="alert-error"><i class="ph-bold ph-warning-circle"></i> <?= htmlspecialchars($mensajeError) ?></div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">

        <div class="gestor-art-card">
            <h3 class="card-subtitle text-tertiary">Categorías</h3>
            
            <form action="gestor-catalogos" method="GET" style="display:flex; gap:0.5rem; margin-bottom: 1rem;">
                <?php foreach($_GET as $k => $v): if($k !== 'q_cat' && $k !== 'p_cat' && $k !== 'ruta'): ?>
                    <input type="hidden" name="<?= htmlspecialchars($k) ?>" value="<?= htmlspecialchars($v) ?>">
                <?php endif; endforeach; ?>
                <input type="text" name="q_cat" class="login-flat-input p-input" placeholder="Buscar categoría..." value="<?= htmlspecialchars($busquedas['q_cat']) ?>" style="flex-grow:1; padding: 0.5rem;">
                <button type="submit" class="btn btn-secondary">Buscar</button>
            </form>

            <form action="gestor-catalogos" method="POST" class="mt-1">
                <input type="hidden" name="accion" value="crear_categoria">
                <div class="form-group" style="display:flex; gap:0.5rem;">
                    <input type="text" name="nombre" class="login-flat-input w-100 p-input" placeholder="Nueva categoría..." required style="padding: 0.5rem;">
                    <button type="submit" class="btn btn-primary">+</button>
                </div>
            </form>

            <div class="mt-1-5">
                <?php if(empty($categorias['data'])): ?>
                    <p class="text-muted" style="text-align:center;">No hay resultados.</p>
                <?php endif; ?>
                <?php foreach ($categorias['data'] as $cat): ?>
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:0.6rem 0; border-bottom:1px solid rgba(0,0,0,0.05);">
                        <span><?= htmlspecialchars($cat['nombre']) ?></span>
                        
                        <div style="display:flex; gap: 0.3rem;">
                            <button type="button" class="btn-icon btn-edit" style="padding:0.2rem 0.5rem;" onclick="abrirModalEdicion('actualizar_categoria', <?= (int)$cat['id'] ?>, '<?= htmlspecialchars($cat['nombre'], ENT_QUOTES) ?>')">
                                <i class="ph-bold ph-pencil-simple"></i>
                            </button>

                            <form action="gestor-catalogos" method="POST" style="margin:0;">
                                <input type="hidden" name="accion" value="eliminar_categoria">
                                <input type="hidden" name="id" value="<?= (int)$cat['id'] ?>">
                                <button type="submit" class="btn-icon btn-delete" style="padding:0.2rem 0.5rem;" onclick="return confirm('¿Eliminar esta categoría?');"><i class="ph-bold ph-trash"></i></button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if($categorias['paginas'] > 1): ?>
                <div style="display:flex; justify-content:center; gap:0.5rem; margin-top:1rem;">
                    <?php if($categorias['pagina_actual'] > 1): ?>
                        <a href="<?= urlParam(['p_cat' => $categorias['pagina_actual'] - 1]) ?>" class="btn btn-secondary" style="padding:0.2rem 0.6rem;">&laquo;</a>
                    <?php endif; ?>
                    <span style="font-size:0.9rem; align-self:center;">Pág <?= $categorias['pagina_actual'] ?> de <?= $categorias['paginas'] ?></span>
                    <?php if($categorias['pagina_actual'] < $categorias['paginas']): ?>
                        <a href="<?= urlParam(['p_cat' => $categorias['pagina_actual'] + 1]) ?>" class="btn btn-secondary" style="padding:0.2rem 0.6rem;">&raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="gestor-art-card">
            <h3 class="card-subtitle text-tertiary">Etiquetas</h3>
            
            <form action="gestor-catalogos" method="GET" style="display:flex; gap:0.5rem; margin-bottom: 1rem;">
                <?php foreach($_GET as $k => $v): if($k !== 'q_tag' && $k !== 'p_tag' && $k !== 'ruta'): ?>
                    <input type="hidden" name="<?= htmlspecialchars($k) ?>" value="<?= htmlspecialchars($v) ?>">
                <?php endif; endforeach; ?>
                <input type="text" name="q_tag" class="login-flat-input p-input" placeholder="Buscar etiqueta..." value="<?= htmlspecialchars($busquedas['q_tag']) ?>" style="flex-grow:1; padding: 0.5rem;">
                <button type="submit" class="btn btn-secondary">Buscar</button>
            </form>

            <form action="gestor-catalogos" method="POST" class="mt-1">
                <input type="hidden" name="accion" value="crear_etiqueta">
                <div class="form-group" style="display:flex; gap:0.5rem;">
                    <input type="text" name="nombre" class="login-flat-input w-100 p-input" placeholder="Nueva etiqueta..." required style="padding: 0.5rem;">
                    <button type="submit" class="btn btn-primary">+</button>
                </div>
            </form>

            <div class="mt-1-5">
                <?php if(empty($etiquetas['data'])): ?>
                    <p class="text-muted" style="text-align:center;">No hay resultados.</p>
                <?php endif; ?>
                <?php foreach ($etiquetas['data'] as $tag): ?>
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:0.6rem 0; border-bottom:1px solid rgba(0,0,0,0.05);">
                        <span><?= htmlspecialchars($tag['nombre']) ?></span>
                        <div style="display:flex; gap: 0.3rem;">
                            <button type="button" class="btn-icon btn-edit" style="padding:0.2rem 0.5rem;" onclick="abrirModalEdicion('actualizar_etiqueta', <?= (int)$tag['id'] ?>, '<?= htmlspecialchars($tag['nombre'], ENT_QUOTES) ?>')">
                                <i class="ph-bold ph-pencil-simple"></i>
                            </button>
                        <form action="gestor-catalogos" method="POST" style="margin:0;">
                            <input type="hidden" name="accion" value="eliminar_etiqueta">
                            <input type="hidden" name="id" value="<?= (int)$tag['id'] ?>">
                            <button type="submit" class="btn-icon btn-delete" style="padding:0.2rem 0.5rem;" onclick="return confirm('¿Eliminar esta etiqueta?');"><i class="ph-bold ph-trash"></i></button>
                        </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if($etiquetas['paginas'] > 1): ?>
                <div style="display:flex; justify-content:center; gap:0.5rem; margin-top:1rem;">
                    <?php if($etiquetas['pagina_actual'] > 1): ?>
                        <a href="<?= urlParam(['p_tag' => $etiquetas['pagina_actual'] - 1]) ?>" class="btn btn-secondary" style="padding:0.2rem 0.6rem;">&laquo;</a>
                    <?php endif; ?>
                    <span style="font-size:0.9rem; align-self:center;">Pág <?= $etiquetas['pagina_actual'] ?> de <?= $etiquetas['paginas'] ?></span>
                    <?php if($etiquetas['pagina_actual'] < $etiquetas['paginas']): ?>
                        <a href="<?= urlParam(['p_tag' => $etiquetas['pagina_actual'] + 1]) ?>" class="btn btn-secondary" style="padding:0.2rem 0.6rem;">&raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="gestor-art-card">
            <h3 class="card-subtitle text-tertiary">Editoriales</h3>
            
            <form action="gestor-catalogos" method="GET" style="display:flex; gap:0.5rem; margin-bottom: 1rem;">
                <?php foreach($_GET as $k => $v): if($k !== 'q_edit' && $k !== 'p_edit' && $k !== 'ruta'): ?>
                    <input type="hidden" name="<?= htmlspecialchars($k) ?>" value="<?= htmlspecialchars($v) ?>">
                <?php endif; endforeach; ?>
                <input type="text" name="q_edit" class="login-flat-input p-input" placeholder="Buscar editorial..." value="<?= htmlspecialchars($busquedas['q_edit']) ?>" style="flex-grow:1; padding: 0.5rem;">
                <button type="submit" class="btn btn-secondary">Buscar</button>
            </form>

            <form action="gestor-catalogos" method="POST" class="mt-1">
                <input type="hidden" name="accion" value="crear_editorial">
                <div class="form-group" style="display:flex; gap:0.5rem;">
                    <input type="text" name="nombre" class="login-flat-input w-100 p-input" placeholder="Nuevo editorial..." required style="padding: 0.5rem;">
                    <button type="submit" class="btn btn-primary">+</button>
                </div>
            </form>

            <div class="mt-1-5">
                <?php if(empty($editoriales['data'])): ?>
                    <p class="text-muted" style="text-align:center;">No hay resultados.</p>
                <?php endif; ?>
                <?php foreach ($editoriales['data'] as $edit): ?>
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:0.6rem 0; border-bottom:1px solid rgba(0,0,0,0.05);">
                        <span><?= htmlspecialchars($edit['nombre']) ?></span>
                        <div style="display:flex; gap: 0.3rem;">
                            <button type="button" class="btn-icon btn-edit" style="padding:0.2rem 0.5rem;" onclick="abrirModalEdicion('actualizar_editorial', <?= (int)$edit['id'] ?>, '<?= htmlspecialchars($edit['nombre'], ENT_QUOTES) ?>')">
                                <i class="ph-bold ph-pencil-simple"></i>
                            </button>
                            <form action="gestor-catalogos" method="POST" style="margin:0;">
                                <input type="hidden" name="accion" value="eliminar_editorial">
                                <input type="hidden" name="id" value="<?= (int)$edit['id'] ?>">
                                <button type="submit" class="btn-icon btn-delete" style="padding:0.2rem 0.5rem;" onclick="return confirm('¿Eliminar este editorial?');"><i class="ph-bold ph-trash"></i></button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if($editoriales['paginas'] > 1): ?>
                <div style="display:flex; justify-content:center; gap:0.5rem; margin-top:1rem;">
                    <?php if($editoriales['pagina_actual'] > 1): ?>
                        <a href="<?= urlParam(['p_edit' => $editoriales['pagina_actual'] - 1]) ?>" class="btn btn-secondary" style="padding:0.2rem 0.6rem;">&laquo;</a>
                    <?php endif; ?>
                    <span style="font-size:0.9rem; align-self:center;">Pág <?= $editoriales['pagina_actual'] ?> de <?= $editoriales['paginas'] ?></span>
                    <?php if($editoriales['pagina_actual'] < $editoriales['paginas']): ?>
                        <a href="<?= urlParam(['p_edit' => $editoriales['pagina_actual'] + 1]) ?>" class="btn btn-secondary" style="padding:0.2rem 0.6rem;">&raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="gestor-art-card">
            <h3 class="card-subtitle text-tertiary">Autores</h3>
            <p class="text-muted" style="font-size:0.85rem; margin-bottom:1rem;">Busque un autor por nombre o cédula para editarlo.</p>

            <form action="gestor-catalogos" method="GET" style="display:flex; gap:0.5rem; margin-bottom: 1rem;">
                <?php foreach($_GET as $k => $v): if($k !== 'q_aut' && $k !== 'ruta'): ?>
                    <input type="hidden" name="<?= htmlspecialchars($k) ?>" value="<?= htmlspecialchars($v) ?>">
                <?php endif; endforeach; ?>
                <input type="text" name="q_aut" class="login-flat-input p-input" placeholder="Nombre o Cédula..." value="<?= htmlspecialchars($busquedas['q_aut']) ?>" style="flex-grow:1; padding: 0.5rem;">
                <button type="submit" class="btn btn-secondary">Buscar</button>
            </form>

            <div class="mt-1-5">
                <?php if($busquedas['q_aut'] === ''): ?>
                    <p class="text-muted" style="text-align:center; font-style:italic;">Use el buscador para encontrar autores.</p>
                <?php elseif(empty($autores)): ?>
                    <p class="text-muted" style="text-align:center; font-style:italic;">No se encontraron resultados para "<?= htmlspecialchars($busquedas['q_aut']) ?>".</p>
                <?php else: ?>
                    <?php foreach($autores as $autor): ?>
                        <form action="gestor-catalogos" method="POST" style="background: rgba(0,0,0,0.02); padding: 1rem; border-radius: 4px; margin-bottom: 1rem; border: 1px solid rgba(0,0,0,0.05);">
                            <input type="hidden" name="accion" value="actualizar_autor">
                            <input type="hidden" name="id" value="<?= (int)$autor['id'] ?>">
                            
                            <label class="font-bold" style="font-size:0.85rem; display:block; margin-bottom:0.2rem;">Nombre Completo</label>
                            <input type="text" name="nombre_completo" class="login-flat-input w-100 p-input mb-1" value="<?= htmlspecialchars($autor['nombre_completo']) ?>" required style="padding: 0.5rem; margin-bottom:0.8rem;">
                            
                            <label class="font-bold" style="font-size:0.85rem; display:block; margin-bottom:0.2rem;">Cédula</label>
                            <input type="text" name="cedula" class="login-flat-input w-100 p-input" value="<?= htmlspecialchars($autor['cedula'] ?? '') ?>" placeholder="V-12345678" style="padding: 0.5rem;">
                            
                            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content:center; margin-top: 1rem; padding: 0.5rem; font-size: 0.9rem;">
                                Actualizar Datos
                            </button>
                        </form>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
    <div id="modal-edicion-catalogo" class="art-modal-overlay" style="display: none;">
        <div class="art-modal-box">
            <h3 class="text-secondary mt-0 box-title-border">Editar Registro</h3>
            <p class="text-muted mb-2">Modifique el nombre del registro. Los cambios se reflejarán en todos los artículos vinculados.</p>

            <form id="form-editar-catalogo" method="POST" action="gestor-catalogos" class="m-0">
                <input type="hidden" name="accion" id="edit-accion">
                <input type="hidden" name="id" id="edit-id">

                <div class="form-group mt-1">
                    <label class="font-bold">Nuevo Nombre *</label>
                    <input type="text" name="nombre" id="edit-nombre" class="login-flat-input w-100 p-input" required>
                </div>

                <div class="modal-actions mt-1-5">
                    <button type="button" class="btn btn-secondary" onclick="cerrarModalEdicion()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function abrirModalEdicion(accion, id, nombreActual) {
        document.getElementById('edit-accion').value = accion;
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-nombre').value = nombreActual;
        
        // Mostramos el modal
        document.getElementById('modal-edicion-catalogo').style.display = 'flex';
        
        // Ponemos el foco en el input para que pueda escribir directamente
        setTimeout(() => document.getElementById('edit-nombre').focus(), 100);
    }

    function cerrarModalEdicion() {
        document.getElementById('modal-edicion-catalogo').style.display = 'none';
    }
    </script>
</div>