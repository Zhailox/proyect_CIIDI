<?php
// core/Views/500.php
$mensajeError = $dbErrorMsg ?? null;
$standalone = $isStandalone ?? false;
?>
<?php if ($standalone): ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Fallo de Conexión | CIIDI</title>
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
    </style>
</head>
<body>
<?php endif; ?>

<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 82vh; padding: 0.5rem; text-align: center; width: 100%;">
    <div style="max-width: 1000px; width: 92%; background: #ffffff; border-radius: 16px; padding: 1.25rem; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); border: 1px solid rgba(0, 0, 0, 0.06); box-sizing: border-box;">
        <img src="assets/img/500.jpg" alt="Error 500 - Error Interno del Servidor" style="width: 100%; max-height: 70vh; height: auto; border-radius: 12px; display: block; object-fit: contain; margin: 0 auto;">
        
        <?php if (!empty($mensajeError)): ?>
            <div style="margin-top: 1rem; padding: 0.75rem 1rem; background: #fff1f2; border: 1px solid #fecdd3; border-radius: 8px; color: #9f1239; font-size: 0.85rem; max-width: 750px; margin-left: auto; margin-right: auto; text-align: left; font-family: monospace; word-break: break-word;">
                <strong>Detalle Técnico del Error:</strong> <?= htmlspecialchars($mensajeError) ?>
            </div>
        <?php endif; ?>

        <div style="margin-top: 1.25rem; display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
            <a href="?ruta=inicio" style="display: inline-flex; align-items: center; gap: 0.5rem; background: var(--color-secundario, #002244); color: #ffffff; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 0.95rem;">
                <i class="ph ph-house" style="font-size: 1.2rem;"></i> Regresar al Inicio
            </a>
            <button type="button" onclick="location.reload()" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #f1f5f9; color: #334155; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; border: 1px solid #cbd5e1; cursor: pointer; font-size: 0.95rem;">
                <i class="ph ph-arrows-counter-clockwise" style="font-size: 1.2rem;"></i> Reintentar
            </button>
        </div>
    </div>
</div>

<?php if ($standalone): ?>
</body>
</html>
<?php endif; ?>
