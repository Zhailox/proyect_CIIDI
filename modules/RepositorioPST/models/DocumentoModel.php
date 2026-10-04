<?php
// modules/RepositorioPST/models/DocumentoModel.php
require_once CORE_PATH . 'Database/Connection.php';
require_once CORE_PATH . 'Database/QueryBuilder.php';

class DocumentoModel {

    /**
     * Traducción de caracteres corruptos de codificación DOS CP850 a UTF-8.
    /**
     * Normalización de caracteres y conversión de codificación CP850/ISO a UTF-8.
     */
    private function cleanCP850(?string $str): string {
        if ($str === null || $str === '') return '';
        
        if (!mb_check_encoding($str, 'UTF-8')) {
            $converted = @mb_convert_encoding($str, 'UTF-8', 'CP850, ISO-8859-1, Windows-1252');
            if ($converted !== false && $converted !== '') {
                $str = $converted;
            }
        }
        
        $map = [
            '¢' => 'ó', '¤' => 'ñ', '¡' => 'í', '£' => 'ú', '¥' => 'Ñ', '‚' => 'é',
            "\xC2\xA0" => ' ', "\xA0" => ' '
        ];
        
        return strtr($str, $map);
    }
    
    private function cleanRow(array $row): array {
        foreach ($row as $key => $value) {
            if (is_string($value)) {
                $row[$key] = $this->cleanCP850($value);
            }
        }
        return $row;
    }
    
    private function cleanArray(array $arr): array {
        return array_map([$this, 'cleanRow'], $arr);
    }
    
