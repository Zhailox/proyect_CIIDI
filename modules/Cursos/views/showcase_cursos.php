<?php
// modules/Cursos/views/showcase_cursos.php
// Variables: $cursos, $estadisticas, $filtros, $paginacion, $mensaje_exito, $mensaje_error, $usuario_actual, $config_vista

$nivel           = $usuario_actual['nivel'] ?? -1;
$puede_gestionar = ($nivel >= ($config_vista['roles']['nivel_crear_curso']   ?? 1));
$puede_eliminar  = ($nivel >= ($config_vista['roles']['nivel_eliminar_curso'] ?? 2));
$puede_config    = ($nivel >= ($config_vista['roles']['nivel_ver_config']     ?? 3));

$busqueda       = htmlspecialchars($filtros['busqueda'] ?? '', ENT_QUOTES, 'UTF-8');
$filtro_estado  = $filtros['estado'] ?? '';

$lazy_attr      = !empty($config_vista['imagenes']['lazy_load']) ? 'lazy' : 'eager';
$placeholder    = htmlspecialchars($config_vista['imagenes']['placeholder_url'] ?? '', ENT_QUOTES, 'UTF-8');
$url_fallback   = htmlspecialchars($config_vista['moodle']['url_fallback']      ?? '#', ENT_QUOTES, 'UTF-8');

// Paginación
$pag           = $paginacion ?? ['pagina_actual' => 1, 'total_paginas' => 1, 'total' => 0, 'por_pagina' => 9, 'opciones' => [9]];
$pagina_actual = (int)$pag['pagina_actual'];
$total_paginas = (int)$pag['total_paginas'];
$por_pagina    = (int)$pag['por_pagina'];

// EstadÃ­sticas
$total_publicados = (int)($estadisticas['publicados'] ?? 0);
$total_borradores = (int)($estadisticas['borradores'] ?? 0);
$total_archivados = (int)($estadisticas['archivados'] ?? 0);
$total_todos      = (int)($estadisticas['total']      ?? 0);
?>

