<?php
// modules/RepositorioPST/services/ExtractorPST.php

class ExtractorPST {

    /**
     * Extrae texto de un archivo PDF usando la librería Smalot\PdfParser con configuración de descompresión de memoria.
     * Incluye fallback de seguridad contra archivos encriptados o truncos.
     */
    public static function extraerTextoPDF(string $filePath): string {
        if (!file_exists($filePath)) {
            throw new Exception("El archivo PDF especificado no existe.");
        }
        
        try {
            ini_set('memory_limit', '512M');
            $config = new \Smalot\PdfParser\Config();
            $config->setDecodeMemoryLimit(100 * 1024 * 1024);
            
            $parser = new \Smalot\PdfParser\Parser([], $config);
            $pdf = $parser->parseFile($filePath);
            return $pdf->getText() ?? '';
        } catch (\Throwable $e) {
            error_log("Error al extraer texto PDF: " . $e->getMessage());
            return ''; // Retornar vacío en lugar de romper la ejecución
        }
    }

    /**
     * Extrae texto de un archivo Word (.docx) leyendo directamente el XML interno.
     */
    public static function extraerTextoDOCX(string $filePath): string {
        if (!file_exists($filePath)) {
            throw new Exception("El archivo Word especificado no existe.");
        }

        $zip = new \ZipArchive();
        if ($zip->open($filePath) === true) {
            $index = $zip->locateName('word/document.xml');
            if ($index !== false) {
                $data = $zip->getFromIndex($index);
                $zip->close();
                
                $dom = new \DOMDocument();
                libxml_use_internal_errors(true);
                $dom->loadXML($data);
                libxml_clear_errors();

                $xpath = new \DOMXPath($dom);
                $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');

                $nodes = $xpath->query('//w:p');
                $lines = [];
                foreach ($nodes as $node) {
                    // Capturar tanto texto <w:t> como saltos de línea suaves <w:br>
                    $childElements = $xpath->query('.//w:t | .//w:br', $node);
                    $pText = '';
                    foreach ($childElements as $elem) {
                        if ($elem->nodeName === 'w:br') {
                            $pText .= "\n";
                        } else {
                            $pText .= $elem->nodeValue;
                        }
                    }
                    $subLines = explode("\n", $pText);
                    foreach ($subLines as $sl) {
                        $trimmed = trim($sl);
                        if ($trimmed !== '') {
                            $lines[] = $trimmed;
                        }
                    }
                }
                return implode("\n", $lines);
            }
            $zip->close();
        }
        throw new Exception("No se pudo leer la estructura del archivo Word (.docx) o no es válido.");
    }

    /**
     * Parsea el texto extraído del documento para obtener los metadatos relevantes.
     */
    public static function analizarTexto(string $text, string $fileName = ''): array {
        $lines = explode("\n", $text);
        $cleanLines = [];
        foreach ($lines as $l) {
            $trimmed = trim($l);
            if ($trimmed !== '') {
                $cleanLines[] = $trimmed;
            }
        }

        // 1. Extraer Título (Heurística refinada con saltos de línea y fallback de nombre de archivo)
        $titulo = self::extraerTitulo($cleanLines, $text, $fileName);

        // 2. Extraer Año (Heurística)
        $anio = self::extraerAnio($text);

        // 3. Extraer Resumen
        $resumen = self::extraerResumen($text, $cleanLines);

        // 4. Extraer Palabras Clave
        $palabrasClave = self::extraerPalabrasClave($text);

        // 5. Extraer Comunidad u Objeto Beneficiario
        $comunidad = self::extraerComunidad($text, $cleanLines);

        // 6. Extraer Cédulas, Autores y Tutores
        $personas = self::extraerPersonas($cleanLines);

        // Filter out empty author slots
        $autoresFiltrados = [];
        foreach ($personas['autores'] as $aut) {
            if (!empty(trim($aut['nombre'])) || !empty(trim($aut['cedula']))) {
                $autoresFiltrados[] = $aut;
            }
        }

        // 7. Clasificación de Línea de Investigación y Dimensión
        $clasificacion = self::clasificarLineaYDimension($text);

        // 8. Extraer Objetivo General del documento
        $objGeneral = self::extraerObjetivoGeneral($text, $cleanLines);

        return [
            'titulo' => $titulo,
            'anio_publicacion' => $anio,
            'resumen' => $resumen,
            'obj_general' => $objGeneral,
            'palabras_clave' => $palabrasClave,
            'comunidad_beneficiada' => $comunidad,
            'autores' => $autoresFiltrados,
            'tutor_academico_nombre' => $personas['tutores']['academico']['nombre'] ?? '',
            'tutor_academico_cedula' => $personas['tutores']['academico']['cedula'] ?? '',
            'tutor_institucional_nombre' => $personas['tutores']['institucional']['nombre'] ?? '',
            'tutor_institucional_cedula' => $personas['tutores']['institucional']['cedula'] ?? '',
            'tutor_comunitario_nombre' => $personas['tutores']['comunitario']['nombre'] ?? '',
            'tutor_comunitario_cedula' => $personas['tutores']['comunitario']['cedula'] ?? '',
            'linea_id' => $clasificacion['linea_id'],
            'dimension_id' => $clasificacion['dimension_id']
        ];
    }

