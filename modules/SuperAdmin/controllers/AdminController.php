<?php
// modules/SuperAdmin/controllers/AdminController.php

require_once CORE_PATH . 'Security/Auth.php';
require_once __DIR__ . '/../models/AdminDashboardModel.php';
require_once __DIR__ . '/../services/BackupService.php';

class AdminController {
    
    private $dashboardModel;

    public function __construct() {
        $this->dashboardModel = new AdminDashboardModel();
    }

    public function mostrarPanelAdministrativo() {
        Auth::requierePrivilegioMinimo(3);
        try {
            $datosGraficas = $this->dashboardModel->obtenerEstadisticas();
            $listaTablas = $this->dashboardModel->obtenerTablasSistema();
            $telemetria = $this->dashboardModel->obtenerTelemetriaServidor();
            $ultimosLogs = $this->dashboardModel->obtenerUltimasAccionesAudit(5);
            
            return [
                'stats' => $datosGraficas,
                'tablas' => $listaTablas,
                'telemetria' => $telemetria,
                'ultimosLogs' => $ultimosLogs
            ];
        } catch (Throwable $e) {
            AuditLogger::registrar('CRITICAL', 'SuperAdmin', 'Error Panel Admin', $e->getMessage());
            return [
                'stats' => [], 'tablas' => [], 'telemetria' => [], 'ultimosLogs' => []
            ];
        }
    }

