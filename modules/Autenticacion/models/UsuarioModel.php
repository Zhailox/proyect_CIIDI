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
        $cedulaTrim = trim($cedula);
        $soloDigitos = preg_replace('/[^0-9]/', '', $cedulaTrim);
        $conPrefijo = 'V-' . $soloDigitos;

        // Quitamos el filtro de 'activo' para poder saber el estado real de la cuenta
        return $this->qb->tabla('usuarios u')
            ->select('u.id, u.cedula, u.nombre_completo, u.email, u.contrasena, u.activo, r.nombre AS nombre_rol, p.nivel_privilegio')
            ->join('roles r', 'u.id_rol = r.id')
            ->join('privilegios p', 'r.privilegio_id = p.privilegio_id')
            ->whereRaw('(u.cedula = ? OR u.cedula = ? OR u.cedula = ?)', [$cedulaTrim, $soloDigitos, $conPrefijo]) 
            ->first();
    }
    /**
     * Registra o actualiza el contador de accesos y la última actividad del usuario.
     */
    public function registrarAcceso(int $id_usuario) {
        $db = Connection::getInstance();
        
        try {
            // 1. Verificar si el usuario ya posee un registro de actividad
            $stmtCheck = $db->prepare("SELECT id FROM registro_actividad WHERE id_usuario = ? LIMIT 1");
            $stmtCheck->execute([$id_usuario]);
            $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                // 2. Si existe, actualizamos la fecha y sumamos 1 al contador
                $stmtUpdate = $db->prepare("
                    UPDATE registro_actividad 
                    SET ultima_actividad = CURRENT_TIMESTAMP, 
                        conteo_accesos = conteo_accesos + 1 
                    WHERE id = ?
                ");
                $stmtUpdate->execute([$row['id']]);
            } else {
                // 3. Si no existe, creamos el primer registro de acceso para este usuario
                $stmtInsert = $db->prepare("
                    INSERT INTO registro_actividad (id_usuario, fecha_inicial, ultima_actividad, conteo_accesos) 
                    VALUES (?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1)
                ");
                $stmtInsert->execute([$id_usuario]);
            }
        } catch (Throwable $e) {
            // Manejar excepcion en silencio para evitar bloquear la experiencia del usuario
        }
    }
    /**
     * Verifica si un usuario ya existe por cédula o correo
     */
    public function existeUsuario(string $cedula, string $email) {
        $cedulaTrim = trim($cedula);
        $soloDigitos = preg_replace('/[^0-9]/', '', $cedulaTrim);
        $conPrefijo = 'V-' . $soloDigitos;

        $db = Connection::getInstance();
        $sql = "SELECT id FROM usuarios WHERE (cedula = ? OR cedula = ? OR cedula = ?) OR email = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$cedulaTrim, $soloDigitos, $conPrefijo, $email]);
        return $stmt->fetch() !== false; // Retorna true si ya existe
    }

    /**
     * Inserta un nuevo usuario en la base de datos con token de activación (Double Opt-in)
     */
    public function registrarUsuario(string $cedula, string $nombre, string $email, string $hash, ?string $tokenActivacion = null) {
        $db = Connection::getInstance();
        $emailVerificado = ($tokenActivacion === null);
        $sql = "
            INSERT INTO usuarios (cedula, nombre_completo, email, contrasena, id_rol, activo, email_verified, activation_token) 
            VALUES (?, ?, ?, ?, 3, true, ?, ?) 
            RETURNING id
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$cedula, $nombre, $email, $hash, $emailVerificado ? 'true' : 'false', $tokenActivacion]);
        return $stmt->fetch() !== false;
    }

    public function activarCuentaPorToken(string $tokenActivacion): bool {
        $db = Connection::getInstance();
        $stmt = $db->prepare("UPDATE usuarios SET email_verified = true, activation_token = NULL WHERE activation_token = ?");
        $stmt->execute([$tokenActivacion]);
        return $stmt->rowCount() > 0;
    }

    public function findByCedula(string $cedula) {
        $cedulaTrim = trim($cedula);
        $soloDigitos = preg_replace('/[^0-9]/', '', $cedulaTrim);
        $conPrefijo = 'V-' . $soloDigitos;

        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT id, nombre_completo, email, cedula FROM usuarios WHERE (cedula = ? OR cedula = ? OR cedula = ?) AND activo = true");
        $stmt->execute([$cedulaTrim, $soloDigitos, $conPrefijo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function findByEmail(string $email) {
        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT id, nombre_completo, email FROM usuarios WHERE email = ? AND activo = true");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Registra un token de recuperación firmado SHA-256 con expiración de 15 minutos en password_resets
     */
    public function guardarTokenRecuperacionSHA256(string $email, string $tokenHash) {
        $db = Connection::getInstance();
        // Invalida tokens anteriores no utilizados para este email
        $stmtCancel = $db->prepare("UPDATE password_resets SET utilizado = true WHERE email = ? AND utilizado = false");
        $stmtCancel->execute([$email]);

        $sql = "INSERT INTO password_resets (email, token_hash, expiracion, utilizado) VALUES (?, ?, CURRENT_TIMESTAMP + INTERVAL '15 minutes', false)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$email, $tokenHash]);
    }
    
    /**
     * Verifica la validez de un token SHA-256
     */
    public function obtenerTokenRecuperacionValido(string $tokenHash) {
        $db = Connection::getInstance();
        $stmt = $db->prepare("
            SELECT pr.id, pr.email, u.id AS usuario_id, u.nombre_completo 
            FROM password_resets pr
            JOIN usuarios u ON u.email = pr.email
            WHERE pr.token_hash = ? AND pr.utilizado = false AND pr.expiracion >= CURRENT_TIMESTAMP AND u.activo = true
        ");
        $stmt->execute([$tokenHash]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Actualiza la contraseña mediante token seguro y marca el token como utilizado
     */
    public function restablecerPasswordConToken(string $tokenHash, string $nuevaPasswordHash): bool {
        $tokenData = $this->obtenerTokenRecuperacionValido($tokenHash);
        if (!$tokenData) {
            return false;
        }

        $db = Connection::getInstance();
        $db->beginTransaction();

        try {
            // 1. Actualizar clave del usuario
            $stmtUser = $db->prepare("UPDATE usuarios SET contrasena = ? WHERE id = ?");
            $stmtUser->execute([$nuevaPasswordHash, $tokenData['usuario_id']]);

            // 2. Marcar token como utilizado
            $stmtToken = $db->prepare("UPDATE password_resets SET utilizado = true WHERE id = ?");
            $stmtToken->execute([$tokenData['id']]);

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }

    public function guardarTokenRecuperacion(int $id, string $token) {
        $db = Connection::getInstance();
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