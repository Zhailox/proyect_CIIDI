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
            
            // Accesos Dinámicos
            if (isset($_POST['accesos']) && is_array($_POST['accesos'])) {
                foreach ($_POST['accesos'] as $slug => $niveles) {
                    if ($slug === 'superadmin' || $slug === 'super_admin') continue; // Blindaje absoluto
                    
                    if ($slug === 'autenticacion') {
                        // Forzamos desde el backend que jamás supere 998
                        $config['accesos_modulos'][$slug]['publico'] = min(998, (int)($niveles['publico'] ?? 998));
                    } else {
                        // Creación y actualización automática de cualquier otro módulo
                        $config['accesos_modulos'][$slug]['publico'] = (int)($niveles['publico'] ?? 999);
                        $config['accesos_modulos'][$slug]['admin'] = (int)($niveles['admin'] ?? 1);
                    }
                }
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