<?php
// core/Views/503.php
?>
<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 82vh; padding: 1rem 0; text-align: center; width: 100%;">
    <!-- Imagen Libre Sin Contenedor Confinado -->
    <img src="assets/img/503.jpg" alt="Error 503 - Servicio No Disponible" style="max-width: 900px; width: 95%; max-height: 72vh; height: auto; display: block; object-fit: contain; margin: 0 auto; filter: drop-shadow(0 12px 28px rgba(18, 26, 62, 0.12)); border-radius: 16px;">
    
    <!-- Botones Flotantes de Acción -->
    <div style="margin-top: 1.5rem; display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
        <a href="login" style="display: inline-flex; align-items: center; gap: 0.6rem; background: var(--color-secundario, #002244); color: #ffffff; padding: 0.75rem 1.6rem; border-radius: 50px; font-weight: 700; text-decoration: none; font-size: 0.95rem; box-shadow: 0 8px 20px rgba(0, 34, 68, 0.25); transition: all 0.25s ease;">
            <i class="ph ph-lock-key" style="font-size: 1.25rem;"></i> Acceso Administrativo
        </a>
        <a href="inicio" style="display: inline-flex; align-items: center; gap: 0.6rem; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(8px); color: #334155; padding: 0.75rem 1.6rem; border-radius: 50px; font-weight: 700; border: 1px solid rgba(80, 89, 132, 0.25); text-decoration: none; font-size: 0.95rem; box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05); transition: all 0.25s ease;">
            <i class="ph ph-house" style="font-size: 1.25rem;"></i> Ir al Inicio
        </a>
    </div>
</div>
