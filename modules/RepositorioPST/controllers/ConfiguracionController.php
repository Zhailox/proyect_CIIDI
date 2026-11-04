<?php
// modules/RepositorioPST/controllers/ConfiguracionController.php
require_once __DIR__ . '/../services/ConfigService.php';
require_once __DIR__ . '/../../SuperAdmin/services/SystemConfigService.php';
require_once CORE_PATH . 'Security/AuditLogger.php';
require_once CORE_PATH . 'Database/Connection.php';

class ConfiguracionController {
    private int $nivelAdmin;
    private int $nivelPublico;
    
    public function __construct() {
        $this->nivelAdmin   = SystemConfigService::get('accesos_modulos.repositorio_pst.admin', 1);
        $this->nivelPublico = SystemConfigService::get('accesos_modulos.repositorio_pst.publico', 10);
    }
    public function index(): array {
        require_once CORE_PATH . 'Security/Auth.php';
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'auditar', "RepositorioPST");

        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // 1. Consumimos mensajes de sesión y limpiamos
        $mensaje = $_SESSION['mensaje_exito'] ?? null;
        $error = $_SESSION['mensaje_error'] ?? null;
        unset($_SESSION['mensaje_exito'], $_SESSION['mensaje_error']);

        require_once __DIR__ . '/../models/DocumentoModel.php';
        $documentoModel = new DocumentoModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $submittedCsrf = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
                if (empty($submittedCsrf) || !hash_equals($_SESSION['csrf_token'], $submittedCsrf)) {
                    throw new Exception("Petición rechazada por seguridad: Token CSRF no válido o expirado.");
                }

                // A. GESTIÓN DE NIVELES ACADÉMICOS (AÑADIR, MODIFICAR, ELIMINAR, TOGGLE)
                $accionNivel = $_POST['accion_nivel'] ?? null;
                if (!empty($accionNivel)) {
                    if ($accionNivel === 'crear') {
                        $res = $documentoModel->crearNivelAcademico($_POST);
                        if ($res['success']) {
                            $_SESSION['mensaje_exito'] = $res['message'];
                            AuditLogger::registrar('INFO', 'RepositorioPST', 'Crear Nivel Académico', "Se registró el nivel '{$_POST['nombre']}'.");
                        } else {
                            $_SESSION['mensaje_error'] = $res['message'];
                        }
                    } elseif ($accionNivel === 'editar') {
                        $idNivel = (int)($_POST['id_nivel'] ?? 0);
                        $res = $documentoModel->actualizarNivelAcademico($idNivel, $_POST);
                        if ($res['success']) {
                            $_SESSION['mensaje_exito'] = $res['message'];
                            AuditLogger::registrar('INFO', 'RepositorioPST', 'Editar Nivel Académico', "Se actualizó el nivel académico ID {$idNivel}.");
                        } else {
                            $_SESSION['mensaje_error'] = $res['message'];
                        }
                    } elseif ($accionNivel === 'eliminar') {
                        $idNivel = (int)($_POST['id_nivel'] ?? 0);
                        $res = $documentoModel->eliminarNivelAcademico($idNivel);
                        if ($res['success']) {
                            $_SESSION['mensaje_exito'] = $res['message'];
                            AuditLogger::registrar('INFO', 'RepositorioPST', 'Eliminar Nivel Académico', "Se eliminó el nivel académico ID {$idNivel}.");
                        } else {
                            $_SESSION['mensaje_error'] = $res['message'];
                        }
                    } elseif ($accionNivel === 'toggle') {
                        $idNivel = (int)($_POST['id_nivel'] ?? 0);
                        $res = $documentoModel->toggleNivelAcademico($idNivel);
                        if ($res['success']) {
                            $_SESSION['mensaje_exito'] = $res['message'];
                        } else {
                            $_SESSION['mensaje_error'] = $res['message'];
                        }
                    }

                    header('Location: ?ruta=configuracion-pst&tab=tabNiveles');
                    exit;
                }

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
                if (isset($_POST['modo_carga'])) $actual['paginacion']['modo_carga'] = trim($_POST['modo_carga']);
                if (isset($_POST['limite_catalogo'])) $actual['paginacion']['limite_catalogo'] = max(1, (int)$_POST['limite_catalogo']);
                if (isset($_POST['limite_buscador'])) $actual['paginacion']['limite_buscador'] = max(1, (int)$_POST['limite_buscador']);
                if (isset($_POST['max_proyectos_similares'])) $actual['paginacion']['max_proyectos_similares'] = max(1, (int)$_POST['max_proyectos_similares']);
                
