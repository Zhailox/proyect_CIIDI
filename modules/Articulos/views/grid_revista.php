<?php
require_once __DIR__ . '/../services/ConfigService.php';
$paginaActual = (int) ($paginacion['pagina'] ?? 1);
$paginasTotales = (int) ($paginacion['paginas'] ?? 1);

$buildUrl = function($page = 1, $overrideParams = []) use ($filtros, $tabActiva) {
    $params = [
        'tab' => 'articulos', // <-- Fija la pestaña en la URL de los filtros
        'page' => $page,
        'q' => isset($overrideParams['q']) ? $overrideParams['q'] : ($filtros['q'] ?? ''),
        'q_autor' => isset($overrideParams['q_autor']) ? $overrideParams['q_autor'] : ($filtros['q_autor'] ?? ''), 
        'year' => isset($overrideParams['year']) ? $overrideParams['year'] : ($filtros['year'] ?? '')
    ];

    $cats = isset($overrideParams['categorias']) ? $overrideParams['categorias'] : ($filtros['categorias'] ?? []);
    foreach ($cats as $c) {
        if (!empty($c)) $params['categoria'][] = (int)$c;
    }

    $etis = isset($overrideParams['etiquetas']) ? $overrideParams['etiquetas'] : ($filtros['etiquetas'] ?? []);
    foreach ($etis as $e) {
        if (!empty($e)) $params['etiqueta'][] = (int)$e;
    }

    $params = array_filter($params, function($v) {
        return $v !== '' && $v !== null && $v !== [];
    });

    return 'articulos' . (!empty($params) ? '?' . http_build_query($params) : '');
};

$buildUrlToggleCat = function($catId) use ($buildUrl, $filtros) {
    if ($catId === null) {
        return $buildUrl(1, ['categorias' => []]);
    }
    $currentCats = array_map('intval', $filtros['categorias'] ?? []);
    if (in_array((int)$catId, $currentCats)) {
        $newCats = array_values(array_filter($currentCats, function($c) use ($catId) { return (int)$c !== (int)$catId; }));
    } else {
        $newCats = $currentCats;
        $newCats[] = (int)$catId;
    }
    return $buildUrl(1, ['categorias' => $newCats]);
};

$buildUrlToggleEti = function($etiId) use ($buildUrl, $filtros) {
    if ($etiId === null) {
        return $buildUrl(1, ['etiquetas' => []]);
    }
    $currentEtis = array_map('intval', $filtros['etiquetas'] ?? []);
    if (in_array((int)$etiId, $currentEtis)) {
        $newEtis = array_values(array_filter($currentEtis, function($e) use ($etiId) { return (int)$e !== (int)$etiId; }));
    } else {
        $newEtis = $currentEtis;
        $newEtis[] = (int)$etiId;
    }
    return $buildUrl(1, ['etiquetas' => $newEtis]);
};

$buildUrlRemoveParam = function($param) use ($buildUrl) {
    $overrides = [];
    if ($param === 'q') $overrides['q'] = '';
    if ($param === 'q_autor') $overrides['q_autor'] = ''; // <-- Agregado
    if ($param === 'year') $overrides['year'] = '';
    return $buildUrl(1, $overrides);
};
?>
<!-- HERO ANIMADO DE ANCHO COMPLETO (TIPOGRAFÍA CIENTÍFICA SOBRE FONDO CLARO) -->
<div class="art-hero-canvas-wrapper">
    <canvas id="artCanvasHero"></canvas>
    <div class="art-hero-canvas-overlay">
        <div class="art-hero-content-inner">
            <span class="art-hero-badge"><i class="ph-fill ph-journal"></i> Revista Digital CIIDI</span>
            <h1>Catálogo de Artículos Científicos e Investigaciones</h1>
            <p>Monitoreo, divulgación y consulta de publicaciones académicas de alto impacto.</p>
        </div>
    </div>
</div>

