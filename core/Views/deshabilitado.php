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
            max-height: 62vh;
            height: auto;
            border-radius: 12px;
            display: block;
            object-fit: contain;
            margin: 0 auto;
        }
        .mantenimiento-msg {
            margin-top: 1.25rem;
            font-size: 1.05rem;
            color: #475569;
            line-height: 1.5;
            max-width: 680px;
            margin-left: auto;
            margin-right: auto;
            font-weight: 500;
        }
        .mantenimiento-actions {
            margin-top: 1.25rem;
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .btn-action {
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
            border: none;
            cursor: pointer;
        }
        .btn-action:hover {
            opacity: 0.9;
        }
        .btn-secondary {
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
        }
        .btn-secondary:hover {
            background: #e2e8f0;
            color: #0f172a;
        }
    </style>
</head>
<body>
    <div class="mantenimiento-wrapper">
        <img src="assets/img/503.jpg" alt="503 - Funcionalidad Deshabilitada" class="mantenimiento-img">
        
        <?php if (!empty($mensajeRutaDeshabilitada)): ?>
            <p class="mantenimiento-msg">
                <i class="<?= $modoRuta === 'solo_lectura' ? 'ph-bold ph-eye-slash' : 'ph-bold ph-prohibit' ?>" style="color: <?= $modoRuta === 'solo_lectura' ? '#d97706' : '#dc2626' ?>; margin-right: 4px;"></i>
                <?= htmlspecialchars($mensajeRutaDeshabilitada) ?>
            </p>
        <?php endif; ?>

        <div class="mantenimiento-actions">
            <a href="inicio" class="btn-action">
                <i class="ph ph-house" style="font-size: 1.2rem;"></i> Ir al Inicio
            </a>
            <button type="button" onclick="history.back()" class="btn-action btn-secondary">
                <i class="ph ph-arrow-left" style="font-size: 1.2rem;"></i> Regresar
            </button>
        </div>
    </div>
</body>
</html>

