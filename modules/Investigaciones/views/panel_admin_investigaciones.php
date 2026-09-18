<?php
// modules/Investigaciones/views/panel_admin_investigaciones.php
// Variables: $investigaciones (array), $paginacion (array), $postulaciones (array), $lineas (array), $porPaginaInv (int)
?>
<div class="inv-wrapper">

    <!-- FLASH MESSAGES -->
    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="inv-flash inv-flash-success"><i class="ph-fill ph-check-circle"></i> <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="inv-flash inv-flash-error"><i class="ph-fill ph-warning-circle"></i> <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
    <?php endif; ?>

    <div class="inv-header-gestion">
        <div>
            <h1 style="display:flex; align-items:center; gap:0.7rem;">
                <div style="width:44px; height:44px; border-radius:10px; background:linear-gradient(135deg,#0369A1,#0077BE); color:white; display:flex; align-items:center; justify-content:center; font-size:1.3rem; box-shadow:0 6px 15px rgba(3,105,161,0.3);">
                    <i class="ph-fill ph-shield-check"></i>
                </div>
                Panel Administrativo I+D
            </h1>
            <p style="color: var(--inv-muted); margin-top:0.5rem; font-size:1rem; margin-left:52px;">Control total sobre investigaciones y postulaciones de toda la institución.</p>
        </div>
    </div>

    <!-- ESTADÍSTICAS -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
        <div style="background: white; padding: 1.5rem; border-radius: 16px; border: 1px solid var(--inv-border); border-bottom: 4px solid var(--inv-primary); box-shadow: var(--inv-shadow-sm);">
            <div style="color: var(--inv-muted); font-size: 0.85rem; text-transform: uppercase; font-weight: 700; letter-spacing:0.5px;">Total Investigaciones</div>
            <div style="font-size: 2.5rem; font-weight: 800; color: var(--inv-dark); margin-top:0.5rem;"><?= $paginacion['total'] ?? count($investigaciones) ?></div>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 16px; border: 1px solid var(--inv-border); border-bottom: 4px solid var(--inv-success); box-shadow: var(--inv-shadow-sm);">
            <div style="color: var(--inv-muted); font-size: 0.85rem; text-transform: uppercase; font-weight: 700; letter-spacing:0.5px;">Proyectos Activos</div>
            <div style="font-size: 2.5rem; font-weight: 800; color: var(--inv-dark); margin-top:0.5rem;"><?= count(array_filter($investigaciones, fn($i) => $i['estado'] === 'Abierta' || $i['estado'] === 'En Desarrollo')) ?></div>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 16px; border: 1px solid var(--inv-border); border-bottom: 4px solid var(--inv-accent); box-shadow: var(--inv-shadow-sm);">
            <div style="color: var(--inv-muted); font-size: 0.85rem; text-transform: uppercase; font-weight: 700; letter-spacing:0.5px;">Total Postulaciones</div>
            <div style="font-size: 2.5rem; font-weight: 800; color: var(--inv-dark); margin-top:0.5rem;"><?= count($postulaciones) ?></div>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 16px; border: 1px solid var(--inv-border); border-bottom: 4px solid var(--inv-warning); box-shadow: var(--inv-shadow-sm);">
            <div style="color: var(--inv-muted); font-size: 0.85rem; text-transform: uppercase; font-weight: 700; letter-spacing:0.5px;">Pendientes</div>
            <div style="font-size: 2.5rem; font-weight: 800; color: var(--inv-dark); margin-top:0.5rem;"><?= count(array_filter($postulaciones, fn($p) => $p['estado'] === 'Pendiente')) ?></div>
        </div>
    </div>

    <!-- SECCIÓN TABLA INVESTIGACIONES -->
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom: 1.2rem;">
        <div style="display:flex; align-items:center; gap:0.8rem;">
            <div style="width:40px; height:40px; border-radius:10px; background:var(--inv-primary-light); color:var(--inv-primary); display:flex; align-items:center; justify-content:center; font-size:1.3rem;"><i class="ph-fill ph-folders"></i></div>
            <h2 style="color: var(--inv-dark); font-size: 1.5rem; margin: 0; font-weight:800;">Directorio de Investigaciones</h2>
        </div>

        <!-- Filtros admin -->
        <form action="index.php" method="GET" style="display:flex; gap:0.7rem; flex-wrap:wrap; align-items:center;">
            <input type="hidden" name="ruta" value="panel-investigaciones-admin">
            <div style="position:relative;">
                <i class="ph-bold ph-magnifying-glass" style="position:absolute; left:0.8rem; top:50%; transform:translateY(-50%); color:var(--inv-muted); font-size:0.9rem;"></i>
                <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Buscar proyecto..."
                    style="padding:0.6rem 0.8rem 0.6rem 2.2rem; border:1px solid var(--inv-border); border-radius:50px; font-size:0.88rem; outline:none; width:230px;"
                    onfocus="this.style.boxShadow='0 0 0 3px rgba(3,105,161,0.15)'; this.style.borderColor='#0369A1';"
                    onblur="this.style.boxShadow='none'; this.style.borderColor='var(--inv-border)';">
            </div>
            <select name="estado" style="padding:0.6rem 1rem; border:1px solid var(--inv-border); border-radius:50px; font-size:0.88rem; outline:none; background:white; font-weight:600; cursor:pointer;"
                    onchange="this.form.submit();">
                <option value="">Todos los estados</option>
                <option value="Abierta"       <?= ($_GET['estado'] ?? '') === 'Abierta'       ? 'selected' : '' ?>>Abierta</option>
                <option value="En Desarrollo" <?= ($_GET['estado'] ?? '') === 'En Desarrollo' ? 'selected' : '' ?>>En Desarrollo</option>
                <option value="Finalizada"    <?= ($_GET['estado'] ?? '') === 'Finalizada'    ? 'selected' : '' ?>>Finalizada</option>
                <option value="Cerrada"       <?= ($_GET['estado'] ?? '') === 'Cerrada'       ? 'selected' : '' ?>>Cerrada</option>
            </select>
            <button type="submit" style="background:linear-gradient(135deg,#0369A1,#0077BE); color:white; border:none; padding:0.6rem 1.2rem; border-radius:50px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:0.4rem; font-size:0.88rem;">
                <i class="ph-bold ph-funnel"></i> Filtrar
            </button>
            <?php if (!empty($_GET['q']) || !empty($_GET['estado'])): ?>
            <a href="?ruta=panel-investigaciones-admin" style="color:var(--inv-muted); font-size:0.85rem; text-decoration:none; display:inline-flex; align-items:center; gap:0.3rem; font-weight:600;">
                <i class="ph-bold ph-x-circle"></i> Limpiar
            </a>
            <?php endif; ?>
        </form>
    </div>

    <div class="inv-table-container" style="margin-bottom: 1.5rem;">
        <table class="inv-table">
            <thead>
                <tr>
                    <th>Proyecto</th>
                    <th>Responsable</th>
                    <th>Estado</th>
                    <th style="text-align: right;">Gestión</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($investigaciones as $inv): ?>
                <tr>
                    <td>
                        <span style="color: var(--inv-muted); font-size: 0.75rem; font-family: monospace; background:#F1F5F9; padding:0.2rem 0.5rem; border-radius:4px;">#<?= $inv['id'] ?></span><br>
                        <strong style="color: var(--inv-dark); font-size:1.05rem; display:block; margin-top:0.4rem;"><?= htmlspecialchars($inv['titulo']) ?></strong>
                        <div style="font-size: 0.85rem; color: var(--inv-muted); margin-top: 0.2rem;"><i class="ph-fill ph-bookmark-simple"></i> <?= htmlspecialchars($inv['linea_nombre']) ?></div>
                    </td>
                    <td>
                        <div style="display:flex; align-items:center; gap:0.5rem; font-weight:600; color:var(--inv-dark);">
                            <i class="ph-fill ph-chalkboard-teacher" style="color:var(--inv-primary);"></i> <?= htmlspecialchars($inv['profesor']) ?>
                        </div>
                    </td>
                    <td>
                        <?php
                        $clase_estado = 'inv-status-borrador';
                        if($inv['estado'] === 'Abierta') $clase_estado = 'inv-status-activo';
                        if($inv['estado'] === 'En Desarrollo') $clase_estado = 'inv-status-publicado';
                        if($inv['estado'] === 'Finalizada') $clase_estado = 'inv-status-archivado';
                        if($inv['estado'] === 'Cerrada') $clase_estado = 'inv-status-rechazado';
                        ?>
                        <span class="inv-status-pill <?= $clase_estado ?>"><?= htmlspecialchars($inv['estado']) ?></span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display:flex; justify-content:flex-end; align-items:center; gap: 1rem;">
                            <form action="?ruta=cambiar-estado-investigacion" method="POST" style="display:inline-flex; align-items:center; gap: 0.5rem; background:#F8FAFC; padding:0.3rem; border-radius:50px; border:1px solid var(--inv-border);">
                                <input type="hidden" name="id" value="<?= $inv['id'] ?>">
                                <select name="estado" style="padding: 0.4rem 1rem; border: none; border-radius: 50px; font-size: 0.85rem; background:transparent; font-weight:600; outline:none; cursor:pointer;">
                                    <option value="Abierta" <?= $inv['estado'] == 'Abierta'?'selected':'' ?>>Abierta</option>
                                    <option value="En Desarrollo" <?= $inv['estado'] == 'En Desarrollo'?'selected':'' ?>>En Desarrollo</option>
                                    <option value="Finalizada" <?= $inv['estado'] == 'Finalizada'?'selected':'' ?>>Finalizada</option>
                                    <option value="Cerrada" <?= $inv['estado'] == 'Cerrada'?'selected':'' ?>>Cerrada</option>
                                </select>
                                <button type="submit" class="inv-action-btn" title="Actualizar Estado"><i class="ph-bold ph-arrows-clockwise"></i></button>
                            </form>
                            <form action="?ruta=eliminar-investigacion" method="POST" style="display:inline;" id="form-delete-<?= $inv['id'] ?>">
                                <input type="hidden" name="id" value="<?= $inv['id'] ?>">
                                <input type="hidden" name="from_admin" value="1">
                                <button type="button" class="inv-action-btn del" title="Eliminar Proyecto" onclick="confirmarEliminacion(<?= $inv['id'] ?>)">
                                    <i class="ph-bold ph-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($investigaciones)): ?>
                <tr>
                    <td colspan="4" style="text-align:center; padding:3rem; color:var(--inv-muted);">No hay investigaciones que coincidan.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINACIÓN ADMIN -->
    <?php if (isset($paginacion) && $paginacion['paginas'] > 1): ?>
    <?php
        $qAdminBase = ['ruta' => 'panel-investigaciones-admin'];
        if (!empty($_GET['q']))      $qAdminBase['q']      = $_GET['q'];
        if (!empty($_GET['estado'])) $qAdminBase['estado'] = $_GET['estado'];
        $baseAdminPag = '?' . http_build_query($qAdminBase) . '&page=';
        $pA = $paginacion['pagina'];
        $pT = $paginacion['paginas'];
    ?>
    <div style="display:flex; flex-direction:column; align-items:center; gap:0.8rem; margin-bottom:3rem;">
        <p style="color:var(--inv-muted); font-size:0.88rem; margin:0;">
            Página <strong style="color:var(--inv-dark);"><?= $pA ?></strong> de <strong style="color:var(--inv-dark);"><?= $pT ?></strong>
            &nbsp;·&nbsp; <strong style="color:var(--inv-dark);"><?= $paginacion['total'] ?></strong> investigaciones en total
        </p>
        <div style="display:flex; gap:0.4rem; flex-wrap:wrap; justify-content:center;">
            <?php if ($pA > 1): ?>
            <a href="<?= $baseAdminPag . ($pA - 1) ?>" style="width:38px; height:38px; border-radius:9px; display:flex; align-items:center; justify-content:center; background:white; border:1px solid var(--inv-border); color:var(--inv-dark); text-decoration:none; font-weight:700; transition:all 0.2s;"
               onmouseover="this.style.background='#0369A1'; this.style.color='white';" onmouseout="this.style.background='white'; this.style.color='var(--inv-dark)';">
                <i class="ph-bold ph-caret-left"></i>
            </a>
            <?php endif; ?>
            <?php
            $r = 2; $si = max(1, $pA - $r); $fi = min($pT, $pA + $r);
            if ($si > 1): ?><a href="<?= $baseAdminPag . '1' ?>" style="width:38px; height:38px; border-radius:9px; display:flex; align-items:center; justify-content:center; background:white; border:1px solid var(--inv-border); color:var(--inv-dark); text-decoration:none; font-weight:700;">1</a><?php if ($si > 2): ?><span style="display:flex; align-items:flex-end; padding-bottom:5px; color:var(--inv-muted);">…</span><?php endif; ?>
            <?php endif; ?>
            <?php for ($i = $si; $i <= $fi; $i++): ?>
            <a href="<?= $baseAdminPag . $i ?>"
               style="width:38px; height:38px; border-radius:9px; display:flex; align-items:center; justify-content:center; text-decoration:none; font-weight:700; transition:all 0.2s;
                      <?= $i === $pA ? 'background:linear-gradient(135deg,#0369A1,#0077BE); color:white; border:none; box-shadow:0 4px 10px rgba(3,105,161,0.3);' : 'background:white; border:1px solid var(--inv-border); color:var(--inv-dark);' ?>"
               <?php if ($i !== $pA): ?>onmouseover="this.style.background='#0369A1'; this.style.color='white';" onmouseout="this.style.background='white'; this.style.color='var(--inv-dark)';"<?php endif ?>>
                <?= $i ?>
            </a>
            <?php endfor; ?>
            <?php if ($fi < $pT): ?><?php if ($fi < $pT - 1): ?><span style="display:flex; align-items:flex-end; padding-bottom:5px; color:var(--inv-muted);">…</span><?php endif; ?><a href="<?= $baseAdminPag . $pT ?>" style="width:38px; height:38px; border-radius:9px; display:flex; align-items:center; justify-content:center; background:white; border:1px solid var(--inv-border); color:var(--inv-dark); text-decoration:none; font-weight:700;"><?= $pT ?></a><?php endif; ?>
            <?php if ($pA < $pT): ?>
            <a href="<?= $baseAdminPag . ($pA + 1) ?>" style="width:38px; height:38px; border-radius:9px; display:flex; align-items:center; justify-content:center; background:white; border:1px solid var(--inv-border); color:var(--inv-dark); text-decoration:none; font-weight:700; transition:all 0.2s;"
               onmouseover="this.style.background='#0369A1'; this.style.color='white';" onmouseout="this.style.background='white'; this.style.color='var(--inv-dark)';">
                <i class="ph-bold ph-caret-right"></i>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ══════════════════════════════════════════════════════════════
         CONFIGURACIÓN DE PAGINACIÓN
         ══════════════════════════════════════════════════════════════ -->
    <div style="background:white; border:1px solid var(--inv-border); border-radius:16px; padding:2rem; margin-bottom:3rem; box-shadow:var(--inv-shadow-sm);">
        <h2 style="color:var(--inv-dark); font-size:1.2rem; font-weight:800; margin-bottom:1.5rem; padding-bottom:1rem; border-bottom:2px solid #F1F5F9; display:flex; align-items:center; gap:0.6rem;">
            <span style="display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; border-radius:8px; background:#FEF3C7;">
                <i class="ph-fill ph-sliders-horizontal" style="color:#D97706; font-size:1.1rem;"></i>
            </span>
            Configuración de Paginación
        </h2>
        <form action="?ruta=panel-investigaciones-admin" method="POST" style="display:flex; flex-wrap:wrap; gap:2rem; align-items:flex-end;">
            <input type="hidden" name="config_paginacion" value="1">
            <div>
                <label style="display:block; font-weight:700; color:var(--inv-dark); margin-bottom:0.6rem; font-size:0.9rem;">
                    <i class="ph-bold ph-stack" style="color:#0369A1;"></i>
                    Investigaciones por página (Panel Admin)
                </label>
                <div style="display:flex; align-items:center; gap:1rem;">
                    <input type="range" name="por_pagina_inv" id="rangeInv"
                        min="3" max="50" step="1" value="<?= htmlspecialchars((int)($porPaginaInv ?? 12)) ?>"
                        style="width:200px; accent-color:#0369A1; cursor:pointer;"
                        oninput="document.getElementById('valInv').textContent = this.value;">
                    <span id="valInv" style="font-size:1.4rem; font-weight:800; color:#0369A1; min-width:30px; text-align:center;"><?= (int)($porPaginaInv ?? 12) ?></span>
                    <span style="color:var(--inv-muted); font-size:0.88rem;">proyectos / página</span>
                </div>
                <p style="color:var(--inv-muted); font-size:0.8rem; margin-top:0.5rem; margin-bottom:0;">
                    Actualmente se muestran <strong style="color:var(--inv-dark);"><?= (int)($porPaginaInv ?? 12) ?></strong> proyectos por página en este panel.
                    <br>La cartelera pública siempre muestra <strong style="color:var(--inv-dark);">9</strong> proyectos por página (grilla 3×3).
                </p>
            </div>
            <button type="submit" style="background:linear-gradient(135deg,#0369A1,#0077BE); color:white; border:none; padding:0.8rem 1.8rem; border-radius:50px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:0.5rem; box-shadow:0 4px 12px rgba(3,105,161,0.3); transition:all 0.2s; white-space:nowrap;"
                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(3,105,161,0.4)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(3,105,161,0.3)';">
                <i class="ph-bold ph-floppy-disk"></i> Guardar Configuración
            </button>
        </form>
    </div>

    <!-- TABLA GLOBAL DE POSTULACIONES -->
    <div style="display:flex; align-items:center; gap:0.8rem; margin-bottom: 1.5rem;">
        <div style="width:40px; height:40px; border-radius:10px; background:#EDE9FE; color:var(--inv-accent); display:flex; align-items:center; justify-content:center; font-size:1.3rem;"><i class="ph-fill ph-users-three"></i></div>
        <h2 style="color: var(--inv-dark); font-size: 1.5rem; margin: 0; font-weight:800;">Supervisión de Postulaciones</h2>
    </div>

    <div class="inv-table-container">
        <table class="inv-table">
            <thead>
                <tr>
                    <th>Estudiante</th>
                    <th>Proyecto Solicitado</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th style="text-align: right;">Resolución</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($postulaciones as $p): ?>
                <tr>
                    <td>
                        <div style="font-weight: 700; color: var(--inv-dark); display:flex; align-items:center; gap:0.5rem;"><i class="ph-fill ph-user-circle" style="font-size:1.5rem; color:var(--inv-muted);"></i> <?= htmlspecialchars($p['estudiante']) ?></div>
                    </td>
                    <td>
                        <strong style="color: var(--inv-dark); font-size: 0.95rem; display:block;"><?= htmlspecialchars($p['investigacion_titulo']) ?></strong>
                        <div style="font-size: 0.85rem; color: var(--inv-muted); margin-top:0.2rem;"><i class="ph-fill ph-chalkboard-teacher"></i> Prof. <?= htmlspecialchars($p['profesor']) ?></div>
                    </td>
                    <td>
                        <span style="background:#F1F5F9; padding:0.3rem 0.6rem; border-radius:6px; font-size:0.85rem; font-weight:600; color:var(--inv-muted);"><i class="ph-bold ph-calendar-blank"></i> <?= date('d/m/Y', strtotime($p['fecha_postulacion'])) ?></span>
                    </td>
                    <td>
                        <?php
                        $clase_estado = 'inv-status-borrador';
                        if($p['estado'] === 'Aceptado') $clase_estado = 'inv-status-aprobado';
                        if($p['estado'] === 'Rechazado') $clase_estado = 'inv-status-rechazado';
                        ?>
                        <span class="inv-status-pill <?= $clase_estado ?>"><?= htmlspecialchars($p['estado']) ?></span>
                    </td>
                    <td style="text-align: right;">
                        <form action="?ruta=responder-postulacion" method="POST" style="display:inline-flex; align-items:center; justify-content:flex-end; gap: 0.5rem; background:#F8FAFC; padding:0.3rem; border-radius:50px; border:1px solid var(--inv-border);">
                            <input type="hidden" name="id_postulacion" value="<?= $p['id'] ?>">
                            <input type="hidden" name="from_admin" value="1">
                            <select name="estado" style="padding: 0.4rem 1rem; border: none; border-radius: 50px; font-size: 0.85rem; background:transparent; font-weight:600; outline:none; cursor:pointer;">
                                <option value="Pendiente" <?= $p['estado'] == 'Pendiente'?'selected':'' ?>>Pendiente</option>
                                <option value="Aceptado" <?= $p['estado'] == 'Aceptado'?'selected':'' ?>>Aprobar</option>
                                <option value="Rechazado" <?= $p['estado'] == 'Rechazado'?'selected':'' ?>>Rechazar</option>
                            </select>
                            <button type="submit" class="inv-action-btn" style="background:var(--inv-dark); color:white;" title="Aplicar Resolución"><i class="ph-bold ph-check"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($postulaciones)): ?>
                <tr>
                    <td colspan="5" style="text-align:center; padding:3rem; color:var(--inv-muted);">No hay postulaciones registradas en el sistema.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
