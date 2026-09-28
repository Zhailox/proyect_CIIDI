<?php
// modules/LineasInvestigacion/views/showcase_lineas.php
require_once CORE_PATH . 'Security/Auth.php';

$totalLineas = isset($lineas) ? count($lineas) : 0;
$totalDimensiones = $total_dimensiones ?? 0;
$totalProyectos = $total_proyectos ?? 0;
$totalOfertas = $total_invest ?? 0;

$lineasPorCarrera = [];
if (!empty($lineas)) {
    foreach ($lineas as $linea) {
        $carrera = !empty($linea['carrera_nombre']) ? $linea['carrera_nombre'] : 'General';
        $lineasPorCarrera[$carrera][] = $linea;
    }
}

$cosmosIcons = ['ph-cpu', 'ph-graph', 'ph-network', 'ph-code-block', 'ph-flask', 'ph-binary'];
// Gradientes usando la paleta CIIDI (#2b3453 â†’ #505984 â†’ #7090CB)
$cosmosGradients = [
    'linear-gradient(135deg, #2b3453 0%, #505984 100%)',
    'linear-gradient(135deg, #3d4b72 0%, #7090CB 100%)',
    'linear-gradient(135deg, #2b3453 0%, #3d4b72 100%)',
    'linear-gradient(135deg, #505984 0%, #7090CB 100%)',
];
?>

<div class="li-wrapper">
  <div class="view-container active-view" id="vista-landing">

<!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
     COSMOS HERO â€” Paleta oficial CIIDI
â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
<section class="cosmos-hero">

    <!-- Fondo animado tipo GIF: cuadrícula 3D + partículas + nebulosas -->
    <div class="cosmos-starfield" aria-hidden="true">
        <!-- Cuadrícula 3D en movimiento -->
        <div class="cosmos-moving-grid"></div>
        <!-- Partículas/estrellas parpadeantes -->
        <div class="cosmos-stars cosmos-stars-sm"></div>
        <div class="cosmos-stars cosmos-stars-md"></div>
        
        <!-- Nebulosas de color institucional -->
        <div class="cosmos-nebula cosmos-nebula-1"></div>
        <div class="cosmos-nebula cosmos-nebula-2"></div>
    </div>

    <div class="cosmos-hero-inner">
        <div class="cosmos-hero-badge">
            <i class="ph-bold ph-broadcast"></i>
            <span>CIIDI &middot; L&iacute;neas Activas</span>
        </div>

        <h1 class="cosmos-hero-title">
            Ecosistema de <span class="cosmos-accent-text">Investigación</span> CIIDI
        </h1>

        <p class="cosmos-hero-desc">
            Catálogo unificado y articulador de líneas de investigación institucionales,
            dimensiones operativas y producción académica sociotecnológica.
        </p>

        <!-- Buscador Glassmorphism -->
        <div class="cosmos-search-wrap">
            <div class="cosmos-search-box">
                <i class="ph-bold ph-magnifying-glass cosmos-search-icon"></i>
                <input class="cosmos-search-input li-search-input"
                    placeholder="Buscar línea de investigación (ej. Ingeniería de Software, Redes)..."
                    type="text"/>
                <button class="cosmos-search-btn li-search-action-btn">
                    <span>Consultar</span>
                    <i class="ph-bold ph-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- KPIs con counter animado -->
        <div class="cosmos-kpi-row">
            <div class="cosmos-kpi-chip">
                <i class="ph-bold ph-compass"></i>
                <div class="cosmos-kpi-data">
                    <span class="cosmos-kpi-num" data-target="<?= $totalLineas ?>">0</span>
                    <span class="cosmos-kpi-label">Líneas Activas</span>
                </div>
            </div>
            <div class="cosmos-kpi-sep" aria-hidden="true"></div>
            <div class="cosmos-kpi-chip">
                <i class="ph-bold ph-tree-structure"></i>
                <div class="cosmos-kpi-data">
                    <span class="cosmos-kpi-num" data-target="<?= $totalDimensiones ?>">0</span>
                    <span class="cosmos-kpi-label">Dimensiones Validadas</span>
                </div>
            </div>
            <div class="cosmos-kpi-sep" aria-hidden="true"></div>
            <div class="cosmos-kpi-chip">
                <i class="ph-bold ph-folder-open"></i>
                <div class="cosmos-kpi-data">
                    <span class="cosmos-kpi-num" data-target="<?= $totalProyectos ?>">0</span>
                    <span class="cosmos-kpi-label">Proyectos PST</span>
                </div>
            </div>
            <div class="cosmos-kpi-sep" aria-hidden="true"></div>
            <div class="cosmos-kpi-chip">
                <i class="ph-bold ph-chalkboard-teacher"></i>
                <div class="cosmos-kpi-data">
                    <span class="cosmos-kpi-num" data-target="<?= $totalOfertas ?>">0</span>
                    <span class="cosmos-kpi-label">Ofertas Docentes</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
     CONTENIDO PRINCIPAL
