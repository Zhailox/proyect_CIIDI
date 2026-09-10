<?php
// modules/SuperAdmin/controllers/AdminController.php

// IMPORTANTE: Importamos la clase Auth para que el controlador la reconozca
require_once CORE_PATH . 'Security/Auth.php';
require_once __DIR__ . '/../models/AdminDashboardModel.php'; // Incluimos el modelo

class AdminController {
    
    // (Opcional) Si en el futuro necesitas usar AdminUsuarioModel aquí, instáncialo en un constructor
    
    private $dashboardModel;

    public function __construct() {
        $this->dashboardModel = new AdminDashboardModel();
    }

    public function mostrarPanelAdministrativo() {
        Auth::requierePrivilegioMinimo(3);
        
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
    }

    public function mostrarMantenimiento() {
        Auth::requierePrivilegioMinimo(3);
        
        $listaTablas = $this->dashboardModel->obtenerTablasSistema();
        
        return [
            'tablas' => $listaTablas
        ];
    }

    public function generarBackup() {
        Auth::requierePrivilegioMinimo(3);

        $creds = Connection::getCredentials();
        $pgDumpPath = Connection::getPgDumpPath();

        $directorioRespaldos = __DIR__ . '/../../../storage/backups/';
        if (!is_dir($directorioRespaldos)) {
            mkdir($directorioRespaldos, 0777, true);
        }
        
        $fecha = date('Y-m-d_H-i-s');
        $nombreArchivo = "backup_ciidi_{$fecha}.sql";
        $rutaCompleta = $directorioRespaldos . $nombreArchivo;

        putenv("PGPASSWORD={$creds['pass']}");
        $comando = "{$pgDumpPath} -h {$creds['host']} -p {$creds['port']} -U {$creds['user']} -F p --clean --inserts -d {$creds['db']} -f \"{$rutaCompleta}\" 2>&1";

        $salida = [];
        $codigo_retorno = 0;
        exec($comando, $salida, $codigo_retorno);
        putenv("PGPASSWORD=");

        if ($codigo_retorno === 0 && file_exists($rutaCompleta)) {
            AuditLogger::registrar('INFO', 'SuperAdmin', 'Generar Respaldo BD', "Copia de seguridad completa creada: {$nombreArchivo}");
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_admin_exito'] = "Respaldo creado de forma segura: storage/backups/" . $nombreArchivo;
            header("Location: sudoadmin");
            exit;
        } else {
            AuditLogger::registrar('CRITICAL', 'SuperAdmin', 'Fallo Generación Respaldo BD', "Código de error: {$codigo_retorno}");
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_admin_error'] = "Error Fatal al generar el Backup. Código: {$codigo_retorno}";
            header("Location: sudoadmin");
            exit;
        }
    }

