<?php
// modules/VinculacionEmpresarial/controllers/ProfesorPSTController.php
//
// Panel de Profesores PST:
//  - postulaciones-pst          : Profesor ve las postulaciones dirigidas a él (Comité/Super Admin ven todas).
//  - revisar-postulacion-pst    : Profesor PST aprueba o devuelve (con motivo) una postulación.
//  - guardar-mi-perfil-pst      : Profesor actualiza sus trayectos/trimestres PST.
//  - profesores-pst             : Comité/Super Admin gestionan docentes PST y aprueban registros en línea.
//  - guardar-profesor-pst       : Comité/Super Admin editan el perfil PST de un docente.
//  - resolver-registro-docente  : Comité/Super Admin aprueban/rechazan solicitudes de registro docente.
//
// La autorización se valida en servidor por rol (rol_nombre) / nivel de privilegio, ya que los
// nombres reales de rol en BD son 'Super Administrador', 'Comité', 'Docentes' y 'Estudiantes'.

require_once CORE_PATH . 'Security/Auth.php';
require_once CORE_PATH . 'Security/CSRF.php';
require_once CORE_PATH . 'Security/AuditLogger.php';
require_once CORE_PATH . 'Services/MailService.php';
require_once __DIR__ . '/../models/ProfesorPSTModel.php';
require_once __DIR__ . '/../../Autenticacion/services/PerfilPSTService.php';

class ProfesorPSTController
{
    private ProfesorPSTModel $modelo;

    public function __construct()
    {
        $this->modelo = new ProfesorPSTModel();
    }

    // ------------------------------------------------------------------
    // AUTORIZACIÓN
    // ------------------------------------------------------------------

