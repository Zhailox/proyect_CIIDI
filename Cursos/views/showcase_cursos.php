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

// Helper for url pagination
$url_params = "";
if ($busqueda) $url_params .= "&busqueda=$busqueda";
if ($f_modalidad) $url_params .= "&modalidad=$f_modalidad";
if ($f_nivel) $url_params .= "&nivel=$f_nivel";
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
            
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;"><i class="ph-bold ph-monitor" style="color: #4F46E5;"></i> Modalidad</label>
                <select name="modalidad" style="width: 100%; padding: 0.8rem; border: 1px solid #E5E7EB; border-radius: 10px; outline: none;">
                    <option value="">Todas</option>
                    <option value="Virtual" <?= $f_modalidad == 'Virtual' ? 'selected' : '' ?>>Virtual</option>
                    <option value="Presencial" <?= $f_modalidad == 'Presencial' ? 'selected' : '' ?>>Presencial</option>
                    <option value="Híbrido" <?= $f_modalidad == 'Híbrido' ? 'selected' : '' ?>>Híbrido / Semipresencial</option>
                </select>
            </div>

            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;"><i class="ph-bold ph-chart-bar" style="color: #4F46E5;"></i> Nivel</label>
                <select name="nivel" style="width: 100%; padding: 0.8rem; border: 1px solid #E5E7EB; border-radius: 10px; outline: none;">
                    <option value="">Todos</option>
                    <option value="Básico" <?= $f_nivel == 'Básico' ? 'selected' : '' ?>>Básico</option>
                    <option value="Intermedio" <?= $f_nivel == 'Intermedio' ? 'selected' : '' ?>>Intermedio</option>
                    <option value="Avanzado" <?= $f_nivel == 'Avanzado' ? 'selected' : '' ?>>Avanzado</option>
                </select>
            </div>

            <div>
                <button type="submit" style="padding: 0.8rem 2rem; background: #4F46E5; color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: background 0.2s; display: flex; align-items: center; gap: 0.5rem;"><i class="ph-bold ph-funnel"></i> Filtrar</button>
            </div>
            
            <?php if ($busqueda || $f_modalidad || $f_nivel): ?>
            <div>
                <a href="?ruta=cursos" style="padding: 0.8rem 1.5rem; background: #FEE2E2; color: #EF4444; text-decoration: none; border-radius: 10px; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; transition: background 0.2s;"><i class="ph-bold ph-x-circle"></i> Limpiar Todo</a>
            </div>
            <?php endif; ?>
        </form>
    </div>

    <div>
        <?php if ($busqueda || $f_modalidad || $f_nivel): ?>
        <div style="margin-bottom: 2rem; color: var(--cur-muted); display:flex; justify-content:space-between; align-items:center; background:var(--cur-white); padding:1rem 1.5rem; border-radius:12px; box-shadow:var(--cur-shadow-sm);">
            <span>Resultados (<?= $total_cursos ?> encontrados)</span>
        </div>
        <?php endif; ?>

        <main class="cur-grid" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                <?php if (empty($cursos)): ?>
                    <div class="cur-empty" style="grid-column: 1 / -1; padding: 6rem 2rem;">
                        <div style="width:100px; height:100px; background:var(--cur-primary-light); color:var(--cur-primary); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:4rem; margin:0 auto 1.5rem;">
                            <i class="ph-fill ph-graduation-cap"></i>
                        </div>
                        <h3>Sin resultados</h3>
                        <p>Prueba con otros términos o cambia los filtros de búsqueda.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($cursos as $curso): 
                        $titulo = htmlspecialchars($curso['titulo']);
                        $desc   = htmlspecialchars(mb_strimwidth($curso['descripcion'] ?? '', 0, 110, '…'));
                        $docente= htmlspecialchars($curso['nombre_docente'] ?? 'Docente');
                        $duracion = htmlspecialchars($curso['duracion'] ?? '');
                        $modalidad = htmlspecialchars($curso['modalidad'] ?? 'Virtual');
                        $est_insc = htmlspecialchars($curso['estado_inscripcion'] ?? 'Abierta');
                        $slug = htmlspecialchars($curso['slug'] ?? $curso['id']);
                        $img_raw = $curso['imagen_portada'] ?? '';
                        $img = !empty($img_raw) ? htmlspecialchars(curImgUrl($img_raw)) : $placeholder;
                        $icon_mod = match(strtolower($modalidad)) {
                            'presencial' => 'ph-map-pin', 'híbrido' => 'ph-arrows-split', default => 'ph-monitor'
                        };
                        $color_est = match(strtolower($est_insc)) {
                            'abierta' => '#10B981', 'próximamente' => '#F59E0B', 'en curso' => '#3B82F6', default => '#EF4444'
                        };
                    ?>
                    <a href="?ruta=cursos-detalle&slug=<?= $slug ?>" class="cur-card">
                        <div class="cur-card-img-wrap">
                            <img src="<?= $img ?>" alt="<?= $titulo ?>" loading="<?= $lazy_attr ?>" onerror="this.src='<?= $placeholder ?>'">
                            <span class="cur-badge"><i class="ph-fill <?= $icon_mod ?>"></i> <?= $modalidad ?></span>
                        </div>
                        <div class="cur-card-content">
                            <span style="display:inline-block; margin-bottom: 0.5rem; font-size: 0.75rem; font-weight: 700; color: <?= $color_est ?>; background: <?= $color_est ?>15; padding: 0.2rem 0.6rem; border-radius: 4px; text-transform: uppercase;">
                                <?= $est_insc ?>
                            </span>
                            <h3 class="cur-card-title"><?= $titulo ?></h3>
                            <p class="cur-card-desc"><?= $desc ?></p>
                            <div class="cur-card-footer">
                                <span class="cur-docente"><i class="ph-fill ph-chalkboard-teacher"></i> <?= $docente ?></span>
                                <?php if ($duracion): ?>
                                    <span class="cur-duracion"><i class="ph-fill ph-clock"></i> <?= $duracion ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
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

