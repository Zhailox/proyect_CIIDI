<?php
// modules/RepositorioPST/views/configuracion_pst.php
?>
<div class="main-content">
    <div class="pst-config-wrapper">
        
        <div class="pst-config-header">
            <div>
                <h1>Gestión de Parámetros del Repositorio</h1>
                <p>Configura las reglas de citación, paginación, metadatos visibles y comportamiento general (Almacenado localmente en JSON).</p>
            </div>
            <div>
                <a href="?ruta=repositorio" class="btn-cancel-sm">
                    <i class="ph ph-arrow-left"></i> Volver al Catálogo
                </a>
            </div>
        </div>

        <?php if (!empty($mensaje)): ?>
            <div style="background-color: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 0.75rem 1rem; border-radius: var(--radius-md); margin-bottom: 1rem; font-size: 0.88rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph ph-check-circle" style="font-size: 1.2rem;"></i> <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div style="background-color: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 0.75rem 1rem; border-radius: var(--radius-md); margin-bottom: 1rem; font-size: 0.88rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph ph-warning-circle" style="font-size: 1.2rem;"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" id="configPstForm">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <!-- PESTAÑAS DE NAVEGACIÓN -->
            <div class="pst-config-nav-tabs">
                <button type="button" class="config-tab-btn active" onclick="switchConfigTab('tabCitas', this)">
                    <i class="ph ph-quotes"></i> Formatos de Cita
                </button>
                <button type="button" class="config-tab-btn" onclick="switchConfigTab('tabPaginacion', this)">
                    <i class="ph ph-list-numbers"></i> Paginación y Límites
                </button>
                <button type="button" class="config-tab-btn" onclick="switchConfigTab('tabRecursos', this)">
                    <i class="ph ph-sliders"></i> Metadatos & Visualización
                </button>
                <button type="button" class="config-tab-btn" onclick="switchConfigTab('tabBuscador', this)">
                    <i class="ph ph-magnifying-glass"></i> Buscador y Visor PDF
                </button>
                <button type="button" class="config-tab-btn" onclick="switchConfigTab('tabArchivos', this)">
                    <i class="ph ph-cloud-arrow-up"></i> Carga y Equipo PST
                </button>
                <button type="button" class="config-tab-btn" onclick="switchConfigTab('tabFidelidad', this)">
                    <i class="ph ph-shield-check"></i> Verificador de Fidelidad
                </button>
            </div>

            <!-- TAB 1: FORMATOS DE CITA ACADÉMICA -->
            <div id="tabCitas" class="config-tab-pane active">
                <div class="config-card">
                    <div class="config-card-header">
                        <h3 class="config-card-title">
                            <i class="ph ph-quotes"></i> Estilos de Cita Configurables
                        </h3>
                        <span class="field-hint">Generador de plantillas con variables dinámicas</span>
                    </div>

                    <div id="citasContainer">
                        <?php 
                        $estilos = $config['citas']['estilos'] ?? [];
                        foreach ($estilos as $slug => $estilo): 
                        ?>
                            <div class="citation-style-box" id="citation_box_<?= htmlspecialchars($slug) ?>">
                                <div class="citation-box-header">
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <input type="text" 
                                               name="citas_estilos[<?= htmlspecialchars($slug) ?>][nombre]" 
                                               value="<?= htmlspecialchars($estilo['nombre'] ?? '') ?>" 
                                               class="config-input" 
                                               style="font-weight: 700; width: 220px;" 
                                               required>
                                        <span class="field-hint">(id: <?= htmlspecialchars($slug) ?>)</span>
                                    </div>
                                    
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <label class="switch-toggle" title="Activar/Desactivar este formato">
                                            <input type="checkbox" 
                                                   name="citas_estilos[<?= htmlspecialchars($slug) ?>][activo]" 
                                                   value="1" 
                                                   <?= !empty($estilo['activo']) ? 'checked' : '' ?>>
                                            <span class="switch-slider"></span>
                                        </label>
                                        
                                        <button type="button" 
                                                class="btn-delete-style" 
                                                onclick="eliminarFormatoCita('<?= htmlspecialchars($slug) ?>', '<?= htmlspecialchars($estilo['nombre'] ?? $slug) ?>')"
                                                title="Eliminar este estilo de cita">
                                            <i class="ph ph-trash"></i> Eliminar
                                        </button>
                                    </div>
                                </div>

                                <div class="config-field">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                                        <label>Estructura de la Plantilla:</label>
                                        
                                        <!-- Barra de Chips de Variables Rápidas (Add Flag UX) -->
                                        <div style="display: flex; gap: 0.35rem; align-items: center; flex-wrap: wrap;">
                                            <span class="field-hint" style="font-weight: 700;">Insertar Variable:</span>
                                            <button type="button" class="tag-pill-btn" onclick="insertarVariableEnInput('input_tpl_<?= htmlspecialchars($slug) ?>', '{autores}')">+ {autores}</button>
                                            <button type="button" class="tag-pill-btn" onclick="insertarVariableEnInput('input_tpl_<?= htmlspecialchars($slug) ?>', '{anio}')">+ {anio}</button>
                                            <button type="button" class="tag-pill-btn" onclick="insertarVariableEnInput('input_tpl_<?= htmlspecialchars($slug) ?>', '{titulo}')">+ {titulo}</button>
                                            <button type="button" class="tag-pill-btn" onclick="insertarVariableEnInput('input_tpl_<?= htmlspecialchars($slug) ?>', '{carrera}')">+ {carrera}</button>
                                        </div>
                                    </div>
                                    
                                    <input type="text" 
                                           id="input_tpl_<?= htmlspecialchars($slug) ?>"
                                           name="citas_estilos[<?= htmlspecialchars($slug) ?>][plantilla]" 
                                           value="<?= htmlspecialchars($estilo['plantilla'] ?? '') ?>" 
                                           class="config-input citation-template-input" 
                                           oninput="actualizarPrevisualizacionCita('<?= htmlspecialchars($slug) ?>', this.value)">
                                </div>

                                <div>
                                    <span class="field-hint">Previsualización interactiva en tiempo real:</span>
                                    <div class="citation-preview-live" id="preview_<?= htmlspecialchars($slug) ?>">
                                        <!-- Se poblará por JS -->
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Agregar nuevo formato de cita -->
                    <div style="border-top: 1px dashed rgba(169, 168, 166, 0.2); padding-top: 1rem; margin-top: 1rem;">
                        <h4 style="font-size: 0.85rem; font-weight: 700; color: var(--texto-titulos); margin: 0 0 0.5rem 0;">
                            <i class="ph ph-plus-circle"></i> Agregar Nuevo Formato de Cita (ej. APA 8, Vancouver, Chicago)
                        </h4>
                        <div class="config-grid-2">
                            <div class="config-field">
                                <label>Identificador Único (slug sin espacios):</label>
                                <input type="text" name="nuevo_estilo_slug" placeholder="ej. apa8, mla, chicago" class="config-input">
                            </div>
                            <div class="config-field">
                                <label>Nombre Descriptivo:</label>
                                <input type="text" name="nuevo_estilo_nombre" placeholder="ej. Estilo APA (8va edición)" class="config-input">
                            </div>
                        </div>
                        <div class="config-field">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                                <label>Plantilla del Nuevo Estilo:</label>
                                <div style="display: flex; gap: 0.35rem; align-items: center; flex-wrap: wrap;">
                                    <span class="field-hint" style="font-weight: 700;">Insertar Variable:</span>
                                    <button type="button" class="tag-pill-btn" onclick="insertarVariableEnInput('nuevo_estilo_plantilla_input', '{autores}')">+ {autores}</button>
                                    <button type="button" class="tag-pill-btn" onclick="insertarVariableEnInput('nuevo_estilo_plantilla_input', '{anio}')">+ {anio}</button>
                                    <button type="button" class="tag-pill-btn" onclick="insertarVariableEnInput('nuevo_estilo_plantilla_input', '{titulo}')">+ {titulo}</button>
                                    <button type="button" class="tag-pill-btn" onclick="insertarVariableEnInput('nuevo_estilo_plantilla_input', '{carrera}')">+ {carrera}</button>
                                </div>
                            </div>
                            <input type="text" 
                                   id="nuevo_estilo_plantilla_input"
                                   name="nuevo_estilo_plantilla" 
                                   placeholder="{autores} ({anio}). {titulo}. UPTTMBI." 
                                   class="config-input citation-template-input">
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: PAGINACIÓN Y LÍMITES -->
            <div id="tabPaginacion" class="config-tab-pane">
                <div class="config-card">
                    <div class="config-card-header">
                        <h3 class="config-card-title">
                            <i class="ph ph-list-numbers"></i> Ajustes de Paginación y Consultas
                        </h3>
                    </div>

                    <div class="config-grid-2">
                        <div class="config-field">
                            <label>Registros por página en Catálogo Principal:</label>
                            <input type="number" name="limite_catalogo" value="<?= (int)($config['paginacion']['limite_catalogo'] ?? 10) ?>" min="1" max="100" class="config-input">
                            <p class="field-hint">Cantidad predeterminada de proyectos por vista en la lista general.</p>
                        </div>

                        <div class="config-field">
                            <label>Registros por página en Buscador Unificado:</label>
                            <input type="number" name="limite_buscador" value="<?= (int)($config['paginacion']['limite_buscador'] ?? 5) ?>" min="1" max="100" class="config-input">
                            <p class="field-hint">Cantidad de resultados a retornar en las búsquedas.</p>
                        </div>
                    </div>

                    <div class="config-grid-2">
                        <div class="config-field">
                            <label>Máximo de Investigaciones Afines (Recomendados):</label>
                            <input type="number" name="max_proyectos_similares" value="<?= (int)($config['paginacion']['max_proyectos_similares'] ?? 3) ?>" min="1" max="10" class="config-input">
                            <p class="field-hint">Número de proyectos mostrados al pie de la Ficha Técnica.</p>
                        </div>

                        <div class="config-field">
                            <label>Opciones del Selector de Paginación (separadas por coma):</label>
                            <?php 
                            $optsRaw = implode(', ', $config['paginacion']['opciones_selector'] ?? [5, 10, 15, 20, 50]);
                            ?>
                            <input type="text" name="opciones_selector_raw" value="<?= htmlspecialchars($optsRaw) ?>" class="config-input">
                            <p class="field-hint">Valores seleccionables por el usuario (ej. 5, 10, 15, 20, 50).</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: METADATOS Y VISUALIZACIÓN -->
            <div id="tabRecursos" class="config-tab-pane">
                <div class="config-card">
                    <div class="config-card-header">
                        <h3 class="config-card-title">
                            <i class="ph ph-sliders"></i> Nomenclatura y Campos Visibles
                        </h3>
                    </div>

                    <div class="config-field">
                        <label>Sufijo / Nomenclatura del Tipo de Recurso:</label>
                        <input type="text" name="sufijo_tipo_recurso" value="<?= htmlspecialchars($config['recursos']['sufijo_tipo_recurso'] ?? 'PST / Proyecto Socio-Tecnológico') ?>" class="config-input">
                        <p class="field-hint">Texto utilizado para identificar los proyectos en etiquetas y resultados.</p>
                    </div>

                    <div style="margin-top: 1rem;">
                        <h4 style="font-size: 0.85rem; font-weight: 700; color: var(--texto-titulos); margin-bottom: 0.5rem;">Visibilidad de Campos en Ficha Técnica:</h4>
                        
                        <div class="config-switch-row">
                            <div class="config-switch-label">
                                <strong>Mostrar Enlace a Repositorio Git / Código Fuente</strong>
                                <span>Despliega la URL del código si fue registrada.</span>
                            </div>
                            <label class="switch-toggle">
                                <input type="checkbox" name="mostrar_url_git" value="1" <?= !empty($config['recursos']['mostrar_url_git']) ? 'checked' : '' ?>>
                                <span class="switch-slider"></span>
                            </label>
                        </div>

                        <div class="config-switch-row">
                            <div class="config-switch-label">
                                <strong>Mostrar Comunidad / Ente Beneficiario</strong>
                                <span>Permite visualizar e interactuar con coincidencias de comunidades.</span>
                            </div>
                            <label class="switch-toggle">
                                <input type="checkbox" name="mostrar_comunidad" value="1" <?= !empty($config['recursos']['mostrar_comunidad']) ? 'checked' : '' ?>>
                                <span class="switch-slider"></span>
                            </label>
                        </div>

                        <div class="config-switch-row">
                            <div class="config-switch-label">
                                <strong>Mostrar Nivel Académico y Trayecto</strong>
                                <span>Informa si pertenece a Pregrado / Trayecto I-IV.</span>
                            </div>
                            <label class="switch-toggle">
                                <input type="checkbox" name="mostrar_nivel_academico" value="1" <?= !empty($config['recursos']['mostrar_nivel_academico']) ? 'checked' : '' ?>>
                                <span class="switch-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 4: BUSCADOR Y VISOR PDF -->
            <div id="tabBuscador" class="config-tab-pane">
                <div class="config-card">
                    <div class="config-card-header">
                        <h3 class="config-card-title">
                            <i class="ph ph-magnifying-glass"></i> Comportamiento del Buscador
                        </h3>
                    </div>

                    <div class="config-grid-2">
                        <div class="config-field">
                            <label>Año Mínimo en Histograma de Publicaciones:</label>
                            <input type="number" name="anio_minimo_histograma" value="<?= (int)($config['buscador']['anio_minimo_histograma'] ?? 2018) ?>" class="config-input">
                            <p class="field-hint">Filtrar barras del histograma a partir de este año.</p>
                        </div>

                        <div class="config-field">
                            <label>Ordenamiento Predeterminado de Resultados:</label>
                            <select name="orden_predeterminado" class="config-input">
                                <option value="anio_desc" <?= ($config['buscador']['orden_predeterminado'] ?? '') === 'anio_desc' ? 'selected' : '' ?>>Año (Más reciente a más antiguo)</option>
                                <option value="anio_asc" <?= ($config['buscador']['orden_predeterminado'] ?? '') === 'anio_asc' ? 'selected' : '' ?>>Año (Más antiguo a más reciente)</option>
                                <option value="titulo_asc" <?= ($config['buscador']['orden_predeterminado'] ?? '') === 'titulo_asc' ? 'selected' : '' ?>>Título (A - Z)</option>
                            </select>
                        </div>
                    </div>

                    <div class="config-switch-row" style="margin-top: 0.5rem;">
                        <div class="config-switch-label">
                            <strong>Resaltar Coincidencias de Búsqueda</strong>
                            <span>Resalta visualmente en amarillo las palabras buscadas en los títulos y resúmenes.</span>
                        </div>
                        <label class="switch-toggle">
                            <input type="checkbox" name="resaltar_coincidencias" value="1" <?= !empty($config['buscador']['resaltar_coincidencias']) ? 'checked' : '' ?>>
                            <span class="switch-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="config-card">
                    <div class="config-card-header">
                        <h3 class="config-card-title">
                            <i class="ph ph-file-pdf"></i> Parámetros del Visor PDF
                        </h3>
                    </div>

                    <div class="config-switch-row">
                        <div class="config-switch-label">
                            <strong>Mostrar Barra de Herramientas del Visor PDF</strong>
                            <span>Si se activa, el iframe mostrará botones de impresión y zoom nativos del navegador.</span>
                        </div>
                        <label class="switch-toggle">
                            <input type="checkbox" name="mostrar_toolbar" value="1" <?= !empty($config['visor_pdf']['mostrar_toolbar']) ? 'checked' : '' ?>>
                            <span class="switch-slider"></span>
                        </label>
                    </div>

                    <div class="config-switch-row">
                        <div class="config-switch-label">
                            <strong>Permitir Descarga Directa del Documento</strong>
                            <span>Habilita los botones de descarga de archivos asociados.</span>
                        </div>
                        <label class="switch-toggle">
                            <input type="checkbox" name="permitir_descarga" value="1" <?= !empty($config['visor_pdf']['permitir_descarga']) ? 'checked' : '' ?>>
                            <span class="switch-slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- TAB 5: CARGA Y LÍMITES DE EQUIPO -->
            <div id="tabArchivos" class="config-tab-pane">
                <div class="config-card">
                    <div class="config-card-header">
                        <h3 class="config-card-title">
                            <i class="ph ph-hard-drives"></i> Restricciones de Carga de Archivos
                        </h3>
                    </div>

                    <div class="config-grid-2">
                        <div class="config-field">
                            <label>Tamaño Máximo Permitido (MB):</label>
                            <input type="number" name="max_size_mb" value="<?= (int)($config['archivos']['max_size_mb'] ?? 20) ?>" min="1" max="500" class="config-input">
                            <p class="field-hint">Límite máximo por archivo PDF/DOCX al registrar un nuevo PST.</p>
                        </div>
                    </div>
                </div>

                <div class="config-card">
                    <div class="config-card-header">
                        <h3 class="config-card-title">
                            <i class="ph ph-users-three"></i> Límites Integrantes del Proyecto
                        </h3>
                    </div>

                    <div class="config-grid-2">
                        <div class="config-field">
                            <label>Máximo de Estudiantes (Autores):</label>
                            <input type="number" name="max_autores" value="<?= (int)($config['limites_equipo']['max_autores'] ?? 4) ?>" min="1" max="10" class="config-input">
                            <p class="field-hint">Límite máximo de integrantes por PST.</p>
                        </div>

                        <div class="config-field">
                            <label>Máximo de Tutores Asesores:</label>
                            <input type="number" name="max_tutores" value="<?= (int)($config['limites_equipo']['max_tutores'] ?? 2) ?>" min="1" max="5" class="config-input">
                            <p class="field-hint">Límite máximo de tutores por PST.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 6: VERIFICADOR DE FIDELIDAD DE EXTRACCIÓN -->
            <div id="tabFidelidad" class="config-tab-pane">
                <div class="config-card">
                    <div class="config-card-header">
                        <h3 class="config-card-title">
                            Verificador de Fidelidad de Extracción de Metadatos
                        </h3>
                    </div>

                    <div style="background-color: var(--bg-card, #ffffff); border: 2px dashed rgba(169, 168, 166, 0.4); border-radius: var(--radius-md, 6px); padding: 1.5rem; text-align: center; cursor: pointer; transition: border-color 0.2s;" id="dropzoneFidelidad">
                        <h4 style="margin: 0 0 0.25rem 0; font-size: 0.95rem; font-weight: 700; color: var(--texto-titulos);">Prueba de Fidelidad de Documento</h4>
                        <input type="file" id="fileFidelidadInput" accept=".pdf,.docx" style="display: none;">
                        <button type="button" class="btn-save-sm" id="btnBrowseFidelidad" style="margin-top: 0.5rem;">
                            Seleccionar Documento
                        </button>
                        <span id="selectedFileNameFidelidad" style="display: block; margin-top: 0.5rem; font-size: 0.8rem; font-weight: 700; color: var(--color-secundario);"></span>
                    </div>

                    <div style="margin-top: 1rem; text-align: center;">
                        <button type="button" class="btn-save-sm" style="padding: 0.55rem 1.5rem;" onclick="ejecutarPruebaFidelidad()">
                            Ejecutar Verificación
                        </button>
                    </div>

                    <!-- Panel de Resultados de la Prueba -->
                    <div id="resultadoFidelidadBox" style="display: none; margin-top: 1.25rem; background-color: var(--bg-card, #ffffff); border: 1px solid rgba(169, 168, 166, 0.3); border-radius: var(--radius-md, 6px); padding: 1rem;">
                        <h4 style="font-size: 0.88rem; font-weight: 800; color: var(--color-secundario); margin: 0 0 0.75rem 0; border-bottom: 1px solid rgba(169, 168, 166, 0.2); padding-bottom: 0.4rem;">
                            Reporte de Diagnóstico de Fidelidad
                        </h4>
                        
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 0.75rem; margin-bottom: 1rem;">
                            <div style="background: var(--bg-body, #f8fafc); padding: 0.6rem; border-radius: 4px; border: 1px solid rgba(169, 168, 166, 0.2);">
                                <strong style="font-size: 0.7rem; color: var(--texto-silenciado); text-transform: uppercase; display: block;">Título Detectado:</strong>
                                <span id="fidResTitulo" style="font-size: 0.82rem; font-weight: 700; color: var(--texto-titulos);"></span>
                            </div>
                            <div style="background: var(--bg-body, #f8fafc); padding: 0.6rem; border-radius: 4px; border: 1px solid rgba(169, 168, 166, 0.2);">
                                <strong style="font-size: 0.7rem; color: var(--texto-silenciado); text-transform: uppercase; display: block;">Año / Nivel Académico:</strong>
                                <span id="fidResMeta" style="font-size: 0.82rem; font-weight: 700; color: var(--texto-titulos);"></span>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 0.75rem; margin-bottom: 1rem;">
                            <div style="background: var(--bg-body, #f8fafc); padding: 0.6rem; border-radius: 4px; border: 1px solid rgba(169, 168, 166, 0.2);">
                                <strong style="font-size: 0.7rem; color: var(--texto-silenciado); text-transform: uppercase; display: block;">Autores / Integrantes:</strong>
                                <span id="fidResAutores" style="font-size: 0.8rem; color: var(--texto-normal);"></span>
                            </div>
                            <div style="background: var(--bg-body, #f8fafc); padding: 0.6rem; border-radius: 4px; border: 1px solid rgba(169, 168, 166, 0.2);">
                                <strong style="font-size: 0.7rem; color: var(--texto-silenciado); text-transform: uppercase; display: block;">Tutor / Comunidad:</strong>
                                <span id="fidResTutorComunidad" style="font-size: 0.8rem; color: var(--texto-normal);"></span>
                            </div>
                        </div>

                        <div style="background: var(--bg-body, #fafbfe); padding: 0.75rem; border-radius: 4px; border: 1px solid rgba(169, 168, 166, 0.2);">
                            <strong style="font-size: 0.7rem; color: var(--texto-silenciado); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">Texto Extraído:</strong>
                            <div id="fidResPreview" style="font-size: 0.78rem; font-family: monospace; color: var(--texto-normal); max-height: 140px; overflow-y: auto; white-space: pre-wrap; background: var(--bg-card, #ffffff); padding: 0.5rem; border-radius: 3px; border: 1px solid rgba(169, 168, 166, 0.2);"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOTONES DE ACCIÓN PERMANENTES -->
            <div class="pst-config-footer">
                <a href="?ruta=repositorio" class="btn-cancel-sm">Cancelar</a>
                <button type="submit" class="btn-save-sm">
                    <i class="ph ph-floppy-disk"></i> Guardar Cambios
                </button>
            </div>

        </form>
    </div>
