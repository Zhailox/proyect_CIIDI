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
</head>
<body class="core-maint-body">
    <div class="mantenimiento-container">
        <!-- Imagen Libre Sin Contenedor Blanco Confinado -->
        <img src="assets/img/503.webp" alt="503 - Sistema en Mantenimiento" class="mantenimiento-img-free">
        
        <?php if (!empty($mensajeCustom)): ?>
            <p class="mantenimiento-msg"><?= htmlspecialchars($mensajeCustom) ?></p>
        <?php endif; ?>

        <?php if (!empty($fechaFinMantenimiento)): ?>
            <div class="mantenimiento-timer-card">
                <span class="mantenimiento-timer-label">
                    <i class="ph-bold ph-timer"></i> Tiempo Estimado de Apertura:
                </span>
                <div id="countdown-timer" class="mantenimiento-timer-val">
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
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <a href="cerrar-sesion" class="btn-admin-pill btn-admin-pill-logout">
                    <i class="ph-bold ph-sign-out core-pill-icon"></i> Cerrar Sesión Activa
                </a>
            <?php else: ?>
                <a href="login" onclick="if(window.location.search){ window.location.href = './login'; return false; }" class="btn-admin-pill">
                    <i class="ph ph-lock-key core-pill-icon"></i> Acceso Administrativo
                </a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>