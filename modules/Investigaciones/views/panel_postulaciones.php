<?php
// modules/Investigaciones/views/panel_postulaciones.php
// Variables: $agrupadas, $lineas, $filtro_linea, $misPostulaciones, $postuladas_ids
require_once CORE_PATH . 'Security/Auth.php';
$is_logged = Auth::check();
?>
<div class="inv-wrapper">

    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="inv-flash inv-flash-success"><i class="ph-fill ph-check-circle"></i> <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="inv-flash inv-flash-error"><i class="ph-fill ph-warning-circle"></i> <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
    <?php endif; ?>

    <header style="background: linear-gradient(135deg, #1E293B 0%, #334155 100%); border-radius: var(--inv-radius-xl); padding: 4rem 3rem; color: white; margin-bottom: 2rem; box-shadow: 0 20px 40px rgba(30,41,59,0.2); position:relative; overflow:hidden;">
        <div style="position:relative; z-index:2; max-width:800px;">
            <div style="background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); display:inline-block; padding:0.4rem 1rem; border-radius:50px; font-size:0.85rem; font-weight:700; margin-bottom:1.5rem; letter-spacing:0.5px;"><i class="ph-fill ph-rocket"></i> POSTULACIONES I+D</div>
            <h1 style="font-size:3rem; font-weight:800; margin-bottom:1rem; line-height:1.2;">¿Pueden tus ideas <span style="color:#38BDF8;">cambiar al mundo?</span></h1>
            <p style="font-size:1.15rem; color:#E2E8F0; line-height:1.6;">Únete a los proyectos de investigación científica e innovación de nuestra institución. Colabora directamente con expertos y desarrolla soluciones de alto impacto regional.</p>
        </div>
        <i class="ph-fill ph-lightbulb" style="position:absolute; right:-5%; top:-10%; font-size:25rem; color:rgba(255,255,255,0.05); transform:rotate(15deg);"></i>
    </header>

    <?php if ($is_logged && !empty($misPostulaciones)): ?>
    <!-- SECCIÓN DE MIS POSTULACIONES -->
    <div style="background: rgba(255,255,255,0.95); border: 1px solid rgba(80,89,132,0.15); border-radius: 16px; padding: 2rem; margin-bottom: 2.5rem; box-shadow: var(--inv-shadow-sm);">
        <h2 style="color: var(--inv-dark); font-size: 1.25rem; font-weight:800; margin-bottom: 1.5rem; display:flex; align-items:center; gap:0.5rem;"><i class="ph-fill ph-clock-counter-clockwise" style="color:var(--inv-primary);"></i> Mis Postulaciones Enviadas</h2>
        <div style="display: flex; gap: 1rem; overflow-x: auto; padding-bottom: 0.5rem;">
            <?php foreach($misPostulaciones as $mp): ?>
            <div style="background: #F8FAFC; border: 1px solid var(--inv-border); border-radius: 12px; padding: 1.2rem; min-width: 300px; flex: 0 0 auto;">
                <div style="font-weight: 700; color: var(--inv-dark); margin-bottom: 0.8rem; font-size: 0.95rem; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;" title="<?= htmlspecialchars($mp['investigacion_titulo']) ?>">
                    <?= htmlspecialchars($mp['investigacion_titulo']) ?>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.8rem; color: var(--inv-muted); font-weight:600;"><i class="ph-bold ph-calendar-blank"></i> <?= date('d/m/Y', strtotime($mp['fecha_postulacion'])) ?></span>
                    <?php 
                    $clase_estado = 'inv-status-borrador';
                    if($mp['estado'] === 'Aceptado') $clase_estado = 'inv-status-aprobado';
                    if($mp['estado'] === 'Rechazado') $clase_estado = 'inv-status-rechazado';
                    ?>
                    <span class="inv-status-pill <?= $clase_estado ?>">
                        <?= htmlspecialchars($mp['estado']) ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- FILTROS -->
    <div style="display:flex; flex-wrap:wrap; gap:0.5rem; margin-bottom: 2rem;">
        <a href="?ruta=postulaciones-investigacion" style="padding:0.6rem 1.2rem; border-radius:50px; font-weight:600; text-decoration:none; font-size:0.9rem; transition:all 0.2s; <?= empty($filtro_linea) ? 'background:var(--inv-primary); color:white; box-shadow:0 4px 10px rgba(14,165,233,0.3);' : 'background:white; color:var(--inv-muted); border:1px solid var(--inv-border);' ?>">
            Todas las Líneas
        </a>
        <?php foreach ($lineas as $l): ?>
        <a href="?ruta=postulaciones-investigacion&linea=<?= $l['id'] ?>" style="padding:0.6rem 1.2rem; border-radius:50px; font-weight:600; text-decoration:none; font-size:0.9rem; transition:all 0.2s; <?= ($filtro_linea == $l['id']) ? 'background:var(--inv-primary); color:white; box-shadow:0 4px 10px rgba(14,165,233,0.3);' : 'background:white; color:var(--inv-muted); border:1px solid var(--inv-border);' ?>">
            <?= htmlspecialchars($l['nombre']) ?>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- KANBAN BOARD -->
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;">
        
        <?php 
        $niveles = [
            't1' => ['Trayecto I', 'Lógica y Algoritmos Básicos', 'ph-code'],
            't2' => ['Trayecto II', 'Sistemas de Información', 'ph-database'],
            't3' => ['Trayecto III', 'Sistemas de Media-Alta Escala', 'ph-desktop'],
            't4' => ['Trayecto IV', 'Arquitecturas Avanzadas', 'ph-cpu'],
            'maestria' => ['Postgrado', 'Investigación Avanzada', 'ph-student']
        ];
        foreach($niveles as $key => $info):
            $icono = $info[2];
        ?>
        <div style="background: rgba(248, 250, 252, 0.8); border: 1px solid var(--inv-border); border-radius: 20px; padding: 1.5rem; display:flex; flex-direction:column; gap:1rem;">
            <div style="margin-bottom:0.5rem; display:flex; align-items:center; gap:0.8rem; padding-bottom:1rem; border-bottom:2px solid var(--inv-border);">
                <div style="width:40px; height:40px; background:var(--inv-primary-light); color:var(--inv-primary); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.3rem;">
                    <i class="ph-fill <?= $icono ?>"></i>
                </div>
                <div>
                    <h3 style="font-size:1.2rem; font-weight:800; color:var(--inv-dark); margin:0;"><?= $info[0] ?></h3>
                    <span style="font-size:0.8rem; color:var(--inv-muted); font-weight:600;"><?= $info[1] ?></span>
                </div>
            </div>
            
            <?php foreach ($agrupadas[$key] as $inv): ?>
            <?php $yaPostulado = in_array($inv['id'], $postuladas_ids); ?>
            <div style="background: white; border: 1px solid rgba(80,89,132,0.15); border-radius: 12px; padding: 1.2rem; box-shadow: 0 4px 6px rgba(15,23,42,0.02); transition:transform 0.2s; cursor:pointer;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                <div style="font-size:0.75rem; font-weight:700; color:var(--inv-primary); margin-bottom:0.5rem; text-transform:uppercase; display:flex; align-items:center; gap:0.3rem;"><i class="ph-bold ph-bookmark-simple"></i> <?= htmlspecialchars($inv['linea_nombre']) ?></div>
                <h4 style="font-size:1rem; font-weight:700; color:var(--inv-dark); margin-bottom:0.5rem; line-height:1.4;"><?= htmlspecialchars($inv['titulo']) ?></h4>
                <p style="font-size:0.85rem; color:var(--inv-muted); line-height:1.5; margin-bottom:1.2rem; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;"><?= htmlspecialchars(mb_substr($inv['planteamiento_problema'], 0, 120)) ?>...</p>
                <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--inv-border); padding-top:1rem;">
                    <span style="font-size:0.8rem; font-weight:700; color:var(--inv-muted);"><i class="ph-fill ph-users-three"></i> <?= (int)$inv['cupos_disponibles'] ?> Vacantes</span>
                    <?php if ($yaPostulado): ?>
                        <span style="font-size:0.8rem; font-weight:700; color:#F59E0B; background:#FEF3C7; padding:0.3rem 0.6rem; border-radius:6px; display:flex; align-items:center; gap:0.3rem;"><i class="ph-bold ph-check"></i> Postulado</span>
                    <?php else: ?>
                        <button onclick="abrirModal(<?= $inv['id'] ?>, '<?= htmlspecialchars(addslashes($inv['titulo'])) ?>')" class="inv-btn-primary" style="padding:0.4rem 1rem; font-size:0.85rem;">Aplicar</button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if(empty($agrupadas[$key])): ?>
                <div style="text-align:center; padding:3rem 1rem; color:var(--inv-muted); border:2px dashed var(--inv-border); border-radius:12px;">
                    <i class="ph-fill ph-folder-dashed" style="font-size:2rem; margin-bottom:0.5rem; color:var(--inv-border);"></i><br>
                    <span style="font-size:0.9rem; font-weight:600;">No hay proyectos en esta categoría.</span>
                </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

    </div>
