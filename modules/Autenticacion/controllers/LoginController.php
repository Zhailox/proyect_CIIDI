<?php
// modules/Autenticacion/controllers/LoginController.php
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once CORE_PATH . 'Security/Auth.php';
require_once CORE_PATH . 'Security/CaptchaService.php';
require_once CORE_PATH . 'Services/MailService.php';

class LoginController {
    
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }

    public function generarCaptchaImagen() {
        CaptchaService::renderImagenCaptcha();
        return false;
    }

    public function mostrarFormulario() {
        if (Auth::check()) {
            header("Location: perfil");
            exit; 
        }
        
        $error = $_SESSION['error_login'] ?? null;
        $exito = $_SESSION['exito_registro'] ?? null;
        
        unset($_SESSION['error_login'], $_SESSION['exito_registro']);
        
        return ['error' => $error, 'exito' => $exito];
    }

    public function mostrarRecuperar() {
        if (Auth::check()) {
            header("Location: perfil");
            exit;
        }
        $error = $_SESSION['error_recuperar'] ?? null;
        $exito = $_SESSION['exito_recuperar'] ?? null;
        unset($_SESSION['error_recuperar'], $_SESSION['exito_recuperar']);
        return ['error' => $error, 'exito' => $exito];
    }
    
    public function procesarRecuperacion() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        
        $metodo = $_POST['metodo_recuperacion'] ?? '';
        $dato = trim($_POST['dato_recuperacion'] ?? '');
        
        // Validación de captcha
        $verifCaptcha = CaptchaService::validarPeticion($_POST);
        if (!$verifCaptcha['valido']) {
            $_SESSION['error_recuperar'] = $verifCaptcha['mensaje'];
            header("Location: recuperar-cuenta");
            exit;
        }
        
        $usuario = null;
        if ($metodo === 'cedula') {
            $usuario = $this->usuarioModel->findByCedula($dato);
        } else {
            $usuario = $this->usuarioModel->findByEmail($dato);
        }
        
        if ($usuario && !empty($usuario['email'])) {
            // Generar Token Firmado Criptográficamente (SHA-256) con expiración de 15 minutos
            $rawToken = bin2hex(random_bytes(32));
            $tokenHash = hash('sha256', $rawToken);

            $this->usuarioModel->guardarTokenRecuperacionSHA256($usuario['email'], $tokenHash);

            $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
            $baseUrl = "{$protocolo}://{$host}" . ($baseDir && $baseDir !== '/' ? $baseDir : '');
            $enlaceSeguro = "{$baseUrl}/restablecer-clave?token={$rawToken}";

            $resMail = MailService::enviarEvento('autenticacion.recuperar_clave', $usuario['email'], [
                'NOMBRE_USUARIO'    => $usuario['nombre_completo'],
                'ENLACE_ACCION'     => $enlaceSeguro,
                'TIEMPO_EXPIRACION' => '15 minutos'
            ], $usuario['nombre_completo']);

            if ($resMail['exito']) {
                $_SESSION['exito_recuperar'] = "Hemos enviado las instrucciones y el enlace seguro a su correo: {$usuario['email']}. Revisa tu bandeja de entrada o Spam.";
            } else {
                $_SESSION['exito_recuperar'] = "Se generó el enlace de recuperación (Servidor SMTP desconfigurado o error): {$enlaceSeguro}";
            }

            header("Location: recuperar-cuenta");
            exit;
        } else {
            $_SESSION['error_recuperar'] = "No se encontró ningún usuario con ese dato, o no tiene correo asociado.";
            header("Location: recuperar-cuenta");
            exit;
        }
    }

    public function mostrarRestablecerClave() {
        if (Auth::check()) {
            header("Location: perfil");
            exit;
        }

        $rawToken = trim($_GET['token'] ?? '');
        if (empty($rawToken)) {
            $_SESSION['error_recuperar'] = "El token de recuperación no fue proporcionado.";
            header("Location: recuperar-cuenta");
            exit;
        }

        $tokenHash = hash('sha256', $rawToken);
        $tokenValido = $this->usuarioModel->obtenerTokenRecuperacionValido($tokenHash);

        if (!$tokenValido) {
            $_SESSION['error_recuperar'] = "El enlace de recuperación es inválido, ya fue utilizado o ha expirado (límite 15 minutos).";
            header("Location: recuperar-cuenta");
            exit;
        }

        $error = $_SESSION['error_restablecer'] ?? null;
        unset($_SESSION['error_restablecer']);

        return [
            'token' => $rawToken,
            'nombreUsuario' => $tokenValido['nombre_completo'],
            'error' => $error
        ];
    }

    public function procesarRestablecerClave() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $rawToken = trim($_POST['token'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $passwordConfirm = trim($_POST['password_confirm'] ?? '');

        if (empty($rawToken) || empty($password)) {
            $_SESSION['error_restablecer'] = "Todos los campos son obligatorios.";
            header("Location: restablecer-clave?token=" . urlencode($rawToken));
            exit;
        }

        if (strlen($password) < 8) {
            $_SESSION['error_restablecer'] = "La nueva contraseña debe tener al menos 8 caracteres.";
            header("Location: restablecer-clave?token=" . urlencode($rawToken));
            exit;
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['error_restablecer'] = "Las contraseñas no coinciden.";
            header("Location: restablecer-clave?token=" . urlencode($rawToken));
            exit;
        }

        $tokenHash = hash('sha256', $rawToken);
        $hashNueva = password_hash($password, PASSWORD_BCRYPT);

        if ($this->usuarioModel->restablecerPasswordConToken($tokenHash, $hashNueva)) {
            $_SESSION['exito_registro'] = "Su contraseña se ha actualizado correctamente. Ya puede acceder con sus nuevas credenciales.";
            header("Location: login");
            exit;
        } else {
            $_SESSION['error_recuperar'] = "No se pudo actualizar la contraseña. El enlace de token ha expirado o ya fue utilizado.";
            header("Location: recuperar-cuenta");
            exit;
        }
    }

    public function activarCuenta() {
        $tokenActivacion = trim($_GET['token'] ?? '');
        if (empty($tokenActivacion)) {
            $_SESSION['error_login'] = "Código de activación no proporcionado.";
            header("Location: login");
            exit;
        }

        if ($this->usuarioModel->activarCuentaPorToken($tokenActivacion)) {
            $_SESSION['exito_registro'] = "¡Su cuenta ha sido activada exitosamente! Ya puede iniciar sesión.";
        } else {
            $_SESSION['error_login'] = "El enlace de activación es inválido o su cuenta ya fue activada previamente.";
        }

        header("Location: login");
        exit;
    }
    
    public function mostrarIngresarCodigo() {
        if (Auth::check()) {
            header("Location: perfil");
            exit;
        }
        $uid = $_GET['uid'] ?? '';
        if (!$uid) {
            header("Location: ?ruta=recuperar-cuenta");
            exit;
        }
        
        $error = $_SESSION['error_codigo'] ?? null;
        unset($_SESSION['error_codigo']);
        return ['error' => $error, 'uid' => $uid];
    }

    public function procesarCodigo() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        
        $uid = (int)($_POST['uid'] ?? 0);
        $codigo = trim($_POST['codigo'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $password_conf = trim($_POST['password_conf'] ?? '');
        
        if ($password !== $password_conf) {
            $_SESSION['error_codigo'] = "Las contraseñas no coinciden.";
            header("Location: ?ruta=ingresar-codigo&uid=" . $uid);
            exit;
        }
        
        if ($this->usuarioModel->verificarToken($uid, $codigo)) {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $this->usuarioModel->actualizarPassword($uid, $hash);
            
            $_SESSION['exito_registro'] = "Tu contraseña ha sido actualizada con éxito. Ya puedes iniciar sesión.";
            header("Location: login");
            exit;
        } else {
            $_SESSION['error_codigo'] = "El código de seguridad es inválido o ha expirado.";
            header("Location: ?ruta=ingresar-codigo&uid=" . $uid);
            exit;
        }
    }
    
    
    public function procesar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return false;

        // Validación de seguridad Anti-Bot (Honeypot + Tiempo Humano + Captcha)
        $verifCaptcha = CaptchaService::validarPeticion($_POST);
        if (!$verifCaptcha['valido']) {
            return ['es_error' => true, 'mensaje' => $verifCaptcha['mensaje'], 'destino' => 'login'];
        }

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

        // 2. Probar si el usuario existe y si está activo
        if (!$usuario) {
            return ['es_error' => true, 'mensaje' => "No se encontró ninguna cuenta con la cédula {$cedula}.", 'destino' => 'login'];
        }

        if ($usuario['activo'] === false) {
            return ['es_error' => true, 'mensaje' => 'Esta cuenta se encuentra actualmente suspendida por administración.', 'destino' => 'login'];
        }

        // 3. Probar la contraseña
        if (!password_verify($password, $usuario['contrasena'])) {
            return [
                'es_error' => true,
                'mensaje'  => 'La contraseña ingresada es incorrecta.',
                'destino'  => 'login'
            ];
        }
        // Verificar que el server no esté en mantenimiento
        $archivoMant = __DIR__ . '/../../../storage/maintenance.json';
        if (file_exists($archivoMant)) {
            $data = json_decode(file_get_contents($archivoMant), true);
            if (isset($data['activo']) && $data['activo'] === true) {
                $nivelUsuario = (int) $usuario['nivel_privilegio'];
                if ($nivelUsuario < 3) {
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

        // Validación de seguridad Anti-Bot (Honeypot + Tiempo Humano + Captcha)
        $verifCaptcha = CaptchaService::validarPeticion($_POST);
        if (!$verifCaptcha['valido']) {
            return ['es_error' => true, 'mensaje' => $verifCaptcha['mensaje'], 'destino' => 'registro'];
        }

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

        // 2.1. Validar fortaleza de la contraseña en el backend (mínimo 8 caracteres, 1 mayúscula, 1 número y 1 especial)
        $hasLength  = strlen($password) >= 8;
        $hasUpper   = preg_match('/[A-Z]/', $password);
        $hasNumber  = preg_match('/[0-9]/', $password);
        $hasSpecial = preg_match('/[@$!%*?&._\-\#\^\(\)\{\}\[\]]/', $password);

        if (!$hasLength || !$hasUpper || !$hasNumber || !$hasSpecial) {
            return [
                'es_error' => true,
                'mensaje'  => 'La contraseña no cumple con los requisitos mínimos de seguridad (8+ caracteres, 1 mayúscula, 1 número y 1 carácter especial).',
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

        // 4. Registrar usuario directamente activo y encriptar contraseña
        $hashSeguro = password_hash($password, PASSWORD_BCRYPT);
        $creado = $this->usuarioModel->registrarUsuario($cedula, $nombre, $email, $hashSeguro, null);

        if ($creado) {
            $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
            $baseUrl = "{$protocolo}://{$host}" . ($baseDir && $baseDir !== '/' ? $baseDir : '');
            $enlaceAcceso = "{$baseUrl}/login";

            MailService::enviarEvento('autenticacion.bienvenida', $email, [
                'NOMBRE_USUARIO' => $nombre,
                'CEDULA_USUARIO' => $cedula,
                'ENLACE_ACCESO'  => $enlaceAcceso
            ], $nombre);

            $_SESSION['exito_registro'] = "¡Cuenta creada con éxito! Se ha enviado un mensaje de bienvenida a su correo. Ya puede iniciar sesión.";

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

    /**
     * Valida el token de invitación enviado al profesor y muestra el formulario privado de activación.
     */
    public function mostrarCompletarRegistroProfesor() {
        $rawToken = trim($_GET['token'] ?? '');
        if (empty($rawToken)) {
            $_SESSION['error_login'] = "El token de invitación es inválido o no fue proporcionado.";
            header("Location: login");
            exit;
        }

        $tokenHash = hash('sha256', $rawToken);
        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT u.id, u.nombre_completo, u.email, u.cedula FROM usuarios u WHERE u.activation_token = ? AND u.activo = false");
        $stmt->execute([$tokenHash]);
        $profesor = $stmt->fetch();

        if (!$profesor) {
            $_SESSION['error_login'] = "El token de invitación ha expirado, ya fue utilizado o es inválido.";
            header("Location: login");
            exit;
        }

        return [
            'token'    => $rawToken,
            'profesor' => $profesor,
            'error'    => $_SESSION['error_completar_registro'] ?? null
        ];
    }

    /**
     * Recibe la nueva contraseña definida por el profesor, activa su cuenta y elimina el token.
     */
    public function procesarCompletarRegistroProfesor() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: login");
            exit;
        }

        $rawToken = trim($_POST['token'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $passwordConfirm = trim($_POST['password_confirm'] ?? '');

        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($rawToken)) {
            $_SESSION['error_login'] = "Token inválido.";
            header("Location: login");
            exit;
        }

        if (empty($password) || empty($passwordConfirm)) {
            $_SESSION['error_completar_registro'] = "Debe ingresar y confirmar su nueva contraseña.";
            header("Location: completar-registro?token=" . urlencode($rawToken));
            exit;
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['error_completar_registro'] = "Las contraseñas ingresadas no coinciden.";
            header("Location: completar-registro?token=" . urlencode($rawToken));
            exit;
        }

        // Requisitos mínimos de seguridad (8+ caracteres, 1 mayúscula, 1 número, 1 símbolo)
        if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[@$!%*?&._\-\#\^\(\)\{\}\[\]]/', $password)) {
            $_SESSION['error_completar_registro'] = "La contraseña debe tener al menos 8 caracteres, 1 mayúscula, 1 número y 1 carácter especial.";
            header("Location: completar-registro?token=" . urlencode($rawToken));
            exit;
        }

        $tokenHash = hash('sha256', $rawToken);
        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT id, nombre_completo, email, cedula FROM usuarios WHERE activation_token = ? AND activo = false");
        $stmt->execute([$tokenHash]);
        $profesor = $stmt->fetch();

        if (!$profesor) {
            $_SESSION['error_login'] = "La invitación ha expirado o ya fue procesada.";
            header("Location: login");
            exit;
        }

        $hashSeguro = password_hash($password, PASSWORD_BCRYPT);
        $stmtUpdate = $db->prepare("UPDATE usuarios SET contrasena = ?, activo = true, email_verified = true, activation_token = NULL WHERE id = ?");
        $exito = $stmtUpdate->execute([$hashSeguro, $profesor['id']]);

        if ($exito) {
            AuditLogger::registrar('INFO', 'Autenticacion', 'Activación Docente Exitoso', "El profesor {$profesor['nombre_completo']} (C.I: {$profesor['cedula']}) activo su cuenta mediante token.");

            $_SESSION['exito_login'] = "¡Cuenta de profesor activada exitosamente! Ya puede iniciar sesión con sus credenciales.";
            header("Location: login");
            exit;
        } else {
            $_SESSION['error_completar_registro'] = "Error inesperado al activar la cuenta.";
            header("Location: completar-registro?token=" . urlencode($rawToken));
            exit;
        }
    }
}