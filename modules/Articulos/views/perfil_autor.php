<?php
require_once __DIR__ . '/../services/ConfigService.php';
$paginaActual = (int) ($paginacion['pagina'] ?? 1);
$paginasTotales = (int) ($paginacion['paginas'] ?? 1);

// Helper para construir URLs manteniendo el ID del autor
$buildUrl = function($page = 1, $overrideParams = []) use ($filtros, $autor) {
    $params = [
        'id' => $autor['id'],
        'page' => $page,
        'q' => isset($overrideParams['q']) ? $overrideParams['q'] : ($filtros['q'] ?? ''),
        'year' => isset($overrideParams['year']) ? $overrideParams['year'] : ($filtros['year'] ?? '')
    ];

    $cats = isset($overrideParams['categorias']) ? $overrideParams['categorias'] : ($filtros['categorias'] ?? []);
    foreach ($cats as $c) { if (!empty($c)) $params['categoria'][] = (int)$c; }

    $etis = isset($overrideParams['etiquetas']) ? $overrideParams['etiquetas'] : ($filtros['etiquetas'] ?? []);
    foreach ($etis as $e) { if (!empty($e)) $params['etiqueta'][] = (int)$e; }

    $params = array_filter($params, function($v) { return $v !== '' && $v !== null && $v !== []; });
    return 'perfil-autor?' . http_build_query($params);
};

$buildUrlToggleCat = function($catId) use ($buildUrl, $filtros) {
    if ($catId === null) return $buildUrl(1, ['categorias' => []]);
    $currentCats = array_map('intval', $filtros['categorias'] ?? []);
    $newCats = in_array((int)$catId, $currentCats) 
        ? array_values(array_filter($currentCats, function($c) use ($catId) { return (int)$c !== (int)$catId; })) 
        : array_merge($currentCats, [(int)$catId]);
    return $buildUrl(1, ['categorias' => $newCats]);
};

$buildUrlToggleEti = function($etiId) use ($buildUrl, $filtros) {
    if ($etiId === null) return $buildUrl(1, ['etiquetas' => []]);
    $currentEtis = array_map('intval', $filtros['etiquetas'] ?? []);
    $newEtis = in_array((int)$etiId, $currentEtis) 
        ? array_values(array_filter($currentEtis, function($e) use ($etiId) { return (int)$e !== (int)$etiId; })) 
        : array_merge($currentEtis, [(int)$etiId]);
    return $buildUrl(1, ['etiquetas' => $newEtis]);
};

$buildUrlRemoveParam = function($param) use ($buildUrl) {
    $overrides = [];
    if ($param === 'q') $overrides['q'] = '';
    if ($param === 'year') $overrides['year'] = '';
    return $buildUrl(1, $overrides);
};
?>

<!-- Importar el CSS exclusivo para el perfil -->
<link rel="stylesheet" href="../modules/Articulos/assets/css/perfil_autor.css">

