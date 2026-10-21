<?php
// modules/Cursos/views/vista_formulario_google.php
// Vista dedicada y separada para el Formulario de Google de Cursos
$curso        = $curso ?? null;
$cursos       = $cursos ?? [];
$id_curso     = $id_curso ?? ($curso['id'] ?? 0);
$url_form     = $url_form ?? ($config_vista['formulario_google']['url_default'] ?? '');
$modo         = $modo ?? ($config_vista['formulario_google']['modo'] ?? 'embebido');
$titulo_form  = $titulo_form ?? ($config_vista['formulario_google']['titulo'] ?? 'Formulario Oficial de Inscripción');

// Asegurar formato embebible y limpio para Google Forms si es URL estándar
$url_embed = $url_form;
if (strpos($url_embed, 'docs.google.com/forms') !== false) {
    // Si viene con /edit o con parámetros como ?usp=..., normalizar a /viewform
    $base_form = preg_replace('/(\/viewform|\/edit).*$/', '/viewform', $url_embed);
    $url_embed = $base_form . '?embedded=true';
    if (strpos($url_form, 'usp=') !== false || strpos($url_form, '/edit') !== false) {
        $url_form = $base_form;
    }
}
?>

<div class="cur-wrapper" style="max-width: 1200px; margin: 0 auto; padding: 1.5rem 1rem 3rem 1rem;">

    <!-- BREADCRUMBS Y RETORNO -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; font-weight: 600; color: var(--cur-muted);">
            <a href="?ruta=cursos" style="color: var(--cur-primary); text-decoration: none; display: inline-flex; align-items: center; gap: 0.3rem;">
                <i class="ph-bold ph-graduation-cap"></i> Oferta de Cursos
            </a>
            <span style="color: #CBD5E1;">/</span>
            <span style="color: var(--cur-dark);">Inscripción Oficial (Google Form)</span>
        </div>
        <a href="?ruta=cursos" class="cur-btn-secondary" style="border: 1px solid var(--cur-border); padding: 0.5rem 1.2rem; border-radius: 8px; color: var(--cur-dark); text-decoration: none; font-weight: 600; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.4rem; background: #fff; transition: all 0.2s;" onmouseover="this.style.background='#F8FAFC';" onmouseout="this.style.background='#fff';">
            <i class="ph-bold ph-arrow-left"></i> Volver a Cursos
        </a>
    </div>

    <!-- HERO DE LA VISTA SEPARADA -->
    <div style="background: linear-gradient(135deg, rgba(80,89,132,0.97) 0%, rgba(112,144,203,0.92) 100%); border-radius: 20px; padding: 2.2rem; color: white; margin-bottom: 2rem; box-shadow: 0 15px 35px rgba(80,89,132,0.22); position: relative; overflow: hidden;">
        <div style="position: relative; z-index: 2; max-width: 800px;">
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.18); border: 1px solid rgba(255,255,255,0.3); padding: 0.35rem 0.9rem; border-radius: 50px; font-size: 0.82rem; font-weight: 700; margin-bottom: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                <i class="ph-bold ph-google-logo" style="color: #FFD166;"></i> Google Forms Institucional
            </div>
            <h1 style="font-size: 2.2rem; font-weight: 800; margin: 0 0 0.6rem 0; color: #fff;">
                <?= htmlspecialchars($titulo_form) ?>
            </h1>
            <p style="margin: 0; font-size: 1.05rem; color: #E0E7FF; line-height: 1.5;">
                Formaliza tu postulación completando el formulario oficial. Tus datos serán remitidos al departamento de formación del CIIDI.
            </p>
        </div>
        <div style="position: absolute; right: 2rem; bottom: -20px; font-size: 9rem; color: rgba(255,255,255,0.06); pointer-events: none;">
            <i class="ph-fill ph-clipboard-text"></i>
        </div>
    </div>

    <!-- RESUMEN DEL CURSO SELECCIONADO (SI APLICA) -->
    <div style="background: #fff; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.5rem 1.8rem; margin-bottom: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div style="flex: 1; min-width: 280px;">
                <?php if ($curso): ?>
                    <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #64748B; letter-spacing: 0.5px;">Curso Seleccionado</span>
                    <h2 style="font-size: 1.35rem; font-weight: 800; color: #0F172A; margin: 0.2rem 0 0.5rem 0;">
                        <?= htmlspecialchars($curso['titulo']) ?>
                    </h2>
                    
                    <div style="display: flex; gap: 0.8rem; flex-wrap: wrap; align-items: center; font-size: 0.85rem; color: #475569;">
                        <span><i class="ph-bold ph-chalkboard-teacher" style="color: #505984;"></i> <?= htmlspecialchars($curso['nombre_docente'] ?? 'Docente Asignado') ?></span>
                        <span>&bull;</span>
                        <span><i class="ph-bold ph-monitor" style="color: #505984;"></i> <?= htmlspecialchars($curso['modalidad'] ?? 'Virtual') ?></span>
                        <span>&bull;</span>
                        <span><i class="ph-bold ph-clock" style="color: #505984;"></i> <?= htmlspecialchars($curso['duracion'] ?? 'A convenir') ?></span>
                    </div>

                    <!-- LÍNEAS Y DIMENSIONES EN EL DETALLE -->
                    <?php if (!empty($curso['lineas']) || !empty($curso['dimensiones'])): ?>
                    <div style="margin-top: 0.8rem; display: flex; flex-wrap: wrap; gap: 0.4rem; align-items: center;">
                        <span style="font-size: 0.75rem; font-weight: 700; color: #64748B; margin-right: 0.2rem;">Opera en:</span>
                        <?php foreach (($curso['lineas'] ?? []) as $lin): ?>
                            <span style="background: rgba(80,89,132,0.1); color: #505984; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 50px; display: inline-flex; align-items: center; gap: 0.3rem;">
                                <i class="ph-bold ph-git-branch"></i> <?= htmlspecialchars($lin['nombre']) ?>
                            </span>
                        <?php endforeach; ?>
                        <?php foreach (($curso['dimensiones'] ?? []) as $dim): ?>
                            <span style="background: #F1F5F9; color: #475569; font-size: 0.75rem; font-weight: 600; padding: 0.2rem 0.6rem; border-radius: 50px; display: inline-flex; align-items: center; gap: 0.3rem;">
                                <i class="ph-bold ph-circles-three"></i> <?= htmlspecialchars($dim['nombre']) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                <?php else: ?>
                    <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #64748B;">Inscripción Abierta</span>
                    <h2 style="font-size: 1.25rem; font-weight: 800; color: #0F172A; margin: 0.2rem 0 0.2rem 0;">
                        Convocatoria General de Formación Continua
                    </h2>
                    <p style="margin: 0; font-size: 0.9rem; color: #64748B;">Indica en el formulario el programa o diplomado al que deseas postularte.</p>
                <?php endif; ?>
            </div>

            <!-- CAMBIAR CURSO -->
            <?php if (!empty($cursos)): ?>
            <div style="min-width: 260px;">
                <label for="selector_curso" style="display: block; font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 0.3rem;">
                    Cambiar de curso:
                </label>
                <select id="selector_curso" onchange="if(this.value){ window.location.href='?ruta=cursos-formulario-google&curso_id='+this.value; }else{ window.location.href='?ruta=cursos-formulario-google'; }" style="width: 100%; padding: 0.6rem 0.8rem; border-radius: 8px; border: 1px solid #CBD5E1; font-size: 0.88rem; outline: none; background: #F8FAFC;">
                    <option value="">-- General / Sin especificar --</option>
                    <?php foreach ($cursos as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($id_curso == $c['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['titulo']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- BARRA DE ACCIÓN DEL FORMULARIO DE GOOGLE -->
    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; align-items: center; gap: 0.8rem;">
            <div style="width: 36px; height: 36px; border-radius: 8px; background: #FEF2F2; color: #DC2626; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                <i class="ph-bold ph-info"></i>
            </div>
            <div>
                <strong style="display: block; color: #1E293B; font-size: 0.95rem;">Instrucciones para el participante</strong>
                <span style="color: #64748B; font-size: 0.83rem;">Llena todos los campos requeridos en el formulario a continuación.</span>
            </div>
        </div>

        <div style="display: flex; gap: 0.6rem;">
            <a href="<?= htmlspecialchars($url_form) ?>" target="_blank" rel="noopener noreferrer" style="background: #505984; color: #fff; padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 0.88rem; display: inline-flex; align-items: center; gap: 0.4rem; transition: background 0.2s;" onmouseover="this.style.background='#3C456A';" onmouseout="this.style.background='#505984';">
                <i class="ph-bold ph-arrow-square-out"></i> Abrir a Pantalla Completa
            </a>
            <?php if ($curso): ?>
            <a href="?ruta=cursos-detalle&slug=<?= htmlspecialchars($curso['slug'] ?? $curso['id']) ?>" style="background: #E2E8F0; color: #334155; padding: 0.6rem 1rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.88rem; display: inline-flex; align-items: center; gap: 0.4rem;">
                <i class="ph-bold ph-eye"></i> Ficha del Curso
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- CONTENEDOR EMBEBIDO DEL FORMULARIO DE GOOGLE -->
    <div style="background: #fff; border: 1px solid #E2E8F0; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.06); position: relative; min-height: 850px;">
        <div id="form-loader" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; background: rgba(255,255,255,0.9); z-index: 10;">
            <div style="width: 48px; height: 48px; border: 4px solid #E2E8F0; border-top-color: #505984; border-radius: 50%; animation: spinCur 0.8s linear infinite;"></div>
            <p style="margin-top: 1rem; color: #64748B; font-weight: 600; font-size: 0.95rem;">Cargando formulario oficial de Google...</p>
        </div>

        <iframe 
            id="google-form-iframe"
            src="<?= htmlspecialchars($url_embed) ?>" 
            width="100%" 
            height="850" 
            frameborder="0" 
            marginheight="0" 
            marginwidth="0"
            style="display: block; width: 100%; min-height: 850px; border: none;"
            onload="document.getElementById('form-loader').style.display='none';">
            Cargando…
        </iframe>
    </div>

</div>

<style>
@keyframes spinCur {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
