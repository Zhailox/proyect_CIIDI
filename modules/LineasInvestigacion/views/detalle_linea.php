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
    <div class="li-detail-hero">
        <a href="index.php?ruta=lineas-investigacion" class="li-back-link">
            <i class="ph-bold ph-arrow-left"></i> Volver a Líneas de Investigación
        </a>

        <h1><?= htmlspecialchars(ucwords(strtolower($linea['nombre']))) ?></h1>

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
                            <?= htmlspecialchars($dim['nombre']) ?>
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
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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
