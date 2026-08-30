<?php
// modules/RepositorioPST/controllers/BusquedaGlobalController.php
require_once __DIR__ . '/../models/DocumentoModel.php';

class BusquedaGlobalController {
    
    public function index(): array {
        $model = new DocumentoModel();
        
        // Parámetros de búsqueda principal
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        // Paginación
        $limit = 5; // Mostrar 5 registros por página
        $page = !empty($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;
        
        // Filtros avanzados opcionales
        $filtrosExtra = [
            'anio'         => !empty($_GET['anio']) ? (int)$_GET['anio'] : null,
            'carrera_id'   => 1, // locked to PNF en Informática
            'linea_id'     => !empty($_GET['linea_id']) ? (int)$_GET['linea_id'] : null,
            'dimension_id' => !empty($_GET['dimension_id']) ? (int)$_GET['dimension_id'] : null,
        ];
        
        // Búsqueda SQL estándar con paginación
        $resultados = $model->buscarPST($q, $filtrosExtra, $limit, $offset);
        $totalResults = $model->buscarPSTCount($q, $filtrosExtra);
        
        $totalPages = ceil($totalResults / $limit);
        
        // Cargar colecciones para poblar selectores
        $carreras     = $model->getCarreras();
        $lineas       = $model->getLineasInvestigacion();
        $dimensiones  = $model->getDimensionesOperativas();
        $anioCounts   = $model->getPSTCountByYear();
        
        return [
            'resultados'   => $resultados,
            'q'            => $q,
            'carreras'     => $carreras,
            'lineas'       => $lineas,
            'dimensiones'  => $dimensiones,
            'anioCounts'   => $anioCounts,
            'filtros'      => $filtrosExtra,
            'pagination'   => [
                'current_page' => $page,
                'total_pages'  => $totalPages,
                'total_items'  => $totalResults,
                'limit'        => $limit
            ]
        ];
    }
}

