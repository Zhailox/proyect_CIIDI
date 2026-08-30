
<div class="art-detail-page">
    <?php if (!empty($articulo)): ?>
        <?php
            $imgPortada = $articulo['imagen_portada'] ?? 'default_article.jpg';
            $rutaImg = (strpos($imgPortada, 'http') === 0)
                ? $imgPortada
                : '../public/uploads/articulos/' . $imgPortada;
        ?>

        <article class="art-detail-card">
            <header class="art-detail-hero" style="background-image: url('<?= htmlspecialchars($rutaImg) ?>');">
                <div class="art-detail-hero__overlay">
                    <div class="art-detail-hero__meta">
                        <span class="badge">
                            <?= htmlspecialchars($articulo['categoria'] ?? 'Artículo') ?>
                        </span>

                        <?php if (!empty($articulo['volumen'])): ?>
                            <span class="art-detail-hero__volume">
                                Vol. <?= htmlspecialchars($articulo['volumen']) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <h1><?= htmlspecialchars($articulo['titulo'] ?? 'Artículo sin título') ?></h1>

                    <p class="art-detail-hero__authors">
                        Por: <?= htmlspecialchars($articulo['autores_text'] ?? 'Autor no registrado') ?>
                        | Publicado: <?= htmlspecialchars($articulo['anio_publicacion'] ?? 'Sin año') ?>
                    </p>
                </div>
            </header>

            <div class="art-detail-body">
                <?php if (!empty($articulo['archivo_pdf'])): ?>
                    <a href="<?= htmlspecialchars($articulo['archivo_pdf']) ?>"
                       target="_blank"
                       rel="noopener"
                       class="btn art-detail-link">
                        Ir al artículo completo
                    </a>
                <?php endif; ?>

                <div class="art-detail-content">
                    <h3>Resumen</h3>
                    <p><?= nl2br(htmlspecialchars($articulo['resumen'] ?? 'Sin resumen disponible.')) ?></p>
                </div>
            </div>
        </article>

    <?php else: ?>
        <div class="art-detail-empty">
            <p>No se encontró el artículo solicitado.</p>
        </div>
    <?php endif; ?>
</div>