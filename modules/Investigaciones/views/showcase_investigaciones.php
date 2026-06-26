<?php
// modules/Investigaciones/views/showcase_investigaciones.php
// Variables inyectadas por InvestigacionesController::showcase():
// $proyectos — array de proyectos activos con JOIN a investigador y línea
// $lineas    — array de líneas de investigación

$iconos_linea = [
    'Ingeniería de Software'     => 'ph-code',
    'Gestión de Datos e IA'      => 'ph-database',
    'Redes y Ciberseguridad'     => 'ph-hard-drives',
    'Agroinformática y Hardware' => 'ph-plant',
];
?>
<div class="inv-wrapper">

    <section class="inv-hero">
        <div class="inv-hero-content">
            <h1>Investigación y Desarrollo CIIDI</h1>
            <p>
                Impulsamos la soberanía tecnológica mediante la creación, innovación y despliegue de soluciones informáticas desarrolladas por nuestros docentes y estudiantes del Trayecto IV.
            </p>
            <a href="?ruta=postulaciones-investigacion" class="inv-hero-btn">Postular a un Proyecto</a>
        </div>
        <div class="inv-hero-img">
            <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=500&q=80" alt="Código y Desarrollo">
        </div>
    </section>

    <div class="inv-lines-ribbon">
        <?php foreach ($lineas as $linea): ?>
        <div class="inv-line-item">
            <div class="inv-line-header">
                <i class="ph-bold <?= htmlspecialchars($linea['icono_ph']) ?>"></i>
                <?= htmlspecialchars($linea['nombre']) ?>
            </div>
            <div class="inv-line-desc">
                <?= htmlspecialchars($linea['descripcion']) ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="inv-layout">

        <main class="inv-grid">
            <?php if (empty($proyectos)): ?>
            <p style="color: var(--texto-silenciado); padding: 2rem;">No hay proyectos activos en este momento.</p>
            <?php else: ?>
            <?php foreach ($proyectos as $p): ?>
            <article class="inv-card">
                <div class="inv-card-header" style="background-image: url('<?= htmlspecialchars($p['imagen_url'] ?? '') ?>');"></div>
                <div class="inv-card-body">
                    <div class="inv-tags-container">
                        <span class="inv-tag who">
                            <i class="ph-fill ph-user-circle"></i>
                            Quién: <?= htmlspecialchars($p['grado_academico'] . ' ' . $p['investigador_nombre']) ?>
                        </span>
                        <span class="inv-tag what">
                            <i class="ph-fill ph-<?= htmlspecialchars($iconos_linea[$p['linea_nombre']] ?? 'flask') ?>"></i>
                            Qué: <?= htmlspecialchars($p['linea_nombre']) ?>
                        </span>
                        <span class="inv-tag about">
                            <i class="ph-fill ph-folder-open"></i>
                            Estado:
                            <?php
                                $estados = [
                                    'activo'    => '🟢 En Desarrollo',
                                    'pruebas'   => '🟡 Fase de Pruebas',
                                    'concluido' => '⚫ Concluido',
                                ];
                                echo $estados[$p['estado']] ?? ucfirst(htmlspecialchars($p['estado']));
                            ?>
                        </span>
                    </div>
                    <h3 class="inv-card-title"><?= htmlspecialchars($p['titulo']) ?></h3>
                    <p class="inv-card-abstract"><?= htmlspecialchars($p['resumen']) ?></p>
                    <a href="?ruta=postulaciones-investigacion" class="inv-btn-read">Ver Detalles / Postularse</a>
                </div>
            </article>
            <?php endforeach; ?>
            <?php endif; ?>
        </main>

        <aside class="inv-sidebar">
            <h3>Buscar en CIIDI</h3>

            <div class="inv-search-box">
                <input type="text" class="inv-search-input" placeholder="Buscar título o autor..." id="buscador-proyectos">
                <button class="inv-search-btn"><i class="ph-bold ph-magnifying-glass"></i></button>
            </div>

            <div class="inv-filter-group">
                <div class="inv-filter-title">Filtrar por Estado</div>
                <label class="inv-checkbox"><input type="checkbox" class="filtro-estado" value="activo" checked> 🟢 En Desarrollo</label>
                <label class="inv-checkbox"><input type="checkbox" class="filtro-estado" value="pruebas"> 🟡 Fase de Pruebas</label>
                <label class="inv-checkbox"><input type="checkbox" class="filtro-estado" value="concluido"> ⚫ Concluido</label>
            </div>

            <div class="inv-filter-group">
                <div class="inv-filter-title">Línea de I+D</div>
                <?php foreach ($lineas as $linea): ?>
                <label class="inv-checkbox">
                    <input type="checkbox" class="filtro-linea" value="<?= htmlspecialchars($linea['nombre']) ?>">
                    <?= htmlspecialchars($linea['nombre']) ?>
                </label>
                <?php endforeach; ?>
            </div>

            <a href="?ruta=cartelera-investigacion" class="inv-hero-btn" style="width: 100%; margin-top: 1rem; display: block; text-align: center; text-decoration: none;">
                Ver Convocatorias
            </a>
        </aside>

    </div>
</div>