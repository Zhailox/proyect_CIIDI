<?php
// modules/Cursos/views/showcase_cursos.php
// Variables disponibles: $cursos, $estadisticas, $filtros, $mensaje_exito, $mensaje_error, $usuario_actual

$nivel = $usuario_actual['nivel'] ?? -1;
$puede_gestionar = ($nivel >= 1);   // Profesor, Admin, SuperAdmin
$puede_eliminar  = ($nivel >= 2);   // Bibliotecario, Admin, SuperAdmin
?>

<div class="cur-wrapper">

    <?php if ($mensaje_exito): ?>
        <div class="cur-flash cur-flash--ok">
            <i class="ph-bold ph-check-circle"></i>
            <?= htmlspecialchars($mensaje_exito) ?>
        </div>
    <?php endif; ?>

    <?php if ($mensaje_error): ?>
        <div class="cur-flash cur-flash--err">
            <i class="ph-bold ph-warning-circle"></i>
            <?= htmlspecialchars($mensaje_error) ?>
        </div>
    <?php endif; ?>

    <!-- ===== HERO ===== -->
    <section class="cur-hero">
        <div class="cur-hero-content">
            <h1>Catálogo de Formación Continua</h1>
            <p>
                Potencia tus habilidades con cursos especializados impartidos por nuestros docentes.
                Aprende a tu ritmo y certifica tu conocimiento.
            </p>
        </div>

        <div class="cur-hero-stats">
            <div class="cur-stat">
                <span class="cur-stat-num"><?= (int)$estadisticas['total'] ?></span>
                <span class="cur-stat-label">Total Cursos</span>
            </div>
            <div class="cur-stat">
                <span class="cur-stat-num"><?= (int)$estadisticas['publicados'] ?></span>
                <span class="cur-stat-label">Publicados</span>
            </div>
            <div class="cur-stat">
                <span class="cur-stat-num"><?= (int)$estadisticas['borradores'] ?></span>
                <span class="cur-stat-label">Borradores</span>
            </div>
        </div>
    </section>

    <!-- ===== BARRA DE ACCIONES ADMIN ===== -->
    <?php if ($puede_gestionar): ?>
    <div class="cur-admin-bar">
        <span class="cur-admin-label">
            <i class="ph-fill ph-shield-check"></i> Panel de Gestión
        </span>
        <a href="?ruta=cursos-crear" class="cur-btn-admin cur-btn-admin--new">
            <i class="ph-bold ph-plus"></i> Nuevo Curso
        </a>
    </div>
    <?php endif; ?>

    <div class="cur-layout">

        <!-- ===== SIDEBAR FILTROS ===== -->
        <aside class="cur-sidebar">
            <div class="cur-sidebar-header">
                <h3>Filtrar Cursos</h3>
                <a href="?ruta=cursos" class="cur-clear-filters">Limpiar</a>
            </div>

            <form method="GET" action="" id="form-filtros">
                <input type="hidden" name="ruta" value="cursos">

                <div class="cur-filter-group">
                    <div class="cur-filter-title">Estado</div>
                    <?php
                    $estados_filtro = ['publicado' => 'Publicado', 'borrador' => 'Borrador', 'archivado' => 'Archivado'];
                    foreach ($estados_filtro as $val => $label):
                        $checked = (($filtros['estado'] ?? '') === $val) ? 'checked' : '';
                    ?>
                    <label class="cur-checkbox-label">
                        <input type="radio" name="estado" value="<?= $val ?>" <?= $checked ?> onchange="this.form.submit()">
                        <?= $label ?>
                    </label>
                    <?php endforeach; ?>
                </div>

                <?php if ($puede_gestionar): ?>
                <div class="cur-filter-group">
                    <div class="cur-filter-title">Vista rápida</div>
                    <a href="?ruta=cursos&estado=borrador" class="cur-filter-link <?= ($filtros['estado'] ?? '') === 'borrador' ? 'active' : '' ?>">
                        <i class="ph-fill ph-pencil-simple"></i> Solo Borradores
                    </a>
                    <a href="?ruta=cursos&estado=archivado" class="cur-filter-link <?= ($filtros['estado'] ?? '') === 'archivado' ? 'active' : '' ?>">
                        <i class="ph-fill ph-archive"></i> Solo Archivados
                    </a>
                </div>
                <?php endif; ?>

            </form>

            <?php if ($puede_gestionar): ?>
            <div class="cur-sidebar-stats">
                <div class="cur-mini-stat">
                    <span><?= (int)$estadisticas['publicados'] ?></span>
                    <label>Publicados</label>
                </div>
                <div class="cur-mini-stat">
                    <span><?= (int)$estadisticas['borradores'] ?></span>
                    <label>Borradores</label>
                </div>
                <div class="cur-mini-stat">
                    <span><?= (int)$estadisticas['archivados'] ?></span>
                    <label>Archivados</label>
                </div>
            </div>
            <?php endif; ?>
        </aside>

        <!-- ===== GRID DE CURSOS ===== -->
        <main class="cur-grid">

            <?php if (empty($cursos)): ?>
            <div class="cur-empty">
                <i class="ph-fill ph-books"></i>
                <h3>No hay cursos disponibles</h3>
                <p>
                    <?php if (!empty($filtros['estado'])): ?>
                        No se encontraron cursos con el estado «<?= htmlspecialchars($filtros['estado']) ?>».
                        <a href="?ruta=cursos">Ver todos</a>
                    <?php else: ?>
                        Aún no se han registrado cursos en el sistema.
                        <?php if ($puede_gestionar): ?>
                            <a href="?ruta=cursos-crear">Crear el primero</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </p>
            </div>

            <?php else: ?>
            <?php foreach ($cursos as $curso): ?>
            <?php
                $estado = $curso['estado'];
                $clase_badge_estado = match($estado) {
                    'publicado' => 'estado-publicado',
                    'borrador'  => 'estado-borrador',
                    'archivado' => 'estado-archivado',
                    default     => ''
                };
                $label_estado = match($estado) {
                    'publicado' => 'Publicado',
                    'borrador'  => 'Borrador',
                    'archivado' => 'Archivado',
                    default     => ucfirst($estado)
                };
                $img = !empty($curso['imagen_portada'])
                    ? htmlspecialchars($curso['imagen_portada'])
                    : 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?auto=format&fit=crop&q=80&w=400';
            ?>
            <article class="cur-card <?= $estado !== 'publicado' ? 'cur-card--inactivo' : '' ?>">

                <!-- Badge de estado -->
                <span class="cur-badge <?= $clase_badge_estado ?>"><?= $label_estado ?></span>

                <div class="cur-card-img" style="background-image: url('<?= $img ?>');">
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
                                title="Eliminar curso"
                                onclick="confirmarEliminar(<?= $curso['id'] ?>, '<?= addslashes(htmlspecialchars($curso['titulo'])) ?>')">
                            <i class="ph-bold ph-trash"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="cur-card-body">
                    <h3 class="cur-card-title"><?= htmlspecialchars($curso['titulo']) ?></h3>

                    <?php if (!empty($curso['descripcion'])): ?>
                    <p class="cur-card-desc"><?= htmlspecialchars(mb_strimwidth($curso['descripcion'], 0, 100, '…')) ?></p>
                    <?php endif; ?>

                    <div class="cur-meta-list">
                        <div class="cur-meta-item">
                            <i class="ph-fill ph-chalkboard-teacher"></i>
                            <span><?= htmlspecialchars($curso['nombre_docente'] ?? 'Sin asignar') ?></span>
                        </div>
                        <div class="cur-meta-item">
                            <i class="ph-fill ph-medal"></i>
                            <span>Nota mínima: <?= number_format((float)$curso['nota_minima_aprobacion'], 1) ?></span>
                        </div>
                        <div class="cur-meta-item">
                            <i class="ph-fill ph-calendar-blank"></i>
                            <span><?= date('d/m/Y', strtotime($curso['fecha_creacion'])) ?></span>
                        </div>
                    </div>

                    <?php if ($estado === 'publicado'): ?>
                    <a href="?ruta=cursos-detalle&id=<?= $curso['id'] ?>" class="cur-btn-external">
                        Ver Curso <i class="ph-bold ph-arrow-up-right"></i>
                    </a>
                    <?php elseif ($puede_gestionar): ?>
                    <a href="?ruta=cursos-editar&id=<?= $curso['id'] ?>" class="cur-btn-draft">
                        <i class="ph-bold ph-pencil-simple"></i> Editar / Publicar
                    </a>
                    <?php endif; ?>
                </div>

            </article>
            <?php endforeach; ?>
            <?php endif; ?>

        </main>
    </div>
