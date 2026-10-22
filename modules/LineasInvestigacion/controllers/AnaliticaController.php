<?php
// modules/LineasInvestigacion/controllers/AnaliticaController.php
require_once __DIR__ . '/../../../core/Database/Connection.php';
require_once __DIR__ . '/../../SuperAdmin/services/SystemConfigService.php';
require_once __DIR__ . '/../../../core/Security/Auth.php';

class AnaliticaController {

    /** Año académico = 3 periodos trimestrales -> 2 años = 6 periodos, 1 año = 3 */
    private const HIST_WINDOWS = ['2y' => 6, '1y' => 3];
    private const BASE_MODELOS = ['linear', 'poly', 'rf'];
    private const MODELOS = [
        'auto'   => 'Auto-ML (Mejor Ajuste por Línea)',
        'linear' => 'Regresión Lineal Múltiple',
        'poly'   => 'Regresión Polinómica (Grado 2)',
        'rf'     => 'Random Forest Regressor',
    ];

    private $pythonScriptPath;
    private $pythonExe = null;
    private bool $pythonDetectado = false;
    private $trainingDataPath;
    private int $nivelAdmin;
    private int $nivelPublico;

    public function __construct() {
        $this->pythonScriptPath = realpath(__DIR__ . '/../../../storage/modelos_ia/ml_pipeline.py');
        $this->nivelAdmin   = SystemConfigService::get('accesos_modulos.lineas_investigacion.admin', 1);
        $this->nivelPublico = SystemConfigService::get('accesos_modulos.lineas_investigacion.publico', 10);
        $this->trainingDataPath = realpath(__DIR__ . '/../../../storage/modelos_ia/training_data.json');
    }

    /**
     * Crea automáticamente la tabla de snapshots de predicción si aún no existe.
     */
    private function ensureSnapshotsTable(PDO $pdo): void {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS public.analitica_snapshots (
                id SERIAL PRIMARY KEY,
                fecha_snapshot TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
                usuario_nombre VARCHAR(150) DEFAULT 'Sistema CIIDI',
                id_carrera INTEGER NULL,
                carrera_nombre VARCHAR(150) DEFAULT 'Todas las Carreras / PNF',
                modelo_usado VARCHAR(80) NOT NULL,
                modelo_label VARCHAR(120) NOT NULL,
                fuente_datos VARCHAR(30) DEFAULT 'pst',
                ventana_historial VARCHAR(30) DEFAULT '2y',
                horizonte_trimestres INTEGER NOT NULL DEFAULT 4,
                trimestre_base VARCHAR(20) NULL,
                trimestre_meta VARCHAR(20) NULL,
                rmse_global NUMERIC(10, 4) DEFAULT 0,
                mae_global NUMERIC(10, 4) DEFAULT 0,
                confianza_pct NUMERIC(5, 2) DEFAULT 0,
                total_historico INTEGER DEFAULT 0,
                total_proyectado INTEGER DEFAULT 0,
                estacionalidad_activa BOOLEAN DEFAULT FALSE,
                resumen_json JSONB NOT NULL DEFAULT '[]'::jsonb
            )
        ");
    }

    /**
     * Endpoint para mostrar el Dashboard Analítico (Vista UI)
     */
    public function index() {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'auditar', 'LineasInvestigacion');

        $carreras = [];
        $lineas = [];
        try {
            $pdo = Connection::getInstance();
            $this->ensureSnapshotsTable($pdo);

            $sqlCarreras = "
                SELECT c.id, c.nombre, c.descripcion,
                       COUNT(li.id) FILTER (WHERE li.activo = true) AS total_lineas
                FROM public.carreras c
                LEFT JOIN public.lineas_investigacion li ON li.id_carrera = c.id
                GROUP BY c.id, c.nombre, c.descripcion
                ORDER BY c.id ASC
            ";
            $carreras = $pdo->query($sqlCarreras)->fetchAll(PDO::FETCH_ASSOC);

            $sqlLineas = "
                SELECT li.id, li.nombre, li.id_carrera, COALESCE(c.nombre, 'General') AS carrera_nombre
                FROM public.lineas_investigacion li
                LEFT JOIN public.carreras c ON c.id = li.id_carrera
                WHERE li.activo = true
                ORDER BY c.id ASC, li.nombre ASC
            ";
            $lineas = $pdo->query($sqlLineas)->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            error_log('[AnaliticaController::index] Error cargando catálogos: ' . $e->getMessage());
        }

