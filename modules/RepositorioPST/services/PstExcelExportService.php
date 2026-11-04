<?php

/**
 * Servicio de exportación avanzada a Excel nativo (.xlsx OpenXML) para el Catálogo PST.
 * Genera un libro de cálculo con dos hojas:
 *  1. Catálogo de Proyectos: Cinta de KPIs, tabla estilizada con AutoFiltros nativos de Excel,
 *     paneles inmovilizados, ajuste de línea automático y alturas dinámicas.
 *  2. Resumen y Gráficos: Tablas estadísticas ejecutivas y 4 gráficos vectoriales nativos
 *     de Excel (DrawingML) con la paleta azul institucional.
 */
class PstExcelExportService
{
    /**
     * Genera y envía al navegador el archivo .xlsx del catálogo PST.
     */
    public static function descargarExcelCatalogo(array $documentos, array $filtros = [], array $catalogosAux = []): void
    {
        $tmpFile = self::generarArchivoXlsx($documentos, $filtros, $catalogosAux);

        while (ob_get_level()) {
            ob_end_clean();
        }

        $filename = 'Reporte_Catalogo_PST_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($tmpFile));
        header('Cache-Control: max-age=0, no-cache, no-store, must-revalidate');
        header('Pragma: public');

        readfile($tmpFile);
        @unlink($tmpFile);
        exit;
    }

