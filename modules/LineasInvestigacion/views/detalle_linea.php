<?php
// modules/LineasInvestigacion/views/detalle_linea.php
?>

<div class="li-wrapper">
<?php if ($error || !$linea): ?>
    <div class="li-empty-state" style="margin-top: 3rem;">
        <i class="ph-bold ph-warning-circle"></i>
        <p><?= htmlspecialchars($error ?? 'Línea no encontrada.') ?></p>
        <a href="index.php?ruta=lineas-investigacion" class="li-btn-primary" style="margin-top:1rem;width:auto;padding:0.6rem 1.5rem;">
            <i class="ph-bold ph-arrow-left"></i> Volver al listado
        </a>
    </div>
<?php else: ?>
<div class="view-container" id="vista-detalle">
<!-- Cabecera de la Línea y Breadcrumbs -->
<section class="li-detail-header-wrapper">
<div class="li-detail-header-inner">
<!-- Migas de pan -->
<nav class="li-breadcrumbs">
<a href="?ruta=lineas-investigacion" class="li-breadcrumb-link">
<i class="ph-bold ph-arrow-left"></i>
<span>Explorar Líneas</span>
</a>
<i class="ph-bold ph-caret-right li-breadcrumbs-sep"></i>
<span><?= htmlspecialchars($linea['carrera_nombre'] ?? 'General') ?> (Activo)</span>
<i class="ph-bold ph-caret-right li-breadcrumbs-sep"></i>
<span class="li-breadcrumbs-current">Línea #<?= $linea['id'] ?></span>
</nav>
<div class="li-line-title-row">
<div>
<div class="li-line-title-meta">
<span class="li-code-badge">LÍNEA #<?= $linea['id'] ?></span>
<span class="li-pnf-tag"><?= htmlspecialchars($linea['carrera_nombre'] ?? 'General') ?></span>
</div>
<h1 class="li-line-title"><?= htmlspecialchars($linea['nombre']) ?></h1>
</div>

</div>
<p class="li-line-description"><?= htmlspecialchars($linea['descripcion'] ?: 'Sin descripción registrada.') ?></p>

<div class="li-header-stats-strip">
<div class="li-strip-stat">
<i class="ph-bold ph-tree-structure li-strip-stat-icon"></i>
<div>
<span class="li-strip-stat-val"><?= count($dimensiones) ?></span>
<span class="li-strip-stat-desc">Dimensiones Operativas</span>
</div>
</div>
<div class="li-strip-stat">
<i class="ph-bold ph-folder-notch li-strip-stat-icon"></i>
<div>
<span class="li-strip-stat-val"><?= count($proyectos) ?></span>
<span class="li-strip-stat-desc">Proyectos Vinculados</span>
</div>
</div>
<div class="li-strip-stat">
<i class="ph-bold ph-hand-pointing li-strip-stat-icon"></i>
<div>
<span class="li-strip-stat-val"><?= count($investigaciones) ?></span>
<span class="li-strip-stat-desc">Ofertas Docentes Activas</span>
</div>
</div>
</div>

</div>
</section>
<!-- Layout de 2 Columnas (70% - 30%) -->
<div class="li-detail-layout">
<!-- Columna Izquierda (70%) - Proyectos y Dimensiones -->
<div class="li-col-left">
<!-- Panel de Dimensiones Operativas (Etiquetas Clickeables) -->

<section class="li-dimensions-panel">
    <h3 class="li-panel-title">
        <i class="ph-bold ph-tag"></i> Dimensiones Operativas de la Línea
    </h3>
    <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-top: 1rem;">
        <?php if(empty($dimensiones)): ?>
            <p style="color: #64748b; font-size: 0.9rem;">No hay dimensiones operativas registradas.</p>
        <?php else: ?>
            <?php foreach($dimensiones as $dim): ?>
                <?php
                $c_proy = count(array_filter($proyectos, function($p) use ($dim) { return $p['dimension_nombre'] === $dim['nombre']; }));
                $c_inv = count(array_filter($investigaciones, function($i) use ($dim) { return $i['dimension_nombre'] === $dim['nombre']; }));
                ?>
                <article class="li-card" style="padding: 1.25rem; background: #ffffff; border: 1px solid var(--color-borde); border-radius: var(--radio-md); box-shadow: var(--sombra-sm); display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; margin-bottom: 0.75rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(112, 144, 203, 0.15); display: flex; align-items: center; justify-content: center; color: var(--color-terciario);">
                                    <i class="ph-bold ph-tag"></i>
                                </div>
                                <h4 style="margin: 0; font-size: 1.05rem; color: var(--li-text-title);"><?= htmlspecialchars($dim['nombre']) ?></h4>
                            </div>
                        </div>
                        <p style="margin: 0; font-size: 0.9rem; color: var(--li-text-desc); line-height: 1.5; margin-bottom: 1rem;">
                            <?= htmlspecialchars($dim['descripcion'] ?: 'Sin descripción registrada.') ?>
                        </p>
                    </div>
                    
                    <div style="display: flex; gap: 1rem; border-top: 1px solid var(--color-borde); padding-top: 0.75rem;">
                        <span style="font-size: 0.8rem; font-weight: 600; color: #475569; display: flex; align-items: center; gap: 4px;">
                            <i class="ph-bold ph-folder-notch" style="color: #64748b;"></i> <?= $c_proy ?> Proyectos Vinculados
                        </span>
                        <span style="font-size: 0.8rem; font-weight: 600; color: #475569; display: flex; align-items: center; gap: 4px;">
                            <i class="ph-bold ph-hand-pointing" style="color: #64748b;"></i> <?= $c_inv ?> Ofertas Activas
                        </span>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Listado de Proyectos Desarrollados -->
