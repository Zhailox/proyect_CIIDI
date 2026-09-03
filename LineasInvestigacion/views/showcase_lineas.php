<?php
// modules/LineasInvestigacion/views/showcase_lineas.php
// Variables inyectadas por ShowcaseLineasController:
//   $lineas (array), $total_dimensiones (int), $total_proyectos (int), $total_invest (int)
?>

<div class="li-wrapper">

    <!-- ╔══ HERO ══════════════════════════════════════════════════════════╗ -->
    <div class="li-hero">
        <div class="li-hero-content">
            <div class="li-hero-badge">
                <i class="ph-bold ph-graph"></i> CIIDI · UPTTMBI
            </div>
            <h1>Líneas de Investigación</h1>
            <p>
                Ejes estratégicos que articulan el conocimiento científico-tecnológico del
                PNF en Informática. Explora las dimensiones operativas, proyectos clasificados
                e investigaciones disponibles para postulación.
            </p>
        </div>

        <div class="li-hero-stats">
            <div class="li-hero-stat">
                <span class="li-hero-stat-num"><?= count($lineas) ?></span>
                <span>Líneas</span>
            </div>
            <div class="li-hero-stat">
                <span class="li-hero-stat-num"><?= (int)$total_dimensiones ?></span>
                <span>Dimensiones</span>
            </div>
            <div class="li-hero-stat">
                <span class="li-hero-stat-num"><?= (int)$total_proyectos ?></span>
                <span>Proyectos</span>
            </div>
            <?php if ($total_invest > 0): ?>
            <div class="li-hero-stat">
                <span class="li-hero-stat-num"><?= (int)$total_invest ?></span>
                <span>Ofertadas</span>
            </div>
            <?php endif; ?>
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
                            <?= htmlspecialchars(ucwords(strtolower($linea['nombre']))) ?>
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
