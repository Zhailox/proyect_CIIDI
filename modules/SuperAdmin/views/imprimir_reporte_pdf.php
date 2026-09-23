<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tituloReporte ?? 'Reporte Institucional CIIDI') ?></title>

    <!-- Cargamos Phosphor Icons -->
    <link rel="stylesheet" href="public/assets/css/phosphor-icons.css">
    
    <!-- Carga síncrona en el HEAD con fallback a CDN si la ruta relativa falla -->
    <script src="public/assets/js/chart.min.js"></script>
    <script>
    if (typeof Chart === 'undefined') {
        document.write('<script src="https://cdn.jsdelivr.net/npm/chart.js"><\/script>');
    }
    </script>

    <style>
        :root {
            --color-secundario: rgb(80, 89, 132);
            --color-terciario: rgb(112, 144, 203);
            --texto-titulos: #1E293B;
            --texto-silenciado: #64748B;
            --blanco: #F4F7FB;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            font-size: 11px;
            color: var(--texto-titulos);
            margin: 0;
            padding: 24px;
            background: #ffffff;
        }

        .header {
            border-bottom: 2px solid var(--color-secundario);
            padding-bottom: 16px;
            margin-bottom: 20px;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .badge-institution {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            background: rgba(80, 89, 132, 0.08);
            border: 1px solid rgba(80, 89, 132, 0.25);
            border-radius: 6px;
            font-weight: 800;
            font-size: 10px;
            color: var(--color-secundario);
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .header h1 {
            font-size: 14px;
            margin: 0;
            color: var(--color-secundario);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 800;
            text-align: center;
        }

        .header h2 {
            font-size: 12px;
            margin: 6px 0 0 0;
            color: var(--texto-silenciado);
            font-weight: 700;
            text-align: center;
        }

        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 10px;
            color: var(--texto-silenciado);
            background: var(--blanco);
            padding: 10px 16px;
            border-radius: 8px;
            border: 1px solid rgba(80, 89, 132, 0.15);
        }

        .chart-box {
            background: #ffffff;
            border: 1px solid rgba(80, 89, 132, 0.18);
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 24px;
            text-align: center;
        }

        .chart-box h3 {
            font-size: 11px;
            color: var(--color-secundario);
            margin: 0 0 12px 0;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            font-weight: 800;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid rgba(80, 89, 132, 0.2);
            padding: 8px 11px;
            text-align: left;
            word-wrap: break-word;
        }

        th {
            background-color: var(--color-secundario);
            color: #ffffff;
            font-weight: 800;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tr:nth-child(even) {
            background-color: var(--blanco);
        }

        .notas-box {
            margin-top: 24px;
            padding: 12px 16px;
            background: var(--blanco);
            border: 1px dashed rgba(80, 89, 132, 0.3);
            border-radius: 8px;
            font-size: 10px;
            color: var(--texto-titulos);
        }

        .notas-box strong {
            color: var(--color-secundario);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 4px;
        }

        .footer {
            margin-top: 36px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9px;
            color: var(--texto-silenciado);
            border-top: 1px solid rgba(80, 89, 132, 0.18);
            padding-top: 12px;
        }

        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
            .chart-render-img { max-width: 100% !important; height: auto !important; }
        }
    </style>
</head>
<body>

    <!-- BOTONES DE ACCIÓN DE IMPRESIÓN (OCULTOS EN IMPRESIÓN) -->
    <div class="no-print" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; background: var(--blanco); padding: 12px 20px; border-radius: 10px; border: 1px solid rgba(80, 89, 132, 0.2);">
        <div>
            <strong style="color: var(--color-secundario); font-size: 0.9rem;">Vista Previa del Reporte Oficial CIIDI</strong>
            <div style="font-size: 0.78rem; color: var(--texto-silenciado);">Utilice el botón para enviar a la impresora o guardar como archivo PDF.</div>
        </div>
        <button onclick="window.print()" style="padding: 10px 22px; background: var(--color-secundario); color: #ffffff; border: none; border-radius: 8px; cursor: pointer; font-weight: 800; font-size: 0.88rem; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(80,89,132,0.3);">
            <i class="ph-bold ph-printer"></i> Imprimir / Descargar PDF
        </button>
    </div>

    <!-- ENCABEZADO INSTITUCIONAL -->
    <div class="header">
        <?php if (!empty($mostrarInsignias)): ?>
        <div class="header-top">
            <div class="badge-institution">
                <i class="ph-bold ph-shield-check" style="font-size: 14px;"></i> UPTTMBI — MARIO BRICEÑO IRAGORRY
            </div>
            <div class="badge-institution" style="background: rgba(112, 144, 203, 0.12); color: var(--color-terciario); border-color: rgba(112, 144, 203, 0.3);">
                <i class="ph-bold ph-atom" style="font-size: 14px;"></i> CENTRO CIIDI
            </div>
        </div>
        <?php endif; ?>

        <h1><?= htmlspecialchars($tituloReporte ?? 'REPORTE INSTITUCIONAL DE PRODUCCIÓN SOCIO-TECNOLÓGICA (PST)') ?></h1>
        <h2><?= htmlspecialchars($subtituloReporte ?? 'Centro de Investigación, Innovación y Desarrollo Integral (CIIDI)') ?></h2>
    </div>

    <!-- METADATOS DEL EMISOR -->
    <div class="meta-info">
        <div><strong>Fecha y Hora:</strong> <?= date('d/m/Y H:i:s') ?></div>
        <div><strong>Emisor Responsable:</strong> <?= htmlspecialchars(Auth::usuario()['nombre_completo'] ?? Auth::usuario()['nombre'] ?? 'Administrador') ?> (SuperAdmin)</div>
        <div><strong>Total Registros Evaluados:</strong> <?= count($datos) ?></div>
    </div>

    <!-- SECCIÓN DE GRÁFICO ESTADÍSTICO CON CANVA INVISIBLE Y SOLO UNA IMAGEN PNG -->
    <?php if (($tipoGrafico ?? 'bar') !== 'none' && !empty($estadisticasGrafico['labels'])): ?>
    <div class="chart-box">
        <h3>Analítica Estadística Agrupada</h3>
        <div style="max-width: 540px; margin: 0 auto; min-height: 200px; position: relative;">
            <!-- Canvas oculto únicamente para procesamiento dinámico de Chart.js -->
            <canvas id="pdfChartCanvas" width="540" height="200" style="display: none !important;"></canvas>
            <!-- Imagen única en pantalla e impresión -->
            <img id="pdfChartImg" class="chart-render-img" style="display: block; max-width: 100%; height: auto; margin: 0 auto; border-radius: 6px;" alt="Gráfico Estadístico">
        </div>
    </div>

    <script>
    function renderizarGraficoPDFEInyectarImagen() {
        const canvas = document.getElementById('pdfChartCanvas');
        const img = document.getElementById('pdfChartImg');
        if (!canvas || typeof Chart === 'undefined') return;

        const ctx = canvas.getContext('2d');
        const labels = <?= json_encode($estadisticasGrafico['labels'] ?? []) ?>;
        const data = <?= json_encode($estadisticasGrafico['valores'] ?? []) ?>;
        const chartType = <?= json_encode($tipoGrafico ?? 'bar') ?>;

        const colorSecundario = 'rgb(80, 89, 132)';
        const bgColors = [
            'rgba(80, 89, 132, 0.85)',
            'rgba(112, 144, 203, 0.85)',
            'rgba(80, 89, 132, 0.65)',
            'rgba(112, 144, 203, 0.65)',
            'rgba(80, 89, 132, 0.45)'
        ];

        new Chart(ctx, {
            type: (chartType === 'doughnut' || chartType === 'pie') ? 'doughnut' : (chartType === 'line' ? 'line' : 'bar'),
            data: {
                labels: labels,
                datasets: [{
                    label: 'Registros',
                    data: data,
                    backgroundColor: (chartType === 'doughnut' || chartType === 'pie') ? bgColors : 'rgba(80, 89, 132, 0.75)',
                    borderColor: colorSecundario,
                    borderWidth: 1.5,
                    fill: chartType === 'line' ? false : true
                }]
            },
            options: {
                responsive: false,
                maintainAspectRatio: true,
                animation: false,
                plugins: {
                    legend: { display: (chartType === 'doughnut' || chartType === 'pie') ? true : false }
                },
                scales: (chartType === 'doughnut' || chartType === 'pie') ? {} : {
                    y: { beginAtZero: true }
                }
            }
        });

        // Inmediatamente convertimos el canvas procesado a imagen PNG y lo asignamos al elemento <img>
        try {
            const dataUrl = canvas.toDataURL('image/png');
            if (img && dataUrl) {
                img.src = dataUrl;
            }
        } catch (e) {
            console.error("No se pudo convertir canvas a imagen:", e);
        }
    }

    if (document.readyState === 'complete') {
        renderizarGraficoPDFEInyectarImagen();
    } else {
        window.addEventListener('load', renderizarGraficoPDFEInyectarImagen);
    }
    </script>
    <?php endif; ?>

    <!-- TABLA PRINCIPAL DE DATOS CON COLUMNAS DINÁMICAS Y FALLBACK -->
    <?php
    if (empty($columnasMap)) {
        $columnasMap = [
            'titulo' => 'Título del Proyecto',
            'carrera' => 'Carrera / PNF',
            'nivel_academico' => 'Nivel',
            'trayecto' => 'Trayecto',
            'anio_publicacion' => 'Año',
            'comunidad_beneficiada' => 'Comunidad Beneficiada',
            'linea_investigacion' => 'Línea de Investigación'
        ];
    }
    $headersTabla = array_values($columnasMap);
    ?>

    <table>
        <thead>
            <tr>
                <?php foreach ($headersTabla as $colHeader): ?>
                    <th><?= htmlspecialchars($colHeader) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($datos)): ?>
                <tr>
                    <td colspan="<?= count($headersTabla) ?>" style="text-align: center; padding: 16px; color: var(--texto-silenciado);">
                        No se encontraron registros bajo los criterios seleccionados en el sistema.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($datos as $row): ?>
                    <tr>
                        <?php foreach ($columnasMap as $colKey => $colHeader): ?>
                            <td>
                                <?php
                                $valRow = $row[$colKey] ?? 'N/D';
                                if ($colKey === 'titulo' || $colKey === 'nombre_empresa' || $colKey === 'nombre') {
                                    echo '<strong>' . htmlspecialchars($valRow) . '</strong>';
                                } elseif ($colKey === 'activo') {
                                    echo $valRow ? 'ACTIVO' : 'SUSPENDIDO';
                                } else {
                                    echo htmlspecialchars($valRow);
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- BLOQUE DE OBSERVACIONES / NOTAS -->
    <?php if (!empty($notasPie)): ?>
    <div class="notas-box">
        <strong>Observaciones / Notas de Expedición:</strong>
        <?= nl2br(htmlspecialchars($notasPie)) ?>
    </div>
    <?php endif; ?>

    <!-- PIE DE PÁGINA FORMAL -->
    <div class="footer">
        <div>Sistema Integral CIIDI — Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry"</div>
        <div>Documento Oficial de Evaluación Académica e Investigación</div>
    </div>

</body>
</html>
