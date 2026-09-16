<?php
// modules/Cursos/views/detalle_curso.php
$titulo      = htmlspecialchars($curso['titulo']);
$descripcion = htmlspecialchars($curso['descripcion'] ?? '');
$docente     = htmlspecialchars($curso['nombre_docente'] ?? 'Sin asignar');
$modalidad   = htmlspecialchars($curso['modalidad'] ?? 'Virtual');
$nivel_c     = htmlspecialchars($curso['nivel'] ?? 'Básico');
$duracion    = htmlspecialchars($curso['duracion'] ?? '');
$estado      = $curso['estado'] ?? 'borrador';
$fecha       = date('d de M, Y', strtotime($curso['fecha_creacion']));

$placeholder = htmlspecialchars($config_vista['imagenes']['placeholder_url'] ?? '', ENT_QUOTES, 'UTF-8');
$img = !empty($curso['imagen_portada']) ? htmlspecialchars($curso['imagen_portada']) : $placeholder;
$url_fallback = htmlspecialchars($config_vista['moodle']['url_fallback'] ?? '#', ENT_QUOTES, 'UTF-8');
$url_moodle = !empty($curso['url_moodle']) ? htmlspecialchars($curso['url_moodle']) : $url_fallback;
?>
<div class="cur-wrapper">
    <a href="?ruta=cursos" class="cur-btn-secondary" style="margin-bottom: 2rem; border:none; padding: 0.5rem 1rem;"><i class="ph-bold ph-arrow-left"></i> Volver al Catálogo</a>

    <div class="cur-detail-hero">
        <img src="<?= $img ?>" alt="<?= $titulo ?>" class="cur-detail-img" onerror="this.src='<?= $placeholder ?>'">
        
        <div class="cur-detail-info">
            <?php if ($estado !== 'publicado'): ?>
                <span class="cur-status-pill cur-status-<?= $estado ?>" style="align-self: flex-start; margin-bottom: 1rem;"><?= ucfirst($estado) ?></span>
            <?php endif; ?>
            <h1 class="cur-detail-title"><?= $titulo ?></h1>
            <div class="cur-detail-meta">
                <span><i class="ph-fill ph-chalkboard-teacher"></i> <?= $docente ?></span>
                <span><i class="ph-fill ph-monitor"></i> <?= $modalidad ?></span>
                <?php if ($nivel_c): ?><span><i class="ph-fill ph-chart-bar-horizontal"></i> <?= $nivel_c ?></span><?php endif; ?>
                <?php if ($duracion): ?><span><i class="ph-fill ph-clock"></i> <?= $duracion ?></span><?php endif; ?>
                <span><i class="ph-fill ph-calendar-blank"></i> Publicado: <?= $fecha ?></span>
            </div>

            <?php if ($estado === 'publicado'): ?>
                <a href="<?= $url_moodle ?>" target="_blank" class="cur-detail-btn">
                    <i class="ph-bold ph-graduation-cap"></i> Ir al Curso en Moodle <i class="ph-bold ph-arrow-up-right"></i>
                </a>
            <?php else: ?>
                <button class="cur-detail-btn" style="opacity: 0.6; cursor: not-allowed;"><i class="ph-bold ph-lock"></i> No disponible</button>
            <?php endif; ?>
        </div>
    </div>

    <div class="cur-detail-desc">
        <h3 style="margin-bottom: 1.5rem; font-size: 1.5rem; font-weight: 700; color: var(--cur-dark);">Acerca de este curso</h3>
        <?= $descripcion ?>
    </div>
</div>