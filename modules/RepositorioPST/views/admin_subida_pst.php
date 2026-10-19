<?php
require_once CORE_PATH . 'Security/Auth.php';
require_once __DIR__ . '/../services/ConfigService.php';
require_once __DIR__ . '/../../SuperAdmin/services/SystemConfigService.php';
$nivelAdminPst = SystemConfigService::get('accesos_modulos.repositorio_pst.admin', 1);

$parseIniToMb = function(?string $str): float {
    if (empty($str)) return 2.0;
    $str = trim($str);
    $unit = strtolower($str[strlen($str) - 1] ?? '');
    $num = (float)$str;
    switch ($unit) {
        case 'g': return $num * 1024;
        case 'm': return $num;
        case 'k': return $num / 1024;
        default: return $num / (1024 * 1024);
    }
};
$configMaxMb = (float)ConfigService::get('archivos.max_size_mb', 20);
$phpUploadMaxMb = $parseIniToMb(ini_get('upload_max_filesize'));
$phpPostMaxMb = $parseIniToMb(ini_get('post_max_size'));
$maxMbEfectivo = round($configMaxMb, 2);
$maxArchivosLote = (int)ConfigService::get('archivos.max_archivos_lote', 5);
$carrerasList = $carreras ?? [];
$currCarrera = $_POST['id_carrera'] ?? $documento['id_carrera'] ?? $documento['carrera_id'] ?? 1;
$labelsNiveles = !empty($labelsNiveles) ? $labelsNiveles : [
    'Pregrado' => 'Pregrado',
    'Especializacion' => 'Especialización',
    'Maestria' => 'Maestría',
    'Doctorado' => 'Doctorado'
];
$nivelesConTrayecto = !empty($nivelesConTrayecto) ? $nivelesConTrayecto : ['Pregrado'];
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
                    <p>Extrae los datos automáticamente desde el documento o completa la ficha manualmente.</p>
                <?php elseif ($accion === 'editar'): ?>
                    <h1>Modificar Proyecto Socio-Tecnológico</h1>
                    <p>Actualiza la información académica, el equipo o el documento digital del proyecto.</p>
                <?php else: ?>
                    <h1>Gestión Documental PST</h1>
                    <p>Administra el catálogo de investigaciones, revisa su estado y organiza los archivos del repositorio.</p>
                <?php endif; ?>
            </div>
            <?php if ($accion === 'crear' || $accion === 'editar'): ?>
                <div>
                    <a href="?ruta=agregar-documento" class="btn-cancel" style="padding: 0.5rem 1rem; font-size: 0.82rem;">
                        <i class="ph ph-arrow-left"></i> Volver al listado
                    </a>
                </div>
            <?php endif; ?>
        </header>

        <!-- VISTA 1: FORMULARIO DE REGISTRO Y EDICIÓN -->
        <?php if ($accion === 'crear' || $accion === 'editar'): ?>
            
            <article class="upload-card">
                <!-- Barra de progreso de llenado de la ficha -->
                <div style="background: #fafbfe; border: 1px solid rgba(112, 144, 203, 0.2); border-radius: 6px; padding: 0.65rem 1rem; margin-bottom: 1.1rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 0.6rem; flex: 1; min-width: 240px;">
                        <i class="ph ph-check-square-offset" style="color: var(--color-secundario); font-size: 1.25rem;"></i>
                        <div style="flex: 1;">
                            <div style="display: flex; justify-content: space-between; font-size: 0.78rem; font-weight: 700; color: var(--texto-titulos); margin-bottom: 0.25rem;">
                                <span id="labelCompletitudFicha">Datos completados en la ficha</span>
                                <span id="porcentajeCompletitudFicha" style="color: var(--color-secundario);">0%</span>
                            </div>
                            <div style="width: 100%; height: 6px; background: rgba(80, 89, 132, 0.12); border-radius: 4px; overflow: hidden;">
                                <div id="barraCompletitudFicha" style="width: 0%; height: 100%; background: var(--color-terciario); transition: width 0.25s ease;"></div>
                            </div>
                        </div>
                    </div>
                    <span id="estadoCompletitudTexto" style="font-size: 0.75rem; font-weight: 600; color: var(--texto-silenciado);">Completa los campos principales para publicar el proyecto</span>
                </div>

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
                                <div id="alerta_duplicado_titulo" style="display: none; margin-top: 0.4rem; padding: 0.4rem 0.6rem; border-radius: 4px; font-size: 0.78rem; background-color: #fffbeb; border: 1px solid #fde68a; color: #92400e;">
                                    <i class="ph ph-warning"></i> <strong>Atención:</strong> Ya existe un proyecto registrado con este título en el repositorio. Modifique el título antes de guardar.
                                </div>
                            </div>

                            <div class="grid-2-cols">
                                <div class="upload-input-group">
                                    <label for="anio_publicacion">Año de Creación / Publicación *</label>
                                    <input type="number" id="anio_publicacion" name="anio_publicacion" class="upload-input" min="2018" max="2027" value="<?= htmlspecialchars($_POST['anio_publicacion'] ?? $documento['anio_publicacion'] ?? date('Y')) ?>" required>
                                </div>
                                <div class="upload-input-group">
                                    <label for="fecha_defensa">Fecha de Defensa *</label>
                                    <input type="date" id="fecha_defensa" name="fecha_defensa" class="upload-input" value="<?= htmlspecialchars($_POST['fecha_defensa'] ?? $documento['fecha_defensa'] ?? date('Y-m-d')) ?>" required>
                                    <span id="hint_fecha_defensa" style="display: none; font-size: 0.72rem; margin-top: 0.25rem; line-height: 1.3;"></span>
                                </div>
                            </div>

                            <div class="grid-2-cols">
                                <div class="upload-input-group">
                                    <label for="nivel_academico">Nivel Académico *</label>
                                    <select id="nivel_academico" name="nivel_academico" class="upload-input" onchange="toggleTrayectoByNivel()" required>
                                        <?php 
                                        $nivelesAcademicosDB = $nivelesAcademicos ?? ['Pregrado', 'Especialización', 'Maestría', 'Doctorado'];
                                        $currCarreraForm = (int)($_POST['id_carrera'] ?? $documento['carrera_id'] ?? $documento['id_carrera'] ?? 1);
                                        $trayectosDB = [];
                                        if (!empty($trayectosList)) {
                                            foreach ($trayectosList as $trItem) {
                                                if (is_array($trItem) && isset($trItem['id_carrera'])) {
                                                    if ((int)$trItem['id_carrera'] === $currCarreraForm) {
                                                        $trayectosDB[] = $trItem;
                                                    }
                                                } else {
                                                    $trayectosDB[] = $trItem;
                                                }
                                            }
                                        }
                                        // Deduplicación estricta para garantizar que nunca se repita ningún trayecto en el formulario
                                        $dedupTrayectos = [];
                                        $vistosNombres = [];
                                        $listaParaFiltrar = !empty($trayectosDB) ? $trayectosDB : ($trayectosList ?? []);
                                        foreach ($listaParaFiltrar as $trItem) {
                                            $nom = is_array($trItem) ? ($trItem['nombre'] ?? '') : (string)$trItem;
                                            if ($nom && !in_array($nom, $vistosNombres)) {
                                                $vistosNombres[] = $nom;
                                                $dedupTrayectos[] = $trItem;
                                            }
                                        }
                                        $trayectosDB = !empty($dedupTrayectos) ? $dedupTrayectos : [
                                            ['id' => 1, 'nombre' => 'Trayecto I', 'numero' => 1],
                                            ['id' => 2, 'nombre' => 'Trayecto II', 'numero' => 2],
                                            ['id' => 3, 'nombre' => 'Trayecto III', 'numero' => 3],
                                            ['id' => 4, 'nombre' => 'Trayecto IV', 'numero' => 4]
                                        ];
                                        $currNivel = $_POST['nivel_academico'] ?? $documento['nivel_academico'] ?? ($nivelesAcademicosDB[0] ?? 'Pregrado');
                                        foreach ($nivelesAcademicosDB as $nivelItem):
                                            $labelVisible = $labelsNiveles[$nivelItem] ?? $nivelItem;
                                        ?>
                                            <option value="<?= htmlspecialchars($nivelItem) ?>" <?= ($currNivel === $nivelItem) ? 'selected' : '' ?>><?= htmlspecialchars($labelVisible) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="upload-input-group" id="container_trayecto" style="<?= (in_array($currNivel, $nivelesConTrayecto)) ? 'display: block;' : 'display: none;' ?>">
                                    <label for="trayecto">Trayecto del PNF *</label>
                                    <select id="trayecto" name="trayecto" class="upload-input" onchange="syncIdTrayecto(this)">
                                        <?php 
                                        $currTrayecto = $_POST['trayecto'] ?? $documento['trayecto'] ?? ($trayectosDB[0]['nombre'] ?? ($trayectosDB[0] ?? 'Trayecto I'));
                                        $currIdTrayecto = $_POST['id_trayecto'] ?? $documento['id_trayecto'] ?? ($trayectosDB[0]['id'] ?? '');
                                        foreach ($trayectosDB as $trItem):
                                            $tNombre = is_array($trItem) ? $trItem['nombre'] : $trItem;
                                            $tId = is_array($trItem) ? $trItem['id'] : '';
                                            $isSelected = ($currIdTrayecto && (string)$currIdTrayecto === (string)$tId) || ($currTrayecto === $tNombre);
                                        ?>
                                            <option value="<?= htmlspecialchars($tNombre) ?>" data-id="<?= htmlspecialchars($tId) ?>" <?= $isSelected ? 'selected' : '' ?>><?= htmlspecialchars($tNombre) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" id="id_trayecto" name="id_trayecto" value="<?= htmlspecialchars($currIdTrayecto) ?>">
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
                                    $cedRaw = $autoresList[$i]['cedula'] ?? '';
                                    $cedTipo = 'V-';
                                    $cedNum = $cedRaw;
                                    if (stripos($cedNum, 'E-') === 0) {
                                        $cedTipo = 'E-';
                                        $cedNum = substr($cedNum, 2);
                                    } elseif (stripos($cedNum, 'V-') === 0) {
                                        $cedTipo = 'V-';
                                        $cedNum = substr($cedNum, 2);
                                    }
                                    $cedNum = preg_replace('/\D/', '', $cedNum);
                                ?>
                                    <div class="sub-label-header"><?= $label ?></div>
                                    <div class="grid-2-cols">
                                        <div class="upload-input-group">
                                            <div style="display: flex; gap: 0.35rem; align-items: stretch;">
                                                <select class="upload-input autor-cedula-tipo" style="width: 75px; flex-shrink: 0; cursor: pointer; font-weight: 700; padding: 0.5rem 0.4rem;" onchange="sincronizarCedulasPST()">
                                                    <option value="V-" <?= $cedTipo === 'V-' ? 'selected' : '' ?>>V-</option>
                                                    <option value="E-" <?= $cedTipo === 'E-' ? 'selected' : '' ?>>E-</option>
                                                </select>
                                                <input type="text" inputmode="numeric" class="upload-input autor-cedula-num" value="<?= htmlspecialchars($cedNum) ?>" placeholder="Cédula<?= $esObligatorio ? ' (30123456)' : '' ?>" maxlength="9" oninput="this.value = this.value.replace(/\D/g, ''); sincronizarCedulasPST();" style="flex: 1;">
                                                <input type="hidden" name="autor_cedula[]" class="autor-cedula-hidden" value="<?= htmlspecialchars($cedRaw) ?>">
                                            </div>
                                        </div>
                                        <div class="upload-input-group">
                                            <input type="text" name="autor_nombre[]" class="upload-input" value="<?= htmlspecialchars($autoresList[$i]['nombre_completo'] ?? '') ?>" placeholder="Nombres y Apellidos<?= $esObligatorio ? ' del Estudiante' : '' ?>" <?= $esObligatorio ? 'required' : '' ?>>
                                        </div>
                                    </div>
                                <?php endfor; ?>
                            </div>

                            <!-- Panel de Tutores del PST (respetando max_tutores) -->
                            <?php 
                            $maxTutores = (int)ConfigService::get('limites_equipo.max_tutores', 3);
                            $tAcadCed = $_POST['tutor_academico_cedula'] ?? $tutores['academico']['cedula'] ?? '';
                            $tAcadNom = $_POST['tutor_academico_nombre'] ?? $tutores['academico']['nombre'] ?? '';
                            $tInstCed = $_POST['tutor_institucional_cedula'] ?? $tutores['institucional']['cedula'] ?? '';
                            $tInstNom = $_POST['tutor_institucional_nombre'] ?? $tutores['institucional']['nombre'] ?? '';
                            $tComCed = $_POST['tutor_comunitario_cedula'] ?? $tutores['comunitario']['cedula'] ?? '';
                            $tComNom = $_POST['tutor_comunitario_nombre'] ?? $tutores['comunitario']['nombre'] ?? '';
                            ?>
                            <h3 class="upload-section-title">
                                <i class="ph ph-chalkboard-teacher"></i> Tutores del Proyecto (Máx. <?= $maxTutores ?>)
                            </h3>
                            <div class="tutors-container">
                                <?php if ($maxTutores >= 1): 
                                    $tAcadTipo = (stripos($tAcadCed, 'E-') === 0) ? 'E-' : 'V-';
                                    $tAcadNum = preg_replace('/\D/', '', (stripos($tAcadCed, 'V-') === 0 || stripos($tAcadCed, 'E-') === 0) ? substr($tAcadCed, 2) : $tAcadCed);
                                ?>
                                <div class="sub-label-header">Tutor Académico</div>
                                <div class="grid-2-cols">
                                    <div class="upload-input-group">
                                        <div style="display: flex; gap: 0.35rem; align-items: stretch;">
                                            <select id="tutor_academico_tipo" class="upload-input" style="width: 75px; flex-shrink: 0; cursor: pointer; font-weight: 700; padding: 0.5rem 0.4rem;" onchange="sincronizarCedulasPST()">
                                                <option value="V-" <?= $tAcadTipo === 'V-' ? 'selected' : '' ?>>V-</option>
                                                <option value="E-" <?= $tAcadTipo === 'E-' ? 'selected' : '' ?>>E-</option>
                                            </select>
                                            <input type="text" inputmode="numeric" id="tutor_academico_num" class="upload-input" value="<?= htmlspecialchars($tAcadNum) ?>" placeholder="Cédula Tutor" maxlength="9" oninput="this.value = this.value.replace(/\D/g, ''); sincronizarCedulasPST();" style="flex: 1;">
                                            <input type="hidden" name="tutor_academico_cedula" id="tutor_academico_cedula" value="<?= htmlspecialchars($tAcadCed) ?>">
                                        </div>
                                    </div>
                                    <div class="upload-input-group">
                                        <input type="text" name="tutor_academico_nombre" id="tutor_academico_nombre" class="upload-input" value="<?= htmlspecialchars($tAcadNom) ?>" placeholder="Nombre Completo del Tutor Académico">
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if ($maxTutores >= 2): 
                                    $tInstTipo = (stripos($tInstCed, 'E-') === 0) ? 'E-' : 'V-';
                                    $tInstNum = preg_replace('/\D/', '', (stripos($tInstCed, 'V-') === 0 || stripos($tInstCed, 'E-') === 0) ? substr($tInstCed, 2) : $tInstCed);
                                ?>
                                <div class="sub-label-header">Tutor Institucional</div>
                                <div class="grid-2-cols">
                                    <div class="upload-input-group">
                                        <div style="display: flex; gap: 0.35rem; align-items: stretch;">
                                            <select id="tutor_institucional_tipo" class="upload-input" style="width: 75px; flex-shrink: 0; cursor: pointer; font-weight: 700; padding: 0.5rem 0.4rem;" onchange="sincronizarCedulasPST()">
                                                <option value="V-" <?= $tInstTipo === 'V-' ? 'selected' : '' ?>>V-</option>
                                                <option value="E-" <?= $tInstTipo === 'E-' ? 'selected' : '' ?>>E-</option>
                                            </select>
                                            <input type="text" inputmode="numeric" id="tutor_institucional_num" class="upload-input" value="<?= htmlspecialchars($tInstNum) ?>" placeholder="Cédula Tutor" maxlength="9" oninput="this.value = this.value.replace(/\D/g, ''); sincronizarCedulasPST();" style="flex: 1;">
                                            <input type="hidden" name="tutor_institucional_cedula" id="tutor_institucional_cedula" value="<?= htmlspecialchars($tInstCed) ?>">
                                        </div>
                                    </div>
                                    <div class="upload-input-group">
                                        <input type="text" name="tutor_institucional_nombre" id="tutor_institucional_nombre" class="upload-input" value="<?= htmlspecialchars($tInstNom) ?>" placeholder="Nombre Completo del Tutor Institucional">
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if ($maxTutores >= 3): 
                                    $tComTipo = (stripos($tComCed, 'E-') === 0) ? 'E-' : 'V-';
                                    $tComNum = preg_replace('/\D/', '', (stripos($tComCed, 'V-') === 0 || stripos($tComCed, 'E-') === 0) ? substr($tComCed, 2) : $tComCed);
                                ?>
                                <div class="sub-label-header">Tutor Comunitario</div>
                                <div class="grid-2-cols">
                                    <div class="upload-input-group">
                                        <div style="display: flex; gap: 0.35rem; align-items: stretch;">
                                            <select id="tutor_comunitario_tipo" class="upload-input" style="width: 75px; flex-shrink: 0; cursor: pointer; font-weight: 700; padding: 0.5rem 0.4rem;" onchange="sincronizarCedulasPST()">
                                                <option value="V-" <?= $tComTipo === 'V-' ? 'selected' : '' ?>>V-</option>
                                                <option value="E-" <?= $tComTipo === 'E-' ? 'selected' : '' ?>>E-</option>
                                            </select>
                                            <input type="text" inputmode="numeric" id="tutor_comunitario_num" class="upload-input" value="<?= htmlspecialchars($tComNum) ?>" placeholder="Cédula Tutor" maxlength="9" oninput="this.value = this.value.replace(/\D/g, ''); sincronizarCedulasPST();" style="flex: 1;">
                                            <input type="hidden" name="tutor_comunitario_cedula" id="tutor_comunitario_cedula" value="<?= htmlspecialchars($tComCed) ?>">
                                        </div>
                                    </div>
                                    <div class="upload-input-group">
                                        <input type="text" name="tutor_comunitario_nombre" id="tutor_comunitario_nombre" class="upload-input" value="<?= htmlspecialchars($tComNom) ?>" placeholder="Nombre Completo del Tutor Comunitario">
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <h3 class="upload-section-title">
                                <i class="ph ph-tree-structure"></i> Clasificación y Programa Académico
                            </h3>

                            <div class="grid-2-cols">
                                <?php 
                                $permitirFiltroCarrera = (bool)ConfigService::get('buscador.permitir_filtro_carrera', false);
                                $carrerasList = $carreras ?? [];
                                $currCarrera = $_POST['id_carrera'] ?? $documento['id_carrera'] ?? $documento['carrera_id'] ?? ($permitirFiltroCarrera ? '' : 1);
                                $currLinea = $_POST['linea_id'] ?? $documento['linea_id'] ?? '';
                                $lineasParaMostrar = (!empty($currCarrera)) ? ($lineas ?? []) : [];
                                ?>
                                <!-- Selector de Carrera / PNF -->
                                <div class="upload-input-group">
                                    <label for="id_carrera"><i class="ph ph-graduation-cap"></i> Programa Académico *</label>
                                    <?php if ($permitirFiltroCarrera): ?>
                                        <select id="id_carrera" name="id_carrera" class="upload-input" onchange="alCambiarCarreraFormulario(this.value)" required>
                                            <option value="">Seleccione una Carrera...</option>
                                            <?php foreach ($carrerasList as $cItem): ?>
                                                <option value="<?= $cItem['id'] ?>" <?= ((string)$currCarrera === (string)$cItem['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($cItem['nombre']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else: ?>
                                        <input type="hidden" name="id_carrera" value="1">
                                        <div style="background: rgba(80, 89, 132, 0.08); border: 1px dashed rgba(80, 89, 132, 0.3); padding: 9px 12px; border-radius: var(--radius-sm, 8px); display: flex; align-items: center; justify-content: space-between; font-size: 0.88rem; font-weight: 700; color: var(--texto-titulos, #1E293B);" title="El repositorio está configurado para PNF en Informática">
                                            <span>PNF en Informática</span>
                                            <span style="color: #64748B;"><i class="ph ph-lock-key"></i></span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Selector de Línea de Investigación -->
                                <div class="upload-input-group">
                                    <label for="linea_id">Línea de Investigación *</label>
                                    <select id="linea_id" name="linea_id" class="upload-input" <?= empty($currCarrera) && $permitirFiltroCarrera ? 'disabled' : '' ?> required>
                                        <option value=""><?= empty($currCarrera) && $permitirFiltroCarrera ? 'Primero seleccione una Carrera...' : 'Seleccione una Línea...' ?></option>
                                        <?php if (!empty($lineasParaMostrar)): ?>
                                            <?php foreach ($lineasParaMostrar as $linea): ?>
                                                <option value="<?= $linea['id'] ?>" <?= ($currLinea == $linea['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($linea['nombre'] ?? '') ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="grid-2-cols" style="margin-top: 0.5rem;">
                                <!-- Selector de Dimensión Operativa Dependiente -->
                                <div class="upload-input-group">
                                    <label for="dimension_id">Dimensión Operativa</label>
                                    <select id="dimension_id" name="dimension_id" class="upload-input" disabled>
                                        <option value="">Seleccione una Dimensión...</option>
                                    </select>
                                </div>

                                <!-- Selector de Carreras Vinculadas -->
                                <div class="upload-input-group">
                                    <label><i class="ph ph-tag"></i> Carreras Vinculadas</label>
                                    <div class="carreras-vinculadas-container" style="display: flex; flex-wrap: wrap; gap: 0.45rem; margin-top: 0.35rem; padding: 0.5rem 0.75rem; background: rgba(80, 89, 132, 0.05); border: 1px solid #cbd5e1; border-radius: 8px;">
                                        <?php 
                                        $vinculadasIds = $_POST['carreras_vinculadas'] ?? $documento['carreras_vinculadas_ids'] ?? [];
                                        if (!is_array($vinculadasIds)) $vinculadasIds = [];
                                        ?>
                                        <?php foreach ($carrerasList as $cItem): ?>
                                            <?php $esBase = ((string)$currCarrera === (string)$cItem['id']); ?>
                                            <label class="tag-carrera-check" id="tag_carrera_label_<?= $cItem['id'] ?>" style="display: inline-flex; align-items: center; gap: 0.35rem; cursor: pointer; padding: 0.25rem 0.55rem; border-radius: 6px; font-size: 0.78rem; font-weight: 600; border: 1px solid <?= (in_array((int)$cItem['id'], array_map('intval', $vinculadasIds)) && !$esBase) ? 'var(--color-terciario)' : '#cbd5e1' ?>; background: <?= (in_array((int)$cItem['id'], array_map('intval', $vinculadasIds)) && !$esBase) ? 'rgba(112, 144, 203, 0.15)' : '#ffffff' ?>; color: var(--color-secundario); transition: all 0.2s; user-select: none; <?= $esBase ? 'opacity: 0.45; pointer-events: none;' : '' ?>">
                                                <input type="checkbox" name="carreras_vinculadas[]" value="<?= $cItem['id'] ?>" class="check-carrera-vinculada" <?= in_array((int)$cItem['id'], array_map('intval', $vinculadasIds)) && !$esBase ? 'checked' : '' ?> <?= $esBase ? 'disabled' : '' ?> style="cursor: pointer;" onchange="actualizarEstiloTagCarrera(this)">
                                                <span><?= htmlspecialchars($cItem['nombre']) ?></span>
                                                <?php if ($esBase): ?>
                                                    <span style="font-size: 0.68rem; font-style: italic; color: var(--texto-silenciado);">Base</span>
                                                <?php endif; ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                    <span style="font-size: 0.73rem; color: var(--texto-silenciado); margin-top: 0.25rem; display: block;">
                                        Etiquete una o varias carreras secundarias afines a esta investigación.
                                    </span>
                                </div>
                            </div>

                            <div class="grid-2-cols">
                                <div class="upload-input-group">
                                    <label for="comunidad_beneficiada">Comunidad u Objeto Beneficiario</label>
                                    <input type="text" id="comunidad_beneficiada" name="comunidad_beneficiada" class="upload-input" value="<?= htmlspecialchars($_POST['comunidad_beneficiada'] ?? $documento['comunidad_beneficiada'] ?? '') ?>" placeholder="Ej: Consejo Comunal SAPNNAET">
                                </div>
                                <div class="upload-input-group">
                                    <label for="palabras_clave">Palabras Clave</label>
                                    <input type="text" id="palabras_clave" name="palabras_clave" class="upload-input" value="<?= htmlspecialchars($_POST['palabras_clave'] ?? $documento['palabras_clave'] ?? '') ?>" placeholder="Ej: Inventario, PHP, PostgreSQL, MVC">
                                </div>
                            </div>

                            <div class="upload-input-group" style="margin-top: 0.25rem;">
                                <label for="url_repositorio">URL del Repositorio de Código</label>
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
                            <div style="background-color: #fafbfe; border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 4px; padding: 0.75rem; font-size: 0.8rem; color: var(--texto-comun); line-height: 1.4; margin-bottom: 0.5rem;">
                                <i class="ph ph-shield-check" style="color: var(--color-secundario); font-weight: 700; font-size: 1rem; vertical-align: middle; margin-right: 0.25rem;"></i> 
                                <strong>Ruta Automática:</strong> El sistema generará una ruta de almacenamiento segura e indexada en el repositorio basándose en el título de la investigación.
                            </div>

                            <!-- Zona Interactiva de Extracción / Sustitución de Archivo -->
                            <div class="drag-drop-zone" id="dropzone">
                                <input type="file" id="input_archivo_extractor" name="archivo_pst" accept=".pdf,.docx,.doc" <?= $accion === 'crear' ? 'multiple' : '' ?> style="display:none;">
                                <div class="drag-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="17 8 12 3 7 8"></polyline>
                                        <line x1="12" y1="3" x2="12" y2="15"></line>
                                    </svg>
                                </div>
                                <?php 
                                $maxMb = $maxMbEfectivo;
                                if ($accion === 'editar'): 
                                ?>
                                    <h3 class="drag-title">Sustitución de Documento Digital</h3>
                                    <p class="drag-desc">Arrastra o selecciona un nuevo archivo PDF o Word. Máx. <?= $maxMb ?> MB para reemplazar el documento actual.</p>
                                    <button type="button" class="btn-browse" id="btnBrowseFile">Sustituir Archivo Adjunto</button>
                                    
                                    <div id="badgeArchivoSustituido" style="display: none; margin-top: 0.75rem; background: rgba(80, 89, 132, 0.08); border: 1px solid rgba(80, 89, 132, 0.2); border-radius: 4px; padding: 0.5rem 0.75rem; text-align: left; font-size: 0.78rem; color: var(--color-secundario);">
                                        <i class="ph ph-file-check" style="font-size: 1rem; vertical-align: middle; margin-right: 0.25rem;"></i>
                                        <strong>Nuevo archivo listo para sustituir:</strong> <span id="nombreArchivoSustituidoText" style="font-weight: 700;"></span>
                                    </div>
                                <?php else: ?>
                                    <h3 class="drag-title">Carga Automática e Indexación por Lotes</h3>
                                    <p class="drag-desc">Arrastra tus archivos PDF o Word aquí. Máx. <?= $maxArchivosLote ?> archivos por lote y <?= $maxMb ?> MB por archivo.</p>
                                    <button type="button" class="btn-browse" id="btnBrowseFile">Seleccionar Archivo(s)</button>
                                <?php endif; ?>
                            </div>

                            <!-- COLA DE DOCUMENTOS POR LOTES UI (Exclusivo en modo Creación Masiva) -->
                            <?php if ($accion === 'crear'): ?>
                            <div id="contenedorColaDocumentos" style="display: none; background: var(--bg-card, #ffffff); border: 1px solid rgba(169, 168, 166, 0.2); border-radius: 6px; padding: 0.75rem; margin-top: 0.5rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; border-bottom: 1px solid rgba(169, 168, 166, 0.15); padding-bottom: 0.4rem;">
                                    <h4 style="font-size: 0.85rem; font-weight: 700; color: var(--texto-titulos); margin: 0; display: flex; align-items: center; gap: 0.35rem;">
                                        <i class="ph ph-stack"></i> Documentos Cargados en Lote (<span id="countCola">0</span> / <?= $maxArchivosLote ?> máx.)
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

                            <div style="background-color: #fafbfe; border: 1px solid rgba(112, 144, 203, 0.2); border-radius: 4px; padding: 0.75rem; font-size: 0.8rem; color: var(--texto-comun); line-height: 1.4;">
                                <i class="ph ph-info" style="color: var(--color-terciario); font-weight: 700;"></i> 
                                <strong>Validación de Registro:</strong> Los campos marcados con <strong>*</strong> son estrictamente necesarios para que la base de datos indexe y clasifique el PST en el repositorio.
                            </div>

                        </div>

                    </div>

                    <!-- Botones de Acción -->
                    <div class="upload-actions">
                        <a href="?ruta=agregar-documento" class="btn-cancel">
                            <i class="ph ph-arrow-left"></i> Cancelar y Volver
                        </a>
                        <button type="button" class="btn-cancel" style="background-color: rgba(80, 89, 132, 0.08); color: var(--color-secundario);" onclick="abrirModalPrevisualizacionDocumento()">
                            <i class="ph ph-eye"></i> Previsualizar Documento
                        </button>
                        <button type="button" class="btn-clear" id="btnLimpiarFormulario" onclick="limpiarFormularioPst()">
                            <i class="ph ph-eraser"></i> Limpiar Formulario
                        </button>
                        <button type="button" class="btn-clear" id="btnGuardarBorrador" onclick="guardarBorradorEnCola()" style="display: none; background-color: rgba(112, 144, 203, 0.1); color: var(--color-secundario); border-color: rgba(80, 89, 132, 0.3);">
                            <i class="ph ph-floppy-disk"></i> Guardar en Borrador
                        </button>
                        <button type="submit" class="btn-save">
                            <?= $accion === 'editar' ? 'Guardar Cambios' : 'Registrar Recurso' ?>
                        </button>
                    </div>

                </form>
            </article>

        <!-- VISTA 2: TABLA DE GESTIÓN -->
        <?php else: ?>
            
            <?php 
            $totalCatalogReal    = $statsResumen['total_catalog'] ?? ($pagination['total_items'] ?? count($documentos ?? []));
            $totalActivosReal    = $statsResumen['total_activos'] ?? 0;
            $totalOcultosReal    = $statsResumen['total_ocultos'] ?? 0;
            $totalConPdfReal     = $statsResumen['total_con_pdf'] ?? 0;
            $totalConGitReal     = $statsResumen['total_con_git'] ?? 0;
            $totalIncompletos    = $statsResumen['total_incompletos'] ?? 0;
            $totalVistasGlobal   = $statsResumen['total_vistas'] ?? 0;
            $storagePstMb        = $statsResumen['storage_pst_mb'] ?? 0;
            $storageTmpMb        = $statsResumen['storage_tmp_mb'] ?? 0;
            $storageTmpCount     = $statsResumen['storage_tmp_count'] ?? 0;
            $formatosConteo      = $statsResumen['formatos'] ?? ['pdf' => 0, 'docx' => 0, 'doc' => 0];

            $fCarrera = $filtrosAdmin['carrera_id'] ?? '';
            $fTrayecto = $filtrosAdmin['trayecto'] ?? '';
            $fLinea = $filtrosAdmin['linea_id'] ?? '';
            $fAnio = $filtrosAdmin['anio'] ?? '';
            $fEstado = $filtrosAdmin['estado_filtro'] ?? '';
            $fOrden = $filtrosAdmin['orden'] ?? 'recientes';
            $fLimit = $pagination['limit'] ?? 10;
            $hayFiltrosActivos = !empty($q) || !empty($fCarrera) || !empty($fTrayecto) || !empty($fLinea) || !empty($fAnio) || !empty($fEstado) || ($fOrden !== 'recientes');

            $exportParams = $_GET;
            $exportParams['ruta'] = 'agregar-documento';
            $exportParams['accion'] = 'exportar_csv';
            $urlExportarCsv = '?' . http_build_query($exportParams);
            ?>

            <!-- TARJETAS DE MÉTRICAS E INDICADORES DEL CATÁLOGO -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 0.9rem; margin-bottom: 1.15rem;">
                
                <!-- Tarjeta 1: Total en Catálogo -->
                <a href="?ruta=agregar-documento" style="text-decoration: none; background: rgba(255, 255, 255, 0.65); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid <?= empty($fEstado) ? 'var(--color-secundario)' : 'rgba(80, 89, 132, 0.18)' ?>; border-radius: 12px; padding: 0.95rem 1.1rem; display: flex; align-items: center; gap: 0.9rem; box-shadow: 0 8px 20px rgba(0,0,0,0.03); transition: transform 0.15s ease;">
                    <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(80, 89, 132, 0.12); color: var(--color-secundario); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                        <i class="ph ph-folder-open"></i>
                    </div>
                    <div style="min-width: 0;">
                        <div style="font-size: 1.45rem; font-weight: 800; color: var(--texto-titulos); line-height: 1.1;"><?= number_format($totalCatalogReal) ?></div>
                        <div style="font-size: 0.72rem; font-weight: 700; color: var(--texto-silenciado); text-transform: uppercase; letter-spacing: 0.4px; margin-top: 0.15rem;">Proyectos en Catálogo</div>
                        <div style="font-size: 0.72rem; color: var(--color-secundario); font-weight: 600; margin-top: 0.15rem;">
                            <?= number_format($totalVistasGlobal) ?> lecturas acumuladas
                        </div>
                    </div>
                </a>

                <!-- Tarjeta 2: Visibles y Ocultos -->
                <a href="?ruta=agregar-documento&estado_filtro=<?= $fEstado === 'ocultos' ? 'activos' : 'ocultos' ?>" style="text-decoration: none; background: rgba(255, 255, 255, 0.65); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid <?= in_array($fEstado, ['activos', 'ocultos'], true) ? 'var(--color-secundario)' : 'rgba(80, 89, 132, 0.18)' ?>; border-radius: 12px; padding: 0.95rem 1.1rem; display: flex; align-items: center; gap: 0.9rem; box-shadow: 0 8px 20px rgba(0,0,0,0.03); transition: transform 0.15s ease;" title="Haz clic para filtrar visibles u ocultos">
                    <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(80, 89, 132, 0.12); color: var(--color-secundario); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                        <i class="ph ph-eye"></i>
                    </div>
                    <div style="min-width: 0;">
                        <div style="font-size: 1.45rem; font-weight: 800; color: var(--texto-titulos); line-height: 1.1;"><?= number_format($totalActivosReal) ?></div>
                        <div style="font-size: 0.72rem; font-weight: 700; color: var(--texto-silenciado); text-transform: uppercase; letter-spacing: 0.4px; margin-top: 0.15rem;">Visibles al Público</div>
                        <div style="font-size: 0.72rem; color: var(--texto-silenciado); font-weight: 600; margin-top: 0.15rem;">
                            <?= number_format($totalOcultosReal) ?> ocultos actualmente
                        </div>
                    </div>
                </a>

                <!-- Tarjeta 3: Archivos Digitales y Formatos -->
                <a href="?ruta=agregar-documento&estado_filtro=con_git" style="text-decoration: none; background: rgba(255, 255, 255, 0.65); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid <?= $fEstado === 'con_git' ? 'var(--color-terciario)' : 'rgba(80, 89, 132, 0.18)' ?>; border-radius: 12px; padding: 0.95rem 1.1rem; display: flex; align-items: center; gap: 0.9rem; box-shadow: 0 8px 20px rgba(0,0,0,0.03); transition: transform 0.15s ease;" title="Haz clic para ver los proyectos con enlace de código">
                    <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(112, 144, 203, 0.15); color: var(--color-terciario); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                        <i class="ph ph-file-text"></i>
                    </div>
                    <div style="min-width: 0;">
                        <div style="font-size: 1.45rem; font-weight: 800; color: var(--texto-titulos); line-height: 1.1;"><?= number_format($totalConPdfReal) ?></div>
                        <div style="font-size: 0.72rem; font-weight: 700; color: var(--texto-silenciado); text-transform: uppercase; letter-spacing: 0.4px; margin-top: 0.15rem;">Documentos Adjuntos</div>
                        <div style="font-size: 0.71rem; color: var(--color-terciario); font-weight: 600; margin-top: 0.15rem;">
                            <?= (int)$formatosConteo['pdf'] ?> PDF • <?= (int)$formatosConteo['docx'] + (int)$formatosConteo['doc'] ?> Word • <?= number_format($totalConGitReal) ?> con Git
                        </div>
                    </div>
                </a>

                <!-- Tarjeta 4: Calidad de las Fichas -->
                <a href="?ruta=agregar-documento&estado_filtro=incompletos" style="text-decoration: none; background: rgba(255, 255, 255, 0.65); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid <?= $fEstado === 'incompletos' ? 'var(--color-secundario)' : 'rgba(80, 89, 132, 0.18)' ?>; border-radius: 12px; padding: 0.95rem 1.1rem; display: flex; align-items: center; gap: 0.9rem; box-shadow: 0 8px 20px rgba(0,0,0,0.03); transition: transform 0.15s ease;" title="Ver proyectos a los que les falta comunidad o palabras clave">
                    <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(80, 89, 132, 0.12); color: var(--color-secundario); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                        <i class="ph ph-clipboard-text"></i>
                    </div>
                    <div style="min-width: 0;">
                        <div style="font-size: 1.45rem; font-weight: 800; color: var(--texto-titulos); line-height: 1.1;"><?= number_format($totalIncompletos) ?></div>
                        <div style="font-size: 0.72rem; font-weight: 700; color: var(--texto-silenciado); text-transform: uppercase; letter-spacing: 0.4px; margin-top: 0.15rem;">Fichas por Completar</div>
                        <div style="font-size: 0.72rem; color: var(--texto-silenciado); font-weight: 600; margin-top: 0.15rem;">
                            <?= $totalIncompletos > 0 ? 'Haz clic para revisarlas' : 'Todas las fichas completas' ?>
                        </div>
                    </div>
                </a>

                <!-- Tarjeta 5: Uso de Espacio en Servidor -->
                <div style="background: rgba(255, 255, 255, 0.65); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(80, 89, 132, 0.18); border-radius: 12px; padding: 0.95rem 1.1rem; display: flex; align-items: center; justify-content: space-between; gap: 0.6rem; box-shadow: 0 8px 20px rgba(0,0,0,0.03);">
                    <div style="display: flex; align-items: center; gap: 0.85rem; min-width: 0;">
                        <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(112, 144, 203, 0.15); color: var(--color-terciario); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                            <i class="ph ph-hard-drives"></i>
                        </div>
                        <div style="min-width: 0;">
                            <div style="font-size: 1.35rem; font-weight: 800; color: var(--texto-titulos); line-height: 1.1;"><?= number_format($storagePstMb, 2) ?> MB</div>
                            <div style="font-size: 0.72rem; font-weight: 700; color: var(--texto-silenciado); text-transform: uppercase; letter-spacing: 0.4px; margin-top: 0.15rem;">Espacio en Servidor</div>
                            <div style="font-size: 0.71rem; color: var(--texto-silenciado); font-weight: 600; margin-top: 0.15rem;">
                                <?= $storageTmpCount > 0 ? "{$storageTmpCount} temporales ocupando {$storageTmpMb} MB" : "Sin archivos temporales" ?>
                            </div>
                        </div>
                    </div>
                    <?php if ($storageTmpCount > 0): ?>
                        <button type="button" onclick="limpiarTemporalesServidor()" class="btn-cancel" style="padding: 0.35rem 0.55rem; font-size: 0.72rem; flex-shrink: 0;" title="Borrar archivos temporales en desuso">
                            <i class="ph ph-broom"></i> Limpiar
                        </button>
                    <?php endif; ?>
                </div>

            </div>

            <!-- CONSOLA DE BÚSQUEDA, FILTROS Y ACCIONES OPERATIVAS -->
            <div style="background: rgba(255, 255, 255, 0.45); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(112, 144, 203, 0.25); border-radius: 12px; padding: 1rem 1.15rem; margin-bottom: 1.25rem; box-shadow: 0 8px 25px rgba(0,0,0,0.03); display: flex; flex-direction: column; gap: 0.85rem;">
                
                <!-- Fila Superior: Buscador y Botones Principales -->
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.85rem; flex-wrap: wrap;">
                    <form id="formFiltrosAdminPst" method="GET" action="" style="display: flex; flex-direction: column; gap: 0.75rem; width: 100%;">
                        <input type="hidden" name="ruta" value="agregar-documento">

                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; flex: 1; min-width: 280px;">
                                <div style="position: relative; flex: 1; max-width: 460px;">
                                    <i class="ph ph-magnifying-glass" style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: var(--color-secundario); font-size: 1.05rem;"></i>
                                    <input type="text" name="q" value="<?= htmlspecialchars($q ?? '') ?>" class="upload-input" style="padding-left: 2.4rem !important; border-radius: 6px !important; width: 100%;" placeholder="Buscar por título, autor, cédula, tutor o comunidad...">
                                </div>
                                <button type="submit" class="btn-save" style="padding: 0.5rem 1.1rem; font-size: 0.82rem; display: inline-flex; align-items: center; gap: 0.35rem;">
                                    <i class="ph ph-magnifying-glass"></i> Filtrar
                                </button>
                                <?php if ($hayFiltrosActivos): ?>
                                    <a href="?ruta=agregar-documento" class="btn-cancel" style="padding: 0.5rem 0.9rem; font-size: 0.8rem;">
                                        <i class="ph ph-arrow-counter-clockwise"></i> Limpiar filtros
                                    </a>
                                <?php endif; ?>
                            </div>

                            <!-- Acciones de Gestión Masiva y Catálogo -->
                            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                <a href="<?= htmlspecialchars($urlExportarCsv) ?>" class="btn-cancel" style="padding: 0.5rem 0.9rem; font-size: 0.8rem; background: rgba(80, 89, 132, 0.08); color: var(--color-secundario); border: 1px solid rgba(80, 89, 132, 0.2);" title="Descargar listado en formato compatible con Excel">
                                    <i class="ph ph-file-csv"></i> Exportar listado
                                </a>

                                <?php if (Auth::requierePrivilegioMinimo($nivelAdminPst, 'eliminar', 'RepositorioPST', false) && $totalCatalogReal > 0): ?>
                                    <button type="button" onclick="abrirModalPurgarCatalogo()" class="btn-clear" style="padding: 0.5rem 0.95rem; font-size: 0.8rem;" title="Borrar todos los proyectos con clave de SuperAdmin">
                                        <i class="ph ph-trash"></i> Vaciar catálogo
                                    </button>
                                <?php endif; ?>

                                <?php if (Auth::requierePrivilegioMinimo($nivelAdminPst, 'crear', 'RepositorioPST', false)): ?>
                                    <a href="?ruta=agregar-documento&accion=crear" class="btn-create-new" style="padding: 0.55rem 1.2rem; font-size: 0.85rem; box-shadow: 0 4px 14px rgba(80, 89, 132, 0.2);">
                                        <i class="ph ph-plus-circle" style="font-size: 1.05rem;"></i> Agregar Nuevo Proyecto
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Fila Inferior: Selectores Multicriterio -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.6rem; padding-top: 0.65rem; border-top: 1px solid rgba(112, 144, 203, 0.18);">
                            <div>
                                <select name="carrera_id" class="upload-input" onchange="this.form.submit()" style="font-size: 0.8rem !important;">
                                    <option value="">Todas las carreras</option>
                                    <?php foreach ($carrerasList as $cItem): ?>
                                        <option value="<?= $cItem['id'] ?>" <?= ((string)$fCarrera === (string)$cItem['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cItem['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <select name="trayecto" class="upload-input" onchange="this.form.submit()" style="font-size: 0.8rem !important;">
                                    <option value="">Todos los trayectos</option>
                                    <?php foreach (['Trayecto I', 'Trayecto II', 'Trayecto III', 'Trayecto IV'] as $tOpt): ?>
                                        <option value="<?= $tOpt ?>" <?= ($fTrayecto === $tOpt) ? 'selected' : '' ?>><?= $tOpt ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <select name="linea_id" class="upload-input" onchange="this.form.submit()" style="font-size: 0.8rem !important;">
                                    <option value="">Todas las líneas</option>
                                    <?php foreach (($lineas ?? []) as $lItem): ?>
                                        <option value="<?= $lItem['id'] ?>" <?= ((string)$fLinea === (string)$lItem['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($lItem['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <select name="anio" class="upload-input" onchange="this.form.submit()" style="font-size: 0.8rem !important;">
                                    <option value="">Todos los años</option>
                                    <?php for ($y = (int)date('Y') + 1; $y >= 2018; $y--): ?>
                                        <option value="<?= $y ?>" <?= ((string)$fAnio === (string)$y) ? 'selected' : '' ?>>Año <?= $y ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <div>
                                <select name="estado_filtro" class="upload-input" onchange="this.form.submit()" style="font-size: 0.8rem !important;">
                                    <option value="">Todos los estados</option>
                                    <option value="activos" <?= $fEstado === 'activos' ? 'selected' : '' ?>>Solo visibles</option>
                                    <option value="ocultos" <?= $fEstado === 'ocultos' ? 'selected' : '' ?>>Solo ocultos</option>
                                    <option value="incompletos" <?= $fEstado === 'incompletos' ? 'selected' : '' ?>>Fichas por completar</option>
                                    <option value="con_git" <?= $fEstado === 'con_git' ? 'selected' : '' ?>>Con enlace Git</option>
                                    <option value="sin_archivo" <?= $fEstado === 'sin_archivo' ? 'selected' : '' ?>>Sin documento adjunto</option>
                                </select>
                            </div>

                            <div>
                                <select name="orden" class="upload-input" onchange="this.form.submit()" style="font-size: 0.8rem !important;">
                                    <option value="recientes" <?= $fOrden === 'recientes' ? 'selected' : '' ?>>Más recientes primero</option>
                                    <option value="anio_desc" <?= $fOrden === 'anio_desc' ? 'selected' : '' ?>>Año más reciente</option>
                                    <option value="anio_asc" <?= $fOrden === 'anio_asc' ? 'selected' : '' ?>>Año más antiguo</option>
                                    <option value="titulo_asc" <?= $fOrden === 'titulo_asc' ? 'selected' : '' ?>>Título de la A a la Z</option>
                                    <option value="vistas_desc" <?= $fOrden === 'vistas_desc' ? 'selected' : '' ?>>Más leídos</option>
                                </select>
                            </div>

                            <div>
                                <select name="limit" class="upload-input" onchange="this.form.submit()" style="font-size: 0.8rem !important;">
                                    <?php foreach ([10, 25, 50, 100] as $limOpt): ?>
                                        <option value="<?= $limOpt ?>" <?= ((int)$fLimit === $limOpt) ? 'selected' : '' ?>>Mostrar <?= $limOpt ?> filas</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- CONTENEDOR DE TABLA DE GESTIÓN -->
            <section class="crud-table-wrapper" style="background: rgba(255, 255, 255, 0.25); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(112, 144, 203, 0.25); border-radius: 12px; padding: 1.1rem; box-shadow: 0 8px 30px rgba(0, 0, 0, 0.04);">
                
                <div class="table-responsive">
                    <table class="pst-table" style="width: 100%; border-collapse: separate; border-spacing: 0 0.4rem;">
                        <thead>
                            <tr style="border-bottom: 2px solid rgba(112, 144, 203, 0.2);">
                                <th style="width: 36px; text-align: center; padding: 0.75rem 0.5rem;">
                                    <input type="checkbox" id="checkAllPstRows" onchange="alternarSeleccionTodosPst(this)" style="width: 16px; height: 16px; cursor: pointer; accent-color: var(--color-secundario);" title="Seleccionar todos los de esta página">
                                </th>
                                <th style="width: 44%; padding: 0.75rem 0.8rem; color: var(--texto-titulos); font-size: 0.76rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">PROYECTO Y CLASIFICACIÓN</th>
                                <th style="width: 24%; padding: 0.75rem 0.8rem; color: var(--texto-titulos); font-size: 0.76rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">EQUIPO</th>
                                <th style="width: 10%; padding: 0.75rem 0.6rem; color: var(--texto-titulos); font-size: 0.76rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">AÑO</th>
                                <th style="text-align: center; width: 22%; padding: 0.75rem 0.8rem; color: var(--texto-titulos); font-size: 0.76rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($documentos)): ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 3rem 1.5rem; color: var(--texto-silenciado); background: rgba(255, 255, 255, 0.4); border-radius: 8px;">
                                        <i class="ph ph-folder-open" style="font-size: 2.5rem; color: var(--color-terciario); opacity: 0.6; display: block; margin-bottom: 0.5rem;"></i>
                                        No se encontraron proyectos con los filtros seleccionados.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php 
                                $baseRoot = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__, 3);
                                foreach ($documentos as $doc): 
                                    $doc['archivo_existe'] = !empty($doc['archivo_pdf']) && ConfigService::existeArchivoFisico($doc['archivo_pdf']);
                                    $extArchivo = !empty($doc['archivo_pdf']) ? strtoupper(pathinfo($doc['archivo_pdf'], PATHINFO_EXTENSION)) : '';
                                    $pesoArchivoMb = 0;
                                    if ($doc['archivo_existe']) {
                                        $fullDocPath = $baseRoot . '/' . ltrim($doc['archivo_pdf'], '/\\');
                                        $pesoArchivoMb = round(((int)@filesize($fullDocPath)) / (1024 * 1024), 2);
                                    }
                                    $fichaIncompleta = empty(trim($doc['palabras_clave'] ?? '')) || empty(trim($doc['comunidad_beneficiada'] ?? '')) || empty(trim($doc['resumen'] ?? ''));
                                ?>
                                    <tr style="background: rgba(255, 255, 255, 0.5); transition: all 0.2s ease; border-radius: 8px;">
                                         <td style="text-align: center; vertical-align: middle; padding: 0.75rem 0.5rem; border-radius: 8px 0 0 8px;">
                                             <input type="checkbox" class="check-pst-row" value="<?= (int)$doc['id'] ?>" onchange="actualizarBarraLotePst()" style="width: 16px; height: 16px; cursor: pointer; accent-color: var(--color-secundario);">
                                         </td>
                                         <td class="pst-td-title" style="padding: 0.85rem 0.8rem;">
                                              <div style="display: flex; align-items: center; gap: 0.35rem; flex-wrap: wrap; margin-bottom: 0.35rem;">
                                                  <?php 
                                                  $nivelDoc = $doc['nivel_academico'] ?? 'Pregrado';
                                                  $nivelDocLabel = $labelsNiveles[$nivelDoc] ?? $nivelDoc;
                                                  ?>
                                                  <?php if (!empty($doc['carrera_nombre'])): ?>
                                                      <span class="pst-badge-soft" style="background-color: rgba(80, 89, 132, 0.14); color: var(--color-secundario); padding: 0.15rem 0.45rem; border-radius: 4px; font-size: 0.68rem; font-weight: 700; border: 1px solid rgba(80, 89, 132, 0.22);">
                                                          <?= htmlspecialchars($doc['carrera_nombre']) ?>
                                                      </span>
                                                  <?php else: ?>
                                                      <span class="pst-badge-soft" style="background-color: rgba(80, 89, 132, 0.12); color: var(--color-secundario); padding: 0.15rem 0.45rem; border-radius: 4px; font-size: 0.68rem; font-weight: 700; border: 1px solid rgba(80, 89, 132, 0.2);">
                                                          <?= htmlspecialchars($nivelDocLabel) ?>
                                                      </span>
                                                  <?php endif; ?>

                                                  <?php if (in_array($nivelDoc, $nivelesConTrayecto) && !empty($doc['trayecto'])): ?>
                                                      <span class="pst-badge-soft" style="background-color: rgba(112, 144, 203, 0.12); color: var(--color-terciario); padding: 0.15rem 0.45rem; border-radius: 4px; font-size: 0.68rem; font-weight: 700; border: 1px solid rgba(112, 144, 203, 0.2);"><?= htmlspecialchars($doc['trayecto']) ?></span>
                                                  <?php endif; ?>

                                                  <?php if (!empty($doc['linea_nombre'])): ?>
                                                      <span class="pst-badge-soft" style="background-color: rgba(112, 144, 203, 0.1); color: var(--texto-titulos); padding: 0.15rem 0.45rem; border-radius: 4px; font-size: 0.68rem; font-weight: 600; border: 1px solid rgba(112, 144, 203, 0.22);">
                                                          <?= htmlspecialchars($doc['linea_nombre']) ?>
                                                      </span>
                                                  <?php endif; ?>

                                                  <?php if (($doc['activo'] ?? true)): ?>
                                                      <span style="background: rgba(80, 89, 132, 0.1); color: var(--color-secundario); padding: 0.15rem 0.45rem; border-radius: 4px; font-size: 0.68rem; font-weight: 700; border: 1px solid rgba(80, 89, 132, 0.2); display: inline-flex; align-items: center; gap: 0.2rem;">
                                                          <span style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--color-secundario); display: inline-block;"></span> Visible
                                                      </span>
                                                  <?php else: ?>
                                                      <span style="background: rgba(100, 116, 139, 0.12); color: var(--texto-silenciado); padding: 0.15rem 0.45rem; border-radius: 4px; font-size: 0.68rem; font-weight: 700; border: 1px solid rgba(100, 116, 139, 0.25); display: inline-flex; align-items: center; gap: 0.2rem;">
                                                          <span style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--texto-silenciado); display: inline-block;"></span> Oculto
                                                      </span>
                                                  <?php endif; ?>

                                                  <?php if ($doc['archivo_existe']): ?>
                                                      <span style="background: rgba(112, 144, 203, 0.1); color: var(--color-secundario); padding: 0.12rem 0.4rem; border-radius: 4px; font-size: 0.66rem; font-weight: 700;">
                                                          <?= htmlspecialchars($extArchivo) ?> • <?= $pesoArchivoMb ?> MB
                                                      </span>
                                                  <?php else: ?>
                                                      <span style="background: #fff1f2; color: #e11d48; padding: 0.12rem 0.4rem; border-radius: 4px; font-size: 0.66rem; font-weight: 700; border: 1px solid rgba(225, 29, 72, 0.2);">
                                                          Sin archivo
                                                      </span>
                                                  <?php endif; ?>

                                                  <?php if ($fichaIncompleta): ?>
                                                      <span style="background: #fffbeb; color: #b45309; padding: 0.12rem 0.4rem; border-radius: 4px; font-size: 0.66rem; font-weight: 700; border: 1px solid #fde68a;" title="A esta ficha le falta comunidad o palabras clave">
                                                          Por completar
                                                      </span>
                                                  <?php endif; ?>

                                                  <?php if (!empty($doc['url_repositorio'])): ?>
                                                      <a href="<?= htmlspecialchars($doc['url_repositorio']) ?>" target="_blank" style="color: var(--color-secundario); font-size: 0.68rem; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 0.2rem; background: rgba(80, 89, 132, 0.08); padding: 0.12rem 0.4rem; border-radius: 4px;" title="Abrir código fuente">
                                                          <i class="ph ph-git-branch"></i> Git
                                                      </a>
                                                  <?php endif; ?>
                                              </div>

                                              <strong style="font-size: 0.9rem; color: var(--texto-titulos); line-height: 1.35; display: block;"><?= htmlspecialchars($doc['titulo'] ?? '') ?></strong>

                                              <?php if (!empty($doc['comunidad_beneficiada'])): ?>
                                                  <div style="font-size: 0.74rem; color: var(--texto-silenciado); margin-top: 0.2rem; display: flex; align-items: center; gap: 0.3rem;">
                                                      <i class="ph ph-buildings" style="color: var(--color-terciario);"></i>
                                                      <span><?= htmlspecialchars($doc['comunidad_beneficiada']) ?></span>
                                                  </div>
                                              <?php endif; ?>
                                         </td>
                                         <td style="padding: 0.85rem 0.8rem; color: var(--texto-comun); font-size: 0.82rem; vertical-align: middle;">
                                             <div style="display: flex; align-items: flex-start; gap: 0.4rem;">
                                                 <i class="ph ph-users" style="color: var(--color-terciario); font-size: 1rem; margin-top: 2px; flex-shrink: 0;"></i>
                                                 <div>
                                                     <div style="font-weight: 600; color: var(--texto-titulos); line-height: 1.35;"><?= htmlspecialchars($doc['autores_nombres'] ?? 'No registrados') ?></div>
                                                     <?php if (!empty($doc['tutores_nombres'])): ?>
                                                         <div style="font-size: 0.72rem; color: var(--texto-silenciado); margin-top: 0.2rem;">
                                                             <?= htmlspecialchars($doc['tutores_nombres']) ?>
                                                         </div>
                                                     <?php endif; ?>
                                                 </div>
                                             </div>
                                         </td>
                                         <td style="padding: 0.85rem 0.6rem; text-align: center; vertical-align: middle;">
                                             <span style="background: rgba(255,255,255,0.75); padding: 0.25rem 0.55rem; border-radius: 6px; font-weight: 800; font-size: 0.84rem; color: var(--texto-titulos); border: 1px solid rgba(169, 168, 166, 0.2); display: inline-block;"><?= $doc['anio_publicacion'] ?></span>
                                             <div style="font-size: 0.7rem; color: var(--texto-silenciado); margin-top: 0.25rem; font-weight: 600;">
                                                 <i class="ph ph-eye"></i> <?= (int)($doc['vistas'] ?? 0) ?>
                                             </div>
                                         </td>
                                         <td style="text-align: center; padding: 0.85rem 0.8rem; border-radius: 0 8px 8px 0; vertical-align: middle;">
                                             <div class="action-links" style="display: flex; gap: 0.3rem; justify-content: center; flex-wrap: wrap;">
                                                 <!-- Ver Ficha -->
                                                 <button type="button" class="btn-action-edit" style="background: rgba(112, 144, 203, 0.12); color: var(--color-terciario); border: 1px solid rgba(112, 144, 203, 0.25); border-radius: 6px; padding: 0.32rem 0.55rem; font-size: 0.75rem; font-weight: 700; cursor: pointer;" title="Ver ficha completa" onclick="abrirModalPrevisualizarFichaAdmin(<?= htmlspecialchars(json_encode($doc)) ?>)">
                                                     <i class="ph ph-eye"></i> Ficha
                                                 </button>

                                                 <!-- Abrir Documento Adjunto -->
                                                 <?php if (!empty($doc['archivo_pdf']) && !empty($doc['archivo_existe'])): ?>
                                                     <a href="?ruta=ver-pdf-pst&id=<?= $doc['id'] ?>" target="_blank" class="btn-action-edit" style="background: rgba(80, 89, 132, 0.1); color: var(--color-secundario); border: 1px solid rgba(80, 89, 132, 0.25); border-radius: 6px; padding: 0.32rem 0.55rem; font-size: 0.75rem; font-weight: 700; text-decoration: none;" title="Abrir documento digital">
                                                         <i class="ph ph-file-text"></i> Archivo
                                                     </a>
                                                 <?php endif; ?>

                                                 <?php if (Auth::requierePrivilegioMinimo($nivelAdminPst, 'editar', 'RepositorioPST', false)): ?>
                                                     <!-- Edición Rápida -->
                                                     <button type="button" class="btn-action-edit" style="background: rgba(80, 89, 132, 0.1); color: var(--color-secundario); border: 1px solid rgba(80, 89, 132, 0.22); border-radius: 6px; padding: 0.32rem 0.55rem; font-size: 0.75rem; font-weight: 700; cursor: pointer;" title="Edición rápida sin salir de la tabla" onclick="abrirModalEdicionRapidaPst(<?= htmlspecialchars(json_encode($doc)) ?>)">
                                                         <i class="ph ph-sliders-horizontal"></i> Rápido
                                                     </button>

                                                     <!-- Alternar Visibilidad -->
                                                     <a href="?ruta=agregar-documento&accion=toggle_estado&id=<?= $doc['id'] ?>" class="btn-action-edit" style="background: rgba(80, 89, 132, 0.08); color: var(--color-secundario); border: 1px solid rgba(80, 89, 132, 0.2); border-radius: 6px; padding: 0.32rem 0.55rem; font-size: 0.75rem; font-weight: 700; text-decoration: none;" title="<?= ($doc['activo'] ?? true) ? 'Ocultar del catálogo público' : 'Hacer visible en el catálogo público' ?>">
                                                         <i class="ph <?= ($doc['activo'] ?? true) ? 'ph-eye-slash' : 'ph-eye' ?>"></i> <?= ($doc['activo'] ?? true) ? 'Ocultar' : 'Mostrar' ?>
                                                     </a>

                                                     <!-- Editar Completo -->
                                                     <a href="?ruta=agregar-documento&accion=editar&id=<?= $doc['id'] ?>" class="btn-action-edit" style="background: rgba(80, 89, 132, 0.12); color: var(--color-secundario); border: 1px solid rgba(80, 89, 132, 0.25); border-radius: 6px; padding: 0.32rem 0.55rem; font-size: 0.75rem; font-weight: 700; text-decoration: none;" title="Abrir formulario completo">
                                                         <i class="ph ph-pencil-simple"></i> Editar
                                                     </a>
                                                 <?php endif; ?>

                                                 <?php if (Auth::requierePrivilegioMinimo($nivelAdminPst, 'eliminar', 'RepositorioPST', false)): ?>
                                                     <a href="javascript:void(0)" class="btn-action-delete" style="background: rgba(30, 41, 59, 0.08); color: var(--texto-titulos); border: 1px solid rgba(30, 41, 59, 0.2); border-radius: 6px; padding: 0.32rem 0.55rem; font-size: 0.75rem; font-weight: 700; text-decoration: none;" title="Eliminar este proyecto" onclick="confirmarEliminacionModal('?ruta=agregar-documento&accion=eliminar&id=<?= $doc['id'] ?>')">
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
                    <div class="pst-pagination" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; margin-top: 1rem; padding-top: 0.75rem;">
                        <span style="font-size: 0.82rem; font-weight: 700; color: var(--texto-silenciado, #64748B);">
                            Página <?= $pagination['current_page'] ?> de <?= $pagination['total_pages'] ?> • <?= $pagination['total_items'] ?> proyectos encontrados
                        </span>

                        <div style="display: flex; gap: 0.35rem; align-items: center; flex-wrap: wrap;">
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
                            
                            <?php 
                            $rangoInicio = max(1, $curr - 2);
                            $rangoFin = min($tot, $curr + 2);
                            if ($rangoInicio > 1): ?>
                                <a href="<?= $build_url(1) ?>" class="page-link">1</a>
                                <?php if ($rangoInicio > 2): ?><span class="page-link disabled">...</span><?php endif; ?>
                            <?php endif; ?>

                            <?php for ($i = $rangoInicio; $i <= $rangoFin; $i++): ?>
                                <?php if ($i == $curr): ?>
                                    <span class="page-link active"><?= $i ?></span>
                                <?php else: ?>
                                    <a href="<?= $build_url($i) ?>" class="page-link"><?= $i ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($rangoFin < $tot): ?>
                                <?php if ($rangoFin < $tot - 1): ?><span class="page-link disabled">...</span><?php endif; ?>
                                <a href="<?= $build_url($tot) ?>" class="page-link"><?= $tot ?></a>
                            <?php endif; ?>
                            
                            <?php if ($curr < $tot): ?>
                                <a href="<?= $build_url($curr + 1) ?>" class="page-link">Siguiente &raquo;</a>
                            <?php else: ?>
                                <span class="page-link disabled">Siguiente &raquo;</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </section>

            <!-- BARRA FLOTANTE PARA ACCIONES EN LOTE -->
            <div id="barraAccionesLotePst" style="display: none; position: fixed; bottom: 1.5rem; left: 50%; transform: translateX(-50%); background: var(--bg-card, #ffffff); border: 1px solid rgba(80, 89, 132, 0.3); border-radius: 10px; padding: 0.75rem 1.25rem; box-shadow: 0 12px 35px rgba(18, 26, 62, 0.18); z-index: 9990; align-items: center; gap: 1rem; flex-wrap: wrap;">
                <span style="font-size: 0.85rem; font-weight: 800; color: var(--texto-titulos);">
                    <i class="ph ph-check-square" style="color: var(--color-secundario);"></i>
                    <span id="contadorSeleccionadosLote">0</span> seleccionados
                </span>
                <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                    <button type="button" onclick="ejecutarAccionMasivaTabla('activar')" class="btn-save" style="padding: 0.4rem 0.85rem; font-size: 0.78rem;">
                        <i class="ph ph-eye"></i> Mostrar
                    </button>
                    <button type="button" onclick="ejecutarAccionMasivaTabla('ocultar')" class="btn-cancel" style="padding: 0.4rem 0.85rem; font-size: 0.78rem;">
                        <i class="ph ph-eye-slash"></i> Ocultar
                    </button>
                    <?php if (Auth::requierePrivilegioMinimo($nivelAdminPst, 'eliminar', 'RepositorioPST', false)): ?>
                        <button type="button" onclick="confirmarEliminarLoteTabla()" class="btn-clear" style="padding: 0.4rem 0.85rem; font-size: 0.78rem;">
                            <i class="ph ph-trash"></i> Eliminar
                        </button>
                    <?php endif; ?>
                    <button type="button" onclick="limpiarSeleccionTablaPst()" class="btn-cancel" style="padding: 0.4rem 0.75rem; font-size: 0.78rem; background: transparent;">
                        Desmarcar
                    </button>
                </div>
            </div>

        <?php endif; ?>

    </div>
</div>

</div>

<!-- Modal de Carga / Extracción de Datos -->
<div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center; color: white;">
    <div style="background: var(--bg-card, #ffffff); color: var(--texto-comun, #333); padding: 2.2rem; border-radius: var(--radius-lg, 16px); max-width: 480px; width: 90%; text-align: center; box-shadow: 0 12px 35px rgba(0,0,0,0.2); border: 1px solid rgba(80, 89, 132, 0.2);">
        <div style="font-size: 3rem; color: var(--color-secundario); margin-bottom: 1rem; animation: pulse-loader 1.5s infinite ease-in-out;">
            <i class="ph-bold ph-cpu"></i>
        </div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--texto-titulos); margin-bottom: 0.5rem;" id="loaderTitle">Procesando Archivo</h3>
        <p style="font-size: 0.85rem; color: var(--texto-silenciado); margin-bottom: 1.5rem;" id="loaderText">Extrayendo texto y analizando metadatos del proyecto...</p>
        
        <!-- Barra de Progreso -->
        <div style="width: 100%; height: 8px; background-color: rgba(80, 89, 132, 0.12); border-radius: 4px; overflow: hidden; margin-bottom: 0.5rem;">
            <div id="progressBar" style="width: 0%; height: 100%; background-color: var(--color-secundario); border-radius: 4px; transition: width 0.2s ease-out;"></div>
        </div>
        <div id="progressPercent" style="font-size: 0.85rem; font-weight: 700; color: var(--color-secundario); text-align: right;">0%</div>
    </div>
</div>

<script>
// Límite de tamaño de archivo sincronizado con la configuración del sistema y límites del servidor
const MAX_FILE_SIZE_MB = <?= json_encode($maxMbEfectivo) ?>;
const MAX_FILE_SIZE_BYTES = Math.floor(MAX_FILE_SIZE_MB * 1024 * 1024);
const MAX_ARCHIVOS_LOTE = <?= json_encode($maxArchivosLote) ?>;

// JSON con todas las dimensiones operativas del sistema para el filtrado dinámico
const todasDimensiones = <?= json_encode($lineas && $dimensiones ? $dimensiones : []) ?>;
const activeDimensionId = <?= json_encode($_POST['dimension_id'] ?? $documento['dimension_id'] ?? '') ?>;

// Estado global de la cola de documentos por lotes y despachador secuencial
let documentosEnCola = [];
let documentoSeleccionadoIndex = -1;
let extraccionEnCurso = false;

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

const nivelesConTrayecto = <?= json_encode($nivelesConTrayecto ?? ['Pregrado']) ?>;
function toggleTrayectoByNivel() {
    const nivelSelect = document.getElementById('nivel_academico');
    const trayectoContainer = document.getElementById('container_trayecto');
    const trayectoSelect = document.getElementById('trayecto');
    if (!nivelSelect || !trayectoContainer) return;

    if (nivelesConTrayecto.includes(nivelSelect.value)) {
        trayectoContainer.style.display = 'block';
        if (trayectoSelect && !trayectoSelect.value) trayectoSelect.value = 'Trayecto I';
    } else {
        trayectoContainer.style.display = 'none';
        if (trayectoSelect) trayectoSelect.value = '';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    toggleTrayectoByNivel();
    const trayectoSelect = document.getElementById('trayecto');
    if (trayectoSelect) {
        syncIdTrayecto(trayectoSelect);
    }
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

    // Interceptar envío tradicional del formulario para garantizar que exista documento cargado y no sea duplicado
    const formSubidaPst = document.getElementById('formSubidaPst');
    if (formSubidaPst) {
        formSubidaPst.addEventListener('submit', (e) => {
            const esCrear = <?= json_encode($accion === 'crear') ?>;
            if (esCrear) {
                const pdfHidden = document.getElementById('archivo_pdf_hidden') ? document.getElementById('archivo_pdf_hidden').value.trim() : '';
                const fileInput = document.getElementById('input_archivo_extractor');
                const tieneArchivoInput = (fileInput && fileInput.files && fileInput.files.length > 0);

                if (!pdfHidden && !tieneArchivoInput) {
                    e.preventDefault();
                    mostrarModalAlerta('warning', 'Documento Requerido', 'Debe cargar y extraer un documento digital (PDF o Word) válido antes de registrar el proyecto.');
                    return false;
                }

                const alertaEl = document.getElementById('alerta_duplicado_titulo');
                if (alertaEl && alertaEl.style.display !== 'none') {
                    e.preventDefault();
                    mostrarModalAlerta('warning', 'Título Duplicado', 'Ya existe un proyecto registrado con este título en la base de datos. Por favor modifique el título antes de enviar el formulario.');
                    return false;
                }
            }
        });
    }

    // Verificación en tiempo real de duplicidad de título al escribir
    const tituloInput = document.getElementById('titulo');
    if (tituloInput) {
        let timerTitulo = null;
        tituloInput.addEventListener('input', () => {
            clearTimeout(timerTitulo);
            timerTitulo = setTimeout(async () => {
                const titVal = tituloInput.value.trim();
                const alertaEl = document.getElementById('alerta_duplicado_titulo');
                if (titVal.length >= 5) {
                    try {
                        const resp = await fetch('?ruta=agregar-documento&accion=verificar_titulo&titulo=' + encodeURIComponent(titVal));
                        const checkData = await resp.json();
                        if (checkData.status === 'success' && checkData.existe) {
                            if (alertaEl) alertaEl.style.display = 'block';
                            if (documentoSeleccionadoIndex >= 0 && documentosEnCola[documentoSeleccionadoIndex]) {
                                documentosEnCola[documentoSeleccionadoIndex].advertencia = 'Ya existe un proyecto registrado con este título en el repositorio.';
                                renderizarColaUI();
                            }
                        } else {
                            if (alertaEl) alertaEl.style.display = 'none';
                            if (documentoSeleccionadoIndex >= 0 && documentosEnCola[documentoSeleccionadoIndex]) {
                                documentosEnCola[documentoSeleccionadoIndex].advertencia = '';
                                if (documentosEnCola[documentoSeleccionadoIndex].estado === 'error' && documentosEnCola[documentoSeleccionadoIndex].data.archivo_pdf) {
                                    documentosEnCola[documentoSeleccionadoIndex].estado = 'listo';
                                    documentosEnCola[documentoSeleccionadoIndex].errorMsg = '';
                                }
                                renderizarColaUI();
                            }
                        }
                    } catch (e) {
                        console.error('Error al verificar título:', e);
                    }
                } else {
                    if (alertaEl) alertaEl.style.display = 'none';
                }
            }, 400);
        });
    }
});

function procesarArchivosSeleccionados(fileList) {
    const esEditar = <?= json_encode($accion === 'editar') ?>;
    const permitirFiltroCarrera = <?= json_encode((bool)ConfigService::get('buscador.permitir_filtro_carrera', false)) ?>;
    let files = Array.from(fileList);
    
    if (esEditar && files.length > 1) {
        files = [files[0]];
    }

    // 1. Filtrar extensiones no admitidas
    const invalidFormatFiles = files.filter(f => {
        const ext = f.name.split('.').pop().toLowerCase();
        return ext !== 'pdf' && ext !== 'docx' && ext !== 'doc';
    });

    // 2. Filtrar archivos que exceden el tamaño máximo por archivo
    const oversizedFiles = files.filter(f => {
        const ext = f.name.split('.').pop().toLowerCase();
        return (ext === 'pdf' || ext === 'docx' || ext === 'doc') && f.size > MAX_FILE_SIZE_BYTES;
    });

    // 3. Archivos válidos que cumplen formato y tamaño
    let validFiles = files.filter(f => {
        const ext = f.name.split('.').pop().toLowerCase();
        return (ext === 'pdf' || ext === 'docx' || ext === 'doc') && f.size <= MAX_FILE_SIZE_BYTES;
    });

    // 4. Validar límite de archivos por lote (MAX_ARCHIVOS_LOTE)
    let batchLimitFiles = [];
    if (!esEditar && typeof MAX_ARCHIVOS_LOTE === 'number' && MAX_ARCHIVOS_LOTE > 0) {
        const cuposDisponibles = Math.max(0, MAX_ARCHIVOS_LOTE - documentosEnCola.length);
        if (cuposDisponibles <= 0) {
            mostrarModalAlerta(
                'warning',
                'Límite de Lote Alcanzado',
                `La cola ya contiene el límite máximo permitido de ${MAX_ARCHIVOS_LOTE} documentos.\n\nProcese o elimine documentos existentes en la cola antes de agregar nuevos archivos.`
            );
            return;
        }

        if (validFiles.length > cuposDisponibles) {
            batchLimitFiles = validFiles.slice(cuposDisponibles);
            validFiles = validFiles.slice(0, cuposDisponibles);
        }
    }

    // Notificar al usuario si hubo archivos rechazados u omitidos por cupo, sin descartar los válidos
    const avisosRechazo = [];
    if (invalidFormatFiles.length > 0) {
        const nombresInv = invalidFormatFiles.map(f => f.name).join(', ');
        avisosRechazo.push(`Formato no admitido (solo .pdf, .docx y .doc):\n• ${nombresInv}`);
    }
    if (oversizedFiles.length > 0) {
        const detallesOversized = oversizedFiles.map(f => `• ${f.name} (${(f.size / (1024 * 1024)).toFixed(2)} MB)`).join('\n');
        avisosRechazo.push(`Excede(n) el tamaño máximo permitido (${MAX_FILE_SIZE_MB} MB):\n${detallesOversized}`);
    }
    if (batchLimitFiles.length > 0) {
        const nombresLimit = batchLimitFiles.map(f => f.name).join(', ');
        avisosRechazo.push(`Excede(n) el cupo máximo por lote (${MAX_ARCHIVOS_LOTE} archivos máx. en cola):\n• ${nombresLimit}`);
    }

    if (avisosRechazo.length > 0) {
        if (validFiles.length > 0) {
            mostrarModalAlerta(
                'warning',
                'Aviso de Carga por Lotes',
                `Se procesarán ${validFiles.length} archivo(s) válido(s).\n\nLos siguientes archivos fueron omitidos:\n\n${avisosRechazo.join('\n\n')}`
            );
        } else {
            mostrarModalAlerta(
                'warning',
                'Archivos Rechazados',
                `Ninguno de los archivos seleccionados pudo ser cargado:\n\n${avisosRechazo.join('\n\n')}`
            );
            return;
        }
    }

    if (validFiles.length === 0) {
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

        const badge = document.getElementById('badgeArchivoSustituido');
        const badgeText = document.getElementById('nombreArchivoSustituidoText');
        if (badge && badgeText) {
            badgeText.textContent = file.name;
            badge.style.display = 'block';
        }

        mostrarModalAlerta('success', 'Archivo Listo para Sustituir', `El archivo "${file.name}" reemplazará al documento actual al presionar el botón "Guardar Cambios".`);
        return;
    }

    const carreraSelectForm = document.getElementById('id_carrera');
    const carreraValActual = carreraSelectForm ? carreraSelectForm.value : '';

    if (permitirFiltroCarrera && !carreraValActual) {
        archivosPendientesExtraccion = validFiles;
        const modal = document.getElementById('modalSeleccionCarreraPst');
        if (modal) modal.style.display = 'flex';
        return;
    }

    iniciarProcesamientoListaArchivos(validFiles, carreraValActual || 1);
}

function iniciarProcesamientoListaArchivos(validFiles, carreraId) {
    validFiles.forEach(file => {
        const docObj = {
            id: Date.now() + '_' + Math.random().toString(36).substr(2, 5),
            nombreArchivo: file.name,
            file: file,
            estado: 'pendiente',
            errorMsg: '',
            progresoPct: 0,
            faseMsg: 'En cola de espera...',
            xhr: null,
            data: {
                titulo: '',
                id_carrera: carreraId,
                anio_publicacion: new Date().getFullYear(),
                fecha_defensa: '',
                fecha_precision: '',
                fecha_texto_detectado: '',
                nivel_academico: 'Pregrado',
                trayecto: 'Trayecto I',
                id_trayecto: '',
                url_repositorio: '',
                resumen: '',
                obj_general: '',
                palabras_clave: '',
                comunidad_beneficiada: '',
                linea_id: '',
                dimension_id: '',
                archivo_pdf: '',
                autores: [],
                tutor_academico_cedula: '',
                tutor_academico_nombre: '',
                tutor_institucional_cedula: '',
                tutor_institucional_nombre: '',
                tutor_comunitario_cedula: '',
                tutor_comunitario_nombre: ''
            }
        };
        documentosEnCola.push(docObj);
    });

    // Resetear valor de input file para permitir re-seleccionar los mismos archivos si es necesario
    const fileInput = document.getElementById('input_archivo_extractor');
    if (fileInput) fileInput.value = '';

    renderizarColaUI();

    // Iniciar procesamiento secuencial de la cola (concurrencia controlada = 1)
    procesarSiguienteEnCola();
}

function procesarSiguienteEnCola() {
    if (extraccionEnCurso) return;

    const siguienteIdx = documentosEnCola.findIndex(d => d.estado === 'pendiente');
    if (siguienteIdx === -1) {
        return;
    }

    extraccionEnCurso = true;
    subirYExtraerDatos(documentosEnCola[siguienteIdx], siguienteIdx);
}

function subirYExtraerDatos(docItem, index) {
    docItem.estado = 'extrayendo';
    docItem.progresoPct = 0;
    docItem.faseMsg = 'Subiendo al servidor...';
    renderizarColaUI();

    const CHUNK_SIZE = 8 * 1024 * 1024; // 8 MB por fragmento (evita límites post_max_size/upload_max_filesize de 64M en PHP)
    const file = docItem.file;
    const totalSize = file.size;

    const finalizarConRespuesta = function(response) {
        if (response.status === 'success') {
            docItem.estado = 'listo';
            docItem.errorMsg = '';
            docItem.progresoPct = 100;
            docItem.faseMsg = 'Listo';
            if (response.data) {
                Object.assign(docItem.data, response.data);
                if (response.data.ya_existe_en_bd) {
                    docItem.advertencia = 'Ya existe un proyecto registrado con este título en el repositorio. Modifique el título antes de registrarlo.';
                } else {
                    docItem.advertencia = '';
                }
            }
            try {
                if (documentoSeleccionadoIndex === -1) {
                    seleccionarDocumentoDeCola(index);
                } else if (documentoSeleccionadoIndex === index) {
                    rellenarFormulario(docItem.data);
                }
            } catch (renderErr) {
                console.error("Error al rellenar formulario:", renderErr);
            }
        } else {
            docItem.estado = 'error';
            docItem.errorMsg = response.message || 'Error en la extracción de metadatos.';
            docItem.data.titulo = '';
            docItem.data.archivo_pdf = '';
            if (documentoSeleccionadoIndex === index) {
                limpiarCamposFormularioSilencioso();
            }
        }
        renderizarColaUI();
        extraccionEnCurso = false;
        procesarSiguienteEnCola();
    };

    const fallarSubida = function(msg) {
        docItem.xhr = null;
        docItem.estado = 'error';
        docItem.errorMsg = msg;
        docItem.data.titulo = '';
        docItem.data.archivo_pdf = '';
        if (documentoSeleccionadoIndex === index) {
            limpiarCamposFormularioSilencioso();
        }
        renderizarColaUI();
        extraccionEnCurso = false;
        procesarSiguienteEnCola();
    };

    if (totalSize > CHUNK_SIZE) {
        const totalChunks = Math.ceil(totalSize / CHUNK_SIZE);
        const uploadId = docItem.id + '_' + Date.now();
        let currentChunk = 0;

        const enviarSiguienteChunk = function() {
            const start = currentChunk * CHUNK_SIZE;
            const end = Math.min(totalSize, start + CHUNK_SIZE);
            const blobChunk = file.slice(start, end);

            const formData = new FormData();
            formData.append('archivo_pst', blobChunk, file.name);
            formData.append('chunk_index', currentChunk);
            formData.append('total_chunks', totalChunks);
            formData.append('upload_id', uploadId);
            formData.append('original_name', file.name);
            formData.append('total_size', totalSize);
            formData.append('csrf_token', window.CSRF_TOKEN || '');

            const xhr = new XMLHttpRequest();
            docItem.xhr = xhr;
            xhr.open('POST', '?ruta=agregar-documento&accion=extraer', true);
            xhr.setRequestHeader('X-CSRF-Token', window.CSRF_TOKEN || '');

            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    const bytesSubidos = start + e.loaded;
                    const pct = Math.min(99, Math.round((bytesSubidos / totalSize) * 100));
                    docItem.progresoPct = pct;
                    if (currentChunk + 1 === totalChunks && e.loaded >= e.total) {
                        docItem.faseMsg = 'Analizando texto...';
                    } else {
                        docItem.faseMsg = `Subiendo (${pct}%)...`;
                    }
                    renderizarColaUI();
                }
            };

            xhr.onload = function() {
                docItem.xhr = null;
                if (xhr.status !== 200) {
                    fallarSubida('Error en el servidor (' + xhr.status + ').');
                    return;
                }
                let response;
                try {
                    response = JSON.parse(xhr.responseText);
                } catch (jsonErr) {
                    console.error("Error parseando respuesta JSON del servidor:", jsonErr, xhr.responseText);
                    fallarSubida('Respuesta inválida del servidor.');
                    return;
                }

                if (response.status === 'chunk_ok') {
                    currentChunk++;
                    if (currentChunk < totalChunks) {
                        enviarSiguienteChunk();
                    }
                } else {
                    finalizarConRespuesta(response);
                }
            };

            xhr.onerror = function() {
                fallarSubida('Error de conexión de red durante la subida por fragmentos.');
            };

            xhr.send(formData);
        };

        enviarSiguienteChunk();
        return;
    }

    const formData = new FormData();
    formData.append('archivo_pst', file);
    formData.append('csrf_token', window.CSRF_TOKEN || '');

    const xhr = new XMLHttpRequest();
    docItem.xhr = xhr;
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
        docItem.xhr = null;
        if (xhr.status === 200) {
            let response;
            try {
                response = JSON.parse(xhr.responseText);
            } catch (jsonErr) {
                console.error("Error parseando respuesta JSON del servidor:", jsonErr, xhr.responseText);
                fallarSubida('Respuesta inválida del servidor.');
                return;
            }
            finalizarConRespuesta(response);
        } else {
            fallarSubida('Error en el servidor (' + xhr.status + ').');
        }
    };

    xhr.onerror = function() {
        fallarSubida('Error de conexión de red.');
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
            if (item.advertencia) {
                statusBadge = `<span style="font-size: 0.7rem; padding: 0.15rem 0.4rem; border-radius: 3px; background-color: rgba(245, 158, 11, 0.15); color: #b45309; font-weight: 700;"><i class="ph ph-warning"></i> Duplicado</span>`;
            } else {
                statusBadge = `<span style="font-size: 0.7rem; padding: 0.15rem 0.4rem; border-radius: 3px; background-color: rgba(80, 89, 132, 0.1); color: var(--color-secundario); font-weight: 700;"><i class="ph ph-check-circle"></i> Listo</span>`;
            }
        } else if (item.estado === 'subiendo') {
            statusBadge = `<span style="font-size: 0.7rem; padding: 0.15rem 0.4rem; border-radius: 3px; background-color: rgba(112, 144, 203, 0.15); color: var(--color-terciario); font-weight: 700;"><i class="ph ph-cloud-arrow-up spin"></i> Subiendo...</span>`;
        } else if (item.estado === 'exito') {
            statusBadge = `<span style="font-size: 0.7rem; padding: 0.15rem 0.4rem; border-radius: 3px; background-color: rgba(80, 89, 132, 0.1); color: var(--color-secundario); font-weight: 700;"><i class="ph ph-check-circle"></i> Subido</span>`;
        } else if (item.estado === 'error') {
            statusBadge = `<span style="font-size: 0.7rem; padding: 0.15rem 0.4rem; border-radius: 3px; background-color: rgba(239, 68, 68, 0.12); color: #dc2626; font-weight: 700;"><i class="ph ph-warning-circle"></i> Error</span>`;
        }

        const titleText = (item.estado === 'listo' || item.estado === 'exito' || (item.data && item.data.titulo)) 
            ? (item.data && item.data.titulo ? item.data.titulo : item.nombreArchivo) 
            : (item.estado === 'error' ? 'Extracción fallida' : 'Analizando documento...');

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
        } else if (item.advertencia) {
            content += `
                <div style="margin-top: 0.4rem; margin-bottom: 0; padding: 0.35rem 0.5rem; font-size: 0.7rem; word-break: break-word; border-radius: 3px; background-color: #fffbeb; border: 1px solid #fde68a; color: #92400e;">
                    <i class="ph ph-warning"></i> ${escapeHtml(item.advertencia)}
                </div>
            `;
        }

        content += `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.4rem; padding-top: 0.3rem; border-top: 1px dashed rgba(169, 168, 166, 0.15);">
                ${(item.estado === 'listo' || (item.estado === 'error' && item.data && item.data.archivo_pdf)) ? `
                    <button type="button" class="btn-action-edit" style="font-size: 0.7rem; padding: 0.15rem 0.4rem;" onclick="event.stopPropagation(); seleccionarDocumentoDeCola(${idx});">
                        <i class="ph ph-pencil-simple"></i> Revisar / Editar
                    </button>
                ` : `
                    <span style="font-size: 0.72rem; color: var(--texto-silenciado);">
                        ${item.estado === 'error' ? '<i class=\"ph ph-x-circle\" style=\"color:#dc2626;\"></i> No procesable' : '<i class=\"ph ph-spinner spin\"></i> En proceso'}
                    </span>
                `}
                <button type="button" class="btn-action-delete" style="font-size: 0.7rem; padding: 0.15rem 0.4rem;" onclick="event.stopPropagation(); eliminarDocumentoDeCola(${idx});">
                    <i class="ph ph-trash"></i> Descartar
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

    const docItem = documentosEnCola[index];
    if (docItem.estado === 'extrayendo' || docItem.estado === 'pendiente') {
        mostrarModalAlerta('warning', 'Extracción en Proceso', `El archivo "${docItem.nombreArchivo}" aún se está procesando. Espere a que finalice la extracción.`);
        return;
    }

    if (docItem.estado === 'error' && (!docItem.data || !docItem.data.archivo_pdf)) {
        mostrarModalAlerta('warning', 'Documento no Procesado', `El archivo "${docItem.nombreArchivo}" no pudo ser procesado:\n\n${docItem.errorMsg || 'Error desconocido'}.\n\nElimine este archivo de la cola o cargue una versión que cumpla con los requisitos.`);
        return;
    }

    if (documentoSeleccionadoIndex >= 0 && documentoSeleccionadoIndex < documentosEnCola.length && documentoSeleccionadoIndex !== index) {
        guardarDatosFormularioEnArray(documentoSeleccionadoIndex);
    }

    documentoSeleccionadoIndex = index;
    rellenarFormulario(docItem.data);
    renderizarColaUI();

    if (docItem.estado === 'error' && docItem.errorMsg) {
        mostrarModalAlerta('warning', 'Documento con Observación', `El documento "${docItem.nombreArchivo}" presentó la siguiente observación:\n\n${docItem.errorMsg}\n\nPuede modificar los datos en el formulario y pulsar "Guardar en Borrador" para prepararlo.`);
    } else if (docItem.advertencia) {
        mostrarModalAlerta('warning', 'Título Duplicado', `Atención: ${docItem.advertencia}`);
    }
}

function guardarDatosFormularioEnArray(index) {
    if (index < 0 || index >= documentosEnCola.length) return;
    const currentData = obtenerDatosFormularioActual();
    Object.assign(documentosEnCola[index].data, currentData);
}

async function guardarBorradorEnCola() {
    if (documentoSeleccionadoIndex < 0 || documentoSeleccionadoIndex >= documentosEnCola.length) {
        mostrarModalAlerta('warning', 'Selección Requerida', 'Seleccione un documento de la cola para guardar sus cambios en borrador.');
        return;
    }
    guardarDatosFormularioEnArray(documentoSeleccionadoIndex);
    const item = documentosEnCola[documentoSeleccionadoIndex];

    // Verificar si el título ya existe en la base de datos
    if (item.data && item.data.titulo) {
        try {
            const resp = await fetch('?ruta=agregar-documento&accion=verificar_titulo&titulo=' + encodeURIComponent(item.data.titulo));
            const checkData = await resp.json();
            if (checkData.status === 'success' && checkData.existe) {
                item.advertencia = 'Ya existe un proyecto registrado con este título en el repositorio. Modifique el título antes de registrarlo.';
                const alertaEl = document.getElementById('alerta_duplicado_titulo');
                if (alertaEl) alertaEl.style.display = 'block';
            } else {
                item.advertencia = '';
                const alertaEl = document.getElementById('alerta_duplicado_titulo');
                if (alertaEl) alertaEl.style.display = 'none';
                if (item.estado === 'error' && item.data.archivo_pdf) {
                    item.estado = 'listo';
                    item.errorMsg = '';
                }
            }
        } catch (e) {
            console.error('Error al verificar título:', e);
        }
    }

    if (item.estado === 'error' && item.data && item.data.archivo_pdf && !item.advertencia) {
        item.estado = 'listo';
        item.errorMsg = '';
    }

    renderizarColaUI();

    const nombre = item.nombreArchivo;
    if (item.advertencia) {
        mostrarModalAlerta('warning', 'Borrador Guardado con Advertencia', `Borrador guardado para "${nombre}". Sin embargo:\n\n${item.advertencia}`);
    } else {
        mostrarModalAlerta('success', 'Borrador Guardado', `Borrador actualizado en cola para el archivo "${nombre}".`);
    }
}

function sincronizarCedulasPST() {
    // Autores
    const autorRows = document.querySelectorAll('.authors-container .grid-2-cols');
    autorRows.forEach(row => {
        const tipoEl = row.querySelector('.autor-cedula-tipo');
        const numEl = row.querySelector('.autor-cedula-num');
        const hiddenEl = row.querySelector('.autor-cedula-hidden');
        if (tipoEl && numEl && hiddenEl) {
            const num = numEl.value.trim().replace(/\D/g, '');
            hiddenEl.value = num ? (tipoEl.value + num) : '';
        }
    });

    // Tutores
    const tutorPairs = [
        ['tutor_academico_tipo', 'tutor_academico_num', 'tutor_academico_cedula'],
        ['tutor_institucional_tipo', 'tutor_institucional_num', 'tutor_institucional_cedula'],
        ['tutor_comunitario_tipo', 'tutor_comunitario_num', 'tutor_comunitario_cedula']
    ];
    tutorPairs.forEach(([tId, nId, hId]) => {
        const tEl = document.getElementById(tId);
        const nEl = document.getElementById(nId);
        const hEl = document.getElementById(hId);
        if (tEl && nEl && hEl) {
            const num = nEl.value.trim().replace(/\D/g, '');
            hEl.value = num ? (tEl.value + num) : '';
        }
    });
}

function obtenerDatosFormularioActual() {
    sincronizarCedulasPST();
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
        id_carrera: document.getElementById('id_carrera') && document.getElementById('id_carrera').value ? document.getElementById('id_carrera').value : (document.getElementById('carrera_lote_master') ? document.getElementById('carrera_lote_master').value : 1),
        anio_publicacion: document.getElementById('anio_publicacion') ? document.getElementById('anio_publicacion').value : new Date().getFullYear(),
        fecha_defensa: document.getElementById('fecha_defensa') ? document.getElementById('fecha_defensa').value : '',
        fecha_precision: (documentoSeleccionadoIndex >= 0 && documentosEnCola[documentoSeleccionadoIndex] && documentosEnCola[documentoSeleccionadoIndex].data) ? (documentosEnCola[documentoSeleccionadoIndex].data.fecha_precision || '') : '',
        fecha_texto_detectado: (documentoSeleccionadoIndex >= 0 && documentosEnCola[documentoSeleccionadoIndex] && documentosEnCola[documentoSeleccionadoIndex].data) ? (documentosEnCola[documentoSeleccionadoIndex].data.fecha_texto_detectado || '') : '',
        nivel_academico: document.getElementById('nivel_academico') ? document.getElementById('nivel_academico').value : 'Pregrado',
        trayecto: document.getElementById('trayecto') ? document.getElementById('trayecto').value : 'Trayecto I',
        id_trayecto: document.getElementById('id_trayecto') ? document.getElementById('id_trayecto').value : '',
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
        tutor_comunitario_nombre: document.getElementsByName('tutor_comunitario_nombre')[0] ? document.getElementsByName('tutor_comunitario_nombre')[0].value.trim() : '',
        carreras_vinculadas: Array.from(document.querySelectorAll('.check-carrera-vinculada:checked'))
            .filter(chk => !chk.disabled && chk.value)
            .map(chk => parseInt(chk.value, 10))
    };
}

function eliminarDocumentoDeCola(index) {
    if (index < 0 || index >= documentosEnCola.length) return;
    
    const eliminado = documentosEnCola[index];
    if (eliminado && eliminado.xhr && typeof eliminado.xhr.abort === 'function') {
        try {
            eliminado.xhr.abort();
        } catch(e) {}
    }
    const eraExtrayendo = (eliminado && eliminado.estado === 'extrayendo');

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
    
    if (eraExtrayendo) {
        extraccionEnCurso = false;
    }

    renderizarColaUI();
    procesarSiguienteEnCola();
}

function limpiarColaDocumentos() {
    if (documentosEnCola.length === 0) return;
    confirmarAccionModal(
        'warning',
        'Vaciar Cola',
        '¿Desea vaciar completamente la cola de documentos extraídos?',
        'Sí, vaciar cola',
        () => {
            documentosEnCola.forEach(doc => {
                if (doc.xhr && typeof doc.xhr.abort === 'function') {
                    try { doc.xhr.abort(); } catch(e) {}
                }
            });
            extraccionEnCurso = false;
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
    const hintFecha = document.getElementById('hint_fecha_defensa');
    if (hintFecha) {
        hintFecha.style.display = 'none';
        hintFecha.textContent = '';
    }
    const hiddenInputs = form.querySelectorAll('input[name="tutor_academico_cedula"], input[name="tutor_institucional_cedula"], input[name="tutor_comunitario_cedula"], .autor-cedula-hidden');
    hiddenInputs.forEach(h => { h.value = ''; });
    const selects = form.querySelectorAll('.autor-cedula-tipo, #tutor_academico_tipo, #tutor_institucional_tipo, #tutor_comunitario_tipo');
    selects.forEach(s => { s.value = 'V-'; });
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

    // Verificar si hay documentos aún en extracción
    const enProceso = documentosEnCola.filter(d => d.estado === 'extrayendo' || d.estado === 'pendiente');
    if (enProceso.length > 0) {
        mostrarModalAlerta('warning', 'Extracción en Proceso', 'Hay documentos que aún se están analizando y extrayendo en el servidor. Espere a que finalice su análisis.');
        return;
    }

    // Solo procesar documentos que estén debidamente extraídos y listos
    const listos = documentosEnCola.filter(d => d.estado === 'listo');
    const conError = documentosEnCola.filter(d => d.estado === 'error');

    if (listos.length === 0) {
        if (conError.length > 0) {
            mostrarModalAlerta('warning', 'Sin Documentos Listos', 'No hay documentos válidos listos para registrar. Los archivos en cola presentan errores de validación o extracción.');
        } else {
            mostrarModalAlerta('success', 'Cola Completada', 'Todos los documentos válidos de la cola ya se han subido con éxito a la base de datos.');
        }
        return;
    }

    // Verificar si algún documento listo tiene advertencia de duplicado
    const conDuplicado = listos.filter(d => d.advertencia);
    if (conDuplicado.length > 0) {
        mostrarModalAlerta('warning', 'Títulos Duplicados Detectados', 'Uno o más documentos en cola tienen títulos que ya existen en la base de datos. Por favor seleccione el documento, modifique su título y presione "Guardar en Borrador" antes de subir el lote.');
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
        if (item.estado !== 'listo') continue;

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
                item.advertencia = '';
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

    if (exitosos === 0 && fallidos === 0) {
        mostrarModalAlerta('warning', 'Sin Documentos Procesados', 'No se procesó ningún documento de la cola.');
        return;
    }

    let msj = `Proceso de subida en lote finalizado.\n\n• Documentos registrados con éxito: ${exitosos}.`;
    if (fallidos > 0) {
        msj += `\n• Documentos con error: ${fallidos}.\nRevise los ítems marcados en la cola para corregirlos.`;
        if (exitosos > 0) {
            confirmarAccionModal(
                'warning',
                'Carga Parcial Finalizada',
                msj + '\n\n¿Desea ir al catálogo para ver los proyectos registrados o permanecer aquí para revisar los que tuvieron error?',
                'Ver Catálogo',
                () => {
                    window.location.href = '?ruta=agregar-documento&msg=created';
                }
            );
        } else {
            mostrarModalAlerta('warning', 'Resumen de Carga', msj);
        }
    } else {
        mostrarModalAlerta('success', 'Carga Completada', msj + '\n\nPresione Aceptar para ver los proyectos registrados en el catálogo.', () => {
            window.location.href = '?ruta=agregar-documento&msg=created';
        });
    }
}

function rellenarFormulario(data) {
    if (!data) return;

    if (data.archivo_pdf !== undefined && document.getElementById('archivo_pdf_hidden')) document.getElementById('archivo_pdf_hidden').value = data.archivo_pdf || '';
    if (data.titulo !== undefined) document.getElementById('titulo').value = data.titulo || '';

    const alertaEl = document.getElementById('alerta_duplicado_titulo');
    if (alertaEl) {
        if (data.ya_existe_en_bd || (documentoSeleccionadoIndex >= 0 && documentosEnCola[documentoSeleccionadoIndex] && documentosEnCola[documentoSeleccionadoIndex].advertencia)) {
            alertaEl.style.display = 'block';
        } else {
            alertaEl.style.display = 'none';
        }
    }
    if (data.anio_publicacion !== undefined) document.getElementById('anio_publicacion').value = data.anio_publicacion || new Date().getFullYear();
    const fechaInput = document.getElementById('fecha_defensa');
    const hintFecha = document.getElementById('hint_fecha_defensa');
    if (fechaInput && data.fecha_defensa !== undefined) {
        fechaInput.value = data.fecha_defensa || '';
    }
    if (hintFecha) {
        const prec = data.fecha_precision || '';
        const txtDet = data.fecha_texto_detectado || '';
        if (prec === 'completa') {
            hintFecha.style.display = 'block';
            hintFecha.style.color = '#166534';
            hintFecha.innerHTML = `<i class="ph ph-check-circle"></i> Fecha exacta detectada${txtDet ? ' (' + txtDet + ')' : ''}.`;
        } else if (prec === 'mes_anio') {
            hintFecha.style.display = 'block';
            hintFecha.style.color = '#b45309';
            hintFecha.innerHTML = `<i class="ph ph-info"></i> Se detectó mes y año${txtDet ? ' (' + txtDet + ')' : ''}; se fijó el día 01 (ajústelo si conoce el día exacto).`;
        } else if (prec === 'solo_anio') {
            hintFecha.style.display = 'block';
            hintFecha.style.color = '#9a3412';
            hintFecha.innerHTML = `<i class="ph ph-warning-circle"></i> El documento solo declara el año (${data.anio_publicacion || ''}); complete el día y mes de defensa.`;
        } else {
            hintFecha.style.display = 'none';
            hintFecha.textContent = '';
        }
    }
    if (data.nivel_academico !== undefined && document.getElementById('nivel_academico')) {
        document.getElementById('nivel_academico').value = data.nivel_academico || 'Pregrado';
        toggleTrayectoByNivel();
    }
    if (data.trayecto !== undefined && document.getElementById('trayecto')) document.getElementById('trayecto').value = data.trayecto || 'Trayecto I';
    if (data.id_trayecto !== undefined && document.getElementById('id_trayecto')) document.getElementById('id_trayecto').value = data.id_trayecto || '';
    if (data.url_repositorio !== undefined && document.getElementById('url_repositorio')) document.getElementById('url_repositorio').value = data.url_repositorio || '';
    if (data.resumen !== undefined) document.getElementById('resumen').value = data.resumen || '';
    if (data.obj_general !== undefined && document.getElementById('obj_general')) document.getElementById('obj_general').value = data.obj_general || '';
    if (data.palabras_clave !== undefined) document.getElementById('palabras_clave').value = data.palabras_clave || '';
    if (data.comunidad_beneficiada !== undefined) document.getElementById('comunidad_beneficiada').value = data.comunidad_beneficiada || '';

    const autorRows = document.querySelectorAll('.authors-container .grid-2-cols');
    const nombreInputs = document.getElementsByName('autor_nombre[]');
    for (let k = 0; k < autorRows.length; k++) {
        const tipoEl = autorRows[k].querySelector('.autor-cedula-tipo');
        const numEl = autorRows[k].querySelector('.autor-cedula-num');
        const hiddenEl = autorRows[k].querySelector('.autor-cedula-hidden');
        if (tipoEl) tipoEl.value = 'V-';
        if (numEl) numEl.value = '';
        if (hiddenEl) hiddenEl.value = '';
        if (nombreInputs[k]) nombreInputs[k].value = '';
    }
    if (data.autores && Array.isArray(data.autores)) {
        data.autores.forEach((autor, idx) => {
            if (idx < autorRows.length) {
                let rawCed = String(autor.cedula || '').trim();
                let tipo = 'V-';
                if (rawCed.toUpperCase().startsWith('E-')) {
                    tipo = 'E-';
                    rawCed = rawCed.substring(2);
                } else if (rawCed.toUpperCase().startsWith('V-')) {
                    tipo = 'V-';
                    rawCed = rawCed.substring(2);
                }
                const num = rawCed.replace(/\D/g, '');
                const tipoEl = autorRows[idx].querySelector('.autor-cedula-tipo');
                const numEl = autorRows[idx].querySelector('.autor-cedula-num');
                const hiddenEl = autorRows[idx].querySelector('.autor-cedula-hidden');
                if (tipoEl) tipoEl.value = tipo;
                if (numEl) numEl.value = num;
                if (hiddenEl) hiddenEl.value = num ? (tipo + num) : '';
                if (nombreInputs[idx]) nombreInputs[idx].value = autor.nombre || autor.nombre_completo || '';
            }
        });
    }

    const setTutorVal = (tId, nId, hId, rawVal) => {
        let rawCed = String(rawVal || '').trim();
        let tipo = 'V-';
        if (rawCed.toUpperCase().startsWith('E-')) {
            tipo = 'E-';
            rawCed = rawCed.substring(2);
        } else if (rawCed.toUpperCase().startsWith('V-')) {
            tipo = 'V-';
            rawCed = rawCed.substring(2);
        }
        const num = rawCed.replace(/\D/g, '');
        const tEl = document.getElementById(tId);
        const nEl = document.getElementById(nId);
        const hEl = document.getElementById(hId);
        if (tEl) tEl.value = tipo;
        if (nEl) nEl.value = num;
        if (hEl) hEl.value = num ? (tipo + num) : '';
    };

    setTutorVal('tutor_academico_tipo', 'tutor_academico_num', 'tutor_academico_cedula', data.tutor_academico_cedula);
    const tAcadNomEl = document.getElementById('tutor_academico_nombre') || document.getElementsByName('tutor_academico_nombre')[0];
    if (tAcadNomEl) tAcadNomEl.value = data.tutor_academico_nombre || '';

    setTutorVal('tutor_institucional_tipo', 'tutor_institucional_num', 'tutor_institucional_cedula', data.tutor_institucional_cedula);
    const tInstNomEl = document.getElementById('tutor_institucional_nombre') || document.getElementsByName('tutor_institucional_nombre')[0];
    if (tInstNomEl) tInstNomEl.value = data.tutor_institucional_nombre || '';

    setTutorVal('tutor_comunitario_tipo', 'tutor_comunitario_num', 'tutor_comunitario_cedula', data.tutor_comunitario_cedula);
    const tComNomEl = document.getElementById('tutor_comunitario_nombre') || document.getElementsByName('tutor_comunitario_nombre')[0];
    if (tComNomEl) tComNomEl.value = data.tutor_comunitario_nombre || '';

    const carreraSelect = document.getElementById('id_carrera');
    const targetCarreraId = data.id_carrera || (carreraSelect ? carreraSelect.value : 1);
    if (carreraSelect) {
        carreraSelect.value = targetCarreraId;
    }

    // Sincronizar carreras vinculadas (etiquetas intercarrera)
    const vinculadasIds = (data.carreras_vinculadas || data.carreras_vinculadas_ids || []).map(v => parseInt(v, 10));
    document.querySelectorAll('.check-carrera-vinculada').forEach(chk => {
        const val = parseInt(chk.value, 10);
        if (String(val) === String(targetCarreraId)) {
            chk.checked = false;
            chk.disabled = true;
        } else {
            chk.disabled = false;
            chk.checked = vinculadasIds.includes(val);
        }
        actualizarEstiloTagCarrera(chk);
    });
    actualizarDisponibilidadCarrerasVinculadas(targetCarreraId);

    actualizarLineasPorCarrera(targetCarreraId, data.linea_id, data.dimension_id);
    actualizarTrayectosPorCarrera(targetCarreraId, data.trayecto, data.id_trayecto);
    if (typeof calcularCompletitudFormularioPst === 'function') {
        setTimeout(calcularCompletitudFormularioPst, 120);
    }
}

function syncIdTrayecto(selectEl) {
    if (!selectEl) return;
    const opt = selectEl.options[selectEl.selectedIndex];
    const idInput = document.getElementById('id_trayecto');
    if (idInput && opt) {
        idInput.value = opt.getAttribute('data-id') || '';
    }
}

async function actualizarTrayectosPorCarrera(carreraId, selectedTrayecto = null, selectedTrayectoId = null) {
    const trayectoSelect = document.getElementById('trayecto');
    if (!trayectoSelect) return;

    try {
        const url = `?ruta=agregar-documento&accion=obtener_trayectos${carreraId ? '&carrera_id=' + encodeURIComponent(carreraId) : ''}`;
        const res = await fetch(url);
        const json = await res.json();
        
        if (json.status === 'success' && Array.isArray(json.trayectos) && json.trayectos.length > 0) {
            trayectoSelect.innerHTML = '';
            const vistos = new Set();
            json.trayectos.forEach(t => {
                const key = t.numero ? String(t.numero) : t.nombre;
                if (!vistos.has(key)) {
                    vistos.add(key);
                    const opt = document.createElement('option');
                    opt.value = t.nombre;
                    opt.setAttribute('data-id', t.id);
                    opt.textContent = t.nombre;
                    trayectoSelect.appendChild(opt);
                }
            });
            
            if (selectedTrayectoId) {
                for (let i = 0; i < trayectoSelect.options.length; i++) {
                    if (trayectoSelect.options[i].getAttribute('data-id') == selectedTrayectoId) {
                        trayectoSelect.selectedIndex = i;
                        break;
                    }
                }
            } else if (selectedTrayecto) {
                trayectoSelect.value = selectedTrayecto;
            }
            syncIdTrayecto(trayectoSelect);
        }
    } catch (e) {
        console.error('Error cargando trayectos por carrera:', e);
    }
}

function alCambiarCarreraFormulario(carreraId) {
    actualizarLineasPorCarrera(carreraId);
    actualizarTrayectosPorCarrera(carreraId);
    actualizarDisponibilidadCarrerasVinculadas(carreraId);
}

function actualizarDisponibilidadCarrerasVinculadas(carreraBaseId) {
    document.querySelectorAll('.check-carrera-vinculada').forEach(chk => {
        const label = document.getElementById('tag_carrera_label_' + chk.value);
        if (String(chk.value) === String(carreraBaseId)) {
            chk.checked = false;
            chk.disabled = true;
            if (label) {
                label.style.opacity = '0.45';
                label.style.pointerEvents = 'none';
                label.style.background = '#f8fafc';
                label.style.borderColor = '#cbd5e1';
                label.style.color = 'var(--texto-silenciado)';
            }
        } else {
            chk.disabled = false;
            if (label) {
                label.style.opacity = '1';
                label.style.pointerEvents = 'auto';
                actualizarEstiloTagCarrera(chk);
            }
        }
    });
}

function actualizarEstiloTagCarrera(chk) {
    const label = document.getElementById('tag_carrera_label_' + chk.value);
    if (!label) return;
    if (chk.checked) {
        label.style.background = 'rgba(112, 144, 203, 0.15)';
        label.style.borderColor = 'var(--color-terciario)';
        label.style.color = 'var(--color-secundario)';
    } else {
        label.style.background = '#ffffff';
        label.style.borderColor = '#cbd5e1';
        label.style.color = 'var(--color-secundario)';
    }
}

async function actualizarLineasPorCarrera(carreraId, selectedLineaId = null, selectedDimId = null) {
    const lineaSelect = document.getElementById('linea_id');
    if (!lineaSelect) return;

    lineaSelect.innerHTML = '<option value="">Cargando líneas...</option>';
    
    try {
        const url = `?ruta=agregar-documento&accion=obtener_lineas${carreraId ? '&carrera_id=' + encodeURIComponent(carreraId) : ''}`;
        const res = await fetch(url);
        const json = await res.json();
        
        lineaSelect.innerHTML = '<option value="">Seleccione una Línea...</option>';
        if (json.status === 'success' && Array.isArray(json.lineas)) {
            json.lineas.forEach(linea => {
                const opt = document.createElement('option');
                opt.value = linea.id;
                opt.textContent = linea.nombre;
                lineaSelect.appendChild(opt);
            });
        }
        if (selectedLineaId) {
            lineaSelect.value = selectedLineaId;
        }
        if (typeof updateDimensionOptions === 'function') {
            updateDimensionOptions(lineaSelect.value);
            if (selectedDimId) {
                const dimSelect = document.getElementById('dimension_id');
                if (dimSelect) dimSelect.value = selectedDimId;
            }
        }
        if (typeof calcularCompletitudFormularioPst === 'function') {
            calcularCompletitudFormularioPst();
        }
    } catch (e) {
        console.error('Error cargando líneas por carrera:', e);
        lineaSelect.innerHTML = '<option value="">Seleccione una Línea...</option>';
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
function mostrarModalAlerta(tipo, titulo, mensaje, onAccept = null) {
    const modal = document.getElementById('pstSystemModal');
    const iconWrapper = document.getElementById('modalPstIconWrapper');
    const titleElem = document.getElementById('modalPstTitle');
    const msgElem = document.getElementById('modalPstMessage');
    const btnConfirm = document.getElementById('btnModalPstConfirm');
    const btnCancel = document.getElementById('btnModalPstCancel');

    btnCancel.style.display = 'none';
    btnConfirm.textContent = 'Aceptar';
    btnConfirm.onclick = () => {
        cerrarModalAlertaPst();
        if (typeof onAccept === 'function') {
            onAccept();
        }
    };

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
        const isLegacyDoc = item.nombreArchivo && item.nombreArchivo.toLowerCase().endsWith('.doc');
        if (isLegacyDoc && item.data && item.data.archivo_pdf) {
            fileUrl = `?ruta=ver-pdf-pst&file=${encodeURIComponent(item.data.archivo_pdf)}`;
            fileName = item.nombreArchivo;
        } else if (item.file && !isLegacyDoc) {
            fileUrl = URL.createObjectURL(item.file);
            fileName = item.nombreArchivo;
            if (item.nombreArchivo.toLowerCase().endsWith('.docx')) isDocx = true;
        } else if (item.data && item.data.archivo_pdf) {
            const tbHash = <?= ConfigService::get('visor_pdf.mostrar_toolbar', true) ? "'#toolbar=1&navpanes=1'" : "'#toolbar=0&navpanes=0'" ?>;
            fileUrl = `?ruta=ver-pdf-pst&file=${encodeURIComponent(item.data.archivo_pdf)}${tbHash}`;
            fileName = item.nombreArchivo || item.data.archivo_pdf.split('/').pop();
            if (fileName.toLowerCase().endsWith('.docx') || item.data.archivo_pdf.toLowerCase().endsWith('.docx')) isDocx = true;
        }
    } 
    // Caso 2: Archivo recién seleccionado localmente por el usuario
    else if (fileInput && fileInput.files && fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const isLegacyDoc = file.name.toLowerCase().endsWith('.doc');
        if (isLegacyDoc && pdfHidden && pdfHidden.value) {
            fileUrl = `?ruta=ver-pdf-pst&file=${encodeURIComponent(pdfHidden.value)}`;
            fileName = file.name;
        } else {
            fileUrl = URL.createObjectURL(file);
            fileName = file.name;
            if (file.name.toLowerCase().endsWith('.docx')) isDocx = true;
        }
    } 
    // Caso 3: Documento en edición o registrado en la base de datos
    else if (pdfHidden && pdfHidden.value) {
        const tbHash = <?= ConfigService::get('visor_pdf.mostrar_toolbar', true) ? "'#toolbar=1&navpanes=1'" : "'#toolbar=0&navpanes=0'" ?>;
        <?php if (!empty($documento['id'])): ?>
            fileUrl = '?ruta=ver-pdf-pst&id=<?= $documento['id'] ?>' + tbHash;
            fileName = '<?= htmlspecialchars($documento['titulo'] ?? 'Documento Indexado') ?>';
            if (pdfHidden.value.toLowerCase().endsWith('.docx')) isDocx = true;
        <?php else: ?>
            fileUrl = `?ruta=ver-pdf-pst&file=${encodeURIComponent(pdfHidden.value)}${tbHash}`;
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
        modal.style.display = 'flex';
        iframe.removeAttribute('srcdoc');
        iframe.src = fileUrl || 'about:blank';
    }

    modal.style.display = 'flex';
}

function fetchYRenderizarMammoth(url, iframe, fileName) {
    let cleanUrl = url.split('#')[0];
    if (!cleanUrl.includes('raw=1')) {
        cleanUrl += (cleanUrl.includes('?') ? '&' : '?') + 'raw=1';
    }
    fetch(cleanUrl)
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
                <p style="font-size:0.8rem; color:#64748b;">Ruta solicitada: <code>${cleanUrl}</code></p>
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
                            border-bottom: 2px solid var(--color-secundario, rgb(80, 89, 132));
                            padding-bottom: 0.5rem;
                            margin-bottom: 2rem;
                        }
                        h1, .doc-title {
                            color: var(--texto-titulos, #1E293B);
                            font-size: 1.8rem;
                            font-weight: 800;
                            line-height: 1.25;
                            margin-top: 1.5rem;
                            margin-bottom: 1rem;
                        }
                        h2, .doc-subtitle, .doc-heading-1 {
                            color: var(--texto-titulos, #1E293B);
                            font-size: 1.35rem;
                            font-weight: 700;
                            margin-top: 1.75rem;
                            margin-bottom: 0.75rem;
                            border-bottom: 1px solid #e2e8f0;
                            padding-bottom: 0.35rem;
                        }
                        h3, .doc-heading-2 {
                            color: var(--color-secundario, rgb(80, 89, 132));
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
                            border-left: 4px solid var(--color-secundario, rgb(80, 89, 132));
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
    const bindCedulaBlur = (tipoElem, numElem, nomElem, tipoPersona) => {
        if (!numElem || !nomElem) return;
        const ejecutarBusqueda = () => {
            const num = numElem.value.trim().replace(/\D/g, '');
            const tipo = tipoElem ? tipoElem.value : 'V-';
            const ced = num ? (tipo + num) : '';
            if (num.length >= 6 && (!nomElem.value || nomElem.value.trim() === '')) {
                fetch(`?ruta=agregar-documento&accion=buscar_cedula&cedula=${encodeURIComponent(ced)}&tipo=${tipoPersona}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success' && data.nombre) {
                            nomElem.value = data.nombre;
                            nomElem.style.transition = 'background-color 0.3s';
                            nomElem.style.backgroundColor = 'rgba(112, 144, 203, 0.15)';
                            setTimeout(() => { nomElem.style.backgroundColor = ''; }, 1200);
                        }
                    })
                    .catch(err => console.error('Error al autocompletar persona:', err));
            }
        };
        numElem.addEventListener('blur', ejecutarBusqueda);
    };

    // Autores principales
    const autorRows = document.querySelectorAll('.authors-container .grid-2-cols');
    autorRows.forEach(row => {
        const tipoEl = row.querySelector('.autor-cedula-tipo');
        const numEl = row.querySelector('.autor-cedula-num');
        const nomEl = row.querySelector('input[name="autor_nombre[]"]');
        bindCedulaBlur(tipoEl, numEl, nomEl, 'autor');
    });

    // Tutores
    const bindTutor = (tipoId, numId, nomName) => {
        const t = document.getElementById(tipoId);
        const c = document.getElementById(numId);
        const n = document.getElementsByName(nomName)[0];
        if (c && n) bindCedulaBlur(t, c, n, 'tutor');
    };
    bindTutor('tutor_academico_tipo', 'tutor_academico_num', 'tutor_academico_nombre');
    bindTutor('tutor_institucional_tipo', 'tutor_institucional_num', 'tutor_institucional_nombre');
    bindTutor('tutor_comunitario_tipo', 'tutor_comunitario_num', 'tutor_comunitario_nombre');
}

