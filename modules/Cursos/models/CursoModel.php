<?php
// modules/Cursos/models/CursoModel.php
require_once CORE_PATH . 'Database/QueryBuilder.php';

class CursoModel {

    private $qb;
    private $db;

    /** Ruta al archivo JSON de metadatos extendidos por curso */
    private string $metaFile;

    /** Ruta al archivo JSON de configuración del módulo */
    private string $configFile;

    public function __construct() {
        $this->qb         = new QueryBuilder();
        $this->db         = Connection::getInstance();
        $this->metaFile   = dirname(__DIR__, 3) . '/storage/cursos_moodle.json';
        $this->configFile = __DIR__ . '/../config_cursos.json';
    }

    // =========================================================
    //  CONFIGURACIÓN DEL MÓDULO
    // =========================================================

    /**
     * Carga y devuelve la configuración del módulo desde config_cursos.json.
     * Valores del JSON son la fuente de verdad; se devuelve un array
     * con defaults de seguridad si el archivo no existiera.
     */
    public function cargarConfig(): array {
        if (!file_exists($this->configFile)) {
            return $this->configDefaults();
        }
        $raw = file_get_contents($this->configFile);
        $cfg = json_decode($raw, true);
        if (!is_array($cfg)) {
            return $this->configDefaults();
        }
        // Merge con defaults para garantizar todas las claves
        return array_replace_recursive($this->configDefaults(), $cfg);
    }