    /**
     * Construye el paquete .xlsx en un archivo temporal y devuelve su ruta.
     */
    public static function generarArchivoXlsx(array $documentos, array $filtros = [], array $catalogosAux = []): string
    {
        $stats = self::calcularEstadisticasExportacion($documentos, $filtros, $catalogosAux);

        $tmpFile = tempnam(sys_get_temp_dir(), 'pst_xlsx_');
        $zip = new ZipArchive();
        if ($zip->open($tmpFile, ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('No fue posible inicializar el archivo Excel temporal.');
        }

        $zip->addFromString('[Content_Types].xml', self::buildContentTypesXml());
        $zip->addFromString('_rels/.rels', self::buildRootRelsXml());
        $zip->addFromString('docProps/app.xml', self::buildAppPropsXml());
        $zip->addFromString('docProps/core.xml', self::buildCorePropsXml());
        $zip->addFromString('xl/workbook.xml', self::buildWorkbookXml());
        $zip->addFromString('xl/_rels/workbook.xml.rels', self::buildWorkbookRelsXml());
        $zip->addFromString('xl/styles.xml', self::buildStylesXml());

        // Hoja 1: Catálogo de Proyectos con AutoFiltros y Paneles Inmovilizados
        $zip->addFromString('xl/worksheets/sheet1.xml', self::buildSheetCatalogoXml($documentos, $stats));

        // Hoja 2: Resumen Ejecutivo y Gráficos Nativos
        $sheet2Data = self::buildSheetResumenXml($stats);
        $zip->addFromString('xl/worksheets/sheet2.xml', $sheet2Data['xml']);
        $zip->addFromString('xl/worksheets/_rels/sheet2.xml.rels', self::buildSheet2RelsXml());

        // Dibujos y Gráficos Nativos en Hoja 2
        $zip->addFromString('xl/drawings/drawing1.xml', self::buildDrawingXml());
        $zip->addFromString('xl/drawings/_rels/drawing1.xml.rels', self::buildDrawingRelsXml());

        $zip->addFromString('xl/charts/chart1.xml', self::buildChartCarrerasXml($stats['por_carrera'], $sheet2Data['ranges']['carrera']));
        $zip->addFromString('xl/charts/chart2.xml', self::buildChartAniosXml($stats['por_anio'], $sheet2Data['ranges']['anio']));
        $zip->addFromString('xl/charts/chart3.xml', self::buildChartFormatosXml($stats['por_formato'], $sheet2Data['ranges']['formato']));
        $zip->addFromString('xl/charts/chart4.xml', self::buildChartLineasXml($stats['por_linea'], $sheet2Data['ranges']['linea']));

        $zip->close();
        return $tmpFile;
    }

    /**
     * Calcula todas las agregaciones estadísticas para los KPIs, tablas de resumen y gráficos.
     */
    private static function calcularEstadisticasExportacion(array $documentos, array $filtros, array $catalogosAux): array
    {
        $total = count($documentos);
        $visibles = 0;
        $ocultos = 0;
        $pdf = 0;
        $word = 0;
        $sinArchivo = 0;
        $conGit = 0;
        $completas = 0;
        $incompletas = 0;
        $totalVistas = 0;

        $porCarrera = [];
        $porAnio = [];
        $porLinea = [];
        $porTrayecto = [];

        foreach ($documentos as $doc) {
            $esActivo = !isset($doc['activo']) || ($doc['activo'] !== false && $doc['activo'] !== 0 && $doc['activo'] !== '0' && $doc['activo'] !== 'f');
            if ($esActivo) {
                $visibles++;
            } else {
                $ocultos++;
            }

            $archivo = strtolower(trim((string)($doc['archivo_pdf'] ?? '')));
            if ($archivo === '') {
                $sinArchivo++;
            } elseif (str_ends_with($archivo, '.pdf')) {
                $pdf++;
            } elseif (str_ends_with($archivo, '.docx') || str_ends_with($archivo, '.doc')) {
                $word++;
            } else {
                $pdf++;
            }

            if (!empty(trim((string)($doc['url_repositorio'] ?? '')))) {
                $conGit++;
            }

            $faltaCom = empty(trim((string)($doc['comunidad_beneficiada'] ?? '')));
            $faltaKw  = empty(trim((string)($doc['palabras_clave'] ?? '')));
            $faltaRes = empty(trim((string)($doc['resumen'] ?? '')));
            if ($faltaCom || $faltaKw || $faltaRes) {
                $incompletas++;
            } else {
                $completas++;
            }

            $vistasDoc = (int)($doc['vistas'] ?? 0);
            $totalVistas += $vistasDoc;

            $carrera = trim((string)($doc['carrera_nombre'] ?? ''));
            if ($carrera === '') {
                $carrera = 'Sin carrera asignada';
            }
            if (!isset($porCarrera[$carrera])) {
                $porCarrera[$carrera] = ['nombre' => $carrera, 'cantidad' => 0, 'vistas' => 0];
            }
            $porCarrera[$carrera]['cantidad']++;
            $porCarrera[$carrera]['vistas'] += $vistasDoc;

            $anio = (string)($doc['anio_publicacion'] ?? 'Sin año');
            if ($anio === '' || $anio === '0') {
                $anio = 'Sin año';
            }
            if (!isset($porAnio[$anio])) {
                $porAnio[$anio] = ['nombre' => $anio, 'cantidad' => 0, 'vistas' => 0];
            }
            $porAnio[$anio]['cantidad']++;
            $porAnio[$anio]['vistas'] += $vistasDoc;

            $linea = trim((string)($doc['linea_nombre'] ?? ''));
            if ($linea === '') {
                $linea = 'Sin línea asignada';
            }
            if (!isset($porLinea[$linea])) {
                $porLinea[$linea] = ['nombre' => $linea, 'cantidad' => 0, 'vistas' => 0];
            }
            $porLinea[$linea]['cantidad']++;
            $porLinea[$linea]['vistas'] += $vistasDoc;

            $trayecto = trim((string)($doc['trayecto'] ?? ''));
            $nivel = trim((string)($doc['nivel_academico'] ?? 'Pregrado'));
            $claveTray = $trayecto !== '' ? $trayecto : $nivel;
            if (!isset($porTrayecto[$claveTray])) {
                $porTrayecto[$claveTray] = ['nombre' => $claveTray, 'cantidad' => 0, 'vistas' => 0];
            }
            $porTrayecto[$claveTray]['cantidad']++;
            $porTrayecto[$claveTray]['vistas'] += $vistasDoc;
        }

        usort($porCarrera, fn($a, $b) => $b['cantidad'] <=> $a['cantidad']);
        ksort($porAnio);
        $porAnioList = array_values($porAnio);
        usort($porLinea, fn($a, $b) => $b['cantidad'] <=> $a['cantidad']);
        $porLineaTop = array_slice($porLinea, 0, 10);

        if (empty($porCarrera)) {
            $porCarrera = [['nombre' => 'Sin registros', 'cantidad' => 0, 'vistas' => 0]];
        }
        if (empty($porAnioList)) {
            $porAnioList = [['nombre' => date('Y'), 'cantidad' => 0, 'vistas' => 0]];
        }
        if (empty($porLineaTop)) {
            $porLineaTop = [['nombre' => 'Sin registros', 'cantidad' => 0, 'vistas' => 0]];
        }

        $porFormato = [
            ['nombre' => 'Formato PDF', 'cantidad' => $pdf],
            ['nombre' => 'Formato Word', 'cantidad' => $word],
            ['nombre' => 'Sin archivo', 'cantidad' => $sinArchivo],
        ];

        // Descripción legible de filtros aplicados
        $partesFiltro = [];
        if (!empty($filtros['q'])) {
            $partesFiltro[] = 'Búsqueda: ' . $filtros['q'];
        }
        if (!empty($filtros['carrera_id']) && !empty($catalogosAux['carrera_nombre'])) {
            $partesFiltro[] = 'Carrera: ' . $catalogosAux['carrera_nombre'];
        }
        if (!empty($filtros['nivel_academico'])) {
            $partesFiltro[] = 'Nivel: ' . $filtros['nivel_academico'];
        }
        if (!empty($filtros['trayecto'])) {
            $partesFiltro[] = 'Trayecto: ' . $filtros['trayecto'];
        }
        if (!empty($filtros['linea_id']) && !empty($catalogosAux['linea_nombre'])) {
            $partesFiltro[] = 'Línea: ' . $catalogosAux['linea_nombre'];
        }
        if (!empty($filtros['dimension_id']) && !empty($catalogosAux['dimension_nombre'])) {
            $partesFiltro[] = 'Dimensión: ' . $catalogosAux['dimension_nombre'];
        }
        if (!empty($filtros['anio'])) {
            $partesFiltro[] = 'Año: ' . $filtros['anio'];
        }
        if (!empty($filtros['estado_filtro'])) {
            $mapaEstados = [
                'activos'      => 'Solo visibles',
                'ocultos'      => 'Solo ocultos',
                'incompletos'  => 'Fichas por completar',
                'con_archivo'  => 'Con documento adjunto',
                'formato_pdf'  => 'Formato PDF',
                'formato_word' => 'Formato Word',
                'con_git'      => 'Con repositorio Git',
                'sin_archivo'  => 'Sin documento adjunto'
            ];
            $partesFiltro[] = 'Estado: ' . ($mapaEstados[$filtros['estado_filtro']] ?? $filtros['estado_filtro']);
        }
        $descripcionFiltros = !empty($partesFiltro) ? implode('  •  ', $partesFiltro) : 'Catálogo general completo sin restricciones';

        return [
            'total'               => $total,
            'visibles'            => $visibles,
            'ocultos'             => $ocultos,
            'pdf'                 => $pdf,
            'word'                => $word,
            'sin_archivo'         => $sinArchivo,
            'con_git'             => $conGit,
            'completas'           => $completas,
            'incompletas'         => $incompletas,
            'total_vistas'        => $totalVistas,
            'por_carrera'         => $porCarrera,
            'por_anio'            => $porAnioList,
            'por_linea'           => $porLineaTop,
            'por_trayecto'        => array_values($porTrayecto),
            'por_formato'         => $porFormato,
            'descripcion_filtros' => $descripcionFiltros
        ];
    }

    /**
     * construye Hoja 1: Catálogo de Proyectos con AutoFiltros nativos y paneles congelados.
     */
    private static function buildSheetCatalogoXml(array $documentos, array $stats): string
    {
        $fechaGen = date('d/m/Y H:i');
        $lastDataRow = max(7, 6 + count($documentos));

        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $xml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">';

        // 1. Vista con Paneles Inmovilizados en la fila 6 (encabezado de tabla)
        $xml .= '<sheetViews>';
        $xml .= '<sheetView tabSelected="1" showGridLines="1" workbookViewId="0">';
        $xml .= '<pane ySplit="6" topLeftCell="A7" activePane="bottomLeft" state="frozen"/>';
        $xml .= '<selection pane="bottomLeft" activeCell="A7" sqref="A7"/>';
        $xml .= '</sheetView>';
        $xml .= '</sheetViews>';

        // 2. Propiedades de formato de hoja
        $xml .= '<sheetFormatPr defaultRowHeight="22"/>';

        // 3. Anchos de columna calibrados para lectura cómoda
        $anchos = [
            1  => 8,   // A: ID
            2  => 50,  // B: Título del Proyecto
            3  => 10,  // C: Año
            4  => 15,  // D: Fecha de Defensa
            5  => 28,  // E: Carrera o PNF
            6  => 18,  // F: Nivel Académico
            7  => 15,  // G: Trayecto
            8  => 34,  // H: Línea de Investigación
            9  => 30,  // I: Dimensión Operativa
            10 => 32,  // J: Autores
            11 => 28,  // K: Tutores
            12 => 28,  // L: Comunidad Beneficiada
            13 => 28,  // M: Palabras Clave
            14 => 44,  // N: Objetivo General
            15 => 16,  // O: Formato Adjunto
            16 => 28,  // P: Repositorio Git
            17 => 14,  // Q: Visibilidad
            18 => 24,  // R: Salud de Ficha
            19 => 12,  // S: Lecturas
        ];
        $xml .= '<cols>';
        foreach ($anchos as $colIdx => $width) {
            $xml .= '<col min="' . $colIdx . '" max="' . $colIdx . '" width="' . $width . '" customWidth="1"/>';
        }
        $xml .= '</cols>';

        // 4. Datos de la hoja
        $xml .= '<sheetData>';

        // Fila 1: Banner Principal
        $xml .= '<row r="1" ht="30" customHeight="1">';
        $xml .= self::inlineCell('A1', 'REPOSITORIO INSTITUCIONAL CIIDI — CATÁLOGO DE PROYECTOS SOCIO-TECNOLÓGICOS', 1);
        for ($c = 2; $c <= 19; $c++) {
            $xml .= self::emptyCell(self::colLetter($c) . '1', 1);
        }
        $xml .= '</row>';

        // Fila 2: Subtítulo con fecha y filtros activos
        $subtitulo = 'Fecha de exportación: ' . $fechaGen . '   |   Filtros activos: ' . $stats['descripcion_filtros'];
        $xml .= '<row r="2" ht="22" customHeight="1">';
        $xml .= self::inlineCell('A2', $subtitulo, 2);
        for ($c = 2; $c <= 19; $c++) {
            $xml .= self::emptyCell(self::colLetter($c) . '2', 2);
        }
        $xml .= '</row>';

        // Fila 3: Separador limpio
        $xml .= '<row r="3" ht="10" customHeight="1"/>';

        // Fila 4: Cinta de KPIs (Etiquetas) y Fila 5 (Valores)
        $kpis = [
            ['colStart' => 1,  'colEnd' => 2,  'label' => 'PROYECTOS EXPORTADOS', 'val' => $stats['total']],
            ['colStart' => 3,  'colEnd' => 4,  'label' => 'VISIBLES AL PÚBLICO',  'val' => $stats['visibles']],
            ['colStart' => 5,  'colEnd' => 6,  'label' => 'OCULTOS',              'val' => $stats['ocultos']],
            ['colStart' => 7,  'colEnd' => 8,  'label' => 'DOCUMENTOS PDF',       'val' => $stats['pdf']],
            ['colStart' => 9,  'colEnd' => 10, 'label' => 'DOCUMENTOS WORD',      'val' => $stats['word']],
            ['colStart' => 11, 'colEnd' => 12, 'label' => 'CON REPOSITORIO GIT',  'val' => $stats['con_git']],
            ['colStart' => 13, 'colEnd' => 14, 'label' => 'FICHAS COMPLETAS',     'val' => $stats['completas']],
            ['colStart' => 15, 'colEnd' => 16, 'label' => 'POR COMPLETAR',        'val' => $stats['incompletas']],
            ['colStart' => 17, 'colEnd' => 19, 'label' => 'LECTURAS ACUMULADAS',  'val' => $stats['total_vistas']],
        ];

        $xml .= '<row r="4" ht="19" customHeight="1">';
        foreach ($kpis as $k) {
            $xml .= self::inlineCell(self::colLetter($k['colStart']) . '4', $k['label'], 3);
            for ($c = $k['colStart'] + 1; $c <= $k['colEnd']; $c++) {
                $xml .= self::emptyCell(self::colLetter($c) . '4', 3);
            }
        }
        $xml .= '</row>';

        $xml .= '<row r="5" ht="25" customHeight="1">';
        foreach ($kpis as $k) {
            $xml .= self::numCell(self::colLetter($k['colStart']) . '5', $k['val'], 4);
            for ($c = $k['colStart'] + 1; $c <= $k['colEnd']; $c++) {
                $xml .= self::emptyCell(self::colLetter($c) . '5', 4);
            }
        }
        $xml .= '</row>';

        // Fila 6: Cabecera de Tabla (con AutoFiltros activados)
        $headers = [
            'ID',
            'Título del Proyecto',
            'Año',
            'Fecha de Defensa',
            'Carrera o PNF',
            'Nivel Académico',
            'Trayecto',
            'Línea de Investigación',
            'Dimensión Operativa',
            'Autores',
            'Tutores',
            'Comunidad Beneficiada',
            'Palabras Clave',
            'Objetivo General',
            'Formato Adjunto',
            'Repositorio Git',
            'Estado',
            'Salud de Ficha',
            'Lecturas'
        ];
        $xml .= '<row r="6" ht="28" customHeight="1">';
        foreach ($headers as $idx => $hText) {
            $colL = self::colLetter($idx + 1);
            $xml .= self::inlineCell($colL . '6', $hText, 5);
        }
        $xml .= '</row>';

        // Filas 7..N: Registros del Catálogo
        if (empty($documentos)) {
            $xml .= '<row r="7" ht="26" customHeight="1">';
            $xml .= self::inlineCell('A7', 'No se encontraron proyectos con los filtros seleccionados.', 6);
            for ($c = 2; $c <= 19; $c++) {
                $xml .= self::emptyCell(self::colLetter($c) . '7', 6);
            }
            $xml .= '</row>';
        } else {
            $rNum = 7;
            foreach ($documentos as $idx => $doc) {
                $esPar = ($idx % 2 === 1);
                $styleText   = $esPar ? 7 : 6;
                $styleCenter = $esPar ? 9 : 8;
                $styleNum    = $esPar ? 11 : 10;

                $archivo = strtolower(trim((string)($doc['archivo_pdf'] ?? '')));
                if ($archivo === '') {
                    $formatoTxt = 'Sin archivo';
                    $styleFormato = 13;
                } elseif (str_ends_with($archivo, '.pdf')) {
                    $formatoTxt = 'PDF';
                    $styleFormato = 12;
                } elseif (str_ends_with($archivo, '.docx') || str_ends_with($archivo, '.doc')) {
                    $formatoTxt = 'WORD';
                    $styleFormato = 12;
                } else {
                    $formatoTxt = strtoupper(pathinfo($archivo, PATHINFO_EXTENSION) ?: 'ARCHIVO');
                    $styleFormato = 12;
                }

                $esVisible = !isset($doc['activo']) || ($doc['activo'] !== false && $doc['activo'] !== 0 && $doc['activo'] !== '0' && $doc['activo'] !== 'f');
                $estadoTxt = $esVisible ? 'Visible' : 'Oculto';
                $styleEstado = $esVisible ? 12 : 13;

                $faltantes = [];
                if (empty(trim((string)($doc['comunidad_beneficiada'] ?? '')))) {
                    $faltantes[] = 'comunidad';
                }
                if (empty(trim((string)($doc['palabras_clave'] ?? '')))) {
                    $faltantes[] = 'palabras clave';
                }
                if (empty(trim((string)($doc['resumen'] ?? '')))) {
                    $faltantes[] = 'resumen';
                }
                $saludTxt = empty($faltantes) ? 'Ficha completa' : ('Falta: ' . implode(', ', $faltantes));
                $styleSalud = empty($faltantes) ? 12 : 13;

                $titulo = trim((string)($doc['titulo'] ?? ''));
                $autores = trim((string)($doc['autores_nombres'] ?? 'No registrados'));
                $tutores = trim((string)($doc['tutores_nombres'] ?? 'No registrados'));
                $linea = trim((string)($doc['linea_nombre'] ?? 'Sin línea'));
                $dimension = trim((string)($doc['dimension_nombre'] ?? 'Sin dimensión'));
                $comunidad = trim((string)($doc['comunidad_beneficiada'] ?? 'No registrada'));
                $keywords = trim((string)($doc['palabras_clave'] ?? ''));
                $objGeneral = trim((string)($doc['obj_general'] ?? ''));

                // Cálculo dinámico de altura de fila según longitud de textos envueltos
                $maxLines = max(
                    1,
                    (int)ceil(mb_strlen($titulo, 'UTF-8') / 44),
                    (int)ceil(mb_strlen($autores, 'UTF-8') / 28),
                    (int)ceil(mb_strlen($tutores, 'UTF-8') / 25),
                    (int)ceil(mb_strlen($linea, 'UTF-8') / 30),
                    (int)ceil(mb_strlen($objGeneral, 'UTF-8') / 40)
                );
                $rowHeight = min(115, max(24, $maxLines * 15 + 8));

                $xml .= '<row r="' . $rNum . '" ht="' . $rowHeight . '" customHeight="1">';
                $xml .= self::numCell('A' . $rNum, (int)($doc['id'] ?? 0), $styleCenter);
                $xml .= self::inlineCell('B' . $rNum, $titulo, $styleText);
                $xml .= self::numCell('C' . $rNum, (int)($doc['anio_publicacion'] ?? date('Y')), $styleCenter);
                $xml .= self::inlineCell('D' . $rNum, (string)($doc['fecha_defensa'] ?? ''), $styleCenter);
                $xml .= self::inlineCell('E' . $rNum, (string)($doc['carrera_nombre'] ?? ''), $styleText);
                $xml .= self::inlineCell('F' . $rNum, (string)($doc['nivel_academico'] ?? 'Pregrado'), $styleCenter);
                $xml .= self::inlineCell('G' . $rNum, (string)($doc['trayecto'] ?? ''), $styleCenter);
                $xml .= self::inlineCell('H' . $rNum, $linea, $styleText);
                $xml .= self::inlineCell('I' . $rNum, $dimension, $styleText);
                $xml .= self::inlineCell('J' . $rNum, $autores, $styleText);
                $xml .= self::inlineCell('K' . $rNum, $tutores, $styleText);
                $xml .= self::inlineCell('L' . $rNum, $comunidad, $styleText);
                $xml .= self::inlineCell('M' . $rNum, $keywords, $styleText);
                $xml .= self::inlineCell('N' . $rNum, $objGeneral, $styleText);
                $xml .= self::inlineCell('O' . $rNum, $formatoTxt, $styleFormato);
                $xml .= self::inlineCell('P' . $rNum, (string)($doc['url_repositorio'] ?? ''), $styleText);
                $xml .= self::inlineCell('Q' . $rNum, $estadoTxt, $styleEstado);
                $xml .= self::inlineCell('R' . $rNum, $saludTxt, $styleSalud);
                $xml .= self::numCell('S' . $rNum, (int)($doc['vistas'] ?? 0), $styleNum);
                $xml .= '</row>';

                $rNum++;
            }
        }

        $xml .= '</sheetData>';

        // 5. AutoFiltro Nativo de Excel en todas las columnas de la tabla (A6:S{lastDataRow})
        $xml .= '<autoFilter ref="A6:S' . $lastDataRow . '"/>';

        // 6. Celdas Combinadas del Banner y Cinta de KPIs (filas 1, 2, 4 y 5; fuera del rango de AutoFiltro)
        $merges = [
            'A1:S1',
            'A2:S2',
        ];
        foreach ($kpis as $k) {
            $c1 = self::colLetter($k['colStart']);
            $c2 = self::colLetter($k['colEnd']);
            $merges[] = "{$c1}4:{$c2}4";
            $merges[] = "{$c1}5:{$c2}5";
        }
        $xml .= '<mergeCells count="' . count($merges) . '">';
        foreach ($merges as $mRef) {
            $xml .= '<mergeCell ref="' . $mRef . '"/>';
        }
        $xml .= '</mergeCells>';

        // 7. Configuración de página horizontal
        $xml .= '<pageMargins left="0.4" right="0.4" top="0.5" bottom="0.5" header="0.3" footer="0.3"/>';
        $xml .= '<pageSetup orientation="landscape" paperSize="9" fitToWidth="1" fitToHeight="0"/>';
        $xml .= '</worksheet>';

        return $xml;
    }

    /**
     * Construye Hoja 2: Resumen Ejecutivo y Tablas Estadísticas + Anclaje a 4 Gráficos Nativos.
     */
    private static function buildSheetResumenXml(array $stats): array
    {
        $totalBase = max(1, (int)$stats['total']);

        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $xml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">';
        $xml .= '<sheetViews><sheetView showGridLines="1" workbookViewId="0"/></sheetViews>';
        $xml .= '<sheetFormatPr defaultRowHeight="21"/>';

        $xml .= '<cols>';
        $xml .= '<col min="1" max="1" width="38" customWidth="1"/>'; // A: Categoría / Indicador
        $xml .= '<col min="2" max="2" width="14" customWidth="1"/>'; // B: Proyectos
        $xml .= '<col min="3" max="3" width="14" customWidth="1"/>'; // C: Porcentaje
        $xml .= '<col min="4" max="4" width="14" customWidth="1"/>'; // D: Lecturas
        $xml .= '<col min="5" max="5" width="22" customWidth="1"/>'; // E: Barra Visual
        $xml .= '<col min="6" max="6" width="4" customWidth="1"/>';  // F: Separador hacia área de gráficos
        $xml .= '<col min="7" max="16" width="11" customWidth="1"/>'; // G..P: Lienzo de Gráficos Nativos
        $xml .= '</cols>';

        $xml .= '<sheetData>';

        // Banner superior
        $xml .= '<row r="1" ht="30" customHeight="1">';
        $xml .= self::inlineCell('A1', 'RESUMEN EJECUTIVO Y ESTADÍSTICAS DEL CATÁLOGO PST', 1);
        for ($c = 2; $c <= 16; $c++) {
            $xml .= self::emptyCell(self::colLetter($c) . '1', 1);
        }
        $xml .= '</row>';

        $xml .= '<row r="2" ht="22" customHeight="1">';
        $xml .= self::inlineCell('A2', 'Análisis consolidado sobre ' . $stats['total'] . ' proyectos exportados   |   ' . $stats['descripcion_filtros'], 2);
        for ($c = 2; $c <= 16; $c++) {
            $xml .= self::emptyCell(self::colLetter($c) . '2', 2);
        }
        $xml .= '</row>';

        $merges = ['A1:P1', 'A2:P2'];
        $r = 4;

        // =========================================================================
        // TABLA 1: DISTRIBUCIÓN POR CARRERA O PNF
        // =========================================================================
        $xml .= '<row r="' . $r . '" ht="24" customHeight="1">';
        $xml .= self::inlineCell("A{$r}", '1. DISTRIBUCIÓN DE PROYECTOS POR CARRERA O PNF', 3);
        for ($c = 2; $c <= 5; $c++) {
            $xml .= self::emptyCell(self::colLetter($c) . $r, 3);
        }
        $merges[] = "A{$r}:E{$r}";
        $xml .= '</row>';
        $r++;

        $xml .= '<row r="' . $r . '" ht="24" customHeight="1">';
        $xml .= self::inlineCell("A{$r}", 'Carrera o Programa Nacional de Formación', 5);
        $xml .= self::inlineCell("B{$r}", 'Proyectos', 5);
        $xml .= self::inlineCell("C{$r}", 'Porcentaje', 5);
        $xml .= self::inlineCell("D{$r}", 'Lecturas', 5);
        $xml .= self::inlineCell("E{$r}", 'Participación Visual', 5);
        $xml .= '</row>';
        $r++;

        $carreraStartRow = $r;
        foreach ($stats['por_carrera'] as $idx => $item) {
            $esPar = ($idx % 2 === 1);
            $pct = round($item['cantidad'] / $totalBase, 4);
            $barra = self::buildProgressBar($pct);
            $xml .= '<row r="' . $r . '" ht="22" customHeight="1">';
            $xml .= self::inlineCell("A{$r}", $item['nombre'], $esPar ? 7 : 6);
            $xml .= self::numCell("B{$r}", $item['cantidad'], $esPar ? 9 : 8);
            $xml .= self::numCell("C{$r}", $pct, $esPar ? 15 : 14);
            $xml .= self::numCell("D{$r}", $item['vistas'], $esPar ? 11 : 10);
            $xml .= self::inlineCell("E{$r}", $barra, 12);
            $xml .= '</row>';
            $r++;
        }
        $carreraEndRow = $r - 1;

        // Fila Total Carrera
        $xml .= '<row r="' . $r . '" ht="23" customHeight="1">';
        $xml .= self::inlineCell("A{$r}", 'TOTAL GENERAL', 5);
        $xml .= self::numCell("B{$r}", $stats['total'], 5);
        $xml .= self::numCell("C{$r}", $stats['total'] > 0 ? 1 : 0, 14);
        $xml .= self::numCell("D{$r}", $stats['total_vistas'], 5);
        $xml .= self::inlineCell("E{$r}", '██████████ 100%', 5);
        $xml .= '</row>';
        $r += 2;

        // =========================================================================
        // TABLA 2: EVOLUCIÓN POR AÑO DE PUBLICACIÓN
        // =========================================================================
        $xml .= '<row r="' . $r . '" ht="24" customHeight="1">';
        $xml .= self::inlineCell("A{$r}", '2. EVOLUCIÓN DE PROYECTOS POR AÑO DE PUBLICACIÓN', 3);
        for ($c = 2; $c <= 5; $c++) {
            $xml .= self::emptyCell(self::colLetter($c) . $r, 3);
        }
        $merges[] = "A{$r}:E{$r}";
        $xml .= '</row>';
        $r++;

        $xml .= '<row r="' . $r . '" ht="24" customHeight="1">';
        $xml .= self::inlineCell("A{$r}", 'Año de Publicación', 5);
        $xml .= self::inlineCell("B{$r}", 'Proyectos', 5);
        $xml .= self::inlineCell("C{$r}", 'Porcentaje', 5);
        $xml .= self::inlineCell("D{$r}", 'Lecturas', 5);
        $xml .= self::inlineCell("E{$r}", 'Participación Visual', 5);
        $xml .= '</row>';
        $r++;

        $anioStartRow = $r;
        foreach ($stats['por_anio'] as $idx => $item) {
            $esPar = ($idx % 2 === 1);
            $pct = round($item['cantidad'] / $totalBase, 4);
            $barra = self::buildProgressBar($pct);
            $xml .= '<row r="' . $r . '" ht="22" customHeight="1">';
            $xml .= self::inlineCell("A{$r}", 'Año ' . $item['nombre'], $esPar ? 9 : 8);
            $xml .= self::numCell("B{$r}", $item['cantidad'], $esPar ? 9 : 8);
            $xml .= self::numCell("C{$r}", $pct, $esPar ? 15 : 14);
            $xml .= self::numCell("D{$r}", $item['vistas'], $esPar ? 11 : 10);
            $xml .= self::inlineCell("E{$r}", $barra, 12);
            $xml .= '</row>';
            $r++;
        }
        $anioEndRow = $r - 1;
        $r += 2;

        // =========================================================================
        // TABLA 3: FORMATOS DIGITALES Y SALUD DEL CATÁLOGO
        // =========================================================================
        $xml .= '<row r="' . $r . '" ht="24" customHeight="1">';
        $xml .= self::inlineCell("A{$r}", '3. FORMATOS DIGITALES Y SALUD DOCUMENTAL', 3);
        for ($c = 2; $c <= 5; $c++) {
            $xml .= self::emptyCell(self::colLetter($c) . $r, 3);
        }
        $merges[] = "A{$r}:E{$r}";
        $xml .= '</row>';
        $r++;

        $xml .= '<row r="' . $r . '" ht="24" customHeight="1">';
        $xml .= self::inlineCell("A{$r}", 'Indicador del Catálogo', 5);
        $xml .= self::inlineCell("B{$r}", 'Cantidad', 5);
        $xml .= self::inlineCell("C{$r}", 'Porcentaje', 5);
        $xml .= self::inlineCell("D{$r}", 'Clasificación', 5);
        $xml .= self::inlineCell("E{$r}", 'Cobertura Visual', 5);
        $xml .= '</row>';
        $r++;

        // Las primeras 3 filas de esta tabla alimentan el Gráfico 3 (Anillo de Formatos)
        $formatoStartRow = $r;
        $filasSalud = [
            ['nombre' => 'Formato PDF',                  'cant' => $stats['pdf'],         'tipo' => 'Archivo Digital'],
            ['nombre' => 'Formato Word',                 'cant' => $stats['word'],        'tipo' => 'Archivo Digital'],
            ['nombre' => 'Sin archivo',                  'cant' => $stats['sin_archivo'], 'tipo' => 'Archivo Digital'],
            ['nombre' => 'Con Repositorio Git',          'cant' => $stats['con_git'],     'tipo' => 'Código Fuente'],
            ['nombre' => 'Proyectos Visibles al Público','cant' => $stats['visibles'],    'tipo' => 'Visibilidad'],
            ['nombre' => 'Proyectos Ocultos',            'cant' => $stats['ocultos'],     'tipo' => 'Visibilidad'],
            ['nombre' => 'Fichas Documentales Completas','cant' => $stats['completas'],   'tipo' => 'Calidad de Ficha'],
            ['nombre' => 'Fichas por Completar',         'cant' => $stats['incompletas'], 'tipo' => 'Calidad de Ficha'],
        ];
        foreach ($filasSalud as $idx => $fItem) {
            $esPar = ($idx % 2 === 1);
            $pct = round($fItem['cant'] / $totalBase, 4);
            $barra = self::buildProgressBar($pct);
            $xml .= '<row r="' . $r . '" ht="22" customHeight="1">';
            $xml .= self::inlineCell("A{$r}", $fItem['nombre'], $esPar ? 7 : 6);
            $xml .= self::numCell("B{$r}", $fItem['cant'], $esPar ? 9 : 8);
            $xml .= self::numCell("C{$r}", $pct, $esPar ? 15 : 14);
            $xml .= self::inlineCell("D{$r}", $fItem['tipo'], $esPar ? 9 : 8);
            $xml .= self::inlineCell("E{$r}", $barra, 12);
            $xml .= '</row>';
            $r++;
        }
        $formatoEndRow = $formatoStartRow + 2; // PDF, Word, Sin archivo
        $r += 2;

        // =========================================================================
        // TABLA 4: PRINCIPALES LÍNEAS DE INVESTIGACIÓN
        // =========================================================================
        $xml .= '<row r="' . $r . '" ht="24" customHeight="1">';
        $xml .= self::inlineCell("A{$r}", '4. PRINCIPALES LÍNEAS DE INVESTIGACIÓN', 3);
        for ($c = 2; $c <= 5; $c++) {
            $xml .= self::emptyCell(self::colLetter($c) . $r, 3);
        }
        $merges[] = "A{$r}:E{$r}";
        $xml .= '</row>';
        $r++;

        $xml .= '<row r="' . $r . '" ht="24" customHeight="1">';
        $xml .= self::inlineCell("A{$r}", 'Línea de Investigación', 5);
        $xml .= self::inlineCell("B{$r}", 'Proyectos', 5);
        $xml .= self::inlineCell("C{$r}", 'Porcentaje', 5);
        $xml .= self::inlineCell("D{$r}", 'Lecturas', 5);
        $xml .= self::inlineCell("E{$r}", 'Participación Visual', 5);
        $xml .= '</row>';
        $r++;

        $lineaStartRow = $r;
        foreach ($stats['por_linea'] as $idx => $item) {
            $esPar = ($idx % 2 === 1);
            $pct = round($item['cantidad'] / $totalBase, 4);
            $barra = self::buildProgressBar($pct);
            $xml .= '<row r="' . $r . '" ht="24" customHeight="1">';
            $xml .= self::inlineCell("A{$r}", $item['nombre'], $esPar ? 7 : 6);
            $xml .= self::numCell("B{$r}", $item['cantidad'], $esPar ? 9 : 8);
            $xml .= self::numCell("C{$r}", $pct, $esPar ? 15 : 14);
            $xml .= self::numCell("D{$r}", $item['vistas'], $esPar ? 11 : 10);
            $xml .= self::inlineCell("E{$r}", $barra, 12);
            $xml .= '</row>';
            $r++;
        }
        $lineaEndRow = $r - 1;

        $xml .= '</sheetData>';

        $xml .= '<mergeCells count="' . count($merges) . '">';
        foreach ($merges as $mRef) {
            $xml .= '<mergeCell ref="' . $mRef . '"/>';
        }
        $xml .= '</mergeCells>';

        $xml .= '<pageMargins left="0.4" right="0.4" top="0.5" bottom="0.5" header="0.3" footer="0.3"/>';
        $xml .= '<pageSetup orientation="landscape" paperSize="9" fitToWidth="1" fitToHeight="0"/>';
        $xml .= '<drawing r:id="rId1"/>';
        $xml .= '</worksheet>';

        return [
            'xml' => $xml,
            'ranges' => [
                'carrera' => ['start' => $carreraStartRow, 'end' => $carreraEndRow],
                'anio'    => ['start' => $anioStartRow,    'end' => $anioEndRow],
                'formato' => ['start' => $formatoStartRow, 'end' => $formatoEndRow],
                'linea'   => ['start' => $lineaStartRow,   'end' => $lineaEndRow],
            ]
        ];
    }

    /**
     * Genera barra de progreso visual con bloques Unicode y porcentaje.
     */
    private static function buildProgressBar(float $ratio): string
    {
        $pct = max(0, min(100, (int)round($ratio * 100)));
        $filled = (int)round($pct / 10);
        $empty = 10 - $filled;
        return str_repeat('█', $filled) . str_repeat('░', $empty) . ' ' . $pct . '%';
    }

    /**
     * Construye el archivo xl/drawings/drawing1.xml que posiciona los 4 gráficos en la Hoja 2.
     */
    private static function buildDrawingXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<xdr:wsDr xmlns:xdr="http://schemas.openxmlformats.org/drawingml/2006/spreadsheetDrawing" xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            // Gráfico 1: Proyectos por Carrera (Cols G a P, Filas 4 a 18)
            . self::buildTwoCellAnchor(2, 'Gráfico Carreras', 6, 3, 16, 18, 'rId1')
            // Gráfico 2: Proyectos por Año (Cols G a K, Filas 20 a 34)
            . self::buildTwoCellAnchor(3, 'Gráfico Años', 6, 19, 11, 34, 'rId2')
            // Gráfico 3: Distribución por Formato (Cols L a P, Filas 20 a 34)
            . self::buildTwoCellAnchor(4, 'Gráfico Formatos', 11, 19, 16, 34, 'rId3')
            // Gráfico 4: Top Líneas de Investigación (Cols G a P, Filas 36 a 52)
            . self::buildTwoCellAnchor(5, 'Gráfico Líneas', 6, 35, 16, 52, 'rId4')
            . '</xdr:wsDr>';
    }

