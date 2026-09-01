import os
import json

base_dir = r"c:\laragon\www\proyect_CIIDI"
artifact_dir = r"C:\Users\josex\.gemini\antigravity-ide\brain\117e2b30-c442-482b-bde6-46bd14949e6e\artifacts"

# Paleta de colores:
# Azul profundo: #1a1f33, #0f172a
# Azul brillante: #3b82f6

css_styles = """
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    :root {
        --bg-dark: #0f172a;
        --bg-panel: #1a1f33;
        --primary: #3b82f6;
        --primary-hover: #2563eb;
        --text-main: #f8fafc;
        --text-muted: #94a3b8;
        --border: #334155;
        --success: #10b981;
        --card-bg: #ffffff;
        --text-dark: #1e293b;
        --text-light-muted: #64748b;
        --bg-body: #f1f5f9;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    body {
        background-color: var(--bg-body);
        color: var(--text-dark);
        min-height: 100vh;
        padding: 2rem;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Tarjetas y Contenedores */
    .card {
        background: var(--card-bg);
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
    }
    
    /* Botones */
    .btn-primary {
        background-color: var(--primary);
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: background 0.3s;
        display: inline-block;
        text-align: center;
    }

    .btn-primary:hover {
        background-color: var(--primary-hover);
    }

    /* Encabezados y Banners */
    .header-banner {
        background: linear-gradient(135deg, var(--bg-dark), var(--bg-panel));
        color: white;
        padding: 3rem 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .header-banner h1 {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        color: var(--text-main);
    }
    
    .header-banner p {
        color: var(--text-muted);
        font-size: 1.1rem;
    }

    /* Formularios */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--text-dark);
        font-size: 0.9rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 1rem;
        outline: none;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Grillas */
    .grid {
        display: grid;
        gap: 1.5rem;
    }

    .grid-3 {
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    }

    /* Tablas */
    .table-responsive {
        overflow-x: auto;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .table th, .table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .table th {
        background-color: var(--bg-panel);
        color: var(--text-main);
        font-weight: 600;
    }
    
    .table tr:nth-child(even) {
        background-color: #f8fafc;
    }

    .table tr:hover {
        background-color: #f1f5f9;
    }
    
    .badge {
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        background-color: #e0f2fe;
        color: #0369a1;
    }
</style>
"""

def generate_html(title, content):
    return f"""<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{title}</title>
    {css_styles}
</head>
<body>
    <div class="container">
{content}
    </div>
</body>
</html>"""

