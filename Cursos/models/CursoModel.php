<?php
// modules/Cursos/models/CursoModel.php
require_once CORE_PATH . 'Database/QueryBuilder.php';

class CursoModel {

    private $qb;
    private $db;
    private string $configFile;

    public function __construct() {
        $this->qb         = new QueryBuilder();
        $this->db         = Connection::getInstance();
        $this->configFile = __DIR__ . '/../config_cursos.json';
    }

    public function cargarConfig(): array {
        if (!file_exists($this->configFile)) {
            return $this->configDefaults();
        }
        $raw = file_get_contents($this->configFile);
        $cfg = json_decode($raw, true);
        if (!is_array($cfg)) {
            return $this->configDefaults();
        }
        return array_replace_recursive($this->configDefaults(), $cfg);
    }

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
                'carpeta_uploads'       => 'storage/uploads/cursos/',
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
    //  CONSULTAS PRINCIPALES
    // =========================================================

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

        if (!empty($filtros['modalidad'])) {
            $condiciones[] = "c.modalidad = ?";
            $parametros[]  = $filtros['modalidad'];
        }

        if (!empty($filtros['nivel'])) {
            $condiciones[] = "c.nivel = ?";
            $parametros[]  = $filtros['nivel'];
        }

        if (!empty($filtros['busqueda'])) {
            $condiciones[] = "(c.titulo ILIKE ? OR c.descripcion ILIKE ?)";
            $term          = '%' . $filtros['busqueda'] . '%';
            $parametros[]  = $term;
            $parametros[]  = $term;
        }

        $where = !empty($condiciones) ? 'WHERE ' . implode(' AND ', $condiciones) : '';

        $sqlTotal = "SELECT COUNT(*) AS total FROM public.cursos c {$where}";
        $stmtTotal = $this->db->prepare($sqlTotal);
        $stmtTotal->execute($parametros);
        $total = (int)($stmtTotal->fetch()['total'] ?? 0);

        $offset = ($pagina - 1) * $porPagina;
        $sql = "
            SELECT
                c.*,
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
            'cursos' => $cursos,
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
        return $resultado ?: null;
    }

    public function obtenerPorSlug(string $slug): ?array {
        $sql = "
            SELECT c.*, u.nombre_completo AS nombre_docente
            FROM public.cursos c
            LEFT JOIN public.usuarios u ON c.id_docente = u.id
            WHERE c.slug = ? OR c.id::varchar = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$slug, $slug]);
        $resultado = $stmt->fetch();
        return $resultado ?: null;
    }

    private function crearSlug(string $titulo): string {
        $slug = mb_strtolower(trim($titulo));
        $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
        $slug = trim($slug, '-');
        if (empty($slug)) $slug = 'curso-' . time();
        return $slug;
    }

    public function crearCurso(array $datos) {
        $slug = $this->crearSlug($datos['titulo'] ?? '');
        $sql = "
            INSERT INTO public.cursos
                (id_docente, titulo, descripcion, imagen_portada, estado, nota_minima_aprobacion, url_moodle, modalidad, nivel, duracion, cupo_maximo, fecha_inicio, fecha_fin, url_video_preview, slug, estado_inscripcion)
            VALUES
                (?, ?, ?, ?, ?::public.estado_curso_enum, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            RETURNING id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            (int)   $datos['id_docente'],
                    trim($datos['titulo']),
                    trim($datos['descripcion'] ?? ''),
                    trim($datos['imagen_portada'] ?? '') ?: null,
                    $datos['estado'] ?? 'borrador',
            (float) ($datos['nota_minima_aprobacion'] ?? 70.00),
                    $datos['url_moodle'] ?? '',
                    $datos['modalidad'] ?? 'Virtual',
                    $datos['nivel'] ?? 'Básico',
                    $datos['duracion'] ?? '',
                    (empty($datos['cupo_maximo']) ? null : (int)$datos['cupo_maximo']),
                    (empty($datos['fecha_inicio']) ? null : $datos['fecha_inicio']),
                    (empty($datos['fecha_fin']) ? null : $datos['fecha_fin']),
                    $datos['url_video_preview'] ?? '',
                    $slug,
                    $datos['estado_inscripcion'] ?? 'Abierta'
        ]);
        $resultado = $stmt->fetch();
        return $resultado ? (int)$resultado['id'] : false;
    }

    public function editarCurso(int $id, array $datos): bool {
        $slug = $this->crearSlug($datos['titulo'] ?? '');
        
        $sql = "
            UPDATE public.cursos SET
                id_docente             = ?,
                titulo                 = ?,
                descripcion            = ?,
                imagen_portada         = COALESCE(NULLIF(?,''), imagen_portada),
                estado                 = ?::public.estado_curso_enum,
                nota_minima_aprobacion = ?,
                url_moodle             = ?,
                modalidad              = ?,
                nivel                  = ?,
                duracion               = ?,
                cupo_maximo            = ?,
                fecha_inicio           = ?,
                fecha_fin              = ?,
                url_video_preview      = ?,
                slug                   = ?,
                estado_inscripcion     = ?,
                fecha_actualizacion    = CURRENT_TIMESTAMP
            WHERE id = ?
        ";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            (int)   $datos['id_docente'],
                    trim($datos['titulo']),
                    trim($datos['descripcion'] ?? ''),
                    trim($datos['imagen_portada'] ?? '') ?: '',
                    $datos['estado'] ?? 'borrador',
            (float) ($datos['nota_minima_aprobacion'] ?? 70.00),
                    $datos['url_moodle'] ?? '',
                    $datos['modalidad'] ?? 'Virtual',
                    $datos['nivel'] ?? 'Básico',
                    $datos['duracion'] ?? '',
                    (empty($datos['cupo_maximo']) ? null : (int)$datos['cupo_maximo']),
                    (empty($datos['fecha_inicio']) ? null : $datos['fecha_inicio']),
                    (empty($datos['fecha_fin']) ? null : $datos['fecha_fin']),
                    $datos['url_video_preview'] ?? '',
                    $slug,
                    $datos['estado_inscripcion'] ?? 'Abierta',
            $id
        ]);
    }

    public function eliminarCurso(int $id): bool {
        $sql  = "DELETE FROM public.cursos WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
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
