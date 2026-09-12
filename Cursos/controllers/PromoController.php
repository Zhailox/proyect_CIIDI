<?php
// modules/Cursos/controllers/PromoController.php
require_once __DIR__ . '/../models/CursoModel.php';
require_once __DIR__ . '/../services/CsrfService.php';
require_once __DIR__ . '/../services/ImagenService.php';
require_once CORE_PATH . 'Security/Auth.php';

class PromoController {

    private CursoModel $model;
    private array $cfg;

    public function __construct() {
        $this->model = new CursoModel();
        $this->cfg   = $this->model->cargarConfig();
    }

    // ─────────────────────────────────────────────────────────
    //  HELPERS PRIVADOS
    // ─────────────────────────────────────────────────────────

    /** Purifica un campo de texto: strip_tags + htmlspecialchars + filter_var */
    private function limpiarTexto(string $valor, int $maxLen = 500): string {
        $valor = strip_tags($valor);
        $valor = filter_var($valor, FILTER_SANITIZE_SPECIAL_CHARS);
        $valor = htmlspecialchars_decode($valor, ENT_QUOTES); // volver a texto plano limpio
        return mb_substr(trim($valor), 0, $maxLen);
    }

    /** Valida y limpia una URL. Devuelve URL segura o cadena vacía. */
    private function limpiarUrl(string $valor): string {
        $valor = trim($valor);
        if (empty($valor)) return '';
        $url = filter_var($valor, FILTER_VALIDATE_URL);
        return $url !== false ? $url : '';
    }

    // ─────────────────────────────────────────────────────────
    //  CATÁLOGO — Vista principal pública
    // ─────────────────────────────────────────────────────────

    public function mostrarCatalogo(): array {
        $usuario_actual  = Auth::usuario();

        $filtros  = [];
        $pagina   = max(1, (int)($_GET['pagina'] ?? 1));
        $porPagina = (int)($_GET['por_pagina'] ?? $this->cfg['paginacion']['limite_catalogo']);

        // Sanitizar porPagina para que sea uno de las opciones válidas
        $opciones  = $this->cfg['paginacion']['opciones_selector'];
        if (!in_array($porPagina, $opciones)) {
            $porPagina = $this->cfg['paginacion']['limite_catalogo'];
        }

        // Catálogo público: SIEMPRE solo los publicados
        $filtros['estado'] = 'publicado';

        // Búsqueda de texto purificada
        if (!empty($_GET['busqueda'])) {
            $filtros['busqueda'] = $this->limpiarTexto($_GET['busqueda'], 200);
        }

        $resultado    = $this->model->listarCursos($filtros, $pagina, $porPagina);
        $cursos       = $resultado['cursos'];
        $total        = $resultado['total'];
        $estadisticas = $this->model->obtenerEstadisticas();

        // Calcular paginación
        $total_paginas = (int)ceil($total / $porPagina);
        $paginacion    = [
            'pagina_actual' => $pagina,
            'total_paginas' => $total_paginas,
            'total'         => $total,
            'por_pagina'    => $porPagina,
            'opciones'      => $opciones,
        ];

        $mensaje_exito = $_SESSION['cur_exito'] ?? null;
        $mensaje_error = $_SESSION['cur_error'] ?? null;
        unset($_SESSION['cur_exito'], $_SESSION['cur_error']);

        $config_vista = $this->cfg;

        return compact(
            'cursos', 'estadisticas', 'filtros', 'paginacion',
            'mensaje_exito', 'mensaje_error', 'usuario_actual', 'config_vista'
        );
    }

    // ─────────────────────────────────────────────────────────
    //  GESTIÓN DE CURSOS (Mis Cursos / Admin)
    // ─────────────────────────────────────────────────────────

