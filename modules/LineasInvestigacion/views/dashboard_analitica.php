<!-- modules/LineasInvestigacion/views/dashboard_analitica.php -->
<div class="li-gestor-wrapper animate-fade-in">

    <style>
.ag-header-banner {
    background: linear-gradient(135deg, rgba(80, 89, 132, 0.95) 0%, rgba(30, 41, 59, 0.98) 100%) !important;
    color: #ffffff;
    border-radius: 14px;
    padding: 2.5rem 3rem;
    box-shadow: 0 16px 36px rgba(15, 23, 42, 0.15);
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}
.ag-header-banner::before {
    content: '';
    position: absolute;
    top: -50%; right: -10%;
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 60%);
    border-radius: 50%;
}
.ag-header-subtitle {
    display: inline-flex; 
    align-items: center; 
    gap: 0.5rem; 
    color: #94a3b8; 
    font-weight: 800; 
    font-size: 0.8rem; 
    text-transform: uppercase; 
    letter-spacing: 1.5px; 
    margin-bottom: 0.5rem;
}
.ag-header-title {
    font-size: 2.2rem; 
    font-weight: 800; 
    margin: 0 0 0.8rem 0; 
    color: #ffffff;
    letter-spacing: -0.5px;
}
.ag-header-desc {
    margin: 0; 
    color: #cbd5e1; 
    font-size: 1.05rem;
    max-width: 820px;
    line-height: 1.6;
}

