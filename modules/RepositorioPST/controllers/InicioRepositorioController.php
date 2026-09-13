<?php
// modules/RepositorioPST/controllers/InicioRepositorioController.php
require_once __DIR__ . '/../models/DocumentoModel.php';
require_once __DIR__ . '/../services/ConfigService.php';

class InicioRepositorioController {
    
    public function index(): array {
        $model = new DocumentoModel();
        
        // Calcular métricas exactas directas de la base de datos sin límites ni fallbacks
        $totalPST = $model->getPSTDocumentosCount();

        $db = Connection::getInstance();
        
        // Autores únicos reales vinculados a PST (tipo 1)
        $stmtAutores = $db->query("SELECT COUNT(DISTINCT ra.id_autor) AS total 
                                   FROM public.recurso_autores ra 
                                   JOIN public.recursos r ON ra.id_recurso = r.id 
                                   WHERE r.id_tipo_recurso = 1");
        $resAutores = $stmtAutores->fetch(PDO::FETCH_ASSOC);
        $cantAutores = $resAutores ? (int)$resAutores['total'] : 0;

        // Comunidades únicas reales vinculadas a PST (tipo 1)
        $stmtComunidades = $db->query("SELECT COUNT(DISTINCT LOWER(TRIM(dp.comunidad_beneficiada))) AS total 
                                       FROM public.detalles_proyectos dp 
                                       JOIN public.recursos r ON dp.id_recurso = r.id 
                                       WHERE r.id_tipo_recurso = 1 
                                         AND dp.comunidad_beneficiada IS NOT NULL 
                                         AND TRIM(dp.comunidad_beneficiada) != ''");
        $resComunidades = $stmtComunidades->fetch(PDO::FETCH_ASSOC);
        $cantComunidades = $resComunidades ? (int)$resComunidades['total'] : 0;
        
        // Conteo por Trayectos (1-4)
        $trayectoCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0];

        // Cargar configuración de paginación y modo de carga (paginador vs lazy_loading)
        $configPaginacion = ConfigService::get('paginacion', []);
        $modoCarga = $configPaginacion['modo_carga'] ?? 'paginador';
        $limitDefault = (int)($configPaginacion['limite_catalogo'] ?? 10);

        // Parámetros de paginación
        $limit = !empty($_GET['limit']) ? max(1, (int)$_GET['limit']) : $limitDefault;
        $page = !empty($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        // Capturar todos los filtros de la URL
        $filtros = [
            'trayecto'        => !empty($_GET['trayecto']) ? trim($_GET['trayecto']) : null,
            'anio'            => !empty($_GET['anio']) ? (int)$_GET['anio'] : null,
            'linea_id'        => !empty($_GET['linea_id']) ? (int)$_GET['linea_id'] : null,
            'dimension_id'    => !empty($_GET['dimension_id']) ? (int)$_GET['dimension_id'] : null,
            'nivel_academico' => !empty($_GET['nivel_academico']) ? trim($_GET['nivel_academico']) : null,
            'orden'           => !empty($_GET['orden']) ? trim($_GET['orden']) : 'desc',
        ];

        // Obtener proyectos paginados según la configuración
        $documentosPaginados = $model->getPSTDocumentos($filtros, $limit, $offset);
        $totalFiltrados = $model->getPSTDocumentosCount($filtros);
        $totalPages = max(1, (int)ceil($totalFiltrados / $limit));

        // Colecciones para poblar selectores y tarjetas dinámicas desde la BD
        $lineas            = $model->getLineasInvestigacion();
        $lineasConConteo   = $model->getPSTCountByLinea();
        $dimensiones       = $model->getDimensionesOperativas();
        $nivelesAcademicos = $model->getNivelesAcademicos();
        $trayectosList     = $model->getTrayectos();
        $anioCounts        = $model->getPSTCountByYear();
        $trayectoCountsBD  = $model->getPSTCountByTrayecto();
        foreach ($trayectoCountsBD as $tNum => $cnt) {
            $trayectoCounts[$tNum] = $cnt;
        }

        return [
            'recientes'         => array_slice($documentosPaginados, 0, 8),
            'documentos'        => $documentosPaginados,
            'totalPST'          => $totalPST,
            'totalAutores'      => $cantAutores,
            'totalComunidades'  => $cantComunidades,
            'trayectoCounts'    => $trayectoCounts,
            'lineas'            => $lineas,
            'lineasConConteo'   => $lineasConConteo,
            'dimensiones'       => $dimensiones,
            'nivelesAcademicos' => $nivelesAcademicos,
            'trayectosList'     => $trayectosList,
            'anioCounts'        => $anioCounts,
            'filtros'           => $filtros,
            'modoCarga'         => $modoCarga,
            'pagination'        => [
                'current_page' => $page,
                'total_pages'  => $totalPages,
                'total_items'  => $totalFiltrados,
                'limit'        => $limit
            ]
        ];
    }
}
