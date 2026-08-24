<!-- modules/LineasInvestigacion/views/dashboard_analitica.php -->
<div class="li-gestor-wrapper animate-fade-in">

    <!-- ╔══ BANNER ══════════════════════════════════════════════════════════╗ -->
    <div class="li-gestor-banner">
        <div>
            <h1><i class="ph-bold ph-brain" style="margin-right:0.5rem;"></i>Dashboard Analítico Predictivo (IA)</h1>
            <p>Proyección de volumen y clasificación automatizada de documentos de investigación mediante Machine Learning.</p>
        </div>
        <button id="btn-proyectar" class="li-btn-ver" style="width:auto;padding:0.6rem 1.25rem;flex-shrink:0; cursor:pointer; border:none; outline:none; font-family:inherit;">
            <i class="ph-bold ph-trend-up"></i> Ejecutar Regresión de Tendencias
        </button>
    </div>

    <!-- ╔══ SECCIÓN 1: TENDENCIAS (SERIES TEMPORALES) ══════════════════════╗ -->
    <div class="li-form-card" style="margin-bottom: 2rem;">
        <div class="li-form-header">
            <i class="ph-bold ph-chart-line-up" style="color:var(--li-emerald, #10b981);"></i>
            <span style="color: #2c3e50; font-weight: 600;">Proyección de Tendencias (Adopción y Saturación)</span>
        </div>

        <div class="li-form-body">
            <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 1.5rem;">
                Se inyectarán registros históricos simulados de inscripciones por área. El modelo realizará una regresión lineal imputando los datos faltantes para calcular la adopción futura.
            </p>
            
            <div id="loading-tendencias" style="display: none; color: var(--li-emerald, #10b981); font-style: italic; margin-bottom: 1rem;">
                <i class="ph-bold ph-spinner ph-spin"></i> Calculando proyecciones con IA...
            </div>
            
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
                
                <!-- Gráfico de Adopción -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <canvas id="tendenciasChart" height="280"></canvas>
                </div>
                
                <!-- Panel de KPIs y Saturación -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <h4 style="margin-bottom: 1rem; color: #1e293b; font-size: 1rem; display: flex; align-items: center; gap: 0.5rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem;">
                        <i class="ph-fill ph-gauge"></i> Métricas de Error y Saturación
                    </h4>
                    
                    <div id="metricas-tendencias-resultado" style="font-size: 0.9rem; color: #475569;">
                        <div class="li-empty-state" style="padding: 1rem;">
                            <i class="ph-bold ph-chart-bar" style="font-size: 2rem; color: #cbd5e1;"></i>
                            <p style="font-size: 0.8rem; margin-top: 0.5rem; color: #94a3b8;">Presiona el botón superior para procesar el tensor histórico.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ╔══ SECCIÓN 2: CLASIFICADOR DOCUMENTAL (NLP) ═══════════════════════╗ -->
    <div class="li-form-card">
        <div class="li-form-header">
            <i class="ph-bold ph-text-aa" style="color:var(--li-indigo, #6366f1);"></i>
            <span style="color: #2c3e50; font-weight: 600;">Motor de Clasificación Documental (NLP)</span>
        </div>
        
        <div class="li-form-body">
            <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 1.5rem;">
                Prueba el modelo Multiclase (Naive Bayes) extrayendo características de texto libre mediante TF-IDF. Clasifica de forma automática la temática de la tesis.
            </p>
            
            <div style="display: grid; grid-template-columns: 2fr 1.5fr; gap: 2rem; align-items: start;">
                
                <!-- Formulario NLP -->
                <div>
                    <div class="li-form-group">
                        <label for="nlp-titulo">Título del Documento *</label>
                        <input type="text" id="nlp-titulo" placeholder="Ej: Desarrollo de API Restful con PHP...">
                    </div>
                    
                    <div class="li-form-group li-form-full">
                        <label for="nlp-resumen">Resumen Abstracto *</label>
                        <textarea id="nlp-resumen" rows="3" placeholder="Ingresa el resumen de la investigación..."></textarea>
                    </div>
                    
                    <div class="li-form-group li-form-full">
                        <label for="nlp-objetivos">Objetivos Principales</label>
                        <textarea id="nlp-objetivos" rows="2" placeholder="1. Desarrollar un modelo de predicción..."></textarea>
                    </div>
                    
                    <div class="li-form-actions" style="margin-top: 1rem; justify-content: flex-start;">
                        <button id="btn-cargar-nlp" class="btn btn-secondary" style="cursor:pointer;">
                            <i class="ph-bold ph-magic-wand"></i> Inyectar Prueba
                        </button>
                        <button id="btn-clasificar" class="btn" style="background:var(--li-indigo, #6366f1);color:#fff; cursor:pointer;">
                            <i class="ph-bold ph-cpu"></i> Ejecutar Inferencia NLP
                        </button>
                    </div>
                </div>
                
                <!-- Resultados NLP -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; height: 100%; display: flex; flex-direction: column; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <h4 style="margin-bottom: 1rem; color: #1e293b; font-size: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem;">
                        <i class="ph-bold ph-terminal-window"></i> Salida del Tensor
                    </h4>
                    
                    <div id="loading-nlp" style="display: none; color: var(--li-indigo, #6366f1); font-style: italic; margin-bottom: 1rem;">
                        <i class="ph-bold ph-spinner ph-spin"></i> Analizando vectores del texto...
                    </div>
                    
                    <div id="resultado-nlp-box" style="flex-grow: 1;">
                        <div class="li-empty-state" style="padding: 1rem; margin-top: 1rem;">
                            <i class="ph-bold ph-cube" style="font-size: 2rem; color: #cbd5e1;"></i>
                            <p style="font-size: 0.8rem; margin-top: 0.5rem; color: #94a3b8;">Esperando ejecución para mostrar métricas (F1-Score, Confusión) y categoría predicha.</p>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

</div>

<!-- Cargar Chart.js desde CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Cargar el script que maneja la UI analítica -->
<script src="../modules/LineasInvestigacion/assets/analitica_ui.js"></script>
