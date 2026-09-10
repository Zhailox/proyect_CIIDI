<?php
// modules/Investigaciones/views/panel_postulaciones.php
// Variables: $agrupadas, $lineas, $filtro_linea, $misPostulaciones, $postuladas_ids
require_once CORE_PATH . 'Security/Auth.php';
$is_logged = Auth::check();
?>
<div class="post-wrapper">

    <?php if (isset($_SESSION['flash_success'])): ?>
    <div style="background: #10b981; color: white; padding: 1rem; text-align: center; font-weight: bold; margin-bottom: 1rem; border-radius: 4px;">
        <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
    </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['flash_error'])): ?>
    <div style="background: #ef4444; color: white; padding: 1rem; text-align: center; font-weight: bold; margin-bottom: 1rem; border-radius: 4px;">
        <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
    </div>
    <?php endif; ?>

    <header class="post-hero">
		<div class="post-hero-content">
			<h1>¿Pueden tus ideas cambiar al mundo?</h1>
			<p>Únete a los proyectos de investigación científica e innovación de nuestra institución. Colabora directamente con expertos y desarrolla soluciones de alto impacto regional.</p>
		</div>
	</header>

    <?php if ($is_logged && !empty($misPostulaciones)): ?>
    <!-- SECCIÓN DE MIS POSTULACIONES -->
    <div style="background: #f8fafc; border: 1px solid var(--gris); border-radius: 8px; padding: 1.5rem; margin: 2rem 0; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="color: var(--texto-titulos); font-size: 1.2rem; margin-top: 0; margin-bottom: 1rem;"><i class="ph-fill ph-check-circle"></i> Mis Postulaciones Enviadas</h2>
        <div style="display: flex; gap: 1rem; overflow-x: auto; padding-bottom: 0.5rem;">
            <?php foreach($misPostulaciones as $mp): ?>
            <div style="background: white; border: 1px solid var(--gris); border-radius: 6px; padding: 1rem; min-width: 280px; flex: 0 0 auto;">
                <div style="font-weight: bold; color: var(--color-secundario); margin-bottom: 0.5rem; font-size: 0.95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($mp['investigacion_titulo']) ?>">
                    <?= htmlspecialchars($mp['investigacion_titulo']) ?>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.8rem; color: var(--texto-silenciado);"><?= date('d/m/Y', strtotime($mp['fecha_postulacion'])) ?></span>
                    <span style="font-size: 0.75rem; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: bold; color: white; background-color: <?= $mp['estado']==='Pendiente'?'#f59e0b':($mp['estado']==='Aceptado'?'#10b981':'#ef4444') ?>;">
                        <?= htmlspecialchars($mp['estado']) ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- FILTROS -->
    <div class="post-filter-container">
        <div class="post-filter-pill-bar">
            <a href="?ruta=postulaciones-investigacion" class="post-filter-btn <?= empty($filtro_linea) ? 'active' : '' ?>" style="text-decoration:none;">
                Todas las Líneas
            </a>
            <?php foreach ($lineas as $l): ?>
            <a href="?ruta=postulaciones-investigacion&linea=<?= $l['id'] ?>" class="post-filter-btn <?= ($filtro_linea == $l['id']) ? 'active' : '' ?>" style="text-decoration:none;">
                <?= htmlspecialchars($l['nombre']) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- KANBAN BOARD (3 columns: t3, t4, maestria) -->
    <div class="post-kanban-board">
        
        <!-- COLUMNA TRAYECTO III -->
        <div class="post-kanban-col" data-nivel="t3">
            <div class="post-col-header">
                <h3>Trayecto III</h3>
                <span>Sistemas de Media-Alta Escala</span>
            </div>
            <?php foreach ($agrupadas['t3'] as $inv): ?>
            <?php $yaPostulado = in_array($inv['id'], $postuladas_ids); ?>
            <div class="post-card">
                <span class="post-card-linea">Línea: <?= htmlspecialchars($inv['linea_nombre']) ?></span>
                <h4><?= htmlspecialchars($inv['titulo']) ?></h4>
                <p><?= htmlspecialchars(mb_substr($inv['planteamiento_problema'], 0, 100)) ?>...</p>
                <div class="post-card-footer">
                    <span class="post-vacancies">● <?= (int)$inv['cupos_disponibles'] ?> Vacante(s)</span>
                    <?php if ($yaPostulado): ?>
                        <span style="font-size: 0.8rem; font-weight: bold; color: #f59e0b;"><i class="ph-bold ph-check"></i> Postulado</span>
                    <?php else: ?>
                        <button class="post-btn-apply" onclick="abrirModal(<?= $inv['id'] ?>, '<?= htmlspecialchars(addslashes($inv['titulo'])) ?>')">Aplicar</button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if(empty($agrupadas['t3'])): ?><div style="text-align:center; color:var(--texto-silenciado); padding:2rem 0; font-size:0.9rem;">No hay proyectos disponibles.</div><?php endif; ?>
        </div>

        <!-- COLUMNA TRAYECTO IV -->
        <div class="post-kanban-col" data-nivel="t4">
            <div class="post-col-header">
                <h3>Trayecto IV</h3>
                <span>Arquitecturas de Software Avanzadas</span>
            </div>
            <?php foreach ($agrupadas['t4'] as $inv): ?>
            <?php $yaPostulado = in_array($inv['id'], $postuladas_ids); ?>
            <div class="post-card">
                <span class="post-card-linea">Línea: <?= htmlspecialchars($inv['linea_nombre']) ?></span>
                <h4><?= htmlspecialchars($inv['titulo']) ?></h4>
                <p><?= htmlspecialchars(mb_substr($inv['planteamiento_problema'], 0, 100)) ?>...</p>
                <div class="post-card-footer">
                    <span class="post-vacancies">● <?= (int)$inv['cupos_disponibles'] ?> Vacante(s)</span>
                    <?php if ($yaPostulado): ?>
                        <span style="font-size: 0.8rem; font-weight: bold; color: #f59e0b;"><i class="ph-bold ph-check"></i> Postulado</span>
                    <?php else: ?>
                        <button class="post-btn-apply" onclick="abrirModal(<?= $inv['id'] ?>, '<?= htmlspecialchars(addslashes($inv['titulo'])) ?>')">Aplicar</button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if(empty($agrupadas['t4'])): ?><div style="text-align:center; color:var(--texto-silenciado); padding:2rem 0; font-size:0.9rem;">No hay proyectos disponibles.</div><?php endif; ?>
        </div>

        <!-- COLUMNA MAESTRÍA -->
        <div class="post-kanban-col" data-nivel="maestria">
            <div class="post-col-header">
                <h3>Postgrado</h3>
                <span>Nivel Élite / Investigación Avanzada</span>
            </div>
            <?php foreach ($agrupadas['maestria'] as $inv): ?>
            <?php $yaPostulado = in_array($inv['id'], $postuladas_ids); ?>
            <div class="post-card">
                <span class="post-card-linea">Línea: <?= htmlspecialchars($inv['linea_nombre']) ?></span>
                <h4><?= htmlspecialchars($inv['titulo']) ?></h4>
                <p><?= htmlspecialchars(mb_substr($inv['planteamiento_problema'], 0, 100)) ?>...</p>
                <div class="post-card-footer">
                    <span class="post-vacancies">● <?= (int)$inv['cupos_disponibles'] ?> Vacante(s)</span>
                    <?php if ($yaPostulado): ?>
                        <span style="font-size: 0.8rem; font-weight: bold; color: #f59e0b;"><i class="ph-bold ph-check"></i> Postulado</span>
                    <?php else: ?>
                        <button class="post-btn-apply" onclick="abrirModal(<?= $inv['id'] ?>, '<?= htmlspecialchars(addslashes($inv['titulo'])) ?>')">Aplicar</button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if(empty($agrupadas['maestria'])): ?><div style="text-align:center; color:var(--texto-silenciado); padding:2rem 0; font-size:0.9rem;">No hay proyectos disponibles.</div><?php endif; ?>
        </div>

    </div>
