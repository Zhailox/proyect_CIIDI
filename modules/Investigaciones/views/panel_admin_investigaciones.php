<?php
// modules/Investigaciones/views/panel_admin_investigaciones.php
// Variables: $investigaciones (array), $paginacion (array), $postulaciones (array), $lineas (array), $porPaginaInv (int), $responsablesDisponibles (array), $esSuperAdmin (bool)
$responsablesDisponibles = $responsablesDisponibles ?? [];
$esSuperAdmin = $esSuperAdmin ?? false;
$trayectoLabels = [
    't1' => 'Trayecto 1',
    't2' => 'Trayecto 2',
    't3' => 'Trayecto 3',
    't4' => 'Trayecto 4',
    'maestria' => 'Postgrado / Maestría',
];
?>
<div class="inv-wrapper">

    <!-- FLASH MESSAGES -->
    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="inv-flash inv-flash-success"><i class="ph-fill ph-check-circle"></i> <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="inv-flash inv-flash-error"><i class="ph-fill ph-warning-circle"></i> <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
    <?php endif; ?>

    <div class="inv-header-gestion" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
        <div>
            <div style="display:flex; align-items:center; gap:0.6rem; flex-wrap:wrap;">
                <h1 style="display:flex; align-items:center; gap:0.7rem; margin:0;">
                    <div style="width:44px; height:44px; border-radius:10px; background:linear-gradient(135deg,#0369A1,#0077BE); color:white; display:flex; align-items:center; justify-content:center; font-size:1.3rem; box-shadow:0 6px 15px rgba(3,105,161,0.3);">
                        <i class="ph-fill ph-shield-check"></i>
                    </div>
                    Panel Administrativo I+D
                </h1>
                <?php if ($esSuperAdmin): ?>
                <span style="background:linear-gradient(135deg,#0f172a,#1e293b); color:#38bdf8; font-size:0.75rem; font-weight:800; padding:0.35rem 0.85rem; border-radius:50px; letter-spacing:0.6px; text-transform:uppercase; display:inline-flex; align-items:center; gap:0.35rem; border:1px solid rgba(56,189,248,0.3);">
                    <i class="ph-fill ph-crown-simple"></i> Control Total SuperAdmin
                </span>
                <?php endif; ?>
            </div>
            <p style="color: var(--inv-muted); margin-top:0.5rem; font-size:0.98rem; margin-left:52px; margin-bottom:0;">
                Supervisión, edición integral, reasignación de responsables, control de estado y eliminación de todas las investigaciones registradas en el sistema.
            </p>
        </div>
        <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
            <a href="?ruta=investigaciones" style="background:white; color:var(--inv-dark); border:1px solid var(--inv-border); padding:0.75rem 1.3rem; border-radius:50px; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:0.45rem; font-size:0.9rem; box-shadow:var(--inv-shadow-sm);">
                <i class="ph-bold ph-Compass"></i> Ver Cartelera Pública
            </a>
            <a href="?ruta=crear-investigacion&from_admin=1" class="inv-btn-create" style="text-decoration:none;">
                <i class="ph-bold ph-plus"></i> Nueva Investigación
            </a>
        </div>
    </div>

    <!-- ESTADÍSTICAS -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <div style="background: white; padding: 1.5rem; border-radius: 16px; border: 1px solid var(--inv-border); border-bottom: 4px solid var(--inv-primary); box-shadow: var(--inv-shadow-sm);">
            <div style="color: var(--inv-muted); font-size: 0.85rem; text-transform: uppercase; font-weight: 700; letter-spacing:0.5px;">Total Investigaciones</div>
            <div style="font-size: 2.4rem; font-weight: 800; color: var(--inv-dark); margin-top:0.5rem;"><?= $paginacion['total'] ?? count($investigaciones) ?></div>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 16px; border: 1px solid var(--inv-border); border-bottom: 4px solid var(--inv-success); box-shadow: var(--inv-shadow-sm);">
            <div style="color: var(--inv-muted); font-size: 0.85rem; text-transform: uppercase; font-weight: 700; letter-spacing:0.5px;">Proyectos Activos (Página)</div>
            <div style="font-size: 2.4rem; font-weight: 800; color: var(--inv-dark); margin-top:0.5rem;"><?= count(array_filter($investigaciones, fn($i) => $i['estado'] === 'Abierta' || $i['estado'] === 'En Desarrollo')) ?></div>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 16px; border: 1px solid var(--inv-border); border-bottom: 4px solid var(--inv-accent); box-shadow: var(--inv-shadow-sm);">
            <div style="color: var(--inv-muted); font-size: 0.85rem; text-transform: uppercase; font-weight: 700; letter-spacing:0.5px;">Total Postulaciones</div>
            <div style="font-size: 2.4rem; font-weight: 800; color: var(--inv-dark); margin-top:0.5rem;"><?= count($postulaciones) ?></div>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 16px; border: 1px solid var(--inv-border); border-bottom: 4px solid var(--inv-warning); box-shadow: var(--inv-shadow-sm);">
            <div style="color: var(--inv-muted); font-size: 0.85rem; text-transform: uppercase; font-weight: 700; letter-spacing:0.5px;">Postulaciones Pendientes</div>
            <div style="font-size: 2.4rem; font-weight: 800; color: var(--inv-dark); margin-top:0.5rem;"><?= count(array_filter($postulaciones, fn($p) => $p['estado'] === 'Pendiente')) ?></div>
        </div>
    </div>

    <!-- SECCIÓN TABLA INVESTIGACIONES -->
    <div style="background:white; border:1px solid var(--inv-border); border-radius:16px; padding:1.25rem 1.5rem; margin-bottom:1.25rem; box-shadow:var(--inv-shadow-sm);">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
            <div style="display:flex; align-items:center; gap:0.8rem;">
                <div style="width:40px; height:40px; border-radius:10px; background:var(--inv-primary-light); color:var(--inv-primary); display:flex; align-items:center; justify-content:center; font-size:1.3rem;"><i class="ph-fill ph-folders"></i></div>
                <div>
                    <h2 style="color: var(--inv-dark); font-size: 1.35rem; margin: 0; font-weight:800;">Control General de Investigaciones</h2>
                    <span style="font-size:0.82rem; color:var(--inv-muted);">Audita, modifica, cambia estado, reasigna docente o elimina cualquier investigación subida al sistema.</span>
                </div>
            </div>

            <!-- Filtros admin -->
            <form action="index.php" method="GET" style="display:flex; gap:0.65rem; flex-wrap:wrap; align-items:center;">
                <input type="hidden" name="ruta" value="panel-investigaciones-admin">
                <div style="position:relative;">
                    <i class="ph-bold ph-magnifying-glass" style="position:absolute; left:0.8rem; top:50%; transform:translateY(-50%); color:var(--inv-muted); font-size:0.9rem;"></i>
                    <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Título, problema o docente..."
                        style="padding:0.55rem 0.8rem 0.55rem 2.2rem; border:1px solid var(--inv-border); border-radius:50px; font-size:0.85rem; outline:none; width:210px;"
                        onfocus="this.style.boxShadow='0 0 0 3px rgba(3,105,161,0.15)'; this.style.borderColor='#0369A1';"
                        onblur="this.style.boxShadow='none'; this.style.borderColor='var(--inv-border)';">
                </div>

                <select name="linea" style="padding:0.55rem 0.9rem; border:1px solid var(--inv-border); border-radius:50px; font-size:0.85rem; outline:none; background:white; font-weight:600; cursor:pointer; max-width:210px;"
                        onchange="this.form.submit();">
                    <option value="">Todas las líneas</option>
                    <?php foreach ($lineas as $l): ?>
                        <option value="<?= (int)$l['id'] ?>" <?= ((int)($_GET['linea'] ?? 0) === (int)$l['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($l['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="profesor" style="padding:0.55rem 0.9rem; border:1px solid var(--inv-border); border-radius:50px; font-size:0.85rem; outline:none; background:white; font-weight:600; cursor:pointer; max-width:210px;"
                        onchange="this.form.submit();">
                    <option value="">Todos los responsables</option>
                    <?php foreach ($responsablesDisponibles as $resp): ?>
                        <option value="<?= (int)$resp['id'] ?>" <?= ((int)($_GET['profesor'] ?? 0) === (int)$resp['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($resp['nombre_completo'] ?? $resp['nombre'] ?? '') ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="estado" style="padding:0.55rem 0.9rem; border:1px solid var(--inv-border); border-radius:50px; font-size:0.85rem; outline:none; background:white; font-weight:600; cursor:pointer;"
                        onchange="this.form.submit();">
                    <option value="">Todos los estados</option>
                    <option value="Abierta"       <?= ($_GET['estado'] ?? '') === 'Abierta'       ? 'selected' : '' ?>>Abierta</option>
                    <option value="En Desarrollo" <?= ($_GET['estado'] ?? '') === 'En Desarrollo' ? 'selected' : '' ?>>En Desarrollo</option>
                    <option value="Finalizada"    <?= ($_GET['estado'] ?? '') === 'Finalizada'    ? 'selected' : '' ?>>Finalizada</option>
                    <option value="Cerrada"       <?= ($_GET['estado'] ?? '') === 'Cerrada'       ? 'selected' : '' ?>>Cerrada</option>
                </select>

                <button type="submit" style="background:linear-gradient(135deg,#0369A1,#0077BE); color:white; border:none; padding:0.55rem 1.1rem; border-radius:50px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:0.4rem; font-size:0.85rem;">
                    <i class="ph-bold ph-funnel"></i> Filtrar
                </button>
                <?php if (!empty($_GET['q']) || !empty($_GET['estado']) || !empty($_GET['linea']) || !empty($_GET['profesor'])): ?>
                <a href="?ruta=panel-investigaciones-admin" style="color:var(--inv-muted); font-size:0.84rem; text-decoration:none; display:inline-flex; align-items:center; gap:0.3rem; font-weight:600;">
                    <i class="ph-bold ph-x-circle"></i> Limpiar
                </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="inv-table-container" style="margin-bottom: 1.5rem;">
        <table class="inv-table">
            <thead>
                <tr>
                    <th style="width: 34%;">Investigación / Línea</th>
                    <th style="width: 22%;">Docente Responsable</th>
                    <th style="width: 14%;">Cupos y Estado</th>
                    <th style="width: 30%; text-align: right;">Control y Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($investigaciones as $inv): ?>
                <?php
                    $trayectoKey = $inv['trayecto'] ?? 't4';
                    $trayectoTexto = $trayectoLabels[$trayectoKey] ?? strtoupper((string)$trayectoKey);
                    $tagQue = $inv['tag_que'] ?? 'Proyecto';
                ?>
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:0.4rem; flex-wrap:wrap; margin-bottom:0.35rem;">
                            <span style="color: var(--inv-muted); font-size: 0.73rem; font-family: monospace; background:#F1F5F9; padding:0.18rem 0.5rem; border-radius:4px; font-weight:700;">#<?= (int)$inv['id'] ?></span>
                            <span style="font-size:0.73rem; font-weight:700; background:#E0F2FE; color:#0369A1; padding:0.18rem 0.55rem; border-radius:50px;">
                                <?= htmlspecialchars($tagQue) ?>
                            </span>
                            <span style="font-size:0.73rem; font-weight:700; background:#F1F5F9; color:#475569; padding:0.18rem 0.55rem; border-radius:50px;">
                                <?= htmlspecialchars($trayectoTexto) ?>
                            </span>
                        </div>
                        <strong style="color: var(--inv-dark); font-size:1.02rem; display:block; line-height:1.35;"><?= htmlspecialchars($inv['titulo'] ?? '') ?></strong>
                        <div style="font-size: 0.83rem; color: var(--inv-muted); margin-top: 0.3rem; display:flex; align-items:center; gap:0.35rem;">
                            <i class="ph-fill ph-bookmark-simple" style="color:var(--inv-primary);"></i>
                            <span><?= htmlspecialchars($inv['linea_nombre'] ?? 'Sin línea asignada') ?></span>
                        </div>
                    </td>
                    <td>
                        <form action="?ruta=cambiar-estado-investigacion" method="POST" style="display:flex; flex-direction:column; gap:0.35rem;">
                            <input type="hidden" name="id" value="<?= (int)$inv['id'] ?>">
                            <div style="display:flex; align-items:center; gap:0.4rem;">
                                <i class="ph-fill ph-chalkboard-teacher" style="color:var(--inv-primary); font-size:1.1rem;"></i>
                                <select name="id_profesor" onchange="this.form.submit()" title="Cambiar docente responsable de esta investigación"
                                        style="padding:0.38rem 0.65rem; border:1px solid var(--inv-border); border-radius:8px; font-size:0.84rem; font-weight:600; color:var(--inv-dark); background:#F8FAFC; cursor:pointer; max-width:210px; outline:none;">
                                    <?php foreach ($responsablesDisponibles as $resp): ?>
                                        <option value="<?= (int)$resp['id'] ?>" <?= ((int)($inv['id_profesor'] ?? 0) === (int)$resp['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($resp['nombre_completo'] ?? $resp['nombre'] ?? '') ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <?php if (!empty($inv['id_profesor']) && !in_array((int)$inv['id_profesor'], array_map('intval', array_column($responsablesDisponibles, 'id')), true)): ?>
                                        <option value="<?= (int)$inv['id_profesor'] ?>" selected><?= htmlspecialchars($inv['profesor'] ?? 'Responsable actual') ?></option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <span style="font-size:0.74rem; color:var(--inv-muted); padding-left:1.5rem;">Selecciona para reasignar</span>
                        </form>
                    </td>
                    <td>
                        <?php
                        $clase_estado = 'inv-status-borrador';
                        if ($inv['estado'] === 'Abierta')       $clase_estado = 'inv-status-activo';
                        if ($inv['estado'] === 'En Desarrollo') $clase_estado = 'inv-status-publicado';
                        if ($inv['estado'] === 'Finalizada')    $clase_estado = 'inv-status-archivado';
                        if ($inv['estado'] === 'Cerrada')       $clase_estado = 'inv-status-rechazado';
                        ?>
                        <span class="inv-status-pill <?= $clase_estado ?>"><?= htmlspecialchars($inv['estado']) ?></span>
                        <div style="margin-top:0.45rem; font-size:0.8rem; color:var(--inv-muted); display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                            <span title="Cupos disponibles"><i class="ph-bold ph-users"></i> <?= (int)($inv['cupos_disponibles'] ?? 0) ?> cupos</span>
                            <?php if (!empty($inv['total_postulantes'])): ?>
                                <span style="background:#EDE9FE; color:#6D28D9; padding:0.1rem 0.45rem; border-radius:50px; font-weight:700; font-size:0.74rem;">
                                    <?= (int)$inv['total_postulantes'] ?> post.
                                </span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td style="text-align: right;">
                        <div style="display:flex; justify-content:flex-end; align-items:center; gap: 0.55rem; flex-wrap:wrap;">
                            <!-- Selector rápido de Estado (Mantener / En Desarrollo / Finalizar / Cerrar) -->
                            <form action="?ruta=cambiar-estado-investigacion" method="POST" style="display:inline-flex; align-items:center; gap: 0.35rem; background:#F8FAFC; padding:0.25rem 0.35rem; border-radius:50px; border:1px solid var(--inv-border);">
                                <input type="hidden" name="id" value="<?= (int)$inv['id'] ?>">
                                <select name="estado" onchange="this.form.submit()" title="Cambiar estado de la investigación"
                                        style="padding: 0.35rem 0.65rem; border: none; border-radius: 50px; font-size: 0.82rem; background:transparent; font-weight:700; color:var(--inv-dark); outline:none; cursor:pointer;">
                                    <option value="Abierta"       <?= $inv['estado'] === 'Abierta'       ? 'selected' : '' ?>>Abierta</option>
                                    <option value="En Desarrollo" <?= $inv['estado'] === 'En Desarrollo' ? 'selected' : '' ?>>En Desarrollo</option>
                                    <option value="Finalizada"    <?= $inv['estado'] === 'Finalizada'    ? 'selected' : '' ?>>Finalizada</option>
                                    <option value="Cerrada"       <?= $inv['estado'] === 'Cerrada'       ? 'selected' : '' ?>>Cerrada</option>
                                </select>
                                <button type="submit" class="inv-action-btn" title="Guardar Estado" style="width:30px; height:30px;">
                                    <i class="ph-bold ph-arrows-clockwise"></i>
                                </button>
                            </form>

                            <!-- Ver Detalles Completos -->
                            <button type="button" class="inv-action-btn" title="Ver Ficha Completa de la Investigación"
                                    style="background:#E0F2FE; color:#0369A1;"
                                    onclick='verDetalleInvestigacionAdmin(<?= json_encode([
                                        "id" => (int)$inv["id"],
                                        "titulo" => (string)($inv["titulo"] ?? ""),
                                        "profesor" => (string)($inv["profesor"] ?? ""),
                                        "linea" => (string)($inv["linea_nombre"] ?? ""),
                                        "estado" => (string)($inv["estado"] ?? ""),
                                        "trayecto" => $trayectoTexto,
                                        "tag_que" => $tagQue,
                                        "cupos" => (int)($inv["cupos_disponibles"] ?? 0),
                                        "postulantes" => (int)($inv["total_postulantes"] ?? 0),
                                        "pendientes" => (int)($inv["postulantes_pendientes"] ?? 0),
                                        "planteamiento" => (string)($inv["planteamiento_problema"] ?? "Sin planteamiento registrado."),
                                        "objetivo" => (string)($inv["objetivo_general"] ?? "Sin objetivo general registrado."),
                                        "fecha" => !empty($inv["fecha_publicacion"]) ? date("d/m/Y", strtotime($inv["fecha_publicacion"])) : "—",
                                    ], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>)'>
                                <i class="ph-bold ph-eye"></i>
                            </button>

                            <!-- Editar / Modificar Investigación Completa -->
                            <a href="?ruta=editar-investigacion&id=<?= (int)$inv['id'] ?>&from_admin=1"
                               class="inv-action-btn edit" title="Modificar Investigación (Título, Problema, Líneas, Responsable, etc.)"
                               style="text-decoration:none;">
                                <i class="ph-bold ph-pencil-simple"></i>
                            </a>

                            <!-- Eliminar Investigación -->
                            <form action="?ruta=eliminar-investigacion" method="POST" style="display:inline;" id="form-delete-<?= (int)$inv['id'] ?>">
                                <input type="hidden" name="id" value="<?= (int)$inv['id'] ?>">
                                <input type="hidden" name="from_admin" value="1">
                                <button type="button" class="inv-action-btn del" title="Eliminar Proyecto Permanentemente" onclick="confirmarEliminacion(<?= (int)$inv['id'] ?>)">
                                    <i class="ph-bold ph-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($investigaciones)): ?>
                <tr>
                    <td colspan="4" style="text-align:center; padding:3rem; color:var(--inv-muted);">No hay investigaciones que coincidan con los filtros seleccionados.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINACIÓN ADMIN -->
    <?php if (isset($paginacion) && $paginacion['paginas'] > 1): ?>
    <?php
        $qAdminBase = ['ruta' => 'panel-investigaciones-admin'];
        if (!empty($_GET['q']))        $qAdminBase['q']        = $_GET['q'];
        if (!empty($_GET['estado']))   $qAdminBase['estado']   = $_GET['estado'];
        if (!empty($_GET['linea']))    $qAdminBase['linea']    = $_GET['linea'];
        if (!empty($_GET['profesor'])) $qAdminBase['profesor'] = $_GET['profesor'];
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
                        min="3" max="50" step="1" value="<?= htmlspecialchars((string)(int)($porPaginaInv ?? 12)) ?>"
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
function verDetalleInvestigacionAdmin(inv) {
    const overlay = document.createElement('div');
    overlay.style.cssText = 'position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.75); z-index:99999; display:flex; align-items:center; justify-content:center; backdrop-filter:blur(4px); padding:1rem;';

    const esc = (str) => {
        const d = document.createElement('div');
        d.textContent = str ?? '';
        return d.innerHTML;
    };

    overlay.innerHTML = `
        <div style="background:white; border-radius:20px; max-width:680px; width:100%; max-height:90vh; overflow-y:auto; box-shadow:0 25px 50px -12px rgba(0,0,0,0.35); border:1px solid #E2E8F0;">
            <div style="padding:1.5rem 2rem; border-bottom:1px solid #F1F5F9; display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; background:linear-gradient(135deg,#F8FAFC,#EFF6FF);">
                <div>
                    <div style="display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap; margin-bottom:0.5rem;">
                        <span style="font-family:monospace; font-size:0.78rem; font-weight:700; background:#E2E8F0; color:#334155; padding:0.2rem 0.55rem; border-radius:6px;">#${inv.id}</span>
                        <span style="font-size:0.76rem; font-weight:700; background:#DBEAFE; color:#0369A1; padding:0.2rem 0.65rem; border-radius:50px;">${esc(inv.tag_que)}</span>
                        <span style="font-size:0.76rem; font-weight:700; background:#F1F5F9; color:#475569; padding:0.2rem 0.65rem; border-radius:50px;">${esc(inv.trayecto)}</span>
                        <span style="font-size:0.76rem; font-weight:700; background:#FEF3C7; color:#B45309; padding:0.2rem 0.65rem; border-radius:50px;">Estado: ${esc(inv.estado)}</span>
                    </div>
                    <h3 style="margin:0; color:#0F172A; font-size:1.3rem; font-weight:800; line-height:1.35;">${esc(inv.titulo)}</h3>
                </div>
                <button type="button" onclick="this.closest('div').parentElement.parentElement.remove()" style="background:white; border:1px solid #E2E8F0; width:36px; height:36px; border-radius:50%; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:1.1rem; color:#64748B; flex-shrink:0;">
                    <i class="ph-bold ph-x"></i>
                </button>
            </div>
            <div style="padding:1.75rem 2rem; display:flex; flex-direction:column; gap:1.25rem;">
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:1rem; background:#F8FAFC; padding:1rem 1.25rem; border-radius:12px; border:1px solid #E2E8F0;">
                    <div>
                        <div style="font-size:0.75rem; text-transform:uppercase; color:#64748B; font-weight:700;">Docente Responsable</div>
                        <div style="font-weight:700; color:#0F172A; margin-top:0.2rem;"><i class="ph-fill ph-chalkboard-teacher" style="color:#0369A1;"></i> ${esc(inv.profesor)}</div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem; text-transform:uppercase; color:#64748B; font-weight:700;">Línea de Investigación</div>
                        <div style="font-weight:700; color:#0F172A; margin-top:0.2rem;"><i class="ph-fill ph-bookmark-simple" style="color:#0369A1;"></i> ${esc(inv.linea)}</div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem; text-transform:uppercase; color:#64748B; font-weight:700;">Cupos / Postulantes</div>
                        <div style="font-weight:700; color:#0F172A; margin-top:0.2rem;">${inv.cupos} cupos · ${inv.postulantes} postulantes (${inv.pendientes} pend.)</div>
                    </div>
                </div>

                <div>
                    <h4 style="margin:0 0 0.45rem 0; font-size:0.9rem; text-transform:uppercase; letter-spacing:0.5px; color:#0369A1; font-weight:800;">
                        <i class="ph-bold ph-question"></i> Planteamiento del Problema
                    </h4>
                    <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px; padding:1rem; color:#334155; font-size:0.93rem; line-height:1.6; white-space:pre-line;">${esc(inv.planteamiento)}</div>
                </div>

                <div>
                    <h4 style="margin:0 0 0.45rem 0; font-size:0.9rem; text-transform:uppercase; letter-spacing:0.5px; color:#0369A1; font-weight:800;">
                        <i class="ph-bold ph-target"></i> Objetivo General
                    </h4>
                    <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px; padding:1rem; color:#334155; font-size:0.93rem; line-height:1.6; white-space:pre-line;">${esc(inv.objetivo)}</div>
                </div>
            </div>
            <div style="padding:1.25rem 2rem; border-top:1px solid #F1F5F9; background:#F8FAFC; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem; border-radius:0 0 20px 20px;">
                <button type="button" onclick="this.closest('div').parentElement.parentElement.remove(); confirmarEliminacion(${inv.id});"
                        style="background:#FEE2E2; color:#DC2626; border:1px solid #FECACA; padding:0.65rem 1.2rem; border-radius:50px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:0.4rem; font-size:0.88rem;">
                    <i class="ph-bold ph-trash"></i> Eliminar Investigación
                </button>
                <div style="display:flex; gap:0.65rem;">
                    <button type="button" onclick="this.closest('div').parentElement.parentElement.parentElement.remove()"
                            style="background:white; color:#334155; border:1px solid #CBD5E1; padding:0.65rem 1.3rem; border-radius:50px; font-weight:700; cursor:pointer; font-size:0.88rem;">
                        Cerrar
                    </button>
                    <a href="?ruta=editar-investigacion&id=${inv.id}&from_admin=1"
                       style="background:linear-gradient(135deg,#0369A1,#0077BE); color:white; text-decoration:none; padding:0.65rem 1.4rem; border-radius:50px; font-weight:700; display:inline-flex; align-items:center; gap:0.4rem; font-size:0.88rem; box-shadow:0 4px 12px rgba(3,105,161,0.3);">
                        <i class="ph-bold ph-pencil-simple"></i> Modificar Investigación
                    </a>
                </div>
            </div>
        </div>
    `;

    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) overlay.remove();
    });
    document.body.appendChild(overlay);
}

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
