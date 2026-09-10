<?php
// modules/Cursos/views/detalle_curso.php
// Variables: $curso, $usuario_actual, $config_vista

$nivel           = $usuario_actual['nivel'] ?? -1;
$puede_gestionar = ($nivel >= ($config_vista['roles']['nivel_crear_curso']   ?? 1));
$puede_eliminar  = ($nivel >= ($config_vista['roles']['nivel_eliminar_curso'] ?? 2));

$lazy_attr       = !empty($config_vista['imagenes']['lazy_load']) ? 'lazy' : 'eager';
$placeholder     = htmlspecialchars($config_vista['imagenes']['placeholder_url'] ?? 'https://images.unsplash.com/photo-1501504905252-473c47e087f8?auto=format&fit=crop&q=80&w=1200', ENT_QUOTES, 'UTF-8');
$url_fallback    = htmlspecialchars($config_vista['moodle']['url_fallback'] ?? '#', ENT_QUOTES, 'UTF-8');

// Datos del curso
$id          = (int)$curso['id'];
$titulo      = htmlspecialchars($curso['titulo'] ?? '', ENT_QUOTES, 'UTF-8');
$descripcion = $curso['descripcion'] ?? '';
$docente     = htmlspecialchars($curso['nombre_docente'] ?? 'No asignado', ENT_QUOTES, 'UTF-8');
$estado      = $curso['estado'];
$nota        = number_format((float)($curso['nota_minima_aprobacion'] ?? 70), 1);
$fecha_reg   = date('d \d\e M\., Y', strtotime($curso['fecha_creacion']));

// Metadatos JSON
$url_moodle   = $curso['url_moodle']        ?? '';
$url_video    = $curso['url_video_preview'] ?? '';
$modalidad    = htmlspecialchars($curso['modalidad']  ?? 'Virtual');
$nivel_c      = htmlspecialchars($curso['nivel']      ?? '');
$duracion     = htmlspecialchars($curso['duracion']   ?? '');
$cupo_maximo  = (int)($curso['cupo_maximo'] ?? 0);

// Imagen de portada
$img = !empty($curso['imagen_portada'])
    ? htmlspecialchars($curso['imagen_portada'])
    : 'https://images.unsplash.com/photo-1501504905252-473c47e087f8?auto=format&fit=crop&q=80&w=1200';

// Íconos de modalidad
$icon_modalidad = match(strtolower($modalidad)) {
    'presencial'          => 'ph-map-pin',
    'híbrido', 'hibrido'  => 'ph-arrows-split',
    default               => 'ph-monitor',
};

// Convertir URL de YouTube/Vimeo a embed
function convertirUrlVideoEmbed(string $url): string {
    if (empty($url)) return '';

    // YouTube: youtube.com/watch?v=xxx  →  youtube.com/embed/xxx
    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_\-]{11})/', $url, $m)) {
        return 'https://www.youtube.com/embed/' . $m[1] . '?rel=0&modestbranding=1';
    }
    // Vimeo: vimeo.com/xxx  →  player.vimeo.com/video/xxx
    if (preg_match('/vimeo\.com\/(\d+)/', $url, $m)) {
        return 'https://player.vimeo.com/video/' . $m[1];
    }
    // Si ya es una URL de embed, devolver tal cual
    return $url;
}
$embed_url = convertirUrlVideoEmbed($url_video);

// Iniciales del docente para el avatar
$iniciales = '';
foreach (explode(' ', $docente) as $word) {
    $iniciales .= mb_strtoupper(mb_substr($word, 0, 1));
    if (mb_strlen($iniciales) >= 2) break;
}

// Obtener letra inicial de cada palabra del docente
?>

