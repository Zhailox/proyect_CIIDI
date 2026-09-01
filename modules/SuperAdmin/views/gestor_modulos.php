
<div class="welcome-banner admin-banner">
    <h1>Gestor de Módulos (Hot-Swapping)</h1>
    <p>Lectura dinámica desde el Kernel. El sistema evalúa las dependencias reales de cada paquete interoperable.</p>
</div>

<div class="modules-grid">
    <?php foreach($modulosDelSistema as $mod): ?>
        <div class="module-card-v2 <?= $mod['es_core'] ? 'is-core' : ($mod['estado'] === 'offline' ? 'offline-demo' : '') ?>">
            <div class="module-info">
                <h3><i class="<?= $mod['icono'] ?>"></i> <?= $mod['nombre'] ?></h3>
                <p><?= $mod['descripcion'] ?></p>
            </div>
            
            <div class="module-toggle-group mt-1">
                
                <?php if($mod['es_core']): ?>
                    <span class="module-status locked">SISTEMA BASE</span>
                    <label class="toggle-switch cursor-not-allowed" title="Este módulo no puede desactivarse">
                        <input type="checkbox" checked disabled>
                        <span class="toggle-slider locked cursor-not-allowed"></span>
                    </label>
                
                <?php else: ?>
                    <span class="module-status"><?= strtoupper($mod['estado']) ?></span>
                    
                    <form action="alternar-modulo" method="POST" style="margin:0;">
                        <input type="hidden" name="modulo_id" value="<?= htmlspecialchars($mod['id']) ?>">
                        <input type="hidden" name="nuevo_estado" value="<?= $mod['estado'] === 'online' ? 'offline' : 'online' ?>">
                        
                        <label class="toggle-switch cursor-pointer" title="Alternar Estado">
                            <input type="checkbox" <?= $mod['estado'] === 'online' ? 'checked' : '' ?> onchange="this.form.submit()">
                            <span class="toggle-slider"></span>
                        </label>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php foreach($modulosDelSistema as $mod): ?>
    <?php if($mod['dependencias_count'] > 0 && !$mod['es_core']): ?>
        
        <input type="checkbox" id="modal-warn-<?= $mod['id'] ?>" class="modal-toggle">
        
        <div class="warning-modal-overlay">
            <div class="warning-modal-box">
                <form action="#" method="POST">
                    <h2 class="text-danger mb-1 text-lg">Advertencia de Dependencias</h2>
                    <p class="text-modal-desc mb-1-5">
                        Estás a punto de apagar el módulo <strong><?= $mod['nombre'] ?></strong>. <br><br>
                        <strong>Impacto crítico en el sistema:</strong><br>
                        Este paquete es requerido por otros <strong><?= $mod['dependencias_count'] ?> módulo(s)</strong> (<?= $mod['nombres_dependencias'] ?>). Si procedes con la desactivación, las funciones asociadas devolverán un Error 404 y podrías causar inestabilidad en las rutas públicas.
                    </p>
                    <div class="modal-actions">
                        <label for="modal-warn-<?= $mod['id'] ?>" class="btn btn-secondary cursor-pointer">Cancelar</label>
                        <button type="submit" class="btn btn-danger">Entiendo el riesgo, Proceder</button>
                    </div>
                </form>
            </div>
        </div>

    <?php endif; ?>
<?php endforeach; ?>