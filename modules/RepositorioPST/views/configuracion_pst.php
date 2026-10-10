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
            <div style="background-color: rgba(80, 89, 132, 0.08); border: 1px solid rgba(80, 89, 132, 0.25); color: var(--color-secundario); padding: 0.75rem 1rem; border-radius: var(--radius-md); margin-bottom: 1rem; font-size: 0.88rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph ph-check-circle" style="font-size: 1.2rem;"></i> <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div style="background-color: rgba(100, 116, 139, 0.1); border: 1px solid rgba(100, 116, 139, 0.25); color: var(--texto-titulos); padding: 0.75rem 1rem; border-radius: var(--radius-md); margin-bottom: 1rem; font-size: 0.88rem; display: flex; align-items: center; gap: 0.5rem;">
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
                <button type="button" class="config-tab-btn" onclick="switchConfigTab('tabNiveles', this)">
                    <i class="ph ph-graduation-cap"></i> Niveles Académicos
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
                                        <span class="field-hint">ID: <?= htmlspecialchars($slug) ?></span>
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
                            <label>Modo de Carga del Catálogo:</label>
                            <?php $modoCargaActualConfig = $config['paginacion']['modo_carga'] ?? 'paginador'; ?>
                            <select name="modo_carga" class="config-input" style="font-weight: 700;">
                                <option value="paginador" <?= $modoCargaActualConfig === 'paginador' ? 'selected' : '' ?>>Paginación Numérica Tradicional</option>
                                <option value="lazy_loading" <?= $modoCargaActualConfig === 'lazy_loading' ? 'selected' : '' ?>>Carga Progresiva Infinita</option>
                            </select>
                            <p class="field-hint">Alterna el comportamiento de navegación en la vista general del repositorio.</p>
                        </div>

                        <div class="config-field">
                            <label>Registros por página en Catálogo Principal:</label>
                            <input type="number" name="limite_catalogo" value="<?= (int)($config['paginacion']['limite_catalogo'] ?? 10) ?>" min="1" max="100" class="config-input">
                            <p class="field-hint">Cantidad predeterminada de proyectos por vista en la lista general.</p>
                        </div>
                    </div>

                    <div class="config-grid-2">
                        <div class="config-field">
                            <label>Registros por página en Buscador Unificado:</label>
                            <input type="number" name="limite_buscador" value="<?= (int)($config['paginacion']['limite_buscador'] ?? 5) ?>" min="1" max="100" class="config-input">
                            <p class="field-hint">Cantidad de resultados a retornar en las búsquedas.</p>
                        </div>
                    </div>

                    <div class="config-grid-2">
                        <div class="config-field">
                            <label>Máximo de Investigaciones Afines:</label>
                            <input type="number" name="max_proyectos_similares" value="<?= (int)($config['paginacion']['max_proyectos_similares'] ?? 3) ?>" min="1" max="10" class="config-input">
                            <p class="field-hint">Número de proyectos mostrados al pie de la Ficha Técnica.</p>
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
                                <option value="anio_desc" <?= ($config['buscador']['orden_predeterminado'] ?? '') === 'anio_desc' ? 'selected' : '' ?>>Año: Más reciente</option>
                                <option value="anio_asc" <?= ($config['buscador']['orden_predeterminado'] ?? '') === 'anio_asc' ? 'selected' : '' ?>>Año: Más antiguo</option>
                                <option value="titulo_asc" <?= ($config['buscador']['orden_predeterminado'] ?? '') === 'titulo_asc' ? 'selected' : '' ?>>Título: A - Z</option>
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

                    <div class="config-switch-row" style="margin-top: 0.5rem;">
                        <div class="config-switch-label">
                            <strong>Habilitar Filtro Dinámico de Carrera</strong>
                            <span>Permite alternar y filtrar por diferentes carreras en el buscador sin fijar la carrera 1.</span>
                        </div>
                        <label class="switch-toggle">
                            <input type="checkbox" name="permitir_filtro_carrera" value="1" <?= !empty($config['buscador']['permitir_filtro_carrera']) ? 'checked' : '' ?>>
                            <span class="switch-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="config-card">
                    <div class="config-card-header">
                        <h3 class="config-card-title">
                            <i class="ph ph-file-text"></i> Parámetros del Visor de Documentos
                        </h3>
                    </div>

                    <div class="config-switch-row">
                        <label for="switch_mostrar_toolbar" class="config-switch-label" style="cursor: pointer;">
                            <strong>Mostrar Barra de Herramientas del Visor</strong>
                            <span>Si se activa, el visor mostrará controles de navegación, zoom e impresión tanto en documentos PDF como en Word.</span>
                        </label>
                        <label class="switch-toggle">
                            <input type="checkbox" id="switch_mostrar_toolbar" name="mostrar_toolbar" value="1" <?= !empty($config['visor_pdf']['mostrar_toolbar']) ? 'checked' : '' ?>>
                            <span class="switch-slider"></span>
                        </label>
                    </div>

                    <div class="config-switch-row">
                        <label for="switch_permitir_descarga" class="config-switch-label" style="cursor: pointer;">
                            <strong>Permitir Descarga Directa del Documento</strong>
                            <span>Habilita los botones de descarga en fichas, buscador y catálogo cuando el archivo digital se encuentra en el servidor.</span>
                        </label>
                        <label class="switch-toggle">
                            <input type="checkbox" id="switch_permitir_descarga" name="permitir_descarga" value="1" <?= !empty($config['visor_pdf']['permitir_descarga']) ? 'checked' : '' ?>>
                            <span class="switch-slider"></span>
                        </label>
                    </div>

                    <div class="config-field" style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid rgba(169, 168, 166, 0.2);">
                        <label for="nivel_minimo_descarga">
                            <i class="ph ph-shield-check"></i> Nivel Mínimo Requerido para Descarga:
                        </label>
                        <?php 
                        $nivelActual = (int)($config['visor_pdf']['nivel_minimo_descarga'] ?? 999);
                        ?>
                        <select name="nivel_minimo_descarga" id="nivel_minimo_descarga" class="config-input">
                            <option value="999" <?= $nivelActual === 999 ? 'selected' : '' ?>>
                                Público General - Sin inicio de sesión
                            </option>
                            <option value="998" <?= $nivelActual === 998 ? 'selected' : '' ?>>
                                Cualquier Usuario Autenticado - Cuentas registradas
                            </option>
                            <?php if (!empty($rolesDisponibles)): ?>
                                <?php foreach ($rolesDisponibles as $rol): ?>
                                    <option value="<?= (int)$rol['nivel_privilegio'] ?>" <?= $nivelActual === (int)$rol['nivel_privilegio'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($rol['nombre']) ?> o superior - Nivel <?= (int)$rol['nivel_privilegio'] ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="5" <?= $nivelActual === 5 ? 'selected' : '' ?>>Estudiantes o superior - Nivel 5</option>
                                <option value="2" <?= $nivelActual === 2 ? 'selected' : '' ?>>Docentes o superior - Nivel 2</option>
                                <option value="1" <?= $nivelActual === 1 ? 'selected' : '' ?>>Comité o superior - Nivel 1</option>
                                <option value="0" <?= $nivelActual === 0 ? 'selected' : '' ?>>Solo Super Administrador - Nivel 0</option>
                            <?php endif; ?>
                        </select>
                        <p class="field-hint">Controla qué nivel de usuario podrá ver el botón de descarga y descargar directamente los archivos digitales del repositorio.</p>
                    </div>
                </div>
            </div>

            <!-- TAB 5: CARGA Y LÍMITES DE EQUIPO -->
            <div id="tabArchivos" class="config-tab-pane">
                <div class="config-card">
                    <div class="config-card-header">
                        <h3 class="config-card-title">
                            <i class="ph ph-hard-drives"></i> Restricciones de Carga y Procesamiento de Documentos
                        </h3>
                    </div>

                    <div class="config-grid-2">
                        <div class="config-field">
                            <label>Tamaño Máximo Permitido por Archivo (MB):</label>
                            <input type="number" name="max_size_mb" value="<?= (int)($config['archivos']['max_size_mb'] ?? 20) ?>" min="1" max="500" class="config-input">
                            <p class="field-hint">Límite máximo por archivo individual PDF/DOCX al registrar un PST.</p>
                        </div>

                        <div class="config-field">
                            <label>Máximo de Documentos por Lote:</label>
                            <input type="number" name="max_archivos_lote" value="<?= (int)($config['archivos']['max_archivos_lote'] ?? 5) ?>" min="1" max="50" class="config-input">
                            <p class="field-hint">Cantidad máxima de proyectos que pueden cargarse simultáneamente en cola.</p>
                        </div>
                    </div>

                    <div class="config-grid-2" style="margin-top: 1rem;">
                        <div class="config-field">
                            <label>Límite de Páginas para Análisis de Metadatos:</label>
                            <input type="number" name="max_paginas_analisis" value="<?= (int)($config['archivos']['max_paginas_analisis'] ?? 15) ?>" min="1" max="100" class="config-input">
                            <p class="field-hint">Páginas iniciales a procesar (título, autores, tutores, resumen y objetivos). Reduce drásticamente el consumo de memoria RAM.</p>
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
                            <label>Máximo de Estudiantes Autores:</label>
                            <input type="number" name="max_autores" value="<?= (int)($config['limites_equipo']['max_autores'] ?? 4) ?>" min="1" max="10" class="config-input">
                            <p class="field-hint">Límite máximo de integrantes por PST.</p>
                        </div>

                        <div class="config-field">
                            <label>Máximo de Tutores:</label>
                            <input type="number" name="max_tutores" value="<?= (int)($config['limites_equipo']['max_tutores'] ?? 3) ?>" min="1" max="5" class="config-input">
                            <p class="field-hint">Límite máximo de tutores (académico, institucional, comunitario).</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 6: NIVELES ACADÉMICOS DINÁMICOS -->
            <div id="tabNiveles" class="config-tab-pane">
                <div class="config-card" style="padding: 1.5rem; background: #ffffff;">
                    <div class="config-card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem;">
                        <div>
                            <h3 class="config-card-title" style="font-size: 1rem;">
                                <i class="ph ph-graduation-cap"></i> Gestión de Niveles Académicos
                            </h3>
                        </div>
                        <button type="button" class="btn-save-sm" onclick="abrirModalCrearNivel()" style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 1.1rem; font-size: 0.82rem; cursor: pointer;">
                            <i class="ph ph-plus-circle" style="font-size: 1.1rem;"></i> Añadir Nivel Académico
                        </button>
                    </div>

                    <div style="background-color: #fafbfe; border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 6px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.82rem; color: var(--texto-silenciado); line-height: 1.45; display: flex; align-items: flex-start; gap: 0.6rem;">
                        <i class="ph ph-info" style="font-size: 1.2rem; color: var(--color-secundario); flex-shrink: 0; margin-top: 1px;"></i>
                        <div>
                            Los niveles académicos aquí configurados se reflejan en los formularios de registro de PST, filtros de búsqueda, histogramas y fichas técnicas. Si un nivel requiere trayecto, el sistema habilitará automáticamente el selector de trayectos del PNF correspondiente.
                        </div>
                    </div>

                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="pst-table" style="width: 100%; border-collapse: separate; border-spacing: 0; font-size: 0.85rem;">
                            <thead>
                                <tr style="background: rgba(80, 89, 132, 0.05); border-bottom: 2px solid rgba(80, 89, 132, 0.15);">
                                    <th style="padding: 0.75rem 0.85rem; width: 70px; font-weight: 800; color: var(--color-secundario); text-align: center;">Orden</th>
                                    <th style="padding: 0.75rem 0.85rem; font-weight: 800; color: var(--texto-titulos);">Nivel Académico</th>
                                    <th style="padding: 0.75rem 0.85rem; font-weight: 800; color: var(--texto-titulos);">Código Identificador</th>
                                    <th style="padding: 0.75rem 0.85rem; font-weight: 800; color: var(--texto-titulos); text-align: center;">Aplica Trayectos PNF</th>
                                    <th style="padding: 0.75rem 0.85rem; font-weight: 800; color: var(--texto-titulos); text-align: center;">Proyectos Vinculados</th>
                                    <th style="padding: 0.75rem 0.85rem; font-weight: 800; color: var(--texto-titulos); text-align: center;">Estado</th>
                                    <th style="padding: 0.75rem 0.85rem; font-weight: 800; color: var(--texto-titulos); text-align: center;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($nivelesAcademicosDetallados)): ?>
                                    <tr>
                                        <td colspan="7" style="padding: 2.5rem 1rem; text-align: center; color: var(--texto-silenciado); background: #ffffff;">
                                            No hay niveles académicos registrados en el sistema.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($nivelesAcademicosDetallados as $nItem): 
                                        $esActivo = !empty($nItem['activo']);
                                        $totalProy = (int)($nItem['total_proyectos'] ?? 0);
                                    ?>
                                        <tr style="border-bottom: 1px solid rgba(169, 168, 166, 0.15); transition: background 0.15s; background: #ffffff;">
                                            <td style="padding: 0.85rem 0.85rem; font-weight: 800; color: var(--color-secundario); text-align: center; vertical-align: middle;">
                                                <span style="background: rgba(80, 89, 132, 0.08); padding: 0.2rem 0.55rem; border-radius: 4px; font-size: 0.8rem;">#<?= (int)$nItem['orden'] ?></span>
                                            </td>
                                            <td style="padding: 0.85rem 0.85rem; vertical-align: middle;">
                                                <strong style="color: var(--texto-titulos); font-size: 0.92rem; display: block;"><?= htmlspecialchars($nItem['nombre']) ?></strong>
                                                <?php if (!empty($nItem['descripcion'])): ?>
                                                    <div style="font-size: 0.75rem; color: var(--texto-silenciado); margin-top: 2px;">
                                                        <?= htmlspecialchars($nItem['descripcion']) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td style="padding: 0.85rem 0.85rem; vertical-align: middle;">
                                                <span class="pst-badge-soft" style="background: rgba(80, 89, 132, 0.08); color: var(--color-secundario); font-family: monospace; font-size: 0.75rem; padding: 0.2rem 0.5rem; border-radius: 4px; border: 1px solid rgba(80, 89, 132, 0.15);">
                                                    <?= htmlspecialchars($nItem['codigo']) ?>
                                                </span>
                                            </td>
                                            <td style="padding: 0.85rem 0.85rem; text-align: center; vertical-align: middle;">
                                                <?php if (!empty($nItem['requiere_trayecto'])): ?>
                                                    <span class="pst-badge-soft" style="background: rgba(0, 123, 255, 0.1); color: var(--color-terciario); padding: 0.25rem 0.55rem; border-radius: 12px; font-size: 0.72rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.25rem;">
                                                        <i class="ph ph-check-circle"></i> Sí, Trayecto I - IV
                                                    </span>
                                                <?php else: ?>
                                                    <span class="pst-badge-soft" style="background: rgba(100, 116, 139, 0.1); color: var(--texto-silenciado); padding: 0.25rem 0.55rem; border-radius: 12px; font-size: 0.72rem; font-weight: 700;">
                                                        No aplica
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td style="padding: 0.85rem 0.85rem; text-align: center; vertical-align: middle;">
                                                <span class="pst-badge-soft" style="font-weight: 700; font-size: 0.78rem; color: <?= $totalProy > 0 ? 'var(--color-secundario)' : 'var(--texto-silenciado)' ?>; background: rgba(80, 89, 132, 0.08); padding: 0.25rem 0.6rem; border-radius: 10px;">
                                                    <?= $totalProy ?> <?= $totalProy === 1 ? 'proyecto' : 'proyectos' ?>
                                                </span>
                                            </td>
                                            <td style="padding: 0.85rem 0.85rem; text-align: center; vertical-align: middle;">
                                                <?php if ($esActivo): ?>
                                                    <span class="pst-badge-soft" style="background: rgba(80, 89, 132, 0.1); color: var(--color-secundario); padding: 0.25rem 0.55rem; border-radius: 12px; font-size: 0.72rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.25rem; border: 1px solid rgba(80, 89, 132, 0.2);">
                                                        <span style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--color-secundario); display: inline-block;"></span> Activo
                                                    </span>
                                                <?php else: ?>
                                                    <span class="pst-badge-soft" style="background: rgba(169, 168, 166, 0.12); color: var(--texto-silenciado); padding: 0.25rem 0.55rem; border-radius: 12px; font-size: 0.72rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.25rem; border: 1px solid rgba(169, 168, 166, 0.25);">
                                                        <span style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--texto-silenciado); display: inline-block;"></span> Inactivo
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td style="padding: 0.85rem 0.85rem; text-align: center; vertical-align: middle;">
                                                <div class="action-links" style="display: flex; gap: 0.4rem; justify-content: center; align-items: center;">
                                                    <!-- Editar -->
                                                    <button type="button" 
                                                            class="btn-action-edit" 
                                                            onclick='abrirModalEditarNivel(<?= json_encode($nItem) ?>)'
                                                            title="Modificar este nivel académico">
                                                        <i class="ph ph-pencil-simple"></i> Editar
                                                    </button>
                                                    
                                                    <!-- Toggle Estado -->
                                                    <button type="button"
                                                            class="btn-action-edit"
                                                            onclick="ejecutarToggleNivel(<?= (int)$nItem['id'] ?>, '<?= htmlspecialchars(addslashes($nItem['nombre'])) ?>')"
                                                            title="<?= $esActivo ? 'Desactivar nivel' : 'Activar nivel' ?>"
                                                            style="color: var(--color-secundario); border-color: rgba(80, 89, 132, 0.2); background-color: rgba(80, 89, 132, 0.04);">
                                                        <i class="ph ph-power"></i> <?= $esActivo ? 'Desactivar' : 'Activar' ?>
                                                    </button>

                                                    <!-- Eliminar -->
                                                    <button type="button"
                                                            class="btn-action-edit"
                                                            onclick="abrirModalEliminarNivel(<?= (int)$nItem['id'] ?>, '<?= htmlspecialchars(addslashes($nItem['nombre'])) ?>', <?= $totalProy ?>)"
                                                            title="<?= $totalProy > 0 ? 'No se puede eliminar: tiene proyectos vinculados' : 'Eliminar nivel académico' ?>"
                                                            style="color: var(--texto-titulos); border-color: rgba(169, 168, 166, 0.35); background-color: rgba(169, 168, 166, 0.06); <?= $totalProy > 0 ? 'opacity: 0.45; cursor: not-allowed;' : '' ?>">
                                                        <i class="ph ph-trash"></i> Eliminar
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- BOTONES DE ACCIÓN PERMANENTES -->
            <div class="pst-config-footer" style="margin-top: 1.5rem; margin-bottom: 2rem;">
                <a href="?ruta=repositorio" class="btn-cancel-sm">Cancelar</a>
                <button type="submit" class="btn-save-sm">
                    <i class="ph ph-floppy-disk"></i> Guardar Cambios
                </button>
            </div>

        </form>
    </div>