    private static function buildTwoCellAnchor(int $id, string $name, int $fromCol, int $fromRow, int $toCol, int $toRow, string $rId): string
    {
        return '<xdr:twoCellAnchor>'
            . '<xdr:from><xdr:col>' . $fromCol . '</xdr:col><xdr:colOff>0</xdr:colOff><xdr:row>' . $fromRow . '</xdr:row><xdr:rowOff>0</xdr:rowOff></xdr:from>'
            . '<xdr:to><xdr:col>' . $toCol . '</xdr:col><xdr:colOff>0</xdr:colOff><xdr:row>' . $toRow . '</xdr:row><xdr:rowOff>0</xdr:rowOff></xdr:to>'
            . '<xdr:graphicFrame macro="">'
            . '<xdr:nvGraphicFramePr>'
            . '<xdr:cNvPr id="' . $id . '" name="' . self::esc($name) . '"/>'
            . '<xdr:cNvGraphicFramePr/>'
            . '</xdr:nvGraphicFramePr>'
            . '<xdr:xfrm><a:off x="0" y="0"/><a:ext cx="0" cy="0"/></xdr:xfrm>'
            . '<a:graphic>'
            . '<a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/chart">'
            . '<c:chart xmlns:c="http://schemas.openxmlformats.org/drawingml/2006/chart" r:id="' . $rId . '"/>'
            . '</a:graphicData>'
            . '</a:graphic>'
            . '</xdr:graphicFrame>'
            . '<xdr:clientData/>'
            . '</xdr:twoCellAnchor>';
    }

