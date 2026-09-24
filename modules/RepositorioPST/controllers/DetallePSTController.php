<?php
// modules/RepositorioPST/controllers/DetallePSTController.php
require_once __DIR__ . '/../models/DocumentoModel.php';
require_once __DIR__ . '/../services/ConfigService.php';
require_once __DIR__ . '/../../SuperAdmin/services/SystemConfigService.php';

class DetallePSTController {
    private int $nivelAdmin;
    private int $nivelPublico;
    
    public function __construct() {
        $this->nivelAdmin   = SystemConfigService::get('accesos_modulos.repositorio_pst.admin', 1);
        $this->nivelPublico = SystemConfigService::get('accesos_modulos.repositorio_pst.publico', 10);
    }
    public function index(): array {
        $model = new DocumentoModel();
        
        // Paginación dinámica desde configuración JSON
        $limitDefault = (int)ConfigService::get('paginacion.limite_catalogo', 10);
        $limit = !empty($_GET['limit']) ? max(1, (int)$_GET['limit']) : $limitDefault;
        $page = !empty($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;
        
        // Configuración de filtro dinámico de carrera
        $permitirFiltroCarrera = (bool)ConfigService::get('buscador.permitir_filtro_carrera', true);
        $carreraId = null;
        if (!empty($_GET['carrera_id'])) {
            $carreraId = (int)$_GET['carrera_id'];
        } elseif (!$permitirFiltroCarrera) {
            $carreraId = 1;
        }

        // Capturar parámetros GET para el filtrado dinámico
        $filtros = [
            'carrera_id'      => $carreraId,
            'linea_id'        => !empty($_GET['linea_id']) ? (int)$_GET['linea_id'] : null,
            'dimension_id'    => !empty($_GET['dimension_id']) ? (int)$_GET['dimension_id'] : null,
            'nivel_academico' => !empty($_GET['nivel_academico']) ? trim($_GET['nivel_academico']) : null,
            'trayecto'        => !empty($_GET['trayecto']) ? trim($_GET['trayecto']) : null,
            'comunidad'       => !empty($_GET['comunidad']) ? trim($_GET['comunidad']) : null,
            'anio'            => !empty($_GET['anio']) ? (int)$_GET['anio'] : null,
        ];
        
        // Cargar los documentos filtrados y paginados
        $documentos = $model->getPSTDocumentos($filtros, $limit, $offset);
        $totalDocs = $model->getPSTDocumentosCount($filtros);
        $totalPages = ceil($totalDocs / $limit);
        
        // Obtener líneas, dimensiones, comunidades beneficiadas reales y conteo por año
        $lineas = $model->getLineasInvestigacion($carreraId);
        $dimensiones = $model->getDimensionesOperativas();
        $comunidades = $model->getComunidadesBeneficiadas();
        $anioCounts = $model->getPSTCountByYear($filtros);
        
        // Conteo rápido agregado por SQL para optimizar rendimiento
        $conteoLineas = $model->getPSTCountByLinea($carreraId);
        $conteoTrayectos = $model->getPSTCountByTrayecto($carreraId);
        $totalPSTGeneral = $totalDocs;
        
        $pstPorLinea = [];
        foreach ($documentos as $doc) {
            $lineaNombre = !empty($doc['linea_nombre']) ? trim($doc['linea_nombre']) : 'General';
            if (!isset($pstPorLinea[$lineaNombre])) {
                $pstPorLinea[$lineaNombre] = [];
            }
            if (count($pstPorLinea[$lineaNombre]) < 6) {
                $pstPorLinea[$lineaNombre][] = $doc;
            }
        }
        
        $carreras = $model->getCarreras();
        $nivelesAcademicos = $model->getNivelesAcademicos();
        $trayectosList = $model->getTrayectos();
        
        return [
            'documentos'        => $documentos,
            'lineas'            => $lineas,
            'dimensiones'       => $dimensiones,
            'comunidades'       => $comunidades,
            'carreras'          => $carreras,
            'nivelesAcademicos' => $nivelesAcademicos,
            'trayectosList'     => $trayectosList,
            'anioCounts'        => $anioCounts,
            'filtros'           => $filtros,
            'totalPSTGeneral'   => $totalPSTGeneral,
            'conteoLineas'      => $conteoLineas,
            'conteoTrayectos'   => $conteoTrayectos,
            'pstPorLinea'       => $pstPorLinea,
            'pagination'        => [
                'current_page' => $page,
                'total_pages'  => $totalPages,
                'total_items'  => $totalDocs,
                'limit'        => $limit
            ]
        ];
    }

    public function ver(): array {
        $model = new DocumentoModel();
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        
        $documento = null;
        $proyectosSimilares = [];
        $proyectosComunidad = [];
        $conteoComunidad = 0;
        
        if ($id) {
            $documento = $model->getPSTDocumentoById($id);
            if ($documento) {
                $lineaId = !empty($documento['linea_id']) ? (int)$documento['linea_id'] : null;
                $maxSimilares = (int)ConfigService::get('paginacion.max_proyectos_similares', 3);
                $proyectosSimilares = $model->getProyectosSimilares($id, $lineaId, $maxSimilares);
                
                if (!empty($documento['comunidad_beneficiada'])) {
                    $comunidadStr = trim($documento['comunidad_beneficiada']);
                    $conteoComunidad = $model->getConteoProyectosComunidad($comunidadStr, $id);
                    if ($conteoComunidad > 0) {
                        $proyectosComunidad = $model->getProyectosMismaComunidad($comunidadStr, $id, 4);
                    }
                }
            }
        }
        
        return [
            'documento'          => $documento,
            'proyectosSimilares' => $proyectosSimilares,
            'proyectosComunidad' => $proyectosComunidad,
            'conteoComunidad'    => $conteoComunidad
        ];
    }

    /**
     * Sirve el archivo PDF/Word de forma segura para previsualización inline en iframe u objeto.
     * Incluye protección estricta contra Path Traversal y sanitización de rutas.
     */
    public function verPdf(): void {
        error_reporting(E_ERROR | E_PARSE);
        ini_set('display_errors', '0');
        while (ob_get_level()) ob_end_clean();

        $id = !empty($_GET['id']) ? (int)$_GET['id'] : 0;
        $requestedFile = !empty($_GET['file']) ? trim($_GET['file']) : '';

        $fullPath = '';
        $safeFilename = 'documento';

        $doc = null;

        if (!empty($requestedFile)) {
            $relPath = ltrim(str_replace(['\\', '/'], '/', $requestedFile), '/');
            if (strpos($relPath, '..') === false && (strpos($relPath, 'storage/documentos/pst/') === 0 || strpos($relPath, 'storage/') === 0)) {
                $candidate = BASE_PATH . '/' . $relPath;
                if (is_file($candidate)) {
                    $fullPath = $candidate;
                    $safeFilename = pathinfo($fullPath, PATHINFO_BASENAME);
                }
            }
        }

        if (empty($fullPath)) {
            if ($id <= 0) {
                http_response_code(400);
                die("ID o parámetro de archivo no válido.");
            }

            $model = new DocumentoModel();
            $doc = $model->getPSTDocumentoById($id);
            if (!$doc) {
                http_response_code(404);
                die("Proyecto no encontrado en el sistema.");
            }

            if (isset($doc['activo']) && !$doc['activo']) {
                Auth::requierePrivilegioMinimo($this->nivelAdmin);
            }
            
            $dbPath = !empty($doc['archivo_pdf']) ? $doc['archivo_pdf'] : '';
            $relPath = ltrim(str_replace(['\\', '/'], '/', $dbPath), '/');
            
            // Prevención de Path Traversal
            if (strpos($relPath, '..') !== false) {
                http_response_code(403);
                die("Acceso denegado: Ruta de archivo no permitida.");
            }

            $fullPath = BASE_PATH . '/' . $relPath;
            $safeFilename = pathinfo($fullPath, PATHINFO_BASENAME);
        }
        
        // Fallback: Si no existe exactamente en la ruta de BD, buscar en storage por coincidencia limpia
        if (empty($dbPath) || !is_file($fullPath)) {
            $dir = BASE_PATH . '/storage/documentos/pst/';
            if (is_dir($dir) && !empty($relPath)) {
                $baseName = pathinfo($relPath, PATHINFO_FILENAME);
                if ($baseName) {
                    $candidates = glob($dir . '*' . preg_replace('/[^a-zA-Z0-9_\-]/', '', $baseName) . '*');
                    foreach ($candidates as $cand) {
                        if (is_file($cand)) {
                            $fullPath = $cand;
                            break;
                        }
                    }
                }
            }
        }

        if (!is_file($fullPath)) {
            header('Content-Type: text/html; charset=utf-8');
            echo "<div style='font-family:sans-serif; text-align:center; padding:3rem; color:#666;'>
                    <svg width='48' height='48' viewBox='0 0 24 24' fill='none' stroke='#007bff' stroke-width='2' style='margin-bottom:1rem;'><path d='M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z'></path><polyline points='14 2 14 8 20 8'></polyline></svg>
                    <h3 style='color:#002244; margin-bottom:0.5rem;'>Documento Indexado en Repositorio</h3>
                    <p style='max-width:500px; margin:0 auto 1rem auto; font-size:0.9rem;'>La investigación <strong>\"" . htmlspecialchars($doc['titulo'] ?? '') . "\"</strong> está debidamente registrada en la base de datos institucional.</p>
                    <span style='background:#f1f5f9; padding:0.3rem 0.6rem; border-radius:4px; font-size:0.75rem; color:#475569;'>Estado: Ficha registrada sin archivo adjunto cargado</span>
                 </div>";
            exit;
        }

        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        $safeFilename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', basename($fullPath));
        while (ob_get_level()) ob_end_clean();

        if ($ext === 'pdf') {
            $etag = '"' . md5(filesize($fullPath) . '_' . filemtime($fullPath)) . '"';
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $safeFilename . '"');
            header('Content-Length: ' . filesize($fullPath));
            header('Cache-Control: public, max-age=86400');
            header('ETag: ' . $etag);
            if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) === $etag) {
                http_response_code(304);
                exit;
            }
            readfile($fullPath);
            exit;
        } elseif ($ext === 'docx') {
            require_once BASE_PATH . '/vendor/autoload.php';
            try {
                $phpWord = \PhpOffice\PhpWord\IOFactory::load($fullPath);
                $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
                
                header('Content-Type: text/html; charset=utf-8');
                echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'>
                      <style>
                        body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 1.5rem; line-height: 1.6; user-select: none; }
                        .paper-container { max-width: 820px; margin: 0 auto; background: #ffffff; padding: 2.5rem 3rem; border-radius: 6px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; }
                        h1, h2, h3, h4 { color: #002244; font-weight: 800; line-height: 1.3; }
                        p { margin-bottom: 1rem; text-align: justify; font-size: 0.95rem; }
                        table { width: 100%; border-collapse: collapse; margin: 1.25rem 0; font-size: 0.9rem; }
                        td, th { border: 1px solid #cbd5e1; padding: 0.6rem; }
                        th { background: #f1f5f9; }
                      </style></head><body>
                      <div class='paper-container'>";
                $writer->save('php://output');
                echo "</div></body></html>";
                exit;
            } catch (Exception $e) {
                require_once __DIR__ . '/../services/ExtractorPST.php';
                $text = ExtractorPST::extraerTextoDOCX($fullPath);
                header('Content-Type: text/html; charset=utf-8');
                echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'><style>
                        body { font-family: sans-serif; background: #f8fafc; padding: 2rem; color: #1e293b; user-select: none; }
                        .paper-container { max-width: 820px; margin: 0 auto; background: white; padding: 2rem; border-radius: 6px; border: 1px solid #cbd5e1; white-space: pre-wrap; line-height: 1.6; font-size: 0.92rem; }
                      </style></head><body>
                      <div class='paper-container'>" . htmlspecialchars($text ?? '') . "</div></body></html>";
                exit;
            }
        } else {
            header('Content-Type: application/octet-stream');
            readfile($fullPath);
        }
        exit;
    }

    public function crear(): array {
        require_once CORE_PATH . 'Security/Auth.php';
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $accion = !empty($_GET['accion']) ? trim($_GET['accion']) : 'listar';
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;

        // Si la petición es POST y excede post_max_size, PHP vacía $_POST y $_FILES
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) && empty($_FILES) && !empty($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > 0) {
            $maxPostSize = ini_get('post_max_size') ?: 'desconocido';
            if (in_array($accion, ['extraer', 'crear_ajax', 'simular_extraccion'])) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'status' => 'error',
                    'message' => "El archivo enviado excede el tamaño máximo permitido por el servidor (post_max_size: {$maxPostSize})."
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
        }

        // Si es petición AJAX, responder con JSON si no se tienen permisos en lugar de 302 redirect
        if (in_array($accion, ['extraer', 'crear_ajax', 'simular_extraccion'])) {
            if (!Auth::requierePrivilegioMinimo($this->nivelAdmin, 'crear', 'RepositorioPST')) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Sesión expirada o permisos insuficientes para realizar esta acción.'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $submittedCsrf = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
                if (empty($submittedCsrf) || !hash_equals($_SESSION['csrf_token'], $submittedCsrf)) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Petición rechazada por seguridad: Token CSRF no válido o expirado.'
                    ], JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }
        } else {
            Auth::requierePrivilegioMinimo($this->nivelAdmin);
        }
        if ($accion === 'crear') Auth::requierePrivilegioMinimo($this->nivelAdmin, 'crear', 'RepositorioPST');
        if ($accion === 'editar') Auth::requierePrivilegioMinimo($this->nivelAdmin, 'editar', 'RepositorioPST');
        if ($accion === 'eliminar') Auth::requierePrivilegioMinimo($this->nivelAdmin, 'eliminar', 'RepositorioPST');

        $model = new DocumentoModel();
        
        // Directoria de destino garantizado
        $storageDir = BASE_PATH . '/storage/documentos/pst/';
        if (!file_exists($storageDir)) {
            mkdir($storageDir, 0777, true);
        }
        
        // 0. Procesar Acción: EXTRAER METADATOS DE ARCHIVOS Y GUARDAR ARCHIVO (AJAX)
        if ($accion === 'extraer' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json; charset=utf-8');
            try {
                if (!isset($_FILES['archivo_pst']) || $_FILES['archivo_pst']['error'] !== UPLOAD_ERR_OK) {
                    $errorCode = $_FILES['archivo_pst']['error'] ?? 'desconocido';
                    $errorMensajes = [
                        UPLOAD_ERR_INI_SIZE   => "El archivo excede el límite máximo permitido por el servidor PHP (upload_max_filesize: " . ini_get('upload_max_filesize') . ").",
                        UPLOAD_ERR_FORM_SIZE  => "El archivo excede el límite de tamaño especificado en el formulario.",
                        UPLOAD_ERR_PARTIAL    => "El archivo solo fue cargado parcialmente. Intente nuevamente.",
                        UPLOAD_ERR_NO_FILE    => "No se recibió ningún archivo para procesar.",
                        UPLOAD_ERR_NO_TMP_DIR => "Error del servidor: Falta la carpeta temporal de subida.",
                        UPLOAD_ERR_CANT_WRITE => "Error del servidor: Fallo al escribir el archivo temporal en disco.",
                        UPLOAD_ERR_EXTENSION  => "Una extensión del servidor detuvo la subida del archivo."
                    ];
                    $msgError = $errorMensajes[$errorCode] ?? ("Error al cargar el archivo en el servidor. Código: " . $errorCode);
                    throw new Exception($msgError);
                }

                // Validar tamaño máximo configurado en config_pst.json
                $maxMb = (int)ConfigService::get('archivos.max_size_mb', 20);
                $maxBytes = $maxMb * 1024 * 1024;
                if ($_FILES['archivo_pst']['size'] > $maxBytes) {
                    throw new Exception("El archivo excede el tamaño máximo permitido por el sistema ({$maxMb} MB).");
                }

                $fileTmpPath = $_FILES['archivo_pst']['tmp_name'];
                $fileName = $_FILES['archivo_pst']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (!in_array($fileExtension, ['pdf', 'docx'])) {
                    throw new Exception("Formato de archivo no soportado. Debe ser un archivo PDF o Word (.docx).");
                }

                // 1. Validar MIME-Type binario / Magic Bytes para prevenir archivos falsos o maliciosos
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $fileTmpPath);
                finfo_close($finfo);

                $allowedMimeTypes = [
                    'application/pdf',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/zip', // Algunos sistemas detectan docx como zip
                    'application/x-zip-compressed'
                ];

                if (!in_array($mimeType, $allowedMimeTypes)) {
                    throw new Exception("El contenido binario del archivo no corresponde a un documento PDF o Word válido.");
                }

                require_once __DIR__ . '/../services/ExtractorPST.php';

                $text = '';
                if ($fileExtension === 'pdf') {
                    $text = ExtractorPST::extraerTextoPDF($fileTmpPath);
                } else {
                    $text = ExtractorPST::extraerTextoDOCX($fileTmpPath);
                }

                if (empty(trim($text))) {
                    throw new Exception("No se pudo extraer texto del documento. Asegúrese de que el archivo no esté protegido o escaneado como imagen pura sin OCR.");
                }

                $datosExtraidos = ExtractorPST::analizarTexto($text, $fileName);

                // Guardar permanentemente en storage/documentos/pst/
                $slugTitle = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', substr($datosExtraidos['titulo'] ?? $fileName, 0, 30)));
                $savedFileName = 'pst_' . $slugTitle . '_' . time() . '_' . mt_rand(100, 999) . '.' . $fileExtension;
                $targetFile = $storageDir . $savedFileName;
                
                if (move_uploaded_file($fileTmpPath, $targetFile) || copy($fileTmpPath, $targetFile)) {
                    $datosExtraidos['archivo_pdf'] = 'storage/documentos/pst/' . $savedFileName;
                } else {
                    throw new Exception("No se pudo guardar el archivo de forma permanente en el almacenamiento del servidor.");
                }

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Metadatos extraídos e indexados con éxito.',
                    'data' => array_merge($datosExtraidos, ['texto_raw' => $text])
                ], JSON_UNESCAPED_UNICODE);
                
            } catch (Exception $e) {
                // Registrar el fallo en la auditoría WAF antes de devolver el error al cliente
                AuditLogger::registrar('WARNING', 'RepositorioPST', 'Fallo en Extracción Masiva', $e->getMessage());
                
                echo json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], JSON_UNESCAPED_UNICODE);
            }
            exit;
        }

        // 0.1 Procesar Acción: SIMULAR EXTRACCIÓN (Prueba de Extracción sin Guardar en Servidor/BD)
        if ($accion === 'simular_extraccion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json; charset=utf-8');
            try {
                if (!isset($_FILES['archivo_pst']) || $_FILES['archivo_pst']['error'] !== UPLOAD_ERR_OK) {
                    throw new Exception("Seleccione un archivo válido para simular la extracción.");
                }

                $fileTmpPath = $_FILES['archivo_pst']['tmp_name'];
                $fileName = $_FILES['archivo_pst']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (!in_array($fileExtension, ['pdf', 'docx'])) {
                    throw new Exception("Formato no soportado. Debe ser PDF o Word (.docx).");
                }

                require_once __DIR__ . '/../services/ExtractorPST.php';
                $text = ($fileExtension === 'pdf') ? ExtractorPST::extraerTextoPDF($fileTmpPath) : ExtractorPST::extraerTextoDOCX($fileTmpPath);

                if (empty(trim($text))) {
                    throw new Exception("No se pudo extraer texto del archivo.");
                }

                $datosExtraidos = ExtractorPST::analizarTexto($text, $fileName);

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Simulación ejecutada con éxito.',
                    'data' => $datosExtraidos,
                    'preview_texto' => mb_substr($text, 0, 800) . '...'
                ], JSON_UNESCAPED_UNICODE);

            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
            }
            exit;
        }

        // 0.2 Procesar Acción: BUSCAR PERSONA POR CÉDULA (Autocompletado)
        if ($accion === 'buscar_cedula') {
            header('Content-Type: application/json; charset=utf-8');
            $ced = !empty($_GET['cedula']) ? trim($_GET['cedula']) : '';
            $tipo = !empty($_GET['tipo']) ? trim($_GET['tipo']) : 'autor';
            
            $nombre = $model->getPersonaByCedula($ced, $tipo);
            echo json_encode([
                'status' => $nombre ? 'success' : 'not_found',
                'nombre' => $nombre ?? ''
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // 0.2.1 Procesar Acción: OBTENER LÍNEAS DE INVESTIGACIÓN POR CARRERA (AJAX)
        if ($accion === 'obtener_lineas') {
            header('Content-Type: application/json; charset=utf-8');
            $carreraIdReq = !empty($_GET['carrera_id']) ? (int)$_GET['carrera_id'] : null;
            $lineasCarrera = $model->getLineasInvestigacion($carreraIdReq);
            echo json_encode([
                'status' => 'success',
                'lineas' => $lineasCarrera
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // 0.1 Procesar Acción: CREAR VIA AJAX (SUBIDA EN LOTE)
        if ($accion === 'crear_ajax' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json; charset=utf-8');
            try {
                $rawInput = file_get_contents('php://input');
                $postData = json_decode($rawInput, true);
                if (!is_array($postData)) {
                    $postData = $_POST;
                }

                $autores = [];
                if (!empty($postData['autores']) && is_array($postData['autores'])) {
                    foreach ($postData['autores'] as $autor) {
                        $ced = !empty($autor['cedula']) ? trim($autor['cedula']) : '';
                        $nom = !empty($autor['nombre']) ? trim($autor['nombre']) : (!empty($autor['nombre_completo']) ? trim($autor['nombre_completo']) : '');
                        if ($ced !== '' && $nom !== '') {
                            $autores[] = ['cedula' => $ced, 'nombre' => $nom];
                        }
                    }
                }

                $nivelPost = !empty($postData['nivel_academico']) ? trim($postData['nivel_academico']) : 'Pregrado';
                $trayectoPost = ($nivelPost === 'Pregrado') ? (!empty($postData['trayecto']) ? trim($postData['trayecto']) : 'Trayecto I') : null;

                $rawUrlGit = !empty($postData['url_repositorio']) ? trim($postData['url_repositorio']) : null;
                $urlGitSanitizada = null;
                if (!empty($rawUrlGit) && filter_var($rawUrlGit, FILTER_VALIDATE_URL)) {
                    $scheme = strtolower(parse_url($rawUrlGit, PHP_URL_SCHEME) ?? '');
                    if (in_array($scheme, ['http', 'https'])) {
                        $urlGitSanitizada = $rawUrlGit;
                    }
                }

                $archivoPdf = !empty($postData['archivo_pdf']) ? trim($postData['archivo_pdf']) : null;

                $datos = [
                    'titulo'                     => !empty($postData['titulo']) ? trim($postData['titulo']) : '',
                    'anio_publicacion'           => !empty($postData['anio_publicacion']) ? (int)$postData['anio_publicacion'] : (int)date('Y'),
                    'autores'                    => $autores,
                    'tutor_academico_cedula'     => !empty($postData['tutor_academico_cedula']) ? trim($postData['tutor_academico_cedula']) : '',
                    'tutor_academico_nombre'     => !empty($postData['tutor_academico_nombre']) ? trim($postData['tutor_academico_nombre']) : '',
                    'tutor_institucional_cedula' => !empty($postData['tutor_institucional_cedula']) ? trim($postData['tutor_institucional_cedula']) : '',
                    'tutor_institucional_nombre' => !empty($postData['tutor_institucional_nombre']) ? trim($postData['tutor_institucional_nombre']) : '',
                    'tutor_comunitario_cedula'   => !empty($postData['tutor_comunitario_cedula']) ? trim($postData['tutor_comunitario_cedula']) : '',
                    'tutor_comunitario_nombre'   => !empty($postData['tutor_comunitario_nombre']) ? trim($postData['tutor_comunitario_nombre']) : '',
                    'fecha_defensa'              => !empty($postData['fecha_defensa']) ? trim($postData['fecha_defensa']) : date('Y-m-d'),
                    'nivel_academico'            => $nivelPost,
                    'trayecto'                   => $trayectoPost,
                    'url_repositorio'            => $urlGitSanitizada,
                    'archivo_pdf'                => $archivoPdf,
                    'resumen'                    => !empty($postData['resumen']) ? trim($postData['resumen']) : '',
                    'obj_general'                => !empty($postData['obj_general']) ? trim($postData['obj_general']) : null,
                    'comunidad_beneficiada'      => !empty($postData['comunidad_beneficiada']) ? trim($postData['comunidad_beneficiada']) : '',
                    'palabras_clave'             => !empty($postData['palabras_clave']) ? trim($postData['palabras_clave']) : '',
                    'id_carrera'                 => !empty($postData['id_carrera']) ? (int)$postData['id_carrera'] : 1,
                    'linea_id'                   => !empty($postData['linea_id']) ? (int)$postData['linea_id'] : null,
                    'dimension_id'               => !empty($postData['dimension_id']) ? (int)$postData['dimension_id'] : null,
                ];

                if (empty($datos['titulo'])) {
                    throw new Exception("El título del proyecto es obligatorio.");
                }

                if ($model->existePSTPorTitulo($datos['titulo'])) {
                    throw new Exception("Ya existe un proyecto registrado con el título: '" . $datos['titulo'] . "'");
                }

                if (empty($datos['archivo_pdf'])) {
                    throw new Exception("El archivo digital del documento (PDF/DOCX) es obligatorio.");
                }

                $realFilePath = BASE_PATH . '/' . ltrim($datos['archivo_pdf'], '/');
                if (!file_exists($realFilePath)) {
                    throw new Exception("El archivo digital asociado no se encuentra en el almacenamiento del servidor. Extraiga el documento nuevamente.");
                }

                if (empty($datos['autores'])) {
                    throw new Exception("Debe registrar al menos un autor principal (cédula y nombre).");
                }

                if (empty($datos['resumen'])) {
                    throw new Exception("El resumen del proyecto es obligatorio.");
                }

                if (empty($datos['linea_id'])) {
                    throw new Exception("Debe clasificar el proyecto bajo una línea de investigación.");
                }

                $nuevoId = $model->crearPST($datos);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Proyecto cargado con éxito en el catálogo.',
                    'id' => $nuevoId
                ], JSON_UNESCAPED_UNICODE);

            } catch (Exception $e) {
                require_once CORE_PATH . 'Security/Auth.php';
                $tituloFallo = !empty($datos['titulo']) ? $datos['titulo'] : 'Proyecto Sin Título';
                AuditLogger::registrar('WARNING', 'RepositorioPST', 'Fallo en Extracción Masiva', "Error al intentar guardar el PST '{$tituloFallo}': " . $e->getMessage());
                
                echo json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], JSON_UNESCAPED_UNICODE);
            }
            exit;
        }
        
        $error = null;
        $success = null;
        
        // Cargar mensajes de éxito desde la redirección anterior
        if (isset($_GET['msg'])) {
            if ($_GET['msg'] === 'deleted') {
                $success = "El recurso indexado ha sido eliminado exitosamente del catálogo.";
            } elseif ($_GET['msg'] === 'created') {
                $success = "El Proyecto Socio-Tecnológico ha sido registrado con éxito en el catálogo.";
            } elseif ($_GET['msg'] === 'updated') {
                $success = "El recurso ha sido modificado y actualizado exitosamente.";
            } elseif ($_GET['msg'] === 'status_changed') {
                $success = "El estado de visibilidad del recurso se ha actualizado correctamente.";
            }
        }

        // 0.3 Procesar Acción: ALTERNAR ESTADO (Activar / Ocultar - Soft Delete)
        if ($accion === 'toggle_estado' && $id) {
            Auth::requierePrivilegioMinimo($this->nivelAdmin, 'editar', 'RepositorioPST');
            try {
                $docActual = $model->getPSTDocumentoById($id);
                if ($docActual) {
                    $nuevoEstado = !($docActual['activo'] ?? true);
                    $model->cambiarEstadoPST($id, $nuevoEstado);
                    
                    $estTxt = $nuevoEstado ? 'Visible' : 'Oculto';
                    AuditLogger::registrar('INFO', 'RepositorioPST', 'Alternar Visibilidad Proyecto', "Proyecto ID #{$id} cambiado a estado: {$estTxt}.");

                    header("Location: ?ruta=agregar-documento&msg=status_changed");
                    exit;
                }
            } catch (Exception $e) {
                $error = "Error al cambiar el estado del recurso: " . $e->getMessage();
                $accion = 'listar';
            }
        }
        
        // 1. Procesar Acción: ELIMINAR
        if ($accion === 'eliminar' && $id) {
            Auth::requierePrivilegioMinimo($this->nivelAdmin, 'eliminar', 'RepositorioPST');
            try {
                $model->eliminarPST($id);
                
                AuditLogger::registrar('WARNING', 'RepositorioPST', 'Eliminar Proyecto', "Proyecto PST ID #{$id} eliminado del repositorio.");

                header("Location: ?ruta=agregar-documento&msg=deleted");
                exit;
            } catch (Exception $e) {
                $error = "Error al intentar eliminar el recurso: " . $e->getMessage();
                $accion = 'listar';
            }
        }
        
        // 2. Procesar Acción: CREAR / REGISTRAR (Formulario Tradicional POST)
        if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $autores = [];
            if (!empty($_POST['autor_cedula']) && is_array($_POST['autor_cedula'])) {
                for ($i = 0; $i < count($_POST['autor_cedula']); $i++) {
                    $ced = !empty($_POST['autor_cedula'][$i]) ? trim($_POST['autor_cedula'][$i]) : '';
                    $nom = !empty($_POST['autor_nombre'][$i]) ? trim($_POST['autor_nombre'][$i]) : '';
                    if ($ced !== '' && $nom !== '') {
                        $autores[] = ['cedula' => $ced, 'nombre' => $nom];
                    }
                }
            }

            // Manejo de archivo adjunto si se subió en el form tradicional
            $archivoPath = null;
            if (isset($_FILES['archivo_pst'])) {
                if ($_FILES['archivo_pst']['error'] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES['archivo_pst']['name'], PATHINFO_EXTENSION));
                    if (in_array($ext, ['pdf', 'docx'])) {
                        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', substr($_POST['titulo'] ?? 'pst', 0, 30)));
                        $destName = 'pst_' . $slug . '_' . time() . '.' . $ext;
                        if (move_uploaded_file($_FILES['archivo_pst']['tmp_name'], $storageDir . $destName)) {
                            $archivoPath = 'storage/documentos/pst/' . $destName;
                        } else {
                            AuditLogger::registrar('WARNING', 'RepositorioPST', 'Fallo de Escritura', "El archivo para el proyecto se recibió pero el servidor no pudo guardarlo en el disco.");
                        }
                    } else {
                        AuditLogger::registrar('WARNING', 'RepositorioPST', 'Fallo de Formato', "Intento de subir un archivo con extensión no permitida (.{$ext}). Archivo ignorado.");
                    }
                } elseif ($_FILES['archivo_pst']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $codigoError = $_FILES['archivo_pst']['error'];
                    $tituloTemporal = !empty($_POST['titulo']) ? trim($_POST['titulo']) : 'Sin Título';
                    AuditLogger::registrar('WARNING', 'RepositorioPST', 'Fallo en Subida de Archivo', "El PST '{$tituloTemporal}' se procesó, pero el documento fue rechazado (Código: {$codigoError}).");
                }
            }

            $nivelPost = !empty($_POST['nivel_academico']) ? trim($_POST['nivel_academico']) : 'Pregrado';
            $trayectoPost = ($nivelPost === 'Pregrado') ? (!empty($_POST['trayecto']) ? trim($_POST['trayecto']) : 'Trayecto I') : null;
            $finalPdfPath = $archivoPath ? $archivoPath : (!empty($_POST['archivo_pdf']) ? trim($_POST['archivo_pdf']) : null);

            $datos = [
                'titulo'                     => !empty($_POST['titulo']) ? trim($_POST['titulo']) : '',
                'anio_publicacion'           => !empty($_POST['anio_publicacion']) ? (int)$_POST['anio_publicacion'] : (int)date('Y'),
                'autores'                    => $autores,
                'tutor_academico_cedula'     => !empty($_POST['tutor_academico_cedula']) ? trim($_POST['tutor_academico_cedula']) : '',
                'tutor_academico_nombre'     => !empty($_POST['tutor_academico_nombre']) ? trim($_POST['tutor_academico_nombre']) : '',
                'tutor_institucional_cedula' => !empty($_POST['tutor_institucional_cedula']) ? trim($_POST['tutor_institucional_cedula']) : '',
                'tutor_institucional_nombre' => !empty($_POST['tutor_institucional_nombre']) ? trim($_POST['tutor_institucional_nombre']) : '',
                'tutor_comunitario_cedula'   => !empty($_POST['tutor_comunitario_cedula']) ? trim($_POST['tutor_comunitario_cedula']) : '',
                'tutor_comunitario_nombre'   => !empty($_POST['tutor_comunitario_nombre']) ? trim($_POST['tutor_comunitario_nombre']) : '',
                'fecha_defensa'              => !empty($_POST['fecha_defensa']) ? trim($_POST['fecha_defensa']) : date('Y-m-d'),
                'nivel_academico'            => $nivelPost,
                'trayecto'                   => $trayectoPost,
                'url_repositorio'            => !empty($_POST['url_repositorio']) ? trim($_POST['url_repositorio']) : null,
                'archivo_pdf'                => $finalPdfPath,
                'resumen'                    => !empty($_POST['resumen']) ? trim($_POST['resumen']) : '',
                'obj_general'                => !empty($_POST['obj_general']) ? trim($_POST['obj_general']) : null,
                'comunidad_beneficiada'      => !empty($_POST['comunidad_beneficiada']) ? trim($_POST['comunidad_beneficiada']) : '',
                'palabras_clave'             => !empty($_POST['palabras_clave']) ? trim($_POST['palabras_clave']) : '',
                'id_carrera'                 => !empty($_POST['id_carrera']) ? (int)$_POST['id_carrera'] : 1,
                'linea_id'                   => !empty($_POST['linea_id']) ? (int)$_POST['linea_id'] : null,
                'dimension_id'               => !empty($_POST['dimension_id']) ? (int)$_POST['dimension_id'] : null,
            ];
            
            if (empty($datos['titulo'])) {
                $error = "El título de la investigación es obligatorio.";
            } elseif ($model->existePSTPorTitulo($datos['titulo'])) {
                $error = "Ya existe un proyecto registrado con este título en el repositorio.";
            } elseif (empty($datos['autores'])) {
                $error = "Debe registrar al menos un autor principal (cédula y nombre).";
            } elseif (empty($datos['resumen'])) {
                $error = "El resumen del proyecto es obligatorio.";
            } elseif (empty($datos['linea_id'])) {
                $error = "Debe clasificar el proyecto bajo una línea de investigación.";
            } elseif (empty($datos['archivo_pdf'])) {
                $error = "Debe adjuntar o extraer un documento digital (PDF o Word) válido para registrar el proyecto.";
            } else {
                try {
                    $nuevoId = (int)$model->crearPST($datos);
                    if ($nuevoId > 0) {
                        AuditLogger::registrar('INFO', 'RepositorioPST', 'Registrar Proyecto', "Proyecto PST registrado exitosamente: '{$datos['titulo']}' (ID: #{$nuevoId}).");
                        header("Location: ?ruta=agregar-documento&accion=crear&msg=created");
                        exit;
                    } else {
                        $error = "Ocurrió un error interno en la base de datos al registrar el recurso.";
                    }
                } catch (Exception $e) {
                    $error = "Error de Base de Datos: " . $e->getMessage();
                }
            }
        }
        
        // 3. Procesar Acción: EDITAR
        $documento = null;
        $autores = [];
        $tutores = [
            'academico' => ['cedula' => '', 'nombre' => ''],
            'institucional' => ['cedula' => '', 'nombre' => ''],
            'comunitario' => ['cedula' => '', 'nombre' => '']
        ];
        
        if ($accion === 'editar' && $id) {
            $documento = $model->getPSTDocumentoById($id);
            if (!$documento) {
                $error = "El recurso solicitado para edición no existe.";
                $accion = 'listar';
            } else {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $autoresPost = [];
                    if (!empty($_POST['autor_cedula']) && is_array($_POST['autor_cedula'])) {
                        for ($i = 0; $i < count($_POST['autor_cedula']); $i++) {
                            $ced = !empty($_POST['autor_cedula'][$i]) ? trim($_POST['autor_cedula'][$i]) : '';
                            $nom = !empty($_POST['autor_nombre'][$i]) ? trim($_POST['autor_nombre'][$i]) : '';
                            if ($ced !== '' && $nom !== '') {
                                $autoresPost[] = ['cedula' => $ced, 'nombre' => $nom];
                            }
                        }
                    }

                    $archivoPath = null;
                    if (isset($_FILES['archivo_pst'])) {
                        if ($_FILES['archivo_pst']['error'] === UPLOAD_ERR_OK) {
                            $ext = strtolower(pathinfo($_FILES['archivo_pst']['name'], PATHINFO_EXTENSION));
                            if (in_array($ext, ['pdf', 'docx'])) {
                                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                                $mimeType = finfo_file($finfo, $_FILES['archivo_pst']['tmp_name']);
                                finfo_close($finfo);

                                $allowedMimeTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/x-zip-compressed'];

                                if (in_array($mimeType, $allowedMimeTypes)) {
                                    $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', substr($_POST['titulo'] ?? 'pst', 0, 30)));
                                    $destName = 'pst_' . $slug . '_' . time() . '.' . $ext;
                                    if (move_uploaded_file($_FILES['archivo_pst']['tmp_name'], $storageDir . $destName)) {
                                        $archivoPath = 'storage/documentos/pst/' . $destName;
                                    } else {
                                        AuditLogger::registrar('WARNING', 'RepositorioPST', 'Fallo de Escritura (Edición)', "El nuevo adjunto para el proyecto ID #{$id} no pudo guardarse en el disco.");
                                    }
                                } else {
                                    $error = "El contenido binario del archivo no corresponde a un documento PDF o Word válido.";
                                    AuditLogger::registrar('WARNING', 'RepositorioPST', 'Fallo de MIME Type', "Intento de subir un archivo inválido o manipulado (MIME: {$mimeType}) en el proyecto ID #{$id}.");
                                }
                            } else {
                                AuditLogger::registrar('WARNING', 'RepositorioPST', 'Fallo de Formato (Edición)', "Intento de subir un archivo no permitido (.{$ext}) en el proyecto ID #{$id}.");
                            }
                        } elseif ($_FILES['archivo_pst']['error'] !== UPLOAD_ERR_NO_FILE) {
                            $codigoError = $_FILES['archivo_pst']['error'];
                            $tituloTemporal = !empty($_POST['titulo']) ? trim($_POST['titulo']) : 'Sin Título';
                            AuditLogger::registrar('WARNING', 'RepositorioPST', 'Fallo en Subida de Archivo (Edición)', "El PST '{$tituloTemporal}' se actualizó, pero el nuevo documento fue rechazado (Código: {$codigoError}).");
                        }
                    }

                    $nivelEdit = !empty($_POST['nivel_academico']) ? trim($_POST['nivel_academico']) : ($documento['nivel_academico'] ?? 'Pregrado');
                    $trayectoEdit = ($nivelEdit === 'Pregrado') ? (!empty($_POST['trayecto']) ? trim($_POST['trayecto']) : 'Trayecto I') : null;
                    $finalEditPdf = $archivoPath ? $archivoPath : (!empty($_POST['archivo_pdf']) ? trim($_POST['archivo_pdf']) : ($documento['archivo_pdf'] ?? null));

                    $rawUrlGitEdit = !empty($_POST['url_repositorio']) ? trim($_POST['url_repositorio']) : null;
                    $urlGitEditSanitizada = null;
                    if (!empty($rawUrlGitEdit) && filter_var($rawUrlGitEdit, FILTER_VALIDATE_URL)) {
                        $scheme = strtolower(parse_url($rawUrlGitEdit, PHP_URL_SCHEME) ?? '');
                        if (in_array($scheme, ['http', 'https'])) {
                            $urlGitEditSanitizada = $rawUrlGitEdit;
                        }
                    }

                    $datos = [
                        'titulo'                     => !empty($_POST['titulo']) ? trim($_POST['titulo']) : '',
                        'anio_publicacion'           => !empty($_POST['anio_publicacion']) ? (int)$_POST['anio_publicacion'] : (int)date('Y'),
                        'autores'                    => $autoresPost,
                        'tutor_academico_cedula'     => !empty($_POST['tutor_academico_cedula']) ? trim($_POST['tutor_academico_cedula']) : '',
                        'tutor_academico_nombre'     => !empty($_POST['tutor_academico_nombre']) ? trim($_POST['tutor_academico_nombre']) : '',
                        'tutor_institucional_cedula' => !empty($_POST['tutor_institucional_cedula']) ? trim($_POST['tutor_institucional_cedula']) : '',
                        'tutor_institucional_nombre' => !empty($_POST['tutor_institucional_nombre']) ? trim($_POST['tutor_institucional_nombre']) : '',
                        'tutor_comunitario_cedula'   => !empty($_POST['tutor_comunitario_cedula']) ? trim($_POST['tutor_comunitario_cedula']) : '',
                        'tutor_comunitario_nombre'   => !empty($_POST['tutor_comunitario_nombre']) ? trim($_POST['tutor_comunitario_nombre']) : '',
                        'fecha_defensa'              => !empty($_POST['fecha_defensa']) ? trim($_POST['fecha_defensa']) : date('Y-m-d'),
                        'nivel_academico'            => $nivelEdit,
                        'trayecto'                   => $trayectoEdit,
                        'url_repositorio'            => $urlGitEditSanitizada,
                        'archivo_pdf'                => $finalEditPdf,
                        'resumen'                    => !empty($_POST['resumen']) ? trim($_POST['resumen']) : '',
                        'obj_general'                => !empty($_POST['obj_general']) ? trim($_POST['obj_general']) : null,
                        'comunidad_beneficiada'      => !empty($_POST['comunidad_beneficiada']) ? trim($_POST['comunidad_beneficiada']) : '',
                        'palabras_clave'             => !empty($_POST['palabras_clave']) ? trim($_POST['palabras_clave']) : '',
                        'id_carrera'                 => !empty($_POST['id_carrera']) ? (int)$_POST['id_carrera'] : 1,
                        'linea_id'                   => !empty($_POST['linea_id']) ? (int)$_POST['linea_id'] : null,
                        'dimension_id'               => !empty($_POST['dimension_id']) ? (int)$_POST['dimension_id'] : null,
                    ];
                    
                    if (empty($datos['titulo'])) {
                        $error = "El título de la investigación es obligatorio.";
                    } elseif ($model->existePSTPorTitulo($datos['titulo'], $id)) {
                        $error = "Ya existe otro proyecto registrado con este título en el repositorio.";
                    } elseif (empty($datos['autores'])) {
                        $error = "Debe registrar al menos un autor principal (cédula y nombre).";
                    } elseif (empty($datos['resumen'])) {
                        $error = "El resumen del proyecto es obligatorio.";
                    } elseif (empty($datos['linea_id'])) {
                        $error = "Debe clasificar el proyecto bajo una línea de investigación.";
                    } else {
                        try {
                            $editado = $model->editarPST($id, $datos);
                            if ($editado) {
                                header("Location: ?ruta=agregar-documento&msg=updated");
                                echo "<script>window.location.href='?ruta=agregar-documento&msg=updated';</script>";
                                exit;
                            }
                        } catch (Exception $e) {
                            $error = "Error de Base de Datos al actualizar: " . $e->getMessage();
                        }
                    }
                } else {
                    $autores = $model->getAutoresByRecurso($id);
                    while (count($autores) < 4) {
                        $autores[] = ['nombre_completo' => '', 'cedula' => ''];
                    }
                    
                    $tutoresBD = $model->getTutoresByRecurso($id);
                    foreach ($tutoresBD as $tBD) {
                        if ($tBD['tipo_tutor_id'] == 3) {
                            $tutores['academico'] = ['cedula' => $tBD['cedula'], 'nombre' => $tBD['nombre_completo']];
                        } elseif ($tBD['tipo_tutor_id'] == 2) {
                            $tutores['institucional'] = ['cedula' => $tBD['cedula'], 'nombre' => $tBD['nombre_completo']];
                        } elseif ($tBD['tipo_tutor_id'] == 4) {
                            $tutores['comunitario'] = ['cedula' => $tBD['cedula'], 'nombre' => $tBD['nombre_completo']];
                        }
                    }
                }
            }
        }

        // 4. Cargar Listado Principal de Gestión
        $documentos = [];
        $pagination = [];
        $q = !empty($_GET['q']) ? trim($_GET['q']) : '';
        
        if ($accion === 'listar') {
            $limit = 10;
            $page = !empty($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $offset = ($page - 1) * $limit;
            
            if (!empty($q)) {
                $documentos = $model->buscarStandard($q, ['activo' => 'todos'], $limit, $offset);
                $totalDocs = $model->buscarStandardCount($q, ['activo' => 'todos']);
            } else {
                $documentos = $model->getPSTDocumentos(['activo' => 'todos'], $limit, $offset);
                $totalDocs = $model->getPSTDocumentosCount(['activo' => 'todos']);
            }
            
            $totalPages = ceil($totalDocs / $limit);
            $pagination = [
                'current_page' => $page,
                'total_pages'  => $totalPages,
                'total_items'  => $totalDocs,
                'limit'        => $limit
            ];
        }

        $carreras = $model->getCarreras();
        $lineas = $model->getLineasInvestigacion();
        $dimensiones = $model->getDimensionesOperativas();
        $nivelesAcademicos = $model->getNivelesAcademicos();
        $trayectosList = $model->getTrayectos();
        
        $statsResumen = $model->getPSTStatsResumen();
        
        return [
            'accion'            => $accion,
            'documentos'        => $documentos,
            'documento'         => $documento,
            'autores'           => $autores,
            'tutores'           => $tutores,
            'carreras'          => $carreras,
            'lineas'            => $lineas,
            'dimensiones'       => $dimensiones,
            'nivelesAcademicos' => $nivelesAcademicos,
            'trayectosList'     => $trayectosList,
            'pagination'        => $pagination,
            'statsResumen'      => $statsResumen,
            'q'                 => $q,
            'error'             => $error,
            'success'           => $success
        ];
    }
}
