<?php
// modules/LineasInvestigacion/models/DimensionesModel.php

require_once CORE_PATH . 'Database/QueryBuilder.php';

/**
 * DimensionesModel
 * Gestiona todas las operaciones de BD sobre dimensiones_operativas.
 */
class DimensionesModel extends QueryBuilder {

    /**
     * Obtiene todas las dimensiones de una línea específica.
     */
    public function getPorLinea(int $id_linea): array {
        return $this->tabla('dimensiones_operativas')
                    ->where('id_linea', '=', $id_linea)
                    ->orderBy('nombre', 'ASC')
                    ->get();
    }

    /**
     * Obtiene una dimensión por su ID.
     */
    public function getPorId(int $id): ?array {
        $result = $this->tabla('dimensiones_operativas')
                       ->where('id', '=', $id)
                       ->first();
        return $result ?: null;
    }

    /**
     * Obtiene todas las dimensiones con el nombre de su línea padre,
     * útil para la vista del gestor.
     */
    public function getTodasConLinea(): array {
        $sql = "
            SELECT
                dim.id,
                dim.nombre,
                dim.descripcion,
                dim.id_linea,
                li.nombre  AS linea_nombre
            FROM dimensiones_operativas dim
            INNER JOIN lineas_investigacion li ON li.id = dim.id_linea
            ORDER BY li.nombre ASC, dim.nombre ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Cuenta cuántos proyectos están clasificados bajo una dimensión.
     */
    public function contarProyectosPorDimension(int $id_dimension): int {
        $sql = "SELECT COUNT(*) AS total FROM recurso_clasificaciones WHERE id_dimension_operativa = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_dimension]);
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    /**
     * Inserta una nueva dimensión operativa.
     * @return int|false ID generado o false en fallo
     */
    public function crear(array $datos) {
        return $this->tabla('dimensiones_operativas')->insert($datos);
    }

    /**
     * Actualiza una dimensión operativa por ID.
     */
    public function actualizar(int $id, array $datos): bool {
        return $this->tabla('dimensiones_operativas')
                    ->where('id', '=', $id)
                    ->update($datos);
    }

    /**
     * Elimina una dimensión operativa por ID.
     */
    public function eliminar(int $id): bool {
        return $this->tabla('dimensiones_operativas')
                    ->where('id', '=', $id)
                    ->delete();
    }
}
