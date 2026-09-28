<?php
// Vista de Configuración del Módulo

$configData = $controller->configuracion();
?>
<style>
.vc-header {
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
    padding: 1.5rem 2rem;
    margin-bottom: 2rem;
}
.vc-title {
    color: #121a3e;
    margin: 0 0 0.5rem 0;
    font-size: 1.8rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.vc-subtitle {
    color: #64748b;
    margin: 0;
    font-size: 0.95rem;
}
.vc-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 2rem 2rem 2rem;
}
.vc-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.vc-card-title {
    color: #1e293b;
    font-size: 1.2rem;
    margin: 0 0 1.5rem 0;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.vc-form-group {
    margin-bottom: 1.5rem;
}
.vc-form-group label {
    display: block;
    font-weight: 600;
    color: #475569;
    margin-bottom: 0.5rem;
}
.vc-form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 1rem;
    color: #1e293b;
    transition: border-color 0.2s;
}
.vc-form-control:focus {
    border-color: #7090cb;
    outline: none;
}
.vc-switch-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}
.vc-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}
.vc-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.vc-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1;
    transition: .4s;
    border-radius: 24px;
}
.vc-slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}
input:checked + .vc-slider {
    background-color: #10b981;
}
input:checked + .vc-slider:before {
    transform: translateX(26px);
}
.vc-help-text {
    font-size: 0.85rem;
    color: #64748b;
    margin-top: 5px;
    display: block;
}
.vc-btn-submit {
    background: #505984;
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.2s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.vc-btn-submit:hover {
    background: #3c456a;
}
</style>

<?php if (isset($_SESSION['flash_success']) || isset($_SESSION['flash_error'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['flash_success'])): ?>
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '<?php echo addslashes($_SESSION['flash_success']); ?>',
                    confirmButtonColor: '#10b981'
                });
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['flash_error'])): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?php echo addslashes($_SESSION['flash_error']); ?>',
                    confirmButtonColor: '#e11d48'
                });
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>
        });
    </script>
<?php endif; ?>

<div class="vc-header">
    <h2 class="vc-title"><i class="ph-bold ph-gear"></i> Configuración del Módulo</h2>
    <p class="vc-subtitle">Ajusta los parámetros operativos de Vinculación Empresarial y Bancos de Proyectos.</p>
</div>

<div class="vc-container">
    <form action="?ruta=guardar-configuracion" method="POST">
        
        <div class="vc-card">
            <h3 class="vc-card-title"><i class="ph-bold ph-clock-countdown"></i> Ciclo de Recepción</h3>
            
            <div class="vc-form-group">
                <label>Recepción de Propuestas Empresariales</label>
                <div class="vc-switch-wrap">
                    <label class="vc-switch">
                        <input type="checkbox" name="recepcion_activa" value="1" <?php echo !empty($configData['recepcion_activa']) ? 'checked' : ''; ?>>
                        <span class="vc-slider"></span>
                    </label>
                    <span style="font-weight: bold; color: <?php echo !empty($configData['recepcion_activa']) ? '#10b981' : '#ef4444'; ?>;">
                        <?php echo !empty($configData['recepcion_activa']) ? 'ABIERTA' : 'CERRADA'; ?>
                    </span>
                </div>
                <span class="vc-help-text">Apaga este interruptor cuando culmine el período académico y ya no desees que las empresas envíen nuevas postulaciones de requerimientos tecnológicos.</span>
            </div>
        </div>

        <div class="vc-card">
            <h3 class="vc-card-title"><i class="ph-bold ph-list-numbers"></i> Paginación y Visualización</h3>
            
            <div style="display:flex; gap: 2rem;">
                <div class="vc-form-group" style="flex:1;">
                    <label>Paginación de Gestión Interna</label>
                    <input type="number" class="vc-form-control" name="paginacion_gestion" value="<?php echo htmlspecialchars($configData['paginacion_gestion'] ?? 8); ?>" min="1" max="100" required>
                    <span class="vc-help-text">Número de filas visibles por página en "Gestión de Solicitudes".</span>
                </div>
                <div class="vc-form-group" style="flex:1;">
                    <label>Paginación en la Cartelera</label>
                    <input type="number" class="vc-form-control" name="paginacion_cartelera" value="<?php echo htmlspecialchars($configData['paginacion_cartelera'] ?? 12); ?>" min="1" max="100" required>
                    <span class="vc-help-text">Número de proyectos visibles por página en la Cartelera Pública de Estudiantes.</span>
                </div>
            </div>
        </div>

        <div class="vc-card">
            <h3 class="vc-card-title"><i class="ph-bold ph-megaphone"></i> Textos Públicos</h3>
            
            <div class="vc-form-group">
                <label>Mensaje Destacado (Cintilla Informativa)</label>
                <input type="text" class="vc-form-control" name="mensaje_marquee" value="<?php echo htmlspecialchars($configData['mensaje_marquee'] ?? 'Plataforma Oficial para la Vinculación de Estudiantes con Proyectos Reales de la Industria'); ?>" required>
                <span class="vc-help-text">Texto dinámico que corre de derecha a izquierda en la sección principal del Landing (Conócenos).</span>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: 1rem;">
            <button type="submit" class="vc-btn-submit"><i class="ph-bold ph-floppy-disk"></i> Guardar Cambios</button>
        </div>
    </form>
</div>
