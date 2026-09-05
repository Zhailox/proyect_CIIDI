<?php
// core/Views/home_bienvenida.php
$seccionesHome = $this->getTarjetasInicio();
?>

<div class="landing-container">
    
    <!-- 1. HERO SECTION INTERACTIVO Y ESPACIAL (ANTIGRAVITY DESIGN) -->
    <section class="landing-hero-modern">
        <canvas id="landingCanvasBg" class="landing-hero-canvas"></canvas>

        <div class="hero-text-content">
            <div class="hero-badge-glass">
                <i class="ph ph-sparkles"></i> Plataforma Institucional Unificada
            </div>
            <h1>Ecosistema Tecnológico UPTTMBI</h1>
            <p>Plataforma integral para la catalogación académica, investigación científica, vinculación empresarial y desarrollo socio-tecnológico del estado Trujillo.</p>
            
            <div class="hero-actions-modern">
                <a href="?ruta=repositorio" class="btn-hero-primary">
                    <i class="ph ph-compass"></i> Explorar Repositorio PST
                </a>
                <a href="#modulos" class="btn-hero-secondary">
                    <i class="ph ph-squares-four"></i> Ver Módulos Activos
                </a>
            </div>
        </div>

        <div class="hero-graphic-spatial">
            <div class="hero-img-wrapper">
                <img src="assets/img/uptt.png" alt="Ecosistema UPTTMBI" class="landing-hero-img-spatial">
                
                <!-- Tarjetas Flotantes Glassmorphic (Antigravity Depth) -->
                <div class="floating-glass-card card-top-left">
                    <div class="floating-icon">
                        <i class="ph ph-book-open-text"></i>
                    </div>
                    <div class="floating-info">
                        <strong>+46 PST Indexados</strong>
                        <span>Catálogo Institucional</span>
                    </div>
                </div>

                <div class="floating-glass-card card-bottom-right">
                    <div class="floating-icon">
                        <i class="ph ph-check-circle"></i>
                    </div>
                    <div class="floating-info">
                        <strong>100% Digitalizado</strong>
                        <span>Acceso Abierto (OA)</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. SECCIÓN DE MÉTRICAS E IMPACTO ACADÉMICO -->
    <section class="landing-metrics-section">
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-icon-box">
                    <i class="ph ph-books"></i>
                </div>
                <div class="metric-data">
                    <h3>PST / Investigaciones</h3>
                    <p>Banco de Proyectos Socio-Tecnológicos</p>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon-box">
                    <i class="ph ph-newspaper"></i>
                </div>
                <div class="metric-data">
                    <h3>Artículos Científicos</h3>
                    <p>Publicaciones y Ensayos Académicos</p>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon-box">
                    <i class="ph ph-tree-structure"></i>
                </div>
                <div class="metric-data">
                    <h3>Líneas Institucionales</h3>
                    <p>Áreas de Desarrollo y Ámbitos</p>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon-box">
                    <i class="ph ph-buildings"></i>
                </div>
                <div class="metric-data">
                    <h3>Comunidades Atendidas</h3>
                    <p>Vinculación Territorial Directa</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. SECCIÓN DE MÓDULOS ACTIVOS DEL SISTEMA -->
    <div id="modulos" class="landing-sections-wrapper">
        
        <?php foreach ($seccionesHome as $index => $seccion): ?>
            
            <?php if (isset($seccion['tipo']) && $seccion['tipo'] === 'custom_view'): ?>
                <?php 
                    if (file_exists($seccion['ruta_vista'])) {
                        include $seccion['ruta_vista'];
                    }
                ?>
                
            <?php else: ?>
                <?php $claseFondo = ($index % 2 === 0) ? 'bg-light' : 'bg-white'; ?>
                <?php $claseAlineacion = ($index % 2 === 0) ? 'row-normal' : 'row-reverse'; ?>

                <section class="landing-franja <?= $claseFondo ?>">
                    <div class="franja-inner <?= $claseAlineacion ?>">
                        <div class="franja-text">
                            <div class="franja-badge">
                                <i class="<?= htmlspecialchars($seccion['icono']) ?>"></i> Módulo del Ecosistema
                            </div>
                            <h2><?= htmlspecialchars($seccion['titulo']) ?></h2>
                            <p><?= htmlspecialchars($seccion['descripcion']) ?></p>
                            <a href="<?= htmlspecialchars($seccion['enlace']) ?>" class="btn">
                                <?= htmlspecialchars($seccion['texto_boton']) ?> <i class="ph ph-arrow-right"></i>
                            </a>
                        </div>
                        <div class="franja-visual">
                            <i class="<?= htmlspecialchars($seccion['icono']) ?> franja-icon-large"></i>
                        </div>
                    </div>
                </section>

            <?php endif; ?>
            
        <?php endforeach; ?>

    </div>

    <!-- 4. BANNER DE LLAMADO A LA ACCIÓN (CTA FINAL) -->
    <section class="landing-cta-banner">
        <div class="cta-banner-inner">
            <h2>Explora la Búsqueda Inteligente del Ecosistema</h2>
            <p>Accede al motor unificado para consultar investigaciones, documentos académicos y proyectos comunitarios en tiempo real.</p>
            <a href="?ruta=buscador" class="btn-cta-white">
                <i class="ph ph-magnifying-glass"></i> Ir al Buscador Unificado
            </a>
        </div>
    </section>

</div>

<script>
// ANIMACIÓN DE FONDO CANVAS HERO (ANTIGRAVITY BLUE NETWORK)
(function initLandingCanvas() {
    const canvas = document.getElementById('landingCanvasBg');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let width, height;
    let particles = [];

    function resize() {
        width = canvas.width = canvas.parentElement.offsetWidth || window.innerWidth;
        height = canvas.height = canvas.parentElement.offsetHeight || 500;
    }
    window.addEventListener('resize', resize);
    resize();

    class Particle {
        constructor() {
            this.x = Math.random() * width;
            this.y = Math.random() * height;
            this.vx = (Math.random() - 0.5) * 0.6;
            this.vy = (Math.random() - 0.5) * 0.6;
            this.radius = Math.random() * 3 + 2;
        }
        update() {
            this.x += this.vx;
            this.y += this.vy;
            if (this.x < 0 || this.x > width) this.vx *= -1;
            if (this.y < 0 || this.y > height) this.vy *= -1;
        }
        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(80, 89, 132, 0.4)';
            ctx.fill();
        }
    }

    const numParticles = Math.min(Math.floor(width / 22), 45);
    for (let i = 0; i < numParticles; i++) {
        particles.push(new Particle());
    }

    let animId = null;
    function animate() {
        if (document.hidden) return;
        ctx.clearRect(0, 0, width, height);
        for (let i = 0; i < particles.length; i++) {
            particles[i].update();
            particles[i].draw();
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < 140) {
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.strokeStyle = `rgba(112, 144, 203, ${0.35 * (1 - dist / 140)})`;
                    ctx.lineWidth = 1.2;
                    ctx.stroke();
                }
            }
        }
        animId = requestAnimationFrame(animate);
    }

    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            if (animId) cancelAnimationFrame(animId);
            animate();
        }
    });

    animate();
})();
</script>