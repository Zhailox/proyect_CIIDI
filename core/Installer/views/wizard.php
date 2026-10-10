<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalador &bull; Repositorio Institucional CIIDI</title>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        /* ==========================================================================
           Antigravity UI - Installer Wizard Multi-Configuration Theme
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
            max-width: 960px;
            width: 100%;
            display: grid;
            grid-template-columns: 270px 1fr;
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(80, 89, 132, 0.18);
            border-radius: var(--radius-md, 16px);
            box-shadow: 0 25px 50px -12px rgba(80, 89, 132, 0.22);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @media (max-width: 860px) {
            .installer-container {
                grid-template-columns: 1fr;
            }
            .installer-sidebar {
                display: none;
            }
        }

        .installer-sidebar {
            background: linear-gradient(145deg, var(--color-secundario, #505984) 0%, #2f3652 100%);
            color: #ffffff;
            padding: 2.2rem 1.6rem;
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
            box-shadow: 0 10px 25px rgba(0,0,0,0.25);
            border: 2px solid rgba(255, 255, 255, 0.18);
            transition: transform 0.4s ease;
        }

        .sidebar-illustration img:hover {
            transform: scale(1.03);
        }

        .installer-main {
            padding: 2rem 2.4rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 560px;
        }

        .installer-header {
            margin-bottom: 1.1rem;
        }

        .installer-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--texto-titulos, #1e293b);
            margin: 0.2rem 0 0 0;
        }

        .stepper-bar {
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 1.3rem;
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
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .ag-card-config {
            background: rgba(248, 250, 252, 0.85);
            border: 1px solid rgba(80, 89, 132, 0.14);
            border-radius: 10px;
            padding: 1rem 1.15rem;
            margin-bottom: 0.9rem;
            transition: all 0.25s ease;
        }

        .ag-card-config:hover {
            border-color: rgba(80, 89, 132, 0.28);
        }

        .ag-card-title {
            font-size: 0.85rem;
            font-weight: 800;
            color: var(--texto-titulos, #1e293b);
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 0.65rem;
        }

        .ag-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--texto-subtitulos, #505984);
            display: block;
            margin-bottom: 4px;
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .ag-input, .ag-select {
            width: 100%;
            padding: 8px 12px;
            border-radius: var(--radius-sm, 8px);
            border: 1px solid rgba(80, 89, 132, 0.25);
            background: #ffffff;
            font-size: 0.85rem;
            color: var(--texto-titulos, #1e293b);
            box-sizing: border-box;
            transition: all 0.25s ease;
        }

        .ag-input:focus, .ag-select:focus {
            outline: none;
            border-color: var(--color-secundario, #505984);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(80, 89, 132, 0.15);
        }

        .btn-toggle-eye {
            position: absolute;
            right: 8px;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--color-secundario, #505984);
            font-size: 1.15rem;
            padding: 4px;
            display: flex;
            align-items: center;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .btn-toggle-eye:hover {
            opacity: 1;
        }

        .ag-btn {
            height: 38px;
            padding: 0 16px;
            border-radius: var(--radius-sm, 8px);
            font-weight: 700;
            font-size: 0.84rem;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            box-sizing: border-box;
        }

        .ag-btn:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            pointer-events: none;
        }

        .ag-btn-primary {
            background: var(--color-secundario, #505984) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(80, 89, 132, 0.25);
        }

        .ag-btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(80, 89, 132, 0.35);
            background: #3C456A !important;
        }

        .ag-btn-secondary {
            background: #ffffff !important;
            color: var(--color-secundario, #505984) !important;
            border: 1px solid rgba(80, 89, 132, 0.25) !important;
        }

        .ag-btn-secondary:hover:not(:disabled) {
            transform: translateY(-2px);
            background: #f8fafc !important;
            border-color: var(--color-secundario) !important;
        }

        .check-badge {
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 0.74rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .check-ok { background: rgba(16, 185, 129, 0.12); color: #059669; border: 1px solid rgba(16, 185, 129, 0.25); }
        .check-err { background: rgba(239, 68, 68, 0.12); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.25); }

        .telemetry-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 7px 12px;
            border-radius: 8px;
            background: rgba(248, 250, 252, 0.8);
            border: 1px solid rgba(80, 89, 132, 0.1);
            font-size: 0.83rem;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }

        .telemetry-item.pending { opacity: 0.45; }
        .telemetry-item.running {
            background: rgba(80, 89, 132, 0.08);
            border-color: var(--color-secundario);
            font-weight: 700;
            color: var(--color-secundario);
        }
        .telemetry-item.completed {
            background: rgba(16, 185, 129, 0.08);
            border-color: rgba(16, 185, 129, 0.3);
            color: #047857;
            font-weight: 600;
        }

        .spin { animation: spin 1s linear infinite; }
        @keyframes spin { 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>

<div class="installer-canvas-bg">
    <canvas id="installerNodeCanvas"></canvas>
</div>

<div class="installer-container">
    <!-- PANEL LATERAL CORPORATIVO -->
    <div class="installer-sidebar">
        <div>
            <div class="sidebar-brand">
                <i class="ph-fill ph-circles-four" style="font-size: 1.8rem; color: var(--color-terciario, #7090cb);"></i>
                CIIDI <span style="font-weight: 400; opacity: 0.85;">Installer</span>
            </div>
            <p style="font-size: 0.83rem; margin-top: 0.6rem; opacity: 0.9; line-height: 1.4;">
                Asistente de configuración y despliegue del Repositorio Institucional UPTTMBI.
            </p>
        </div>

        <div class="sidebar-illustration">
            <img src="public/assets/img/500.webp" alt="Instalador CIIDI" id="installerIlustrationImg" onerror="this.style.display='none'">
        </div>

        <div style="font-size: 0.74rem; opacity: 0.8; text-align: center;">
            Versión 2.0.0-PROD
        </div>
    </div>

    <!-- PANEL PRINCIPAL INTERACTIVO -->
    <div class="installer-main">
        <div>
            <div class="installer-header">
                <div style="font-size: 0.72rem; font-weight: 800; color: var(--color-terciario, #7090cb); text-transform: uppercase; letter-spacing: 1.2px;">
                    PASO <span id="lblPasoNumero">1</span> DE 5
                </div>
                <h1 class="installer-title" id="lblPasoTitulo">
                    Comprobación del Entorno
                </h1>
            </div>

            <!-- STEPPER DE 5 PASOS -->
            <div class="stepper-bar">
                <div class="step-pill active" id="pill-1"></div>
                <div class="step-pill" id="pill-2"></div>
                <div class="step-pill" id="pill-3"></div>
                <div class="step-pill" id="pill-4"></div>
                <div class="step-pill" id="pill-5"></div>
            </div>

            <!-- CAJA DE ALERTAS DINÁMICA -->
            <div id="alertBoxInstaller" style="display: none; padding: 0.75rem 1.1rem; border-radius: var(--radius-sm); font-weight: 600; font-size: 0.85rem; margin-bottom: 1.1rem;"></div>

            <!-- ==========================================
                 PASO 1: DIAGNÓSTICO DEL ENTORNO
                 ========================================== -->
            <div class="step-pane active" id="pane-1">
                <div style="background: rgba(248, 250, 252, 0.9); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 12px; padding: 1.1rem; margin-bottom: 1.1rem;">
                    <!-- Versión PHP y Memoria -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; padding-bottom: 0.7rem; border-bottom: 1px solid rgba(80, 89, 132, 0.1);">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-weight: 700; font-size: 0.82rem;">Versión de PHP (>= 8.1)</span>
                            <span class="check-badge <?= $envCheck['php_version_ok'] ? 'check-ok' : 'check-err' ?>">
                                <i class="ph-bold <?= $envCheck['php_version_ok'] ? 'ph-check-circle' : 'ph-x-circle' ?>"></i>
                                PHP <?= htmlspecialchars($envCheck['php_version']) ?>
                            </span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-weight: 700; font-size: 0.82rem;">Límite de Memoria</span>
                            <span class="check-badge <?= $envCheck['memory_ok'] ? 'check-ok' : 'check-err' ?>">
                                <i class="ph-bold <?= $envCheck['memory_ok'] ? 'ph-check-circle' : 'ph-warning' ?>"></i>
                                <?= htmlspecialchars($envCheck['memory_limit']) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Extensiones PHP Críticas -->
                    <div style="padding: 0.7rem 0; border-bottom: 1px solid rgba(80, 89, 132, 0.1);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <span style="font-weight: 700; font-size: 0.82rem;">Extensiones PHP Requeridas</span>
                            <span style="font-size: 0.72rem; color: var(--texto-silenciado, #64748b);">Verificación nativa</span>
                        </div>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.35rem;">
                            <?php foreach ($envCheck['extensions'] as $ext => $info): ?>
                                <span class="check-badge <?= $info['loaded'] ? 'check-ok' : 'check-err' ?>" title="<?= htmlspecialchars($info['description']) ?>">
                                    <i class="ph-bold <?= $info['loaded'] ? 'ph-check-circle' : 'ph-x-circle' ?>"></i>
                                    <?= htmlspecialchars($ext) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Permisos de Escritura -->
                    <div style="padding-top: 0.7rem;">
                        <div style="font-weight: 700; font-size: 0.82rem; margin-bottom: 0.4rem;">Permisos de Escritura del Sistema</div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.4rem;">
                            <?php foreach ($envCheck['directories'] as $label => $info): ?>
                                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.78rem; background: #fff; padding: 4px 8px; border-radius: 6px; border: 1px solid rgba(80,89,132,0.1);">
                                    <span><code><?= htmlspecialchars($label) ?></code></span>
                                    <span class="check-badge <?= $info['writable'] ? 'check-ok' : 'check-err' ?>" style="font-size: 0.7rem; padding: 2px 6px;">
                                        <?= $info['writable'] ? 'Correcto' : 'Bloqueado' ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <?php if (!$envCheck['ready']): ?>
                    <div style="background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px; padding: 0.8rem; font-size: 0.82rem; color: #b91c1c;">
                        <i class="ph-bold ph-warning-circle" style="vertical-align: middle;"></i>
                        <strong>Atención:</strong> Su servidor no cumple con todos los requisitos necesarios para continuar. Revise los elementos marcados en rojo en su archivo <code>php.ini</code> o asigne permisos a las carpetas señaladas.
                    </div>
                <?php endif; ?>
            </div>

            <!-- ==========================================
                 PASO 2: CONEXIÓN & POSTGRESQL
                 ========================================== -->
            <div class="step-pane" id="pane-2">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.25rem;">
                    <div>
                        <label class="ag-label">Host Servidor BD</label>
                        <input type="text" id="db_host" class="ag-input" value="localhost" placeholder="localhost o 127.0.0.1">
                    </div>
                    <div>
                        <label class="ag-label">Puerto PostgreSQL</label>
                        <input type="number" id="db_port" class="ag-input" value="5432" placeholder="5432">
                    </div>
                    <div>
                        <label class="ag-label">Usuario BD</label>
                        <input type="text" id="db_user" class="ag-input" value="postgres" placeholder="postgres">
                    </div>
                    <div>
                        <label class="ag-label">Contraseña BD</label>
                        <div class="input-group">
                            <input type="password" id="db_pass" class="ag-input" placeholder="••••••••">
                            <button type="button" class="btn-toggle-eye" onclick="togglePassVisibility('db_pass', this)">
                                <i class="ph-bold ph-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <label class="ag-label">Nombre de la Base de Datos</label>
                        <input type="text" id="db_name" class="ag-input" value="ciidi" placeholder="ciidi">
                    </div>
                </div>

                <div id="boxExistingTables" style="display: none; background: rgba(245, 158, 11, 0.08); border: 1px solid rgba(245, 158, 11, 0.35); border-radius: 8px; padding: 0.9rem; margin-bottom: 1.2rem; font-size: 0.83rem;">
                    <div style="display: flex; gap: 8px; align-items: flex-start; color: #b45309; font-weight: 700; margin-bottom: 0.4rem;">
                        <i class="ph-bold ph-warning" style="font-size: 1.15rem; margin-top: 1px;"></i>
                        <span id="txtExistingTablesTitle">Tablas preexistentes detectadas</span>
                    </div>
                    <p id="txtExistingTablesDesc" style="margin: 0 0 0.6rem 0; color: #78350f; line-height: 1.4;">
                        Esta base de datos ya contiene información. Seleccione cómo desea proceder:
                    </p>
                    <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; color: #78350f;">
                            <input type="radio" name="opt_bd_existente" value="conservar" checked>
                            <strong>Conservar tablas existentes</strong> (Reutilizar estructura y solo sincronizar credenciales)
                        </label>
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; color: #b91c1c;">
                            <input type="radio" name="opt_bd_existente" value="reinstalar" id="chkReinstalarLimpio">
                            <strong>Instalación limpia</strong> (Purgar y vaciar todas las tablas existentes para desplegar de cero)
                        </label>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 PASO 3: PARÁMETROS DEL SISTEMA & SERVICIOS
                 ========================================== -->
            <div class="step-pane" id="pane-3">
                <div style="max-height: 380px; overflow-y: auto; padding-right: 6px;">
                    <!-- TARJETA 1: IDENTIDAD Y RED -->
                    <div class="ag-card-config">
                        <div class="ag-card-title">
                            <i class="ph-bold ph-buildings" style="color: var(--color-secundario);"></i>
                            Identidad Institucional y Red
                        </div>
                        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 0.8rem; margin-bottom: 0.6rem;">
                            <div>
                                <label class="ag-label">Nombre de la Institución</label>
                                <input type="text" id="cfg_inst_nombre" class="ag-input" value="Universidad Politécnica Territorial de Trujillo Mario Briceño Iragorry">
                            </div>
                            <div>
                                <label class="ag-label">Siglas / Acrónimo</label>
                                <input type="text" id="cfg_inst_siglas" class="ag-input" value="UPTTMBI">
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 0.8rem;">
                            <div>
                                <label class="ag-label">URL Base de Acceso (APP_URL)</label>
                                <input type="text" id="cfg_app_url" class="ag-input" placeholder="http://localhost/proyect_CIIDI">
                            </div>
                            <div>
                                <label class="ag-label">Zona Horaria</label>
                                <select id="cfg_timezone" class="ag-select">
                                    <option value="America/Caracas" selected>America/Caracas (UTC-4)</option>
                                    <option value="America/Bogota">America/Bogota (UTC-5)</option>
                                    <option value="America/Mexico_City">America/Mexico_City (UTC-6)</option>
                                    <option value="America/Argentina/Buenos_Aires">America/Argentina (UTC-3)</option>
                                    <option value="Europe/Madrid">Europe/Madrid (UTC+1)</option>
                                    <option value="UTC">UTC (Universal)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- TARJETA 2: TAREAS PROGRAMADAS (SCHEDULER / CRON) -->
                    <div class="ag-card-config">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                            <div class="ag-card-title" style="margin: 0;">
                                <i class="ph-bold ph-clock-countdown" style="color: #059669;"></i>
                                Tareas Automatizadas (Scheduler & Cron)
                            </div>
                            <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 0.82rem; font-weight: 700; color: #059669;">
                                <input type="checkbox" id="chk_enable_scheduler" checked style="width: 16px; height: 16px;">
                                Habilitar Tareas Automáticas
                            </label>
                        </div>
                        <p style="margin: 0; font-size: 0.77rem; color: var(--texto-silenciado, #64748b); line-height: 1.4;">
                            Gestiona respaldos automáticos diarios de PostgreSQL a medianoche (.sql.gz), purgado de archivos temporales a las 3:00 AM y rotación de logs de auditoría.
                        </p>
                    </div>

                    <!-- TARJETA 3: SEGURIDAD DE SESIÓN Y WAF -->
                    <div class="ag-card-config">
                        <div class="ag-card-title">
                            <i class="ph-bold ph-shield-check" style="color: #4f46e5;"></i>
                            Seguridad de Sesiones y WAF
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.8rem;">
                            <div>
                                <label class="ag-label">Inactividad de Sesión</label>
                                <select id="cfg_session_timeout" class="ag-select">
                                    <option value="30">30 minutos</option>
                                    <option value="60">60 minutos</option>
                                    <option value="120" selected>120 min (Recomendado)</option>
                                    <option value="240">240 minutos</option>
                                </select>
                            </div>
                            <div>
                                <label class="ag-label">Intentos Fallidos (WAF)</label>
                                <select id="cfg_waf_attempts" class="ag-select">
                                    <option value="3">3 intentos</option>
                                    <option value="5" selected>5 intentos (Estándar)</option>
                                    <option value="10">10 intentos</option>
                                </select>
                            </div>
                            <div>
                                <label class="ag-label">Modo de Ejecución</label>
                                <select id="cfg_app_env" class="ag-select">
                                    <option value="production" selected>Producción (Seguro)</option>
                                    <option value="development">Desarrollo (Debug)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- TARJETA 4: NOTIFICACIONES POR CORREO (SMTP) -->
                    <div class="ag-card-config">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                            <div class="ag-card-title" style="margin: 0;">
                                <i class="ph-bold ph-envelope" style="color: #ea580c;"></i>
                                Notificaciones por Correo (SMTP)
                            </div>
                            <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 0.82rem; font-weight: 700; color: #ea580c;">
                                <input type="checkbox" id="chk_enable_smtp" onchange="toggleSmtpPanel(this.checked)" style="width: 16px; height: 16px;">
                                Configurar SMTP ahora (Opcional)
                            </label>
                        </div>
                        <p style="margin: 0 0 0.5rem 0; font-size: 0.77rem; color: var(--texto-silenciado, #64748b);">
                            Permite el restablecimiento de contraseñas de usuarios y el envío de notificaciones del repositorio. Si se omite, puede configurarse luego desde el panel SuperAdmin.
                        </p>

                        <!-- Panel Colapsable de Credenciales SMTP -->
                        <div id="panelSmtpCredentials" style="display: none; padding-top: 0.6rem; border-top: 1px solid rgba(80, 89, 132, 0.1);">
                            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 0.8rem; margin-bottom: 0.6rem;">
                                <div>
                                    <label class="ag-label">Servidor SMTP (Host)</label>
                                    <input type="text" id="smtp_host" class="ag-input" placeholder="smtp.gmail.com o mail.uptt.edu.ve">
                                </div>
                                <div>
                                    <label class="ag-label">Puerto SMTP</label>
                                    <input type="number" id="smtp_port" class="ag-input" value="587">
                                </div>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.8rem; margin-bottom: 0.6rem;">
                                <div>
                                    <label class="ag-label">Usuario / Correo Saliente</label>
                                    <input type="email" id="smtp_user" class="ag-input" placeholder="notificaciones@uptt.edu.ve">
                                </div>
                                <div>
                                    <label class="ag-label">Contraseña de Aplicación</label>
                                    <div class="input-group">
                                        <input type="password" id="smtp_pass" class="ag-input" placeholder="Contraseña de aplicación SMTP">
                                        <button type="button" class="btn-toggle-eye" onclick="togglePassVisibility('smtp_pass', this)">
                                            <i class="ph-bold ph-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.8rem; margin-bottom: 0.6rem;">
                                <div>
                                    <label class="ag-label">Correo Remitente (De:)</label>
                                    <input type="email" id="smtp_from_email" class="ag-input" placeholder="notificaciones@uptt.edu.ve">
                                </div>
                                <div>
                                    <label class="ag-label">Nombre del Remitente</label>
                                    <input type="text" id="smtp_from_name" class="ag-input" value="Sistema CIIDI - UPTTMBI">
                                </div>
                            </div>

                            <!-- Botón de Prueba SMTP -->
                            <div style="display: flex; gap: 0.5rem; align-items: center; margin-top: 0.5rem;">
                                <input type="email" id="smtp_test_to" class="ag-input" style="max-width: 250px;" placeholder="Destino de prueba">
                                <button type="button" id="btnTestSmtp" onclick="probarSmtp()" class="ag-btn ag-btn-secondary" style="height: 34px; font-size: 0.78rem;">
                                    <i class="ph-bold ph-paper-plane-tilt"></i> Probar Conexión SMTP
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 PASO 4: REGISTRO DEL SUPERADMIN
                 ========================================== -->
            <div class="step-pane" id="pane-4">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.25rem;">
                    <div>
                        <label class="ag-label">Cédula de Identidad</label>
                        <input type="number" min="1" id="adm_cedula" class="ag-input" placeholder="Ej: 12345678" onkeydown="if(['-','e','.'].includes(event.key)) event.preventDefault();">
                    </div>
                    <div>
                        <label class="ag-label">Nombre Completo</label>
                        <input type="text" id="adm_nombre" class="ag-input" placeholder="Ej: Administrador CIIDI">
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <label class="ag-label">Correo Electrónico Institucional</label>
                        <input type="email" id="adm_correo" class="ag-input" placeholder="admin@uptt.edu.ve">
                    </div>
                    <div>
                        <label class="ag-label">Contraseña Maestra</label>
                        <div class="input-group">
                            <input type="password" id="adm_clave" class="ag-input" placeholder="Mínimo 8 caracteres" oninput="evaluarFortalezaClave()">
                            <button type="button" class="btn-toggle-eye" onclick="togglePassVisibility('adm_clave', this)">
                                <i class="ph-bold ph-eye"></i>
                            </button>
                        </div>
                        <div id="barFortalezaClave" style="height: 4px; background: #e2e8f0; border-radius: 4px; margin-top: 5px; overflow: hidden;">
                            <div id="barFortalezaProgreso" style="width: 0%; height: 100%; transition: all 0.3s;"></div>
                        </div>
                    </div>
                    <div>
                        <label class="ag-label">Confirmar Contraseña</label>
                        <div class="input-group">
                            <input type="password" id="adm_clave_confirm" class="ag-input" placeholder="Repita la contraseña" oninput="evaluarCoincidenciaClave()">
                            <button type="button" class="btn-toggle-eye" onclick="togglePassVisibility('adm_clave_confirm', this)">
                                <i class="ph-bold ph-eye"></i>
                            </button>
                        </div>
                        <div id="lblCoincidenciaClave" style="font-size: 0.72rem; margin-top: 4px; display: none;"></div>
                    </div>
                </div>

                <!-- Telemetría de Despliegue en Vivo -->
                <div id="boxTelemetryDeploy" style="display: none; margin-top: 1rem;">
                    <div style="font-weight: 700; font-size: 0.85rem; color: var(--texto-titulos); margin-bottom: 0.6rem;">
                        Progreso del Despliegue Automatizado
                    </div>
                    <div id="telStep1" class="telemetry-item pending">
                        <i class="ph-bold ph-circle" id="telIcon1"></i>
                        <span>1. Conexión y verificación de PostgreSQL</span>
                    </div>
                    <div id="telStep2" class="telemetry-item pending">
                        <i class="ph-bold ph-circle" id="telIcon2"></i>
                        <span>2. Sincronización de variables (.env) y clave de cifrado</span>
                    </div>
                    <div id="telStep3" class="telemetry-item pending">
                        <i class="ph-bold ph-circle" id="telIcon3"></i>
                        <span>3. Configuración de parámetros del sistema y tareas programadas</span>
                    </div>
                    <div id="telStep4" class="telemetry-item pending">
                        <i class="ph-bold ph-circle" id="telIcon4"></i>
                        <span>4. Importación de infraestructura base (schema.sql)</span>
                    </div>
                    <div id="telStep5" class="telemetry-item pending">
                        <i class="ph-bold ph-circle" id="telIcon5"></i>
                        <span>5. Aplicación de migraciones de actualización</span>
                    </div>
                    <div id="telStep6" class="telemetry-item pending">
                        <i class="ph-bold ph-circle" id="telIcon6"></i>
                        <span>6. Registro del SuperAdmin con cifrado Bcrypt</span>
                    </div>
                    <div id="telStep7" class="telemetry-item pending">
                        <i class="ph-bold ph-circle" id="telIcon7"></i>
                        <span>7. Activación de candado de seguridad installed.lock</span>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 PASO 5: ÉXITO & FINALIZACIÓN
                 ========================================== -->
            <div class="step-pane" id="pane-5">
                <div style="text-align: center; padding: 1rem 0;">
                    <div style="width: 58px; height: 58px; border-radius: 50%; background: rgba(16,185,129,0.12); color: #059669; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 0.8rem auto;">
                        <i class="ph-bold ph-shield-check"></i>
                    </div>
                    <h2 style="font-size: 1.4rem; font-weight: 800; color: var(--texto-titulos); margin: 0 0 0.3rem 0;">
                        ¡Despliegue Institucional Completado!
                    </h2>
                    <p style="color: var(--texto-silenciado); font-size: 0.85rem; max-width: 480px; margin: 0 auto 1.2rem auto;">
                        El repositorio ha sido inicializado con éxito. Las bases de datos, parámetros del sistema y el candado de protección se encuentran operativos.
                    </p>

                    <div style="background: rgba(248, 250, 252, 0.9); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 10px; max-width: 480px; margin: 0 auto 1.2rem auto; padding: 0.9rem 1.1rem; text-align: left; font-size: 0.8rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.35rem;">
                            <span style="color: var(--texto-silenciado);">Base de Datos:</span>
                            <strong id="resSummaryDb">PostgreSQL</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.35rem;">
                            <span style="color: var(--texto-silenciado);">SuperAdmin:</span>
                            <strong id="resSummaryAdmin">-</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.35rem;">
                            <span style="color: var(--texto-silenciado);">Tareas Programadas:</span>
                            <strong id="resSummaryScheduler">Activas</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.35rem;">
                            <span style="color: var(--texto-silenciado);">Notificaciones SMTP:</span>
                            <strong id="resSummarySmtp">Configurado</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--texto-silenciado);">Candado de Seguridad:</span>
                            <span class="check-badge check-ok">installed.lock ACTIVO</span>
                        </div>
                    </div>

                    <!-- GUÍA DE AUTOMATIZACIÓN (CRONTAB / SCHTASKS) -->
                    <div style="background: rgba(80, 89, 132, 0.05); border: 1px solid rgba(80, 89, 132, 0.18); border-radius: 8px; max-width: 480px; margin: 0 auto; padding: 0.75rem 1rem; text-align: left; font-size: 0.76rem;">
                        <div style="font-weight: 700; color: var(--color-secundario); margin-bottom: 0.3rem; display: flex; align-items: center; gap: 5px;">
                            <i class="ph-bold ph-terminal-window"></i> Comando de Automatización para el Servidor:
                        </div>
                        <p style="margin: 0 0 0.4rem 0; color: #475569;">
                            Para ejecutar las tareas programadas automáticamente cada minuto, registre esta línea en el servidor:
                        </p>
                        <div style="background: #1e293b; color: #38bdf8; padding: 6px 10px; border-radius: 6px; font-family: monospace; font-size: 0.74rem; overflow-x: auto; white-space: nowrap;" id="txtCronCommand">
                            * * * * * php <?= addslashes(str_replace('\\', '/', dirname(__DIR__, 2) . '/storage/cron_runner.php')) ?> > /dev/null 2>&1
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BARRA INFERIOR DE ACCIONES -->
        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(80, 89, 132, 0.1); padding-top: 1.1rem; margin-top: 0.8rem;">
            <button type="button" id="btnPrevStep" onclick="navegarPaso(-1)" class="ag-btn ag-btn-secondary" style="display: none;">
                <i class="ph-bold ph-arrow-left"></i> Anterior
            </button>

            <div style="margin-left: auto; display: flex; gap: 0.5rem;">
                <button type="button" id="btnTestDb" onclick="testearBD()" class="ag-btn ag-btn-secondary" style="display: none;">
                    <i class="ph-bold ph-plugs-connected"></i> Probar Conexión
                </button>
                <button type="button" id="btnNextStep" onclick="navegarPaso(1)" class="ag-btn ag-btn-primary" <?= !$envCheck['ready'] ? 'disabled' : '' ?>>
                    Continuar <i class="ph-bold ph-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const envReady = <?= json_encode((bool)($envCheck['ready'] ?? false)) ?>;
let pasoActual = 1;
let bdVerificada = false;

const titulosPasos = [
    'Comprobación del Entorno',
    'Base de Datos PostgreSQL',
    'Parámetros del Sistema y Servicios',
    'Registro del SuperAdmin',
    'Despliegue Finalizado'
];

// Autodetectar URL base en JavaScript si está vacío
(function autoDetectAppUrl() {
    const urlInput = document.getElementById('cfg_app_url');
    if (urlInput && !urlInput.value) {
        const origin = window.location.origin;
        const pathname = window.location.pathname.replace(/\/index\.php$/, '').replace(/\/$/, '');
        urlInput.value = origin + pathname;
    }
})();

function togglePassVisibility(inputId, btn) {
    const inp = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.className = 'ph-bold ph-eye-slash';
    } else {
        inp.type = 'password';
        icon.className = 'ph-bold ph-eye';
    }
}

function toggleSmtpPanel(mostrar) {
    document.getElementById('panelSmtpCredentials').style.display = mostrar ? 'block' : 'none';
}

function mostrarAlerta(msg, tipo = 'error') {
    const box = document.getElementById('alertBoxInstaller');
    box.style.display = 'block';
    if (tipo === 'success') {
        box.style.background = 'rgba(16,185,129,0.12)';
        box.style.color = '#047857';
        box.style.border = '1px solid rgba(16,185,129,0.3)';
    } else if (tipo === 'warning') {
        box.style.background = 'rgba(245,158,11,0.12)';
        box.style.color = '#b45309';
        box.style.border = '1px solid rgba(245,158,11,0.3)';
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

function evaluarFortalezaClave() {
    const val = document.getElementById('adm_clave').value;
    const bar = document.getElementById('barFortalezaProgreso');
    if (!val) {
        bar.style.width = '0%';
        return;
    }

    let score = 0;
    if (val.length >= 8) score += 35;
    if (val.length >= 12) score += 15;
    if (/[A-Z]/.test(val)) score += 20;
    if (/[0-9]/.test(val)) score += 15;
    if (/[^A-Za-z0-9]/.test(val)) score += 15;

    bar.style.width = score + '%';
    if (score < 40) {
        bar.style.background = '#ef4444';
    } else if (score < 75) {
        bar.style.background = '#f59e0b';
    } else {
        bar.style.background = '#10b981';
    }

    evaluarCoincidenciaClave();
}

function evaluarCoincidenciaClave() {
    const clave = document.getElementById('adm_clave').value;
    const confirm = document.getElementById('adm_clave_confirm').value;
    const lbl = document.getElementById('lblCoincidenciaClave');

    if (!confirm) {
        lbl.style.display = 'none';
        return;
    }

    lbl.style.display = 'block';
    if (clave === confirm) {
        lbl.style.color = '#059669';
        lbl.innerHTML = '<i class="ph-bold ph-check"></i> Las contraseñas coinciden';
    } else {
        lbl.style.color = '#dc2626';
        lbl.innerHTML = '<i class="ph-bold ph-x"></i> Las contraseñas no coinciden';
    }
}

function irAlPaso(paso) {
    if (paso < 1 || paso > 5) return;
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

    document.getElementById('btnPrevStep').style.display = pasoActual > 1 && pasoActual < 5 ? 'inline-flex' : 'none';
    document.getElementById('btnTestDb').style.display = pasoActual === 2 ? 'inline-flex' : 'none';

    const btnNext = document.getElementById('btnNextStep');
    if (pasoActual === 1) {
        btnNext.disabled = !envReady;
        btnNext.innerHTML = 'Continuar <i class="ph-bold ph-arrow-right"></i>';
    } else if (pasoActual < 4) {
        btnNext.disabled = false;
        btnNext.innerHTML = 'Continuar <i class="ph-bold ph-arrow-right"></i>';
    } else if (pasoActual === 4) {
        btnNext.disabled = false;
        btnNext.innerHTML = '<i class="ph-bold ph-rocket-launch"></i> Ejecutar Instalación';
    } else if (pasoActual === 5) {
        btnNext.disabled = false;
        btnNext.innerHTML = 'Iniciar Sesión en CIIDI <i class="ph-bold ph-arrow-right"></i>';
        document.getElementById('btnPrevStep').style.display = 'none';
    }
}

function navegarPaso(dir) {
    if (dir === 1) {
        if (pasoActual === 1 && !envReady) {
            mostrarAlerta('No se puede avanzar: resuelva las dependencias señaladas en rojo.');
            return;
        }

        if (pasoActual === 2 && !bdVerificada) {
            mostrarAlerta('Se recomienda probar la conexión a la base de datos antes de continuar.', 'warning');
        }

        if (pasoActual === 4) {
            ejecutarInstalacionFinal();
            return;
        }

        if (pasoActual === 5) {
            window.location.href = 'login';
            return;
        }
    }

    irAlPaso(pasoActual + dir);
}

function testearBD() {
    ocultarAlerta();
    const btnTest = document.getElementById('btnTestDb');
    btnTest.disabled = true;
    btnTest.innerHTML = '<i class="ph-bold ph-spinner spin"></i> Probando...';

    const formData = new FormData();
    formData.append('host', document.getElementById('db_host').value.trim());
    formData.append('port', document.getElementById('db_port').value.trim());
    formData.append('user', document.getElementById('db_user').value.trim());
    formData.append('pass', document.getElementById('db_pass').value);
    formData.append('db', document.getElementById('db_name').value.trim());

    fetch('?ruta=installer-test-db', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        btnTest.disabled = false;
        btnTest.innerHTML = '<i class="ph-bold ph-plugs-connected"></i> Probar Conexión';

        if (data.exito) {
            bdVerificada = true;
            mostrarAlerta(data.mensaje, 'success');

            const boxTables = document.getElementById('boxExistingTables');
            if (data.tiene_tablas) {
                boxTables.style.display = 'block';
                document.getElementById('txtExistingTablesTitle').innerText = `Se detectaron ${data.num_tablas} tabla(s) existente(s) en la base de datos`;
            } else {
                boxTables.style.display = 'none';
            }
        } else {
            bdVerificada = false;
            mostrarAlerta(data.mensaje, 'error');
            document.getElementById('boxExistingTables').style.display = 'none';
        }
    })
    .catch(err => {
        btnTest.disabled = false;
        btnTest.innerHTML = '<i class="ph-bold ph-plugs-connected"></i> Probar Conexión';
        mostrarAlerta('Error de red al conectar con el servidor: ' + err, 'error');
    });
}

function probarSmtp() {
    ocultarAlerta();
    const btn = document.getElementById('btnTestSmtp');
    btn.disabled = true;
    btn.innerHTML = '<i class="ph-bold ph-spinner spin"></i> Probando SMTP...';

    const formData = new FormData();
    formData.append('smtp_host', document.getElementById('smtp_host').value.trim());
    formData.append('smtp_port', document.getElementById('smtp_port').value.trim());
    formData.append('smtp_user', document.getElementById('smtp_user').value.trim());
    formData.append('smtp_pass', document.getElementById('smtp_pass').value);
    formData.append('smtp_from_email', document.getElementById('smtp_from_email').value.trim());
    formData.append('smtp_test_to', document.getElementById('smtp_test_to').value.trim());

    fetch('?ruta=installer-test-smtp', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="ph-bold ph-paper-plane-tilt"></i> Probar Conexión SMTP';
        if (data.exito) {
            mostrarAlerta(data.mensaje, 'success');
        } else {
            mostrarAlerta(data.mensaje, 'error');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="ph-bold ph-paper-plane-tilt"></i> Probar Conexión SMTP';
        mostrarAlerta('Error al ejecutar prueba SMTP: ' + err, 'error');
    });
}

function setTelemetryStep(stepNum, status) {
    const item = document.getElementById(`telStep${stepNum}`);
    const icon = document.getElementById(`telIcon${stepNum}`);
    if (!item || !icon) return;

    item.className = `telemetry-item ${status}`;
    if (status === 'running') {
        icon.className = 'ph-bold ph-spinner spin';
    } else if (status === 'completed') {
        icon.className = 'ph-bold ph-check-circle';
    } else {
        icon.className = 'ph-bold ph-circle';
    }
}

function ejecutarInstalacionFinal() {
    ocultarAlerta();

    const cedula = document.getElementById('adm_cedula').value.trim();
    const nombre = document.getElementById('adm_nombre').value.trim();
    const correo = document.getElementById('adm_correo').value.trim();
    const clave = document.getElementById('adm_clave').value;
    const confirm = document.getElementById('adm_clave_confirm').value;

    if (!cedula || parseInt(cedula, 10) <= 0) {
        mostrarAlerta('Por favor ingrese un número de cédula válido para el Administrador.');
        return;
    }
    if (nombre.length < 3) {
        mostrarAlerta('Por favor ingrese el nombre completo del Administrador (mínimo 3 letras).');
        return;
    }
    if (!correo.includes('@') || !correo.includes('.')) {
        mostrarAlerta('Por favor ingrese una dirección de correo electrónico válida.');
        return;
    }
    if (clave.length < 8) {
        mostrarAlerta('La contraseña debe tener al menos 8 caracteres.');
        return;
    }
    if (clave !== confirm) {
        mostrarAlerta('Las contraseñas no coinciden. Por favor verifíquelas.');
        return;
    }

    const btnNext = document.getElementById('btnNextStep');
    const btnPrev = document.getElementById('btnPrevStep');
    btnNext.disabled = true;
    btnPrev.disabled = true;
    btnNext.innerHTML = '<i class="ph-bold ph-spinner spin"></i> Desplegando CIIDI...';

    document.getElementById('boxTelemetryDeploy').style.display = 'block';
    setTelemetryStep(1, 'running');

    const reinstalarLimpio = document.getElementById('chkReinstalarLimpio')?.checked ? 1 : 0;
    const enableScheduler = document.getElementById('chk_enable_scheduler').checked ? 1 : 0;
    const enableSmtp = document.getElementById('chk_enable_smtp').checked ? 1 : 0;

    const formData = new FormData();
    // BD
    formData.append('host', document.getElementById('db_host').value.trim());
    formData.append('port', document.getElementById('db_port').value.trim());
    formData.append('user', document.getElementById('db_user').value.trim());
    formData.append('pass', document.getElementById('db_pass').value);
    formData.append('db', document.getElementById('db_name').value.trim());
    formData.append('reinstalar', reinstalarLimpio);

    // Identidad y Entorno
    formData.append('inst_nombre', document.getElementById('cfg_inst_nombre').value.trim());
    formData.append('inst_siglas', document.getElementById('cfg_inst_siglas').value.trim());
    formData.append('app_url', document.getElementById('cfg_app_url').value.trim());
    formData.append('timezone', document.getElementById('cfg_timezone').value);
    formData.append('session_timeout', document.getElementById('cfg_session_timeout').value);
    formData.append('waf_attempts', document.getElementById('cfg_waf_attempts').value);
    formData.append('app_env', document.getElementById('cfg_app_env').value);

    // Tareas Programadas y SMTP
    formData.append('enable_scheduler', enableScheduler);
    formData.append('enable_smtp', enableSmtp);
    if (enableSmtp) {
        formData.append('smtp_host', document.getElementById('smtp_host').value.trim());
        formData.append('smtp_port', document.getElementById('smtp_port').value.trim());
        formData.append('smtp_user', document.getElementById('smtp_user').value.trim());
        formData.append('smtp_pass', document.getElementById('smtp_pass').value);
        formData.append('smtp_from_email', document.getElementById('smtp_from_email').value.trim());
        formData.append('smtp_from_name', document.getElementById('smtp_from_name').value.trim());
    }

    // SuperAdmin
    formData.append('admin_cedula', cedula);
    formData.append('admin_nombre', nombre);
    formData.append('admin_correo', correo);
    formData.append('admin_clave', clave);
    formData.append('admin_clave_confirm', confirm);

    setTimeout(() => { setTelemetryStep(1, 'completed'); setTelemetryStep(2, 'running'); }, 500);
    setTimeout(() => { setTelemetryStep(2, 'completed'); setTelemetryStep(3, 'running'); }, 1100);
    setTimeout(() => { setTelemetryStep(3, 'completed'); setTelemetryStep(4, 'running'); }, 1800);

    fetch('?ruta=installer-run', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.text())
    .then(text => {
        let data;
        try {
            data = JSON.parse(text);
        } catch(e) {
            console.error("Respuesta no-JSON recibida del servidor:", text);
            btnNext.disabled = false;
            btnPrev.disabled = false;
            btnNext.innerHTML = '<i class="ph-bold ph-rocket-launch"></i> Reintentar Instalación';
            mostrarAlerta("Error en el servidor: " + text.replace(/<[^>]*>?/gm, '').substring(0, 300), 'error');
            return;
        }

        if (data.exito) {
            setTelemetryStep(4, 'completed');
            setTelemetryStep(5, 'completed');
            setTelemetryStep(6, 'completed');
            setTelemetryStep(7, 'completed');

            document.getElementById('resSummaryDb').innerText = `${document.getElementById('db_name').value} (${document.getElementById('db_host').value})`;
            document.getElementById('resSummaryAdmin').innerText = `${nombre} (${correo})`;
            document.getElementById('resSummaryScheduler').innerText = enableScheduler ? 'Habilitadas (Diarias y Semanales)' : 'Inactivas';
            document.getElementById('resSummarySmtp').innerText = enableSmtp ? 'Configurado con cifrado AES-256' : 'Omitido (Gestionable en SuperAdmin)';

            setTimeout(() => {
                irAlPaso(5);
            }, 800);
        } else {
            btnNext.disabled = false;
            btnPrev.disabled = false;
            btnNext.innerHTML = '<i class="ph-bold ph-rocket-launch"></i> Reintentar Instalación';
            mostrarAlerta(data.mensaje, 'error');
        }
    })
    .catch(err => {
        btnNext.disabled = false;
        btnPrev.disabled = false;
        btnNext.innerHTML = '<i class="ph-bold ph-rocket-launch"></i> Reintentar Instalación';
        mostrarAlerta('Error durante el despliegue del sistema: ' + err, 'error');
    });
}

// CANVAS DE FONDO DE NODOS ANIMADOS AZUL CLARO
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
            this.vx = (Math.random() - 0.5) * 0.5;
            this.vy = (Math.random() - 0.5) * 0.5;
            this.radius = Math.random() * 3 + 2;
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
            ctx.fillStyle = 'rgba(112, 144, 203, 0.55)';
            ctx.fill();
        }
    }

    const numParticles = Math.min(Math.floor(width / 16), 50);
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
                if (dist < 150) {
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.strokeStyle = `rgba(112, 144, 203, ${0.3 * (1 - dist / 150)})`;
                    ctx.lineWidth = 1.1;
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