    private static function normalizar(string $txt): string
    {
        return strtr(mb_strtolower($txt), ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u']);
    }

    /**
     * Determina el contexto del usuario autenticado. Redirige a login si no hay sesión.
     * @return array{id:int, esAdmin:bool, esComite:bool, esProfesor:bool, esGestor:bool}
     */
    private function contexto(): array
    {
        if (!Auth::check()) {
            header("Location: login");
            exit;
        }
        $id    = (int)($_SESSION['usuario_id'] ?? 0);
        $nivel = (int)($_SESSION['nivel_privilegio'] ?? 999);
        $rol   = self::normalizar((string)($_SESSION['rol_nombre'] ?? ''));

        $esAdmin    = $nivel === 0 || str_contains($rol, 'super admin');
        $esComite   = str_contains($rol, 'comit');
        $esProfesor = str_contains($rol, 'docente') || str_contains($rol, 'profesor') || PerfilPSTService::esProfesorPST($id);

        return [
            'id'         => $id,
            'esAdmin'    => $esAdmin,
            'esComite'   => $esComite,
            'esProfesor' => $esProfesor,
            'esGestor'   => $esAdmin || $esComite,
        ];
    }

    /** Responde 403 usando la vista estándar del framework. */
    private function denegar(): void
    {
        // Nivel -1: ningún usuario (excepto Super Admin, ya autorizado antes) lo cumple -> render 403 estándar.
        Auth::requierePrivilegioMinimo(-1);
        http_response_code(403);
        exit('Acceso denegado');
    }

    private function validarCsrf(string $volverA): void
    {
        if (!CSRF::validarToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = "La sesión del formulario expiró. Recargue la página e intente nuevamente.";
            header("Location: ?ruta=" . $volverA);
            exit;
        }
    }

    // ------------------------------------------------------------------
    // PANEL DE POSTULACIONES PST (Profesor / Comité / Super Admin)
    // ------------------------------------------------------------------

    public function panel(): array
    {
        $ctx = $this->contexto();
        
        // Bloquear acceso al comité explícitamente a este panel (como pidió el usuario)
        if ($ctx['esComite'] && !$ctx['esAdmin']) {
            $_SESSION['flash_error'] = "El comité no debe validar postulaciones académicas. Debes esperar a que el profesor lo apruebe para luego gestionarlo en 'Gestión de Solicitudes'.";
            header("Location: ?ruta=empresas-inicio");
            exit;
        }

        if (!$ctx['esProfesor'] && !$ctx['esAdmin']) {
            $this->denegar();
        }

        $filtros = [
            'trayecto'   => PerfilPSTService::trayectoValido($_GET['trayecto'] ?? null) ? (int)$_GET['trayecto'] : null,
            'trimestre'  => PerfilPSTService::trimestreValido($_GET['trimestre'] ?? null) ? (int)$_GET['trimestre'] : null,
            'aprobacion' => in_array($_GET['aprobacion'] ?? '', ['Pendiente', 'Aprobado', 'Devuelto'], true) ? $_GET['aprobacion'] : null,
            'estado'     => in_array($_GET['estado'] ?? '', ['Pendiente', 'Aceptado', 'Rechazado'], true) ? $_GET['estado'] : null,
            'profesor'   => ($ctx['esGestor'] && !empty($_GET['profesor'])) ? (int)$_GET['profesor'] : null,
        ];

        // Vista por defecto: Comité/Admin ven todo; un profesor solo ve lo dirigido a él.
        $vistaTodas = $ctx['esGestor'] && (($_GET['vista'] ?? 'todas') !== 'mias');
        $idFiltroProfesor = $vistaTodas ? null : $ctx['id'];

        $postulaciones = $this->modelo->getPostulacionesPST($idFiltroProfesor, $filtros);

        $kpis = ['Pendiente' => 0, 'Aprobado' => 0, 'Devuelto' => 0];
        foreach ($postulaciones as $p) {
            $kpis[$p['aprobacion_profesor']] = ($kpis[$p['aprobacion_profesor']] ?? 0) + 1;
        }

        return [
            'ctx'               => $ctx,
            'vistaTodas'        => $vistaTodas,
            'filtros'           => $filtros,
            'postulaciones'     => $postulaciones,
            'kpis'              => $kpis,
            'catTrayectos'      => PerfilPSTService::catalogoTrayectos(),
            'catTrimestres'     => PerfilPSTService::catalogoTrimestres(),
            'profesoresPST'     => $ctx['esGestor'] ? $this->modelo->getProfesoresPST(false) : [],
            'miEsProfesorPST'   => PerfilPSTService::esProfesorPST($ctx['id']),
            'miSeleccionPST'    => PerfilPSTService::obtenerClavesAsignacion($ctx['id']),
        ];
    }

    public function revisarPostulacion(): void
    {
        $ctx = $this->contexto();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?ruta=postulaciones-pst");
            exit;
        }
        $this->validarCsrf('postulaciones-pst');

        $idPostulacion = (int)($_POST['id_postulacion'] ?? 0);
        $decision = $_POST['decision'] ?? '';
        $motivo = trim($_POST['motivo'] ?? '');
        $volver = '?ruta=postulaciones-pst' . (!empty($_POST['volver_query']) ? '&' . preg_replace('/[^a-zA-Z0-9=&_%-]/', '', $_POST['volver_query']) : '');

        if (!in_array($decision, ['Aprobado', 'Devuelto'], true)) {
            $_SESSION['flash_error'] = "Decisión no válida.";
            header("Location: " . $volver);
            exit;
        }
        if ($decision === 'Devuelto' && $motivo === '') {
            $_SESSION['flash_error'] = "Debe indicar el motivo de la devolución para informar al estudiante.";
            header("Location: " . $volver);
            exit;
        }

        $post = $this->modelo->getPostulacion($idPostulacion);
        if (!$post) {
            $_SESSION['flash_error'] = "La postulación no existe.";
            header("Location: " . $volver);
            exit;
        }

        // Solo el profesor PST elegido por el estudiante (o el Super Administrador) puede decidir.
        if ((int)$post['id_profesor_pst'] !== $ctx['id'] && !$ctx['esAdmin']) {
            $this->denegar();
        }

        if ($post['estado'] !== 'Pendiente') {
            $_SESSION['flash_error'] = "El Comité ya emitió la decisión final sobre esta postulación; no puede modificarse.";
            header("Location: " . $volver);
            exit;
        }

        if (!$this->modelo->registrarDecisionProfesor($idPostulacion, $decision, $decision === 'Devuelto' ? $motivo : ($motivo ?: null))) {
            $_SESSION['flash_error'] = "No se pudo registrar la decisión.";
            header("Location: " . $volver);
            exit;
        }

        AuditLogger::registrar('INFO', 'VinculacionEmpresarial', 'Revisión Profesor PST',
            "Postulación #{$idPostulacion} marcada como '{$decision}' por el usuario ID {$ctx['id']}.");

        // Notificar al estudiante
        if (!empty($post['correo'])) {
            $est = htmlspecialchars($post['estudiante'], ENT_QUOTES, 'UTF-8');
            $emp = htmlspecialchars($post['nombre_empresa'] ?? 'la oferta seleccionada', ENT_QUOTES, 'UTF-8');
            $tt  = htmlspecialchars(PerfilPSTService::nombreTrayecto((int)$post['trayecto']) . ' - Trimestre ' . (int)$post['trimestre'], ENT_QUOTES, 'UTF-8');
            if ($decision === 'Aprobado') {
                $asunto = 'Tu profesor PST avaló tu postulación - CIIDI';
                $cuerpo = "<p>Hola <b>{$est}</b>,</p>
                           <p>Tu profesor(a) PST ha <b>avalado</b> tu postulación al reto de <b>{$emp}</b> ({$tt}).</p>
                           <p>Ahora el Comité de Proyectos realizará la asignación final. Te notificaremos el resultado.</p>
                           <p>Saludos cordiales,<br><b>Equipo CIIDI</b></p>";
            } else {
                $mot = nl2br(htmlspecialchars($motivo, ENT_QUOTES, 'UTF-8'));
                $asunto = 'Tu postulación fue devuelta por el profesor PST - CIIDI';
                $cuerpo = "<p>Hola <b>{$est}</b>,</p>
                           <p>Tu profesor(a) PST ha <b>devuelto</b> tu postulación al reto de <b>{$emp}</b> ({$tt}).</p>
                           <div style='background:#fff1f2; padding:12px; border-left:4px solid #e11d48; color:#9f1239;'><b>Motivo:</b><br><i>{$mot}</i></div>
                           <p>Puedes corregir los datos y volver a enviar tu postulación desde la Cartelera de Oportunidades PST.</p>
                           <p>Saludos cordiales,<br><b>Equipo CIIDI</b></p>";
            }
            MailService::enviarAsincrono($post['correo'], $post['estudiante'], $asunto, $cuerpo);
        }

        $_SESSION['flash_success'] = $decision === 'Aprobado'
            ? "Postulación avalada. El Comité realizará la asignación final."
            : "Postulación devuelta al estudiante con el motivo indicado.";
        header("Location: " . $volver);
        exit;
    }

