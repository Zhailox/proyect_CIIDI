<?php
// Solicitamos los datos limpios a la clase de Seguridad
require_once CORE_PATH . 'Security/Auth.php';
$usuarioHeader = Auth::usuario();

// Lógica de presentación con fallback múltiple de claves de sesión
$nombreCompleto = $usuarioHeader['nombre'] 
    ?? $_SESSION['nombre'] 
    ?? $_SESSION['usuario_nombre'] 
    ?? $_SESSION['nombre_completo'] 
    ?? $_SESSION['usuario'] 
    ?? $_SESSION['emerg_user_nombre'] 
    ?? 'Usuario';

$primerNombreHeader = explode(' ', trim($nombreCompleto))[0];
if (empty($primerNombreHeader)) $primerNombreHeader = 'Usuario';
$inicialHeader = strtoupper(substr($primerNombreHeader, 0, 1));

$rolUsuarioHeader = $usuarioHeader['rol'] 
    ?? $_SESSION['rol'] 
    ?? $_SESSION['rol_nombre'] 
    ?? $_SESSION['usuario_rol'] 
    ?? 'Usuario';
?>

<a href="perfil" class="header-profile-widget" title="Ir a mi perfil">
    <div class="header-avatar-mini">
        <?= htmlspecialchars($inicialHeader) ?>
    </div>
    <div class="header-user-info">
        <span class="header-user-name"><?= htmlspecialchars($primerNombreHeader) ?></span>
        <span class="header-user-role"><?= htmlspecialchars($rolUsuarioHeader) ?></span>
    </div>
</a>