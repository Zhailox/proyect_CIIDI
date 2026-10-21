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
            'formulario_google' => [
                'url_default' => 'https://docs.google.com/forms/d/e/1FAIpQLSean-NUF1PEU6ci8MiNIQp13OewWCofp7O7V3MV8k_CA1CHNQ/viewform',
                'modo'        => 'embebido',
                'habilitado'  => true,
                'titulo'      => 'Formulario Oficial de Inscripción CIIDI',
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

        if (!empty($filtros['id_linea'])) {
            $condiciones[] = "EXISTS (SELECT 1 FROM public.cursos_lineas cl WHERE cl.id_curso = c.id AND cl.id_linea = ?)";
            $parametros[]  = (int)$filtros['id_linea'];
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
        $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Hidratar con líneas y dimensiones para optimizar render sin N+1
        if (!empty($cursos)) {
            $ids = array_column($cursos, 'id');
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            // Líneas asociadas
            $stmtL = $this->db->prepare("
                SELECT cl.id_curso, l.id, l.nombre
                FROM public.cursos_lineas cl
                INNER JOIN public.lineas_investigacion l ON cl.id_linea = l.id
                WHERE cl.id_curso IN ($placeholders)
                ORDER BY l.nombre ASC
            ");
            $stmtL->execute($ids);
            $lineasPorCurso = [];
            while ($row = $stmtL->fetch(PDO::FETCH_ASSOC)) {
                $lineasPorCurso[$row['id_curso']][] = $row;
            }

            // Dimensiones asociadas
            $stmtD = $this->db->prepare("
                SELECT cd.id_curso, d.id, d.id_linea, d.nombre
                FROM public.cursos_dimensiones cd
                INNER JOIN public.dimensiones_operativas d ON cd.id_dimension = d.id
                WHERE cd.id_curso IN ($placeholders)
                ORDER BY d.nombre ASC
            ");
            $stmtD->execute($ids);
            $dimsPorCurso = [];
            while ($row = $stmtD->fetch(PDO::FETCH_ASSOC)) {
                $dimsPorCurso[$row['id_curso']][] = $row;
            }

            foreach ($cursos as &$c) {
                $c['lineas']      = $lineasPorCurso[$c['id']] ?? [];
                $c['dimensiones'] = $dimsPorCurso[$c['id']]   ?? [];
            }
            unset($c);
        }

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
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultado) {
            $resultado['lineas']          = $this->obtenerLineasDeCurso((int)$resultado['id']);
            $resultado['lineas_ids']      = array_column($resultado['lineas'], 'id');
            $resultado['dimensiones']     = $this->obtenerDimensionesDeCurso((int)$resultado['id']);
            $resultado['dimensiones_ids'] = array_column($resultado['dimensiones'], 'id');
        }
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
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultado) {
            $resultado['lineas']          = $this->obtenerLineasDeCurso((int)$resultado['id']);
            $resultado['lineas_ids']      = array_column($resultado['lineas'], 'id');
            $resultado['dimensiones']     = $this->obtenerDimensionesDeCurso((int)$resultado['id']);
            $resultado['dimensiones_ids'] = array_column($resultado['dimensiones'], 'id');
        }
        return $resultado ?: null;
    }

    private function crearSlug(string $titulo, ?int $excluirId = null): string {
        $slug = mb_strtolower(trim($titulo));
        $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
        $slug = trim($slug, '-');
        if (empty($slug)) $slug = 'curso-' . time();

        $baseSlug = $slug;
        $count = 1;
        while (true) {
            $sql = "SELECT id FROM public.cursos WHERE slug = ?" . ($excluirId ? " AND id != ?" : "");
            $stmt = $this->db->prepare($sql);
            $params = $excluirId ? [$slug, $excluirId] : [$slug];
            $stmt->execute($params);
            if (!$stmt->fetch()) {
                break;
            }
            $count++;
            $slug = $baseSlug . '-' . $count;
        }

        return $slug;
    }

    public function crearCurso(array $datos) {
        $slug = $this->crearSlug($datos['titulo'] ?? '');
        $sql = "
            INSERT INTO public.cursos
                (id_docente, titulo, descripcion, imagen_portada, estado, nota_minima_aprobacion, url_moodle, modalidad, nivel, duracion, cupo_maximo, fecha_inicio, fecha_fin, url_video_preview, slug, estado_inscripcion, url_formulario_google)
            VALUES
                (?, ?, ?, ?, ?::public.estado_curso_enum, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
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
                    $datos['estado_inscripcion'] ?? 'Abierta',
                    trim($datos['url_formulario_google'] ?? '') ?: null
        ]);
        $resultado = $stmt->fetch();
        $nuevo_id = $resultado ? (int)$resultado['id'] : false;

        if ($nuevo_id) {
            $this->guardarLineasYDimensiones($nuevo_id, $datos['id_linea'] ?? [], $datos['id_dimension'] ?? []);
        }

        return $nuevo_id;
    }

    public function editarCurso(int $id, array $datos): bool {
        $slug = $this->crearSlug($datos['titulo'] ?? '', $id);
        
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
                url_formulario_google  = ?,
                fecha_actualizacion    = CURRENT_TIMESTAMP
            WHERE id = ?
        ";
        
        $stmt = $this->db->prepare($sql);
        $ok = $stmt->execute([
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
                    trim($datos['url_formulario_google'] ?? '') ?: null,
            $id
        ]);

        if ($ok) {
            $this->guardarLineasYDimensiones($id, $datos['id_linea'] ?? [], $datos['id_dimension'] ?? []);
        }

        return $ok;
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
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================================================
    //  LÍNEAS DE INVESTIGACIÓN Y DIMENSIONES
    // =========================================================

    public function obtenerLineas(): array {
        $sql = "SELECT id, nombre, descripcion FROM public.lineas_investigacion WHERE activo = true ORDER BY nombre ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTodasDimensiones(): array {
        $sql = "SELECT id, id_linea, nombre FROM public.dimensiones_operativas WHERE activo = true ORDER BY nombre ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerLineasDeCurso(int $id_curso): array {
        $sql = "
            SELECT l.id, l.nombre, l.descripcion
            FROM public.cursos_lineas cl
            INNER JOIN public.lineas_investigacion l ON cl.id_linea = l.id
            WHERE cl.id_curso = ?
            ORDER BY l.nombre ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_curso]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerDimensionesDeCurso(int $id_curso): array {
        $sql = "
            SELECT d.id, d.id_linea, d.nombre, d.descripcion
            FROM public.cursos_dimensiones cd
            INNER JOIN public.dimensiones_operativas d ON cd.id_dimension = d.id
            WHERE cd.id_curso = ?
            ORDER BY d.nombre ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_curso]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardarLineasYDimensiones(int $id_curso, array $lineas_ids, array $dimensiones_ids): void {
        // Líneas
        $stmtDelL = $this->db->prepare("DELETE FROM public.cursos_lineas WHERE id_curso = ?");
        $stmtDelL->execute([$id_curso]);

        if (!empty($lineas_ids)) {
            $stmtInsL = $this->db->prepare("INSERT INTO public.cursos_lineas (id_curso, id_linea) VALUES (?, ?) ON CONFLICT DO NOTHING");
            foreach ($lineas_ids as $lid) {
                $lid = (int)$lid;
                if ($lid > 0) $stmtInsL->execute([$id_curso, $lid]);
            }
        }

        // Dimensiones
        $stmtDelD = $this->db->prepare("DELETE FROM public.cursos_dimensiones WHERE id_curso = ?");
        $stmtDelD->execute([$id_curso]);

        if (!empty($dimensiones_ids)) {
            $stmtInsD = $this->db->prepare("INSERT INTO public.cursos_dimensiones (id_curso, id_dimension) VALUES (?, ?) ON CONFLICT DO NOTHING");
            foreach ($dimensiones_ids as $did) {
                $did = (int)$did;
                if ($did > 0) $stmtInsD->execute([$id_curso, $did]);
            }
        }
    }

    public function registrarPreinscripcion(array $datos): bool {
        $sql = "
            INSERT INTO public.cursos_preinscripciones 
                (id_curso, nombre_completo, correo_electronico, telefono, horario_deseado, mensaje)
            VALUES (?, ?, ?, ?, ?, ?)
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            (int)$datos['id_curso'],
            trim($datos['nombre_completo']),
            trim($datos['correo_electronico']),
            trim($datos['telefono'] ?? ''),
            trim($datos['horario_deseado']),
            trim($datos['mensaje'] ?? '')
        ]);
    }
}
