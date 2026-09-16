<?php
// modules/Cursos/services/CsrfService.php
// Servicio de protección CSRF para el módulo de Cursos.

class CursosCsrfService {

    private const SESSION_KEY = 'cur_csrf_token';

    /**
     * Genera (o reutiliza) un token CSRF y lo guarda en sesión.
     */
    public static function generarToken(): string {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    /**
     * Verifica el token recibido contra el de sesión.
     * Lo rota después de verificar para evitar replay attacks.
     */
    public static function verificarToken(string $tokenRecibido): bool {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $tokenEsperado = $_SESSION[self::SESSION_KEY] ?? '';

        // hash_equals previene timing attacks
        $valido = !empty($tokenEsperado) && hash_equals($tokenEsperado, $tokenRecibido);

        // Rotar siempre para uso único
        unset($_SESSION[self::SESSION_KEY]);
        self::generarToken(); // pre-genera el siguiente

        return $valido;
    }

    /**
     * Devuelve el campo hidden HTML listo para insertar en formularios.
     */
    public static function campoHidden(): string {
        $token = self::generarToken();
        return '<input type="hidden" name="cur_csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    /**
     * Verifica el POST y redirige con error si no es válido.
     */
    public static function verificarOFallar(string $redirectUrl = 'cursos'): void {
        $token = $_POST['cur_csrf_token'] ?? '';
        if (!self::verificarToken($token)) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['cur_error'] = 'Token de seguridad inválido. Por favor intente de nuevo.';
            header("Location: ?ruta={$redirectUrl}");
            exit;
        }
    }
}
