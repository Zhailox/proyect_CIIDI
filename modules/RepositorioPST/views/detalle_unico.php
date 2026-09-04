<div class="main-content">
    <?php if (!$documento): ?>
        <div class="no-results-card" style="max-width: 500px; margin: 2rem auto; text-align: center; border: 1px solid rgba(169,168,166,0.2); padding: 2rem; border-radius: 6px; background-color: var(--bg-card);">
            <i class="ph ph-warning-circle" style="font-size: 2.5rem; color: #e53e3e; margin-bottom: 0.75rem; display: block;"></i>
            <h3 style="margin-bottom: 0.5rem; font-size: 1.1rem; font-weight: 700;">Proyecto no encontrado</h3>
            <p style="font-size: 0.85rem; color: var(--texto-silenciado); margin-bottom: 1.25rem;">El Proyecto Socio-Tecnológico solicitado no existe en el sistema o fue removido.</p>
            <a href="?ruta=repositorio" class="btn-back">Volver al Repositorio</a>
        </div>
    <?php else: ?>
        <div class="pst-detail-view">
            
            <div class="pst-detail-header">
                <div style="display: flex; gap: 0.4rem; margin-bottom: 0.4rem; flex-wrap: wrap; align-items: center;">
                    <span class="pst-badge-soft" style="background-color: rgba(112, 144, 203, 0.15); color: var(--color-secundario); font-weight: 700;"><?= htmlspecialchars($documento['nivel_academico'] ?? 'Pregrado') ?></span>
                    <?php if (($documento['nivel_academico'] ?? 'Pregrado') === 'Pregrado' && !empty($documento['trayecto'])): ?>
                        <span class="pst-badge-soft" style="background-color: rgba(0, 123, 255, 0.1); color: var(--color-terciario); font-weight: 700;"><?= htmlspecialchars($documento['trayecto']) ?></span>
                    <?php endif; ?>
                    <span class="pst-badge-soft" style="background-color: #f1f5f9; color: var(--texto-silenciado);">AÑO <?= $documento['anio_publicacion'] ?></span>
                    <span class="pst-badge-soft" style="background-color: #f1f5f9; color: var(--texto-silenciado);">PNF Informática</span>
                    <?php if (!empty($documento['url_repositorio']) && ConfigService::get('recursos.mostrar_url_git', true)): ?>
                        <a href="<?= htmlspecialchars($documento['url_repositorio']) ?>" target="_blank" class="pst-badge-soft" style="background-color: #002244; color: #ffffff; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem; max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <i class="ph ph-git-branch"></i> Repositorio Git
                        </a>
                    <?php endif; ?>
                </div>
                <h1 style="margin-bottom: 0.75rem;"><?= htmlspecialchars($documento['titulo'] ?? '') ?></h1>

                <!-- BLOQUE UNIFICADO 1: CAPITAL HUMANO (Autores/Estudiantes y Tutores Asesores) -->
                <div style="background-color: #f8fafc; padding: 0.85rem 1rem; border-radius: 6px; border: 1px solid rgba(169, 168, 166, 0.25); margin-top: 0.75rem;">
                    <h3 style="font-size: 0.88rem; font-weight: 800; color: var(--color-secundario); margin: 0 0 0.6rem 0; display: flex; align-items: center; gap: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="ph ph-users-three" style="color: var(--color-terciario); font-size: 1.1rem;"></i> Equipo de Trabajo & Asesoría Académica
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 0.75rem;">
                        <!-- Estudiantes Autores -->
                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 5px; padding: 0.6rem 0.8rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                            <strong style="color: var(--texto-silenciado); font-size: 0.7rem; text-transform: uppercase; display: block; margin-bottom: 0.3rem; letter-spacing: 0.5px;">
                                <i class="ph ph-student" style="color: var(--color-terciario);"></i> Estudiantes (Autores):
                            </strong>
                            <span style="font-size: 0.85rem; font-weight: 700; color: var(--texto-titulos); line-height: 1.4; display: block;">
                                <?= htmlspecialchars($documento['autores_nombres'] ?? 'No registrados') ?>
                            </span>
                        </div>
                        
                        <!-- Tutores Asesores -->
                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 5px; padding: 0.6rem 0.8rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                            <strong style="color: var(--texto-silenciado); font-size: 0.7rem; text-transform: uppercase; display: block; margin-bottom: 0.3rem; letter-spacing: 0.5px;">
                                <i class="ph ph-chalkboard-teacher" style="color: var(--color-terciario);"></i> Tutores Asesores:
                            </strong>
                            <?php if (!empty($documento['tutores_lista'])): ?>
                                <div style="display: flex; gap: 0.35rem; flex-wrap: wrap;">
                                    <?php foreach ($documento['tutores_lista'] as $tut): ?>
                                        <div style="background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px; padding: 0.25rem 0.5rem; font-size: 0.76rem; display: inline-flex; align-items: center; gap: 0.3rem;">
                                            <span style="font-weight: 700; color: var(--color-secundario); background: rgba(112, 144, 203, 0.15); padding: 0.08rem 0.3rem; border-radius: 3px; font-size: 0.68rem;">
                                                <?= htmlspecialchars($tut['tipo_nombre'] ?? '') ?>
                                            </span>
                                            <span style="font-weight: 600; color: var(--texto-normal);"><?= htmlspecialchars($tut['nombre_completo'] ?? '') ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <span style="font-size: 0.82rem; color: var(--texto-silenciado); font-style: italic;">
                                    <?= htmlspecialchars($documento['tutores_nombres'] ?? 'No registrados') ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BLOQUE UNIFICADO 2: FICHA TÉCNICA DEL PROYECTO -->
            <div class="pst-detail-meta-grid">
                
                <div class="pst-meta-item">
                    <strong>Nivel Académico / Trayecto</strong>
                    <span><?= htmlspecialchars($documento['nivel_academico'] ?? 'Pregrado') ?><?= (($documento['nivel_academico'] ?? 'Pregrado') === 'Pregrado' && !empty($documento['trayecto'])) ? ' • ' . htmlspecialchars($documento['trayecto']) : '' ?></span>
                </div>

                <div class="pst-meta-item">
                    <strong>Comunidad / Ente Beneficiario</strong>
                    <span>
                        <?php if (!empty($documento['comunidad_beneficiada'])): ?>
                            <span style="font-weight: 700; color: var(--texto-titulos);">
                                <?= htmlspecialchars($documento['comunidad_beneficiada']) ?>
                            </span>
                            <?php if ($conteoComunidad > 0): ?>
                                <button type="button" onclick="abrirModalComunidad()" style="margin-left: 0.35rem; border: none; background: rgba(0, 123, 255, 0.1); color: var(--color-terciario); font-size: 0.72rem; font-weight: 800; padding: 0.18rem 0.5rem; border-radius: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.25rem; transition: background 0.2s;" title="Ver otros proyectos en esta comunidad">
                                    <i class="ph ph-buildings"></i> <?= $conteoComunidad ?> coincidencia<?= $conteoComunidad > 1 ? 's' : '' ?>
                                </button>
                            <?php endif; ?>
                        <?php else: ?>
                            <em style="color: var(--texto-silenciado);">No registrada</em>
                        <?php endif; ?>
                    </span>
                </div>

                <div class="pst-meta-item">
                    <strong>Línea de Investigación</strong>
                    <span><?= htmlspecialchars($documento['linea_nombre'] ?? 'General') ?></span>
                </div>

                <div class="pst-meta-item">
                    <strong>Dimensión Operativa</strong>
                    <span><?= htmlspecialchars($documento['dimension_nombre'] ?? 'Sin dimensión asociada') ?></span>
                </div>

                <?php if (ConfigService::get('recursos.mostrar_url_git', true)): ?>
                <div class="pst-meta-item">
                    <strong>Código Fuente (Git)</strong>
                    <span>
                        <?php if (!empty($documento['url_repositorio'])): ?>
                            <a href="<?= htmlspecialchars($documento['url_repositorio']) ?>" target="_blank" style="color: var(--color-terciario); text-decoration: underline;">
                                <?= htmlspecialchars($documento['url_repositorio']) ?>
                            </a>
                        <?php else: ?>
                            <em style="color: var(--texto-silenciado);">No proporcionado</em>
                        <?php endif; ?>
                    </span>
                </div>
                <?php endif; ?>

            </div>

            <!-- TABS DE NAVEGACIÓN DE LA FICHA -->
            <div class="pst-detail-tabs">
                <button type="button" class="tab-btn active" onclick="switchDetailTab('tabResumen', this)">
                    <i class="ph ph-book-open"></i> Matriz Epistémica & Resumen
                </button>
                <button type="button" class="tab-btn" onclick="switchDetailTab('tabVisorPdf', this)">
                    <i class="ph ph-file-pdf"></i> Visor de Documento Digital
                </button>
            </div>

            <!-- TAB 1: RESUMEN Y PALABRAS CLAVE -->
            <div id="tabResumen" class="tab-content active">
                <?php if (!empty($documento['obj_general'])): ?>
                    <div style="margin-bottom: 1.2rem; background-color: #f8fafc; border-left: 4px solid var(--color-terciario, #007bff); padding: 0.85rem 1rem; border-radius: 0 6px 6px 0;">
                        <h4 style="font-size: 0.88rem; font-weight: 800; color: var(--texto-titulos, #002244); margin: 0 0 0.35rem 0; display: flex; align-items: center; gap: 0.4rem;">
                            <i class="ph ph-target" style="color: var(--color-terciario, #007bff);"></i> Objetivo General de la Investigación
                        </h4>
                        <p style="margin: 0; font-size: 0.9rem; line-height: 1.5; color: var(--texto-normal, #333333); font-weight: 500;">
                            <?= htmlspecialchars($documento['obj_general']) ?>
                        </p>
                    </div>
                <?php endif; ?>

                <div class="pst-detail-abstract">
                    <p>
                        <?= nl2br(htmlspecialchars($documento['resumen'] ?? 'No se ha cargado un resumen o matriz epistémica para esta investigación en el sistema.')) ?>
                    </p>
                </div>

                <?php if (!empty($documento['palabras_clave'])): ?>
                    <div style="margin-top: 0.85rem; padding-top: 0.5rem; border-top: 1px dashed rgba(169, 168, 166, 0.2); font-size: 0.82rem;">
                        <strong>Palabras Clave:</strong>
                        <span style="font-style: italic; color: var(--texto-silenciado);"><?= htmlspecialchars($documento['palabras_clave'] ?? '') ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- TAB 2: VISOR PDF EMBEBIDO -->
            <div id="tabVisorPdf" class="tab-content">
                <div style="background-color: #f8fafc; border: 1px solid rgba(169, 168, 166, 0.2); border-radius: 6px; padding: 0.5rem; text-align: center;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; padding: 0 0.25rem;">
                        <span style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos);">
                            <i class="ph ph-file-text"></i> Previsualización Oficial del Documento Digital (Lectura en Ficha)
                        </span>
                    </div>
                    <iframe src="?ruta=ver-pdf-pst&id=<?= $documento['id'] ?>#toolbar=0&navpanes=0" style="width: 100%; height: 580px; border: 1px solid rgba(169,168,166,0.3); border-radius: 4px; background: white;" title="Visor PDF"></iframe>
                </div>
            </div>

            <!-- BOTONES INFERIORES DE ACCIÓN -->
            <div class="pst-detail-actions">
                <a href="?ruta=repositorio" class="btn-back">
                    <i class="ph ph-arrow-left"></i> Volver al Catálogo
                </a>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="button" class="btn-back" style="background-color: #fff1f2; color: #be123c; border: 1px solid rgba(190, 18, 60, 0.2);" onclick="abrirModalCita(<?= htmlspecialchars(json_encode($documento['titulo'])) ?>, <?= htmlspecialchars(json_encode($documento['autores_nombres'] ?? 'Autores Varios')) ?>, <?= $documento['anio_publicacion'] ?>)">
                        <i class="ph ph-quotes"></i> Generar Cita Académica
                    </button>
                </div>
            </div>

            <!-- SECCIÓN DE PROYECTOS SIMILARES REALES -->
            <?php if (!empty($proyectosSimilares)): ?>
                <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(169, 168, 166, 0.2);">
                    <h3 style="font-size: 0.95rem; font-weight: 800; color: var(--texto-titulos); margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.4rem;">
                        <i class="ph ph-git-fork" style="color: var(--color-terciario);"></i> Investigaciones Afines en la misma Línea (<?= htmlspecialchars($documento['linea_nombre'] ?? 'General') ?>)
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 0.75rem;">
                        <?php foreach ($proyectosSimilares as $sim): ?>
                            <div style="background: #fafbfe; border: 1px solid rgba(169, 168, 166, 0.2); border-radius: 5px; padding: 0.65rem 0.85rem; display: flex; flex-direction: column; justify-content: space-between;">
                                <div>
                                    <span style="font-size: 0.7rem; font-weight: 700; color: var(--color-secundario);">AÑO <?= $sim['anio_publicacion'] ?></span>
                                    <h4 style="font-size: 0.82rem; font-weight: 700; color: var(--texto-titulos); margin: 0.2rem 0 0.35rem 0; line-height: 1.3;">
                                        <a href="?ruta=detalles-pst&id=<?= $sim['id'] ?>" style="color: inherit; text-decoration: none;">
                                            <?= htmlspecialchars($sim['titulo']) ?>
                                        </a>
                                    </h4>
                                    <p style="font-size: 0.75rem; color: var(--texto-silenciado); margin: 0; line-height: 1.3;">
                                        <?= htmlspecialchars(substr($sim['resumen'] ?? '', 0, 95)) ?>...
                                    </p>
                                </div>
                                <div style="margin-top: 0.5rem;">
                                    <a href="?ruta=detalles-pst&id=<?= $sim['id'] ?>" style="font-size: 0.75rem; color: var(--color-terciario); font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.2rem;">
                                        Ver Ficha <i class="ph ph-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    <?php endif; ?>
