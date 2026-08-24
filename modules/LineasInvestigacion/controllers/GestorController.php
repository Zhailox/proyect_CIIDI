<?php
// modules/LineasInvestigacion/controllers/GestorController.php

require_once __DIR__ . '/../models/LineasModel.php';
require_once __DIR__ . '/../models/DimensionesModel.php';

/**
 * GestorLineasController
 * Maneja el CRUD de Líneas de Investigación y Dimensiones Operativas.
 * Comparte dos métodos: index() para líneas y dimensiones() para dimensiones.
 */
class GestorLineasController {

    // =========================================================
    //  GESTIÓN DE LÍNEAS DE INVESTIGACIÓN
    // =========================================================

    public function index() {
        $lineasModel = new LineasModel();

        // --- Procesar operaciones POST (CRUD) ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accion = trim($_POST['accion'] ?? '');

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
                $this->redirigir('gestionar-lineas', 'error', 'Error en la base de datos: ' . $e->getMessage());
            }

            return false; // Siempre redirecciona en POST
        }

        // --- GET: cargar datos para la vista ---
        $lineas   = $lineasModel->getTodasConEstadisticas();
        $carreras = $this->getCarreras();

        // Línea a editar (si viene ?editar=ID)
        $linea_editar = null;
        if (!empty($_GET['editar'])) {
            $linea_editar = $lineasModel->getLineaConCarrera((int) $_GET['editar']);
        }

        // Mensajes flash desde URL
        $mensaje      = htmlspecialchars($_GET['msg'] ?? '');
        $tipo_mensaje = htmlspecialchars($_GET['tipo'] ?? '');

        return [
            'lineas'        => $lineas,
            'carreras'      => $carreras,
            'linea_editar'  => $linea_editar,
            'mensaje'       => $mensaje,
            'tipo_mensaje'  => $tipo_mensaje,
        ];
    }

    // =========================================================
    //  GESTIÓN DE DIMENSIONES OPERATIVAS
    // =========================================================

    public function dimensiones() {
        $dimModel    = new DimensionesModel();
        $lineasModel = new LineasModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accion = trim($_POST['accion'] ?? '');

            try {
                switch ($accion) {

                    case 'crear':
                        $datos = [
                            'id_linea'    => (int) ($_POST['id_linea'] ?? 0),
                            'nombre'      => trim($_POST['nombre'] ?? ''),
                            'descripcion' => trim($_POST['descripcion'] ?? ''),
                        ];
                        if (empty($datos['nombre']) || $datos['id_linea'] === 0) {
                            $this->redirigir('gestionar-dimensiones', 'error', 'Nombre y línea son obligatorios.');
                        }
                        $dimModel->crear($datos);
                        $this->redirigir('gestionar-dimensiones', 'exito', 'Dimensión operativa creada exitosamente.');
                        break;

                    case 'editar':
                        $id    = (int) ($_POST['id'] ?? 0);
                        $datos = [
                            'id_linea'    => (int) ($_POST['id_linea'] ?? 0),
                            'nombre'      => trim($_POST['nombre'] ?? ''),
                            'descripcion' => trim($_POST['descripcion'] ?? ''),
                        ];
                        if ($id === 0 || empty($datos['nombre'])) {
                            $this->redirigir('gestionar-dimensiones', 'error', 'Datos incompletos.');
                        }
                        $dimModel->actualizar($id, $datos);
                        $this->redirigir('gestionar-dimensiones', 'exito', 'Dimensión actualizada correctamente.');
                        break;

                    case 'eliminar':
                        $id = (int) ($_POST['id'] ?? 0);
                        if ($id === 0) {
                            $this->redirigir('gestionar-dimensiones', 'error', 'ID inválido.');
                        }
                        $dimModel->eliminar($id);
                        $this->redirigir('gestionar-dimensiones', 'exito', 'Dimensión eliminada correctamente.');
                        break;

                    default:
                        $this->redirigir('gestionar-dimensiones', 'error', 'Acción no reconocida.');
                }
            } catch (Exception $e) {
                $this->redirigir('gestionar-dimensiones', 'error', 'Error en la BD: ' . $e->getMessage());
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
