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

                        <?php if (ConfigService::get('recursos.mostrar_issn', true) && !empty($articulo['issn'])): ?>
                            <span class="art-pill-vol">
                                ISSN: <?= htmlspecialchars($articulo['issn']) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- TÍTULO PRINCIPAL DEL ARTÍCULO -->
                    <h1 class="art-detail-title"><?= htmlspecialchars($articulo['titulo'] ?? 'Artículo sin título') ?></h1>

                    <div class="art-detail-authors-box" style="align-items: flex-start;">
                        <i class="ph-bold ph-users" style="font-size: 1.25rem; color: #7090cb; margin-top: 4px;"></i>
                        <div class="art-authors-line" style="margin-bottom: 0; gap: 0.5rem;">
                            <?php if (!empty($articulo['autores_data'])): ?>
                                <?php foreach($articulo['autores_data'] as $autorData): ?>
                                    <span style="display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2); padding:3px 10px; border-radius:6px; font-size:0.85rem; white-space:nowrap;">
                                        <!-- Enlace al Buscador Interno (Letra blanca para contraste) -->
                                        <a href="perfil-autor?id=<?= (int)$autorData['id'] ?>" style="color:#ffffff; text-decoration:none; font-weight:600;" title="Ver perfil de <?= htmlspecialchars($autorData['nombre']) ?>">
                                            <?= htmlspecialchars($autorData['nombre']) ?>
                                        </a>
                                        <?php if (!empty($autorData['orcid'])): ?>
                                            <?php $orcidUrl = strpos($autorData['orcid'], 'http') === 0 ? $autorData['orcid'] : 'https://orcid.org/' . $autorData['orcid']; ?>
                                            <a href="<?= htmlspecialchars($orcidUrl) ?>" target="_blank" title="Ver perfil ORCID" style="color:var(--color-terciario); display:flex; align-items:center; margin-left: 2px;">
                                                <i class="ph-fill ph-identification-badge" style="font-size:1.15rem;"></i>
                                            </a>
                                        <?php endif; ?>
                                    </span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span style="color: rgba(255,255,255,0.8);">Autor no registrado</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- BARRA DE ACCIONES PRINCIPALES EN CABECERA -->
                    <div class="art-detail-actions-bar">
                        <a class="art-btn-read" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); padding: 0.65rem 1rem; font-size: 0.88rem;" href="<?= htmlspecialchars($articulo['archivo_pdf'] ?? '#') ?>" target="_blank" rel="noopener noreferrer">
                            <i class="ph-bold ph-book-open"></i> Leer artículo
                        </a>
                        <button type="button" class="art-action-icon-btn" style="width: 38px; height: 38px; background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.3); font-size: 0.88rem;" title="Generar Cita Académica" onclick="abrirModalCita(<?= htmlspecialchars(json_encode($articulo['titulo'] ?? '')) ?>, <?= htmlspecialchars(json_encode($articulo['autores_text'] ?? 'S/A')) ?>, <?= json_encode($articulo['anio_publicacion'] ?? '') ?>, <?= htmlspecialchars(json_encode($articulo['editorial'] ?? 'S/E')) ?>, <?= htmlspecialchars(json_encode($articulo['volumen'] ?? '')) ?>, <?= htmlspecialchars(json_encode($articulo['numero'] ?? '')) ?>, <?= htmlspecialchars(json_encode($articulo['issn'] ?? '')) ?>)">
                            <i class="ph-bold ph-quotes"></i> 
                        </button>
                        <button type="button" class="art-action-icon-btn" style="width: 38px; height: 38px; background: rgba(255,255,255,0.15); color: white; border-color: rgba(255,255,255,0.3);" title="Copiar enlace del artículo" onclick="compartirEnlaceArticulo(<?= (int)$articulo['id'] ?>, this)">
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
                            <span class="art-meta-label">Editorial:</span>
                            <span class="art-meta-val"><?= htmlspecialchars($articulo['editorial'] ?? 'N/A') ?></span>
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
                                <i class="ph-bold ph-users"></i> 
                                <?php if (!empty($sim['autores_data'])): ?>
                                    <?php foreach($sim['autores_data'] as $autorData): ?>
                                        <span style="display:inline-flex; align-items:center; gap:4px; margin-right:8px; background:rgba(0,0,0,0.04); padding:2px 6px; border-radius:4px;">
                                            <!-- Enlace al Perfil del Autor -->
                                            <a href="perfil-autor?id=<?= (int)$autorData['id'] ?>" style="color:var(--color-terciario); text-decoration:none; font-weight:600;" title="Ver perfil de <?= htmlspecialchars($autorData['nombre']) ?>">
                                                <?= htmlspecialchars($autorData['nombre']) ?>
                                            </a>
                                            <!-- Enlace al ORCID -->
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
                                    <a href="leer-articulo?id=<?= $sim['id'] ?>" class="art-btn-read">
                                        <i class="ph-bold ph-book-open"></i> Leer
                                    </a>
                                    <button type="button" class="art-action-icon-btn" title="Generar Cita Académica" onclick="abrirModalCita(<?= htmlspecialchars(json_encode($sim['titulo'] ?? '')) ?>, <?= htmlspecialchars(json_encode($sim['autores_text'] ?? 'S/A')) ?>, <?= json_encode($sim['anio_publicacion'] ?? '') ?>, <?= htmlspecialchars(json_encode($sim['editorial'] ?? 'S/E')) ?>, <?= htmlspecialchars(json_encode($sim['volumen'] ?? '')) ?>, <?= htmlspecialchars(json_encode($sim['numero'] ?? '')) ?>, <?= htmlspecialchars(json_encode($sim['issn'] ?? '')) ?>)">
                                        <i class="ph-bold ph-quotes"></i>
                                    </button>
                                    <button type="button" class="art-action-icon-btn" title="Copiar enlace del artículo" onclick="compartirEnlaceArticulo(<?= (int)$sim['id'] ?>, this)">
                                        <i class="ph-bold ph-share-network"></i>
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
let toastTimerArt = null;