    public function guardarMiPerfil(): void
    {
        $ctx = $this->contexto();
        if (!$ctx['esProfesor'] && !$ctx['esGestor']) {
            $this->denegar();
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?ruta=postulaciones-pst");
            exit;
        }
        $this->validarCsrf('postulaciones-pst');

        $esPst = !empty($_POST['es_profesor_pst']);
        $trayectos = PerfilPSTService::parsearTrayectos($_POST['pst_trayectos'] ?? $_POST['pst_asignaciones'] ?? []);
        
        if ($ctx['esGestor']) {
            PerfilPSTService::guardarPerfil($ctx['id'], $esPst, $trayectos);
            $_SESSION['flash_success'] = "Su perfil de Docente PST fue actualizado directamente.";
        } else {
            if ($esPst && empty($trayectos)) {
                $_SESSION['flash_error'] = "Indique al menos un trayecto en el que imparte PST.";
            } elseif (PerfilPSTService::crearSolicitud($ctx['id'], $esPst, $trayectos)) {
                $_SESSION['flash_success'] = "Su solicitud de acreditación PST fue enviada al Comité y Super Administrador para validación.";
            } else {
                $_SESSION['flash_error'] = "No se pudo registrar la solicitud de acreditación PST.";
            }
        }
        header("Location: ?ruta=" . ($_POST['volver_a'] ?? 'perfil'));
        exit;
    }

    // ------------------------------------------------------------------
    // GESTIÓN DE PROFESORES PST (Comité / Super Admin)
    // ------------------------------------------------------------------

