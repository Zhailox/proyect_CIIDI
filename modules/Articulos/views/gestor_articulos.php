<div class="gestor-art-container">
    
    <div class="gestor-art-header">
        <div class="gestor-art-title-box">
            <h1>Gestor de Artículos Científicos</h1>
            <p>Administración del catálogo, volúmenes y portadas de la revista digital.</p>
        </div>
        <a href="nuevo-articulo" class="btn btn-primary gestor-art-btn-new">
            <i class="ph-bold ph-plus"></i> Registrar Artículo
        </a>
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
        </div>
    </div>
</div>