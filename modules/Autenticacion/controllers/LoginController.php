<?php
// modules/Autenticacion/controllers/LoginController.php
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once CORE_PATH . 'Security/Auth.php';

class LoginController {
    
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }

    // Prepara los datos (si hubiera) y permite que el Kernel cargue la vista
    // ... parte superior del controlador intacta ...

    public function mostrarFormulario() {
        if (Auth::check()) {
            header("Location: perfil");
            exit; 
        }
        
        $error = $_SESSION['error_login'] ?? null;
        $exito = $_SESSION['exito_registro'] ?? null; // NUEVA LÍNEA
        
        unset($_SESSION['error_login'], $_SESSION['exito_registro']);
        
        return ['error' => $error, 'exito' => $exito]; // NUEVA LÍNEA
    }
    // Método para la pantalla de recuperar contraseña
    public function mostrarRecuperar() {
        // Si ya está logueado, no tiene sentido que recupere clave
        if (Auth::check()) {
            header("Location: perfil");
            exit;
        }
        return [];
    }
    
    
    public function procesar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return false;

        $cedula = trim($_POST['cedula'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // 1. Probar la Base de Datos
        try {
            $usuario = $this->usuarioModel->intentarAutenticacion($cedula);
        } catch (Exception $e) {
            return [
                'es_error' => true,
                'mensaje'  => 'Falla de conexión: ' . $e->getMessage(),
                'destino'  => 'login'
            ];
        }

        // 2. Probar si el usuario existe
        if (!$usuario) {
            return [
                'es_error' => true,
                'mensaje'  => "No se encontró ninguna cuenta activa con la cédula {$cedula}.",
                'destino'  => 'login'
            ];
        }

        // 3. Probar la contraseña
        if (!password_verify($password, $usuario['contrasena'])) {
            return [
                'es_error' => true,
                'mensaje'  => 'La contraseña ingresada es incorrecta.',
                'destino'  => 'login'
            ];
        }

        // 4. Si todo está perfecto, crear sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre_usuario'] = $usuario['nombre_completo'];
        $_SESSION['rol_nombre'] = $usuario['nombre_rol'];
        $_SESSION['nivel_privilegio'] = (int) $usuario['nivel_privilegio'];

        try {
            $this->usuarioModel->registrarAcceso($usuario['id']);
        } catch (Exception $e) {
            // Si falla la auditoría, no detenemos el login, solo seguimos adelante
        }

        // ÉXITO: Mandamos los datos para la pantalla de bienvenida (anillo de carga)
        return [
            'es_error'       => false,
            'nombre_usuario' => $usuario['nombre_completo'],
            'rol_nombre'     => $usuario['nombre_rol'],
            'destino'        => 'perfil'
        ];
    }

    public function cerrarSesion() {
        // 1. Aseguramos que PHP sepa qué sesión estamos intentando destruir
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // 2. Vaciamos las variables de la memoria RAM
        $_SESSION = [];
        
        // 3. Destruimos el archivo físico de la sesión en el servidor
        session_destroy();
        
        // 4. Redirigimos al usuario a la pantalla de login (ruta relativa segura)
        header("Location: login");
        exit; // Vital para detener cualquier otro renderizado
    }
    // Carga la vista del formulario de registro
    public function mostrarRegistro() {
        if (Auth::check()) {
            header("Location: perfil");
            exit;
        }
        $error = $_SESSION['error_registro'] ?? null;
        unset($_SESSION['error_registro']);
        
        return ['error' => $error];
    }

    // Lógica pesada de creación de cuenta
    public function procesarRegistro() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return false;

        $cedula = trim($_POST['cedula'] ?? '');
        $nombre = trim($_POST['nombre'] ?? '');
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password'] ?? '');
        $password_confirm = trim($_POST['password_confirm'] ?? '');

        // 1. Validar campos vacíos
        if (empty($cedula) || empty($nombre) || empty($email) || empty($password)) {
            $_SESSION['error_registro'] = 'Todos los campos son obligatorios.';
            header("Location: registro");
            exit;
        }

        // 2. Validar que las contraseñas coincidan
        if ($password !== $password_confirm) {
            $_SESSION['error_registro'] = 'Las contraseñas no coinciden.';
            header("Location: registro");
            exit;
        }

        // 3. Validar duplicados en la BD
        if ($this->usuarioModel->existeUsuario($cedula, $email)) {
            $_SESSION['error_registro'] = 'La cédula o el correo ya están registrados en el sistema.';
            header("Location: registro");
            exit;
        }

        // 4. Encriptar contraseña y guardar
        $hashSeguro = password_hash($password, PASSWORD_BCRYPT);
        $creado = $this->usuarioModel->registrarUsuario($cedula, $nombre, $email, $hashSeguro);

        if ($creado) {
            // Mandamos mensaje de éxito a la pantalla de login
            $_SESSION['exito_registro'] = 'Cuenta creada exitosamente. Ya puede iniciar sesión.';
            header("Location: login");
            exit;
        } else {
            $_SESSION['error_registro'] = 'Ocurrió un error interno al crear la cuenta.';
            header("Location: registro");
            exit;
        }
    }
}