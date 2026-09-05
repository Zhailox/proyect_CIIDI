<?php
require_once CORE_PATH . 'Security/Auth.php';
require_once __DIR__ . '/../models/ArticuloModel.php';
require_once __DIR__ . '/../services/ConfigService.php';

class ArticulosController {
    
    private $articuloModel;

    public function __construct() {
        $this->articuloModel = new ArticuloModel();
    }

    public function index() {
        $filtros = [
            'q' => trim($_GET['q'] ?? ''),
            'year' => !empty($_GET['year']) ? (int) $_GET['year'] : '',
            'categorias' => array_filter(array_map('intval', $_GET['categoria'] ?? [])),
            'etiquetas' => array_filter(array_map('intval', $_GET['etiqueta'] ?? [])),
            'activo' => true
        ];

        $pagina = max(1, (int) ($_GET['page'] ?? 1));
        $porPagina = ConfigService::get('paginacion.limite_catalogo', 16); // Límite dinámico

        $paginacion = $this->articuloModel->obtenerArticulosPaginados($filtros, $pagina, $porPagina);
        $categorias = $this->articuloModel->obtenerCategorias();
        $etiquetas = $this->articuloModel->obtenerEtiquetas();

        return [
            'articulos' => $paginacion['articulos'],
            'categorias' => $categorias,
            'etiquetas' => $etiquetas,
            'filtros' => $filtros,
            'paginacion' => $paginacion
        ];
    }

    public function leer() {
        $id = (int)($_GET['id'] ?? 0);
        $maxRecomendados = ConfigService::get('paginacion.max_recomendados', 3);

        return [
            'articulo' => $this->articuloModel->obtenerArticuloPorId($id),
            'similares' => $this->articuloModel->getArticulosSimilares($id, $maxRecomendados)
        ];
    }

