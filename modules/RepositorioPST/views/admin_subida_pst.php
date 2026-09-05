<?php
require_once CORE_PATH . 'Security/Auth.php';
require_once __DIR__ . '/../services/ConfigService.php';
$nivelUsuario = Auth::check() ? (int)Auth::usuario()['nivel'] : -1;
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.6.0/mammoth.browser.min.js"></script>
<script>
window.CSRF_TOKEN = <?= json_encode($_SESSION['csrf_token'] ?? '') ?>;
if (typeof window.mammoth === 'undefined') {
    document.write('<script src="modules/RepositorioPST/assets/js/mammoth.browser.min.js"><\/script>');
}
</script>
<div class="main-content">
    <div class="upload-view-container">
        
        <!-- CABECERA DE LA PÁGINA -->
        <header class="pst-header">
            <div class="pst-header-left">
                <?php if ($accion === 'crear'): ?>
                    <h1>Registrar Nuevo Proyecto</h1>
                <?php elseif ($accion === 'editar'): ?>
                    <h1>Modificar Proyecto Socio-Tecnológico</h1>
                <?php else: ?>
                    <h1>Gestión Documental PST</h1>
                <?php endif; ?>
            </div>
        </header>

        <?php if ($accion === 'listar'): ?>
            <div style="margin-bottom: 1rem; display: flex; justify-content: flex-start;">
                <a href="?ruta=agregar-documento&accion=crear" class="btn-create-new">
                    <i class="ph ph-plus-circle" style="font-size: 1rem;"></i> Agregar Nuevo Proyecto
                </a>
            </div>
        <?php endif; ?>

        <!-- VISTA 1: FORMULARIO (CREAR O EDITAR) -->
        <?php if ($accion === 'crear' || $accion === 'editar'): ?>
            
            <article class="upload-card">
                <form id="formSubidaPst" action="?ruta=agregar-documento&accion=<?= $accion ?><?= $accion === 'editar' ? '&id='.$documento['id'] : '' ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <input type="hidden" id="archivo_pdf_hidden" name="archivo_pdf" value="<?= htmlspecialchars($documento['archivo_pdf'] ?? '') ?>">

                    <div class="upload-grid">
                        
                        <!-- Columna Principal: Metadatos e Información Académica -->
                        <div class="upload-form-data">
                            
                            <h3 class="upload-section-title">
                                <i class="ph ph-book-open"></i> Metadatos de la Investigación
                            </h3>

                            <div class="upload-input-group">
                                <label for="titulo">Título de la Investigación *</label>
                                <input type="text" id="titulo" name="titulo" class="upload-input" value="<?= htmlspecialchars($_POST['titulo'] ?? $documento['titulo'] ?? '') ?>" placeholder="Ej: Sistema Web de Gestión de Inventario para SAPNNAET" required>
                            </div>

                            <div class="grid-2-cols">
                                <div class="upload-input-group">
                                    <label for="anio_publicacion">Año de Publicación *</label>
                                    <input type="number" id="anio_publicacion" name="anio_publicacion" class="upload-input" min="2018" max="2026" value="<?= htmlspecialchars($_POST['anio_publicacion'] ?? $documento['anio_publicacion'] ?? date('Y')) ?>" required>
                                </div>
                                <div class="upload-input-group">
                                    <label for="fecha_defensa">Fecha de Defensa *</label>
                                    <input type="date" id="fecha_defensa" name="fecha_defensa" class="upload-input" value="<?= htmlspecialchars($_POST['fecha_defensa'] ?? $documento['fecha_defensa'] ?? date('Y-m-d')) ?>" required>
                                </div>
                            </div>

                            <div class="grid-2-cols">
                                <div class="upload-input-group">
                                    <label for="nivel_academico">Nivel Académico *</label>
                                    <select id="nivel_academico" name="nivel_academico" class="upload-input" onchange="toggleTrayectoByNivel()" required>
                                        <?php 
                                        $currNivel = $_POST['nivel_academico'] ?? $documento['nivel_academico'] ?? 'Pregrado';
                                        ?>
                                        <option value="Pregrado" <?= ($currNivel === 'Pregrado') ? 'selected' : '' ?>>Pregrado </option>
                                        <option value="Especialización" <?= ($currNivel === 'Especialización') ? 'selected' : '' ?>>Especialización</option>
                                        <option value="Maestría" <?= ($currNivel === 'Maestría') ? 'selected' : '' ?>>Maestría</option>
                                        <option value="Doctorado" <?= ($currNivel === 'Doctorado') ? 'selected' : '' ?>>Doctorado</option>
                                    </select>
                                </div>
                                <div class="upload-input-group" id="container_trayecto" style="<?= ($currNivel === 'Pregrado') ? 'display: block;' : 'display: none;' ?>">
                                    <label for="trayecto">Trayecto del PNF *</label>
                                    <select id="trayecto" name="trayecto" class="upload-input">
                                        <?php 
                                        $currTrayecto = $_POST['trayecto'] ?? $documento['trayecto'] ?? 'Trayecto I';
                                        ?>
                                        <option value="Trayecto I" <?= ($currTrayecto === 'Trayecto I') ? 'selected' : '' ?>>Trayecto I </option>
                                        <option value="Trayecto II" <?= ($currTrayecto === 'Trayecto II') ? 'selected' : '' ?>>Trayecto II </option>
                                        <option value="Trayecto III" <?= ($currTrayecto === 'Trayecto III') ? 'selected' : '' ?>>Trayecto III </option>
                                        <option value="Trayecto IV" <?= ($currTrayecto === 'Trayecto IV') ? 'selected' : '' ?>>Trayecto IV </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Panel de Autores (Dinámico desde configuración) -->
                            <h3 class="upload-section-title">
                                <i class="ph ph-users"></i> Autores (Estudiantes del Equipo - Máx. <?= (int)ConfigService::get('limites_equipo.max_autores', 4) ?>)
                            </h3>
                            <div class="authors-container">
                                <?php 
                                $maxAutores = (int)ConfigService::get('limites_equipo.max_autores', 4);
                                if (isset($_POST['autor_cedula']) && is_array($_POST['autor_cedula'])) {
                                    $autoresList = [];
                                    for ($k = 0; $k < $maxAutores; $k++) {
                                        $autoresList[] = [
                                            'cedula' => $_POST['autor_cedula'][$k] ?? '',
                                            'nombre_completo' => $_POST['autor_nombre'][$k] ?? ''
                                        ];
                                    }
                                } else {
                                    $autoresList = $autores;
                                }
                                if (empty($autoresList)) {
                                    $autoresList = array_fill(0, $maxAutores, ['cedula' => '', 'nombre_completo' => '']);
                                }

                                for ($i = 0; $i < $maxAutores; $i++):
                                    $esObligatorio = ($i === 0);
                                    $label = 'Estudiante ' . ($i + 1) . ($esObligatorio ? ' (Autor Principal) *' : ' (Opcional)');
                                ?>
                                    <div class="sub-label-header"><?= $label ?></div>
                                    <div class="grid-2-cols">
                                        <div class="upload-input-group">
                                            <input type="text" name="autor_cedula[]" class="upload-input" value="<?= htmlspecialchars($autoresList[$i]['cedula'] ?? '') ?>" placeholder="Cédula<?= $esObligatorio ? ' (V-30123456)' : '' ?>" <?= $esObligatorio ? 'required' : '' ?>>
                                        </div>
                                        <div class="upload-input-group">
                                            <input type="text" name="autor_nombre[]" class="upload-input" value="<?= htmlspecialchars($autoresList[$i]['nombre_completo'] ?? '') ?>" placeholder="Nombres y Apellidos<?= $esObligatorio ? ' del Estudiante' : '' ?>" <?= $esObligatorio ? 'required' : '' ?>>
                                        </div>
                                    </div>
                                <?php endfor; ?>
                            </div>

                            <!-- Panel de 3 Tutores del PST -->
                            <h3 class="upload-section-title">
                                <i class="ph ph-chalkboard-teacher"></i> Tutores del Proyecto (Académico, Institucional, Comunitario)
                            </h3>
                            <div class="tutors-container">
                                <?php 
                                $tAcadCed = $_POST['tutor_academico_cedula'] ?? $tutores['academico']['cedula'] ?? '';
                                $tAcadNom = $_POST['tutor_academico_nombre'] ?? $tutores['academico']['nombre'] ?? '';
                                $tInstCed = $_POST['tutor_institucional_cedula'] ?? $tutores['institucional']['cedula'] ?? '';
                                $tInstNom = $_POST['tutor_institucional_nombre'] ?? $tutores['institucional']['nombre'] ?? '';
                                $tComCed = $_POST['tutor_comunitario_cedula'] ?? $tutores['comunitario']['cedula'] ?? '';
                                $tComNom = $_POST['tutor_comunitario_nombre'] ?? $tutores['comunitario']['nombre'] ?? '';
                                ?>
                                <div class="sub-label-header">Tutor Académico (Asesor Docente)</div>
                                <div class="grid-2-cols">
                                    <div class="upload-input-group">
                                        <input type="text" name="tutor_academico_cedula" class="upload-input" value="<?= htmlspecialchars($tAcadCed) ?>" placeholder="Cédula Tutor">
                                    </div>
                                    <div class="upload-input-group">
                                        <input type="text" name="tutor_academico_nombre" class="upload-input" value="<?= htmlspecialchars($tAcadNom) ?>" placeholder="Nombre Completo del Tutor Académico">
                                    </div>
                                </div>

                                <div class="sub-label-header">Tutor Institucional (Asesor de la Organización)</div>
                                <div class="grid-2-cols">
                                    <div class="upload-input-group">
                                        <input type="text" name="tutor_institucional_cedula" class="upload-input" value="<?= htmlspecialchars($tInstCed) ?>" placeholder="Cédula Tutor">
                                    </div>
                                    <div class="upload-input-group">
                                        <input type="text" name="tutor_institucional_nombre" class="upload-input" value="<?= htmlspecialchars($tInstNom) ?>" placeholder="Nombre Completo del Tutor Institucional">
                                    </div>
                                </div>

                                <div class="sub-label-header">Tutor Comunitario (Líder / Representante Comunal)</div>
                                <div class="grid-2-cols">
                                    <div class="upload-input-group">
                                        <input type="text" name="tutor_comunitario_cedula" class="upload-input" value="<?= htmlspecialchars($tComCed) ?>" placeholder="Cédula Tutor">
                                    </div>
                                    <div class="upload-input-group">
                                        <input type="text" name="tutor_comunitario_nombre" class="upload-input" value="<?= htmlspecialchars($tComNom) ?>" placeholder="Nombre Completo del Tutor Comunitario">
                                    </div>
                                </div>
                            </div>

                            <h3 class="upload-section-title">
                                <i class="ph ph-tree-structure"></i> Clasificación y Línea
                            </h3>

                            <div class="grid-2-cols">
                                <?php 
                                $currLinea = $_POST['linea_id'] ?? $documento['linea_id'] ?? '';
                                ?>
                                <!-- Selector de Línea de Investigación -->
                                <div class="upload-input-group">
                                    <label for="linea_id">Línea de Investigación *</label>
                                    <select id="linea_id" name="linea_id" class="upload-input" required>
                                        <option value="">Seleccione una Línea...</option>
                                        <?php foreach ($lineas as $linea): ?>
                                            <option value="<?= $linea['id'] ?>" <?= ($currLinea == $linea['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($linea['nombre'] ?? '') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Selector de Dimensión Operativa Dependiente -->
                                <div class="upload-input-group">
                                    <label for="dimension_id">Dimensión Operativa</label>
                                    <select id="dimension_id" name="dimension_id" class="upload-input" disabled>
                                        <option value="">Seleccione una Dimensión...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid-2-cols">
                                <div class="upload-input-group">
                                    <label for="comunidad_beneficiada">Comunidad u Objeto Beneficiario</label>
                                    <input type="text" id="comunidad_beneficiada" name="comunidad_beneficiada" class="upload-input" value="<?= htmlspecialchars($_POST['comunidad_beneficiada'] ?? $documento['comunidad_beneficiada'] ?? '') ?>" placeholder="Ej: Consejo Comunal SAPNNAET">
                                </div>
                                <div class="upload-input-group">
                                    <label for="palabras_clave">Palabras Clave (Keywords)</label>
                                    <input type="text" id="palabras_clave" name="palabras_clave" class="upload-input" value="<?= htmlspecialchars($_POST['palabras_clave'] ?? $documento['palabras_clave'] ?? '') ?>" placeholder="Ej: Inventario, PHP, PostgreSQL, MVC">
                                </div>
                            </div>

                            <div class="upload-input-group" style="margin-top: 0.25rem;">
                                <label for="url_repositorio">URL del Repositorio de Código (Opcional - GitHub / GitLab)</label>
                                <input type="url" id="url_repositorio" name="url_repositorio" class="upload-input" value="<?= htmlspecialchars($_POST['url_repositorio'] ?? $documento['url_repositorio'] ?? '') ?>" placeholder="Ej: https://github.com/usuario/repositorio-pst">
                            </div>

                            <div class="upload-input-group" style="margin-top: 0.5rem;">
                                <label for="obj_general">Objetivo General de la Investigación</label>
                                <textarea id="obj_general" name="obj_general" class="upload-input" rows="3" style="resize: vertical; font-family: inherit;" placeholder="Objetivo general extraído del documento (ej: Desarrollar un sistema informático para...)"><?= htmlspecialchars($_POST['obj_general'] ?? $documento['obj_general'] ?? '') ?></textarea>
                            </div>

                            <div class="upload-input-group" style="margin-top: 0.5rem;">
                                <label for="resumen">Resumen Epistémico / Resumen de Propuesta *</label>
                                <textarea id="resumen" name="resumen" class="upload-input" rows="4" style="resize: vertical; font-family: inherit;" placeholder="Redacte la síntesis del proyecto (contexto, problema, objetivo, metodología y resultados obtenidos)..." required><?= htmlspecialchars($_POST['resumen'] ?? $documento['resumen'] ?? '') ?></textarea>
                            </div>

                        </div>
                        
                        <!-- Columna Secundaria: Archivo PDF y Ayuda de Carga -->
                        <div class="upload-form-file" style="display: flex; flex-direction: column; gap: 1rem;">
                            
                            <h3 class="upload-section-title">
                                <i class="ph ph-file-pdf"></i> Documento Digital
                            </h3>

                            <!-- Generación de PDF Automática -->
                            <div style="background-color: #f8fafc; border: 1px solid rgba(169, 168, 166, 0.2); border-radius: 4px; padding: 0.75rem; font-size: 0.8rem; color: var(--texto-normal); line-height: 1.4; margin-bottom: 0.5rem;">
                                <i class="ph ph-shield-check" style="color: #31c48d; font-weight: 700; font-size: 1rem; vertical-align: middle; margin-right: 0.25rem;"></i> 
                                <strong>Ruta Automática:</strong> El sistema generará una ruta de almacenamiento segura e indexada en el repositorio basándose en el título de la investigación.
                            </div>

                            <!-- Zona Interactiva de Extracción / Sustitución de Archivo -->
                            <div class="drag-drop-zone" id="dropzone">
                                <input type="file" id="input_archivo_extractor" name="archivo_pst" accept=".pdf,.docx" <?= $accion === 'crear' ? 'multiple' : '' ?> style="display:none;">
                                <div class="drag-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="17 8 12 3 7 8"></polyline>
                                        <line x1="12" y1="3" x2="12" y2="15"></line>
                                    </svg>
                                </div>
                                <?php 
                                $maxMb = (int)ConfigService::get('archivos.max_size_mb', 20);
                                if ($accion === 'editar'): 
                                ?>
                                    <h3 class="drag-title">Sustitución de Documento Digital</h3>
                                    <p class="drag-desc">Arrastra o selecciona un nuevo archivo PDF o Word (.docx) (Máx. <?= $maxMb ?> MB) para reemplazar el documento actual.</p>
                                    <button type="button" class="btn-browse" id="btnBrowseFile">Sustituir Archivo Adjunto</button>
                                    
                                    <div id="badgeArchivoSustituido" style="display: none; margin-top: 0.75rem; background: #e0f2fe; border: 1px solid #7dd3fc; border-radius: 4px; padding: 0.5rem 0.75rem; text-align: left; font-size: 0.78rem; color: #0369a1;">
                                        <i class="ph ph-file-check" style="font-size: 1rem; vertical-align: middle; margin-right: 0.25rem;"></i>
                                        <strong>Nuevo archivo listo para sustituir:</strong> <span id="nombreArchivoSustituidoText" style="font-weight: 700;"></span>
                                    </div>
                                <?php else: ?>
                                    <h3 class="drag-title">Carga Automática e Indexación por Lotes</h3>
                                    <p class="drag-desc">Arrastra tus archivos PDF o Word (.docx) aquí (Máx. <?= $maxMb ?> MB por archivo) para auto-completar y gestionar la investigación.</p>
                                    <button type="button" class="btn-browse" id="btnBrowseFile">Seleccionar Archivo(s)</button>
                                <?php endif; ?>
                            </div>

                            <!-- COLA DE DOCUMENTOS POR LOTES UI (Exclusivo en modo Creación Masiva) -->
                            <?php if ($accion === 'crear'): ?>
                            <div id="contenedorColaDocumentos" style="display: none; background: var(--bg-card, #ffffff); border: 1px solid rgba(169, 168, 166, 0.2); border-radius: 6px; padding: 0.75rem; margin-top: 0.5rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; border-bottom: 1px solid rgba(169, 168, 166, 0.15); padding-bottom: 0.4rem;">
                                    <h4 style="font-size: 0.85rem; font-weight: 700; color: var(--texto-titulos); margin: 0; display: flex; align-items: center; gap: 0.35rem;">
                                        <i class="ph ph-stack"></i> Documentos Cargados en Lote (<span id="countCola">0</span>)
                                    </h4>
                                    <button type="button" class="btn-clear" style="padding: 0.2rem 0.5rem; font-size: 0.75rem;" onclick="limpiarColaDocumentos()">
                                        <i class="ph ph-trash"></i> Vaciar Cola
                                    </button>
                                </div>

                                <div id="listaColaItems" style="display: flex; flex-direction: column; gap: 0.5rem; max-height: 280px; overflow-y: auto; padding-right: 0.2rem;">
                                    <!-- Ítems renderizados dinámicamente -->
                                </div>

                                <div style="margin-top: 0.75rem; padding-top: 0.5rem; border-top: 1px solid rgba(169, 168, 166, 0.15);">
                                    <button type="button" class="btn-save" id="btnSubirLote" onclick="subirLoteABaseDeDatos()" style="width: 100%; display: inline-flex; justify-content: center; align-items: center; gap: 0.4rem; padding: 0.55rem 1rem;">
                                        <i class="ph ph-cloud-arrow-up" style="font-size: 1.1rem;"></i> Subir Lote a Base de Datos
                                    </button>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div style="background-color: #fafbfe; border: 1px solid rgba(0, 123, 255, 0.15); border-radius: 4px; padding: 0.75rem; font-size: 0.8rem; color: var(--texto-normal); line-height: 1.4;">
                                <i class="ph ph-info" style="color: var(--color-terciario, #007bff); font-weight: 700;"></i> 
                                <strong>Validación de Registro:</strong> Los campos marcados con <strong>*</strong> son estrictamente necesarios para que la base de datos indexe y clasifique el PST en el repositorio.
                            </div>

                        </div>

                    </div>

                    <!-- Botones de Acción -->
                    <div class="upload-actions">
                        <a href="?ruta=agregar-documento" class="btn-cancel">
                            <i class="ph ph-arrow-left"></i> Cancelar y Volver
                        </a>
                        <button type="button" class="btn-cancel" style="background-color: #f1f5f9; color: var(--color-secundario);" onclick="abrirModalPrevisualizacionDocumento()">
                            <i class="ph ph-eye"></i> Previsualizar Documento
                        </button>
                        <button type="button" class="btn-clear" id="btnLimpiarFormulario" onclick="limpiarFormularioPst()">
                            <i class="ph ph-eraser"></i> Limpiar Formulario
                        </button>
                        <button type="button" class="btn-clear" id="btnGuardarBorrador" onclick="guardarBorradorEnCola()" style="display: none; background-color: #f0fdf4; color: #15803d; border-color: rgba(21, 128, 61, 0.3);">
                            <i class="ph ph-floppy-disk"></i> Guardar en Borrador
                        </button>
                        <button type="submit" class="btn-save">
                            <?= $accion === 'editar' ? 'Guardar Cambios' : 'Registrar Recurso' ?>
                        </button>
                    </div>

                </form>
            </article>

        <!-- VISTA 2: TABLA DE GESTIÓN (LISTADO CRUD) -->
        <?php else: ?>
            
            <section class="crud-table-wrapper">
                
                <!-- Buscador rápido en el panel de control -->
                <form method="GET" action="" class="search-crud-bar">
                    <input type="hidden" name="ruta" value="agregar-documento">
                    <input type="text" name="q" value="<?= htmlspecialchars($q ?? '') ?>" class="search-crud-input" placeholder="Buscar por títulos, palabras clave o autores de PST...">
                    <button type="submit" class="btn-search-crud">Buscar</button>
                    <?php if (!empty($q)): ?>
                        <a href="?ruta=agregar-documento" class="btn-search-crud" style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">Restablecer</a>
                    <?php endif; ?>
                </form>

                <div class="table-responsive">
                    <table class="pst-table">
                        <thead>
                            <tr>
                                <th style="width: 50%;">TÍTULO DEL PROYECTO</th>
                                <th style="width: 25%;">AUTORES DEL EQUIPO</th>
                                <th style="width: 10%;">AÑO</th>
                                <th style="text-align: center; width: 15%;">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($documentos)): ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 2rem; color: var(--texto-silenciado);">
                                        No se encontraron registros de proyectos socio-tecnológicos.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($documentos as $doc): ?>
                                    <tr>
                                         <td class="pst-td-title">
                                             <div style="display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.15rem; flex-wrap: wrap;">
                                                 <span class="pst-badge-soft" style="background-color: rgba(112, 144, 203, 0.15); color: var(--color-secundario); padding: 0.1rem 0.35rem; border-radius: 3px; font-size: 0.7rem; font-weight: 700;"><?= htmlspecialchars($doc['nivel_academico'] ?? 'Pregrado') ?></span>
                                                 <?php if (($doc['nivel_academico'] ?? 'Pregrado') === 'Pregrado' && !empty($doc['trayecto'])): ?>
                                                     <span class="pst-badge-soft" style="background-color: rgba(0, 123, 255, 0.1); color: var(--color-terciario); padding: 0.1rem 0.35rem; border-radius: 3px; font-size: 0.7rem; font-weight: 700;"><?= htmlspecialchars($doc['trayecto']) ?></span>
                                                 <?php endif; ?>
                                                 <?php if (($doc['activo'] ?? true)): ?>
                                                     <span style="background: #def7ec; color: #03543f; padding: 0.1rem 0.35rem; border-radius: 3px; font-size: 0.68rem; font-weight: 700;">Visibilidad: Activo</span>
                                                 <?php else: ?>
                                                     <span style="background: #fde8e8; color: #9b1c1c; padding: 0.1rem 0.35rem; border-radius: 3px; font-size: 0.68rem; font-weight: 700;">Visibilidad: Oculto</span>
                                                 <?php endif; ?>
                                                 <?php if (!empty($doc['url_repositorio'])): ?>
                                                     <a href="<?= htmlspecialchars($doc['url_repositorio']) ?>" target="_blank" style="color: var(--color-secundario); font-size: 0.75rem; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 0.2rem;" title="Ver código fuente">
                                                         <i class="ph ph-git-branch"></i> Git
                                                     </a>
                                                 <?php endif; ?>
                                             </div>
                                             <strong><?= htmlspecialchars($doc['titulo'] ?? '') ?></strong>
                                             <?php if (!empty($doc['obj_general'])): ?>
                                                 <div style="font-size: 0.73rem; color: var(--texto-normal); margin-top: 0.15rem; font-style: italic;">
                                                     <strong>Objetivo:</strong> <?= htmlspecialchars(substr($doc['obj_general'], 0, 110)) ?>...
                                                 </div>
                                             <?php endif; ?>
                                         </td>
                                         <td><?= htmlspecialchars($doc['autores_nombres'] ?? 'No registrados') ?></td>
                                         <td><strong><?= $doc['anio_publicacion'] ?></strong></td>
                                         <td style="text-align: center;">
                                             <div class="action-links" style="display: flex; gap: 0.3rem; justify-content: center; flex-wrap: wrap;">
                                                 <!-- Opción 3: Previsualizar Ficha Completa -->
                                                 <button type="button" class="btn-action-edit" style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;" title="Previsualizar Ficha Técnica" onclick="abrirModalPrevisualizarFichaAdmin(<?= htmlspecialchars(json_encode($doc)) ?>)">
                                                     <i class="ph ph-eye"></i> Ficha
                                                 </button>

                                                 <!-- Opción 5: Descargar Documento Adjunto -->
                                                 <?php if (!empty($doc['archivo_pdf'])): ?>
                                                     <a href="?ruta=ver-pdf-pst&id=<?= $doc['id'] ?>" target="_blank" class="btn-action-edit" style="background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd;" title="Descargar / Abrir Documento Digital">
                                                         <i class="ph ph-download-simple"></i> Adjunto
                                                     </a>
                                                 <?php endif; ?>

                                                 <!-- Opción 1: Activar / Desactivar (Soft Delete) -->
                                                 <a href="?ruta=agregar-documento&accion=toggle_estado&id=<?= $doc['id'] ?>" class="btn-action-edit" style="background: <?= ($doc['activo'] ?? true) ? '#fef3c7; color: #92400e; border: 1px solid #fde68a;' : '#dcfce7; color: #15803d; border: 1px solid #86efac;' ?>" title="<?= ($doc['activo'] ?? true) ? 'Ocultar del catálogo público' : 'Hacer visible en el catálogo público' ?>">
                                                     <i class="ph ph-eye-slash"></i> <?= ($doc['activo'] ?? true) ? 'Ocultar' : 'Activar' ?>
                                                 </a>

                                                 <a href="?ruta=agregar-documento&accion=editar&id=<?= $doc['id'] ?>" class="btn-action-edit" title="Modificar Metadatos">
                                                     <i class="ph ph-pencil-simple"></i> Editar
                                                 </a>

                                                 <?php if ($nivelUsuario >= 2): ?>
                                                     <a href="javascript:void(0)" class="btn-action-delete" title="Eliminar Registro Definitivo" onclick="confirmarEliminacionModal('?ruta=agregar-documento&accion=eliminar&id=<?= $doc['id'] ?>')">
                                                         <i class="ph ph-trash"></i> Eliminar
                                                     </a>
                                                 <?php endif; ?>
                                             </div>
                                         </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginador de Gestión Documental -->
                <?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
                    <div class="pst-pagination">
                        <?php 
                        $query_params = $_GET;
                        unset($query_params['page']); 
                        
                        $build_url = function($p) use ($query_params) {
                            $query_params['page'] = $p;
                            return '?' . http_build_query($query_params);
                        };
                        
                        $curr = $pagination['current_page'];
                        $tot = $pagination['total_pages'];
                        ?>
                        
                        <?php if ($curr > 1): ?>
                            <a href="<?= $build_url($curr - 1) ?>" class="page-link">&laquo; Anterior</a>
                        <?php else: ?>
                            <span class="page-link disabled">&laquo; Anterior</span>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $tot; $i++): ?>
                            <?php if ($i == $curr): ?>
                                <span class="page-link active"><?= $i ?></span>
                            <?php else: ?>
                                <a href="<?= $build_url($i) ?>" class="page-link"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <?php if ($curr < $tot): ?>
                            <a href="<?= $build_url($curr + 1) ?>" class="page-link">Siguiente &raquo;</a>
                        <?php else: ?>
                            <span class="page-link disabled">Siguiente &raquo;</span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </section>

        <?php endif; ?>

    </div>
