<?php
// modules/Investigaciones/views/cartelera.php
// Variables inyectadas por InvestigacionesController::cartelera():
// $anuncios — array de anuncios ordenados por fecha DESC

$iconos_categoria = [
    'convocatoria' => ['icono' => 'ph-megaphone',     'color' => '#121A3E', 'label' => 'Convocatoria'],
    'evento'       => ['icono' => 'ph-calendar-star', 'color' => '#1A8754', 'label' => 'Evento'],
    'resultado'    => ['icono' => 'ph-trophy',         'color' => '#B45309', 'label' => 'Resultado'],
    'general'      => ['icono' => 'ph-info',           'color' => '#505984', 'label' => 'General'],
];

function tiempoRelativo(string $fecha): string {
    $diff = time() - strtotime($fecha);
    if ($diff < 3600)        return 'Hace ' . floor($diff/60) . ' min';
    if ($diff < 86400)       return 'Hace ' . floor($diff/3600) . ' horas';
    if ($diff < 604800)      return 'Hace ' . floor($diff/86400) . ' días';
    return date('d/m/Y', strtotime($fecha));
}
?>

<div class="cart-wrapper">

    <header class="cart-hero">
        <div class="cart-hero-content">
            <div class="cart-hero-badge">
                <i class="ph-fill ph-megaphone"></i>
                Tablón Oficial
            </div>
            <h1>Cartelera I+D</h1>
            <p>Convocatorias, eventos y resultados del Centro de Investigación e Innovación Digital de la UPTTMBI.</p>
        </div>
    </header>

    <div class="cart-layout">

        <main class="cart-feed">

            <?php if (empty($anuncios)): ?>
            <div class="cart-empty">
                <i class="ph-fill ph-newspaper"></i>
                <p>No hay anuncios publicados aún.</p>
            </div>
            <?php else: ?>

            <?php foreach ($anuncios as $a):
                $cat   = $iconos_categoria[$a['categoria']] ?? $iconos_categoria['general'];
                $nuevo = (bool) $a['es_nuevo'];
            ?>
            <article class="cart-item <?= $nuevo ? 'cart-item--nuevo' : '' ?>">
                <div class="cart-item-icon" style="background: <?= $cat['color'] ?>20; color: <?= $cat['color'] ?>;">
                    <i class="ph-fill <?= $cat['icono'] ?>"></i>
                </div>
                <div class="cart-item-body">
                    <div class="cart-item-meta">
                        <span class="cart-categoria" style="color: <?= $cat['color'] ?>;">
                            <?= $cat['label'] ?>
                        </span>
                        <span class="cart-fecha">
                            <i class="ph-fill ph-clock"></i>
                            <?= tiempoRelativo($a['fecha_publicacion']) ?>
                        </span>
                        <?php if ($nuevo): ?>
                        <span class="cart-badge-nuevo">NUEVO</span>
                        <?php endif; ?>
                    </div>
                    <h3 class="cart-item-titulo"><?= htmlspecialchars($a['titulo']) ?></h3>
                    <p class="cart-item-contenido"><?= htmlspecialchars($a['contenido']) ?></p>
                    <?php if (!empty($a['url_detalle']) && $a['url_detalle'] !== '#'): ?>
                    <a href="<?= htmlspecialchars($a['url_detalle']) ?>" class="cart-btn-leer">
                        Leer completo <i class="ph-bold ph-arrow-right"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>

            <?php endif; ?>
        </main>

        <!-- SIDEBAR: Filtros por categoría -->
        <aside class="cart-sidebar">
            <h3><i class="ph-fill ph-funnel"></i> Filtrar</h3>
            <nav class="cart-filter-nav">
                <button class="cart-filter-item active" data-categoria="todos">
                    <i class="ph-fill ph-list"></i> Todos
                </button>
                <button class="cart-filter-item" data-categoria="convocatoria">
                    <i class="ph-fill ph-megaphone"></i> Convocatorias
                </button>
                <button class="cart-filter-item" data-categoria="evento">
                    <i class="ph-fill ph-calendar-star"></i> Eventos
                </button>
                <button class="cart-filter-item" data-categoria="resultado">
                    <i class="ph-fill ph-trophy"></i> Resultados
                </button>
            </nav>

            <div class="cart-sidebar-cta">
                <p>¿Tienes un proyecto para presentar?</p>
                <a href="?ruta=postulaciones-investigacion" class="dir-btn-proyectos" style="display:block; text-align:center; margin-top:0.8rem;">
                    Ver Vacantes
                </a>
            </div>
        </aside>

    </div>

</div>

<script>
// Filtro por categoría
document.querySelectorAll('.cart-filter-item').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.cart-filter-item').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const cat = this.dataset.categoria;
        document.querySelectorAll('.cart-item').forEach(item => {
            const itemCat = item.querySelector('.cart-categoria')?.textContent.trim().toLowerCase();
            const catMap  = { 'convocatoria': 'convocatoria', 'evento': 'evento', 'resultado': 'resultado', 'general': 'general' };
            if (cat === 'todos' || Object.values(catMap).some(c => c === cat && itemCat?.includes(c))) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>
