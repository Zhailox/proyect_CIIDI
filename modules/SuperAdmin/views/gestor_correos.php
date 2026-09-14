<div class="sa-hero-header mb-2" style="background: #ffffff !important; border: 1px solid rgba(80, 89, 132, 0.18); padding: 1.5rem 2rem; border-radius: var(--radius-md); box-shadow: 0 4px 20px rgba(18, 26, 62, 0.04);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-terciario) !important; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 0.3rem;">
                <i class="ph-bold ph-paper-plane-tilt"></i> INFRAESTRUCTURA DE NOTIFICACIONES Y PLANTILLAS
            </div>
            <h1 style="font-size: 1.8rem; font-weight: 800; color: #121a3e !important; margin: 0;">
                Gestor de Correos SMTP & Plantillas
            </h1>
            <p style="margin: 0.3rem 0 0 0; color: #64748b !important; font-size: 0.9rem;">
                Administración de credenciales de envío, diagnóstico en vivo, layout institucional base y edición de plantillas por evento.
            </p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="sudoadmin" class="btn btn-outline" style="border-color: var(--color-secundario); color: var(--color-secundario) !important; text-decoration: none; padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 0.85rem;">
                <i class="ph-bold ph-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </div>
</div>

<?php if (!empty($mensajeExito)): ?>
    <div style="background: rgba(16,185,129,0.12); color: #047857; border: 1px solid rgba(16,185,129,0.3); padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
        <i class="ph-bold ph-check-circle" style="font-size: 1.2rem;"></i> <?= htmlspecialchars($mensajeExito) ?>
    </div>
<?php endif; ?>

<?php if (!empty($mensajeError)): ?>
    <div style="background: rgba(239,68,68,0.12); color: #b91c1c; border: 1px solid rgba(239,68,68,0.3); padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
        <i class="ph-bold ph-warning-circle" style="font-size: 1.2rem;"></i> <?= htmlspecialchars($mensajeError) ?>
    </div>
<?php endif; ?>

<!-- TABS SUPERADMIN DE CORREOS -->
<div class="sa-tabs-header glass-panel mb-2" style="background: #ffffff; padding: 8px; border-radius: var(--radius-sm); border: 1px solid rgba(80,89,132,0.15);">
    <button type="button" class="sa-tab-btn tab-active" onclick="switchMailTab('tabConexionSmtp', this)">
        <i class="ph-bold ph-envelope-simple"></i> Conexión & Credenciales SMTP
    </button>
    <button type="button" class="sa-tab-btn" onclick="switchMailTab('tabPruebaLive', this)">
        <i class="ph-bold ph-paper-plane-right"></i> Diagnóstico / Correo de Prueba
    </button>
    <button type="button" class="sa-tab-btn" onclick="switchMailTab('tabLayoutBase', this)">
        <i class="ph-bold ph-paint-brush"></i> Layout Institucional Base
    </button>
    <button type="button" class="sa-tab-btn" onclick="switchMailTab('tabPlantillas', this)">
        <i class="ph-bold ph-layout"></i> Catálogo de Plantillas de Correo
    </button>
</div>

