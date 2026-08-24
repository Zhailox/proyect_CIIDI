<div class="main-content">
    
    <div class="repo-internal-header">
        <h1>Módulo de Repositorio Documental</h1>
        <p>Consola de control para el seguimiento de los Proyectos Socio-Tecnológicos (PST) del PNF en Informática.</p>
    </div>

    <!-- Indicadores Estadísticos Dinámicos -->
    <div class="repo-stats-row">
        <div class="stat-box-mini">
            <div class="stat-icon-wrapper"><i class="ph-bold ph-file-text"></i></div>
            <div>
                <h4>Proyectos Indexados</h4>
                <div class="stat-number"><?= number_format($totalPST) ?></div>
            </div>
        </div>
        <div class="stat-box-mini">
            <div class="stat-icon-wrapper"><i class="ph-bold ph-users"></i></div>
            <div>
                <h4>Autores (Estudiantes)</h4>
                <div class="stat-number"><?= number_format($totalAutores) ?></div>
            </div>
        </div>
        <div class="stat-box-mini">
            <div class="stat-icon-wrapper"><i class="ph-bold ph-map-pin"></i></div>
            <div>
                <h4>Comunidades Beneficiadas</h4>
                <div class="stat-number"><?= number_format($totalComunidades) ?></div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de Acceso Rápido -->
    <div class="repo-menu-cards">
        
        <div class="repo-action-card">
            <i class="ph-fill ph-file-plus"></i>
            <h3>Cargar Documento</h3>
            <p>Registrar un nuevo PST aprobado por el comité evaluador. Asegura la carga del PDF de la bitácora y la matriz epistémica.</p>
            <a href="?ruta=agregar-documento" class="card-link-action">Acceder al formulario <i class="ph-bold ph-arrow-right"></i></a>
        </div>

        <div class="repo-action-card">
            <i class="ph-fill ph-magnifying-glass"></i>
            <h3>Buscador Unificado</h3>
            <p>Consulta avanzada sobre el banco de proyectos mediante filtros por palabras clave, tutores asesores o comunidades autónomas.</p>
            <a href="?ruta=buscador" class="card-link-action">Abrir motor de búsqueda <i class="ph-bold ph-arrow-right"></i></a>
        </div>

        <div class="repo-action-card">
            <i class="ph-fill ph-chart-pie-slice"></i>
            <h3>Modelo de Predicción</h3>
            <p>Visualización estadística e inteligencia científica sobre las tendencias de desarrollo tecnológico en los próximos trayectos académicos.</p>
            <a href="?ruta=dashboard-prediccion" class="card-link-action">Ver analítica de datos <i class="ph-bold ph-arrow-right"></i></a>
        </div>

    </div>

    <!-- Panel de Últimos Proyectos Cargados -->
    <div class="recent-docs-panel">
        <div class="panel-title-bar" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3>Últimos Proyectos Socio-Tecnológicos Cargados</h3>
            <a href="?ruta=detalles-pst" style="color: var(--color-terciario); font-weight: 600; text-decoration: none; font-size: 0.9rem;">Ver Catálogo Completo →</a>
        </div>
        <table class="data-table-repo">
            <thead>
                <tr>
                    <th>Proyecto / Título</th>
                    <th>Comunidad Objeto</th>
                    <th>Línea de Investigación</th>
                    <th>Año Publicación</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recientes)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 2rem; color: var(--texto-silenciado);">
                            No hay proyectos cargados actualmente en la base de datos.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recientes as $doc): ?>
                        <tr>
                            <td>
                                <span class="doc-title-cell" style="font-weight: 700; color: var(--texto-titulos); display: block;">
                                    <?= htmlspecialchars($doc['titulo']) ?>
                                </span>
                                <span class="doc-meta-sub" style="font-size: 0.8rem; color: var(--texto-silenciado);">
                                    Autores: <?= htmlspecialchars($doc['autores_nombres'] ?? 'No registrados') ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($doc['comunidad_beneficiada'] ?? 'No registrada') ?></td>
                            <td><span class="badge-linea" style="background-color: rgba(112, 144, 203, 0.1); color: var(--color-secundario); padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 700;"><?= htmlspecialchars($doc['linea_nombre'] ?? 'General') ?></span></td>
                            <td><strong><?= $doc['anio_publicacion'] ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>