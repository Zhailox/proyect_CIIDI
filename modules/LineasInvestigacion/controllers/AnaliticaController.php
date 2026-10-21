<?php
// modules/LineasInvestigacion/controllers/AnaliticaController.php
require_once __DIR__ . '/../../../core/Database/Connection.php';
require_once __DIR__ . '/../../SuperAdmin/services/SystemConfigService.php';
require_once __DIR__ . '/../../../core/Security/Auth.php';

class AnaliticaController {

    /** Año académico = 3 trimestres  ->  2 años = 6 trimestres, 1 año = 3 */
    private const HIST_WINDOWS = ['2y' => 6, '1y' => 3];
    private const MODELOS = [
        'linear' => 'Regresión Lineal',
        'poly'   => 'Regresión Polinómica (Grado 2)',
        'rf'     => 'Random Forest Regressor',
    ];

    private $pythonScriptPath;
    private $pythonExe = null;          // Se resuelve de forma perezosa (detectPythonExecutable)
    private bool $pythonDetectado = false;
    private $trainingDataPath;
    private int $nivelAdmin;
    private int $nivelPublico;

    public function __construct() {
        // Ruta dinámica al script de python para analítica (funciona en cualquier PC)
        $this->pythonScriptPath = realpath(__DIR__ . '/../../../storage/modelos_ia/ml_pipeline.py');
        $this->nivelAdmin   = SystemConfigService::get('accesos_modulos.lineas_investigacion.admin', 1);
        $this->nivelPublico = SystemConfigService::get('accesos_modulos.lineas_investigacion.publico', 10);

        // El ejecutable de Python ya NO se fija aquí: se detecta de forma portable
        // (PYTHON_BIN en .env -> venv del proyecto -> PATH -> rutas estándar) en detectPythonExecutable().

        // Ruta al JSON de entrenamiento
        $this->trainingDataPath = realpath(__DIR__ . '/../../../storage/modelos_ia/training_data.json');
    }