    public function generarBackupEsquema() {
        Auth::requierePrivilegioMinimo(3);

        $creds = Connection::getCredentials();
        $pgDumpPath = Connection::getPgDumpPath();

        $directorioRespaldos = __DIR__ . '/../../../storage/backups/';
        if (!is_dir($directorioRespaldos)) {
            mkdir($directorioRespaldos, 0777, true);
        }
        
        $fecha = date('Y-m-d_H-i-s');
        $nombreArchivo = "esquema_ciidi_{$fecha}.sql";
        $rutaCompleta = $directorioRespaldos . $nombreArchivo;

        putenv("PGPASSWORD={$creds['pass']}");
        $comando = "{$pgDumpPath} -h {$creds['host']} -p {$creds['port']} -U {$creds['user']} -F p -s -d {$creds['db']} -f \"{$rutaCompleta}\" 2>&1";

        $salida = [];
        $codigo_retorno = 0;
        exec($comando, $salida, $codigo_retorno);
        putenv("PGPASSWORD=");

        if ($codigo_retorno === 0 && file_exists($rutaCompleta)) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_admin_exito'] = "Respaldo del ESQUEMA creado en: storage/backups/" . $nombreArchivo;
            header("Location: sudoadmin");
            exit;
        } else {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_admin_error'] = "Error Fatal al generar el esquema. Código: {$codigo_retorno}";
            header("Location: sudoadmin");
            exit;
        }
    }

    public function generarBackupTabla() {
        Auth::requierePrivilegioMinimo(3);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: sudoadmin");
            exit;
        }

        $tabla = trim($_POST['nombre_tabla']);

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $tabla)) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_admin_error'] = "Error: El nombre de la tabla contiene caracteres inválidos.";
            header("Location: sudoadmin");
            exit;
        }

        $creds = Connection::getCredentials();
        $pgDumpPath = Connection::getPgDumpPath();

        $directorioRespaldos = __DIR__ . '/../../../storage/backups/';
        if (!is_dir($directorioRespaldos)) {
            mkdir($directorioRespaldos, 0777, true);
        }
        
        $fecha = date('Y-m-d_H-i-s');
        $nombreArchivo = "tabla_{$tabla}_{$fecha}.sql";
        $rutaCompleta = $directorioRespaldos . $nombreArchivo;

        putenv("PGPASSWORD={$creds['pass']}");
        $comando = "{$pgDumpPath} -h {$creds['host']} -p {$creds['port']} -U {$creds['user']} -F p --clean --inserts -t {$tabla} -d {$creds['db']} -f \"{$rutaCompleta}\" 2>&1";

        $salida = [];
        $codigo_retorno = 0;
        exec($comando, $salida, $codigo_retorno);
        putenv("PGPASSWORD=");

        if ($codigo_retorno === 0 && file_exists($rutaCompleta)) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_admin_exito'] = "Respaldo de la TABLA '{$tabla}' creado en: storage/backups/" . $nombreArchivo;
            header("Location: sudoadmin");
            exit;
        } else {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_admin_error'] = "Fallo interno al exportar la tabla '{$tabla}'.";
            header("Location: sudoadmin");
            exit;
        }
    }

    public function descargarBackup() {
        Auth::requierePrivilegioMinimo(3);

        $archivo = $_GET['archivo'] ?? '';
        $nombreLimpio = basename($archivo);
        $rutaCompleta = __DIR__ . '/../../../storage/backups/' . $nombreLimpio;

        if (!empty($nombreLimpio) && file_exists($rutaCompleta)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $nombreLimpio . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($rutaCompleta));
            readfile($rutaCompleta);
            exit;
        }

        header("Location: sudoadmin");
        exit;
    }

    public function eliminarBackup() {
        Auth::requierePrivilegioMinimo(3);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $archivo = $_POST['archivo'] ?? '';
            $nombreLimpio = basename($archivo);
            $rutaCompleta = __DIR__ . '/../../../storage/backups/' . $nombreLimpio;

            if (!empty($nombreLimpio) && file_exists($rutaCompleta)) {
                unlink($rutaCompleta);
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_admin_exito'] = "Respaldo '{$nombreLimpio}' eliminado con éxito.";
            }

            header("Location: sudoadmin");
            exit;
        }
    }

    public function alternarMantenimiento() {
        Auth::requierePrivilegioMinimo(3);

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

            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_admin_exito'] = $nuevoEstado
                ? "Modo Mantenimiento ACTIVADO. Los estudiantes no podrán acceder."
                : "Modo Mantenimiento DESACTIVADO. Sistema abierto.";

            header("Location: sudoadmin");
            exit;
        }
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 1. O bien subieron un archivo vía POST o seleccionaron un archivo guardado
            $rutaArchivoRestaurar = null;

            if (isset($_FILES['backup_file']) && $_FILES['backup_file']['error'] === UPLOAD_ERR_OK) {
                $rutaArchivoRestaurar = $_FILES['backup_file']['tmp_name'];
            } elseif (!empty($_POST['archivo_guardado'])) {
                $nombreLimpio = basename($_POST['archivo_guardado']);
                $rutaGuardada = __DIR__ . '/../../../storage/backups/' . $nombreLimpio;
                if (file_exists($rutaGuardada)) {
                    $rutaArchivoRestaurar = $rutaGuardada;
                }
            }

            if ($rutaArchivoRestaurar && file_exists($rutaArchivoRestaurar)) {
                $creds = Connection::getCredentials();
                $psqlPath = Connection::getPsqlPath();

                putenv("PGPASSWORD={$creds['pass']}");
                $comando = "{$psqlPath} -h {$creds['host']} -p {$creds['port']} -U {$creds['user']} -d {$creds['db']} -f \"{$rutaArchivoRestaurar}\" 2>&1";

                $salida = [];
                $codigo_retorno = 0;
                exec($comando, $salida, $codigo_retorno);
                putenv("PGPASSWORD=");

                if (session_status() === PHP_SESSION_NONE) session_start();

                if ($codigo_retorno === 0) {
                    $_SESSION['mensaje_admin_exito'] = "Base de datos restaurada correctamente.";
                } else {
                    $_SESSION['mensaje_admin_error'] = "Error al restaurar la BD. Código: {$codigo_retorno}";
                }
            } else {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_admin_error'] = "No se proporcionó un archivo de respaldo válido.";
            }

            header("Location: sudoadmin");
            exit;
        }
    }
}
?>