<section class="li-projects-section">
    <div class="li-projects-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
        <h3 class="li-projects-title" style="font-size:1.2rem; font-weight:700; color:var(--texto-oscuro); display:flex; align-items:center; gap:8px;">
            <i class="ph-bold ph-file-text" style="color:var(--color-secundario);"></i>
            Proyectos Desarrollados
        </h3>
        <?php if (!empty($proyectos)): ?>
            <span class="li-badge-counter" style="background:#f1f5f9; color:#475569; padding:4px 10px; border-radius:20px; font-size:0.8rem; font-weight:700;">
                Mostrando <?= count($proyectos) ?> de <?= $pagination['total_items'] ?>
            </span>
        <?php endif; ?>
    </div>

    <div class="li-projects-list" style="display:flex; flex-direction:column; gap:1rem;">
        <?php if (empty($proyectos)): ?>
            <div style="background:#f8fafc; border:1px dashed #cbd5e1; border-radius:12px; padding:3rem 1rem; text-align:center;">
                <div style="width:60px; height:60px; background:#e2e8f0; color:#64748b; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.8rem; margin:0 auto 1rem;">
                    <i class="ph-bold ph-folder-dashed"></i>
                </div>
                <h4 style="font-size:1.1rem; color:#334155; margin-bottom:0.5rem; font-weight:700;">Aún no hay proyectos clasificados en esta línea</h4>
                <p style="color:#64748b; max-width:400px; margin:0 auto 1.5rem; font-size:0.9rem; line-height:1.5;">
                    Esta línea de investigación está esperando por nuevos desarrollos. Si estás cursando tu PST, ¡tu proyecto podría ser el primero!
                </p>
                <a href="?ruta=mis-proyectos" style="background:var(--color-primario); color:#fff; text-decoration:none; padding:10px 20px; border-radius:8px; font-weight:700; font-size:0.9rem; display:inline-flex; align-items:center; gap:6px; transition:0.2s; box-shadow:0 4px 10px rgba(0,0,0,0.15);">
                    <i class="ph-bold ph-plus-circle"></i> Registra tu Proyecto PST aquí
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($proyectos as $p): ?>
                <article class="li-project-item" style="background:#fff; border:1px solid var(--color-borde); border-radius:12px; padding:1.25rem; display:flex; justify-content:space-between; align-items:flex-start; gap:1.5rem; transition:0.2s;">
                    <div class="li-project-content" style="flex:1;">
                        <span class="li-project-dim-badge" style="display:inline-block; font-size:0.75rem; font-weight:700; color:#4338ca; background:#e0e7ff; padding:4px 8px; border-radius:6px; margin-bottom:8px; letter-spacing:0.5px; text-transform:uppercase;">
                            <?= htmlspecialchars($p['dimension_nombre'] ?? 'Sin Dimensión') ?>
                        </span>
                        <h4 class="li-project-title" style="font-size:1.1rem; font-weight:700; color:var(--texto-oscuro); margin-bottom:10px; line-height:1.4;">
                            <?= htmlspecialchars($p['titulo']) ?>
                        </h4>
                        <div class="li-project-meta-row" style="display:flex; flex-wrap:wrap; gap:1rem; font-size:0.85rem; color:#64748b; font-weight:500;">
                            <span class="li-project-meta-item" style="display:flex; align-items:center; gap:4px;">
                                <i class="ph-bold ph-users"></i> <?= htmlspecialchars($p['autores'] ?? 'Autores No Registrados') ?>
                            </span>
                            <span class="li-project-meta-item" style="display:flex; align-items:center; gap:4px;">
                                <i class="ph-bold ph-calendar"></i> <?= htmlspecialchars($p['anio_publicacion'] ?? '') ?>
                            </span>
                            <?php if (!empty($p['nivel_academico'])): ?>
                            <span class="li-project-meta-item" style="display:flex; align-items:center; gap:4px;">
                                <i class="ph-bold ph-graduation-cap"></i> <?= htmlspecialchars($p['nivel_academico']) ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!empty($p['archivo_pdf'])): ?>
                    <div class="li-project-actions" style="flex-shrink:0;">
                        <a class="li-btn-secondary" href="?ruta=ver-pdf-pst&id=<?= $p['id'] ?>" target="_blank" title="Descargar documento de investigación" style="background:#f8fafc; color:#334155; border:1px solid #cbd5e1; padding:8px 12px; border-radius:8px; font-weight:600; font-size:0.85rem; display:inline-flex; align-items:center; gap:6px; text-decoration:none; transition:0.2s;">
                            <i class="ph-bold ph-file-pdf" style="color:#ef4444;"></i>
                            <span>Ver PDF</span>
                        </a>
                    </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
            
            <!-- Paginación -->
            <?php if ($pagination['total_pages'] > 1): ?>
            <div style="display:flex; justify-content:center; align-items:center; gap:10px; margin-top:1.5rem; border-top:1px solid var(--color-borde); padding-top:1.5rem;">
                <?php if ($pagination['current_page'] > 1): ?>
                    <a href="?ruta=detalle-linea&id=<?= $linea['id'] ?>&p=<?= $pagination['current_page'] - 1 ?>" style="padding:8px 16px; border:1px solid var(--color-borde); border-radius:8px; color:var(--texto-oscuro); text-decoration:none; font-weight:600; font-size:0.9rem; background:#fff; transition:0.2s;"><i class="ph-bold ph-caret-left"></i> Anterior</a>
                <?php endif; ?>
                
                <span style="font-size:0.9rem; font-weight:600; color:#64748b;">
                    Página <?= $pagination['current_page'] ?> de <?= $pagination['total_pages'] ?>
                </span>
                
                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                    <a href="?ruta=detalle-linea&id=<?= $linea['id'] ?>&p=<?= $pagination['current_page'] + 1 ?>" style="padding:8px 16px; border:1px solid var(--color-borde); border-radius:8px; color:var(--texto-oscuro); text-decoration:none; font-weight:600; font-size:0.9rem; background:#fff; transition:0.2s;">Siguiente <i class="ph-bold ph-caret-right"></i></a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
