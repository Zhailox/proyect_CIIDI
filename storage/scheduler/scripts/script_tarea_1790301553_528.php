<?php
/**
 * ═══════════════════════════════════════════════════════════════════════════
 *  Worker: Generación asíncrona de Embeddings Semánticos
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * Este script se ejecuta en SEGUNDO PLANO (vía Cron o el Scheduler del panel)
 * y cumple con la Regla Estricta 2: el procesamiento de la red neuronal
 * NUNCA ocurre dentro del request HTTP del usuario.
 *
 * Flujo por ejecución:
 *   1. Busca proyectos donde vector_semantico IS NULL (LIMIT configurable).
 *   2. Concatena Título + Resumen + Objetivo General + Palabras Clave.
 *   3. Pasa el texto por el modelo ONNX para generar el vector.
 *   4. Almacena el vector en PostgreSQL usando la sintaxis de pgvector.
 *
 * Ubicación recomendada: scripts/generar_embeddings.php
 *
 * Registro en Scheduler:
 *  
 * //═══════════════════════════════════════════════════════════════════════════
 */

// ──────────────────────────────────────────────────────────────────────────
// CONFIGURACIÓN DEL WORKER
// ──────────────────────────────────────────────────────────────────────────

/** Máximo de registros a procesar por ejecución — protección del CPU */
$LIMIT_POR_EJECUCION = 2;

/** Límite de memoria elevado para la carga del DLL de ONNX Runtime */
ini_set('memory_limit', '512M');

/** Tiempo máximo de ejecución del script */
set_time_limit(120);

// ──────────────────────────────────────────────────────────────────────────
// BOOTSTRAP DE LA APLICACIÓN
// ──────────────────────────────────────────────────────────────────────────

/**
 * ⚠ AJUSTAR esta sección según la estructura de tu proyecto.
 * El objetivo es que CORE_PATH y BASE_PATH queden definidos y que
 * Connection::getInstance() funcione correctamente.
 */
$basePath = dirname(__DIR__);  // Sube un nivel desde /scripts/ → raíz del proyecto

if (!defined('BASE_PATH')) {
    define('BASE_PATH', $basePath . DIRECTORY_SEPARATOR);
}

// Intentar cargar el bootstrap principal del proyecto
$bootstrapCandidates = [
    $basePath . '/config/bootstrap.php',
    $basePath . '/init.php',
    $basePath . '/bootstrap.php',
];

$bootstrapLoaded = false;
foreach ($bootstrapCandidates as $candidate) {
    if (file_exists($candidate)) {
        require_once $candidate;
        $bootstrapLoaded = true;
        break;
    }
}

if (!$bootstrapLoaded) {
    // Fallback mínimo: definir CORE_PATH si no existe
    if (!defined('CORE_PATH')) {
        define('CORE_PATH', $basePath . '/core/');
    }
}

// Dependencias obligatorias
require_once CORE_PATH . 'Database/Connection.php';
require_once BASE_PATH . 'vendor/autoload.php';
require_once BASE_PATH . 'modules/RepositorioPST/services/EmbeddingService.php';

// ──────────────────────────────────────────────────────────────────────────
// FUNCIONES AUXILIARES
// ──────────────────────────────────────────────────────────────────────────

/**
 * Logger del worker: escribe tanto en error_log como en stdout (modo CLI).
 */
function logWorker(string $mensaje): void {
    $timestamp = date('Y-m-d H:i:s');
    $linea = "[{$timestamp}] [EmbeddingWorker] {$mensaje}";
    error_log($linea);
    if (php_sapi_name() === 'cli') {
        echo $linea . PHP_EOL;
    }
}

// ──────────────────────────────────────────────────────────────────────────
// EJECUCIÓN PRINCIPAL
// ──────────────────────────────────────────────────────────────────────────

try {
    logWorker("═══ Iniciando ciclo de generación de embeddings ═══");

    $db = Connection::getInstance();

    // ─── PASO 1: Buscar proyectos sin vector semántico ──────────────
    $stmt = $db->prepare("
        SELECT r.id, 
               r.titulo, 
               dp.resumen, 
               dp.palabras_clave, 
               dp.obj_general
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

    logWorker("Encontrados " . count($pendientes) . " proyecto(s) pendientes de vectorización.");

    // ─── PASO 2: Cargar el servicio de embeddings (modelo ONNX) ─────
    logWorker("Cargando modelo ONNX y tokenizer...");
    $embeddingService = new EmbeddingService();
    logWorker("Modelo cargado. Dimensión del vector: " . $embeddingService->getDimension());

    // ─── PASO 3: Procesar cada proyecto pendiente ───────────────────
    $procesados = 0;
    $errores    = 0;

    foreach ($pendientes as $proyecto) {
        $id     = (int)$proyecto['id'];
        $titulo = mb_substr($proyecto['titulo'] ?? '(sin título)', 0, 70);

        logWorker("Procesando recurso ID={$id}: \"{$titulo}\"...");

        try {
            // 3a. Concatenar los campos de texto relevantes
            //     El orden importa: título primero le da más peso semántico implícito
            $textoCompleto = implode('. ', array_filter([
                $proyecto['titulo']         ?? '',
                $proyecto['resumen']        ?? '',
                $proyecto['obj_general']    ?? '',
                $proyecto['palabras_clave'] ?? '',
            ], fn($t) => trim($t) !== ''));

            if (trim($textoCompleto) === '') {
                logWorker("  ⚠ Recurso ID={$id} no tiene texto útil. Omitido.");
                continue;
            }

            // 3b. Generar el embedding vía inferencia ONNX
            $vector = $embeddingService->generarEmbedding($textoCompleto);

            // 3c. Formatear como string pgvector: '[0.123, -0.456, ...]'
            $vectorPgFormat = '[' . implode(',', array_map(
                fn($v) => sprintf('%.8f', $v),
                $vector
            )) . ']';

            // ─── PASO 4: Guardar el vector en PostgreSQL ────────────
            $stmtUpdate = $db->prepare("
                UPDATE public.detalles_proyectos 
                SET vector_semantico = ?::vector 
                WHERE id_recurso = ?
            ");
            $stmtUpdate->execute([$vectorPgFormat, $id]);

            $procesados++;
            logWorker("  ✅ Vector almacenado exitosamente (" . $embeddingService->getDimension() . " dims).");

        } catch (Exception $e) {
            $errores++;
            logWorker("  ❌ Error en ID={$id}: " . $e->getMessage());
        }
    }

    // ─── RESUMEN ────────────────────────────────────────────────────
    logWorker("═══ Ciclo completado. Procesados: {$procesados} | Errores: {$errores} ═══");

} catch (Exception $e) {
    logWorker("❌ ERROR FATAL: " . $e->getMessage());
    logWorker("Traza: " . $e->getTraceAsString());
    exit(1);
}
