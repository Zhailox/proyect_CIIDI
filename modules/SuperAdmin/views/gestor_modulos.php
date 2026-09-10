
<div class="welcome-banner admin-banner">
    <h1>Gestor de Módulos & Feature Flags por Ruta</h1>
    <p>Control de módulos globales e interrupción granular de rutas o métodos de escritura por función.</p>
</div>

<div class="modules-grid">
    <?php foreach($modulosDelSistema as $mod): ?>
        <div class="module-card-v2 <?= $mod['es_core'] ? 'is-core' : ($mod['estado'] === 'offline' ? 'offline-demo' : '') ?>" style="flex-direction: column; align-items: stretch; gap: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div class="module-info">
                    <h3><i class="<?= $mod['icono'] ?>"></i> <?= $mod['nombre'] ?></h3>
                    <p><?= $mod['descripcion'] ?></p>
                </div>
                
                <div class="module-toggle-group">
                    <?php if($mod['es_core']): ?>
                        <span class="module-status locked">NÚCLEO</span>
                        <label class="toggle-switch cursor-not-allowed" title="El núcleo del sistema no se puede desactivar">
                            <input type="checkbox" checked disabled>
                            <span class="toggle-slider locked cursor-not-allowed"></span>
                        </label>
                    <?php else: ?>
                        <span class="module-status"><?= strtoupper($mod['estado']) ?></span>
                        <form action="alternar-modulo" method="POST" style="margin:0;">
                            <input type="hidden" name="modulo_id" value="<?= htmlspecialchars($mod['id']) ?>">
                            <input type="hidden" name="nuevo_estado" value="<?= $mod['estado'] === 'online' ? 'offline' : 'online' ?>">
                            <label class="toggle-switch cursor-pointer" title="Alternar Estado del Módulo">
                                <input type="checkbox" <?= $mod['estado'] === 'online' ? 'checked' : '' ?> onchange="this.form.submit()">
                                <span class="toggle-slider"></span>
                            </label>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ACORDEÓN DE RUTAS INTERNAS DEL MÓDULO -->
            <?php if (!empty($mod['rutas'])): ?>
                <details class="routes-accordion" style="border-top: 1px solid rgba(0,0,0,0.06); padding-top: 0.75rem;">
                    <summary style="font-size: 0.85rem; font-weight: 600; color: var(--color-secundario, #002244); cursor: pointer; display: flex; align-items: center; justify-content: space-between;">
                        <span><i class="ph ph-tree-structure"></i> Configurar Rutas Internas (<?= count($mod['rutas']) ?>)</span>
                        <i class="ph ph-caret-down"></i>
                    </summary>
                    
                    <div style="margin-top: 0.75rem; display: flex; flex-direction: column; gap: 0.75rem;">
                        <?php foreach ($mod['rutas'] as $r): ?>
                            <div class="route-control-card" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.75rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                    <div>
                                        <span style="font-weight: 600; font-size: 0.85rem; color: #1e293b;"><?= htmlspecialchars($r['titulo']) ?></span>
                                        <code style="display: inline-block; background: #e2e8f0; color: #475569; font-size: 0.75rem; padding: 2px 6px; border-radius: 4px; margin-left: 6px;">?ruta=<?= htmlspecialchars($r['clave']) ?></code>
                                    </div>
                                    
                                    <form action="alternar-estado-ruta" method="POST" style="display: flex; align-items: center; gap: 0.5rem; margin:0;">
                                        <input type="hidden" name="ruta_clave" value="<?= htmlspecialchars($r['clave']) ?>">
                                        
                                        <select name="nuevo_estado" onchange="this.form.submit()" style="font-size: 0.78rem; padding: 4px 8px; border-radius: 6px; border: 1px solid #cbd5e1; font-weight: 600; background: #ffffff;">
                                            <option value="online" <?= $r['estado'] === 'online' ? 'selected' : '' ?>>🟢 Activa (Normal)</option>
                                            <option value="solo_lectura" <?= $r['estado'] === 'solo_lectura' ? 'selected' : '' ?>>🟡 Solo Lectura</option>
                                            <option value="offline" <?= $r['estado'] === 'offline' ? 'selected' : '' ?>>🔴 Deshabilitada</option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </details>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>
</div>

<?php foreach($modulosDelSistema as $mod): ?>
    <?php if($mod['dependencias_count'] > 0 && !$mod['es_core']): ?>
        <input type="checkbox" id="modal-warn-<?= $mod['id'] ?>" class="modal-toggle">
        <div class="warning-modal-overlay">
            <div class="warning-modal-box">
                <form action="alternar-modulo" method="POST">
                    <input type="hidden" name="modulo_id" value="<?= htmlspecialchars($mod['id']) ?>">
                    <input type="hidden" name="nuevo_estado" value="offline">
                    <h2 class="text-danger mb-1 text-lg">Advertencia de Dependencias</h2>
                    <p class="text-modal-desc mb-1-5">
                        Estás a punto de apagar el módulo <strong><?= $mod['nombre'] ?></strong>. <br><br>
                        <strong>Impacto crítico en el sistema:</strong><br>
                        Este paquete es requerido por otros <strong><?= $mod['dependencias_count'] ?> módulo(s)</strong> (<?= $mod['nombres_dependencias'] ?>). Si procedes con la desactivación, las funciones asociadas devolverán un Error y podrían causar inestabilidad.
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