    /**
     * Test de Diagnóstico Integrado del Core del Sistema
     */
    public function testearCore() {
        Auth::requierePrivilegioMinimo(3);
        header('Content-Type: application/json');

        $inicio = microtime(true);
        $pruebas = [];
        $saludGlobal = true;

        // 1. Diagnóstico de la Base de Datos PostgreSQL
        try {
            $tDbInicio = microtime(true);
            if (file_exists(CORE_PATH . 'Database/Connection.php')) {
                require_once CORE_PATH . 'Database/Connection.php';
            }
            $db = Connection::getInstance();
            $stmt = $db->query("SELECT version() AS version_db, NOW() AS timestamp_db");
            $resDb = $stmt->fetch(PDO::FETCH_ASSOC);
            $tDbDuracion = round((microtime(true) - $tDbInicio) * 1000, 2);

            $pruebas[] = [
                'modulo'   => 'Base de Datos PostgreSQL',
                'estado'   => 'OK',
                'detalles' => "Conexión estable. Latencia: {$tDbDuracion} ms",
                'info'     => substr($resDb['version_db'] ?? 'PostgreSQL Active', 0, 45)
            ];
        } catch (Throwable $e) {
            $saludGlobal = false;
            $pruebas[] = [
                'modulo'   => 'Base de Datos PostgreSQL',
                'estado'   => 'ERROR',
                'detalles' => 'Fallo de conexión a la BD: ' . $e->getMessage(),
                'info'     => 'Sin respuesta'
            ];
        }

        // 2. Diagnóstico del Kernel & Rutas Core
        try {
            if (defined('CORE_PATH') && file_exists(CORE_PATH . 'Security/Auth.php')) {
                $pruebas[] = [
                    'modulo'   => 'Kernel & Sistema de Enrutamiento',
                    'estado'   => 'OK',
                    'detalles' => 'Núcleo cargado correctamente. Constantes de rutas e interfaces verificadas.',
                    'info'     => 'CORE_PATH activo'
                ];
            } else {
                $saludGlobal = false;
                $pruebas[] = [
                    'modulo'   => 'Kernel & Enrutamiento',
                    'estado'   => 'ERROR',
                    'detalles' => 'No se encuentra CORE_PATH o Auth.php',
                    'info'     => 'Rutas corruptas'
                ];
            }
        } catch (Throwable $e) {
            $saludGlobal = false;
            $pruebas[] = [
                'modulo'   => 'Kernel & Enrutamiento',
                'estado'   => 'ERROR',
                'detalles' => $e->getMessage(),
                'info'     => 'Fallo de ejecución'
            ];
        }

        // 3. Permisos de Almacenamiento (Storage System)
        try {
            $storageDir = defined('STORAGE_PATH') ? STORAGE_PATH : __DIR__ . '/../../../storage/';
            if (is_dir($storageDir) && is_writable($storageDir)) {
                $bytesLibres = @disk_free_space($storageDir);
                $gbLibres = $bytesLibres ? round($bytesLibres / (1024 * 1024 * 1024), 2) . ' GB' : 'N/A';
                $pruebas[] = [
                    'modulo'   => 'Sistema de Archivos y Storage',
                    'estado'   => 'OK',
                    'detalles' => "Directorio storage/ con permisos de escritura. Espacio libre: {$gbLibres}",
                    'info'     => "Escritura OK ({$gbLibres})"
                ];
            } else {
                $saludGlobal = false;
                $pruebas[] = [
                    'modulo'   => 'Sistema de Archivos y Storage',
                    'estado'   => 'ERROR',
                    'detalles' => 'Directorio storage/ no existe o no tiene permisos de escritura.',
                    'info'     => 'Sin permisos'
                ];
            }
        } catch (Throwable $e) {
            $saludGlobal = false;
            $pruebas[] = [
                'modulo'   => 'Sistema de Archivos y Storage',
                'estado'   => 'ERROR',
                'detalles' => $e->getMessage(),
                'info'     => 'Fallo de almacenamiento'
            ];
        }

        // 4. Módulos Core Vitales (Autenticacion & SuperAdmin)
        try {
            $modAutenticacion = MODULES_PATH . 'Autenticacion/index.php';
            $modSuperAdmin    = MODULES_PATH . 'SuperAdmin/index.php';

            if (file_exists($modAutenticacion) && file_exists($modSuperAdmin)) {
                $pruebas[] = [
                    'modulo'   => 'Módulos Indispensables (Core)',
                    'estado'   => 'OK',
                    'detalles' => 'Módulos Autenticacion y SuperAdmin presentes y funcionales en disco.',
                    'info'     => '2 / 2 Core Módulos Activos'
                ];
            } else {
                $saludGlobal = false;
                $pruebas[] = [
                    'modulo'   => 'Módulos Indispensables (Core)',
                    'estado'   => 'ERROR',
                    'detalles' => 'Falta alguno de los módulos vitales (Autenticacion o SuperAdmin).',
                    'info'     => 'Faltan módulos vitales'
                ];
            }
        } catch (Throwable $e) {
            $saludGlobal = false;
            $pruebas[] = [
                'modulo'   => 'Módulos Indispensables (Core)',
                'estado'   => 'ERROR',
                'detalles' => $e->getMessage(),
                'info'     => 'Error en comprobación'
            ];
        }

        $duracionTotal = round((microtime(true) - $inicio) * 1000, 2);
        $memoriaUsoMB  = round(memory_get_usage(true) / 1024 / 1024, 2);

        AuditLogger::registrar($saludGlobal ? 'INFO' : 'CRITICAL', 'SuperAdmin', 'Test Respuesta Core', "Diagnóstico del Core ejecutado en {$duracionTotal}ms. Memoria: {$memoriaUsoMB} MB. Estado: " . ($saludGlobal ? 'SALUDABLE' : 'CON FALLOS'));

        echo json_encode([
            'status'        => $saludGlobal ? 'success' : 'error',
            'saludable'     => $saludGlobal,
            'duracion_ms'   => $duracionTotal,
            'memoria_mb'    => $memoriaUsoMB,
            'php_version'   => PHP_VERSION,
            'pruebas'       => $pruebas,
            'message'       => $saludGlobal ? "Diagnóstico completado: El Núcleo del Sistema responde al 100% ({$duracionTotal} ms)" : 'Se detectaron anomalías en el diagnóstico de salud del Core'
        ]);
        exit;
    }

    public function mostrarMantenimiento() {
        Auth::requierePrivilegioMinimo(3);
        try {
            $listaTablas = $this->dashboardModel->obtenerTablasSistema();
            return ['tablas' => $listaTablas];
        } catch (Throwable $e) {
            AuditLogger::registrar('CRITICAL', 'SuperAdmin', 'Error Mantenimiento View', $e->getMessage());
            return ['tablas' => []];
        }
    }

    public function generarBackup() {
        Auth::requierePrivilegioMinimo(3);
        try {
            $res = BackupService::crearBackup(true, false);
            if (session_status() === PHP_SESSION_NONE) session_start();
            if ($res['exito']) {
                $_SESSION['mensaje_admin_exito'] = $res['mensaje'];
            } else {
                $_SESSION['mensaje_admin_error'] = $res['mensaje'];
            }
        } catch (Throwable $e) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            AuditLogger::registrar('CRITICAL', 'SuperAdmin', 'Excepción Backup BD', $e->getMessage());
            $_SESSION['mensaje_admin_error'] = "Excepción en el sistema: " . $e->getMessage();
        }