    /**
     * Obtiene los documentos clasificados como PST (tipo 1) aplicando filtros y paginación.
     */
    public function getPSTDocumentos(array $filtros = [], int $limit = 5, int $offset = 0): array {
        $db = Connection::getInstance();
        
        $sql = "SELECT r.id, r.titulo, r.anio_publicacion, r.archivo_pdf,
                       dp.resumen, dp.obj_general, dp.palabras_clave, dp.comunidad_beneficiada, dp.nivel_academico, 
                       dp.id_trayecto, t.nombre AS trayecto_nombre, t.numero AS trayecto_numero, 
                       t.nombre AS trayecto, 
                       dp.url_repositorio, dp.fecha_defensa, COALESCE(dp.activo, true) AS activo,
                       COALESCE(dp.vistas, 0) AS vistas,
                       li.nombre AS linea_nombre, 
                       li.id AS linea_id,
                       dims.nombre AS dimension_nombre,
                       dims.id AS dimension_id,
                       c.nombre AS carrera_nombre,
                       c.id AS carrera_id,
                       (SELECT STRING_AGG(c_vinc.nombre, ', ') 
                        FROM public.proyecto_carreras_vinculadas pcv 
                        JOIN public.carreras c_vinc ON pcv.id_carrera = c_vinc.id 
                        WHERE pcv.id_recurso = r.id) AS carreras_vinculadas_nombres,
                       (SELECT STRING_AGG(a.nombre_completo, ', ') 
                        FROM public.recurso_autores ra 
                        JOIN public.autores a ON ra.id_autor = a.id 
                        WHERE ra.id_recurso = r.id) AS autores_nombres,
                       (SELECT STRING_AGG(t.nombre_completo || ' - ' || 
                            CASE pt.tipo_tutor_id 
                                WHEN 3 THEN 'Tutor Académico'
                                WHEN 2 THEN 'Tutor Institucional'
                                WHEN 4 THEN 'Tutor Comunitario'
                                ELSE COALESCE(tt.nombre, 'Tutor')
                            END, ' • ') 
                        FROM public.proyecto_tutores pt 
                        JOIN public.tutores t ON pt.id_tutor = t.id 
                        LEFT JOIN public.tipo_tutor tt ON pt.tipo_tutor_id = tt.id
                        WHERE pt.id_recurso = r.id) AS tutores_nombres
                FROM public.recursos r
                LEFT JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso
                LEFT JOIN public.trayectos t ON dp.id_trayecto = t.id
                LEFT JOIN public.recurso_clasificaciones rc ON r.id = rc.id_recurso
                LEFT JOIN public.lineas_investigacion li ON rc.id_linea_investigacion = li.id
                LEFT JOIN public.dimensiones_operativas dims ON rc.id_dimension_operativa = dims.id
                LEFT JOIN public.carreras c ON COALESCE(dp.id_carrera, li.id_carrera) = c.id
                WHERE r.id_tipo_recurso = 1"; 
                
        $execParams = [];

        if (!empty($filtros['carrera_id'])) {
            $sql .= " AND (COALESCE(dp.id_carrera, li.id_carrera) = ? OR EXISTS (SELECT 1 FROM public.proyecto_carreras_vinculadas pcv WHERE pcv.id_recurso = r.id AND pcv.id_carrera = ?))";
            $execParams[] = (int)$filtros['carrera_id'];
            $execParams[] = (int)$filtros['carrera_id'];
        }

        if (isset($filtros['activo'])) {
            if ($filtros['activo'] !== 'todos') {
                $sql .= " AND COALESCE(dp.activo, true) = ?";
                $execParams[] = ($filtros['activo'] === true || $filtros['activo'] === '1' || $filtros['activo'] === 1) ? true : false;
            }
        } else {
            // Por defecto en vistas públicas solo retornar los proyectos activos
            $sql .= " AND COALESCE(dp.activo, true) = true";
        }
        
        if (!empty($filtros['linea_id'])) {
            $sql .= " AND rc.id_linea_investigacion = ?";
            $execParams[] = (int)$filtros['linea_id'];
        }
        
        if (!empty($filtros['dimension_id'])) {
            $sql .= " AND rc.id_dimension_operativa = ?";
            $execParams[] = (int)$filtros['dimension_id'];
        }

        if (!empty($filtros['nivel_academico'])) {
            $nivelMap = [
                'Especialización' => 'Especializacion',
                'Maestría'        => 'Maestria'
            ];
            $valNivel = trim($filtros['nivel_academico']);
            $valNivel = $nivelMap[$valNivel] ?? $valNivel;
            $sql .= " AND dp.nivel_academico = ?";
            $execParams[] = $valNivel;
        }

        if (!empty($filtros['trayecto'])) {
            $trayectoParam = trim((string)$filtros['trayecto']);
            if (is_numeric($trayectoParam)) {
                $sql .= " AND (dp.id_trayecto = ? OR t.numero = ?)";
                $execParams[] = (int)$trayectoParam;
                $execParams[] = (int)$trayectoParam;
            } else {
                $sql .= " AND t.nombre = ?";
                $execParams[] = $trayectoParam;
            }
        }

        if (!empty($filtros['comunidad'])) {
            $sql .= " AND dp.comunidad_beneficiada ILIKE ?";
            $execParams[] = '%' . trim($filtros['comunidad']) . '%';
        }
        
        if (!empty($filtros['anio'])) {
            $sql .= " AND r.anio_publicacion = ?";
            $execParams[] = (int)$filtros['anio'];
        }
        
        if (!empty($filtros['orden']) && $filtros['orden'] === 'asc') {
            $sql .= " ORDER BY r.anio_publicacion ASC, r.id ASC LIMIT ? OFFSET ?";
        } else {
            $sql .= " ORDER BY r.anio_publicacion DESC, r.id DESC LIMIT ? OFFSET ?";
        }
        
        $execParams[] = $limit;
        $execParams[] = $offset;
        
        $stmt = $db->prepare($sql);
        $stmt->execute($execParams);
        return $this->cleanArray($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Retorna la lista única de comunidades beneficiadas que existen en la BD.
     */
    public function getComunidadesBeneficiadas(): array {
        $db = Connection::getInstance();
        $sql = "SELECT DISTINCT comunidad_beneficiada 
                FROM public.detalles_proyectos 
                WHERE comunidad_beneficiada IS NOT NULL AND TRIM(comunidad_beneficiada) != '' 
                ORDER BY comunidad_beneficiada ASC";
        $stmt = $db->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $comunidades = [];
        foreach ($rows as $row) {
            $clean = $this->cleanCP850($row['comunidad_beneficiada']);
            if (!empty($clean) && !in_array($clean, $comunidades)) {
                $comunidades[] = $clean;
            }
        }
        return $comunidades;
    }

    /**
     * Obtiene proyectos dinámicamente relacionados o similares en base a la línea de investigación o palabras clave.
     */
    public function getProyectosSimilares(int $idRecursoActual, ?int $lineaId = null, int $limit = 3): array {
        $db = Connection::getInstance();
        $sql = "SELECT r.id, r.titulo, r.anio_publicacion, dp.resumen, li.nombre AS linea_nombre
                FROM public.recursos r
                LEFT JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso
                LEFT JOIN public.recurso_clasificaciones rc ON r.id = rc.id_recurso
                LEFT JOIN public.lineas_investigacion li ON rc.id_linea_investigacion = li.id
                WHERE r.id_tipo_recurso = 1 AND r.id != ?";
        $params = [$idRecursoActual];
        if ($lineaId) {
            $sql .= " AND rc.id_linea_investigacion = ?";
            $params[] = $lineaId;
        }
        $sql .= " ORDER BY r.id DESC LIMIT ?";
        $params[] = $limit;
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $this->cleanArray($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Cuenta cuántos proyectos existen para una comunidad beneficiada específica (excluyendo opcionalmente el actual).
     */
    public function getConteoProyectosComunidad(string $comunidad, ?int $idExcluir = null): int {
        $db = Connection::getInstance();
        $sql = "SELECT COUNT(DISTINCT r.id) as total 
                FROM public.recursos r
                JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso
                WHERE r.id_tipo_recurso = 1 
                  AND dp.comunidad_beneficiada IS NOT NULL 
                  AND TRIM(LOWER(dp.comunidad_beneficiada)) = TRIM(LOWER(?))";
        $params = [$comunidad];
        if ($idExcluir) {
            $sql .= " AND r.id != ?";
            $params[] = $idExcluir;
        }
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? (int)$res['total'] : 0;
    }

    /**
     * Obtiene otros proyectos realizados en la misma comunidad beneficiada.
     */
    public function getProyectosMismaComunidad(string $comunidad, int $idExcluir, int $limit = 4): array {
        $db = Connection::getInstance();
        $sql = "SELECT r.id, r.titulo, r.anio_publicacion, dp.resumen, dp.comunidad_beneficiada, li.nombre AS linea_nombre
                FROM public.recursos r
                JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso
                LEFT JOIN public.recurso_clasificaciones rc ON r.id = rc.id_recurso
                LEFT JOIN public.lineas_investigacion li ON rc.id_linea_investigacion = li.id
                WHERE r.id_tipo_recurso = 1 
                  AND r.id != ? 
                  AND dp.comunidad_beneficiada IS NOT NULL 
                  AND TRIM(LOWER(dp.comunidad_beneficiada)) = TRIM(LOWER(?))
                ORDER BY r.id DESC LIMIT ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$idExcluir, $comunidad, $limit]);
        return $this->cleanArray($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Retorna el conteo total de PSTs bajo los filtros dados.
     */
    public function getPSTDocumentosCount(array $filtros = []): int {
        $db = Connection::getInstance();
        $sql = "SELECT COUNT(DISTINCT r.id) as total
                FROM public.recursos r
                LEFT JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso
                LEFT JOIN public.trayectos t ON dp.id_trayecto = t.id
                LEFT JOIN public.recurso_clasificaciones rc ON r.id = rc.id_recurso
                LEFT JOIN public.lineas_investigacion li ON rc.id_linea_investigacion = li.id
                WHERE r.id_tipo_recurso = 1";
        
        $params = [];
        if (isset($filtros['activo']) && $filtros['activo'] !== 'todos') {
            $sql .= " AND COALESCE(dp.activo, true) = ?";
            $params[] = ($filtros['activo'] === true || $filtros['activo'] === '1' || $filtros['activo'] === 1) ? true : false;
        }
        if (!empty($filtros['carrera_id'])) {
            $sql .= " AND (COALESCE(dp.id_carrera, li.id_carrera) = ? OR EXISTS (SELECT 1 FROM public.proyecto_carreras_vinculadas pcv WHERE pcv.id_recurso = r.id AND pcv.id_carrera = ?))";
            $params[] = (int)$filtros['carrera_id'];
            $params[] = (int)$filtros['carrera_id'];
        }
        if (!empty($filtros['linea_id'])) {
            $sql .= " AND rc.id_linea_investigacion = ?";
            $params[] = (int)$filtros['linea_id'];
        }
        if (!empty($filtros['dimension_id'])) {
            $sql .= " AND rc.id_dimension_operativa = ?";
            $params[] = (int)$filtros['dimension_id'];
        }
        if (!empty($filtros['nivel_academico'])) {
            $nivelMap = [
                'Especialización' => 'Especializacion',
                'Maestría'        => 'Maestria'
            ];
            $valNivel = trim($filtros['nivel_academico']);
            $valNivel = $nivelMap[$valNivel] ?? $valNivel;
            $sql .= " AND dp.nivel_academico = ?";
            $params[] = $valNivel;
        }
        if (!empty($filtros['trayecto'])) {
            $trayectoParam = trim((string)$filtros['trayecto']);
            if (is_numeric($trayectoParam)) {
                $sql .= " AND (dp.id_trayecto = ? OR t.numero = ?)";
                $params[] = (int)$trayectoParam;
                $params[] = (int)$trayectoParam;
            } else {
                $sql .= " AND t.nombre = ?";
                $params[] = $trayectoParam;
            }
        }
        if (!empty($filtros['comunidad'])) {
            $sql .= " AND dp.comunidad_beneficiada ILIKE ?";
            $params[] = '%' . trim($filtros['comunidad']) . '%';
        }
        if (!empty($filtros['anio'])) {
            $sql .= " AND r.anio_publicacion = ?";
            $params[] = (int)$filtros['anio'];
        }
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? (int)$res['total'] : 0;
    }

    /**
     * Obtiene el resumen de métricas globales (Total catálogo, Activos visibles, Con adjunto PDF) en una sola consulta.
     */
    public function getPSTStatsResumen(): array {
        $db = Connection::getInstance();
        $sql = "SELECT 
                    COUNT(DISTINCT r.id) AS total_catalog,
                    COUNT(DISTINCT CASE WHEN COALESCE(dp.activo, true) = true THEN r.id END) AS total_activos,
                    COUNT(DISTINCT CASE WHEN r.archivo_pdf IS NOT NULL AND TRIM(r.archivo_pdf) != '' THEN r.id END) AS total_con_pdf
                FROM public.recursos r
                LEFT JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso
                WHERE r.id_tipo_recurso = 1";
        $stmt = $db->query($sql);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return [
            'total_catalog' => $res ? (int)$res['total_catalog'] : 0,
            'total_activos' => $res ? (int)$res['total_activos'] : 0,
            'total_con_pdf' => $res ? (int)$res['total_con_pdf'] : 0,
        ];
    }

    /**
     * Búsqueda estándar (Modo A) filtrando por título, palabras clave, resumen y paginación.
     */
    public function buscarStandard(string $query, array $filtrosExtra = [], int $limit = 5, int $offset = 0): array {
        $db = Connection::getInstance();
        
        $sql = "SELECT r.id, r.titulo, r.anio_publicacion, r.archivo_pdf,
                       tr.nombre AS tipo_recurso_nombre,
                       dp.resumen AS proyecto_resumen, dp.palabras_clave AS proyecto_palabras,
                       COALESCE(dp.vistas, 0) AS vistas,
                       da.resumen AS articulo_resumen,
                       (SELECT STRING_AGG(a.nombre_completo, ', ') 
                        FROM public.recurso_autores ra 
                        JOIN public.autores a ON ra.id_autor = a.id 
                        WHERE ra.id_recurso = r.id) AS autores_nombres
                FROM public.recursos r
                JOIN public.tipo_recurso tr ON r.id_tipo_recurso = tr.id
                LEFT JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso
                LEFT JOIN public.detalles_articulos da ON r.id = da.id_recurso
                WHERE 1=1";
                
        $params = [];
        
        if (!empty($query)) {
            $sql .= " AND (r.titulo ILIKE ? OR dp.resumen ILIKE ? OR da.resumen ILIKE ? OR dp.palabras_clave ILIKE ?)";
            $searchTerm = "%$query%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (!empty($filtrosExtra['tipo_recurso'])) {
            $sql .= " AND r.id_tipo_recurso = ?";
            $params[] = (int)$filtrosExtra['tipo_recurso'];
        }
        
        if (!empty($filtrosExtra['anio'])) {
            $sql .= " AND r.anio_publicacion = ?";
            $params[] = (int)$filtrosExtra['anio'];
        }
        
        $sql .= " ORDER BY r.id DESC LIMIT ? OFFSET ?";
        
        $execParams = $params;
        $execParams[] = $limit;
        $execParams[] = $offset;
        
        $stmt = $db->prepare($sql);
        $stmt->execute($execParams);
        return $this->cleanArray($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Retorna el conteo total de coincidencias de búsqueda.
     */
    public function buscarStandardCount(string $query, array $filtrosExtra = []): int {
        $db = Connection::getInstance();
        
        $sql = "SELECT COUNT(DISTINCT r.id) as total
                FROM public.recursos r
                LEFT JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso
                LEFT JOIN public.detalles_articulos da ON r.id = da.id_recurso
                WHERE 1=1";
                
        $params = [];
        
        if (!empty($query)) {
            $sql .= " AND (r.titulo ILIKE ? OR dp.resumen ILIKE ? OR da.resumen ILIKE ? OR dp.palabras_clave ILIKE ?)";
            $searchTerm = "%$query%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (!empty($filtrosExtra['tipo_recurso'])) {
            $sql .= " AND r.id_tipo_recurso = ?";
            $params[] = (int)$filtrosExtra['tipo_recurso'];
        }
        
        if (!empty($filtrosExtra['anio'])) {
            $sql .= " AND r.anio_publicacion = ?";
            $params[] = (int)$filtrosExtra['anio'];
        }
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? (int)$res['total'] : 0;
    }

    private static array $cacheLineas = [];
    private static array $cacheDimensiones = [];

    /**
     * Normaliza cadenas eliminando tildes, caracteres especiales y espacios sobrantes para comparación lógica de similitud.
     */
    public function normalizarString(string $str): string {
        $str = $this->cleanCP850($str);
        $str = mb_strtolower(trim($str));
        $unwanted = [
            'á'=>'a', 'é'=>'e', 'í'=>'i', 'ó'=>'o', 'ú'=>'u', 'ü'=>'u', 'ñ'=>'n',
            'Á'=>'a', 'É'=>'e', 'Í'=>'i', 'Ó'=>'o', 'Ú'=>'u', 'Ü'=>'u', 'Ñ'=>'n'
        ];
        $str = strtr($str, $unwanted);
        return preg_replace('/[^a-z0-9\s]/', '', $str);
    }

    public function getCarreras(): array {
        $qb = new QueryBuilder();
        return $this->cleanArray($qb->tabla('carreras')->orderBy('nombre', 'ASC')->get());
    }

    public function getLineasInvestigacion(?int $carreraId = null): array {
        $qb = new QueryBuilder();
        $qb->tabla('lineas_investigacion');
        if ($carreraId !== null && $carreraId > 0) {
            $qb->where('id_carrera', '=', $carreraId);
        }
        return $this->cleanArray($qb->orderBy('nombre', 'ASC')->get());
    }

    public function getDimensionesOperativas(): array {
        if (!empty(self::$cacheDimensiones)) {
            return self::$cacheDimensiones;
        }
        if (isset($_SESSION['pst_cache_dimensiones']) && is_array($_SESSION['pst_cache_dimensiones'])) {
            self::$cacheDimensiones = $_SESSION['pst_cache_dimensiones'];
            return self::$cacheDimensiones;
        }
        $qb = new QueryBuilder();
        $res = $this->cleanArray($qb->tabla('dimensiones_operativas')->orderBy('nombre', 'ASC')->get());
        self::$cacheDimensiones = $res;
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['pst_cache_dimensiones'] = $res;
        }
        return $res;
    }

    public function getTiposRecurso(): array {
        $qb = new QueryBuilder();
        return $this->cleanArray($qb->tabla('tipo_recurso')->orderBy('nombre', 'ASC')->get());
    }

    public function getNivelesAcademicos(bool $soloActivos = true): array {
        $db = Connection::getInstance();
        try {
            $sql = "SELECT codigo FROM public.niveles_academicos";
            if ($soloActivos) {
                $sql .= " WHERE activo = true";
            }
            $sql .= " ORDER BY orden ASC, id ASC";
            $stmt = $db->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if (!empty($rows)) {
                return $rows;
            }
        } catch (\Throwable $e) {
            error_log("Error obteniendo niveles_academicos: " . $e->getMessage());
        }

        return ['Pregrado', 'Especializacion', 'Maestria', 'Doctorado'];
    }

    public function getNivelesAcademicosDetallados(): array {
        $db = Connection::getInstance();
        try {
            $sql = "SELECT n.id, n.codigo, n.nombre, n.descripcion, n.requiere_trayecto, n.orden, n.activo,
                           COUNT(dp.id_recurso) AS total_proyectos
                    FROM public.niveles_academicos n
                    LEFT JOIN public.detalles_proyectos dp ON dp.nivel_academico = n.codigo
                    GROUP BY n.id, n.codigo, n.nombre, n.descripcion, n.requiere_trayecto, n.orden, n.activo
                    ORDER BY n.orden ASC, n.id ASC";
            $stmt = $db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            error_log("Error obteniendo niveles académicos detallados: " . $e->getMessage());
            return [];
        }
    }

    public function getNivelAcademicoMap(): array {
        $db = Connection::getInstance();
        try {
            $sql = "SELECT codigo, nombre FROM public.niveles_academicos WHERE activo = true ORDER BY orden ASC, id ASC";
            $stmt = $db->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            if (!empty($rows)) {
                return $rows;
            }
        } catch (\Throwable $e) {
            error_log("Error obteniendo mapa de niveles: " . $e->getMessage());
        }

        return [
            'Pregrado' => 'Pregrado',
            'Especializacion' => 'Especialización',
            'Maestria' => 'Maestría',
            'Doctorado' => 'Doctorado'
        ];
    }

    public function getNivelesConTrayecto(): array {
        $db = Connection::getInstance();
        try {
            $sql = "SELECT codigo FROM public.niveles_academicos WHERE requiere_trayecto = true AND activo = true";
            $stmt = $db->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if (!empty($rows)) {
                return $rows;
            }
        } catch (\Throwable $e) {
            error_log("Error obteniendo niveles con trayecto: " . $e->getMessage());
        }

        return ['Pregrado'];
    }

    public function crearNivelAcademico(array $datos): array {
        $db = Connection::getInstance();
        $nombre = trim($datos['nombre'] ?? '');
        $codigo = trim($datos['codigo'] ?? '');
        $descripcion = trim($datos['descripcion'] ?? '');
        $requiereTrayecto = !empty($datos['requiere_trayecto']) ? true : false;
        $orden = isset($datos['orden']) ? (int)$datos['orden'] : 0;
        $activo = isset($datos['activo']) ? (bool)$datos['activo'] : true;

        if (empty($nombre)) {
            return ['success' => false, 'message' => 'El nombre del nivel académico es obligatorio.'];
        }
        if (empty($codigo)) {
            $codigo = preg_replace('/[^a-zA-Z0-9]/', '', ucwords($nombre));
        }
        $codigo = preg_replace('/[^a-zA-Z0-9_]/', '', $codigo);
        if (empty($codigo)) {
            return ['success' => false, 'message' => 'El código identificador no es válido.'];
        }

        try {
            $stmtCheck = $db->prepare("SELECT id FROM public.niveles_academicos WHERE LOWER(codigo) = LOWER(?)");
            $stmtCheck->execute([$codigo]);
            if ($stmtCheck->fetch()) {
                return ['success' => false, 'message' => "Ya existe un nivel académico con el código '{$codigo}'."];
            }

            $stmt = $db->prepare("INSERT INTO public.niveles_academicos (codigo, nombre, descripcion, requiere_trayecto, orden, activo, created_at, updated_at) 
                                  VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW()) RETURNING id");
            $stmt->execute([$codigo, $nombre, $descripcion, $requiereTrayecto ? 'true' : 'false', $orden, $activo ? 'true' : 'false']);
            $nuevoId = (int)$stmt->fetchColumn();
            return ['success' => true, 'id' => $nuevoId, 'message' => "Nivel académico '{$nombre}' creado correctamente."];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Error al crear el nivel académico: ' . $e->getMessage()];
        }
    }

    public function actualizarNivelAcademico(int $id, array $datos): array {
        $db = Connection::getInstance();
        $nombre = trim($datos['nombre'] ?? '');
        $codigoNuevo = trim($datos['codigo'] ?? '');
        $descripcion = trim($datos['descripcion'] ?? '');
        $requiereTrayecto = !empty($datos['requiere_trayecto']) ? true : false;
        $orden = isset($datos['orden']) ? (int)$datos['orden'] : 0;
        $activo = isset($datos['activo']) ? (bool)$datos['activo'] : true;

        if (empty($nombre)) {
            return ['success' => false, 'message' => 'El nombre del nivel académico es obligatorio.'];
        }
        if (empty($codigoNuevo)) {
            $codigoNuevo = preg_replace('/[^a-zA-Z0-9]/', '', ucwords($nombre));
        }
        $codigoNuevo = preg_replace('/[^a-zA-Z0-9_]/', '', $codigoNuevo);

        try {
            $stmtCur = $db->prepare("SELECT id, codigo FROM public.niveles_academicos WHERE id = ?");
            $stmtCur->execute([$id]);
            $current = $stmtCur->fetch(PDO::FETCH_ASSOC);
            if (!$current) {
                return ['success' => false, 'message' => 'Nivel académico no encontrado.'];
            }

            $stmtCheck = $db->prepare("SELECT id FROM public.niveles_academicos WHERE LOWER(codigo) = LOWER(?) AND id != ?");
            $stmtCheck->execute([$codigoNuevo, $id]);
            if ($stmtCheck->fetch()) {
                return ['success' => false, 'message' => "El código '{$codigoNuevo}' ya está asignado a otro nivel académico."];
            }

            $db->beginTransaction();

            $codigoAntiguo = $current['codigo'];

            $stmt = $db->prepare("UPDATE public.niveles_academicos 
                                  SET codigo = ?, nombre = ?, descripcion = ?, requiere_trayecto = ?, orden = ?, activo = ?, updated_at = NOW() 
                                  WHERE id = ?");
            $stmt->execute([$codigoNuevo, $nombre, $descripcion, $requiereTrayecto ? 'true' : 'false', $orden, $activo ? 'true' : 'false', $id]);

            if ($codigoAntiguo !== $codigoNuevo) {
                $stmtCascada = $db->prepare("UPDATE public.detalles_proyectos SET nivel_academico = ? WHERE nivel_academico = ?");
                $stmtCascada->execute([$codigoNuevo, $codigoAntiguo]);
            }

            $db->commit();
            return ['success' => true, 'message' => "Nivel académico '{$nombre}' actualizado correctamente."];
        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            return ['success' => false, 'message' => 'Error al actualizar el nivel académico: ' . $e->getMessage()];
        }
    }

    public function eliminarNivelAcademico(int $id): array {
        $db = Connection::getInstance();
        try {
            $stmtCur = $db->prepare("SELECT id, codigo, nombre FROM public.niveles_academicos WHERE id = ?");
            $stmtCur->execute([$id]);
            $current = $stmtCur->fetch(PDO::FETCH_ASSOC);
            if (!$current) {
                return ['success' => false, 'message' => 'Nivel académico no encontrado.'];
            }

            $stmtCount = $db->prepare("SELECT COUNT(*) FROM public.detalles_proyectos WHERE nivel_academico = ?");
            $stmtCount->execute([$current['codigo']]);
            $totalProyectos = (int)$stmtCount->fetchColumn();

            if ($totalProyectos > 0) {
                return [
                    'success' => false, 
                    'message' => "No se puede eliminar el nivel '{$current['nombre']}' porque tiene {$totalProyectos} proyecto(s) asociado(s). Reasigne los proyectos a otro nivel académico o desactive este nivel."
                ];
            }

            $stmtDel = $db->prepare("DELETE FROM public.niveles_academicos WHERE id = ?");
            $stmtDel->execute([$id]);

            return ['success' => true, 'message' => "Nivel académico '{$current['nombre']}' eliminado correctamente."];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Error al eliminar el nivel académico: ' . $e->getMessage()];
        }
    }

    public function toggleNivelAcademico(int $id): array {
        $db = Connection::getInstance();
        try {
            $stmt = $db->prepare("UPDATE public.niveles_academicos SET activo = NOT activo, updated_at = NOW() WHERE id = ? RETURNING activo, nombre");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $estado = !empty($row['activo']) ? 'activado' : 'desactivado';
                return ['success' => true, 'message' => "Nivel académico '{$row['nombre']}' {$estado} correctamente."];
            }
            return ['success' => false, 'message' => 'Nivel académico no encontrado.'];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Error al cambiar estado: ' . $e->getMessage()];
        }
    }

    public function getTrayectos(?int $carreraId = null): array {
        $db = Connection::getInstance();
        $sql = "SELECT t.id, t.nombre, t.numero, t.id_carrera, c.nombre AS carrera_nombre 
                FROM public.trayectos t
                JOIN public.carreras c ON t.id_carrera = c.id
                WHERE t.activo = true";
        $params = [];
        if ($carreraId !== null && $carreraId > 0) {
            $sql .= " AND t.id_carrera = ?";
            $params[] = $carreraId;
        }
        $sql .= " ORDER BY t.id_carrera ASC, t.numero ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $trayectos = [];
        foreach ($rows as $row) {
            $trayectos[] = [
                'id'             => (int)$row['id'],
                'nombre'         => $this->cleanCP850($row['nombre']),
                'numero'         => (int)$row['numero'],
                'id_carrera'     => (int)$row['id_carrera'],
                'carrera_nombre' => $this->cleanCP850($row['carrera_nombre']),
            ];
        }
        return $trayectos;
    }

    public function getTrayectosNombres(?int $carreraId = null): array {
        $trayectos = $this->getTrayectos($carreraId);
        $nombres = [];
        foreach ($trayectos as $t) {
            if (!in_array($t['nombre'], $nombres)) {
                $nombres[] = $t['nombre'];
            }
        }
        return !empty($nombres) ? $nombres : ['Trayecto I', 'Trayecto II', 'Trayecto III', 'Trayecto IV'];
    }

    public function resolverIdTrayecto(int $idCarrera, $trayectoInput): ?int {
        if (empty($trayectoInput)) return null;
        $db = Connection::getInstance();

        if (is_numeric($trayectoInput)) {
            $stmt = $db->prepare("SELECT id FROM public.trayectos WHERE (id = ? OR (id_carrera = ? AND numero = ?)) AND activo = true LIMIT 1");
            $stmt->execute([(int)$trayectoInput, $idCarrera, (int)$trayectoInput]);
            $found = $stmt->fetchColumn();
            if ($found) return (int)$found;
        }
        
        $clean = strtoupper(trim((string)$trayectoInput));
        $num = null;
        if (str_contains($clean, 'IV') || $clean === '4') $num = 4;
        elseif (str_contains($clean, 'III') || $clean === '3') $num = 3;
        elseif (str_contains($clean, 'II') || $clean === '2') $num = 2;
        elseif (str_contains($clean, 'I') || $clean === '1') $num = 1;
        
        if ($num !== null) {
            $stmt = $db->prepare("SELECT id FROM public.trayectos WHERE id_carrera = ? AND numero = ? AND activo = true LIMIT 1");
            $stmt->execute([$idCarrera, $num]);
            $found = $stmt->fetchColumn();
            if ($found) return (int)$found;
        }
        return null;
    }

    public function resolverNombreTrayecto(int $idTrayecto): ?string {
        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT nombre FROM public.trayectos WHERE id = ?");
        $stmt->execute([$idTrayecto]);
        $val = $stmt->fetchColumn();
        return $val ? (string)$val : null;
    }

    public function getPSTCountByLinea(?int $carreraId = null): array {
        $db = Connection::getInstance();
        $sql = "SELECT li.id, li.nombre, li.descripcion, COUNT(DISTINCT r.id) AS total
                FROM public.lineas_investigacion li
                LEFT JOIN public.recurso_clasificaciones rc ON li.id = rc.id_linea_investigacion
                LEFT JOIN public.recursos r ON rc.id_recurso = r.id AND r.id_tipo_recurso = 1
                LEFT JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso";
        $params = [];
        if ($carreraId !== null && $carreraId > 0) {
            $sql .= " WHERE (li.id_carrera = ? OR COALESCE(dp.id_carrera, li.id_carrera) = ?)";
            $params[] = $carreraId;
            $params[] = $carreraId;
        }
        $sql .= " GROUP BY li.id, li.nombre, li.descripcion
                  ORDER BY li.id ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $res = [];
        foreach ($rows as $r) {
            $res[] = [
                'id'          => (int)$r['id'],
                'nombre'      => $this->cleanCP850($r['nombre']),
                'descripcion' => $this->cleanCP850($r['descripcion'] ?? ''),
                'total'       => (int)$r['total']
            ];
        }
        return $res;
    }

    public function getPSTCountByTrayecto(?int $carreraId = null): array {
        $db = Connection::getInstance();
        $sql = "SELECT t.numero AS trayecto_num, 
                COUNT(DISTINCT r.id) AS total
                FROM public.recursos r
                JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso
                LEFT JOIN public.trayectos t ON dp.id_trayecto = t.id
                LEFT JOIN public.recurso_clasificaciones rc ON r.id = rc.id_recurso
                LEFT JOIN public.lineas_investigacion li ON rc.id_linea_investigacion = li.id
                WHERE r.id_tipo_recurso = 1";
        $params = [];
        if ($carreraId !== null && $carreraId > 0) {
            $sql .= " AND COALESCE(dp.id_carrera, li.id_carrera) = ?";
            $params[] = $carreraId;
        }
        $sql .= " GROUP BY trayecto_num ORDER BY trayecto_num ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $res = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
        foreach ($rows as $r) {
            $num = (int)($r['trayecto_num'] ?? 0);
            if (isset($res[$num])) {
                $res[$num] = (int)$r['total'];
            }
        }
        return $res;
    }

    /**
     * Obtiene un único PST por su ID.
     */
    public function getPSTDocumentoById(int $id): ?array {
        $db = Connection::getInstance();
        
        $sql = "SELECT r.id, r.titulo, r.anio_publicacion, r.archivo_pdf,
                       dp.resumen, dp.obj_general, dp.palabras_clave, dp.comunidad_beneficiada, dp.nivel_academico, 
                       dp.id_trayecto, t.nombre AS trayecto_nombre, t.numero AS trayecto_numero, 
                       t.nombre AS trayecto, 
                       dp.url_repositorio, dp.fecha_defensa, COALESCE(dp.activo, true) AS activo,
                       COALESCE(dp.vistas, 0) AS vistas,
                       li.nombre AS linea_nombre, 
                       li.id AS linea_id,
                       dims.nombre AS dimension_nombre,
                       dims.id AS dimension_id,
                       c.nombre AS carrera_nombre,
                       c.id AS carrera_id,
                       (SELECT STRING_AGG(c_vinc.nombre, ', ') 
                        FROM public.proyecto_carreras_vinculadas pcv 
                        JOIN public.carreras c_vinc ON pcv.id_carrera = c_vinc.id 
                        WHERE pcv.id_recurso = r.id) AS carreras_vinculadas_nombres,
                       (SELECT STRING_AGG(a.nombre_completo, ', ') 
                        FROM public.recurso_autores ra 
                        JOIN public.autores a ON ra.id_autor = a.id 
                        WHERE ra.id_recurso = r.id) AS autores_nombres,
                       (SELECT STRING_AGG(t.nombre_completo || ' - ' || 
                            CASE pt.tipo_tutor_id 
                                WHEN 3 THEN 'Tutor Académico'
                                WHEN 2 THEN 'Tutor Institucional'
                                WHEN 4 THEN 'Tutor Comunitario'
                                ELSE COALESCE(tt.nombre, 'Tutor')
                            END, ' • ') 
                        FROM public.proyecto_tutores pt 
                        JOIN public.tutores t ON pt.id_tutor = t.id 
                        LEFT JOIN public.tipo_tutor tt ON pt.tipo_tutor_id = tt.id
                        WHERE pt.id_recurso = r.id) AS tutores_nombres
                FROM public.recursos r
                LEFT JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso
                LEFT JOIN public.trayectos t ON dp.id_trayecto = t.id
                LEFT JOIN public.recurso_clasificaciones rc ON r.id = rc.id_recurso
                LEFT JOIN public.lineas_investigacion li ON rc.id_linea_investigacion = li.id
                LEFT JOIN public.dimensiones_operativas dims ON rc.id_dimension_operativa = dims.id
                LEFT JOIN public.carreras c ON COALESCE(dp.id_carrera, li.id_carrera) = c.id
                WHERE r.id = ? AND r.id_tipo_recurso = 1";
                
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            $row = $this->cleanRow($res);
            $row['tutores_lista'] = $this->getTutoresByRecurso($id);
            $row['carreras_vinculadas_lista'] = $this->getCarrerasVinculadasByRecurso($id);
            $row['carreras_vinculadas_ids'] = array_column($row['carreras_vinculadas_lista'], 'id');
            return $row;
        }
        return null;
    }

    /**
     * Incrementa en 1 el contador de visualizaciones de un proyecto PST.
     */
    public function incrementarVistasPST(int $idRecurso): bool {
        $db = Connection::getInstance();
        $stmt = $db->prepare("UPDATE public.detalles_proyectos SET vistas = COALESCE(vistas, 0) + 1 WHERE id_recurso = ?");
        return $stmt->execute([$idRecurso]);
    }

    /**
     * Verifica si ya existe un proyecto con el mismo título en el repositorio.
     */
    public function existePSTPorTitulo(string $titulo, ?int $idExcluir = null): bool {
        $db = Connection::getInstance();
        $cleanTitulo = mb_strtolower(trim($titulo), 'UTF-8');
        
        if ($idExcluir) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM public.recursos WHERE LOWER(TRIM(titulo)) = ? AND id != ?");
            $stmt->execute([$cleanTitulo, $idExcluir]);
        } else {
            $stmt = $db->prepare("SELECT COUNT(*) FROM public.recursos WHERE LOWER(TRIM(titulo)) = ?");
            $stmt->execute([$cleanTitulo]);
        }
        return ($stmt->fetchColumn() > 0);
    }

    /**
     * Inserta un nuevo registro de PST en la base de datos de manera manual y transaccional.
     */
    public function crearPST(array $datos): int {
        if ($this->existePSTPorTitulo($datos['titulo'])) {
            throw new Exception("Ya existe una investigación registrada en el repositorio con el título: '" . $datos['titulo'] . "'.");
        }

        $db = Connection::getInstance();
        
        try {
            $db->beginTransaction();
            
            // Ruta de archivo de almacenamiento real
            $pdfPath = !empty($datos['archivo_pdf']) ? trim($datos['archivo_pdf']) : null;
            
            // 1. Insertar el recurso base (id_tipo_recurso = 1 indica PST)
            $stmt = $db->prepare("INSERT INTO public.recursos (titulo, id_tipo_recurso, anio_publicacion, archivo_pdf) 
                                  VALUES (?, 1, ?, ?) RETURNING id");
            $stmt->execute([
                $datos['titulo'],
                (int)$datos['anio_publicacion'],
                $pdfPath
            ]);
            $recursoId = $stmt->fetchColumn();
            
            if (!$recursoId) {
                throw new Exception("No se pudo generar el recurso principal.");
            }
            
            // 2. Insertar los detalles específicos del proyecto (INSERTAR PRIMERO para satisfacer Fkey de tutores!)
            //    NOTA: La columna 'vector_semantico' se omite intencionalmente → queda NULL.
            //    El worker asíncrono (scripts/generar_embeddings.php) la procesará en segundo plano.
            $nivelAcademicoRaw = !empty($datos['nivel_academico']) ? trim($datos['nivel_academico']) : 'Pregrado';
            $nivelMap = [
                'Especialización' => 'Especializacion',
                'Maestría'        => 'Maestria'
            ];
            $nivelAcademico = $nivelMap[$nivelAcademicoRaw] ?? $nivelAcademicoRaw;
            $idCarrera = !empty($datos['id_carrera']) ? (int)$datos['id_carrera'] : 1;

            $idTrayecto = !empty($datos['id_trayecto']) ? (int)$datos['id_trayecto'] : null;
            $nivelesConTrayecto = $this->getNivelesConTrayecto();
            if (!$idTrayecto && !empty($datos['trayecto']) && in_array($nivelAcademicoRaw, $nivelesConTrayecto)) {
                $idTrayecto = $this->resolverIdTrayecto($idCarrera, $datos['trayecto']);
            }
            if (!$idTrayecto && in_array($nivelAcademicoRaw, $nivelesConTrayecto)) {
                $idTrayecto = $this->resolverIdTrayecto($idCarrera, 'Trayecto I');
            }

            $stmt = $db->prepare("INSERT INTO public.detalles_proyectos (id_recurso, fecha_defensa, nivel_academico, id_trayecto, url_repositorio, resumen, obj_general, id_carrera, comunidad_beneficiada, palabras_clave) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $recursoId,
                !empty($datos['fecha_defensa']) ? $datos['fecha_defensa'] : date('Y-m-d'),
                $nivelAcademico,
                $idTrayecto,
                !empty($datos['url_repositorio']) ? trim($datos['url_repositorio']) : null,
                $datos['resumen'] ?? null,
                $datos['obj_general'] ?? null,
                $idCarrera,
                $datos['comunidad_beneficiada'] ?? null,
                $datos['palabras_clave'] ?? null
            ]);
            
            // 3. Insertar autores múltiples con deduplicación avanzada (Cédula -> Nombre exacto -> Soundex / Levenshtein)
            if (!empty($datos['autores']) && is_array($datos['autores'])) {
                foreach ($datos['autores'] as $autor) {
                    $nom = !empty($autor['nombre']) ? trim($autor['nombre']) : (!empty($autor['nombre_completo']) ? trim($autor['nombre_completo']) : '');
                    $ced = !empty($autor['cedula']) ? trim($autor['cedula']) : null;
                    if ($nom !== '') {
                        $autorId = null;
                        if ($ced) {
                            $cleanCed = trim($ced);
                            $soloDigitos = preg_replace('/\D/', '', $cleanCed);
                            $stmt = $db->prepare("SELECT id FROM public.autores WHERE LOWER(TRIM(cedula)) IN (LOWER(?), LOWER(?), LOWER(?), LOWER(?)) LIMIT 1");
                            $stmt->execute([$cleanCed, $soloDigitos, 'V-' . $soloDigitos, 'E-' . $soloDigitos]);
                            $autorId = $stmt->fetchColumn();
                        }
                        if (!$autorId) {
                            $stmt = $db->prepare("SELECT id FROM public.autores WHERE LOWER(TRIM(nombre_completo)) = LOWER(?)");
                            $stmt->execute([$nom]);
                            $autorId = $stmt->fetchColumn();
                        }
                        // Búsqueda difusa por normalización y similitud de cadenas si no hubo coincidencia exacta
                        if (!$autorId) {
                            $normNom = $this->normalizarString($nom);
                            $stmt = $db->query("SELECT id, nombre_completo FROM public.autores");
                            $todosAutores = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($todosAutores as $candAut) {
                                $normCand = $this->normalizarString($candAut['nombre_completo']);
                                if ($normNom === $normCand || (levenshtein($normNom, $normCand) <= 2 && soundex($normNom) === soundex($normCand))) {
                                    $autorId = (int)$candAut['id'];
                                    break;
                                }
                            }
                        }
                        if (!$autorId) {
                            $stmt = $db->prepare("INSERT INTO public.autores (nombre_completo, cedula) VALUES (?, ?) RETURNING id");
                            $stmt->execute([$nom, $ced]);
                            $autorId = $stmt->fetchColumn();
                        }
                        if ($autorId) {
                            $stmt = $db->prepare("INSERT INTO public.recurso_autores (id_recurso, id_autor) VALUES (?, ?) ON CONFLICT (id_recurso, id_autor) DO NOTHING");
                            $stmt->execute([$recursoId, $autorId]);
                        }
                    }
                }
            }
            
            // 4. Insertar tutores múltiples (no requiere obligatoriamente cédula si viene el nombre)
            $tutoresTipos = [
                'academico'     => 3,
                'institucional' => 2,
                'comunitario'   => 4
            ];
            
            foreach ($tutoresTipos as $key => $tipoId) {
                $cedField = "tutor_{$key}_cedula";
                $nomField = "tutor_{$key}_nombre";
                
                if (!empty($datos[$nomField])) {
                    $nombre = trim($datos[$nomField]);
                    $cedula = !empty($datos[$cedField]) ? trim($datos[$cedField]) : null;
                    $tutorId = null;
                    
                    if ($cedula) {
                        $cleanCed = trim($cedula);
                        $soloDigitos = preg_replace('/\D/', '', $cleanCed);
                        $stmt = $db->prepare("SELECT id FROM public.tutores WHERE LOWER(TRIM(cedula)) IN (LOWER(?), LOWER(?), LOWER(?), LOWER(?)) LIMIT 1");
                        $stmt->execute([$cleanCed, $soloDigitos, 'V-' . $soloDigitos, 'E-' . $soloDigitos]);
                        $tutorId = $stmt->fetchColumn();
                    }
                    if (!$tutorId) {
                        $stmt = $db->prepare("SELECT id FROM public.tutores WHERE LOWER(TRIM(nombre_completo)) = LOWER(?)");
                        $stmt->execute([$nombre]);
                        $tutorId = $stmt->fetchColumn();
                    }
                    if (!$tutorId) {
                        $normNom = $this->normalizarString($nombre);
                        $stmt = $db->query("SELECT id, nombre_completo FROM public.tutores");
                        $todosTutores = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($todosTutores as $candTut) {
                            $normCand = $this->normalizarString($candTut['nombre_completo']);
                            if ($normNom === $normCand || (levenshtein($normNom, $normCand) <= 2 && soundex($normNom) === soundex($normCand))) {
                                $tutorId = (int)$candTut['id'];
                                break;
                            }
                        }
                    }
                    if (!$tutorId) {
                        if (!empty($cedula)) {
                            $stmt = $db->prepare("INSERT INTO public.tutores (nombre_completo, cedula) VALUES (?, ?) ON CONFLICT (cedula) DO UPDATE SET nombre_completo = EXCLUDED.nombre_completo RETURNING id");
                            $stmt->execute([$nombre, $cedula]);
                        } else {
                            $stmt = $db->prepare("INSERT INTO public.tutores (nombre_completo, cedula) VALUES (?, NULL) RETURNING id");
                            $stmt->execute([$nombre]);
                        }
                        $tutorId = $stmt->fetchColumn();
                    }
                    if ($tutorId) {
                        $stmt = $db->prepare("INSERT INTO public.proyecto_tutores (id_recurso, id_tutor, tipo_tutor_id) VALUES (?, ?, ?) ON CONFLICT (id_recurso, id_tutor) DO UPDATE SET tipo_tutor_id = EXCLUDED.tipo_tutor_id");
                        $stmt->execute([$recursoId, $tutorId, $tipoId]);
                    }
                }
            }
            
            // 5. Insertar clasificación modular
            if (!empty($datos['linea_id'])) {
                $stmt = $db->prepare("INSERT INTO public.recurso_clasificaciones (id_recurso, id_linea_investigacion, id_dimension_operativa) 
                                      VALUES (?, ?, ?) ON CONFLICT (id_recurso, id_linea_investigacion) DO UPDATE SET id_dimension_operativa = EXCLUDED.id_dimension_operativa");
                $stmt->execute([
                    $recursoId,
                    (int)$datos['linea_id'],
                    !empty($datos['dimension_id']) ? (int)$datos['dimension_id'] : null
                ]);
            }

            // 6. Insertar carreras vinculadas secundarias (tags de vinculación intercarrera)
            if (!empty($datos['carreras_vinculadas']) && is_array($datos['carreras_vinculadas'])) {
                $stmtVinc = $db->prepare("INSERT INTO public.proyecto_carreras_vinculadas (id_recurso, id_carrera) 
                                          VALUES (?, ?) ON CONFLICT DO NOTHING");
                foreach ($datos['carreras_vinculadas'] as $carrId) {
                    $carrId = (int)$carrId;
                    if ($carrId > 0 && $carrId !== $idCarrera) {
                        $stmtVinc->execute([$recursoId, $carrId]);
                    }
                }
            }
            
            $db->commit();
            return (int)$recursoId;
            
        } catch (Exception $e) {
            $db->rollBack();
            error_log("Error al crear PST: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Actualiza un PST en la base de datos de forma transaccional.
     */
    public function editarPST(int $id, array $datos): bool {
        if ($this->existePSTPorTitulo($datos['titulo'], $id)) {
            throw new Exception("Ya existe otro proyecto registrado en el repositorio con el título: '" . $datos['titulo'] . "'.");
        }

        $db = Connection::getInstance();
        try {
            $db->beginTransaction();

            // 1. Actualizar el recurso base
            if (!empty($datos['archivo_pdf'])) {
                $stmt = $db->prepare("UPDATE public.recursos SET titulo = ?, anio_publicacion = ?, archivo_pdf = ? WHERE id = ?");
                $stmt->execute([
                    $datos['titulo'],
                    (int)$datos['anio_publicacion'],
                    $datos['archivo_pdf'],
                    $id
                ]);
            } else {
                $stmt = $db->prepare("UPDATE public.recursos SET titulo = ?, anio_publicacion = ? WHERE id = ?");
                $stmt->execute([
                    $datos['titulo'],
                    (int)$datos['anio_publicacion'],
                    $id
                ]);
            }
            
            // 2. Actualizar detalles_proyectos
            $nivelAcademicoRaw = !empty($datos['nivel_academico']) ? trim($datos['nivel_academico']) : 'Pregrado';
            $nivelMap = [
                'Especialización' => 'Especializacion',
                'Maestría'        => 'Maestria'
            ];
            $nivelAcademico = $nivelMap[$nivelAcademicoRaw] ?? $nivelAcademicoRaw;
            $idCarrera = !empty($datos['id_carrera']) ? (int)$datos['id_carrera'] : 1;

            $idTrayecto = !empty($datos['id_trayecto']) ? (int)$datos['id_trayecto'] : null;
            $nivelesConTrayecto = $this->getNivelesConTrayecto();
            if (!$idTrayecto && !empty($datos['trayecto']) && in_array($nivelAcademicoRaw, $nivelesConTrayecto)) {
                $idTrayecto = $this->resolverIdTrayecto($idCarrera, $datos['trayecto']);
            }
            if (!$idTrayecto && in_array($nivelAcademicoRaw, $nivelesConTrayecto)) {
                $idTrayecto = $this->resolverIdTrayecto($idCarrera, 'Trayecto I');
            }

            // Invalidar el vector semántico (NULL) para que el worker lo regenere con el contenido actualizado
            $stmt = $db->prepare("UPDATE public.detalles_proyectos 
                                  SET fecha_defensa = ?, nivel_academico = ?, id_trayecto = ?, url_repositorio = ?, resumen = ?, obj_general = ?, id_carrera = ?, comunidad_beneficiada = ?, palabras_clave = ?,
                                      vector_semantico = NULL
                                  WHERE id_recurso = ?");
            $stmt->execute([
                !empty($datos['fecha_defensa']) ? $datos['fecha_defensa'] : date('Y-m-d'),
                $nivelAcademico,
                $idTrayecto,
                !empty($datos['url_repositorio']) ? trim($datos['url_repositorio']) : null,
                $datos['resumen'] ?? null,
                $datos['obj_general'] ?? null,
                $idCarrera,
                $datos['comunidad_beneficiada'] ?? null,
                $datos['palabras_clave'] ?? null,
                $id
            ]);
            
            // 3. Actualizar autores
            $stmt = $db->prepare("DELETE FROM public.recurso_autores WHERE id_recurso = ?");
            $stmt->execute([$id]);
            
            if (!empty($datos['autores']) && is_array($datos['autores'])) {
                foreach ($datos['autores'] as $autor) {
                    $nom = !empty($autor['nombre']) ? trim($autor['nombre']) : (!empty($autor['nombre_completo']) ? trim($autor['nombre_completo']) : '');
                    $ced = !empty($autor['cedula']) ? trim($autor['cedula']) : null;
                    if ($nom !== '') {
                        $autorId = null;
                        if ($ced) {
                            $stmt = $db->prepare("SELECT id FROM public.autores WHERE cedula = ?");
                            $stmt->execute([$ced]);
                            $autorId = $stmt->fetchColumn();
                        }
                        if (!$autorId) {
                            $stmt = $db->prepare("SELECT id FROM public.autores WHERE LOWER(TRIM(nombre_completo)) = LOWER(?)");
                            $stmt->execute([$nom]);
                            $autorId = $stmt->fetchColumn();
                        }
                        if (!$autorId) {
                            $stmt = $db->prepare("INSERT INTO public.autores (nombre_completo, cedula) VALUES (?, ?) RETURNING id");
                            $stmt->execute([$nom, $ced]);
                            $autorId = $stmt->fetchColumn();
                        }
                        if ($autorId) {
                            $stmt = $db->prepare("INSERT INTO public.recurso_autores (id_recurso, id_autor) VALUES (?, ?) ON CONFLICT (id_recurso, id_autor) DO NOTHING");
                            $stmt->execute([$id, $autorId]);
                        }
                    }
                }
            }
            
            // 4. Actualizar tutores
            $stmt = $db->prepare("DELETE FROM public.proyecto_tutores WHERE id_recurso = ?");
            $stmt->execute([$id]);
            
            $tutoresTipos = [
                'academico'     => 3,
                'institucional' => 2,
                'comunitario'   => 4
            ];
            
            foreach ($tutoresTipos as $key => $tipoId) {
                $cedField = "tutor_{$key}_cedula";
                $nomField = "tutor_{$key}_nombre";
                
                if (!empty($datos[$nomField])) {
                    $nombre = trim($datos[$nomField]);
                    $cedula = !empty($datos[$cedField]) ? trim($datos[$cedField]) : null;
                    $tutorId = null;
                    
                    if ($cedula) {
                        $stmt = $db->prepare("SELECT id FROM public.tutores WHERE cedula = ?");
                        $stmt->execute([$cedula]);
                        $tutorId = $stmt->fetchColumn();
                    }
                    if (!$tutorId) {
                        $stmt = $db->prepare("SELECT id FROM public.tutores WHERE LOWER(TRIM(nombre_completo)) = LOWER(?)");
                        $stmt->execute([$nombre]);
                        $tutorId = $stmt->fetchColumn();
                    }
                    if (!$tutorId) {
                        $stmt = $db->prepare("INSERT INTO public.tutores (nombre_completo, cedula) VALUES (?, ?) RETURNING id");
                        $stmt->execute([$nombre, $cedula]);
                        $tutorId = $stmt->fetchColumn();
                    }
                    if ($tutorId) {
                        $stmt = $db->prepare("INSERT INTO public.proyecto_tutores (id_recurso, id_tutor, tipo_tutor_id) VALUES (?, ?, ?) ON CONFLICT (id_recurso, id_tutor) DO UPDATE SET tipo_tutor_id = EXCLUDED.tipo_tutor_id");
                        $stmt->execute([$id, $tutorId, $tipoId]);
                    }
                }
            }
            
            // 5. Actualizar clasificación
            $stmt = $db->prepare("DELETE FROM public.recurso_clasificaciones WHERE id_recurso = ?");
            $stmt->execute([$id]);
            
            if (!empty($datos['linea_id'])) {
                $stmt = $db->prepare("INSERT INTO public.recurso_clasificaciones (id_recurso, id_linea_investigacion, id_dimension_operativa) 
                                      VALUES (?, ?, ?) ON CONFLICT (id_recurso, id_linea_investigacion) DO UPDATE SET id_dimension_operativa = EXCLUDED.id_dimension_operativa");
                $stmt->execute([
                    $id,
                    (int)$datos['linea_id'],
                    !empty($datos['dimension_id']) ? (int)$datos['dimension_id'] : null
                ]);
            }

            // 6. Actualizar carreras vinculadas secundarias (tags de vinculación intercarrera)
            $stmtDelVinc = $db->prepare("DELETE FROM public.proyecto_carreras_vinculadas WHERE id_recurso = ?");
            $stmtDelVinc->execute([$id]);

            if (!empty($datos['carreras_vinculadas']) && is_array($datos['carreras_vinculadas'])) {
                $stmtVinc = $db->prepare("INSERT INTO public.proyecto_carreras_vinculadas (id_recurso, id_carrera) 
                                          VALUES (?, ?) ON CONFLICT DO NOTHING");
                foreach ($datos['carreras_vinculadas'] as $carrId) {
                    $carrId = (int)$carrId;
                    if ($carrId > 0 && $carrId !== $idCarrera) {
                        $stmtVinc->execute([$id, $carrId]);
                    }
                }
            }
            
            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            error_log("Error al editar PST: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Elimina completamente un PST y sus relaciones asociadas de forma transaccional.
     */
    public function eliminarPST(int $id): bool {
        $db = Connection::getInstance();
        try {
            // Obtener el archivo adjunto para borrarlo físicamente
            $stmtFile = $db->prepare("SELECT archivo_pdf FROM public.recursos WHERE id = ?");
            $stmtFile->execute([$id]);
            $archivoPath = $stmtFile->fetchColumn();

            $db->beginTransaction();
            
            $stmt = $db->prepare("DELETE FROM public.proyecto_tutores WHERE id_recurso = ?");
            $stmt->execute([$id]);
            
            $stmt = $db->prepare("DELETE FROM public.recurso_autores WHERE id_recurso = ?");
            $stmt->execute([$id]);
            
            $stmt = $db->prepare("DELETE FROM public.recurso_clasificaciones WHERE id_recurso = ?");
            $stmt->execute([$id]);
            
            $stmt = $db->prepare("DELETE FROM public.detalles_proyectos WHERE id_recurso = ?");
            $stmt->execute([$id]);
            
            $stmt = $db->prepare("DELETE FROM public.recursos WHERE id = ?");
            $stmt->execute([$id]);
            
            $db->commit();

            // Limpieza del archivo físico en el servidor
            if (!empty($archivoPath)) {
                $basePath = defined('BASE_PATH') ? BASE_PATH : (defined('ROOT_PATH') ? ROOT_PATH : dirname(__DIR__, 3));
                $fullPath = $basePath . '/' . ltrim($archivoPath, '/\\');
                if (file_exists($fullPath) && is_file($fullPath)) {
                    @unlink($fullPath);
                }
            }

            return true;
        } catch (Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            error_log("Error al eliminar PST: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Alterna o establece el estado activo/desactivado (soft delete / ocultar) de un proyecto.
     */
    public function cambiarEstadoPST(int $id, bool $nuevoEstado): bool {
        $db = Connection::getInstance();
        $stmt = $db->prepare("UPDATE public.detalles_proyectos SET activo = ? WHERE id_recurso = ?");
        return $stmt->execute([$nuevoEstado ? 1 : 0, $id]);
    }

    public function getAutoresByRecurso(int $recursoId): array {
        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT a.nombre_completo, a.cedula 
                              FROM public.recurso_autores ra 
                              JOIN public.autores a ON ra.id_autor = a.id 
                              WHERE ra.id_recurso = ?");
        $stmt->execute([$recursoId]);
        return $this->cleanArray($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    public function getTutoresByRecurso(int $recursoId): array {
        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT t.nombre_completo, t.cedula, pt.tipo_tutor_id,
                                     CASE pt.tipo_tutor_id 
                                         WHEN 3 THEN 'Tutor Académico'
                                         WHEN 2 THEN 'Tutor Institucional'
                                         WHEN 4 THEN 'Tutor Comunitario'
                                         ELSE COALESCE(tt.nombre, 'Tutor')
                                     END AS tipo_nombre
                              FROM public.proyecto_tutores pt 
                              JOIN public.tutores t ON pt.id_tutor = t.id 
                              LEFT JOIN public.tipo_tutor tt ON pt.tipo_tutor_id = tt.id
                              WHERE pt.id_recurso = ?
                              ORDER BY pt.tipo_tutor_id ASC");
        $stmt->execute([$recursoId]);
        return $this->cleanArray($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Obtiene las carreras secundarias/interdisciplinarias vinculadas a un recurso PST.
     */
    public function getCarrerasVinculadasByRecurso(int $recursoId): array {
        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT c.id, c.nombre 
                              FROM public.proyecto_carreras_vinculadas pcv
                              JOIN public.carreras c ON pcv.id_carrera = c.id
                              WHERE pcv.id_recurso = ?
                              ORDER BY c.nombre ASC");
        $stmt->execute([$recursoId]);
        return $this->cleanArray($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getPSTCountByYear($filtros = null): array {
        $db = Connection::getInstance();
        $sql = "SELECT r.anio_publicacion, COUNT(DISTINCT r.id) as total
                FROM public.recursos r
                LEFT JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso
                LEFT JOIN public.trayectos t ON dp.id_trayecto = t.id
                LEFT JOIN public.recurso_clasificaciones rc ON r.id = rc.id_recurso
                LEFT JOIN public.lineas_investigacion li ON rc.id_linea_investigacion = li.id
                WHERE r.id_tipo_recurso = 1";
        
        $params = [];
        if (is_numeric($filtros)) {
            $carreraId = (int)$filtros;
            if ($carreraId > 0) {
                $sql .= " AND COALESCE(dp.id_carrera, li.id_carrera) = ?";
                $params[] = $carreraId;
            }
        } elseif (is_array($filtros)) {
            if (!empty($filtros['carrera_id'])) {
                $sql .= " AND COALESCE(dp.id_carrera, li.id_carrera) = ?";
                $params[] = (int)$filtros['carrera_id'];
            }
            if (!empty($filtros['linea_id'])) {
                $sql .= " AND rc.id_linea_investigacion = ?";
                $params[] = (int)$filtros['linea_id'];
            }
            if (!empty($filtros['dimension_id'])) {
                $sql .= " AND rc.id_dimension_operativa = ?";
                $params[] = (int)$filtros['dimension_id'];
            }
            if (!empty($filtros['nivel_academico'])) {
                $nivelMap = [
                    'Especialización' => 'Especializacion',
                    'Maestría'        => 'Maestria'
                ];
                $valNivel = trim($filtros['nivel_academico']);
                $valNivel = $nivelMap[$valNivel] ?? $valNivel;
                $sql .= " AND dp.nivel_academico = ?";
                $params[] = $valNivel;
            }
            if (!empty($filtros['trayecto'])) {
                $trayectoParam = trim((string)$filtros['trayecto']);
                if (is_numeric($trayectoParam)) {
                    $sql .= " AND (dp.id_trayecto = ? OR t.numero = ?)";
                    $params[] = (int)$trayectoParam;
                    $params[] = (int)$trayectoParam;
                } else {
                    $sql .= " AND t.nombre = ?";
                    $params[] = $trayectoParam;
                }
            }
            if (!empty($filtros['comunidad'])) {
                $sql .= " AND dp.comunidad_beneficiada ILIKE ?";
                $params[] = '%' . trim($filtros['comunidad']) . '%';
            }
        }
        $sql .= " GROUP BY r.anio_publicacion ORDER BY r.anio_publicacion ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                       
        $counts = [];
        for ($y = 2018; $y <= 2026; $y++) {
            $counts[$y] = 0;
        }
        foreach ($results as $row) {
            $year = (int)$row['anio_publicacion'];
            if ($year >= 2018 && $year <= 2026) {
                $counts[$year] = (int)$row['total'];
            }
        }
        return $counts;
    }

    public function buscarPST(string $query, array $filtros = [], int $limit = 5, int $offset = 0): array {
        $qb = new QueryBuilder();
        $qb->tabla('public.recursos r')
           ->select("r.id, r.titulo, r.anio_publicacion, r.archivo_pdf,
                     dp.resumen AS proyecto_resumen, dp.palabras_clave AS proyecto_palabras, dp.nivel_academico, t.nombre AS trayecto, dp.url_repositorio,
                     li.nombre AS linea_nombre,
                     dims.nombre AS dimension_nombre,
                     (SELECT STRING_AGG(c_vinc.nombre, ', ') 
                      FROM public.proyecto_carreras_vinculadas pcv 
                      JOIN public.carreras c_vinc ON pcv.id_carrera = c_vinc.id 
                      WHERE pcv.id_recurso = r.id) AS carreras_vinculadas_nombres,
                     (SELECT STRING_AGG(a.nombre_completo, ', ') 
                      FROM public.recurso_autores ra 
                      JOIN public.autores a ON ra.id_autor = a.id 
                      WHERE ra.id_recurso = r.id) AS autores_nombres")
           ->join('public.detalles_proyectos dp', 'r.id = dp.id_recurso', 'LEFT')
           ->join('public.trayectos t', 'dp.id_trayecto = t.id', 'LEFT')
           ->join('public.recurso_clasificaciones rc', 'r.id = rc.id_recurso', 'LEFT')
           ->join('public.lineas_investigacion li', 'rc.id_linea_investigacion = li.id', 'LEFT')
           ->join('public.dimensiones_operativas dims', 'rc.id_dimension_operativa = dims.id', 'LEFT')
           ->where('r.id_tipo_recurso', '=', 1);
           
        if (!empty($query)) {
            $keywords = self::extraerPalabrasClave($query);
            if (empty($keywords)) {
                $searchTerm = "%$query%";
                $qb->whereRaw("(r.titulo ILIKE ? OR dp.resumen ILIKE ? OR dp.palabras_clave ILIKE ?)", [$searchTerm, $searchTerm, $searchTerm]);
            } else {
                $conditions = [];
                $params = [];
                foreach ($keywords as $kw) {
                    $conditions[] = "(r.titulo ILIKE ? OR dp.resumen ILIKE ? OR dp.palabras_clave ILIKE ?)";
                    $params[] = "%$kw%";
                    $params[] = "%$kw%";
                    $params[] = "%$kw%";
                }
                $qb->whereRaw("(" . implode(" OR ", $conditions) . ")", $params);
            }
        }
        
        if (!empty($filtros['carrera_id'])) {
            $cId = (int)$filtros['carrera_id'];
            $qb->whereRaw("(COALESCE(dp.id_carrera, li.id_carrera) = ? OR EXISTS (SELECT 1 FROM public.proyecto_carreras_vinculadas pcv WHERE pcv.id_recurso = r.id AND pcv.id_carrera = ?))", [$cId, $cId]);
        }
        
        if (!empty($filtros['anio'])) {
            $qb->where('r.anio_publicacion', '=', (int)$filtros['anio']);
        }
        
        if (!empty($filtros['linea_id'])) {
            $qb->where('rc.id_linea_investigacion', '=', (int)$filtros['linea_id']);
        }
        
        if (!empty($filtros['dimension_id'])) {
            $qb->where('rc.id_dimension_operativa', '=', (int)$filtros['dimension_id']);
        }
        
        $results = $qb->orderBy('r.id', 'DESC')
                      ->limit($limit)
                      ->offset($offset)
                      ->get();
                      
        return $this->cleanArray($results);
    }

    public function buscarPSTCount(string $query, array $filtros = []): int {
        $qb = new QueryBuilder();
        $qb->tabla('public.recursos r')
           ->join('public.detalles_proyectos dp', 'r.id = dp.id_recurso', 'LEFT')
           ->join('public.recurso_clasificaciones rc', 'r.id = rc.id_recurso', 'LEFT')
           ->join('public.lineas_investigacion li', 'rc.id_linea_investigacion = li.id', 'LEFT')
           ->where('r.id_tipo_recurso', '=', 1);
           
        if (!empty($query)) {
            $keywords = self::extraerPalabrasClave($query);
            if (empty($keywords)) {
                $searchTerm = "%$query%";
                $qb->whereRaw("(r.titulo ILIKE ? OR dp.resumen ILIKE ? OR dp.palabras_clave ILIKE ?)", [$searchTerm, $searchTerm, $searchTerm]);
            } else {
                $conditions = [];
                $params = [];
                foreach ($keywords as $kw) {
                    $conditions[] = "(r.titulo ILIKE ? OR dp.resumen ILIKE ? OR dp.palabras_clave ILIKE ?)";
                    $params[] = "%$kw%";
                    $params[] = "%$kw%";
                    $params[] = "%$kw%";
                }
                $qb->whereRaw("(" . implode(" OR ", $conditions) . ")", $params);
            }
        }
        
        if (!empty($filtros['carrera_id'])) {
            $cId = (int)$filtros['carrera_id'];
            $qb->whereRaw("(COALESCE(dp.id_carrera, li.id_carrera) = ? OR EXISTS (SELECT 1 FROM public.proyecto_carreras_vinculadas pcv WHERE pcv.id_recurso = r.id AND pcv.id_carrera = ?))", [$cId, $cId]);
        }
        
        if (!empty($filtros['anio'])) {
            $qb->where('r.anio_publicacion', '=', (int)$filtros['anio']);
        }
        
        if (!empty($filtros['linea_id'])) {
            $qb->where('rc.id_linea_investigacion', '=', (int)$filtros['linea_id']);
        }
        
        if (!empty($filtros['dimension_id'])) {
            $qb->where('rc.id_dimension_operativa', '=', (int)$filtros['dimension_id']);
        }
        
        return $qb->count();
    }

    public static function extraerPalabrasClave(string $query): array {
        $query = mb_strtolower($query);
        $map = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'ü' => 'u', 'ñ' => 'n'
        ];
        $query = strtr($query, $map);
        $query = preg_replace('/[^a-z0-9\s]/u', ' ', $query);
        $words = array_filter(explode(' ', $query));
        
        $stopwords = [
            'dame', 'algo', 'de', 'un', 'una', 'el', 'la', 'los', 'las', 'y', 'en', 
            'para', 'con', 'por', 'sobre', 'del', 'al', 'lo', 'como', 'mas', 'que',
            'este', 'esta', 'estos', 'estas', 'buscar', 'encuentra', 'quiero',
            'necesito', 'proyectos', 'proyecto', 'investigacion', 'sobre'
        ];
        
        $keywords = [];
        foreach ($words as $w) {
            if (!in_array($w, $stopwords) && strlen($w) > 2) {
                $keywords[] = $w;
            }
        }
        return $keywords;
    }

    public function getPSTTrainingData(): array {
        $qb = new QueryBuilder();
        $results = $qb->tabla('public.recursos r')
                      ->select('r.id, dp.resumen, dp.palabras_clave, rc.id_linea_investigacion AS linea_id')
                      ->join('public.detalles_proyectos dp', 'r.id = dp.id_recurso', 'INNER')
                      ->join('public.recurso_clasificaciones rc', 'r.id = rc.id_recurso', 'INNER')
                      ->where('r.id_tipo_recurso', '=', 1)
                      ->whereRaw('rc.id_linea_investigacion IS NOT NULL')
                      ->get();
        return $this->cleanArray($results);
    }

    public function buscarSemantico(string $querytexto, array $filtros = []): array {
        if (trim($querytexto) === '') return [];
        
        require_once __DIR__ . '/../services/EmbeddingService.php';
        
        try {
            $embeddingService = new EmbeddingService();
            $vector = $embeddingService->generarEmbedding($querytexto);
            
            $vectorPgFormat = '[' . implode(',', array_map(
                fn($v) => sprintf('%.8f', $v),
                $vector
            )) . ']';

            $db = Connection::getInstance();

            $whereClauses = [
                'r.id_tipo_recurso = 1',
                'dp.vector_semantico IS NOT NULL',
                '(dp.vector_semantico <=> ?) < 0.85'
            ];
            $params = [$vectorPgFormat, $vectorPgFormat];

            if (!empty($filtros['anio'])) {
                $whereClauses[] = 'r.anio_publicacion = ?';
                $params[] = (int)$filtros['anio'];
            }
            if (!empty($filtros['linea_id'])) {
                $whereClauses[] = 'rc.id_linea_investigacion = ?';
                $params[] = (int)$filtros['linea_id'];
            }
            if (!empty($filtros['dimension_id'])) {
                $whereClauses[] = 'rc.id_dimension_operativa = ?';
                $params[] = (int)$filtros['dimension_id'];
            }
            if (!empty($filtros['carrera_id'])) {
                $whereClauses[] = '(li.id_carrera = ? OR EXISTS (SELECT 1 FROM public.proyecto_carreras_vinculadas pcv WHERE pcv.id_recurso = r.id AND pcv.id_carrera = ?))';
                $params[] = (int)$filtros['carrera_id'];
                $params[] = (int)$filtros['carrera_id'];
            }

            $sql = "SELECT 
    r.id, 
    r.titulo, 
    r.anio_publicacion,
    r.archivo_pdf,
    dp.resumen AS proyecto_resumen,
    dp.palabras_clave AS proyecto_palabras,
    dp.nivel_academico,
    t.nombre AS trayecto,
    dp.url_repositorio,
    COALESCE(dp.vistas, 0) AS vistas,
    li.nombre AS linea_nombre,
    dims.nombre AS dimension_nombre,
    (SELECT STRING_AGG(c_vinc.nombre, ', ') 
     FROM public.proyecto_carreras_vinculadas pcv 
     JOIN public.carreras c_vinc ON pcv.id_carrera = c_vinc.id 
     WHERE pcv.id_recurso = r.id) AS carreras_vinculadas_nombres,
    (dp.vector_semantico <=> ?) AS distancia,
    (SELECT STRING_AGG(a.nombre_completo, ', ') 
     FROM public.recurso_autores ra 
     JOIN public.autores a ON ra.id_autor = a.id 
     WHERE ra.id_recurso = r.id) AS autores_nombres
FROM public.recursos r
INNER JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso
LEFT JOIN public.trayectos t ON dp.id_trayecto = t.id
LEFT JOIN public.recurso_clasificaciones rc ON r.id = rc.id_recurso
LEFT JOIN public.lineas_investigacion li ON rc.id_linea_investigacion = li.id
LEFT JOIN public.dimensiones_operativas dims ON rc.id_dimension_operativa = dims.id
WHERE " . implode(' AND ', $whereClauses) . "
ORDER BY distancia ASC
LIMIT 20";
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $this->cleanArray($rows);
        } catch (\Exception $e) {
            error_log("Error en búsqueda semántica: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca el nombre completo de un autor o tutor a partir de su cédula.
     */
    public function getPersonaByCedula(string $cedula, string $tipo = 'autor'): ?string {
        $db = Connection::getInstance();
        $cleanCed = trim($cedula);
        if (empty($cleanCed)) return null;

        $tabla = ($tipo === 'tutor') ? 'public.tutores' : 'public.autores';
        $soloDigitos = preg_replace('/\D/', '', $cleanCed);
        $conPrefijoV = 'V-' . $soloDigitos;
        $conPrefijoE = 'E-' . $soloDigitos;

        $stmt = $db->prepare("SELECT nombre_completo FROM {$tabla} WHERE LOWER(TRIM(cedula)) IN (LOWER(?), LOWER(?), LOWER(?), LOWER(?)) LIMIT 1");
        $stmt->execute([$cleanCed, $soloDigitos, $conPrefijoV, $conPrefijoE]);
        $nombre = $stmt->fetchColumn();
        
        return $nombre ? $this->cleanCP850($nombre) : null;
    }

    /**
     * Busca la cédula de un autor o tutor a partir de su nombre completo.
     */
    public function getCedulaByNombre(string $nombre, string $tipo = 'tutor'): ?string {
        $db = Connection::getInstance();
        $cleanNom = trim($nombre);
        if (mb_strlen($cleanNom) < 4) return null;

        $tabla = ($tipo === 'tutor') ? 'public.tutores' : 'public.autores';
        
        $stmt = $db->prepare("SELECT cedula FROM {$tabla} WHERE LOWER(TRIM(nombre_completo)) = LOWER(TRIM(?)) AND cedula IS NOT NULL AND TRIM(cedula) != '' LIMIT 1");
        $stmt->execute([$cleanNom]);
        $cedula = $stmt->fetchColumn();

        if (!$cedula) {
            $stmtLike = $db->prepare("SELECT cedula FROM {$tabla} WHERE LOWER(TRIM(nombre_completo)) LIKE LOWER(?) AND cedula IS NOT NULL AND TRIM(cedula) != '' LIMIT 1");
            $stmtLike->execute(['%' . $cleanNom . '%']);
            $cedula = $stmtLike->fetchColumn();
        }

        return $cedula ? $this->cleanCP850(trim($cedula)) : null;
    }
}
