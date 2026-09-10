<?php
// modules/Investigaciones/controllers/InvestigacionController.php
require_once __DIR__ . '/../models/InvestigacionModel.php';
require_once CORE_PATH . 'Security/Auth.php';

class InvestigacionController {

    private InvestigacionModel $model;

    public function __construct() {
        $this->model = new InvestigacionModel();
    }

    // ──────────────────────────────────────────────────────────────────────────
    // VISTAS PÚBLICAS Y DE ESTUDIANTES
    // ──────────────────────────────────────────────────────────────────────────

    public function mostrarCartelera(): array {
        $lineas = $this->model->obtenerLineas();
        
        $filtros = [];
        if (!empty($_GET['q'])) {
            $filtros['busqueda'] = trim(strip_tags($_GET['q']));
        }
        if (!empty($_GET['linea'])) {
            $filtros['id_linea'] = (int)$_GET['linea'];
        }
        if (!empty($_GET['estado'])) {
            $filtros['estado'] = trim(strip_tags($_GET['estado']));
        }
        
        $investigaciones = $this->model->listarInvestigaciones($filtros);
        $busqueda = $_GET['q'] ?? '';

        return compact('lineas', 'investigaciones', 'busqueda');
    }

    public function mostrarPanelPostulaciones(): array {
        Auth::requierePrivilegioMinimo(0);
        $user = Auth::usuario();

        $lineas = $this->model->obtenerLineas();
        
        $filtros = ['estado' => 'Abierta'];
        if (!empty($_GET['linea'])) {
            $filtros['id_linea'] = (int)$_GET['linea'];
        }
        
        $investigaciones = $this->model->listarInvestigaciones($filtros);
        
        $agrupadas = ['t3' => [], 't4' => [], 'maestria' => []];
        foreach ($investigaciones as $inv) {
            $trayecto = $inv['trayecto'] ?? 't4';
            if (isset($agrupadas[$trayecto])) {
                $agrupadas[$trayecto][] = $inv;
            } else {
                $agrupadas['t4'][] = $inv;
            }
        }

        $filtro_linea = $_GET['linea'] ?? '';
        
        // Cargar las postulaciones del usuario para deshabilitar botones
        $misPostulaciones = $this->model->obtenerMisPostulaciones((int)$user['id']);
        $postuladas_ids = array_column($misPostulaciones, 'id_investigacion');

        return compact('agrupadas', 'lineas', 'filtro_linea', 'misPostulaciones', 'postuladas_ids');
    }

    public function procesarPostulacion() {
        Auth::requierePrivilegioMinimo(0);
        $user = Auth::usuario();
        
        $id_inv = (int)($_POST['id_investigacion'] ?? 0);
        $motivacion = trim($_POST['motivacion'] ?? '');
        $portafolio = trim($_POST['portafolio'] ?? '');
        
        if (empty($id_inv) || empty($motivacion)) {
            $_SESSION['flash_error'] = 'Debe completar el mensaje de motivación.';
            header('Location: ?ruta=postulaciones-investigacion');
            exit;
        }
        
        if (!empty($portafolio)) {
            $motivacion .= "\n\nEnlace al Portafolio: " . $portafolio;
        }
        
        $ok = $this->model->postularEstudiante($id_inv, (int)$user['id'], $motivacion);
        
        if ($ok) {
            $_SESSION['flash_success'] = '¡Tu postulación ha sido enviada con éxito! El docente a cargo revisará tu perfil.';
        } else {
            $_SESSION['flash_error'] = 'Ya te has postulado a este proyecto anteriormente.';
        }
        
        header('Location: ?ruta=postulaciones-investigacion');
        exit;
    }
    
