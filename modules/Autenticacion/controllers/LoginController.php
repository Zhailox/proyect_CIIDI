<?php
// modules/Autenticacion/controllers/LoginController.php
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once CORE_PATH . 'Security/Auth.php';

class LoginController {
    
    private $usuarioModel;

    private function getUsuarioModel() {
        if ($this->usuarioModel === null) {
            $this->usuarioModel = new UsuarioModel();
        }
        return $this->usuarioModel;
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

        require_once CORE_PATH . 'Security/RateLimiter.php';

        $ip = RateLimiter::obtenerIPCliente();
        $checkWAF = RateLimiter::estaBloqueada($ip);

        if ($checkWAF['bloqueada']) {
            AuditLogger::registrar('CRITICAL', 'Autenticacion', 'Acceso Rechazado por WAF', "Intento de login bloqueado para IP: {$ip}. Razón: {$checkWAF['razon']}");
            return [
                'es_error' => true,
                'mensaje'  => 'Seguridad WAF: ' . $checkWAF['razon'],
                'destino'  => 'login'
            ];
        }

        $cedula = trim($_POST['cedula'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // 1. Probar la Base de Datos
        try {
            $usuario = $this->getUsuarioModel()->intentarAutenticacion($cedula);
        } catch (Exception $e) {
            return [
                'es_error' => true,
                'mensaje'  => 'Falla de conexión: ' . $e->getMessage(),
                'destino'  => 'login'
            ];
        }

        // 2. Probar si el usuario existe y si está activo
        if (!$usuario) {
            RateLimiter::registrarIntentoFallido($ip, $cedula);
            AuditLogger::registrar('WARNING', 'Autenticacion', 'Intento de Acceso Fallido', "Intento de inicio de sesión con cédula inexistente: {$cedula}");
            return ['es_error' => true, 'mensaje' => "No se encontró ninguna cuenta con la cédula {$cedula}.", 'destino' => 'login'];
        }

        if ($usuario['activo'] === false) {
            RateLimiter::registrarIntentoFallido($ip, $cedula);
            AuditLogger::registrar('WARNING', 'Autenticacion', 'Acceso Denegado', "Intento de inicio de sesión en cuenta suspendida C.I.: {$cedula}");
            return ['es_error' => true, 'mensaje' => 'Esta cuenta se encuentra actualmente suspendida por administración.', 'destino' => 'login'];
        }

        // 3. Probar la contraseña
        if (!password_verify($password, $usuario['contrasena'])) {
            RateLimiter::registrarIntentoFallido($ip, $cedula);
            AuditLogger::registrar('WARNING', 'Autenticacion', 'Contraseña Incorrecta', "Intento de inicio de sesión fallido por contraseña para el usuario C.I.: {$cedula}");
            return [
                'es_error' => true,
                'mensaje'  => 'La contraseña ingresada es incorrecta.',
                'destino'  => 'login'
            ];
        }

        // Éxito: Limpiamos los intentos acumulados de la IP
        RateLimiter::limpiarIntentosExitosa($ip);
        // Verificar que el server no esté en mantenimiento
        $archivoMant = __DIR__ . '/../../../storage/maintenance.json';
        if (file_exists($archivoMant)) {
            $data = json_decode(file_get_contents($archivoMant), true);
            if (isset($data['activo']) && $data['activo'] === true) {
                $nivelUsuario = (int) $usuario['nivel_privilegio'];
                if ($nivelUsuario == 0) {
                    // Error: solo administradores pueden acceder durante mantenimiento
                    $_SESSION['error_login'] = 'El sistema está en mantenimiento. Solo administradores pueden acceder.';
                    header("Location: login");
                    exit;
                }
            }
        }

        // 4. Si todo está perfecto, crear sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = (int)$usuario['id'];
        $_SESSION['nombre_usuario'] = $usuario['nombre_completo'];
        $_SESSION['rol_nombre'] = $usuario['nombre_rol'];
        $_SESSION['nivel_privilegio'] = (int) $usuario['nivel_privilegio'];

        // Limpiamos cualquier bloqueo de sesión previa si existía en revoked_sessions.json
        $archivo_sesiones = __DIR__ . '/../../../storage/revoked_sessions.json';
        if (file_exists($archivo_sesiones)) {
            $revogadas = json_decode(file_get_contents($archivo_sesiones), true) ?: [];
            if (isset($revogadas[$usuario['id']])) {
                unset($revogadas[$usuario['id']]);
                file_put_contents($archivo_sesiones, json_encode($revogadas, JSON_PRETTY_PRINT));
            }
        }

        try {
            $this->usuarioModel->registrarAcceso($usuario['id']);
        } catch (Exception $e) {
            // Si falla la auditoría de BD, no detenemos el login
        }

        AuditLogger::registrar('INFO', 'Autenticacion', 'Inicio de Sesión Exitoso', "El usuario {$usuario['nombre_completo']} ({$usuario['nombre_rol']}) ha iniciado sesión.");

        // ÉXITO: Mandamos los datos para la pantalla de bienvenida (anillo de carga)
        return [
            'es_error'       => false,
            'nombre_usuario' => $usuario['nombre_completo'],
            'rol_nombre'     => $usuario['nombre_rol'],
            'destino'        => 'perfil'
        ];
    }

    public function cerrarSesion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['nombre_usuario'])) {
            AuditLogger::registrar('INFO', 'Autenticacion', 'Cierre de Sesión', "El usuario {$_SESSION['nombre_usuario']} ha cerrado su sesión.");
        }

        $_SESSION = [];
        session_destroy();
        
        header("Location: login");
        exit;
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
            return [
                'es_error' => true,
                'mensaje'  => 'Todos los campos son obligatorios para el registro.',
                'destino'  => 'registro'
            ];
        }

        // 2. Validar que las contraseñas coincidan
        if ($password !== $password_confirm) {
            return [
                'es_error' => true,
                'mensaje'  => 'Las contraseñas no coinciden. Verifique e intente nuevamente.',
                'destino'  => 'registro'
            ];
        }

        // 3. Validar duplicados en la BD
        if ($this->usuarioModel->existeUsuario($cedula, $email)) {
            return [
                'es_error' => true,
                'mensaje'  => 'La cédula o el correo ya están registrados en nuestra base de datos.',
                'destino'  => 'registro'
            ];
        }

        // 4. Encriptar contraseña y guardar
        $hashSeguro = password_hash($password, PASSWORD_BCRYPT);
        $creado = $this->usuarioModel->registrarUsuario($cedula, $nombre, $email, $hashSeguro);

        if ($creado) {
            // ÉXITO: Mandamos los datos para la pantalla de bienvenida y lo enviamos al login
            return [
                'es_error'       => false,
                'nombre_usuario' => $nombre,
                'rol_nombre'     => 'Cuenta Creada Exitosamente',
                'destino'        => 'login'
            ];
        } else {
            return [
                'es_error' => true,
                'mensaje'  => 'Ocurrió un error interno en el servidor al intentar crear la cuenta.',
                'destino'  => 'registro'
            ];
        }
    }
}