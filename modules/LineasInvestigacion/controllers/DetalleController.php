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

        // Carga proyectos clasificados bajo esta línea
        $proyectos = $lineasModel->getProyectosPorLinea($id);

        // Carga investigaciones ofertadas bajo esta línea
        $investigaciones = $lineasModel->getInvestigacionesPorLinea($id);

        return [
            'linea'           => $linea,
            'dimensiones'     => $dimensiones,
            'proyectos'       => $proyectos,
            'investigaciones' => $investigaciones,
            'error'           => null,
        ];
    }
}
