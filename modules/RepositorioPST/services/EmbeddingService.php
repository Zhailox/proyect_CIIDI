<?php
/**
 * Servicio de generación de embeddings semánticos usando ONNX Runtime.
 *
 * Encapsula toda la lógica de:
 *   1. Auto-aprovisionamiento y carga del modelo ONNX paraphrase-multilingual-MiniLM-L12-v2 XLM-RoBERTa
 *   2. Tokenización SentencePiece Unigram / WordPiece vía TokenizerService
 *   3. Inferencia neuronal y Mean Pooling ponderado por máscara de atención
 *   4. Normalización L2 del vector resultante de 384 dimensiones
 *
 * El vector normalizado es apto para almacenarse en PostgreSQL pgvector y realizar
 * búsquedas por distancia coseno mediante el operador <=>.
 *
 * Ubicación: modules/RepositorioPST/services/EmbeddingService.php
 */

require_once __DIR__ . '/TokenizerService.php';
require_once __DIR__ . '/ConfigService.php';

class EmbeddingService {

    /** @var \OnnxRuntime\InferenceSession|\OnnxRuntime\Model|null Sesión ONNX activa */
    private $session = null;

    /** @var bool Si el proceso actual (ej. Apache sin reiniciar) no tiene FFI cargado en memoria, delega a PHP CLI */
    private bool $usarPuenteCli = false;

    /** @var TokenizerService Tokenizer activo */
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

    /** @var array<string, mixed> Caché estático en memoria por proceso PHP para evitar recargas redundantes */
    private static array $runtimeCache = [];