    public function gestionProfesores(): array
    {
        $ctx = $this->contexto();
        if (!$ctx['esGestor']) {
            $this->denegar();
        }
        $docentes = $this->modelo->getDocentes();
        $pendientes = array_values(array_filter($docentes, fn($d) => $d['estado_registro'] === 'pendiente'));
        $solicitudesPst = PerfilPSTService::obtenerSolicitudesPendientes();

        return [
            'ctx'            => $ctx,
            'docentes'       => $docentes,
            'pendientes'     => $pendientes,
            'solicitudesPst' => $solicitudesPst,
            'catTrayectos'   => PerfilPSTService::catalogoTrayectos(),
            'catTrimestres'  => PerfilPSTService::catalogoTrimestres(),
        ];
    }

    public function guardarProfesor(): void
    {
        $ctx = $this->contexto();
        if (!$ctx['esGestor']) {
            $this->denegar();
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?ruta=profesores-pst");
            exit;
        }
        $this->validarCsrf('profesores-pst');

        $idProfesor = (int)($_POST['id_profesor'] ?? 0);
        $usuario = $this->modelo->getUsuarioBasico($idProfesor);
        $esPst = !empty($_POST['es_profesor_pst']);
        $trayectos = PerfilPSTService::parsearTrayectos($_POST['pst_trayectos'] ?? $_POST['pst_asignaciones'] ?? []);

        if (!$usuario) {
            $_SESSION['flash_error'] = "Docente no encontrado.";
        } elseif ($esPst && empty($trayectos)) {
            $_SESSION['flash_error'] = "Indique al menos un trayecto para el profesor PST.";
        } elseif (PerfilPSTService::guardarPerfil($idProfesor, $esPst, $trayectos)) {
            AuditLogger::registrar('INFO', 'VinculacionEmpresarial', 'Perfil Profesor PST',
                "Perfil PST de '{$usuario['nombre_completo']}' actualizado por el usuario ID {$ctx['id']} (PST: " . ($esPst ? 'Sí' : 'No') . ").");
            $_SESSION['flash_success'] = "Perfil PST de {$usuario['nombre_completo']} actualizado.";
        } else {
            $_SESSION['flash_error'] = "No se pudo actualizar el perfil PST.";
        }
        header("Location: ?ruta=profesores-pst");
        exit;
    }

    public function resolverRegistro(): void
    {
        $ctx = $this->contexto();
        if (!$ctx['esGestor']) {
            $this->denegar();
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?ruta=profesores-pst");
            exit;
        }
        $this->validarCsrf('profesores-pst');

        $idUsuario = (int)($_POST['id_usuario'] ?? 0);
        $aprobar = ($_POST['accion'] ?? '') === 'aprobar';
        $motivo = trim($_POST['motivo'] ?? '');
        $usuario = $this->modelo->getUsuarioBasico($idUsuario);

        if (!$usuario || !$this->modelo->resolverRegistroDocente($idUsuario, $aprobar)) {
            $_SESSION['flash_error'] = "La solicitud no existe o ya fue procesada.";
            header("Location: ?ruta=profesores-pst");
            exit;
        }

        AuditLogger::registrar('INFO', 'VinculacionEmpresarial', $aprobar ? 'Registro Docente Aprobado' : 'Registro Docente Rechazado',
            "Solicitud de registro docente de '{$usuario['nombre_completo']}' (C.I: {$usuario['cedula']}) " . ($aprobar ? 'aprobada' : 'rechazada') . " por el usuario ID {$ctx['id']}.");

        if (!empty($usuario['email'])) {
            $nom = htmlspecialchars($usuario['nombre_completo'], ENT_QUOTES, 'UTF-8');
            if ($aprobar) {
                MailService::enviarAsincrono($usuario['email'], $usuario['nombre_completo'], 'Cuenta docente aprobada - CIIDI',
                    "<p>Hola <b>{$nom}</b>,</p><p>Su solicitud de registro como <b>docente</b> fue <b>aprobada</b>. Ya puede iniciar sesión en el Sistema CIIDI con su cédula y la contraseña que definió.</p><p>Saludos cordiales,<br><b>Equipo CIIDI</b></p>");
            } else {
                $mot = $motivo !== '' ? "<div style='background:#fff1f2; padding:12px; border-left:4px solid #e11d48; color:#9f1239;'><b>Motivo:</b><br><i>" . nl2br(htmlspecialchars($motivo, ENT_QUOTES, 'UTF-8')) . "</i></div>" : '';
                MailService::enviarAsincrono($usuario['email'], $usuario['nombre_completo'], 'Solicitud de registro docente no aprobada - CIIDI',
                    "<p>Hola <b>{$nom}</b>,</p><p>Su solicitud de registro como docente <b>no fue aprobada</b>.</p>{$mot}<p>Si considera que se trata de un error, contacte a la coordinación.</p><p>Saludos cordiales,<br><b>Equipo CIIDI</b></p>");
            }
        }

        $_SESSION['flash_success'] = $aprobar
            ? "Cuenta docente de {$usuario['nombre_completo']} aprobada y activada."
            : "Solicitud de {$usuario['nombre_completo']} rechazada.";
        header("Location: ?ruta=profesores-pst");
        exit;
    }

