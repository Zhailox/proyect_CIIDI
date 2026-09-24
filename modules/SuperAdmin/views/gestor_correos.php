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
            <a href="sudoadmin" class="sa-btn sa-btn-outline">
                <i class="ph-bold ph-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </div>
</div>

<?php if (!empty($mensajeExito)): ?>
    <div class="sa-alert sa-alert-success">
        <i class="ph-bold ph-check-circle"></i> <?= htmlspecialchars($mensajeExito) ?>
    </div>
<?php endif; ?>

<?php if (!empty($mensajeError)): ?>
    <div class="sa-alert sa-alert-error">
        <i class="ph-bold ph-warning-circle"></i> <?= htmlspecialchars($mensajeError) ?>
    </div>
<?php endif; ?>

<!-- TABS SUPERADMIN DE CORREOS -->
<div class="sa-tabs-header glass-panel mb-2" style="background: #ffffff; padding: 6px 8px; border-radius: var(--radius-sm); border: 1px solid rgba(80,89,132,0.15); display: flex; flex-wrap: wrap; gap: 6px; box-shadow: 0 2px 8px rgba(18, 26, 62, 0.04);">
    <button type="button" class="sa-tab-btn tab-active" onclick="switchMailTab('tabCorreoDirecto', this)">
        <i class="ph-bold ph-paper-plane-tilt"></i> Enviar Correo Directo
    </button>
    <button type="button" class="sa-tab-btn" onclick="switchMailTab('tabHistorialLogs', this)">
        <i class="ph-bold ph-clock-counter-clockwise"></i> Historial de Envíos
    </button>
    <button type="button" class="sa-tab-btn" onclick="switchMailTab('tabConexionSmtp', this)">
        <i class="ph-bold ph-gear"></i> Credenciales & Diagnóstico SMTP
    </button>
    <button type="button" class="sa-tab-btn" onclick="switchMailTab('tabLayoutBase', this)">
        <i class="ph-bold ph-paint-brush"></i> Layout Base Institucional
    </button>
    <button type="button" class="sa-tab-btn" onclick="switchMailTab('tabPlantillas', this)">
        <i class="ph-bold ph-layout"></i> Catálogo de Plantillas
    </button>
</div>

