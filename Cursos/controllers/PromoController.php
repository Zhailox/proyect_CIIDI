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

    private function limpiarTexto(string $valor, int $maxLen = 5000): string {
        // Solo limpiamos espacios y truncamos. htmlspecialchars se aplica en la vista.
        $valor = trim($valor);
        return mb_substr($valor, 0, $maxLen);
    }

    private function limpiarUrl(string $valor): string {
        $valor = trim($valor);
        if (empty($valor)) return '';
        $url = filter_var($valor, FILTER_VALIDATE_URL);
        if ($url === false) return '';
        $scheme = parse_url($url, PHP_URL_SCHEME);
        if (!in_array(strtolower($scheme ?? ''), ['http', 'https'])) return '';
        return $url;
    }

    public function mostrarCatalogo(): array {
        $usuario_actual  = Auth::usuario();

        $filtros  = [];
        $pagina   = max(1, (int)($_GET['pagina'] ?? 1));
        $porPagina = (int)($_GET['por_pagina'] ?? $this->cfg['paginacion']['limite_catalogo']);

        $opciones  = $this->cfg['paginacion']['opciones_selector'];
        if (!in_array($porPagina, $opciones)) {
            $porPagina = $this->cfg['paginacion']['limite_catalogo'];
        }

        $filtros['estado'] = 'publicado';

        if (!empty($_GET['busqueda'])) {
            $filtros['busqueda'] = $this->limpiarTexto($_GET['busqueda'], 200);
        }
        if (!empty($_GET['modalidad'])) {
            $filtros['modalidad'] = $this->limpiarTexto($_GET['modalidad'], 50);
        }
        if (!empty($_GET['nivel'])) {
            $filtros['nivel'] = $this->limpiarTexto($_GET['nivel'], 50);
        }

        $resultado    = $this->model->listarCursos($filtros, $pagina, $porPagina);
        $cursos       = $resultado['cursos'];
        $total        = $resultado['total'];
        $estadisticas = $this->model->obtenerEstadisticas();

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

    public function mostrarGestion(): array {
        $nivel_requerido = $this->cfg['roles']['nivel_crear_curso'] ?? 1;
        Auth::requierePrivilegioMinimo($nivel_requerido);
        
        $usuario_actual = Auth::usuario();
        $nivel = (int)($usuario_actual['nivel'] ?? -1);
        $id_usuario = (int)($usuario_actual['id'] ?? 0);
        
        $filtros  = [];
        $pagina   = max(1, (int)($_GET['pagina'] ?? 1));
        $porPagina = 10;

        if (!empty($_GET['estado'])) {
            $estados_validos = ['publicado', 'borrador', 'archivado'];
            if (in_array($_GET['estado'], $estados_validos)) {
                $filtros['estado'] = $_GET['estado'];
            }
        }

        if (!empty($_GET['busqueda'])) {
            $filtros['busqueda'] = $this->limpiarTexto($_GET['busqueda'], 200);
        }

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
        $usuario_actual = Auth::usuario();
        unset($_SESSION['cur_form_error']);

        return compact('docentes', 'curso', 'meta', 'modo', 'titulo_form', 'error', 'csrf_token', 'config_vista', 'usuario_actual');
    }

    public function procesarCrear(): void {
        Auth::requierePrivilegioMinimo($this->cfg['roles']['nivel_crear_curso']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?ruta=cursos-gestion'); exit;
        }

        if ($this->cfg['seguridad']['csrf_activo']) {
            CursosCsrfService::verificarOFallar('cursos-crear');
        }

        $titulo      = $this->limpiarTexto($_POST['titulo']      ?? '', 255);
        $descripcion = $this->limpiarTexto($_POST['descripcion'] ?? '', 5000);
        $duracion    = $this->limpiarTexto($_POST['duracion']    ?? '', 80);

        if (empty($titulo)) {
            $_SESSION['cur_form_error'] = 'El título del curso es obligatorio.';
            header('Location: ?ruta=cursos-crear'); exit;
        }

        $usuario_actual = Auth::usuario();
        $nivel_usuario = (int)($usuario_actual['nivel'] ?? -1);

        $nivel_admin = $this->cfg['roles']['nivel_ver_config'] ?? 3; // admin configurado
        if ($nivel_usuario >= $nivel_admin) {
            $id_docente = (int)($_POST['id_docente'] ?? 0);
            if ($id_docente <= 0) {
                $_SESSION['cur_form_error'] = 'Debe seleccionar un docente responsable.';
                header('Location: ?ruta=cursos-crear'); exit;
            }
        } else {
            $id_docente = (int)($usuario_actual['id'] ?? 0);
        }

        $estados_validos = ['borrador', 'publicado', 'archivado'];
        $estado = in_array($_POST['estado'] ?? '', $estados_validos) ? $_POST['estado'] : 'borrador';

        $imagen_portada = '';
        if (isset($_FILES['imagen_portada_file']) && $_FILES['imagen_portada_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['imagen_portada_file']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['cur_form_error'] = 'Error al subir la imagen (Código PHP: ' . $_FILES['imagen_portada_file']['error'] . '). Verifica que no exceda el límite del servidor (upload_max_filesize).';
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
                $_SESSION['cur_form_error'] = 'La imagen no pudo procesarse. Formato no válido o tamaño excedido.';
                header('Location: ?ruta=cursos-crear'); exit;
            }
        } elseif (!empty($_POST['imagen_portada_url'])) {
            // Alternativa: URL externa de imagen
            $imagen_portada = $this->limpiarUrl($_POST['imagen_portada_url'] ?? '');
        }


        $url_moodle = $this->limpiarUrl($_POST['url_moodle'] ?? '');
        if (empty($url_moodle)) {
            $url_moodle = $this->cfg['moodle']['url_fallback'];
        }

        $modalidades_validas = ['Virtual', 'Presencial', 'Híbrido'];
        $niveles_validos     = ['Básico', 'Intermedio', 'Avanzado', 'Todos los niveles'];
        $modalidad = in_array($_POST['modalidad'] ?? '', $modalidades_validas) ? $_POST['modalidad'] : 'Virtual';
        $nivel_c   = in_array($_POST['nivel']     ?? '', $niveles_validos)     ? $_POST['nivel']     : 'Básico';
        $cupo      = max(0, (int)($_POST['cupo_maximo'] ?? 0));
        $nota_minima_aprobacion = (float)($_POST['nota_minima_aprobacion'] ?? 70.00);
        $fecha_inicio = empty($_POST['fecha_inicio']) ? null : $_POST['fecha_inicio'];
        $fecha_fin = empty($_POST['fecha_fin']) ? null : $_POST['fecha_fin'];
        $url_video_preview = $this->limpiarUrl($_POST['url_video_preview'] ?? '');
        $estado_inscripcion = $this->limpiarTexto($_POST['estado_inscripcion'] ?? 'Abierta', 50);

        $datos = [
            'id_docente'     => $id_docente,
            'titulo'         => $titulo,
            'descripcion'    => $descripcion,
            'imagen_portada' => $imagen_portada,
            'estado'         => $estado,
            'nota_minima_aprobacion' => $nota_minima_aprobacion,
            'url_moodle' => $url_moodle,
            'modalidad' => $modalidad,
            'nivel' => $nivel_c,
            'duracion' => $duracion,
            'cupo_maximo' => $cupo,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'url_video_preview' => $url_video_preview,
            'estado_inscripcion' => $estado_inscripcion,
        ];

        try {
            $nuevo_id = $this->model->crearCurso($datos);
            if ($nuevo_id) {
                $_SESSION['cur_exito'] = 'Curso creado exitosamente.';
            } else {
                $_SESSION['cur_error'] = 'No se pudo crear el curso. Intente nuevamente.';
            }
        } catch (Exception $e) {
            error_log('Error DB crear curso: ' . $e->getMessage());
            $_SESSION['cur_error'] = 'Ocurrió un error en la base de datos al procesar la solicitud.';
        }

        header('Location: ?ruta=cursos-gestion'); exit;
    }

    public function mostrarFormularioEditar(): array {
        Auth::requierePrivilegioMinimo($this->cfg['roles']['nivel_crear_curso']);

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) { header('Location: ?ruta=cursos-gestion'); exit; }

        $curso = $this->model->obtenerPorId($id);
        if (!$curso) {
            $_SESSION['cur_error'] = 'El curso solicitado no fue encontrado.';
            header('Location: ?ruta=cursos-gestion'); exit;
        }

        $usuario_actual = Auth::usuario();
        $nivel_usuario = (int)($usuario_actual['nivel'] ?? -1);
        $nivel_admin = $this->cfg['roles']['nivel_eliminar_curso'] ?? 2;
        if ($nivel_usuario < $nivel_admin && $curso['id_docente'] != $usuario_actual['id']) {
            $_SESSION['cur_error'] = 'No tienes permiso para editar este curso.';
            header('Location: ?ruta=cursos-gestion'); exit;
        }

        $docentes     = $this->model->listarDocentes();
        $meta         = $curso; // Ya vienen en el curso
        $modo         = 'editar';
        $titulo_form  = 'Editar Curso';
        $error        = $_SESSION['cur_form_error'] ?? null;
        $csrf_token   = CursosCsrfService::campoHidden();
        $config_vista = $this->cfg;
        unset($_SESSION['cur_form_error']);

        return compact('docentes', 'curso', 'meta', 'modo', 'titulo_form', 'error', 'csrf_token', 'config_vista', 'usuario_actual');
    }

    public function procesarEditar(): void {
        Auth::requierePrivilegioMinimo($this->cfg['roles']['nivel_crear_curso']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?ruta=cursos-gestion'); exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) { header('Location: ?ruta=cursos-gestion'); exit; }

        if ($this->cfg['seguridad']['csrf_activo']) {
            CursosCsrfService::verificarOFallar("cursos-editar?id={$id}");
        }

        $curso_actual = $this->model->obtenerPorId($id);
        if (!$curso_actual) {
            $_SESSION['cur_error'] = 'Curso no encontrado.';
            header('Location: ?ruta=cursos-gestion'); exit;
        }

        $usuario_actual = Auth::usuario();
        $nivel_usuario = (int)($usuario_actual['nivel'] ?? -1);
        $nivel_admin_edit = $this->cfg['roles']['nivel_eliminar_curso'] ?? 2;
        
        // AUDITORIA: PREVENCIÓN IDOR
        if ($nivel_usuario < $nivel_admin_edit && $curso_actual['id_docente'] != $usuario_actual['id']) {
            $_SESSION['cur_error'] = 'Acceso denegado: No puedes editar un curso que no te pertenece.';
            header('Location: ?ruta=cursos-gestion'); exit;
        }

        $titulo      = $this->limpiarTexto($_POST['titulo']      ?? '', 255);
        $descripcion = $this->limpiarTexto($_POST['descripcion'] ?? '', 5000);
        $duracion    = $this->limpiarTexto($_POST['duracion']    ?? '', 80);

        if (empty($titulo)) {
            $_SESSION['cur_form_error'] = 'El título del curso es obligatorio.';
            header("Location: ?ruta=cursos-editar&id={$id}"); exit;
        }

        $nivel_admin_docente = $this->cfg['roles']['nivel_ver_config'] ?? 3;
        if ($nivel_usuario >= $nivel_admin_docente) {
            $id_docente = (int)($_POST['id_docente'] ?? 0);
            if ($id_docente <= 0) {
                $_SESSION['cur_form_error'] = 'Debe seleccionar un docente responsable.';
                header("Location: ?ruta=cursos-editar&id={$id}"); exit;
            }
        } else {
            $id_docente = (int)($curso_actual['id_docente'] ?? $usuario_actual['id']);
        }

        $estados_validos = ['borrador', 'publicado', 'archivado'];
        $estado = in_array($_POST['estado'] ?? '', $estados_validos) ? $_POST['estado'] : 'borrador';

        $imagen_portada = '';
        if (isset($_FILES['imagen_portada_file']) && $_FILES['imagen_portada_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['imagen_portada_file']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['cur_form_error'] = 'Error al subir la nueva imagen (Código PHP: ' . $_FILES['imagen_portada_file']['error'] . '). Verifica que no exceda el límite del servidor.';
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
                // AUDITORIA: LIMPIAR ARCHIVO HUÉRFANO
                if (!empty($curso_actual['imagen_portada']) && strpos($curso_actual['imagen_portada'], 'http') === false) {
                    $old_path = dirname(__DIR__, 3) . '/' . $curso_actual['imagen_portada'];
                    if (file_exists($old_path) && is_file($old_path)) {
                        @unlink($old_path);
                    }
                }
            } else {
                $_SESSION['cur_form_error'] = 'La imagen no pudo procesarse. Verifica el formato y tamaño.';
                header("Location: ?ruta=cursos-editar&id={$id}"); exit;
            }
        } elseif (!empty($_POST['imagen_portada_url'])) {
            // Alternativa: URL externa de imagen
            $imagen_portada = $this->limpiarUrl($_POST['imagen_portada_url'] ?? '');
        }


        $url_moodle = $this->limpiarUrl($_POST['url_moodle'] ?? '');
        if (empty($url_moodle)) {
            $url_moodle = $this->cfg['moodle']['url_fallback'];
        }

        $modalidades_validas = ['Virtual', 'Presencial', 'Híbrido'];
        $niveles_validos     = ['Básico', 'Intermedio', 'Avanzado', 'Todos los niveles'];
        $modalidad = in_array($_POST['modalidad'] ?? '', $modalidades_validas) ? $_POST['modalidad'] : 'Virtual';
        $nivel_c   = in_array($_POST['nivel']     ?? '', $niveles_validos)     ? $_POST['nivel']     : 'Básico';
        $cupo      = max(0, (int)($_POST['cupo_maximo'] ?? 0));
        $nota_minima_aprobacion = (float)($_POST['nota_minima_aprobacion'] ?? 70.00);
        $fecha_inicio = empty($_POST['fecha_inicio']) ? null : $_POST['fecha_inicio'];
        $fecha_fin = empty($_POST['fecha_fin']) ? null : $_POST['fecha_fin'];
        $url_video_preview = $this->limpiarUrl($_POST['url_video_preview'] ?? '');
        $estado_inscripcion = $this->limpiarTexto($_POST['estado_inscripcion'] ?? 'Abierta', 50);

        $datos = [
            'id_docente'     => $id_docente,
            'titulo'         => $titulo,
            'descripcion'    => $descripcion,
            'imagen_portada' => $imagen_portada,
            'estado'         => $estado,
            'nota_minima_aprobacion' => $nota_minima_aprobacion,
            'url_moodle' => $url_moodle,
            'modalidad' => $modalidad,
            'nivel' => $nivel_c,
            'duracion' => $duracion,
            'cupo_maximo' => $cupo,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'url_video_preview' => $url_video_preview,
            'estado_inscripcion' => $estado_inscripcion,
        ];

        try {
            $ok = $this->model->editarCurso($id, $datos);
            if ($ok !== false) {
                $_SESSION['cur_exito'] = 'Curso actualizado correctamente.';
            } else {
                $_SESSION['cur_error'] = 'No se encontraron cambios o el curso no existe.';
            }
        } catch (Exception $e) {
            error_log('Error DB editar curso: ' . $e->getMessage());
            $_SESSION['cur_error'] = 'Ocurrió un error interno al guardar los cambios.';
        }

        header('Location: ?ruta=cursos-gestion'); exit;
    }

    public function procesarEliminar(): void {
        Auth::requierePrivilegioMinimo($this->cfg['roles']['nivel_crear_curso']); // Permitimos nivel 1, luego validamos propiedad

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?ruta=cursos-gestion'); exit;
        }

        if ($this->cfg['seguridad']['csrf_activo']) {
            CursosCsrfService::verificarOFallar('cursos');
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['cur_error'] = 'Solicitud de eliminación inválida.';
            header('Location: ?ruta=cursos-gestion'); exit;
        }

        $curso = $this->model->obtenerPorId($id);
        if (!$curso) {
            $_SESSION['cur_error'] = 'El curso no existe.';
            header('Location: ?ruta=cursos-gestion'); exit;
        }

        // AUDITORIA: PREVENCIÓN IDOR
        $usuario_actual = Auth::usuario();
        $nivel_usuario = (int)($usuario_actual['nivel'] ?? -1);
        $nivel_admin = $this->cfg['roles']['nivel_eliminar_curso'] ?? 2;
        if ($nivel_usuario < $nivel_admin && $curso['id_docente'] != $usuario_actual['id']) {
            $_SESSION['cur_error'] = 'Acceso denegado: No puedes eliminar este curso.';
            header('Location: ?ruta=cursos-gestion'); exit;
        }

        try {
            $ok = $this->model->eliminarCurso($id);
            if ($ok) {
                // Limpiar archivo huérfano
                if (!empty($curso['imagen_portada'])) {
                    $old_path = dirname(__DIR__, 3) . '/' . $curso['imagen_portada'];
                    if (file_exists($old_path) && is_file($old_path)) {
                        @unlink($old_path);
                    }
                }
                $_SESSION['cur_exito'] = "Curso eliminado del sistema.";
            } else {
                $_SESSION['cur_error'] = 'No se pudo eliminar el curso.';
            }
        } catch (Exception $e) {
            error_log('Error DB eliminar curso: ' . $e->getMessage());
            $_SESSION['cur_error'] = 'Error interno al intentar eliminar el curso.';
        }

        header('Location: ?ruta=cursos-gestion'); exit;
    }

    public function verDetalle(): array {
        $slug = $_GET['slug'] ?? '';
        if (empty($slug)) { header('Location: ?ruta=cursos'); exit; }

        $usuario_actual = Auth::usuario();
        $nivel          = $usuario_actual['nivel'] ?? -1;

        $curso = $this->model->obtenerPorSlug($slug);
        if (!$curso) {
            $_SESSION['cur_error'] = 'El curso solicitado no fue encontrado.';
            header('Location: ?ruta=cursos'); exit;
        }

        if ($curso['estado'] !== 'publicado' && $nivel < $this->cfg['roles']['nivel_crear_curso']) {
            $_SESSION['cur_error'] = 'El curso solicitado no está disponible.';
            header('Location: ?ruta=cursos'); exit;
        }

        if (empty($curso['url_moodle'])) {
            $curso['url_moodle'] = $this->cfg['moodle']['url_fallback'];
        }

        $config_vista = $this->cfg;
        return compact('curso', 'usuario_actual', 'config_vista');
    }

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
        $cfg['paginacion']['limite_catalogo'] = max(1, min(50, (int)($_POST['limite_catalogo'] ?? 9)));
        $cfg['imagenes']['max_size_mb'] = max(1, min(20, (int)($_POST['max_size_mb'] ?? 5)));
        $cfg['imagenes']['convertir_a_webp'] = isset($_POST['convertir_a_webp']);
        $cfg['imagenes']['lazy_load'] = isset($_POST['lazy_load']);
        $fallback = $this->limpiarUrl($_POST['url_fallback'] ?? '');
        if (!empty($fallback)) $cfg['moodle']['url_fallback'] = $fallback;
        $cfg['seguridad']['csrf_activo'] = isset($_POST['csrf_activo']);
        $placeholder = $this->limpiarUrl($_POST['placeholder_url'] ?? '');
        if (!empty($placeholder)) $cfg['imagenes']['placeholder_url'] = $placeholder;

        try {
            $this->model->guardarConfig($cfg);
            $_SESSION['cur_exito'] = 'Configuración del módulo guardada correctamente.';
        } catch (Exception $e) {
            error_log('Error guardar config: ' . $e->getMessage());
            $_SESSION['cur_error'] = 'Error interno al guardar la configuración.';
        }

        header('Location: ?ruta=cursos-config'); exit;
    }
}
