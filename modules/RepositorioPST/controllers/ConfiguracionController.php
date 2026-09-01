<?php
// modules/RepositorioPST/controllers/ConfiguracionController.php
require_once __DIR__ . '/../services/ConfigService.php';

class ConfiguracionController {

    public function index(): array {
        require_once CORE_PATH . 'Security/Auth.php';
        Auth::requierePrivilegioMinimo(1);

        $mensaje = null;
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $actual = ConfigService::get() ?? [];

                // 1. Citas (Eliminar y Actualizar)
                if (!empty($_POST['eliminar_estilo']) && is_string($_POST['eliminar_estilo'])) {
                    $slugEliminar = trim($_POST['eliminar_estilo']);
                    unset($actual['citas']['estilos'][$slugEliminar]);
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

                // Si se agrega un nuevo estilo de cita desde la UI
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
                if (isset($_POST['limite_catalogo'])) {
                    $actual['paginacion']['limite_catalogo'] = max(1, (int)$_POST['limite_catalogo']);
                }
                if (isset($_POST['limite_buscador'])) {
                    $actual['paginacion']['limite_buscador'] = max(1, (int)$_POST['limite_buscador']);
                }
                if (isset($_POST['max_proyectos_similares'])) {
                    $actual['paginacion']['max_proyectos_similares'] = max(1, (int)$_POST['max_proyectos_similares']);
                }
                if (!empty($_POST['opciones_selector_raw'])) {
                    $opts = array_map('intval', explode(',', $_POST['opciones_selector_raw']));
                    $opts = array_values(array_filter($opts, fn($n) => $n > 0));
                    sort($opts);
                    if (!empty($opts)) {
                        $actual['paginacion']['opciones_selector'] = $opts;
                    }
                }

                // 3. Recursos
                $actual['recursos']['sufijo_tipo_recurso'] = trim($_POST['sufijo_tipo_recurso'] ?? 'PST / Proyecto Socio-Tecnológico');
                $actual['recursos']['mostrar_url_git'] = isset($_POST['mostrar_url_git']) && $_POST['mostrar_url_git'] === '1';
                $actual['recursos']['mostrar_comunidad'] = isset($_POST['mostrar_comunidad']) && $_POST['mostrar_comunidad'] === '1';
                $actual['recursos']['mostrar_nivel_academico'] = isset($_POST['mostrar_nivel_academico']) && $_POST['mostrar_nivel_academico'] === '1';

                // 4. Buscador
                if (isset($_POST['anio_minimo_histograma'])) {
                    $actual['buscador']['anio_minimo_histograma'] = (int)$_POST['anio_minimo_histograma'];
                }
                if (isset($_POST['orden_predeterminado'])) {
                    $actual['buscador']['orden_predeterminado'] = trim($_POST['orden_predeterminado']);
                }
                $actual['buscador']['resaltar_coincidencias'] = isset($_POST['resaltar_coincidencias']) && $_POST['resaltar_coincidencias'] === '1';

                // 5. Visor PDF
                $actual['visor_pdf']['mostrar_toolbar'] = isset($_POST['mostrar_toolbar']) && $_POST['mostrar_toolbar'] === '1';
                $actual['visor_pdf']['permitir_descarga'] = isset($_POST['permitir_descarga']) && $_POST['permitir_descarga'] === '1';

                // 6. Archivos y Carga Documental
                if (isset($_POST['max_size_mb'])) {
                    $actual['archivos']['max_size_mb'] = max(1, (int)$_POST['max_size_mb']);
                }
                if (isset($_POST['max_autores'])) {
                    $actual['limites_equipo']['max_autores'] = max(1, (int)$_POST['max_autores']);
                }
                if (isset($_POST['max_tutores'])) {
                    $actual['limites_equipo']['max_tutores'] = max(1, (int)$_POST['max_tutores']);
                }

                if (ConfigService::save($actual)) {
                    $mensaje = "¡Configuración del Repositorio guardada exitosamente!";
                } else {
                    $error = "No se pudo escribir en el archivo de configuración JSON.";
                }
            } catch (Exception $e) {
                $error = "Error al procesar la configuración: " . $e->getMessage();
            }
        }

        $config = ConfigService::get();

        return [
            'config'  => $config,
            'mensaje' => $mensaje,
            'error'   => $error
        ];
    }
}
