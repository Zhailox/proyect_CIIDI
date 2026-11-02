<?php
/**
 * ============================================================================
 * INSTALADOR E INTEGRADOR DE BASE DE DATOS Y CONFIGURACIÓN DEL MACROMÓDULO CIIDI
 * Archivo: instalar_macromodulo_db.php
 * Compatible con ejecución por Consola Linux (CLI) y Navegador Web.
 * ============================================================================
 */

declare(strict_types=1);

$isCli = (PHP_SAPI === 'cli');
$baseDir = __DIR__;

if (!defined('BASE_PATH')) {
    define('BASE_PATH', $baseDir);
}
if (!defined('CORE_PATH')) {
    define('CORE_PATH', $baseDir . '/core/');
}
if (!defined('STORAGE_PATH')) {
    define('STORAGE_PATH', $baseDir . '/storage/');
}
if (!defined('MODULES_PATH')) {
    define('MODULES_PATH', $baseDir . '/modules/');
}

require_once CORE_PATH . 'Database/Connection.php';

$pasos = [];
$errores = [];

function registrarPaso(string $titulo, bool $ok, string $detalle = ''): void {
    global $pasos, $errores, $isCli;
    $pasos[] = ['titulo' => $titulo, 'ok' => $ok, 'detalle' => $detalle];
    if (!$ok) {
        $errores[] = "$titulo: $detalle";
    }
    if ($isCli) {
        $icono = $ok ? "[OK]" : "[ERROR]";
        echo sprintf("%-8s %s%s\n", $icono, $titulo, $detalle !== '' ? " -> $detalle" : "");
    }
}

if ($isCli) {
    echo "======================================================================\n";
    echo " INSTALADOR DEL MACROMÓDULO CIIDI (BD POSTGRESQL + CONFIG + ML PYTHON)\n";
    echo "======================================================================\n\n";
}

// 1. Conectar a PostgreSQL usando las credenciales existentes del servidor
$pdo = null;
try {
    $pdo = Connection::getInstance();
    if (!$pdo) {
        throw new RuntimeException("Connection::getInstance() devolvió null.");
    }
    $ver = (string)$pdo->query("SELECT version()")->fetchColumn();
    registrarPaso("Conexión a PostgreSQL del Servidor", true, strtok($ver, ','));
} catch (Throwable $e) {
    registrarPaso("Conexión a PostgreSQL del Servidor", false, $e->getMessage());
}

// 2. Ejecutar migraciones SQL idempotentes del Macromódulo y sus integraciones
$migraciones = [
    'migrations/004_profesor_linea.sql'                      => 'Tabla pivote profesor_linea (Afiliación Docente <-> Línea)',
    'migrations/004_profesores_pst_postulaciones.sql'        => 'Registro Docente en línea, Profesores PST y Postulaciones',
    'migrations/005_vinculacion_formulario_archivo.sql'      => 'Formulario dinámico y archivado suave en Vinculación Empresarial',
    'migrations/007_reparacion_integral_utf8_unaccent.sql'   => 'Funciones unaccent y normalizar_busqueda_pst en PostgreSQL',
    'migrations/007_solicitudes_acreditacion_pst.sql'        => 'Solicitudes de Acreditación Docente PST desde Perfil',
    'migrations/008_cursos_investigaciones_lineas_google.sql'=> 'Integración Cursos, Investigaciones, Líneas y marca es_investigador',
    'migrations/009_seguridad_lineas_schema.sql'             => 'Esquema SOC / RBAC / Bitácora SHA-256 de SeguridadLineas',
    'migrations/010_trayectos_dinamicos_ampliados.sql'       => 'Soporte de Trayectos ampliados y niveles de Postgrado (1..99)',
    'migrations/011_analitica_snapshots.sql'                 => 'Tabla analitica_snapshots para Snapshots y Backtesting ML',
    'migrations/012_profesor_dimensiones.sql'                => 'Tabla profesor_dimension (Especialidad por Dimensión y Carrera)',
    'migrations/013_perfil_investigador_orcid_foto.sql'      => 'Campos académicos de profesor/investigador (foto_perfil, orcid, sitio_web, grado_academico)',
];

