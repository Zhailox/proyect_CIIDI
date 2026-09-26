<?php
// Solicitamos los datos limpios a la clase de Seguridad
require_once CORE_PATH . 'Security/Auth.php';
$usuarioHeader = Auth::usuario();

// Lógica de presentación (Obtener primera letra del primer nombre)
$primerNombreHeader = explode(' ', trim($usuarioHeader['nombre']))[0];
$inicialHeader = strtoupper(substr($primerNombreHeader, 0, 1));
?>

<a href="perfil" class="header-profile-widget" title="Ir a mi perfil">
    <div class="header-avatar-mini">
        <?= htmlspecialchars($inicialHeader) ?>
    </div>
    <div class="header-user-info">
        <span class="header-user-name"><?= htmlspecialchars($primerNombreHeader) ?></span>
        <span class="header-user-role"><?= htmlspecialchars($usuarioHeader['rol']) ?></span>
    </div>
</a>