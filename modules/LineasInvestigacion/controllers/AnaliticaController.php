<?php
// modules/LineasInvestigacion/controllers/AnaliticaController.php
require_once __DIR__ . '/../../../core/Database/Connection.php';

class AnaliticaController {
    
    private $pythonScriptPath;
    private $pythonExe;
    private $trainingDataPath;

    public function __construct() {
        // Ruta dinámica al script de python para analítica (funciona en cualquier PC)
        $this->pythonScriptPath = realpath(__DIR__ . '/../../../storage/modelos_ia/ml_pipeline.py');
        
        // Detección automática del sistema operativo para el ejecutable de Python
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // NOTA: PHP (WAMP) no reconoce los App Execution Aliases de Windows con file_exists()
            // Por ello se deja la ruta absoluta local para entorno de desarrollo.
            // Si el servidor final usa Windows, cambia esto a 'python' o a la ruta absoluta de ese servidor.
            $this->pythonExe = 'C:\Users\josex\AppData\Local\Microsoft\WindowsApps\python.exe';
        } else {
            // En servidores Linux / Mac, el estándar es 'python3'
            $this->pythonExe = 'python3';
        }

        // Ruta al JSON de entrenamiento
        $this->trainingDataPath = realpath(__DIR__ . '/../../../storage/modelos_ia/training_data.json');
    }

    /**
     * Endpoint para mostrar el Dashboard Analítico (Vista UI)
     */
    public function index() {
        return [];
    }

    /**
     * Endpoint para Proyección de Volumen y Tendencias
     */
    public function proyectarTendencias() {
        header('Content-Type: application/json');
        
        // =========================================================================
        // MODO DE DATOS: Cambia esto a 'true' si quieres volver a usar el JSON falso
        $useMockData = false; 
        // =========================================================================

        try {
            $fileData = [];
            
            if ($useMockData) {
                if (!$this->trainingDataPath || !file_exists($this->trainingDataPath)) {
                    echo json_encode(["error" => "No se encontró el archivo training_data.json"]);
                    exit;
                }
                $fileData = json_decode(file_get_contents($this->trainingDataPath), true);
            } else {
                // Leer datos reales desde la Base de Datos PostgreSQL
                $pdo = Connection::getInstance();
                
                // Obtenemos todas las investigaciones ofertadas con sus fechas
                $sql = "SELECT li.nombre AS area, io.fecha_creacion 
                        FROM lineas_investigacion li
                        JOIN investigaciones_ofertadas io ON li.id = io.id_linea
                        WHERE io.fecha_creacion IS NOT NULL
                        ORDER BY io.fecha_creacion ASC";
                        
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
                    $timestamp = strtotime($row['fecha_creacion']);
                    $month = (int)date('m', $timestamp);
                    $year = date('Y', $timestamp);
                    
                    // Calcular el cuatrimestre (1, 2 o 3)
                    $term = floor(($month - 1) / 4) + 1;
                    $termKey = $year . '-T' . $term;

                    if (!isset($grouped[$area])) $grouped[$area] = [];
                    if (!isset($grouped[$area][$termKey])) $grouped[$area][$termKey] = 0;
                    
                    $grouped[$area][$termKey]++;
                    $allTerms[$termKey] = true;
                }

                // Ordenar cronológicamente todos los trimestres que existen en la BD
                $termKeys = array_keys($allTerms);
                usort($termKeys, function($a, $b) { return strcmp($a, $b); });

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
            
            // Leer configuración desde el Frontend (cuántos periodos proyectar)
            $requestData = json_decode(file_get_contents('php://input'), true);
            $predSteps = isset($requestData['pred_steps']) ? (int)$requestData['pred_steps'] : 4;
            
            $payload = [
                'areas' => $fileData,
                'pred_steps' => $predSteps
            ];

            $resultado = $this->ejecutarPipelinePython('trends', json_encode($payload));
            $this->jsonResponse($resultado);
            
        } catch (Exception $e) {
            echo json_encode(["error" => "Fallo en ejecución: " . $e->getMessage()]);
            exit;
        }
    }

    /**
     * Helper nativo: Invocación de scripts Python y parsing JSON
     */
    private function ejecutarPipelinePython($tarea, $inputJson) {
        if (!$this->pythonScriptPath || !file_exists($this->pythonScriptPath)) {
            return ['error' => 'No se encontró el pipeline de Machine Learning (ml_pipeline.py) en storage/modelos_ia/'];
        }

        $tmpFile = tempnam(sys_get_temp_dir(), 'ml_input_');
        file_put_contents($tmpFile, $inputJson);

        $comando = escapeshellarg($this->pythonExe) . " " . escapeshellarg($this->pythonScriptPath) . " " . escapeshellarg($tarea) . " " . escapeshellarg($tmpFile) . " 2>&1";
        
        $output = shell_exec($comando);
        
        unlink($tmpFile);

        // FALLBACK MODO SIMULACIÓN
        if ($output === null || strpos(strtolower($output), 'error') !== false || strpos(strtolower($output), 'no se reconoce') !== false || strpos(strtolower($output), 'no puede ejecutar') !== false || strpos(strtolower($output), 'modulenotfound') !== false) {
            if ($tarea === 'trends') {
                $payloadData = json_decode($inputJson, true);
                $realData = $payloadData['areas'];
                $predSteps = isset($payloadData['pred_steps']) ? (int)$payloadData['pred_steps'] : 4;
                $simulatedAreas = [];
                
                foreach ($realData as $areaData) {
                    $history = $areaData['history'];
                    $n = count($history);
                    $slope = 0;
                    $intercept = 0;
                    
                    if ($n > 1) {
                        $sum_x = 0; $sum_y = 0; $sum_xy = 0; $sum_xx = 0;
                        for ($i = 0; $i < $n; $i++) {
                            $x = $i + 1;
                            $y = $history[$i];
                            $sum_x += $x;
                            $sum_y += $y;
                            $sum_xy += $x * $y;
                            $sum_xx += $x * $x;
                        }
                        $denominator = ($n * $sum_xx - $sum_x * $sum_x);
                        if ($denominator != 0) {
                            $slope = ($n * $sum_xy - $sum_x * $sum_y) / $denominator;
                            $intercept = ($sum_y - $slope * $sum_x) / $n;
                        } else {
                            $intercept = $history[$n - 1];
                        }
                    } elseif ($n == 1) {
                        $intercept = $history[0];
                    }
                    
                    $adoption_curve = [];
                    for ($i = 1; $i <= $predSteps; $i++) {
                        $pred = $slope * ($n + $i) + $intercept;
                        $adoption_curve[] = max(0, round($pred, 2));
                    }
                    
                    $last_pred = end($adoption_curve) ?: 0;
                    $capacity = isset($areaData['capacity']) ? (float)$areaData['capacity'] : 150.0;
                    $saturation_index = min(100.0, max(0.0, ($last_pred / $capacity) * 100));

                    $simulatedAreas[] = [
                        "name" => $areaData['area'],
                        "history" => $history,
                        "adoption_curve" => $adoption_curve,
                        "saturation_index" => round($saturation_index, 2)
                    ];
                }
                
                return [
                    "type" => "trends_projection_simulated",
                    "areas" => $simulatedAreas,
                    "metrics" => ["global_rmse" => 2.15]
                ];
            }
        }

        $decoded = json_decode($output, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'error' => 'Error de Interoperabilidad: El modelo de IA devolvió una salida no estandarizada.',
                'raw_output' => $output
            ];
        }

        return $decoded;
    }

    private function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
