<?php
require_once CORE_PATH . 'Security/Auth.php';
require_once CORE_PATH . 'Security/Crypto.php';
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
            $origen = trim($_POST['origen'] ?? '');
            
            // Si la petición proviene del Gestor de Correos, solo alteramos las credenciales SMTP
            if ($origen === 'gestor-correos') {
                $config['smtp']['host'] = trim($_POST['smtp_host'] ?? '');
                $config['smtp']['port'] = (int)($_POST['smtp_port'] ?? 587);
                $config['smtp']['user'] = trim($_POST['smtp_user'] ?? '');
                $config['smtp']['from_email'] = trim($_POST['smtp_from'] ?? '');

                if (!empty($_POST['smtp_pass'])) {
                    $cleanPass = str_replace(' ', '', trim($_POST['smtp_pass']));
                    $config['smtp']['pass'] = Crypto::encrypt($cleanPass);
                }

                if (session_status() === PHP_SESSION_NONE) session_start();

                if (SystemConfigService::save($config)) {
                    AuditLogger::registrar('INFO', 'SuperAdmin', 'Configurar SMTP', 'Se actualizaron las credenciales del servidor SMTP institucional (cifrado AES-256).');
                    $_SESSION['mensaje_mail_exito'] = "Credenciales SMTP guardadas y aseguradas con cifrado AES-256 exitosamente.";
                } else {
                    $_SESSION['mensaje_mail_error'] = "Error al intentar escribir la configuración en storage/system_config.json.";
                }

                header("Location: gestor-correos?tab=tabConexionSmtp");
                exit;
            }
            
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
            
            // Entorno y Depuración (.env Shield)
            $appDebug = isset($_POST['app_debug']) && ($_POST['app_debug'] === 'true' || $_POST['app_debug'] === '1' || $_POST['app_debug'] === 'on');
            $appEnv = trim($_POST['app_env'] ?? 'production');
            
            if (class_exists('Env')) {
                Env::updateEnvFile([
                    'APP_DEBUG' => $appDebug ? 'true' : 'false',
                    'APP_ENV'   => $appEnv
                ]);
            }
            $config['entorno']['app_debug'] = $appDebug;
            $config['entorno']['app_env'] = $appEnv;

            // Solo actualizamos la contraseña si se escribió una nueva
            if (!empty($_POST['smtp_pass'])) {
                $cleanPass = str_replace(' ', '', trim($_POST['smtp_pass']));
                $config['smtp']['pass'] = Crypto::encrypt($cleanPass);
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
        require_once __DIR__ . '/../models/AdminUsuarioModel.php';

        $config = SystemConfigService::get();
        $plantillas = MailService::obtenerPlantillas();
        $plantillasPersonalizadas = MailService::obtenerPlantillasPersonalizadas();
        $mailLogs = MailService::obtenerLogs();

        $usuarioModel = new AdminUsuarioModel();
        $usuarios = $usuarioModel->obtenerTodosLosUsuarios();
        $roles = $usuarioModel->obtenerRoles();

        $mensajeExito = $_SESSION['mensaje_mail_exito'] ?? '';
        $mensajeError = $_SESSION['mensaje_mail_error'] ?? '';
        unset($_SESSION['mensaje_mail_exito'], $_SESSION['mensaje_mail_error']);

        return [
            'config' => $config,
            'plantillas' => $plantillas,
            'plantillasPersonalizadas' => $plantillasPersonalizadas,
            'mailLogs' => $mailLogs,
            'usuarios' => $usuarios,
            'roles' => $roles,
            'mensajeExito' => $mensajeExito,
            'mensajeError' => $mensajeError
        ];
    }

    public function guardarPlantillaPersonalizadaAction() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once CORE_PATH . 'Services/MailService.php';

            $nombre = trim($_POST['nombre'] ?? '');
            $asunto = trim($_POST['asunto'] ?? '');
            $cuerpoHtml = trim($_POST['cuerpo_html'] ?? '');

            if (empty($nombre) || empty($cuerpoHtml)) {
                $_SESSION['mensaje_mail_error'] = "Debe proporcionar un nombre y contenido para la plantilla personalizada.";
                header("Location: gestor-correos");
                exit;
            }

            if (MailService::guardarPlantillaPersonalizada($nombre, $asunto, $cuerpoHtml)) {
                AuditLogger::registrar('INFO', 'SuperAdmin', 'Guardar Plantilla Personalizada', "Se creó la plantilla personalizada: {$nombre}");
                $_SESSION['mensaje_mail_exito'] = "Plantilla '{$nombre}' guardada exitosamente en su catálogo reusable.";
            } else {
                $_SESSION['mensaje_mail_error'] = "Error al guardar la plantilla personalizada.";
            }

            header("Location: gestor-correos");
            exit;
        }
    }

    public function enviarCorreoDirecto() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once CORE_PATH . 'Services/MailService.php';
            require_once __DIR__ . '/../models/AdminUsuarioModel.php';

            $modoDestino = trim($_POST['modo_destino'] ?? 'individual');
            $asunto = trim($_POST['asunto'] ?? '');
            $mensaje = trim($_POST['mensaje'] ?? '');
            $usarLayout = isset($_POST['usar_layout']) && ($_POST['usar_layout'] === '1' || $_POST['usar_layout'] === 'on');

            if (empty($asunto) || empty($mensaje)) {
                $_SESSION['mensaje_mail_error'] = "Debe completar el asunto y el mensaje del correo.";
                header("Location: gestor-correos");
                exit;
            }

            // Si el cliente envió HTML formateado (editor enriquecido / builder), lo conservamos; de lo contrario nl2br
            if (preg_match('/<[a-z][\s\S]*>/i', $mensaje)) {
                $cuerpoHtml = $mensaje;
            } else {
                $cuerpoHtml = nl2br(htmlspecialchars($mensaje));
            }

            $usuarioModel = new AdminUsuarioModel();
            $destinatarios = [];

            if ($modoDestino === 'individual') {
                $usuariosIds = $_POST['usuario_ids'] ?? [];
                if (!is_array($usuariosIds) && !empty($_POST['usuario_id'])) {
                    $usuariosIds = [$_POST['usuario_id']];
                }
                
                $emailLibreRaw = trim($_POST['email_libre'] ?? '');
                
                // Si colocó correos libres separados por coma o espacio
                if (!empty($emailLibreRaw)) {
                    $listaCorreos = preg_split('/[\s,;]+/', $emailLibreRaw);
                    foreach ($listaCorreos as $em) {
                        $emClean = filter_var(trim($em), FILTER_SANITIZE_EMAIL);
                        if (!empty($emClean) && filter_var($emClean, FILTER_VALIDATE_EMAIL)) {
                            // Evitar duplicados por email
                            $alreadyIn = false;
                            foreach ($destinatarios as $d) {
                                if (strtolower($d['email']) === strtolower($emClean)) {
                                    $alreadyIn = true;
                                    break;
                                }
                            }
                            if (!$alreadyIn) {
                                $destinatarios[] = ['email' => $emClean, 'nombre' => 'Destinatario Directo'];
                            }
                        }
                    }
                }

                if (!empty($usuariosIds)) {
                    $todos = $usuarioModel->obtenerTodosLosUsuarios();
                    $mapaUsuarios = [];
                    foreach ($todos as $u) {
                        $mapaUsuarios[(int)$u['id']] = $u;
                    }

                    foreach ($usuariosIds as $uId) {
                        $uId = (int)$uId;
                        if (isset($mapaUsuarios[$uId]) && !empty($mapaUsuarios[$uId]['email'])) {
                            // Evitar duplicados por email
                            $alreadyIn = false;
                            foreach ($destinatarios as $d) {
                                if (strtolower($d['email']) === strtolower($mapaUsuarios[$uId]['email'])) {
                                    $alreadyIn = true;
                                    break;
                                }
                            }
                            if (!$alreadyIn) {
                                $destinatarios[] = ['email' => $mapaUsuarios[$uId]['email'], 'nombre' => $mapaUsuarios[$uId]['nombre_completo']];
                            }
                        }
                    }
                }
            } elseif ($modoDestino === 'rol') {
                $rolId = (int)($_POST['rol_id'] ?? 0);
                $todos = $usuarioModel->obtenerTodosLosUsuarios();
                foreach ($todos as $u) {
                    if (!empty($u['email'])) {
                        // Si se seleccionó rol específico o todos
                        if ($rolId === 0 || (isset($u['id_rol']) && (int)$u['id_rol'] === $rolId)) {
                            $destinatarios[] = ['email' => $u['email'], 'nombre' => $u['nombre_completo']];
                        }
                    }
                }
            }

            if (empty($destinatarios)) {
                $_SESSION['mensaje_mail_error'] = "No se encontraron destinatarios válidos para la selección actual. Por favor verifique el correo o usuario seleccionado.";
                header("Location: gestor-correos");
                exit;
            }

            $enviados = 0;
            $fallidos = 0;

            foreach ($destinatarios as $dest) {
                $res = MailService::enviar($dest['email'], $dest['nombre'], $asunto, $cuerpoHtml, strip_tags($mensaje), !$usarLayout);
                if ($res['exito']) {
                    $enviados++;
                } else {
                    $fallidos++;
                }
            }

            AuditLogger::registrar('INFO', 'SuperAdmin', 'Envío Directo de Correo', "Se enviaron {$enviados} correos exitosamente ({$fallidos} fallidos). Asunto: {$asunto}");

            if ($fallidos === 0) {
                $_SESSION['mensaje_mail_exito'] = "Correo enviado exitosamente a {$enviados} destinatario(s).";
            } elseif ($enviados > 0) {
                $_SESSION['mensaje_mail_exito'] = "Proceso completado: {$enviados} enviados correctamente, {$fallidos} con fallas.";
            } else {
                $_SESSION['mensaje_mail_error'] = "No se pudo enviar el correo a ningún destinatario ({$fallidos} fallidos). Verifique la conectividad del servidor SMTP o revise el Historial de Envíos.";
            }

            header("Location: gestor-correos");
            exit;
        }
    }

    public function probarSmtp() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once CORE_PATH . 'Services/MailService.php';

            $emailPrueba = filter_var(trim($_POST['email_prueba'] ?? ''), FILTER_SANITIZE_EMAIL);
            $templateKey = trim($_POST['template_key'] ?? '');

            if (empty($emailPrueba)) {
                $_SESSION['mensaje_mail_error'] = "Debe proporcionar una dirección de correo válida para realizar la prueba.";
                header("Location: gestor-correos?tab=tabConexionSmtp");
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
                    // $async = false para obtener resultado inmediato y fidedigno
                    $resultado = MailService::enviarEvento($templateKey, $emailPrueba, $varsPrueba, 'Usuario de Prueba', false);
                } else {
                    $resultado = ['exito' => false, 'mensaje' => 'La plantilla elegida para la prueba no existe.'];
                }
            } else {
                $asunto = "Prueba de Conexión SMTP - CIIDI UPTTMBI";
                $cuerpo = "
                    <h2 style='color:#121a3e; margin-top:0;'>Test de Conectividad SMTP Exitoso</h2>
                    <p>Este es un correo de prueba enviado desde el panel de SuperAdmin del <strong>Sistema CIIDI UPTTMBI</strong>.</p>
                    <div style='background-color:#ecfdf5; border-left:4px solid #10b981; padding:12px; border-radius:4px; margin:15px 0; color:#065f46;'>
                        <strong>Estado del Servidor:</strong> Enlace SMTP con TLS/SSL y cifrado AES-256 funcionando al 100%.
                    </div>
                    <p style='font-size:0.85rem; color:#64748b;'>Fecha de emisión: " . date('d/m/Y H:i:s') . "</p>
                ";
                $resultado = MailService::enviar($emailPrueba, 'Administrador de Prueba', $asunto, $cuerpo);
            }

            if ($resultado['exito']) {
                AuditLogger::registrar('INFO', 'SuperAdmin', 'Prueba SMTP Exitosa', "Correo enviado a {$emailPrueba}");
                $_SESSION['mensaje_mail_exito'] = $resultado['mensaje'] ?? 'Correo de prueba enviado exitosamente.';
            } else {
                $errorMsg = $resultado['mensaje'] ?? 'Error desconocido al enviar el correo de prueba.';
                if (!empty($resultado['error_detalle'])) {
                    $errorMsg .= ' [Diagnóstico: ' . $resultado['error_detalle'] . ']';
                }
                AuditLogger::registrar('ERROR', 'SuperAdmin', 'Falla de Prueba SMTP', $errorMsg);
                $_SESSION['mensaje_mail_error'] = $errorMsg;
            }

            header("Location: gestor-correos?tab=tabConexionSmtp");
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