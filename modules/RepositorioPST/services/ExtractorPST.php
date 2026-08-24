<?php
// modules/RepositorioPST/services/ExtractorPST.php

class ExtractorPST {

    /**
     * Extrae texto de un archivo PDF usando la librería Smalot\PdfParser.
     */
    public static function extraerTextoPDF(string $filePath): string {
        if (!file_exists($filePath)) {
            throw new Exception("El archivo PDF especificado no existe.");
        }
        
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($filePath);
        return $pdf->getText();
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
                
                // Cargar XML y desarmar etiquetas de Word
                $xml = new \SimpleXMLElement($data);
                $namespaces = $xml->getNamespaces(true);
                $wNamespace = $namespaces['w'] ?? 'http://schemas.openxmlformats.org/wordprocessingml/2006/main';
                $xml->registerXPathNamespace('w', $wNamespace);
                
                $textElements = $xml->xpath('//w:t');
                $text = '';
                foreach ($textElements as $element) {
                    $text .= (string)$element . "\n";
                }
                return $text;
            }
            $zip->close();
        }
        throw new Exception("No se pudo leer la estructura del archivo Word (.docx) o no es válido.");
    }

    /**
     * Parsea el texto extraído del documento para obtener los metadatos relevantes.
     */
    public static function analizarTexto(string $text): array {
        $lines = explode("\n", $text);
        $cleanLines = [];
        foreach ($lines as $l) {
            $trimmed = trim($l);
            if ($trimmed !== '') {
                $cleanLines[] = $trimmed;
            }
        }

        // 1. Extraer Título (Heurística)
        $titulo = self::extraerTitulo($cleanLines);

        // 2. Extraer Año (Heurística)
        $anio = self::extraerAnio($text);

        // 3. Extraer Resumen
        $resumen = self::extraerResumen($text);

        // 4. Extraer Palabras Clave
        $palabrasClave = self::extraerPalabrasClave($text);

        // 5. Extraer Cedulas, Autores y Tutores
        $personas = self::extraerPersonas($cleanLines);

        // 6. Clasificación de Línea de Investigación y Dimensión
        $clasificacion = self::clasificarLineaYDimension($text);

        return [
            'titulo' => $titulo,
            'anio_publicacion' => $anio,
            'resumen' => $resumen,
            'palabras_clave' => $palabrasClave,
            'autores' => $personas['autores'],
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
     * Busca las primeras líneas con formato de título (mayúsculas largas, obviando cabeceras universitarias).
     */
    private static function extraerTitulo(array $lines): string {
        $autoresIdx = -1;
        foreach ($lines as $idx => $line) {
            if (preg_match('/\b(autores|estudiantes|bachilleres|autor|estudiante|bachiller)\b/ui', $line)) {
                $autoresIdx = $idx;
                break;
            }
        }

        if ($autoresIdx !== -1) {
            // Buscar hacia atrás la última línea que contenga la ubicación (Valera, Trujillo, Edo, etc.)
            $lastHeaderIdx = -1;
            for ($i = $autoresIdx - 1; $i >= 0; $i--) {
                if (preg_match('/\b(valera|trujillo|edo|estado trujillo|pablo viloria|beatriz)\b/ui', $lines[$i])) {
                    $lastHeaderIdx = $i;
                    break;
                }
            }

            if ($lastHeaderIdx !== -1 && $lastHeaderIdx < $autoresIdx - 1) {
                // Las líneas entre el encabezado y "Autores" son el título
                $tituloLines = [];
                for ($j = $lastHeaderIdx + 1; $j < $autoresIdx; $j++) {
                    $tituloLines[] = $lines[$j];
                }
                return trim(implode(' ', $tituloLines));
            } else {
                // Si no se encuentra el separador geográfico, tomar las líneas no vacías inmediatamente anteriores a Autores (máximo 4 líneas)
                $tituloLines = [];
                $count = 0;
                for ($j = $autoresIdx - 1; $j >= 0; $j--) {
                    $line = trim($lines[$j]);
                    if ($line !== '') {
                        if (preg_match('/\b(república|ministerio|universidad|pnf|programa|nucleo|núcleo)\b/ui', $line)) {
                            break; // Llegó al encabezado
                        }
                        array_unshift($tituloLines, $line);
                        $count++;
                        if ($count >= 4) break;
                    }
                }
                return trim(implode(' ', $tituloLines));
            }
        }

        // Fallback al método anterior si no hay "Autores"
        $headersToSkip = [
            'república', 'republica', 'bolivariana', 'venezuela', 'ministerio', 'educación', 'educacion',
            'universitaria', 'universidad', 'politécnica', 'politecnica', 'territorial', 'uptt', 'mbi',
            'informática', 'informatica', 'programa nacional', 'pnf', 'trayecto', 'sección', 'seccion',
            'proyecto socio-tecnológico', 'proyecto socio tecnologico', 'pst', 'autores', 'tutor', 'asesor',
            'creado por', 'presentado por', 'bachiller', 'ingeniería', 'ingenieria', 'tsu'
        ];

        $candidatos = [];
        $lineCount = count($lines);
        
        for ($i = 0; $i < min($lineCount, 60); $i++) {
            $line = $lines[$i];
            
            // Verificar si contiene palabras clave de cabecera a omitir
            $skip = false;
            foreach ($headersToSkip as $word) {
                if (mb_stripos($line, $word) !== false) {
                    $skip = true;
                    break;
                }
            }
            if ($skip) {
                continue;
            }

            // Un título de proyecto suele ser largo, mayormente en mayúsculas
            $len = mb_strlen($line);
            if ($len > 15 && $len < 150) {
                // Verificar si tiene un alto porcentaje de letras mayúsculas
                $letters = preg_replace('/[^a-zA-ZÁÉÍÓÚÑ]/u', '', $line);
                $uppercase = preg_replace('/[^A-ZÁÉÍÓÚÑ]/u', '', $line);
                if (strlen($letters) > 0 && (strlen($uppercase) / strlen($letters)) > 0.8) {
                    $candidatos[] = $line;
                    // Si la siguiente línea también es mayúscula y es larga, las unimos
                    if ($i + 1 < $lineCount) {
                        $nextLine = $lines[$i + 1];
                        $nextLen = mb_strlen($nextLine);
                        $nextLetters = preg_replace('/[^a-zA-ZÁÉÍÓÚÑ]/u', '', $nextLine);
                        $nextUppercase = preg_replace('/[^A-ZÁÉÍÓÚÑ]/u', '', $nextLine);
                        if ($nextLen > 15 && strlen($nextLetters) > 0 && (strlen($nextUppercase) / strlen($nextLetters)) > 0.8) {
                            $candidatos[] = $nextLine;
                            $i++; // Saltar la siguiente
                        }
                    }
                    break; // Tomar el primer grupo de título
                }
            }
        }

        if (!empty($candidatos)) {
            return implode(' ', $candidatos);
        }

        return '';
    }

    /**
     * Extrae un año coherente (2018-2026) en la portada.
     */
    private static function extraerAnio(string $text): int {
        // Analizar los primeros 2500 caracteres (portada)
        $coverText = mb_substr($text, 0, 2500);
        if (preg_match_all('/\b(201[8-9]|202[0-7])\b/', $coverText, $matches)) {
            // El año de defensa suele estar al final de la portada, así que tomamos el último de las coincidencias de portada
            return (int) end($matches[1]);
        }
        return (int) date('Y'); // Por defecto el año actual
    }

    /**
     * Extrae el resumen buscando la sección "RESUMEN" y recortando antes de la siguiente sección.
     */
    private static function extraerResumen(string $text): string {
        // Quitar acentos para simplificar búsqueda
        $normalizedText = self::normalizarTextoBajo($text);
        
        $keywords = ['resumen', 'sintesis', 'síntesis'];
        $pos = false;
        
        foreach ($keywords as $kw) {
            $pos = mb_strpos($normalizedText, $kw);
            if ($pos !== false) {
                // Verificar que no sea parte de otra palabra (ej: resumido)
                $charBefore = $pos > 0 ? mb_substr($normalizedText, $pos - 1, 1) : ' ';
                $charAfter = mb_substr($normalizedText, $pos + mb_strlen($kw), 1);
                if (preg_match('/[\s\r\n\:]/u', $charBefore) && preg_match('/[\s\r\n\:]/u', $charAfter)) {
                    $pos += mb_strlen($kw);
                    break;
                }
                $pos = false;
            }
        }

        if ($pos !== false) {
            // Extraer a partir de la posición
            $resumenText = mb_substr($text, $pos);
            
            // Eliminar dos puntos o espacios iniciales si los hay
            $resumenText = ltrim($resumenText, " \t\r\n\:-");

            // Buscar dónde termina el resumen (normalmente palabras clave o introducción)
            $stopPatterns = [
                '/palabras\s+claves?/ui',
                '/keywords?/ui',
                '/introducción/ui',
                '/introduccion/ui',
                '/capítulo\s+i/ui',
                '/capitulo\s+i/ui'
            ];

            $minStopPos = mb_strlen($resumenText);
            foreach ($stopPatterns as $pattern) {
                if (preg_match($pattern, $resumenText, $matches, PREG_OFFSET_CAPTURE)) {
                    $offset = $matches[0][1];
                    if ($offset < $minStopPos) {
                        $minStopPos = $offset;
                    }
                }
            }

            $resumen = mb_substr($resumenText, 0, $minStopPos);
            // Limpiar saltos de línea excesivos y normalizar espacios
            $resumen = preg_replace('/\s+/u', ' ', $resumen);
            
            return trim($resumen);
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
     * Extrae personas del documento (autores y tutores) mapeando cédulas y nombres.
     */
    private static function extraerPersonas(array $lines): array {
        $autores = [];
        $tutores = [
            'academico' => ['nombre' => '', 'cedula' => ''],
            'institucional' => ['nombre' => '', 'cedula' => ''],
            'comunitario' => ['nombre' => '', 'cedula' => '']
        ];

        // Regex para cédulas de identidad venezolanas (con formato)
        $cedulaRegex = '/\b(?:V|E|C\.?I\.?)?[-.]?\s*(\d{1,2}\.?\d{3}\.?\d{3})\b/i';

        // 1. Primer paso: Extraer personas asociadas a una cédula en el texto (Autores y Tutores con Cédula)
        foreach ($lines as $index => $line) {
            if (preg_match($cedulaRegex, $line, $matches)) {
                $cedulaLimpia = preg_replace('/\D/', '', $matches[1]);
                if (strlen($cedulaLimpia) < 7 || strlen($cedulaLimpia) > 9) {
                    continue; // Cédula inválida
                }

                // Heurística de nombre: limpiar la línea de la cédula y palabras clave
                $nombreCandidato = self::limpiarNombreLinea($line, $matches[0]);
                
                // Si la línea quedó muy corta, probar la línea inmediatamente superior
                if (mb_strlen($nombreCandidato) < 5 && $index > 0) {
                    $nombreCandidato = self::limpiarNombreLinea($lines[$index - 1], '');
                }

                // Si aún así no, probar la línea inferior
                if (mb_strlen($nombreCandidato) < 5 && $index + 1 < count($lines)) {
                    $nombreCandidato = self::limpiarNombreLinea($lines[$index + 1], '');
                }

                if (mb_strlen($nombreCandidato) >= 5) {
                    // Determinar si es Tutor o Estudiante (Autor)
                    // Buscar si la palabra "tutor", "asesor" o "representante" está en la misma línea o en las 3 líneas previas
                    $esTutor = false;
                    $tipoTutor = 'academico'; // Por defecto
                    
                    for ($k = max(0, $index - 3); $k <= $index; $k++) {
                        if (isset($lines[$k])) {
                            $contextLine = mb_strtolower($lines[$k]);
                            if (mb_strpos($contextLine, 'tutor') !== false || mb_strpos($contextLine, 'asesor') !== false || mb_strpos($contextLine, 'representante') !== false) {
                                $esTutor = true;
                                if (mb_strpos($contextLine, 'inst') !== false) {
                                    $tipoTutor = 'institucional';
                                } elseif (mb_strpos($contextLine, 'comun') !== false || mb_strpos($contextLine, 'líder') !== false || mb_strpos($contextLine, 'lider') !== false) {
                                    $tipoTutor = 'comunitario';
                                }
                                break;
                            }
                        }
                    }

                    if ($esTutor) {
                        // Evitar sobreescribir si ya está lleno
                        if (empty($tutores[$tipoTutor]['cedula'])) {
                            $tutores[$tipoTutor] = [
                                'nombre' => $nombreCandidato,
                                'cedula' => 'V-' . $cedulaLimpia
                            ];
                        }
                    } else {
                        // Agregar como autor si no supera el límite de 4 y no está repetido
                        $yaExiste = false;
                        foreach ($autores as $aut) {
                            if ($aut['cedula'] === 'V-' . $cedulaLimpia) {
                                $yaExiste = true;
                                break;
                            }
                        }
                        if (!$yaExiste && count($autores) < 4) {
                            $autores[] = [
                                'nombre' => $nombreCandidato,
                                'cedula' => 'V-' . $cedulaLimpia
                            ];
                        }
                    }
                }
            }
        }

        // 2. Segundo paso: Extraer tutores por palabra clave estructurada (si no tienen cédula en el documento)
        $tutorPatterns = [
            'academico'     => '/(?:docente\s+asesor|tutor\s+acad[eé]mico|tutor\s+asesor|tutor\(a\)\s+acad[eé]mico)\s*:\s*(.+)/ui',
            'institucional' => '/(?:representante\s+institucional|tutor\s+institucional|tutor\(a\)\s+institucional)\s*:\s*(.+)/ui',
            'comunitario'   => '/(?:representante\s+comunitario|tutor\s+comunitario|tutor\(a\)\s+comunitario)\s*:\s*(.+)/ui'
        ];

        foreach ($lines as $line) {
            foreach ($tutorPatterns as $tipo => $pattern) {
                // Solo si no fue extraído previamente en la primera fase
                if (empty($tutores[$tipo]['nombre'])) {
                    if (preg_match($pattern, $line, $matches)) {
                        $nombreCompleto = trim($matches[1]);
                        if ($nombreCompleto !== '') {
                            $nombreCompleto = self::limpiarNombreLinea($nombreCompleto, '');
                            if (mb_strlen($nombreCompleto) >= 5) {
                                $tutores[$tipo] = [
                                    'nombre' => $nombreCompleto,
                                    'cedula' => '' // Cédula no especificada en portada
                                ];
                            }
                        }
                    }
                }
            }
        }

        // Si no se encontraron autores, llenar slots vacíos
        while (count($autores) < 4) {
            $autores[] = ['nombre' => '', 'cedula' => ''];
        }

        return [
            'autores' => $autores,
            'tutores' => $tutores
        ];
    }

    private static function limpiarNombreLinea(string $line, string $cedulaMatch): string {
        // 1. Quitar la coincidencia de la cédula si existe
        if ($cedulaMatch !== '') {
            $line = str_ireplace($cedulaMatch, '', $line);
        }

        // 2. Remover títulos y cargos específicos usando límites de palabra (regex)
        // Esto evita que "Alejandro" se convierta en "Alejano" por culpa de "dr"
        $prefixesRegex = '/\b(ing|lic|dr|dra|prof|profa|tsu|t\.s\.u\.)\.?\b/ui';
        $line = preg_replace($prefixesRegex, '', $line);

        // 3. Remover palabras descriptivas comunes
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

        // Remover caracteres especiales sobrantes
        $cleaned = str_replace([':', '-', '=', '/'], ' ', $cleaned);

        // Mantener solo letras y espacios de nombres (eliminamos puntos)
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
