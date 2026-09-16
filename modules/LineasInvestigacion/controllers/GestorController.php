<?php
// modules/LineasInvestigacion/controllers/GestorController.php

require_once __DIR__ . '/../../SuperAdmin/services/SystemConfigService.php';
require_once __DIR__ . '/../../../core/Security/Auth.php';
require_once __DIR__ . '/../models/LineasModel.php';
require_once __DIR__ . '/../models/DimensionesModel.php';

/**
 * GestorLineasController
 * Maneja el CRUD de Líneas de Investigación y Dimensiones Operativas.
 * Comparte dos métodos: index() para líneas y dimensiones() para dimensiones.
 */
class GestorLineasController {

    private int $nivelAdmin;

    public function __construct() {
        $this->nivelAdmin = SystemConfigService::get('accesos_modulos.lineas_investigacion.admin', 1);
    }

    // =========================================================
    //  GESTIÓN DE LÍNEAS DE INVESTIGACIÓN
    // =========================================================

    public function index() {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'auditar', 'LineasInvestigacion');
        $lineasModel = new LineasModel();

        // --- Procesar operaciones POST (CRUD) ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accion = trim($_POST['accion'] ?? '');
            if ($accion === 'crear') Auth::requierePrivilegioMinimo($this->nivelAdmin, 'crear', 'LineasInvestigacion');
            if ($accion === 'editar') Auth::requierePrivilegioMinimo($this->nivelAdmin, 'editar', 'LineasInvestigacion');
            if ($accion === 'eliminar') Auth::requierePrivilegioMinimo($this->nivelAdmin, 'eliminar', 'LineasInvestigacion');

            try {
                switch ($accion) {

                    case 'crear':
                        $datos = [
                            'nombre'      => trim($_POST['nombre'] ?? ''),
                            'id_carrera'  => (int) ($_POST['id_carrera'] ?? 0),
                            'descripcion' => trim($_POST['descripcion'] ?? ''),
                        ];
                        if (empty($datos['nombre']) || $datos['id_carrera'] === 0) {
                            $this->redirigir('gestionar-lineas', 'error', 'El nombre y la carrera son obligatorios.');
                        }
                        $lineasModel->crear($datos);
                        $this->redirigir('gestionar-lineas', 'exito', 'Línea de investigación creada exitosamente.');
                        break;

                    case 'editar':
                        $id    = (int) ($_POST['id'] ?? 0);
                        $datos = [
                            'nombre'      => trim($_POST['nombre'] ?? ''),
                            'id_carrera'  => (int) ($_POST['id_carrera'] ?? 0),
                            'descripcion' => trim($_POST['descripcion'] ?? ''),
                        ];
                        if ($id === 0 || empty($datos['nombre'])) {
                            $this->redirigir('gestionar-lineas', 'error', 'Datos incompletos para la actualización.');
                        }
                        $lineasModel->actualizar($id, $datos);
                        $this->redirigir('gestionar-lineas', 'exito', 'Línea actualizada correctamente.');
                        break;

                    case 'eliminar':
                        $id = (int) ($_POST['id'] ?? 0);
                        if ($id === 0) {
                            $this->redirigir('gestionar-lineas', 'error', 'ID inválido para eliminar.');
                        }
                        $lineasModel->eliminar($id);
                        $this->redirigir('gestionar-lineas', 'exito', 'Línea eliminada. Sus dimensiones asociadas también fueron removidas.');
                        break;

                    default:
                        $this->redirigir('gestionar-lineas', 'error', 'Acción no reconocida.');
                }
            } catch (Exception $e) {
                $msg = $e->getMessage();
                if (strpos($msg, '23000') !== false || strpos($msg, '23503') !== false) {
                    $msg = 'No se puede eliminar porque tiene registros asociados dependientes (ej. proyectos o investigaciones).';
                } else {
                    $msg = 'Error en la base de datos: ' . $msg;
                }
                $this->redirigir('gestionar-lineas', 'error', $msg);
            }

