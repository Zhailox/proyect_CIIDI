<?php
// modules/Investigaciones/views/form_investigacion.php
// Variables: $investigacion (array), $lineas (array), $dimensiones (array - opcional)
$esEdicion = !empty($investigacion['id']);
?>
<div class="inv-wrapper" style="max-width: 800px; margin: 0 auto;">

    <div style="display: flex; align-items: center; margin-bottom: 2rem; gap: 1rem;">
        <a href="?ruta=mis-investigaciones" style="color: var(--texto-silenciado); font-size: 1.5rem; text-decoration:none;">
            <i class="ph-bold ph-arrow-left"></i>
        </a>
        <h1 style="color: var(--texto-titulos); font-size: 2rem; margin: 0;">
            <?= $esEdicion ? 'Editar Investigación' : 'Crear Nueva Investigación' ?>
        </h1>
    </div>

    <?php if (isset($_SESSION['flash_error'])): ?>
    <div style="background: #ef4444; color: white; padding: 1rem; border-radius: 4px; text-align: center; font-weight: bold; margin-bottom: 1rem;">
        <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
    </div>
    <?php endif; ?>

    <form action="?ruta=<?= $esEdicion ? 'actualizar-investigacion' : 'guardar-investigacion' ?>" method="POST" enctype="multipart/form-data" style="background: #fff; padding: 2rem; border-radius: 8px; border: 1px solid var(--gris); box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        
        <?php if ($esEdicion): ?>
            <input type="hidden" name="id" value="<?= $investigacion['id'] ?>">
        <?php endif; ?>

        <div class="inv-form-group">
            <label>Título del Proyecto <span style="color:red">*</span></label>
            <input type="text" name="titulo" class="inv-form-control" value="<?= htmlspecialchars($investigacion['titulo'] ?? '') ?>" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="inv-form-group">
                <label>Línea de Investigación <span style="color:red">*</span></label>
                <select name="id_linea" class="inv-form-control" required>
                    <option value="">Seleccione una línea</option>
                    <?php foreach ($lineas as $l): ?>
                        <option value="<?= $l['id'] ?>" <?= ($investigacion['id_linea'] == $l['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($l['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="inv-form-group">
                <label>Estado</label>
                <select name="estado" class="inv-form-control">
                    <?php $estados = ['Abierta', 'En Desarrollo', 'Cerrada', 'Finalizada']; ?>
                    <?php foreach ($estados as $est): ?>
                        <option value="<?= $est ?>" <?= ($investigacion['estado'] == $est) ? 'selected' : '' ?>><?= $est ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="inv-form-group">
                <label>Trayecto Académico Objetivo</label>
                <select name="trayecto" class="inv-form-control">
                    <option value="t3" <?= (($investigacion['trayecto'] ?? '') == 't3') ? 'selected' : '' ?>>Trayecto III (T3)</option>
                    <option value="t4" <?= (($investigacion['trayecto'] ?? '') == 't4') ? 'selected' : '' ?>>Trayecto IV (T4)</option>
                    <option value="maestria" <?= (($investigacion['trayecto'] ?? '') == 'maestria') ? 'selected' : '' ?>>Maestría</option>
                </select>
            </div>
            
            <div class="inv-form-group">
                <label>Cupos Disponibles</label>
                <input type="number" name="cupos_disponibles" class="inv-form-control" min="1" max="10" value="<?= $investigacion['cupos_disponibles'] ?? 3 ?>">
            </div>
        </div>

        <div class="inv-form-group">
            <label>Planteamiento del Problema / Resumen <span style="color:red">*</span></label>
            <textarea name="planteamiento_problema" class="inv-form-control" rows="4" required><?= htmlspecialchars($investigacion['planteamiento_problema'] ?? '') ?></textarea>
        </div>

        <div class="inv-form-group">
            <label>Objetivo General / Meta</label>
            <textarea name="objetivo_general" class="inv-form-control" rows="2"><?= htmlspecialchars($investigacion['objetivo_general'] ?? '') ?></textarea>
        </div>
        
        <div class="inv-form-group" style="padding: 1.5rem; background: #f8fafc; border: 1px dashed var(--gris); margin-top: 2rem; border-radius: 6px;">
            <label style="color: var(--color-secundario); font-size: 1.1rem; margin-bottom: 1rem;"><i class="ph-bold ph-image"></i> Portada del Proyecto</label>
            
            <?php if (!empty($investigacion['imagen'])): ?>
                <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                    <img src="<?= htmlspecialchars($investigacion['imagen']) ?>" alt="Portada actual" style="width: 120px; height: 80px; object-fit: cover; border-radius: 4px; border: 1px solid var(--gris);">
                    <div style="font-size: 0.85rem; color: var(--texto-silenciado);">Imagen actual activa</div>
                </div>
            <?php endif; ?>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; align-items: end;">
                <div>
                    <label style="font-size: 0.85rem;">Opción 1: Subir Archivo Local</label>
                    <input type="file" name="imagen" class="inv-form-control" accept="image/jpeg, image/png, image/webp" style="background: white;">
                </div>
                <div>
                    <label style="font-size: 0.85rem;">Opción 2: Enlace Web (URL)</label>
                    <input type="url" name="imagen_url" class="inv-form-control" placeholder="https://ejemplo.com/imagen.jpg" value="<?= (strpos($investigacion['imagen'] ?? '', 'http') === 0) ? htmlspecialchars($investigacion['imagen']) : '' ?>">
                </div>
            </div>
            <small style="color: var(--texto-silenciado); display:block; margin-top:1rem;">* Si subes un archivo, este tendrá prioridad sobre el enlace web.</small>
        </div>

        <div style="margin-top: 2rem; text-align: right;">
            <button type="submit" class="inv-btn-primary" style="padding: 1rem 3rem;">
                <?= $esEdicion ? 'Guardar Cambios' : 'Crear Proyecto' ?>
            </button>
        </div>
    </form>
</div>
