<?php
// modules/Cursos/views/gestion_cursos.php
// Vista de Administración (Mis Cursos / Todos los Cursos para SuperAdmin y Comité)
$nivel      = isset($usuario_actual['nivel']) ? (int)$usuario_actual['nivel'] : 999;
$rol_actual = strtolower((string)($usuario_actual['rol'] ?? ''));
$es_admin_cursos = !empty($es_admin_cursos)
    || ($nivel >= 0 && $nivel <= 1)
    || str_contains($rol_actual, 'super')
    || str_contains($rol_actual, 'admin')
    || str_contains($rol_actual, 'comit');

$puede_eliminar = $es_admin_cursos || ($nivel <= 2);
$busqueda  = htmlspecialchars($filtros['busqueda'] ?? '', ENT_QUOTES, 'UTF-8');
$estado_f  = $filtros['estado'] ?? '';
$docente_f = (int)($filtros['id_docente'] ?? ($_GET['docente'] ?? 0));
$docentes  = $docentes ?? [];

$placeholder = htmlspecialchars($config_vista['imagenes']['placeholder_url'] ?? '', ENT_QUOTES, 'UTF-8');

// DB stores 'public/uploads/cursos/x.webp' or 'storage/uploads/cursos/x.webp'; resolve relative to public/
if (!function_exists('curImgUrl')) {
    function curImgUrl(string $raw): string {
        $raw = trim(str_replace('\\', '/', $raw));
        if ($raw === '') return '';
        if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://') || str_starts_with($raw, 'data:image/')) {
            return $raw;
        }
        $clean = ltrim(preg_replace('#^(public|storage)/#', '', $raw), '/');
        $rootDir = dirname(__DIR__, 3);
        if (file_exists($rootDir . '/public/' . $clean)) {
            return $clean;
        }
        if (file_exists($rootDir . '/storage/' . $clean)) {
            return '../storage/' . $clean;
        }
        return ltrim(preg_replace('#^public/#', '', $raw), '/');
    }
}

