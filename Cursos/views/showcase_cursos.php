<?php
// modules/Cursos/views/showcase_cursos.php
// Variables inyectadas por PromoController::catalogo():
// $cursos       — array de cursos publicados
// $total_cursos — int

$imgs_defecto = [
    'https://images.unsplash.com/photo-1633356122544-f134324a6cee?auto=format&fit=crop&w=600&q=80',
    'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=600&q=80',
    'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=600&q=80',
    'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=600&q=80',
    'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=600&q=80',
    'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?auto=format&fit=crop&w=600&q=80',
];

// Datos del usuario en sesión para control de permisos
$usuario_id       = $_SESSION['usuario_id']       ?? null;
$nivel_privilegio = (int) ($_SESSION['nivel_privilegio'] ?? -1);
$puede_crear      = $usuario_id && $nivel_privilegio >= 1; // Profesor o Admin
$es_admin         = $nivel_privilegio >= 3;

// Mensajes flash
$msg_eliminado = isset($_GET['eliminado']) && $_GET['eliminado'] === '1';
?>
<div class="cur-wrapper">

    <section class="cur-hero">
        <div class="cur-hero-content">
            <h1>Catálogo de Formación Continua</h1>
            <p>
                Potencia tus habilidades tecnológicas con cursos especializados impartidos por nuestros docentes. Aprende a tu ritmo con contenido actualizado y práctico.
            </p>
            <?php if ($puede_crear): ?>
            <a href="?ruta=form-curso" class="cur-btn-nuevo">
                <i class="ph-bold ph-plus"></i> Nuevo Curso
            </a>
            <?php endif; ?>
        </div>

        <div class="cur-hero-stats">
            <div class="cur-stat">
                <span class="cur-stat-num"><?= $total_cursos ?></span>
                <span class="cur-stat-label">Cursos Activos</span>
            </div>
            <div class="cur-stat">
                <?php
                    $total_lecciones = array_sum(array_column($cursos, 'total_lecciones'));
                ?>
                <span class="cur-stat-num"><?= $total_lecciones ?></span>
                <span class="cur-stat-label">Lecciones</span>
            </div>
            <div class="cur-stat">
                <?php
                    $total_inscritos = array_sum(array_column($cursos, 'total_inscritos'));
                ?>
                <span class="cur-stat-num"><?= $total_inscritos ?></span>
                <span class="cur-stat-label">Inscritos</span>
            </div>
        </div>
    </section>

    <?php if ($msg_eliminado): ?>
    <div class="det-alert det-alert--success" id="alert-flash">
        <i class="ph-fill ph-check-circle"></i>
        Curso eliminado correctamente.
    </div>
    <?php endif; ?>

    <div class="cur-layout">

        <aside class="cur-sidebar">
            <div class="cur-sidebar-header">
                <h3>Filtrar Cursos</h3>
                <span class="cur-clear-filters" onclick="limpiarFiltros()">Limpiar</span>
            </div>

            <div class="cur-filter-group">
                <div class="cur-filter-title">Buscar</div>
                <input
                    type="text"
                    id="buscador-cursos"
                    class="cur-search-input"
                    placeholder="Título o docente..."
                    oninput="filtrarCursos()"
                >
            </div>

            <div class="cur-filter-group">
                <div class="cur-filter-title">Duración</div>
                <label class="cur-checkbox-label">
                    <input type="checkbox" class="filtro-dur" value="corto" onchange="filtrarCursos()"> 1–5 lecciones
                </label>
                <label class="cur-checkbox-label">
                    <input type="checkbox" class="filtro-dur" value="medio" onchange="filtrarCursos()"> 6–15 lecciones
                </label>
                <label class="cur-checkbox-label">
                    <input type="checkbox" class="filtro-dur" value="largo" onchange="filtrarCursos()"> 16+ lecciones
                </label>
            </div>

            <a href="?ruta=cursos" class="btn" style="width:100%; margin-top:1rem; padding:0.8rem; text-align:center; text-decoration:none; display:block;">
                Ver todos
            </a>
        </aside>

        <main class="cur-grid" id="grid-cursos">

            <?php if (empty($cursos)): ?>
            <div style="grid-column:1/-1; text-align:center; padding:4rem; color:var(--texto-silenciado);">
                <i class="ph-fill ph-graduation-cap" style="font-size:3rem; opacity:0.3; display:block; margin-bottom:1rem;"></i>
                <p>No hay cursos publicados en este momento.</p>
            </div>
            <?php else: ?>

            <?php foreach ($cursos as $idx => $c):
                $img = !empty($c['imagen_portada'])
                    ? htmlspecialchars($c['imagen_portada'])
                    : $imgs_defecto[$idx % count($imgs_defecto)];
                $dur_clase = $c['total_lecciones'] <= 5 ? 'corto' : ($c['total_lecciones'] <= 15 ? 'medio' : 'largo');
            ?>
            <article class="cur-card"
                     data-titulo="<?= strtolower(htmlspecialchars($c['titulo'])) ?>"
                     data-docente="<?= strtolower(htmlspecialchars($c['docente_nombre'])) ?>"
                     data-dur="<?= $dur_clase ?>">

                <div class="cur-card-img" style="background-image: url('<?= $img ?>');"></div>

                <div class="cur-card-body">
                    <h3 class="cur-card-title"><?= htmlspecialchars($c['titulo']) ?></h3>

                    <div class="cur-meta-list">
                        <div class="cur-meta-item">
                            <i class="ph-fill ph-chalkboard-teacher"></i>
                            <span><?= htmlspecialchars($c['docente_nombre']) ?></span>
                        </div>
                        <div class="cur-meta-item">
                            <i class="ph-fill ph-books"></i>
                            <span><?= $c['total_lecciones'] ?> Lección<?= $c['total_lecciones'] !== 1 ? 'es' : '' ?></span>
                        </div>
                        <div class="cur-meta-item">
                            <i class="ph-fill ph-users"></i>
                            <span><?= $c['total_inscritos'] ?> Inscrito<?= $c['total_inscritos'] !== 1 ? 's' : '' ?></span>
                        </div>
                    </div>

                    <?php if (!empty($c['descripcion'])): ?>
                    <p class="cur-card-desc"><?= htmlspecialchars(mb_substr($c['descripcion'], 0, 110)) ?>...</p>
                    <?php endif; ?>

                    <a href="?ruta=detalle-curso&id=<?= $c['id'] ?>" class="cur-btn-external">
                        Ver Curso <i class="ph-bold ph-arrow-right"></i>
                    </a>

                    <?php
                        // Mostrar controles si es admin o es el docente de este curso
                        $puede_gestionar = $es_admin
                            || ($usuario_id && isset($c['id_docente']) && (int)$c['id_docente'] === (int)$usuario_id);
                    ?>
                    <?php if ($puede_gestionar): ?>
                    <div class="cur-card-mgmt">
                        <a href="?ruta=form-curso&id=<?= $c['id'] ?>" class="cur-mgmt-btn cur-mgmt-edit" title="Editar">
                            <i class="ph-fill ph-pencil-simple"></i> Editar
                        </a>
                        <?php if ($es_admin): ?>
                        <form method="POST" action="?ruta=eliminar-curso" style="margin:0;"
                              onsubmit="return confirm('¿Eliminar este curso?')">
                            <input type="hidden" name="id_curso" value="<?= $c['id'] ?>">
                            <button type="submit" class="cur-mgmt-btn cur-mgmt-delete" title="Eliminar">
                                <i class="ph-fill ph-trash"></i> Eliminar
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>

            <?php endif; ?>
        </main>

    </div>
</div>

<script>
function filtrarCursos() {
    const texto   = document.getElementById('buscador-cursos').value.toLowerCase();
    const durs    = [...document.querySelectorAll('.filtro-dur:checked')].map(cb => cb.value);
    const tarjetas = document.querySelectorAll('#grid-cursos .cur-card');

    tarjetas.forEach(card => {
        const matchTexto = !texto
            || card.dataset.titulo.includes(texto)
            || card.dataset.docente.includes(texto);
        const matchDur = durs.length === 0 || durs.includes(card.dataset.dur);
        card.style.display = (matchTexto && matchDur) ? '' : 'none';
    });
}

function limpiarFiltros() {
    document.getElementById('buscador-cursos').value = '';
    document.querySelectorAll('.filtro-dur').forEach(cb => cb.checked = false);
    document.querySelectorAll('#grid-cursos .cur-card').forEach(c => c.style.display = '');
}
</script>