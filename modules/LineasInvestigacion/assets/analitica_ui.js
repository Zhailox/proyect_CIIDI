// modules/LineasInvestigacion/assets/analitica_ui.js

document.addEventListener('DOMContentLoaded', () => {
    
    // Variables globales para Chart.js
    let chartInstance = null;

    // --- PROYECCIÓN DE TENDENCIAS ---
    const btnProyectar = document.getElementById('btn-proyectar');
    const loadingTendencias = document.getElementById('loading-tendencias');
    const metricasTendencias = document.getElementById('metricas-tendencias-resultado');

    btnProyectar.addEventListener('click', async () => {
        // Mostrar estado de carga
        btnProyectar.disabled = true;
        loadingTendencias.style.display = 'block';
        metricasTendencias.innerHTML = '<p class="text-muted">Procesando tensor...</p>';

        // Generar datos ficticios (Histórico de inscripciones de proyectos)
        const datosFicticios = {
            areas: [
                { name: "Ingeniería de Software", history: [20, 25, 30, 28, 40, null, 55] }, // Un null simulado
                { name: "Inteligencia Artificial", history: [5, 10, 18, 25, 45, 60, 85] },
                { name: "Redes y Seguridad", history: [40, 38, 42, 35, 30, 25, 20] } // Tendencia a la baja
            ]
        };

        try {
            // Llamada AJAX al controlador PHP nativo
            const response = await fetch('api-prediccion-tendencias', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datosFicticios)
            });

            const data = await response.json();

            if(data.error) {
                let errorHtml = `<p style="color:#ff4444">${data.error}</p>`;
                if (data.raw_output) {
                    errorHtml += `<pre style="color:#ffaa00; font-size:11px; background:#222; padding:5px;">${data.raw_output}</pre>`;
                }
                metricasTendencias.innerHTML = errorHtml;
            } else {
                renderizarGraficoTendencias(data);
                renderizarMetricasTendencias(data);
            }
        } catch (error) {
            metricasTendencias.innerHTML = `<p style="color:#ff4444">Error de conexión con el motor de IA.</p>`;
            console.error(error);
        } finally {
            btnProyectar.disabled = false;
            loadingTendencias.style.display = 'none';
        }
    });

    function renderizarGraficoTendencias(data) {
        const ctx = document.getElementById('tendenciasChart').getContext('2d');
        
        // Destruir gráfico previo si existe
        if (chartInstance) {
            chartInstance.destroy();
        }

        // Preparar datasets para Chart.js
        // Generar colores dinámicos si hay muchas áreas
        const baseColors = ['#0ea5e9', '#8b5cf6', '#10b981', '#f59e0b', '#ef4444', '#ec4899', '#6366f1', '#14b8a6'];
        
        // Determinar etiquetas del eje X. Si el backend las manda, usamos esas.
        // Si no, generamos genéricas para mostrar la data histórica + predicción
        let chartLabels = data.labels || [];
        if (chartLabels.length === 0) {
            // Asumimos que la primera área tiene el historial más largo
            const maxHistory = Math.max(...data.areas.map(a => a.history ? a.history.length : 0));
            const maxProjection = Math.max(...data.areas.map(a => a.adoption_curve ? a.adoption_curve.length : 0));
            
            for (let i = 0; i < maxHistory; i++) {
                chartLabels.push(`Mes ${i - maxHistory + 1}`); // Histórico
            }
            for (let i = 1; i <= maxProjection; i++) {
                chartLabels.push(`Proyección +${i}`); // Futuro
            }
        }

        const lineData = data.areas.map((a, index) => {
            const color = baseColors[index % baseColors.length];
            
            // Unimos la data histórica real con la curva de predicción para ver el panorama completo
            const historicalData = a.history || [];
            const predictionData = a.adoption_curve || [];
            
            // Rellenar con nulls la predicción donde va el histórico y viceversa
            const combinedData = [...historicalData, ...predictionData];

            return {
                label: a.name,
                data: combinedData,
                borderColor: color,
                backgroundColor: color + '20',
                borderWidth: 3,
                tension: 0.4,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: color,
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true // Añadir un leve sombreado inferior
            };
        });

        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: lineData
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { font: { family: 'inherit', size: 13 } }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    },
                    x: {
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    }
                }
            }
        });
    }

    function renderizarMetricasTendencias(data) {
        let html = `<div style="margin-bottom: 10px; color: #1e293b;">
            <strong>Error Global (RMSE):</strong> <span class="badge badge-success" style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 3px 8px; border-radius: 4px; margin-left: 5px;">${data.metrics.global_rmse}</span>
            <div style="font-size: 0.8rem; color: #64748b; margin-top: 5px;">Margen de error de la IA en la cantidad de proyectos.</div>
        </div>
        <hr style="border-color: #e2e8f0; margin: 10px 0;">
        <strong style="color: #1e293b;">Detalles por Línea de Investigación:</strong>
        <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 10px;">Proyección para el próximo mes y nivel de saturación operativa.</div>
        <ul style="list-style:none; padding:0; margin-top:10px;">`;
        
        data.areas.forEach(area => {
            let satColor = area.saturation_index > 80 ? '#ef4444' : (area.saturation_index > 50 ? '#f59e0b' : '#10b981');
            
            // Extraer la predicción del próximo ciclo (el primer valor de adoption_curve)
            const predictionData = area.adoption_curve || [];
            const nextPrediction = predictionData.length > 0 ? Math.round(predictionData[0]) : 0;
            
            // Determinar la tendencia (si creció respecto al mes anterior)
            const historyData = area.history || [];
            const lastMonth = historyData.length > 0 ? historyData[historyData.length - 1] : 0;
            const isGrowing = nextPrediction >= lastMonth;
            const trendLabel = isGrowing ? '<span style="color:#ef4444; font-size:12px;">▲ Sube</span>' : '<span style="color:#10b981; font-size:12px;">▼ Baja</span>';

            html += `<li style="margin-bottom:12px; padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px;">
                <div class="flex-between" style="display:flex; justify-content:space-between; margin-bottom:4px; font-size: 0.90rem;">
                    <strong style="color:#334155; max-width: 70%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${area.name}">${area.name}</strong>
                    <span style="color:${satColor}; font-weight:bold;">${area.saturation_index}% Sat.</span>
                </div>
                <div class="flex-between" style="display:flex; justify-content:space-between; margin-bottom:8px; font-size: 0.85rem; color:#64748b;">
                    <span>Predicción: <strong>${nextPrediction} proyectos</strong></span>
                    ${trendLabel}
                </div>
                <div style="width:100%; background:#e2e8f0; height:6px; border-radius:3px; overflow:hidden;">
                    <div style="width:${area.saturation_index}%; background:${satColor}; height:100%; border-radius:3px;"></div>
                </div>
            </li>`;
        });
        html += `</ul>`;
        metricasTendencias.innerHTML = html;
    }

    // --- CLASIFICADOR DOCUMENTAL NLP ---
    const btnCargarPrueba = document.getElementById('btn-cargar-nlp');
    const btnClasificar = document.getElementById('btn-clasificar');
    const inputTitulo = document.getElementById('nlp-titulo');
    const inputResumen = document.getElementById('nlp-resumen');
    const inputObjetivos = document.getElementById('nlp-objetivos');
    const loadingNlp = document.getElementById('loading-nlp');
    const boxNlp = document.getElementById('resultado-nlp-box');

    btnCargarPrueba.addEventListener('click', () => {
        inputTitulo.value = "Implementación de una red neuronal convolucional";
        inputResumen.value = "Este trabajo de investigación presenta un modelo avanzado de machine learning para el reconocimiento óptico de caracteres. Se exploran arquitecturas de deep learning optimizadas.";
        inputObjetivos.value = "1. Desarrollar el algoritmo base.\n2. Entrenar la red neuronal con un dataset de 10k imágenes.\n3. Evaluar la precisión de la inteligencia artificial.";
    });

    btnClasificar.addEventListener('click', async () => {
        const payload = {
            title: inputTitulo.value,
            abstract: inputResumen.value,
            objectives: inputObjetivos.value
        };

        if(!payload.abstract) {
            boxNlp.innerHTML = `<p style="color:#f59e0b; padding:1rem; background:#fef3c7; border-radius:6px;">Por favor, ingrese al menos un resumen abstracto.</p>`;
            return;
        }

        btnClasificar.disabled = true;
        loadingNlp.style.display = 'block';
        boxNlp.innerHTML = '';

        try {
            const response = await fetch('api-clasificacion-automatica', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if(data.error) {
                let errorHtml = `<p style="color:#ef4444; margin-bottom:0.5rem;">${data.error}</p>`;
                if (data.raw_output) {
                    errorHtml += `<pre style="color:#b45309; font-size:11px; background:#fef3c7; padding:10px; border-radius:4px; max-height:150px; overflow-y:auto; border:1px solid #fde68a;">${data.raw_output}</pre>`;
                }
                boxNlp.innerHTML = errorHtml;
            } else {
                renderizarResultadoNlp(data);
            }

        } catch (error) {
            boxNlp.innerHTML = `<p style="color:#ef4444">Error al conectar con el motor NLP.</p>`;
            console.error(error);
        } finally {
            btnClasificar.disabled = false;
            loadingNlp.style.display = 'none';
        }
    });

    function renderizarResultadoNlp(data) {
        // Matriz de confusión visual sencilla
        const cm = data.metrics.confusion_matrix;
        let cmHtml = cm ? `<small style="display:block; margin-top:5px; color:#64748b; font-family:monospace; background:#f8fafc; padding:8px; border-radius:4px; border:1px solid #e2e8f0;">[${cm.map(row => '['+row.join(',')+']').join(', ')}]</small>` : '';

        boxNlp.innerHTML = `
            <div style="font-size:1.1em; margin-bottom:15px; color:#475569;">
                Categoría Predicha:<br>
                <strong style="font-size:1.5em; color:#6366f1;"><i class="ph-fill ph-tag"></i> ${data.predicted_category}</strong>
            </div>
            
            <div style="display:flex; justify-content:space-between; border-top:1px solid #e2e8f0; padding-top:1rem; gap: 1rem;">
                <div style="flex:1;">
                    <span style="color:#64748b; font-size:0.9em;">F1-Score (Precisión):</span><br>
                    <span class="badge badge-success" style="display:inline-block; margin-top:5px; background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 4px 10px; border-radius: 4px; font-weight:bold;">${data.metrics.f1_score}</span>
                </div>
                <div style="flex:2;">
                    <span style="color:#64748b; font-size:0.9em;">Matriz de Confusión:</span>
                    ${cmHtml}
                </div>
            </div>
            
            <div style="margin-top:15px; padding:12px; background:#f8fafc; border: 1px solid #e2e8f0; border-radius:6px; font-size:0.85em; color:#64748b; font-style:italic;">
                <em>Tensor procesado:</em> "${data.extracted_text_preview}"
            </div>
        `;
    }

});
