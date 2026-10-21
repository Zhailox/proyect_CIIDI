<?php
// modules/Cursos/views/detalle_curso.php
$titulo      = htmlspecialchars($curso['titulo']);
$descripcion = nl2br(htmlspecialchars(trim($curso['descripcion'] ?? '')));
$descripcion = preg_replace('/^(?:<br\s*\/?>|\s)+/i', '', $descripcion);
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
            <div style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-bottom:1rem; align-items:center;">
                <?php if ($estado !== 'publicado'): ?>
                    <span style="background: #FEF3C7; color: #D97706; padding: 0.35rem 0.9rem; border-radius: 50px; font-weight: 600; font-size: 0.85rem;"><?= ucfirst($estado) ?></span>
                <?php endif; ?>
                <span style="background: #EEF2FF; color: #4F46E5; padding: 0.35rem 0.9rem; border-radius: 50px; font-weight: 700; font-size: 0.85rem; border: 1px solid #C7D2FE;">Inscripción: <?= $est_insc ?></span>
            </div>

            <h1 style="font-size: 2.3rem; font-weight: 800; color: #121a3e; margin-bottom: 1rem; line-height: 1.25;"><?= $titulo ?></h1>

            <!-- Líneas de Investigación y Dimensiones Operativas -->
            <?php 
            $cur_lineas = $curso['lineas'] ?? [];
            $cur_dims = $curso['dimensiones'] ?? [];
            ?>
            <?php if (!empty($cur_lineas) || !empty($cur_dims)): ?>
                <div style="display:flex; flex-direction:column; gap:0.6rem; margin-bottom:1.5rem; background:#F8FAFC; padding:1rem 1.2rem; border-radius:12px; border:1px solid #E2E8F0;">
                    <?php if (!empty($cur_lineas)): ?>
                        <div style="display:flex; align-items:center; gap:0.6rem; flex-wrap:wrap;">
                            <span style="font-size:0.8rem; font-weight:700; text-transform:uppercase; color:#505984; display:inline-flex; align-items:center; gap:0.3rem;">
                                <i class="ph-bold ph-tree-structure" style="color:#505984;"></i> Línea(s):
                            </span>
                            <?php foreach ($cur_lineas as $l): ?>
                                <span style="background:rgba(80,89,132,0.1); color:#505984; font-size:0.82rem; font-weight:600; padding:0.25rem 0.7rem; border-radius:20px; border:1px solid rgba(80,89,132,0.2);">
                                    <?= htmlspecialchars($l['nombre']) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($cur_dims)): ?>
                        <div style="display:flex; align-items:center; gap:0.6rem; flex-wrap:wrap;">
                            <span style="font-size:0.8rem; font-weight:700; text-transform:uppercase; color:#7090cb; display:inline-flex; align-items:center; gap:0.3rem;">
                                <i class="ph-bold ph-circles-three" style="color:#7090cb;"></i> Dimensión(es):
                            </span>
                            <?php foreach ($cur_dims as $d): ?>
                                <span style="background:rgba(112,144,203,0.12); color:#2c4c87; font-size:0.82rem; font-weight:600; padding:0.25rem 0.7rem; border-radius:20px; border:1px solid rgba(112,144,203,0.25);">
                                    <?= htmlspecialchars($d['nombre']) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem; background: #F9FAFB; padding: 1.5rem; border-radius: 12px; border: 1px solid #E5E7EB;">
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #4B5563; font-weight: 500;"><i class="ph-fill ph-chalkboard-teacher" style="color: #505984; font-size: 1.2rem;"></i> <?= $docente ?></div>
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #4B5563; font-weight: 500;"><i class="ph-fill ph-monitor" style="color: #505984; font-size: 1.2rem;"></i> <?= $modalidad ?></div>
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #4B5563; font-weight: 500;"><i class="ph-fill ph-chart-bar-horizontal" style="color: #505984; font-size: 1.2rem;"></i> <?= $nivel_c ?></div>
                <?php if ($duracion): ?><div style="display: flex; align-items: center; gap: 0.5rem; color: #4B5563; font-weight: 500;"><i class="ph-fill ph-clock" style="color: #505984; font-size: 1.2rem;"></i> <?= $duracion ?></div><?php endif; ?>
                <?php if ($f_inicio): ?><div style="display: flex; align-items: center; gap: 0.5rem; color: #4B5563; font-weight: 500;"><i class="ph-fill ph-calendar-plus" style="color: #10B981; font-size: 1.2rem;"></i> Inicia: <?= $f_inicio ?></div><?php endif; ?>
                <?php if ($f_fin): ?><div style="display: flex; align-items: center; gap: 0.5rem; color: #4B5563; font-weight: 500;"><i class="ph-fill ph-calendar-check" style="color: #EF4444; font-size: 1.2rem;"></i> Fin: <?= $f_fin ?></div><?php endif; ?>
            </div>

            <?php if ($estado === 'publicado'): ?>
                <div style="display: flex; gap: 0.8rem; flex-wrap: wrap; align-items: center;">
                    <?php if ($est_insc === 'Abierta'): ?>
                        <!-- Formulario de Google (Vista Separada / Oficial) -->
                        <a href="?ruta=cursos-formulario-google&curso_id=<?= $curso['id'] ?>" style="background: #505984; color: white; padding: 0.9rem 1.8rem; border-radius: 12px; font-weight: 700; text-decoration: none; text-align: center; font-size: 1.05rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.6rem; box-shadow: 0 4px 14px rgba(80, 89, 132, 0.25); transition: all 0.2s;" onmouseover="this.style.background='#3C456A'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='#505984'; this.style.transform='translateY(0)';">
                            <i class="ph-bold ph-google-logo" style="font-size:1.3rem;"></i> Inscribirme (Formulario Google)
                        </a>

                        <?php if (!empty($curso['url_moodle'])): ?>
                            <a href="<?= $url_moodle ?>" target="_blank" style="background: #121a3e; color: white; padding: 0.9rem 1.6rem; border-radius: 12px; font-weight: 700; text-decoration: none; text-align: center; font-size: 1.05rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.6rem; box-shadow: 0 4px 14px rgba(18, 26, 62, 0.25); transition: transform 0.2s;">
                                <i class="ph-bold ph-student"></i> Aula Virtual
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <button style="background: #E5E7EB; color: #9CA3AF; padding: 0.9rem 1.8rem; border-radius: 12px; font-weight: 700; border: none; font-size: 1.05rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.6rem; cursor: not-allowed;">
                            <i class="ph-bold ph-lock"></i> Inscripciones <?= $est_insc ?>
                        </button>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <button style="background: #E5E7EB; color: #9CA3AF; padding: 1rem 2rem; border-radius: 12px; font-weight: 700; border: none; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; gap: 0.8rem; cursor: not-allowed; width: max-content;"><i class="ph-bold ph-lock"></i> No disponible</button>
            <?php endif; ?>
        </div>
    </div>

    <div style="display: flex; gap: 3rem; flex-wrap: wrap;">
        <div class="cur-detail-desc" style="flex: 2; min-width: 300px; background: #fff; border-radius: 24px; padding: 3rem; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
            <h3 style="margin: 0; font-size: 1.8rem; font-weight: 800; color: #111827; border-bottom: 2px solid #F3F4F6; padding-bottom: 0.2rem;">Acerca de este curso</h3>
            <div style="font-size: 1.1rem; line-height: 1.5; color: #374151; margin-top: 0.2rem; padding-top: 0;"><?= $descripcion ?></div>
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

