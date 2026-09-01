<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema en Mantenimiento - CIIDI</title>
    <!-- Referenciamos su propio CSS en la carpeta pública -->
     <link rel="stylesheet" href="../public/assets/css/style.css">
    <link rel="stylesheet" href="../public/assets/css/mantenimiento.css">
    <!-- Iconos Phosphor -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>
    <div class="mantenimiento-container">
        <i class="ph-fill ph-wrench mantenimiento-icon"></i>
        <h1 class="mantenimiento-title">Sistema en Mantenimiento</h1>
        <p class="mantenimiento-text"><?= htmlspecialchars($mensajeCustom) ?></p>
        
        <a href="login" class="mantenimiento-link">
            <i class="ph-bold ph-lock-key"></i> Acceso Administrativo
        </a>
    </div>
</body>
</html>