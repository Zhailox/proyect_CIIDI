<div class="art-catalog-wrapper">

    <aside class="art-filters-sidebar">
        <h3>Búsqueda Avanzada</h3>
        
        <div class="art-filter-group">
            <label for="search_art">Palabra Clave o Autor</label>
            <input type="text" id="search_art" class="art-search-input" placeholder="Ej: Microkernel, Redes...">
        </div>

        <div class="art-filter-group">
            <label>Área de Investigación</label>
            <label class="art-checkbox-label">
                <input type="checkbox" checked> Ingeniería de Software
            </label>
            <label class="art-checkbox-label">
                <input type="checkbox"> Infraestructura y Redes
            </label>
            <label class="art-checkbox-label">
                <input type="checkbox"> Inteligencia Artificial
            </label>
            <label class="art-checkbox-label">
                <input type="checkbox"> Agroinformática
            </label>
        </div>

        <div class="art-filter-group">
            <label for="year_filter">Año de Publicación</label>
            <select id="year_filter" class="art-select-input">
                <option value="todos">Todos los años</option>
                <option value="2026">2026</option>
                <option value="2025">2025</option>
                <option value="2024">2024</option>
            </select>
        </div>
        
        <button class="btn" style="width: 100%; margin-top: 1rem;">Aplicar Filtros</button>
    </aside>

    <main class="art-catalog-content">

        <article class="art-featured-post">
            <div class="art-featured-img" style="background-image: url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&q=80');"></div>
            <div class="art-featured-body">
                
                <div class="art-post-meta">
                    <div class="art-tags">
                        <span class="art-tag">Arquitectura</span>
                        <span class="art-tag">PHP Nativo</span>
                    </div>
                    <span class="art-metric">↓ 450 Descargas</span>
                </div>

                <a href="leer-articulo" class="art-post-title" style="font-size: 1.8rem;">Implementación de Arquitecturas Microkernel en Sistemas Universitarios</a>
                
                <p class="art-abstract">
                    Un estudio sobre el rendimiento de sistemas modulares (bare-metal) frente a frameworks pesados. Se demuestra cómo una arquitectura Microkernel nativa en PHP reduce el consumo de RAM y agiliza el enrutamiento dinámico en los servidores locales de la UPTTMBI.
                </p>
                <p class="art-authors" style="margin-bottom: 0;">Por: Investigadores del Trayecto IV</p>
            </div>
        </article>

        <div class="art-masonry-grid">
            
            <article class="art-post-card">
                <div class="art-post-img" style="background-image: url('https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?auto=format&fit=crop&q=80');"></div>
                <div class="art-post-body">
                    <div class="art-post-meta">
                        <span class="art-tag">Agroinformática</span>
                        <span class="art-metric">★ 12 Citas</span>
                    </div>
                    <a href="leer-articulo" class="art-post-title">Factibilidad de Riego por Goteo Automatizado con MicroPython</a>
                    <p class="art-abstract">
                        Integración de hardware libre y sensores de humedad para optimizar parcelas experimentales en el estado Trujillo.
                    </p>
                    <p class="art-authors" style="margin-top: auto; padding-top: 1rem;">Por: J. Blanco, L. Pérez</p>
                </div>
            </article>

            <article class="art-post-card">
                <div class="art-post-img" style="background-image: url('https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&q=80');"></div>
                <div class="art-post-body">
                    <div class="art-post-meta">
                        <span class="art-tag">Educación</span>
                        <span class="art-metric">↓ 320 Descargas</span>
                    </div>
                    <a href="leer-articulo" class="art-post-title">Nuevas Metodologías de Aprendizaje Virtual para PNF</a>
                    <p class="art-abstract">
                        Evaluación de plataformas LMS de bajo consumo de recursos para garantizar el acceso asíncrono a estudiantes foráneos.
                    </p>
                    <p class="art-authors" style="margin-top: auto; padding-top: 1rem;">Por: A. Gómez, M. Ruiz</p>
                </div>
            </article>

            <article class="art-post-card">
                <div class="art-post-img" style="background-image: url('https://images.unsplash.com/photo-1555949963-aa79dcee981c?auto=format&fit=crop&q=80');"></div>
                <div class="art-post-body">
                    <div class="art-post-meta">
                        <span class="art-tag">IA & Datos</span>
                        <span class="art-metric">↓ 89 Descargas</span>
                    </div>
                    <a href="leer-articulo" class="art-post-title">Sistemas Predictivos de Producción en Lácteos Los Andes</a>
                    <p class="art-abstract">
                        Uso de redes neuronales convolucionales para el análisis de big data e histórico de producción regional.
                    </p>
                    <p class="art-authors" style="margin-top: auto; padding-top: 1rem;">Por: C. Méndez</p>
                </div>
            </article>

            <article class="art-post-card">
                <div class="art-post-img" style="background-image: url('https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&q=80');"></div>
                <div class="art-post-body">
                    <div class="art-post-meta">
                        <span class="art-tag">Ciberseguridad</span>
                        <span class="art-metric">★ 34 Citas</span>
                    </div>
                    <a href="leer-articulo" class="art-post-title">Análisis de Vulnerabilidades en Redes de Tráfico Público</a>
                    <p class="art-abstract">
                        Auditoría exhaustiva utilizando Nmap y mitigación de riesgos de intrusión en intranets de dependencias estatales.
                    </p>
                    <p class="art-authors" style="margin-top: auto; padding-top: 1rem;">Por: Prof. R. Diaz</p>
                </div>
            </article>

        </div>

        <div class="art-load-more-container">
            <button class="art-btn-load" onclick="this.innerHTML='Cargando publicaciones...'">
                Cargar más publicaciones ↓
            </button>
        </div>

    </main>
</div>