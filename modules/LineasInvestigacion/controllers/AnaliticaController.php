<?php
// modules/LineasInvestigacion/controllers/AnaliticaController.php
require_once __DIR__ . '/../../../core/Security/Auth.php';

/**
 * Controlador neutralizado tras la eliminación del módulo de Analítica IA / Machine Learning.
 */
class AnaliticaController {

    public function index() {
        header('Location: index.php?ruta=lineas-investigacion');
        exit;
    }
}
