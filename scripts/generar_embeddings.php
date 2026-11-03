<?php
/**
 * Worker de compatibilidad post-migración a MariaDB 10.6.
 * La vectorización ONNX y pgvector fueron reemplazados por índices FULLTEXT + LIKE nativos en MariaDB.
 */
date_default_timezone_set('America/Caracas');
if (php_sapi_name() === 'cli') {
    echo "[" . date('Y-m-d H:i:s') . "] [EmbeddingWorker] Búsqueda híbrida nativa MariaDB 10.6 (FULLTEXT + LIKE) activa. No se requiere vectorización ONNX." . PHP_EOL;
}
exit(0);