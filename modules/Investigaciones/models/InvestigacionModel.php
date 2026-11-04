<?php
// modules/Investigaciones/models/InvestigacionModel.php
require_once CORE_PATH . 'Database/QueryBuilder.php';

class InvestigacionModel {

    private $db;
    private string $metaFile;

    public function __construct() {
        $this->db = Connection::getInstance();
        $this->metaFile = dirname(__DIR__, 3) . '/storage/investigaciones_meta.json';
    }

    private function cargarMeta(): array {
        if (!file_exists($this->metaFile)) return [];
        $raw = file_get_contents($this->metaFile);
        return json_decode($raw, true) ?? [];
    }

    private function guardarMeta(array $meta): void {
        file_put_contents(
            $this->metaFile,
            json_encode($meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            LOCK_EX
        );
    }

    public function obtenerMetaInvestigacion(int $id): array {
        $meta = $this->cargarMeta();
        return $meta[(string)$id] ?? [
            'imagen' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=600&q=80',
            'tag_quien' => 'Equipo CIIDI',
            'tag_que' => 'Proyecto',
            'tag_sobre' => 'Innovacion',
            'trayecto' => 't4',
        ];
    }
    
    public function actualizarMetaInvestigacion(int $id, array $nuevosDatos): void {
        $meta = $this->cargarMeta();
        $actual = $meta[(string)$id] ?? [
            'imagen' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=600&q=80',
            'tag_quien' => 'Equipo CIIDI',
            'tag_que' => 'Proyecto',
            'tag_sobre' => 'Innovacion',
            'trayecto' => 't4',
        ];
        
        $meta[(string)$id] = array_merge($actual, $nuevosDatos);
        $this->guardarMeta($meta);
    }

    private function mergeMetadata(array $investigaciones): array {
        if (empty($investigaciones)) return [];
        $meta = $this->cargarMeta();
        $defaults = [
            'imagen' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=600&q=80',
            'tag_quien' => 'Equipo CIIDI',
            'tag_que' => 'Proyecto',
            'tag_sobre' => 'Innovacion',
            'trayecto' => 't4'
        ];
        foreach ($investigaciones as &$inv) {
            $m = $meta[(string)$inv['id']] ?? [];
            $inv = array_merge($defaults, $m, $inv);
        }
        return $investigaciones;
    }

    public function obtenerLineas(): array {
        $sql = "SELECT id, nombre, descripcion FROM lineas_investigacion ORDER BY nombre ASC";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function obtenerDimensiones(int $id_linea): array {
        $sql = "SELECT id, nombre FROM dimensiones_operativas WHERE id_linea = ? ORDER BY nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_linea]);
        return $stmt->fetchAll();
    }

    public function obtenerTodasDimensiones(): array {
        $sql = "SELECT id, id_linea, nombre FROM dimensiones_operativas ORDER BY nombre ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function listarInvestigaciones(array $filtros = []): array {
        $whereBase   = " WHERE 1=1";
        $params      = [];
        $selectScore = "0 AS score";
        $paramsScore = [];
        $orderBy     = "i.fecha_creacion DESC";

        if (!empty($filtros['estado'])) {
            $whereBase .= " AND i.estado = ?";
            $params[] = $filtros['estado'];
        }
        if (!empty($filtros['busqueda'])) {
            $qRaw       = trim((string)$filtros['busqueda']);
            $whereBase .= " AND (MATCH(i.titulo, i.planteamiento_problema, i.objetivo_general) AGAINST(? IN NATURAL LANGUAGE MODE) OR i.titulo LIKE ? OR i.planteamiento_problema LIKE ? OR COALESCE(u.nombre_completo, '') LIKE ?)";
            $term       = '%' . $qRaw . '%';
            $params[]   = $qRaw;
            $params[]   = $term;
            $params[]   = $term;
            $params[]   = $term;

            $selectScore   = "MATCH(i.titulo, i.planteamiento_problema, i.objetivo_general) AGAINST(? IN NATURAL LANGUAGE MODE) AS score";
            $paramsScore[] = $qRaw;
            $orderBy       = "score DESC, i.fecha_creacion DESC";
        }
        if (!empty($filtros['id_linea'])) {
            $whereBase .= " AND (i.id_linea = ? OR EXISTS (SELECT 1 FROM investigaciones_lineas il WHERE il.id_investigacion = i.id AND il.id_linea = ?))";
            $params[] = (int)$filtros['id_linea'];
            $params[] = (int)$filtros['id_linea'];
        }
        if (!empty($filtros['id_profesor'])) {
            $whereBase .= " AND i.id_profesor = ?";
            $params[] = (int)$filtros['id_profesor'];
        }

        $fromJoin = "
            FROM investigaciones_ofertadas i
            LEFT JOIN usuarios u ON i.id_profesor = u.id
        ";

        // Total de registros para paginación
        $sqlCount = "SELECT COUNT(*) " . $fromJoin . $whereBase;
        $stmtCount = $this->db->prepare($sqlCount);
        $stmtCount->execute($params);
        $total = (int)$stmtCount->fetchColumn();

        // Paginación
        $perPage  = max(1, (int)($filtros['por_pagina'] ?? 9));
        $paginas  = max(1, (int)ceil($total / $perPage));
        $pagina   = max(1, min((int)($filtros['pagina'] ?? 1), $paginas));
        $offset   = ($pagina - 1) * $perPage;

        $sql = "
            SELECT
                i.id, i.id_profesor, i.id_linea, i.id_dimension,
                i.titulo, i.planteamiento_problema, i.objetivo_general,
                i.cupos_disponibles, i.estado, i.fecha_creacion,
                {$selectScore},
                COALESCE(u.nombre_completo, 'Sin responsable asignado') AS profesor,
                u.foto_perfil AS profesor_foto,
                COALESCE(
                    NULLIF((SELECT GROUP_CONCAT(l.nombre SEPARATOR ', ') FROM investigaciones_lineas il JOIN lineas_investigacion l ON il.id_linea = l.id WHERE il.id_investigacion = i.id), ''),
                    (SELECT l2.nombre FROM lineas_investigacion l2 WHERE l2.id = i.id_linea),
                    'Sin línea asignada'
                ) AS linea_nombre,
                (SELECT COUNT(*) FROM postulaciones_estudiantes p WHERE p.id_investigacion = i.id AND p.estado = 'Pendiente') AS postulantes_pendientes,
                (SELECT COUNT(*) FROM postulaciones_estudiantes p WHERE p.id_investigacion = i.id) AS total_postulantes
            " . $fromJoin . $whereBase . "
            ORDER BY {$orderBy}
            LIMIT ? OFFSET ?
        ";

        $paramsPage = array_merge($paramsScore, $params, [$perPage, $offset]);
        $stmt = $this->db->prepare($sql);
        $stmt->execute($paramsPage);
        $resultados = $stmt->fetchAll();

        return [
            'items'     => $this->mergeMetadata($resultados),
            'total'     => $total,
            'pagina'    => $pagina,
            'paginas'   => $paginas,
            'por_pagina' => $perPage,
        ];
    }
    
    public function obtenerTodasAdmin(array $filtros = []): array {
        // Reutiliza listarInvestigaciones (paginación integrada)
        return $this->listarInvestigaciones($filtros);
    }
    
    public function obtenerMisInvestigaciones(int $id_profesor, array $filtros = []): array {
        $fromJoin = "
            FROM investigaciones_ofertadas i
            LEFT JOIN usuarios u ON i.id_profesor = u.id
        ";
        $where  = " WHERE i.id_profesor = ?";
        $params = [$id_profesor];

        // Total
        $stmtCount = $this->db->prepare("SELECT COUNT(*) " . $fromJoin . $where);
        $stmtCount->execute($params);
        $total = (int)$stmtCount->fetchColumn();

        $perPage = max(1, (int)($filtros['por_pagina'] ?? 10));
        $paginas = max(1, (int)ceil($total / $perPage));
        $pagina  = max(1, min((int)($filtros['pagina'] ?? 1), $paginas));
        $offset  = ($pagina - 1) * $perPage;

        $sql = "
            SELECT
                i.*,
                COALESCE(u.nombre_completo, 'Sin responsable asignado') AS profesor,
                u.foto_perfil AS profesor_foto,
                COALESCE(
                    NULLIF((SELECT GROUP_CONCAT(l.nombre SEPARATOR ', ') FROM investigaciones_lineas il JOIN lineas_investigacion l ON il.id_linea = l.id WHERE il.id_investigacion = i.id), ''),
                    (SELECT l2.nombre FROM lineas_investigacion l2 WHERE l2.id = i.id_linea),
                    'Sin línea asignada'
                ) AS linea_nombre,
                (SELECT COUNT(*) FROM postulaciones_estudiantes p
                 WHERE p.id_investigacion = i.id AND p.estado = 'Pendiente') AS postulantes_pendientes
            " . $fromJoin . $where . "
            ORDER BY i.fecha_creacion DESC
            LIMIT ? OFFSET ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_merge($params, [$perPage, $offset]));
        $resultados = $stmt->fetchAll();

        return [
            'items'      => $this->mergeMetadata($resultados),
            'total'      => $total,
            'pagina'     => $pagina,
            'paginas'    => $paginas,
            'por_pagina' => $perPage,
        ];
    }

    public function obtenerPorId(int $id): ?array {
        $sql = "
            SELECT 
                i.*, 
                u.nombre_completo AS profesor,
                u.foto_perfil AS profesor_foto,
                (SELECT GROUP_CONCAT(l.nombre SEPARATOR ', ') FROM investigaciones_lineas il JOIN lineas_investigacion l ON il.id_linea = l.id WHERE il.id_investigacion = i.id) AS linea_nombre,
                (SELECT JSON_ARRAYAGG(il.id_linea) FROM investigaciones_lineas il WHERE il.id_investigacion = i.id) AS lineas_ids,
                (SELECT JSON_ARRAYAGG(idd.id_dimension) FROM investigaciones_dimensiones idd WHERE idd.id_investigacion = i.id) AS dimensiones_ids
            FROM investigaciones_ofertadas i
            LEFT JOIN usuarios u ON i.id_profesor = u.id
            WHERE i.id = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $inv = $stmt->fetch();
        if (!$inv) return null;

        $inv['lineas_array'] = $inv['lineas_ids'] ? json_decode($inv['lineas_ids'], true) : (!empty($inv['id_linea']) ? [$inv['id_linea']] : []);
        $inv['dimensiones_array'] = $inv['dimensiones_ids'] ? json_decode($inv['dimensiones_ids'], true) : (!empty($inv['id_dimension']) ? [$inv['id_dimension']] : []);
        
        $meta = $this->obtenerMetaInvestigacion($id);
        $defaults = [
            'imagen' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=600&q=80',
            'tag_quien' => 'Equipo CIIDI',
            'tag_que' => 'Proyecto',
            'tag_sobre' => 'Innovacion',
            'trayecto' => 't4'
        ];
        return array_merge($defaults, $meta, $inv);
    }
    
    public function crearInvestigacion(array $datos, int $id_profesor): int {
        $id_profesor_asignado = (!empty($datos['id_profesor']) && (int)$datos['id_profesor'] > 0) 
            ? (int)$datos['id_profesor'] 
            : $id_profesor;

        $id_linea_principal = !empty($datos['id_linea']) && is_array($datos['id_linea']) ? $datos['id_linea'][0] : ($datos['id_linea'] ?? null);
        $id_dimension_principal = !empty($datos['id_dimension']) && is_array($datos['id_dimension']) ? $datos['id_dimension'][0] : ($datos['id_dimension'] ?? null);

        $sql = "
            INSERT INTO investigaciones_ofertadas 
            (id_profesor, titulo, planteamiento_problema, objetivo_general, id_linea, id_dimension, cupos_disponibles, estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $id_profesor_asignado,
            $datos['titulo'],
            $datos['planteamiento_problema'],
            $datos['objetivo_general'],
            $id_linea_principal,
            $id_dimension_principal,
            $datos['cupos_disponibles'],
            $datos['estado'] ?? 'Abierta'
        ]);
        $nuevo_id = (int)$this->db->lastInsertId();

        // Guardar lineas y sincronizar afiliación del profesor en profesor_linea
        $stmtProfLin = $this->db->prepare("INSERT IGNORE INTO profesor_linea (id_usuario, id_linea) VALUES (?, ?)");
        if (!empty($datos['id_linea']) && is_array($datos['id_linea'])) {
            $stmtL = $this->db->prepare("INSERT IGNORE INTO investigaciones_lineas (id_investigacion, id_linea) VALUES (?, ?)");
            foreach ($datos['id_linea'] as $lId) {
                if ($lId) {
                    $stmtL->execute([$nuevo_id, $lId]);
                    if ($id_profesor_asignado > 0) {
                        $stmtProfLin->execute([$id_profesor_asignado, (int)$lId]);
                    }
                }
            }
        } elseif (!empty($id_linea_principal)) {
            $stmtL = $this->db->prepare("INSERT IGNORE INTO investigaciones_lineas (id_investigacion, id_linea) VALUES (?, ?)");
            $stmtL->execute([$nuevo_id, $id_linea_principal]);
            if ($id_profesor_asignado > 0) {
                $stmtProfLin->execute([$id_profesor_asignado, (int)$id_linea_principal]);
            }
        }

        // Guardar dimensiones
        if (!empty($datos['id_dimension']) && is_array($datos['id_dimension'])) {
            $stmtD = $this->db->prepare("INSERT IGNORE INTO investigaciones_dimensiones (id_investigacion, id_dimension) VALUES (?, ?)");
            foreach ($datos['id_dimension'] as $dId) {
                if($dId) $stmtD->execute([$nuevo_id, $dId]);
            }
        } elseif (!empty($id_dimension_principal)) {
            $stmtD = $this->db->prepare("INSERT IGNORE INTO investigaciones_dimensiones (id_investigacion, id_dimension) VALUES (?, ?)");
            $stmtD->execute([$nuevo_id, $id_dimension_principal]);
        }
        
        // Guardar metadatos
        $this->actualizarMetaInvestigacion($nuevo_id, [
            'trayecto' => $datos['trayecto'] ?? 't4',
            'tag_que'  => $datos['tag_que'] ?? 'Proyecto',
            'imagen'   => $datos['imagen'] ?? 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=600&q=80'
        ]);
        
        return $nuevo_id;
    }
    