</div>

<!-- MODAL: CREAR NIVEL ACADÉMICO -->
<div id="modalCrearNivel" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 34, 68, 0.7); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center;">
    <div style="background: var(--bg-card, #ffffff); border: 1px solid rgba(169, 168, 166, 0.2); border-radius: 8px; width: 90%; max-width: 520px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.25);">
        <form action="" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="accion_nivel" value="crear">

            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(169, 168, 166, 0.2); padding-bottom: 0.5rem; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--texto-titulos); margin: 0; display: flex; align-items: center; gap: 0.4rem;">
                    <i class="ph ph-graduation-cap" style="color: var(--color-secundario);"></i> Añadir Nivel Académico
                </h3>
                <button type="button" onclick="cerrarModalCrearNivel()" style="background: none; border: none; font-size: 1.3rem; cursor: pointer; color: var(--texto-silenciado);">&times;</button>
            </div>

            <div class="config-grid-2">
                <div class="config-field">
                    <label>Nombre Visible *</label>
                    <input type="text" name="nombre" id="crear_nivel_nombre" class="config-input" placeholder="Ej: Diplomado" required oninput="autogenerarCodigoCrear(this.value)">
                </div>

                <div class="config-field">
                    <label>Código Identificador *</label>
                    <input type="text" name="codigo" id="crear_nivel_codigo" class="config-input" placeholder="Ej: Diplomado" required>
                    <p class="field-hint">Identificador interno único con letras, números y guión bajo.</p>
                </div>
            </div>

            <div class="config-field" style="margin-top: 0.6rem;">
                <label>Orden de Visualización</label>
                <input type="number" name="orden" id="crear_nivel_orden" class="config-input" value="5" min="0" max="99" style="max-width: 140px;">
                <p class="field-hint">Posición numérica para ordenar en selectores y listados.</p>
            </div>

            <div class="config-switch-row" style="margin-top: 0.75rem; padding: 0.6rem 0.75rem; background: rgba(80, 89, 132, 0.03); border: 1px solid rgba(169, 168, 166, 0.2); border-radius: 6px;">
                <label for="crear_nivel_requiere_trayecto" class="config-switch-label" style="cursor: pointer; margin: 0;">
                    <strong>¿Aplica Trayectos del PNF?</strong>
                    <span>Habilita la selección de trayectos I al IV en proyectos con este nivel</span>
                </label>
                <label class="switch-toggle" style="margin: 0; flex-shrink: 0;">
                    <input type="checkbox" name="requiere_trayecto" id="crear_nivel_requiere_trayecto" value="1">
                    <span class="switch-slider"></span>
                </label>
            </div>

            <div class="config-field" style="margin-top: 0.75rem;">
                <label>Descripción Opcional</label>
                <textarea name="descripcion" id="crear_nivel_descripcion" class="config-input" rows="2" style="resize: vertical; font-family: inherit;" placeholder="Breve descripción del alcance del nivel académico..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem; border-top: 1px solid rgba(169, 168, 166, 0.15); padding-top: 1rem; margin-top: 1.25rem;">
                <button type="button" onclick="cerrarModalCrearNivel()" class="btn-cancel-sm">Cancelar</button>
                <button type="submit" class="btn-save-sm">
                    <i class="ph ph-check"></i> Guardar Nivel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: EDITAR NIVEL ACADÉMICO -->
