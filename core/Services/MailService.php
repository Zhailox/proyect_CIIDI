<?php
// core/Services/MailService.php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../modules/SuperAdmin/services/SystemConfigService.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class MailService {

    private static string $templatesPath = __DIR__ . '/../../storage/email_templates.json';

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
            return ['exito' => true, 'mensaje' => 'Correo enviado exitosamente a ' . htmlspecialchars($destinoEmail)];
        } catch (Exception $e) {
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
        <body style='margin:0; padding:0; background-color:#f4f7fb; font-family: Arial, Helvetica, sans-serif; color:#334155;'>
            <table role='presentation' width='100%' cellspacing='0' cellpadding='0' style='background-color:#f4f7fb; padding: 20px 0;'>
                <tr>
                    <td align='center'>
                        <table role='presentation' width='100%' style='max-width: 600px; background-color:#ffffff; border-radius: 12px; overflow:hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;'>
                            
                            <!-- HEADER INSTITUCIONAL -->
                            <tr>
                                <td style='background: linear-gradient(135deg, #121a3e 0%, #1e293b 100%); padding: 30px 20px; text-align: center; color: #ffffff;'>
                                    <div style='font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: #f59e0b; margin-bottom: 6px;'>
                                        UPTTMBI - VALERA, TRUJILLO
                                    </div>
                                    <h1 style='margin:0; font-size: 22px; font-weight: 800; letter-spacing: 0.5px; color: #ffffff;'>
                                        SISTEMA INTEGRAL CIIDI
                                    </h1>
                                </td>
                            </tr>

                            <!-- CONTENIDO -->
                            <tr>
                                <td style='padding: 35px 30px; background-color: #ffffff;'>
                                    {$cuerpo}
                                </td>
                            </tr>

                            <!-- FOOTER INSTITUCIONAL -->
                            <tr>
                                <td style='background-color: #f8fafc; padding: 20px 30px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 12px; color: #64748b;'>
                                    <p style='margin: 0 0 6px 0; font-weight: 600; color: #475569;'>
                                        Universidad Politécnica Territorial del Estado Trujillo \"Mario Briceño Iragorry\"
                                    </p>
                                    <p style='margin: 0;'>
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
