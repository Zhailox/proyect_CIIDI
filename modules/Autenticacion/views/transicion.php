<?php
// Validamos que existan datos enviados por el controlador
if (!isset($destino)) {
    $destino = 'login';
    // header("Location: login");
    // exit;
}

$esError = $es_error ?? false;
?>

<?php if (!$esError): ?>
    <meta http-equiv="refresh" content="2;url=<?= htmlspecialchars($destino) ?>">
<?php endif; ?>

<div class="splash-fullscreen">
    <div class="splash-content <?= $esError ? 'error-mode' : 'success-mode' ?>">
        
        <?php if (!$esError): ?>
            <?php $primerNombre = explode(' ', trim($nombre_usuario))[0]; ?>
            <div class="splash-loader"></div>
            <h1 class="splash-title">¡Hola, <?= htmlspecialchars($primerNombre) ?>!</h1>
            <p class="splash-subtitle">Preparando tu entorno de trabajo en el Ecosistema UPTTMBI...</p>
            <div class="splash-role-badge"><?= htmlspecialchars($rol_nombre) ?></div>
            
            <?php else: ?>
            <div class="splash-icon-error">
                <i class="ph-fill ph-warning-circle"></i>
            </div>
            <h1 class="splash-title splash-title-error">Acceso Denegado</h1>
            <p class="splash-subtitle"><?= htmlspecialchars($mensaje) ?></p>
            
            <a href="<?= htmlspecialchars($destino) ?>" class="splash-btn-back">
                <i class="ph-bold ph-arrow-left"></i> Volver al Login
            </a>
        <?php endif; ?>

    </div>
</div>