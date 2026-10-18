<?php
// modules/RepositorioPST/services/ExtractorPST.php

if (!class_exists('\Smalot\PdfParser\Parser')) {
    $vendorAutoload = dirname(__DIR__, 3) . '/vendor/autoload.php';
    if (file_exists($vendorAutoload)) {
        require_once $vendorAutoload;
    }
}

if (!class_exists('ConfigService')) {
    $cfgFile = __DIR__ . '/ConfigService.php';
    if (file_exists($cfgFile)) {
        require_once $cfgFile;
    }
}

class ExtractorPST {

    /**
     * Extrae texto de cualquier documento PST soportado (.pdf, .docx, .doc) detectando su formato real.
     */
    public static function extraerTextoArchivo(string $filePath, string $extension = ''): string {
        if (!file_exists($filePath)) {
            throw new Exception("El archivo especificado no existe.");
        }

        $ext = strtolower(trim($extension ?: pathinfo($filePath, PATHINFO_EXTENSION)));

        // Detectar firma binaria real (Magic Bytes) por si un .doc fue renombrado a .docx o viceversa
        $fh = @fopen($filePath, 'rb');
        $magic = $fh ? bin2hex((string)fread($fh, 8)) : '';
        if ($fh) fclose($fh);

        if (strpos($magic, '25504446') === 0 || $ext === 'pdf') {
            return self::extraerTextoPDF($filePath);
        }
        if (strpos($magic, 'd0cf11e0') === 0 || $ext === 'doc') {
            return self::extraerTextoDOC($filePath);
        }
        if (strpos($magic, '504b0304') === 0 || $ext === 'docx') {
            return self::extraerTextoDOCX($filePath);
        }

        throw new Exception("El archivo está dañado, vacío o su formato binario no corresponde a un documento Word (.docx/.doc) o PDF válido.");
    }

    /**
     * Optimiza y comprime un archivo Word (.docx) reduciendo el peso de las imágenes internas (word/media/*)
     * mediante redimensionado inteligente y compresión GD para evitar saturar el almacenamiento del servidor.
     */
    public static function optimizarArchivoDOCX(string $filePath, int $maxWidth = 1200, int $jpegQuality = 72): bool {
        if (!file_exists($filePath) || !extension_loaded('gd') || !class_exists('\ZipArchive')) {
            return false;
        }

        // Solo optimizar si el archivo supera los 5 MB
        clearstatcache(true, $filePath);
        $fileSize = @filesize($filePath);
        if (!$fileSize || $fileSize < 5 * 1024 * 1024) {
            return false;
        }

        $zip = new \ZipArchive();
        if ($zip->open($filePath) !== true) {
            return false;
        }

        $mediaToReplace = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            if (!$stat || empty($stat['name'])) continue;
            $name = $stat['name'];

            // Comprimir imágenes pesadas (> 100 KB) alojadas en word/media/
            if (strpos($name, 'word/media/') === 0 && $stat['size'] > 100 * 1024) {
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                if (in_array($ext, ['png', 'jpg', 'jpeg', 'bmp'], true)) {
                    $rawImg = $zip->getFromIndex($i);
                    if (!$rawImg) continue;

                    $img = @imagecreatefromstring($rawImg);
                    if ($img !== false) {
                        $w = imagesx($img);
                        $h = imagesy($img);
                        if ($w > $maxWidth) {
                            $newH = max(1, (int)round($h * ($maxWidth / $w)));
                            $scaled = @imagescale($img, $maxWidth, $newH, IMG_BILINEAR_FIXED);
                            if ($scaled !== false) {
                                $img = $scaled;
                            }
                        }

                        ob_start();
                        @imagejpeg($img, null, $jpegQuality);
                        $compressedData = ob_get_clean();
                        unset($img);

                        if ($compressedData && strlen($compressedData) < ($stat['size'] * 0.85)) {
                            $mediaToReplace[$name] = $compressedData;
                        }
                    }
                }
            }
        }