</div>

<!-- MODAL DE APLICACIÓN -->
<div id="modal-aplicar" class="inv-modal-overlay" style="display:none; opacity:0; transition:opacity 0.3s;">
    <div class="inv-modal" style="transform:translateY(20px); transition:transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; padding-bottom:1rem; border-bottom:1px solid var(--inv-border);">
            <h2 style="font-size:1.3rem; font-weight:800; color:var(--inv-dark); margin:0;">Aplicar a Proyecto</h2>
            <button onclick="cerrarModal()" style="background:none; border:none; font-size:1.5rem; color:var(--inv-muted); cursor:pointer; padding:0.2rem;">&times;</button>
        </div>
        
        <?php if (!$is_logged): ?>
            <div style="padding:2rem 0; text-align:center;">
                <i class="ph-fill ph-lock-key" style="font-size:3rem; color:var(--inv-warning); margin-bottom:1rem;"></i>
                <p style="font-size:1.05rem; color:var(--inv-dark); font-weight:600; margin-bottom:0.5rem;">Debes iniciar sesión</p>
                <p style="font-size:0.9rem; color:var(--inv-muted); margin-bottom:1.5rem;">Para postularte a un proyecto de investigación necesitas acceder a tu cuenta.</p>
                <a href="?ruta=login" class="inv-btn-primary" style="width:100%; justify-content:center;">Iniciar Sesión</a>
            </div>
        <?php else: ?>
            <p style="font-size:0.95rem; color:var(--inv-muted); margin-bottom:1.5rem; text-align:left;">Estás a punto de postularte como investigador/tesista para:</p>
            <strong id="modal-titulo" style="display:block; font-size:1.1rem; color:var(--inv-primary); font-weight:700; text-align:left; margin-bottom:2rem; padding:1rem; background:var(--inv-primary-light); border-radius:10px;"></strong>
            
            <form action="?ruta=procesar-aplicacion" method="POST" id="form-aplicar">
                <input type="hidden" name="id_investigacion" id="modal-id">
                <button type="submit" class="inv-btn-primary" style="width:100%; justify-content:center; font-size:1.1rem; padding:1rem;">Confirmar Postulación</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<script>
function abrirModal(id, titulo) {
    const overlay = document.getElementById('modal-aplicar');
    const modal = overlay.querySelector('.inv-modal');
    <?php if ($is_logged): ?>
        document.getElementById('modal-titulo').textContent = titulo;
        document.getElementById('modal-id').value = id;
    <?php endif; ?>
    overlay.style.display = 'flex';
    // Trigger reflow
    void overlay.offsetWidth;
    overlay.style.opacity = '1';
    modal.style.transform = 'translateY(0)';
}
function cerrarModal() {
    const overlay = document.getElementById('modal-aplicar');
    const modal = overlay.querySelector('.inv-modal');
    overlay.style.opacity = '0';
    modal.style.transform = 'translateY(20px)';
    setTimeout(() => {
        overlay.style.display = 'none';
    }, 300);
}
// Close on outside click
document.getElementById('modal-aplicar').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
</script>