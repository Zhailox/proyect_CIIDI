<?php
// modules/Cursos/models/CursoModel.php

require_once CORE_PATH . 'Database/QueryBuilder.php';

class CursoModel extends QueryBuilder {

    // -------------------------------------------------------------------------
    // CATÁLOGO
    // -------------------------------------------------------------------------

    /**
     * Devuelve todos los cursos publicados con nombre del docente
     * y conteo de lecciones e inscritos.
     */
    public function getCursosPublicados(): array {
        $cursos = $this->tabla('cursos c')
            ->select('c.id, c.titulo, c.descripcion, c.imagen_portada,
                      c.id_docente, c.nota_minima_aprobacion, c.fecha_creacion,
                      u.nombre_completo AS docente_nombre')
            ->join('usuarios u', 'c.id_docente = u.id')
            ->where('c.estado', '=', 'publicado')
            ->orderBy('c.fecha_creacion', 'DESC')
            ->get();

        // Enriquecer cada curso con conteos
        foreach ($cursos as &$curso) {
            $curso['total_lecciones'] = $this->contarLecciones($curso['id']);
            $curso['total_inscritos']  = $this->contarInscritos($curso['id']);
        }
        unset($curso);

        return $cursos;
    }

    /**
     * Devuelve un único curso con todos sus datos.
     */
    public function getCurso(int $id): ?array {
        $curso = $this->tabla('cursos c')
            ->select('c.id, c.titulo, c.descripcion, c.imagen_portada,
                      c.nota_minima_aprobacion, c.estado,
                      u.nombre_completo AS docente_nombre,
                      u.email AS docente_email')
            ->join('usuarios u', 'c.id_docente = u.id')
            ->where('c.id', '=', $id)
            ->first();

        return $curso ?: null;
    }

    // -------------------------------------------------------------------------
    // LECCIONES
    // -------------------------------------------------------------------------

    /**
     * Devuelve las lecciones de un curso, ordenadas por número de orden.
     */
    public function getLeccionesCurso(int $id_curso): array {
        return $this->tabla('lecciones_curso')
            ->where('id_curso', '=', $id_curso)
            ->orderBy('orden', 'ASC')
            ->get();
    }

    /**
     * Cuenta cuántas lecciones tiene un curso.
     */
    public function contarLecciones(int $id_curso): int {
        $result = $this->tabla('lecciones_curso')
            ->select('COUNT(*) AS total')
            ->where('id_curso', '=', $id_curso)
            ->first();
        return (int) ($result['total'] ?? 0);
    }

    // -------------------------------------------------------------------------
    // INSCRIPCIONES
    // -------------------------------------------------------------------------

    /**
     * Devuelve la inscripción activa de un usuario en un curso (o null).
     */
    public function getInscripcion(int $id_usuario, int $id_curso): ?array {
        $resultado = $this->tabla('inscripciones_curso')
            ->where('id_usuario', '=', $id_usuario)
            ->where('id_curso',   '=', $id_curso)
            ->first();
        return $resultado ?: null;
    }

    /**
     * Registra una nueva inscripción. Devuelve el ID insertado o false.
     */
    public function registrarInscripcion(int $id_usuario, int $id_curso): int|false {
        return $this->tabla('inscripciones_curso')->insert([
            'id_usuario' => $id_usuario,
            'id_curso'   => $id_curso,
            'progreso'   => 0,
        ]);
    }

    // -------------------------------------------------------------------------
    // CRUD DE CURSOS (admin / docente propietario)
    // -------------------------------------------------------------------------

    /**
     * Inserta un nuevo curso. Devuelve el ID generado.
     */
    public function crearCurso(array $datos): int|false {
        return $this->tabla('cursos')->insert([
            'titulo'                 => trim($datos['titulo']),
            'descripcion'            => trim($datos['descripcion'] ?? ''),
            'imagen_portada'         => trim($datos['imagen_portada'] ?? ''),
            'nota_minima_aprobacion' => (float) ($datos['nota_minima_aprobacion'] ?? 70),
            'id_docente'             => (int)   $datos['id_docente'],
            'estado'                 => 'publicado',
        ]);
    }

    /**
     * Actualiza un curso existente.
     */
    public function actualizarCurso(int $id, array $datos): bool {
        return $this->tabla('cursos')
            ->where('id', '=', $id)
            ->update([
                'titulo'                 => trim($datos['titulo']),
                'descripcion'            => trim($datos['descripcion'] ?? ''),
                'imagen_portada'         => trim($datos['imagen_portada'] ?? ''),
                'nota_minima_aprobacion' => (float) ($datos['nota_minima_aprobacion'] ?? 70),
                'estado'                 => $datos['estado'] ?? 'publicado',
            ]);
    }

    /**
     * Elimina un curso (solo admin o propietario verificado antes de llamar).
     */
    public function eliminarCurso(int $id): bool {
        return $this->tabla('cursos')
            ->where('id', '=', $id)
            ->delete();
    }

    /**
     * Verifica si el usuario puede editar/eliminar el curso
     * (es el docente propietario o es admin nivel >= 3).
     */
    public function puedeGestionar(int $id_curso, int $usuario_id, int $nivel_privilegio): bool {
        if ($nivel_privilegio >= 3) return true; // Admin: acceso total

        $curso = $this->getCurso($id_curso);
        return $curso && (int) $curso['id_docente'] === $usuario_id;
    }

    /**
     * Cuenta el total de inscritos en un curso.
     */
    public function contarInscritos(int $id_curso): int {
        $result = $this->tabla('inscripciones_curso')
            ->select('COUNT(*) AS total')
            ->where('id_curso', '=', $id_curso)
            ->first();
        return (int) ($result['total'] ?? 0);
    }
}
