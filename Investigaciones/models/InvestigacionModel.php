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
        $sql = "SELECT id, nombre, descripcion FROM public.lineas_investigacion ORDER BY nombre ASC";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function obtenerDimensiones(int $id_linea): array {
        $sql = "SELECT id, nombre FROM public.dimensiones_operativas WHERE id_linea = ? ORDER BY nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_linea]);
        return $stmt->fetchAll();
    }

    public function listarInvestigaciones(array $filtros = []): array {
        $sql = "
            SELECT 
                i.id, i.titulo, i.planteamiento_problema, i.objetivo_general, 
                i.cupos_disponibles, i.estado, i.fecha_creacion,
                u.nombre_completo AS profesor,
                l.nombre AS linea_nombre
            FROM public.investigaciones_ofertadas i
            LEFT JOIN public.usuarios u ON i.id_profesor = u.id
            LEFT JOIN public.lineas_investigacion l ON i.id_linea = l.id
            WHERE 1=1
        ";
        
        $params = [];
        
        if (!empty($filtros['estado'])) {
            $sql .= " AND i.estado = ?";
            $params[] = $filtros['estado'];
        }
        
        if (!empty($filtros['busqueda'])) {
            $sql .= " AND (i.titulo ILIKE ? OR i.planteamiento_problema ILIKE ? OR u.nombre_completo ILIKE ?)";
            $term = '%' . $filtros['busqueda'] . '%';
            $params[] = $term; $params[] = $term; $params[] = $term;
        }

        if (!empty($filtros['id_linea'])) {
            $sql .= " AND i.id_linea = ?";
            $params[] = (int)$filtros['id_linea'];
        }

        $sql .= " ORDER BY i.fecha_creacion DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $resultados = $stmt->fetchAll();

        return $this->mergeMetadata($resultados);
    }
    
    public function obtenerTodasAdmin(array $filtros = []): array {
        // Reutilizamos listarInvestigaciones que ya hace los JOINs necesarios
        return $this->listarInvestigaciones($filtros);
    }
    
    public function obtenerMisInvestigaciones(int $id_profesor): array {
        $sql = "
            SELECT 
                i.*, 
                l.nombre AS linea_nombre,
                (SELECT COUNT(*) FROM public.postulaciones_estudiantes p WHERE p.id_investigacion = i.id AND p.estado = 'Pendiente') as postulantes_pendientes
            FROM public.investigaciones_ofertadas i
            LEFT JOIN public.lineas_investigacion l ON i.id_linea = l.id
            WHERE i.id_profesor = ?
            ORDER BY i.fecha_creacion DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_profesor]);
        $resultados = $stmt->fetchAll();
        return $this->mergeMetadata($resultados);
    }

    public function obtenerPorId(int $id): ?array {
        $sql = "
            SELECT 
                i.*, 
                u.nombre_completo AS profesor,
                l.nombre AS linea_nombre
            FROM public.investigaciones_ofertadas i
            LEFT JOIN public.usuarios u ON i.id_profesor = u.id
            LEFT JOIN public.lineas_investigacion l ON i.id_linea = l.id
            WHERE i.id = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $inv = $stmt->fetch();
        if (!$inv) return null;
        
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
        $sql = "
            INSERT INTO public.investigaciones_ofertadas 
            (id_profesor, titulo, planteamiento_problema, objetivo_general, id_linea, id_dimension, cupos_disponibles, estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?) RETURNING id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $id_profesor,
            $datos['titulo'],
            $datos['planteamiento_problema'],
            $datos['objetivo_general'],
            $datos['id_linea'],
            $datos['id_dimension'] ?: null,
            $datos['cupos_disponibles'],
            $datos['estado'] ?? 'Abierta'
        ]);
        $nuevo_id = $stmt->fetchColumn();
        
        // Guardar metadatos
        $this->actualizarMetaInvestigacion($nuevo_id, [
            'trayecto' => $datos['trayecto'] ?? 't4',
            'tag_que'  => $datos['tag_que'] ?? 'Proyecto',
            'imagen'   => $datos['imagen'] ?? 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=600&q=80'
        ]);
        
        return $nuevo_id;
    }
    
    public function actualizarInvestigacion(int $id, array $datos, int $id_usuario, int $nivel): bool {
        // Verificar propiedad o privilegios admin
        $inv = $this->obtenerPorId($id);
        if (!$inv || ($inv['id_profesor'] != $id_usuario && $nivel < 2)) {
            return false;
        }

        $sql = "
            UPDATE public.investigaciones_ofertadas 
            SET titulo = ?, planteamiento_problema = ?, objetivo_general = ?, 
                id_linea = ?, id_dimension = ?, cupos_disponibles = ?, estado = ?
            WHERE id = ?
        ";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $datos['titulo'],
            $datos['planteamiento_problema'],
            $datos['objetivo_general'],
            $datos['id_linea'],
            $datos['id_dimension'] ?: null,
            $datos['cupos_disponibles'],
            $datos['estado'],
            $id
        ]);
        
        if ($result) {
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
        if (!$inv || ($inv['id_profesor'] != $id_usuario && $nivel < 2)) {
            return false;
        }
        
        $sql = "DELETE FROM public.investigaciones_ofertadas WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    public function cambiarEstadoInvestigacion(int $id, string $estado): bool {
        $sql = "UPDATE public.investigaciones_ofertadas SET estado = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$estado, $id]);
    }

    public function obtenerMisPostulaciones(int $id_estudiante): array {
        $sql = "
            SELECT p.*, i.titulo AS investigacion_titulo
            FROM public.postulaciones_estudiantes p
            INNER JOIN public.investigaciones_ofertadas i ON p.id_investigacion = i.id
            WHERE p.id_estudiante = ?
            ORDER BY p.fecha_postulacion DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_estudiante]);
        return $stmt->fetchAll();
    }
    
    public function obtenerPostulantesDeMiProyecto(int $id_profesor): array {
        $sql = "
            SELECT 
                p.*, 
                u.nombre_completo AS estudiante, 
                u.email,
                i.titulo AS investigacion_titulo
            FROM public.postulaciones_estudiantes p
            INNER JOIN public.investigaciones_ofertadas i ON p.id_investigacion = i.id
            INNER JOIN public.usuarios u ON p.id_estudiante = u.id
            WHERE i.id_profesor = ?
            ORDER BY p.fecha_postulacion DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_profesor]);
        return $stmt->fetchAll();
    }
    
    public function obtenerPostulacionesAdmin(): array {
        $sql = "
            SELECT 
                p.*, 
                u.nombre_completo AS estudiante, 
                u.email,
                i.titulo AS investigacion_titulo,
                prof.nombre_completo AS profesor
            FROM public.postulaciones_estudiantes p
            INNER JOIN public.investigaciones_ofertadas i ON p.id_investigacion = i.id
            INNER JOIN public.usuarios u ON p.id_estudiante = u.id
            INNER JOIN public.usuarios prof ON i.id_profesor = prof.id
            ORDER BY p.fecha_postulacion DESC
        ";
        return $this->db->query($sql)->fetchAll();
    }

    public function postularEstudiante(int $id_investigacion, int $id_estudiante, string $motivacion): bool {
        $sqlCheck = "SELECT id FROM public.postulaciones_estudiantes WHERE id_investigacion = ? AND id_estudiante = ?";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->execute([$id_investigacion, $id_estudiante]);
        if ($stmtCheck->fetch()) {
            return false;
        }
        
        $sql = "INSERT INTO public.postulaciones_estudiantes (id_investigacion, id_estudiante, mensaje_motivacion, estado) VALUES (?, ?, ?, 'Pendiente')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_investigacion, $id_estudiante, $motivacion]);
    }
    
    public function responderPostulacion(int $id_postulacion, string $estado, int $id_usuario, int $nivel): bool {
        // Verificar que la postulación pertenece a un proyecto del profesor, o es admin
        $sqlCheck = "
            SELECT p.id, i.id_profesor 
            FROM public.postulaciones_estudiantes p
            INNER JOIN public.investigaciones_ofertadas i ON p.id_investigacion = i.id
            WHERE p.id = ?
        ";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->execute([$id_postulacion]);
        $postulacion = $stmtCheck->fetch();
        
        if (!$postulacion || ($postulacion['id_profesor'] != $id_usuario && $nivel < 2)) {
            return false;
        }
        
        $sql = "UPDATE public.postulaciones_estudiantes SET estado = ?, fecha_respuesta = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$estado, $id_postulacion]);
    }

    public function obtenerInvestigadores(): array {
        // Traemos profesores (o admins) que tengan investigaciones creadas o sean rol profesor activo
        $sql = "
            SELECT 
                u.id, 
                u.nombre_completo, 
                u.email,
                COUNT(i.id) as total_investigaciones,
                r.nombre as rol_nombre
            FROM public.usuarios u
            INNER JOIN public.roles r ON u.id_rol = r.id
            LEFT JOIN public.investigaciones_ofertadas i ON u.id = i.id_profesor
            WHERE u.activo = true AND (r.nombre = 'Profesor' OR i.id IS NOT NULL)
            GROUP BY u.id, u.nombre_completo, u.email, r.nombre
            ORDER BY total_investigaciones DESC, u.nombre_completo ASC
        ";
        return $this->db->query($sql)->fetchAll();
    }
}
