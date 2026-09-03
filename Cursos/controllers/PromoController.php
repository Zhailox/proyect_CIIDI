<?php
// modules/Cursos/controllers/PromoController.php
require_once __DIR__ . '/../models/CursoModel.php';
require_once CORE_PATH . 'Security/Auth.php';

class PromoController {

    private CursoModel $model;

    public function __construct() {
        $this->model = new CursoModel();
    }

    // =========================================================
    //  VISTA PRINCIPAL — Catálogo público
    // =========================================================

    /**
     * Carga el catálogo de cursos con filtros opcionales.
     */
    public function mostrarCatalogo(): array {
        // Filtros recibidos por GET
        $filtros = [];
        if (!empty($_GET['estado'])) {
            $estados_validos = ['publicado', 'borrador', 'archivado'];
            if (in_array($_GET['estado'], $estados_validos)) {
                $filtros['estado'] = $_GET['estado'];
            }
        }

        $cursos     = $this->model->listarCursos($filtros);
        $estadisticas = $this->model->obtenerEstadisticas();

        // Mensajes flash
        $mensaje_exito = $_SESSION['cur_exito'] ?? null;
        $mensaje_error = $_SESSION['cur_error'] ?? null;
        unset($_SESSION['cur_exito'], $_SESSION['cur_error']);

        $usuario_actual = Auth::usuario();

        return compact('cursos', 'estadisticas', 'filtros', 'mensaje_exito', 'mensaje_error', 'usuario_actual');
    }

    // =========================================================
    //  CREAR CURSO
    // =========================================================

    /**
     * Muestra el formulario vacío para crear un nuevo curso.
     * Protegido: nivel 1 (Profesor) o superior.
     */
    public function mostrarFormularioCrear(): array {
        Auth::requierePrivilegioMinimo(1);

        $docentes    = $this->model->listarDocentes();
        $curso       = null; // Sin datos previos
        $modo        = 'crear';
        $titulo_form = 'Registrar Nuevo Curso';

        $error = $_SESSION['cur_form_error'] ?? null;
        unset($_SESSION['cur_form_error']);

        return compact('docentes', 'curso', 'modo', 'titulo_form', 'error');
    }

    /**
     * Procesa el POST para crear un curso nuevo.
     */
    public function procesarCrear() {
        Auth::requierePrivilegioMinimo(1);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: cursos');
            exit;
        }

        // Validación básica
        $titulo = trim($_POST['titulo'] ?? '');
        if (empty($titulo)) {
            $_SESSION['cur_form_error'] = 'El título del curso es obligatorio.';
            header('Location: cursos-crear');
            exit;
        }

        $id_docente = (int) ($_POST['id_docente'] ?? 0);
        if ($id_docente <= 0) {
            $_SESSION['cur_form_error'] = 'Debe seleccionar un docente responsable.';
            header('Location: cursos-crear');
            exit;
        }

        $datos = [
            'id_docente'             => $id_docente,
            'titulo'                 => $titulo,
            'descripcion'            => trim($_POST['descripcion'] ?? ''),
            'imagen_portada'         => trim($_POST['imagen_portada'] ?? ''),
            'estado'                 => $_POST['estado'] ?? 'borrador',
            'nota_minima_aprobacion' => (float) ($_POST['nota_minima_aprobacion'] ?? 70.00),
        ];

        try {
            $nuevo_id = $this->model->crearCurso($datos);

            if ($nuevo_id) {
                $_SESSION['cur_exito'] = 'Curso «' . htmlspecialchars($titulo) . '» creado exitosamente.';
            } else {
                $_SESSION['cur_error'] = 'No se pudo crear el curso. Intente nuevamente.';
            }
        } catch (Exception $e) {
            $_SESSION['cur_error'] = 'Error en la base de datos: ' . $e->getMessage();
        }

