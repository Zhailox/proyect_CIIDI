<?php
// modules/LineasInvestigacion/models/LineasModel.php

require_once CORE_PATH . 'Database/QueryBuilder.php';

/**
 * LineasModel
 * Gestiona todas las operaciones de BD sobre lineas_investigacion
 * y sus relaciones con dimensiones, proyectos e investigaciones ofertadas.
 */
class LineasModel extends QueryBuilder {

    /**
     * Obtiene todas las líneas con estadísticas agregadas:
     * total de dimensiones, proyectos clasificados e investigaciones ofertadas.
     */
    public function getTodasConEstadisticas(): array {
        $sql = "
            SELECT
                li.id,
                li.nombre,
                li.descripcion,
                li.id_carrera,
                c.nombre  AS carrera_nombre,
                COUNT(DISTINCT dim.id)       AS total_dimensiones,
                COUNT(DISTINCT rc.id_recurso) AS total_proyectos,
                COUNT(DISTINCT io.id)         AS total_investigaciones
            FROM lineas_investigacion li
            LEFT JOIN carreras               c   ON c.id   = li.id_carrera
            LEFT JOIN dimensiones_operativas dim ON dim.id_linea = li.id
            LEFT JOIN recurso_clasificaciones rc  ON rc.id_linea_investigacion = li.id
            LEFT JOIN investigaciones_ofertadas io ON io.id_linea = li.id
            GROUP BY li.id, li.nombre, li.descripcion, li.id_carrera, c.nombre
            ORDER BY li.id ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtiene una línea por su ID, con datos de la carrera asociada.
     */
    public function getLineaConCarrera(int $id): ?array {
        $sql = "
            SELECT li.*, c.nombre AS carrera_nombre
            FROM lineas_investigacion li
            LEFT JOIN carreras c ON c.id = li.id_carrera
            WHERE li.id = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Obtiene los proyectos (recursos tipo 1) clasificados en una línea,
     * con datos de autor, dimensión operativa y detalle del proyecto.
     */
    public function getProyectosPorLinea(int $id_linea): array {
        $sql = "
            SELECT
                r.id,
                r.titulo,
                r.anio_publicacion,
                dp.resumen,
                dp.nivel_academico,
                dp.palabras_clave,
                dp.fecha_defensa,
                dim.nombre AS dimension_nombre,
                STRING_AGG(DISTINCT a.nombre_completo, ', ') AS autores
            FROM recurso_clasificaciones rc
            INNER JOIN recursos r          ON r.id   = rc.id_recurso
            LEFT  JOIN detalles_proyectos dp ON dp.id_recurso = r.id
            LEFT  JOIN dimensiones_operativas dim ON dim.id   = rc.id_dimension_operativa
            LEFT  JOIN recurso_autores ra  ON ra.id_recurso   = r.id
            LEFT  JOIN autores a           ON a.id            = ra.id_autor
            WHERE rc.id_linea_investigacion = ?
            GROUP BY r.id, r.titulo, r.anio_publicacion, dp.resumen,
                     dp.nivel_academico, dp.palabras_clave, dp.fecha_defensa, dim.nombre
            ORDER BY r.anio_publicacion DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_linea]);
        return $stmt->fetchAll();
    }

    /**
     * Obtiene las investigaciones ofertadas (activas/abiertas) para una línea,
     * con nombre del profesor y dimensión operativa.
     */
    public function getInvestigacionesPorLinea(int $id_linea): array {
        $sql = "
            SELECT
                io.*,
                dim.nombre  AS dimension_nombre,
                u.nombre_completo AS nombre_profesor
            FROM investigaciones_ofertadas io
            LEFT JOIN dimensiones_operativas dim ON dim.id = io.id_dimension
            LEFT JOIN usuarios              u   ON u.id   = io.id_profesor
            WHERE io.id_linea = ?
            ORDER BY io.fecha_creacion DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_linea]);
        return $stmt->fetchAll();
    }

    /**
     * Retorna todas las líneas en formato simple (para selectores de formulario).
     */
    public function getTodas(): array {
        return $this->tabla('lineas_investigacion')
                    ->orderBy('nombre', 'ASC')
                    ->get();
    }

    /**
     * Inserta una nueva línea de investigación.
     * @return int|false ID generado o false en fallo
     */
    public function crear(array $datos) {
        return $this->tabla('lineas_investigacion')->insert($datos);
    }

    /**
     * Actualiza una línea de investigación por ID.
     */
    public function actualizar(int $id, array $datos): bool {
        return $this->tabla('lineas_investigacion')
                    ->where('id', '=', $id)
                    ->update($datos);
    }

    /**
     * Elimina una línea de investigación por ID.
     */
    public function eliminar(int $id): bool {
        return $this->tabla('lineas_investigacion')
                    ->where('id', '=', $id)
                    ->delete();
    }
}