</div>

</div>

<!-- Modal de Carga / Extracción de Datos -->
<div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 34, 68, 0.75); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center; color: white;">
    <div style="background: var(--bg-card, #ffffff); color: var(--texto-normal, #333); padding: 2.2rem; border-radius: var(--radius-lg, 12px); max-width: 480px; width: 90%; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.3); border: 1px solid rgba(169, 168, 166, 0.2);">
        <div style="font-size: 3rem; color: var(--color-terciario, #007bff); margin-bottom: 1rem; animation: pulse-loader 1.5s infinite ease-in-out;">
            <i class="ph-bold ph-cpu"></i>
        </div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--texto-titulos, #002244); margin-bottom: 0.5rem;" id="loaderTitle">Procesando Archivo</h3>
        <p style="font-size: 0.85rem; color: var(--texto-silenciado, #666); margin-bottom: 1.5rem;" id="loaderText">Extrayendo texto y analizando metadatos del proyecto...</p>
        
        <!-- Barra de Progreso -->
        <div style="width: 100%; height: 8px; background-color: #e2e8f0; border-radius: 4px; overflow: hidden; margin-bottom: 0.5rem;">
            <div id="progressBar" style="width: 0%; height: 100%; background-color: var(--color-terciario, #007bff); border-radius: 4px; transition: width 0.2s ease-out;"></div>
        </div>
        <div id="progressPercent" style="font-size: 0.85rem; font-weight: 700; color: var(--color-terciario, #007bff); text-align: right;">0%</div>
    </div>
