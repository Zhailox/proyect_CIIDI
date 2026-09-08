<?php
require_once __DIR__ . '/../services/ConfigService.php';
$paginaActual = (int) ($paginacion['pagina'] ?? 1);
$paginasTotales = (int) ($paginacion['paginas'] ?? 1);

$buildUrl = function($page = 1, $overrideParams = []) use ($filtros) {
    $params = [
        'page' => $page,
        'q' => isset($overrideParams['q']) ? $overrideParams['q'] : ($filtros['q'] ?? ''),
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

    <!-- PANEL DE CONTROL Y FILTROS HORIZONTALES SUPERIOR (MULTISELECCIÓN MULTI-CATEGORÍA Y MULTI-ETIQUETA) -->
    <header class="art-top-filter-panel">
        <form action="articulos" method="GET" class="art-top-filter-form">
            
            <!-- CAMPOS OCULTOS PARA PRESERVAR MULTISELECCIÓN -->
            <?php foreach (($filtros['categorias'] ?? []) as $cId): ?>
                <input type="hidden" name="categoria[]" value="<?= (int)$cId ?>">
            <?php endforeach; ?>
            <?php foreach (($filtros['etiquetas'] ?? []) as $eId): ?>
                <input type="hidden" name="etiqueta[]" value="<?= (int)$eId ?>">
            <?php endforeach; ?>

            <!-- 1. FILA DE PILLS DE CATEGORÍAS (MULTISELECCIÓN CON TOGGLE) -->
            <div class="art-category-pills-bar">
                <span class="art-pills-label"><i class="ph-bold ph-squares-four"></i> Categorías:</span>
                <div class="art-pills-scroll-wrapper">
                    <?php 
                        $catsSeleccionadas = array_map('intval', $filtros['categorias'] ?? []);
                        $todasActive = empty($catsSeleccionadas);
                    ?>
                    <a href="<?= $buildUrlToggleCat(null) ?>" class="art-top-pill <?= $todasActive ? 'active' : '' ?>">
                        Todas
                    </a>
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

            <!-- 2. FILA DE PILLS DE ETIQUETAS (MULTISELECCIÓN CON TOGGLE) -->
            <?php if (!empty($etiquetas)): ?>
                <div class="art-category-pills-bar" style="border-bottom: none; padding-bottom: 0;">
                    <span class="art-pills-label"><i class="ph-bold ph-hash"></i> Etiquetas:</span>
                    <div class="art-pills-scroll-wrapper">
                        <?php 
                            $etisSeleccionadas = array_map('intval', $filtros['etiquetas'] ?? []);
                        ?>
                        <?php foreach ($etiquetas as $eti): ?>
                            <?php $isActiveEti = in_array((int)$eti['id'], $etisSeleccionadas); ?>
                            <a href="<?= $buildUrlToggleEti((int)$eti['id']) ?>" class="art-top-pill <?= $isActiveEti ? 'active' : '' ?>" style="<?= $isActiveEti ? 'background: var(--color-secundario, #0b1a30); color: #ffffff;' : '' ?>" title="<?= $isActiveEti ? 'Desactivar etiqueta' : 'Añadir etiqueta' ?>">
                                #<?= htmlspecialchars($eti['nombre']) ?> <?= $isActiveEti ? '✓' : '' ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- 3. BARRA DE BÚSQUEDA Y SELECTOR DE AÑO -->
            <div class="art-filter-controls-row">
                <!-- BUSCADOR -->
                <div class="art-search-box">
                    <i class="ph-bold ph-magnifying-glass art-search-icon"></i>
                    <input type="text" name="q" class="art-top-search-input" placeholder="Buscar artículo por título, autor..." value="<?= htmlspecialchars($filtros['q'] ?? '') ?>">
                </div>

                <!-- SELECTOR DE AÑO -->
                <div class="art-select-box">
                    <select name="year" class="art-top-select">
                        <option value="">Todos los años</option>
                        <?php 
                        $anioActual = (int)date('Y');
                        $anioMinimo = (int)ConfigService::get('buscador.anio_minimo', 2020);
                        for ($y = $anioActual; $y >= $anioMinimo; $y--): 
                        ?>
                            <option value="<?= $y ?>" <?= (isset($filtros['year']) && $filtros['year'] == $y) ? 'selected' : '' ?>>
                                Año <?= $y ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- BOTÓN FILTRAR -->
                <button type="submit" class="art-btn-read" style="padding: 0.6rem 1.1rem; font-size: 0.85rem;">
                    <i class="ph-bold ph-funnel"></i> Buscar / Filtrar
                </button>

                <?php if (!empty($filtros['q']) || !empty($filtros['year']) || !empty($filtros['categorias']) || !empty($filtros['etiquetas'])): ?>
                    <a href="articulos" class="art-link-reset" style="margin: 0; align-self: center;">
                        <i class="ph-bold ph-arrows-counter-clockwise"></i> Limpiar Todo
                    </a>
                <?php endif; ?>

                <!-- ALTERNADOR DE VISTA (GRID / LISTA ESPACIOSA) -->
                <div class="art-view-toggle-box" style="display: flex; gap: 0.25rem; background: #f1f5f9; padding: 0.2rem; border-radius: 6px; border: 1px solid rgba(11,26,48,0.1); margin-left: auto;">
                    <button type="button" id="btnViewGrid" class="art-view-btn active" title="Vista Cuadrícula (Tarjetas)" onclick="setArtViewMode('grid')">
                        <i class="ph-bold ph-squares-four"></i>
                    </button>
                    <button type="button" id="btnViewList" class="art-view-btn" title="Vista Lista Espaciosa" onclick="setArtViewMode('list')">
                        <i class="ph-bold ph-rows"></i>
                    </button>
                </div>
            </div>

            <!-- 4. CHIPS DE FILTROS ACTIVOS (1-CLICK PARA QUITAR INDIVIDUALMENTE) -->
            <?php 
            $hayFiltrosActivos = !empty($filtros['q']) || !empty($filtros['year']) || !empty($filtros['categorias']) || !empty($filtros['etiquetas']);
            ?>
            <?php if ($hayFiltrosActivos): ?>
                <div class="art-active-chips-row">
                    <span style="font-size: 0.78rem; font-weight: 700; color: var(--color-secundario);">Filtros activos:</span>
                    
                    <?php if (!empty($filtros['q'])): ?>
                        <a href="<?= $buildUrlRemoveParam('q') ?>" class="art-chip-tag" title="Quitar búsqueda">
                            Búsqueda: "<?= htmlspecialchars($filtros['q']) ?>" <i class="ph-bold ph-x"></i>
                        </a>
                    <?php endif; ?>

                    <?php if (!empty($filtros['year'])): ?>
                        <a href="<?= $buildUrlRemoveParam('year') ?>" class="art-chip-tag" title="Quitar filtro de año">
                            Año: <?= htmlspecialchars($filtros['year']) ?> <i class="ph-bold ph-x"></i>
                        </a>
                    <?php endif; ?>

                    <?php foreach (($filtros['categorias'] ?? []) as $catId): ?>
                        <?php 
                            $nomCat = 'Categoría';
                            foreach ($categorias as $c) { if ((int)$c['id'] === (int)$catId) { $nomCat = $c['nombre']; break; } }
                        ?>
                        <a href="<?= $buildUrlToggleCat((int)$catId) ?>" class="art-chip-tag" title="Quitar esta categoría">
                            Cat: <?= htmlspecialchars($nomCat) ?> <i class="ph-bold ph-x"></i>
                        </a>
                    <?php endforeach; ?>

                    <?php foreach (($filtros['etiquetas'] ?? []) as $etiId): ?>
                        <?php 
                            $nomEti = 'Etiqueta';
                            foreach ($etiquetas as $e) { if ((int)$e['id'] === (int)$etiId) { $nomEti = $e['nombre']; break; } }
                        ?>
                        <a href="<?= $buildUrlToggleEti((int)$etiId) ?>" class="art-chip-tag" title="Quitar esta etiqueta">
                            #<?= htmlspecialchars($nomEti) ?> <i class="ph-bold ph-x"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <input type="hidden" name="page" value="1">
        </form>
    </header>

    <!-- GRID DE TARJETAS UNIFORMES (3-4 COLUMNAS) -->
    <main class="art-catalog-content" style="width: 100%;">

        <div class="art-masonry-grid">
            <?php if (empty($articulos)): ?>
                <p class="art-empty-state" style="grid-column: 1/-1;">No hay artículos que coincidan con los criterios de búsqueda.</p>
            <?php else: ?>
                <?php foreach ($articulos as $art): ?>
                    <?php 
                        $imgPortada = $art['imagen_portada'] ?? 'default_article.jpg';
                        $rutaImg = (strpos($imgPortada, 'http') === 0) ? htmlspecialchars($imgPortada) : '../storage/uploads/articulos/' . htmlspecialchars($imgPortada);
                        
                        // Limitación del título a máximo 60 caracteres
                        $tituloLimpio = htmlspecialchars($art['titulo'] ?? '');
                        $tituloCorto = (mb_strlen($tituloLimpio) > 60) ? mb_substr($tituloLimpio, 0, 57) . '...' : $tituloLimpio;
                    ?>
                    <article class="art-post-card">
                        <div class="art-card-img-wrapper">
                            <img data-src="<?= $rutaImg ?>" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 180'%3E%3Crect width='100%25' height='100%25' fill='%23f1f5f9'/%3E%3C/svg%3E" alt="<?= htmlspecialchars($art['titulo'] ?? 'Portada') ?>" class="art-post-img">
                            <span class="art-badge-cat"><?= htmlspecialchars($art['categoria'] ?? 'Artículo') ?></span>
                        </div>

                        <div class="art-post-body">
                            <div class="art-post-meta">
                                <span class="art-year-tag"><i class="ph-bold ph-calendar-blank"></i> <?= htmlspecialchars($art['anio_publicacion']) ?></span>
                                <?php if (ConfigService::get('recursos.mostrar_volumen', true)): ?>
                                    <span class="art-metric">
                                        Vol. <?= htmlspecialchars($art['volumen'] ?? 'N/A') ?>
                                        <?= !empty($art['numero']) ? ' - Núm. ' . htmlspecialchars($art['numero']) : '' ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <a href="leer-articulo?id=<?= $art['id'] ?>" class="art-post-title" title="<?= htmlspecialchars($art['titulo']) ?>">
                                <?= $tituloCorto ?>
                            </a>

                            <div class="art-authors-line">
                                <i class="ph-bold ph-users"></i> <?= htmlspecialchars($art['autores_text']) ?>
                            </div>

                            <!-- ACCIONES DE TARJETA ESTILIZADAS -->
                            <div class="art-card-actions">
                                <a href="leer-articulo?id=<?= $art['id'] ?>" class="art-btn-read">
                                    <i class="ph-bold ph-book-open"></i> Leer
                                </a>
                                <button type="button" class="art-action-icon-btn" title="Generar Cita Académica" onclick="abrirModalCita(<?= htmlspecialchars(json_encode($art['titulo'])) ?>, <?= htmlspecialchars(json_encode($art['autores_text'])) ?>, <?= $art['anio_publicacion'] ?>, <?= htmlspecialchars(json_encode($art['editorial'] ?? 'N/A')) ?>, <?= htmlspecialchars(json_encode($art['volumen'] ?? '')) ?>, <?= htmlspecialchars(json_encode($art['numero'] ?? '')) ?>, <?= htmlspecialchars(json_encode($art['issn'] ?? '')) ?>)">
                                    <i class="ph-bold ph-quotes"></i>
                                </button>
                                <button type="button" class="art-action-icon-btn" title="Compartir Enlace" onclick="compartirEnlace('<?= htmlspecialchars($art['archivo_pdf'] ?? '') ?>', this)">
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
                    <a class="page-link <?= $i === $paginaActual ? 'active' : '' ?>" href="<?= $buildUrl($i) ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($paginaActual < $paginasTotales): ?>
                    <a class="page-link" href="<?= $buildUrl($paginaActual + 1) ?>">Siguiente →</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

<!-- INYECCIÓN DEL MODAL Y SCRIPTS GLOBALES -->
<script>
const configuracionesCitas = <?= json_encode(ConfigService::get('citas.estilos', [])) ?>;

function abrirModalCita(titulo, autores, anio, editorial, volumen, numero, issn) {
    const listEl = document.getElementById('listaCitasDinamicas');
    if (!listEl) return;
    listEl.innerHTML = '';
    
    const mockData = {
        '{autores}': autores || 'S/A', '{anio}': anio || 's.f.', '{titulo}': titulo || 'Sin Título',
        '{editorial}': editorial || 'S/E', '{volumen}': volumen || '', '{numero}': numero || '', '{issn}': issn || ''
    };
    
    let count = 0;
    for (const [slug, item] of Object.entries(configuracionesCitas)) {
        if (!item.activo) continue;
        count++;
        let textoCita = item.plantilla || '';
        for (const [k, v] of Object.entries(mockData)) { textoCita = textoCita.replaceAll(k, v); }
        
        const boxId = 'cita_txt_' + slug;
        listEl.insertAdjacentHTML('beforeend', `
            <div style="margin-bottom: 0.85rem;">
                <strong style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: var(--color-secundario); text-transform: uppercase; margin-bottom: 0.25rem;">
                    <span>${item.nombre || slug}</span>
                    <button type="button" onclick="copiarCitaText('${boxId}', this)" style="padding: 0.2rem 0.5rem; font-size: 0.7rem; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 3px; cursor: pointer; font-weight: 700;"><i class="ph ph-copy"></i> Copiar</button>
                </strong>
                <div id="${boxId}" style="background: #fafbfe; border: 1px solid rgba(169, 168, 166, 0.15); padding: 0.5rem 0.65rem; border-radius: 4px; font-size: 0.8rem; font-family: monospace;">${textoCita}</div>
            </div>
        `);
    }
    document.getElementById('modalCitasContainer').style.display = 'flex';
}

function cerrarModalCitas() { document.getElementById('modalCitasContainer').style.display = 'none'; }

function compartirEnlace(rutaArchivo, btn) {
    if (!rutaArchivo) {
        alert('Este artículo no tiene un archivo enlazado.');
        return;
    }
    
    // Convertir ruta relativa a URL absoluta
    const linkAbsoluto = document.createElement('a');
    linkAbsoluto.href = rutaArchivo;
    const urlFinal = linkAbsoluto.href;

    const origHtml = btn.innerHTML;
    navigator.clipboard.writeText(urlFinal).then(() => {
        btn.innerHTML = '<i class="ph ph-check"></i> Copiado';
        setTimeout(() => { btn.innerHTML = origHtml; }, 2000);
    });
}

function copiarCitaText(id, btn) {
    const text = document.getElementById(id).textContent;
    const origHtml = btn.innerHTML;
    navigator.clipboard.writeText(text).then(() => {
        btn.innerHTML = '<i class="ph ph-check"></i> ¡Copiado!';
        setTimeout(() => { btn.innerHTML = origHtml; }, 2000);
    });
}

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

<div id="modalCitasContainer" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 34, 68, 0.7); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 8px; width: 90%; max-width: 540px; padding: 1.25rem; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(0,0,0,0.1); padding-bottom: 0.5rem; margin-bottom: 1rem;">
            <h3 style="margin: 0;"><i class="ph ph-quotes"></i> Cita Académica</h3>
            <button type="button" onclick="cerrarModalCitas()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer;">&times;</button>
        </div>
        <div id="listaCitasDinamicas" style="max-height: 380px; overflow-y: auto;"></div>
    </div>
</div>

<script src="../modules/Articulos/assets/js/lazy_loading.js"></script>
    </main>
</div>