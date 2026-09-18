<?php
// core/Services/MailService.php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../modules/SuperAdmin/services/SystemConfigService.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class MailService {

    private static string $templatesPath = __DIR__ . '/../../storage/email_templates.json';
    private static string $customTemplatesPath = __DIR__ . '/../../storage/custom_templates.json';

    /**
     * Devuelve todas las plantillas registradas en el sistema.
     */
    public static function obtenerPlantillas(): array {
        if (file_exists(self::$templatesPath)) {
            return json_decode(file_get_contents(self::$templatesPath), true) ?: [];
        }
        return [];
    }

    /**
     * Devuelve las plantillas de usuario personalizadas reutilizables.
     */
    public static function obtenerPlantillasPersonalizadas(): array {
        if (file_exists(self::$customTemplatesPath)) {
            return json_decode(file_get_contents(self::$customTemplatesPath), true) ?: [];
        }
        return [];
    }

    /**
     * Guarda una plantilla personalizada en storage/custom_templates.json
     */
    public static function guardarPlantillaPersonalizada(string $nombre, string $asunto, string $cuerpoHtml): bool {
        $plantillas = self::obtenerPlantillasPersonalizadas();
        $key = 'custom_' . time() . '_' . rand(100,999);
        
        $plantillas[$key] = [
            'key' => $key,
            'nombre' => $nombre,
            'asunto' => $asunto,
            'cuerpo_html' => $cuerpoHtml,
            'fecha' => date('Y-m-d H:i:s')
        ];

        return @file_put_contents(self::$customTemplatesPath, json_encode($plantillas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }

    private static string $logsPath = __DIR__ . '/../../storage/mail_logs.json';

    /**
     * Registra un correo enviado en el historial de logs (storage/mail_logs.json).
     */
    public static function registrarLog(string $destinoEmail, string $destinoNombre, string $asunto, bool $exito, string $detalle = '', string $tipo = 'Directo'): void {
        $logs = [];
        if (file_exists(self::$logsPath)) {
            $logs = json_decode(file_get_contents(self::$logsPath), true) ?: [];
        }

        $nuevoLog = [
            'id' => uniqid('mail_'),
            'fecha' => date('Y-m-d H:i:s'),
            'destino_email' => $destinoEmail,
            'destino_nombre' => $destinoNombre,
            'asunto' => $asunto,
            'exito' => $exito,
            'tipo' => $tipo,
            'detalle' => $detalle
        ];

        array_unshift($logs, $nuevoLog); // El más reciente primero

        // Mantener como máximo los últimos 200 registros de envío
        if (count($logs) > 200) {
            $logs = array_slice($logs, 0, 200);
        }

        @file_put_contents(self::$logsPath, json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene los logs de historial de envíos de correo.
     */
    public static function obtenerLogs(): array {
        if (file_exists(self::$logsPath)) {
            return json_decode(file_get_contents(self::$logsPath), true) ?: [];
        }
        return [];
    }

    /**
     * Guarda cambios realizados a las plantillas en el storage.
     */
    public static function guardarPlantillas(array $plantillas): bool {
        return file_put_contents(self::$templatesPath, json_encode($plantillas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }

    /**
     * Envía un correo electrónico desacoplado procesando una plantilla/evento del sistema.
     */
    public static function enviarEvento(string $templateKey, string $destinoEmail, array $variables = [], string $destinoNombre = 'Usuario'): array {
        $plantillas = self::obtenerPlantillas();

        if (!isset($plantillas[$templateKey])) {
            return ['exito' => false, 'mensaje' => "No se encontró la plantilla para el evento: {$templateKey}"];
        }

        $tpl = $plantillas[$templateKey];

        // Si la plantilla está inhabilitada por el administrador, se omite el envío de forma limpia
        if (isset($tpl['activo']) && $tpl['activo'] === false) {
            return ['exito' => true, 'mensaje' => "La plantilla '{$templateKey}' se encuentra inhabilitada por el administrador. Envío omitido."];
        }

        $asunto = $tpl['asunto'] ?? 'Notificación del Sistema CIIDI';
        $cuerpoHtml = $tpl['cuerpo_html'] ?? '';

        // Reemplazar variables dinámicas en el Asunto y Cuerpo
        foreach ($variables as $clave => $valor) {
            $placeholder = '{' . strtoupper($clave) . '}';
            $asunto = str_replace($placeholder, $valor, $asunto);
            $cuerpoHtml = str_replace($placeholder, $valor, $cuerpoHtml);
        }

        $esCompleta = !empty($tpl['plantilla_completa']);
        return self::enviar($destinoEmail, $destinoNombre, $asunto, $cuerpoHtml, '', $esCompleta);
    }

    /**
     * Envía un correo electrónico utilizando la configuración SMTP institucional global.
     */
    public static function enviar(string $destinoEmail, string $destinoNombre, string $asunto, string $contenidoHtml, string $textoPlano = '', bool $esCompleta = false): array {
        $configSmtp = SystemConfigService::get('smtp', []);
        
        $host     = trim($configSmtp['host'] ?? '');
        $port     = (int)($configSmtp['port'] ?? 587);
        $user     = trim($configSmtp['user'] ?? '');
        $pass     = trim($configSmtp['pass'] ?? '');
        $fromEmail= trim($configSmtp['from_email'] ?? $user);

        if (empty($fromEmail)) {
            $fromEmail = 'no-reply@upttmbi.edu.ve';
        }

        $mail = new PHPMailer(true);

        try {
            $mail->CharSet = 'UTF-8';

            $mail->isSMTP();

            // Si hay un host configurado en el panel, lo usamos; de lo contrario fallback a localhost
            $mail->Host       = !empty($host) ? $host : 'localhost';
            $mail->SMTPAuth   = !empty($user) && !empty($pass);
            $mail->Username   = $user;
            $mail->Password   = $pass;
            $mail->SMTPSecure = ($port === 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $port;
            $mail->Timeout    = 10;

            $mail->setFrom($fromEmail, 'Sistema CIIDI - UPTTMBI');
            $mail->addAddress($destinoEmail, $destinoNombre);

            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body    = $esCompleta ? $contenidoHtml : self::generarPlantillaInstitucional($asunto, $contenidoHtml);
            $mail->AltBody = !empty($textoPlano) ? $textoPlano : strip_tags($contenidoHtml);

            $mail->send();
            self::registrarLog($destinoEmail, $destinoNombre, $asunto, true, 'Enviado correctamente vía SMTP');
            return ['exito' => true, 'mensaje' => 'Correo enviado exitosamente a ' . htmlspecialchars($destinoEmail)];
        } catch (Exception $e) {
            self::registrarLog($destinoEmail, $destinoNombre, $asunto, false, $mail->ErrorInfo);
            return ['exito' => false, 'mensaje' => 'Error al enviar correo vía SMTP: ' . $mail->ErrorInfo];
        }
    }

    /**
     * Plantilla HTML institucional responsive adaptada a estilo.md & UPTTMBI
     */
    public static function generarPlantillaInstitucional(string $titulo, string $cuerpo): string {
        $year = date('Y');
        $layoutConfig = SystemConfigService::get('email_layout', null);

        if (!empty($layoutConfig) && !empty($layoutConfig['html_wrapper'])) {
            $html = $layoutConfig['html_wrapper'];
            $html = str_replace('{TITULO}', $titulo, $html);
            $html = str_replace('{CUERPO}', $cuerpo, $html);
            $html = str_replace('{YEAR}', $year, $html);
            return $html;
        }

        return "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>{$titulo}</title>
        </head>
        <body style='margin:0; padding:0; background-color:#f8fafc; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; color:#334155; -webkit-font-smoothing: antialiased;'>
            <table role='presentation' width='100%' cellspacing='0' cellpadding='0' style='background-color:#f8fafc; padding: 30px 15px;'>
                <tr>
                    <td align='center'>
                        <table role='presentation' width='100%' style='max-width: 600px; background-color:#ffffff; border-radius: 12px; overflow:hidden; box-shadow: 0 10px 25px -5px rgba(18, 26, 62, 0.08); border: 1px solid #e2e8f0;'>
                            
                            <!-- HEADER INSTITUCIONAL CIIDI -->
                            <tr>
                                <td style='background: linear-gradient(135deg, rgb(80, 89, 132) 0%, rgb(112, 144, 203) 100%); padding: 32px 25px; text-align: center; color: #ffffff;'>
                                    <div style='font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; color: #ffffff; opacity: 0.9; margin-bottom: 8px;'>
                                        UPTTMBI &bull; VALERA, TRUJILLO
                                    </div>
                                    <h1 style='margin:0; font-size: 24px; font-weight: 800; letter-spacing: 0.5px; color: #ffffff; text-transform: uppercase;'>
                                        SISTEMA INTEGRAL CIIDI
                                    </h1>
                                    <div style='font-size: 12px; color: #ffffff; opacity: 0.85; margin-top: 4px; font-weight: 500;'>
                                        Centro de Investigación, Innovación y Desarrollo Informático
                                    </div>
                                </td>
                            </tr>

                            <!-- CUERPO DE NOTIFICACIÓN -->
                            <tr>
                                <td style='padding: 35px 30px; background-color: #ffffff;'>
                                    {$cuerpo}
                                </td>
                            </tr>

                            <!-- FOOTER INSTITUCIONAL -->
                            <tr>
                                <td style='background-color: #f8fafc; padding: 24px 30px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 12px; color: #64748b;'>
                                    <p style='margin: 0 0 6px 0; font-weight: 700; color: #121a3e;'>
                                        Universidad Politécnica Territorial del Estado Trujillo &quot;Mario Briceño Iragorry&quot;
                                    </p>
                                    <p style='margin: 0; color: #64748b;'>
                                        &copy; {$year} CIIDI. Todos los derechos reservados.
                                    </p>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        ";
    }
}