<div class="art-catalog-wrapper" style="flex-direction: column; gap: 1.75rem;">
    
    <!-- TABS TIPO PÍLDORA PARA CAMBIAR ENTRE ARTICULOS Y AUTORES -->
    <div class="art-pill-tabs-container">
        <div class="art-pill-tabs">
            <a href="articulos?tab=articulos" class="art-pill-tab <?= $tabActiva === 'articulos' ? 'active' : '' ?>">
                <i class="ph-bold ph-books"></i> Artículos e Investigaciones
            </a>
            <a href="articulos?tab=autores" class="art-pill-tab <?= $tabActiva === 'autores' ? 'active' : '' ?>">
                <i class="ph-bold ph-users-three"></i> Directorio de Autores
            </a>
        </div>
    </div>

    <?php if ($tabActiva === 'articulos'): ?>
        
        <!-- ============================================== -->
        <!-- PESTAÑA 1: CATÁLOGO NORMAL DE ARTÍCULOS        -->
        <!-- ============================================== -->

        <header class="art-top-filter-panel">
            <form action="articulos" method="GET" class="art-top-filter-form">
                <input type="hidden" name="tab" value="articulos">
                <!-- CAMPOS OCULTOS PARA PRESERVAR MULTISELECCIÓN -->
                <?php foreach (($filtros['categorias'] ?? []) as $cId): ?>
                    <input type="hidden" name="categoria[]" value="<?= (int)$cId ?>">
                <?php endforeach; ?>
                <?php foreach (($filtros['etiquetas'] ?? []) as $eId): ?>
                    <input type="hidden" name="etiqueta[]" value="<?= (int)$eId ?>">
                <?php endforeach; ?>

                <div class="art-category-pills-bar">
                    <span class="art-pills-label"><i class="ph-bold ph-squares-four"></i> Categorías:</span>
                    <div class="art-pills-scroll-wrapper">
                        <?php 
                            $catsSeleccionadas = array_map('intval', $filtros['categorias'] ?? []);
                            $todasActive = empty($catsSeleccionadas);
                        ?>
                        <a href="<?= $buildUrlToggleCat(null) ?>" class="art-top-pill <?= $todasActive ? 'active' : '' ?>">Todas</a>
                        <?php if (!empty($categorias)): ?>
                            <?php foreach ($categorias as $cat): ?>
                                <?php $isActive = in_array((int)$cat['id'], $catsSeleccionadas); ?>
                                <a href="<?= $buildUrlToggleCat((int)$cat['id']) ?>" class="art-top-pill <?= $isActive ? 'active' : '' ?>" title="<?= $isActive ? 'Desactivar categoría' : 'Añadir categoría' ?>">
                                    <?= htmlspecialchars($cat['nombre']) ?> <?= $isActive ? '✓' : '' ?>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($etiquetas)): ?>
                    <div class="art-category-pills-bar" style="border-bottom: none; padding-bottom: 0;">
                        <span class="art-pills-label"><i class="ph-bold ph-hash"></i> Etiquetas:</span>
                        <div class="art-pills-scroll-wrapper">
                            <?php $etisSeleccionadas = array_map('intval', $filtros['etiquetas'] ?? []); ?>
                            <?php foreach ($etiquetas as $eti): ?>
                                <?php $isActiveEti = in_array((int)$eti['id'], $etisSeleccionadas); ?>
                                <a href="<?= $buildUrlToggleEti((int)$eti['id']) ?>" class="art-top-pill <?= $isActiveEti ? 'active' : '' ?>" style="<?= $isActiveEti ? 'background: var(--color-secundario, #0b1a30); color: #ffffff;' : '' ?>" title="<?= $isActiveEti ? 'Desactivar etiqueta' : 'Añadir etiqueta' ?>">
                                    #<?= htmlspecialchars($eti['nombre']) ?> <?= $isActiveEti ? '✓' : '' ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="art-filter-controls-row">
                    <div class="art-search-box" style="flex: 1;">
                        <i class="ph-bold ph-magnifying-glass art-search-icon"></i>
                        <input type="text" name="q" class="art-top-search-input" placeholder="Buscar por título, resumen..." value="<?= htmlspecialchars($filtros['q'] ?? '') ?>">
                    </div>
                    <div class="art-search-box" style="flex: 1;">
                        <i class="ph-bold ph-user-focus art-search-icon"></i>
                        <input type="text" name="q_autor" class="art-top-search-input" placeholder="Buscar autor u ORCID..." value="<?= htmlspecialchars($filtros['q_autor'] ?? '') ?>">
                    </div>
                    <div class="art-select-box">
                        <select name="year" class="art-top-select">
                            <option value="">Todos los años</option>
                            <?php 
                            $anioActual = (int)date('Y');
                            $anioMinimo = (int)ConfigService::get('buscador.anio_minimo', 2020);
                            for ($y = $anioActual; $y >= $anioMinimo; $y--): 
                            ?>
                                <option value="<?= $y ?>" <?= (isset($filtros['year']) && $filtros['year'] == $y) ? 'selected' : '' ?>>Año <?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <button type="submit" class="art-btn-read" style="padding: 0.6rem 1.1rem; font-size: 0.85rem;">
                        <i class="ph-bold ph-funnel"></i> Buscar / Filtrar
                    </button>

                    <?php if (!empty($filtros['q']) || !empty($filtros['year']) || !empty($filtros['categorias']) || !empty($filtros['etiquetas']) || !empty($filtros['q_autor'])): ?>
                        <a href="articulos?tab=articulos" class="art-link-reset" style="margin: 0; align-self: center;">
                            <i class="ph-bold ph-arrows-counter-clockwise"></i> Limpiar Todo
                        </a>
                    <?php endif; ?>

                    <div class="art-view-toggle-box" style="display: flex; gap: 0.25rem; background: #f1f5f9; padding: 0.2rem; border-radius: 6px; border: 1px solid rgba(11,26,48,0.1); margin-left: auto;">
                        <button type="button" id="btnViewGrid" class="art-view-btn active" title="Vista Cuadrícula (Tarjetas)" onclick="setArtViewMode('grid')"><i class="ph-bold ph-squares-four"></i></button>
                        <button type="button" id="btnViewList" class="art-view-btn" title="Vista Lista Espaciosa" onclick="setArtViewMode('list')"><i class="ph-bold ph-rows"></i></button>
                    </div>
                </div>

                <?php $hayFiltrosActivos = !empty($filtros['q']) || !empty($filtros['year']) || !empty($filtros['categorias']) || !empty($filtros['etiquetas']) || !empty($filtros['q_autor']); ?>
                <?php if ($hayFiltrosActivos): ?>
                    <div class="art-active-chips-row">
                        <span style="font-size: 0.78rem; font-weight: 700; color: var(--color-secundario);">Filtros activos:</span>
                        <?php if (!empty($filtros['q'])): ?>
                            <a href="<?= $buildUrlRemoveParam('q') ?>" class="art-chip-tag" title="Quitar búsqueda">Búsqueda: "<?= htmlspecialchars($filtros['q']) ?>" <i class="ph-bold ph-x"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($filtros['q_autor'])): ?>
                            <a href="<?= $buildUrlRemoveParam('q_autor') ?>" class="art-chip-tag" title="Quitar búsqueda por autor">Autor: "<?= htmlspecialchars($filtros['q_autor']) ?>" <i class="ph-bold ph-x"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($filtros['year'])): ?>
                            <a href="<?= $buildUrlRemoveParam('year') ?>" class="art-chip-tag" title="Quitar filtro de año">Año: <?= htmlspecialchars($filtros['year']) ?> <i class="ph-bold ph-x"></i></a>
                        <?php endif; ?>
                        <?php foreach (($filtros['categorias'] ?? []) as $catId): ?>
                            <?php $nomCat = 'Categoría'; foreach ($categorias as $c) { if ((int)$c['id'] === (int)$catId) { $nomCat = $c['nombre']; break; } } ?>
                            <a href="<?= $buildUrlToggleCat((int)$catId) ?>" class="art-chip-tag" title="Quitar esta categoría">Cat: <?= htmlspecialchars($nomCat) ?> <i class="ph-bold ph-x"></i></a>
                        <?php endforeach; ?>
                        <?php foreach (($filtros['etiquetas'] ?? []) as $etiId): ?>
                            <?php $nomEti = 'Etiqueta'; foreach ($etiquetas as $e) { if ((int)$e['id'] === (int)$etiId) { $nomEti = $e['nombre']; break; } } ?>
                            <a href="<?= $buildUrlToggleEti((int)$etiId) ?>" class="art-chip-tag" title="Quitar esta etiqueta">#<?= htmlspecialchars($nomEti) ?> <i class="ph-bold ph-x"></i></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <input type="hidden" name="page" value="1">
            </form>
        </header>

        <main class="art-catalog-content" style="width: 100%;">
            <div class="art-masonry-grid">
                <?php if (empty($articulos)): ?>
                    <p class="art-empty-state" style="grid-column: 1/-1;">No hay artículos que coincidan con los criterios de búsqueda.</p>
                <?php else: ?>
                    <?php foreach ($articulos as $art): ?>
                        <?php 
                            $imgPortada = $art['imagen_portada'] ?? 'default_article.jpg';
                            $rutaImg = (strpos($imgPortada, 'http') === 0) ? htmlspecialchars($imgPortada) : '../storage/uploads/articulos/' . htmlspecialchars($imgPortada);
                            $tituloLimpio = htmlspecialchars($art['titulo'] ?? '');
                            $tituloCorto = (mb_strlen($tituloLimpio) > 60) ? mb_substr($tituloLimpio, 0, 57) . '...' : $tituloLimpio;
                        ?>
                        <article class="art-post-card">
                            <div class="art-card-img-wrapper">
                                <img data-src="<?= $rutaImg ?>" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 180'%3E%3Crect width='100%25' height='100%25' fill='%23f1f5f9'/%3E%3C/svg%3E" alt="<?= htmlspecialchars($art['titulo'] ?? 'Portada') ?>" class="art-post-img">
                                <span class="art-badge-cat"><?= htmlspecialchars($art['categoria'] ?? 'Artículo') ?></span>
                            </div>

                            <div class="art-post-body">
                                <div class="art-post-meta" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                    <span class="art-year-tag"><i class="ph-bold ph-calendar-blank"></i> <?= htmlspecialchars($art['anio_publicacion']) ?></span>
                                    <div style="display: flex; align-items: center; gap: 0.5rem; text-align: right; flex-wrap: wrap; justify-content: flex-end;">
                                        <?php if (ConfigService::get('recursos.mostrar_editorial', true) && !empty($art['editorial'])): ?>
                                            <span class="art-metric" style="background: transparent; color: var(--texto-silenciado); font-weight: 600; padding: 0;"><?= htmlspecialchars($art['editorial']) ?></span>
                                        <?php endif; ?>
                                        <?php if (ConfigService::get('recursos.mostrar_volumen', true) && (!empty($art['volumen']) || !empty($art['numero']))): ?>
                                            <?php if (ConfigService::get('recursos.mostrar_editorial', true) && !empty($art['editorial'])): ?>
                                                <span style="color: var(--texto-silenciado); font-size: 0.75rem;">•</span>
                                            <?php endif; ?>
                                            <span class="art-metric">Vol. <?= htmlspecialchars($art['volumen'] ?? 'N/A') ?><?= !empty($art['numero']) ? ' - Núm. ' . htmlspecialchars($art['numero']) : '' ?></span>
                                        <?php endif; ?>
                                        <?php if (ConfigService::get('recursos.mostrar_issn', true) && !empty($art['issn'])): ?>
                                            <?php if ((ConfigService::get('recursos.mostrar_volumen', true) && (!empty($art['volumen']) || !empty($art['numero']))) || (ConfigService::get('recursos.mostrar_editorial', true) && !empty($art['editorial']))): ?>
                                                <span style="color: var(--texto-silenciado); font-size: 0.75rem;">•</span>
                                            <?php endif; ?>
                                            <span class="art-metric" style="background: rgba(15, 23, 42, 0.05); color: var(--color-secundario);">ISSN: <?= htmlspecialchars($art['issn']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <a href="leer-articulo?id=<?= $art['id'] ?>" class="art-post-title" title="<?= htmlspecialchars($art['titulo']) ?>"><?= $tituloCorto ?></a>

                                <div class="art-authors-line">
                                    <i class="ph-bold ph-users"></i> 
                                    <?php if (!empty($art['autores_data'])): ?>
                                        <?php foreach($art['autores_data'] as $autorData): ?>
                                            <span style="display:inline-flex; align-items:center; gap:4px; background:rgba(0,0,0,0.04); padding:2px 6px; border-radius:4px; font-size:0.78rem; white-space:nowrap;">
                                               <a href="perfil-autor?id=<?= (int)$autorData['id'] ?>" style="color:var(--color-terciario); text-decoration:none; font-weight:600;" title="Ver perfil de <?= htmlspecialchars($autorData['nombre']) ?>">
                                                    <?php 
                                                        $nomAutor = htmlspecialchars($autorData['nombre']);
                                                        echo (mb_strlen($nomAutor) > 16) ? mb_substr($nomAutor, 0, 14) . '..' : $nomAutor; 
                                                    ?>
                                                </a>
                                                <?php if (!empty($autorData['orcid'])): ?>
                                                    <?php $orcidUrl = strpos($autorData['orcid'], 'http') === 0 ? $autorData['orcid'] : 'https://orcid.org/' . $autorData['orcid']; ?>
                                                    <a href="<?= htmlspecialchars($orcidUrl) ?>" target="_blank" title="Ver perfil ORCID" style="color:var(--color-terciario); display:flex; align-items:center;">
                                                        <i class="ph-fill ph-identification-badge" style="font-size:1.1rem;"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        Autor no registrado
                                    <?php endif; ?>
                                </div>

                                <div class="art-card-actions">
                                    <a href="leer-articulo?id=<?= $art['id'] ?>" class="art-btn-read"><i class="ph-bold ph-book-open"></i> Leer</a>
                                    <button type="button" class="art-action-icon-btn btn-trigger-cita" title="Generar Cita Académica" 
                                        data-titulo="<?= htmlspecialchars($art['titulo'] ?? 'Sin Título', ENT_QUOTES, 'UTF-8') ?>"
                                        data-autores="<?= htmlspecialchars($art['autores_text'] ?? 'Autor no registrado', ENT_QUOTES, 'UTF-8') ?>"
                                        data-anio="<?= htmlspecialchars((string)($art['anio_publicacion'] ?? 's.f.'), ENT_QUOTES, 'UTF-8') ?>"
                                        data-editorial="<?= htmlspecialchars($art['editorial'] ?? 'S/E', ENT_QUOTES, 'UTF-8') ?>"
                                        data-volumen="<?= htmlspecialchars((string)($art['volumen'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                        data-numero="<?= htmlspecialchars((string)($art['numero'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                        data-issn="<?= htmlspecialchars((string)($art['issn'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                        onclick="abrirModalCitaDesdeBtn(this)">
                                        <i class="ph-bold ph-quotes"></i>
                                    </button>
                                    <button type="button" class="art-action-icon-btn btn-share-link" title="Copiar enlace al artículo" onclick="compartirEnlaceArticulo(<?= (int)$art['id'] ?>, this)">
                                        <i class="ph-bold ph-share-network"></i>
                                    </button>
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
        </main>
    
    <?php else: ?>
        
        <!-- ============================================== -->
        <!-- PESTAÑA 2: DIRECTORIO Y TARJETAS DE AUTORES    -->
        <!-- ============================================== -->
        
        <header class="art-top-filter-panel">
            <form action="articulos" method="GET" class="art-top-filter-form">
                <input type="hidden" name="tab" value="autores">
                
                <div class="art-filter-controls-row">
                    <div class="art-search-box">
                        <i class="ph-bold ph-user-focus art-search-icon"></i>
                        <input type="text" name="q_autores_tab" class="art-top-search-input" placeholder="Buscar investigador por nombre u ORCID..." value="<?= htmlspecialchars($q_autores_tab ?? '') ?>">
                    </div>
                    
                    <button type="submit" class="art-btn-read art-btn-search-submit">
                        <i class="ph-bold ph-funnel"></i> Buscar Investigador
                    </button>
                    
                    <?php if (!empty($q_autores_tab)): ?>
                        <a href="articulos?tab=autores" class="art-link-reset art-link-reset-inline">
                            <i class="ph-bold ph-arrows-counter-clockwise"></i> Limpiar
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </header>

        <main class="art-catalog-content" style="width: 100%;">
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 260px), 1fr)); gap: 1.5rem;">
                <?php if (empty($autoresCatalogo['data'])): ?>
                    <p class="art-empty-state" style="grid-column: 1/-1;">No se encontraron autores en el directorio del sistema.</p>
                <?php else: ?>
                    <?php foreach ($autoresCatalogo['data'] as $autorInfo): ?>
                        <div style="background: white; border: 1px solid rgba(112, 144, 203, 0.2); border-radius: 12px; padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem; box-shadow: 0 4px 10px rgba(0,0,0,0.02); transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 25px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 10px rgba(0,0,0,0.02)';">
                            <div style="display: flex; gap: 1rem; align-items: center;">
                                <div style="width: 60px; height: 60px; background: var(--color-secundario); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; font-weight: 800; flex-shrink: 0;">
                                    <?= mb_strtoupper(mb_substr($autorInfo['nombre_completo'], 0, 1)) ?>
                                </div>
                                <div>
                                    <h3 style="margin: 0 0 0.35rem 0; font-size: 1.15rem; color: var(--texto-titulos);">
                                        <?= htmlspecialchars($autorInfo['nombre_completo']) ?>
                                    </h3>
                                    <?php if (!empty($autorInfo['orcid'])): ?>
                                        <?php $urlOrcid = strpos($autorInfo['orcid'], 'http') === 0 ? $autorInfo['orcid'] : 'https://orcid.org/' . $autorInfo['orcid']; ?>
                                        <a href="<?= htmlspecialchars($urlOrcid) ?>" target="_blank" title="Ver perfil verificado en ORCID" style="color: var(--color-terciario); font-size: 0.85rem; display: flex; align-items: center; gap: 0.3rem; text-decoration: none; font-weight: 700;">
                                            <i class="ph-fill ph-identification-badge" style="font-size: 1.2rem;"></i> ORCID: <?= htmlspecialchars($autorInfo['orcid']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span style="font-size: 0.8rem; color: #94a3b8; display: flex; align-items: center; gap: 0.3rem;"><i class="ph-fill ph-identification-badge"></i> Sin ORCID registrado</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div style="flex: 1;">
                                <p style="margin: 0; font-size: 0.9rem; color: var(--texto-silenciado); display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.5;">
                                    <?= !empty($autorInfo['biografia']) ? nl2br(htmlspecialchars($autorInfo['biografia'])) : 'El investigador no posee una biografía o resumen académico registrado.' ?>
                                </p>
                            </div>
                            
                            <div style="border-top: 1px solid rgba(0,0,0,0.06); padding-top: 1rem; display: flex; justify-content: space-between; align-items: center;">
                                <?php if (!empty($autorInfo['pagina_web'])): ?>
                                    <a href="<?= htmlspecialchars($autorInfo['pagina_web']) ?>" target="_blank" style="color: var(--color-terciario);margin-right:0.5rem; font-size: 0.85rem; text-decoration: none; font-weight: 700; display: flex; align-items: center; gap: 0.3rem;">
                                        <i class="ph-bold ph-globe"></i> Visitar Web
                                    </a>
                                <?php else: ?>
                                    <span></span>
                                <?php endif; ?> 
                                
                                <!-- Al hacer clic lo mandamos a la pestaña de artículos filtrando por él -->
                                <a href="perfil-autor?id=<?= (int)$autorInfo['id'] ?>" class="art-btn-read" style="padding: 0.45rem 0.9rem; font-size: 0.85rem; border-radius: 6px;">
                                    Ver Publicaciones <i class="ph-bold ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?php if (($autoresCatalogo['paginas'] ?? 0) > 1): ?>
                <div class="pagination" style="margin-top: 2rem;">
                    <?php 
                    $pagActAut = $autoresCatalogo['pagina_actual'];
                    $pagTotAut = $autoresCatalogo['paginas'];
                    $qUrl = urlencode($q_autores_tab);
                    ?>
                    <?php if ($pagActAut > 1): ?>
                        <a class="page-link" href="articulos?tab=autores&q_autores_tab=<?= $qUrl ?>&p_aut=<?= $pagActAut - 1 ?>">← Anterior</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $pagTotAut; $i++): ?>
                        <a class="page-link <?= $i === $pagActAut ? 'active' : '' ?>" href="articulos?tab=autores&q_autores_tab=<?= $qUrl ?>&p_aut=<?= $i ?>"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php if ($pagActAut < $pagTotAut): ?>
                        <a class="page-link" href="articulos?tab=autores&q_autores_tab=<?= $qUrl ?>&p_aut=<?= $pagActAut + 1 ?>">Siguiente →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
        
    <?php endif; ?>

<!-- INYECCIÓN DEL MODAL Y SCRIPTS GLOBALES -->
<script>
const configuracionesCitas = <?= json_encode(ConfigService::get('citas.estilos', [])) ?>;

// --- UTILIDADES UNIVERSALES DE PORTAPAPELES Y NOTIFICACIÓN ---
function copiarAlPortapapeles(texto, onSuccess) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(texto).then(() => {
            if (typeof onSuccess === 'function') onSuccess();
        }).catch(() => {
            fallbackCopiarTexto(texto, onSuccess);
        });
    } else {
        fallbackCopiarTexto(texto, onSuccess);
    }
}

