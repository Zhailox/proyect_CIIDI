<?php
/**
 * Tokenizer WordPiece compatible con modelos BERT / Sentence-Transformers.
 *
 * Implementación ligera en PHP puro — no requiere dependencias externas.
 * Lee el vocabulario desde un archivo vocab.txt estándar (una línea por token,
 * el índice de línea es el ID numérico del token).
 *
 * Ubicación: modules/RepositorioPST/services/TokenizerService.php
 */

class TokenizerService {

    /** @var array<string, int> Mapeo token → ID numérico */
    private array $vocab = [];

    /** @var int Longitud máxima de secuencia (incluyendo [CLS] y [SEP]) */
    private int $maxLength;

    /** @var int ID del token [CLS] */
    private int $clsTokenId;

    /** @var int ID del token [SEP] */
    private int $sepTokenId;

    /** @var int ID del token [PAD] */
    private int $padTokenId;

    /** @var int ID del token [UNK] */
    private int $unkTokenId;

    /**
     * @param string $vocabPath Ruta absoluta al archivo vocab.txt del modelo.
     * @param int    $maxLength Longitud máxima de la secuencia tokenizada.
     */
    public function __construct(string $vocabPath, int $maxLength = 128) {
        if (!file_exists($vocabPath)) {
            throw new RuntimeException(
                "Archivo de vocabulario no encontrado: {$vocabPath}"
            );
        }

        $this->maxLength = $maxLength;

        // Cargar vocabulario completo en memoria
        $lines = file($vocabPath, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $index => $token) {
            $this->vocab[$token] = $index;
        }

        // Resolver IDs de tokens especiales (valores estándar BERT como fallback)
        $this->clsTokenId = $this->vocab['[CLS]'] ?? 101;
        $this->sepTokenId = $this->vocab['[SEP]'] ?? 102;
        $this->padTokenId = $this->vocab['[PAD]'] ?? 0;
        $this->unkTokenId = $this->vocab['[UNK]'] ?? 100;
    }

    /**
     * Codifica un texto de entrada en los tres tensores que espera el modelo ONNX:
     *   - input_ids:      IDs numéricos de cada token
     *   - attention_mask:  1 para tokens reales, 0 para padding
     *   - token_type_ids:  0 para todos (single sentence, segment A)
     *
     * @return array{input_ids: int[], attention_mask: int[], token_type_ids: int[]}
     */
    public function encode(string $text): array {
        $text = mb_strtolower(trim($text), 'UTF-8');

        // Pre-tokenización: separar en "palabras" por espacios y puntuación
        $words = $this->preTokenize($text);

        // Construir secuencia: [CLS] + subtokens + [SEP]
        $inputIds = [$this->clsTokenId];

        foreach ($words as $word) {
            $subTokenIds = $this->wordPieceTokenize($word);

            // Verificar que no excedamos el límite (reservar 1 posición para [SEP])
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
        $seqLength    = count($inputIds);
        $attentionMask = array_fill(0, $seqLength, 1);

        // Padding hasta maxLength
        $paddingLength = $this->maxLength - $seqLength;
        if ($paddingLength > 0) {
            $inputIds      = array_merge($inputIds, array_fill(0, $paddingLength, $this->padTokenId));
            $attentionMask = array_merge($attentionMask, array_fill(0, $paddingLength, 0));
        }

        // token_type_ids: ceros (single sentence = segment A)
        $tokenTypeIds = array_fill(0, $this->maxLength, 0);

        return [
            'input_ids'      => $inputIds,
            'attention_mask'  => $attentionMask,
            'token_type_ids'  => $tokenTypeIds,
        ];
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Métodos internos
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Pre-tokenización: inserta espacios alrededor de puntuación para que cada
     * signo se trate como un token independiente, luego divide por espacios.
     *
     * @return string[]
     */
    private function preTokenize(string $text): array {
        $text = preg_replace('/([^\w\s])/u', ' $1 ', $text);
        return array_values(
            array_filter(preg_split('/\s+/u', $text), fn($w) => $w !== '')
        );
    }

    /**
     * Algoritmo WordPiece: descompone una palabra en subtokens presentes
     * en el vocabulario, usando el prefijo "##" para fragmentos internos.
     *
     * Ejemplo: "embeddings" → IDs de ["em", "##bed", "##ding", "##s"]
     *
     * @return int[]
     */
    private function wordPieceTokenize(string $word): array {
        // Protección contra palabras absurdamente largas
        if (mb_strlen($word) > 200) {
            return [$this->unkTokenId];
        }

        $tokens  = [];
        $start   = 0;
        $wordLen = mb_strlen($word);

        while ($start < $wordLen) {
            $end          = $wordLen;
            $foundSubword = false;

            while ($start < $end) {
                $substr = mb_substr($word, $start, $end - $start);

                // Los fragmentos después del primero llevan prefijo "##"
                $candidate = ($start > 0) ? '##' . $substr : $substr;

                if (isset($this->vocab[$candidate])) {
                    $tokens[]     = $this->vocab[$candidate];
                    $foundSubword = true;
                    $start        = $end;   // avanzar más allá de este fragmento
                    break;
                }

                $end--;
            }

            if (!$foundSubword) {
                // Carácter no reconocido → [UNK]
                $tokens[] = $this->unkTokenId;
                $start++;
            }
        }

        return $tokens;
    }
}