</div>

<!-- ===== MODAL CONFIRMACIÓN ELIMINAR ===== -->
<?php if ($puede_eliminar): ?>
<div id="modal-eliminar" class="cur-modal-overlay" style="display:none;">
    <div class="cur-modal">
        <div class="cur-modal-icon">
            <i class="ph-fill ph-warning"></i>
        </div>
        <h3 id="modal-titulo-curso">¿Eliminar este curso?</h3>
        <p>Esta acción es <strong>irreversible</strong>. El curso y toda su información serán eliminados permanentemente del sistema.</p>
        <div class="cur-modal-actions">
            <button type="button" onclick="cerrarModal()" class="btn btn-outline">Cancelar</button>
            <form id="form-eliminar" method="POST" action="?ruta=cursos-eliminar">
                <input type="hidden" name="id" id="modal-id-curso">
                <button type="submit" class="btn cur-btn-danger">
                    <i class="ph-bold ph-trash"></i> Sí, eliminar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function confirmarEliminar(id, titulo) {
    document.getElementById('modal-id-curso').value = id;
    document.getElementById('modal-titulo-curso').textContent = '¿Eliminar "' + titulo + '"?';
    document.getElementById('modal-eliminar').style.display = 'flex';
}
function cerrarModal() {
    document.getElementById('modal-eliminar').style.display = 'none';
}
document.getElementById('modal-eliminar').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
</script>
<?php endif; ?>