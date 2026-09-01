<?php
// Solicitamos las configuraciones de las secciones (franjas) al Kernel
$seccionesHome = $this->getTarjetasInicio();
?>

<div class="landing-container">
    
    <section class="landing-hero">
        <div class="hero-text-content">
            <h1>Ecosistema Tecnológico UPTTMBI</h1>
            <p>La plataforma unificada para la gestión académica, vinculación empresarial, investigación y desarrollo sociotecnológico del estado Trujillo.</p>
            <div class="hero-actions">
                <a href="#modulos" class="btn btn-solid">Descubrir Módulos</a>
            </div>
        </div>
        <div class="hero-graphic">
            <img src="assets/img/uptt.png" alt="Ecosistema UPTTMBI" class="landing-hero-img">
        </div>
    </section>

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
                                <i class="<?= htmlspecialchars($seccion['icono']) ?>"></i> Módulo Oficial
                            </div>
                            <h2><?= htmlspecialchars($seccion['titulo']) ?></h2>
                            <p><?= htmlspecialchars($seccion['descripcion']) ?></p>
                            <a href="<?= htmlspecialchars($seccion['enlace']) ?>" class="btn">
                                <?= htmlspecialchars($seccion['texto_boton']) ?> <i class="ph-bold ph-arrow-right"></i>
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
</div>