<?php
// modules/RepositorioPST/models/DocumentoModel.php
require_once CORE_PATH . 'Database/Connection.php';
require_once CORE_PATH . 'Database/QueryBuilder.php';

class DocumentoModel {

    /**
     * Traducción de caracteres corruptos de codificación DOS CP850 a UTF-8.
     */
    private function cleanCP850(?string $str): string {
        if ($str === null) return '';
        
        $map = [
            '¢' => 'ó',
            '¤' => 'ñ',
            '¡' => 'í',
            '£' => 'ú',
            '¥' => 'Ñ',
            '‚' => 'é',
            ' ' => 'á', // Non-breaking space U+00A0
            "\xC2\xA0" => 'á', // UTF-8 non-breaking space
            "\xA0" => 'á',
            '¢n' => 'ón',
            '¢s' => 'ós',
            '¡a' => 'ía',
            '¡n' => 'ín',
            '¡s' => 'ís',
            '£a' => 'úa',
            '£n' => 'ún',
            '£s' => 'ús'
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
                       dp.resumen, dp.palabras_clave, dp.comunidad_beneficiada, dp.nivel_academico, dp.trayecto, dp.url_repositorio,
                       li.nombre AS linea_nombre, 
                       li.id AS linea_id,
                       dims.nombre AS dimension_nombre,
                       dims.id AS dimension_id,
                       c.nombre AS carrera_nombre,
                       c.id AS carrera_id,
                       (SELECT STRING_AGG(a.nombre_completo, ', ') 
                        FROM public.recurso_autores ra 
                        JOIN public.autores a ON ra.id_autor = a.id 
                        WHERE ra.id_recurso = r.id) AS autores_nombres
                FROM public.recursos r
                LEFT JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso
                LEFT JOIN public.recurso_clasificaciones rc ON r.id = rc.id_recurso
                LEFT JOIN public.lineas_investigacion li ON rc.id_linea_investigacion = li.id
                LEFT JOIN public.dimensiones_operativas dims ON rc.id_dimension_operativa = dims.id
                LEFT JOIN public.carreras c ON COALESCE(dp.id_carrera, li.id_carrera) = c.id
                WHERE r.id_tipo_recurso = 1
                  AND COALESCE(dp.id_carrera, li.id_carrera) = 1"; 
                
        $execParams = [];
        
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
            $sql .= " AND dp.nivel_academico = ?::public.nivel_academico_enum";
            $execParams[] = $valNivel;
        }

        if (!empty($filtros['trayecto'])) {
            $sql .= " AND dp.trayecto = ?";
            $execParams[] = trim($filtros['trayecto']);
        }

        if (!empty($filtros['comunidad'])) {
            $sql .= " AND dp.comunidad_beneficiada ILIKE ?";
            $execParams[] = '%' . trim($filtros['comunidad']) . '%';
        }
        
        if (!empty($filtros['anio'])) {
            $sql .= " AND r.anio_publicacion = ?";
            $execParams[] = (int)$filtros['anio'];
        }
        
        $sql .= " ORDER BY r.id DESC LIMIT ? OFFSET ?";
        
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
                LEFT JOIN public.recurso_clasificaciones rc ON r.id = rc.id_recurso
                LEFT JOIN public.lineas_investigacion li ON rc.id_linea_investigacion = li.id
                WHERE r.id_tipo_recurso = 1
                  AND COALESCE(dp.id_carrera, li.id_carrera) = 1";
        
        $params = [];
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
            $sql .= " AND dp.nivel_academico = ?::public.nivel_academico_enum";
            $params[] = $valNivel;
        }
        if (!empty($filtros['trayecto'])) {
            $sql .= " AND dp.trayecto = ?";
            $params[] = trim($filtros['trayecto']);
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
     * Búsqueda estándar (Modo A) filtrando por título, palabras clave, resumen y paginación.
     */
    public function buscarStandard(string $query, array $filtrosExtra = [], int $limit = 5, int $offset = 0): array {
        $db = Connection::getInstance();
        
        $sql = "SELECT r.id, r.titulo, r.anio_publicacion, r.archivo_pdf,
                       tr.nombre AS tipo_recurso_nombre,
                       dp.resumen AS proyecto_resumen, dp.palabras_clave AS proyecto_palabras,
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

    public function getLineasInvestigacion(): array {
        if (!empty(self::$cacheLineas)) {
            return self::$cacheLineas;
        }
        if (isset($_SESSION['pst_cache_lineas']) && is_array($_SESSION['pst_cache_lineas'])) {
            self::$cacheLineas = $_SESSION['pst_cache_lineas'];
            return self::$cacheLineas;
        }
        $qb = new QueryBuilder();
        $res = $this->cleanArray($qb->tabla('lineas_investigacion')->orderBy('nombre', 'ASC')->get());
        self::$cacheLineas = $res;
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['pst_cache_lineas'] = $res;
        }
        return $res;
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

    /**
     * Obtiene un único PST por su ID.
     */
    public function getPSTDocumentoById(int $id): ?array {
        $db = Connection::getInstance();
        
        $sql = "SELECT r.id, r.titulo, r.anio_publicacion, r.archivo_pdf,
                       dp.resumen, dp.palabras_clave, dp.comunidad_beneficiada, dp.nivel_academico, dp.trayecto, dp.url_repositorio, dp.fecha_defensa,
                       li.nombre AS linea_nombre, 
                       li.id AS linea_id,
                       dims.nombre AS dimension_nombre,
                       dims.id AS dimension_id,
                       c.nombre AS carrera_nombre,
                       c.id AS carrera_id,
                       (SELECT STRING_AGG(a.nombre_completo, ', ') 
                        FROM public.recurso_autores ra 
                        JOIN public.autores a ON ra.id_autor = a.id 
                        WHERE ra.id_recurso = r.id) AS autores_nombres,
                       (SELECT STRING_AGG(t.nombre_completo || ' (' || 
                            CASE pt.tipo_tutor_id 
                                WHEN 3 THEN 'Tutor Académico'
                                WHEN 2 THEN 'Tutor Institucional'
                                WHEN 4 THEN 'Tutor Comunitario'
                                ELSE COALESCE(tt.nombre, 'Tutor')
                            END || ')', ' • ') 
                        FROM public.proyecto_tutores pt 
                        JOIN public.tutores t ON pt.id_tutor = t.id 
                        LEFT JOIN public.tipo_tutor tt ON pt.tipo_tutor_id = tt.id
                        WHERE pt.id_recurso = r.id) AS tutores_nombres
                FROM public.recursos r
                LEFT JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso
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
            return $row;
        }
        return null;
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
            $stmt = $db->prepare("INSERT INTO public.recursos (titulo, id_tipo_recurso, anio_publicacion, ejemplares_totales, ejemplares_disponibles, archivo_pdf) 
                                  VALUES (?, 1, ?, 1, 1, ?) RETURNING id");
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
            $nivelAcademicoRaw = !empty($datos['nivel_academico']) ? trim($datos['nivel_academico']) : 'Pregrado';
            $nivelMap = [
                'Especialización' => 'Especializacion',
                'Maestría'        => 'Maestria'
            ];
            $nivelAcademico = $nivelMap[$nivelAcademicoRaw] ?? $nivelAcademicoRaw;
            $trayectoVal = ($nivelAcademicoRaw === 'Pregrado') ? (!empty($datos['trayecto']) ? trim($datos['trayecto']) : 'Trayecto I') : null;

            $stmt = $db->prepare("INSERT INTO public.detalles_proyectos (id_recurso, fecha_defensa, nivel_academico, trayecto, url_repositorio, resumen, id_carrera, comunidad_beneficiada, palabras_clave) 
                                  VALUES (?, ?, ?::public.nivel_academico_enum, ?, ?, ?, 1, ?, ?)");
            $stmt->execute([
                $recursoId,
                !empty($datos['fecha_defensa']) ? $datos['fecha_defensa'] : date('Y-m-d'),
                $nivelAcademico,
                $trayectoVal,
                !empty($datos['url_repositorio']) ? trim($datos['url_repositorio']) : null,
                $datos['resumen'],
                $datos['comunidad_beneficiada'],
                $datos['palabras_clave']
            ]);
            
            // 3. Insertar autores múltiples con deduplicación avanzada (Cédula -> Nombre exacto -> Soundex / Levenshtein)
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
                        $stmt = $db->prepare("INSERT INTO public.tutores (nombre_completo, cedula) VALUES (?, ?) RETURNING id");
                        $stmt->execute([$nombre, $cedula]);
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
            $trayectoVal = ($nivelAcademicoRaw === 'Pregrado') ? (!empty($datos['trayecto']) ? trim($datos['trayecto']) : 'Trayecto I') : null;

            $stmt = $db->prepare("UPDATE public.detalles_proyectos 
                                  SET fecha_defensa = ?, nivel_academico = ?::public.nivel_academico_enum, trayecto = ?, url_repositorio = ?, resumen = ?, comunidad_beneficiada = ?, palabras_clave = ? 
                                  WHERE id_recurso = ?");
            $stmt->execute([
                !empty($datos['fecha_defensa']) ? $datos['fecha_defensa'] : date('Y-m-d'),
                $nivelAcademico,
                $trayectoVal,
                !empty($datos['url_repositorio']) ? trim($datos['url_repositorio']) : null,
                $datos['resumen'],
                $datos['comunidad_beneficiada'],
                $datos['palabras_clave'],
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
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            error_log("Error al eliminar PST: " . $e->getMessage());
            throw $e;
        }
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

    public function getPSTCountByYear(): array {
        $qb = new QueryBuilder();
        $results = $qb->tabla('public.recursos r')
                      ->select('r.anio_publicacion, COUNT(*) as total')
                      ->where('r.id_tipo_recurso', '=', 1)
                      ->groupBy('r.anio_publicacion')
                      ->orderBy('r.anio_publicacion', 'ASC')
                      ->get();
                      
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
                     dp.resumen AS proyecto_resumen, dp.palabras_clave AS proyecto_palabras, dp.nivel_academico, dp.trayecto, dp.url_repositorio,
                     li.nombre AS linea_nombre,
                     dims.nombre AS dimension_nombre,
                     (SELECT STRING_AGG(a.nombre_completo, ', ') 
                      FROM public.recurso_autores ra 
                      JOIN public.autores a ON ra.id_autor = a.id 
                      WHERE ra.id_recurso = r.id) AS autores_nombres")
           ->join('public.detalles_proyectos dp', 'r.id = dp.id_recurso', 'LEFT')
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

    /**
     * Busca el nombre completo de un autor o tutor a partir de su cédula.
     */
    public function getPersonaByCedula(string $cedula, string $tipo = 'autor'): ?string {
        $db = Connection::getInstance();
        $cleanCed = trim($cedula);
        if (empty($cleanCed)) return null;

        $tabla = ($tipo === 'tutor') ? 'public.tutores' : 'public.autores';
        $stmt = $db->prepare("SELECT nombre_completo FROM {$tabla} WHERE LOWER(TRIM(cedula)) = LOWER(?) LIMIT 1");
        $stmt->execute([$cleanCed]);
        $nombre = $stmt->fetchColumn();
        
        return $nombre ? $this->cleanCP850($nombre) : null;
    }
}
