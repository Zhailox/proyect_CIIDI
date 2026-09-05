<?php
// core/Views/403.php
?>
<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 82vh; padding: 0.5rem; text-align: center;">
    <div style="max-width: 1000px; width: 100%; background: #ffffff; border-radius: 16px; padding: 1rem; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); border: 1px solid rgba(0, 0, 0, 0.06);">
        <img src="assets/img/403.jpg" alt="Error 403 - Acceso Denegado" style="width: 100%; max-height: 70vh; height: auto; border-radius: 12px; display: block; object-fit: contain; margin: 0 auto;">
        <div style="margin-top: 1.25rem; display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
            <a href="?ruta=inicio" style="display: inline-flex; align-items: center; gap: 0.5rem; background: var(--color-secundario, #002244); color: #ffffff; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 0.95rem;">
                <i class="ph ph-house" style="font-size: 1.2rem;"></i> Regresar al Inicio
            </a>
            <button type="button" onclick="history.back()" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #f1f5f9; color: #334155; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; border: 1px solid #cbd5e1; cursor: pointer; font-size: 0.95rem;">
                <i class="ph ph-arrow-left" style="font-size: 1.2rem;"></i> Volver Atrás
            </button>
        </div>
    </div>
</div>
