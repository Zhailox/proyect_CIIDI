<?php
// modules/Investigaciones/views/ficha_investigador.php
// Variables: $investigadores (array real desde BD)
?>
<div class="inv-wrapper">
    <header class="inv-hero" style="background: linear-gradient(135deg, #0a0f24 0%, #121a3e 100%); padding: 3rem; border-radius: 8px;">
        <div class="inv-hero-content" style="text-align: center; max-width: 100%; color: white;">
            <h1 style="color: white; margin-bottom: 0.5rem;">Directorio de Investigadores</h1>
            <p style="color: rgba(255,255,255,0.8); font-size: 1.1rem;">Conoce a los docentes y expertos que lideran los proyectos de I+D en el CIIDI.</p>
        </div>
    </header>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem; margin-top: 2rem;">
        <?php if (empty($investigadores)): ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; color: var(--texto-silenciado);">
                <h3>No hay investigadores registrados actualmente.</h3>
            </div>
        <?php else: ?>
            <?php foreach ($investigadores as $inv): ?>
            <div style="background: #fff; border: 1px solid var(--gris); border-radius: 8px; overflow: hidden; text-align: center; padding: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: transform 0.2s;">
                <div style="width: 100px; height: 100px; background: var(--color-secundario); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 1.5rem; font-weight: bold;">
                    <?= strtoupper(substr(trim($inv['nombre_completo']), 0, 1)) ?>
                </div>
                <h3 style="color: var(--texto-titulos); margin-bottom: 0.5rem; font-size: 1.2rem;"><?= htmlspecialchars($inv['nombre_completo']) ?></h3>
                <div style="color: var(--color-terciario); font-weight: 600; font-size: 0.9rem; margin-bottom: 1rem; text-transform: uppercase;">
                    <?= htmlspecialchars($inv['rol_nombre']) ?>
                </div>
                <div style="color: var(--texto-silenciado); font-size: 0.85rem; margin-bottom: 1.5rem;">
                    <i class="ph-fill ph-envelope-simple"></i> <?= htmlspecialchars($inv['email'] ?: 'Sin correo público') ?>
                </div>
                <div style="padding-top: 1rem; border-top: 1px solid var(--gris); display: flex; justify-content: center; gap: 1rem;">
                    <div style="text-align: center;">
                        <div style="font-size: 1.2rem; font-weight: 800; color: var(--color-principal);"><?= (int)$inv['total_investigaciones'] ?></div>
                        <div style="font-size: 0.75rem; text-transform: uppercase; color: var(--texto-silenciado); font-weight: bold;">Proyectos</div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
