<?php require_once __DIR__ . '/../services/ConfigService.php'; ?>

<div class="art-detail-container">
    
    <!-- ENLACE DE NAVEGACIÓN DE REGRESO -->
    <a href="articulos" class="art-detail-back-btn">
        <i class="ph-bold ph-arrow-left"></i> Volver al Catálogo de Artículos
    </a>

    <?php if (!empty($articulo)): ?>
        <?php
            $imgPortada = $articulo['imagen_portada'] ?? 'default_article.jpg';
            $rutaImg = (strpos($imgPortada, 'http') === 0) ? $imgPortada : '../storage/uploads/articulos/' . $imgPortada;
        ?>

        <article class="art-detail-card-main">
            
            <!-- CABECERA HERO DEL ARTÍCULO CIENTÍFICO -->
            <header class="art-detail-hero-box">
                <img src="<?= htmlspecialchars($rutaImg) ?>" alt="Portada" class="art-detail-hero-bg-img">
                
                <div class="art-detail-hero-content">
                    
                    <!-- PILLS DE METADATOS Y REVISTA -->
                    <div class="art-detail-meta-pills">
                        <?php 
                        $listaCategorias = !empty($articulo['categoria']) ? array_map('trim', explode(',', $articulo['categoria'])) : ['Artículo Científico'];
                        foreach ($listaCategorias as $catNom): 
                        ?>
                            <span class="art-pill-cat">
                                <i class="ph-bold ph-bookmark"></i> <?= htmlspecialchars($catNom) ?>
                            </span>
                        <?php endforeach; ?>

                        <span class="art-pill-vol">
                            <i class="ph-bold ph-calendar-blank"></i> Año <?= htmlspecialchars($articulo['anio_publicacion'] ?? 's.f.') ?>
                        </span>

                        <?php if (ConfigService::get('recursos.mostrar_volumen', true) && (!empty($articulo['volumen']) || !empty($articulo['numero']))): ?>
                            <span class="art-pill-vol">
                                Vol. <?= htmlspecialchars($articulo['volumen'] ?? 'N/A') ?>
                                <?= !empty($articulo['numero']) ? ' - Núm. ' . htmlspecialchars($articulo['numero']) : '' ?>
                            </span>
                        <?php endif; ?>

                        <?php if (!empty($articulo['issn'])): ?>
                            <span class="art-pill-vol">
                                ISSN: <?= htmlspecialchars($articulo['issn']) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- TÍTULO PRINCIPAL DEL ARTÍCULO -->
                    <h1 class="art-detail-title"><?= htmlspecialchars($articulo['titulo'] ?? 'Artículo sin título') ?></h1>

                    <!-- AUTORES DE LA PUBLICACIÓN -->
                    <div class="art-detail-authors-box">
                        <i class="ph-bold ph-users" style="font-size: 1.25rem; color: #7090cb;"></i>
                        <span>
                            <strong>Autores:</strong> <?= htmlspecialchars($articulo['autores_text'] ?? 'Autor no registrado') ?>
                        </span>
                    </div>

                    <!-- BARRA DE ACCIONES PRINCIPALES EN CABECERA -->
                    <div class="art-detail-actions-bar">
                        <button type="button" class="art-btn-read" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); padding: 0.65rem 1rem; font-size: 0.88rem;" onclick="abrirModalCita(<?= htmlspecialchars(json_encode($articulo['titulo'])) ?>, <?= htmlspecialchars(json_encode($articulo['autores_text'])) ?>, <?= $articulo['anio_publicacion'] ?>, <?= htmlspecialchars(json_encode($articulo['editorial'] ?? 'N/A')) ?>, <?= htmlspecialchars(json_encode($articulo['volumen'] ?? '')) ?>, <?= htmlspecialchars(json_encode($articulo['numero'] ?? '')) ?>, <?= htmlspecialchars(json_encode($articulo['issn'] ?? '')) ?>)">
                            <i class="ph-bold ph-quotes"></i> Generar Cita
                        </button>

                        <button type="button" class="art-action-icon-btn" style="width: 38px; height: 38px; background: rgba(255,255,255,0.15); color: white; border-color: rgba(255,255,255,0.3);" title="Copiar Enlace Directo" onclick="compartirEnlace(this)">
                            <i class="ph-bold ph-share-network"></i>
                        </button>
                    </div>

                </div>
            </header>

            <!-- GRID PRINCIPAL DE CONTENIDO Y FICHA TÉCNICA -->
            <div class="art-detail-grid-layout">
                
                <!-- COLUMNA IZQUIERDA: RESUMEN -->
                <div class="art-detail-main-col">
                    
                    <!-- BLOQUE DE RESUMEN / ABSTRACT -->
                    <div class="art-abstract-box">
                        <h3><i class="ph-bold ph-text-align-left"></i> Resumen / Abstract</h3>
                        <p><?= nl2br(htmlspecialchars($articulo['resumen'] ?? 'Sin resumen disponible para este artículo.')) ?></p>
                    </div>

                    <!-- ETIQUETAS / PALABRAS CLAVE -->
                    <?php if (!empty($articulo['etiquetas_nombres'])): ?>
                        <div style="margin-top: 2rem;">
                            <h4 style="color: var(--color-secundario); font-size: 0.95rem; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.4rem;">
                                <i class="ph-bold ph-hash"></i> Palabras Clave / Etiquetas
                            </h4>
                            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                <?php foreach ($articulo['etiquetas_nombres'] as $tag): ?>
                                    <span style="background: rgba(112, 144, 203, 0.1); color: var(--color-secundario); padding: 0.3rem 0.75rem; border-radius: 4px; font-size: 0.82rem; font-weight: 600;">
                                        #<?= htmlspecialchars($tag) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>

                <!-- COLUMNA DERECHA: FICHA TÉCNICA Y METADATOS -->
                <aside class="art-detail-sidebar-col">
                    <div class="art-metadata-card">
                        <h4><i class="ph-bold ph-info"></i> Ficha Técnica del Artículo</h4>

                        <div class="art-meta-item">
                            <span class="art-meta-label">ID Recurso:</span>
                            <span class="art-meta-val">#<?= (int)$articulo['id'] ?></span>
                        </div>

                        <div class="art-meta-item">
                            <span class="art-meta-label">Categoría:</span>
                            <span class="art-meta-val"><?= htmlspecialchars($articulo['categoria'] ?? 'Sin categoría') ?></span>
                        </div>

                        <div class="art-meta-item">
                            <span class="art-meta-label">Año de Publicación:</span>
                            <span class="art-meta-val"><?= htmlspecialchars($articulo['anio_publicacion'] ?? 'N/A') ?></span>
                        </div>

                        <?php if (!empty($articulo['volumen'])): ?>
                            <div class="art-meta-item">
                                <span class="art-meta-label">Volumen:</span>
                                <span class="art-meta-val">Vol. <?= htmlspecialchars($articulo['volumen']) ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($articulo['numero'])): ?>
                            <div class="art-meta-item">
                                <span class="art-meta-label">Número:</span>
                                <span class="art-meta-val">Núm. <?= htmlspecialchars($articulo['numero']) ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($articulo['editorial'])): ?>
                            <div class="art-meta-item">
                                <span class="art-meta-label">Editorial:</span>
                                <span class="art-meta-val"><?= htmlspecialchars($articulo['editorial']) ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($articulo['issn'])): ?>
                            <div class="art-meta-item">
                                <span class="art-meta-label">ISSN:</span>
                                <span class="art-meta-val"><?= htmlspecialchars($articulo['issn']) ?></span>
                            </div>
                        <?php endif; ?>

                        <button type="button" class="art-btn-filter-apply" style="margin-top: 1.25rem;" onclick="abrirModalCita(<?= htmlspecialchars(json_encode($articulo['titulo'])) ?>, <?= htmlspecialchars(json_encode($articulo['autores_text'])) ?>, <?= $articulo['anio_publicacion'] ?>, <?= htmlspecialchars(json_encode($articulo['editorial'] ?? 'N/A')) ?>, <?= htmlspecialchars(json_encode($articulo['volumen'] ?? '')) ?>, <?= htmlspecialchars(json_encode($articulo['numero'] ?? '')) ?>, <?= htmlspecialchars(json_encode($articulo['issn'] ?? '')) ?>)">
                            <i class="ph-bold ph-quotes"></i> Formatos de Cita
                        </button>
                    </div>
                </aside>

            </div>

        </article>

        <!-- SECCIÓN DE ARTÍCULOS RELACIONADOS / RECOMENDADOS -->
        <?php if (!empty($similares)): ?>
            <div style="margin-top: 3rem;">
                <h3 style="color: var(--color-secundario); font-size: 1.35rem; font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="ph-bold ph-books"></i> Artículos Recomendados
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                    <?php foreach($similares as $sim): 
                        $img = $sim['imagen_portada'] ?? 'default_article.jpg';
                        $ruta = (strpos($img, 'http') === 0) ? htmlspecialchars($img) : '../storage/uploads/articulos/' . htmlspecialchars($img);
                        $titLimpio = htmlspecialchars($sim['titulo'] ?? '');
                        $titCorto = (mb_strlen($titLimpio) > 60) ? mb_substr($titLimpio, 0, 57) . '...' : $titLimpio;
                    ?>
                        <article class="art-post-card">
                            <div class="art-card-img-wrapper">
                                <img src="<?= $ruta ?>" alt="<?= htmlspecialchars($sim['titulo']) ?>" class="art-post-img">
                                <span class="art-badge-cat"><?= htmlspecialchars($sim['categoria']) ?></span>
                            </div>

                            <div class="art-post-body">
                                <div class="art-post-meta">
                                    <span class="art-year-tag"><i class="ph-bold ph-calendar-blank"></i> <?= $sim['anio_publicacion'] ?></span>
                                    <?php if (!empty($sim['volumen']) || !empty($sim['numero'])): ?>
                                        <span class="art-metric" style="font-size: 0.75rem; padding: 0.2rem 0.5rem;">
                                            Vol. <?= htmlspecialchars($sim['volumen'] ?? 'N/A') ?>
                                            <?= !empty($sim['numero']) ? ' - Núm. ' . htmlspecialchars($sim['numero']) : '' ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <a href="leer-articulo?id=<?= $sim['id'] ?>" class="art-post-title" title="<?= htmlspecialchars($sim['titulo']) ?>">
                                    <?= $titCorto ?>
                                </a>

                                <div class="art-authors-line">
                                    <i class="ph-bold ph-users"></i> <?= htmlspecialchars($sim['autores_text'] ?? 'Autor no registrado') ?>
                                </div>

                                <div class="art-card-actions">
                                    <a href="leer-articulo?id=<?= $sim['id'] ?>" class="art-btn-read">
                                        <i class="ph-bold ph-book-open"></i> Leer
                                    </a>
                                    <button type="button" class="art-action-icon-btn" title="Generar Cita Académica" onclick="abrirModalCita(<?= htmlspecialchars(json_encode($sim['titulo'])) ?>, <?= htmlspecialchars(json_encode($sim['autores_text'])) ?>, <?= $sim['anio_publicacion'] ?>, <?= htmlspecialchars(json_encode($sim['editorial'] ?? 'N/A')) ?>, <?= htmlspecialchars(json_encode($sim['volumen'] ?? '')) ?>, <?= htmlspecialchars(json_encode($sim['numero'] ?? '')) ?>, <?= htmlspecialchars(json_encode($sim['issn'] ?? '')) ?>)">
                                        <i class="ph-bold ph-quotes"></i>
                                    </button>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="art-empty-state">
            <i class="ph-bold ph-warning-circle" style="font-size: 3rem; color: #ef4444; margin-bottom: 1rem;"></i>
            <h3>Artículo no encontrado</h3>
            <p>No existe el artículo solicitado o fue retirado del catálogo.</p>
            <a href="articulos" class="art-btn-read" style="display: inline-flex; margin-top: 1rem;">Volver al Catálogo</a>
        </div>
    <?php endif; ?>