function mostrarModalSistema(tipo, titulo, mensaje, isConfirm = false, onConfirm = null) {
    const overlay = document.createElement('div');
    overlay.style.cssText = 'position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,34,68,0.8); z-index:99999; display:flex; align-items:center; justify-content:center; backdrop-filter:blur(4px);';
    
    let icon = tipo === 'success' ? '<i class="ph-bold ph-check-circle" style="color: #16a34a;"></i>' : '<i class="ph-bold ph-warning-circle" style="color: #dc2626;"></i>';
    let btnHtml = isConfirm 
        ? `<button type="button" class="btn btn-secondary" onclick="this.closest('div').parentElement.parentElement.remove()" style="margin-right:0.5rem; background:white; color:var(--inv-dark); border:1px solid var(--inv-border); padding:0.8rem 1.5rem; border-radius:50px; font-weight:600; cursor:pointer;">Cancelar</button>
           <button type="button" class="btn btn-primary" id="btn-confirm-modal" style="background:var(--color-danger, #dc2626); color:white; border:none; padding:0.8rem 1.5rem; border-radius:50px; font-weight:600; cursor:pointer; box-shadow:0 4px 10px rgba(220, 38, 38, 0.3);">Sí, eliminar</button>`
        : `<button type="button" class="btn btn-primary w-100 justify-center" onclick="this.closest('div').parentElement.parentElement.remove()" style="background:var(--color-secundario, #0b1a30); color:white; border:none; padding:0.8rem 1.5rem; border-radius:50px; font-weight:700; cursor:pointer; width:100%;">Entendido</button>`;

    if (isConfirm) {
        icon = '<i class="ph-bold ph-trash" style="color: #dc2626;"></i>';
    }

    overlay.innerHTML = `
        <div style="background: white; padding: 2.5rem 2rem; border-radius: 16px; max-width: 400px; width: 90%; text-align: center; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <div style="font-size: 3.5rem; margin-bottom: 1rem;">${icon}</div>
            <h3 style="margin: 0 0 0.5rem 0; color: #0f172a; font-size:1.4rem; font-weight:800;">${titulo}</h3>
            <p style="color: #475569; font-size: 0.95rem; margin-bottom: 2rem; line-height:1.5;">${mensaje}</p>
            <div style="display:flex; justify-content:center;">${btnHtml}</div>
        </div>
    `;
    
    document.body.appendChild(overlay);

    if (isConfirm && onConfirm) {
        document.getElementById('btn-confirm-modal').addEventListener('click', () => {
            overlay.remove();
            onConfirm();
        });
    }
}

function confirmarEliminacion(idInvestigacion) {
    mostrarModalSistema(
        'warning', 
        'Eliminar Proyecto', 
        '¿Está seguro de eliminar esta investigación? Se borrará el proyecto y todas sus postulaciones permanentemente.', 
        true, 
        () => document.getElementById('form-delete-' + idInvestigacion).submit()
    );
}
</script>
<script>
<?php if (isset($_SESSION['flash_success'])): ?>
    document.addEventListener("DOMContentLoaded", function() {
        mostrarModalSistema('success', 'Operación Exitosa', '<?= htmlspecialchars($_SESSION['flash_success']) ?>');
    });
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['flash_error'])): ?>
    document.addEventListener("DOMContentLoaded", function() {
        mostrarModalSistema('error', 'Ocurrió un Problema', '<?= htmlspecialchars($_SESSION['flash_error']) ?>');
    });
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>
</script>