views = [
    {
        "path": "modules/Autenticacion/views/login.html",
        "title": "Login - UPTTMBI",
        "content": """
        <div style="max-width: 400px; margin: 4rem auto;">
            <div class="card" style="padding: 2.5rem;">
                <div style="text-align: center; margin-bottom: 2rem;">
                    <h2 style="color: var(--bg-panel); font-size: 1.5rem; font-weight: 700;">Ingreso al Sistema</h2>
                    <p style="color: var(--text-light-muted); margin-top: 0.5rem;">Introduzca sus credenciales</p>
                </div>
                <form action="#" method="POST">
                    <div class="form-group">
                        <label for="cedula">CÉDULA</label>
                        <input type="text" id="cedula" class="form-control" placeholder="Ej: V-12345678" required>
                    </div>
                    <div class="form-group">
                        <label for="password">CONTRASEÑA</label>
                        <input type="password" id="password" class="form-control" placeholder="********" required>
                    </div>
                    <button type="submit" class="btn-primary" style="width: 100%; margin-top: 1rem;">INGRESAR</button>
                    <div style="text-align: center; margin-top: 1.5rem;">
                        <a href="recuperar_cuenta.html" style="color: var(--primary); text-decoration: none; font-size: 0.9rem;">¿Olvidó su contraseña?</a>
                    </div>
                </form>
            </div>
        </div>
        """
    },
    {
        "path": "modules/Autenticacion/views/recuperar_cuenta.html",
        "title": "Recuperar Cuenta - UPTTMBI",
        "content": """
        <div style="max-width: 400px; margin: 4rem auto;">
            <div class="card" style="padding: 2.5rem;">
                <div style="text-align: center; margin-bottom: 2rem;">
                    <h2 style="color: var(--bg-panel); font-size: 1.5rem; font-weight: 700;">Recuperar Cuenta</h2>
                    <p style="color: var(--text-light-muted); margin-top: 0.5rem;">Enviaremos un token a su correo registrado</p>
                </div>
                <form action="#" method="POST">
                    <div class="form-group">
                        <label for="cedula">CÉDULA O CORREO</label>
                        <input type="text" id="cedula" class="form-control" placeholder="Ingrese su cédula o correo" required>
                    </div>
                    <button type="submit" class="btn-primary" style="width: 100%; margin-top: 1rem;">ENVIAR TOKEN</button>
                    <div style="text-align: center; margin-top: 1.5rem;">
                        <a href="login.html" style="color: var(--primary); text-decoration: none; font-size: 0.9rem;">Volver al Login</a>
                    </div>
                </form>
            </div>
        </div>
        """
    },
    {
        "path": "modules/Autenticacion/views/mi_dashboard.html",
        "title": "Mi Dashboard - UPTTMBI",
        "content": """
        <div class="header-banner" style="padding: 2rem; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h1>Hola, Investigador</h1>
                <p>Bienvenido a tu panel de control personal.</p>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 1rem; border-radius: 8px; text-align: center;">
                <span style="display: block; font-size: 2rem; font-weight: bold; color: var(--text-main);">4</span>
                <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Proyectos Activos</span>
            </div>
        </div>
        <div class="grid grid-3">
            <div class="card">
                <h3 style="margin-bottom: 1rem; color: var(--text-dark);">Mis Postulaciones</h3>
                <p style="color: var(--text-light-muted); font-size: 0.9rem;">Tienes 2 postulaciones en revisión para proyectos de Robótica.</p>
                <a href="#" class="btn-primary" style="margin-top: 1rem; font-size: 0.8rem;">VER ESTADO</a>
            </div>
            <div class="card">
                <h3 style="margin-bottom: 1rem; color: var(--text-dark);">Última Actividad</h3>
                <ul style="list-style: none; color: var(--text-light-muted); font-size: 0.9rem; line-height: 1.8;">
                    <li>- Subida de archivo PST-091 PDF</li>
                    <li>- Comentario en "Sensor Agrícola"</li>
                </ul>
            </div>
        </div>
        """
    },
    {
        "path": "modules/RepositorioPST/views/home_bienvenida.html",
        "title": "Bienvenido al Repositorio Inteligente - UPTTMBI",
        "content": """
        <div class="header-banner" style="text-align: left; background: linear-gradient(135deg, #1a1f33, #0f172a); border-radius: 0; padding: 4rem 3rem;">
            <h1 style="font-weight: 700; margin-bottom: 1rem;">Bienvenido al Repositorio Inteligente</h1>
            <p style="color: #cbd5e1; font-size: 1.2rem; max-width: 800px;">La plataforma definitiva para la gestión, visibilidad e impacto de la producción científica de la UPTT</p>
        </div>
        <div style="padding: 0 1rem;">
            <div style="border-bottom: 2px solid #e2e8f0; margin-bottom: 2rem; display: flex; gap: 1.5rem;">
                <a href="#" style="padding: 1rem 0; color: var(--primary); font-weight: 600; text-decoration: none; border-bottom: 2px solid var(--primary); margin-bottom: -2px;">Inicio</a>
                <a href="#" style="padding: 1rem 0; color: var(--text-dark); font-weight: 600; text-decoration: none;">PST</a>
                <a href="#" style="padding: 1rem 0; color: var(--text-dark); font-weight: 600; text-decoration: none;">Investigaciones</a>
                <a href="#" style="padding: 1rem 0; color: var(--text-dark); font-weight: 600; text-decoration: none;">Cursos</a>
                <a href="#" style="padding: 1rem 0; color: var(--text-dark); font-weight: 600; text-decoration: none;">Artículos</a>
                <a href="#" style="padding: 1rem 0; color: var(--text-light-muted); font-weight: 600; text-decoration: none;">Solicitudes</a>
            </div>
            
            <div class="grid grid-3">
                <div class="card" style="border-top: 3px solid transparent;">
                    <h3 style="margin-bottom: 1rem;">Visibilidad PST</h3>
                    <p style="color: var(--text-light-muted); margin-bottom: 1.5rem; line-height: 1.5;">Acceso centralizado a todos los Proyectos Socio-Tecnológicos desarrollados por la comunidad.</p>
                    <a href="#" class="btn-primary">VER MÁS</a>
                </div>
                <div class="card" style="border-top: 3px solid var(--bg-dark);">
                    <h3 style="margin-bottom: 1rem;">IA de Clasificación</h3>
                    <p style="color: var(--text-light-muted); margin-bottom: 1.5rem; line-height: 1.5;">Nuestra red neuronal organiza investigaciones automáticamente en las líneas estratégicas.</p>
                    <a href="#" class="btn-primary">VER MÁS</a>
                </div>
                <div class="card" style="border-top: 3px solid transparent;">
                    <h3 style="margin-bottom: 1rem;">Vinculación Real</h3>
                    <p style="color: var(--text-light-muted); margin-bottom: 1.5rem; line-height: 1.5;">Conexión directa entre problemas del sector productivo y soluciones académicas.</p>
                    <a href="#" class="btn-primary">VER MÁS</a>
                </div>
            </div>
        </div>
        """
    },
    {
        "path": "modules/RepositorioPST/views/buscador_unificado.html",
        "title": "Búsqueda Inteligente - UPTTMBI",
        "content": """
        <div style="text-align: center; margin-bottom: 3rem; margin-top: 2rem;">
            <h1 style="color: var(--bg-panel); font-size: 2.5rem; margin-bottom: 0.5rem;">Búsqueda Inteligente</h1>
            <p style="color: var(--text-light-muted);">Motor de búsqueda impulsado con IA</p>
        </div>
        <div class="card" style="max-width: 900px; margin: 0 auto; padding: 2.5rem; border-radius: 12px;">
            <div class="form-group" style="position: relative;">
                <input type="text" class="form-control" placeholder="Buscar por títulos, palabras clave o metadatos..." style="padding-left: 1.5rem; padding-right: 3rem; height: 3.5rem; border-radius: 50px; font-size: 1.1rem; border-color: var(--primary);">
                <span style="position: absolute; right: 1.5rem; top: 1rem; color: var(--primary); font-size: 1.5rem; cursor: pointer;">🔍</span>
            </div>
            
            <div class="grid" style="grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-top: 2rem;">
                <div>
                    <label style="font-size: 0.8rem; font-weight: 600; color: var(--text-light-muted); text-transform: uppercase;">Autor</label>
                    <input type="text" class="form-control" placeholder="Nombre del investigador...">
                </div>
                <div>
                    <label style="font-size: 0.8rem; font-weight: 600; color: var(--text-light-muted); text-transform: uppercase;">Tipo de Documento</label>
                    <select class="form-control">
                        <option>PST, Tesis, Artículo...</option>
                    </select>
                </div>
                <div>
                    <label style="font-size: 0.8rem; font-weight: 600; color: var(--text-light-muted); text-transform: uppercase;">Año</label>
                    <select class="form-control">
                        <option>Seleccionar año...</option>
                    </select>
                </div>
            </div>
            
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                <div>
                    <label style="font-size: 0.8rem; font-weight: 600; color: var(--text-light-muted); text-transform: uppercase;">Carrera Universitaria</label>
                    <select class="form-control">
                        <option>PNF Informática, Mecánica, etc.</option>
                    </select>
                </div>
                <div>
                    <label style="font-size: 0.8rem; font-weight: 600; color: var(--text-light-muted); text-transform: uppercase;">Línea de Investigación</label>
                    <select class="form-control">
                        <option>Rama o área estratégica...</option>
                    </select>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 2.5rem;">
                <button class="btn-primary" style="padding: 0.8rem 2.5rem; font-size: 1.1rem; border-radius: 50px;">REALIZAR BÚSQUEDA</button>
            </div>
        </div>
        """
    },
    {
        "path": "modules/RepositorioPST/views/detalle_pst.html",
        "title": "Repositorio de PST - UPTTMBI",
        "content": """
        <div style="margin-bottom: 2rem;">
            <h1 style="color: var(--bg-panel);">Repositorio de Proyectos Socio-Tecnológicos</h1>
        </div>
        
        <div class="card" style="display: flex; padding: 0; overflow: hidden; margin-bottom: 3rem;">
            <div style="width: 300px; background: #e2e8f0; display: flex; align-items: center; justify-content: center;">
                <span style="color: var(--text-light-muted);">[ Imagen de Proyecto ]</span>
            </div>
            <div style="padding: 2.5rem; flex: 1;">
                <h3 style="font-size: 1.2rem; margin-bottom: 1rem;">Destacado del Mes</h3>
                <p style="font-size: 1.1rem; color: var(--text-light-muted); margin-bottom: 1.5rem;">Sistema Automatizado de Control de Inventarios para el Ambulatorio La Beatriz.</p>
                <button class="btn-primary">DESCARGAR PDF</button>
            </div>
        </div>

        <div class="card" style="padding: 0; overflow: hidden;">
            <div class="table-responsive">
                <table class="table" style="margin-top: 0;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título del Proyecto</th>
                            <th>Línea</th>
                            <th>Trayecto</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>PST-091</td>
                            <td>Gestión de Redes Comunitarias</td>
                            <td>Redes</td>
                            <td>III</td>
                            <td><button class="btn-primary" style="padding: 0.2rem 0.5rem; font-size: 0.8rem;">👁</button></td>
                        </tr>
                        <tr>
                            <td>PST-102</td>
                            <td>APP de Servicios Trujillo</td>
                            <td>Software</td>
                            <td>IV</td>
                            <td><button class="btn-primary" style="padding: 0.2rem 0.5rem; font-size: 0.8rem;">👁</button></td>
                        </tr>
                        <tr>
                            <td>PST-115</td>
                            <td>Sensor de Monitoreo Agrícola</td>
                            <td>Robótica</td>
                            <td>II</td>
                            <td><button class="btn-primary" style="padding: 0.2rem 0.5rem; font-size: 0.8rem;">👁</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        """
    },
    {
        "path": "modules/RepositorioPST/views/admin_subida_pst.html",
        "title": "Subida de PST - UPTTMBI",
        "content": """
        <div class="header-banner">
            <h1>Carga de Proyecto Socio-Tecnológico</h1>
            <p>Sube el documento PDF y los metadatos correspondientes.</p>
        </div>
        <div class="card">
            <form>
                <div class="grid grid-3">
                    <div class="form-group">
                        <label>Título del Proyecto</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Línea de Investigación</label>
                        <select class="form-control"><option>Software</option><option>Hardware</option></select>
                    </div>
                    <div class="form-group">
                        <label>Autores (Separados por coma)</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
                
                <div class="form-group" style="margin-top: 1.5rem;">
                    <label>Archivo del Proyecto (PDF)</label>
                    <div style="border: 2px dashed #cbd5e1; padding: 3rem; text-align: center; border-radius: 8px; background: #f8fafc; cursor: pointer;">
                        <p style="color: var(--text-light-muted); font-weight: 500;">Arrastra y suelta tu archivo PDF aquí o haz clic para explorar.</p>
                    </div>
                </div>
                
                <button type="submit" class="btn-primary" style="margin-top: 1rem;">SUBIR PROYECTO</button>
            </form>
        </div>
        """
    },
    {
        "path": "modules/RepositorioPST/views/dashboard_prediccion.html",
        "title": "Analítica Predictiva - UPTTMBI",
        "content": """
        <div class="header-banner" style="background: linear-gradient(135deg, #10b981, #059669);">
            <h1>Analítica Predictiva a 12 Meses</h1>
            <p>Proyecciones basadas en modelos de IA sobre tendencias de investigación.</p>
        </div>
        <div class="grid" style="grid-template-columns: 2fr 1fr;">
            <div class="card" style="min-height: 300px; display: flex; align-items: center; justify-content: center; background: #f8fafc;">
                <p style="color: var(--text-muted);">[ Gráfico de Líneas Predictivo Dummy ]</p>
            </div>
            <div class="card">
                <h3 style="margin-bottom: 1rem;">Insights IA</h3>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 1rem;">
                    <li style="padding: 1rem; background: #e0f2fe; border-radius: 6px; color: #0369a1;">⬆ Tendencia al alza en Robótica.</li>
                    <li style="padding: 1rem; background: #fef3c7; border-radius: 6px; color: #b45309;">➡ Estabilidad en Redes y Telecomunicaciones.</li>
                    <li style="padding: 1rem; background: #dcfce7; border-radius: 6px; color: #15803d;">⬆ Alta demanda de IA Agrícola proyectada para el Q3.</li>
                </ul>
            </div>
        </div>
        """
    },
    {
        "path": "modules/Investigaciones/views/showcase_investigaciones.html",
        "title": "Investigaciones Estratégicas - UPTTMBI",
        "content": """
        <div class="header-banner">
            <h1>Líneas Estratégicas de I+D</h1>
            <p>Explora nuestras áreas de desarrollo tecnológico y científico.</p>
        </div>
        <div class="grid grid-3">
            <div class="card" style="text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🤖</div>
                <h3>Inteligencia Artificial</h3>
                <p style="color: var(--text-light-muted); margin: 1rem 0;">Desarrollo de modelos LLM y visión por computadora.</p>
                <button class="btn-primary">Explorar</button>
            </div>
            <div class="card" style="text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">⚙️</div>
                <h3>Robótica y Automatización</h3>
                <p style="color: var(--text-light-muted); margin: 1rem 0;">Sistemas de control para la agroindustria regional.</p>
                <button class="btn-primary">Explorar</button>
            </div>
            <div class="card" style="text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📡</div>
                <h3>Telecomunicaciones</h3>
                <p style="color: var(--text-light-muted); margin: 1rem 0;">Redes comunitarias y protocolos de IoT.</p>
                <button class="btn-primary">Explorar</button>
            </div>
        </div>
        """
    },
    {
        "path": "modules/Investigaciones/views/cartelera.html",
        "title": "Cartelera de Investigaciones - UPTTMBI",
        "content": """
        <div style="margin-bottom: 2rem;">
            <h1 style="color: var(--bg-panel);">Anuncios y Noticias I+D</h1>
        </div>
        <div class="grid">
            <div class="card" style="border-left: 4px solid var(--primary);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <h3 style="color: var(--text-dark);">Apertura de Convocatoria Q2 2026</h3>
                    <span class="badge">NUEVO</span>
                </div>
                <p style="color: var(--text-light-muted); margin-bottom: 1rem;">Se abre el proceso de recepción de anteproyectos para el financiamiento de I+D en el área de Software Libre.</p>
                <a href="#" style="color: var(--primary); font-weight: 500; text-decoration: none;">Leer completo →</a>
            </div>
            <div class="card" style="border-left: 4px solid var(--text-muted);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <h3 style="color: var(--text-dark);">Resultados del Hackathon UPTT</h3>
                    <span class="badge" style="background: #f1f5f9; color: var(--text-light-muted);">HACE 1 SEMANA</span>
                </div>
                <p style="color: var(--text-light-muted); margin-bottom: 1rem;">Conoce a los equipos ganadores del evento anual de desarrollo rápido.</p>
                <a href="#" style="color: var(--primary); font-weight: 500; text-decoration: none;">Leer completo →</a>
            </div>
        </div>
        """
    },
    {
        "path": "modules/Investigaciones/views/ficha_investigador.html",
        "title": "Perfil del Investigador - UPTTMBI",
        "content": """
        <div class="card" style="max-width: 800px; margin: 0 auto;">
            <div style="display: flex; gap: 2rem; align-items: center;">
                <div style="width: 120px; height: 120px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem;">
                    👤
                </div>
                <div>
                    <h2 style="font-size: 1.8rem; color: var(--bg-panel); margin-bottom: 0.5rem;">Dr. Carlos Méndez</h2>
                    <p style="color: var(--primary); font-weight: 500; margin-bottom: 0.5rem;">Especialista en Robótica Agrícola</p>
                    <p style="color: var(--text-light-muted);">PNF Informática - Sede Central</p>
                    <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                        <span class="badge">IoT</span>
                        <span class="badge">Automatización</span>
                        <span class="badge">Python</span>
                    </div>
                </div>
            </div>
            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 2rem 0;">
            <h3 style="margin-bottom: 1rem;">Proyectos Publicados (12)</h3>
            <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.5rem; color: var(--text-light-muted);">
                <li>- Sistema de Riego Automatizado (2025)</li>
                <li>- Drones para Mapeo de Cultivos (2024)</li>
            </ul>
        </div>
        """
    },
    {
        "path": "modules/Investigaciones/views/panel_postulaciones.html",
        "title": "Vacantes I+D - UPTTMBI",
        "content": """
        <div class="header-banner" style="background: linear-gradient(135deg, #1e293b, #0f172a);">
            <h1>Oportunidades de Participación</h1>
            <p>Únete a proyectos de investigación activos como asistente o co-autor.</p>
        </div>
        <div class="grid grid-3">
            <div class="card">
                <span class="badge" style="margin-bottom: 1rem; display: inline-block;">Software Libre</span>
                <h3 style="margin-bottom: 0.5rem;">Desarrollador Backend Python</h3>
                <p style="color: var(--text-light-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Se requiere estudiante de 4to trayecto para optimización de API REST.</p>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.8rem; font-weight: 600; color: var(--success);">1 Vacante</span>
                    <button class="btn-primary" style="padding: 0.4rem 1rem; font-size: 0.9rem;" onclick="alert('Abre Modal')">APLICAR</button>
                </div>
            </div>
            <div class="card">
                <span class="badge" style="margin-bottom: 1rem; display: inline-block;">Hardware Libre</span>
                <h3 style="margin-bottom: 0.5rem;">Ensamblador de Placas Arduino</h3>
                <p style="color: var(--text-light-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Apoyo en la creación de sensores ambientales para proyecto PST.</p>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.8rem; font-weight: 600; color: var(--success);">3 Vacantes</span>
                    <button class="btn-primary" style="padding: 0.4rem 1rem; font-size: 0.9rem;" onclick="alert('Abre Modal')">APLICAR</button>
                </div>
            </div>
        </div>
        """
    },
    {
        "path": "modules/Investigaciones/views/modal_aplicar.html",
        "title": "Modal Aplicar - UPTTMBI",
        "content": """
        <!-- Este contenido debe inyectarse en un modal, pero se maqueta aislado -->
        <div style="background: rgba(0,0,0,0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
            <div class="card" style="width: 100%; max-width: 500px; padding: 2rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem;">
                    <h2 style="font-size: 1.2rem;">Postulación a Proyecto</h2>
                    <span style="cursor: pointer; font-size: 1.2rem;">✖</span>
                </div>
                <form>
                    <div class="form-group">
                        <label>Motivación</label>
                        <textarea class="form-control" rows="4" placeholder="¿Por qué deseas unirte a este proyecto?"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Enlace a Portafolio/GitHub (Opcional)</label>
                        <input type="url" class="form-control" placeholder="https://...">
                    </div>
                    <button class="btn-primary" style="width: 100%;" type="button">ENVIAR SOLICITUD</button>
                </form>
            </div>
        </div>
        """
    },
    {
        "path": "modules/Articulos/views/showcase_articulos.html",
        "title": "Revista Digital - UPTTMBI",
        "content": """
        <div class="header-banner" style="background: linear-gradient(135deg, #1e3a8a, #172554);">
            <h1>Saber y Acción Científica</h1>
            <p>Nuestra revista digital indexada. Descubre las últimas publicaciones.</p>
        </div>
        <h2 style="margin-bottom: 1.5rem; color: var(--bg-panel);">Edición Destacada (Vol. 5)</h2>
        <div class="card" style="display: flex; gap: 2rem; margin-bottom: 3rem;">
            <div style="width: 200px; height: 280px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-weight: bold; color: var(--text-light-muted);">PORTADA</div>
            <div style="flex: 1; display: flex; flex-direction: column; justify-content: center;">
                <h3 style="font-size: 1.8rem; margin-bottom: 1rem;">Avances Tecnológicos en la Región Andina</h3>
                <p style="color: var(--text-light-muted); margin-bottom: 1.5rem; line-height: 1.6;">Una recopilación de los mejores artículos sobre soberanía tecnológica y desarrollo de software libre en el estado Trujillo.</p>
                <div><button class="btn-primary">LEER EDICIÓN</button></div>
            </div>
        </div>
        """
    },
    {
        "path": "modules/Articulos/views/grid_revista.html",
        "title": "Grid Artículos - UPTTMBI",
        "content": """
        <div class="grid grid-3">
            <div class="card">
                <div style="height: 150px; background: #e2e8f0; margin: -1.5rem -1.5rem 1.5rem -1.5rem; border-radius: 8px 8px 0 0; display: flex; align-items: center; justify-content: center; color: var(--text-light-muted);">IMAGEN ARTÍCULO</div>
                <span class="badge" style="margin-bottom: 0.5rem; display: inline-block;">Ingeniería</span>
                <h3 style="font-size: 1.1rem; margin-bottom: 0.5rem; line-height: 1.4;">Análisis de Seguridad en Redes de Datos</h3>
                <p style="color: var(--text-light-muted); font-size: 0.9rem; margin-bottom: 1rem;">Autores: R. Diaz, P. Castillo</p>
                <a href="leer_articulo.html" class="btn-primary" style="font-size: 0.8rem; width: 100%;">LEER ARTÍCULO</a>
            </div>
            <div class="card">
                <div style="height: 150px; background: #e2e8f0; margin: -1.5rem -1.5rem 1.5rem -1.5rem; border-radius: 8px 8px 0 0; display: flex; align-items: center; justify-content: center; color: var(--text-light-muted);">IMAGEN ARTÍCULO</div>
                <span class="badge" style="margin-bottom: 0.5rem; display: inline-block;">Agronomía</span>
                <h3 style="font-size: 1.1rem; margin-bottom: 0.5rem; line-height: 1.4;">Factibilidad de Riego por Goteo</h3>
                <p style="color: var(--text-light-muted); font-size: 0.9rem; margin-bottom: 1rem;">Autores: J. Blanco, L. Pérez</p>
                <a href="leer_articulo.html" class="btn-primary" style="font-size: 0.8rem; width: 100%;">LEER ARTÍCULO</a>
            </div>
        </div>
        """
    },
    {
        "path": "modules/Articulos/views/leer_articulo.html",
        "title": "Leer Artículo - UPTTMBI",
        "content": """
        <div style="max-width: 800px; margin: 0 auto; background: white; padding: 4rem; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="text-align: center; margin-bottom: 3rem;">
                <span class="badge" style="margin-bottom: 1rem; display: inline-block;">Revista Saber y Acción Vol.5</span>
                <h1 style="color: var(--bg-panel); font-size: 2.2rem; line-height: 1.3; margin-bottom: 1rem;">Análisis de Seguridad en Redes de Datos Inalámbricas del Campus Universitario</h1>
                <p style="color: var(--text-light-muted); font-size: 1.1rem;">Por: R. Diaz, P. Castillo | Publicado: 12 Mayo 2026</p>
            </div>
            <div style="font-size: 1.1rem; line-height: 1.8; color: var(--text-dark);">
                <h3 style="margin: 2rem 0 1rem 0;">Resumen</h3>
                <p style="margin-bottom: 1.5rem; text-align: justify;">La infraestructura de red actual presenta desafíos significativos en cuanto a vulnerabilidades y protocolos de cifrado. Este estudio propone un esquema de seguridad robusto basado en el estándar IEEE 802.1X, evaluando el impacto...</p>
                
                <h3 style="margin: 2rem 0 1rem 0;">1. Introducción</h3>
                <p style="margin-bottom: 1.5rem; text-align: justify;">El crecimiento exponencial de dispositivos móviles en el entorno educativo ha forzado la adopción de redes inalámbricas como principal medio de conexión...</p>
                
                <div style="background: #f1f5f9; padding: 2rem; border-left: 4px solid var(--primary); margin: 2rem 0; font-style: italic;">
                    "La seguridad no es un producto, sino un proceso continuo de adaptación y mitigación en las redes universitarias."
                </div>
            </div>
        </div>
        """
    },
    {
        "path": "modules/VinculacionEmpresarial/views/landing_informativa.html",
        "title": "Vinculación Empresarial - UPTTMBI",
        "content": """
        <div class="header-banner" style="background: linear-gradient(135deg, #0f172a, #334155); text-align: center;">
            <h1>Vinculación Universidad-Empresa</h1>
            <p>Transformamos problemas productivos en soluciones académicas de alto impacto.</p>
        </div>
        <div class="grid grid-3">
            <div class="card" style="text-align: center; padding: 3rem 2rem;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem; color: var(--primary);">1️⃣</div>
                <h3>Plantea tu Reto</h3>
                <p style="color: var(--text-light-muted); margin-top: 1rem;">Las empresas registran sus necesidades operativas o tecnológicas en nuestra plataforma.</p>
            </div>
            <div class="card" style="text-align: center; padding: 3rem 2rem;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem; color: var(--primary);">2️⃣</div>
                <h3>Análisis Académico</h3>
                <p style="color: var(--text-light-muted); margin-top: 1rem;">Nuestros tutores evalúan y asignan el problema como un Proyecto Socio-Tecnológico.</p>
            </div>
            <div class="card" style="text-align: center; padding: 3rem 2rem;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem; color: var(--primary);">3️⃣</div>
                <h3>Solución Entregada</h3>
                <p style="color: var(--text-light-muted); margin-top: 1rem;">Los estudiantes desarrollan y despliegan la solución en su entorno productivo.</p>
            </div>
        </div>
        <div style="text-align: center; margin-top: 3rem;">
            <a href="form_aplicar_problema.html" class="btn-primary" style="font-size: 1.2rem; padding: 1rem 3rem;">CARGAR UNA NECESIDAD AHORA</a>
        </div>
        """
    },
    {
        "path": "modules/VinculacionEmpresarial/views/form_aplicar_problema.html",
        "title": "Cargar Problemática - UPTTMBI",
        "content": """
        <div class="card" style="max-width: 700px; margin: 0 auto;">
            <h2 style="color: var(--bg-panel); margin-bottom: 0.5rem;">Registro de Necesidad Tecnológica</h2>
            <p style="color: var(--text-light-muted); margin-bottom: 2rem;">Complete los datos de su empresa y describa el problema que requiere solución.</p>
            
            <form>
                <h4 style="margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e2e8f0;">Datos de la Empresa</h4>
                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div class="form-group">
                        <label>Nombre / Razón Social</label>
                        <input type="text" class="form-control" placeholder="Ej: Lácteos Los Andes">
                    </div>
                    <div class="form-group">
                        <label>RIF</label>
                        <input type="text" class="form-control" placeholder="J-12345678-9">
                    </div>
                </div>
                
                <h4 style="margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e2e8f0;">Descripción del Problema</h4>
                <div class="form-group">
                    <label>Título Breve de la Necesidad</label>
                    <input type="text" class="form-control" placeholder="Ej: Control automatizado de temperatura en silos">
                </div>
                <div class="form-group">
                    <label>Descripción Detallada</label>
                    <textarea class="form-control" rows="5" placeholder="Explique la situación actual, los cuellos de botella y qué espera lograr..."></textarea>
                </div>
                
                <button type="button" class="btn-primary" style="width: 100%; font-size: 1.1rem; padding: 1rem;">ENVIAR PROPUESTA AL CONSEJO ACADÉMICO</button>
            </form>
        </div>
        """
    },
    {
        "path": "modules/VinculacionEmpresarial/views/banco_propuestas.html",
        "title": "Banco de Propuestas - UPTTMBI",
        "content": """
        <div style="margin-bottom: 2rem;">
            <h1 style="color: var(--bg-panel);">Banco de Necesidades Empresariales</h1>
            <p style="color: var(--text-light-muted);">Problemas reales esperando ser asignados como PST.</p>
        </div>
        <div class="card" style="padding: 0;">
            <table class="table" style="margin-top: 0;">
                <thead>
                    <tr>
                        <th>Empresa</th>
                        <th>Problemática</th>
                        <th>Fecha Ingreso</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight: 600;">Lácteos Los Andes</td>
                        <td>Control automatizado de silos de leche</td>
                        <td>01/06/2026</td>
                        <td><span class="badge" style="background: #dcfce7; color: #15803d;">APROBADO PARA PST</span></td>
                        <td><button class="btn-primary" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">Ver Detalles</button></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600;">Ambulatorio La Beatriz</td>
                        <td>Digitalización de Historias Médicas</td>
                        <td>05/06/2026</td>
                        <td><span class="badge" style="background: #fef3c7; color: #b45309;">EN REVISIÓN</span></td>
                        <td><button class="btn-primary" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">Ver Detalles</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        """
    },
    {
        "path": "modules/Cursos/views/showcase_cursos.html",
        "title": "Cursos UPTTMBI",
        "content": """
        <div class="header-banner" style="background: linear-gradient(135deg, #0ea5e9, #0284c7);">
            <h1>Formación Continua y Cursos</h1>
            <p>Potencia tus habilidades con nuestra oferta académica avalada.</p>
        </div>
        <div class="card" style="text-align: center; padding: 4rem 2rem; background: #f8fafc; margin-bottom: 3rem;">
            <h2 style="font-size: 2rem; color: var(--bg-panel); margin-bottom: 1rem;">Aprende con los mejores expertos de la UPTT</h2>
            <p style="color: var(--text-light-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto 2rem auto;">Cursos diseñados para complementar tu formación académica, enfocados en tecnologías de vanguardia demandadas por el mercado.</p>
            <a href="grid_cursos.html" class="btn-primary" style="font-size: 1.1rem; padding: 0.8rem 2rem;">VER CATÁLOGO COMPLETO</a>
        </div>
        """
    },
    {
        "path": "modules/Cursos/views/grid_cursos.html",
        "title": "Catálogo de Cursos - UPTTMBI",
        "content": """
        <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
            <h1 style="color: var(--bg-panel);">Cursos Activos</h1>
            <div style="display: flex; gap: 0.5rem;">
                <input type="text" class="form-control" placeholder="Buscar curso..." style="width: 250px;">
            </div>
        </div>
        <div class="grid grid-3">
            <div class="card">
                <div style="height: 140px; background: #1e293b; margin: -1.5rem -1.5rem 1.5rem -1.5rem; border-radius: 8px 8px 0 0; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; font-weight: bold;">
                    Python for Data Science
                </div>
                <h3 style="margin-bottom: 0.5rem;">Introducción a Pandas y Numpy</h3>
                <p style="color: var(--text-light-muted); font-size: 0.9rem; margin-bottom: 1rem;">Duración: 40 Horas | Modalidad: Virtual</p>
                <div style="background: #e2e8f0; height: 8px; border-radius: 4px; margin-bottom: 1rem; overflow: hidden;">
                    <div style="width: 0%; height: 100%; background: var(--success);"></div>
                </div>
                <button class="btn-primary" style="width: 100%;">MATRICULARME</button>
            </div>
            <div class="card">
                <div style="height: 140px; background: #0284c7; margin: -1.5rem -1.5rem 1.5rem -1.5rem; border-radius: 8px 8px 0 0; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; font-weight: bold;">
                    Docker & Kubernetes
                </div>
                <h3 style="margin-bottom: 0.5rem;">Contenedores en Producción</h3>
                <p style="color: var(--text-light-muted); font-size: 0.9rem; margin-bottom: 1rem;">Duración: 60 Horas | Modalidad: Híbrida</p>
                <div style="background: #e2e8f0; height: 8px; border-radius: 4px; margin-bottom: 1rem; overflow: hidden;">
                    <div style="width: 45%; height: 100%; background: var(--success);"></div>
                </div>
                <button class="btn-primary" style="width: 100%; background: var(--bg-panel);">CONTINUAR APRENDIZAJE</button>
            </div>
        </div>
        """
    },
    {
        "path": "modules/SuperAdmin/views/dashboard_admin.html",
        "title": "SuperAdmin Dashboard - UPTTMBI",
        "content": """
        <div class="header-banner" style="background: linear-gradient(135deg, #475569, #1e293b); padding: 2rem;">
            <h1>Panel de Control del Sistema (SuperAdmin)</h1>
            <p>Monitoreo global de la plataforma.</p>
        </div>
        <div class="grid grid-3" style="margin-bottom: 2rem;">
            <div class="card" style="border-left: 4px solid var(--primary);">
                <p style="color: var(--text-light-muted); font-size: 0.9rem; font-weight: 600; text-transform: uppercase;">Usuarios Registrados</p>
                <h2 style="font-size: 2.5rem; color: var(--bg-panel); margin-top: 0.5rem;">4,201</h2>
            </div>
            <div class="card" style="border-left: 4px solid var(--success);">
                <p style="color: var(--text-light-muted); font-size: 0.9rem; font-weight: 600; text-transform: uppercase;">PST Almacenados</p>
                <h2 style="font-size: 2.5rem; color: var(--bg-panel); margin-top: 0.5rem;">854</h2>
            </div>
            <div class="card" style="border-left: 4px solid #f59e0b;">
                <p style="color: var(--text-light-muted); font-size: 0.9rem; font-weight: 600; text-transform: uppercase;">Consultas IA (Mes)</p>
                <h2 style="font-size: 2.5rem; color: var(--bg-panel); margin-top: 0.5rem;">12.5K</h2>
            </div>
        </div>
        <div class="card">
            <h3 style="margin-bottom: 1rem;">Actividad Reciente del Sistema</h3>
            <ul style="list-style: none; font-size: 0.9rem; color: var(--text-dark);">
                <li style="padding: 0.8rem 0; border-bottom: 1px solid #e2e8f0;">[14:32:01] 🟢 Módulo IA respondió consulta en 240ms</li>
                <li style="padding: 0.8rem 0; border-bottom: 1px solid #e2e8f0;">[14:30:15] 🔴 Fallo de inicio de sesión IP: 192.168.1.100</li>
                <li style="padding: 0.8rem 0;">[14:28:44] 🟢 Nuevo PST (ID: 116) indexado correctamente.</li>
            </ul>
        </div>
        """
    },
    {
        "path": "modules/SuperAdmin/views/gestor_modulos.html",
        "title": "Gestor de Módulos - UPTTMBI",
        "content": """
        <div style="margin-bottom: 2rem;">
            <h1 style="color: var(--bg-panel);">Gestor de Módulos (Microkernel)</h1>
            <p style="color: var(--text-light-muted);">Activa o desactiva componentes del sistema en caliente.</p>
        </div>
        <div class="card" style="padding: 0;">
            <table class="table" style="margin-top: 0;">
                <thead>
                    <tr>
                        <th>Módulo</th>
                        <th>Versión</th>
                        <th>Ruta Core</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight: 600;">Autenticacion</td>
                        <td>v1.0.2</td>
                        <td>/modules/Autenticacion</td>
                        <td><span class="badge" style="background: #dcfce7; color: #15803d;">ACTIVO (CORE)</span></td>
                        <td><button class="btn-primary" style="background: #94a3b8; cursor: not-allowed;" disabled>Bloqueado</button></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600;">RepositorioPST</td>
                        <td>v2.1.0</td>
                        <td>/modules/RepositorioPST</td>
                        <td><span class="badge" style="background: #dcfce7; color: #15803d;">ACTIVO</span></td>
                        <td><button class="btn-primary" style="background: #ef4444;">Desactivar</button></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600;">ForoChatbot</td>
                        <td>v0.9.beta</td>
                        <td>/modules/ForoChatbot</td>
                        <td><span class="badge" style="background: #fee2e2; color: #b91c1c;">INACTIVO</span></td>
                        <td><button class="btn-primary" style="background: var(--success);">Activar</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        """
    }
]

# Asegurar que el directorio de artefactos existe
os.makedirs(artifact_dir, exist_ok=True)

artifact_md = "# Vistas HTML/CSS Generadas - UPTTMBI\n\n"
artifact_md += "Este documento contiene el código HTML/CSS para las 23 vistas solicitadas, organizadas por módulo.\n\n"

for view in views:
    full_path = os.path.join(base_dir, view["path"].replace("/", os.sep))
    dir_path = os.path.dirname(full_path)
    
    # Create directories
    os.makedirs(dir_path, exist_ok=True)
    
    # Generate content
    html_content = generate_html(view["title"], view["content"])
    
    # Write to file
    with open(full_path, "w", encoding="utf-8") as f:
        f.write(html_content)
        
    # Append to markdown artifact
    artifact_md += f"### `{view['path']}`\n"
    artifact_md += "```html\n"
    artifact_md += html_content + "\n"
    artifact_md += "```\n\n"

artifact_path = os.path.join(artifact_dir, "interfaces_html_css.md")
with open(artifact_path, "w", encoding="utf-8") as f:
    f.write(artifact_md)

print(f"Generadas exitosamente {len(views)} vistas en la arquitectura y creado el archivo artifact interfaces_html_css.md.")