â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
<main class="li-main-content">
    <div class="cosmos-main-layout">

        <div class="li-layout-left">
            <!-- Toolbar -->
            <div class="li-section-toolbar">
                <div class="li-toolbar-left">
                    <h2 class="li-section-title">Líneas de Investigación</h2>
                    <span class="li-badge-counter"><?= $totalLineas ?> Activas</span>
                </div>
                <div class="li-toolbar-right">
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

            <?php $globalIndex = 0; foreach ($lineasPorCarrera as $carrera => $grupo): ?>
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

                <div class="li-items-container cosmos-cards-grid grid-view">
                    <?php foreach ($grupo as $linea):
                        $idx      = $globalIndex % count($cosmosGradients);
                        $iconIdx  = $globalIndex % count($cosmosIcons);
                        $indexLabel = str_pad($globalIndex + 1, 2, '0', STR_PAD_LEFT);
                        $gradient = $cosmosGradients[$idx];
                        $bgIcon   = $cosmosIcons[$iconIdx];
                        $globalIndex++;
                    ?>
                    <a href="?ruta=detalle-linea&id=<?= $linea['id'] ?>" style="text-decoration:none;color:inherit;display:flex;flex-direction:column;height:100%;">
                        <article class="cosmos-card li-card" style="background:linear-gradient(135deg, #505984 0%, #1e293b 100%); flex: 1;">
                            <div class="cosmos-card-watermark" aria-hidden="true">
                                <i class="ph-bold <?= $bgIcon ?>"></i>
                            </div>
                            <span class="cosmos-card-index"><?= $indexLabel ?></span>

                            <div class="cosmos-card-body">
                                <div class="cosmos-card-icon-wrap">
                                    <i class="ph-bold ph-code"></i>
                                </div>
                                <h4 class="cosmos-card-title"><?= htmlspecialchars($linea['nombre']) ?></h4>
                                <p class="cosmos-card-desc">
                                    <?= htmlspecialchars($linea['descripcion'] ?: 'Ãrea de investigación y desarrollo sociotecnológico institucional.') ?>
                                </p>
                                <div class="cosmos-card-stats">
                                    <span class="cosmos-stat-pill">
                                        <i class="ph-bold ph-tree-structure"></i>
                                        <?= (int)($linea['total_dimensiones'] ?? 0) ?> Dim.
                                    </span>
                                    <span class="cosmos-stat-pill">
                                        <i class="ph-bold ph-folder-notch"></i>
                                        <?= (int)($linea['total_proyectos'] ?? 0) ?> Proy.
                                    </span>
                                    <?php if (!empty($linea['total_investigaciones'])): ?>
                                    <span class="cosmos-stat-pill cosmos-stat-active">
                                        <i class="ph-bold ph-hand-pointing"></i>
                                        <?= (int)$linea['total_investigaciones'] ?> Ofertas
                                    </span>
                                    <?php else: ?>
                                    <span class="cosmos-stat-pill">
                                        <i class="ph-bold ph-hand-pointing"></i> 0 Ofertas
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="cosmos-card-footer">
                                <span class="cosmos-explore-link">
                                    Explorar dimensiones
                                    <i class="ph-bold ph-arrow-right"></i>
                                </span>
                            </div>
                        </article>
                    </a>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endforeach; ?>
        </div>

        <!-- Sidebar -->
        <aside class="li-layout-right">
            <section class="cosmos-sidebar-card">
                <div class="cosmos-sidebar-header">
                    <i class="ph-bold ph-lightning"></i>
                    <h3>Ofertas de Investigaciones</h3>
                </div>
                <div class="cosmos-sidebar-body">
                    <?php if (empty($ofertas_recientes)): ?>
                        <div class="cosmos-sidebar-empty">
                            <i class="ph-bold ph-archive-box"></i>
                            <p>No hay ofertas activas en este momento.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($ofertas_recientes as $oferta): ?>
                        <a href="?ruta=investigaciones" class="cosmos-oferta-item">
                            <div class="cosmos-oferta-accent-bar"></div>
                            <div class="cosmos-oferta-content">
                                <span class="cosmos-oferta-badge">ACTIVA Â· <?= $oferta['cupos_disponibles'] ?> Cupos</span>
                                <h4 class="cosmos-oferta-title"><?= htmlspecialchars($oferta['titulo'] ?? 'Requerimiento PST') ?></h4>
                                <div class="cosmos-oferta-meta"><i class="ph-bold ph-user"></i> Prof. <?= htmlspecialchars($oferta['profesor']) ?></div>
                                <div class="cosmos-oferta-linea"><i class="ph-bold ph-flask"></i> <?= htmlspecialchars($oferta['linea_nombre'] ?? 'Línea General') ?></div>
                                <div class="cosmos-oferta-action">Postularse <i class="ph-bold ph-arrow-right"></i></div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <a href="?ruta=investigaciones" class="cosmos-sidebar-footer-link">
                    <i class="ph-bold ph-arrow-square-out"></i> Ver todas las ofertas
                </a>
            </section>
        </aside>

    </div>
