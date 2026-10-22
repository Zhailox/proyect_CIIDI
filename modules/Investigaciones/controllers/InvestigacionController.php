<?php
// modules/Investigaciones/controllers/InvestigacionController.php
require_once __DIR__ . '/../models/InvestigacionModel.php';
require_once CORE_PATH . 'Security/Auth.php';
require_once __DIR__ . '/../../SuperAdmin/services/SystemConfigService.php';

class InvestigacionController {

    private InvestigacionModel $model;
    private int $nivelAdmin;
    private int $nivelPublico;

    public function __construct() {
        $this->model = new InvestigacionModel();
        $this->nivelAdmin = SystemConfigService::get('accesos_modulos.investigaciones.admin', 1);
        $this->nivelPublico = SystemConfigService::get('accesos_modulos.investigaciones.publico', 999);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // VISTAS PÚBLICAS
    // ──────────────────────────────────────────────────────────────────────────

    /** Cartelera pública — visible para todos, sin restricción de nivel */
    public function mostrarCartelera(): array {
        $lineas = $this->model->obtenerLineas();

        $filtros = [];
        if (!empty($_GET['q'])) {
            $filtros['busqueda'] = trim(strip_tags($_GET['q']));
        }
        if (!empty($_GET['linea'])) {
            $filtros['id_linea'] = (int)$_GET['linea'];
        }
        if (!empty($_GET['estado'])) {
            $filtros['estado'] = trim(strip_tags($_GET['estado']));
        }

        // Paginación 9 tarjetas (cuadrícula 3×3)
        $filtros['por_pagina'] = 9;
        $filtros['pagina']     = max(1, (int)($_GET['page'] ?? 1));

        $resultado       = $this->model->listarInvestigaciones($filtros);
        $investigaciones = $resultado['items'];
        $paginacion = [
            'pagina'     => $resultado['pagina'],
            'paginas'    => $resultado['paginas'],
            'total'      => $resultado['total'],
            'por_pagina' => $resultado['por_pagina'],
        ];
        $busqueda = $_GET['q'] ?? '';

        // Datos de postulación del usuario autenticado
        $misPostulaciones = [];
        $postuladas_ids   = [];
        $is_logged        = Auth::check();
        if ($is_logged) {
            $user             = Auth::usuario();
            $misPostulaciones = $this->model->obtenerMisPostulaciones((int)$user['id']);
            $postuladas_ids   = array_column($misPostulaciones, 'id_investigacion');
        }

        return compact(
            'lineas', 'investigaciones', 'busqueda',
            'paginacion', 'misPostulaciones', 'postuladas_ids', 'is_logged'
        );
    }

    /**
     * Panel de Postulaciones (vista Kanban por trayecto).
     * RBAC: docentes/admin.
     * Estudiantes usan el drawer de la cartelera directamente.
     */
    public function mostrarPanelPostulaciones(): array {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'auditar', 'Investigaciones'); // RBAC — NO accesible para estudiantes
        $user = Auth::usuario();

        $lineas  = $this->model->obtenerLineas();
        $filtros = ['estado' => 'Abierta'];
        if (!empty($_GET['linea'])) {
            $filtros['id_linea'] = (int)$_GET['linea'];
        }

        $resultado       = $this->model->listarInvestigaciones($filtros);
        $investigaciones = $resultado['items'];

        $agrupadas = ['t1' => [], 't2' => [], 't3' => [], 't4' => [], 'maestria' => []];
        foreach ($investigaciones as $inv) {
            $trayecto = $inv['trayecto'] ?? 't4';
            $agrupadas[array_key_exists($trayecto, $agrupadas) ? $trayecto : 't4'][] = $inv;
        }

        $filtro_linea     = $_GET['linea'] ?? '';
        $misPostulaciones = $this->model->obtenerMisPostulaciones((int)$user['id']);
        $postuladas_ids   = array_column($misPostulaciones, 'id_investigacion');

        return compact('agrupadas', 'lineas', 'filtro_linea', 'misPostulaciones', 'postuladas_ids');
    }