</div>
<!-- Columna Derecha (30%) - Investigaciones Ofertadas -->
<aside class="li-col-right">
<div class="li-offers-panel">
<div class="li-offers-header">
<h3 class="li-offers-title">
<i class="ph-bold ph-chalkboard-teacher" style="color: var(--color-secundario);"></i>
<span>Investigaciones Ofertadas</span>
</h3>
<span class="li-offers-badge"><?= count($investigaciones) ?> Activas</span>
</div>
<div class="li-offers-list">
            <?php if (count($investigaciones) === 0): ?>
                <div style="text-align: center; color: var(--texto-silenciado); padding: 2rem 0;">
                    <i class="ph-bold ph-folder-open" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                    <p style="font-size: 0.85rem;">No hay ofertas activas</p>
                </div>
            <?php else: ?>
                <?php foreach ($investigaciones as $inv): ?>
                <!-- Oferta -->
                <div class="li-offer-card">
                    <div class="li-offer-top">
                        <?php if ($inv['estado'] === 'Abierta'): ?>
                            <span class="li-offer-status open">Abierta</span>
                        <?php else: ?>
                            <span class="li-offer-status closed">Cerrada</span>
                        <?php endif; ?>
                        <span class="li-offer-slots"><i class="ph-bold ph-user-check"></i> <?= (int)$inv['cupos_disponibles'] ?> cupos disp.</span>
                    </div>
                    <h4 class="li-offer-title"><?= htmlspecialchars($inv['titulo']) ?></h4>
                    <div class="li-offer-prof">
                        <i class="ph-bold ph-graduation-cap"></i>
                        <span>Prof. <?= htmlspecialchars($inv['nombre_profesor'] ?? 'No asignado') ?></span>
                    </div>
                    <?php if ($inv['estado'] === 'Abierta'): ?>
                        <a href="?ruta=postulaciones-investigacion" class="li-btn-apply" style="text-decoration:none; display:flex;">
                            <i class="ph-bold ph-hand-pointing"></i>
                            <span>Postularse / Contactar</span>
                        </a>
                    <?php else: ?>
                        <button class="li-btn-apply" disabled style="opacity:0.5; cursor:not-allowed;">
                            <i class="ph-bold ph-lock-key"></i>
                            <span>Convocatoria Cerrada</span>
                        </button>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
</aside>
</div>
</div>
</div>
<!-- ========================================================================
       SCRIPTS VANILLA JS: NAVEGACIÓN, FILTROS Y VISTAS GRID / LIST
       ======================================================================== -->

<?php endif; ?>
</div>
