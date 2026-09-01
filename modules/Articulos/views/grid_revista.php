<?php
$paginaActual = (int) ($paginacion['pagina'] ?? 1);
$paginasTotales = (int) ($paginacion['paginas'] ?? 1);

$buildUrl = function($page) use ($filtros) {
    $params = [
        'page' => $page,
        'q' => $filtros['q'] ?? '',
        'year' => $filtros['year'] ?? ''
    ];

    foreach (($filtros['categorias'] ?? []) as $categoria) {
        $params['categoria'][] = $categoria;
    }

    foreach (($filtros['etiquetas'] ?? []) as $etiqueta) {
        $params['etiqueta'][] = $etiqueta;
    }

    return 'articulos' . (!empty($params) ? '?' . http_build_query($params) : '');
};
?>
<div class="art-catalog-wrapper">

    <!-- BÚSQUEDA Y FILTROS -->
    <aside class="art-filters-sidebar">
    <h3>Búsqueda de Artículos</h3>

    <form action="articulos" method="GET">
        <div class="art-filter-group">
            <label for="search_art">Título o Autor</label>
            <input type="text" id="search_art" name="q" class="art-search-input"
                   placeholder="Ej: Redes Neuronales, Pérez..."
                   value="<?= htmlspecialchars($filtros['q'] ?? '') ?>">
        </div>

        <div class="art-filter-group">
    <details class="art-filter-accordion" open>
        <summary>Categorías</summary>
        <?php if (!empty($categorias)): ?>
            <?php foreach ($categorias as $categoria): ?>
                <?php $seleccionada = !empty($filtros['categorias']) && in_array((int) $categoria['id'], $filtros['categorias']); ?>
                <label class="art-checkbox-label">
                    <input type="checkbox" name="categoria[]" value="<?= (int) $categoria['id'] ?>" <?= $seleccionada ? 'checked' : '' ?>>
                    <?= htmlspecialchars($categoria['nombre']) ?>
                </label>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="art-empty-text">No hay categorías registradas.</p>
        <?php endif; ?>
    </details>
</div>

<div class="art-filter-group">
    <details class="art-filter-accordion">
        <summary>Etiquetas</summary>
        <?php if (!empty($etiquetas)): ?>
            <?php foreach ($etiquetas as $etiqueta): ?>
                <?php $seleccionada = !empty($filtros['etiquetas']) && in_array((int) $etiqueta['id'], $filtros['etiquetas']); ?>
                <label class="art-checkbox-label">
                    <input type="checkbox" name="etiqueta[]" value="<?= (int) $etiqueta['id'] ?>" <?= $seleccionada ? 'checked' : '' ?>>
                    <?= htmlspecialchars($etiqueta['nombre']) ?>
                </label>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="art-empty-text">No hay etiquetas registradas.</p>
        <?php endif; ?>
    </details>
</div>


        <div class="art-filter-group">
            <label for="year_filter">Año de Publicación</label>
            <select id="year_filter" name="year" class="art-select-input">
                <option value="">Todos los años</option>
                <?php for ($anio = date('Y'); $anio >= 2020; $anio--): ?>
                    <option value="<?= $anio ?>" <?= (!empty($filtros['year']) && (int)$filtros['year'] === $anio) ? 'selected' : '' ?>>
                        <?= $anio ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <button type="submit" class="btn" style="width: 100%; margin-top: 1rem;">Aplicar Filtros</button>
        <a href="articulos" class="art-link-reset">Limpiar filtros</a>
    <input type="hidden" name="page" value="1">
    </form>
