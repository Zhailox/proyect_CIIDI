<?php
// modules/Cursos/views/showcase_cursos.php
$busqueda     = htmlspecialchars($filtros['busqueda'] ?? '', ENT_QUOTES, 'UTF-8');
$f_modalidad  = htmlspecialchars($filtros['modalidad'] ?? '', ENT_QUOTES, 'UTF-8');
$f_nivel      = htmlspecialchars($filtros['nivel'] ?? '', ENT_QUOTES, 'UTF-8');
$lazy_attr    = !empty($config_vista['imagenes']['lazy_load']) ? 'lazy' : 'eager';
$placeholder  = htmlspecialchars($config_vista['imagenes']['placeholder_url'] ?? '', ENT_QUOTES, 'UTF-8');
$url_fallback = htmlspecialchars($config_vista['moodle']['url_fallback']      ?? '#', ENT_QUOTES, 'UTF-8');

// DB stores 'public/uploads/cursos/x.webp' but web root IS public/, so strip 'public/'
if (!function_exists('curImgUrl')) {
    function curImgUrl(string $raw): string {
        if (empty($raw)) return '';
        if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) return $raw;
        return ltrim(preg_replace('#^public/#', '', $raw), '/');
    }
}

$pag           = $paginacion ?? ['pagina_actual' => 1, 'total_paginas' => 1, 'total' => 0, 'por_pagina' => 9];
$pagina_actual = (int)$pag['pagina_actual'];
$total_paginas = (int)$pag['total_paginas'];
$total_cursos  = (int)$pag['total'];

$lineas        = $lineas ?? [];
$f_linea       = (int)($filtros['id_linea'] ?? ($_GET['linea'] ?? 0));

