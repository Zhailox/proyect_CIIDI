<?php
// modules/RepositorioPST/views/buscador_unificado.php
require_once __DIR__ . '/../services/ConfigService.php';
?>
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
                <input type="hidden" name="usar_ia" id="searchUsarIaHidden" value="<?= !empty($_GET['usar_ia']) ? '1' : '' ?>">

                <?php 
                $permitirFiltroCarrera = (bool)ConfigService::get('buscador.permitir_filtro_carrera', true);
                $selectedCarrera = $filtros['carrera_id'] ?? null;
                ?>
                <!-- Caja de Carrera (Dinámica / Bloqueada) -->
                <div class="filter-group-card">
                    <h3><i class="ph ph-graduation-cap"></i> Programa Académico</h3>
                    <?php if ($permitirFiltroCarrera): ?>
                        <div class="minimal-input-wrapper">
                            <select name="carrera_id" id="carreraFilterSelect" onchange="submitFilterForm()">
                                <option value="">Todas las carreras</option>
                                <?php if (!empty($carreras)): ?>
                                    <?php foreach ($carreras as $carrera): ?>
                                        <option value="<?= $carrera['id'] ?>" <?= ((string)$selectedCarrera === (string)$carrera['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($carrera['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <span class="select-arrow">▼</span>
                        </div>
                    <?php else: ?>
                        <div class="locked-value">
                            <span>PNF en Informática</span>
                            <span class="lock-badge"><i class="ph ph-lock-key"></i></span>
                        </div>
                    <?php endif; ?>
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
                        <?php if ($q !== '' || !empty($filtros['anio']) || !empty($filtros['linea_id']) || !empty($filtros['dimension_id']) || !empty($selectedCarrera) || !empty($_GET['usar_ia'])): ?>
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
                        <input type="hidden" name="carrera_id" value="<?= htmlspecialchars($selectedCarrera ?? '') ?>">
                        <input type="hidden" name="anio" value="<?= htmlspecialchars($filtros['anio'] ?? '') ?>">
                        <input type="hidden" name="linea_id" value="<?= htmlspecialchars($filtros['linea_id'] ?? '') ?>">
                        <input type="hidden" name="dimension_id" value="<?= htmlspecialchars($filtros['dimension_id'] ?? '') ?>">

                        <div class="google-search-bar <?= !empty($_GET['usar_ia']) ? 'ia-mode-container' : '' ?>" id="searchBarContainer">
                            <input type="text" name="q" id="searchQueryInput" value="<?= htmlspecialchars($q ?? '') ?>" placeholder="<?= !empty($_GET['usar_ia']) ? 'Describe tu propuesta o temática de investigación (Búsqueda Semántica con Redes Neuronales)...' : 'Buscar por títulos, palabras clave o resumen abstract...' ?>" autocomplete="off">
                            <svg class="google-search-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <button type="submit" class="btn-search-inner-submit" title="Buscar"><i class="ph ph-magnifying-glass"></i></button>
                        </div>
                        
                        <!-- Toggle de Búsqueda Semántica (IA) -->
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div class="semantic-toggle-wrapper">
                                <label class="semantic-switch" for="usarIaCheckbox">
                                    <input type="checkbox" id="usarIaCheckbox" name="usar_ia" value="1" <?= !empty($_GET['usar_ia']) ? 'checked' : '' ?> onchange="handleSemanticToggle(this)">
                                    <span class="semantic-slider"></span>
                                </label>
                                <label for="usarIaCheckbox" class="semantic-toggle-label <?= !empty($_GET['usar_ia']) ? 'active' : '' ?>">
                                    <i class="ph-bold ph-sparkle"></i> Búsqueda Semántica con Redes Neuronales (IA)
                                </label>
                            </div>
                            <div class="ia-hint <?= !empty($_GET['usar_ia']) ? 'visible' : '' ?>">
                                <i class="ph-bold ph-info"></i> Encuentra proyectos por significado conceptual analizando resúmenes y títulos mediante embeddings vectoriales (ONNX).
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Resultados de Búsqueda -->
                <div class="search-results-section">
                    <?php if ($q === '' && empty($filtros['anio']) && empty($filtros['linea_id']) && empty($filtros['dimension_id']) && empty($selectedCarrera)): ?>
                        <!-- Pantalla inicial / Estado Vacío inicial -->
                        <div class="search-welcome-state">
                            <i class="ph ph-books" style="font-size: 4rem; color: var(--color-terciario); opacity: 0.8;"></i>
                            <h2>Explora el repositorio PST</h2>
                            <p>Escribe palabras clave o usa los filtros del panel izquierdo (como el histograma de publicaciones) para iniciar la búsqueda.</p>
                        </div>
                    <?php else: ?>
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem;">
                            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 700; color: var(--texto-titulos);">
                                <?php if (!empty($usar_ia)): ?>
                                    <i class="ph-bold ph-sparkle" style="color: var(--color-secundario);"></i> Resultados por Coincidencia Semántica IA
                                <?php else: ?>
                                    Resultados Obtenidos
                                <?php endif; ?>
                                <span style="font-size: 0.85rem; font-weight: 500; color: var(--texto-silenciado);">
                                    (Mostrando <?= count($resultados) ?> de <?= $pagination['total_items'] ?>)
                                </span>
                            </h3>
                            <?php if (!empty($usar_ia)): ?>
                                <span style="background: rgba(80, 89, 132, 0.12); color: var(--color-secundario); font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 6px; border: 1px solid rgba(80, 89, 132, 0.25);">
                                    <i class="ph-bold ph-cpu"></i> Modelo MiniLM ONNX
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (empty($resultados)): ?>
                            <div class="no-results-card">
                                <i class="ph ph-warning-circle"></i>
                                <p>
                                    <?php if (!empty($usar_ia)): ?>
                                        No se encontraron proyectos con similitud semántica suficiente para tu consulta. Prueba con términos más descriptivos o desactiva la Búsqueda Semántica.
                                    <?php else: ?>
                                        No se encontraron proyectos PST que coincidan con la búsqueda.
                                    <?php endif; ?>
                                </p>
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
                                            <span class="badge-tipo"><i class="ph ph-file-text"></i> <?= htmlspecialchars(ConfigService::get('recursos.sufijo_tipo_recurso', 'PST / Proyecto Socio-Tecnológico')) ?></span>
                                            <?php if (isset($res['distancia'])): ?>
                                                <?php 
                                                    $dist = (float)$res['distancia'];
                                                    $similitud = max(0, min(100, round((1 - $dist) * 100, 1)));
                                                ?>
                                                <span class="badge-similitud" title="Distancia Coseno: <?= number_format($dist, 4) ?>">
                                                    <i class="ph-bold ph-sparkle"></i> <?= $similitud ?>% Similitud IA
                                                </span>
                                            <?php endif; ?>
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
                                        
                                        <div class="result-actions" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                            <a href="?ruta=detalles-pst&id=<?= $res['id'] ?>" class="btn-view-details">
                                                <i class="ph ph-info"></i> Ver Ficha Técnica
                                            </a>
                                            <?php if (ConfigService::puedeDescargarDocumento($res['archivo_pdf'] ?? null)): ?>
                                                <a href="?ruta=ver-pdf-pst&id=<?= $res['id'] ?>&download=1" class="btn-view-details" style="background: rgba(16, 185, 129, 0.1); color: #059669; border-color: rgba(16, 185, 129, 0.3); text-decoration: none;" target="_blank" download>
                                                    <i class="ph ph-download-simple"></i> Descargar Adjunto
                                                </a>
                                            <?php endif; ?>
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
            ctx.fillStyle = 'rgba(80, 89, 132, 0.85)'; // Color secundario de la paleta del sistema
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
                    ctx.strokeStyle = `rgba(112, 144, 203, ${0.45 * (1 - dist / 185)})`; // Color terciario de la paleta del sistema
                    ctx.lineWidth = 1.2;
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


