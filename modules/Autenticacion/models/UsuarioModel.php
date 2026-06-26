<?php
// modules/Autenticacion/models/UsuarioModel.php
require_once CORE_PATH . 'Database/QueryBuilder.php';

class UsuarioModel {
    
    private $qb;

    public function __construct() {
        // Instanciamos nuestro motor genérico
        $this->qb = new QueryBuilder();
    }

    /**
     * Busca un usuario activo por su correo y extrae su nivel de privilegio exacto.
     */
    public function intentarAutenticacion(string $cedula) {
        return $this->qb->tabla('usuarios u')
            ->select('u.id, u.nombre_completo, u.email, u.contrasena, r.nombre AS nombre_rol, p.nivel_privilegio')
            ->join('roles r', 'u.id_rol = r.id')
            ->join('privilegios p', 'r.privilegio_id = p.privilegio_id')
            ->where('u.cedula', '=', $cedula) // CORRECCIÓN: Búsqueda por cédula
            ->where('u.activo', '=', 'true')
            ->first();
    }
    /**
     * Actualiza el registro de última actividad del usuario.
     */
    public function registrarAcceso(int $id_usuario) {
        // Pedimos la conexión directa a PostgreSQL usando nuestra clase del Core
        $db = Connection::getInstance();
        
        $sql = "
            UPDATE registro_actividad 
            SET ultima_actividad = CURRENT_TIMESTAMP, 
                conteo_accesos = conteo_accesos + 1 
            WHERE id_usuario = :id_usuario
        ";
        
        // Ahora usamos $db local en lugar de $this->db
        $stmt = $db->prepare($sql);
        $stmt->execute(['id_usuario' => $id_usuario]);
    }
    /**
     * Verifica si un usuario ya existe por cédula o correo
     */
    public function existeUsuario(string $cedula, string $email) {
        $db = Connection::getInstance();
        $sql = "SELECT id FROM usuarios WHERE cedula = ? OR email = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$cedula, $email]);
        return $stmt->fetch() !== false; // Retorna true si ya existe
    }

    /**
     * Inserta un nuevo usuario en la base de datos (Rol 3 = Estudiante por defecto)
     */
    public function registrarUsuario(string $cedula, string $nombre, string $email, string $hash) {
        $db = Connection::getInstance();
        $sql = "
            INSERT INTO usuarios (cedula, nombre_completo, email, contrasena, id_rol, activo) 
            VALUES (?, ?, ?, ?, 3, true) 
            RETURNING id
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$cedula, $nombre, $email, $hash]);
        return $stmt->fetch() !== false;
    }
}