    public function mostrarInvestigadores(): array {
        $investigadores = $this->model->obtenerInvestigadores();
        return compact('investigadores');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // PROFESORES (Nivel >= 1)
    // ──────────────────────────────────────────────────────────────────────────

    public function mostrarMisInvestigaciones(): array {
        Auth::requierePrivilegioMinimo(1);
        $user = Auth::usuario();
        $investigaciones = $this->model->obtenerMisInvestigaciones((int)$user['id']);
        return compact('investigaciones');
    }

    public function mostrarFormCrear(): array {
        Auth::requierePrivilegioMinimo(1);
        $lineas = $this->model->obtenerLineas();
        // Variables por defecto para el formulario
        $investigacion = [
            'id' => 0, 'titulo' => '', 'planteamiento_problema' => '', 
            'objetivo_general' => '', 'id_linea' => '', 'id_dimension' => '', 
            'cupos_disponibles' => 3, 'estado' => 'Abierta', 'trayecto' => 't4', 'tag_que' => 'Proyecto'
        ];
        return compact('lineas', 'investigacion');
    }

    public function mostrarFormEditar(): array {
        Auth::requierePrivilegioMinimo(1);
        $user = Auth::usuario();
        $id = (int)($_GET['id'] ?? 0);
        
        $investigacion = $this->model->obtenerPorId($id);
        if (!$investigacion || ($investigacion['id_profesor'] != $user['id'] && (int)$user['nivel'] < 2)) {
            $_SESSION['flash_error'] = 'No tienes permiso para editar esta investigación.';
            header('Location: ?ruta=mis-investigaciones');
            exit;
        }
        
        $lineas = $this->model->obtenerLineas();
        $dimensiones = $this->model->obtenerDimensiones((int)$investigacion['id_linea']);
        
        return compact('lineas', 'dimensiones', 'investigacion');
    }

    public function guardarInvestigacion() {
        Auth::requierePrivilegioMinimo(1);
        $user = Auth::usuario();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        
        $datos = [
            'titulo' => trim($_POST['titulo'] ?? ''),
            'planteamiento_problema' => trim($_POST['planteamiento_problema'] ?? ''),
            'objetivo_general' => trim($_POST['objetivo_general'] ?? ''),
            'id_linea' => (int)($_POST['id_linea'] ?? 0),
            'id_dimension' => (int)($_POST['id_dimension'] ?? 0),
            'cupos_disponibles' => (int)($_POST['cupos_disponibles'] ?? 3),
            'estado' => $_POST['estado'] ?? 'Abierta',
            'trayecto' => $_POST['trayecto'] ?? 't4',
            'tag_que' => $_POST['tag_que'] ?? 'Proyecto'
        ];
        
        // Manejo de Upload de Imagen
        $imagenUrl = '';
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagenUrl = $this->subirImagen($_FILES['imagen']);
        } elseif (!empty($_POST['imagen_url'])) {
            $imagenUrl = trim($_POST['imagen_url']);
        }
        
        if ($imagenUrl) {
            $datos['imagen'] = $imagenUrl;
        }

        if (empty($datos['titulo']) || empty($datos['id_linea'])) {
            $_SESSION['flash_error'] = 'El título y la línea de investigación son obligatorios.';
            header('Location: ?ruta=crear-investigacion');
            exit;
        }

        $id = $this->model->crearInvestigacion($datos, (int)$user['id']);
        
        if ($id) {
            $_SESSION['flash_success'] = 'Investigación creada exitosamente.';
            header('Location: ?ruta=mis-investigaciones');
        } else {
            $_SESSION['flash_error'] = 'Error al crear la investigación.';
            header('Location: ?ruta=crear-investigacion');
        }
        exit;
    }

    public function actualizarInvestigacion() {
        Auth::requierePrivilegioMinimo(1);
        $user = Auth::usuario();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        
        $id = (int)($_POST['id'] ?? 0);
        $datos = [
            'titulo' => trim($_POST['titulo'] ?? ''),
            'planteamiento_problema' => trim($_POST['planteamiento_problema'] ?? ''),
            'objetivo_general' => trim($_POST['objetivo_general'] ?? ''),
            'id_linea' => (int)($_POST['id_linea'] ?? 0),
            'id_dimension' => (int)($_POST['id_dimension'] ?? 0),
            'cupos_disponibles' => (int)($_POST['cupos_disponibles'] ?? 3),
            'estado' => $_POST['estado'] ?? 'Abierta',
            'trayecto' => $_POST['trayecto'] ?? 't4',
            'tag_que' => $_POST['tag_que'] ?? 'Proyecto'
        ];

        // Manejo de Upload de Imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagenUrl = $this->subirImagen($_FILES['imagen']);
            if ($imagenUrl) {
                $datos['imagen'] = $imagenUrl;
            }
        } elseif (!empty($_POST['imagen_url'])) {
            $datos['imagen'] = trim($_POST['imagen_url']);
        }

