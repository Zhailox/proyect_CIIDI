<style>
/* Ajustes de compresión visual y espaciados */
.main-content {
    padding: 0.75rem 1rem !important;
}
.upload-view-container {
    width: 100%;
    max-width: 100% !important;
    animation: fadeIn 0.4s ease-out;
}
.pst-header {
    margin-bottom: 0.75rem !important;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(169, 168, 166, 0.15);
    padding-bottom: 0.5rem;
}
.pst-header-left h1 {
    font-size: 1.6rem !important;
    margin: 0 0 0.15rem 0 !important;
    color: var(--texto-titulos);
    font-weight: 800;
}
.pst-header-left p {
    font-size: 0.85rem !important;
    color: var(--texto-silenciado);
    margin: 0;
}

.upload-card {
    background: var(--blanco);
    border: 1px solid rgba(169, 168, 166, 0.2);
    border-radius: var(--radius-md, 6px);
    padding: 1.25rem !important;
    box-shadow: none !important;
}
.upload-grid {
    display: grid;
    grid-template-columns: 2.2fr 1fr;
    gap: 1.5rem;
}
@media (max-width: 992px) {
    .upload-grid {
        grid-template-columns: 1fr;
    }
}

.upload-section-title {
    font-size: 1.05rem !important;
    font-weight: 700;
    color: var(--texto-titulos);
    border-bottom: 1px solid rgba(169, 168, 166, 0.2);
    padding-bottom: 0.4rem;
    margin: 0.75rem 0 0.75rem 0;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.upload-section-title:first-of-type {
    margin-top: 0;
}
.upload-input-group {
    margin-bottom: 0.6rem;
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}
.upload-input-group label {
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--texto-titulos);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.upload-input {
    width: 100%;
    padding: 0.45rem 0.6rem !important;
    border: 1px solid rgba(169, 168, 166, 0.4) !important;
    border-radius: var(--radius-sm, 4px) !important;
    background-color: #fcfcfc !important;
    color: var(--texto-normal) !important;
    font-size: 0.85rem !important;
    outline: none;
    transition: border-color 0.2s;
}
.upload-input:focus {
    border-color: var(--color-terciario, #007bff) !important;
}
.grid-2-cols {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 0.75rem;
}

.upload-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    margin-top: 1rem;
    border-top: 1px solid rgba(169, 168, 166, 0.15);
    padding-top: 0.75rem;
}
.btn-cancel {
    padding: 0.45rem 1.2rem;
    background-color: #e2e8f0;
    color: #333;
    border: none;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}
.btn-cancel:hover {
    background-color: #cbd5e0;
}
.btn-save {
    padding: 0.45rem 1.5rem;
    background-color: var(--color-terciario, #007bff);
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 700;
    cursor: pointer;
}
.btn-save:hover {
    background-color: var(--color-secundario, #002244);
}

.alert-message {
    padding: 0.6rem 0.85rem;
    border-radius: 4px;
    margin-bottom: 0.75rem;
    font-size: 0.8rem;
    font-weight: 600;
}
.alert-error {
    background-color: #fde8e8;
    border: 1px solid #f8b4b4;
    color: #c81e1e;
}
.alert-success {
    background-color: #def7ec;
    border: 1px solid #31c48d;
    color: #03543f;
}

.authors-container, .tutors-container {
    background-color: #fafbfe;
    border: 1px solid rgba(169, 168, 166, 0.15);
    border-radius: 6px;
    padding: 0.75rem;
    margin-bottom: 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.sub-label-header {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--color-secundario);
    border-bottom: 1px dashed rgba(169, 168, 166, 0.2);
    padding-bottom: 0.2rem;
    margin-bottom: 0.25rem;
}

/* Estilos de la tabla de listado CRUD */
.crud-table-wrapper {
    background-color: var(--bg-card, #ffffff);
    border: 1px solid rgba(169, 168, 166, 0.2);
    border-radius: var(--radius-md, 6px);
    padding: 1rem;
    margin-top: 1rem;
}
.btn-create-new {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background-color: var(--color-terciario, #007bff);
    color: white;
    padding: 0.45rem 1rem;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 700;
    text-decoration: none;
    transition: background-color 0.2s;
}
.btn-create-new:hover {
    background-color: var(--color-secundario, #002244);
}
.search-crud-bar {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
}
.search-crud-input {
    flex-grow: 1;
    padding: 0.45rem 0.75rem;
    border: 1px solid rgba(169, 168, 166, 0.4);
    border-radius: 4px;
    font-size: 0.85rem;
    outline: none;
}
.search-crud-input:focus {
    border-color: var(--color-terciario, #007bff);
}
.btn-search-crud {
    background-color: #e2e8f0;
    color: #333;
    border: 1px solid rgba(169, 168, 166, 0.3);
    padding: 0.45rem 1rem;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 700;
    cursor: pointer;
}
.btn-search-crud:hover {
    background-color: #cbd5e0;
}

.action-links {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
}
.btn-action-edit {
    color: var(--color-terciario, #007bff);
    text-decoration: none;
    font-weight: 700;
    font-size: 0.8rem;
    padding: 0.2rem 0.4rem;
    border: 1px solid rgba(0, 123, 255, 0.2);
    border-radius: 3px;
    background-color: rgba(0, 123, 255, 0.02);
}
.btn-action-edit:hover {
    background-color: var(--color-terciario, #007bff);
    color: white;
}
.btn-action-delete {
    color: #e53e3e;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.8rem;
    padding: 0.2rem 0.4rem;
    border: 1px solid rgba(229, 62, 62, 0.2);
    border-radius: 3px;
    background-color: rgba(229, 62, 62, 0.02);
}
.btn-action-delete:hover {
    background-color: #e53e3e;
    color: white;
}

/* Paginador plano */
.pst-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.25rem;
    margin-top: 1rem;
    padding-top: 0.5rem;
    border-top: 1px solid rgba(169, 168, 166, 0.1);
}
.page-link {
    display: inline-block;
    padding: 0.3rem 0.6rem;
    background-color: #f7f9fa;
    border: 1px solid rgba(169, 168, 166, 0.2);
    color: var(--color-secundario, #002244);
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 700;
    border-radius: 4px;
    transition: all 0.1s;
}
.page-link:hover {
    background-color: var(--color-terciario, #007bff);
    color: white;
    border-color: var(--color-terciario, #007bff);
}
.page-link.active {
    background-color: var(--color-terciario, #007bff);
    color: white;
    border-color: var(--color-terciario, #007bff);
}
.page-link.disabled {
    background-color: #f3f4f6;
    color: #9ca3af;
    border-color: #e5e7eb;
    pointer-events: none;
}
</style>

<div class="main-content">
    <div class="upload-view-container">
        
        <!-- CABECERA DE LA PÁGINA -->
        <header class="pst-header">
            <div class="pst-header-left">
                <?php if ($accion === 'crear'): ?>
                    <h1>Registrar Nuevo Proyecto</h1>
                    <p>Indexa una nueva investigación en el repositorio institucional.</p>
                <?php elseif ($accion === 'editar'): ?>
                    <h1>Modificar Proyecto Socio-Tecnológico</h1>
                    <p>Edita los metadatos correspondientes del proyecto indexado #<?= $documento['id'] ?>.</p>
                <?php else: ?>
                    <h1>Gestión Documental PST</h1>
                    <p>Catálogo de control y administración para la indexación de investigaciones.</p>
                <?php endif; ?>
            </div>
            
            <?php if ($accion === 'listar'): ?>
                <div style="display: flex; gap: 0.5rem;">
                    <a href="?ruta=gestion-red-neuronal" class="btn-create-new" style="background-color: var(--color-secundario);">
                        <i class="ph ph-cpu" style="font-size: 1rem;"></i> Redes Neuronales (IA)
                    </a>
                    <a href="?ruta=agregar-documento&accion=crear" class="btn-create-new">
                        <i class="ph ph-plus-circle" style="font-size: 1rem;"></i> Agregar Nuevo Proyecto
                    </a>
                </div>
            <?php endif; ?>
        </header>

        <!-- Mensajes de Estado del Formulario -->
        <?php if (!empty($error)): ?>
            <div class="alert-message alert-error">
                <i class="ph ph-warning-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert-message alert-success">
                <i class="ph ph-check-circle"></i> <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <!-- VISTA 1: FORMULARIO (CREAR O EDITAR) -->
        <?php if ($accion === 'crear' || $accion === 'editar'): ?>
            
            <article class="upload-card">
                <form action="?ruta=agregar-documento&accion=<?= $accion ?><?= $accion === 'editar' ? '&id='.$documento['id'] : '' ?>" method="POST">

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
                                    <select id="nivel_academico" name="nivel_academico" class="upload-input" required>
                                        <?php 
                                        $currNivel = $_POST['nivel_academico'] ?? $documento['nivel_academico'] ?? 'Pregrado';
                                        ?>
                                        <option value="Pregrado" <?= ($currNivel === 'Pregrado') ? 'selected' : '' ?>>Pregrado (Ingeniería)</option>
                                        <option value="TSU" <?= ($currNivel === 'TSU') ? 'selected' : '' ?>>TSU</option>
                                        <option value="Especializacion" <?= ($currNivel === 'Especializacion') ? 'selected' : '' ?>>Especialización</option>
                                        <option value="Maestria" <?= ($currNivel === 'Maestria') ? 'selected' : '' ?>>Maestría</option>
                                        <option value="Doctorado" <?= ($currNivel === 'Doctorado') ? 'selected' : '' ?>>Doctorado</option>
                                    </select>
                                </div>
                                <div class="upload-input-group">
                                    <label>PNF / Programa Fijo</label>
                                    <select class="upload-input" disabled>
                                        <option>PNF en Informática</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Panel de Autores (Entre 1 y 4 estudiantes) -->
                            <h3 class="upload-section-title">
                                <i class="ph ph-users"></i> Autores (Estudiantes del Equipo - Máx. 4)
                            </h3>
                            <div class="authors-container">
                                <?php 
                                // Si es post, usamos los valores de post, si es edición, usamos los de autores
                                $autoresList = $autores;
                                if (!empty($_POST['autor_cedula']) && is_array($_POST['autor_cedula'])) {
                                    $autoresList = [];
                                    for ($k = 0; $k < 4; $k++) {
                                        $autoresList[] = [
                                            'cedula' => $_POST['autor_cedula'][$k] ?? '',
                                            'nombre_completo' => $_POST['autor_nombre'][$k] ?? ''
                                        ];
                                    }
                                }
                                if (empty($autoresList)) {
                                    $autoresList = array_fill(0, 4, ['cedula' => '', 'nombre_completo' => '']);
                                }
                                ?>
                                <div class="sub-label-header">Estudiante 1 (Autor Principal) *</div>
                                <div class="grid-2-cols">
                                    <div class="upload-input-group">
                                        <input type="text" name="autor_cedula[]" class="upload-input" value="<?= htmlspecialchars($autoresList[0]['cedula'] ?? '') ?>" placeholder="Cédula (V-30123456)" required>
                                    </div>
                                    <div class="upload-input-group">
                                        <input type="text" name="autor_nombre[]" class="upload-input" value="<?= htmlspecialchars($autoresList[0]['nombre_completo'] ?? '') ?>" placeholder="Nombres y Apellidos del Estudiante" required>
                                    </div>
                                </div>
                                
                                <div class="sub-label-header">Estudiante 2 (Opcional)</div>
                                <div class="grid-2-cols">
                                    <div class="upload-input-group">
                                        <input type="text" name="autor_cedula[]" class="upload-input" value="<?= htmlspecialchars($autoresList[1]['cedula'] ?? '') ?>" placeholder="Cédula">
                                    </div>
                                    <div class="upload-input-group">
                                        <input type="text" name="autor_nombre[]" class="upload-input" value="<?= htmlspecialchars($autoresList[1]['nombre_completo'] ?? '') ?>" placeholder="Nombres y Apellidos">
                                    </div>
                                </div>

                                <div class="sub-label-header">Estudiante 3 (Opcional)</div>
                                <div class="grid-2-cols">
                                    <div class="upload-input-group">
                                        <input type="text" name="autor_cedula[]" class="upload-input" value="<?= htmlspecialchars($autoresList[2]['cedula'] ?? '') ?>" placeholder="Cédula">
                                    </div>
                                    <div class="upload-input-group">
                                        <input type="text" name="autor_nombre[]" class="upload-input" value="<?= htmlspecialchars($autoresList[2]['nombre_completo'] ?? '') ?>" placeholder="Nombres y Apellidos">
                                    </div>
                                </div>

                                <div class="sub-label-header">Estudiante 4 (Opcional)</div>
                                <div class="grid-2-cols">
                                    <div class="upload-input-group">
                                        <input type="text" name="autor_cedula[]" class="upload-input" value="<?= htmlspecialchars($autoresList[3]['cedula'] ?? '') ?>" placeholder="Cédula">
                                    </div>
                                    <div class="upload-input-group">
                                        <input type="text" name="autor_nombre[]" class="upload-input" value="<?= htmlspecialchars($autoresList[3]['nombre_completo'] ?? '') ?>" placeholder="Nombres y Apellidos">
                                    </div>
                                </div>
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
                                                <?= htmlspecialchars($linea['nombre']) ?>
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

                            <!-- Zona Interactiva de Extracción -->
                            <div class="drag-drop-zone" id="dropzone">
                                <input type="file" id="input_archivo_extractor" accept=".pdf,.docx" style="display:none;">
                                <div class="drag-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="17 8 12 3 7 8"></polyline>
                                        <line x1="12" y1="3" x2="12" y2="15"></line>
                                    </svg>
                                </div>
                                <h3 class="drag-title">Carga Automática Inteligente</h3>
                                <p class="drag-desc">Arrastra tu archivo PDF o Word (.docx) aquí para auto-completar los campos de la investigación.</p>
                                <button type="button" class="btn-browse" id="btnBrowseFile">Seleccionar Archivo</button>
                            </div>

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
                    <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" class="search-crud-input" placeholder="Buscar por títulos, palabras clave o autores de PST...">
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
                                            <strong><?= htmlspecialchars($doc['titulo']) ?></strong>
                                            <?php if (!empty($doc['resumen'])): ?>
                                                <div style="font-size: 0.75rem; color: var(--texto-silenciado); margin-top: 0.15rem;">
                                                    <?= htmlspecialchars(substr($doc['resumen'], 0, 120)) ?>...
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($doc['autores_nombres'] ?? 'No registrados') ?></td>
                                        <td><strong><?= $doc['anio_publicacion'] ?></strong></td>
                                        <td style="text-align: center;">
                                            <div class="action-links">
                                                <a href="?ruta=agregar-documento&accion=editar&id=<?= $doc['id'] ?>" class="btn-action-edit" title="Modificar Metadatos">
                                                    <i class="ph ph-pencil-simple"></i> Editar
                                                </a>
                                                <a href="?ruta=agregar-documento&accion=eliminar&id=<?= $doc['id'] ?>" class="btn-action-delete" title="Eliminar Registro" onclick="return confirm('¿Desea eliminar este proyecto del repositorio?');">
                                                    <i class="ph ph-trash"></i> Eliminar
                                                </a>
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

<style>
@keyframes pulse-loader {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.1); opacity: 0.7; }
}
.drag-drop-zone.active {
    border-color: var(--color-terciario, #007bff) !important;
    background: rgba(0, 123, 255, 0.05) !important;
}
</style>

<script>
// JSON con todas las dimensiones operativas del sistema para el filtrado dinámico
const todasDimensiones = <?= json_encode($lineas && $dimensiones ? $dimensiones : []) ?>;
const activeDimensionId = <?= json_encode($_POST['dimension_id'] ?? $documento['dimension_id'] ?? '') ?>;

// Función para actualizar dinámicamente el selector de dimensiones operativas
function updateDimensionOptions(selectedLineaId) {
    const dimSelect = document.getElementById('dimension_id');
    if (!dimSelect) return;
    
    // Resetear opciones
    dimSelect.innerHTML = '<option value="">Seleccione una Dimensión...</option>';
    
    if (!selectedLineaId) {
        dimSelect.disabled = true;
        return;
    }
    
    dimSelect.disabled = false;
    
    // Filtrar dimensiones por la línea
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

document.addEventListener('DOMContentLoaded', () => {
    const lineaSelect = document.getElementById('linea_id');
    if (lineaSelect) {
        // Inicializar el selector de dimensión si hay una línea seleccionada previamente
        if (lineaSelect.value) {
            updateDimensionOptions(lineaSelect.value);
        }
        
        // Escuchar cambios de línea
        lineaSelect.addEventListener('change', (e) => {
            updateDimensionOptions(e.target.value);
        });
    }

    // --- CONFIGURACIÓN DE LA EXTRACCIÓN DE METADATOS ---
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('input_archivo_extractor');
    const btnBrowse = document.getElementById('btnBrowseFile');

    if (dropzone && fileInput) {
        // Abrir selector al clickear la zona (pero no si se hace click directamente en el input)
        dropzone.addEventListener('click', (e) => {
            if (e.target !== fileInput) {
                fileInput.click();
            }
        });

        // Eventos de arrastrar y soltar
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
            if (files.length > 0) {
                procesarArchivoSeleccionado(files[0]);
            }
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                procesarArchivoSeleccionado(e.target.files[0]);
            }
        });
    }
});

function procesarArchivoSeleccionado(file) {
    const name = file.name;
    const ext = name.split('.').pop().toLowerCase();
    
    if (ext !== 'pdf' && ext !== 'docx') {
        alert("Formato de archivo inválido. Solo se admiten archivos PDF y Word (.docx).");
        return;
    }

    if (confirm(`Se ha detectado el archivo "${name}". ¿Desea extraer de manera automática la información para rellenar el formulario?`)) {
        subirYExtraerDatos(file);
    }
}

function subirYExtraerDatos(file) {
    const formData = new FormData();
    formData.append('archivo_pst', file);

    const overlay = document.getElementById('loadingOverlay');
    const progressBar = document.getElementById('progressBar');
    const progressPercent = document.getElementById('progressPercent');
    const loaderTitle = document.getElementById('loaderTitle');
    const loaderText = document.getElementById('loaderText');

    if (overlay) {
        overlay.style.display = 'flex';
        progressBar.style.width = '0%';
        progressPercent.textContent = '0%';
        loaderTitle.textContent = 'Analizando Documento';
        loaderText.textContent = 'Subiendo archivo e iniciando la extracción de metadatos...';
    }

    // Simular progreso de carga
    let progress = 0;
    const interval = setInterval(() => {
        if (progress < 90) {
            progress += Math.floor(Math.random() * 8) + 3;
            if (progress > 90) progress = 90;
            if (progressBar && progressPercent) {
                progressBar.style.width = progress + '%';
                progressPercent.textContent = progress + '%';
            }
        }
    }, 120);

    // Petición AJAX al controlador
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '?ruta=agregar-documento&accion=extraer', true);
    
    xhr.onload = function() {
        clearInterval(interval);
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    if (progressBar && progressPercent) {
                        progressBar.style.width = '100%';
                        progressPercent.textContent = '100%';
                    }
                    setTimeout(() => {
                        rellenarFormulario(response.data);
                        if (overlay) overlay.style.display = 'none';
                        // Desmarcar input para permitir re-selección
                        document.getElementById('input_archivo_extractor').value = '';
                    }, 400);
                } else {
                    if (overlay) overlay.style.display = 'none';
                    alert("Error en la extracción: " + response.message);
                    document.getElementById('input_archivo_extractor').value = '';
                }
            } catch (e) {
                if (overlay) overlay.style.display = 'none';
                console.error("Respuesta inválida:", xhr.responseText);
                alert("Error al procesar la respuesta del servidor.");
                document.getElementById('input_archivo_extractor').value = '';
            }
        } else {
            clearInterval(interval);
            if (overlay) overlay.style.display = 'none';
            alert("Error en el servidor (Código " + xhr.status + ").");
            document.getElementById('input_archivo_extractor').value = '';
        }
    };

    xhr.onerror = function() {
        clearInterval(interval);
        if (overlay) overlay.style.display = 'none';
        alert("Error de red al intentar conectarse al servidor.");
        document.getElementById('input_archivo_extractor').value = '';
    };

    xhr.send(formData);
}

function rellenarFormulario(data) {
    if (!data) return;

    // Rellenar campos de texto principales
    if (data.titulo) document.getElementById('titulo').value = data.titulo;
    if (data.anio_publicacion) document.getElementById('anio_publicacion').value = data.anio_publicacion;
    if (data.resumen) document.getElementById('resumen').value = data.resumen;
    if (data.palabras_clave) document.getElementById('palabras_clave').value = data.palabras_clave;
    
    if (data.comunidad_beneficiada) {
        document.getElementById('comunidad_beneficiada').value = data.comunidad_beneficiada;
    }

    // Rellenar Autores (Estudiantes)
    const cedulaInputs = document.getElementsByName('autor_cedula[]');
    const nombreInputs = document.getElementsByName('autor_nombre[]');
    if (data.autores && Array.isArray(data.autores)) {
        data.autores.forEach((autor, idx) => {
            if (idx < cedulaInputs.length) {
                cedulaInputs[idx].value = autor.cedula || '';
                nombreInputs[idx].value = autor.nombre || '';
            }
        });
    }

    // Rellenar Tutores
    if (data.tutor_academico_cedula) document.getElementsByName('tutor_academico_cedula')[0].value = data.tutor_academico_cedula;
    if (data.tutor_academico_nombre) document.getElementsByName('tutor_academico_nombre')[0].value = data.tutor_academico_nombre;
    if (data.tutor_institucional_cedula) document.getElementsByName('tutor_institucional_cedula')[0].value = data.tutor_institucional_cedula;
    if (data.tutor_institucional_nombre) document.getElementsByName('tutor_institucional_nombre')[0].value = data.tutor_institucional_nombre;
    if (data.tutor_comunitario_cedula) document.getElementsByName('tutor_comunitario_cedula')[0].value = data.tutor_comunitario_cedula;
    if (data.tutor_comunitario_nombre) document.getElementsByName('tutor_comunitario_nombre')[0].value = data.tutor_comunitario_nombre;

    // Rellenar Línea e Investigación
    if (data.linea_id) {
        const lineaSelect = document.getElementById('linea_id');
        if (lineaSelect) {
            lineaSelect.value = data.linea_id;
            updateDimensionOptions(data.linea_id);
            
            // Seleccionar Dimensión después de rellenar opciones
            if (data.dimension_id) {
                const dimSelect = document.getElementById('dimension_id');
                if (dimSelect) {
                    dimSelect.value = data.dimension_id;
                }
            }
        }
    }
}
</script>
