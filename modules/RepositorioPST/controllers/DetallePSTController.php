<?php
// modules/RepositorioPST/controllers/DetallePSTController.php
require_once __DIR__ . '/../models/DocumentoModel.php';
require_once __DIR__ . '/../services/ConfigService.php';
require_once __DIR__ . '/../../SuperAdmin/services/SystemConfigService.php';
require_once CORE_PATH . 'Security/Auth.php';
require_once CORE_PATH . 'Security/AuditLogger.php';

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
            'orden'           => !empty($_GET['orden']) ? trim($_GET['orden']) : ConfigService::get('buscador.orden_predeterminado', 'anio_desc'),
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
        $trayectosList = $model->getTrayectos($carreraId ?: 1);
        
        return [
            'documentos'        => $documentos,
            'lineas'            => $lineas,
            'dimensiones'       => $dimensiones,
            'comunidades'       => $comunidades,
            'carreras'          => $carreras,
            'nivelesAcademicos'  => $nivelesAcademicos,
            'nivelesConTrayecto' => $model->getNivelesConTrayecto(),
            'labelsNiveles'      => $model->getNivelAcademicoMap(),
            'trayectosList'      => $trayectosList,
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
                // Registrar visualización única por sesión para evitar inflación por recargas (F5)
                if (session_status() === PHP_SESSION_NONE) {
                    @session_start();
                }
                if (empty($_SESSION['visto_pst_' . $id])) {
                    $model->incrementarVistasPST($id);
                    $_SESSION['visto_pst_' . $id] = true;
                    $documento['vistas'] = ((int)($documento['vistas'] ?? 0)) + 1;
                }

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

        if (!$documento && !headers_sent()) {
            http_response_code(404);
        }
        
        return [
            'documento'          => $documento,
            'proyectosSimilares' => $proyectosSimilares,
            'proyectosComunidad' => $proyectosComunidad,
            'conteoComunidad'    => $conteoComunidad,
            'nivelesConTrayecto' => $model->getNivelesConTrayecto(),
            'labelsNiveles'      => $model->getNivelAcademicoMap()
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
        $esDescarga = (!empty($_GET['download']) && $_GET['download'] === '1') || (!empty($_GET['descargar']) && $_GET['descargar'] === '1');
        $esRaw = (!empty($_GET['raw']) && $_GET['raw'] === '1');

        $fullPath = '';
        $safeFilename = 'documento';
        $doc = null;

        if (!empty($requestedFile)) {
            $relPath = ltrim(str_replace(['\\', '/'], '/', $requestedFile), '/');
            $extPermitidas = ['pdf', 'docx', 'doc'];
            $extReq = strtolower(pathinfo($relPath, PATHINFO_EXTENSION));

            // Restricción estricta de seguridad: Solo archivos de documentos PST autorizados
            $esRutaPermitida = (strpos($relPath, 'storage/documentos/pst/') === 0 || strpos($relPath, 'storage/documentos/tmp/') === 0);
            if (strpos($relPath, '..') === false && $esRutaPermitida && in_array($extReq, $extPermitidas, true)) {
                $candidate = ConfigService::resolverRutaArchivoFisico($relPath);
                if ($candidate && is_file($candidate)) {
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
            $extDoc = strtolower(pathinfo($relPath, PATHINFO_EXTENSION));
            $extPermitidas = ['pdf', 'docx', 'doc'];
            
            // Prevención de Path Traversal y validación de extensión autorizada
            if (strpos($relPath, '..') !== false || (!empty($extDoc) && !in_array($extDoc, $extPermitidas, true))) {
                http_response_code(403);
                die("Acceso denegado: Ruta o formato de archivo no permitido.");
            }

            $candidate = ConfigService::resolverRutaArchivoFisico($relPath);
            if ($candidate && is_file($candidate)) {
                $fullPath = $candidate;
                $safeFilename = pathinfo($fullPath, PATHINFO_BASENAME);
            }
        }

        if (empty($fullPath) || !is_file($fullPath)) {
            if ($esDescarga || $esRaw) {
                http_response_code(404);
                header('Content-Type: text/html; charset=utf-8');
                echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'><title>Archivo No Disponible</title><style>body { font-family: system-ui, -apple-system, sans-serif; background: #f8fafc; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; } .card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 2rem; max-width: 480px; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.05); } h3 { color: #be123c; margin-top: 0; } p { color: #475569; font-size: 0.92rem; line-height: 1.5; } .btn { display: inline-block; margin-top: 1rem; padding: 0.5rem 1rem; background: #002244; color: white; text-decoration: none; border-radius: 6px; font-size: 0.85rem; font-weight: 600; }</style></head><body><div class='card'><h3>Documento Físico No Disponible</h3><p>Este proyecto está registrado en la base de datos pero su archivo digital no se encuentra disponible físicamente en el almacenamiento del servidor.</p><a href='javascript:history.back()' class='btn'>Regresar</a></div></body></html>";
                exit;
            }

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

        // 1. Verificación de política de descarga directa
        $puedeDescargar = ConfigService::puedeDescargar();

        if ($esDescarga && !$puedeDescargar) {
            http_response_code(403);
            header('Content-Type: text/html; charset=utf-8');
            echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'><title>Descarga Restringida</title><style>body { font-family: system-ui, -apple-system, sans-serif; background: #f8fafc; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; } .card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 2rem; max-width: 480px; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.05); } h3 { color: #be123c; margin-top: 0; } p { color: #475569; font-size: 0.92rem; line-height: 1.5; } .btn { display: inline-block; margin-top: 1rem; padding: 0.5rem 1rem; background: #002244; color: white; text-decoration: none; border-radius: 6px; font-size: 0.85rem; font-weight: 600; }</style></head><body><div class='card'><h3>Descarga Restringida</h3><p>Su nivel de usuario no cuenta con los privilegios requeridos para descargar este documento o la descarga se encuentra inhabilitada institucionalmente. Puede consultar el documento a través del visor web.</p><a href='javascript:history.back()' class='btn'>Regresar</a></div></body></html>";
            exit;
        }

        if ($esDescarga && $puedeDescargar) {
            // Forzar descarga del archivo adjunto
            $mimeType = match($ext) {
                'pdf' => 'application/pdf',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'doc' => 'application/msword',
                default => 'application/octet-stream'
            };
            header('Content-Type: ' . $mimeType);
            header('Content-Disposition: attachment; filename="' . $safeFilename . '"');
            header('Content-Length: ' . filesize($fullPath));
            header('Cache-Control: private, no-cache, no-store, must-revalidate');
            readfile($fullPath);
            exit;
        }

        // Entrega de binario puro (?raw=1) para visores cliente (Mammoth, etc.)
        if ($esRaw) {
            $mimeType = match($ext) {
                'pdf' => 'application/pdf',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'doc' => 'application/msword',
                default => 'application/octet-stream'
            };
            header('Content-Type: ' . $mimeType);
            header('Content-Disposition: inline; filename="' . $safeFilename . '"');
            header('Content-Length: ' . filesize($fullPath));
            header('Cache-Control: public, max-age=3600');
            readfile($fullPath);
            exit;
        }

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
        } elseif ($ext === 'docx' || $ext === 'doc') {
            $mostrarToolbar = (bool)ConfigService::get('visor_pdf.mostrar_toolbar', true);
            $esDocx = ($ext === 'docx');
            $rawUrlParams = $_GET;
            $rawUrlParams['raw'] = '1';
            $rawUrl = '?' . http_build_query($rawUrlParams);

            $paragraphsHtml = '';
            if (!$esDocx) {
                require_once __DIR__ . '/../services/ExtractorPST.php';
                $docText = '';
                try {
                    $docText = ExtractorPST::extraerTextoDOC($fullPath);
                } catch (\Throwable $e) {
                    $docText = '';
                }
                foreach (explode("\n", $docText) as $pLine) {
                    $pLine = trim($pLine);
                    if ($pLine !== '' && stripos($pLine, 'PAGEREF _Toc') === false) {
                        $paragraphsHtml .= '<p>' . htmlspecialchars($pLine, ENT_QUOTES, 'UTF-8') . '</p>';
                    }
                }
                if ($paragraphsHtml === '') {
                    $paragraphsHtml = '<p><em>No se pudo generar la vista previa textual para este documento Word antiguo.</em></p>';
                }
            }

            header('Content-Type: text/html; charset=utf-8');
            echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'>
                  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                  <title>" . htmlspecialchars($safeFilename) . "</title>
                  <style>
                    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
                    :root {
                        --azul-oscuro: #002244;
                        --azul-inst: #505984;
                        --azul-claro: #7090CB;
                        --borde-suave: rgba(80, 89, 132, 0.22);
                    }
                    * { box-sizing: border-box; }
                    body { font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif; background-color: #f4f7fb; color: #1e293b; margin: 0; padding: 0; line-height: 1.7; }
                    .word-toolbar { position: sticky; top: 0; z-index: 200; background: #ffffff; border-bottom: 1px solid var(--borde-suave); padding: 0.55rem 1rem; display: flex; align-items: center; justify-content: space-between; gap: 0.65rem; flex-wrap: wrap; box-shadow: 0 4px 14px rgba(0, 34, 68, 0.06); }
                    .word-toolbar-group { display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap; }
                    .word-btn { background: #ffffff; color: var(--azul-inst); border: 1px solid var(--borde-suave); border-radius: 6px; padding: 0.35rem 0.65rem; font-size: 0.78rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem; transition: all 0.15s ease; }
                    .word-btn:hover, .word-btn.active { background: var(--azul-inst); color: #ffffff; border-color: var(--azul-inst); }
                    .word-search-box { display: flex; align-items: center; background: #ffffff; border: 1px solid var(--borde-suave); border-radius: 6px; padding: 0.15rem 0.45rem; gap: 0.3rem; }
                    .word-search-input { border: none; outline: none; font-size: 0.78rem; color: #1e293b; width: 165px; padding: 0.22rem 0.25rem; background: transparent; }
                    .word-search-count { font-size: 0.72rem; font-weight: 700; color: var(--azul-inst); min-width: 38px; text-align: center; }
                    .word-mini-btn { background: transparent; border: none; color: var(--azul-inst); cursor: pointer; font-size: 0.78rem; font-weight: 800; padding: 0.15rem 0.3rem; border-radius: 4px; }
                    .word-mini-btn:hover { background: rgba(112, 144, 203, 0.15); }
                    .word-stats-pill { font-size: 0.74rem; font-weight: 700; color: var(--azul-inst); background: #f4f8ff; border: 1px solid rgba(112, 144, 203, 0.3); padding: 0.28rem 0.6rem; border-radius: 6px; }
                    .word-workspace { display: flex; align-items: flex-start; min-height: calc(100vh - 54px); }
                    .word-outline-sidebar { width: 270px; flex-shrink: 0; background: #ffffff; border-right: 1px solid var(--borde-suave); position: sticky; top: 52px; height: calc(100vh - 52px); overflow-y: auto; padding: 0.9rem 0.75rem; display: none; }
                    .word-outline-sidebar.open { display: block; }
                    .word-outline-title { font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: var(--azul-oscuro); margin: 0 0 0.6rem 0; padding-bottom: 0.4rem; border-bottom: 2px solid var(--azul-inst); }
                    .word-outline-item { display: block; width: 100%; text-align: left; background: transparent; border: none; padding: 0.38rem 0.5rem; margin-bottom: 0.15rem; border-radius: 5px; font-size: 0.76rem; font-weight: 600; color: #334155; cursor: pointer; line-height: 1.35; }
                    .word-outline-item:hover { background: #f0f5ff; color: var(--azul-oscuro); }
                    .word-outline-item.level-2 { padding-left: 1rem; font-size: 0.73rem; }
                    .word-outline-item.level-3 { padding-left: 1.5rem; font-size: 0.71rem; color: #505984; }
                    .word-canvas { flex: 1; padding: 1.75rem 1.25rem 3rem 1.25rem; overflow-x: auto; }
                    .paper-container { max-width: 860px; margin: 0 auto; background: #ffffff; padding: 3rem 3.5rem; border-radius: 8px; box-shadow: 0 8px 24px rgba(0, 34, 68, 0.07); border: 1px solid var(--borde-suave); transition: max-width 0.2s ease, zoom 0.15s ease; word-break: break-word; }
                    .paper-container.wide-mode { max-width: 96%; }
                    .paper-container h1, .paper-container h2, .paper-container h3, .paper-container h4 { color: #002244; font-weight: 800; line-height: 1.3; }
                    .paper-container p { margin-bottom: 1rem; text-align: justify; font-size: 0.94rem; color: #334155; }
                    .paper-container a { color: var(--azul-inst); text-decoration: none; border-bottom: 1px dashed var(--azul-claro); cursor: pointer; }
                    .paper-container a:hover { color: var(--azul-oscuro); background-color: #f0f5ff; border-bottom-style: solid; }
                    .paper-container table { width: 100%; border-collapse: collapse; margin: 1.4rem 0; font-size: 0.86rem; background: #ffffff; }
                    .paper-container td, .paper-container th { border: 1px solid rgba(80, 89, 132, 0.25); padding: 0.6rem 0.8rem; }
                    .paper-container th { background: #f4f8ff; color: #002244; font-weight: 700; }
                    .paper-container img { max-width: 100%; height: auto; display: block; margin: 1.4rem auto; border-radius: 6px; border: 1px solid var(--borde-suave); }
                    .doc-loader { text-align: center; padding: 3rem; color: #505984; font-weight: 600; font-size: 0.95rem; }
                    mark.word-search-hit { background: rgba(112, 144, 203, 0.35); color: #002244; padding: 0 2px; border-radius: 3px; font-weight: 700; }
                    mark.word-search-hit.current { background: #505984; color: #ffffff; outline: 2px solid #7090CB; }
                    .word-target-flash { animation: flashTarget 1.6s ease; }
                    @keyframes flashTarget { 0% { background-color: rgba(112, 144, 203, 0.35); } 100% { background-color: transparent; } }
                    .btn-top-float { position: fixed; bottom: 1.2rem; right: 1.2rem; background: var(--azul-inst); color: #ffffff; border: none; border-radius: 8px; padding: 0.45rem 0.8rem; font-size: 0.78rem; font-weight: 700; cursor: pointer; box-shadow: 0 6px 16px rgba(0, 34, 68, 0.2); z-index: 150; }
                    @media print { .word-toolbar, .word-outline-sidebar, .btn-top-float { display: none !important; } body { padding: 0; background: #fff; } .word-canvas { padding: 0; } .paper-container { box-shadow: none; border: none; padding: 0; max-width: 100%; } }
                  </style>";
            if ($esDocx) {
                echo "<script src='modules/RepositorioPST/assets/js/mammoth.browser.min.js'></script>
                      <script>
                        if (typeof window.mammoth === 'undefined') {
                            document.write('<script src=\"https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.6.0/mammoth.browser.min.js\"><\/script>');
                        }
                      </script>";
            }
            echo "</head><body>";
            if ($mostrarToolbar) {
                echo "<div class='word-toolbar'>
                        <div class='word-toolbar-group'>
                            <button type='button' class='word-btn' id='btnToggleOutline' onclick='alternarEsquemaWord()'>☰ Capítulos e Índice</button>
                            <div class='word-search-box'>
                                <input type='text' id='wordSearchInput' class='word-search-input' placeholder='Buscar palabra en el texto...' oninput='ejecutarBusquedaWord()' onkeydown=\"if(event.key==='Enter'){event.preventDefault(); saltarCoincidenciaWord(1);}\">
                                <span id='wordSearchCount' class='word-search-count'>0 / 0</span>
                                <button type='button' class='word-mini-btn' onclick='saltarCoincidenciaWord(-1)' title='Anterior'>▲</button>
                                <button type='button' class='word-mini-btn' onclick='saltarCoincidenciaWord(1)' title='Siguiente'>▼</button>
                                <button type='button' class='word-mini-btn' onclick='limpiarBusquedaWord()' title='Limpiar'>✕</button>
                            </div>
                        </div>
                        <div class='word-toolbar-group'>
                            <span id='wordStatsBadge' class='word-stats-pill'>Cargando...</span>
                            <button type='button' class='word-btn' onclick='cambiarZoomWord(-10)'>−</button>
                            <span id='wordZoomLabel' style='font-size:0.76rem; font-weight:800; color:#002244; min-width:42px; text-align:center;'>100%</span>
                            <button type='button' class='word-btn' onclick='cambiarZoomWord(10)'>+</button>
                            <button type='button' class='word-btn' id='btnWideMode' onclick='alternarAnchoWord()'>Ancho completo</button>
                            <button type='button' class='word-btn' onclick='window.print()'>Imprimir</button>
                        </div>
                      </div>";
            }
            echo "<div class='word-workspace'>
                    <aside id='wordOutlineSidebar' class='word-outline-sidebar'>
                        <div class='word-outline-title'>Secciones del Documento</div>
                        <div id='wordOutlineList'></div>
                    </aside>
                    <main class='word-canvas'>
                        <div class='paper-container' id='paperContent'>"
                        . ($esDocx ? "<div class='doc-loader' id='docLoader'>Cargando visualización del documento Word...</div>" : $paragraphsHtml) .
                        "</div>
                    </main>
                  </div>
                  <button type='button' class='btn-top-float' onclick=\"window.scrollTo({top: 0, behavior: 'smooth'})\">↑ Subir al inicio</button>
                  <script>
                    let zoomActualWord = 100;
                    let coincidenciasWord = [];
                    let indiceCoincidenciaActual = -1;

                    function resaltarElementoDestino(el) {
                        if (!el) return;
                        el.classList.remove('word-target-flash');
                        void el.offsetWidth;
                        el.classList.add('word-target-flash');
                    }

                    document.addEventListener('click', function(e) {
                        const link = e.target.closest('a');
                        if (!link) return;
                        const rawHref = (link.getAttribute('href') || '').trim();
                        e.preventDefault();
                        e.stopPropagation();

                        if (/^https?:\\/\\//i.test(rawHref) && !rawHref.includes(window.location.host)) {
                            window.open(rawHref, '_blank', 'noopener,noreferrer');
                            return;
                        }

                        let anchorId = '';
                        if (rawHref.startsWith('#')) {
                            anchorId = decodeURIComponent(rawHref.substring(1));
                        } else if (rawHref.includes('#')) {
                            anchorId = decodeURIComponent(rawHref.split('#').pop());
                        }

                        if (anchorId) {
                            const porId = document.getElementById(anchorId) || document.querySelector('[name=\"' + anchorId.replace(/\"/g, '') + '\"]');
                            if (porId) {
                                porId.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                resaltarElementoDestino(porId.closest('h1, h2, h3, h4, h5, p') || porId);
                                return;
                            }
                        }

                        const textoIndice = (link.textContent || '').replace(/[\\.…\\s\\t]+\\d+\\s*$/, '').replace(/\\s+/g, ' ').trim();
                        if (textoIndice.length >= 2) {
                            const buscado = textoIndice.toLowerCase();
                            const bloques = document.querySelectorAll('#paperContent h1, #paperContent h2, #paperContent h3, #paperContent h4, #paperContent h5, #paperContent p, #paperContent strong');
                            for (let i = 0; i < bloques.length; i++) {
                                const b = bloques[i];
                                if (b.contains(link) || link.contains(b)) continue;
                                const txt = (b.textContent || '').replace(/[\\.…\\s\\t]+\\d+\\s*$/, '').replace(/\\s+/g, ' ').trim().toLowerCase();
                                if (txt === buscado || (buscado.length >= 5 && txt.startsWith(buscado))) {
                                    b.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                    resaltarElementoDestino(b);
                                    return;
                                }
                            }
                        }
                    }, true);

                    function cambiarZoomWord(delta) {
                        zoomActualWord = Math.min(170, Math.max(70, zoomActualWord + delta));
                        const paper = document.getElementById('paperContent');
                        if (paper) paper.style.zoom = zoomActualWord + '%';
                        const lbl = document.getElementById('wordZoomLabel');
                        if (lbl) lbl.textContent = zoomActualWord + '%';
                    }

                    function alternarAnchoWord() {
                        const paper = document.getElementById('paperContent');
                        const btn = document.getElementById('btnWideMode');
                        if (!paper) return;
                        paper.classList.toggle('wide-mode');
                        if (btn) btn.classList.toggle('active', paper.classList.contains('wide-mode'));
                    }

                    function alternarEsquemaWord() {
                        const sidebar = document.getElementById('wordOutlineSidebar');
                        const btn = document.getElementById('btnToggleOutline');
                        if (!sidebar) return;
                        sidebar.classList.toggle('open');
                        if (btn) btn.classList.toggle('active', sidebar.classList.contains('open'));
                    }

                    function inicializarEsquemaYEstadisticas() {
                        const paper = document.getElementById('paperContent');
                        if (!paper) return;
                        const textoPlano = (paper.innerText || '').trim();
                        const totalPalabras = textoPlano ? textoPlano.split(/\\s+/).length : 0;
                        const totalImgs = paper.querySelectorAll('img').length;
                        const badge = document.getElementById('wordStatsBadge');
                        if (badge) {
                            badge.textContent = totalPalabras.toLocaleString('es-VE') + ' palabras • ' + totalImgs + ' imágenes';
                        }

                        const outlineList = document.getElementById('wordOutlineList');
                        if (!outlineList) return;
                        outlineList.innerHTML = '';

                        const nodos = paper.querySelectorAll('h1, h2, h3, h4, p');
                        let contadorSecciones = 0;
                        const regexSeccion = /^(CAP[ÍI]TULO|PARTE|FASE|MOMENTO|RESUMEN|INTRODUCCI[ÓO]N|CONCLUSIONES|RECOMENDACIONES|REFERENCIAS|BIBLIOGRAF[ÍI]A|ANEXOS|OBJETIVO|\\d+\\.\\d*\\s+[A-ZÁÉÍÓÚÑ])/i;

                        nodos.forEach((nodo, idx) => {
                            const tag = nodo.tagName.toLowerCase();
                            const texto = (nodo.textContent || '').replace(/\\s+/g, ' ').trim();
                            if (!texto || texto.length < 3 || texto.length > 140) return;
                            if (/\\.{4,}\\s*\\d+$/.test(texto)) return;

                            let esEncabezado = (tag === 'h1' || tag === 'h2' || tag === 'h3' || tag === 'h4');
                            let nivel = tag === 'h1' ? 1 : (tag === 'h2' ? 2 : 3);
                            if (!esEncabezado && tag === 'p' && regexSeccion.test(texto) && texto.length <= 95) {
                                esEncabezado = true;
                                nivel = 2;
                            }

                            if (esEncabezado) {
                                const secId = 'word_sec_' + idx;
                                if (!nodo.id) nodo.id = secId;
                                const btn = document.createElement('button');
                                btn.type = 'button';
                                btn.className = 'word-outline-item level-' + nivel;
                                btn.textContent = texto;
                                btn.onclick = function() {
                                    nodo.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                    resaltarElementoDestino(nodo);
                                };
                                outlineList.appendChild(btn);
                                contadorSecciones++;
                            }
                        });

                        if (contadorSecciones === 0) {
                            outlineList.innerHTML = '<p style=\"font-size: 0.75rem; color: #64748b; text-align: left;\">No se detectaron subtítulos separados en este documento.</p>';
                        }
                    }

                    function limpiarMarcasBusqueda() {
                        const paper = document.getElementById('paperContent');
                        if (!paper) return;
                        const marcas = paper.querySelectorAll('mark.word-search-hit');
                        marcas.forEach(m => {
                            const parent = m.parentNode;
                            parent.replaceChild(document.createTextNode(m.textContent), m);
                            parent.normalize();
                        });
                        coincidenciasWord = [];
                        indiceCoincidenciaActual = -1;
                    }

                    function limpiarBusquedaWord() {
                        const input = document.getElementById('wordSearchInput');
                        if (input) input.value = '';
                        limpiarMarcasBusqueda();
                        const countEl = document.getElementById('wordSearchCount');
                        if (countEl) countEl.textContent = '0 / 0';
                    }

                    function ejecutarBusquedaWord() {
                        limpiarMarcasBusqueda();
                        const input = document.getElementById('wordSearchInput');
                        const countEl = document.getElementById('wordSearchCount');
                        const termino = input ? input.value.trim() : '';
                        if (termino.length < 2) {
                            if (countEl) countEl.textContent = '0 / 0';
                            return;
                        }

                        const paper = document.getElementById('paperContent');
                        const walker = document.createTreeWalker(paper, NodeFilter.SHOW_TEXT, null, false);
                        const nodosTexto = [];
                        let n;
                        while ((n = walker.nextNode())) {
                            if (n.parentNode && !['SCRIPT', 'STYLE', 'MARK'].includes(n.parentNode.tagName)) {
                                nodosTexto.push(n);
                            }
                        }

                        const escaped = termino.replace(/[.*+?^\${}()|[\\]\\\\]/g, '\\\\$&');
                        const regex = new RegExp(escaped, 'gi');

                        nodosTexto.forEach(textNode => {
                            const val = textNode.nodeValue;
                            if (!regex.test(val)) return;
                            regex.lastIndex = 0;

                            const frag = document.createDocumentFragment();
                            let lastIdx = 0;
                            let match;
                            while ((match = regex.exec(val)) !== null) {
                                if (match.index > lastIdx) {
                                    frag.appendChild(document.createTextNode(val.slice(lastIdx, match.index)));
                                }
                                const mark = document.createElement('mark');
                                mark.className = 'word-search-hit';
                                mark.textContent = match[0];
                                frag.appendChild(mark);
                                coincidenciasWord.push(mark);
                                lastIdx = regex.lastIndex;
                            }
                            if (lastIdx < val.length) {
                                frag.appendChild(document.createTextNode(val.slice(lastIdx)));
                            }
                            textNode.parentNode.replaceChild(frag, textNode);
                        });

                        if (coincidenciasWord.length > 0) {
                            indiceCoincidenciaActual = 0;
                            enfocarCoincidenciaActual();
                        } else if (countEl) {
                            countEl.textContent = '0 / 0';
                        }
                    }

                    function enfocarCoincidenciaActual() {
                        const countEl = document.getElementById('wordSearchCount');
                        if (coincidenciasWord.length === 0) {
                            if (countEl) countEl.textContent = '0 / 0';
                            return;
                        }
                        coincidenciasWord.forEach((m, idx) => {
                            m.classList.toggle('current', idx === indiceCoincidenciaActual);
                        });
                        const actual = coincidenciasWord[indiceCoincidenciaActual];
                        if (actual) {
                            actual.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if (countEl) {
                            countEl.textContent = (indiceCoincidenciaActual + 1) + ' / ' + coincidenciasWord.length;
                        }
                    }

                    function saltarCoincidenciaWord(paso) {
                        if (coincidenciasWord.length === 0) return;
                        indiceCoincidenciaActual = (indiceCoincidenciaActual + paso + coincidenciasWord.length) % coincidenciasWord.length;
                        enfocarCoincidenciaActual();
                    }
                  " . ($esDocx ? "
                    fetch('" . $rawUrl . "')
                        .then(res => {
                            if (!res.ok) throw new Error('HTTP ' + res.status);
                            return res.arrayBuffer();
                        })
                        .then(arrayBuffer => {
                            const m = window.mammoth || (typeof mammoth !== 'undefined' ? mammoth : null);
                            if (!m) throw new Error('Mammoth.js no está disponible');
                            return m.convertToHtml({arrayBuffer: arrayBuffer});
                        })
                        .then(result => {
                            const container = document.getElementById('paperContent');
                            container.innerHTML = result.value || '<p><em>El documento no contiene texto legible.</em></p>';
                            inicializarEsquemaYEstadisticas();
                        })
                        .catch(err => {
                            console.warn('Fallo renderizado Mammoth:', err);
                            const container = document.getElementById('paperContent');
                            container.innerHTML = '<div style=\"padding:1.5rem; color:#505984;\"><p><strong>Vista previa no disponible mediante visor dinámico.</strong></p><p style=\"font-size:0.85rem; color:#64748b;\">' + (err.message || err) + '</p></div>';
                        });
                  " : "
                    inicializarEsquemaYEstadisticas();
                  ") . "
                  </script>
                  </body></html>";
            exit;
        } else {
            header('Content-Type: application/octet-stream');
            readfile($fullPath);
        }
        exit;
    }

    public function crear(): array {
        require_once CORE_PATH . 'Security/Auth.php';
        require_once CORE_PATH . 'Security/AuditLogger.php';
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $accion = !empty($_GET['accion']) ? trim($_GET['accion']) : 'listar';
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;

        // Si la petición es un formulario multipart/form-data o urlencoded y excede post_max_size, PHP vacía $_POST y $_FILES
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        $isMultipartOrForm = (stripos($contentType, 'multipart/form-data') !== false || stripos($contentType, 'application/x-www-form-urlencoded') !== false);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isMultipartOrForm && empty($_POST) && empty($_FILES) && !empty($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > 0) {
            $maxPostSize = ini_get('post_max_size') ?: 'desconocido';
            if (in_array($accion, ['extraer', 'simular_extraccion'])) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'status' => 'error',
                    'message' => "El archivo enviado excede el tamaño máximo permitido por el servidor (post_max_size: {$maxPostSize})."
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
        }

        // Si es petición AJAX, responder con JSON si no se tienen permisos en lugar de 302 redirect
        if (in_array($accion, ['extraer', 'crear_ajax', 'simular_extraccion', 'bulk_action', 'quick_edit', 'purgar_todo_pst', 'contar_borrado_criterios', 'limpiar_temporales'], true)) {
            $permisoRequerido = 'crear';
            if ($accion === 'quick_edit') {
                $permisoRequerido = 'editar';
            } elseif (in_array($accion, ['purgar_todo_pst', 'contar_borrado_criterios', 'limpiar_temporales'], true)) {
                $permisoRequerido = 'eliminar';
            }
            if (!Auth::requierePrivilegioMinimo($this->nivelAdmin, $permisoRequerido, 'RepositorioPST', false)) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Tu sesión ha expirado o no cuentas con permisos para realizar esta acción.'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $submittedCsrf = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_SERVER['HTTP_CSRF_TOKEN'] ?? '';
                if (empty($submittedCsrf) && stripos($contentType, 'application/json') !== false) {
                    $jsonBody = @json_decode(file_get_contents('php://input'), true);
                    if (is_array($jsonBody) && !empty($jsonBody['csrf_token'])) {
                        $submittedCsrf = $jsonBody['csrf_token'];
                    }
                }
                if (empty($submittedCsrf) || !hash_equals($_SESSION['csrf_token'], $submittedCsrf)) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'La petición fue rechazada por seguridad. Recarga la página e intenta de nuevo.'
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
        
        // Directorios de destino garantizados (permanente y temporal staging)
        $storageDir = BASE_PATH . '/storage/documentos/pst/';
        if (!file_exists($storageDir)) {
            @mkdir($storageDir, 0777, true);
        }
        $tmpDir = BASE_PATH . '/storage/documentos/tmp/';
        if (!file_exists($tmpDir)) {
            @mkdir($tmpDir, 0777, true);
        } else {
            // Limpieza automática de archivos temporales huérfanos con más de 2 horas de antigüedad
            $ahora = time();
            foreach (glob($tmpDir . '*') as $archViejo) {
                if (is_file($archViejo) && ($ahora - filemtime($archViejo) > 7200)) {
                    @unlink($archViejo);
                }
            }
        }
        
        // 0. Procesar Acción: EXTRAER METADATOS DE ARCHIVOS Y GUARDAR ARCHIVO (AJAX, soporta subida directa o por fragmentos / chunks)
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

                $configMaxMb = (float)ConfigService::get('archivos.max_size_mb', 100);
                $maxMb = round($configMaxMb, 2);
                $maxBytes = $maxMb * 1024 * 1024;

                $isChunked = isset($_POST['chunk_index'], $_POST['total_chunks'], $_POST['upload_id']);
                $isAssembledFile = false;

                if ($isChunked) {
                    $chunkIndex = (int)$_POST['chunk_index'];
                    $totalChunks = max(1, (int)$_POST['total_chunks']);
                    $uploadId = preg_replace('/[^a-zA-Z0-9_-]/', '', (string)$_POST['upload_id']);
                    $originalName = !empty($_POST['original_name']) ? trim((string)$_POST['original_name']) : $_FILES['archivo_pst']['name'];
                    $totalSize = isset($_POST['total_size']) ? (float)$_POST['total_size'] : 0;

                    if ($totalSize > $maxBytes) {
                        throw new Exception("El archivo excede el tamaño máximo permitido ({$maxMb} MB).");
                    }

                    $extCheck = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                    if (!in_array($extCheck, ['pdf', 'docx', 'doc'], true)) {
                        throw new Exception("Formato de archivo no soportado. Debe ser un archivo PDF o Word (.docx / .doc).");
                    }

                    $partFile = $tmpDir . 'part_' . $uploadId . '.' . $extCheck;
                    if ($chunkIndex === 0 && file_exists($partFile)) {
                        @unlink($partFile);
                    }

                    $inStream = @fopen($_FILES['archivo_pst']['tmp_name'], 'rb');
                    $outStream = @fopen($partFile, $chunkIndex === 0 ? 'wb' : 'ab');
                    if (!$inStream || !$outStream) {
                        if ($inStream) @fclose($inStream);
                        if ($outStream) @fclose($outStream);
                        throw new Exception("No se pudo ensamblar el fragmento temporal en el servidor.");
                    }
                    stream_copy_to_stream($inStream, $outStream);
                    fclose($inStream);
                    fclose($outStream);

                    if ((float)@filesize($partFile) > $maxBytes) {
                        @unlink($partFile);
                        throw new Exception("El archivo ensamblado excede el tamaño máximo permitido ({$maxMb} MB).");
                    }

                    // Si aún faltan fragmentos por subir, confirmar recepción del fragmento actual
                    if ($chunkIndex + 1 < $totalChunks) {
                        while (ob_get_level()) ob_end_clean();
                        echo json_encode([
                            'status'      => 'chunk_ok',
                            'chunk_index' => $chunkIndex
                        ], JSON_UNESCAPED_UNICODE);
                        exit;
                    }

                    // Último fragmento recibido: archivo completo listo para procesar
                    $fileTmpPath = $partFile;
                    $fileName = $originalName;
                    $isAssembledFile = true;
                } else {
                    if ($_FILES['archivo_pst']['size'] > $maxBytes) {
                        throw new Exception("El archivo excede el tamaño máximo permitido ({$maxMb} MB).");
                    }
                    $fileTmpPath = $_FILES['archivo_pst']['tmp_name'];
                    $fileName = $_FILES['archivo_pst']['name'];
                }

                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (!in_array($fileExtension, ['pdf', 'docx', 'doc'], true)) {
                    if ($isAssembledFile && file_exists($fileTmpPath)) @unlink($fileTmpPath);
                    throw new Exception("Formato de archivo no soportado. Debe ser un archivo PDF o Word (.docx / .doc).");
                }

                // 1. Validar MIME-Type binario / Magic Bytes para prevenir archivos falsos o maliciosos
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $fileTmpPath);
                if (PHP_VERSION_ID < 80500 && is_resource($finfo)) {
                    finfo_close($finfo);
                }

                $allowedMimeTypes = [
                    'application/pdf',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/msword',
                    'application/CDFV2',
                    'application/vnd.ms-office',
                    'application/x-ole-storage',
                    'application/zip', // Algunos sistemas detectan docx como zip
                    'application/x-zip-compressed',
                    'application/octet-stream'
                ];

                if (!in_array($mimeType, $allowedMimeTypes, true)) {
                    if ($isAssembledFile && file_exists($fileTmpPath)) @unlink($fileTmpPath);
                    throw new Exception("El contenido binario del archivo no corresponde a un documento PDF o Word válido.");
                }

                require_once __DIR__ . '/../services/ExtractorPST.php';

                $text = ExtractorPST::extraerTextoArchivo($fileTmpPath, $fileExtension);

                if (empty(trim($text))) {
                    if ($isAssembledFile && file_exists($fileTmpPath)) @unlink($fileTmpPath);
                    throw new Exception("No se pudo extraer texto del documento. Asegúrese de que el archivo no esté protegido o escaneado como imagen pura sin OCR.");
                }

                $datosExtraidos = ExtractorPST::analizarTexto($text, $fileName);

                // Si se extrajo nombre de tutor pero no su cédula, intentar resolver desde BD si ya existe
                foreach (['academico', 'institucional', 'comunitario'] as $tKey) {
                    $nomK = 'tutor_' . $tKey . '_nombre';
                    $cedK = 'tutor_' . $tKey . '_cedula';
                    if (!empty($datosExtraidos[$nomK]) && empty($datosExtraidos[$cedK])) {
                        $cedFromDb = $model->getCedulaByNombre($datosExtraidos[$nomK], 'tutor');
                        if ($cedFromDb) {
                            $datosExtraidos[$cedK] = $cedFromDb;
                        }
                    }
                }
                if (!empty($datosExtraidos['autores']) && is_array($datosExtraidos['autores'])) {
                    foreach ($datosExtraidos['autores'] as &$autItem) {
                        if (!empty($autItem['nombre']) && empty($autItem['cedula'])) {
                            $cedAut = $model->getCedulaByNombre($autItem['nombre'], 'autor');
                            if ($cedAut) {
                                $autItem['cedula'] = $cedAut;
                            }
                        }
                    }
                    unset($autItem);
                }

                // Verificar si el proyecto ya existe por título en el repositorio
                $tituloExtraido = trim($datosExtraidos['titulo'] ?? '');
                $yaExiste = !empty($tituloExtraido) && $model->existePSTPorTitulo($tituloExtraido);
                $datosExtraidos['ya_existe_en_bd'] = $yaExiste;

                // Guardar de forma provisional en el staging temporal storage/documentos/tmp/
                $slugTitle = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', substr($datosExtraidos['titulo'] ?? $fileName, 0, 30)));
                $savedFileName = 'tmp_pst_' . $slugTitle . '_' . time() . '_' . mt_rand(100, 999) . '.' . $fileExtension;
                $targetFile = $tmpDir . $savedFileName;
                
                $movedOk = $isAssembledFile
                    ? (@rename($fileTmpPath, $targetFile) || (@copy($fileTmpPath, $targetFile) && @unlink($fileTmpPath)))
                    : (move_uploaded_file($fileTmpPath, $targetFile) || copy($fileTmpPath, $targetFile));

                if ($movedOk) {
                    // Si es un archivo Word (.docx) pesado, optimizar sus imágenes internas para ahorrar espacio en el servidor
                    if ($fileExtension === 'docx') {
                        $datosExtraidos['optimizacion_archivo'] = ExtractorPST::optimizarArchivoDOCX($targetFile);
                    }
                    $datosExtraidos['archivo_pdf'] = 'storage/documentos/tmp/' . $savedFileName;
                } else {
                    throw new Exception("No se pudo guardar el archivo temporal en el almacenamiento del servidor.");
                }

                while (ob_get_level()) ob_end_clean();
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Metadatos extraídos e indexados con éxito.',
                    'data' => array_merge($datosExtraidos, ['texto_raw' => $text])
                ], JSON_UNESCAPED_UNICODE);
                
            } catch (\Throwable $e) {
                // Registrar el fallo en la auditoría WAF antes de devolver el error al cliente
                AuditLogger::registrar('WARNING', 'RepositorioPST', 'Fallo en Extracción Masiva', $e->getMessage());
                
                while (ob_get_level()) ob_end_clean();
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

                $configMaxMb = (float)ConfigService::get('archivos.max_size_mb', 100);
                $maxMb = round($configMaxMb, 2);
                $maxBytes = $maxMb * 1024 * 1024;
                if ($_FILES['archivo_pst']['size'] > $maxBytes) {
                    throw new Exception("El archivo excede el tamaño máximo permitido ({$maxMb} MB).");
                }

                $fileTmpPath = $_FILES['archivo_pst']['tmp_name'];
                $fileName = $_FILES['archivo_pst']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (!in_array($fileExtension, ['pdf', 'docx', 'doc'], true)) {
                    throw new Exception("Formato no soportado. Debe ser PDF o Word (.docx / .doc).");
                }

                $finfoSim = finfo_open(FILEINFO_MIME_TYPE);
                $mimeSim = finfo_file($finfoSim, $fileTmpPath);
                if (PHP_VERSION_ID < 80500 && is_resource($finfoSim)) {
                    finfo_close($finfoSim);
                }
                $allowedMimeSim = [
                    'application/pdf',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/msword',
                    'application/CDFV2',
                    'application/vnd.ms-office',
                    'application/x-ole-storage',
                    'application/zip',
                    'application/x-zip-compressed',
                    'application/octet-stream'
                ];
                if (!in_array($mimeSim, $allowedMimeSim, true)) {
                    throw new Exception("El contenido binario del archivo no corresponde a un documento PDF o Word válido.");
                }

                require_once __DIR__ . '/../services/ExtractorPST.php';
                $text = ExtractorPST::extraerTextoArchivo($fileTmpPath, $fileExtension);

                if (empty(trim($text))) {
                    throw new Exception("No se pudo extraer texto del archivo.");
                }

                $datosExtraidos = ExtractorPST::analizarTexto($text, $fileName);

                foreach (['academico', 'institucional', 'comunitario'] as $tKey) {
                    $nomK = 'tutor_' . $tKey . '_nombre';
                    $cedK = 'tutor_' . $tKey . '_cedula';
                    if (!empty($datosExtraidos[$nomK]) && empty($datosExtraidos[$cedK])) {
                        $cedFromDb = $model->getCedulaByNombre($datosExtraidos[$nomK], 'tutor');
                        if ($cedFromDb) {
                            $datosExtraidos[$cedK] = $cedFromDb;
                        }
                    }
                }
                if (!empty($datosExtraidos['autores']) && is_array($datosExtraidos['autores'])) {
                    foreach ($datosExtraidos['autores'] as &$autItem) {
                        if (!empty($autItem['nombre']) && empty($autItem['cedula'])) {
                            $cedAut = $model->getCedulaByNombre($autItem['nombre'], 'autor');
                            if ($cedAut) {
                                $autItem['cedula'] = $cedAut;
                            }
                        }
                    }
                    unset($autItem);
                }

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Simulación ejecutada con éxito.',
                    'data' => $datosExtraidos,
                    'preview_texto' => mb_substr($text, 0, 800) . '...'
                ], JSON_UNESCAPED_UNICODE);

            } catch (\Throwable $e) {
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

        // 0.2.1b Procesar Acción: OBTENER TRAYECTOS POR CARRERA (AJAX)
        if ($accion === 'obtener_trayectos') {
            header('Content-Type: application/json; charset=utf-8');
            $carreraIdReq = !empty($_GET['carrera_id']) ? (int)$_GET['carrera_id'] : null;
            $trayectosCarrera = $model->getTrayectos($carreraIdReq);
            echo json_encode([
                'status'    => 'success',
                'trayectos' => $trayectosCarrera
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // 0.2.2 Procesar Acción: VERIFICAR TÍTULO DUPLICADO (AJAX en tiempo real)
        if ($accion === 'verificar_titulo') {
            header('Content-Type: application/json; charset=utf-8');
            $tit = !empty($_GET['titulo']) ? trim($_GET['titulo']) : '';
            $excluirId = !empty($_GET['id_excluir']) ? (int)$_GET['id_excluir'] : null;
            $existe = !empty($tit) && $model->existePSTPorTitulo($tit, $excluirId);
            echo json_encode([
                'status' => 'success',
                'existe' => $existe,
                'titulo' => $tit
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // 0.1 Procesar Acción: CREAR VIA AJAX (SUBIDA EN LOTE)
        if ($accion === 'crear_ajax' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json; charset=utf-8');
            $archivoPdf = null;
            $finalPdfPath = null;
            $datos = [];
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
                        if ($nom !== '') {
                            $autores[] = ['cedula' => $ced ?: null, 'nombre' => $nom];
                        }
                    }
                }

                $nivelPost = !empty($postData['nivel_academico']) ? trim($postData['nivel_academico']) : 'Pregrado';
                $idTrayectoPost = !empty($postData['id_trayecto']) ? (int)$postData['id_trayecto'] : null;
                $trayectoPost = in_array($nivelPost, $model->getNivelesConTrayecto()) ? (!empty($postData['trayecto']) ? trim($postData['trayecto']) : 'Trayecto I') : null;

                $rawUrlGit = !empty($postData['url_repositorio']) ? trim($postData['url_repositorio']) : null;
                $urlGitSanitizada = null;
                if (!empty($rawUrlGit) && filter_var($rawUrlGit, FILTER_VALIDATE_URL)) {
                    $scheme = strtolower(parse_url($rawUrlGit, PHP_URL_SCHEME) ?? '');
                    if (in_array($scheme, ['http', 'https'])) {
                        $urlGitSanitizada = $rawUrlGit;
                    }
                }

                $archivoPdf = !empty($postData['archivo_pdf']) ? trim($postData['archivo_pdf']) : null;

                $idCarrera = !empty($postData['id_carrera']) ? (int)$postData['id_carrera'] : 1;
                $lineaId = !empty($postData['linea_id']) ? (int)$postData['linea_id'] : null;
                if (empty($lineaId)) {
                    $lineasDisp = $model->getLineasInvestigacion($idCarrera);
                    if (!empty($lineasDisp) && !empty($lineasDisp[0]['id'])) {
                        $lineaId = (int)$lineasDisp[0]['id'];
                    } else {
                        $lineaId = 7;
                    }
                }

                $resumen = !empty($postData['resumen']) ? trim($postData['resumen']) : '';
                if (empty($resumen)) {
                    $resumen = !empty($postData['obj_general']) ? trim($postData['obj_general']) : 'Proyecto Socio-Tecnológico indexado en el repositorio institucional.';
                }

                $carrerasVinculadas = [];
                if (!empty($postData['carreras_vinculadas'])) {
                    if (is_array($postData['carreras_vinculadas'])) {
                        $carrerasVinculadas = array_map('intval', $postData['carreras_vinculadas']);
                    } else {
                        $carrerasVinculadas = array_filter(array_map('intval', explode(',', (string)$postData['carreras_vinculadas'])));
                    }
                }
                $carrerasVinculadas = array_values(array_unique(array_filter($carrerasVinculadas, fn($cId) => $cId > 0 && $cId !== $idCarrera)));

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
                    'id_trayecto'                => $idTrayectoPost,
                    'url_repositorio'            => $urlGitSanitizada,
                    'archivo_pdf'                => $archivoPdf,
                    'resumen'                    => $resumen,
                    'obj_general'                => !empty($postData['obj_general']) ? trim($postData['obj_general']) : null,
                    'comunidad_beneficiada'      => !empty($postData['comunidad_beneficiada']) ? trim($postData['comunidad_beneficiada']) : '',
                    'palabras_clave'             => !empty($postData['palabras_clave']) ? trim($postData['palabras_clave']) : '',
                    'id_carrera'                 => $idCarrera,
                    'linea_id'                   => $lineaId,
                    'dimension_id'               => !empty($postData['dimension_id']) ? (int)$postData['dimension_id'] : null,
                    'carreras_vinculadas'        => $carrerasVinculadas,
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

                $relPdfCheck = ltrim(str_replace(['\\', '/'], '/', $datos['archivo_pdf']), '/');
                if (strpos($relPdfCheck, '..') !== false || (strpos($relPdfCheck, 'storage/documentos/tmp/') !== 0 && strpos($relPdfCheck, 'storage/documentos/pst/') !== 0)) {
                    throw new Exception("Ruta de archivo temporal no autorizada.");
                }

                $realFilePath = BASE_PATH . '/' . $relPdfCheck;
                if (!file_exists($realFilePath)) {
                    throw new Exception("El archivo digital asociado no se encuentra en el almacenamiento temporal del servidor. Extraiga el documento nuevamente.");
                }

                if (empty($datos['autores'])) {
                    throw new Exception("Debe registrar al menos un autor para el proyecto.");
                }

                // Promover archivo desde storage/documentos/tmp/ a storage/documentos/pst/
                $destPath = null;
                if (strpos($relPdfCheck, 'storage/documentos/tmp/') === 0) {
                    $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', substr($datos['titulo'], 0, 30)));
                    $ext = strtolower(pathinfo($relPdfCheck, PATHINFO_EXTENSION));
                    $destName = 'pst_' . $slug . '_' . time() . '_' . mt_rand(100, 999) . '.' . $ext;
                    $destPath = $storageDir . $destName;
                    if (!copy($realFilePath, $destPath)) {
                        throw new Exception("No se pudo transferir el archivo temporal al almacenamiento definitivo de proyectos.");
                    }
                    $finalPdfPath = 'storage/documentos/pst/' . $destName;
                    $datos['archivo_pdf'] = $finalPdfPath;
                } else {
                    $finalPdfPath = $relPdfCheck;
                }

                $nuevoId = $model->crearPST($datos);

                // Si la inserción fue exitosa y venía de tmp, limpiar el archivo temporal del staging
                if (!empty($destPath) && file_exists($realFilePath)) {
                    @unlink($realFilePath);
                }

                while (ob_get_level()) ob_end_clean();
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Proyecto cargado con éxito en el catálogo.',
                    'id' => $nuevoId
                ], JSON_UNESCAPED_UNICODE);

            } catch (\Throwable $e) {
                // Si ocurrió un error y se copió a storage/documentos/pst/, eliminarlo para evitar huérfanos
                if (!empty($destPath) && file_exists($destPath)) {
                    @unlink($destPath);
                }

                require_once CORE_PATH . 'Security/Auth.php';
                $tituloFallo = !empty($datos['titulo']) ? $datos['titulo'] : 'Proyecto Sin Título';
                AuditLogger::registrar('WARNING', 'RepositorioPST', 'Fallo en Extracción Masiva', "Error al intentar guardar el PST '{$tituloFallo}': " . $e->getMessage());
                
                while (ob_get_level()) ob_end_clean();
                echo json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], JSON_UNESCAPED_UNICODE);
            }
            exit;
        }

        // 0.1b Procesar Acción: ACCIONES EN LOTE DESDE LA TABLA
        if ($accion === 'bulk_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json; charset=utf-8');
            try {
                $rawBody = file_get_contents('php://input');
                $payload = json_decode($rawBody, true);
                if (!is_array($payload)) {
                    $payload = $_POST;
                }

                $operacion = strtolower(trim((string)($payload['operacion'] ?? '')));
                $ids = $payload['ids'] ?? [];
                if (!is_array($ids)) {
                    $ids = explode(',', (string)$ids);
                }

                if ($operacion === 'eliminar') {
                    if (!Auth::requierePrivilegioMinimo($this->nivelAdmin, 'eliminar', 'RepositorioPST', false)) {
                        throw new Exception("No cuentas con permisos para eliminar proyectos.");
                    }
                } else {
                    if (!Auth::requierePrivilegioMinimo($this->nivelAdmin, 'editar', 'RepositorioPST', false)) {
                        throw new Exception("No cuentas con permisos para modificar estos proyectos.");
                    }
                }

                $res = $model->ejecutarAccionMasivaPST($ids, $operacion);
                $cant = $res['procesados'] ?? 0;

                $labelOp = 'actualizados';
                if ($operacion === 'activar') $labelOp = 'visibles en el catálogo';
                elseif ($operacion === 'ocultar') $labelOp = 'ocultados del público';
                elseif ($operacion === 'eliminar') $labelOp = 'eliminados definitivamente';

                AuditLogger::registrar('INFO', 'RepositorioPST', 'Acción por Lote', "Se procesaron {$cant} proyectos con la operación: {$operacion}.");

                while (ob_get_level()) ob_end_clean();
                echo json_encode([
                    'status'  => 'success',
                    'message' => "Listo. {$cant} proyectos quedaron {$labelOp}."
                ], JSON_UNESCAPED_UNICODE);
            } catch (\Throwable $e) {
                while (ob_get_level()) ob_end_clean();
                echo json_encode([
                    'status'  => 'error',
                    'message' => $e->getMessage()
                ], JSON_UNESCAPED_UNICODE);
            }
            exit;
        }

        // 0.1c Procesar Acción: EDICIÓN RÁPIDA DESDE LA TABLA
        if ($accion === 'quick_edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json; charset=utf-8');
            try {
                $rawBody = file_get_contents('php://input');
                $payload = json_decode($rawBody, true);
                if (!is_array($payload)) {
                    $payload = $_POST;
                }

                $idEdit = (int)($payload['id'] ?? 0);
                if ($idEdit <= 0) {
                    throw new Exception("No se indicó qué proyecto deseas actualizar.");
                }

                $model->actualizarRapidoPST($idEdit, $payload);
                AuditLogger::registrar('INFO', 'RepositorioPST', 'Edición Rápida PST', "Proyecto #{$idEdit} actualizado desde la tabla rápida.");

                while (ob_get_level()) ob_end_clean();
                echo json_encode([
                    'status'  => 'success',
                    'message' => 'Los cambios del proyecto se guardaron correctamente.'
                ], JSON_UNESCAPED_UNICODE);
            } catch (\Throwable $e) {
                while (ob_get_level()) ob_end_clean();
                echo json_encode([
                    'status'  => 'error',
                    'message' => $e->getMessage()
                ], JSON_UNESCAPED_UNICODE);
            }
            exit;
        }

        // 0.1d Procesar Acción: LIMPIAR ARCHIVOS TEMPORALES HUÉRFANOS
        if ($accion === 'limpiar_temporales' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json; charset=utf-8');
            try {
                $borrados = 0;
                $bytesLiberados = 0;
                if (is_dir($tmpDir)) {
                    foreach (glob($tmpDir . '*') ?: [] as $archTmp) {
                        if (is_file($archTmp) && basename($archTmp)[0] !== '.') {
                            $bytesLiberados += (int)@filesize($archTmp);
                            if (@unlink($archTmp)) {
                                $borrados++;
                            }
                        }
                    }
                }
                $mbLib = round($bytesLiberados / (1024 * 1024), 2);
                AuditLogger::registrar('INFO', 'RepositorioPST', 'Limpieza de Temporales', "Se eliminaron {$borrados} archivos temporales liberando {$mbLib} MB.");

                while (ob_get_level()) ob_end_clean();
                echo json_encode([
                    'status'  => 'success',
                    'message' => $borrados > 0
                        ? "Se limpiaron {$borrados} archivos temporales y se liberaron {$mbLib} MB en el servidor."
                        : "La carpeta temporal ya estaba limpia."
                ], JSON_UNESCAPED_UNICODE);
            } catch (\Throwable $e) {
                while (ob_get_level()) ob_end_clean();
                echo json_encode([
                    'status'  => 'error',
                    'message' => $e->getMessage()
                ], JSON_UNESCAPED_UNICODE);
            }
            exit;
        }

        // 0.1e Procesar Acción: PURGAR TODOS LOS PST CON AUTENTICACIÓN SUPERADMIN
        if ($accion === 'contar_borrado_criterios' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json; charset=utf-8');
            try {
                $rawBody = file_get_contents('php://input');
                $payload = json_decode($rawBody, true);
                if (!is_array($payload)) {
                    $payload = $_POST;
                }
                $modo = strtolower(trim((string)($payload['modo'] ?? 'criterios')));
                if ($modo === 'todo') {
                    $resumen = $model->getPSTStatsResumen();
                    $total = (int)($resumen['total_catalog'] ?? 0);
                } else {
                    $criterios = [
                        'anio_desde'   => !empty($payload['anio_desde']) ? (int)$payload['anio_desde'] : null,
                        'anio_hasta'   => !empty($payload['anio_hasta']) ? (int)$payload['anio_hasta'] : null,
                        'carrera_id'   => !empty($payload['carrera_id']) ? (int)$payload['carrera_id'] : null,
                        'linea_id'     => !empty($payload['linea_id']) ? (int)$payload['linea_id'] : null,
                        'dimension_id' => !empty($payload['dimension_id']) ? (int)$payload['dimension_id'] : null
                    ];
                    $tieneAlguno = !empty($criterios['anio_desde']) || !empty($criterios['anio_hasta']) || !empty($criterios['carrera_id']) || !empty($criterios['linea_id']) || !empty($criterios['dimension_id']);
                    $total = $tieneAlguno ? count($model->obtenerIdsPSTPorCriterios($criterios)) : 0;
                }
                while (ob_get_level()) ob_end_clean();
                echo json_encode([
                    'status' => 'success',
                    'total'  => $total
                ], JSON_UNESCAPED_UNICODE);
            } catch (\Throwable $e) {
                while (ob_get_level()) ob_end_clean();
                echo json_encode([
                    'status'  => 'error',
                    'message' => $e->getMessage()
                ], JSON_UNESCAPED_UNICODE);
            }
            exit;
        }

        if ($accion === 'purgar_todo_pst' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json; charset=utf-8');
            try {
                $rawBody = file_get_contents('php://input');
                $payload = json_decode($rawBody, true);
                if (!is_array($payload)) {
                    $payload = $_POST;
                }

                $cedulaSudo = trim((string)($payload['cedula_superadmin'] ?? ''));
                $passwordSudo = (string)($payload['password_superadmin'] ?? '');
                $modo = strtolower(trim((string)($payload['modo'] ?? 'todo')));
                $frase = strtoupper(trim((string)($payload['frase_confirmacion'] ?? $payload['confirmacion_texto'] ?? '')));
                $conservarPersonas = isset($payload['conservar_personas']) ? (bool)$payload['conservar_personas'] : true;

                if ($modo === 'todo' && !in_array($frase, ['ELIMINAR TODO', 'VACIAR REPOSITORIO', 'CONFIRMAR'], true)) {
                    throw new Exception('Debes escribir exactamente la frase ELIMINAR TODO para confirmar.');
                }

                $sudoUser = $model->verificarCredencialesSuperAdmin($cedulaSudo, $passwordSudo);
                if (!$sudoUser) {
                    AuditLogger::registrar('WARNING', 'RepositorioPST', 'Intento Fallido de Purga Total', "Credenciales de SuperAdmin inválidas al intentar vaciar el catálogo de proyectos.");
                    throw new Exception('Las credenciales ingresadas no son válidas o no pertenecen a una cuenta de SuperAdmin.');
                }

                if ($modo === 'criterios') {
                    $criterios = [
                        'anio_desde'   => !empty($payload['anio_desde']) ? (int)$payload['anio_desde'] : null,
                        'anio_hasta'   => !empty($payload['anio_hasta']) ? (int)$payload['anio_hasta'] : null,
                        'carrera_id'   => !empty($payload['carrera_id']) ? (int)$payload['carrera_id'] : null,
                        'linea_id'     => !empty($payload['linea_id']) ? (int)$payload['linea_id'] : null,
                        'dimension_id' => !empty($payload['dimension_id']) ? (int)$payload['dimension_id'] : null
                    ];
                    $resultado = $model->eliminarPSTPorCriterios($criterios, $conservarPersonas);
                } else {
                    $resultado = $model->purgarTodosLosPST($conservarPersonas);
                }
                $elim = $resultado['proyectos_eliminados'] ?? 0;
                $mb = $resultado['mb_liberados'] ?? 0;

                AuditLogger::registrar(
                    'CRITICAL',
                    'RepositorioPST',
                    'Purga Total del Catálogo PST',
                    "Catálogo vaciado con autorización de SuperAdmin {$sudoUser['nombre_completo']} con cédula {$sudoUser['cedula']}. Se eliminaron {$elim} proyectos y se liberaron {$mb} MB."
                );

                while (ob_get_level()) ob_end_clean();
                echo json_encode([
                    'status'  => 'success',
                    'message' => $modo === 'criterios'
                        ? "Se eliminaron {$elim} proyectos que coincidían con los filtros seleccionados y se liberaron {$mb} MB de espacio."
                        : "El catálogo fue vaciado por completo. Se eliminaron {$elim} proyectos y se liberaron {$mb} MB de espacio."
                ], JSON_UNESCAPED_UNICODE);
            } catch (\Throwable $e) {
                while (ob_get_level()) ob_end_clean();
                echo json_encode([
                    'status'  => 'error',
                    'message' => $e->getMessage()
                ], JSON_UNESCAPED_UNICODE);
            }
            exit;
        }

        // 0.1f Procesar Acción: EXPORTAR LISTADO ACTUAL A EXCEL NATIVO (.XLSX) CON GRÁFICOS Y FILTROS
        if ($accion === 'exportar_csv' || $accion === 'exportar_excel') {
            require_once __DIR__ . '/../services/PstExcelExportService.php';
            $filtrosExp = [
                'activo'          => 'todos',
                'q'               => !empty($_GET['q']) ? trim($_GET['q']) : null,
                'carrera_id'      => !empty($_GET['carrera_id']) ? (int)$_GET['carrera_id'] : null,
                'nivel_academico' => !empty($_GET['nivel_academico']) ? trim($_GET['nivel_academico']) : null,
                'trayecto'        => !empty($_GET['trayecto']) ? trim($_GET['trayecto']) : null,
                'anio'            => !empty($_GET['anio']) ? (int)$_GET['anio'] : null,
                'linea_id'        => !empty($_GET['linea_id']) ? (int)$_GET['linea_id'] : null,
                'dimension_id'    => !empty($_GET['dimension_id']) ? (int)$_GET['dimension_id'] : null,
                'estado_filtro'   => !empty($_GET['estado_filtro']) ? trim($_GET['estado_filtro']) : null,
                'orden'           => !empty($_GET['orden']) ? trim($_GET['orden']) : 'recientes'
            ];
            $listaExp = $model->getPSTDocumentos($filtrosExp, 5000, 0);

            $catalogosAux = [];
            if (!empty($filtrosExp['carrera_id'])) {
                foreach ($model->getCarreras() as $cAux) {
                    if ((int)$cAux['id'] === (int)$filtrosExp['carrera_id']) {
                        $catalogosAux['carrera_nombre'] = $cAux['nombre'];
                        break;
                    }
                }
            }
            if (!empty($filtrosExp['linea_id'])) {
                foreach ($model->getLineasInvestigacion(null) as $lAux) {
                    if ((int)$lAux['id'] === (int)$filtrosExp['linea_id']) {
                        $catalogosAux['linea_nombre'] = $lAux['nombre'];
                        break;
                    }
                }
            }
            if (!empty($filtrosExp['dimension_id'])) {
                foreach ($model->getDimensionesOperativas() as $dAux) {
                    if ((int)$dAux['id'] === (int)$filtrosExp['dimension_id']) {
                        $catalogosAux['dimension_nombre'] = $dAux['nombre'];
                        break;
                    }
                }
            }

            PstExcelExportService::descargarExcelCatalogo($listaExp, $filtrosExp, $catalogosAux);
        }
        
        $error = null;
        $success = null;
        
        // Cargar mensajes de éxito desde la redirección anterior
        if (isset($_GET['msg'])) {
            if ($_GET['msg'] === 'deleted') {
                $success = "El proyecto fue eliminado del catálogo.";
            } elseif ($_GET['msg'] === 'created') {
                $success = "El proyecto fue registrado con éxito en el catálogo.";
            } elseif ($_GET['msg'] === 'updated') {
                $success = "Los cambios del proyecto se guardaron correctamente.";
            } elseif ($_GET['msg'] === 'status_changed') {
                $success = "La visibilidad del proyecto fue actualizada.";
            }
        }

        $paramsRetornoFiltro = [];
        foreach (['q', 'carrera_id', 'nivel_academico', 'trayecto', 'linea_id', 'dimension_id', 'anio', 'estado_filtro', 'orden', 'limit', 'page'] as $kParam) {
            if (isset($_GET[$kParam]) && $_GET[$kParam] !== '') {
                $paramsRetornoFiltro[$kParam] = $_GET[$kParam];
            }
        }
        $qsRetornoFiltro = !empty($paramsRetornoFiltro) ? ('&' . http_build_query($paramsRetornoFiltro)) : '';

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

                    header("Location: ?ruta=agregar-documento&msg=status_changed" . $qsRetornoFiltro);
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

                header("Location: ?ruta=agregar-documento&msg=deleted" . $qsRetornoFiltro);
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
                    if ($nom !== '') {
                        $autores[] = ['cedula' => $ced ?: null, 'nombre' => $nom];
                    }
                }
            }

            // Manejo de archivo adjunto si se subió en el form tradicional o se extrajo previamente
            $archivoPath = null;
            $promotedTmpPath = null;
            if (isset($_FILES['archivo_pst']) && $_FILES['archivo_pst']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['archivo_pst']['name'], PATHINFO_EXTENSION));
                $finfoTrad = finfo_open(FILEINFO_MIME_TYPE);
                $mimeTrad = finfo_file($finfoTrad, $_FILES['archivo_pst']['tmp_name']);
                if (PHP_VERSION_ID < 80500 && is_resource($finfoTrad)) {
                    finfo_close($finfoTrad);
                }
                $allowedMimeTrad = [
                    'application/pdf',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/msword',
                    'application/CDFV2',
                    'application/vnd.ms-office',
                    'application/x-ole-storage',
                    'application/zip',
                    'application/x-zip-compressed',
                    'application/octet-stream'
                ];

                if (in_array($ext, ['pdf', 'docx', 'doc'], true) && in_array($mimeTrad, $allowedMimeTrad, true)) {
                    $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', substr($_POST['titulo'] ?? 'pst', 0, 30)));
                    $destName = 'pst_' . $slug . '_' . time() . '_' . mt_rand(100, 999) . '.' . $ext;
                    if (move_uploaded_file($_FILES['archivo_pst']['tmp_name'], $storageDir . $destName)) {
                        if ($ext === 'docx') {
                            require_once __DIR__ . '/../services/ExtractorPST.php';
                            ExtractorPST::optimizarArchivoDOCX($storageDir . $destName);
                        }
                        $archivoPath = 'storage/documentos/pst/' . $destName;
                    } else {
                        AuditLogger::registrar('WARNING', 'RepositorioPST', 'Fallo de Escritura', "El archivo para el proyecto se recibió pero el servidor no pudo guardarlo en el disco.");
                    }
                } else {
                    AuditLogger::registrar('WARNING', 'RepositorioPST', 'Fallo de Formato/MIME', "Intento de subir un archivo con extensión o firma binaria no permitida (.{$ext}, MIME: {$mimeTrad}). Archivo ignorado.");
                }
            } elseif (!empty($_POST['archivo_pdf'])) {
                $rawPdf = ltrim(str_replace(['\\', '/'], '/', trim($_POST['archivo_pdf'])), '/');
                if (strpos($rawPdf, '..') === false && (strpos($rawPdf, 'storage/documentos/tmp/') === 0 || strpos($rawPdf, 'storage/documentos/pst/') === 0)) {
                    $sourceFilePath = BASE_PATH . '/' . $rawPdf;
                    if (file_exists($sourceFilePath)) {
                        if (strpos($rawPdf, 'storage/documentos/tmp/') === 0) {
                            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', substr($_POST['titulo'] ?? 'pst', 0, 30)));
                            $ext = strtolower(pathinfo($rawPdf, PATHINFO_EXTENSION));
                            $destName = 'pst_' . $slug . '_' . time() . '_' . mt_rand(100, 999) . '.' . $ext;
                            $destPath = $storageDir . $destName;
                            if (rename($sourceFilePath, $destPath) || copy($sourceFilePath, $destPath)) {
                                @unlink($sourceFilePath);
                                $archivoPath = 'storage/documentos/pst/' . $destName;
                                $promotedTmpPath = $destPath;
                            }
                        } else {
                            $archivoPath = $rawPdf;
                        }
                    }
                }
            }

            $nivelPost = !empty($_POST['nivel_academico']) ? trim($_POST['nivel_academico']) : 'Pregrado';
            $idTrayectoPost = !empty($_POST['id_trayecto']) ? (int)$_POST['id_trayecto'] : null;
            $trayectoPost = in_array($nivelPost, $model->getNivelesConTrayecto()) ? (!empty($_POST['trayecto']) ? trim($_POST['trayecto']) : 'Trayecto I') : null;
            $idCarrera = !empty($_POST['id_carrera']) ? (int)$_POST['id_carrera'] : 1;
            $lineaId = !empty($_POST['linea_id']) ? (int)$_POST['linea_id'] : null;
            if (empty($lineaId)) {
                $lineasDisp = $model->getLineasInvestigacion($idCarrera);
                if (!empty($lineasDisp) && !empty($lineasDisp[0]['id'])) {
                    $lineaId = (int)$lineasDisp[0]['id'];
                } else {
                    $lineaId = 7;
                }
            }

            $carrerasVinculadas = [];
            if (!empty($_POST['carreras_vinculadas'])) {
                if (is_array($_POST['carreras_vinculadas'])) {
                    $carrerasVinculadas = array_map('intval', $_POST['carreras_vinculadas']);
                } else {
                    $carrerasVinculadas = array_filter(array_map('intval', explode(',', (string)$_POST['carreras_vinculadas'])));
                }
            }
            $carrerasVinculadas = array_values(array_unique(array_filter($carrerasVinculadas, fn($cId) => $cId > 0 && $cId !== $idCarrera)));

            $rawUrlGitTrad = !empty($_POST['url_repositorio']) ? trim($_POST['url_repositorio']) : null;
            $urlGitTradSanitizada = null;
            if (!empty($rawUrlGitTrad) && filter_var($rawUrlGitTrad, FILTER_VALIDATE_URL)) {
                $schemeTrad = strtolower(parse_url($rawUrlGitTrad, PHP_URL_SCHEME) ?? '');
                if (in_array($schemeTrad, ['http', 'https'], true)) {
                    $urlGitTradSanitizada = $rawUrlGitTrad;
                }
            }

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
                'id_trayecto'                => $idTrayectoPost,
                'url_repositorio'            => $urlGitTradSanitizada,
                'archivo_pdf'                => $archivoPath,
                'resumen'                    => !empty($_POST['resumen']) ? trim($_POST['resumen']) : '',
                'obj_general'                => !empty($_POST['obj_general']) ? trim($_POST['obj_general']) : null,
                'comunidad_beneficiada'      => !empty($_POST['comunidad_beneficiada']) ? trim($_POST['comunidad_beneficiada']) : '',
                'palabras_clave'             => !empty($_POST['palabras_clave']) ? trim($_POST['palabras_clave']) : '',
                'id_carrera'                 => $idCarrera,
                'linea_id'                   => $lineaId,
                'dimension_id'               => !empty($_POST['dimension_id']) ? (int)$_POST['dimension_id'] : null,
                'carreras_vinculadas'        => $carrerasVinculadas,
            ];
            
            if (empty($datos['titulo'])) {
                $error = "El título de la investigación es obligatorio.";
            } elseif ($model->existePSTPorTitulo($datos['titulo'])) {
                $error = "Ya existe un proyecto registrado con este título en el repositorio.";
            } elseif (empty($datos['autores'])) {
                $error = "Debe registrar al menos un autor para el proyecto.";
            } elseif (empty($datos['resumen'])) {
                $error = "El resumen del proyecto es obligatorio.";
            } elseif (empty($datos['archivo_pdf'])) {
                $error = "Debe adjuntar o extraer un documento digital (PDF o Word) válido para registrar el proyecto.";
            } else {
                try {
                    $nuevoId = (int)$model->crearPST($datos);
                    if ($nuevoId > 0) {
                        AuditLogger::registrar('INFO', 'RepositorioPST', 'Registrar Proyecto', "Proyecto PST registrado exitosamente: '{$datos['titulo']}' (ID: #{$nuevoId}).");
                        header("Location: ?ruta=agregar-documento&msg=created");
                        exit;
                    } else {
                        $error = "Ocurrió un error interno en la base de datos al registrar el recurso.";
                    }
                } catch (Exception $e) {
                    $error = "Error de Base de Datos: " . $e->getMessage();
                }
            }

            // Si falló el registro, limpiar el archivo definitivo recién creado para no dejar huérfanos
            if (!empty($error) && !empty($archivoPath)) {
                $realErrFile = BASE_PATH . '/' . ltrim($archivoPath, '/');
                if (file_exists($realErrFile)) {
                    @unlink($realErrFile);
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
                    $nombresPost = $_POST['autor_nombre'] ?? [];
                    $cedulasPost = $_POST['autor_cedula'] ?? [];
                    if (is_array($nombresPost)) {
                        for ($i = 0; $i < count($nombresPost); $i++) {
                            $nom = !empty($nombresPost[$i]) ? trim($nombresPost[$i]) : '';
                            $ced = !empty($cedulasPost[$i]) ? trim($cedulasPost[$i]) : '';
                            if ($nom !== '') {
                                $autoresPost[] = ['cedula' => $ced ?: null, 'nombre' => $nom];
                            }
                        }
                    }

                    $nuevoArchivoPath = null;
                    if (isset($_FILES['archivo_pst']) && $_FILES['archivo_pst']['error'] === UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($_FILES['archivo_pst']['name'], PATHINFO_EXTENSION));
                        if (in_array($ext, ['pdf', 'docx', 'doc'], true)) {
                            $finfo = finfo_open(FILEINFO_MIME_TYPE);
                            $mimeType = finfo_file($finfo, $_FILES['archivo_pst']['tmp_name']);
                            if (PHP_VERSION_ID < 80500 && is_resource($finfo)) {
                                finfo_close($finfo);
                            }

                            $allowedMimeTypes = [
                                'application/pdf',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/msword',
                                'application/CDFV2',
                                'application/vnd.ms-office',
                                'application/x-ole-storage',
                                'application/zip',
                                'application/x-zip-compressed',
                                'application/octet-stream'
                            ];

                            if (in_array($mimeType, $allowedMimeTypes, true)) {
                                $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', substr($_POST['titulo'] ?? 'pst', 0, 30)));
                                $destName = 'pst_' . $slug . '_' . time() . '_' . mt_rand(100, 999) . '.' . $ext;
                                if (move_uploaded_file($_FILES['archivo_pst']['tmp_name'], $storageDir . $destName)) {
                                    if ($ext === 'docx') {
                                        require_once __DIR__ . '/../services/ExtractorPST.php';
                                        ExtractorPST::optimizarArchivoDOCX($storageDir . $destName);
                                    }
                                    $nuevoArchivoPath = 'storage/documentos/pst/' . $destName;
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
                    } elseif (isset($_FILES['archivo_pst']) && $_FILES['archivo_pst']['error'] !== UPLOAD_ERR_NO_FILE) {
                        $codigoError = $_FILES['archivo_pst']['error'];
                        $tituloTemporal = !empty($_POST['titulo']) ? trim($_POST['titulo']) : 'Sin Título';
                        AuditLogger::registrar('WARNING', 'RepositorioPST', 'Fallo en Subida de Archivo (Edición)', "El PST '{$tituloTemporal}' se actualizó, pero el nuevo documento fue rechazado (Código: {$codigoError}).");
                    }

                    $nivelEdit = !empty($_POST['nivel_academico']) ? trim($_POST['nivel_academico']) : ($documento['nivel_academico'] ?? 'Pregrado');
                    $idTrayectoEdit = !empty($_POST['id_trayecto']) ? (int)$_POST['id_trayecto'] : null;
                    $trayectoEdit = in_array($nivelEdit, $model->getNivelesConTrayecto()) ? (!empty($_POST['trayecto']) ? trim($_POST['trayecto']) : 'Trayecto I') : null;
                    $finalEditPdf = $nuevoArchivoPath ? $nuevoArchivoPath : ($documento['archivo_pdf'] ?? null);

                    $rawUrlGitEdit = !empty($_POST['url_repositorio']) ? trim($_POST['url_repositorio']) : null;
                    $urlGitEditSanitizada = null;
                    if (!empty($rawUrlGitEdit) && filter_var($rawUrlGitEdit, FILTER_VALIDATE_URL)) {
                        $scheme = strtolower(parse_url($rawUrlGitEdit, PHP_URL_SCHEME) ?? '');
                        if (in_array($scheme, ['http', 'https'])) {
                            $urlGitEditSanitizada = $rawUrlGitEdit;
                        }
                    }

                    $idCarrera = !empty($_POST['id_carrera']) ? (int)$_POST['id_carrera'] : ($documento['carrera_id'] ?? 1);
                    $lineaId = !empty($_POST['linea_id']) ? (int)$_POST['linea_id'] : ($documento['linea_id'] ?? null);
                    if (empty($lineaId)) {
                        $lineasDisp = $model->getLineasInvestigacion($idCarrera);
                        if (!empty($lineasDisp) && !empty($lineasDisp[0]['id'])) {
                            $lineaId = (int)$lineasDisp[0]['id'];
                        } else {
                            $lineaId = 7;
                        }
                    }

                    $resumenEdit = !empty($_POST['resumen']) ? trim($_POST['resumen']) : ($documento['resumen'] ?? '');
                    if (empty($resumenEdit)) {
                        $resumenEdit = !empty($_POST['obj_general']) ? trim($_POST['obj_general']) : 'Proyecto Socio-Tecnológico indexado en el repositorio institucional.';
                    }

                    $dimensionId = !empty($_POST['dimension_id']) ? (int)$_POST['dimension_id'] : ($documento['dimension_id'] ?? null);

                    $carrerasVinculadasEdit = [];
                    if (isset($_POST['carreras_vinculadas'])) {
                        if (is_array($_POST['carreras_vinculadas'])) {
                            $carrerasVinculadasEdit = array_map('intval', $_POST['carreras_vinculadas']);
                        } else {
                            $carrerasVinculadasEdit = array_filter(array_map('intval', explode(',', (string)$_POST['carreras_vinculadas'])));
                        }
                    }
                    $carrerasVinculadasEdit = array_values(array_unique(array_filter($carrerasVinculadasEdit, fn($cId) => $cId > 0 && $cId !== $idCarrera)));

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
                        'id_trayecto'                => $idTrayectoEdit,
                        'url_repositorio'            => $urlGitEditSanitizada,
                        'archivo_pdf'                => $finalEditPdf,
                        'resumen'                    => $resumenEdit,
                        'obj_general'                => !empty($_POST['obj_general']) ? trim($_POST['obj_general']) : null,
                        'comunidad_beneficiada'      => !empty($_POST['comunidad_beneficiada']) ? trim($_POST['comunidad_beneficiada']) : '',
                        'palabras_clave'             => !empty($_POST['palabras_clave']) ? trim($_POST['palabras_clave']) : '',
                        'id_carrera'                 => $idCarrera,
                        'linea_id'                   => $lineaId,
                        'dimension_id'               => $dimensionId,
                        'carreras_vinculadas'        => $carrerasVinculadasEdit,
                    ];
                    
                    if (empty($datos['titulo'])) {
                        $error = "El título de la investigación es obligatorio.";
                    } elseif ($model->existePSTPorTitulo($datos['titulo'], $id)) {
                        $error = "Ya existe otro proyecto registrado con este título en el repositorio.";
                    } elseif (empty($datos['autores'])) {
                        $error = "Debe registrar al menos un autor para el proyecto.";
                    } else {
                        try {
                            $editado = $model->editarPST($id, $datos);
                            if ($editado) {
                                // Si se reemplazó el archivo por uno nuevo con éxito, borrar el archivo anterior del disco
                                if ($nuevoArchivoPath && !empty($documento['archivo_pdf']) && $documento['archivo_pdf'] !== $nuevoArchivoPath) {
                                    $oldFile = BASE_PATH . '/' . ltrim($documento['archivo_pdf'], '/');
                                    if (file_exists($oldFile) && is_file($oldFile)) {
                                        @unlink($oldFile);
                                    }
                                }

                                AuditLogger::registrar('INFO', 'RepositorioPST', 'Modificar Proyecto', "Proyecto PST ID #{$id} modificado exitosamente: '{$datos['titulo']}'.");

                                if (!headers_sent()) {
                                    header("Location: ?ruta=agregar-documento&msg=updated");
                                    exit;
                                } else {
                                    echo "<script>window.location.href='?ruta=agregar-documento&msg=updated';</script>";
                                    exit;
                                }
                            } else {
                                $error = "Ocurrió un error al actualizar los datos en la base de datos.";
                            }
                        } catch (Exception $e) {
                            $error = "Error de Base de Datos al actualizar: " . $e->getMessage();
                        }
                    }

                    // Si hubo error y se había subido un archivo nuevo, limpiarlo para no dejar huérfanos
                    if (!empty($error) && $nuevoArchivoPath) {
                        $errFile = BASE_PATH . '/' . ltrim($nuevoArchivoPath, '/');
                        if (file_exists($errFile) && is_file($errFile)) {
                            @unlink($errFile);
                        }
                    }

                    // Preservar los datos enviados en el formulario para que el usuario no los pierda
                    $maxAutoresCfg = max(1, (int)ConfigService::get('limites_equipo.max_autores', 4));
                    $autores = [];
                    foreach ($autoresPost as $ap) {
                        $autores[] = ['nombre_completo' => $ap['nombre'], 'cedula' => $ap['cedula'] ?? ''];
                    }
                    while (count($autores) < $maxAutoresCfg) {
                        $autores[] = ['nombre_completo' => '', 'cedula' => ''];
                    }

                    $tutores = [
                        'academico'     => ['cedula' => $_POST['tutor_academico_cedula'] ?? '', 'nombre' => $_POST['tutor_academico_nombre'] ?? ''],
                        'institucional' => ['cedula' => $_POST['tutor_institucional_cedula'] ?? '', 'nombre' => $_POST['tutor_institucional_nombre'] ?? ''],
                        'comunitario'   => ['cedula' => $_POST['tutor_comunitario_cedula'] ?? '', 'nombre' => $_POST['tutor_comunitario_nombre'] ?? ''],
                    ];

                    $documento = array_merge($documento, $datos);
                } else {
                    $maxAutoresCfg = max(1, (int)ConfigService::get('limites_equipo.max_autores', 4));
                    $autores = $model->getAutoresByRecurso($id);
                    while (count($autores) < $maxAutoresCfg) {
                        $autores[] = ['nombre_completo' => '', 'cedula' => ''];
                    }
                    
                    $tutoresBD = $model->getTutoresByRecurso($id);
                    foreach ($tutoresBD as $tBD) {
                        if ($tBD['tipo_tutor_id'] == 3) {
                            $tutores['academico'] = ['cedula' => $tBD['cedula'] ?? '', 'nombre' => $tBD['nombre_completo'] ?? ''];
                        } elseif ($tBD['tipo_tutor_id'] == 2) {
                            $tutores['institucional'] = ['cedula' => $tBD['cedula'] ?? '', 'nombre' => $tBD['nombre_completo'] ?? ''];
                        } elseif ($tBD['tipo_tutor_id'] == 4) {
                            $tutores['comunitario'] = ['cedula' => $tBD['cedula'] ?? '', 'nombre' => $tBD['nombre_completo'] ?? ''];
                        }
                    }
                }
            }
        }

        // 4. Cargar Listado Principal de Gestión
        $documentos = [];
        $pagination = [];
        $q = !empty($_GET['q']) ? trim($_GET['q']) : '';
        $filtrosAdmin = [
            'activo'          => 'todos',
            'q'               => $q !== '' ? $q : null,
            'carrera_id'      => !empty($_GET['carrera_id']) ? (int)$_GET['carrera_id'] : null,
            'nivel_academico' => !empty($_GET['nivel_academico']) ? trim($_GET['nivel_academico']) : null,
            'trayecto'        => !empty($_GET['trayecto']) ? trim($_GET['trayecto']) : null,
            'anio'            => !empty($_GET['anio']) ? (int)$_GET['anio'] : null,
            'linea_id'        => !empty($_GET['linea_id']) ? (int)$_GET['linea_id'] : null,
            'dimension_id'    => !empty($_GET['dimension_id']) ? (int)$_GET['dimension_id'] : null,
            'estado_filtro'   => !empty($_GET['estado_filtro']) ? trim($_GET['estado_filtro']) : null,
            'orden'           => !empty($_GET['orden']) ? trim($_GET['orden']) : 'recientes'
        ];
        
        if ($accion === 'listar') {
            $limitDefault = max(1, (int)ConfigService::get('paginacion.limite_catalogo', 10));
            $limit = !empty($_GET['limit']) ? max(5, min(100, (int)$_GET['limit'])) : $limitDefault;
            $page = !empty($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $offset = ($page - 1) * $limit;
            
            $documentos = $model->getPSTDocumentos($filtrosAdmin, $limit, $offset);
            $totalDocs = $model->getPSTDocumentosCount($filtrosAdmin);
            
            $totalPages = max(1, (int)ceil($totalDocs / $limit));
            if ($page > $totalPages) {
                $page = $totalPages;
                $offset = ($page - 1) * $limit;
                $documentos = $model->getPSTDocumentos($filtrosAdmin, $limit, $offset);
            }

            $pagination = [
                'current_page' => $page,
                'total_pages'  => $totalPages,
                'total_items'  => $totalDocs,
                'limit'        => $limit
            ];
        }

        $carreras = $model->getCarreras();
        $lineas = $model->getLineasInvestigacion(!empty($filtrosAdmin['carrera_id']) ? (int)$filtrosAdmin['carrera_id'] : null);
        $dimensiones = $model->getDimensionesOperativas();
        $nivelesAcademicos = $model->getNivelesAcademicos();
        $carreraFormId = !empty($documento['carrera_id']) ? (int)$documento['carrera_id'] : (!empty($documento['id_carrera']) ? (int)$documento['id_carrera'] : (!empty($_GET['carrera_id']) ? (int)$_GET['carrera_id'] : 1));
        $trayectosList = $model->getTrayectos($carreraFormId);
        
        $statsResumen = $model->getPSTStatsResumen();
        
        return [
            'accion'            => $accion,
            'documentos'        => $documentos,
            'documento'         => $documento,
            'autores'           => $autores,
            'tutores'           => $tutores,
            'carreras'          => $carreras,
            'lineas'             => $lineas,
            'todasLineas'        => $model->getLineasInvestigacion(null),
            'dimensiones'        => $dimensiones,
            'nivelesAcademicos'  => $nivelesAcademicos,
            'nivelesConTrayecto' => $model->getNivelesConTrayecto(),
            'labelsNiveles'      => $model->getNivelAcademicoMap(),
            'trayectosList'      => $trayectosList,
            'pagination'        => $pagination,
            'statsResumen'      => $statsResumen,
            'filtrosAdmin'      => $filtrosAdmin,
            'q'                 => $q,
            'error'             => $error,
            'success'           => $success
        ];
    }

    /**
     * Endpoint público/AJAX para consultar trayectos asociados a una carrera.
     */
    public function obtenerTrayectosPorCarrera(): void {
        header('Content-Type: application/json; charset=utf-8');
        $carreraIdReq = !empty($_GET['carrera_id']) ? (int)$_GET['carrera_id'] : 1;
        $model = new DocumentoModel();
        $trayectosCarrera = $model->getTrayectos($carreraIdReq);
        echo json_encode([
            'status'    => 'success',
            'trayectos' => $trayectosCarrera
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
