<?php
// modules/Cursos/views/detalle_curso.php
$titulo      = htmlspecialchars($curso['titulo']);
$descripcion = nl2br(htmlspecialchars($curso['descripcion'] ?? ''));
$docente     = htmlspecialchars($curso['nombre_docente'] ?? 'Sin asignar');
$modalidad   = htmlspecialchars($curso['modalidad'] ?? 'Virtual');
$nivel_c     = htmlspecialchars($curso['nivel'] ?? 'Básico');
$duracion    = htmlspecialchars($curso['duracion'] ?? '');
$estado      = $curso['estado'] ?? 'borrador';
$fecha       = date('d de M, Y', strtotime($curso['fecha_creacion']));

$f_inicio = !empty($curso['fecha_inicio']) ? date('d/m/Y', strtotime($curso['fecha_inicio'])) : null;
$f_fin    = !empty($curso['fecha_fin']) ? date('d/m/Y', strtotime($curso['fecha_fin'])) : null;
$est_insc = htmlspecialchars($curso['estado_inscripcion'] ?? 'Abierta');


$vpreview   = $curso['url_video_preview'] ?? '';

$placeholder = htmlspecialchars($config_vista['imagenes']['placeholder_url'] ?? '', ENT_QUOTES, 'UTF-8');
$img_raw = $curso['imagen_portada'] ?? '';
// DB stores 'public/uploads/cursos/x.webp' but web root IS public/, so strip 'public/'
// External URLs (http/https) are left untouched.
function curImgUrl(string $raw): string {
    if (empty($raw)) return '';
    if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) return $raw;
    return ltrim(preg_replace('#^public/#', '', $raw), '/');
}
$img = !empty($img_raw) ? htmlspecialchars(curImgUrl($img_raw)) : $placeholder;
$url_fallback = htmlspecialchars($config_vista['moodle']['url_fallback'] ?? '#', ENT_QUOTES, 'UTF-8');
$url_moodle = !empty($curso['url_moodle']) ? htmlspecialchars($curso['url_moodle']) : $url_fallback;
?>
<div class="cur-wrapper" style="max-width: 1100px; margin: 0 auto; font-family: 'Inter', sans-serif;">
    <a href="?ruta=cursos" class="cur-btn-secondary" style="margin-bottom: 2rem; border:none; padding: 0.5rem 1rem; color: #4B5563; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;"><i class="ph-bold ph-arrow-left"></i> Volver al Catálogo</a>

    <div class="cur-detail-hero" style="display: flex; gap: 3rem; background: #fff; border-radius: 24px; padding: 3rem; box-shadow: 0 20px 40px rgba(0,0,0,0.04); margin-bottom: 3rem; flex-wrap: wrap;">
        
        <div style="flex: 1; min-width: 300px;">
            <img src="<?= $img ?>" alt="<?= $titulo ?>" style="width: 100%; border-radius: 16px; object-fit: cover; aspect-ratio: 4/3; box-shadow: 0 10px 20px rgba(0,0,0,0.08);" onerror="this.src='<?= $placeholder ?>'">
        </div>
        
        <div class="cur-detail-info" style="flex: 1.5; min-width: 350px; display: flex; flex-direction: column; justify-content: center;">
            <?php if ($estado !== 'publicado'): ?>
                <span style="align-self: flex-start; margin-bottom: 1rem; background: #FEF3C7; color: #D97706; padding: 0.4rem 1rem; border-radius: 50px; font-weight: 600; font-size: 0.85rem;"><?= ucfirst($estado) ?></span>
            <?php endif; ?>
            
            <span style="align-self: flex-start; margin-bottom: 1rem; background: #EFF6FF; color: #2563EB; padding: 0.4rem 1rem; border-radius: 50px; font-weight: 700; font-size: 0.85rem; border: 1px solid #BFDBFE;">Inscripción: <?= $est_insc ?></span>
            
            <h1 style="font-size: 2.5rem; font-weight: 800; color: #111827; margin-bottom: 1rem; line-height: 1.2;"><?= $titulo ?></h1>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem; background: #F9FAFB; padding: 1.5rem; border-radius: 12px; border: 1px solid #E5E7EB;">
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #4B5563; font-weight: 500;"><i class="ph-fill ph-chalkboard-teacher" style="color: #6366F1; font-size: 1.2rem;"></i> <?= $docente ?></div>
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #4B5563; font-weight: 500;"><i class="ph-fill ph-monitor" style="color: #6366F1; font-size: 1.2rem;"></i> <?= $modalidad ?></div>
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #4B5563; font-weight: 500;"><i class="ph-fill ph-chart-bar-horizontal" style="color: #6366F1; font-size: 1.2rem;"></i> <?= $nivel_c ?></div>
                <?php if ($duracion): ?><div style="display: flex; align-items: center; gap: 0.5rem; color: #4B5563; font-weight: 500;"><i class="ph-fill ph-clock" style="color: #6366F1; font-size: 1.2rem;"></i> <?= $duracion ?></div><?php endif; ?>
                <?php if ($f_inicio): ?><div style="display: flex; align-items: center; gap: 0.5rem; color: #4B5563; font-weight: 500;"><i class="ph-fill ph-calendar-plus" style="color: #10B981; font-size: 1.2rem;"></i> Inicia: <?= $f_inicio ?></div><?php endif; ?>
                <?php if ($f_fin): ?><div style="display: flex; align-items: center; gap: 0.5rem; color: #4B5563; font-weight: 500;"><i class="ph-fill ph-calendar-check" style="color: #EF4444; font-size: 1.2rem;"></i> Fin: <?= $f_fin ?></div><?php endif; ?>
                
            </div>

            <?php if ($estado === 'publicado'): ?>
                <?php if ($est_insc === 'Abierta'): ?>
                    <a href="<?= $url_moodle ?>" target="_blank" style="background: #4F46E5; color: white; padding: 1rem 2rem; border-radius: 12px; font-weight: 700; text-decoration: none; text-align: center; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; gap: 0.8rem; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.4); transition: transform 0.2s; display: inline-flex; width: max-content;">
                        <i class="ph-bold ph-student"></i> Ir a Moodle / Inscribirse
                    </a>
                <?php else: ?>
                    <button style="background: #E5E7EB; color: #9CA3AF; padding: 1rem 2rem; border-radius: 12px; font-weight: 700; border: none; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; gap: 0.8rem; cursor: not-allowed; width: max-content;">
                        <i class="ph-bold ph-lock"></i> <?= $est_insc ?>
                    </button>
                    <a href="<?= $url_fallback ?>" target="_blank" style="color: #4F46E5; text-decoration: none; margin-top: 1rem; font-weight: 600; display: inline-block;">Consultar información <i class="ph-bold ph-arrow-right"></i></a>
                <?php endif; ?>
            <?php else: ?>
                <button style="background: #E5E7EB; color: #9CA3AF; padding: 1rem 2rem; border-radius: 12px; font-weight: 700; border: none; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; gap: 0.8rem; cursor: not-allowed; width: max-content;"><i class="ph-bold ph-lock"></i> No disponible</button>
            <?php endif; ?>
        </div>
    </div>

    <div style="display: flex; gap: 3rem; flex-wrap: wrap;">
        <div class="cur-detail-desc" style="flex: 2; min-width: 300px; background: #fff; border-radius: 24px; padding: 3rem; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
            <h3 style="margin-bottom: 1.5rem; font-size: 1.8rem; font-weight: 800; color: #111827; border-bottom: 2px solid #F3F4F6; padding-bottom: 1rem;">Acerca de este curso</h3>
            <div style="font-size: 1.1rem; line-height: 1.8; color: #374151;">
                <?= $descripcion ?>
            </div>
        </div>

        <?php if (!empty($vpreview)): ?>
        <div style="flex: 1; min-width: 300px; background: #fff; border-radius: 24px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.03); align-self: flex-start;">
            <h3 style="margin-bottom: 1.5rem; font-size: 1.3rem; font-weight: 700; color: #111827;"><i class="ph-fill ph-video-camera" style="color: #EF4444;"></i> Video Promocional</h3>
            <?php 
            // Simple iframe embed for youtube
            $yt_id = '';
            if (preg_match('/(youtube\.com\/watch\?v=|youtu\.be\/)([^&]+)/', $vpreview, $matches)) {
                $yt_id = $matches[2];
            }
            if ($yt_id): ?>
                <iframe width="100%" height="250" src="https://www.youtube.com/embed/<?= $yt_id ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="border-radius: 12px;"></iframe>
            <?php else: ?>
                <a href="<?= htmlspecialchars($vpreview) ?>" target="_blank" style="background: #FEF2F2; color: #DC2626; padding: 1rem; border-radius: 12px; font-weight: 600; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 0.5rem; border: 1px solid #FECACA;"><i class="ph-bold ph-play-circle"></i> Ver Video</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