</div>

<script>
// JSON con todas las dimensiones operativas del sistema para el filtrado dinámico
const todasDimensiones = <?= json_encode($lineas && $dimensiones ? $dimensiones : []) ?>;
const activeDimensionId = <?= json_encode($_POST['dimension_id'] ?? $documento['dimension_id'] ?? '') ?>;

// Estado global de la cola de documentos por lotes
let documentosEnCola = [];
let documentoSeleccionadoIndex = -1;

// Función para actualizar dinámicamente el selector de dimensiones operativas
function updateDimensionOptions(selectedLineaId) {
    const dimSelect = document.getElementById('dimension_id');
    if (!dimSelect) return;
    
    dimSelect.innerHTML = '<option value="">Seleccione una Dimensión...</option>';
    
    if (!selectedLineaId) {
        dimSelect.disabled = true;
        return;
    }
    
    dimSelect.disabled = false;
    const filtered = todasDimensiones.filter(d => d.id_linea == selectedLineaId);
    
    filtered.forEach(d => {
        const opt = document.createElement('option');
        opt.value = d.id;
        opt.textContent = d.nombre;
        if (d.id == activeDimensionId) {
            opt.selected = true;
        }
        dimSelect.appendChild(opt);
    });
}

function toggleTrayectoByNivel() {
    const nivelSelect = document.getElementById('nivel_academico');
    const trayectoContainer = document.getElementById('container_trayecto');
    const trayectoSelect = document.getElementById('trayecto');
    if (!nivelSelect || !trayectoContainer) return;

    if (nivelSelect.value === 'Pregrado') {
        trayectoContainer.style.display = 'block';
        if (trayectoSelect && !trayectoSelect.value) trayectoSelect.value = 'Trayecto I';
    } else {
        trayectoContainer.style.display = 'none';
        if (trayectoSelect) trayectoSelect.value = '';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    toggleTrayectoByNivel();
    const lineaSelect = document.getElementById('linea_id');
    if (lineaSelect) {
        if (lineaSelect.value) {
            updateDimensionOptions(lineaSelect.value);
        }
        lineaSelect.addEventListener('change', (e) => {
            updateDimensionOptions(e.target.value);
        });
    }

    // --- CONFIGURACIÓN DE LA EXTRACCIÓN DE METADATOS POR LOTES ---
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('input_archivo_extractor');
    const btnBrowse = document.getElementById('btnBrowseFile');

    if (dropzone && fileInput) {
        dropzone.addEventListener('click', (e) => {
            if (e.target !== fileInput && (!btnBrowse || e.target !== btnBrowse)) {
                fileInput.click();
            }
        });

        if (btnBrowse) {
            btnBrowse.addEventListener('click', (e) => {
                e.stopPropagation();
                fileInput.click();
            });
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('active');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('active');
            }, false);
        });

        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                procesarArchivosSeleccionados(files);
            }
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files && e.target.files.length > 0) {
                procesarArchivosSeleccionados(e.target.files);
            }
        });
    }
});

