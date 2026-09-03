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

    <?php if (isset($_SESSION['mensaje_exito'])): ?>
        <div class="alert-success">
            <i class="ph-bold ph-check-circle"></i> <?= htmlspecialchars($_SESSION['mensaje_exito']) ?>
        </div>
        <?php unset($_SESSION['mensaje_exito']); ?>
    <?php endif; ?>

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
                                    <div class="art-mini-thumbnail" style="background-image: url('<?= $rutaImg ?>');"></div>
                                </td>
                                <td class="art-title-col">
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
                                    <a href="editar-articulo?id=<?= $art['id'] ?>" class="btn-icon btn-edit" title="Editar">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                    </a>
                                    <form action="eliminar-articulo" method="POST" class="form-inline-delete">
                                        <input type="hidden" name="id_articulo" value="<?= $art['id'] ?>">
                                        <button type="submit" class="btn-icon btn-delete" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar este artículo de la revista?');">
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