</div>

<script>
function switchDetailTab(tabId, btn) {
    document.querySelectorAll('.tab-content').forEach(tc => tc.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(tb => tb.classList.remove('active'));
    
    document.getElementById(tabId).classList.add('active');
    btn.classList.add('active');
}

// MODAL DE CITAS ACADÉMICAS DINÁMICAS (DESDE CONFIGURACIÓN JSON)
const configuracionesCitas = <?= json_encode(ConfigService::get('citas.estilos', [])) ?>;

function abrirModalCita(titulo, autores, anio) {
    const listEl = document.getElementById('listaCitasDinamicas');
    if (!listEl) return;
    
    listEl.innerHTML = '';
    
    const mockData = {
        '{autores}': autores || 'Autores Varios',
        '{anio}': anio || 's.f.',
        '{titulo}': titulo || 'Proyecto Socio-Tecnológico',
        '{carrera}': 'PNF en Informática'
    };
    
    let count = 0;
    for (const [slug, item] of Object.entries(configuracionesCitas)) {
        if (!item.activo) continue;
        count++;
        
        let textoCita = item.plantilla || '';
        for (const [k, v] of Object.entries(mockData)) {
            textoCita = textoCita.replaceAll(k, v);
        }
        
        const boxId = 'cita_txt_' + slug;
        const cardHtml = `
            <div style="margin-bottom: 0.85rem;">
                <strong style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: var(--color-secundario); text-transform: uppercase; margin-bottom: 0.25rem;">
                    <span>${item.nombre || slug}</span>
                    <button type="button" onclick="copiarCitaText('${boxId}', this)" style="padding: 0.2rem 0.5rem; font-size: 0.7rem; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 3px; cursor: pointer; font-weight: 700;">
                        <i class="ph ph-copy"></i> Copiar
                    </button>
                </strong>
                <div id="${boxId}" style="background: #fafbfe; border: 1px solid rgba(169, 168, 166, 0.15); padding: 0.5rem 0.65rem; border-radius: 4px; font-size: 0.8rem; color: var(--texto-normal); font-family: monospace; line-height: 1.4;">${textoCita}</div>
            </div>
        `;
        listEl.insertAdjacentHTML('beforeend', cardHtml);
    }
    
    if (count === 0) {
        listEl.innerHTML = '<p style="font-size: 0.8rem; color: var(--texto-silenciado);">No hay formatos de cita activos en la configuración.</p>';
    }
    
    document.getElementById('modalCitasContainer').style.display = 'flex';
}

function cerrarModalCitas() {
    document.getElementById('modalCitasContainer').style.display = 'none';
}

function copiarCitaText(elementId, btn) {
    const el = document.getElementById(elementId);
    if (!el) return;
    const text = el.textContent;

    const mostrarExito = () => {
        const origText = btn.innerHTML;
        btn.innerHTML = '<i class="ph ph-check"></i> ¡Copiado!';
        setTimeout(() => { btn.innerHTML = origText; }, 2000);
    };

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(mostrarExito).catch(() => {
            fallbackCopiarTexto(text, mostrarExito);
        });
    } else {
        fallbackCopiarTexto(text, mostrarExito);
    }
}

