<?php
// modules/Cursos/services/ImagenService.php
// Servicio de procesamiento de imágenes para el módulo de Cursos.
// Restricción estricta: solo se aceptan archivos PNG auténticos.

class CursosImagenService {

    /**
     * Único tipo MIME válido: imagen PNG.
     */
    private static array $mimePermitidos = [
        'image/png',
    ];

    /**
     * Extensión de archivo permitida.
     */
    private static string $extensionPermitida = 'png';

    /**
     * Firma (magic bytes) de un archivo PNG: 0x89 0x50 0x4E 0x47 0x0D 0x0A 0x1A 0x0A
     */
    private static string $pngMagicBytes = "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A";

    /**
     * Verifica el MIME REAL del archivo usando finfo (magic bytes del sistema).
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
     * Verifica la extensión del nombre de archivo original.
     */
    public static function validarExtension(array $file): bool {
        $nombre = $file['name'] ?? '';
        $ext = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));
        return $ext === self::$extensionPermitida;
    }

    /**
     * Verifica la firma binaria (magic bytes) del archivo PNG.
     * Lee los primeros 8 bytes y compara con la firma oficial de PNG.
     */
    public static function validarFirmaPNG(array $file): bool {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return false;
        }

        $handle = fopen($file['tmp_name'], 'rb');
        if (!$handle) return false;

        $bytes = fread($handle, 8);
        fclose($handle);

        return $bytes === self::$pngMagicBytes;
    }

    /**
     * Convierte un PNG a formato WebP usando GD.
     * Devuelve true en éxito, false en fallo.
     */
    public static function convertirAWebp(string $origen, string $destino, int $calidad = 80): bool {
        if (!function_exists('imagewebp')) return false;

        $imagen = imagecreatefrompng($origen);
        if (!$imagen) return false;

        // Para PNG con transparencia, convertir a fondo blanco antes de webp
        $ancho  = imagesx($imagen);
        $alto   = imagesy($imagen);
        $fondo  = imagecreatetruecolor($ancho, $alto);
        $blanco = imagecolorallocate($fondo, 255, 255, 255);
        imagefill($fondo, 0, 0, $blanco);
        imagecopy($fondo, $imagen, 0, 0, 0, 0, $ancho, $alto);
        imagedestroy($imagen);
        $imagen = $fondo;

        $ok = imagewebp($imagen, $destino, $calidad);
        imagedestroy($imagen);

        return $ok;
    }

    /**
     * Procesa la imagen subida: valida extensión, MIME, firma binaria y tamaño.
     * Solo acepta PNG auténticos.
     * Devuelve el nombre final del archivo (sin ruta) o null si falla.
     *
     * @param array  $file    El $_FILES['campo'] correspondiente
     * @param string $carpeta Ruta absoluta de destino
     * @param array  $config  Configuración del módulo (max_size_mb, convertir_a_webp)
     */
    public static function procesarImagen(array $file, string $carpeta, array $config = []): ?string {
        // 1. Verificar error de subida
        if ($file['error'] !== UPLOAD_ERR_OK) return null;

        // 2. Validar extensión del archivo (.png)
        if (!self::validarExtension($file)) return null;

        // 3. Validar MIME real (debe ser image/png)
        if (!self::validarMime($file)) return null;

        // 4. Validar firma binaria PNG (magic bytes)
        if (!self::validarFirmaPNG($file)) return null;

        // 5. Validar tamaño
        $maxBytes = ($config['max_size_mb'] ?? 5) * 1024 * 1024;
        if ($file['size'] > $maxBytes) return null;

        // 6. Preparar carpeta de destino
        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0755, true);
        }

        $nombreBase = 'curso_' . time() . '_' . bin2hex(random_bytes(4));
        $convertir  = $config['convertir_a_webp'] ?? true;

        if ($convertir && function_exists('imagewebp')) {
            // Convertir PNG a WebP
            $nombreFinal = $nombreBase . '.webp';
            $rutaFinal   = $carpeta . $nombreFinal;
            $ok = self::convertirAWebp($file['tmp_name'], $rutaFinal);
            if (!$ok) return null;
        } else {
            // Guardar como PNG
            $nombreFinal = $nombreBase . '.png';
            $rutaFinal   = $carpeta . $nombreFinal;
            if (!move_uploaded_file($file['tmp_name'], $rutaFinal)) return null;
        }

        return $nombreFinal;
    }
}
