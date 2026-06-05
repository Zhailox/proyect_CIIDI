<?php
// templates/curriculum/create.php
ob_start();
?>
<div class="glass-card">
    <h1 class="page-title">Perfil Curricular Cualitativo</h1>
    <p class="page-description">Ingresa los datos de tu trayectoria para aplicar a proyectos como asistente de investigación.</p>

    <?php if (isset($success)): ?>
        <div style="background: rgba(16, 185, 129, 0.2); color: #10b981; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; border: 1px solid rgba(16, 185, 129, 0.3);">
            Currículo analizado y actualizado exitosamente. (Marcador AI: <?= htmlspecialchars($ai_marker ?? '') ?>)
        </div>
    <?php endif; ?>

    <form action="<?= APP_URL ?>/curriculum/store" method="POST">
        <div class="form-group">
            <label for="summary">Resumen Profesional (Cualitativo)</label>
            <textarea id="summary" name="summary" rows="4" required placeholder="Describe tu visión, intereses de investigación y perfil general..."></textarea>
        </div>

        <div class="form-group">
            <label for="education">Formación Académica</label>
            <textarea id="education" name="education" rows="3" required placeholder="Institución, Título, Año de Egreso..."></textarea>
        </div>

        <div class="form-group">
            <label for="skills">Habilidades (Separadas por comas)</label>
            <input type="text" id="skills" name="skills" required placeholder="Python, Diseño de BD, Análisis Estadístico, Redacción Científica">
        </div>

        <button type="submit">Guardar y Analizar Perfil</button>
    </form>
</div>
<?php
$content = ob_get_clean();
$title = 'Mi Currículo';
require __DIR__ . '/../layout.php';
