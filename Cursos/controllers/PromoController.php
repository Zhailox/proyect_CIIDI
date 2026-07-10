<?php
// modules/Cursos/controllers/PromoController.php

require_once CORE_PATH . 'Security/Auth.php';
require_once __DIR__ . '/../models/CursoModel.php';

class PromoController {

    private CursoModel $model;

    public function __construct() {
        $this->model = new CursoModel();
    }

    // -------------------------------------------------------------------------
    // CATÁLOGO — ruta: cursos
    // -------------------------------------------------------------------------
    /**
     * Lista todos los cursos publicados.
     * Inyecta: $cursos
     */
    public function catalogo(): array {
        Auth::check();
        $cursos = $this->model->getCursosPublicados();

        return [
            'cursos'        => $cursos,
            'total_cursos'  => count($cursos),
        ];
    }

    // -------------------------------------------------------------------------
    // DETALLE — ruta: detalle-curso?id=X
    // -------------------------------------------------------------------------
    /**
     * Muestra el detalle de un curso con sus lecciones.
     * Detecta si el usuario en sesión ya está inscrito.
     * Inyecta: $curso, $lecciones, $inscripcion, $usuario_id
     */
    public function detalle(): array {
        Auth::check();
        $id_curso = (int) ($_GET['id'] ?? 0);

        if ($id_curso <= 0) {
            header('Location: ?ruta=cursos');
            exit;
        }

        $curso = $this->model->getCurso($id_curso);

        if (!$curso) {
            header('Location: ?ruta=cursos');
            exit;
        }

        $lecciones   = $this->model->getLeccionesCurso($id_curso);
        $inscripcion = null;
        $usuario_id  = $_SESSION['usuario_id'] ?? null;

        if ($usuario_id) {
            $inscripcion = $this->model->getInscripcion((int) $usuario_id, $id_curso);
        }

        // Enriquecer curso con conteos
        $curso['total_lecciones'] = count($lecciones);
        $curso['total_inscritos']  = $this->model->contarInscritos($id_curso);

        return [
            'curso'       => $curso,
            'lecciones'   => $lecciones,
            'inscripcion' => $inscripcion,
            'usuario_id'  => $usuario_id,
        ];
    }

    // -------------------------------------------------------------------------
    // INSCRIBIRSE — ruta: inscribirse-curso (POST)
    // -------------------------------------------------------------------------
    /**
     * Procesa la inscripción de un usuario a un curso.
     * Patrón PRG: redirige siempre (nunca renderiza vista directa).
     */
    public function inscribirse(): false {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?ruta=cursos');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'] ?? null;

        // Sin sesión → redirigir al login
        if (!$usuario_id) {
            header('Location: ?ruta=login');
            exit;
        }

        $id_curso = (int) ($_POST['id_curso'] ?? 0);

        if ($id_curso <= 0) {
            header('Location: ?ruta=cursos');
            exit;
        }

        // Verificar que el curso existe y está publicado
        $curso = $this->model->getCurso($id_curso);
        if (!$curso || $curso['estado'] !== 'publicado') {
            header('Location: ?ruta=cursos');
            exit;
        }

        // Evitar inscripción duplicada
        $ya_inscrito = $this->model->getInscripcion((int) $usuario_id, $id_curso);

        if ($ya_inscrito) {
            header('Location: ?ruta=detalle-curso&id=' . $id_curso . '&ya_inscrito=1');
            exit;
        }

        // Registrar inscripción
        $resultado = $this->model->registrarInscripcion((int) $usuario_id, $id_curso);

        if ($resultado) {
            header('Location: ?ruta=detalle-curso&id=' . $id_curso . '&inscrito=1');
        } else {
            header('Location: ?ruta=detalle-curso&id=' . $id_curso . '&error=1');
        }

