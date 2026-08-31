<?php require_once __DIR__ . '/../services/ConfigService.php'; ?>
<style>
/* Ajustes de espaciado y compresión visual */
.main-content {
    padding: 0.75rem 1rem !important;
}
.pst-header {
    margin-bottom: 0.75rem !important;
}
.pst-header h1 {
    font-size: 1.7rem !important;
    margin-bottom: 0.15rem !important;
    color: var(--texto-titulos);
}
.pst-header p {
    font-size: 0.85rem !important;
    color: var(--texto-silenciado);
}

/* Tarjeta Principal (Compacta y sin cover degradado azul) */
.pst-hero-card {
    margin-bottom: 1.25rem !important;
    display: block !important;
    background-color: var(--bg-card);
    border: 1px solid rgba(169, 168, 166, 0.2);
    border-radius: var(--radius-md);
}
.pst-hero-content {
    width: 100% !important;
    padding: 1rem !important;
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}
.pst-hero-content h3 {
    font-size: 1.2rem !important;
    margin: 0 !important;
    font-weight: 800;
}
.pst-hero-content p {
    font-size: 0.85rem !important;
    margin: 0.25rem 0 !important;
    line-height: 1.4;
}

/* Layout en cuadrícula: Contenido a la izquierda (3 columnas) y Sidebar a la derecha (1 columna) */
.pst-layout-grid {
    display: grid;
    grid-template-columns: 3.2fr 1fr;
    gap: 1rem;
    align-items: start;
}
@media (max-width: 992px) {
    .pst-layout-grid {
        grid-template-columns: 1fr;
    }
}

