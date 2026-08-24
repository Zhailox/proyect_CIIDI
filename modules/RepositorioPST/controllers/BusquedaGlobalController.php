<?php
// modules/RepositorioPST/controllers/BusquedaGlobalController.php
require_once __DIR__ . '/../models/DocumentoModel.php';
require_once __DIR__ . '/../services/NeuralClassifier.php';

class BusquedaGlobalController {
    
    public function index(): array {
        $model = new DocumentoModel();
        
        // Parámetros de búsqueda principal y modo (A = Estándar, B = IA / Red Neuronal)
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        $modo = isset($_GET['modo']) ? trim($_GET['modo']) : 'A'; 
        
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
        
        $resultados = [];
        $totalResults = 0;
        
        if ($modo === 'A') {
            // Modo A: Búsqueda SQL estándar con paginación
            $resultados = $model->buscarPST($q, $filtrosExtra, $limit, $offset);
            $totalResults = $model->buscarPSTCount($q, $filtrosExtra);
        } else {
            // Modo B: Red Neuronal Real
            if (!empty($q)) {
                $classifier = new NeuralClassifier();
                if ($classifier->existeModelo()) {
                    $predictedLineaId = $classifier->predecirCategoria($q);
                    $metadata = $classifier->getMetadatosModelo();
                    $accuracy = $metadata['accuracy'] ?? 0.85;
                    
                    if ($predictedLineaId) {
                        // Forzar el filtro por la línea de investigación predicha
                        $filtrosExtra['linea_id'] = $predictedLineaId;
                        
                        $resultados = $model->buscarPST($q, $filtrosExtra, $limit, $offset);
                        $totalResults = $model->buscarPSTCount($q, $filtrosExtra);
                        
                        foreach ($resultados as &$res) {
                            $res['tipo_recurso_nombre'] = 'PST / Proyecto Socio-Tecnológico (Predicción Neuronal)';
                            $res['score'] = number_format($accuracy * 100, 1) . '%';
                            $res['simulado'] = false; // Clasificación real
                        }
                    }
                } else {
                    // Fallback si no hay modelo entrenado aún
                    $mockResultados = [
                        [
                            'id' => 9999,
                            'titulo' => 'ALERTA IA: Modelo de Red Neuronal no entrenado',
                            'tipo_recurso_nombre' => 'Estado del Sistema IA',
                            'proyecto_resumen' => 'El perceptrón multicapa no ha sido entrenado todavía en este servidor. Por favor dirígete a la sección de "Gestión de Red Neuronal" y haz clic en "Entrenar Red Neuronal".',
                            'proyecto_palabras' => 'red neuronal, clasificador, entrenamiento',
                            'anio_publicacion' => (int)date('Y'),
                            'archivo_pdf' => '#',
                            'autores_nombres' => 'Científico de Datos CIIDI',
                            'simulado' => true,
                            'score' => '0.0%'
                        ]
                    ];
                    $totalResults = count($mockResultados);
                    $resultados = array_slice($mockResultados, $offset, $limit);
                }
            }
        }
        
        $totalPages = ceil($totalResults / $limit);
        
        // Cargar colecciones para poblar selectores
        $carreras     = $model->getCarreras();
        $lineas       = $model->getLineasInvestigacion();
        $dimensiones  = $model->getDimensionesOperativas();
        $anioCounts   = $model->getPSTCountByYear();
        
        return [
            'resultados'   => $resultados,
            'q'            => $q,
            'modo'         => $modo,
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

    /**
     * Orquesta el entrenamiento y despliega las estadísticas del perceptrón multicapa.
     */
    public function gestionRed(): array {
        $classifier = new NeuralClassifier();
        $model = new DocumentoModel();
        
        $success = null;
        $error = null;
        
        // Procesar entrenamiento si se solicita por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'entrenar') {
            try {
                $layersRaw = isset($_POST['hidden_layers']) ? trim($_POST['hidden_layers']) : '16';
                // Convertir la cadena de capas ocultas en un array de enteros (ej. "16,8" -> [16, 8])
                $hiddenLayers = array_map('intval', array_filter(explode(',', $layersRaw)));
                if (empty($hiddenLayers)) {
                    $hiddenLayers = [16];
                }
                
                $iterations   = isset($_POST['iterations']) ? max(10, (int)$_POST['iterations']) : 1000;
                $learningRate = isset($_POST['learning_rate']) ? max(0.001, (float)$_POST['learning_rate']) : 0.1;
                
                $config = [
                    'hidden_layers' => $hiddenLayers,
                    'iterations'    => $iterations,
                    'learning_rate' => $learningRate
                ];
                
                $res = $classifier->entrenarRed($config);
                $success = "¡Entrenamiento Exitoso! Exactitud de validación: " . number_format($res['accuracy'] * 100, 2) . "%. Vocabulario indexado: " . $res['vocabulary_size'] . " palabras clave.";
            } catch (Exception $e) {
                $error = "Error durante el entrenamiento del perceptrón: " . $e->getMessage();
            }
        }
        
        // Obtener estadísticas de proyectos entrenados vs no entrenados
        $metadata = $classifier->getMetadatosModelo();
        $proyectosDb = $model->getPSTTrainingData();
        $totalProyectos = count($proyectosDb);
        
        $trainedIds = $metadata['trained_ids'] ?? [];
        $trainedCount = 0;
        $untrainedCount = 0;
        
        foreach ($proyectosDb as $p) {
            if (in_array((int)$p['id'], $trainedIds)) {
                $trainedCount++;
            } else {
                $untrainedCount++;
            }
        }
        
        // Si no existe el modelo, todos están sin entrenar
        if (!$metadata) {
            $untrainedCount = $totalProyectos;
        }
        
        return [
            'success' => $success,
            'error'   => $error,
            'metadata' => $metadata,
            'stats'   => [
                'total' => $totalProyectos,
                'trained' => $trainedCount,
                'untrained' => $untrainedCount
            ]
        ];
    }
}
