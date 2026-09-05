<?php
// modules/RepositorioPST/views/buscador_unificado.php
require_once __DIR__ . '/../services/ConfigService.php';
?>
<div class="main-content">
    <!-- FONDO DE PÁGINA COMPLETA CON REDES AZULES Y FONDO BLANCO -->
    <div class="search-page-canvas-bg" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; pointer-events: none; z-index: 0; background: #ffffff;">
        <canvas id="pstSearchPageCanvas" style="width: 100%; height: 100%; display: block;"></canvas>
    </div>

    <div class="search-view-wrapper" style="position: relative; z-index: 1;">
        
        <div class="search-brand">
            <h1>Búsqueda Inteligente</h1>
            <div class="search-badge">
                <i class="ph ph-sparkles"></i> Motor de búsqueda unificado
            </div>
        </div>

        <div class="search-layout-grid">
            <!-- Columna Izquierda: Filtros Avanzados (OpenAlex Style) -->
            <aside class="search-sidebar-column">
                <form id="searchFilterForm" action="" method="GET">
                    <input type="hidden" name="ruta" value="buscador">
                    <input type="hidden" name="q" id="searchQueryHidden" value="<?= htmlspecialchars($q ?? '') ?>">
                    <input type="hidden" name="anio" id="searchYearInput" value="<?= htmlspecialchars($filtros['anio'] ?? '') ?>">

                    <!-- Caja de Carrera (Bloqueada) -->
                    <div class="filter-group-card">
                        <h3><i class="ph ph-graduation-cap"></i> Programa Académico</h3>
                        <div class="locked-value">
                            <span>PNF en Informática</span>
                            <span class="lock-badge"><i class="ph ph-lock-key"></i></span>
                        </div>
                    </div>

                    <!-- Histograma Interactivo para el Año -->
                    <div class="filter-group-card">
                        <h3><i class="ph ph-chart-bar"></i> Distribución por Año</h3>
                        <p class="filter-help-text">Haz clic en un año para filtrar</p>
                        
                        <div class="year-histogram-container">
                            <?php 
                            $maxCount = !empty($anioCounts) ? max($anioCounts) : 1;
                            if ($maxCount <= 0) $maxCount = 1;
                            foreach ($anioCounts as $year => $count): 
                                $percent = ($count / $maxCount) * 100;
                                $isActive = (isset($filtros['anio']) && (int)$filtros['anio'] === $year);
                            ?>
                                <div class="histogram-col <?= $isActive ? 'active' : '' ?>" 
                                     data-year="<?= $year ?>" 
                                     onclick="selectYear(<?= $year ?>)"
                                     title="<?= $year ?>: <?= $count ?> proyectos">
                                    <div class="histogram-bar-wrapper">
                                        <div class="histogram-bar" style="height: <?= max(5, $percent) ?>%;">
                                            <span class="histogram-tooltip"><?= $count ?></span>
                                        </div>
                                    </div>
                                    <span class="histogram-year-label"><?= substr($year, 2) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (!empty($filtros['anio'])): ?>
                            <button type="button" class="btn-reset-year" onclick="selectYear('')">
                                <i class="ph ph-x-circle"></i> Quitar filtro de año (<?= $filtros['anio'] ?>)
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- Línea de Investigación -->
                    <div class="filter-group-card">
                        <h3><i class="ph ph-compass"></i> Línea de Investigación</h3>
                        <div class="minimal-input-wrapper">
                            <select name="linea_id" id="linea_id">
                                <option value="">Todas las líneas</option>
                                <?php foreach ($lineas as $linea): ?>
                                    <option value="<?= $linea['id'] ?>" <?= ($filtros['linea_id'] == $linea['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($linea['nombre'] ?? '') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="select-arrow">▼</span>
                        </div>
                    </div>

                    <!-- Dimensión Operativa Dependiente -->
                    <div class="filter-group-card">
                        <h3><i class="ph ph-tree-structure"></i> Dimensión Operativa</h3>
                        <div class="minimal-input-wrapper">
                            <select name="dimension_id" id="dimension_id" disabled>
                                <option value="">Todas las dimensiones</option>
                            </select>
                            <span class="select-arrow">▼</span>
                        </div>
                    </div>

                    <div style="margin-top: 1rem;">
                        <button type="submit" class="btn-apply-filters"><i class="ph ph-funnel"></i> Aplicar Filtros</button>
                        <?php if ($q !== '' || !empty($filtros['anio']) || !empty($filtros['linea_id']) || !empty($filtros['dimension_id'])): ?>
                            <a href="?ruta=buscador" class="btn-reset-all">Restablecer Todo</a>
                        <?php endif; ?>
                    </div>
                </form>
            </aside>

            <!-- Columna Derecha: Entrada de Búsqueda y Resultados -->
            <main class="search-main-column">
                
                <!-- Input de búsqueda y modo switch -->
                <div class="search-bar-panel">
                    <form id="searchBarForm" action="" method="GET">
                        <input type="hidden" name="ruta" value="buscador">
                        <input type="hidden" name="anio" value="<?= htmlspecialchars($filtros['anio'] ?? '') ?>">
                        <input type="hidden" name="linea_id" value="<?= htmlspecialchars($filtros['linea_id'] ?? '') ?>">
                        <input type="hidden" name="dimension_id" value="<?= htmlspecialchars($filtros['dimension_id'] ?? '') ?>">

                        <div class="google-search-bar" id="searchBarContainer">
                            <input type="text" name="q" id="searchQueryInput" value="<?= htmlspecialchars($q ?? '') ?>" placeholder="Buscar por títulos, palabras clave o resumen abstract..." autocomplete="off">
                            <svg class="google-search-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <button type="submit" class="btn-search-inner-submit"><i class="ph ph-magnifying-glass"></i></button>
                        </div>
                    </form>
                </div>

                <!-- Resultados de Búsqueda -->
                <div class="search-results-section">
                    <?php if ($q === '' && empty($filtros['anio']) && empty($filtros['linea_id']) && empty($filtros['dimension_id'])): ?>
                        <!-- Pantalla inicial / Estado Vacío inicial -->
                        <div class="search-welcome-state">
                            <i class="ph ph-books" style="font-size: 4rem; color: var(--color-terciario); opacity: 0.8;"></i>
                            <h2>Explora el repositorio PST</h2>
                            <p>Escribe palabras clave o usa los filtros del panel izquierdo (como el histograma de publicaciones) para iniciar la búsqueda.</p>
                        </div>
                    <?php else: ?>
                        <h3>Resultados Obtenidos (Mostrando <?= count($resultados) ?> de <?= $pagination['total_items'] ?>)</h3>
                        
                        <?php if (empty($resultados)): ?>
                            <div class="no-results-card">
                                <i class="ph ph-warning-circle"></i>
                                <p>No se encontraron proyectos PST que coincidan con la búsqueda.</p>
                            </div>
                        <?php else: ?>
                            <div class="results-grid">
                                <?php 
                                $resaltar = ConfigService::get('buscador.resaltar_coincidencias', true);
                                $highlight = function($text, $query) use ($resaltar) {
                                    $safeText = htmlspecialchars($text ?? '');
                                    if (!$resaltar || empty($query)) return $safeText;
                                    $pattern = '/' . preg_quote($query, '/') . '/i';
                                    return preg_replace($pattern, '<mark style="background-color: #fef08a; color: #854d0e; padding: 0.1rem 0.25rem; border-radius: 3px; font-weight: 700;">$0</mark>', $safeText);
                                };
                                ?>
                                <?php foreach ($resultados as $res): ?>
                                    <div class="result-card">
                                        <div class="result-card-header">
                                            <span class="badge-tipo"><i class="ph ph-file-text"></i> PST / Proyecto Socio-Tecnológico</span>
                                            <span class="result-year"><?= $res['anio_publicacion'] ?></span>
                                        </div>
                                        <h4 class="result-title">
                                            <a href="?ruta=detalles-pst&id=<?= $res['id'] ?>"><?= $highlight($res['titulo'] ?? '', $q) ?></a>
                                        </h4>
                                        <p class="result-summary">
                                            <?= $highlight($res['proyecto_resumen'] ?? 'Sin resumen cargado en el sistema.', $q) ?>
                                        </p>
                                        
                                        <!-- Línea, Trayecto y Dimensión en los resultados -->
                                        <div class="result-classification-tags">
                                            <?php if (!empty($res['nivel_academico']) && $res['nivel_academico'] !== 'Pregrado'): ?>
                                                <span class="tag-linea" style="background-color: rgba(112, 144, 203, 0.15); color: var(--color-secundario); font-weight: 700;">
                                                    <i class="ph ph-graduation-cap"></i> <?= htmlspecialchars($res['nivel_academico'] ?? '') ?>
                                                </span>
                                            <?php elseif (!empty($res['trayecto'])): ?>
                                                <span class="tag-linea" style="background-color: rgba(0, 123, 255, 0.1); color: var(--color-terciario); font-weight: 700;">
                                                    <i class="ph ph-graduation-cap"></i> <?= htmlspecialchars($res['trayecto'] ?? '') ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if (!empty($res['linea_nombre'])): ?>
                                                <span class="tag-linea"><i class="ph ph-compass"></i> <?= htmlspecialchars($res['linea_nombre'] ?? '') ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($res['dimension_nombre'])): ?>
                                                <span class="tag-dimension"><i class="ph ph-tree-structure"></i> <?= htmlspecialchars($res['dimension_nombre'] ?? '') ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($res['url_repositorio']) && ConfigService::get('recursos.mostrar_url_git', true)): ?>
                                                <a href="<?= htmlspecialchars($res['url_repositorio'] ?? '') ?>" target="_blank" class="tag-linea" style="background-color: #002244; color: #fff; text-decoration: none;">
                                                    <i class="ph ph-git-branch"></i> Git
                                                </a>
                                            <?php endif; ?>
                                        </div>

                                        <div class="result-meta">
                                            <span><strong>Autores:</strong> <?= htmlspecialchars($res['autores_nombres'] ?? 'No registrados') ?></span>
                                            <?php if (!empty($res['proyecto_palabras'])): ?>
                                                <span><strong>Palabras Clave:</strong> <?= htmlspecialchars($res['proyecto_palabras'] ?? '') ?></span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="result-actions">
                                            <a href="?ruta=detalles-pst&id=<?= $res['id'] ?>" class="btn-view-details">
                                                <i class="ph ph-info"></i> Ver Ficha Técnia
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Paginador del Buscador -->
                            <?php if ($pagination['total_pages'] > 1): ?>
                                <div class="pst-pagination">
                                    <?php 
                                    $query_params = $_GET;
                                    unset($query_params['page']); 
                                    
                                    $build_url = function($p) use ($query_params) {
                                        $query_params['page'] = $p;
                                        return '?' . http_build_query($query_params);
                                    };
                                    
                                    $curr = $pagination['current_page'];
                                    $tot = $pagination['total_pages'];
                                    ?>
                                    
                                    <?php if ($curr > 1): ?>
                                        <a href="<?= $build_url($curr - 1) ?>" class="page-link">&laquo; Anterior</a>
                                    <?php else: ?>
                                        <span class="page-link disabled">&laquo; Anterior</span>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $tot; $i++): ?>
                                        <?php if ($i == $curr): ?>
                                            <span class="page-link active"><?= $i ?></span>
                                        <?php else: ?>
                                            <a href="<?= $build_url($i) ?>" class="page-link"><?= $i ?></a>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    
                                    <?php if ($curr < $tot): ?>
                                        <a href="<?= $build_url($curr + 1) ?>" class="page-link">Siguiente &raquo;</a>
                                    <?php else: ?>
                                        <span class="page-link disabled">Siguiente &raquo;</span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

            </main>
        </div>

    </div>