<!-- TAB 0: ENVIAR CORREO DIRECTO / COMUNICADO (LAYOUT 2 COLUMNAS CON SIDEBAR DE PLANTILLAS REUTILIZABLES) -->
<div id="tabCorreoDirecto" class="sa-tab-content active">
    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem; align-items: start;">
        
        <!-- COLUMNA IZQUIERDA: FORMULARIO PRINCIPAL DE COMPOSICIÓN -->
        <div class="glass-panel" style="padding: 1.5rem; border-radius: var(--radius-sm); background:#ffffff;">
            <div style="margin-bottom: 1.25rem;">
                <h4 style="margin: 0 0 0.3rem 0; font-size: 1.1rem; font-weight: 800; color: #121a3e; display: flex; align-items: center; gap: 6px;">
                    <i class="ph-bold ph-paper-plane-tilt" style="color: var(--color-secundario);"></i> Componer y Enviar Correo Directo
                </h4>
                <p style="font-size: 0.85rem; color: #64748b; margin: 0;">
                    Redacte comunicados o mensajes personalizados agregando múltiples destinatarios fácilmente.
                </p>
            </div>

            <form action="enviar-correo-directo" method="POST" id="formCorreoDirecto" onsubmit="sincronizarCuerpoOculto();">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                <!-- SELECCIÓN DE TIPO DE DESTINATARIO -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.25rem; margin-bottom: 1.25rem;">
                    <label style="font-size: 0.85rem; font-weight: 700; color: #1e293b; display: block; margin-bottom: 10px;">
                        ¿A quién va dirigido este correo?
                    </label>
                    <div style="display: flex; gap: 1.5rem; margin-bottom: 1rem; flex-wrap: wrap;">
                        <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.85rem; font-weight: 600; color: #334155; cursor: pointer;">
                            <input type="radio" name="modo_destino" value="individual" checked onclick="toggleModoDestino('individual')" style="width: 16px; height: 16px;">
                            Selección Interactiva / Multi-Destinatario
                        </label>
                        <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.85rem; font-weight: 600; color: #334155; cursor: pointer;">
                            <input type="radio" name="modo_destino" value="rol" onclick="toggleModoDestino('rol')" style="width: 16px; height: 16px;">
                            Grupo / Rol Completo de Usuarios
                        </label>
                    </div>

                    <!-- CAMPO INDIVIDUAL INTERACTIVO (BÚSQUEDA AJAX AUTOCOMPLETE + CHIPS) -->
                    <div id="campo-destino-individual">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                            <!-- BUSCADOR CON SUGERENCIAS DESPLEGABLES -->
                            <div style="position: relative;">
                                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">
                                    <i class="ph-bold ph-magnifying-glass"></i> Buscar Usuario (Nombre, Cédula o Correo):
                                </label>
                                <input type="text" id="input_buscar_usuario_ajax" oninput="buscarUsuariosLive(this.value)" placeholder="Escriba para buscar usuario..." class="sa-filter-input" style="width: 100%; font-size: 0.85rem;" autocomplete="off">
                                
                                <!-- MENU DE SUGERENCIAS FLOTANTE -->
                                <div id="dropdown_sugerencias_usuarios" style="display: none; position: absolute; top: 100%; left: 0; width: 100%; max-height: 220px; overflow-y: auto; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); z-index: 900; margin-top: 4px;">
                                </div>
                            </div>

                            <div>
                                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">
                                    <i class="ph-bold ph-envelope-simple"></i> O añadir correo directo (presione Enter o coma):
                                </label>
                                <div style="display: flex; gap: 6px;">
                                    <input type="email" id="input_email_libre" placeholder="ejemplo@upttmbi.edu.ve" class="sa-filter-input" style="width: 100%;" onkeydown="if(event.key==='Enter'||event.key===','){ event.preventDefault(); agregarCorreoDirectoManual(); }">
                                    <button type="button" onclick="agregarCorreoDirectoManual()" class="btn" style="background: var(--color-secundario); color: #ffffff !important; border:none; padding: 0 14px; border-radius: 6px; font-weight: 700; cursor: pointer; white-space: nowrap;">
                                        <i class="ph-bold ph-plus"></i> Añadir
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- CHIPS DE DESTINATARIOS -->
                        <div>
                            <label style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
                                Destinatarios Seleccionados (<span id="count-destinatarios">0</span>):
                            </label>
                            <div id="contenedor-chips-destinatarios" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; padding: 10px; min-height: 48px; display: flex; flex-wrap: wrap; gap: 6px; align-items: center;">
                                <span id="placeholder-chips" style="color: #94a3b8; font-size: 0.8rem; font-style: italic;">Empiece a escribir en el buscador superior para seleccionar destinatarios.</span>
                            </div>
                            <div id="inputs-ocultos-destinatarios"></div>
                        </div>
                    </div>

                    <!-- CAMPO ROL -->
                    <div id="campo-destino-rol" style="display: none;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Seleccionar Grupo de Usuarios:</label>
                        <select name="rol_id" class="sa-filter-input" style="width: 100%; max-width: 400px;">
                            <option value="0">-- Enviar a TODOS los Usuarios Registrados --</option>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= $r['id'] ?>">Enviar solo a Rol: <?= htmlspecialchars($r['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- ASUNTO DEL MENSAJE -->
                <div style="margin-bottom: 1.25rem;">
                    <label style="font-size: 0.85rem; font-weight: 700; color: #1e293b; display: block; margin-bottom: 6px;">
                        Asunto del Mensaje:
                    </label>
                    <input type="text" name="asunto" required placeholder="Ej: Comunicado Urgente sobre Inicio de Clases" class="sa-filter-input" style="width: 100%; font-size: 0.95rem; padding: 10px 14px;">
                </div>

                <!-- CONSTRUCTOR DE BLOQUES PRECONSTRUIDOS Y EDITOR DE FORMATO ENRIQUECIDO -->
                <div style="margin-bottom: 1.25rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; flex-wrap: wrap; gap: 6px;">
                        <label style="font-size: 0.85rem; font-weight: 700; color: #1e293b; margin: 0;">
                            Cuerpo del Correo y Diseño Estructurado:
                        </label>
                        
                        <!-- BOTONES PARA INSERTAR SECCIONES / BLOQUES PRECONSTRUIDOS -->
                        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                            <span style="font-size: 0.75rem; font-weight: 700; color: #64748b; align-self: center;">+ Insertar Sección:</span>
                            <button type="button" onclick="insertarBloqueSeccion('titulo')" style="background: #eff6ff; color: var(--color-secundario) !important; border: 1px solid #bfdbfe; border-radius: 4px; padding: 4px 10px; font-size: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.15s;">
                                <i class="ph-bold ph-text-h-two" style="color: var(--color-secundario) !important;"></i> <span style="color: var(--color-secundario) !important;">Título de Sección</span>
                            </button>
                            <button type="button" onclick="insertarBloqueSeccion('destacado')" style="background: #eff6ff; color: var(--color-secundario) !important; border: 1px solid #bfdbfe; border-radius: 4px; padding: 4px 10px; font-size: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.15s;">
                                <i class="ph-bold ph-star" style="color: var(--color-secundario) !important;"></i> <span style="color: var(--color-secundario) !important;">Caja Destacada</span>
                            </button>
                            <button type="button" onclick="insertarBloqueSeccion('boton')" style="background: #eff6ff; color: var(--color-secundario) !important; border: 1px solid #bfdbfe; border-radius: 4px; padding: 4px 10px; font-size: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.15s;">
                                <i class="ph-bold ph-cursor-click" style="color: var(--color-secundario) !important;"></i> <span style="color: var(--color-secundario) !important;">Botón con Enlace</span>
                            </button>
                        </div>
                    </div>

                    <!-- BARRA DE HERRAMIENTAS DE FORMATO (EDITOR ENRIQUECIDO) -->
                    <div style="background: #f1f5f9; border: 1px solid #cbd5e1; border-bottom: none; border-radius: 6px 6px 0 0; padding: 6px 10px; display: flex; gap: 6px; flex-wrap: wrap; align-items: center;">
                        <button type="button" onclick="ejecutarComandoEditor('bold')" title="Negrita" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 8px; font-weight: bold; cursor: pointer;">B</button>
                        <button type="button" onclick="ejecutarComandoEditor('italic')" title="Cursiva" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 8px; font-style: italic; cursor: pointer;">I</button>
                        <button type="button" onclick="ejecutarComandoEditor('underline')" title="Subrayado" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 8px; text-decoration: underline; cursor: pointer;">U</button>
                        <div style="width: 1px; height: 18px; background: #cbd5e1; margin: 0 4px;"></div>
                        
                        <select onchange="ejecutarComandoEditor('fontSize', this.value)" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 3px 6px; font-size: 0.75rem; cursor: pointer;">
                            <option value="3">Tamaño Normal</option>
                            <option value="1">Pequeño</option>
                            <option value="4">Mediano</option>
                            <option value="6">Grande Encabezado</option>
                        </select>

                        <input type="color" onchange="ejecutarComandoEditor('foreColor', this.value)" title="Color del Texto" style="width: 28px; height: 26px; border: 1px solid #cbd5e1; border-radius: 4px; cursor: pointer; padding: 1px; background: #ffffff;">

                        <div style="width: 1px; height: 18px; background: #cbd5e1; margin: 0 4px;"></div>

                        <button type="button" onclick="ejecutarComandoEditor('insertUnorderedList')" title="Lista de Viñetas" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 8px; cursor: pointer;"><i class="ph-bold ph-list-bullets"></i></button>
                        <button type="button" onclick="ejecutarComandoEditor('insertOrderedList')" title="Lista Numerada" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 8px; cursor: pointer;"><i class="ph-bold ph-list-numbers"></i></button>
                    </div>

                    <!-- CANVAS / AREA EDICION WYSIWYG -->
                    <div id="editor-cuerpo-wysiwyg" contenteditable="true" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 0 0 6px 6px; min-height: 250px; padding: 16px; font-family: Arial, sans-serif; font-size: 0.95rem; line-height: 1.6; outline: none;" oninput="sincronizarCuerpoOculto()">
                        <p>Redacte su mensaje aquí o use los botones superiores para insertar secciones estructuradas...</p>
                    </div>

                    <!-- TEXTAREA OCULTO QUE SE ENVIA CON EL FORMULARIO POST -->
                    <textarea name="mensaje" id="input_mensaje_oculto" style="display: none;" required></textarea>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 16px; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                    <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 700; color: #334155; cursor: pointer;">
                        <input type="checkbox" name="usar_layout" value="1" checked style="width: 16px; height: 16px;">
                        Envolver en el Encabezado y Pie de Página Institucional UPTTMBI
                    </label>
                </div>

                <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                    <button type="submit" class="btn" style="background-color: var(--color-secundario) !important; color: #ffffff !important; border:none; padding: 12px 28px; border-radius: 6px; font-weight: 700; font-size: 0.9rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="ph-bold ph-paper-plane-tilt" style="color: #ffffff !important;"></i> <span style="color: #ffffff !important;">Enviar Correo Ahora</span>
                    </button>

                    <button type="button" onclick="previsualizarCorreoDirecto()" class="btn" style="background: #ffffff; color: #1e293b !important; border: 1px solid #cbd5e1; padding: 12px 20px; border-radius: 6px; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="ph-bold ph-eye" style="color: #1e293b !important;"></i> <span style="color: #1e293b !important;">Vista Previa del Mensaje</span>
                    </button>

                    <button type="button" onclick="abrirModalGuardarPlantilla()" class="btn" style="background: #f1f5f9; color: #334155 !important; border: 1px solid #cbd5e1; padding: 12px 20px; border-radius: 6px; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="ph-bold ph-floppy-disk" style="color: #334155 !important;"></i> <span style="color: #334155 !important;">Guardar como Plantilla</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- COLUMNA DERECHA: SECCIÓN LATERAL DE PLANTILLAS REUTILIZABLES DE USUARIO -->
        <div class="glass-panel" style="padding: 1.25rem; border-radius: var(--radius-sm); background:#ffffff;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem;">
                <h5 style="margin: 0; font-size: 0.95rem; font-weight: 800; color: #121a3e; display: flex; align-items: center; gap: 6px;">
                    <i class="ph-bold ph-bookmark-simple" style="color: var(--color-terciario);"></i> Plantillas Reutilizables
                </h5>
                <span style="font-size: 0.7rem; font-weight: 800; color: var(--color-secundario); background: #eff6ff; padding: 2px 8px; border-radius: 12px;">
                    <?= count($plantillasPersonalizadas ?? []) ?>
                </span>
            </div>
            <p style="font-size: 0.78rem; color: #64748b; margin: 0 0 1rem 0;">
                Haga clic en cualquiera de sus plantillas guardadas para cargar su contenido en el editor.
            </p>

            <?php if (empty($plantillasPersonalizadas)): ?>
                <div style="text-align: center; padding: 2rem 0.5rem; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; color: #64748b;">
                    <i class="ph-bold ph-floppy-disk-back" style="font-size: 2rem; color: #cbd5e1; margin-bottom: 0.5rem; display: block;"></i>
                    <p style="margin: 0; font-size: 0.8rem; font-weight: 600;">Sin plantillas guardadas aún.</p>
                    <span style="font-size: 0.75rem; color: #94a3b8; display: block; margin-top: 4px;">Use "Guardar como Plantilla" en el editor.</span>
                </div>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 0.75rem; max-height: 520px; overflow-y: auto; padding-right: 4px;">
                    <?php foreach ($plantillasPersonalizadas as $pKey => $pData): ?>
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; transition: all 0.2s; position: relative;" onmouseover="this.style.borderColor='var(--color-secundario)'; this.style.background='#ffffff';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                <span style="font-size: 0.68rem; font-weight: 800; color: var(--color-secundario); text-transform: uppercase;">
                                    REUTILIZABLE
                                </span>
                                <span style="font-size: 0.7rem; color: #94a3b8;">
                                    <?= isset($pData['fecha']) ? date('d/m/Y', strtotime($pData['fecha'])) : '' ?>
                                </span>
                            </div>
                            <h5 style="margin: 0 0 4px 0; font-size: 0.88rem; font-weight: 800; color: #1e293b;">
                                <?= htmlspecialchars($pData['nombre']) ?>
                            </h5>
                            <p style="margin: 0 0 10px 0; font-size: 0.78rem; color: #64748b; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.35;">
                                <strong>Asunto:</strong> <?= htmlspecialchars($pData['asunto'] ?? 'Sin Asunto') ?>
                            </p>
                            <button type="button" onclick="cargarPlantillaEnEditor('<?= htmlspecialchars($pKey) ?>')" style="background: var(--color-secundario); color: #ffffff !important; border: none; padding: 6px 10px; border-radius: 5px; font-weight: 700; font-size: 0.75rem; cursor: pointer; width: 100%; display: flex; align-items: center; justify-content: center; gap: 4px; transition: background 0.15s;">
                                <i class="ph-bold ph-download-simple" style="color: #ffffff !important;"></i> Cargar en Editor
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- TAB 0.5: HISTORIAL DE ENVÍOS SMTP (INMUTABLE CON BUSCADOR Y PAGINADOR) -->
<div id="tabHistorialLogs" class="sa-tab-content" style="display:none;">
    <div class="glass-panel" style="padding: 1.5rem; border-radius: var(--radius-sm); background:#ffffff;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h4 style="margin: 0 0 0.3rem 0; font-size: 1.1rem; font-weight: 800; color: #121a3e; display: flex; align-items: center; gap: 6px;">
                    <i class="ph-bold ph-clock-counter-clockwise" style="color: var(--color-terciario);"></i> Registro e Historial Inmutable de Envíos
                </h4>
                <p style="font-size: 0.85rem; color: #64748b; margin: 0;">
                    Auditoría continua de notificaciones procesadas por el servidor SMTP de la institución.
                </p>
            </div>
            
            <!-- BUSCADOR CLIENT-SIDE -->
            <div style="display: flex; gap: 8px; align-items: center;">
                <div style="position: relative; width: 280px;">
                    <i class="ph-bold ph-magnifying-glass" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                    <input type="text" id="input-buscar-log" onkeyup="filtrarLogsHistorial()" placeholder="Buscar por email, asunto..." class="sa-filter-input" style="width: 100%; padding-left: 32px; font-size: 0.85rem;">
                </div>
            </div>
        </div>

        <?php if (empty($mailLogs)): ?>
            <div style="text-align: center; padding: 3rem 1rem; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; color: #64748b;">
                <i class="ph-bold ph-envelope-open" style="font-size: 2.5rem; color: #cbd5e1; margin-bottom: 0.5rem; display: block;"></i>
                <p style="margin: 0; font-size: 0.95rem; font-weight: 600;">No hay envíos registrados en el historial.</p>
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="sa-table" id="tabla-logs-correo" style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                            <th style="padding: 10px 12px; font-weight: 700; color: #475569;">Fecha / Hora</th>
                            <th style="padding: 10px 12px; font-weight: 700; color: #475569;">Destinatario</th>
                            <th style="padding: 10px 12px; font-weight: 700; color: #475569;">Asunto</th>
                            <th style="padding: 10px 12px; font-weight: 700; color: #475569;">Estado</th>
                            <th style="padding: 10px 12px; font-weight: 700; color: #475569;">Detalle</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-logs-correo">
                        <?php foreach ($mailLogs as $index => $log): ?>
                            <tr class="fila-log-correo" style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 10px 12px; font-family: monospace; color: #64748b; white-space: nowrap;">
                                    <?= htmlspecialchars($log['fecha']) ?>
                                </td>
                                <td style="padding: 10px 12px;" class="col-destinatario">
                                    <strong style="color: #1e293b; display: block;"><?= htmlspecialchars($log['destino_nombre']) ?></strong>
                                    <span style="color: #64748b; font-size: 0.75rem;"><?= htmlspecialchars($log['destino_email']) ?></span>
                                </td>
                                <td style="padding: 10px 12px; font-weight: 600; color: #334155;" class="col-asunto">
                                    <?= htmlspecialchars($log['asunto']) ?>
                                </td>
                                <td style="padding: 10px 12px;">
                                    <?php if ($log['exito']): ?>
                                        <span class="sa-badge-active">
                                            <i class="ph-bold ph-check-circle"></i> ENTREGADO
                                        </span>
                                    <?php else: ?>
                                        <span class="sa-badge-inactive">
                                            <i class="ph-bold ph-x-circle"></i> FALLIDO
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 10px 12px; color: #64748b; font-size: 0.8rem; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    <?= htmlspecialchars($log['detalle'] ?? '') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- CONTROLES DE PAGINACIÓN CLIENT-SIDE -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; flex-wrap: wrap; gap: 1rem;">
                <div style="font-size: 0.8rem; color: #64748b;" id="info-paginacion-logs">
                    Mostrando 0 registros
                </div>
                <div style="display: flex; gap: 4px;" id="contenedor-paginacion-logs">
                    <!-- Los botones de páginas se generan dinámicamente con JS -->
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- TAB 1: CONEXIÓN & CREDENCIALES + DIAGNÓSTICO EN VIVO (UNIFICADO) -->
<div id="tabConexionSmtp" class="sa-tab-content" style="display:none;">
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        
        <!-- SECCIÓN SUPERIOR: CONFIGURACIÓN DE CREDENCIALES -->
        <div class="glass-panel" style="padding: 1.5rem; border-radius: var(--radius-sm); background:#ffffff;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.75rem;">
                <div>
                    <h4 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #121a3e; display: flex; align-items: center; gap: 8px;">
                        <i class="ph-bold ph-gear" style="color: var(--color-terciario);"></i> Ajustes de Conexión del Servidor SMTP Institucional
                    </h4>
                    <p style="font-size: 0.85rem; color: #64748b; margin: 4px 0 0 0;">
                        Credenciales utilizadas para la autenticación y transmisión masiva e individual de notificaciones.
                    </p>
                </div>
            </div>
            
            <form action="guardar-configuracion-sistema" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="origen" value="gestor-correos">
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 1.25rem;">
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 700; color: #1e293b; display: block; margin-bottom: 6px;">Servidor Host (SMTP)</label>
                        <input type="text" name="smtp_host" value="<?= htmlspecialchars($config['smtp']['host'] ?? '') ?>" placeholder="smtp.gmail.com / smtp.office365.com" class="sa-filter-input" style="width: 100%;">
                    </div>
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 700; color: #1e293b; display: block; margin-bottom: 6px;">Puerto (TLS / SSL)</label>
                        <input type="number" name="smtp_port" value="<?= (int)($config['smtp']['port'] ?? 587) ?>" class="sa-filter-input" style="width: 100%;">
                    </div>
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 700; color: #1e293b; display: block; margin-bottom: 6px;">Usuario Authenticated Email</label>
                        <input type="email" name="smtp_user" value="<?= htmlspecialchars($config['smtp']['user'] ?? '') ?>" placeholder="ciidi@upttmbi.edu.ve" class="sa-filter-input" style="width: 100%;">
                    </div>
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 700; color: #1e293b; display: block; margin-bottom: 6px;">Contraseña de Aplicación</label>
                        <input type="password" name="smtp_pass" placeholder="Dejar vacío para conservar actual" class="sa-filter-input" style="width: 100%;">
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: #1e293b; display: block; margin-bottom: 6px;">Email Remitente Público (Header From)</label>
                        <input type="email" name="smtp_from" value="<?= htmlspecialchars($config['smtp']['from_email'] ?? '') ?>" placeholder="no-reply@upttmbi.edu.ve" class="sa-filter-input" style="width: 100%;">
                    </div>
                </div>

                <button type="submit" class="btn" style="background-color: var(--color-secundario) !important; color: #ffffff !important; border:none; padding: 10px 22px; border-radius: 6px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="ph-bold ph-floppy-disk" style="color: #ffffff !important;"></i> <span style="color: #ffffff !important;">Guardar Credenciales SMTP</span>
                </button>
            </form>
        </div>

        <!-- SECCIÓN INFERIOR: DIAGNÓSTICO EN VIVO Y CORREO DE PRUEBA -->
        <div class="glass-panel" style="padding: 1.5rem; border-radius: var(--radius-sm); background: #f8fafc; border: 1px solid #cbd5e1;">
            <div style="margin-bottom: 1rem;">
                <h4 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: var(--texto-titulos); display: flex; align-items: center; gap: 8px;">
                    <i class="ph-bold ph-paper-plane-right" style="color: var(--color-terciario);"></i> Diagnóstico & Prueba de Conectividad en Tiempo Real
                </h4>
                <p style="font-size: 0.85rem; color: #64748b; margin: 4px 0 0 0;">
                    Envíe un mensaje instantáneo de prueba para comprobar el apretón de manos SMTP, autenticación y la renderización en bandeja.
                </p>
            </div>

            <form action="probar-smtp" method="POST" style="max-width: 650px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1rem; margin-bottom: 1.25rem;">
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Seleccionar Tipo de Mensaje:</label>
                        <select name="template_key" class="sa-filter-input" style="width: 100%;">
                            <option value="">-- Correo Genérico de Diagnóstico (Prueba Estándar) --</option>
                            <?php foreach ($plantillas as $key => $tpl): ?>
                                <option value="<?= htmlspecialchars($key) ?>">Probar Plantilla: <?= htmlspecialchars($tpl['nombre']) ?> (<?= htmlspecialchars($key) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label style="font-size: 0.8rem; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Correo Destino de Prueba:</label>
                        <input type="email" name="email_prueba" required placeholder="tu_correo@ejemplo.com" class="sa-filter-input" style="width: 100%;">
                    </div>
                </div>

                <button type="submit" class="btn" style="background-color: var(--color-secundario) !important; color: #ffffff !important; border:none; padding: 10px 22px; border-radius: 6px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="ph-bold ph-paper-plane" style="color: #ffffff !important;"></i> <span style="color: #ffffff !important;">Ejecutar Prueba de Conexión</span>
                </button>
            </form>
        </div>

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
                        <td style='background: linear-gradient(135deg, rgb(80, 89, 132) 0%, rgb(112, 144, 203) 100%); padding: 30px 20px; text-align: center; color: #ffffff;'>
                            <div style='font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: #ffffff; opacity: 0.9; margin-bottom: 6px;'>
                                UPTTMBI &bull; VALERA, TRUJILLO
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
                                Universidad Politécnica Territorial del Estado Trujillo &quot;Mario Briceño Iragorry&quot;
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

        <form action="guardar-layout-correo" method="POST" onsubmit="sincronizarLayoutFinal()">
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 14px; margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
                <div>
                    <label style="font-size: 0.75rem; font-weight: 700; color: #475569; display: block; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">
                        Variables del Contenedor (Haz clic para insertar):
                    </label>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <button type="button" onclick="insertarVariable('area-html-wrapper', '{TITULO}')" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 8px; font-size: 0.75rem; font-weight: 700; color: var(--color-secundario) !important; font-family: monospace; cursor: pointer;">+{TITULO}</button>
                        <button type="button" onclick="insertarVariable('area-html-wrapper', '{CUERPO}')" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 8px; font-size: 0.75rem; font-weight: 700; color: var(--color-secundario) !important; font-family: monospace; cursor: pointer;">+{CUERPO}</button>
                        <button type="button" onclick="insertarVariable('area-html-wrapper', '{YEAR}')" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 8px; font-size: 0.75rem; font-weight: 700; color: var(--color-secundario) !important; font-family: monospace; cursor: pointer;">+{YEAR}</button>
                    </div>
                </div>

                <!-- CONMUTADOR DUAL VISUAL / CÓDIGO PARA LAYOUT -->
                <div style="display: flex; background: #e2e8f0; border-radius: 6px; padding: 2px; gap: 2px;">
                    <button type="button" onclick="alternarModoEditorLayout('visual')" id="btn-mode-layout-visual" style="background: var(--color-secundario); color: #ffffff !important; border: none; padding: 4px 12px; border-radius: 4px; font-size: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.15s;">
                        <i class="ph-bold ph-eye" style="color: #ffffff !important;"></i> Modo Visual (WYSIWYG)
                    </button>
                    <button type="button" onclick="alternarModoEditorLayout('codigo')" id="btn-mode-layout-code" style="background: transparent; color: #475569 !important; border: none; padding: 4px 12px; border-radius: 4px; font-size: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.15s;">
                        <i class="ph-bold ph-code" style="color: #475569 !important;"></i> Código HTML
                    </button>
                </div>
            </div>

            <!-- MODO DUAL: EDITOR VISUAL DEL ENVOLTORIO VS CÓDIGO HTML -->
            <div style="margin-bottom: 1.5rem;">
                <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Estructura HTML del Envoltorio Institucional:</label>

                <!-- CONTENEDOR MODO VISUAL LAYOUT -->
                <div id="wrapper-wysiwyg-layout">
                    <div style="background: #f1f5f9; border: 1px solid #cbd5e1; border-bottom: none; border-radius: 6px 6px 0 0; padding: 6px 10px; display: flex; gap: 6px; flex-wrap: wrap; align-items: center;">
                        <button type="button" onclick="ejecutarComandoEditorLayout('bold')" title="Negrita" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 8px; font-weight: bold; cursor: pointer;">B</button>
                        <button type="button" onclick="ejecutarComandoEditorLayout('italic')" title="Cursiva" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 8px; font-style: italic; cursor: pointer;">I</button>
                        <button type="button" onclick="ejecutarComandoEditorLayout('underline')" title="Subrayado" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 8px; text-decoration: underline; cursor: pointer;">U</button>
                        <input type="color" onchange="ejecutarComandoEditorLayout('foreColor', this.value)" title="Color del Texto" style="width: 28px; height: 26px; border: 1px solid #cbd5e1; border-radius: 4px; cursor: pointer; padding: 1px; background: #ffffff;">
                        <div style="width: 1px; height: 18px; background: #cbd5e1; margin: 0 4px;"></div>
                        
                        <span style="font-size: 0.75rem; font-weight: 700; color: #64748b; align-self: center;">+ Bloque:</span>
                        <button type="button" onclick="insertarBloqueSeccionContexto('wysiwyg-layout', 'titulo')" style="background: rgba(80, 89, 132, 0.08); color: var(--color-secundario) !important; border: 1px solid rgba(80, 89, 132, 0.2); border-radius: 4px; padding: 3px 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                            <i class="ph-bold ph-text-h-two" style="color: var(--color-secundario) !important;"></i> Título
                        </button>
                        <button type="button" onclick="insertarBloqueSeccionContexto('wysiwyg-layout', 'destacado')" style="background: rgba(80, 89, 132, 0.08); color: var(--color-secundario) !important; border: 1px solid rgba(80, 89, 132, 0.2); border-radius: 4px; padding: 3px 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                            <i class="ph-bold ph-star" style="color: var(--color-secundario) !important;"></i> Destacado
                        </button>
                        <button type="button" onclick="insertarBloqueSeccionContexto('wysiwyg-layout', 'boton')" style="background: rgba(80, 89, 132, 0.08); color: var(--color-secundario) !important; border: 1px solid rgba(80, 89, 132, 0.2); border-radius: 4px; padding: 3px 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                            <i class="ph-bold ph-cursor-click" style="color: var(--color-secundario) !important;"></i> Botón
                        </button>
                    </div>
                    <div id="wysiwyg-layout" contenteditable="true" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 0 0 6px 6px; min-height: 320px; padding: 16px; font-family: Arial, sans-serif; font-size: 0.9rem; line-height: 1.5; outline: none;" oninput="sincronizarCodigoDesdeWysiwygLayout()">
                        <?= $layoutActual ?>
                    </div>
                </div>

                <!-- CONTENEDOR MODO CÓDIGO LAYOUT (OCULTO INICIALMENTE) -->
                <div id="wrapper-code-layout" style="display: none;">
                    <textarea id="area-html-wrapper" name="html_wrapper" rows="18" required class="sa-filter-input" style="width: 100%; font-family: monospace; font-size: 0.85rem; line-height: 1.5; padding: 12px;" oninput="sincronizarWysiwygDesdeCodigoLayout()"><?= htmlspecialchars($layoutActual) ?></textarea>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                <button type="submit" class="btn" style="background-color: var(--color-secundario) !important; color: #ffffff !important; border:none; padding: 12px 24px; border-radius: 6px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
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
                            <span class="<?= $estaActivo ? 'sa-badge-active' : 'sa-badge-inactive' ?>">
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

                        <!-- MODO DUAL: EDITOR VISUAL WYSIWYG VS CÓDIGO HTML -->
                        <div style="margin-bottom: 1.5rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                                <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); margin: 0;">Contenido de la Plantilla:</label>
                                
                                <div style="display: flex; background: #e2e8f0; border-radius: 6px; padding: 2px; gap: 2px;">
                                    <button type="button" onclick="alternarModoEditorTpl('<?= str_replace('.', '-', $key) ?>', 'visual')" id="btn-mode-visual-<?= str_replace('.', '-', $key) ?>" style="background: var(--color-secundario); color: #ffffff !important; border: none; padding: 4px 12px; border-radius: 4px; font-size: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.15s;">
                                        <i class="ph-bold ph-eye" style="color: #ffffff !important;"></i> Modo Visual (WYSIWYG)
                                    </button>
                                    <button type="button" onclick="alternarModoEditorTpl('<?= str_replace('.', '-', $key) ?>', 'codigo')" id="btn-mode-code-<?= str_replace('.', '-', $key) ?>" style="background: transparent; color: #475569 !important; border: none; padding: 4px 12px; border-radius: 4px; font-size: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.15s;">
                                        <i class="ph-bold ph-code" style="color: #475569 !important;"></i> Código HTML
                                    </button>
                                </div>
                            </div>

                            <!-- CONTENEDOR MODO VISUAL -->
                            <div id="wrapper-wysiwyg-tpl-<?= str_replace('.', '-', $key) ?>">
                                <div style="background: #f1f5f9; border: 1px solid #cbd5e1; border-bottom: none; border-radius: 6px 6px 0 0; padding: 6px 10px; display: flex; gap: 6px; flex-wrap: wrap; align-items: center;">
                                    <button type="button" onclick="ejecutarComandoEditorTpl('<?= str_replace('.', '-', $key) ?>', 'bold')" title="Negrita" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 8px; font-weight: bold; cursor: pointer;">B</button>
                                    <button type="button" onclick="ejecutarComandoEditorTpl('<?= str_replace('.', '-', $key) ?>', 'italic')" title="Cursiva" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 8px; font-style: italic; cursor: pointer;">I</button>
                                    <button type="button" onclick="ejecutarComandoEditorTpl('<?= str_replace('.', '-', $key) ?>', 'underline')" title="Subrayado" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 8px; text-decoration: underline; cursor: pointer;">U</button>
                                    <div style="width: 1px; height: 18px; background: #cbd5e1; margin: 0 4px;"></div>
                                    <button type="button" onclick="ejecutarComandoEditorTpl('<?= str_replace('.', '-', $key) ?>', 'insertUnorderedList')" title="Lista de Viñetas" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 8px; cursor: pointer;"><i class="ph-bold ph-list-bullets"></i></button>
                                    <button type="button" onclick="ejecutarComandoEditorTpl('<?= str_replace('.', '-', $key) ?>', 'insertOrderedList')" title="Lista Numerada" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 8px; cursor: pointer;"><i class="ph-bold ph-list-numbers"></i></button>
                                    <div style="width: 1px; height: 18px; background: #cbd5e1; margin: 0 4px;"></div>
                                    <span style="font-size: 0.75rem; font-weight: 700; color: #64748b; align-self: center;">+ Bloque:</span>
                                    <button type="button" onclick="insertarBloqueSeccionContexto('wysiwyg-tpl-<?= str_replace('.', '-', $key) ?>', 'titulo')" style="background: rgba(80, 89, 132, 0.08); color: var(--color-secundario) !important; border: 1px solid rgba(80, 89, 132, 0.2); border-radius: 4px; padding: 3px 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                                        <i class="ph-bold ph-text-h-two" style="color: var(--color-secundario) !important;"></i> Título
                                    </button>
                                    <button type="button" onclick="insertarBloqueSeccionContexto('wysiwyg-tpl-<?= str_replace('.', '-', $key) ?>', 'destacado')" style="background: rgba(80, 89, 132, 0.08); color: var(--color-secundario) !important; border: 1px solid rgba(80, 89, 132, 0.2); border-radius: 4px; padding: 3px 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                                        <i class="ph-bold ph-star" style="color: var(--color-secundario) !important;"></i> Destacado
                                    </button>
                                    <button type="button" onclick="insertarBloqueSeccionContexto('wysiwyg-tpl-<?= str_replace('.', '-', $key) ?>', 'boton')" style="background: rgba(80, 89, 132, 0.08); color: var(--color-secundario) !important; border: 1px solid rgba(80, 89, 132, 0.2); border-radius: 4px; padding: 3px 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                                        <i class="ph-bold ph-cursor-click" style="color: var(--color-secundario) !important;"></i> Botón
                                    </button>
                                </div>
                                <div id="wysiwyg-tpl-<?= str_replace('.', '-', $key) ?>" contenteditable="true" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 0 0 6px 6px; min-height: 220px; padding: 16px; font-family: Arial, sans-serif; font-size: 0.95rem; line-height: 1.6; outline: none;" oninput="sincronizarCodigoDesdeWysiwyg('<?= str_replace('.', '-', $key) ?>')">
                                    <?= $tpl['cuerpo_html'] ?>
                                </div>
                            </div>

                            <!-- CONTENEDOR MODO CÓDIGO HTML (OCULTO INICIALMENTE) -->
                            <div id="wrapper-code-tpl-<?= str_replace('.', '-', $key) ?>" style="display: none;">
                                <textarea id="area-tpl-<?= str_replace('.', '-', $key) ?>" name="cuerpo_html" rows="14" required class="sa-filter-input" style="width: 100%; font-family: monospace; font-size: 0.85rem; line-height: 1.5; padding: 12px;" oninput="sincronizarWysiwygDesdeCodigo('<?= str_replace('.', '-', $key) ?>')"><?= htmlspecialchars($tpl['cuerpo_html']) ?></textarea>
                            </div>
                        </div>

                        <button type="submit" onclick="sincronizarFinalPlantilla('<?= str_replace('.', '-', $key) ?>')" class="btn" style="background-color: var(--color-secundario) !important; color: #ffffff !important; border:none; padding: 12px 24px; border-radius: 6px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
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
        <div style="background: var(--color-secundario); color: #ffffff; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center;">
            <div style="font-weight: 700; font-size: 0.95rem; display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-eye"></i> Vista Previa de Renderizado Institucional
            </div>
            <button type="button" onclick="cerrarModalPreview()" style="background: none; border: none; color: #ffffff; font-size: 1.4rem; cursor: pointer;">&times;</button>
        </div>
        <div style="padding: 20px; overflow-y: auto; flex: 1; background: #f4f7fb;">
            <iframe id="iframePreviewCorreo" style="width: 100%; height: 500px; border: none; background: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);"></iframe>
        </div>
    </div>
</div>

<!-- MODAL GENÉRICO DE AVISO Y NOTIFICACIÓN (REEMPLAZO DE ALERT) -->
<div id="modalAvisoAlert" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(18,26,62,0.6); backdrop-filter: blur(6px); z-index: 10000; justify-content: center; align-items: center;">
    <div style="background: #ffffff; width: 90%; max-width: 440px; border-radius: var(--radius-md); padding: 1.75rem; text-align: center; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 1px solid rgba(80, 89, 132, 0.2);">
        <div id="modalAvisoIcono" style="font-size: 2.5rem; margin-bottom: 0.5rem; color: var(--color-terciario);">
            <i class="ph-bold ph-info"></i>
        </div>
        <h3 id="modalAvisoTitulo" style="margin: 0 0 0.5rem 0; font-size: 1.15rem; font-weight: 800; color: var(--texto-titulos);">Notificación</h3>
        <p id="modalAvisoMensaje" style="margin: 0 0 1.25rem 0; font-size: 0.9rem; color: #475569; line-height: 1.5;"></p>
        <button type="button" onclick="cerrarModalAviso()" class="sa-btn sa-btn-primary" style="min-width: 130px;">
            Aceptar
        </button>
    </div>
</div>

<!-- MODAL PARA INSERTAR BOTÓN CON ENLACE (REEMPLAZO DE PROMPT) -->
<div id="modalConfigBoton" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(18,26,62,0.6); backdrop-filter: blur(6px); z-index: 10000; justify-content: center; align-items: center;">
    <div style="background: #ffffff; width: 90%; max-width: 480px; border-radius: var(--radius-md); padding: 1.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 1px solid rgba(80, 89, 132, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.75rem;">
            <h3 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: var(--texto-titulos); display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-cursor-click" style="color: var(--color-terciario);"></i> Configurar Botón de Acción
            </h3>
            <button type="button" onclick="cerrarModalConfigBoton()" style="background: none; border: none; color: #64748b; font-size: 1.3rem; cursor: pointer;">&times;</button>
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Texto a mostrar en el botón:</label>
            <input type="text" id="input_btn_texto" value="ACCEDER AL PORTAL" class="sa-filter-input" style="width: 100%;">
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">URL de destino (Enlace):</label>
            <input type="url" id="input_btn_url" value="https://upttmbi.edu.ve" class="sa-filter-input" style="width: 100%;">
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem; border-top: 1px solid #e2e8f0; padding-top: 1rem;">
            <button type="button" onclick="cerrarModalConfigBoton()" class="sa-btn sa-btn-cancel" style="min-width: 110px;">
                Cancelar
            </button>
            <button type="button" onclick="confirmarInsertarBoton()" class="sa-btn sa-btn-primary" style="min-width: 130px;">
                Insertar Botón
            </button>
        </div>
    </div>
</div>

<!-- MODAL PARA GUARDAR COMO PLANTILLA PERSONALIZADA REUTILIZABLE -->
<div id="modalGuardarPlantilla" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(18,26,62,0.6); backdrop-filter: blur(6px); z-index: 10000; justify-content: center; align-items: center;">
    <div style="background: #ffffff; width: 90%; max-width: 480px; border-radius: var(--radius-md); padding: 1.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 1px solid rgba(80, 89, 132, 0.2);">
        <form action="guardar-plantilla-personalizada" method="POST" id="formGuardarPlantillaCustom">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="cuerpo_html" id="input_modal_cuerpo_html">
            <input type="hidden" name="asunto" id="input_modal_asunto">

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.75rem;">
                <h3 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: var(--texto-titulos); display: flex; align-items: center; gap: 8px;">
                    <i class="ph-bold ph-floppy-disk" style="color: var(--color-terciario);"></i> Guardar como Plantilla Reusable
                </h3>
                <button type="button" onclick="cerrarModalGuardarPlantilla()" style="background: none; border: none; color: #64748b; font-size: 1.3rem; cursor: pointer;">&times;</button>
            </div>
            
            <div style="margin-bottom: 1.25rem;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Nombre Identificador de la Plantilla:</label>
                <input type="text" name="nombre" required placeholder="Ej. Invitación a Conferencia 2026" class="sa-filter-input" style="width: 100%;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem; border-top: 1px solid #e2e8f0; padding-top: 1rem;">
                <button type="button" onclick="cerrarModalGuardarPlantilla()" class="sa-btn sa-btn-cancel" style="min-width: 110px;">
                    Cancelar
                </button>
                <button type="submit" class="sa-btn sa-btn-primary" style="min-width: 140px;">
                    Guardar Plantilla
                </button>
            </div>
        </form>
    </div>
</div>



<script>
function switchMailTab(tabId, btn) {
    document.querySelectorAll('#tabCorreoDirecto, #tabHistorialLogs, #tabConexionSmtp, #tabLayoutBase, #tabPlantillas').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.sa-tab-btn').forEach(b => b.classList.remove('tab-active'));
    const target = document.getElementById(tabId);
    if (target) target.style.display = 'block';
    if (btn) btn.classList.add('tab-active');
}

function switchSubCatalogo(catId, btn) {
    document.getElementById('subcat-mis-plantillas').style.display = 'none';
    document.getElementById('subcat-plantillas-sistema').style.display = 'none';
    
    document.querySelectorAll('.btn-subcatalogo').forEach(b => {
        b.style.background = 'transparent';
        b.style.color = '#64748b';
        b.style.borderColor = 'transparent';
    });

    const target = document.getElementById('subcat-' + catId);
    if (target) target.style.display = 'block';

    if (btn) {
        btn.style.background = '#ffffff';
        btn.style.color = 'var(--color-secundario)';
        btn.style.borderColor = '#cbd5e1';
    }
}

function toggleModoDestino(modo) {
    const ind = document.getElementById('campo-destino-individual');
    const rol = document.getElementById('campo-destino-rol');
    if (modo === 'individual') {
        ind.style.display = 'grid';
        rol.style.display = 'none';
    } else {
        ind.style.display = 'none';
        rol.style.display = 'block';
    }
}

// --- SISTEMA DE AVISOS Y MODALES PERSONALIZADOS (REEMPLAZO NATIVO DE ALERTS Y PROMPTS) ---
function mostrarAvisoModal(mensaje, titulo = 'Notificación', tipo = 'info') {
    const modal = document.getElementById('modalAvisoAlert');
    const elemTitulo = document.getElementById('modalAvisoTitulo');
    const elemMensaje = document.getElementById('modalAvisoMensaje');
    const elemIcono = document.getElementById('modalAvisoIcono');

    if (!modal || !elemMensaje) return;

    elemTitulo.textContent = titulo;
    elemMensaje.textContent = mensaje;

    if (tipo === 'error') {
        elemIcono.innerHTML = '<i class="ph-bold ph-x-circle" style="color: var(--color-secundario);"></i>';
    } else if (tipo === 'warning') {
        elemIcono.innerHTML = '<i class="ph-bold ph-warning" style="color: var(--color-principal);"></i>';
    } else {
        elemIcono.innerHTML = '<i class="ph-bold ph-info" style="color: var(--color-terciario);"></i>';
    }

    modal.style.display = 'flex';
}

function cerrarModalAviso() {
    document.getElementById('modalAvisoAlert').style.display = 'none';
}

function abrirModalConfigBoton() {
    document.getElementById('modalConfigBoton').style.display = 'flex';
}

function cerrarModalConfigBoton() {
    document.getElementById('modalConfigBoton').style.display = 'none';
}

function sincronizarCuerpoOculto() {
    const editor = document.getElementById('editor-cuerpo-wysiwyg');
    const oculto = document.getElementById('input_mensaje_oculto');
    if (editor && oculto) {
        oculto.value = editor.innerHTML;
    }
}

function ejecutarComandoEditor(comando, val = null) {
    const editor = document.getElementById('editor-cuerpo-wysiwyg');
    if (!editor) return;
    editor.focus();
    document.execCommand(comando, false, val);
    sincronizarCuerpoOculto();
}

// --- GESTIÓN DE PLANTILLAS PERSONALIZADAS ---
const plantillasCustomJS = <?= json_encode($plantillasPersonalizadas ?? []) ?>;

function abrirModalGuardarPlantilla() {
    sincronizarCuerpoOculto();
    const oculto = document.getElementById('input_mensaje_oculto');
    const asuntoInput = document.querySelector('#formCorreoDirecto input[name="asunto"]');

    if (!oculto || !oculto.value.trim() || oculto.value === '<p><br></p>') {
        mostrarAvisoModal('Debe redactar el contenido de su correo en el editor antes de guardarlo como plantilla.', 'Contenido Vacío', 'warning');
        return;
    }

    document.getElementById('input_modal_cuerpo_html').value = oculto.value;
    document.getElementById('input_modal_asunto').value = asuntoInput ? asuntoInput.value : '';

    document.getElementById('modalGuardarPlantilla').style.display = 'flex';
}

function cerrarModalGuardarPlantilla() {
    document.getElementById('modalGuardarPlantilla').style.display = 'none';
}

const plantillasSistemaJS = <?= json_encode($plantillas ?? []) ?>;

function abrirModalCatalogoPlantillas() {
    document.getElementById('modalCatalogoPlantillas').style.display = 'flex';
}

function cerrarModalCatalogoPlantillas() {
    document.getElementById('modalCatalogoPlantillas').style.display = 'none';
}

function cargarPlantillaSistemaEnEditor(key) {
    if (!key || !plantillasSistemaJS[key]) return;
    const tpl = plantillasSistemaJS[key];
    const editor = document.getElementById('editor-cuerpo-wysiwyg');
    const asuntoInput = document.querySelector('#formCorreoDirecto input[name="asunto"]');

    if (asuntoInput && tpl.asunto) {
        asuntoInput.value = tpl.asunto;
    }

    if (editor && tpl.cuerpo_html) {
        editor.innerHTML = tpl.cuerpo_html;
        sincronizarCuerpoOculto();
    }
    cerrarModalCatalogoPlantillas();
    mostrarAvisoModal(`Se ha cargado la plantilla '${tpl.nombre}' en el editor.`, 'Plantilla Cargada', 'info');
}

function cargarPlantillaEnEditor(key) {
    if (!key || !plantillasCustomJS[key]) return;

    const tpl = plantillasCustomJS[key];
    const editor = document.getElementById('editor-cuerpo-wysiwyg');
    const asuntoInput = document.querySelector('#formCorreoDirecto input[name="asunto"]');

    if (asuntoInput && tpl.asunto) {
        asuntoInput.value = tpl.asunto;
    }

    if (editor && tpl.cuerpo_html) {
        editor.innerHTML = tpl.cuerpo_html;
        sincronizarCuerpoOculto();
    }
    cerrarModalCatalogoPlantillas();
    mostrarAvisoModal(`Se ha cargado la plantilla personalizada '${tpl.nombre}' en el editor.`, 'Plantilla Cargada', 'info');
}

let targetWysiwygBotonId = 'editor-cuerpo-wysiwyg';

function abrirModalConfigBoton(targetId = 'editor-cuerpo-wysiwyg') {
    targetWysiwygBotonId = targetId;
    document.getElementById('modalConfigBoton').style.display = 'flex';
}

function cerrarModalConfigBoton() {
    document.getElementById('modalConfigBoton').style.display = 'none';
}

function confirmarInsertarBoton() {
    const txt = document.getElementById('input_btn_texto')?.value.trim();
    const url = document.getElementById('input_btn_url')?.value.trim();

    if (!txt || !url) {
        mostrarAvisoModal('Por favor especifique tanto el texto del botón como la URL de destino.', 'Campos Requeridos', 'warning');
        return;
    }

    const htmlBloque = `<div style="text-align:center; margin:24px 0;"><a href="${url}" target="_blank" style="background-color:rgb(80, 89, 132); color:#ffffff !important; padding:12px 26px; border-radius:6px; text-decoration:none; font-weight:bold; font-size:0.9rem; display:inline-block; border:none;">${txt}</a></div>`;
    
    const editor = document.getElementById(targetWysiwygBotonId);
    if (editor) {
        editor.focus();
        document.execCommand('insertHTML', false, htmlBloque);
        sincronizarEditorPorId(targetWysiwygBotonId);
    }
    cerrarModalConfigBoton();
}

function previsualizarCorreoDirecto() {
    sincronizarCuerpoOculto();
    const asuntoInput = document.querySelector('#formCorreoDirecto input[name="asunto"]');
    const oculto = document.getElementById('input_mensaje_oculto');
    const usarLayoutCheck = document.querySelector('#formCorreoDirecto input[name="usar_layout"]');
    const wrapperArea = document.getElementById('area-html-wrapper');

    const asunto = asuntoInput ? (asuntoInput.value || 'Sin Asunto Especificado') : 'Sin Asunto';
    const mensajeHtml = oculto ? oculto.value : '';

    if (!mensajeHtml.trim() || mensajeHtml === '<p><br></p>') {
        mostrarAvisoModal('Por favor redacte un mensaje en el cuerpo del correo para generar la vista previa.', 'Mensaje Vacío', 'warning');
        return;
    }

    const usarLayout = usarLayoutCheck ? usarLayoutCheck.checked : true;
    let htmlCompleto = '';

    if (usarLayout && wrapperArea && wrapperArea.value) {
        htmlCompleto = wrapperArea.value
            .replace(/{TITULO}/g, asunto)
            .replace(/{CUERPO}/g, `<h3 style="color:#1E293B; margin-top:0; font-weight:800; font-size:1.1rem;">${asunto}</h3><div style="line-height:1.6; color:#334155;">${mensajeHtml}</div>`)
            .replace(/{YEAR}/g, new Date().getFullYear());
    } else {
        htmlCompleto = `
            <!DOCTYPE html>
            <html>
            <head><meta charset="utf-8"><title>${asunto}</title></head>
            <body style="font-family: Arial, sans-serif; padding:20px; color:#334155;">
                <h2 style="color:#1E293B;">${asunto}</h2>
                <div style="line-height:1.6;">${mensajeHtml}</div>
            </body>
            </html>
        `;
    }

    const iframe = document.getElementById('iframePreviewCorreo');
    const modal = document.getElementById('modalPreviewCorreo');
    modal.style.display = 'flex';

    const doc = iframe.contentWindow.document;
    doc.open();
    doc.write(htmlCompleto);
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

function sincronizarEditorPorId(editorId) {
    if (editorId === 'editor-cuerpo-wysiwyg') {
        sincronizarCuerpoOculto();
    } else if (editorId === 'wysiwyg-layout') {
        sincronizarCodigoDesdeWysiwygLayout();
    } else if (editorId.startsWith('wysiwyg-tpl-')) {
        const safeKey = editorId.replace('wysiwyg-tpl-', '');
        sincronizarCodigoDesdeWysiwyg(safeKey);
    }
}

function insertarBloqueSeccion(tipo) {
    insertarBloqueSeccionContexto('editor-cuerpo-wysiwyg', tipo);
}

function insertarBloqueSeccionContexto(targetId, tipo) {
    const editor = document.getElementById(targetId);
    if (!editor) return;

    let htmlBloque = '';

    if (tipo === 'titulo') {
        htmlBloque = `<h3 style="color:#1E293B; font-size:1.2rem; font-weight:800; margin:18px 0 8px 0;">TITULO DE SECCIÓN</h3><p>Escriba aquí los detalles correspondientes a esta sección...</p>`;
    } else if (tipo === 'destacado') {
        htmlBloque = `<div style="background-color:rgba(80, 89, 132, 0.08); border-left:4px solid rgb(80, 89, 132); padding:14px; border-radius:6px; margin:16px 0; color:#1E293B; font-size:0.9rem;"><strong>Información Importante:</strong> Inserte aquí los aspectos resaltantes o avisos prioritarios para el usuario.</div>`;
    } else if (tipo === 'boton') {
        abrirModalConfigBoton(targetId);
        return;
    }

    if (htmlBloque) {
        editor.focus();
        document.execCommand('insertHTML', false, htmlBloque);
        sincronizarEditorPorId(targetId);
    }
}

// Sincronización previa al envío del formulario
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formCorreoDirecto');
    if (form) {
        form.addEventListener('submit', (e) => {
            sincronizarCuerpoOculto();
            const oculto = document.getElementById('input_mensaje_oculto');
            if (!oculto.value.trim() || oculto.value === '<p><br></p>') {
                e.preventDefault();
                mostrarAvisoModal('Por favor redacte el contenido o cuerpo del mensaje antes de enviar.', 'Cuerpo Requerido', 'warning');
            }
        });
    }
    sincronizarCuerpoOculto();
});

