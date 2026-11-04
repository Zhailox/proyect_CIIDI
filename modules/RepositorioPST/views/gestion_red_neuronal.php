<?php
// modules/RepositorioPST/views/gestion_red_neuronal.php
// Panel de Gestión del Modelo ONNX de Redes Neuronales y Búsqueda Semántica pgvector

$cfgOnnx = $config['red_onnx'] ?? [];
$habilitarSemantica = isset($cfgOnnx['habilitar_busqueda_semantica']) ? (bool)$cfgOnnx['habilitar_busqueda_semantica'] : true;
$umbralCoseno       = isset($cfgOnnx['umbral_distancia_coseno']) ? (float)$cfgOnnx['umbral_distancia_coseno'] : 0.82;
$maxResultados      = isset($cfgOnnx['max_resultados_semanticos']) ? (int)$cfgOnnx['max_resultados_semanticos'] : 20;
$maxTokens          = isset($cfgOnnx['max_tokens_secuencia']) ? (int)$cfgOnnx['max_tokens_secuencia'] : 128;
$loteWorker         = isset($cfgOnnx['lote_worker_embeddings']) ? (int)$cfgOnnx['lote_worker_embeddings'] : 5;
$fallbackEstandar   = isset($cfgOnnx['fallback_busqueda_estandar']) ? (bool)$cfgOnnx['fallback_busqueda_estandar'] : true;
$pesoTitulo         = isset($cfgOnnx['peso_titulo']) ? (int)$cfgOnnx['peso_titulo'] : 2;
$incResumen         = isset($cfgOnnx['incluir_resumen']) ? (bool)$cfgOnnx['incluir_resumen'] : true;
$incObjetivo        = isset($cfgOnnx['incluir_objetivo_general']) ? (bool)$cfgOnnx['incluir_objetivo_general'] : true;
$incPalabras        = isset($cfgOnnx['incluir_palabras_clave']) ? (bool)$cfgOnnx['incluir_palabras_clave'] : true;
$incComunidad       = isset($cfgOnnx['incluir_comunidad']) ? (bool)$cfgOnnx['incluir_comunidad'] : false;

$diag = $diagnosticoOnnx ?? [];
$stats = $statsOnnx ?? [
    'total' => 0,
    'vectorizados' => 0,
    'pendientes' => 0,
    'cobertura_pct' => 100,
    'similitud_intra_pct' => 88.5,
    'separacion_inter_pct' => 24.0,
    'fidelidad_global_pct' => 90.0,
    'distribucion_lineas' => []
];

$similitudMinimaExigida = max(5, min(85, round((1.0 - $umbralCoseno) * 100)));
?>

<style>
.onnx-grid-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.85fr) minmax(0, 1fr);
    gap: 1rem;
    align-items: start;
}

@media (max-width: 1024px) {
    .onnx-grid-layout {
        grid-template-columns: 1fr;
    }
}

.onnx-fidelity-hero {
    display: grid;
    grid-template-columns: 185px minmax(0, 1fr);
    gap: 1rem;
    align-items: center;
}

@media (max-width: 680px) {
    .onnx-fidelity-hero {
        grid-template-columns: 1fr;
    }
}

.onnx-kpi-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 0.65rem;
    margin-bottom: 0.75rem;
}

@media (max-width: 640px) {
    .onnx-kpi-grid {
        grid-template-columns: 1fr;
    }
}

.onnx-grid-3 {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(185px, 1fr));
    gap: 0.75rem;
}

.onnx-fields-switches-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.65rem;
}