</div>

<script>
function switchConfigTab(tabId, btn) {
    document.querySelectorAll('.config-tab-pane').forEach(tp => tp.classList.remove('active'));
    document.querySelectorAll('.config-tab-btn').forEach(tb => tb.classList.remove('active'));
    
    document.getElementById(tabId).classList.add('active');
    btn.classList.add('active');
}

function insertarVariableEnInput(inputId, variableText) {
    const input = document.getElementById(inputId);
    if (!input) return;
    
    const startPos = input.selectionStart || input.value.length;
    const endPos = input.selectionEnd || input.value.length;
    
    input.value = input.value.substring(0, startPos) + variableText + input.value.substring(endPos);
    input.focus();
    
    const newPos = startPos + variableText.length;
    input.setSelectionRange(newPos, newPos);
    
    // Disparar evento de previsualización
    const nameAttr = input.getAttribute('name');
    if (nameAttr) {
        const match = nameAttr.match(/citas_estilos\[([^\]]+)\]/);
        if (match && match[1]) {
            actualizarPrevisualizacionCita(match[1], input.value);
        }
    }
}

function eliminarFormatoCita(slug, nombre) {
    if (confirm(`¿Estás seguro de que deseas eliminar el formato de cita "${nombre}"? esta acción surtirá efecto al guardar.`)) {
        const box = document.getElementById('citation_box_' + slug);
        if (box) {
            box.style.opacity = '0.4';
            box.style.pointerEvents = 'none';
        }
        
        // Crear hidden input dinámico para notificar eliminación al POST
        const form = document.getElementById('configPstForm');
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'eliminar_estilo';
        hiddenInput.value = slug;
        form.appendChild(hiddenInput);
        
        // Guardar automáticamente para aplicar cambio limpio
        form.submit();
    }
}