function procesarArchivosSeleccionados(fileList) {
    const esEditar = <?= json_encode($accion === 'editar') ?>;
    let files = Array.from(fileList);
    
    if (esEditar && files.length > 1) {
        files = [files[0]];
    }

    const validFiles = files.filter(f => {
        const ext = f.name.split('.').pop().toLowerCase();
        return ext === 'pdf' || ext === 'docx';
    });

    if (validFiles.length === 0) {
        mostrarModalAlerta('warning', 'Formato no admitido', 'Formato de archivo inválido. Solo se admiten documentos PDF (.pdf) y Microsoft Word (.docx).');
        return;
    }

    if (esEditar) {
        // En modo edición, transferir el objeto de archivo real al elemento input file para que sea enviado por el formulario
        const file = validFiles[0];
        const fileInput = document.getElementById('input_archivo_extractor');
        if (fileInput && window.DataTransfer) {
            const dt = new DataTransfer();
            dt.items.add(file);
            fileInput.files = dt.files;
        }

        const fileInputHidden = document.getElementById('archivo_pdf_hidden');
        if (fileInputHidden) {
            fileInputHidden.value = file.name;
        }

        const badge = document.getElementById('badgeArchivoSustituido');
        const badgeText = document.getElementById('nombreArchivoSustituidoText');
        if (badge && badgeText) {
            badgeText.textContent = file.name;
            badge.style.display = 'block';
        }

        mostrarModalAlerta('success', 'Archivo Listo para Sustituir', `El archivo "${file.name}" reemplazará al documento actual al presionar el botón "Guardar Cambios".`);
        return;
    }

    const nuevosIndices = [];
    validFiles.forEach(file => {
        const docObj = {
            id: Date.now() + '_' + Math.random().toString(36).substr(2, 5),
            nombreArchivo: file.name,
            file: file,
            estado: 'pendiente',
            errorMsg: '',
            data: {
                titulo: file.name.replace(/\.[^/.]+$/, ""),
                anio_publicacion: new Date().getFullYear(),
                fecha_defensa: new Date().toISOString().split('T')[0],
                nivel_academico: 'Pregrado',
                trayecto: 'Trayecto I',
                url_repositorio: '',
                resumen: '',
                obj_general: '',
                palabras_clave: '',
                comunidad_beneficiada: '',
                linea_id: '',
                dimension_id: '',
                autores: [{ cedula: '', nombre: '' }, { cedula: '', nombre: '' }, { cedula: '', nombre: '' }, { cedula: '', nombre: '' }],
                tutor_academico_cedula: '',
                tutor_academico_nombre: '',
                tutor_institucional_cedula: '',
                tutor_institucional_nombre: '',
                tutor_comunitario_cedula: '',
                tutor_comunitario_nombre: ''
            }
        };
        documentosEnCola.push(docObj);
        nuevosIndices.push(documentosEnCola.length - 1);
    });

    // Resetear valor de input file para permitir re-seleccionar los mismos archivos
    const fileInput = document.getElementById('input_archivo_extractor');
    if (fileInput) fileInput.value = '';

    renderizarColaUI();

    // Seleccionar automáticamente el primer documento cargado en el formulario si es el primer lote
    if (documentoSeleccionadoIndex === -1 && documentosEnCola.length > 0) {
        seleccionarDocumentoDeCola(0);
    }

    // Iniciar extracción para cada nuevo documento agregado
    nuevosIndices.forEach(idx => {
        subirYExtraerDatos(documentosEnCola[idx], idx);
    });
}

function subirYExtraerDatos(docItem, index) {
    docItem.estado = 'extrayendo';
    docItem.progresoPct = 0;
    docItem.faseMsg = 'Subiendo al servidor...';
    renderizarColaUI();

    const formData = new FormData();
    formData.append('archivo_pst', docItem.file);
    formData.append('csrf_token', window.CSRF_TOKEN || '');

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '?ruta=agregar-documento&accion=extraer', true);
    xhr.setRequestHeader('X-CSRF-Token', window.CSRF_TOKEN || '');

    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            const pct = Math.round((e.loaded / e.total) * 100);
            docItem.progresoPct = pct;
            if (pct < 100) {
                docItem.faseMsg = 'Subiendo (' + pct + '%)...';
            } else {
                docItem.faseMsg = 'Analizando texto...';
            }
            renderizarColaUI();
        }
    };

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    docItem.estado = 'listo';
                    docItem.errorMsg = '';
                    docItem.progresoPct = 100;
                    docItem.faseMsg = 'Listo';
                    if (response.data) {
                        Object.assign(docItem.data, response.data);
                    }
                    if (documentoSeleccionadoIndex === index) {
                        rellenarFormulario(docItem.data);
                    }
                } else {
                    docItem.estado = 'error';
                    docItem.errorMsg = response.message || 'Error en la extracción de metadatos.';
                }
            } catch (e) {
                docItem.estado = 'error';
                docItem.errorMsg = 'Respuesta inválida del servidor.';
            }
        } else {
            docItem.estado = 'error';
            docItem.errorMsg = 'Error en el servidor (' + xhr.status + ').';
        }
        renderizarColaUI();
    };

    xhr.onerror = function() {
        docItem.estado = 'error';
        docItem.errorMsg = 'Error de conexión de red.';
        renderizarColaUI();
    };

    xhr.send(formData);
}