function mostrarToastArticulos(msg) {
    const toast = document.getElementById('artToastNotification');
    const toastTxt = document.getElementById('artToastText');
    if (!toast) return;
    if (toastTxt) toastTxt.textContent = msg;
    toast.classList.add('show');
    clearTimeout(toastTimerArt);
    toastTimerArt = setTimeout(() => {
        toast.classList.remove('show');
    }, 2500);
}

function fallbackCopiarTexto(texto, onSuccess) {
    const ta = document.createElement('textarea');
    ta.value = texto;
    ta.style.position = 'fixed';
    ta.style.left = '-9999px';
    ta.style.top = '-9999px';
    document.body.appendChild(ta);
    ta.focus();
    ta.select();
    try {
        document.execCommand('copy');
        if (typeof onSuccess === 'function') onSuccess();
    } catch (e) {
        alert('No se pudo copiar automáticamente: ' + texto);
    }
    document.body.removeChild(ta);
}

function compartirEnlaceArticulo(idArticulo, btn) {
    const url = new URL('leer-articulo?id=' + idArticulo, window.location.href).href;
    const origHtml = btn.innerHTML;
    
    const exito = () => {
        btn.classList.add('is-copied');
        btn.innerHTML = '<i class="ph-bold ph-check"></i>';
        mostrarToastArticulos('¡Enlace del artículo copiado al portapapeles!');
        setTimeout(() => {
            btn.innerHTML = origHtml;
            btn.classList.remove('is-copied');
        }, 2000);
    };

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url).then(exito).catch(() => {
            fallbackCopiarTexto(url, exito);
        });
    } else {
        fallbackCopiarTexto(url, exito);
    }
}