    /**
     * Gráfico 1: Columnas verticales de Proyectos por Carrera / PNF.
     */
    private static function buildChartCarrerasXml(array $datos, array $range): string
    {
        $catFormula = "'Resumen y Graficos'!\$A\$" . $range['start'] . ":\$A\$" . $range['end'];
        $valFormula = "'Resumen y Graficos'!\$B\$" . $range['start'] . ":\$B\$" . $range['end'];
        return self::buildBarOrColChartXml(
            'Proyectos por Carrera o PNF',
            'col',
            '505984',
            $datos,
            $catFormula,
            $valFormula
        );
    }

    /**
     * Gráfico 2: Columnas verticales de Evolución por Año.
     */
    private static function buildChartAniosXml(array $datos, array $range): string
    {
        $datosAnio = array_map(fn($d) => ['nombre' => 'Año ' . $d['nombre'], 'cantidad' => $d['cantidad']], $datos);
        $catFormula = "'Resumen y Graficos'!\$A\$" . $range['start'] . ":\$A\$" . $range['end'];
        $valFormula = "'Resumen y Graficos'!\$B\$" . $range['start'] . ":\$B\$" . $range['end'];
        return self::buildBarOrColChartXml(
            'Proyectos por Año de Publicación',
            'col',
            '7090CB',
            $datosAnio,
            $catFormula,
            $valFormula
        );
    }