        if (!empty($mediaToReplace)) {
            foreach ($mediaToReplace as $name => $data) {
                $zip->addFromString($name, $data);
            }
        }
        $zip->close();
        clearstatcache(true, $filePath);
        return !empty($mediaToReplace);
    }

    /**
     * Extrae texto de un archivo PDF usando Smalot\PdfParser con lectura zonal:
     * lee las primeras páginas (preliminares, Parte I y Parte II) y escanea enlaces/secciones clave.
     */
    public static function extraerTextoPDF(string $filePath): string {
        if (!file_exists($filePath)) {
            throw new Exception("El archivo PDF especificado no existe.");
        }
        
        try {
            ini_set('memory_limit', '512M');
            $config = new \Smalot\PdfParser\Config();
            $config->setDecodeMemoryLimit(120 * 1024 * 1024);
            
            $parser = new \Smalot\PdfParser\Parser([], $config);
            $pdf = $parser->parseFile($filePath);

            $maxPaginas = class_exists('ConfigService') 
                ? max(35, (int)ConfigService::get('archivos.max_paginas_analisis', 35))
                : 35;

            $pages = $pdf->getPages();
            $totalPages = count($pages);

            if ($totalPages === 0) {
                $raw = $pdf->getText();
                unset($pdf, $parser);
                return $raw ?? '';
            }

            $pagesToRead = ($maxPaginas > 0) ? min($totalPages, $maxPaginas) : $totalPages;

            $textParts = [];
            for ($i = 0; $i < $pagesToRead; $i++) {
                try {
                    $pageText = $pages[$i]->getText();
                    if (!empty($pageText)) {
                        $textParts[] = $pageText;
                    }
                } catch (\Throwable $pe) {
                    error_log("Error extrayendo texto en página PDF {$i}: " . $pe->getMessage());
                }
            }

            // Si el PDF tiene más páginas, hacer muestreo ligero en busca de Parte II o enlaces GitHub
            if ($totalPages > $pagesToRead) {
                $extraScanLimit = min($totalPages, $pagesToRead + 30);
                for ($i = $pagesToRead; $i < $extraScanLimit; $i++) {
                    try {
                        $pt = $pages[$i]->getText();
                        if (!empty($pt) && preg_match('/(l[íi]nea\s+de\s+investigaci[óo]n|dimensi[óo]n|parte\s+ii\b|github\.com|gitlab\.com|bitbucket\.org)/ui', $pt)) {
                            $textParts[] = $pt;
                        }
                    } catch (\Throwable $pe) {}
                }
            }

            unset($pages, $pdf, $parser);
            if (function_exists('gc_collect_cycles')) {
                gc_collect_cycles();
            }

            return implode("\n", $textParts);
        } catch (\Throwable $e) {
            error_log("Error al extraer texto PDF: " . $e->getMessage());
            return '';
        }
    }

    /**
     * Extrae texto de un archivo Word (.docx) mediante lectura zonal inteligente:
     * - Zona 1: Primeros 950 párrafos (Preliminares, Portada, Resumen, Parte I, Objetivos, Comunidad).
     * - Zona 2: Escaneo completo de Parte II (Línea de Investigación, Dimensión Operativa, Marco Referencial) y enlaces en párrafos > 950.
     * - Zona 3: Extracción de hipervínculos reales en word/_rels/document.xml.rels (para capturar links de GitHub).
     */
    public static function extraerTextoDOCX(string $filePath): string {
        if (!file_exists($filePath)) {
            throw new Exception("El archivo Word especificado no existe.");
        }

        $zip = new \ZipArchive();
        if ($zip->open($filePath) === true) {
            $index = $zip->locateName('word/document.xml');
            if ($index !== false) {
                $stat = $zip->statIndex($index);
                $maxXmlMb = class_exists('ConfigService') 
                    ? max(25, (int)ConfigService::get('archivos.max_xml_descomprimido_mb', 25))
                    : 25;
                if ($stat !== false && isset($stat['size']) && $stat['size'] > ($maxXmlMb * 1024 * 1024)) {
                    $zip->close();
                    throw new Exception("El contenido de texto del documento Word excede el límite permitido ({$maxXmlMb}MB descomprimido).");
                }

                $data = $zip->getFromIndex($index);

                // Extraer también hipervínculos externos desde word/_rels/document.xml.rels
                $relsLinks = [];
                $relsIdx = $zip->locateName('word/_rels/document.xml.rels');
                if ($relsIdx !== false) {
                    $relsXml = (string)$zip->getFromIndex($relsIdx);
                    if (preg_match_all('/Target="([^"]*(?:github\.com|gitlab\.com|bitbucket\.org|codeberg\.org)[^"]*)"/i', $relsXml, $rm)) {
                        $relsLinks = array_unique($rm[1]);
                    }
                }

                $zip->close();
                
                $dom = new \DOMDocument();
                libxml_use_internal_errors(true);
                $dom->loadXML($data, LIBXML_NONET);
                libxml_clear_errors();

                $xpath = new \DOMXPath($dom);
                $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');

                $nodes = $xpath->query('//w:p');
                $lines = [];
                $zone1Limit = 950;
                $totalNodes = $nodes->length;
                $extraWindow = 0;

                for ($idx = 0; $idx < $totalNodes; $idx++) {
                    $node = $nodes->item($idx);
                    if (!$node) continue;

                    // En Zona 2 (después del párrafo 950), filtrar rápido antes de construir XPath detallado
                    if ($idx >= $zone1Limit && $extraWindow <= 0) {
                        $quickText = $node->textContent;
                        if ($quickText === '' || !preg_match('/(l[íi]nea\s+de\s+investigaci[óo]n|dimensi[óo]n|vinculaci[óo]n\s+del\s+proyecto|parte\s+ii\b|github\.com|gitlab\.com|bitbucket\.org|codeberg\.org)/ui', $quickText)) {
                            continue;
                        }
                        // Si encontramos una sección relevante en Zona 2, capturar también los siguientes 6 párrafos de contexto
                        $extraWindow = 6;
                    } elseif ($idx >= $zone1Limit && $extraWindow > 0) {
                        $extraWindow--;
                    }

                    // Capturar <w:t>, saltos <w:br> y tabuladores <w:tab> ignorando <w:instrText> y offsets de dibujo
                    $childElements = $xpath->query('.//w:t | .//w:br | .//w:tab', $node);
                    $pText = '';
                    foreach ($childElements as $elem) {
                        if ($elem->nodeName === 'w:br') {
                            $pText .= "\n";
                        } elseif ($elem->nodeName === 'w:tab') {
                            $pText .= "\t";
                        } else {
                            $pText .= $elem->nodeValue;
                        }
                    }

                    $subLines = preg_split('/[\r\n\t]+/', $pText);
                    foreach ($subLines as $sl) {
                        $trimmed = trim($sl);
                        if ($trimmed !== '') {
                            $lines[] = $trimmed;
                        }
                    }
                }

                if (!empty($relsLinks)) {
                    foreach ($relsLinks as $rl) {
                        $lines[] = "URL_REPOSITORIO_DETECTADA: " . $rl;
                    }
                }

                unset($data, $dom, $xpath, $nodes);
                if (function_exists('gc_collect_cycles')) {
                    gc_collect_cycles();
                }

                return implode("\n", $lines);
            }
            $zip->close();
        }
        throw new Exception("No se pudo leer la estructura del archivo Word (.docx) o el archivo está dañado.");
    }

    /**
     * Extrae texto de un archivo binario antiguo de Microsoft Word 97-2003 (.doc / OLE2 Compound File).
     * Lee de manera eficiente los bloques de texto sin cargar las imágenes incrustadas en memoria.
     */
    public static function extraerTextoDOC(string $filePath): string {
        if (!file_exists($filePath)) {
            throw new Exception("El archivo Word (.doc) especificado no existe.");
        }

        $fp = @fopen($filePath, 'rb');
        if (!$fp) {
            throw new Exception("No se pudo abrir el archivo Word (.doc).");
        }

        $fileSize = (int)@filesize($filePath);
        $extractedChunks = [];
        $accumulatedLen = 0;

        $maxScanBytes = min($fileSize, 12 * 1024 * 1024);
        $offset = 0;
        $foundStart = false;

        while (!feof($fp) && $offset < $maxScanBytes) {
            $chunk = fread($fp, 512 * 1024);
            if ($chunk === false || $chunk === '') break;

            if (!$foundStart) {
                if (preg_match('/(REP[ÚU\xda]BLICA|MINISTERIO\s+DEL\s+PODER|UNIVERSIDAD\s+POLIT)/i', $chunk, $m, PREG_OFFSET_CAPTURE)) {
                    $foundStart = true;
                    $startPos = max(0, $m[0][1]);
                    $chunk = substr($chunk, $startPos);
                } elseif (preg_match('/U\x00N\x00I\x00V\x00E\x00R\x00S\x00I\x00D\x00A\x00D/i', $chunk, $m, PREG_OFFSET_CAPTURE)) {
                    $foundStart = true;
                    $startPos = max(0, $m[0][1]);
                    $sub = substr($chunk, $startPos);
                    $chunk = mb_convert_encoding($sub, 'Windows-1252', 'UTF-16LE');
                }
            }

            if ($foundStart) {
                if ($accumulatedLen > 2000 && preg_match('/(\x89PNG|JFIF|\x00{256,})/s', $chunk, $endM, PREG_OFFSET_CAPTURE)) {
                    $extractedChunks[] = substr($chunk, 0, $endM[0][1]);
                    break;
                }
                $extractedChunks[] = $chunk;
                $accumulatedLen += strlen($chunk);
                if ($accumulatedLen > 500 * 1024) {
                    break;
                }
            }
            $offset += 512 * 1024;
        }
        fclose($fp);

        $rawText = implode('', $extractedChunks);
        if ($rawText === '') {
            return '';
        }

        $utf8 = @mb_convert_encoding($rawText, 'UTF-8', 'Windows-1252');
        $utf8 = str_replace(["\r\n", "\r", "\x07", "\x0B", "\x0C", "\t"], "\n", $utf8);
        $utf8 = preg_replace('/HYPERLINK\s+"[^"]*"/ui', ' ', $utf8);
        $utf8 = preg_replace('/[\x00-\x08\x0E-\x1F]/u', ' ', $utf8);

        $lines = explode("\n", $utf8);
        $cleanLines = [];
        foreach ($lines as $l) {
            $l = trim(preg_replace('/\s+/u', ' ', $l));
            if (mb_strlen($l) >= 3 && preg_match('/[a-záéíóúñA-ZÁÉÍÓÚÑ]{2,}/u', $l)) {
                $cleanLines[] = $l;
            }
        }

        return implode("\n", $cleanLines);
    }

    /**
     * Parsea el texto extraído del documento para obtener todos los metadatos del formulario PST.
     */
    public static function analizarTexto(string $text, string $fileName = ''): array {
        $lines = explode("\n", $text);
        $cleanLines = [];
        foreach ($lines as $l) {
            // Limpiar instrucciones residuales de tabla de contenido de Word (TOC \o, PAGEREF _Toc)
            $l = preg_replace('/\bTOC\s+\\\\[a-z]\s+(?:"[^"]*"\s*)?(?:\\\\[a-z]\s*)*\b/ui', '', $l);
            $l = preg_replace('/\bHYPERLINK\s+\\\\l\s+"[^"]*"/ui', '', $l);
            // Remover basura numérica de offsets de imágenes al inicio de línea (ej: "5562600812803048001000125REPÚBLICA" o "-12127583820")
            $l = preg_replace('/^(?:center|left|right)?-?\d{6,}(?=[A-ZÁÉÍÓÚÑ]|$)/u', '', trim($l));
            $trimmed = trim($l);
            if ($trimmed !== '') {
                $cleanLines[] = $trimmed;
            }
        }

        // 1. Extraer Título
        $titulo = self::extraerTitulo($cleanLines, $text, $fileName);

        // 2. Extraer Fecha de Defensa y Año de Creación/Publicación (Portada / Resumen / Nombre de Archivo)
        $fechaInfo = self::extraerFechaYAnio($text, $cleanLines, $fileName);
        $anio = $fechaInfo['anio'];
        $fechaDefensa = $fechaInfo['fecha_defensa'];

        // 3. Extraer Trayecto del PNF (Trayecto I, II, III, IV)
        $trayectoInfo = self::extraerTrayecto($text, $cleanLines, $fileName);

        // 4. Extraer Resumen
        $resumen = self::extraerResumen($text, $cleanLines);

        // 5. Extraer Palabras Clave
        $palabrasClave = self::extraerPalabrasClave($text, $cleanLines);

        // 6. Extraer Comunidad u Objeto Beneficiario
        $comunidad = self::extraerComunidad($text, $cleanLines, $titulo);

        // 7. Extraer Cédulas, Autores y Tutores
        $personas = self::extraerPersonas($cleanLines);

        $autoresFiltrados = [];
        foreach ($personas['autores'] as $aut) {
            if (!empty(trim($aut['nombre'])) || !empty(trim($aut['cedula']))) {
                $autoresFiltrados[] = $aut;
            }
        }

        // 8. Extraer Objetivo General del documento
        $objGeneral = self::extraerObjetivoGeneral($text, $cleanLines, $titulo);

        // 9. Clasificación Inteligente de Línea de Investigación y Dimensión Operativa (Parte II + Contexto Integral)
        $clasificacion = self::clasificarLineaYDimension($text, $titulo, $objGeneral, $palabrasClave, $resumen);

        // 10. Extraer Link de GitHub / Repositorio de Código
        $urlRepositorio = self::extraerUrlRepositorio($text);

        return [
            'titulo'                     => $titulo,
            'anio_publicacion'           => $anio,
            'fecha_defensa'              => $fechaDefensa,
            'fecha_precision'            => $fechaInfo['precision'],
            'fecha_texto_detectado'      => $fechaInfo['texto_detectado'],
            'trayecto'                   => $trayectoInfo['nombre'],
            'id_trayecto'                => $trayectoInfo['id'],
            'url_repositorio'            => $urlRepositorio,
            'resumen'                    => $resumen,
            'obj_general'                => $objGeneral,
            'palabras_clave'             => $palabrasClave,
            'comunidad_beneficiada'      => $comunidad,
            'autores'                    => $autoresFiltrados,
            'tutor_academico_nombre'     => $personas['tutores']['academico']['nombre'] ?? '',
            'tutor_academico_cedula'     => $personas['tutores']['academico']['cedula'] ?? '',
            'tutor_institucional_nombre' => $personas['tutores']['institucional']['nombre'] ?? '',
            'tutor_institucional_cedula' => $personas['tutores']['institucional']['cedula'] ?? '',
            'tutor_comunitario_nombre'   => $personas['tutores']['comunitario']['nombre'] ?? '',
            'tutor_comunitario_cedula'   => $personas['tutores']['comunitario']['cedula'] ?? '',
            'linea_id'                   => $clasificacion['linea_id'],
            'dimension_id'               => $clasificacion['dimension_id']
        ];
    }

    /**
     * Extrae la Fecha de Defensa y el Año de creación del PST desde la portada, el resumen o el nombre del archivo.
     * - Si encuentra día, mes y año -> devuelve 'YYYY-MM-DD' exacto.
     * - Si encuentra mes y año (ej: "Valera, Noviembre de 2025") -> devuelve 'YYYY-MM-01' (para posicionar el mes y año en el input date) y el año.
     * - Si solo encuentra el año -> devuelve el año en 'anio' y deja 'fecha_defensa' vacía ('') para que el usuario complete día y mes.
     */
    private static function extraerFechaYAnio(string $text, array $lines, string $fileName = ''): array {
        $mesesMap = [
            'enero'      => '01', 'febrero'    => '02', 'marzo'      => '03', 'abril'      => '04',
            'mayo'       => '05', 'junio'      => '06', 'julio'      => '07', 'agosto'     => '08',
            'septiembre' => '09', 'setiembre'  => '09', 'octubre'    => '10', 'noviembre'  => '11',
            'diciembre'  => '12'
        ];

        $coverLines = array_slice($lines, 0, 85);
        $coverText = implode("\n", $coverLines);

        // 1. Buscar fecha completa numérica o textual en portada (ej: "02 de diciembre de 2025", "23/07/2026", "02-12-2025")
        if (preg_match('/\b(\d{1,2})\s+de\s+(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|setiembre|octubre|noviembre|diciembre)\s+(?:de|del)?\s*(201[8-9]|202[0-9])\b/ui', $coverText, $m)) {
            $dia = str_pad((string)min(31, max(1, (int)$m[1])), 2, '0', STR_PAD_LEFT);
            $mes = $mesesMap[mb_strtolower($m[2])] ?? '01';
            $anio = (int)$m[3];
            return [
                'anio'            => $anio,
                'fecha_defensa'   => "{$anio}-{$mes}-{$dia}",
                'precision'       => 'completa',
                'texto_detectado' => trim($m[0])
            ];
        }

        if (preg_match('/\b(\d{2})[\/\-](\d{2})[\/\-](201[8-9]|202[0-9])\b/', $coverText . ' ' . $fileName, $m)) {
            $d = (int)$m[1];
            $mo = (int)$m[2];
            $y = (int)$m[3];
            if ($d >= 1 && $d <= 31 && $mo >= 1 && $mo <= 12) {
                $dia = str_pad((string)$d, 2, '0', STR_PAD_LEFT);
                $mes = str_pad((string)$mo, 2, '0', STR_PAD_LEFT);
                return [
                    'anio'            => $y,
                    'fecha_defensa'   => "{$y}-{$mes}-{$dia}",
                    'precision'       => 'completa',
                    'texto_detectado' => trim($m[0])
                ];
            }
        }

        // 2. Buscar "Mes, Año" o "Ciudad, Mes de Año" en portada y encabezado de resumen (ej: "Valera, Noviembre de 2025", "JULIO, 2026")
        foreach ($coverLines as $line) {
            // Evitar confundir el nombre de un autor llamado "Julio César..." con el mes de Julio si no hay año en esa línea
            if (preg_match('/\b(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|setiembre|octubre|noviembre|diciembre)\s*(?:de|del|,|\s|-)*\s*(201[8-9]|202[0-9])\b/ui', $line, $m)) {
                $mesNombre = mb_strtolower($m[1]);
                $mes = $mesesMap[$mesNombre] ?? '01';
                $anio = (int)$m[2];

                // Verificar si en el nombre del archivo viene el día exacto (ej: "Actualizado 23 Julio" o "2 DE DICIEMBRE")
                $dia = '01';
                $precision = 'mes_anio';
                if (preg_match('/\b(\d{1,2})\s+(?:de\s+)?' . preg_quote($mesNombre, '/') . '\b/ui', $line . ' ' . $fileName, $dm)) {
                    $dVal = (int)$dm[1];
                    if ($dVal >= 1 && $dVal <= 31) {
                        $dia = str_pad((string)$dVal, 2, '0', STR_PAD_LEFT);
                        $precision = 'completa';
                    }
                }

                return [
                    'anio'            => $anio,
                    'fecha_defensa'   => "{$anio}-{$mes}-{$dia}",
                    'precision'       => $precision,
                    'texto_detectado' => ucfirst($mesNombre) . ' ' . $anio
                ];
            }
        }

        // 3. Verificar fecha en el nombre del archivo (ej: "PROYECTO 2 DE DICIEMBRE.pdf", "JULIO 2026")
        if (preg_match('/\b(?:(\d{1,2})\s+de\s+)?(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|setiembre|octubre|noviembre|diciembre)(?:\s+(?:de\s+)?(201[8-9]|202[0-9]))?\b/ui', $fileName, $fm)) {
            $mesNombre = mb_strtolower($fm[2]);
            $mes = $mesesMap[$mesNombre] ?? '01';
            $anio = !empty($fm[3]) ? (int)$fm[3] : self::extraerAnio($text, $fileName);
            $dia = !empty($fm[1]) ? str_pad((string)min(31, max(1, (int)$fm[1])), 2, '0', STR_PAD_LEFT) : '01';
            return [
                'anio'            => $anio,
                'fecha_defensa'   => "{$anio}-{$mes}-{$dia}",
                'precision'       => !empty($fm[1]) ? 'completa' : 'mes_anio',
                'texto_detectado' => trim($fm[0]) . ' ' . $anio
            ];
        }

        // 4. Solo Año detectado (sin día ni mes): se devuelve el año y fecha_defensa vacía para que el usuario complete día/mes
        $anioSolo = self::extraerAnio($text, $fileName);
        return [
            'anio'            => $anioSolo,
            'fecha_defensa'   => '',
            'precision'       => 'solo_anio',
            'texto_detectado' => (string)$anioSolo
        ];
    }

    /**
     * Extrae el Trayecto del PNF (Trayecto I, II, III, IV) desde la portada, el resumen o el nombre del archivo.
     */
    private static function extraerTrayecto(string $text, array $lines, string $fileName = ''): array {
        $mapTrayectos = [
            1 => ['id' => 1, 'nombre' => 'Trayecto I'],
            2 => ['id' => 2, 'nombre' => 'Trayecto II'],
            3 => ['id' => 3, 'nombre' => 'Trayecto III'],
            4 => ['id' => 4, 'nombre' => 'Trayecto IV'],
        ];

        $coverSample = implode("\n", array_slice($lines, 0, 100));

        // 1. Mención explícita de "Trayecto" en portada o resumen
        if (preg_match('/\btrayecto\s*[:\-]?\s*(?:iv|4|cuatro)\b/ui', $coverSample)) return $mapTrayectos[4];
        if (preg_match('/\btrayecto\s*[:\-]?\s*(?:iii|3|tres)\b/ui', $coverSample)) return $mapTrayectos[3];
        if (preg_match('/\btrayecto\s*[:\-]?\s*(?:ii|2|dos)\b/ui', $coverSample)) return $mapTrayectos[2];
        if (preg_match('/\btrayecto\s*[:\-]?\s*(?:i|1|uno)\b/ui', $coverSample)) return $mapTrayectos[1];

        // 2. Mención en nombre del archivo (ej: "T4", "T3-T3", "Trayecto IV", "PROYECTO IV", "PST 4", "PST3")
        if (preg_match('/(?:\bT4\b|\bT4T\d\b|trayecto\s*(?:iv|4)|proyecto\s*(?:socio\s*tecnol[óo]gico\s*)?(?:iv|4)\b|\bpst\s*4\b|\bpsi\s*iv\b)/ui', $fileName)) {
            return $mapTrayectos[4];
        }
        if (preg_match('/(?:\bT3\b|\bT3[\-_]T\d\b|trayecto\s*(?:iii|3)|proyecto\s*(?:socio\s*tecnol[óo]gico\s*)?(?:iii|3)\b|\bpst\s*(?:iii|3)\b)/ui', $fileName)) {
            return $mapTrayectos[3];
        }
        if (preg_match('/(?:\bT2\b|trayecto\s*(?:ii|2)|proyecto\s*(?:socio\s*tecnol[óo]gico\s*)?(?:ii|2)\b|\bpst\s*(?:ii|2)\b)/ui', $fileName)) {
            return $mapTrayectos[2];
        }
        if (preg_match('/(?:\bT1\b|trayecto\s*(?:i|1)|\bpsti\b)/ui', $fileName)) {
            return $mapTrayectos[1];
        }

        // 3. Mención en cuerpo preliminar ("Proyecto Socio-Tecnológico IV", "cursando el T4", etc.)
        if (preg_match('/\b(?:proyecto\s+socio[\-\s]*tecnol[óo]gico\s+iv|cursando\s+el\s+t4|optando\s+por\s+el\s+t[íi]tulo\s+de\s+ingenier[íi]a)\b/ui', $text)) {
            return $mapTrayectos[4];
        }
        if (preg_match('/\b(?:proyecto\s+socio[\-\s]*tecnol[óo]gico\s+iii|cursando\s+el\s+t3)\b/ui', $text)) {
            return $mapTrayectos[3];
        }
        if (preg_match('/\b(?:proyecto\s+socio[\-\s]*tecnol[óo]gico\s+ii)\b/ui', $text)) {
            return $mapTrayectos[2];
        }

        return $mapTrayectos[3]; // Default institucional más común en la muestra
    }

    /**
     * Extrae la URL del repositorio de código (GitHub, GitLab, Bitbucket, Codeberg) si está presente en el documento.
     */
    private static function extraerUrlRepositorio(string $text): string {
        // Buscar enlaces de repositorios de código reales (descartando páginas github.io de libros/referencias)
        if (preg_match_all('/https?:\/\/(?:www\.)?(?:github\.com|gitlab\.com|bitbucket\.org|codeberg\.org)\/[a-zA-Z0-9_\-\.]+\/[a-zA-Z0-9_\-\.]+/ui', $text, $matches)) {
            foreach ($matches[0] as $url) {
                $cleanUrl = rtrim($url, ".,;:)\"' \t\r\n");
                return $cleanUrl;
            }
        }
        // También soportar formato sin protocolo escrito en el texto (ej: github.com/usuario/proyecto)
        if (preg_match_all('/\b(?:github\.com|gitlab\.com|bitbucket\.org)\/[a-zA-Z0-9_\-\.]+\/[a-zA-Z0-9_\-\.]+/ui', $text, $matches)) {
            foreach ($matches[0] as $url) {
                $cleanUrl = 'https://' . rtrim($url, ".,;:)\"' \t\r\n");
                return $cleanUrl;
            }
        }
        return '';
    }

    /**
     * Extrae de forma precisa el Objetivo General del documento descartando objetivos institucionales y específicos.
     */
    private static function extraerObjetivoGeneral(string $text, array $lines, string $titulo = ''): string {
        $count = count($lines);
        $verbosLogro = 'desarrollar|implementar|implantar|diseñar|crear|proporcionar|fortalecer|construir|evaluar|optimizar|analizar|elaborar|instalar|automatizar|proponer|realizar|brindar|actualizar|modernizar|digitalizar|dotar|sistematizar';

        // 1. Búsqueda por encabezados formales: "Objetivo General", "1.3.1. Objetivo General", "3.1. Objetivo General"
        for ($i = 0; $i < min($count, 850); $i++) {
            $line = trim($lines[$i]);

            // Omitir líneas de tabla de contenido o PAGEREF
            if (preg_match('/(\.\.\.\.\.|PAGEREF\s+_Toc)/i', $line)) continue;

            // Omitir "Objetivos de la Institución / Organización / Comunidad / Concejo Municipal"
            if (preg_match('/objetivos?\s+de\s+(?:la\s+|el\s+)?(?:instituci[óo]n|organizaci[óo]n|empresa|comunidad|concejo|consejo)/ui', $line)) {
                continue;
            }

            // Omitir antecedentes citados en Parte II (ej: si en las 3 líneas anteriores hay un autor con año "(2024)" o "Antecedentes")
            $esAntecedente = false;
            for ($p = max(0, $i - 3); $p < $i; $p++) {
                if (preg_match('/(\(\d{4}\)|antecedentes?\s+de\s+la\s+investigaci[óo]n)/ui', $lines[$p])) {
                    $esAntecedente = true;
                    break;
                }
            }
            if ($esAntecedente) continue;

            if (preg_match('/^\s*(?:[a-z]\)|\d+(?:\.\d+)*\.?\s*)?(?:objetivo\s+general|prop[óo]sito\s+general|objetivo\s+del\s+proyecto)\s*:?\s*\.?\s*$/ui', $line) ||
                preg_match('/^\s*(?:[a-z]\)|\d+(?:\.\d+)*\.?\s*)?(?:objetivo\s+general|prop[óo]sito\s+general|objetivo\s+del\s+proyecto)\b/ui', $line)) {

                // Omitir si la línea es una entrada de índice que termina en número de página (ej: "Propósito General 37")
                if (preg_match('/\s+\d{1,3}\s*$/u', $line)) continue;

                // Caso A: El texto del objetivo general está en la misma línea después de los dos puntos o punto
                if (preg_match('/(?:objetivo\s+general|prop[óo]sito\s+general|objetivo\s+del\s+proyecto)\s*[:\.]\s*(.+)/ui', $line, $m)) {
                    $cand = trim($m[1]);
                    if (mb_strlen($cand) > 20 && preg_match('/\b(' . $verbosLogro . ')\b/ui', $cand)) {
                        return self::limpiarObjetivoGeneral($cand);
                    }
                }

                // Caso B: El texto del objetivo está en las líneas inmediatas siguientes
                $objLines = [];
                for ($k = 1; $k <= 10; $k++) {
                    if (!isset($lines[$i + $k])) break;
                    $candLine = trim($lines[$i + $k]);

                    if ($candLine === '') continue;
                    if (preg_match('/(\.\.\.\.\.|PAGEREF\s+_Toc)/i', $candLine)) break;

                    // Si se encuentra con Objetivos Específicos / Propósitos Intermedios o siguiente sub-sección, detener
                    if (preg_match('/^\s*(?:\d+(?:\.\d+)*\.?\s*)?(?:objetivos?\s+(?:espec[íi]ficos?|intermedios?)|prop[óo]sitos?\s+(?:espec[íi]ficos?|intermedios?)|1\.\d+\.\d+|3\.2|2\.2|cuadro\s+\d+|fundamentaci[óo]n|justificaci[óo]n|marco|alcance)/ui', $candLine)) {
                        break;
                    }
                    // Omitir líneas de índice que terminan en número de página
                    if (preg_match('/\s+\d{1,3}\s*$/u', $candLine) && mb_strlen($candLine) < 55) {
                        continue;
                    }

                    // Omitir definiciones teóricas de metodología o cabeceras del árbol de objetivos ("Fines", "Medios")
                    if (preg_match('/^(es\s+el\s+fin\s+[úu]ltimo|seg[úu]n\s+|debe\s+ser\s+redactado|representan\s+los\s+pasos|son\s+las\s+metas|fines\b|medios\b)/ui', $candLine)) {
                        continue;
                    }

                    $objLines[] = $candLine;
                    $fullTemp = implode(' ', $objLines);
                    if (mb_strlen($fullTemp) > 35 && preg_match('/\.\s*$/u', $candLine)) {
                        break;
                    }
                }

                if (!empty($objLines)) {
                    $res = implode(' ', $objLines);
                    if (mb_strlen($res) > 20 && !preg_match('/^(los\s+objetivos\s+del\s+concejo|brindar\s+una\s+educaci[óo]n\s+integral)/ui', $res)) {
                        return self::limpiarObjetivoGeneral($res);
                    }
                }
            }
        }

        // 2. Buscar declaración explícita de objetivo dentro del Resumen / Introducción ("con el objetivo general de...", "El propósito principal del proyecto fue...")
        if (preg_match('/(?:objetivo\s+general\s+de|prop[óo]sito\s+principal\s+(?:de\s+este\s+proyecto|del\s+proyecto)\s+(?:es|fue|consiste\s+en)|tiene\s+como\s+(?:objetivo|prop[óo]sito|finalidad)\s+(?:principal\s+)?)\s*((?:' . $verbosLogro . ')\b[^.;]{25,320}[\.;])/ui', $text, $m)) {
            return self::limpiarObjetivoGeneral($m[1]);
        }

        // 3. Fallback Inteligente: Derivar del Título si empieza por sustantivo de acción ("IMPLEMENTACIÓN DE...", "DESARROLLO DE...", "OPTIMIZACIÓN DE...", "SISTEMA...")
        if (!empty($titulo)) {
            $tClean = trim($titulo);
            if (preg_match('/^implementaci[óo]n\s+de\s+(.+)/ui', $tClean, $tm)) {
                return 'Implementar ' . mb_strtolower(mb_substr($tm[1], 0, 1)) . mb_substr($tm[1], 1) . '.';
            }
            if (preg_match('/^desarrollo\s+de\s+(.+)/ui', $tClean, $tm)) {
                return 'Desarrollar ' . mb_strtolower(mb_substr($tm[1], 0, 1)) . mb_substr($tm[1], 1) . '.';
            }
            if (preg_match('/^optimizaci[óo]n\s+del?\s+(.+)/ui', $tClean, $tm)) {
                return 'Optimizar el ' . mb_strtolower(mb_substr($tm[1], 0, 1)) . mb_substr($tm[1], 1) . '.';
            }
            if (preg_match('/^fortalecimiento\s+del?\s+(.+)/ui', $tClean, $tm)) {
                return 'Fortalecer el ' . mb_strtolower(mb_substr($tm[1], 0, 1)) . mb_substr($tm[1], 1) . '.';
            }
            if (preg_match('/^(sistema|aplicaci[óo]n|plataforma|portal|gestor)\s+(.+)/ui', $tClean)) {
                return 'Desarrollar e implementar el ' . $tClean . '.';
            }
        }

        return '';
    }

    private static function limpiarObjetivoGeneral(string $str): string {
        $str = preg_replace('/^\s*(?:\d+(?:\.\d+)*\.?\s*)?(?:objetivo\s+general|prop[óo]sito\s+general|objetivo\s+del\s+proyecto|fines\s+medios|fines|medios)\s*:?\s*/ui', '', $str);
        $str = preg_replace('/^\s*\d+\.\s*/u', '', $str);
        $str = preg_replace('/\s+/u', ' ', $str);
        return ucfirst(trim($str, " \t\r\n\:-"));
    }

    /**
     * Extrae dinámicamente el nombre de la comunidad, organización o institución beneficiada.
     */
    private static function extraerComunidad(string $text, array $lines, string $titulo = ''): string {
        $count = count($lines);

        // 1. Extraer directamente desde el Título si contiene el patrón "PARA [EL/LA] [ORGANIZACIÓN]" o "EN [EL/LA] [ORGANIZACIÓN]"
        if (!empty($titulo)) {
            if (preg_match('/\b(?:para\s+el|para\s+la|en\s+el|en\s+la|de\s+la|del|adscrito\s+a\s+la|en|para)\s+((?:Unidad\s+Educativa|Escuela\s+T[ée]cnica(?:\s+Comercial)?|Escuela|Liceo|Colegio|Centro\s+de\s+Educaci[óo]n\s+Inicial|C\.E\.I\.?|Centro\s+Bolivariano[^,]+|Centro\s+de\s+Alta\s+Tecnolog[íi]a[^,]+|Centro\s+de\s+Atenci[óo]n\s+Integral[^,]+|Centro\s+Veterinario[^,]+|Centro\s+Inmobiliario[^,]+|Instituto\s+Nacional[^,]+|Instituto\s+del\s+Deporte[^,]+|INDET[^,]*|Programa\s+Nacional\s+de\s+Formaci[óo]n\s+en\s+Inform[áa]tica[^,]+|Servicio\s+Nacional[^,]+|Servicio\s+Administrativo[^,]+|Consejo\s+Comunal[^,]+|Consejo\s+Local[^,]+|Concejo\s+Municipal[^,]+|Prefectura[^,]+|Asociaci[óo]n[^,]+|Cooperativa[^,]+|Empresa\s+Trujillana[^,]+|Empresa[^,]+|Farmacia[^,]+|Laboratorio\s+Cl[íi]nico[^,]+|Taller\s+Automotriz[^,]+|Oficina\s+Administrativa[^,]+|Super\s+Kauchos[^,]+|La\s+Pantera\s+PC\s+Store|TecnoService[^,]+|Musart\s+Academia[^,]+|INME\s+C\.?A\.?|VALFOR\s+S\.?A\.?|Gominova|ETPROINVI[^,]*|SAPNNAET|SENAMECF).*)$/ui', $titulo, $tm)) {
                $candTitulo = self::limpiarComunidad($tm[1]);
                if (mb_strlen($candTitulo) >= 5 && self::esComunidadValida($candTitulo)) {
                    return $candTitulo;
                }
            }
        }

        // 2. Búsqueda por encabezados formales de sección (ej: 1.1.1, Nombre de la Comunidad, Razón Social)
        for ($i = 0; $i < $count; $i++) {
            $line = trim($lines[$i]);

            if (preg_match('/(descripci[óo]n\s+del\s+proyecto|diagn[oó]stico\s+situacional|descripci[óo]n\s+del\s+contexto|cuadro\s+\d+|tabla\s+\d+|\.\.\.\.\.|PAGEREF\s+_Toc)/ui', $line)) {
                continue;
            }

            if (preg_match('/(1\.1\.1|nombre\s+de\s+la\s+comunidad|nombre\s+de\s+la\s+organizaci[óo]n|comunidad\s+u\s+organizaci[óo]n|raz[óo]n\s+social|empresa\s*\/\s*organizaci[óo]n|organizaci[óo]n\s+beneficiada|comunidad\s+beneficiada)/ui', $line)) {

                if (preg_match('/[a-záéíóúñ\s]*\d+\s*$/ui', $line) && !preg_match('/1\.1\.1\./', $line)) {
                    continue;
                }
                if ($i + 1 < $count && preg_match('/1\.1\.2|naturaleza\s+de\s+la\s+(?:organizaci[óo]n|comunidad)/ui', trim($lines[$i + 1]))) {
                    continue;
                }

                // Si el nombre está en la misma línea después de los dos puntos
                if (preg_match('/:\s*(.+)/u', $line, $m) && mb_strlen(trim($m[1])) > 4) {
                    $cand = self::limpiarComunidad($m[1]);
                    if (self::esComunidadValida($cand)) {
                        return $cand;
                    }
                }

                // Si está en las líneas siguientes
                for ($k = 1; $k <= 4; $k++) {
                    if (!isset($lines[$i + $k])) break;
                    $candidate = trim($lines[$i + $k]);

                    if (preg_match('/^(1\.1\.2|naturaleza|encargo|objetivos|localizaci[óo]n|reseña|cuadro|figura|geogr[áa]fica|descripci[óo]n|diagn[óo]stico)/ui', $candidate)) break;
                    if (preg_match('/^(1\.1\.1|nombre\s+de\s+la\s+comunidad)/ui', $candidate)) continue;

                    if (mb_strlen($candidate) > 4) {
                        $cand = self::limpiarComunidad($candidate);
                        if (self::esComunidadValida($cand)) {
                            return $cand;
                        }
                    }
                }
            }
        }

        // 3. Reconocimiento Dinámico de Entidades Nombradas por Patrón Gramatical
        $fullText = implode("\n", array_slice($lines, 0, 280));
        $pattern = '/\b((?:Escuela\s+T[ée]cnica|Escuela|Unidad\s+Educativa|Liceo|Colegio|Instituto|Centro|Departamento|Coordinaci[óo]n|Consejo\s+Comunal|Concejo\s+Municipal|Prefectura|Corporaci[óo]n|Compañ[íi]a|Empresa|Cl[íi]nica|Farmacia|Laboratorio|Taller|Fundaci[óo]n|Asociaci[óo]n|Servicio|Cooperativa)\s+(?:[A-ZÁÉÍÓÚÑa-záéíóúñ0-9\x{201c}\x{201d}“"\'\.\-–\(\)]+\s*){1,12})/u';

        if (preg_match_all($pattern, $fullText, $matches)) {
            foreach ($matches[1] as $match) {
                $cleaned = self::limpiarComunidad($match);
                if (self::esComunidadValida($cleaned)) {
                    return $cleaned;
                }
            }
        }

        return 'Comunidad / Organización No Específicamente Nombrada';
    }

    private static function esComunidadValida(string $cand): bool {
        if (mb_strlen($cand) < 4) return false;
        if (preg_match('/(PAGEREF|_Toc|\.\.\.\.)/i', $cand)) return false;
        if (preg_match('/^(descripci[óo]n\s+del|diagn[óo]stico|seg[úu]n\s+conceptos|estado,\s*municipio|el\s+nombre\s+legal|rep[úu]blica|ministerio|misi[óo]n|visi[óo]n|registro\s+de\s+informaci[óo]n\s+fiscal)/ui', $cand)) {
            return false;
        }
        return true;
    }

    private static function limpiarComunidad(string $text): string {
        $text = preg_replace('/^(?:[a-z]\.\s*)?(?:1\.1\.1\.?|nombre\s+de\s+la\s+comunidad(?:\s+u\s+organizaci[óo]n)?\.?|localizaci[óo]n[\-\s]+geogr[áa]fica:?\s*-?\s*|raz[óo]n\s+social:?)\s*/ui', '', $text);
        $text = preg_replace('/^(?:la\s+sociedad\s+se\s+denomina,?\s*|la\s+raz[óo]n\s+social\s+de\s+(?:la\s+empresa\s+es\s*)?|la\s+comunidad\s+institucional\s+seleccionada\s+para\s+el\s+desarrollo\s+del\s+presente\s+proyecto\s+es\s+el|la\s+comunidad\s+seleccionada\s+es\s+la|la\s+instituci[óo]n\s+educativa,\s+conocida\s+como\s*|se\s+desarrolla\s+en\s+el|ubicado\s+en)\s*/ui', '', $text);

        // Cortar si hay guiones largos separadores o RIF o texto explicativo largo ("se centra en ofrecer...", "se encuentra en una situación...")
        $text = preg_replace('/\s*(?:\/?\s*RIF\b.*|\-{3,}.*|\bse\s+(?:centra|encuentra|dedica|ubica)\s+en\b.*|\best[áa]\s+abierta\s+al\b.*)$/ui', '', $text);
        // Cortar sufijos geográficos redundantes al final si vienen del título (", MUNICIPIO VALERA, ESTADO TRUJILLO")
        $text = preg_replace('/,?\s*(?:municipio\s+valera,?\s*)?(?:valera,?\s*)?estado\s+trujillo\.?$/ui', '', $text);

        if (preg_match('/^([^,\n]+(?:,\s*(?:C\.?A\.?|S\.?A\.?|F\.?P\.?|R\.?L\.?))?)/ui', $text, $m)) {
            $text = $m[1];
        }

        if (mb_strlen($text) > 150) {
            $text = mb_substr($text, 0, 150);
        }

        return trim($text, " \t\r\n\:-.,;");
    }

    /**
     * Extrae el título de la investigación limpiando cintillos universitarios, de núcleo y etiquetas de participantes.
     */
    private static function extraerTitulo(array $lines, string $rawText = '', string $fileName = ''): string {
        $maxWords = 42;
        $maxChars = 260;

        // Regex de cabeceras institucionales / cintillos que NUNCA forman parte del título
        $headerRegex = '/^(?:rep[úu]blica|ariana\s+de\s+venezuela|ministerio|universitaria,?\s+ciencia|ciencia\s+y\s+tecnolog[íi]a|universidad\s+polit[ée]cnica|“?mario\s+briceño\s+iragorry”?|programa\s+nacional|\(programa\s+nacional|pnf\s+en\s+inform[áa]tica|n[úu]cleo|nues\.?\s+dr|nuestro\s+dr|dr\.\s+pablo\s+viloria|valera\s*[,\-–]?\s*(?:estado|edo)|coordinaci[óo]n|proyecto\s+socio[\-\s]*tecnol[óo]gico\s*(?:i|ii|iii|iv|1|2|3|4)?\s*$|\-?\s*\d+\s*\-?$)/ui';

        // Regex de inicio de bloque de autores/tutores que marca el FIN del título en la portada
        $stopAuthorsRegex = '/^\s*(?:autores|estudiantes|bachilleres|autor|estudiante|bachiller|participantes|integrantes|presentado\s+por|creado\s+por|realizado\s+por|elaborado\s+por|docente\s+asesor|tutor(?:a)?|profesor\s+asesor|trayecto|trimestre|secci[óo]n)\b/ui';

        // Heurística A: Buscar líneas entre el final del cintillo universitario y el inicio de Autores/Participantes
        $autoresIdx = -1;
        for ($idx = 0; $idx < min(count($lines), 45); $idx++) {
            $line = trim($lines[$idx]);
            if (preg_match($stopAuthorsRegex, $line)) {
                $autoresIdx = $idx;
                break;
            }
        }

        if ($autoresIdx > 0) {
            $tituloLines = [];
            for ($i = $autoresIdx - 1; $i >= 0; $i--) {
                $line = trim($lines[$i]);
                if ($line === '') continue;

                // Si la línea contiene pegados "Participantes:" o "Integrantes:" al final, cortarlos antes
                $line = preg_replace('/\s+\b(?:participantes|integrantes|autores|realizado\s+por)\s*:?.*$/ui', '', $line);

                if (preg_match($headerRegex, $line)) {
                    if (!empty($tituloLines)) {
                        break;
                    }
                    continue;
                }
                array_unshift($tituloLines, $line);
            }

            if (!empty($tituloLines)) {
                $cand = self::limpiarTitulo(implode(' ', $tituloLines), $maxChars);
                if (mb_strlen($cand) >= 18 && !preg_match('/^(mario\s+briceño|universidad|programa\s+nacional|trimestre|secci[óo]n|ciencia\s+y\s+tecnolog)/ui', $cand)) {
                    return $cand;
                }
            }
        }

        // Heurística B: Buscar en el Resumen frases como: 'proyecto titulado "..."'
        if (preg_match('/(?:proyecto(?:\s+socio[\-\s]*tecnol[óo]gico)?\s+titulado|trabajo\s+titulado)\s+[“"«]([^”"»]{18,240})[”"»]/ui', $rawText, $tm)) {
            $cand = self::limpiarTitulo($tm[1], $maxChars);
            if (mb_strlen($cand) >= 18) {
                return $cand;
            }
        }

        // Heurística C: Escanear primeras 35 líneas buscando el primer bloque sustantivo de proyecto
        $candidates = [];
        $wordsCount = 0;
        for ($i = 0; $i < min(count($lines), 35); $i++) {
            $line = trim($lines[$i]);
            if (preg_match($headerRegex, $line)) {
                if (!empty($candidates)) break;
                continue;
            }
            if (preg_match($stopAuthorsRegex, $line)) {
                break;
            }
            if (mb_strlen($line) >= 12) {
                $lineWords = count(explode(' ', $line));
                if ($wordsCount + $lineWords > $maxWords) break;
                $candidates[] = $line;
                $wordsCount += $lineWords;
            }
        }

        if (!empty($candidates)) {
            $tituloCand = self::limpiarTitulo(implode(' ', $candidates), $maxChars);
            if (mb_strlen($tituloCand) >= 18) {
                return $tituloCand;
            }
        }

        // Fallback: nombre del archivo limpio
        if (!empty($fileName)) {
            $cleanFileName = pathinfo($fileName, PATHINFO_FILENAME);
            $cleanFileName = preg_replace('/[_\-]+/', ' ', $cleanFileName);
            $cleanFileName = preg_replace('/\s*\(\d+\)\s*/', ' ', $cleanFileName);
            return trim(ucfirst($cleanFileName));
        }

        return 'Proyecto Socio-Tecnológico Sin Título Detectado';
    }

    /**
     * Limpia el texto del título removiendo cintillos residuales de núcleo/ubicación y nombres de participantes colados.
     */
    private static function limpiarTitulo(string $titulo, int $maxChars = 260): string {
        $titulo = preg_replace('/\s+/u', ' ', $titulo);
        $titulo = trim($titulo, " \t\r\n\:-.,;“”\"'");

        // Eliminar cintillos de núcleo pegados al inicio del título
        $cintillos = [
            '/^“?n[úu]cleo\s+universitario\s+“?dr\.?\s+pablo\s+viloria”?\s*[\-–]?\s*la\s+beatriz\.?\s*/ui',
            '/^nues(?:tro)?\.?\s+dr\.?\s+pablo\s+viloria\s*[\-–]?\s*la\s+beatriz\.?\s*/ui',
            '/^dr\.?\s+pablo\s+viloria\s*[\-–]?\s*la\s+beatriz\.?\s*/ui',
            '/^\(?programa\s+nacional\s+de\s+formaci[óo]n\s+en\s+inform[áa]tica\)?\s*/ui',
            '/^plan\s+nacional\s+de\s+formaci[óo]n\s+en\s+inform[áa]tica\s*/ui',
            '/^proyecto\s+socio[\-\s]*tecnol[óo]gico\s*(?:i|ii|iii|iv|1|2|3|4)?\s*/ui',
            '/^\d{6,}\s*/u'
        ];
        foreach ($cintillos as $pat) {
            $titulo = preg_replace($pat, '', $titulo);
        }

        // Cortar si se coló "Participantes:", "Integrantes:", "Autores:" o números de cédula al final del título
        $titulo = preg_replace('/\s+\b(?:participantes|integrantes|autores|realizado\s+por|tutor|docente\s+asesor)\s*:?.*$/ui', '', $titulo);

        // Eliminar prefijos de ubicación sueltos al inicio
        $patternGeo = '/^\s*(?:valera|trujillo|la\s+beatriz)\s*(?:,|\s+|-|–)*\s*(?:estado|edo\.?|municipio)?\s*(?:trujillo)?\s*(?:\d{4}|enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre)?\s*/ui';
        for ($k = 0; $k < 2; $k++) {
            if (preg_match($patternGeo, $titulo)) {
                $titulo = preg_replace($patternGeo, '', $titulo);
            }
        }

        // Corregir palabras partidas por PDF (ej: "M EDICINA" -> "MEDICINA")
        $titulo = preg_replace('/\bM\s+EDICINA\b/u', 'MEDICINA', $titulo);

        $titulo = trim($titulo, " \t\r\n\:-.,;“”\"'");

        if (mb_strlen($titulo) > $maxChars) {
            $titulo = mb_substr($titulo, 0, $maxChars);
            $lastSpace = mb_strrpos($titulo, ' ');
            if ($lastSpace !== false) {
                $titulo = mb_substr($titulo, 0, $lastSpace);
            }
            $titulo .= '...';
        }

        return $titulo;
    }

    /**
     * Extrae un año coherente (2018-2027) en la portada o nombre de archivo.
     */
    private static function extraerAnio(string $text, string $fileName = ''): int {
        $coverText = mb_substr($text, 0, 3500);
        if (preg_match_all('/\b(201[8-9]|202[0-7])\b/', $coverText, $matches)) {
            return (int) end($matches[1]);
        }
        if (preg_match('/\b(201[8-9]|202[0-7])\b/', $fileName, $fm)) {
            return (int) $fm[1];
        }
        return (int) date('Y');
    }

    /**
     * Extrae el resumen buscando encabezados formales ("RESUMEN", "RESUMEN DEL PROYECTO", "MEMORIA DESCRIPTIVA", "SÍNTESIS") o fallback narrativo.
     */
    private static function extraerResumen(string $text, array $lines = []): string {
        $count = count($lines);

        // 1. Buscar encabezado formal de Resumen en las primeras 350 líneas
        for ($i = 0; $i < min($count, 350); $i++) {
            $line = trim($lines[$i]);

            if (preg_match('/^\s*(?:res[úu]men(?:\s+del\s+proyecto|\s+ejecutivo)?|s[íi]ntesis|memoria\s+descriptiva)\s*\.?\s*:?\s*$/ui', $line)) {
                if ($i + 1 < $count && preg_match('/(\.\.\.\.\.|PAGEREF\s+_Toc)/i', $lines[$i + 1])) continue;
                if ($i + 1 < $count && preg_match('/^\s*(introducci[óo]n|parte\s+i|cap[íi]tulo|[íi]ndice)/ui', trim($lines[$i + 1]))) continue;

                $bodyLines = [];
                for ($j = $i + 1; $j < min($count, $i + 40); $j++) {
                    $l = trim($lines[$j]);
                    if ($l === '') continue;

                    if (preg_match('/^(palabras\s+claves?|descriptores?|keywords?|introducci[óo]n|[íi]ndice\s+general|parte\s+i\b|cap[íi]tulo\s+i\b)/ui', $l)) {
                        break;
                    }
                    // Omitir líneas de metadatos repetidos dentro del encabezado del resumen (Autores:, Tutor:, Fecha:)
                    if (count($bodyLines) === 0 && preg_match('/^(autores?|tutor|docente\s+asesor|representante|fecha|valera,\s*\w+\s*\d{4})/ui', $l)) {
                        continue;
                    }

                    $bodyLines[] = $l;
                }

                if (!empty($bodyLines)) {
                    $resumen = implode(" ", $bodyLines);
                    $resumen = preg_replace('/\s+/u', ' ', $resumen);
                    $resumen = preg_replace('/\b(palabras\s+claves?|keywords?|descriptores?)\s*:?.*$/ui', '', $resumen);
                    $resumen = trim($resumen, " \t\r\n\:-.,");
                    if (mb_strlen($resumen) >= 60) {
                        return $resumen;
                    }
                }
            }
        }

        // 2. Fallback Inteligente: Reconstruir bloque narrativo de propósito / introducción
        if (!empty($lines)) {
            $inBlock = false;
            $narrativeLines = [];

            for ($i = 0; $i < min($count, 400); $i++) {
                $l = trim($lines[$i]);
                if (preg_match('/(\.\.\.\.\.|PAGEREF\s+_Toc)/i', $l)) continue;

                if (preg_match('/(el\s+prop[óo]sito\s+principal\s+de\s+este\s+proyecto|el\s+proyecto\s+socio[\-\s]*tecnol[óo]gico\s+realizado\s+en|el\s+presente\s+proyecto\s+(?:socio[\-\s]*tecnol[óo]gico\s+)?tiene\s+como\s+(?:prop[óo]sito|finalidad|objetivo)|el\s+presente\s+proyecto\s+se\s+desarrolla|el\s+presente\s+trabajo\s+de\s+investigaci[óo]n\s+abord[óo]|este\s+proyecto\s+de\s+investigaci[óo]n,?\s+inscrito\s+en|este\s+proyecto\s+consiste\s+en\s+el\s+diseño)/ui', $l)) {
                    $inBlock = true;
                }

                if ($inBlock) {
                    if ($l === '' || preg_match('/^(palabras\s+claves?|descriptores?|parte\s+i\b|introducci[óo]n|[íi]ndice|diagn[óo]stico|1\.1)/ui', $l)) {
                        break;
                    }
                    $narrativeLines[] = $l;
                    if (count($narrativeLines) >= 8) break;
                }
            }

            if (!empty($narrativeLines)) {
                $res = implode(" ", $narrativeLines);
                $res = preg_replace('/\s+/u', ' ', $res);
                $res = preg_replace('/\b(palabras\s+claves?|keywords?|descriptores?)\s*:?.*$/ui', '', $res);
                return trim($res, " \t\r\n\:-.,");
            }

            // 3. Fallback Genérico para párrafos descriptivos en las primeras 160 líneas
            for ($i = 12; $i < min($count, 160); $i++) {
                $l = trim($lines[$i]);
                if (mb_strlen($l) >= 110 && mb_strlen($l) <= 1200) {
                    if (preg_match('/(investigaci[óo]n|proyecto|sistema|aplicaci[óo]n\s+web|propuesta|desarrollo|diagn[óo]stico)/ui', $l)) {
                        if (!preg_match('/(cuadro|tabla|figura|[íi]ndice|matriz|p[áa]gina|hoja|rep[úu]blica|ministerio|universidad|\.\.\.\.|PAGEREF)/ui', $l)) {
                            return $l;
                        }
                    }
                }
            }
        }

        return '';
    }

    /**
     * Extrae palabras clave después del tag "Palabras Clave", "Descriptores" o "Keywords".
     */
    private static function extraerPalabrasClave(string $text, array $lines = []): string {
        if (preg_match('/(?:palabras\s+claves?|descriptores|keywords?)\s*:?\s*([^\.\r\n]{4,180})/ui', $text, $matches)) {
            $kw = trim($matches[1], " \t\r\n\:-.,;");
            if (!preg_match('/(PAGEREF|\.\.\.\.)/i', $kw)) {
                return $kw;
            }
        }
        return '';
    }

    /**
     * Extrae personas del documento (autores y tutores) soportando múltiples cédulas por línea, tablas y prefijos académicos.
     */
    private static function extraerPersonas(array $lines): array {
        $maxAutores = 5;
        if (class_exists('ConfigService')) {
            $maxAutores = max(1, (int)ConfigService::get('limites_equipo.max_autores', 5));
        }

        $autores = [];
        $tutores = [
            'academico'     => ['nombre' => '', 'cedula' => ''],
            'institucional' => ['nombre' => '', 'cedula' => ''],
            'comunitario'   => ['nombre' => '', 'cedula' => '']
        ];

        $linesCover = array_slice($lines, 0, 120);

        // Pre-procesamiento: expandir líneas que contengan múltiples etiquetas de tutor o múltiples autores con tabuladores
        $expandedLines = [];
        foreach ($linesCover as $rawLine) {
            // Separar si hay múltiples etiquetas de tutor pegadas en la misma línea (ej: "Docente Asesor: X Tutor Comunitario: Y")
            $splitTutorLabels = preg_replace('/(?<!^)\s*(?=(?:docente\s+asesor|tutor(?:a)?\s+(?:acad[eé]mico|institucional|comunitario|del\s+pst)|representante\s+(?:institucional|comunitario|estudiantil)|profesor\s+asesor)\s*:)/ui', "\n", $rawLine);
            foreach (explode("\n", $splitTutorLabels) as $subL) {
                $subL = trim($subL);
                if ($subL !== '') {
                    $expandedLines[] = $subL;
                }
            }
        }

        $processedLines = [];
        $count = count($expandedLines);
        for ($i = 0; $i < $count; $i++) {
            $line = trim($expandedLines[$i]);
            // Unir etiqueta de tutor solitaria con su valor en la línea siguiente (solo si la línea siguiente NO es una fecha ni otra etiqueta)
            if (preg_match('/^(?:docente\s+asesor|tutor(?:a)?(?:\s+[a-záéíóúñ]+)*|asesor(?:a)?(?:\s+[a-záéíóúñ]+)*|representante(?:\s+[a-záéíóúñ]+)*|profesor\s+asesor)\b\s*:?\s*$/ui', $line) && $i + 1 < $count) {
                $nextLine = trim($expandedLines[$i + 1]);
                if (!preg_match('/^(?:enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre|valera|trujillo|\d{4}|tutor|docente|representante|autores)/ui', $nextLine)) {
                    $line .= ': ' . $nextLine;
                    $i++;
                }
            }
            $processedLines[] = $line;
        }

        $cedulaRegex = '/\b(?:V|E|C\.?I\.?|V\.?I\.?|C[eé]dula)?[-.:]?\s*(\d{1,2}\.\d{3}\.\d{3}|\d{7,9})\b/ui';

        // 1. Extraer por coincidencia de cédula en la portada (soportando múltiples personas en la misma línea)
        foreach ($processedLines as $index => $line) {
            // Ignorar "Representante Estudiantil:" o líneas con RIF empresarial ("RIF.: J-40583752-7")
            if (preg_match('/^\s*representante\s+estudiantil\b|\bRIF\b|(?:J|G)\s*[\-–]\s*\d{7,9}/ui', $line)) {
                continue;
            }

            if (preg_match_all($cedulaRegex, $line, $allMatches, PREG_OFFSET_CAPTURE)) {
                $numMatches = count($allMatches[0]);
                for ($mIdx = 0; $mIdx < $numMatches; $mIdx++) {
                    $fullMatchStr = $allMatches[0][$mIdx][0];
                    $matchOffset  = $allMatches[0][$mIdx][1];
                    $rawDigits    = $allMatches[1][$mIdx][0];

                    $cedulaLimpia = preg_replace('/\D/', '', $rawDigits);
                    if (strlen($cedulaLimpia) < 7 || strlen($cedulaLimpia) > 9) continue;

                    $prefijoCed = (stripos($fullMatchStr, 'E-') === 0 || stripos($fullMatchStr, 'E.') === 0 || stripos($fullMatchStr, 'E ') === 0) ? 'E-' : 'V-';

                    // Si hay varias cédulas en la misma línea, extraer el segmento de texto entre la cédula anterior y la actual
                    if ($numMatches > 1) {
                        $prevEnd = ($mIdx === 0) ? 0 : ($allMatches[0][$mIdx - 1][1] + strlen($allMatches[0][$mIdx - 1][0]));
                        $segment = substr($line, $prevEnd, $matchOffset - $prevEnd);
                        $nombreCandidato = self::limpiarNombreLinea($segment, '');
                    } else {
                        $nombreCandidato = self::limpiarNombreLinea($line, $fullMatchStr);
                        if (mb_strlen($nombreCandidato) < 4 && $index > 0) {
                            $prevLine = $processedLines[$index - 1];
                            $nombreCandidato = self::limpiarNombreLinea($prevLine, '');
                            // Si el nombre estaba partido en 2 líneas anteriores (ej: "Nombres y Apellidos: Jesús Francisco" + "Montilla Olmos")
                            if ($index > 1 && mb_strlen($nombreCandidato) < 12 && !preg_match($cedulaRegex, $processedLines[$index - 2])) {
                                $prevPrev = self::limpiarNombreLinea($processedLines[$index - 2], '');
                                if (mb_strlen($prevPrev) >= 3 && mb_strlen($prevPrev) <= 25) {
                                    $nombreCandidato = trim($prevPrev . ' ' . $nombreCandidato);
                                }
                            }
                        }
                    }

                    if (mb_strlen($nombreCandidato) >= 4) {
                        $esTutor = false;
                        $tipoTutor = 'academico';

                        for ($k = max(0, $index - 2); $k <= $index; $k++) {
                            if (isset($processedLines[$k])) {
                                $ctx = mb_strtolower($processedLines[$k]);
                                if (preg_match('/\b(tutor(?:a)?|asesor(?:a)?|representante\s+(?:institucional|comunitario|organizacional))\b/ui', $ctx)) {
                                    // Si en la misma línea dice "Autores:" o "Participantes:", no es tutor
                                    if ($numMatches > 1 && preg_match('/^\s*(?:autores|participantes|integrantes)\b/ui', $line)) {
                                        break;
                                    }
                                    $esTutor = true;
                                    if (mb_strpos($ctx, 'inst') !== false) {
                                        $tipoTutor = 'institucional';
                                    } elseif (mb_strpos($ctx, 'comun') !== false || mb_strpos($ctx, 'líder') !== false || mb_strpos($ctx, 'lider') !== false || mb_strpos($ctx, 'org') !== false) {
                                        $tipoTutor = 'comunitario';
                                    }
                                    break;
                                }
                            }
                        }

                        if ($esTutor) {
                            if (empty($tutores[$tipoTutor]['cedula'])) {
                                $tutores[$tipoTutor] = ['nombre' => $nombreCandidato, 'cedula' => $prefijoCed . $cedulaLimpia];
                            }
                        } else {
                            $yaExiste = false;
                            foreach ($autores as $aut) {
                                if ($aut['cedula'] === $prefijoCed . $cedulaLimpia || mb_strtolower($aut['nombre']) === mb_strtolower($nombreCandidato)) {
                                    $yaExiste = true;
                                    break;
                                }
                            }
                            if (!$yaExiste && count($autores) < $maxAutores) {
                                $autores[] = ['nombre' => $nombreCandidato, 'cedula' => $prefijoCed . $cedulaLimpia];
                            }
                        }
                    }
                }
            }
        }

        // 2. Extraer tutores por etiquetas estructuradas
        $tutorPatterns = [
            'academico'     => '/(?:docente\s+asesor|tutor(?:a)?\s+acad[eé]mico|tutor(?:a)?\s+asesor|tutor\(a\)\s+acad[eé]mico|tutor(?:a)?\s+del\s+pst|profesor\s+asesor|asesor\s+acad[eé]mico|asesor\s+metodol[oó]gico|tutor\s+metodol[oó]gico|tutor(?!\s*(?:institucional|comunitario|organizacional|empresarial)))\s*:\s*(.+)/ui',
            'institucional' => '/(?:representante\s+institucional|tutor(?:a)?\s+institucional|tutor\(a\)\s+institucional|asesor\s+institucional|tutor\s+empresarial|asesor\s+empresarial)\s*:\s*(.+)/ui',
            'comunitario'   => '/(?:representante\s+comunitario|tutor(?:a)?\s+comunitario|tutor\(a\)\s+comunitario|representante\s+organizacional|asesor\s+comunitario)\s*:\s*(.+)/ui'
        ];

        foreach ($processedLines as $line) {
            foreach ($tutorPatterns as $tipo => $pattern) {
                if (empty($tutores[$tipo]['nombre'])) {
                    if (preg_match($pattern, $line, $matches)) {
                        $rawCandidate = trim($matches[1]);
                        $cedEncontrada = '';
                        if (preg_match($cedulaRegex, $rawCandidate, $cedM)) {
                            $cedLimpia = preg_replace('/\D/', '', $cedM[1]);
                            if (strlen($cedLimpia) >= 6 && strlen($cedLimpia) <= 9) {
                                $tipoCed = (stripos($cedM[0], 'E-') === 0 || stripos($cedM[0], 'E.') === 0) ? 'E-' : 'V-';
                                $cedEncontrada = $tipoCed . $cedLimpia;
                            }
                        }
                        $nombre = self::limpiarNombreLinea($rawCandidate, $cedEncontrada);
                        if (mb_strlen($nombre) >= 4 && !self::esMesOFecha($nombre)) {
                            $tutores[$tipo] = ['nombre' => $nombre, 'cedula' => $cedEncontrada];
                        }
                    }
                }
            }
        }

        // 3. Extraer autores sin cédula (si aún faltan)
        if (count($autores) === 0) {
            $inAutoresSection = false;
            foreach ($processedLines as $line) {
                if (preg_match('/^\s*(?:autores|estudiantes|bachilleres|autor|estudiante|bachiller|participantes|integrantes)\b/ui', $line)) {
                    $inAutoresSection = true;
                    continue;
                }
                if ($inAutoresSection) {
                    if (preg_match('/^(?:docente|tutor|representante|profesor|res[úu]men|introducci[óo]n|[íi]ndice|valera|trayecto|trimestre|secci[óo]n|202[0-9])/ui', $line)) {
                        $inAutoresSection = false;
                        break;
                    }
                    $nombreCandidate = self::limpiarNombreLinea($line, '');
                    if (mb_strlen($nombreCandidate) >= 5 && !self::esMesOFecha($nombreCandidate) && count($autores) < $maxAutores) {
                        $autores[] = ['nombre' => $nombreCandidate, 'cedula' => ''];
                    }
                }
            }
        }

        $autores = array_slice($autores, 0, $maxAutores);
        while (count($autores) < $maxAutores) {
            $autores[] = ['nombre' => '', 'cedula' => ''];
        }

        return ['autores' => $autores, 'tutores' => $tutores];
    }

    private static function esMesOFecha(string $str): bool {
        return (bool)preg_match('/^(?:enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|setiembre|octubre|noviembre|diciembre|valera|trujillo)$/ui', trim($str));
    }

    private static function limpiarNombreLinea(string $line, string $cedulaMatch): string {
        if ($cedulaMatch !== '') {
            $line = str_ireplace($cedulaMatch, '', $line);
        }
        // Cortar cualquier etiqueta secundaria que haya quedado a la derecha
        $line = preg_replace('/\b(?:representante\s+\w+|tutor(?:a)?\s+\w+|docente\s+asesor|secci[óo]n|trayecto|trimestre)\b.*$/ui', '', $line);

        // Remover títulos académicos y cargos comunes en las portadas PST (incluyendo T.S.U. con puntos)
        $line = preg_replace('/\bt\.?\s*s\.?\s*u\.?\b/ui', ' ', $line);
        $prefixesRegex = '/\b(ingeniera|ingeniero|t[ée]cnico\s+superior(?:\s+universitario)?|administraci[óo]n\s+rrhh|ing|lic|lcdo|lcda|dr|dra|prof|profa|tsu|tec|msc|m\.sc|esp|abg|econ)\.?(?:\s*\([a-z]\))?\b/ui';
        $line = preg_replace($prefixesRegex, ' ', $line);

        $wordsToRemove = [
            'estudiante', 'estudiantes', 'bachiller', 'bachilleres', 'participante', 'participantes', 'integrante', 'integrantes',
            'autor', 'autores', 'tutor', 'tutora', 'tutores', 'asesor', 'asesora', 'pst', 'del', 'tsu',
            'docente', 'representante', 'comunitario', 'institucional', 'académico', 'academico', 'estudiantil',
            'nombre', 'nombres', 'apellido', 'apellidos', 'c.i', 'ci', 'v-', 'v.', 'e-', 'cedula', 'cédula'
        ];

        $cleaned = mb_strtolower($line);
        foreach ($wordsToRemove as $word) {
            $cleaned = preg_replace('/\b' . preg_quote($word, '/') . '\b/ui', ' ', $cleaned);
        }

        $cleaned = str_replace([':', '-', '=', '/', '(', ')'], ' ', $cleaned);
        $cleaned = preg_replace('/[^a-záéíóúñ\s]/u', '', $cleaned);
        $cleaned = preg_replace('/\s+/u', ' ', $cleaned);
        $cleaned = trim($cleaned);

        if (self::esMesOFecha($cleaned)) {
            return '';
        }

        return ucwords($cleaned);
    }

    /**
     * Clasifica el documento bajo su Línea de Investigación y Dimensión Operativa real deduciendo su naturaleza
     * técnica a partir de:
     * 1) Título, Objetivo General, Resumen y Palabras Clave (alta prioridad semántica).
     * 2) Apartado de Línea de Investigación / Parte II del documento.
     * 3) Distinción estricta entre Edumática (software educativo/enseñanza) vs Sistemas de Información en escuelas/liceos (gestión administrativa/académica/matrícula).
     */
    private static function clasificarLineaYDimension(
        string $text,
        string $titulo = '',
        string $objGeneral = '',
        string $palabrasClave = '',
        string $resumen = ''
    ): array {
        $coreMeta = mb_strtolower($titulo . ' ' . $objGeneral . ' ' . $palabrasClave . ' ' . $resumen);
        $textLower = mb_strtolower($text);

        // 1. Detectar si es genuinamente EDUMÁTICA (Línea 8)
        // Solo es Edumática si el propósito es educativo/didáctico/enseñanza-aprendizaje, NO si es gestión administrativa/inscripción/control de notas de un liceo
        $esEnsenanzaDidactica = preg_match('/(fortalecimiento\s+del\s+aprendizaje|enseñanza[\-\s]+aprendizaje|software\s+educativo|plataforma\s+educativa|entorno\s+interactivo\s+de\s+enseñanza|juego\s+did[áa]ctico|gu[íi]a\s+de\s+estudio|objeto\s+de\s+aprendizaje|e-learning|aula\s+virtual|aprendizaje\s+de\s+castellano|aprendizaje\s+de\s+matem[áa]tica)/ui', $coreMeta);
        $esGestionAdministrativa = preg_match('/(gesti[óo]n\s+administrativa|proceso\s+administrativo|control\s+de\s+matr[íi]cula|registro\s+acad[ée]mico\s+y\s+administrativo|gesti[óo]n\s+y\s+control\s+de\s+datos|gesti[óo]n\s+escolar|control\s+de\s+inventario|gesti[óo]n\s+de\s+pacientes|bienes\s+nacionales|administraci[óo]n\s+de\s+casos|talento\s+humano|facturaci[óo]n|n[óo]mina|certificaci[óo]n)/ui', $coreMeta);

        if ($esEnsenanzaDidactica && !$esGestionAdministrativa) {
            $lineaGanadora = 8; // EDUMATICA
            // Determinar dimensión de Edumática (10..15)
            $dimScores = [
                10 => 1, // Software educativo (base)
                11 => 0, // Guías de estudio web
                12 => 0, // Tutoriales
                13 => 0, // Juegos didácticos
                14 => 0, // Entornos interactivos de enseñanza
                15 => 0  // Sistemas e-learning
            ];
            if (preg_match('/(plataforma\s+educativa|entorno\s+interactivo|interactiva|castellano\s+y\s+literatura)/ui', $coreMeta)) $dimScores[14] += 5;
            if (preg_match('/(juego|l[úu]dico|gamificaci[óo]n)/ui', $coreMeta)) $dimScores[13] += 5;
            if (preg_match('/(gu[íi]a\s+de\s+estudio|material\s+instruccional)/ui', $coreMeta)) $dimScores[11] += 5;
            if (preg_match('/(tutorial)/ui', $coreMeta)) $dimScores[12] += 5;
            if (preg_match('/(e-learning|elearning|educaci[óo]n\s+a\s+distancia)/ui', $coreMeta)) $dimScores[15] += 5;
            if (preg_match('/(software\s+educativo|medio\s+did[áa]ctico)/ui', $coreMeta)) $dimScores[10] += 4;

            arsort($dimScores);
            return [
                'linea_id'     => 8,
                'dimension_id' => key($dimScores)
            ];
        }

        // 2. Detectar si es REDES Y TELECOMUNICACIONES (Línea 10)
        if (preg_match('/\b(redes\s+y\s+telecomunicaciones|radio\s+enlace|fibra\s+[óo]ptica|infraestructura\s+de\s+red|red\s+lan|red\s+wan|topolog[íi]a\s+de\s+red|telecomunicaciones|transmisi[óo]n\s+de\s+datos|mikrotik|cisco)\b/ui', $coreMeta)) {
            $dimId = preg_match('/(simulaci[óo]n|packet\s+tracer|gns3)/ui', $coreMeta . ' ' . $textLower) ? 18 : 19;
            return [
                'linea_id'     => 10,
                'dimension_id' => $dimId
            ];
        }

        // 3. SISTEMAS DE INFORMACIÓN Y MODELADO DE DATOS (Línea 7)
        // En el repositorio institucional, los Sistemas de Información (tanto Web como Tradicionales y Geográficos) pertenecen a la Línea 7
        $lineaGanadora = 7;

        // Analizar si es Sistema de Información Tradicional (Dimensión 5) vs Sistema de Información Web (Dimensión 7)
        // Indicadores explícitos de Sistema de Información Tradicional (Dimensión 5):
        $esTradicionalExplicito = (bool)preg_match('/(sistemas?\s+de\s+informaci[óo]n\s+tradicional(?:es)?)/ui', $textLower);

        // Casos donde el proyecto es un Sistema de Información Tradicional / Interno (Dimensión 5):
        $esPatronTradicional = (bool)preg_match('/(gestor\s+administrativo\s+de\s+certificaci[óo]n|gacpnfi|sistema\s+integrado\s+para\s+la\s+gesti[óo]n\s+de\s+los\s+procesos\s+administrativos\s+para\s+el\s+centro\s+inmobiliario|gesti[óo]n\s+administrativa\s+de\s+gominova|taller\s+automotriz\s+inversiones|concejo\s+municipal\s+de\s+valera)/ui', $coreMeta . ' ' . $textLower);

        if ($esTradicionalExplicito || $esPatronTradicional) {
            $dimensionGanadora = 5; // Sistemas de información tradicionales
        } else {
            $dimensionGanadora = 7; // Sistemas de información web
        }

        return [
            'linea_id'     => $lineaGanadora,
            'dimension_id' => $dimensionGanadora
        ];
    }

    /**
     * Elimina acentos y normaliza texto a minúsculas.
     */
    private static function normalizarTextoBajo(string $text): string {
        $normalized = mb_strtolower($text);
        
        $map = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'ü' => 'u', 'ñ' => 'n'
        ];
        
        return strtr($normalized, $map);
    }
}
