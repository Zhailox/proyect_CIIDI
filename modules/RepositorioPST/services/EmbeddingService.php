<?php
/**
 * Servicio de generación de embeddings semánticos usando ONNX Runtime.
 *
 * Encapsula toda la lógica de:
 *   1. Carga del modelo ONNX (Sentence-Transformer)
 *   2. Tokenización WordPiece vía TokenizerService
 *   3. Inferencia y mean pooling
 *   4. Normalización L2 del vector resultante
 *
 * El vector normalizado es apto para almacenarse en pgvector y realizar
 * búsquedas por distancia coseno (operador <=>).
 *
 * Ubicación: modules/RepositorioPST/services/EmbeddingService.php
 */

require_once __DIR__ . '/TokenizerService.php';

class EmbeddingService {

    /** @var \OnnxRuntime\InferenceSession|\OnnxRuntime\Model Sesión ONNX activa */
    private $session;

    /** @var TokenizerService Tokenizer WordPiece */
    private TokenizerService $tokenizer;

    /** @var int Dimensión del vector que produce el modelo */
    private int $dimension;

    /** @var int Longitud máxima de tokens por secuencia */
    private int $maxTokens;

    /** @var string[] Nombres de los inputs que acepta el modelo */
    private array $modelInputNames;

    /**
     * @param string|null $modelPath Ruta absoluta al archivo .onnx del modelo.
     * @param string|null $vocabPath Ruta absoluta al archivo vocab.txt del tokenizer.
     * @param int         $maxTokens Longitud máxima de la secuencia tokenizada (128 es eficiente; 256 si tus resúmenes son extensos).
     */
    public function __construct(
        ?string $modelPath = null,
        ?string $vocabPath = null,
        int     $maxTokens = 128
    ) {
        // ──────────────────────────────────────────────────────────────
        // 1. Garantizar que el runtime nativo C++ está disponible
        // ──────────────────────────────────────────────────────────────
        if (class_exists('\OnnxRuntime\Vendor')) {
            \OnnxRuntime\Vendor::check();
        }

        // ──────────────────────────────────────────────────────────────
        // 2. Resolver rutas por defecto
        // ──────────────────────────────────────────────────────────────
        $basePath = defined('BASE_PATH') ? rtrim(BASE_PATH, '/\\') : dirname(__DIR__, 3);

        $modelPath = $modelPath ?? $basePath . '/vendor/php-ai/rd-test/model.onnx';
        $vocabPath = $vocabPath ?? $basePath . '/vendor/php-ai/rd-test/vocab.txt';

        if (!file_exists($modelPath)) {
            throw new RuntimeException("Modelo ONNX no encontrado en: {$modelPath}");
        }
        if (!file_exists($vocabPath)) {
            throw new RuntimeException(
                "Vocabulario del tokenizer no encontrado en: {$vocabPath}\n" .
                "Descarga vocab.txt del mismo modelo Hugging Face que exportaste a ONNX."
            );
        }

        $this->maxTokens = $maxTokens;

        // ──────────────────────────────────────────────────────────────
        // 3. Cargar el tokenizer WordPiece
        // ──────────────────────────────────────────────────────────────
        $this->tokenizer = new TokenizerService($vocabPath, $maxTokens);

        // ──────────────────────────────────────────────────────────────
        // 4. Cargar el modelo ONNX (compatibilidad con ambas clases)
        // ──────────────────────────────────────────────────────────────
        if (class_exists('\OnnxRuntime\InferenceSession')) {
            $this->session = new \OnnxRuntime\InferenceSession($modelPath);
        } elseif (class_exists('\OnnxRuntime\Model')) {
            $this->session = new \OnnxRuntime\Model($modelPath);
        } else {
            throw new RuntimeException(
                "No se encontró ninguna clase ONNX Runtime compatible.\n" .
                "Verifica que 'ankane/onnxruntime' esté instalado vía Composer."
            );
        }

        // ──────────────────────────────────────────────────────────────
        // 5. Detectar los nombres de inputs del modelo (para no enviar
        //    tensores que el modelo no espera, ej. token_type_ids)
        // ──────────────────────────────────────────────────────────────
        $this->modelInputNames = $this->detectarInputsModelo();

        // ──────────────────────────────────────────────────────────────
        // 6. Detectar la dimensión del embedding con una inferencia de prueba
        // ──────────────────────────────────────────────────────────────
        $this->dimension = $this->detectarDimension();
    }

    // ═══════════════════════════════════════════════════════════════════
    //  API PÚBLICA
    // ═══════════════════════════════════════════════════════════════════

