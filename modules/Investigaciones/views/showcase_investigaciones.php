<?php
// modules/Investigaciones/views/showcase_investigaciones.php
require_once CORE_PATH . 'Security/Auth.php';
$nivel_usuario    = Auth::check() ? Auth::usuario()['nivel'] : -1;
$is_logged        = $is_logged ?? Auth::check();
$postuladas_ids   = $postuladas_ids ?? [];
$misPostulaciones = $misPostulaciones ?? [];
?>
<div class="inv-wrapper">

    <!-- FLASH MESSAGES -->
    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="inv-flash inv-flash-success"><i class="ph-fill ph-check-circle"></i> <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="inv-flash inv-flash-error"><i class="ph-fill ph-warning-circle"></i> <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
    <?php endif; ?>

    <!-- HERO SECTION — Paleta de Cursos -->
    <section class="landing-hero-modern" style="margin-bottom: 2rem; border-radius: var(--inv-radius-xl); overflow: hidden; box-shadow: 0 20px 40px rgba(80, 89, 132, 0.25); background: linear-gradient(135deg, rgba(80,89,132,0.97) 0%, rgba(112,144,203,0.93) 100%); border: none;">

        <canvas id="landingCanvasBg" class="landing-hero-canvas"></canvas>

        <div class="hero-text-content">
            <div class="hero-badge-glass" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white;">
                <i class="ph-fill ph-microscope"></i> Investigación y Desarrollo CIIDI
            </div>
            <h1 style="color: white;">Soberanía <span>Tecnológica</span></h1>
            <p style="margin-bottom: 2rem; color: #E0F2FE;">Impulsamos la creación, innovación y despliegue de soluciones informáticas desarrolladas por nuestros docentes y estudiantes para la región andina.</p>
            
            <div style="display: flex; gap: 1rem; flex-wrap:wrap;">
                <a href="#inv-grid" onclick="event.preventDefault(); document.getElementById('inv-grid').scrollIntoView({behavior:'smooth'});"
                   style="background: white; color: var(--inv-primary); padding: 0.8rem 1.5rem; border-radius: 50px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: transform 0.2s;"
                   onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                    <i class="ph-bold ph-rocket-launch"></i> Ver Proyectos
                </a>
                <?php if ($nivel_usuario >= 1): ?>
                <a href="?ruta=mis-investigaciones"
                   style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 0.8rem 1.5rem; border-radius: 50px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: background 0.2s;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)';" onmouseout="this.style.background='rgba(255,255,255,0.2)';">
                    <i class="ph-bold ph-folder-open"></i> Mis Proyectos
                </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="hero-graphic-spatial">
            <div class="hero-img-wrapper" style="max-width: 450px;">
                <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&q=80&w=800" alt="I+D" class="landing-hero-img-spatial" style="border-radius: 20px;">

                <div class="floating-glass-card card-top-left" style="animation-delay: 0.5s; background: rgba(255,255,255,0.9); border: none;">
                    <div class="floating-icon" style="color: #0EA5E9; background: #E0F2FE;"><i class="ph-bold ph-lightbulb"></i></div>
                    <div class="floating-info">
                        <strong style="color: var(--inv-dark);">Innovación Abierta</strong>
                        <span style="color: var(--inv-muted);">Desarrollo Endógeno</span>
                    </div>
                </div>

                <div class="floating-glass-card card-bottom-right" style="animation-delay: 1s; background: rgba(255,255,255,0.9); border: none;">
                    <div class="floating-icon" style="color: #8B5CF6; background: #EDE9FE;"><i class="ph-bold ph-users-three"></i></div>
                    <div class="floating-info">
                        <strong style="color: var(--inv-dark);">Equipos I+D</strong>
                        <span style="color: var(--inv-muted);">Docentes y Estudiantes</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MIS POSTULACIONES (solo si logueado y tiene postulaciones) -->
    <?php if ($is_logged && !empty($misPostulaciones)): ?>
    <div style="background: rgba(255,255,255,0.95); border: 1px solid rgba(80,89,132,0.15); border-radius: 16px; padding: 1.5rem 2rem; margin-bottom: 2rem; box-shadow: var(--inv-shadow-sm);">
        <h2 style="color: var(--inv-dark); font-size: 1.1rem; font-weight:800; margin-bottom: 1rem; display:flex; align-items:center; gap:0.5rem;">
            <i class="ph-fill ph-clock-counter-clockwise" style="color:var(--inv-primary);"></i> Mis Postulaciones Enviadas
        </h2>
        <div style="display: flex; gap: 1rem; overflow-x: auto; padding-bottom: 0.5rem;">
            <?php foreach($misPostulaciones as $mp): ?>
            <div style="background: #F8FAFC; border: 1px solid var(--inv-border); border-radius: 12px; padding: 1rem 1.2rem; min-width: 280px; flex: 0 0 auto;">
                <div style="font-weight: 700; color: var(--inv-dark); margin-bottom: 0.6rem; font-size: 0.9rem; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;" title="<?= htmlspecialchars($mp['investigacion_titulo']) ?>">
                    <?= htmlspecialchars($mp['investigacion_titulo']) ?>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.78rem; color: var(--inv-muted); font-weight:600;"><i class="ph-bold ph-calendar-blank"></i> <?= date('d/m/Y', strtotime($mp['fecha_postulacion'])) ?></span>
                    <?php 
                    $clase_mp = 'inv-status-borrador';
                    if($mp['estado'] === 'Aceptado')  $clase_mp = 'inv-status-aprobado';
                    if($mp['estado'] === 'Rechazado') $clase_mp = 'inv-status-rechazado';
                    ?>
                    <span class="inv-status-pill <?= $clase_mp ?>"><?= htmlspecialchars($mp['estado']) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- FILTROS Y BÚSQUEDA -->
    <header style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 1.5rem; margin-bottom: 2.5rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04);">
        <form action="index.php" method="GET" id="filterForm" style="display:flex; flex-direction:column; gap:1.5rem;">
            <input type="hidden" name="ruta" value="investigaciones">
            
            <div style="display:flex; flex-wrap:wrap; gap:1.5rem; justify-content:space-between; align-items:center;">
                <!-- Búsqueda -->
                <div style="position:relative; flex:1; min-width:300px; max-width:500px;">
                    <i class="ph-bold ph-magnifying-glass" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--inv-muted);"></i>
                    <input type="text" name="q" value="<?= htmlspecialchars($busqueda) ?>" placeholder="Buscar título o descripción..."
                           style="width:100%; padding:0.8rem 1rem 0.8rem 2.5rem; border:1px solid var(--inv-border); border-radius:50px; font-size:1rem; outline:none; transition:all 0.2s;"
                           onfocus="this.style.boxShadow='0 0 0 3px rgba(80,89,132,0.2)'; this.style.borderColor='var(--inv-primary)';"
                           onblur="this.style.boxShadow='none'; this.style.borderColor='var(--inv-border)';">
                </div>
                <!-- Selector estado -->
                <div style="display:flex; align-items:center; gap:0.5rem;">
                    <span style="font-weight:700; color:var(--inv-dark); font-size:0.9rem;"><i class="ph-bold ph-pulse"></i> Estado:</span>
                    <select name="estado" onchange="document.getElementById('filterForm').submit();"
                            style="padding:0.6rem 1.2rem; border:1px solid var(--inv-border); border-radius:50px; outline:none; background:white; font-size:0.9rem; font-weight:600; cursor:pointer;">
                        <option value="">Todos los estados</option>
                        <option value="Abierta"       <?= (isset($_GET['estado']) && $_GET['estado'] == 'Abierta')       ? 'selected' : '' ?>>Abiertas a Postulación</option>
                        <option value="En Desarrollo" <?= (isset($_GET['estado']) && $_GET['estado'] == 'En Desarrollo') ? 'selected' : '' ?>>En Desarrollo</option>
                        <option value="Finalizada"    <?= (isset($_GET['estado']) && $_GET['estado'] == 'Finalizada')    ? 'selected' : '' ?>>Finalizada</option>
                        <?php if ($nivel_usuario >= 1): /* Cerrada: solo docentes/admin la ven */ ?>
                        <option value="Cerrada"       <?= (isset($_GET['estado']) && $_GET['estado'] == 'Cerrada')       ? 'selected' : '' ?>>Cerrada</option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>


            <!-- Pills de Líneas -->
            <div style="display:flex; align-items:center; gap:1rem; border-top:1px solid var(--inv-border); padding-top:1.5rem; overflow-x:auto;">
                <span style="font-weight:700; color:var(--inv-dark); font-size:0.95rem; display:flex; align-items:center; gap:0.4rem; white-space:nowrap;"><i class="ph-bold ph-git-branch"></i> Líneas:</span>
                
                <a href="?ruta=investigaciones<?= !empty($busqueda)?'&q='.urlencode($busqueda):'' ?><?= !empty($_GET['estado'])?'&estado='.urlencode($_GET['estado']):'' ?>"
                   style="padding:0.4rem 1rem; border-radius:50px; font-size:0.85rem; font-weight:700; text-decoration:none; white-space:nowrap; transition:all 0.2s;
                          <?= empty($_GET['linea']) ? 'background:var(--inv-primary); color:white; box-shadow:0 4px 10px rgba(80,89,132,0.35);' : 'background:#F1F5F9; color:var(--inv-muted); border:1px solid transparent;' ?>">
                    Todas
                </a>
                
                <?php foreach($lineas as $l): ?>
                    <?php 
                        $isActive = (isset($_GET['linea']) && $_GET['linea'] == $l['id']); 
                        $url = "?ruta=investigaciones&linea={$l['id']}";
                        if(!empty($busqueda)) $url .= "&q=".urlencode($busqueda);
                        if(!empty($_GET['estado'])) $url .= "&estado=".urlencode($_GET['estado']);
                    ?>
                    <a href="<?= $url ?>"
                       style="padding:0.4rem 1rem; border-radius:50px; font-size:0.85rem; font-weight:700; text-decoration:none; white-space:nowrap; transition:all 0.2s;
                              <?= $isActive ? 'background:var(--inv-primary); color:white; box-shadow:0 4px 10px rgba(80,89,132,0.35);' : 'background:#F1F5F9; color:var(--inv-muted); border:1px solid transparent;' ?>">
                        <?= htmlspecialchars($l['nombre']) ?>
                    </a>
                <?php endforeach; ?>

            </div>
        </form>
    </header>

    <!-- GRID DE INVESTIGACIONES -->
    <main class="inv-grid" id="inv-grid" style="grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));">
        <?php if (empty($investigaciones)): ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 6rem 2rem; background: var(--inv-white); border-radius:16px; border: 1px dashed var(--inv-border); color: var(--inv-muted);">
                <i class="ph-fill ph-magnifying-glass" style="font-size: 4rem; color:var(--inv-primary-light); margin-bottom: 1rem;"></i>
                <h3 style="color:var(--inv-dark); font-size:1.5rem; margin-bottom:0.5rem;">No se encontraron investigaciones</h3>
                <p>Intenta ajustar tus criterios de búsqueda o explora otras líneas de investigación.</p>
            </div>
        <?php else: ?>
            <?php foreach ($investigaciones as $inv): ?>
            <?php $yaPostulado = in_array((int)$inv['id'], array_map('intval', $postuladas_ids)); ?>
            <article class="inv-card">
                <div class="inv-card-img-wrap">
                    <?php $img = !empty($inv['imagen']) ? $inv['imagen'] : 'assets/img/default-inv.png'; ?>
                    <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($inv['titulo']) ?>"
                         onerror="this.src='https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&q=80&w=400'">
                    <span class="inv-badge" style="background: rgba(15,23,42,0.85);"><i class="ph-fill ph-pulse"></i> <?= htmlspecialchars($inv['estado']) ?></span>
                    <?php if ($yaPostulado): ?>
                    <span class="inv-badge" style="background: rgba(80,89,132,0.9); top:auto; bottom:1rem; left:1rem; letter-spacing:0.3px;">
                        <i class="ph-bold ph-check"></i> Ya Postulado
                    </span>
                    <?php endif; ?>
                </div>
                
                <div class="inv-card-content">
                    <div style="display:flex; flex-wrap:wrap; gap:0.5rem; margin-bottom:1rem;">
                        <span style="background: #F1F5F9; color:#475569; padding:0.2rem 0.6rem; border-radius:6px; font-size:0.75rem; font-weight:600; display:flex; align-items:center; gap:0.3rem;" title="Docente/Tutor">
                            <i class="ph-fill ph-chalkboard-teacher"></i> <?= htmlspecialchars($inv['profesor'] ?? $inv['tag_quien'] ?? 'Tutor') ?>
                        </span>
                        <span style="background: var(--inv-primary-light); color:var(--inv-primary); padding:0.2rem 0.6rem; border-radius:6px; font-size:0.75rem; font-weight:600; display:flex; align-items:center; gap:0.3rem;" title="Línea de Investigación">
                        <i class="ph-bold ph-git-branch"></i> <?= htmlspecialchars($inv['linea_nombre']) ?>
                    </span>
                    </div>

                    <h3 class="inv-card-title"><?= htmlspecialchars($inv['titulo']) ?></h3>
                    <p class="inv-card-desc"><?= htmlspecialchars(mb_substr($inv['planteamiento_problema'], 0, 150)) ?>...</p>
                    
                    <div class="inv-card-footer">
                        <button onclick="abrirDrawer(<?= (int)$inv['id'] ?>)" class="inv-btn-primary"
                                style="padding:0.6rem 1.2rem; font-size:0.9rem; width:100%; justify-content:center;">
                            <i class="ph-bold ph-eye"></i>
                            <?php if ($inv['estado'] === 'Abierta' && !$yaPostulado): ?>
                                Ver Detalles / Postularse
                            <?php elseif ($yaPostulado): ?>
                                Ver Detalles (Postulado)
                            <?php else: ?>
                                Ver Detalles
                            <?php endif; ?>
                        </button>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <!-- PAGINACIÓN -->
    <?php if (isset($paginacion) && $paginacion['paginas'] > 1): ?>
    <?php
        // Construir base URL conservando filtros activos
        $qParams = [];
        if (!empty($busqueda))       $qParams['q']      = $busqueda;
        if (!empty($_GET['linea']))   $qParams['linea']  = (int)$_GET['linea'];
        if (!empty($_GET['estado']))  $qParams['estado'] = $_GET['estado'];
        $qParams['ruta'] = 'investigaciones';
        $basePaginacion  = '?' . http_build_query($qParams) . '&page=';
        $pagActual       = $paginacion['pagina'];
        $pagTotal        = $paginacion['paginas'];
        $total           = $paginacion['total'];
    ?>
    <nav style="display:flex; flex-direction:column; align-items:center; gap:1rem; margin-top:2.5rem; margin-bottom:1rem;">
        <!-- Info de registros -->
        <p style="color:var(--inv-muted); font-size:0.88rem; margin:0;">
            Mostrando
            <strong style="color:var(--inv-dark);">
                <?= (($pagActual - 1) * $paginacion['por_pagina']) + 1 ?>–<?= min($pagActual * $paginacion['por_pagina'], $total) ?>
            </strong>
            de <strong style="color:var(--inv-dark);"><?= $total ?></strong> investigaciones
        </p>

        <!-- Botones de páginas -->
        <div style="display:flex; gap:0.4rem; flex-wrap:wrap; justify-content:center;">
            <!-- Anterior -->
            <?php if ($pagActual > 1): ?>
            <a href="<?= $basePaginacion . ($pagActual - 1) ?>"
               style="width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; background:white; border:1px solid var(--inv-border); color:var(--inv-dark); text-decoration:none; font-weight:700; transition:all 0.2s; box-shadow:var(--inv-shadow-sm);"
               onmouseover="this.style.background='var(--inv-primary)'; this.style.color='white'; this.style.borderColor='var(--inv-primary)';"
               onmouseout="this.style.background='white'; this.style.color='var(--inv-dark)'; this.style.borderColor='var(--inv-border)';">
                <i class="ph-bold ph-caret-left"></i>
            </a>
            <?php endif; ?>

            <!-- Páginas numeradas (máx 7 visibles) -->
            <?php
            $rango  = 2;
            $inicio = max(1, $pagActual - $rango);
            $fin    = min($pagTotal, $pagActual + $rango);
            if ($inicio > 1): ?>
                <a href="<?= $basePaginacion . '1' ?>" style="width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; background:white; border:1px solid var(--inv-border); color:var(--inv-dark); text-decoration:none; font-weight:700;">1</a>
                <?php if ($inicio > 2): ?><span style="display:flex; align-items:flex-end; padding-bottom:6px; color:var(--inv-muted);">…</span><?php endif; ?>
            <?php endif; ?>

            <?php for ($i = $inicio; $i <= $fin; $i++): ?>
            <a href="<?= $basePaginacion . $i ?>"
               style="width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; text-decoration:none; font-weight:700; transition:all 0.2s;
                      <?= $i === $pagActual ? 'background:linear-gradient(135deg, var(--color-principal, #121a3e), var(--color-secundario, #505984)); color:white; border:none; box-shadow:0 4px 12px rgba(80,89,132,0.35);' : 'background:white; border:1px solid var(--inv-border); color:var(--inv-dark);' ?>"
               <?php if ($i !== $pagActual): ?>
               onmouseover="this.style.background='var(--inv-primary)'; this.style.color='white'; this.style.borderColor='var(--inv-primary)';"
               onmouseout="this.style.background='white'; this.style.color='var(--inv-dark)'; this.style.borderColor='var(--inv-border)';"
               <?php endif; ?>>
                <?= $i ?>
            </a>
            <?php endfor; ?>

            <?php if ($fin < $pagTotal): ?>
                <?php if ($fin < $pagTotal - 1): ?><span style="display:flex; align-items:flex-end; padding-bottom:6px; color:var(--inv-muted);">…</span><?php endif; ?>
                <a href="<?= $basePaginacion . $pagTotal ?>" style="width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; background:white; border:1px solid var(--inv-border); color:var(--inv-dark); text-decoration:none; font-weight:700;"><?= $pagTotal ?></a>
            <?php endif; ?>

            <!-- Siguiente -->
            <?php if ($pagActual < $pagTotal): ?>
            <a href="<?= $basePaginacion . ($pagActual + 1) ?>"
               style="width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; background:white; border:1px solid var(--inv-border); color:var(--inv-dark); text-decoration:none; font-weight:700; transition:all 0.2s; box-shadow:var(--inv-shadow-sm);"
               onmouseover="this.style.background='var(--inv-primary)'; this.style.color='white'; this.style.borderColor='var(--inv-primary)';"
               onmouseout="this.style.background='white'; this.style.color='var(--inv-dark)'; this.style.borderColor='var(--inv-border)';">
                <i class="ph-bold ph-caret-right"></i>
            </a>
            <?php endif; ?>
        </div>
    </nav>
    <?php endif; ?>