/* Barra de controles alineada en una sola fila limpia y armónica con el banner */
.ml-control-bar {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 16px;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: flex-end;
    gap: 14px;
    box-shadow: 0 2px 8px rgba(30, 41, 59, 0.04);
}
.ml-filters-group {
    display: flex;
    align-items: flex-end;
    gap: 14px;
    flex-wrap: wrap;
    flex: 1;
    min-width: 300px;
}
.ml-field-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
    flex: 1;
    min-width: 210px;
    max-width: 310px;
}
.ml-field-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: #505984;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.ml-select-control, .ml-input-control {
    height: 40px;
    padding: 0 12px;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    background: #f8fafc;
    color: #1e293b;
    font-weight: 600;
    font-size: 0.86rem;
    outline: none;
    cursor: pointer;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    box-sizing: border-box;
    width: 100%;
}
.ml-select-control:focus, .ml-input-control:focus {
    border-color: #505984;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(80, 89, 132, 0.12);
}
.ml-actions-group {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.ml-btn {
    height: 40px;
    padding: 0 15px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    transition: all 0.2s ease;
    white-space: nowrap;
    box-sizing: border-box;
}
.ml-btn-secondary {
    background: #f8fafc;
    color: #334155;
    border: 1px solid #cbd5e1;
}
.ml-btn-secondary:hover, .ml-btn-secondary.active {
    background: #eef0f7;
    color: #1e293b;
    border-color: #505984;
}
.ml-btn-primary {
    background: #505984;
    color: #ffffff;
    border: 1px solid #505984;
    box-shadow: 0 3px 8px rgba(80, 89, 132, 0.25);
}
.ml-btn-primary:hover {
    background: #3e466b;
    border-color: #3e466b;
    transform: translateY(-1px);
}
.ml-btn-dark {
    background: #1e293b;
    color: #ffffff;
    border: 1px solid #334155;
    box-shadow: 0 3px 8px rgba(30, 41, 59, 0.2);
}
.ml-btn-dark:hover {
    background: #0f172a;
    transform: translateY(-1px);
}
.ml-line-chip {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #1e293b;
    transition: all 0.18s ease;
    user-select: none;
}
.ml-line-chip:hover {
    border-color: #505984;
    background: #f8fafc;
}
.ml-line-chip.inactive {
    opacity: 0.45;
    background: #f1f5f9;
    color: #64748b;
    text-decoration: line-through;
}
@media (max-width: 960px) {
    .ml-main-grid {
        grid-template-columns: 1fr !important;
    }
    .ml-field-item {
        max-width: 100%;
    }
}
</style>

<div class="ag-header-banner">
    <canvas id="li-nodes-canvas-1" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 0; opacity: 0.6;"></canvas>
    <div style="position: relative; z-index: 1;">
        <div class="ag-header-subtitle">
            <i class="ph-bold ph-brain"></i> SISTEMA INTEGRAL CIIDI · INTELIGENCIA PREDICTIVA
        </div>
        <h1 class="ag-header-title">Dashboard Analítico Predictivo (IA)</h1>
        <p class="ag-header-desc">Proyección trimestral de demanda, adopción y saturación por Carrera (PNF) y Línea de Investigación mediante modelos supervisados de Machine Learning.</p>
    </div>
</div>

    <!-- SECCIÓN 1: TENDENCIAS (SERIES TEMPORALES) -->
    <div class="li-form-card" style="margin-bottom: 2rem;">
        <div class="li-form-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-chart-line-up" style="color: #505984; font-size: 1.25rem;"></i>
                <span style="color: #1e293b; font-weight: 700;">Proyección de Tendencias y Auditoría Predictiva</span>
            </div>
            <span style="font-size: 0.76rem; color: #505984; background: #eef0f7; border: 1px solid #d5daec; padding: 4px 11px; border-radius: 20px; font-weight: 600;">
                <i class="ph-bold ph-check-circle"></i> Base predeterminada: Proyectos PST Culminados
            </span>
        </div>

        <div class="li-form-body">
            
            <!-- BARRA DE CONTROLES PRINCIPAL (ALINEADA EN UNA SOLA LÍNEA) -->
            <div class="ml-control-bar">
                <div class="ml-filters-group">
                    <!-- Filtro por Carrera / PNF -->
                    <div class="ml-field-item">
                        <label for="select-carrera" class="ml-field-label">
                            <i class="ph-bold ph-graduation-cap"></i> Carrera / PNF
                        </label>
                        <select id="select-carrera" class="ml-select-control">
                            <option value="all" selected>Todas las Carreras / PNF</option>
                            <?php if (!empty($carreras) && is_array($carreras)): ?>
                                <?php foreach ($carreras as $car): ?>
                                    <?php $numLineas = (int)($car['total_lineas'] ?? 0); ?>
                                    <option value="<?= (int)$car['id'] ?>">
                                        <?= htmlspecialchars($car['nombre']) ?> (<?= $numLineas ?> <?= $numLineas === 1 ? 'línea' : 'líneas' ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Horizonte de Proyección -->
                    <div class="ml-field-item">
                        <label for="select-trimestres" class="ml-field-label">
                            <i class="ph-bold ph-calendar-plus"></i> Horizonte de Proyección
                        </label>
                        <select id="select-trimestres" class="ml-select-control">
                            <option value="1">1 Trimestre (Inmediato)</option>
                            <option value="2">2 Trimestres</option>
                            <option value="3">3 Trimestres (1 Año Académico)</option>
                            <option value="4" selected>4 Trimestres (Recomendado)</option>
                            <option value="5">5 Trimestres</option>
                            <option value="6">6 Trimestres (2 Años Académicos)</option>
                        </select>
                    </div>
                </div>
                
                <!-- Grupo de Botones Alineados -->
                <div class="ml-actions-group">
                    <button id="btn-advanced" type="button" class="ml-btn ml-btn-secondary">
                        <i class="ph-bold ph-faders"></i> Controles Avanzados
                    </button>
                    <button id="btn-toggle-backtest" type="button" class="ml-btn ml-btn-secondary" title="Ejecutar y visualizar pruebas de validación ciega (Walk-Forward Backtesting) sobre el dataset real cargado">
                        <i class="ph-bold ph-flask"></i> Validar Fiabilidad (Backtest)
                    </button>
                    <button id="btn-guardar-snapshot" type="button" class="ml-btn ml-btn-secondary" title="Guardar instantánea detallada de esta proyección por línea y trimestre para auditarla contra datos reales">
                        <i class="ph-bold ph-camera"></i> Guardar Snapshot
                    </button>
                    <button id="btn-proyectar" type="button" class="ml-btn ml-btn-primary">
                        <i class="ph-bold ph-arrows-clockwise"></i> Actualizar Modelo
                    </button>
                    <button id="btn-descargar-pdf" type="button" class="ml-btn ml-btn-dark" style="display: none;">
                        <i class="ph-bold ph-file-pdf" style="color: #94a3b8;"></i> Exportar Informe PDF
                    </button>
                </div>
            </div>

            <!-- CONTROLES AVANZADOS ML (Oculto por defecto) -->
            <div id="advanced-settings-panel" style="display: none; background: #f8fafc; border: 1px solid #cbd5e1; border-left: 4px solid #505984; border-radius: 10px; padding: 16px 18px; margin-bottom: 16px; gap: 16px; flex-wrap: wrap; align-items: flex-start;">
                <div style="flex: 1; min-width: 200px;">
                    <label for="select-historial" class="ml-field-label" style="margin-bottom: 5px;">
                        <i class="ph-bold ph-clock-counter-clockwise"></i> Muestreo de Historial
                    </label>
                    <select id="select-historial" class="ml-select-control" style="background: #ffffff;">
                        <option value="all">Todo el Historial Registrado</option>
                        <option value="2y" selected>Últimos 2 Años (6 Trimestres · Recomendado)</option>
                        <option value="1y">Último Año Académico (3 Trimestres)</option>
                    </select>
                    <small style="color: #64748b; font-size: 0.73rem; display: block; margin-top: 4px;">Acota la serie histórica analizada.</small>
                </div>

                <div style="flex: 1; min-width: 210px;">
                    <label for="select-ml-model" class="ml-field-label" style="margin-bottom: 5px;">
                        <i class="ph-bold ph-cpu"></i> Algoritmo ML / Regresor
                    </label>
                    <select id="select-ml-model" class="ml-select-control" style="background: #ffffff;">
                        <option value="auto">Auto-ML (Mejor Ajuste por Línea)</option>
                        <option value="linear" selected>Regresión Lineal Múltiple (OLS)</option>
                        <option value="poly">Regresión Polinómica (Grado 2)</option>
                        <option value="rf">Random Forest Regressor</option>
                    </select>
                    <small style="color: #64748b; font-size: 0.73rem; display: block; margin-top: 4px;">Motor matemático de proyección.</small>
                </div>

                <div style="flex: 1; min-width: 200px;">
                    <label for="select-fuente" class="ml-field-label" style="margin-bottom: 5px;">
                        <i class="ph-bold ph-database"></i> Fuente de Proyectos
                    </label>
                    <select id="select-fuente" class="ml-select-control" style="background: #ffffff;">
                        <option value="pst" selected>Solo Proyectos PST Culminados (Predeterminado)</option>
                        <option value="all">Proyectos PST + Investigaciones en Curso</option>
                    </select>
                    <small style="color: #64748b; font-size: 0.73rem; display: block; margin-top: 4px;">Filtra proyectos defendidos vs. ofertas activas.</small>
                </div>

                <div style="flex: 0.7; min-width: 145px;">
                    <label for="input-capacidad" class="ml-field-label" style="margin-bottom: 5px;">
                        <i class="ph-bold ph-users-three"></i> Capacidad / Línea
                    </label>
                    <input type="number" id="input-capacidad" value="25" min="5" max="500" class="ml-input-control" style="background: #ffffff;">
                    <small style="color: #64748b; font-size: 0.73rem; display: block; margin-top: 4px;">Tope trimestral para % saturación.</small>
                </div>

                <!-- CONTROLES EXCLUSIVOS PARA RANDOM FOREST (Ocultos cuando se elige Lineal, Polinómica o Auto) -->
                <div id="rf-only-controls" style="display: none; flex: 1.4; min-width: 270px; gap: 12px; background: #eef0f7; padding: 12px 14px; border-radius: 8px; border: 1px solid #cbd5e1; align-items: flex-start; flex-wrap: wrap;">
                    <div style="flex-basis: 100%; font-size: 0.72rem; font-weight: 800; color: #1e293b; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 6px;">
                        <i class="ph-bold ph-tree-structure" style="color: #505984;"></i> Parámetros Exclusivos de Random Forest
                    </div>
                    <div style="flex: 1; min-width: 120px;">
                        <label for="input-epochs" style="font-size: 0.76rem; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">N° Árboles (Max Epochs)</label>
                        <input type="number" id="input-epochs" value="150" min="10" max="500" class="ml-input-control" style="height: 36px; background: #ffffff;">
                    </div>
                    <div style="flex: 1; min-width: 120px;">
                        <label for="input-timeout" style="font-size: 0.76rem; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">Timeout Límite (s)</label>
                        <input type="number" id="input-timeout" value="10" min="2" max="120" class="ml-input-control" style="height: 36px; background: #ffffff;">
                    </div>
                </div>
            </div>

            <!-- INSIGNIAS DEL MOTOR IA, MODELO Y FIABILIDAD -->
            <div id="engine-badge-tendencias" style="display: none; flex-wrap: wrap; align-items: center; gap: 8px; margin-bottom: 12px; padding: 10px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;"></div>

            <!-- FILTRO INTERACTIVO DE LÍNEAS DE INVESTIGACIÓN VISIBLES -->
            <div id="lineas-filter-bar" style="display: none; flex-wrap: wrap; align-items: center; gap: 8px; margin-bottom: 16px; padding: 10px 14px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px;"></div>
            
            <div id="loading-tendencias" style="display: none; color: #1e293b; background: #eef0f7; border: 1px solid #cbd5e1; padding: 12px 16px; border-radius: 8px; font-weight: 600; margin-bottom: 1rem;">
                <i class="ph-bold ph-spinner ph-spin" style="color: #505984;"></i> Procesando series temporales y entrenando modelos de Machine Learning...
            </div>
            
            <div class="ml-main-grid" style="display: grid; grid-template-columns: minmax(0, 1.65fr) minmax(320px, 1fr); gap: 1.5rem; align-items: start;">
                
                <!-- Columna Izquierda: Gráfico + Insights + Estacionalidad -->
                <div style="min-width: 0;">
                    <!-- Gráfico de Adopción -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1.25rem; box-shadow: 0 2px 6px rgba(30, 41, 59, 0.04); min-width: 0; overflow: hidden; position: relative;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 8px;">
                            <div style="font-size: 0.9rem; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 6px;">
                                <i class="ph-bold ph-chart-line" style="color: #505984;"></i> Evolución Histórica (Proyectos Culminados) y Curva de Proyección
                            </div>
                            <span style="font-size: 0.74rem; color: #64748b;">
                                <span style="display:inline-block; width:16px; height:3px; background:#1e293b; vertical-align:middle; margin-right:4px;"></span>Histórico Real
                                &nbsp;&nbsp;
                                <span style="display:inline-block; width:16px; height:0; border-top:3px dashed #7090cb; vertical-align:middle; margin-right:4px;"></span>Proyección IA
                            </span>
                        </div>
                        <div style="position: relative; width: 100%; height: 340px;">
                            <canvas id="tendenciasChart"></canvas>
                        </div>
                    </div>

                    <!-- Insights Automáticos (4 tarjetas) -->
                    <div id="insights-tendencias" style="margin-top: 1.15rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.9rem;">
                    </div>
                </div>
                
                <!-- Panel de KPIs, Fiabilidad y Saturación -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1.25rem; box-shadow: 0 2px 6px rgba(30, 41, 59, 0.04);">
                    <h4 style="margin: 0 0 1rem 0; color: #1e293b; font-size: 0.98rem; display: flex; align-items: center; gap: 0.5rem; border-bottom: 2px solid #eef0f7; padding-bottom: 0.65rem;">
                        <i class="ph-fill ph-gauge" style="color: #505984;"></i> Fiabilidad, Error y Diagnóstico por Línea
                    </h4>
                    
                    <div id="metricas-tendencias-resultado" style="font-size: 0.9rem; color: #475569;">
                        <div class="li-empty-state" style="padding: 1.5rem; text-align: center;">
                            <i class="ph-bold ph-chart-bar" style="font-size: 2.2rem; color: #cbd5e1;"></i>
                            <p style="font-size: 0.85rem; margin-top: 0.5rem; color: #94a3b8;">Procesando el tensor histórico de líneas de investigación...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MATRIZ EJECUTIVA Y RECOMENDACIONES AUTOMÁTICAS -->
            <div id="matriz-ejecutiva-tendencias" style="display: none; margin-top: 1.5rem; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1.25rem; box-shadow: 0 2px 6px rgba(30, 41, 59, 0.04);">
            </div>

            <!-- LABORATORIO DE FIABILIDAD ML (BACKTESTING WALK-FORWARD CON DATOS REALES) -->
            <div id="backtest-tendencias-container" style="display: none; margin-top: 1.5rem; background: #ffffff; border: 1px solid #cbd5e1; border-left: 4px solid #505984; border-radius: 10px; padding: 1.25rem; box-shadow: 0 4px 12px rgba(30, 41, 59, 0.05);">
            </div>

            <!-- HISTORIAL DE SNAPSHOTS DE PREDICCIÓN (AUDITORÍA IA VS REALIDAD) -->
            <div id="snapshots-tendencias-container" style="display: none; margin-top: 1.5rem; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1.25rem; box-shadow: 0 2px 6px rgba(30, 41, 59, 0.04);">
            </div>

            <!-- MODAL DE AUDITORÍA ESPECÍFICA DE SNAPSHOT (DESGLOSE LÍNEA POR LÍNEA Y TRIMESTRE POR TRIMESTRE) -->
            <div id="snapshot-audit-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(3px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
                <div style="background: #ffffff; border-radius: 14px; width: 100%; max-width: 1020px; max-height: 90vh; overflow-y: auto; box-shadow: 0 24px 48px rgba(15, 23, 42, 0.28); border: 1px solid #cbd5e1;">
                    <div id="snapshot-audit-modal-content"></div>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- Cargar Chart.js desde CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Cargar el script que maneja la UI analítica -->
<script src="../modules/LineasInvestigacion/assets/analitica_ui.js?v=<?= @filemtime(__DIR__ . '/../assets/analitica_ui.js') ?: time() ?>"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('li-nodes-canvas-1');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let width = canvas.width = canvas.offsetWidth;
        let height = canvas.height = canvas.offsetHeight;
        
        window.addEventListener('resize', () => {
            if (!canvas) return;
            width = canvas.width = canvas.offsetWidth;
            height = canvas.height = canvas.offsetHeight;
        });

        const particles = [];
        const numParticles = 40;

        for (let i = 0; i < numParticles; i++) {
            particles.push({
                x: Math.random() * width,
                y: Math.random() * height,
                vx: (Math.random() - 0.5) * 0.5,
                vy: (Math.random() - 0.5) * 0.5,
                radius: Math.random() * 2 + 1.2
            });
        }

        function animate() {
            ctx.clearRect(0, 0, width, height);

            for (let i = 0; i < numParticles; i++) {
                const p = particles[i];
                p.x += p.vx;
                p.y += p.vy;

                if (p.x < 0 || p.x > width) p.vx *= -1;
                if (p.y < 0 || p.y > height) p.vy *= -1;

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(112, 144, 203, 0.75)';
                ctx.fill();

                for (let j = i + 1; j < numParticles; j++) {
                    const p2 = particles[j];
                    const dx = p.x - p2.x;
                    const dy = p.y - p2.y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    
                    if (dist < 100) {
                        ctx.beginPath();
                        ctx.moveTo(p.x, p.y);
                        ctx.lineTo(p2.x, p2.y);
                        ctx.strokeStyle = `rgba(112, 144, 203, ${0.4 - dist/250})`;
                        ctx.lineWidth = 0.8;
                        ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(animate);
        }
        animate();
    }
});
</script>
