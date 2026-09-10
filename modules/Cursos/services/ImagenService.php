<?php
// modules/Cursos/services/ImagenService.php
// Servicio de procesamiento de imágenes para el módulo de Cursos.
// Soporta: validación MIME real, conversión a WebP, guardado seguro.

class CursosImagenService {

    /**
     * Tipos MIME válidos para imágenes de portada.
     */
    private static array $mimePermitidos = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    /**
     * Verifica el MIME REAL del archivo (no solo la extensión).
     * Usa finfo para leer los magic bytes del archivo.
     */
    public static function validarMime(array $file): bool {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return false;
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);

        return in_array($mime, self::$mimePermitidos, true);
    }

    /**
     * Convierte una imagen (JPG/PNG/GIF/WEBP) a formato WebP usando GD.
     * Devuelve true en éxito, false en fallo.
     */
    public static function convertirAWebp(string $origen, string $destino, int $calidad = 80): bool {
        if (!function_exists('imagewebp')) return false;

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($origen);

        $imagen = match($mime) {
            'image/jpeg' => imagecreatefromjpeg($origen),
            'image/png'  => imagecreatefrompng($origen),
            'image/gif'  => imagecreatefromgif($origen),
            'image/webp' => imagecreatefromwebp($origen),
            default      => null,
        };

        if (!$imagen) return false;

        // Para PNG con transparencia, convertir a fondo blanco antes de webp
        if ($mime === 'image/png') {
            $ancho  = imagesx($imagen);
            $alto   = imagesy($imagen);
            $fondo  = imagecreatetruecolor($ancho, $alto);
            $blanco = imagecolorallocate($fondo, 255, 255, 255);
            imagefill($fondo, 0, 0, $blanco);
            imagecopy($fondo, $imagen, 0, 0, 0, 0, $ancho, $alto);
            imagedestroy($imagen);
            $imagen = $fondo;
        }

        $ok = imagewebp($imagen, $destino, $calidad);
        imagedestroy($imagen);

        return $ok;
    }

    /**
     * Procesa la imagen subida: valida MIME, convierte a WebP, guarda.
     * Devuelve el nombre final del archivo (sin ruta) o null si falla.
     *
     * @param array  $file    El $_FILES['campo'] correspondiente
     * @param string $carpeta Ruta absoluta de destino
     * @param array  $config  Configuración del módulo (max_size_mb, convertir_a_webp)
     */
    public static function procesarImagen(array $file, string $carpeta, array $config = []): ?string {
        // 1. Verificar error de subida
        if ($file['error'] !== UPLOAD_ERR_OK) return null;

        // 2. Validar MIME real
        if (!self::validarMime($file)) return null;

        // 3. Validar tamaño
        $maxBytes = ($config['max_size_mb'] ?? 5) * 1024 * 1024;
        if ($file['size'] > $maxBytes) return null;

        // 4. Preparar carpeta
        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0755, true);
        }

        $nombreBase = 'curso_' . time() . '_' . bin2hex(random_bytes(4));
        $convertir  = $config['convertir_a_webp'] ?? true;

        if ($convertir && function_exists('imagewebp')) {
            // Convertir a WebP
            $nombreFinal = $nombreBase . '.webp';
            $rutaFinal   = $carpeta . $nombreFinal;
            $ok = self::convertirAWebp($file['tmp_name'], $rutaFinal);
            if (!$ok) return null;
        } else {
            // Guardar con extensión original
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime  = $finfo->file($file['tmp_name']);
            $ext   = match($mime) {
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/gif'  => 'gif',
                'image/webp' => 'webp',
                default      => 'jpg',
            };
            $nombreFinal = $nombreBase . '.' . $ext;
            $rutaFinal   = $carpeta . $nombreFinal;
            if (!move_uploaded_file($file['tmp_name'], $rutaFinal)) return null;
        }

        return $nombreFinal;
    }
}
