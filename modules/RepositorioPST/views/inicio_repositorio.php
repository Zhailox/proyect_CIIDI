<?php
// modules/RepositorioPST/views/inicio_repositorio.php
require_once CORE_PATH . 'Security/Auth.php';
require_once __DIR__ . '/../services/ConfigService.php';

$nivelUsuario = Auth::check() ? (int)Auth::usuario()['nivel'] : -1;
$tCounts = $trayectoCounts ?? [1 => 0, 2 => 0, 3 => 0, 4 => 0];
$lineasLista = $lineas ?? [];
$lineasConConteoLista = $lineasConConteo ?? [];
$dimensionesLista = $dimensiones ?? [];
$nivelesAcademicosLista = $nivelesAcademicos ?? ['Pregrado', 'Especialización', 'Maestría', 'Doctorado'];
$trayectosLista = $trayectosList ?? ['1', '2', '3', '4'];
$aniosHist = $anioCounts ?? [2024 => 4, 2023 => 3, 2022 => 2, 2021 => 1];
$modoCargaActual = $modoCarga ?? 'paginador';
$pag = $pagination ?? ['current_page' => 1, 'total_pages' => 1, 'total_items' => 0, 'limit' => 10];
$filtrosActivos = $filtros ?? [];
?>

<style>
/* PADDING GLOBAL Y ESTILOS PESO PLUMA (estilo.md) */
.ag-pst-view-container {
    padding: 1.5rem 2.2rem 3.5rem 2.2rem;
    width: 100%;
    box-sizing: border-box;
}

@media (max-width: 768px) {
    .ag-pst-view-container {
        padding: 1rem;
    }
}

.ag-pst-hero {
    position: relative;
    border-radius: var(--radius-md, 16px);
    overflow: hidden;
    background: linear-gradient(135deg, rgba(80, 89, 132, 0.95) 0%, rgba(30, 41, 59, 0.98) 100%);
    color: #ffffff;
    padding: 3rem 2.2rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.06);
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.ag-pst-canvas {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    pointer-events: none;
}

.ag-pst-hero-inner {
    position: relative;
    z-index: 2;
    max-width: 820px;
    margin: 0 auto;
    text-align: center;
}

.ag-pst-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(112, 144, 203, 0.25);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: var(--radius-sm, 8px);
    padding: 5px 14px;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.6px;
    text-transform: uppercase;
    margin-bottom: 1.2rem;
}

.ag-pst-hero-title {
    font-size: 2.2rem;
    font-weight: 800;
    line-height: 1.25;
    margin-bottom: 0.8rem;
    color: #ffffff;
}

.ag-pst-hero-desc {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.85);
    line-height: 1.6;
    margin-bottom: 2rem;
}

.ag-pst-search-box {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(14px);
    border-radius: var(--radius-md, 16px);
    padding: 6px;
    display: flex;
    align-items: center;
    box-shadow: 0 12px 30px rgba(0,0,0,0.18);
    border: 1px solid rgba(255,255,255,0.4);
}

.ag-pst-search-box input {
    border: none;
    outline: none;
    padding: 12px 16px;
    font-size: 0.95rem;
    color: var(--texto-titulos, #1E293B);
    width: 100%;
    background: transparent;
    font-weight: 500;
}

.ag-pst-search-btn {
    background: var(--color-secundario, rgb(80, 89, 132));
    color: #ffffff;
    border: none;
    padding: 12px 24px;
    border-radius: var(--radius-sm, 8px);
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    white-space: nowrap;
}

.ag-pst-search-btn:hover {
    background: #3C456A;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(80, 89, 132, 0.3);
}

mark, .highlight-match {
    background-color: rgba(112, 144, 203, 0.28) !important;
    color: var(--color-secundario, rgb(80, 89, 132)) !important;
    border-radius: 4px;
    padding: 2px 6px;
    font-weight: 700;
}

/* Layout Grid: Columna Principal + Sidebar de Filtros Sticky */
.ag-pst-layout-grid {
    display: grid;
    grid-template-columns: 1fr 310px;
    gap: 1.8rem;
    align-items: start;
}

@media (max-width: 992px) {
    .ag-pst-layout-grid {
        grid-template-columns: 1fr;
    }
}

/* Tarjetas de Métricas Peso Pluma */
.ag-pst-stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1.2rem;
    margin-bottom: 1.8rem;
}

