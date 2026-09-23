<?php
// modules/SuperAdmin/models/ReportesModel.php
require_once CORE_PATH . 'Database/QueryBuilder.php';

class ReportesModel {

    private $qb;
    private $db;

    public function __construct() {
        $this->qb = new QueryBuilder();
        $this->db = Connection::getInstance();
    }

    /**
     * Reporte 1: Producción Científica PST (Campos reales comprobados)
     */
    public function obtenerReportePST(array $filtros = []): array {
        $sql = "SELECT DISTINCT ON (r.id)
                    r.id,
                    r.titulo,
                    COALESCE(c.nombre, 'Informática') AS carrera,
                    COALESCE(dp.nivel_academico, 'Pregrado') AS nivel_academico,
                    COALESCE(NULLIF(TRIM(dp.trayecto), ''), 'Sin Trayecto') AS trayecto,
                    r.anio_publicacion,
                    COALESCE(dp.comunidad_beneficiada, 'N/D') AS comunidad_beneficiada,
                    COALESCE(li.nombre, 'General / No Asignada') AS linea_investigacion,
                    COALESCE(dp.fecha_defensa::text, 'N/D') AS fecha_defensa,
                    COALESCE(dp.obj_general, 'N/D') AS obj_general,
                    COALESCE(dp.resumen, 'Sin resumen registrado.') AS resumen
                FROM public.recursos r
                JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso
                LEFT JOIN public.recurso_clasificaciones rc ON r.id = rc.id_recurso
                LEFT JOIN public.lineas_investigacion li ON rc.id_linea_investigacion = li.id
                LEFT JOIN public.carreras c ON COALESCE(dp.id_carrera, li.id_carrera) = c.id
                WHERE r.id_tipo_recurso = 1";
        
        $params = [];

        if (!empty($filtros['anio'])) {
            $sql .= " AND r.anio_publicacion = :anio";
            $params[':anio'] = (int)$filtros['anio'];
        }

        if (!empty($filtros['trayecto'])) {
            $sql .= " AND (dp.trayecto = :trayecto OR dp.trayecto = 'Trayecto ' || :trayecto)";
            $params[':trayecto'] = (string)$filtros['trayecto'];
        }

        if (!empty($filtros['nivel_academico'])) {
            $sql .= " AND dp.nivel_academico = :nivel_academico";
            $params[':nivel_academico'] = $filtros['nivel_academico'];
        }

        if (!empty($filtros['linea_investigacion'])) {
            $sql .= " AND li.id = :linea_investigacion";
            $params[':linea_investigacion'] = (int)$filtros['linea_investigacion'];
        }

        $sql .= " ORDER BY r.id DESC, r.anio_publicacion DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Reporte 2: Propuestas de Vinculación Empresarial
     */
    public function obtenerReporteVinculacion(array $filtros = []): array {
        $sql = "SELECT 
                    p.id,
                    p.nombre_empresa,
                    COALESCE(p.persona_contacto, 'N/D') AS contacto_persona,
                    COALESCE(p.telefono_contacto, 'N/D') AS telefono,
                    COALESCE(p.correo_contacto, 'N/D') AS correo,
                    COALESCE(p.descripcion_problema, p.area_afectada, 'N/D') AS titulo_problematica,
                    p.estado,
                    COALESCE(p.nivel_trayecto, 'N/D') AS nivel_trayecto,
                    p.fecha_creacion
                FROM public.propuestas_empresa p
                WHERE 1=1";
        
        $params = [];

        if (!empty($filtros['estado'])) {
            $sql .= " AND p.estado = :estado";
            $params[':estado'] = $filtros['estado'];
        }

        if (!empty($filtros['fecha_desde'])) {
            $sql .= " AND p.fecha_creacion >= :fecha_desde";
            $params[':fecha_desde'] = $filtros['fecha_desde'] . ' 00:00:00';
        }

        if (!empty($filtros['fecha_hasta'])) {
            $sql .= " AND p.fecha_creacion <= :fecha_hasta";
            $params[':fecha_hasta'] = $filtros['fecha_hasta'] . ' 23:59:59';
        }

        $sql .= " ORDER BY p.fecha_creacion DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Reporte 3: Padrón de Usuarios del Sistema
     */
    public function obtenerReporteUsuarios(array $filtros = []): array {
        $sql = "SELECT 
                    u.id,
                    u.cedula,
                    u.nombre_completo AS nombre,
                    '' AS apellido,
                    u.email AS correo,
                    r.nombre AS rol,
                    u.activo,
                    NOW() AS fecha_registro
                FROM public.usuarios u
                LEFT JOIN public.roles r ON u.id_rol = r.id
                WHERE 1=1";
        
        $params = [];

        if (isset($filtros['activo']) && $filtros['activo'] !== '') {
            $sql .= " AND u.activo = :activo";
            $params[':activo'] = $filtros['activo'] === '1' ? 'true' : 'false';
        }

        if (!empty($filtros['id_rol'])) {
            $sql .= " AND u.id_rol = :id_rol";
            $params[':id_rol'] = (int)$filtros['id_rol'];
        }

        $sql .= " ORDER BY u.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Reporte 4: Auditoría Técnica y Logs
     */
    public function obtenerReporteLogs(array $filtros = []): array {
        $sql = "SELECT 
                    l.id,
                    l.fecha_hora AS fecha,
                    COALESCE(l.nivel, 'INFO') AS nivel,
                    l.accion AS mensaje,
                    COALESCE(l.ip, '127.0.0.1') AS ip,
                    COALESCE(l.responsable, 'Sistema') AS responsable
                FROM public.system_audit_log l
                WHERE 1=1";
        
        $params = [];

        if (!empty($filtros['nivel'])) {
            $sql .= " AND l.nivel = :nivel";
            $params[':nivel'] = $filtros['nivel'];
        }

        if (!empty($filtros['fecha_desde'])) {
            $sql .= " AND l.fecha_hora >= :fecha_desde";
            $params[':fecha_desde'] = $filtros['fecha_desde'] . ' 00:00:00';
        }

        if (!empty($filtros['fecha_hasta'])) {
            $sql .= " AND l.fecha_hora <= :fecha_hasta";
            $params[':fecha_hasta'] = $filtros['fecha_hasta'] . ' 23:59:59';
        }

        $sql .= " ORDER BY l.id DESC LIMIT 500";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener líneas de investigación activas para filtros
     */
    public function obtenerLineasInvestigacion(): array {
        try {
            $stmt = $this->db->query("SELECT id, nombre FROM public.lineas_investigacion ORDER BY nombre ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Obtener agregaciones estadísticas agrupadas limpias (sin duplicaciones)
     */
    public function obtenerEstadisticasGrafico(string $dominio, string $agruparPor = 'auto'): array {
        $labels = [];
        $val = [];

        try {
            if ($dominio === 'pst') {
                if ($agruparPor === 'nivel') {
                    $sql = "SELECT COALESCE(dp.nivel_academico, 'Pregrado') as label, COUNT(DISTINCT r.id) as cantidad 
                            FROM public.recursos r 
                            JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso 
                            WHERE r.id_tipo_recurso = 1
                            GROUP BY dp.nivel_academico ORDER BY cantidad DESC";
                } elseif ($agruparPor === 'linea') {
                    $sql = "SELECT COALESCE(li.nombre, 'Sin Línea Asignada') as label, COUNT(DISTINCT r.id) as cantidad 
                            FROM public.recursos r 
                            JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso
                            LEFT JOIN public.recurso_clasificaciones rc ON r.id = rc.id_recurso
                            LEFT JOIN public.lineas_investigacion li ON rc.id_linea_investigacion = li.id
                            WHERE r.id_tipo_recurso = 1
                            GROUP BY li.nombre ORDER BY cantidad DESC";
                } elseif ($agruparPor === 'carrera') {
                    $sql = "SELECT COALESCE(c.nombre, 'Informática') as label, COUNT(DISTINCT r.id) as cantidad 
                            FROM public.recursos r 
                            JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso
                            LEFT JOIN public.recurso_clasificaciones rc ON r.id = rc.id_recurso
                            LEFT JOIN public.lineas_investigacion li ON rc.id_linea_investigacion = li.id
                            LEFT JOIN public.carreras c ON COALESCE(dp.id_carrera, li.id_carrera) = c.id
                            WHERE r.id_tipo_recurso = 1
                            GROUP BY c.nombre ORDER BY cantidad DESC";
                } elseif ($agruparPor === 'anio') {
                    $sql = "SELECT r.anio_publicacion::text as label, COUNT(DISTINCT r.id) as cantidad 
                            FROM public.recursos r 
                            JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso 
                            WHERE r.id_tipo_recurso = 1
                            GROUP BY r.anio_publicacion ORDER BY r.anio_publicacion ASC";
                } else {
                    // Limpieza absoluta de trayecto directamente de los valores en base de datos ('Trayecto I', 'Trayecto II', etc.)
                    $sql = "SELECT 
                                COALESCE(NULLIF(TRIM(dp.trayecto), ''), 'Sin Trayecto Asignado') as label, 
                                COUNT(DISTINCT r.id) as cantidad 
                            FROM public.recursos r 
                            JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso 
                            WHERE r.id_tipo_recurso = 1
                            GROUP BY label ORDER BY label ASC";
                }
            } elseif ($dominio === 'vinculacion') {
                if ($agruparPor === 'trayecto') {
                    $sql = "SELECT COALESCE(NULLIF(TRIM(p.nivel_trayecto), ''), 'Sin Trayecto') as label, COUNT(*) as cantidad 
                            FROM public.propuestas_empresa p GROUP BY label ORDER BY cantidad DESC";
                } else {
                    // Se agrega el casteo ::text al ENUM para evitar el crash de PostgreSQL
                    $sql = "SELECT UPPER(p.estado::text) as label, COUNT(*) as cantidad 
                            FROM public.propuestas_empresa p GROUP BY p.estado ORDER BY cantidad DESC";
                }
            } elseif ($dominio === 'usuarios') {
                $sql = "SELECT COALESCE(r.nombre, 'Sin Rol') as label, COUNT(*) as cantidad 
                        FROM public.usuarios u LEFT JOIN public.roles r ON u.id_rol = r.id GROUP BY r.nombre ORDER BY cantidad DESC";
            } else {
                $sql = "SELECT COALESCE(l.nivel, 'INFO') as label, COUNT(*) as cantidad 
                        FROM public.system_audit_log l GROUP BY l.nivel ORDER BY cantidad DESC";
            }

            $stmt = $this->db->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $r) {
                $labels[] = $r['label'] ?: 'N/D';
                $val[] = (int)$r['cantidad'];
            }
        } catch (Exception $e) {
            $labels = ['Trayecto I', 'Trayecto II', 'Trayecto III', 'Trayecto IV'];
            $val = [0, 0, 0, 0];
        }

        return ['labels' => $labels, 'valores' => $val];
    }

    public function obtenerResumenGeneral(): array {
        $totalPst = 0;
        $totalEmpresas = 0;
        $totalUsuarios = 0;
        $totalLogs = 0;

        try { $totalPst = (int)($this->db->query("SELECT COUNT(DISTINCT id_recurso) FROM public.detalles_proyectos")->fetchColumn() ?: 0); } catch (Exception $e) {}
        try { $totalEmpresas = (int)($this->db->query("SELECT COUNT(*) FROM public.propuestas_empresa")->fetchColumn() ?: 0); } catch (Exception $e) {}
        try { $totalUsuarios = (int)($this->db->query("SELECT COUNT(*) FROM public.usuarios")->fetchColumn() ?: 0); } catch (Exception $e) {}
        try { $totalLogs = (int)($this->db->query("SELECT COUNT(*) FROM public.system_audit_log")->fetchColumn() ?: 0); } catch (Exception $e) {}

        return [
            'pst' => $totalPst,
            'empresas' => $totalEmpresas,
            'usuarios' => $totalUsuarios,
            'logs' => $totalLogs
        ];
    }
}