</div>

<!-- ═══════════════════════════════════════════════════
     DRAWER LATERAL — DETALLES Y POSTULACIÓN
     ═══════════════════════════════════════════════════ -->
<div id="inv-drawer-overlay"
     style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9998; opacity:0; transition:opacity 0.3s;"
     onclick="if(event.target===this) cerrarDrawer();">
    <div id="inv-drawer"
         style="position:absolute; right:0; top:0; height:100%; width:min(600px, 100vw); background:white; overflow-y:auto;
                box-shadow:-20px 0 60px rgba(15,23,42,0.15); transform:translateX(100%); transition:transform 0.35s cubic-bezier(0.4,0,0.2,1);">

        <!-- Encabezado sticky -->
        <div style="position:sticky; top:0; z-index:10; background:white; padding:1.2rem 1.5rem; border-bottom:1px solid var(--inv-border); display:flex; justify-content:space-between; align-items:center; box-shadow:0 2px 8px rgba(18,26,62,0.04);">
            <span style="font-weight:800; color:var(--inv-dark); font-size:1.1rem; display:flex; align-items:center; gap:0.5rem;">
                <i class="ph-fill ph-flask" style="color:var(--inv-primary);"></i> Detalles del Proyecto
            </span>
            <button onclick="cerrarDrawer()"
                    style="background:#F1F5F9; border:none; border-radius:50%; width:36px; height:36px; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:1rem; color:var(--inv-muted); transition:all 0.2s;"
                    onmouseover="this.style.background='#E2E8F0'; this.style.color='var(--inv-dark)';"
                    onmouseout="this.style.background='#F1F5F9'; this.style.color='var(--inv-muted)';">
                <i class="ph-bold ph-x"></i>
            </button>
        </div>

        <!-- Contenido dinámico -->
        <div id="inv-drawer-content" style="padding:1.5rem;"></div>
    </div>
