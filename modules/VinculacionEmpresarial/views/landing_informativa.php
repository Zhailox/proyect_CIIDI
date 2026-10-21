<?php require_once __DIR__ . '/../services/ConfigService.php'; ?>
<?php
require_once __DIR__ . '/../models/PropuestaEmpresaModel.php';
require_once __DIR__ . '/../services/FormularioPropuestaService.php';
require_once CORE_PATH . 'Security/CaptchaService.php';
require_once CORE_PATH . 'Security/CSRF.php';

$propuestaModel = new PropuestaEmpresaModel();

// Organizaciones con propuestas aceptadas (sin archivadas), cada una UNA sola vez
$organizacionesConfianza = $propuestaModel->getOrganizacionesConfianza();
$totalOrganizaciones = count($organizacionesConfianza);

// Copias mínimas para llenar el ancho (~10 elementos visibles). El track contiene esa "mitad"
// dos veces para que la animación translateX(-50%) sea un bucle continuo sin saltos.
// En el cliente se recalcula según el ancho real de la pantalla (ver script del carrusel).
$minVisiblesCarrusel = 10;
$copiasCarrusel = $totalOrganizaciones > 0 ? (int)ceil($minVisiblesCarrusel / $totalOrganizaciones) : 0;
$mitadCarrusel = [];
for ($c = 0; $c < $copiasCarrusel; $c++) {
    foreach ($organizacionesConfianza as $org) {
        $mitadCarrusel[] = $org;
    }
}

$paletaAvatares = ['#121a3e', '#505984', '#7090cb', '#3c456a'];
$renderOrgCard = static function (array $org, bool $esCopia) use ($paletaAvatares): string {
    $nombre = $org['nombre'];
    $palabras = preg_split('/\s+/u', preg_replace('/[^\p{L}\p{N}\s]/u', '', $nombre) ?? '', -1, PREG_SPLIT_NO_EMPTY) ?: [$nombre];
    $iniciales = mb_strtoupper(mb_substr($palabras[0], 0, 1) . (isset($palabras[1]) ? mb_substr($palabras[1], 0, 1) : mb_substr($palabras[0], 1, 1)));
    $color = $paletaAvatares[crc32(mb_strtolower($nombre)) % count($paletaAvatares)];
    $sub = $org['en_desarrollo'] ? 'Proyecto en desarrollo' : 'Aliado tecnológico';
    $aria = $esCopia ? ' aria-hidden="true"' : '';
    return '<div class="ve-org-card"' . $aria . ' title="' . htmlspecialchars($nombre) . '">'
        . '<span class="ve-org-avatar" style="background:' . $color . ';">' . htmlspecialchars($iniciales) . '</span>'
        . '<span class="ve-org-info"><span class="ve-org-name">' . htmlspecialchars($nombre) . '</span>'
        . '<span class="ve-org-sub">' . $sub . '</span></span></div>';
};

