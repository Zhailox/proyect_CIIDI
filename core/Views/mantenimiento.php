<?php
// core/Views/mantenimiento.php
http_response_code(503);
$mensajeCustom = !empty($mensajeCustom) ? $mensajeCustom : "Estamos realizando labores de optimización. Vuelve en un momento.";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - Sistema en Mantenimiento | CIIDI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #1e293b;
        }
        .mantenimiento-wrapper {
            max-width: 1000px;
            width: 92%;
            background: #ffffff;
            border-radius: 16px;
            padding: 1.25rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.06);
            text-align: center;
            box-sizing: border-box;
            margin: 1.5rem auto;
        }
        .mantenimiento-img {
            width: 100%;
            max-height: 68vh;
            height: auto;
            border-radius: 12px;
            display: block;
            object-fit: contain;
            margin: 0 auto;
        }
        .mantenimiento-msg {
            margin-top: 1.25rem;
            font-size: 1rem;
            color: #475569;
            line-height: 1.5;
            max-width: 650px;
            margin-left: auto;
            margin-right: auto;
        }
        .mantenimiento-actions {
            margin-top: 1.25rem;
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .btn-admin {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--color-secundario, #002244);
            color: #ffffff;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            font-size: 0.95rem;
            transition: opacity 0.2s ease;
        }
        .btn-admin:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="mantenimiento-wrapper">
        <img src="assets/img/503.jpg" alt="503 - Sistema en Mantenimiento" class="mantenimiento-img">
        
        <?php if (!empty($mensajeCustom)): ?>
            <p class="mantenimiento-msg"><?= htmlspecialchars($mensajeCustom) ?></p>
        <?php endif; ?>

        <div class="mantenimiento-actions">
            <a href="login" class="btn-admin">
                <i class="ph ph-lock-key" style="font-size: 1.2rem;"></i> Acceso Administrativo
            </a>
        </div>
    </div>
</body>
</html>