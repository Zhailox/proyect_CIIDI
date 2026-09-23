<?php
// modules/SuperAdmin/views/gestor_reportes.php
$resumen = $resumen ?? ['pst' => 0, 'empresas' => 0, 'usuarios' => 0, 'logs' => 0];
$dominioActual = $dominio ?? 'pst';
$filtrosActivos = $filtros ?? [];
$graficoStats = $grafico_stats ?? ['labels' => [], 'valores' => []];
$lineasInvestigacion = $lineas ?? [];
?>

<div class="sa-hero-header mb-3" style="background: #ffffff !important; border: 1px solid rgba(80, 89, 132, 0.18); padding: 1.75rem 2rem; border-radius: var(--radius-md); box-shadow: 0 4px 20px rgba(18, 26, 62, 0.04);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-terciario) !important; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 0.4rem;">
                <i class="ph-bold ph-sliders"></i> ESTUDIO DE EXPORTACIÓN & REPORTES CIIDI
            </div>
            <h1 style="font-size: 2rem; font-weight: 800; color: #121a3e !important; margin: 0; line-height: 1.2;">
                Estudio Institucional de Exportación PST
            </h1>
            <p style="margin: 0.4rem 0 0 0; color: #64748b !important; font-size: 0.95rem;">
                Configure y personalice granularmente los datos, columnas, métricas estadísticas y diseño antes de generar su reporte oficial.
            </p>
        </div>

        <div>
            <a href="sudoadmin" class="btn btn-outline" style="border-color: var(--color-secundario); color: var(--color-secundario) !important; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-weight: 700; font-size: 0.88rem; display: inline-flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-arrow-left"></i> Volver al Panel
            </a>
        </div>
    </div>
</div>

<!-- PASO 1: SELECCIÓN DE DOMINIO DE DATOS -->
<div style="font-size: 0.9rem; font-weight: 800; color: #121a3e; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.6px; display: flex; align-items: center; gap: 8px;">
    <span style="background: var(--color-secundario); color: #ffffff; border-radius: 50%; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.78rem;">1</span>
    Seleccione el Dominio de Datos
</div>

<div class="sa-quick-grid mb-3" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
    
    <a href="?ruta=gestor-reportes&dominio=pst" class="sa-quick-card glass-card" style="border-radius: var(--radius-md); text-decoration: none; border: 2px solid <?= $dominioActual === 'pst' ? 'var(--color-secundario)' : 'rgba(80,89,132,0.15)' ?>; padding: 1.25rem;">
        <div class="sa-quick-icon" style="background: rgba(80, 89, 132, 0.12); color: var(--color-secundario);">
            <i class="ph-bold ph-books"></i>
        </div>
        <div>
            <div style="font-size: 0.8rem; font-weight: 800; color: var(--color-secundario); text-transform: uppercase;">PST Académico</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: #121a3e; margin-top: 2px;"><?= number_format($resumen['pst']) ?></div>
            <div style="font-size: 0.78rem; color: #64748b;">Proyectos Socio-Tecnológicos</div>
        </div>
    </a>

    <a href="?ruta=gestor-reportes&dominio=vinculacion" class="sa-quick-card glass-card" style="border-radius: var(--radius-md); text-decoration: none; border: 2px solid <?= $dominioActual === 'vinculacion' ? 'var(--color-secundario)' : 'rgba(80,89,132,0.15)' ?>; padding: 1.25rem;">
        <div class="sa-quick-icon" style="background: rgba(112, 144, 203, 0.15); color: var(--color-terciario);">
            <i class="ph-bold ph-buildings"></i>
        </div>
        <div>
            <div style="font-size: 0.8rem; font-weight: 800; color: var(--color-terciario); text-transform: uppercase;">Sector Productivo</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: #121a3e; margin-top: 2px;"><?= number_format($resumen['empresas']) ?></div>
            <div style="font-size: 0.78rem; color: #64748b;">Propuestas de Empresas</div>
        </div>
    </a>

    <a href="?ruta=gestor-reportes&dominio=usuarios" class="sa-quick-card glass-card" style="border-radius: var(--radius-md); text-decoration: none; border: 2px solid <?= $dominioActual === 'usuarios' ? 'var(--color-secundario)' : 'rgba(80,89,132,0.15)' ?>; padding: 1.25rem;">
        <div class="sa-quick-icon" style="background: rgba(80, 89, 132, 0.12); color: var(--color-secundario);">
            <i class="ph-bold ph-users-three"></i>
        </div>
        <div>
            <div style="font-size: 0.8rem; font-weight: 800; color: var(--color-secundario); text-transform: uppercase;">Cuentas & Usuarios</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: #121a3e; margin-top: 2px;"><?= number_format($resumen['usuarios']) ?></div>
            <div style="font-size: 0.78rem; color: #64748b;">Matrícula Registrada</div>
        </div>
    </a>

    <a href="?ruta=gestor-reportes&dominio=logs" class="sa-quick-card glass-card" style="border-radius: var(--radius-md); text-decoration: none; border: 2px solid <?= $dominioActual === 'logs' ? 'var(--color-secundario)' : 'rgba(80,89,132,0.15)' ?>; padding: 1.25rem;">
        <div class="sa-quick-icon" style="background: rgba(112, 144, 203, 0.15); color: var(--color-terciario);">
            <i class="ph-bold ph-shield-warning"></i>
        </div>
        <div>
            <div style="font-size: 0.8rem; font-weight: 800; color: var(--color-terciario); text-transform: uppercase;">Auditoría WAF</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: #121a3e; margin-top: 2px;"><?= number_format($resumen['logs']) ?></div>
            <div style="font-size: 0.78rem; color: #64748b;">Eventos de Seguridad</div>
        </div>
    </a>

