<?php
// modules/Investigaciones/views/ficha_investigador.php
// Variables inyectadas por InvestigacionesController::directorio():
// $investigadores — array de investigadores activos

// Paleta de colores para los avatares (cuando no hay foto)
$paleta_avatar = [
    'linear-gradient(135deg, #121A3E 0%, #505984 100%)',
    'linear-gradient(135deg, #1A8754 0%, #0d6640 100%)',
    'linear-gradient(135deg, #2D3561 0%, #121A3E 100%)',
    'linear-gradient(135deg, #505984 0%, #7b84bb 100%)',
    'linear-gradient(135deg, #c0392b 0%, #6d1f1f 100%)',
    'linear-gradient(135deg, #d35400 0%, #873600 100%)',
];

// Inicial del nombre para avatar fallback
function obtenerIniciales(string $nombre): string {
    $partes = explode(' ', trim($nombre));
    $iniciales = '';
    foreach (array_slice($partes, 0, 2) as $parte) {
        $iniciales .= mb_strtoupper(mb_substr($parte, 0, 1));
    }
    return $iniciales;
}
?>

<div class="dir-wrapper">

    <!-- HERO SECTION -->
    <section class="dir-hero">
        <div class="dir-hero-bg"></div>
        <div class="dir-hero-content">
            <div class="dir-hero-badge">
                <i class="ph-fill ph-users-three"></i>
                CIIDI — UPTTMBI
            </div>
            <h1>Directorio de <span class="dir-hero-highlight">Investigadores</span></h1>
            <p>Conoce a los expertos que lideran nuestros proyectos. Docentes y estudiantes de alto nivel que impulsan la soberanía tecnológica desde los Andes venezolanos.</p>
            <div class="dir-hero-stats">
                <div class="dir-stat">
                    <span class="dir-stat-num"><?= count($investigadores) ?></span>
                    <span class="dir-stat-label">Investigadores</span>
                </div>
                <div class="dir-stat-divider"></div>
                <div class="dir-stat">
                    <span class="dir-stat-num">4</span>
                    <span class="dir-stat-label">Líneas I+D</span>
                </div>
                <div class="dir-stat-divider"></div>
                <div class="dir-stat">
                    <span class="dir-stat-num">6</span>
                    <span class="dir-stat-label">Proyectos Activos</span>
                </div>
            </div>
        </div>
    </section>

    <!-- GRID DE INVESTIGADORES -->
    <section class="dir-grid-section">
        <div class="dir-section-header">
            <h2>Nuestros Expertos</h2>
            <p>Cada investigador aporta una visión única a nuestra misión de innovación tecnológica.</p>
        </div>

        <div class="dir-grid">
            <?php foreach ($investigadores as $i => $inv): ?>
            <?php
                $iniciales   = obtenerIniciales($inv['nombre']);
                $gradiente   = $paleta_avatar[$i % count($paleta_avatar)];
                $habilidades = is_array($inv['habilidades'])
                    ? $inv['habilidades']
                    : (is_string($inv['habilidades']) ? array_map('trim', explode(',', trim($inv['habilidades'], '{}'))) : []);
            ?>
            <article class="dir-card" data-investigador="<?= $i ?>">

                <!-- Borde superior de acento -->
                <div class="dir-card-accent"></div>

                <!-- Foto / Avatar -->
                <div class="dir-card-avatar-wrap">
                    <?php if (!empty($inv['foto_url'])): ?>
                    <img
                        src="<?= htmlspecialchars($inv['foto_url']) ?>"
                        alt="<?= htmlspecialchars($inv['nombre']) ?>"
                        class="dir-avatar-img"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    >
                    <div class="dir-avatar-fallback" style="background: <?= $gradiente ?>; display: none;">
                        <?= $iniciales ?>
                    </div>
                    <?php else: ?>
                    <div class="dir-avatar-fallback" style="background: <?= $gradiente ?>;">
                        <?= $iniciales ?>
                    </div>
                    <?php endif; ?>

                    <!-- Badge de grado académico -->
                    <span class="dir-grado-badge"><?= htmlspecialchars($inv['grado_academico']) ?></span>
                </div>

                <!-- Info principal -->
                <div class="dir-card-info">
                    <h3 class="dir-card-nombre"><?= htmlspecialchars($inv['nombre']) ?></h3>
                    <p class="dir-card-especialidad"><?= htmlspecialchars($inv['especialidad']) ?></p>

                    <div class="dir-card-sede">
                        <i class="ph-fill ph-map-pin"></i>
                        <?= htmlspecialchars($inv['sede']) ?>
                    </div>
                </div>

                <!-- Bio expandible -->
                <?php if (!empty($inv['bio'])): ?>
                <div class="dir-card-bio">
                    <p><?= htmlspecialchars($inv['bio']) ?></p>
                </div>
                <?php endif; ?>

                <!-- Tags de habilidades -->
                <?php if (!empty($habilidades)): ?>
                <div class="dir-tags">
                    <?php foreach ($habilidades as $tag): ?>
                    <?php if (!empty(trim($tag))): ?>
                    <span class="dir-tag"><?= htmlspecialchars(trim($tag)) ?></span>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Footer de la card -->
                <div class="dir-card-footer">
                    <?php if (!empty($inv['email'])): ?>
                    <a href="mailto:<?= htmlspecialchars($inv['email']) ?>" class="dir-btn-contacto">
                        <i class="ph-fill ph-envelope"></i>
                        Contactar
                    </a>
                    <?php endif; ?>
                    <a href="?ruta=investigaciones" class="dir-btn-proyectos">
                        <i class="ph-fill ph-flask"></i>
                        Ver Proyectos
                    </a>
                </div>

            </article>
            <?php endforeach; ?>

            <?php if (empty($investigadores)): ?>
            <div style="grid-column: 1/-1; text-align:center; padding: 4rem; color: var(--texto-silenciado);">
                <i class="ph-fill ph-users-three" style="font-size: 3rem; opacity: 0.3;"></i>
                <p style="margin-top: 1rem;">No hay investigadores registrados aún.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- LLAMADA A LA ACCIÓN -->
    <section class="dir-cta">
        <div class="dir-cta-content">
            <i class="ph-fill ph-handshake"></i>
            <h2>¿Eres docente investigador?</h2>
            <p>Si formas parte del cuerpo docente del PNF de Informática y lideras un proyecto de I+D, ponte en contacto con la Dirección CIIDI para integrar tu perfil al directorio.</p>
            <a href="?ruta=cartelera-investigacion" class="dir-cta-btn">
                Ver Convocatorias Activas
            </a>
        </div>
    </section>

</div>