    /** Procesar postulación de estudiante (autenticado, privilegio <= 10) */
    public function procesarPostulacion(): void {
        Auth::requierePrivilegioMinimo(10, 'crear', 'Investigaciones');
        $user = Auth::usuario();

        $id_inv     = (int)($_POST['id_investigacion'] ?? 0);
        $motivacion = trim($_POST['motivacion'] ?? '');
        $portafolio = trim($_POST['portafolio'] ?? '');

        if (empty($id_inv) || empty($motivacion)) {
            $_SESSION['flash_error'] = 'Debe completar el mensaje de motivación.';
            header('Location: ?ruta=investigaciones');
            exit;
        }

        if (!empty($portafolio)) {
            $motivacion .= "\n\nEnlace al Portafolio: " . $portafolio;
        }

        $ok = $this->model->postularEstudiante($id_inv, (int)$user['id'], $motivacion);

        if ($ok) {
            $_SESSION['flash_success'] = '¡Tu postulación ha sido enviada con éxito! El docente a cargo revisará tu perfil.';
        } else {
            $_SESSION['flash_error'] = 'Ya te has postulado a este proyecto anteriormente.';
        }

        header('Location: ?ruta=investigaciones');
        exit;
    }

    /** Directorio de investigadores — público */
    public function mostrarInvestigadores(): array {
        $busqueda = trim(strip_tags($_GET['q'] ?? ''));
        $id_linea = (int)($_GET['linea'] ?? 0);

        $filtros = [];
        if ($busqueda !== '') {
            $filtros['busqueda'] = $busqueda;
        }
        if ($id_linea > 0) {
            $filtros['id_linea'] = $id_linea;
        }

        $usuarioActual = Auth::usuario();
        $esSuperAdmin = $usuarioActual !== null && (
            (int)($usuarioActual['nivel'] ?? 999) === 0
            || mb_strtolower(trim((string)($usuarioActual['rol'] ?? ''))) === 'super administrador'
        );

        $investigadores = $this->model->obtenerInvestigadores($filtros);
        if (!$esSuperAdmin) {
            foreach ($investigadores as &$inv) {
                unset($inv['rol_nombre'], $inv['id_rol']);
            }
            unset($inv);
        }

        $lineas = $this->model->obtenerLineas();

        return compact('investigadores', 'lineas', 'busqueda', 'id_linea', 'esSuperAdmin');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // PROFESORES Y GESTIÓN DE PROYECTOS
    // ──────────────────────────────────────────────────────────────────────────

    /** Mis investigaciones con paginación (o todas para Super Administrador) */
    public function mostrarMisInvestigaciones(): array {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'auditar', 'Investigaciones');
        $user = Auth::usuario();
        $esSuperAdmin = ((int)($user['nivel'] ?? 999) === 0)
            || (mb_strtolower(trim((string)($user['rol'] ?? ''))) === 'super administrador');

        $alcance = $esSuperAdmin ? trim((string)($_GET['alcance'] ?? 'todas')) : 'mias';
        if (!in_array($alcance, ['todas', 'mias'], true)) {
            $alcance = 'todas';
        }

        $filtros = [
            'por_pagina' => 10,
            'pagina'     => max(1, (int)($_GET['page'] ?? 1)),
        ];
        if (!empty($_GET['q'])) {
            $filtros['busqueda'] = trim(strip_tags($_GET['q']));
        }
        if (!empty($_GET['estado'])) {
            $filtros['estado'] = trim(strip_tags($_GET['estado']));
        }
        if (!empty($_GET['linea'])) {
            $filtros['id_linea'] = (int)$_GET['linea'];
        }
        if (!empty($_GET['profesor'])) {
            $filtros['id_profesor'] = (int)$_GET['profesor'];
        }

        if ($esSuperAdmin && $alcance === 'todas') {
            $resultado = $this->model->obtenerTodasAdmin($filtros);
        } else {
            $resultado = $this->model->obtenerMisInvestigaciones((int)$user['id'], $filtros);
        }

        $investigaciones = $resultado['items'];
        $paginacion = [
            'pagina'     => $resultado['pagina'],
            'paginas'    => $resultado['paginas'],
            'total'      => $resultado['total'],
            'por_pagina' => $resultado['por_pagina'],
        ];

        $lineas = $this->model->obtenerLineas();
        $responsablesDisponibles = $this->model->obtenerListaResponsablesDisponibles();

        return compact('investigaciones', 'paginacion', 'esSuperAdmin', 'alcance', 'lineas', 'responsablesDisponibles');
    }

