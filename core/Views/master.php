<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo_pagina); ?></title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <link rel="stylesheet" href="assets/css/style.css">
    
    <?php 
    $cssGlobalesComponentes = $this->getGlobalCss();
    foreach ($cssGlobalesComponentes as $estilo_componente) {
        echo '<link rel="stylesheet" href="' . htmlspecialchars($estilo_componente) . '">';
    }
    ?>
    <?php 
    $cssModulos = $this->getGlobalCss();
    foreach ($cssModulos as $archivoCss) {
        echo '<link rel="stylesheet" href="' . $archivoCss . '">';
    }
    ?>
    <?php if (!empty($css_modulo)): ?>
        <?php foreach ($css_modulo as $estilo_vista): ?>
            <link rel="stylesheet" href="<?php echo htmlspecialchars($estilo_vista); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    
    <?php if ($layout_config['header'] ?? true): ?>
        <?php include CORE_VIEWS . 'header.php'; ?>
    <?php endif; ?>

    <main class="app-container">
        
        <?php if ($layout_config['sidebar'] ?? true): ?>
            <?php include CORE_VIEWS . 'sidebar.php'; ?>
        <?php endif; ?>
        
        <div class="main-content">
            <?php 
            if (!empty($vista_modulo_path) && file_exists($vista_modulo_path)) {
                include $vista_modulo_path;
            } else {
                echo "<h2>Error</h2><p>La vista no existe.</p>";
            }
            ?>
        </div>

    </main>

    <?php if ($layout_config['footer'] ?? true): ?>
        <?php include CORE_VIEWS . 'footer.php'; ?>
    <?php endif; ?>

</body>
</html>