function fallbackCopiarTexto(texto, onSuccess) {
    const textArea = document.createElement('textarea');
    textArea.value = texto;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    textArea.style.opacity = '0';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        const ok = document.execCommand('copy');
        if (ok && typeof onSuccess === 'function') {
            onSuccess();
        }
    } catch (err) {
        console.error('Error al copiar vía fallback:', err);
    }
    document.body.removeChild(textArea);
}

function mostrarToastNotificacion(mensaje) {
    let toast = document.getElementById('ciidi-toast-notification');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'ciidi-toast-notification';
        toast.className = 'art-toast-pill';
        document.body.appendChild(toast);
    }
    toast.innerHTML = '<i class="ph-bold ph-check-circle" style="color:#10B981; font-size:1.2rem;"></i> ' + mensaje;
    toast.classList.add('is-visible');
    
    clearTimeout(window._ciidiToastTimer);
    window._ciidiToastTimer = setTimeout(() => {
        toast.classList.remove('is-visible');
    }, 2500);
}

function compartirEnlaceArticulo(idArticulo, btn) {
    const urlFinal = new URL('leer-articulo?id=' + idArticulo, window.location.href).href;
    
    copiarAlPortapapeles(urlFinal, () => {
        const origHtml = btn.innerHTML;
        const origTitle = btn.getAttribute('title') || '';
        
        btn.innerHTML = '<i class="ph-bold ph-check"></i>';
        btn.classList.add('is-copied');
        btn.setAttribute('title', '¡Enlace copiado!');
        mostrarToastNotificacion('¡Enlace del artículo copiado al portapapeles!');
        
        setTimeout(() => {
            btn.innerHTML = origHtml;
            btn.classList.remove('is-copied');
            btn.setAttribute('title', origTitle);
        }, 2000);
    });
}