    public function mostrarGestion(): array {
        $nivel_requerido = $this->cfg['roles']['nivel_crear_curso'] ?? 1;
        Auth::requierePrivilegioMinimo($nivel_requerido);
        
        $usuario_actual = Auth::usuario();
        $nivel = (int)($usuario_actual['nivel'] ?? -1);
        $id_usuario = (int)($usuario_actual['id'] ?? 0);
        
        $filtros  = [];
        $pagina   = max(1, (int)($_GET['pagina'] ?? 1));
        // En la gestión podemos mostrar un poco más de elementos por página, ej 12 fijos
        $porPagina = 12;

        if (!empty($_GET['estado'])) {
            $estados_validos = ['publicado', 'borrador', 'archivado'];
            if (in_array($_GET['estado'], $estados_validos)) {
                $filtros['estado'] = $_GET['estado'];
            }
        }

        if (!empty($_GET['busqueda'])) {
            $filtros['busqueda'] = $this->limpiarTexto($_GET['busqueda'], 200);
        }

        // Si es profesor (nivel 1), limitar a "Mis Cursos"
        $nivel_admin = $this->cfg['roles']['nivel_eliminar_curso'] ?? 2;
        if ($nivel < $nivel_admin) {
            $filtros['id_docente'] = $id_usuario;
        }

        $resultado = $this->model->listarCursos($filtros, $pagina, $porPagina);
        $cursos    = $resultado['cursos'];
        $total     = $resultado['total'];

        $total_paginas = (int)ceil($total / $porPagina);
        $paginacion    = [
            'pagina_actual' => $pagina,
            'total_paginas' => $total_paginas,
            'total'         => $total,
            'por_pagina'    => $porPagina,
        ];

        $mensaje_exito = $_SESSION['cur_exito'] ?? null;
        $mensaje_error = $_SESSION['cur_error'] ?? null;
        unset($_SESSION['cur_exito'], $_SESSION['cur_error']);
        
        $config_vista = $this->cfg;

        return compact(
            'cursos', 'filtros', 'paginacion', 'mensaje_exito', 'mensaje_error', 
            'usuario_actual', 'config_vista'
        );
    }

    // ─────────────────────────────────────────────────────────
    //  CREAR CURSO
    // ─────────────────────────────────────────────────────────

    public function mostrarFormularioCrear(): array {
        Auth::requierePrivilegioMinimo($this->cfg['roles']['nivel_crear_curso']);

        $docentes    = $this->model->listarDocentes();
        $curso       = null;
        $meta        = [];
        $modo        = 'crear';
        $titulo_form = 'Registrar Nuevo Curso';
        $error       = $_SESSION['cur_form_error'] ?? null;
        $csrf_token  = CursosCsrfService::campoHidden();
        $config_vista = $this->cfg;
        unset($_SESSION['cur_form_error']);

        return compact('docentes', 'curso', 'meta', 'modo', 'titulo_form', 'error', 'csrf_token', 'config_vista');
    }

