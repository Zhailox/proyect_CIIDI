<div class="ve-wrapper">
    
    <section class="ve-hero">
        <div class="ve-hero-content">
            <h1>Innovación Tecnológica para el Sector Productivo</h1>
            <p>
                Conectamos el talento de la UPTTMBI con las necesidades reales de las organizaciones. 
                Si tienes un cuello de botella informático, nosotros tenemos la solución en código.
            </p>
        </div>
        
        <div class="ve-wave-bottom">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118.08,130.83,123.8,198.81,115.15,240.76,109.84,281.42,88.4,321.39,56.44Z" class="shape-fill"></path>
            </svg>
        </div>
    </section>

    <section class="ve-marquee-section">
        <h3 class="ve-marquee-title">Organizaciones que han confiado en nuestro talento</h3>
        <div class="ve-marquee-container">
            <div class="ve-marquee-track">
                <span class="ve-marquee-item">Venvidrio</span>
                <span class="ve-marquee-item">Lácteos Los Andes</span>
                <span class="ve-marquee-item">Cemento Andino</span>
                <span class="ve-marquee-item">Alcaldía de Valera</span>
                <span class="ve-marquee-item">Café Flor de Patria</span>
                <span class="ve-marquee-item">Hospital Central</span>
                <span class="ve-marquee-item">Venvidrio</span>
                <span class="ve-marquee-item">Lácteos Los Andes</span>
                <span class="ve-marquee-item">Cemento Andino</span>
                <span class="ve-marquee-item">Alcaldía de Valera</span>
                <span class="ve-marquee-item">Café Flor de Patria</span>
                <span class="ve-marquee-item">Hospital Central</span>
            </div>
        </div>
    </section>

    <section class="ve-section alt-bg">
        <h2 class="ve-steps-title">¿Qué somos capaces de desarrollar?</h2>
        <p class="ve-steps-subtitle">
            Nuestros estudiantes de Trayecto (TSU e Ingeniería) están capacitados para construir soluciones eficientes, seguras y nativas. No hacemos simples páginas web; diseñamos sistemas robustos.
        </p>
        <div class="ve-steps-grid">
            <div class="ve-step-card">
                <div class="ve-step-number">1</div>
                <h3>Software a Medida</h3>
                <p>Aplicaciones de escritorio y web ultraligeras para gestión de inventarios, facturación, y control de personal, optimizadas para el hardware de tu empresa.</p>
            </div>
            <div class="ve-step-card">
                <div class="ve-step-number">2</div>
                <h3>Infraestructura y Redes</h3>
                <p>Auditoría de redes locales, configuración de servidores Linux/Windows, cableado estructurado y despliegue de intranets seguras.</p>
            </div>
            <div class="ve-step-card">
                <div class="ve-step-number">3</div>
                <h3>Bases de Datos y Seguridad</h3>
                <p>Migración de datos, estructuración de bases de datos relacionales (SQL) y análisis de vulnerabilidades en sistemas existentes.</p>
            </div>
        </div>
    </section>

    <section class="ve-section">
        <h2 class="ve-steps-title">¿Tienes un problema? Te damos la solución.</h2>
        <p class="ve-steps-subtitle">
            No importa si eres una pequeña bodega en Valera buscando organizar tu stock, o una gran planta industrial con problemas de red. Postula tu "dolor" siguiendo estos pasos para que un equipo lo asuma como su Proyecto Socio Tecnológico (PST).
        </p>
        
        <div class="ve-steps-grid">
            <div class="ve-step-card">
                <div class="ve-step-number">1</div>
                <h3>Identidad Básica</h3>
                <p>Rellena el formulario inferior con los datos de contacto de tu organización. Necesitamos saber quién eres y dónde estás ubicado para agendar una posible visita técnica.</p>
            </div>
            <div class="ve-step-card">
                <div class="ve-step-number">2</div>
                <h3>Explica tu "Dolencia" (Sé Específico)</h3>
                <p>¿Qué proceso te quita tiempo? ¿Qué área necesita sistematizarse? Entre más detalles nos des, más fácil será asignar tu requerimiento a estudiantes de TSU (soporte/redes) o Ingeniería (arquitectura de software).</p>
            </div>
            <div class="ve-step-card">
                <div class="ve-step-number">3</div>
                <h3>Ejecución Académica</h3>
                <p>Si tu propuesta es aprobada por nuestro comité, un equipo de estudiantes trabajará de la mano contigo durante el año académico para desarrollar e implement la solución final a coste cero.</p>
            </div>
        </div>
    </section>

    <section id="registro-problematica" class="ve-section alt-bg">
        <div class="ve-form-container">
            <h2>Postula tu Requerimiento Tecnológico</h2>
            <p>Ingresa los detalles a continuación. Nuestro banco de proyectos clasificará tu solicitud para que los estudiantes de Informática puedan adoptarla.</p>
            
            <?php if(isset($_SESSION['mensaje_exito'])): ?>
                <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    <?php echo $_SESSION['mensaje_exito']; unset($_SESSION['mensaje_exito']); ?>
                </div>
            <?php endif; ?>
            <?php if(isset($_SESSION['mensaje_error'])): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    <?php echo $_SESSION['mensaje_error']; unset($_SESSION['mensaje_error']); ?>
                </div>
            <?php endif; ?>

            <form action="?ruta=guardar-propuesta" method="POST" class="ve-form">
                
                <h4 style="color: var(--ve-secundario); margin-bottom: 1rem; border-bottom: 1px solid rgba(0,0,0,0.1); padding-bottom: 0.5rem;">Paso 1: Datos de la Organización</h4>
                
                <div class="ve-form-row">
                    <div class="ve-form-group">
                        <label class="ve-form-label" for="nombre_empresa">Nombre de la Empresa / Comercio / Institución</label>
                        <input type="text" id="nombre_empresa" name="nombre_empresa" class="ve-input" placeholder="Ej. Distribuidora Los Andes C.A." required>
                    </div>
                    <div class="ve-form-group">
                        <label class="ve-form-label" for="rif_empresa">RIF (Opcional si es comunidad)</label>
                        <input type="text" id="rif_empresa" name="rif_empresa" class="ve-input" placeholder="Ej. J-12345678-9">
                    </div>
                </div>

                <div class="ve-form-row">
                    <div class="ve-form-group">
                        <label class="ve-form-label" for="persona_contacto">Persona de Contacto</label>
                        <input type="text" id="persona_contacto" name="persona_contacto" class="ve-input" placeholder="Nombre y Apellido del responsable" required>
                    </div>
                    <div class="ve-form-group">
                        <label class="ve-form-label" for="telefono_contacto">Teléfono Móvil o Fijo</label>
                        <input type="text" id="telefono_contacto" name="telefono_contacto" class="ve-input" placeholder="Ej. 0414-0000000" required>
                    </div>
                </div>

                <div class="ve-form-group">
                    <label class="ve-form-label" for="correo_contacto">Correo Electrónico (Para recibir estatus del PST)</label>
                    <input type="email" id="correo_contacto" name="correo_contacto" class="ve-input" placeholder="contacto@tuempresa.com" required>
                </div>

                <h4 style="color: var(--ve-secundario); margin-top: 2rem; margin-bottom: 1rem; border-bottom: 1px solid rgba(0,0,0,0.1); padding-bottom: 0.5rem;">Paso 2: Diagnóstico del Problema</h4>

                <div class="ve-form-group">
                    <label class="ve-form-label" for="area_afectada">¿A qué área afecta principalmente este problema?</label>
                    <select id="area_afectada" name="area_afectada" class="ve-input" required>
                        <option value="" disabled selected>Selecciona un área...</option>
                        <option value="inventario">Control de Inventario / Almacén</option>
                        <option value="facturacion">Ventas / Facturación / Administración</option>
                        <option value="redes">Infraestructura de Redes / Internet / Servidores</option>
                        <option value="datos">Gestión de Datos / Registros de Personal</option>
                        <option value="otro">Otro (Especifique en la descripción)</option>
                    </select>
                </div>

                <div class="ve-form-group">
                    <label class="ve-form-label" for="descripcion_problema">Explica el problema y la solución que buscas de forma detallada:</label>
                    <textarea id="descripcion_problema" name="descripcion_problema" class="ve-textarea" placeholder="Ej. Actualmente llevamos el inventario en un cuaderno y perdemos mucho tiempo cuadrando caja. Necesitamos un sistema de escritorio rápido que nos permita registrar entradas y salidas con reportes en PDF..." required></textarea>
                </div>

                <button type="submit" class="btn ve-btn-submit">Ingresar Solicitud al Banco de Proyectos</button>
            </form>
        </div>
    </section>

</div>