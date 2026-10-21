// modules/LineasInvestigacion/assets/analitica_ui.js

const pdfScript = document.createElement('script');
pdfScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js';
document.head.appendChild(pdfScript);

document.addEventListener('DOMContentLoaded', () => {
    
    // Variables globales para Chart.js
    window.tendenciasChart = null;

    // --- PROYECCIÓN DE TENDENCIAS ---
    const btnProyectar = document.getElementById('btn-proyectar');
    const loadingTendencias = document.getElementById('loading-tendencias');
    const metricasTendencias = document.getElementById('metricas-tendencias-resultado');

    // Lectura segura de un control numérico con valor por defecto y límites
    function leerEntero(id, defecto, min, max) {
        const el = document.getElementById(id);
        const v = el ? parseInt(el.value, 10) : NaN;
        if (isNaN(v)) return defecto;
        return Math.min(max, Math.max(min, v));
    }
    function leerValor(id, defecto) {
        const el = document.getElementById(id);
        return el && el.value ? el.value : defecto;
    }
    function escaparHtml(txt) {
        return String(txt ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    }

    btnProyectar.addEventListener('click', async () => {
        // Leer la cantidad de trimestres a proyectar
        const predSteps = leerEntero('select-trimestres', 4, 1, 12);

        // Controles Avanzados ML (se envían todos al backend)
        const params = {
            pred_steps: predSteps,
            historial_base: leerValor('select-historial', 'all'),
            ml_model: leerValor('select-ml-model', 'linear'),
            max_epochs: leerEntero('input-epochs', 500, 1, 5000),
            timeout: leerEntero('input-timeout', 10, 1, 120)
        };

        // Mostrar estado de carga
        btnProyectar.disabled = true;
        loadingTendencias.style.display = 'block';
        metricasTendencias.innerHTML = '<p class="text-muted">Procesando tensor...</p>';

        try {
            // Llamada AJAX al controlador PHP nativo
            const response = await fetch('api-prediccion-tendencias', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(params)
            });

            const data = await response.json();

            if(data.error) {
                let errorHtml = `<p style="color:#ff4444">${escaparHtml(data.error)}</p>`;
                if (data.raw_output || data.raw) {
                    errorHtml += `<pre style="color:#ffaa00; font-size:11px; background:#222; padding:5px; white-space:pre-wrap;">${escaparHtml(data.raw_output || data.raw)}</pre>`;
                }
                metricasTendencias.innerHTML = errorHtml;
            } else {
                // El backend puede limitar pred_steps: usamos el valor efectivo
                const pasos = (data.future_labels && data.future_labels.length) ? data.future_labels.length : predSteps;
                renderizarBadgeMotor(data, params);
                renderizarGraficoTendencias(data, pasos);
                renderizarMetricasTendencias(data, pasos);
                renderizarInsights(data, pasos);
                document.getElementById('btn-descargar-pdf').style.display = 'flex';
            }
        } catch (error) {
            metricasTendencias.innerHTML = `<div style="background:#fff1f2; border:1px solid #fda4af; padding:10px; border-radius:6px; color:#be123c;">
                <strong>Aviso de Motor IA:</strong> Ocurrió un fallo en el cliente de gráficos.
                <br><span style="font-size:0.75rem;">${error.message}</span>
            </div>`;
            console.error(error);
        } finally {
            btnProyectar.disabled = false;
            loadingTendencias.style.display = 'none';
        }
    });

    // Insignia visible del motor que produjo el resultado (nunca silencioso)
    function renderizarBadgeMotor(data, params) {
        const cont = document.getElementById('engine-badge-tendencias');
        if (!cont) return;
        const esPython = data.engine === 'python';
        const hist = data.history_labels || [];
        const periodo = hist.length ? `${hist[0]} – ${hist[hist.length - 1]} (${hist.length} trimestres)` : 'sin datos';
        const ventanas = { all: 'Todo el historial', '2y': 'Últimos 2 años', '1y': 'Último año' };
        const extraRf = data.model_used === 'rf' && data.params && data.params.n_estimators ? ` · ${data.params.n_estimators} árboles` : '';

        const chip = (icono, texto, bg, color, borde, titulo = '') =>
            `<span title="${escaparHtml(titulo)}" style="display:inline-flex; align-items:center; gap:5px; font-size:0.75rem; font-weight:700; padding:4px 10px; border-radius:50px; background:${bg}; color:${color}; border:1px solid ${borde};"><i class="ph-bold ${icono}"></i>${texto}</span>`;

        let html = esPython
            ? chip('ph-cpu', 'Motor: Python · scikit-learn', '#dcfce7', '#166534', '#86efac', data.engine_detail || '')
            : chip('ph-warning', 'Motor: PHP de respaldo', '#fef3c7', '#92400e', '#fcd34d', data.engine_detail || '');
        html += chip('ph-function', escaparHtml((data.model_label || params.ml_model) + extraRf), '#eef0f7', '#505984', '#c7cbe0');
        html += chip('ph-calendar', `${escaparHtml(ventanas[params.historial_base] || params.historial_base)}: ${escaparHtml(periodo)}`, '#f8fafc', '#475569', '#e2e8f0');

        if (!esPython && data.fallback_reason) {
            html += `<div style="flex-basis:100%; font-size:0.75rem; color:#92400e; margin-top:2px;"><i class="ph-bold ph-info"></i> Python no disponible: ${escaparHtml(data.fallback_reason)}</div>`;
        }
        cont.innerHTML = html;
        cont.style.display = 'flex';
    }

    function renderizarGraficoTendencias(data, predSteps) {
        const ctx = document.getElementById('tendenciasChart').getContext('2d');
        
        if (window.tendenciasChart) {
            window.tendenciasChart.destroy();
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
        const etiquetasBackend = Array.isArray(data.history_labels) && data.history_labels.length === maxHistory
            && Array.isArray(data.future_labels) && data.future_labels.length >= predSteps;

        if (etiquetasBackend) {
            // El backend devuelve exactamente los trimestres analizados (ventana de historial aplicada)
            chartLabels = [
                ...data.history_labels,
                ...data.future_labels.slice(0, predSteps).map(l => `${l} (Proy)`)
            ];
        } else {
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

        window.tendenciasChart = new Chart(ctx, {
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
        // Calcular etiquetas futuras para la línea de tiempo
        let futureLabels = [];
        if (Array.isArray(data.future_labels) && data.future_labels.length >= predSteps) {
            // "T2 2026" -> "T2 '26"
            futureLabels = data.future_labels.slice(0, predSteps).map(l => l.replace(/^(T\d) \d{2}(\d{2})$/, "$1 '$2"));
        } else {
        let date = new Date();
        let currentMonth = date.getMonth() + 1;
        let currentT = 1;
        if (currentMonth >= 1 && currentMonth <= 4) currentT = 1;
        else if (currentMonth >= 5 && currentMonth <= 8) currentT = 2;
        else currentT = 3;
        
        let currentY = date.getFullYear();
        
        let tempT = currentT;
        let tempY = currentY;
        for (let i = 0; i < predSteps; i++) {
            tempT++;
            if (tempT > 3) { tempT = 1; tempY++; }
            futureLabels.push(`T${tempT} '${tempY.toString().slice(-2)}`);
        }
        }
        let targetLabel = futureLabels[futureLabels.length - 1];

        // RMSE con 2 decimales: redondear a entero ocultaba la diferencia entre modelos
        const rmse = Number(data.metrics && data.metrics.global_rmse) || 0;
        let errorText = rmse < 0.005 ? "Ajuste exacto" : `±${rmse.toFixed(2)} proy.`;

        // Comparativa de RMSE de los 3 modelos sobre la misma ventana de datos
        let comparativaHtml = '';
        const porModelo = data.metrics && data.metrics.rmse_by_model;
        if (porModelo) {
            const nombres = { linear: 'Lineal', poly: 'Polinómica', rf: 'Random Forest' };
            comparativaHtml = `<div style="display:flex; gap:6px; flex-wrap:wrap; margin-top:8px;">` +
                Object.keys(nombres).filter(k => porModelo[k] !== undefined).map(k => {
                    const activo = k === data.model_used;
                    return `<span title="RMSE de ajuste del modelo ${nombres[k]}" style="font-size:0.7rem; padding:2px 7px; border-radius:4px; border:1px solid ${activo ? '#505984' : '#e2e8f0'}; background:${activo ? '#505984' : '#ffffff'}; color:${activo ? '#ffffff' : '#475569'}; font-weight:${activo ? '700' : '600'};">${nombres[k]}: ${Number(porModelo[k]).toFixed(2)}</span>`;
                }).join('') + `</div>`;
        }

        let html = `<div style="margin-bottom: 15px; color: #1e293b; background: #f8fafc; padding: 10px; border-radius: 6px; border-left: 4px solid #7090cb;">
            <div style="display:flex; flex-wrap: wrap; justify-content:space-between; align-items:center; gap: 5px;">
                <strong style="font-size: 0.95rem; flex: 1; min-width: 120px;">Margen (RMSE):</strong>
                <span style="background: rgba(112, 144, 203, 0.15); color: #121a3e; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 0.85rem; flex-shrink: 0;">${errorText}</span>
            </div>
            <div style="font-size: 0.8rem; color: #64748b; margin-top: 6px;">Margen estadístico de error en la IA (${escaparHtml(data.model_label || 'modelo')}).</div>
            ${comparativaHtml}
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
        
        let peakLabel = (Array.isArray(data.future_labels) && data.future_labels[peakIndex]) ? data.future_labels[peakIndex] : '';
        if (!peakLabel) {
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
        peakLabel = `T${peakT} ${peakY}`;
        }

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

    // --- EXPORTAR A PDF ---
    const btnDescargarPdf = document.getElementById('btn-descargar-pdf');
    if (btnDescargarPdf) {
        btnDescargarPdf.addEventListener('click', () => {
            const pdfWrapper = document.createElement('div');
            pdfWrapper.style.padding = '20px';
            pdfWrapper.style.backgroundColor = '#ffffff';
            pdfWrapper.style.color = '#121a3e';
            pdfWrapper.style.fontFamily = 'Arial, sans-serif';
            
            const header = document.createElement('div');
            header.innerHTML = `
                <h1 style="color:#121a3e; text-align:center; margin-bottom: 5px; font-size:24px;">Reporte Analítico - Proyección de Demanda Empresarial</h1>
                <p style="color:#64748b; text-align:center; font-size: 14px; margin-top:0; margin-bottom: 30px;">Generado por el Sistema CIIDI - IA Analytics</p>
            `;
            pdfWrapper.appendChild(header);

            const canvasOriginal = document.getElementById('tendenciasChart');
            const imgData = canvasOriginal.toDataURL('image/png');
            const img = document.createElement('img');
            img.src = imgData;
            img.style.width = '100%';
            img.style.marginBottom = '30px';
            img.style.border = '1px solid #e2e8f0';
            img.style.borderRadius = '8px';
            pdfWrapper.appendChild(img);

            const metricasOriginal = document.getElementById('metricas-tendencias-resultado');
            const metricasClon = metricasOriginal.cloneNode(true);
            metricasClon.style.marginBottom = '30px';
            
            const tituloMetricas = document.createElement('h3');
            tituloMetricas.style.color = '#121a3e';
            tituloMetricas.style.borderBottom = '1px solid #cbd5e1';
            tituloMetricas.style.paddingBottom = '5px';
            tituloMetricas.innerText = 'Métricas de Error y Saturación';
            pdfWrapper.appendChild(tituloMetricas);
            pdfWrapper.appendChild(metricasClon);

            const insightsOriginal = document.getElementById('insights-tendencias');
            const insightsClon = insightsOriginal.cloneNode(true);
            
            const tituloInsights = document.createElement('h3');
            tituloInsights.style.color = '#121a3e';
            tituloInsights.style.borderBottom = '1px solid #cbd5e1';
            tituloInsights.style.paddingBottom = '5px';
            tituloInsights.innerText = 'Hallazgos de IA';
            pdfWrapper.appendChild(tituloInsights);
            pdfWrapper.appendChild(insightsClon);

            pdfWrapper.style.position = 'absolute';
            pdfWrapper.style.left = '-9999px';
            document.body.appendChild(pdfWrapper);

            const opt = {
                margin:       10,
                filename:     'Reporte_Proyeccion_CIIDI.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            const iconOriginal = btnDescargarPdf.innerHTML;
            btnDescargarPdf.innerHTML = '<i class="ph-bold ph-spinner ph-spin" style="font-size: 1.1rem;"></i> Generando...';
            btnDescargarPdf.disabled = true;

            html2pdf().set(opt).from(pdfWrapper).save().then(() => {
                document.body.removeChild(pdfWrapper);
                btnDescargarPdf.innerHTML = iconOriginal;
                btnDescargarPdf.disabled = false;
            });
        });
    }
});

