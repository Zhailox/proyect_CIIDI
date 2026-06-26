
    <div class="main-content">

        <div class="upload-view-container">
            
            <header class="pst-header" style="margin-bottom: 2rem;">
                <h1>Ingreso de Nuevo Recurso</h1>
                <p>Sube el documento técnico o académico y completa los metadatos correspondientes para su indexación.</p>
            </header>

            <article class="upload-card">
                <form action="#" method="POST">

                    <div class="upload-grid">
                        
                        <div class="upload-form-data">
                            <h3 class="upload-section-title">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/><path d="M6 5h4v1H6V5zm0 2h4v1H6V7zm0 2h4v1H6V9z"/></svg>
                                Metadatos del Proyecto
                            </h3>

                            <div class="upload-input-group">
                                <label>Título de la Investigación</label>
                                <input type="text" class="upload-input" placeholder="Ej: Sistema de Control de Inventarios para SAPNNAET">
                            </div>

                            <div class="upload-input-group" style="margin-bottom: 2.5rem;">
                                <label>Línea de Investigación PNF</label>
                                <input type="text" class="upload-input" placeholder="Ej: Desarrollo Web e Inteligencia Artificial">
                            </div>

                            <h3 class="upload-section-title">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/><path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/></svg>
                                Autores y Tutores
                            </h3>

                            <div class="grid-2-cols">
                                <div class="upload-input-group">
                                    <label>Cédula Autor</label>
                                    <input type="text" class="upload-input" placeholder="Ej: V-30123456">
                                </div>
                                <div class="upload-input-group">
                                    <label>Nombre Autor</label>
                                    <input type="text" class="upload-input" placeholder="Ej: Piña, Juan">
                                </div>
                            </div>

                            <div class="grid-2-cols">
                                <div class="upload-input-group">
                                    <label>Cédula Tutor</label>
                                    <input type="text" class="upload-input" placeholder="Ej: V-15654321">
                                </div>
                                <div class="upload-input-group">
                                    <label>Nombre Tutor</label>
                                    <input type="text" class="upload-input" placeholder="Ej: Gutiérrez, Karina">
                                </div>
                            </div>
                        </div>

                        <div class="upload-form-file">
                            <h3 class="upload-section-title">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/><path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/></svg>
                                Documento
                            </h3>

                            <div class="drag-drop-zone">
                                <div class="drag-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="17 8 12 3 7 8"></polyline>
                                        <line x1="12" y1="3" x2="12" y2="15"></line>
                                    </svg>
                                </div>
                                <h3 class="drag-title">Arrastra y suelta tu archivo aquí</h3>
                                <p class="drag-desc">Formatos soportados: PDF, DOCX.<br>Tamaño máximo permitido: 15 MB.</p>
                                <button type="button" class="btn-browse">Explorar Archivos</button>
                            </div>
                        </div>

                    </div>

                    <div class="upload-actions">
                        <button type="button" class="btn-cancel">Cancelar</button>
                        <button type="submit" class="btn-save">Guardar Recurso</button>
                    </div>

                </form>
            </article>

        </div>

    </div>
