<?php
// modules/Autenticacion/views/partials/selector_pst.php
//
// Selector de "Profesor PST" + Selección múltiple de Trayectos (sin trimestres).
// Variables opcionales antes de incluir:
//   $pstEsProfesor   (bool)   Marca inicial de profesor PST.
//   $pstSeleccion    (array)  Números de trayecto seleccionados (ej. [1, 2] o ['1', '2']).
//   $pstIdPrefijo    (string) Prefijo para IDs únicos si hay varios selectores en la página.
//   $pstMostrarCheck (bool)   Mostrar el interruptor "Soy profesor PST" (por defecto true).
require_once __DIR__ . '/../../services/PerfilPSTService.php';

$pstEsProfesor   = $pstEsProfesor ?? false;
$pstSeleccion    = PerfilPSTService::parsearTrayectos($pstSeleccion ?? []);
$pstIdPrefijo    = preg_replace('/[^a-zA-Z0-9_]/', '', $pstIdPrefijo ?? 'pst');
$pstMostrarCheck = $pstMostrarCheck ?? true;
$pstCatTrayectos = PerfilPSTService::catalogoTrayectos();
$descTrayectos   = PerfilPSTService::descripcionesTrayectos();
?>
<div class="pst-selector" id="<?= $pstIdPrefijo ?>-selector" style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 14px 16px; background: #f8fafc; margin-bottom: 1rem;">
    <?php if ($pstMostrarCheck): ?>
    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-weight: 700; color: #121a3e; font-size: 0.95rem; margin-bottom: 4px;">
        <input type="checkbox" name="es_profesor_pst" value="1" id="<?= $pstIdPrefijo ?>-check" <?= $pstEsProfesor ? 'checked' : '' ?>
               onchange="document.getElementById('<?= $pstIdPrefijo ?>-matriz').style.display = this.checked ? 'block' : 'none';"
               style="width: 18px; height: 18px; accent-color: #505984; cursor: pointer;">
        Soy profesor(a) de Proyecto Sociotecnológico (PST)
    </label>
    <?php else: ?>
        <input type="hidden" name="es_profesor_pst" value="1">
    <?php endif; ?>

    <div id="<?= $pstIdPrefijo ?>-matriz" style="display: <?= ($pstEsProfesor || !$pstMostrarCheck) ? 'block' : 'none' ?>; margin-top: 12px;">
        <p style="font-size: 0.82rem; color: #64748b; margin: 0 0 10px 0;">
            Seleccione el o los <strong>Trayectos</strong> en los que imparte o tutoriza PST. Puede seleccionar varios según su asignación académica.
        </p>
        <div style="border-radius: 8px; overflow: hidden; border: 1px solid #cbd5e1; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; background: #fff;">
                <thead>
                    <tr style="background: #505984 !important; border-bottom: 2px solid #3c456a;">
                        <th style="padding: 10px 14px; text-align: left; color: #ffffff !important; font-weight: 700; font-size: 0.88rem; letter-spacing: 0.02em;">
                            Trayecto Académico
                        </th>
                        <th style="padding: 10px 14px; text-align: center; color: #ffffff !important; font-weight: 700; font-size: 0.88rem; width: 140px; letter-spacing: 0.02em;">
                            ¿Dicta PST?
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pstCatTrayectos as $numTray => $nomTray):
                        $marcado = in_array((int)$numTray, $pstSeleccion, true);
                    ?>
                    <tr style="border-bottom: 1px solid #e2e8f0; transition: background 0.15s ease;">
                        <td style="padding: 10px 14px;">
                            <label for="<?= $pstIdPrefijo ?>-t-<?= $numTray ?>" style="cursor: pointer; display: block; margin: 0;">
                                <strong style="color: #121a3e; font-size: 0.92rem;"><?= htmlspecialchars($nomTray) ?></strong>
                                <span style="display: block; font-size: 0.76rem; color: #64748b; margin-top: 2px;">
                                    <?= htmlspecialchars($descTrayectos[$numTray] ?? '') ?>
                                </span>
                            </label>
                        </td>
                        <td style="padding: 10px 14px; text-align: center; vertical-align: middle;">
                            <!-- Enviamos tanto pst_trayectos[] como pst_asignaciones[] para compatibilidad -->
                            <input type="checkbox" name="pst_trayectos[]" value="<?= $numTray ?>" id="<?= $pstIdPrefijo ?>-t-<?= $numTray ?>"
                                   <?= $marcado ? 'checked' : '' ?>
                                   style="width: 18px; height: 18px; accent-color: #505984; cursor: pointer;">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