</div>

<script>
<?php include __DIR__ . '/../assets/js/search_engine.js'; ?>
</script>
<script>
// ANIMACIÓN DE CANVAS PARA EL FONDO DE PÁGINA COMPLETA (FONDO BLANCO Y REDES AZULES)
(function initPstSearchPageCanvas() {
    const canvas = document.getElementById('pstSearchPageCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let width, height;
    let particles = [];

    function resize() {
        width = canvas.width = window.innerWidth;
        height = canvas.height = window.innerHeight;
    }
    window.addEventListener('resize', resize);
    resize();

    class Particle {
        constructor() {
            this.x = Math.random() * width;
            this.y = Math.random() * height;
            this.vx = (Math.random() - 0.5) * 0.7;
            this.vy = (Math.random() - 0.5) * 0.7;
            this.radius = Math.random() * 4 + 3.5; // Nodos más grandes (3.5px a 7.5px)
        }
        update() {
            this.x += this.vx;
            this.y += this.vy;
            if (this.x < 0 || this.x > width) this.vx *= -1;
            if (this.y < 0 || this.y > height) this.vy *= -1;
        }
        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(0, 102, 255, 0.85)'; // Nodos azul brillante e intenso
            ctx.fill();
        }
    }

    const numParticles = Math.min(Math.floor(width / 12), 85); // Mayor densidad de nodos
    for (let i = 0; i < numParticles; i++) {
        particles.push(new Particle());
    }

    let animId = null;
    function animate() {
        if (document.hidden) return;
        ctx.clearRect(0, 0, width, height);
        for (let i = 0; i < particles.length; i++) {
            particles[i].update();
            particles[i].draw();
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < 185) {
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.strokeStyle = `rgba(0, 102, 255, ${0.55 * (1 - dist / 185)})`;
                    ctx.lineWidth = 1.8;
                    ctx.stroke();
                }
            }
        }
        animId = requestAnimationFrame(animate);
    }
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            if (animId) cancelAnimationFrame(animId);
            animate();
        }
    });
    animate();
})();

document.addEventListener('DOMContentLoaded', () => {
    initDimensionSelector(
        <?= json_encode($dimensiones) ?>,
        <?= json_encode($filtros['dimension_id'] ?? '') ?>
    );
});
</script>