    /**
     * Genera un vector embedding normalizado L2 a partir de un texto libre.
     *
     * @param  string  $texto Texto de entrada (título + resumen + palabras clave + …).
     * @return float[] Vector de dimensión fija (ej. 384 para MiniLM-L6-v2).
     */
    public function generarEmbedding(string $texto): array {
        // Limpiar y truncar
        $texto = $this->preprocesarTexto($texto);

        if (trim($texto) === '') {
            throw new InvalidArgumentException("El texto de entrada está vacío después del preprocesamiento.");
        }

        // Tokenizar
        $tokens = $this->tokenizer->encode($texto);

        // Construir feeds según los inputs que acepta el modelo
        $feeds = $this->construirFeeds($tokens);

        // Inferencia ONNX
        $outputs = $this->session->run(null, $feeds);

        // Extraer embedding: detectar si la salida ya está pooled o necesita mean pooling
        $firstBatchOutput = $outputs[0][0]; // primer elemento del batch

        if (is_array($firstBatchOutput[0])) {
            // Forma [seq_len, hidden_dim] → requiere mean pooling
            $embedding = $this->meanPooling($firstBatchOutput, $tokens['attention_mask']);
        } else {
            // Forma [hidden_dim] → ya viene pooled (sentence_embedding)
            $embedding = $firstBatchOutput;
        }

        // Normalizar L2 para distancia coseno en pgvector
        return $this->normalizarL2($embedding);
    }

    /**
     * Retorna la dimensión del vector que produce este modelo.
     */
    public function getDimension(): int {
        return $this->dimension;
    }

    // ═══════════════════════════════════════════════════════════════════
    //  MÉTODOS INTERNOS
    // ═══════════════════════════════════════════════════════════════════

    /**
     * Preprocesamiento del texto: strip HTML, normalizar espacios, truncar.
     */
    private function preprocesarTexto(string $texto): string {
        // Eliminar HTML residual
        $texto = strip_tags($texto);
        // Normalizar saltos de línea y espacios múltiples
        $texto = preg_replace('/\s+/', ' ', trim($texto));
        // Truncar a ~5000 caracteres para proteger el tokenizer
        if (mb_strlen($texto) > 5000) {
            $texto = mb_substr($texto, 0, 5000);
        }
        return $texto;
    }

    /**
     * Construir el diccionario de feeds (inputs) para la sesión ONNX,
     * enviando solo los tensores que el modelo espera.
     */
    private function construirFeeds(array $tokens): array {
        $feeds = [];

        if (in_array('input_ids', $this->modelInputNames, true)) {
            $feeds['input_ids'] = [$tokens['input_ids']];
        }
        if (in_array('attention_mask', $this->modelInputNames, true)) {
            $feeds['attention_mask'] = [$tokens['attention_mask']];
        }
        if (in_array('token_type_ids', $this->modelInputNames, true)) {
            $feeds['token_type_ids'] = [$tokens['token_type_ids']];
        }

        return $feeds;
    }

    /**
     * Mean Pooling: promedia los embeddings de cada token, ponderados
     * por el attention_mask, para obtener un solo vector por oración.
     *
     * Convierte [seq_len, hidden_dim] → [hidden_dim].
     */
    private function meanPooling(array $tokenEmbeddings, array $attentionMask): array {
        $hiddenDim = count($tokenEmbeddings[0]);
        $pooled    = array_fill(0, $hiddenDim, 0.0);
        $maskSum   = 0.0;

        foreach ($tokenEmbeddings as $i => $tokenVec) {
            $mask = (float)($attentionMask[$i] ?? 0);
            if ($mask > 0) {
                $maskSum += $mask;
                foreach ($tokenVec as $j => $val) {
                    $pooled[$j] += (float)$val * $mask;
                }
            }
        }

        // Evitar división por cero
        if ($maskSum > 0) {
            foreach ($pooled as &$val) {
                $val /= $maskSum;
            }
        }

        return $pooled;
    }

    /**
     * Normalización L2: convierte el vector a norma unitaria.
     * Requisito para que el operador coseno (<=>) de pgvector sea preciso.
     */
    private function normalizarL2(array $vector): array {
        $norma = 0.0;
        foreach ($vector as $val) {
            $norma += $val * $val;
        }
        $norma = sqrt($norma);

        if ($norma > 1e-12) {
            foreach ($vector as &$val) {
                $val = $val / $norma;
            }
        }

        return $vector;
    }

    /**
     * Inspecciona los metadatos del modelo para saber qué inputs acepta.
     * Fallback: asume los 3 inputs estándar de BERT.
     */
    private function detectarInputsModelo(): array {
        try {
            if (method_exists($this->session, 'inputs')) {
                $inputsMeta = $this->session->inputs();
                return array_map(fn($i) => $i['name'], $inputsMeta);
            }
        } catch (\Throwable $e) {
            // Silenciar — se usan los defaults
        }

        // Fallback: inputs estándar de modelos BERT / Sentence-Transformers
        return ['input_ids', 'attention_mask', 'token_type_ids'];
    }

    /**
     * Ejecuta una inferencia mínima para detectar la dimensión del vector.
     */
    private function detectarDimension(): int {
        $testTokens = $this->tokenizer->encode('test');
        $feeds      = $this->construirFeeds($testTokens);
        $outputs    = $this->session->run(null, $feeds);

        $firstBatchOutput = $outputs[0][0];

        if (is_array($firstBatchOutput[0])) {
            // [seq_len, hidden_dim] → la dimensión está en el segundo nivel
            return count($firstBatchOutput[0]);
        }

        // [hidden_dim] → ya está pooled
        return count($firstBatchOutput);
    }
}
