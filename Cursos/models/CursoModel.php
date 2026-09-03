<?php
// modules/Cursos/models/CursoModel.php
require_once CORE_PATH . 'Database/QueryBuilder.php';

class CursoModel {

    private $qb;
    private $db;

    public function __construct() {
        $this->qb = new QueryBuilder();
        $this->db = Connection::getInstance();
    }

    /**
     * Lista todos los cursos con el nombre completo del docente.
     * Acepta filtros opcionales: estado, id_docente.
     */
    public function listarCursos(array $filtros = []): array {
        $sql = "
            SELECT 
                c.id,
                c.titulo,
                c.descripcion,
                c.imagen_portada,
                c.estado,
                c.nota_minima_aprobacion,
                c.fecha_creacion,
                c.fecha_actualizacion,
                c.id_docente,
                u.nombre_completo AS nombre_docente
            FROM public.cursos c
            LEFT JOIN public.usuarios u ON c.id_docente = u.id
        ";

        $condiciones = [];
        $parametros  = [];

        if (!empty($filtros['estado'])) {
            $condiciones[] = "c.estado = ?";
            $parametros[]  = $filtros['estado'];
        }

        if (!empty($filtros['id_docente'])) {
            $condiciones[] = "c.id_docente = ?";
            $parametros[]  = (int) $filtros['id_docente'];
        }

        if (!empty($condiciones)) {
            $sql .= " WHERE " . implode(" AND ", $condiciones);
        }

        $sql .= " ORDER BY c.fecha_creacion DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetchAll();
    }

    /**
     * Obtiene un único curso por su ID.
     */
    public function obtenerPorId(int $id): ?array {
        $sql = "
            SELECT 
                c.*,
                u.nombre_completo AS nombre_docente
            FROM public.cursos c
            LEFT JOIN public.usuarios u ON c.id_docente = u.id
            WHERE c.id = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $resultado = $stmt->fetch();
        return $resultado ?: null;
    }

    /**
     * Crea un nuevo curso. Devuelve el ID generado o false si falla.
     */
    public function crearCurso(array $datos) {
        $sql = "
            INSERT INTO public.cursos 
                (id_docente, titulo, descripcion, imagen_portada, estado, nota_minima_aprobacion)
            VALUES 
                (?, ?, ?, ?, ?::public.estado_curso_enum, ?)
            RETURNING id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            (int) $datos['id_docente'],
            trim($datos['titulo']),
            trim($datos['descripcion'] ?? ''),
            trim($datos['imagen_portada'] ?? '') ?: null,
            $datos['estado'] ?? 'borrador',
            (float) ($datos['nota_minima_aprobacion'] ?? 70.00)
        ]);
        $resultado = $stmt->fetch();
        return $resultado ? (int) $resultado['id'] : false;
    }

    /**
     * Actualiza un curso existente. Devuelve true si se modificó al menos una fila.
     */
    public function editarCurso(int $id, array $datos): bool {
        $sql = "
            UPDATE public.cursos SET
                id_docente             = ?,
                titulo                 = ?,
                descripcion            = ?,
                imagen_portada         = ?,
                estado                 = ?::public.estado_curso_enum,
                nota_minima_aprobacion = ?,
                fecha_actualizacion    = CURRENT_TIMESTAMP
            WHERE id = ?
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            (int) $datos['id_docente'],
            trim($datos['titulo']),
            trim($datos['descripcion'] ?? ''),
            trim($datos['imagen_portada'] ?? '') ?: null,
            $datos['estado'] ?? 'borrador',
            (float) ($datos['nota_minima_aprobacion'] ?? 70.00),
            $id
        ]);
    }

    /**
     * Elimina un curso por su ID.
     */
    public function eliminarCurso(int $id): bool {
        $sql  = "DELETE FROM public.cursos WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Devuelve estadísticas rápidas para el hero de la vista.
     */
    public function obtenerEstadisticas(): array {
        $sql = "
            SELECT
                COUNT(*) FILTER (WHERE estado = 'publicado')  AS publicados,
                COUNT(*) FILTER (WHERE estado = 'borrador')   AS borradores,
                COUNT(*) FILTER (WHERE estado = 'archivado')  AS archivados,
                COUNT(*)                                       AS total
            FROM public.cursos
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch() ?: ['publicados' => 0, 'borradores' => 0, 'archivados' => 0, 'total' => 0];
    }

    /**
     * Lista los usuarios disponibles para asignar como docente.
     */
    public function listarDocentes(): array {
        $sql  = "SELECT id, nombre_completo FROM public.usuarios WHERE activo = true ORDER BY nombre_completo ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
