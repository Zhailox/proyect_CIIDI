<?php
// modules/LineasInvestigacion/views/showcase_lineas.php
require_once CORE_PATH . 'Security/Auth.php';

$totalLineas = 0;
$totalDimensiones = 0;
$totalProyectos = 0;
$totalOfertas = 0;

foreach ($lineasPorCarrera as $grupo) {
    $totalLineas += count($grupo);
    foreach ($grupo as $l) {
        $totalDimensiones += $l['total_dimensiones'] ?? 0;
        $totalProyectos += $l['total_proyectos'] ?? 0;
        $totalOfertas += $l['total_investigaciones'] ?? 0;
    }
}
?>

<div class="li-wrapper">
    <div class="view-container active-view" id="vista-landing">
<!-- Hero Section -->
<section class="li-hero">
<div class="li-hero-inner">
<div class="li-hero-tag-row">
<div class="li-hero-tag">
<i class="ph-bold ph-flask"></i>
              Ecosistema Científico Institucional
            </div>
<div class="li-hero-status-pill">
<span>Fase 1: PNF Informática 100% Operativo</span>
</div>
</div>
<h1 class="li-hero-title">Ecosistema de Investigación CIIDI</h1>
<p class="li-hero-subtitle">
            Catálogo unificado y articulador de líneas de investigación institucionales, dimensiones operativas y producción académica sociotecnológica.
          </p>
<!-- Buscador Universal Grande -->
<div class="li-search-box">
<div class="li-search-scope">
<i class="ph-bold ph-funnel"></i>
<span>PNF Informática (Activo)</span>
</div>
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
<div class="li-kpi-value">118</div>
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
<!-- Banner de Estado de PNF (Requerimiento 1) -->
<div class="li-stage-banner">
<div class="li-stage-info">
<div class="li-stage-icon">
<i class="ph-bold ph-check-circle"></i>
</div>
<div class="li-stage-text">
<h4>
<span>Programa Activo: PNF en Informática</span>
<span class="li-stage-badge-active">100% Operativo</span>
</h4>
<p>Módulo de registro, vinculación de proyectos PST y banco de ofertas habilitado en producción para Informática. Los próximos PNF se integrarán progresivamente.</p>
</div>
</div>
<div>
<span class="li-stage-badge-upcoming">
<i class="ph-bold ph-clock"></i> Fase 2 Planificada: 3 PNF
            </span>
</div>
</div>
<!-- Toolbar de Resultados y Controles de Vista (Requerimiento 3) -->
<div class="li-section-toolbar">
<div class="li-toolbar-left">
<h2 class="li-section-title">Líneas de Investigación</h2>
<span class="li-badge-counter">4 Activas en Informática</span>
</div>
<div class="li-toolbar-right">
<!-- Botón para alternar Panel de Filtros Avanzados -->
<button class="li-btn-filter-toggle" id="btn-toggle-filters" onclick="toggleAdvancedFilters()">
<i class="ph-bold ph-sliders-horizontal"></i>
<span>Filtros Avanzados</span>
<span class="li-filter-count-badge">3</span>
</button>
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
<!-- Píldoras de Filtros Facetados de Carreras con Estado Activo / Próximamente (Requerimiento 1) -->
<div class="li-facet-filters">
<button class="li-facet-chip active">
<i class="ph-bold ph-laptop"></i>
<span>PNF en Informática</span>
<span class="li-facet-status-tag active-tag">100% Operativo</span>
</button>
<button class="li-facet-chip disabled" title="Próximamente disponible en Fase 2">
<i class="ph-bold ph-briefcase"></i>
<span>PNF en Administración</span>
<span class="li-facet-status-tag soon-tag">Próximamente • Fase 2</span>
</button>
<button class="li-facet-chip disabled" title="Próximamente disponible en Fase 2">
<i class="ph-bold ph-plant"></i>
<span>PNF en Agroalimentación</span>
<span class="li-facet-status-tag soon-tag">Próximamente • Fase 2</span>
</button>
<button class="li-facet-chip disabled" title="Próximamente disponible en Fase 2">
<i class="ph-bold ph-lightning"></i>
<span>PNF en Electricidad</span>
<span class="li-facet-status-tag soon-tag">Próximamente • Fase 2</span>
</button>
</div>
<!-- ====================================================================
             PANEL DE FILTROS AVANZADOS EXPANDIBLE (REQUERIMIENTO 2)
             ==================================================================== -->
