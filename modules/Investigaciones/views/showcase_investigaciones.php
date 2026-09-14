<?php
// modules/Investigaciones/views/showcase_investigaciones.php
require_once CORE_PATH . 'Security/Auth.php';
$nivel_usuario = Auth::check() ? Auth::usuario()['nivel'] : -1;
?>
<div class="inv-wrapper">

    <!-- HERO SECTION (Antigravity Modern) -->
    <section class="landing-hero-modern" style="margin-bottom: 2rem; border-radius: var(--inv-radius-xl); overflow: hidden; box-shadow: 0 20px 40px rgba(0, 119, 190, 0.2); background: linear-gradient(135deg, #0077BE 0%, #003F66 100%); border: none;">
        <canvas id="landingCanvasBg" class="landing-hero-canvas"></canvas>

        <div class="hero-text-content">
            <div class="hero-badge-glass" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white;">
                <i class="ph-fill ph-microscope"></i> Investigación y Desarrollo CIIDI
            </div>
            <h1 style="color: white;">Soberanía <span>Tecnológica</span></h1>
            <p style="margin-bottom: 2rem; color: #E0F2FE;">Impulsamos la creación, innovación y despliegue de soluciones informáticas desarrolladas por nuestros docentes y estudiantes para la región andina.</p>
            
            <div style="display: flex; gap: 1rem; flex-wrap:wrap;">
                <a href="?ruta=postulaciones-investigacion" style="background: white; color: #0369A1; padding: 0.8rem 1.5rem; border-radius: 50px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                    <i class="ph-bold ph-rocket-launch"></i> Ver Postulaciones
                </a>
                <?php if ($nivel_usuario >= 1): ?>
                <a href="?ruta=mis-investigaciones" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 0.8rem 1.5rem; border-radius: 50px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)';" onmouseout="this.style.background='rgba(255,255,255,0.2)';">
                    <i class="ph-bold ph-folder-open"></i> Mis Proyectos
                </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="hero-graphic-spatial">
            <div class="hero-img-wrapper" style="max-width: 450px;">
                <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&q=80&w=800" alt="I+D" class="landing-hero-img-spatial" style="border-radius: 20px;">
                
                <div class="floating-glass-card card-top-left" style="animation-delay: 0.5s; background: rgba(255,255,255,0.9); border: none;">
                    <div class="floating-icon" style="color: #0EA5E9; background: #E0F2FE;">
                        <i class="ph-bold ph-lightbulb"></i>
                    </div>
                    <div class="floating-info">
                        <strong style="color: var(--inv-dark);">Innovación Abierta</strong>
                        <span style="color: var(--inv-muted);">Desarrollo Endógeno</span>
                    </div>
                </div>

                <div class="floating-glass-card card-bottom-right" style="animation-delay: 1s; background: rgba(255,255,255,0.9); border: none;">
                    <div class="floating-icon" style="color: #8B5CF6; background: #EDE9FE;">
                        <i class="ph-bold ph-users-three"></i>
                    </div>
                    <div class="floating-info">
                        <strong style="color: var(--inv-dark);">Equipos I+D</strong>
                        <span style="color: var(--inv-muted);">Docentes y Estudiantes</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PANEL DE CONTROL Y FILTROS HORIZONTALES (Estilo Artículos) -->
    <header style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 1.5rem; margin-bottom: 2.5rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04);">
        <form action="index.php" method="GET" id="filterForm" style="display:flex; flex-direction:column; gap:1.5rem;">
            <input type="hidden" name="ruta" value="investigaciones">
            
            <div style="display:flex; flex-wrap:wrap; gap:1.5rem; justify-content:space-between; align-items:center;">
                <!-- Barra de búsqueda rápida -->
                <div style="position:relative; flex:1; min-width:300px; max-width:500px;">
                    <i class="ph-bold ph-magnifying-glass" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--inv-muted);"></i>
                    <input type="text" name="q" value="<?= htmlspecialchars($busqueda) ?>" placeholder="Buscar título o descripción..." style="width:100%; padding:0.8rem 1rem 0.8rem 2.5rem; border:1px solid var(--inv-border); border-radius:50px; font-size:1rem; outline:none; transition:all 0.2s;" onfocus="this.style.boxShadow='0 0 0 3px rgba(14,165,233,0.15)'; this.style.borderColor='var(--inv-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--inv-border)';">
                </div>
                
                <!-- Selector de estado rápido -->
                <div style="display:flex; align-items:center; gap:0.5rem;">
                    <span style="font-weight:700; color:var(--inv-dark); font-size:0.9rem;"><i class="ph-bold ph-pulse"></i> Estado:</span>
                    <select name="estado" onchange="document.getElementById('filterForm').submit();" style="padding:0.6rem 1.2rem; border:1px solid var(--inv-border); border-radius:50px; outline:none; background:white; font-size:0.9rem; font-weight:600; cursor:pointer;">
                        <option value="">Todos los estados</option>
                        <option value="Abierta" <?= (isset($_GET['estado']) && $_GET['estado'] == 'Abierta') ? 'selected' : '' ?>>Abiertas a Postulación</option>
                        <option value="En Desarrollo" <?= (isset($_GET['estado']) && $_GET['estado'] == 'En Desarrollo') ? 'selected' : '' ?>>En Desarrollo</option>
                        <option value="Finalizada" <?= (isset($_GET['estado']) && $_GET['estado'] == 'Finalizada') ? 'selected' : '' ?>>Finalizada</option>
                    </select>
                </div>
            </div>

            <!-- FILA DE PILLS DE LÍNEAS DE INVESTIGACIÓN -->
            <div style="display:flex; align-items:center; gap:1rem; border-top:1px solid var(--inv-border); padding-top:1.5rem; overflow-x:auto;">
                <span style="font-weight:700; color:var(--inv-dark); font-size:0.95rem; display:flex; align-items:center; gap:0.4rem; white-space:nowrap;"><i class="ph-bold ph-git-branch"></i> Líneas:</span>
                
                <a href="?ruta=investigaciones<?= !empty($busqueda)?'&q='.urlencode($busqueda):'' ?><?= !empty($_GET['estado'])?'&estado='.urlencode($_GET['estado']):'' ?>" style="padding:0.4rem 1rem; border-radius:50px; font-size:0.85rem; font-weight:700; text-decoration:none; white-space:nowrap; transition:all 0.2s; <?= empty($_GET['linea']) ? 'background:var(--color-secundario, #0b1a30); color:white; box-shadow:0 4px 10px rgba(11,26,48,0.2);' : 'background:#F1F5F9; color:var(--inv-muted); border:1px solid transparent;' ?>" onmouseover="if(!this.style.background.includes('var(--color-secundario')) {this.style.background='white'; this.style.borderColor='var(--inv-border)';}" onmouseout="if(!this.style.background.includes('var(--color-secundario')) {this.style.background='#F1F5F9'; this.style.borderColor='transparent';}">
                    Todas
                </a>
                
                <?php foreach($lineas as $l): ?>
                    <?php 
                        $isActive = (isset($_GET['linea']) && $_GET['linea'] == $l['id']); 
                        $url = "?ruta=investigaciones&linea={$l['id']}";
                        if(!empty($busqueda)) $url .= "&q=".urlencode($busqueda);
                        if(!empty($_GET['estado'])) $url .= "&estado=".urlencode($_GET['estado']);
                    ?>
                    <a href="<?= $url ?>" style="padding:0.4rem 1rem; border-radius:50px; font-size:0.85rem; font-weight:700; text-decoration:none; white-space:nowrap; transition:all 0.2s; <?= $isActive ? 'background:var(--color-secundario, #0b1a30); color:white; box-shadow:0 4px 10px rgba(11,26,48,0.2);' : 'background:#F1F5F9; color:var(--inv-muted); border:1px solid transparent;' ?>" onmouseover="if(!this.style.background.includes('var(--color-secundario')) {this.style.background='white'; this.style.borderColor='var(--inv-border)';}" onmouseout="if(!this.style.background.includes('var(--color-secundario')) {this.style.background='#F1F5F9'; this.style.borderColor='transparent';}">
                        <?= htmlspecialchars($l['nombre']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </form>
    </header>

    <!-- MAIN LAYOUT (Full Width Grid) -->
    <main class="inv-grid" style="grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));">
        <?php if (empty($investigaciones)): ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 6rem 2rem; background: var(--inv-white); border-radius:16px; border: 1px dashed var(--inv-border); color: var(--inv-muted);">
                <i class="ph-fill ph-magnifying-glass" style="font-size: 4rem; color:var(--inv-primary-light); margin-bottom: 1rem;"></i>
                <h3 style="color:var(--inv-dark); font-size:1.5rem; margin-bottom:0.5rem;">No se encontraron investigaciones</h3>
                <p>Intenta ajustar tus criterios de búsqueda o explora otras líneas de investigación.</p>
            </div>
        <?php else: ?>
            <?php foreach ($investigaciones as $inv): ?>
            <article class="inv-card">
                <div class="inv-card-img-wrap">
                    <?php $img = !empty($inv['imagen']) ? $inv['imagen'] : 'assets/img/default-inv.png'; ?>
                    <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($inv['titulo']) ?>" onerror="this.src='https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&q=80&w=400'">
                    <span class="inv-badge" style="background: rgba(15,23,42,0.85);"><i class="ph-fill ph-pulse"></i> <?= htmlspecialchars($inv['estado']) ?></span>
                </div>
                
                <div class="inv-card-content">
                    <!-- Etiquetas de metadatos -->
                    <div style="display:flex; flex-wrap:wrap; gap:0.5rem; margin-bottom:1rem;">
                        <span style="background: #F1F5F9; color:#475569; padding:0.2rem 0.6rem; border-radius:6px; font-size:0.75rem; font-weight:600; display:flex; align-items:center; gap:0.3rem;" title="Docente/Tutor">
                            <i class="ph-fill ph-chalkboard-teacher"></i> <?= htmlspecialchars($inv['profesor'] ?? $inv['tag_quien'] ?? 'Tutor') ?>
                        </span>
                        <span style="background: #E0F2FE; color:#0369A1; padding:0.2rem 0.6rem; border-radius:6px; font-size:0.75rem; font-weight:600; display:flex; align-items:center; gap:0.3rem;" title="Línea de Investigación">
                            <i class="ph-fill ph-bookmark-simple"></i> <?= htmlspecialchars($inv['linea_nombre'] ?? 'Sin línea') ?>
                        </span>
                    </div>

                    <h3 class="inv-card-title"><?= htmlspecialchars($inv['titulo']) ?></h3>
                    <p class="inv-card-desc">
                        <?= htmlspecialchars(mb_substr($inv['planteamiento_problema'], 0, 150)) ?>...
                    </p>
                    
                    <div class="inv-card-footer">
                        <?php if ($inv['estado'] === 'Abierta'): ?>
                            <a href="?ruta=postulaciones-investigacion" class="inv-btn-primary" style="padding:0.6rem 1.2rem; font-size:0.9rem; width:100%; justify-content:center;">Ver Detalles / Postularse</a>
                        <?php else: ?>
                            <button class="inv-btn-secondary" style="padding:0.6rem 1.2rem; font-size:0.9rem; width:100%; justify-content:center; opacity:0.6; cursor:not-allowed;" disabled>Proyecto <?= htmlspecialchars($inv['estado']) ?></button>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

</div>

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
    particles = Array.from({length: 30}, () => ({
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