if ($pdo) {
    foreach ($migraciones as $relPath => $descripcion) {
        $fullPath = $baseDir . '/' . $relPath;
        if (!is_file($fullPath)) {
            registrarPaso("Migración: $relPath", false, "Archivo no encontrado ($descripcion)");
            continue;
        }
        try {
            $sql = (string)file_get_contents($fullPath);
            $pdo->exec($sql);
            registrarPaso("Migración: $relPath", true, $descripcion);
        } catch (Throwable $e) {
            registrarPaso("Migración: $relPath", false, $e->getMessage());
        }
    }

    // Inicializar esquema y semillas por defecto de SeguridadLineas si el módulo está presente
    $segSchemaFile = MODULES_PATH . 'SeguridadLineas/services/SeguridadSchema.php';
    if (is_file($segSchemaFile)) {
        try {
            require_once $segSchemaFile;
            if (class_exists('SeguridadSchema')) {
                SeguridadSchema::asegurar($pdo);
                registrarPaso("Semillas iniciales de SeguridadLineas (SOC/RBAC)", true, "Esquema y permisos base verificados");
            }
        } catch (Throwable $e) {
            registrarPaso("Semillas iniciales de SeguridadLineas (SOC/RBAC)", false, $e->getMessage());
        }
    }
}

// 3. Sincronizar storage/config_system.json de forma no destructiva
try {
    $cfgSysPath = STORAGE_PATH . 'config_system.json';
    $cfgSys = is_file($cfgSysPath) ? (json_decode((string)file_get_contents($cfgSysPath), true) ?: []) : [];
    if (!isset($cfgSys['modulos']) || !is_array($cfgSys['modulos'])) {
        $cfgSys['modulos'] = [];
    }
    $modulosMacromodulo = [
        'LineasInvestigacion',
        'Investigaciones',
        'VinculacionEmpresarial',
        'SeguridadLineas',
        'Cursos',
    ];
    foreach ($modulosMacromodulo as $mod) {
        if (!isset($cfgSys['modulos'][$mod])) {
            $cfgSys['modulos'][$mod] = ['estado' => 'online'];
        }
    }
    file_put_contents($cfgSysPath, json_encode($cfgSys, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    registrarPaso("Sincronización de storage/config_system.json", true, "Módulos del Macromódulo registrados en estado online");
} catch (Throwable $e) {
    registrarPaso("Sincronización de storage/config_system.json", false, $e->getMessage());
}

// 4. Sincronizar storage/system_config.json (accesos_modulos) sin tocar SMTP ni credenciales
try {
    $sysCfgPath = STORAGE_PATH . 'system_config.json';
    $sysCfg = is_file($sysCfgPath) ? (json_decode((string)file_get_contents($sysCfgPath), true) ?: []) : [];
    if (!isset($sysCfg['accesos_modulos']) || !is_array($sysCfg['accesos_modulos'])) {
        $sysCfg['accesos_modulos'] = [];
    }
    $accesosDefault = [
        'cursos'                  => ['publico' => 999, 'admin' => 1],
        'investigaciones'         => ['publico' => 999, 'admin' => 1],
        'lineas_investigacion'    => ['publico' => 999, 'admin' => 1],
        'vinculacion_empresarial' => ['publico' => 999, 'admin' => 2],
        'autenticacion'           => ['publico' => 998],
    ];
    foreach ($accesosDefault as $k => $v) {
        if (!isset($sysCfg['accesos_modulos'][$k])) {
            $sysCfg['accesos_modulos'][$k] = $v;
        }
    }
    file_put_contents($sysCfgPath, json_encode($sysCfg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    registrarPaso("Sincronización de storage/system_config.json", true, "Niveles de acceso del Macromódulo preservados");
} catch (Throwable $e) {
    registrarPaso("Sincronización de storage/system_config.json", false, $e->getMessage());
}

// 5. Verificar motor Python 3 y librerías científicas (numpy, scipy, scikit-learn)
$mlScript = STORAGE_PATH . 'modelos_ia/ml_pipeline.py';
if (is_file($mlScript)) {
    $candidatosPython = [
        STORAGE_PATH . 'modelos_ia/.venv/bin/python3',
        STORAGE_PATH . 'modelos_ia/venv/bin/python3',
        '/usr/bin/python3',
        '/usr/local/bin/python3',
        'python3',
        'python',
    ];
    $pythonBin = null;
    foreach ($candidatosPython as $cand) {
        if (str_contains($cand, '/')) {
            if (is_file($cand) && is_executable($cand)) {
                $pythonBin = $cand;
                break;
            }
        } elseif (DIRECTORY_SEPARATOR === '/') {
            $which = trim((string)@shell_exec("command -v " . escapeshellarg($cand) . " 2>/dev/null"));
            if ($which !== '') {
                $pythonBin = $which;
                break;
            }
        }
    }
    if ($pythonBin === null && DIRECTORY_SEPARATOR === '\\') {
        $pythonBin = 'python';
    }

    if ($pythonBin !== null) {
        $checkCmd = escapeshellarg($pythonBin) . ' -c "import numpy, scipy, sklearn; print(\'OK:\' + sklearn.__version__)" 2>&1';
        $checkOut = trim((string)@shell_exec($checkCmd));
        if (str_contains($checkOut, 'OK:')) {
            registrarPaso("Motor IA Python 3 (numpy, scipy, scikit-learn)", true, "Binario: $pythonBin ($checkOut)");
        } else {
            registrarPaso("Motor IA Python 3 (numpy, scipy, scikit-learn)", true, "Binario: $pythonBin (Usando motor matemático de respaldo o instale python3-sklearn: $checkOut)");
        }
    } else {
        registrarPaso("Motor IA Python 3", true, "Python no detectado en PATH; AnaliticaController usará automáticamente el motor matemático PHP de respaldo.");
    }
} else {
    registrarPaso("Archivo storage/modelos_ia/ml_pipeline.py", false, "No encontrado");
}

if ($isCli) {
    echo "\n======================================================================\n";
    if (empty($errores)) {
        echo " INSTALACIÓN DEL MACROMÓDULO COMPLETADA EXITOSAMENTE (0 ERRORES)\n";
        echo "======================================================================\n";
        exit(0);
    } else {
        echo " INSTALACIÓN FINALIZADA CON " . count($errores) . " ADVERTENCIA(S)/ERROR(ES)\n";
        echo "======================================================================\n";
        exit(1);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Instalador del Macromódulo CIIDI</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; background: #0f172a; color: #f8fafc; margin: 0; padding: 2rem; }
        .card { max-width: 860px; margin: 0 auto; background: #1e293b; border-radius: 14px; padding: 2rem; border: 1px solid #334155; box-shadow: 0 20px 40px rgba(0,0,0,0.35); }
        h1 { margin-top: 0; font-size: 1.45rem; color: #f8fafc; }
        .step { display: flex; justify-content: space-between; align-items: center; gap: 1rem; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 0.5rem; background: #0f172a; border-left: 4px solid #10b981; }
        .step.err { border-left-color: #ef4444; }
        .badge { padding: 0.25rem 0.6rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700; }
        .ok { background: rgba(16,185,129,0.18); color: #34d399; }
        .ko { background: rgba(239,68,68,0.18); color: #f87171; }
        .detail { font-size: 0.82rem; color: #94a3b8; margin-top: 0.2rem; }
    </style>
</head>
<body>
<div class="card">
    <h1>Instalador de Integración del Macromódulo CIIDI</h1>
    <?php foreach ($pasos as $p): ?>
        <div class="step <?= $p['ok'] ? '' : 'err' ?>">
            <div>
                <strong><?= htmlspecialchars($p['titulo']) ?></strong>
                <?php if ($p['detalle'] !== ''): ?>
                    <div class="detail"><?= htmlspecialchars($p['detalle']) ?></div>
                <?php endif; ?>
            </div>
            <span class="badge <?= $p['ok'] ? 'ok' : 'ko' ?>"><?= $p['ok'] ? 'OK' : 'ERROR' ?></span>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
