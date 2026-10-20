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
require_once __DIR__ . '/ConfigService.php';

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

    /** @var string Ruta del modelo ONNX cargado */
    private string $modelPath;

    /** @var string Ruta del vocabulario cargado */
    private string $vocabPath;

    /**
     * @param string|null $modelPath Ruta absoluta al archivo .onnx del modelo.
     * @param string|null $vocabPath Ruta absoluta al archivo vocab.txt del tokenizer.
     * @param int|null    $maxTokens Longitud máxima de la secuencia tokenizada (si es null, se lee de ConfigService).
     */
    public function __construct(
        ?string $modelPath = null,
        ?string $vocabPath = null,
        ?int    $maxTokens = null
    ) {
        if ($maxTokens === null) {
            $maxTokens = (int)ConfigService::get('red_onnx.max_tokens_secuencia', 128);
        }
        $maxTokens = max(32, min(256, $maxTokens));

        // ──────────────────────────────────────────────────────────────
        // 1. Garantizar que el runtime nativo C++ está disponible
        // ──────────────────────────────────────────────────────────────
        if (class_exists('\OnnxRuntime\Vendor')) {
            $dest = \OnnxRuntime\Vendor::defaultLib();
            if (!file_exists($dest)) {
                throw new RuntimeException("Librería de ONNX Runtime no encontrada en: {$dest}");
            }
        }

        // ──────────────────────────────────────────────────────────────
        // 2. Resolver rutas por defecto
        // ──────────────────────────────────────────────────────────────
        $basePath = defined('BASE_PATH') ? rtrim(BASE_PATH, '/\\') : dirname(__DIR__, 3);

        $this->modelPath = $modelPath ?? $basePath . '/vendor/php-ai/rd-test/model.onnx';
        $this->vocabPath = $vocabPath ?? $basePath . '/vendor/php-ai/rd-test/vocab.txt';

        if (!file_exists($this->modelPath) && class_exists('ZipArchive')) {
            $zipRespaldo = $basePath . '/migrations/El_Monstruo_ONNX_1000PST_Quantized.zip';
            if (file_exists($zipRespaldo)) {
                $zip = new \ZipArchive();
                if ($zip->open($zipRespaldo) === true) {
                    $destinoDir = dirname($this->modelPath);
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $entryName = $zip->getNameIndex($i);
                        $baseEntry = basename((string)$entryName);
                        if ($baseEntry !== '' && !str_ends_with((string)$entryName, '/')) {
                            @copy("zip://{$zipRespaldo}#{$entryName}", $destinoDir . '/' . $baseEntry);
                        }
                    }
                    $zip->close();
                }
            }
        }

        if (!file_exists($this->modelPath)) {
            throw new RuntimeException("Modelo ONNX no encontrado en: {$this->modelPath}");
        }
        if (!file_exists($this->vocabPath)) {
            throw new RuntimeException(
                "Vocabulario del tokenizer no encontrado en: {$this->vocabPath}\n" .
                "Descarga vocab.txt del mismo modelo Hugging Face que exportaste a ONNX."
            );
        }

        $this->maxTokens = $maxTokens;

        // ──────────────────────────────────────────────────────────────
        // 3. Cargar el tokenizer WordPiece
        // ──────────────────────────────────────────────────────────────
        $this->tokenizer = new TokenizerService($this->vocabPath, $this->maxTokens);

        // ──────────────────────────────────────────────────────────────
        // 4. Cargar el modelo ONNX (compatibilidad con ambas clases)
        // ──────────────────────────────────────────────────────────────
        if (class_exists('\OnnxRuntime\InferenceSession')) {
            $this->session = new \OnnxRuntime\InferenceSession($this->modelPath);
        } elseif (class_exists('\OnnxRuntime\Model')) {
            $this->session = new \OnnxRuntime\Model($this->modelPath);
        } else {
            throw new RuntimeException(
                "No se encontró ninguna clase ONNX Runtime compatible.\n" .
                "Verifica que 'ankane/onnxruntime' esté instalado vía Composer."
            );
        }

        // ──────────────────────────────────────────────────────────────
        // 5. Detectar los nombres de inputs del modelo
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
        $texto = $this->preprocesarTexto($texto);

        if (trim($texto) === '') {
            throw new InvalidArgumentException("El texto de entrada está vacío después del preprocesamiento.");
        }

        $tokens = $this->tokenizer->encode($texto);
        $feeds = $this->construirFeeds($tokens);
        $outputs = $this->session->run(null, $feeds);

        $firstBatchOutput = $outputs[0][0];

        if (is_array($firstBatchOutput[0])) {
            $embedding = $this->meanPooling($firstBatchOutput, $tokens['attention_mask']);
        } else {
            $embedding = $firstBatchOutput;
        }

        return $this->normalizarL2($embedding);
    }

    /**
     * Retorna la cantidad de tokens reales (sin padding) que produce un texto dado.
     */
    public function contarTokensActivos(string $texto): int {
        $texto = $this->preprocesarTexto($texto);
        if ($texto === '') return 0;
        $tokens = $this->tokenizer->encode($texto);
        return (int)array_sum($tokens['attention_mask'] ?? []);
    }

    /**
     * Construye el texto combinado de un proyecto PST respetando los pesos y campos configurados en red_onnx.
     */
    public static function construirTextoSemanticoProyecto(array $proyecto): string {
        $pesoTitulo      = max(1, min(3, (int)ConfigService::get('red_onnx.peso_titulo', 2)));
        $incResumen      = (bool)ConfigService::get('red_onnx.incluir_resumen', true);
        $incObjetivo     = (bool)ConfigService::get('red_onnx.incluir_objetivo_general', true);
        $incPalabras     = (bool)ConfigService::get('red_onnx.incluir_palabras_clave', true);
        $incComunidad    = (bool)ConfigService::get('red_onnx.incluir_comunidad', false);

        $segmentos = [];
        $titulo = trim((string)($proyecto['titulo'] ?? ''));
        if ($titulo !== '') {
            for ($i = 0; $i < $pesoTitulo; $i++) {
                $segmentos[] = $titulo;
            }
        }

        if ($incResumen && !empty($proyecto['resumen'])) {
            $segmentos[] = trim((string)$proyecto['resumen']);
        }
        if ($incObjetivo && !empty($proyecto['obj_general'])) {
            $segmentos[] = trim((string)$proyecto['obj_general']);
        }
        if ($incPalabras && !empty($proyecto['palabras_clave'])) {
            $segmentos[] = trim((string)$proyecto['palabras_clave']);
        }
        if ($incComunidad && !empty($proyecto['comunidad_beneficiada'])) {
            $segmentos[] = trim((string)$proyecto['comunidad_beneficiada']);
        }

        $segmentos = array_values(array_filter($segmentos, fn($s) => $s !== ''));
        if (empty($segmentos) && $titulo !== '') {
            return $titulo;
        }

        return implode('. ', $segmentos);
    }

    /**
     * Retorna el diagnóstico completo de salud, archivos, vocabulario y latencia del motor ONNX.
     */
    public static function obtenerDiagnosticoModelo(): array {
        $basePath = defined('BASE_PATH') ? rtrim(BASE_PATH, '/\\') : dirname(__DIR__, 3);
        $dirModelo = $basePath . '/vendor/php-ai/rd-test';
        $modelFile = $dirModelo . '/model.onnx';
        $dataFile  = $dirModelo . '/model.onnx.data';
        $vocabFile = $dirModelo . '/vocab.txt';
        $cfgFile   = $dirModelo . '/tokenizer_config.json';

        $modelExists = file_exists($modelFile);
        $dataExists  = file_exists($dataFile);
        $vocabExists = file_exists($vocabFile);

        $pesoTotalBytes = ($modelExists ? filesize($modelFile) : 0) + ($dataExists ? filesize($dataFile) : 0);
        $pesoTotalMb    = round($pesoTotalBytes / (1024 * 1024), 2);

        $vocabTokensCount = 0;
        if ($vocabExists) {
            $lines = @file($vocabFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $vocabTokensCount = is_array($lines) ? count($lines) : 0;
        }

        $tokenizerMeta = [];
        if (file_exists($cfgFile)) {
            $tokenizerMeta = @json_decode((string)@file_get_contents($cfgFile), true) ?: [];
        }

        $libNativaRuta = null;
        $libNativaOk = false;
        if (class_exists('\OnnxRuntime\Vendor')) {
            try {
                $libNativaRuta = \OnnxRuntime\Vendor::defaultLib();
                $libNativaOk = $libNativaRuta && file_exists($libNativaRuta);
            } catch (\Throwable $e) {
                $libNativaOk = false;
            }
        }

        $operativo = false;
        $dimension = 384;
        $latenciaMs = 0.0;
        $normaL2 = 0.0;
        $inputsDetectados = ['input_ids', 'attention_mask', 'token_type_ids'];
        $mensajeEstado = 'Motor ONNX listo y operativo';

        try {
            $t0 = microtime(true);
            $instancia = new self();
            $vecPrueba = $instancia->generarEmbedding('Sistema web de gestión académica e inteligencia artificial');
            $latenciaMs = round((microtime(true) - $t0) * 1000, 2);
            $dimension = $instancia->getDimension();
            $inputsDetectados = $instancia->getInputsDetectados();

            $sumaCuadrados = 0.0;
            foreach ($vecPrueba as $v) {
                $sumaCuadrados += ($v * $v);
            }
            $normaL2 = round(sqrt($sumaCuadrados), 4);
            $operativo = true;
        } catch (\Throwable $e) {
            $operativo = false;
            $mensajeEstado = $e->getMessage();
        }

        return [
            'operativo'          => $operativo,
            'mensaje_estado'     => $mensajeEstado,
            'lib_nativa_ok'      => $libNativaOk,
            'lib_nativa_nombre'  => $libNativaRuta ? basename($libNativaRuta) : 'No detectada',
            'modelo_existe'      => $modelExists,
            'pesos_existen'      => $dataExists,
            'vocab_existe'       => $vocabExists,
            'peso_total_mb'      => $pesoTotalMb,
            'vocab_total_tokens' => $vocabTokensCount,
            'tokenizer_clase'    => $tokenizerMeta['tokenizer_class'] ?? 'BertTokenizer',
            'model_max_length'   => (int)($tokenizerMeta['model_max_length'] ?? 256),
            'dimension'          => $dimension,
            'latencia_ms'        => $latenciaMs,
            'norma_l2'           => $normaL2,
            'inputs_modelo'      => $inputsDetectados
        ];
    }

    /**
     * Retorna la dimensión del vector que produce este modelo.
     */
    public function getDimension(): int {
        return $this->dimension;
    }

    /**
     * Retorna los nombres de los tensores de entrada que acepta el modelo.
     */
    public function getInputsDetectados(): array {
        return $this->modelInputNames;
    }

    // ═══════════════════════════════════════════════════════════════════
    //  MÉTODOS INTERNOS
    // ═══════════════════════════════════════════════════════════════════

    private function preprocesarTexto(string $texto): string {
        $texto = strip_tags($texto);
        $texto = preg_replace('/\s+/', ' ', trim($texto));
        if (mb_strlen($texto) > 5000) {
            $texto = mb_substr($texto, 0, 5000);
        }
        return $texto;
    }

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

        if ($maskSum > 0) {
            foreach ($pooled as &$val) {
                $val /= $maskSum;
            }
        }

        return $pooled;
    }

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

    private function detectarInputsModelo(): array {
        try {
            if (method_exists($this->session, 'inputs')) {
                $inputsMeta = $this->session->inputs();
                return array_map(fn($i) => $i['name'], $inputsMeta);
            }
        } catch (\Throwable $e) {
            // Silenciar — se usan los defaults
        }

        return ['input_ids', 'attention_mask', 'token_type_ids'];
    }

    private function detectarDimension(): int {
        $testTokens = $this->tokenizer->encode('test');
        $feeds      = $this->construirFeeds($testTokens);
        $outputs    = $this->session->run(null, $feeds);

        $firstBatchOutput = $outputs[0][0];

        if (is_array($firstBatchOutput[0])) {
            return count($firstBatchOutput[0]);
        }

        return count($firstBatchOutput);
    }
}