function actualizarPrevisualizacionCita(slug, plantilla) {
    const previewEl = document.getElementById('preview_' + slug);
    if (!previewEl) return;
    
    const mockData = {
        '{autores}': 'Pérez, J. & Gómez, M.',
        '{anio}': '2025',
        '{titulo}': 'Sistema de Gestión de Información Científica para Comunidades',
        '{carrera}': 'PNF en Informática'
    };
    
    let res = plantilla;
    for (const [key, val] of Object.entries(mockData)) {
        res = res.replaceAll(key, val);
    }
    
    previewEl.textContent = res;
}

// LÓGICA DEL VERIFICADOR DE FIDELIDAD DE EXTRACCIÓN CON DRAG & DROP NATIVO
document.addEventListener('DOMContentLoaded', () => {
    const fileInput = document.getElementById('fileFidelidadInput');
    const labelFileName = document.getElementById('selectedFileNameFidelidad');
    const dropzone = document.getElementById('dropzoneFidelidad');
    const btnBrowse = document.getElementById('btnBrowseFidelidad');

    if (dropzone && fileInput) {
        dropzone.addEventListener('click', (e) => {
            if (e.target !== fileInput) {
                fileInput.click();
            }
        });

        if (btnBrowse) {
            btnBrowse.addEventListener('click', (e) => {
                e.stopPropagation();
                fileInput.click();
            });
        }

        ['dragenter', 'dragover'].forEach(evt => {
            dropzone.addEventListener(evt, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.style.borderColor = 'var(--color-terciario, #007bff)';
                dropzone.style.backgroundColor = 'var(--bg-body, #f4f8ff)';
            });
        });

        ['dragleave', 'drop'].forEach(evt => {
            dropzone.addEventListener(evt, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.style.borderColor = 'rgba(169, 168, 166, 0.4)';
                dropzone.style.backgroundColor = 'var(--bg-card, #ffffff)';
            });
        });

        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            if (dt && dt.files && dt.files.length > 0) {
                fileInput.files = dt.files;
                if (labelFileName) {
                    labelFileName.textContent = 'Archivo Seleccionado: ' + dt.files[0].name;
                }
            }
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files && e.target.files.length > 0) {
                if (labelFileName) {
                    labelFileName.textContent = 'Archivo Seleccionado: ' + e.target.files[0].name;
                }
            } else {
                if (labelFileName) labelFileName.textContent = '';
            }
        });
    }
});