<!-- TAB 1: CONEXIÓN & CREDENCIALES SMTP -->
<div id="tabConexionSmtp" class="sa-tab-content active">
    <div class="glass-panel" style="padding: 1.5rem; border-radius: var(--radius-sm); background:#ffffff;">
        <h4 style="margin: 0 0 0.5rem 0; font-size: 1rem; font-weight: 700; color: var(--texto-titulos); display: flex; align-items: center; gap: 6px;">
            <i class="ph-bold ph-gear" style="color: var(--color-terciario);"></i> Ajustes del Servidor SMTP Institucional
        </h4>
        <p style="font-size: 0.85rem; color: var(--texto-silenciado); margin-bottom: 1.25rem;">
            Credenciales utilizadas para el envío masivo e individual de notificaciones en la plataforma.
        </p>
        
        <form action="guardar-configuracion-sistema" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Servidor Host (SMTP)</label>
                    <input type="text" name="smtp_host" value="<?= htmlspecialchars($config['smtp']['host'] ?? '') ?>" placeholder="smtp.gmail.com / smtp.office365.com" class="sa-filter-input" style="width: 100%;">
                </div>
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Puerto (TLS / SSL)</label>
                    <input type="number" name="smtp_port" value="<?= (int)($config['smtp']['port'] ?? 587) ?>" class="sa-filter-input" style="width: 100%;">
                </div>
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Usuario Authenticated Email</label>
                    <input type="email" name="smtp_user" value="<?= htmlspecialchars($config['smtp']['user'] ?? '') ?>" placeholder="ciidi@upttmbi.edu.ve" class="sa-filter-input" style="width: 100%;">
                </div>
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Contraseña de Aplicación</label>
                    <input type="password" name="smtp_pass" placeholder="Dejar vacío para conservar actual" class="sa-filter-input" style="width: 100%;">
                </div>
                <div style="grid-column: 1 / -1;">
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Email Remitente Público (Header From)</label>
                    <input type="email" name="smtp_from" value="<?= htmlspecialchars($config['smtp']['from_email'] ?? '') ?>" placeholder="no-reply@upttmbi.edu.ve" class="sa-filter-input" style="width: 100%;">
                </div>
            </div>

            <button type="submit" class="btn" style="background-color: #2563eb !important; color: #ffffff !important; border:none; padding: 12px 24px; border-radius: 6px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-floppy-disk" style="color: #ffffff !important;"></i> <span style="color: #ffffff !important;">Guardar Credenciales SMTP</span>
            </button>
        </form>
    </div>
</div>

