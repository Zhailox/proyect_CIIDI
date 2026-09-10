<?php
// modules/Investigaciones/views/panel_admin_investigaciones.php
// Variables: $investigaciones (array), $postulaciones (array)
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

    <h1 style="color: var(--texto-titulos); font-size: 2rem; margin-bottom: 0.5rem;">Panel Administrativo I+D</h1>
    <p style="color: var(--texto-silenciado); margin-bottom: 2rem;">Control total sobre investigaciones y postulaciones de toda la institución.</p>

    <!-- ESTADÍSTICAS -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 3rem;">
        <div style="background: #fff; padding: 1.5rem; border-radius: 8px; border: 1px solid var(--gris); border-bottom: 3px solid var(--color-secundario);">
            <div style="color: var(--texto-silenciado); font-size: 0.85rem; text-transform: uppercase; font-weight: bold;">Total Investigaciones</div>
            <div style="font-size: 2rem; font-weight: 800; color: var(--texto-titulos);"><?= count($investigaciones) ?></div>
        </div>
        <div style="background: #fff; padding: 1.5rem; border-radius: 8px; border: 1px solid var(--gris); border-bottom: 3px solid #10b981;">
            <div style="color: var(--texto-silenciado); font-size: 0.85rem; text-transform: uppercase; font-weight: bold;">Proyectos Activos</div>
            <div style="font-size: 2rem; font-weight: 800; color: var(--texto-titulos);"><?= count(array_filter($investigaciones, fn($i) => $i['estado'] === 'Abierta' || $i['estado'] === 'En Desarrollo')) ?></div>
        </div>
        <div style="background: #fff; padding: 1.5rem; border-radius: 8px; border: 1px solid var(--gris); border-bottom: 3px solid var(--color-terciario);">
            <div style="color: var(--texto-silenciado); font-size: 0.85rem; text-transform: uppercase; font-weight: bold;">Total Postulaciones</div>
            <div style="font-size: 2rem; font-weight: 800; color: var(--texto-titulos);"><?= count($postulaciones) ?></div>
        </div>
        <div style="background: #fff; padding: 1.5rem; border-radius: 8px; border: 1px solid var(--gris); border-bottom: 3px solid #ef4444;">
            <div style="color: var(--texto-silenciado); font-size: 0.85rem; text-transform: uppercase; font-weight: bold;">Postulaciones Pendientes</div>
            <div style="font-size: 2rem; font-weight: 800; color: var(--texto-titulos);"><?= count(array_filter($postulaciones, fn($p) => $p['estado'] === 'Pendiente')) ?></div>
        </div>
    </div>

    <!-- TABLA DE INVESTIGACIONES -->
    <h2 style="color: var(--texto-titulos); font-size: 1.5rem; margin-bottom: 1rem;">Directorio de Investigaciones</h2>
    <div style="background: #fff; border: 1px solid var(--gris); border-radius: 8px; overflow-x: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 4rem;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background: #f8fafc; border-bottom: 2px solid var(--gris);">
                <tr>
                    <th style="padding: 1rem; color: var(--texto-titulos);">ID / Proyecto</th>
                    <th style="padding: 1rem; color: var(--texto-titulos);">Profesor Cargo</th>
                    <th style="padding: 1rem; color: var(--texto-titulos);">Estado</th>
                    <th style="padding: 1rem; color: var(--texto-titulos); text-align: right;">Controles Administrativos</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($investigaciones as $inv): ?>
                <tr style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                    <td style="padding: 1rem;">
                        <span style="color: var(--texto-silenciado); font-size: 0.8rem; font-family: monospace;">#<?= $inv['id'] ?></span><br>
                        <strong style="color: var(--color-secundario);"><?= htmlspecialchars($inv['titulo']) ?></strong>
                        <div style="font-size: 0.85rem; color: var(--texto-silenciado); margin-top: 0.3rem;">
                            <?= htmlspecialchars($inv['linea_nombre']) ?>
                        </div>
                    </td>
                    <td style="padding: 1rem; color: var(--texto-comun);"><?= htmlspecialchars($inv['profesor']) ?></td>
                    <td style="padding: 1rem;">
                        <span style="display:inline-block; padding: 0.3rem 0.6rem; border-radius: 4px; font-size: 0.8rem; font-weight:bold; color:#fff; background-color: <?= $inv['estado'] === 'Abierta' ? '#10b981' : ($inv['estado'] === 'En Desarrollo' ? '#f59e0b' : ($inv['estado'] === 'Cerrada' ? '#ef4444' : '#3b82f6')) ?>;">
                            <?= htmlspecialchars($inv['estado']) ?>
                        </span>
                    </td>
                    <td style="padding: 1rem; text-align: right;">
                        <form action="?ruta=cambiar-estado-investigacion" method="POST" style="display:inline-flex; align-items:center; gap: 0.5rem;">
                            <input type="hidden" name="id" value="<?= $inv['id'] ?>">
                            <select name="estado" style="padding: 0.4rem; border: 1px solid var(--gris); border-radius: 4px; font-size: 0.85rem;">
                                <option value="Abierta" <?= $inv['estado'] == 'Abierta'?'selected':'' ?>>Abierta</option>
                                <option value="En Desarrollo" <?= $inv['estado'] == 'En Desarrollo'?'selected':'' ?>>En Desarrollo</option>
                                <option value="Finalizada" <?= $inv['estado'] == 'Finalizada'?'selected':'' ?>>Finalizada</option>
                                <option value="Cerrada" <?= $inv['estado'] == 'Cerrada'?'selected':'' ?>>Cerrada (Baja)</option>
                            </select>
                            <button type="submit" style="background:var(--color-terciario); border:none; color:white; padding: 0.4rem 0.8rem; border-radius: 4px; cursor: pointer; font-size: 0.8rem;">Actualizar</button>
                        </form>
                        <form action="?ruta=eliminar-investigacion" method="POST" style="display:inline; margin-left: 1rem;" onsubmit="return confirm('ATENCIÓN: Se eliminará permanentemente la investigación y todas sus postulaciones. ¿Proceder?');">
                            <input type="hidden" name="id" value="<?= $inv['id'] ?>">
                            <input type="hidden" name="from_admin" value="1">
                            <button type="submit" style="background:none; border:none; color: #ef4444; cursor: pointer;" title="Eliminar Forzosamente">
                                <i class="ph-bold ph-trash" style="font-size: 1.2rem;"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- TABLA GLOBAL DE POSTULACIONES -->
    <h2 style="color: var(--texto-titulos); font-size: 1.5rem; margin-bottom: 1rem;">Supervisión de Postulaciones</h2>
    <div style="background: #fff; border: 1px solid var(--gris); border-radius: 8px; overflow-x: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background: #f8fafc; border-bottom: 2px solid var(--gris);">
                <tr>
                    <th style="padding: 1rem; color: var(--texto-titulos);">Estudiante</th>
                    <th style="padding: 1rem; color: var(--texto-titulos);">Proyecto y Profesor</th>
                    <th style="padding: 1rem; color: var(--texto-titulos);">Fecha</th>
                    <th style="padding: 1rem; color: var(--texto-titulos);">Estado</th>
                    <th style="padding: 1rem; color: var(--texto-titulos); text-align: right;">Anular/Gestionar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($postulaciones as $p): ?>
                <tr style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                    <td style="padding: 1rem;">
                        <div style="font-weight: bold; color: var(--texto-titulos);"><?= htmlspecialchars($p['estudiante']) ?></div>
                    </td>
                    <td style="padding: 1rem;">
                        <strong style="color: var(--color-secundario); font-size: 0.9rem;"><?= htmlspecialchars($p['investigacion_titulo']) ?></strong>
                        <div style="font-size: 0.85rem; color: var(--texto-silenciado);">Prof. <?= htmlspecialchars($p['profesor']) ?></div>
                    </td>
                    <td style="padding: 1rem; font-size: 0.85rem; color: var(--texto-comun);">
                        <?= date('d/m/Y', strtotime($p['fecha_postulacion'])) ?>
                    </td>
                    <td style="padding: 1rem;">
                        <span style="display:inline-block; padding: 0.3rem 0.6rem; border-radius: 4px; font-size: 0.8rem; font-weight:bold; color:#fff; background-color: <?= $p['estado'] === 'Pendiente' ? '#f59e0b' : ($p['estado'] === 'Aceptado' ? '#10b981' : '#ef4444') ?>;">
                            <?= htmlspecialchars($p['estado']) ?>
                        </span>
                    </td>
                    <td style="padding: 1rem; text-align: right;">
                        <form action="?ruta=responder-postulacion" method="POST" style="display:inline;">
                            <input type="hidden" name="id_postulacion" value="<?= $p['id'] ?>">
                            <input type="hidden" name="from_admin" value="1">
                            <select name="estado" style="padding: 0.4rem; border: 1px solid var(--gris); border-radius: 4px; font-size: 0.85rem;">
                                <option value="Aceptado" <?= $p['estado'] == 'Aceptado'?'selected':'' ?>>Aceptar</option>
                                <option value="Rechazado" <?= $p['estado'] == 'Rechazado'?'selected':'' ?>>Rechazar</option>
                                <option value="Pendiente" <?= $p['estado'] == 'Pendiente'?'selected':'' ?>>Pendiente</option>
                            </select>
                            <button type="submit" style="background:var(--color-terciario); border:none; color:white; padding: 0.4rem 0.8rem; border-radius: 4px; cursor: pointer; font-size: 0.8rem; margin-left: 0.5rem;">Fijar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