                if (!empty($_POST['opciones_selector_raw'])) {
                    $opts = array_map('intval', explode(',', $_POST['opciones_selector_raw']));
                    $opts = array_values(array_filter($opts, fn($n) => $n > 0));
                    sort($opts);
                    if (!empty($opts)) $actual['paginacion']['opciones_selector'] = $opts;
                }

                // 3. Recursos
                $actual['recursos']['sufijo_tipo_recurso'] = trim($_POST['sufijo_tipo_recurso'] ?? 'PST / Proyecto Socio-Tecnológico');
                $actual['recursos']['mostrar_url_git'] = isset($_POST['mostrar_url_git']) && $_POST['mostrar_url_git'] === '1';
                $actual['recursos']['mostrar_comunidad'] = isset($_POST['mostrar_comunidad']) && $_POST['mostrar_comunidad'] === '1';
                $actual['recursos']['mostrar_nivel_academico'] = isset($_POST['mostrar_nivel_academico']) && $_POST['mostrar_nivel_academico'] === '1';

                // 4. Buscador
                if (isset($_POST['anio_minimo_histograma'])) $actual['buscador']['anio_minimo_histograma'] = (int)$_POST['anio_minimo_histograma'];
                if (isset($_POST['orden_predeterminado'])) $actual['buscador']['orden_predeterminado'] = trim($_POST['orden_predeterminado']);
                
                $actual['buscador']['resaltar_coincidencias'] = isset($_POST['resaltar_coincidencias']) && $_POST['resaltar_coincidencias'] === '1';
                $actual['buscador']['permitir_filtro_carrera'] = isset($_POST['permitir_filtro_carrera']) && $_POST['permitir_filtro_carrera'] === '1';

                // 5. Visor PDF
                $actual['visor_pdf']['mostrar_toolbar'] = isset($_POST['mostrar_toolbar']) && $_POST['mostrar_toolbar'] === '1';
                $actual['visor_pdf']['permitir_descarga'] = isset($_POST['permitir_descarga']) && $_POST['permitir_descarga'] === '1';
                if (isset($_POST['nivel_minimo_descarga'])) {
                    $actual['visor_pdf']['nivel_minimo_descarga'] = (int)$_POST['nivel_minimo_descarga'];
                }

                // 6. Archivos y Carga Documental
                if (isset($_POST['max_size_mb'])) $actual['archivos']['max_size_mb'] = max(1, (int)$_POST['max_size_mb']);
                if (isset($_POST['max_archivos_lote'])) $actual['archivos']['max_archivos_lote'] = max(1, min(50, (int)$_POST['max_archivos_lote']));
                if (isset($_POST['max_paginas_analisis'])) $actual['archivos']['max_paginas_analisis'] = max(1, min(100, (int)$_POST['max_paginas_analisis']));
                if (isset($_POST['max_autores'])) $actual['limites_equipo']['max_autores'] = max(1, (int)$_POST['max_autores']);
                if (isset($_POST['max_tutores'])) $actual['limites_equipo']['max_tutores'] = max(1, (int)$_POST['max_tutores']);

                if (ConfigService::save($actual)) {
                    $_SESSION['mensaje_exito'] = "¡Configuración del Repositorio guardada exitosamente!";
                    AuditLogger::registrar('INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).');
                } else {
                    $_SESSION['mensaje_error'] = "No se pudo escribir en el archivo de configuración JSON.";
                    AuditLogger::registrar('WARNING', 'RepositorioPST', 'Fallo al Guardar Configuración', 'Error de permisos o escritura en el archivo JSON de configuración.');
                }
            } catch (Exception $e) {
                $_SESSION['mensaje_error'] = "Error al procesar la configuración: " . $e->getMessage();
                AuditLogger::registrar('WARNING', 'RepositorioPST', 'Fallo en Configuración', $e->getMessage());
            }
            
            // 2. Redirección para limpiar POST y activar JS en la recarga
            $redirectUrl = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '?ruta=configuracion-pst';
            header('Location: ' . $redirectUrl);
            exit;
        }

        $rolesDisponibles = [];
        try {
            $db = Connection::getInstance();
            if ($db) {
                $stmt = $db->query("SELECT r.id, r.nombre, p.nivel_privilegio 
                                    FROM roles r 
                                    JOIN privilegios p ON r.privilegio_id = p.privilegio_id 
                                    ORDER BY p.nivel_privilegio ASC");
                $rolesDisponibles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Throwable $e) {
            $rolesDisponibles = [];
        }

        $config = ConfigService::get();

        return [
            'config'                      => $config,
            'rolesDisponibles'            => $rolesDisponibles,
            'nivelesAcademicosDetallados' => $documentoModel->getNivelesAcademicosDetallados(),
            'tabActiva'                   => $_GET['tab'] ?? 'tabCitas',
            'mensaje'                     => $mensaje,
            'error'                       => $error
        ];
    }
}