    /**
     * Extrae de forma precisa el Objetivo General del documento descartando objetivos específicos y guías.
     */
    private static function extraerObjetivoGeneral(string $text, array $lines): string {
        $count = count($lines);

        // 1. Búsqueda por encabezados formales: "Objetivo General", "1.3.1. Objetivo General", "3.1. Objetivo General", etc.
        for ($i = 0; $i < min($count, 700); $i++) {
            $line = trim($lines[$i]);

            // Omitir líneas de tabla de contenido con puntos suspensivos continuos (ej: Objetivos.....30)
            if (preg_match('/\.\.\.\.\./', $line)) continue;

            if (preg_match('/^\s*(?:\d+(?:\.\d+)*\.?\s*)?(?:objetivo\s+general|propósito\s+general|objetivo\s+del\s+proyecto)\s*:?\s*\.?\s*$/ui', $line) ||
                preg_match('/^\s*(?:\d+(?:\.\d+)*\.?\s*)?(?:objetivo\s+general|propósito\s+general|objetivo\s+del\s+proyecto)\b/ui', $line)) {

                // Caso A: El texto del objetivo general está en la misma línea después de los dos puntos
                if (preg_match('/:\s*(.+)/u', $line, $m)) {
                    $cand = trim($m[1]);
                    if (mb_strlen($cand) > 15 && preg_match('/^[a-záéíóúñ\s]*\b(desarrollar|implementar|diseñar|crear|proporcionar|fortalecer|construir|evaluar|optimizar|analizar|elaborar|instalar|automatizar|proponer|realizar|brindar)\b/ui', $cand)) {
                        return self::limpiarObjetivoGeneral($cand);
                    }
                }

                // Caso B: El texto del objetivo está en la línea o líneas inmediatas siguientes
                $objLines = [];
                for ($k = 1; $k <= 10; $k++) {
                    if (!isset($lines[$i + $k])) break;
                    $candLine = trim($lines[$i + $k]);

                    if ($candLine === '') continue;
                    if (preg_match('/\.\.\.\.\./', $candLine)) break;

                    // Si se encuentra con Objetivos Intermedios / Específicos o siguiente sub-sección, detener
                    if (preg_match('/^\s*(?:\d+(?:\.\d+)*\.?\s*)?(?:objetivos?\s+(?:espec[íi]ficos?|intermedios?)|1\.\d+\.\d+\.\d+|3\.2|2\.2|cuadro\s+\d+|fundamentación|justificación|marco)/ui', $candLine)) {
                        break;
                    }

                    // Omitir definiciones teóricas de la guía de proyectos si las hay
                    if (preg_match('/^(es\s+el\s+fin\s+último|según|debe\s+ser\s+redactado|representan\s+los\s+pasos|son\s+las\s+metas)/ui', $candLine)) {
                        continue;
                    }

                    $objLines[] = $candLine;
                    $fullTemp = implode(' ', $objLines);
                    // Si ya acumuló una oración completa terminada en punto
                    if (mb_strlen($fullTemp) > 30 && preg_match('/\.\s*$/u', $candLine)) {
                        break;
                    }
                }

                if (!empty($objLines)) {
                    $res = implode(' ', $objLines);
                    if (mb_strlen($res) > 15) {
                        return self::limpiarObjetivoGeneral($res);
                    }
                }
            }
        }

        // 2. Fallback Heurístico (Analizar primeras 500 líneas buscando oración con verbo en infinitivo de logro)
        for ($i = 0; $i < min($count, 500); $i++) {
            $line = trim($lines[$i]);
            if (preg_match('/\.\.\.\.\./', $line)) continue;
            if (preg_match('/^(índice|tabla\s+de\s+contenido|república|ministerio|universidad|autor|tutor|fortalecer\s+nuestra\s+sociedad)/ui', $line)) continue;

            if (preg_match('/^\s*(?:\d+\.\s*)?\b(desarrollar|implementar|diseñar|crear|proporcionar|fortalecer|construir|evaluar|optimizar|analizar|elaborar|instalar|automatizar|proponer|realizar|brindar)\b/ui', $line)) {
                // Evitar viñetas de objetivos específicos
                if (preg_match('/^\s*(?:[\•\-\*\¬]|1\.|2\.|3\.|4\.|5\.|a\)|b\)|c\))\s+/ui', $line)) {
                    continue;
                }
                if (mb_strlen($line) >= 40 && mb_strlen($line) <= 600) {
                    return self::limpiarObjetivoGeneral($line);
                }
            }
        }

        return '';
    }

