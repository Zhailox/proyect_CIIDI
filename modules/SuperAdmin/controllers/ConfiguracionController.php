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

    public function gestorCorreos() {
        Auth::requierePrivilegioMinimo(0);

        require_once CORE_PATH . 'Services/MailService.php';

        $config = SystemConfigService::get();
        $plantillas = MailService::obtenerPlantillas();

        $mensajeExito = $_SESSION['mensaje_mail_exito'] ?? '';
        $mensajeError = $_SESSION['mensaje_mail_error'] ?? '';
        unset($_SESSION['mensaje_mail_exito'], $_SESSION['mensaje_mail_error']);

        return [
            'config' => $config,
            'plantillas' => $plantillas,
            'mensajeExito' => $mensajeExito,
            'mensajeError' => $mensajeError
        ];
    }

    public function probarSmtp() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once CORE_PATH . 'Services/MailService.php';

            $emailPrueba = filter_var(trim($_POST['email_prueba'] ?? ''), FILTER_SANITIZE_EMAIL);
            $templateKey = trim($_POST['template_key'] ?? '');

            if (empty($emailPrueba)) {
                $_SESSION['mensaje_mail_error'] = "Debe proporcionar una dirección de correo válida para realizar la prueba.";
                header("Location: gestor-correos");
                exit;
            }

            if (!empty($templateKey)) {
                $plantillas = MailService::obtenerPlantillas();
                if (isset($plantillas[$templateKey])) {
                    $tpl = $plantillas[$templateKey];
                    $varsPrueba = [];
                    foreach (($tpl['variables'] ?? []) as $v) {
                        $varsPrueba[$v] = "[VALOR_PRUEBA_{$v}]";
                    }
                    $resultado = MailService::enviarEvento($templateKey, $emailPrueba, $varsPrueba, 'Usuario de Prueba');
                } else {
                    $resultado = ['exito' => false, 'mensaje' => 'La plantilla elegida para la prueba no existe.'];
                }
            } else {
                $asunto = "Prueba de Conexión SMTP - CIIDI UPTTMBI";
                $cuerpo = "
                    <h2 style='color:#121a3e; margin-top:0;'>Test de Conectividad SMTP Exitoso</h2>
                    <p>Este es un correo de prueba enviado desde el panel de SuperAdmin del <strong>Sistema CIIDI UPTTMBI</strong>.</p>
                    <div style='background-color:#ecfdf5; border-left:4px solid #10b981; padding:12px; border-radius:4px; margin:15px 0; color:#065f46;'>
                        <strong>Estado del Servidor:</strong> Enlace SMTP con TLS/SSL funcionando al 100%.
                    </div>
                    <p style='font-size:0.85rem; color:#64748b;'>Fecha de emisión: " . date('d/m/Y H:i:s') . "</p>
                ";
                $resultado = MailService::enviar($emailPrueba, 'Administrador de Prueba', $asunto, $cuerpo);
            }

            if ($resultado['exito']) {
                AuditLogger::registrar('INFO', 'SuperAdmin', 'Prueba SMTP Exitosa', "Correo enviado a {$emailPrueba}");
                $_SESSION['mensaje_mail_exito'] = $resultado['mensaje'];
            } else {
                AuditLogger::registrar('ERROR', 'SuperAdmin', 'Falla de Prueba SMTP', $resultado['mensaje']);
                $_SESSION['mensaje_mail_error'] = $resultado['mensaje'];
            }

            header("Location: gestor-correos");
            exit;
        }
    }

    public function guardarPlantillaCorreo() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once CORE_PATH . 'Services/MailService.php';

            $key = trim($_POST['template_key'] ?? '');
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $asunto = trim($_POST['asunto'] ?? '');
            $cuerpoHtml = trim($_POST['cuerpo_html'] ?? '');
            $activo = isset($_POST['activo']) && ($_POST['activo'] === '1' || $_POST['activo'] === 'on');
            $plantillaCompleta = isset($_POST['plantilla_completa']) && ($_POST['plantilla_completa'] === '1' || $_POST['plantilla_completa'] === 'on');

            $plantillas = MailService::obtenerPlantillas();

            if (!isset($plantillas[$key])) {
                $_SESSION['mensaje_mail_error'] = "La plantilla especificada '{$key}' no existe en el catálogo.";
                header("Location: gestor-correos");
                exit;
            }

            if ($nombre !== '') {
                $plantillas[$key]['nombre'] = $nombre;
            }
            if ($descripcion !== '') {
                $plantillas[$key]['descripcion'] = $descripcion;
            }
            $plantillas[$key]['asunto'] = $asunto;
            $plantillas[$key]['cuerpo_html'] = $cuerpoHtml;
            $plantillas[$key]['activo'] = $activo;
            $plantillas[$key]['plantilla_completa'] = $plantillaCompleta;

            if (MailService::guardarPlantillas($plantillas)) {
                AuditLogger::registrar('WARNING', 'SuperAdmin', 'Modificar Plantilla de Correo', "Se actualizó la plantilla: {$key}");
                $_SESSION['mensaje_mail_exito'] = "Plantilla '{$plantillas[$key]['nombre']}' guardada correctamente.";
            } else {
                $_SESSION['mensaje_mail_error'] = "Error al intentar escribir la plantilla en disco.";
            }

            header("Location: gestor-correos");
            exit;
        }
    }

    public function guardarLayoutCorreo() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $htmlWrapper = trim($_POST['html_wrapper'] ?? '');

            $config = SystemConfigService::get();
            $config['email_layout']['html_wrapper'] = $htmlWrapper;

            if (SystemConfigService::save($config)) {
                AuditLogger::registrar('WARNING', 'SuperAdmin', 'Modificar Layout de Correo Base', 'Se actualizó la plantilla base HTML institucional.');
                $_SESSION['mensaje_mail_exito'] = "Layout Institucional Base actualizado correctamente.";
            } else {
                $_SESSION['mensaje_mail_error'] = "Error al guardar el Layout Institucional.";
            }

            header("Location: gestor-correos");
            exit;
        }
    }
}