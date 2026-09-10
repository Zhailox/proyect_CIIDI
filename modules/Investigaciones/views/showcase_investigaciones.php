<?php
// modules/Investigaciones/views/showcase_investigaciones.php
require_once CORE_PATH . 'Security/Auth.php';
$nivel_usuario = Auth::check() ? Auth::usuario()['nivel'] : -1;
?>
<div class="inv-wrapper">

    <!-- HERO SECTION -->
    <section class="inv-hero">
        <div class="inv-hero-content">
            <h1>Investigación y Desarrollo CIIDI</h1>
            <p>
                Impulsamos la soberanía tecnológica mediante la creación, innovación y despliegue de soluciones informáticas desarrolladas por nuestros docentes y estudiantes.
            </p>
            <div style="display: flex; gap: 1rem;">
                <a href="?ruta=postulaciones-investigacion" class="inv-hero-btn" style="text-decoration:none;">
                    Ver Postulaciones
                </a>
                <?php if ($nivel_usuario >= 1): ?>
                <a href="?ruta=mis-investigaciones" class="inv-hero-btn" style="background-color: var(--color-terciario); text-decoration:none;">
                    Mis Proyectos
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="inv-hero-img">
            <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=500&q=80" alt="Código y Desarrollo">
        </div>
    </section>

    <!-- MAIN LAYOUT -->
    <div class="inv-layout">
        
        <!-- GRID CENTRAL -->
        <main class="inv-grid">
            
            <?php if (empty($investigaciones)): ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background: #f8fafc; border: 1px dashed var(--gris); color: var(--texto-silenciado);">
                    <i class="ph-fill ph-magnifying-glass" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                    <h3>No se encontraron investigaciones</h3>
                    <p>Intenta ajustar tus criterios de búsqueda o explora otras líneas de investigación.</p>
                </div>
            <?php else: ?>
                <?php foreach ($investigaciones as $inv): ?>
                <article class="inv-card">
                    <!-- Imagen extraída del JSON de metadatos o por defecto -->
                    <div class="inv-card-header" style="background-image: url('<?= htmlspecialchars($inv['imagen']) ?>');">
                        <span class="inv-badge-estado <?= strtolower(str_replace(' ', '-', $inv['estado'])) ?>">
                            <?= htmlspecialchars($inv['estado']) ?>
                        </span>
                    </div>
                    
                    <div class="inv-card-body">
                        
                        <div class="inv-tags-container">
                            <span class="inv-tag who" title="Docente/Tutor">
                                <i class="ph-fill ph-chalkboard-teacher"></i> 
                                <?= htmlspecialchars($inv['profesor'] ?? $inv['tag_quien']) ?>
                            </span>
                            <span class="inv-tag what" title="Línea de Investigación">
                                <i class="ph-fill ph-bookmark-simple"></i> 
                                <?= htmlspecialchars($inv['linea_nombre'] ?? 'Sin asignar') ?>
                            </span>
                            <span class="inv-tag about" title="Tipo">
                                <i class="ph-fill ph-cpu"></i> 
                                <?= htmlspecialchars($inv['tag_que']) ?>
                            </span>
                        </div>

                        <h3 class="inv-card-title"><?= htmlspecialchars($inv['titulo']) ?></h3>
                        <p class="inv-card-abstract">
                            <?= htmlspecialchars(mb_substr($inv['planteamiento_problema'], 0, 150)) ?>...
                        </p>
                        
                        <?php if ($inv['estado'] === 'Abierta'): ?>
                            <a href="?ruta=postulaciones-investigacion" class="inv-btn-read">Ver Detalles / Postularse</a>
                        <?php else: ?>
                            <span class="inv-btn-read" style="opacity: 0.5; cursor: not-allowed; border-color: var(--gris);">Proyecto <?= htmlspecialchars($inv['estado']) ?></span>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; ?>
            <?php endif; ?>

        </main>

        <!-- BARRA LATERAL / BÚSQUEDA -->
        <aside class="inv-sidebar">
            <h3>Buscar Investigaciones</h3>
            
            <form action="index.php" method="GET" class="inv-search-box">
                <input type="hidden" name="ruta" value="investigaciones">
                <input type="text" name="q" class="inv-search-input" value="<?= htmlspecialchars($busqueda) ?>" placeholder="Buscar título o descripción...">
                <button type="submit" class="inv-search-btn"><i class="ph-bold ph-magnifying-glass"></i></button>
            </form>

            <form action="index.php" method="GET" id="filterForm">
                <input type="hidden" name="ruta" value="investigaciones">
                <?php if(!empty($busqueda)): ?><input type="hidden" name="q" value="<?= htmlspecialchars($busqueda) ?>"><?php endif; ?>
                
                <div class="inv-filter-group">
                    <div class="inv-filter-title">Línea de Investigación</div>
                    <select name="linea" class="inv-form-control" onchange="document.getElementById('filterForm').submit();" style="width:100%; padding:0.5rem; margin-bottom:1rem;">
                        <option value="">Todas las líneas</option>
                        <?php foreach($lineas as $l): ?>
                            <option value="<?= $l['id'] ?>" <?= (isset($_GET['linea']) && $_GET['linea'] == $l['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($l['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="inv-filter-group">
                    <div class="inv-filter-title">Estado del Proyecto</div>
                    <select name="estado" class="inv-form-control" onchange="document.getElementById('filterForm').submit();" style="width:100%; padding:0.5rem; margin-bottom:1rem;">
                        <option value="">Cualquier estado</option>
                        <option value="Abierta" <?= (isset($_GET['estado']) && $_GET['estado'] == 'Abierta') ? 'selected' : '' ?>>Abiertas a Postulación</option>
                        <option value="En Desarrollo" <?= (isset($_GET['estado']) && $_GET['estado'] == 'En Desarrollo') ? 'selected' : '' ?>>En Desarrollo</option>
                        <option value="Finalizada" <?= (isset($_GET['estado']) && $_GET['estado'] == 'Finalizada') ? 'selected' : '' ?>>Finalizada</option>
                    </select>
                </div>
            </form>
        </aside>

    </div>
</div>