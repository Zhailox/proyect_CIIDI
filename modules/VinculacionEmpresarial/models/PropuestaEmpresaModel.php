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

    /**
     * Propuestas activas (no archivadas) con el estado de su oferta académica más reciente.
     */
    public function getTodas(): array {
        require_once CORE_PATH . 'Database/Connection.php';
        $pdo = Connection::getInstance();
        $sql = "SELECT p.*, io.id AS id_investigacion, io.estado AS estado_oferta,
                       (SELECT COUNT(*) FROM postulaciones_estudiantes pe
                         WHERE pe.id_investigacion = io.id AND pe.estado IN ('Pendiente', 'Aceptado')) AS postulaciones_activas
                FROM propuestas_empresa p
                LEFT JOIN LATERAL (
                    SELECT i.id, i.estado FROM investigaciones_ofertadas i
                    WHERE i.id_propuesta_empresa = p.id
                    ORDER BY (i.estado <> 'Cerrada') DESC, i.id DESC LIMIT 1
                ) io ON TRUE
                WHERE p.eliminado_en IS NULL
                ORDER BY p.fecha_creacion DESC";
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Propuestas archivadas (soft-delete) para la pestaña "Archivadas".
     */
    public function getArchivadas(): array {
        require_once CORE_PATH . 'Database/Connection.php';
        $pdo = Connection::getInstance();
        $sql = "SELECT p.*, u.nombre_completo AS archivado_por_nombre
                FROM propuestas_empresa p
                LEFT JOIN usuarios u ON u.id = p.archivado_por
                WHERE p.eliminado_en IS NOT NULL
                ORDER BY p.eliminado_en DESC";
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAceptadas(): array {
        require_once CORE_PATH . 'Database/Connection.php';
        $pdo = Connection::getInstance();
        // Excluye propuestas archivadas; las ofertas cerradas/canceladas (p. ej. por reversión) ya quedan fuera por i.estado = 'Abierta'
        $sql = "SELECT p.*, i.id as id_investigacion, i.cupos_disponibles, l.nombre as linea_investigacion, l.id as id_linea
                FROM propuestas_empresa p
                JOIN investigaciones_ofertadas i ON i.id_propuesta_empresa = p.id
                LEFT JOIN lineas_investigacion l ON i.id_linea = l.id
                WHERE p.estado = 'aceptada' AND i.estado = 'Abierta' AND p.eliminado_en IS NULL
                ORDER BY p.fecha_creacion DESC";
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Organizaciones para el carrusel "Organizaciones que han confiado en nuestro talento".
     * Incluye propuestas aceptadas (o con oferta abierta / en desarrollo / finalizada), excluye archivadas.
     * Cada organización aparece UNA sola vez: se deduplica por nombre normalizado y, si el RIF
     * tiene formato válido (letra + dígitos), también por RIF.
     */
    public function getOrganizacionesConfianza(): array {
        require_once CORE_PATH . 'Database/Connection.php';
        $pdo = Connection::getInstance();
        $sql = "SELECT p.id, p.nombre_empresa, p.rif_empresa, p.fecha_creacion,
                       (SELECT i.estado FROM investigaciones_ofertadas i
                         WHERE i.id_propuesta_empresa = p.id AND i.estado <> 'Cerrada'
                         ORDER BY i.id DESC LIMIT 1) AS estado_oferta
                FROM propuestas_empresa p
                WHERE p.eliminado_en IS NULL
                  AND (p.estado = 'aceptada'
                       OR EXISTS (SELECT 1 FROM investigaciones_ofertadas i2
                                   WHERE i2.id_propuesta_empresa = p.id
                                     AND i2.estado IN ('Abierta', 'En Desarrollo', 'Finalizada')))
                ORDER BY p.fecha_creacion ASC, p.id ASC";
        $filas = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $porNombre = [];
        $porRif = [];
        $organizaciones = [];
        foreach ($filas as $f) {
            $nombre = trim(preg_replace('/\s+/u', ' ', (string)$f['nombre_empresa']) ?? '');
            if ($nombre === '') continue;
            $kNombre = self::normalizarNombreOrganizacion($nombre);
            $kRif = self::normalizarRif((string)($f['rif_empresa'] ?? ''));

            $idx = $porNombre[$kNombre] ?? ($kRif !== '' ? ($porRif[$kRif] ?? null) : null);
            if ($idx === null) {
                $idx = count($organizaciones);
                $organizaciones[] = ['nombre' => $nombre, 'en_desarrollo' => false, 'proyectos' => 0];
            }
            $porNombre[$kNombre] = $idx;
            if ($kRif !== '') $porRif[$kRif] = $idx;
            $organizaciones[$idx]['proyectos']++;
            if (in_array($f['estado_oferta'], ['En Desarrollo', 'Finalizada'], true)) {
                $organizaciones[$idx]['en_desarrollo'] = true;
            }
        }
        return $organizaciones;
    }

    private static function normalizarNombreOrganizacion(string $nombre): string {
        $n = mb_strtolower($nombre, 'UTF-8');
        $n = strtr($n, ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n']);
        $n = preg_replace('/[^a-z0-9 ]+/', ' ', $n) ?? '';
        // Quitar sufijos societarios comunes (C.A., S.A., S.R.L., etc.)
        $n = preg_replace('/\b(c ?a|s ?a|s ?r ?l|s ?c|f ?p|r ?l)\s*$/', '', trim(preg_replace('/\s+/', ' ', $n) ?? '')) ?? '';
        return str_replace(' ', '', $n);
    }

    private static function normalizarRif(string $rif): string {
        $r = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $rif) ?? '');
        // Solo RIF con formato válido: letra de tipo + 8 o 9 dígitos
        return preg_match('/^[JGVEPC]\d{7,9}$/', $r) ? $r : '';
    }

    /**
     * Revierte una propuesta 'aceptada' a 'pendiente': cierra su(s) oferta(s) académica(s)
     * vinculada(s) (estado 'Cerrada') y rechaza las postulaciones activas con el motivo indicado.
     * @return array{ok: bool, error?: string, afectados?: array, ofertas?: int}
     */
    public function revertirAceptada(int $id, string $motivo): array {
        require_once CORE_PATH . 'Database/Connection.php';
        $pdo = Connection::getInstance();
        $pdo->beginTransaction();
        try {
            $st = $pdo->prepare("SELECT id, estado, eliminado_en FROM propuestas_empresa WHERE id = ? FOR UPDATE");
            $st->execute([$id]);
            $prop = $st->fetch(PDO::FETCH_ASSOC);
            if (!$prop || $prop['estado'] !== 'aceptada' || $prop['eliminado_en'] !== null) {
                $pdo->rollBack();
                return ['ok' => false, 'error' => 'Solo se pueden revertir propuestas aceptadas y no archivadas.'];
            }

            $st = $pdo->prepare("SELECT id, estado FROM investigaciones_ofertadas WHERE id_propuesta_empresa = ? AND estado <> 'Cerrada' FOR UPDATE");
            $st->execute([$id]);
            $ofertas = $st->fetchAll(PDO::FETCH_ASSOC);
            foreach ($ofertas as $o) {
                if ($o['estado'] === 'Finalizada') {
                    $pdo->rollBack();
                    return ['ok' => false, 'error' => 'El proyecto ya fue finalizado por el equipo de estudiantes; no puede revertirse.'];
                }
            }

            $afectados = [];
            $idsOfertas = array_column($ofertas, 'id');
            if ($idsOfertas) {
                $in = implode(',', array_fill(0, count($idsOfertas), '?'));
                $st = $pdo->prepare("SELECT pe.id, pe.estado, u.nombre_completo, u.email
                                     FROM postulaciones_estudiantes pe JOIN usuarios u ON u.id = pe.id_estudiante
                                     WHERE pe.id_investigacion IN ($in) AND pe.estado IN ('Pendiente', 'Aceptado')");
                $st->execute($idsOfertas);
                $afectados = $st->fetchAll(PDO::FETCH_ASSOC);

                $motivoPost = 'Postulación cancelada: la propuesta empresarial fue retirada de la cartelera por el Comité. Motivo: ' . $motivo;
                $st = $pdo->prepare("UPDATE postulaciones_estudiantes SET estado = 'Rechazado', fecha_respuesta = CURRENT_TIMESTAMP, motivo_rechazo = ?
                                     WHERE id_investigacion IN ($in) AND estado IN ('Pendiente', 'Aceptado')");
                $st->execute(array_merge([$motivoPost], $idsOfertas));

                $st = $pdo->prepare("UPDATE investigaciones_ofertadas SET estado = 'Cerrada' WHERE id IN ($in)");
                $st->execute($idsOfertas);
            }

            $st = $pdo->prepare("UPDATE propuestas_empresa SET estado = 'pendiente', revertido_en = CURRENT_TIMESTAMP, motivo_reversion = ? WHERE id = ?");
            $st->execute([$motivo, $id]);

            $pdo->commit();
            return ['ok' => true, 'afectados' => $afectados, 'ofertas' => count($idsOfertas)];
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            error_log('revertirAceptada: ' . $e->getMessage());
            return ['ok' => false, 'error' => 'Error de base de datos al revertir la propuesta.'];
        }
    }

    /** Archivo lógico (soft-delete). Las archivadas se ocultan de listados, cartelera y carrusel. */
    public function archivar(int $id, string $motivo, ?int $idUsuario): bool {
        require_once CORE_PATH . 'Database/Connection.php';
        $pdo = Connection::getInstance();
        $st = $pdo->prepare("UPDATE propuestas_empresa SET eliminado_en = CURRENT_TIMESTAMP, motivo_archivo = ?, archivado_por = ? WHERE id = ? AND eliminado_en IS NULL");
        $st->execute([$motivo, $idUsuario, $id]);
        return $st->rowCount() > 0;
    }

    public function restaurar(int $id): bool {
        require_once CORE_PATH . 'Database/Connection.php';
        $pdo = Connection::getInstance();
        $st = $pdo->prepare("UPDATE propuestas_empresa SET eliminado_en = NULL, archivado_por = NULL WHERE id = ? AND eliminado_en IS NOT NULL");
        $st->execute([$id]);
        return $st->rowCount() > 0;
    }

    public function getPorId(int $id): ?array {
        require_once CORE_PATH . 'Database/Connection.php';
        $pdo = Connection::getInstance();
        $st = $pdo->prepare("SELECT * FROM propuestas_empresa WHERE id = ?");
        $st->execute([$id]);
        return $st->fetch(PDO::FETCH_ASSOC) ?: null;
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

    public function crearPostulacion(int $id_investigacion, int $id_estudiante, string $motivacion, ?string $equipo_extra = null, ?int $trayecto = null, ?int $trimestre = null, ?int $id_profesor_pst = null): bool|int|string {
        require_once CORE_PATH . 'Database/Connection.php';
        $pdo = Connection::getInstance();
        
        $stmt = $pdo->prepare("SELECT id, estado FROM postulaciones_estudiantes WHERE id_investigacion = ? AND id_estudiante = ?");
        $stmt->execute([$id_investigacion, $id_estudiante]);
        $existe = $stmt->fetch();

        if ($existe) {
            if ($existe['estado'] === 'Rechazado') {
                $updateStmt = $pdo->prepare("UPDATE postulaciones_estudiantes SET mensaje_motivacion = ?, equipo_extra = ?, estado = 'Pendiente', fecha_postulacion = CURRENT_TIMESTAMP, trayecto = ?, trimestre = ?, id_profesor_pst = ?, aprobacion_profesor = 'Pendiente' WHERE id = ?");
                return $updateStmt->execute([$motivacion, $equipo_extra, $trayecto, $trimestre, $id_profesor_pst, $existe['id']]);
            }

            throw new \PDOException("Duplicate entry", 23505);
        }

        return $this->qb->tabla('postulaciones_estudiantes')->insert([
            'id_investigacion' => $id_investigacion,
            'id_estudiante' => $id_estudiante,
            'mensaje_motivacion' => $motivacion,
            'estado' => 'Pendiente',
            'equipo_extra' => $equipo_extra,
            'trayecto' => $trayecto,
            'trimestre' => $trimestre,
            'id_profesor_pst' => $id_profesor_pst,
            'aprobacion_profesor' => 'Pendiente'
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
                       p.trayecto as pst_trayecto, p.trimestre as pst_trimestre, p.aprobacion_profesor,
                       u.nombre_completo as estudiante, u.email as correo, u.cedula,
                       i.titulo, i.id as id_investigacion, i.id_propuesta_empresa, i.cupos_disponibles,
                       e.nombre_empresa, e.codigo_seguimiento, e.nivel_trayecto,
                       e.area_afectada, e.descripcion_problema, e.persona_contacto, e.telefono_contacto, e.correo_contacto,
                       li.nombre as linea_nombre, dim.nombre as dimension_nombre,
                       prof.nombre_completo as profesor_nombre
                FROM postulaciones_estudiantes p
                JOIN usuarios u ON p.id_estudiante = u.id
                JOIN investigaciones_ofertadas i ON p.id_investigacion = i.id
                JOIN propuestas_empresa e ON i.id_propuesta_empresa = e.id
                LEFT JOIN lineas_investigacion li ON i.id_linea = li.id
                LEFT JOIN dimensiones_operativas dim ON i.id_dimension = dim.id
                LEFT JOIN usuarios prof ON p.id_profesor_pst = prof.id
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
