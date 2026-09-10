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
            $rawMatrix = $_POST['matrix'] ?? [];
            $matrizProcesada = [];

            foreach ($rawMatrix as $rol => $permisos) {
                if (is_array($permisos)) {
                    foreach ($permisos as $accion => $val) {
                        $matrizProcesada[$rol][$accion] = ($val === '1' || $val === 1 || $val === true);
                    }
                }
            }

            $archivo = CORE_PATH . '../storage/rbac_matrix.json';
            
            $directorio = dirname($archivo);
            if (!is_dir($directorio)) {
                mkdir($directorio, 0777, true);
            }

            file_put_contents($archivo, json_encode($matrizProcesada, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            AuditLogger::registrar('WARNING', 'SuperAdmin', 'Modificar Matriz RBAC', 'Se actualizaron los permisos dinámicos del sistema por rol.');

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

                AuditLogger::registrar('INFO', 'SuperAdmin', 'Renombrar Rol', "Rol ID #{$rolId} renombrado de '{$nombreAnterior}' a '{$nuevoNombre}' con actualización en cascada.");

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

                AuditLogger::registrar('CRITICAL', 'SuperAdmin', 'Revocar Sesión Remota', "Sesión expulsada para el Usuario ID #{$usuarioIdAExpulsar}");

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
        
        $detallesEdicion = "Datos actualizados para el Usuario C.I. {$cedula} ({$nombre})." . ($hashSeguro ? " Se forzó cambio de contraseña." : "");
        AuditLogger::registrar('INFO', 'SuperAdmin', 'Editar Usuario', $detallesEdicion);

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
            
            $accionAudit = !$estadoActual ? 'Restaurar Cuenta Usuario' : 'Suspender Cuenta Usuario';
            $sevAudit = !$estadoActual ? 'INFO' : 'WARNING';
            AuditLogger::registrar($sevAudit, 'SuperAdmin', $accionAudit, "Estado de la cuenta del Usuario C.I. {$cedula} cambiado a: " . (!$estadoActual ? 'Activo' : 'Suspendido'));

            header("Location: gestor-usuarios?cedula=" . urlencode($cedula));
            exit;
        }
    }

    // Registrar nuevo usuario desde el Gestor de Usuarios
    public function crearUsuarioAction() {
        Auth::requierePrivilegioMinimo(3);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cedula = trim($_POST['cedula'] ?? '');
            $nombre = trim($_POST['nombre'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $id_rol = (int)($_POST['id_rol'] ?? 0);
            $clave = trim($_POST['password'] ?? '');

            if (empty($cedula) || empty($nombre) || empty($email) || empty($clave) || $id_rol <= 0) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_error'] = "Todos los campos son obligatorios para crear el usuario.";
                header("Location: gestor-usuarios");
                exit;
            }

            // Verificar duplicado de cédula
            $existe = $this->adminModel->buscarPorCedula($cedula);
            if ($existe) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_error'] = "Ya existe un usuario registrado con la cédula C.I. {$cedula}.";
                header("Location: gestor-usuarios");
                exit;
            }

            $hashClave = password_hash($clave, PASSWORD_BCRYPT);
            $exito = $this->adminModel->crearUsuario($cedula, $nombre, $email, $id_rol, $hashClave);

            if (session_status() === PHP_SESSION_NONE) session_start();
            if ($exito) {
                AuditLogger::registrar('INFO', 'SuperAdmin', 'Crear Usuario', "Nuevo usuario registrado: {$nombre} (C.I: {$cedula})");
                $_SESSION['mensaje_gestor_exito'] = "Usuario '{$nombre}' creado exitosamente en el sistema.";
            } else {
                $_SESSION['mensaje_gestor_error'] = "Error inesperado al crear el usuario.";
            }

            header("Location: gestor-usuarios");
            exit;
        }
    }

    // Restablecimiento rápido de contraseña por el Administrador
    public function resetClaveRapido() {
        Auth::requierePrivilegioMinimo(3);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioId = (int)($_POST['usuario_id'] ?? 0);
            $cedula = trim($_POST['cedula'] ?? '');
            $claveNueva = trim($_POST['nueva_clave'] ?? 'Temporal2026!');

            if ($usuarioId <= 0 || empty($claveNueva)) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_error'] = "Datos insuficientes para restablecer la contraseña.";
                header("Location: gestor-usuarios");
                exit;
            }

            $hashClave = password_hash($claveNueva, PASSWORD_BCRYPT);
            $exito = $this->adminModel->forzarRestablecerClave($usuarioId, $hashClave);

            if (session_status() === PHP_SESSION_NONE) session_start();
            if ($exito) {
                AuditLogger::registrar('WARNING', 'SuperAdmin', 'Restablecer Clave Rápida', "Contraseña restablecida para el Usuario C.I. {$cedula}");
                $_SESSION['mensaje_gestor_exito'] = "Contraseña de la cuenta C.I. {$cedula} restablecida a: '{$claveNueva}'.";
            } else {
                $_SESSION['mensaje_gestor_error'] = "Error al restablecer la contraseña.";
            }

            header("Location: gestor-usuarios");
            exit;
        }
    }
}