        exit;
    }

    // -------------------------------------------------------------------------
    // FORMULARIO CREAR / EDITAR — ruta: form-curso (GET)
    // -------------------------------------------------------------------------
    /**
     * Muestra el formulario vacío (crear) o precargado (editar).
     * Requiere nivel >= 1 (Profesor) para crear. Para editar, además verifica propiedad.
     * Inyecta: $curso (null si crear), $es_admin, $usuario_id
     */
    public function formCurso(): array {
        Auth::check(); // Asegura que la sesión esté iniciada
        $usuario_id      = $_SESSION['usuario_id']  ?? null;
        $nivel_privilegio = (int) ($_SESSION['nivel_privilegio'] ?? -1);

        // Solo Profesores (nivel>=1) y Admin (nivel>=3) pueden acceder
        if (!$usuario_id || $nivel_privilegio < 1) {
            header('Location: ?ruta=cursos');
            exit;
        }

        $id    = (int) ($_GET['id'] ?? 0);
        $curso = null;

        if ($id > 0) {
            $curso = $this->model->getCurso($id);
            // Verificar que puede gestionar este curso
            if (!$curso || !$this->model->puedeGestionar($id, (int) $usuario_id, $nivel_privilegio)) {
                header('Location: ?ruta=cursos');
                exit;
            }
        }

        return [
            'curso'    => $curso,
            'es_admin' => $nivel_privilegio >= 3,
            'usuario_id' => $usuario_id,
        ];
    }

    // -------------------------------------------------------------------------
    // GUARDAR CURSO — ruta: guardar-curso (POST)
    // -------------------------------------------------------------------------
    /**
     * Procesa el formulario de creación o edición.
     */
    public function guardarCurso(): false {
        Auth::check();
        $usuario_id       = $_SESSION['usuario_id']  ?? null;
        $nivel_privilegio = (int) ($_SESSION['nivel_privilegio'] ?? -1);

        if (!$usuario_id || $nivel_privilegio < 1 || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?ruta=cursos');
            exit;
        }

        $id     = (int) ($_POST['id'] ?? 0);
        $datos  = [
            'titulo'                 => htmlspecialchars(strip_tags($_POST['titulo']         ?? ''), ENT_QUOTES, 'UTF-8'),
            'descripcion'            => htmlspecialchars(strip_tags($_POST['descripcion']    ?? ''), ENT_QUOTES, 'UTF-8'),
            'imagen_portada'         => filter_var($_POST['imagen_portada']                  ?? '',  FILTER_SANITIZE_URL),
            'nota_minima_aprobacion' => min(100, max(0, (float) ($_POST['nota_minima_aprobacion'] ?? 70))),
            'estado'                 => in_array($_POST['estado'] ?? '', ['publicado','borrador','archivado'])
                                            ? $_POST['estado'] : 'publicado',
        ];

        if (empty(trim($datos['titulo']))) {
            header('Location: ?ruta=form-curso' . ($id ? "&id=$id" : '') . '&error=titulo_vacio');
            exit;
        }

        if ($id > 0) {
            // EDITAR: verificar permisos
            if (!$this->model->puedeGestionar($id, (int) $usuario_id, $nivel_privilegio)) {
                header('Location: ?ruta=cursos');
                exit;
            }
            $this->model->actualizarCurso($id, $datos);
            header('Location: ?ruta=detalle-curso&id=' . $id . '&actualizado=1');
        } else {
            // CREAR
            $datos['id_docente'] = (int) $usuario_id;
            $nuevo_id = $this->model->crearCurso($datos);
            header('Location: ?ruta=detalle-curso&id=' . $nuevo_id . '&creado=1');
        }

        exit;
    }

    // -------------------------------------------------------------------------
    // ELIMINAR CURSO — ruta: eliminar-curso (POST)
    // -------------------------------------------------------------------------
    /**
     * Solo Admin puede eliminar cualquier curso.
     * Docente puede eliminar solo los suyos.
     */
    public function eliminarCurso(): false {
        Auth::check();
        $usuario_id       = $_SESSION['usuario_id']  ?? null;
        $nivel_privilegio = (int) ($_SESSION['nivel_privilegio'] ?? -1);

        if (!$usuario_id || $nivel_privilegio < 1 || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?ruta=cursos');
            exit;
        }

        $id = (int) ($_POST['id_curso'] ?? 0);

        if ($id <= 0 || !$this->model->puedeGestionar($id, (int) $usuario_id, $nivel_privilegio)) {
            header('Location: ?ruta=cursos');
            exit;
        }

        $this->model->eliminarCurso($id);
        header('Location: ?ruta=cursos&eliminado=1');
        exit;
    }
}
