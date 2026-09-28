<?php
// modules/LineasInvestigacion/views/detalle_linea.php
?>

<div class="li-wrapper">
<?php if ($error || !$linea): ?>
    <div class="li-empty-state" style="margin-top:3rem;">
        <i class="ph-bold ph-warning-circle"></i>
        <p><?= htmlspecialchars($error ?? 'Línea no encontrada.') ?></p>
        <a href="index.php?ruta=lineas-investigacion" class="cosmos-search-btn" style="margin-top:1rem;text-decoration:none;width:auto;display:inline-flex;">
            <i class="ph-bold ph-arrow-left"></i> Volver al listado
        </a>
    </div>
<?php else: ?>
<div class="view-container" id="vista-detalle">

<!-- ══════════════════════════════════════════════
     COSMOS DETAIL HERO
══════════════════════════════════════════════ -->
<section class="cosmos-detail-hero">
    <!-- Fondo animado (mismo estilo que landing) -->
    <div class="cosmos-starfield" aria-hidden="true">
        <div class="cosmos-moving-grid"></div>
        <div class="cosmos-stars cosmos-stars-sm"></div>
        <div class="cosmos-stars cosmos-stars-md"></div>
        
        <div class="cosmos-nebula cosmos-nebula-1"></div>
        <div class="cosmos-nebula cosmos-nebula-2"></div>
    </div>

    <div class="cosmos-detail-hero-inner">
        <!-- Breadcrumb -->
        <nav class="cosmos-breadcrumb">
            <a href="?ruta=lineas-investigacion" class="cosmos-breadcrumb-back">
                <i class="ph-bold ph-arrow-left"></i>
                <span>Explorar Líneas</span>
            </a>
            <i class="ph-bold ph-caret-right" style="opacity:0.4;font-size:0.8rem;"></i>
            <span style="opacity:0.6;font-size:0.82rem;"><?= htmlspecialchars($linea['carrera_nombre'] ?? 'General') ?></span>
            <i class="ph-bold ph-caret-right" style="opacity:0.4;font-size:0.8rem;"></i>
            <span style="font-size:0.82rem;font-weight:700;color:rgba(255,255,255,0.9);">Línea #<?= $linea['id'] ?></span>
        </nav>

        <!-- Badge + Título -->
        <div class="cosmos-detail-title-area">
            <div class="cosmos-detail-badges">
                <span class="cosmos-hero-badge" style="font-size:0.68rem;">
                    <i class="ph-bold ph-code"></i>
                    LÍNEA #<?= $linea['id'] ?>
                </span>
                <span class="cosmos-hero-badge" style="font-size:0.68rem;background:rgba(5,150,105,0.2);border-color:rgba(5,150,105,0.4);">
                    <i class="ph-bold ph-check-circle"></i>
                    <?= htmlspecialchars($linea['carrera_nombre'] ?? 'General') ?>
                </span>
            </div>
            <h1 class="cosmos-detail-title"><?= htmlspecialchars($linea['nombre']) ?></h1>
            <p class="cosmos-detail-desc">
                <?= htmlspecialchars($linea['descripcion'] ?: 'Sin descripción registrada.') ?>
            </p>
        </div>

        <!-- Strip de estadísticas -->
        <div class="cosmos-detail-stats-strip">
            <div class="cosmos-detail-stat">
                <i class="ph-bold ph-tree-structure"></i>
                <div>
                    <span class="cosmos-detail-stat-num"><?= count($dimensiones) ?></span>
                    <span class="cosmos-detail-stat-label">Dimensiones Operativas</span>
                </div>
            </div>
            <div class="cosmos-detail-stat-sep"></div>
            <div class="cosmos-detail-stat">
                <i class="ph-bold ph-folder-notch"></i>
                <div>
                    <span class="cosmos-detail-stat-num"><?= count($proyectos) ?></span>
                    <span class="cosmos-detail-stat-label">Proyectos Vinculados</span>
                </div>
            </div>
            <div class="cosmos-detail-stat-sep"></div>
            <div class="cosmos-detail-stat">
                <i class="ph-bold ph-hand-pointing"></i>
                <div>
                    <span class="cosmos-detail-stat-num"><?= count($investigaciones) ?></span>
                    <span class="cosmos-detail-stat-label">Ofertas Activas</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════════
     LAYOUT 2 COLUMNAS
