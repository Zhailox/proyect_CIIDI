<?php
// modules/LineasInvestigacion/views/showcase_lineas.php
require_once CORE_PATH . 'Security/Auth.php';

// Variables inyectadas por el controlador: $lineas, $total_dimensiones, $total_proyectos, $total_invest
$totalLineas = isset($lineas) ? count($lineas) : 0;
$totalDimensiones = $total_dimensiones ?? 0;
$totalProyectos = $total_proyectos ?? 0;
$totalOfertas = $total_invest ?? 0;

// Agrupar las líneas por carrera para la vista
$lineasPorCarrera = [];
if (!empty($lineas)) {
    foreach ($lineas as $linea) {
        $carrera = !empty($linea['carrera_nombre']) ? $linea['carrera_nombre'] : 'General';
        $lineasPorCarrera[$carrera][] = $linea;
    }
}
?>

<div class="li-wrapper">
    <div class="view-container active-view" id="vista-landing">
<!-- Hero Section -->
<section class="li-hero">
<div class="li-hero-inner">

<h1 class="li-hero-title">Ecosistema de Investigación CIIDI</h1>
<p class="li-hero-subtitle">
            Catálogo unificado y articulador de líneas de investigación institucionales, dimensiones operativas y producción académica sociotecnológica.
          </p>
<!-- Buscador Universal Grande -->
<div class="li-search-box">
<div class="li-search-input-wrapper">
<i class="ph-bold ph-magnifying-glass"></i>
<input class="li-search-input" placeholder="Buscar por código, dimensión temática, tecnología (ej. GNU/Linux, REST API)..." type="text"/>
</div>
<button class="li-search-action-btn">
<span>Consultar</span>
<i class="ph-bold ph-arrow-right"></i>
</button>
</div>
<!-- 4 KPIs / Estadísticas globales -->
<div class="li-kpi-grid">
<div class="li-kpi-card">
<div class="li-kpi-icon">
<i class="ph-bold ph-compass"></i>
</div>
<div>
<div class="li-kpi-value"><?= $totalLineas ?></div>
<div class="li-kpi-label">Líneas Activas (Informática)</div>
</div>
</div>
<div class="li-kpi-card">
<div class="li-kpi-icon">
<i class="ph-bold ph-tree-structure"></i>
</div>
<div>
<div class="li-kpi-value"><?= $totalDimensiones ?></div>
<div class="li-kpi-label">Dimensiones Validadas</div>
</div>
</div>
<div class="li-kpi-card">
<div class="li-kpi-icon">
<i class="ph-bold ph-folder-open"></i>
</div>
<div>
<div class="li-kpi-value"><?= $totalProyectos ?></div>
<div class="li-kpi-label">Proyectos PST Informática</div>
</div>
</div>
<div class="li-kpi-card">
<div class="li-kpi-icon">
<i class="ph-bold ph-chalkboard-teacher"></i>
</div>
<div>
<div class="li-kpi-value"><?= $totalOfertas ?></div>
<div class="li-kpi-label">Ofertas Docentes Activas</div>
</div>
</div>
</div>
</div>
</section>
<!-- Contenido Principal -->
<main class="li-main-content">
<div style="display: grid; grid-template-columns: 1fr 340px; gap: 2rem; align-items: start;" class="li-main-layout">
<div class="li-layout-left">
<!-- Toolbar de Resultados y Controles de Vista (Requerimiento 3) -->
<div class="li-section-toolbar">
<div class="li-toolbar-left">
<h2 class="li-section-title">Líneas de Investigación</h2>
<span class="li-badge-counter"><?= $totalLineas ?> Activas</span>
</div>
<div class="li-toolbar-right">

<!-- Toggle Vista Cuadrícula / Vista Lista (Requerimiento 3) -->
<div aria-label="Modo de visualización" class="li-view-mode-selector" role="group">
<button class="li-view-btn active" id="btn-view-grid" onclick="setListingView('grid')" title="Vista Cuadrícula">
<i class="ph-bold ph-squares-four"></i>
</button>
<button class="li-view-btn" id="btn-view-list" onclick="setListingView('list')" title="Vista Lista Compacta">
<i class="ph-bold ph-list-dashes"></i>
</button>
</div>
</div>
</div>


<!-- ====================================================================
             GRUPO DE LÍNEAS ACTIVAS: PNF EN INFORMÁTICA (100% OPERATIVO)
             ==================================================================== -->

    
    <?php foreach ($lineasPorCarrera as $carrera => $grupo): ?>
    <!-- GRUPO PNF -->
    <section class="li-pnf-group">
        <div class="li-pnf-header">
            <div class="li-pnf-title-area">
                <i class="ph-bold ph-laptop li-pnf-icon"></i>
                <div class="li-pnf-name">
                    <span><?= htmlspecialchars($carrera) ?></span>
                    
                </div>
            </div>
            <span class="li-pnf-meta"><?= count($grupo) ?> Líneas de Investigación Homologadas</span>
        </div>
        
        <div class="li-items-container grid-view">
            <?php foreach ($grupo as $linea): ?>
            <!-- CARD -->
            <a href="?ruta=detalle-linea&id=<?= $linea['id'] ?>" style="text-decoration: none; color: inherit; display: block;">
                <article class="li-card">
                    <div class="li-card-content-block">
                        <div class="li-card-top">
                            <div class="li-card-icon-box">
                                <i class="ph-bold ph-code"></i>
                            </div>
                            <div class="li-card-heading">
                                <h4 class="li-card-title"><?= htmlspecialchars($linea['nombre']) ?></h4>
                            </div>
                        </div>
                        <p class="li-card-description">
                            <?= htmlspecialchars($linea['descripcion'] ?: 'Sin descripción registrada en el sistema.') ?>
                        </p>
                        
                        <div class="li-card-badges">
                            <span class="li-badge"><i class="ph-bold ph-tree-structure"></i> <?= (int)($linea['total_dimensiones'] ?? 0) ?> Dim.</span>
                            <span class="li-badge"><i class="ph-bold ph-folder-notch"></i> <?= (int)($linea['total_proyectos'] ?? 0) ?> Proy.</span>
                            <?php if (!empty($linea['total_investigaciones'])): ?>
                                <span class="li-badge has-offers"><i class="ph-bold ph-hand-pointing"></i> <?= (int)$linea['total_investigaciones'] ?> Ofertas</span>
                            <?php else: ?>
                                <span class="li-badge"><i class="ph-bold ph-hand-pointing"></i> 0 Ofertas</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="li-card-footer">
                        <div class="li-card-footer-action">
                            <span>Explorar dimensiones</span>
                            <i class="ph-bold ph-arrow-right"></i>
                        </div>
                    </div>
                </article>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endforeach; ?>
    

