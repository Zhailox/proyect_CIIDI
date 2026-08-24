<?php
// modules/SuperAdmin/controllers/GestorUsuariosController.php
require_once CORE_PATH . 'Security/Auth.php';
require_once __DIR__ . '/../models/AdminUsuarioModel.php';

class GestorUsuariosController {
    
    private $adminModel;

    public function __construct() {
        $this->adminModel = new AdminUsuarioModel();
    }

    public function index() {
        // Nivel 2 mínimo (Bibliotecario o SuperAdmin)
        Auth::requierePrivilegioMinimo(2);

        // Capturamos si hay una búsqueda activa
        $cedulaBusqueda = trim($_GET['cedula'] ?? '');
        $resultadoBusqueda = null;
        $mensajeError = null;

        if (!empty($cedulaBusqueda)) {
            $resultadoBusqueda = $this->adminModel->buscarPorCedula($cedulaBusqueda);
            if (!$resultadoBusqueda) {
                $mensajeError = "No se encontró ningún usuario con la cédula: " . htmlspecialchars($cedulaBusqueda);
            }
        }

        // Cargamos la lista de profesores para la segunda sección
        $profesores = $this->adminModel->obtenerProfesores();

        return [
            'cedulaBusqueda' => $cedulaBusqueda,
            'usuarioEncontrado' => $resultadoBusqueda,
            'mensajeError' => $mensajeError,
            'profesores' => $profesores
        ];
    }
    // Carga la vista de edición con los datos del usuario
    public function mostrarEdicion() {
        Auth::requierePrivilegioMinimo(2);
        
        $cedula = $_GET['cedula'] ?? '';
        $usuario = $this->adminModel->buscarPorCedula($cedula);

        if (!$usuario) {
            header("Location: gestor-usuarios");
            exit;
        }

        // Bloqueo de seguridad: No puedes editarte a ti mismo desde aquí
        if ($usuario['id'] === $_SESSION['usuario_id']) {
            $_SESSION['error_gestor'] = "Para editar tus propios datos, utiliza la configuración de tu perfil personal.";
            header("Location: gestor-usuarios?cedula=" . urlencode($cedula));
            exit;
        }

        return [
            'usuarioEditar' => $usuario,
            'rolesDisponibles' => $this->adminModel->obtenerRoles(),
            'error' => $_SESSION['error_edicion'] ?? null
        ];
    }

    // Recibe los datos del formulario de edición y los guarda
    public function procesarEdicion() {
        Auth::requierePrivilegioMinimo(2);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return false;

        $id = (int) $_POST['usuario_id'];
        $cedula = trim($_POST['cedula']);
        $nombre = trim($_POST['nombre']);
        $email = trim($_POST['email']);
        $id_rol = (int) $_POST['id_rol'];
        
        // Nuevos campos de contraseña
        $password = trim($_POST['password'] ?? '');
        $password_confirm = trim($_POST['password_confirm'] ?? '');

        if (empty($cedula) || empty($nombre) || empty($email)) {
            $_SESSION['error_edicion'] = "Todos los datos básicos son obligatorios.";
            header("Location: editar-usuario?cedula=" . urlencode($_POST['cedula_original']));
            exit;
        }

        $hashSeguro = null;
        if (!empty($password)) {
            if ($password !== $password_confirm) {
                $_SESSION['error_edicion'] = "Las nuevas contraseñas no coinciden.";
                header("Location: editar-usuario?cedula=" . urlencode($_POST['cedula_original']));
                exit;
            }
            $hashSeguro = password_hash($password, PASSWORD_BCRYPT);
        }

        // Pasamos el hashSeguro al modelo (será null si no se llenaron los campos)
        $this->adminModel->actualizarUsuario($id, $cedula, $nombre, $email, $id_rol, $hashSeguro);
        
        header("Location: gestor-usuarios?cedula=" . urlencode($cedula));
        exit;
    }

    // Enciende o apaga la cuenta
    public function alternarEstado() {
        Auth::requierePrivilegioMinimo(2);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) $_POST['usuario_id'];
            $cedula = $_POST['cedula'];
            $estadoActual = $_POST['estado_actual'] === '1'; // true si estaba activo
            
            // Verificación de seguridad
            if ($id === $_SESSION['usuario_id']) {
                header("Location: gestor-usuarios?cedula=" . urlencode($cedula));
                exit;
            }

            // Invertimos el estado
            $this->adminModel->cambiarEstadoActivo($id, !$estadoActual);
            header("Location: gestor-usuarios?cedula=" . urlencode($cedula));
            exit;
        }
    }
}