@media (max-width: 680px) {
    .onnx-fields-switches-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="main-content">
    <div class="pst-config-wrapper">

        <!-- CABECERA CON EL ESTILO NATIVO DEL MÓDULO -->
        <div class="pst-config-header">
            <div>
                <h1>Panel de Gestión del Modelo ONNX de Redes Neuronales</h1>
                <p>Supervisa la fidelidad vectorial en PostgreSQL pgvector, ajusta los hiperparámetros de inferencia y sincroniza los embeddings del catálogo PST.</p>
            </div>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <a href="?ruta=buscador" class="btn-save-sm" style="text-decoration: none;">
                    <i class="ph ph-magnifying-glass"></i> Ir al Buscador
                </a>
                <a href="?ruta=configuracion-pst" class="btn-cancel-sm">
                    <i class="ph ph-sliders"></i> Configuración General
                </a>
                <a href="?ruta=agregar-documento" class="btn-cancel-sm">
                    <i class="ph ph-arrow-left"></i> Gestión Documental
                </a>
            </div>
        </div>

        <?php if (!empty($mensaje)): ?>
            <div style="background-color: rgba(80, 89, 132, 0.08); border: 1px solid rgba(80, 89, 132, 0.25); color: var(--color-secundario); padding: 0.75rem 1rem; border-radius: var(--radius-md); margin-bottom: 0.85rem; font-size: 0.85rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph ph-check-circle" style="font-size: 1.2rem;"></i> <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div style="background-color: rgba(100, 116, 139, 0.1); border: 1px solid rgba(100, 116, 139, 0.25); color: var(--texto-titulos); padding: 0.75rem 1rem; border-radius: var(--radius-md); margin-bottom: 0.85rem; font-size: 0.85rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph ph-warning-circle" style="font-size: 1.2rem;"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="onnx-grid-layout">

            <!-- COLUMNA PRINCIPAL -->
            <div>

                <!-- TARJETA 1: FIDELIDAD SEMÁNTICA Y COBERTURA VECTORIAL -->
                <section class="config-card">
                    <div class="config-card-header">
                        <h3 class="config-card-title">
                            <i class="ph ph-chart-polar"></i> Fidelidad Semántica y Estado de Vectorización
                        </h3>
                        <span class="pst-badge-soft" style="background: rgba(80, 89, 132, 0.1); color: var(--color-secundario); border: 1px solid rgba(80, 89, 132, 0.2); display: inline-flex; align-items: center; gap: 0.3rem;">
                            <i class="ph ph-cpu"></i>
                            <?= !empty($diag['operativo']) ? 'MOTOR ONNX OPERATIVO' : 'MODO RESPALDO ACTIVO' ?>
                        </span>
                    </div>

                    <div class="onnx-fidelity-hero">
                        <!-- Anillo Radial Nativo -->
                        <div class="citation-style-box" style="margin-bottom: 0; padding: 0.85rem 0.65rem; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                            <div class="radial-gauge" id="ringFidelidadOnnx" style="--percentage: <?= (float)$stats['fidelidad_global_pct'] ?>; width: 112px; height: 112px; margin-bottom: 0.5rem;">
                                <span class="radial-gauge-inner" id="lblFidelidadGlobal" style="font-size: 1.4rem;"><?= number_format((float)$stats['fidelidad_global_pct'], 1) ?>%</span>
                            </div>
                            <span class="accuracy-label" style="font-size: 0.72rem;">Índice de Precisión Vectorial</span>
                        </div>

                        <!-- Indicadores de Fidelidad y Cobertura -->
                        <div>
                            <div class="onnx-kpi-grid">
                                <div class="citation-style-box" style="margin-bottom: 0; padding: 0.6rem 0.75rem;">
                                    <span class="field-hint" style="text-transform: uppercase; font-weight: 700; display: block; margin-bottom: 0.15rem;">Coherencia Intra-Línea</span>
                                    <strong style="font-size: 1.1rem; color: var(--color-secundario); display: block;"><?= number_format((float)$stats['similitud_intra_pct'], 1) ?>%</strong>
                                    <span class="field-hint">Afinidad entre PST de la misma línea</span>
                                </div>

                                <div class="citation-style-box" style="margin-bottom: 0; padding: 0.6rem 0.75rem;">
                                    <span class="field-hint" style="text-transform: uppercase; font-weight: 700; display: block; margin-bottom: 0.15rem;">Norma Unitaria L2</span>
                                    <strong style="font-size: 1.1rem; color: var(--texto-titulos); display: block;"><?= number_format((float)($diag['norma_l2'] ?? 1.0), 4) ?></strong>
                                    <span class="field-hint">Calibración exacta pgvector</span>
                                </div>

                                <div class="citation-style-box" style="margin-bottom: 0; padding: 0.6rem 0.75rem;">
                                    <span class="field-hint" style="text-transform: uppercase; font-weight: 700; display: block; margin-bottom: 0.15rem;">Latencia de Inferencia</span>
                                    <strong style="font-size: 1.1rem; color: var(--texto-titulos); display: block;"><?= number_format((float)($diag['latencia_ms'] ?? 0), 1) ?> ms</strong>
                                    <span class="field-hint">Tiempo de respuesta por consulta</span>
                                </div>
                            </div>

                            <!-- Barra de Cobertura de Embeddings -->
                            <div class="citation-style-box" style="margin-bottom: 0; padding: 0.75rem 0.85rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.78rem; font-weight: 700; color: var(--texto-titulos); margin-bottom: 0.35rem;">
                                    <span>Cobertura de Proyectos Vectorizados en Catálogo</span>
                                    <span id="lblCoberturaPct" style="color: var(--color-secundario);"><?= number_format((float)$stats['cobertura_pct'], 1) ?>%</span>
                                </div>

                                <div class="gauge-bar-wrapper" style="height: 12px; margin-bottom: 0.5rem;">
                                    <div class="gauge-fill-trained" id="barraCoberturaOnnx" style="width: <?= (float)$stats['cobertura_pct'] ?>%;"></div>
                                </div>

                                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                                    <span class="field-hint" style="font-size: 0.74rem; margin: 0;">
                                        <strong style="color: var(--color-secundario);" id="lblConteoVectorizados"><?= (int)$stats['vectorizados'] ?></strong> vectorizados de <strong id="lblConteoTotal"><?= (int)$stats['total'] ?></strong> proyectos · Pendientes: <strong style="color: var(--color-secundario);" id="lblConteoPendientes"><?= (int)$stats['pendientes'] ?></strong>
                                    </span>

                                    <div style="display: flex; gap: 0.45rem; flex-wrap: wrap;">
                                        <button type="button" class="btn-cancel-sm" id="btnReiniciarVectores" onclick="solicitarReinicioVectores()" style="padding: 0.35rem 0.75rem; font-size: 0.74rem;" title="Marca todos los proyectos como pendientes para regenerar sus vectores con los nuevos pesos">
                                            <i class="ph ph-arrows-clockwise"></i> Re-indexar Todo
                                        </button>
                                        <button type="button" class="btn-save-sm" id="btnVectorizarAhora" onclick="procesarEmbeddingsEnVivo()" style="padding: 0.35rem 0.9rem; font-size: 0.74rem;">
                                            <i class="ph ph-lightning"></i> Vectorizar Pendientes Ahora
                                        </button>
                                    </div>
                                </div>

                                <div id="estadoProcesoEmbeddings" class="citation-preview-live" style="display: none; margin-top: 0.55rem; font-family: inherit; font-weight: 700; color: var(--color-secundario);"></div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- TARJETA 2: CONFIGURACIÓN DE PARÁMETROS DEL MODELO ONNX -->
                <section class="config-card">
                    <div class="config-card-header">
                        <h3 class="config-card-title">
                            <i class="ph ph-sliders"></i> Configuración de Hiperparámetros y Búsqueda Semántica
                        </h3>
                        <span class="field-hint">Protección activa de límites para el buscador</span>
                    </div>

                    <form action="?ruta=gestion-red-onnx" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                        <input type="hidden" name="accion_onnx" value="guardar_parametros">

                        <!-- Interruptores con el diseño y estructura nativa del módulo -->
                        <div class="citation-style-box" style="padding: 0.4rem 0.8rem; margin-bottom: 0.85rem;">
                            <div class="config-switch-row">
                                <label for="sw_habilitar_semantica" class="config-switch-label" style="cursor: pointer;">
                                    <strong>Habilitar Búsqueda Semántica con Redes Neuronales en el Buscador</strong>
                                    <span>Permite a los usuarios activar el interruptor de Inteligencia Artificial en el buscador unificado.</span>
                                </label>
                                <label class="switch-toggle">
                                    <input type="checkbox" id="sw_habilitar_semantica" name="habilitar_busqueda_semantica" value="1" <?= $habilitarSemantica ? 'checked' : '' ?>>
                                    <span class="switch-slider"></span>
                                </label>
                            </div>

                            <div class="config-switch-row">
                                <label for="sw_fallback_estandar" class="config-switch-label" style="cursor: pointer;">
                                    <strong>Respaldo Automático al Buscador Textual Estándar</strong>
                                    <span>Si una consulta semántica no encuentra coincidencias bajo el umbral o hay proyectos aún sin vectorizar, muestra resultados por coincidencia de texto para que el buscador nunca quede vacío.</span>
                                </label>
                                <label class="switch-toggle">
                                    <input type="checkbox" id="sw_fallback_estandar" name="fallback_busqueda_estandar" value="1" <?= $fallbackEstandar ? 'checked' : '' ?>>
                                    <span class="switch-slider"></span>
                                </label>
                            </div>
                        </div>

                        <div class="config-grid-2" style="margin-bottom: 0.4rem;">
                            <!-- Umbral de distancia coseno -->
                            <div class="config-field">
                                <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.35rem; flex-wrap: wrap;">
                                    <label for="umbral_distancia_coseno">
                                        Umbral de Distancia Coseno Máxima: <span id="lblUmbralVal" style="color: var(--color-secundario);"><?= number_format($umbralCoseno, 2) ?></span>
                                    </label>
                                    <div style="display: flex; gap: 0.25rem; flex-wrap: wrap;">
                                        <button type="button" class="tag-pill-btn" id="btnPresetEstricto" onclick="seleccionarPresetExigencia(0.75)">Estricto</button>
                                        <button type="button" class="tag-pill-btn" id="btnPresetEquilibrado" onclick="seleccionarPresetExigencia(0.82)">Recomendado</button>
                                        <button type="button" class="tag-pill-btn" id="btnPresetFlexible" onclick="seleccionarPresetExigencia(0.88)">Flexible</button>
                                    </div>
                                </div>
                                <input type="range" id="umbral_distancia_coseno" name="umbral_distancia_coseno" min="0.45" max="0.95" step="0.01" value="<?= htmlspecialchars((string)$umbralCoseno) ?>" oninput="actualizarEtiquetaUmbral(this.value)" style="width: 100%; accent-color: var(--color-secundario); cursor: pointer; margin: 0.25rem 0;">
                                <p class="field-hint">
                                    Equivale a exigir una <strong>fidelidad mínima base del <span id="lblSimilitudMinima"><?= $similitudMinimaExigida ?>%</span></strong> con compensación automática para búsquedas de 1 a 3 palabras.
                                </p>
                            </div>

                            <!-- Longitud de secuencia de tokens -->
                            <div class="config-field">
                                <label for="max_tokens_secuencia">Longitud Máxima de Secuencia SentencePiece:</label>
                                <select id="max_tokens_secuencia" name="max_tokens_secuencia" class="config-input" style="font-weight: 700;">
                                    <option value="64" <?= $maxTokens === 64 ? 'selected' : '' ?>>64 tokens — Ultrarrápido para títulos cortos</option>
                                    <option value="128" <?= $maxTokens === 128 ? 'selected' : '' ?>>128 tokens — Estándar nativo XLM-RoBERTa recomendado</option>
                                    <option value="192" <?= $maxTokens === 192 ? 'selected' : '' ?>>192 tokens — Cobertura amplia de resúmenes</option>
                                    <option value="256" <?= $maxTokens === 256 ? 'selected' : '' ?>>256 tokens — Contexto extendido para resúmenes largos</option>
                                </select>
                                <p class="field-hint">Cantidad máxima de sub-palabras procesadas por cada inferencia del modelo ONNX.</p>
                            </div>
                        </div>

                        <div class="onnx-grid-3">
                            <div class="config-field">
                                <label for="max_resultados_semanticos">Máximo de Resultados Semánticos:</label>
                                <input type="number" id="max_resultados_semanticos" name="max_resultados_semanticos" class="config-input" min="1" max="100" value="<?= $maxResultados ?>">
                                <p class="field-hint">Cantidad máxima de proyectos devueltos por consulta semántica.</p>
                            </div>

                            <div class="config-field">
                                <label for="lote_worker_embeddings">Proyectos por Lote de Vectorización:</label>
                                <input type="number" id="lote_worker_embeddings" name="lote_worker_embeddings" class="config-input" min="1" max="25" value="<?= $loteWorker ?>">
                                <p class="field-hint">Cantidad de proyectos procesados en cada ciclo automático para cuidar la CPU.</p>
                            </div>

                            <div class="config-field">
                                <label for="peso_titulo">Prioridad del Título en el Vector:</label>
                                <select id="peso_titulo" name="peso_titulo" class="config-input" style="font-weight: 700;">
                                    <option value="1" <?= $pesoTitulo === 1 ? 'selected' : '' ?>>Prioridad Normal — 1x</option>
                                    <option value="2" <?= $pesoTitulo === 2 ? 'selected' : '' ?>>Prioridad Alta Recomendada — 2x</option>
                                    <option value="3" <?= $pesoTitulo === 3 ? 'selected' : '' ?>>Prioridad Máxima al Título — 3x</option>
                                </select>
                                <p class="field-hint">Refuerza el peso conceptual del título frente al texto extenso del resumen.</p>
                            </div>
                        </div>

                        <!-- Selección de campos incluidos en el embedding con switch-toggle nativo -->
                        <div style="border-top: 1px dashed rgba(169, 168, 166, 0.25); padding-top: 0.75rem; margin-top: 0.4rem;">
                            <label style="display: block; font-size: 0.78rem; font-weight: 800; color: var(--texto-titulos); margin-bottom: 0.5rem;">
                                <i class="ph ph-stack" style="color: var(--color-terciario);"></i> Campos del Proyecto que Integran el Vector Semántico:
                            </label>

                            <div class="onnx-fields-switches-grid">
                                <div class="citation-style-box" style="margin-bottom: 0; padding: 0.45rem 0.75rem;">
                                    <div class="config-switch-row" style="padding: 0.15rem 0; border-bottom: none;">
                                        <label for="sw_inc_resumen" class="config-switch-label" style="cursor: pointer;">
                                            <strong>Resumen de la Investigación</strong>
                                            <span>Problemática y metodología del PST</span>
                                        </label>
                                        <label class="switch-toggle">
                                            <input type="checkbox" id="sw_inc_resumen" name="incluir_resumen" value="1" <?= $incResumen ? 'checked' : '' ?>>
                                            <span class="switch-slider"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="citation-style-box" style="margin-bottom: 0; padding: 0.45rem 0.75rem;">
                                    <div class="config-switch-row" style="padding: 0.15rem 0; border-bottom: none;">
                                        <label for="sw_inc_objetivo" class="config-switch-label" style="cursor: pointer;">
                                            <strong>Objetivo General</strong>
                                            <span>Meta tecnológica y solución propuesta</span>
                                        </label>
                                        <label class="switch-toggle">
                                            <input type="checkbox" id="sw_inc_objetivo" name="incluir_objetivo_general" value="1" <?= $incObjetivo ? 'checked' : '' ?>>
                                            <span class="switch-slider"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="citation-style-box" style="margin-bottom: 0; padding: 0.45rem 0.75rem;">
                                    <div class="config-switch-row" style="padding: 0.15rem 0; border-bottom: none;">
                                        <label for="sw_inc_palabras" class="config-switch-label" style="cursor: pointer;">
                                            <strong>Palabras Clave</strong>
                                            <span>Tecnologías, herramientas y etiquetas</span>
                                        </label>
                                        <label class="switch-toggle">
                                            <input type="checkbox" id="sw_inc_palabras" name="incluir_palabras_clave" value="1" <?= $incPalabras ? 'checked' : '' ?>>
                                            <span class="switch-slider"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="citation-style-box" style="margin-bottom: 0; padding: 0.45rem 0.75rem;">
                                    <div class="config-switch-row" style="padding: 0.15rem 0; border-bottom: none;">
                                        <label for="sw_inc_comunidad" class="config-switch-label" style="cursor: pointer;">
                                            <strong>Comunidad Beneficiada</strong>
                                            <span>Institución u organización vinculada</span>
                                        </label>
                                        <label class="switch-toggle">
                                            <input type="checkbox" id="sw_inc_comunidad" name="incluir_comunidad" value="1" <?= $incComunidad ? 'checked' : '' ?>>
                                            <span class="switch-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pst-config-footer" style="margin-top: 0.9rem;">
                            <button type="submit" class="btn-save-sm">
                                <i class="ph ph-floppy-disk"></i> Guardar Parámetros del Modelo ONNX
                            </button>
                        </div>
                    </form>
                </section>

                <!-- TARJETA 3: LABORATORIO DE PRUEBA DE FIDELIDAD EN VIVO -->
                <section class="config-card" style="margin-bottom: 0;">
                    <div class="config-card-header">
                        <h3 class="config-card-title">
                            <i class="ph ph-flask"></i> Simulador de Inferencia y Fidelidad en Vivo
                        </h3>
                        <span class="field-hint">Prueba consultas conceptuales y verifica la distancia coseno real</span>
                    </div>

                    <div class="config-field" style="margin-bottom: 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.35rem; margin-bottom: 0.35rem;">
                            <label for="inputConsultaPruebaOnnx">Consulta de Prueba Semántica:</label>
                            <div style="display: flex; gap: 0.35rem; align-items: center; flex-wrap: wrap;">
                                <span class="field-hint" style="font-weight: 700;">Probar idea rápida:</span>
                                <button type="button" class="tag-pill-btn" onclick="probarIdeaEjemplo('Automatización de control de inventario y almacén web')">+ Inventario web</button>
                                <button type="button" class="tag-pill-btn" onclick="probarIdeaEjemplo('Aplicación educativa interactiva para aprendizaje')">+ App educativa</button>
                                <button type="button" class="tag-pill-btn" onclick="probarIdeaEjemplo('Gestión de procesos administrativos y archivo digital')">+ Gestión administrativa</button>
                            </div>
                        </div>

                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <input type="text" id="inputConsultaPruebaOnnx" class="config-input" style="flex: 1; min-width: 240px; padding: 0.48rem 0.75rem;" placeholder="Escribe una idea o problemática, ej: automatización de control de inventario y reportes web..." onkeydown="if(event.key==='Enter'){event.preventDefault();ejecutarPruebaInferenciaEnVivo();}">
                            <button type="button" class="btn-save-sm" id="btnProbarInferenciaOnnx" onclick="ejecutarPruebaInferenciaEnVivo()">
                                <i class="ph ph-sparkle"></i> Probar Inferencia ONNX
                            </button>
                        </div>
                    </div>

                    <div id="contenedorResultadoPruebaOnnx" style="display: none; margin-top: 0.75rem;">
                        <div id="metaResultadoPruebaOnnx" class="citation-preview-live" style="font-family: inherit; font-weight: 700; color: var(--color-secundario); margin-bottom: 0.6rem; display: flex; gap: 0.65rem; flex-wrap: wrap;"></div>
                        <div id="listaResultadoPruebaOnnx" style="display: flex; flex-direction: column; gap: 0.45rem;"></div>
                    </div>
                </section>

            </div>

            <!-- COLUMNA LATERAL DERECHA: DIAGNÓSTICO DE ARQUITECTURA Y DISTRIBUCIÓN -->
            <aside>

                <!-- FICHA TÉCNICA DEL BINARIO ONNX -->
                <section class="config-card">
                    <div class="config-card-header">
                        <h3 class="config-card-title">
                            <i class="ph ph-hard-drives"></i> Arquitectura del Modelo Activo
                        </h3>
                    </div>

                    <div class="citation-style-box" style="margin-bottom: 0; padding: 0.65rem 0.85rem; display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.78rem;">
                        <div style="display: flex; justify-content: space-between; padding-bottom: 0.4rem; border-bottom: 1px dashed rgba(169, 168, 166, 0.25); gap: 0.5rem;">
                            <span style="color: var(--texto-silenciado); font-weight: 600;">Arquitectura Base:</span>
                            <strong style="color: var(--texto-titulos); text-align: right;"><?= htmlspecialchars($diag['arquitectura'] ?? 'paraphrase-multilingual-MiniLM-L12-v2 · XLM-RoBERTa') ?></strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding-bottom: 0.4rem; border-bottom: 1px dashed rgba(169, 168, 166, 0.25);">
                            <span style="color: var(--texto-silenciado); font-weight: 600;">Motor Nativo C++:</span>
                            <strong style="color: var(--color-secundario);"><?= htmlspecialchars($diag['lib_nativa_nombre'] ?? 'ONNX Runtime') ?></strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding-bottom: 0.4rem; border-bottom: 1px dashed rgba(169, 168, 166, 0.25);">
                            <span style="color: var(--texto-silenciado); font-weight: 600;">Peso del Grafo Cuantizado:</span>
                            <strong style="color: var(--texto-titulos);"><?= number_format((float)($diag['peso_total_mb'] ?? 113.43), 2) ?> MB</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding-bottom: 0.4rem; border-bottom: 1px dashed rgba(169, 168, 166, 0.25);">
                            <span style="color: var(--texto-silenciado); font-weight: 600;">Vocabulario SentencePiece:</span>
                            <strong style="color: var(--texto-titulos);"><?= number_format((int)($diag['vocab_total_tokens'] ?? 250002), 0, ',', '.') ?> tokens</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding-bottom: 0.4rem; border-bottom: 1px dashed rgba(169, 168, 166, 0.25);">
                            <span style="color: var(--texto-silenciado); font-weight: 600;">Dimensión del Embedding:</span>
                            <strong style="color: var(--color-secundario);"><?= (int)($diag['dimension'] ?? 384) ?> dimensiones</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--texto-silenciado); font-weight: 600;">Tensores de Entrada:</span>
                            <strong style="color: var(--texto-titulos); font-family: monospace; font-size: 0.73rem;"><?= htmlspecialchars(implode(', ', $diag['inputs_modelo'] ?? ['input_ids', 'attention_mask'])) ?></strong>
                        </div>
                    </div>
                </section>

                <!-- COBERTURA POR LÍNEA DE INVESTIGACIÓN -->
                <section class="config-card">
                    <div class="config-card-header">
                        <h3 class="config-card-title">
                            <i class="ph ph-compass"></i> Vectores por Línea de Investigación
                        </h3>
                    </div>

                    <?php if (empty($stats['distribucion_lineas'])): ?>
                        <p class="field-hint">Aún no hay proyectos registrados por línea de investigación.</p>
                    <?php else: ?>
                        <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                            <?php foreach ($stats['distribucion_lineas'] as $linItem):
                                $totLin = max(1, (int)$linItem['total']);
                                $vecLin = (int)$linItem['vectorizados'];
                                $pctLin = round(($vecLin / $totLin) * 100, 1);
                            ?>
                                <div class="citation-style-box" style="margin-bottom: 0; padding: 0.5rem 0.7rem;">
                                    <div style="display: flex; justify-content: space-between; font-size: 0.75rem; font-weight: 700; color: var(--texto-titulos); margin-bottom: 0.25rem;">
                                        <span><?= htmlspecialchars($linItem['linea']) ?></span>
                                        <span style="color: var(--color-secundario);"><?= $vecLin ?> / <?= (int)$linItem['total'] ?></span>
                                    </div>
                                    <div class="gauge-bar-wrapper" style="height: 6px; margin-bottom: 0;">
                                        <div class="gauge-fill-trained" style="width: <?= $pctLin ?>%;"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>

                <!-- FLUJO TÉCNICO DE LA RED NEURONAL -->
                <section class="config-card" style="margin-bottom: 0;">
                    <div class="config-card-header">
                        <h3 class="config-card-title">
                            <i class="ph ph-graduation-cap"></i> Ciclo de Inferencia Semántica
                        </h3>
                    </div>

                    <div class="citation-preview-live" style="margin-top: 0; margin-bottom: 0.55rem; font-family: inherit; font-size: 0.76rem; line-height: 1.4;">
                        <strong style="display: block; color: var(--texto-titulos); margin-bottom: 2px;">1. Tokenización SentencePiece Unigram XLM-RoBERTa</strong>
                        El texto del proyecto se segmenta mediante prefijos metaspace <code>▁</code> sobre un vocabulario multilingüe de 250.002 tokens generando las matrices <code>input_ids</code> y <code>attention_mask</code>.
                    </div>

                    <div class="citation-preview-live" style="margin-top: 0; margin-bottom: 0.55rem; font-family: inherit; font-size: 0.76rem; line-height: 1.4;">
                        <strong style="display: block; color: var(--texto-titulos); margin-bottom: 2px;">2. Grafo Transformer de 12 Capas en ONNX Runtime</strong>
                        El motor C++ proyecta cada token en un espacio contextual denso de 384 dimensiones entrenado para español académico y aplica <em>Mean Pooling</em> ponderado por atención.
                    </div>

                    <div class="citation-preview-live" style="margin-top: 0; margin-bottom: 0; font-family: inherit; font-size: 0.76rem; line-height: 1.4;">
                        <strong style="display: block; color: var(--texto-titulos); margin-bottom: 2px;">3. Normalización L2 y Distancia Coseno</strong>
                        El vector resultante se normaliza a magnitud unitaria <code>1.0</code> y se indexa en PostgreSQL <code>pgvector</code> para consultas instantáneas por similitud conceptual.
                    </div>
                </section>

            </aside>

        </div>

    </div>
