<?php
// modules/RepositorioPST/controllers/DetallePSTController.php
require_once __DIR__ . '/../models/DocumentoModel.php';

class DetallePSTController {
    
    public function index(): array {
        $model = new DocumentoModel();
        
        // Paginación
        $limit = 5; // Mostrar 5 registros por página
        $page = !empty($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;
        
        // Capturar parámetros GET para el filtrado dinámico
        $filtros = [
            'linea_id'   => !empty($_GET['linea_id']) ? (int)$_GET['linea_id'] : null,
            'dimension_id' => !empty($_GET['dimension_id']) ? (int)$_GET['dimension_id'] : null,
        ];
        
        // Cargar los documentos filtrados y paginados
        $documentos = $model->getPSTDocumentos($filtros, $limit, $offset);
        $totalDocs = $model->getPSTDocumentosCount($filtros);
        $totalPages = ceil($totalDocs / $limit);
        
        // Obtener líneas y dimensiones
        $lineas = $model->getLineasInvestigacion();
        $dimensiones = $model->getDimensionesOperativas();
        
        return [
            'documentos'  => $documentos,
            'lineas'      => $lineas,
            'dimensiones' => $dimensiones,
            'filtros'     => $filtros,
            'pagination'  => [
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
        if ($id) {
            $documento = $model->getPSTDocumentoById($id);
        }
        
        return [
            'documento' => $documento
        ];
    }

    public function crear(): array {
        $model = new DocumentoModel();
        
        $accion = !empty($_GET['accion']) ? trim($_GET['accion']) : 'listar';
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        
        // 0. Procesar Acción: EXTRAER METADATOS DE ARCHIVOS (AJAX)
        if ($accion === 'extraer' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json; charset=utf-8');
            try {
                if (!isset($_FILES['archivo_pst']) || $_FILES['archivo_pst']['error'] !== UPLOAD_ERR_OK) {
                    $errorCode = $_FILES['archivo_pst']['error'] ?? 'desconocido';
                    throw new Exception("Error al cargar el archivo en el servidor. Código: " . $errorCode);
                }

                $fileTmpPath = $_FILES['archivo_pst']['tmp_name'];
                $fileName = $_FILES['archivo_pst']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (!in_array($fileExtension, ['pdf', 'docx'])) {
                    throw new Exception("Formato de archivo no soportado. Debe ser un archivo PDF o Word (.docx).");
                }

                require_once __DIR__ . '/../services/ExtractorPST.php';

                $text = '';
                if ($fileExtension === 'pdf') {
                    $text = ExtractorPST::extraerTextoPDF($fileTmpPath);
                } else {
                    $text = ExtractorPST::extraerTextoDOCX($fileTmpPath);
                }

                if (empty(trim($text))) {
                    throw new Exception("No se pudo extraer texto del documento. Asegúrese de que el archivo no esté protegido o vacío.");
                }

                $datosExtraidos = ExtractorPST::analizarTexto($text);

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Metadatos extraídos con éxito.',
                    'data' => $datosExtraidos
                ], JSON_UNESCAPED_UNICODE);
                
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], JSON_UNESCAPED_UNICODE);
            }
            exit; // Detiene el Kernel
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
            }
        }
        
        // 1. Procesar Acción: ELIMINAR
        if ($accion === 'eliminar' && $id) {
            try {
                $model->eliminarPST($id);
                header("Location: ?ruta=agregar-documento&msg=deleted");
                echo "<script>window.location.href='?ruta=agregar-documento&msg=deleted';</script>";
                exit;
            } catch (Exception $e) {
                $error = "Error al intentar eliminar el recurso: " . $e->getMessage();
                $accion = 'listar';
            }
        }
        
        // 2. Procesar Acción: CREAR / REGISTRAR
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
                'nivel_academico'            => !empty($_POST['nivel_academico']) ? trim($_POST['nivel_academico']) : 'Pregrado',
                'resumen'                    => !empty($_POST['resumen']) ? trim($_POST['resumen']) : '',
                'comunidad_beneficiada'      => !empty($_POST['comunidad_beneficiada']) ? trim($_POST['comunidad_beneficiada']) : '',
                'palabras_clave'             => !empty($_POST['palabras_clave']) ? trim($_POST['palabras_clave']) : '',
                'linea_id'                   => !empty($_POST['linea_id']) ? (int)$_POST['linea_id'] : null,
                'dimension_id'               => !empty($_POST['dimension_id']) ? (int)$_POST['dimension_id'] : null,
            ];
            
            if (empty($datos['titulo'])) {
                $error = "El título de la investigación es obligatorio.";
            } elseif (empty($datos['autores'])) {
                $error = "Debe registrar al menos un autor principal (cédula y nombre).";
            } elseif (empty($datos['resumen'])) {
                $error = "El resumen del proyecto es obligatorio.";
            } elseif (empty($datos['linea_id'])) {
                $error = "Debe clasificar el proyecto bajo una línea de investigación.";
            } else {
                try {
                    $guardado = $model->crearPST($datos);
                    if ($guardado) {
                        header("Location: ?ruta=agregar-documento&msg=created");
                        echo "<script>window.location.href='?ruta=agregar-documento&msg=created';</script>";
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
                // Si es un POST, guardamos los cambios
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
                        'nivel_academico'            => !empty($_POST['nivel_academico']) ? trim($_POST['nivel_academico']) : 'Pregrado',
                        'resumen'                    => !empty($_POST['resumen']) ? trim($_POST['resumen']) : '',
                        'comunidad_beneficiada'      => !empty($_POST['comunidad_beneficiada']) ? trim($_POST['comunidad_beneficiada']) : '',
                        'palabras_clave'             => !empty($_POST['palabras_clave']) ? trim($_POST['palabras_clave']) : '',
                        'linea_id'                   => !empty($_POST['linea_id']) ? (int)$_POST['linea_id'] : null,
                        'dimension_id'               => !empty($_POST['dimension_id']) ? (int)$_POST['dimension_id'] : null,
                    ];
                    
                    if (empty($datos['titulo'])) {
                        $error = "El título de la investigación es obligatorio.";
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
                            } else {
                                $error = "Ocurrió un error al intentar actualizar el recurso.";
                            }
                        } catch (Exception $e) {
                            $error = "Error de Base de Datos al actualizar: " . $e->getMessage();
                        }
                    }
                } else {
                    // Cargar autores individuales y mapear a 4 slots
                    $autores = $model->getAutoresByRecurso($id);
                    while (count($autores) < 4) {
                        $autores[] = ['nombre_completo' => '', 'cedula' => ''];
                    }
                    
                    // Cargar tutores individuales y mapear por tipo
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
            $limit = 10; // Mostrar 10 en gestión
            $page = !empty($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $offset = ($page - 1) * $limit;
            
            // Reutilizar buscarStandard o getPSTDocumentos para listar con búsqueda
            if (!empty($q)) {
                $documentos = $model->buscarStandard($q, [], $limit, $offset);
                $totalDocs = $model->buscarStandardCount($q, []);
            } else {
                $documentos = $model->getPSTDocumentos([], $limit, $offset);
                $totalDocs = $model->getPSTDocumentosCount([]);
            }
            
            $totalPages = ceil($totalDocs / $limit);
            $pagination = [
                'current_page' => $page,
                'total_pages'  => $totalPages,
                'total_items'  => $totalDocs,
                'limit'        => $limit
            ];
        }

        $lineas = $model->getLineasInvestigacion();
        $dimensiones = $model->getDimensionesOperativas();
        
        return [
            'accion'      => $accion,
            'documentos'  => $documentos,
            'documento'   => $documento,
            'autores'     => $autores,
            'tutores'     => $tutores,
            'lineas'      => $lineas,
            'dimensiones' => $dimensiones,
            'pagination'  => $pagination,
            'q'           => $q,
            'error'       => $error,
            'success'     => $success
        ];
    }
}
