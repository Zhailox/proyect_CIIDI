<?php
/**
 * Tokenizer híbrido SentencePiece Unigram XLM-RoBERTa y WordPiece BERT.
 *
 * Implementación ligera en PHP puro optimizada para el modelo
 * paraphrase-multilingual-MiniLM-L12-v2 con arquitectura XLM-RoBERTa
 * de 250.002 tokens, manteniendo compatibilidad automática con BERT WordPiece.
 *
 * Ubicación: modules/RepositorioPST/services/TokenizerService.php
 */

class TokenizerService {

    /** @var array<string, int> Mapeo token → ID numérico */
    private array $vocab = [];

    /** @var int Longitud máxima de secuencia incluyendo tokens de inicio y cierre */
    private int $maxLength;

    /** @var int ID del token de inicio de secuencia: <s> = 0 en XLM-RoBERTa, [CLS] = 101 en BERT */
    private int $clsTokenId;

    /** @var int ID del token de cierre de secuencia: </s> = 2 en XLM-RoBERTa, [SEP] = 102 en BERT */
    private int $sepTokenId;

    /** @var int ID del token de relleno: <pad> = 1 en XLM-RoBERTa, [PAD] = 0 en BERT */
    private int $padTokenId;

    /** @var int ID del token desconocido: <unk> = 3 en XLM-RoBERTa, [UNK] = 100 en BERT */
    private int $unkTokenId;

    /** @var bool Indica si el vocabulario activo corresponde a SentencePiece Unigram XLM-RoBERTa */
    private bool $isSentencePiece = false;

    /** @var string Prefijo metaspace de SentencePiece U+2581 */
    private const METASPACE = "\xE2\x96\x81";

    /**
     * @param string $vocabPath Ruta absoluta al archivo vocab.txt del modelo.
     * @param int    $maxLength Longitud máxima de la secuencia tokenizada.
     */
    public function __construct(string $vocabPath, int $maxLength = 128) {
        if (!file_exists($vocabPath)) {
            $dir = dirname($vocabPath);
            $tokJsonPath = $dir . '/tokenizer.json';
            if (file_exists($tokJsonPath)) {
                self::generarVocabDesdeTokenizerJson($tokJsonPath, $vocabPath);
            }
        }

        if (!file_exists($vocabPath)) {
            throw new RuntimeException(
                "Archivo de vocabulario no encontrado: {$vocabPath}"
            );
        }

        $this->maxLength = max(32, min(256, $maxLength));

        // Cargar vocabulario completo en memoria
        $lines = file($vocabPath, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $index => $token) {
            $this->vocab[$token] = $index;
        }

        // Detectar arquitectura XLM-RoBERTa SentencePiece vs BERT WordPiece
        if (isset($this->vocab['<s>'], $this->vocab['<pad>'], $this->vocab['</s>'])) {
            $this->isSentencePiece = true;
            $this->clsTokenId      = $this->vocab['<s>'];
            $this->padTokenId      = $this->vocab['<pad>'];
            $this->sepTokenId      = $this->vocab['</s>'];
            $this->unkTokenId      = $this->vocab['<unk>'] ?? 3;
        } else {
            $this->isSentencePiece = false;
            $this->clsTokenId      = $this->vocab['[CLS]'] ?? 101;
            $this->sepTokenId      = $this->vocab['[SEP]'] ?? 102;
            $this->padTokenId      = $this->vocab['[PAD]'] ?? 0;
            $this->unkTokenId      = $this->vocab['[UNK]'] ?? 100;
        }
    }

    /**
     * Construye automáticamente vocab.txt a partir de tokenizer.json de Hugging Face.
     */
    public static function generarVocabDesdeTokenizerJson(string $tokenizerJsonPath, string $vocabDestPath): bool {
        if (!file_exists($tokenizerJsonPath)) {
            return false;
        }
        $raw = @file_get_contents($tokenizerJsonPath);
        if ($raw === false) {
            return false;
        }
        $data = @json_decode($raw, true);
        if (!is_array($data) || empty($data['model']['vocab'])) {
            return false;
        }

        $vocabEntries = $data['model']['vocab'];
        $tokens = [];

        // En Unigram XLM-RoBERTa vocab es una lista de pares [token, score]
        // En WordPiece BERT vocab es un mapa asociativo token => id
        $firstKey = array_key_first($vocabEntries);
        if (is_int($firstKey) && is_array($vocabEntries[$firstKey])) {
            foreach ($vocabEntries as $pair) {
                $tokens[] = (string)($pair[0] ?? '');
            }
        } else {
            asort($vocabEntries);
            $tokens = array_keys($vocabEntries);
        }

        if (empty($tokens)) {
            return false;
        }

        return @file_put_contents($vocabDestPath, implode("\n", $tokens)) !== false;
    }

    /**
     * Indica si el tokenizer activo opera en modo SentencePiece Unigram XLM-RoBERTa.
     */
    public function esSentencePiece(): bool {
        return $this->isSentencePiece;
    }

    /**
     * Retorna el tamaño total del vocabulario cargado en memoria.
     */
    public function getVocabSize(): int {
        return count($this->vocab);
    }