function abrirModalCita(titulo, autores, anio, editorial, volumen, numero, issn) {
    const listEl = document.getElementById('listaCitasDinamicas');
    const modal = document.getElementById('modalCitasContainer');
    if (!listEl || !modal) return;
    listEl.innerHTML = '';
    
    const mockData = {
        '{autores}': autores || 'S/A',
        '{anio}': anio || 's.f.',
        '{titulo}': titulo || 'Sin Título',
        '{editorial}': editorial || 'S/E',
        '{volumen}': volumen || '',
        '{numero}': numero || '',
        '{issn}': issn || ''
    };
    
    let count = 0;
    for (const [slug, item] of Object.entries(configuracionesCitas)) {
        if (!item.activo) continue;
        count++;
        let textoCita = item.plantilla || '';
        for (const [k, v] of Object.entries(mockData)) {
            textoCita = textoCita.replaceAll(k, String(v));
        }
        
        const boxId = 'cita_txt_' + slug;
        listEl.insertAdjacentHTML('beforeend', `
            <div class="art-modal-cita-item">
                <div class="art-modal-cita-header">
                    <span class="art-modal-cita-tag">${item.nombre || slug}</span>
                    <button type="button" class="art-modal-cita-copy-btn" onclick="copiarCitaText('${boxId}', this)">
                        <i class="ph-bold ph-copy"></i> Copiar
                    </button>
                </div>
                <div id="${boxId}" class="art-modal-cita-box">${textoCita}</div>
            </div>
        `);
    }

    if (count === 0) {
        listEl.innerHTML = '<p class="art-empty-state" style="padding: 1rem;">No hay formatos de cita activos configurados en el sistema.</p>';
    }

    modal.style.display = 'flex';
}

function cerrarModalCitas() {
    const modal = document.getElementById('modalCitasContainer');
    if (modal) modal.style.display = 'none';
}

function copiarCitaText(id, btn) {
    const el = document.getElementById(id);
    if (!el) return;
    const text = el.textContent;
    const origHtml = btn.innerHTML;
    
    const exito = () => {
        btn.innerHTML = '<i class="ph-bold ph-check"></i> ¡Copiado!';
        btn.classList.add('is-copied');
        mostrarToastArticulos('¡Cita académica copiada al portapapeles!');
        setTimeout(() => {
            btn.innerHTML = origHtml;
            btn.classList.remove('is-copied');
        }, 2000);
    };

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(exito).catch(() => {
            fallbackCopiarTexto(text, exito);
        });
    } else {
        fallbackCopiarTexto(text, exito);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const modalCitas = document.getElementById('modalCitasContainer');
    if (modalCitas) {
        modalCitas.addEventListener('click', function(e) {
            if (e.target === this) cerrarModalCitas();
        });
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModalCitas();
    }
});
</script>

<!-- MODAL CITAS ACADÉMICAS -->
<div id="modalCitasContainer" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(18, 26, 62, 0.7); backdrop-filter: blur(6px); z-index: 99999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; width: 90%; max-width: 560px; padding: 1.5rem; box-shadow: 0 20px 40px rgba(0,0,0,0.25); border: 1px solid rgba(80,89,132,0.18);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(80,89,132,0.15); padding-bottom: 0.75rem; margin-bottom: 1.25rem;">
            <h3 style="margin: 0; font-size: 1.15rem; color: var(--texto-titulos); display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph-bold ph-quotes" style="color: var(--color-secundario);"></i> Cita Académica
            </h3>
            <button type="button" onclick="cerrarModalCitas()" style="background: none; border: none; font-size: 1.3rem; cursor: pointer; color: var(--texto-silenciado); padding: 0.2rem;" title="Cerrar modal">&times;</button>
        </div>
        <div id="listaCitasDinamicas" style="max-height: 380px; overflow-y: auto;"></div>
        <div style="margin-top: 1rem; text-align: right; border-top: 1px solid rgba(80,89,132,0.1); padding-top: 0.75rem;">
            <button type="button" onclick="cerrarModalCitas()" class="art-btn-read" style="padding: 0.45rem 1.25rem; font-size: 0.85rem; border-radius: 6px;">Cerrar</button>
        </div>
    </div>
</div>

<!-- TOAST FLOTANTE INSTITUCIONAL -->
<div id="artToastNotification" class="art-toast-pill">
    <i class="ph-bold ph-check-circle" style="color: #10b981; font-size: 1.15rem;"></i>
    <span id="artToastText">¡Enlace copiado al portapapeles!</span>
</div>