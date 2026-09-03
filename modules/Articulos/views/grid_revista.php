<?php
require_once __DIR__ . '/../services/ConfigService.php';
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
    <details class="art-filter-accordion">
        <summary>Categorías</summary>
        <!-- Envolvemos el contenido en el contenedor flotante -->
        <div class="art-filter-dropdown">
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
        </div>
    </details>
</div>

<div class="art-filter-group">
    <details class="art-filter-accordion">
        <summary>Etiquetas</summary>
        <!-- Envolvemos el contenido en el contenedor flotante -->
        <div class="art-filter-dropdown">
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
        </div>
    </details>
</div>

        <div class="art-filter-group">
                <label for="year_filter">Año de Publicación</label>
                <select id="year_filter" name="year" class="art-select-input">
                    <option value="">Todos los años (Sin límite)</option>
                    <?php 
                    $anioActual = (int)date('Y');
                    for ($y = $anioActual; $y >= $anioActual - 10; $y--): 
                    ?>
                        <option value="<?= $y ?>" <?= (isset($filtros['year']) && $filtros['year'] == $y) ? 'selected' : '' ?>>
                            <?= $y ?>
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
                $rutaImg = (strpos($imgPortada, 'http') === 0) ? htmlspecialchars($imgPortada) : '../public/uploads/articulos/' . htmlspecialchars($imgPortada);
            ?>
            <div class="art-featured-img" style="background-image: url('<?= $rutaImg ?>');"></div>
            <div class="art-featured-body">
                <div class="art-post-meta">
                    <div class="art-tags">
                        <span class="art-tag" style="background: var(--color-secundario); color: white;">ÚLTIMA PUBLICACIÓN</span>
                        <span class="art-tag"><?= htmlspecialchars($ultimo['categoria'] ?? 'Investigación') ?></span>
                    </div>
                    <?php if (ConfigService::get('recursos.mostrar_volumen', true)): ?>
                        <span class="art-metric">
                            Vol. <?= htmlspecialchars($ultimo['volumen'] ?? 'N/A') ?>
                            <?= !empty($ultimo['numero']) ? ' - Núm. ' . htmlspecialchars($ultimo['numero']) : '' ?>
                        </span>
                    <?php endif; ?>
                </div>

                <a href="leer-articulo?id=<?= $ultimo['id'] ?>" class="art-post-title" style="font-size: 1.4rem;">
                    <?= htmlspecialchars($ultimo['titulo']) ?>
                </a>
                
                <p class="art-abstract"><?= htmlspecialchars($ultimo['resumen'] ?? 'Sin resumen disponible.') ?></p>
                <p class="art-authors" style="margin-bottom: 1rem;">
                    Publicado: <?= htmlspecialchars($ultimo['anio_publicacion']) ?>
                    <span style="display:block; margin-top:0.25rem;">Autores: <?= htmlspecialchars($ultimo['autores_text']) ?></span>
                </p>

                <!-- BOTONES NUEVOS -->
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    <a href="leer-articulo?id=<?= $ultimo['id'] ?>" class="btn" style="padding: 0.4rem 1rem; font-size: 0.8rem;">Leer Completamente</a>
                    <button type="button" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;" onclick="abrirModalCita(<?= htmlspecialchars(json_encode($ultimo['titulo'])) ?>, <?= htmlspecialchars(json_encode($ultimo['autores_text'])) ?>, <?= $ultimo['anio_publicacion'] ?>, <?= htmlspecialchars(json_encode($ultimo['editorial'] ?? 'N/A')) ?>, <?= htmlspecialchars(json_encode($ultimo['volumen'] ?? '')) ?>, <?= htmlspecialchars(json_encode($ultimo['numero'] ?? '')) ?>, <?= htmlspecialchars(json_encode($ultimo['issn'] ?? '')) ?>)">
                        <i class="ph ph-quotes"></i> Citar
                    </button>
                    <button type="button" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;" onclick="compartirEnlace(<?= $ultimo['id'] ?>, this)">
                        <i class="ph ph-share-network"></i> Compartir
                    </button>
                </div>
            </div>
        </article>
        <?php endif; ?>

        <!-- GRID DEL CATÁLOGO RESTANTE -->
        <div class="art-masonry-grid">
            <?php 
            $restoArticulos = array_slice($articulos, 1);
            if (empty($restoArticulos)): 
            ?>
                <p class="art-empty-state" style="grid-column: 1/-1;">No hay artículos que coincidan con los filtros seleccionados.</p>
            <?php else: ?>
                <?php foreach ($restoArticulos as $art): ?>
                <article class="art-post-card">
                    <?php 
                        $imgPortada = $art['imagen_portada'] ?? 'default_article.jpg';
                        $rutaImg = (strpos($imgPortada, 'http') === 0) ? htmlspecialchars($imgPortada) : '../public/uploads/articulos/' . htmlspecialchars($imgPortada);
                    ?>
                    <div class="art-post-img" style="background-image: url('<?= $rutaImg ?>');"></div>
                    <div class="art-post-body">
                        <div class="art-post-meta">
                            <span class="art-tag"><?= htmlspecialchars($art['categoria'] ?? 'Artículo') ?></span>
                            <?php if (ConfigService::get('recursos.mostrar_volumen', true)): ?>
                                <span class="art-metric">
                                    Vol. <?= htmlspecialchars($art['volumen'] ?? 'N/A') ?>
                                    <?= !empty($art['numero']) ? ' - Núm. ' . htmlspecialchars($art['numero']) : '' ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <a href="leer-articulo?id=<?= $art['id'] ?>" class="art-post-title" style="margin-bottom:0.5rem;"><?= htmlspecialchars($art['titulo']) ?></a>
                        
                        <?php
                        $summaryShort = mb_strlen($art['resumen'] ?? '') > 110 ? mb_substr($art['resumen'] ?? '', 0, 107) . '...' : ($art['resumen'] ?? '');
                        ?>
                        <p class="art-card-summary"><?= htmlspecialchars($summaryShort) ?></p>
                        
                        <p class="art-authors" style="margin-bottom: 1rem; border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 1rem;">
                            Publicado: <?= htmlspecialchars($art['anio_publicacion']) ?>
                            <span style="display:block; margin-top:0.25rem;">Autores: <?= htmlspecialchars($art['autores_text']) ?></span>
                        </p>

                        <!-- BOTONES NUEVOS -->
                        <div style="display: flex; gap: 0.3rem; margin-top: auto;">
                            <button type="button" class="btn btn-secondary" style="flex:1; padding: 0.3rem; font-size: 0.8rem;" onclick="abrirModalCita(<?= htmlspecialchars(json_encode($art['titulo'])) ?>, <?= htmlspecialchars(json_encode($art['autores_text'])) ?>, <?= $art['anio_publicacion'] ?>, <?= htmlspecialchars(json_encode($art['editorial'] ?? 'N/A')) ?>, <?= htmlspecialchars(json_encode($art['volumen'] ?? '')) ?>, <?= htmlspecialchars(json_encode($art['numero'] ?? '')) ?>, <?= htmlspecialchars(json_encode($art['issn'] ?? '')) ?>)">
                                <i class="ph ph-quotes"></i> Citar
                            </button>
                            <button type="button" class="btn btn-secondary" style="flex:1; padding: 0.3rem; font-size: 0.8rem;" onclick="compartirEnlace(<?= $art['id'] ?>, this)">
                                <i class="ph ph-share-network"></i> Compartir
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

function compartirEnlace(id, btn) {
    const url = window.location.origin + window.location.pathname.replace('/articulos', '') + '/leer-articulo?id=' + id;
    const origHtml = btn.innerHTML;
    navigator.clipboard.writeText(url).then(() => {
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
</script>

<div id="modalCitasContainer" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 34, 68, 0.7); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 8px; width: 90%; max-width: 540px; padding: 1.25rem; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(0,0,0,0.1); padding-bottom: 0.5rem; margin-bottom: 1rem;">
            <h3 style="margin: 0;"><i class="ph ph-quotes"></i> Cita Académica</h3>
            <button type="button" onclick="cerrarModalCitas()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer;">&times;</button>
        </div>
        <div id="listaCitasDinamicas" style="max-height: 380px; overflow-y: auto;"></div>
    </div>
</div>

    </main>
</div>
