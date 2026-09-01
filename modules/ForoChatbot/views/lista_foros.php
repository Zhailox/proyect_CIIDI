<input type="checkbox" id="toggle-new-post">

<div class="welcome-banner" style="margin-bottom: 2rem; padding: 2rem 2.5rem;">
    <h1>Foro Comunitario UPTTMBI</h1>
    <p>Comparte conocimientos, resuelve dudas de código o discute sobre metodologías con toda la comunidad estudiantil y docente.</p>
</div>

<div class="public-forum-container">
    
    <div class="forum-controls">
        <div class="forum-filters">
            <button class="filter-btn active">🔥 Relevantes</button>
            <button class="filter-btn">🆕 Nuevos</button>
            <button class="filter-btn">💻 Código y Bugs</button>
        </div>
        <label for="toggle-new-post" class="btn" style="padding: 0.5rem 1.2rem; display: inline-block;">+ Nuevo Hilo</label>
    </div>

    <a href="leer-hilo" class="thread-card">
        <div class="thread-votes">
            <span class="vote-arrow up">▲</span>
            <span class="vote-count">42</span>
            <span class="vote-arrow down">▼</span>
        </div>
        <div class="thread-content">
            <div class="thread-meta">
                Posteado por <span class="thread-author">AnaP_Trayecto4</span> • Hace 3 horas
            </div>
            <h3 class="thread-title">¿Cómo están estructurando la matriz FODA en el Documento Rector?</h3>
            <p class="thread-preview">
                Saludos compañeros. Mi tutor me indicó que la matriz FODA debe ir antes del árbol de problemas, pero según la guía metodológica IAP...
            </p>
            <div class="thread-footer">
                <div class="thread-tags"><span class="tag">IAP</span> <span class="tag">Documento Rector</span></div>
                <div class="thread-comments">💬 15 Comentarios</div>
            </div>
        </div>
    </a>

    <a href="leer-hilo" class="thread-card">
        <div class="thread-votes">
            <span class="vote-arrow up">▲</span>
            <span class="vote-count">28</span>
            <span class="vote-arrow down">▼</span>
        </div>
        <div class="thread-content">
            <div class="thread-meta">
                Posteado por <span class="thread-author">DevTrujillo</span> • Hace 5 horas
            </div>
            <h3 class="thread-title">Error al generar PDF con la librería FPDF usando puro PHP</h3>
            <p class="thread-preview">
                Estoy intentando generar los reportes de inventario de mi PST usando FPDF. El código conecta bien a la base de datos PostgreSQL...
            </p>
            <div class="thread-footer">
                <div class="thread-tags"><span class="tag">PHP</span> <span class="tag">Bugs</span></div>
                <div class="thread-comments">💬 8 Comentarios</div>
            </div>
        </div>
    </a>

</div>

<div class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Crear Nuevo Hilo</h3>
            <label for="toggle-new-post" class="btn-close-modal" title="Cerrar">✖</label>
        </div>
        <form action="#" method="POST" onsubmit="event.preventDefault();">
            <div class="modal-body">
                <div class="form-group">
                    <label>Título de la pregunta o debate</label>
                    <input type="text" class="modal-input" placeholder="Ej. Duda sobre esquema relacional..." required>
                </div>
                <div class="form-group">
                    <label>Etiquetas (Categoría)</label>
                    <input type="text" class="modal-input" placeholder="Ej. SQL, IAP, Requerimientos">
                </div>
                <div class="form-group">
                    <label>Detalla tu publicación</label>
                    <textarea class="modal-textarea" placeholder="Escribe el contexto detallado de tu problema aquí..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <label for="toggle-new-post" class="btn" style="background-color: transparent; border: 1px solid var(--text-muted); color: var(--text-muted);">Cancelar</label>
                <button type="submit" class="btn">Publicar Hilo</button>
            </div>
        </form>
    </div>
</div>