.ag-pst-stat-card {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(80, 89, 132, 0.15);
    border-radius: var(--radius-md, 16px);
    padding: 1.25rem 1.4rem;
    display: flex;
    align-items: center;
    gap: 1.1rem;
    box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.ag-pst-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 16px 36px rgba(18, 26, 62, 0.07);
}

.ag-pst-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-sm, 8px);
    background: rgba(112, 144, 203, 0.15);
    color: var(--color-secundario, rgb(80, 89, 132));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
}

.ag-pst-stat-val {
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--texto-titulos, #1E293B);
    line-height: 1;
}

.ag-pst-stat-lbl {
    font-size: 0.8rem;
    color: var(--texto-silenciado, #64748B);
    font-weight: 600;
    margin-top: 4px;
}

/* Grilla de Trayectos Académicos */
.ag-pst-trayectos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
    gap: 1.2rem;
    margin-bottom: 1.8rem;
}

.ag-pst-trayecto-card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(80, 89, 132, 0.15);
    border-radius: var(--radius-md, 16px);
    padding: 1.25rem;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.ag-pst-trayecto-card:hover {
    border-color: var(--color-terciario, rgb(112, 144, 203));
    transform: translateY(-3px);
    box-shadow: 0 12px 28px rgba(80, 89, 132, 0.1);
}

.ag-pst-trayecto-card .t-num {
    font-size: 0.72rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--color-secundario, rgb(80, 89, 132));
    background: rgba(80, 89, 132, 0.08);
    padding: 3px 8px;
    border-radius: var(--radius-sm, 8px);
    display: inline-block;
    margin-bottom: 0.6rem;
}

