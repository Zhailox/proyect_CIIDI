<?php
/**
 * ============================================================================
 * EMPAQUETADOR OFFLINE DEL MACROMÓDULO CIIDI (WINDOWS + LINUX)
 * Archivo: empaquetar_macromodulo_linux.php
 * ============================================================================
 */

declare(strict_types=1);

$baseDir  = __DIR__;
$outWin   = $baseDir . '/storage/paquete_macromodulo_windows.zip';
$outZip   = $baseDir . '/storage/paquete_macromodulo_linux.zip';
$outTar   = $baseDir . '/storage/paquete_macromodulo_linux.tar';
$outTarGz = $baseDir . '/storage/paquete_macromodulo_linux.tar.gz';

// Asegurar LF en instalar_macromodulo_linux.sh y CRLF en instalar_macromodulo_windows.bat
$shFile = $baseDir . '/instalar_macromodulo_linux.sh';
if (is_file($shFile)) {
    file_put_contents($shFile, str_replace("\r\n", "\n", (string)file_get_contents($shFile)));
}
$batFile = $baseDir . '/instalar_macromodulo_windows.bat';
if (is_file($batFile)) {
    $batContent = str_replace("\r\n", "\n", (string)file_get_contents($batFile));
    file_put_contents($batFile, str_replace("\n", "\r\n", $batContent));
}

$archivosIndividuales = [
    // Instaladores (Windows y Linux)
    'instalar_macromodulo_windows.bat',
    'instalar_macromodulo_linux.sh',
    'instalar_macromodulo_db.php',

    // Core (Macro-Módulos, Guardianes de Rutas, Kernel, Dispatcher, Sidebar)
    'core/Interfaces/RouteGuardContract.php',
    'core/Http/RouteGuardMiddleware.php',
    'core/Http/Dispatcher.php',
    'core/System/Kernel.php',
    'core/System/ModuleLoader.php',
    'core/Views/sidebar.php',
    'core/Views/home_bienvenida.php',

    // Estilos Globales (Sidebar Macro-Módulos + Responsivo)
    'public/assets/css/style.css',

    // Motor de Machine Learning y Backtesting
    'storage/modelos_ia/ml_pipeline.py',

    // Integración Transversal en Autenticación
    'modules/Autenticacion/controllers/LoginController.php',
    'modules/Autenticacion/controllers/PerfilController.php',
    'modules/Autenticacion/models/UsuarioModel.php',
    'modules/Autenticacion/services/PerfilPSTService.php',
    'modules/Autenticacion/views/completar_registro_profesor.php',
    'modules/Autenticacion/views/mi_dashboard.php',
    'modules/Autenticacion/views/partials/selector_pst.php',
    'modules/Autenticacion/views/registro.php',

    // Integración Transversal en SuperAdmin y RepositorioPST
    'modules/SuperAdmin/views/editar_usuario.php',
    'modules/RepositorioPST/services/EmbeddingService.php',

    // Migraciones SQL Idempotentes del Macromódulo (PostgreSQL)
    'migrations/004_profesor_linea.sql',
    'migrations/004_profesores_pst_postulaciones.sql',
    'migrations/005_vinculacion_formulario_archivo.sql',
    'migrations/007_reparacion_integral_utf8_unaccent.sql',
    'migrations/007_solicitudes_acreditacion_pst.sql',
    'migrations/008_cursos_investigaciones_lineas_google.sql',
    'migrations/009_seguridad_lineas_schema.sql',
    'migrations/010_trayectos_dinamicos_ampliados.sql',
    'migrations/011_analitica_snapshots.sql',
    'migrations/012_profesor_dimensiones.sql',
];

$directoriosCompletos = [
    'modules/LineasInvestigacion',
    'modules/Investigaciones',
    'modules/VinculacionEmpresarial',
    'modules/SeguridadLineas',
    'modules/Cursos',
];

$listaFinal = [];
foreach ($archivosIndividuales as $rel) {
    $full = $baseDir . '/' . $rel;
    if (is_file($full)) {
        $listaFinal[$rel] = $full;
    } else {
        echo "[ADVERTENCIA] No encontrado: $rel\n";
    }
}

foreach ($directoriosCompletos as $dirRel) {
    $dirFull = $baseDir . '/' . $dirRel;
    if (!is_dir($dirFull)) continue;
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dirFull, FilesystemIterator::SKIP_DOTS)
    );
    foreach ($it as $fileInfo) {
        if (!$fileInfo->isFile()) continue;
        $fullPath = str_replace('\\', '/', $fileInfo->getPathname());
        $relPath = ltrim(substr($fullPath, strlen(str_replace('\\', '/', $baseDir))), '/');
        if (str_contains($relPath, '__pycache__') || str_ends_with($relPath, '(viejo).php')) {
            continue;
        }
        $listaFinal[$relPath] = $fullPath;
    }
}

ksort($listaFinal);

foreach ([$outWin, $outZip, $outTar, $outTarGz] as $f) {
    if (is_file($f)) @unlink($f);
}

$zip = new ZipArchive();
if ($zip->open($outZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    throw new RuntimeException("No se pudo crear el archivo ZIP en: $outZip");
}

$tar = new PharData($outTar);
$textExtensions = ['php', 'sh', 'py', 'sql', 'css', 'js', 'json', 'html', 'md', 'txt'];

foreach ($listaFinal as $relPath => $fullPath) {
    $ext = strtolower(pathinfo($relPath, PATHINFO_EXTENSION));
    $raw = (string)file_get_contents($fullPath);
    if ($ext === 'bat') {
        $raw = str_replace("\n", "\r\n", str_replace("\r\n", "\n", $raw));
    } elseif (in_array($ext, $textExtensions, true)) {
        $raw = str_replace("\r\n", "\n", $raw);
    }
    $zip->addFromString($relPath, $raw);
    $tar->addFromString($relPath, $raw);
}

$zip->close();
copy($outZip, $outWin);

$tar->compress(Phar::GZ);
unset($tar);
if (is_file($outTar)) @unlink($outTar);

$sizeWin = round(filesize($outWin) / 1024, 1);
$sizeTar = round(filesize($outTarGz) / 1024, 1);
echo "======================================================================\n";
echo " PAQUETES OFFLINE (WINDOWS Y LINUX) GENERADOS EXITOSAMENTE\n";
echo " 1. WINDOWS (.zip)        : $outWin ({$sizeWin} KB)\n";
echo " 2. LINUX (.tar.gz)       : $outTarGz ({$sizeTar} KB)\n";
echo " 3. UNIVERSAL (.zip)      : $outZip ({$sizeWin} KB)\n";
echo " Total de archivos incluidos : " . count($listaFinal) . "\n";
echo "======================================================================\n";