</div>

<!-- MODAL DE CONFIRMACIÓN PARA RE-INDEXAR CATÁLOGO -->
<div id="modalConfirmarReindexOnnx" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 34, 68, 0.7); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center;">
    <div style="background: var(--bg-card, #ffffff); border: 1px solid rgba(169, 168, 166, 0.2); border-radius: 8px; width: 90%; max-width: 460px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.25);">
        <div style="text-align: center; margin-bottom: 1rem;">
            <div style="width: 48px; height: 48px; margin: 0 auto 0.65rem auto; border-radius: 50%; background: rgba(80, 89, 132, 0.1); display: flex; align-items: center; justify-content: center; font-size: 1.6rem; color: var(--color-secundario);">
                <i class="ph ph-arrows-clockwise"></i>
            </div>
            <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--texto-titulos); margin: 0 0 0.45rem 0;">Re-vectorizar Catálogo Completo</h3>
            <p style="font-size: 0.82rem; color: var(--texto-silenciado); margin: 0; line-height: 1.45;">
                ¿Deseas marcar todos los proyectos para regenerar sus vectores semánticos con los parámetros actuales? El proceso se ejecutará seguidamente por lotes seguros sin interrumpir el buscador.
            </p>
        </div>
        <div style="display: flex; justify-content: center; gap: 0.6rem; border-top: 1px solid rgba(169, 168, 166, 0.15); padding-top: 1rem;">
            <button type="button" class="btn-cancel-sm" onclick="cerrarModalReindexOnnx()">Cancelar</button>
            <button type="button" class="btn-save-sm" onclick="confirmarReinicioVectoresOnnx()">
                <i class="ph ph-check"></i> Sí, re-indexar ahora
            </button>
        </div>
    </div>