<div class="author-profile-wrapper">
    
    <!-- ENLACE DE REGRESO -->
    <a href="articulos?tab=autores" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-terciario); font-weight: 700; text-decoration: none; width: fit-content;">
        <i class="ph-bold ph-arrow-left"></i> Volver al Directorio de Autores
    </a>

    <!-- TARJETA PRINCIPAL DEL AUTOR -->
    <div class="author-header-card">
        <div class="author-header-bg"></div>
        <div class="author-avatar-large">
            <?= mb_strtoupper(mb_substr($autor['nombre_completo'], 0, 1)) ?>
        </div>
        <div class="author-info-content">
            <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-terciario); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.4rem; display: block;">
                Perfil de Investigador
            </span>
            <h1 class="author-title"><?= htmlspecialchars($autor['nombre_completo']) ?></h1>
            
            <div class="author-badges">
                <?php if (!empty($autor['orcid'])): ?>
                    <?php $urlOrcid = strpos($autor['orcid'], 'http') === 0 ? $autor['orcid'] : 'https://orcid.org/' . $autor['orcid']; ?>
                    <a href="<?= htmlspecialchars($urlOrcid) ?>" target="_blank" class="author-badge-item orcid" title="Perfil verificado en ORCID">
                        <i class="ph-fill ph-identification-badge" style="font-size: 1.2rem;"></i> <?= htmlspecialchars($autor['orcid']) ?>
                    </a>
                <?php endif; ?>
                
                <?php if (!empty($autor['pagina_web'])): ?>
                    <a href="<?= htmlspecialchars($autor['pagina_web']) ?>" target="_blank" class="author-badge-item web" title="Visitar página web del autor">
                        <i class="ph-bold ph-globe" style="font-size: 1.1rem;"></i> Sitio Web Personal
                    </a>
                <?php endif; ?>
            </div>

            <p class="author-bio">
                <?= !empty($autor['biografia']) ? nl2br(htmlspecialchars($autor['biografia'])) : 'Este investigador aún no ha registrado un resumen académico en el sistema.' ?>
            </p>
        </div>
    </div>

    <!-- SECCIÓN DE ARTÍCULOS DEL AUTOR -->
    <div style="margin-top: 1rem;">
        <h3 style="font-size: 1.4rem; color: var(--texto-titulos); margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
            <i class="ph-bold ph-books" style="color: var(--color-principal);"></i> Publicaciones Científicas (<?= $paginacion['total'] ?>)
        </h3>

        <!-- BARRA DE FILTROS ADAPTADA -->
        <header class="art-top-filter-panel" style="margin-bottom: 2rem;">
            <form action="perfil-autor" method="GET" class="art-top-filter-form">
                <input type="hidden" name="id" value="<?= $autor['id'] ?>">
                
                <?php foreach (($filtros['categorias'] ?? []) as $cId): ?>
                    <input type="hidden" name="categoria[]" value="<?= (int)$cId ?>">
                <?php endforeach; ?>
                <?php foreach (($filtros['etiquetas'] ?? []) as $eId): ?>
                    <input type="hidden" name="etiqueta[]" value="<?= (int)$eId ?>">
                <?php endforeach; ?>

                <div class="art-category-pills-bar">
                    <span class="art-pills-label"><i class="ph-bold ph-squares-four"></i> Categorías:</span>
                    <div class="art-pills-scroll-wrapper">
                        <?php $catsSeleccionadas = array_map('intval', $filtros['categorias'] ?? []); ?>
                        <a href="<?= $buildUrlToggleCat(null) ?>" class="art-top-pill <?= empty($catsSeleccionadas) ? 'active' : '' ?>">Todas</a>
                        <?php foreach ($categorias as $cat): $isActive = in_array((int)$cat['id'], $catsSeleccionadas); ?>
                            <a href="<?= $buildUrlToggleCat((int)$cat['id']) ?>" class="art-top-pill <?= $isActive ? 'active' : '' ?>">
                                <?= htmlspecialchars($cat['nombre']) ?> <?= $isActive ? '✓' : '' ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="art-filter-controls-row">
                    <div class="art-search-box" style="flex: 1;">
                        <i class="ph-bold ph-magnifying-glass art-search-icon"></i>
                        <input type="text" name="q" class="art-top-search-input" placeholder="Buscar en las publicaciones de este autor..." value="<?= htmlspecialchars($filtros['q'] ?? '') ?>">
                    </div>
                    <div class="art-select-box">
                        <select name="year" class="art-top-select">
                            <option value="">Todos los años</option>
                            <?php for ($y = (int)date('Y'); $y >= 2020; $y--): ?>
                                <option value="<?= $y ?>" <?= (isset($filtros['year']) && $filtros['year'] == $y) ? 'selected' : '' ?>>Año <?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <button type="submit" class="art-btn-read" style="padding: 0.6rem 1.1rem; font-size: 0.85rem;">
                        <i class="ph-bold ph-funnel"></i> Filtrar
                    </button>
                    <?php if (!empty($filtros['q']) || !empty($filtros['year']) || !empty($filtros['categorias']) || !empty($filtros['etiquetas'])): ?>
                        <a href="perfil-autor?id=<?= $autor['id'] ?>" class="art-link-reset" style="margin: 0; align-self: center;">
                            <i class="ph-bold ph-arrows-counter-clockwise"></i> Limpiar
                        </a>
                    <?php endif; ?>
                </div>

                <!-- CHIPS DE FILTROS ACTIVOS -->
                <?php if (!empty($filtros['q']) || !empty($filtros['year']) || !empty($filtros['categorias']) || !empty($filtros['etiquetas'])): ?>
                    <div class="art-active-chips-row">
                        <span style="font-size: 0.78rem; font-weight: 700; color: var(--color-secundario);">Filtros activos:</span>
                        <?php if (!empty($filtros['q'])): ?>
                            <a href="<?= $buildUrlRemoveParam('q') ?>" class="art-chip-tag">Búsqueda: "<?= htmlspecialchars($filtros['q']) ?>" <i class="ph-bold ph-x"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($filtros['year'])): ?>
                            <a href="<?= $buildUrlRemoveParam('year') ?>" class="art-chip-tag">Año: <?= htmlspecialchars($filtros['year']) ?> <i class="ph-bold ph-x"></i></a>
                        <?php endif; ?>
                        <?php foreach ($catsSeleccionadas as $catId): 
                            $nomCat = 'Categoría'; foreach ($categorias as $c) { if ((int)$c['id'] === (int)$catId) { $nomCat = $c['nombre']; break; } } ?>
                            <a href="<?= $buildUrlToggleCat((int)$catId) ?>" class="art-chip-tag">Cat: <?= htmlspecialchars($nomCat) ?> <i class="ph-bold ph-x"></i></a>
                        <?php endforeach; ?>
                        <?php foreach (($filtros['etiquetas'] ?? []) as $etiId): 
                            $nomEti = 'Etiqueta'; foreach ($etiquetas as $e) { if ((int)$e['id'] === (int)$etiId) { $nomEti = $e['nombre']; break; } } ?>
                            <a href="<?= $buildUrlToggleEti((int)$etiId) ?>" class="art-chip-tag">#<?= htmlspecialchars($nomEti) ?> <i class="ph-bold ph-x"></i></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </form>
        </header>

        <!-- GRID DE ARTÍCULOS -->
        <div class="art-masonry-grid">
            <?php if (empty($articulos)): ?>
                <p class="art-empty-state" style="grid-column: 1/-1;">Este investigador no posee publicaciones que coincidan con los filtros aplicados.</p>
            <?php else: ?>
                <!-- REUTILIZA LA MISMA LÓGICA DE TARJETAS DE ARTÍCULOS DEL GRID NORMAL AQUÍ -->
                <?php foreach ($articulos as $art): ?>
                    <!-- Pega aquí el HTML del <article class="art-post-card"> idéntico al de grid_revista.php -->
                    <?php 
                        $imgPortada = $art['imagen_portada'] ?? 'default_article.jpg';
                        $rutaImg = (strpos($imgPortada, 'http') === 0) ? htmlspecialchars($imgPortada) : '../storage/uploads/articulos/' . htmlspecialchars($imgPortada);
                        $tituloLimpio = htmlspecialchars($art['titulo'] ?? '');
                        $tituloCorto = (mb_strlen($tituloLimpio) > 60) ? mb_substr($tituloLimpio, 0, 57) . '...' : $tituloLimpio;
                    ?>
                    <article class="art-post-card">
                        <div class="art-card-img-wrapper">
                            <img src="<?= $rutaImg ?>" alt="<?= htmlspecialchars($art['titulo'] ?? 'Portada') ?>" class="art-post-img">
                            <span class="art-badge-cat"><?= htmlspecialchars($art['categoria'] ?? 'Artículo') ?></span>
                        </div>
                        <div class="art-post-body">
                            <div class="art-post-meta" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                <span class="art-year-tag"><i class="ph-bold ph-calendar-blank"></i> <?= htmlspecialchars($art['anio_publicacion']) ?></span>
                            </div>
                            <a href="leer-articulo?id=<?= $art['id'] ?>" class="art-post-title" title="<?= htmlspecialchars($art['titulo']) ?>"><?= $tituloCorto ?></a>
                            
                            <div class="art-card-actions" style="margin-top: 1rem;">
                                <a href="leer-articulo?id=<?= $art['id'] ?>" class="art-btn-read"><i class="ph-bold ph-book-open"></i> Leer</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if ($paginasTotales > 1): ?>
            <div class="pagination">
                <?php if ($paginaActual > 1): ?>
                    <a class="page-link" href="<?= $buildUrl($paginaActual - 1) ?>">← Anterior</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $paginasTotales; $i++): ?>
                    <a class="page-link <?= $i === $paginaActual ? 'active' : '' ?>" href="<?= $buildUrl($i) ?>"><?= $i ?></a>
                <?php endfor; ?>
                <?php if ($paginaActual < $paginasTotales): ?>
                    <a class="page-link" href="<?= $buildUrl($paginaActual + 1) ?>">Siguiente →</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>