</aside>

    <main class="art-catalog-content">

        <!-- EL MÁS RECIENTE (Objetividad cronológica) -->
        <?php if (!empty($articulos)): $ultimo = $articulos[0]; ?>
        <article class="art-featured-post">
            <?php 
                $imgPortada = $ultimo['imagen_portada'] ?? 'default_article.jpg';
                // Verificamos si es una URL externa o un archivo local
                $rutaImg = (strpos($imgPortada, 'http') === 0) 
                    ? htmlspecialchars($imgPortada) 
                    : '../public/uploads/articulos/' . htmlspecialchars($imgPortada);
            ?>
            <div class="art-featured-img" style="background-image: url('<?= $rutaImg ?>');"></div>
            <div class="art-featured-body">
                
                <div class="art-post-meta">
                    <div class="art-tags">
                        <span class="art-tag" style="background: var(--color-secundario); color: white;">ÚLTIMA PUBLICACIÓN</span>
                        <span class="art-tag"><?= htmlspecialchars($ultimo['categoria'] ?? 'Investigación') ?></span>
                    </div>
                    <span class="art-metric">Vol. <?= htmlspecialchars($ultimo['volumen'] ?? 'N/A') ?></span>
                </div>

                <a href="leer-articulo?id=<?= $ultimo['id'] ?>" class="art-post-title" style="font-size: 1.8rem;">
                    <?= htmlspecialchars($ultimo['titulo']) ?>
                </a>
                
                <!-- Imprimimos el resumen real limitando visualmente a 3 líneas con la clase art-abstract -->
                <p class="art-abstract">
                    <?= htmlspecialchars($ultimo['resumen'] ?? 'Sin resumen disponible.') ?>
                </p>
                <p class="art-authors" style="margin-bottom: 0;">
                    Publicado: <?= htmlspecialchars($ultimo['anio_publicacion']) ?>
                    <span style="display:block; margin-top:0.25rem;">
                        Autores: <?= htmlspecialchars($ultimo['autores_text']) ?>
                    </span>
                </p>
            </div>
        </article>
        <?php endif; ?>

        <!-- GRID DEL CATÁLOGO RESTANTE -->
        <div class="art-masonry-grid">
            <?php 
            // Saltamos el primero porque ya lo pusimos arriba
            $restoArticulos = array_slice($articulos, 1);
            if (empty($restoArticulos)): 
            ?>
                <p class="art-empty-state" style="grid-column: 1/-1;">
                    No hay artículos que coincidan con los filtros seleccionados.
                </p>
            <?php else: ?>
                <?php foreach ($restoArticulos as $art): ?>
                <article class="art-post-card">
                    <?php 
                        $imgPortada = $art['imagen_portada'] ?? 'default_article.jpg';
                        $rutaImg = (strpos($imgPortada, 'http') === 0) 
                            ? htmlspecialchars($imgPortada) 
                            : '../public/uploads/articulos/' . htmlspecialchars($imgPortada);
                    ?>
                    <div class="art-post-img" style="background-image: url('<?= $rutaImg ?>');"></div>
                    <div class="art-post-body">
                        <div class="art-post-meta">
                            <span class="art-tag"><?= htmlspecialchars($art['categoria'] ?? 'Artículo') ?></span>
                            <span class="art-metric">Vol. <?= htmlspecialchars($art['volumen'] ?? 'N/A') ?></span>
                        </div>
                        <a href="leer-articulo?id=<?= $art['id'] ?>" class="art-post-title"><?= htmlspecialchars($art['titulo']) ?></a>
                        <?php
                        $summary = trim($art['resumen'] ?? '');
                        $summaryShort = mb_strlen($summary) > 110
                            ? mb_substr($summary, 0, 107) . '...'
                            : $summary;
                        ?>
                        <p class="art-card-summary"><?= htmlspecialchars($summaryShort) ?></p>
                        
                        <!-- Añadimos el resumen también a las tarjetas pequeñas -->
                        <p class="art-authors" style="margin-bottom: 0;">
                            Publicado: <?= htmlspecialchars($art['anio_publicacion']) ?>
                            <span style="display:block; margin-top:0.25rem;">
                                Autores: <?= htmlspecialchars($art['autores_text']) ?>
                            </span>
                        </p>
                        
                        <p class="art-authors" style="margin-bottom: 0;">

                    </div>
                </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php if ($paginasTotales > 1): ?>
<div class="art-pagination">
    <?php if ($paginaActual > 1): ?>
        <a class="art-page-link" href="<?= $buildUrl($paginaActual - 1) ?>">← Anterior</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $paginasTotales; $i++): ?>
        <a class="art-page-link <?= $i === $paginaActual ? 'active' : '' ?>" href="<?= $buildUrl($i) ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($paginaActual < $paginasTotales): ?>
        <a class="art-page-link" href="<?= $buildUrl($paginaActual + 1) ?>">Siguiente →</a>
    <?php endif; ?>
</div>
<?php endif; ?>

    </main>
</div>