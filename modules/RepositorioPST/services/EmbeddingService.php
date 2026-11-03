<?php
/**
 * Servicio de compatibilidad de indexación de texto (MariaDB 10.6 FULLTEXT).
 *
 * Se ha eliminado el uso de modelos de Machine Learning (ONNX / XLM-RoBERTa),
 * subprocesos externos (proc_open / CLI) y vectores (pgvector).
 *
 * Ubicación: modules/RepositorioPST/services/EmbeddingService.php
 */

require_once __DIR__ . '/ConfigService.php';

class EmbeddingService {

    private int $dimension = 0;
    private int $maxTokens = 128;

    public function __construct(
        ?string $modelPath = null,
        ?string $vocabPath = null,
        ?int    $maxTokens = null
    ) {
        $this->maxTokens = $maxTokens ?? 128;
    }

    public static function asegurarModeloDisponible(string $basePath): void {
        // Desactivado en producción: no se utilizan binarios ONNX ni extracción de modelos ML.
    }

    public function generarEmbedding(string $texto): array {
        return [];
    }

    public function contarTokensActivos(string $texto): int {
        $palabras = preg_split('/\s+/u', trim(strip_tags($texto)), -1, PREG_SPLIT_NO_EMPTY);
        return is_array($palabras) ? count($palabras) : 0;
    }

    public static function construirTextoSemanticoProyecto(array $proyecto): string {
        $segmentos = [];
        foreach (['titulo', 'resumen', 'obj_general', 'palabras_clave', 'comunidad_beneficiada'] as $campo) {
            if (!empty($proyecto[$campo])) {
                $segmentos[] = trim((string)$proyecto[$campo]);
            }
        }
        return implode('. ', $segmentos);
    }

    public static function obtenerDiagnosticoModelo(): array {
        return [
            'operativo'          => true,
            'mensaje_estado'     => 'Motor nativo MariaDB FULLTEXT + LIKE activo (Sin dependencias de IA/ML)',
            'arquitectura'       => 'MariaDB 10.6 Native Hybrid Search (FULLTEXT + LIKE)',
            'es_xlm_roberta'     => false,
            'lib_nativa_ok'      => true,
            'lib_nativa_nombre'  => 'InnoDB FULLTEXT Index',
            'modelo_existe'      => false,
            'pesos_existen'      => false,
            'vocab_existe'       => false,
            'peso_total_mb'      => 0.0,
            'vocab_total_tokens' => 0,
            'tokenizer_clase'    => 'SQL Natural Language Mode',
            'model_max_length'   => 128,
            'dimension'          => 0,
            'latencia_ms'        => 0.5,
            'norma_l2'           => 1.0,
            'inputs_modelo'      => []
        ];
    }

    public function getDimension(): int {
        return $this->dimension;
    }

    public function getInputsDetectados(): array {
        return [];
    }

    public function getVocabSize(): int {
        return 0;
    }

    public function esSentencePiece(): bool {
        return false;
    }
}
