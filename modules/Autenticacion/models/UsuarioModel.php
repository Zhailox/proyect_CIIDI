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
        // Quitamos el filtro de 'activo' para poder saber el estado real de la cuenta
        return $this->qb->tabla('usuarios u')
            ->select('u.id, u.nombre_completo, u.email, u.contrasena, u.activo, r.nombre AS nombre_rol, p.nivel_privilegio')
            ->join('roles r', 'u.id_rol = r.id')
            ->join('privilegios p', 'r.privilegio_id = p.privilegio_id')
            ->where('u.cedula', '=', $cedula) 
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

    public function findByCedula(string $cedula) {
        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT id, nombre_completo, email FROM usuarios WHERE cedula = ? AND activo = true");
        $stmt->execute([$cedula]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function findByEmail(string $email) {
        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT id, nombre_completo, email FROM usuarios WHERE email = ? AND activo = true");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function guardarTokenRecuperacion(int $id, string $token) {
        $db = Connection::getInstance();
        // Expira en 15 minutos
        $stmt = $db->prepare("UPDATE usuarios SET reset_token = ?, reset_expires = CURRENT_TIMESTAMP + INTERVAL '15 minutes' WHERE id = ?");
        $stmt->execute([$token, $id]);
    }
    
    public function verificarToken(int $id, string $token) {
        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT id FROM usuarios WHERE id = ? AND reset_token = ? AND reset_expires >= CURRENT_TIMESTAMP AND activo = true");
        $stmt->execute([$id, $token]);
        return $stmt->fetch() !== false;
    }
    
    public function actualizarPassword(int $id, string $hash) {
        $db = Connection::getInstance();
        $stmt = $db->prepare("UPDATE usuarios SET contrasena = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
        $stmt->execute([$hash, $id]);
    }
}