function renderizarColaUI() {
    const contenedor = document.getElementById('contenedorColaDocumentos');
    const lista = document.getElementById('listaColaItems');
    const countSpan = document.getElementById('countCola');
    const btnGuardarBorrador = document.getElementById('btnGuardarBorrador');

    if (!contenedor || !lista) return;

    if (documentosEnCola.length === 0) {
        contenedor.style.display = 'none';
        documentoSeleccionadoIndex = -1;
        if (btnGuardarBorrador) btnGuardarBorrador.style.display = 'none';
        return;
    }

    contenedor.style.display = 'block';
    if (countSpan) countSpan.textContent = documentosEnCola.length;
    if (btnGuardarBorrador) btnGuardarBorrador.style.display = 'inline-flex';

    lista.innerHTML = '';

    documentosEnCola.forEach((item, idx) => {
        const itemCard = document.createElement('div');
        const isSelected = (idx === documentoSeleccionadoIndex);
        
        let borderStyle = isSelected 
            ? 'border: 2px solid var(--color-terciario, #007bff); background-color: #f4f8ff;'
            : 'border: 1px solid rgba(169, 168, 166, 0.25); background-color: var(--blanco, #ffffff);';

        itemCard.setAttribute('style', `${borderStyle} border-radius: 4px; padding: 0.5rem 0.6rem; cursor: pointer; transition: all 0.15s ease;`);

        let statusBadge = '';
        if (item.estado === 'pendiente') {
            statusBadge = `<span style="font-size: 0.7rem; padding: 0.15rem 0.4rem; border-radius: 3px; background-color: #f1f5f9; color: var(--texto-silenciado); font-weight: 700;"><i class="ph ph-clock"></i> Pendiente</span>`;
        } else if (item.estado === 'extrayendo') {
            const faseText = item.faseMsg || 'Extrayendo...';
            const pct = item.progresoPct || 0;
            statusBadge = `
                <div style="text-align: right;">
                    <span style="font-size: 0.68rem; padding: 0.15rem 0.4rem; border-radius: 3px; background-color: #eff6ff; color: var(--color-terciario); font-weight: 700; display: inline-block;"><i class="ph ph-arrows-clockwise spin"></i> ${escapeHtml(faseText)}</span>
                    <div style="width: 100%; min-width: 90px; background-color: #e2e8f0; border-radius: 4px; height: 4px; margin-top: 3px; overflow: hidden;">
                        <div style="width: ${pct}%; background-color: var(--color-terciario); height: 100%; transition: width 0.2s ease;"></div>
                    </div>
                </div>
            `;
        } else if (item.estado === 'listo') {
            statusBadge = `<span style="font-size: 0.7rem; padding: 0.15rem 0.4rem; border-radius: 3px; background-color: #def7ec; color: #03543f; font-weight: 700;"><i class="ph ph-check-circle"></i> Listo</span>`;
        } else if (item.estado === 'subiendo') {
            statusBadge = `<span style="font-size: 0.7rem; padding: 0.15rem 0.4rem; border-radius: 3px; background-color: #fef3c7; color: #92400e; font-weight: 700;"><i class="ph ph-cloud-arrow-up spin"></i> Subiendo...</span>`;
        } else if (item.estado === 'exito') {
            statusBadge = `<span style="font-size: 0.7rem; padding: 0.15rem 0.4rem; border-radius: 3px; background-color: #def7ec; color: #03543f; font-weight: 700;"><i class="ph ph-check-circle"></i> Subido</span>`;
        } else if (item.estado === 'error') {
            statusBadge = `<span style="font-size: 0.7rem; padding: 0.15rem 0.4rem; border-radius: 3px; background-color: #fde8e8; color: #c81e1e; font-weight: 700;"><i class="ph ph-warning-circle"></i> Error</span>`;
        }

        const titleText = item.data.titulo ? item.data.titulo : item.nombreArchivo;

        let content = `
            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.4rem;">
                <div style="overflow: hidden; text-overflow: ellipsis; flex-grow: 1;">
                    <div style="font-size: 0.75rem; font-weight: 700; color: var(--texto-titulos); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <i class="ph ph-file-pdf" style="color: var(--color-terciario);"></i> ${escapeHtml(item.nombreArchivo)}
                    </div>
                    <div style="font-size: 0.7rem; color: var(--texto-silenciado); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 0.1rem;">
                        ${escapeHtml(titleText)}
                    </div>
                </div>
                <div>${statusBadge}</div>
            </div>
        `;

        if (item.estado === 'error' && item.errorMsg) {
            content += `
                <div class="alert-message alert-error" style="margin-top: 0.4rem; margin-bottom: 0; padding: 0.35rem 0.5rem; font-size: 0.7rem; word-break: break-word;">
                    <i class="ph ph-warning-circle"></i> ${escapeHtml(item.errorMsg)}
                </div>
            `;
        }

        content += `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.4rem; padding-top: 0.3rem; border-top: 1px dashed rgba(169, 168, 166, 0.15);">
                <button type="button" class="btn-action-edit" style="font-size: 0.7rem; padding: 0.15rem 0.4rem;" onclick="event.stopPropagation(); seleccionarDocumentoDeCola(${idx});">
                    <i class="ph ph-pencil-simple"></i> Revisar / Editar
                </button>
                <button type="button" class="btn-action-delete" style="font-size: 0.7rem; padding: 0.15rem 0.4rem;" onclick="event.stopPropagation(); eliminarDocumentoDeCola(${idx});">
                    <i class="ph ph-trash"></i> Quitar
                </button>
            </div>
        `;

        itemCard.innerHTML = content;
        itemCard.addEventListener('click', () => {
            seleccionarDocumentoDeCola(idx);
        });

        lista.appendChild(itemCard);
    });
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function seleccionarDocumentoDeCola(index) {
    if (index < 0 || index >= documentosEnCola.length) return;

    if (documentoSeleccionadoIndex >= 0 && documentoSeleccionadoIndex < documentosEnCola.length && documentoSeleccionadoIndex !== index) {
        guardarDatosFormularioEnArray(documentoSeleccionadoIndex);
    }

    documentoSeleccionadoIndex = index;
    const docItem = documentosEnCola[index];
    rellenarFormulario(docItem.data);
    renderizarColaUI();
}

function guardarDatosFormularioEnArray(index) {
    if (index < 0 || index >= documentosEnCola.length) return;
    const currentData = obtenerDatosFormularioActual();
    Object.assign(documentosEnCola[index].data, currentData);
}

function guardarBorradorEnCola() {
    if (documentoSeleccionadoIndex < 0 || documentoSeleccionadoIndex >= documentosEnCola.length) {
        mostrarModalAlerta('warning', 'Selección Requerida', 'Seleccione un documento de la cola para guardar sus cambios en borrador.');
        return;
    }
    guardarDatosFormularioEnArray(documentoSeleccionadoIndex);
    renderizarColaUI();

    const nombre = documentosEnCola[documentoSeleccionadoIndex].nombreArchivo;
    mostrarModalAlerta('success', 'Borrador Guardado', `Borrador actualizado en cola para el archivo "${nombre}".`);
}

function obtenerDatosFormularioActual() {
    const autores = [];
    const cedulaInputs = document.getElementsByName('autor_cedula[]');
    const nombreInputs = document.getElementsByName('autor_nombre[]');
    for (let i = 0; i < cedulaInputs.length; i++) {
        const ced = cedulaInputs[i] ? cedulaInputs[i].value.trim() : '';
        const nom = nombreInputs[i] ? nombreInputs[i].value.trim() : '';
        autores.push({ cedula: ced, nombre: nom });
    }

    return {
        archivo_pdf: document.getElementById('archivo_pdf_hidden') ? document.getElementById('archivo_pdf_hidden').value.trim() : '',
        titulo: document.getElementById('titulo') ? document.getElementById('titulo').value.trim() : '',
        anio_publicacion: document.getElementById('anio_publicacion') ? document.getElementById('anio_publicacion').value : new Date().getFullYear(),
        nivel_academico: document.getElementById('nivel_academico') ? document.getElementById('nivel_academico').value : 'Pregrado',
        trayecto: document.getElementById('trayecto') ? document.getElementById('trayecto').value : 'Trayecto I',
        url_repositorio: document.getElementById('url_repositorio') ? document.getElementById('url_repositorio').value.trim() : '',
        resumen: document.getElementById('resumen') ? document.getElementById('resumen').value.trim() : '',
        obj_general: document.getElementById('obj_general') ? document.getElementById('obj_general').value.trim() : '',
        palabras_clave: document.getElementById('palabras_clave') ? document.getElementById('palabras_clave').value.trim() : '',
        comunidad_beneficiada: document.getElementById('comunidad_beneficiada') ? document.getElementById('comunidad_beneficiada').value.trim() : '',
        linea_id: document.getElementById('linea_id') ? document.getElementById('linea_id').value : '',
        dimension_id: document.getElementById('dimension_id') ? document.getElementById('dimension_id').value : '',
        autores: autores,
        tutor_academico_cedula: document.getElementsByName('tutor_academico_cedula')[0] ? document.getElementsByName('tutor_academico_cedula')[0].value.trim() : '',
        tutor_academico_nombre: document.getElementsByName('tutor_academico_nombre')[0] ? document.getElementsByName('tutor_academico_nombre')[0].value.trim() : '',
        tutor_institucional_cedula: document.getElementsByName('tutor_institucional_cedula')[0] ? document.getElementsByName('tutor_institucional_cedula')[0].value.trim() : '',
        tutor_institucional_nombre: document.getElementsByName('tutor_institucional_nombre')[0] ? document.getElementsByName('tutor_institucional_nombre')[0].value.trim() : '',
        tutor_comunitario_cedula: document.getElementsByName('tutor_comunitario_cedula')[0] ? document.getElementsByName('tutor_comunitario_cedula')[0].value.trim() : '',
        tutor_comunitario_nombre: document.getElementsByName('tutor_comunitario_nombre')[0] ? document.getElementsByName('tutor_comunitario_nombre')[0].value.trim() : ''
    };
}

function eliminarDocumentoDeCola(index) {
    if (index < 0 || index >= documentosEnCola.length) return;
    
    documentosEnCola.splice(index, 1);
    
    if (documentoSeleccionadoIndex === index) {
        if (documentosEnCola.length > 0) {
            seleccionarDocumentoDeCola(Math.max(0, index - 1));
        } else {
            documentoSeleccionadoIndex = -1;
            limpiarCamposFormularioSilencioso();
        }
    } else if (documentoSeleccionadoIndex > index) {
        documentoSeleccionadoIndex--;
    }
    
    renderizarColaUI();
}

function limpiarColaDocumentos() {
    if (documentosEnCola.length === 0) return;
    confirmarAccionModal(
        'warning',
        'Vaciar Cola',
        '¿Desea vaciar completamente la cola de documentos extraídos?',
        'Sí, vaciar cola',
        () => {
            documentosEnCola = [];
            documentoSeleccionadoIndex = -1;
            limpiarCamposFormularioSilencioso();
            renderizarColaUI();
        }
    );
}

function limpiarCamposFormularioSilencioso() {
    const form = document.getElementById('formSubidaPst');
    if (!form) return;
    const inputs = form.querySelectorAll('input[type="text"], input[type="number"], input[type="date"], textarea');
    inputs.forEach(input => { input.value = ''; });
    const lineaSelect = document.getElementById('linea_id');
    if (lineaSelect) {
        lineaSelect.value = '';
        if (typeof updateDimensionOptions === 'function') updateDimensionOptions('');
    }
}

async function subirLoteABaseDeDatos() {
    if (documentosEnCola.length === 0) {
        mostrarModalAlerta('warning', 'Cola Vacía', 'La cola de documentos está vacía.');
        return;
    }

    if (documentoSeleccionadoIndex >= 0 && documentoSeleccionadoIndex < documentosEnCola.length) {
        guardarDatosFormularioEnArray(documentoSeleccionadoIndex);
    }

    const pendientes = documentosEnCola.filter(d => d.estado !== 'exito');
    if (pendientes.length === 0) {
        mostrarModalAlerta('success', 'Cola Completada', 'Todos los documentos de la cola ya se han subido con éxito a la base de datos.');
        return;
    }

    const btnSubir = document.getElementById('btnSubirLote');
    if (btnSubir) {
        btnSubir.disabled = true;
        btnSubir.innerHTML = '<i class="ph ph-spinner spin"></i> Procesando Subida en Lote...';
    }

    let exitosos = 0;
    let fallidos = 0;

    for (let i = 0; i < documentosEnCola.length; i++) {
        const item = documentosEnCola[i];
        if (item.estado === 'exito') continue;

        item.estado = 'subiendo';
        renderizarColaUI();

        try {
            const res = await fetch('?ruta=agregar-documento&accion=crear_ajax', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json; charset=utf-8',
                    'X-CSRF-Token': window.CSRF_TOKEN || ''
                },
                body: JSON.stringify(Object.assign({}, item.data, { csrf_token: window.CSRF_TOKEN || '' }))
            });

            const json = await res.json();
            if (json.status === 'success') {
                item.estado = 'exito';
                item.errorMsg = '';
                exitosos++;
            } else {
                item.estado = 'error';
                item.errorMsg = json.message || 'Error al procesar la inserción.';
                fallidos++;
            }
        } catch (err) {
            item.estado = 'error';
            item.errorMsg = 'Error de comunicación con el servidor.';
            fallidos++;
        }

        renderizarColaUI();
    }

    if (btnSubir) {
        btnSubir.disabled = false;
        btnSubir.innerHTML = '<i class="ph ph-cloud-arrow-up" style="font-size: 1.1rem;"></i> Subir Lote a Base de Datos';
    }

    let msj = `Proceso de subida en lote finalizado.\n\n• Documentos registrados con éxito: ${exitosos}.`;
    if (fallidos > 0) {
        msj += `\n• Documentos con error: ${fallidos}.\nRevise los ítems marcados en la cola para corregirlos.`;
        mostrarModalAlerta('warning', 'Resumen de Carga', msj);
    } else {
        mostrarModalAlerta('success', 'Carga Completada', msj);
    }
}

function rellenarFormulario(data) {
    if (!data) return;

    if (data.archivo_pdf !== undefined && document.getElementById('archivo_pdf_hidden')) document.getElementById('archivo_pdf_hidden').value = data.archivo_pdf || '';
    if (data.titulo !== undefined) document.getElementById('titulo').value = data.titulo || '';
    if (data.anio_publicacion !== undefined) document.getElementById('anio_publicacion').value = data.anio_publicacion || new Date().getFullYear();
    if (data.nivel_academico !== undefined && document.getElementById('nivel_academico')) {
        document.getElementById('nivel_academico').value = data.nivel_academico || 'Pregrado';
        toggleTrayectoByNivel();
    }
    if (data.trayecto !== undefined && document.getElementById('trayecto')) document.getElementById('trayecto').value = data.trayecto || 'Trayecto I';
    if (data.url_repositorio !== undefined && document.getElementById('url_repositorio')) document.getElementById('url_repositorio').value = data.url_repositorio || '';
    if (data.resumen !== undefined) document.getElementById('resumen').value = data.resumen || '';
    if (data.obj_general !== undefined && document.getElementById('obj_general')) document.getElementById('obj_general').value = data.obj_general || '';
    if (data.palabras_clave !== undefined) document.getElementById('palabras_clave').value = data.palabras_clave || '';
    if (data.comunidad_beneficiada !== undefined) document.getElementById('comunidad_beneficiada').value = data.comunidad_beneficiada || '';

    const cedulaInputs = document.getElementsByName('autor_cedula[]');
    const nombreInputs = document.getElementsByName('autor_nombre[]');
    for (let k = 0; k < cedulaInputs.length; k++) {
        cedulaInputs[k].value = '';
        nombreInputs[k].value = '';
    }
    if (data.autores && Array.isArray(data.autores)) {
        data.autores.forEach((autor, idx) => {
            if (idx < cedulaInputs.length) {
                cedulaInputs[idx].value = autor.cedula || '';
                nombreInputs[idx].value = autor.nombre || autor.nombre_completo || '';
            }
        });
    }

    const getElem = (name) => document.getElementsByName(name)[0];
    if (getElem('tutor_academico_cedula')) getElem('tutor_academico_cedula').value = data.tutor_academico_cedula || '';
    if (getElem('tutor_academico_nombre')) getElem('tutor_academico_nombre').value = data.tutor_academico_nombre || '';
    if (getElem('tutor_institucional_cedula')) getElem('tutor_institucional_cedula').value = data.tutor_institucional_cedula || '';
    if (getElem('tutor_institucional_nombre')) getElem('tutor_institucional_nombre').value = data.tutor_institucional_nombre || '';
    if (getElem('tutor_comunitario_cedula')) getElem('tutor_comunitario_cedula').value = data.tutor_comunitario_cedula || '';
    if (getElem('tutor_comunitario_nombre')) getElem('tutor_comunitario_nombre').value = data.tutor_comunitario_nombre || '';

    const lineaSelect = document.getElementById('linea_id');
    if (lineaSelect) {
        lineaSelect.value = data.linea_id || '';
        updateDimensionOptions(lineaSelect.value);
        if (data.dimension_id) {
            const dimSelect = document.getElementById('dimension_id');
            if (dimSelect) {
                dimSelect.value = data.dimension_id;
            }
        }
    }
}