</div>

<!-- MODAL DE APLICACIÓN -->
<div id="modal-aplicar" class="inv-modal-overlay" style="display:none;">
    <div class="inv-modal-content">
        <div class="inv-modal-header">
            <h2 id="modal-titulo">Postulación a Proyecto</h2>
            <span class="inv-modal-close" onclick="cerrarModal()">&times;</span>
        </div>
        <form action="?ruta=postulaciones-procesar" method="POST">
            <input type="hidden" name="id_investigacion" id="modal-id-inv">
            <p id="modal-nombre-proyecto" style="margin-bottom: 1.5rem; font-weight: bold; color: var(--color-secundario);"></p>
            <div class="inv-form-group">
                <label>Motivación Personal <span style="color:red">*</span></label>
                <textarea name="motivacion" class="inv-form-control" rows="4" required placeholder="Explica brevemente por qué te interesa este proyecto y qué puedes aportar..."></textarea>
            </div>
            <div class="inv-form-group">
                <label>Enlace a Portafolio/GitHub <span style="color: var(--texto-silenciado); font-size:0.8rem;">(Opcional)</span></label>
                <input type="url" name="portafolio" class="inv-form-control" placeholder="https://github.com/tu-usuario">
            </div>
            <button type="submit" class="inv-btn-primary" style="width: 100%; padding: 0.85rem; border-radius: 4px;">ENVIAR SOLICITUD DE INGRESO</button>
        </form>
    </div>
</div>

<script>
function abrirModal(id, titulo) {
    document.getElementById('modal-id-inv').value = id;
    document.getElementById('modal-nombre-proyecto').textContent = "Proyecto: " + titulo;
    document.getElementById('modal-aplicar').style.display = 'flex';
}
function cerrarModal() {
    document.getElementById('modal-aplicar').style.display = 'none';
}
document.getElementById('modal-aplicar').addEventListener('click', function(e) {
    if (e.target === this) { cerrarModal(); }
});
</script>