<section class="li-advanced-filter-panel" id="panel-filtros-avanzados">
<div class="li-af-header">
<div class="li-af-title">
<i class="ph-bold ph-funnel" style="color: var(--color-secundario);"></i>
<span>Criterios Avanzados de Búsqueda Académica</span>
</div>
<span style="font-size: 0.75rem; color: var(--texto-silenciado);">Repositorio Curricular PST</span>
</div>
<div class="li-af-grid">
<!-- Criterio 1: Trayecto Formativo PST -->
<div class="li-af-group">
<label class="li-af-label">
<i class="ph-bold ph-path"></i> Trayecto Formativo PST
              </label>
<select class="li-af-select">
<option value="">Todos los Trayectos</option>
<option value="T1">Trayecto I (Soporte Técnico y Redes)</option>
<option value="T2">Trayecto II (Desarrollo y BD Básicas)</option>
<option value="T3">Trayecto III (Sistemas Distribuidos y Web)</option>
<option value="T4">Trayecto IV (Ingeniería e I+D Integral)</option>
</select>
</div>
<!-- Criterio 2: Estatus / Nivel de Cobertura -->
<div class="li-af-group">
<label class="li-af-label">
<i class="ph-bold ph-chart-donut"></i> Estatus / Cobertura
              </label>
<select class="li-af-select">
<option value="">Cualquier Cobertura</option>
<option selected="" value="proy">Con Proyectos Activos (&gt; 20)</option>
<option value="ofertas">Con Ofertas Docentes Abiertas</option>
<option value="form">En Fase de Formulación</option>
<option value="alta">Alta Cobertura (&gt; 70%)</option>
</select>
</div>
<!-- Criterio 3: Tipología de Dimensión / Área Tecnológica -->
<div class="li-af-group">
<label class="li-af-label">
<i class="ph-bold ph-tree-structure"></i> Área Tecnológica
              </label>
<select class="li-af-select">
<option value="">Todas las Áreas</option>
<option value="sw-libre">Software Libre y Abierto</option>
<option value="redes">Redes y Telecomunicaciones Comunitarias</option>
<option value="ciber">Seguridad y Criptografía</option>
<option value="iot">Internet de las Cosas (IoT) y Embebidos</option>
</select>
</div>
<!-- Criterio 4: Período / Año Académico -->
<div class="li-af-group">
<label class="li-af-label">
<i class="ph-bold ph-calendar-blank"></i> Año / Cohorte
              </label>
<select class="li-af-select">
<option value="">Todos los Períodos</option>
<option selected="" value="2024">Período Académico 2024</option>
<option value="2023">Período Académico 2023</option>
<option value="2022">Período Académico 2022</option>
</select>
</div>
</div>
<!-- Footer del Panel: Píldoras de Filtros Activos y Acciones -->
<div class="li-af-footer">
<div class="li-af-active-pills">
<span style="font-size: 0.75rem; font-weight: 700; color: var(--texto-silenciado); margin-right: 0.25rem;">
                Filtros aplicados:
              </span>
<span class="li-af-pill-removable" onclick="this.remove()">
<span>PNF: Informática</span>
<i class="ph-bold ph-x"></i>
</span>
<span class="li-af-pill-removable" onclick="this.remove()">
<span>Cobertura: Con Proyectos</span>
<i class="ph-bold ph-x"></i>
</span>
<span class="li-af-pill-removable" onclick="this.remove()">
<span>Año: 2024</span>
<i class="ph-bold ph-x"></i>
</span>
</div>
<div class="li-af-actions">
<button class="li-af-btn-clear" onclick="resetFilters()">
<i class="ph-bold ph-arrow-counter-clockwise"></i> Limpiar Filtros
              </button>
<button class="li-af-btn-apply" onclick="applyFilters()">
<i class="ph-bold ph-check"></i> Aplicar Filtros (4 Resultados)
              </button>
</div>
</div>
</section>
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
                    <span class="li-badge-operative">Operativo</span>
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
                    
                    <div class="li-progress-container">
                        <?php 
                        $cobertura = min(100, max(20, (int)($linea['total_proyectos'] ?? 0) * 10)); 
                        ?>
                        <div class="li-progress-labels">
                            <span>Cobertura Estimada</span>
                            <span class="li-progress-percent"><?= $cobertura ?>%</span>
                        </div>
                        <div class="li-progress-track">
                            <div class="li-progress-fill" style="width: <?= $cobertura ?>%;"></div>
                        </div>
                        <div class="li-card-footer-action">
                            <span>Explorar submódulos</span>
                            <i class="ph-bold ph-arrow-right"></i>
                        </div>
                    </div>
                </article>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endforeach; ?>
    
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
