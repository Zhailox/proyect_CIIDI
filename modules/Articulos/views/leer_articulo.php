<?php require_once __DIR__ . '/../services/ConfigService.php'; ?>
<div class="art-detail-page">
    <?php if (!empty($articulo)): ?>
        <?php
            $imgPortada = $articulo['imagen_portada'] ?? 'default_article.jpg';
            $rutaImg = (strpos($imgPortada, 'http') === 0) ? $imgPortada : '../public/uploads/articulos/' . $imgPortada;
        ?>

        <article class="art-detail-card">
            <header class="art-detail-hero" style="background-image: url('<?= htmlspecialchars($rutaImg) ?>');">
                <div class="art-detail-hero__overlay" style="width: 100%;">
                    <div style="display:flex; justify-content: space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem;">
                        <div>
                            <div class="art-detail-hero__meta">
                                <span class="badge"><?= htmlspecialchars($articulo['categoria'] ?? 'Artículo') ?></span>

                                <?php if (ConfigService::get('recursos.mostrar_volumen', true) && (!empty($articulo['volumen']) || !empty($articulo['numero']))): ?>
                                    <span class="art-detail-hero__volume">
                                        Vol. <?= htmlspecialchars($articulo['volumen'] ?? 'N/A') ?>
                                        <?= !empty($articulo['numero']) ? ' - Núm. ' . htmlspecialchars($articulo['numero']) : '' ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <h1><?= htmlspecialchars($articulo['titulo'] ?? 'Artículo sin título') ?></h1>
                            <p class="art-detail-hero__authors">
                                Por: <?= htmlspecialchars($articulo['autores_text'] ?? 'Autor no registrado') ?><br>
                                Publicado: <?= htmlspecialchars($articulo['anio_publicacion'] ?? 'Sin año') ?>
                                <?php if (ConfigService::get('recursos.mostrar_editorial', true) && !empty($articulo['editorial'])): ?>
                                    | Ed: <?= htmlspecialchars($articulo['editorial']) ?>
                                <?php endif; ?>
                                <?php if (ConfigService::get('recursos.mostrar_issn', true) && !empty($articulo['issn'])): ?>
                                    | ISSN: <?= htmlspecialchars($articulo['issn']) ?>
                                <?php endif; ?>
                            </p>
                        </div>
                        
                        <!-- BOTONES DE CABECERA -->
                        <div style="display: flex; gap: 0.5rem; background: rgba(0,0,0,0.3); padding: 0.5rem; border-radius: 8px;">
                            <button type="button" class="btn" style="background:white; color:#0f172a;" onclick="abrirModalCita(<?= htmlspecialchars(json_encode($articulo['titulo'])) ?>, <?= htmlspecialchars(json_encode($articulo['autores_text'])) ?>, <?= $articulo['anio_publicacion'] ?>, <?= htmlspecialchars(json_encode($articulo['editorial'] ?? 'N/A')) ?>, <?= htmlspecialchars(json_encode($articulo['volumen'] ?? '')) ?>, <?= htmlspecialchars(json_encode($articulo['numero'] ?? '')) ?>, <?= htmlspecialchars(json_encode($articulo['issn'] ?? '')) ?>)">
                                <i class="ph ph-quotes"></i> Citar
                            </button>
                            <button type="button" class="btn" style="background:white; color:#0f172a;" onclick="compartirEnlace('<?= htmlspecialchars($articulo['archivo_pdf'] ?? '') ?>', this)">
                                <i class="ph ph-share-network"></i> Copiar Link
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <div class="art-detail-body">
                <?php if (!empty($articulo['archivo_pdf'])): ?>
                    <a href="<?= htmlspecialchars($articulo['archivo_pdf']) ?>" target="_blank" rel="noopener" class="btn art-detail-link">Ir al artículo completo</a>
                <?php endif; ?>

                <div class="art-detail-content">
                    <h3>Resumen</h3>
                    <p><?= nl2br(htmlspecialchars($articulo['resumen'] ?? 'Sin resumen disponible.')) ?></p>
                </div>
                
                <!-- SECCIÓN DE ARTÍCULOS RELACIONADOS -->
                <?php if (!empty($similares)): ?>
                <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid rgba(0,0,0,0.1);">
                    <h3 style="color: var(--texto-titulos); margin-bottom: 1.5rem;"><i class="ph-bold ph-books"></i> Artículos Recomendados</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                        <?php foreach($similares as $sim): 
                            $img = $sim['imagen_portada'] ?? 'default_article.jpg';
                            $ruta = (strpos($img, 'http') === 0) ? htmlspecialchars($img) : '../public/uploads/articulos/' . htmlspecialchars($img);
                        ?>
                            <div style="border: 1px solid rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; display: flex; flex-direction: column;">
                                <div style="height: 120px; background: url('<?= $ruta ?>') center/cover;"></div>
                                <div style="padding: 1rem; flex-grow: 1; display: flex; flex-direction: column;">
                                    <span style="font-size: 0.75rem; font-weight: bold; color: var(--color-secundario);"><?= htmlspecialchars($sim['categoria']) ?> • <?= $sim['anio_publicacion'] ?></span>
                                    <h4 style="margin: 0.5rem 0; font-size: 1rem;"><a href="leer-articulo?id=<?= $sim['id'] ?>" style="color: inherit; text-decoration: none;"><?= htmlspecialchars($sim['titulo']) ?></a></h4>
                                    <a href="leer-articulo?id=<?= $sim['id'] ?>" style="margin-top: auto; font-size: 0.85rem; font-weight: bold; color: var(--color-terciario); text-decoration: none;">Leer más ➔</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </article>

    <?php else: ?>
        <div class="art-detail-empty"><p>No se encontró el artículo solicitado.</p></div>
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