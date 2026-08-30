// modules/LineasInvestigacion/assets/analitica_ui.js

document.addEventListener('DOMContentLoaded', () => {
    
    // Variables globales para Chart.js
    let chartInstance = null;

    // --- PROYECCIÓN DE TENDENCIAS ---
    const btnProyectar = document.getElementById('btn-proyectar');
    const loadingTendencias = document.getElementById('loading-tendencias');
    const metricasTendencias = document.getElementById('metricas-tendencias-resultado');

    btnProyectar.addEventListener('click', async () => {
        // Leer la cantidad de trimestres a proyectar
        const selectTrimestres = document.getElementById('select-trimestres');
        const predSteps = selectTrimestres ? parseInt(selectTrimestres.value) : 4;

        // Mostrar estado de carga
        btnProyectar.disabled = true;
        loadingTendencias.style.display = 'block';
        metricasTendencias.innerHTML = '<p class="text-muted">Procesando tensor...</p>';

        try {
            // Llamada AJAX al controlador PHP nativo
            const response = await fetch('api-prediccion-tendencias', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ pred_steps: predSteps })
            });

            const data = await response.json();

            if(data.error) {
                let errorHtml = `<p style="color:#ff4444">${data.error}</p>`;
                if (data.raw_output) {
                    errorHtml += `<pre style="color:#ffaa00; font-size:11px; background:#222; padding:5px;">${data.raw_output}</pre>`;
                }
                metricasTendencias.innerHTML = errorHtml;
            } else {
                renderizarGraficoTendencias(data, predSteps);
                renderizarMetricasTendencias(data, predSteps);
                renderizarInsights(data, predSteps);
            }
        } catch (error) {
            metricasTendencias.innerHTML = `<p style="color:#ff4444">Error de conexión con el motor de IA.</p>`;
            console.error(error);
        } finally {
            btnProyectar.disabled = false;
            loadingTendencias.style.display = 'none';
        }
    });

    function renderizarGraficoTendencias(data, predSteps) {
        const ctx = document.getElementById('tendenciasChart').getContext('2d');
        
        if (chartInstance) {
            chartInstance.destroy();
        }

        // Paleta de colores Corporativa 100% exacta
        const baseColors = [
            '#121a3e', // Azul Oscuro (Ingeniería de Software)
            '#505984', // Azul Gris (Redes)
            '#7090cb', // Azul Claro (IA)
            '#f59e0b'  // Naranja complementario para Seguridad
        ];
        
        const maxHistory = Math.max(...data.areas.map(a => a.history ? a.history.length : 0));
        
        // Generar Fechas Reales (3 Trimestres por Año)
        let chartLabels = [];
        let date = new Date();
        // Un año de 3 trimestres equivale a cuatrimestres (cada 4 meses)
        let currentT = Math.floor(date.getMonth() / 4) + 1;
        let currentY = date.getFullYear();

        // Retroceder en el tiempo para alinear el historial
        let startT = currentT;
        let startY = currentY;
        for(let i = 0; i < maxHistory - 1; i++) {
            startT--;
            if(startT < 1) { startT = 3; startY--; }
        }

        let tempT = startT;
        let tempY = startY;

        // Etiquetas del Pasado
        for(let i = 0; i < maxHistory; i++) {
            chartLabels.push(`T${tempT} ${tempY}`);
            tempT++;
            if(tempT > 3) { tempT = 1; tempY++; }
        }
        // Etiquetas del Futuro
        for(let i = 0; i < predSteps; i++) {
            chartLabels.push(`T${tempT} ${tempY} (Proy)`);
            tempT++;
            if(tempT > 3) { tempT = 1; tempY++; }
        }

        const lineData = data.areas.map((a, index) => {
            const color = baseColors[index % baseColors.length];
            const historicalData = a.history || [];
            const predictionData = a.adoption_curve || [];
            const combinedData = [...historicalData, ...predictionData];

            return {
                label: a.name,
                data: combinedData,
                borderColor: color,
                backgroundColor: color,
                borderWidth: 4, // Líneas más gruesas y estéticas
                tension: 0.4, // Curvatura suave
                pointBackgroundColor: color,
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 8,
                fill: false, // Sin relleno para que no se cubran
                segment: {
                    // Si el punto es del futuro, se dibuja punteado
                    borderDash: ctx => ctx.p0DataIndex >= historicalData.length - 1 ? [8, 6] : undefined,
                    // Reducimos un poco la opacidad de la predicción para resaltarla
                    borderColor: ctx => ctx.p0DataIndex >= historicalData.length - 1 ? color + '80' : color
                }
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
                        labels: { 
                            font: { family: 'inherit', size: 13, weight: 'bold' },
                            color: '#121a3e',
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(18, 26, 62, 0.9)', // Tooltip corporativo
                        titleFont: { size: 14, family: 'inherit' },
                        bodyFont: { size: 13, family: 'inherit' },
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const dataIndex = context.dataIndex;
                                const label = context.dataset.label || '';
                                const value = context.parsed.y;
                                if (dataIndex >= maxHistory) {
                                    return ` ${label}: ${value} (Esperados)`;
                                }
                                return ` ${label}: ${value} (Histórico)`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(80, 89, 132, 0.1)', borderDash: [5, 5] },
                        ticks: { color: '#505984', font: { weight: '600' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#505984', font: { weight: '600' } }
                    }
                }
            }
        });
    }

    function renderizarMetricasTendencias(data, predSteps) {
        let date = new Date();
        let currentMonth = date.getMonth() + 1;
        let currentT = 1;
        if (currentMonth >= 1 && currentMonth <= 4) currentT = 1;
        else if (currentMonth >= 5 && currentMonth <= 8) currentT = 2;
        else currentT = 3;
        
        let currentY = date.getFullYear();
        
        // Calcular etiquetas futuras para la línea de tiempo
        let futureLabels = [];
        let tempT = currentT;
        let tempY = currentY;
        for (let i = 0; i < predSteps; i++) {
            tempT++;
            if (tempT > 3) { tempT = 1; tempY++; }
            futureLabels.push(`T${tempT} '${tempY.toString().slice(-2)}`);
        }
        let targetLabel = futureLabels[futureLabels.length - 1];

        let errorMargen = Math.round(data.metrics.global_rmse);
        let errorText = errorMargen === 0 ? "Precisión Alta" : `±${errorMargen} proy.`;

        let html = `<div style="margin-bottom: 15px; color: #1e293b; background: #f8fafc; padding: 10px; border-radius: 6px; border-left: 4px solid #7090cb;">
            <div style="display:flex; flex-wrap: wrap; justify-content:space-between; align-items:center; gap: 5px;">
                <strong style="font-size: 0.95rem; flex: 1; min-width: 120px;">Margen (RMSE):</strong>
                <span style="background: rgba(112, 144, 203, 0.15); color: #121a3e; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 0.85rem; flex-shrink: 0;">${errorText}</span>
            </div>
            <div style="font-size: 0.8rem; color: #64748b; margin-top: 6px;">Margen estadístico de error en la IA.</div>
        </div>
        
        <strong style="color: #121a3e; font-size: 1.05rem; display:block; margin-top: 20px; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px;">
            Diagnóstico
        </strong>
        <div style="font-size: 0.8rem; color: #64748b; margin: 8px 0 15px 0;">Tendencias proyectadas hasta <strong>${targetLabel}</strong>.</div>
        
        <ul style="list-style:none; padding:0; margin:0;">`;
        
        data.areas.forEach(area => {
            const predictionData = area.adoption_curve || [];
            const targetPrediction = predictionData.length > 0 ? Math.round(predictionData[predSteps - 1]) : 0;
            
            const historyData = area.history || [];
            const lastMonth = historyData.length > 0 ? historyData[historyData.length - 1] : 0;
            
            let delta = 0;
            if (lastMonth > 0) {
                delta = ((targetPrediction - lastMonth) / lastMonth) * 100;
            } else {
                delta = targetPrediction > 0 ? 100 : 0;
            }

            let classLabel = '';
            let classColorBg = '';
            let classColorTxt = '';

            if (delta > 20) {
                classLabel = 'Crec. Acelerado';
                classColorBg = '#dbeafe'; 
                classColorTxt = '#1e3a8a';
            } else if (delta > 5) {
                classLabel = 'Tendencia Positiva';
                classColorBg = '#e0e7ff';
                classColorTxt = '#3730a3';
            } else if (delta >= -5 && delta <= 5) {
                classLabel = 'Estable';
                classColorBg = '#f1f5f9';
                classColorTxt = '#475569';
            } else {
                classLabel = 'En Declive';
                classColorBg = '#fef2f2';
                classColorTxt = '#991b1b';
            }

            let satInt = Math.round(area.saturation_index);
            let satColor = satInt > 80 ? '#ef4444' : (satInt > 50 ? '#f59e0b' : '#10b981');

            // Generar la Ruta de Etiquetas (Timeline Badges)
            let timelineHtml = `<div style="display:flex; flex-wrap:nowrap; overflow-x:auto; gap:6px; margin-top:12px; padding-bottom:4px; align-items:center;">`;
            for(let i=0; i<predSteps; i++) {
                let val = Math.round(predictionData[i]);
                timelineHtml += `
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:4px; padding:3px 6px; font-size:0.7rem; color:#475569; white-space:nowrap;">
                        <strong style="color:#121a3e;">${futureLabels[i]}:</strong> ${val}
                    </div>`;
                if (i < predSteps - 1) {
                    timelineHtml += `<i class="ph-bold ph-arrow-right" style="color:#cbd5e1; font-size:0.7rem;"></i>`;
                }
            }
            timelineHtml += `</div>`;

            html += `<li style="margin-bottom:12px; padding: 12px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                
                <div style="display:flex; align-items:flex-start; gap: 8px; margin-bottom:8px;">
                    <div style="flex: 1; min-width: 0;">
                        <strong style="color:#121a3e; font-size: 0.90rem; display:block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${area.name}">
                            ${area.name}
                        </strong>
                        <span style="display:inline-block; margin-top:4px; font-size:0.7rem; font-weight:bold; padding:2px 6px; border-radius:12px; background:${classColorBg}; color:${classColorTxt}; border: 1px solid ${classColorBg}; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;">
                            ${classLabel}
                        </span>
                    </div>
                    <div style="flex-shrink: 0; text-align: right; width: 45px;">
                        <span style="color:${satColor}; font-weight:bold; font-size: 1rem; display:block;">${satInt}%</span>
                        <span style="font-size: 0.65rem; color:#64748b; font-weight:bold;">SAT.</span>
                    </div>
                </div>
                
                <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:6px; font-size: 0.80rem; color:#475569;">
                    <span>Demanda Final: <strong style="color:#121a3e;">${targetPrediction} proy.</strong></span>
                </div>
                
                <div style="width:100%; background:#f1f5f9; height:6px; border-radius:3px; overflow:hidden;">
                    <div style="width:${satInt}%; background:${satColor}; height:100%; border-radius:3px; transition: width 0.8s ease-in-out;"></div>
                </div>
                
                ${timelineHtml}
            </li>`;
        });
        html += `</ul>`;
        metricasTendencias.innerHTML = html;
    }

    function renderizarInsights(data, predSteps) {
        const container = document.getElementById('insights-tendencias');
        if (!container) return;
        
        // Cambiamos a Grid 2x2 para acomodar 4 insights
        container.style.gridTemplateColumns = 'repeat(2, 1fr)';

        let maxDemanda = { name: '-', val: -1 };
        let minDemanda = { name: '-', val: 999999 };
        let maxAceleracion = { name: '-', growthPct: -999, absoluteGrowth: 0 };
        
        let sumaPorTrimestre = new Array(predSteps).fill(0);
        let totalProyectosFuturo = 0;

        data.areas.forEach(area => {
            const predData = area.adoption_curve || [];
            const histData = area.history || [];
            
            if (predData.length >= predSteps) {
                // Última predicción
                const tPred = Math.round(predData[predSteps - 1]);
                if (tPred > maxDemanda.val) { maxDemanda.val = tPred; maxDemanda.name = area.name; }
                if (tPred < minDemanda.val) { minDemanda.val = tPred; minDemanda.name = area.name; }
                
                // Aceleración (Pendiente)
                const slope = predData.length > 1 ? (predData[1] - predData[0]) : 0;
                const lastHist = histData.length > 0 ? histData[histData.length - 1] : 0;
                const growthPct = lastHist > 0 ? (slope / lastHist) * 100 : 0;
                
                if (growthPct > maxAceleracion.growthPct) {
                    maxAceleracion.name = area.name;
                    maxAceleracion.growthPct = growthPct;
                    maxAceleracion.absoluteGrowth = Math.round(slope);
                }
                
                for(let i=0; i<predSteps; i++) {
                    sumaPorTrimestre[i] += Math.round(predData[i]);
                    totalProyectosFuturo += Math.round(predData[i]);
                }
            }
        });

        let peakIndex = 0;
        let maxSum = -1;
        for(let i=0; i<predSteps; i++) {
            if (sumaPorTrimestre[i] > maxSum) {
                maxSum = sumaPorTrimestre[i];
                peakIndex = i;
            }
        }
        
        let date = new Date();
        let currentMonth = date.getMonth() + 1;
        let startT = (currentMonth >= 1 && currentMonth <= 4) ? 1 : ((currentMonth >= 5 && currentMonth <= 8) ? 2 : 3);
        let startY = date.getFullYear();
        
        let peakT = startT;
        let peakY = startY;
        for(let i=0; i<=peakIndex; i++) {
            peakT++;
            if (peakT > 3) { peakT = 1; peakY++; }
        }
        let peakLabel = `T${peakT} ${peakY}`;

        const cPrincipal = '#121a3e';
        const cSecundario = '#505984';
        const cTerciario = '#7090cb';
        const cComplemento = '#f59e0b'; // Naranja corporativo

        let html = `
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px; box-shadow: 0 4px 6px -1px rgba(18, 26, 62, 0.05); position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px;">
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 0.70rem; color: ${cSecundario}; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">Línea Líder (Demanda)</div>
                        <div style="font-size: 1.05rem; color: ${cPrincipal}; font-weight: 800; margin-top: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${maxDemanda.name}">${maxDemanda.name}</div>
                    </div>
                    <div style="background: rgba(18, 26, 62, 0.05); padding: 10px; border-radius: 8px; flex-shrink: 0;">
                        <i class="ph-bold ph-rocket" style="font-size: 1.4rem; color: ${cPrincipal};"></i>
                    </div>
                </div>
                <div style="margin-top: 15px; display: flex; align-items: center;">
                    <span style="font-size: 0.85rem; color: ${cSecundario}; background: #f8fafc; padding: 4px 8px; border-radius: 4px; border: 1px solid #e2e8f0; width: 100%;">Cima de <strong style="color: ${cPrincipal};">${maxDemanda.val} proy.</strong></span>
                </div>
                <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 4px; background: ${cPrincipal};"></div>
            </div>
            
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px; box-shadow: 0 4px 6px -1px rgba(80, 89, 132, 0.05); position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px;">
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 0.70rem; color: ${cSecundario}; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">Mayor Aceleración</div>
                        <div style="font-size: 1.05rem; color: ${cPrincipal}; font-weight: 800; margin-top: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${maxAceleracion.name}">${maxAceleracion.name}</div>
                    </div>
                    <div style="background: rgba(245, 158, 11, 0.1); padding: 10px; border-radius: 8px; flex-shrink: 0;">
                        <i class="ph-bold ph-trend-up" style="font-size: 1.4rem; color: ${cComplemento};"></i>
                    </div>
                </div>
                <div style="margin-top: 15px; display: flex; align-items: center;">
                    <span style="font-size: 0.85rem; color: ${cSecundario}; background: #f8fafc; padding: 4px 8px; border-radius: 4px; border: 1px solid #e2e8f0; width: 100%;">Crece un <strong style="color: ${cComplemento};">+${maxAceleracion.growthPct.toFixed(1)}%</strong> aprox.</span>
                </div>
                <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 4px; background: ${cComplemento};"></div>
            </div>

            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px; box-shadow: 0 4px 6px -1px rgba(80, 89, 132, 0.05); position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px;">
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 0.70rem; color: ${cSecundario}; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">Menor Interés</div>
                        <div style="font-size: 1.05rem; color: ${cPrincipal}; font-weight: 800; margin-top: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${minDemanda.name}">${minDemanda.name}</div>
                    </div>
                    <div style="background: rgba(80, 89, 132, 0.08); padding: 10px; border-radius: 8px; flex-shrink: 0;">
                        <i class="ph-bold ph-arrow-down-right" style="font-size: 1.4rem; color: ${cSecundario};"></i>
                    </div>
                </div>
                <div style="margin-top: 15px; display: flex; align-items: center;">
                    <span style="font-size: 0.85rem; color: ${cSecundario}; background: #f8fafc; padding: 4px 8px; border-radius: 4px; border: 1px solid #e2e8f0; width: 100%;">Fondo de <strong style="color: ${cPrincipal};">${minDemanda.val} proy.</strong></span>
                </div>
                <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 4px; background: ${cSecundario};"></div>
            </div>
            
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px; box-shadow: 0 4px 6px -1px rgba(112, 144, 203, 0.05); position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px;">
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 0.70rem; color: ${cSecundario}; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">Trimestre Pico</div>
                        <div style="font-size: 1.05rem; color: ${cPrincipal}; font-weight: 800; margin-top: 6px;">${peakLabel}</div>
                    </div>
                    <div style="background: rgba(112, 144, 203, 0.1); padding: 10px; border-radius: 8px; flex-shrink: 0;">
                        <i class="ph-bold ph-lightning" style="font-size: 1.4rem; color: ${cTerciario};"></i>
                    </div>
                </div>
                <div style="margin-top: 15px; display: flex; align-items: center;">
                    <span style="font-size: 0.85rem; color: ${cSecundario}; background: #f8fafc; padding: 4px 8px; border-radius: 4px; border: 1px solid #e2e8f0; width: 100%;">Carga: <strong style="color: ${cPrincipal};">${maxSum} simultáneos</strong></span>
                </div>
                <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 4px; background: ${cTerciario};"></div>
            </div>
        `;
        
        container.innerHTML = html;
    }
});