<div id="modalEditarNivel" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 34, 68, 0.7); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center;">
    <div style="background: var(--bg-card, #ffffff); border: 1px solid rgba(169, 168, 166, 0.2); border-radius: 8px; width: 90%; max-width: 520px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.25);">
        <form action="" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="accion_nivel" value="editar">
            <input type="hidden" name="id_nivel" id="edit_nivel_id" value="">

            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(169, 168, 166, 0.2); padding-bottom: 0.5rem; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--texto-titulos); margin: 0; display: flex; align-items: center; gap: 0.4rem;">
                    <i class="ph ph-pencil-simple" style="color: var(--color-secundario);"></i> Modificar Nivel Académico
                </h3>
                <button type="button" onclick="cerrarModalEditarNivel()" style="background: none; border: none; font-size: 1.3rem; cursor: pointer; color: var(--texto-silenciado);">&times;</button>
            </div>

            <div class="config-grid-2">
                <div class="config-field">
                    <label>Nombre Visible *</label>
                    <input type="text" name="nombre" id="edit_nivel_nombre" class="config-input" required>
                </div>

                <div class="config-field">
                    <label>Código Identificador *</label>
                    <input type="text" name="codigo" id="edit_nivel_codigo" class="config-input" required>
                    <p class="field-hint">Al cambiar el código, se actualizan los proyectos vinculados automáticamente.</p>
                </div>
            </div>

            <div class="config-field" style="margin-top: 0.6rem;">
                <label>Orden de Visualización</label>
                <input type="number" name="orden" id="edit_nivel_orden" class="config-input" min="0" max="99" style="max-width: 140px;">
                <p class="field-hint">Posición numérica para ordenar en selectores y listados.</p>
            </div>

            <div class="config-switch-row" style="margin-top: 0.75rem; padding: 0.6rem 0.75rem; background: rgba(80, 89, 132, 0.03); border: 1px solid rgba(169, 168, 166, 0.2); border-radius: 6px;">
                <label for="edit_nivel_requiere_trayecto" class="config-switch-label" style="cursor: pointer; margin: 0;">
                    <strong>¿Aplica Trayectos del PNF?</strong>
                    <span>Habilita la selección de trayectos I al IV en proyectos con este nivel</span>
                </label>
                <label class="switch-toggle" style="margin: 0; flex-shrink: 0;">
                    <input type="checkbox" name="requiere_trayecto" id="edit_nivel_requiere_trayecto" value="1">
                    <span class="switch-slider"></span>
                </label>
            </div>

            <div class="config-field" style="margin-top: 0.75rem;">
                <label>Descripción</label>
                <textarea name="descripcion" id="edit_nivel_descripcion" class="config-input" rows="2" style="resize: vertical; font-family: inherit;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem; border-top: 1px solid rgba(169, 168, 166, 0.15); padding-top: 1rem; margin-top: 1.25rem;">
                <button type="button" onclick="cerrarModalEditarNivel()" class="btn-cancel-sm">Cancelar</button>
                <button type="submit" class="btn-save-sm">
                    <i class="ph ph-check"></i> Actualizar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: ELIMINAR NIVEL ACADÉMICO -->