    public function actualizarInvestigacion(int $id, array $datos, int $id_usuario, int $nivel): bool {
        // Verificar propiedad o privilegios admin/superadmin (nivel 0 = SuperAdmin, 1 = Comité)
        $inv = $this->obtenerPorId($id);
        if (!$inv || ($inv['id_profesor'] != $id_usuario && $nivel > 1)) {
            return false;
        }

        $id_profesor_asignado = (!empty($datos['id_profesor']) && (int)$datos['id_profesor'] > 0)
            ? (int)$datos['id_profesor']
            : (int)$inv['id_profesor'];

        $id_linea_principal = !empty($datos['id_linea']) && is_array($datos['id_linea']) ? $datos['id_linea'][0] : ($datos['id_linea'] ?? null);
        $id_dimension_principal = !empty($datos['id_dimension']) && is_array($datos['id_dimension']) ? $datos['id_dimension'][0] : ($datos['id_dimension'] ?? null);

        $sql = "
            UPDATE investigaciones_ofertadas 
            SET titulo = ?, planteamiento_problema = ?, objetivo_general = ?, 
                id_linea = ?, id_dimension = ?, cupos_disponibles = ?, estado = ?,
                id_profesor = ?
            WHERE id = ?
        ";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $datos['titulo'],
            $datos['planteamiento_problema'],
            $datos['objetivo_general'],
            $id_linea_principal,
            $id_dimension_principal,
            $datos['cupos_disponibles'],
            $datos['estado'],
            $id_profesor_asignado,
            $id
        ]);
        
        if ($result) {
            // Eliminar lineas y dimensiones anteriores
            $this->db->prepare("DELETE FROM investigaciones_lineas WHERE id_investigacion = ?")->execute([$id]);
            $this->db->prepare("DELETE FROM investigaciones_dimensiones WHERE id_investigacion = ?")->execute([$id]);

            // Guardar lineas y sincronizar afiliación del profesor en profesor_linea
            $stmtProfLin = $this->db->prepare("INSERT IGNORE INTO profesor_linea (id_usuario, id_linea) VALUES (?, ?)");
            if (!empty($datos['id_linea']) && is_array($datos['id_linea'])) {
                $stmtL = $this->db->prepare("INSERT IGNORE INTO investigaciones_lineas (id_investigacion, id_linea) VALUES (?, ?)");
                foreach ($datos['id_linea'] as $lId) {
                    if ($lId) {
                        $stmtL->execute([$id, $lId]);
                        if ($id_profesor_asignado > 0) {
                            $stmtProfLin->execute([$id_profesor_asignado, (int)$lId]);
                        }
                    }
                }
            } elseif (!empty($id_linea_principal)) {
                $stmtL = $this->db->prepare("INSERT IGNORE INTO investigaciones_lineas (id_investigacion, id_linea) VALUES (?, ?)");
                $stmtL->execute([$id, $id_linea_principal]);
                if ($id_profesor_asignado > 0) {
                    $stmtProfLin->execute([$id_profesor_asignado, (int)$id_linea_principal]);
                }
            }

            // Guardar dimensiones
            if (!empty($datos['id_dimension']) && is_array($datos['id_dimension'])) {
                $stmtD = $this->db->prepare("INSERT IGNORE INTO investigaciones_dimensiones (id_investigacion, id_dimension) VALUES (?, ?)");
                foreach ($datos['id_dimension'] as $dId) {
                    if($dId) $stmtD->execute([$id, $dId]);
                }
            } elseif (!empty($id_dimension_principal)) {
                $stmtD = $this->db->prepare("INSERT IGNORE INTO investigaciones_dimensiones (id_investigacion, id_dimension) VALUES (?, ?)");
                $stmtD->execute([$id, $id_dimension_principal]);
            }

            $metaActualizacion = [
                'trayecto' => $datos['trayecto'] ?? 't4',
                'tag_que'  => $datos['tag_que'] ?? 'Proyecto'
            ];
            if (!empty($datos['imagen'])) {
                $metaActualizacion['imagen'] = $datos['imagen'];
            }
            $this->actualizarMetaInvestigacion($id, $metaActualizacion);
        }
        
        return $result;
    }
    
    public function eliminarInvestigacion(int $id, int $id_usuario, int $nivel): bool {
        $inv = $this->obtenerPorId($id);
        if (!$inv || ($inv['id_profesor'] != $id_usuario && $nivel > 1)) {
            return false;
        }

        try {
            $this->db->prepare("DELETE FROM postulaciones_estudiantes WHERE id_investigacion = ?")->execute([$id]);
            $this->db->prepare("DELETE FROM investigaciones_lineas WHERE id_investigacion = ?")->execute([$id]);
            $this->db->prepare("DELETE FROM investigaciones_dimensiones WHERE id_investigacion = ?")->execute([$id]);
        } catch (\Throwable $e) {}

        $sql = "DELETE FROM investigaciones_ofertadas WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $ok = $stmt->execute([$id]);
        if ($ok) {
            $meta = $this->cargarMeta();
            if (isset($meta[(string)$id])) {
                unset($meta[(string)$id]);
                $this->guardarMeta($meta);
            }
        }
        return $ok;
    }
    
    public function cambiarEstadoInvestigacion(int $id, string $estado): bool {
        $sql = "UPDATE investigaciones_ofertadas SET estado = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$estado, $id]);
    }

    public function reasignarResponsableInvestigacion(int $id, int $id_profesor): bool {
        $sql = "UPDATE investigaciones_ofertadas SET id_profesor = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $ok = $stmt->execute([$id_profesor, $id]);
        if ($ok && $id_profesor > 0) {
            try {
                $this->db->prepare("
                    INSERT IGNORE INTO profesor_linea (id_usuario, id_linea)
                    SELECT ?, il.id_linea FROM investigaciones_lineas il WHERE il.id_investigacion = ?
                    UNION
                    SELECT ?, io.id_linea FROM investigaciones_ofertadas io WHERE io.id = ? AND io.id_linea IS NOT NULL
                ")->execute([$id_profesor, $id, $id_profesor, $id]);
            } catch (\Throwable $e) {}
        }
        return $ok;
    }

    public function obtenerMisPostulaciones(int $id_estudiante): array {
        $sql = "
            SELECT p.*, i.titulo AS investigacion_titulo
            FROM postulaciones_estudiantes p
            INNER JOIN investigaciones_ofertadas i ON p.id_investigacion = i.id
            WHERE p.id_estudiante = ?
            ORDER BY p.fecha_postulacion DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_estudiante]);
        return $stmt->fetchAll();
    }
    
    public function obtenerPostulantesDeMiProyecto(int $id_profesor, array $filtros = []): array {
        $fromJoin = "
            FROM postulaciones_estudiantes p
            INNER JOIN investigaciones_ofertadas i ON p.id_investigacion = i.id
            INNER JOIN usuarios u ON p.id_estudiante = u.id
        ";
        $where  = " WHERE i.id_profesor = ?";
        $params = [$id_profesor];

        // Total
        $stmtCount = $this->db->prepare("SELECT COUNT(*) " . $fromJoin . $where);
        $stmtCount->execute($params);
        $total = (int)$stmtCount->fetchColumn();

        $perPage = max(1, (int)($filtros['por_pagina'] ?? 10));
        $paginas = max(1, (int)ceil($total / $perPage));
        $pagina  = max(1, min((int)($filtros['pagina'] ?? 1), $paginas));
        $offset  = ($pagina - 1) * $perPage;

        $sql = "
            SELECT
                p.*,
                u.nombre_completo AS estudiante,
                u.email,
                i.titulo AS investigacion_titulo
            " . $fromJoin . $where . "
            ORDER BY p.fecha_postulacion DESC
            LIMIT ? OFFSET ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_merge($params, [$perPage, $offset]));

        return [
            'items'      => $stmt->fetchAll(),
            'total'      => $total,
            'pagina'     => $pagina,
            'paginas'    => $paginas,
            'por_pagina' => $perPage,
        ];
    }
    
    public function obtenerPostulacionesAdmin(): array {
        $sql = "
            SELECT 
                p.*, 
                u.nombre_completo AS estudiante, 
                u.email,
                i.titulo AS investigacion_titulo,
                prof.nombre_completo AS profesor
            FROM postulaciones_estudiantes p
            INNER JOIN investigaciones_ofertadas i ON p.id_investigacion = i.id
            INNER JOIN usuarios u ON p.id_estudiante = u.id
            INNER JOIN usuarios prof ON i.id_profesor = prof.id
            ORDER BY p.fecha_postulacion DESC
        ";
        return $this->db->query($sql)->fetchAll();
    }

    public function postularEstudiante(int $id_investigacion, int $id_estudiante, string $motivacion): bool {
        $sqlCheck = "SELECT id FROM postulaciones_estudiantes WHERE id_investigacion = ? AND id_estudiante = ?";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->execute([$id_investigacion, $id_estudiante]);
        if ($stmtCheck->fetch()) {
            return false;
        }
        
        $sql = "INSERT INTO postulaciones_estudiantes (id_investigacion, id_estudiante, mensaje_motivacion, estado) VALUES (?, ?, ?, 'Pendiente')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_investigacion, $id_estudiante, $motivacion]);
    }
    
    public function responderPostulacion(int $id_postulacion, string $estado, int $id_usuario, int $nivel): bool {
        // Verificar que la postulación pertenece a un proyecto del profesor, o es admin
        $sqlCheck = "
            SELECT p.id, i.id_profesor 
            FROM postulaciones_estudiantes p
            INNER JOIN investigaciones_ofertadas i ON p.id_investigacion = i.id
            WHERE p.id = ?
        ";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->execute([$id_postulacion]);
        $postulacion = $stmtCheck->fetch();
        
        if (!$postulacion || ($postulacion['id_profesor'] != $id_usuario && $nivel > 1)) {
            return false;
        }
        
        $sql = "UPDATE postulaciones_estudiantes SET estado = ?, fecha_respuesta = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$estado, $id_postulacion]);
    }

    public function obtenerInvestigadores(array $filtros = []): array {
        $where = "
            WHERE u.activo = 1 
              AND (
                  u.es_investigador = 1 
                  OR u.id_rol = 4 
                  OR u.es_profesor_pst = 1 
                  OR EXISTS (SELECT 1 FROM profesor_linea pl WHERE pl.id_usuario = u.id)
                  OR EXISTS (SELECT 1 FROM investigaciones_ofertadas io WHERE io.id_profesor = u.id)
              )
        ";
        $params = [];

        if (!empty($filtros['busqueda'])) {
            $where .= " AND (u.nombre_completo LIKE ? OR u.email LIKE ? OR COALESCE(u.grado_academico, '') LIKE ?)";
            $term = '%' . trim($filtros['busqueda']) . '%';
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }

        if (!empty($filtros['id_linea'])) {
            $where .= " AND (
                EXISTS (SELECT 1 FROM profesor_linea pl WHERE pl.id_usuario = u.id AND pl.id_linea = ?)
                OR EXISTS (SELECT 1 FROM profesor_dimension pd WHERE pd.id_usuario = u.id AND pd.id_linea = ?)
                OR EXISTS (
                    SELECT 1 FROM investigaciones_ofertadas io 
                    LEFT JOIN investigaciones_lineas il ON io.id = il.id_investigacion 
                    WHERE io.id_profesor = u.id AND (io.id_linea = ? OR il.id_linea = ?)
                )
            )";
            $idLinFiltro = (int)$filtros['id_linea'];
            $params[] = $idLinFiltro;
            $params[] = $idLinFiltro;
            $params[] = $idLinFiltro;
            $params[] = $idLinFiltro;
        }

        $sql = "
            SELECT 
                u.id, 
                u.nombre_completo, 
                u.email,
                u.id_rol,
                u.es_investigador,
                u.es_profesor_pst,
                u.foto_perfil,
                u.orcid,
                u.sitio_web,
                u.grado_academico,
                u.biografia,
                r.nombre as rol_nombre
            FROM usuarios u
            INNER JOIN roles r ON u.id_rol = r.id
            {$where}
            ORDER BY u.nombre_completo ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $investigadores = $stmt->fetchAll();

        if (empty($investigadores)) {
            return [];
        }

        // Hidratación optimizada para evitar consultas repetidas y lecturas de disco redundantes
        $stmtLineas = $this->db->prepare("
            SELECT DISTINCT l.id, l.nombre, l.id_carrera, COALESCE(c.nombre, 'General / Institucional') AS carrera_nombre
            FROM lineas_investigacion l
            LEFT JOIN carreras c ON c.id = l.id_carrera
            WHERE l.id IN (
                SELECT pl.id_linea FROM profesor_linea pl WHERE pl.id_usuario = ?
                UNION
                SELECT pd.id_linea FROM profesor_dimension pd WHERE pd.id_usuario = ?
                UNION
                SELECT io.id_linea FROM investigaciones_ofertadas io WHERE io.id_profesor = ? AND io.id_linea IS NOT NULL
                UNION
                SELECT il.id_linea FROM investigaciones_ofertadas io 
                JOIN investigaciones_lineas il ON io.id = il.id_investigacion 
                WHERE io.id_profesor = ?
            )
            ORDER BY l.nombre ASC
        ");

        $stmtDims = $this->db->prepare("
            SELECT DISTINCT d.id, d.id_linea, d.nombre
            FROM dimensiones_operativas d
            WHERE d.activo = 1
              AND (
                  EXISTS (
                      SELECT 1 FROM profesor_dimension pd
                      WHERE pd.id_dimension = d.id AND pd.id_usuario = ?
                  )
                  OR EXISTS (
                      SELECT 1 FROM investigaciones_ofertadas io
                      LEFT JOIN investigaciones_dimensiones idim ON idim.id_investigacion = io.id
                      WHERE io.id_profesor = ? AND (io.id_dimension = d.id OR idim.id_dimension = d.id)
                  )
              )
            ORDER BY d.id_linea ASC, d.nombre ASC
        ");

        $stmtProyectos = $this->db->prepare("
            SELECT io.id, io.titulo, io.estado, io.cupos_disponibles, io.fecha_creacion,
                   io.planteamiento_problema, io.objetivo_general
            FROM investigaciones_ofertadas io
            WHERE io.id_profesor = ?
            ORDER BY io.fecha_creacion DESC
        ");

        $stmtPst = $this->db->prepare("
            SELECT pp.trayecto, pp.trimestre
            FROM profesores_pst pp
            WHERE pp.id_profesor = ?
            ORDER BY pp.trayecto ASC, pp.trimestre ASC
        ");

        foreach ($investigadores as &$inv) {
            $uid = (int)$inv['id'];
            $stmtLineas->execute([$uid, $uid, $uid, $uid]);
            $inv['lineas'] = $stmtLineas->fetchAll();

            $inv['dimensiones'] = [];
            try {
                $stmtDims->execute([$uid, $uid]);
                $inv['dimensiones'] = $stmtDims->fetchAll();
            } catch (\Throwable $e) {
                $inv['dimensiones'] = [];
            }

            $stmtProyectos->execute([$uid]);
            $inv['investigaciones'] = $this->mergeMetadata($stmtProyectos->fetchAll());
            $inv['total_investigaciones'] = count($inv['investigaciones']);

            try {
                $stmtPst->execute([$uid]);
                $inv['trayectos_pst'] = $stmtPst->fetchAll();
            } catch (\Throwable $e) {
                $inv['trayectos_pst'] = [];
            }
        }

        // Ordenar con prioridad a quienes tengan más investigaciones o líneas asociadas
        usort($investigadores, function($a, $b) {
            if ($a['total_investigaciones'] !== $b['total_investigaciones']) {
                return $b['total_investigaciones'] <=> $a['total_investigaciones'];
            }
            return count($b['lineas']) <=> count($a['lineas']);
        });

        return $investigadores;
    }

    public function obtenerListaResponsablesDisponibles(): array {
        $sql = "
            SELECT u.id, u.nombre_completo, u.email, r.nombre AS rol_nombre, u.es_investigador
            FROM usuarios u
            INNER JOIN roles r ON u.id_rol = r.id
            WHERE u.activo = 1 
              AND (u.id_rol IN (1, 2, 4) OR u.es_investigador = 1 OR u.es_profesor_pst = 1)
            ORDER BY u.nombre_completo ASC
        ";
        return $this->db->query($sql)->fetchAll();
    }

    public function afiliarInvestigadorALinea(int $id_usuario, int $id_linea): bool {
        $stmt = $this->db->prepare("
            INSERT IGNORE INTO profesor_linea (id_usuario, id_linea) 
            VALUES (?, ?)
        ");
        return $stmt->execute([$id_usuario, $id_linea]);
    }

    public function desafiliarInvestigadorDeLinea(int $id_usuario, int $id_linea): bool {
        $stmt = $this->db->prepare("
            DELETE FROM profesor_linea 
            WHERE id_usuario = ? AND id_linea = ?
        ");
        return $stmt->execute([$id_usuario, $id_linea]);
    }

    /**
     * Estadísticas rápidas de postulantes de un profesor
     * (Pendientes / Aceptados / Rechazados / Total) sin cargar todos los registros.
     */
    public function obtenerEstadisticasPostulantes(int $id_profesor): array {
        $sql = "
            SELECT
                COALESCE(SUM(CASE WHEN p.estado = 'Pendiente' THEN 1 ELSE 0 END), 0) AS pendientes,
                COALESCE(SUM(CASE WHEN p.estado = 'Aceptado'  THEN 1 ELSE 0 END), 0) AS aceptados,
                COALESCE(SUM(CASE WHEN p.estado = 'Rechazado' THEN 1 ELSE 0 END), 0) AS rechazados,
                COUNT(*)                                                             AS total
            FROM postulaciones_estudiantes p
            INNER JOIN investigaciones_ofertadas i ON p.id_investigacion = i.id
            WHERE i.id_profesor = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_profesor]);
        return $stmt->fetch() ?: ['pendientes' => 0, 'aceptados' => 0, 'rechazados' => 0, 'total' => 0];
    }
}
