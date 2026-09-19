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
        .mantenimiento-container {
            width: 100%;
            max-width: 950px;
            padding: 1.5rem 1rem;
            text-align: center;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .mantenimiento-img {
            max-width: 900px;
            width: 95%;
            max-height: 65vh;
            height: auto;
            display: block;
            object-fit: contain;
            margin: 0 auto;
            filter: drop-shadow(0 14px 30px rgba(18, 26, 62, 0.14));
            border-radius: 16px;
        }
        .mantenimiento-msg {
            margin-top: 1.25rem;
            font-size: 1.05rem;
            color: #475569;
            line-height: 1.5;
            max-width: 650px;
            margin-left: auto;
            margin-right: auto;
            font-weight: 500;
        }
        .mantenimiento-timer-card {
            margin-top: 1.25rem;
            background: rgba(254, 243, 199, 0.85);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(253, 230, 138, 0.8);
            border-radius: 50px;
            padding: 0.75rem 2rem;
            max-width: 480px;
            box-shadow: 0 4px 15px rgba(217, 119, 6, 0.12);
        }
        .mantenimiento-actions {
            margin-top: 1.5rem;
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .btn-admin-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: var(--color-secundario, #002244);
            color: #ffffff;
            padding: 0.75rem 1.6rem;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            font-size: 0.95rem;
            box-shadow: 0 8px 20px rgba(0, 34, 68, 0.25);
            transition: all 0.25s ease;
        }
        .btn-admin-pill:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(0, 34, 68, 0.35);
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="mantenimiento-container">
        <!-- Imagen Libre Sin Contenedor Blanco Confinado -->
        <img src="assets/img/503.jpg" alt="503 - Sistema en Mantenimiento" class="mantenimiento-img">
        
        <?php if (!empty($mensajeCustom)): ?>
            <p class="mantenimiento-msg"><?= htmlspecialchars($mensajeCustom) ?></p>
        <?php endif; ?>

        <?php if (!empty($fechaFinMantenimiento)): ?>
            <div class="mantenimiento-timer-card">
                <span style="font-weight: 600; color: #92400e; font-size: 0.85rem; display: block; margin-bottom: 0.25rem;">
                    <i class="ph-bold ph-timer"></i> Tiempo Estimado de Apertura:
                </span>
                <div id="countdown-timer" style="font-size: 1.5rem; font-weight: 700; color: #78350f; font-family: monospace; letter-spacing: 2px;">
                    Calculando...
                </div>
            </div>

            <script>
                (function() {
                    var targetDate = new Date("<?= date('c', strtotime($fechaFinMantenimiento)) ?>").getTime();
                    var timerElem = document.getElementById("countdown-timer");

                    function updateTimer() {
                        var now = new Date().getTime();
                        var diff = targetDate - now;

                        if (diff <= 0) {
                            timerElem.innerHTML = "¡Reapertura en proceso...!";
                            setTimeout(function() { window.location.reload(); }, 3000);
                            return;
                        }

                        var hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        var seconds = Math.floor((diff % (1000 * 60)) / 1000);

                        timerElem.innerHTML = 
                            (hours < 10 ? "0" + hours : hours) + "h : " +
                            (minutes < 10 ? "0" + minutes : minutes) + "m : " +
                            (seconds < 10 ? "0" + seconds : seconds) + "s";
                    }

                    updateTimer();
                    setInterval(updateTimer, 1000);
                })();
            </script>
        <?php endif; ?>

        <div class="mantenimiento-actions">
            <a href="login" onclick="if(window.location.search){ window.location.href = './login'; return false; }" class="btn-admin-pill">
                <i class="ph ph-lock-key" style="font-size: 1.25rem;"></i> Acceso Administrativo
            </a>
        </div>
    </div>
</body>
</html>