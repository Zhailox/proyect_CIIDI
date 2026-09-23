<?php
// modules/SuperAdmin/controllers/GestorUsuariosController.php
require_once CORE_PATH . 'Security/Auth.php';
require_once CORE_PATH . 'Services/MailService.php';
require_once __DIR__ . '/../models/AdminUsuarioModel.php';

class GestorUsuariosController {
    
    private $adminModel;

    public function __construct() {
        $this->adminModel = new AdminUsuarioModel();
    }

    public function index() {
        Auth::requierePrivilegioMinimo(0);

        $cedulaBusqueda = trim($_GET['cedula'] ?? '');
        $resultadoBusqueda = null;
        $mensajeError = null;

        if (!empty($cedulaBusqueda)) {
            $resultadoBusqueda = $this->adminModel->buscarPorCedula($cedulaBusqueda);
            if (!$resultadoBusqueda) {
                $mensajeError = "No se encontró ningún usuario con la cédula: " . htmlspecialchars($cedulaBusqueda);
            }
        }

        // Escanear dinámicamente los módulos instalados (Excluyendo el Core)
        $modulosInstalados = [];
        $carpetas = array_diff(scandir(MODULES_PATH), array('.', '..'));
        foreach ($carpetas as $c) {
            if (is_dir(MODULES_PATH . $c) && !in_array($c, ['Autenticacion', 'SuperAdmin'])) {
                $modulosInstalados[] = $c;
            }
        }

        $accionesDisponibles = ['crear', 'editar', 'eliminar', 'auditar'];

        return [
            'modulosInstalados' => $modulosInstalados,
            'accionesDisponibles' => $accionesDisponibles,
            'cedulaBusqueda' => $cedulaBusqueda,
            'usuarioEncontrado' => $resultadoBusqueda,
            'mensajeError' => $mensajeError,
            'profesores' => $this->adminModel->obtenerProfesores(),
            'todosLosUsuarios' => $this->adminModel->obtenerTodosLosUsuarios(),
            'matrizRBAC' => $this->obtenerMatrizRBAC(),
            'roles' => $this->adminModel->obtenerRoles(),
            'privilegios' => $this->adminModel->obtenerPrivilegios()
        ];
    }

    private function obtenerMatrizRBAC(): array {
        $db = Connection::getInstance();
        $stmt = $db->query("SELECT nivel_privilegio, modulo, permisos FROM matriz_rbac");
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $matriz = [];
        foreach ($resultados as $row) {
            $nivel = $row['nivel_privilegio'];
            $modulo = $row['modulo'];
            $permisos = is_string($row['permisos']) ? json_decode($row['permisos'], true) : $row['permisos'];
            $matriz[$nivel][$modulo] = $permisos;
        }
        return $matriz;
    }