function fallbackCopiarTexto(text, onSuccess) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.left = "-999999px";
    textArea.style.top = "-999999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        const successful = document.execCommand('copy');
        if (successful && typeof onSuccess === 'function') {
            onSuccess();
        }
    } catch (err) {
        console.error('Error al copiar texto via fallback: ', err);
    }
    document.body.removeChild(textArea);
}
function abrirModalComunidad() {
    const modal = document.getElementById('modalComunidadContainer');
    if (modal) modal.style.display = 'flex';
}

function cerrarModalComunidad() {
    const modal = document.getElementById('modalComunidadContainer');
    if (modal) modal.style.display = 'none';
}
</script>

<!-- Modal Citas Académicas -->
<div id="modalCitasContainer" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 34, 68, 0.7); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center;">
    <div style="background: var(--bg-card, #ffffff); border: 1px solid rgba(169, 168, 166, 0.2); border-radius: 8px; width: 90%; max-width: 540px; padding: 1.25rem; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(169, 168, 166, 0.2); padding-bottom: 0.5rem; margin-bottom: 1rem;">
            <h3 style="font-size: 1rem; font-weight: 800; color: var(--texto-titulos); margin: 0; display: flex; align-items: center; gap: 0.35rem;">
                <i class="ph ph-quotes" style="color: var(--color-terciario);"></i> Cita Académica Formal
            </h3>
            <button type="button" onclick="cerrarModalCitas()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: var(--texto-silenciado);">&times;</button>
        </div>
        
        <div id="listaCitasDinamicas" style="max-height: 380px; overflow-y: auto;">
            <!-- Contenido inyectado dinámicamente -->
        </div>

        <div style="text-align: right; border-top: 1px solid rgba(169, 168, 166, 0.15); padding-top: 0.5rem;">
            <button type="button" onclick="cerrarModalCitas()" class="btn-back" style="display: inline-block; width: auto; padding: 0.35rem 1rem;">Cerrar</button>
        </div>
    </div>
