<?php
// modules/SuperAdmin/models/AdminUsuarioModel.php
require_once CORE_PATH . 'Database/QueryBuilder.php';

class AdminUsuarioModel {
    
    // Ya no usamos $this->qb global para evitar que las consultas se mezclen

    public function __construct() {
        // Constructor limpio
    }

    // Busca un usuario individual sin importar su rol
    public function buscarPorCedula(string $cedula) {
        $qb = new QueryBuilder(); // <-- 1. Instancia nueva y limpia
        
        return $qb->tabla('usuarios u')
            ->select('u.id, u.cedula, u.nombre_completo, u.email, u.activo, r.nombre AS rol_nombre, p.nivel_privilegio')
            ->join('roles r', 'u.id_rol = r.id')
            ->join('privilegios p', 'r.privilegio_id = p.privilegio_id')
            ->where('u.cedula', '=', $cedula)
            ->first();
    }

    // Obtiene a todo el personal docente (por nivel de privilegio docente/profesor)
    public function obtenerProfesores() {
        $qb = new QueryBuilder(); // <-- 2. Instancia nueva y limpia
        
        return $qb->tabla('usuarios u')
            ->select('u.id, u.cedula, u.nombre_completo, u.email, u.activo')
            ->join('roles r', 'u.id_rol = r.id')
            ->join('privilegios p', 'r.privilegio_id = p.privilegio_id')
            ->where('p.nivel_privilegio', '=', 2)
            ->orderBy('u.nombre_completo', 'ASC')
            ->get();
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
    // Obtiene el catálogo de niveles de privilegio (0 al 5)
    public function obtenerPrivilegios() {
        $qb = new QueryBuilder();
        return $qb->tabla('privilegios')->orderBy('nivel_privilegio', 'ASC')->get();
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
    
}
