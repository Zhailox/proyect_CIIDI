<?php
// modules/Investigaciones/views/ficha_investigador.php
// Variables: $investigadores (array), $lineas (array), $busqueda (string), $id_linea (int)
$busqueda = $busqueda ?? '';
$id_linea = $id_linea ?? 0;
$totalInvestigadores = count($investigadores ?? []);
?>
<div class="inv-wrapper" style="max-width: 1300px; margin: 0 auto; font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;">

    <!-- HERO HEADER -->
    <header class="inv-hero" style="background: linear-gradient(135deg, #121a3e 0%, #2a3666 50%, #505984 100%); padding: 3rem 2.5rem; border-radius: 20px; color: white; margin-bottom: 2rem; box-shadow: 0 15px 35px rgba(18, 26, 62, 0.15); position: relative; overflow: hidden;">
        <div style="position: absolute; right: -40px; top: -40px; width: 220px; height: 220px; background: radial-gradient(circle, rgba(112,144,203,0.25) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; pointer-events: none;"></div>
        
        <div style="position: relative; z-index: 2; max-width: 800px;">
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.12); padding: 0.4rem 1rem; border-radius: 50px; font-size: 0.85rem; font-weight: 600; margin-bottom: 1rem; backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.2);">
                <i class="ph-bold ph-identification-badge" style="color: #7090cb;"></i> Directorio Académico I+D
            </div>
            <h1 style="font-size: 2.4rem; font-weight: 800; color: white; margin: 0 0 0.8rem 0; line-height: 1.2;">
                Investigadores y Docentes
            </h1>
            <p style="color: rgba(255,255,255,0.85); font-size: 1.1rem; line-height: 1.5; margin: 0;">
                Conoce al cuerpo docente e investigadores adscritos a las líneas de investigación institucionales y los proyectos científicos y tecnológicos bajo su responsabilidad.
            </p>
        </div>
    </header>

    <!-- BARRA DE BÚSQUEDA Y FILTRADO -->
    <div style="background: #ffffff; border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 1.2rem 1.5rem; margin-bottom: 2.5rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04);">
        <form method="GET" action="index.php" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; justify-content: space-between;">
            <input type="hidden" name="ruta" value="investigadores">

            <div style="display: flex; gap: 1rem; flex-wrap: wrap; flex: 1; min-width: 280px;">
                <!-- Buscador de texto -->
                <div style="position: relative; flex: 1; min-width: 220px;">
                    <i class="ph-bold ph-magnifying-glass" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 1.1rem;"></i>
                    <input 
                        type="text" 
                        name="q" 
                        value="<?= htmlspecialchars($busqueda) ?>" 
                        placeholder="Buscar por nombre o correo..." 
                        style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.6rem; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.95rem; outline: none; transition: border-color 0.2s;"
                        onfocus="this.style.borderColor='#505984';"
                        onblur="this.style.borderColor='#cbd5e1';"
                    >
                </div>

                <!-- Filtro por Línea de Investigación -->
                <div style="position: relative; min-width: 250px;">
                    <i class="ph-bold ph-tree-structure" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 1.1rem;"></i>
                    <select 
                        name="linea" 
                        style="width: 100%; padding: 0.75rem 2.2rem 0.75rem 2.6rem; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.95rem; outline: none; background: white; cursor: pointer; appearance: none;"
                        onchange="this.form.submit();"
                    >
                        <option value="0">Todas las Líneas de Investigación</option>
                        <?php if (!empty($lineas)): ?>
                            <?php foreach ($lineas as $l): ?>
                                <option value="<?= $l['id'] ?>" <?= ($id_linea === (int)$l['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($l['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <i class="ph-bold ph-caret-down" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #64748b; pointer-events: none;"></i>
                </div>
            </div>

            <div style="display: flex; gap: 0.8rem; align-items: center;">
                <button type="submit" style="background: linear-gradient(135deg, #121a3e 0%, #505984 100%); color: white; border: none; padding: 0.75rem 1.6rem; border-radius: 10px; font-weight: 700; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 4px 12px rgba(80,89,132,0.25);" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                    <i class="ph-bold ph-funnel"></i> Filtrar
                </button>

                <?php if ($busqueda !== '' || $id_linea > 0): ?>
                    <a href="?ruta=investigadores" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 0.75rem 1.2rem; border-radius: 10px; font-weight: 600; font-size: 0.95rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; transition: background 0.2s;" onmouseover="this.style.background='#e2e8f0';" onmouseout="this.style.background='#f1f5f9';">
                        <i class="ph-bold ph-x"></i> Limpiar
                    </a>
                <?php endif; ?>
            </div>
        </form>

        <div style="margin-top: 0.8rem; padding-top: 0.8rem; border-top: 1px dashed #e2e8f0; display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; color: #64748b;">
            <span>Mostrando <strong><?= $totalInvestigadores ?></strong> investigador(es) y docente(s) disponibles</span>
            <?php if ($id_linea > 0): ?>
                <?php 
                $lineaSelNombre = '';
                foreach ($lineas as $l) {
                    if ((int)$l['id'] === $id_linea) { $lineaSelNombre = $l['nombre']; break; }
                }
                ?>
                <span style="background: rgba(80,89,132,0.1); color: #505984; padding: 0.2rem 0.6rem; border-radius: 6px; font-weight: 600;">
                    Filtrado por: <?= htmlspecialchars($lineaSelNombre) ?>
                </span>
            <?php endif; ?>
        </div>
    </div>

    <!-- CUADRÍCULA DE INVESTIGADORES -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 1.8rem; margin-bottom: 4rem;">
        <?php if (empty($investigadores)): ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 5rem 2rem; background: #ffffff; border: 1px dashed #cbd5e1; border-radius: 16px;">
                <div style="width: 70px; height: 70px; border-radius: 50%; background: #f1f5f9; color: #94a3b8; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; margin: 0 auto 1.5rem;">
                    <i class="ph-bold ph-users-three"></i>
                </div>
                <h3 style="color: #1e293b; font-size: 1.4rem; font-weight: 700; margin: 0 0 0.5rem 0;">No se encontraron investigadores</h3>
                <p style="color: #64748b; font-size: 1rem; margin: 0 0 1.5rem 0;">No hay resultados con los criterios de búsqueda seleccionados.</p>
                <a href="?ruta=investigadores" style="background: #505984; color: white; padding: 0.6rem 1.4rem; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <i class="ph-bold ph-arrow-counter-clockwise"></i> Restablecer Directorio
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($investigadores as $inv): 
                $lineasAfiliadas = $inv['lineas'] ?? [];
                $investigacionesACargo = $inv['investigaciones'] ?? [];
                $iniciales = '';
                $partesNombre = explode(' ', trim($inv['nombre_completo']));
                foreach (array_slice($partesNombre, 0, 2) as $p) {
                    if (!empty($p)) $iniciales .= mb_substr($p, 0, 1, 'UTF-8');
                }
                $iniciales = strtoupper($iniciales ?: 'IN');
            ?>
            <div style="background: #ffffff; border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(18, 26, 62, 0.03); display: flex; flex-direction: column; transition: transform 0.25s, box-shadow 0.25s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 15px 35px rgba(80,89,132,0.12)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 25px rgba(18, 26, 62, 0.03)';">
                
                <!-- Encabezado de la Tarjeta -->
                <div style="padding: 1.8rem 1.8rem 1.2rem 1.8rem; border-bottom: 1px solid #f1f5f9; display: flex; gap: 1.2rem; align-items: center;">
                    <div style="width: 65px; height: 65px; min-width: 65px; border-radius: 16px; background: linear-gradient(135deg, #121a3e 0%, #505984 100%); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; font-weight: 800; box-shadow: 0 8px 18px rgba(80,89,132,0.25); border: 2px solid white;">
                        <?= $iniciales ?>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <h3 style="color: #121a3e; margin: 0 0 0.3rem 0; font-size: 1.2rem; font-weight: 800; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($inv['nombre_completo']) ?>">
                            <?= htmlspecialchars($inv['nombre_completo']) ?>
                        </h3>
                        
                        <div style="display: flex; gap: 0.4rem; flex-wrap: wrap; margin-bottom: 0.4rem;">
                            <span style="background: #e0e7ff; color: #3730a3; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 20px; text-transform: uppercase;">
                                <?= htmlspecialchars($inv['rol_nombre']) ?>
                            </span>
                            <?php if (!empty($inv['es_investigador'])): ?>
                                <span style="background: #ede9fe; color: #6d28d9; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 20px; display: inline-flex; align-items: center; gap: 0.3rem;">
                                    <i class="ph-bold ph-flask"></i> Investigador
                                </span>
                            <?php endif; ?>
                            <?php if (!empty($inv['es_profesor_pst'])): ?>
                                <span style="background: #dcfce7; color: #166534; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 20px;">
                                    Prof. PST
                                </span>
                            <?php endif; ?>
                        </div>

                        <div style="color: #64748b; font-size: 0.85rem; display: flex; align-items: center; gap: 0.4rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <i class="ph-fill ph-envelope-simple" style="color: #505984;"></i>
                            <a href="mailto:<?= htmlspecialchars($inv['email']) ?>" style="color: #64748b; text-decoration: none;" onmouseover="this.style.color='#505984';" onmouseout="this.style.color='#64748b;'">
                                <?= htmlspecialchars($inv['email'] ?: 'Sin correo registrado') ?>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Cuerpo de la Tarjeta: Líneas e Investigaciones -->
                <div style="padding: 1.5rem 1.8rem; flex: 1; display: flex; flex-direction: column; gap: 1.4rem;">
                    
                    <!-- Líneas Afiliadas -->
                    <div>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.6rem;">
                            <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: #505984; display: inline-flex; align-items: center; gap: 0.4rem;">
                                <i class="ph-bold ph-tree-structure" style="color: #505984; font-size: 1rem;"></i> Líneas de Investigación
                            </span>
                            <span style="font-size: 0.75rem; font-weight: 700; background: #f1f5f9; color: #475569; padding: 0.15rem 0.5rem; border-radius: 10px;">
                                <?= count($lineasAfiliadas) ?>
                            </span>
                        </div>

                        <?php if (empty($lineasAfiliadas)): ?>
                            <div style="font-size: 0.85rem; color: #94a3b8; font-style: italic; background: #f8fafc; padding: 0.6rem 0.8rem; border-radius: 8px; border: 1px dashed #e2e8f0;">
                                Sin líneas de investigación afiliadas actualmente.
                            </div>
                        <?php else: ?>
                            <div style="display: flex; flex-wrap: wrap; gap: 0.4rem;">
                                <?php foreach ($lineasAfiliadas as $la): ?>
                                    <a href="?ruta=detalle-linea&id=<?= $la['id'] ?>" style="background: rgba(80,89,132,0.08); color: #505984; font-size: 0.8rem; font-weight: 600; padding: 0.25rem 0.7rem; border-radius: 20px; border: 1px solid rgba(80,89,132,0.18); text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#505984'; this.style.color='#ffffff';" onmouseout="this.style.background='rgba(80,89,132,0.08)'; this.style.color='#505984';">
                                        <i class="ph-bold ph-git-branch" style="font-size: 0.75rem;"></i> <?= htmlspecialchars($la['nombre']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Proyectos / Investigaciones bajo su Responsabilidad -->
                    <div style="margin-top: auto;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.6rem;">
                            <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: #121a3e; display: inline-flex; align-items: center; gap: 0.4rem;">
                                <i class="ph-bold ph-folder-open" style="color: #7090cb; font-size: 1rem;"></i> Proyectos a su Cargo
                            </span>
                            <span style="font-size: 0.75rem; font-weight: 700; background: #e0f2fe; color: #0369a1; padding: 0.15rem 0.5rem; border-radius: 10px;">
                                <?= count($investigacionesACargo) ?>
                            </span>
                        </div>

                        <?php if (empty($investigacionesACargo)): ?>
                            <div style="font-size: 0.85rem; color: #64748b; background: #f8fafc; padding: 0.8rem; border-radius: 8px; border: 1px dashed #e2e8f0; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="ph-bold ph-check-circle" style="color: #10b981;"></i>
                                <span>Disponible para formular y liderar nuevos proyectos de I+D.</span>
                            </div>
                        <?php else: ?>
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <?php foreach (array_slice($investigacionesACargo, 0, 3) as $invProj): 
                                    $estColor = match($invProj['estado'] ?? '') {
                                        'Abierta' => ['bg' => '#dcfce7', 'fg' => '#15803d'],
                                        'En Desarrollo' => ['bg' => '#e0f2fe', 'fg' => '#0369a1'],
                                        'Finalizada' => ['bg' => '#f1f5f9', 'fg' => '#475569'],
                                        default => ['bg' => '#fef3c7', 'fg' => '#b45309'],
                                    };
                                ?>
                                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.6rem 0.8rem; display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                                        <div style="font-size: 0.85rem; font-weight: 600; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1;" title="<?= htmlspecialchars($invProj['titulo']) ?>">
                                            <?= htmlspecialchars($invProj['titulo']) ?>
                                        </div>
                                        <span style="background: <?= $estColor['bg'] ?>; color: <?= $estColor['fg'] ?>; font-size: 0.72rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 12px; white-space: nowrap;">
                                            <?= htmlspecialchars($invProj['estado'] ?? 'Activa') ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>

                                <?php if (count($investigacionesACargo) > 3): ?>
                                    <div style="font-size: 0.8rem; color: #505984; font-weight: 600; text-align: right;">
                                        + <?= count($investigacionesACargo) - 3 ?> proyecto(s) adicional(es)
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>

                <!-- Pie de Tarjeta: Resumen y Acciones -->
                <div style="padding: 1rem 1.8rem; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; gap: 0.8rem; flex-wrap: wrap;">
                    <div style="display: flex; gap: 1.2rem;">
                        <div>
                            <span style="font-size: 0.7rem; text-transform: uppercase; color: #94a3b8; font-weight: 700; display: block;">Líneas</span>
                            <strong style="color: #505984; font-size: 1.05rem;"><?= count($lineasAfiliadas) ?></strong>
                        </div>
                        <div style="border-left: 1px solid #e2e8f0; padding-left: 1.2rem;">
                            <span style="font-size: 0.7rem; text-transform: uppercase; color: #94a3b8; font-weight: 700; display: block;">Proyectos</span>
                            <strong style="color: #121a3e; font-size: 1.05rem;"><?= (int)$inv['total_investigaciones'] ?></strong>
                        </div>
                    </div>

                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <button type="button" onclick="abrirPerfilInvestigador(<?= (int)$inv['id'] ?>)" style="background: linear-gradient(135deg, #121a3e 0%, #505984 100%); color: #ffffff; border: none; padding: 0.5rem 0.9rem; border-radius: 8px; font-size: 0.82rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; transition: all 0.2s; box-shadow: 0 3px 10px rgba(80,89,132,0.22);" onmouseover="this.style.transform='translateY(-1px)';" onmouseout="this.style.transform='translateY(0)';">
                            <i class="ph-bold ph-identification-card"></i> Ver Perfil
                        </button>
                        <a href="?ruta=investigaciones&q=<?= urlencode($inv['nombre_completo']) ?>" style="background: #ffffff; color: #505984; border: 1px solid rgba(80,89,132,0.25); padding: 0.5rem 0.85rem; border-radius: 8px; font-size: 0.82rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; transition: all 0.2s;" onmouseover="this.style.background='#505984'; this.style.color='#ffffff';" onmouseout="this.style.background='#ffffff'; this.style.color='#505984';">
                            Proyectos <i class="ph-bold ph-arrow-right"></i>
                        </a>
                    </div>
                </div>

            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<!-- MODAL PÚBLICO DE FICHA COMPLETA DEL INVESTIGADOR -->
<div id="inv-perfil-overlay"
     style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.62); backdrop-filter:blur(5px); z-index:9998; opacity:0; transition:opacity 0.28s; align-items:center; justify-content:center; padding:1.5rem;"
     onclick="if(event.target===this) cerrarPerfilInvestigador();">
    <div id="inv-perfil-modal"
         style="background:#ffffff; width:min(720px, 100%); max-height:88vh; border-radius:20px; overflow:hidden; display:flex; flex-direction:column; box-shadow:0 25px 60px rgba(15,23,42,0.28); transform:translateY(20px) scale(0.98); transition:all 0.28s cubic-bezier(0.4,0,0.2,1);">
        <div id="inv-perfil-content" style="overflow-y:auto; flex:1;"></div>
    </div>
</div>

<script>
const investigadoresData = <?= json_encode(array_values($investigadores ?? []), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
const invMapById = {};
investigadoresData.forEach(item => { invMapById[parseInt(item.id)] = item; });

function escPerfil(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;').replace(/'/g,'&#039;');
}

function abrirPerfilInvestigador(id) {
    const inv = invMapById[parseInt(id)];
    if (!inv) return;

    const partes = String(inv.nombre_completo || '').trim().split(/\s+/).slice(0, 2);
    const iniciales = (partes.map(p => p.charAt(0)).join('') || 'IN').toUpperCase();

    const lineas = Array.isArray(inv.lineas) ? inv.lineas : [];
    const proyectos = Array.isArray(inv.investigaciones) ? inv.investigaciones : [];
    const trayectosPst = Array.isArray(inv.trayectos_pst) ? inv.trayectos_pst : [];

    let badgesHtml = `<span style="background:rgba(255,255,255,0.2); color:#ffffff; font-size:0.75rem; font-weight:700; padding:0.25rem 0.7rem; border-radius:20px; text-transform:uppercase; border:1px solid rgba(255,255,255,0.25);">${escPerfil(inv.rol_nombre)}</span>`;
    if (inv.es_investigador) {
        badgesHtml += `<span style="background:#ede9fe; color:#5b21b6; font-size:0.75rem; font-weight:700; padding:0.25rem 0.7rem; border-radius:20px; display:inline-flex; align-items:center; gap:0.3rem;"><i class="ph-bold ph-flask"></i> Investigador Acreditado</span>`;
    }
    if (inv.es_profesor_pst) {
        badgesHtml += `<span style="background:#dcfce7; color:#166534; font-size:0.75rem; font-weight:700; padding:0.25rem 0.7rem; border-radius:20px; display:inline-flex; align-items:center; gap:0.3rem;"><i class="ph-bold ph-chalkboard-teacher"></i> Docente PST</span>`;
    }

    let lineasHtml = '';
    if (lineas.length === 0) {
        lineasHtml = `<div style="font-size:0.88rem; color:#64748b; font-style:italic; background:#f8fafc; padding:0.8rem 1rem; border-radius:10px; border:1px dashed #cbd5e1;">Sin líneas de investigación adscritas actualmente.</div>`;
    } else {
        lineasHtml = `<div style="display:flex; flex-wrap:wrap; gap:0.5rem;">` +
            lineas.map(l => `
                <a href="?ruta=detalle-linea&id=${parseInt(l.id)}" style="background:rgba(80,89,132,0.09); color:#505984; font-size:0.85rem; font-weight:700; padding:0.4rem 0.9rem; border-radius:20px; border:1px solid rgba(80,89,132,0.22); text-decoration:none; display:inline-flex; align-items:center; gap:0.4rem;">
                    <i class="ph-bold ph-git-branch"></i> ${escPerfil(l.nombre)}
                </a>
            `).join('') + `</div>`;
    }

    let pstHtml = '';
    if (trayectosPst.length > 0) {
        pstHtml = `
            <div>
                <h4 style="font-size:0.82rem; font-weight:800; text-transform:uppercase; color:#166534; letter-spacing:0.5px; margin:0 0 0.6rem 0; display:flex; align-items:center; gap:0.4rem;">
                    <i class="ph-bold ph-graduation-cap"></i> Asignación Docente PST (Trayecto / Trimestre)
                </h4>
                <div style="display:flex; flex-wrap:wrap; gap:0.5rem;">
                    ${trayectosPst.map(tp => `
                        <span style="background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; font-size:0.8rem; font-weight:700; padding:0.3rem 0.75rem; border-radius:12px;">
                            Trayecto ${escPerfil(tp.trayecto)} &bull; Trimestre ${escPerfil(tp.trimestre)}
                        </span>
                    `).join('')}
                </div>
            </div>
        `;
    }

    let proyectosHtml = '';
    if (proyectos.length === 0) {
        proyectosHtml = `<div style="font-size:0.88rem; color:#64748b; background:#f8fafc; padding:1rem; border-radius:10px; border:1px dashed #cbd5e1; display:flex; align-items:center; gap:0.6rem;"><i class="ph-bold ph-check-circle" style="color:#10b981; font-size:1.2rem;"></i> Disponible para liderar y tutorizar nuevos proyectos de investigación.</div>`;
    } else {
        proyectosHtml = `<div style="display:flex; flex-direction:column; gap:0.75rem;">` +
            proyectos.map(p => {
                let badgeBg = '#fef3c7', badgeFg = '#b45309';
                if (p.estado === 'Abierta') { badgeBg = '#dcfce7'; badgeFg = '#15803d'; }
                else if (p.estado === 'En Desarrollo') { badgeBg = '#e0f2fe'; badgeFg = '#0369a1'; }
                else if (p.estado === 'Finalizada') { badgeBg = '#f1f5f9'; badgeFg = '#475569'; }
                const resumen = p.planteamiento_problema || p.objetivo_general || '';
                return `
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:1rem 1.2rem;">
                        <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:0.8rem; margin-bottom:0.4rem;">
                            <strong style="color:#121a3e; font-size:0.95rem; line-height:1.35;">${escPerfil(p.titulo)}</strong>
                            <span style="background:${badgeBg}; color:${badgeFg}; font-size:0.74rem; font-weight:700; padding:0.2rem 0.65rem; border-radius:12px; white-space:nowrap;">
                                ${escPerfil(p.estado || 'Activa')}
                            </span>
                        </div>
                        ${resumen ? `<p style="margin:0 0 0.6rem 0; font-size:0.84rem; color:#475569; line-height:1.5;">${escPerfil(resumen.substring(0, 180))}${resumen.length > 180 ? '...' : ''}</p>` : ''}
                        <div style="display:flex; align-items:center; justify-content:space-between; font-size:0.78rem; color:#64748b;">
                            <span><i class="ph-bold ph-users-three"></i> Cupos: <strong>${parseInt(p.cupos_disponibles) || 0}</strong></span>
                            <a href="?ruta=investigaciones&q=${encodeURIComponent(p.titulo)}" style="color:#505984; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:0.3rem;">
                                Ver en Cartelera <i class="ph-bold ph-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                `;
            }).join('') + `</div>`;
    }

    document.getElementById('inv-perfil-content').innerHTML = `
        <!-- Banner superior -->
        <div style="background:linear-gradient(135deg, #121a3e 0%, #2a3666 55%, #505984 100%); padding:2rem 2rem 1.8rem 2rem; color:white; position:relative;">
            <button type="button" onclick="cerrarPerfilInvestigador()"
                    style="position:absolute; right:1.2rem; top:1.2rem; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.25); color:white; width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:1rem;">
                <i class="ph-bold ph-x"></i>
            </button>
            <div style="display:flex; align-items:center; gap:1.3rem; flex-wrap:wrap;">
                <div style="width:76px; height:76px; border-radius:20px; background:rgba(255,255,255,0.15); border:2px solid rgba(255,255,255,0.4); display:flex; align-items:center; justify-content:center; font-size:1.7rem; font-weight:800; color:white; flex-shrink:0;">
                    ${iniciales}
                </div>
                <div style="flex:1; min-width:220px;">
                    <div style="display:flex; flex-wrap:wrap; gap:0.4rem; margin-bottom:0.5rem;">
                        ${badgesHtml}
                    </div>
                    <h2 style="margin:0 0 0.35rem 0; font-size:1.45rem; font-weight:800; color:white;">${escPerfil(inv.nombre_completo)}</h2>
                    <div style="font-size:0.9rem; color:rgba(255,255,255,0.85); display:flex; align-items:center; gap:0.4rem;">
                        <i class="ph-fill ph-envelope-simple"></i>
                        <a href="mailto:${escPerfil(inv.email)}" style="color:white; text-decoration:underline;">${escPerfil(inv.email || 'Sin correo registrado')}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cuerpo de la ficha pública -->
        <div style="padding:1.8rem 2rem; display:flex; flex-direction:column; gap:1.5rem;">
            <div>
                <h4 style="font-size:0.82rem; font-weight:800; text-transform:uppercase; color:#505984; letter-spacing:0.5px; margin:0 0 0.6rem 0; display:flex; align-items:center; gap:0.4rem;">
                    <i class="ph-bold ph-tree-structure"></i> Líneas de Investigación Adscritas (${lineas.length})
                </h4>
                ${lineasHtml}
            </div>

            ${pstHtml}

            <div>
                <h4 style="font-size:0.82rem; font-weight:800; text-transform:uppercase; color:#121a3e; letter-spacing:0.5px; margin:0 0 0.6rem 0; display:flex; align-items:center; gap:0.4rem;">
                    <i class="ph-bold ph-folder-open"></i> Proyectos de Investigación a su Cargo (${proyectos.length})
                </h4>
                ${proyectosHtml}
            </div>
        </div>

        <!-- Footer -->
        <div style="padding:1rem 2rem; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.8rem;">
            <a href="?ruta=investigaciones&q=${encodeURIComponent(inv.nombre_completo)}" style="background:#505984; color:white; padding:0.6rem 1.2rem; border-radius:10px; font-weight:700; font-size:0.88rem; text-decoration:none; display:inline-flex; align-items:center; gap:0.4rem;">
                <i class="ph-bold ph-rocket-launch"></i> Ver todos sus proyectos en Cartelera
            </a>
            <button type="button" onclick="cerrarPerfilInvestigador()" style="background:#e2e8f0; color:#334155; border:none; padding:0.6rem 1.2rem; border-radius:10px; font-weight:700; font-size:0.88rem; cursor:pointer;">
                Cerrar Ficha
            </button>
        </div>
    `;

    const overlay = document.getElementById('inv-perfil-overlay');
    const modal = document.getElementById('inv-perfil-modal');
    overlay.style.display = 'flex';
    requestAnimationFrame(() => {
        overlay.style.opacity = '1';
        modal.style.transform = 'translateY(0) scale(1)';
    });
    document.body.style.overflow = 'hidden';
}

function cerrarPerfilInvestigador() {
    const overlay = document.getElementById('inv-perfil-overlay');
    const modal = document.getElementById('inv-perfil-modal');
    overlay.style.opacity = '0';
    modal.style.transform = 'translateY(20px) scale(0.98)';
    setTimeout(() => {
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    }, 280);
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarPerfilInvestigador(); });

document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const verId = parseInt(params.get('ver_id') || '0');
    if (verId > 0 && invMapById[verId]) {
        abrirPerfilInvestigador(verId);
    } else if (params.get('ver_perfil') === '1' && investigadoresData.length === 1) {
        abrirPerfilInvestigador(investigadoresData[0].id);
    }
});
</script>
