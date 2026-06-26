<div class="chat-app-wrapper">

    <main class="chat-main-area">
        
        <header class="chat-header">
            <div class="chat-header-info">
                <h2>PST: Sistema de Control de Pagos</h2>
                <p>Tutor: Ing. Josué García | Trayecto IV</p>
            </div>
            <div class="chat-status">
                Fase 2 Activa
            </div>
        </header>

        <div class="chat-messages-box">
            
            <div class="chat-msg system">
                <div class="chat-msg-bubble">
                    El Ing. Josué García ha creado la sala del proyecto.
                </div>
            </div>

            <div class="chat-msg tutor">
                <span class="chat-msg-meta">Ing. Josué García • Ayer 14:30</span>
                <div class="chat-msg-bubble">
                    Buenas tardes equipo. Ya revisé el documento rector preliminar. Por favor, ajusten la matriz FODA y envíen el nuevo esquema de la base de datos por aquí para revisarlo.
                </div>
            </div>

            <div class="chat-msg me">
                <span class="chat-msg-meta">Tú • Hoy 09:15</span>
                <div class="chat-msg-bubble">
                    Entendido, profe. Ya hicimos las correcciones metodológicas. Aquí adjuntamos el modelo relacional en PDF para su aprobación.
                </div>
            </div>

            <div class="chat-msg system">
                <div class="chat-msg-bubble">
                    <i class="ph ph-link-simple"></i> Documento Adjunto: Modelo_Relacional_V2.pdf
                </div>
            </div>

        </div>

        <form action="#" method="POST" class="chat-input-area" onsubmit="event.preventDefault();">
            <label class="chat-btn-attach" title="Adjuntar Documento">
                <i class="ph ph-link-simple"></i> <input type="file" hidden>
            </label>
            <input type="text" class="chat-input-box" placeholder="Escribe un mensaje al grupo..." required>
            <button type="submit" class="chat-btn-send">ENVIAR</button>
        </form>

    </main>
    <?php include __DIR__ . '/sala_chat.php'; ?>


</div>