        header('Location: cursos');
        exit;
    }

    // =========================================================
    //  EDITAR CURSO
    // =========================================================

    /**
     * Muestra el formulario pre-rellenado para editar un curso.
     */
    public function mostrarFormularioEditar(): array {
        Auth::requierePrivilegioMinimo(1);

        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: cursos');
            exit;
        }

        $curso = $this->model->obtenerPorId($id);
        if (!$curso) {
            $_SESSION['cur_error'] = 'El curso solicitado no fue encontrado.';
            header('Location: cursos');
            exit;
        }

        $docentes    = $this->model->listarDocentes();
        $modo        = 'editar';
        $titulo_form = 'Editar Curso';

        $error = $_SESSION['cur_form_error'] ?? null;
        unset($_SESSION['cur_form_error']);

        return compact('docentes', 'curso', 'modo', 'titulo_form', 'error');
    }

    /**
     * Procesa el POST para actualizar un curso existente.
     */
    public function procesarEditar() {
        Auth::requierePrivilegioMinimo(1);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: cursos');
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            header('Location: cursos');
            exit;
        }

        $titulo = trim($_POST['titulo'] ?? '');
        if (empty($titulo)) {
            $_SESSION['cur_form_error'] = 'El título del curso es obligatorio.';
            header("Location: cursos-editar?id={$id}");
            exit;
        }

        $id_docente = (int) ($_POST['id_docente'] ?? 0);
        if ($id_docente <= 0) {
            $_SESSION['cur_form_error'] = 'Debe seleccionar un docente responsable.';
            header("Location: cursos-editar?id={$id}");
            exit;
        }

        $datos = [
            'id_docente'             => $id_docente,
            'titulo'                 => $titulo,
            'descripcion'            => trim($_POST['descripcion'] ?? ''),
            'imagen_portada'         => trim($_POST['imagen_portada'] ?? ''),
            'estado'                 => $_POST['estado'] ?? 'borrador',
            'nota_minima_aprobacion' => (float) ($_POST['nota_minima_aprobacion'] ?? 70.00),
        ];

        try {
            $ok = $this->model->editarCurso($id, $datos);

            if ($ok) {
                $_SESSION['cur_exito'] = 'Curso «' . htmlspecialchars($titulo) . '» actualizado correctamente.';
            } else {
                $_SESSION['cur_error'] = 'No se encontraron cambios o el curso no existe.';
            }
        } catch (Exception $e) {
            $_SESSION['cur_error'] = 'Error en la base de datos: ' . $e->getMessage();
        }

        header('Location: cursos');
        exit;
    }

    // =========================================================
    //  ELIMINAR CURSO
    // =========================================================

    /**
     * Procesa el POST para eliminar un curso.
     * Protegido: nivel 2 (Bibliotecario) o superior.
     */
    public function procesarEliminar() {
        Auth::requierePrivilegioMinimo(2);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: cursos');
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['cur_error'] = 'Solicitud de eliminación inválida.';
            header('Location: cursos');
            exit;
        }

        // Recuperar título antes de borrar para el mensaje
        $curso = $this->model->obtenerPorId($id);
        $titulo_borrado = $curso ? $curso['titulo'] : "ID #{$id}";

        try {
            $ok = $this->model->eliminarCurso($id);

            if ($ok) {
                $_SESSION['cur_exito'] = 'Curso «' . htmlspecialchars($titulo_borrado) . '» eliminado del sistema.';
            } else {
                $_SESSION['cur_error'] = 'No se pudo eliminar el curso. Puede que ya no exista.';
            }
        } catch (Exception $e) {
            $_SESSION['cur_error'] = 'Error al eliminar: ' . $e->getMessage();
        }

        header('Location: cursos');
        exit;
    }
    /**
     * Muestra la vista de detalle de un curso específico.
     */
    public function verDetalle(): array {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: cursos');
            exit;
        }

        $curso = $this->model->obtenerPorId($id);
        if (!$curso || $curso['estado'] !== 'publicado') {
            // Permitir a usuarios con privilegios ver cursos no publicados (opcional, pero buena práctica)
            $usuario = Auth::usuario();
            if (!$curso || ($curso['estado'] !== 'publicado' && ($usuario['nivel'] ?? -1) < 1)) {
                $_SESSION['cur_error'] = 'El curso solicitado no está disponible.';
                header('Location: cursos');
                exit;
            }
        }

        $usuario_actual = Auth::usuario();

        return compact('curso', 'usuario_actual');
    }
}
