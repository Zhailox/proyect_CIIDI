<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalador &bull; Repositorio CIIDI</title>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        /* ==========================================================================
           Antigravity UI & Style Guide (estilo.md) - Installer Wizard Theme
           ========================================================================== */
        body {
            background-color: var(--blanco, #f4f7fb);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-family, 'Inter', system-ui, -apple-system, sans-serif);
            margin: 0;
            padding: 1.5rem;
            box-sizing: border-box;
            color: var(--texto-comun, #1a1a1a);
            position: relative;
            overflow-x: hidden;
        }

        /* FONDO ANIMADO DE NODOS PARTICULAS ILUMINADAS */
        .installer-canvas-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 0;
        }

        .installer-container {
            position: relative;
            z-index: 1;
            max-width: 880px;
            width: 100%;
            display: grid;
            grid-template-columns: 290px 1fr;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(80, 89, 132, 0.18);
            border-radius: var(--radius-md, 16px);
            box-shadow: 0 20px 45px rgba(80, 89, 132, 0.12);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @media (max-width: 768px) {
            .installer-container {
                grid-template-columns: 1fr;
            }
            .installer-sidebar {
                display: none;
            }
        }

        /* BANNER LATERAL ILUSTRATIVO */
        .installer-sidebar {
            background: linear-gradient(135deg, var(--color-secundario, #505984) 0%, #3C456A 100%);
            color: #ffffff;
            padding: 2.5rem 1.8rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .sidebar-illustration {
            text-align: center;
            margin: 1.5rem 0;
        }

        .sidebar-illustration img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            border: 2px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.4s ease;
        }

        .sidebar-illustration img:hover {
            transform: scale(1.03);
        }

        /* ÁREA DE CONTENIDO Y PASOS */
        .installer-main {
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .installer-header {
            margin-bottom: 1.5rem;
        }

        .installer-title {
            font-size: 1.65rem;
            font-weight: 800;
            color: var(--texto-titulos, #1e293b);
            margin: 0.2rem 0 0 0;
        }

        /* STEPPER BARRA DE NAVEGACIÓN */
        .stepper-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 2rem;
        }

        .step-pill {
            flex: 1;
            height: 6px;
            background: rgba(80, 89, 132, 0.15);
            border-radius: 10px;
            transition: all 0.4s ease;
        }

        .step-pill.active {
            background: var(--color-secundario, #505984);
            box-shadow: 0 0 10px rgba(80, 89, 132, 0.4);
        }

        .step-pill.completed {
            background: #059669;
        }

        .step-pane {
            display: none;
        }

        .step-pane.active {
            display: block;
            animation: agFadeIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes agFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* INPUTS Y FORMULARIOS */
        .ag-label {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--texto-subtitulos, #505984);
            display: block;
            margin-bottom: 6px;
        }

        .ag-input {
            width: 100%;
            padding: 10px 14px;
            border-radius: var(--radius-sm, 8px);
            border: 1px solid rgba(80, 89, 132, 0.25);
            background: rgba(248, 250, 252, 0.9);
            font-size: 0.88rem;
            color: var(--texto-titulos, #1e293b);
            box-sizing: border-box;
            transition: all 0.25s ease;
        }

        .ag-input:focus {
            outline: none;
            border-color: var(--color-secundario, #505984);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(80, 89, 132, 0.15);
        }

        .ag-btn {
            height: 38px;
            padding: 0 16px;
            border-radius: var(--radius-sm, 8px);
            font-weight: 700;
            font-size: 0.85rem;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            box-sizing: border-box;
        }

        .ag-btn-primary {
            background: var(--color-secundario, #505984) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(80, 89, 132, 0.25);
        }

        .ag-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(80, 89, 132, 0.35);
            background: #3C456A !important;
        }

        .ag-btn-secondary {
            background: #ffffff !important;
            color: var(--color-secundario, #505984) !important;
            border: 1px solid rgba(80, 89, 132, 0.25) !important;
        }

        .ag-btn-secondary:hover {
            transform: translateY(-2px);
            background: #f8fafc !important;
            border-color: var(--color-secundario) !important;
        }

        .check-badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.76rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .check-ok { background: rgba(16, 185, 129, 0.12); color: #059669; border: 1px solid rgba(16, 185, 129, 0.25); }
        .check-err { background: rgba(239, 68, 68, 0.12); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.25); }
    </style>
</head>
<body>

<!-- CANVAS DE FONDO DE NODOS NODAL AZUL CLARO ANIMADO -->
<div class="installer-canvas-bg">
    <canvas id="installerNodeCanvas"></canvas>
</div>

<div class="installer-container">
    <!-- PANEL IZQUIERDO CORPORATIVO CON ILUSTRACIÓN -->
    <div class="installer-sidebar">
        <div>
            <div class="sidebar-brand">
                <i class="ph-fill ph-circles-four" style="font-size: 1.8rem; color: var(--color-terciario, #7090cb);"></i>
                CIIDI <span style="font-weight: 400; opacity: 0.85;">Installer</span>
            </div>
            <p style="font-size: 0.83rem; margin-top: 0.6rem; opacity: 0.9; line-height: 1.4;">
                Asistente de configuración y despliegue del Repositorio UPTTMBI.
            </p>
        </div>

        <div class="sidebar-illustration">
            <img src="public/assets/img/500.jpg" alt="Instalador CIIDI" id="installerIlustrationImg" onerror="this.style.display='none'">
        </div>

        <div style="font-size: 0.74rem; opacity: 0.75; text-align: center;">
            Versión 2.0.0
        </div>
    </div>

    <!-- PANEL DERECHO INTERACTIVO -->
    <div class="installer-main">
        <div>
            <div class="installer-header">
                <div style="font-size: 0.75rem; font-weight: 800; color: var(--color-terciario, #7090cb); text-transform: uppercase; letter-spacing: 1.2px;">
                    PASO <span id="lblPasoNumero">1</span> DE 4
                </div>
                <h1 class="installer-title" id="lblPasoTitulo">
                    Comprobación del Entorno
                </h1>
            </div>

            <!-- BARRA DE PROGRESO MULTI-PASO -->
            <div class="stepper-bar">
                <div class="step-pill active" id="pill-1"></div>
                <div class="step-pill" id="pill-2"></div>
                <div class="step-pill" id="pill-3"></div>
                <div class="step-pill" id="pill-4"></div>
            </div>

            <!-- MENSAJES DINÁMICOS DE ALERTA -->
            <div id="alertBoxInstaller" style="display: none; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); font-weight: 600; font-size: 0.88rem; margin-bottom: 1.5rem;"></div>

            <!-- PASO 1: VERIFICACIÓN DEL ENTORNO -->
            <div class="step-pane active" id="pane-1">
                <div style="background: rgba(248, 250, 252, 0.9); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 12px; padding: 1.1rem; margin-bottom: 1.5rem;">
                    <!-- Versión PHP -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.65rem; border-bottom: 1px solid rgba(80, 89, 132, 0.1);">
                        <span style="font-weight: 700; font-size: 0.85rem;">Versión de PHP (>= 8.1.0)</span>
                        <span class="check-badge <?= $envCheck['php_version_ok'] ? 'check-ok' : 'check-err' ?>">
                            <i class="ph-bold <?= $envCheck['php_version_ok'] ? 'ph-check-circle' : 'ph-x-circle' ?>"></i>
                            PHP <?= htmlspecialchars($envCheck['php_version']) ?>
                        </span>
                    </div>

                    <!-- Límite de Memoria RAM -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.65rem 0; border-bottom: 1px solid rgba(80, 89, 132, 0.1);">
                        <span style="font-weight: 700; font-size: 0.85rem;">Memoria Asignada</span>
                        <span class="check-badge <?= $envCheck['memory_ok'] ? 'check-ok' : 'check-err' ?>">
                            <i class="ph-bold <?= $envCheck['memory_ok'] ? 'ph-check-circle' : 'ph-warning' ?>"></i>
                            <?= htmlspecialchars($envCheck['memory_limit']) ?>
                        </span>
                    </div>

                    <!-- Extensiones PHP -->
                    <div style="padding: 0.65rem 0; border-bottom: 1px solid rgba(80, 89, 132, 0.1);">
                        <div style="font-weight: 700; font-size: 0.85rem; margin-bottom: 0.4rem;">Extensiones Requeridas</div>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.4rem;">
                            <?php foreach ($envCheck['extensions'] as $ext => $loaded): ?>
                                <span class="check-badge <?= $loaded ? 'check-ok' : 'check-err' ?>">
                                    <i class="ph-bold <?= $loaded ? 'ph-check-circle' : 'ph-x-circle' ?>"></i> <?= htmlspecialchars($ext) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Carpetas de Escritura -->
                    <div style="padding-top: 0.65rem;">
                        <div style="font-weight: 700; font-size: 0.85rem; margin-bottom: 0.4rem;">Permisos de Escritura</div>
                        <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                            <?php foreach ($envCheck['directories'] as $label => $info): ?>
                                <div style="display: flex; justify-content: space-between; font-size: 0.8rem;">
                                    <span><code><?= htmlspecialchars($label) ?>/</code></span>
                                    <span class="check-badge <?= $info['writable'] ? 'check-ok' : 'check-err' ?>">
                                        <?= $info['writable'] ? 'Escritura OK' : 'Sin Permisos' ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PASO 2: CONEXIÓN & CREACIÓN AUTOMÁTICA BD -->
            <div class="step-pane" id="pane-2">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label class="ag-label">Host Servidor BD</label>
                        <input type="text" id="db_host" class="ag-input" value="localhost">
                    </div>
                    <div>
                        <label class="ag-label">Puerto PostgreSQL</label>
                        <input type="text" id="db_port" class="ag-input" value="5432">
                    </div>
                    <div>
                        <label class="ag-label">Usuario BD</label>
                        <input type="text" id="db_user" class="ag-input" value="postgres">
                    </div>
                    <div>
                        <label class="ag-label">Contraseña BD</label>
                        <input type="password" id="db_pass" class="ag-input" value="1234">
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <label class="ag-label">Nombre de la Base de Datos</label>
                        <input type="text" id="db_name" class="ag-input" value="ciidi">
                    </div>
                </div>
            </div>

            <!-- PASO 3: REGISTRO SUPERADMIN -->
            <div class="step-pane" id="pane-3">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label class="ag-label">Cédula de Identidad</label>
                        <input type="number" min="1" id="adm_cedula" class="ag-input" placeholder="Ej: 12345678" onkeydown="if(['-','e','.'].includes(event.key)) event.preventDefault();">
                    </div>
                    <div>
                        <label class="ag-label">Nombre Completo</label>
                        <input type="text" id="adm_nombre" class="ag-input" placeholder="Ej: Administrador CIIDI">
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <label class="ag-label">Correo Electrónico</label>
                        <input type="email" id="adm_correo" class="ag-input" placeholder="admin@ciidi.local">
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <label class="ag-label">Contraseña Maestra</label>
                        <input type="password" id="adm_clave" class="ag-input" placeholder="Contraseña alta seguridad">
                    </div>
                </div>
            </div>

            <!-- PASO 4: ÉXITO -->
            <div class="step-pane" id="pane-4">
                <div style="text-align: center; padding: 1.5rem 0;">
                    <div style="width: 64px; height: 64px; border-radius: 50%; background: rgba(16,185,129,0.12); color: #059669; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; margin: 0 auto 1rem auto;">
                        <i class="ph-bold ph-shield-check"></i>
                    </div>
                    <h2 style="font-size: 1.45rem; font-weight: 800; color: var(--texto-titulos); margin: 0 0 0.4rem 0;">
                        ¡Instalación Completada!
                    </h2>
                    <p style="color: var(--texto-silenciado); font-size: 0.88rem; max-width: 460px; margin: 0 auto 1.5rem auto;">
                        Se han migrado las tablas de <code>ciidi.sql</code>, el usuario SuperAdmin ha sido registrado y el candado <code>installed.lock</code> ha sido activado.
                    </p>
                </div>
            </div>
        </div>

        <!-- BARRA DE NAVEGACIÓN INFERIOR DE ACCIONES -->
        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(80, 89, 132, 0.1); padding-top: 1.25rem; margin-top: 1rem;">
            <button type="button" id="btnPrevStep" onclick="navegarPaso(-1)" class="ag-btn ag-btn-secondary" style="display: none;">
                <i class="ph-bold ph-arrow-left"></i> Anterior
            </button>

            <div style="margin-left: auto; display: flex; gap: 0.5rem;">
                <button type="button" id="btnTestDb" onclick="testearBD()" class="ag-btn ag-btn-secondary" style="display: none;">
                    <i class="ph-bold ph-plugs-connected"></i> Probar Conexión
                </button>
                <button type="button" id="btnNextStep" onclick="navegarPaso(1)" class="ag-btn ag-btn-primary">
                    Continuar <i class="ph-bold ph-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let pasoActual = 1;
const titulosPasos = [
    'Comprobación del Entorno',
    'Base de Datos PostgreSQL',
    'Registro del SuperAdmin',
    'Despliegue Finalizado'
];

function mostrarAlerta(msg, tipo = 'error') {
    const box = document.getElementById('alertBoxInstaller');
    box.style.display = 'block';
    if (tipo === 'success') {
        box.style.background = 'rgba(16,185,129,0.12)';
        box.style.color = '#047857';
        box.style.border = '1px solid rgba(16,185,129,0.3)';
    } else {
        box.style.background = 'rgba(239,68,68,0.12)';
        box.style.color = '#b91c1c';
        box.style.border = '1px solid rgba(239,68,68,0.3)';
    }
    box.innerHTML = msg;
}

function ocultarAlerta() {
    document.getElementById('alertBoxInstaller').style.display = 'none';
}

function irAlPaso(paso) {
    if (paso < 1 || paso > 4) return;
    ocultarAlerta();
    pasoActual = paso;

    document.getElementById('lblPasoNumero').innerText = pasoActual;
    document.getElementById('lblPasoTitulo').innerText = titulosPasos[pasoActual - 1];

    document.querySelectorAll('.step-pane').forEach(el => el.classList.remove('active'));
    document.getElementById(`pane-${pasoActual}`).classList.add('active');

    document.querySelectorAll('.step-pill').forEach((pill, idx) => {
        if (idx + 1 === pasoActual) {
            pill.className = 'step-pill active';
        } else if (idx + 1 < pasoActual) {
            pill.className = 'step-pill completed';
        } else {
            pill.className = 'step-pill';
        }
    });

    document.getElementById('btnPrevStep').style.display = pasoActual > 1 && pasoActual < 4 ? 'inline-flex' : 'none';
    document.getElementById('btnTestDb').style.display = pasoActual === 2 ? 'inline-flex' : 'none';
    
    const btnNext = document.getElementById('btnNextStep');
    if (pasoActual === 3) {
        btnNext.innerHTML = '<i class="ph-bold ph-rocket-launch"></i> Ejecutar Instalación';
        btnNext.className = 'ag-btn ag-btn-primary';
    } else if (pasoActual === 4) {
        btnNext.innerHTML = 'Ir al Iniciar Sesión <i class="ph-bold ph-arrow-right"></i>';
        btnNext.className = 'ag-btn ag-btn-primary';
        document.getElementById('btnPrevStep').style.display = 'none';
    } else {
        btnNext.innerHTML = 'Continuar <i class="ph-bold ph-arrow-right"></i>';
        btnNext.className = 'ag-btn ag-btn-primary';
    }
}

function navegarPaso(dir) {
    if (dir === 1 && pasoActual === 3) {
        ejecutarInstalacionFinal();
        return;
    }
    
    if (dir === 1 && pasoActual === 4) {
        window.location.href = 'login';
        return;
    }

    irAlPaso(pasoActual + dir);
}

function testearBD() {
    ocultarAlerta();
    const formData = new FormData();
    formData.append('host', document.getElementById('db_host').value);
    formData.append('port', document.getElementById('db_port').value);
    formData.append('user', document.getElementById('db_user').value);
    formData.append('pass', document.getElementById('db_pass').value);
    formData.append('db', document.getElementById('db_name').value);

    fetch('?ruta=installer-test-db', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.exito) {
            mostrarAlerta(data.mensaje, 'success');
        } else {
            mostrarAlerta(data.mensaje, 'error');
        }
    })
    .catch(err => mostrarAlerta('Error de red al probar conexión: ' + err));
}

function ejecutarInstalacionFinal() {
    ocultarAlerta();
    const btnNext = document.getElementById('btnNextStep');
    btnNext.disabled = true;
    btnNext.innerHTML = '<i class="ph-bold ph-spinner spin"></i> Desplegando e Importando SQL...';

    const formData = new FormData();
    formData.append('host', document.getElementById('db_host').value);
    formData.append('port', document.getElementById('db_port').value);
    formData.append('user', document.getElementById('db_user').value);
    formData.append('pass', document.getElementById('db_pass').value);
    formData.append('db', document.getElementById('db_name').value);

    formData.append('admin_cedula', document.getElementById('adm_cedula').value);
    formData.append('admin_nombre', document.getElementById('adm_nombre').value);
    formData.append('admin_correo', document.getElementById('adm_correo').value);
    formData.append('admin_clave', document.getElementById('adm_clave').value);

    fetch('?ruta=installer-run', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.text())
    .then(text => {
        btnNext.disabled = false;
        let data;
        try {
            data = JSON.parse(text);
        } catch(e) {
            console.error("Respuesta no-JSON recibida del servidor:", text);
            mostrarAlerta("Error de servidor PHP: " + text.replace(/<[^>]*>?/gm, ''), 'error');
            btnNext.innerHTML = '<i class="ph-bold ph-rocket-launch"></i> Ejecutar Instalación';
            return;
        }

        if (data.exito) {
            irAlPaso(4); // Avanzar directamente al paso 4 (Éxito)
        } else {
            btnNext.innerHTML = '<i class="ph-bold ph-rocket-launch"></i> Ejecutar Instalación';
            mostrarAlerta(data.mensaje, 'error');
        }
    })
    .catch(err => {
        btnNext.disabled = false;
        btnNext.innerHTML = '<i class="ph-bold ph-rocket-launch"></i> Ejecutar Instalación';
        mostrarAlerta('Error durante la instalación: ' + err);
    });
}

// ANIMACIÓN DE FONDO DE NODOS PARTICULAS AZUL CLARO AMIGABLES
(function initInstallerNodeCanvas() {
    const canvas = document.getElementById('installerNodeCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let width, height;
    let particles = [];

    function resize() {
        width = canvas.width = window.innerWidth;
        height = canvas.height = window.innerHeight;
    }
    window.addEventListener('resize', resize);
    resize();

    class Particle {
        constructor() {
            this.x = Math.random() * width;
            this.y = Math.random() * height;
            this.vx = (Math.random() - 0.5) * 0.6;
            this.vy = (Math.random() - 0.5) * 0.6;
            this.radius = Math.random() * 3.5 + 2.5; // Nodos azul claro
        }
        update() {
            this.x += this.vx;
            this.y += this.vy;
            if (this.x < 0 || this.x > width) this.vx *= -1;
            if (this.y < 0 || this.y > height) this.vy *= -1;
        }
        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(112, 144, 203, 0.6)'; // Azul claro corporativo (--color-terciario)
            ctx.fill();
        }
    }

    const numParticles = Math.min(Math.floor(width / 15), 55);
    for (let i = 0; i < numParticles; i++) {
        particles.push(new Particle());
    }

    function animate() {
        if (document.hidden) {
            requestAnimationFrame(animate);
            return;
        }
        ctx.clearRect(0, 0, width, height);
        for (let i = 0; i < particles.length; i++) {
            particles[i].update();
            particles[i].draw();
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < 160) {
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.strokeStyle = `rgba(112, 144, 203, ${0.35 * (1 - dist / 160)})`;
                    ctx.lineWidth = 1.2;
                    ctx.stroke();
                }
            }
        }
        requestAnimationFrame(animate);
    }
    animate();
})();
</script>

</body>
</html>