    /**
     * Gráfico 4: Barras horizontales para Top Líneas de Investigación.
     */
    private static function buildChartLineasXml(array $datos, array $range): string
    {
        $catFormula = "'Resumen y Graficos'!\$A\$" . $range['start'] . ":\$A\$" . $range['end'];
        $valFormula = "'Resumen y Graficos'!\$B\$" . $range['start'] . ":\$B\$" . $range['end'];
        return self::buildBarOrColChartXml(
            'Principales Líneas de Investigación',
            'bar',
            '121A3E',
            $datos,
            $catFormula,
            $valFormula
        );
    }

    /**
     * Plantilla OpenXML DrawingML para gráfico de columnas o barras horizontales.
     */
    private static function buildBarOrColChartXml(
        string $titulo,
        string $barDir,
        string $hexColor,
        array $datos,
        string $catFormula,
        string $valFormula
    ): string {
        $count = count($datos);
        $strPts = '';
        $numPts = '';
        foreach ($datos as $i => $d) {
            $strPts .= '<c:pt idx="' . $i . '"><c:v>' . self::esc((string)$d['nombre']) . '</c:v></c:pt>';
            $numPts .= '<c:pt idx="' . $i . '"><c:v>' . (int)$d['cantidad'] . '</c:v></c:pt>';
        }

        $catPos = $barDir === 'bar' ? 'l' : 'b';
        $valPos = $barDir === 'bar' ? 'b' : 'l';

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<c:chartSpace xmlns:c="http://schemas.openxmlformats.org/drawingml/2006/chart" xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<c:roundedCorners val="1"/>'
            . '<c:chart>'
            . '<c:title>'
            . '<c:tx><c:rich><a:bodyPr/><a:lstStyle/><a:p><a:pPr><a:defRPr sz="1100" b="1"><a:solidFill><a:srgbClr val="121A3E"/></a:solidFill></a:defRPr></a:pPr><a:r><a:rPr lang="es-VE" sz="1100" b="1"><a:solidFill><a:srgbClr val="121A3E"/></a:solidFill></a:rPr><a:t>' . self::esc($titulo) . '</a:t></a:r></a:p></c:rich></c:tx>'
            . '<c:overlay val="0"/>'
            . '</c:title>'
            . '<c:autoTitleDeleted val="0"/>'
            . '<c:plotArea>'
            . '<c:layout/>'
            . '<c:barChart>'
            . '<c:barDir val="' . $barDir . '"/>'
            . '<c:grouping val="clustered"/>'
            . '<c:varyColors val="0"/>'
            . '<c:ser>'
            . '<c:idx val="0"/>'
            . '<c:order val="0"/>'
            . '<c:tx><c:v>Proyectos</c:v></c:tx>'
            . '<c:spPr><a:solidFill><a:srgbClr val="' . $hexColor . '"/></a:solidFill><a:ln w="9525"><a:solidFill><a:srgbClr val="121A3E"/></a:solidFill></a:ln></c:spPr>'
            . '<c:cat><c:strRef><c:f>' . $catFormula . '</c:f><c:strCache><c:ptCount val="' . $count . '"/>' . $strPts . '</c:strCache></c:strRef></c:cat>'
            . '<c:val><c:numRef><c:f>' . $valFormula . '</c:f><c:numCache><c:formatCode>General</c:formatCode><c:ptCount val="' . $count . '"/>' . $numPts . '</c:numCache></c:numRef></c:val>'
            . '</c:ser>'
            . '<c:dLbls>'
            . '<c:showLegendKey val="0"/><c:showVal val="1"/><c:showCatName val="0"/><c:showSerName val="0"/><c:showPercent val="0"/><c:showBubbleSize val="0"/>'
            . '</c:dLbls>'
            . '<c:gapWidth val="120"/>'
            . '<c:axId val="1001"/>'
            . '<c:axId val="1002"/>'
            . '</c:barChart>'
            . '<c:catAx>'
            . '<c:axId val="1001"/>'
            . '<c:scaling><c:orientation val="minMax"/></c:scaling>'
            . '<c:delete val="0"/>'
            . '<c:axPos val="' . $catPos . '"/>'
            . '<c:numFmt formatCode="General" sourceLinked="1"/>'
            . '<c:majorTickMark val="none"/>'
            . '<c:minorTickMark val="none"/>'
            . '<c:tickLblPos val="nextTo"/>'
            . '<c:crossAx val="1002"/>'
            . '<c:crosses val="autoZero"/>'
            . '<c:auto val="1"/>'
            . '<c:lblAlgn val="ctr"/>'
            . '<c:lblOffset val="100"/>'
            . '</c:catAx>'
            . '<c:valAx>'
            . '<c:axId val="1002"/>'
            . '<c:scaling><c:orientation val="minMax"/></c:scaling>'
            . '<c:delete val="0"/>'
            . '<c:axPos val="' . $valPos . '"/>'
            . '<c:majorGridlines><c:spPr><a:ln w="9525"><a:solidFill><a:srgbClr val="E2E8F0"/></a:solidFill></a:ln></c:spPr></c:majorGridlines>'
            . '<c:numFmt formatCode="#,##0" sourceLinked="0"/>'
            . '<c:majorTickMark val="none"/>'
            . '<c:minorTickMark val="none"/>'
            . '<c:tickLblPos val="nextTo"/>'
            . '<c:crossAx val="1001"/>'
            . '<c:crosses val="autoZero"/>'
            . '<c:crossBetween val="between"/>'
            . '</c:valAx>'
            . '<c:spPr><a:solidFill><a:srgbClr val="FFFFFF"/></a:solidFill></c:spPr>'
            . '</c:plotArea>'
            . '<c:plotVisOnly val="1"/>'
            . '<c:dispBlanksAs val="gap"/>'
            . '</c:chart>'
            . '<c:spPr><a:solidFill><a:srgbClr val="FFFFFF"/></a:solidFill><a:ln w="12700"><a:solidFill><a:srgbClr val="CBD5E1"/></a:solidFill></a:ln></c:spPr>'
            . '</c:chartSpace>';
    }