    public function procesarCrear(): void {
        Auth::requierePrivilegioMinimo($this->cfg['roles']['nivel_crear_curso']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?ruta=cursos'); exit;
        }

        // Verificar CSRF si está activo
        if ($this->cfg['seguridad']['csrf_activo']) {
            CursosCsrfService::verificarOFallar('cursos-crear');
        }

        // Purificar inputs de texto
        $titulo     = $this->limpiarTexto($_POST['titulo']      ?? '', 255);
        $descripcion= $this->limpiarTexto($_POST['descripcion'] ?? '', 5000);
        $duracion   = $this->limpiarTexto($_POST['duracion']    ?? '', 80);

        if (empty($titulo)) {
            $_SESSION['cur_form_error'] = 'El título del curso es obligatorio.';
            header('Location: ?ruta=cursos-crear'); exit;
        }

        $id_docente = (int)($_POST['id_docente'] ?? 0);
        if ($id_docente <= 0) {
            $_SESSION['cur_form_error'] = 'Debe seleccionar un docente responsable.';
            header('Location: ?ruta=cursos-crear'); exit;
        }

        // Estado validado contra enum permitido
        $estados_validos = ['borrador', 'publicado', 'archivado'];
        $estado = in_array($_POST['estado'] ?? '', $estados_validos) ? $_POST['estado'] : 'borrador';

        // Nota mínima acotada 0-100
        $nota = max(0, min(100, (float)($_POST['nota_minima_aprobacion'] ?? 70.00)));

        // ── Imagen de portada ────────────────────────────────
        $imagen_portada = '';

        // Opción A: URL externa (purificada)
        $url_imagen = $this->limpiarUrl($_POST['imagen_portada'] ?? '');
        if (!empty($url_imagen)) {
            $imagen_portada = $url_imagen;
        }

        // Opción B: Archivo subido (tiene prioridad sobre URL)
        if (isset($_FILES['imagen_portada_file']) && $_FILES['imagen_portada_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['imagen_portada_file']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['cur_form_error'] = 'Error al subir la imagen. Es posible que el archivo sea demasiado grande (límite del servidor).';
                header('Location: ?ruta=cursos-crear'); exit;
            }
            
            $carpeta = dirname(__DIR__, 3) . '/' . $this->cfg['imagenes']['carpeta_uploads'];
            $nombre  = CursosImagenService::procesarImagen(
                $_FILES['imagen_portada_file'],
                $carpeta,
                $this->cfg['imagenes']
            );
            if ($nombre) {
                $imagen_portada = $this->cfg['imagenes']['carpeta_uploads'] . $nombre;
            } else {
                $_SESSION['cur_form_error'] = 'La imagen no pudo procesarse. Verifica el formato y tamaño (máx. ' . $this->cfg['imagenes']['max_size_mb'] . ' MB).';
                header('Location: ?ruta=cursos-crear'); exit;
            }
        }

        // ── URLs de Moodle / Video ───────────────────────────
        $url_moodle   = $this->limpiarUrl($_POST['url_moodle']        ?? '');
        $url_video    = $this->limpiarUrl($_POST['url_video_preview']  ?? '');

        // Si url_moodle está vacía, usar fallback de config
        if (empty($url_moodle)) {
            $url_moodle = $this->cfg['moodle']['url_fallback'];
        }

        // Modalidad y nivel validados contra lista blanca
        $modalidades_validas = ['Virtual', 'Presencial', 'Híbrido'];
        $niveles_validos     = ['Básico', 'Intermedio', 'Avanzado', 'Todos los niveles'];
        $modalidad = in_array($_POST['modalidad'] ?? '', $modalidades_validas) ? $_POST['modalidad'] : 'Virtual';
        $nivel_c   = in_array($_POST['nivel']     ?? '', $niveles_validos)     ? $_POST['nivel']     : 'Básico';
        $cupo      = max(0, (int)($_POST['cupo_maximo'] ?? 0));

        $datos = [
            'id_docente'             => $id_docente,
            'titulo'                 => $titulo,
            'descripcion'            => $descripcion,
            'imagen_portada'         => $imagen_portada,
            'estado'                 => $estado,
            'nota_minima_aprobacion' => $nota,
        ];

        try {
            $nuevo_id = $this->model->crearCurso($datos);
            if ($nuevo_id) {
                $this->model->guardarMetaCurso($nuevo_id, [
                    'url_moodle'        => $url_moodle,
                    'url_video_preview' => $url_video,
                    'modalidad'         => $modalidad,
                    'nivel'             => $nivel_c,
                    'duracion'          => $duracion,
                    'cupo_maximo'       => $cupo,
                ]);
                $_SESSION['cur_exito'] = 'Curso «' . htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') . '» creado exitosamente.';
            } else {
                $_SESSION['cur_error'] = 'No se pudo crear el curso. Intente nuevamente.';
            }
        } catch (Exception $e) {
            $_SESSION['cur_error'] = 'Error en la base de datos: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }

        header('Location: ?ruta=cursos'); exit;
    }

    // ─────────────────────────────────────────────────────────
    //  EDITAR CURSO
    // ─────────────────────────────────────────────────────────

    public function mostrarFormularioEditar(): array {
        Auth::requierePrivilegioMinimo($this->cfg['roles']['nivel_crear_curso']);

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) { header('Location: ?ruta=cursos'); exit; }

        $curso = $this->model->obtenerPorId($id);
        if (!$curso) {
            $_SESSION['cur_error'] = 'El curso solicitado no fue encontrado.';
            header('Location: ?ruta=cursos'); exit;
        }

        $docentes     = $this->model->listarDocentes();
        $meta         = $this->model->obtenerMetaCurso($id);
        $modo         = 'editar';
        $titulo_form  = 'Editar Curso';
        $error        = $_SESSION['cur_form_error'] ?? null;
        $csrf_token   = CursosCsrfService::campoHidden();
        $config_vista = $this->cfg;
        unset($_SESSION['cur_form_error']);

        return compact('docentes', 'curso', 'meta', 'modo', 'titulo_form', 'error', 'csrf_token', 'config_vista');
    }