        $ok = $this->model->actualizarInvestigacion($id, $datos, (int)$user['id'], (int)$user['nivel']);
        
        if ($ok) {
            $_SESSION['flash_success'] = 'Investigación actualizada exitosamente.';
        } else {
            $_SESSION['flash_error'] = 'No tienes permiso o ocurrió un error al actualizar.';
        }
        
        $redir = ((int)$user['nivel'] >= 2 && isset($_POST['from_admin'])) ? '?ruta=panel-investigaciones-admin' : '?ruta=mis-investigaciones';
        header("Location: $redir");
        exit;
    }

    public function eliminarInvestigacion() {
        Auth::requierePrivilegioMinimo(1);
        $user = Auth::usuario();
        $id = (int)($_POST['id'] ?? 0);
        
        $ok = $this->model->eliminarInvestigacion($id, (int)$user['id'], (int)$user['nivel']);
        
        if ($ok) {
            $_SESSION['flash_success'] = 'Investigación eliminada correctamente.';
        } else {
            $_SESSION['flash_error'] = 'Error al eliminar la investigación o sin permisos.';
        }
        
        $redir = ((int)$user['nivel'] >= 2 && isset($_POST['from_admin'])) ? '?ruta=panel-investigaciones-admin' : '?ruta=mis-investigaciones';
        header("Location: $redir");
        exit;
    }

    public function mostrarMisPostulantes(): array {
        Auth::requierePrivilegioMinimo(1);
        $user = Auth::usuario();
        $postulaciones = $this->model->obtenerPostulantesDeMiProyecto((int)$user['id']);
        return compact('postulaciones');
    }

    public function responderPostulacion() {
        Auth::requierePrivilegioMinimo(1);
        $user = Auth::usuario();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        
        $id_postulacion = (int)($_POST['id_postulacion'] ?? 0);
        $estado = $_POST['estado'] ?? ''; // Aceptado, Rechazado
        
        if (in_array($estado, ['Aceptado', 'Rechazado'])) {
            $ok = $this->model->responderPostulacion($id_postulacion, $estado, (int)$user['id'], (int)$user['nivel']);
            if ($ok) {
                $_SESSION['flash_success'] = "Postulación marcada como $estado.";
            } else {
                $_SESSION['flash_error'] = "Error al procesar la respuesta o falta de permisos.";
            }
        }
        
        $redir = ((int)$user['nivel'] >= 2 && isset($_POST['from_admin'])) ? '?ruta=panel-investigaciones-admin' : '?ruta=mis-postulantes';
        header("Location: $redir");
        exit;
    }

    // ──────────────────────────────────────────────────────────────────────────
    // ADMIN (Nivel >= 2)
    // ──────────────────────────────────────────────────────────────────────────

    public function mostrarPanelAdmin(): array {
        Auth::requierePrivilegioMinimo(2);
        
        $investigaciones = $this->model->obtenerTodasAdmin();
        $postulaciones = $this->model->obtenerPostulacionesAdmin();
        $lineas = $this->model->obtenerLineas();
        
        return compact('investigaciones', 'postulaciones', 'lineas');
    }

    public function cambiarEstado() {
        Auth::requierePrivilegioMinimo(2);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        
        $id = (int)($_POST['id'] ?? 0);
        $estado = $_POST['estado'] ?? '';
        
        $this->model->cambiarEstadoInvestigacion($id, $estado);
        $_SESSION['flash_success'] = 'Estado de investigación actualizado.';
        
        header('Location: ?ruta=panel-investigaciones-admin');
        exit;
    }

    // ──────────────────────────────────────────────────────────────────────────
    // UTILIDADES
    // ──────────────────────────────────────────────────────────────────────────

    private function subirImagen(array $file): string {
        $targetDir = dirname(__DIR__, 3) . '/public/uploads/investigaciones/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        
        $fileName = uniqid('inv_') . '_' . basename($file['name']);
        $targetPath = $targetDir . $fileName;
        
        // Validar tipo de imagen
        $validTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (in_array($file['type'], $validTypes)) {
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                return 'uploads/investigaciones/' . $fileName;
            }
        }
        return '';
    }
}