<div class="cur-wrapper">

    <?php if ($mensaje_exito): ?>
        <div class="cur-flash cur-flash--ok" role="alert">
            <i class="ph-bold ph-check-circle"></i>
            <?= htmlspecialchars($mensaje_exito) ?>
        </div>
    <?php endif; ?>

    <?php if ($mensaje_error): ?>
        <div class="cur-flash cur-flash--err" role="alert">
            <i class="ph-bold ph-warning-circle"></i>
            <?= htmlspecialchars($mensaje_error) ?>
        </div>
    <?php endif; ?>

    <!-- ===== HERO ===== -->
    <section class="cur-hero" aria-label="CatÃ¡logo de FormaciÃ³n">
        <div class="cur-hero-inner">

            <div class="cur-hero-text">
                <h1>FormaciÃ³n <span>Continua</span><br>de Alto Impacto</h1>
                <p>
                    Potencia tus habilidades con cursos especializados impartidos por nuestros docentes.
                    Aprende a tu ritmo, certifica tu conocimiento y accede a travÃ©s de Moodle.
                </p>

                <!-- BÃºsqueda -->
                <form method="GET" action="" class="cur-hero-search" role="search" id="form-busqueda">
                    <input type="hidden" name="ruta" value="cursos">
                    <?php if ($filtro_estado && $puede_gestionar): ?>
                        <input type="hidden" name="estado" value="<?= htmlspecialchars($filtro_estado) ?>">
                    <?php endif; ?>
                    <i class="ph-bold ph-magnifying-glass"></i>
                    <input
                        type="search"
                        name="busqueda"
                        id="campo-busqueda"
                        value="<?= $busqueda ?>"
                        placeholder="Buscar cursos, temas o docentesâ€¦"
                        autocomplete="off"
                        aria-label="Buscar cursos"
                    >
                    <button type="submit">
                        <i class="ph-bold ph-arrow-right"></i>
                    </button>
                </form>
            </div>

            <!-- Stats -->
            <div class="cur-hero-stats" aria-label="EstadÃ­sticas">
                <div class="cur-stat-card">
                    <span class="cur-stat-num" data-target="<?= $total_todos ?>">0</span>
                    <span class="cur-stat-label">Total Cursos</span>
                </div>
                <div class="cur-stat-card">
                    <span class="cur-stat-num" data-target="<?= $total_publicados ?>">0</span>
                    <span class="cur-stat-label">Disponibles</span>
                </div>
                <?php if ($puede_gestionar): ?>
                <div class="cur-stat-card">
                    <span class="cur-stat-num" data-target="<?= $total_borradores ?>">0</span>
                    <span class="cur-stat-label">Borradores</span>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </section>

    <!-- ===== BARRA ADMIN ===== -->
    <?php if ($puede_gestionar): ?>
    <div class="cur-admin-bar" role="toolbar" aria-label="Panel de gestiÃ³n">
        <span class="cur-admin-label">
            <i class="ph-fill ph-shield-check"></i>
            Panel de GestiÃ³n de Cursos
        </span>
        <div class="cur-admin-actions">
            <a href="?ruta=cursos-crear" class="cur-btn-admin cur-btn-admin--new">
                <i class="ph-bold ph-plus"></i>
                Nuevo Curso
            </a>
        </div>
    </div>
    <?php endif; ?>

    <!-- ===== FILTROS (TABS) ===== -->
    <nav class="cur-filter-tabs" aria-label="Filtrar por estado">
        <a href="?ruta=cursos"
           class="cur-filter-tab <?= $filtro_estado === '' ? 'active' : '' ?>"
           aria-current="<?= $filtro_estado === '' ? 'page' : 'false' ?>">
            <i class="ph-fill ph-squares-four"></i>
            Todos
            <?php if ($puede_gestionar): ?>
                <span class="cur-count"><?= $total_todos ?></span>
            <?php else: ?>
                <span class="cur-count"><?= $total_publicados ?></span>
            <?php endif; ?>
        </a>

        <a href="?ruta=cursos&estado=publicado"
           class="cur-filter-tab <?= $filtro_estado === 'publicado' ? 'active' : '' ?>">
            <i class="ph-fill ph-check-circle"></i>
            Publicados
            <span class="cur-count"><?= $total_publicados ?></span>
        </a>

        <?php if ($puede_gestionar): ?>
        <a href="?ruta=cursos&estado=borrador"
           class="cur-filter-tab <?= $filtro_estado === 'borrador' ? 'active' : '' ?>">
            <i class="ph-fill ph-pencil-simple"></i>
            Borradores
            <span class="cur-count"><?= $total_borradores ?></span>
        </a>

        <a href="?ruta=cursos&estado=archivado"
           class="cur-filter-tab <?= $filtro_estado === 'archivado' ? 'active' : '' ?>">
            <i class="ph-fill ph-archive"></i>
            Archivados
            <span class="cur-count"><?= $total_archivados ?></span>
        </a>
        <?php endif; ?>
    </nav>

    <!-- ===== RESULTADOS BAR ===== -->
    <?php if (!empty($busqueda)): ?>
    <div class="cur-results-bar">
        <p class="cur-results-count">
            Resultados para: <strong>"<?= $busqueda ?>"</strong>
            — <?= count($cursos) ?> curso<?= count($cursos) !== 1 ? 's' : '' ?> encontrado<?= count($cursos) !== 1 ? 's' : '' ?>
        </p>
        <a href="?ruta=cursos" class="cur-filter-tab">
            <i class="ph-bold ph-x"></i> Limpiar bÃºsqueda
        </a>
    </div>
    <?php endif; ?>

    <!-- ===== GRID DE CURSOS ===== -->
    <main id="cur-grid-container" class="cur-grid" aria-label="Lista de cursos">

        <?php if (empty($cursos)): ?>

            <!-- Estado vacÃ­o con cursos de demostraciÃ³n -->
            <div class="cur-empty-wrapper">
                <div class="cur-empty-msg">
                    <i class="ph-fill ph-graduation-cap"></i>
                    <h3>
                        <?php if (!empty($busqueda)): ?>
                            Sin resultados para "<?= $busqueda ?>"
                        <?php elseif ($filtro_estado): ?>
                            No hay cursos en estado Â'<?= htmlspecialchars($filtro_estado) ?>Â»
                        <?php else: ?>
                            La oferta formativa se publicarÃ¡ pronto
                        <?php endif; ?>
                    </h3>
                    <p>
                        <?php if (!empty($busqueda) || $filtro_estado): ?>
                            Prueba con otros tÃ©rminos o
                            <a href="?ruta=cursos">ver todos los cursos</a>.
                        <?php elseif ($puede_gestionar): ?>
                            Comienza registrando el primer curso en el catÃ¡logo.
                            <a href="?ruta=cursos-crear">Crear el primero</a>.
                        <?php else: ?>
                            PrÃ³ximamente tendremos disponibles cursos especializados para ti.
                        <?php endif; ?>
                    </p>
                </div>

                <!-- Cursos de demostraciÃ³n (solo si no hay bÃºsqueda activa) -->
                <?php if (empty($busqueda) && !$filtro_estado): ?>
                <div style="position:relative; width:100%;">
                    <div class="cur-demo-grid">
                        <?php
                        $demos = [
                            ['Inteligencia Artificial Aplicada', 'Dr. Carlos RamÃ­rez', '48 horas', 'Virtual', 'https://images.unsplash.com/photo-1677442135703-1787eea5ce01?auto=format&fit=crop&q=80&w=600'],
                            ['DiseÃ±o de Circuitos ElectrÃ³nicos', 'Prof. MarÃ­a GonzÃ¡lez', '36 horas', 'Presencial', 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&q=80&w=600'],
                            ['GestiÃ³n de Proyectos Ãgiles', 'Lic. Pedro Alonzo', '24 horas', 'HÃ­brido', 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&q=80&w=600'],
                        ];
                        foreach ($demos as $demo): ?>
                        <div class="cur-card cur-card-skeleton-real" aria-hidden="true">
                            <div class="cur-card-img" style="background-image:url('<?= $demo[4] ?>');">
                                <div class="cur-card-badges">
                                    <span class="cur-badge badge-publicado"><i class="ph-fill ph-check"></i> Publicado</span>
                                    <span class="cur-badge cur-badge-modalidad">
                                        <i class="ph-fill ph-monitor"></i> <?= $demo[3] ?>
                                    </span>
                                </div>
                            </div>
                            <div class="cur-card-body">
                                <span class="cur-card-category">PrÃ³ximamente</span>
                                <h3 class="cur-card-title"><?= $demo[0] ?></h3>
                                <p class="cur-card-desc">Curso especializado diseÃ±ado para potenciar tus competencias profesionales en esta Ã¡rea de alta demanda laboral.</p>
                                <div class="cur-card-meta">
                                    <div class="cur-card-meta-item">
                                        <i class="ph-fill ph-chalkboard-teacher"></i>
                                        <span><?= $demo[1] ?></span>
                                    </div>
                                    <div class="cur-card-meta-item">
                                        <i class="ph-fill ph-clock"></i>
                                        <span><?= $demo[2] ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="cur-card-footer">
                                <span class="cur-btn-moodle" style="cursor:default; animation:none; opacity:0.5;">
                                    <i class="ph-bold ph-graduation-cap"></i> PrÃ³ximamente en Moodle
                                </span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="cur-demo-label">
                        <h4><i class="ph-fill ph-info" style="color:var(--cur-light)"></i> Vista Previa de Ejemplo</h4>
                        <p>Los cursos reales aparecerÃ¡n aquÃ­ una vez publicados.</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

        <?php else: ?>

            <?php foreach ($cursos as $curso):

                $estado       = $curso['estado'];
                $titulo       = htmlspecialchars($curso['titulo']);
                $descripcion  = htmlspecialchars(mb_strimwidth($curso['descripcion'] ?? '', 0, 110, 'â€¦'));
                $docente      = htmlspecialchars($curso['nombre_docente'] ?? 'Sin asignar');
                $url_moodle   = $curso['url_moodle'] ?? '';
                $modalidad    = htmlspecialchars($curso['modalidad'] ?? 'Virtual');
                $duracion     = htmlspecialchars($curso['duracion'] ?? '');
                $nivel_c      = htmlspecialchars($curso['nivel'] ?? '');

                $img = !empty($curso['imagen_portada'])
                    ? htmlspecialchars($curso['imagen_portada'])
                    : 'https://images.unsplash.com/photo-1501504905252-473c47e087f8?auto=format&fit=crop&q=80&w=600';

                // Â¿Curso nuevo? (creado en los Ãºltimos 30 dÃ­as)
                $es_nuevo = (strtotime($curso['fecha_creacion']) > strtotime('-30 days'));

                $clase_badge = match($estado) {
                    'publicado' => 'badge-publicado',
                    'borrador'  => 'badge-borrador',
                    'archivado' => 'badge-archivado',
                    default     => ''
                };
                $label_estado = match($estado) {
                    'publicado' => 'Publicado',
                    'borrador'  => 'Borrador',
                    'archivado' => 'Archivado',
                    default     => ucfirst($estado)
                };

                // Ãcono de modalidad
                $icon_modalidad = match(strtolower($modalidad)) {
                    'presencial' => 'ph-map-pin',
                    'hÃ­brido', 'hibrido' => 'ph-arrows-split',
                    default => 'ph-monitor',
                };
            ?>
            <article
                class="cur-card <?= $estado !== 'publicado' ? 'cur-card--inactivo' : '' ?>"
                aria-label="<?= $titulo ?>"
            >
                <!-- Imagen de portada — con lazy load -->
                <div class="cur-card-img">
                    <img
                        src="<?= !empty($img) ? htmlspecialchars($img, ENT_QUOTES, 'UTF-8') : $placeholder ?>"
                        alt="Portada de <?= $titulo ?>"
                        loading="<?= $lazy_attr ?>"
                        onerror="this.src='<?= $placeholder ?>'"
                        style="width:100%;height:100%;object-fit:cover;display:block;"
                    >
                    <div class="cur-card-badges" style="position:absolute;top:0;left:0;right:0;padding:0.75rem;">
                        <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                            <span class="cur-badge <?= $clase_badge ?>">
                                <?= $estado === 'publicado' ? '<i class="ph-fill ph-check"></i>' : '' ?>
                                <?= $label_estado ?>
                            </span>
                            <?php if ($es_nuevo && $estado === 'publicado'): ?>
                                <span class="cur-badge cur-badge-nuevo">âœ¨ Nuevo</span>
                            <?php endif; ?>
                        </div>
                        <span class="cur-badge cur-badge-modalidad">
                            <i class="ph-fill <?= $icon_modalidad ?>"></i>
                            <?= $modalidad ?>
                        </span>
                    </div>

                    <!-- Acciones admin (sobre imagen) -->
                    <?php if ($puede_gestionar): ?>
                    <div class="cur-card-actions">
                        <a href="?ruta=cursos-editar&id=<?= $curso['id'] ?>"
                           class="cur-action-btn cur-action-btn--edit"
                           title="Editar curso">
                            <i class="ph-bold ph-pencil-simple"></i>
                        </a>
                        <?php if ($puede_eliminar): ?>
                        <button type="button"
                                class="cur-action-btn cur-action-btn--delete"
                                title="¿Eliminar curso"
                                onclick="abrirModal¿Eliminar(<?= $curso['id'] ?>, '<?= addslashes($titulo) ?>')">
                            <i class="ph-bold ph-trash"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Cuerpo -->
                <div class="cur-card-body">
                    <?php if ($nivel_c): ?>
                        <span class="cur-card-category">
                            <i class="ph-fill ph-chart-bar-horizontal"></i> <?= $nivel_c ?>
                        </span>
                    <?php endif; ?>
                    <h3 class="cur-card-title"><?= $titulo ?></h3>
                    <?php if ($descripcion): ?>
                        <p class="cur-card-desc"><?= $descripcion ?></p>
                    <?php endif; ?>

                    <div class="cur-card-meta">
                        <div class="cur-card-meta-item">
                            <i class="ph-fill ph-chalkboard-teacher"></i>
                            <span><?= $docente ?></span>
                        </div>
                        <?php if ($duracion): ?>
                        <div class="cur-card-meta-item">
                            <i class="ph-fill ph-clock"></i>
                            <span><?= $duracion ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="cur-card-meta-item">
                            <i class="ph-fill ph-calendar-blank"></i>
                            <span><?= date('d/m/Y', strtotime($curso['fecha_creacion'])) ?></span>
                        </div>
                    </div>
                </div>

                <!-- CTAs -->
                <div class="cur-card-footer">
                    <?php if ($estado === 'publicado'): ?>

                        <!-- Ver detalle siempre disponible -->
                        <a href="?ruta=cursos-detalle&id=<?= $curso['id'] ?>"
                           class="cur-btn-detail"
                           title="Ver detalle del curso">
                            <i class="ph-bold ph-info"></i>
                        </a>

                        <!-- BotÃ³n Moodle: usa url_moodle del curso o el fallback de configuraciÃ³n -->
                        <?php $btn_url = !empty($url_moodle) ? htmlspecialchars($url_moodle, ENT_QUOTES, 'UTF-8') : $url_fallback; ?>
                        <a href="<?= $btn_url ?>"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="cur-btn-moodle"
                           title="<?= !empty($url_moodle) ? 'Abrir curso en Moodle' : 'Ver en plataforma' ?>">
                            <i class="ph-bold ph-graduation-cap"></i>
                            <?= !empty($url_moodle) ? 'Ir al Curso' : 'Ver Plataforma' ?>
                            <i class="ph-bold ph-arrow-up-right"></i>
                        </a>

                    <?php elseif ($puede_gestionar): ?>

                        <a href="?ruta=cursos-editar&id=<?= $curso['id'] ?>"
                           class="cur-btn-draft">
                            <i class="ph-bold ph-pencil-simple"></i>
                            Editar / Publicar
                        </a>

                    <?php endif; ?>
                </div>

            </article>
            <?php endforeach; ?>

        <?php endif; ?>

    </main><!-- /#cur-grid-container -->

    <!-- ===== PAGINACIÃ“N ===== -->
    <?php if ($pag["total"] > 0): ?>
    <nav class="cur-pagination" aria-label="Paginación">
        <div class="cur-pag-info">
            Página <?= $pagina_actual ?> de <?= $total_paginas ?> — <?= $pag['total'] ?> cursos
        </div>


        <div class="cur-pag-controls">
            <?php if ($pagina_actual > 1): ?>
                <a href="?ruta=cursos&pagina=<?= $pagina_actual - 1 ?><?= $filtro_estado ? '&estado='.$filtro_estado : '' ?><?= $busqueda ? '&busqueda='.$busqueda : '' ?>"
                   class="cur-pag-btn" aria-label="Página anterior">
                    <i class="ph-bold ph-caret-left"></i>
                </a>
            <?php endif; ?>

            <?php
            $rango_inicio = max(1, $pagina_actual - 2);
            $rango_fin    = min($total_paginas, $pagina_actual + 2);
            for ($p = $rango_inicio; $p <= $rango_fin; $p++):
            ?>
                <a href="?ruta=cursos&pagina=<?= $p ?><?= $filtro_estado ? '&estado='.$filtro_estado : '' ?><?= $busqueda ? '&busqueda='.$busqueda : '' ?>"
                   class="cur-pag-btn <?= $p === $pagina_actual ? 'cur-pag-btn--active' : '' ?>"
                   <?= $p === $pagina_actual ? 'aria-current="page"' : '' ?>>
                    <?= $p ?>
                </a>
            <?php endfor; ?>

            <?php if ($pagina_actual < $total_paginas): ?>
                <a href="?ruta=cursos&pagina=<?= $pagina_actual + 1 ?><?= $filtro_estado ? '&estado='.$filtro_estado : '' ?><?= $busqueda ? '&busqueda='.$busqueda : '' ?>"
                   class="cur-pag-btn" aria-label="Página siguiente">
                    <i class="ph-bold ph-caret-right"></i>
                </a>
            <?php endif; ?>
        </div>
    </nav>
    <?php endif; ?>

</div><!-- /.cur-wrapper -->
<?php if ($puede_eliminar): ?>
<div id="modal-eliminar" class="cur-modal-overlay" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="modal-titulo">
    <div class="cur-modal">
        <div class="cur-modal-icon">
            <i class="ph-fill ph-warning"></i>
        </div>
        <h3 id="modal-titulo">¿Eliminar este curso?</h3>
        <p>Esta acción es <strong>irreversible</strong>. El curso y toda su información serán eliminados permanentemente del sistema.</p>
        <div class="cur-modal-actions">
            <button type="button" onclick="cerrarModal()" class="btn btn-outline">
                Cancelar
            </button>
            <form id="form-eliminar" method="POST" action="?ruta=cursos-eliminar">
                <input type="hidden" name="id" id="modal-id-curso">
                <?= CursosCsrfService::campoHidden() ?>
                <button type="submit" class="btn cur-btn-danger">
                    <i class="ph-bold ph-trash"></i> Sí, eliminar
                </button>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
(function () {
    // ── Animación de contadores ──
    document.querySelectorAll('.cur-stat-num[data-target]').forEach(function(el) {
        var target = parseInt(el.getAttribute('data-target'), 10);
        if (isNaN(target) || target === 0) { el.textContent = 0; return; }
        var start = 0;
        var duration = 1200;
        var step = Math.ceil(duration / target);
        var timer = setInterval(function() {
            start += Math.max(1, Math.ceil(target / 30));
            if (start >= target) { el.textContent = target; clearInterval(timer); }
            else { el.textContent = start; }
        }, step < 16 ? 16 : step);
    });

    <?php if ($puede_eliminar): ?>
    // ── Modal eliminar ──
    window.abrirModalEliminar = function(id, titulo) {
        document.getElementById('modal-id-curso').value = id;
        document.getElementById('modal-titulo').textContent = '¿Eliminar "' + titulo + '"?';
        var overlay = document.getElementById('modal-eliminar');
        overlay.style.display = 'flex';
        overlay.setAttribute('aria-hidden', 'false');
    };
    window.cerrarModal = function() {
        var overlay = document.getElementById('modal-eliminar');
        overlay.style.display = 'none';
        overlay.setAttribute('aria-hidden', 'true');
    };
    document.getElementById('modal-eliminar').addEventListener('click', function(e) {
        if (e.target === this) cerrarModal();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') cerrarModal();
    });
    <?php endif; ?>

    // ── Auto-flash dismiss ──
    document.querySelectorAll('.cur-flash').forEach(function(el) {
        setTimeout(function() {
            el.style.transition = 'opacity 0.5s ease';
            el.style.opacity = '0';
            setTimeout(function() { el.style.display = 'none'; }, 500);
        }, 5000);
    });

})();
</script>
