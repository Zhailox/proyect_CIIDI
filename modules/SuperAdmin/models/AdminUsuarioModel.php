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

    // Obtiene a todo el personal docente
    public function obtenerProfesores() {
        $qb = new QueryBuilder(); // <-- 2. Instancia nueva y limpia
        
        return $qb->tabla('usuarios u')
            ->select('u.id, u.cedula, u.nombre_completo, u.email, u.activo')
            ->join('roles r', 'u.id_rol = r.id')
            ->where('r.nombre', '=', 'Profesor')
            ->orderBy('u.nombre_completo', 'ASC')
            ->get();
    }
    // Obtiene todos los roles disponibles para el select de edición
    // Obtiene todos los roles disponibles para el select de edición
    public function obtenerRoles() {
        $qb = new QueryBuilder();
        return $qb->tabla('roles r')
            ->select('r.id, r.nombre')
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

    // Suspende o restaura el acceso de un usuario
    public function cambiarEstadoActivo(int $id, bool $nuevoEstado) {
        $db = Connection::getInstance();
        $sql = "UPDATE usuarios SET activo = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        // En PostgreSQL los booleanos se pueden pasar como 'true' o 'false' en string
        return $stmt->execute([$nuevoEstado ? 'true' : 'false', $id]);
    }
}