$pag = $paginacion ?? ['pagina_actual' => 1, 'total_paginas' => 1, 'total' => 0, 'por_pagina' => 12];
$pagina_actual = (int)$pag['pagina_actual'];
$total_paginas = (int)$pag['total_paginas'];
?>
<div class="cur-wrapper">
    
    <?php if ($mensaje_error): ?>
        <div class="cur-flash cur-flash-error" style="border-radius:12px;">
            <i class="ph-fill ph-warning-circle"></i> <?= htmlspecialchars($mensaje_error) ?>
        </div>
    <?php endif; ?>

    <div class="cur-header-gestion" style="flex-wrap:wrap; gap:1rem;">
        <div>
            <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
                <h1 style="font-size: 2.2rem; font-weight: 800; color: var(--cur-dark); margin: 0;">
                    Gestión de Cursos
                </h1>
                <?php if ($es_admin_cursos): ?>
                    <span style="background: rgba(80, 89, 132, 0.12); color: #505984; border: 1px solid rgba(80, 89, 132, 0.25); padding: 0.3rem 0.85rem; border-radius: 50px; font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: inline-flex; align-items: center; gap: 0.35rem;">
                        <i class="ph-fill ph-shield-check"></i> Control Total SuperAdmin
                    </span>
                <?php endif; ?>
            </div>
            <p style="color:var(--cur-muted); margin-top: 0.3rem;">
                <?= $es_admin_cursos ? 'Administra todos los cursos del ecosistema CIIDI, asigna docentes responsables y controla su estado de publicación.' : 'Administra tu oferta académica y su disponibilidad.' ?>
            </p>
        </div>
        <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
            <a href="?ruta=cursos" style="background:white; color:#505984; border:1px solid rgba(80,89,132,0.25); border-radius:50px; padding: 0.75rem 1.3rem; font-size:0.95rem; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:0.45rem;">
                <i class="ph-bold ph-eye"></i> Ver Catálogo
            </a>
            <?php if ($es_admin_cursos): ?>
            <a href="/proyect_CIIDI/PORTAL_EXTERNO_MYSQL/" target="_blank" style="background:white; color:#505984; border:1px solid rgba(80,89,132,0.25); border-radius:50px; padding: 0.75rem 1.3rem; font-size:0.95rem; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:0.45rem;" title="Abrir el Portal Externo en Localhost">
                <i class="ph-bold ph-arrow-square-out"></i> Portal Externo Local
            </a>
            <button type="button" id="btn-quick-sync-gestion" onclick="sincronizarPortalDesdeGestion()" style="background:#ECFDF5; color:#059669; border:1px solid #A7F3D0; border-radius:50px; padding: 0.75rem 1.3rem; font-size:0.95rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:0.45rem; transition:all 0.2s;" title="Enviar cursos e investigadores al Portal Externo MySQL">
                <i class="ph-bold ph-arrows-clockwise"></i> Sincronizar Portal
            </button>
            <a href="?ruta=cursos-config" style="background:white; color:#1E293B; border:1px solid #CBD5E1; border-radius:50px; padding: 0.75rem 1.3rem; font-size:0.95rem; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:0.45rem;">
                <i class="ph-bold ph-sliders"></i> Configuración
            </a>
            <?php endif; ?>
            <a href="?ruta=cursos-crear" class="cur-btn-crear" style="border-radius:50px; padding: 0.8rem 1.8rem; font-size:1.05rem;">
                <i class="ph-bold ph-plus"></i> Crear Curso
            </a>
        </div>
    </div>

    <div class="cur-table-container" style="border-radius:16px; box-shadow: 0 10px 30px rgba(15,23,42,0.04); border: 1px solid rgba(80,89,132,0.15);">
        
        <!-- BUSCADOR MODERNO -->
        <div style="padding: 1.5rem 2rem; background: #F8FAFC; border-bottom:1px solid var(--cur-border); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
            <form method="GET" style="display:flex; gap:0.85rem; flex-wrap:wrap; align-items:center;">
                <input type="hidden" name="ruta" value="cursos-gestion">
                
                <div style="position:relative;">
                    <i class="ph-bold ph-magnifying-glass" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                    <input type="text" name="busqueda" value="<?= $busqueda ?>" placeholder="Buscar por título o docente..." style="width:280px; padding: 0.8rem 1rem 0.8rem 2.5rem; border: 1px solid var(--cur-border); border-radius: 50px; outline:none; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 3px rgba(80,89,132,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                </div>

                <?php if ($es_admin_cursos && !empty($docentes)): ?>
                <div style="position:relative;">
                    <i class="ph-bold ph-chalkboard-teacher" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                    <select name="docente" style="width:220px; padding: 0.8rem 2.2rem 0.8rem 2.5rem; border: 1px solid var(--cur-border); border-radius: 50px; outline:none; appearance:none; background:white; cursor:pointer;" onfocus="this.style.boxShadow='0 0 0 3px rgba(80,89,132,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                        <option value="">Todos los docentes</option>
                        <?php foreach ($docentes as $doc): ?>
                            <option value="<?= (int)$doc['id'] ?>" <?= $docente_f === (int)$doc['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($doc['nombre_completo']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <i class="ph-bold ph-caret-down" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted); pointer-events:none;"></i>
                </div>
                <?php endif; ?>

                <div style="position:relative;">
                    <i class="ph-bold ph-funnel" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                    <select name="estado" style="width:180px; padding: 0.8rem 1rem 0.8rem 2.5rem; border: 1px solid var(--cur-border); border-radius: 50px; outline:none; appearance:none; background:white; cursor:pointer;" onfocus="this.style.boxShadow='0 0 0 3px rgba(80,89,132,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                        <option value="">Todos los estados</option>
                        <option value="publicado" <?= $estado_f === 'publicado' ? 'selected' : '' ?>>Publicados</option>
                        <option value="borrador" <?= $estado_f === 'borrador' ? 'selected' : '' ?>>Borradores</option>
                        <option value="archivado" <?= $estado_f === 'archivado' ? 'selected' : '' ?>>Archivados</option>
                    </select>
                    <i class="ph-bold ph-caret-down" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted); pointer-events:none;"></i>
                </div>

                <button type="submit" style="background:var(--cur-primary); color:white; border:none; border-radius:50px; padding:0.8rem 1.5rem; font-weight:600; cursor:pointer; transition:all 0.2s; display:flex; align-items:center; gap:0.5rem;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 10px rgba(80,89,132,0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    Filtrar
                </button>

                <?php if($busqueda || $estado_f || ($es_admin_cursos && $docente_f > 0)): ?>
                    <a href="?ruta=cursos-gestion" style="color:var(--cur-danger); text-decoration:none; font-weight:600; font-size:0.9rem; display:flex; align-items:center; gap:0.3rem;"><i class="ph-bold ph-x-circle"></i> Limpiar</a>
                <?php endif; ?>
            </form>
            
            <div style="background:white; border:1px solid var(--cur-border); padding:0.5rem 1rem; border-radius:50px; font-size:0.85rem; font-weight:600; color:var(--cur-dark); display:flex; align-items:center; gap:0.5rem;">
                <span style="width:8px; height:8px; background:var(--cur-primary); border-radius:50%; display:inline-block;"></span>
                <?= $pag['total'] ?> cursos registrados
            </div>
        </div>

        <table class="cur-table">
            <thead>
                <tr>
                    <th width="80">Portada</th>
                    <th>Título</th>
                    <th>Docente</th>
                    <th>Modalidad</th>
                    <th>Estado</th>
                    <th width="140">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($cursos)): ?>
                <tr>
                    <td colspan="6" style="text-align:center; padding:4rem; color:var(--cur-muted);">
                        <i class="ph-fill ph-book-open" style="font-size:3rem; color:var(--cur-border); margin-bottom:1rem;"></i><br>
                        No se encontraron cursos con estos filtros.
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach($cursos as $c): 
                        $img_raw = $c['imagen_portada'] ?? '';
                        $img = !empty($img_raw) ? htmlspecialchars(curImgUrl($img_raw)) : $placeholder;
                        $slug_det = !empty($c['slug']) ? htmlspecialchars($c['slug']) : (string)$c['id'];
                    ?>
                    <tr>
                        <td><img src="<?= $img ?>" class="cur-table-img" onerror="this.src='<?= $placeholder ?>'" style="box-shadow:0 4px 6px rgba(0,0,0,0.05);"></td>
                        <td>
                            <div class="cur-table-title" style="font-size:1.05rem;"><?= htmlspecialchars($c['titulo']) ?></div>
                            <small style="color:var(--cur-muted);"><i class="ph-fill ph-calendar-blank"></i> <?= date('d M, Y', strtotime($c['fecha_creacion'])) ?></small>
                        </td>
                        <td style="font-weight:600; color:var(--cur-dark);">
                            <span style="display:inline-flex; align-items:center; gap:0.35rem;">
                                <i class="ph-fill ph-chalkboard-teacher" style="color:#505984;"></i>
                                <?= htmlspecialchars($c['nombre_docente'] ?? 'Sin asignar') ?>
                            </span>
                        </td>
                        <td><span style="background:var(--cur-light); padding:0.3rem 0.8rem; border-radius:20px; font-size:0.85rem; border:1px solid var(--cur-border);"><i class="ph-fill ph-monitor" style="color:var(--cur-muted);"></i> <?= htmlspecialchars($c['modalidad'] ?? 'Virtual') ?></span></td>
                        <td>
                            <span class="cur-status-pill cur-status-<?= $c['estado'] ?>">
                                <?= ucfirst($c['estado']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="cur-action-btns">
                                <a href="?ruta=cursos-detalle&slug=<?= $slug_det ?>" class="cur-action-btn" title="Ver Detalle"><i class="ph-bold ph-eye"></i></a>
                                <a href="?ruta=cursos-editar&id=<?= $c['id'] ?>" class="cur-action-btn" title="Editar Curso"><i class="ph-bold ph-pencil-simple"></i></a>
                                <?php if($es_admin_cursos || $puede_eliminar || (int)$c['id_docente'] === (int)($usuario_actual['id'] ?? 0)): ?>
                                <button type="button" class="cur-action-btn del" onclick="abrirModal(<?= $c['id'] ?>, '<?= addslashes(htmlspecialchars($c['titulo'])) ?>')" title="Eliminar Curso"><i class="ph-bold ph-trash"></i></button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- Pagination -->
        <?php if ($total_paginas > 1): ?>
        <div style="padding: 1.5rem; display:flex; justify-content:center; background:#F8FAFC; border-top:1px solid var(--cur-border);">
            <div class="cur-pag-controls">
                <?php for ($p = max(1, $pagina_actual - 2); $p <= min($total_paginas, $pagina_actual + 2); $p++): ?>
                    <a href="?ruta=cursos-gestion&pagina=<?= $p ?><?= $busqueda ? '&busqueda='.$busqueda : '' ?><?= $estado_f ? '&estado='.$estado_f : '' ?><?= ($es_admin_cursos && $docente_f > 0) ? '&docente='.$docente_f : '' ?>" class="cur-pag-btn <?= $p === $pagina_actual ? 'cur-pag-btn--active' : '' ?>"><?= $p ?></a>
                <?php endfor; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Eliminar -->
<div id="modal-eliminar" class="cur-modal-overlay" style="display:none;">
    <div class="cur-modal" style="border-radius:16px;">
        <div class="cur-modal-icon" style="background:#FEE2E2; color:#EF4444; width:80px; height:80px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem;"><i class="ph-fill ph-warning" style="font-size:3rem; margin:0;"></i></div>
        <h3 id="modal-titulo" style="font-size:1.3rem;">¿Eliminar curso?</h3>
        <p style="margin-bottom:2rem;">Esta acción es <strong>irreversible</strong> y eliminará toda la información del catálogo.</p>
        <div class="cur-modal-actions">
            <button type="button" onclick="cerrarModal()" class="cur-btn-secondary" style="border-radius:50px;">Cancelar</button>
            <form method="POST" action="?ruta=cursos-eliminar">
                <input type="hidden" name="id" id="modal-id">
                <?= CursosCsrfService::campoHidden() ?>
                <button type="submit" class="cur-action-btn del" style="width:auto; border-radius:50px; padding:0 1.5rem; height:45px; font-size:1rem; font-weight:600;"><i class="ph-bold ph-trash" style="margin-right:0.5rem;"></i> Sí, eliminar permanentemente</button>
            </form>
        </div>
    </div>
</div>

<script>
function abrirModal(id, titulo) {
    document.getElementById('modal-id').value = id;
    document.getElementById('modal-titulo').textContent = '¿Eliminar "' + titulo + '"?';
    document.getElementById('modal-eliminar').style.display = 'flex';
}
function cerrarModal() {
    document.getElementById('modal-eliminar').style.display = 'none';
}
// Cerrar modal al hacer clic fuera
document.getElementById('modal-eliminar').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

function sincronizarPortalDesdeGestion() {
    const btn = document.getElementById('btn-quick-sync-gestion');
    if (!btn) return;
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="ph-bold ph-spinner ph-spin"></i> Sincronizando...';

    const fd = new FormData();
    fd.append('ajax', '1');

    fetch('?ruta=cursos-sincronizar-externo', {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        if (data && data.ok) {
            btn.innerHTML = '<i class="ph-bold ph-check-circle"></i> ¡Sincronizado (' + data.cursos_enviados + ' cursos)!';
            setTimeout(() => { btn.innerHTML = originalHtml; }, 3500);
        } else {
            btn.innerHTML = originalHtml;
            alert((data && data.mensaje) ? data.mensaje : 'No se pudo conectar con el servidor externo. Revisa la URL en Configuración.');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        alert('Error de red al sincronizar: ' + err.message);
    });
}
</script>

<!-- Modal de Éxito -->
<?php if ($mensaje_exito): ?>
<div id="modal-exito-overlay" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.65); z-index:99999; display:flex; align-items:center; justify-content:center; backdrop-filter:blur(4px);">
    <div style="background: white; padding: 2.5rem 2rem; border-radius: 16px; max-width: 420px; width: 90%; text-align: center; box-shadow: 0 20px 50px rgba(0,0,0,0.2); animation: modalPop 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
        <div style="width:80px; height:80px; background:#DCFCE7; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem;">
            <i class="ph-fill ph-check-circle" style="font-size:3rem; color:#16A34A;"></i>
        </div>
        <h3 style="font-size:1.3rem; font-weight:700; color:#0F172A; margin:0 0 0.75rem;">¡Operación Exitosa!</h3>
        <p style="color:#64748B; font-size:0.95rem; margin-bottom:2rem; line-height:1.5;"><?= htmlspecialchars($mensaje_exito) ?></p>
        <button onclick="document.getElementById('modal-exito-overlay').remove()" style="background: linear-gradient(135deg, rgba(80,89,132,0.95), rgba(112,144,203,0.9)); color:white; border:none; border-radius:50px; padding:0.8rem 2rem; font-weight:700; font-size:1rem; cursor:pointer; transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
            <i class="ph-bold ph-check"></i> Entendido
        </button>
    </div>
</div>
<script>
// Auto-cerrar el modal de éxito al hacer clic fuera
document.getElementById('modal-exito-overlay').addEventListener('click', function(e) {
    if (e.target === this) this.remove();
});
</script>
<?php endif; ?>