// Formulario dinámico "Postula tu Requerimiento Tecnológico"
$camposFormulario = FormularioPropuestaService::getCampos();
$bloquesFormulario = FormularioPropuestaService::agruparParaRender($camposFormulario);
$valoresPrevios = is_array($_SESSION['propuesta_form_old'] ?? null) ? $_SESSION['propuesta_form_old'] : [];
unset($_SESSION['propuesta_form_old']);
$recepcionActiva = (bool)VinculacionConfigService::get('recepcion_activa', true);
$segDataPropuesta = CaptchaService::generarCamposSeguridad();
?>
<style>
/* --- Carrusel dinámico de organizaciones --- */
.ve-marquee-track.ve-org-track { display: inline-flex; align-items: center; gap: 1.25rem; padding: 0.5rem 0; animation-duration: var(--ve-marquee-dur, 40s); }
.ve-org-track .ve-org-card { display: inline-flex; align-items: center; gap: 0.75rem; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 0.7rem 1.1rem 0.7rem 0.7rem; box-shadow: 0 2px 6px rgba(18,26,62,0.06); min-width: 210px; max-width: 280px; transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease; }
.ve-org-track .ve-org-card:hover { transform: translateY(-3px); box-shadow: 0 8px 18px rgba(18,26,62,0.12); border-color: #7090cb; }
.ve-org-avatar { flex: 0 0 44px; width: 44px; height: 44px; border-radius: 50%; color: #ffffff; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1rem; letter-spacing: 1px; }
.ve-org-info { display: flex; flex-direction: column; min-width: 0; text-align: left; }
.ve-org-name { color: #121a3e; font-weight: 700; font-size: 1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ve-org-sub { color: #7090cb; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
.ve-org-empty { max-width: 560px; margin: 0 auto; text-align: center; background: #ffffff; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 2rem 1.5rem; color: #64748b; }
.ve-org-empty i { font-size: 2.5rem; color: #7090cb; }
.ve-org-empty a { color: #505984; font-weight: 700; }
@media (prefers-reduced-motion: reduce) { .ve-marquee-track.ve-org-track { animation: none; } }
/* --- Captcha del formulario de empresas --- */
.ve-captcha-row { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
.ve-captcha-row img { border-radius: 6px; border: 1px solid #cbd5e1; height: 36px; cursor: pointer; }
.ve-captcha-row .ve-input { width: 130px; text-align: center; font-weight: 700; }
</style>
<div class="ve-wrapper">
    
    <section class="ve-hero">
        <div class="ve-hero-content">
            <h1>Innovación Tecnológica para el Sector Productivo</h1>
            <p>
                <?= htmlspecialchars(VinculacionConfigService::get('mensaje_marquee', 'Conectamos el talento de la UPTTMBI con las necesidades reales de las organizaciones. Si tienes un cuello de botella informático, nosotros tenemos la solución en código.')) ?>
            </p>
        </div>
        
        <div class="ve-wave-bottom">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118.08,130.83,123.8,198.81,115.15,240.76,109.84,281.42,88.4,321.39,56.44Z" class="shape-fill"></path>
            </svg>
        </div>
    </section>

    <section class="ve-marquee-section">
        <h3 class="ve-marquee-title">Organizaciones que han confiado en nuestro talento</h3>
        <div class="ve-marquee-container">
            <?php if ($totalOrganizaciones === 0): ?>
                <div class="ve-org-empty">
                    <i class="fas fa-building"></i>
                    <p>Sé la primera organización en <a href="#registro-problematica">postular un requerimiento tecnológico</a> y permítenos ser tu aliado.</p>
                </div>
            <?php else: ?>
                <div class="ve-marquee-track ve-org-track">
                    <?php 
                    // Track contains $mitadCarrusel twice for seamless loop
                    foreach ($mitadCarrusel as $org) {
                        echo $renderOrgCard($org, false);
                    }
                    foreach ($mitadCarrusel as $org) {
                        echo $renderOrgCard($org, true);
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="ve-section alt-bg">
        <h2 class="ve-steps-title">¿Qué somos capaces de desarrollar?</h2>
        <p class="ve-steps-subtitle">
            Nuestros estudiantes de Trayecto (TSU e Ingeniería) están capacitados para construir soluciones eficientes, seguras y nativas. No hacemos simples páginas web; diseñamos sistemas robustos.
        </p>
        <div class="ve-steps-grid">
            <div class="ve-step-card">
                <div class="ve-step-number">1</div>
                <h3>Software a Medida</h3>
                <p>Aplicaciones de escritorio y web ultraligeras para gestión de inventarios, facturación, y control de personal, optimizadas para el hardware de tu empresa.</p>
            </div>
            <div class="ve-step-card">
                <div class="ve-step-number">2</div>
                <h3>Infraestructura y Redes</h3>
                <p>Auditoría de redes locales, configuración de servidores Linux/Windows, cableado estructurado y despliegue de intranets seguras.</p>
            </div>
            <div class="ve-step-card">
                <div class="ve-step-number">3</div>
                <h3>Bases de Datos y Seguridad</h3>
                <p>Migración de datos, estructuración de bases de datos relacionales (SQL) y análisis de vulnerabilidades en sistemas existentes.</p>
            </div>
        </div>
    </section>

    <section class="ve-section">
        <h2 class="ve-steps-title">¿Tienes un problema? Te damos la solución.</h2>
        <p class="ve-steps-subtitle">
            No importa si eres una pequeña bodega en Valera buscando organizar tu stock, o una gran planta industrial con problemas de red. Postula tu "dolor" siguiendo estos pasos para que un equipo lo asuma como su Proyecto Socio Tecnológico (PST).
        </p>
        
        <div class="ve-steps-grid">
            <div class="ve-step-card">
                <div class="ve-step-number">1</div>
                <h3>Identidad Básica</h3>
                <p>Rellena el formulario inferior con los datos de contacto de tu organización. Necesitamos saber quién eres y dónde estás ubicado para agendar una posible visita técnica.</p>
            </div>
            <div class="ve-step-card">
                <div class="ve-step-number">2</div>
                <h3>Explica tu "Dolencia" (Sé Específico)</h3>
                <p>¿Qué proceso te quita tiempo? ¿Qué área necesita sistematizarse? Entre más detalles nos des, más fácil será asignar tu requerimiento a estudiantes de TSU (soporte/redes) o Ingeniería (arquitectura de software).</p>
            </div>
            <div class="ve-step-card">
                <div class="ve-step-number">3</div>
                <h3>Ejecución Académica</h3>
                <p>Si tu propuesta es aprobada por nuestro comité, un equipo de estudiantes trabajará de la mano contigo durante el año académico para desarrollar e implement la solución final a coste cero.</p>
            </div>
        </div>
    </section>

    <section id="registro-problematica" class="ve-section alt-bg">
        <div class="ve-form-container">
            <h2>Postula tu Requerimiento Tecnológico</h2>
            <p>Ingresa los detalles a continuación. Nuestro banco de proyectos clasificará tu solicitud para que los estudiantes de Informática puedan adoptarla.</p>
            
            <?php if(isset($_SESSION['mensaje_exito'])): ?>
                <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    <?php echo $_SESSION['mensaje_exito']; unset($_SESSION['mensaje_exito']); ?>
                </div>
            <?php endif; ?>
            <?php if(isset($_SESSION['mensaje_error'])): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    <?php echo $_SESSION['mensaje_error']; unset($_SESSION['mensaje_error']); ?>
                </div>
            <?php endif; ?>

            <form action="?ruta=guardar-propuesta" method="POST" class="ve-form">
                <?php echo CSRF::campoOculto(); ?>
                <input type="hidden" name="_form_ts" value="<?php echo time(); ?>">
                <input type="hidden" name="website_url_hp" value="" style="display:none;" autocomplete="off">
                
                <?php foreach ($bloquesFormulario as $bloque): ?>
                    <?php if ($bloque['tipo'] === 'seccion'): ?>
                        <?php $c = $bloque['campos'][0]; ?>
                        <h4 style="color: var(--ve-secundario); margin-top: 2rem; margin-bottom: 1rem; border-bottom: 1px solid rgba(0,0,0,0.1); padding-bottom: 0.5rem;">
                            <?= htmlspecialchars($c['etiqueta']) ?>
                        </h4>
                    <?php elseif ($bloque['tipo'] === 'fila'): ?>
                        <div class="ve-form-row">
                            <?php foreach ($bloque['campos'] as $c): ?>
                                <div class="ve-form-group">
                                    <?= FormularioPropuestaService::renderInput($c, $valoresPrevios[$c['clave']] ?? '') ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="ve-form-group">
                            <?php $c = $bloque['campos'][0]; ?>
                            <?= FormularioPropuestaService::renderInput($c, $valoresPrevios[$c['clave']] ?? '') ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>

                <div class="ve-form-group ve-captcha-row" style="margin-top: 1.5rem;">
                    <label class="ve-form-label" style="width:100%;">Seguridad: ¿Cuánto es?</label>
                    <img src="?ruta=captcha-imagen&_t=<?= time() ?>" alt="Captcha" id="ve-captcha-img" onclick="this.src='?ruta=captcha-imagen&_t='+Date.now()">
                    <input type="number" name="captcha_ans" class="ve-input" required placeholder="?" autocomplete="off" style="width: 100px;">
                    <span style="font-size:0.8rem; color:#64748b;">(Clic en la imagen para recargar)</span>
                </div>

                <button type="submit" class="btn ve-btn-submit" style="margin-top: 1.5rem;">Ingresar Solicitud al Banco de Proyectos</button>
            </form>
        </div>
    </section>

</div>

<?php if (isset($_SESSION['codigo_seguimiento'])): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const code = <?php echo json_encode($_SESSION['codigo_seguimiento']); ?>;
        Swal.fire({
            title: '¡Propuesta Enviada con Éxito!',
            html: `
                <p>Su requerimiento ha ingresado al CIIDI.</p>
                <p>Este es su <b>Código de Seguimiento</b> exclusivo:</p>
                <div style="background: #f1f5f9; padding: 15px; margin: 15px 0; border-radius: 8px; border: 2px dashed #94a3b8; display: flex; align-items: center; justify-content: space-between;">
                    <b id="swal-tracking-code" style="font-size: 24px; color: #121a3e; letter-spacing: 2px; font-family: monospace;">
                        ${code}
                    </b>
                    <button id="swal-copy-btn" style="background:#505984; color:white; border:none; border-radius:4px; padding:6px 12px; cursor:pointer; font-weight:bold;">Copiar</button>
                </div>
                <p style="font-size: 0.9em; color: #64748b;">
                    Guárdelo en un lugar seguro. Podrá usarlo en nuestro <a href="?ruta=transparencia-seguimiento&codigo=${code}" style="color:#7090cb; font-weight:bold; text-decoration:underline;">Portal de Seguimiento</a> para ver en tiempo real qué equipo de estudiantes solucionará su problema.
                </p>
            `,
            icon: 'success',
            confirmButtonText: 'Entendido, lo he guardado',
            confirmButtonColor: '#121a3e',
            allowOutsideClick: false,
            didOpen: () => {
                const btn = document.getElementById('swal-copy-btn');
                btn.addEventListener('click', () => {
                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(code).then(() => {
                            btn.textContent = '¡Copiado!';
                            btn.style.background = '#10b981';
                        });
                    } else {
                        const textArea = document.createElement("textarea");
                        textArea.value = code;
                        textArea.style.position = "fixed";
                        textArea.style.left = "-999999px";
                        textArea.style.top = "-999999px";
                        document.body.appendChild(textArea);
                        textArea.focus();
                        textArea.select();
                        try {
                            document.execCommand('copy');
                            btn.textContent = '¡Copiado!';
                            btn.style.background = '#10b981';
                        } catch (err) {
                            console.error('Fallback: Oops, unable to copy', err);
                        }
                        document.body.removeChild(textArea);
                    }
                    setTimeout(() => {
                        btn.textContent = 'Copiar';
                        btn.style.background = '#505984';
                    }, 2000);
                });
            }
        });
    });
</script>
<?php 
    unset($_SESSION['codigo_seguimiento']); 
    unset($_SESSION['mensaje_exito']); 
endif; ?>