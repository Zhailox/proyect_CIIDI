<?php
// modules/Autenticacion/controllers/PerfilController.php
require_once CORE_PATH . 'Security/Auth.php';
require_once CORE_PATH . 'Security/CSRF.php';
require_once CORE_PATH . 'Security/AuditLogger.php';
require_once CORE_PATH . 'Security/RateLimiter.php';
require_once CORE_PATH . 'Services/MailService.php';
require_once __DIR__ . '/../models/UsuarioModel.php';

class PerfilController {

    private UsuarioModel $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }

    public function mostrarDashboard() {
        // 1. Guardián de seguridad RBAC
        Auth::requierePrivilegioMinimo(998);
        
        $usuarioSesion = Auth::usuario();
        $userId = (int)($usuarioSesion['id'] ?? 0);
        $esSuperAdmin = (int)($usuarioSesion['nivel'] ?? 999) === 0;

        // Si se recibió un POST directamente en la ruta /perfil
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accion = $_POST['accion'] ?? '';
            if ($accion === 'actualizar_datos') {
                return $this->procesarActualizarPerfil();
            } elseif ($accion === 'cambiar_clave') {
                return $this->procesarCambiarClave();
            } elseif ($accion === 'enviar_verificacion_email') {
                return $this->procesarVerificacionEmail();
            } elseif ($accion === 'vincular_lineas_docente') {
                return $this->procesarVincularLineasDocente();
            } elseif ($accion === 'solicitar_acreditacion_pst') {
                return $this->procesarSolicitudAcreditacionPST();
            }
        }
        
        // 2. Extraemos los datos frescos de la BD
        $datosCompletos = $this->usuarioModel->obtenerPerfilCompleto($userId);
        
        if (!$datosCompletos) {
            $datosCompletos = [
                'id' => $userId,
                'cedula' => $usuarioSesion['cedula'] ?? '',
                'nombre_completo' => $usuarioSesion['nombre'] ?? 'Usuario',
                'email' => $usuarioSesion['email'] ?? '',
                'activo' => true,
                'email_verified' => false,
                'nombre_rol' => $usuarioSesion['rol'] ?? 'Usuario',
                'nivel_privilegio' => $usuarioSesion['nivel'] ?? 999,
                'fecha_inicial' => null,
                'ultima_actividad' => null,
                'conteo_accesos' => 1
            ];
        }

        // Lógica de presentación de nombres
        $partesNombre = explode(' ', trim($datosCompletos['nombre_completo'] ?? 'Usuario'));
        $datosCompletos['primer_nombre'] = $partesNombre[0] ?? 'Usuario';
        $datosCompletos['iniciales'] = strtoupper(substr($partesNombre[0] ?? 'U', 0, 1));
        if (isset($partesNombre[1]) && !empty($partesNombre[1])) {
            $datosCompletos['iniciales'] .= strtoupper(substr($partesNombre[1], 0, 1));
        }

        // 3. Información del entorno de sesión actual
        $ipActual = RateLimiter::obtenerIPCliente();
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido';
        $dispositivo = 'Navegador Web';
        if (stripos($userAgent, 'Mobile') !== false || stripos($userAgent, 'Android') !== false || stripos($userAgent, 'iPhone') !== false) {
            $dispositivo = 'Dispositivo Móvil';
        } elseif (stripos($userAgent, 'Windows') !== false) {
            $dispositivo = 'PC Windows';
        } elseif (stripos($userAgent, 'Macintosh') !== false) {
            $dispositivo = 'Apple Mac';
        } elseif (stripos($userAgent, 'Linux') !== false) {
            $dispositivo = 'Equipo Linux';
        }

        // Configuración de inactividad de sesión
        $sysConfigPath = defined('STORAGE_PATH') ? STORAGE_PATH . 'system_config.json' : CORE_PATH . '../storage/system_config.json';
        $timeoutMinutos = 120;
        if (file_exists($sysConfigPath)) {
            $sysCfg = json_decode(file_get_contents($sysConfigPath), true) ?: [];
            $timeoutMinutos = (int)($sysCfg['seguridad']['timeout_minutos'] ?? 120);
        }

        // 4. Actividad reciente de auditoría vinculada al usuario
        $actividadReciente = $this->usuarioModel->obtenerActividadRecienteUsuario($userId, 6);

        // 5. Mensajes de sesión flash
        $mensajeExito = $_SESSION['perfil_exito'] ?? null;
        $mensajeError = $_SESSION['perfil_error'] ?? null;
        $activeTab = $_SESSION['perfil_active_tab'] ?? 'resumen';
        unset($_SESSION['perfil_exito'], $_SESSION['perfil_error'], $_SESSION['perfil_active_tab']);

        // Extraer carreras, líneas de investigación, dimensiones y trayectos si es Docente, Investigador, Comité o SuperAdmin
        $carrerasDisponibles = [];
        $lineasDisponibles = [];
        $dimensionesPorLinea = [];
        $misLineasIds = [];
        $misDimensionesIds = [];
        $rolDetectado = mb_strtolower(trim((string)($datosCompletos['nombre_rol'] ?? $datosCompletos['rol_nombre'] ?? $usuarioSesion['rol'] ?? '')));
        $nivelDetectado = (int)($datosCompletos['nivel_privilegio'] ?? $usuarioSesion['nivel'] ?? 999);
        $esProfesor = str_contains($rolDetectado, 'docente')
                   || str_contains($rolDetectado, 'profesor')
                   || str_contains($rolDetectado, 'investigador')
                   || str_contains($rolDetectado, 'comit')
                   || !empty($datosCompletos['es_investigador'])
                   || !empty($datosCompletos['es_profesor_pst'])
                   || $nivelDetectado <= 2;
        
        if ($esProfesor) {
            $pdo = Connection::getInstance();
            $this->asegurarTablasEspecialidad($pdo);
            try {
                $carrerasDisponibles = $pdo->query("SELECT id, nombre, descripcion FROM public.carreras ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

                $lineasDisponibles = $pdo->query("
                    SELECT li.id, li.nombre, li.descripcion, li.id_carrera,
                           COALESCE(c.nombre, 'General / Institucional') AS carrera_nombre
                    FROM public.lineas_investigacion li
                    LEFT JOIN public.carreras c ON c.id = li.id_carrera
                    WHERE li.activo = true
                    ORDER BY li.id_carrera ASC NULLS LAST, li.nombre ASC
                ")->fetchAll(PDO::FETCH_ASSOC);

                $dimsRaw = $pdo->query("
                    SELECT id, id_linea, nombre, descripcion
                    FROM public.dimensiones_operativas
                    WHERE activo = true
                    ORDER BY id_linea ASC, nombre ASC
                ")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($dimsRaw as $dimRow) {
                    $idL = (int)$dimRow['id_linea'];
                    $dimensionesPorLinea[$idL][] = $dimRow;
                }
                
                $stmtMisLineas = $pdo->prepare("SELECT id_linea FROM public.profesor_linea WHERE id_usuario = ?");
                $stmtMisLineas->execute([$userId]);
                $misLineasIds = array_map('intval', $stmtMisLineas->fetchAll(PDO::FETCH_COLUMN));

                $stmtMisDims = $pdo->prepare("SELECT id_dimension FROM public.profesor_dimension WHERE id_usuario = ?");
                $stmtMisDims->execute([$userId]);
                $misDimensionesIds = array_map('intval', $stmtMisDims->fetchAll(PDO::FETCH_COLUMN));
            } catch (\Throwable $e) {}

            require_once __DIR__ . '/../services/PerfilPSTService.php';
            $esPstActual = PerfilPSTService::esProfesorPST($userId);
            $trayectosPstActuales = PerfilPSTService::obtenerTrayectos($userId);
            $solicitudPstPendiente = PerfilPSTService::obtenerSolicitudPendiente($userId);
            $ultimaSolicitudPst = PerfilPSTService::obtenerUltimaSolicitud($userId);
        } else {
            $esPstActual = false;
            $trayectosPstActuales = [];
            $solicitudPstPendiente = null;
            $ultimaSolicitudPst = null;
        }

        return [
            'esProfesor'            => $esProfesor,
            'carrerasDisponibles'   => $carrerasDisponibles,
            'lineasDisponibles'     => $lineasDisponibles,
            'dimensionesPorLinea'   => $dimensionesPorLinea,
            'misLineasIds'          => $misLineasIds,
            'misDimensionesIds'     => $misDimensionesIds,
            'esPstActual'           => $esPstActual,
            'trayectosPstActuales'  => $trayectosPstActuales,
            'solicitudPstPendiente' => $solicitudPstPendiente,
            'ultimaSolicitudPst'    => $ultimaSolicitudPst,
            'usuarioActual'         => $datosCompletos,
            'esSuperAdmin'          => $esSuperAdmin,
            'ipActual'              => $ipActual,
            'dispositivo'           => $dispositivo,
            'timeoutMinutos'        => $timeoutMinutos,
            'actividadReciente'     => $actividadReciente,
            'mensajeExito'          => $mensajeExito,
            'mensajeError'          => $mensajeError,
            'activeTab'             => $activeTab
        ];
    }

    public function procesarActualizarPerfil() {
        Auth::requierePrivilegioMinimo(998);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: perfil");
            exit;
        }

        // 1. Validación estricta de CSRF
        if (!CSRF::validarToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['perfil_error'] = "El token de seguridad ha caducado o es inválido. Por favor, intente de nuevo.";
            $_SESSION['perfil_active_tab'] = 'editar';
            header("Location: perfil");
            exit;
        }

        $usuarioSesion = Auth::usuario();
        $userId = (int)($usuarioSesion['id'] ?? 0);
        $esSuperAdmin = (int)($usuarioSesion['nivel'] ?? 999) === 0;

        if ($userId <= 0) {
            header("Location: login");
            exit;
        }

        $datosActuales = $this->usuarioModel->obtenerPerfilCompleto($userId);
        if (!$datosActuales) {
            $_SESSION['perfil_error'] = "No se pudo recuperar la información del usuario.";
            header("Location: perfil");
            exit;
        }

        $cambiosRealizados = [];

        // 2. ACTUALIZACIÓN DE CORREO ELECTRÓNICO (Permitido para todos los usuarios)
        $nuevoEmail = trim($_POST['email'] ?? '');
        if (!empty($nuevoEmail) && strtolower($nuevoEmail) !== strtolower($datosActuales['email'] ?? '')) {
            // Validación con regex estándar
            $regexEmail = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
            if (!preg_match($regexEmail, $nuevoEmail)) {
                $_SESSION['perfil_error'] = "El correo electrónico ingresado no cumple con el formato válido.";
                $_SESSION['perfil_active_tab'] = 'editar';
                header("Location: perfil");
                exit;
            }

            // Validación estricta con MailService
            $valEmail = MailService::validarEmail($nuevoEmail, false);
            if (!$valEmail['valido']) {
                $_SESSION['perfil_error'] = $valEmail['mensaje'];
                $_SESSION['perfil_active_tab'] = 'editar';
                header("Location: perfil");
                exit;
            }

            // Validar que otro usuario no tenga ya este correo
            if ($this->usuarioModel->emailEnUsoPorOtro($nuevoEmail, $userId)) {
                $_SESSION['perfil_error'] = "El correo electrónico '{$nuevoEmail}' ya está siendo utilizado por otro usuario.";
                $_SESSION['perfil_active_tab'] = 'editar';
                header("Location: perfil");
                exit;
            }

            // Actualizar correo y reiniciar estado de verificación
            $this->usuarioModel->actualizarEmailUsuario($userId, $nuevoEmail, true);
            $_SESSION['usuario_email'] = $nuevoEmail;

            // Enviar correo de verificación automáticamente al nuevo correo
            $this->enviarTokenVerificacion($userId, $nuevoEmail, $datosActuales['nombre_completo']);

            AuditLogger::registrar(
                'INFO', 
                'Autenticacion', 
                'Correo Modificado', 
                "El usuario ID #{$userId} actualizó su correo a '{$nuevoEmail}'. Se envió enlace de verificación."
            );

            $cambiosRealizados[] = "correo electrónico (hemos enviado un enlace para verificar tu nueva dirección)";
        }

        // 3. ACTUALIZACIÓN DE CÉDULA Y NOMBRE (Exclusivo para SuperAdmin)
        if ($esSuperAdmin) {
            $nombreCompleto = trim($_POST['nombre_completo'] ?? '');
            if (!empty($nombreCompleto) && $nombreCompleto !== ($datosActuales['nombre_completo'] ?? '')) {
                if (mb_strlen($nombreCompleto) < 3 || mb_strlen($nombreCompleto) > 150) {
                    $_SESSION['perfil_error'] = "El nombre completo debe tener entre 3 y 150 caracteres.";
                    $_SESSION['perfil_active_tab'] = 'editar';
                    header("Location: perfil");
                    exit;
                }

                $this->usuarioModel->actualizarNombreUsuario($userId, $nombreCompleto);
                $_SESSION['usuario_nombre'] = $nombreCompleto;
                $_SESSION['nombre'] = $nombreCompleto;
                $_SESSION['nombre_usuario'] = $nombreCompleto;
                $_SESSION['nombre_completo'] = $nombreCompleto;

                AuditLogger::registrar(
                    'INFO', 
                    'Autenticacion', 
                    'Nombre Modificado por SuperAdmin', 
                    "El superusuario actualizó su nombre a '{$nombreCompleto}' (ID: {$userId})."
                );
                $cambiosRealizados[] = "nombre completo";
            }

            $nuevaCedula = trim($_POST['cedula'] ?? '');
            if (!empty($nuevaCedula) && $nuevaCedula !== ($datosActuales['cedula'] ?? '')) {
                // Comprobar colisión de cédula con otros usuarios
                if ($this->usuarioModel->cedulaEnUsoPorOtro($nuevaCedula, $userId)) {
                    $_SESSION['perfil_error'] = "La cédula '{$nuevaCedula}' ya pertenece a otro registro en el sistema.";
                    $_SESSION['perfil_active_tab'] = 'editar';
                    header("Location: perfil");
                    exit;
                }

                $this->usuarioModel->actualizarCedulaUsuario($userId, $nuevaCedula);
                $_SESSION['usuario_cedula'] = $nuevaCedula;

                AuditLogger::registrar(
                    'SECURITY', 
                    'Autenticacion', 
                    'Cédula Modificada por SuperAdmin', 
                    "El superusuario ID #{$userId} actualizó su cédula de identidad a '{$nuevaCedula}'."
                );
                $cambiosRealizados[] = "cédula de identidad";
            }
        }

        if (!empty($cambiosRealizados)) {
            $_SESSION['perfil_exito'] = "Se actualizaron correctamente los siguientes datos: " . implode(', ', $cambiosRealizados) . ".";
            $_SESSION['perfil_active_tab'] = 'resumen';
        } else {
            $_SESSION['perfil_exito'] = "No se detectaron modificaciones en los datos ingresados.";
            $_SESSION['perfil_active_tab'] = 'editar';
        }

        header("Location: perfil");
        exit;
    }

    public function procesarVerificacionEmail() {
        Auth::requierePrivilegioMinimo(998);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: perfil");
            exit;
        }

        if (!CSRF::validarToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['perfil_error'] = "El token de seguridad ha expirado. Por favor, reintente.";
            header("Location: perfil");
            exit;
        }

        $userId = (int)($_SESSION['usuario_id'] ?? 0);
        $datos = $this->usuarioModel->obtenerPerfilCompleto($userId);
        if (!$datos) {
            $_SESSION['perfil_error'] = "No se encontraron los datos del usuario.";
            header("Location: perfil");
            exit;
        }

        $email = trim($datos['email'] ?? '');
        if (empty($email)) {
            $_SESSION['perfil_error'] = "No tienes ningún correo electrónico registrado en tu cuenta.";
            header("Location: perfil");
            exit;
        }

        if (!empty($datos['email_verified'])) {
            $_SESSION['perfil_exito'] = "Tu correo electrónico ya se encuentra verificado.";
            header("Location: perfil");
            exit;
        }

        // Validación estricta con Regex existente y librería MailService
        $regexEmail = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        if (!preg_match($regexEmail, $email)) {
            $_SESSION['perfil_error'] = "El correo registrado ({$email}) no posee una estructura de formato válida.";
            header("Location: perfil");
            exit;
        }

        $valEmail = MailService::validarEmail($email, false);
        if (!$valEmail['valido']) {
            $_SESSION['perfil_error'] = $valEmail['mensaje'];
            header("Location: perfil");
            exit;
        }

        $this->enviarTokenVerificacion($userId, $email, $datos['nombre_completo'] ?? 'Usuario');

        AuditLogger::registrar(
            'INFO', 
            'Autenticacion', 
            'Verificación de Correo Solicitada', 
            "Enlace de verificación enviado a '{$email}' para usuario ID #{$userId}."
        );

        $_SESSION['perfil_exito'] = "Hemos enviado un enlace de confirmación a tu correo: {$email}. Revisa tu bandeja de entrada o carpeta de Spam.";
        header("Location: perfil");
        exit;
    }

    private function enviarTokenVerificacion(int $userId, string $email, string $nombre): void {
        $rawToken = bin2hex(random_bytes(32));
        $this->usuarioModel->guardarTokenActivacion($userId, $rawToken);

        $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
        $baseUrl = "{$protocolo}://{$host}" . ($baseDir && $baseDir !== '/' ? $baseDir : '');
        $enlaceVerificacion = "{$baseUrl}/activar-cuenta?token={$rawToken}";

        $cuerpoHtml = "
            <h2 style='color:#121a3e; margin-top:0;'>Verificación de Correo Electrónico</h2>
            <p>Estimado(a) <strong>" . htmlspecialchars($nombre) . "</strong>,</p>
            <p>Has registrado o actualizado tu dirección de correo electrónico institucional en la plataforma CIIDI UPTTMBI.</p>
            <p style='text-align: center; margin: 30px 0;'>
                <a href='{$enlaceVerificacion}' style='background-color:#505984; color:#ffffff; padding:12px 24px; text-decoration:none; font-weight:bold; display:inline-block;'>
                    Confirmar y Activar Mi Correo
                </a>
            </p>
            <p style='font-size:0.85rem; color:#64748b;'>Si el botón no funciona, copia y pega el siguiente enlace en tu navegador:</p>
            <p style='font-size:0.8rem; word-break:break-all; color:#7090cb;'>{$enlaceVerificacion}</p>
            <p style='color:#505984; font-size:0.85rem; font-weight:bold;'>⚠ Si tú no solicitaste este cambio, puedes ignorar este mensaje.</p>
        ";

        try {
            MailService::enviarCorreoPersonalizado(
                $email, 
                "Verificación de Correo Institucional - CIIDI UPTTMBI", 
                $cuerpoHtml, 
                $nombre
            );
        } catch (Throwable) {
            // No bloquear en entornos locales de prueba sin servidor de correo
        }
    }

    public function procesarCambiarClave() {
        Auth::requierePrivilegioMinimo(998);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: perfil");
            exit;
        }

        // 1. Validación de CSRF
        if (!CSRF::validarToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['perfil_error'] = "El token de seguridad ha expirado. Por favor, reintente.";
            $_SESSION['perfil_active_tab'] = 'seguridad';
            header("Location: perfil");
            exit;
        }

        $userId = (int)($_SESSION['usuario_id'] ?? 0);
        $claveActual = $_POST['clave_actual'] ?? '';
        $claveNueva = $_POST['clave_nueva'] ?? '';
        $claveConfirmar = $_POST['clave_confirmar'] ?? '';

        // 2. Validaciones básicas
        if (empty($claveActual) || empty($claveNueva) || empty($claveConfirmar)) {
            $_SESSION['perfil_error'] = "Todos los campos de contraseña son estrictamente obligatorios.";
            $_SESSION['perfil_active_tab'] = 'seguridad';
            header("Location: perfil");
            exit;
        }

        if ($claveNueva !== $claveConfirmar) {
            $_SESSION['perfil_error'] = "La nueva contraseña y su confirmación no coinciden.";
            $_SESSION['perfil_active_tab'] = 'seguridad';
            header("Location: perfil");
            exit;
        }

        if (strlen($claveNueva) < 8) {
            $_SESSION['perfil_error'] = "La nueva contraseña debe tener al menos 8 caracteres por motivos de seguridad.";
            $_SESSION['perfil_active_tab'] = 'seguridad';
            header("Location: perfil");
            exit;
        }

        // 3. Verificación de la contraseña actual
        $hashActual = $this->usuarioModel->obtenerContrasenaHash($userId);
        if (!$hashActual || !password_verify($claveActual, $hashActual)) {
            AuditLogger::registrar(
                'WARNING', 
                'Autenticacion', 
                'Fallo Cambio Contraseña', 
                "Intento fallido de cambio de clave por contraseña actual incorrecta (Usuario ID: {$userId})."
            );

            $_SESSION['perfil_error'] = "La contraseña actual ingresada es incorrecta.";
            $_SESSION['perfil_active_tab'] = 'seguridad';
            header("Location: perfil");
            exit;
        }

        // 4. Generación de nuevo hash y actualización
        $nuevoHash = password_hash($claveNueva, PASSWORD_BCRYPT);
        $ok = $this->usuarioModel->actualizarPasswordUsuario($userId, $nuevoHash);

        if ($ok) {
            AuditLogger::registrar(
                'SECURITY', 
                'Autenticacion', 
                'Contraseña Cambiada', 
                "El usuario ID: {$userId} actualizó exitosamente su contraseña de acceso."
            );

            // Notificación por correo electrónico si está configurado
            $emailUsuario = $_SESSION['usuario_email'] ?? '';
            $nombreUsuario = $_SESSION['usuario_nombre'] ?? 'Usuario';
            if (!empty($emailUsuario)) {
                try {
                    MailService::enviarCorreoPersonalizado(
                        $emailUsuario,
                        "Aviso de Seguridad: Contraseña Modificada - CIIDI",
                        "<p>Hola <strong>" . htmlspecialchars($nombreUsuario) . "</strong>,</p>" .
                        "<p>Te informamos que la contraseña de tu cuenta institucional ha sido actualizada exitosamente el día de hoy.</p>" .
                        "<p>Si tú no realizaste este cambio, por favor contacta de inmediato al administrador del sistema.</p>",
                        $nombreUsuario
                    );
                } catch (Throwable) {
                    // Continuar sin interrumpir al usuario si el servidor SMTP no está disponible
                }
            }

            $_SESSION['perfil_exito'] = "Tu contraseña ha sido actualizada con éxito.";
            $_SESSION['perfil_active_tab'] = 'seguridad';
        } else {
            $_SESSION['perfil_error'] = "Ocurrió un error al intentar actualizar la contraseña.";
            $_SESSION['perfil_active_tab'] = 'seguridad';
        }

        header("Location: perfil");
        exit;
    }

    private function asegurarTablasEspecialidad(\PDO $pdo): void {
        try {
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS public.profesor_dimension (
                    id_usuario INT NOT NULL REFERENCES public.usuarios(id) ON DELETE CASCADE,
                    id_dimension INT NOT NULL REFERENCES public.dimensiones_operativas(id) ON DELETE CASCADE,
                    id_linea INT NOT NULL REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (id_usuario, id_dimension)
                )
            ");
        } catch (\Throwable $e) {}
    }

    private function procesarVincularLineasDocente() {
        Auth::requierePrivilegioMinimo(998);
        if (!CSRF::validarToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['perfil_error'] = "El token de seguridad ha caducado. Intente de nuevo.";
            $_SESSION['perfil_active_tab'] = 'vinculacion';
            header("Location: perfil");
            exit;
        }

        $usuario = Auth::usuario();
        $userId = (int)($usuario["id"] ?? 0);
        
        try {
            $pdo = Connection::getInstance();
            $this->asegurarTablasEspecialidad($pdo);

            $lineas = is_array($_POST["lineas"] ?? null) ? array_values(array_unique(array_filter(array_map('intval', $_POST["lineas"]), fn($v) => $v > 0))) : [];
            $dimensionesIds = is_array($_POST["dimensiones"] ?? null) ? array_values(array_unique(array_filter(array_map('intval', $_POST["dimensiones"]), fn($v) => $v > 0))) : [];

            // Validar dimensiones seleccionadas y asegurar que su línea padre también quede vinculada
            $dimensionesValidas = [];
            if (!empty($dimensionesIds)) {
                $placeholders = implode(',', array_fill(0, count($dimensionesIds), '?'));
                $stmtDimLookup = $pdo->prepare("SELECT id, id_linea FROM public.dimensiones_operativas WHERE id IN ({$placeholders}) AND activo = true");
                $stmtDimLookup->execute($dimensionesIds);
                foreach ($stmtDimLookup->fetchAll(PDO::FETCH_ASSOC) as $dRow) {
                    $idDim = (int)$dRow['id'];
                    $idLin = (int)$dRow['id_linea'];
                    $dimensionesValidas[$idDim] = $idLin;
                    if (!in_array($idLin, $lineas, true)) {
                        $lineas[] = $idLin;
                    }
                }
            }

            $esInvestigador = isset($_POST['es_investigador']) ? (!empty($_POST['es_investigador'])) : (!empty($lineas));
            
            $pdo->beginTransaction();
            // Eliminar líneas y dimensiones actuales
            $stmtDelLin = $pdo->prepare("DELETE FROM public.profesor_linea WHERE id_usuario = ?");
            $stmtDelLin->execute([$userId]);

            $stmtDelDim = $pdo->prepare("DELETE FROM public.profesor_dimension WHERE id_usuario = ?");
            $stmtDelDim->execute([$userId]);
            
            // Insertar nuevas líneas
            if (!empty($lineas)) {
                $stmtInsert = $pdo->prepare("INSERT INTO public.profesor_linea (id_usuario, id_linea) VALUES (?, ?) ON CONFLICT (id_usuario, id_linea) DO NOTHING");
                foreach ($lineas as $id_linea) {
                    if ($id_linea > 0) {
                        $stmtInsert->execute([$userId, $id_linea]);
                    }
                }
            }

            // Insertar nuevas dimensiones operativas
            if (!empty($dimensionesValidas)) {
                $stmtInsertDim = $pdo->prepare("INSERT INTO public.profesor_dimension (id_usuario, id_dimension, id_linea) VALUES (?, ?, ?) ON CONFLICT (id_usuario, id_dimension) DO NOTHING");
                foreach ($dimensionesValidas as $id_dim => $id_lin) {
                    $stmtInsertDim->execute([$userId, $id_dim, $id_lin]);
                }
            }

            // Actualizar marca de investigador en usuarios
            $stmtUpInv = $pdo->prepare("UPDATE public.usuarios SET es_investigador = ? WHERE id = ?");
            $stmtUpInv->execute([$esInvestigador ? 'true' : 'false', $userId]);

            $pdo->commit();

            AuditLogger::registrar('INFO', 'Autenticacion', 'Actualización Líneas y Dimensiones Especialidad',
                "El docente/investigador ID #{$userId} actualizó su especialidad (" . count($lineas) . " líneas y " . count($dimensionesValidas) . " dimensiones operativas).");
            
            $_SESSION["perfil_exito"] = "Tus líneas de investigación y dimensiones operativas de especialidad han sido actualizadas correctamente.";
            $_SESSION["perfil_active_tab"] = "vinculacion";
        } catch (\Throwable $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $_SESSION["perfil_error"] = "Error al actualizar las líneas y dimensiones de investigación.";
            $_SESSION["perfil_active_tab"] = "vinculacion";
        }
        
        header("Location: perfil");
        exit;
    }

    private function procesarSolicitudAcreditacionPST() {
        Auth::requierePrivilegioMinimo(998);
        if (!CSRF::validarToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['perfil_error'] = "El token de seguridad ha caducado. Intente de nuevo.";
            $_SESSION['perfil_active_tab'] = 'vinculacion';
            header("Location: perfil");
            exit;
        }

        $userId = (int)(Auth::usuario()['id'] ?? 0);
        require_once __DIR__ . '/../services/PerfilPSTService.php';

        $esPst = !empty($_POST['es_profesor_pst']);
        $trayectos = PerfilPSTService::parsearTrayectos($_POST['pst_trayectos'] ?? $_POST['pst_asignaciones'] ?? []);

        if ($esPst && empty($trayectos)) {
            $_SESSION['perfil_error'] = "Debe seleccionar al menos un trayecto en el que imparte PST.";
            $_SESSION['perfil_active_tab'] = 'vinculacion';
            header("Location: perfil");
            exit;
        }

        if (PerfilPSTService::crearSolicitud($userId, $esPst, $trayectos)) {
            AuditLogger::registrar('INFO', 'Autenticacion', 'Solicitud Acreditación PST', 
                "El docente ID {$userId} envió una solicitud de acreditación PST para trayectos: " . implode(',', $trayectos));
            $_SESSION['perfil_exito'] = "Su solicitud de acreditación como Docente PST ha sido enviada al Comité y Super Administrador para su validación.";
        } else {
            $_SESSION['perfil_error'] = "Ocurrió un error al procesar la solicitud de acreditación PST.";
        }

        $_SESSION['perfil_active_tab'] = 'vinculacion';
        header("Location: perfil");
        exit;
    }
}