<!-- TAB 2: DIAGNÓSTICO EN VIVO / CORREO DE PRUEBA -->
<div id="tabPruebaLive" class="sa-tab-content" style="display:none;">
    <div class="glass-panel" style="padding: 1.5rem; border-radius: var(--radius-sm); background:#ffffff;">
        <h4 style="margin: 0 0 0.5rem 0; font-size: 1rem; font-weight: 700; color: var(--texto-titulos); display: flex; align-items: center; gap: 6px;">
            <i class="ph-bold ph-paper-plane-right" style="color: var(--color-terciario);"></i> Probar Enlace SMTP en Tiempo Real
        </h4>
        <p style="font-size: 0.85rem; color: var(--texto-silenciado); margin-bottom: 1.25rem;">
            Envía una notificación de diagnóstico o prueba una plantilla específica para comprobar conectividad y maquetación.
        </p>

        <form action="probar-smtp" method="POST" style="max-width: 550px;">
            <div style="margin-bottom: 1.25rem;">
                <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Seleccionar Tipo de Prueba:</label>
                <select name="template_key" class="sa-filter-input" style="width: 100%;">
                    <option value="">-- Correo Genérico de Diagnóstico (Prueba Estándar) --</option>
                    <?php foreach ($plantillas as $key => $tpl): ?>
                        <option value="<?= htmlspecialchars($key) ?>">Probar Plantilla: <?= htmlspecialchars($tpl['nombre']) ?> (<?= htmlspecialchars($key) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Correo Destino de Prueba:</label>
                <input type="email" name="email_prueba" required placeholder="tu_correo@ejemplo.com" class="sa-filter-input" style="width: 100%;">
            </div>

            <button type="submit" class="btn" style="background-color: #2563eb !important; color: #ffffff !important; border:none; padding: 12px 24px; border-radius: 6px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-paper-plane" style="color: #ffffff !important;"></i> <span style="color: #ffffff !important;">Enviar Correo de Diagnóstico</span>
            </button>
        </form>
    </div>
</div>

<!-- TAB 3: GESTIÓN DE LAYOUT INSTITUCIONAL BASE -->
<div id="tabLayoutBase" class="sa-tab-content" style="display:none;">
    <div class="glass-panel" style="padding: 1.5rem; border-radius: var(--radius-sm); background:#ffffff;">
        <h4 style="margin: 0 0 0.5rem 0; font-size: 1rem; font-weight: 700; color: var(--texto-titulos); display: flex; align-items: center; gap: 6px;">
            <i class="ph-bold ph-paint-brush" style="color: var(--color-terciario);"></i> Plantilla / Envoltorio Institucional Base
        </h4>
        <p style="font-size: 0.85rem; color: var(--texto-silenciado); margin-bottom: 1.25rem;">
            Modifique la estructura HTML global (Encabezado institucional, logos, pie de página y colores) que envuelve a todas las plantillas del sistema.
        </p>

        <?php
        $layoutActual = $config['email_layout']['html_wrapper'] ?? "
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>{TITULO}</title>
</head>
<body style='margin:0; padding:0; background-color:#f4f7fb; font-family: Arial, Helvetica, sans-serif; color:#334155;'>
    <table role='presentation' width='100%' cellspacing='0' cellpadding='0' style='background-color:#f4f7fb; padding: 20px 0;'>
        <tr>
            <td align='center'>
                <table role='presentation' width='100%' style='max-width: 600px; background-color:#ffffff; border-radius: 12px; overflow:hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;'>
                    
                    <!-- HEADER INSTITUCIONAL -->
                    <tr>
                        <td style='background: linear-gradient(135deg, #121a3e 0%, #1e293b 100%); padding: 30px 20px; text-align: center; color: #ffffff;'>
                            <div style='font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: #f59e0b; margin-bottom: 6px;'>
                                UPTTMBI - VALERA, TRUJILLO
                            </div>
                            <h1 style='margin:0; font-size: 22px; font-weight: 800; letter-spacing: 0.5px; color: #ffffff;'>
                                SISTEMA INTEGRAL CIIDI
                            </h1>
                        </td>
                    </tr>

                    <!-- CONTENIDO -->
                    <tr>
                        <td style='padding: 35px 30px; background-color: #ffffff;'>
                            {CUERPO}
                        </td>
                    </tr>

                    <!-- FOOTER INSTITUCIONAL -->
                    <tr>
                        <td style='background-color: #f8fafc; padding: 20px 30px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 12px; color: #64748b;'>
                            <p style='margin: 0 0 6px 0; font-weight: 600; color: #475569;'>
                                Universidad Politécnica Territorial del Estado Trujillo \"Mario Briceño Iragorry\"
                            </p>
                            <p style='margin: 0;'>
                                &copy; {YEAR} CIIDI. Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>";
        ?>

        <form action="guardar-layout-correo" method="POST">
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 14px; margin-bottom: 1.25rem;">
                <label style="font-size: 0.75rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Variables del Contenedor (Haz clic para insertar):
                </label>
                <div style="display: flex; gap: 8px;">
                    <button type="button" onclick="insertarVariable('area-html-wrapper', '{TITULO}')" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 8px; font-size: 0.75rem; font-weight: 700; color: #2563eb !important; font-family: monospace; cursor: pointer;">+{TITULO}</button>
                    <button type="button" onclick="insertarVariable('area-html-wrapper', '{CUERPO}')" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 8px; font-size: 0.75rem; font-weight: 700; color: #2563eb !important; font-family: monospace; cursor: pointer;">+{CUERPO}</button>
                    <button type="button" onclick="insertarVariable('area-html-wrapper', '{YEAR}')" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 8px; font-size: 0.75rem; font-weight: 700; color: #2563eb !important; font-family: monospace; cursor: pointer;">+{YEAR}</button>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Estructura HTML del Envoltorio Institucional:</label>
                <textarea id="area-html-wrapper" name="html_wrapper" rows="18" required class="sa-filter-input" style="width: 100%; font-family: monospace; font-size: 0.85rem; line-height: 1.5; padding: 12px;"><?= htmlspecialchars($layoutActual) ?></textarea>
            </div>

            <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                <button type="submit" class="btn" style="background-color: #2563eb !important; color: #ffffff !important; border:none; padding: 12px 24px; border-radius: 6px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="ph-bold ph-floppy-disk" style="color: #ffffff !important;"></i> <span style="color: #ffffff !important;">Guardar Layout Institucional</span>
                </button>

                <button type="button" onclick="abrirModalPreviewLayout()" class="btn" style="background: #ffffff; color: #1e293b !important; border: 1px solid #cbd5e1; padding: 12px 20px; border-radius: 6px; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="ph-bold ph-eye" style="color: #1e293b !important;"></i> <span style="color: #1e293b !important;">Vista Previa del Layout</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- TAB 4: CATÁLOGO DE PLANTILLAS DINÁMICAS POR MÓDULO -->
<div id="tabPlantillas" class="sa-tab-content" style="display:none;">
    <div style="display: grid; grid-template-columns: 320px 1fr; gap: 1.5rem;">
        
        <!-- SIDEBAR DE SELECCIÓN DE PLANTILLAS -->
        <div class="glass-panel" style="padding: 1rem; border-radius: var(--radius-sm); background:#ffffff;">
            <h5 style="margin: 0 0 0.75rem 0; font-size: 0.9rem; font-weight: 700; color: var(--texto-titulos);">
                Plantillas por Evento
            </h5>
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <?php foreach ($plantillas as $key => $tpl): 
                    $estaActivo = $tpl['activo'] ?? true;
                ?>
                    <button type="button" onclick="seleccionarPlantilla('<?= $key ?>')" class="tpl-item-btn" id="btn-tpl-<?= str_replace('.', '-', $key) ?>" style="text-align: left; padding: 10px 12px; border-radius: 6px; border: 1px solid #e2e8f0; background: #f8fafc; cursor: pointer; transition: all 0.2s; position: relative;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                            <span style="font-size: 0.75rem; font-weight: 700; color: var(--color-terciario); text-transform: uppercase;">
                                <?= htmlspecialchars($tpl['modulo'] ?? 'Módulo') ?>
                            </span>
                            <span style="font-size: 0.65rem; font-weight: 800; padding: 2px 6px; border-radius: 4px; <?= $estaActivo ? 'background:#d1fae5; color:#047857;' : 'background:#fee2e2; color:#b91c1c;' ?>">
                                <?= $estaActivo ? 'ACTIVA' : 'INHABILITADA' ?>
                            </span>
                        </div>
                        <div style="font-size: 0.85rem; font-weight: 700; color: #1e293b;">
                            <?= htmlspecialchars($tpl['nombre'] ?? $key) ?>
                        </div>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- EDITOR Y PANEL DE CONTROL DE LA PLANTILLA -->
        <div class="glass-panel" style="padding: 1.5rem; border-radius: var(--radius-sm); background:#ffffff;">
            
            <?php foreach ($plantillas as $key => $tpl): 
                $estaActivo = $tpl['activo'] ?? true;
                $esCompleta = $tpl['plantilla_completa'] ?? false;
            ?>
                <div id="editor-tpl-<?= str_replace('.', '-', $key) ?>" class="tpl-editor-panel" style="display: none;">
                    
                    <form action="guardar-plantilla-correo" method="POST">
                        <input type="hidden" name="template_key" value="<?= htmlspecialchars($key) ?>">

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.75rem;">
                            <div>
                                <span style="font-size: 0.75rem; font-weight: 700; color: var(--color-terciario); text-transform: uppercase;">
                                    <?= htmlspecialchars($tpl['modulo']) ?> &bull; Clave del Evento: <?= htmlspecialchars($key) ?>
                                </span>
                                <h3 style="margin: 2px 0 0 0; font-size: 1.2rem; font-weight: 800; color: #121a3e;">
                                    <?= htmlspecialchars($tpl['nombre']) ?>
                                </h3>
                            </div>
                            <button type="button" onclick="abrirModalPreview('<?= str_replace('.', '-', $key) ?>')" class="btn" style="background: #ffffff; color: #1e293b !important; border: 1px solid #cbd5e1; padding: 8px 14px; border-radius: 6px; font-weight: 700; font-size: 0.8rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="ph-bold ph-eye" style="color: #1e293b !important;"></i> <span style="color: #1e293b !important;">Vista Previa</span>
                            </button>
                        </div>

                        <!-- PANEL DE SWITCHES DE ESTADO -->
                        <div style="display: flex; gap: 1.5rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px 16px; margin-bottom: 1.25rem;">
                            <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 700; color: #1e293b; cursor: pointer;">
                                <input type="checkbox" name="activo" value="1" <?= $estaActivo ? 'checked' : '' ?> style="width: 16px; height: 16px;">
                                Habilitar Envío de esta Plantilla
                            </label>

                            <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 700; color: #1e293b; cursor: pointer;">
                                <input type="checkbox" name="plantilla_completa" id="check-completa-<?= str_replace('.', '-', $key) ?>" value="1" <?= $esCompleta ? 'checked' : '' ?> style="width: 16px; height: 16px;">
                                Editar Documento HTML Completo (Omitir Layout Base)
                            </label>
                        </div>

                        <!-- CAMPOS EDITABLES METADATOS: NOMBRE Y DESCRIPCIÓN DE PLANTILLA -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem; margin-bottom: 1.25rem;">
                            <div>
                                <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Nombre / Título de la Plantilla:</label>
                                <input type="text" name="nombre" value="<?= htmlspecialchars($tpl['nombre'] ?? '') ?>" required class="sa-filter-input" style="width: 100%;">
                            </div>
                            <div>
                                <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Descripción del Evento:</label>
                                <input type="text" name="descripcion" value="<?= htmlspecialchars($tpl['descripcion'] ?? '') ?>" required class="sa-filter-input" style="width: 100%;">
                            </div>
                        </div>

                        <!-- VARIABLES DINÁMICAS / PLACEHOLDERS COPIABLES -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 14px; margin-bottom: 1.25rem;">
                            <label style="font-size: 0.75rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">
                                Variables Dinámicas Disponibles (Haz clic para insertar):
                            </label>
                            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                <?php foreach ($tpl['variables'] as $var): ?>
                                    <button type="button" onclick="insertarVariable('area-tpl-<?= str_replace('.', '-', $key) ?>', '{<?= $var ?>}')" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 5px 10px; font-size: 0.75rem; font-weight: 700; color: #1e293b !important; cursor: pointer; font-family: monospace;">
                                        <span style="color: #1e293b !important;">+{<?= htmlspecialchars($var) ?>}</span>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div style="margin-bottom: 1.25rem;">
                            <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Asunto del Correo:</label>
                            <input type="text" name="asunto" value="<?= htmlspecialchars($tpl['asunto']) ?>" required class="sa-filter-input" style="width: 100%;">
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Código HTML de la Plantilla:</label>
                            <textarea id="area-tpl-<?= str_replace('.', '-', $key) ?>" name="cuerpo_html" rows="14" required class="sa-filter-input" style="width: 100%; font-family: monospace; font-size: 0.85rem; line-height: 1.5; padding: 12px;"><?= htmlspecialchars($tpl['cuerpo_html']) ?></textarea>
                        </div>

                        <button type="submit" class="btn" style="background-color: #2563eb !important; color: #ffffff !important; border:none; padding: 12px 24px; border-radius: 6px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="ph-bold ph-floppy-disk" style="color: #ffffff !important;"></i> <span style="color: #ffffff !important;">Guardar Cambios en Plantilla</span>
                        </button>
                    </form>

                </div>
            <?php endforeach; ?>

        </div>
    </div>
</div>

<!-- MODAL VISTA PREVIA VIRTUAL HORMIGÓN/RESPONSIVE -->
<div id="modalPreviewCorreo" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(18,26,62,0.6); backdrop-filter: blur(6px); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: #ffffff; width: 90%; max-width: 700px; max-height: 90vh; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
        <div style="background: #121a3e; color: #ffffff; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center;">
            <div style="font-weight: 700; font-size: 0.95rem; display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-eye"></i> Vista Previa de Renderizado Institucional
            </div>
            <button onclick="cerrarModalPreview()" style="background: none; border: none; color: #ffffff; font-size: 1.4rem; cursor: pointer;">&times;</button>
        </div>
        <div style="padding: 20px; overflow-y: auto; flex: 1; background: #f4f7fb;">
            <iframe id="iframePreviewCorreo" style="width: 100%; height: 500px; border: none; background: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);"></iframe>
        </div>
    </div>
</div>

<script>
function switchMailTab(tabId, btn) {
    document.querySelectorAll('#tabConexionSmtp, #tabPruebaLive, #tabLayoutBase, #tabPlantillas').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.sa-tab-btn').forEach(b => b.classList.remove('tab-active'));
    document.getElementById(tabId).style.display = 'block';
    btn.classList.add('tab-active');
}

function seleccionarPlantilla(key) {
    const safeKey = key.replace(/\./g, '-');
    document.querySelectorAll('.tpl-editor-panel').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.tpl-item-btn').forEach(b => b.style.background = '#f8fafc');
    
    const targetEditor = document.getElementById('editor-tpl-' + safeKey);
    const targetBtn = document.getElementById('btn-tpl-' + safeKey);

    if (targetEditor) targetEditor.style.display = 'block';
    if (targetBtn) targetBtn.style.background = '#e2e8f0';
}

function insertarVariable(textareaId, variableStr) {
    const area = document.getElementById(textareaId);
    if (!area) return;
    const start = area.selectionStart;
    const end = area.selectionEnd;
    const text = area.value;
    area.value = text.substring(0, start) + variableStr + text.substring(end);
    area.selectionStart = area.selectionEnd = start + variableStr.length;
    area.focus();
}

function abrirModalPreviewLayout() {
    const areaWrapper = document.getElementById('area-html-wrapper');
    if (!areaWrapper) return;

    let layoutWrapper = areaWrapper.value;
    const sampleBody = `
        <h3 style="color:#121a3e; margin-top:0; font-weight:800; font-size: 1.1rem;">Ejemplo de Notificación Institucional</h3>
        <p style="color:#475569; line-height:1.6; font-size: 0.9rem;">Este es un mensaje de prueba para verificar el renderizado del Envoltorio Base Institucional (encabezado, cuerpo, footer y estilos CSS).</p>
        <div style="background-color:#eff6ff; border-left:4px solid #2563eb; padding:14px; border-radius:6px; margin:16px 0; color:#1e40af; font-size:0.85rem;">
            <strong>Demostración:</strong> El contenido enviado por cualquier módulo del sistema CIIDI se renderizará automáticamente en este espacio.
        </div>
        <p style="color:#64748b; font-size:0.8rem; margin-bottom: 0;">Atentamente,<br><strong>Equipo de Desarrollo CIIDI UPTTMBI</strong></p>
    `;

    let htmlRenderizado = layoutWrapper
        .replace(/{TITULO}/g, 'Vista Previa del Layout Institucional')
        .replace(/{CUERPO}/g, sampleBody)
        .replace(/{YEAR}/g, new Date().getFullYear());

    const iframe = document.getElementById('iframePreviewCorreo');
    const modal = document.getElementById('modalPreviewCorreo');
    modal.style.display = 'flex';

    const doc = iframe.contentWindow.document;
    doc.open();
    doc.write(htmlRenderizado);
    doc.close();

    setTimeout(() => {
        try {
            const links = iframe.contentWindow.document.querySelectorAll('a');
            links.forEach(link => {
                link.setAttribute('href', 'javascript:void(0);');
                link.setAttribute('target', '_self');
                link.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                };
            });
        } catch(err){}
    }, 100);
}