</div>

<script>
// ─── Datos de investigaciones serializados a JSON ───────────────
const invData = <?= json_encode(array_values($investigaciones ?? []), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
const invById = {};
invData.forEach(inv => { invById[inv.id] = inv; });

const isLogged       = <?= json_encode($is_logged) ?>;
const postuladasIds  = <?= json_encode(array_map('intval', $postuladas_ids)) ?>;

const trayectoLabels = {
    't1': 'Trayecto I', 't2': 'Trayecto II', 't3': 'Trayecto III',
    't4': 'Trayecto IV', 'maestria': 'Maestría / Postgrado'
};

// Escapar HTML para inserción segura en innerHTML
function esc(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;').replace(/'/g,'&#039;');
}

function abrirDrawer(id) {
    const inv = invById[id];
    if (!inv) return;

    const yaPostulado = postuladasIds.includes(parseInt(id));
    const esAbierta   = inv.estado === 'Abierta';

    // Imagen con fallback
    const imgSrc = inv.imagen || 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&q=80&w=800';

    // Colores de estado
    const stateMap = {
        'Abierta':        { bg:'#DCFCE7', color:'#166534', icon:'ph-check-circle' },
        'En Desarrollo':  { bg:'#FEF3C7', color:'#92400E', icon:'ph-spinner-gap' },
        'Finalizada':     { bg:'#F1F5F9', color:'#475569', icon:'ph-check-square' },
        'Cerrada':        { bg:'#FEE2E2', color:'#991B1B', icon:'ph-x-circle' }
    };
    const sc = stateMap[inv.estado] || { bg:'#F1F5F9', color:'#475569', icon:'ph-pulse' };

    // ─── Sección de postulación según estado ───────────────────
    let postulacionHtml = '';
    if (esAbierta) {
        if (!isLogged) {
            postulacionHtml = `
                <div style="background:#F8FAFC; border-radius:14px; padding:2rem; text-align:center; border:2px dashed var(--inv-border);">
                    <i class="ph-fill ph-lock-key" style="font-size:3rem; color:#F59E0B; margin-bottom:1rem; display:block;"></i>
                    <p style="font-size:1rem; color:var(--inv-dark); font-weight:700; margin-bottom:0.5rem;">Inicia sesión para postularte</p>
                    <p style="font-size:0.9rem; color:var(--inv-muted); margin-bottom:1.5rem;">Necesitas una cuenta para aplicar a este proyecto de investigación.</p>
                    <a href="?ruta=login" style="background:linear-gradient(135deg,var(--color-principal),var(--inv-primary)); color:white; padding:0.8rem 2rem; border-radius:50px; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:0.5rem; box-shadow:0 4px 15px rgba(80,89,132,0.35);">
                        <i class="ph-bold ph-sign-in"></i> Iniciar Sesión
                    </a>
                </div>`;
        } else if (yaPostulado) {
            postulacionHtml = `
                <div style="background:var(--inv-primary-light); border-radius:14px; padding:1.5rem; display:flex; align-items:center; gap:1rem; border:1px solid rgba(80,89,132,0.2);">
                    <div style="width:50px; height:50px; background:var(--inv-primary); border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 4px 12px rgba(80,89,132,0.35);">
                        <i class="ph-bold ph-check" style="color:white; font-size:1.5rem;"></i>
                    </div>
                    <div>
                        <strong style="color:var(--inv-primary); font-size:1rem; display:block; margin-bottom:0.3rem;">¡Ya enviaste tu postulación!</strong>
                        <span style="color:var(--inv-primary); font-size:0.9rem;">Tu solicitud está siendo revisada por el docente responsable del proyecto.</span>
                    </div>
                </div>`;
        } else {
            postulacionHtml = `
                <div style="background:#F8FAFC; border-radius:14px; padding:1.5rem; border:1px solid var(--inv-border);">
                    <h4 style="color:var(--inv-dark); font-weight:800; font-size:1rem; margin-bottom:1.2rem; display:flex; align-items:center; gap:0.5rem;">
                        <i class="ph-bold ph-paper-plane-tilt" style="color:var(--inv-primary);"></i> Postularme a este Proyecto
                    </h4>
                    <form action="?ruta=postulaciones-procesar" method="POST">
                        <input type="hidden" name="id_investigacion" value="${inv.id}">
                        <div style="margin-bottom:1rem;">
                            <label style="display:block; font-weight:600; color:var(--inv-dark); margin-bottom:0.5rem; font-size:0.9rem;">
                                Carta de Motivación <span style="color:#EF4444;">*</span>
                            </label>
                            <textarea name="motivacion" required rows="4"
                                placeholder="Explica por qué te interesa este proyecto y qué puedes aportar..."
                                style="width:100%; padding:0.9rem; border:1px solid var(--inv-border); border-radius:10px; font-size:0.9rem; outline:none; font-family:inherit; resize:vertical; transition:box-shadow 0.2s; box-sizing:border-box;"
                                onfocus="this.style.boxShadow='0 0 0 3px rgba(80,89,132,0.2)'; this.style.borderColor='var(--inv-primary)';"
                                onblur="this.style.boxShadow='none'; this.style.borderColor='var(--inv-border)';"></textarea>
                        </div>
                        <div style="margin-bottom:1.5rem;">
                            <label style="display:block; font-weight:600; color:var(--inv-dark); margin-bottom:0.5rem; font-size:0.9rem;">
                                Enlace a Portafolio / GitHub <span style="color:var(--inv-muted); font-weight:400;">(Opcional)</span>
                            </label>
                            <div style="position:relative;">
                                <i class="ph-bold ph-link" style="position:absolute; left:0.9rem; top:50%; transform:translateY(-50%); color:var(--inv-muted); font-size:0.95rem;"></i>
                                <input type="url" name="portafolio" placeholder="https://github.com/tu-usuario"
                                    style="width:100%; padding:0.8rem 0.8rem 0.8rem 2.4rem; border:1px solid var(--inv-border); border-radius:10px; font-size:0.9rem; outline:none; transition:box-shadow 0.2s; box-sizing:border-box;"
                                    onfocus="this.style.boxShadow='0 0 0 3px rgba(80,89,132,0.2)'; this.style.borderColor='var(--inv-primary)';"
                                    onblur="this.style.boxShadow='none'; this.style.borderColor='var(--inv-border)';">
                            </div>
                        </div>
                        <button type="submit"
                            style="width:100%; background:linear-gradient(135deg, var(--color-principal, #121a3e) 0%, var(--color-secundario, #505984) 100%); color:white; border:none; padding:1rem; border-radius:50px; font-weight:700; font-size:1rem; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:0.6rem; transition:all 0.2s; box-shadow:0 4px 15px rgba(80,89,132,0.35);"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(80,89,132,0.45)';" 
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(80,89,132,0.35)';">
                            <i class="ph-bold ph-paper-plane-tilt"></i> Enviar Postulación
                        </button>
                    </form>
                </div>`;
        }
    } else {
        postulacionHtml = `
            <div style="background:#F1F5F9; border-radius:12px; padding:1.2rem; text-align:center; border:1px solid var(--inv-border);">
                <i class="ph-fill ph-lock" style="font-size:2rem; color:var(--inv-muted); margin-bottom:0.5rem; display:block;"></i>
                <span style="color:var(--inv-muted); font-size:0.9rem; font-weight:600;">Este proyecto ya no acepta postulaciones (${esc(inv.estado)})</span>
            </div>`;
    }

    // ─── Construcción del HTML del drawer ────────────────────────
    const trayLabel = trayectoLabels[inv.trayecto] || esc(inv.trayecto);

    document.getElementById('inv-drawer-content').innerHTML = `
        <!-- Imagen -->
        <div style="border-radius:14px; overflow:hidden; margin-bottom:1.5rem; height:200px; background:#F1F5F9;">
            <img src="${esc(imgSrc)}" alt="${esc(inv.titulo)}" style="width:100%; height:100%; object-fit:cover;"
                 onerror="this.src='https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&q=80&w=800'">
        </div>

        <!-- Badges de meta -->
        <div style="display:flex; flex-wrap:wrap; gap:0.5rem; margin-bottom:1.2rem;">
            <span style="background:${sc.bg}; color:${sc.color}; padding:0.3rem 0.85rem; border-radius:20px; font-size:0.78rem; font-weight:700; display:inline-flex; align-items:center; gap:0.35rem;">
                <i class="ph-bold ${sc.icon}"></i> ${esc(inv.estado)}
            </span>
            <span style="background:var(--inv-primary-light); color:var(--inv-primary); padding:0.3rem 0.85rem; border-radius:20px; font-size:0.78rem; font-weight:700; display:inline-flex; align-items:center; gap:0.35rem;">
                <i class="ph-fill ph-bookmark-simple"></i> ${esc(inv.linea_nombre || 'Sin línea')}
            </span>
            <span style="background:#F1F5F9; color:#475569; padding:0.3rem 0.85rem; border-radius:20px; font-size:0.78rem; font-weight:700; display:inline-flex; align-items:center; gap:0.35rem;">
                <i class="ph-fill ph-graduation-cap"></i> ${trayLabel}
            </span>
            <span style="background:#EDE9FE; color:#6D28D9; padding:0.3rem 0.85rem; border-radius:20px; font-size:0.78rem; font-weight:700; display:inline-flex; align-items:center; gap:0.35rem;">
                <i class="ph-fill ph-users-three"></i> ${parseInt(inv.cupos_disponibles) || 0} Cupos
            </span>
        </div>

        <!-- Título -->
        <h2 style="font-size:1.45rem; font-weight:800; color:var(--inv-dark); margin-bottom:1.5rem; line-height:1.3;">${esc(inv.titulo)}</h2>

        <!-- Docente -->
        <div style="display:flex; align-items:center; gap:0.8rem; margin-bottom:1.5rem; padding:0.9rem 1rem; background:#F8FAFC; border-radius:12px; border:1px solid var(--inv-border);">
            <div style="width:40px; height:40px; background:linear-gradient(135deg, var(--color-principal, #121a3e), var(--color-secundario, #505984)); border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="ph-fill ph-chalkboard-teacher" style="color:white; font-size:1.1rem;"></i>
            </div>
            <div>
                <div style="font-size:0.72rem; color:var(--inv-muted); font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Docente Responsable</div>
                <div style="font-weight:700; color:var(--inv-dark); font-size:0.95rem; margin-top:0.15rem;">${esc(inv.profesor || 'Por asignar')}</div>
            </div>
        </div>

        <!-- Planteamiento -->
        ${inv.planteamiento_problema ? `
        <div style="margin-bottom:1.5rem;">
            <div style="font-size:0.78rem; font-weight:700; color:var(--inv-muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:0.6rem; display:flex; align-items:center; gap:0.4rem;">
                <i class="ph-bold ph-file-text" style="color:var(--inv-primary);"></i> Planteamiento / Resumen
            </div>
            <p style="color:var(--inv-dark); font-size:0.92rem; line-height:1.65; background:#F8FAFC; padding:1rem 1.2rem; border-radius:10px; margin:0; border-left:3px solid var(--inv-primary);">
                ${esc(inv.planteamiento_problema)}
            </p>
        </div>` : ''}

        <!-- Objetivo -->
        ${inv.objetivo_general ? `
        <div style="margin-bottom:1.5rem;">
            <div style="font-size:0.78rem; font-weight:700; color:var(--inv-muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:0.6rem; display:flex; align-items:center; gap:0.4rem;">
                <i class="ph-bold ph-target" style="color:#4F46E5;"></i> Objetivo General
            </div>
            <p style="color:var(--inv-dark); font-size:0.92rem; line-height:1.65; background:#F8FAFC; padding:1rem 1.2rem; border-radius:10px; margin:0; border-left:3px solid #4F46E5;">
                ${esc(inv.objetivo_general)}
            </p>
        </div>` : ''}

        <!-- Sección postulación -->
        <div style="border-top:2px solid var(--inv-border); padding-top:1.5rem; margin-top:0.5rem;">
            <h3 style="font-size:1rem; font-weight:800; color:var(--inv-dark); margin-bottom:1.2rem; display:flex; align-items:center; gap:0.5rem;">
                <i class="ph-bold ph-rocket-launch" style="color:var(--inv-primary);"></i> Postulación al Proyecto
            </h3>
            ${postulacionHtml}
        </div>
    `;

    // Mostrar overlay y drawer con animación
    const overlay = document.getElementById('inv-drawer-overlay');
    const drawer  = document.getElementById('inv-drawer');
    overlay.style.display = 'block';
    requestAnimationFrame(() => {
        overlay.style.opacity = '1';
        drawer.style.transform = 'translateX(0)';
    });
    document.body.style.overflow = 'hidden';
}