</div>

<!-- Modal Coincidencias de Comunidad Beneficiada -->
<?php if (!empty($proyectosComunidad)): ?>
<div id="modalComunidadContainer" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 34, 68, 0.7); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center;">
    <div style="background: var(--bg-card, #ffffff); border: 1px solid rgba(169, 168, 166, 0.2); border-radius: 8px; width: 90%; max-width: 620px; padding: 1.25rem; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(169, 168, 166, 0.2); padding-bottom: 0.5rem; margin-bottom: 0.85rem;">
            <h3 style="font-size: 0.95rem; font-weight: 800; color: var(--texto-titulos); margin: 0; display: flex; align-items: center; gap: 0.4rem;">
                <i class="ph ph-buildings" style="color: var(--color-terciario);"></i> Proyectos Desarrollados en "<?= htmlspecialchars($documento['comunidad_beneficiada']) ?>"
            </h3>
            <button type="button" onclick="cerrarModalComunidad()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: var(--texto-silenciado);">&times;</button>
        </div>
        
        <p style="font-size: 0.78rem; color: var(--texto-silenciado); margin-bottom: 0.75rem;">
            Se encontraron <strong><?= count($proyectosComunidad) ?></strong> investigación(es) adicional(es) realizada(s) en esta misma comunidad o institución:
        </p>

        <div style="display: flex; flex-direction: column; gap: 0.5rem; max-height: 340px; overflow-y: auto; padding-right: 0.25rem;">
            <?php foreach ($proyectosComunidad as $pc): ?>
                <div style="background: #fafbfe; border: 1px solid rgba(169, 168, 166, 0.2); border-radius: 5px; padding: 0.6rem 0.8rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.2rem;">
                        <span style="font-size: 0.7rem; font-weight: 700; color: var(--color-secundario);">AÑO <?= $pc['anio_publicacion'] ?> • <?= htmlspecialchars($pc['linea_nombre'] ?? 'General') ?></span>
                        <a href="?ruta=detalles-pst&id=<?= $pc['id'] ?>" style="font-size: 0.72rem; color: var(--color-terciario); font-weight: 700; text-decoration: none;">
                            Ver Ficha <i class="ph ph-arrow-right"></i>
                        </a>
                    </div>
                    <h4 style="font-size: 0.82rem; font-weight: 700; color: var(--texto-titulos); margin: 0 0 0.25rem 0;">
                        <a href="?ruta=detalles-pst&id=<?= $pc['id'] ?>" style="color: inherit; text-decoration: none;">
                            <?= htmlspecialchars($pc['titulo']) ?>
                        </a>
                    </h4>
                    <?php if (!empty($pc['resumen'])): ?>
                        <p style="font-size: 0.75rem; color: var(--texto-silenciado); margin: 0; line-height: 1.3;">
                            <?= htmlspecialchars(substr($pc['resumen'], 0, 110)) ?>...
                        </p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="text-align: right; border-top: 1px solid rgba(169, 168, 166, 0.15); padding-top: 0.5rem; margin-top: 0.85rem;">
            <button type="button" onclick="cerrarModalComunidad()" class="btn-back" style="display: inline-block; width: auto; padding: 0.35rem 1rem;">Cerrar</button>
        </div>
    </div>
</div>
<?php endif; ?>