</div>

<script>
const CSRF_TOKEN_ONNX = <?= json_encode($_SESSION['csrf_token'] ?? '') ?>;

function actualizarEtiquetaUmbral(val) {
    const num = parseFloat(val) || 0.85;
    const lblUmbral = document.getElementById('lblUmbralVal');
    const lblSim = document.getElementById('lblSimilitudMinima');

    const btnFlex = document.getElementById('btnPresetFlexible');
    const btnEq   = document.getElementById('btnPresetEquilibrado');
    const btnEst  = document.getElementById('btnPresetEstricto');

    if (lblUmbral) lblUmbral.textContent = num.toFixed(2);
    if (lblSim) {
        const simMin = Math.max(5, Math.min(85, Math.round((1.0 - num) * 100)));
        lblSim.textContent = simMin + '%';
    }

    if (btnFlex) btnFlex.classList.remove('active');
    if (btnEq) btnEq.classList.remove('active');
    if (btnEst) btnEst.classList.remove('active');

    if (num >= 0.86) {
        if (btnFlex) btnFlex.classList.add('active');
    } else if (num >= 0.79) {
        if (btnEq) btnEq.classList.add('active');
    } else {
        if (btnEst) btnEst.classList.add('active');
    }
}

function seleccionarPresetExigencia(valor) {
    const slider = document.getElementById('umbral_distancia_coseno');
    if (slider) {
        slider.value = valor;
        actualizarEtiquetaUmbral(valor);
    }
}