// PRUEBAS DE EXTRACCIÓN DE TEXTO (SIMULACIÓN DE EXTRACCIÓN DE METADATOS)
function simularExtraccionModal() {
    const fileInput = document.getElementById('input_archivo_extractor');
    if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
        mostrarModalAlerta('warning', 'Archivo Requerido', 'Seleccione o arrastre un documento PDF o Word (.docx / .doc) en la casilla de la derecha para simular su extracción.');
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
            let tutoresStr = [];
            if (d.tutor_academico_nombre) tutoresStr.push(`  - Académico: ${d.tutor_academico_nombre}${d.tutor_academico_cedula ? ' (' + d.tutor_academico_cedula + ')' : ''}`);
            if (d.tutor_institucional_nombre) tutoresStr.push(`  - Institucional: ${d.tutor_institucional_nombre}${d.tutor_institucional_cedula ? ' (' + d.tutor_institucional_cedula + ')' : ''}`);
            if (d.tutor_comunitario_nombre) tutoresStr.push(`  - Comunitario: ${d.tutor_comunitario_nombre}${d.tutor_comunitario_cedula ? ' (' + d.tutor_comunitario_cedula + ')' : ''}`);
            
            const resMsj = `METADATOS DETECTADOS POR EL EXTRACTOR:\n\n` +
                `• Título: ${d.titulo || 'No detectado'}\n` +
                `• Año: ${d.anio_publicacion || 's.f.'} | Fecha Defensa: ${d.fecha_defensa || 'Pendiente (' + (d.fecha_precision || 's.f.') + ')'}\n` +
                `• Nivel Académico: ${d.nivel_academico || 'Pregrado'} | Trayecto: ${d.trayecto || 'No detectado'}\n` +
                `• Línea ID / Dimensión ID: ${d.linea_id || 'N/A'} / ${d.dimension_id || 'N/A'}\n` +
                `• URL Repositorio: ${d.url_repositorio || 'No detectado'}\n` +
                `• Autores Extraídos: ${d.autores ? d.autores.map(a => (a.nombre || a.nombre_completo) + (a.cedula ? ' (' + a.cedula + ')' : '')).filter(Boolean).join(', ') : 'Ninguno'}\n` +
                `• Tutores:\n${tutoresStr.length > 0 ? tutoresStr.join('\n') : '  No detectados'}\n` +
                `• Comunidad Beneficiada: ${d.comunidad_beneficiada || 'No detectada'}\n\n` +
                `FRAGMENTO DE TEXTO EXTRAÍDO:\n"${data.preview_texto}"`;
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

    const formPst = document.getElementById('formSubidaPst');
    if (formPst) {
        formPst.addEventListener('submit', function() {
            sincronizarCedulasPST();
        });
    }

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
<div id="modalFichaAdminPst" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(5px); z-index: 99999; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: var(--bg-card, #ffffff); border: 1px solid rgba(80, 89, 132, 0.25); border-radius: 12px; width: 95%; max-width: 1050px; max-height: 92vh; overflow-y: auto; padding: 2rem; box-shadow: 0 20px 45px rgba(0,0,0,0.25); animation: fadeIn 0.2s ease-out;">
        
        <!-- Encabezado de la Ficha -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid rgba(80, 89, 132, 0.15); padding-bottom: 1rem; margin-bottom: 1.25rem;">
            <div style="flex: 1; padding-right: 1rem;">
                <div style="display: flex; gap: 0.4rem; align-items: center; margin-bottom: 0.35rem; flex-wrap: wrap;">
                    <span class="pst-badge-soft" id="modalFichaNivel" style="background: rgba(112, 144, 203, 0.12); color: var(--color-terciario); padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.75rem; font-weight: 800;"></span>
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
                <div style="background: #fafbfe; padding: 1rem; border-radius: 8px; border: 1px solid rgba(112, 144, 203, 0.15);">
                    <strong style="color: var(--color-terciario); display: flex; align-items: center; gap: 0.35rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem;">
                        <i class="ph ph-users" style="font-size: 1.1rem;"></i> Equipo de Autores
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
            <div id="modalFichaObjWrapper" style="background: #fafbfe; border-left: 4px solid var(--color-terciario); padding: 1rem 1.1rem; border-radius: 0 8px 8px 0;">
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
                <div style="grid-column: span 2;" id="modalFichaCarrerasVinculadasWrapper">
                    <strong style="color: var(--texto-titulos); font-size: 0.78rem; display: block; text-transform: uppercase; margin-bottom: 0.2rem;"><i class="ph ph-tag"></i> Carreras Vinculadas:</strong>
                    <span id="modalFichaCarrerasVinculadas" style="font-weight: 600; color: var(--color-secundario);"></span>
                </div>
                <div style="grid-column: span 2;">
                    <strong style="color: var(--texto-titulos); font-size: 0.78rem; display: block; text-transform: uppercase; margin-bottom: 0.2rem;">Palabras Clave:</strong>
                    <span id="modalFichaKeywords" style="font-style: italic; color: var(--texto-silenciado);"></span>
                </div>
                <div id="modalFichaGitWrapper" style="grid-column: span 2;">
                    <strong style="color: var(--texto-titulos); font-size: 0.78rem; display: block; text-transform: uppercase; margin-bottom: 0.2rem;">Código Fuente:</strong>
                    <a id="modalFichaGit" href="#" target="_blank" style="color: var(--color-terciario); text-decoration: underline; font-weight: 600;"></a>
                </div>
            </div>

        </div>

        <!-- Botones Inferiores -->
        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(169, 168, 166, 0.2); padding-top: 1rem; margin-top: 1.5rem;">
            <div id="modalFichaAdjuntoWrapper">
                <a id="btnModalFichaVerAdjunto" href="#" target="_blank" class="btn-save" style="background: var(--color-secundario); color: white; display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.45rem 1rem; text-decoration: none;">
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
        badgeEstado.style.background = 'rgba(100, 116, 139, 0.12)';
        badgeEstado.style.color = 'var(--texto-silenciado)';
    } else {
        badgeEstado.textContent = 'Visibilidad: Activo';
        badgeEstado.style.background = 'rgba(80, 89, 132, 0.1)';
        badgeEstado.style.color = 'var(--color-secundario)';
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
    const vincEl = document.getElementById('modalFichaCarrerasVinculadas');
    if (vincEl) {
        vincEl.textContent = doc.carreras_vinculadas_nombres || 'Ninguna';
    }
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
    if (doc.archivo_pdf && doc.archivo_existe) {
        btnAdjunto.href = '?ruta=ver-pdf-pst&id=' + doc.id;
        btnAdjuntoWrapper.style.display = 'block';
    } else {
        btnAdjuntoWrapper.style.display = 'none';
    }

    document.getElementById('modalFichaAdminPst').style.display = 'flex';
}
</script>

<!-- MODAL INTERACTIVO DE SELECCIÓN DE CARRERA PARA EXTRACCIÓN POR LOTES -->
<div id="modalSeleccionCarreraPst" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: 16px; padding: 2rem; width: 90%; max-width: 460px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); text-align: center; border: 1px solid rgba(80, 89, 132, 0.2); position: relative;">
        <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(80, 89, 132, 0.1); color: var(--color-secundario); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 1.2rem auto; border: 1px solid rgba(80, 89, 132, 0.25);">
            <i class="ph ph-graduation-cap"></i>
        </div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--texto-titulos); margin: 0 0 0.5rem 0;">Programa Académico Destino</h3>
        <p style="font-size: 0.88rem; color: var(--texto-silenciado); margin: 0 0 1.2rem 0; line-height: 1.5;">
            Selecciona la carrera o PNF al que pertenecen los documentos a extraer:
        </p>

        <div style="margin-bottom: 1.5rem; text-align: left;">
            <label style="font-size: 0.78rem; font-weight: 800; color: #334155; display: block; margin-bottom: 0.4rem; text-transform: uppercase;">Carrera / PNF *</label>
            <select id="selectCarreraModalLote" class="upload-input" style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid #cbd5e1; font-weight: 600; font-size: 0.95rem; background: #f8fafc;">
                <option value="">Seleccione una Carrera...</option>
                <?php foreach ($carrerasList as $cItem): ?>
                    <option value="<?= $cItem['id'] ?>" <?= ((string)$currCarrera === (string)$cItem['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cItem['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
            <button type="button" class="btn-cancel" onclick="cerrarModalCarreraPst()" style="padding: 9px 18px; font-size: 0.88rem;">Cancelar</button>
            <button type="button" class="btn-save" onclick="confirmarCarreraModalLote()" style="padding: 9px 20px; font-size: 0.88rem; display: inline-flex; align-items: center; gap: 6px;">
                <i class="ph ph-check-circle"></i> Continuar Extracción
            </button>
        </div>
    </div>
</div>

<script>
let archivosPendientesExtraccion = null;

function cerrarModalCarreraPst() {
    const modal = document.getElementById('modalSeleccionCarreraPst');
    if (modal) modal.style.display = 'none';
    archivosPendientesExtraccion = null;
    const fileInput = document.getElementById('input_archivo_extractor');
    if (fileInput) fileInput.value = '';
}

function confirmarCarreraModalLote() {
    const selectModal = document.getElementById('selectCarreraModalLote');
    const carreraVal = selectModal ? selectModal.value : '';
    
    if (!carreraVal) {
        mostrarModalAlerta('warning', 'Selección Requerida', 'Por favor selecciona la carrera destino para procesar la investigación.');
        return;
    }

    const carreraSelectForm = document.getElementById('id_carrera');
    if (carreraSelectForm) {
        carreraSelectForm.value = carreraVal;
    }

    // Cargar las líneas y trayectos de la carrera seleccionada
    actualizarLineasPorCarrera(carreraVal);
    actualizarTrayectosPorCarrera(carreraVal);

    const modal = document.getElementById('modalSeleccionCarreraPst');
    if (modal) modal.style.display = 'none';

    if (archivosPendientesExtraccion && archivosPendientesExtraccion.length > 0) {
        const filesToProcess = archivosPendientesExtraccion;
        archivosPendientesExtraccion = null;
        iniciarProcesamientoListaArchivos(filesToProcess, carreraVal);
    }
}
</script>

<!-- MODAL DE EDICIÓN RÁPIDA DE PROYECTO DESDE LA TABLA -->
<div id="modalEdicionRapidaPst" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); z-index: 99998; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: var(--bg-card, #ffffff); border-radius: 16px; width: 100%; max-width: 620px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 1px solid rgba(80, 89, 132, 0.2); overflow: hidden;">
        <div style="padding: 1.25rem 1.5rem; background: linear-gradient(135deg, var(--color-secundario) 0%, var(--color-terciario) 100%); color: #ffffff; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #ffffff; display: flex; align-items: center; gap: 8px;">
                    <i class="ph ph-sliders-horizontal"></i> Ajuste Rápido de Proyecto
                </h3>
                <p style="margin: 3px 0 0 0; font-size: 0.8rem; color: rgba(255, 255, 255, 0.88);">Modifica los datos principales sin salir del listado</p>
            </div>
            <button type="button" onclick="cerrarModalEdicionRapidaPst()" style="background: rgba(255,255,255,0.18); border: none; color: #ffffff; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                <i class="ph ph-x"></i>
            </button>
        </div>

        <div style="padding: 1.4rem 1.5rem; max-height: 78vh; overflow-y: auto;">
            <input type="hidden" id="quick_edit_id" value="">

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.76rem; font-weight: 800; color: var(--texto-titulos); text-transform: uppercase; margin-bottom: 5px;">Título del Proyecto</label>
                <textarea id="quick_edit_titulo" class="upload-input" rows="2" style="width: 100%; padding: 9px 12px; font-size: 0.9rem; resize: vertical;"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 0.9rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.76rem; font-weight: 800; color: var(--texto-titulos); text-transform: uppercase; margin-bottom: 5px;">Carrera o PNF</label>
                    <select id="quick_edit_carrera" class="upload-input" style="width: 100%; padding: 9px 12px; font-size: 0.88rem;">
                        <?php foreach ($carrerasList as $cItem): ?>
                            <option value="<?= $cItem['id'] ?>"><?= htmlspecialchars($cItem['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.76rem; font-weight: 800; color: var(--texto-titulos); text-transform: uppercase; margin-bottom: 5px;">Año de Creación</label>
                    <input type="number" id="quick_edit_anio" class="upload-input" min="2000" max="2099" style="width: 100%; padding: 9px 12px; font-size: 0.88rem;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.9rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.76rem; font-weight: 800; color: var(--texto-titulos); text-transform: uppercase; margin-bottom: 5px;">Línea de Investigación</label>
                    <select id="quick_edit_linea" class="upload-input" onchange="actualizarDimensionesEdicionRapida(this.value)" style="width: 100%; padding: 9px 12px; font-size: 0.88rem;">
                        <option value="">Sin línea asignada</option>
                        <?php foreach ($lineas as $lItem): ?>
                            <option value="<?= $lItem['id'] ?>"><?= htmlspecialchars($lItem['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.76rem; font-weight: 800; color: var(--texto-titulos); text-transform: uppercase; margin-bottom: 5px;">Dimensión Operativa</label>
                    <select id="quick_edit_dimension" class="upload-input" style="width: 100%; padding: 9px 12px; font-size: 0.88rem;">
                        <option value="">Sin dimensión asignada</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 0.9rem; margin-bottom: 0.5rem;">
                <div>
                    <label style="display: block; font-size: 0.76rem; font-weight: 800; color: var(--texto-titulos); text-transform: uppercase; margin-bottom: 5px;">Enlace de Código</label>
                    <input type="url" id="quick_edit_git" class="upload-input" placeholder="https://github.com/usuario/proyecto" style="width: 100%; padding: 9px 12px; font-size: 0.88rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.76rem; font-weight: 800; color: var(--texto-titulos); text-transform: uppercase; margin-bottom: 5px;">Visibilidad en el Catálogo</label>
                    <select id="quick_edit_activo" class="upload-input" style="width: 100%; padding: 9px 12px; font-size: 0.88rem;">
                        <option value="1">Visible al público</option>
                        <option value="0">Oculto del catálogo</option>
                    </select>
                </div>
            </div>
        </div>

        <div style="padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 0.75rem;">
            <button type="button" class="btn-cancel" onclick="cerrarModalEdicionRapidaPst()" style="padding: 8px 18px; font-size: 0.86rem;">Cancelar</button>
            <button type="button" id="btnGuardarQuickEditPst" class="btn-save" onclick="guardarEdicionRapidaPst()" style="padding: 8px 20px; font-size: 0.86rem; display: inline-flex; align-items: center; gap: 6px;">
                <i class="ph ph-floppy-disk"></i> Guardar Cambios
            </button>
        </div>
    </div>
</div>

<!-- MODAL DE VACIADO TOTAL DEL CATÁLOGO CON AUTORIZACIÓN DEL SUPER ADMINISTRADOR -->
<div id="modalPurgarCatalogoPst" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: var(--bg-card, #ffffff); border-radius: 16px; width: 100%; max-width: 500px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3); border: 1px solid #fecdd3; overflow: hidden;">
        <div style="padding: 1.3rem 1.5rem; background: #fff1f2; border-bottom: 1px solid #ffe4e6; display: flex; align-items: center; gap: 12px;">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: #ffe4e6; color: #be123c; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                <i class="ph ph-shield-warning"></i>
            </div>
            <div style="flex: 1;">
                <h3 style="margin: 0; font-size: 1.12rem; font-weight: 800; color: #881337;">Vaciar Catálogo de Proyectos</h3>
                <p style="margin: 2px 0 0 0; font-size: 0.79rem; color: #9f1239;">Requiere autorización de un Super Administrador</p>
            </div>
            <button type="button" onclick="cerrarModalPurgarCatalogo()" style="background: transparent; border: none; color: #9f1239; width: 30px; height: 30px; border-radius: 8px; cursor: pointer; font-size: 1.1rem;">
                <i class="ph ph-x"></i>
            </button>
        </div>

        <div style="padding: 1.35rem 1.5rem;">
            <p style="margin: 0 0 1rem 0; font-size: 0.86rem; color: var(--texto-comun); line-height: 1.5;">
                Esta operación eliminará todos los proyectos registrados en el repositorio junto con sus documentos guardados en el servidor.
            </p>

            <label style="display: flex; align-items: flex-start; gap: 9px; padding: 10px 12px; border-radius: 10px; background: #f8fafc; border: 1px solid #e2e8f0; margin-bottom: 1rem; cursor: pointer;">
                <input type="checkbox" id="purgar_conservar_personas" checked style="margin-top: 3px; width: 16px; height: 16px; accent-color: var(--color-secundario);">
                <span style="font-size: 0.82rem; color: var(--texto-titulos); font-weight: 600; line-height: 1.4;">
                    Conservar el directorio de personas registradas, como autores y tutores, para agilizar próximas cargas
                </span>
            </label>

            <div style="margin-bottom: 0.9rem;">
                <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--texto-titulos); text-transform: uppercase; margin-bottom: 4px;">Cédula del Super Administrador</label>
                <input type="text" id="purgar_cedula_superadmin" class="upload-input" value="<?= htmlspecialchars($_SESSION['usuario_cedula'] ?? '') ?>" placeholder="Ej: V-12345678" style="width: 100%; padding: 9px 12px; font-size: 0.88rem;">
            </div>

            <div style="margin-bottom: 0.9rem;">
                <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--texto-titulos); text-transform: uppercase; margin-bottom: 4px;">Contraseña del Super Administrador</label>
                <input type="password" id="purgar_password_superadmin" class="upload-input" placeholder="Ingresa la contraseña para autorizar" style="width: 100%; padding: 9px 12px; font-size: 0.88rem;">
            </div>

            <div style="margin-bottom: 0.4rem;">
                <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #be123c; text-transform: uppercase; margin-bottom: 4px;">Escribe VACIAR REPOSITORIO para confirmar</label>
                <input type="text" id="purgar_frase_confirmacion" class="upload-input" placeholder="VACIAR REPOSITORIO" autocomplete="off" style="width: 100%; padding: 9px 12px; font-size: 0.88rem; border-color: #fda4af;">
            </div>
        </div>

        <div style="padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 0.75rem;">
            <button type="button" class="btn-cancel" onclick="cerrarModalPurgarCatalogo()" style="padding: 8px 16px; font-size: 0.86rem;">Cancelar</button>
            <button type="button" id="btnEjecutarPurgaCatalogo" onclick="ejecutarPurgaTotalCatalogo()" style="padding: 8px 18px; font-size: 0.86rem; font-weight: 700; border-radius: 8px; border: 1px solid #be123c; background: #be123c; color: #ffffff; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                <i class="ph ph-trash"></i> Confirmar y Vaciar
            </button>
        </div>
    </div>
</div>

<script>
// 1. INDICADOR EN TIEMPO REAL DE COMPLETITUD DEL FORMULARIO
function calcularCompletitudFormularioPst() {
    const barra = document.getElementById('barraCompletitudFicha');
    const textoPct = document.getElementById('porcentajeCompletitudFicha');
    const textoEstado = document.getElementById('estadoCompletitudTexto');
    if (!barra || !textoPct || !textoEstado) return;

    const camposEvaluados = [
        { nombre: 'título', ok: Boolean((document.getElementById('titulo')?.value || '').trim()) },
        { nombre: 'carrera', ok: Boolean((document.getElementById('id_carrera')?.value || '').trim()) },
        { nombre: 'año', ok: Boolean((document.getElementById('anio_publicacion')?.value || '').trim()) },
        { nombre: 'línea de investigación', ok: Boolean((document.getElementById('linea_id')?.value || '').trim()) },
        { nombre: 'dimensión', ok: Boolean((document.getElementById('dimension_id')?.value || '').trim()) },
        { nombre: 'comunidad beneficiada', ok: Boolean((document.getElementById('comunidad_beneficiada')?.value || '').trim()) },
        { nombre: 'resumen', ok: (document.getElementById('resumen')?.value || '').trim().length > 25 },
        { nombre: 'objetivo general', ok: (document.getElementById('obj_general')?.value || '').trim().length > 15 },
        { nombre: 'palabras clave', ok: Boolean((document.getElementById('palabras_clave')?.value || '').trim()) }
    ];

    // Verificar al menos un autor con nombre
    let tieneAutor = false;
    document.getElementsByName('autor_nombre[]').forEach(inp => {
        if ((inp.value || '').trim().length > 2) tieneAutor = true;
    });
    camposEvaluados.push({ nombre: 'autor principal', ok: tieneAutor });

    // Verificar al menos un tutor con nombre
    const tAcad = (document.getElementById('tutor_academico_nombre')?.value || '').trim();
    const tInst = (document.getElementById('tutor_institucional_nombre')?.value || '').trim();
    const tCom = (document.getElementById('tutor_comunitario_nombre')?.value || '').trim();
    camposEvaluados.push({ nombre: 'tutor', ok: Boolean(tAcad || tInst || tCom) });

    // Verificar archivo adjunto o cargado
    const tieneArchivo = Boolean(
        (document.getElementById('archivo_pdf_hidden')?.value || '').trim() ||
        (document.getElementById('input_archivo_extractor')?.files?.length > 0)
    );
    camposEvaluados.push({ nombre: 'documento adjunto', ok: tieneArchivo });

    const completados = camposEvaluados.filter(c => c.ok).length;
    const faltantes = camposEvaluados.filter(c => !c.ok).map(c => c.nombre);
    const pct = Math.round((completados / camposEvaluados.length) * 100);

    barra.style.width = pct + '%';
    textoPct.textContent = pct + '%';

    if (pct === 100) {
        textoEstado.textContent = 'Ficha completa y lista para guardar';
    } else if (faltantes.length <= 3) {
        textoEstado.textContent = 'Casi listo, falta completar: ' + faltantes.join(', ');
    } else {
        textoEstado.textContent = completados + ' de ' + camposEvaluados.length + ' datos clave completados';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const formPst = document.getElementById('formSubidaPst');
    if (formPst) {
        formPst.addEventListener('input', calcularCompletitudFormularioPst);
        formPst.addEventListener('change', calcularCompletitudFormularioPst);
        setTimeout(calcularCompletitudFormularioPst, 150);
    }
});

// 2. SELECCIÓN POR LOTES Y BARRA FLOTANTE DE ACCIONES MASIVAS
function obtenerIdsSeleccionadosTablaPst() {
    const checks = document.querySelectorAll('.check-pst-row:checked');
    return Array.from(checks).map(c => parseInt(c.value, 10)).filter(n => n > 0);
}

function alternarSeleccionTodosPst(masterCheckbox) {
    const checks = document.querySelectorAll('.check-pst-row');
    checks.forEach(c => {
        c.checked = masterCheckbox.checked;
    });
    actualizarBarraLotePst();
}

function actualizarBarraLotePst() {
    const seleccionados = obtenerIdsSeleccionadosTablaPst();
    const barra = document.getElementById('barraAccionesLotePst');
    const contador = document.getElementById('contadorSeleccionadosPst');
    const master = document.getElementById('checkAllPstTable');
    const totalChecks = document.querySelectorAll('.check-pst-row').length;

    if (contador) contador.textContent = seleccionados.length;
    if (barra) {
        barra.style.display = seleccionados.length > 0 ? 'flex' : 'none';
    }
    if (master && totalChecks > 0) {
        master.checked = seleccionados.length === totalChecks;
    }
}

function limpiarSeleccionTablaPst() {
    document.querySelectorAll('.check-pst-row').forEach(c => { c.checked = false; });
    const master = document.getElementById('checkAllPstTable');
    if (master) master.checked = false;
    actualizarBarraLotePst();
}

async function ejecutarAccionMasivaTabla(operacion) {
    const ids = obtenerIdsSeleccionadosTablaPst();
    if (ids.length === 0) return;

    try {
        const res = await fetch('?ruta=agregar-documento&accion=bulk_action', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': window.CSRF_TOKEN || ''
            },
            body: JSON.stringify({
                ids: ids,
                operacion: operacion,
                csrf_token: window.CSRF_TOKEN || ''
            })
        });
        const json = await res.json();
        if (json.status === 'success') {
            mostrarModalAlerta('success', 'Operación Completada', json.message || 'Los proyectos seleccionados fueron actualizados.', () => {
                window.location.reload();
            });
        } else {
            mostrarModalAlerta('error', 'No se pudo completar', json.message || 'Ocurrió un inconveniente al procesar la selección.');
        }
    } catch (err) {
        mostrarModalAlerta('error', 'Error de Conexión', 'No fue posible comunicarse con el servidor.');
    }
}

function confirmarEliminarLoteTabla() {
    const ids = obtenerIdsSeleccionadosTablaPst();
    if (ids.length === 0) return;

    confirmarAccionModal(
        'error',
        'Eliminar Selección',
        '¿Estás seguro de eliminar permanentemente los ' + ids.length + ' proyectos seleccionados junto con sus archivos? Esta acción no se puede deshacer.',
        'Sí, eliminar selección',
        () => {
            ejecutarAccionMasivaTabla('eliminar');
        }
    );
}

// 3. LIMPIEZA DE ARCHIVOS TEMPORALES DE EXTRACCIÓN
async function limpiarTemporalesServidor() {
    try {
        const res = await fetch('?ruta=agregar-documento&accion=limpiar_temporales', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': window.CSRF_TOKEN || ''
            },
            body: JSON.stringify({ csrf_token: window.CSRF_TOKEN || '' })
        });
        const json = await res.json();
        if (json.status === 'success') {
            mostrarModalAlerta('success', 'Almacenamiento Optimizado', json.message || 'Se limpiaron los archivos temporales.', () => {
                window.location.reload();
            });
        } else {
            mostrarModalAlerta('error', 'Aviso', json.message || 'No se pudieron limpiar los archivos temporales.');
        }
    } catch (e) {
        mostrarModalAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor.');
    }
}

// 4. EDICIÓN RÁPIDA DESDE LA TABLA
function actualizarDimensionesEdicionRapida(lineaId, selectedDimId = '') {
    const dimSelect = document.getElementById('quick_edit_dimension');
    if (!dimSelect) return;

    dimSelect.innerHTML = '<option value="">Sin dimensión asignada</option>';
    const dims = (typeof dimensionesPorLinea !== 'undefined' && lineaId && dimensionesPorLinea[lineaId]) ? dimensionesPorLinea[lineaId] : [];
    dims.forEach(d => {
        const opt = document.createElement('option');
        opt.value = d.id;
        opt.textContent = d.nombre;
        dimSelect.appendChild(opt);
    });
    if (selectedDimId) {
        dimSelect.value = String(selectedDimId);
    }
}

function abrirModalEdicionRapidaPst(doc) {
    if (!doc || !doc.id) return;
    document.getElementById('quick_edit_id').value = doc.id;
    document.getElementById('quick_edit_titulo').value = doc.titulo || '';
    document.getElementById('quick_edit_carrera').value = doc.id_carrera || 1;
    document.getElementById('quick_edit_anio').value = doc.anio_publicacion || new Date().getFullYear();
    document.getElementById('quick_edit_linea').value = doc.linea_id || '';
    actualizarDimensionesEdicionRapida(doc.linea_id || '', doc.dimension_id || '');
    document.getElementById('quick_edit_git').value = doc.url_repositorio || '';
    document.getElementById('quick_edit_activo').value = (doc.activo === false || doc.activo === 0 || doc.activo === '0' || doc.activo === 'f') ? '0' : '1';

    const modal = document.getElementById('modalEdicionRapidaPst');
    if (modal) modal.style.display = 'flex';
}

function cerrarModalEdicionRapidaPst() {
    const modal = document.getElementById('modalEdicionRapidaPst');
    if (modal) modal.style.display = 'none';
}

async function guardarEdicionRapidaPst() {
    const id = parseInt(document.getElementById('quick_edit_id').value, 10);
    const titulo = (document.getElementById('quick_edit_titulo').value || '').trim();
    if (!id || !titulo) {
        mostrarModalAlerta('warning', 'Título Requerido', 'El proyecto debe tener un título válido.');
        return;
    }

    const btn = document.getElementById('btnGuardarQuickEditPst');
    if (btn) btn.disabled = true;

    try {
        const res = await fetch('?ruta=agregar-documento&accion=quick_edit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': window.CSRF_TOKEN || ''
            },
            body: JSON.stringify({
                id: id,
                titulo: titulo,
                id_carrera: document.getElementById('quick_edit_carrera').value,
                anio_publicacion: document.getElementById('quick_edit_anio').value,
                linea_id: document.getElementById('quick_edit_linea').value,
                dimension_id: document.getElementById('quick_edit_dimension').value,
                url_repositorio: document.getElementById('quick_edit_git').value,
                activo: document.getElementById('quick_edit_activo').value === '1',
                csrf_token: window.CSRF_TOKEN || ''
            })
        });
        const json = await res.json();
        if (btn) btn.disabled = false;

        if (json.status === 'success') {
            cerrarModalEdicionRapidaPst();
            mostrarModalAlerta('success', 'Cambios Guardados', json.message || 'El proyecto se actualizó correctamente.', () => {
                window.location.reload();
            });
        } else {
            mostrarModalAlerta('error', 'No se pudo actualizar', json.message || 'Verifica los datos ingresados.');
        }
    } catch (e) {
        if (btn) btn.disabled = false;
        mostrarModalAlerta('error', 'Error de Conexión', 'No fue posible guardar los cambios en el servidor.');
    }
}

