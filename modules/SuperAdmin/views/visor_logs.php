<input type="radio" name="log-filter" id="filter-none" class="log-radio" checked>
<input type="radio" name="log-filter" id="filter-auth" class="log-radio">
<input type="radio" name="log-filter" id="filter-db" class="log-radio">
<input type="radio" name="log-filter" id="filter-err" class="log-radio">

<div class="welcome-banner admin-banner gradient">
    <h1>Centro de Auditoría</h1>
    <p>Supervisión estructurada de eventos del sistema, trazabilidad de base de datos y control de accesos.</p>
</div>

<div class="inbox-filters mb-1">
    <label for="filter-none" class="filter-pill cursor-pointer">
        <svg class="svg-icon-log" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 9h-2V7h-2v5H6v2h2v5h2v-5h2v-2z"/></svg>
        Reposo
    </label>
    <label for="filter-auth" class="filter-pill cursor-pointer">
        <svg class="svg-icon-log" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5-4-8-4z"/></svg>
        Accesos (AUTH)
    </label>
    <label for="filter-db" class="filter-pill cursor-pointer">
        <svg class="svg-icon-log" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 4.02 2 6.5v11C2 19.98 6.48 22 12 22s10-2.02 10-4.5v-11C22 4.02 17.52 2 12 2zm0 18c-4.41 0-8-1.79-8-4v-1.15c2.14 1.34 5.2 2.15 8 2.15s5.86-.81 8-2.15V16c0 2.21-3.59 4-8 4zm0-6c-4.41 0-8-1.79-8-4V8.85c2.14 1.34 5.2 2.15 8 2.15s5.86-.81 8-2.15V10c0 2.21-3.59 4-8 4zm0-6c-4.41 0-8-1.79-8-4s3.59-4 8-4 8 1.79 8 4-3.59 4-8 4z"/></svg>
        Cambios BD (CRUD)
    </label>
    <label for="filter-err" class="filter-pill cursor-pointer text-danger">
        <svg class="svg-icon-log" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
        Errores (ERR)
    </label>
</div>

