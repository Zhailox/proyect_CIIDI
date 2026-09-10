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
            'profesores' => $profesores,
            'todosLosUsuarios' => $this->adminModel->obtenerTodosLosUsuarios(),
            'matrizRBAC' => $this->obtenerMatrizRBAC(),
            'roles' => $this->adminModel->obtenerRoles()
        ];
    }

    private function obtenerMatrizRBAC(): array {
        $archivo = CORE_PATH . '../storage/rbac_matrix.json';
        if (file_exists($archivo)) {
            return json_decode(file_get_contents($archivo), true) ?: [];
        }
        return [
            'Estudiante' => ['crear' => true, 'editar' => false, 'eliminar' => false, 'auditar' => false],
            'Profesor'   => ['crear' => true, 'editar' => true, 'eliminar' => false, 'auditar' => true],
            'Bibliotecario' => ['crear' => true, 'editar' => true, 'eliminar' => true, 'auditar' => true]
        ];
    }

    public function guardarMatrizRBAC() {
        Auth::requierePrivilegioMinimo(3);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $matriz = $_POST['matrix'] ?? [];
            $archivo = CORE_PATH . '../storage/rbac_matrix.json';
            
            $directorio = dirname($archivo);
            if (!is_dir($directorio)) {
                mkdir($directorio, 0777, true);
            }

            file_put_contents($archivo, json_encode($matriz, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_gestor_exito'] = "Matriz de permisos RBAC actualizada correctamente.";
            header("Location: gestor-usuarios");
            exit;
        }
    }

    public function actualizarRol() {
        Auth::requierePrivilegioMinimo(3);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rolId = (int)($_POST['rol_id'] ?? 0);
            $nombreAnterior = trim($_POST['nombre_anterior'] ?? '');
            $nuevoNombre = trim($_POST['nuevo_nombre'] ?? '');

            if ($rolId > 0 && !empty($nuevoNombre) && $nombreAnterior !== $nuevoNombre) {
                // 1. Actualizamos el nombre en la base de datos PostgreSQL
                $this->adminModel->actualizarNombreRol($rolId, $nuevoNombre);

                // 2. Actualización en cascada en la matriz RBAC (storage/rbac_matrix.json)
                $archivo = CORE_PATH . '../storage/rbac_matrix.json';
                if (file_exists($archivo)) {
                    $matriz = json_decode(file_get_contents($archivo), true) ?: [];
                    if (isset($matriz[$nombreAnterior])) {
                        $permisosPrevios = $matriz[$nombreAnterior];
                        unset($matriz[$nombreAnterior]);
                        $matriz[$nuevoNombre] = $permisosPrevios;
                        file_put_contents($archivo, json_encode($matriz, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                    }
                }

                // 3. Si el usuario actual posee este rol, refrescar la variable de sesión en vivo
                if (isset($_SESSION['rol_nombre']) && $_SESSION['rol_nombre'] === $nombreAnterior) {
                    $_SESSION['rol_nombre'] = $nuevoNombre;
                }

                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_exito'] = "Rol '{$nombreAnterior}' renombrado exitosamente a '{$nuevoNombre}' en BD y Matriz RBAC.";
            }

            header("Location: gestor-usuarios");
            exit;
        }
    }

    public function revocarSesion() {
        Auth::requierePrivilegioMinimo(3);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioIdAExpulsar = (int)($_POST['usuario_id'] ?? 0);
            $miUsuarioId = (int)($_SESSION['usuario_id'] ?? 0);
            
            // Protección: Imposible expulsarse a uno mismo
            if ($usuarioIdAExpulsar > 0 && $usuarioIdAExpulsar !== $miUsuarioId) {
                $archivo = CORE_PATH . '../storage/revoked_sessions.json';
                $revogadas = file_exists($archivo) ? (json_decode(file_get_contents($archivo), true) ?: []) : [];
                
                $revogadas[(string)$usuarioIdAExpulsar] = true;
                file_put_contents($archivo, json_encode($revogadas, JSON_PRETTY_PRINT));

                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_exito'] = "Sesión revocada exitosamente para el usuario #{$usuarioIdAExpulsar}.";
            } else {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_error'] = "No puedes revocar tu propia sesión activa desde esta acción.";
            }

            header("Location: gestor-usuarios");
            exit;
        }
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