function seleccionarPlantilla(key) {
    const safeKey = key.replace(/\./g, '-');
    document.querySelectorAll('.tpl-editor-panel').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.tpl-item-btn').forEach(b => b.style.background = '#f8fafc');
    
    const targetEditor = document.getElementById('editor-tpl-' + safeKey);
    const targetBtn = document.getElementById('btn-tpl-' + safeKey);

    if (targetEditor) targetEditor.style.display = 'block';
    if (targetBtn) targetBtn.style.background = '#e2e8f0';
}

function alternarModoEditorTpl(safeKey, modo) {
    const wrapWysiwyg = document.getElementById('wrapper-wysiwyg-tpl-' + safeKey);
    const wrapCode = document.getElementById('wrapper-code-tpl-' + safeKey);
    const btnVisual = document.getElementById('btn-mode-visual-' + safeKey);
    const btnCode = document.getElementById('btn-mode-code-' + safeKey);

    if (modo === 'visual') {
        sincronizarWysiwygDesdeCodigo(safeKey);
        if (wrapWysiwyg) wrapWysiwyg.style.display = 'block';
        if (wrapCode) wrapCode.style.display = 'none';
        if (btnVisual) {
            btnVisual.style.background = 'var(--color-secundario)';
            btnVisual.style.setProperty('color', '#ffffff', 'important');
        }
        if (btnCode) {
            btnCode.style.background = 'transparent';
            btnCode.style.setProperty('color', '#475569', 'important');
        }
    } else {
        sincronizarCodigoDesdeWysiwyg(safeKey);
        if (wrapWysiwyg) wrapWysiwyg.style.display = 'none';
        if (wrapCode) wrapCode.style.display = 'block';
        if (btnCode) {
            btnCode.style.background = 'var(--color-secundario)';
            btnCode.style.setProperty('color', '#ffffff', 'important');
        }
        if (btnVisual) {
            btnVisual.style.background = 'transparent';
            btnVisual.style.setProperty('color', '#475569', 'important');
        }
    }
}

