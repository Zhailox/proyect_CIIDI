<?php
$paginaActual = (int) ($paginacion['pagina'] ?? 1);
$paginasTotales = (int) ($paginacion['paginas'] ?? 1);
$busquedaActual = $_GET['q'] ?? '';

// Helper para no perder la búsqueda al cambiar de página
$buildUrl = function($page) use ($busquedaActual) {
    $url = 'gestor-articulos?page=' . $page;
    if (!empty($busquedaActual)) {
        $url .= '&q=' . urlencode($busquedaActual);
    }
    return $url;
};
?>
<div class="gestor-art-container">
    
    <div class="gestor-art-header" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; justify-content: space-between;">
        <div class="gestor-art-title-box">
            <h1>Gestor de Artículos Científicos</h1>
            <p>Administración del catálogo, volúmenes y portadas de la revista digital.</p>
        </div>
       
        <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
            <form action="gestor-articulos" method="GET" style="display: flex; gap: 0.5rem; margin: 0;">
                <input type="text" name="q" class="login-flat-input p-input" placeholder="Buscar por título o autor..." value="<?= htmlspecialchars($filtros['q'] ?? '') ?>" style="padding: 0.6rem; min-width: 250px;">
                <button type="submit" class="btn btn-secondary">Buscar</button>
            </form>

            <a href="gestor-catalogos" class="btn btn-secondary">
                <i class="ph-bold ph-tags"></i> Catálogos
            </a>
            <a href="nuevo-articulo" class="btn btn-primary gestor-art-btn-new">
                <i class="ph-bold ph-plus"></i> Registrar Artículo
            </a>
        </div>
    </div>

    

    <div class="gestor-art-card">
        <div class="table-responsive">
            <table class="gestor-art-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Portada</th>
                        <th>Título del Artículo</th>
                        <th>Volumen / Año</th>
                        <th>Categoría</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($articulos)): ?>
                        <tr>
                            <td colspan="6" class="gestor-art-empty">No hay artículos registrados en el sistema.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($articulos as $art): ?>
                            <tr>
                                <td class="art-id-col">#<?= $art['id'] ?></td>
                                <td>
                                    <?php 
                                        $imgPortada = $art['imagen_portada'] ?? 'default_article.jpg';
                                        // Verificamos si es una URL externa o un archivo local
                                        $rutaImg = (strpos($imgPortada, 'http') === 0) 
                                            ? htmlspecialchars($imgPortada) 
                                            : '../public/uploads/articulos/' . htmlspecialchars($imgPortada);
                                    ?>
                                    <img src="<?= $rutaImg ?>" loading="lazy" alt="Miniatura" class="art-mini-thumbnail" style="object-fit: cover; width: 45px; height: 60px; border-radius: 4px;">
                                </td>
                                <td class="art-title-col">
                                    <div style="margin-bottom: 0.3rem;">
                                        <?php if ($art['activo'] ?? true): ?>
                                            <span style="background: #def7ec; color: #03543f; padding: 0.1rem 0.35rem; border-radius: 3px; font-size: 0.68rem; font-weight: 700;">Visible</span>
                                        <?php else: ?>
                                            <span style="background: #fde8e8; color: #9b1c1c; padding: 0.1rem 0.35rem; border-radius: 3px; font-size: 0.68rem; font-weight: 700;">Oculto</span>
                                        <?php endif; ?>
                                    </div>
                                    <strong><?= htmlspecialchars($art['titulo']) ?></strong>
                                </td>
                                <td>
                                    Vol. <?= htmlspecialchars((string) ($art['volumen'] ?? '')) ?> <br>
                                    <span class="art-year-badge"><?= htmlspecialchars($art['anio_publicacion']) ?></span>
                                </td>
                                <td>
                                    <span class="art-category-badge"><?= htmlspecialchars($art['categoria'] ?? 'Sin categoría') ?></span>
                                </td>
                                <td class="art-actions-col">
                                    <a href="toggle-estado-articulo?id=<?= $art['id'] ?>" class="btn-icon" style="background: <?= ($art['activo'] ?? true) ? '#fef3c7; color: #92400e;' : '#dcfce7; color: #15803d;' ?>" title="<?= ($art['activo'] ?? true) ? 'Ocultar' : 'Activar' ?>">
                                        <i class="ph-bold <?= ($art['activo'] ?? true) ? 'ph-eye-slash' : 'ph-eye' ?>"></i>
                                    </a>

                                    <a href="editar-articulo?id=<?= $art['id'] ?>" class="btn-icon btn-edit" title="Editar">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                    </a>
                                    
                                    <form action="eliminar-articulo" method="POST" class="form-inline-delete" id="form-delete-<?= $art['id'] ?>">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                        <input type="hidden" name="id_articulo" value="<?= $art['id'] ?>">
                                        <button type="button" class="btn-icon btn-delete" title="Eliminar" onclick="confirmarEliminacion(<?= $art['id'] ?>)">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php if ($paginasTotales > 1): ?>
    <div class="pagination" style="margin-top: 1.5rem;">
        <?php if ($paginaActual > 1): ?>
            <a class="page-link" href="<?= $buildUrl($paginaActual - 1) ?>">← Anterior</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $paginasTotales; $i++): ?>
            <a class="page-link <?= $i === $paginaActual ? 'active' : '' ?>" href="<?= $buildUrl($i) ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($paginaActual < $paginasTotales): ?>
            <a class="page-link" href="<?= $buildUrl($paginaActual + 1) ?>">Siguiente →</a>
        <?php endif; ?>
    </div>
