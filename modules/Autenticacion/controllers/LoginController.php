<?php
// modules/Autenticacion/controllers/LoginController.php
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once CORE_PATH . 'Security/Auth.php';
require_once CORE_PATH . 'Helpers/PHPMailer/Exception.php';
require_once CORE_PATH . 'Helpers/PHPMailer/PHPMailer.php';
require_once CORE_PATH . 'Helpers/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

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
        
        $usuario = null;
        if ($metodo === 'cedula') {
            $usuario = $this->usuarioModel->findByCedula($dato);
        } else {
            $usuario = $this->usuarioModel->findByEmail($dato);
        }
        
        if ($usuario && !empty($usuario['email'])) {
            $codigo = sprintf("%06d", mt_rand(1, 999999));
            $this->usuarioModel->guardarTokenRecuperacion($usuario['id'], $codigo);
            
            $mail = new PHPMailer(true);
            try {
                // CONFIGURACIÓN SMTP
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; 
                $mail->SMTPAuth   = true;
                $mail->Username   = 'orlando5711666@gmail.com'; 
                $mail->Password   = 'hkwtkytxrqxslngb'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;
                $mail->CharSet    = 'UTF-8';

                $mail->setFrom('no-reply@ciidi.edu.ve', 'Sistema CIIDI');
                $mail->addAddress($usuario['email'], $usuario['nombre_completo']);

                $mail->isHTML(true);
                $mail->Subject = 'Código de Recuperación de Acceso - CIIDI';
                $mail->Body    = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;'>
                        <div style='background-color: #121a3e; padding: 20px; text-align: center; color: white;'>
                            <h2 style='margin: 0;'>Recuperación de Contraseña</h2>
                        </div>
                        <div style='padding: 20px; background-color: #ffffff; color: #333333;'>
                            <p>Hola <b>{$usuario['nombre_completo']}</b>,</p>
                            <p>Hemos recibido una solicitud para restablecer el acceso a tu cuenta en el sistema CIIDI.</p>
                            <p>Tu código de seguridad de 6 dígitos es:</p>
                            <div style='text-align: center; margin: 20px 0;'>
                                <span style='font-size: 32px; font-weight: bold; color: #121a3e; letter-spacing: 5px; background: #f1f5f9; padding: 10px 20px; border-radius: 8px; border: 1px dashed #94a3b8;'>$codigo</span>
                            </div>
                            <p style='color: #ef4444; font-size: 0.9em;'>⚠ Este código expirará en 15 minutos.</p>
                            <p>Si no solicitaste este cambio, puedes ignorar este correo de forma segura.</p>
                        </div>
                        <div style='background-color: #f8fafc; padding: 15px; text-align: center; font-size: 0.8em; color: #64748b;'>
                            &copy; " . date('Y') . " Sistema CIIDI - Todos los derechos reservados.
                        </div>
                    </div>
                ";
                $mail->AltBody = "Hola {$usuario['nombre_completo']},\n\nTu código de recuperación es: $codigo\n\nEste código expirará en 15 minutos.";

                $mail->send();
                $_SESSION['exito_recuperar'] = "Hemos enviado un código a tu correo: {$usuario['email']}. Revisa tu bandeja de entrada o la carpeta de Spam.";
            } catch (Exception $e) {
                // Fallback para pruebas locales si falla el SMTP por no estar configurado aún
                $_SESSION['exito_recuperar'] = "Código generado (SMTP aún no configurado). Para continuar tus pruebas locales el código es: $codigo";
            }
            
            header("Location: ?ruta=ingresar-codigo&uid=" . $usuario['id']);
            exit;
        } else {
            $_SESSION['error_recuperar'] = "No se encontró ningún usuario con ese dato, o no tiene correo asociado.";
            header("Location: ?ruta=recuperar-cuenta");
            exit;
        }
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