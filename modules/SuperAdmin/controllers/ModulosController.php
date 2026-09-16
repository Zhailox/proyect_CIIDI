<?php
require_once CORE_PATH . 'Security/Auth.php';

class ModulosController {
    
    private $archivo_config;
    private $archivo_legacy;

    public function __construct() {
        $this->archivo_config = __DIR__ . '/../../../storage/config_system.json';
        $this->archivo_legacy = __DIR__ . '/../../../storage/modules.json';
    }

    private function obtenerConfiguracion(): array {
        if (file_exists($this->archivo_config)) {
            $config = json_decode(file_get_contents($this->archivo_config), true);
            if (is_array($config)) return $config;
        }

        // Migración transparente si solo existe modules.json
        $config = ['modulos' => [], 'rutas' => []];
        if (file_exists($this->archivo_legacy)) {
            $legacy = json_decode(file_get_contents($this->archivo_legacy), true);
            if (is_array($legacy)) {
                foreach ($legacy as $mod => $est) {
                    $config['modulos'][$mod] = ['estado' => $est];
                }
            }
        }
        return $config;
    }

    private function guardarConfiguracion(array $config): bool {
        $directorio = dirname($this->archivo_config);
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }
        return (bool) file_put_contents($this->archivo_config, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function index() {
        Auth::requierePrivilegioMinimo(0); 

        $config = $this->obtenerConfiguracion();
        $estadosModulos = $config['modulos'] ?? [];
        $estadosRutas = $config['rutas'] ?? [];

        $modulosReales = [];
        $carpetas = array_diff(scandir(MODULES_PATH), array('.', '..'));
        
        foreach ($carpetas as $carpeta) {
            $ruta_index = MODULES_PATH . $carpeta . '/index.php';
            
            if (file_exists($ruta_index)) {
                $claseModulo = $carpeta . 'Module';
                if (class_exists($claseModulo)) {
                    $modulo = new $claseModulo();
                } else {
                    $modulo = require_once $ruta_index;
                }
                
                if ($modulo instanceof ModuleContract) {
                    $esCore = in_array($carpeta, ['Autenticacion', 'SuperAdmin']);
                    
                    $modConfig = $estadosModulos[$carpeta] ?? ['estado' => 'online'];
                    $estadoModulo = $esCore ? 'core' : ($modConfig['estado'] ?? 'online');
                    
                    $rutasModulo = $modulo->getRutas();
                    $rutasDetalle = [];

                    foreach ($rutasModulo as $claveRuta => $infoRuta) {
                        $configRuta = $estadosRutas[$claveRuta] ?? ['estado' => 'online', 'mensaje' => '', 'rol_minimo' => 1, 'accesos' => 0];
                        $rutasDetalle[] = [
                            'clave'       => $claveRuta,
                            'titulo'      => $infoRuta['titulo'] ?? $claveRuta,
                            'controlador' => $infoRuta['controlador'] ?? 'Directo',
                            'metodo'      => $infoRuta['metodo'] ?? 'N/A',
                            'estado'      => $configRuta['estado'] ?? 'online', // online | solo_lectura | offline
                            'mensaje'     => $configRuta['mensaje'] ?? '',
                            'rol_minimo'  => $configRuta['rol_minimo'] ?? 1,
                            'accesos'     => $configRuta['accesos'] ?? 0
                        ];
                    }

                    $modulosReales[] = [
                        'id'                 => $carpeta,
                        'nombre'             => method_exists($modulo, 'getNombre') ? $modulo->getNombre() : $carpeta,
                        'descripcion'        => method_exists($modulo, 'getDescripcion') ? $modulo->getDescripcion() : 'Módulo del sistema.',
                        'estado'             => $estadoModulo,
                        'es_core'            => $esCore,
                        'dependencias_count' => method_exists($modulo, 'getDependencias') ? count($modulo->getDependencias()) : 0,
                        'nombres_dependencias' => method_exists($modulo, 'getDependencias') ? implode(', ', $modulo->getDependencias()) : '',
                        'icono'              => $esCore ? 'ph-bold ph-shield-check' : 'ph-bold ph-plugs-connected',
                        'rutas'              => $rutasDetalle
                    ];
                }
            }
        }

        return ['modulosDelSistema' => $modulosReales];
    }

    public function alternarEstado() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $carpeta = $_POST['modulo_id'] ?? '';
            $nuevoEstado = $_POST['nuevo_estado'] ?? 'online';
            $esAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

            if (in_array($carpeta, ['Autenticacion', 'SuperAdmin'])) {
                if ($esAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'error', 'message' => 'El núcleo del sistema no se puede modificar.']);
                    exit;
                }
                header("Location: gestor-modulos");
                exit;
            }

            $config = $this->obtenerConfiguracion();
            $config['modulos'][$carpeta] = ['estado' => $nuevoEstado];
            $this->guardarConfiguracion($config);

            // Obtener rutas del módulo para informar al frontend en AJAX
            $rutasModulo = [];
            $ruta_index_mod = MODULES_PATH . $carpeta . '/index.php';
            if (file_exists($ruta_index_mod)) {
                $claseMod = $carpeta . 'Module';
                $modInst = class_exists($claseMod) ? new $claseMod() : require_once $ruta_index_mod;
                if ($modInst instanceof ModuleContract) {
                    $rutasModulo = array_keys($modInst->getRutas());
                }
            }

            AuditLogger::registrar('WARNING', 'SuperAdmin', 'Alternar Estado Módulo', "Módulo {$carpeta} cambiado a estado: {$nuevoEstado}");

            if ($esAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'nuevo_estado' => $nuevoEstado,
                    'modulo' => $carpeta,
                    'rutas' => $rutasModulo,
                    'message' => "Módulo '{$carpeta}' invertido a {$nuevoEstado}"
                ]);
                exit;
            }

            header("Location: gestor-modulos");
            exit;
        }
    }

    public function alternarEstadoRuta() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $claveRuta   = $_POST['ruta_clave'] ?? '';
            $nuevoEstado = $_POST['nuevo_estado'] ?? 'online'; // online | solo_lectura | offline
            $mensaje     = trim($_POST['mensaje_personalizado'] ?? '');
            $rolMinimo   = isset($_POST['rol_minimo']) ? (int)$_POST['rol_minimo'] : null;
            $esAjax      = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

            if (!empty($claveRuta)) {
                $config = $this->obtenerConfiguracion();
                
                if (!isset($config['rutas'][$claveRuta])) {
                    $config['rutas'][$claveRuta] = [];
                }
                
                $config['rutas'][$claveRuta]['estado'] = $nuevoEstado;
                if ($mensaje !== '') {
                    $config['rutas'][$claveRuta]['mensaje'] = $mensaje;
                }
                if ($rolMinimo !== null) {
                    $config['rutas'][$claveRuta]['rol_minimo'] = $rolMinimo;
                }

                $this->guardarConfiguracion($config);
                AuditLogger::registrar('WARNING', 'SuperAdmin', 'Feature Flag de Ruta', "Ruta '?ruta={$claveRuta}' actualizada: Estado {$nuevoEstado}" . ($rolMinimo !== null ? ", Rol Mínimo {$rolMinimo}" : ''));
            }

            if ($esAjax) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'ruta' => $claveRuta, 'nuevo_estado' => $nuevoEstado, 'message' => "Ruta '?ruta={$claveRuta}' actualizada correctamente"]);
                exit;
            }

            header("Location: gestor-modulos");
            exit;
        }
    }

    public function detalleModulo() {
        Auth::requierePrivilegioMinimo(0);

        $idModulo = $_GET['id'] ?? $_GET['modulo'] ?? '';
        if (empty($idModulo)) {
            header("Location: gestor-modulos");
            exit;
        }

        $configSystem = $this->obtenerConfiguracion();
        $estadosModulos = $configSystem['modulos'] ?? [];
        $estadosRutas = $configSystem['rutas'] ?? [];

        $ruta_index = MODULES_PATH . $idModulo . '/index.php';
        if (!file_exists($ruta_index)) {
            header("Location: gestor-modulos");
            exit;
        }

        $modulo = require_once $ruta_index;
        if ($modulo === true) {
            $claseModulo = $idModulo . 'Module';
            if (class_exists($claseModulo)) {
                $modulo = new $claseModulo();
            }
        }

        if (!($modulo instanceof ModuleContract)) {
            header("Location: gestor-modulos");
            exit;
        }

        $esCore = in_array($idModulo, ['Autenticacion', 'SuperAdmin']);
        $modConfig = $estadosModulos[$idModulo] ?? ['estado' => 'online'];
        $estadoModulo = $esCore ? 'core' : ($modConfig['estado'] ?? 'online');

        $rutasModulo = $modulo->getRutas();
        $rutasDetalle = [];
        $rutasActivasCount = 0;
        $rutasRestringidasCount = 0;
        $totalAccesosModulo = 0;

        foreach ($rutasModulo as $claveRuta => $infoRuta) {
            $configRuta = $estadosRutas[$claveRuta] ?? ['estado' => 'online', 'mensaje' => '', 'rol_minimo' => 1, 'accesos' => 0];
            $st = $configRuta['estado'] ?? 'online';
            $accesos = (int)($configRuta['accesos'] ?? 0);
            $totalAccesosModulo += $accesos;

            if ($st === 'online') {
                $rutasActivasCount++;
            } else {
                $rutasRestringidasCount++;
            }

            $rutasDetalle[] = [
                'clave'       => $claveRuta,
                'titulo'      => $infoRuta['titulo'] ?? $claveRuta,
                'controlador' => $infoRuta['controlador'] ?? 'Directo',
                'metodo'      => $infoRuta['metodo'] ?? 'N/A',
                'vista'       => isset($infoRuta['vista']) ? basename($infoRuta['vista']) : 'N/A',
                'estado'      => $st,
                'mensaje'     => $configRuta['mensaje'] ?? '',
                'rol_minimo'  => (int)($configRuta['rol_minimo'] ?? 1),
                'accesos'     => $accesos
            ];
        }

        // Buscar archivo de configuración JSON propio del módulo
        $configFile = null;
        $configJsonData = null;
        $foundJsons = glob(MODULES_PATH . $idModulo . '/*.json');
        if (!empty($foundJsons)) {
            $configFile = $foundJsons[0];
        } else {
            $storageDir = defined('STORAGE_PATH') ? STORAGE_PATH : (defined('BASE_PATH') ? BASE_PATH . '/storage/' : __DIR__ . '/../../../storage/');
            $storageMeta = $storageDir . strtolower($idModulo) . '_meta.json';
            if (file_exists($storageMeta)) {
                $configFile = $storageMeta;
            }
        }

        if ($configFile && file_exists($configFile)) {
            $raw = file_get_contents($configFile);
            $configJsonData = json_decode($raw, true);
        }

        // Obtener la lista dinámica de roles registrados en la BD PostgreSQL (Agrupados por nivel de privilegio único)
        $rolesDinamicos = [
            ['nivel' => 0, 'nombre' => 'Público / Libres (Sin Autenticación)']
        ];

        try {
            if (file_exists(CORE_PATH . 'Database/Connection.php')) {
                require_once CORE_PATH . 'Database/Connection.php';
                $db = Connection::getInstance();
                $stmt = $db->query("
                    SELECT p.nivel_privilegio, string_agg(r.nombre, ' / ') AS nombres_roles
                    FROM privilegios p
                    LEFT JOIN roles r ON r.privilegio_id = p.privilegio_id
                    GROUP BY p.nivel_privilegio
                    ORDER BY p.nivel_privilegio ASC
                ");
                $rolesDb = $stmt->fetchAll();
                foreach ($rolesDb as $rDb) {
                    $nivel = (int)$rDb['nivel_privilegio'];
                    $nombreRol = !empty($rDb['nombres_roles']) ? $rDb['nombres_roles'] : "Nivel {$nivel}";
                    $rolesDinamicos[] = [
                        'nivel'  => $nivel,
                        'nombre' => htmlspecialchars($nombreRol) . " (Nivel " . $nivel . ")"
                    ];
                }
            }
        } catch (Throwable $e) {
            // Fallback si la BD no responde
            $rolesDinamicos = [
                ['nivel' => 0, 'nombre' => 'Público / Todos'],
                ['nivel' => 1, 'nombre' => 'Profesor / Usuario Registrado (Nivel 1)'],
                ['nivel' => 2, 'nombre' => 'Bibliotecario / Gestor (Nivel 2)'],
                ['nivel' => 3, 'nombre' => 'Administrador / SuperAdmin (Nivel 3)']
            ];
        }

        // Obtener Logs de auditoría asociados a este módulo
        $auditLogsModulo = [];
        $archivoAudit = CORE_PATH . '../storage/system_audit.json';
        if (file_exists($archivoAudit)) {
            $allAudit = json_decode(file_get_contents($archivoAudit), true) ?: [];
            foreach ($allAudit as $item) {
                if (isset($item['modulo']) && strcasecmp($item['modulo'], $idModulo) === 0) {
                    $auditLogsModulo[] = $item;
                }
            }
        }

        $moduloData = [
            'id'                   => $idModulo,
            'nombre'               => method_exists($modulo, 'getNombre') ? $modulo->getNombre() : $idModulo,
            'descripcion'          => method_exists($modulo, 'getDescripcion') ? $modulo->getDescripcion() : 'Módulo del sistema.',
            'estado'               => $estadoModulo,
            'es_core'              => $esCore,
            'dependencias'         => method_exists($modulo, 'getDependencias') ? $modulo->getDependencias() : [],
            'icono'                => $esCore ? 'ph-bold ph-shield-check' : 'ph-bold ph-plugs-connected',
            'rutas'                => $rutasDetalle,
            'total_rutas'          => count($rutasDetalle),
            'rutas_activas'        => $rutasActivasCount,
            'rutas_restringidas'   => $rutasRestringidasCount,
            'total_accesos'        => $totalAccesosModulo,
            'configFile'           => $configFile ? basename($configFile) : null,
            'configFilePath'       => $configFile,
            'configJsonData'       => $configJsonData,
            'rolesDinamicos'       => $rolesDinamicos,
            'auditLogs'            => array_slice($auditLogsModulo, 0, 50)
        ];

        return [
            'moduloData' => $moduloData
        ];
    }

    public function guardarConfiguracionEspecifica() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idModulo = $_POST['modulo_id'] ?? '';
            $configFilePath = $_POST['config_file_path'] ?? '';
            $rawJson = $_POST['raw_config_json'] ?? '';
            $esAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

            if (!empty($configFilePath) && !empty($rawJson)) {
                $decoded = json_decode($rawJson, true);
                if (is_array($decoded)) {
                    $dir = dirname($configFilePath);
                    if (!is_dir($dir)) mkdir($dir, 0777, true);

                    file_put_contents($configFilePath, json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                    AuditLogger::registrar('INFO', $idModulo, 'Config Módulo Guardada', "Configuración de {$idModulo} actualizada en " . basename($configFilePath));

                    if ($esAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(['status' => 'success', 'message' => 'Configuración del módulo guardada exitosamente.']);
                        exit;
                    }
                } else {
                    if ($esAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(['status' => 'error', 'message' => 'El formato JSON enviado no es válido.']);
                        exit;
                    }
                }
            }

            header("Location: detalle-modulo?id=" . urlencode($idModulo));
            exit;
        }
    }

    /**
     * Probador sintáctico / Health-Check de Ruta
     */
    public function testearRuta() {
        Auth::requierePrivilegioMinimo(0);
        header('Content-Type: application/json');

        $moduloId  = $_POST['modulo_id'] ?? '';
        $claveRuta = $_POST['clave_ruta'] ?? '';

        if (empty($moduloId) || empty($claveRuta)) {
            echo json_encode(['status' => 'error', 'message' => 'Faltan parámetros requeridos']);
            exit;
        }

        $inicio = microtime(true);
        $rutaIndex = MODULES_PATH . $moduloId . '/index.php';
        if (!file_exists($rutaIndex)) {
            echo json_encode(['status' => 'error', 'message' => 'Archivo index.php del módulo no existe.']);
            exit;
        }

        $modulo = require_once $rutaIndex;
        if ($modulo === true) {
            $claseModulo = $moduloId . 'Module';
            if (class_exists($claseModulo)) {
                $modulo = new $claseModulo();
            }
        }

        if (!($modulo instanceof ModuleContract)) {
            echo json_encode(['status' => 'error', 'message' => 'El módulo no implementa ModuleContract.']);
            exit;
        }

        $rutas = $modulo->getRutas();
        if (!isset($rutas[$claveRuta])) {
            echo json_encode(['status' => 'error', 'message' => "La ruta '{$claveRuta}' no está declarada por el módulo."]);
            exit;
        }

        $info = $rutas[$claveRuta];
        $controladorPath = $info['controlador_path'] ?? null;
        $claseControlador = $info['controlador'] ?? null;
        $metodo = $info['metodo'] ?? null;
        $vistaPath = $info['vista'] ?? null;

        $diagnostico = [];
        $saludable = true;

        if ($controladorPath) {
            if (file_exists($controladorPath)) {
                $diagnostico[] = "Controlador encontrado: " . basename($controladorPath);
                require_once $controladorPath;
                if ($claseControlador && class_exists($claseControlador)) {
                    $diagnostico[] = "Clase {$claseControlador} instanciable.";
                    if ($metodo && method_exists($claseControlador, $metodo)) {
                        $diagnostico[] = "Método {$metodo}() verificado correctamente.";
                    } else {
                        $saludable = false;
                        $diagnostico[] = "Error: El método {$metodo}() NO existe en {$claseControlador}.";
                    }
                } else {
                    $saludable = false;
                    $diagnostico[] = "Error: La clase {$claseControlador} NO existe en el controlador.";
                }
            } else {
                $saludable = false;
                $diagnostico[] = "Error: Archivo de controlador no existe: " . basename($controladorPath);
            }
        }

        if ($vistaPath) {
            if (file_exists($vistaPath)) {
                $diagnostico[] = "Vista renderizable: " . basename($vistaPath);
            } else {
                $saludable = false;
                $diagnostico[] = "Error: Archivo de vista NO existe: " . basename($vistaPath);
            }
        }

        $duracion = round((microtime(true) - $inicio) * 1000, 2);

        AuditLogger::registrar($saludable ? 'INFO' : 'ERROR', $moduloId, 'Health Check Ruta', "Test en ruta '?ruta={$claveRuta}': " . ($saludable ? "OK ({$duracion}ms)" : "FALLÓ"));

        echo json_encode([
            'status'     => $saludable ? 'success' : 'error',
            'saludable'  => $saludable,
            'duracion'   => $duracion . ' ms',
            'diagnostico'=> $diagnostico,
            'message'    => $saludable ? "Ruta '?ruta={$claveRuta}' 100% Funcional ({$duracion}ms)" : "Fallo en diagnóstico de ruta '?ruta={$claveRuta}'"
        ]);
        exit;
    }

    /**
     * Limpieza de Caché y Archivos Temporales del Módulo
     */
    public function purgarCacheModulo() {
        Auth::requierePrivilegioMinimo(0);
        header('Content-Type: application/json');

        $moduloId = $_POST['modulo_id'] ?? '';
        if (empty($moduloId)) {
            echo json_encode(['status' => 'error', 'message' => 'ID de módulo no proporcionado.']);
            exit;
        }

        $storageDir = defined('STORAGE_PATH') ? STORAGE_PATH : __DIR__ . '/../../../storage/';
        $cacheDir   = $storageDir . 'cache/';
        
        $eliminados = 0;
        $liberadoBytes = 0;

        if (is_dir($cacheDir)) {
            $archivos = glob($cacheDir . '*');
            foreach ($archivos as $arch) {
                if (is_file($arch) && (strpos(strtolower(basename($arch)), strtolower($moduloId)) !== false || strpos(strtolower(basename($arch)), 'temp_') !== false)) {
                    $liberadoBytes += filesize($arch);
                    if (unlink($arch)) {
                        $eliminados++;
                    }
                }
            }
        }

        $liberadoKB = round($liberadoBytes / 1024, 2);

        AuditLogger::registrar('INFO', $moduloId, 'Purgado de Caché', "Se eliminaron {$eliminados} archivos de caché ({$liberadoKB} KB liberados).");

        echo json_encode([
            'status'   => 'success',
            'message'  => "Caché de '{$moduloId}' purgada: {$eliminados} archivos eliminados ({$liberadoKB} KB liberados).",
            'archivos' => $eliminados,
            'liberado' => $liberadoKB . ' KB'
        ]);
        exit;
    }

    /**
     * Exportación de la Configuración Global del Sistema
     */
    public function exportarConfiguracion() {
        Auth::requierePrivilegioMinimo(0);
        $config = $this->obtenerConfiguracion();

        $filename = "config_system_" . date('Ymd_His') . ".json";
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Importación / Restablecimiento de Configuración del Sistema
     */
    public function importarConfiguracion() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $esAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

            if (isset($_FILES['config_file']) && $_FILES['config_file']['error'] === UPLOAD_ERR_OK) {
                $content = file_get_contents($_FILES['config_file']['tmp_name']);
                $decoded = json_decode($content, true);

                if (is_array($decoded) && (isset($decoded['modulos']) || isset($decoded['rutas']))) {
                    $this->guardarConfiguracion($decoded);
                    AuditLogger::registrar('WARNING', 'SuperAdmin', 'Importar Configuración', "Configuración global restaurada desde archivo importado.");

                    if ($esAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(['status' => 'success', 'message' => 'Configuración del sistema restaurada e importada exitosamente.']);
                        exit;
                    }
                } else {
                    if ($esAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(['status' => 'error', 'message' => 'El archivo subido no es una configuración válida del sistema.']);
                        exit;
                    }
                }
            }
        }
        header("Location: gestor-modulos");
        exit;
    }
}