<div id="modalEliminarNivel" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 34, 68, 0.7); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center;">
    <div style="background: var(--bg-card, #ffffff); border: 1px solid rgba(169, 168, 166, 0.2); border-radius: 8px; width: 90%; max-width: 480px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.25);">
        <form action="" method="POST" id="formEliminarNivel">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="accion_nivel" value="eliminar">
            <input type="hidden" name="id_nivel" id="del_nivel_id" value="">

            <div style="text-align: center; margin-bottom: 1rem;">
                <div style="width: 52px; height: 52px; margin: 0 auto 0.75rem auto; border-radius: 50%; background: rgba(80, 89, 132, 0.1); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: var(--color-secundario);">
                    <i class="ph ph-trash"></i>
                </div>
                <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--texto-titulos); margin: 0 0 0.5rem 0;">¿Eliminar Nivel Académico?</h3>
                <p id="del_nivel_mensaje" style="font-size: 0.85rem; color: var(--texto-silenciado); margin: 0; line-height: 1.45;"></p>
            </div>

            <div id="del_nivel_alerta_bloqueo" style="display: none; background: rgba(80, 89, 132, 0.08); border: 1px solid rgba(80, 89, 132, 0.25); color: var(--color-secundario); border-radius: 6px; padding: 0.75rem; font-size: 0.8rem; margin-bottom: 1rem; line-height: 1.4;">
                <i class="ph ph-warning-circle" style="font-size: 1rem; vertical-align: middle;"></i> Este nivel tiene proyectos activos asignados. El sistema no permite su eliminación para preservar la integridad de los datos. Puede desactivarlo para que no aparezca en nuevas cargas.
            </div>

            <div style="display: flex; justify-content: center; gap: 0.6rem; border-top: 1px solid rgba(169, 168, 166, 0.15); padding-top: 1rem;">
                <button type="button" onclick="cerrarModalEliminarNivel()" class="btn-cancel-sm">Cancelar</button>
                <button type="submit" id="btnConfirmarEliminarNivel" class="btn-save-sm" style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 1.25rem;">
                    <i class="ph ph-trash"></i> Confirmar Eliminación
                </button>
            </div>
        </form>
    </div>