    private static function limpiarObjetivoGeneral(string $str): string {
        $str = preg_replace('/^\s*(?:\d+(?:\.\d+)*\.?\s*)?(?:objetivo\s+general|propósito\s+general|objetivo\s+del\s+proyecto)\s*:?\s*/ui', '', $str);
        $str = preg_replace('/\s+/u', ' ', $str);
        return trim($str, " \t\r\n\:-");
    }

    /**
     * Extrae dinámicamente el nombre de la comunidad, organización o institución beneficiada.
     */
    private static function extraerComunidad(string $text, array $lines): string {
        $count = count($lines);

        // 1. Búsqueda por encabezados formales de sección (ej: 1.1.1, Nombre de la Comunidad, Razón Social, Empresa / Organización)
        for ($i = 0; $i < $count; $i++) {
            $line = trim($lines[$i]);

            if (preg_match('/(1\.1\.1|nombre\s+de\s+la\s+comunidad|comunidad\s+u\s+organización|razón\s+social|empresa\s*\/\s*organización|organización\s+beneficiada|comunidad\s+beneficiada)/ui', $line)) {

                // Ignorar si pertenece a la Tabla de Contenidos del Índice
                if (preg_match('/[a-záéíóúñ\s]*\d+\s*$/ui', $line) && !preg_match('/1\.1\.1\./', $line)) {
                    continue;
                }
                if ($i + 1 < $count && preg_match('/1\.1\.2|naturaleza\s+de\s+la\s+organización/ui', trim($lines[$i + 1]))) {
                    continue;
                }

                // Si el nombre está en la misma línea después de los dos puntos
                if (preg_match('/:\s*(.+)/u', $line, $m) && mb_strlen(trim($m[1])) > 6) {
                    return self::limpiarComunidad($m[1]);
                }

                // Si está en las líneas siguientes
                for ($k = 1; $k <= 4; $k++) {
                    if (!isset($lines[$i + $k])) break;
                    $candidate = trim($lines[$i + $k]);

                    if (preg_match('/^(1\.1\.2|naturaleza|encargo|objetivos|localización|reseña|cuadro|figura|geográfica)/ui', $candidate)) break;
                    if (preg_match('/^(1\.1\.1|nombre\s+de\s+la\s+comunidad)/ui', $candidate)) continue;

                    if (mb_strlen($candidate) > 8) {
                        return self::limpiarComunidad($candidate);
                    }
                }
            }
        }

        // 2. Reconocimiento Dinámico de Entidades Nombradas por Patrón Gramatical (NLP Heurístico)
        $fullText = implode("\n", array_slice($lines, 0, 200));
        $pattern = '/\b((?:Escuela|Unidad Educativa|Liceo|Colegio|Instituto|Centro|Departamento|Coordinación|Consejo Comunal|Comité|Corporación|Compañía|Empresa|Clínica|Fundación|Asociación|Servicio|Sociedad|S\.A\.|C\.A\.)\s+(?:[A-ZÁÉÍÓÚÑ0-9\x{201c}\x{201d}“"\'\.\-–\(\)]+\s*){2,12})/u';

        if (preg_match_all($pattern, $fullText, $matches)) {
            foreach ($matches[1] as $match) {
                $cleaned = self::limpiarComunidad($match);
                if (mb_strlen($cleaned) > 10 && !preg_match('/(república|ministerio|universidad politécnica|programa nacional)/ui', $cleaned)) {
                    return $cleaned;
                }
            }
        }

        return 'Comunidad / Organización No Específicamente Nombrada';
    }

