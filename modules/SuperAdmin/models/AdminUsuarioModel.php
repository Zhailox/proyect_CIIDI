<?php
// modules/SuperAdmin/models/AdminUsuarioModel.php
require_once CORE_PATH . 'Database/QueryBuilder.php';

class AdminUsuarioModel {
    
    // Ya no usamos $this->qb global para evitar que las consultas se mezclen

    public function __construct() {
        // Constructor limpio
    }

    // Busca un usuario individual sin importar su rol por Cédula
    public function buscarPorCedula(string $cedula) {
        $qb = new QueryBuilder();
        
        return $qb->tabla('usuarios u')
            ->select('u.id, u.cedula, u.nombre_completo, u.email, u.activo, u.id_rol, r.nombre AS rol_nombre, p.nivel_privilegio')
            ->join('roles r', 'u.id_rol = r.id')
            ->join('privilegios p', 'r.privilegio_id = p.privilegio_id')
            ->where('u.cedula', '=', $cedula)
            ->first();
    }

    // Busca un usuario individual por su ID primario
    public function buscarPorId(int $id) {
        $qb = new QueryBuilder();
        return $qb->tabla('usuarios u')
            ->select('u.id, u.cedula, u.nombre_completo, u.email, u.activo, u.id_rol, r.nombre AS rol_nombre, p.nivel_privilegio')
            ->join('roles r', 'u.id_rol = r.id')
            ->join('privilegios p', 'r.privilegio_id = p.privilegio_id')
            ->where('u.id', '=', $id)
            ->first();
    }