    /**
     * Persiste la configuración editada al archivo JSON.
     */
    public function guardarConfig(array $cfg): void {
        file_put_contents(
            $this->configFile,
            json_encode($cfg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            LOCK_EX
        );
    }

    private function configDefaults(): array {
        return [
            'paginacion' => [
                'limite_catalogo'   => 9,
                'opciones_selector' => [6, 9, 12, 18],
            ],
            'imagenes' => [
                'max_size_mb'           => 5,
                'extensiones_permitidas'=> ['jpg','jpeg','png','webp','gif'],
                'mime_permitidos'       => ['image/jpeg','image/png','image/gif','image/webp'],
                'convertir_a_webp'      => true,
                'lazy_load'             => true,
                'carpeta_uploads'       => 'public/uploads/cursos/',
                'placeholder_url'       => 'https://images.unsplash.com/photo-1501504905252-473c47e087f8?auto=format&fit=crop&q=80&w=600',
            ],
            'moodle' => [
                'url_fallback' => 'https://www.youtube.com',
            ],
            'seguridad' => [
                'csrf_activo' => true,
            ],
            'roles' => [
                'nivel_ver_catalogo'  => -1,
                'nivel_crear_curso'   => 1,
                'nivel_eliminar_curso'=> 2,
                'nivel_ver_config'    => 3,
            ],
        ];
    }

    // =========================================================
    //  JSON METADATA — Campos extra sin tocar la BD
    // =========================================================

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

    public function obtenerMetaCurso(int $id): array {
        $meta = $this->cargarMeta();
        return $meta[(string)$id] ?? [
            'url_moodle'        => '',
            'url_video_preview' => '',
            'modalidad'         => 'Virtual',
            'nivel'             => 'Básico',
            'duracion'          => '',
            'cupo_maximo'       => 0,
        ];
    }

    public function guardarMetaCurso(int $id, array $datos): void {
        $meta = $this->cargarMeta();
        $campos_permitidos = [
            'url_moodle', 'url_video_preview', 'modalidad',
            'nivel', 'duracion', 'cupo_maximo',
        ];
        foreach ($campos_permitidos as $campo) {
            $meta[(string)$id][$campo] = $datos[$campo] ?? '';
        }
        $this->guardarMeta($meta);
    }

    public function eliminarMetaCurso(int $id): void {
        $meta = $this->cargarMeta();
        unset($meta[(string)$id]);
        $this->guardarMeta($meta);
    }

    private function mergeMetadata(array $cursos): array {
        if (empty($cursos)) return [];
        $meta     = $this->cargarMeta();
        $defaults = [
            'url_moodle'        => '',
            'url_video_preview' => '',
            'modalidad'         => 'Virtual',
            'nivel'             => 'Básico',
            'duracion'          => '',
            'cupo_maximo'       => 0,
        ];
        foreach ($cursos as &$curso) {
            $m      = $meta[(string)$curso['id']] ?? [];
            $curso  = array_merge($defaults, $m, $curso);
        }
        return $cursos;
    }

    // =========================================================
    //  CONSULTAS PRINCIPALES
    // =========================================================

    /**
     * Lista cursos con filtros, soporte de paginación y total de filas.
     * Devuelve ['cursos' => [...], 'total' => int].
     */
    public function listarCursos(array $filtros = [], int $pagina = 1, int $porPagina = 9): array {
        $condiciones = [];
        $parametros  = [];

        if (!empty($filtros['estado'])) {
            $estados_validos = ['publicado', 'borrador', 'archivado'];
            if (in_array($filtros['estado'], $estados_validos)) {
                $condiciones[] = "c.estado = ?";
                $parametros[]  = $filtros['estado'];
            }
        }

        if (!empty($filtros['id_docente'])) {
            $condiciones[] = "c.id_docente = ?";
            $parametros[]  = (int)$filtros['id_docente'];
        }

        if (!empty($filtros['busqueda'])) {
            $condiciones[] = "(c.titulo ILIKE ? OR c.descripcion ILIKE ?)";
            $term          = '%' . $filtros['busqueda'] . '%';
            $parametros[]  = $term;
            $parametros[]  = $term;
        }

        $where = !empty($condiciones) ? 'WHERE ' . implode(' AND ', $condiciones) : '';

        // Consulta de total
        $sqlTotal = "SELECT COUNT(*) AS total FROM public.cursos c LEFT JOIN public.usuarios u ON c.id_docente = u.id {$where}";
        $stmtTotal = $this->db->prepare($sqlTotal);
        $stmtTotal->execute($parametros);
        $total = (int)($stmtTotal->fetch()['total'] ?? 0);

        // Consulta de datos paginada
        $offset = ($pagina - 1) * $porPagina;
        $sql = "
            SELECT
                c.id, c.titulo, c.descripcion, c.imagen_portada,
                c.estado, c.nota_minima_aprobacion,
                c.fecha_creacion, c.fecha_actualizacion,
                c.id_docente,
                u.nombre_completo AS nombre_docente
            FROM public.cursos c
            LEFT JOIN public.usuarios u ON c.id_docente = u.id
            {$where}
            ORDER BY c.fecha_creacion DESC
            LIMIT ? OFFSET ?
        ";
        $paramsData   = array_merge($parametros, [$porPagina, $offset]);
        $stmt = $this->db->prepare($sql);
        $stmt->execute($paramsData);
        $cursos = $stmt->fetchAll();

        return [
            'cursos' => $this->mergeMetadata($cursos),
            'total'  => $total,
        ];
    }

    public function obtenerPorId(int $id): ?array {
        $sql = "
            SELECT c.*, u.nombre_completo AS nombre_docente
            FROM public.cursos c
            LEFT JOIN public.usuarios u ON c.id_docente = u.id
            WHERE c.id = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $resultado = $stmt->fetch();
        if (!$resultado) return null;

        $meta = $this->obtenerMetaCurso($id);
        return array_merge($meta, $resultado);
    }

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
            (int)   $datos['id_docente'],
                    trim($datos['titulo']),
                    trim($datos['descripcion']    ?? ''),
                    trim($datos['imagen_portada'] ?? '') ?: null,
                    $datos['estado'] ?? 'borrador',
            (float) ($datos['nota_minima_aprobacion'] ?? 70.00),
        ]);
        $resultado = $stmt->fetch();
        return $resultado ? (int)$resultado['id'] : false;
    }

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
            (int)   $datos['id_docente'],
                    trim($datos['titulo']),
                    trim($datos['descripcion']    ?? ''),
                    trim($datos['imagen_portada'] ?? '') ?: null,
                    $datos['estado'] ?? 'borrador',
            (float) ($datos['nota_minima_aprobacion'] ?? 70.00),
            $id,
        ]);
    }

    public function eliminarCurso(int $id): bool {
        $sql  = "DELETE FROM public.cursos WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $ok   = $stmt->execute([$id]);
        if ($ok) $this->eliminarMetaCurso($id);
        return $ok;
    }

    public function obtenerEstadisticas(): array {
        $sql = "
            SELECT
                COUNT(*) FILTER (WHERE estado = 'publicado') AS publicados,
                COUNT(*) FILTER (WHERE estado = 'borrador')  AS borradores,
                COUNT(*) FILTER (WHERE estado = 'archivado') AS archivados,
                COUNT(*)                                      AS total
            FROM public.cursos
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch() ?: [
            'publicados' => 0, 'borradores' => 0, 'archivados' => 0, 'total' => 0,
        ];
    }

    public function listarDocentes(): array {
        $sql  = "SELECT id, nombre_completo FROM public.usuarios WHERE activo = true ORDER BY nombre_completo ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