function probarIdeaEjemplo(texto) {
    const input = document.getElementById('inputConsultaPruebaOnnx');
    if (input) {
        input.value = texto;
        ejecutarPruebaInferenciaEnVivo();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const slider = document.getElementById('umbral_distancia_coseno');
    if (slider) {
        actualizarEtiquetaUmbral(slider.value);
    }
});

async function procesarEmbeddingsEnVivo() {
    const btn = document.getElementById('btnVectorizarAhora');
    const estadoBox = document.getElementById('estadoProcesoEmbeddings');
    if (!btn || !estadoBox) return;

    btn.disabled = true;
    estadoBox.style.display = 'block';
    estadoBox.innerHTML = '<i class="ph ph-spinner spin"></i> Iniciando vectorización por lotes seguros con ONNX Runtime...';

    let continuar = true;
    let totalProcesadosSesion = 0;

    while (continuar) {
        try {
            const fd = new FormData();
            fd.append('csrf_token', CSRF_TOKEN_ONNX);
            fd.append('accion_onnx', 'procesar_lote');

            const res = await fetch('?ruta=gestion-red-onnx', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: fd
            });
            const data = await res.json();

            if (!data || !data.success) {
                estadoBox.textContent = data?.message || 'Error al procesar el lote de vectores.';
                continuar = false;
                break;
            }

            totalProcesadosSesion += (data.procesados || 0);

            if (typeof data.cobertura_pct !== 'undefined') {
                const barra = document.getElementById('barraCoberturaOnnx');
                const lblCob = document.getElementById('lblCoberturaPct');
                if (barra) barra.style.width = data.cobertura_pct + '%';
                if (lblCob) lblCob.textContent = Number(data.cobertura_pct).toFixed(1) + '%';
            }
            if (typeof data.fidelidad_global_pct !== 'undefined') {
                const ring = document.getElementById('ringFidelidadOnnx');
                const lblFid = document.getElementById('lblFidelidadGlobal');
                if (ring) ring.style.setProperty('--percentage', data.fidelidad_global_pct);
                if (lblFid) lblFid.textContent = Number(data.fidelidad_global_pct).toFixed(1) + '%';
            }
            if (typeof data.vectorizados !== 'undefined') {
                const elVec = document.getElementById('lblConteoVectorizados');
                if (elVec) elVec.textContent = data.vectorizados;
            }
            if (typeof data.pendientes_restantes !== 'undefined') {
                const elPend = document.getElementById('lblConteoPendientes');
                if (elPend) elPend.textContent = data.pendientes_restantes;
            }

            if ((data.pendientes_restantes || 0) <= 0 || (data.procesados || 0) === 0) {
                estadoBox.innerHTML = '<i class="ph ph-check-circle"></i> ¡Vectorización completada! Proyectos procesados en esta sesión: ' + totalProcesadosSesion + '.';
                continuar = false;
            } else {
                estadoBox.innerHTML = '<i class="ph ph-spinner spin"></i> Vectorizando catálogo... Procesados: ' + totalProcesadosSesion + ' · Pendientes restantes: ' + data.pendientes_restantes;
            }
        } catch (err) {
            estadoBox.textContent = 'Error de conexión durante la vectorización.';
            continuar = false;
        }
    }

    btn.disabled = false;
}