/* Estilos planos del panel lateral de filtros */
.pst-sidebar-panel {
    background-color: var(--bg-card, #ffffff);
    border: 1px solid rgba(169, 168, 166, 0.2);
    border-radius: var(--radius-md, 6px);
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}
.pst-sidebar-panel h3 {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--texto-titulos);
    border-bottom: 1px solid rgba(169, 168, 166, 0.2);
    padding-bottom: 0.4rem;
    margin: 0 0 0.25rem 0;
    display: flex;
    align-items: center;
    gap: 0.35rem;
}
.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}
.filter-group label {
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--texto-titulos, #333);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.filter-group select {
    width: 100%;
    padding: 0.45rem;
    border: 1px solid rgba(169, 168, 166, 0.4);
    border-radius: var(--radius-sm, 4px);
    background-color: #fcfcfc;
    color: var(--texto-normal, #555);
    font-size: 0.85rem;
    outline: none;
    transition: border-color 0.2s;
}
.filter-group select:focus {
    border-color: var(--color-terciario, #007bff);
}
.filter-group select:disabled {
    background-color: #f3f4f6;
    color: #9ca3af;
    cursor: not-allowed;
    border-color: #e5e7eb;
}
.btn-clear-filters {
    display: block;
    padding: 0.5rem;
    background-color: #e2e8f0;
    color: #333;
    border-radius: var(--radius-sm, 4px);
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 700;
    text-align: center;
    border: none;
    cursor: pointer;
    transition: background-color 0.2s;
}
.btn-clear-filters:hover {
    background-color: #cbd5e0;
}

/* Estilos del histograma interactivo de años */
.year-histogram-container {
    display: flex;
    align-items: flex-end;
    gap: 3px;
    height: 70px;
    padding: 0.5rem 0.25rem 0.25rem 0.25rem;
    background-color: #fafbfc;
    border: 1px solid rgba(169, 168, 166, 0.2);
    border-radius: var(--radius-sm, 4px);
    margin-top: 0.25rem;
}
.histogram-col {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100%;
    cursor: pointer;
}
.histogram-bar-wrapper {
    flex: 1;
    width: 100%;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    position: relative;
}
.histogram-bar {
    width: 80%;
    background-color: rgba(112, 144, 203, 0.35);
    border-radius: 2px 2px 0 0;
    transition: background-color 0.2s, height 0.3s;
    position: relative;
}
.histogram-col:hover .histogram-bar {
    background-color: var(--color-terciario, #007bff);
}
.histogram-col.active .histogram-bar {
    background-color: var(--color-secundario, #002244);
}
.histogram-tooltip {
    display: none;
    position: absolute;
    top: -20px;
    left: 50%;
    transform: translateX(-50%);
    background: #002244;
    color: white;
    font-size: 0.65rem;
    padding: 1px 4px;
    border-radius: 3px;
    white-space: nowrap;
}
.histogram-col:hover .histogram-tooltip {
    display: block;
}
.histogram-year-label {
    font-size: 0.65rem;
    color: var(--texto-silenciado, #666);
    margin-top: 2px;
    font-weight: 700;
}
.btn-reset-year {
    display: inline-block;
    width: 100%;
    margin-top: 0.35rem;
    padding: 0.25rem;
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 700;
    cursor: pointer;
    text-align: center;
}

/* Reducción de espaciados de la Tabla */
.pst-table-wrapper {
    margin-top: 0 !important;
    padding: 0.75rem !important;
    background-color: var(--bg-card);
    border: 1px solid rgba(169, 168, 166, 0.2);
    border-radius: var(--radius-md);
}
.pst-table-title {
    font-size: 0.95rem !important;
    margin: 0 0 0.5rem 0 !important;
}
.pst-table {
    margin-bottom: 0 !important;
}
.pst-table th {
    padding: 0.4rem 0.6rem !important;
    font-size: 0.75rem !important;
}
.pst-table td {
    padding: 0.4rem 0.6rem !important;
    font-size: 0.8rem !important;
    line-height: 1.3;
}
.pst-badge-soft {
    padding: 0.15rem 0.3rem !important;
    font-size: 0.7rem !important;
    border-radius: 4px;
    font-weight: 700;
}

/* Estilos del paginador plano */
.pst-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.25rem;
    margin-top: 0.75rem;
    padding-top: 0.5rem;
    border-top: 1px solid rgba(169, 168, 166, 0.1);
}
.page-link {
    display: inline-block;
    padding: 0.3rem 0.6rem;
    background-color: #f7f9fa;
    border: 1px solid rgba(169, 168, 166, 0.2);
    color: var(--color-secundario, #002244);
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 700;
    border-radius: 4px;
    transition: all 0.1s;
}
.page-link:hover {
    background-color: var(--color-terciario, #007bff);
    color: white;
    border-color: var(--color-terciario, #007bff);
}
.page-link.active {
    background-color: var(--color-terciario, #007bff);
    color: white;
    border-color: var(--color-terciario, #007bff);
}
.page-link.disabled {
    background-color: #f3f4f6;
    color: #9ca3af;
    border-color: #e5e7eb;
    pointer-events: none;
}
</style>

<div class="main-content">
    <div class="pst-container">

        <header class="pst-header">
            <h1>Proyectos Socio-Tecnológicos (PST)</h1>
            <p>Repositorio de investigaciones aprobadas del PNF en Informática (UPTTMBI)</p>
        </header>

        <!-- Layout de Cuadrícula con Sidebar a la derecha -->
        <div class="pst-layout-grid">
            
            <!-- Columna Izquierda: Tarjeta Destacada y Tabla de Resultados -->
            <div class="pst-main-column">
                
                <!-- Sección de Proyecto Destacado (Sin resumen ni autores según directiva de diseño) -->
                <?php if (!empty($documentos) && $pagination['current_page'] == 1): 
                    $destacado = $documentos[0];
                ?>
                    <article class="pst-hero-card">
                        <div class="pst-hero-content">
                            <div style="display: flex; gap: 0.4rem; align-items: center; margin-bottom: 0.35rem; flex-wrap: wrap;">
                                <span class="pst-badge-soft" style="font-size: 0.75rem; background-color: var(--color-terciario); color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700;">ÚLTIMO REGISTRO</span>
                                <span class="pst-badge-soft" style="background-color: rgba(112, 144, 203, 0.15); color: var(--color-secundario); font-weight: 700;"><?= htmlspecialchars($destacado['nivel_academico'] ?? 'Pregrado') ?></span>
                                <?php if (($destacado['nivel_academico'] ?? 'Pregrado') === 'Pregrado' && !empty($destacado['trayecto'])): ?>
                                    <span class="pst-badge-soft" style="background-color: rgba(0, 123, 255, 0.1); color: var(--color-terciario); font-weight: 700;"><?= htmlspecialchars($destacado['trayecto']) ?></span>
                                <?php endif; ?>
                                <?php if (!empty($destacado['url_repositorio']) && ConfigService::get('recursos.mostrar_url_git', true)): ?>
                                    <a href="<?= htmlspecialchars($destacado['url_repositorio']) ?>" target="_blank" class="pst-badge-soft" style="background-color: #f1f5f9; color: var(--color-secundario); text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 0.25rem;">
                                        <i class="ph ph-git-branch"></i> Git Repository
                                    </a>
                                <?php endif; ?>
                            </div>
                            <h3><?= htmlspecialchars($destacado['titulo'] ?? '') ?></h3>
                            <div style="font-size: 0.8rem; color: var(--texto-silenciado); line-height: 1.3; margin-bottom: 0.5rem; margin-top: 0.25rem;">
                                <strong>Comunidad:</strong> <?= htmlspecialchars($destacado['comunidad_beneficiada'] ?? 'No registrada') ?>
                            </div>
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <a href="?ruta=detalles-pst&id=<?= $destacado['id'] ?>" class="btn-outline-repo" style="text-decoration: none; padding: 0.4rem 1rem; border-radius: 4px; font-size: 0.8rem;">
                                    Ver Ficha Completa
                                </a>
                                <button type="button" class="btn-outline-repo" style="padding: 0.4rem 0.8rem; border-radius: 4px; font-size: 0.8rem; cursor: pointer;" onclick="abrirModalCita(<?= htmlspecialchars(json_encode($destacado['titulo'])) ?>, <?= htmlspecialchars(json_encode($destacado['autores_nombres'] ?? 'Autores Varios')) ?>, <?= $destacado['anio_publicacion'] ?>)">
                                    <i class="ph ph-quotes"></i> Citar
                                </button>
                            </div>
                        </div>
                    </article>
                <?php endif; ?>

                <!-- Listado de Investigaciones Indexadas (Sin resumen ni columna de autores) -->
                <section class="pst-table-wrapper">
                    <h3 class="pst-table-title">Banco de Proyectos (Mostrando <?= count($documentos) ?> de <?= $pagination['total_items'] ?>)</h3>
                    
                    <div class="table-responsive">
                        <table class="pst-table">
                            <thead>
                                <tr>
                                    <th style="width: 65%;">TÍTULO DEL PROYECTO</th>
                                    <th style="width: 20%;">LÍNEA / NIVEL</th>
                                    <th style="width: 7%;">AÑO</th>
                                    <th style="text-align: center; width: 8%;">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($documentos)): ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center; padding: 2rem; color: var(--texto-silenciado);">
                                            No se encontraron proyectos con los filtros de catalogación seleccionados.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($documentos as $doc): ?>
                                        <tr>
                                            <td class="pst-td-title">
                                                <strong><?= htmlspecialchars($doc['titulo'] ?? '') ?></strong>
                                            </td>
                                            <td>
                                                <span class="pst-badge-soft badge-edu" style="display: block; margin-bottom: 0.15rem; text-align: center;">
                                                    <?= htmlspecialchars($doc['linea_nombre'] ?? 'General') ?>
                                                </span>
                                                <small style="color: var(--color-secundario); font-weight: 700; display: block; font-size: 0.7rem; text-align: center;">
                                                    <?= htmlspecialchars($doc['nivel_academico'] ?? 'Pregrado') ?><?= (($doc['nivel_academico'] ?? 'Pregrado') === 'Pregrado' && !empty($doc['trayecto'])) ? ' • ' . htmlspecialchars($doc['trayecto']) : '' ?>
                                                </small>
                                            </td>
                                            <td><strong><?= $doc['anio_publicacion'] ?></strong></td>
                                            <td style="text-align: center;">
                                                <div style="display: flex; gap: 0.25rem; justify-content: center;">
                                                    <a href="?ruta=detalles-pst&id=<?= $doc['id'] ?>" class="btn-outline-repo" style="text-decoration: none; padding: 0.3rem 0.5rem; font-size: 0.75rem;" title="Ver Ficha">
                                                        Ver
                                                    </a>
                                                    <button type="button" class="btn-outline-repo" style="padding: 0.3rem 0.5rem; font-size: 0.75rem; cursor: pointer;" title="Generar Cita" onclick="abrirModalCita(<?= htmlspecialchars(json_encode($doc['titulo'])) ?>, <?= htmlspecialchars(json_encode($doc['autores_nombres'] ?? 'Autores Varios')) ?>, <?= $doc['anio_publicacion'] ?>)">
                                                        <i class="ph ph-quotes"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginador de la Vista Catálogo -->
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

                </section>
            </div>

            <!-- Columna Derecha: Sidebar Panel de Filtrado -->
            <aside class="pst-sidebar-column">
                <form id="pstFilterForm" method="GET" action="" class="pst-sidebar-panel">
                    <input type="hidden" name="ruta" value="repositorio">
                    <input type="hidden" name="anio" id="filterAnioInput" value="<?= htmlspecialchars($filtros['anio'] ?? '') ?>">
                    
                    <h3><i class="ph ph-funnel"></i> Filtrar Recursos</h3>
                    
                    <!-- Mostrar carrera como informativa (fija) -->
                    <div class="filter-group">
                        <label>Programa Académico</label>
                        <select disabled>
                            <option>PNF en Informática</option>
                        </select>
                    </div>

                    <!-- Filtro por Tiempo: Histograma por Año -->
                    <div class="filter-group">
                        <label><i class="ph ph-chart-bar"></i> Distribución por Año</label>
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
                                    <span class="histogram-year-label"><?= substr((string)$year, 2) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (!empty($filtros['anio'])): ?>
                            <button type="button" class="btn-reset-year" onclick="selectYear('')">
                                <i class="ph ph-x-circle"></i> Quitar año (<?= $filtros['anio'] ?>)
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- Selector de Nivel Académico -->
                    <div class="filter-group">
                        <label for="nivel_academico">Nivel Académico</label>
                        <select name="nivel_academico" id="nivel_academico_filter" onchange="this.form.submit()">
                            <option value="">Todos los Niveles</option>
                            <option value="Pregrado" <?= (($filtros['nivel_academico'] ?? '') === 'Pregrado') ? 'selected' : '' ?>>Pregrado</option>
                            <option value="Especialización" <?= (($filtros['nivel_academico'] ?? '') === 'Especialización') ? 'selected' : '' ?>>Especialización</option>
                            <option value="Maestría" <?= (($filtros['nivel_academico'] ?? '') === 'Maestría') ? 'selected' : '' ?>>Maestría</option>
                            <option value="Doctorado" <?= (($filtros['nivel_academico'] ?? '') === 'Doctorado') ? 'selected' : '' ?>>Doctorado</option>
                        </select>
                    </div>

                    <!-- Selector de Trayecto -->
                    <div class="filter-group">
                        <label for="trayecto">Trayecto del PNF (Pregrado)</label>
                        <select name="trayecto" id="trayecto_filter" onchange="this.form.submit()">
                            <option value="">Todos los Trayectos</option>
                            <option value="Trayecto I" <?= (($filtros['trayecto'] ?? '') === 'Trayecto I') ? 'selected' : '' ?>>Trayecto I</option>
                            <option value="Trayecto II" <?= (($filtros['trayecto'] ?? '') === 'Trayecto II') ? 'selected' : '' ?>>Trayecto II</option>
                            <option value="Trayecto III" <?= (($filtros['trayecto'] ?? '') === 'Trayecto III') ? 'selected' : '' ?>>Trayecto III</option>
                            <option value="Trayecto IV" <?= (($filtros['trayecto'] ?? '') === 'Trayecto IV') ? 'selected' : '' ?>>Trayecto IV</option>
                        </select>
                    </div>

                    <!-- Selector de Línea de Investigación -->
                    <div class="filter-group">
                        <label for="linea_id">Línea de Investigación</label>
                        <select name="linea_id" id="linea_id">
                            <option value="">Todas las Líneas</option>
                            <?php foreach ($lineas as $linea): ?>
                                <option value="<?= $linea['id'] ?>" <?= ($filtros['linea_id'] == $linea['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($linea['nombre'] ?? '') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Selector de Dimensión Operativa Dependiente -->
                    <div class="filter-group">
                        <label for="dimension_id">Dimensión Operativa</label>
                        <select name="dimension_id" id="dimension_id" disabled>
                            <option value="">Todas las Dimensiones</option>
                        </select>
                    </div>

                    <a href="?ruta=repositorio" class="btn-clear-filters">Limpiar Filtros</a>
                </form>
            </aside>

        </div>

    </div>
</div>

<script>
// Función para seleccionar/deseleccionar un año en el histograma del sidebar
function selectYear(year) {
    const input = document.getElementById('filterAnioInput');
    if (input) {
        input.value = year;
        document.getElementById('pstFilterForm').submit();
    }
}
// JSON con todas las dimensiones operativas del sistema
const todasDimensiones = <?= json_encode($dimensiones) ?>;
const activeDimensionId = <?= json_encode($filtros['dimension_id'] ?? '') ?>;

// Función para actualizar dinámicamente las opciones del selector de dimensión
function updateDimensionOptions(selectedLineaId) {
    const dimSelect = document.getElementById('dimension_id');
    
    // Resetear opciones
    dimSelect.innerHTML = '<option value="">Todas las Dimensiones</option>';
    
    if (!selectedLineaId) {
        dimSelect.disabled = true;
        return;
    }
    
    dimSelect.disabled = false;
    
    // Filtrar dimensiones que pertenecen a la línea seleccionada
    const filtered = todasDimensiones.filter(d => d.id_linea == selectedLineaId);
    
    filtered.forEach(d => {
        const opt = document.createElement('option');
        opt.value = d.id;
        opt.textContent = d.nombre;
        if (d.id == activeDimensionId) {
            opt.selected = true;
        }
        dimSelect.appendChild(opt);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const lineaSelect = document.getElementById('linea_id');
    
    // Inicializar el selector al cargar
    updateDimensionOptions(lineaSelect.value);
    
    // Escuchar el cambio en el selector de Línea
    lineaSelect.addEventListener('change', (e) => {
        updateDimensionOptions(e.target.value);
        e.target.form.submit(); // Submit automático
    });
    
    // Escuchar el cambio en el selector de Dimensión
    const dimSelect = document.getElementById('dimension_id');
    if (dimSelect) {
        dimSelect.addEventListener('change', () => {
            dimSelect.form.submit();
        });
    }
});

// MODAL DE GENERACIÓN DE CITAS ACADÉMICAS DINÁMICAS
const configuracionesCitas = <?= json_encode(ConfigService::get('citas.estilos', [])) ?>;

function abrirModalCita(titulo, autores, anio) {
    const listEl = document.getElementById('listaCitasDinamicas');
    if (!listEl) return;
    
    listEl.innerHTML = '';
    
    const mockData = {
        '{autores}': autores || 'Autores Varios',
        '{anio}': anio || 's.f.',
        '{titulo}': titulo || 'Proyecto Socio-Tecnológico',
        '{carrera}': 'PNF en Informática'
    };
    
    let count = 0;
    for (const [slug, item] of Object.entries(configuracionesCitas)) {
        if (!item.activo) continue;
        count++;
        
        let textoCita = item.plantilla || '';
        for (const [k, v] of Object.entries(mockData)) {
            textoCita = textoCita.replaceAll(k, v);
        }
        
        const boxId = 'cita_txt_' + slug;
        const cardHtml = `
            <div style="margin-bottom: 0.85rem;">
                <strong style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: var(--color-secundario); text-transform: uppercase; margin-bottom: 0.25rem;">
                    <span>${item.nombre || slug}</span>
                    <button type="button" onclick="copiarCitaText('${boxId}', this)" style="padding: 0.2rem 0.5rem; font-size: 0.7rem; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 3px; cursor: pointer; font-weight: 700;">
                        <i class="ph ph-copy"></i> Copiar
                    </button>
                </strong>
                <div id="${boxId}" style="background: #fafbfe; border: 1px solid rgba(169, 168, 166, 0.15); padding: 0.5rem 0.65rem; border-radius: 4px; font-size: 0.8rem; color: var(--texto-normal); font-family: monospace; line-height: 1.4;">${textoCita}</div>
            </div>
        `;
        listEl.insertAdjacentHTML('beforeend', cardHtml);
    }
    
    if (count === 0) {
        listEl.innerHTML = '<p style="font-size: 0.8rem; color: var(--texto-silenciado);">No hay formatos de cita activos en la configuración.</p>';
    }
    
    document.getElementById('modalCitasContainer').style.display = 'flex';
}

function cerrarModalCitas() {
    document.getElementById('modalCitasContainer').style.display = 'none';
}

function copiarCitaText(elementId, btn) {
    const el = document.getElementById(elementId);
    if (!el) return;
    const text = el.textContent;

    const mostrarExito = () => {
        const origText = btn.innerHTML;
        btn.innerHTML = '<i class="ph ph-check"></i> ¡Copiado!';
        setTimeout(() => { btn.innerHTML = origText; }, 2000);
    };

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(mostrarExito).catch(() => {
            fallbackCopiarTexto(text, mostrarExito);
        });
    } else {
        fallbackCopiarTexto(text, mostrarExito);
    }
}

function fallbackCopiarTexto(text, onSuccess) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.left = "-999999px";
    textArea.style.top = "-999999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        const successful = document.execCommand('copy');
        if (successful && typeof onSuccess === 'function') {
            onSuccess();
        }
    } catch (err) {
        console.error('Error al copiar texto via fallback: ', err);
    }
    document.body.removeChild(textArea);
}
</script>

<!-- Modal Citas Académicas -->
<div id="modalCitasContainer" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 34, 68, 0.7); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center;">
    <div style="background: var(--bg-card, #ffffff); border: 1px solid rgba(169, 168, 166, 0.2); border-radius: 8px; width: 90%; max-width: 540px; padding: 1.25rem; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(169, 168, 166, 0.2); padding-bottom: 0.5rem; margin-bottom: 1rem;">
            <h3 style="font-size: 1rem; font-weight: 800; color: var(--texto-titulos); margin: 0; display: flex; align-items: center; gap: 0.35rem;">
                <i class="ph ph-quotes" style="color: var(--color-terciario);"></i> Formato de Cita Académica
            </h3>
            <button type="button" onclick="cerrarModalCitas()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: var(--texto-silenciado);">&times;</button>
        </div>
        
        <div id="listaCitasDinamicas" style="max-height: 380px; overflow-y: auto;">
            <!-- Contenido inyectado dinámicamente -->
        </div>

        <div style="text-align: right; border-top: 1px solid rgba(169, 168, 166, 0.15); padding-top: 0.5rem;">
            <button type="button" onclick="cerrarModalCitas()" class="btn-clear-filters" style="display: inline-block; width: auto; padding: 0.35rem 1rem;">Cerrar</button>
        </div>
    </div>
</div>