</div>

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
    
    for (const [slug, item] of Object.entries(configuracionesCitas)) {
        if (!item.activo) continue;
        let textoCita = item.plantilla || '';
        for (const [k, v] of Object.entries(mockData)) { textoCita = textoCita.replaceAll(k, v); }
        
        const boxId = 'cita_txt_' + slug;
        listEl.insertAdjacentHTML('beforeend', `
            <div style="margin-bottom: 0.85rem;">
                <strong style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: var(--color-secundario); text-transform: uppercase; margin-bottom: 0.25rem;">
                    <span>${item.nombre || slug}</span>
                    <button type="button" onclick="copiarCitaText('${boxId}', this)" style="padding: 0.2rem 0.5rem; font-size: 0.7rem; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 4px; cursor: pointer; font-weight: 700;"><i class="ph ph-copy"></i> Copiar</button>
                </strong>
                <div id="${boxId}" style="background: #fafbfe; border: 1px solid rgba(169, 168, 166, 0.15); padding: 0.5rem 0.65rem; border-radius: 4px; font-size: 0.8rem; font-family: monospace;">${textoCita}</div>
            </div>
        `);
    }
    document.getElementById('modalCitasContainer').style.display = 'flex';
}

function cerrarModalCitas() { document.getElementById('modalCitasContainer').style.display = 'none'; }

function compartirEnlace(btn) {
    const origHtml = btn.innerHTML;
    navigator.clipboard.writeText(window.location.href).then(() => {
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