    // Obtiene a todo el personal docente (por nivel de privilegio docente/profesor o coincidencia de rol)
    public function obtenerProfesores() {
        $db = Connection::getInstance();
        $sql = "
            SELECT u.id, u.cedula, u.nombre_completo, u.email, u.activo, r.nombre AS rol_nombre
            FROM usuarios u
            INNER JOIN roles r ON u.id_rol = r.id
            INNER JOIN privilegios p ON r.privilegio_id = p.privilegio_id
            WHERE p.nivel_privilegio > 0 
              AND (
                  p.nivel_privilegio = 2 
                  OR LOWER(r.nombre) LIKE '%profe%' 
                  OR LOWER(r.nombre) LIKE '%docent%'
                  OR LOWER(r.nombre) LIKE '%tutor%'
              )
              AND u.cedula NOT LIKE '%_x%'
            ORDER BY u.nombre_completo ASC
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    // Obtiene todos los roles disponibles para el select de edición
    // Obtiene todos los roles disponibles para el select de edición
    public function obtenerRoles() {
        $qb = new QueryBuilder();
        return $qb->tabla('roles r')
            ->select('r.id, r.nombre, r.privilegio_id, p.nivel_privilegio') // <-- Agregados los campos
            ->join('privilegios p', 'r.privilegio_id = p.privilegio_id')
            ->orderBy('p.nivel_privilegio', 'ASC')
            ->get();
    }

    // Actualiza los datos básicos y el rol de un usuario
    // Actualiza los datos básicos, rol y opcionalmente la contraseña
    public function actualizarUsuario(int $id, string $cedula, string $nombre, string $email, int $id_rol, ?string $hashClave = null) {
        $db = Connection::getInstance();
        
        if ($hashClave) {
            $sql = "UPDATE usuarios SET cedula = ?, nombre_completo = ?, email = ?, id_rol = ?, contrasena = ? WHERE id = ?";
            $stmt = $db->prepare($sql);
            return $stmt->execute([$cedula, $nombre, $email, $id_rol, $hashClave, $id]);
        } else {
            $sql = "UPDATE usuarios SET cedula = ?, nombre_completo = ?, email = ?, id_rol = ? WHERE id = ?";
            $stmt = $db->prepare($sql);
            return $stmt->execute([$cedula, $nombre, $email, $id_rol, $id]);
        }
    }

    // Obtiene todos los usuarios con su registro de actividad reciente
    public function obtenerTodosLosUsuarios() {
        $db = Connection::getInstance();
        $sql = "
            SELECT 
                u.id, 
                u.cedula, 
                u.nombre_completo, 
                u.email, 
                u.activo, 
                r.nombre AS rol_nombre, 
                p.nivel_privilegio,
                ra.ultima_actividad,
                ra.conteo_accesos
            FROM usuarios u
            INNER JOIN roles r ON u.id_rol = r.id
            INNER JOIN privilegios p ON r.privilegio_id = p.privilegio_id
            LEFT JOIN registro_actividad ra ON u.id = ra.id_usuario
            WHERE u.cedula NOT LIKE '%_x%' AND u.email NOT LIKE '%_deleted_%'
            ORDER BY u.id DESC
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Suspende o restaura el acceso de un usuario
    public function cambiarEstadoActivo(int $id, bool $nuevoEstado) {
        $db = Connection::getInstance();
        $sql = "UPDATE usuarios SET activo = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$nuevoEstado ? 'true' : 'false', $id]);
    }

    // Archiva a un usuario liberando sus credenciales (Cédula y Email) manteniendo la integridad histórica
    public function archivarUsuario(int $id): bool {
        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT cedula, email, nombre_completo FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            return false;
        }

        $ts = time();
        $cedulaLimpia = preg_replace('/[^0-9A-Za-z]/', '', $usuario['cedula'] ?? '');
        $cedulaCorta = (strlen($cedulaLimpia) > 8) ? substr($cedulaLimpia, 0, 8) : $cedulaLimpia;
        $suffixTs    = substr((string)$ts, -7);
        $cedulaArchivada = $cedulaCorta . '_x' . $suffixTs; // Ejemplo: 30469331_x8984383 (Longitud max: 17 chars, dentro del límite VARCHAR(20))

        $emailArchivado  = $usuario['email'] . '_deleted_' . $ts;
        $nombreArchivado = '[Archivado] ' . $usuario['nombre_completo'];
        $hashInvalido    = password_hash('DISABLED_ACCOUNT_' . bin2hex(random_bytes(16)), PASSWORD_BCRYPT);

        $sql = "UPDATE usuarios SET cedula = ?, email = ?, nombre_completo = ?, contrasena = ?, activo = false WHERE id = ?";
        $stmtUpdate = $db->prepare($sql);
        return $stmtUpdate->execute([$cedulaArchivada, $emailArchivado, $nombreArchivado, $hashInvalido, $id]);
    }

    // Actualiza el nombre de un rol en la base de datos
    public function actualizarNombreRol(int $rolId, string $nuevoNombre) {
        $db = Connection::getInstance();
        $sql = "UPDATE roles SET nombre = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$nuevoNombre, $rolId]);
    }

    // Registra un nuevo usuario en la base de datos
    public function crearUsuario(string $cedula, string $nombre, string $email, int $id_rol, string $hashClave): bool {
        $db = Connection::getInstance();
        $sql = "INSERT INTO usuarios (cedula, nombre_completo, email, id_rol, contrasena, activo) VALUES (?, ?, ?, ?, ?, 'true')";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$cedula, $nombre, $email, $id_rol, $hashClave]);
    }

    // Forzar reseteo de clave por el administrador
    public function forzarRestablecerClave(int $usuarioId, string $hashClave): bool {
        $db = Connection::getInstance();
        $sql = "UPDATE usuarios SET contrasena = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$hashClave, $usuarioId]);
    }
    // Obtiene el catálogo de niveles de privilegio (Niveles únicos ordenados)
    public function obtenerPrivilegios() {
        $db = Connection::getInstance();
        $sql = "
            SELECT DISTINCT ON (nivel_privilegio) privilegio_id, nivel_privilegio
            FROM privilegios
            ORDER BY nivel_privilegio ASC, privilegio_id ASC
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Purga de forma limpia las filas con nivel_privilegio repetidos que no estén vinculadas a un rol activo
    public function purgarDuplicadosPrivilegios(): int {
        $db = Connection::getInstance();
        $sql = "
            DELETE FROM privilegios p1
            USING privilegios p2
            WHERE p1.nivel_privilegio = p2.nivel_privilegio
              AND p1.privilegio_id > p2.privilegio_id
              AND p1.privilegio_id NOT IN (SELECT DISTINCT privilegio_id FROM roles WHERE privilegio_id IS NOT NULL)
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
    }

    // Crea un nuevo rol vinculado a un nivel de privilegio
    public function crearRol(string $nombre, int $privilegio_id): bool {
        $db = Connection::getInstance();
        $sql = "INSERT INTO roles (nombre, privilegio_id) VALUES (?, ?)";
        $stmt = $db->prepare($sql);
        return $stmt->execute([trim($nombre), $privilegio_id]);
    }
    

    // Actualiza el nombre y el nivel jerárquico de un rol existente
    public function actualizarRolGeneral(int $rolId, string $nuevoNombre, int $privilegioId): bool {
        $db = Connection::getInstance();
        $sql = "UPDATE roles SET nombre = ?, privilegio_id = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([trim($nuevoNombre), $privilegioId, $rolId]);
    }
    // Extiende la jerarquía añadiendo el siguiente número disponible
    public function extenderNivelPrivilegio(): bool {
    $db = Connection::getInstance();

    // Buscar el primer número faltante entre 1 y el MAX actual
    $sql = "
        SELECT n
        FROM generate_series(
            1,
            GREATEST((SELECT COALESCE(MAX(nivel_privilegio), 0) FROM privilegios), 1)
        ) AS n
        WHERE n NOT IN (SELECT nivel_privilegio FROM privilegios)
        ORDER BY n ASC
        LIMIT 1
    ";
    $stmt = $db->query($sql);
    $nuevoNivel = $stmt->fetchColumn();

    // Si no hay huecos (todo contiguo), continuamos después del máximo
    if ($nuevoNivel === false) {
        $stmtMax = $db->query("SELECT COALESCE(MAX(nivel_privilegio), 0) FROM privilegios");
        $nuevoNivel = (int) $stmtMax->fetchColumn() + 1;
    }

    $stmtInsert = $db->prepare("INSERT INTO privilegios (nivel_privilegio) VALUES (?)");
    return $stmtInsert->execute([(int) $nuevoNivel]);
}

    // Elimina un rol (fallará intencionalmente por protección de BD si tiene usuarios asignados)
    public function eliminarRol(int $id): bool {
        $db = Connection::getInstance();
        $stmt = $db->prepare("DELETE FROM roles WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // Elimina un nivel de privilegio específico
    public function eliminarPrivilegio(int $nivel): array {
    $db = Connection::getInstance();

    // 1. Buscar roles que cuelgan de este nivel
    $stmtRoles = $db->prepare("
        SELECT r.id, r.nombre, COUNT(u.id) AS total_usuarios
        FROM roles r
        INNER JOIN privilegios p ON r.privilegio_id = p.privilegio_id
        LEFT JOIN usuarios u ON u.id_rol = r.id
        WHERE p.nivel_privilegio = ?
        GROUP BY r.id, r.nombre
    ");
    $stmtRoles->execute([$nivel]);
    $rolesAfectados = $stmtRoles->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($rolesAfectados)) {
        $totalUsuarios = array_sum(array_column($rolesAfectados, 'total_usuarios'));
        $detalleRoles = [];
        foreach ($rolesAfectados as $r) {
            $detalleRoles[] = "«{$r['nombre']}» ({$r['total_usuarios']} usuario(s))";
        }
        return [
            'exito' => false,
            'mensaje' => "Bloqueado por seguridad: el Nivel {$nivel} tiene roles asignados → " 
                       . implode(', ', $detalleRoles) 
                       . ". Reasigna o elimina estos roles antes de borrar el nivel."
        ];
    }

    // 2. Sin dependencias → eliminar todo en una sola transacción
    try {
        $db->beginTransaction();

        $stmtDel = $db->prepare("DELETE FROM privilegios WHERE nivel_privilegio = ?");
        $stmtDel->execute([$nivel]);

        $stmtRbac = $db->prepare("DELETE FROM matriz_rbac WHERE nivel_privilegio = ?");
        $stmtRbac->execute([$nivel]);

        $db->commit();
        return ['exito' => true, 'mensaje' => "Nivel {$nivel} eliminado y purgado del RBAC correctamente."];
    } catch (Throwable $e) {
        if ($db->inTransaction()) $db->rollBack();
        return ['exito' => false, 'mensaje' => "Error de base de datos al eliminar el nivel: " . $e->getMessage()];
    }
}
}
