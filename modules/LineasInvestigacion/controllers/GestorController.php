<?php
// modules/LineasInvestigacion/controllers/GestorController.php

require_once __DIR__ . '/../../SuperAdmin/services/SystemConfigService.php';
require_once __DIR__ . '/../../../core/Security/Auth.php';
require_once __DIR__ . '/../../../core/Security/CSRF.php';
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
            if (!CSRF::validarToken($_POST['csrf_token'] ?? '')) {
                $this->redirigir('gestionar-lineas', 'error', 'Token de seguridad inválido. Intenta de nuevo.');
            }
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
                        if ($lineasModel->existeNombre($datos['nombre'], $datos['id_carrera'])) {
                            $this->redirigir('gestionar-lineas', 'error', 'Ya existe una línea con ese nombre en esa carrera.');
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
                        if (($_SESSION['nivel_privilegio'] ?? 999) !== 0) {
                            $this->redirigir('gestionar-lineas', 'error', 'Solo el Dios Superadministrador puede eliminar líneas de investigación.');
                        }
                        $id = (int) ($_POST['id'] ?? 0);
                        if ($id === 0) {
                            $this->redirigir('gestionar-lineas', 'error', 'ID inválido para eliminar.');
                        }
                        if ($lineasModel->tieneRecursosAsociados($id)) {
                            $this->redirigir('gestionar-lineas', 'error', 'No se puede eliminar la línea porque tiene proyectos o investigaciones asociadas. Ocultela en su lugar.');
                        }
                        $lineasModel->eliminar($id);
                        $this->redirigir('gestionar-lineas', 'exito', 'Línea eliminada definitivamente.');
                        break;

                    case 'ocultar':
                        $id = (int) ($_POST['id'] ?? 0);
                        $lineasModel->toggleActivo($id, false);
                        $this->redirigir('gestionar-lineas', 'exito', 'Línea ocultada del repositorio público.');
                        break;

                    case 'mostrar':
                        $id = (int) ($_POST['id'] ?? 0);
                        $lineasModel->toggleActivo($id, true);
                        $this->redirigir('gestionar-lineas', 'exito', 'Línea nuevamente visible.');
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
            if (!CSRF::validarToken($_POST['csrf_token'] ?? '')) {
                $this->redirigir('gestionar-lineas', 'error', 'Token de seguridad inválido. Intenta de nuevo.');
            }
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
                        if (($_SESSION['nivel_privilegio'] ?? 999) !== 0) {
                            $this->redirigir('gestionar-lineas', 'error', 'Solo el Dios Superadministrador puede eliminar dimensiones.');
                        }
                        $id = (int) ($_POST['id'] ?? 0);
                        if ($id === 0) {
                            $this->redirigir('gestionar-lineas', 'error', 'ID inválido.');
                        }
                        $dimModel->eliminar($id);
                        $this->redirigir('gestionar-lineas', 'exito', 'Dimensión eliminada correctamente.');
                        break;

                    case 'ocultar':
                        $id = (int) ($_POST['id'] ?? 0);
                        $lineasModel->toggleActivoDimension($id, false); // Using the method we added
                        $this->redirigir('gestionar-lineas', 'exito', 'Dimensión ocultada.');
                        break;
                        
                    case 'mostrar':
                        $id = (int) ($_POST['id'] ?? 0);
                        $lineasModel->toggleActivoDimension($id, true);
                        $this->redirigir('gestionar-lineas', 'exito', 'Dimensión nuevamente visible.');
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
    
    public function carreras() {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'auditar', 'LineasInvestigacion');
        $qb = new LineasModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!CSRF::validarToken($_POST['csrf_token'] ?? '')) {
                $this->redirigir('gestionar-carreras', 'error', 'Token de seguridad inválido. Intenta de nuevo.');
            }
            $accion = trim($_POST['accion'] ?? '');
            
            if ($accion === 'crear') Auth::requierePrivilegioMinimo($this->nivelAdmin, 'crear', 'LineasInvestigacion');
            if ($accion === 'editar') Auth::requierePrivilegioMinimo($this->nivelAdmin, 'editar', 'LineasInvestigacion');
            if ($accion === 'eliminar') Auth::requierePrivilegioMinimo($this->nivelAdmin, 'eliminar', 'LineasInvestigacion');

            try {
                switch ($accion) {
                    case 'crear':
                        $datos = [
                            'nombre'      => trim($_POST['nombre'] ?? ''),
                            'descripcion' => trim($_POST['descripcion'] ?? '')
                        ];
                        if (empty($datos['nombre'])) {
                            $this->redirigir('gestionar-carreras', 'error', 'El nombre de la carrera es obligatorio.');
                        }
                        $qb->tabla('carreras')->insert($datos);
                        $this->redirigir('gestionar-carreras', 'exito', 'Programa de Formación (PNF) creado correctamente.');
                        break;

                    case 'editar':
                        $id = (int) ($_POST['id'] ?? 0);
                        $datos = [
                            'nombre'      => trim($_POST['nombre'] ?? ''),
                            'descripcion' => trim($_POST['descripcion'] ?? '')
                        ];
                        if ($id === 0 || empty($datos['nombre'])) {
                            $this->redirigir('gestionar-carreras', 'error', 'Datos inválidos para actualizar.');
                        }
                        $qb->tabla('carreras')->where('id', '=', $id)->update($datos);
                        $this->redirigir('gestionar-carreras', 'exito', 'Programa de Formación (PNF) actualizado correctamente.');
                        break;

                    case 'eliminar':
                        if (($_SESSION['nivel_privilegio'] ?? 999) !== 0) {
                            $this->redirigir('gestionar-carreras', 'error', 'Solo el Dios Superadministrador puede eliminar carreras.');
                        }
                        $id = (int) ($_POST['id'] ?? 0);
                        if ($id === 0) {
                            $this->redirigir('gestionar-carreras', 'error', 'ID inválido.');
                        }
                        // Will fail if foreign key constraint exists, which is caught in catch block
                        $qb->tabla('carreras')->where('id', '=', $id)->delete();
                        $this->redirigir('gestionar-carreras', 'exito', 'Programa de Formación (PNF) eliminado correctamente.');
                        break;
                        
                    default:
                        $this->redirigir('gestionar-carreras', 'error', 'Acción no reconocida.');
                }
            } catch (Exception $e) {
                $msg = $e->getMessage();
                if (strpos($msg, '23000') !== false || strpos($msg, '23503') !== false) {
                    $msg = 'No se puede eliminar porque esta carrera ya tiene líneas de investigación asociadas.';
                } else {
                    $msg = 'Error en la base de datos: ' . $msg;
                }
                $this->redirigir('gestionar-carreras', 'error', $msg);
            }
        }

        // GET: cargar datos para la vista
        $carreras = $qb->tabla('carreras')->orderBy('nombre', 'ASC')->get();
        
        $carrera_editar = null;
        if (!empty($_GET['editar'])) {
            $res = $qb->tabla('carreras')->where('id', '=', (int)$_GET['editar'])->get();
            if(!empty($res)) $carrera_editar = $res[0];
        }

        $mensaje      = htmlspecialchars($_GET['msg'] ?? '');
        $tipo_mensaje = htmlspecialchars($_GET['tipo'] ?? '');

        return [
            'carreras'       => $carreras,
            'carrera_editar' => $carrera_editar,
            'mensaje'        => $mensaje,
            'tipo_mensaje'   => $tipo_mensaje,
            'isSuper'        => ($_SESSION['nivel_privilegio'] ?? 999) === 0
        ];
    }

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
    // =========================================================
    //  EXPORTACI"N Y REPORTES

    // =========================================================
    //  EXPORTACION Y REPORTES
    // =========================================================

    public function exportarCsv() {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'auditar', 'LineasInvestigacion');
        $lineasModel = new LineasModel();
        $lineas = $lineasModel->getTodasConEstadisticas();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="Matriz_Lineas_Investigacion_' . date('Ymd') . '.csv"');
        
        $salida = fopen('php://output', 'w');
        // UTF-8 BOM para Excel
        fprintf($salida, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($salida, ['ID', 'Línea de Investigación', 'PNF / Carrera', 'Descripción', 'Total Proyectos', 'Total Investigaciones Ofertadas'], ';');
        
        foreach ($lineas as $l) {
            fputcsv($salida, [
                $l['id'],
                $l['nombre'],
                $l['carrera_nombre'] ?? 'General',
                $l['descripcion'],
                $l['total_proyectos'],
                $l['total_investigaciones']
            ], ';');
        }
        fclose($salida);
        exit;
    }

    
    public function apiFiltrosPdf() {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'auditar', 'LineasInvestigacion');
        header('Content-Type: application/json');
        
        $lineasModel = new LineasModel();
        
        $carreras = $this->getCarreras();
        $lineas = $lineasModel->getTodas();
        
        echo json_encode([
            'carreras' => $carreras,
            'lineas' => $lineas
        ]);
        exit;
    }

    public function generarReportePdf() {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'auditar', 'LineasInvestigacion');
        
        $tipo = $_GET['tipo'] ?? 'completo';
        $lineasModel = new LineasModel();
        $dimModel = new DimensionesModel();
        
        $datos = [];
        $tituloReporte = "Reporte de Líneas de Investigación";

        $id_carrera = isset($_GET['id_carrera']) && $_GET['id_carrera'] !== '' ? (int)$_GET['id_carrera'] : null;
        $id_linea = isset($_GET['id_linea']) && $_GET['id_linea'] !== '' ? (int)$_GET['id_linea'] : null;

        // Fetch everything hierarchically to have it ready for the view to decide how to render
        $carreras_raw = $this->getCarreras();
        $todas_lineas = $lineasModel->getTodas();
        $todas_dimensiones = $dimModel->getTodasConLinea();

        // Aplicar filtros
        if ($id_carrera !== null) {
            $carreras_raw = array_filter($carreras_raw, function($c) use ($id_carrera) {
                return $c['id'] == $id_carrera;
            });
        }
        if ($id_linea !== null) {
            $todas_lineas = array_filter($todas_lineas, function($l) use ($id_linea) {
                return $l['id'] == $id_linea;
            });
        }

        // Organizar datos en árbol
        $arbol = [];
        foreach ($carreras_raw as $c) {
            $arbol[$c['id']] = [
                'carrera' => $c,
                'lineas' => []
            ];
        }
        
        // Pseudo carrera para líneas sin asignar (ID 0 o null)
        $arbol[0] = [
            'carrera' => ['id' => 0, 'nombre' => 'General / Sin Asignar', 'descripcion' => 'Líneas transversales'],
            'lineas' => []
        ];

        foreach ($todas_lineas as $l) {
            $cid = $l['id_carrera'] ?: 0;
            if (!isset($arbol[$cid])) continue;
            
            // Asignar dimensiones a esta línea
            $dims_de_linea = [];
            foreach ($todas_dimensiones as $d) {
                if ($d['id_linea'] == $l['id']) {
                    $dims_de_linea[] = $d;
                }
            }
            $l['dimensiones'] = $dims_de_linea;
            $arbol[$cid]['lineas'][] = $l;
        }

        switch($tipo) {
            case 'carreras':
                $tituloReporte = "Estructura Académica por PNF";
                break;
            case 'lineas':
                $tituloReporte = "Directorio de Líneas de Investigación";
                break;
            case 'dimensiones':
                $tituloReporte = "Matriz de Dimensiones Operativas";
                break;
            case 'completo':
            default:
                $tituloReporte = "Reporte Completo: Estructura de Líneas de Investigación";
                break;
        }

        // Render the print view directly
        require_once __DIR__ . '/../views/reporte_pdf.php';
        exit;
    }

    public function imprimirMatriz() {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'auditar', 'LineasInvestigacion');
        $lineasModel = new LineasModel();
        $lineas = $lineasModel->getTodasConEstadisticas();

        // Esta vista imprime un HTML limpio pensado para ser convertido a PDF mediante window.print()
        echo "<!DOCTYPE html><html><head><title>Matriz de Líneas de Investigación</title>";
        echo "<style>body { font-family: Arial, sans-serif; padding: 20px; } table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ccc; padding: 8px; text-align: left; } th { background: #f4f4f4; }</style>";
        echo "</head><body onload='window.print()'>";
        echo "<h2>Matriz de Líneas de Investigación e Innovación</h2>";
        echo "<table><thead><tr><th>Línea</th><th>PNF</th><th>Proyectos</th><th>Ofertas</th></tr></thead><tbody>";
        foreach ($lineas as $l) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($l['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($l['carrera_nombre'] ?? 'General') . "</td>";
            echo "<td>" . (int)$l['total_proyectos'] . "</td>";
            echo "<td>" . (int)$l['total_investigaciones'] . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table></body></html>";
        exit;
    }
}
