<?php
// modules/VinculacionEmpresarial/models/ProfesorPSTModel.php
//
// Acceso a datos de Profesores PST y de la validación docente de postulaciones estudiantiles.
// Tablas: usuarios (es_profesor_pst, estado_registro), profesores_pst y postulaciones_estudiantes
// (trayecto, trimestre, id_profesor_pst, aprobacion_profesor, motivo_profesor, fecha_revision_profesor).

require_once CORE_PATH . 'Database/Connection.php';
require_once __DIR__ . '/../../Autenticacion/services/PerfilPSTService.php';

class ProfesorPSTModel {

    private PDO $db;

    public function __construct() {
        $this->db = Connection::getInstance();
    }

    /**
     * Profesores PST activos con sus trayectos/trimestres.
     * Cada fila: id, nombre_completo, email, asignaciones (array de ['trayecto','trimestre']).
     */
    public function getProfesoresPST(bool $soloActivos = true): array {
        $sql = "SELECT u.id, u.nombre_completo, u.email,
                       COALESCE(json_agg(DISTINCT pp.trayecto) FILTER (WHERE pp.id IS NOT NULL), '[]') AS trayectos,
                       COALESCE(json_agg(json_build_object('trayecto', pp.trayecto, 'trimestre', COALESCE(pp.trimestre, 1))
                                ORDER BY pp.trayecto) FILTER (WHERE pp.id IS NOT NULL), '[]') AS asignaciones
                FROM usuarios u
                LEFT JOIN profesores_pst pp ON pp.id_profesor = u.id
                WHERE u.es_profesor_pst = true" . ($soloActivos ? " AND u.activo = true" : "") . "
                GROUP BY u.id, u.nombre_completo, u.email
                ORDER BY u.nombre_completo ASC";
        $rows = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$r) {
            $r['trayectos'] = json_decode($r['trayectos'], true) ?: [];
            $r['asignaciones'] = json_decode($r['asignaciones'], true) ?: [];
        }
        return $rows;
    }

    /** Verifica que el usuario sea un profesor PST activo. */
    public function esProfesorPSTActivo(int $idProfesor): bool {
        $stmt = $this->db->prepare("SELECT 1 FROM usuarios WHERE id = ? AND es_profesor_pst = true AND activo = true");
        $stmt->execute([$idProfesor]);
        return (bool)$stmt->fetchColumn();
    }

    /** ¿El profesor dicta PST en ese trayecto? */
    public function profesorDicta(int $idProfesor, int $trayecto, ?int $trimestre = null): bool {
        $stmt = $this->db->prepare("SELECT 1 FROM profesores_pst WHERE id_profesor = ? AND trayecto = ?");
        $stmt->execute([$idProfesor, $trayecto]);
        return (bool)$stmt->fetchColumn();
    }

    /**
     * Postulaciones con datos PST. Si $idProfesor no es null, solo las dirigidas a ese profesor.
     * Filtros opcionales: trayecto, trimestre, aprobacion_profesor, estado (Comité).
     */
    public function getPostulacionesPST(?int $idProfesor, array $filtros = []): array {
        $where = [];
        $params = [];
        if ($idProfesor !== null) {
            $where[] = 'p.id_profesor_pst = ?';
            $params[] = $idProfesor;
        } elseif (!empty($filtros['profesor'])) {
            $where[] = 'p.id_profesor_pst = ?';
            $params[] = (int)$filtros['profesor'];
        }
        if (!empty($filtros['trayecto'])) {
            $where[] = 'p.trayecto = ?';
            $params[] = (int)$filtros['trayecto'];
        }
        if (!empty($filtros['trimestre'])) {
            $where[] = 'p.trimestre = ?';
            $params[] = (int)$filtros['trimestre'];
        }
        if (!empty($filtros['aprobacion'])) {
            $where[] = 'p.aprobacion_profesor = ?';
            $params[] = $filtros['aprobacion'];
        }
        if (!empty($filtros['estado'])) {
            $where[] = 'p.estado = ?';
            $params[] = $filtros['estado'];
        }

        $sql = "SELECT p.id AS id_postulacion, p.id_investigacion, p.mensaje_motivacion, p.fecha_postulacion, p.equipo_extra,
                       p.estado, p.trayecto, p.trimestre, p.id_profesor_pst, p.aprobacion_profesor, p.motivo_profesor,
                       p.fecha_revision_profesor,
                       u.nombre_completo AS estudiante, u.email AS correo, u.cedula, u.telefono,
                       prof.nombre_completo AS profesor_nombre, prof.email AS profesor_email,
                       i.titulo, e.nombre_empresa, e.nivel_trayecto, e.area_afectada, e.codigo_seguimiento,
                       li.nombre AS linea_nombre,
                       EXISTS (SELECT 1 FROM profesores_pst pp
                               WHERE pp.id_profesor = p.id_profesor_pst AND pp.trayecto = p.trayecto) AS coincide_profesor
                FROM postulaciones_estudiantes p
                JOIN usuarios u ON p.id_estudiante = u.id
                JOIN investigaciones_ofertadas i ON p.id_investigacion = i.id
                LEFT JOIN propuestas_empresa e ON i.id_propuesta_empresa = e.id
                LEFT JOIN lineas_investigacion li ON i.id_linea = li.id
                LEFT JOIN usuarios prof ON p.id_profesor_pst = prof.id"
             . (empty($where) ? '' : ' WHERE ' . implode(' AND ', $where)) . "
                ORDER BY (p.aprobacion_profesor = 'Pendiente') DESC, p.fecha_postulacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$r) {
            $r['coincide_profesor'] = (bool)$r['coincide_profesor'];
            // ¿El trayecto declarado por el estudiante coincide con el nivel requerido por la oferta?
            $nivelOferta = PerfilPSTService::numeroDesdeTexto($r['nivel_trayecto'] ?? null);
            $r['trayecto_oferta'] = $nivelOferta;
            $r['coincide_oferta'] = ($nivelOferta === null || (int)$r['trayecto'] === $nivelOferta);
        }
        return $rows;
    }

    public function getPostulacion(int $idPostulacion): ?array {
        $stmt = $this->db->prepare("SELECT p.*, u.nombre_completo AS estudiante, u.email AS correo,
                                           e.nombre_empresa
                                    FROM postulaciones_estudiantes p
                                    JOIN usuarios u ON p.id_estudiante = u.id
                                    JOIN investigaciones_ofertadas i ON p.id_investigacion = i.id
                                    LEFT JOIN propuestas_empresa e ON i.id_propuesta_empresa = e.id
                                    WHERE p.id = ?");
        $stmt->execute([$idPostulacion]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /** Registra la decisión del profesor PST (Aprobado / Devuelto). */
    public function registrarDecisionProfesor(int $idPostulacion, string $decision, ?string $motivo): bool {
        $stmt = $this->db->prepare("UPDATE postulaciones_estudiantes
                                    SET aprobacion_profesor = ?, motivo_profesor = ?, fecha_revision_profesor = CURRENT_TIMESTAMP
                                    WHERE id = ? AND estado = 'Pendiente'");
        $stmt->execute([$decision, $motivo, $idPostulacion]);
        return $stmt->rowCount() > 0;
    }

    /** Detalle PST de las postulaciones del estudiante, indexado por id_investigacion. */
    public function getDetallesPostulacionesEstudiante(int $idEstudiante): array {
        $stmt = $this->db->prepare("SELECT p.id_investigacion, p.estado, p.trayecto, p.trimestre, p.id_profesor_pst,
                                           p.aprobacion_profesor, p.motivo_profesor, prof.nombre_completo AS profesor_nombre
                                    FROM postulaciones_estudiantes p
                                    LEFT JOIN usuarios prof ON prof.id = p.id_profesor_pst
                                    WHERE p.id_estudiante = ?");
        $stmt->execute([$idEstudiante]);
        $out = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $out[(int)$r['id_investigacion']] = $r;
        }
        return $out;
    }

    /**
     * Docentes registrados (rol docente/profesor) y/o marcados como PST, incluyendo solicitudes
     * de registro en línea pendientes. Incluye asignaciones PST.
     */
    public function getDocentes(): array {
        $sql = "SELECT u.id, u.nombre_completo, u.cedula, u.email, u.activo, u.estado_registro, u.es_profesor_pst,
                       r.nombre AS rol_nombre,
                       COALESCE(json_agg(DISTINCT pp.trayecto) FILTER (WHERE pp.id IS NOT NULL), '[]') AS trayectos,
                       COALESCE(json_agg(json_build_object('trayecto', pp.trayecto, 'trimestre', COALESCE(pp.trimestre, 1))
                                ORDER BY pp.trayecto) FILTER (WHERE pp.id IS NOT NULL), '[]') AS asignaciones
                FROM usuarios u
                LEFT JOIN roles r ON r.id = u.id_rol
                LEFT JOIN profesores_pst pp ON pp.id_profesor = u.id
                WHERE (u.es_profesor_pst = true
                       OR u.estado_registro IS NOT NULL
                       OR LOWER(r.nombre) LIKE '%docente%' OR LOWER(r.nombre) LIKE '%profesor%')
                  AND u.email NOT LIKE '%\\_deleted\\_%'
                GROUP BY u.id, r.nombre
                ORDER BY (u.estado_registro = 'pendiente') DESC NULLS LAST, u.nombre_completo ASC";
        $rows = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$r) {
            $r['trayectos'] = json_decode($r['trayectos'], true) ?: [];
            $r['asignaciones'] = json_decode($r['asignaciones'], true) ?: [];
            $r['activo'] = (bool)$r['activo'];
            $r['es_profesor_pst'] = (bool)$r['es_profesor_pst'];
        }
        return $rows;
    }

    public function getUsuarioBasico(int $id): ?array {
        $stmt = $this->db->prepare("SELECT id, nombre_completo, email, cedula, activo, estado_registro FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /** Aprueba (activa) o rechaza una solicitud de registro docente en línea pendiente. */
    public function resolverRegistroDocente(int $idUsuario, bool $aprobar): bool {
        $sql = $aprobar
            ? "UPDATE usuarios SET activo = true, estado_registro = 'aprobado' WHERE id = ? AND estado_registro = 'pendiente'"
            : "UPDATE usuarios SET activo = false, estado_registro = 'rechazado' WHERE id = ? AND estado_registro = 'pendiente'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idUsuario]);
        return $stmt->rowCount() > 0;
    }
}
