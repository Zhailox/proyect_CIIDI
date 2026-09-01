<?php
// modules/LineasInvestigacion/views/gestor_dimensiones.php
// Variables inyectadas por GestorLineasController::dimensiones():
//   $dimensiones (array), $lineas (array), $dim_editar (array|null), $mensaje (string), $tipo_mensaje (string)
?>

<div class="li-gestor-wrapper">

    <!-- ╔══ BANNER ══════════════════════════════════════════════════════════╗ -->
    <div class="li-gestor-banner">
        <div>
            <h1><i class="ph-bold ph-squares-four" style="margin-right:0.5rem;"></i>Gestión de Dimensiones Operativas</h1>
            <p>Administra las dimensiones que agrupan proyectos dentro de cada línea de investigación.</p>
        </div>
        <a href="index.php?ruta=gestionar-lineas" class="li-btn-ver" style="width:auto;padding:0.6rem 1.25rem;flex-shrink:0;">
            <i class="ph-bold ph-arrow-left"></i> Volver a Líneas
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
            <?php if ($dim_editar): ?>
            <i class="ph-bold ph-pencil-simple" style="color:var(--li-indigo);"></i>
            Editando Dimensión: <strong><?= htmlspecialchars($dim_editar['nombre']) ?></strong>
            <a href="index.php?ruta=gestionar-dimensiones" style="margin-left:auto;font-size:0.8rem;color:var(--text-muted);">
                <i class="ph-bold ph-x"></i> Cancelar edición
            </a>
            <?php else: ?>
            <i class="ph-bold ph-plus-circle" style="color:var(--li-emerald);"></i>
            Nueva Dimensión Operativa
            <?php endif; ?>
        </div>

        <div class="li-form-body">
            <form method="POST" action="index.php?ruta=gestionar-dimensiones">
                <input type="hidden" name="accion" value="<?= $dim_editar ? 'editar' : 'crear' ?>">
                <?php if ($dim_editar): ?>
                <input type="hidden" name="id" value="<?= (int)$dim_editar['id'] ?>">
                <?php endif; ?>

                <div class="li-form-grid">
                    <!-- Línea padre -->
                    <div class="li-form-group">
                        <label for="id_linea">Línea de Investigación *</label>
                        <select id="id_linea" name="id_linea" required>
                            <option value="">— Seleccionar línea —</option>
                            <?php foreach ($lineas as $li): ?>
                            <option value="<?= (int)$li['id'] ?>"
                                <?= isset($dim_editar['id_linea']) && (int)$dim_editar['id_linea'] === (int)$li['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars(ucwords(strtolower($li['nombre']))) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Nombre -->
                    <div class="li-form-group">
                        <label for="nombre_dim">Nombre de la Dimensión *</label>
                        <input
                            type="text"
                            id="nombre_dim"
                            name="nombre"
                            placeholder="Ej. Sistemas de información web"
                            value="<?= htmlspecialchars($dim_editar['nombre'] ?? '') ?>"
                            required
                            maxlength="150">
                    </div>

                    <!-- Descripción -->
                    <div class="li-form-group li-form-full">
                        <label for="descripcion_dim">Descripción</label>
                        <textarea
                            id="descripcion_dim"
                            name="descripcion"
                            placeholder="Describe el alcance conceptual de esta dimensión operativa..."><?= htmlspecialchars($dim_editar['descripcion'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="li-form-actions">
                    <?php if ($dim_editar): ?>
                    <a href="index.php?ruta=gestionar-dimensiones" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn" style="background:var(--li-indigo);color:#fff;">
                        <i class="ph-bold ph-floppy-disk"></i> Guardar Cambios
                    </button>
                    <?php else: ?>
                    <button type="submit" class="btn" style="background:linear-gradient(135deg,var(--li-indigo),var(--li-violet));color:#fff;">
                        <i class="ph-bold ph-plus"></i> Crear Dimensión
                    </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- ╔══ TABLA DE DIMENSIONES ═════════════════════════════════════════════╗ -->
    <div class="li-table-card">
        <div class="li-table-header">
            <h3>
                <i class="ph-fill ph-squares-four"></i>
                Dimensiones Registradas
                <span class="li-tbl-badge" style="margin-left:0.5rem;"><?= count($dimensiones) ?></span>
            </h3>
        </div>

        <?php if (empty($dimensiones)): ?>
            <div class="li-empty-state">
                <i class="ph-bold ph-squares-four"></i>
                <p>No hay dimensiones operativas registradas. Crea la primera usando el formulario de arriba.</p>
            </div>
        <?php else: ?>
        <table class="li-table">
            <thead>
                <tr>
                    <th>Dimensión Operativa</th>
                    <th>Línea de Investigación</th>
                    <th>Descripción (Resumen)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dimensiones as $dim): ?>
                <tr>
                    <td>
                        <div class="li-table-nombre" style="font-size:0.88rem;">
                            <?= htmlspecialchars($dim['nombre']) ?>
                        </div>
                    </td>
                    <td>
                        <span class="li-tbl-badge">
                            <?= htmlspecialchars(ucwords(strtolower($dim['linea_nombre'] ?? '—'))) ?>
                        </span>
                    </td>
                    <td style="font-size:0.8rem;color:var(--text-muted);max-width:280px;">
                        <?php if (!empty($dim['descripcion'])): ?>
                        <span style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                            <?= htmlspecialchars($dim['descripcion']) ?>
                        </span>
                        <?php else: ?>
                        <em>Sin descripción</em>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="li-tbl-actions">

                            <!-- Editar -->
                            <a href="index.php?ruta=gestionar-dimensiones&editar=<?= (int)$dim['id'] ?>"
                               class="li-btn-edit">
                                <i class="ph-bold ph-pencil-simple"></i> Editar
                            </a>

                            <!-- Eliminar con modal CSS -->
                            <label for="modal-dim-del-<?= (int)$dim['id'] ?>" class="li-btn-del" style="cursor:pointer;">
                                <i class="ph-bold ph-trash"></i> Eliminar
                            </label>

                            <input type="checkbox" id="modal-dim-del-<?= (int)$dim['id'] ?>" class="li-modal-toggle">
                            <div class="li-modal-overlay">
                                <div class="li-modal-box">
                                    <div class="li-modal-icon"><i class="ph-bold ph-warning"></i></div>
                                    <div class="li-modal-title">¿Eliminar esta dimensión?</div>
                                    <p class="li-modal-desc">
                                        Estás a punto de eliminar
                                        <strong><?= htmlspecialchars($dim['nombre']) ?></strong>.
                                        Los proyectos clasificados en esta dimensión perderán esta asociación.
                                    </p>
                                    <div class="li-modal-actions">
                                        <label for="modal-dim-del-<?= (int)$dim['id'] ?>" class="btn btn-secondary">
                                            Cancelar
                                        </label>
                                        <form method="POST" action="index.php?ruta=gestionar-dimensiones" style="display:inline;">
                                            <input type="hidden" name="accion" value="eliminar">
                                            <input type="hidden" name="id" value="<?= (int)$dim['id'] ?>">
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