    /**
     * Endpoint para mostrar el Dashboard Analítico (Vista UI)
     */
    public function index() {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'auditar', 'LineasInvestigacion');
        return [];
    }

    /**
     * Endpoint para Proyección de Volumen y Tendencias
     */
    public function proyectarTendencias() {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'auditar', 'LineasInvestigacion');
        header('Content-Type: application/json');

        // =========================================================================
        // MODO DE DATOS: Cambia esto a 'true' si quieres volver a usar el JSON falso
        $useMockData = false;
        // =========================================================================

        try {
            $fileData = [];
            $termKeys = [];

            if ($useMockData) {
                if (!$this->trainingDataPath || !file_exists($this->trainingDataPath)) {
                    echo json_encode(["error" => "No se encontró el archivo training_data.json"]);
                    exit;
                }
                $fileData = json_decode(file_get_contents($this->trainingDataPath), true);
                $fileData = isset($fileData['areas']) ? $fileData['areas'] : $fileData;
                $maxLen = 0;
                foreach ($fileData as $a) $maxLen = max($maxLen, count($a['history'] ?? []));
                $termKeys = $this->generarTrimestresHastaActual($maxLen);
            } else {
                // Leer datos reales desde la Base de Datos PostgreSQL
                $pdo = Connection::getInstance();

                // Obtenemos todos los proyectos culminados (repositorio real) con sus fechas de defensa
                $sql = "SELECT li.nombre AS area, COALESCE(dp.fecha_defensa, dp.created_at) AS fecha_creacion 
                        FROM lineas_investigacion li
                        LEFT JOIN recurso_clasificaciones rc ON li.id = rc.id_linea_investigacion
                        LEFT JOIN recursos r ON rc.id_recurso = r.id
                        LEFT JOIN detalles_proyectos dp ON r.id = dp.id_recurso
                        ORDER BY li.nombre ASC, fecha_creacion ASC";

                $resultados = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

                if (empty($resultados)) {
                    echo json_encode(["error" => "La base de datos no tiene proyectos registrados aún para hacer proyecciones."]);
                    exit;
                }

                // Agrupar por Trimestres universitarios (T1=Ene-Abr, T2=May-Ago, T3=Sep-Dic)
                $grouped = [];
                $allTerms = [];

                foreach ($resultados as $row) {
                    $area = $row['area'];
                    if (!isset($grouped[$area])) $grouped[$area] = [];

                    // Las líneas sin proyectos llegan con fecha NULL (LEFT JOIN): se conservan
                    // como área (serie en ceros) pero NO generan un trimestre falso en 1970.
                    $timestamp = !empty($row['fecha_creacion']) ? strtotime($row['fecha_creacion']) : false;
                    if ($timestamp === false) continue;

                    $month = (int)date('m', $timestamp);
                    $year = date('Y', $timestamp);

                    // Calcular el cuatrimestre (1, 2 o 3)
                    $term = floor(($month - 1) / 4) + 1;
                    $termKey = $year . '-T' . $term;

                    if (!isset($grouped[$area][$termKey])) $grouped[$area][$termKey] = 0;

                    $grouped[$area][$termKey]++;
                    $allTerms[$termKey] = true;
                }

                // Ordenar cronológicamente todos los trimestres que existen en la BD
                $termKeysRaw = array_keys($allTerms);
                usort($termKeysRaw, function($a, $b) { return strcmp($a, $b); });

                if (empty($termKeysRaw)) {
                    echo json_encode(["error" => "Los proyectos registrados no tienen fecha de defensa/creación para construir la serie temporal."]);
                    exit;
                }

                list($minYear, $minTerm) = array_map('intval', explode('-T', $termKeysRaw[0]));
                list($maxYear, $maxTerm) = array_map('intval', explode('-T', end($termKeysRaw)));

                // La serie termina en el trimestre actual (los trimestres recientes sin
                // proyectos cuentan como 0) para que las etiquetas reflejen el presente.
                list($curYear, $curTerm) = $this->trimestreActual();
                if ($curYear * 10 + $curTerm > $maxYear * 10 + $maxTerm) {
                    $maxYear = $curYear;
                    $maxTerm = $curTerm;
                }

                // Asegurarnos de que no nos vayamos 20 años atrás si hay un dato atípico viejo
                // Forzamos que como máximo tome 3 años de histórico global
                $currentYear = (int)date('Y');
                if ($minYear < $currentYear - 3) {
                    $minYear = $currentYear - 3;
                    $minTerm = 1;
                }

                for ($y = $minYear; $y <= $maxYear; $y++) {
                    $startT = ($y === $minYear) ? $minTerm : 1;
                    $endT = ($y === $maxYear) ? $maxTerm : 3;
                    for ($t = $startT; $t <= $endT; $t++) {
                        $termKeys[] = $y . '-T' . $t;
                    }
                }

                foreach ($grouped as $area => $terms) {
                    $history = [];
                    foreach ($termKeys as $tk) {
                        $history[] = isset($terms[$tk]) ? $terms[$tk] : 0;
                    }
                    $fileData[] = [
                        "area" => $area,
                        "history" => $history,
                        "capacity" => 150 // Capacidad dinámica por defecto
                    ];
                }
            }

            // Leer configuración desde el Frontend (periodos, ventana histórica, modelo, epochs, timeout)
            $inputJson = json_decode(file_get_contents('php://input'), true) ?: [];
            $requestData = array_merge($_GET, $_POST, $inputJson);
            $predSteps = isset($requestData['pred_steps']) ? (int)$requestData['pred_steps'] : 4;
            $historial = $requestData['historial_base'] ?? ($requestData['historial'] ?? 'all');
            $modelo    = $requestData['ml_model'] ?? ($requestData['model'] ?? 'linear');

            $payload = [
                'areas'          => $fileData,
                'labels'         => $termKeys,
                'pred_steps'     => max(1, min($predSteps, 12)),
                'historial_base' => isset(self::HIST_WINDOWS[$historial]) ? $historial : 'all',
                'ml_model'       => isset(self::MODELOS[$modelo]) ? $modelo : 'linear',
                'max_epochs'     => max(1, min(isset($requestData['max_epochs']) ? (int)$requestData['max_epochs'] : 500, 5000)),
                'timeout'        => max(1, min(isset($requestData['timeout']) ? (int)$requestData['timeout'] : 10, 120)),
                'compare_models' => true,
            ];

            $resultado = $this->ejecutarPipelinePython('trends', $payload);
            $this->jsonResponse($resultado);

        } catch (Exception $e) {
            echo json_encode(["error" => "Fallo en ejecución: " . $e->getMessage()]);
            exit;
        }
    }

    /**
     * Orquestador: intenta el pipeline real de Python (scikit-learn) y, SOLO si Python
     * no está disponible o falla, usa el motor matemático de respaldo en PHP.
     * La respuesta siempre indica qué motor la produjo ('engine' => 'python' | 'php-fallback').
     */
    private function ejecutarPipelinePython($tarea, array $payload) {
        $timeout = (int)($payload['timeout'] ?? 10);
        [$resultado, $motivo] = $this->invocarPython($tarea, json_encode($payload), $timeout);

        if ($resultado === null) {
            if ($tarea !== 'trends') {
                return ['error' => 'Fallo la IA', 'raw' => $motivo];
            }
            $resultado = $this->proyectarConRespaldoPhp($payload);
            $resultado['fallback_reason'] = $motivo;
            error_log('[AnaliticaController] Motor de respaldo PHP activado: ' . $motivo);
        }

        // Etiquetas de los trimestres efectivamente analizados (ventana aplicada) y proyectados
        $terms = $resultado['history_terms'] ?? [];
        $resultado['history_labels'] = array_map([$this, 'formatearTrimestre'], $terms);
        $resultado['future_labels']  = $this->trimestresFuturos(end($terms) ?: null, (int)$payload['pred_steps']);
        $resultado['params']['timeout'] = $timeout;
        $resultado['params']['max_epochs'] = (int)$payload['max_epochs'];
        return $resultado;
    }

    /**
     * Ejecuta ml_pipeline.py con proc_open (sin shell) aplicando el timeout del usuario.
     * @return array{0: ?array, 1: ?string}  [resultado decodificado | null, motivo del fallo]
     */
    private function invocarPython(string $tarea, string $inputJson, int $timeout): array {
        if (!$this->pythonScriptPath || !file_exists($this->pythonScriptPath)) {
            return [null, 'No se encontró el pipeline de Machine Learning (ml_pipeline.py).'];
        }
        if (!function_exists('proc_open') || in_array('proc_open', array_map('trim', explode(',', (string)ini_get('disable_functions'))), true)) {
            return [null, 'proc_open está deshabilitado en PHP (disable_functions).'];
        }
        $python = $this->detectPythonExecutable();
        if ($python === null) {
            return [null, 'No se encontró un intérprete de Python. Configure PYTHON_BIN en .env.'];
        }

        $tmpIn  = tempnam(sys_get_temp_dir(), 'ml_input_');
        $tmpOut = tempnam(sys_get_temp_dir(), 'ml_out_');
        $tmpErr = tempnam(sys_get_temp_dir(), 'ml_err_');
        file_put_contents($tmpIn, $inputJson);

        // stdout/stderr a archivos (las pipes no bloqueantes no funcionan en Windows)
        $descriptores = [
            0 => ['pipe', 'r'],
            1 => ['file', $tmpOut, 'w'],
            2 => ['file', $tmpErr, 'w'],
        ];
        $env = getenv();
        $env['PYTHONIOENCODING'] = 'utf-8';
        $env['PYTHONDONTWRITEBYTECODE'] = '1';

        @set_time_limit($timeout + 15);
        $cmd = [$python, $this->pythonScriptPath, $tarea, $tmpIn];   // Forma array: sin shell, sin problemas de escape
        $proc = @proc_open($cmd, $descriptores, $pipes, dirname($this->pythonScriptPath), $env, ['bypass_shell' => true]);

        if (!is_resource($proc)) {
            $this->limpiarTemporales([$tmpIn, $tmpOut, $tmpErr]);
            return [null, "No se pudo iniciar el proceso de Python ($python)."];
        }
        fclose($pipes[0]);

        $inicio = microtime(true);
        $excedido = false;
        $exitCode = null;
        while (true) {
            $estado = proc_get_status($proc);
            if (!$estado['running']) {
                $exitCode = $estado['exitcode'];
                break;
            }
            if ((microtime(true) - $inicio) > $timeout) {
                $excedido = true;
                proc_terminate($proc, 9);   // SIGKILL en Linux / TerminateProcess en Windows
                break;
            }
            usleep(50000);
        }
        proc_close($proc);

        $output = (string)@file_get_contents($tmpOut);
        $stderr = trim((string)@file_get_contents($tmpErr));
        $this->limpiarTemporales([$tmpIn, $tmpOut, $tmpErr]);

        if ($excedido) {
            return [null, "Python superó el tiempo límite de {$timeout}s y el proceso fue terminado."];
        }

        $jsonOutput = $output;
        $startPos = strpos($output, '{');
        $endPos = strrpos($output, '}');
        if ($startPos !== false && $endPos !== false && $endPos > $startPos) {
            $jsonOutput = substr($output, $startPos, $endPos - $startPos + 1);
        }

        $decoded = json_decode($jsonOutput, true);
        if (json_last_error() === JSON_ERROR_NONE && isset($decoded['type'])) {
            return [$decoded, null];
        }
        if (is_array($decoded) && isset($decoded['error'])) {
            $detalle = isset($decoded['details']) ? ' (' . $decoded['details'] . ')' : '';
            return [null, $decoded['error'] . $detalle];
        }

        $raw = trim($stderr !== '' ? $stderr : $output);
        $raw = mb_substr(mb_convert_encoding($raw, 'UTF-8', 'UTF-8, ISO-8859-1'), 0, 300);
        return [null, "Python ($python) terminó con código " . var_export($exitCode, true) . ($raw !== '' ? ": $raw" : ' sin salida.')];
    }

    /**
     * Detección portable del intérprete de Python:
     *  1. Variable PYTHON_BIN (.env)                     -> override explícito
     *  2. venv del proyecto en storage/modelos_ia/(.)venv -> recomendado en producción
     *  3. PATH: Linux `command -v python3|python`; Windows `where python|python3` (omitiendo el alias de WindowsApps)
     *  4. Rutas estándar de instalación (útil en WAMP: Apache corre como LocalSystem sin el PATH del usuario)
     */
    private function detectPythonExecutable() {
        if ($this->pythonDetectado) return $this->pythonExe;
        $this->pythonDetectado = true;

        $override = getenv('PYTHON_BIN') ?: ($_ENV['PYTHON_BIN'] ?? ($_SERVER['PYTHON_BIN'] ?? ''));
        $override = trim((string)$override, " \t\"'");
        if ($override !== '') {
            return $this->pythonExe = $override;
        }

        $esWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $baseIa = dirname((string)$this->pythonScriptPath);
        $venvs = $esWindows
            ? [$baseIa . '/venv/Scripts/python.exe', $baseIa . '/.venv/Scripts/python.exe']
            : [$baseIa . '/venv/bin/python3', $baseIa . '/.venv/bin/python3', $baseIa . '/venv/bin/python', $baseIa . '/.venv/bin/python'];
        foreach ($venvs as $v) {
            if (is_file($v)) return $this->pythonExe = $v;
        }

        $puedeShell = function_exists('shell_exec') && !in_array('shell_exec', array_map('trim', explode(',', (string)ini_get('disable_functions'))), true);

        if ($esWindows) {
            if ($puedeShell) {
                foreach (['python', 'python3'] as $nombre) {
                    $where = (string)@shell_exec('where ' . $nombre . ' 2>nul');
                    foreach (preg_split('/\r?\n/', trim($where)) as $p) {
                        $p = trim($p);
                        if ($p !== '' && is_file($p) && stripos($p, 'windowsapps') === false) {
                            return $this->pythonExe = $p;
                        }
                    }
                }
            }
            // Rutas estándar (por usuario y globales); se prefiere la versión más reciente
            $patrones = [];
            $usuarios = getenv('PUBLIC') ? dirname(getenv('PUBLIC')) : ((getenv('SystemDrive') ?: 'C:') . '\\Users');
            $patrones[] = $usuarios . '\\*\\AppData\\Local\\Programs\\Python\\Python3*\\python.exe';
            if (getenv('LOCALAPPDATA')) $patrones[] = getenv('LOCALAPPDATA') . '\\Programs\\Python\\Python3*\\python.exe';
            if (getenv('ProgramFiles')) $patrones[] = getenv('ProgramFiles') . '\\Python3*\\python.exe';
            $patrones[] = (getenv('SystemDrive') ?: 'C:') . '\\Python3*\\python.exe';
            $encontrados = [];
            foreach ($patrones as $patron) {
                foreach (glob($patron) ?: [] as $f) $encontrados[] = $f;
            }
            if ($encontrados) {
                usort($encontrados, fn($a, $b) => strnatcasecmp(basename(dirname($b)), basename(dirname($a))));
                return $this->pythonExe = $encontrados[0];
            }
            return $this->pythonExe = null;
        }

        // Linux / macOS
        if ($puedeShell) {
            foreach (['python3', 'python'] as $nombre) {
                $p = trim((string)@shell_exec('command -v ' . $nombre . ' 2>/dev/null'));
                if ($p !== '' && is_file($p)) return $this->pythonExe = $p;
            }
        }
        foreach (['/usr/bin/python3', '/usr/local/bin/python3', '/opt/homebrew/bin/python3'] as $p) {
            if (is_file($p)) return $this->pythonExe = $p;
        }
        return $this->pythonExe = null;
    }

    // =========================================================================
    //  MOTOR DE RESPALDO EN PHP (solo si Python no está disponible)
    //  Implementa matemática real y determinista; respeta la ventana histórica.
    // =========================================================================

    private function proyectarConRespaldoPhp(array $payload): array {
        $areas     = $payload['areas'] ?? [];
        $labels    = $payload['labels'] ?? [];
        $predSteps = (int)($payload['pred_steps'] ?? 4);
        $modelo    = $payload['ml_model'] ?? 'linear';
        $historial = $payload['historial_base'] ?? 'all';

        $totalLen = $labels ? count($labels) : max(array_map(fn($a) => count($a['history'] ?? []), $areas ?: [[]]));
        $ventana  = isset(self::HIST_WINDOWS[$historial]) ? min(self::HIST_WINDOWS[$historial], $totalLen) : $totalLen;

        $rmses = ['linear' => [], 'poly' => [], 'rf' => []];
        $salida = [];
        foreach ($areas as $areaData) {
            $history = array_values($areaData['history'] ?? []);
            if ($ventana > 0 && count($history) > $ventana) {
                $history = array_slice($history, -$ventana);
            }
            if (count($history) === 0) continue;
            $y = array_map('floatval', $history);

            $curvaSel = [];
            $rmseSel = 0.0;
            foreach (array_keys(self::MODELOS) as $tipo) {
                [$curva, $rmse] = $this->ajustarModeloPhp($tipo, $y, $predSteps);
                $rmses[$tipo][] = $rmse;
                if ($tipo === $modelo) { $curvaSel = $curva; $rmseSel = $rmse; }
            }

            $capacity = isset($areaData['capacity']) ? (float)$areaData['capacity'] : 150.0;
            $ultimo = end($curvaSel) ?: 0;
            $salida[] = [
                'name'             => $areaData['area'] ?? ($areaData['name'] ?? 'Desconocida'),
                'history'          => $history,
                'adoption_curve'   => $curvaSel,
                'saturation_index' => round(min(100.0, max(0.0, ($ultimo / ($capacity ?: 150.0)) * 100)), 2),
                'rmse'             => round($rmseSel, 4),
            ];
        }

        if (!$salida) {
            return ['error' => 'Error Critico: Datos insuficientes para la proyección.'];
        }

        $media = fn($v) => $v ? round(array_sum($v) / count($v), 4) : 0.0;
        return [
            'type'          => 'trends_projection',
            'engine'        => 'php-fallback',
            'engine_detail' => 'Motor matemático PHP ' . PHP_VERSION . ' (respaldo)',
            'model_used'    => $modelo,
            'model_label'   => self::MODELOS[$modelo] . ($modelo === 'rf' ? ' (aprox. determinista)' : ''),
            'history_terms' => $labels ? array_slice($labels, -$ventana) : [],
            'areas'         => $salida,
            'metrics'       => [
                'global_rmse'   => $media($rmses[$modelo]),
                'rmse_by_model' => array_map($media, $rmses),
            ],
            'params'        => [
                'historial_base' => $historial,
                'points_used'    => $ventana,
                'pred_steps'     => $predSteps,
                'n_estimators'   => null,
            ],
        ];
    }

    /**
     * Ajusta un modelo sobre x = 0..n-1 y proyecta $pasos periodos.
     * @return array{0: float[], 1: float}  [curva proyectada (>= 0), RMSE de ajuste]
     */
    private function ajustarModeloPhp(string $tipo, array $y, int $pasos): array {
        $n = count($y);
        if ($n < 2) {
            return [array_fill(0, $pasos, round(max(0.0, $y[0] ?? 0.0), 2)), 0.0];
        }

        if ($tipo === 'rf') {
            // Aproximación determinista de un Random Forest sobre el índice temporal:
            // un árbol no extrapola, así que fuera del rango predice la media de las
            // hojas más recientes. Usamos una media móvil de k puntos como "hoja".
            $k = min(3, $n);
            $ajuste = [];
            for ($i = 0; $i < $n; $i++) {
                $desde = max(0, $i - $k + 1);
                $tramo = array_slice($y, $desde, $i - $desde + 1);
                $ajuste[] = array_sum($tramo) / count($tramo);
            }
            $ultimo = array_sum(array_slice($y, -$k)) / $k;
            $curva = array_fill(0, $pasos, round(max(0.0, $ultimo), 2));
            return [$curva, $this->rmse($y, $ajuste)];
        }

        if ($tipo === 'poly' && $n >= 3) {
            $coef = $this->minimosCuadradosCuadratico($y);
            if ($coef !== null) {
                [$a, $b, $c] = $coef;   // y = a + b·x + c·x²
                $f = fn($x) => $a + $b * $x + $c * $x * $x;
                $ajuste = array_map($f, range(0, $n - 1));
                $curva = [];
                for ($i = 0; $i < $pasos; $i++) $curva[] = round(max(0.0, $f($n + $i)), 2);
                return [$curva, $this->rmse($y, $ajuste)];
            }
        }

        // Regresión lineal por mínimos cuadrados (también respaldo de 'poly' con < 3 puntos)
        $sx = $sy = $sxy = $sxx = 0.0;
        for ($i = 0; $i < $n; $i++) {
            $sx += $i; $sy += $y[$i]; $sxy += $i * $y[$i]; $sxx += $i * $i;
        }
        $den = $n * $sxx - $sx * $sx;
        $m = $den != 0 ? ($n * $sxy - $sx * $sy) / $den : 0.0;
        $b0 = ($sy - $m * $sx) / $n;
        $ajuste = array_map(fn($x) => $b0 + $m * $x, range(0, $n - 1));
        $curva = [];
        for ($i = 0; $i < $pasos; $i++) $curva[] = round(max(0.0, $b0 + $m * ($n + $i)), 2);
        return [$curva, $this->rmse($y, $ajuste)];
    }

    /** Resuelve las ecuaciones normales 3x3 de y = a + b·x + c·x² (eliminación gaussiana con pivoteo) */
    private function minimosCuadradosCuadratico(array $y): ?array {
        $s = array_fill(0, 5, 0.0);   // Σx^0..Σx^4
        $t = array_fill(0, 3, 0.0);   // Σy, Σxy, Σx²y
        foreach ($y as $x => $v) {
            $p = 1.0;
            for ($k = 0; $k <= 4; $k++) {
                $s[$k] += $p;
                if ($k <= 2) $t[$k] += $p * $v;
                $p *= $x;
            }
        }
        $M = [
            [$s[0], $s[1], $s[2], $t[0]],
            [$s[1], $s[2], $s[3], $t[1]],
            [$s[2], $s[3], $s[4], $t[2]],
        ];
        for ($col = 0; $col < 3; $col++) {
            $piv = $col;
            for ($r = $col + 1; $r < 3; $r++) if (abs($M[$r][$col]) > abs($M[$piv][$col])) $piv = $r;
            if (abs($M[$piv][$col]) < 1e-12) return null;
            [$M[$col], $M[$piv]] = [$M[$piv], $M[$col]];
            for ($r = 0; $r < 3; $r++) {
                if ($r === $col) continue;
                $f = $M[$r][$col] / $M[$col][$col];
                for ($c = $col; $c < 4; $c++) $M[$r][$c] -= $f * $M[$col][$c];
            }
        }
        return [$M[0][3] / $M[0][0], $M[1][3] / $M[1][1], $M[2][3] / $M[2][2]];
    }

    private function rmse(array $y, array $ajuste): float {
        $n = count($y);
        if ($n === 0) return 0.0;
        $suma = 0.0;
        for ($i = 0; $i < $n; $i++) $suma += ($y[$i] - $ajuste[$i]) ** 2;
        return sqrt($suma / $n);
    }

    // =========================================================================
    //  HELPERS DE TRIMESTRES (T1=Ene-Abr, T2=May-Ago, T3=Sep-Dic)
    // =========================================================================

    private function trimestreActual(): array {
        return [(int)date('Y'), (int)floor(((int)date('n') - 1) / 4) + 1];
    }

    private function generarTrimestresHastaActual(int $cantidad): array {
        [$y, $t] = $this->trimestreActual();
        $out = [];
        for ($i = 0; $i < $cantidad; $i++) {
            array_unshift($out, $y . '-T' . $t);
            if (--$t < 1) { $t = 3; $y--; }
        }
        return $out;
    }

    private function trimestresFuturos(?string $ultimo, int $pasos): array {
        if ($ultimo && preg_match('/^(\d{4})-T([1-3])$/', $ultimo, $m)) {
            $y = (int)$m[1]; $t = (int)$m[2];
        } else {
            [$y, $t] = $this->trimestreActual();
        }
        $out = [];
        for ($i = 0; $i < $pasos; $i++) {
            if (++$t > 3) { $t = 1; $y++; }
            $out[] = "T{$t} {$y}";
        }
        return $out;
    }

    private function formatearTrimestre(string $key): string {
        return preg_match('/^(\d{4})-T([1-3])$/', $key, $m) ? "T{$m[2]} {$m[1]}" : $key;
    }

    private function limpiarTemporales(array $archivos): void {
        foreach ($archivos as $f) {
            if ($f && is_file($f)) @unlink($f);
        }
    }

    private function jsonResponse($data, $statusCode = 200) {
        if (ob_get_length()) ob_clean();
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_INVALID_UTF8_SUBSTITUTE);
        exit;
    }
}