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
<div class="li-projects-header">
<h3 class="li-projects-title">
<i class="ph-bold ph-file-text"></i>
                Proyectos Desarrollados Recientes
              </h3>
<span class="li-badge-counter">4 de 48 registrados</span>
</div>
<div class="li-projects-list">
<!-- Proyecto 1 -->
<article class="li-project-item">
<div class="li-project-content">
<span class="li-project-dim-badge">Sistemas de Gestión Comunal • Trayecto III</span>
<h4 class="li-project-title">Sistema Integral Abierto para Distribución de Insumos Socioproductivos "La Candelaria"</h4>
<div class="li-project-meta-row">
<span class="li-project-meta-item">
<i class="ph-bold ph-users"></i>
                      M. Briceño, C. Mendoza, Y. Pérez
                    </span>
<span class="li-project-meta-item">
<i class="ph-bold ph-calendar"></i>
                      Julio 2024
                    </span>
<span class="li-project-meta-item">
<i class="ph-bold ph-medal"></i>
                      Mención Honorífica
                    </span>
</div>
</div>
<div class="li-project-actions">
<a class="li-btn-secondary" href="#doc-pdf" title="Descargar documento de investigación">
<i class="ph-bold ph-file-pdf"></i>
<span>Ver Documento</span>
</a>
</div>
</article>
<!-- Proyecto 2 -->
<article class="li-project-item">
<div class="li-project-content">
<span class="li-project-dim-badge">Interoperabilidad y APIs Abiertas • Trayecto IV</span>
<h4 class="li-project-title">Plataforma API RESTful para Integración de Catálogos de Bienes Comunitarios</h4>
<div class="li-project-meta-row">
<span class="li-project-meta-item">
<i class="ph-bold ph-users"></i>
                      A. Salazar, J. Rivas
                    </span>
<span class="li-project-meta-item">
<i class="ph-bold ph-calendar"></i>
                      Mayo 2024
                    </span>
<span class="li-project-meta-item">
<i class="ph-bold ph-check"></i>
                      Aprobado con Distinción
                    </span>
</div>
</div>
<div class="li-project-actions">
<a class="li-btn-secondary" href="#doc-pdf">
<i class="ph-bold ph-file-pdf"></i>
<span>Ver Documento</span>
</a>
</div>
</article>
<!-- Proyecto 3 -->
<article class="li-project-item">
<div class="li-project-content">
<span class="li-project-dim-badge">Migración a GNU/Linux • Trayecto II</span>
<h4 class="li-project-title">Protocolo de Auditoría y Transición a Estaciones de Trabajo Canaima en Centros de Salud</h4>
<div class="li-project-meta-row">
<span class="li-project-meta-item">
<i class="ph-bold ph-users"></i>
                      E. Valera, G. Márquez
                    </span>
<span class="li-project-meta-item">
<i class="ph-bold ph-calendar"></i>
                      Noviembre 2023
                    </span>
<span class="li-project-meta-item">
<i class="ph-bold ph-check"></i>
                      Aprobado
                    </span>
</div>
</div>
<div class="li-project-actions">
<a class="li-btn-secondary" href="#doc-pdf">
<i class="ph-bold ph-file-pdf"></i>
<span>Ver Documento</span>
</a>
</div>
</article>
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