        return [
            'carreras' => $carreras,
            'lineas'   => $lineas,
        ];
    }

    /**
     * Endpoint para Proyección de Volumen y Gestión de Snapshots
     */
    public function proyectarTendencias() {
        Auth::requierePrivilegioMinimo($this->nivelAdmin, 'auditar', 'LineasInvestigacion');
        header('Content-Type: application/json');

        $useMockData = false;

        try {
            $inputJson = json_decode(file_get_contents('php://input'), true) ?: [];
            $requestData = array_merge($_GET, $_POST, $inputJson);
            $pdo = Connection::getInstance();
            $this->ensureSnapshotsTable($pdo);

            // Manejo de acciones sobre Snapshots de Predicción
            $accion = $requestData['action'] ?? 'project';
            if ($accion === 'save_snapshot') {
                $snap = $this->guardarSnapshot($pdo, $requestData);
                $this->jsonResponse([
                    'ok' => true,
                    'snapshot' => $snap,
                    'snapshots' => $this->obtenerSnapshotsConAuditoria($pdo),
                ]);
            }
            if ($accion === 'delete_snapshot') {
                $idSnap = (int)($requestData['id_snapshot'] ?? 0);
                if ($idSnap > 0) {
                    $stmtDel = $pdo->prepare("DELETE FROM public.analitica_snapshots WHERE id = ?");
                    $stmtDel->execute([$idSnap]);
                }
                $this->jsonResponse([
                    'ok' => true,
                    'snapshots' => $this->obtenerSnapshotsConAuditoria($pdo),
                ]);
            }

            // Parámetros de proyección (Predeterminado: 'pst' = Solo Proyectos PST Culminados)
            $predSteps     = isset($requestData['pred_steps']) ? (int)$requestData['pred_steps'] : 4;
            $historial     = $requestData['historial_base'] ?? ($requestData['historial'] ?? '2y');
            $modelo        = $requestData['ml_model'] ?? ($requestData['model'] ?? 'linear');
            $idCarreraRaw  = $requestData['id_carrera'] ?? 'all';
            $idCarrera     = ($idCarreraRaw !== 'all' && $idCarreraRaw !== '' && is_numeric($idCarreraRaw) && (int)$idCarreraRaw > 0)
                             ? (int)$idCarreraRaw
                             : null;
            $fuenteDatos   = in_array($requestData['fuente_datos'] ?? 'pst', ['pst', 'all'], true)
                             ? $requestData['fuente_datos']
                             : 'pst';
            $capacidadBase = isset($requestData['capacidad_linea']) && is_numeric($requestData['capacidad_linea'])
                             ? max(5, min((int)$requestData['capacidad_linea'], 500))
                             : 25;

            $fileData = [];
            $termKeys = [];
            $carreraFiltroNombre = 'Todas las Carreras / PNF';

            if ($useMockData) {
                if (!$this->trainingDataPath || !file_exists($this->trainingDataPath)) {
                    $this->jsonResponse(["error" => "No se encontró el archivo training_data.json"]);
                }
                $fileData = json_decode(file_get_contents($this->trainingDataPath), true);
                $fileData = isset($fileData['areas']) ? $fileData['areas'] : $fileData;
                $maxLen = 0;
                foreach ($fileData as $a) $maxLen = max($maxLen, count($a['history'] ?? []));
                $termKeys = $this->generarTrimestresHastaActual($maxLen ?: 6);
            } else {
                if ($idCarrera !== null) {
                    $stmtCar = $pdo->prepare("SELECT nombre FROM public.carreras WHERE id = ?");
                    $stmtCar->execute([$idCarrera]);
                    $nomCar = $stmtCar->fetchColumn();
                    if ($nomCar) {
                        $carreraFiltroNombre = (string)$nomCar;
                    }
                }

                // 1. Obtener todas las Líneas de Investigación activas (filtradas por carrera si aplica)
                $sqlLineas = "
                    SELECT li.id, li.nombre AS area, li.id_carrera,
                           COALESCE(c.nombre, 'General') AS carrera_nombre
                    FROM public.lineas_investigacion li
                    LEFT JOIN public.carreras c ON c.id = li.id_carrera
                    WHERE li.activo = true
                ";
                $paramsLineas = [];
                if ($idCarrera !== null) {
                    $sqlLineas .= " AND li.id_carrera = ?";
                    $paramsLineas[] = $idCarrera;
                }
                $sqlLineas .= " ORDER BY li.id_carrera ASC, li.nombre ASC";

                $stmtLineas = $pdo->prepare($sqlLineas);
                $stmtLineas->execute($paramsLineas);
                $lineasActivas = $stmtLineas->fetchAll(PDO::FETCH_ASSOC);

                if (empty($lineasActivas)) {
                    $this->jsonResponse([
                        "empty_career" => true,
                        "carrera_nombre" => $carreraFiltroNombre,
                        "snapshots" => $this->obtenerSnapshotsConAuditoria($pdo),
                        "error" => "La carrera seleccionada ({$carreraFiltroNombre}) aún no tiene Líneas de Investigación activas registradas."
                    ]);
                }

                // 2. Determinar el eje temporal global de periodos en la institución
                $sqlGlobalDates = "
                    SELECT COALESCE(dp.fecha_defensa, dp.created_at) AS fecha
                    FROM public.recurso_clasificaciones rc
                    INNER JOIN public.recursos r ON rc.id_recurso = r.id
                    LEFT JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso
                    WHERE COALESCE(dp.fecha_defensa, dp.created_at) IS NOT NULL
                ";
                if ($fuenteDatos === 'all') {
                    $sqlGlobalDates .= "
                        UNION ALL
                        SELECT io.fecha_creacion AS fecha
                        FROM public.investigaciones_ofertadas io
                        WHERE io.fecha_creacion IS NOT NULL
                    ";
                }
                $fechasGlobales = $pdo->query($sqlGlobalDates)->fetchAll(PDO::FETCH_COLUMN);

                $allTerms = [];
                foreach ($fechasGlobales as $fStr) {
                    $ts = !empty($fStr) ? strtotime((string)$fStr) : false;
                    if ($ts === false) continue;
                    $m = (int)date('m', $ts);
                    $y = (int)date('Y', $ts);
                    $t = (int)floor(($m - 1) / 4) + 1;
                    $allTerms[$y . '-T' . $t] = true;
                }

                if (empty($allTerms)) {
                    $termKeys = $this->generarTrimestresHastaActual(6);
                } else {
                    $termKeysRaw = array_keys($allTerms);
                    usort($termKeysRaw, fn($a, $b) => strcmp($a, $b));

                    list($minYear, $minTerm) = array_map('intval', explode('-T', $termKeysRaw[0]));
                    list($maxYear, $maxTerm) = array_map('intval', explode('-T', end($termKeysRaw)));

                    list($curYear, $curTerm) = $this->trimestreActual();
                    if ($curYear * 10 + $curTerm > $maxYear * 10 + $maxTerm) {
                        $maxYear = $curYear;
                        $maxTerm = $curTerm;
                    }

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
                    if (count($termKeys) < 3) {
                        $termKeys = $this->generarTrimestresHastaActual(4);
                    }
                }

                // 3. Consultar los proyectos por cada línea activa
                $grouped = [];
                $metaPorLinea = [];
                foreach ($lineasActivas as $la) {
                    $lid = (int)$la['id'];
                    $grouped[$lid] = [];
                    $metaPorLinea[$lid] = $la;
                }

                $idsPlaceholder = implode(',', array_fill(0, count($lineasActivas), '?'));
                $idsLineas = array_map(fn($l) => (int)$l['id'], $lineasActivas);

                // 3a. Proyectos PST culminados (recurso_clasificaciones)
                $sqlPst = "
                    SELECT rc.id_linea_investigacion AS id_linea,
                           COALESCE(dp.fecha_defensa, dp.created_at) AS fecha_creacion
                    FROM public.recurso_clasificaciones rc
                    INNER JOIN public.recursos r ON rc.id_recurso = r.id
                    LEFT JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso
                    WHERE rc.id_linea_investigacion IN ($idsPlaceholder)
                ";
                $stmtPst = $pdo->prepare($sqlPst);
                $stmtPst->execute($idsLineas);
                $rowsEventos = $stmtPst->fetchAll(PDO::FETCH_ASSOC);

                // 3b. Investigaciones ofertadas / en desarrollo (solo si el usuario lo solicita explícitamente)
                if ($fuenteDatos === 'all') {
                    $sqlOfertas = "
                        SELECT io.id_linea, io.fecha_creacion
                        FROM public.investigaciones_ofertadas io
                        WHERE io.id_linea IN ($idsPlaceholder)
                          AND COALESCE(io.estado, '') NOT IN ('Cancelada', 'Rechazada')
                    ";
                    $stmtOf = $pdo->prepare($sqlOfertas);
                    $stmtOf->execute($idsLineas);
                    $rowsEventos = array_merge($rowsEventos, $stmtOf->fetchAll(PDO::FETCH_ASSOC));
                }

                foreach ($rowsEventos as $ev) {
                    $lid = (int)$ev['id_linea'];
                    if (!isset($grouped[$lid])) continue;
                    $timestamp = !empty($ev['fecha_creacion']) ? strtotime((string)$ev['fecha_creacion']) : false;
                    if ($timestamp === false) continue;

                    $month = (int)date('m', $timestamp);
                    $year  = (int)date('Y', $timestamp);
                    $term  = (int)floor(($month - 1) / 4) + 1;
                    $termKey = $year . '-T' . $term;

                    if (!isset($grouped[$lid][$termKey])) {
                        $grouped[$lid][$termKey] = 0;
                    }
                    $grouped[$lid][$termKey]++;
                }

                foreach ($grouped as $lid => $terms) {
                    $meta = $metaPorLinea[$lid];
                    $history = [];
                    foreach ($termKeys as $tk) {
                        $history[] = isset($terms[$tk]) ? (int)$terms[$tk] : 0;
                    }
                    $maxHist = !empty($history) ? max($history) : 0;
                    $dynamicCap = max($capacidadBase, (int)ceil($maxHist * 1.25));

                    $fileData[] = [
                        "id_linea"       => $lid,
                        "area"           => $meta['area'],
                        "name"           => $meta['area'],
                        "id_carrera"     => (int)$meta['id_carrera'],
                        "carrera_nombre" => $meta['carrera_nombre'],
                        "history"        => $history,
                        "capacity"       => $dynamicCap,
                    ];
                }
            }

            $payload = [
                'areas'          => $fileData,
                'labels'         => $termKeys,
                'pred_steps'     => max(1, min($predSteps, 12)),
                'historial_base' => isset(self::HIST_WINDOWS[$historial]) ? $historial : 'all',
                'ml_model'       => isset(self::MODELOS[$modelo]) ? $modelo : 'linear',
                'max_epochs'     => max(10, min(isset($requestData['max_epochs']) ? (int)$requestData['max_epochs'] : 150, 500)),
                'timeout'        => max(1, min(isset($requestData['timeout']) ? (int)$requestData['timeout'] : 10, 120)),
                'compare_models' => true,
                'id_carrera'     => $idCarrera ?? 'all',
                'carrera_nombre' => $carreraFiltroNombre,
                'fuente_datos'   => $fuenteDatos,
                'capacidad_base' => $capacidadBase,
            ];

            $resultado = $this->ejecutarPipelinePython('trends', $payload);
            $resultado['snapshots'] = $this->obtenerSnapshotsConAuditoria($pdo);
            $this->jsonResponse($resultado);

        } catch (Exception $e) {
            $this->jsonResponse(["error" => "Fallo en ejecución: " . $e->getMessage()], 500);
        }
    }

    /**
     * Guarda un Snapshot de Predicción para auditoría futura (Predicho vs. Real).
     */
    private function guardarSnapshot(PDO $pdo, array $req): array {
        $usuario = $_SESSION['nombre_completo'] ?? ($_SESSION['correo'] ?? 'Coordinador CIIDI');
        $idCarrera = isset($req['id_carrera']) && is_numeric($req['id_carrera']) && (int)$req['id_carrera'] > 0
            ? (int)$req['id_carrera']
            : null;
        $carreraNombre = trim((string)($req['carrera_nombre'] ?? 'Todas las Carreras / PNF'));
        $modeloUsado   = trim((string)($req['modelo_usado'] ?? 'linear'));
        $modeloLabel   = trim((string)($req['modelo_label'] ?? (self::MODELOS[$modeloUsado] ?? $modeloUsado)));
        $fuenteDatos   = trim((string)($req['fuente_datos'] ?? 'pst'));
        $ventanaHist   = trim((string)($req['ventana_historial'] ?? '2y'));
        $horizonte     = max(1, min((int)($req['horizonte_trimestres'] ?? 4), 12));
        $trimBase      = trim((string)($req['trimestre_base'] ?? ''));
        $trimMeta      = trim((string)($req['trimestre_meta'] ?? ''));
        $rmseGlobal    = round((float)($req['rmse_global'] ?? 0), 4);
        $maeGlobal     = round((float)($req['mae_global'] ?? 0), 4);
        $confianzaPct  = round((float)($req['confianza_pct'] ?? 75), 2);
        $totalHist     = (int)($req['total_historico'] ?? 0);
        $totalProy     = (int)($req['total_proyectado'] ?? 0);
        $resumenAreas  = isset($req['resumen_areas']) && is_array($req['resumen_areas']) ? $req['resumen_areas'] : [];

        $sql = "
            INSERT INTO public.analitica_snapshots
            (usuario_nombre, id_carrera, carrera_nombre, modelo_usado, modelo_label,
             fuente_datos, ventana_historial, horizonte_trimestres, trimestre_base, trimestre_meta,
             rmse_global, mae_global, confianza_pct, total_historico, total_proyectado,
             estacionalidad_activa, resumen_json)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, false, ?)
            RETURNING id, fecha_snapshot
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $usuario,
            $idCarrera,
            $carreraNombre,
            $modeloUsado,
            $modeloLabel,
            $fuenteDatos,
            $ventanaHist,
            $horizonte,
            $trimBase,
            $trimMeta,
            $rmseGlobal,
            $maeGlobal,
            $confianzaPct,
            $totalHist,
            $totalProy,
            json_encode($resumenAreas, JSON_UNESCAPED_UNICODE)
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Obtiene los últimos 10 Snapshots y calcula el volumen real actual registrado en la BD
     * tanto globalmente como por carrera, por línea y por cada trimestre proyectado
     * para auditar la precisión histórica (Predicho vs. Real).
     */
    private function obtenerSnapshotsConAuditoria(PDO $pdo): array {
        $sql = "
            SELECT id,
                   TO_CHAR(fecha_snapshot, 'DD/MM/YYYY HH24:MI') AS fecha_formateada,
                   usuario_nombre, id_carrera, carrera_nombre,
                   modelo_usado, modelo_label, fuente_datos, ventana_historial,
                   horizonte_trimestres, trimestre_base, trimestre_meta,
                   rmse_global, mae_global, confianza_pct,
                   total_historico, total_proyectado, resumen_json
            FROM public.analitica_snapshots
            ORDER BY fecha_snapshot DESC
            LIMIT 10
        ";
        $rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $sqlReales = "
            SELECT COALESCE(dp.fecha_defensa, dp.created_at) AS fecha,
                   li.id AS id_linea,
                   li.nombre AS linea_nombre,
                   li.id_carrera
            FROM public.recurso_clasificaciones rc
            INNER JOIN public.recursos r ON rc.id_recurso = r.id
            INNER JOIN public.lineas_investigacion li ON li.id = rc.id_linea_investigacion
            LEFT JOIN public.detalles_proyectos dp ON r.id = dp.id_recurso
            WHERE COALESCE(dp.fecha_defensa, dp.created_at) IS NOT NULL
        ";
        $realesRaw = $pdo->query($sqlReales)->fetchAll(PDO::FETCH_ASSOC);
        $realesPorTrimGlobal  = [];
        $realesPorTrimCarrera = [];
        $realesPorLineaTrim   = [];
        $maxSortKeyReal       = 0;

        foreach ($realesRaw as $rr) {
            $ts = strtotime((string)$rr['fecha']);
            if ($ts === false) continue;
            $t = (int)floor(((int)date('m', $ts) - 1) / 4) + 1;
            $y = (int)date('Y', $ts);
            $lbl = "T{$t} {$y}";
            $sortKey = $y * 10 + $t;
            if ($sortKey > $maxSortKeyReal) {
                $maxSortKeyReal = $sortKey;
            }
            $cid = (int)$rr['id_carrera'];
            $lnorm = mb_strtoupper(trim((string)$rr['linea_nombre']), 'UTF-8');
            $realesPorTrimGlobal[$lbl] = ($realesPorTrimGlobal[$lbl] ?? 0) + 1;
            $realesPorTrimCarrera[$cid][$lbl] = ($realesPorTrimCarrera[$cid][$lbl] ?? 0) + 1;
            $realesPorLineaTrim[$lnorm][$lbl] = ($realesPorLineaTrim[$lnorm][$lbl] ?? 0) + 1;
        }

        [$curY, $curT] = $this->trimestreActual();
        $curSortKey = max($curY * 10 + $curT, $maxSortKeyReal);

        $parseLabelSort = function (string $lbl): int {
            if (preg_match('/^T([1-3])\s+(\d{4})$/', trim($lbl), $m)) {
                return ((int)$m[2]) * 10 + ((int)$m[1]);
            }
            if (preg_match('/^(\d{4})-T([1-3])$/', trim($lbl), $m)) {
                return ((int)$m[1]) * 10 + ((int)$m[2]);
            }
            return 0;
        };

        $generarLabelsDesdeBase = function (string $baseLbl, int $pasos): array {
            $y = 0;
            $t = 0;
            if (preg_match('/^T([1-3])\s+(\d{4})$/', trim($baseLbl), $m)) {
                $t = (int)$m[1];
                $y = (int)$m[2];
            } elseif (preg_match('/^(\d{4})-T([1-3])$/', trim($baseLbl), $m)) {
                $y = (int)$m[1];
                $t = (int)$m[2];
            } else {
                [$y, $t] = $this->trimestreActual();
            }
            $out = [];
            for ($i = 0; $i < $pasos; $i++) {
                if (++$t > 3) {
                    $t = 1;
                    $y++;
                }
                $out[] = "T{$t} {$y}";
            }
            return $out;
        };

        foreach ($rows as &$r) {
            $areas = json_decode((string)$r['resumen_json'], true) ?: [];
            unset($r['resumen_json']);

            $horizonte = max(1, (int)($r['horizonte_trimestres'] ?? 4));
            $baseLbl   = (string)($r['trimestre_base'] ?? '');
            $futLabels = [];
            if (!empty($areas[0]['future_labels']) && is_array($areas[0]['future_labels'])) {
                $futLabels = array_slice($areas[0]['future_labels'], 0, $horizonte);
            } else {
                $futLabels = $generarLabelsDesdeBase($baseLbl, $horizonte);
            }
            $r['future_labels'] = $futLabels;

            $cid = $r['id_carrera'] ? (int)$r['id_carrera'] : null;

            // Enriquecer cada línea con su curva completa y sus datos reales por trimestre
            $sumaPredPorTrim = array_fill(0, count($futLabels), 0);
            foreach ($areas as &$a) {
                $lnorm = mb_strtoupper(trim((string)($a['name'] ?? '')), 'UTF-8');
                $curva = [];
                if (!empty($a['adoption_curve']) && is_array($a['adoption_curve'])) {
                    $curva = array_map(fn($v) => round((float)$v, 2), array_slice($a['adoption_curve'], 0, $horizonte));
                } elseif (!empty($a['curva_predicha']) && is_array($a['curva_predicha'])) {
                    $curva = array_map(fn($v) => round((float)$v, 2), array_slice($a['curva_predicha'], 0, $horizonte));
                } else {
                    // Reconstrucción lineal para snapshots antiguos que solo guardaron T1 y Meta Final
                    $p1   = (float)($a['primer_trimestre_pred'] ?? 0);
                    $pFin = (float)($a['meta_final_pred'] ?? $p1);
                    for ($i = 0; $i < $horizonte; $i++) {
                        $frac = $horizonte > 1 ? ($i / ($horizonte - 1)) : 0;
                        $curva[] = round($p1 + ($pFin - $p1) * $frac, 2);
                    }
                }
                $a['curva_predicha'] = $curva;
                $a['future_labels']  = $futLabels;

                $realesLinea = [];
                $esFuturoTrim = [];
                foreach ($futLabels as $idxT => $flbl) {
                    $realVal = $realesPorLineaTrim[$lnorm][$flbl] ?? 0;
                    $realesLinea[] = (int)$realVal;
                    $sk = $parseLabelSort($flbl);
                    $esFuturoTrim[] = ($sk > $curSortKey) && ($realVal === 0);
                    $sumaPredPorTrim[$idxT] += (int)round($curva[$idxT] ?? 0);
                }
                $a['reales_por_trimestre'] = $realesLinea;
                $a['es_futuro_por_trimestre'] = $esFuturoTrim;
                $a['total_proyectado_linea'] = array_sum(array_map(fn($v) => (int)round($v), $curva));
                $a['total_real_linea']       = array_sum($realesLinea);
            }
            unset($a);

            $r['resumen_areas'] = $areas;

            // Auditoría trimestre a trimestre a nivel global del snapshot
            $auditoriaTrimestres = [];
            foreach ($futLabels as $idxT => $flbl) {
                $realGlobalT = $cid !== null
                    ? ($realesPorTrimCarrera[$cid][$flbl] ?? 0)
                    : ($realesPorTrimGlobal[$flbl] ?? 0);
                $sk = $parseLabelSort($flbl);
                $esFuturo = ($sk > $curSortKey) && ($realGlobalT === 0);
                $predT = (int)($sumaPredPorTrim[$idxT] ?? 0);
                $auditoriaTrimestres[] = [
                    'label'     => $flbl,
                    'predicho'  => $predT,
                    'real'      => (int)$realGlobalT,
                    'diferencia'=> (int)$realGlobalT - $predT,
                    'es_futuro' => $esFuturo,
                ];
            }
            $r['auditoria_trimestres'] = $auditoriaTrimestres;

            $primerTrimProy = $futLabels[0] ?? ($r['resumen_areas'][0]['primer_trimestre_label'] ?? $r['trimestre_meta']);
            $realEnMeta = 0;
            $esFuturoT1 = true;
            if ($primerTrimProy) {
                $realEnMeta = $cid !== null
                    ? ($realesPorTrimCarrera[$cid][$primerTrimProy] ?? 0)
                    : ($realesPorTrimGlobal[$primerTrimProy] ?? 0);
                $esFuturoT1 = ($parseLabelSort((string)$primerTrimProy) > $curSortKey) && ($realEnMeta === 0);
            }
            $r['real_registrado_t1'] = $realEnMeta;
            $r['es_futuro_t1']       = $esFuturoT1;
        }

        return $rows;
    }

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

        $terms = $resultado['history_terms'] ?? [];
        $resultado['history_labels'] = array_map([$this, 'formatearTrimestre'], $terms);
        $resultado['future_labels']  = $this->trimestresFuturos(end($terms) ?: null, (int)$payload['pred_steps']);
        if (!empty($resultado['backtest']) && is_array($resultado['backtest'])) {
            foreach (['1t', '2t'] as $btKey) {
                if (!empty($resultado['backtest'][$btKey]) && is_array($resultado['backtest'][$btKey])) {
                    $trTerms = $resultado['backtest'][$btKey]['train_terms'] ?? [];
                    $teTerms = $resultado['backtest'][$btKey]['test_terms'] ?? [];
                    $resultado['backtest'][$btKey]['train_labels'] = array_map([$this, 'formatearTrimestre'], $trTerms);
                    $resultado['backtest'][$btKey]['test_labels']  = array_map([$this, 'formatearTrimestre'], $teTerms);
                }
            }
        }
        $resultado['params']['timeout']        = $timeout;
        $resultado['params']['max_epochs']     = (int)$payload['max_epochs'];
        $resultado['params']['id_carrera']     = $payload['id_carrera'] ?? 'all';
        $resultado['params']['carrera_nombre'] = $payload['carrera_nombre'] ?? 'Todas las Carreras / PNF';
        $resultado['params']['fuente_datos']   = $payload['fuente_datos'] ?? 'pst';
        $resultado['params']['capacidad_base'] = $payload['capacidad_base'] ?? 25;
        return $resultado;
    }

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

        $descriptores = [
            0 => ['pipe', 'r'],
            1 => ['file', $tmpOut, 'w'],
            2 => ['file', $tmpErr, 'w'],
        ];
        $env = getenv();
        $env['PYTHONIOENCODING'] = 'utf-8';
        $env['PYTHONDONTWRITEBYTECODE'] = '1';

        @set_time_limit($timeout + 15);
        $cmd = [$python, $this->pythonScriptPath, $tarea, $tmpIn];
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
                proc_terminate($proc, 9);
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
    //  MOTOR DE RESPALDO EN PHP
    // =========================================================================

    private function proyectarConRespaldoPhp(array $payload): array {
        $areas     = $payload['areas'] ?? [];
        $labels    = $payload['labels'] ?? [];
        $predSteps = (int)($payload['pred_steps'] ?? 4);
        $modelo    = $payload['ml_model'] ?? 'linear';
        $historial = $payload['historial_base'] ?? 'all';

        $totalLen = $labels ? count($labels) : max(array_map(fn($a) => count($a['history'] ?? []), $areas ?: [[]]));
        $ventana  = isset(self::HIST_WINDOWS[$historial]) ? min(self::HIST_WINDOWS[$historial], $totalLen) : $totalLen;
        $trimmedLabels = $labels ? array_slice($labels, -$ventana) : [];

        $rmses = ['linear' => [], 'poly' => [], 'rf' => []];
        $maes  = ['linear' => [], 'poly' => [], 'rf' => []];
        $r2s   = ['linear' => [], 'poly' => [], 'rf' => []];
        $selectedRmses = [];
        $selectedMaes  = [];
        $selectedR2s   = [];
        $totalProjects = 0;

        $salida = [];
        foreach ($areas as $areaData) {
            $history = array_values($areaData['history'] ?? []);
            if ($ventana > 0 && count($history) > $ventana) {
                $history = array_slice($history, -$ventana);
            }
            if (count($history) === 0) continue;
            $y = array_map('floatval', $history);
            $totalHistArea = (int)array_sum($y);
            $totalProjects += $totalHistArea;

            $candidatos = [];
            foreach (self::BASE_MODELOS as $tipo) {
                [$curva, $rmse, $mae, $r2] = $this->ajustarModeloPhp($tipo, $y, $predSteps);
                $candidatos[$tipo] = [
                    'curva' => $curva,
                    'rmse'  => $rmse,
                    'mae'   => $mae,
                    'r2'    => $r2,
                ];
                $rmses[$tipo][] = $rmse;
                $maes[$tipo][]  = $mae;
                $r2s[$tipo][]   = $r2;
            }

            $bestKind = 'linear';
            if (count($y) >= 4 && count(array_filter($y, fn($v) => $v > 0)) > 1) {
                $bestScore = INF;
                foreach (self::BASE_MODELOS as $tipo) {
                    $pen = $tipo === 'poly' ? 1.06 : ($tipo === 'rf' ? 1.15 : 1.0);
                    $sc = $candidatos[$tipo]['rmse'] * $pen;
                    if ($sc < $bestScore) {
                        $bestScore = $sc;
                        $bestKind = $tipo;
                    }
                }
            }

            $chosenKind = ($modelo === 'auto') ? $bestKind : (isset($candidatos[$modelo]) ? $modelo : 'linear');
            $chosen     = $candidatos[$chosenKind];
            $curvaSel   = $chosen['curva'];
            $rmseSel    = $chosen['rmse'];
            $maeSel     = $chosen['mae'];
            $r2Sel      = $chosen['r2'];

            $selectedRmses[] = $rmseSel;
            $selectedMaes[]  = $maeSel;
            $selectedR2s[]   = $r2Sel;

            $capacity = isset($areaData['capacity']) ? (float)$areaData['capacity'] : 25.0;
            $ultimo = end($curvaSel) ?: 0.0;
            $pico   = !empty($curvaSel) ? max($curvaSel) : 0.0;

            $ciLower = array_map(fn($p) => round(max(0.0, $p - $rmseSel), 2), $curvaSel);
            $ciUpper = array_map(fn($p) => round($p + $rmseSel, 2), $curvaSel);

            $salida[] = [
                'id_linea'            => $areaData['id_linea'] ?? null,
                'name'                => $areaData['area'] ?? ($areaData['name'] ?? 'Desconocida'),
                'id_carrera'          => $areaData['id_carrera'] ?? null,
                'carrera_nombre'      => $areaData['carrera_nombre'] ?? 'General',
                'history'             => $history,
                'total_historico'     => $totalHistArea,
                'promedio_historico'  => round(array_sum($y) / max(1, count($y)), 2),
                'ultimo_trimestre'    => (int)end($y),
                'adoption_curve'      => $curvaSel,
                'ci_lower'            => $ciLower,
                'ci_upper'            => $ciUpper,
                'capacity'            => $capacity,
                'saturation_index'    => round(min(100.0, max(0.0, (max($ultimo, $pico) / ($capacity ?: 25.0)) * 100)), 2),
                'rmse'                => round($rmseSel, 4),
                'mae'                 => round($maeSel, 4),
                'r2'                  => round($r2Sel, 4),
                'model_applied'       => $chosenKind,
                'model_applied_label' => self::MODELOS[$chosenKind] ?? $chosenKind,
                'best_model'          => $bestKind,
                'best_model_label'    => self::MODELOS[$bestKind] ?? $bestKind,
            ];
        }

        if (!$salida) {
            return ['error' => 'Error Crítico: Datos insuficientes para la proyección.'];
        }

        $media = fn($v) => $v ? round(array_sum($v) / count($v), 4) : 0.0;
        $globalRmse = $media($selectedRmses);
        $globalMae  = $media($selectedMaes);
        $globalR2   = $media($selectedR2s);

        $covScore = min(1.0, $ventana / 6.0) * 35.0;
        $volScore = min(1.0, $totalProjects / 20.0) * 25.0;
        $fitScore = max(0.0, min(1.0, $globalR2 > 0 ? $globalR2 : (1.0 / (1.0 + $globalRmse)))) * 40.0;
        $confidenceScore = round(min(99.0, max(15.0, $covScore + $volScore + $fitScore)), 1);

        $rmseByModel = array_map($media, $rmses);
        asort($rmseByModel);
        $bestGlobal = array_key_first($rmseByModel) ?: 'linear';

        return [
            'type'          => 'trends_projection',
            'engine'        => 'php-fallback',
            'engine_detail' => 'Motor matemático PHP ' . PHP_VERSION . ' (respaldo)',
            'model_used'    => $modelo,
            'model_label'   => (self::MODELOS[$modelo] ?? $modelo) . ($modelo === 'rf' ? ' (aprox. determinista)' : ''),
            'history_terms' => $trimmedLabels,
            'areas'         => $salida,
            'metrics'       => [
                'global_rmse'             => $globalRmse,
                'global_mae'              => $globalMae,
                'global_r2'               => $globalR2,
                'confidence_score'        => $confidenceScore,
                'total_projects_analyzed' => $totalProjects,
                'best_global_model'       => $bestGlobal,
                'best_global_model_label' => self::MODELOS[$bestGlobal] ?? $bestGlobal,
                'rmse_by_model'           => array_map($media, $rmses),
                'mae_by_model'            => array_map($media, $maes),
                'r2_by_model'             => array_map($media, $r2s),
            ],
            'params'        => [
                'historial_base' => $historial,
                'points_used'    => $ventana,
                'pred_steps'     => $predSteps,
                'n_estimators'   => null,
            ],
        ];
    }

    private function ajustarModeloPhp(string $tipo, array $y, int $pasos): array {
        $n = count($y);
        if ($n < 2 || count(array_unique($y)) === 1) {
            return [array_fill(0, $pasos, round(max(0.0, $y[$n - 1] ?? 0.0), 2)), 0.0, 0.0, 1.0];
        }

        if ($tipo === 'rf') {
            $k = min(3, $n);
            $ajuste = [];
            for ($i = 0; $i < $n; $i++) {
                $desde = max(0, $i - $k + 1);
                $tramo = array_slice($y, $desde, $i - $desde + 1);
                $ajuste[] = array_sum($tramo) / count($tramo);
            }
            $ultimo = array_sum(array_slice($y, -$k)) / $k;
            $curva = array_fill(0, $pasos, round(max(0.0, $ultimo), 2));
            return [$curva, $this->rmse($y, $ajuste), $this->mae($y, $ajuste), $this->r2($y, $ajuste)];
        }

        if ($tipo === 'poly' && $n >= 3 && ($coef = $this->minimosCuadradosCuadratico($y)) !== null) {
            [$a, $b, $c] = $coef;
            $f = fn($x) => $a + $b * $x + $c * $x * $x;
            $ajuste = array_map($f, range(0, $n - 1));
            $capUpper = max(max($y) * 2.5 + 5.0, 15.0);
            $curva = [];
            for ($i = 0; $i < $pasos; $i++) {
                $curva[] = round(min($capUpper, max(0.0, $f($n + $i))), 2);
            }
            return [$curva, $this->rmse($y, $ajuste), $this->mae($y, $ajuste), $this->r2($y, $ajuste)];
        }

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
        return [$curva, $this->rmse($y, $ajuste), $this->mae($y, $ajuste), $this->r2($y, $ajuste)];
    }

    private function minimosCuadradosCuadratico(array $y): ?array {
        $s = array_fill(0, 5, 0.0);
        $t = array_fill(0, 3, 0.0);
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

    private function mae(array $y, array $ajuste): float {
        $n = count($y);
        if ($n === 0) return 0.0;
        $suma = 0.0;
        for ($i = 0; $i < $n; $i++) $suma += abs($y[$i] - $ajuste[$i]);
        return $suma / $n;
    }

    private function r2(array $y, array $ajuste): float {
        $n = count($y);
        if ($n === 0) return 0.0;
        $mean = array_sum($y) / $n;
        $ssTot = 0.0;
        $ssRes = 0.0;
        for ($i = 0; $i < $n; $i++) {
            $ssTot += ($y[$i] - $mean) ** 2;
            $ssRes += ($y[$i] - $ajuste[$i]) ** 2;
        }
        if ($ssTot < 1e-9) return $ssRes < 1e-6 ? 1.0 : 0.0;
        return max(0.0, min(1.0, 1.0 - ($ssRes / $ssTot)));
    }

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