    public function guardarMatrizRBAC() {
        Auth::requierePrivilegioMinimo(0);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rawMatrix = $_POST['matrix'] ?? [];
            
            $db = Connection::getInstance();
            $db->beginTransaction();
            try {
                // Borrar configuración vieja e insertar la nueva de golpe
                $db->exec("TRUNCATE matriz_rbac");
                $stmt = $db->prepare("INSERT INTO matriz_rbac (nivel_privilegio, modulo, permisos) VALUES (?, ?, ?)");
                
                foreach ($rawMatrix as $nivel => $modulos) {
                    if (is_array($modulos)) {
                        foreach ($modulos as $nombreModulo => $permisos) {
                            $permisosProcesados = [];
                            foreach ($permisos as $accion => $val) {
                                $permisosProcesados[$accion] = ($val === '1' || $val === 1 || $val === true);
                            }
                            $stmt->execute([$nivel, $nombreModulo, json_encode($permisosProcesados)]);
                        }
                    }
                }
                $db->commit();

                // Revocación masiva por Nivel de Privilegio modificado en la Matriz RBAC
                try {
                    $nivelesModificados = array_keys($rawMatrix);
                    if (!empty($nivelesModificados)) {
                        $placeholders = implode(',', array_fill(0, count($nivelesModificados), '?'));
                        $stmtUsers = $db->prepare("
                            SELECT u.id 
                            FROM usuarios u
                            JOIN roles r ON u.id_rol = r.id
                            JOIN privilegios p ON r.privilegio_id = p.privilegio_id
                            WHERE p.nivel_privilegio IN ({$placeholders})
                        ");
                        $stmtUsers->execute($nivelesModificados);
                        $afectados = $stmtUsers->fetchAll(PDO::FETCH_COLUMN);

                        if (!empty($afectados)) {
                            $archivoSesiones = CORE_PATH . '../storage/revoked_sessions.json';
                            $revogadas = file_exists($archivoSesiones) ? (json_decode(file_get_contents($archivoSesiones), true) ?: []) : [];
                            $miUsuarioId = (int)($_SESSION['usuario_id'] ?? 0);

                            foreach ($afectados as $uId) {
                                if ((int)$uId !== $miUsuarioId) {
                                    $revogadas[(string)$uId] = true;
                                }
                            }
                            file_put_contents($archivoSesiones, json_encode($revogadas, JSON_PRETTY_PRINT));
                        }
                    }
                } catch (Throwable $e) {}

                AuditLogger::registrar('WARNING', 'SuperAdmin', 'Modificar Matriz RBAC', 'Se actualizaron los permisos granulares por Módulo y Nivel. Sesiones sincronizadas.');
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_exito'] = "Matriz de permisos segmentada actualizada en BD correctamente y sesiones sincronizadas.";
            } catch (Exception $e) {
                $db->rollBack();
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_error'] = "Error al guardar la matriz en la base de datos.";
            }
            header("Location: gestor-usuarios");
            exit;
        }
    }
    
    public function actualizarRol() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rolId = (int)($_POST['rol_id'] ?? 0);
            $nombreAnterior = trim($_POST['nombre_anterior'] ?? '');
            $nuevoNombre = trim($_POST['nuevo_nombre'] ?? '');
            $nuevoPrivilegioId = (int)($_POST['privilegio_id'] ?? 0);
            $miNivel = (int)($_SESSION['nivel_privilegio'] ?? 0);

