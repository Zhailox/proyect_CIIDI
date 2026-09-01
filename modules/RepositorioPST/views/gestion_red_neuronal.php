<?php
// modules/RepositorioPST/views/gestion_red_neuronal.php
// $success, $error, $metadata, $stats are provided by BusquedaGlobalController::gestionRed()
?>

<style>
/* Estilos adicionales específicos del Dashboard de Red Neuronal */
.neural-dashboard {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
    width: 100%;
}

@media (max-width: 992px) {
    .neural-dashboard {
        grid-template-columns: 1fr;
    }
}

.neural-card {
    background-color: var(--bg-card, #ffffff);
    border: 1px solid rgba(169, 168, 166, 0.2);
    border-radius: var(--radius-lg, 12px);
    padding: 1.75rem;
    box-shadow: 0 4px 15px rgba(18, 26, 62, 0.03);
    margin-bottom: 1.5rem;
}

.neural-card-header {
    border-bottom: 1px solid rgba(169, 168, 166, 0.15);
    padding-bottom: 0.75rem;
    margin-bottom: 1.25rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.neural-card-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--texto-titulos);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.neural-card-title i {
    color: var(--color-terciario);
}

/* Gráfico de Barra Visual (Trained vs Untrained) */
.gauge-container {
    margin-top: 1rem;
}

.gauge-bar-wrapper {
    height: 24px;
    background-color: rgba(169, 168, 166, 0.15);
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    width: 100%;
    margin-bottom: 0.75rem;
    border: 1px solid rgba(169, 168, 166, 0.1);
}

.gauge-fill-trained {
    height: 100%;
    background: linear-gradient(90deg, var(--color-secundario), var(--color-terciario));
    transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.gauge-fill-untrained {
    height: 100%;
    background-color: #fca5a5;
    transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.gauge-legend {
    display: flex;
    justify-content: space-between;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--texto-silenciado);
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.legend-color-trained {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: var(--color-terciario);
}

.legend-color-untrained {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: #fca5a5;
}

/* Anillo de exactitud (Accuracy radial gauge) */
.accuracy-radial-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1rem 0;
}

.radial-gauge {
    position: relative;
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: radial-gradient(closest-side, white 79%, transparent 80% 100%),
                conic-gradient(var(--color-terciario) calc(var(--percentage) * 1%), rgba(169, 168, 166, 0.15) 0);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
    box-shadow: 0 4px 10px rgba(18, 26, 62, 0.05);
}

.radial-gauge-inner {
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--texto-titulos);
}

.accuracy-label {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--texto-silenciado);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Formulario de Configuración */
.config-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.25rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

.form-group label {
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--texto-subtitulos);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-group input {
    padding: 0.6rem 0.8rem;
    border: 1px solid rgba(169, 168, 166, 0.4);
    border-radius: 6px;
    font-size: 0.9rem;
    outline: none;
    background-color: rgba(244, 244, 244, 0.3);
    transition: all var(--transition-fast);
}

.form-group input:focus {
    border-color: var(--color-terciario);
    background-color: #fff;
    box-shadow: 0 0 0 3px rgba(112, 144, 203, 0.15);
}

.form-help {
    font-size: 0.75rem;
    color: var(--texto-silenciado);
}

.btn-neural-train {
    background-color: var(--color-secundario);
    color: white;
    border: none;
    border-radius: 6px;
    padding: 0.75rem 1.5rem;
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    box-shadow: 0 4px 10px rgba(18, 26, 62, 0.1);
    transition: all var(--transition-fast);
}

.btn-neural-train:hover {
    background-color: var(--color-terciario);
    transform: translateY(-1px);
}

.btn-neural-train:active {
    transform: translateY(0);
}

.train-overlay-loading {
    display: none;
    background-color: rgba(255, 255, 255, 0.9);
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    z-index: 10;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 1rem;
    border-radius: var(--radius-lg, 12px);
}

.train-overlay-loading.active {
    display: flex;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid rgba(112, 144, 203, 0.2);
    border-top-color: var(--color-terciario);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

/* Botón de Regresar */
.btn-create-new {
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.4rem !important;
    background-color: rgba(169, 168, 166, 0.15) !important;
    color: var(--texto-subtitulos) !important;
    border: 1px solid rgba(169, 168, 166, 0.2) !important;
    padding: 0.45rem 1rem !important;
    border-radius: 4px !important;
    font-size: 0.8rem !important;
    font-weight: 700 !important;
    text-decoration: none !important;
    transition: background-color 0.2s !important;
}

.btn-create-new:hover {
    background-color: rgba(169, 168, 166, 0.25) !important;
    color: var(--texto-titulos) !important;
}

/* Explicación de Conceptos IA */
.concept-box {
    background-color: rgba(112, 144, 203, 0.05);
    border-left: 4px solid var(--color-terciario);
    padding: 1rem;
    border-radius: 0 6px 6px 0;
    margin-bottom: 1rem;
    font-size: 0.85rem;
    line-height: 1.45;
}

.concept-title {
    font-weight: 700;
    color: var(--texto-titulos);
    margin-bottom: 0.25rem;
}
</style>

<div class="main-content">
    <div class="upload-view-container">
        
        <!-- CABECERA DE LA PÁGINA -->
        <header class="pst-header" style="margin-bottom: 1.5rem;">
            <div class="pst-header-left">
                <h1>Panel de Redes Neuronales</h1>
                <p>Configura, optimiza y entrena el perceptrón multicapa del Repositorio PST.</p>
            </div>
            <div>
                <a href="?ruta=agregar-documento" class="btn-create-new" style="background-color: rgba(169, 168, 166, 0.15); color: var(--texto-subtitulos); border: 1px solid rgba(169, 168, 166, 0.2);">
                    <i class="ph ph-arrow-left"></i> Volver a Gestión Documental
                </a>
            </div>
        </header>

        <!-- Mensajes de Éxito / Error -->
        <?php if (!empty($error)): ?>
            <div class="alert-message alert-error" style="background-color: #fee2e2; color: #ef4444; border: 1px solid #fca5a5; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph ph-warning-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert-message alert-success" style="background-color: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph ph-check-circle"></i> <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <div class="neural-dashboard">
            
            <!-- Columna Izquierda: Panel del Modelo y Formulario de Entrenamiento -->
            <div class="left-column">
                
                <!-- CARD 1: ESTADO ACTUAL Y STATS -->
                <div class="neural-card" style="position: relative;">
                    <!-- Loading overlay para entrenamiento -->
                    <div class="train-overlay-loading" id="trainLoader">
                        <div class="spinner"></div>
                        <h4 style="color: var(--texto-titulos); font-weight: 700;">Entrenando Perceptrón Multicapa...</h4>
                        <p style="color: var(--texto-silenciado); font-size: 0.85rem; max-width: 320px; text-align: center;">Este proceso puede tomar varios segundos mientras se calculan los pesos del vocabulario en la base de datos.</p>
                    </div>

                    <div class="neural-card-header">
                        <h2 class="neural-card-title"><i class="ph ph-activity"></i> Estado del Clasificador IA</h2>
                        <?php if ($metadata): ?>
                            <span class="badge-sys" style="background-color: #dcfce7; color: #16a34a; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.6rem; border-radius: 12px;">ACTIVO</span>
                        <?php else: ?>
                            <span class="badge-sys" style="background-color: #fee2e2; color: #ef4444; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.6rem; border-radius: 12px;">SIN ENTRENAR</span>
                        <?php endif; ?>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: center;">
                        <div>
                            <p style="font-size: 0.9rem; margin-bottom: 0.5rem; color: var(--texto-comun);">
                                <strong>Último Entrenamiento:</strong> <?= $metadata ? date('d/m/Y - h:i A', $metadata['timestamp']) : 'Nunca' ?>
                            </p>
                            <p style="font-size: 0.9rem; margin-bottom: 0.5rem; color: var(--texto-comun);">
                                <strong>Vocabulario Indexado:</strong> <?= $metadata ? ($metadata['vocabulary_size'] . ' palabras clave') : 'Ninguna' ?>
                            </p>
                            
                            <!-- Gráfico de Cobertura (Trained vs Untrained) -->
                            <div class="gauge-container">
                                <h4 style="font-size: 0.8rem; font-weight: 700; color: var(--texto-subtitulos); margin-bottom: 0.4rem; text-transform: uppercase;">Cobertura del Modelo</h4>
                                <div class="gauge-bar-wrapper">
                                    <?php 
                                    $pctTrained = $stats['total'] > 0 ? ($stats['trained'] / $stats['total']) * 100 : 0;
                                    $pctUntrained = 100 - $pctTrained;
                                    ?>
                                    <div class="gauge-fill-trained" style="width: <?= $pctTrained ?>%;" title="Entrenado: <?= $stats['trained'] ?> proyectos"></div>
                                    <div class="gauge-fill-untrained" style="width: <?= $pctUntrained ?>%;" title="Sin Entrenar: <?= $stats['untrained'] ?> proyectos"></div>
                                </div>
                                <div class="gauge-legend">
                                    <span class="legend-item"><span class="legend-color-trained"></span> Entrenados: <?= $stats['trained'] ?></span>
                                    <span class="legend-item"><span class="legend-color-untrained"></span> Sin Entrenar: <?= $stats['untrained'] ?></span>
                                </div>
                                <?php if ($stats['untrained'] > 0): ?>
                                    <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.5rem; font-weight: 600;">
                                        <i class="ph ph-info"></i> Hay <?= $stats['untrained'] ?> proyecto(s) agregados recientemente que no están indexados en la red neuronal. Se recomienda reentrenar.
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Anillo de Exactitud -->
                        <div class="accuracy-radial-container">
                            <?php $accPercent = $metadata ? (float)$metadata['accuracy'] * 100 : 0; ?>
                            <div class="radial-gauge" style="--percentage: <?= $accPercent ?>;">
                                <div class="radial-gauge-inner"><?= number_format($accPercent, 1) ?>%</div>
                            </div>
                            <span class="accuracy-label">Fiabilidad de Búsqueda</span>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: CONFIGURACIÓN Y ENTRENAMIENTO -->
                <div class="neural-card">
                    <div class="neural-card-header">
                        <h2 class="neural-card-title"><i class="ph ph-gear"></i> Optimización y Seteo de Pesos (MLP)</h2>
                    </div>
                    
                    <form action="" method="POST" id="neuralTrainForm" onsubmit="showTrainingLoader()">
                        <input type="hidden" name="action" value="entrenar">
                        
                        <?php 
                        $layersVal = ($metadata && isset($metadata['config']['hidden_layers'])) ? implode(',', $metadata['config']['hidden_layers']) : '16,8';
                        $itersVal = ($metadata && isset($metadata['config']['iterations'])) ? $metadata['config']['iterations'] : 1000;
                        $lrVal = ($metadata && isset($metadata['config']['learning_rate'])) ? $metadata['config']['learning_rate'] : 0.1;
                        ?>
                        <div class="config-grid">
                            
                            <div class="form-group">
                                <label for="hidden_layers">Capas Ocultas (Estructura de Red)</label>
                                <input type="text" name="hidden_layers" id="hidden_layers" value="<?= htmlspecialchars($layersVal) ?>" placeholder="Ej: 16 o 32,16 o 16,8">
                                <span class="form-help">Ingresa números separados por coma representando el número de neuronas por cada capa oculta. Ej: "16,8" crea dos capas ocultas.</span>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                                <div class="form-group">
                                    <label for="iterations">Iteraciones (Epochs)</label>
                                    <input type="number" name="iterations" id="iterations" value="<?= $itersVal ?>" min="10" max="25000">
                                    <span class="form-help">Número de ciclos de ajuste de pesos en la red neuronal.</span>
                                </div>
                                <div class="form-group">
                                    <label for="learning_rate">Tasa de Aprendizaje (Learning Rate)</label>
                                    <input type="number" step="0.001" name="learning_rate" id="learning_rate" value="<?= $lrVal ?>" min="0.001" max="1.0">
                                    <span class="form-help">Ajusta el paso de gradiente del perceptrón. Por defecto 0.1.</span>
                                </div>
                            </div>

                        </div>

                        <div style="margin-top: 1.5rem;">
                            <button type="submit" class="btn-neural-train">
                                <i class="ph ph-cpu"></i> Entrenar Red Neuronal (Reajustar Pesos)
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            <!-- Columna Derecha: Explicación Didáctica y Defensas del Proyecto -->
            <aside class="right-column">
                
                <div class="neural-card" style="height: 100%;">
                    <div class="neural-card-header">
                        <h2 class="neural-card-title"><i class="ph ph-student"></i> Defensa del Proyecto (Teoría IA)</h2>
                    </div>

                    <div class="concept-box">
                        <div class="concept-title">1. Tokenización y Limpieza</div>
                        El texto crudo de los proyectos se unifica, se pasa a minúsculas, se remueven caracteres especiales y se eliminan acentos para que los tokens coincidan sin importar su formato tipográfico.
                    </div>

                    <div class="concept-box">
                        <div class="concept-title">2. Bag of Words (Bolsa de Palabras)</div>
                        Mediante `TokenCountVectorizer`, se crea un vocabulario de palabras únicas en todos los proyectos. Cada texto de un proyecto se convierte en un vector donde cada posición representa una palabra del vocabulario y su valor indica las veces que aparece.
                    </div>

                    <div class="concept-box">
                        <div class="concept-title">3. Ponderación TF-IDF</div>
                        Usa `TfIdfTransformer` para calcular la importancia de las palabras. Si una palabra aparece en casi todos los proyectos (ej. "sistema"), su peso disminuye. Si aparece solo en un proyecto de una categoría específica, su peso aumenta para mejorar la clasificación.
                    </div>

                    <div class="concept-box">
                        <div class="concept-title">4. Perceptrón Multicapa (MLP)</div>
                        Una red neuronal artificial alimentada hacia adelante (feedforward). Se compone de una capa de entrada (tamaño del vocabulario), una o varias capas ocultas (donde se configuran las neuronas intermedias y la tasa de aprendizaje) y una capa de salida (las clases a predecir, correspondientes a las Líneas de Investigación). El algoritmo se entrena por retropropagación (backpropagation).
                    </div>
                </div>

            </aside>

        </div>

    </div>
</div>

<script>
function showTrainingLoader() {
    const loader = document.getElementById('trainLoader');
    if (loader) {
        loader.classList.add('active');
    }
}
</script>