    /**
     * Codifica un texto de entrada en los tensores que espera el modelo ONNX:
     *   - input_ids:      IDs numéricos de cada token
     *   - attention_mask: 1 para tokens reales, 0 para padding
     *   - token_type_ids: 0 para todos
     *
     * @return array{input_ids: int[], attention_mask: int[], token_type_ids: int[]}
     */
    public function encode(string $text): array {
        $text = mb_strtolower(trim($text), 'UTF-8');

        $words = $this->preTokenize($text);

        // Construir secuencia: <s> + subtokens + </s>
        $inputIds = [$this->clsTokenId];

        foreach ($words as $word) {
            $subTokenIds = $this->isSentencePiece
                ? $this->sentencePieceTokenize($word)
                : $this->wordPieceTokenize($word);

            // Verificar que no excedamos el límite reservando 1 posición para el token de cierre
            if (count($inputIds) + count($subTokenIds) >= $this->maxLength - 1) {
                $espacioDisponible = $this->maxLength - 1 - count($inputIds);
                if ($espacioDisponible > 0) {
                    $inputIds = array_merge(
                        $inputIds,
                        array_slice($subTokenIds, 0, $espacioDisponible)
                    );
                }
                break;
            }

            $inputIds = array_merge($inputIds, $subTokenIds);
        }

        $inputIds[] = $this->sepTokenId;

        // Attention mask: 1 para cada token real
        $seqLength     = count($inputIds);
        $attentionMask = array_fill(0, $seqLength, 1);

        // Padding hasta maxLength usando el padTokenId nativo del modelo: 1 en XLM-RoBERTa, 0 en BERT
        $paddingLength = $this->maxLength - $seqLength;
        if ($paddingLength > 0) {
            $inputIds      = array_merge($inputIds, array_fill(0, $paddingLength, $this->padTokenId));
            $attentionMask = array_merge($attentionMask, array_fill(0, $paddingLength, 0));
        }

        $tokenTypeIds = array_fill(0, $this->maxLength, 0);

        return [
            'input_ids'      => $inputIds,
            'attention_mask' => $attentionMask,
            'token_type_ids' => $tokenTypeIds,
        ];
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Métodos internos
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Pre-tokenización adaptada según la arquitectura del modelo.
     *
     * @return string[]
     */
    private function preTokenize(string $text): array {
        if ($this->isSentencePiece) {
            // Limpiar comillas tipográficas y delimitadores que impedirían el prefijo metaspace ▁
            $text = preg_replace('/[“”"\'\(\)\[\]\{\}«»]/u', ' ', $text);
            return array_values(
                array_filter(preg_split('/\s+/u', trim($text)), fn($w) => $w !== '')
            );
        }

        $text = preg_replace('/([^\w\s])/u', ' $1 ', $text);
        return array_values(
            array_filter(preg_split('/\s+/u', $text), fn($w) => $w !== '')
        );
    }

    /**
     * Algoritmo SentencePiece Unigram XLM-RoBERTa:
     * Antepone el carácter metaspace ▁ al inicio de la palabra y segmenta en
     * sub-palabras presentes en los 250.002 tokens del vocabulario multilingüe.
     *
     * @return int[]
     */
    private function sentencePieceTokenize(string $word): array {
        if (mb_strlen($word, 'UTF-8') > 200) {
            return [$this->unkTokenId];
        }

        $piece = self::METASPACE . $word;

        // Atajo directo si la palabra completa con metaspace existe en el vocabulario
        if (isset($this->vocab[$piece])) {
            return [$this->vocab[$piece]];
        }

        $tokens   = [];
        $start    = 0;
        $pieceLen = mb_strlen($piece, 'UTF-8');

        while ($start < $pieceLen) {
            $end          = $pieceLen;
            $foundSubword = false;

            while ($start < $end) {
                $substr = mb_substr($piece, $start, $end - $start, 'UTF-8');

                if (isset($this->vocab[$substr])) {
                    $tokens[]     = $this->vocab[$substr];
                    $foundSubword = true;
                    $start        = $end;
                    break;
                }

                $end--;
            }

            if (!$foundSubword) {
                $tokens[] = $this->unkTokenId;
                $start++;
            }
        }

        return $tokens;
    }

    /**
     * Algoritmo WordPiece clásico para modelos BERT:
     * Descompone una palabra en subtokens usando el prefijo "##" para fragmentos internos.
     *
     * @return int[]
     */
    private function wordPieceTokenize(string $word): array {
        if (mb_strlen($word, 'UTF-8') > 200) {
            return [$this->unkTokenId];
        }

        $tokens  = [];
        $start   = 0;
        $wordLen = mb_strlen($word, 'UTF-8');

        while ($start < $wordLen) {
            $end          = $wordLen;
            $foundSubword = false;

            while ($start < $end) {
                $substr    = mb_substr($word, $start, $end - $start, 'UTF-8');
                $candidate = ($start > 0) ? '##' . $substr : $substr;

                if (isset($this->vocab[$candidate])) {
                    $tokens[]     = $this->vocab[$candidate];
                    $foundSubword = true;
                    $start        = $end;
                    break;
                }

                $end--;
            }

            if (!$foundSubword) {
                $tokens[] = $this->unkTokenId;
                $start++;
            }
        }

        return $tokens;
    }
}