            // Extraer el Nivel Real asociado a este Privilegio ID
            $privilegios = $this->adminModel->obtenerPrivilegios();
            $nivelSeleccionado = 0;
            foreach($privilegios as $p) {
                if ($p['privilegio_id'] == $nuevoPrivilegioId) { // o $privilegioId en crearRolAction
                    $nivelSeleccionado = $p['nivel_privilegio']; break;
                }
            }

            
            if ($nuevoPrivilegioId < $miNivel && $miNivel !== 0) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_error'] = "Seguridad: No puedes asignar un nivel jerárquico superior al tuyo.";
                header("Location: gestor-usuarios");
                exit;
            }

            if ($rolId > 0 && !empty($nuevoNombre) && $nuevoPrivilegioId > 0) {
                $this->adminModel->actualizarRolGeneral($rolId, $nuevoNombre, $nuevoPrivilegioId);

                if (isset($_SESSION['rol_nombre']) && $_SESSION['rol_nombre'] === $nombreAnterior) {
                    $_SESSION['rol_nombre'] = $nuevoNombre;
                }

                // Revocación masiva de sesión para todos los usuarios pertenecientes a este Rol
                try {
                    $db = Connection::getInstance();
                    $stmtUsers = $db->prepare("SELECT id FROM usuarios WHERE id_rol = ?");
                    $stmtUsers->execute([$rolId]);
                    $usuariosAfectados = $stmtUsers->fetchAll(PDO::FETCH_COLUMN);

                    if (!empty($usuariosAfectados)) {
                        $archivoSesiones = CORE_PATH . '../storage/revoked_sessions.json';
                        $revogadas = file_exists($archivoSesiones) ? (json_decode(file_get_contents($archivoSesiones), true) ?: []) : [];
                        $miUsuarioId = (int)($_SESSION['usuario_id'] ?? 0);

                        foreach ($usuariosAfectados as $uId) {
                            if ((int)$uId !== $miUsuarioId) {
                                $revogadas[(string)$uId] = true;
                            }
                        }
                        file_put_contents($archivoSesiones, json_encode($revogadas, JSON_PRETTY_PRINT));
                    }
                } catch (Throwable $e) {}

                AuditLogger::registrar('INFO', 'SuperAdmin', 'Modificar Rol', "Rol ID #{$rolId} actualizado a '{$nuevoNombre}' (Nivel: {$nuevoPrivilegioId}). Sesiones remotas de usuarios revocadas.");
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_exito'] = "Rol actualizado exitosamente en la base de datos y sesiones sincronizadas.";
            }
            header("Location: gestor-usuarios");
            exit;
        }
    }
    public function crearRolAction() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nuevo_rol_nombre'] ?? '');
            $privilegioId = (int)($_POST['nuevo_privilegio_id'] ?? 0);
            $miNivel = (int)($_SESSION['nivel_privilegio'] ?? 0);

            if (empty($nombre) || $privilegioId <= 0) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_error'] = "El nombre y el nivel de privilegio son obligatorios.";
                header("Location: gestor-usuarios");
                exit;
            }

            if ($privilegioId <= $miNivel) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_error'] = "No puedes crear un rol con un nivel jerárquico superior al tuyo.";
                header("Location: gestor-usuarios");
                exit;
            }

            try {
                $this->adminModel->crearRol($nombre, $privilegioId);
                
                // Inicializar en la matriz RBAC en BD
                $db = Connection::getInstance();
                $stmt = $db->prepare("INSERT INTO matriz_rbac (nivel_privilegio, modulo, permisos) VALUES (?, 'Sistema', ?) ON CONFLICT DO NOTHING");
                $stmt->execute([$privilegioId, json_encode(['ver' => true, 'editar' => false, 'eliminar' => false])]);

                AuditLogger::registrar('WARNING', 'SuperAdmin', 'Crear Rol', "Nuevo rol creado: {$nombre} (Privilegio ID: {$privilegioId})");
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_exito'] = "Rol '{$nombre}' creado correctamente e integrado a la matriz RBAC.";
            } catch (Throwable $e) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_error'] = "Error al crear el rol. Posible nombre duplicado.";
            }
            header("Location: gestor-usuarios");
            exit;
        }
    }

    public function revocarSesion() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioIdAExpulsar = (int)($_POST['usuario_id'] ?? 0);
            $miUsuarioId = (int)($_SESSION['usuario_id'] ?? 0);
            
            // Protección: Imposible expulsarse a uno mismo
            if ($usuarioIdAExpulsar > 0 && $usuarioIdAExpulsar !== $miUsuarioId) {
                $archivo = CORE_PATH . '../storage/revoked_sessions.json';
                $revogadas = file_exists($archivo) ? (json_decode(file_get_contents($archivo), true) ?: []) : [];
                
                $revogadas[(string)$usuarioIdAExpulsar] = true;
                file_put_contents($archivo, json_encode($revogadas, JSON_PRETTY_PRINT));

                AuditLogger::registrar('CRITICAL', 'SuperAdmin', 'Revocar Sesión Remota', "Sesión expulsada para el Usuario ID #{$usuarioIdAExpulsar}");

                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_exito'] = "Sesión revocada exitosamente para el usuario #{$usuarioIdAExpulsar}.";
            } else {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_error'] = "No puedes revocar tu propia sesión activa desde esta acción.";
            }

            header("Location: gestor-usuarios");
            exit;
        }
    }

    public function eliminarUsuarioAction() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioIdAEliminar = (int)($_POST['usuario_id'] ?? 0);
            $usuarioIdActual = (int)($_SESSION['usuario_id'] ?? 0);

            if ($usuarioIdAEliminar <= 0) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_error'] = "ID de usuario no válido para eliminación.";
                header("Location: gestor-usuarios");
                exit;
            }

            if ($usuarioIdAEliminar === $usuarioIdActual) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_error'] = "Operación denegada: No puedes eliminar tu propia cuenta en sesión activa.";
                header("Location: gestor-usuarios");
                exit;
            }

            $exito = $this->adminModel->archivarUsuario($usuarioIdAEliminar);

            if (session_status() === PHP_SESSION_NONE) session_start();

            if ($exito) {
                // Expulsar cualquier sesión activa del usuario eliminado
                $archivo = CORE_PATH . '../storage/revoked_sessions.json';
                $revogadas = file_exists($archivo) ? (json_decode(file_get_contents($archivo), true) ?: []) : [];
                $revogadas[(string)$usuarioIdAEliminar] = true;
                file_put_contents($archivo, json_encode($revogadas, JSON_PRETTY_PRINT));

                AuditLogger::registrar('WARNING', 'SuperAdmin', 'Eliminar/Archivar Usuario', "Usuario ID #{$usuarioIdAEliminar} archivado exitosamente. Credenciales liberadas.");
                $_SESSION['mensaje_gestor_exito'] = "Usuario #{$usuarioIdAEliminar} eliminado exitosamente. Sus credenciales han sido liberadas para nuevos registros.";
            } else {
                $_SESSION['mensaje_gestor_error'] = "No se pudo eliminar el usuario especificado o no existe en la base de datos.";
            }

            header("Location: gestor-usuarios");
            exit;
        }
    }

    // Carga la vista de edición con los datos del usuario
    public function mostrarEdicion() {
        Auth::requierePrivilegioMinimo(0);
        
        $cedula = $_GET['cedula'] ?? '';
        $usuario = $this->adminModel->buscarPorCedula($cedula);

        if (!$usuario) {
            header("Location: gestor-usuarios");
            exit;
        }

        // Bloqueo de seguridad: No puedes editarte a ti mismo desde aquí
        if ($usuario['id'] === $_SESSION['usuario_id']) {
            $_SESSION['error_gestor'] = "Para editar tus propios datos, utiliza la configuración de tu perfil personal.";
            header("Location: gestor-usuarios?cedula=" . urlencode($cedula));
            exit;
        }

        return [
            'usuarioEditar' => $usuario,
            'rolesDisponibles' => $this->adminModel->obtenerRoles(),
            'error' => $_SESSION['error_edicion'] ?? null
        ];
    }

    // Recibe los datos del formulario de edición y los guarda
    public function procesarEdicion() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return false;

        $id = (int) $_POST['usuario_id'];
        $cedula = trim($_POST['cedula']);
        $nombre = trim($_POST['nombre']);
        $email = trim($_POST['email']);
        $id_rol = (int) $_POST['id_rol'];
        
        // Nuevos campos de contraseña
        $password = trim($_POST['password'] ?? '');
        $password_confirm = trim($_POST['password_confirm'] ?? '');

        if (empty($cedula) || empty($nombre) || empty($email)) {
            $_SESSION['error_edicion'] = "Todos los datos básicos son obligatorios.";
            header("Location: editar-usuario?cedula=" . urlencode($_POST['cedula_original']));
            exit;
        }

        $hashSeguro = null;
        if (!empty($password)) {
            if ($password !== $password_confirm) {
                $_SESSION['error_edicion'] = "Las nuevas contraseñas no coinciden.";
                header("Location: editar-usuario?cedula=" . urlencode($_POST['cedula_original']));
                exit;
            }
            $hashSeguro = password_hash($password, PASSWORD_BCRYPT);
        }

        // Obtener datos exactos del usuario por ID antes de aplicar los cambios en la BD
        $usuarioAnterior = $this->adminModel->buscarPorId($id);

        // Pasamos el hashSeguro al modelo (será null si no se llenaron los campos de clave)
        $this->adminModel->actualizarUsuario($id, $cedula, $nombre, $email, $id_rol, $hashSeguro);
        
        // Evaluar ESTRICTAMENTE si hubo una modificación real en el id_rol
        if ($usuarioAnterior && isset($usuarioAnterior['id_rol']) && (int)$usuarioAnterior['id_rol'] !== (int)$id_rol) {
            $roles = $this->adminModel->obtenerRoles();
            $rolAnteriorNombre = $usuarioAnterior['rol_nombre'] ?? 'Desconocido';
            $nuevoRolNombre = 'Usuario';
            foreach ($roles as $r) {
                if ((int)$r['id'] === (int)$id_rol) { 
                    $nuevoRolNombre = $r['nombre']; 
                    break; 
                }
            }

            MailService::enviarEvento('superadmin.cambio_rol', $email, [
                'NOMBRE_USUARIO' => $nombre,
                'ROL_ANTERIOR'   => $rolAnteriorNombre,
                'NUEVO_ROL'      => $nuevoRolNombre,
                'FECHA_CAMBIO'   => date('d/m/Y H:i A')
            ], $nombre);
        }

        // Revocación remota de sesión automática para que los datos en caché y sesión del usuario se reseteen al instante
        $miUsuarioId = (int)($_SESSION['usuario_id'] ?? 0);
        if ($id > 0 && $id !== $miUsuarioId) {
            $archivoSesiones = CORE_PATH . '../storage/revoked_sessions.json';
            $revogadas = file_exists($archivoSesiones) ? (json_decode(file_get_contents($archivoSesiones), true) ?: []) : [];
            $revogadas[(string)$id] = true;
            file_put_contents($archivoSesiones, json_encode($revogadas, JSON_PRETTY_PRINT));
        }

        $detallesEdicion = "Datos actualizados para el Usuario C.I. {$cedula} ({$nombre})." . ($hashSeguro ? " Se forzó cambio de contraseña." : "") . " Sesión remota revocada.";
        AuditLogger::registrar('INFO', 'SuperAdmin', 'Editar Usuario', $detallesEdicion);

        header("Location: gestor-usuarios?cedula=" . urlencode($cedula));
        exit;
    }

    // Enciende o apaga la cuenta
    public function alternarEstado() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) $_POST['usuario_id'];
            $cedula = $_POST['cedula'];
            $estadoActual = $_POST['estado_actual'] === '1'; // true si estaba activo
            
            // Verificación de seguridad
            if ($id === $_SESSION['usuario_id']) {
                header("Location: gestor-usuarios?cedula=" . urlencode($cedula));
                exit;
            }

            // Invertimos el estado
            $this->adminModel->cambiarEstadoActivo($id, !$estadoActual);
            
            // Si el estado pasa de Activo a Suspendido, revocar inmediatamente su sesión
            if ($estadoActual) {
                $archivoSesiones = CORE_PATH . '../storage/revoked_sessions.json';
                $revogadas = file_exists($archivoSesiones) ? (json_decode(file_get_contents($archivoSesiones), true) ?: []) : [];
                $revogadas[(string)$id] = true;
                file_put_contents($archivoSesiones, json_encode($revogadas, JSON_PRETTY_PRINT));
            }
            
            $usuarioObj = $this->adminModel->buscarPorCedula($cedula);
            if ($usuarioObj && !empty($usuarioObj['email'])) {
                $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
                $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                $baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
                $baseUrl = "{$protocolo}://{$host}" . ($baseDir && $baseDir !== '/' ? $baseDir : '');

                if (!$estadoActual) {
                    // Se acaba de restaurar/reactivar la cuenta
                    MailService::enviarEvento('superadmin.cuenta_restaurada', $usuarioObj['email'], [
                        'NOMBRE_USUARIO' => $usuarioObj['nombre_completo'],
                        'ENLACE_ACCESO'   => "{$baseUrl}/login"
                    ], $usuarioObj['nombre_completo']);
                } else {
                    // Se acaba de suspender/bloquear la cuenta
                    MailService::enviarEvento('seguridad.cuenta_bloqueada', $usuarioObj['email'], [
                        'NOMBRE_USUARIO' => $usuarioObj['nombre_completo'],
                        'MOTIVO'         => 'Suspensión administrativa ejecutada desde el Panel de SuperAdmin',
                        'IP_ORIGEN'      => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
                        'FECHA_HORA'     => date('d/m/Y H:i A'),
                        'ENLACE_SOPORTE' => "{$baseUrl}/recuperar-cuenta"
                    ], $usuarioObj['nombre_completo']);
                }
            }

            $accionAudit = !$estadoActual ? 'Restaurar Cuenta Usuario' : 'Suspender Cuenta Usuario';
            $sevAudit = !$estadoActual ? 'INFO' : 'WARNING';
            AuditLogger::registrar($sevAudit, 'SuperAdmin', $accionAudit, "Estado de la cuenta del Usuario C.I. {$cedula} cambiado a: " . (!$estadoActual ? 'Activo' : 'Suspendido'));

            header("Location: gestor-usuarios?cedula=" . urlencode($cedula));
            exit;
        }
    }

    // Registrar nuevo usuario desde el Gestor de Usuarios
    public function crearUsuarioAction() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cedula = trim($_POST['cedula'] ?? '');
            $nombre = trim($_POST['nombre'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $id_rol = (int)($_POST['id_rol'] ?? 0);
            $clave = trim($_POST['password'] ?? '');

            if (empty($cedula) || empty($nombre) || empty($email) || empty($clave) || $id_rol <= 0) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_error'] = "Todos los campos son obligatorios para crear el usuario.";
                header("Location: gestor-usuarios");
                exit;
            }

            // Verificar duplicado de cédula
            $existe = $this->adminModel->buscarPorCedula($cedula);
            if ($existe) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_error'] = "Ya existe un usuario registrado con la cédula C.I. {$cedula}.";
                header("Location: gestor-usuarios");
                exit;
            }

            $hashClave = password_hash($clave, PASSWORD_BCRYPT);
            $exito = $this->adminModel->crearUsuario($cedula, $nombre, $email, $id_rol, $hashClave);

            if (session_status() === PHP_SESSION_NONE) session_start();
            if ($exito) {
                AuditLogger::registrar('INFO', 'SuperAdmin', 'Crear Usuario', "Nuevo usuario registrado: {$nombre} (C.I: {$cedula})");
                
                // Asegurar que si el ID fue previamente marcado como revocado, sea limpiado
                $usuarioNuevo = $this->adminModel->buscarPorCedula($cedula);
                if ($usuarioNuevo && isset($usuarioNuevo['id'])) {
                    $archivoSesiones = CORE_PATH . '../storage/revoked_sessions.json';
                    if (file_exists($archivoSesiones)) {
                        $revogadas = json_decode(file_get_contents($archivoSesiones), true) ?: [];
                        $idStr = (string)$usuarioNuevo['id'];
                        if (isset($revogadas[$idStr])) {
                            unset($revogadas[$idStr]);
                            file_put_contents($archivoSesiones, json_encode($revogadas, JSON_PRETTY_PRINT));
                        }
                    }
                }

                // Disparar correo situacional de invitación
                $roles = $this->adminModel->obtenerRoles();
                $nombreRol = 'Usuario';
                foreach ($roles as $r) {
                    if ((int)$r['id'] === $id_rol) { $nombreRol = $r['nombre']; break; }
                }

                $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
                $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                $baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
                $baseUrl = "{$protocolo}://{$host}" . ($baseDir && $baseDir !== '/' ? $baseDir : '');
                $enlaceAcceso = "{$baseUrl}/login";

                MailService::enviarEvento('superadmin.invitacion_usuario', $email, [
                    'NOMBRE_USUARIO' => $nombre,
                    'CEDULA'         => $cedula,
                    'ROL_ASIGNADO'   => $nombreRol,
                    'CLAVE_TEMPORAL' => $clave,
                    'ENLACE_ACCESO'   => $enlaceAcceso
                ], $nombre);

                $_SESSION['mensaje_gestor_exito'] = "Usuario '{$nombre}' creado exitosamente en el sistema y notificación enviada a su correo.";
            } else {
                $_SESSION['mensaje_gestor_error'] = "Error inesperado al crear el usuario.";
            }

            header("Location: gestor-usuarios");
            exit;
        }
    }

    // Restablecimiento rápido de contraseña por el Administrador
    public function resetClaveRapido() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioId = (int)($_POST['usuario_id'] ?? 0);
            $cedula = trim($_POST['cedula'] ?? '');
            $claveNueva = trim($_POST['nueva_clave'] ?? 'Temporal2026!');

            if ($usuarioId <= 0 || empty($claveNueva)) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_error'] = "Datos insuficientes para restablecer la contraseña.";
                header("Location: gestor-usuarios");
                exit;
            }

            $hashClave = password_hash($claveNueva, PASSWORD_BCRYPT);
            $exito = $this->adminModel->forzarRestablecerClave($usuarioId, $hashClave);

            if (session_status() === PHP_SESSION_NONE) session_start();
            if ($exito) {
                AuditLogger::registrar('WARNING', 'SuperAdmin', 'Restablecer Clave Rápida', "Contraseña restablecida para el Usuario C.I. {$cedula}");
                $_SESSION['mensaje_gestor_exito'] = "Contraseña de la cuenta C.I. {$cedula} restablecida a: '{$claveNueva}'.";
            } else {
                $_SESSION['mensaje_gestor_error'] = "Error al restablecer la contraseña.";
            }

            header("Location: gestor-usuarios");
            exit;
        }
    }
    public function crearNivelPrivilegioAction() {
        Auth::requierePrivilegioMinimo(0);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->adminModel->extenderNivelPrivilegio();
            AuditLogger::registrar('WARNING', 'SuperAdmin', 'Extender Privilegios', "Se ha creado un nuevo nivel jerárquico en el sistema.");
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_gestor_exito'] = "Se ha extendido la jerarquía del sistema con un nuevo nivel.";
            header("Location: gestor-usuarios");
            exit;
        }
    }

    public function eliminarRolAction() {
        Auth::requierePrivilegioMinimo(0);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rolId = (int)($_POST['rol_id'] ?? 0);
            try {
                $this->adminModel->eliminarRol($rolId);
                AuditLogger::registrar('WARNING', 'SuperAdmin', 'Eliminar Rol', "Rol ID #{$rolId} eliminado de la base de datos.");
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_exito'] = "Rol eliminado correctamente.";
            } catch (Throwable $e) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_error'] = "No se puede eliminar un rol que ya tiene usuarios asignados.";
            }
            header("Location: gestor-usuarios");
            exit;
        }
    }
    public function eliminarNivelPrivilegioAction() {
        Auth::requierePrivilegioMinimo(0);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $nivel = (int)($_POST['nivel'] ?? 0);

        if ($nivel <= 3) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_gestor_error'] = "Seguridad: No se pueden eliminar los niveles base (0 al 3).";
            header("Location: gestor-usuarios");
            exit;
        }

        // Verificación + eliminación atómica
        $resultado = $this->adminModel->eliminarPrivilegio($nivel);

        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($resultado['exito']) {
            AuditLogger::registrar('WARNING', 'SuperAdmin', 'Eliminar Nivel',
                "Nivel de privilegio {$nivel} eliminado de la BD y purgado del RBAC.");
            $_SESSION['mensaje_gestor_exito'] = $resultado['mensaje'];
        } else {
            $_SESSION['mensaje_gestor_error'] = $resultado['mensaje'];
        }

        header("Location: gestor-usuarios");
        exit;
    }

    /**
     * Emite una invitación oficial a un docente/profesor enviando un enlace firmado criptográficamente.
     */
    public function invitarProfesorAction() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cedula = trim($_POST['cedula'] ?? '');
            $nombre = trim($_POST['nombre'] ?? '');
            $email = trim($_POST['email'] ?? '');

            if (empty($cedula) || empty($nombre) || empty($email)) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_gestor_error'] = "Cédula, Nombre Completo y Correo Institucional son obligatorios para invitar a un profesor.";
                header("Location: gestor-usuarios");
                exit;
            }

            // Verificar si el usuario ya existe por cédula o correo en la base de datos
            $db = Connection::getInstance();
            $stmtCheck = $db->prepare("SELECT id, cedula, email FROM usuarios WHERE cedula = ? OR email = ?");
            $stmtCheck->execute([$cedula, $email]);
            $duplicado = $stmtCheck->fetch();

            if ($duplicado) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                if ($duplicado['cedula'] === $cedula) {
                    $_SESSION['mensaje_gestor_error'] = "Ya existe un usuario registrado con la C.I. {$cedula}.";
                } else {
                    $_SESSION['mensaje_gestor_error'] = "El correo electrónico '{$email}' ya se encuentra registrado por otro usuario.";
                }
                header("Location: gestor-usuarios");
                exit;
            }

            // Buscar el ID de Rol de Profesor (o crear si no existe)
            $roles = $this->adminModel->obtenerRoles();
            $idRolProfesor = 0;
            foreach ($roles as $r) {
                if (mb_strtolower($r['nombre']) === 'profesor' || mb_strtolower($r['nombre']) === 'docente') {
                    $idRolProfesor = (int)$r['id'];
                    break;
                }
            }
            // Si no se encuentra un rol denominado 'Profesor', usamos el rol ID 2 por defecto
            if ($idRolProfesor === 0) {
                $idRolProfesor = 2;
            }

            // Generar Token Firmado SHA-256 (validez 48 horas)
            $rawToken = bin2hex(random_bytes(32));
            $tokenHash = hash('sha256', $rawToken);

            // Crear usuario en estado inactivo (esperando que establezca clave por token)
            $hashClaveDummy = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
            $sql = "INSERT INTO usuarios (cedula, nombre_completo, email, id_rol, contrasena, activo, activation_token) VALUES (?, ?, ?, ?, ?, 'false', ?)";
            
            try {
                $stmt = $db->prepare($sql);
                $exito = $stmt->execute([$cedula, $nombre, $email, $idRolProfesor, $hashClaveDummy, $tokenHash]);
            } catch (Throwable $e) {
                $exito = false;
            }

            if (session_status() === PHP_SESSION_NONE) session_start();

            if ($exito) {
                $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
                $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
                $baseDir = rtrim(dirname($scriptName), '/\\');
                $baseUrl = "{$protocolo}://{$host}" . ($baseDir && $baseDir !== '/' ? $baseDir : '');
                
                // Si la URL amigable con .htaccess falla en el entorno local, garantizamos fallback con index.php?ruta=
                $enlaceActivacion = "{$baseUrl}/index.php?ruta=completar-registro&token={$rawToken}";

                MailService::enviarEvento('superadmin.invitacion_profesor', $email, [
                    'NOMBRE_PROFESOR'   => $nombre,
                    'CEDULA'            => $cedula,
                    'ENLACE_ACTIVACION' => $enlaceActivacion,
                    'TIEMPO_EXPIRACION' => '48 horas'
                ], $nombre);

                AuditLogger::registrar('INFO', 'SuperAdmin', 'Invitar Profesor', "Invitación emitida para el profesor {$nombre} (C.I: {$cedula}, Correo: {$email})");
                $_SESSION['mensaje_gestor_exito'] = "Invitación emitida y correo de registro enviado exitosamente al profesor '{$nombre}'.";
            } else {
                $_SESSION['mensaje_gestor_error'] = "Error al intentar guardar el registro del profesor en la base de datos.";
            }

            header("Location: gestor-usuarios");
            exit;
        }
    }
}