function compartirEnlace(rutaArchivo, btn) {
    let urlFinal = '';
    if (rutaArchivo && rutaArchivo !== '#' && typeof rutaArchivo === 'string' && rutaArchivo.trim() !== '') {
        const link = document.createElement('a');
        link.href = rutaArchivo;
        urlFinal = link.href;
    } else {
        urlFinal = window.location.href;
    }
    
    copiarAlPortapapeles(urlFinal, () => {
        const origHtml = btn.innerHTML;
        const origTitle = btn.getAttribute('title') || '';
        btn.innerHTML = '<i class="ph-bold ph-check"></i>';
        btn.classList.add('is-copied');
        btn.setAttribute('title', '¡Enlace copiado!');
        mostrarToastNotificacion('¡Enlace copiado al portapapeles!');
        setTimeout(() => {
            btn.innerHTML = origHtml;
            btn.classList.remove('is-copied');
            btn.setAttribute('title', origTitle);
        }, 2000);
    });
}

function abrirModalCitaDesdeBtn(btn) {
    const ds = btn.dataset;
    abrirModalCita(ds.titulo, ds.autores, ds.anio, ds.editorial, ds.volumen, ds.numero, ds.issn);
}

function abrirModalCita(titulo, autores, anio, editorial, volumen, numero, issn) {
    const listEl = document.getElementById('listaCitasDinamicas');
    const modalEl = document.getElementById('modalCitasContainer');
    if (!listEl || !modalEl) return;
    listEl.innerHTML = '';
    
    const mockData = {
        '{autores}': autores || 'Autor no registrado', 
        '{anio}': anio || 's.f.', 
        '{titulo}': titulo || 'Sin Título',
        '{editorial}': editorial || 'S/E', 
        '{volumen}': volumen || '', 
        '{numero}': numero || '', 
        '{issn}': issn || ''
    };
    
    let count = 0;
    if (typeof configuracionesCitas === 'object' && configuracionesCitas !== null) {
        for (const [slug, item] of Object.entries(configuracionesCitas)) {
            if (!item || !item.activo) continue;
            count++;
            let textoCita = item.plantilla || '';
            for (const [k, v] of Object.entries(mockData)) { 
                textoCita = textoCita.replaceAll(k, v); 
            }
            
            const boxId = 'cita_txt_' + slug;
            listEl.insertAdjacentHTML('beforeend', `
                <div style="margin-bottom: 1rem; background: var(--bg-card, #f8fafc); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 8px; padding: 0.85rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                        <span style="font-size: 0.8rem; font-weight: 700; color: var(--color-secundario, #505984); text-transform: uppercase; letter-spacing: 0.03em;">
                            <i class="ph-bold ph-bookmark-simple"></i> ${item.nombre || slug}
                        </span>
                        <button type="button" onclick="copiarCitaText('${boxId}', this)" style="padding: 0.3rem 0.65rem; font-size: 0.75rem; background: var(--color-secundario, #505984); color: #ffffff; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 0.35rem; transition: all 0.2s ease;">
                            <i class="ph-bold ph-copy"></i> Copiar
                        </button>
                    </div>
                    <div id="${boxId}" style="background: rgba(0,0,0,0.02); border: 1px solid rgba(0,0,0,0.06); padding: 0.65rem 0.8rem; border-radius: 6px; font-size: 0.84rem; line-height: 1.5; color: var(--texto-comun, #1e293b); word-break: break-word; font-family: inherit;">${textoCita}</div>
                </div>
            `);
        }
    }

    if (count === 0) {
        listEl.innerHTML = `
            <div style="text-align: center; padding: 2rem 1rem; color: var(--texto-silenciado, #64748b);">
                <i class="ph-bold ph-info" style="font-size: 2.2rem; display: block; margin-bottom: 0.5rem; color: var(--color-terciario, #7090cb);"></i>
                <p style="margin: 0; font-size: 0.9rem;">No hay formatos de cita académica configurados activos.</p>
            </div>
        `;
    }
    
    modalEl.style.display = 'flex';
}

