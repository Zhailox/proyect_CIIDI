<?php
// modules/LineasInvestigacion/controllers/DetalleController.php

require_once __DIR__ . '/../models/LineasModel.php';
require_once __DIR__ . '/../models/DimensionesModel.php';

/**
 * DetalleLineaController
 * Provee los datos de la vista de detalle de una línea de investigación específica.
 * Requiere el parámetro GET 'id'.
 */
class DetalleLineaController {

    public function index(): array {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        $lineasModel     = new LineasModel();
        $dimensionesModel = new DimensionesModel();

        // Carga la línea con datos de carrera
        $linea = $lineasModel->getLineaConCarrera($id);

        // Si no existe, devolvemos array de error para que la vista lo maneje
        if (!$linea) {
            return [
                'linea'           => null,
                'dimensiones'     => [],
                'proyectos'       => [],
                'investigaciones' => [],
                'error'           => 'La línea de investigación solicitada no existe o fue eliminada.',
            ];
        }

        // Carga dimensiones operativas de esta línea
        $dimensiones = $dimensionesModel->getPorLinea($id);

        // Paginación
        $page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        // Carga proyectos clasificados bajo esta línea
        $proyectos = $lineasModel->getProyectosPorLinea($id, $limit, $offset);
        $total_proyectos = $lineasModel->countProyectosPorLinea($id);
        $total_pages = ceil($total_proyectos / $limit);

        // Carga investigaciones ofertadas bajo esta línea
        $investigaciones = $lineasModel->getInvestigacionesPorLinea($id);

        return [
            'linea'           => $linea,
            'dimensiones'     => $dimensiones,
            'proyectos'       => $proyectos,
            'investigaciones' => $investigaciones,
            'pagination'      => [
                'current_page' => $page,
                'total_pages'  => $total_pages,
                'total_items'  => $total_proyectos,
                'limit'        => $limit
            ],
            'error'           => null,
        ];
    }
}