</div>

<!-- FORMULARIO OCULTO PARA TOGGLE ESTADO -->
<form id="formToggleNivel" action="" method="POST" style="display:none;">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    <input type="hidden" name="accion_nivel" value="toggle">
    <input type="hidden" name="id_nivel" id="toggle_nivel_id" value="">
</form>

<script>
function switchConfigTab(tabId, btn) {
    document.querySelectorAll('.config-tab-pane').forEach(tp => tp.classList.remove('active'));
    document.querySelectorAll('.config-tab-btn').forEach(tb => tb.classList.remove('active'));
    
    const targetPane = document.getElementById(tabId);
    if (targetPane) targetPane.classList.add('active');
    if (btn) btn.classList.add('active');
    
    // Ocultar botón 'Guardar Cambios' de configuración general cuando se ve la pestaña de Niveles
    const footer = document.querySelector('.pst-config-footer');
    if (footer) {
        footer.style.display = (tabId === 'tabNiveles') ? 'none' : 'flex';
    }
    
    // Guardar la pestaña activa en la memoria del navegador
    sessionStorage.setItem('configPstTabActiva', tabId);
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

// --- FUNCIONES GESTIÓN DE NIVELES ACADÉMICOS ---
function abrirModalCrearNivel() {
    const modal = document.getElementById('modalCrearNivel');
    if (modal) {
        const nomInput = document.getElementById('crear_nivel_nombre');
        const codInput = document.getElementById('crear_nivel_codigo');
        const ordInput = document.getElementById('crear_nivel_orden');
        const reqInput = document.getElementById('crear_nivel_requiere_trayecto');
        const descInput = document.getElementById('crear_nivel_descripcion');
        if (nomInput) nomInput.value = '';
        if (codInput) codInput.value = '';
        if (ordInput) ordInput.value = '5';
        if (reqInput) reqInput.checked = false;
        if (descInput) descInput.value = '';
        modal.style.display = 'flex';
        if (nomInput) nomInput.focus();
    }
}

function cerrarModalCrearNivel() {
    const modal = document.getElementById('modalCrearNivel');
    if (modal) modal.style.display = 'none';
}

function autogenerarCodigoCrear(nombre) {
    if (!nombre) return;
    const codInput = document.getElementById('crear_nivel_codigo');
    if (!codInput) return;
    
    // Normalizar a ASCII y remover espacios / caracteres especiales
    const normalizado = nombre
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-zA-Z0-9]/g, '');
    
    codInput.value = normalizado;
}

