<input type="checkbox" id="toggle-new-post">

<div class="fc-wrapper">

    <header class="fc-hero">
        <h1>Foro Comunitario UPTTMBI</h1>
        <p>Espacio público de colaboración. Discute metodologías, resuelve errores de código y comparte avances de tus Proyectos Socio Tecnológicos (PST).</p>
    </header>

    <div class="fc-layout">

        <main class="fc-thread-list">
            
            <a href="leer-hilo" class="fc-thread">
                <div class="fc-votes">
                    <span class="fc-vote-arrow">▲</span>
                    <span class="fc-vote-count">42</span>
                    <span class="fc-vote-arrow">▼</span>
                </div>
                <div class="fc-thread-avatar-box">
                    <div class="fc-avatar" style="background-color: var(--color-terciario);">A</div>
                </div>
                <div class="fc-thread-content">
                    <h3 class="fc-thread-title">¿Cómo están estructurando la matriz FODA en el Documento Rector?</h3>
                    <div class="fc-thread-meta">
                        Iniciado por <strong>AnaP_Trayecto4</strong> • Hace 3 horas
                    </div>
                </div>
                <div class="fc-thread-stats">
                    <span class="fc-tag">IAP / Metodología</span>
                    <span class="fc-comments">💬 15 Respuestas</span>
                </div>
            </a>

            <a href="leer-hilo" class="fc-thread">
                <div class="fc-votes">
                    <span class="fc-vote-arrow">▲</span>
                    <span class="fc-vote-count">28</span>
                    <span class="fc-vote-arrow">▼</span>
                </div>
                <div class="fc-thread-avatar-box">
                    <div class="fc-avatar" style="background-color: var(--color-secundario);">D</div>
                </div>
                <div class="fc-thread-content">
                    <h3 class="fc-thread-title">Error al generar PDF con la librería FPDF usando puro PHP</h3>
                    <div class="fc-thread-meta">
                        Iniciado por <strong>DevTrujillo</strong> • Hace 5 horas
                    </div>
                </div>
                <div class="fc-thread-stats">
                    <span class="fc-tag">PHP / Bugs</span>
                    <span class="fc-comments">💬 8 Respuestas</span>
                </div>
            </a>

            <a href="leer-hilo" class="fc-thread">
                <div class="fc-votes">
                    <span class="fc-vote-arrow">▲</span>
                    <span class="fc-vote-count">12</span>
                    <span class="fc-vote-arrow">▼</span>
                </div>
                <div class="fc-thread-avatar-box">
                    <div class="fc-avatar" style="background-color: #10b981;">J</div>
                </div>
                <div class="fc-thread-content">
                    <h3 class="fc-thread-title">Duda sobre migración de bases de datos de MySQL a PostgreSQL</h3>
                    <div class="fc-thread-meta">
                        Iniciado por <strong>JoseM_BD</strong> • Ayer
                    </div>
                </div>
                <div class="fc-thread-stats">
                    <span class="fc-tag">Base de Datos</span>
                    <span class="fc-comments">💬 3 Respuestas</span>
                </div>
            </a>

        </main>

        <aside class="fc-sidebar">
            
            <div class="fc-actions">
                <label for="toggle-new-post" class="fc-btn-primary">NUEVO HILO DE DEBATE</label>
                <a href="mis-chats" class="fc-btn-secondary">MIS TUTORÍAS (PRIVADO)</a>
            </div>

            <div class="fc-nav-group">
                <h3>Ordenar Por</h3>
                <div class="fc-nav-list">
                    <button class="fc-nav-item active">Más Relevantes</button>
                    <button class="fc-nav-item">Últimos Creados</button>
                    <button class="fc-nav-item">Sin Responder</button>
                </div>
            </div>

            <div class="fc-nav-group">
                <h3>Categorías</h3>
                <div class="fc-nav-list">
                    <button class="fc-nav-item">Código y Bugs</button>
                    <button class="fc-nav-item">Metodología IAP</button>
                    <button class="fc-nav-item">Hardware y Redes</button>
                    <button class="fc-nav-item">Base de Datos</button>
                </div>
            </div>

        </aside>

    </div>
</div>

<div class="fc-modal-overlay">
    <div class="fc-modal-box">
        <div class="fc-modal-header">
            <h3>Crear Nueva Discusión</h3>
            <label for="toggle-new-post" class="fc-close-btn" title="Cerrar">×</label>
        </div>
        
        <form action="#" method="POST" onsubmit="event.preventDefault();">
            <div class="fc-modal-body">
                <div class="fc-input-group">
                    <label>Título de la pregunta o debate</label>
                    <input type="text" class="fc-input" placeholder="Ej. Duda sobre esquema relacional en PostgreSQL..." required>
                </div>
                
                <div class="fc-input-group">
                    <label>Categoría / Etiqueta</label>
                    <input type="text" class="fc-input" placeholder="Ej. Base de Datos, Metodología, PHP...">
                </div>
                
                <div class="fc-input-group">
                    <label>Detalle de la Publicación</label>
                    <textarea class="fc-textarea" placeholder="Escribe el contexto detallado de tu duda o aporte aquí..." required></textarea>
                </div>
            </div>
            
            <div class="fc-modal-footer">
                <label for="toggle-new-post" class="btn" style="background-color: transparent; border: 2px solid var(--gris); color: var(--text-muted); border-radius: 0;">Cancelar</label>
                <button type="submit" class="fc-btn-primary" style="padding: 0.6rem 1.5rem;">Publicar Hilo</button>
            </div>
        </form>
    </div>
</div>