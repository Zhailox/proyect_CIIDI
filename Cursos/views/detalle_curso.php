<?php
// modules/Cursos/views/detalle_curso.php
// Variables inyectadas por PromoController::detalle():
// $curso       — array con datos completos del curso + docente
// $lecciones   — array de lecciones ordenadas
// $inscripcion — array|null  (null si no está inscrito)
// $usuario_id  — int|null    (null si no hay sesión)

$esta_inscrito = $inscripcion !== null;
$progreso      = (int) ($inscripcion['progreso'] ?? 0);

// Control de permisos para gestionar
$nivel_privilegio = (int) ($_SESSION['nivel_privilegio'] ?? -1);
$es_admin         = $nivel_privilegio >= 3;
$puede_gestionar  = $es_admin || ($usuario_id && (int) $curso['id_docente'] === (int) $usuario_id);

// Mensajes flash desde GET (patrón PRG)
$msg_exito    = isset($_GET['inscrito'])    && $_GET['inscrito']    === '1';
$msg_ya       = isset($_GET['ya_inscrito']) && $_GET['ya_inscrito'] === '1';
$msg_error    = isset($_GET['error'])       && $_GET['error']       === '1';
$msg_actualizado = isset($_GET['actualizado']) && $_GET['actualizado'] === '1';
$msg_creado   = isset($_GET['creado'])      && $_GET['creado']      === '1';

// Imagen de portada por defecto si está vacía
$img_portada = !empty($curso['imagen_portada'])
    ? htmlspecialchars($curso['imagen_portada'])
    : 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?auto=format&fit=crop&w=1200&q=80';

/**
 * Convierte una URL de YouTube en su versión embebida.
 * Soporta: youtube.com/watch?v=ID  y  youtu.be/ID
 */
function ytEmbed(string $url): ?string {
    if (empty(trim($url))) return null;
    preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_\-]{11})/', $url, $m);
    return isset($m[1]) ? 'https://www.youtube.com/embed/' . $m[1] : null;
}
?>

