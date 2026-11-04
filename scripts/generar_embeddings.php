<?php
/**
 * ═══════════════════════════════════════════════════════════════════════════
 *  Worker: Generación asíncrona de Embeddings Semánticos
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * Este script se ejecuta en SEGUNDO PLANO (vía Cron o el Scheduler del panel)
 * y cumple con la Regla Estricta 2: el procesamiento de la red neuronal
 * NUNCA ocurre dentro del request HTTP del usuario.
 */

date_default_timezone_set('America/Caracas');

/** Límite de memoria elevado para la carga del binario de ONNX Runtime */
ini_set('memory_limit', '512M');

/** Tiempo máximo de ejecución del script */
set_time_limit(120);

$basePath = __DIR__;
while (!file_exists($basePath . '/vendor/autoload.php') && $basePath !== DIRECTORY_SEPARATOR) {
    $basePath = dirname($basePath);
}

if (!defined('BASE_PATH')) {
    define('BASE_PATH', rtrim($basePath, '/\\') . DIRECTORY_SEPARATOR);
}
if (!defined('CORE_PATH')) {
    define('CORE_PATH', BASE_PATH . 'core/');
}
if (!defined('STORAGE_PATH')) {
    define('STORAGE_PATH', BASE_PATH . 'storage/');
}

if (file_exists(CORE_PATH . 'System/Env.php')) {
    require_once CORE_PATH . 'System/Env.php';
    Env::load(BASE_PATH . '.env');
}

require_once CORE_PATH . 'Database/Connection.php';
require_once BASE_PATH . 'vendor/autoload.php';
require_once BASE_PATH . 'modules/RepositorioPST/services/ConfigService.php';
require_once BASE_PATH . 'modules/RepositorioPST/services/EmbeddingService.php';

/** Máximo de registros a procesar por ejecución leído desde ConfigService */
$LIMIT_POR_EJECUCION = max(1, min(25, (int)ConfigService::get('red_onnx.lote_worker_embeddings', 5)));

function logWorker(string $mensaje): void {
    $timestamp = date('Y-m-d H:i:s');
    $linea = "[{$timestamp}] [EmbeddingWorker] {$mensaje}";
    error_log($linea);
    if (php_sapi_name() === 'cli') {
        echo $linea . PHP_EOL;
    }
}

try {
    logWorker("═══ Iniciando ciclo de generación de embeddings ═══");

    $db = Connection::getInstance();

    $stmt = $db->prepare("
        SELECT r.id, 
               r.titulo, 
               dp.resumen, 
               dp.palabras_clave, 
               dp.obj_general,
               dp.comunidad_beneficiada
        FROM public.recursos r
        INNER JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso
        WHERE r.id_tipo_recurso = 1 
          AND dp.vector_semantico IS NULL
        ORDER BY r.id ASC
        LIMIT ?
    ");
    $stmt->execute([$LIMIT_POR_EJECUCION]);
    $pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($pendientes)) {
        logWorker("✓ No hay proyectos pendientes de vectorización. Fin del ciclo.");
        exit(0);
    }

    logWorker("Encontrados " . count($pendientes) . " proyectos pendientes de vectorización.");

    logWorker("Cargando modelo ONNX y tokenizer...");
    $embeddingService = new EmbeddingService();
    logWorker("Modelo cargado. Dimensión del vector: " . $embeddingService->getDimension());

    $procesados = 0;
    $errores    = 0;

    foreach ($pendientes as $proyecto) {
        $id     = (int)$proyecto['id'];
        $titulo = mb_substr($proyecto['titulo'] ?? 'Sin título', 0, 70);

        logWorker("Procesando recurso ID={$id}: \"{$titulo}\"...");

        try {
            $textoCompleto = EmbeddingService::construirTextoSemanticoProyecto($proyecto);

            if (trim($textoCompleto) === '') {
                logWorker("  ⚠ Recurso ID={$id} no tiene texto útil. Omitido.");
                continue;
            }

            $vector = $embeddingService->generarEmbedding($textoCompleto);

            $vectorPgFormat = '[' . implode(',', array_map(
                fn($v) => sprintf('%.8f', $v),
                $vector
            )) . ']';

            $stmtUpdate = $db->prepare("
                UPDATE public.detalles_proyectos 
                SET vector_semantico = ?::vector 
                WHERE id_recurso = ?
            ");
            $stmtUpdate->execute([$vectorPgFormat, $id]);

            $procesados++;
            logWorker("  ✅ Vector almacenado exitosamente - " . $embeddingService->getDimension() . " dims.");

        } catch (\Throwable $e) {
            $errores++;
            logWorker("  ❌ Error en ID={$id}: " . $e->getMessage());
        }
    }

    logWorker("═══ Ciclo completado. Procesados: {$procesados} | Errores: {$errores} ═══");

} catch (\Throwable $e) {
    logWorker("❌ ERROR FATAL: " . $e->getMessage());
    exit(1);
}
