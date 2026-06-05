<?php
// templates/projects/create.php
ob_start();
?>
<div class="glass-card">
    <h1 class="page-title">Formulación de Proyecto</h1>
    <p class="page-description">Inscribe una nueva investigación vinculada a una línea taxonómica activa.</p>

    <?php if (isset($success)): ?>
        <div style="background: rgba(16, 185, 129, 0.2); color: #10b981; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; border: 1px solid rgba(16, 185, 129, 0.3);">
            Proyecto "<?= htmlspecialchars($success) ?>" registrado exitosamente con estatus Borrador.
        </div>
    <?php endif; ?>

    <form action="<?= APP_URL ?>/projects/store" method="POST">
        <div class="form-group">
            <label for="title">Título de la Investigación</label>
            <input type="text" id="title" name="title" required placeholder="Ej: Análisis Cuantitativo de Algoritmos...">
        </div>

        <div class="form-group">
            <label for="taxonomic_line_id">Línea Taxonómica</label>
            <select id="taxonomic_line_id" name="taxonomic_line_id" required>
                <option value="">Seleccione una línea de investigación...</option>
                <option value="1">Inteligencia Artificial y NLP</option>
                <option value="2">Ingeniería de Software y Arquitectura</option>
                <option value="3">Seguridad Informática y Redes</option>
            </select>
        </div>

        <div class="form-group">
            <label for="abstract">Resumen / Abstract</label>
            <textarea id="abstract" name="abstract" rows="5" required placeholder="Describa el objetivo principal y la metodología propuesta..."></textarea>
        </div>

        <button type="submit">Guardar Borrador del Proyecto</button>
    </form>
</div>
<?php
$content = ob_get_clean();
$title = 'Crear Proyecto';
require __DIR__ . '/../layout.php';