</main>

</div>

<script>
function setListingView(mode) {
    const containers = document.querySelectorAll('.li-items-container');
    const btnGrid = document.getElementById('btn-view-grid');
    const btnList = document.getElementById('btn-view-list');
    if (mode === 'list') {
        containers.forEach(c => { c.classList.remove('grid-view'); c.classList.add('list-view'); });
        btnGrid?.classList.remove('active');
        btnList?.classList.add('active');
    } else {
        containers.forEach(c => { c.classList.remove('list-view'); c.classList.add('grid-view'); });
        btnList?.classList.remove('active');
        btnGrid?.classList.add('active');
    }
}
function toggleAdvancedFilters() {
    document.getElementById('panel-filtros')?.classList.toggle('active');
}
document.addEventListener('DOMContentLoaded', () => {
    // Animación counter KPIs
    document.querySelectorAll('.cosmos-kpi-num').forEach(el => {
        const target = parseInt(el.getAttribute('data-target')) || 0;
        if (!target) { el.textContent = '0'; return; }
        let cur = 0;
        const step = Math.max(1, Math.ceil(target / 35));
        const t = setInterval(() => {
            cur = Math.min(cur + step, target);
            el.textContent = cur;
            if (cur >= target) clearInterval(t);
        }, 28);
    });

    // Animación entrada escalonada de las cards
    document.querySelectorAll('.cosmos-card').forEach((card, i) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(24px)';
        setTimeout(() => {
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100 + i * 80);
    });

    // Buscador
    const searchInput = document.querySelector('.cosmos-search-input');
    const searchBtn   = document.querySelector('.cosmos-search-btn');
    function filterCards() {
        if (!searchInput) return;
        const term = searchInput.value.toLowerCase().trim();
        document.querySelectorAll('.cosmos-card').forEach(card => {
            const title = card.querySelector('.cosmos-card-title')?.textContent.toLowerCase() || '';
            const desc  = card.querySelector('.cosmos-card-desc')?.textContent.toLowerCase()  || '';
            const anchor = card.closest('a');
            if (anchor) anchor.style.display = (title.includes(term) || desc.includes(term)) ? '' : 'none';
        });
    }
    searchInput?.addEventListener('input', filterCards);
    searchBtn?.addEventListener('click', e => { e.preventDefault(); filterCards(); });
});
</script>
</div>

