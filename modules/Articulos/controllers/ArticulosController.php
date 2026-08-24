<?php
require_once CORE_PATH . 'Security/Auth.php';
require_once __DIR__ . '/../models/ArticuloModel.php';

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
            'etiquetas' => array_filter(array_map('intval', $_GET['etiqueta'] ?? []))
        ];

        $pagina = max(1, (int) ($_GET['page'] ?? 1));
        $porPagina = 16;

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

    return [
        'articulo' => $this->articuloModel->obtenerArticuloPorId($id)
    ];
}
    public function gestor() {
        // Candado: Solo Bibliotecario (2) o SuperAdmin (3) pueden entrar aquí
        Auth::requierePrivilegioMinimo(2);

        // Atrapamos la búsqueda y la página actual
        $filtros = [
            'q' => trim($_GET['q'] ?? '')
        ];
        $pagina = max(1, (int) ($_GET['page'] ?? 1));
        $porPagina = 15; // Mostramos 15 filas en la tabla para aprovechar el espacio

        // Reutilizamos el modelo avanzado de búsqueda y paginación
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

        $qb = new QueryBuilder();
        
        // Obtenemos los catálogos para llenar los <select> del formulario
        $categorias = $qb->tabla('categorias')->orderBy('nombre', 'ASC')->get();
        $editoriales = $qb->tabla('editoriales')->orderBy('nombre', 'ASC')->get();
        $etiquetas = $qb->tabla('etiquetas')->orderBy('nombre', 'ASC')->get();
        $autores = $qb->tabla('autores')->orderBy('nombre_completo', 'ASC')->get();

        return [
            'categorias' => $categorias,
            'editoriales' => $editoriales,
            'etiquetas' => $etiquetas,
            'autores' => $autores
        ];
    }
    public function procesar() {
        Auth::requierePrivilegioMinimo(2);

        // Bloqueo de seguridad si intentan acceder por URL directa
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: gestor-articulos');
            exit;
        }

        // 1. Recibir datos básicos
        $titulo = trim($_POST['titulo'] ?? '');
        $resumen = trim($_POST['resumen'] ?? '');
        $categorias = array_values(array_filter(array_map('intval', $_POST['categorias'] ?? [])));
        $id_editorial = !empty($_POST['id_editorial']) ? (int)$_POST['id_editorial'] : null;
        $archivo_pdf = trim($_POST['archivo_pdf'] ?? '');
        $anio_publicacion = !empty($_POST['anio_publicacion']) ? (int)$_POST['anio_publicacion'] : (int)date('Y');
        $volumen = trim($_POST['volumen'] ?? '');
        $numero = trim($_POST['numero'] ?? '');
        $issn = trim($_POST['issn'] ?? '');
        
        $autores = $_POST['autores'] ?? [];
        $autores_nuevos = $_POST['autores_nuevos'] ?? [];
        $etiquetas = $_POST['etiquetas'] ?? [];

        // 2. Manejar la imagen de portada
        $nombreImagen = 'default_article.jpg';
        
        // A. Si escribieron una URL externa, la tomamos primero
        $url_imagen = trim($_POST['url_imagen'] ?? '');
        if (!empty($url_imagen)) {
            $nombreImagen = $url_imagen;
        }

        // B. Si subieron un archivo físico, tiene prioridad
        if (isset($_FILES['imagen_portada']) && $_FILES['imagen_portada']['error'] === UPLOAD_ERR_OK) {
            
            $ext = strtolower(pathinfo($_FILES['imagen_portada']['name'], PATHINFO_EXTENSION));
            $extensiones_validas = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (in_array($ext, $extensiones_validas)) {
                $nombreImagen = 'art_' . time() . '_' . uniqid() . '.' . $ext;
                
                $destino = __DIR__ . '/../../../public/uploads/articulos/';
                if (!is_dir($destino)) { 
                    mkdir($destino, 0777, true); 
                }
                
                move_uploaded_file($_FILES['imagen_portada']['tmp_name'], $destino . $nombreImagen);
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

    $id = (int)($_GET['id'] ?? 0);

    $articulo = $this->articuloModel->obtenerArticuloPorId($id);
    if (!$articulo) {
        header('Location: gestor-articulos');
        exit;
    }

    $qb = new QueryBuilder();
    $categorias = $qb->tabla('categorias')->orderBy('nombre', 'ASC')->get();
    $editoriales = $qb->tabla('editoriales')->orderBy('nombre', 'ASC')->get();
    $etiquetas = $qb->tabla('etiquetas')->orderBy('nombre', 'ASC')->get();
    $autores = $qb->tabla('autores')->orderBy('nombre_completo', 'ASC')->get();

    return [
        'articulo' => $articulo,
        'categorias' => $categorias,
        'editoriales' => $editoriales,
        'etiquetas' => $etiquetas,
        'autores' => $autores,
        'autoresSeleccionados' => $this->articuloModel->obtenerAutoresDelArticulo($id),
        'etiquetasSeleccionadas' => $this->articuloModel->obtenerEtiquetasDelArticulo($id),
        'categoriasSeleccionadas' => $this->articuloModel->obtenerCategoriasDelArticulo($id)
    ];
}

public function actualizar() {
    Auth::requierePrivilegioMinimo(2);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: gestor-articulos');
        exit;
    }

    $id = (int)($_POST['id_articulo'] ?? 0);
    $titulo = trim($_POST['titulo'] ?? '');
    $resumen = trim($_POST['resumen'] ?? '');
    $categorias = array_values(array_filter(array_map('intval', $_POST['categorias'] ?? [])));
    $id_editorial = !empty($_POST['id_editorial']) ? (int)$_POST['id_editorial'] : null;
    $archivo_pdf = trim($_POST['archivo_pdf'] ?? '');
    $anio_publicacion = !empty($_POST['anio_publicacion']) ? (int)$_POST['anio_publicacion'] : (int)date('Y');
    $volumen = trim($_POST['volumen'] ?? '');
    $numero = trim($_POST['numero'] ?? '');
    $issn = trim($_POST['issn'] ?? '');
    $autores = $_POST['autores'] ?? [];
    $autores_nuevos = $_POST['autores_nuevos'] ?? [];
    $etiquetas = $_POST['etiquetas'] ?? [];

    $nombreImagen = trim($_POST['imagen_actual'] ?? 'default_article.jpg');
    $url_imagen = trim($_POST['url_imagen'] ?? '');

    if (!empty($url_imagen)) {
        $nombreImagen = $url_imagen;
    }
    if (isset($_FILES['imagen_portada']) && $_FILES['imagen_portada']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['imagen_portada']['name'], PATHINFO_EXTENSION));
        $extensiones_validas = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ext, $extensiones_validas)) {
            $nombreImagen = 'art_' . time() . '_' . uniqid() . '.' . $ext;
            $destino = __DIR__ . '/../../../public/uploads/articulos/';
            if (!is_dir($destino)) mkdir($destino, 0777, true);
            move_uploaded_file($_FILES['imagen_portada']['tmp_name'], $destino . $nombreImagen);
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
}