function abrirModalEditarNivel(item) {
    if (!item) return;
    const idEl = document.getElementById('edit_nivel_id');
    const nomEl = document.getElementById('edit_nivel_nombre');
    const codEl = document.getElementById('edit_nivel_codigo');
    const ordEl = document.getElementById('edit_nivel_orden');
    const reqEl = document.getElementById('edit_nivel_requiere_trayecto');
    const descEl = document.getElementById('edit_nivel_descripcion');

    if (idEl) idEl.value = item.id || '';
    if (nomEl) nomEl.value = item.nombre || '';
    if (codEl) codEl.value = item.codigo || '';
    if (ordEl) ordEl.value = item.orden !== undefined ? item.orden : 0;
    if (reqEl) reqEl.checked = !!(item.requiere_trayecto && item.requiere_trayecto !== '0' && item.requiere_trayecto !== 0);
    if (descEl) descEl.value = item.descripcion || '';

    const modal = document.getElementById('modalEditarNivel');
    if (modal) {
        modal.style.display = 'flex';
        if (nomEl) nomEl.focus();
    }
}

function cerrarModalEditarNivel() {
    const modal = document.getElementById('modalEditarNivel');
    if (modal) modal.style.display = 'none';
}

function abrirModalEliminarNivel(id, nombre, totalProy) {
    const idEl = document.getElementById('del_nivel_id');
    const msgEl = document.getElementById('del_nivel_mensaje');
    const alerta = document.getElementById('del_nivel_alerta_bloqueo');
    const btnConfirm = document.getElementById('btnConfirmarEliminarNivel');

    if (idEl) idEl.value = id;
    if (msgEl) {
        msgEl.innerHTML = `¿Está seguro de que desea eliminar el nivel académico <strong>"${nombre}"</strong>?`;
    }

    if (totalProy > 0) {
        if (alerta) alerta.style.display = 'block';
        if (btnConfirm) {
            btnConfirm.disabled = true;
            btnConfirm.style.opacity = '0.5';
            btnConfirm.style.cursor = 'not-allowed';
            btnConfirm.title = 'No se puede eliminar porque tiene proyectos vinculados';
        }
    } else {
        if (alerta) alerta.style.display = 'none';
        if (btnConfirm) {
            btnConfirm.disabled = false;
            btnConfirm.style.opacity = '1';
            btnConfirm.style.cursor = 'pointer';
            btnConfirm.title = '';
        }
    }

    const modal = document.getElementById('modalEliminarNivel');
    if (modal) modal.style.display = 'flex';
}

