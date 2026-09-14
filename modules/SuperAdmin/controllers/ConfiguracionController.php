<?php
require_once CORE_PATH . 'Security/Auth.php';
require_once __DIR__ . '/../services/SystemConfigService.php'; // Asegúrate de que el nombre coincida con tu archivo

class ConfiguracionController {
    
    public function index() {
        Auth::requierePrivilegioMinimo(0);
        
        $config = SystemConfigService::get();
        $mensajeExito = $_SESSION['mensaje_config_exito'] ?? '';
        $mensajeError = $_SESSION['mensaje_config_error'] ?? '';
        unset($_SESSION['mensaje_config_exito'], $_SESSION['mensaje_config_error']);
        
        return [
            'config' => $config,
            'mensajeExito' => $mensajeExito,
            'mensajeError' => $mensajeError
        ];
    }

    public function guardar() {
        Auth::requierePrivilegioMinimo(0);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $config = SystemConfigService::get();
            
            // Paginación
            $config['paginacion']['logs'] = max(1, (int)($_POST['pag_logs'] ?? 50));
            $config['paginacion']['usuarios'] = max(1, (int)($_POST['pag_usuarios'] ?? 15));
            $config['paginacion']['docentes'] = max(1, (int)($_POST['pag_docentes'] ?? 15));
            
            // Seguridad
            $config['seguridad']['timeout_minutos'] = max(5, (int)($_POST['seg_timeout'] ?? 120));
            $config['seguridad']['intentos_login'] = max(1, (int)($_POST['seg_intentos'] ?? 5));
            
            // SMTP
            $config['smtp']['host'] = trim($_POST['smtp_host'] ?? '');
            $config['smtp']['port'] = (int)($_POST['smtp_port'] ?? 587);
            $config['smtp']['user'] = trim($_POST['smtp_user'] ?? '');
            $config['smtp']['from_email'] = trim($_POST['smtp_from'] ?? '');
            $modulosEstandar = ['cursos', 'articulos', 'pst', 'lineas', 'investigacion', 'autenticacion', 'vinculacion_empresarial'];
            foreach ($modulosEstandar as $mod) {
                $config['accesos_modulos'][$mod]['publico'] = (int)($_POST["acceso_{$mod}_publico"] ?? 999);
                $config['accesos_modulos'][$mod]['admin']   = (int)($_POST["acceso_{$mod}_admin"] ?? 0);
            }
            
            
            // Solo actualizamos la contraseña si se escribió una nueva
            if (!empty($_POST['smtp_pass'])) {
                $config['smtp']['pass'] = trim($_POST['smtp_pass']);
            }

            if (session_status() === PHP_SESSION_NONE) session_start();
            
            if (SystemConfigService::save($config)) {
                AuditLogger::registrar('WARNING', 'SuperAdmin', 'Modificar Variables Globales', 'Se actualizaron las variables de entorno del sistema.');
                $_SESSION['mensaje_config_exito'] = "Variables de entorno guardadas correctamente.";
            } else {
                $_SESSION['mensaje_config_error'] = "Error al intentar escribir en el archivo JSON.";
            }
            
            header("Location: configuracion-sistema");
            exit;
        }
    }
}