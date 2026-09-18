<?php
// modules/LineasInvestigacion/views/showcase_lineas.php
// Variables inyectadas por ShowcaseLineasController:
//   $lineas (array), $total_dimensiones (int), $total_proyectos (int), $total_invest (int)

// Agrupar por carrera
$lineasPorCarrera = [];
foreach ($lineas as $l) {
    $carrera = empty($l['carrera_nombre']) ? 'General' : $l['carrera_nombre'];
    if (!isset($lineasPorCarrera[$carrera])) {
        $lineasPorCarrera[$carrera] = [];
    }
    $lineasPorCarrera[$carrera][] = $l;
}
?>

<div class="li-wrapper">

    <!-- HERO SECTION -->
    <div class="ag-header-banner" style="text-align: center;">
        <span class="ag-header-subtitle"><i class="ph-bold ph-network"></i> Ecosistema de Investigación</span>
        <h1 class="ag-header-title">Líneas de Investigación Institucionales</h1>
        <p class="ag-header-desc">
            Explora las directrices académicas que guían el desarrollo de proyectos y la innovación tecnológica.
        </p>
        
        <!-- GLOBAL SEARCH BAR -->
        <div style="margin-top: 2rem;">
            <input type="text" id="agSearchInput" class="ag-search-bar" placeholder="Buscar línea por nombre, palabra clave o PNF..." autocomplete="off">
        </div>
    </div>

    <!-- GLOBAL STATS -->
    <div class="li-stats-bar">
        <div class="li-stat-card">
            <div class="li-stat-icon" style="background: rgba(18, 26, 62, 0.08); color: var(--li-indigo);">
                <i class="ph-fill ph-git-merge"></i>
            </div>
            <div>
                <div class="li-stat-value"><?= count($lineas) ?></div>
                <div class="li-stat-label">Líneas Activas</div>
            </div>
        </div>
        <div class="li-stat-card">
            <div class="li-stat-icon" style="background: rgba(80, 89, 132, 0.08); color: var(--li-violet);">
                <i class="ph-fill ph-squares-four"></i>
            </div>
            <div>
                <div class="li-stat-value"><?= $total_dimensiones ?></div>
                <div class="li-stat-label">Dimensiones Operativas</div>
            </div>
        </div>
        <div class="li-stat-card">
            <div class="li-stat-icon" style="background: rgba(112, 144, 203, 0.08); color: var(--li-cyan);">
                <i class="ph-fill ph-folder-open"></i>
            </div>
            <div>
                <div class="li-stat-value"><?= $total_proyectos ?></div>
                <div class="li-stat-label">Proyectos Desarrollados</div>
            </div>
        </div>
        <div class="li-stat-card">
            <div class="li-stat-icon" style="background: rgba(217, 119, 6, 0.08); color: var(--li-amber);">
                <i class="ph-fill ph-flask"></i>
            </div>
            <div>
                <div class="li-stat-value"><?= $total_invest ?></div>
                <div class="li-stat-label">Investigaciones Ofertadas</div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT: SECTIONED BY PNF -->
    <div id="agLineasContainer">
        <?php if (empty($lineas)): ?>
            <div style="text-align: center; padding: 4rem; color: var(--text-muted);">
                <i class="ph-fill ph-empty" style="font-size: 3rem;"></i>
                <p>No hay líneas de investigación registradas.</p>
            </div>
        <?php else: ?>
            
            <?php foreach ($lineasPorCarrera as $carrera => $grupo): ?>
                <div class="ag-carrera-section" data-carrera="<?= htmlspecialchars(strtolower($carrera)) ?>">
                    <div style="border-bottom: 2px solid #e2e8f0; margin-bottom: 1.5rem; padding-bottom: 0.5rem; margin-top: 3rem;">
                        <h2 style="font-size: 1.5rem; color: var(--li-indigo); margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="ph-bold ph-graduation-cap"></i> <?= htmlspecialchars($carrera) ?>
                        </h2>
                    </div>

                    <div class="li-grid">
                        <?php foreach ($grupo as $index => $linea): 
                            $accentIdx = ($index % 4) + 1;
                            $porcentaje = $total_proyectos > 0 ? round(($linea['total_proyectos'] / $total_proyectos) * 100) : 0;
                        ?>
                            <a href="?ruta=detalle-linea&id=<?= $linea['id'] ?>" class="li-card ag-linea-item" data-nombre="<?= htmlspecialchars(strtolower($linea['nombre'])) ?>">
                                <div class="li-card-accent li-accent-<?= $accentIdx ?>"></div>
                                <div class="li-card-body">
                                    <div class="li-card-header-row">
                                        <div class="li-icon-box li-icon-<?= $accentIdx ?>">
                                            <i class="<?= htmlspecialchars($linea['icono'] ?: 'ph-fill ph-graph') ?>"></i>
                                        </div>
                                        <div>
                                            <h2 class="li-card-title">
                                                <?= htmlspecialchars(mb_convert_case($linea['nombre'], MB_CASE_TITLE, 'UTF-8')) ?>
                                            </h2>
                                        </div>
                                    </div>
                                    <?php if (!empty($linea['descripcion'])): ?>
                                        <p class="li-card-desc">
                                            <?= htmlspecialchars($linea['descripcion']) ?>
                                        </p>
                                    <?php endif; ?>

                                    <!-- Barra de Cobertura Visual -->
                                    <div style="margin-bottom: 1rem;">
                                        <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 4px;">
                                            <span>Cobertura de proyectos</span>
                                            <span><?= $porcentaje ?>%</span>
                                        </div>
                                        <div style="width: 100%; background: #f1f5f9; border-radius: 4px; height: 6px; overflow: hidden;">
                                            <div style="height: 100%; background: var(--li-indigo); width: <?= $porcentaje ?>%;"></div>
                                        </div>
                                    </div>

                                    <div class="li-card-stats">
                                        <div class="li-stat-item">
                                            <i class="ph-fill ph-squares-four"></i>
                                            <span class="li-stat-num"><?= (int)$linea['total_dimensiones'] ?></span> Dim.
                                        </div>
                                        <div class="li-stat-item">
                                            <i class="ph-fill ph-folder-open"></i>
                                            <span class="li-stat-num"><?= (int)$linea['total_proyectos'] ?></span> Proy.
                                        </div>
                                        <?php if ((int)$linea['total_investigaciones'] > 0): ?>
                                            <div class="li-stat-item">
                                                <i class="ph-fill ph-flask"></i>
                                                <span class="li-stat-num"><?= (int)$linea['total_investigaciones'] ?></span> Ofer.
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>

    <!-- CANVAS BACKGROUND EFFECT -->
    <canvas id="li-network-canvas" class="li-network-bg"></canvas>
</div>

<script src="../modules/LineasInvestigacion/assets/js/pst_network.js"></script>
<script>
    // Buscador en tiempo real
    const searchInput = document.getElementById('agSearchInput');
    const sections = document.querySelectorAll('.ag-carrera-section');

    if(searchInput) {
        searchInput.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase().trim();

            sections.forEach(sec => {
                const carrera = sec.getAttribute('data-carrera');
                const items = sec.querySelectorAll('.ag-linea-item');
                let matchesInSection = 0;

                items.forEach(item => {
                    const nombre = item.getAttribute('data-nombre');
                    // Mostrar si coincide el termino en nombre o carrera
                    if (nombre.includes(term) || carrera.includes(term)) {
                        item.style.display = 'block';
                        matchesInSection++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Ocultar sección completa si no hay matches
                if (matchesInSection === 0) {
                    sec.style.display = 'none';
                } else {
                    sec.style.display = 'block';
                }
            });
        });
    }
</script>