function ejecutarPruebaFidelidad() {
    const fileInput = document.getElementById('fileFidelidadInput');
    if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
        alert('Por favor seleccione un archivo PDF o Word (.docx) para ejecutar la prueba de fidelidad.');
        return;
    }

    const file = fileInput.files[0];
    const formData = new FormData();
    formData.append('archivo_pst', file);

    const resultBox = document.getElementById('resultadoFidelidadBox');
    if (resultBox) resultBox.style.display = 'block';

    document.getElementById('fidResTitulo').textContent = 'Procesando lectura de documento...';
    document.getElementById('fidResMeta').textContent = 'Analizando estructura...';
    document.getElementById('fidResAutores').textContent = 'Extrayendo...';
    document.getElementById('fidResTutorComunidad').textContent = 'Extrayendo...';
    document.getElementById('fidResPreview').textContent = 'Extrayendo binarios y texto...';

    fetch('?ruta=agregar-documento&accion=simular_extraccion', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            const d = data.data;
            document.getElementById('fidResTitulo').textContent = d.titulo || 'No detectado';
            document.getElementById('fidResMeta').textContent = (d.anio_publicacion || 's.f.') + ' | ' + (d.nivel_academico || 'Pregrado');
            
            const autoresList = d.autores ? d.autores.map(a => a.nombre || a.nombre_completo).filter(Boolean).join(', ') : 'Ninguno';
            document.getElementById('fidResAutores').textContent = autoresList || 'No detectados';
            
            document.getElementById('fidResTutorComunidad').textContent = 'Tutor: ' + (d.tutor_academico_nombre || 'N/A') + ' | Comunidad: ' + (d.comunidad_beneficiada || 'N/A');
            document.getElementById('fidResPreview').textContent = data.preview_texto || 'Sin vista previa';
        } else {
            alert('Error en la prueba de fidelidad: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch(err => {
        alert('Ocurrió un error al comunicarse con el servidor para la simulación.');
    });
}

// Inicializar previsualizaciones al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.citation-template-input').forEach(input => {
        const nameAttr = input.getAttribute('name');
        if (nameAttr) {
            const match = nameAttr.match(/citas_estilos\[([^\]]+)\]/);
            if (match && match[1]) {
                actualizarPrevisualizacionCita(match[1], input.value);
            }
        }
    });
});
</script>