    /** Formulario de creación */
    public function mostrarFormCrear(): array {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'crear', 'Investigaciones');
        $usuario_actual = Auth::usuario();
        $lineas = $this->model->obtenerLineas();
        $investigacion = [
            'id' => 0, 'titulo' => '', 'planteamiento_problema' => '',
            'objetivo_general' => '', 'id_linea' => '', 'id_dimension' => '',
            'cupos_disponibles' => 3, 'estado' => 'Abierta', 'trayecto' => 't4', 'tag_que' => 'Proyecto',
            'lineas_array' => [], 'dimensiones_array' => [],
            'id_profesor' => (int)($usuario_actual['id'] ?? 0),
        ];
        $todasDimensiones = $this->model->obtenerTodasDimensiones();
        $responsablesDisponibles = $this->model->obtenerListaResponsablesDisponibles();
        $from_admin = !empty($_GET['from_admin']);
        return compact('lineas', 'investigacion', 'todasDimensiones', 'responsablesDisponibles', 'usuario_actual', 'from_admin');
    }

    /** Formulario de edición + verificación de propiedad o SuperAdmin/Comité */
    public function mostrarFormEditar(): array {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'editar', 'Investigaciones');
        $usuario_actual = Auth::usuario();
        $id   = (int)($_GET['id'] ?? 0);

        $investigacion = $this->model->obtenerPorId($id);
        if (!$investigacion || ($investigacion['id_profesor'] != $usuario_actual['id'] && (int)$usuario_actual['nivel'] > 1)) {
            $_SESSION['flash_error'] = 'No tienes permiso para editar esta investigación.';
            header('Location: ?ruta=mis-investigaciones');
            exit;
        }

        $lineas = $this->model->obtenerLineas();
        $todasDimensiones = $this->model->obtenerTodasDimensiones();
        $responsablesDisponibles = $this->model->obtenerListaResponsablesDisponibles();
        $from_admin = !empty($_GET['from_admin']);

        return compact('lineas', 'todasDimensiones', 'investigacion', 'responsablesDisponibles', 'usuario_actual', 'from_admin');
    }

    /** Guardar nueva investigación */
    public function guardarInvestigacion(): void {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'crear', 'Investigaciones');
        $user = Auth::usuario();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $id_profesor = (int)$user['id'];
        if (!empty($_POST['id_profesor']) && (int)$_POST['id_profesor'] > 0) {
            $id_profesor = (int)$_POST['id_profesor'];
        }

        $datos = [
            'titulo'                  => trim($_POST['titulo'] ?? ''),
            'planteamiento_problema'  => trim($_POST['planteamiento_problema'] ?? ''),
            'objetivo_general'        => trim($_POST['objetivo_general'] ?? ''),
            'id_linea'                => $_POST['id_linea'] ?? [],
            'id_dimension'            => $_POST['id_dimension'] ?? [],
            'cupos_disponibles'       => (int)($_POST['cupos_disponibles'] ?? 3),
            'estado'                  => $_POST['estado'] ?? 'Abierta',
            'trayecto'                => $_POST['trayecto'] ?? 't4',
            'tag_que'                 => $_POST['tag_que'] ?? 'Proyecto',
            'id_profesor'             => $id_profesor,
        ];

        // Asegurarse de que id_linea y id_dimension sean arrays
        if (!is_array($datos['id_linea'])) $datos['id_linea'] = $datos['id_linea'] ? [(int)$datos['id_linea']] : [];
        if (!is_array($datos['id_dimension'])) $datos['id_dimension'] = $datos['id_dimension'] ? [(int)$datos['id_dimension']] : [];

        // Imagen
        $imagenUrl = '';
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagenUrl = $this->subirImagen($_FILES['imagen']);
        } elseif (!empty($_POST['imagen_url'])) {
            $imagenUrl = trim($_POST['imagen_url']);
        }
        if ($imagenUrl) {
            $datos['imagen'] = $imagenUrl;
        }

        $fromAdmin = ((int)$user['nivel'] <= 1 && !empty($_POST['from_admin']));

        if (empty($datos['titulo']) || empty($datos['id_linea'])) {
            $_SESSION['flash_error'] = 'El título y al menos una línea de investigación son obligatorios.';
            header('Location: ?ruta=crear-investigacion' . ($fromAdmin ? '&from_admin=1' : ''));
            exit;
        }

        $id = $this->model->crearInvestigacion($datos, $id_profesor);

        if ($id) {
            $_SESSION['flash_success'] = 'Investigación creada exitosamente.';
            header('Location: ' . ($fromAdmin ? '?ruta=panel-investigaciones-admin' : '?ruta=mis-investigaciones'));
        } else {
            $_SESSION['flash_error'] = 'Error al crear la investigación.';
            header('Location: ?ruta=crear-investigacion' . ($fromAdmin ? '&from_admin=1' : ''));
        }
        exit;
    }

    /** Actualizar investigación + verificación de propiedad o SuperAdmin/Comité en modelo */
    public function actualizarInvestigacion(): void {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'editar', 'Investigaciones');
        $user = Auth::usuario();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $id    = (int)($_POST['id'] ?? 0);
        $datos = [
            'titulo'                 => trim($_POST['titulo'] ?? ''),
            'planteamiento_problema' => trim($_POST['planteamiento_problema'] ?? ''),
            'objetivo_general'       => trim($_POST['objetivo_general'] ?? ''),
            'id_linea'               => $_POST['id_linea'] ?? [],
            'id_dimension'           => $_POST['id_dimension'] ?? [],
            'cupos_disponibles'      => (int)($_POST['cupos_disponibles'] ?? 3),
            'estado'                 => $_POST['estado'] ?? 'Abierta',
            'trayecto'               => $_POST['trayecto'] ?? 't4',
            'tag_que'                => $_POST['tag_que'] ?? 'Proyecto',
        ];

        if (!empty($_POST['id_profesor']) && (int)$_POST['id_profesor'] > 0) {
            $datos['id_profesor'] = (int)$_POST['id_profesor'];
        }

        // Asegurarse de que id_linea y id_dimension sean arrays
        if (!is_array($datos['id_linea'])) $datos['id_linea'] = $datos['id_linea'] ? [(int)$datos['id_linea']] : [];
        if (!is_array($datos['id_dimension'])) $datos['id_dimension'] = $datos['id_dimension'] ? [(int)$datos['id_dimension']] : [];

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagenUrl = $this->subirImagen($_FILES['imagen']);
            if ($imagenUrl) {
                $datos['imagen'] = $imagenUrl;
            }
        } elseif (!empty($_POST['imagen_url'])) {
            $datos['imagen'] = trim($_POST['imagen_url']);
        }

        $ok = $this->model->actualizarInvestigacion($id, $datos, (int)$user['id'], (int)$user['nivel']);

        if ($ok) {
            $_SESSION['flash_success'] = 'Investigación actualizada exitosamente.';
        } else {
            $_SESSION['flash_error'] = 'No tienes permiso o ocurrió un error al actualizar.';
        }

        $redir = ((int)$user['nivel'] <= 1 && !empty($_POST['from_admin']))
            ? '?ruta=panel-investigaciones-admin'
            : '?ruta=mis-investigaciones';
        header("Location: $redir");
        exit;
    }

    /** Eliminar investigación + verificación de propiedad o SuperAdmin/Comité en modelo */
    public function eliminarInvestigacion(): void {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'eliminar', 'Investigaciones');
        $user = Auth::usuario();
        $id   = (int)($_POST['id'] ?? 0);

        $ok = $this->model->eliminarInvestigacion($id, (int)$user['id'], (int)$user['nivel']);

        if ($ok) {
            $_SESSION['flash_success'] = 'Investigación eliminada correctamente.';
        } else {
            $_SESSION['flash_error'] = 'Error al eliminar la investigación o sin permisos.';
        }

        $redir = ((int)$user['nivel'] <= 1 && !empty($_POST['from_admin']))
            ? '?ruta=panel-investigaciones-admin'
            : '?ruta=mis-investigaciones';
        header("Location: $redir");
        exit;
    }

    /** Mis postulantes con paginación */
    public function mostrarMisPostulantes(): array {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'auditar', 'Investigaciones');
        $user = Auth::usuario();

        $filtros = [
            'por_pagina' => 10,
            'pagina'     => max(1, (int)($_GET['page'] ?? 1)),
        ];

        $resultado    = $this->model->obtenerPostulantesDeMiProyecto((int)$user['id'], $filtros);
        $postulaciones = $resultado['items'];
        $paginacion = [
            'pagina'     => $resultado['pagina'],
            'paginas'    => $resultado['paginas'],
            'total'      => $resultado['total'],
            'por_pagina' => $resultado['por_pagina'],
        ];

        // Para los stats necesitamos el total sin paginar — consultamos todo de forma eficiente
        $stats = $this->model->obtenerEstadisticasPostulantes((int)$user['id']);

        return compact('postulaciones', 'paginacion', 'stats');
    }

    /** Responder postulación (Aceptar/Rechazar) */
    public function responderPostulacion(): void {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'editar', 'Investigaciones');
        $user = Auth::usuario();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $id_postulacion = (int)($_POST['id_postulacion'] ?? 0);
        $estado         = $_POST['estado'] ?? ''; // Aceptado | Rechazado

        if (in_array($estado, ['Aceptado', 'Rechazado'], true)) {
            $ok = $this->model->responderPostulacion(
                $id_postulacion, $estado, (int)$user['id'], (int)$user['nivel']
            );
            if ($ok) {
                $_SESSION['flash_success'] = "Postulación marcada como $estado.";
            } else {
                $_SESSION['flash_error'] = 'Error al procesar la respuesta o falta de permisos.';
            }
        }

        $redir = ((int)$user['nivel'] <= 1 && !empty($_POST['from_admin']))
            ? '?ruta=panel-investigaciones-admin'
            : '?ruta=mis-postulantes';
        header("Location: $redir");
        exit;
    }

    // ──────────────────────────────────────────────────────────────────────────
    // ADMIN (SuperAdmin y Comité)
    // ──────────────────────────────────────────────────────────────────────────

    /** Panel administrativo de investigaciones */
    public function mostrarPanelAdmin(): array {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'administrar', 'Investigaciones');
        $user = Auth::usuario();
        $esSuperAdmin = ((int)($user['nivel'] ?? 999) === 0)
            || (mb_strtolower(trim((string)($user['rol'] ?? ''))) === 'super administrador');

        // Configuración de paginación persistida en sesión
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['config_paginacion'])) {
            $nuevoPorPagina = max(1, min(100, (int)($_POST['por_pagina_inv'] ?? 12)));
            $_SESSION['inv_admin_por_pagina'] = $nuevoPorPagina;
            $_SESSION['flash_success'] = 'Configuración de paginación guardada.';
            header('Location: ?ruta=panel-investigaciones-admin');
            exit;
        }
        $porPaginaInv = (int)($_SESSION['inv_admin_por_pagina'] ?? 12);

        $filtros = [
            'por_pagina' => $porPaginaInv,
            'pagina'     => max(1, (int)($_GET['page'] ?? 1)),
        ];
        if (!empty($_GET['estado'])) {
            $filtros['estado'] = trim(strip_tags($_GET['estado']));
        }
        if (!empty($_GET['linea'])) {
            $filtros['id_linea'] = (int)$_GET['linea'];
        }
        if (!empty($_GET['profesor'])) {
            $filtros['id_profesor'] = (int)$_GET['profesor'];
        }
        if (!empty($_GET['q'])) {
            $filtros['busqueda'] = trim(strip_tags($_GET['q']));
        }

        $resultado       = $this->model->obtenerTodasAdmin($filtros);
        $investigaciones = $resultado['items'];
        $paginacion = [
            'pagina'     => $resultado['pagina'],
            'paginas'    => $resultado['paginas'],
            'total'      => $resultado['total'],
            'por_pagina' => $resultado['por_pagina'],
        ];

        $postulaciones           = $this->model->obtenerPostulacionesAdmin();
        $lineas                  = $this->model->obtenerLineas();
        $responsablesDisponibles = $this->model->obtenerListaResponsablesDisponibles();

        return compact('investigaciones', 'paginacion', 'postulaciones', 'lineas', 'porPaginaInv', 'responsablesDisponibles', 'esSuperAdmin');
    }

    /** Cambiar estado y/o responsable de investigación */
    public function cambiarEstado(): void {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'administrar', 'Investigaciones');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $id          = (int)($_POST['id'] ?? 0);
        $estado      = $_POST['estado'] ?? '';
        $id_profesor = (int)($_POST['id_profesor'] ?? 0);

        $mensajes = [];
        if ($id > 0 && $estado !== '') {
            if ($this->model->cambiarEstadoInvestigacion($id, $estado)) {
                $mensajes[] = "estado actualizado a '$estado'";
            }
        }
        if ($id > 0 && $id_profesor > 0) {
            if ($this->model->reasignarResponsableInvestigacion($id, $id_profesor)) {
                $mensajes[] = 'responsable reasignado';
            }
        }

        if (!empty($mensajes)) {
            $_SESSION['flash_success'] = 'Investigación actualizada (' . implode(', ', $mensajes) . ').';
        } else {
            $_SESSION['flash_error'] = 'No se pudo actualizar la investigación.';
        }

        $redir = !empty($_POST['from_mis_inv'])
            ? '?ruta=mis-investigaciones'
            : '?ruta=panel-investigaciones-admin';
        header("Location: $redir");
        exit;
    }

    // ──────────────────────────────────────────────────────────────────────────
    // API INTERNA
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * API: Retorna dimensiones operativas de una línea.
     * Responde JSON; el routing lo marca como es_api=true.
     */
    public function apiDimensiones(): void {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'auditar', 'Investigaciones');

        header('Content-Type: application/json; charset=utf-8');

        $id_linea = (int)($_GET['id_linea'] ?? 0);
        if ($id_linea <= 0) {
            echo json_encode(['ok' => false, 'dimensiones' => []]);
            exit;
        }

        $dimensiones = $this->model->obtenerDimensiones($id_linea);
        echo json_encode(['ok' => true, 'dimensiones' => $dimensiones], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // ──────────────────────────────────────────────────────────────────────────
    // UTILIDADES PRIVADAS
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Sube imagen con validación MIME real (finfo) + extensión.
     * Tipos permitidos: JPEG, PNG, WebP.
     */
    private function subirImagen(array $file): string {
        $targetDir = dirname(__DIR__, 3) . '/public/uploads/investigaciones/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // MIME real detectado por contenido (no confiar en $_FILES['type'])
        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $realMime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $mimeToExt = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
        ];

        if (!array_key_exists($realMime, $mimeToExt)) {
            $_SESSION['flash_error'] = 'Formato de imagen no permitido. Use JPG, PNG o WebP.';
            return '';
        }

        // Validar también la extensión declarada del archivo original
        $extOriginal = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($extOriginal, $allowedExts, true)) {
            $_SESSION['flash_error'] = 'Extensión no permitida. Use .jpg, .jpeg, .png o .webp.';
            return '';
        }

        // Nombre único derivado del MIME real (ignora extensión declarada)
        $safeExt    = $mimeToExt[$realMime];
        $fileName   = uniqid('inv_', true) . '.' . $safeExt;
        $targetPath = $targetDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return 'uploads/investigaciones/' . $fileName;
        }

        $_SESSION['flash_error'] = 'No se pudo guardar el archivo. Verifica los permisos de la carpeta.';
        return '';
    }
}