<?php endif; ?>
        </div>
    </div>
</div>
<script>
function mostrarModalSistema(tipo, titulo, mensaje, isConfirm = false, onConfirm = null) {
    const overlay = document.createElement('div');
    overlay.style.cssText = 'position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,34,68,0.8); z-index:99999; display:flex; align-items:center; justify-content:center; backdrop-filter:blur(4px);';
    
    let icon = tipo === 'success' ? '<i class="ph-bold ph-check-circle" style="color: #16a34a;"></i>' : '<i class="ph-bold ph-warning-circle" style="color: #dc2626;"></i>';
    let btnHtml = isConfirm 
        ? `<button type="button" class="btn btn-secondary" onclick="this.closest('div').parentElement.parentElement.remove()" style="margin-right:0.5rem;">Cancelar</button>
           <button type="button" class="btn btn-primary" id="btn-confirm-modal">Sí, proceder</button>`
        : `<button type="button" class="btn btn-primary w-100 justify-center" onclick="this.closest('div').parentElement.parentElement.remove()">Entendido</button>`;

    overlay.innerHTML = `
        <div style="background: white; padding: 2rem; border-radius: 8px; max-width: 400px; width: 90%; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <div style="font-size: 3.5rem; margin-bottom: 1rem;">${icon}</div>
            <h3 style="margin: 0 0 0.5rem 0; color: #0f172a; font-size:1.2rem;">${titulo}</h3>
            <p style="color: #475569; font-size: 0.9rem; margin-bottom: 1.5rem; line-height:1.5;">${mensaje}</p>
            <div style="display:flex; justify-content:center;">${btnHtml}</div>
        </div>
    `;
    
    document.body.appendChild(overlay);

    if (isConfirm && onConfirm) {
        document.getElementById('btn-confirm-modal').addEventListener('click', () => {
            overlay.remove();
            onConfirm();
        });
    }
}

function confirmarEliminacion(idArticulo) {
    mostrarModalSistema(
        'warning', 
        'Eliminar Artículo', 
        '¿Está seguro de eliminar este artículo del catálogo? Los archivos adjuntos serán borrados permanentemente.', 
        true, 
        () => document.getElementById('form-delete-' + idArticulo).submit()
    );
}

// Inyección de alertas desde PHP
<?php if (isset($_SESSION['mensaje_exito'])): ?>
    mostrarModalSistema('success', 'Operación Exitosa', '<?= htmlspecialchars($_SESSION['mensaje_exito']) ?>');
    <?php unset($_SESSION['mensaje_exito']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['mensaje_error'])): ?>
    mostrarModalSistema('error', 'Ocurrió un Problema', '<?= htmlspecialchars($_SESSION['mensaje_error']) ?>');
    <?php unset($_SESSION['mensaje_error']); ?>
<?php endif; ?>
</script>