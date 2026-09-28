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

    public function guardarPropuesta(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre_empresa'       => trim($_POST['nombre_empresa'] ?? ''),
                'rif_empresa'          => trim($_POST['rif_empresa'] ?? ''),
                'persona_contacto'     => trim($_POST['persona_contacto'] ?? ''),
                'telefono_contacto'    => trim($_POST['telefono_contacto'] ?? ''),
                'correo_contacto'      => trim($_POST['correo_contacto'] ?? ''),
                'area_afectada'        => 'Por evaluar (Se definirá al aprobar)',
                'descripcion_problema' => trim($_POST['descripcion_problema'] ?? '')
            ];

            // Generar código de seguimiento único
            $codigo_seguimiento = 'CIIDI-' . date('Y') . '-' . strtoupper(substr(md5(uniqid((string)rand(), true)), 0, 5));
            $datos['codigo_seguimiento'] = $codigo_seguimiento;

            $id = $this->modelo->guardar($datos);
            if ($id) {
                $_SESSION['mensaje_exito'] = "Su propuesta fue enviada correctamente.";
                $_SESSION['codigo_seguimiento'] = $codigo_seguimiento;
            } else {
                $_SESSION['mensaje_error'] = "Ocurrió un error al enviar su propuesta.";
            }
            echo "<script>window.location.href='?ruta=empresas-inicio';</script>";
            exit;
        }
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
                    $stmt = $pdo->prepare("SELECT area_afectada, descripcion_problema, nombre_empresa, persona_contacto, correo_contacto, codigo_seguimiento FROM propuestas_empresa WHERE id = ?");
                    $stmt->execute([$id]);
                    $prop = $stmt->fetch();

                    if ($prop) {
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
            }

            echo "<script>window.location.href='?ruta=gestion-proyectos&tab=propuestas';</script>";
            exit;
        }
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

            try {
                if ($this->modelo->crearPostulacion($id_investigacion, $user['id'], $motivacion, $equipo_extra)) {
                    $_SESSION['flash_success'] = "¡Tu solicitud ha sido enviada al Comité!";
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
        Auth::requierePrivilegioMinimo($nivelPublico); // 999 permite a cualquier usuario autenticado
        
        $oportunidades = $this->modelo->getAceptadas();
        
        $userData = [];
        if (Auth::check()) {
            $pdo = \Connection::getInstance();
            $stmt = $pdo->prepare("SELECT nombre_completo, cedula, email, telefono FROM usuarios WHERE id = ?");
            $stmt->execute([$_SESSION['usuario_id']]);
            $userData = $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
        }
        
        return [
            'oportunidades' => $oportunidades,
            'userData' => $userData
        ];
    }

    public function gestionProyectos(): array
    {
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
            
            $datos['todas'] = $todas;
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
                $nivel = !empty($p['nivel_trayecto']) ? $p['nivel_trayecto'] : 'Sin Asignar';
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
    'procesar-asignacion'   => $controller->procesarAsignacion(),
    'guardar-configuracion' => $controller->guardarConfiguracion(),
    default                 => null
};