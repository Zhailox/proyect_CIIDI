// modules/LineasInvestigacion/assets/analitica_ui.js
(() => {
    if (window.__ciidiAnaliticaInitialized) return;
    window.__ciidiAnaliticaInitialized = true;

    // Paleta corporativa armónica (basada en los tonos #1e293b, #505984, #7090cb del encabezado institucional)
    const PALETA_LINEAS = [
        '#1e293b', // Azul Pizarra Profundo
        '#505984', // Azul Índigo Institucional
        '#7090cb', // Azul Acero Suave
        '#3b82f6', // Azul Real Académico
        '#4f46e5', // Índigo Ejecutivo
        '#0284c7', // Azul Cian Oscuro
        '#0d9488', // Verde Azulado Sobrio
        '#6366f1', // Violeta Pizarra
        '#334155', // Gris Pizarra Oscuro
        '#2563eb', // Cobalto Institucional
        '#64748b', // Pizarra Medio
        '#475569'  // Grafito Azulado
    ];

    document.addEventListener('DOMContentLoaded', () => {
        window.tendenciasChart = null;
        window.lastTendenciasData = null;
        window.lastTendenciasParams = null;
        window.hiddenLineNames = new Set();
        window.currentBacktestHoldout = '2t';

        const btnProyectar        = document.getElementById('btn-proyectar');
        const btnAdvanced         = document.getElementById('btn-advanced');
        const btnToggleBacktest   = document.getElementById('btn-toggle-backtest');
        const btnGuardarSnapshot  = document.getElementById('btn-guardar-snapshot');
        const panelAdvanced       = document.getElementById('advanced-settings-panel');
        const selectModelo        = document.getElementById('select-ml-model');
        const rfOnlyControls      = document.getElementById('rf-only-controls');
        const selectCarrera       = document.getElementById('select-carrera');
        const selectTrimestres    = document.getElementById('select-trimestres');
        const selectFuente        = document.getElementById('select-fuente');
        const loadingTendencias   = document.getElementById('loading-tendencias');
        const metricasTendencias  = document.getElementById('metricas-tendencias-resultado');
        const btnDescargarPdf     = document.getElementById('btn-descargar-pdf');

        if (!btnProyectar) return;

        function leerEntero(id, defecto, min, max) {
            const el = document.getElementById(id);
            const v = el ? parseInt(el.value, 10) : NaN;
            if (isNaN(v)) return defecto;
            return Math.min(max, Math.max(min, v));
        }

        function leerValor(id, defecto) {
            const el = document.getElementById(id);
            return el && el.value !== undefined && el.value !== '' ? el.value : defecto;
        }

        function escaparHtml(txt) {
            return String(txt ?? '').replace(/[&<>"']/g, c => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
            }[c]));
        }

        // Mostrar/ocultar panel avanzado
        if (btnAdvanced && panelAdvanced) {
            btnAdvanced.addEventListener('click', () => {
                const isHidden = panelAdvanced.style.display === 'none' || !panelAdvanced.style.display;
                panelAdvanced.style.display = isHidden ? 'flex' : 'none';
                btnAdvanced.classList.toggle('active', isHidden);
            });
        }

        // Mostrar Max Epochs y Timeout ÚNICAMENTE cuando se selecciona Random Forest ('rf')
        function actualizarVisibilidadControlesModelo() {
            if (!selectModelo || !rfOnlyControls) return;
            rfOnlyControls.style.display = selectModelo.value === 'rf' ? 'flex' : 'none';
        }

        if (selectModelo) {
            selectModelo.addEventListener('change', actualizarVisibilidadControlesModelo);
            actualizarVisibilidadControlesModelo();
        }

        if (selectCarrera) {
            selectCarrera.addEventListener('change', () => {
                window.hiddenLineNames.clear();
                ejecutarProyeccion();
            });
        }
        if (selectTrimestres) {
            selectTrimestres.addEventListener('change', () => ejecutarProyeccion());
        }
        if (selectFuente) {
            selectFuente.addEventListener('change', () => ejecutarProyeccion());
        }

        // Clasificación diagnóstica con paleta corporativa (#1e293b, #505984, #7090cb)
        function diagnosticarLinea(area, predSteps) {
            const predictionData = area.adoption_curve || [];
            const targetPrediction = predictionData.length > 0
                ? Math.round(predictionData[Math.min(predSteps, predictionData.length) - 1])
                : 0;
            const historyData = area.history || [];
            const totalHist = typeof area.total_historico === 'number'
                ? area.total_historico
                : historyData.reduce((acc, v) => acc + (Number(v) || 0), 0);
            const lastTermVal = historyData.length > 0 ? Number(historyData[historyData.length - 1]) : 0;

            let delta = 0;
            if (lastTermVal > 0) {
                delta = ((targetPrediction - lastTermVal) / lastTermVal) * 100;
            } else if (targetPrediction > 0) {
                delta = 100;
            }

            const satInt = Math.round(Number(area.saturation_index) || 0);
            let classLabel = '';
            let classColorBg = '';
            let classColorTxt = '';
            let recomendacion = '';

            if (totalHist === 0 && targetPrediction === 0) {
                classLabel = 'Línea Nueva / Sin Historial';
                classColorBg = '#eef0f7';
                classColorTxt = '#505984';
                recomendacion = 'Línea sin proyectos PST culminados registrados aún. Se sugiere vincular nuevos equipos estudiantiles y tutores para consolidar su base estadística.';
            } else if (delta > 20) {
                classLabel = 'Crec. Acelerado';
                classColorBg = '#1e293b';
                classColorTxt = '#ffffff';
                recomendacion = satInt >= 75
                    ? 'Alta demanda proyectada con cercanía al tope de capacidad. Priorizar la asignación de nuevos tutores académicos y jurados evaluadores en esta línea.'
                    : 'Crecimiento sostenido sobresaliente. Fortalecer alianzas institucionales para canalizar el flujo de nuevos proyectos estudiantiles.';
            } else if (delta > 5) {
                classLabel = 'Tendencia Positiva';
                classColorBg = '#eef0f7';
                classColorTxt = '#1e293b';
                recomendacion = 'Evolución favorable y en expansión. Mantener la oferta actual de temas de investigación y realizar seguimiento trimestral de capacidad.';
            } else if (delta >= -5 && delta <= 5) {
                classLabel = 'Estable';
                classColorBg = '#f1f5f9';
                classColorTxt = '#475569';
                recomendacion = 'Flujo de investigación consolidado y predecible. Ideal para estandarizar bancos de problemas y optimizar tiempos de defensa.';
            } else {
                classLabel = 'Baja Demanda';
                classColorBg = '#e2e8f0';
                classColorTxt = '#334155';
                recomendacion = 'Disminución proyectada de volumen. Se recomienda actualizar las dimensiones operativas de la línea y promover propuestas vinculadas.';
            }

            return {
                targetPrediction,
                totalHist,
                lastTermVal,
                delta,
                satInt,
                classLabel,
                classColorBg,
                classColorTxt,
                recomendacion
            };
        }

        function obtenerAreasVisibles(data) {
            if (!data || !Array.isArray(data.areas)) return [];
            const filtradas = data.areas.filter(a => !window.hiddenLineNames.has(a.name));
            return filtradas.length > 0 ? filtradas : data.areas;
        }

        async function ejecutarProyeccion() {
            const predSteps = leerEntero('select-trimestres', 4, 1, 12);
            const modeloElegido = leerValor('select-ml-model', 'linear');

            const params = {
                pred_steps: predSteps,
                id_carrera: leerValor('select-carrera', 'all'),
                historial_base: leerValor('select-historial', '2y'),
                ml_model: modeloElegido,
                fuente_datos: leerValor('select-fuente', 'pst'),
                capacidad_linea: leerEntero('input-capacidad', 25, 5, 500),
                max_epochs: modeloElegido === 'rf' ? leerEntero('input-epochs', 150, 10, 500) : 150,
                timeout: modeloElegido === 'rf' ? leerEntero('input-timeout', 10, 2, 120) : 10
            };

            btnProyectar.disabled = true;
            loadingTendencias.style.display = 'block';
            metricasTendencias.innerHTML = '<p style="color:#64748b; padding:12px;">Entrenando modelos y calculando proyecciones...</p>';

            try {
                const response = await fetch('api-prediccion-tendencias', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(params)
                });

                const data = await response.json();

                if (data.empty_career) {
                    if (window.tendenciasChart) {
                        window.tendenciasChart.destroy();
                        window.tendenciasChart = null;
                    }
                    ['engine-badge-tendencias', 'lineas-filter-bar', 'estacionalidad-tendencias', 'matriz-ejecutiva-tendencias'].forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.style.display = 'none';
                    });
                    const insightsCont = document.getElementById('insights-tendencias');
                    if (insightsCont) insightsCont.innerHTML = '';
                    if (btnDescargarPdf) btnDescargarPdf.style.display = 'none';

                    if (Array.isArray(data.snapshots)) {
                        renderizarSnapshots(data.snapshots);
                    }

                    metricasTendencias.innerHTML = `
                        <div style="background:#f8fafc; border:1px dashed #7090cb; padding:20px; border-radius:10px; text-align:center;">
                            <i class="ph-bold ph-folder-simple-plus" style="font-size:2.2rem; color:#505984;"></i>
                            <h4 style="color:#1e293b; margin:10px 0 6px 0; font-size:0.95rem;">Sin Líneas Activas en esta Carrera</h4>
                            <p style="font-size:0.82rem; color:#64748b; margin-bottom:14px;">${escaparHtml(data.error)}</p>
                            <a href="?ruta=gestionar-lineas" style="display:inline-flex; align-items:center; gap:6px; background:#505984; color:#ffffff; text-decoration:none; padding:8px 14px; border-radius:6px; font-size:0.8rem; font-weight:600;">
                                <i class="ph-bold ph-plus-circle"></i> Registrar Línea en Gestor Académico
                            </a>
                        </div>`;
                    return;
                }

                if (data.error) {
                    metricasTendencias.innerHTML = `<div style="background:#f8fafc; border:1px solid #cbd5e1; padding:12px; border-radius:8px; color:#1e293b; font-weight:600;">${escaparHtml(data.error)}</div>`;
                    if (btnDescargarPdf) btnDescargarPdf.style.display = 'none';
                } else {
                    window.lastTendenciasData = data;
                    window.lastTendenciasParams = params;
                    reRenderizarTodo();
                    if (btnDescargarPdf) btnDescargarPdf.style.display = 'inline-flex';
                }
            } catch (error) {
                metricasTendencias.innerHTML = `<div style="background:#f8fafc; border:1px solid #cbd5e1; padding:12px; border-radius:8px; color:#1e293b;">
                    <strong>Aviso de Motor IA:</strong> Ocurrió un fallo al procesar la respuesta analítica.
                    <br><span style="font-size:0.78rem; color:#64748b;">${escaparHtml(error.message)}</span>
                </div>`;
                console.error(error);
            } finally {
                btnProyectar.disabled = false;
                loadingTendencias.style.display = 'none';
            }
        }

        function reRenderizarTodo() {
            const data = window.lastTendenciasData;
            const params = window.lastTendenciasParams;
            if (!data || !params) return;

            const pasos = (data.future_labels && data.future_labels.length)
                ? data.future_labels.length
                : params.pred_steps;

            renderizarBadgeMotor(data, params);
            renderizarBarraFiltroLineas(data);
            renderizarGraficoTendencias(data, pasos);
            renderizarMetricasTendencias(data, pasos);
            renderizarInsights(data, pasos);
            renderizarMatrizEjecutiva(data, pasos);
            renderizarLaboratorioBacktest(data);
            renderizarSnapshots(data.snapshots || []);
        }

        if (btnToggleBacktest) {
            btnToggleBacktest.addEventListener('click', () => {
                const btCont = document.getElementById('backtest-tendencias-container');
                if (!btCont) return;
                btCont.style.display = 'block';
                btCont.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        }

        btnProyectar.addEventListener('click', ejecutarProyeccion);

        // Guardar Snapshot de Predicción (Con desglose completo por línea y por trimestre)
        if (btnGuardarSnapshot) {
            btnGuardarSnapshot.addEventListener('click', async () => {
                const data = window.lastTendenciasData;
                const params = window.lastTendenciasParams;
                if (!data || !params || !Array.isArray(data.areas)) return;

                const areasVisibles = obtenerAreasVisibles(data);
                const pasos = (data.future_labels && data.future_labels.length) ? data.future_labels.length : params.pred_steps;
                const futureLabels = (data.future_labels || []).slice(0, pasos);
                const histLabels = data.history_labels || [];

                let totalProy = 0;
                const resumenAreas = areasVisibles.map(a => {
                    const preds = (a.adoption_curve || []).slice(0, pasos);
                    const ciLow = (a.ci_lower || []).slice(0, pasos);
                    const ciHigh = (a.ci_upper || []).slice(0, pasos);
                    const sumLine = preds.reduce((acc, v) => acc + Math.round(v), 0);
                    totalProy += sumLine;
                    return {
                        id_linea: a.id_linea ?? null,
                        name: a.name,
                        carrera: a.carrera_nombre || 'General',
                        history: a.history || [],
                        history_labels: histLabels,
                        future_labels: futureLabels,
                        total_historico: a.total_historico ?? 0,
                        promedio_historico: a.promedio_historico ?? 0,
                        ultimo_real: a.ultimo_trimestre ?? 0,
                        adoption_curve: preds,
                        ci_lower: ciLow,
                        ci_upper: ciHigh,
                        primer_trimestre_label: futureLabels[0] || '',
                        primer_trimestre_pred: Math.round(preds[0] ?? 0),
                        meta_final_pred: Math.round(preds[pasos - 1] ?? 0),
                        rmse: a.rmse ?? 0,
                        mae: a.mae ?? 0,
                        r2: a.r2 ?? 0,
                        saturation_index: a.saturation_index ?? 0,
                        capacity: a.capacity ?? 25,
                        model_applied: a.model_applied || data.model_used,
                        model_applied_label: a.model_applied_label || data.model_label
                    };
                });

                const textoOriginal = btnGuardarSnapshot.innerHTML;
                btnGuardarSnapshot.disabled = true;
                btnGuardarSnapshot.innerHTML = '<i class="ph-bold ph-spinner ph-spin"></i> Guardando...';

                try {
                    const res = await fetch('api-prediccion-tendencias', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            action: 'save_snapshot',
                            id_carrera: params.id_carrera,
                            carrera_nombre: data.params && data.params.carrera_nombre ? data.params.carrera_nombre : 'Todas las Carreras / PNF',
                            modelo_usado: data.model_used,
                            modelo_label: data.model_label,
                            fuente_datos: params.fuente_datos,
                            ventana_historial: params.historial_base,
                            horizonte_trimestres: pasos,
                            trimestre_base: histLabels.length ? histLabels[histLabels.length - 1] : '',
                            trimestre_meta: futureLabels.length ? futureLabels[futureLabels.length - 1] : '',
                            rmse_global: data.metrics ? data.metrics.global_rmse : 0,
                            mae_global: data.metrics ? data.metrics.global_mae : 0,
                            confianza_pct: data.metrics ? data.metrics.confidence_score : 75,
                            total_historico: data.metrics ? data.metrics.total_projects_analyzed : 0,
                            total_proyectado: totalProy,
                            estacionalidad_activa: params.seasonality,
                            resumen_areas: resumenAreas
                        })
                    });
                    const json = await res.json();
                    if (json.ok && Array.isArray(json.snapshots)) {
                        window.lastTendenciasData.snapshots = json.snapshots;
                        renderizarSnapshots(json.snapshots);
                        if (json.snapshots.length > 0) {
                            abrirModalAuditoriaSnapshot(json.snapshots[0]);
                        }
                        btnGuardarSnapshot.innerHTML = '<i class="ph-bold ph-check"></i> Snapshot Guardado';
                        setTimeout(() => { btnGuardarSnapshot.innerHTML = textoOriginal; }, 1800);
                    } else {
                        btnGuardarSnapshot.innerHTML = textoOriginal;
                    }
                } catch (e) {
                    console.error(e);
                    btnGuardarSnapshot.innerHTML = textoOriginal;
                } finally {
                    btnGuardarSnapshot.disabled = false;
                }
            });
        }

        // 1. Insignias sobrias con paleta corporativa (#1e293b, #505984, #7090cb)
        function renderizarBadgeMotor(data, params) {
            const cont = document.getElementById('engine-badge-tendencias');
            if (!cont) return;
            const esPython = data.engine === 'python';
            const hist = data.history_labels || [];
            const periodo = hist.length ? `${hist[0]} – ${hist[hist.length - 1]} (${hist.length} trim.)` : 'sin datos';
            const ventanas = { all: 'Todo el historial', '2y': 'Últimos 2 años', '1y': 'Último año' };
            const extraRf = data.model_used === 'rf' && data.params && data.params.n_estimators ? ` · ${data.params.n_estimators} árboles` : '';
            const carreraTxt = (data.params && data.params.carrera_nombre) ? data.params.carrera_nombre : 'Todas las Carreras / PNF';
            const confScore = Number(data.metrics && data.metrics.confidence_score) || 75;
            const totalProyAnalizados = Number(data.metrics && data.metrics.total_projects_analyzed) || 0;
            const fuenteTxt = params.fuente_datos === 'all' ? 'PST + Ofertas Activas' : 'Proyectos PST Culminados';

            const chip = (icono, texto, bg, color, borde, titulo = '') =>
                `<span title="${escaparHtml(titulo)}" style="display:inline-flex; align-items:center; gap:5px; font-size:0.74rem; font-weight:600; padding:4px 11px; border-radius:50px; background:${bg}; color:${color}; border:1px solid ${borde};"><i class="ph-bold ${icono}"></i>${texto}</span>`;

            let html = chip('ph-graduation-cap', `PNF: ${escaparHtml(carreraTxt)}`, '#1e293b', '#ffffff', '#1e293b');
            html += chip('ph-function', escaparHtml((data.model_label || params.ml_model) + extraRf), '#eef0f7', '#1e293b', '#cbd5e1');
            html += chip('ph-archive-box', `${escaparHtml(fuenteTxt)}: ${totalProyAnalizados} proy.`, '#ffffff', '#334155', '#cbd5e1');
            html += chip('ph-calendar', `${escaparHtml(ventanas[params.historial_base] || params.historial_base)}: ${escaparHtml(periodo)}`, '#ffffff', '#475569', '#cbd5e1');
            html += chip('ph-shield-check', `Fiabilidad: ${confScore.toFixed(0)}%`, '#eef0f7', '#505984', '#c7cbe0');
            html += esPython
                ? chip('ph-cpu', 'Motor: Python · scikit-learn', '#f1f5f9', '#334155', '#cbd5e1', data.engine_detail || '')
                : chip('ph-calculator', 'Motor: Matemático PHP', '#f1f5f9', '#334155', '#cbd5e1', data.engine_detail || '');

            cont.innerHTML = html;
            cont.style.display = 'flex';
        }

        // 2. Barra de chips interactivos para filtrar líneas de investigación
        function renderizarBarraFiltroLineas(data) {
            const bar = document.getElementById('lineas-filter-bar');
            if (!bar || !Array.isArray(data.areas) || data.areas.length === 0) {
                if (bar) bar.style.display = 'none';
                return;
            }

            let html = `<span style="font-size:0.74rem; font-weight:700; color:#505984; text-transform:uppercase; letter-spacing:0.5px; margin-right:4px; display:inline-flex; align-items:center; gap:4px;">
                <i class="ph-bold ph-funnel"></i> Líneas Activas (${data.areas.length}):
            </span>`;

            data.areas.forEach((area, idx) => {
                const color = PALETA_LINEAS[idx % PALETA_LINEAS.length];
                const isHidden = window.hiddenLineNames.has(area.name);
                const carreraTag = area.carrera_nombre ? ` · ${area.carrera_nombre}` : '';
                html += `<button type="button" class="ml-line-chip ${isHidden ? 'inactive' : ''}" data-line-name="${escaparHtml(area.name)}" title="${escaparHtml(area.name + carreraTag)}">
                    <span style="width:9px; height:9px; border-radius:50%; background:${color}; display:inline-block; flex-shrink:0;"></span>
                    <span>${escaparHtml(area.name)}</span>
                </button>`;
            });

            if (window.hiddenLineNames.size > 0) {
                html += `<button type="button" id="btn-reset-lineas-chip" style="background:#eef0f7; color:#1e293b; border:1px solid #cbd5e1; border-radius:999px; padding:4px 10px; font-size:0.72rem; font-weight:700; cursor:pointer;">
                    Mostrar todas
                </button>`;
            }

            bar.innerHTML = html;
            bar.style.display = 'flex';

            bar.querySelectorAll('.ml-line-chip').forEach(btn => {
                btn.addEventListener('click', () => {
                    const name = btn.getAttribute('data-line-name');
                    if (window.hiddenLineNames.has(name)) {
                        window.hiddenLineNames.delete(name);
                    } else if (window.hiddenLineNames.size < data.areas.length - 1) {
                        window.hiddenLineNames.add(name);
                    }
                    reRenderizarTodo();
                });
            });

            const btnReset = document.getElementById('btn-reset-lineas-chip');
            if (btnReset) {
                btnReset.addEventListener('click', () => {
                    window.hiddenLineNames.clear();
                    reRenderizarTodo();
                });
            }
        }

        // 3. Renderizar Gráfico Principal en Chart.js
        function renderizarGraficoTendencias(data, predSteps) {
            const canvas = document.getElementById('tendenciasChart');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');

            if (window.tendenciasChart) {
                window.tendenciasChart.destroy();
            }

            const areasVisibles = obtenerAreasVisibles(data);
            const maxHistory = Math.max(...areasVisibles.map(a => a.history ? a.history.length : 0), 1);

            let chartLabels = [];
            if (Array.isArray(data.history_labels) && data.history_labels.length === maxHistory && Array.isArray(data.future_labels)) {
                chartLabels = [
                    ...data.history_labels,
                    ...data.future_labels.slice(0, predSteps).map(l => `${l} (Proy)`)
                ];
            } else {
                for (let i = 1; i <= maxHistory; i++) chartLabels.push(`Hist ${i}`);
                for (let i = 1; i <= predSteps; i++) chartLabels.push(`Proy +${i}`);
            }

            const lineData = areasVisibles.map((a) => {
                const origIdx = data.areas.findIndex(orig => orig.name === a.name);
                const color = PALETA_LINEAS[(origIdx >= 0 ? origIdx : 0) % PALETA_LINEAS.length];
                const historicalData = a.history || [];
                const predictionData = (a.adoption_curve || []).slice(0, predSteps);
                const combinedData = [...historicalData, ...predictionData];

                return {
                    label: a.name,
                    data: combinedData,
                    borderColor: color,
                    backgroundColor: color,
                    borderWidth: 3,
                    tension: 0.35,
                    pointBackgroundColor: color,
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4.5,
                    pointHoverRadius: 7,
                    fill: false,
                    segment: {
                        borderDash: ctxSeg => ctxSeg.p0DataIndex >= historicalData.length - 1 ? [7, 5] : undefined,
                        borderColor: ctxSeg => ctxSeg.p0DataIndex >= historicalData.length - 1 ? color + 'AA' : color
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
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { family: 'Inter, sans-serif', size: 11.5, weight: '600' },
                                color: '#1e293b',
                                usePointStyle: true,
                                padding: 14
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(30, 41, 59, 0.95)',
                            titleFont: { size: 12.5, family: 'Inter, sans-serif', weight: 'bold' },
                            bodyFont: { size: 12, family: 'Inter, sans-serif' },
                            padding: 11,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    const dataIndex = context.dataIndex;
                                    const label = context.dataset.label || '';
                                    const value = context.parsed.y;
                                    if (dataIndex >= maxHistory) {
                                        return ` ${label}: ${value} proy. proyectados`;
                                    }
                                    return ` ${label}: ${value} proy. culminados`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(80, 89, 132, 0.1)', borderDash: [5, 5] },
                            ticks: { color: '#505984', font: { weight: '600' }, precision: 0 }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#505984', font: { weight: '600', size: 11 } }
                        }
                    }
                }
            });
        }

        // 4. Panel Lateral de Métricas de Fiabilidad y Diagnóstico por Línea
        function renderizarMetricasTendencias(data, predSteps) {
            const areasVisibles = obtenerAreasVisibles(data);
            let futureLabels = [];
            if (Array.isArray(data.future_labels) && data.future_labels.length >= predSteps) {
                futureLabels = data.future_labels.slice(0, predSteps).map(l => l.replace(/^(T\d) \d{2}(\d{2})$/, "$1 '$2"));
            } else {
                for (let i = 1; i <= predSteps; i++) futureLabels.push(`+T${i}`);
            }
            const targetLabel = futureLabels[futureLabels.length - 1] || 'Horizonte';

            const rmse = Number(data.metrics && data.metrics.global_rmse) || 0;
            const mae  = Number(data.metrics && data.metrics.global_mae) || 0;
            const conf = Number(data.metrics && data.metrics.confidence_score) || 75;
            const errorText = rmse < 0.005 ? 'Ajuste exacto (±0.00)' : `±${rmse.toFixed(2)} proy.`;

            let comparativaHtml = '';
            const porModelo = data.metrics && data.metrics.rmse_by_model;
            const bestGlobal = data.metrics && data.metrics.best_global_model;
            if (porModelo) {
                const nombres = { linear: 'Lineal', poly: 'Polinómica', rf: 'Random Forest' };
                comparativaHtml = `
                <div style="margin-top:8px; padding-top:8px; border-top:1px solid #e2e8f0;">
                    <div style="font-size:0.69rem; font-weight:700; color:#505984; margin-bottom:5px; text-transform:uppercase;">Comparativa RMSE por Algoritmo:</div>
                    <div style="display:flex; gap:6px; flex-wrap:wrap;">` +
                    Object.keys(nombres).filter(k => porModelo[k] !== undefined).map(k => {
                        const activo = (data.model_used === k) || (data.model_used === 'auto' && bestGlobal === k);
                        const esMejor = bestGlobal === k;
                        return `<span style="font-size:0.7rem; padding:3px 8px; border-radius:5px; border:1px solid ${activo ? '#505984' : '#cbd5e1'}; background:${activo ? '#505984' : '#ffffff'}; color:${activo ? '#ffffff' : '#475569'}; font-weight:${activo ? '700' : '600'};">
                            ${nombres[k]}: ${Number(porModelo[k]).toFixed(2)}${esMejor ? ' ★' : ''}
                        </span>`;
                    }).join('') + `</div>
                </div>`;
            }

            let html = `
            <div style="margin-bottom: 14px; color: #1e293b; background: #f8fafc; padding: 12px; border-radius: 8px; border-left: 4px solid #505984; border-top: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
                <div style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:6px;">
                    <strong style="font-size: 0.86rem; color:#1e293b;">Margen Estadístico (RMSE):</strong>
                    <span style="background:#eef0f7; color:#1e293b; padding:3px 9px; border-radius:5px; font-weight:700; font-size:0.8rem; border:1px solid #cbd5e1;">${errorText}</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:0.75rem; color:#64748b; margin-top:6px;">
                    <span>Error Absoluto (MAE): <strong style="color:#1e293b;">${mae.toFixed(2)} proy.</strong></span>
                    <span>Fiabilidad: <strong style="color:#505984;">${conf.toFixed(0)}%</strong></span>
                </div>
                ${comparativaHtml}
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; margin: 14px 0 10px 0; border-bottom: 2px solid #eef0f7; padding-bottom: 6px;">
                <strong style="color: #1e293b; font-size: 0.92rem;">Diagnóstico por Línea (${areasVisibles.length})</strong>
                <span style="font-size: 0.73rem; color: #64748b;">Meta: <strong>${escaparHtml(targetLabel)}</strong></span>
            </div>

            <ul style="list-style:none; padding:0; margin:0; max-height: 450px; overflow-y: auto; padding-right: 4px;">`;

            areasVisibles.forEach((area) => {
                const origIdx = data.areas.findIndex(orig => orig.name === area.name);
                const lineColor = PALETA_LINEAS[(origIdx >= 0 ? origIdx : 0) % PALETA_LINEAS.length];
                const diag = diagnosticarLinea(area, predSteps);
                const predictionData = area.adoption_curve || [];
                const satColor = diag.satInt > 80 ? '#1e293b' : (diag.satInt > 50 ? '#505984' : '#7090cb');
                const rangoMin = Math.max(0, Math.round(diag.targetPrediction - (Number(area.rmse) || 0)));
                const rangoMax = Math.round(diag.targetPrediction + (Number(area.rmse) || 0));

                let timelineHtml = `<div style="display:flex; flex-wrap:nowrap; overflow-x:auto; gap:5px; margin-top:9px; padding-bottom:3px; align-items:center;">`;
                for (let i = 0; i < predSteps; i++) {
                    const val = Math.round(predictionData[i] ?? 0);
                    timelineHtml += `
                        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:4px; padding:2px 6px; font-size:0.68rem; color:#475569; white-space:nowrap;">
                            <strong style="color:#1e293b;">${escaparHtml(futureLabels[i])}:</strong> ${val}
                        </div>`;
                    if (i < predSteps - 1) {
                        timelineHtml += `<i class="ph-bold ph-arrow-right" style="color:#cbd5e1; font-size:0.65rem;"></i>`;
                    }
                }
                timelineHtml += `</div>`;

                html += `
                <li style="margin-bottom:10px; padding: 11px 12px; background: #ffffff; border: 1px solid #e2e8f0; border-left: 4px solid ${lineColor}; border-radius: 8px;">
                    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap: 8px; margin-bottom:6px;">
                        <div style="flex: 1; min-width: 0;">
                            <strong style="color:#1e293b; font-size: 0.84rem; display:block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${escaparHtml(area.name)}">
                                ${escaparHtml(area.name)}
                            </strong>
                            <div style="display:flex; align-items:center; gap:5px; flex-wrap:wrap; margin-top:4px;">
                                <span style="font-size:0.65rem; font-weight:700; padding:2px 7px; border-radius:10px; background:${diag.classColorBg}; color:${diag.classColorTxt};">
                                    ${escaparHtml(diag.classLabel)}
                                </span>
                                <span style="font-size:0.65rem; color:#64748b; background:#f8fafc; border:1px solid #e2e8f0; padding:1px 6px; border-radius:4px;">
                                    ${escaparHtml(area.carrera_nombre || 'General')}
                                </span>
                            </div>
                        </div>
                        <div style="flex-shrink: 0; text-align: right;">
                            <span style="color:${satColor}; font-weight:800; font-size: 0.92rem; display:block;">${diag.satInt}%</span>
                            <span style="font-size: 0.6rem; color:#64748b; font-weight:700;">SAT.</span>
                        </div>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:5px; font-size: 0.75rem; color:#475569;">
                        <span>Demanda Final: <strong style="color:#1e293b;">${diag.targetPrediction} proy.</strong> <span style="color:#64748b; font-size:0.69rem;">(${rangoMin}–${rangoMax})</span></span>
                        <span style="font-size:0.7rem; color:#64748b;">Culminados: <strong>${diag.totalHist}</strong></span>
                    </div>

                    <div style="width:100%; background:#f1f5f9; height:5px; border-radius:3px; overflow:hidden;">
                        <div style="width:${diag.satInt}%; background:${satColor}; height:100%; border-radius:3px; transition: width 0.6s ease;"></div>
                    </div>

                    ${timelineHtml}
                </li>`;
            });

            html += `</ul>`;
            metricasTendencias.innerHTML = html;
        }

        // 5. Tarjetas de Hallazgos Estratégicos (KPIs Inferiores) con paleta corporativa
        function renderizarInsights(data, predSteps) {
            const container = document.getElementById('insights-tendencias');
            if (!container) return;

            const areasVisibles = obtenerAreasVisibles(data);
            let maxDemanda = { name: '-', val: -1 };
            let minDemanda = { name: '-', val: 999999 };
            let maxAceleracion = { name: '-', growthPct: -999 };
            let sumaPorTrimestre = new Array(predSteps).fill(0);

            areasVisibles.forEach(area => {
                const predData = area.adoption_curve || [];
                const histData = area.history || [];

                if (predData.length >= predSteps) {
                    const tPred = Math.round(predData[predSteps - 1]);
                    if (tPred > maxDemanda.val) maxDemanda = { name: area.name, val: tPred };
                    if (tPred < minDemanda.val) minDemanda = { name: area.name, val: tPred };

                    const lastHist = histData.length > 0 ? histData[histData.length - 1] : 0;
                    const growthPct = lastHist > 0 ? ((predData[predSteps - 1] - lastHist) / lastHist) * 100 : (tPred > 0 ? 100 : 0);

                    if (growthPct > maxAceleracion.growthPct) {
                        maxAceleracion = { name: area.name, growthPct };
                    }

                    for (let i = 0; i < predSteps; i++) {
                        sumaPorTrimestre[i] += Math.round(predData[i]);
                    }
                }
            });

            if (minDemanda.val === 999999) minDemanda.val = 0;
            if (maxDemanda.val === -1) maxDemanda.val = 0;
            if (maxAceleracion.growthPct === -999) maxAceleracion.growthPct = 0;

            let peakIndex = 0;
            let maxSum = -1;
            for (let i = 0; i < predSteps; i++) {
                if (sumaPorTrimestre[i] > maxSum) {
                    maxSum = sumaPorTrimestre[i];
                    peakIndex = i;
                }
            }
            if (maxSum < 0) maxSum = 0;

            const peakLabel = (Array.isArray(data.future_labels) && data.future_labels[peakIndex])
                ? data.future_labels[peakIndex]
                : `Trimestre +${peakIndex + 1}`;

            const cNavy  = '#1e293b';
            const cSlate = '#505984';
            const cSteel = '#7090cb';
            const signoCrec = maxAceleracion.growthPct >= 0 ? '+' : '';

            container.innerHTML = `
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 15px; position: relative; overflow: hidden;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-size: 0.67rem; color: ${cSlate}; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">Línea Líder (Demanda)</div>
                            <div style="font-size: 0.92rem; color: ${cNavy}; font-weight: 800; margin-top: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${escaparHtml(maxDemanda.name)}">${escaparHtml(maxDemanda.name)}</div>
                        </div>
                        <div style="background: #eef0f7; padding: 8px; border-radius: 8px; flex-shrink: 0;">
                            <i class="ph-bold ph-rocket" style="font-size: 1.2rem; color: ${cNavy};"></i>
                        </div>
                    </div>
                    <div style="margin-top: 10px;">
                        <span style="display:block; font-size: 0.78rem; color: #475569; background: #f8fafc; padding: 4px 8px; border-radius: 4px; border: 1px solid #e2e8f0;">Cima: <strong style="color: ${cNavy};">${maxDemanda.val} proy.</strong></span>
                    </div>
                    <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 3px; background: ${cNavy};"></div>
                </div>

                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 15px; position: relative; overflow: hidden;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-size: 0.67rem; color: ${cSlate}; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">Mayor Impulso</div>
                            <div style="font-size: 0.92rem; color: ${cNavy}; font-weight: 800; margin-top: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${escaparHtml(maxAceleracion.name)}">${escaparHtml(maxAceleracion.name)}</div>
                        </div>
                        <div style="background: #eef0f7; padding: 8px; border-radius: 8px; flex-shrink: 0;">
                            <i class="ph-bold ph-trend-up" style="font-size: 1.2rem; color: ${cSlate};"></i>
                        </div>
                    </div>
                    <div style="margin-top: 10px;">
                        <span style="display:block; font-size: 0.78rem; color: #475569; background: #f8fafc; padding: 4px 8px; border-radius: 4px; border: 1px solid #e2e8f0;">Variación: <strong style="color: ${cSlate};">${signoCrec}${maxAceleracion.growthPct.toFixed(1)}%</strong></span>
                    </div>
                    <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 3px; background: ${cSlate};"></div>
                </div>

                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 15px; position: relative; overflow: hidden;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-size: 0.67rem; color: ${cSlate}; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">Menor Demanda</div>
                            <div style="font-size: 0.92rem; color: ${cNavy}; font-weight: 800; margin-top: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${escaparHtml(minDemanda.name)}">${escaparHtml(minDemanda.name)}</div>
                        </div>
                        <div style="background: #f1f5f9; padding: 8px; border-radius: 8px; flex-shrink: 0;">
                            <i class="ph-bold ph-arrow-down-right" style="font-size: 1.2rem; color: #475569;"></i>
                        </div>
                    </div>
                    <div style="margin-top: 10px;">
                        <span style="display:block; font-size: 0.78rem; color: #475569; background: #f8fafc; padding: 4px 8px; border-radius: 4px; border: 1px solid #e2e8f0;">Flujo: <strong style="color: ${cNavy};">${minDemanda.val} proy.</strong></span>
                    </div>
                    <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 3px; background: #64748b;"></div>
                </div>

                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 15px; position: relative; overflow: hidden;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-size: 0.67rem; color: ${cSlate}; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">Trimestre Pico</div>
                            <div style="font-size: 0.92rem; color: ${cNavy}; font-weight: 800; margin-top: 5px;">${escaparHtml(peakLabel)}</div>
                        </div>
                        <div style="background: rgba(112, 144, 203, 0.14); padding: 8px; border-radius: 8px; flex-shrink: 0;">
                            <i class="ph-bold ph-lightning" style="font-size: 1.2rem; color: ${cSteel};"></i>
                        </div>
                    </div>
                    <div style="margin-top: 10px;">
                        <span style="display:block; font-size: 0.78rem; color: #475569; background: #f8fafc; padding: 4px 8px; border-radius: 4px; border: 1px solid #e2e8f0;">Carga: <strong style="color: ${cNavy};">${maxSum} simultáneos</strong></span>
                    </div>
                    <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 3px; background: ${cSteel};"></div>
                </div>
            `;
        }

        // 6. Panel de Detección de Estacionalidad Académica (T1 / T2 / T3)
        function renderizarEstacionalidad(data) {
            const cont = document.getElementById('estacionalidad-tendencias');
            if (!cont) return;
            const seas = data.metrics && data.metrics.seasonality;
            if (!seas || !seas.indices) {
                cont.style.display = 'none';
                return;
            }

            const idx = seas.indices;
            const tots = seas.totals_by_term || { T1: 0, T2: 0, T3: 0 };
            const trimestresInfo = [
                { key: 'T1', nombre: 'Trimestre 1 (Ene–Abr)', desc: 'Inicio de Trayecto', factor: Number(idx.T1 || 1), total: Number(tots.T1 || 0) },
                { key: 'T2', nombre: 'Trimestre 2 (May–Ago)', desc: 'Desarrollo Intermedio', factor: Number(idx.T2 || 1), total: Number(tots.T2 || 0) },
                { key: 'T3', nombre: 'Trimestre 3 (Sep–Dic)', desc: 'Cierre y Defensas PST', factor: Number(idx.T3 || 1), total: Number(tots.T3 || 0) },
            ];

            let cardsTrim = trimestresInfo.map(t => {
                const esDom = seas.dominant_term === t.key;
                const diffPct = Math.round((t.factor - 1) * 100);
                const diffStr = diffPct >= 0 ? `+${diffPct}% vs media` : `${diffPct}% vs media`;
                return `
                <div style="flex:1; min-width:170px; background:${esDom ? '#eef0f7' : '#f8fafc'}; border:1px solid ${esDom ? '#505984' : '#e2e8f0'}; border-radius:8px; padding:10px 12px;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <strong style="font-size:0.8rem; color:#1e293b;">${t.nombre}</strong>
                        <span style="font-size:0.68rem; font-weight:700; padding:2px 7px; border-radius:10px; background:${esDom ? '#505984' : '#e2e8f0'}; color:${esDom ? '#ffffff' : '#475569'};">
                            ×${t.factor.toFixed(2)} (${diffStr})
                        </span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px; font-size:0.74rem; color:#64748b;">
                        <span>${t.desc}</span>
                        <strong style="color:#1e293b;">${t.total} proy. hist.</strong>
                    </div>
                </div>`;
            }).join('');

            cont.innerHTML = `
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; margin-bottom:10px;">
                    <div style="font-size:0.88rem; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:6px;">
                        <i class="ph-bold ph-waves" style="color:#505984;"></i> Patrón de Estacionalidad Académica Detectado (Ciclos T1 / T2 / T3)
                    </div>
                    <span style="font-size:0.72rem; color:#505984; font-weight:600;">
                        ${seas.enabled ? 'Ajuste Estacional Activo en la Curva' : 'Modo Lineal (Sin ponderación estacional)'}
                    </span>
                </div>
                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    ${cardsTrim}
                </div>
            `;
            cont.style.display = 'block';
        }

        // 7. Matriz Ejecutiva de Proyección y Recomendación Estratégica en Pantalla
        function renderizarMatrizEjecutiva(data, predSteps) {
            const cont = document.getElementById('matriz-ejecutiva-tendencias');
            if (!cont) return;
            const areasVisibles = obtenerAreasVisibles(data);
            const futureLabels = (Array.isArray(data.future_labels) && data.future_labels.length >= predSteps)
                ? data.future_labels.slice(0, predSteps)
                : Array.from({ length: predSteps }, (_, i) => `Proy +${i + 1}`);

            let thTrimestres = futureLabels.map(fl => `<th style="padding:10px 8px; text-align:center; color:#ffffff; font-size:0.74rem; white-space:nowrap;">${escaparHtml(fl)}</th>`).join('');

            let filasHtml = areasVisibles.map((area, idx) => {
                const diag = diagnosticarLinea(area, predSteps);
                const preds = (area.adoption_curve || []).slice(0, predSteps);
                const bgRow = idx % 2 === 0 ? '#ffffff' : '#f8fafc';
                const tdsPred = futureLabels.map((_, i) => {
                    const v = Math.round(preds[i] ?? 0);
                    return `<td style="padding:9px 8px; text-align:center; font-weight:700; color:#1e293b; font-size:0.8rem;">${v}</td>`;
                }).join('');

                return `
                <tr style="background:${bgRow}; border-bottom:1px solid #e2e8f0;">
                    <td style="padding:10px 12px; font-weight:700; color:#1e293b; font-size:0.8rem;">
                        ${escaparHtml(area.name)}
                        <div style="font-size:0.68rem; color:#505984; font-weight:600; margin-top:2px;">${escaparHtml(area.carrera_nombre || 'General')}</div>
                    </td>
                    <td style="padding:9px 8px; text-align:center; color:#475569; font-size:0.78rem;">${diag.totalHist} <span style="color:#94a3b8; font-size:0.7rem;">(prom. ${Number(area.promedio_historico || 0).toFixed(1)})</span></td>
                    ${tdsPred}
                    <td style="padding:9px 8px; text-align:center; font-weight:700; color:#505984; font-size:0.78rem;">±${Number(area.rmse || 0).toFixed(2)}</td>
                    <td style="padding:9px 8px; text-align:center;">
                        <span style="font-size:0.68rem; font-weight:700; padding:3px 8px; border-radius:12px; background:${diag.classColorBg}; color:${diag.classColorTxt}; white-space:nowrap;">
                            ${escaparHtml(diag.classLabel)}
                        </span>
                    </td>
                    <td style="padding:9px 12px; font-size:0.75rem; color:#334155; line-height:1.4; max-width:340px;">
                        ${escaparHtml(diag.recomendacion)}
                    </td>
                </tr>`;
            }).join('');

            cont.innerHTML = `
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:12px;">
                    <h4 style="margin:0; color:#1e293b; font-size:0.96rem; display:flex; align-items:center; gap:8px;">
                        <i class="ph-bold ph-table" style="color:#505984;"></i> Matriz Consolidada de Proyecciones y Recomendaciones Estratégicas (IA)
                    </h4>
                    <span style="font-size:0.74rem; color:#64748b;">Incluida en el Informe Ejecutivo PDF</span>
                </div>
                <div style="overflow-x:auto; border:1px solid #e2e8f0; border-radius:8px;">
                    <table style="width:100%; border-collapse:collapse; text-align:left;">
                        <thead>
                            <tr style="background: linear-gradient(135deg, #505984 0%, #1e293b 100%);">
                                <th style="padding:10px 12px; color:#ffffff; font-size:0.74rem;">Línea de Investigación / Carrera</th>
                                <th style="padding:10px 8px; text-align:center; color:#ffffff; font-size:0.74rem;">Culminados</th>
                                ${thTrimestres}
                                <th style="padding:10px 8px; text-align:center; color:#ffffff; font-size:0.74rem;">Margen RMSE</th>
                                <th style="padding:10px 8px; text-align:center; color:#ffffff; font-size:0.74rem;">Diagnóstico</th>
                                <th style="padding:10px 12px; color:#ffffff; font-size:0.74rem;">Recomendación Estratégica Automatizada</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${filasHtml}
                        </tbody>
                    </table>
                </div>
            `;
            cont.style.display = 'block';
        }

        // 7b. Laboratorio de Fiabilidad ML (Validación Walk-Forward con Datos Reales Ocultados)
        function renderizarLaboratorioBacktest(data) {
            const cont = document.getElementById('backtest-tendencias-container');
            if (!cont) return;

            const btRoot = data && data.backtest ? data.backtest : null;
            const mode = window.currentBacktestHoldout || '2t';
            const bt = btRoot ? (btRoot[mode] || btRoot['2t'] || btRoot['1t']) : null;

            if (!bt || !Array.isArray(bt.lines) || bt.lines.length === 0) {
                cont.style.display = 'none';
                return;
            }

            const trainLabels = bt.train_labels || bt.train_terms || [];
            const testLabels  = bt.test_labels || bt.test_terms || [];
            const rangoTrain  = trainLabels.length ? `${trainLabels[0]} → ${trainLabels[trainLabels.length - 1]} (${trainLabels.length} trim.)` : '-';
            const rangoTest   = testLabels.join(' y ');
            const modSummary  = bt.models_summary || {};
            const ordenModelos = ['poly', 'rf', 'auto', 'linear'];

            const cardsModelos = ordenModelos.filter(m => modSummary[m]).map(m => {
                const ms = modSummary[m];
                const esGanador = bt.best_overall_model === m;
                const esActivo = data.model_used === m;
                return `
                <div style="flex:1; min-width:205px; background:${esGanador ? '#eef0f7' : '#f8fafc'}; border:1px solid ${esGanador ? '#505984' : '#cbd5e1'}; border-top:3px solid ${esGanador ? '#1e293b' : '#7090cb'}; border-radius:8px; padding:11px 13px;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:6px;">
                        <strong style="font-size:0.8rem; color:#1e293b;">${escaparHtml(ms.label)}</strong>
                        ${esGanador ? '<span style="background:#1e293b; color:#ffffff; font-size:0.65rem; font-weight:700; padding:2px 7px; border-radius:99px; white-space:nowrap;">★ Más Preciso</span>' : (esActivo ? '<span style="background:#505984; color:#ffffff; font-size:0.65rem; font-weight:700; padding:2px 7px; border-radius:99px;">Activo</span>' : '')}
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:6px; margin-top:8px; font-size:0.74rem; color:#475569;">
                        <div style="background:#ffffff; padding:5px 7px; border-radius:5px; border:1px solid #e2e8f0;">
                            Error MAE Real: <strong style="color:#1e293b; display:block; font-size:0.82rem;">±${Number(ms.oos_mae).toFixed(2)} proy.</strong>
                        </div>
                        <div style="background:#ffffff; padding:5px 7px; border-radius:5px; border:1px solid #e2e8f0;">
                            Precisión Vol.: <strong style="color:#505984; display:block; font-size:0.82rem;">${Number(ms.volume_accuracy_pct).toFixed(1)}%</strong>
                        </div>
                    </div>
                    <div style="margin-top:6px; font-size:0.72rem; color:#64748b; display:flex; justify-content:space-between;">
                        <span>Predicho: <strong>${ms.total_predicted} proy.</strong></span>
                        <span>Real Ocultado: <strong>${ms.total_real} proy.</strong></span>
                    </div>
                </div>`;
            }).join('');

            const thTestCols = testLabels.map(tl => `
                <th style="padding:9px 8px; text-align:center; color:#ffffff; font-size:0.72rem; background:#1e293b; border-left:1px solid rgba(255,255,255,0.15);">
                    ${escaparHtml(tl)}<br><span style="font-size:0.65rem; color:#cbd5e1; font-weight:500;">Real vs. Modelos (L / P / RF)</span>
                </th>
            `).join('');

            const filasLineas = bt.lines.map((ln, idx) => {
                const bg = idx % 2 === 0 ? '#ffffff' : '#f8fafc';
                const celdasTrim = testLabels.map((_, tIdx) => {
                    const realVal = ln.real_held_out[tIdx] ?? 0;
                    const pLin = Math.round((ln.preds_by_model.linear || [])[tIdx] ?? 0);
                    const pPol = Math.round((ln.preds_by_model.poly || [])[tIdx] ?? 0);
                    const pRf  = Math.round((ln.preds_by_model.rf || [])[tIdx] ?? 0);
                    const pLinRaw = Number((ln.preds_by_model.linear || [])[tIdx] ?? 0).toFixed(1);
                    const pPolRaw = Number((ln.preds_by_model.poly || [])[tIdx] ?? 0).toFixed(1);
                    const pRfRaw  = Number((ln.preds_by_model.rf || [])[tIdx] ?? 0).toFixed(1);

                    return `
                    <td style="padding:9px 10px; text-align:center; border-left:1px solid #e2e8f0; font-size:0.76rem;">
                        <div style="margin-bottom:4px;">
                            <span style="background:#1e293b; color:#ffffff; font-weight:700; padding:2px 8px; border-radius:4px; font-size:0.74rem;">
                                Real: ${realVal} proy.
                            </span>
                        </div>
                        <div style="display:flex; justify-content:center; gap:4px; flex-wrap:wrap; font-size:0.69rem;">
                            <span title="Regresión Lineal: ${pLinRaw}" style="background:#f1f5f9; color:#334155; border:1px solid #cbd5e1; padding:1px 6px; border-radius:4px;">
                                Lin: <strong>${pLin}</strong> <span style="color:#64748b;">(${pLinRaw})</span>
                            </span>
                            <span title="Polinómica Grado 2: ${pPolRaw}" style="background:#eef0f7; color:#1e293b; border:1px solid #cbd5e1; padding:1px 6px; border-radius:4px;">
                                Poly: <strong>${pPol}</strong> <span style="color:#64748b;">(${pPolRaw})</span>
                            </span>
                            <span title="Random Forest: ${pRfRaw}" style="background:#f8fafc; color:#505984; border:1px solid #cbd5e1; padding:1px 6px; border-radius:4px;">
                                RF: <strong>${pRf}</strong> <span style="color:#64748b;">(${pRfRaw})</span>
                            </span>
                        </div>
                    </td>`;
                }).join('');

                const badgeColor = ln.trust_level.includes('Alta')
                    ? 'background:#1e293b; color:#ffffff;'
                    : (ln.trust_level.includes('Moderada') ? 'background:#eef0f7; color:#1e293b; border:1px solid #505984;' : 'background:#f1f5f9; color:#475569; border:1px solid #cbd5e1;');

                return `
                <tr style="background:${bg}; border-bottom:1px solid #e2e8f0;">
                    <td style="padding:9px 12px; font-weight:700; color:#1e293b; font-size:0.79rem;">
                        ${escaparHtml(ln.name)}
                        <div style="font-size:0.68rem; color:#64748b; font-weight:500; margin-top:2px;">
                            Entrenamiento: [${(ln.train_history || []).join(', ')}]
                        </div>
                    </td>
                    ${celdasTrim}
                    <td style="padding:9px 10px; text-align:center; border-left:1px solid #e2e8f0;">
                        <span style="font-size:0.72rem; font-weight:700; color:#505984; display:block;">${escaparHtml(ln.best_oos_model_label)}</span>
                        <span style="font-size:0.68rem; color:#64748b;">MAE: ±${Number(ln.mae_by_model[ln.best_oos_model] || 0).toFixed(2)}</span>
                    </td>
                    <td style="padding:9px 12px; font-size:0.73rem; color:#334155;">
                        <span style="display:inline-block; font-size:0.66rem; font-weight:700; padding:2px 8px; border-radius:99px; margin-bottom:3px; ${badgeColor}">
                            ${escaparHtml(ln.trust_level)}
                        </span>
                        <div style="line-height:1.35; color:#475569;">${escaparHtml(ln.trust_note)}</div>
                    </td>
                </tr>`;
            }).join('');

            cont.innerHTML = `
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:12px;">
                    <div>
                        <h4 style="margin:0; color:#1e293b; font-size:0.96rem; display:flex; align-items:center; gap:8px;">
                            <i class="ph-bold ph-flask" style="color:#505984;"></i> Laboratorio de Fiabilidad ML · Prueba Ciega con Datos Reales (Walk-Forward Backtesting)
                        </h4>
                        <p style="margin:3px 0 0 0; font-size:0.76rem; color:#64748b;">
                            Se ocultaron los últimos trimestres reales (<strong>${escaparHtml(rangoTest)}</strong>), entrenando los modelos únicamente con el pasado (<strong>${escaparHtml(rangoTrain)}</strong>) para verificar qué tan cerca predice cada algoritmo la realidad ya conocida.
                        </p>
                    </div>
                    <div style="display:flex; align-items:center; gap:6px;">
                        <span style="font-size:0.72rem; font-weight:700; color:#505984;">Corte de Prueba:</span>
                        <button type="button" class="btn-bt-mode" data-mode="2t" style="padding:5px 10px; border-radius:6px; font-size:0.73rem; font-weight:700; cursor:pointer; border:1px solid ${mode === '2t' ? '#1e293b' : '#cbd5e1'}; background:${mode === '2t' ? '#1e293b' : '#ffffff'}; color:${mode === '2t' ? '#ffffff' : '#334155'};">
                            Ocultar 2 Trimestres (T2 y T3 2026)
                        </button>
                        <button type="button" class="btn-bt-mode" data-mode="1t" style="padding:5px 10px; border-radius:6px; font-size:0.73rem; font-weight:700; cursor:pointer; border:1px solid ${mode === '1t' ? '#1e293b' : '#cbd5e1'}; background:${mode === '1t' ? '#1e293b' : '#ffffff'}; color:${mode === '1t' ? '#ffffff' : '#334155'};">
                            Ocultar 1 Trimestre (T3 2026)
                        </button>
                    </div>
                </div>

                <div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px;">
                    ${cardsModelos}
                </div>

                <div style="overflow-x:auto; border:1px solid #e2e8f0; border-radius:8px;">
                    <table style="width:100%; border-collapse:collapse; text-align:left;">
                        <thead>
                            <tr style="background: linear-gradient(135deg, #505984 0%, #1e293b 100%);">
                                <th style="padding:9px 12px; color:#ffffff; font-size:0.73rem;">Línea de Investigación (Serie de Entrenamiento)</th>
                                ${thTestCols}
                                <th style="padding:9px 10px; text-align:center; color:#ffffff; font-size:0.73rem; border-left:1px solid rgba(255,255,255,0.15);">Mejor Algoritmo</th>
                                <th style="padding:9px 12px; color:#ffffff; font-size:0.73rem;">Veredicto de Confiabilidad Empírica</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${filasLineas}
                        </tbody>
                    </table>
                </div>
            `;
            cont.style.display = 'block';

            cont.querySelectorAll('.btn-bt-mode').forEach(b => {
                b.addEventListener('click', () => {
                    window.currentBacktestHoldout = b.getAttribute('data-mode') || '2t';
                    renderizarLaboratorioBacktest(window.lastTendenciasData);
                });
            });
        }

        // 8. Historial de Snapshots de Predicción (Auditoría Predictiva vs. Realidad con Ficha Detallada)
        function renderizarSnapshots(snapshots) {
            const cont = document.getElementById('snapshots-tendencias-container');
            if (!cont) return;

            let contenido = '';
            if (!Array.isArray(snapshots) || snapshots.length === 0) {
                contenido = `
                <div style="background:#f8fafc; border:1px dashed #cbd5e1; border-radius:8px; padding:14px; text-align:center; color:#64748b; font-size:0.8rem;">
                    Aún no se han guardado instantáneas (Snapshots) de predicción. Usa el botón superior <strong>"Guardar Snapshot"</strong> para registrar el pronóstico completo por línea y por trimestre para auditarlo contra datos reales.
                </div>`;
            } else {
                const ventanasLbl = { all: 'Todo el hist.', '2y': 'Últ. 2 años (6T)', '1y': 'Últ. año (3T)' };
                const filas = snapshots.map((s, idx) => {
                    const bg = idx % 2 === 0 ? '#ffffff' : '#f8fafc';
                    const areas = Array.isArray(s.resumen_areas) ? s.resumen_areas : [];
                    const audTrims = Array.isArray(s.auditoria_trimestres) ? s.auditoria_trimestres : [];
                    const predT1 = areas.reduce((acc, a) => acc + (Number(a.primer_trimestre_pred) || 0), 0);
                    const lblT1  = (areas[0] && areas[0].primer_trimestre_label) ? areas[0].primer_trimestre_label : (s.trimestre_meta || '-');
                    const realT1 = Number(s.real_registrado_t1 || 0);
                    const esFuturoT1 = s.es_futuro_t1 !== undefined ? Boolean(s.es_futuro_t1) : (realT1 === 0);

                    // Chips compactos de todos los trimestres proyectados en este snapshot
                    let chipsTrimHtml = '';
                    if (audTrims.length > 0) {
                        chipsTrimHtml = `<div style="display:flex; flex-wrap:wrap; gap:4px; justify-content:center;">` +
                            audTrims.map(at => {
                                const estadoTxt = at.es_futuro
                                    ? `<span style="color:#64748b;">En espera (0 reg.)</span>`
                                    : `<strong style="color:#1e293b;">Real: ${at.real}</strong>`;
                                return `<span style="background:#eef0f7; border:1px solid #cbd5e1; padding:2px 6px; border-radius:4px; font-size:0.69rem; white-space:nowrap;">
                                    <strong>${escaparHtml(at.label)}:</strong> Pred ${at.predicho} · ${estadoTxt}
                                </span>`;
                            }).join('') +
                            `</div>`;
                    } else {
                        chipsTrimHtml = `
                            <span style="background:#eef0f7; padding:3px 8px; border-radius:4px; font-size:0.72rem;">
                                ${escaparHtml(lblT1)}: Pred. <strong>${predT1}</strong> · ${esFuturoT1 ? 'En espera (trim. futuro)' : `Real <strong>${realT1}</strong>`}
                            </span>`;
                    }

                    // Mini-resumen de las líneas incluidas
                    const miniLineas = areas
                        .filter(a => (Number(a.total_proyectado_linea) || Number(a.meta_final_pred) || 0) > 0)
                        .slice(0, 2)
                        .map(a => `${a.name}: ${a.total_proyectado_linea ?? a.meta_final_pred} proy.`)
                        .join(' · ');

                    return `
                    <tr style="background:${bg}; border-bottom:1px solid #e2e8f0; font-size:0.78rem;">
                        <td style="padding:9px 10px; font-weight:700; color:#1e293b;">
                            #${s.id} · ${escaparHtml(s.fecha_formateada)}
                            <div style="font-size:0.68rem; color:#64748b; font-weight:500;">Por: ${escaparHtml(s.usuario_nombre || 'Coordinador')}</div>
                        </td>
                        <td style="padding:9px 10px; color:#334155;">
                            <strong>${escaparHtml(s.carrera_nombre)}</strong>
                            <div style="font-size:0.68rem; color:#64748b;">${areas.length} líneas evaluadas</div>
                        </td>
                        <td style="padding:9px 10px; color:#505984; font-weight:600;">
                            ${escaparHtml(s.modelo_label)}
                            <div style="font-size:0.68rem; color:#64748b; font-weight:500;">Muestreo: ${escaparHtml(ventanasLbl[s.ventana_historial] || s.ventana_historial)}</div>
                        </td>
                        <td style="padding:9px 10px; text-align:center; color:#475569;">
                            <strong>${escaparHtml(s.trimestre_base || '-')} → ${escaparHtml(s.trimestre_meta || '-')}</strong>
                            <div style="font-size:0.68rem; color:#64748b;">Horizonte: ${s.horizonte_trimestres} trimestres</div>
                        </td>
                        <td style="padding:9px 10px; text-align:center; font-weight:700; color:#1e293b;">
                            <span style="font-size:0.86rem;">${s.total_proyectado} proy.</span>
                            <div style="font-size:0.66rem; color:#505984; font-weight:600; max-width:190px; margin:2px auto 0 auto; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="${escaparHtml(miniLineas)}">
                                ${escaparHtml(miniLineas || 'Ver desglose por línea')}
                            </div>
                        </td>
                        <td style="padding:9px 10px; text-align:center; color:#334155;">
                            ${chipsTrimHtml}
                        </td>
                        <td style="padding:9px 10px; text-align:center; color:#505984; font-weight:600;">
                            ±${Number(s.rmse_global || 0).toFixed(2)}
                            <div style="font-size:0.68rem; color:#475569;">Fiabilidad: ${Number(s.confianza_pct || 0).toFixed(0)}%</div>
                        </td>
                        <td style="padding:9px 10px; text-align:center; white-space:nowrap;">
                            <button type="button" class="btn-ver-snapshot" data-idx="${idx}" title="Ver ficha específica línea por línea y trimestre por trimestre" style="background:#505984; border:1px solid #505984; color:#ffffff; border-radius:6px; padding:5px 10px; cursor:pointer; font-size:0.72rem; font-weight:700; display:inline-flex; align-items:center; gap:4px; margin-right:4px;">
                                <i class="ph-bold ph-magnifying-glass-plus"></i> Ver Ficha
                            </button>
                            <button type="button" class="btn-del-snapshot" data-id="${s.id}" title="Eliminar Snapshot" style="background:transparent; border:1px solid #cbd5e1; color:#64748b; border-radius:6px; padding:5px 8px; cursor:pointer; font-size:0.72rem;">
                                <i class="ph-bold ph-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                }).join('');

                contenido = `
                <div style="overflow-x:auto; border:1px solid #e2e8f0; border-radius:8px;">
                    <table style="width:100%; border-collapse:collapse; text-align:left;">
                        <thead>
                            <tr style="background:#f1f5f9; border-bottom:1px solid #cbd5e1;">
                                <th style="padding:9px 10px; color:#1e293b; font-size:0.72rem;">ID / Fecha Snapshot</th>
                                <th style="padding:9px 10px; color:#1e293b; font-size:0.72rem;">Carrera / Alcance</th>
                                <th style="padding:9px 10px; color:#1e293b; font-size:0.72rem;">Algoritmo y Ventana</th>
                                <th style="padding:9px 10px; text-align:center; color:#1e293b; font-size:0.72rem;">Horizonte Temporal</th>
                                <th style="padding:9px 10px; text-align:center; color:#1e293b; font-size:0.72rem;">Vol. Acumulado Proy.</th>
                                <th style="padding:9px 10px; text-align:center; color:#1e293b; font-size:0.72rem;">Seguimiento por Trimestre Proyectado (Pred. vs Real)</th>
                                <th style="padding:9px 10px; text-align:center; color:#1e293b; font-size:0.72rem;">Error / Fiabilidad</th>
                                <th style="padding:9px 10px; text-align:center; color:#1e293b; font-size:0.72rem;">Auditoría</th>
                            </tr>
                        </thead>
                        <tbody>${filas}</tbody>
                    </table>
                </div>`;
            }

            cont.innerHTML = `
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; margin-bottom:10px;">
                    <div>
                        <h4 style="margin:0; color:#1e293b; font-size:0.95rem; display:flex; align-items:center; gap:7px;">
                            <i class="ph-bold ph-clock-counter-clockwise" style="color:#505984;"></i> Historial de Snapshots de Predicción (Auditoría Específica por Línea y Trimestre)
                        </h4>
                        <span style="font-size:0.73rem; color:#64748b;">Haz clic en <strong>"Ver Ficha"</strong> para inspeccionar la matriz completa de líneas, curvas trimestrales, intervalos de confianza y contraste contra proyectos reales.</span>
                    </div>
                </div>
                ${contenido}
            `;
            cont.style.display = 'block';

            cont.querySelectorAll('.btn-ver-snapshot').forEach(btn => {
                btn.addEventListener('click', () => {
                    const idx = parseInt(btn.getAttribute('data-idx'), 10);
                    if (!isNaN(idx) && snapshots[idx]) {
                        abrirModalAuditoriaSnapshot(snapshots[idx]);
                    }
                });
            });

            cont.querySelectorAll('.btn-del-snapshot').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const id = btn.getAttribute('data-id');
                    if (!id) return;
                    const res = await fetch('api-prediccion-tendencias', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ action: 'delete_snapshot', id_snapshot: id })
                    });
                    const json = await res.json();
                    if (json.ok && Array.isArray(json.snapshots)) {
                        if (window.lastTendenciasData) window.lastTendenciasData.snapshots = json.snapshots;
                        renderizarSnapshots(json.snapshots);
                    }
                });
            });
        }

        // Modal de Ficha Específica de Auditoría de Snapshot
        function abrirModalAuditoriaSnapshot(s) {
            const modal = document.getElementById('snapshot-audit-modal');
            const content = document.getElementById('snapshot-audit-modal-content');
            if (!modal || !content || !s) return;

            const areas = Array.isArray(s.resumen_areas) ? s.resumen_areas : [];
            const futLabels = Array.isArray(s.future_labels) && s.future_labels.length
                ? s.future_labels
                : ((areas[0] && Array.isArray(areas[0].future_labels)) ? areas[0].future_labels : []);
            const audTrims = Array.isArray(s.auditoria_trimestres) ? s.auditoria_trimestres : [];
            const ventanasMap = { all: 'Todo el Historial', '2y': 'Últimos 2 Años (6 Trimestres)', '1y': 'Último Año (3 Trimestres)' };
            const fuenteMap = { pst: 'Solo Proyectos PST Culminados', all: 'PST + Investigaciones en Curso' };

            const cardsTrimGlobal = audTrims.map(at => {
                const badgeEstado = at.es_futuro
                    ? `<span style="background:#f1f5f9; color:#475569; border:1px solid #cbd5e1; padding:2px 7px; border-radius:99px; font-size:0.66rem; font-weight:700;">⏳ Pendiente por transcurrir</span>`
                    : `<span style="background:#1e293b; color:#ffffff; padding:2px 7px; border-radius:99px; font-size:0.66rem; font-weight:700;">✓ Trimestre con registros (Δ ${at.diferencia >= 0 ? '+' : ''}${at.diferencia})</span>`;
                return `
                <div style="flex:1; min-width:175px; background:#f8fafc; border:1px solid #cbd5e1; border-top:3px solid #505984; border-radius:8px; padding:10px 12px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; gap:4px; margin-bottom:6px;">
                        <strong style="font-size:0.84rem; color:#1e293b;">${escaparHtml(at.label)}</strong>
                        ${badgeEstado}
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:0.76rem; color:#334155;">
                        <span>Proyectado IA: <strong style="color:#505984; font-size:0.84rem;">${at.predicho} proy.</strong></span>
                        <span>Real en BD: <strong style="color:#1e293b; font-size:0.84rem;">${at.real} proy.</strong></span>
                    </div>
                </div>`;
            }).join('');

            const thTrimCols = futLabels.map(fl => `
                <th style="padding:9px 8px; text-align:center; color:#ffffff; font-size:0.72rem; border-left:1px solid rgba(255,255,255,0.15);">
                    ${escaparHtml(fl)}<br><span style="font-size:0.63rem; color:#cbd5e1; font-weight:400;">Pred (Rango) vs Real</span>
                </th>
            `).join('');

            const filasDetalleLineas = areas.map((a, idx) => {
                const bg = idx % 2 === 0 ? '#ffffff' : '#f8fafc';
                const curva = Array.isArray(a.curva_predicha) ? a.curva_predicha : (Array.isArray(a.adoption_curve) ? a.adoption_curve : []);
                const ciLow = Array.isArray(a.ci_lower) ? a.ci_lower : [];
                const ciHigh = Array.isArray(a.ci_upper) ? a.ci_upper : [];
                const reales = Array.isArray(a.reales_por_trimestre) ? a.reales_por_trimestre : [];
                const esFut = Array.isArray(a.es_futuro_por_trimestre) ? a.es_futuro_por_trimestre : [];
                const histSeries = Array.isArray(a.history) ? a.history : [];
                const histLbls = Array.isArray(a.history_labels) ? a.history_labels : [];

                const histResumen = histSeries.length
                    ? histSeries.map((v, i) => `${histLbls[i] || ('T' + (i + 1))}: ${v}`).join(' | ')
                    : `Último trim. base: ${a.ultimo_real ?? 0} proy.`;

                const tdsTrim = futLabels.map((_, i) => {
                    const pExact = Number(curva[i] ?? 0);
                    const pRound = Math.round(pExact);
                    const rVal   = Number(reales[i] ?? 0);
                    const isF    = esFut[i] !== undefined ? Boolean(esFut[i]) : (rVal === 0);
                    const minB   = ciLow[i] !== undefined ? Math.round(Number(ciLow[i])) : Math.max(0, Math.round(pExact - Number(a.rmse || 0)));
                    const maxB   = ciHigh[i] !== undefined ? Math.round(Number(ciHigh[i])) : Math.round(pExact + Number(a.rmse || 0));

                    return `
                    <td style="padding:8px 8px; text-align:center; border-left:1px solid #e2e8f0; font-size:0.75rem;">
                        <div style="font-weight:800; color:#1e293b; font-size:0.82rem;">
                            Pred: ${pRound} <span style="font-weight:500; color:#64748b; font-size:0.69rem;">(${pExact.toFixed(1)})</span>
                        </div>
                        <div style="font-size:0.67rem; color:#505984; margin:2px 0;">
                            Banda: [${minB} – ${maxB}]
                        </div>
                        <div style="margin-top:3px;">
                            ${isF
                                ? `<span style="background:#f1f5f9; color:#64748b; padding:1px 6px; border-radius:4px; font-size:0.66rem;">Real: En espera (0)</span>`
                                : `<span style="background:#eef0f7; color:#1e293b; font-weight:700; padding:1px 6px; border-radius:4px; font-size:0.68rem;">Real BD: ${rVal} proy.</span>`
                            }
                        </div>
                    </td>`;
                }).join('');

                const totLineaProy = a.total_proyectado_linea !== undefined
                    ? a.total_proyectado_linea
                    : curva.reduce((acc, v) => acc + Math.round(Number(v) || 0), 0);

                return `
                <tr style="background:${bg}; border-bottom:1px solid #e2e8f0;">
                    <td style="padding:9px 12px;">
                        <strong style="color:#1e293b; font-size:0.8rem; display:block;">${escaparHtml(a.name)}</strong>
                        <span style="font-size:0.68rem; color:#505984; font-weight:600;">${escaparHtml(a.carrera || 'General')} · ${escaparHtml(a.model_applied_label || s.modelo_label)}</span>
                        <div style="font-size:0.67rem; color:#64748b; margin-top:3px;">
                            <strong>Historial Base:</strong> ${escaparHtml(histResumen)}
                        </div>
                    </td>
                    <td style="padding:8px 8px; text-align:center; font-size:0.76rem; color:#334155;">
                        <strong>${a.total_historico ?? '-'}</strong>
                        <div style="font-size:0.67rem; color:#64748b;">Últ: ${a.ultimo_real ?? 0}</div>
                    </td>
                    ${tdsTrim}
                    <td style="padding:8px 8px; text-align:center; font-weight:800; color:#1e293b; font-size:0.82rem; border-left:1px solid #e2e8f0;">
                        ${totLineaProy} proy.
                    </td>
                    <td style="padding:8px 8px; text-align:center; font-size:0.74rem; color:#505984; font-weight:700;">
                        ±${Number(a.rmse || s.rmse_global || 0).toFixed(2)}
                        <div style="font-size:0.66rem; color:#64748b;">Sat: ${Math.round(Number(a.saturation_index || 0))}%</div>
                    </td>
                </tr>`;
            }).join('');

            content.innerHTML = `
                <div style="background: linear-gradient(135deg, #505984 0%, #1e293b 100%); color:#ffffff; padding:18px 22px; border-radius:13px 13px 0 0; display:flex; justify-content:space-between; align-items:flex-start; gap:12px;">
                    <div>
                        <div style="font-size:0.7rem; font-weight:800; color:#cbd5e1; text-transform:uppercase; letter-spacing:1px;">
                            AUDITORÍA ESPECÍFICA DE SNAPSHOT #${s.id} · ${escaparHtml(s.fecha_formateada)}
                        </div>
                        <h3 style="margin:4px 0; font-size:1.15rem; font-weight:800; color:#ffffff;">
                            ${escaparHtml(s.carrera_nombre)} — ${escaparHtml(s.modelo_label)}
                        </h3>
                        <div style="font-size:0.78rem; color:#e2e8f0;">
                            Registrado por <strong>${escaparHtml(s.usuario_nombre || 'Coordinador CIIDI')}</strong> · Fuente: <strong>${escaparHtml(fuenteMap[s.fuente_datos] || s.fuente_datos)}</strong> · Ventana: <strong>${escaparHtml(ventanasMap[s.ventana_historial] || s.ventana_historial)}</strong>
                        </div>
                    </div>
                    <button type="button" id="btn-close-snapshot-modal" style="background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.3); color:#ffffff; border-radius:8px; padding:6px 12px; cursor:pointer; font-weight:700; font-size:0.8rem;">
                        ✕ Cerrar
                    </button>
                </div>

                <div style="padding:18px 22px;">
                    <!-- Resumen de Parámetros y Métricas Globales del Snapshot -->
                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(170px, 1fr)); gap:10px; margin-bottom:16px; background:#f8fafc; border:1px solid #cbd5e1; border-radius:8px; padding:12px;">
                        <div>
                            <div style="font-size:0.68rem; font-weight:800; color:#505984; text-transform:uppercase;">Horizonte Temporal</div>
                            <div style="font-size:0.88rem; font-weight:800; color:#1e293b; margin-top:2px;">${escaparHtml(s.trimestre_base || '-')} → ${escaparHtml(s.trimestre_meta || '-')} (${s.horizonte_trimestres}T)</div>
                        </div>
                        <div>
                            <div style="font-size:0.68rem; font-weight:800; color:#505984; text-transform:uppercase;">Proyectos Analizados (Base)</div>
                            <div style="font-size:0.88rem; font-weight:800; color:#1e293b; margin-top:2px;">${s.total_historico} proyectos históricos</div>
                        </div>
                        <div>
                            <div style="font-size:0.68rem; font-weight:800; color:#505984; text-transform:uppercase;">Demanda Total Proyectada</div>
                            <div style="font-size:0.88rem; font-weight:800; color:#505984; margin-top:2px;">${s.total_proyectado} proyectos (${areas.length} líneas)</div>
                        </div>
                        <div>
                            <div style="font-size:0.68rem; font-weight:800; color:#505984; text-transform:uppercase;">Margen de Error y Confianza</div>
                            <div style="font-size:0.88rem; font-weight:800; color:#1e293b; margin-top:2px;">RMSE ±${Number(s.rmse_global || 0).toFixed(2)} · MAE ${Number(s.mae_global || 0).toFixed(2)} (${Number(s.confianza_pct || 0).toFixed(0)}%)</div>
                        </div>
                    </div>

                    <!-- Seguimiento Global Trimestre a Trimestre -->
                    <h4 style="margin:0 0 8px 0; font-size:0.86rem; color:#1e293b; text-transform:uppercase; letter-spacing:0.4px;">
                        1. Balance Global por Trimestre Proyectado (Pronóstico IA vs. Realidad en Base de Datos)
                    </h4>
                    <div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:18px;">
                        ${cardsTrimGlobal}
                    </div>

                    <!-- Matriz Desglosada Línea por Línea -->
                    <h4 style="margin:0 0 8px 0; font-size:0.86rem; color:#1e293b; text-transform:uppercase; letter-spacing:0.4px;">
                        2. Desglose Específico por Línea de Investigación y Curva Trimestral
                    </h4>
                    <div style="overflow-x:auto; border:1px solid #cbd5e1; border-radius:8px;">
                        <table style="width:100%; border-collapse:collapse; text-align:left;">
                            <thead>
                                <tr style="background:#1e293b;">
                                    <th style="padding:9px 12px; color:#ffffff; font-size:0.72rem;">Línea de Investigación / Historial de Entrenamiento</th>
                                    <th style="padding:9px 8px; text-align:center; color:#ffffff; font-size:0.72rem;">Hist.</th>
                                    ${thTrimCols}
                                    <th style="padding:9px 8px; text-align:center; color:#ffffff; font-size:0.72rem; border-left:1px solid rgba(255,255,255,0.15);">Total Línea</th>
                                    <th style="padding:9px 8px; text-align:center; color:#ffffff; font-size:0.72rem;">RMSE / Sat.</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${filasDetalleLineas}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;

            modal.style.display = 'flex';

            const btnClose = document.getElementById('btn-close-snapshot-modal');
            if (btnClose) {
                btnClose.onclick = () => { modal.style.display = 'none'; };
            }
            modal.onclick = (ev) => {
                if (ev.target === modal) modal.style.display = 'none';
            };
        }

        // =========================================================================
        //  GENERADOR DE INFORME EJECUTIVO PDF (100% NATIVO · SIN LIBRERÍAS EXTERNAS)
        //  Construye un documento editorial exclusivo con la paleta #505984, #1e293b,
        //  #7090cb y ejecuta el motor nativo de exportación PDF del navegador.
        // =========================================================================
        if (btnDescargarPdf) {
            btnDescargarPdf.addEventListener('click', () => {
                const data = window.lastTendenciasData;
                const params = window.lastTendenciasParams;
                if (!data || !Array.isArray(data.areas) || data.areas.length === 0) {
                    alert('Primero calcule una proyección para exportar el informe PDF.');
                    return;
                }

                const areasVisibles = obtenerAreasVisibles(data);
                const predSteps = (data.future_labels && data.future_labels.length)
                    ? data.future_labels.length
                    : (params.pred_steps || 4);
                const futureLabels = (Array.isArray(data.future_labels) && data.future_labels.length >= predSteps)
                    ? data.future_labels.slice(0, predSteps)
                    : Array.from({ length: predSteps }, (_, i) => `T+${i + 1}`);
                const histLabels = data.history_labels || [];

                const ahora = new Date();
                const fechaStr = ahora.toLocaleDateString('es-VE', { day: '2-digit', month: 'long', year: 'numeric' });
                const horaStr  = ahora.toLocaleTimeString('es-VE', { hour: '2-digit', minute: '2-digit' });
                const codigoReporte = `CIIDI-ML-${ahora.getFullYear()}${String(ahora.getMonth() + 1).padStart(2, '0')}${String(ahora.getDate()).padStart(2, '0')}-${String(ahora.getHours()).padStart(2, '0')}${String(ahora.getMinutes()).padStart(2, '0')}`;

                const carreraFiltro = (data.params && data.params.carrera_nombre) ? data.params.carrera_nombre : 'Todas las Carreras / PNF';
                const ventanasMap = { all: 'Todo el Historial', '2y': 'Últimos 2 Años (6 Trimestres)', '1y': 'Último Año (3 Trimestres)' };
                const ventanaTxt = ventanasMap[params.historial_base] || params.historial_base;
                const rangoHistTxt = histLabels.length ? `${histLabels[0]} a ${histLabels[histLabels.length - 1]}` : 'N/A';
                const confGlobal = Number(data.metrics && data.metrics.confidence_score) || 75;
                const rmseGlobal = Number(data.metrics && data.metrics.global_rmse) || 0;
                const maeGlobal  = Number(data.metrics && data.metrics.global_mae) || 0;
                const fuenteTxt  = params.fuente_datos === 'all' ? 'Proyectos PST + Investigaciones en Curso' : 'Solo Proyectos PST Culminados';

                // Capturar imagen de alta resolución del gráfico sobre fondo blanco
                let chartImgUrl = '';
                const chartCanvas = document.getElementById('tendenciasChart');
                if (chartCanvas) {
                    const offCanvas = document.createElement('canvas');
                    offCanvas.width = chartCanvas.width;
                    offCanvas.height = chartCanvas.height;
                    const offCtx = offCanvas.getContext('2d');
                    offCtx.fillStyle = '#ffffff';
                    offCtx.fillRect(0, 0, offCanvas.width, offCanvas.height);
                    offCtx.drawImage(chartCanvas, 0, 0);
                    chartImgUrl = offCanvas.toDataURL('image/png', 1.0);
                }

                // Calcular KPIs ejecutivos
                let maxDem = { name: '-', val: -1 };
                let minDem = { name: '-', val: 999999 };
                let maxAcel = { name: '-', pct: -999 };
                let sumaTrim = new Array(predSteps).fill(0);
                let totalProyectadoHorizonte = 0;

                areasVisibles.forEach(a => {
                    const preds = a.adoption_curve || [];
                    const hists = a.history || [];
                    if (preds.length >= predSteps) {
                        const fin = Math.round(preds[predSteps - 1]);
                        if (fin > maxDem.val) maxDem = { name: a.name, val: fin };
                        if (fin < minDem.val) minDem = { name: a.name, val: fin };
                        const lastH = hists.length ? hists[hists.length - 1] : 0;
                        const pct = lastH > 0 ? ((preds[predSteps - 1] - lastH) / lastH) * 100 : (fin > 0 ? 100 : 0);
                        if (pct > maxAcel.pct) maxAcel = { name: a.name, pct };
                        for (let i = 0; i < predSteps; i++) {
                            sumaTrim[i] += Math.round(preds[i]);
                            totalProyectadoHorizonte += Math.round(preds[i]);
                        }
                    }
                });
                if (minDem.val === 999999) minDem.val = 0;
                if (maxDem.val === -1) maxDem.val = 0;
                if (maxAcel.pct === -999) maxAcel.pct = 0;

                let pIdx = 0, pMax = -1;
                for (let i = 0; i < predSteps; i++) {
                    if (sumaTrim[i] > pMax) { pMax = sumaTrim[i]; pIdx = i; }
                }
                if (pMax < 0) pMax = 0;
                const picoTrimLabel = futureLabels[pIdx] || `T+${pIdx + 1}`;

                // Construir filas de Benchmark de Modelos
                const rmseByModel = (data.metrics && data.metrics.rmse_by_model) || {};
                const maeByModel  = (data.metrics && data.metrics.mae_by_model) || {};
                const r2ByModel   = (data.metrics && data.metrics.r2_by_model) || {};
                const bestMod     = (data.metrics && data.metrics.best_global_model) || 'linear';

                const modelosBench = [
                    { key: 'linear', label: 'Regresión Lineal Múltiple (OLS)' },
                    { key: 'poly',   label: 'Regresión Polinómica (Grado 2)' },
                    { key: 'rf',     label: 'Random Forest Regressor (Ensamble)' }
                ];
                const filasBenchHtml = modelosBench.map(mb => {
                    const rVal = rmseByModel[mb.key] !== undefined ? `±${Number(rmseByModel[mb.key]).toFixed(3)}` : 'N/A';
                    const mVal = maeByModel[mb.key]  !== undefined ? Number(maeByModel[mb.key]).toFixed(3) : 'N/A';
                    const qVal = r2ByModel[mb.key]   !== undefined ? `${(Number(r2ByModel[mb.key]) * 100).toFixed(1)}%` : 'N/A';
                    const esOptimo = bestMod === mb.key;
                    const esActivo = data.model_used === mb.key || (data.model_used === 'auto' && esOptimo);
                    return `
                    <tr style="${esActivo ? 'background:#eef0f7; font-weight:700;' : ''}">
                        <td>${escaparHtml(mb.label)}</td>
                        <td style="text-align:center;">${rVal}</td>
                        <td style="text-align:center;">${mVal}</td>
                        <td style="text-align:center;">${qVal}</td>
                        <td style="text-align:center;">${esOptimo ? '<strong>Óptimo (Menor Error)</strong>' : (esActivo ? 'Modelo Seleccionado' : 'Referencia')}</td>
                    </tr>`;
                }).join('');

                // Tabla de Matriz Cuantitativa y Recomendaciones
                const thColsProy = futureLabels.map(fl => `<th style="text-align:center;">${escaparHtml(fl)}</th>`).join('');
                const filasMatrizHtml = areasVisibles.map(area => {
                    const diag = diagnosticarLinea(area, predSteps);
                    const preds = (area.adoption_curve || []).slice(0, predSteps);
                    const tds = futureLabels.map((_, i) => `<td style="text-align:center; font-weight:700;">${Math.round(preds[i] ?? 0)}</td>`).join('');
                    return `
                    <tr>
                        <td>
                            <strong>${escaparHtml(area.name)}</strong>
                            <div style="font-size:10px; color:#505984;">${escaparHtml(area.carrera_nombre || 'General')}</div>
                        </td>
                        <td style="text-align:center;">${diag.totalHist}</td>
                        ${tds}
                        <td style="text-align:center;">±${Number(area.rmse || 0).toFixed(2)}</td>
                        <td style="text-align:center;"><strong>${diag.satInt}%</strong></td>
                        <td style="text-align:center;"><span class="badge-diag">${escaparHtml(diag.classLabel)}</span></td>
                    </tr>`;
                }).join('');

                const bloquesRecomendacionHtml = areasVisibles.map(area => {
                    const diag = diagnosticarLinea(area, predSteps);
                    return `
                    <div class="rec-item">
                        <div class="rec-header">
                            <strong>${escaparHtml(area.name)} (${escaparHtml(area.carrera_nombre || 'General')})</strong>
                            <span>${escaparHtml(diag.classLabel)} · Meta: ${diag.targetPrediction} proy. · Saturación: ${diag.satInt}%</span>
                        </div>
                        <div class="rec-body">${escaparHtml(diag.recomendacion)}</div>
                    </div>`;
                }).join('');

                const htmlReport = `<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe Ejecutivo Predictivo CIIDI - ${escaparHtml(codigoReporte)}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body {
            font-family: 'Segoe UI', Inter, Arial, sans-serif;
            color: #1e293b;
            background: #ffffff;
            margin: 0;
            padding: 0;
            font-size: 11.5px;
            line-height: 1.45;
        }
        .report-container {
            max-width: 190mm;
            margin: 0 auto;
        }
        .banner {
            background: linear-gradient(135deg, #505984 0%, #1e293b 100%);
            color: #ffffff;
            padding: 20px 24px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }
        .banner-left .sub {
            font-size: 9.5px;
            font-weight: 800;
            color: #cbd5e1;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }
        .banner-left h1 {
            margin: 4px 0 6px 0;
            font-size: 19px;
            font-weight: 800;
            color: #ffffff;
        }
        .banner-left p {
            margin: 0;
            font-size: 11px;
            color: #e2e8f0;
        }
        .banner-right {
            text-align: right;
            font-size: 10px;
            color: #e2e8f0;
            border-left: 1px solid rgba(255,255,255,0.2);
            padding-left: 16px;
        }
        .banner-right .code {
            font-weight: 800;
            font-size: 11px;
            color: #ffffff;
            background: rgba(255,255,255,0.14);
            padding: 3px 8px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 4px;
        }
        .meta-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-left: 4px solid #505984;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 16px;
        }
        .meta-item .lbl {
            font-size: 9px;
            font-weight: 800;
            color: #505984;
            text-transform: uppercase;
        }
        .meta-item .val {
            font-size: 11.5px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 2px;
        }
        .meta-item .sub {
            font-size: 9.5px;
            color: #64748b;
        }
        .section-block {
            margin-bottom: 16px;
        }
        .section-title {
            font-size: 12px;
            font-weight: 800;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border-bottom: 2px solid #505984;
            padding-bottom: 4px;
            margin-bottom: 10px;
        }
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 10px;
        }
        .kpi-card {
            border: 1px solid #cbd5e1;
            border-top: 3px solid #505984;
            border-radius: 8px;
            padding: 10px;
            background: #ffffff;
        }
        .kpi-card .k-title {
            font-size: 8.8px;
            font-weight: 800;
            color: #505984;
            text-transform: uppercase;
        }
        .kpi-card .k-main {
            font-size: 11.5px;
            font-weight: 800;
            color: #1e293b;
            margin: 4px 0;
        }
        .kpi-card .k-sub {
            font-size: 9.8px;
            color: #475569;
            background: #f8fafc;
            padding: 2px 6px;
            border-radius: 4px;
        }
        .synthesis-box {
            background: #eef0f7;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 10.8px;
            color: #1e293b;
        }
        .chart-frame {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            margin-bottom: 10px;
            background: #ffffff;
        }
        .chart-frame img {
            max-width: 100%;
            max-height: 230px;
            height: auto;
        }
        table.corp-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.2px;
        }
        table.corp-table th {
            background: #1e293b;
            color: #ffffff;
            padding: 7px 8px;
            text-align: left;
            font-weight: 700;
            font-size: 9.5px;
            text-transform: uppercase;
        }
        table.corp-table td {
            border-bottom: 1px solid #cbd5e1;
            padding: 7px 8px;
            color: #1e293b;
        }
        table.corp-table tr:nth-child(even) {
            background: #f8fafc;
        }
        .badge-diag {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 700;
            background: #eef0f7;
            color: #1e293b;
            border: 1px solid #cbd5e1;
        }
        .seas-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        .seas-card {
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            padding: 8px 10px;
            background: #f8fafc;
        }
        .seas-card.active {
            background: #eef0f7;
            border-color: #505984;
        }
        .seas-hdr {
            font-size: 9.5px;
            font-weight: 700;
            color: #505984;
        }
        .seas-val {
            font-size: 11px;
            font-weight: 800;
            color: #1e293b;
            margin-top: 3px;
        }
        .seas-val span {
            font-weight: 500;
            color: #64748b;
            font-size: 9.5px;
        }
        .rec-item {
            border: 1px solid #cbd5e1;
            border-left: 4px solid #505984;
            border-radius: 6px;
            padding: 8px 12px;
            margin-bottom: 8px;
            background: #f8fafc;
            page-break-inside: avoid;
        }
        .rec-header {
            display: flex;
            justify-content: space-between;
            font-size: 10.5px;
            color: #1e293b;
            margin-bottom: 3px;
        }
        .rec-header span {
            font-size: 9.5px;
            font-weight: 700;
            color: #505984;
        }
        .rec-body {
            font-size: 10px;
            color: #334155;
        }
        .footer {
            margin-top: 18px;
            padding-top: 8px;
            border-top: 1px solid #cbd5e1;
            display: flex;
            justify-content: space-between;
            font-size: 9.5px;
            color: #64748b;
        }
        .floating-actions {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 999;
        }
        .btn-action-pdf {
            background: linear-gradient(135deg, #505984 0%, #1e293b 100%);
            color: #ffffff;
            border: none;
            padding: 11px 18px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(30, 41, 59, 0.25);
        }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="floating-actions no-print">
        <button class="btn-action-pdf" onclick="window.print()">Imprimir / Guardar como PDF</button>
    </div>

    <div class="report-container">
        <div class="banner">
            <div class="banner-left">
                <div class="sub">SISTEMA INTEGRAL CIIDI · INTELIGENCIA PREDICTIVA</div>
                <h1>Informe Ejecutivo de Proyección Analítica (IA)</h1>
                <p>Proyección trimestral de volumen, adopción y saturación por Línea de Investigación</p>
            </div>
            <div class="banner-right">
                <div class="code">${escaparHtml(codigoReporte)}</div>
                <div>Emitido: ${escaparHtml(fechaStr)}</div>
                <div>Hora: ${escaparHtml(horaStr)}</div>
            </div>
        </div>

        <div class="meta-strip">
            <div class="meta-item">
                <div class="lbl">Carrera / PNF Filtrado</div>
                <div class="val">${escaparHtml(carreraFiltro)}</div>
                <div class="sub">Fuente: ${escaparHtml(fuenteTxt)} (${areasVisibles.length} líneas)</div>
            </div>
            <div class="meta-item">
                <div class="lbl">Algoritmo ML Activo</div>
                <div class="val">${escaparHtml(data.model_label || 'Regresión ML')}</div>
                <div class="sub">RMSE: ±${rmseGlobal.toFixed(2)} | MAE: ${maeGlobal.toFixed(2)} | Fiabilidad: ${confGlobal.toFixed(0)}%</div>
            </div>
            <div class="meta-item">
                <div class="lbl">Horizonte y Ventana</div>
                <div class="val">${predSteps} Trimestres (${escaparHtml(futureLabels[0])} – ${escaparHtml(futureLabels[futureLabels.length - 1])})</div>
                <div class="sub">${escaparHtml(ventanaTxt)} (${escaparHtml(rangoHistTxt)})</div>
            </div>
        </div>

        <div class="section-block">
            <div class="section-title">1. RESUMEN EJECUTIVO E INDICADORES ESTRATÉGICOS (KPIs)</div>
            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="k-title">Línea Líder (Demanda)</div>
                    <div class="k-main">${escaparHtml(maxDem.name)}</div>
                    <div class="k-sub">Cima proyectada: <strong>${maxDem.val} proy.</strong></div>
                </div>
                <div class="kpi-card">
                    <div class="k-title">Mayor Impulso</div>
                    <div class="k-main">${escaparHtml(maxAcel.name)}</div>
                    <div class="k-sub">Tasa: <strong>${maxAcel.pct >= 0 ? '+' : ''}${maxAcel.pct.toFixed(1)}%</strong></div>
                </div>
                <div class="kpi-card">
                    <div class="k-title">Menor Flujo Esperado</div>
                    <div class="k-main">${escaparHtml(minDem.name)}</div>
                    <div class="k-sub">Volumen: <strong>${minDem.val} proy.</strong></div>
                </div>
                <div class="kpi-card">
                    <div class="k-title">Trimestre Pico</div>
                    <div class="k-main">${escaparHtml(picoTrimLabel)}</div>
                    <div class="k-sub">Carga: <strong>${pMax} simultáneos</strong></div>
                </div>
            </div>
            <div class="synthesis-box">
                <strong>Síntesis Analítica de IA:</strong> Para el filtro <em>"${escaparHtml(carreraFiltro)}"</em>, el modelo <strong>${escaparHtml(data.model_label || 'seleccionado')}</strong> estima una demanda acumulada de <strong>${totalProyectadoHorizonte} proyectos</strong> en los próximos ${predSteps} trimestres académicos. El mayor pico operativo ocurrirá en <strong>${escaparHtml(picoTrimLabel)}</strong> (${pMax} proyectos simultáneos), encabezado por la línea <strong>${escaparHtml(maxDem.name)}</strong>.
            </div>
        </div>

        <div class="section-block">
            <div class="section-title">2. CURVA DE PROYECCIÓN MULTIVARIABLE Y BENCHMARK DE ALGORITMOS</div>
            ${chartImgUrl ? `<div class="chart-frame"><img src="${chartImgUrl}" alt="Gráfico de Proyección IA"></div>` : ''}
            <table class="corp-table">
                <thead>
                    <tr>
                        <th>Algoritmo Evaluado en Benchmark</th>
                        <th style="text-align:center;">Error RMSE</th>
                        <th style="text-align:center;">Error MAE</th>
                        <th style="text-align:center;">Ajuste R²</th>
                        <th style="text-align:center;">Condición en el Modelo</th>
                    </tr>
                </thead>
                <tbody>${filasBenchHtml}</tbody>
            </table>
        </div>

        <div class="section-block">
            <div class="section-title">3. MATRIZ CUANTITATIVA DE PROYECCIÓN TRIMESTRAL POR LÍNEA</div>
            <table class="corp-table">
                <thead>
                    <tr>
                        <th>Línea de Investigación / Carrera</th>
                        <th style="text-align:center;">Culm.</th>
                        ${thColsProy}
                        <th style="text-align:center;">RMSE</th>
                        <th style="text-align:center;">Sat. %</th>
                        <th style="text-align:center;">Diagnóstico</th>
                    </tr>
                </thead>
                <tbody>${filasMatrizHtml}</tbody>
            </table>
        </div>

        <div class="section-block">
            <div class="section-title">4. DIAGNÓSTICO DETALLADO Y PLAN DE ACCIÓN RECOMENDADO POR LÍNEA</div>
            ${bloquesRecomendacionHtml}
        </div>

        <div class="footer">
            <span>Universidad Politécnica Territorial (UPTTMBI) · Sistema Integral CIIDI</span>
            <span>Documento generado automáticamente por el Motor de Inteligencia Predictiva</span>
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() { window.print(); }, 350);
        };
    </script>
</body>
</html>`;

                const printWin = window.open('', '_blank');
                if (!printWin) {
                    alert('Por favor permite las ventanas emergentes en tu navegador para abrir la vista de exportación PDF.');
                    return;
                }
                printWin.document.open();
                printWin.document.write(htmlReport);
                printWin.document.close();
            });
        }

        // Ejecutar proyección inicial al abrir el Dashboard
        ejecutarProyeccion();
    });
})();