// --- DUAL EDITOR DE LAYOUT INSTITUCIONAL BASE (TAB 3) ---
function alternarModoEditorLayout(modo) {
    const wrapWysiwyg = document.getElementById('wrapper-wysiwyg-layout');
    const wrapCode = document.getElementById('wrapper-code-layout');
    const btnVisual = document.getElementById('btn-mode-layout-visual');
    const btnCode = document.getElementById('btn-mode-layout-code');

    if (modo === 'visual') {
        sincronizarWysiwygDesdeCodigoLayout();
        if (wrapWysiwyg) wrapWysiwyg.style.display = 'block';
        if (wrapCode) wrapCode.style.display = 'none';
        if (btnVisual) {
            btnVisual.style.background = 'var(--color-secundario)';
            btnVisual.style.setProperty('color', '#ffffff', 'important');
        }
        if (btnCode) {
            btnCode.style.background = 'transparent';
            btnCode.style.setProperty('color', '#475569', 'important');
        }
    } else {
        sincronizarCodigoDesdeWysiwygLayout();
        if (wrapWysiwyg) wrapWysiwyg.style.display = 'none';
        if (wrapCode) wrapCode.style.display = 'block';
        if (btnCode) {
            btnCode.style.background = 'var(--color-secundario)';
            btnCode.style.setProperty('color', '#ffffff', 'important');
        }
        if (btnVisual) {
            btnVisual.style.background = 'transparent';
            btnVisual.style.setProperty('color', '#475569', 'important');
        }
    }
}

