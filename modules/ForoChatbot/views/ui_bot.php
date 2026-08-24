<div class="drawer-panel" id="panel-bot">
    <div class="chavo-header" style="justify-content: center;">
        <div class="status-pulse" style="margin-right: 0.5rem;"></div>
        Copiloto IA (Chavo)
    </div>
    
    <div class="ia-actions">
        <button class="btn-ia">Resumir Sesión</button>
        <button class="btn-ia">Extraer Tareas</button>
    </div>

    <div class="chat-messages">
        <div class="mensaje chavo">
            <span class="msg-meta">Consola IA - Chavo</span>
            [INFO] Conectado...<br><br>
            ¡Wazaaaa! Listo para ayudarte. Pídeme código o estructura metodológica.
        </div>
    </div>

    <form class="input-area" action="#" method="POST" enctype="multipart/form-data" onsubmit="event.preventDefault();">
        
        <label class="btn-attach" style="background-color: rgba(255,255,255,0.1); color: #ffffff; border: none;" title="Subir archivo para análisis de IA">
            <img class="svg-icon" src="../modules/ForoChatbot/assets/link.svg">
            <input type="file" name="archivos_ia[]" multiple hidden>
        </label>

        <input type="text" class="input-box" placeholder="Pregúntale a Chavo..." required>
        <button type="submit" class="btn-send-arrow">➤</button>
    </form>
</div>

<div class="drawer-panel" id="panel-tools">
    <div class="chavo-header" style="justify-content: center;">
        🛡️ Panel del Profesor
    </div>
    
    <div class="moderator-tools" style="flex-grow: 1;">
        <p style="font-size: 0.85rem; color: var(--gris); margin-bottom: 1.5rem; text-align: center;">
            Herramientas exclusivas para el control de la sala.
        </p>
        <div class="mod-btn-group" style="display: flex; flex-direction: column; gap: 1rem;">
            <button class="btn-mod" style="padding: 0.8rem;">✅ Aprobar Fase Actual</button>
            <button class="btn-mod" style="padding: 0.8rem;">📄 Exportar Chat a PDF</button>
            <button class="btn-mod" style="padding: 0.8rem;">⚙️ Fechas de Entrega</button>
            <button class="btn-mod danger" style="padding: 0.8rem; margin-top: 2rem;">🔒 Cerrar Proyecto</button>
        </div>
    </div>
</div>