function cerrarModalCitas() { 
    const modalEl = document.getElementById('modalCitasContainer');
    if (modalEl) modalEl.style.display = 'none'; 
}

function copiarCitaText(id, btn) {
    const el = document.getElementById(id);
    if (!el) return;
    const text = el.textContent.trim();
    
    copiarAlPortapapeles(text, () => {
        const origHtml = btn.innerHTML;
        btn.innerHTML = '<i class="ph-bold ph-check"></i> ¡Copiado!';
        btn.style.background = '#10b981';
        mostrarToastNotificacion('¡Cita académica copiada al portapapeles!');
        setTimeout(() => { 
            btn.innerHTML = origHtml; 
            btn.style.background = '';
        }, 2000);
    });
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModalCitas();
    }
});

function setArtViewMode(mode) {
    const gridEl = document.querySelector('.art-masonry-grid');
    const btnGrid = document.getElementById('btnViewGrid');
    const btnList = document.getElementById('btnViewList');
    if (!gridEl) return;

    if (mode === 'list') {
        gridEl.classList.add('art-view-list');
        if (btnList) btnList.classList.add('active');
        if (btnGrid) btnGrid.classList.remove('active');
        localStorage.setItem('art_view_mode', 'list');
    } else {
        gridEl.classList.remove('art-view-list');
        if (btnGrid) btnGrid.classList.add('active');
        if (btnList) btnList.classList.remove('active');
        localStorage.setItem('art_view_mode', 'grid');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const savedMode = localStorage.getItem('art_view_mode') || 'grid';
    setArtViewMode(savedMode);
});
</script>
<script src="../modules/Articulos/assets/js/hero_typography.js"></script>