function sincronizarCodigoDesdeWysiwygLayout() {
    const wysiwyg = document.getElementById('wysiwyg-layout');
    const area = document.getElementById('area-html-wrapper');
    if (wysiwyg && area) {
        area.value = wysiwyg.innerHTML;
    }
}

function sincronizarWysiwygDesdeCodigoLayout() {
    const wysiwyg = document.getElementById('wysiwyg-layout');
    const area = document.getElementById('area-html-wrapper');
    if (wysiwyg && area) {
        wysiwyg.innerHTML = area.value;
    }
}

function sincronizarLayoutFinal() {
    sincronizarCodigoDesdeWysiwygLayout();
}

function ejecutarComandoEditorLayout(comando, val = null) {
    const wysiwyg = document.getElementById('wysiwyg-layout');
    if (!wysiwyg) return;
    wysiwyg.focus();
    document.execCommand(comando, false, val);
    sincronizarCodigoDesdeWysiwygLayout();
}

function sincronizarCodigoDesdeWysiwyg(safeKey) {
    const wysiwyg = document.getElementById('wysiwyg-tpl-' + safeKey);
    const area = document.getElementById('area-tpl-' + safeKey);
    if (wysiwyg && area) {
        area.value = wysiwyg.innerHTML;
    }
}

function sincronizarWysiwygDesdeCodigo(safeKey) {
    const wysiwyg = document.getElementById('wysiwyg-tpl-' + safeKey);
    const area = document.getElementById('area-tpl-' + safeKey);
    if (wysiwyg && area) {
        wysiwyg.innerHTML = area.value;
    }
}

