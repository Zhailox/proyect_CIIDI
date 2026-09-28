<?php
// modules/Investigaciones/views/postulantes_mis_proyectos.php
// Variables: $postulaciones (array)
require_once CORE_PATH . 'Security/Auth.php';
?>
<div class="inv-wrapper">

    <!-- FLASH MESSAGES -->
    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="inv-flash inv-flash-success"><i class="ph-fill ph-check-circle"></i> <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="inv-flash inv-flash-error"><i class="ph-fill ph-warning-circle"></i> <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
    <?php endif; ?>

    <!-- HEADER -->
    <div class="inv-header-gestion" style="margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 2rem; font-weight: 800; color: var(--inv-dark); margin: 0; display:flex; align-items:center; gap:0.6rem;">
                <div style="width:44px; height:44px; border-radius:10px; background: linear-gradient(135deg, var(--color-principal, #121a3e) 0%, var(--color-secundario, #505984) 100%); color:white; display:flex; align-items:center; justify-content:center; font-size:1.3rem; box-shadow:0 6px 15px rgba(80, 89, 132, 0.3);">
                    <i class="ph-fill ph-users-three"></i>
                </div>
                Postulantes a Mis Proyectos
            </h1>
            <p style="color: var(--inv-muted); margin-top:0.5rem; font-size:1rem; margin-left:52px;">Gestiona las solicitudes de participación recibidas en tus proyectos.</p>
        </div>
        <a href="?ruta=mis-investigaciones" class="inv-btn-secondary" style="text-decoration:none; display:inline-flex; align-items:center; gap:0.5rem;">
            <i class="ph-bold ph-arrow-left"></i> Volver a Mis Proyectos
        </a>
    </div>

    <!-- STATS RÁPIDAS -->
    <?php if (isset($stats)): ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div style="background: white; padding: 1.2rem 1.5rem; border-radius: 14px; border: 1px solid var(--inv-border); border-left: 4px solid #F59E0B; box-shadow: var(--inv-shadow-sm); display:flex; align-items:center; gap:1rem;">
            <div style="width:40px; height:40px; background:#FEF3C7; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                <i class="ph-fill ph-clock" style="color:#D97706; font-size:1.3rem;"></i>
            </div>
            <div>
                <div style="font-size:2rem; font-weight:800; color:var(--inv-dark); line-height:1;"><?= $stats['pendientes'] ?></div>
                <div style="font-size:0.78rem; color:var(--inv-muted); font-weight:600; text-transform:uppercase;">Pendientes</div>
            </div>
        </div>
        <div style="background: white; padding: 1.2rem 1.5rem; border-radius: 14px; border: 1px solid var(--inv-border); border-left: 4px solid #10B981; box-shadow: var(--inv-shadow-sm); display:flex; align-items:center; gap:1rem;">
            <div style="width:40px; height:40px; background:#DCFCE7; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                <i class="ph-fill ph-check-circle" style="color:#059669; font-size:1.3rem;"></i>
            </div>
            <div>
                <div style="font-size:2rem; font-weight:800; color:var(--inv-dark); line-height:1;"><?= $stats['aceptados'] ?></div>
                <div style="font-size:0.78rem; color:var(--inv-muted); font-weight:600; text-transform:uppercase;">Aceptados</div>
            </div>
        </div>
        <div style="background: white; padding: 1.2rem 1.5rem; border-radius: 14px; border: 1px solid var(--inv-border); border-left: 4px solid #EF4444; box-shadow: var(--inv-shadow-sm); display:flex; align-items:center; gap:1rem;">
            <div style="width:40px; height:40px; background:#FEE2E2; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                <i class="ph-fill ph-x-circle" style="color:#DC2626; font-size:1.3rem;"></i>
            </div>
            <div>
                <div style="font-size:2rem; font-weight:800; color:var(--inv-dark); line-height:1;"><?= $stats['rechazados'] ?></div>
                <div style="font-size:0.78rem; color:var(--inv-muted); font-weight:600; text-transform:uppercase;">Rechazados</div>
            </div>
        </div>
        <div style="background: white; padding: 1.2rem 1.5rem; border-radius: 14px; border: 1px solid var(--inv-border); border-left: 4px solid var(--inv-primary); box-shadow: var(--inv-shadow-sm); display:flex; align-items:center; gap:1rem;">
            <div style="width:40px; height:40px; background:var(--inv-primary-light); border-radius:10px; display:flex; align-items:center; justify-content:center;">
                <i class="ph-fill ph-users-three" style="color:var(--inv-primary); font-size:1.3rem;"></i>
            </div>
            <div>
                <div style="font-size:2rem; font-weight:800; color:var(--inv-dark); line-height:1;"><?= $stats['total'] ?></div>
                <div style="font-size:0.78rem; color:var(--inv-muted); font-weight:600; text-transform:uppercase;">Total</div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- TABLA DE POSTULANTES -->
    <div class="inv-table-container">
        <table class="inv-table">
            <thead>
                <tr>
                    <th><i class="ph-bold ph-user"></i> Estudiante</th>
                    <th><i class="ph-bold ph-flask"></i> Proyecto Solicitado</th>
                    <th><i class="ph-bold ph-chat-text"></i> Motivación</th>
                    <th>Estado</th>
                    <th style="text-align: right;"><i class="ph-bold ph-gear-six"></i> Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($postulaciones)): ?>
                <tr>
                    <td colspan="5" style="padding: 4rem; text-align: center; color: var(--inv-muted);">
                        <i class="ph-fill ph-users-three" style="font-size:3rem; color:var(--inv-primary-light); display:block; margin-bottom:1rem;"></i>
                        No hay postulaciones registradas en este momento.
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($postulaciones as $p): ?>
                    <tr>
                        <!-- Columna Estudiante -->
                        <td>
                            <div style="display:flex; align-items:center; gap:0.7rem;">
                                <div style="width:38px; height:38px; border-radius:50%; background: linear-gradient(135deg, var(--color-principal, #121a3e) 0%, var(--color-secundario, #505984) 100%); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <i class="ph-bold ph-user" style="color:white; font-size:1rem;"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: var(--inv-dark); font-size:0.95rem;"><?= htmlspecialchars($p['estudiante']) ?></div>
                                    <div style="font-size: 0.8rem; color: var(--inv-muted); margin-top:0.1rem;"><?= htmlspecialchars($p['email']) ?></div>
                                    <div style="font-size: 0.75rem; color: var(--inv-muted); margin-top:0.1rem;">
                                        <i class="ph-bold ph-calendar-blank"></i> <?= date('d/m/Y', strtotime($p['fecha_postulacion'])) ?>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Columna Proyecto -->
                        <td>
                            <span style="font-weight: 600; color: var(--inv-primary); font-size:0.9rem;">
                                <i class="ph-fill ph-flask" style="color:var(--inv-primary);"></i> <?= htmlspecialchars($p['investigacion_titulo']) ?>
                            </span>
                        </td>

                        <!-- Columna Motivación -->
                        <td style="max-width: 280px;">
                            <?php 
                                $texto = htmlspecialchars($p['mensaje_motivacion']);
                                $separador = "Enlace al Portafolio: ";
                                $partes = explode($separador, $texto);
                                $motivacion = trim($partes[0]);
                                $enlace = isset($partes[1]) ? trim($partes[1]) : '';
                            ?>
                            <div style="font-size: 0.875rem; color: var(--inv-dark); line-height: 1.5; margin: 0;">
                                <div style="display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;" title="<?= $motivacion ?>">
                                    <?= nl2br($motivacion) ?>
                                </div>
                                <?php if (!empty($enlace)): ?>
                                <div style="margin-top: 0.5rem; font-size:0.8rem;">
                                    <a href="<?= $enlace ?>" target="_blank" style="color:var(--inv-primary); font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:0.2rem; background:var(--inv-primary-light); padding:0.2rem 0.6rem; border-radius:50px;">
                                        <i class="ph-bold ph-link"></i> Ver Portafolio
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>

                        <!-- Columna Estado -->
                        <td>
                            <?php 
                            $clase_estado = 'inv-status-borrador';
                            if($p['estado'] === 'Aceptado') $clase_estado = 'inv-status-aprobado';
                            if($p['estado'] === 'Rechazado') $clase_estado = 'inv-status-rechazado';
                            ?>
                            <span class="inv-status-pill <?= $clase_estado ?>">
                                <?php if($p['estado'] === 'Aceptado'): ?><i class="ph-bold ph-check"></i><?php endif; ?>
                                <?php if($p['estado'] === 'Rechazado'): ?><i class="ph-bold ph-x"></i><?php endif; ?>
                                <?php if($p['estado'] === 'Pendiente'): ?><i class="ph-bold ph-clock"></i><?php endif; ?>
                                <?= htmlspecialchars($p['estado']) ?>
                            </span>
                        </td>

                        <!-- Columna Acciones -->
                        <td style="text-align: right;">
                            <?php if ($p['estado'] === 'Pendiente'): ?>
                                <div style="display:flex; gap:0.5rem; justify-content:flex-end;">
                                    <form action="?ruta=responder-postulacion" method="POST" style="display:inline;">
                                        <input type="hidden" name="id_postulacion" value="<?= $p['id'] ?>">
                                        <input type="hidden" name="estado" value="Aceptado">
                                        <button type="submit" class="inv-action-btn" style="width:auto; padding:0.4rem 0.9rem; border-radius:20px; background:#DCFCE7; color:#166534; gap:0.3rem; font-size:0.82rem; font-weight:700;" title="Aceptar postulación"
                                            onmouseover="this.style.background='#10B981'; this.style.color='white';" 
                                            onmouseout="this.style.background='#DCFCE7'; this.style.color='#166534';">
                                            <i class="ph-bold ph-check"></i> Aceptar
                                        </button>
                                    </form>
                                    <form action="?ruta=responder-postulacion" method="POST" style="display:inline;">
                                        <input type="hidden" name="id_postulacion" value="<?= $p['id'] ?>">
                                        <input type="hidden" name="estado" value="Rechazado">
                                        <button type="submit" class="inv-action-btn del" style="width:auto; padding:0.4rem 0.9rem; border-radius:20px; gap:0.3rem; font-size:0.82rem; font-weight:700;" title="Rechazar postulación">
                                            <i class="ph-bold ph-x"></i> Rechazar
                                        </button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <span style="font-size: 0.82rem; color: var(--inv-muted); font-style:italic;">
                                    Procesada<br>
                                    <strong style="font-style:normal;"><?= date('d/m/y H:i', strtotime($p['fecha_respuesta'])) ?></strong>
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINACIÓN -->
    <?php if (isset($paginacion) && $paginacion['paginas'] > 1): ?>
    <?php
    $pagActual = $paginacion['pagina'];
    $pagTotal  = $paginacion['paginas'];
    $basePaginacion = "?ruta=mis-postulantes&page=";
    ?>
    <div class="inv-pagination">
        <div class="inv-pag-controls">
            <?php if ($pagActual > 1): ?>
            <a href="<?= $basePaginacion . ($pagActual - 1) ?>" class="inv-pag-btn">
                <i class="ph-bold ph-caret-left"></i>
            </a>
            <?php endif; ?>

            <?php for ($i = max(1, $pagActual - 2); $i <= min($pagTotal, $pagActual + 2); $i++): ?>
            <a href="<?= $basePaginacion . $i ?>" class="inv-pag-btn <?= $i === $pagActual ? 'inv-pag-btn--active' : '' ?>">
                <?= $i ?>
            </a>
            <?php endfor; ?>

            <?php if ($pagActual < $pagTotal): ?>
            <a href="<?= $basePaginacion . ($pagActual + 1) ?>" class="inv-pag-btn">
                <i class="ph-bold ph-caret-right"></i>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

</div>