function cerrarModalEliminarNivel() {
    const modal = document.getElementById('modalEliminarNivel');
    if (modal) modal.style.display = 'none';
}

function ejecutarToggleNivel(id, nombre) {
    if (confirm(`¿Desea cambiar el estado de visibilidad del nivel académico "${nombre}"?`)) {
        const idInput = document.getElementById('toggle_nivel_id');
        const form = document.getElementById('formToggleNivel');
        if (idInput && form) {
            idInput.value = id;
            form.submit();
        }
    }
}

// Inicializar previsualizaciones y gestión de pestañas al cargar la página
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

    const urlParams = new URLSearchParams(window.location.search);
    const tabUrl = urlParams.get('tab');
    const tabGuardada = tabUrl || sessionStorage.getItem('configPstTabActiva') || 'tabCitas';
    const boton = document.querySelector(`.pst-config-nav-tabs button[onclick*="'${tabGuardada}'"]`);
    if (boton) {
        switchConfigTab(tabGuardada, boton);
    }

    // Cerrar modales con tecla Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            cerrarModalCrearNivel();
            cerrarModalEditarNivel();
            cerrarModalEliminarNivel();
        }
    });

    // Cerrar modales al hacer clic en el backdrop
    ['modalCrearNivel', 'modalEditarNivel', 'modalEliminarNivel'].forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }
    });
});
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
</script>