function sincronizarFinalPlantilla(safeKey) {
    sincronizarCodigoDesdeWysiwyg(safeKey);
}

function ejecutarComandoEditorTpl(safeKey, comando, val = null) {
    const wysiwyg = document.getElementById('wysiwyg-tpl-' + safeKey);
    if (!wysiwyg) return;
    wysiwyg.focus();
    document.execCommand(comando, false, val);
    sincronizarCodigoDesdeWysiwyg(safeKey);
}

function insertarVariable(textareaId, variableStr) {
    const area = document.getElementById(textareaId);
    if (area) {
        const start = area.selectionStart || 0;
        const end = area.selectionEnd || 0;
        const text = area.value || '';
        area.value = text.substring(0, start) + variableStr + text.substring(end);
        area.selectionStart = area.selectionEnd = start + variableStr.length;
        area.focus();
    }

    if (textareaId === 'area-html-wrapper') {
        const wysiwygLayout = document.getElementById('wysiwyg-layout');
        if (wysiwygLayout) {
            wysiwygLayout.focus();
            document.execCommand('insertText', false, variableStr);
            sincronizarCodigoDesdeWysiwygLayout();
        }
    } else {
        const safeKey = textareaId.replace('area-tpl-', '');
        const wysiwyg = document.getElementById('wysiwyg-tpl-' + safeKey);
        if (wysiwyg) {
            wysiwyg.focus();
            document.execCommand('insertText', false, variableStr);
            sincronizarCodigoDesdeWysiwyg(safeKey);
        }
    }
}