    public function gestor() {
        Auth::requierePrivilegioMinimo(2);
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $filtros = [
            'q' => trim($_GET['q'] ?? ''),
            'activo' => 'todos' // 
        ];
        $pagina = max(1, (int) ($_GET['page'] ?? 1));
        $porPagina = ConfigService::get('paginacion.limite_gestor', 15); // Límite dinámico

        $paginacion = $this->articuloModel->obtenerArticulosPaginados($filtros, $pagina, $porPagina);

        return [
            'articulos' => $paginacion['articulos'],
            'paginacion' => $paginacion,
            'filtros' => $filtros
        ];
    }
    public function nuevo() {
        // Candado: Solo administradores o bibliotecarios
        Auth::requierePrivilegioMinimo(2);
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $qb = new QueryBuilder();
        
        // Obtenemos los catálogos para llenar los <select> del formulario
        $editoriales = $qb->tabla('editoriales')->orderBy('nombre', 'ASC')->get();
        $autores = $qb->tabla('autores')->orderBy('nombre_completo', 'ASC')->get();

        return [
            'editoriales' => $editoriales,
            'autores' => $autores
        ];
    }
    public function procesar() {
        Auth::requierePrivilegioMinimo(2);
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
            $_SESSION['mensaje_error'] = "Petición rechazada por seguridad (Token CSRF inválido o expirado).";
            header('Location: gestor-articulos');
            exit;
        }
        // 1. Detección profunda de límite del servidor (post_max_size)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) && isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH'] > 0) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $maxServidor = ini_get('post_max_size');
            $_SESSION['mensaje_error'] = "El archivo enviado es tan masivo que bloqueó el servidor. (Límite estricto en php.ini: {$maxServidor}).";
            header('Location: gestor-articulos');
            exit;
        }

        // Bloqueo de seguridad si intentan acceder por URL directa
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: gestor-articulos');
            exit;
        }

        // 1. Recibir datos básicos
        $titulo = htmlspecialchars(trim($_POST['titulo'] ?? ''), ENT_QUOTES, 'UTF-8');
        $resumen = htmlspecialchars(trim($_POST['resumen'] ?? ''), ENT_QUOTES, 'UTF-8');
        $volumen = htmlspecialchars(trim($_POST['volumen'] ?? ''), ENT_QUOTES, 'UTF-8');
        $numero = htmlspecialchars(trim($_POST['numero'] ?? ''), ENT_QUOTES, 'UTF-8');
        $issn = htmlspecialchars(trim($_POST['issn'] ?? ''), ENT_QUOTES, 'UTF-8');

        $categorias = array_values(array_filter(array_map('intval', $_POST['categorias'] ?? [])));
        $id_editorial = !empty($_POST['id_editorial']) ? (int)$_POST['id_editorial'] : null;
        $anio_publicacion = !empty($_POST['anio_publicacion']) ? (int)$_POST['anio_publicacion'] : (int)date('Y');
        
        $archivo_pdf = filter_var(trim($_POST['archivo_pdf'] ?? ''), FILTER_SANITIZE_URL);
        $url_imagen = filter_var(trim($_POST['url_imagen'] ?? ''), FILTER_SANITIZE_URL);
        
        $autores = $_POST['autores'] ?? [];
        $autores_nuevos = $_POST['autores_nuevos'] ?? [];
        $etiquetas = $_POST['etiquetas'] ?? [];

        // 2. Manejar la imagen de portada
        $nombreImagen = 'default_article.jpg';
        
        // A. Si escribieron una URL externa, la tomamos primero
        if (!empty($url_imagen)) {
            $nombreImagen = $url_imagen;
        }

        // B. Si subieron un archivo físico o el servidor detecta un intento de subida
        if (isset($_FILES['imagen_portada']) && $_FILES['imagen_portada']['error'] !== UPLOAD_ERR_NO_FILE) {
            
            if (session_status() === PHP_SESSION_NONE) session_start();

            // Error 1: Bloqueo desde la configuración central de PHP (php.ini)
            if ($_FILES['imagen_portada']['error'] === UPLOAD_ERR_INI_SIZE) {
                $_SESSION['mensaje_error'] = "El servidor (php.ini) rechazó la imagen porque excede su límite global de " . ini_get('upload_max_filesize') . "B.";
                header('Location: ' . (isset($id) ? "editar-articulo?id=$id" : "nuevo-articulo"));
                exit;
            }

            // Si llegó bien a PHP, validamos según nuestro JSON y de una le verificamos el MIME
            if ($_FILES['imagen_portada']['error'] === UPLOAD_ERR_OK) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                //MIME cosa
                $mimeType = finfo_file($finfo, $_FILES['imagen_portada']['tmp_name']);
                finfo_close($finfo);

                $mimePermitidos = ['image/jpeg', 'image/png', 'image/webp'];
                if (!in_array($mimeType, $mimePermitidos)) {
                    $_SESSION['mensaje_error'] = "El contenido del archivo está corrupto o no es una imagen válida (MIME: $mimeType).";
                    header('Location: ' . (isset($id) ? "editar-articulo?id=$id" : "nuevo-articulo"));
                    exit;
                }
                $ext = strtolower(pathinfo($_FILES['imagen_portada']['name'], PATHINFO_EXTENSION));
                $extConPunto = '.' . $ext;
                
                $extsPermitidas = ConfigService::get('archivos.extensiones_permitidas', ['.jpg', '.jpeg', '.png', '.webp']);
                $maxMb = (int)ConfigService::get('archivos.max_size_mb', 5);
                $maxBytes = $maxMb * 1024 * 1024;

                if ($_FILES['imagen_portada']['size'] > $maxBytes) {
                    $_SESSION['mensaje_error'] = "La imagen de portada excede nuestro peso máximo permitido de {$maxMb}MB.";
                    header('Location: ' . (isset($id) ? "editar-articulo?id=$id" : "nuevo-articulo"));
                    exit;
                }

                $extensionValida = false;
                foreach ($extsPermitidas as $permitida) {
                    if ($extConPunto === $permitida || $ext === str_replace('.', '', $permitida)) {
                        $extensionValida = true;
                        break;
                    }
                }

                if (!$extensionValida) {
                    $_SESSION['mensaje_error'] = "Formato no admitido. Permitidos: " . implode(', ', $extsPermitidas);
                    header('Location: ' . (isset($id) ? "editar-articulo?id=$id" : "nuevo-articulo"));
                    exit;
                }

               $nombreImagen = 'art_' . time() . '_' . uniqid() . '.webp';
                $destino = __DIR__ . '/../../../public/uploads/articulos/';
                if (!is_dir($destino)) mkdir($destino, 0777, true);
                
                $rutaDestino = $destino . $nombreImagen;
                $tmpPath = $_FILES['imagen_portada']['tmp_name'];
                $imagenOriginal = null;

                // Crear instancia de imagen según su MIME real
                if ($mimeType === 'image/jpeg') {
                    $imagenOriginal = imagecreatefromjpeg($tmpPath);
                } elseif ($mimeType === 'image/png') {
                    $imagenOriginal = imagecreatefrompng($tmpPath);
                    // Preservar transparencia en PNGs
                    imagepalettetotruecolor($imagenOriginal);
                    imagealphablending($imagenOriginal, true);
                    imagesavealpha($imagenOriginal, true);
                } elseif ($mimeType === 'image/webp') {
                    $imagenOriginal = imagecreatefromwebp($tmpPath);
                }

                // Generar y guardar como WebP con 85% de calidad (balance peso/calidad)
                if ($imagenOriginal) {
                    imagewebp($imagenOriginal, $rutaDestino, 85);
                    imagedestroy($imagenOriginal);
                } else {
                    // Fallback de seguridad por si falla la librería GD
                    move_uploaded_file($tmpPath, $rutaDestino);
                }
            }
        }

        if (session_status() === PHP_SESSION_NONE) session_start();

        // 3. Mandar al modelo para insertar
        try {
            $this->articuloModel->registrarArticulo(
                $titulo,
                $resumen,
                $categorias,
                $id_editorial,
                $archivo_pdf,
                $anio_publicacion,
                $volumen,
                $numero,
                $issn,
                $nombreImagen,
                $autores,
                $autores_nuevos,
                $etiquetas
                        );

            $_SESSION['mensaje_exito'] = "El artículo fue publicado correctamente en la vitrina.";
            header('Location: gestor-articulos');
            exit;

        } catch (Exception $e) {
            // Si hubo un error (ej. cédula de autor repetida)
            $_SESSION['mensaje_error'] = "Error de base de datos: " . $e->getMessage();
            header('Location: nuevo-articulo');
            exit;
        }
    }
    public function eliminar() {
        Auth::requierePrivilegioMinimo(2);
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
            $_SESSION['mensaje_error'] = "Petición rechazada por seguridad (Token CSRF inválido o expirado).";
            header('Location: gestor-articulos');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_articulo = (int)$_POST['id_articulo'];
            
            try {
                $this->articuloModel->eliminarArticulo($id_articulo);
                
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_exito'] = "El artículo ha sido eliminado del catálogo y sus archivos liberados.";
                
            } catch (Exception $e) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_error'] = "No se pudo eliminar el artículo: " . $e->getMessage();
            }
        }
        
        // Redirigimos de vuelta a la tabla
        header('Location: gestor-articulos');
        exit;
    }
    public function editar() {
        Auth::requierePrivilegioMinimo(2);
        if (session_status() === PHP_SESSION_NONE) session_start();
            if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $id = (int)($_GET['id'] ?? 0);

        $articulo = $this->articuloModel->obtenerArticuloPorId($id);
        if (!$articulo) {
            header('Location: gestor-articulos');
            exit;
        }

        $qb = new QueryBuilder();
        $editoriales = $qb->tabla('editoriales')->orderBy('nombre', 'ASC')->get();
        $autores = $qb->tabla('autores')->orderBy('nombre_completo', 'ASC')->get();

        return [
            'articulo' => $articulo,
            'editoriales' => $editoriales,
            'autores' => $autores,
            'autoresSeleccionados' => $this->articuloModel->obtenerAutoresDelArticulo($id),
            'etiquetasSeleccionadas' => $this->articuloModel->obtenerEtiquetasDelArticulo($id),
            'categoriasSeleccionadas' => $this->articuloModel->obtenerCategoriasDelArticulo($id)
        ];
    }

    public function actualizar() {
        Auth::requierePrivilegioMinimo(2);
        if (session_status() === PHP_SESSION_NONE) session_start();
            if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
                $_SESSION['mensaje_error'] = "Petición rechazada por seguridad (Token CSRF inválido o expirado).";
                header('Location: gestor-articulos');
                exit;
            }
        // 1. Detección profunda de límite del servidor (post_max_size)
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) && isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH'] > 0) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $maxServidor = ini_get('post_max_size');
                $_SESSION['mensaje_error'] = "El archivo enviado es tan masivo que bloqueó el servidor. (Límite estricto en php.ini: {$maxServidor}).";
                header('Location: gestor-articulos');
                exit;
            }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: gestor-articulos');
            exit;
        }

        $id = (int)($_POST['id_articulo'] ?? 0);
        
        $titulo = htmlspecialchars(trim($_POST['titulo'] ?? ''), ENT_QUOTES, 'UTF-8');
        $resumen = htmlspecialchars(trim($_POST['resumen'] ?? ''), ENT_QUOTES, 'UTF-8');
        $volumen = htmlspecialchars(trim($_POST['volumen'] ?? ''), ENT_QUOTES, 'UTF-8');
        $numero = htmlspecialchars(trim($_POST['numero'] ?? ''), ENT_QUOTES, 'UTF-8');
        $issn = htmlspecialchars(trim($_POST['issn'] ?? ''), ENT_QUOTES, 'UTF-8');
        
        $categorias = array_values(array_filter(array_map('intval', $_POST['categorias'] ?? [])));
        $id_editorial = !empty($_POST['id_editorial']) ? (int)$_POST['id_editorial'] : null;
        $anio_publicacion = !empty($_POST['anio_publicacion']) ? (int)$_POST['anio_publicacion'] : (int)date('Y');
        
        $archivo_pdf = filter_var(trim($_POST['archivo_pdf'] ?? ''), FILTER_SANITIZE_URL);
        $url_imagen = filter_var(trim($_POST['url_imagen'] ?? ''), FILTER_SANITIZE_URL);

        $autores = $_POST['autores'] ?? [];
        $autores_nuevos = $_POST['autores_nuevos'] ?? [];
        $etiquetas = $_POST['etiquetas'] ?? [];

        $nombreImagen = trim($_POST['imagen_actual'] ?? 'default_article.jpg');
        

        if (!empty($url_imagen)) {
            $nombreImagen = $url_imagen;
        }
        // B. Si subieron un archivo físico o el servidor detecta un intento de subida
            if (isset($_FILES['imagen_portada']) && $_FILES['imagen_portada']['error'] !== UPLOAD_ERR_NO_FILE) {
                
                if (session_status() === PHP_SESSION_NONE) session_start();

                // Error 1: Bloqueo desde la configuración central de PHP (php.ini)
                if ($_FILES['imagen_portada']['error'] === UPLOAD_ERR_INI_SIZE) {
                    $_SESSION['mensaje_error'] = "El servidor (php.ini) rechazó la imagen porque excede su límite global de " . ini_get('upload_max_filesize') . "B.";
                    header('Location: ' . (isset($id) ? "editar-articulo?id=$id" : "nuevo-articulo"));
                    exit;
                }

                // Si llegó bien a PHP, validamos según nuestro JSON
                if ($_FILES['imagen_portada']['error'] === UPLOAD_ERR_OK) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimeType = finfo_file($finfo, $_FILES['imagen_portada']['tmp_name']);
                    finfo_close($finfo);

                    $mimePermitidos = ['image/jpeg', 'image/png', 'image/webp'];
                    if (!in_array($mimeType, $mimePermitidos)) {
                        $_SESSION['mensaje_error'] = "El contenido del archivo está corrupto o no es una imagen válida (MIME: $mimeType).";
                        header('Location: ' . (isset($id) ? "editar-articulo?id=$id" : "nuevo-articulo"));
                        exit;
                    }
                    $ext = strtolower(pathinfo($_FILES['imagen_portada']['name'], PATHINFO_EXTENSION));
                    $extConPunto = '.' . $ext;
                    
                    $extsPermitidas = ConfigService::get('archivos.extensiones_permitidas', ['.jpg', '.jpeg', '.png', '.webp']);
                    $maxMb = (int)ConfigService::get('archivos.max_size_mb', 5);
                    $maxBytes = $maxMb * 1024 * 1024;

                    if ($_FILES['imagen_portada']['size'] > $maxBytes) {
                        $_SESSION['mensaje_error'] = "La imagen de portada excede nuestro peso máximo permitido de {$maxMb}MB.";
                        header('Location: ' . (isset($id) ? "editar-articulo?id=$id" : "nuevo-articulo"));
                        exit;
                    }

                    $extensionValida = false;
                    foreach ($extsPermitidas as $permitida) {
                        if ($extConPunto === $permitida || $ext === str_replace('.', '', $permitida)) {
                            $extensionValida = true;
                            break;
                        }
                    }

                    if (!$extensionValida) {
                        $_SESSION['mensaje_error'] = "Formato no admitido. Permitidos: " . implode(', ', $extsPermitidas);
                        header('Location: ' . (isset($id) ? "editar-articulo?id=$id" : "nuevo-articulo"));
                        exit;
                    }

                    $nombreImagen = 'art_' . time() . '_' . uniqid() . '.webp';
                    $destino = __DIR__ . '/../../../public/uploads/articulos/';
                    if (!is_dir($destino)) mkdir($destino, 0777, true); 
                    
                    $rutaDestino = $destino . $nombreImagen;
                    $tmpPath = $_FILES['imagen_portada']['tmp_name'];
                    $imagenOriginal = null;

                    // Crear instancia de imagen según su MIME real
                    if ($mimeType === 'image/jpeg') {
                        $imagenOriginal = imagecreatefromjpeg($tmpPath);
                    } elseif ($mimeType === 'image/png') {
                        $imagenOriginal = imagecreatefrompng($tmpPath);
                        // Preservar transparencia en PNGs
                        imagepalettetotruecolor($imagenOriginal);
                        imagealphablending($imagenOriginal, true);
                        imagesavealpha($imagenOriginal, true);
                    } elseif ($mimeType === 'image/webp') {
                        $imagenOriginal = imagecreatefromwebp($tmpPath);
                    }

                    // Generar y guardar como WebP con 85% de calidad (balance peso/calidad)
                    if ($imagenOriginal) {
                        imagewebp($imagenOriginal, $rutaDestino, 85);
                        imagedestroy($imagenOriginal);
                    } else {
                        // Fallback de seguridad por si falla la librería GD
                        move_uploaded_file($tmpPath, $rutaDestino);
                    }
                }
            }

        try {
            $this->articuloModel->actualizarArticulo(
                $id,
                $titulo,
                $resumen,
                $categorias,
                $id_editorial,
                $archivo_pdf,
                $anio_publicacion,
                $volumen,
                $numero,
                $issn,
                $nombreImagen,
                $autores,
                $autores_nuevos,
                $etiquetas
            );

            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_exito'] = 'El artículo fue actualizado correctamente.';
            header('Location: gestor-articulos');
            exit;
        } catch (Exception $e) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_error'] = 'No se pudo actualizar el artículo: ' . $e->getMessage();
            header('Location: editar-articulo?id=' . $id);
            exit;
        }
    }
    public function gestorCatalogos() {
        Auth::requierePrivilegioMinimo(2);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) session_start();

            $accion = $_POST['accion'] ?? '';
            $nombre = trim($_POST['nombre'] ?? '');

            try {
                if ($accion === 'crear_categoria' && $nombre !== '') {
                    $this->articuloModel->crearCategoria($nombre);
                    $_SESSION['mensaje_exito'] = 'Categoría creada correctamente.';
                } elseif ($accion === 'crear_etiqueta' && $nombre !== '') {
                    $this->articuloModel->crearEtiqueta($nombre);
                    $_SESSION['mensaje_exito'] = 'Etiqueta creada correctamente.';
                } elseif ($accion === 'crear_editorial' && $nombre !== '') {
                    $this->articuloModel->crearEditorial($nombre);
                    $_SESSION['mensaje_exito'] = 'Editorial/Repositorio creado correctamente.';
                } elseif ($accion === 'eliminar_categoria') {
                    $this->articuloModel->eliminarCategoria((int)($_POST['id'] ?? 0));
                    $_SESSION['mensaje_exito'] = 'Categoría eliminada.';
                } elseif ($accion === 'eliminar_etiqueta') {
                    $this->articuloModel->eliminarEtiqueta((int)($_POST['id'] ?? 0));
                    $_SESSION['mensaje_exito'] = 'Etiqueta eliminada.';
                } elseif ($accion === 'eliminar_editorial') {
                    $this->articuloModel->eliminarEditorial((int)($_POST['id'] ?? 0));
                    $_SESSION['mensaje_exito'] = 'Editorial/Repositorio eliminado.';
                    
                // --- NUEVAS ACCIONES DE EDICIÓN ---
                } elseif ($accion === 'actualizar_categoria' && $nombre !== '') {
                    $this->articuloModel->actualizarCategoria((int)($_POST['id'] ?? 0), $nombre);
                    $_SESSION['mensaje_exito'] = 'Categoría actualizada correctamente.';
                } elseif ($accion === 'actualizar_etiqueta' && $nombre !== '') {
                    $this->articuloModel->actualizarEtiqueta((int)($_POST['id'] ?? 0), $nombre);
                    $_SESSION['mensaje_exito'] = 'Etiqueta actualizada correctamente.';
                } elseif ($accion === 'actualizar_editorial' && $nombre !== '') {
                    $this->articuloModel->actualizarEditorial((int)($_POST['id'] ?? 0), $nombre);
                    $_SESSION['mensaje_exito'] = 'Editorial/Repositorio actualizado.';
                } elseif ($accion === 'actualizar_autor') {
                    $nombre_autor = trim($_POST['nombre_completo'] ?? '');
                    $cedula_autor = trim($_POST['cedula'] ?? '');
                    if ($nombre_autor !== '') {
                        $this->articuloModel->actualizarAutor((int)($_POST['id'] ?? 0), $nombre_autor, $cedula_autor);
                        $_SESSION['mensaje_exito'] = 'Datos del autor actualizados correctamente.';
                    }
                }
            } catch (Exception $e) {
                $_SESSION['mensaje_error'] = 'Error: ' . $e->getMessage();
            }

            header('Location: gestor-catalogos');
            exit;
        }

        $q_cat = trim($_GET['q_cat'] ?? '');
            $p_cat = max(1, (int)($_GET['p_cat'] ?? 1));

            $q_tag = trim($_GET['q_tag'] ?? '');
            $p_tag = max(1, (int)($_GET['p_tag'] ?? 1));

            $q_edit = trim($_GET['q_edit'] ?? '');
            $p_edit = max(1, (int)($_GET['p_edit'] ?? 1));

            $q_aut = trim($_GET['q_aut'] ?? ''); // Búsqueda de autores

            // 2. Ejecutamos las consultas paginadas
            $categorias = $this->articuloModel->obtenerCatalogoPaginado('categorias', $q_cat, $p_cat, 5);
            $etiquetas = $this->articuloModel->obtenerCatalogoPaginado('etiquetas', $q_tag, $p_tag, 5);
            $editoriales = $this->articuloModel->obtenerCatalogoPaginado('editoriales', $q_edit, $p_edit, 5);
            $autores = $this->articuloModel->buscarAutoresGestor($q_aut);

            return [
                'categorias' => $categorias,
                'etiquetas' => $etiquetas,
                'editoriales' => $editoriales,
                'autores' => $autores,
                'busquedas' => [
                    'q_cat' => $q_cat, 'q_tag' => $q_tag, 'q_edit' => $q_edit, 'q_aut' => $q_aut
                ]
            ];
        }
    public function apiCatalogos() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        header('Content-Type: application/json; charset=utf-8');
        
        $qb = new QueryBuilder();
        echo json_encode([
            'categorias' => $qb->tabla('categorias')->orderBy('nombre', 'ASC')->get(),
            'etiquetas' => $qb->tabla('etiquetas')->orderBy('nombre', 'ASC')->get()
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    public function toggleEstado() {
        Auth::requierePrivilegioMinimo(2);
        $id = (int)($_GET['id'] ?? 0);
        try {
            $this->articuloModel->cambiarEstado($id);
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_exito'] = "El estado de visibilidad del artículo ha sido actualizado.";
        } catch (Exception $e) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_error'] = "No se pudo cambiar el estado: " . $e->getMessage();
        }
        header('Location: gestor-articulos');
        exit;
    }
}