<div class="cur-detalle-wrapper">

    <!-- Breadcrumb -->
    <nav class="cur-breadcrumb" aria-label="Navegación">
        <a href="?ruta=cursos">
            <i class="ph-fill ph-graduation-cap"></i>
            Catálogo de Cursos
        </a>
        <i class="ph-bold ph-caret-right"></i>
        <span><?= $titulo ?></span>
    </nav>

    <!-- ===== HERO DEL CURSO ===== -->
    <div class="cur-detalle-hero">
        <div class="cur-detalle-hero-bg">
            <img src="<?= htmlspecialchars($img ?? $placeholder, ENT_QUOTES, 'UTF-8') ?>"
                 alt="Portada del curso <?= $titulo ?>"
                 loading="<?= $lazy_attr ?>"
                 onerror="this.src='<?= $placeholder ?>'"
                 style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div class="cur-detalle-hero-overlay"></div>

        <div class="cur-detalle-hero-content">
            <!-- Tags -->
            <div class="cur-detalle-tags">
                <?php if ($nivel_c): ?>
                    <span class="cur-detalle-tag">
                        <i class="ph-fill ph-chart-bar-horizontal"></i>
                        <?= $nivel_c ?>
                    </span>
                <?php endif; ?>
                <span class="cur-detalle-tag">
                    <i class="ph-fill <?= $icon_modalidad ?>"></i>
                    <?= $modalidad ?>
                </span>
                <?php if ($duracion): ?>
                    <span class="cur-detalle-tag">
                        <i class="ph-fill ph-clock"></i>
                        <?= $duracion ?>
                    </span>
                <?php endif; ?>
                <?php if (!empty($url_moodle)): ?>
                    <span class="cur-detalle-tag cur-detalle-tag--accent">
                        <i class="ph-fill ph-graduation-cap"></i>
                        Disponible en Moodle
                    </span>
                <?php endif; ?>
                <?php if ($estado !== 'publicado' && $puede_gestionar): ?>
                    <span class="cur-detalle-tag" style="background:rgba(230,81,0,0.7);">
                        <i class="ph-fill ph-pencil-simple"></i>
                        <?= ucfirst($estado) ?>
                    </span>
                <?php endif; ?>
            </div>

            <!-- Título principal -->
            <h1 class="cur-detalle-titulo"><?= $titulo ?></h1>

            <!-- Meta row -->
            <div class="cur-detalle-meta-row">
                <div class="cur-detalle-meta-item">
                    <i class="ph-fill ph-chalkboard-teacher"></i>
                    <div>
                        <span>Docente</span>
                        <strong><?= $docente ?></strong>
                    </div>
                </div>
                <div class="cur-detalle-meta-item">
                    <i class="ph-fill ph-calendar-blank"></i>
                    <div>
                        <span>Registrado</span>
                        <strong><?= $fecha_reg ?></strong>
                    </div>
                </div>
                <div class="cur-detalle-meta-item">
                    <i class="ph-fill ph-medal"></i>
                    <div>
                        <span>Nota Mínima</span>
                        <strong><?= $nota ?> / 100</strong>
                    </div>
                </div>
                <?php if ($cupo_maximo > 0): ?>
                <div class="cur-detalle-meta-item">
                    <i class="ph-fill ph-users"></i>
                    <div>
                        <span>Cupo</span>
                        <strong><?= $cupo_maximo ?> estudiantes</strong>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ===== LAYOUT PRINCIPAL ===== -->
    <div class="cur-detalle-layout">

        <!-- COLUMNA IZQUIERDA: Contenido -->
        <main class="cur-detalle-main">

            <!-- Acerca del curso -->
            <section class="cur-detalle-section" aria-labelledby="sec-desc">
                <div class="cur-detalle-section-header">
                    <i class="ph-fill ph-book-open"></i>
                    <h2 id="sec-desc">Acerca de este Curso</h2>
                </div>
                <div class="cur-detalle-section-body">
                    <?php if (!empty($descripcion)): ?>
                        <?php foreach (explode("\n", nl2br(htmlspecialchars($descripcion))) as $parrafo):
                            $parrafo = trim($parrafo);
                            if ($parrafo):
                        ?>
                            <p><?= $parrafo ?></p>
                        <?php endif; endforeach; ?>
                    <?php else: ?>
                        <p style="color:var(--cur-muted); font-style:italic;">
                            <i class="ph-fill ph-info"></i>
                            La descripción detallada de este curso está siendo preparada por el docente.
                        </p>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Video Preview (si existe) -->
            <?php if ($embed_url): ?>
            <section class="cur-detalle-section" aria-labelledby="sec-video">
                <div class="cur-detalle-section-header">
                    <i class="ph-fill ph-play-circle"></i>
                    <h2 id="sec-video">Vista Previa del Curso</h2>
                </div>
                <div class="cur-detalle-section-body">
                    <div class="cur-video-container">
                        <iframe
                            src="<?= htmlspecialchars($embed_url) ?>"
                            title="Vista previa: <?= $titulo ?>"
                            allowfullscreen
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            loading="lazy"
                        ></iframe>
                    </div>
                </div>
            </section>
            <?php endif; ?>

            <!-- Lo que aprenderás -->
            <section class="cur-detalle-section" aria-labelledby="sec-learn">
                <div class="cur-detalle-section-header">
                    <i class="ph-fill ph-list-checks"></i>
                    <h2 id="sec-learn">Lo que Aprenderás</h2>
                </div>
                <div class="cur-detalle-section-body">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.9rem;">
                        <?php
                        $beneficios = [
                            ['ph-certificate',       'Certificado de aprobación oficial'],
                            ['ph-monitor-play',       'Acceso completo a la plataforma Moodle'],
                            ['ph-clock',              'Aprende a tu propio ritmo'],
                            ['ph-chalkboard-teacher', 'Asesoría directa con el docente'],
                            ['ph-books',              'Materiales y recursos descargables'],
                            ['ph-users',              'Comunidad de aprendizaje activa'],
                        ];
                        foreach ($beneficios as $b): ?>
                        <div style="display:flex;align-items:flex-start;gap:0.65rem;padding:0.6rem;border-radius:var(--radius-sm);background:#f8faff;">
                            <i class="ph-fill <?= $b[0] ?>" style="color:#22c55e;font-size:1.2rem;margin-top:0.05rem;flex-shrink:0;"></i>
                            <span style="font-size:0.9rem;color:var(--texto-comun);line-height:1.4;"><?= $b[1] ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

        </main>

        <!-- COLUMNA DERECHA: Sidebar de Acción -->
        <aside class="cur-detalle-sidebar" aria-label="Inscripción y acceso">

            <!-- Card de inscripción / acceso a Moodle -->
            <div class="cur-enroll-card">
                <div class="cur-enroll-card-img" aria-hidden="true" style="overflow:hidden;">
                    <img src="<?= htmlspecialchars($img ?? $placeholder, ENT_QUOTES, 'UTF-8') ?>"
                         alt=""
                         loading="<?= $lazy_attr ?>"
                         onerror="this.src='<?= $placeholder ?>'"
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="cur-enroll-card-body">
                    <h3>
                        <?php if (!empty($url_moodle)): ?>
                            ¡Accede al Curso!
                        <?php else: ?>
                            ¡Inscríbete Ahora!
                        <?php endif; ?>
                    </h3>
                    <p>
                        <?php if (!empty($url_moodle)): ?>
                            Este curso está disponible en la plataforma Moodle de la universidad.
                            Haz clic para acceder directamente.
                        <?php else: ?>
                            Próximamente podrás acceder a este curso a través de la plataforma Moodle.
                        <?php endif; ?>
                    </p>

                    <!-- CTA principal -->
                    <?php if ($estado === 'publicado'): ?>
                        <?php $btn_url = !empty($url_moodle) ? htmlspecialchars($url_moodle, ENT_QUOTES, 'UTF-8') : $url_fallback; ?>
                        <a
                            href="<?= $btn_url ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="cur-btn-enroll-main"
                            aria-label="Ir al curso en plataforma (se abre en nueva pestaña)"
                        >
                            <i class="ph-bold ph-graduation-cap"></i>
                            <?= !empty($url_moodle) ? 'Ingresar al Curso en Moodle' : 'Ingresar a la Plataforma' ?>
                            <i class="ph-bold ph-arrow-up-right"></i>
                        </a>
                    <?php elseif ($puede_gestionar): ?>
                        <a href="?ruta=cursos-editar&id=<?= $id ?>" class="cur-btn-enroll-main">
                            <i class="ph-bold ph-pencil-simple"></i>
                            Editar para Publicar
                        </a>
                    <?php endif; ?>

                    <!-- Características del curso -->
                    <div class="cur-enroll-features">
                        <div class="cur-feature-item">
                            <i class="ph-fill ph-monitor-play"></i>
                            100% a través de Moodle — acceso en línea
                        </div>
                        <div class="cur-feature-item">
                            <i class="ph-fill ph-clock"></i>
                            <?= $duracion ?: 'Duración flexible según tu ritmo' ?>
                        </div>
                        <div class="cur-feature-item">
                            <i class="ph-fill ph-<?= $icon_modalidad ?>"></i>
                            Modalidad: <?= $modalidad ?>
                        </div>
                        <div class="cur-feature-item">
                            <i class="ph-fill ph-certificate"></i>
                            Certificado oficial de aprobación
                        </div>
                        <?php if ($cupo_maximo > 0): ?>
                        <div class="cur-feature-item">
                            <i class="ph-fill ph-users"></i>
                            Cupo máximo: <?= $cupo_maximo ?> participantes
                        </div>
                        <?php endif; ?>
                        <div class="cur-feature-item">
                            <i class="ph-fill ph-medal"></i>
                            Nota mínima de aprobación: <?= $nota ?>/100
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card docente -->
            <div class="cur-docente-card">
                <div class="cur-docente-avatar" aria-hidden="true"><?= $iniciales ?: '?' ?></div>
                <div class="cur-docente-info">
                    <span>Docente Responsable</span>
                    <strong><?= $docente ?></strong>
                </div>
            </div>

            <!-- Acciones admin -->
            <?php if ($puede_gestionar): ?>
            <div class="cur-admin-actions-card">
                <h4><i class="ph-fill ph-shield-check"></i> Panel de Gestión</h4>
                <div class="cur-admin-btns">
                    <a href="?ruta=cursos-editar&id=<?= $id ?>" class="cur-admin-btn cur-admin-btn--edit">
                        <i class="ph-bold ph-pencil-simple"></i> Editar
                    </a>
                    <?php if ($puede_eliminar): ?>
                    <button
                        type="button"
                        class="cur-admin-btn cur-admin-btn--del"
                        onclick="abrirModalEliminar(<?= $id ?>, '<?= addslashes($titulo) ?>')"
                    >
                        <i class="ph-bold ph-trash"></i> Eliminar
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

        </aside>

    </div><!-- /.cur-detalle-layout -->

</div><!-- /.cur-detalle-wrapper -->

<!-- ===== MODAL ELIMINAR ===== -->
<?php if ($puede_eliminar): ?>
<div id="modal-eliminar" class="cur-modal-overlay" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="modal-titulo">
    <div class="cur-modal">
        <div class="cur-modal-icon">
            <i class="ph-fill ph-warning"></i>
        </div>
        <h3 id="modal-titulo">¿Eliminar este curso?</h3>
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
window.abrirModalEliminar = function(id, titulo) {
    document.getElementById('modal-id-curso').value = id;
    document.getElementById('modal-titulo').textContent = '¿Eliminar "' + titulo + '"?';
    var overlay = document.getElementById('modal-eliminar');
    overlay.style.display = 'flex';
};
window.cerrarModal = function() {
    document.getElementById('modal-eliminar').style.display = 'none';
};
document.getElementById('modal-eliminar').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') cerrarModal();
});
</script>
<?php endif; ?>