    public function resolverSolicitudAcreditacion(): void
    {
        $ctx = $this->contexto();
        if (!$ctx['esGestor']) {
            $this->denegar();
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?ruta=profesores-pst");
            exit;
        }
        $this->validarCsrf('profesores-pst');

        $idSolicitud = (int)($_POST['id_solicitud'] ?? 0);
        $aprobar = ($_POST['accion'] ?? '') === 'aprobar';
        $motivo = trim($_POST['motivo'] ?? '');

        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT s.*, u.nombre_completo, u.email FROM solicitudes_docentes_pst s JOIN usuarios u ON s.id_profesor = u.id WHERE s.id = ?");
        $stmt->execute([$idSolicitud]);
        $sol = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$sol || !PerfilPSTService::resolverSolicitud($idSolicitud, $aprobar, $motivo, $ctx['id'])) {
            $_SESSION['flash_error'] = "La solicitud no existe o ya fue procesada.";
            header("Location: ?ruta=profesores-pst");
            exit;
        }

        $profNombre = $sol['nombre_completo'];
        AuditLogger::registrar('INFO', 'VinculacionEmpresarial', $aprobar ? 'Acreditación PST Aprobada' : 'Acreditación PST Rechazada',
            "Solicitud de acreditación PST de '{$profNombre}' " . ($aprobar ? 'aprobada' : 'rechazada') . " por el usuario ID {$ctx['id']}.");

        if (!empty($sol['email'])) {
            $nom = htmlspecialchars($profNombre, ENT_QUOTES, 'UTF-8');
            if ($aprobar) {
                $trNombres = array_map(fn($t) => PerfilPSTService::nombreTrayecto((int)$t), json_decode($sol['trayectos'] ?? '[]', true) ?: []);
                $trTexto = !empty($trNombres) ? implode(', ', $trNombres) : 'PST General';
                MailService::enviarAsincrono($sol['email'], $profNombre, 'Acreditación PST Aprobada - CIIDI',
                    "<p>Estimado(a) <b>{$nom}</b>,</p><p>Su solicitud para impartir/tutorizar <b>Proyecto Sociotecnológico (PST)</b> ha sido <b>aprobada</b> para: <b>{$trTexto}</b>.</p><p>Los estudiantes de estos trayectos ahora podrán seleccionarle como tutor.</p><p>Saludos cordiales,<br><b>Equipo CIIDI</b></p>");
            } else {
                $mot = $motivo !== '' ? "<div style='background:#fff1f2; padding:12px; border-left:4px solid #e11d48; color:#9f1239;'><b>Motivo:</b><br><i>" . nl2br(htmlspecialchars($motivo, ENT_QUOTES, 'UTF-8')) . "</i></div>" : '';
                MailService::enviarAsincrono($sol['email'], $profNombre, 'Solicitud de acreditación PST - CIIDI',
                    "<p>Estimado(a) <b>{$nom}</b>,</p><p>Su solicitud de acreditación como docente PST no fue aprobada en esta oportunidad.</p>{$mot}<p>Si considera que se trata de un error, contacte a la coordinación de proyectos.</p><p>Saludos cordiales,<br><b>Equipo CIIDI</b></p>");
            }
        }

        $_SESSION['flash_success'] = $aprobar
            ? "Acreditación PST de {$profNombre} aprobada exitosamente."
            : "Solicitud de acreditación de {$profNombre} rechazada.";
        header("Location: ?ruta=profesores-pst");
        exit;
    }
}
