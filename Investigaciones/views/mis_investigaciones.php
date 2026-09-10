<?php
// modules/Investigaciones/views/mis_investigaciones.php
// Variables disponibles: $investigaciones (array)
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
        <h1 style="color: var(--texto-titulos); font-size: 2rem; margin: 0;">Mis Proyectos de Investigación</h1>
        <a href="?ruta=crear-investigacion" class="inv-btn-primary" style="text-decoration:none;">
            <i class="ph-bold ph-plus"></i> Nueva Investigación
        </a>
    </div>

    <div style="background: #fff; border: 1px solid var(--gris); border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background: #f8fafc; border-bottom: 2px solid var(--gris);">
                <tr>
                    <th style="padding: 1rem; color: var(--texto-titulos);">Proyecto</th>
                    <th style="padding: 1rem; color: var(--texto-titulos);">Línea / Trayecto</th>
                    <th style="padding: 1rem; color: var(--texto-titulos);">Estado</th>
                    <th style="padding: 1rem; color: var(--texto-titulos); text-align: center;">Postulantes</th>
                    <th style="padding: 1rem; color: var(--texto-titulos); text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($investigaciones)): ?>
                <tr>
                    <td colspan="5" style="padding: 3rem; text-align: center; color: var(--texto-silenciado);">
                        No tienes investigaciones registradas aún.
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($investigaciones as $inv): ?>
                    <tr style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                        <td style="padding: 1rem;">
                            <div style="font-weight: bold; color: var(--color-secundario); margin-bottom: 0.3rem;">
                                <?= htmlspecialchars($inv['titulo']) ?>
                            </div>
                            <div style="font-size: 0.85rem; color: var(--texto-silenciado);">
                                Cupos Totales: <?= (int)$inv['cupos_disponibles'] ?>
                            </div>
                        </td>
                        <td style="padding: 1rem; font-size: 0.9rem; color: var(--texto-comun);">
                            <?= htmlspecialchars($inv['linea_nombre']) ?><br>
                            <span style="color: var(--texto-silenciado); text-transform: uppercase; font-size: 0.8rem;">
                                <?= htmlspecialchars($inv['trayecto']) ?>
                            </span>
                        </td>
                        <td style="padding: 1rem;">
                            <span style="display:inline-block; padding: 0.3rem 0.6rem; border-radius: 4px; font-size: 0.8rem; font-weight:bold; color:#fff; background-color: <?= $inv['estado'] === 'Abierta' ? '#10b981' : ($inv['estado'] === 'En Desarrollo' ? '#f59e0b' : ($inv['estado'] === 'Cerrada' ? '#ef4444' : '#3b82f6')) ?>;">
                                <?= htmlspecialchars($inv['estado']) ?>
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <?php if ($inv['postulantes_pendientes'] > 0): ?>
                                <a href="?ruta=mis-postulantes" style="display:inline-block; padding: 0.3rem 0.6rem; border-radius: 100px; background-color: #ef4444; color: white; text-decoration: none; font-weight: bold; font-size: 0.85rem;">
                                    <?= $inv['postulantes_pendientes'] ?> Nuevos
                                </a>
                            <?php else: ?>
                                <span style="color: var(--texto-silenciado); font-size: 0.85rem;">0 Pendientes</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 1rem; text-align: right;">
                            <a href="?ruta=editar-investigacion&id=<?= $inv['id'] ?>" style="color: var(--color-terciario); margin-right: 1rem; text-decoration: none;" title="Editar">
                                <i class="ph-bold ph-pencil-simple" style="font-size: 1.2rem;"></i>
                            </a>
                            <form action="?ruta=eliminar-investigacion" method="POST" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar esta investigación?');">
                                <input type="hidden" name="id" value="<?= $inv['id'] ?>">
                                <button type="submit" style="background:none; border:none; color: #ef4444; cursor: pointer;" title="Eliminar">
                                    <i class="ph-bold ph-trash" style="font-size: 1.2rem;"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
