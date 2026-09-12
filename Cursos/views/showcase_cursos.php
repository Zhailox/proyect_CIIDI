<?php
// modules/Cursos/views/showcase_cursos.php
$busqueda     = htmlspecialchars($filtros['busqueda'] ?? '', ENT_QUOTES, 'UTF-8');
$lazy_attr    = !empty($config_vista['imagenes']['lazy_load']) ? 'lazy' : 'eager';
$placeholder  = htmlspecialchars($config_vista['imagenes']['placeholder_url'] ?? '', ENT_QUOTES, 'UTF-8');
$url_fallback = htmlspecialchars($config_vista['moodle']['url_fallback']      ?? '#', ENT_QUOTES, 'UTF-8');

$pag           = $paginacion ?? ['pagina_actual' => 1, 'total_paginas' => 1, 'total' => 0, 'por_pagina' => 9];
$pagina_actual = (int)$pag['pagina_actual'];
$total_paginas = (int)$pag['total_paginas'];
$total_cursos  = (int)$pag['total'];
?>
<div class="cur-wrapper">
    <section class="landing-hero-modern" style="margin-bottom: 4rem; border-radius: 32px; overflow: hidden; box-shadow: 0 20px 40px rgba(29, 78, 216, 0.2); background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%); border: none;">
        <canvas id="landingCanvasBg" class="landing-hero-canvas"></canvas>

        <div class="hero-text-content">
            <div class="hero-badge-glass" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white;">
                <i class="ph-fill ph-graduation-cap"></i> Oferta Formativa Continua
            </div>
            <h1 style="color: white;">Formación de <span style="background: linear-gradient(to right, #93C5FD, #E0E7FF); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Alto Impacto</span></h1>
            <p style="margin-bottom: 2rem; color: #DBEAFE;">Potencia tus habilidades con cursos especializados impartidos por nuestros expertos. Aprende a tu ritmo y certifica tu conocimiento en la plataforma educativa UPTTMBI.</p>
            
            <form method="GET" action="" class="cur-search-bar" style="background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255,255,255,0.25); border-radius: 50px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width:100%; max-width: 500px; display:flex; align-items:center; padding: 0.5rem 0.5rem 0.5rem 1.5rem;">
                <input type="hidden" name="ruta" value="cursos">
                <input type="search" name="busqueda" value="<?= $busqueda ?>" placeholder="Buscar diplomados, cursos o temas..." autocomplete="off" style="background:transparent; border:none; color:white; flex:1; outline:none; font-size:1.1rem;">
                <button type="submit" style="background: white; color: #1D4ED8; border: none; border-radius: 50px; width: 46px; height: 46px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;"><i class="ph-bold ph-magnifying-glass"></i></button>
            </form>
        </div>

        <div class="hero-graphic-spatial">
            <div class="hero-img-wrapper" style="max-width: 450px;">
                <!-- Imagen representativa elegante -->
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

    <?php if (!empty($busqueda)): ?>
    <div style="margin-bottom: 2rem; color: var(--cur-muted); display:flex; justify-content:space-between; align-items:center; background:var(--cur-white); padding:1rem 1.5rem; border-radius:12px; box-shadow:var(--cur-shadow-sm);">
        <span>Resultados para: <strong style="color:var(--cur-dark);">"<?= $busqueda ?>"</strong> (<?= $total_cursos ?> encontrados)</span>
        <a href="?ruta=cursos" style="color:var(--cur-danger); text-decoration:none; font-weight:600; display:flex; align-items:center; gap:0.4rem;">
            <i class="ph-bold ph-x-circle"></i> Limpiar Búsqueda
        </a>
    </div>
    <?php endif; ?>

    <main class="cur-grid">
        <?php if (empty($cursos)): ?>
            <div class="cur-empty" style="grid-column: 1 / -1; padding: 6rem 2rem;">
                <div style="width:100px; height:100px; background:var(--cur-primary-light); color:var(--cur-primary); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:4rem; margin:0 auto 1.5rem;">
                    <i class="ph-fill ph-graduation-cap"></i>
                </div>
                <h3><?= !empty($busqueda) ? "Sin resultados para \"$busqueda\"" : "Catálogo en preparación" ?></h3>
                <p><?= !empty($busqueda) ? "Prueba con otros términos de búsqueda." : "Próximamente publicaremos nuestra nueva oferta formativa." ?></p>
            </div>
        <?php else: ?>
            <?php foreach ($cursos as $curso): 
                $titulo = htmlspecialchars($curso['titulo']);
                $desc   = htmlspecialchars(mb_strimwidth($curso['descripcion'] ?? '', 0, 110, '…'));
                $docente= htmlspecialchars($curso['nombre_docente'] ?? 'Docente');
                $duracion = htmlspecialchars($curso['duracion'] ?? '');
                $modalidad = htmlspecialchars($curso['modalidad'] ?? 'Virtual');
                $img = !empty($curso['imagen_portada']) ? htmlspecialchars($curso['imagen_portada']) : $placeholder;
                $icon_mod = match(strtolower($modalidad)) {
                    'presencial' => 'ph-map-pin', 'híbrido' => 'ph-arrows-split', default => 'ph-monitor'
                };
            ?>
            <a href="?ruta=cursos-detalle&id=<?= $curso['id'] ?>" class="cur-card">
                <div class="cur-card-img-wrap">
                    <img src="<?= $img ?>" alt="<?= $titulo ?>" loading="<?= $lazy_attr ?>" onerror="this.src='<?= $placeholder ?>'">
                    <span class="cur-badge"><i class="ph-fill <?= $icon_mod ?>"></i> <?= $modalidad ?></span>
                </div>
                <div class="cur-card-content">
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
    <nav class="cur-pagination">
        <div class="cur-pag-controls">
            <?php if ($pagina_actual > 1): ?>
                <a href="?ruta=cursos&pagina=<?= $pagina_actual - 1 ?><?= $busqueda ? '&busqueda='.$busqueda : '' ?>" class="cur-pag-btn"><i class="ph-bold ph-caret-left"></i></a>
            <?php endif; ?>
            
            <?php for ($p = max(1, $pagina_actual - 2); $p <= min($total_paginas, $pagina_actual + 2); $p++): ?>
                <a href="?ruta=cursos&pagina=<?= $p ?><?= $busqueda ? '&busqueda='.$busqueda : '' ?>" class="cur-pag-btn <?= $p === $pagina_actual ? 'cur-pag-btn--active' : '' ?>"><?= $p ?></a>
            <?php endfor; ?>

            <?php if ($pagina_actual < $total_paginas): ?>
                <a href="?ruta=cursos&pagina=<?= $pagina_actual + 1 ?><?= $busqueda ? '&busqueda='.$busqueda : '' ?>" class="cur-pag-btn"><i class="ph-bold ph-caret-right"></i></a>
            <?php endif; ?>
        </div>
    </nav>
    <?php endif; ?>
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