            return false; // Siempre redirecciona en POST
        }

        // --- GET: cargar datos para la vista ---
        $lineas   = $lineasModel->getTodasConEstadisticas();
        $carreras = $this->getCarreras();

        $dimModel = new DimensionesModel();
        $todas_dimensiones = $dimModel->getTodasConLinea();

        // Línea a editar (si viene ?editar=ID)
        $linea_editar = null;
        if (!empty($_GET['editar'])) {
            $linea_editar = $lineasModel->getLineaConCarrera((int) $_GET['editar']);
        }

        // Dimensión a editar (si viene ?editar_dim=ID)
        $dim_editar = null;
        if (!empty($_GET['editar_dim'])) {
            $dim_editar = $dimModel->getPorId((int) $_GET['editar_dim']);
        }

        // Mensajes flash desde URL
        $mensaje      = htmlspecialchars($_GET['msg'] ?? '');
        $tipo_mensaje = htmlspecialchars($_GET['tipo'] ?? '');

        return [
            'lineas'        => $lineas,
            'carreras'      => $carreras,
            'linea_editar'  => $linea_editar,
            'dimensiones'   => $todas_dimensiones,
            'dim_editar'    => $dim_editar,
            'mensaje'       => $mensaje,
            'tipo_mensaje'  => $tipo_mensaje,
        ];
    }

    // =========================================================
    //  GESTIÓN DE DIMENSIONES OPERATIVAS
    // =========================================================


    public function detalleGestionLinea() {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'auditar', 'LineasInvestigacion');
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            header("Location: index.php?ruta=gestionar-lineas");
            exit;
        }

        $lineasModel = new LineasModel();
        $linea = $lineasModel->getLineaConCarrera($id);
        if (!$linea) {
            header("Location: index.php?ruta=gestionar-lineas");
            exit;
        }

        $dimModel = new DimensionesModel();
        $dimensiones = $dimModel->getPorLinea($id);

        $mensaje = htmlspecialchars($_GET['msg'] ?? '');
        $tipo_mensaje = htmlspecialchars($_GET['tipo'] ?? '');

        return [
            'linea'        => $linea,
            'dimensiones'  => $dimensiones,
            'mensaje'      => $mensaje,
            'tipo_mensaje' => $tipo_mensaje
        ];
    }

    public function dimensiones() {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'auditar', 'LineasInvestigacion');
        $dimModel    = new DimensionesModel();
        $lineasModel = new LineasModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accion = trim($_POST['accion'] ?? '');
            if ($accion === 'crear') Auth::requierePrivilegioMinimo($this->nivelAdmin, 'crear', 'LineasInvestigacion');
            if ($accion === 'editar') Auth::requierePrivilegioMinimo($this->nivelAdmin, 'editar', 'LineasInvestigacion');
            if ($accion === 'eliminar') Auth::requierePrivilegioMinimo($this->nivelAdmin, 'eliminar', 'LineasInvestigacion');

            try {
                switch ($accion) {

                    case 'crear':
                        $datos = [
                            'id_linea'    => (int) ($_POST['id_linea'] ?? 0),
                            'nombre'      => trim($_POST['nombre'] ?? ''),
                            'descripcion' => trim($_POST['descripcion'] ?? ''),
                        ];
                        if (empty($datos['nombre']) || $datos['id_linea'] === 0) {
                            $this->redirigir('gestionar-lineas', 'error', 'Nombre y línea son obligatorios.');
                        }
                        $dimModel->crear($datos);
                        $this->redirigir('gestionar-lineas', 'exito', 'Dimensión operativa creada exitosamente.');
                        break;

                    case 'editar':
                        $id    = (int) ($_POST['id'] ?? 0);
                        $datos = [
                            'id_linea'    => (int) ($_POST['id_linea'] ?? 0),
                            'nombre'      => trim($_POST['nombre'] ?? ''),
                            'descripcion' => trim($_POST['descripcion'] ?? ''),
                        ];
                        if ($id === 0 || empty($datos['nombre'])) {
                            $this->redirigir('gestionar-lineas', 'error', 'Datos incompletos.');
                        }
                        $dimModel->actualizar($id, $datos);
                        $this->redirigir('gestionar-lineas', 'exito', 'Dimensión actualizada correctamente.');
                        break;

                    case 'eliminar':
                        $id = (int) ($_POST['id'] ?? 0);
                        if ($id === 0) {
                            $this->redirigir('gestionar-lineas', 'error', 'ID inválido.');
                        }
                        $dimModel->eliminar($id);
                        $this->redirigir('gestionar-lineas', 'exito', 'Dimensión eliminada correctamente.');
                        break;

                    default:
                        $this->redirigir('gestionar-lineas', 'error', 'Acción no reconocida.');
                }
            } catch (Exception $e) {
                $this->redirigir('gestionar-lineas', 'error', 'Error en la BD: ' . $e->getMessage());
            }

            return false;
        }

        // GET: cargar datos para la vista
        $dimensiones   = $dimModel->getTodasConLinea();
        $lineas        = $lineasModel->getTodas();

        $dim_editar = null;
        if (!empty($_GET['editar'])) {
            $dim_editar = $dimModel->getPorId((int) $_GET['editar']);
        }

        $mensaje      = htmlspecialchars($_GET['msg'] ?? '');
        $tipo_mensaje = htmlspecialchars($_GET['tipo'] ?? '');

        return [
            'dimensiones'  => $dimensiones,
            'lineas'       => $lineas,
            'dim_editar'   => $dim_editar,
            'mensaje'      => $mensaje,
            'tipo_mensaje' => $tipo_mensaje,
        ];
    }

    // =========================================================
    //  MÉTODOS AUXILIARES PRIVADOS
    // =========================================================

    /**
     * Redirige a una ruta con un mensaje de estado y detiene la ejecución.
     */
    private function redirigir(string $ruta, string $tipo, string $msg): void {
        if (!empty($_POST['redirect_to'])) {
            $ruta = $_POST['redirect_to'];
        }
        $url = 'index.php?ruta=' . $ruta
             . '&tipo=' . urlencode($tipo)
             . '&msg='  . urlencode($msg);
        header('Location: ' . $url);
        exit;
    }

    /**
     * Obtiene todas las carreras de la BD (para llenar selectores).
     */
    private function getCarreras(): array {
        // Usamos QueryBuilder directamente (LineasModel ya lo hereda)
        $qb = new LineasModel();
        return $qb->tabla('carreras')->orderBy('nombre', 'ASC')->get();
    }
}
