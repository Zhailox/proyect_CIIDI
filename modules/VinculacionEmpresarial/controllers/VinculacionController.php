<?php
require_once __DIR__ . '/../services/ConfigService.php';
// modules/VinculacionEmpresarial/controllers/VinculacionController.php

require_once __DIR__ . '/../../SuperAdmin/services/SystemConfigService.php';
require_once __DIR__ . '/../../../core/Security/Auth.php';
require_once __DIR__ . '/../models/PropuestaEmpresaModel.php';

class VinculacionController
{
    private int $nivelAdmin;
    private int $nivelLogueado;
    private int $nivelPublico;
    private PropuestaEmpresaModel $modelo;

    public function __construct()
    {
        $this->modelo = new PropuestaEmpresaModel();
        $this->nivelAdmin = SystemConfigService::get('accesos_modulos.vinculacion_empresarial.admin', 1);
        $this->nivelPublico = SystemConfigService::get('accesos_modulos.vinculacion_empresarial.publico', 999);
        $this->nivelLogueado = SystemConfigService::get('accesos_modulos.autenticacion.publico', 999);
    }

    /** Tiempo mínimo (segundos) que un humano tarda en llenar el formulario público de empresas */
    private const PROPUESTA_MIN_SEGUNDOS = 3;

    public function guardarPropuesta(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../services/FormularioPropuestaService.php';
            require_once CORE_PATH . 'Security/CaptchaService.php';
            require_once CORE_PATH . 'Security/CSRF.php';

            $validacion = FormularioPropuestaService::validarEnvio($_POST);

            // 0. Ciclo de recepción cerrado desde Configuración
            if (!VinculacionConfigService::get('recepcion_activa', true)) {
                $this->rechazarEnvioPropuesta("La recepción de propuestas se encuentra cerrada en este momento.", $validacion['valores']);
            }

            // 1. CSRF
            if (!CSRF::validarToken($_POST['csrf_token'] ?? null)) {
                $this->rechazarEnvioPropuesta("Su sesión expiró. Por favor, verifique los datos y envíe nuevamente.", $validacion['valores']);
            }

            // 2. Tiempo humano mínimo (mismo campo _form_ts que usa el login)
            $ts = (int)($_POST['_form_ts'] ?? 0);
            $transcurrido = time() - $ts;
            if ($ts <= 0 || $transcurrido < self::PROPUESTA_MIN_SEGUNDOS) {
                $this->rechazarEnvioPropuesta("El formulario se envió demasiado rápido. Por favor, revise los datos e intente de nuevo.", $validacion['valores']);
            }

            // 3. Honeypot (website_url_hp) + Captcha visual del login (captcha_num_ans en sesión)
            $verifCaptcha = CaptchaService::validarPeticion($_POST);
            if (!$verifCaptcha['valido']) {
                $this->rechazarEnvioPropuesta($verifCaptcha['mensaje'], $validacion['valores']);
            }

            // 4. Validación por tipo de cada campo configurado
            if (!empty($validacion['errores'])) {
                $this->rechazarEnvioPropuesta(implode("\n", $validacion['errores']), $validacion['valores']);
            }

            $datos = $validacion['core'];
            $datos['correo_contacto'] = strtolower($datos['correo_contacto']);
            $datos['area_afectada'] = 'Por evaluar (Se definirá al aprobar)';
            $datos['campos_extra'] = json_encode($validacion['extras'], JSON_UNESCAPED_UNICODE);

            // Generar código de seguimiento único
            $codigo_seguimiento = 'CIIDI-' . date('Y') . '-' . strtoupper(substr(md5(uniqid((string)rand(), true)), 0, 5));
            $datos['codigo_seguimiento'] = $codigo_seguimiento;

            $id = $this->modelo->guardar($datos);
            if ($id) {
                unset($_SESSION['propuesta_form_old']);
                $_SESSION['mensaje_exito'] = "Su propuesta fue enviada correctamente.";
                $_SESSION['codigo_seguimiento'] = $codigo_seguimiento;
            } else {
                $_SESSION['propuesta_form_old'] = $validacion['valores'];
                $_SESSION['mensaje_error'] = "Ocurrió un error al enviar su propuesta.";
            }
            echo "<script>window.location.href='?ruta=empresas-inicio';</script>";
            exit;
        }
    }

    /** Redirige al formulario público conservando lo que el usuario escribió. */
    private function rechazarEnvioPropuesta(string $mensaje, array $valores): never
    {
        $_SESSION['propuesta_form_old'] = $valores;
        $_SESSION['mensaje_error'] = $mensaje;
        echo "<script>window.location.href='?ruta=empresas-inicio#registro-problematica';</script>";
        exit;
    }

    public function procesarPropuesta(): void
    {
        Auth::requierePrivilegioMinimo($this->nivelPublico);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_propuesta'], $_POST['accion'])) {
            $roles_permitidos = ['Profesor', 'Super Administrador', 'Comite'];
            if (!isset($_SESSION['rol_nombre']) || !in_array($_SESSION['rol_nombre'], $roles_permitidos, true)) {
                die("Acceso denegado");
            }

            $id = (int) $_POST['id_propuesta'];
            $accion = trim($_POST['accion']);

            if ($accion === 'aprobar') {
                $nivel = $_POST['nivel_trayecto'] ?? 'Trayecto I';
                $id_linea = !empty($_POST['id_linea']) ? (int)$_POST['id_linea'] : null;
                $id_dimension = !empty($_POST['id_dimension']) ? (int)$_POST['id_dimension'] : null;
                $area_afectada = trim($_POST['area_afectada'] ?? '');
                $cupos = isset($_POST['cupos_disponibles']) && !empty($_POST['cupos_disponibles']) 
                    ? (int) $_POST['cupos_disponibles'] 
                    : VinculacionConfigService::get('cupos_por_defecto', 3);

                $pdo = Connection::getInstance();

                if (!empty($area_afectada)) {
                    $stmt_area = $pdo->prepare("UPDATE propuestas_empresa SET area_afectada = ? WHERE id = ?");
                    $stmt_area->execute([$area_afectada, $id]);
                }

                $this->modelo->actualizarEstado($id, 'aceptada', $nivel);

                if ($id_linea) {
                    $stmt = $pdo->prepare("SELECT area_afectada, descripcion_problema, nombre_empresa, persona_contacto, correo_contacto, codigo_seguimiento, campos_extra FROM propuestas_empresa WHERE id = ?");
                    $stmt->execute([$id]);
                    $prop = $stmt->fetch();

                    if ($prop) {
                        require_once __DIR__ . '/../services/FormularioPropuestaService.php';
                        $extrasCorreo = FormularioPropuestaService::extrasHtmlCorreo(FormularioPropuestaService::decodificarExtras($prop['campos_extra'] ?? null));
                        $titulo_oferta = "Requerimiento: " . $prop['area_afectada'];
                        $desc_oferta = $prop['descripcion_problema'] . "\n\n(Nivel Requerido: $nivel)";

                        $sql_insert = "INSERT INTO investigaciones_ofertadas 
                                       (id_profesor, id_linea, id_dimension, titulo, planteamiento_problema, objetivo_general, estado, id_propuesta_empresa, cupos_disponibles) 
                                       VALUES (?, ?, ?, ?, ?, ?, 'Abierta', ?, ?)";
                        $stmt_in = $pdo->prepare($sql_insert);
                        $stmt_in->execute([
                            $_SESSION['usuario_id'],
                            $id_linea,
                            $id_dimension,
                            $titulo_oferta,
                            $desc_oferta,
                            "Dar respuesta y solución tecnológica a los requerimientos de " . $prop['nombre_empresa'],
                            $id,
                            $cupos
                        ]);

                        if (!empty($prop['correo_contacto'])) {
                            $asunto = 'Propuesta Tecnológica Aprobada - CIIDI';
                            $cuerpo = "
                            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;'>
                                <div style='background-color: #505984; padding: 20px; text-align: center; color: white;'>
                                    <h2 style='margin: 0;'>¡Propuesta Aprobada!</h2>
                                </div>
                                <div style='padding: 20px; background-color: #ffffff; color: #333333;'>
                                    <p>Hola <b>{$prop['persona_contacto']}</b> (Representante de {$prop['nombre_empresa']}),</p>
                                    <p>Nos complace informarte que la propuesta tecnológica que enviaste ha sido evaluada y <b>APROBADA</b> por nuestro comité de proyectos del CIIDI.</p>
                                    <p>Tu solicitud ha sido postulada en nuestra cartelera interna y se encuentra a la espera de que un equipo de estudiantes la asuma para iniciar el desarrollo.</p>
                                    <div style='background: #f4f7fb; padding: 15px; border-left: 4px solid #7090cb; margin: 20px 0;'>
                                        <p style='margin: 0 0 10px 0;'><b>Código de Seguimiento:</b> {$prop['codigo_seguimiento']}</p>
                                        <p style='margin: 0 0 10px 0;'><b>Área / Tema:</b> {$prop['area_afectada']}</p>
                                        <p style='margin: 0;'><b>Problemática:</b><br>" . nl2br($prop['descripcion_problema']) . "</p>
                                        {$extrasCorreo}
                                    </div>
                                    <p>Te notificaremos nuevamente tan pronto como un equipo de desarrollo sea asignado a tu requerimiento.</p>
                                    <p>Saludos cordiales,<br><b>Equipo CIIDI</b></p>
                                </div>
                            </div>";
                            $this->enviarCorreoNotificacion($prop['correo_contacto'], $prop['persona_contacto'], $asunto, $cuerpo);
                        }
                    }
                }

                $_SESSION['flash_success'] = "La propuesta ha sido aceptada y publicada en la cartelera.";

            } elseif ($accion === 'rechazar') {
                $motivo = trim($_POST['motivo_rechazo'] ?? '');

                if (empty($motivo)) {
                    $motivo = "Su problemática no cuenta con los requerimientos académicos o el alcance necesario para ser abordada como proyecto en este periodo.";
                }

                $this->modelo->actualizarEstado($id, 'rechazada', null, $motivo);

                $pdo = Connection::getInstance();
                $stmt = $pdo->prepare("SELECT nombre_empresa, persona_contacto, correo_contacto FROM propuestas_empresa WHERE id = ?");
                $stmt->execute([$id]);
                $prop = $stmt->fetch();

                if ($prop && !empty($prop['correo_contacto'])) {
                    $asunto = 'Actualización sobre su Propuesta Tecnológica - CIIDI';
                    $cuerpo = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;'>
                        <div style='background-color: #505984; padding: 20px; text-align: center; color: white;'>
                            <h2 style='margin: 0;'>Actualización de Propuesta</h2>
                        </div>
                        <div style='padding: 20px; background-color: #ffffff; color: #333333;'>
                            <p>Hola <b>{$prop['persona_contacto']}</b> (Representante de {$prop['nombre_empresa']}),</p>
                            <p>Agradecemos el tiempo que tomaste para enviarnos tu propuesta tecnológica. Nuestro comité ha evaluado cuidadosamente tu requerimiento, y lamentablemente en esta ocasión <b>no ha sido aprobada</b>.</p>
                            <div style='background: #fff1f2; padding: 15px; border-left: 4px solid #e11d48; margin: 20px 0; color: #9f1239;'>
                                <p style='margin: 0 0 5px 0;'><b>Motivo de la decisión:</b></p>
                                <p style='margin: 0;'><i>" . nl2br($motivo) . "</i></p>
                            </div>
                            <p>Te invitamos a postular nuevas problemáticas en el futuro que se ajusten al perfil de desarrollo de nuestros estudiantes.</p>
                            <p>Saludos cordiales,<br><b>Equipo CIIDI</b></p>
                        </div>
                    </div>";
                    $this->enviarCorreoNotificacion($prop['correo_contacto'], $prop['persona_contacto'], $asunto, $cuerpo);
                }

                $_SESSION['flash_success'] = "La propuesta ha sido rechazada y se notificó a la empresa.";
            } elseif (in_array($accion, ['revertir', 'archivar', 'restaurar'], true)) {
                $filtroDestino = $this->gestionarCicloPropuesta($id, $accion);
                echo "<script>window.location.href='?ruta=gestion-proyectos&tab=propuestas&filtro=" . rawurlencode($filtroDestino) . "';</script>";
                exit;
            }

            echo "<script>window.location.href='?ruta=gestion-proyectos&tab=propuestas';</script>";
            exit;
        }
    }

    /**
     * Ciclo de vida de propuestas desde "Gestión de Solicitudes":
     *  - revertir:  'aceptada' -> 'pendiente' (cierra la oferta vinculada y cancela postulaciones con motivo)
     *  - archivar:  soft-delete con motivo (oculta de listados, cartelera y carrusel)
     *  - restaurar: deshace el archivo
     * Devuelve el filtro de estado a mostrar tras la redirección.
     */
    private function gestionarCicloPropuesta(int $id, string $accion): string
    {
        require_once CORE_PATH . 'Security/CSRF.php';
        require_once CORE_PATH . 'Security/AuditLogger.php';

        if (!CSRF::validarToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = "Token de seguridad inválido o expirado. Recargue la página e intente de nuevo.";
            return 'Todos';
        }

        $prop = $this->modelo->getPorId($id);
        if (!$prop) {
            $_SESSION['flash_error'] = "La propuesta indicada no existe.";
            return 'Todos';
        }
        $ref = ($prop['codigo_seguimiento'] ?? 'S/C') . ' - ' . $prop['nombre_empresa'];
        $motivo = mb_substr(trim((string)($_POST['motivo'] ?? '')), 0, 1000);

        if ($accion === 'revertir') {
            if ($motivo === '') {
                $_SESSION['flash_error'] = "Debe indicar el motivo de la reversión.";
                return 'aceptada';
            }
            $res = $this->modelo->revertirAceptada($id, $motivo);
            if (!$res['ok']) {
                $_SESSION['flash_error'] = $res['error'] ?? "No se pudo revertir la propuesta.";
                return 'aceptada';
            }
            $afectados = $res['afectados'] ?? [];
            foreach ($afectados as $est) {
                if (empty($est['email'])) continue;
                $nombreEst = htmlspecialchars((string)$est['nombre_completo']);
                $cuerpo = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;'>
                    <div style='background-color: #505984; padding: 20px; text-align: center; color: white;'>
                        <h2 style='margin: 0;'>Actualización de Postulación</h2>
                    </div>
                    <div style='padding: 20px; background-color: #ffffff; color: #333333;'>
                        <p>Hola <b>{$nombreEst}</b>,</p>
                        <p>Te informamos que el requerimiento tecnológico de la organización <b>" . htmlspecialchars((string)$prop['nombre_empresa']) . "</b> fue <b>retirado de la cartelera</b> por el Comité de Proyectos y vuelve a evaluación. Por este motivo tu postulación ha sido <b>cancelada</b>.</p>
                        <div style='background: #fff1f2; padding: 15px; border-left: 4px solid #e11d48; margin: 20px 0; color: #9f1239;'>
                            <p style='margin: 0 0 5px 0;'><b>Motivo:</b></p>
                            <p style='margin: 0;'><i>" . nl2br(htmlspecialchars($motivo)) . "</i></p>
                        </div>
                        <p>Te invitamos a revisar la cartelera de oportunidades y postularte a otros proyectos.</p>
                        <p>Saludos cordiales,<br><b>Equipo CIIDI</b></p>
                    </div>
                </div>";
                $this->enviarCorreoNotificacion($est['email'], (string)$est['nombre_completo'], 'Postulación cancelada - CIIDI', $cuerpo);
            }
            AuditLogger::registrar('WARNING', 'VinculacionEmpresarial', 'Revertir Propuesta Aceptada',
                "Propuesta #{$id} ({$ref}) revertida a pendiente. Ofertas cerradas: " . ($res['ofertas'] ?? 0) . ". Postulaciones canceladas: " . count($afectados) . ". Motivo: {$motivo}");
            $_SESSION['flash_success'] = "La propuesta volvió a estado Pendiente. Se cerró su oferta en la cartelera"
                . (count($afectados) ? " y se notificó a " . count($afectados) . " postulante(s)." : ".");
            return 'pendiente';
        }

        if ($accion === 'archivar') {
            if ($motivo === '') {
                $_SESSION['flash_error'] = "Debe indicar el motivo del archivo.";
                return 'Todos';
            }
            if ($this->modelo->archivar($id, $motivo, isset($_SESSION['usuario_id']) ? (int)$_SESSION['usuario_id'] : null)) {
                AuditLogger::registrar('WARNING', 'VinculacionEmpresarial', 'Archivar Propuesta', "Propuesta #{$id} ({$ref}) archivada. Estado previo: {$prop['estado']}. Motivo: {$motivo}");
                $_SESSION['flash_success'] = "La propuesta fue archivada. Puede restaurarla desde el filtro 'Archivadas'.";
                return 'archivada';
            }
            $_SESSION['flash_error'] = "No se pudo archivar la propuesta (puede que ya esté archivada).";
            return 'Todos';
        }

        // restaurar
        if ($this->modelo->restaurar($id)) {
            AuditLogger::registrar('INFO', 'VinculacionEmpresarial', 'Restaurar Propuesta', "Propuesta #{$id} ({$ref}) restaurada desde el archivo.");
            $_SESSION['flash_success'] = "La propuesta fue restaurada.";
            return (string)$prop['estado'];
        }
        $_SESSION['flash_error'] = "No se pudo restaurar la propuesta.";
        return 'archivada';
    }

    public function postularOportunidad(): void
    {
        Auth::requierePrivilegioMinimo(0);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once CORE_PATH . 'Security/Auth.php';
            if (!Auth::check()) {
                $_SESSION['flash_error'] = "Debes iniciar sesión para postularte.";
                header("Location: ?ruta=cartelera-oportunidades");
                exit;
            }
            $user = Auth::usuario();
            $id_investigacion = (int) $_POST['id_investigacion'];
            $motivacion = trim($_POST['motivacion'] ?? '');
            $equipo_extra = $_POST['equipo_extra'] ?? null;
            $trayecto = !empty($_POST['trayecto']) ? (int) $_POST['trayecto'] : null;
            $trimestre = !empty($_POST['trimestre']) ? (int) $_POST['trimestre'] : 1;
            $id_profesor_pst = !empty($_POST['id_profesor_pst']) ? (int) $_POST['id_profesor_pst'] : null;

            if (!$trayecto || !$id_profesor_pst) {
                $_SESSION['flash_error'] = "Debe indicar su trayecto y el profesor PST deseado.";
                header("Location: ?ruta=cartelera-oportunidades");
                exit;
            }

            try {
                if ($this->modelo->crearPostulacion($id_investigacion, $user['id'], $motivacion, $equipo_extra, $trayecto, $trimestre, $id_profesor_pst)) {
                    $_SESSION['flash_success'] = "¡Tu solicitud ha sido enviada al Comité y a tu Profesor PST!";
                } else {
                    $_SESSION['flash_error'] = "Ocurrió un error al enviar tu solicitud.";
                }
            } catch (PDOException $e) {
                if ($e->getCode() == 23505) {
                    $_SESSION['flash_error'] = "Ya te has postulado anteriormente a este reto. Tu solicitud está en revisión.";
                } else {
                    $_SESSION['flash_error'] = "Ocurrió un error inesperado en la base de datos.";
                }
            }
            echo "<script>window.location.href='?ruta=cartelera-oportunidades';</script>";
            exit;
        }
    }

    public function procesarAsignacion(): void
    {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'editar', 'VinculacionEmpresarial');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_postulacion = (int) $_POST['id_postulacion'];
            $id_investigacion = (int) $_POST['id_investigacion'];
            $estado = $_POST['estado'];

            if ($this->modelo->procesarAsignacion($id_postulacion, $estado, $id_investigacion)) {
                if ($estado === 'Aceptado') {
                    $_SESSION['flash_success'] = "El equipo ha sido asignado al proyecto y ambas partes fueron notificadas.";
                    $pdo = Connection::getInstance();
                    $sql = "SELECT p.equipo_extra,
                                   u.nombre_completo as lider_nombre, u.email as lider_email, u.cedula as lider_cedula,
                                   pr.nombre_empresa, pr.correo_contacto, pr.persona_contacto, pr.area_afectada, pr.descripcion_problema, pr.nivel_trayecto
                            FROM postulaciones_estudiantes p
                            JOIN usuarios u ON p.id_estudiante = u.id
                            JOIN investigaciones_ofertadas i ON p.id_investigacion = i.id
                            JOIN propuestas_empresa pr ON i.id_propuesta_empresa = pr.id
                            WHERE p.id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$id_postulacion]);
                    $info = $stmt->fetch();

                    if ($info) {
                        $listaEstudiantes = "<ul>";
                        $listaEstudiantes .= "<li><b>Líder:</b> {$info['lider_nombre']} (C.I: {$info['lider_cedula']}, Correo: {$info['lider_email']})</li>";
                        if (!empty($info['equipo_extra'])) {
                            $equipoArray = json_decode($info['equipo_extra'], true);
                            if (is_array($equipoArray)) {
                                foreach ($equipoArray as $m) {
                                    $listaEstudiantes .= "<li><b>Compañero:</b> {$m['nombre']} (C.I: {$m['cedula']}, Télf: {$m['telefono']})</li>";
                                }
                            }
                        }
                        $listaEstudiantes .= "</ul>";

                        if (!empty($info['lider_email'])) {
                            $asuntoEst = '¡Postulación Aceptada! - Proyecto Asignado';
                            $cuerpoEst = "
                            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;'>
                                <div style='background-color: #505984; padding: 20px; text-align: center; color: white;'>
                                    <h2 style='margin: 0;'>¡Felicidades! Equipo Asignado</h2>
                                </div>
                                <div style='padding: 20px; background-color: #ffffff; color: #333333;'>
                                    <p>Hola <b>{$info['lider_nombre']}</b>,</p>
                                    <p>Tu postulación ha sido <b>SELECCIONADA</b> para desarrollar el requerimiento tecnológico de la empresa <b>{$info['nombre_empresa']}</b>.</p>
                                    <div style='background: #f4f7fb; padding: 15px; border-left: 4px solid #7090cb; margin: 20px 0;'>
                                        <h4 style='margin-top:0;'>Datos de la Organización:</h4>
                                        <p style='margin: 0 0 5px 0;'><b>Empresa:</b> {$info['nombre_empresa']}</p>
                                        <p style='margin: 0 0 5px 0;'><b>Contacto:</b> {$info['persona_contacto']} ({$info['correo_contacto']})</p>
                                        <p style='margin: 0 0 5px 0;'><b>Área:</b> {$info['area_afectada']}</p>
                                        <p style='margin: 0;'><b>Problemática:</b><br>" . nl2br($info['descripcion_problema']) . "</p>
                                    </div>
                                    <p>Saludos cordiales,<br><b>Equipo CIIDI</b></p>
                                </div>
                            </div>";
                            $this->enviarCorreoNotificacion($info['lider_email'], $info['lider_nombre'], $asuntoEst, $cuerpoEst);
                        }

                        if (!empty($info['correo_contacto'])) {
                            $asuntoEmp = '¡Equipo Asignado a tu Requerimiento! - CIIDI';
                            $cuerpoEmp = "
                            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;'>
                                <div style='background-color: #505984; padding: 20px; text-align: center; color: white;'>
                                    <h2 style='margin: 0;'>¡Un equipo tomará tu proyecto!</h2>
                                </div>
                                <div style='padding: 20px; background-color: #ffffff; color: #333333;'>
                                    <p>Hola <b>{$info['persona_contacto']}</b> (Representante de {$info['nombre_empresa']}),</p>
                                    <p>Tenemos excelentes noticias. Tu requerimiento ha sido asignado a un equipo de desarrollo de <b>{$info['nivel_trayecto']}</b>.</p>
                                    <div style='background: #f4f7fb; padding: 15px; border-left: 4px solid #7090cb; margin: 20px 0;'>
                                        <p style='margin: 0 0 10px 0;'><b>Área / Tema:</b> {$info['area_afectada']}</p>
                                        <p style='margin: 0 0 10px 0;'><b>Problemática a resolver:</b><br>" . nl2br($info['descripcion_problema']) . "</p>
                                    </div>
                                    <h4 style='color: #121a3e; border-bottom: 1px solid #cbd5e1; padding-bottom: 5px;'>Datos del Equipo de Estudiantes:</h4>
                                    {$listaEstudiantes}
                                    <p style='margin-top:20px;'>Pronto se pondrán en contacto contigo para los detalles iniciales del proyecto.</p>
                                    <p>Saludos cordiales,<br><b>Equipo CIIDI</b></p>
                                </div>
                            </div>";
                            $this->enviarCorreoNotificacion($info['correo_contacto'], $info['persona_contacto'], $asuntoEmp, $cuerpoEmp);
                        }
                    }
                } elseif ($estado === 'Rechazado') {
                    $pdo = Connection::getInstance();
                    $sql = "SELECT u.nombre_completo as lider_nombre, u.email as lider_email,
                                   pr.nombre_empresa
                            FROM postulaciones_estudiantes p
                            JOIN usuarios u ON p.id_estudiante = u.id
                            JOIN investigaciones_ofertadas i ON p.id_investigacion = i.id
                            JOIN propuestas_empresa pr ON i.id_propuesta_empresa = pr.id
                            WHERE p.id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$id_postulacion]);
                    $info = $stmt->fetch();

                    if ($info && !empty($info['lider_email'])) {
                        $motivo = !empty($_POST['motivo_rechazo']) ? trim($_POST['motivo_rechazo']) : "Lamentablemente, el comité de proyectos ha determinado que tu perfil o equipo no cumple con los requerimientos técnicos actuales para abordar esta problemática.";
                        $asuntoEst = 'Actualización sobre su Postulación - CIIDI';
                        $cuerpoEst = "
                        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;'>
                            <div style='background-color: #505984; padding: 20px; text-align: center; color: white;'>
                                <h2 style='margin: 0;'>Actualización de Postulación</h2>
                            </div>
                            <div style='padding: 20px; background-color: #ffffff; color: #333333;'>
                                <p>Hola <b>{$info['lider_nombre']}</b>,</p>
                                <p>Agradecemos el interés de tu equipo en participar en el desarrollo del requerimiento tecnológico de la empresa <b>{$info['nombre_empresa']}</b>.</p>
                                <p>Tras evaluar las postulaciones, debemos informarte que tu equipo <b>no ha sido seleccionado</b> para este proyecto en particular.</p>
                                <div style='background: #fff1f2; padding: 15px; border-left: 4px solid #e11d48; margin: 20px 0; color: #9f1239;'>
                                    <p style='margin: 0 0 5px 0;'><b>Motivo de la decisión:</b></p>
                                    <p style='margin: 0;'><i>{$motivo}</i></p>
                                </div>
                                <p>Te invitamos a seguir revisando la cartelera de oportunidades y postularte a otros proyectos que se ajusten mejor a su perfil.</p>
                                <p>Saludos cordiales,<br><b>Equipo CIIDI</b></p>
                            </div>
                        </div>";
                        $this->enviarCorreoNotificacion($info['lider_email'], $info['lider_nombre'], $asuntoEst, $cuerpoEst);
                    }
                    $_SESSION['flash_success'] = "La postulación ha sido rechazada y el equipo fue notificado.";
                }
            } else {
                $_SESSION['flash_error'] = "Ocurrió un error al procesar la postulación.";
            }
            echo "<script>window.location.href='?ruta=gestion-proyectos&tab=equipos';</script>";
            exit;
        }
    }

    private function enviarCorreoNotificacion(string $destinatarioEmail, string $destinatarioNombre, string $asunto, string $cuerpoHtml): bool
    {
        require_once CORE_PATH . 'Helpers/PHPMailer/Exception.php';
        require_once CORE_PATH . 'Helpers/PHPMailer/PHPMailer.php';
        require_once CORE_PATH . 'Helpers/PHPMailer/SMTP.php';

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'orlando5711666@gmail.com';
            $mail->Password = 'hkwtkytxrqxslngb';
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom('no-reply@ciidi.edu.ve', 'Sistema CIIDI');
            $mail->addAddress($destinatarioEmail, $destinatarioNombre);

            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body = $cuerpoHtml;

            $mail->send();
            return true;
        } catch (\Throwable $e) {
            error_log("Error enviando correo a $destinatarioEmail: " . $mail->ErrorInfo);
            return false;
        }
    }

    // --- METODOS DE VISTAS FRONTEND ---
    
    public function carteleraOportunidades(): array {
        $nivelPublico = SystemConfigService::get('accesos_modulos.vinculacion_empresarial.publico', 999);
        Auth::requierePrivilegioMinimo($nivelPublico);
        
        $oportunidades = $this->modelo->getAceptadas();
        
        $lineasUnicasMap = [];
        $oportunidadesPorTrayecto = [];
        $cuposUnicosMap = [];
        
        foreach ($oportunidades as $op) {
            if (!empty($op['linea_investigacion'])) {
                $lineasUnicasMap[$op['linea_investigacion']] = $op['linea_investigacion'];
            }
            $trayecto = !empty($op['nivel_trayecto']) ? $op['nivel_trayecto'] : 'Sin Trayecto Asignado';
            $oportunidadesPorTrayecto[$trayecto][] = $op;
            
            $cupo = (int)($op['cupos_disponibles'] ?? 3);
            $cuposUnicosMap[$cupo] = $cupo;
        }
        
        $lineasUnicas = array_values($lineasUnicasMap);
        ksort($oportunidadesPorTrayecto);
        
        $cuposUnicos = array_values($cuposUnicosMap);
        sort($cuposUnicos);

        $userData = [];
        $estados_postulaciones = [];
        if (Auth::check()) {
            $pdo = \Connection::getInstance();
            $stmt = $pdo->prepare("SELECT nombre_completo, cedula, email, telefono FROM usuarios WHERE id = ?");
            $stmt->execute([$_SESSION['usuario_id']]);
            $userData = $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
            
            $estados_postulaciones = $this->modelo->getEstadosPostulacionesEstudiante($_SESSION['usuario_id']);
        }
        
        require_once __DIR__ . '/../models/ProfesorPSTModel.php';
        require_once __DIR__ . '/../../Autenticacion/services/PerfilPSTService.php';
        $modeloPst = new ProfesorPSTModel();

        return [
            'oportunidades' => $oportunidades,
            'userData' => $userData,
            'lineasUnicas' => $lineasUnicas,
            'estados_postulaciones' => $estados_postulaciones,
            'oportunidadesPorTrayecto' => $oportunidadesPorTrayecto,
            'cuposUnicos' => $cuposUnicos,
            'profesoresPST' => $modeloPst->getProfesoresPST(true),
            'catTrayectos' => PerfilPSTService::catalogoTrayectos(),
            'catTrimestres' => PerfilPSTService::catalogoTrimestres()
        ];
    }

    public function gestionProyectos(): array
    {
        $rol = Auth::usuario()['rol'] ?? '';
        $nivel = (int)(Auth::usuario()['nivel'] ?? 999);
        $esSuperAdmin = $nivel === 0;
        
        // Si es profesor sin rol administrativo, redirigirlo a su panel oculto
        if (!$esSuperAdmin && in_array($rol, ['Profesor', 'Docentes']) && $rol !== 'Comité') {
            header("Location: ?ruta=postulaciones-pst");
            exit;
        }

        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'auditar', 'VinculacionEmpresarial');
        
        $tab = $_GET['tab'] ?? 'propuestas';
        $pdo = Connection::getInstance();
        
        $datos = [
            'tab' => $tab,
            'todas' => [],
            'postulaciones' => [],
            'lineas' => [],
            'dimensiones' => [],
            'kpiNuevas' => 0,
            'trayectos' => [],
            'estados' => []
        ];

        if ($tab === 'propuestas') {
            $todas = $this->modelo->getTodas();
            $lineas = $pdo->query("SELECT id, nombre FROM lineas_investigacion ORDER BY nombre ASC")->fetchAll(\PDO::FETCH_ASSOC);
            $dimensiones = $pdo->query("SELECT id, id_linea, nombre FROM dimensiones_operativas ORDER BY nombre ASC")->fetchAll(\PDO::FETCH_ASSOC);
            
            $kpiNuevas = 0;
            $trayectos = [];
            $estados = ['pendiente' => 0, 'aceptada' => 0, 'rechazada' => 0];
            
            foreach ($todas as $p) {
                if (($p['estado'] ?? '') === 'pendiente') $kpiNuevas++;
                
                $nivel = !empty($p['nivel_trayecto']) ? $p['nivel_trayecto'] : 'Sin Asignar';
                $trayectos[$nivel] = ($trayectos[$nivel] ?? 0) + 1;
                
                $est = $p['estado'] ?? '';
                $estados[$est] = ($estados[$est] ?? 0) + 1;
            }
            ksort($trayectos);
            
            $archivadas = $this->modelo->getArchivadas();
            $estados['archivada'] = count($archivadas);
            
            $datos['todas'] = $todas;
            $datos['archivadas'] = $archivadas;
            $datos['lineas'] = $lineas;
            $datos['dimensiones'] = $dimensiones;
            $datos['kpiNuevas'] = $kpiNuevas;
            $datos['trayectos'] = $trayectos;
            $datos['estados'] = $estados;
        } else {
            $postulaciones = $this->modelo->getPostulacionesEmpresariales();
            $trayectos = [];
            $estados = ['Pendiente' => 0, 'Aceptado' => 0, 'Rechazado' => 0];
            
            foreach ($postulaciones as $p) {
                $nivel = !empty($p['pst_trayecto']) ? $p['pst_trayecto'] : 'Sin Asignar';
                $trayectos[$nivel] = ($trayectos[$nivel] ?? 0) + 1;
                
                $est = $p['estado'] ?? '';
                $estados[$est] = ($estados[$est] ?? 0) + 1;
            }
            ksort($trayectos);
            
            $datos['postulaciones'] = $postulaciones;
            $datos['trayectos'] = $trayectos;
            $datos['estados'] = $estados;
        }
        
        return $datos;
    }

    public function configuracion(): array
    {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'editar', 'VinculacionEmpresarial');
        return VinculacionConfigService::get();
    }

    public function guardarConfiguracion(): void
    {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'editar', 'VinculacionEmpresarial');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $configs = [
                'paginacion_gestion'   => (int)($_POST['paginacion_gestion'] ?? 8),
                'paginacion_cartelera' => (int)($_POST['paginacion_cartelera'] ?? 12),
                'recepcion_activa'     => isset($_POST['recepcion_activa']),
                'mensaje_marquee'      => trim($_POST['mensaje_marquee'] ?? '')
            ];

            // Campos del formulario público (JSON serializado por el editor de configuracion.php)
            if (isset($_POST['campos_formulario_json'])) {
                require_once __DIR__ . '/../services/FormularioPropuestaService.php';
                $camposRecibidos = json_decode((string)$_POST['campos_formulario_json'], true);
                if (is_array($camposRecibidos)) {
                    $configs[FormularioPropuestaService::CLAVE_CONFIG] = FormularioPropuestaService::sanear($camposRecibidos);
                }
            }

            // Catálogo dinámico de Trayectos y Niveles Académicos (Pregrado / Postgrado)
            if (isset($_POST['catalogo_trayectos_json'])) {
                require_once __DIR__ . '/../../Autenticacion/services/PerfilPSTService.php';
                $trayectosRecibidos = json_decode((string)$_POST['catalogo_trayectos_json'], true);
                if (is_array($trayectosRecibidos)) {
                    $configs[PerfilPSTService::CLAVE_CONFIG_TRAYECTOS] = PerfilPSTService::sanearCatalogoTrayectos($trayectosRecibidos);
                }
            }

            if (VinculacionConfigService::setMultiple($configs)) {
                $_SESSION['flash_success'] = "Configuraciones actualizadas exitosamente.";
            } else {
                $_SESSION['flash_error'] = "Error al guardar las configuraciones.";
            }
            echo "<script>window.location.href='?ruta=vinculacion-config';</script>";
            exit;
        }
    }
}

// Bootstrap
$controller = new VinculacionController();
$ruta = $_GET['ruta'] ?? '';

match ($ruta) {
    'guardar-propuesta'     => $controller->guardarPropuesta(),
    'procesar-propuesta'    => $controller->procesarPropuesta(),
    'postular-oportunidad'  => $controller->postularOportunidad(),
    'cartelera-oportunidades' => $controller->carteleraOportunidades(),
    'procesar-asignacion'   => $controller->procesarAsignacion(),
    'guardar-configuracion' => $controller->guardarConfiguracion(),
    default                 => null
};