<div class="det-wrapper">

    <!-- ── HERO DEL CURSO ─────────────────────────────────────────────── -->
    <section class="det-hero" style="background-image: url('<?= $img_portada ?>');">
        <div class="det-hero-overlay">
            <div class="det-hero-content">
                <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1rem;">
                    <a href="?ruta=cursos" class="det-back-link" style="margin: 0;">
                        <i class="ph-bold ph-arrow-left"></i> Volver al Catálogo
                    </a>
                    <?php if ($puede_gestionar): ?>
                    <a href="?ruta=form-curso&id=<?= $curso['id'] ?>" class="det-badge" style="background: rgba(255,255,255,0.25); text-decoration: none;">
                        <i class="ph-fill ph-pencil-simple"></i> Editar Curso
                    </a>
                    <?php endif; ?>
                </div>
                
                <h1><?= htmlspecialchars($curso['titulo']) ?></h1>
                <p class="det-hero-docente">
                    <i class="ph-fill ph-chalkboard-teacher"></i>
                    <?= htmlspecialchars($curso['docente_nombre']) ?>
                </p>
                <div class="det-hero-badges">
                    <span class="det-badge">
                        <i class="ph-fill ph-books"></i>
                        <?= $curso['total_lecciones'] ?> Lección<?= $curso['total_lecciones'] !== 1 ? 'es' : '' ?>
                    </span>
                    <span class="det-badge">
                        <i class="ph-fill ph-users"></i>
                        <?= $curso['total_inscritos'] ?> Inscrito<?= $curso['total_inscritos'] !== 1 ? 's' : '' ?>
                    </span>
                    <span class="det-badge">
                        <i class="ph-fill ph-medal"></i>
                        Aprobación: <?= $curso['nota_minima_aprobacion'] ?>%
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- ── MENSAJES FLASH ─────────────────────────────────────────────── -->
    <?php if ($msg_exito): ?>
    <div class="det-alert det-alert--success">
        <i class="ph-fill ph-check-circle"></i>
        ¡Te inscribiste exitosamente! Ya puedes acceder al contenido del curso.
    </div>
    <?php elseif ($msg_ya): ?>
    <div class="det-alert det-alert--info">
        <i class="ph-fill ph-info"></i>
        Ya estabas inscrito en este curso.
    </div>
    <?php elseif ($msg_creado): ?>
    <div class="det-alert det-alert--success">
        <i class="ph-fill ph-check-circle"></i>
        Curso creado exitosamente.
    </div>
    <?php elseif ($msg_actualizado): ?>
    <div class="det-alert det-alert--success">
        <i class="ph-fill ph-check-circle"></i>
        Curso actualizado exitosamente.
    </div>
    <?php elseif ($msg_error): ?>
    <div class="det-alert det-alert--error">
        <i class="ph-fill ph-warning-circle"></i>
        Ocurrió un error al procesar tu solicitud. Intenta de nuevo.
    </div>
    <?php endif; ?>

    <!-- ── CUERPO PRINCIPAL ───────────────────────────────────────────── -->
    <div class="det-layout">

        <!-- COLUMNA IZQUIERDA: Lecciones -->
        <main class="det-main">

            <?php if (!empty($curso['descripcion'])): ?>
            <section class="det-section">
                <h2 class="det-section-title">
                    <i class="ph-fill ph-article"></i> Descripción
                </h2>
                <p class="det-descripcion"><?= nl2br(htmlspecialchars($curso['descripcion'])) ?></p>
            </section>
            <?php endif; ?>

            <section class="det-section">
                <h2 class="det-section-title">
                    <i class="ph-fill ph-list-numbers"></i>
                    Contenido del Curso
                    <span class="det-count"><?= count($lecciones) ?> lección<?= count($lecciones) !== 1 ? 'es' : '' ?></span>
                </h2>

                <?php if (empty($lecciones)): ?>
                <p style="color:var(--texto-silenciado); font-style:italic; padding: 1rem 0;">
                    Este curso aún no tiene lecciones publicadas.
                </p>
                <?php else: ?>

                <div class="det-accordion">
                    <?php foreach ($lecciones as $i => $leccion):
                        $embed = ytEmbed($leccion['url_video'] ?? '');
                    ?>
                    <div class="det-acc-item" id="leccion-<?= $leccion['id'] ?>">
                        <button class="det-acc-header" onclick="toggleLeccion(this)">
                            <div class="det-acc-left">
                                <span class="det-acc-num"><?= str_pad($leccion['orden'], 2, '0', STR_PAD_LEFT) ?></span>
                                <span class="det-acc-titulo"><?= htmlspecialchars($leccion['titulo']) ?></span>
                            </div>
                            <div class="det-acc-right">
                                <?php if ($embed): ?>
                                <span class="det-acc-tag"><i class="ph-fill ph-youtube-logo"></i> Video</span>
                                <?php elseif (!empty($leccion['contenido'])): ?>
                                <span class="det-acc-tag"><i class="ph-fill ph-text-align-left"></i> Lectura</span>
                                <?php endif; ?>
                                <i class="ph-bold ph-caret-down det-acc-arrow"></i>
                            </div>
                        </button>

                        <div class="det-acc-body">
                            <?php if ($embed): ?>
                            <div class="det-video-wrap">
                                <iframe
                                    src="<?= $embed ?>"
                                    title="<?= htmlspecialchars($leccion['titulo']) ?>"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                ></iframe>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($leccion['contenido'])): ?>
                            <div class="det-leccion-contenido">
                                <?= nl2br(htmlspecialchars($leccion['contenido'])) ?>
                            </div>
                            <?php endif; ?>

                            <?php if (!$embed && empty($leccion['contenido'])): ?>
                            <p class="det-leccion-vacia">Contenido próximamente disponible.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php endif; ?>
            </section>
        </main>

        <!-- COLUMNA DERECHA: Panel de inscripción / progreso -->
        <aside class="det-sidebar">

            <!-- Panel de inscripción -->
            <div class="det-enroll-card">
                <div class="det-enroll-icon">
                    <i class="ph-fill ph-graduation-cap"></i>
                </div>

                <?php if ($esta_inscrito): ?>

                    <h3>Ya estás inscrito</h3>
                    <p class="det-enroll-sub">Sigue con tu progreso en el curso.</p>

                    <div class="det-progress-wrap">
                        <div class="det-progress-bar">
                            <div class="det-progress-fill" style="width: <?= $progreso ?>%;"></div>
                        </div>
                        <span class="det-progress-label"><?= $progreso ?>% completado</span>
                    </div>

                <?php elseif ($usuario_id): ?>

                    <h3>Únete a este Curso</h3>
                    <p class="det-enroll-sub">Accede a todas las lecciones de forma gratuita.</p>

                    <form method="POST" action="?ruta=inscribirse-curso">
                        <input type="hidden" name="id_curso" value="<?= $curso['id'] ?>">
                        <button type="submit" class="det-btn-inscribir">
                            <i class="ph-fill ph-paper-plane-tilt"></i>
                            INSCRIBIRME AHORA
                        </button>
                    </form>

                <?php else: ?>

                    <h3>¿Quieres aprender?</h3>
                    <p class="det-enroll-sub">Inicia sesión para inscribirte y llevar seguimiento de tu progreso.</p>

                    <a href="?ruta=login" class="det-btn-inscribir">
                        <i class="ph-fill ph-sign-in"></i>
                        INICIAR SESIÓN
                    </a>

                <?php endif; ?>

                <div class="det-enroll-meta">
                    <div class="det-enroll-item">
                        <i class="ph-fill ph-books"></i>
                        <span><?= $curso['total_lecciones'] ?> Lección<?= $curso['total_lecciones'] !== 1 ? 'es' : '' ?></span>
                    </div>
                    <div class="det-enroll-item">
                        <i class="ph-fill ph-users"></i>
                        <span><?= $curso['total_inscritos'] ?> Estudiante<?= $curso['total_inscritos'] !== 1 ? 's' : '' ?></span>
                    </div>
                    <div class="det-enroll-item">
                        <i class="ph-fill ph-medal"></i>
                        <span>Nota mínima: <?= $curso['nota_minima_aprobacion'] ?>%</span>
                    </div>
                    <?php if (!empty($curso['docente_email'])): ?>
                    <div class="det-enroll-item">
                        <i class="ph-fill ph-envelope"></i>
                        <a href="mailto:<?= htmlspecialchars($curso['docente_email']) ?>" style="color: inherit;">
                            <?= htmlspecialchars($curso['docente_email']) ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

        </aside>
    </div>
</div>

<script>
function toggleLeccion(btn) {
    const item  = btn.closest('.det-acc-item');
    const body  = item.querySelector('.det-acc-body');
    const arrow = btn.querySelector('.det-acc-arrow');
    const open  = item.classList.contains('active');

    // Cierra todos los demás
    document.querySelectorAll('.det-acc-item.active').forEach(el => {
        el.classList.remove('active');
        el.querySelector('.det-acc-body').style.maxHeight = null;
        el.querySelector('.det-acc-arrow').style.transform = '';
    });

    if (!open) {
        item.classList.add('active');
        body.style.maxHeight = body.scrollHeight + 'px';
        arrow.style.transform = 'rotate(180deg)';
    }
}

// Auto-ocultar alertas flash tras 5 s
document.querySelectorAll('.det-alert').forEach(el => {
    setTimeout(() => { el.style.opacity = '0'; setTimeout(() => el.remove(), 500); }, 5000);
});
</script>