    public function procesarEditar(): void {
        Auth::requierePrivilegioMinimo($this->cfg['roles']['nivel_crear_curso']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?ruta=cursos'); exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) { header('Location: ?ruta=cursos'); exit; }

        if ($this->cfg['seguridad']['csrf_activo']) {
            CursosCsrfService::verificarOFallar("cursos-editar?id={$id}");
        }

        // Purificar inputs
        $titulo      = $this->limpiarTexto($_POST['titulo']      ?? '', 255);
        $descripcion = $this->limpiarTexto($_POST['descripcion'] ?? '', 5000);
        $duracion    = $this->limpiarTexto($_POST['duracion']    ?? '', 80);

        if (empty($titulo)) {
            $_SESSION['cur_form_error'] = 'El título del curso es obligatorio.';
            header("Location: ?ruta=cursos-editar&id={$id}"); exit;
        }

        $id_docente = (int)($_POST['id_docente'] ?? 0);
        if ($id_docente <= 0) {
            $_SESSION['cur_form_error'] = 'Debe seleccionar un docente responsable.';
            header("Location: ?ruta=cursos-editar&id={$id}"); exit;
        }

        $estados_validos = ['borrador', 'publicado', 'archivado'];
        $estado = in_array($_POST['estado'] ?? '', $estados_validos) ? $_POST['estado'] : 'borrador';
        $nota   = max(0, min(100, (float)($_POST['nota_minima_aprobacion'] ?? 70.00)));

        // ── Imagen ──────────────────────────────────────────
        // Obtener imagen existente como fallback
        $curso_actual   = $this->model->obtenerPorId($id);
        $imagen_portada = $curso_actual['imagen_portada'] ?? '';

        $url_imagen = $this->limpiarUrl($_POST['imagen_portada'] ?? '');
        if (!empty($url_imagen)) {
            $imagen_portada = $url_imagen;
        }

        if (isset($_FILES['imagen_portada_file']) && $_FILES['imagen_portada_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['imagen_portada_file']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['cur_form_error'] = 'Error al subir la imagen. Es posible que el archivo sea demasiado grande (límite del servidor).';
                header("Location: ?ruta=cursos-editar&id={$id}"); exit;
            }
            
            $carpeta = dirname(__DIR__, 3) . '/' . $this->cfg['imagenes']['carpeta_uploads'];
            $nombre  = CursosImagenService::procesarImagen(
                $_FILES['imagen_portada_file'],
                $carpeta,
                $this->cfg['imagenes']
            );
            if ($nombre) {
                $imagen_portada = $this->cfg['imagenes']['carpeta_uploads'] . $nombre;
            } else {
                $_SESSION['cur_form_error'] = 'La imagen no pudo procesarse. Verifica el formato y tamaño.';
                header("Location: ?ruta=cursos-editar&id={$id}"); exit;
            }
        }

        $url_moodle  = $this->limpiarUrl($_POST['url_moodle']       ?? '');
        $url_video   = $this->limpiarUrl($_POST['url_video_preview'] ?? '');
        if (empty($url_moodle)) {
            $url_moodle = $this->cfg['moodle']['url_fallback'];
        }

        $modalidades_validas = ['Virtual', 'Presencial', 'Híbrido'];
        $niveles_validos     = ['Básico', 'Intermedio', 'Avanzado', 'Todos los niveles'];
        $modalidad = in_array($_POST['modalidad'] ?? '', $modalidades_validas) ? $_POST['modalidad'] : 'Virtual';
        $nivel_c   = in_array($_POST['nivel']     ?? '', $niveles_validos)     ? $_POST['nivel']     : 'Básico';
        $cupo      = max(0, (int)($_POST['cupo_maximo'] ?? 0));

        $datos = [
            'id_docente'             => $id_docente,
            'titulo'                 => $titulo,
            'descripcion'            => $descripcion,
            'imagen_portada'         => $imagen_portada,
            'estado'                 => $estado,
            'nota_minima_aprobacion' => $nota,
        ];

        try {
            $ok = $this->model->editarCurso($id, $datos);
            $this->model->guardarMetaCurso($id, [
                'url_moodle'        => $url_moodle,
                'url_video_preview' => $url_video,
                'modalidad'         => $modalidad,
                'nivel'             => $nivel_c,
                'duracion'          => $duracion,
                'cupo_maximo'       => $cupo,
            ]);
            if ($ok !== false) {
                $_SESSION['cur_exito'] = 'Curso «' . htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') . '» actualizado correctamente.';
            } else {
                $_SESSION['cur_error'] = 'No se encontraron cambios o el curso no existe.';
            }
        } catch (Exception $e) {
            $_SESSION['cur_error'] = 'Error en la base de datos: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }

        header('Location: ?ruta=cursos'); exit;
    }

    // ─────────────────────────────────────────────────────────
    //  ELIMINAR CURSO
    // ─────────────────────────────────────────────────────────

    public function procesarEliminar(): void {
        Auth::requierePrivilegioMinimo($this->cfg['roles']['nivel_eliminar_curso']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?ruta=cursos'); exit;
        }

        if ($this->cfg['seguridad']['csrf_activo']) {
            CursosCsrfService::verificarOFallar('cursos');
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['cur_error'] = 'Solicitud de eliminación inválida.';
            header('Location: ?ruta=cursos'); exit;
        }

        $curso          = $this->model->obtenerPorId($id);
        $titulo_borrado = $curso ? htmlspecialchars($curso['titulo'], ENT_QUOTES, 'UTF-8') : "ID #{$id}";

        try {
            $ok = $this->model->eliminarCurso($id);
            if ($ok) {
                $_SESSION['cur_exito'] = "Curso «{$titulo_borrado}» eliminado del sistema.";
            } else {
                $_SESSION['cur_error'] = 'No se pudo eliminar el curso. Puede que ya no exista.';
            }
        } catch (Exception $e) {
            $_SESSION['cur_error'] = 'Error al eliminar: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }

        header('Location: ?ruta=cursos'); exit;
    }

    // ─────────────────────────────────────────────────────────
    //  DETALLE DEL CURSO
    // ─────────────────────────────────────────────────────────

    public function verDetalle(): array {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) { header('Location: ?ruta=cursos'); exit; }

        $usuario_actual = Auth::usuario();
        $nivel          = $usuario_actual['nivel'] ?? -1;

        $curso = $this->model->obtenerPorId($id);
        if (!$curso) {
            $_SESSION['cur_error'] = 'El curso solicitado no fue encontrado.';
            header('Location: ?ruta=cursos'); exit;
        }

        if ($curso['estado'] !== 'publicado' && $nivel < $this->cfg['roles']['nivel_crear_curso']) {
            $_SESSION['cur_error'] = 'El curso solicitado no está disponible.';
            header('Location: ?ruta=cursos'); exit;
        }

        // Si url_moodle vacía, usar fallback
        if (empty($curso['url_moodle'])) {
            $curso['url_moodle'] = $this->cfg['moodle']['url_fallback'];
        }

        $config_vista = $this->cfg;
        return compact('curso', 'usuario_actual', 'config_vista');
    }

    // ─────────────────────────────────────────────────────────
    //  CONFIGURACIÓN DEL MÓDULO (solo SuperAdmin nivel >= 3)
    // ─────────────────────────────────────────────────────────

    public function mostrarConfig(): array {
        Auth::requierePrivilegioMinimo($this->cfg['roles']['nivel_ver_config']);

        $config_actual = $this->model->cargarConfig();
        $csrf_token    = CursosCsrfService::campoHidden();
        $mensaje_exito = $_SESSION['cur_exito'] ?? null;
        $mensaje_error = $_SESSION['cur_error'] ?? null;
        unset($_SESSION['cur_exito'], $_SESSION['cur_error']);

        return compact('config_actual', 'csrf_token', 'mensaje_exito', 'mensaje_error');
    }

    public function guardarConfig(): void {
        Auth::requierePrivilegioMinimo($this->cfg['roles']['nivel_ver_config']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?ruta=cursos-config'); exit;
        }

        if ($this->cfg['seguridad']['csrf_activo']) {
            CursosCsrfService::verificarOFallar('cursos-config');
        }

        $cfg = $this->model->cargarConfig();

        // Actualizar paginación
        $limite = max(1, min(50, (int)($_POST['limite_catalogo'] ?? 9)));
        $cfg['paginacion']['limite_catalogo'] = $limite;

        // Actualizar límite de imagen
        $maxMb = max(1, min(20, (int)($_POST['max_size_mb'] ?? 5)));
        $cfg['imagenes']['max_size_mb'] = $maxMb;

        // Convertir a WebP
        $cfg['imagenes']['convertir_a_webp'] = isset($_POST['convertir_a_webp']);

        // Lazy load
        $cfg['imagenes']['lazy_load'] = isset($_POST['lazy_load']);

        // URL fallback de Moodle
        $fallback = $this->limpiarUrl($_POST['url_fallback'] ?? '');
        if (!empty($fallback)) {
            $cfg['moodle']['url_fallback'] = $fallback;
        }

        // CSRF activo
        $cfg['seguridad']['csrf_activo'] = isset($_POST['csrf_activo']);

        // Placeholder URL
        $placeholder = $this->limpiarUrl($_POST['placeholder_url'] ?? '');
        if (!empty($placeholder)) {
            $cfg['imagenes']['placeholder_url'] = $placeholder;
        }

        try {
            $this->model->guardarConfig($cfg);
            $_SESSION['cur_exito'] = 'Configuración del módulo guardada correctamente.';
        } catch (Exception $e) {
            $_SESSION['cur_error'] = 'Error al guardar la configuración.';
        }

        header('Location: ?ruta=cursos-config'); exit;
    }
}