    /**
     * @param string|null $modelPath Ruta absoluta al archivo .onnx del modelo.
     * @param string|null $vocabPath Ruta absoluta al archivo vocab.txt del tokenizer.
     * @param int|null    $maxTokens Longitud máxima de la secuencia tokenizada.
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
                try {
                    @ini_set('memory_limit', '1G');
                    @set_time_limit(300);
                    ob_start();
                    \OnnxRuntime\Vendor::check();
                    ob_end_clean();
                } catch (\Throwable $e) {
                    @ob_end_clean();
                }
            }
            if (!file_exists($dest)) {
                throw new RuntimeException("Librería de ONNX Runtime no encontrada en: {$dest}");
            }
        }

        // ──────────────────────────────────────────────────────────────
        // 2. Resolver rutas por defecto y auto-extraer desde migrations si falta
        // ──────────────────────────────────────────────────────────────
        $basePath = defined('BASE_PATH') ? rtrim(BASE_PATH, '/\\') : dirname(__DIR__, 3);

        self::asegurarModeloDisponible($basePath);

        $this->modelPath = $modelPath ?? $basePath . '/vendor/php-ai/rd-test/model.onnx';
        $this->vocabPath = $vocabPath ?? $basePath . '/vendor/php-ai/rd-test/vocab.txt';

        if (!file_exists($this->modelPath)) {
            throw new RuntimeException("Modelo ONNX no encontrado en: {$this->modelPath}");
        }
        if (!file_exists($this->vocabPath)) {
            throw new RuntimeException("Vocabulario del tokenizer no encontrado en: {$this->vocabPath}");
        }

        $this->maxTokens = $maxTokens;

        $cacheKey = $this->modelPath . '|' . $this->vocabPath . '|' . $this->maxTokens;

        if (isset(self::$runtimeCache[$cacheKey])) {
            $cached                = self::$runtimeCache[$cacheKey];
            $this->tokenizer       = $cached['tokenizer'];
            $this->session         = $cached['session'];
            $this->usarPuenteCli   = $cached['usarPuenteCli'] ?? false;
            $this->modelInputNames = $cached['inputs'];
            $this->dimension       = $cached['dimension'];
            return;
        }

        // ──────────────────────────────────────────────────────────────
        // 3. Cargar el tokenizer SentencePiece Unigram / WordPiece
        // ──────────────────────────────────────────────────────────────
        $this->tokenizer = new TokenizerService($this->vocabPath, $this->maxTokens);

        // ──────────────────────────────────────────────────────────────
        // 4. Cargar el modelo ONNX (directo vía FFI o puente CLI si Apache aún no recargó FFI)
        // ──────────────────────────────────────────────────────────────
        $ffiDisponible = class_exists('FFI');
        if ($ffiDisponible) {
            try {
                if (class_exists('\OnnxRuntime\InferenceSession')) {
                    $this->session = new \OnnxRuntime\InferenceSession($this->modelPath);
                } elseif (class_exists('\OnnxRuntime\Model')) {
                    $this->session = new \OnnxRuntime\Model($this->modelPath);
                } else {
                    throw new RuntimeException(
                        "No se encontró ninguna clase ONNX Runtime compatible. Verifica que ankane/onnxruntime esté instalado."
                    );
                }
                $this->modelInputNames = $this->detectarInputsModelo();
                $this->dimension       = $this->detectarDimension();
            } catch (\Throwable $e) {
                if (stripos($e->getMessage(), 'FFI') !== false) {
                    $this->usarPuenteCli   = true;
                    $this->modelInputNames = ['input_ids', 'attention_mask'];
                    $this->dimension       = 384;
                } else {
                    throw $e;
                }
            }
        } else {
            $this->usarPuenteCli   = true;
            $this->modelInputNames = ['input_ids', 'attention_mask'];
            $this->dimension       = 384;
        }

        self::$runtimeCache[$cacheKey] = [
            'tokenizer'     => $this->tokenizer,
            'session'       => $this->session,
            'usarPuenteCli' => $this->usarPuenteCli,
            'inputs'        => $this->modelInputNames,
            'dimension'     => $this->dimension,
        ];
    }

    /**
     * Auto-descomprime el paquete cuantizado XLM-RoBERTa desde migrations si el binario
     * no está presente en vendor/php-ai/rd-test y regenera vocab.txt si falta.
     */
    public static function asegurarModeloDisponible(string $basePath): void {
        $dirModelo   = $basePath . '/vendor/php-ai/rd-test';
        $modelFile   = $dirModelo . '/model.onnx';
        $vocabFile   = $dirModelo . '/vocab.txt';
        $tokJsonFile = $dirModelo . '/tokenizer.json';
        $cfgFile     = $dirModelo . '/tokenizer_config.json';
        $zipPaquete  = $basePath . '/migrations/El_Monstruo_ONNX_1000PST_Quantized.zip';

        if (!is_dir($dirModelo)) {
            @mkdir($dirModelo, 0777, true);
        }

        // Si no existe model.onnx y está el paquete ZIP en migrations, extraer automáticamente
        if (!file_exists($modelFile) && file_exists($zipPaquete) && class_exists('ZipArchive')) {
            $zip = new ZipArchive();
            if ($zip->open($zipPaquete) === true) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $entryName = $zip->getNameIndex($i);
                    $baseName  = basename($entryName);
                    if ($baseName === '' || substr($entryName, -1) === '/') {
                        continue;
                    }
                    $destName = ($baseName === 'model_quantized.onnx') ? 'model.onnx' : $baseName;
                    $inStream = $zip->getStream($entryName);
                    if ($inStream) {
                        $outStream = @fopen($dirModelo . '/' . $destName, 'wb');
                        if ($outStream) {
                            stream_copy_to_stream($inStream, $outStream);
                            fclose($outStream);
                        }
                        fclose($inStream);
                    }
                }
                $zip->close();

                if (file_exists($dirModelo . '/model.onnx.data')) {
                    @unlink($dirModelo . '/model.onnx.data');
                }
            }
        }

        // Si existe tokenizer.json y falta vocab.txt o vocab.txt quedó desincronizado con XLM-RoBERTa
        $necesitaRegenerarVocab = !file_exists($vocabFile);
        if (!$necesitaRegenerarVocab && file_exists($cfgFile) && file_exists($tokJsonFile)) {
            $meta = @json_decode((string)@file_get_contents($cfgFile), true) ?: [];
            $esXlm = stripos((string)($meta['tokenizer_class'] ?? ''), 'XLMRoberta') !== false;
            if ($esXlm && filesize($vocabFile) < 1000000) {
                $necesitaRegenerarVocab = true;
            }
        }

        if ($necesitaRegenerarVocab && file_exists($tokJsonFile)) {
            TokenizerService::generarVocabDesdeTokenizerJson($tokJsonFile, $vocabFile);
        }
    }

    // ═══════════════════════════════════════════════════════════════════
    //  API PÚBLICA
    // ═══════════════════════════════════════════════════════════════════

    /**
     * Genera un vector embedding normalizado L2 a partir de un texto libre.
     *
     * @param  string  $texto Texto de entrada.
     * @return float[] Vector de 384 dimensiones normalizado L2.
     */
    public function generarEmbedding(string $texto): array {
        $texto = $this->preprocesarTexto($texto);

        if (trim($texto) === '') {
            throw new InvalidArgumentException("El texto de entrada está vacío después del preprocesamiento.");
        }

        if ($this->usarPuenteCli || $this->session === null) {
            return $this->generarEmbeddingViaCli($texto);
        }

        $tokens  = $this->tokenizer->encode($texto);
        $feeds   = $this->construirFeeds($tokens);
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
     * Ejecuta la inferencia ONNX en un subproceso PHP CLI con FFI habilitado cuando Apache aún no ha sido reiniciado.
     */
    private function generarEmbeddingViaCli(string $texto): array {
        $basePath = defined('BASE_PATH') ? rtrim(BASE_PATH, '/\\') : dirname(__DIR__, 3);
        $phpBin   = $this->resolverPhpCliBinario();
        $autoload = $basePath . '/vendor/autoload.php';
        $selfFile = __FILE__;

        $inlineCode = 'require ' . var_export($autoload, true) . '; '
            . 'require_once ' . var_export($selfFile, true) . '; '
            . '$input = stream_get_contents(STDIN); '
            . '$svc = new EmbeddingService(' . var_export($this->modelPath, true) . ', ' . var_export($this->vocabPath, true) . ', ' . (int)$this->maxTokens . '); '
            . 'echo json_encode($svc->generarEmbedding($input));';

        $cmd = escapeshellarg($phpBin)
            . ' -d ffi.enable=true -d memory_limit=1G -d display_errors=0 -r '
            . escapeshellarg($inlineCode);

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];
        $proc = @proc_open($cmd, $descriptors, $pipes, $basePath);
        if (!is_resource($proc)) {
            throw new RuntimeException("No se pudo iniciar PHP CLI para inferencia ONNX.");
        }

        fwrite($pipes[0], $texto);
        fclose($pipes[0]);
        $stdout = (string)stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $stderr = (string)stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        $exitCode = proc_close($proc);

        $posBracket = strpos($stdout, '[');
        if ($posBracket !== false) {
            $stdout = substr($stdout, $posBracket);
        }
        $decoded = @json_decode(trim($stdout), true);
        if (!is_array($decoded) || empty($decoded)) {
            throw new RuntimeException("Error en puente CLI ONNX (código {$exitCode}): " . trim($stderr ?: $stdout));
        }
        return $decoded;
    }

    private function resolverPhpCliBinario(): string {
        if (defined('PHP_BINARY') && PHP_BINARY !== '' && stripos(PHP_BINARY, 'httpd') === false && stripos(PHP_BINARY, 'apache') === false) {
            if (file_exists(PHP_BINARY)) {
                return PHP_BINARY;
            }
        }
        if (PHP_OS_FAMILY === 'Windows') {
            $ver = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION . '.' . PHP_RELEASE_VERSION;
            $candidato = "C:/wamp64/bin/php/php{$ver}/php.exe";
            if (file_exists($candidato)) {
                return $candidato;
            }
            $glob = glob('C:/wamp64/bin/php/php*/php.exe');
            if (!empty($glob)) {
                rsort($glob);
                return $glob[0];
            }
        }
        return 'php';
    }

    /**
     * Retorna la cantidad de tokens reales sin padding que produce un texto dado.
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
        $pesoTitulo   = max(1, min(3, (int)ConfigService::get('red_onnx.peso_titulo', 2)));
        $incResumen   = (bool)ConfigService::get('red_onnx.incluir_resumen', true);
        $incObjetivo  = (bool)ConfigService::get('red_onnx.incluir_objetivo_general', true);
        $incPalabras  = (bool)ConfigService::get('red_onnx.incluir_palabras_clave', true);
        $incComunidad = (bool)ConfigService::get('red_onnx.incluir_comunidad', false);

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
     * Retorna el diagnóstico completo de salud, archivos, vocabulario y latencia del motor ONNX XLM-RoBERTa.
     */
    public static function obtenerDiagnosticoModelo(): array {
        $basePath = defined('BASE_PATH') ? rtrim(BASE_PATH, '/\\') : dirname(__DIR__, 3);
        self::asegurarModeloDisponible($basePath);

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

        $tokenizerMeta = [];
        if (file_exists($cfgFile)) {
            $tokenizerMeta = @json_decode((string)@file_get_contents($cfgFile), true) ?: [];
        }

        $libNativaRuta = null;
        $libNativaOk   = false;
        if (class_exists('\OnnxRuntime\Vendor')) {
            try {
                $libNativaRuta = \OnnxRuntime\Vendor::defaultLib();
                $libNativaOk   = $libNativaRuta && file_exists($libNativaRuta);
            } catch (\Throwable $e) {
                $libNativaOk = false;
            }
        }

        $operativo        = false;
        $dimension        = 384;
        $latenciaMs       = 0.0;
        $normaL2          = 0.0;
        $vocabTokensCount = 250002;
        $esSentencePiece  = true;
        $inputsDetectados = ['input_ids', 'attention_mask'];
        $mensajeEstado    = 'Motor ONNX XLM-RoBERTa listo y operativo';

        try {
            $instancia        = new self();
            $t0               = microtime(true);
            $vecPrueba        = $instancia->generarEmbedding('Sistema web de gestión académica e inteligencia artificial');
            $latenciaMs       = round((microtime(true) - $t0) * 1000, 2);
            $dimension        = $instancia->getDimension();
            $inputsDetectados = $instancia->getInputsDetectados();
            $vocabTokensCount = $instancia->getVocabSize();
            $esSentencePiece  = $instancia->esSentencePiece();

            $sumaCuadrados = 0.0;
            foreach ($vecPrueba as $v) {
                $sumaCuadrados += ($v * $v);
            }
            $normaL2   = round(sqrt($sumaCuadrados), 4);
            $operativo = true;
            $libNativaOk = true;
        } catch (\Throwable $e) {
            $operativo     = false;
            $mensajeEstado = $e->getMessage();
        }

        $arquitecturaNombre = $esSentencePiece
            ? 'paraphrase-multilingual-MiniLM-L12-v2 · XLM-RoBERTa'
            : 'Sentence-Transformer MiniLM-L6-v2 · BERT';

        $tokenizerClase = $esSentencePiece
            ? 'XLMRobertaTokenizer · SentencePiece Unigram'
            : ($tokenizerMeta['tokenizer_class'] ?? 'BertTokenizer · WordPiece');

        return [
            'operativo'          => $operativo,
            'mensaje_estado'     => $mensajeEstado,
            'arquitectura'       => $arquitecturaNombre,
            'es_xlm_roberta'     => $esSentencePiece,
            'lib_nativa_ok'      => $libNativaOk,
            'lib_nativa_nombre'  => $libNativaRuta ? basename($libNativaRuta) : 'No detectada',
            'modelo_existe'      => $modelExists,
            'pesos_existen'      => $dataExists,
            'vocab_existe'       => $vocabExists,
            'peso_total_mb'      => $pesoTotalMb,
            'vocab_total_tokens' => $vocabTokensCount,
            'tokenizer_clase'    => $tokenizerClase,
            'model_max_length'   => (int)($tokenizerMeta['model_max_length'] ?? 128),
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

    /**
     * Retorna el tamaño total del vocabulario activo.
     */
    public function getVocabSize(): int {
        return $this->tokenizer->getVocabSize();
    }

    /**
     * Indica si el tokenizer activo es SentencePiece Unigram XLM-RoBERTa.
     */
    public function esSentencePiece(): bool {
        return $this->tokenizer->esSentencePiece();
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
            // Usar entradas estándar de XLM-RoBERTa si no se pueden inspeccionar
        }

        return ['input_ids', 'attention_mask'];
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
