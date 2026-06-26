<?php
// modules/Investigaciones/views/panel_postulaciones.php
// Variables inyectadas por InvestigacionesController::postulaciones():
// $vacantes_por_nivel — array agrupado ['t3'=>[...], 't4'=>[...], 'postgrado'=>[...]]
// $mensaje_exito      — string|null

$config_niveles = [
    't3'        => ['label' => 'Trayecto III',        'sub' => 'Sistemas de Media-Alta Escala'],
    't4'        => ['label' => 'Trayecto IV',          'sub' => 'Ingeniería Avanzada / RAG'],
    'postgrado' => ['label' => 'Postgrado / Maestría', 'sub' => 'Sistemas Expertos e Investigaciones Pesadas'],
];
?>
<div class="post-wrapper">

    <header class="post-hero">
        <div class="post-hero-content">
            <h1>¿Pueden tus ideas cambiar el mundo?</h1>
            <p>Únete a los proyectos de investigación científica e innovación de nuestra institución. Colabora directamente con expertos y desarrolla soluciones de alto impacto regional.</p>
        </div>
    </header>

    <?php if ($mensaje_exito): ?>
    <div class="post-alert-success" id="alert-postulacion">
        <i class="ph-fill ph-check-circle"></i>
        <?= htmlspecialchars($mensaje_exito) ?>
    </div>
    <?php endif; ?>

    <div class="post-filter-container">
        <div class="post-filter-pill-bar">
            <button class="post-filter-btn active" data-nivel="todos">Todas las Líneas</button>
            <button class="post-filter-btn" data-nivel="t3">Trayecto III</button>
            <button class="post-filter-btn" data-nivel="t4">Trayecto IV</button>
            <button class="post-filter-btn" data-nivel="postgrado">Postgrado</button>
        </div>
    </div>

    <div class="post-kanban-board">
        <?php foreach ($config_niveles as $nivel => $cfg): ?>
        <div class="post-kanban-col" data-nivel="<?= $nivel ?>">
            <div class="post-col-header">
                <h3><?= $cfg['label'] ?></h3>
                <span><?= $cfg['sub'] ?></span>
            </div>

            <?php if (empty($vacantes_por_nivel[$nivel])): ?>
                <p class="post-empty-col">Sin vacantes disponibles en este nivel.</p>
            <?php else: ?>
            <?php foreach ($vacantes_por_nivel[$nivel] as $v): ?>
            <div class="post-card" data-vacante-id="<?= $v['id'] ?>" data-titulo="<?= htmlspecialchars($v['titulo_rol']) ?>"
                 onclick="abrirModalPostulacion(<?= $v['id'] ?>, '<?= htmlspecialchars(addslashes($v['titulo_rol'])) ?>', '<?= htmlspecialchars(addslashes($v['proyecto_titulo'])) ?>')">
                <span class="post-card-linea">Línea: <?= htmlspecialchars($v['linea_nombre']) ?></span>
                <h4><?= htmlspecialchars($v['titulo_rol']) ?></h4>
                <p><?= htmlspecialchars($v['descripcion']) ?></p>
                <div class="post-card-footer">
                    <?php if ($v['cupo_disponible'] > 0): ?>
                        <span class="post-vacancies">● <?= $v['cupo_disponible'] ?> Vacante<?= $v['cupo_disponible'] > 1 ? 's' : '' ?></span>
                    <?php else: ?>
                        <span class="post-vacancies" style="color: #ef4444;">● Sin cupos</span>
                    <?php endif; ?>
                    <button class="post-btn-apply" <?= $v['cupo_disponible'] <= 0 ? 'disabled style="opacity:0.4; cursor:not-allowed;"' : '' ?>>
                        Aplicar
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<!-- MODAL DE POSTULACIÓN -->
<div class="post-modal-overlay" id="modal-postulacion" style="display:none;">
    <div class="post-modal-card">
        <div class="post-modal-header">
            <div>
                <h2 id="modal-titulo-rol">Postulación a Proyecto</h2>
                <p id="modal-proyecto-nombre" style="color: var(--texto-silenciado); font-size:0.9rem; margin-top:0.3rem;"></p>
            </div>
            <button class="post-modal-close" onclick="cerrarModal()" title="Cerrar">
                <i class="ph-bold ph-x"></i>
            </button>
        </div>
        <form method="POST" action="?ruta=aplicar-investigacion">
            <input type="hidden" name="vacante_id" id="input-vacante-id">

            <div class="post-form-group">
                <label>Nombre Completo *</label>
                <input type="text" name="nombre_solicitante" class="post-form-control" placeholder="Ej: Juan Pérez" required>
            </div>
            <div class="post-form-row">
                <div class="post-form-group">
                    <label>Cédula de Identidad</label>
                    <input type="text" name="cedula" class="post-form-control" placeholder="V-00.000.000">
                </div>
                <div class="post-form-group">
                    <label>Correo Electrónico *</label>
                    <input type="email" name="email" class="post-form-control" placeholder="tu@correo.com" required>
                </div>
            </div>
            <div class="post-form-group">
                <label>Motivación Personal *</label>
                <textarea name="motivacion" class="post-form-control" rows="4"
                    placeholder="¿Por qué deseas unirte a este proyecto? Cuéntanos sobre tus intereses y habilidades..." required></textarea>
            </div>
            <div class="post-form-group">
                <label>Enlace a Portafolio / GitHub <span style="color:var(--texto-silenciado); font-size:0.8rem;">(Opcional)</span></label>
                <input type="url" name="portfolio_url" class="post-form-control" placeholder="https://github.com/tu-usuario">
            </div>
            <div style="margin-top: 2rem;">
                <button class="post-btn-submit" type="submit">
                    <i class="ph-fill ph-paper-plane-tilt"></i>
                    ENVIAR SOLICITUD DE INGRESO
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirModalPostulacion(vacante_id, tituloRol, proyectoNombre) {
    document.getElementById('input-vacante-id').value   = vacante_id;
    document.getElementById('modal-titulo-rol').textContent       = tituloRol;
    document.getElementById('modal-proyecto-nombre').textContent  = 'Proyecto: ' + proyectoNombre;
    document.getElementById('modal-postulacion').style.display    = 'flex';
    document.body.style.overflow = 'hidden';
}
function cerrarModal() {
    document.getElementById('modal-postulacion').style.display = 'none';
    document.body.style.overflow = '';
}
// Cierra al click fuera del card
document.getElementById('modal-postulacion')?.addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
// Ocultar alerta de éxito tras 5 segundos
const alertEl = document.getElementById('alert-postulacion');
if (alertEl) setTimeout(() => alertEl.style.opacity = '0', 5000);
</script>