    /**
     * Gráfico 3: Anillo (Doughnut) para Distribución de Formatos Digitales en paleta azul institucional.
     */
    private static function buildChartFormatosXml(array $datos, array $range): string
    {
        $catFormula = "'Resumen y Graficos'!\$A\$" . $range['start'] . ":\$A\$" . $range['end'];
        $valFormula = "'Resumen y Graficos'!\$B\$" . $range['start'] . ":\$B\$" . $range['end'];
        $count = count($datos);
        $coloresAzul = ['505984', '7090CB', '121A3E'];

        $strPts = '';
        $numPts = '';
        $dPts = '';
        foreach ($datos as $i => $d) {
            $strPts .= '<c:pt idx="' . $i . '"><c:v>' . self::esc((string)$d['nombre']) . '</c:v></c:pt>';
            $numPts .= '<c:pt idx="' . $i . '"><c:v>' . (int)$d['cantidad'] . '</c:v></c:pt>';
            $clr = $coloresAzul[$i % count($coloresAzul)];
            $dPts .= '<c:dPt><c:idx val="' . $i . '"/><c:bubble3D val="0"/><c:spPr><a:solidFill><a:srgbClr val="' . $clr . '"/></a:solidFill></c:spPr></c:dPt>';
        }

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<c:chartSpace xmlns:c="http://schemas.openxmlformats.org/drawingml/2006/chart" xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<c:roundedCorners val="1"/>'
            . '<c:chart>'
            . '<c:title>'
            . '<c:tx><c:rich><a:bodyPr/><a:lstStyle/><a:p><a:pPr><a:defRPr sz="1100" b="1"><a:solidFill><a:srgbClr val="121A3E"/></a:solidFill></a:defRPr></a:pPr><a:r><a:rPr lang="es-VE" sz="1100" b="1"><a:solidFill><a:srgbClr val="121A3E"/></a:solidFill></a:rPr><a:t>Distribución por Formato Digital</a:t></a:r></a:p></c:rich></c:tx>'
            . '<c:overlay val="0"/>'
            . '</c:title>'
            . '<c:autoTitleDeleted val="0"/>'
            . '<c:plotArea>'
            . '<c:layout/>'
            . '<c:doughnutChart>'
            . '<c:varyColors val="1"/>'
            . '<c:ser>'
            . '<c:idx val="0"/>'
            . '<c:order val="0"/>'
            . '<c:tx><c:v>Formatos</c:v></c:tx>'
            . $dPts
            . '<c:dLbls>'
            . '<c:showLegendKey val="0"/><c:showVal val="1"/><c:showCatName val="1"/><c:showSerName val="0"/><c:showPercent val="1"/><c:showBubbleSize val="0"/>'
            . '</c:dLbls>'
            . '<c:cat><c:strRef><c:f>' . $catFormula . '</c:f><c:strCache><c:ptCount val="' . $count . '"/>' . $strPts . '</c:strCache></c:strRef></c:cat>'
            . '<c:val><c:numRef><c:f>' . $valFormula . '</c:f><c:numCache><c:formatCode>General</c:formatCode><c:ptCount val="' . $count . '"/>' . $numPts . '</c:numCache></c:numRef></c:val>'
            . '</c:ser>'
            . '<c:firstSliceAng val="0"/>'
            . '<c:holeSize val="55"/>'
            . '</c:doughnutChart>'
            . '</c:plotArea>'
            . '<c:legend><c:legendPos val="b"/><c:overlay val="0"/></c:legend>'
            . '<c:plotVisOnly val="1"/>'
            . '</c:chart>'
            . '<c:spPr><a:solidFill><a:srgbClr val="FFFFFF"/></a:solidFill><a:ln w="12700"><a:solidFill><a:srgbClr val="CBD5E1"/></a:solidFill></a:ln></c:spPr>'
            . '</c:chartSpace>';
    }

