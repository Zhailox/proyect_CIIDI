<?php
// core/Views/500.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$mensajeError = $dbErrorMsg ?? ($_SESSION['db_connection_error'] ?? null);
$standalone = $isStandalone ?? true;

// 1. Manejar acción de cerrar sesión limpia
if (isset($_GET['emerg_action']) && $_GET['emerg_action'] === 'logout') {
    $_SESSION = [];
    session_destroy();
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// 2. Manejar actualización de credenciales de BD desde modo emergencia
$mensajeEmerg = null;
$exitoEmerg = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['emerg_update_db'])) {
    if (!empty($_SESSION['modo_emergencia_aislado'])) {
        $h = trim($_POST['host'] ?? 'localhost');
        $p = trim($_POST['port'] ?? '5432');
        $d = trim($_POST['db'] ?? 'ciidi');
        $u = trim($_POST['user'] ?? 'miki');
        $pass = $_POST['pass'] ?? '';

        $configFile = defined('STORAGE_PATH') ? STORAGE_PATH . 'db_config.json' : __DIR__ . '/../../storage/db_config.json';
        $dir = dirname($configFile);
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        $saveData = [
            'host' => $h, 'port' => $p, 'db' => $d, 'user' => $u, 'pass' => $pass,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if (file_put_contents($configFile, json_encode($saveData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false) {
            $exitoEmerg = true;
            $mensajeEmerg = "¡Credenciales de la Base de Datos guardadas correctamente! Haz clic en 'Reintentar Conexión'.";
        } else {
            $mensajeEmerg = "Error al escribir en el archivo storage/db_config.json.";
        }
    }
}

// 3. Manejar Login de Emergencia Break-Glass
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['emerg_login'])) {
    $usuarioInput = trim($_POST['emerg_user'] ?? '');
    $passInput = $_POST['emerg_pass'] ?? '';

    $emergFile = defined('STORAGE_PATH') ? STORAGE_PATH . 'emergency_admin.json' : __DIR__ . '/../../storage/emergency_admin.json';
    if (file_exists($emergFile)) {
        $emergData = json_decode(file_get_contents($emergFile), true) ?: [];
        if (!empty($emergData['activo']) && strtolower($emergData['usuario']) === strtolower($usuarioInput)) {
            if (password_verify($passInput, $emergData['hash_contrasena'])) {
                $_SESSION['modo_emergencia_aislado'] = true;
                $_SESSION['emerg_user_nombre'] = $emergData['usuario'];
            } else {
                $mensajeEmerg = "Contraseña de emergencia incorrecta.";
            }
        } else {
            $mensajeEmerg = "No existe una cuenta de emergencia con el usuario '{$usuarioInput}'.";
        }
    } else {
        $mensajeEmerg = "No existe el archivo de cuenta de emergencia en storage/emergency_admin.json.";
    }
}

// Obtener credenciales actuales para autorellenar el formulario de reparación
$configFileCurrent = defined('STORAGE_PATH') ? STORAGE_PATH . 'db_config.json' : __DIR__ . '/../../storage/db_config.json';
$currentCreds = file_exists($configFileCurrent) ? (json_decode(file_get_contents($configFileCurrent), true) ?: []) : [];
$hostCur = $currentCreds['host'] ?? 'localhost';
$portCur = $currentCreds['port'] ?? '5432';
$dbCur   = $currentCreds['db'] ?? 'ciidi';
$userCur = $currentCreds['user'] ?? 'miki';
$passCur = $currentCreds['pass'] ?? '';
?>
<?php if ($standalone): ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Modo Emergencia | CIIDI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { margin: 0; padding: 2rem 1rem; background-color: #f8fafc; font-family: 'Inter', system-ui, sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; color: #1e293b; box-sizing: border-box; }
        .ag-emerg-input { width: 100%; padding: 9px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.86rem; box-sizing: border-box; }
        .ag-emerg-input:focus { outline: none; border-color: #2563eb; }
        .ag-emerg-btn { background: #2563eb; color: #fff; border: none; padding: 10px 16px; border-radius: 8px; font-weight: 700; font-size: 0.88rem; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 6px; }
        .ag-emerg-btn:hover { background: #1d4ed8; }
        .ag-emerg-btn-danger { background: #dc2626; color: #fff; border: none; padding: 10px 16px; border-radius: 8px; font-weight: 700; font-size: 0.88rem; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 6px; }
        .ag-emerg-btn-danger:hover { background: #b91c1c; }
    </style>
</head>
<body>
<?php endif; ?>

<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%;">
    <div style="max-width: 900px; width: 100%; background: #ffffff; border-radius: 16px; padding: 1.8rem; box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08); border: 1px solid rgba(0, 0, 0, 0.08); box-sizing: border-box;">
        
        <!-- ILUSTRACIÓN 500 ERROR -->
        <div style="text-align: center; margin-bottom: 1.25rem;">
            <img src="assets/img/500.jpg" alt="Error 500 - Error de Conexión" style="width: 100%; max-height: 280px; height: auto; border-radius: 12px; object-fit: contain; display: block; margin: 0 auto;">
        </div>

        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 1rem; margin-bottom: 1.25rem;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 42px; height: 42px; background: rgba(239,68,68,0.12); color: #dc2626; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                    <i class="ph-bold ph-warning-octagon"></i>
                </div>
                <div>
                    <h2 style="margin: 0; font-size: 1.3rem; font-weight: 800; color: #0f172a;">Aislamiento de Emergencia - Fallo BD</h2>
                    <span style="font-size: 0.8rem; color: #64748b;">Modo seguro independiente del Kernel</span>
                </div>
            </div>
            <div style="display: flex; gap: 8px;">
                <a href="?emerg_action=logout" style="background: #f1f5f9; color: #dc2626; border: 1px solid #fca5a5; padding: 7px 12px; border-radius: 8px; font-weight: 700; font-size: 0.8rem; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                    <i class="ph-bold ph-sign-out"></i> Limpiar Sesión
                </a>
                <button type="button" onclick="location.reload()" class="ag-emerg-btn" style="padding: 7px 14px; font-size: 0.8rem;">
                    <i class="ph-bold ph-arrows-counter-clockwise"></i> Reintentar Conexión
                </button>
            </div>
        </div>

        <?php if (!empty($mensajeError)): ?>
            <div style="padding: 0.75rem 1rem; background: #fff1f2; border: 1px solid #fecdd3; border-radius: 8px; color: #9f1239; font-size: 0.84rem; font-family: monospace; word-break: break-word; margin-bottom: 1.25rem;">
                <strong>Excepción BD:</strong> <?= htmlspecialchars($mensajeError) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($mensajeEmerg)): ?>
            <div style="padding: 0.85rem 1.1rem; background: <?= $exitoEmerg ? 'rgba(16,185,129,0.1)' : 'rgba(239,68,68,0.1)' ?>; border: 1px solid <?= $exitoEmerg ? 'rgba(16,185,129,0.3)' : 'rgba(239,68,68,0.3)' ?>; border-radius: 10px; color: <?= $exitoEmerg ? '#047857' : '#b91c1c' ?>; font-weight: 700; font-size: 0.86rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold <?= $exitoEmerg ? 'ph-check-circle' : 'ph-warning-circle' ?>"></i>
                <?= htmlspecialchars($mensajeEmerg) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['modo_emergencia_aislado'])): ?>
            <!-- FORMULARIO DE ACTUALIZACIÓN DE CREDENCIALES BD (MODO EMERGENCIA AUTENTICADO) -->
            <div style="background: rgba(37,99,235,0.04); border: 1px solid rgba(37,99,235,0.2); border-radius: 12px; padding: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <div style="font-weight: 800; font-size: 1.05rem; color: #1e40af; display: flex; align-items: center; gap: 8px;">
                        <i class="ph-bold ph-database"></i> Modificar Parámetros de Conexión PostgreSQL
                    </div>
                    <span style="background: #dbeafe; color: #1e40af; font-size: 0.75rem; font-weight: 800; padding: 3px 10px; border-radius: 20px;">
                        Modo Autenticado (<?= htmlspecialchars($_SESSION['emerg_user_nombre'] ?? 'Admin') ?>)
                    </span>
                </div>
                
                <form action="" method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
                    <input type="hidden" name="emerg_update_db" value="1">
                    
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 10px;">
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">Host del Servidor</label>
                            <input type="text" name="host" class="ag-emerg-input" value="<?= htmlspecialchars($hostCur) ?>" required>
                        </div>
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">Puerto</label>
                            <input type="text" name="port" class="ag-emerg-input" value="<?= htmlspecialchars($portCur) ?>" required>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">Base de Datos</label>
                            <input type="text" name="db" class="ag-emerg-input" value="<?= htmlspecialchars($dbCur) ?>" required>
                        </div>
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">Usuario PostgreSQL</label>
                            <input type="text" name="user" class="ag-emerg-input" value="<?= htmlspecialchars($userCur) ?>" required>
                        </div>
                    </div>

                    <div>
                        <label style="font-size: 0.8rem; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">Contraseña PostgreSQL</label>
                        <input type="password" name="pass" class="ag-emerg-input" value="<?= htmlspecialchars($passCur) ?>" placeholder="Contraseña de la BD">
                    </div>

                    <button type="submit" class="ag-emerg-btn" style="padding: 11px; font-size: 0.9rem; margin-top: 0.4rem;">
                        <i class="ph-bold ph-floppy-disk"></i> Guardar Nuevas Credenciales BD
                    </button>
                </form>
            </div>
        <?php else: ?>
            <!-- FORMULARIO DE ACCESO CON USUARIO DE EMERGENCIA LOCAL -->
            <div style="background: rgba(239, 68, 68, 0.04); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 12px; padding: 1.5rem; max-width: 520px; margin: 0 auto; text-align: left;">
                <div style="display: flex; align-items: center; gap: 8px; color: #dc2626; font-weight: 800; font-size: 1.05rem; margin-bottom: 0.4rem;">
                    <i class="ph-bold ph-key"></i> Autenticación de Emergencia Local (Break-Glass)
                </div>
                <p style="font-size: 0.82rem; color: #64748b; margin: 0 0 1.2rem 0; line-height: 1.45;">
                    Ingresa con tu usuario y contraseña de emergencia local para desbloquear el formulario de configuración de la Base de Datos.
                </p>
                
                <form action="" method="POST" style="display: flex; flex-direction: column; gap: 0.85rem;">
                    <input type="hidden" name="emerg_login" value="1">
                    
                    <div>
                        <label style="font-size: 0.78rem; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">Usuario de Emergencia</label>
                        <input type="text" name="emerg_user" class="ag-emerg-input" placeholder="Nombre de usuario local" required>
                    </div>
                    <div>
                        <label style="font-size: 0.78rem; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">Contraseña</label>
                        <input type="password" name="emerg_pass" class="ag-emerg-input" placeholder="Contraseña de emergencia" required>
                    </div>
                    <button type="submit" class="ag-emerg-btn-danger" style="padding: 11px; font-size: 0.9rem; margin-top: 0.3rem;">
                        <i class="ph-bold ph-shield-check"></i> Desbloquear Formulario de Reparación
                    </button>
                </form>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php if ($standalone): ?>
</body>
</html>
<?php endif; ?>
