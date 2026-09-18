<?php
// modules/LineasInvestigacion/views/detalle_linea.php
// Variables inyectadas por DetalleLineaController:
//   $linea (array|null), $dimensiones (array), $proyectos (array), $investigaciones (array), $error (string|null)
?>

<div class="li-detail-wrapper">

<?php if ($error || !$linea): ?>
    <!-- ╔══ ESTADO DE ERROR ══════════════════════════════════════════════╗ -->
    <div class="li-empty-state">
        <i class="ph-bold ph-warning-circle"></i>
        <p><?= htmlspecialchars($error ?? 'Línea no encontrada.') ?></p>
        <a href="index.php?ruta=lineas-investigacion" class="li-btn-ver" style="margin-top:1rem;width:auto;padding:0.6rem 1.5rem;">
            <i class="ph-bold ph-arrow-left"></i> Volver al listado
        </a>
    </div>
<?php else: ?>

    <!-- ╔══ HERO DE DETALLE ════════════════════════════════════════════════╗ -->
    <div class="li-detail-hero" style="position: relative; overflow: hidden;">
        <canvas id="li-nodes-canvas-3" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 0; opacity: 0.6;"></canvas>
        <div style="position: relative; z-index: 1;">
        <a href="index.php?ruta=lineas-investigacion" class="li-back-link">
            <i class="ph-bold ph-arrow-left"></i> Volver a Líneas de Investigación
        </a>

        <h1><?= htmlspecialchars(mb_convert_case($linea['nombre'], MB_CASE_TITLE, 'UTF-8')) ?></h1>

        <?php if (!empty($linea['descripcion'])): ?>
        <p><?= htmlspecialchars($linea['descripcion']) ?></p>
        <?php endif; ?>

        <div class="li-detail-meta">
            <?php if (!empty($linea['carrera_nombre'])): ?>
            <span class="li-meta-pill">
                <i class="ph-bold ph-graduation-cap"></i>
                <?= htmlspecialchars($linea['carrera_nombre']) ?>
            </span>
            <?php endif; ?>
            <span class="li-meta-pill">
                <i class="ph-bold ph-squares-four"></i>
                <?= count($dimensiones) ?> Dimensión<?= count($dimensiones) !== 1 ? 'es' : '' ?>
            </span>
            <span class="li-meta-pill">
                <i class="ph-bold ph-folder-open"></i>
                <?= count($proyectos) ?> Proyecto<?= count($proyectos) !== 1 ? 's' : '' ?> Clasificado<?= count($proyectos) !== 1 ? 's' : '' ?>
            </span>
            <?php if (count($investigaciones) > 0): ?>
            <span class="li-meta-pill">
                <i class="ph-bold ph-flask"></i>
                <?= count($investigaciones) ?> Investigaci<?= count($investigaciones) !== 1 ? 'ones Ofertadas' : 'ón Ofertada' ?>
            </span>
            <?php endif; ?>
        </div>
    </div>
    </div>

    <!-- ╔══ LAYOUT PRINCIPAL ══════════════════════════════════════════════╗ -->
    <div class="li-detail-layout">

        <!-- ── Columna Izquierda: Dimensiones + Proyectos ── -->
        <div>

            <!-- Dimensiones Operativas -->
            <h3 class="li-section-title">
                <i class="ph-fill ph-squares-four"></i>
                Dimensiones Operativas
            </h3>

            <?php if (empty($dimensiones)): ?>
                <div class="li-empty-state" style="padding:2rem;">
                    <i class="ph-bold ph-squares-four"></i>
                    <p>Esta línea aún no tiene dimensiones operativas registradas.</p>
                </div>
            <?php else: ?>
                <?php foreach ($dimensiones as $dim): ?>
                <details class="li-dimension-accordion">
                    <summary class="li-dim-summary">
                        <span>
                            <i class="ph-fill ph-dot-outline" style="color:var(--li-indigo);margin-right:0.4rem;"></i>
                            <a href='?ruta=buscador-unificado&dim=<?php echo urlencode($dim["nombre"]); ?>' style='color:inherit; text-decoration:none; border-bottom: 1px dashed var(--li-indigo);'><?= htmlspecialchars($dim['nombre']) ?></a>
                        </span>
                    </summary>
                    <div class="li-dim-body">
                        <?= !empty($dim['descripcion'])
                            ? htmlspecialchars($dim['descripcion'])
                            : '<em>Sin descripción disponible.</em>' ?>
                    </div>
                </details>
                <?php endforeach; ?>
            <?php endif; ?>


            <!-- Proyectos Clasificados -->
            <h3 class="li-section-title" style="margin-top:2rem;">
                <i class="ph-fill ph-folder-open"></i>
                Proyectos Clasificados en esta Línea
            </h3>

            <?php if (empty($proyectos)): ?>
                <div class="li-empty-state" style="padding:2rem;">
                    <i class="ph-bold ph-folder-open"></i>
                    <p>Aún no hay proyectos clasificados en esta línea de investigación.</p>
                    <a href="?ruta=mis-proyectos" style="display:inline-block; margin-top:1rem; padding:8px 16px; background:var(--li-indigo); color:white; border-radius:6px; text-decoration:none; font-size:0.85rem; font-weight:600;"><i class="ph-bold ph-plus"></i> Registra tu Proyecto PST aquí</a>
                </div>
            <?php else: ?>
                <div class="li-proj-grid">
                <?php foreach ($proyectos as $proy): ?>
                    <div class="li-proj-card">
                        <div class="li-proj-title">
                            <?= htmlspecialchars($proy['titulo']) ?>
                        </div>
                        <div class="li-proj-meta">
                            <?php if (!empty($proy['anio_publicacion'])): ?>
                            <span><i class="ph-bold ph-calendar-blank"></i> <?= (int)$proy['anio_publicacion'] ?></span>
                            <?php endif; ?>
                            <?php if (!empty($proy['nivel_academico'])): ?>
                            <span><i class="ph-bold ph-student"></i> <?= htmlspecialchars($proy['nivel_academico']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($proy['dimension_nombre'])): ?>
                            <span><i class="ph-bold ph-squares-four"></i> <?= htmlspecialchars($proy['dimension_nombre']) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($proy['autores'])): ?>
                        <div class="li-proj-authors">
                            <i class="ph-bold ph-users"></i> <?= htmlspecialchars($proy['autores']) ?>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($proy['resumen'])): ?>
                        <p style="font-size:0.82rem;color:var(--text-muted);margin:0.5rem 0 0;line-height:1.55;
                                  display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                            <?= htmlspecialchars($proy['resumen']) ?>
                        </p>
                        <?php endif; ?>
                        <div style="margin-top: 1rem; border-top: 1px solid #e2e8f0; padding-top: 0.8rem;">
                            <a href="?ruta=visor-documento&id=<?= $proy['id'] ?>" style="display:inline-flex; align-items:center; gap:0.4rem; font-size:0.8rem; font-weight:600; color:var(--li-violet); text-decoration:none;"><i class="ph-bold ph-file-pdf"></i> Ver Documento</a>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>

        <!-- ── Columna Derecha: Investigaciones Ofertadas ── -->
        <aside>
            <div class="li-sidebar-card">
                <div class="li-sidebar-header">
                    <h3><i class="ph-bold ph-flask" style="margin-right:0.4rem;"></i>Investigaciones Ofertadas</h3>
                </div>

                <div class="li-sidebar-scroll" style="max-height: 480px; overflow-y: auto;">
                <?php if (empty($investigaciones)): ?>
                    <div class="li-empty-state" style="padding:2rem;">
                        <i class="ph-bold ph-flask"></i>
                        <p style="font-size:0.82rem;">No hay investigaciones ofertadas actualmente para esta línea.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($investigaciones as $inv):
                        $estadoClass = strtolower(str_replace(' ', '', $inv['estado'] ?? 'cerrada'));
                    ?>
                    <div class="li-inv-item">
                        <div class="li-inv-title">
                            <?= htmlspecialchars($inv['titulo']) ?>
                        </div>
                        <div class="li-inv-meta">
                            <?php if (!empty($inv['nombre_profesor'])): ?>
                            <div><i class="ph-bold ph-user-circle"></i> <?= htmlspecialchars($inv['nombre_profesor']) ?></div>
                            <?php endif; ?>
                            <?php if (!empty($inv['cupos_disponibles'])): ?>
                            <div><i class="ph-bold ph-chair"></i> <?= (int)$inv['cupos_disponibles'] ?> cupo(s)</div>
                            <?php endif; ?>
                        </div>
                        <div class="li-inv-estado <?= htmlspecialchars($estadoClass) ?>">
                            <?= htmlspecialchars($inv['estado'] ?? 'N/D') ?>
                        </div>
                        <?php if (strtolower($inv['estado'] ?? '') === 'abierta'): ?>
                            <div style="margin-top: 0.8rem;">
                                <a href="?ruta=cartelera-oportunidades&id_investigacion=<?= $inv['id'] ?>" style="display:block; text-align:center; padding:6px; background:#f1f5f9; color:var(--li-indigo); border-radius:4px; font-size:0.75rem; font-weight:700; text-decoration:none; border: 1px solid #cbd5e1;"><i class="ph-bold ph-hand-pointing"></i> Postularse / Contactar</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                </div>
            </div>

            <!-- Link al gestor (solo orientativo, la auth lo protegerá) -->
            <div style="margin-top:1rem;text-align:center;">
                <a href="index.php?ruta=gestionar-lineas"
                   class="li-btn-ver"
                   style="font-size:0.8rem;padding:0.5rem 1rem;">
                    <i class="ph-bold ph-gear"></i>
                    Gestionar esta Línea
                </a>
            </div>
        </aside>

    </div>

<?php endif; ?>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('li-nodes-canvas-3');
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