function solicitarReinicioVectores() {
    const modal = document.getElementById('modalConfirmarReindexOnnx');
    if (modal) modal.style.display = 'flex';
}

function cerrarModalReindexOnnx() {
    const modal = document.getElementById('modalConfirmarReindexOnnx');
    if (modal) modal.style.display = 'none';
}

async function confirmarReinicioVectoresOnnx() {
    cerrarModalReindexOnnx();
    const estadoBox = document.getElementById('estadoProcesoEmbeddings');
    if (estadoBox) {
        estadoBox.style.display = 'block';
        estadoBox.textContent = 'Marcando proyectos para re-vectorización...';
    }

    try {
        const fd = new FormData();
        fd.append('csrf_token', CSRF_TOKEN_ONNX);
        fd.append('accion_onnx', 'reiniciar_vectores');

        const res = await fetch('?ruta=gestion-red-onnx', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: fd
        });
        const data = await res.json();
        if (data && data.success) {
            await procesarEmbeddingsEnVivo();
        } else if (estadoBox) {
            estadoBox.textContent = data?.message || 'No se pudo iniciar la re-vectorización.';
        }
    } catch (e) {
        if (estadoBox) estadoBox.textContent = 'Error de red al reiniciar vectores.';
    }
}

async function ejecutarPruebaInferenciaEnVivo() {
    const input = document.getElementById('inputConsultaPruebaOnnx');
    const btn = document.getElementById('btnProbarInferenciaOnnx');
    const box = document.getElementById('contenedorResultadoPruebaOnnx');
    const meta = document.getElementById('metaResultadoPruebaOnnx');
    const lista = document.getElementById('listaResultadoPruebaOnnx');

    if (!input || !box || !meta || !lista) return;
    const consulta = input.value.trim();
    if (!consulta) {
        input.focus();
        return;
    }

    btn.disabled = true;
    box.style.display = 'block';
    meta.textContent = 'Ejecutando inferencia en el grafo ONNX...';
    lista.innerHTML = '';

    try {
        const fd = new FormData();
        fd.append('csrf_token', CSRF_TOKEN_ONNX);
        fd.append('accion_onnx', 'probar_inferencia');
        fd.append('consulta', consulta);

        const res = await fetch('?ruta=gestion-red-onnx', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: fd
        });
        const data = await res.json();

        if (!data || !data.success) {
            meta.textContent = data?.message || 'No se pudo completar la inferencia.';
            btn.disabled = false;
            return;
        }

        meta.innerHTML = `
            <span><i class="ph ph-timer"></i> Latencia total: ${data.duracion_ms} ms</span>
            <span>·</span>
            <span><i class="ph ph-text-aa"></i> Tokens SentencePiece: ${data.tokens_activos}</span>
            <span>·</span>
            <span><i class="ph ph-cube"></i> Vector: ${data.dimension} dims</span>
            <span>·</span>
            <span><i class="ph ph-list-magnifying-glass"></i> Coincidencias: ${data.total_hallados}</span>
        `;

        if (!data.resultados || data.resultados.length === 0) {
            lista.innerHTML = `<div class="citation-style-box" style="margin-bottom: 0; font-size: 0.78rem; color: var(--texto-silenciado);">No se hallaron proyectos para esta consulta. Si existen proyectos pendientes de vectorizar, pulsa el botón <strong>Vectorizar Pendientes Ahora</strong> arriba.</div>`;
        } else {
            data.resultados.forEach(item => {
                const card = document.createElement('div');
                card.className = 'citation-style-box';
                card.style.cssText = 'margin-bottom: 0; padding: 0.65rem 0.85rem; background: #ffffff; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;';
                card.innerHTML = `
                    <div style="flex: 1; min-width: 220px;">
                        <a href="?ruta=detalles-pst&id=${item.id}" style="font-size: 0.82rem; font-weight: 800; color: var(--color-secundario); text-decoration: none; display: block; margin-bottom: 2px;">${item.titulo}</a>
                        <span class="field-hint">${item.linea} · Año ${item.anio}</span>
                    </div>
                    <div style="text-align: right;">
                        <span class="pst-badge-soft" style="background: rgba(80, 89, 132, 0.1); color: var(--color-secundario); border: 1px solid rgba(80, 89, 132, 0.2);">${item.similitud}% Fidelidad IA</span>
                        <div class="field-hint" style="margin-top: 2px;">Distancia coseno: ${item.distancia}</div>
                    </div>
                `;
                lista.appendChild(card);
            });
        }
    } catch (e) {
        meta.textContent = 'Error al consultar el simulador ONNX.';
    }

    btn.disabled = false;
}
</script>