    private static function limpiarComunidad(string $text): string {
        $text = preg_replace('/^(1\.1\.1\.?|nombre\s+de\s+la\s+comunidad\s+u\s+organización\.?|localización-geográfica:?\s*-?\s*|razón\s+social:?)\s*/ui', '', $text);
        $text = preg_replace('/^(la\s+comunidad\s+institucional\s+seleccionada\s+para\s+el\s+desarrollo\s+del\s+presente\s+proyecto\s+es\s+el|la\s+comunidad\s+seleccionada\s+es\s+la|la\s+institución\s+educativa,\s+conocida\s+como\s*|se\s+desarrolla\s+en\s+el|ubicado\s+en)\s*/ui', '', $text);

        if (preg_match('/^([^,\.\n]+(?:,\s*[^,\.\n]+){0,2})/u', $text, $m)) {
            $text = $m[1];
        }

        if (mb_strlen($text) > 160) {
            $text = mb_substr($text, 0, 160);
        }

        return trim($text, " \t\r\n\:-.,");
    }

    /**
     * Busca las primeras líneas con formato de título (mayúsculas largas, obviando cabeceras universitarias).
     */
    private static function extraerTitulo(array $lines, string $rawText = '', string $fileName = ''): string {
        $maxWords = 40;       // Límite razonable de palabras para un título de proyecto
        $maxChars = 260;      // Límite de caracteres

        // Heurística A: Si encontramos el encabezado "AUTORES", "PRESENTADO POR", etc.
        $autoresIdx = -1;
        foreach ($lines as $idx => $line) {
            if (preg_match('/^\s*(autores|estudiantes|bachilleres|autor|estudiante|bachiller|presentado por|creado por|tutor)\b/ui', $line)) {
                $autoresIdx = $idx;
                break;
            }
        }

        if ($autoresIdx !== -1) {
            $tituloLines = [];
            for ($i = $autoresIdx - 1; $i >= 0; $i--) {
                $line = trim($lines[$i]);
                if ($line === '') continue;

                // Si encontramos cabecera institucional o universitaria, detemos la subida hacia arriba
                if (preg_match('/^(república|republica|ministerio|universidad|programa nacional|pnf|núcleo|nucleo|valera\s*–|coordinación|trayecto)/ui', $line)) {
                    break;
                }
                array_unshift($tituloLines, $line);
            }

            if (!empty($tituloLines)) {
                $cand = trim(implode(' ', $tituloLines));
                if (mb_strlen($cand) >= 15 && count(explode(' ', $cand)) <= $maxWords) {
                    return self::limpiarTitulo($cand, $maxChars);
                }
            }
        }

        // Heurística B: Analizar saltos de línea originales del texto base (preservando párrafos reales)
        if (!empty($rawText)) {
            // Dividir por saltos de línea dobles o múltiples (que marcan cambio de bloque / párrafo)
            $blocks = preg_split('/\n\s*\n/', $rawText);
            foreach ($blocks as $block) {
                $cleanBlockLines = array_filter(array_map('trim', explode("\n", $block)));
                if (empty($cleanBlockLines)) continue;

                $blockText = implode(' ', $cleanBlockLines);

                // Omitir bloques institucional/universitarios o datos de autor/fecha
                if (preg_match('/(república|republica|ministerio|universidad politécnica|programa nacional|núcleo|autores|tutor|valera,\s*\d{4})/ui', $blockText)) {
                    continue;
                }

                $words = explode(' ', $blockText);
                // Si el bloque tiene una longitud coherente para un título
                if (mb_strlen($blockText) >= 15 && count($words) <= $maxWords) {
                    return self::limpiarTitulo($blockText, $maxChars);
                }
            }
        }

        // Heurística C: Recorrer líneas limpias aisladas (máximo las primeras 30 líneas)
        $candidates = [];
        $wordsCount = 0;

        for ($i = 0; $i < min(count($lines), 30); $i++) {
            $line = $lines[$i];

            if (preg_match('/^(república|republica|ministerio|universidad|programa nacional|pnf|núcleo|nucleo|valera|autores|tutor|docente|cedula|c\.i)/ui', $line)) {
                if (!empty($candidates)) {
                    // Si ya veníamos acumulando líneas y nos topamos con autores/universidad, paramos aquí
                    break;
                }
                continue;
            }

            if (mb_strlen($line) >= 12) {
                $lineWords = count(explode(' ', $line));
                if ($wordsCount + $lineWords > $maxWords) {
                    break; // Evitar que siga absorbiendo texto excesivo
                }
                $candidates[] = $line;
                $wordsCount += $lineWords;
            }
        }

        if (!empty($candidates)) {
            $tituloCand = implode(' ', $candidates);
            if (mb_strlen($tituloCand) >= 15) {
                return self::limpiarTitulo($tituloCand, $maxChars);
            }
        }

        // Heurística Fallback: Si todo lo demás falla o extrajo basura, usar el nombre del archivo sin extensión
        if (!empty($fileName)) {
            $cleanFileName = pathinfo($fileName, PATHINFO_FILENAME);
            // Reemplazar guiones o guiones bajos por espacios y capitalizar
            $cleanFileName = preg_replace('/[_\-]+/', ' ', $cleanFileName);
            return trim(mb_ucfirst($cleanFileName));
        }

        return 'Proyecto Socio-Tecnológico Sin Título Detectado';
    }

