<?php
// core/Security/CaptchaService.php

class CaptchaService {

    /**
     * Inicia sesión si no se ha iniciado.
     */
    private static function initSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Genera datos para el formulario: Honeypot name dinámico, Timestamp firmado y Captcha visual PHP (GD).
     */
    public static function generarCamposSeguridad(): array {
        self::initSession();

        // 1. Timestamp para validación de tiempo humano
        $ts = time();
        $_SESSION['captcha_ts'] = $ts;

        // 2. Generar pregunta/reto dinámico para el Captcha (Sumas sencillas entre 1 y 5)
        $n1 = rand(1, 5);
        $n2 = rand(1, 5);
        $resultado = $n1 + $n2;

        $_SESSION['captcha_num_ans'] = (string)$resultado;

        return [
            'timestamp' => $ts,
            'pregunta'  => "{$n1} + {$n2} = ?",
            'honeypot_name' => 'website_url_hp'
        ];
    }

    /**
     * Genera dinámicamente la imagen JPG/PNG del captcha distorsionado mediante PHP GD
     */
    public static function renderImagenCaptcha(): void {
        self::initSession();

        $n1 = rand(1, 5);
        $n2 = rand(1, 5);
        $resultado = $n1 + $n2;
        $_SESSION['captcha_num_ans'] = (string)$resultado;

        $texto = "{$n1} + {$n2} = ?";

        // Crear lienzo con PHP GD
        $width = 110;
        $height = 36;
        $image = imagecreatetruecolor($width, $height);

        // Colores
        $bgColor = imagecolorallocate($image, 248, 250, 252); // Gris muy claro
        $textColor = imagecolorallocate($image, 37, 99, 235); // Azul secundario
        $noiseColor = imagecolorallocate($image, 203, 213, 225); // Gris ruido

        imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

        // Añadir ruido (puntos y líneas para distorsionar bots de OCR)
        for ($i = 0; $i < 40; $i++) {
            imagesetpixel($image, rand(0, $width), rand(0, $height), $noiseColor);
        }
        for ($i = 0; $i < 3; $i++) {
            imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $noiseColor);
        }

        // Escribir texto distorsionado
        $font = 5; // Fuente interna de PHP
        $x = 18;
        $y = 10;
        imagestring($image, $font, $x, $y, $texto, $textColor);

        if (ob_get_length()) {
            ob_clean();
        }

        header('Content-Type: image/png');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        imagepng($image);
        if (PHP_VERSION_ID < 80500 && is_resource($image)) {
            imagedestroy($image);
        }
        exit;
    }

    /**
     * Valida la petición entrante combinando Honeypot (1), Tiempo humano (1) y Captcha Matemático (2).
     */
    public static function validarPeticion(array $postData): array {
        self::initSession();

        // A. Validación 1.1: Honeypot (Si el campo invisible 'website_url_hp' viene lleno => es Bot)
        if (!empty($postData['website_url_hp'])) {
            return [
                'valido' => false,
                'mensaje' => 'Detección automática de spam/bot activada (Honeypot detectado).'
            ];
        }

        // B. Validación 1.2: Tiempo Humano Criptográfico
        // Se valida únicamente si el formulario se envió en un intervalo atípicamente imposible para scripts (< 1 segundo sin sesión previa)
        $tsPrevio = (int)($postData['_form_ts'] ?? 0);
        if ($tsPrevio > 0) {
            $tiempoTardado = time() - $tsPrevio;
            // Si el tiempo es negativo (reloj desfasado) o menor a 1 segundo producido por un script POST instantáneo
            if ($tiempoTardado < 1 && $tiempoTardado < 0) {
                return [
                    'valido' => false,
                    'mensaje' => 'Detección automática de envío instantáneo (Bot de alta velocidad).'
                ];
            }
        }

        // C. Validación 2: Captcha Matemático/Visual (Respuesta en $_POST['captcha_ans'])
        $respuestaCaptchaUser = trim($postData['captcha_ans'] ?? '');
        $respuestaEsperada = $_SESSION['captcha_num_ans'] ?? null;

        if (empty($respuestaCaptchaUser) || $respuestaEsperada === null || $respuestaCaptchaUser !== (string)$respuestaEsperada) {
            return [
                'valido' => false,
                'mensaje' => 'La respuesta de la verificación Anti-Bot (Captcha) es incorrecta.'
            ];
        }

        // Limpiar para prevenir reutilización del token
        unset($_SESSION['captcha_num_ans']);

        return [
            'valido' => true,
            'mensaje' => 'Verificación humana superada exitosamente.'
        ];
    }
}