</div>

<!-- FORMULARIO DE PERSONALIZACIÓN DE PST -->
<form id="agExportConfigForm" action="index.php" method="GET">
    <input type="hidden" name="ruta" value="gestor-reportes">
    <input type="hidden" name="dominio" value="<?= htmlspecialchars($dominioActual) ?>">

    <div style="font-size: 0.9rem; font-weight: 800; color: #121a3e; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.6px; display: flex; align-items: center; gap: 8px;">
        <span style="background: var(--color-secundario); color: #ffffff; border-radius: 50%; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.78rem;">2</span>
        Configuración Granular de Exportación y Gráficos PST
    </div>

    <!-- SELECCIÓN GRANULAR DE COLUMNAS -->
    <?php if ($dominioActual === 'pst'): ?>
    <div style="background: #ffffff; border: 1px solid rgba(80, 89, 132, 0.18); border-radius: var(--radius-md); padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 4px 20px rgba(18, 26, 62, 0.04);">
        <div style="font-size: 0.85rem; font-weight: 800; color: var(--color-secundario); margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.6px; display: flex; align-items: center; gap: 8px;">
            <i class="ph-bold ph-check-square-offset"></i> Seleccione las Columnas a Incluir en el Documento
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
            <?php
            $columnasPST = [
                'titulo' => 'Título del Proyecto',
                'carrera' => 'Carrera / PNF',
                'nivel_academico' => 'Nivel Académico',
                'trayecto' => 'Trayecto Académico',
                'anio_publicacion' => 'Año de Publicación',
                'comunidad_beneficiada' => 'Comunidad Beneficiada',
                'linea_investigacion' => 'Línea de Investigación',
                'fecha_defensa' => 'Fecha de Defensa',
                'obj_general' => 'Objetivo General'
            ];
            $colsSeleccionadas = $filtrosActivos['columnas'] ?? ['titulo', 'carrera', 'nivel_academico', 'trayecto', 'anio_publicacion', 'comunidad_beneficiada', 'linea_investigacion'];
            foreach ($columnasPST as $key => $label):
                $checked = in_array($key, $colsSeleccionadas) ? 'checked' : '';
            ?>
                <label style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: #121a3e; cursor: pointer; background: #f8fafc; padding: 8px 12px; border-radius: 6px; border: 1px solid rgba(80, 89, 132, 0.12);">
                    <input type="checkbox" name="columnas[]" value="<?= $key ?>" <?= $checked ?> style="accent-color: var(--color-secundario); width: 16px; height: 16px;">
                    <span style="font-weight: 600;"><?= htmlspecialchars($label) ?></span>
                </label>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
        
        <!-- COLUMNA 1: FILTROS Y MÉTRICAS DE GRÁFICO -->
        <div style="background: #ffffff; border: 1px solid rgba(80, 89, 132, 0.18); border-radius: var(--radius-md); padding: 1.6rem; box-shadow: 0 4px 20px rgba(18, 26, 62, 0.04);">
            <div style="font-size: 0.85rem; font-weight: 800; color: var(--color-secundario); margin-bottom: 1.2rem; text-transform: uppercase; letter-spacing: 0.6px; display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-funnel"></i> Filtros y Variables del Gráfico
            </div>

            <?php if ($dominioActual === 'pst'): ?>
                <!-- Agrupar métricas en el gráfico -->
                <div style="margin-bottom: 1.2rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 800; color: #121a3e; margin-bottom: 0.4rem;">Medir y Agrupar Gráfico Por</label>
                    <select name="agrupar" class="ag-filter-select" onchange="this.form.submit()" style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid rgba(80, 89, 132, 0.25); background: #ffffff; font-weight: 600; font-size: 0.88rem; color: #121a3e; outline: none;">
                        <option value="trayecto" <?= ($filtrosActivos['agrupar'] ?? 'trayecto') === 'trayecto' ? 'selected' : '' ?>>Trayecto Académico</option>
                        <option value="nivel" <?= ($filtrosActivos['agrupar'] ?? '') === 'nivel' ? 'selected' : '' ?>>Nivel Académico (Pregrado / Maestría)</option>
                        <option value="linea" <?= ($filtrosActivos['agrupar'] ?? '') === 'linea' ? 'selected' : '' ?>>Línea de Investigación</option>
                        <option value="carrera" <?= ($filtrosActivos['agrupar'] ?? '') === 'carrera' ? 'selected' : '' ?>>Carrera / PNF</option>
                        <option value="anio" <?= ($filtrosActivos['agrupar'] ?? '') === 'anio' ? 'selected' : '' ?>>Año de Publicación</option>
                    </select>
                </div>

                <!-- Filtro por Trayecto -->
                <div style="margin-bottom: 1.2rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 800; color: #121a3e; margin-bottom: 0.4rem;">Filtrar por Trayecto Específico</label>
                    <select name="trayecto" class="ag-filter-select" onchange="this.form.submit()" style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid rgba(80, 89, 132, 0.25); background: #ffffff; font-weight: 600; font-size: 0.88rem; color: #121a3e; outline: none;">
                        <option value="">Todos los Trayectos</option>
                        <option value="Trayecto I" <?= ($filtrosActivos['trayecto'] ?? '') === 'Trayecto I' ? 'selected' : '' ?>>Trayecto I</option>
                        <option value="Trayecto II" <?= ($filtrosActivos['trayecto'] ?? '') === 'Trayecto II' ? 'selected' : '' ?>>Trayecto II</option>
                        <option value="Trayecto III" <?= ($filtrosActivos['trayecto'] ?? '') === 'Trayecto III' ? 'selected' : '' ?>>Trayecto III</option>
                        <option value="Trayecto IV" <?= ($filtrosActivos['trayecto'] ?? '') === 'Trayecto IV' ? 'selected' : '' ?>>Trayecto IV</option>
                    </select>
                </div>

                <!-- Filtro por Línea de Investigación -->
                <div style="margin-bottom: 1.2rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 800; color: #121a3e; margin-bottom: 0.4rem;">Filtrar por Línea de Investigación</label>
                    <select name="linea_investigacion" class="ag-filter-select" onchange="this.form.submit()" style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid rgba(80, 89, 132, 0.25); background: #ffffff; font-weight: 600; font-size: 0.88rem; color: #121a3e; outline: none;">
                        <option value="">Todas las Líneas de Investigación</option>
                        <?php foreach ($lineasInvestigacion as $linea): ?>
                            <option value="<?= $linea['id'] ?>" <?= ($filtrosActivos['linea_investigacion'] ?? '') == $linea['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($linea['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <!-- Tipo de Gráfico para PDF -->
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; font-size: 0.82rem; font-weight: 800; color: #121a3e; margin-bottom: 0.4rem;">Tipo de Estilo Visual para el Gráfico</label>
                <select name="tipo_grafico" id="agTipoGraficoSelect" class="ag-filter-select" onchange="actualizarPrevisualizacionGrafico()" style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid rgba(80, 89, 132, 0.25); background: #ffffff; font-weight: 600; font-size: 0.88rem; color: #121a3e; outline: none;">
                    <option value="bar" <?= ($filtrosActivos['tipo_grafico'] ?? 'bar') === 'bar' ? 'selected' : '' ?>>Gráfico de Barras Verticales</option>
                    <option value="doughnut" <?= ($filtrosActivos['tipo_grafico'] ?? '') === 'doughnut' ? 'selected' : '' ?>>Gráfico de Dona / Distribución</option>
                    <option value="line" <?= ($filtrosActivos['tipo_grafico'] ?? '') === 'line' ? 'selected' : '' ?>>Gráfico de Líneas de Tendencia</option>
                    <option value="none" <?= ($filtrosActivos['tipo_grafico'] ?? '') === 'none' ? 'selected' : '' ?>>Sin Gráfico (Solo Documento Tabular)</option>
                </select>
            </div>

            <!-- Previsualización en Vivo de Mayor Altura y Fuentes Grandes -->
            <div style="background: #f8fafc; border: 1px solid rgba(80,89,132,0.12); border-radius: 8px; padding: 1rem; margin-top: 1rem;">
                <div style="font-size: 0.78rem; font-weight: 800; color: var(--color-secundario); margin-bottom: 0.5rem; text-transform: uppercase;">
                    Vista Previa en Tiempo Real del Gráfico
                </div>
                <div style="width: 100%; height: 230px; position: relative;">
                    <canvas id="agPreviewChartCanvas"></canvas>
                </div>
            </div>
        </div>

        <!-- COLUMNA 2: MEMBRETE Y PERSONALIZACIÓN DE PDF -->
        <div style="background: #ffffff; border: 1px solid rgba(80, 89, 132, 0.18); border-radius: var(--radius-md); padding: 1.6rem; box-shadow: 0 4px 20px rgba(18, 26, 62, 0.04);">
            <div style="font-size: 0.85rem; font-weight: 800; color: var(--color-secundario); margin-bottom: 1.2rem; text-transform: uppercase; letter-spacing: 0.6px; display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-pen-nib"></i> Personalización del Documento PDF
            </div>

            <!-- Título del Reporte -->
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; font-size: 0.82rem; font-weight: 800; color: #121a3e; margin-bottom: 0.4rem;">Título Personalizado del Reporte</label>
                <input type="text" name="titulo_custom" placeholder="Ej: Reporte de Producción Socio-Tecnológica (PST)" value="<?= htmlspecialchars($filtrosActivos['titulo_custom'] ?? '') ?>" style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid rgba(80, 89, 132, 0.25); font-size: 0.88rem; color: #121a3e; outline: none;">
                <span style="font-size: 0.75rem; color: #64748b; margin-top: 3px; display: block;">Si se deja vacío, se utilizará el título institucional por defecto.</span>
            </div>

            <!-- Subtítulo o Institución -->
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; font-size: 0.82rem; font-weight: 800; color: #121a3e; margin-bottom: 0.4rem;">Encabezado Secundario / Subtítulo</label>
                <input type="text" name="subtitulo_custom" value="<?= htmlspecialchars($filtrosActivos['subtitulo_custom'] ?? 'Centro de Investigación, Innovación y Desarrollo Integral (CIIDI) - UPTTMBI') ?>" style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid rgba(80, 89, 132, 0.25); font-size: 0.88rem; color: #121a3e; outline: none;">
            </div>

            <!-- Insignias Oficiales -->
            <div style="margin-bottom: 1.2rem; display: flex; align-items: center; gap: 10px;">
                <input type="checkbox" name="mostrar_insignias" id="chkInsignias" value="1" <?= (!isset($filtrosActivos['mostrar_insignias']) || $filtrosActivos['mostrar_insignias']) ? 'checked' : '' ?> style="width: 18px; height: 18px; accent-color: var(--color-secundario); cursor: pointer;">
                <label for="chkInsignias" style="font-size: 0.85rem; font-weight: 700; color: #121a3e; cursor: pointer;">Incluir Membrete e Insignias CIIDI & UPTTMBI</label>
            </div>

            <!-- Notas del Pie -->
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.82rem; font-weight: 800; color: #121a3e; margin-bottom: 0.4rem;">Notas u Observaciones Finales</label>
                <textarea name="notas_pie" rows="3" placeholder="Ej: Reporte extraído para la comisión de evaluación científica." style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid rgba(80, 89, 132, 0.25); font-size: 0.88rem; color: #121a3e; outline: none; resize: vertical;"><?= htmlspecialchars($filtrosActivos['notas_pie'] ?? '') ?></textarea>
            </div>

        </div>

    </div>
</form>

<!-- PASO 3: CENTRO DE EXPORTACIÓN MULTIFORMATO -->
<div style="background: #ffffff; border: 1px solid rgba(80, 89, 132, 0.18); border-radius: var(--radius-md); padding: 1.75rem; text-align: center; box-shadow: 0 4px 20px rgba(18, 26, 62, 0.04);">
    <div style="font-size: 0.9rem; font-weight: 800; color: #121a3e; margin-bottom: 1.2rem; text-transform: uppercase; letter-spacing: 0.6px; display: flex; align-items: center; justify-content: center; gap: 8px;">
        <span style="background: var(--color-secundario); color: #ffffff; border-radius: 50%; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.78rem;">3</span>
        Descargar Documentos e Informes
    </div>

    <div style="display: flex; gap: 1.2rem; justify-content: center; flex-wrap: wrap;">
        <!-- BOTÓN PDF -->
        <button type="button" onclick="ejecutarExportacion('pdf')" class="btn" style="background: var(--color-secundario) !important; color: #ffffff !important; font-weight: 700; padding: 12px 24px; border-radius: 8px; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; border: none; cursor: pointer; box-shadow: 0 4px 14px rgba(80,89,132,0.25);">
            <i class="ph-bold ph-file-pdf" style="font-size: 1.2rem;"></i> Exportar Documento PDF
        </button>

        <!-- BOTÓN CSV -->
        <button type="button" onclick="ejecutarExportacion('csv')" class="btn" style="background: var(--color-terciario) !important; color: #ffffff !important; font-weight: 700; padding: 12px 24px; border-radius: 8px; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; border: none; cursor: pointer; box-shadow: 0 4px 14px rgba(112,144,203,0.25);">
            <i class="ph-bold ph-file-csv" style="font-size: 1.2rem;"></i> Exportar CSV / Excel
        </button>

        <!-- BOTÓN JSON -->
        <button type="button" onclick="ejecutarExportacion('json')" class="btn" style="background: #121a3e !important; color: #ffffff !important; font-weight: 700; padding: 12px 24px; border-radius: 8px; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; border: none; cursor: pointer; box-shadow: 0 4px 14px rgba(18,26,62,0.25);">
            <i class="ph-bold ph-code" style="font-size: 1.2rem;"></i> Exportar JSON
        </button>
    </div>
</div>

<!-- LÓGICA DE RENDERING DE CHART.JS CON FUENTES AMPLIADAS -->
<script>
let previewChartInstance = null;
const statsGrafico = <?= json_encode($graficoStats) ?>;

function renderizarGraficoCanvas() {
    const selectTipo = document.getElementById('agTipoGraficoSelect');
    const tipoVal = selectTipo ? selectTipo.value : 'bar';
    const canvas = document.getElementById('agPreviewChartCanvas');
    if (!canvas || typeof Chart === 'undefined') return;

    const ctx = canvas.getContext('2d');
    if (previewChartInstance) {
        previewChartInstance.destroy();
    }

    if (tipoVal === 'none') {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        return;
    }

    const colorSecundario = 'rgb(80, 89, 132)';
    const bgColors = [
        'rgba(80, 89, 132, 0.85)',
        'rgba(112, 144, 203, 0.85)',
        'rgba(80, 89, 132, 0.65)',
        'rgba(112, 144, 203, 0.65)',
        'rgba(80, 89, 132, 0.45)'
    ];

    previewChartInstance = new Chart(ctx, {
        type: (tipoVal === 'doughnut') ? 'doughnut' : (tipoVal === 'line' ? 'line' : 'bar'),
        data: {
            labels: (statsGrafico.labels && statsGrafico.labels.length > 0) ? statsGrafico.labels : ['Sin datos'],
            datasets: [{
                label: 'Cantidad de Proyectos',
                data: (statsGrafico.valores && statsGrafico.valores.length > 0) ? statsGrafico.valores : [0],
                backgroundColor: tipoVal === 'doughnut' ? bgColors : 'rgba(80, 89, 132, 0.75)',
                borderColor: colorSecundario,
                borderWidth: 1.5,
                fill: tipoVal === 'line' ? false : true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    display: tipoVal === 'doughnut' ? true : false,
                    labels: { font: { size: 13, weight: 'bold' } }
                }
            },
            scales: tipoVal === 'doughnut' ? {} : {
                y: { 
                    beginAtZero: true,
                    ticks: { font: { size: 12, weight: '600' }, color: '#1e293b' } 
                },
                x: { 
                    ticks: { font: { size: 13, weight: 'bold' }, color: '#1e293b' } 
                }
            }
        }
    });
}

function actualizarPrevisualizacionGrafico() {
    renderizarGraficoCanvas();
}

function ejecutarExportacion(tipo) {
    const form = document.getElementById('agExportConfigForm');
    if (!form) return;

    const params = new URLSearchParams(new FormData(form));
    if (tipo === 'pdf') {
        params.set('ruta', 'exportar-reporte-pdf');
        window.open('?' + params.toString(), '_blank');
    } else if (tipo === 'csv') {
        params.set('ruta', 'exportar-reporte-csv');
        window.location.href = '?' + params.toString();
    } else if (tipo === 'json') {
        params.set('ruta', 'exportar-reporte-json');
        window.location.href = '?' + params.toString();
    }
}

// Inicialización
if (document.readyState === 'complete') {
    renderizarGraficoCanvas();
} else {
    window.addEventListener('load', renderizarGraficoCanvas);
}
</script>