    /**
     * Estilos institucionales (paleta azul #121A3E, #505984, #7090CB, #F4F7FB, #FFFFFF).
     */
    private static function buildStylesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<numFmts count="1">'
            . '<numFmt numFmtId="164" formatCode="0.0%"/>'
            . '</numFmts>'
            . '<fonts count="6">'
            // 0: Fuente base para celdas de datos
            . '<font><sz val="10"/><color rgb="FF1E293B"/><name val="Segoe UI"/></font>'
            // 1: Fuente Título Banner Principal (14pt negrita blanco)
            . '<font><b/><sz val="13"/><color rgb="FFFFFFFF"/><name val="Segoe UI"/></font>'
            // 2: Fuente Subtítulo Banner (9.5pt blanco suave)
            . '<font><sz val="9.5"/><color rgb="FFE2E8F0"/><name val="Segoe UI"/></font>'
            // 3: Fuente Cabecera Tabla y Etiqueta KPI (9.5pt negrita blanco)
            . '<font><b/><sz val="9.5"/><color rgb="FFFFFFFF"/><name val="Segoe UI"/></font>'
            // 4: Fuente Valor KPI (13pt negrita azul oscuro)
            . '<font><b/><sz val="13"/><color rgb="FF121A3E"/><name val="Segoe UI"/></font>'
            // 5: Fuente Badge / Destacado Azul (9.5pt negrita azul institucional)
            . '<font><b/><sz val="9.5"/><color rgb="FF505984"/><name val="Segoe UI"/></font>'
            . '</fonts>'
            . '<fills count="8">'
            // 0 y 1: Obligatorios por estándar OpenXML
            . '<fill><patternFill patternType="none"/></fill>'
            . '<fill><patternFill patternType="gray125"/></fill>'
            // 2: Azul Oscuro Institucional (#121A3E) para Banner Principal
            . '<fill><patternFill patternType="solid"><fgColor rgb="FF121A3E"/><bgColor indexed="64"/></patternFill></fill>'
            // 3: Azul Primario Institucional (#505984) para Encabezados de Tabla
            . '<fill><patternFill patternType="solid"><fgColor rgb="FF505984"/><bgColor indexed="64"/></patternFill></fill>'
            // 4: Azul Terciario (#7090CB) para Etiquetas de KPI y Secciones
            . '<fill><patternFill patternType="solid"><fgColor rgb="FF7090CB"/><bgColor indexed="64"/></patternFill></fill>'
            // 5: Fondo Blanco (#FFFFFF) para filas impares
            . '<fill><patternFill patternType="solid"><fgColor rgb="FFFFFFFF"/><bgColor indexed="64"/></patternFill></fill>'
            // 6: Fondo Azul Cebra Suave (#F4F7FB) para filas pares
            . '<fill><patternFill patternType="solid"><fgColor rgb="FFF4F7FB"/><bgColor indexed="64"/></patternFill></fill>'
            // 7: Fondo Píldora / KPI Valor (#EBF1FA)
            . '<fill><patternFill patternType="solid"><fgColor rgb="FFEBF1FA"/><bgColor indexed="64"/></patternFill></fill>'
            . '</fills>'
            . '<borders count="3">'
            // 0: Sin borde
            . '<border><left/><right/><top/><bottom/><diagonal/></border>'
            // 1: Borde de celda de tabla suave (#CBD5E1)
            . '<border>'
            . '<left style="thin"><color rgb="FFCBD5E1"/></left>'
            . '<right style="thin"><color rgb="FFCBD5E1"/></right>'
            . '<top style="thin"><color rgb="FFCBD5E1"/></top>'
            . '<bottom style="thin"><color rgb="FFCBD5E1"/></bottom>'
            . '<diagonal/>'
            . '</border>'
            // 2: Borde institucional más marcado (#505984)
            . '<border>'
            . '<left style="thin"><color rgb="FF505984"/></left>'
            . '<right style="thin"><color rgb="FF505984"/></right>'
            . '<top style="thin"><color rgb="FF505984"/></top>'
            . '<bottom style="medium"><color rgb="FF121A3E"/></bottom>'
            . '<diagonal/>'
            . '</border>'
            . '</borders>'
            . '<cellStyleXfs count="1">'
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>'
            . '</cellStyleXfs>'
            . '<cellXfs count="16">'
            // 0: Estilo base por defecto
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            // 1: Banner Principal (#121A3E)
            . '<xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1" applyAlignment="1"><alignment horizontal="left" vertical="center" indent="1"/></xf>'
            // 2: Subtítulo Banner (#505984)
            . '<xf numFmtId="0" fontId="2" fillId="3" borderId="0" xfId="0" applyFont="1" applyFill="1" applyAlignment="1"><alignment horizontal="left" vertical="center" indent="1"/></xf>'
            // 3: KPI Header / Título de Sección (#7090CB, texto blanco centrado)
            . '<xf numFmtId="0" fontId="3" fillId="4" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'
            // 4: KPI Valor Numérico (#EBF1FA, fuente grande azul oscuro)
            . '<xf numFmtId="3" fontId="4" fillId="7" borderId="1" xfId="0" applyNumberFormat="1" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'
            // 5: Cabecera de Tabla (#505984, texto blanco centrado con wrap)
            . '<xf numFmtId="0" fontId="3" fillId="3" borderId="2" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>'
            // 6: Fila Impar - Texto con salto de línea (Blanco)
            . '<xf numFmtId="0" fontId="0" fillId="5" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="left" vertical="center" wrapText="1"/></xf>'
            // 7: Fila Par - Texto con salto de línea (Cebra #F4F7FB)
            . '<xf numFmtId="0" fontId="0" fillId="6" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="left" vertical="center" wrapText="1"/></xf>'
            // 8: Fila Impar - Centrado
            . '<xf numFmtId="0" fontId="0" fillId="5" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>'
            // 9: Fila Par - Centrado
            . '<xf numFmtId="0" fontId="0" fillId="6" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>'
            // 10: Fila Impar - Número entero con separador de miles
            . '<xf numFmtId="3" fontId="0" fillId="5" borderId="1" xfId="0" applyNumberFormat="1" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'
            // 11: Fila Par - Número entero con separador de miles
            . '<xf numFmtId="3" fontId="0" fillId="6" borderId="1" xfId="0" applyNumberFormat="1" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'
            // 12: Badge Azul Institucional Activo (#EBF1FA con texto #505984 en negrita)
            . '<xf numFmtId="0" fontId="5" fillId="7" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>'
            // 13: Badge Secundario Blanco con texto Azul (#FFFFFF con texto #505984)
            . '<xf numFmtId="0" fontId="5" fillId="5" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>'
            // 14: Porcentaje Fila Impar (0.0%)
            . '<xf numFmtId="164" fontId="0" fillId="5" borderId="1" xfId="0" applyNumberFormat="1" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'
            // 15: Porcentaje Fila Par (0.0%)
            . '<xf numFmtId="164" fontId="0" fillId="6" borderId="1" xfId="0" applyNumberFormat="1" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'
            . '</cellXfs>'
            . '<cellStyles count="1">'
            . '<cellStyle name="Normal" xfId="0" builtinId="0"/>'
            . '</cellStyles>'
            . '<dxfs count="0"/>'
            . '<tableStyles count="0" defaultTableStyle="TableStyleMedium2" defaultPivotStyle="PivotStyleLight16"/>'
            . '</styleSheet>';
    }

    private static function buildContentTypesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet2.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '<Override PartName="/xl/drawings/drawing1.xml" ContentType="application/vnd.openxmlformats-officedocument.drawing+xml"/>'
            . '<Override PartName="/xl/charts/chart1.xml" ContentType="application/vnd.openxmlformats-officedocument.drawingml.chart+xml"/>'
            . '<Override PartName="/xl/charts/chart2.xml" ContentType="application/vnd.openxmlformats-officedocument.drawingml.chart+xml"/>'
            . '<Override PartName="/xl/charts/chart3.xml" ContentType="application/vnd.openxmlformats-officedocument.drawingml.chart+xml"/>'
            . '<Override PartName="/xl/charts/chart4.xml" ContentType="application/vnd.openxmlformats-officedocument.drawingml.chart+xml"/>'
            . '<Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>'
            . '<Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>'
            . '</Types>';
    }

    private static function buildRootRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>'
            . '<Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>'
            . '</Relationships>';
    }

    private static function buildAppPropsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties" xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">'
            . '<Application>Repositorio Institucional CIIDI</Application>'
            . '<DocSecurity>0</DocSecurity>'
            . '<ScaleCrop>false</ScaleCrop>'
            . '<Company>CIIDI</Company>'
            . '<LinksUpToDate>false</LinksUpToDate>'
            . '<SharedDoc>false</SharedDoc>'
            . '<HyperlinksChanged>false</HyperlinksChanged>'
            . '<AppVersion>16.0300</AppVersion>'
            . '</Properties>';
    }

    private static function buildCorePropsXml(): string
    {
        $isoNow = gmdate('Y-m-d\TH:i:s\Z');
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:dcmitype="http://purl.org/dc/dcmitype/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'
            . '<dc:title>Catálogo de Proyectos Socio-Tecnológicos CIIDI</dc:title>'
            . '<dc:subject>Reporte Ejecutivo y Catálogo PST</dc:subject>'
            . '<dc:creator>Plataforma CIIDI</dc:creator>'
            . '<dcterms:created xsi:type="dcterms:W3CDTF">' . $isoNow . '</dcterms:created>'
            . '<dcterms:modified xsi:type="dcterms:W3CDTF">' . $isoNow . '</dcterms:modified>'
            . '</cp:coreProperties>';
    }

    private static function buildWorkbookXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<workbookViews><workbookView xWindow="0" yWindow="0" windowWidth="24000" windowHeight="12000"/></workbookViews>'
            . '<sheets>'
            . '<sheet name="Catalogo de Proyectos" sheetId="1" r:id="rId1"/>'
            . '<sheet name="Resumen y Graficos" sheetId="2" r:id="rId2"/>'
            . '</sheets>'
            . '</workbook>';
    }

    private static function buildWorkbookRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet2.xml"/>'
            . '<Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';
    }

    private static function buildSheet2RelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/drawing" Target="../drawings/drawing1.xml"/>'
            . '</Relationships>';
    }

    private static function buildDrawingRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/chart" Target="../charts/chart1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/chart" Target="../charts/chart2.xml"/>'
            . '<Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/chart" Target="../charts/chart3.xml"/>'
            . '<Relationship Id="rId4" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/chart" Target="../charts/chart4.xml"/>'
            . '</Relationships>';
    }

    private static function inlineCell(string $cellRef, string $text, int $styleIdx): string
    {
        $clean = self::esc($text);
        return '<c r="' . $cellRef . '" s="' . $styleIdx . '" t="inlineStr"><is><t xml:space="preserve">' . $clean . '</t></is></c>';
    }

    private static function numCell(string $cellRef, int|float $num, int $styleIdx): string
    {
        return '<c r="' . $cellRef . '" s="' . $styleIdx . '"><v>' . $num . '</v></c>';
    }

    private static function emptyCell(string $cellRef, int $styleIdx): string
    {
        return '<c r="' . $cellRef . '" s="' . $styleIdx . '"/>';
    }

    private static function colLetter(int $colIndex): string
    {
        $letter = '';
        while ($colIndex > 0) {
            $mod = ($colIndex - 1) % 26;
            $letter = chr(65 + $mod) . $letter;
            $colIndex = (int)(($colIndex - $mod) / 26);
        }
        return $letter;
    }

    private static function esc(string $str): string
    {
        // Eliminar caracteres de control XML inválidos excepto tab, CR, LF
        $str = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $str) ?? '';
        return htmlspecialchars($str, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
