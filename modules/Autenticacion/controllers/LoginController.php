<?php
// modules/Autenticacion/controllers/LoginController.php
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once CORE_PATH . 'Security/Auth.php';
require_once CORE_PATH . 'Security/AuditLogger.php';
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
            $esSuperAdmin = isset($_SESSION['nivel_privilegio']) && (int)$_SESSION['nivel_privilegio'] === 0;
            header("Location: " . ($esSuperAdmin ? "sudoadmin" : "perfil"));
            exit; 
        }
        
        $error = $_SESSION['error_login'] ?? null;
        $exito = $_SESSION['exito_registro'] ?? $_SESSION['exito_login'] ?? null;
        
        unset($_SESSION['error_login'], $_SESSION['exito_registro'], $_SESSION['exito_login']);
        
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
            ], $usuario['nombre_completo'], false);

            if ($resMail['exito']) {
                AuditLogger::registrar('INFO', 'Autenticacion', 'Recuperación Solicitada', "Solicitud de recuperación generada para '{$usuario['nombre_completo']}' ({$usuario['email']}).");
                $_SESSION['exito_recuperar'] = "Hemos enviado las instrucciones y el enlace seguro a su correo: {$usuario['email']}. Revisa tu bandeja de entrada o Spam.";
            } else {
                AuditLogger::registrar('INFO', 'Autenticacion', 'Recuperación Solicitada', "Enlace directo de recuperación generado para '{$usuario['nombre_completo']}' ({$usuario['email']}).");
                $_SESSION['exito_recuperar'] = "Se generó el enlace de recuperación (Servidor SMTP desconfigurado o error): {$enlaceSeguro}";
            }

            header("Location: recuperar-cuenta");
            exit;
        } else {
            AuditLogger::registrar('WARNING', 'Autenticacion', 'Recuperación Fallida', "Intento de recuperación con dato no encontrado: '{$dato}' (Método: {$metodo}).");
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
            AuditLogger::registrar('INFO', 'Autenticacion', 'Contraseña Restablecida', "Contraseña restablecida exitosamente mediante token de seguridad.");
            $_SESSION['exito_registro'] = "Su contraseña se ha actualizado correctamente. Ya puede acceder con sus nuevas credenciales.";
            header("Location: login");
            exit;
        } else {
            AuditLogger::registrar('WARNING', 'Autenticacion', 'Fallo Restablecer Contraseña', "Intento fallido de restablecimiento de contraseña: Token inválido o expirado.");
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
            AuditLogger::registrar('INFO', 'Autenticacion', 'Cuenta Activada', "Cuenta activada exitosamente mediante token.");
            $_SESSION['exito_registro'] = "¡Su cuenta ha sido activada exitosamente! Ya puede iniciar sesión.";
        } else {
            AuditLogger::registrar('WARNING', 'Autenticacion', 'Fallo Activación Cuenta', "Intento fallido de activación de cuenta con token inválido o ya usado.");
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
            AuditLogger::registrar('INFO', 'Autenticacion', 'Contraseña Actualizada por PIN', "Contraseña actualizada exitosamente con código de seguridad para usuario ID #{$uid}.");
            $_SESSION['exito_registro'] = "Tu contraseña ha sido actualizada con éxito. Ya puedes iniciar sesión.";
            header("Location: login");
            exit;
        } else {
            AuditLogger::registrar('WARNING', 'Autenticacion', 'Código de Seguridad Inválido', "Intento fallido de verificación con código de seguridad para usuario ID #{$uid}.");
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

        // Verificación de Rate Limiting y Bloqueo de Intentos Fallidos
        require_once CORE_PATH . 'Security/RateLimiter.php';
        $estadoBloqueo = RateLimiter::estaBloqueada();
        if ($estadoBloqueo['bloqueada']) {
            return ['es_error' => true, 'mensaje' => $estadoBloqueo['razon'], 'destino' => 'login'];
        }

        $cedula = trim($_POST['cedula'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // 1. Probar la Base de Datos
        try {
            $usuario = $this->usuarioModel->intentarAutenticacion($cedula);
        } catch (Exception $e) {
            AuditLogger::registrar('ERROR', 'Autenticacion', 'Falla Conexión Login', "Error de base de datos al autenticar cédula '{$cedula}': " . $e->getMessage());
            return [
                'es_error' => true,
                'mensaje'  => 'Falla de conexión: ' . $e->getMessage(),
                'destino'  => 'login'
            ];
        }

        // 2. Probar si el usuario existe y si está activo
        if (!$usuario) {
            RateLimiter::registrarIntentoFallido(null, $cedula);
            $maxIntentos = RateLimiter::getMaxIntentos();
            $intentosActuales = RateLimiter::obtenerIntentos()[RateLimiter::obtenerIPCliente()]['intentos'] ?? 1;
            $restantes = max(0, $maxIntentos - $intentosActuales);
            AuditLogger::registrar('WARNING', 'Autenticacion', 'Login Fallido', "Intento de inicio de sesión con cédula no registrada: '{$cedula}'.");
            $aviso = $restantes > 0 ? " (Intentos restantes: {$restantes})" : "";
            return ['es_error' => true, 'mensaje' => "No se encontró ninguna cuenta con la cédula {$cedula}.{$aviso}", 'destino' => 'login'];
        }

        if ($usuario['activo'] === false) {
            AuditLogger::registrar('WARNING', 'Autenticacion', 'Acceso Bloqueado', "Intento de acceso a cuenta suspendida: '{$usuario['nombre_completo']}' (C.I: {$cedula}).");
            return ['es_error' => true, 'mensaje' => 'Esta cuenta se encuentra actualmente suspendida por administración.', 'destino' => 'login'];
        }

        // 3. Probar la contraseña
        if (!password_verify($password, $usuario['contrasena'])) {
            RateLimiter::registrarIntentoFallido(null, $cedula);
            $maxIntentos = RateLimiter::getMaxIntentos();
            $intentosActuales = RateLimiter::obtenerIntentos()[RateLimiter::obtenerIPCliente()]['intentos'] ?? 1;
            $restantes = max(0, $maxIntentos - $intentosActuales);
            AuditLogger::registrar('WARNING', 'Autenticacion', 'Login Fallido', "Contraseña incorrecta para el usuario '{$usuario['nombre_completo']}' (C.I: {$cedula}).");
            $aviso = $restantes > 0 ? " (Intentos restantes: {$restantes})" : " Ha superado el límite de intentos permitidos.";
            return [
                'es_error' => true,
                'mensaje'  => 'La contraseña ingresada es incorrecta.' . $aviso,
                'destino'  => 'login'
            ];
        }

        // Credenciales correctas: limpiar intentos fallidos de esta IP
        RateLimiter::limpiarIntentosExitosa();
        // Verificar que el server no esté en mantenimiento
        $archivoMant = __DIR__ . '/../../../storage/maintenance.json';
        if (file_exists($archivoMant)) {
            $data = json_decode(file_get_contents($archivoMant), true);
            if (isset($data['activo']) && $data['activo'] === true) {
                $nivelUsuario = (int) $usuario['nivel_privilegio'];
                // En la estructura del sistema, niveles <= 2 representan administradores/gestores (0 es SuperAdmin)
                if ($nivelUsuario > 2) {
                    AuditLogger::registrar('WARNING', 'Autenticacion', 'Acceso Denegado Mantenimiento', "Acceso denegado por mantenimiento para '{$usuario['nombre_completo']}' (C.I: {$cedula}).");
                    $_SESSION['error_login'] = 'El sistema está en mantenimiento. Solo administradores pueden acceder.';
                    return ['es_error' => true, 'mensaje' => 'El sistema está en mantenimiento. Solo administradores pueden acceder.', 'destino' => 'login'];
                }
            }
        }

        // 4. Si todo está perfecto, crear sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['login_time'] = time();
        $this->limpiarRevocacionSesion($usuario['id']);
        $_SESSION['usuario_nombre'] = $usuario['nombre_completo'];
        $_SESSION['nombre_usuario'] = $usuario['nombre_completo'];
        $_SESSION['nombre'] = $usuario['nombre_completo'];
        $_SESSION['usuario_cedula'] = $usuario['cedula'] ?? $cedula;
        $_SESSION['usuario_email'] = $usuario['email'] ?? '';
        $_SESSION['rol_nombre'] = $usuario['nombre_rol'];
        $_SESSION['rol'] = $usuario['nombre_rol'];
        $_SESSION['usuario_rol'] = $usuario['nombre_rol'];
        $_SESSION['nivel_privilegio'] = (int) $usuario['nivel_privilegio'];

        try {
            $this->usuarioModel->registrarAcceso($usuario['id']);
        } catch (Exception $e) {
            // Si falla la auditoría, no detenemos el login, solo seguimos adelante
        }

        AuditLogger::registrar('INFO', 'Autenticacion', 'Inicio de Sesión', "Acceso exitoso al sistema de '{$usuario['nombre_completo']}' (C.I: {$cedula}, Rol: {$usuario['nombre_rol']}).");

        $esSuperAdmin = (int)$usuario['nivel_privilegio'] === 0;

        // ÉXITO: Mandamos los datos para la pantalla de bienvenida (anillo de carga)
        return [
            'es_error'       => false,
            'nombre_usuario' => $usuario['nombre_completo'],
            'rol_nombre'     => $usuario['nombre_rol'],
            'destino'        => $esSuperAdmin ? 'sudoadmin' : 'perfil'
        ];
    }

    public function cerrarSesion() {
        // 1. Aseguramos que PHP sepa qué sesión estamos intentando destruir
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $nombreUser = $_SESSION['usuario_nombre'] ?? 'Usuario';
        $idUser = $_SESSION['usuario_id'] ?? '0';
        AuditLogger::registrar('INFO', 'Autenticacion', 'Cierre de Sesión', "El usuario '{$nombreUser}' (ID: {$idUser}) cerró su sesión voluntariamente.");

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
        $hasSpecial = preg_match('/[^a-zA-Z0-9\s]/', $password);

        if (!$hasLength || !$hasUpper || !$hasNumber || !$hasSpecial) {
            return [
                'es_error' => true,
                'mensaje'  => 'La contraseña no cumple con los requisitos mínimos de seguridad (8+ caracteres, 1 mayúscula, 1 número y 1 carácter especial).',
                'destino'  => 'registro'
            ];
        }

        // 3. Validar duplicados en la BD
        if ($this->usuarioModel->existeUsuario($cedula, $email)) {
            AuditLogger::registrar('WARNING', 'Autenticacion', 'Registro Duplicado', "Intento de registro con cédula o correo existente: C.I: '{$cedula}', Email: '{$email}'.");
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
            AuditLogger::registrar('INFO', 'Autenticacion', 'Registro de Usuario', "Nuevo usuario registrado exitosamente: '{$nombre}' (C.I: {$cedula}, Email: {$email}).");

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

            // AUTO-LOGIN DIRECTO: Iniciamos sesión automáticamente para que pase al sistema
            $usuario = $this->usuarioModel->intentarAutenticacion($cedula);
            if ($usuario) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['login_time'] = time();
                $this->limpiarRevocacionSesion($usuario['id']);
                $_SESSION['usuario_nombre'] = $usuario['nombre_completo'];
                $_SESSION['nombre_usuario'] = $usuario['nombre_completo'];
                $_SESSION['nombre'] = $usuario['nombre_completo'];
                $_SESSION['usuario_cedula'] = $usuario['cedula'] ?? $cedula;
                $_SESSION['usuario_email'] = $usuario['email'] ?? $email;
                $_SESSION['rol_nombre'] = $usuario['nombre_rol'];
                $_SESSION['rol'] = $usuario['nombre_rol'];
                $_SESSION['usuario_rol'] = $usuario['nombre_rol'];
                $_SESSION['nivel_privilegio'] = (int) $usuario['nivel_privilegio'];

                try {
                    $this->usuarioModel->registrarAcceso($usuario['id']);
                } catch (Exception $e) {}

                // ÉXITO: Mandamos los datos para la pantalla de bienvenida y pasamos directo a perfil
                return [
                    'es_error'       => false,
                    'nombre_usuario' => $nombre,
                    'rol_nombre'     => $usuario['nombre_rol'] ?? 'Estudiante',
                    'destino'        => 'perfil'
                ];
            }

            $_SESSION['exito_registro'] = "¡Cuenta creada con éxito! Se ha enviado un mensaje de bienvenida a su correo. Ya puede iniciar sesión.";

            return [
                'es_error'       => false,
                'nombre_usuario' => $nombre,
                'rol_nombre'     => 'Cuenta Creada Exitosamente',
                'destino'        => 'login'
            ];
        } else {
            AuditLogger::registrar('ERROR', 'Autenticacion', 'Fallo Registro Usuario', "Error en el servidor al registrar el usuario '{$nombre}' (C.I: {$cedula}).");
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

        $error = $_SESSION['error_completar_registro'] ?? null;
        unset($_SESSION['error_completar_registro']);

        return [
            'token'    => $rawToken,
            'profesor' => $profesor,
            'error'    => $error
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

        // Validación de seguridad Anti-Bot (Honeypot + Tiempo Humano + Captcha)
        $verifCaptcha = CaptchaService::validarPeticion($_POST);
        if (!$verifCaptcha['valido']) {
            $_SESSION['error_completar_registro'] = $verifCaptcha['mensaje'];
            header("Location: completar-registro?token=" . urlencode($rawToken));
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

        // Requisitos mínimos de seguridad (8+ caracteres, 1 mayúscula, 1 número, 1 símbolo o especial como +)
        if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[^a-zA-Z0-9\s]/', $password)) {
            $_SESSION['error_completar_registro'] = "La contraseña debe tener al menos 8 caracteres, 1 mayúscula, 1 número y 1 carácter especial o símbolo (+, @, #, $, etc.).";
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
            AuditLogger::registrar('INFO', 'Autenticacion', 'Activación Docente Exitoso', "El profesor {$profesor['nombre_completo']} (C.I: {$profesor['cedula']}) activó su cuenta mediante token.");

            // AUTO-LOGIN DIRECTO DEL PROFESOR: Entra directo al sistema sin reingresar credenciales
            $usuario = $this->usuarioModel->intentarAutenticacion($profesor['cedula']);
            if ($usuario) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['login_time'] = time();
                $this->limpiarRevocacionSesion($usuario['id']);
                $_SESSION['usuario_nombre'] = $usuario['nombre_completo'];
                $_SESSION['nombre_usuario'] = $usuario['nombre_completo'];
                $_SESSION['nombre'] = $usuario['nombre_completo'];
                $_SESSION['usuario_cedula'] = $usuario['cedula'] ?? $profesor['cedula'];
                $_SESSION['usuario_email'] = $usuario['email'] ?? $profesor['email'];
                $_SESSION['rol_nombre'] = $usuario['nombre_rol'];
                $_SESSION['rol'] = $usuario['nombre_rol'];
                $_SESSION['usuario_rol'] = $usuario['nombre_rol'];
                $_SESSION['nivel_privilegio'] = (int) $usuario['nivel_privilegio'];

                try {
                    $this->usuarioModel->registrarAcceso($usuario['id']);
                } catch (Exception $e) {}

                // ÉXITO: Mandamos los datos para la pantalla de bienvenida y pasamos directo a perfil
                return [
                    'es_error'       => false,
                    'nombre_usuario' => $profesor['nombre_completo'],
                    'rol_nombre'     => $usuario['nombre_rol'] ?? 'Docente',
                    'destino'        => 'perfil'
                ];
            }

            $_SESSION['exito_login'] = "¡Cuenta de profesor activada exitosamente! Ya puede iniciar sesión con sus credenciales.";
            header("Location: login");
            exit;
        } else {
            AuditLogger::registrar('ERROR', 'Autenticacion', 'Fallo Activación Docente', "Error al activar la cuenta para el profesor {$profesor['nombre_completo']} (ID: {$profesor['id']}).");
            $_SESSION['error_completar_registro'] = "Error inesperado al activar la cuenta.";
            header("Location: completar-registro?token=" . urlencode($rawToken));
            exit;
        }
    }

    private function limpiarRevocacionSesion($usuarioId): void {
        $archivoSesiones = defined('STORAGE_PATH') ? STORAGE_PATH . 'revoked_sessions.json' : CORE_PATH . '../storage/revoked_sessions.json';
        if (file_exists($archivoSesiones)) {
            $revogadas = json_decode(file_get_contents($archivoSesiones), true) ?: [];
            $uidStr = (string)$usuarioId;
            if (isset($revogadas[$uidStr])) {
                unset($revogadas[$uidStr]);
                file_put_contents($archivoSesiones, json_encode($revogadas, JSON_PRETTY_PRINT));
            }
        }
    }
}