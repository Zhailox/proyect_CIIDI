<?php
// modules/LineasInvestigacion/views/reporte_pdf.php
// Expected variables: $tipo, $tituloReporte, $arbol
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tituloReporte) ?></title>
    <style>
        /* Diseño Institucional y Formal (Matriz Plana) */
        body {
            font-family: "Times New Roman", Times, serif;
            color: #000000;
            line-height: 1.4;
            padding: 30px;
            margin: 0 auto;
            max-width: 100%;
            background: #fff;
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 1.6rem;
            text-transform: uppercase;
        }
        
        .header p {
            margin: 0;
            font-size: 1rem;
        }

        table.matrix {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
            page-break-inside: auto;
        }
        
        table.matrix tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        
        table.matrix th, table.matrix td {
            border: 1px solid #000;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }
        
        table.matrix th {
            background-color: #000;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
        }

        @page {
            size: A4 landscape;
            margin: 15mm;
        }

        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
        }

        .btn-print {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #000;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-transform: uppercase;
        }
        .btn-print:hover { background: #333; }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print no-print">Imprimir / Guardar PDF</button>

    <div class="header">
        <h1><?= htmlspecialchars($tituloReporte) ?></h1>
        <p>Centro de Investigación, Innovación y Desarrollo Informático (CIIDI)</p>
        <p>Generado el: <?= date('d/m/Y H:i') ?></p>
    </div>

    <?php
    // Pre-procesar el árbol para convertirlo en filas planas (Flat Matrix)
    $filas = [];
    
    foreach ($arbol as $rama) {
        $carrera_nombre = mb_convert_case($rama['carrera']['nombre'], MB_CASE_UPPER, 'UTF-8');
        
        if (empty($rama['lineas']) && $tipo === 'carreras') {
            $filas[] = [
                'carrera' => $carrera_nombre,
                'linea' => 'SIN LÍNEAS ASIGNADAS',
                'dimension' => '-',
                'desc_linea' => '-'
            ];
        }

        foreach ($rama['lineas'] as $l) {
            $linea_nombre = mb_convert_case($l['nombre'], MB_CASE_UPPER, 'UTF-8');
            $desc_linea = $l['descripcion'];

            if (empty($l['dimensiones'])) {
                // Si no hay dimensiones, igual agregamos la línea (útil para completo y lineas)
                $filas[] = [
                    'carrera' => $carrera_nombre,
                    'linea' => $linea_nombre,
                    'dimension' => 'SIN DIMENSIONES',
                    'desc_linea' => $desc_linea,
                    'desc_dim' => '-'
                ];
            } else {
                foreach ($l['dimensiones'] as $d) {
                    $dim_nombre = mb_convert_case($d['nombre'], MB_CASE_UPPER, 'UTF-8');
                    $filas[] = [
                        'carrera' => $carrera_nombre,
                        'linea' => $linea_nombre,
                        'dimension' => $dim_nombre,
                        'desc_linea' => $desc_linea,
                        'desc_dim' => $d['descripcion']
                    ];
                }
            }
        }
    }
    ?>

    <?php if (empty($filas)): ?>
        <div style="text-align: center; padding: 50px;">
            <h2>No se encontraron registros para los filtros seleccionados.</h2>
        </div>
    <?php else: ?>
        <table class="matrix">
            <thead>
                <tr>
                    <?php if ($tipo === 'completo'): ?>
                        <th style="width: 20%;">Programa (PNF)</th>
                        <th style="width: 25%;">Línea de Investigación</th>
                        <th style="width: 25%;">Dimensión Operativa</th>
                        <th style="width: 30%;">Descripción de la Dimensión</th>
                    
                    <?php elseif ($tipo === 'dimensiones'): ?>
                        <th style="width: 30%;">Dimensión Operativa</th>
                        <th style="width: 25%;">Línea Asociada</th>
                        <th style="width: 20%;">Programa (PNF)</th>
                        <th style="width: 25%;">Descripción</th>
                    
                    <?php elseif ($tipo === 'lineas' || $tipo === 'carreras'): ?>
                        <th style="width: 30%;">Línea de Investigación</th>
                        <th style="width: 25%;">Programa (PNF) Asociado</th>
                        <th style="width: 45%;">Descripción de la Línea</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filas as $f): ?>
                    <tr>
                        <?php if ($tipo === 'completo'): ?>
                            <td><strong><?= htmlspecialchars($f['carrera']) ?></strong></td>
                            <td><?= htmlspecialchars($f['linea']) ?></td>
                            <td><?= htmlspecialchars($f['dimension']) ?></td>
                            <td><?= htmlspecialchars($f['desc_dim']) ?></td>
                        
                        <?php elseif ($tipo === 'dimensiones'): ?>
                            <td><strong><?= htmlspecialchars($f['dimension']) ?></strong></td>
                            <td><?= htmlspecialchars($f['linea']) ?></td>
                            <td><?= htmlspecialchars($f['carrera']) ?></td>
                            <td><?= htmlspecialchars($f['desc_dim']) ?></td>
                        
                        <?php elseif ($tipo === 'lineas' || $tipo === 'carreras'): ?>
                            <?php 
                            // Avoid duplicating lines when iterating through dimensions for "lineas" view
                            // We will use a tracker to only output unique lines
                            static $lineas_impresas = [];
                            $key = $f['carrera'] . '|' . $f['linea'];
                            if (isset($lineas_impresas[$key])) continue;
                            $lineas_impresas[$key] = true;
                            ?>
                            <td><strong><?= htmlspecialchars($f['linea']) ?></strong></td>
                            <td><?= htmlspecialchars($f['carrera']) ?></td>
                            <td><?= htmlspecialchars($f['desc_linea']) ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <script>
        window.onload = function() {
            setTimeout(() => {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