function abrirModalPreviewLayout() {
    const areaWrapper = document.getElementById('area-html-wrapper');
    if (!areaWrapper) return;

    let layoutWrapper = areaWrapper.value;
    const sampleBody = `
        <h3 style="color:#1E293B; margin-top:0; font-weight:800; font-size: 1.1rem;">Ejemplo de Notificación Institucional</h3>
        <p style="color:#475569; line-height:1.6; font-size: 0.9rem;">Este es un mensaje de prueba para verificar el renderizado del Envoltorio Base Institucional (encabezado, cuerpo, footer y estilos CSS).</p>
        <div style="background-color:rgba(80, 89, 132, 0.08); border-left:4px solid rgb(80, 89, 132); padding:14px; border-radius:6px; margin:16px 0; color:#1E293B; font-size:0.85rem;">
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

// --- BUSCADOR AUTOCOMPLETE DE USUARIOS EN TIEMPO REAL ---
const todosUsuariosJS = <?= json_encode(array_values(array_map(function($u) {
    return [
        'id' => (int)$u['id'],
        'nombre' => (string)$u['nombre_completo'],
        'cedula' => (string)$u['cedula'],
        'email' => (string)$u['email'],
        'rol' => (string)($u['rol_nombre'] ?? '')
    ];
}, $usuarios ?? []))) ?>;

const destinatariosSeleccionados = new Map(); // id/email -> data

function buscarUsuariosLive(query) {
    const dropdown = document.getElementById('dropdown_sugerencias_usuarios');
    if (!dropdown) return;

    query = query.trim().toLowerCase();
    if (query.length < 1) {
        dropdown.style.display = 'none';
        dropdown.innerHTML = '';
        return;
    }

    const filtrados = todosUsuariosJS.filter(u => {
        const nom = (u.nombre || '').toLowerCase();
        const ced = (u.cedula || '').toLowerCase();
        const eml = (u.email || '').toLowerCase();
        return nom.includes(query) || ced.includes(query) || eml.includes(query);
    }).slice(0, 8); // Máximo 8 sugerencias

    if (filtrados.length === 0) {
        dropdown.innerHTML = '<div style="padding: 10px; font-size: 0.8rem; color: #94a3b8; text-align: center;">No se encontraron usuarios que coincidan</div>';
        dropdown.style.display = 'block';
        return;
    }

    let html = '';
    filtrados.forEach(u => {
        const key = 'usr_' + u.id;
        const yaAgregado = destinatariosSeleccionados.has(key);

        html += `
            <div onclick="seleccionarUsuarioAutocomplete(${u.id})" style="padding: 10px 14px; border-bottom: 1px solid #f1f5f9; cursor: ${yaAgregado ? 'default' : 'pointer'}; background: ${yaAgregado ? '#f8fafc' : '#ffffff'}; opacity: ${yaAgregado ? '0.6' : '1'}; transition: background 0.15s;" onmouseover="if(!${yaAgregado}) this.style.background='#eff6ff'" onmouseout="if(!${yaAgregado}) this.style.background='#ffffff'">
                <div style="font-weight: 700; font-size: 0.85rem; color: #1e293b;">${u.nombre} <span style="font-size:0.75rem; color:#64748b; font-weight:normal;">(C.I: ${u.cedula})</span></div>
                <div style="font-size: 0.75rem; color: #0284c7; display: flex; justify-content: space-between;">
                    <span>${u.email}</span>
                    <span style="font-weight:700; color:#64748b;">${u.rol}</span>
                </div>
            </div>
        `;
    });

    dropdown.innerHTML = html;
    dropdown.style.display = 'block';
}

function seleccionarUsuarioAutocomplete(id) {
    const usr = todosUsuariosJS.find(u => parseInt(u.id, 10) === parseInt(id, 10));
    if (!usr) return;

    const key = 'usr_' + usr.id;
    if (!destinatariosSeleccionados.has(key)) {
        destinatariosSeleccionados.set(key, { tipo: 'usuario', id: usr.id, nombre: usr.nombre, email: usr.email });
        renderizarChipsDestinatarios();
    }

    const input = document.getElementById('input_buscar_usuario_ajax');
    const dropdown = document.getElementById('dropdown_sugerencias_usuarios');
    if (input) input.value = '';
    if (dropdown) {
        dropdown.style.display = 'none';
        dropdown.innerHTML = '';
    }
}

function agregarCorreoDirectoManual() {
    const input = document.getElementById('input_email_libre');
    if (!input) return;

    const val = input.value.trim();
    if (!val) return;

    const emails = val.split(/[\s,;]+/);
    emails.forEach(em => {
        em = em.trim().toLowerCase();
        if (em && em.includes('@')) {
            const key = 'email_' + em;
            if (!destinatariosSeleccionados.has(key)) {
                destinatariosSeleccionados.set(key, { tipo: 'email', email: em, nombre: 'Correo Externo' });
            }
        }
    });

    renderizarChipsDestinatarios();
    input.value = '';
}

function removerDestinatario(key) {
    destinatariosSeleccionados.delete(key);
    renderizarChipsDestinatarios();
}

function renderizarChipsDestinatarios() {
    const container = document.getElementById('contenedor-chips-destinatarios');
    const inputsDiv = document.getElementById('inputs-ocultos-destinatarios');
    const countSpan = document.getElementById('count-destinatarios');

    if (!container || !inputsDiv) return;

    container.innerHTML = '';
    inputsDiv.innerHTML = '';

    if (destinatariosSeleccionados.size === 0) {
        container.innerHTML = '<span id="placeholder-chips" style="color: #94a3b8; font-size: 0.8rem; font-style: italic;">Empiece a escribir en el buscador superior para seleccionar destinatarios.</span>';
        if (countSpan) countSpan.textContent = '0';
        return;
    }

    if (countSpan) countSpan.textContent = destinatariosSeleccionados.size;

    const emailsLibres = [];

    destinatariosSeleccionados.forEach((item, key) => {
        if (item.tipo === 'usuario') {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'usuario_ids[]';
            hidden.value = item.id;
            inputsDiv.appendChild(hidden);

            const chip = document.createElement('div');
            chip.style.cssText = 'background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-size: 0.8rem; font-weight: 700; padding: 4px 10px; border-radius: 16px; display: inline-flex; align-items: center; gap: 6px;';
            chip.innerHTML = `<span><i class="ph-bold ph-user"></i> ${item.nombre} (${item.email})</span><button type="button" onclick="removerDestinatario('${key}')" style="background:none; border:none; color:#0284c7; cursor:pointer; font-size:1rem; line-height:1; font-weight:bold;">&times;</button>`;
            container.appendChild(chip);
        } else if (item.tipo === 'email') {
            emailsLibres.push(item.email);

            const chip = document.createElement('div');
            chip.style.cssText = 'background: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 0.8rem; font-weight: 700; padding: 4px 10px; border-radius: 16px; display: inline-flex; align-items: center; gap: 6px;';
            chip.innerHTML = `<span><i class="ph-bold ph-envelope-simple"></i> ${item.email}</span><button type="button" onclick="removerDestinatario('${key}')" style="background:none; border:none; color:#b45309; cursor:pointer; font-size:1rem; line-height:1; font-weight:bold;">&times;</button>`;
            container.appendChild(chip);
        }
    });

    if (emailsLibres.length > 0) {
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'email_libre';
        hidden.value = emailsLibres.join(', ');
        inputsDiv.appendChild(hidden);
    }
}

// Ocultar desplegable al hacer clic fuera
document.addEventListener('click', (e) => {
    const dropdown = document.getElementById('dropdown_sugerencias_usuarios');
    const input = document.getElementById('input_buscar_usuario_ajax');
    if (dropdown && input && !dropdown.contains(e.target) && e.target !== input) {
        dropdown.style.display = 'none';
    }
});

// --- PAGINACIÓN Y BÚSQUEDA DEL HISTORIAL DE ENVÍOS (CLIENT-SIDE) ---
let paginaActualLogs = 1;
const logsPorPagina = 10;

function filtrarLogsHistorial() {
    paginaActualLogs = 1;
    renderizarPaginacionLogs();
}

function renderizarPaginacionLogs() {
    const input = document.getElementById('input-buscar-log');
    const query = input ? input.value.toLowerCase().trim() : '';

    const filas = Array.from(document.querySelectorAll('.fila-log-correo'));
    if (!filas.length) return;

    // Filtrar filas
    const filasFiltradas = filas.filter(fila => {
        const dest = fila.querySelector('.col-destinatario')?.textContent.toLowerCase() || '';
        const asunto = fila.querySelector('.col-asunto')?.textContent.toLowerCase() || '';
        return dest.includes(query) || asunto.includes(query);
    });

    const totalRegistros = filasFiltradas.length;
    const totalPaginas = Math.ceil(totalRegistros / logsPorPagina) || 1;

    if (paginaActualLogs > totalPaginas) paginaActualLogs = totalPaginas;

    // Ocultar todas las filas primero
    filas.forEach(f => f.style.display = 'none');

    // Mostrar solo las de la página actual
    const inicio = (paginaActualLogs - 1) * logsPorPagina;
    const fin = inicio + logsPorPagina;
    const paginadas = filasFiltradas.slice(inicio, fin);

    paginadas.forEach(f => f.style.display = '');

    // Actualizar contador
    const infoSpan = document.getElementById('info-paginacion-logs');
    if (infoSpan) {
        if (totalRegistros === 0) {
            infoSpan.textContent = 'No se encontraron registros en el historial.';
        } else {
            const desde = inicio + 1;
            const hasta = Math.min(fin, totalRegistros);
            infoSpan.textContent = `Mostrando ${desde} - ${hasta} de ${totalRegistros} registros`;
        }
    }

    // Dibujar botones de paginación
    const contPaginacion = document.getElementById('contenedor-paginacion-logs');
    if (contPaginacion) {
        contPaginacion.innerHTML = '';
        if (totalPaginas <= 1) return;

        // Botón Anterior
        const btnAnt = document.createElement('button');
        btnAnt.type = 'button';
        btnAnt.innerHTML = '<i class="ph-bold ph-caret-left"></i>';
        btnAnt.disabled = paginaActualLogs === 1;
        btnAnt.style.cssText = 'background:#ffffff; border:1px solid #cbd5e1; padding:4px 10px; border-radius:4px; font-weight:700; cursor:pointer; font-size:0.8rem;';
        btnAnt.onclick = () => { if (paginaActualLogs > 1) { paginaActualLogs--; renderizarPaginacionLogs(); } };
        contPaginacion.appendChild(btnAnt);

        // Números de página
        for (let i = 1; i <= totalPaginas; i++) {
            const btnNum = document.createElement('button');
            btnNum.type = 'button';
            btnNum.textContent = i;
            if (i === paginaActualLogs) {
                btnNum.style.cssText = 'background:var(--color-secundario); color:#ffffff !important; border:1px solid var(--color-secundario); padding:4px 10px; border-radius:4px; font-weight:700; font-size:0.8rem;';
            } else {
                btnNum.style.cssText = 'background:#ffffff; color:#334155 !important; border:1px solid #cbd5e1; padding:4px 10px; border-radius:4px; font-weight:700; cursor:pointer; font-size:0.8rem;';
                btnNum.onclick = () => { paginaActualLogs = i; renderizarPaginacionLogs(); };
            }
            contPaginacion.appendChild(btnNum);
        }

        // Botón Siguiente
        const btnSig = document.createElement('button');
        btnSig.type = 'button';
        btnSig.innerHTML = '<i class="ph-bold ph-caret-right"></i>';
        btnSig.disabled = paginaActualLogs === totalPaginas;
        btnSig.style.cssText = 'background:#ffffff; border:1px solid #cbd5e1; padding:4px 10px; border-radius:4px; font-weight:700; cursor:pointer; font-size:0.8rem;';
        btnSig.onclick = () => { if (paginaActualLogs < totalPaginas) { paginaActualLogs++; renderizarPaginacionLogs(); } };
        contPaginacion.appendChild(btnSig);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const primerBtn = document.querySelector('.tpl-item-btn');
    if (primerBtn) primerBtn.click();

    // Inicializar paginación de logs
    renderizarPaginacionLogs();

    // Activar pestaña según parámetro de URL si existe (ej. ?tab=tabConexionSmtp)
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    if (tabParam) {
        const btnTab = document.querySelector(`button[onclick*="'${tabParam}'"]`);
        if (btnTab) {
            btnTab.click();
        }
    }
});
</script>