    /**
     * Limpia el texto del título y trunca si excede el límite seguro.
     */
    private static function limpiarTitulo(string $titulo, int $maxChars = 260): string {
        $titulo = preg_replace('/\s+/u', ' ', $titulo);
        $titulo = trim($titulo, " \t\r\n\:-.,");

        if (mb_strlen($titulo) > $maxChars) {
            $titulo = mb_substr($titulo, 0, $maxChars);
            // Cortar en la última palabra completa
            $lastSpace = mb_strrpos($titulo, ' ');
            if ($lastSpace !== false) {
                $titulo = mb_substr($titulo, 0, $lastSpace);
            }
            $titulo .= '...';
        }

        return $titulo;
    }

    /**
     * Extrae un año coherente (2018-2026) en la portada.
     */
    private static function extraerAnio(string $text): int {
        $coverText = mb_substr($text, 0, 3000);
        if (preg_match_all('/\b(201[8-9]|202[0-7])\b/', $coverText, $matches)) {
            return (int) end($matches[1]);
        }
        return (int) date('Y');
    }

    /**
     * Extrae el resumen buscando la sección "RESUMEN" / "RESÚMEN" real (descartando tablas de contenido e índices) o mediante fallback narrativo.
     */
    private static function extraerResumen(string $text, array $lines = []): string {
        $count = count($lines);

        // 1. Buscar encabezado formal RESÚMEN / RESUMEN por línea en las primeras 300 líneas
        for ($i = 0; $i < min($count, 300); $i++) {
            $line = trim($lines[$i]);

            if (preg_match('/^\s*(resúmen|resumen|síntesis|sintesis)\s*:?\s*$/ui', $line)) {
                if ($i + 1 < $count && preg_match('/\.\.\.\.\./', $lines[$i + 1])) continue; // Índice de puntos
                if ($i + 1 < $count && preg_match('/^\s*(introducción|parte i|capítulo)/ui', trim($lines[$i + 1]))) continue;

                $bodyLines = [];
                for ($j = $i + 1; $j < min($count, $i + 35); $j++) {
                    $l = trim($lines[$j]);
                    if ($l === '') continue;

                    if (preg_match('/^(palabras\s+claves?|keywords?|introducción|introduccion|parte\s+i|capítulo)/ui', $l)) {
                        break;
                    }

                    $bodyLines[] = $l;
                }

                if (!empty($bodyLines)) {
                    $resumen = implode(" ", $bodyLines);
                    $resumen = preg_replace('/\s+/u', ' ', $resumen);
                    $resumen = preg_replace('/\b(palabras\s+claves?|keywords?|descriptores?)\s*:?.*$/ui', '', $resumen);
                    return trim($resumen, " \t\r\n\:-.,");
                }
            }
        }

        // 2. Fallback Inteligente: Reconstruir bloque narrativo de propósito / objetivo / diagnóstico si no existe encabezado RESUMEN
        if (!empty($lines)) {
            $inBlock = false;
            $narrativeLines = [];

            for ($i = 0; $i < min($count, 350); $i++) {
                $l = trim($lines[$i]);
                if (preg_match('/\.\.\.\.\./', $l)) continue; // Omitir líneas de índice general

                if (preg_match('/(el\s+propósito\s+principal\s+de\s+este\s+proyecto|el\s+proyecto\s+socio\s+tecnológico\s+realizado\s+en|el\s+presente\s+proyecto\s+tiene\s+como\s+propósito|el\s+presente\s+proyecto\s+se\s+desarrolla|una\s+descripción\s+de\s+proyectos\s+es|dicha\s+investigación\s+se\s+enfoca|esta\s+investigación\s+se\s+centra)/ui', $l)) {
                    $inBlock = true;
                }

                if ($inBlock) {
                    if ($l === '' || preg_match('/^(palabras\s+claves?|parte\s+i|introducción|diagnóstico|1\.1)/ui', $l)) {
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

            // 3. Fallback Genérico para párrafos descriptivos en las primeras 120 líneas
            for ($i = 20; $i < min($count, 120); $i++) {
                $l = trim($lines[$i]);
                if (mb_strlen($l) >= 110 && mb_strlen($l) <= 900) {
                    if (preg_match('/(investigación|proyecto|sistema|propuesta|desarrollo|matrícula|atención|diagnóstico)/ui', $l)) {
                        if (!preg_match('/(cuadro|tabla|figura|índice|matriz|página|hoja|república|ministerio|universidad|\.\.\.\.)/ui', $l)) {
                            return $l;
                        }
                    }
                }
            }
        }

        return '';
    }

    /**
     * Extrae palabras clave después del tag "Palabras Clave"
     */
    private static function extraerPalabrasClave(string $text): string {
        if (preg_match('/palabras\s+claves?\s*:?\s*([^\.\r\n]+)/ui', $text, $matches)) {
            return trim($matches[1]);
        }
        if (preg_match('/keywords?\s*:?\s*([^\.\r\n]+)/ui', $text, $matches)) {
            return trim($matches[1]);
        }
        return '';
    }

    /**
     * Extrae personas del documento (autores y tutores) mapeando cédulas y nombres en la portada.
     */
    private static function extraerPersonas(array $lines): array {
        $autores = [];
        $tutores = [
            'academico' => ['nombre' => '', 'cedula' => ''],
            'institucional' => ['nombre' => '', 'cedula' => ''],
            'comunitario' => ['nombre' => '', 'cedula' => '']
        ];

        // Acotar la búsqueda a la portada y páginas preliminares (primeras 120 líneas)
        $linesCover = array_slice($lines, 0, 120);

        $processedLines = [];
        $count = count($linesCover);
        for ($i = 0; $i < $count; $i++) {
            $line = $linesCover[$i];
            if (preg_match('/^(docente\s+asesor|tutor|representante)\b.*:$/ui', $line) && $i + 1 < $count) {
                $line .= ' ' . $linesCover[$i + 1];
                $i++;
            }
            $processedLines[] = $line;
        }

        // Regex mejorado para soportar cédulas de Word y PDF (ej: V-30.601.065, C.I. 30.671.594, V.I: 30.601.065, C.I: 30671594, 30.601.065)
        $cedulaRegex = '/\b(?:V|E|C\.?I\.?|V\.?I\.?)?[-.:]?\s*(\d{1,2}\.?\d{3}\.?\d{3})\b/i';

        // 1. Extraer por coincidencia de cédula en la portada
        foreach ($processedLines as $index => $line) {
            if (preg_match_all($cedulaRegex, $line, $allMatches, PREG_SET_ORDER)) {
                foreach ($allMatches as $matches) {
                    $cedulaLimpia = preg_replace('/\D/', '', $matches[1]);
                    if (strlen($cedulaLimpia) < 7 || strlen($cedulaLimpia) > 9) continue;

                    $nombreCandidato = self::limpiarNombreLinea($line, $matches[0]);
                    if (mb_strlen($nombreCandidato) < 4 && $index > 0) {
                        $nombreCandidato = self::limpiarNombreLinea($processedLines[$index - 1], '');
                    }

                    if (mb_strlen($nombreCandidato) >= 4) {
                        $esTutor = false;
                        $tipoTutor = 'academico';

                        for ($k = max(0, $index - 3); $k <= $index; $k++) {
                            if (isset($processedLines[$k])) {
                                $ctx = mb_strtolower($processedLines[$k]);
                                if (mb_strpos($ctx, 'tutor') !== false || mb_strpos($ctx, 'asesor') !== false || mb_strpos($ctx, 'representante') !== false) {
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
                                $tutores[$tipoTutor] = ['nombre' => $nombreCandidato, 'cedula' => 'V-' . $cedulaLimpia];
                            }
                        } else {
                            $yaExiste = false;
                            foreach ($autores as $aut) {
                                if ($aut['cedula'] === 'V-' . $cedulaLimpia || mb_strtolower($aut['nombre']) === mb_strtolower($nombreCandidato)) {
                                    $yaExiste = true;
                                    break;
                                }
                            }
                            if (!$yaExiste && count($autores) < 4) {
                                $autores[] = ['nombre' => $nombreCandidato, 'cedula' => 'V-' . $cedulaLimpia];
                            }
                        }
                    }
                }
            }
        }

        // 2. Extraer tutores por etiquetas estructuradas
        $tutorPatterns = [
            'academico'     => '/(?:docente\s+asesor|tutor\s+acad[eé]mico|tutor\s+asesor|tutor\(a\)\s+acad[eé]mico|profesor\s+asesor)\s*:\s*(.+)/ui',
            'institucional' => '/(?:representante\s+institucional|tutor\s+institucional|tutor\(a\)\s+institucional)\s*:\s*(.+)/ui',
            'comunitario'   => '/(?:representante\s+comunitario|tutor\s+comunitario|tutor\(a\)\s+comunitario|representante\s+organizacional)\s*:\s*(.+)/ui'
        ];

        foreach ($processedLines as $line) {
            foreach ($tutorPatterns as $tipo => $pattern) {
                if (empty($tutores[$tipo]['nombre'])) {
                    if (preg_match($pattern, $line, $matches)) {
                        $nombre = self::limpiarNombreLinea($matches[1], '');
                        if (mb_strlen($nombre) >= 4) {
                            $tutores[$tipo] = ['nombre' => $nombre, 'cedula' => ''];
                        }
                    }
                }
            }
        }

        // 3. Extraer autores sin cédula (si aún faltan)
        if (count($autores) < 4) {
            $inAutoresSection = false;
            foreach ($processedLines as $line) {
                if (preg_match('/^\s*(autores|estudiantes|bachilleres|autor|estudiante|bachiller)\b/ui', $line)) {
                    $inAutoresSection = true;
                    continue;
                }
                if ($inAutoresSection) {
                    if (preg_match('/^(docente|tutor|representante|profesor|resúmen|resumen|introducción|índice|valera|febrero|julio|mayo|202[0-9])/ui', $line)) {
                        $inAutoresSection = false;
                        break;
                    }
                    $nombreCandidate = self::limpiarNombreLinea($line, '');
                    if (mb_strlen($nombreCandidate) >= 5 && count($autores) < 4) {
                        $yaExiste = false;
                        foreach ($autores as $aut) {
                            if (mb_strtolower($aut['nombre']) === mb_strtolower($nombreCandidate)) {
                                $yaExiste = true; break;
                            }
                        }
                        if (!$yaExiste) {
                            $autores[] = ['nombre' => $nombreCandidate, 'cedula' => ''];
                        }
                    }
                }
            }
        }

        while (count($autores) < 4) {
            $autores[] = ['nombre' => '', 'cedula' => ''];
        }

        return ['autores' => $autores, 'tutores' => $tutores];
    }

    private static function limpiarNombreLinea(string $line, string $cedulaMatch): string {
        if ($cedulaMatch !== '') {
            $line = str_ireplace($cedulaMatch, '', $line);
        }
        $prefixesRegex = '/\b(ing|lic|dr|dra|prof|profa|tsu|t\.s\.u\.)\.?\b/ui';
        $line = preg_replace($prefixesRegex, '', $line);

        $wordsToRemove = [
            'estudiante', 'estudiantes', 'bachiller', 'bachilleres',
            'autor', 'autores', 'tutor', 'tutores', 'asesor', 'asesora',
            'representante', 'comunitario', 'institucional', 'académico', 'academico',
            'nombre', 'nombres', 'apellido', 'apellidos', 'c.i', 'ci', 'v-', 'v.', 'e-', 'cedula', 'cédula'
        ];

        $cleaned = mb_strtolower($line);
        foreach ($wordsToRemove as $word) {
            $cleaned = preg_replace('/\b' . preg_quote($word, '/') . '\b/ui', '', $cleaned);
        }

        $cleaned = str_replace([':', '-', '=', '/'], ' ', $cleaned);
        $cleaned = preg_replace('/[^a-záéíóúñ\s]/u', '', $cleaned);
        $cleaned = preg_replace('/\s+/u', ' ', $cleaned);
        return ucwords(trim($cleaned));
    }

    /**
     * Clasifica el documento bajo una Línea de Investigación y Dimensión Operativa en base al contenido.
     */
    private static function clasificarLineaYDimension(string $text): array {
        $textLower = mb_strtolower($text);

        // Puntuaciones de las Líneas
        $lineas = [
            7 => 0, // Sistemas de Información
            8 => 0, // Edumática
            9 => 0, // Aplicaciones Web
            10 => 0 // Redes y Telecomunicaciones
        ];

        // Palabras clave de Líneas
        $keywordsLineas = [
            7 => ['sistema de información', 'sistemas de información', 'sistema de informacion', 'sistemas de informacion', 'base de datos', 'modelado de datos', 'gestión tecnológica', 'gestion tecnologica', 'inventario', 'auditoría'],
            8 => ['edumática', 'edumatica', 'software educativo', 'didáctico', 'didactico', 'juegos didácticos', 'juegos didacticos', 'guías de estudio', 'guias de estudio', 'tutoriales', 'e-learning', 'elearning', 'enseñanza', 'aprendizaje', 'escuela', 'colegio', 'liceo', 'estudiantil'],
            9 => ['aplicaciones web', 'aplicación web', 'aplicacion web', 'desarrollo web', 'servicios web', 'cliente-servidor', 'cliente servidor', 'sitio web', 'página web', 'pagina web', 'php', 'javascript', 'html', 'css'],
            10 => ['redes y telecomunicaciones', 'redes', 'telecomunicaciones', 'transmisión de datos', 'transmision de datos', 'simulación', 'simulacion', 'enlaces fijos', 'antena', 'router', 'switch', 'cisco', 'conectividad']
        ];

        foreach ($keywordsLineas as $lineaId => $kws) {
            foreach ($kws as $kw) {
                $count = mb_substr_count($textLower, $kw);
                $lineas[$lineaId] += ($count * 2); // Peso por coincidencia
            }
        }

        // Obtener el ID de la línea ganadora
        arsort($lineas);
        $lineaGanadora = key($lineas);
        $maxScore = current($lineas);

        if ($maxScore == 0) {
            $lineaGanadora = 7; // Default a Sistemas de Información
        }

        // Puntuaciones de Dimensiones dentro de la línea ganadora
        $dimensiones = [];
        $keywordsDimensiones = [];

        if ($lineaGanadora == 7) {
            $dimensiones = [5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0];
            $keywordsDimensiones = [
                5 => ['tradicionales', 'inventario', 'expedientes', 'procesos', 'manuales'],
                6 => ['geográficas', 'geograficas', 'hipermapa', 'mapa', 'gps', 'ubicación'],
                7 => ['web', 'internet', 'intranet', 'portal'],
                8 => ['colaborativo', 'colaborativos', 'experiencias', 'ideas'],
                9 => ['auditoría', 'auditoria', 'control', 'calidad', 'instalación']
            ];
        } elseif ($lineaGanadora == 8) {
            $dimensiones = [10 => 0, 11 => 0, 12 => 0, 13 => 0, 14 => 0, 15 => 0];
            $keywordsDimensiones = [
                10 => ['software educativo', 'medio didáctico', 'didactico', 'didáctico'],
                11 => ['guía de estudio', 'guia de estudio', 'instruccional'],
                12 => ['tutorial', 'tutoriales'],
                13 => ['juego', 'juegos', 'lúdico', 'ludico'],
                14 => ['interactivo', 'entornos interactivos'],
                15 => ['e-learning', 'elearning', 'a distancia', 'virtual']
            ];
        } elseif ($lineaGanadora == 9) {
            $dimensiones = [16 => 0, 17 => 0];
            $keywordsDimensiones = [
                16 => ['cliente-servidor', 'cliente servidor', 'distribuido'],
                17 => ['servicios de integración', 'integración de aplicaciones', 'integracion', 'api']
            ];
        } elseif ($lineaGanadora == 10) {
            $dimensiones = [18 => 0, 19 => 0];
            $keywordsDimensiones = [
                18 => ['simulación', 'simulacion', 'modelizar', 'test'],
                19 => ['transmisión de datos', 'comunicación por cable', 'radio enlaces', 'satelital']
            ];
        }

        foreach ($keywordsDimensiones as $dimId => $kws) {
            foreach ($kws as $kw) {
                $count = mb_substr_count($textLower, $kw);
                $dimensiones[$dimId] += $count;
            }
        }

        $dimensionGanadora = null;
        if (!empty($dimensiones)) {
            arsort($dimensiones);
            $dimensionGanadora = key($dimensiones);
            if (current($dimensiones) == 0) {
                // Seleccionar la primera dimensión por defecto del grupo
                $dimensionGanadora = key($keywordsDimensiones);
            }
        }

        return [
            'linea_id' => $lineaGanadora,
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
