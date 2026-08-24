    <aside class="chat-sidebar">
        
        <input type="radio" name="chat-tabs" id="tab-chats" class="chat-tab-radio" checked>
        <input type="radio" name="chat-tabs" id="tab-chavo" class="chat-tab-radio">
        <input type="radio" name="chat-tabs" id="tab-tools" class="chat-tab-radio">

        <div class="chat-tabs-header">
            <label for="tab-chats" class="chat-tab-label">Mis Chats</label>
            <label for="tab-chavo" class="chat-tab-label">IA Chavo</label>
            <label for="tab-tools" class="chat-tab-label">Tutor</label>
        </div>

        <div class="chat-tab-content" id="content-chats">
            <div class="tab-search-bar">
                <input type="text" class="tab-search-input" placeholder="Buscar proyecto o tutor...">
            </div>
            
            <a href="#" class="chat-list-item active">
                <div class="cli-avatar">SP</div>
                <div class="cli-info">
                    <span class="cli-title">Sistema de Pagos IUNE</span>
                    <span class="cli-preview">Tú: Aquí adjuntamos el mo...</span>
                </div>
            </a>

            <a href="#" class="chat-list-item">
                <div class="cli-avatar" style="background-color: var(--color-secundario);">T4</div>
                <div class="cli-info">
                    <span class="cli-title">Tutoría General - T4</span>
                    <span class="cli-preview">Ing. Josué: Recuerden que...</span>
                </div>
            </a>

            <a href="#" class="chat-list-item">
                <div class="cli-avatar" style="background-color: var(--gris);">IA</div>
                <div class="cli-info">
                    <span class="cli-title">Algoritmo ACO (Simulación)</span>
                    <span class="cli-preview">Prof. Perez: Cerrado.</span>
                </div>
            </a>
        </div>

        <div class="chat-tab-content" id="content-chavo">
            
            <div class="chavo-quick-actions">
                <button class="chavo-macro-btn" onclick="agregarMensajeChavo('sistema', 'Analizando transacciones y acuerdos del chat principal... En breve se generará el resumen.')">
                    Resumir Sala
                </button>
                <button class="chavo-macro-btn" onclick="agregarMensajeChavo('sistema', 'Extrayendo compromisos y entregables del Trayecto IV...')">
                    Extraer Tareas
                </button>
            </div>

            <div class="chavo-conversation" id="chavo-chat-box">
                <div class="chavo-msg system">
                    <div class="chavo-bubble">
                        [CONEXIÓN] Consola IA - Chavo en línea y leyendo el contexto de la sala.
                    </div>
                </div>
                
                <div class="chavo-msg bot">
                    <span class="chavo-meta">Chavo (IA Local)</span>
                    <div class="chavo-bubble">
                        ¡Wazaaaa! Estoy sincronizado con tu chat de proyecto. ¿Te ayudo a estructurar los cambios de la matriz FODA o necesitas código para la persistencia de datos?
                    </div>
                </div>

                <div class="chavo-msg user">
                    <span class="chavo-meta">Tú (Investigador)</span>
                    <div class="chavo-bubble">
                        Chavo, recuérdame cuáles fueron las correcciones exactas que pidió el Ingeniero Josué.
                    </div>
                </div>

                <div class="chavo-msg bot">
                    <span class="chavo-meta">Chavo (IA Local)</span>
                    <div class="chavo-bubble">
                        Según el historial reciente de la sala, el Ing. Josué García solicitó dos cosas:<br><br>
                        1. <strong>Ajustar la matriz FODA</strong> en el Documento Rector antes del árbol de problemas.<br>
                        2. <strong>Enviar el esquema relacional</strong> de la base de datos en formato PDF.
                    </div>
                </div>
            </div>

            <form action="#" method="POST" class="chavo-input-form" onsubmit="procesarPromptChavo(event)">
                <input type="text" id="chavo-prompt" class="chavo-input-field" placeholder="Pregúntale a Chavo..." required autocomplete="off">
                <button type="submit" class="chavo-btn-submit">➤</button>
            </form>

        </div>

        <div class="chat-tab-content" id="content-tools">
            <div class="tools-container">
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">
                    Herramientas de control exclusivas para el docente asignado a este proyecto.
                </p>

                <button class="tool-btn success">Aprobar Fase Actual</button>
                <button class="tool-btn">Exportar Chat a PDF</button>
                <button class="tool-btn">Configurar Fechas de Entrega</button>
                <button class="tool-btn danger">Cerrar Proyecto</button>
            </div>
        </div>

    </aside>