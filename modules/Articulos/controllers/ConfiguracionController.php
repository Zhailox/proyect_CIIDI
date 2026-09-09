<?php
// modules/Articulos/controllers/ConfiguracionController.php
require_once CORE_PATH . 'Database/QueryBuilder.php';
require_once __DIR__ . '/../services/ConfigService.php';

class ConfiguracionController {

    public function index(): array {
        require_once CORE_PATH . 'Security/Auth.php';
        Auth::requierePrivilegioMinimo(2); 

        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $mensaje = null;
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
                $error = "Petición rechazada por seguridad (Token CSRF inválido o expirado).";
            } else {
                try {
                $actual = ConfigService::get() ?? [];

                // 1. Citas
                if (!empty($_POST['eliminar_estilo']) && is_string($_POST['eliminar_estilo'])) {
                    unset($actual['citas']['estilos'][trim($_POST['eliminar_estilo'])]);
                }
                if (isset($_POST['citas_estilos']) && is_array($_POST['citas_estilos'])) {
                    foreach ($_POST['citas_estilos'] as $slug => $item) {
                        if (isset($actual['citas']['estilos'][$slug])) {
                            $actual['citas']['estilos'][$slug]['nombre'] = trim($item['nombre'] ?? '');
                            $actual['citas']['estilos'][$slug]['activo'] = isset($item['activo']) && $item['activo'] === '1';
                            $actual['citas']['estilos'][$slug]['plantilla'] = trim($item['plantilla'] ?? '');
                        }
                    }
                }
                if (!empty($_POST['nuevo_estilo_slug']) && !empty($_POST['nuevo_estilo_nombre'])) {
                    $slugNew = preg_replace('/[^a-z0-9_]/', '', strtolower(trim($_POST['nuevo_estilo_slug'])));
                    if (!empty($slugNew)) {
                        $actual['citas']['estilos'][$slugNew] = [
                            'nombre' => trim($_POST['nuevo_estilo_nombre']),
                            'activo' => true,
                            'plantilla' => trim($_POST['nuevo_estilo_plantilla'] ?? '{autores} ({anio}). {titulo}.')
                        ];
                    }
                }

                // 2. Paginación
                if (isset($_POST['limite_catalogo'])) $actual['paginacion']['limite_catalogo'] = max(1, (int)$_POST['limite_catalogo']);
                if (isset($_POST['limite_gestor'])) $actual['paginacion']['limite_gestor'] = max(1, (int)$_POST['limite_gestor']);
                if (isset($_POST['max_recomendados'])) $actual['paginacion']['max_recomendados'] = max(1, (int)$_POST['max_recomendados']);
                if (isset($_POST['limite_gestor_catalogos'])) $actual['paginacion']['limite_gestor_catalogos'] = max(1, (int)$_POST['limite_gestor_catalogos']);
                // 3. Recursos (Metadatos visuales)
                $actual['recursos']['mostrar_editorial'] = isset($_POST['mostrar_editorial']) && $_POST['mostrar_editorial'] === '1';
                $actual['recursos']['mostrar_volumen'] = isset($_POST['mostrar_volumen']) && $_POST['mostrar_volumen'] === '1';
                $actual['recursos']['mostrar_issn'] = isset($_POST['mostrar_issn']) && $_POST['mostrar_issn'] === '1';
                // El buscador que estaba resagado 
                if (isset($_POST['anio_minimo'])) {
                    $actual['buscador']['anio_minimo'] = (int)$_POST['anio_minimo'];
                }
                // 4. Archivos (Imágenes y Extensiones)
                if (isset($_POST['max_size_mb'])) $actual['archivos']['max_size_mb'] = max(1, (int)$_POST['max_size_mb']);
                if (!empty($_POST['extensiones_permitidas_raw'])) {
                    $exts = explode(',', $_POST['extensiones_permitidas_raw']);
                    $cleanExts = [];
                    foreach ($exts as $ext) {
                        $ext = strtolower(trim($ext));
                        // Asegurar formato ".ext"
                        if ($ext !== '' && $ext !== '.') {
                             $cleanExts[] = (strpos($ext, '.') === 0) ? $ext : '.' . $ext;
                        }
                    }
                    if (!empty($cleanExts)) {
                        $actual['archivos']['extensiones_permitidas'] = array_unique($cleanExts);
                    }
                }


                if (ConfigService::save($actual)) {
                    $mensaje = "¡Configuración de la Revista guardada exitosamente!";
                } else {
                    $error = "No se pudo guardar la configuración.";
                }
            } catch (Exception $e) {
                $error = "Error: " . $e->getMessage();
            }
        }
        }

        $imagenesStorage = [];
        $dirUploads = realpath(__DIR__ . '/../../../storage/uploads/articulos');

        if ($dirUploads && is_dir($dirUploads)) {
            $qb = new QueryBuilder();
            $detallesUso = $qb->tabla('detalles_articulos')->select('id_recurso, imagen_portada')->get();
            
            $mapaPortadas = [];
            foreach ($detallesUso as $det) {
                if (!empty($det['imagen_portada'])) {
                    $mapaPortadas[trim($det['imagen_portada'])] = (int)$det['id_recurso'];
                }
            }

            $archivos = array_diff(scandir($dirUploads), ['.', '..']);
            foreach ($archivos as $archivo) {
                $rutaCompleta = $dirUploads . DIRECTORY_SEPARATOR . $archivo;
                if (is_file($rutaCompleta) && @getimagesize($rutaCompleta) !== false) {
                    $esDefault = ($archivo === 'default_article.jpg');
                    $articuloId = $mapaPortadas[$archivo] ?? null;
                    $enUso = ($articuloId !== null) || $esDefault;

                    $mtime = filemtime($rutaCompleta);
                    $imagenesStorage[] = [
                        'nombre' => $archivo,
                        'url' => '../storage/uploads/articulos/' . htmlspecialchars($archivo),
                        'peso_kb' => round(filesize($rutaCompleta) / 1024, 2),
                        'fecha' => date("Y-m-d H:i", $mtime),
                        'mtime' => $mtime,
                        'en_uso' => $enUso,
                        'articulo_id' => $articuloId,
                        'es_default' => $esDefault
                    ];
                }
            }

            usort($imagenesStorage, function($a, $b) {
                return $b['mtime'] - $a['mtime'];
            });
        }

        return [
            'config'  => ConfigService::get(),
            'mensaje' => $mensaje,
            'error'   => $error,
            'imagenesStorage' => $imagenesStorage
        ];
    }

    public function eliminarImagen() {
        require_once CORE_PATH . 'Security/Auth.php';
        Auth::requierePrivilegioMinimo(2);

        if (session_status() === PHP_SESSION_NONE) session_start();
        header('Content-Type: application/json; charset=utf-8');

        $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $token = $data['csrf_token'] ?? '';
        $nombreImg = basename(trim($data['nombre'] ?? ''));

        if (empty($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            echo json_encode(['success' => false, 'error' => 'Token CSRF inválido o expirado.']);
            exit;
        }

        if (empty($nombreImg) || $nombreImg === 'default_article.jpg') {
            echo json_encode(['success' => false, 'error' => 'No está permitido borrar la imagen por defecto o un nombre vacío.']);
            exit;
        }

        $qb = new QueryBuilder();
        $enUso = $qb->tabla('detalles_articulos')->where('imagen_portada', '=', $nombreImg)->count();
        if ($enUso > 0) {
            echo json_encode(['success' => false, 'error' => 'Esta imagen está asignada a uno o más artículos activos y no puede ser eliminada.']);
            exit;
        }

        $dirUploads = realpath(__DIR__ . '/../../../storage/uploads/articulos');
        if ($dirUploads) {
            $rutaFisica = $dirUploads . DIRECTORY_SEPARATOR . $nombreImg;
            if (file_exists($rutaFisica) && is_file($rutaFisica)) {
                if (@unlink($rutaFisica)) {
                    echo json_encode(['success' => true, 'mensaje' => 'Imagen eliminada del almacenamiento.']);
                    exit;
                }
            }
        }

        echo json_encode(['success' => false, 'error' => 'El archivo no fue encontrado o no se pudo eliminar del servidor.']);
        exit;
    }
}