function cerrarDrawer() {
    const overlay = document.getElementById('inv-drawer-overlay');
    const drawer  = document.getElementById('inv-drawer');
    overlay.style.opacity = '0';
    drawer.style.transform = 'translateX(100%)';
    setTimeout(() => {
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    }, 350);
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarDrawer(); });
</script>

<script>
// Canvas de partículas del hero
document.addEventListener("DOMContentLoaded", function() {
    const canvas = document.getElementById("landingCanvasBg");
    if (!canvas) return;
    const ctx = canvas.getContext("2d");
    let width, height, particles;
    function resize() {
        width = canvas.width = canvas.offsetWidth;
        height = canvas.height = canvas.offsetHeight;
    }
    window.addEventListener("resize", resize);
    resize();
    particles = Array.from({length: 30}, () => ({
        x: Math.random() * width, y: Math.random() * height,
        r: Math.random() * 2 + 1, vx: (Math.random() - 0.5) * 0.5, vy: (Math.random() - 0.5) * 0.5
    }));
    function draw() {
        ctx.clearRect(0, 0, width, height);
        ctx.fillStyle = "rgba(255,255,255,0.4)";
        particles.forEach(p => {
            p.x += p.vx; p.y += p.vy;
            if (p.x < 0 || p.x > width)  p.vx *= -1;
            if (p.y < 0 || p.y > height) p.vy *= -1;
            ctx.beginPath(); ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2); ctx.fill();
        });
        requestAnimationFrame(draw);
    }
    draw();
});
</script>