.ag-pst-trayecto-card h4 {
    font-size: 0.98rem;
    font-weight: 800;
    color: var(--texto-titulos, #1E293B);
    margin: 0 0 0.3rem 0;
}

.ag-pst-trayecto-card p {
    font-size: 0.8rem;
    color: var(--texto-silenciado, #64748B);
    line-height: 1.4;
    margin: 0 0 0.8rem 0;
}

.ag-pst-trayecto-card .t-count {
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--texto-titulos, #1E293B);
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-top: 1px solid rgba(80, 89, 132, 0.1);
    padding-top: 0.6rem;
}

/* Sidebar STICKY de Filtrado Lateral Completo */
.ag-pst-filter-sidebar {
    position: sticky;
    top: 90px;
    background: rgba(255, 255, 255, 0.94);
    backdrop-filter: blur(14px);
    border: 1px solid rgba(80, 89, 132, 0.16);
    border-radius: var(--radius-md, 16px);
    padding: 1.4rem;
    box-shadow: 0 12px 32px rgba(18, 26, 62, 0.05);
}

.ag-filter-group {
    margin-bottom: 1.2rem;
}

.ag-filter-group label {
    display: block;
    font-size: 0.8rem;
    font-weight: 800;
    color: var(--texto-titulos, #1E293B);
    margin-bottom: 0.4rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.ag-filter-select {
    width: 100%;
    padding: 9px 12px;
    border-radius: var(--radius-sm, 8px);
    border: 1px solid rgba(80, 89, 132, 0.2);
    background: #ffffff;
    font-weight: 600;
    font-size: 0.88rem;
    color: var(--texto-titulos, #1E293B);
    outline: none;
    transition: all 0.25s ease;
}

.ag-filter-select:focus {
    border-color: var(--color-terciario, rgb(112, 144, 203));
    box-shadow: 0 0 0 3px rgba(112, 144, 203, 0.2);
}

/* HISTOGRAMA DE TIEMPO INTERACTIVO AL ESTILO OPENALEX */
.ag-histogram-container {
    display: flex;
    align-items: flex-end;
    gap: 4px;
    height: 70px;
    padding: 6px 0;
    margin-top: 6px;
    border-bottom: 1px solid rgba(80, 89, 132, 0.15);
}

.ag-histogram-col {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100%;
    justify-content: flex-end;
    cursor: pointer;
}

.ag-histogram-bar-wrap {
    width: 100%;
    background: rgba(112, 144, 203, 0.15);
    border-radius: 4px 4px 0 0;
    transition: all 0.2s ease;
    position: relative;
}

.ag-histogram-col.active .ag-histogram-bar-wrap,
.ag-histogram-bar-wrap:hover {
    background: var(--color-secundario, rgb(80, 89, 132)) !important;
}

.ag-histogram-year {
    font-size: 0.68rem;
    font-weight: 700;
    color: var(--texto-silenciado, #64748B);
    margin-top: 4px;
}

.ag-btn-reset-filters {
    display: block;
    width: 100%;
    text-align: center;
    padding: 9px;
    border-radius: var(--radius-sm, 8px);
    border: 1px solid rgba(80, 89, 132, 0.2);
    background: transparent;
    color: var(--texto-silenciado, #64748B);
    font-weight: 700;
    font-size: 0.82rem;
    text-decoration: none;
    transition: all 0.2s ease;
    margin-top: 1rem;
}

.ag-btn-reset-filters:hover {
    background: rgba(80, 89, 132, 0.08);
    color: var(--texto-titulos, #1E293B);
}

/* Tabla de Publicaciones */
.ag-pst-table-wrapper {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(80, 89, 132, 0.15);
    border-radius: var(--radius-md, 16px);
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04);
}

.ag-pst-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.88rem;
}

.ag-pst-table th {
    background: rgba(80, 89, 132, 0.06);
    padding: 14px 18px;
    text-align: left;
    font-weight: 800;
    color: var(--texto-titulos, #1E293B);
    border-bottom: 1px solid rgba(80, 89, 132, 0.12);
}

.ag-pst-table td {
    padding: 16px 18px;
    border-bottom: 1px solid rgba(80, 89, 132, 0.08);
    color: var(--texto-titulos, #1E293B);
    vertical-align: middle;
}

.ag-pst-table tr:last-child td {
    border-bottom: none;
}

.ag-doc-link {
    font-weight: 700;
    color: var(--color-secundario, rgb(80, 89, 132));
    text-decoration: none;
    display: block;
    line-height: 1.4;
    font-size: 0.95rem;
    transition: color 0.2s ease;
}

.ag-doc-link:hover {
    color: var(--color-terciario, rgb(112, 144, 203));
    text-decoration: underline;
}

.ag-badge-trayecto {
    background: rgba(80, 89, 132, 0.08);
    color: var(--color-secundario, rgb(80, 89, 132));
    font-weight: 700;
    font-size: 0.76rem;
    padding: 3px 8px;
    border-radius: var(--radius-sm, 6px);
    display: inline-block;
}

/* PAGINADOR NUMÉRICO CLÁSICO */
.ag-pagination-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.2rem;
    background: rgba(255, 255, 255, 0.85);
    border-top: 1px solid rgba(80, 89, 132, 0.1);
    flex-wrap: wrap;
    gap: 0.8rem;
}

.ag-pagination-pages {
    display: flex;
    align-items: center;
    gap: 6px;
}

.ag-page-link {
    padding: 6px 12px;
    border-radius: var(--radius-sm, 6px);
    border: 1px solid rgba(80, 89, 132, 0.2);
    background: #ffffff;
    color: var(--texto-titulos, #1E293B);
    text-decoration: none;
    font-weight: 700;
    font-size: 0.82rem;
    transition: all 0.2s ease;
}

.ag-page-link.active {
    background: var(--color-secundario, rgb(80, 89, 132));
    color: #ffffff;
    border-color: var(--color-secundario, rgb(80, 89, 132));
}

.ag-page-link:hover:not(.active) {
    background: rgba(80, 89, 132, 0.08);
}
</style>

<div class="ag-pst-view-container">
    
    <!-- HERO PRINCIPAL WEIGHTLESS CON CANVAS FLOTANTE -->
    <div class="ag-pst-hero">
        <canvas id="pst-nodes-canvas" class="ag-pst-canvas"></canvas>
        
        <div class="ag-pst-hero-inner">
            <div class="ag-pst-pill">
                <i class="ph-bold ph-book-bookmark"></i> Repositorio PST UPTTMBI
            </div>
            
            <h1 class="ag-pst-hero-title">
                Proyectos Socio-Tecnológicos (PST)
            </h1>
            
            <p class="ag-pst-hero-desc">
                Plataforma de gestión, visibilidad e impacto de la producción científica desarrollada por la comunidad del PNF en Informática.
            </p>
            
            <form action="index.php" method="GET" class="ag-pst-search-box">
                <input type="hidden" name="ruta" value="buscador">
                <i class="ph-bold ph-magnifying-glass" style="font-size: 1.25rem; color: #64748b; margin-left: 12px;"></i>
                <input type="text" name="q" placeholder="Buscar por título, autor, palabras clave o comunidad..." required>
                <button type="submit" class="ag-pst-search-btn">
                    Buscar PST <i class="ph-bold ph-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- METRICAS DESTACADAS REEVACUADAS DESDE POSTGRESQL -->
    <div class="ag-pst-stats-row">
        <div class="ag-pst-stat-card">
            <div class="ag-pst-stat-icon"><i class="ph-bold ph-files"></i></div>
            <div>
                <div class="ag-pst-stat-val"><?= number_format($totalPST) ?></div>
                <div class="ag-pst-stat-lbl">Proyectos Indexados</div>
            </div>
        </div>

        <div class="ag-pst-stat-card">
            <div class="ag-pst-stat-icon"><i class="ph-bold ph-users-three"></i></div>
            <div>
                <div class="ag-pst-stat-val"><?= number_format($totalAutores) ?></div>
                <div class="ag-pst-stat-lbl">Autores Estudiantes</div>
            </div>
        </div>

        <div class="ag-pst-stat-card">
            <div class="ag-pst-stat-icon"><i class="ph-bold ph-buildings"></i></div>
            <div>
                <div class="ag-pst-stat-val"><?= number_format($totalComunidades) ?></div>
                <div class="ag-pst-stat-lbl">Comunidades Atendidas</div>
            </div>
        </div>
    </div>

    <!-- ESTRUCTURA PRINCIPAL: CONTENIDO + SIDEBAR STICKY DE FILTRADO -->
    <div class="ag-pst-layout-grid">
        
        <!-- COLUMNA IZQUIERDA: CONTENIDO Y TABLA DE PUBLICACIONES -->
        <div>
            <!-- EXPLORACIÓN DINÁMICA POR LÍNEAS DE INVESTIGACIÓN (BD) -->
            <div style="font-size: 1.1rem; font-weight: 800; color: var(--texto-titulos, #1E293B); margin-bottom: 1rem;">
                Líneas de Investigación Institucionales
            </div>

            <div class="ag-pst-trayectos-grid">
                <?php if (!empty($lineasConConteoLista)): ?>
                    <?php foreach ($lineasConConteoLista as $lineaCard): ?>
                        <a href="?ruta=repositorio&linea_id=<?= $lineaCard['id'] ?>" class="ag-pst-trayecto-card">
                            <div>
                                <h4 style="margin-top: 0.2rem;"><?= htmlspecialchars($lineaCard['nombre']) ?></h4>
                                <p><?= htmlspecialchars($lineaCard['descripcion'] ?: 'Línea de investigación institucional del PNF.') ?></p>
                            </div>
                            <div class="t-count">
                                <span><?= $lineaCard['total'] ?> Proyectos</span>
                                <i class="ph-bold ph-arrow-right"></i>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; color: var(--texto-silenciado); font-size: 0.88rem;">
                        No hay líneas de investigación registradas en la base de datos.
                    </div>
                <?php endif; ?>
            </div>

            <!-- TABLA PRINCIPAL DE BANCO DE PROYECTOS -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 10px;">
                <span style="font-size: 1.1rem; font-weight: 800; color: var(--texto-titulos, #1E293B);">Banco General de Proyectos</span>
                
                <!-- ORDENAMIENTO DE PUBLICACIONES (RECIENTES VS ANTIGUOS) -->
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 0.8rem; font-weight: 700; color: var(--texto-silenciado, #64748B);">Ordenar por:</span>
                    <select onchange="cambiarOrdenamiento(this.value)" style="font-size: 0.82rem; font-weight: 700; padding: 5px 10px; border-radius: var(--radius-sm, 6px); border: 1px solid rgba(80,89,132,0.2); background: #ffffff; cursor: pointer;">
                        <option value="desc" <?= ($filtrosActivos['orden'] ?? 'desc') === 'desc' ? 'selected' : '' ?>>Más Recientes Primero</option>
                        <option value="asc" <?= ($filtrosActivos['orden'] ?? 'desc') === 'asc' ? 'selected' : '' ?>>Más Antiguos Primero</option>
                    </select>
                </div>
            </div>

            <div class="ag-pst-table-wrapper">
                <table class="ag-pst-table">
                    <thead>
                        <tr>
                            <th style="width: 50%;">Proyecto / Título</th>
                            <th>Nivel / Trayecto</th>
                            <th>Comunidad</th>
                            <th>Año</th>
                            <th style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="agPstTableBody">
                        <?php if (empty($documentos)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 2.5rem; color: var(--texto-silenciado, #64748B);">
                                    No se encontraron publicaciones con los criterios seleccionados.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($documentos as $doc): ?>
                                <tr>
                                    <td>
                                        <a href="?ruta=detalles-pst&id=<?= $doc['id'] ?>" class="ag-doc-link">
                                            <?= htmlspecialchars($doc['titulo']) ?>
                                        </a>
                                        <div style="font-size: 0.78rem; color: var(--texto-silenciado, #64748B); margin-top: 4px;">
                                            Autores: <?= htmlspecialchars($doc['autores_nombres'] ?? 'No especificados') ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="ag-badge-trayecto"><?= htmlspecialchars($doc['nivel_academico'] ?? 'Pregrado') ?></span>
                                        <?php if (!empty($doc['trayecto'])): ?>
                                            <span style="font-size: 0.76rem; font-weight: 700; color: var(--color-secundario); display: block; margin-top: 3px;">
                                                Trayecto <?= htmlspecialchars($doc['trayecto']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="color: var(--texto-titulos, #1E293B); font-weight: 600; font-size: 0.85rem;">
                                        <?= htmlspecialchars($doc['comunidad_beneficiada'] ?? 'Comunidad Unitaria') ?>
                                    </td>
                                    <td style="font-weight: 700; font-size: 0.88rem; color: var(--texto-titulos, #1E293B);">
                                        <?= htmlspecialchars($doc['anio_publicacion'] ?? '') ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <div style="display: flex; gap: 6px; justify-content: center;">
                                            <a href="?ruta=detalles-pst&id=<?= $doc['id'] ?>" class="ag-page-link" style="padding: 4px 8px;" title="Ver Ficha">Ver</a>
                                            <button type="button" class="ag-page-link" style="padding: 4px 8px; cursor: pointer;" title="Citar" onclick="abrirModalCita(<?= htmlspecialchars(json_encode($doc['titulo'])) ?>, <?= htmlspecialchars(json_encode($doc['autores_nombres'] ?? 'Autores Varios')) ?>, <?= $doc['anio_publicacion'] ?>)"><i class="ph-bold ph-quotes"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- MODO DE NAVEGACIÓN DEDICADO: PAGINADOR VS LAZY LOADING -->
                <?php if ($modoCargaActual === 'paginador' && $pag['total_pages'] > 1): ?>
                    <div class="ag-pagination-bar">
                        <span style="font-size: 0.82rem; font-weight: 700; color: var(--texto-silenciado, #64748B);">
                            Página <?= $pag['current_page'] ?> de <?= $pag['total_pages'] ?> (Total: <?= $pag['total_items'] ?> proyectos)
                        </span>
                        
                        <div class="ag-pagination-pages">
                            <?php for ($i = 1; $i <= $pag['total_pages']; $i++): ?>
                                <?php
                                $query = $_GET;
                                $query['page'] = $i;
                                $linkUrl = '?' . http_build_query($query);
                                ?>
                                <a href="<?= $linkUrl ?>" class="ag-page-link <?= $i === $pag['current_page'] ? 'active' : '' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- COLUMNA DERECHA: SIDEBAR STICKY DE FILTRADO COMPLETO -->
        <aside class="ag-pst-filter-sidebar">
            <h3 style="margin: 0 0 1.2rem 0; font-size: 1.05rem; font-weight: 800; color: var(--texto-titulos, #1E293B); display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-funnel" style="color: var(--color-terciario, rgb(112, 144, 203));"></i> Filtros de Búsqueda
            </h3>

            <form id="agPstFilterForm" action="index.php" method="GET">
                <input type="hidden" name="ruta" value="repositorio">
                <input type="hidden" name="orden" value="<?= htmlspecialchars($filtrosActivos['orden'] ?? 'desc') ?>">
                <input type="hidden" name="anio" id="agAnioInput" value="<?= htmlspecialchars($filtrosActivos['anio'] ?? '') ?>">

                <!-- Nivel Académico (Pregrado, Maestría, Doctorado, etc.) -->
                <div class="ag-filter-group">
                    <label>Nivel Académico</label>
                    <select name="nivel_academico" id="agNivelAcademicoSelect" class="ag-filter-select" onchange="toggleTrayectoByNivel(this.value); this.form.submit();">
                        <option value="">Todos los Niveles</option>
                        <?php foreach ($nivelesAcademicosLista as $nivelItem): ?>
                            <option value="<?= htmlspecialchars($nivelItem) ?>" <?= (($filtrosActivos['nivel_academico'] ?? '') === $nivelItem) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($nivelItem) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Trayecto del PNF (Solo activo si Nivel = Pregrado o no seleccionado) -->
                <div class="ag-filter-group" id="agTrayectoGroup">
                    <label>Trayecto (Solo Pregrado)</label>
                    <select name="trayecto" id="agTrayectoSelect" class="ag-filter-select" onchange="this.form.submit()">
                        <option value="">Todos los Trayectos</option>
                        <?php foreach ($trayectosLista as $tItem): ?>
                            <option value="<?= htmlspecialchars($tItem) ?>" <?= (($filtrosActivos['trayecto'] ?? '') == $tItem) ? 'selected' : '' ?>>
                                Trayecto <?= htmlspecialchars($tItem) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Histograma Interactivo por Año (Estilo OpenAlex) -->
                <div class="ag-filter-group">
                    <label><i class="ph-bold ph-chart-bar"></i> Distribución por Año</label>
                    <div class="ag-histogram-container">
                        <?php 
                        $maxCount = !empty($aniosHist) ? max($aniosHist) : 1;
                        if ($maxCount <= 0) $maxCount = 1;
                        foreach ($aniosHist as $year => $count): 
                            $percent = ($count / $maxCount) * 100;
                            $isActive = (isset($filtrosActivos['anio']) && (int)$filtrosActivos['anio'] === (int)$year);
                        ?>
                            <div class="ag-histogram-col <?= $isActive ? 'active' : '' ?>" 
                                 onclick="seleccionarAnioHistograma('<?= $year ?>')"
                                 title="<?= $year ?>: <?= $count ?> proyectos">
                                <div class="ag-histogram-bar-wrap" style="height: <?= max(8, $percent) ?>%;"></div>
                                <span class="ag-histogram-year"><?= substr((string)$year, 2) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Línea de Investigación -->
                <?php if (!empty($lineasLista)): ?>
                <div class="ag-filter-group">
                    <label>Línea de Investigación</label>
                    <select name="linea_id" id="agLineaSelect" class="ag-filter-select" onchange="actualizarDimensionesOperativas(this.value); this.form.submit();">
                        <option value="">Todas las Líneas</option>
                        <?php foreach ($lineasLista as $linea): ?>
                            <option value="<?= $linea['id'] ?>" <?= (($filtrosActivos['linea_id'] ?? null) == $linea['id']) ? 'selected' : '' ?>><?= htmlspecialchars($linea['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

                <!-- Dimensión Operativa -->
                <div class="ag-filter-group">
                    <label>Dimensión Operativa</label>
                    <select name="dimension_id" id="agDimensionSelect" class="ag-filter-select" onchange="this.form.submit()">
                        <option value="">Todas las Dimensiones</option>
                    </select>
                </div>

                <a href="?ruta=repositorio" class="ag-btn-reset-filters">Limpiar Filtros</a>
            </form>
        </aside>

    </div>

</div>

<!-- SCRIPT DE INTERACTIVIDAD & LAZY LOADING DINÁMICO -->
<script>
let modoCarga = <?= json_encode($modoCargaActual) ?>;
let currentPagePst = <?= json_encode((int)$pag['current_page']) ?>;
let totalPagesPst = <?= json_encode((int)$pag['total_pages']) ?>;
let isFetchingPst = false;

const todasDimensiones = <?= json_encode($dimensionesLista) ?>;
const activeDimensionId = <?= json_encode($filtrosActivos['dimension_id'] ?? '') ?>;
const activeLineaId = <?= json_encode($filtrosActivos['linea_id'] ?? '') ?>;

document.addEventListener('DOMContentLoaded', () => {
    // Inicializar selector dependiente de Dimensiones Operativas
    actualizarDimensionesOperativas(activeLineaId);

    // Activar o desactivar Trayectos según Nivel Académico
    const nivelEl = document.getElementById('agNivelAcademicoSelect');
    if (nivelEl) toggleTrayectoByNivel(nivelEl.value);

    // Canvas animation
    const canvas = document.getElementById('pst-nodes-canvas');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let width = canvas.width = canvas.offsetWidth;
        let height = canvas.height = canvas.offsetHeight;
        
        window.addEventListener('resize', () => {
            if (!canvas) return;
            width = canvas.width = canvas.offsetWidth;
            height = canvas.height = canvas.offsetHeight;
        });

        const particles = [];
        const numParticles = 40;

        for (let i = 0; i < numParticles; i++) {
            particles.push({
                x: Math.random() * width,
                y: Math.random() * height,
                vx: (Math.random() - 0.5) * 0.5,
                vy: (Math.random() - 0.5) * 0.5,
                radius: Math.random() * 2 + 1.2
            });
        }

        function animate() {
            ctx.clearRect(0, 0, width, height);

            for (let i = 0; i < numParticles; i++) {
                const p = particles[i];
                p.x += p.vx;
                p.y += p.vy;

                if (p.x < 0 || p.x > width) p.vx *= -1;
                if (p.y < 0 || p.y > height) p.vy *= -1;

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(112, 144, 203, 0.75)';
                ctx.fill();

                for (let j = i + 1; j < numParticles; j++) {
                    const p2 = particles[j];
                    const dx = p.x - p2.x;
                    const dy = p.y - p2.y;
                    const dist = Math.sqrt(dx * dx + dy * dy);

                    if (dist < 110) {
                        ctx.beginPath();
                        ctx.moveTo(p.x, p.y);
                        ctx.lineTo(p2.x, p2.y);
                        ctx.strokeStyle = `rgba(112, 144, 203, ${0.4 * (1 - dist / 110)})`;
                        ctx.lineWidth = 0.8;
                        ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(animate);
        }
        animate();
    }

    // Lazy Loading scroll listener si está activo en la configuración
    if (modoCarga === 'lazy_loading') {
        window.addEventListener('scroll', () => {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 350) {
                cargarMasProyectosLazy();
            }
        });
    }
});

function toggleTrayectoByNivel(nivelVal) {
    const group = document.getElementById('agTrayectoGroup');
    const select = document.getElementById('agTrayectoSelect');
    if (!group || !select) return;

    if (nivelVal && nivelVal !== 'Pregrado') {
        select.value = '';
        select.disabled = true;
        group.style.opacity = '0.4';
    } else {
        select.disabled = false;
        group.style.opacity = '1';
    }
}

function actualizarDimensionesOperativas(lineaId) {
    const dimSelect = document.getElementById('agDimensionSelect');
    if (!dimSelect) return;
    
    dimSelect.innerHTML = '<option value="">Todas las Dimensiones</option>';
    
    if (!lineaId) {
        dimSelect.disabled = true;
        return;
    }
    
    dimSelect.disabled = false;
    const filtradas = todasDimensiones.filter(d => d.id_linea == lineaId);
    
    filtradas.forEach(d => {
        const opt = document.createElement('option');
        opt.value = d.id;
        opt.textContent = d.nombre;
        if (d.id == activeDimensionId) opt.selected = true;
        dimSelect.appendChild(opt);
    });
}

function seleccionarAnioHistograma(year) {
    const input = document.getElementById('agAnioInput');
    const form = document.getElementById('agPstFilterForm');
    if (input && form) {
        input.value = (input.value == year) ? '' : year;
        form.submit();
    }
}

function cambiarOrdenamiento(val) {
    const url = new URL(window.location.href);
    url.searchParams.set('orden', val);
    window.location.href = url.toString();
}

function cargarMasProyectosLazy() {
    if (isFetchingPst || currentPagePst >= totalPagesPst) return;
    
    isFetchingPst = true;
    const nextPage = currentPagePst + 1;
    const currentUrlParams = new URLSearchParams(window.location.search);
    currentUrlParams.set('page', nextPage);
    
    fetch('?' + currentUrlParams.toString())
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newRows = doc.querySelectorAll('#agPstTableBody tr');
            const tableBody = document.getElementById('agPstTableBody');
            if (newRows && newRows.length > 0 && tableBody) {
                newRows.forEach(row => tableBody.appendChild(row.cloneNode(true)));
            }
            currentPagePst = nextPage;
            isFetchingPst = false;
        })
        .catch(() => { isFetchingPst = false; });
}

// Modal Citas
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
                    <button type="button" onclick="copiarCitaText('${boxId}', this)" style="padding: 0.2rem 0.5rem; font-size: 0.7rem; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 4px; cursor: pointer; font-weight: 700;">
                        Copiar
                    </button>
                </strong>
                <div id="${boxId}" style="background: #fafbfe; border: 1px solid rgba(80, 89, 132, 0.15); padding: 0.5rem 0.65rem; border-radius: 6px; font-size: 0.8rem; font-family: monospace;">${textoCita}</div>
            </div>
        `;
        listEl.insertAdjacentHTML('beforeend', cardHtml);
    }
    document.getElementById('modalCitasContainer').style.display = 'flex';
}

function cerrarModalCitas() {
    document.getElementById('modalCitasContainer').style.display = 'none';
}

function copiarCitaText(elementId, btn) {
    const el = document.getElementById(elementId);
    if (!el) return;
    navigator.clipboard.writeText(el.textContent).then(() => {
        btn.innerText = '¡Copiado!';
        setTimeout(() => { btn.innerText = 'Copiar'; }, 2000);
    });
}
</script>

<!-- Modal Citas Académicas -->
<div id="modalCitasContainer" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: 12px; width: 90%; max-width: 520px; padding: 1.4rem; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(80,89,132,0.15); padding-bottom: 0.5rem; margin-bottom: 1rem;">
            <h3 style="font-size: 1rem; font-weight: 800; color: var(--texto-titulos); margin: 0;">Formato de Cita Académica</h3>
            <button type="button" onclick="cerrarModalCitas()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: var(--texto-silenciado);">&times;</button>
        </div>
        <div id="listaCitasDinamicas" style="max-height: 360px; overflow-y: auto;"></div>
        <div style="text-align: right; border-top: 1px solid rgba(80,89,132,0.15); padding-top: 0.6rem; margin-top: 0.8rem;">
            <button type="button" onclick="cerrarModalCitas()" class="ag-page-link">Cerrar</button>
        </div>
    </div>
</div>