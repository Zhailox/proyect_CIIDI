<?php
// modules/RepositorioPST/services/NeuralClassifier.php

use Phpml\Classification\MLPClassifier;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\FeatureExtraction\TfIdfTransformer;

class NeuralClassifier {
    
    private string $modelPath;
    
    public function __construct() {
        $this->modelPath = BASE_PATH . '/storage/modelos_ia/neural_model.meta';
    }

    /**
     * Limpia y normaliza un texto en español para remover acentos, signos de puntuación y pasarlo a minúsculas.
     */
    public static function cleanText(string $text): string {
        $text = mb_strtolower($text);
        
        $map = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'ü' => 'u', 'ñ' => 'n'
        ];
        $text = strtr($text, $map);
        
        // Quitar caracteres no alfanuméricos manteniendo espacios
        $text = preg_replace('/[^a-z0-9\s]/u', ' ', $text);
        // Colapsar espacios múltiples
        $text = preg_replace('/\s+/u', ' ', $text);
        
        return trim($text);
    }

    /**
     * Verifica si existe un modelo ya entrenado.
     */
    public function existeModelo(): bool {
        return file_exists($this->modelPath);
    }

    /**
     * Retorna los metadatos del modelo entrenado.
     */
    public function getMetadatosModelo(): ?array {
        if (!$this->existeModelo()) {
            return null;
        }
        
        try {
            $data = unserialize(file_get_contents($this->modelPath));
            if (is_array($data)) {
                return [
                    'accuracy' => $data['accuracy'] ?? 0.0,
                    'trained_count' => count($data['trained_ids'] ?? []),
                    'vocabulary_size' => $data['vocabulary_size'] ?? 0,
                    'timestamp' => $data['timestamp'] ?? 0,
                    'config' => $data['config'] ?? [],
                    'trained_ids' => $data['trained_ids'] ?? []
                ];
            }
        } catch (Exception $e) {
            error_log("Error al leer metadatos del modelo IA: " . $e->getMessage());
        }
        return null;
    }

    /**
     * Ejecuta el entrenamiento de la red neuronal basándose en los proyectos actuales.
     * 
     * Explicación del proceso:
     * 1. Extracción de Características:
     *    - Se cargan los textos (resumen + palabras clave) de los proyectos registrados.
     *    - Se tokenizan y vectorizan usando "Bag of Words" (TokenCountVectorizer).
     *    - Se escalan los recuentos usando la técnica de "TF-IDF" (TfIdfTransformer) para
     *      darle peso a los términos significativos del vocabulario.
     * 
     * 2. Red Neuronal (MLPClassifier):
     *    - Capa de Entrada: Tiene tantas neuronas como palabras en el vocabulario ($featuresCount).
     *    - Capa Oculta: Configurable (ej. [16] o [16, 8]). Son capas intermedias densas con funciones de activación.
     *    - Capa de Salida: Tiene tantas neuronas como clases (Líneas de investigación de la BD).
     * 
     * 3. Evaluación:
     *    - Si hay suficientes muestras (>= 6), se dividen para evaluar la fiabilidad (accuracy)
     *      antes de reentrenar en el dataset completo.
     */
    public function entrenarRed(array $config = []): array {
        $model = new DocumentoModel();
        $proyectos = $model->getPSTTrainingData();
        
        // El clasificador necesita al menos 2 clases diferentes y 3 proyectos
        if (count($proyectos) < 3) {
            throw new Exception("Insuficientes datos en la base de datos para entrenar el clasificador (mínimo 3 proyectos clasificados).");
        }
        
        // Obtener parámetros de configuración con valores por defecto
        $hiddenLayers = $config['hidden_layers'] ?? [16];
        $iterations   = $config['iterations'] ?? 1000;
        $learningRate = $config['learning_rate'] ?? 0.1;
        
        $samplesText = [];
        $targets = [];
        $trainedIds = [];
        
        foreach ($proyectos as $p) {
            $samplesText[] = self::cleanText($p['resumen'] . ' ' . $p['palabras_clave']);
            $targets[] = (int)$p['linea_id'];
            $trainedIds[] = (int)$p['id'];
        }
        
        // Encontrar clases únicas (Líneas de investigación)
        $classes = array_values(array_unique($targets));
        if (count($classes) < 2) {
            throw new Exception("El clasificador de red neuronal requiere al menos 2 clases diferentes (Líneas de Investigación asignadas a proyectos) para poder entrenar.");
        }
        
        // 1. Vectorizador de Texto (Bag of Words / TokenCount)
        $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());
        $vectorizer->fit($samplesText);
        $vectorizer->transform($samplesText);
        
        // 2. Transformador de peso TF-IDF
        $transformer = new TfIdfTransformer($samplesText);
        $transformer->transform($samplesText);
        
        $featuresCount = count($samplesText[0]);
        if ($featuresCount <= 0) {
            throw new Exception("El vocabulario extraído está vacío.");
        }
        
        // Evaluar exactitud con división aleatoria (si hay muestras suficientes)
        $accuracy = 1.0;
        if (count($samplesText) >= 6) {
            // Dividir de forma simple (80% train, 20% test)
            $totalCount = count($samplesText);
            $trainCount = (int)round($totalCount * 0.8);
            
            $trainSamples = array_slice($samplesText, 0, $trainCount);
            $trainTargets = array_slice($targets, 0, $trainCount);
            $testSamples  = array_slice($samplesText, $trainCount);
            $testTargets  = array_slice($targets, $trainCount);
            
            $evalMlp = new MLPClassifier(
                $featuresCount,
                $hiddenLayers,
                $classes,
                $iterations,
                null,
                $learningRate
            );
            $evalMlp->train($trainSamples, $trainTargets);
            
            $hits = 0;
            foreach ($testSamples as $index => $sample) {
                $predicted = $evalMlp->predict($sample);
                if ($predicted == $testTargets[$index]) {
                    $hits++;
                }
            }
            $accuracy = $hits / count($testSamples);
        } else {
            // Calcular exactitud sobre el mismo conjunto si hay pocas muestras
            $evalMlp = new MLPClassifier(
                $featuresCount,
                $hiddenLayers,
                $classes,
                $iterations,
                null,
                $learningRate
            );
            $evalMlp->train($samplesText, $targets);
            $hits = 0;
            foreach ($samplesText as $index => $sample) {
                if ($evalMlp->predict($sample) == $targets[$index]) {
                    $hits++;
                }
            }
            $accuracy = $hits / count($samplesText);
        }
        
        // 3. Entrenar el clasificador final en el dataset completo
        $mlp = new MLPClassifier(
            $featuresCount,
            $hiddenLayers,
            $classes,
            $iterations,
            null,
            $learningRate
        );
        $mlp->train($samplesText, $targets);
        
        // 4. Guardar modelo serializado
        $modelData = [
            'vectorizer' => $vectorizer,
            'transformer' => $transformer,
            'mlp' => $mlp,
            'trained_ids' => $trainedIds,
            'accuracy' => $accuracy,
            'vocabulary_size' => $featuresCount,
            'timestamp' => time(),
            'config' => [
                'hidden_layers' => $hiddenLayers,
                'iterations' => $iterations,
                'learning_rate' => $learningRate
            ]
        ];
        
        $storageDir = dirname($this->modelPath);
        if (!file_exists($storageDir)) {
            mkdir($storageDir, 0777, true);
        }
        
        file_put_contents($this->modelPath, serialize($modelData));
        
        return [
            'success' => true,
            'accuracy' => $accuracy,
            'trained_count' => count($trainedIds),
            'vocabulary_size' => $featuresCount
        ];
    }

    /**
     * Predice el ID de la línea de investigación para un texto de búsqueda dado.
     */
    public function predecirCategoria(string $textoBusqueda): ?int {
        if (!$this->existeModelo()) {
            return null;
        }
        
        $data = unserialize(file_get_contents($this->modelPath));
        if (!$data) {
            return null;
        }
        
        $vectorizer = $data['vectorizer'];
        $transformer = $data['transformer'];
        $mlp = $data['mlp'];
        
        $cleaned = self::cleanText($textoBusqueda);
        if ($cleaned === '') {
            return null;
        }
        
        $samples = [$cleaned];
        
        // Aplicar la misma vectorización y transformación TF-IDF
        $vectorizer->transform($samples);
        $transformer->transform($samples);
        
        // Predicción con la red neuronal
        return (int)$mlp->predict($samples[0]);
    }
}