        header("Location: gestor-mantenimiento");
        exit;
    }

    public function generarBackupEsquema() {
        Auth::requierePrivilegioMinimo(3);
        try {
            $res = BackupService::crearBackup(true, true);
            if (session_status() === PHP_SESSION_NONE) session_start();
            if ($res['exito']) {
                $_SESSION['mensaje_admin_exito'] = "Respaldo del ESQUEMA creado de forma segura: " . $res['nombre'];
            } else {
                $_SESSION['mensaje_admin_error'] = $res['mensaje'];
            }
        } catch (Throwable $e) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            AuditLogger::registrar('CRITICAL', 'SuperAdmin', 'Excepción Esquema DDL', $e->getMessage());
            $_SESSION['mensaje_admin_error'] = "Excepción en el sistema: " . $e->getMessage();
        }

        header("Location: gestor-mantenimiento");
        exit;
    }

    public function generarBackupTabla() {
        Auth::requierePrivilegioMinimo(3);
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header("Location: gestor-mantenimiento");
                exit;
            }

            $tabla = trim($_POST['nombre_tabla'] ?? '');

            if (empty($tabla) || !preg_match('/^[a-zA-Z0-9_]+$/', $tabla)) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_admin_error'] = "Error: El nombre de la tabla contiene caracteres inválidos.";
                header("Location: gestor-mantenimiento");
                exit;
            }

            $res = BackupService::crearBackup(true, false, $tabla);
            if (session_status() === PHP_SESSION_NONE) session_start();
            if ($res['exito']) {
                $_SESSION['mensaje_admin_exito'] = "Respaldo de la TABLA '{$tabla}' creado: " . $res['nombre'];
            } else {
                $_SESSION['mensaje_admin_error'] = $res['mensaje'];
            }
        } catch (Throwable $e) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            AuditLogger::registrar('CRITICAL', 'SuperAdmin', 'Excepción Backup Tabla', $e->getMessage());
            $_SESSION['mensaje_admin_error'] = "Excepción en el sistema: " . $e->getMessage();
        }

        header("Location: gestor-mantenimiento");
        exit;
    }

    public function verificarRespaldo() {
        Auth::requierePrivilegioMinimo(3);
        try {
            $archivo = $_GET['archivo'] ?? '';
            $nombreLimpio = basename($archivo);
            $backupDir = defined('STORAGE_PATH') ? STORAGE_PATH . 'backups/' : __DIR__ . '/../../../storage/backups/';
            $rutaCompleta = $backupDir . $nombreLimpio;

            $res = BackupService::verificarIntegridad($rutaCompleta);

            if (session_status() === PHP_SESSION_NONE) session_start();

            if ($res['valido']) {
                $_SESSION['mensaje_admin_exito'] = "✅ Verificación exitosa para '{$nombreLimpio}': " . $res['detalles'];
            } else {
                $_SESSION['mensaje_admin_error'] = "❌ Fallo de integridad en '{$nombreLimpio}': " . $res['detalles'];
            }
        } catch (Throwable $e) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_admin_error'] = "Error al verificar archivo: " . $e->getMessage();
        }

        header("Location: gestor-mantenimiento");
        exit;
    }

    public function ejecutarLimpiezaRespaldos() {
        Auth::requierePrivilegioMinimo(3);
        try {
            $dias = !empty($_GET['dias']) ? max(1, (int)$_GET['dias']) : 30;
            $purgados = BackupService::limpiarRespaldosAntiguos($dias);

            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_admin_exito'] = "Auto-limpieza completada: {$purgados} respaldos con más de {$dias} días de antigüedad eliminados.";
        } catch (Throwable $e) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_admin_error'] = "Error durante la auto-limpieza: " . $e->getMessage();
        }

        header("Location: gestor-mantenimiento");
        exit;
    }

    public function descargarBackup() {
        Auth::requierePrivilegioMinimo(3);
        try {
            $archivo = $_GET['archivo'] ?? '';
            $nombreLimpio = basename($archivo);
            $backupDir = defined('STORAGE_PATH') ? STORAGE_PATH . 'backups/' : __DIR__ . '/../../../storage/backups/';
            $rutaCompleta = $backupDir . $nombreLimpio;

            if (!empty($nombreLimpio) && file_exists($rutaCompleta)) {
                $isGzip = str_ends_with($nombreLimpio, '.gz');
                header('Content-Description: File Transfer');
                header('Content-Type: ' . ($isGzip ? 'application/gzip' : 'application/x-sql'));
                header('Content-Disposition: attachment; filename="' . $nombreLimpio . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($rutaCompleta));
                readfile($rutaCompleta);
                exit;
            }
        } catch (Throwable $e) {
            AuditLogger::registrar('CRITICAL', 'SuperAdmin', 'Excepción Descargar Backup', $e->getMessage());
        }

        header("Location: gestor-mantenimiento");
        exit;
    }

    public function eliminarBackup() {
        Auth::requierePrivilegioMinimo(3);
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $archivo = $_POST['archivo'] ?? '';
                $nombreLimpio = basename($archivo);
                $backupDir = defined('STORAGE_PATH') ? STORAGE_PATH . 'backups/' : __DIR__ . '/../../../storage/backups/';
                $rutaCompleta = $backupDir . $nombreLimpio;

                if (!empty($nombreLimpio) && file_exists($rutaCompleta)) {
                    unlink($rutaCompleta);
                    AuditLogger::registrar('WARNING', 'SuperAdmin', 'Eliminar Backup', "Respaldo eliminado: {$nombreLimpio}");
                    if (session_status() === PHP_SESSION_NONE) session_start();
                    $_SESSION['mensaje_admin_exito'] = "Respaldo '{$nombreLimpio}' eliminado con éxito.";
                }
            }
        } catch (Throwable $e) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            AuditLogger::registrar('CRITICAL', 'SuperAdmin', 'Excepción Eliminar Backup', $e->getMessage());
            $_SESSION['mensaje_admin_error'] = "Error al eliminar respaldo: " . $e->getMessage();
        }

        header("Location: gestor-mantenimiento");
        exit;
    }

    public function alternarMantenimiento() {
        Auth::requierePrivilegioMinimo(3);
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $archivo = __DIR__ . '/../../../storage/maintenance.json';
                $actual = file_exists($archivo) ? json_decode(file_get_contents($archivo), true) : ['activo' => false];
                $nuevoEstado = !$actual['activo'];
                $mensaje = trim($_POST['mensaje'] ?? '');
                $minutosMantenimiento = (int)($_POST['minutos_programados'] ?? 0);

                $fechaFin = $minutosMantenimiento > 0 
                    ? date('Y-m-d H:i:s', strtotime("+{$minutosMantenimiento} minutes"))
                    : null;

                $data = [
                    'activo' => $nuevoEstado,
                    'mensaje' => $mensaje,
                    'fecha_fin' => $fechaFin,
                    'minutos' => $minutosMantenimiento
                ];

                file_put_contents($archivo, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

                if ($nuevoEstado) {
                    $this->cerrarSesionesNoAdmin();
                }

                AuditLogger::registrar('WARNING', 'SuperAdmin', 'Alternar Mantenimiento', "Modo Mantenimiento cambiado a: " . ($nuevoEstado ? 'ACTIVADO' : 'DESACTIVADO'));

                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_admin_exito'] = $nuevoEstado
                    ? "Modo Mantenimiento ACTIVADO. Plataforma restringida."
                    : "Modo Mantenimiento DESACTIVADO. Sistema abierto a usuarios.";
            }
        } catch (Throwable $e) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            AuditLogger::registrar('CRITICAL', 'SuperAdmin', 'Excepción Alternar Mantenimiento', $e->getMessage());
            $_SESSION['mensaje_admin_error'] = "Error al modificar modo mantenimiento: " . $e->getMessage();
        }

        header("Location: gestor-mantenimiento");
        exit;
    }

    private function cerrarSesionesNoAdmin() {
        $savePath = session_save_path() ?: (ini_get('session.save_path') ?: sys_get_temp_dir());
        $archivos = glob($savePath . '/sess_*');
        foreach ($archivos as $archivo) {
            $contenido = file_get_contents($archivo);
            preg_match('/nivel_privilegio\|i:(\d+)/', $contenido, $matches);
            if (isset($matches[1]) && (int)$matches[1] < 3) {
                @unlink($archivo);
            }
        }
    }

    public function restaurarBackup() {
        Auth::requierePrivilegioMinimo(3);
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $rutaArchivoRestaurar = null;
                $tempSqlUnzipped = null;

                if (isset($_FILES['backup_file']) && $_FILES['backup_file']['error'] === UPLOAD_ERR_OK) {
                    $rutaArchivoRestaurar = $_FILES['backup_file']['tmp_name'];
                    $origName = $_FILES['backup_file']['name'];
                } elseif (!empty($_POST['archivo_guardado'])) {
                    $nombreLimpio = basename($_POST['archivo_guardado']);
                    $backupDir = defined('STORAGE_PATH') ? STORAGE_PATH . 'backups/' : __DIR__ . '/../../../storage/backups/';
                    $rutaGuardada = $backupDir . $nombreLimpio;
                    if (file_exists($rutaGuardada)) {
                        $rutaArchivoRestaurar = $rutaGuardada;
                        $origName = $nombreLimpio;
                    }
                }

                if ($rutaArchivoRestaurar && file_exists($rutaArchivoRestaurar)) {
                    // Verificar integridad (Dry-run verification)
                    $verif = BackupService::verificarIntegridad($rutaArchivoRestaurar);
                    if (!$verif['valido']) {
                        if (session_status() === PHP_SESSION_NONE) session_start();
                        $_SESSION['mensaje_admin_error'] = "Restauración cancelada. El archivo no superó la prueba de integridad: " . $verif['detalles'];
                        header("Location: gestor-mantenimiento");
                        exit;
                    }

                    // Si está comprimido en GZIP, descomprimir temporalmente para psql
                    $rutaParaPsql = $rutaArchivoRestaurar;
                    if (!empty($verif['esGzip'])) {
                        $tempSqlUnzipped = sys_get_temp_dir() . '/restore_temp_' . time() . '.sql';
                        $zp = gzopen($rutaArchivoRestaurar, 'rb');
                        $fp = fopen($tempSqlUnzipped, 'wb');
                        while (!gzeof($zp)) {
                            fwrite($fp, gzread($zp, 8192));
                        }
                        gzclose($zp);
                        fclose($fp);
                        $rutaParaPsql = $tempSqlUnzipped;
                    }

                    $creds = Connection::getCredentials();
                    $psqlPath = Connection::getPsqlPath();

                    putenv("PGPASSWORD={$creds['pass']}");
                    $comando = "{$psqlPath} -h {$creds['host']} -p {$creds['port']} -U {$creds['user']} -d {$creds['db']} -f \"{$rutaParaPsql}\" 2>&1";

                    $salida = [];
                    $codigo_retorno = 0;
                    exec($comando, $salida, $codigo_retorno);
                    putenv("PGPASSWORD=");

                    if ($tempSqlUnzipped && file_exists($tempSqlUnzipped)) {
                        @unlink($tempSqlUnzipped);
                    }

                    if (session_status() === PHP_SESSION_NONE) session_start();

                    if ($codigo_retorno === 0) {
                        AuditLogger::registrar('WARNING', 'SuperAdmin', 'Restaurar BD', "Base de datos restaurada exitosamente.");
                        $_SESSION['mensaje_admin_exito'] = "Base de datos restaurada correctamente. Integridad verificada antes del proceso.";
                    } else {
                        $errDetalle = implode(' ', $salida);
                        AuditLogger::registrar('CRITICAL', 'SuperAdmin', 'Fallo Restauración BD', $errDetalle);
                        $_SESSION['mensaje_admin_error'] = "Error al restaurar la BD. " . ($errDetalle ?: "Código: {$codigo_retorno}");
                    }
                } else {
                    if (session_status() === PHP_SESSION_NONE) session_start();
                    $_SESSION['mensaje_admin_error'] = "No se proporcionó un archivo de respaldo válido.";
                }
            }
        } catch (Throwable $e) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            AuditLogger::registrar('CRITICAL', 'SuperAdmin', 'Excepción Restaurar BD', $e->getMessage());
            $_SESSION['mensaje_admin_error'] = "Error inesperado al restaurar: " . $e->getMessage();
        }

        header("Location: gestor-mantenimiento");
        exit;
    }
}
?>