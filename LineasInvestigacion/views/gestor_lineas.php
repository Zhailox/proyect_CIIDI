<?php
// modules/LineasInvestigacion/views/gestor_lineas.php
// Variables inyectadas por GestorLineasController::index():
//   $lineas (array), $carreras (array), $linea_editar (array|null), $mensaje (string), $tipo_mensaje (string)
?>

<div class="li-gestor-wrapper">

    <!-- ╔══ BANNER ══════════════════════════════════════════════════════════╗ -->
    <div class="li-gestor-banner">
        <div>
            <h1><i class="ph-bold ph-graph" style="margin-right:0.5rem;"></i>Gestión de Líneas de Investigación</h1>
            <p>Crear, editar y eliminar las líneas de investigación del CIIDI. Los cambios se reflejan de inmediato en la base de datos.</p>
        </div>
        <a href="index.php?ruta=lineas-investigacion" class="li-btn-ver" style="width:auto;padding:0.6rem 1.25rem;flex-shrink:0;">
            <i class="ph-bold ph-eye"></i> Ver Vista Pública
        </a>
    </div>

    <!-- ╔══ ALERTA FLASH ════════════════════════════════════════════════════╗ -->
    <?php if (!empty($mensaje)): ?>
    <div class="li-alert <?= htmlspecialchars($tipo_mensaje) ?>">
        <i class="ph-bold <?= $tipo_mensaje === 'exito' ? 'ph-check-circle' : 'ph-warning-circle' ?>"></i>
        <?= $mensaje ?>
    </div>
    <?php endif; ?>

    <!-- ╔══ FORMULARIO CREAR / EDITAR ══════════════════════════════════════╗ -->
    <div class="li-form-card">
        <div class="li-form-header">
            <?php if ($linea_editar): ?>
            <i class="ph-bold ph-pencil-simple" style="color:var(--li-indigo);"></i>
            Editando: <strong><?= htmlspecialchars(ucwords(strtolower($linea_editar['nombre']))) ?></strong>
            <a href="index.php?ruta=gestionar-lineas" style="margin-left:auto;font-size:0.8rem;color:var(--text-muted);">
                <i class="ph-bold ph-x"></i> Cancelar edición
            </a>
            <?php else: ?>
            <i class="ph-bold ph-plus-circle" style="color:var(--li-emerald);"></i>
            Nueva Línea de Investigación
            <?php endif; ?>
        </div>

        <div class="li-form-body">
            <form method="POST" action="index.php?ruta=gestionar-lineas">
                <input type="hidden" name="accion" value="<?= $linea_editar ? 'editar' : 'crear' ?>">
                <?php if ($linea_editar): ?>
                <input type="hidden" name="id" value="<?= (int)$linea_editar['id'] ?>">
                <?php endif; ?>

                <div class="li-form-grid">
                    <!-- Nombre -->
                    <div class="li-form-group li-form-full">
                        <label for="nombre">Nombre de la Línea *</label>
                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            placeholder="Ej. APLICACIONES WEB"
                            value="<?= htmlspecialchars($linea_editar['nombre'] ?? '') ?>"
                            required
                            maxlength="255">
                    </div>

                    <!-- Carrera -->
                    <div class="li-form-group">
                        <label for="id_carrera">Carrera *</label>
                        <select id="id_carrera" name="id_carrera" required>
                            <option value="">— Seleccionar carrera —</option>
                            <?php foreach ($carreras as $carrera): ?>
                            <option value="<?= (int)$carrera['id'] ?>"
                                <?= isset($linea_editar['id_carrera']) && (int)$linea_editar['id_carrera'] === (int)$carrera['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($carrera['nombre']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Descripción -->
                    <div class="li-form-group li-form-full">
                        <label for="descripcion">Descripción *</label>
                        <textarea
                            id="descripcion"
                            name="descripcion"
                            required
                            placeholder="Describe el alcance y objetivos de esta línea de investigación..."><?= htmlspecialchars($linea_editar['descripcion'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="li-form-actions">
                    <?php if ($linea_editar): ?>
                    <a href="index.php?ruta=gestionar-lineas" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn" style="background:var(--li-indigo);color:#fff;">
                        <i class="ph-bold ph-floppy-disk"></i> Guardar Cambios
                    </button>
                    <?php else: ?>
                    <button type="submit" class="btn" style="background:linear-gradient(135deg,var(--li-indigo),var(--li-violet));color:#fff;">
                        <i class="ph-bold ph-plus"></i> Crear Línea
                    </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- ╔══ TABLA DE LÍNEAS ══════════════════════════════════════════════════╗ -->
    <div class="li-table-card">
        <div class="li-table-header">
            <h3>
                <i class="ph-fill ph-graph"></i>
                Líneas Registradas
                <span class="li-tbl-badge" style="margin-left:0.5rem;"><?= count($lineas) ?></span>
            </h3>
            <a href="index.php?ruta=gestionar-dimensiones" class="li-btn-edit">
                <i class="ph-bold ph-squares-four"></i> Gestionar Dimensiones
            </a>
        </div>

        <?php if (empty($lineas)): ?>
            <div class="li-empty-state">
                <i class="ph-bold ph-graph"></i>
                <p>No hay líneas de investigación registradas. Usa el formulario superior para crear la primera.</p>
            </div>
        <?php else: ?>
        <table class="li-table">
            <thead>
                <tr>
                    <th>Línea de Investigación</th>
                    <th>Carrera</th>
                    <th>Dimensiones</th>
                    <th>Proyectos</th>
                    <th>Investigaciones</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lineas as $li): ?>
                <tr>
                    <td>
                        <div class="li-table-nombre">
                            <?= htmlspecialchars(ucwords(strtolower($li['nombre']))) ?>
                        </div>
                        <?php if (!empty($li['descripcion'])): ?>
                        <div style="font-size:0.78rem;color:var(--text-muted);margin-top:0.25rem;
                                    display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden;">
                            <?= htmlspecialchars($li['descripcion']) ?>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:0.82rem;color:var(--text-muted);">
                        <?= htmlspecialchars($li['carrera_nombre'] ?? '—') ?>
                    </td>
                    <td><span class="li-tbl-badge"><?= (int)$li['total_dimensiones'] ?></span></td>
                    <td><span class="li-tbl-badge" style="background:rgba(8,145,178,0.08);color:var(--li-cyan);"><?= (int)$li['total_proyectos'] ?></span></td>
                    <td><span class="li-tbl-badge" style="background:rgba(5,150,105,0.08);color:var(--li-emerald);"><?= (int)$li['total_investigaciones'] ?></span></td>
                    <td>
                        <div class="li-tbl-actions">
                            <!-- Ver detalle público -->
                            <a href="index.php?ruta=detalle-linea&id=<?= (int)$li['id'] ?>"
                               class="li-btn-edit" title="Ver detalle">
                                <i class="ph-bold ph-eye"></i>
                            </a>

                            <!-- Editar -->
                            <a href="index.php?ruta=gestionar-lineas&editar=<?= (int)$li['id'] ?>"
                               class="li-btn-edit" title="Editar">
                                <i class="ph-bold ph-pencil-simple"></i> Editar
                            </a>

                            <!-- Eliminar (con modal de confirmación CSS) -->
                            <label for="modal-del-<?= (int)$li['id'] ?>" class="li-btn-del" title="Eliminar" style="cursor:pointer;">
                                <i class="ph-bold ph-trash"></i> Eliminar
                            </label>

                            <!-- Modal de confirmación -->
                            <input type="checkbox" id="modal-del-<?= (int)$li['id'] ?>" class="li-modal-toggle">
                            <div class="li-modal-overlay">
                                <div class="li-modal-box">
                                    <div class="li-modal-icon"><i class="ph-bold ph-warning"></i></div>
                                    <div class="li-modal-title">¿Eliminar esta línea?</div>
                                    <p class="li-modal-desc">
                                        Estás a punto de eliminar
                                        <strong><?= htmlspecialchars(ucwords(strtolower($li['nombre']))) ?></strong>.
                                        Esta acción también eliminará sus dimensiones operativas.
                                        ¿Confirmas?
                                    </p>
                                    <div class="li-modal-actions">
                                        <label for="modal-del-<?= (int)$li['id'] ?>" class="btn btn-secondary">
                                            Cancelar
                                        </label>
                                        <form method="POST" action="index.php?ruta=gestionar-lineas" style="display:inline;">
                                            <input type="hidden" name="accion" value="eliminar">
                                            <input type="hidden" name="id" value="<?= (int)$li['id'] ?>">
                                            <button type="submit" class="btn li-btn-del" style="font-size:0.85rem;padding:0.5rem 1rem;">
                                                <i class="ph-bold ph-trash"></i> Sí, Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

</div>