function limpiarFormularioPst() {
    confirmarAccionModal(
        'warning',
        'Limpiar Formulario',
        '¿Está seguro de que desea limpiar todos los campos del formulario?',
        'Sí, limpiar',
        () => {
            limpiarCamposFormularioSilencioso();
            const fileInput = document.getElementById('input_archivo_extractor');
            if (fileInput) fileInput.value = '';
        }
    );
}

// SISTEMA REUTILIZABLE DE MODALES (ÉXITO, ADVERTENCIA, ERROR Y CONFIRMACIÓN)
function mostrarModalAlerta(tipo, titulo, mensaje) {
    const modal = document.getElementById('pstSystemModal');
    const iconWrapper = document.getElementById('modalPstIconWrapper');
    const titleElem = document.getElementById('modalPstTitle');
    const msgElem = document.getElementById('modalPstMessage');
    const btnConfirm = document.getElementById('btnModalPstConfirm');
    const btnCancel = document.getElementById('btnModalPstCancel');

    btnCancel.style.display = 'none';
    btnConfirm.textContent = 'Aceptar';
    btnConfirm.onclick = cerrarModalAlertaPst;

    if (tipo === 'success') {
        iconWrapper.className = 'modal-pst-icon-box success';
        iconWrapper.innerHTML = '<i class="ph ph-check-circle"></i>';
    } else if (tipo === 'warning') {
        iconWrapper.className = 'modal-pst-icon-box warning';
        iconWrapper.innerHTML = '<i class="ph ph-warning"></i>';
    } else { // error
        iconWrapper.className = 'modal-pst-icon-box error';
        iconWrapper.innerHTML = '<i class="ph ph-x-circle"></i>';
    }

    titleElem.textContent = titulo;
    msgElem.textContent = mensaje;
    modal.style.display = 'flex';
}

function confirmarAccionModal(tipo, titulo, mensaje, btnTexto, onConfirm) {
    const modal = document.getElementById('pstSystemModal');
    const iconWrapper = document.getElementById('modalPstIconWrapper');
    const titleElem = document.getElementById('modalPstTitle');
    const msgElem = document.getElementById('modalPstMessage');
    const btnConfirm = document.getElementById('btnModalPstConfirm');
    const btnCancel = document.getElementById('btnModalPstCancel');

    btnCancel.style.display = 'inline-block';
    btnConfirm.textContent = btnTexto || 'Confirmar';
    
    if (tipo === 'warning') {
        iconWrapper.className = 'modal-pst-icon-box warning';
        iconWrapper.innerHTML = '<i class="ph ph-warning-triangle"></i>';
    } else if (tipo === 'error') {
        iconWrapper.className = 'modal-pst-icon-box error';
        iconWrapper.innerHTML = '<i class="ph ph-trash-simple"></i>';
    } else {
        iconWrapper.className = 'modal-pst-icon-box success';
        iconWrapper.innerHTML = '<i class="ph ph-question"></i>';
    }

    titleElem.textContent = titulo;
    msgElem.textContent = mensaje;

    btnConfirm.onclick = () => {
        cerrarModalAlertaPst();
        if (typeof onConfirm === 'function') onConfirm();
    };

    modal.style.display = 'flex';
}

function cerrarModalAlertaPst() {
    const modal = document.getElementById('pstSystemModal');
    if (modal) modal.style.display = 'none';
}

function confirmarEliminacionModal(urlTarget) {
    confirmarAccionModal(
        'error',
        'Eliminar Investigación',
        '¿Desea eliminar permanentemente este proyecto Socio-Tecnológico del repositorio? Esta acción no se puede deshacer.',
        'Sí, eliminar',
        () => {
            window.location.href = urlTarget;
        }
    );
}