// Helper for url pagination
$url_params = "";
if ($busqueda) $url_params .= "&busqueda=$busqueda";
if ($f_modalidad) $url_params .= "&modalidad=$f_modalidad";
if ($f_nivel) $url_params .= "&nivel=$f_nivel";
if ($f_linea) $url_params .= "&linea=$f_linea";
?>
<div style="padding: 1.5rem 2.2rem 3.5rem 2.2rem; width: 100%; box-sizing: border-box;">
    <section class="landing-hero-modern" style="margin-bottom: 3rem; border-radius: 32px; overflow: hidden; box-shadow: 0 20px 40px rgba(80,89,132,0.25); background: linear-gradient(135deg, rgba(80,89,132,0.97) 0%, rgba(112,144,203,0.93) 100%); border: none;">
        <canvas id="landingCanvasBg" class="landing-hero-canvas"></canvas>

        <div class="hero-text-content">
            <div class="hero-badge-glass" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white;">
                <i class="ph-fill ph-graduation-cap"></i> Oferta Formativa Continua
            </div>
            <h1 style="color: white;">Formación de <span style="background: linear-gradient(to right, #93C5FD, #E0E7FF); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Alto Impacto</span></h1>
            <p style="margin-bottom: 2rem; color: #DBEAFE;">Potencia tus habilidades con cursos especializados impartidos por nuestros expertos. Aprende a tu ritmo y certifica tu conocimiento.</p>
            
            <form method="GET" action="" class="cur-search-bar" style="background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255,255,255,0.25); border-radius: 50px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width:100%; max-width: 500px; display:flex; align-items:center; padding: 0.5rem 0.5rem 0.5rem 1.5rem;">
                <input type="hidden" name="ruta" value="cursos">
                <input type="search" name="busqueda" value="<?= $busqueda ?>" placeholder="Buscar diplomados, cursos o temas..." autocomplete="off" style="background:transparent; border:none; color:white; flex:1; outline:none; font-size:1.1rem;">
                <button type="submit" style="background: white; color: #505984; border: none; border-radius: 50px; width: 46px; height: 46px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;"><i class="ph-bold ph-magnifying-glass"></i></button>
            </form>
        </div>

        <div class="hero-graphic-spatial">
            <div class="hero-img-wrapper" style="max-width: 450px;">
                <img src="https://images.unsplash.com/photo-1501504905252-473c47e087f8?auto=format&fit=crop&q=80&w=800" alt="Formación" class="landing-hero-img-spatial" style="border-radius: 20px;">
                
                <div class="floating-glass-card card-top-left" style="animation-delay: 0.5s; background: rgba(255,255,255,0.9); border: none;">
                    <div class="floating-icon" style="color: #10B981; background: #D1FAE5;">
                        <i class="ph-bold ph-chalkboard-teacher"></i>
                    </div>
                    <div class="floating-info">
                        <strong style="color: #0F172A;">Docentes Especializados</strong>
                        <span style="color: #64748B;">Formación Práctica</span>
                    </div>
                </div>

                <div class="floating-glass-card card-bottom-right" style="animation-delay: 1s; background: rgba(255,255,255,0.9); border: none;">
                    <div class="floating-icon" style="color: #F59E0B; background: #FEF3C7;">
                        <i class="ph-bold ph-certificate"></i>
                    </div>
                    <div class="floating-info">
                        <strong style="color: #0F172A;">Integración LMS</strong>
                        <span style="color: #64748B;">Acceso Moodle Institucional</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="cur-wrapper" style="max-width: 1300px; margin: 0 auto;">
        <!-- BARRA DE FILTROS HORIZONTAL -->
        <div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.03); border: 1px solid #F3F4F6; margin-bottom: 2rem;">
        <form method="GET" action="" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
            <input type="hidden" name="ruta" value="cursos">
            <?php if ($busqueda): ?><input type="hidden" name="busqueda" value="<?= $busqueda ?>"><?php endif; ?>
            
            <div style="flex: 1.2; min-width: 220px;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;"><i class="ph-bold ph-git-branch" style="color: #505984;"></i> Línea de Investigación</label>
                <select name="linea" style="width: 100%; padding: 0.8rem; border: 1px solid #E5E7EB; border-radius: 10px; outline: none; background: #fff;">
                    <option value="">Todas las Líneas</option>
                    <?php foreach ($lineas as $l): ?>
                        <option value="<?= $l['id'] ?>" <?= $f_linea == $l['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($l['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="flex: 1; min-width: 180px;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;"><i class="ph-bold ph-monitor" style="color: #505984;"></i> Modalidad</label>
                <select name="modalidad" style="width: 100%; padding: 0.8rem; border: 1px solid #E5E7EB; border-radius: 10px; outline: none; background: #fff;">
                    <option value="">Todas</option>
                    <option value="Virtual" <?= $f_modalidad == 'Virtual' ? 'selected' : '' ?>>Virtual</option>
                    <option value="Presencial" <?= $f_modalidad == 'Presencial' ? 'selected' : '' ?>>Presencial</option>
                    <option value="Híbrido" <?= $f_modalidad == 'Híbrido' ? 'selected' : '' ?>>Híbrido / Semipresencial</option>
                </select>
            </div>

            <div style="flex: 1; min-width: 180px;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;"><i class="ph-bold ph-chart-bar" style="color: #505984;"></i> Nivel</label>
                <select name="nivel" style="width: 100%; padding: 0.8rem; border: 1px solid #E5E7EB; border-radius: 10px; outline: none; background: #fff;">
                    <option value="">Todos</option>
                    <option value="Básico" <?= $f_nivel == 'Básico' ? 'selected' : '' ?>>Básico</option>
                    <option value="Intermedio" <?= $f_nivel == 'Intermedio' ? 'selected' : '' ?>>Intermedio</option>
                    <option value="Avanzado" <?= $f_nivel == 'Avanzado' ? 'selected' : '' ?>>Avanzado</option>
                </select>
            </div>

            <div>
                <button type="submit" style="padding: 0.8rem 1.8rem; background: #505984; color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: background 0.2s; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="ph-bold ph-funnel"></i> Filtrar
                </button>
            </div>
            
            <?php if ($busqueda || $f_modalidad || $f_nivel || $f_linea): ?>
            <div>
                <a href="?ruta=cursos" style="padding: 0.8rem 1.2rem; background: #FEE2E2; color: #EF4444; text-decoration: none; border-radius: 10px; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; transition: background 0.2s;"><i class="ph-bold ph-x-circle"></i> Limpiar</a>
            </div>
            <?php endif; ?>

            <div style="margin-left: auto;">
                <a href="?ruta=cursos-formulario-google" style="padding: 0.8rem 1.6rem; background: #505984; color: #ffffff; text-decoration: none; border-radius: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.6rem; transition: all 0.2s; box-shadow: 0 4px 12px rgba(80, 89, 132, 0.25); border: none;" onmouseover="this.style.background='#3C456A'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='#505984'; this.style.transform='translateY(0)';">
                    <i class="ph-bold ph-google-logo" style="color: #ffffff; font-size: 1.1rem;"></i> Inscripción Oficial (Google Form)
                </a>
            </div>
        </form>
    </div>

    <div>
        <?php if ($busqueda || $f_modalidad || $f_nivel || $f_linea): ?>
        <div style="margin-bottom: 2rem; color: var(--cur-muted); display:flex; justify-content:space-between; align-items:center; background:var(--cur-white); padding:1rem 1.5rem; border-radius:12px; box-shadow:var(--cur-shadow-sm);">
            <span>Resultados de búsqueda (<?= $total_cursos ?> encontrados)</span>
        </div>
        <?php endif; ?>

        <main class="cur-grid" style="grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.8rem;">
                <?php if (empty($cursos)): ?>
                    <div class="cur-empty" style="grid-column: 1 / -1; padding: 6rem 2rem; text-align: center; background: white; border-radius: 20px; border: 1px solid #E2E8F0;">
                        <div style="width:90px; height:90px; background:var(--cur-primary-light); color:var(--cur-primary); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:3.5rem; margin:0 auto 1.5rem;">
                            <i class="ph-fill ph-graduation-cap"></i>
                        </div>
                        <h3 style="color: var(--cur-dark); font-size: 1.4rem; font-weight: 800; margin-bottom: 0.5rem;">No se encontraron cursos</h3>
                        <p style="color: var(--cur-muted); font-size: 1rem;">Prueba con otros términos o cambia la línea de investigación / modalidad seleccionada.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($cursos as $curso): 
                        $titulo = htmlspecialchars($curso['titulo']);
                        $desc   = htmlspecialchars(mb_strimwidth($curso['descripcion'] ?? '', 0, 110, '…'));
                        $docente= htmlspecialchars($curso['nombre_docente'] ?? 'Docente Asignado');
                        $duracion = htmlspecialchars($curso['duracion'] ?? '');
                        $modalidad = htmlspecialchars($curso['modalidad'] ?? 'Virtual');
                        $est_insc = htmlspecialchars($curso['estado_inscripcion'] ?? 'Abierta');
                        $slug = !empty($curso['slug']) ? htmlspecialchars($curso['slug']) : (string)$curso['id'];
                        $img_raw = $curso['imagen_portada'] ?? '';
                        $img = !empty($img_raw) ? htmlspecialchars(curImgUrl($img_raw)) : $placeholder;
                        $icon_mod = match(strtolower($modalidad)) {
                            'presencial' => 'ph-map-pin', 'híbrido' => 'ph-arrows-split', default => 'ph-monitor'
                        };
                        $color_est = match(strtolower($est_insc)) {
                            'abierta' => '#10B981', 'próximamente' => '#F59E0B', 'en curso' => '#3B82F6', default => '#EF4444'
                        };
                        $lineasCurso = $curso['lineas'] ?? [];
                        $dimsCurso   = $curso['dimensiones'] ?? [];
                    ?>
                    <div class="cur-card" style="display: flex; flex-direction: column; background: #fff; border: 1px solid #E2E8F0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.04); transition: transform 0.25s, box-shadow 0.25s;">
                        <a href="?ruta=cursos-detalle&slug=<?= $slug ?>" style="text-decoration: none; color: inherit;">
                            <div class="cur-card-img-wrap" style="position: relative; height: 180px; overflow: hidden; background: #0F172A;">
                                <img src="<?= $img ?>" alt="<?= $titulo ?>" loading="<?= $lazy_attr ?>" onerror="this.src='<?= $placeholder ?>'" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;">
                                <span class="cur-badge" style="position: absolute; top: 12px; left: 12px; background: rgba(18, 26, 62, 0.85); color: #fff; padding: 4px 10px; border-radius: 50px; font-size: 0.78rem; font-weight: 700; backdrop-filter: blur(4px);">
                                    <i class="ph-fill <?= $icon_mod ?>"></i> <?= $modalidad ?>
                                </span>
                            </div>
                        </a>
                        <div class="cur-card-content" style="padding: 1.3rem; display: flex; flex-direction: column; flex: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.6rem;">
                                <span style="font-size: 0.73rem; font-weight: 800; color: <?= $color_est ?>; background: <?= $color_est ?>15; padding: 0.2rem 0.6rem; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <?= $est_insc ?>
                                </span>
                                <?php if ($duracion): ?>
                                    <span style="font-size: 0.78rem; color: #64748B; font-weight: 600;"><i class="ph-fill ph-clock"></i> <?= $duracion ?></span>
                                <?php endif; ?>
                            </div>

                            <a href="?ruta=cursos-detalle&slug=<?= $slug ?>" style="text-decoration: none; color: inherit;">
                                <h3 class="cur-card-title" style="font-size: 1.15rem; font-weight: 800; color: #0F172A; margin: 0 0 0.5rem 0; line-height: 1.3;"><?= $titulo ?></h3>
                            </a>
                            <p class="cur-card-desc" style="color: #64748B; font-size: 0.88rem; line-height: 1.45; margin: 0 0 1rem 0;"><?= $desc ?></p>

                            <!-- LÍNEAS DE INVESTIGACIÓN ASOCIADAS (VARIAS) -->
                            <?php if (!empty($lineasCurso)): ?>
                            <div style="margin-bottom: 0.6rem;">
                                <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: #64748B; margin-bottom: 0.3rem;">Líneas Asociadas:</div>
                                <div style="display: flex; flex-wrap: wrap; gap: 0.35rem;">
                                    <?php foreach (array_slice($lineasCurso, 0, 2) as $lin): ?>
                                        <span style="background: rgba(80,89,132,0.1); color: #505984; font-size: 0.72rem; font-weight: 700; padding: 0.2rem 0.55rem; border-radius: 50px; display: inline-flex; align-items: center; gap: 0.25rem;">
                                            <i class="ph-bold ph-git-branch"></i> <?= htmlspecialchars($lin['nombre']) ?>
                                        </span>
                                    <?php endforeach; ?>
                                    <?php if (count($lineasCurso) > 2): ?>
                                        <span style="background: #F1F5F9; color: #64748B; font-size: 0.72rem; font-weight: 700; padding: 0.2rem 0.45rem; border-radius: 50px;">
                                            +<?= count($lineasCurso) - 2 ?> más
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- DIMENSIONES OPERATIVAS ASOCIADAS (VARIAS) -->
                            <?php if (!empty($dimsCurso)): ?>
                            <div style="margin-bottom: 1rem;">
                                <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: #64748B; margin-bottom: 0.3rem;">Dimensiones Operativas:</div>
                                <div style="display: flex; flex-wrap: wrap; gap: 0.3rem;">
                                    <?php foreach (array_slice($dimsCurso, 0, 2) as $dim): ?>
                                        <span style="background: #F8FAFC; border: 1px solid #E2E8F0; color: #475569; font-size: 0.7rem; font-weight: 600; padding: 0.15rem 0.5rem; border-radius: 4px; display: inline-flex; align-items: center; gap: 0.2rem;">
                                            <i class="ph-bold ph-circles-three"></i> <?= htmlspecialchars($dim['nombre']) ?>
                                        </span>
                                    <?php endforeach; ?>
                                    <?php if (count($dimsCurso) > 2): ?>
                                        <span style="color: #94A3B8; font-size: 0.7rem; font-weight: 600;">+<?= count($dimsCurso) - 2 ?> dim.</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- FOOTER CON DOCENTE Y BOTÓN GOOGLE FORM -->
                            <div class="cur-card-footer" style="margin-top: auto; padding-top: 0.9rem; border-top: 1px solid #F1F5F9; display: flex; justify-content: space-between; align-items: center; gap: 0.5rem;">
                                <span class="cur-docente" style="font-size: 0.82rem; color: #475569; font-weight: 600; display: inline-flex; align-items: center; gap: 0.3rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 140px;">
                                    <i class="ph-fill ph-chalkboard-teacher" style="color: #505984;"></i> <?= $docente ?>
                                </span>
                                
                                <a href="?ruta=cursos-formulario-google&curso_id=<?= $curso['id'] ?>" style="background: #505984; color: #fff; padding: 0.45rem 0.9rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.3rem; transition: background 0.2s;" onmouseover="this.style.background='#3C456A';" onmouseout="this.style.background='#505984';">
                                    <i class="ph-bold ph-google-logo"></i> Inscribirme
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </main>

            <!-- Pagination -->
            <?php if ($total_paginas > 1): ?>
            <nav class="cur-pagination" style="margin-top: 3rem;">
                <div class="cur-pag-controls">
                    <?php if ($pagina_actual > 1): ?>
                        <a href="?ruta=cursos&pagina=<?= $pagina_actual - 1 ?><?= $url_params ?>" class="cur-pag-btn"><i class="ph-bold ph-caret-left"></i></a>
                    <?php endif; ?>
                    
                    <?php for ($p = max(1, $pagina_actual - 2); $p <= min($total_paginas, $pagina_actual + 2); $p++): ?>
                        <a href="?ruta=cursos&pagina=<?= $p ?><?= $url_params ?>" class="cur-pag-btn <?= $p === $pagina_actual ? 'cur-pag-btn--active' : '' ?>"><?= $p ?></a>
                    <?php endfor; ?>

                    <?php if ($pagina_actual < $total_paginas): ?>
                        <a href="?ruta=cursos&pagina=<?= $pagina_actual + 1 ?><?= $url_params ?>" class="cur-pag-btn"><i class="ph-bold ph-caret-right"></i></a>
                    <?php endif; ?>
                </div>
            </nav>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>

<!-- Script animacion canvas del inicio -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const canvas = document.getElementById("landingCanvasBg");
    if(!canvas) return;
    const ctx = canvas.getContext("2d");
    let width, height, particles;
    function resize() {
        width = canvas.width = canvas.offsetWidth;
        height = canvas.height = canvas.offsetHeight;
    }
    window.addEventListener("resize", resize);
    resize();
    particles = Array.from({length: 40}, () => ({
        x: Math.random() * width,
        y: Math.random() * height,
        r: Math.random() * 2 + 1,
        vx: (Math.random() - 0.5) * 0.5,
        vy: (Math.random() - 0.5) * 0.5
    }));
    function draw() {
        ctx.clearRect(0, 0, width, height);
        ctx.fillStyle = "rgba(255, 255, 255, 0.4)";
        particles.forEach(p => {
            p.x += p.vx; p.y += p.vy;
            if(p.x < 0 || p.x > width) p.vx *= -1;
            if(p.y < 0 || p.y > height) p.vy *= -1;
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fill();
        });
        requestAnimationFrame(draw);
    }
    draw();
});
</script>

