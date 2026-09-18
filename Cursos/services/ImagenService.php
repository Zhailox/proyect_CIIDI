<?php
// modules/Cursos/services/ImagenService.php
// Servicio de procesamiento de imágenes para el módulo de Cursos.
// Ahora acepta los formatos configurados en config_cursos.json.

class CursosImagenService {

    public static function validarMime(array $file, array $permitidos): bool {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return false;
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);
        return in_array($mime, $permitidos, true);
    }

    public static function validarExtension(array $file, array $permitidas): bool {
        $nombre = $file['name'] ?? '';
        $ext = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));
        return in_array($ext, $permitidas, true);
    }

    public static function convertirAWebp(string $origen, string $destino, string $mime, int $calidad = 80): bool {
        if (!function_exists('imagewebp')) return false;

        $imagen = null;
        switch ($mime) {
            case 'image/jpeg': $imagen = @imagecreatefromjpeg($origen); break;
            case 'image/png':  $imagen = @imagecreatefrompng($origen); break;
            case 'image/webp': $imagen = @imagecreatefromwebp($origen); break;
            case 'image/gif':  $imagen = @imagecreatefromgif($origen); break;
        }
        if (!$imagen) return false;

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

    public static function procesarImagen(array $file, string $carpeta, array $config = []): ?string {
        if ($file['error'] !== UPLOAD_ERR_OK) return null;

        $exts = $config['extensiones_permitidas'] ?? ['jpg','jpeg','png','webp','gif'];
        $mimes = $config['mime_permitidos'] ?? ['image/jpeg','image/png','image/gif','image/webp'];

        if (!self::validarExtension($file, $exts)) return null;
        if (!self::validarMime($file, $mimes)) return null;

        $maxBytes = ($config['max_size_mb'] ?? 5) * 1024 * 1024;
        if ($file['size'] > $maxBytes) return null;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0755, true);
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);

        $nombreBase = 'curso_' . time() . '_' . bin2hex(random_bytes(4));
        $convertir  = $config['convertir_a_webp'] ?? true;

        if ($convertir && function_exists('imagewebp')) {
            $nombreFinal = $nombreBase . '.webp';
            $rutaFinal   = $carpeta . $nombreFinal;
            $ok = self::convertirAWebp($file['tmp_name'], $rutaFinal, $mime);
            if (!$ok) return null;
        } else {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $nombreFinal = $nombreBase . '.' . $ext;
            $rutaFinal   = $carpeta . $nombreFinal;
            if (!move_uploaded_file($file['tmp_name'], $rutaFinal)) return null;
        }

        return $nombreFinal;
    }
}
