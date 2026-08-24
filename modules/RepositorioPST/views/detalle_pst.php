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
                
                <!-- Sección de Proyecto Destacado (Compacta y sin degradado) -->
                <?php if (!empty($documentos) && $pagination['current_page'] == 1): 
                    $destacado = $documentos[0];
                ?>
                    <article class="pst-hero-card">
                        <div class="pst-hero-content">
                            <div style="display: flex; gap: 0.4rem; align-items: center; margin-bottom: 0.35rem;">
                                <span class="pst-badge-soft" style="font-size: 0.75rem; background-color: var(--color-terciario); color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700;">ÚLTIMO REGISTRO</span>
                                <span class="pst-badge-soft" style="background-color: rgba(112, 144, 203, 0.15); color: var(--color-secundario);"><?= htmlspecialchars($destacado['nivel_academico'] ?? 'Pregrado') ?></span>
                            </div>
                            <h3><?= htmlspecialchars($destacado['titulo']) ?></h3>
                            <p><?= htmlspecialchars($destacado['resumen'] ?? 'Proyecto Socio-Tecnológico desarrollado en la localidad.') ?></p>
                            <div style="font-size: 0.8rem; color: var(--texto-silenciado); line-height: 1.3; margin-bottom: 0.5rem;">
                                <strong>Autores:</strong> <?= htmlspecialchars($destacado['autores_nombres'] ?? 'No registrados') ?><br>
                                <strong>Comunidad:</strong> <?= htmlspecialchars($destacado['comunidad_beneficiada'] ?? 'No registrada') ?>
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="?ruta=detalles-pst&id=<?= $destacado['id'] ?>" class="btn-outline-repo" style="text-decoration: none; padding: 0.4rem 1rem; border-radius: 4px; font-size: 0.8rem;">
                                    Ver Ficha Completa
                                </a>
                                <?php if (!empty($destacado['archivo_pdf'])): ?>
                                    <a href="<?= htmlspecialchars($destacado['archivo_pdf']) ?>" target="_blank" class="btn" style="border-radius: 4px; padding: 0.4rem 1rem; text-decoration: none; text-align: center; font-size: 0.8rem; height: auto; display: inline-flex; align-items: center;">
                                        Descargar PDF
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endif; ?>

                <!-- Listado de Investigaciones Indexadas -->
                <section class="pst-table-wrapper">
                    <h3 class="pst-table-title">Banco de Proyectos (Mostrando <?= count($documentos) ?> de <?= $pagination['total_items'] ?>)</h3>
                    
                    <div class="table-responsive">
                        <table class="pst-table">
                            <thead>
                                <tr>
                                    <th style="width: 50%;">TÍTULO DEL PROYECTO</th>
                                    <th style="width: 25%;">AUTORES</th>
                                    <th style="width: 15%;">LÍNEA / DIMENSIÓN</th>
                                    <th style="width: 5%;">AÑO</th>
                                    <th style="text-align: center; width: 5%;">ACCIÓN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($documentos)): ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 2rem; color: var(--texto-silenciado);">
                                            No se encontraron proyectos con los filtros de catalogación seleccionados.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($documentos as $doc): ?>
                                        <tr>
                                            <td class="pst-td-title">
                                                <strong><?= htmlspecialchars($doc['titulo']) ?></strong>
                                                <?php if (!empty($doc['resumen'])): ?>
                                                    <div style="font-size: 0.75rem; color: var(--texto-silenciado); margin-top: 0.15rem;">
                                                        <?= htmlspecialchars(substr($doc['resumen'], 0, 110)) ?>...
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($doc['autores_nombres'] ?? 'No registrados') ?></td>
                                            <td>
                                                <span class="pst-badge-soft badge-edu" style="display: block; margin-bottom: 0.15rem; text-align: center;">
                                                    <?= htmlspecialchars($doc['linea_nombre'] ?? 'General') ?>
                                                </span>
                                                <?php if (!empty($doc['dimension_nombre'])): ?>
                                                    <small style="color: var(--texto-silenciado); display: block; font-size: 0.7rem; text-align: center;">
                                                        <?= htmlspecialchars($doc['dimension_nombre']) ?>
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            <td><strong><?= $doc['anio_publicacion'] ?></strong></td>
                                            <td style="text-align: center;">
                                                <a href="?ruta=detalles-pst&id=<?= $doc['id'] ?>" class="btn-outline-repo" style="text-decoration: none; padding: 0.3rem 0.6rem; display: inline-block;">
                                                    Ver
                                                </a>
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
                <form method="GET" action="" class="pst-sidebar-panel">
                    <input type="hidden" name="ruta" value="repositorio">
                    
                    <h3><i class="ph ph-funnel"></i> Filtrar Recursos</h3>
                    
                    <!-- Mostrar carrera como informativa (fija) -->
                    <div class="filter-group">
                        <label>Programa Académico</label>
                        <select disabled>
                            <option>PNF en Informática</option>
                        </select>
                    </div>

                    <!-- Selector de Línea de Investigación -->
                    <div class="filter-group">
                        <label for="linea_id">Línea de Investigación</label>
                        <select name="linea_id" id="linea_id">
                            <option value="">Todas las Líneas</option>
                            <?php foreach ($lineas as $linea): ?>
                                <option value="<?= $linea['id'] ?>" <?= ($filtros['linea_id'] == $linea['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($linea['nombre']) ?>
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
    dimSelect.addEventListener('change', () => {
        dimSelect.form.submit(); // Submit automático
    });
});
</script>