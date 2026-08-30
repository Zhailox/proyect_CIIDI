<!-- modules/LineasInvestigacion/views/dashboard_analitica.php -->
<div class="li-gestor-wrapper animate-fade-in">

    <div class="li-gestor-banner">
        <div>
            <h1><i class="ph-bold ph-brain" style="margin-right:0.5rem;"></i>Dashboard Analítico Predictivo (IA)</h1>
            <p>Proyección trimestral de volumen mediante Machine Learning.</p>
        </div>
        <!-- Controles movidos a la barra de configuración abajo -->
    </div>

    <!-- ╔══ SECCIÓN 1: TENDENCIAS (SERIES TEMPORALES) ══════════════════════╗ -->
    <div class="li-form-card" style="margin-bottom: 2rem;">
        <div class="li-form-header">
            <i class="ph-bold ph-chart-line-up" style="color:var(--li-emerald, #10b981);"></i>
            <span style="color: #2c3e50; font-weight: 600;">Proyección de Tendencias (Adopción y Saturación)</span>
        </div>

        <div class="li-form-body">
            
            <!-- BARRA DE CONTROLES (NUEVA) -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px 20px; margin-bottom: 20px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 15px; box-shadow: 0 2px 4px -1px rgba(0,0,0,0.03);">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: #f1f5f9; color: #505984; padding: 10px; border-radius: 8px;">
                        <i class="ph-bold ph-calendar-plus" style="font-size: 1.4rem;"></i>
                    </div>
                    <div>
                        <strong style="color: #121a3e; font-size: 1rem; display: block;">Horizonte de Proyección</strong>
                        <span style="color: #64748b; font-size: 0.85rem;">Define hasta qué fecha la IA calculará la demanda esperada.</span>
                    </div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <select id="select-trimestres" style="padding: 10px 15px; border-radius: 6px; border: 1px solid #cbd5e1; background: #f8fafc; color: #121a3e; font-weight: 600; font-size: 0.9rem; outline: none; cursor: pointer; min-width: 260px; box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);">
                        <option value="1">1 Trimestre (Inmediato)</option>
                        <option value="2">2 Trimestres</option>
                        <option value="3">3 Trimestres (1 Año Académico)</option>
                        <option value="4" selected>4 Trimestres</option>
                        <option value="6">6 Trimestres (2 Años Académicos)</option>
                        <option value="8">8 Trimestres</option>
                        <option value="9">9 Trimestres (3 Años Académicos)</option>
                        <option value="12">12 Trimestres (4 Años Académicos)</option>
                    </select>
                    <button id="btn-proyectar" style="background: #121a3e; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-weight: 700; font-size: 0.95rem; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px -1px rgba(18, 26, 62, 0.3); transition: all 0.2s ease;">
                        <i class="ph-bold ph-magic-wand" style="font-size: 1.1rem;"></i> Actualizar Modelo
                    </button>
                </div>
            </div>

            <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 1.5rem;">
                El motor de Inteligencia Artificial analiza el historial real de proyectos registrados en la plataforma. Mediante algoritmos de Regresión Lineal, proyecta el volumen de demanda para próximos trimestres académicos, facilitando la toma de decisiones estratégicas.
            </p>
            
            <div id="loading-tendencias" style="display: none; color: #505984; font-style: italic; margin-bottom: 1rem;">
                <i class="ph-bold ph-spinner ph-spin"></i> Calculando proyecciones con IA...
            </div>
            
            <div style="display: grid; grid-template-columns: 1.6fr 1fr; gap: 2rem; align-items: start;">
                
                <!-- Columna Izquierda: Gráfico + Insights -->
                <div>
                    <!-- Gráfico de Adopción -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                        <canvas id="tendenciasChart" height="280"></canvas>
                    </div>

                    <!-- Insights Automáticos (Se llenará con JS) -->
                    <div id="insights-tendencias" style="margin-top: 1.5rem; display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                    </div>
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



</div>

<!-- Cargar Chart.js desde CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Cargar el script que maneja la UI analítica -->
<script src="../modules/LineasInvestigacion/assets/analitica_ui.js"></script>
