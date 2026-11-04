<?php
// core/Views/deshabilitado.php
http_response_code(503);
$mensajeRutaDeshabilitada = !empty($mensajeRutaDeshabilitada) 
    ? $mensajeRutaDeshabilitada 
    : "Esta funcionalidad se encuentra temporalmente deshabilitada por el administrador.";
$modoRuta = !empty($modoRuta) ? $modoRuta : 'desactivado';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - Funcionalidad Deshabilitada | CIIDI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="core-maint-body">
    <div class="mantenimiento-wrapper">
        <img src="assets/img/503.webp" alt="503 - Funcionalidad Deshabilitada" class="mantenimiento-img">
        
        <?php if (!empty($mensajeRutaDeshabilitada)): ?>
            <p class="mantenimiento-msg">
                <i class="<?= $modoRuta === 'solo_lectura' ? 'ph-bold ph-eye-slash maint-icon-ro' : 'ph-bold ph-prohibit maint-icon-off' ?>"></i>
                <?= htmlspecialchars($mensajeRutaDeshabilitada) ?>
            </p>
        <?php endif; ?>

        <div class="mantenimiento-actions">
            <a href="inicio" class="btn-action">
                <i class="ph ph-house core-pill-icon"></i> Ir al Inicio
            </a>
            <button type="button" onclick="history.back()" class="btn-action btn-secondary">
                <i class="ph ph-arrow-left core-pill-icon"></i> Regresar
            </button>
        </div>
    </div>
</body>
</html>