function abrirModalPreview(safeKey) {
    const area = document.getElementById('area-tpl-' + safeKey);
    const checkCompleta = document.getElementById('check-completa-' + safeKey);
    const wrapperArea = document.getElementById('area-html-wrapper');
    if (!area) return;

    const cuerpoHtml = area.value;
    const esCompleta = checkCompleta ? checkCompleta.checked : false;

    let plantillaCompleta = '';
    if (esCompleta) {
        plantillaCompleta = cuerpoHtml;
    } else {
        const layoutWrapper = wrapperArea ? wrapperArea.value : '';
        if (layoutWrapper) {
            plantillaCompleta = layoutWrapper
                .replace('{TITULO}', 'Vista Previa del Correo')
                .replace('{CUERPO}', cuerpoHtml)
                .replace('{YEAR}', new Date().getFullYear());
        } else {
            plantillaCompleta = cuerpoHtml;
        }
    }

    const iframe = document.getElementById('iframePreviewCorreo');
    const modal = document.getElementById('modalPreviewCorreo');
    modal.style.display = 'flex';

    // Inyectamos el HTML al iframe e inhabilitamos el comportamiento por defecto de enlaces para evitar navegación
    const doc = iframe.contentWindow.document;
    doc.open();
    doc.write(plantillaCompleta);
    doc.close();

    // Desactivar click en enlaces internos para que la vista previa no navegue a otras páginas
    setTimeout(() => {
        try {
            const links = iframe.contentWindow.document.querySelectorAll('a');
            links.forEach(link => {
                link.setAttribute('href', 'javascript:void(0);');
                link.setAttribute('target', '_self');
                link.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                };
            });
        } catch(err){}
    }, 100);
}

function cerrarModalPreview() {
    document.getElementById('modalPreviewCorreo').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', () => {
    const primerBtn = document.querySelector('.tpl-item-btn');
    if (primerBtn) primerBtn.click();
});
</script>
