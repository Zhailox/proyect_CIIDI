<?php
// modules/Articulos/controllers/ConfiguracionController.php
require_once __DIR__ . '/../services/ConfigService.php';

class ConfiguracionController {

    public function index(): array {
        require_once CORE_PATH . 'Security/Auth.php';
        Auth::requierePrivilegioMinimo(2); 

        $mensaje = null;
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $actual = ConfigService::get() ?? [];

                // 1. Citas
                if (!empty($_POST['eliminar_estilo']) && is_string($_POST['eliminar_estilo'])) {
                    unset($actual['citas']['estilos'][trim($_POST['eliminar_estilo'])]);
                }
                if (isset($_POST['citas_estilos']) && is_array($_POST['citas_estilos'])) {
                    foreach ($_POST['citas_estilos'] as $slug => $item) {
                        if (isset($actual['citas']['estilos'][$slug])) {
                            $actual['citas']['estilos'][$slug]['nombre'] = trim($item['nombre'] ?? '');
                            $actual['citas']['estilos'][$slug]['activo'] = isset($item['activo']) && $item['activo'] === '1';
                            $actual['citas']['estilos'][$slug]['plantilla'] = trim($item['plantilla'] ?? '');
                        }
                    }
                }
                if (!empty($_POST['nuevo_estilo_slug']) && !empty($_POST['nuevo_estilo_nombre'])) {
                    $slugNew = preg_replace('/[^a-z0-9_]/', '', strtolower(trim($_POST['nuevo_estilo_slug'])));
                    if (!empty($slugNew)) {
                        $actual['citas']['estilos'][$slugNew] = [
                            'nombre' => trim($_POST['nuevo_estilo_nombre']),
                            'activo' => true,
                            'plantilla' => trim($_POST['nuevo_estilo_plantilla'] ?? '{autores} ({anio}). {titulo}.')
                        ];
                    }
                }

                // 2. Paginación
                if (isset($_POST['limite_catalogo'])) $actual['paginacion']['limite_catalogo'] = max(1, (int)$_POST['limite_catalogo']);
                if (isset($_POST['limite_gestor'])) $actual['paginacion']['limite_gestor'] = max(1, (int)$_POST['limite_gestor']);
                if (isset($_POST['max_recomendados'])) $actual['paginacion']['max_recomendados'] = max(1, (int)$_POST['max_recomendados']);

                // 3. Recursos (Metadatos visuales)
                $actual['recursos']['mostrar_editorial'] = isset($_POST['mostrar_editorial']) && $_POST['mostrar_editorial'] === '1';
                $actual['recursos']['mostrar_volumen'] = isset($_POST['mostrar_volumen']) && $_POST['mostrar_volumen'] === '1';
                $actual['recursos']['mostrar_issn'] = isset($_POST['mostrar_issn']) && $_POST['mostrar_issn'] === '1';
                // El buscador que estaba resagado 
                if (isset($_POST['anio_minimo'])) {
                    $actual['buscador']['anio_minimo'] = (int)$_POST['anio_minimo'];
                }
                // 4. Archivos (Imágenes y Extensiones)
                if (isset($_POST['max_size_mb'])) $actual['archivos']['max_size_mb'] = max(1, (int)$_POST['max_size_mb']);
                if (!empty($_POST['extensiones_permitidas_raw'])) {
                    $exts = explode(',', $_POST['extensiones_permitidas_raw']);
                    $cleanExts = [];
                    foreach ($exts as $ext) {
                        $ext = strtolower(trim($ext));
                        // Asegurar formato ".ext"
                        if ($ext !== '' && $ext !== '.') {
                             $cleanExts[] = (strpos($ext, '.') === 0) ? $ext : '.' . $ext;
                        }
                    }
                    if (!empty($cleanExts)) {
                        $actual['archivos']['extensiones_permitidas'] = array_unique($cleanExts);
                    }
                }


                if (ConfigService::save($actual)) {
                    $mensaje = "¡Configuración de la Revista guardada exitosamente!";
                } else {
                    $error = "No se pudo guardar la configuración.";
                }
            } catch (Exception $e) {
                $error = "Error: " . $e->getMessage();
            }
        }

        return [
            'config'  => ConfigService::get(),
            'mensaje' => $mensaje,
            'error'   => $error
        ];
    }
}