<div class="log-viewer-card">
    
    <div class="log-viewer-header viewer-card-header">
        <h3>Auditoría Detallada</h3>
        <div class="terminal-actions">
            <button class="btn btn-secondary">Exportar Auditoría .CSV</button>
        </div>
    </div>

    <form action="#" method="GET" class="advanced-filters-bar">
        <div class="filter-group">
            <span class="filter-label">Búsqueda Global:</span>
            <input type="text" class="filter-input w-search" placeholder="ID, Hash o Contexto...">
        </div>
        
        <div class="filter-group">
            <span class="filter-label">Módulo / Origen:</span>
            <select class="filter-select">
                <option value="all">Todo el sistema</option>
                <option value="kernel">Core / Kernel</option>
                <option value="db">PostgreSQL Engine</option>
                <option value="auth">Security & JWT</option>
            </select>
        </div>

        <button type="submit" class="btn btn-filter-submit">Aplicar</button>
    </form>

    <div class="terminal-idle-v3">
        <img src="../modules/SuperAdmin/assets/img/cloud.png" alt="nube">
        <h3 class="idle-title">Buffer de Memoria en Espera</h3>
        <p class="idle-desc">Seleccione una categoría en la barra superior para procesar los registros físicos y cargar la tabla de auditoría transaccional de forma estructurada.</p>
    </div>

    <table class="log-table-v3">
        <thead class="log-group-table auth-logs error-logs db-logs">
            <tr>
                <th style="width: 160px;">Registro (UTC)</th>
                <th>Detalles del Evento y Trazabilidad</th>
                <th style="width: 280px;">Entorno y Metadatos</th>
            </tr>
        </thead>
        
        <tbody class="log-group-table auth-logs">
            <tr>
                <td>
                    <span class="log-date-primary">2026-06-08</span>
                    <span class="log-time-secondary">19:45:12.105</span>
                    <div class="log-badge auth mt-1">AUTH:SUCCESS</div>
                </td>
                <td>
                    <div class="cell-event-type">Generación Exitosa de Credenciales JWT</div>
                    <div class="cell-event-desc">
                        El subsistema de seguridad validó la firma criptográfica de la contraseña. Se inyectó un token portador de corta duración con privilegios de estudiante en el encabezado HTTP.
                    </div>
                    <div class="sql-trace-box">
                        SessionToken: [sha256_hash] eYJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9... <br>
                        Expiration: 3600 seconds. Status: Active.
                    </div>
                </td>
                <td>
                    <ul class="env-data-list">
                        <li><strong>Usuario:</strong> AnaP_Trayecto4</li>
                        <li><strong>Red / IP:</strong> 192.168.1.45</li>
                        <li><strong>Navegador:</strong> Chrome 114.0 (Windows 11)</li>
                        <li><strong>Origen:</strong> /login/verificar</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="log-date-primary">2026-06-08</span>
                    <span class="log-time-secondary">20:12:04.882</span>
                    <div class="log-badge error mt-1">AUTH:FAIL</div>
                </td>
                <td>
                    <div class="cell-event-type text-danger">Fallo de Autenticación / Contraseña Inválida</div>
                    <div class="cell-event-desc">
                        Un intento de acceso falló debido a discrepancia de hash en la verificación de contraseñas. El limitador de tasa sumó un marcador de advertencia a la dirección de origen.
                    </div>
                    <div class="sql-trace-box trace-error">
                        AuthVerifyException: Password hash check failed for entity. <br>
                        RateLimiterCounter: 1/5 attempts.
                    </div>
                </td>
                <td>
                    <ul class="env-data-list">
                        <li><strong>Usuario:</strong> Desconocido (admin_test)</li>
                        <li><strong>Red / IP:</strong> 10.0.0.8</li>
                        <li><strong>Navegador:</strong> Firefox 115.0 (Linux x86_64)</li>
                        <li><strong>Origen:</strong> /login/verificar</li>
                    </ul>
                </td>
            </tr>
        </tbody>

        <tbody class="log-group-table db-logs">
            <tr>
                <td>
                    <span class="log-date-primary">2026-06-08</span>
                    <span class="log-time-secondary">20:15:22.405</span>
                    <div class="log-badge db mt-1">DB:INSERT</div>
                </td>
                <td>
                    <div class="cell-event-type">Creación de Nuevo Registro Documental</div>
                    <div class="cell-event-desc">
                        El sistema procesó la subida de un nuevo archivo asociado a la tabla <strong>pst_documentos</strong>. El motor de almacenamiento asignó una ruta física segura en el servidor.
                    </div>
                    <div class="sql-trace-box">
                        <span class="keyword">INSERT INTO</span> <span class="table">pst_documentos</span> (titulo, autor_id, ruta_pdf) <br>
                        <span class="keyword">VALUES</span> (<span class="string">'Sistema de Pagos IUNE'</span>, 45, <span class="string">'/storage/pst/2026_45.pdf'</span>)
                    </div>
                </td>
                <td>
                    <ul class="env-data-list">
                        <li><strong>Usuario:</strong> DevTrujillo (ID: 45)</li>
                        <li><strong>Red / IP:</strong> 10.0.0.22</li>
                        <li><strong>Navegador:</strong> Chrome 114.0 (Windows 11)</li>
                        <li><strong>Origen:</strong> /upload_pst_endpoint</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="log-date-primary">2026-06-08</span>
                    <span class="log-time-secondary">21:30:10.112</span>
                    <div class="log-badge db mt-1">DB:DELETE</div>
                </td>
                <td>
                    <div class="cell-event-type">Eliminación Suave (Soft-Delete) de Solicitud</div>
                    <div class="cell-event-desc">
                        Se ha marcado como inactiva una solicitud empresarial en la tabla <strong>empresas_solicitudes</strong>. El registro se mantiene por integridad referencial pero no será visible en consultas públicas.
                    </div>
                    <div class="sql-trace-box">
                        <span class="keyword">UPDATE</span> <span class="table">empresas_solicitudes</span> <br>
                        <span class="keyword">SET</span> deleted_at = <span class="keyword">NOW()</span> <br>
                        <span class="keyword">WHERE</span> estatus = <span class="string">'rechazada'</span> <span class="keyword">AND</span> id = 44
                    </div>
                </td>
                <td>
                    <ul class="env-data-list">
                        <li><strong>Usuario:</strong> Ing_JosueG (Admin)</li>
                        <li><strong>Red / IP:</strong> 192.168.1.10</li>
                        <li><strong>Navegador:</strong> Firefox 115.0 (Linux)</li>
                        <li><strong>Origen:</strong> /admin/empresas/rechazar</li>
                    </ul>
                </td>
            </tr>
        </tbody>

        <tbody class="log-group-table error-logs">
            <tr>
                <td>
                    <span class="log-date-primary">2026-06-08</span>
                    <span class="log-time-secondary">20:34:51.990</span>
                    <div class="log-badge error mt-1">FATAL_ERR</div>
                </td>
                <td>
                    <div class="cell-event-type text-danger">Excepción del Motor de Plantillas (FPDF)</div>
                    <div class="cell-event-desc">
                        El controlador intentó inyectar cabeceras PDF, pero el buffer de salida ya había sido impreso por un espacio en blanco o una instrucción previa.
                    </div>
                    <div class="sql-trace-box trace-error">
                        Error Trace: Output already sent by (output started at /core/System/Kernel.php:12) <br>
                        File: /modules/RepositorioPST/controllers/PstCrudController.php <br>
                        Line: 142
                    </div>
                </td>
                <td>
                    <ul class="env-data-list">
                        <li><strong>Usuario:</strong> Sistema / Background</li>
                        <li><strong>Red / IP:</strong> 127.0.0.1 (Localhost)</li>
                        <li><strong>Navegador:</strong> PHP CLI 8.2 (Apache)</li>
                        <li><strong>Origen:</strong> Tarea Programada</li>
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>
</div>