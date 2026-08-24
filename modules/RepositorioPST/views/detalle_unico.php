<style>
.pst-detail-view {
    background-color: var(--bg-card, #ffffff);
    border: 1px solid rgba(169, 168, 166, 0.2);
    border-radius: var(--radius-md, 6px);
    padding: 1.25rem;
    width: 100%;
    max-width: 100% !important;
    animation: fadeIn 0.4s ease-out;
}
.pst-detail-header {
    border-bottom: 2px solid var(--color-terciario, #007bff);
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
}
.pst-detail-header h1 {
    font-size: 1.5rem;
    color: var(--texto-titulos);
    font-weight: 800;
    line-height: 1.3;
    margin: 0 0 0.4rem 0;
}
.pst-detail-meta-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 0.75rem;
    margin-bottom: 1.25rem;
}
.pst-meta-item {
    background-color: #fcfcfc;
    border: 1px solid rgba(169, 168, 166, 0.15);
    border-radius: 4px;
    padding: 0.6rem 0.75rem;
}
.pst-meta-item strong {
    display: block;
    font-size: 0.7rem;
    text-transform: uppercase;
    color: var(--texto-silenciado);
    margin-bottom: 0.2rem;
    letter-spacing: 0.5px;
}
.pst-meta-item span {
    font-size: 0.85rem;
    color: var(--texto-normal);
    font-weight: 600;
    line-height: 1.3;
}
.pst-detail-abstract {
    margin-bottom: 1rem;
}
.pst-detail-abstract h3 {
    font-size: 1rem;
    color: var(--texto-titulos);
    margin-bottom: 0.4rem;
    font-weight: 700;
}
.pst-detail-abstract p {
    font-size: 0.9rem;
    color: var(--texto-normal);
    line-height: 1.5;
    margin: 0;
    text-align: justify;
}
.pst-detail-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid rgba(169, 168, 166, 0.15);
    padding-top: 0.75rem;
    margin-top: 1rem;
}
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.45rem 1rem;
    background-color: #e2e8f0;
    color: #333;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 700;
    border-radius: 4px;
    transition: background-color 0.2s;
    border: none;
    cursor: pointer;
}
.btn-back:hover {
    background-color: #cbd5e0;
}
.btn-download-pdf {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.45rem 1rem;
    background-color: var(--color-terciario, #007bff);
    color: white;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 700;
    border-radius: 4px;
    transition: background-color 0.2s;
}
.btn-download-pdf:hover {
    background-color: var(--color-secundario, #002244);
}
.pst-badge-soft {
    border-radius: 4px;
    padding: 0.2rem 0.4rem;
    font-size: 0.7rem;
    font-weight: 700;
    display: inline-block;
}
</style>

<div class="main-content">
    <?php if (!$documento): ?>
        <div class="no-results-card" style="max-width: 500px; margin: 2rem auto; text-align: center; border: 1px solid rgba(169,168,166,0.2); padding: 2rem; border-radius: 6px; background-color: var(--bg-card);">
            <i class="ph ph-warning-circle" style="font-size: 2.5rem; color: #e53e3e; margin-bottom: 0.75rem; display: block;"></i>
            <h3 style="margin-bottom: 0.5rem; font-size: 1.1rem; font-weight: 700;">Proyecto no encontrado</h3>
            <p style="font-size: 0.85rem; color: var(--texto-silenciado); margin-bottom: 1.25rem;">El Proyecto Socio-Tecnológico solicitado no existe en el sistema o fue removido.</p>
            <a href="?ruta=repositorio" class="btn-back">Volver al Repositorio</a>
        </div>
    <?php else: ?>
        <div class="pst-detail-view">
            
            <div class="pst-detail-header">
                <div style="display: flex; gap: 0.4rem; margin-bottom: 0.4rem;">
                    <span class="pst-badge-soft" style="background-color: rgba(112, 144, 203, 0.15); color: var(--color-secundario);"><?= htmlspecialchars($documento['nivel_academico'] ?? 'Pregrado') ?></span>
                    <span class="pst-badge-soft" style="background-color: rgba(0, 123, 255, 0.1); color: var(--color-terciario);">AÑO <?= $documento['anio_publicacion'] ?></span>
                </div>
                <h1><?= htmlspecialchars($documento['titulo']) ?></h1>
            </div>

            <div class="pst-detail-meta-grid">
                
                <div class="pst-meta-item">
                    <strong>Autores (Estudiantes)</strong>
                    <span><?= htmlspecialchars($documento['autores_nombres'] ?? 'No registrados') ?></span>
                </div>

                <div class="pst-meta-item">
                    <strong>Tutor Asesor</strong>
                    <span><?= htmlspecialchars($documento['tutores_nombres'] ?? 'No registrado') ?></span>
                </div>

                <div class="pst-meta-item">
                    <strong>Comunidad Objeto</strong>
                    <span><?= htmlspecialchars($documento['comunidad_beneficiada'] ?? 'No registrada') ?></span>
                </div>

                <div class="pst-meta-item">
                    <strong>PNF / Programa Académico</strong>
                    <span><?= htmlspecialchars($documento['carrera_nombre'] ?? 'Informática') ?></span>
                </div>

                <div class="pst-meta-item">
                    <strong>Línea de Investigación</strong>
                    <span><?= htmlspecialchars($documento['linea_nombre'] ?? 'General') ?></span>
                </div>

                <div class="pst-meta-item">
                    <strong>Dimensión Operativa</strong>
                    <span><?= htmlspecialchars($documento['dimension_nombre'] ?? 'Sin dimensión asociada') ?></span>
                </div>

            </div>

            <div class="pst-detail-abstract">
                <h3>Resumen Epistémico / Abstract</h3>
                <p>
                    <?= nl2br(htmlspecialchars($documento['resumen'] ?? 'No se ha cargado un resumen o matriz epistémica para esta investigación en el sistema.')) ?>
                </p>
            </div>

            <?php if (!empty($documento['palabras_clave'])): ?>
                <div style="margin-bottom: 1rem; font-size: 0.85rem;">
                    <strong>Palabras Clave:</strong>
                    <span style="font-style: italic; color: var(--texto-silenciado);"><?= htmlspecialchars($documento['palabras_clave']) ?></span>
                </div>
            <?php endif; ?>

            <div class="pst-detail-actions">
                <a href="?ruta=repositorio" class="btn-back">
                    <i class="ph ph-arrow-left"></i> Volver al Catálogo
                </a>
                <?php if (!empty($documento['archivo_pdf']) && $documento['archivo_pdf'] !== '#'): ?>
                    <a href="<?= htmlspecialchars($documento['archivo_pdf']) ?>" target="_blank" class="btn-download-pdf">
                        <i class="ph ph-file-pdf"></i> Descargar Documento (PDF)
                    </a>
                <?php else: ?>
                    <span style="color: var(--texto-silenciado); font-size: 0.8rem; font-weight: 600;">El archivo digital no ha sido cargado aún.</span>
                <?php endif; ?>
            </div>

        </div>
    <?php endif; ?>
</div>
