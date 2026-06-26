<div class="welcome-banner admin-banner gradient">
    <h1>Centro de Mando - Sudoadmin</h1>
    <p>Supervisión global de la arquitectura. Monitoreo de recursos, usuarios y mantenimiento del servidor.</p>
</div>

<h3 class="admin-section-title">Monitor de Usuarios</h3>
<div class="metric-grid-v2">
    <div class="metric-card-v2 success">
        <div>
            <h4>Usuarios Activos</h4>
            <div class="metric-value">1,142 <span class="metric-sub">Online</span></div>
        </div>
    </div>
    <div class="metric-card-v2 warning">
        <div>
            <h4>Pendientes de Aprobación</h4>
            <div class="metric-value">48 <span class="metric-sub">Empresas</span></div>
        </div>
    </div>
    <div class="metric-card-v2 danger">
        <div>
            <h4>Cuentas Deshabilitadas</h4>
            <div class="metric-value">12 <span class="metric-sub">Bloqueados</span></div>
        </div>
    </div>
    <div class="metric-card-v2">
        <div>
            <h4>Tractores (Docentes)</h4>
            <div class="metric-value">82 <span class="metric-sub">Registrados</span></div>
        </div>
    </div>
</div>

<h3 class="admin-section-title">Salud del Servidor</h3>
<div class="metric-grid-v2">
    <div class="metric-card-v2">
        <div>
            <h4>Uso de CPU</h4>
            <div class="metric-value">24% <span class="metric-sub">Estable</span></div>
        </div>
    </div>
    <div class="metric-card-v2">
        <div>
            <h4>Memoria RAM</h4>
            <div class="metric-value">4.2 <span class="metric-sub">GB / 16 GB</span></div>
        </div>
    </div>
    <div class="metric-card-v2 warning">
        <div>
            <h4>Almacenamiento (PST)</h4>
            <div class="metric-value">78% <span class="metric-sub">850 GB Usados</span></div>
        </div>
    </div>
</div>

<h3 class="admin-section-title">Herramientas de Administración</h3>
<div class="actions-container">
    
    <details class="action-accordion">
        <summary>Exportar Base de Datos (PostgreSQL)</summary>
        <div class="action-content">
            <button class="btn btn-secondary">Volcado Completo (Dump .sql)</button>
            <button class="btn btn-secondary">Exportar solo Esquema (Sin datos)</button>
            <button class="btn btn-secondary">Exportar Tabla Específica</button>
        </div>
    </details>

    <details class="action-accordion">
        <summary>Modo Mantenimiento</summary>
        <div class="action-content">
            <p style="width: 100%; font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0.5rem;">Cierra el acceso a los estudiantes y muestra la pantalla de "Mantenimiento Programado".</p>
            <button class="btn">Activar por 1 Hora</button>
            <button class="btn">Activar Indefinidamente</button>
            <button class="btn">Personalizar Mensaje de Cierre</button>
        </div>
    </details>

    <details class="action-accordion">
        <summary>Limpieza y Caché</summary>
        <div class="action-content">
            <button class="btn btn-secondary">Purgar Caché de Plantillas</button>
            <button class="btn btn-secondary">Limpiar Sesiones Expiradas</button>
            <button class="btn btn-secondary">Borrar Historial del LLM Local</button>
        </div>
    </details>

</div>