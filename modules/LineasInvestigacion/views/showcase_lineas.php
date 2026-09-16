<?php
// modules/LineasInvestigacion/views/showcase_lineas.php
// Variables inyectadas por ShowcaseLineasController:
//   $lineas (array), $total_dimensiones (int), $total_proyectos (int), $total_invest (int)
?>

<div class="li-wrapper">

    <!-- ╔══ HERO ══════════════════════════════════════════════════════════╗ -->
    
<style>
/* CLASES PARA VISTA TIPO LISTA SHOWCASE */
.li-view-list {
    grid-template-columns: 1fr !important;
}
.li-view-list .li-card {
    display: flex;
    flex-direction: row;
    align-items: center;
    padding: 0;
}
.li-view-list .li-card-accent {
    width: 6px;
    height: 100%;
    min-height: 120px;
}
.li-view-list .li-card-body {
    flex: 1;
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 2rem;
    padding: 1.5rem 2rem;
}
.li-view-list .li-card-header-row {
    flex: 1;
}
.li-view-list .li-card-desc {
    display: none; /* Hide description in list to save horizontal space or keep it flex */
}
.li-view-list .li-card-stats {
    flex: 1;
    margin-top: 0;
    justify-content: flex-end;
}
.li-view-list .li-card-footer {
    border-top: none;
    border-left: 1px solid rgba(80, 89, 132, 0.1);
    padding: 1.5rem 2rem;
    display: flex;
    align-items: center;
}
</style>

<style>
.ag-header-banner {
    background: linear-gradient(135deg, rgba(80, 89, 132, 0.95) 0%, rgba(30, 41, 59, 0.98) 100%) !important;
    color: #ffffff;
    border-radius: 14px;
    padding: 2.5rem 3rem;
    box-shadow: 0 16px 36px rgba(15, 23, 42, 0.15);
    margin-bottom: 2.5rem;
    position: relative;
    overflow: hidden;
}
.ag-header-banner::before {
    content: '';
    position: absolute;
    top: -50%; right: -10%;
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 60%);
    border-radius: 50%;
}
.ag-header-subtitle {
    display: inline-flex; 
    align-items: center; 
    gap: 0.5rem; 
    color: #94a3b8; 
    font-weight: 800; 
    font-size: 0.8rem; 
    text-transform: uppercase; 
    letter-spacing: 1.5px; 
    margin-bottom: 0.5rem;
}
.ag-header-title {
    font-size: 2.2rem; 
    font-weight: 800; 
    margin: 0 0 0.8rem 0; 
    color: #ffffff;
    letter-spacing: -0.5px;
}
.ag-header-desc {
    margin: 0; 
    color: #cbd5e1; 
    font-size: 1.05rem;
    max-width: 800px;
    line-height: 1.6;
}
</style>

<div class="ag-header-banner" style="margin-bottom: 3rem;">
    <canvas id="li-nodes-canvas-2" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 0; opacity: 0.6;"></canvas>
    <div style="position: relative; z-index: 1; display:flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 20px;">
        <div style="flex: 1; min-width: 300px;">
            <div class="ag-header-subtitle">
                <i class="ph-bold ph-graph"></i> CIIDI • UPTTMBI
            </div>
            <h1 class="ag-header-title">Líneas de Investigación</h1>
            <p class="ag-header-desc">
                Ejes estratégicos que articulan el conocimiento científico-tecnológico del PNF en Informática. Explora las dimensiones operativas, proyectos clasificados e investigaciones disponibles para postulación.
            </p>
        </div>
        <div style="display:flex; gap: 15px; flex-wrap: wrap; flex-shrink: 0;">
            <div style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; padding: 15px 25px; text-align: center; min-width: 100px;">
                <div style="font-size: 2.2rem; font-weight: 800; line-height: 1; margin-bottom: 5px;"><?= count($lineas) ?></div>
                <div style="font-size: 0.8rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase;">Líneas</div>
            </div>
            <div style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; padding: 15px 25px; text-align: center; min-width: 100px;">
                <div style="font-size: 2.2rem; font-weight: 800; line-height: 1; margin-bottom: 5px;"><?= (int)$total_dimensiones ?></div>
                <div style="font-size: 0.8rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase;">Dimensiones</div>
            </div>
            <div style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; padding: 15px 25px; text-align: center; min-width: 100px;">
                <div style="font-size: 2.2rem; font-weight: 800; line-height: 1; margin-bottom: 5px; color: #34d399;"><?= (int)$total_proyectos ?></div>
                <div style="font-size: 0.8rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase;">Proyectos</div>
            </div>
        </div>
    </div>
