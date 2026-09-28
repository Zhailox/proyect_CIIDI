<?php
// modules/VinculacionEmpresarial/models/PropuestaEmpresaModel.php

require_once CORE_PATH . 'Database/QueryBuilder.php';

class PropuestaEmpresaModel {
    private QueryBuilder $qb;

    public function __construct() {
        $this->qb = new QueryBuilder();
    }

    public function guardar(array $datos): int|string|bool {
        return $this->qb->tabla('propuestas_empresa')->insert($datos);
    }

    public function getTodas(): array {
        return $this->qb->tabla('propuestas_empresa')
            ->orderBy('fecha_creacion', 'DESC')
            ->get();
    }
    
    public function getAceptadas(): array {
        require_once CORE_PATH . 'Database/Connection.php';
        $pdo = Connection::getInstance();
        $sql = "SELECT p.*, i.id as id_investigacion, i.cupos_disponibles, l.nombre as linea_investigacion, l.id as id_linea
                FROM propuestas_empresa p
                JOIN investigaciones_ofertadas i ON i.id_propuesta_empresa = p.id
                LEFT JOIN lineas_investigacion l ON i.id_linea = l.id
                WHERE p.estado = 'aceptada' AND i.estado = 'Abierta'
                ORDER BY p.fecha_creacion DESC";
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendientes(): array {
        return $this->qb->tabla('propuestas_empresa')
            ->where('estado', '=', 'pendiente')
            ->orderBy('fecha_creacion', 'DESC')
            ->get();
    }

    public function actualizarEstado(int $id, string $estado, ?string $nivel_trayecto = null, ?string $motivo_rechazo = null): bool {
        $datos = ['estado' => $estado];
        if ($nivel_trayecto !== null) {
            $datos['nivel_trayecto'] = $nivel_trayecto;
        }
        if ($motivo_rechazo !== null) {
            $datos['motivo_rechazo'] = $motivo_rechazo;
        }
        return $this->qb->tabla('propuestas_empresa')
            ->where('id', '=', $id)
            ->update($datos);
    }

    public function crearPostulacion(int $id_investigacion, int $id_estudiante, string $motivacion, ?string $equipo_extra = null): bool|int|string {
        require_once CORE_PATH . 'Database/Connection.php';
        $pdo = Connection::getInstance();
        
        $stmt = $pdo->prepare("SELECT id, estado FROM postulaciones_estudiantes WHERE id_investigacion = ? AND id_estudiante = ?");
        $stmt->execute([$id_investigacion, $id_estudiante]);
        $existe = $stmt->fetch();

        if ($existe) {
            if ($existe['estado'] === 'Rechazado') {
                $updateStmt = $pdo->prepare("UPDATE postulaciones_estudiantes SET mensaje_motivacion = ?, equipo_extra = ?, estado = 'Pendiente', fecha_postulacion = CURRENT_TIMESTAMP WHERE id = ?");
                return $updateStmt->execute([$motivacion, $equipo_extra, $existe['id']]);
            }

            throw new \PDOException("Duplicate entry", 23505);
        }

        return $this->qb->tabla('postulaciones_estudiantes')->insert([
            'id_investigacion' => $id_investigacion,
            'id_estudiante' => $id_estudiante,
            'mensaje_motivacion' => $motivacion,
            'estado' => 'Pendiente',
            'equipo_extra' => $equipo_extra
        ]);
    }

    public function getEstadosPostulacionesEstudiante(int $id_estudiante): array {
        require_once CORE_PATH . 'Database/Connection.php';
        $pdo = Connection::getInstance();
        $stmt = $pdo->prepare("SELECT id_investigacion, estado FROM postulaciones_estudiantes WHERE id_estudiante = ?");
        $stmt->execute([$id_estudiante]);
        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function getPostulacionesEmpresariales(): array {
        require_once CORE_PATH . 'Database/Connection.php';
        $pdo = Connection::getInstance();
        $sql = "SELECT p.id as id_postulacion, p.mensaje_motivacion, p.fecha_postulacion, p.equipo_extra, p.estado,
                       u.nombre_completo as estudiante, u.email as correo, u.cedula,
                       i.titulo, i.id as id_investigacion, i.id_propuesta_empresa, i.cupos_disponibles,
                       e.nombre_empresa, e.codigo_seguimiento, e.nivel_trayecto,
                       e.area_afectada, e.descripcion_problema, e.persona_contacto, e.telefono_contacto, e.correo_contacto,
                       li.nombre as linea_nombre, dim.nombre as dimension_nombre
                FROM postulaciones_estudiantes p
                JOIN usuarios u ON p.id_estudiante = u.id
                JOIN investigaciones_ofertadas i ON p.id_investigacion = i.id
                JOIN propuestas_empresa e ON i.id_propuesta_empresa = e.id
                LEFT JOIN lineas_investigacion li ON i.id_linea = li.id
                LEFT JOIN dimensiones_operativas dim ON i.id_dimension = dim.id
                ORDER BY p.fecha_postulacion DESC";
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function procesarAsignacion(int $id_postulacion, string $estado, int $id_investigacion): bool {
        require_once CORE_PATH . 'Database/Connection.php';
        $pdo = Connection::getInstance();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("UPDATE postulaciones_estudiantes SET estado = ?, fecha_respuesta = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$estado, $id_postulacion]);

            if ($estado === 'Aceptado') {
                $stmt2 = $pdo->prepare("UPDATE investigaciones_ofertadas SET estado = 'En Desarrollo' WHERE id = ?");
                $stmt2->execute([$id_investigacion]);
                
                // Rechazar a los demás que aplicaron al mismo proyecto
                $stmt3 = $pdo->prepare("UPDATE postulaciones_estudiantes SET estado = 'Rechazado', fecha_respuesta = CURRENT_TIMESTAMP WHERE id_investigacion = ? AND id != ?");
                $stmt3->execute([$id_investigacion, $id_postulacion]);
            }
            $pdo->commit();
            return true;
        } catch (\Throwable $e) {
            $pdo->rollBack();
            return false;
        }
    }
}
