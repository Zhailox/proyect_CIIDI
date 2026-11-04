<?php
// modules/RepositorioPST/controllers/BusquedaGlobalController.php
require_once __DIR__ . '/../services/ConfigService.php';
require_once __DIR__ . '/../models/DocumentoModel.php';

class BusquedaGlobalController {
    
    public function index(): array {
        $model = new DocumentoModel();
        
        // Parámetros de búsqueda principal
        $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
        
        // Paginación dinámica desde la configuración del módulo
        $limitDefault = max(1, (int)ConfigService::get('paginacion.limite_buscador', 5));
        $limit = !empty($_GET['limit']) ? max(1, min(50, (int)$_GET['limit'])) : $limitDefault;
        $page = !empty($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        
        // Configuración de filtro dinámico de carrera
        $permitirFiltroCarrera = (bool)ConfigService::get('buscador.permitir_filtro_carrera', true);
        $carreraDefaultId = (int)ConfigService::get('buscador.carrera_default_id', 1);
        $carreraId = null;
        if (!empty($_GET['carrera_id'])) {
            $carreraId = (int)$_GET['carrera_id'];
        } elseif (!$permitirFiltroCarrera) {
            $carreraId = $carreraDefaultId > 0 ? $carreraDefaultId : 1;
        }

        $semanticaHabilitada = (bool)ConfigService::get('red_onnx.habilitar_busqueda_semantica', true);
        $usar_ia = ($semanticaHabilitada && !empty($_GET['usar_ia'])) ? true : false;

        $ordenConfig = (string)ConfigService::get('buscador.orden_predeterminado', 'anio_desc');
        $ordenDefault = ($q !== '') ? 'relevancia' : $ordenConfig;
        $orden = !empty($_GET['orden']) ? trim((string)$_GET['orden']) : $ordenDefault;
        
        // Filtros avanzados dinámicos
        $filtrosExtra = [
            'q'               => $q,
            'usar_ia'         => $usar_ia,
            'anio'            => !empty($_GET['anio']) ? (int)$_GET['anio'] : null,
            'carrera_id'      => $carreraId,
            'linea_id'        => !empty($_GET['linea_id']) ? (int)$_GET['linea_id'] : null,
            'dimension_id'    => !empty($_GET['dimension_id']) ? (int)$_GET['dimension_id'] : null,
            'nivel_academico' => !empty($_GET['nivel_academico']) ? trim((string)$_GET['nivel_academico']) : null,
            'trayecto'        => !empty($_GET['trayecto']) ? trim((string)$_GET['trayecto']) : null,
            'orden'           => $orden,
        ];
        
        // Determinar si el usuario ya inició una búsqueda o aplicó algún filtro real
        $filtroCarreraUsuario = $permitirFiltroCarrera && !empty($_GET['carrera_id']);
        $busquedaActiva = (
            $q !== '' ||
            !empty($_GET['anio']) ||
            !empty($_GET['linea_id']) ||
            !empty($_GET['dimension_id']) ||
            !empty($_GET['nivel_academico']) ||
            !empty($_GET['trayecto']) ||
            $filtroCarreraUsuario
        );

        // Solo consultar proyectos en la base de datos cuando exista una búsqueda o filtro activo
        if (!$busquedaActiva) {
            $resultados = [];
            $totalResults = 0;
            $totalPages = 1;
            $page = 1;
        } elseif ($usar_ia && $q !== '') {
            // Modo IA: búsqueda semántica con paginación real por página
            $todosSemanticos = $model->buscarSemantico($q, $filtrosExtra);
            $totalResults = count($todosSemanticos);
            $totalPages = ($totalResults > 0 && $limit > 0) ? (int)ceil($totalResults / $limit) : 1;
            if ($page > $totalPages) {
                $page = $totalPages;
            }
            $offset = ($page - 1) * $limit;
            $resultados = array_slice($todosSemanticos, $offset, $limit);
        } else {
            // Modo estándar SQL con ranking de relevancia y paginación validada
            $totalResults = $model->buscarPSTCount($q, $filtrosExtra);
            $totalPages = ($totalResults > 0 && $limit > 0) ? (int)ceil($totalResults / $limit) : 1;
            if ($page > $totalPages) {
                $page = $totalPages;
            }
            $offset = ($page - 1) * $limit;
            $resultados = $totalResults > 0 ? $model->buscarPST($q, $filtrosExtra, $limit, $offset) : [];
        }
        
        // Cargar colecciones para poblar selectores
        $carreras     = $model->getCarreras();
        $lineas       = $model->getLineasInvestigacion($carreraId);
        $dimensiones  = $model->getDimensionesOperativas();
        $anioCounts   = $model->getPSTCountByYear($filtrosExtra);
        
        $nivelesAcademicos  = $model->getNivelesAcademicos();
        $nivelesConTrayecto = $model->getNivelesConTrayecto();
        $labelsNiveles      = $model->getNivelAcademicoMap();
        $trayectos          = $model->getTrayectosNombres($carreraId);
        
        return [
            'resultados'         => $resultados,
            'busquedaActiva'     => $busquedaActiva,
            'q'                  => $q,
            'usar_ia'            => $usar_ia,
            'carreras'           => $carreras,
            'lineas'             => $lineas,
            'dimensiones'        => $dimensiones,
            'nivelesAcademicos'  => $nivelesAcademicos,
            'nivelesConTrayecto' => $nivelesConTrayecto,
            'labelsNiveles'      => $labelsNiveles,
            'trayectos'          => $trayectos,
            'anioCounts'         => $anioCounts,
            'filtros'            => $filtrosExtra,
            'pagination'   => [
                'current_page' => $page,
                'total_pages'  => $totalPages,
                'total_items'  => $totalResults,
                'limit'        => $limit
            ]
        ];
    }
}



