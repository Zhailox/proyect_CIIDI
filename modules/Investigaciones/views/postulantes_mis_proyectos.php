<?php
// modules/Investigaciones/views/postulantes_mis_proyectos.php
// Variables: $postulaciones (array)
?>
<div class="inv-wrapper">

    <?php if (isset($_SESSION['flash_success'])): ?>
    <div style="background: #10b981; color: white; padding: 1rem; border-radius: 4px; text-align: center; font-weight: bold; margin-bottom: 1rem;">
        <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
    </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['flash_error'])): ?>
    <div style="background: #ef4444; color: white; padding: 1rem; border-radius: 4px; text-align: center; font-weight: bold; margin-bottom: 1rem;">
        <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
    </div>
    <?php endif; ?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="color: var(--texto-titulos); font-size: 2rem; margin: 0;">Postulantes a mis proyectos</h1>
        <a href="?ruta=mis-investigaciones" class="inv-btn-read" style="text-decoration:none;">
            Volver a mis proyectos
        </a>
    </div>

    <div style="background: #fff; border: 1px solid var(--gris); border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background: #f8fafc; border-bottom: 2px solid var(--gris);">
                <tr>
                    <th style="padding: 1rem; color: var(--texto-titulos);">Estudiante</th>
                    <th style="padding: 1rem; color: var(--texto-titulos);">Proyecto Solicitado</th>
                    <th style="padding: 1rem; color: var(--texto-titulos);">Motivación / Mensaje</th>
                    <th style="padding: 1rem; color: var(--texto-titulos);">Estado</th>
                    <th style="padding: 1rem; color: var(--texto-titulos); text-align: right;">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($postulaciones)): ?>
                <tr>
                    <td colspan="5" style="padding: 3rem; text-align: center; color: var(--texto-silenciado);">
                        No hay postulaciones registradas en este momento.
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($postulaciones as $p): ?>
                    <tr style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                        <td style="padding: 1rem;">
                            <div style="font-weight: bold; color: var(--texto-titulos);"><?= htmlspecialchars($p['estudiante']) ?></div>
                            <div style="font-size: 0.85rem; color: var(--texto-silenciado);"><?= htmlspecialchars($p['email']) ?></div>
                            <div style="font-size: 0.8rem; color: var(--texto-silenciado); margin-top:0.3rem;">
                                <?= date('d/m/Y', strtotime($p['fecha_postulacion'])) ?>
                            </div>
                        </td>
                        <td style="padding: 1rem; color: var(--color-secundario); font-weight: 500;">
                            <?= htmlspecialchars($p['investigacion_titulo']) ?>
                        </td>
                        <td style="padding: 1rem; max-width: 300px;">
                            <p style="font-size: 0.9rem; color: var(--texto-comun); line-height: 1.4; margin: 0; white-space: pre-wrap;"><?= htmlspecialchars($p['mensaje_motivacion']) ?></p>
                        </td>
                        <td style="padding: 1rem;">
                            <?php 
                                $color = '#f59e0b'; // Pendiente
                                if($p['estado'] === 'Aceptado') $color = '#10b981';
                                if($p['estado'] === 'Rechazado') $color = '#ef4444';
                            ?>
                            <span style="display:inline-block; padding: 0.3rem 0.6rem; border-radius: 4px; font-size: 0.8rem; font-weight:bold; color:#fff; background-color: <?= $color ?>;">
                                <?= htmlspecialchars($p['estado']) ?>
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: right;">
                            <?php if ($p['estado'] === 'Pendiente'): ?>
                                <form action="?ruta=responder-postulacion" method="POST" style="display:inline;">
                                    <input type="hidden" name="id_postulacion" value="<?= $p['id'] ?>">
                                    <input type="hidden" name="estado" value="Aceptado">
                                    <button type="submit" style="background:#10b981; border:none; color:white; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; font-weight:bold; font-size: 0.8rem; margin-bottom: 0.3rem; width: 90px;" title="Aceptar">Aceptar</button>
                                </form>
                                <form action="?ruta=responder-postulacion" method="POST" style="display:inline;">
                                    <input type="hidden" name="id_postulacion" value="<?= $p['id'] ?>">
                                    <input type="hidden" name="estado" value="Rechazado">
                                    <button type="submit" style="background:#ef4444; border:none; color:white; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; font-weight:bold; font-size: 0.8rem; width: 90px;" title="Rechazar">Rechazar</button>
                                </form>
                            <?php else: ?>
                                <span style="font-size: 0.85rem; color: var(--texto-silenciado);">Procesada el <br><?= date('d/m/y H:i', strtotime($p['fecha_respuesta'])) ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
