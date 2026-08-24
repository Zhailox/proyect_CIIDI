
<div class="db-wrapper">

    <div class="db-account-header">
        <div class="db-profile-meta">
            <div class="db-avatar-container">
                <div style="width: 100%; height: 100%; background: var(--color-terciario); color: white; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: 800; border-radius: 50%;">
                    <?= strtoupper(substr($usuarioActual['primer_nombre'] ?? 'U', 0, 1)) ?>
                </div>
            </div>
            <div class="db-user-details">
            <h1>Hola, <?= htmlspecialchars($usuarioActual['primer_nombre']) ?></h1>
            <span class="db-role-badge"><?= htmlspecialchars($usuarioActual['rol']) ?></span>
            <p class="db-institution">PNF en Informática — UPTTMBI Sede La Beatriz</p>
        </div>
        </div>
        
        <div class="db-notification-center">
            <button class="db-noti-btn" title="Notificaciones del Sistema" onclick="this.classList.toggle('active')">
                <i class="ph-bold ph-bell"></i>
                <span class="db-noti-badge">3</span>
            </button>
            <div class="db-noti-dropdown">
                <div class="db-noti-item unread">
                    <p>Tu postulación al PST <strong>Sistema Predictivo Venvidrio</strong> fue recibida por la empresa.</p>
                    <span>Hace 10 min</span>
                </div>
                <div class="db-noti-item unread">
                    <p>El profesor Lando subió un nuevo artículo a la Revista Digital.</p>
                    <span>Hace 2 hrs</span>
                </div>
                <div class="db-noti-item">
                    <p>Nuevo mensaje en el Foro: "Dudas sobre Arquitectura Microkernel".</p>
                    <span>Ayer</span>
                </div>
            </div>
        </div>
    </div>

    <div class="db-bento-grid">
        
        <div class="db-bento-card card-large">
            <div class="db-card-header">
                <h3><i class="ph-bold ph-chart-bar"></i> Tu Actividad en la Plataforma</h3>
                <span class="db-status-tag text-success">Datos Actualizados</span>
            </div>
            <p class="db-card-desc">Resumen de tu interacción con los diferentes módulos académicos y empresariales del sistema.</p>
            
            <div class="db-modules-list">
                <div class="db-module-row">
                    <span class="db-mod-name">Módulo: Banco de Propuestas PST</span>
                    <div class="db-progress-bar"><div class="db-progress-fill" style="width: 100%; background-color: var(--color-terciario);"></div></div>
                    <span class="db-mod-status">2 Postulaciones</span>
                </div>
                <div class="db-module-row">
                    <span class="db-mod-name">Módulo: Revista Digital (Artículos)</span>
                    <div class="db-progress-bar"><div class="db-progress-fill" style="width: 60%; background-color: var(--color-secundario);"></div></div>
                    <span class="db-mod-status">12 Leídos</span>
                </div>
                <div class="db-module-row">
                    <span class="db-mod-name">Módulo: Foro y Debates IA</span>
                    <div class="db-progress-bar"><div class="db-progress-fill" style="width: 30%; background-color: var(--gris);"></div></div>
                    <span class="db-mod-status">4 Aportes</span>
                </div>
                <div class="db-module-row">
                    <span class="db-mod-name">Módulo: I+D Universitario</span>
                    <div class="db-progress-bar"><div class="db-progress-fill" style="width: 85%; background-color: var(--color-principal);"></div></div>
                    <span class="db-mod-status">1 Proyecto Act.</span>
                </div>
            </div>
        </div>

        <div class="db-bento-card card-medium">
            <div class="db-card-header">
                <h3><i class="ph-bold ph-bookmark-simple"></i> Colección Guardada</h3>
                <a href="#" class="db-card-link">Ver todo</a>
            </div>
            <div class="db-file-list">
                <div class="db-file-item">
                    <div class="db-file-icon"><i class="ph-fill ph-file-pdf"></i></div>
                    <div class="db-file-meta">
                        <h4>PST_LacteosLosAndes_Requerimientos.pdf</h4>
                        <span>Vinculación Empresarial</span>
                    </div>
                </div>
                <div class="db-file-item">
                    <div class="db-file-icon"><i class="ph-fill ph-article"></i></div>
                    <div class="db-file-meta">
                        <h4>Artículo: Seguridad en Redes</h4>
                        <span>Revista Digital Vol. 5</span>
                    </div>
                </div>
                <div class="db-file-item">
                    <div class="db-file-icon"><i class="ph-fill ph-file-text"></i></div>
                    <div class="db-file-meta">
                        <h4>Tesis_Automatizacion_Riego.pdf</h4>
                        <span>Investigación I+D</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="db-bento-card card-medium">
            <div class="db-card-header">
                <h3><i class="ph-bold ph-clock-counter-clockwise"></i> Vistos Recientemente</h3>
            </div>
            <div class="db-file-list">
                <div class="db-file-item">
                    <div class="db-file-icon" style="color: var(--color-secundario);"><i class="ph-fill ph-book-open"></i></div>
                    <div class="db-file-meta">
                        <h4>Propuesta PST: Bodega El Carmen</h4>
                        <span>Visto hace 15 min</span>
                    </div>
                </div>
                <div class="db-file-item">
                    <div class="db-file-icon" style="color: var(--color-secundario);"><i class="ph-fill ph-article"></i></div>
                    <div class="db-file-meta">
                        <h4>Artículo: Entornos Virtuales Ligeros</h4>
                        <span>Visto hace 2 hrs</span>
                    </div>
                </div>
                <div class="db-file-item">
                    <div class="db-file-icon" style="color: var(--color-secundario);"><i class="ph-fill ph-users-three"></i></div>
                    <div class="db-file-meta">
                        <h4>Perfil Investigador: Prof. P. Castillo</h4>
                        <span>Visto ayer</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="db-bento-card card-small">
            <span class="db-stat-title">Mis Proyectos (PST)</span>
            <span class="db-stat-number">01</span>
            <span class="db-stat-sub text-success"><i class="ph-bold ph-arrow-up-right"></i> En desarrollo actual</span>
        </div>

        <div class="db-bento-card card-small">
            <span class="db-stat-title">Postulaciones</span>
            <span class="db-stat-number">02</span>
            <span class="db-stat-sub">En banco de empresas</span>
        </div>

        <div class="db-bento-card card-small">
            <span class="db-stat-title">Docs Guardados</span>
            <span class="db-stat-number">07</span>
            <span class="db-stat-sub">En bóveda personal</span>
        </div>

    </div>
</div>