// 5. VACIADO TOTAL DEL CATÁLOGO CON AUTORIZACIÓN DEL SUPER ADMINISTRADOR
function abrirModalPurgarCatalogo() {
    const passInput = document.getElementById('purgar_password_superadmin');
    const fraseInput = document.getElementById('purgar_frase_confirmacion');
    if (passInput) passInput.value = '';
    if (fraseInput) fraseInput.value = '';
    const modal = document.getElementById('modalPurgarCatalogoPst');
    if (modal) modal.style.display = 'flex';
}

function cerrarModalPurgarCatalogo() {
    const modal = document.getElementById('modalPurgarCatalogoPst');
    if (modal) modal.style.display = 'none';
}

async function ejecutarPurgaTotalCatalogo() {
    const cedula = (document.getElementById('purgar_cedula_superadmin')?.value || '').trim();
    const password = document.getElementById('purgar_password_superadmin')?.value || '';
    const frase = (document.getElementById('purgar_frase_confirmacion')?.value || '').trim();
    const conservarPersonas = Boolean(document.getElementById('purgar_conservar_personas')?.checked);

    if (!password) {
        mostrarModalAlerta('warning', 'Contraseña Requerida', 'Por favor ingresa la contraseña del Super Administrador para autorizar la operación.');
        return;
    }

    if (frase.toUpperCase() !== 'VACIAR REPOSITORIO') {
        mostrarModalAlerta('warning', 'Frase Incorrecta', 'Debes escribir exactamente VACIAR REPOSITORIO para confirmar.');
        return;
    }

    const btn = document.getElementById('btnEjecutarPurgaCatalogo');
    if (btn) btn.disabled = true;

    try {
        const res = await fetch('?ruta=agregar-documento&accion=purgar_todo_pst', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': window.CSRF_TOKEN || ''
            },
            body: JSON.stringify({
                cedula_superadmin: cedula,
                password_superadmin: password,
                confirmacion_texto: frase,
                conservar_personas: conservarPersonas,
                csrf_token: window.CSRF_TOKEN || ''
            })
        });
        const json = await res.json();
        if (btn) btn.disabled = false;

        if (json.status === 'success') {
            cerrarModalPurgarCatalogo();
            mostrarModalAlerta('success', 'Catálogo Vaciado', json.message || 'El repositorio fue vaciado por completo.', () => {
                window.location.href = '?ruta=agregar-documento&accion=listar';
            });
        } else {
            mostrarModalAlerta('error', 'Autorización Rechazada', json.message || 'No se pudo autorizar el vaciado del catálogo.');
        }
    } catch (e) {
        if (btn) btn.disabled = false;
        mostrarModalAlerta('error', 'Error de Conexión', 'No fue posible comunicarse con el servidor.');
    }
}
</script>