══════════════════════════════════════════════ -->
<div class="cosmos-detail-layout">

    <!-- Columna izquierda -->
    <div class="li-col-left">

        <!-- DIMENSIONES OPERATIVAS -->
        <section class="cosmos-detail-section">
            <h3 class="cosmos-detail-section-title">
                <i class="ph-bold ph-tag"></i>
                Dimensiones Operativas de la Línea
            </h3>

            <div style="display:flex;flex-direction:column;gap:0.85rem;margin-top:1rem;">
                <?php if (empty($dimensiones)): ?>
                    <div class="cosmos-empty-state">
                        <i class="ph-bold ph-folder-dashed"></i>
                        <p>No hay dimensiones operativas registradas para esta línea.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($dimensiones as $dim):
                        $c_proy = count(array_filter($proyectos, fn($p) => $p['dimension_nombre'] === $dim['nombre']));
                        $c_inv  = count(array_filter($investigaciones, fn($i) => $i['dimension_nombre'] === $dim['nombre']));
                    ?>
                    <article class="cosmos-dim-card">
                        <div class="cosmos-dim-card-top">
                            <div class="cosmos-dim-icon">
                                <i class="ph-bold ph-tag"></i>
                            </div>
                            <h4 class="cosmos-dim-name"><?= htmlspecialchars($dim['nombre']) ?></h4>
                        </div>
                        <p class="cosmos-dim-desc">
                            <?= htmlspecialchars($dim['descripcion'] ?: 'Sin descripción registrada.') ?>
                        </p>
                        <div class="cosmos-dim-footer">
                            <span class="cosmos-stat-pill">
                                <i class="ph-bold ph-folder-notch"></i>
                                <?= $c_proy ?> Proyectos
                            </span>
                            <span class="cosmos-stat-pill <?= $c_inv > 0 ? 'cosmos-stat-active' : '' ?>">
                                <i class="ph-bold ph-hand-pointing"></i>
                                <?= $c_inv ?> Ofertas Activas
                            </span>
                        </div>
                    </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- PROYECTOS DESARROLLADOS -->
        <section class="cosmos-detail-section" style="margin-top:1.5rem;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
                <h3 class="cosmos-detail-section-title" style="margin-bottom:0;border-bottom:none;padding-bottom:0;">
                    <i class="ph-bold ph-file-text"></i>
                    Proyectos Desarrollados
                </h3>
                <?php if (!empty($proyectos)): ?>
                <span class="cosmos-stat-pill" style="background:#f1f5f9;border-color:#e2e8f0;color:#475569;">
                    <?= count($proyectos) ?> de <?= $pagination['total_items'] ?>
                </span>
                <?php endif; ?>
            </div>

            <div style="display:flex;flex-direction:column;gap:0.85rem;">
                <?php if (empty($proyectos)): ?>
                <div class="cosmos-empty-state">
                    <i class="ph-bold ph-folder-dashed"></i>
                    <h4 style="margin:0 0 0.3rem;font-size:1rem;color:#334155;">Aún no hay proyectos clasificados en esta línea</h4>
                    <p>Esta línea de investigación está esperando por nuevos desarrollos.</p>
                </div>
                <?php else: ?>
                    <?php foreach ($proyectos as $p): ?>
                    <article class="cosmos-proj-card">
                        <div class="cosmos-proj-dim-badge">
                            <?= htmlspecialchars($p['dimension_nombre'] ?? 'Sin Dimensión') ?>
                        </div>
                        <h4 class="cosmos-proj-title"><?= htmlspecialchars($p['titulo']) ?></h4>
                        <div class="cosmos-proj-meta">
                            <span><i class="ph-bold ph-users"></i> <?= htmlspecialchars($p['autores'] ?? 'Sin autores') ?></span>
                            <span><i class="ph-bold ph-calendar"></i> <?= htmlspecialchars($p['anio_publicacion'] ?? '') ?></span>
                            <?php if (!empty($p['nivel_academico'])): ?>
                            <span><i class="ph-bold ph-graduation-cap"></i> <?= htmlspecialchars($p['nivel_academico']) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($p['archivo_pdf'])): ?>
                        <a class="cosmos-pdf-btn" href="?ruta=ver-pdf-pst&id=<?= $p['id'] ?>" target="_blank">
                            <i class="ph-bold ph-file-pdf"></i> Ver PDF
                        </a>
                        <?php endif; ?>
                    </article>
                    <?php endforeach; ?>

                    <!-- Paginación -->
                    <?php if ($pagination['total_pages'] > 1): ?>
                    <div class="cosmos-pagination">
                        <?php if ($pagination['current_page'] > 1): ?>
                        <a href="?ruta=detalle-linea&id=<?= $linea['id'] ?>&p=<?= $pagination['current_page'] - 1 ?>" class="cosmos-page-btn">
                            <i class="ph-bold ph-caret-left"></i> Anterior
                        </a>
                        <?php endif; ?>
                        <span class="cosmos-page-info">Página <?= $pagination['current_page'] ?> de <?= $pagination['total_pages'] ?></span>
                        <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                        <a href="?ruta=detalle-linea&id=<?= $linea['id'] ?>&p=<?= $pagination['current_page'] + 1 ?>" class="cosmos-page-btn">
                            Siguiente <i class="ph-bold ph-caret-right"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </section>

    </div>

    <!-- Columna derecha: Investigaciones -->
    <aside class="li-col-right">
        <div class="cosmos-sidebar-card">
            <div class="cosmos-sidebar-header">
                <i class="ph-bold ph-chalkboard-teacher"></i>
                <h3>Investigaciones Ofertadas</h3>
                <span style="margin-left:auto;background:rgba(255,255,255,0.18);border-radius:50px;padding:2px 8px;font-size:0.7rem;font-weight:700;"><?= count($investigaciones) ?> Activas</span>
            </div>
            <div class="cosmos-sidebar-body">
                <?php if (count($investigaciones) === 0): ?>
                <div class="cosmos-sidebar-empty">
                    <i class="ph-bold ph-folder-open"></i>
                    <p>No hay ofertas activas para esta línea.</p>
                </div>
                <?php else: ?>
                    <?php foreach ($investigaciones as $inv): ?>
                    <div class="cosmos-oferta-item" style="cursor:default;">
                        <div class="cosmos-oferta-accent-bar" style="background:<?= $inv['estado'] === 'Abierta' ? 'linear-gradient(180deg,#059669,#34d399)' : 'linear-gradient(180deg,#94a3b8,#cbd5e1)' ?>;"></div>
                        <div class="cosmos-oferta-content">
                            <span class="cosmos-oferta-badge" style="<?= $inv['estado'] === 'Abierta' ? '' : 'background:#f1f5f9;color:#64748b;' ?>">
                                <?= $inv['estado'] === 'Abierta' ? 'Abierta · '.(int)$inv['cupos_disponibles'].' cupos' : 'Cerrada' ?>
                            </span>
                            <h4 class="cosmos-oferta-title"><?= htmlspecialchars($inv['titulo']) ?></h4>
                            <div class="cosmos-oferta-meta">
                                <i class="ph-bold ph-graduation-cap"></i>
                                Prof. <?= htmlspecialchars($inv['nombre_profesor'] ?? 'No asignado') ?>
                            </div>
                            <?php if ($inv['estado'] === 'Abierta'): ?>
                            <a href="?ruta=investigaciones" class="cosmos-oferta-action" style="text-decoration:none;">
                                <i class="ph-bold ph-hand-pointing"></i> Postularse / Contactar
                            </a>
                            <?php else: ?>
                            <span class="cosmos-oferta-action" style="color:#94a3b8;">
                                <i class="ph-bold ph-lock-key"></i> Convocatoria Cerrada
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </aside>

</div>
</div>
<?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Animación escalonada de las cards de dimensiones
    document.querySelectorAll('.cosmos-dim-card, .cosmos-proj-card').forEach((card, i) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'opacity 0.45s ease, transform 0.45s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 80 + i * 70);
    });
});
</script>