<div id="modalCitasContainer" onclick="if (event.target === this) cerrarModalCitas();" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(6px); z-index: 99999; align-items: center; justify-content: center; padding: 1rem; box-sizing: border-box;">
    <div style="background: var(--color-principal, #ffffff); color: var(--texto-titulos, #1e293b); border-radius: 14px; width: 100%; max-width: 560px; padding: 1.5rem; box-shadow: 0 20px 45px rgba(0,0,0,0.3); border: 1px solid rgba(80, 89, 132, 0.2); animation: artModalFadeIn 0.2s ease;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(0,0,0,0.08); padding-bottom: 0.85rem; margin-bottom: 1.25rem;">
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 700; color: var(--color-secundario, #505984); display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph-bold ph-quotes" style="font-size: 1.3rem;"></i> Cita Académica
            </h3>
            <button type="button" onclick="cerrarModalCitas()" aria-label="Cerrar modal" style="background: rgba(0,0,0,0.05); border: none; width: 32px; height: 32px; border-radius: 8px; font-size: 1.2rem; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; color: var(--texto-silenciado, #64748b); transition: all 0.2s;">&times;</button>
        </div>
        <div id="listaCitasDinamicas" style="max-height: 60vh; overflow-y: auto; padding-right: 4px;"></div>
    </div>
</div>

<script src="../modules/Articulos/assets/js/lazy_loading.js"></script>
    </main>
</div>