// PREVISUALIZACIÓN DEL DOCUMENTO CARGADO
function abrirModalPrevisualizacionDocumento() {
    const modal = document.getElementById('modalPreviewDocumentModal');
    const iframe = document.getElementById('iframePreviewDocument');
    const fileInfo = document.getElementById('previewFileInfo');
    const pdfHidden = document.getElementById('archivo_pdf_hidden');
    const fileInput = document.getElementById('input_archivo_extractor');
    
    let fileUrl = '';
    let fileName = '';
    let isDocx = false;

    // Caso 1: Documento activo en la cola de procesamiento por lote
    if (documentoSeleccionadoIndex >= 0 && documentosEnCola[documentoSeleccionadoIndex]) {
        const item = documentosEnCola[documentoSeleccionadoIndex];
        if (item.file) {
            fileUrl = URL.createObjectURL(item.file);
            fileName = item.nombreArchivo;
            if (item.nombreArchivo.toLowerCase().endsWith('.docx')) isDocx = true;
        } else if (item.data && item.data.archivo_pdf) {
            fileUrl = `?ruta=ver-pdf-pst&file=${encodeURIComponent(item.data.archivo_pdf)}`;
            fileName = item.nombreArchivo || item.data.archivo_pdf.split('/').pop();
            if (fileName.toLowerCase().endsWith('.docx') || item.data.archivo_pdf.toLowerCase().endsWith('.docx')) isDocx = true;
        }
    } 
    // Caso 2: Archivo recién seleccionado localmente por el usuario
    else if (fileInput && fileInput.files && fileInput.files.length > 0) {
        const file = fileInput.files[0];
        fileUrl = URL.createObjectURL(file);
        fileName = file.name;
        if (file.name.toLowerCase().endsWith('.docx')) isDocx = true;
    } 
    // Caso 3: Documento en edición o registrado en la base de datos
    else if (pdfHidden && pdfHidden.value) {
        <?php if (!empty($documento['id'])): ?>
            fileUrl = '?ruta=ver-pdf-pst&id=<?= $documento['id'] ?>#toolbar=0&navpanes=0';
            fileName = '<?= htmlspecialchars($documento['titulo'] ?? 'Documento Indexado') ?>';
            if (pdfHidden.value.toLowerCase().endsWith('.docx')) isDocx = true;
        <?php else: ?>
            fileUrl = `?ruta=ver-pdf-pst&file=${encodeURIComponent(pdfHidden.value)}`;
            fileName = pdfHidden.value.split('/').pop();
            if (pdfHidden.value.toLowerCase().endsWith('.docx')) isDocx = true;
        <?php endif; ?>
    }

    if (!fileUrl) {
        mostrarModalAlerta('warning', 'Sin Documento', 'No hay un archivo PDF o Word cargado ni seleccionado en el formulario para previsualizar.');
        return;
    }

    fileInfo.textContent = fileName ? `Archivo: ${fileName}` : 'Vista Previa';

    // Si es un archivo Word (.docx) local o en cola, usamos Mammoth.js client-side para renderizar el documento binario original
    if (isDocx) {
        let docxFile = null;
        if (documentoSeleccionadoIndex >= 0 && documentosEnCola[documentoSeleccionadoIndex] && documentosEnCola[documentoSeleccionadoIndex].file) {
            docxFile = documentosEnCola[documentoSeleccionadoIndex].file;
        } else if (fileInput && fileInput.files && fileInput.files.length > 0) {
            docxFile = fileInput.files[0];
        }

        iframe.removeAttribute('src');
        iframe.srcdoc = `<div style="font-family:sans-serif; text-align:center; padding:3rem; color:#002244;">Cargando visor del documento Word...</div>`;

        const libMammoth = window.mammoth || (typeof mammoth !== 'undefined' ? mammoth : null);

        if (docxFile) {
            if (!libMammoth) {
                iframe.srcdoc = `<p style="padding:2rem; color:#be123c;">La librería Mammoth.js no se ha cargado en la página.</p>`;
                modal.style.display = 'flex';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(loadEvent) {
                renderizarDocxConMammoth(loadEvent.target.result);
            };
            reader.onerror = function(err) {
                iframe.srcdoc = `<p style="padding:2rem; color:#be123c;">Error FileReader al leer el archivo Word: ${err}</p>`;
            };
            reader.readAsArrayBuffer(docxFile);
        } else if (fileUrl && typeof mammoth !== 'undefined') {
            fetchYRenderizarMammoth(fileUrl, iframe, fileName);
        } else {
            iframe.srcdoc = `<p style="padding:2rem; color:#be123c;">No se encontró el objeto de archivo local ni la URL del servidor.</p>`;
        }
    } else {
        iframe.removeAttribute('srcdoc');
        iframe.src = fileUrl || 'about:blank';
    }

    modal.style.display = 'flex';
}

function fetchYRenderizarMammoth(url, iframe, fileName) {
    fetch(url)
        .then(res => {
            if (!res.ok) throw new Error("HTTP Error status " + res.status);
            return res.arrayBuffer();
        })
        .then(arrayBuffer => {
            renderizarDocxConMammoth(arrayBuffer);
        })
        .catch(err => {
            console.error('Error al descargar archivo DOCX:', err);
            iframe.srcdoc = `<div style="padding:2rem; font-family:sans-serif; color:#be123c;">
                <h4 style="margin-top:0;">No se pudo descargar el archivo Word para previsualización</h4>
                <p style="font-size:0.85rem; color:#334155;">Detalle técnico: ${err.message || err}</p>
                <p style="font-size:0.8rem; color:#64748b;">Ruta solicitada: <code>${url}</code></p>
            </div>`;
        });
}

function renderizarDocxConMammoth(arrayBuffer) {
    const iframe = document.getElementById('iframePreviewDocument');
    const mInstance = window.mammoth || (typeof mammoth !== 'undefined' ? mammoth : null);

    if (!mInstance) {
        iframe.srcdoc = `<p style="padding:2rem; color:#be123c;">Error: No se pudo obtener la instancia de Mammoth.js en el entorno.</p>`;
        return;
    }

    const options = {
        styleMap: [
            "p[style-name='Title'] => h1.doc-title:fresh",
            "p[style-name='Subtitle'] => h2.doc-subtitle:fresh",
            "p[style-name='Heading 1'] => h2.doc-heading-1:fresh",
            "p[style-name='Heading 2'] => h3.doc-heading-2:fresh",
            "p[style-name='Heading 3'] => h4.doc-heading-3:fresh",
            "p[style-name='Heading 4'] => h5.doc-heading-4:fresh",
            "p[style-name='Quote'] => blockquote.doc-quote:fresh",
            "p[style-name='Intense Quote'] => blockquote.doc-quote-intense:fresh",
            "r[style-name='Strong'] => strong",
            "r[style-name='Emphasis'] => em",
            "p:unordered-list => ul > li:fresh",
            "p:ordered-list => ol > li:fresh"
        ],
        convertImage: mInstance.images.imgElement(function(image) {
            return image.read("base64").then(function(imageBuffer) {
                return {
                    src: "data:" + image.contentType + ";base64," + imageBuffer
                };
            });
        })
    };

    mInstance.convertToHtml({ arrayBuffer: arrayBuffer }, options)
        .then(function(result) {
            const messagesHtml = (result.messages && result.messages.length > 0) ? `
                <div class="doc-warning-banner">
                    <i class="ph ph-info"></i> <strong>Aviso de conversión:</strong> Se procesaron ${result.messages.length} notas de estilo/formato durante la renderización.
                </div>
            ` : '';

            const htmlContent = `
                <!DOCTYPE html>
                <html lang="es">
                <head>
                    <meta charset="UTF-8">
                    <style>
                        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap');
                        
                        body {
                            font-family: 'Inter', system-ui, -apple-system, sans-serif;
                            background-color: #f1f5f9;
                            color: #1e293b;
                            margin: 0;
                            padding: 2rem 1.5rem;
                            line-height: 1.7;
                            -webkit-font-smoothing: antialiased;
                        }
                        .paper-wrapper {
                            max-width: 860px;
                            margin: 0 auto;
                            background: #ffffff;
                            padding: 3.5rem 4rem;
                            border-radius: 8px;
                            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
                            border: 1px solid #cbd5e1;
                            position: relative;
                        }
                        .paper-wrapper::before {
                            content: "VISTA PREVIA INTEGRAL DE DOCUMENTO (DOCX)";
                            display: block;
                            font-size: 0.68rem;
                            font-weight: 800;
                            letter-spacing: 1px;
                            color: #64748b;
                            text-transform: uppercase;
                            border-bottom: 2px solid #007bff;
                            padding-bottom: 0.5rem;
                            margin-bottom: 2rem;
                        }
                        h1, .doc-title {
                            color: #002244;
                            font-size: 1.8rem;
                            font-weight: 800;
                            line-height: 1.25;
                            margin-top: 1.5rem;
                            margin-bottom: 1rem;
                        }
                        h2, .doc-subtitle, .doc-heading-1 {
                            color: #002244;
                            font-size: 1.35rem;
                            font-weight: 700;
                            margin-top: 1.75rem;
                            margin-bottom: 0.75rem;
                            border-bottom: 1px solid #e2e8f0;
                            padding-bottom: 0.35rem;
                        }
                        h3, .doc-heading-2 {
                            color: #007bff;
                            font-size: 1.15rem;
                            font-weight: 700;
                            margin-top: 1.4rem;
                            margin-bottom: 0.5rem;
                        }
                        h4, .doc-heading-3 {
                            color: #334155;
                            font-size: 1rem;
                            font-weight: 700;
                            margin-top: 1.2rem;
                            margin-bottom: 0.4rem;
                        }
                        p {
                            margin-bottom: 1.1rem;
                            text-align: justify;
                            font-size: 0.94rem;
                            color: #334155;
                        }
                        strong {
                            color: #0f172a;
                            font-weight: 700;
                        }
                        blockquote, .doc-quote, .doc-quote-intense {
                            border-left: 4px solid #007bff;
                            background-color: #f8fafc;
                            padding: 0.85rem 1.25rem;
                            margin: 1.25rem 0;
                            border-radius: 0 6px 6px 0;
                            font-style: italic;
                            color: #475569;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin: 1.5rem 0;
                            font-size: 0.88rem;
                            background: white;
                        }
                        th, td {
                            border: 1px solid #cbd5e1;
                            padding: 0.65rem 0.85rem;
                            text-align: left;
                        }
                        th {
                            background-color: #f1f5f9;
                            color: #002244;
                            font-weight: 700;
                        }
                        tr:nth-child(even) {
                            background-color: #f8fafc;
                        }
                        ul, ol {
                            padding-left: 1.5rem;
                            margin-bottom: 1.25rem;
                            font-size: 0.94rem;
                            color: #334155;
                        }
                        li {
                            margin-bottom: 0.4rem;
                        }
                        img {
                            max-width: 100%;
                            height: auto;
                            display: block;
                            margin: 1.5rem auto;
                            border-radius: 6px;
                            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                            border: 1px solid #cbd5e1;
                        }
                        code, pre {
                            font-family: 'JetBrains Mono', monospace;
                            background: #f1f5f9;
                            padding: 0.2rem 0.4rem;
                            border-radius: 4px;
                            font-size: 0.85rem;
                            color: #0f172a;
                        }
                        .doc-warning-banner {
                            background: #eff6ff;
                            border: 1px solid #bfdbfe;
                            color: #1e40af;
                            padding: 0.6rem 1rem;
                            border-radius: 6px;
                            font-size: 0.8rem;
                            margin-bottom: 1.5rem;
                        }
                    </style>
                </head>
                <body>
                    <div class="paper-wrapper">
                        ${messagesHtml}
                        ${result.value}
                    </div>
                </body>
                </html>
            `;
            iframe.srcdoc = htmlContent;
        })
        .catch(function(err) {
            console.error('Error Mammoth.js:', err);
            iframe.srcdoc = `<p style="padding:2rem; color:#be123c;">Error al renderizar la estructura visual del archivo Word. (${err.message})</p>`;
        });
}

function cerrarModalPrevisualizacion() {
    const modal = document.getElementById('modalPreviewDocumentModal');
    const iframe = document.getElementById('iframePreviewDocument');
    if (iframe) iframe.src = 'about:blank';
    if (modal) modal.style.display = 'none';
}

// AUTOCOMPLETADO DE NOMBRES DE AUTORES Y TUTORES AL INGRESAR LA CÉDULA
function inicializarAutocompletadoCedulas() {
    const bindCedulaBlur = (inputElem, nomElem, tipoPersona) => {
        if (!inputElem || !nomElem) return;
        inputElem.addEventListener('blur', function() {
            const ced = this.value.trim();
            if (ced.length >= 5 && (!nomElem.value || nomElem.value.trim() === '')) {
                fetch(`?ruta=agregar-documento&accion=buscar_cedula&cedula=${encodeURIComponent(ced)}&tipo=${tipoPersona}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success' && data.nombre) {
                            nomElem.value = data.nombre;
                            nomElem.style.transition = 'background-color 0.3s';
                            nomElem.style.backgroundColor = '#f0fdf4';
                            setTimeout(() => { nomElem.style.backgroundColor = ''; }, 1200);
                        }
                    })
                    .catch(err => console.error('Error al autocompletar persona:', err));
            }
        });
    };

    // Autores principales
    const cedulaInputs = document.getElementsByName('autor_cedula[]');
    const nombreInputs = document.getElementsByName('autor_nombre[]');
    for (let i = 0; i < cedulaInputs.length; i++) {
        bindCedulaBlur(cedulaInputs[i], nombreInputs[i], 'autor');
    }

    // Tutores
    const bindTutor = (cedName, nomName) => {
        const c = document.getElementsByName(cedName)[0];
        const n = document.getElementsByName(nomName)[0];
        if (c && n) bindCedulaBlur(c, n, 'tutor');
    };
    bindTutor('tutor_academico_cedula', 'tutor_academico_nombre');
    bindTutor('tutor_institucional_cedula', 'tutor_institucional_nombre');
    bindTutor('tutor_comunitario_cedula', 'tutor_comunitario_nombre');
}

// PRUEBAS DE EXTRACCIÓN DE TEXTO (SIMULACIÓN DE EXTRACCIÓN DE METADATOS)
function simularExtraccionModal() {
    const fileInput = document.getElementById('input_archivo_extractor');
    if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
        mostrarModalAlerta('warning', 'Archivo Requerido', 'Seleccione o arrastre un documento PDF o Word (.docx) en la casilla de la derecha para simular su extracción.');
        return;
    }

    const file = fileInput.files[0];
    const formData = new FormData();
    formData.append('archivo_pst', file);

    mostrarModalAlerta('info', 'Ejecutando Simulación', `Analizando estructura binaria y texto de "${file.name}"... Por favor espere.`);

    fetch('?ruta=agregar-documento&accion=simular_extraccion', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            const d = data.data;
            const resMsj = `🔍 METADATOS DETECTADOS POR EL EXTRACTOR:\n\n` +
                `• Título: ${d.titulo || 'No detectado'}\n` +
                `• Año: ${d.anio_publicacion || 's.f.'}\n` +
                `• Nivel Académico: ${d.nivel_academico || 'Pregrado'}\n` +
                `• Autores Extraídos: ${d.autores ? d.autores.map(a => a.nombre || a.nombre_completo).filter(Boolean).join(', ') : 'Ninguno'}\n` +
                `• Tutor Académico: ${d.tutor_academico_nombre || 'No detectado'}\n` +
                `• Comunidad Beneficiada: ${d.comunidad_beneficiada || 'No detectada'}\n\n` +
                `📝 FRAGMENTO DE TEXTO EXTRAÍDO:\n"${data.preview_texto}"`;
            mostrarModalAlerta('success', 'Resultado de Simulación', resMsj);
        } else {
            mostrarModalAlerta('error', 'Falla en Simulación', data.message || 'No se pudo procesar la simulación.');
        }
    })
    .catch(err => {
        mostrarModalAlerta('error', 'Error en Servidor', 'Ocurrió un error al procesar la simulación de extracción.');
    });
}

// Disparador de mensajes del servidor al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    inicializarAutocompletadoCedulas();
    <?php if (!empty($error)): ?>
        mostrarModalAlerta('error', 'Atención / Error', <?= json_encode($error) ?>);
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        mostrarModalAlerta('success', 'Operación Exitosa', <?= json_encode($success) ?>);
    <?php endif; ?>
});
</script>

<!-- MODAL ESTRUCTURAL DEL SISTEMA (ALERTAS Y DIÁLOGOS DE CONFIRMACIÓN) -->
<div id="pstSystemModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 34, 68, 0.7); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center;">
    <div style="background: var(--bg-card, #ffffff); border: 1px solid rgba(169, 168, 166, 0.2); border-radius: 8px; width: 90%; max-width: 440px; padding: 1.5rem; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.25); animation: fadeIn 0.2s ease-out;">
        <div id="modalPstIconWrapper" class="modal-pst-icon-box success" style="margin: 0 auto 0.75rem auto; width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
            <i class="ph ph-check-circle"></i>
        </div>
        
        <h3 id="modalPstTitle" style="font-size: 1.1rem; font-weight: 800; color: var(--texto-titulos); margin: 0 0 0.5rem 0;">
            Notificación del Sistema
        </h3>
        
        <p id="modalPstMessage" style="font-size: 0.85rem; color: var(--texto-silenciado); line-height: 1.45; margin: 0 0 1.25rem 0; white-space: pre-line;">
            Mensaje predeterminado.
        </p>

        <div style="display: flex; justify-content: center; gap: 0.6rem;">
            <button type="button" id="btnModalPstCancel" onclick="cerrarModalAlertaPst()" class="btn-cancel" style="display: none; padding: 0.45rem 1rem;">
                Cancelar
            </button>
            <button type="button" id="btnModalPstConfirm" class="btn-save" style="padding: 0.45rem 1.25rem;">
                Aceptar
            </button>
        </div>
    </div>
</div>

<!-- MODAL DE PREVISUALIZACIÓN DE DOCUMENTO DIGITAL (PDF/WORD) -->
<div id="modalPreviewDocumentModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 34, 68, 0.85); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: var(--bg-card, #ffffff); border: 1px solid rgba(169, 168, 166, 0.2); border-radius: 8px; width: 96%; max-width: 1250px; height: 92vh; padding: 1.25rem; display: flex; flex-direction: column; box-shadow: 0 15px 35px rgba(0,0,0,0.35); animation: fadeIn 0.2s ease-out;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(169, 168, 166, 0.2); padding-bottom: 0.5rem; margin-bottom: 0.75rem;">
            <div>
                <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--texto-titulos); margin: 0; display: flex; align-items: center; gap: 0.4rem;">
                    <i class="ph ph-file-text" style="color: var(--color-terciario);"></i> Previsualización del Documento Digital
                </h3>
                <span id="previewFileInfo" style="font-size: 0.75rem; color: var(--texto-silenciado); font-weight: 600;"></span>
            </div>
            <button type="button" onclick="cerrarModalPrevisualizacion()" style="background: none; border: none; font-size: 1.4rem; cursor: pointer; color: var(--texto-silenciado);">&times;</button>
        </div>
        
        <div style="flex: 1; width: 100%; background: #f8fafc; border: 1px solid rgba(169, 168, 166, 0.2); border-radius: 4px; overflow: hidden;">
            <iframe id="iframePreviewDocument" src="about:blank" style="width: 100%; height: 100%; border: none;" title="Vista Previa Documento"></iframe>
        </div>

        <div style="text-align: right; border-top: 1px solid rgba(169, 168, 166, 0.15); padding-top: 0.6rem; margin-top: 0.75rem;">
            <button type="button" onclick="cerrarModalPrevisualizacion()" class="btn-cancel" style="padding: 0.4rem 1.2rem;">Cerrar Vista Previa</button>
        </div>
    </div>
</div>

<!-- MODAL DE PREVISUALIZACIÓN COMPLETA DE FICHA TÉCNICA DE PROYECTO (ADMIN) -->
<div id="modalFichaAdminPst" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 34, 68, 0.85); backdrop-filter: blur(5px); z-index: 99999; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: var(--bg-card, #ffffff); border: 1px solid rgba(169, 168, 166, 0.25); border-radius: 12px; width: 95%; max-width: 1050px; max-height: 92vh; overflow-y: auto; padding: 2rem; box-shadow: 0 20px 45px rgba(0,0,0,0.4); animation: fadeIn 0.2s ease-out;">
        
        <!-- Encabezado de la Ficha -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid rgba(0, 34, 68, 0.1); padding-bottom: 1rem; margin-bottom: 1.25rem;">
            <div style="flex: 1; padding-right: 1rem;">
                <div style="display: flex; gap: 0.4rem; align-items: center; margin-bottom: 0.35rem; flex-wrap: wrap;">
                    <span class="pst-badge-soft" id="modalFichaNivel" style="background: rgba(0, 123, 255, 0.1); color: var(--color-terciario); padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.75rem; font-weight: 800;"></span>
                    <span id="modalFichaEstado" style="padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.75rem; font-weight: 800;"></span>
                </div>
                <h2 id="modalFichaTitulo" style="font-size: 1.35rem; font-weight: 800; color: var(--texto-titulos); margin: 0; line-height: 1.35;"></h2>
            </div>
            <button type="button" onclick="document.getElementById('modalFichaAdminPst').style.display='none'" style="background: rgba(0,0,0,0.05); border: none; font-size: 1.6rem; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; color: var(--texto-silenciado); display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>

        <!-- Contenido Multicolumna y Secciones -->
        <div style="display: flex; flex-direction: column; gap: 1.25rem; font-size: 0.9rem; color: var(--texto-normal);">
            
            <!-- Equipo y Tutores -->
            <div class="grid-2-cols" style="gap: 1.25rem;">
                <div style="background: #fafbfe; padding: 1rem; border-radius: 8px; border: 1px solid rgba(0, 123, 255, 0.12);">
                    <strong style="color: var(--color-terciario); display: flex; align-items: center; gap: 0.35rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem;">
                        <i class="ph ph-users" style="font-size: 1.1rem;"></i> Autores (Estudiantes del Equipo)
                    </strong>
                    <span id="modalFichaAutores" style="font-weight: 600; color: var(--texto-titulos); line-height: 1.45; display: block;"></span>
                </div>

                <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid rgba(169, 168, 166, 0.2);">
                    <strong style="color: var(--color-secundario); display: flex; align-items: center; gap: 0.35rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem;">
                        <i class="ph ph-chalkboard-teacher" style="font-size: 1.1rem;"></i> Tutores del Proyecto
                    </strong>
                    <span id="modalFichaTutores" style="font-weight: 600; color: var(--texto-titulos); line-height: 1.45; display: block;"></span>
                </div>
            </div>

            <!-- Objetivo General -->
            <div id="modalFichaObjWrapper" style="background: #f0f9ff; border-left: 4px solid var(--color-terciario, #007bff); padding: 1rem 1.1rem; border-radius: 0 8px 8px 0;">
                <strong style="color: var(--color-terciario); display: flex; align-items: center; gap: 0.35rem; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.3rem;">
                    <i class="ph ph-target" style="font-size: 1.1rem;"></i> Objetivo General de la Investigación
                </strong>
                <p id="modalFichaObj" style="margin: 0; font-size: 0.95rem; font-weight: 600; color: var(--texto-titulos); line-height: 1.5;"></p>
            </div>

            <!-- Resumen Epistémico -->
            <div>
                <strong style="color: var(--texto-titulos); display: flex; align-items: center; gap: 0.35rem; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem;">
                    <i class="ph ph-book-open" style="font-size: 1.1rem;"></i> Resumen Epistémico / Síntesis de Propuesta
                </strong>
                <p id="modalFichaResumen" style="margin: 0; line-height: 1.6; text-align: justify; background: #ffffff; padding: 1rem; border-radius: 8px; border: 1px solid rgba(169, 168, 166, 0.2);"></p>
            </div>

            <!-- Metadatos Clasificación y Entornos -->
            <div class="grid-2-cols" style="gap: 1rem; background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid rgba(169, 168, 166, 0.2);">
                <div>
                    <strong style="color: var(--texto-titulos); font-size: 0.78rem; display: block; text-transform: uppercase; margin-bottom: 0.2rem;">Línea de Investigación:</strong>
                    <span id="modalFichaLinea" style="font-weight: 700; color: var(--color-terciario);"></span>
                </div>
                <div>
                    <strong style="color: var(--texto-titulos); font-size: 0.78rem; display: block; text-transform: uppercase; margin-bottom: 0.2rem;">Dimensión Operativa:</strong>
                    <span id="modalFichaDimension" style="font-weight: 600;"></span>
                </div>
                <div>
                    <strong style="color: var(--texto-titulos); font-size: 0.78rem; display: block; text-transform: uppercase; margin-bottom: 0.2rem;">Comunidad u Objeto Beneficiario:</strong>
                    <span id="modalFichaComunidad" style="font-weight: 600;"></span>
                </div>
                <div>
                    <strong style="color: var(--texto-titulos); font-size: 0.78rem; display: block; text-transform: uppercase; margin-bottom: 0.2rem;">Año y Fecha de Defensa:</strong>
                    <span id="modalFichaAnio" style="font-weight: 600;"></span>
                </div>
                <div style="grid-column: span 2;">
                    <strong style="color: var(--texto-titulos); font-size: 0.78rem; display: block; text-transform: uppercase; margin-bottom: 0.2rem;">Palabras Clave (Keywords):</strong>
                    <span id="modalFichaKeywords" style="font-style: italic; color: var(--texto-silenciado);"></span>
                </div>
                <div id="modalFichaGitWrapper" style="grid-column: span 2;">
                    <strong style="color: var(--texto-titulos); font-size: 0.78rem; display: block; text-transform: uppercase; margin-bottom: 0.2rem;">Código Fuente (Git):</strong>
                    <a id="modalFichaGit" href="#" target="_blank" style="color: var(--color-terciario); text-decoration: underline; font-weight: 600;"></a>
                </div>
            </div>

        </div>

        <!-- Botones Inferiores -->
        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(169, 168, 166, 0.2); padding-top: 1rem; margin-top: 1.5rem;">
            <div id="modalFichaAdjuntoWrapper">
                <a id="btnModalFichaVerAdjunto" href="#" target="_blank" class="btn-save" style="background: #0284c7; color: white; display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.45rem 1rem; text-decoration: none;">
                    <i class="ph ph-file-pdf" style="font-size: 1.1rem;"></i> Ver / Descargar Documento Adjunto
                </a>
            </div>
            <button type="button" onclick="document.getElementById('modalFichaAdminPst').style.display='none'" class="btn-cancel" style="padding: 0.45rem 1.4rem;">Cerrar Ficha</button>
        </div>
    </div>
</div>

<script>
function abrirModalPrevisualizarFichaAdmin(doc) {
    if (!doc) return;
    document.getElementById('modalFichaNivel').textContent = (doc.nivel_academico || 'Pregrado') + (doc.trayecto ? ' • ' + doc.trayecto : '');
    
    const badgeEstado = document.getElementById('modalFichaEstado');
    if (doc.activo === false || doc.activo === '0' || doc.activo === 0) {
        badgeEstado.textContent = 'Visibilidad: Oculto';
        badgeEstado.style.background = '#fde8e8';
        badgeEstado.style.color = '#9b1c1c';
    } else {
        badgeEstado.textContent = 'Visibilidad: Activo';
        badgeEstado.style.background = '#def7ec';
        badgeEstado.style.color = '#03543f';
    }

    document.getElementById('modalFichaTitulo').textContent = doc.titulo || 'Sin título';
    document.getElementById('modalFichaAutores').textContent = doc.autores_nombres || 'No registrados';
    document.getElementById('modalFichaTutores').textContent = doc.tutores_nombres || 'No registrados';
    document.getElementById('modalFichaObj').textContent = doc.obj_general || 'No registrado';
    document.getElementById('modalFichaResumen').textContent = doc.resumen || 'Sin resumen';
    document.getElementById('modalFichaLinea').textContent = doc.linea_nombre || 'General';
    document.getElementById('modalFichaDimension').textContent = doc.dimension_nombre || 'Sin dimensión asociada';
    document.getElementById('modalFichaComunidad').textContent = doc.comunidad_beneficiada || 'No registrada';
    document.getElementById('modalFichaAnio').textContent = (doc.anio_publicacion || '') + (doc.fecha_defensa ? ' (Defensa: ' + doc.fecha_defensa + ')' : '');
    document.getElementById('modalFichaKeywords').textContent = doc.palabras_clave || 'Ninguna';

    const gitWrapper = document.getElementById('modalFichaGitWrapper');
    const gitLink = document.getElementById('modalFichaGit');
    if (doc.url_repositorio) {
        gitLink.href = doc.url_repositorio;
        gitLink.textContent = doc.url_repositorio;
        gitWrapper.style.display = 'block';
    } else {
        gitWrapper.style.display = 'none';
    }

    const btnAdjuntoWrapper = document.getElementById('modalFichaAdjuntoWrapper');
    const btnAdjunto = document.getElementById('btnModalFichaVerAdjunto');
    if (doc.archivo_pdf) {
        btnAdjunto.href = '?ruta=ver-pdf-pst&id=' + doc.id;
        btnAdjuntoWrapper.style.display = 'block';
    } else {
        btnAdjuntoWrapper.style.display = 'none';
    }

    document.getElementById('modalFichaAdminPst').style.display = 'flex';
}
</script>