</div>


    <!-- ╔══ GRID DE LÍNEAS ═════════════════════════════════════════════════╗ -->
    <?php if (empty($lineas)): ?>
        <div class="li-empty-state">
            <i class="ph-bold ph-flask"></i>
            <p>No hay líneas de investigación registradas aún.<br>
               Los administradores pueden crearlas desde el panel de gestión.</p>
        </div>
    <?php else: ?>

    
    <!-- BARRA DE CONTROLES (VISTA) -->
    <div style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
        <div style="background: #e2e8f0; padding: 4px; border-radius: 8px; display: inline-flex; gap: 4px;">
            <button type="button" id="btnViewGridShowcase" title="Vista Cuadrícula" onclick="setLineaShowcaseViewMode('grid')" style="border:none; padding: 6px 12px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 6px; font-weight: 600; font-size: 0.85rem; transition: 0.2s; background: #ffffff; color: #1e293b; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"><i class="ph-bold ph-squares-four" style="font-size: 1.1rem;"></i></button>
            <button type="button" id="btnViewListShowcase" title="Vista Lista" onclick="setLineaShowcaseViewMode('list')" style="border:none; padding: 6px 12px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 6px; font-weight: 600; font-size: 0.85rem; transition: 0.2s; background: transparent; color: #64748b;"><i class="ph-bold ph-list" style="font-size: 1.1rem;"></i></button>
        </div>
    </div>

<div class="li-grid">
        <?php foreach ($lineas as $idx => $linea):
            $accentIdx = $idx % 6;
        ?>
        <div class="li-card">

            <!-- Barra de color top -->
            <div class="li-card-accent li-accent-<?= $accentIdx ?>"></div>

            <div class="li-card-body">
                <!-- Icono + Título -->
                <div class="li-card-header-row">
                    <div class="li-icon-box li-icon-<?= $accentIdx ?>">
                        <i class="<?= htmlspecialchars($linea['icono']) ?>"></i>
                    </div>
                    <div>
                        <h2 class="li-card-title">
                            <?= htmlspecialchars(mb_convert_case($linea['nombre'], MB_CASE_TITLE, 'UTF-8')) ?>
                        </h2>
                        <?php if (!empty($linea['carrera_nombre'])): ?>
                        <div class="li-carrera-badge" style="margin-top:0.4rem;">
                            <i class="ph-bold ph-graduation-cap"></i>
                            <?= htmlspecialchars($linea['carrera_nombre']) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Descripción -->
                <?php if (!empty($linea['descripcion'])): ?>
                <p class="li-card-desc">
                    <?= htmlspecialchars($linea['descripcion']) ?>
                </p>
                <?php endif; ?>

                <!-- Stats -->
                <div class="li-card-stats">
                    <div class="li-stat-item">
                        <i class="ph-fill ph-squares-four"></i>
                        <span class="li-stat-num"><?= (int)$linea['total_dimensiones'] ?></span>
                        Dimensiones
                    </div>
                    <div class="li-stat-item">
                        <i class="ph-fill ph-folder-open"></i>
                        <span class="li-stat-num"><?= (int)$linea['total_proyectos'] ?></span>
                        Proyectos
                    </div>
                    <?php if ((int)$linea['total_investigaciones'] > 0): ?>
                    <div class="li-stat-item">
                        <i class="ph-fill ph-flask"></i>
                        <span class="li-stat-num"><?= (int)$linea['total_investigaciones'] ?></span>
                        Ofertadas
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Botón de detalle -->
            <div class="li-card-footer">
                <a href="index.php?ruta=detalle-linea&id=<?= (int)$linea['id'] ?>"
                   class="li-btn-ver">
                    <i class="ph-bold ph-arrow-right"></i>
                    Ver Detalle
                </a>
            </div>

        </div>
        <?php endforeach; ?>
    </div>

    <?php endif; ?>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('li-nodes-canvas-2');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let width = canvas.width = canvas.offsetWidth;
        let height = canvas.height = canvas.offsetHeight;
        
        window.addEventListener('resize', () => {
            if (!canvas) return;
            width = canvas.width = canvas.offsetWidth;
            height = canvas.height = canvas.offsetHeight;
        });

        const particles = [];
        const numParticles = 40;

        for (let i = 0; i < numParticles; i++) {
            particles.push({
                x: Math.random() * width,
                y: Math.random() * height,
                vx: (Math.random() - 0.5) * 0.5,
                vy: (Math.random() - 0.5) * 0.5,
                radius: Math.random() * 2 + 1.2
            });
        }

        function animate() {
            ctx.clearRect(0, 0, width, height);

            for (let i = 0; i < numParticles; i++) {
                const p = particles[i];
                p.x += p.vx;
                p.y += p.vy;

                if (p.x < 0 || p.x > width) p.vx *= -1;
                if (p.y < 0 || p.y > height) p.vy *= -1;

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(112, 144, 203, 0.75)';
                ctx.fill();

                for (let j = i + 1; j < numParticles; j++) {
                    const p2 = particles[j];
                    const dx = p.x - p2.x;
                    const dy = p.y - p2.y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    
                    if (dist < 100) {
                        ctx.beginPath();
                        ctx.moveTo(p.x, p.y);
                        ctx.lineTo(p2.x, p2.y);
                        ctx.strokeStyle = `rgba(112, 144, 203, ${0.4 - dist/250})`;
                        ctx.lineWidth = 0.8;
                        ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(animate);
        }
        animate();
    }
});
</script>
