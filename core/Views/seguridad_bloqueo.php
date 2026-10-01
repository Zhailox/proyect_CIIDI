<?php
// core/Views/seguridad_bloqueo.php
$standalone = $isStandalone ?? true;
$tituloSeguridad    = $tituloSeguridad ?? 'Acceso Restringido por Seguridad';
$mensajeSeguridad   = $mensajeSeguridad ?? 'Se ha detectado una anomalía en la petición que infringe las políticas de seguridad institucional.';
$tipoAmenaza        = $tipoAmenaza ?? null;
$parametroAmenaza   = $parametroAmenaza ?? null;
$ipCliente          = $ipCliente ?? ($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');
$fechaHora          = date('d/m/Y H:i:s');
?>
<?php if ($standalone): ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloSeguridad) ?> | CIIDI</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        :root {
            --color-primario: #002244;
            --color-secundario: #0284c7;
            --color-peligro: #dc2626;
        }
        body {
            margin: 0;
            padding: 2rem 1rem;
            background-color: #f8fafc;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #1e293b;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
<?php endif; ?>

<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 85vh; padding: 1.5rem 0; text-align: center; width: 100%;">
    
    <!-- IMAGEN ILUSTRATIVA DE SEGURIDAD (CON FORMATO IDÉNTICO A 403 / 404 / 500) -->
    <img src="assets/img/seguridad.jpg" 
         alt="Seguridad Institucional - Acceso Interceptado" 
         style="max-width: 850px; width: 92%; max-height: 52vh; height: auto; display: block; object-fit: contain; margin: 0 auto; filter: drop-shadow(0 12px 28px rgba(18, 26, 62, 0.12)); border-radius: 16px;">

    <!-- TARJETA INFORMATIVA DE SEGURIDAD ESTILIZADA -->
    <div style="max-width: 780px; width: 92%; margin-top: 1.75rem; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.75rem 2rem; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04); text-align: left; box-sizing: border-box;">
        
        <!-- ENCABEZADO DE LA TARJETA -->
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem; margin-bottom: 1.25rem;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 44px; height: 44px; background: rgba(220, 38, 38, 0.1); color: #dc2626; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                    <i class="ph-bold ph-shield-warning"></i>
                </div>
                <div>
                    <h2 style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #0f172a;"><?= htmlspecialchars($tituloSeguridad) ?></h2>
                    <span style="font-size: 0.8rem; color: #64748b; font-weight: 500;">Módulo de Prevención y Seguridad Perimetral - UPTTMBI</span>
                </div>
            </div>
            <div style="display: inline-flex; align-items: center; gap: 6px; background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; padding: 4px 12px; border-radius: 9999px; font-size: 0.78rem; font-weight: 700;">
                <i class="ph-bold ph-lock-key"></i> WAF Activo
            </div>
        </div>

        <!-- CUERPO DE DETALLES DEL EVENTO -->
        <p style="margin: 0 0 1.25rem 0; color: #475569; font-size: 0.95rem; line-height: 1.6;">
            <?= htmlspecialchars($mensajeSeguridad) ?>
        </p>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.85rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 1.25rem;">
            <?php if (!empty($tipoAmenaza)): ?>
                <div>
                    <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; font-weight: 700; margin-bottom: 4px;">Patrón Detectado</div>
                    <span style="display: inline-block; background: #fee2e2; color: #b91c1c; font-weight: 700; font-size: 0.82rem; padding: 2px 8px; border-radius: 6px;">
                        <?= htmlspecialchars($tipoAmenaza) ?>
                    </span>
                </div>
            <?php endif; ?>

            <?php if (!empty($parametroAmenaza)): ?>
                <div>
                    <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; font-weight: 700; margin-bottom: 4px;">Parámetro Afectado</div>
                    <code style="background: #e2e8f0; color: #0f172a; font-weight: 700; font-size: 0.82rem; padding: 2px 8px; border-radius: 6px; font-family: monospace;">
                        <?= htmlspecialchars($parametroAmenaza) ?>
                    </code>
                </div>
            <?php endif; ?>

            <div>
                <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; font-weight: 700; margin-bottom: 4px;">Dirección IP Origen</div>
                <span style="font-size: 0.85rem; color: #1e293b; font-weight: 600; font-family: monospace;">
                    <?= htmlspecialchars($ipCliente) ?>
                </span>
            </div>

            <div>
                <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; font-weight: 700; margin-bottom: 4px;">Fecha y Registro</div>
                <span style="font-size: 0.85rem; color: #1e293b; font-weight: 600;">
                    <?= htmlspecialchars($fechaHora) ?>
                </span>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 8px; font-size: 0.8rem; color: #64748b; margin-top: 0.5rem;">
            <i class="ph-bold ph-info" style="color: var(--color-secundario, #0284c7); font-size: 1.1rem; flex-shrink: 0;"></i>
            <span>Este incidente ha sido registrado en la bitácora criptográfica inmutable del sistema (ISO/IEC 27001 Control A.8.15).</span>
        </div>

    </div>

    <!-- BOTONES FLOTANTES DE ACCIÓN (ESTILO IDÉNTICO A 403 / 404) -->
    <div style="margin-top: 1.75rem; display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
        <a href="inicio" 
           style="display: inline-flex; align-items: center; gap: 0.6rem; background: var(--color-secundario, #002244); color: #ffffff; padding: 0.75rem 1.6rem; border-radius: 50px; font-weight: 700; text-decoration: none; font-size: 0.95rem; box-shadow: 0 8px 20px rgba(0, 34, 68, 0.25); transition: all 0.25s ease;">
            <i class="ph ph-house" style="font-size: 1.25rem;"></i> Regresar al Inicio
        </a>
        <button type="button" 
                onclick="history.back()" 
                style="display: inline-flex; align-items: center; gap: 0.6rem; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(8px); color: #334155; padding: 0.75rem 1.6rem; border-radius: 50px; font-weight: 700; border: 1px solid rgba(80, 89, 132, 0.25); cursor: pointer; font-size: 0.95rem; box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05); transition: all 0.25s ease;">
            <i class="ph ph-arrow-left" style="font-size: 1.25rem;"></i> Volver Atrás
        </button>
    </div>

</div>

<?php if ($standalone): ?>
</body>
</html>
<?php endif; ?>
