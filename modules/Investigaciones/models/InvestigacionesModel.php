<?php
// modules/Investigaciones/models/InvestigacionesModel.php

require_once CORE_PATH . 'Database/QueryBuilder.php';

class InvestigacionesModel extends QueryBuilder {

    // -------------------------------------------------------------------------
    // PROYECTOS
    // -------------------------------------------------------------------------

    /**
     * Devuelve todos los proyectos activos con datos de su investigador y línea.
     */
    public function getProyectosDestacados(): array {
        return $this->tabla('inv_proyectos p')
            ->select('p.id, p.titulo, p.resumen, p.imagen_url, p.estado, p.fecha_inicio,
                      i.nombre AS investigador_nombre, i.grado_academico,
                      l.nombre AS linea_nombre, l.icono_ph AS linea_icono')
            ->join('inv_investigadores i', 'p.investigador_id = i.id')
            ->join('inv_lineas l', 'p.linea_id = l.id')
            ->where('p.estado', '!=', 'concluido')
            ->orderBy('p.destacado', 'DESC')
            ->get();
    }

    /**
     * Devuelve todos los proyectos (incluidos concluidos).
     */
    public function getTodosLosProyectos(): array {
        return $this->tabla('inv_proyectos p')
            ->select('p.id, p.titulo, p.resumen, p.imagen_url, p.estado,
                      i.nombre AS investigador_nombre, i.grado_academico,
                      l.nombre AS linea_nombre, l.icono_ph AS linea_icono')
            ->join('inv_investigadores i', 'p.investigador_id = i.id')
            ->join('inv_lineas l', 'p.linea_id = l.id')
            ->orderBy('p.destacado', 'DESC')
            ->get();
    }

    // -------------------------------------------------------------------------
    // LÍNEAS DE INVESTIGACIÓN
    // -------------------------------------------------------------------------

    /**
     * Devuelve todas las líneas de investigación activas.
     */
    public function getLineas(): array {
        return $this->tabla('inv_lineas')
            ->orderBy('id', 'ASC')
            ->get();
    }

    // -------------------------------------------------------------------------
    // INVESTIGADORES
    // -------------------------------------------------------------------------

    /**
     * Devuelve todos los investigadores activos.
     */
    public function getInvestigadores(): array {
        return $this->tabla('inv_investigadores')
            ->where('activo', '=', TRUE)
            ->orderBy('id', 'ASC')
            ->get();
    }

    // -------------------------------------------------------------------------
    // VACANTES / POSTULACIONES
    // -------------------------------------------------------------------------

    /**
     * Devuelve todas las vacantes activas, ordenadas por nivel para el Kanban.
     * Agrupa los resultados por nivel en PHP para facilitar el renderizado de la vista.
     */
    public function getVacantesPorNivel(): array {
        $vacantes = $this->tabla('inv_vacantes v')
            ->select('v.id, v.titulo_rol, v.descripcion, v.nivel_requerido,
                      v.cupo_total, v.cupo_disponible,
                      p.titulo AS proyecto_titulo,
                      l.nombre AS linea_nombre')
            ->join('inv_proyectos p', 'v.proyecto_id = p.id')
            ->join('inv_lineas l',    'v.linea_id = l.id')
            ->where('v.activa', '=', TRUE)
            ->orderBy('v.nivel_requerido', 'ASC')
            ->get();

        // Agrupamos en PHP por nivel para facilitar el foreach en la vista Kanban
        $agrupadas = ['t3' => [], 't4' => [], 'postgrado' => []];
        foreach ($vacantes as $v) {
            $nivel = $v['nivel_requerido'];
            if (isset($agrupadas[$nivel])) {
                $agrupadas[$nivel][] = $v;
            }
        }
        return $agrupadas;
    }

    /**
     * Devuelve una vacante específica para mostrar en el modal.
     */
    public function getVacante(int $id): ?array {
        $resultado = $this->tabla('inv_vacantes v')
            ->select('v.id, v.titulo_rol, v.descripcion, v.nivel_requerido,
                      v.cupo_total, v.cupo_disponible,
                      p.titulo AS proyecto_titulo,
                      l.nombre AS linea_nombre')
            ->join('inv_proyectos p', 'v.proyecto_id = p.id')
            ->join('inv_lineas l',    'v.linea_id = l.id')
            ->where('v.id', '=', $id)
            ->first();
        return $resultado ?: null;
    }

    /**
     * Inserta una postulación y devuelve su ID.
     */
    public function registrarPostulacion(array $datos): int|false {
        return $this->tabla('inv_postulaciones')->insert([
            'vacante_id'         => (int) $datos['vacante_id'],
            'nombre_solicitante' => trim($datos['nombre_solicitante']),
            'cedula'             => trim($datos['cedula'] ?? ''),
            'email'              => trim($datos['email']),
            'motivacion'         => trim($datos['motivacion']),
            'portfolio_url'      => trim($datos['portfolio_url'] ?? ''),
            'estado'             => 'pendiente',
        ]);
    }

    // -------------------------------------------------------------------------
    // ANUNCIOS / CARTELERA
    // -------------------------------------------------------------------------

    /**
     * Devuelve los anuncios más recientes de la cartelera.
     */
    public function getAnuncios(int $limite = 10): array {
        return $this->tabla('inv_anuncios')
            ->orderBy('fecha_publicacion', 'DESC')
            ->limit($limite)
            ->get();
    }
}