</div> <!-- end li-layout-left -->

<aside class="li-layout-right">
    <!-- ====================================================================
             PANEL DE FILTROS AVANZADOS EXPANDIBLE (REQUERIMIENTO 2)
             ==================================================================== -->

    
    <!-- Ofertas de Investigación -->
    <section class="li-sidebar-ofertas" style="background: #ffffff; border: 1px solid var(--color-borde); border-radius: var(--radio-md); padding: 1.5rem; box-shadow: var(--sombra-sm);">
        <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--li-text-title); margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem; border-bottom: 1px solid var(--color-borde); padding-bottom: 0.75rem;">
            <i class="ph-bold ph-hand-pointing" style="color: var(--color-secundario);"></i>
            Ofertas de Investigaciones
        </h3>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <?php if(empty($ofertas_recientes)): ?>
                <p style="color: var(--texto-silenciado); font-size: 0.85rem; margin: 0;">No hay ofertas activas en este momento.</p>
            <?php else: ?>
                <?php foreach($ofertas_recientes as $oferta): ?>
                    <a href="?ruta=postulaciones-investigacion" style="display: block; text-decoration: none; border: 1px solid var(--color-borde); border-radius: 8px; padding: 1rem; transition: all 0.2s; background: #ffffff;" onmouseover="this.style.borderColor='var(--color-principal)'; this.style.background='#ffffff'" onmouseout="this.style.borderColor='var(--color-borde)'; this.style.background='#fafafa'">
                        <span style="display: inline-block; background: #dcfce7; color: #166534; font-size: 0.65rem; font-weight: 700; padding: 2px 6px; border-radius: 4px; margin-bottom: 0.5rem; text-transform: uppercase;">Activa &bull; <?= $oferta['cupos_disponibles'] ?> Cupos</span>
                        
                        <h4 style="margin: 0 0 0.25rem 0; font-size: 0.9rem; color: var(--li-text-title); font-weight: 700; line-height: 1.3;">
                            <?= htmlspecialchars($oferta['titulo'] ?? 'Requerimiento de Sistema PST') ?>
                        </h4>
                        
                        <div style="font-size: 0.75rem; color: var(--texto-comun); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 4px;">
                            <i class="ph-bold ph-user"></i> Prof. <?= htmlspecialchars($oferta['profesor']) ?>
                        </div>
                        
                        <div style="font-size: 0.75rem; color: var(--texto-silenciado); background: #f1f5f9; padding: 4px 6px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="ph-bold ph-flask"></i> <?= htmlspecialchars($oferta['linea_nombre'] ?? 'Línea General') ?>
                        </div>
                        
                        <div style="margin-top: 0.75rem; font-size: 0.8rem; font-weight: 600; color: var(--color-principal); display: flex; align-items: center; gap: 4px;">
                            Postularse <i class="ph-bold ph-arrow-right"></i>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <a href="?ruta=postulaciones-investigacion" style="display: block; text-align: center; margin-top: 1.25rem; font-size: 0.85rem; font-weight: 600; color: var(--color-secundario); text-decoration: none;">
            Ver todas las ofertas
        </a>
    </section>
</aside>
</div> <!-- end li-main-layout -->

</main>
</div>

<script>
function setListingView(mode) {
    const containers = document.querySelectorAll('.li-items-container');
    const btnGrid = document.getElementById('btn-view-grid');
    const btnList = document.getElementById('btn-view-list');
    
    if (mode === 'list') {
        containers.forEach(c => { c.classList.remove('grid-view'); c.classList.add('list-view'); });
        if(btnGrid) btnGrid.classList.remove('active');
        if(btnList) btnList.classList.add('active');
    } else {
        containers.forEach(c => { c.classList.remove('list-view'); c.classList.add('grid-view'); });
        if(btnList) btnList.classList.remove('active');
        if(btnGrid) btnGrid.classList.add('active');
    }
}

function toggleAdvancedFilters() {
    const panel = document.getElementById('panel-filtros');
    if(panel) panel.classList.toggle('active');
}
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('.li-search-input');
    const searchBtn = document.querySelector('.li-search-action-btn');
    const cards = document.querySelectorAll('.li-card');
    function filterCards() {
        if (!searchInput) return;
        const term = searchInput.value.toLowerCase().trim();
        cards.forEach(card => {
            const title = card.querySelector('.li-card-title')?.textContent.toLowerCase() || '';
            const desc = card.querySelector('.li-card-desc')?.textContent.toLowerCase() || '';
            if (title.includes(term) || desc.includes(term)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }
    if (searchInput) searchInput.addEventListener('input', filterCards);
    if (searchBtn) searchBtn.addEventListener('click', (e) => { e.preventDefault(); filterCards(); });
});
</script>
</div></div>
