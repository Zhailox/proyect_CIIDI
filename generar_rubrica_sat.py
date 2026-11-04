# -*- coding: utf-8 -*-
"""
Script: generar_rubrica_sat.py
Propósito: Generar el documento Word de Rúbrica y Matriz de Pruebas de Aceptación en Sitio (SAT)
           para el Sistema Integrado de Gestión Científica y Tecnológica (CIIDI - UPTTMBI),
           considerando Roles del Sistema, Cargos Institucionales y Funcionalidades de:
           Core / Instalador, Autenticación, RepositorioPST, Artículos y SuperAdmin.
Formato estricto solicitado:
  - Fuente: Arial 12 pt en todo el cuadro/documento
  - Interlineado: Espaciado simple (1.0)
  - Sin colores (Texto negro #000000, fondo transparente/blanco, bordes negros simples #000000)
  - Columnas de la tabla SAT:
    1. ID del ítem
    2. Descripción de la prueba
    3. Resultado esperado
    4. Resultado obtenido (Pasa / No pasa / N/A)
    5. Comentarios/Observaciones
Salida: Rubrica_Pruebas_SAT_CIIDI.docx
"""

from docx import Document
from docx.shared import Pt, Cm, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.oxml import parse_xml
from docx.oxml.ns import nsdecls

NEGRO = RGBColor(0, 0, 0)


def formato_arial12_simple(parrafo, alineacion=WD_ALIGN_PARAGRAPH.LEFT, espacio_despues=0, espacio_antes=0):
    """Aplica fuente Arial 12, color negro y espaciado simple (1.0) a un párrafo."""
    parrafo.alignment = alineacion
    parrafo.paragraph_format.line_spacing = 1.0
    parrafo.paragraph_format.space_before = Pt(espacio_antes)
    parrafo.paragraph_format.space_after = Pt(espacio_despues)


def agregar_texto(parrafo, texto, negrita=False, cursiva=False):
    """Agrega un run en Arial 12 negro (sin colores)."""
    run = parrafo.add_run(texto)
    run.font.name = "Arial"
    run.font.size = Pt(12)
    run.font.bold = negrita
    run.font.italic = cursiva
    run.font.color.rgb = NEGRO
    return run


def aplicar_bordes_negros_simples(tabla):
    """Aplica bordes de cuadrícula negros simples (#000000) sin ningún color ni sombreado."""
    tblPr = tabla._tbl.tblPr
    borders = parse_xml(
        f'<w:tblBorders {nsdecls("w")}>'
        f'<w:top w:val="single" w:sz="4" w:space="0" w:color="000000"/>'
        f'<w:bottom w:val="single" w:sz="4" w:space="0" w:color="000000"/>'
        f'<w:left w:val="single" w:sz="4" w:space="0" w:color="000000"/>'
        f'<w:right w:val="single" w:sz="4" w:space="0" w:color="000000"/>'
        f'<w:insideH w:val="single" w:sz="4" w:space="0" w:color="000000"/>'
        f'<w:insideV w:val="single" w:sz="4" w:space="0" w:color="000000"/>'
        f'</w:tblBorders>'
    )
    tblPr.append(borders)


def configurar_padding_celda(cell, top=60, bottom=60, left=80, right=80):
    """Configura márgenes internos sobrios en la celda sin ningún color de fondo."""
    tcPr = cell._tc.get_or_add_tcPr()
    tcMar = parse_xml(
        f'<w:tcMar {nsdecls("w")}>'
        f'<w:top w:w="{top}" w:type="dxa"/>'
        f'<w:bottom w:w="{bottom}" w:type="dxa"/>'
        f'<w:left w:w="{left}" w:type="dxa"/>'
        f'<w:right w:w="{right}" w:type="dxa"/>'
        f'</w:tcMar>'
    )
    tcPr.append(tcMar)


def poblar_tabla_arial12(tabla, filas_datos, anchos_columnas, centrar_columnas=()):
    """Llena una tabla garantizando Arial 12, interlineado simple (1.0) y cero colores."""
    aplicar_bordes_negros_simples(tabla)
    tabla.alignment = WD_TABLE_ALIGNMENT.CENTER

    for row_idx, fila in enumerate(filas_datos):
        tr = tabla.rows[row_idx]
        tr._tr.get_or_add_trPr().append(parse_xml(f'<w:cantSplit {nsdecls("w")}/>'))
        if row_idx == 0:
            tr._tr.get_or_add_trPr().append(parse_xml(f'<w:tblHeader {nsdecls("w")}/>'))

        for col_idx, texto in enumerate(fila):
            celda = tr.cells[col_idx]
            celda.width = anchos_columnas[col_idx]
            configurar_padding_celda(celda)
            p = celda.paragraphs[0]

            if row_idx == 0 or col_idx in centrar_columnas:
                formato_arial12_simple(p, alineacion=WD_ALIGN_PARAGRAPH.CENTER)
            else:
                formato_arial12_simple(p, alineacion=WD_ALIGN_PARAGRAPH.LEFT)

            es_encabezado = (row_idx == 0)
            agregar_texto(p, texto, negrita=es_encabezado)


def construir_rubrica_sat():
    doc = Document()

    # Configuración de márgenes (2.0 cm para dar amplitud a las 5 columnas en Arial 12)
    for section in doc.sections:
        section.top_margin = Cm(2.0)
        section.bottom_margin = Cm(2.0)
        section.left_margin = Cm(2.0)
        section.right_margin = Cm(2.0)

    estilo_normal = doc.styles['Normal']
    estilo_normal.font.name = 'Arial'
    estilo_normal.font.size = Pt(12)
    estilo_normal.font.color.rgb = NEGRO

    # =========================================================================
    # 1. ENCABEZADO INSTITUCIONAL
    # =========================================================================
    p_enc = doc.add_paragraph()
    formato_arial12_simple(p_enc, alineacion=WD_ALIGN_PARAGRAPH.CENTER, espacio_despues=6)
    agregar_texto(
        p_enc,
        "REPÚBLICA BOLIVARIANA DE VENEZUELA\n"
        "MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA\n"
        "UNIVERSIDAD POLITÉCNICA TERRITORIAL DE TRUJILLO \"MARIO BRICEÑO IRAGORRY\"\n"
        "PROGRAMA NACIONAL DE FORMACIÓN EN INFORMÁTICA (PNFI)\n"
        "CENTRO DE INVESTIGACIÓN, INNOVACIÓN Y DESARROLLO INFORMÁTICO (CIIDI)",
        negrita=True
    )

    p_tit = doc.add_paragraph()
    formato_arial12_simple(p_tit, alineacion=WD_ALIGN_PARAGRAPH.CENTER, espacio_antes=6, espacio_despues=6)
    agregar_texto(
        p_tit,
        "PROTOCOLO, RÚBRICA Y MATRIZ DE PRUEBAS DE ACEPTACIÓN EN SITIO (SAT)\n"
        "(SITE ACCEPTANCE TESTING — EVALUACIÓN OPERATIVA POR ROLES Y CARGOS)",
        negrita=True
    )

    # =========================================================================
    # 2. INTRODUCCIÓN Y PROPÓSITO DEL PROTOCOLO EN SITIO (4.3.3.2)
    # =========================================================================
    p_sec1 = doc.add_paragraph()
    formato_arial12_simple(p_sec1, alineacion=WD_ALIGN_PARAGRAPH.LEFT, espacio_antes=6, espacio_despues=4)
    agregar_texto(p_sec1, "1. Propósito y Alcance de las Pruebas en Sitio (SAT)", negrita=True)

    p_intro = doc.add_paragraph()
    formato_arial12_simple(p_intro, alineacion=WD_ALIGN_PARAGRAPH.JUSTIFY, espacio_despues=6)
    agregar_texto(
        p_intro,
        "Las Pruebas de Aceptación en Sitio (SAT, por sus siglas en inglés Site Acceptance Testing) constituyen la fase "
        "de validación presencial ejecutada directamente en la infraestructura tecnológica de la Universidad Politécnica "
        "Territorial de Trujillo \"Mario Briceño Iragorry\" (UPTTMBI), contando con la participación activa de los actores "
        "institucionales según sus respectivos cargos y niveles de privilegio dentro del sistema CIIDI. A diferencia de "
        "las pruebas en fábrica (FAT), el protocolo SAT evalúa el comportamiento del software instalado en el entorno "
        "operativo real, certificando la usabilidad, los tiempos de respuesta frente al proceso manual previo y el "
        "cumplimiento estricto de las funciones asignadas a cada rol en el Core (Instalador Autónomo y Seguridad), "
        "Autenticación, RepositorioPST, Artículos Científicos y SuperAdmin."
    )

    # =========================================================================
    # 3. RÚBRICA DE ROLES, CARGOS INSTITUCIONALES Y ALCANCE EN EL SITIO
    # =========================================================================
    p_sec2 = doc.add_paragraph()
    formato_arial12_simple(p_sec2, alineacion=WD_ALIGN_PARAGRAPH.LEFT, espacio_antes=6, espacio_despues=4)
    agregar_texto(p_sec2, "2. Matriz de Roles del Sistema, Cargos Institucionales y Responsabilidades en Sitio", negrita=True)

    datos_roles = [
        (
            "Rol en el Sistema\n(Nivel RBAC)",
            "Cargo Institucional en la UPTTMBI",
            "Módulos Habilitados",
            "Actividades a Ejecutar durante la Prueba en Sitio (SAT)"
        ),
        (
            "Super Administrador\n(Nivel 0)",
            "Coordinador(a) de Soporte Técnico / Administrador(a) de Red y Servidores del CIIDI",
            "Core / Instalador, SuperAdmin, Autenticación, RepositorioPST y Artículos",
            "Ejecutar el instalador autónomo en el servidor universitario, configurar PostgreSQL y correo SMTP, administrar usuarios/roles en la Matriz RBAC, auditar logs forenses SHA-256, programar respaldos (.sql.gz), gestionar el modo mantenimiento y verificar la cuenta de emergencia (Break-Glass)."
        ),
        (
            "Comité\n(Nivel 1)",
            "Coordinador(a) del CIIDI / Miembro del Comité Editorial y de Investigación del PNFI",
            "Autenticación, RepositorioPST y Artículos (Gestión y Auditoría)",
            "Subir y catalogar Proyectos Socio-Tecnológicos (PST) asistidos por el extractor heurístico de IA, administrar líneas de investigación, trayectos y niveles académicos, publicar y editar artículos científicos, vincular investigadores con código ORCID y gestionar catálogos editoriales."
        ),
        (
            "Docentes\n(Nivel 2)",
            "Docente Investigador(a) / Tutor(a) Académico o Metodológico del PNFI",
            "Autenticación, RepositorioPST (Consulta) y Artículos (Consulta / Perfil de Autor)",
            "Activar cuenta mediante invitación institucional por correo, gestionar su perfil y fotografía (avatar), consultar proyectos PST tutoreados o por comunidad beneficiada, verificar su perfil público de investigador (ORCID) y emplear el buscador semántico IA para revisión de antecedentes."
        ),
        (
            "Estudiantes\n(Nivel 5)",
            "Estudiante Tesista / Proyectista de Trayecto I al IV del PNFI y Comunidad Universitaria",
            "Autenticación, RepositorioPST (Público) y Artículos (Revista Digital)",
            "Iniciar sesión y recuperar clave por correo, explorar y filtrar el Repositorio de Proyectos PST, visualizar documentos en el lector PDF integrado, realizar búsquedas conceptuales en el Buscador Unificado con IA, consultar artículos de la Revista Digital y comprobar el bloqueo de rutas administrativas (HTTP 403)."
        )
    ]

    tabla_roles = doc.add_table(rows=len(datos_roles), cols=4)
    anchos_roles = [Cm(3.2), Cm(4.2), Cm(3.6), Cm(6.0)]
    poblar_tabla_arial12(tabla_roles, datos_roles, anchos_roles, centrar_columnas=(0,))

    # =========================================================================
    # 4. ESCALA Y CRITERIOS DE CALIFICACIÓN DE LA RÚBRICA EN SITIO
    # =========================================================================
    p_sec3 = doc.add_paragraph()
    formato_arial12_simple(p_sec3, alineacion=WD_ALIGN_PARAGRAPH.LEFT, espacio_antes=8, espacio_despues=4)
    agregar_texto(p_sec3, "3. Criterios de Calificación para la Evaluación en Sitio", negrita=True)

    datos_criterios = [
        ("Resultado Obtenido", "Criterio de Aceptación en Sitio", "Acción Requerida"),
        (
            "Pasa",
            "El módulo o función responde en el servidor local de la universidad cumpliendo exactamente el resultado esperado para el rol y cargo evaluador, sin errores visuales ni técnicos.",
            "Ítem validado y aprobado formalmente en el acta de aceptación en sitio."
        ),
        (
            "No pasa",
            "Se presenta una discrepancia funcional, error de permisos RBAC, tiempo de respuesta excesivo o fallo de integración con el entorno institucional.",
            "Se registra la incidencia en Comentarios/Observaciones para ajuste inmediato y re-ejecución."
        ),
        (
            "N/A",
            "La prueba no aplica temporalmente en la estación evaluada (por ejemplo, ausencia de conexión SMTP externa en un laboratorio aislado sin salida a internet).",
            "Se documenta la condición ambiental en la columna de Comentarios/Observaciones."
        )
    ]

    tabla_criterios = doc.add_table(rows=len(datos_criterios), cols=3)
    anchos_criterios = [Cm(3.2), Cm(8.3), Cm(5.5)]
    poblar_tabla_arial12(tabla_criterios, datos_criterios, anchos_criterios, centrar_columnas=(0,))

    # =========================================================================
    # 5. MATRIZ / CUADRO DE PRUEBAS DE ACEPTACIÓN EN SITIO (SAT-001 A SAT-025)
    # =========================================================================
    p_sec4 = doc.add_paragraph()
    formato_arial12_simple(p_sec4, alineacion=WD_ALIGN_PARAGRAPH.LEFT, espacio_antes=8, espacio_despues=4)
    agregar_texto(p_sec4, "4. Cuadro de Ejecución de Pruebas de Aceptación en Sitio (SAT)", negrita=True)

    datos_sat = [
        (
            "ID del ítem",
            "Descripción de la prueba",
            "Resultado esperado",
            "Resultado obtenido (Pasa / No pasa / N/A)",
            "Comentarios/Observaciones"
        ),
        # --- BLOQUE 1: CORE / INSTALADOR EN SITIO ---
        (
            "SAT-001",
            "[Core / Instalador] Cargo: Coordinador de Soporte Técnico (Rol: Super Administrador). Acceder al asistente de instalación en el servidor de la universidad y ejecutar la auditoría automática de prerrequisitos del entorno.",
            "El instalador verifica PHP, las extensiones pdo_pgsql, fileinfo, gd, mbstring, openssl y los permisos de escritura en config/ y storage/, habilitando el botón de avance.",
            "Pasa",
            "Prerrequisitos del servidor institucional verificados al 100% sin dependencias faltantes."
        ),
        (
            "SAT-002",
            "[Core / Instalador] Cargo: Administrador de Base de Datos (Rol: Super Administrador). Ingresar las credenciales del servidor PostgreSQL institucional en el Paso 2 del instalador y ejecutar el despliegue del esquema base.",
            "El sistema valida la conexión sin colgarse, instala las 43 tablas del esquema público junto con la extensión vectorial pgvector y los triggers PL/pgSQL de auditoría.",
            "Pasa",
            "Esquema relacional, extensión pgvector y disparadores creados correctamente en PostgreSQL."
        ),
        (
            "SAT-003",
            "[Core / Instalador] Cargo: Coordinador del CIIDI / Soporte Técnico (Rol: Super Administrador). Registrar la cuenta maestra institucional en el Paso 3 del instalador y comprobar el bloqueo posterior de la ruta de instalación.",
            "Exige contraseña segura (mínimo 8 caracteres con hash Bcrypt), genera el archivo candado storage/installed.lock y redirige automáticamente al portal principal.",
            "Pasa",
            "Cuenta maestra creada y candado installed.lock activo; bloqueado el reingreso al instalador."
        ),
        (
            "SAT-004",
            "[Core / Continuidad Operativa] Cargo: Coordinador de Soporte Técnico (Rol: Super Administrador) y Estudiante Tesista (Rol: Estudiantes). Activar el Modo Mantenimiento desde el panel administrativo y navegar desde una estación de estudiante.",
            "La estación del estudiante recibe la vista institucional de mantenimiento (HTTP 503) cerrando su sesión de forma segura, mientras el Super Administrador conserva acceso total.",
            "Pasa",
            "Conmutación en caliente verificada en red local; sesiones no administrativas expulsadas limpiamente."
        ),
        (
            "SAT-005",
            "[Core / WAF y Excepciones] Cargo: Auditor Técnico / Soporte (Todos los Roles). Probar desde el navegador institucional el acceso a una URL inexistente (/ruta-invalida) y realizar 6 intentos fallidos de inicio de sesión.",
            "El sistema presenta la vista institucional HTTP 404 sin exponer rutas del servidor y bloquea temporalmente la IP por 15 minutos al superar 5 intentos fallidos.",
            "Pasa",
            "Respuestas HTTP 404/403 verificadas y bloqueo registrado en el monitor WAF."
        ),
        # --- BLOQUE 2: AUTENTICACIÓN, SESIONES Y ROLES EN SITIO ---
        (
            "SAT-006",
            "[Autenticación] Cargo: Estudiante Tesista y Docente Investigador (Roles: Estudiantes / Docentes). Iniciar sesión en /login empleando Cédula o Correo institucional, y probar el ingreso con una clave incorrecta.",
            "Permite el acceso redirigiendo al entorno correspondiente al rol del usuario, y ante claves o usuarios erróneos muestra un mensaje genérico ('Credenciales inválidas').",
            "Pasa",
            "Autenticación rápida (< 1 seg) y mensaje anti-enumeración validado por los usuarios."
        ),
        (
            "SAT-007",
            "[Autenticación] Cargo: Docente Investigador (Rol: Docentes). Abrir el enlace de invitación docente recibido por correo electrónico, completar el formulario de activación y definir su contraseña personal.",
            "El sistema valida el token SHA-256 vigente (48 horas), activa la cuenta con el rol Docentes (Nivel 2) e invalida el token para impedir su reutilización.",
            "Pasa",
            "Flujo de incorporación docente completado sin intervención manual del soporte técnico."
        ),
        (
            "SAT-008",
            "[Autenticación] Cargo: Estudiante Tesista (Rol: Estudiantes). Solicitar recuperación de contraseña en /recuperar-cuenta, restablecerla mediante el token enviado y verificar el cierre automático de sesión por inactividad.",
            "El enlace temporal (vigencia de 1 hora) permite actualizar la clave con protección CSRF, y la sesión inactiva expulsa automáticamente al usuario hacia /login.",
            "Pasa",
            "Restablecimiento de clave y caducidad automática de sesión comprobados en laboratorio."
        ),
        (
            "SAT-009",
            "[Autenticación / Perfil] Cargo: Docente Investigador y Miembro del Comité (Roles: Docentes / Comité). Actualizar datos personales en /perfil, cambiar contraseña y subir una imagen de avatar (y probar subir un archivo no gráfico).",
            "Actualiza los datos, reprocesa el avatar legítimo (.jpg/.png/.webp) mediante librería GD y rechaza cualquier archivo cuya firma binaria no corresponda a una imagen.",
            "Pasa",
            "Fotografía de perfil actualizada correctamente y archivo falso rechazado por filtro MIME."
        ),
        (
            "SAT-010",
            "[Autenticación / RBAC] Cargo: Estudiante Tesista y Docente Investigador (Roles: Estudiantes / Docentes). Intentar acceder escribiendo en la barra de direcciones las rutas administrativas /sudoadmin, /visor-logs y /agregar-documento.",
            "El motor RBAC verifica el nivel de privilegio de la sesión activa, deniega el acceso y muestra la pantalla institucional 403 Acceso Denegado.",
            "Pasa",
            "Aislamiento estricto de privilegios confirmado para los roles Estudiantes (Nivel 5) y Docentes (Nivel 2)."
        ),
        # --- BLOQUE 3: REPOSITORIO PST E INTELIGENCIA ARTIFICIAL EN SITIO ---
        (
            "SAT-011",
            "[RepositorioPST] Cargo: Coordinador de PNF / Miembro del Comité (Rol: Comité). Subir un informe real de Proyecto Socio-Tecnológico (PDF/DOCX) en /agregar-documento para evaluar la extracción automática de metadatos.",
            "El extractor heurístico lee el documento y autocompleta título, autores con cédula, tutor, año, resumen, palabras clave y objetivo general en menos de 3 segundos.",
            "Pasa",
            "Reducción drástica del tiempo de carga frente al registro manual; campos autocompletados con precisión."
        ),
        (
            "SAT-012",
            "[RepositorioPST] Cargo: Miembro del Comité CIIDI (Rol: Comité). Completar la clasificación del PST (Carrera, Trayecto, Nivel Académico, Línea de Investigación, Dimensión y Comunidad Beneficiada), guardar la ficha e intentar subir un ejecutable renombrado a .pdf.",
            "Registra transaccionalmente el proyecto PST vinculando autores y tutores sin duplicados, y bloquea de inmediato el archivo falso por inspección de Magic Bytes.",
            "Pasa",
            "Proyecto PST publicado en el catálogo y archivo apócrifo rechazado por seguridad binaria."
        ),
        (
            "SAT-013",
            "[RepositorioPST / Motor IA] Cargo: Estudiante Tesista y Docente Tutor (Roles: Estudiantes / Docentes). Realizar búsquedas por lenguaje natural e ideas conceptuales en /buscador (ej. 'automatización de riego agrícola comunitario').",
            "El motor vectorial (pgvector + modelo ONNX de 384 dimensiones) recupera los proyectos semánticamente afines ordenados por relevancia aunque no coincidan las palabras exactas.",
            "Pasa",
            "Alta pertinencia en resultados semánticos validada por docentes tutores y tesistas."
        ),
        (
            "SAT-014",
            "[RepositorioPST] Cargo: Estudiante Tesista (Rol: Estudiantes). Filtrar el catálogo /repositorio por Carrera, Trayecto, Nivel Académico y Comunidad, abrir la ficha técnica (/detalles-pst) y leer el PDF en el visor embebido.",
            "Filtra en tiempo real sin recargas erróneas, muestra la ficha técnica completa, incrementa el contador de vistas y despliega el PDF respetando los permisos de descarga.",
            "Pasa",
            "Navegación fluida e intuitiva; visor documental operativo en los navegadores del laboratorio."
        ),
        (
            "SAT-015",
            "[RepositorioPST] Cargo: Coordinador del CIIDI / Miembro del Comité (Rol: Comité / Super Administrador). Gestionar en /configuracion-pst el catálogo de niveles académicos, trayectos y líneas de investigación.",
            "Permite crear, editar y activar/desactivar niveles académicos y líneas de investigación, impidiendo eliminar registros que posean proyectos PST asociados.",
            "Pasa",
            "Integridad referencial verificada al intentar eliminar un nivel académico con proyectos vinculados."
        ),
        # --- BLOQUE 4: ARTÍCULOS CIENTÍFICOS Y REVISTA DIGITAL EN SITIO ---
        (
            "SAT-016",
            "[Artículos] Cargo: Miembro del Comité Editorial CIIDI (Rol: Comité / Super Administrador). Publicar un artículo científico en /nuevo-articulo cargando resumen, volumen, número, ISSN, editorial, portada y PDF (o enlace externo).",
            "El artículo se publica de forma atómica en la revista digital, validando el formato de la portada y bloqueando URLs internas no autorizadas (filtro Anti-SSRF).",
            "Pasa",
            "Publicación editorial completada satisfactoriamente por el Comité Editorial."
        ),
        (
            "SAT-017",
            "[Artículos] Cargo: Miembro del Comité Editorial y Docente Autor (Roles: Comité / Docentes). Vincular investigadores al artículo registrando su código ORCID (0000-000X-XXXX-XXXX) y consultar su ficha pública en /perfil-autor.",
            "Valida el formato estándar y la unicidad del código ORCID en la tabla investigadores, asocia los coautores al artículo y despliega su biografía y producción en /perfil-autor.",
            "Pasa",
            "Trazabilidad de autoría científica y validación de código ORCID único verificadas en sitio."
        ),
        (
            "SAT-018",
            "[Artículos] Cargo: Miembro del Comité Editorial CIIDI (Rol: Comité). Acceder a /gestor-catalogos y crear/editar registros en los catálogos de Categorías, Etiquetas y Editoriales.",
            "Actualiza los catálogos paginados, regenera automáticamente la caché JSON de categorías/etiquetas y refleja los cambios en el portal público de la revista.",
            "Pasa",
            "Gestión dinámica de catálogos editoriales rápida y sincronizada con la caché."
        ),
        (
            "SAT-019",
            "[Artículos] Cargo: Miembro del Comité Editorial (Rol: Comité) y Estudiante (Rol: Estudiantes). Cambiar el estado de un artículo de 'Activo' a 'Oculto' en /gestor-articulos y verificar el catálogo público desde la cuenta de estudiante.",
            "El artículo oculto deja de mostrarse inmediatamente en la vitrina pública (/articulos) para los estudiantes, permaneciendo editable en el gestor interno del Comité.",
            "Pasa",
            "Control editorial de visibilidad Activo/Oculto validado entre ambas estaciones de trabajo."
        ),
        (
            "SAT-020",
            "[Artículos] Cargo: Estudiante Tesista y Docente Investigador (Roles: Estudiantes / Docentes). Filtrar artículos en /articulos combinando palabra clave, nombre u ORCID del autor, año, categoría y etiqueta, y abrir /leer-articulo.",
            "Muestra los artículos coincidentes paginados sin errores, permite la lectura íntegra del resumen y metadatos, y habilita la consulta o descarga del PDF.",
            "Pasa",
            "Búsqueda multicriterio de la Revista Digital aprobada satisfactoriamente por los usuarios."
        ),
        # --- BLOQUE 5: PANEL SUPERADMIN, AUDITORÍA Y RESPALDOS EN SITIO ---
        (
            "SAT-021",
            "[SuperAdmin] Cargo: Coordinador de Soporte Técnico / Administrador General (Rol: Super Administrador). Revisar el panel /sudoadmin y ejecutar la herramienta de diagnóstico en vivo 'Testear Core'.",
            "Muestra estadísticas en tiempo real, métricas de las 43 tablas de PostgreSQL, uso de disco/memoria y confirma el estado SALUDABLE (100%) de los componentes del núcleo.",
            "Pasa",
            "Monitoreo de telemetría del servidor y latencia de base de datos verificados en sitio."
        ),
        (
            "SAT-022",
            "[SuperAdmin] Cargo: Coordinador del CIIDI / Administrador General (Rol: Super Administrador). Administrar usuarios en /gestor-usuarios, ajustar permisos en la Matriz RBAC, revocar una sesión activa e intentar eliminar su propia cuenta.",
            "Aplica en caliente los cambios de permisos RBAC, expulsa al usuario cuya sesión fue revocada y bloquea por seguridad cualquier intento de autoeliminación del propio SuperAdmin.",
            "Pasa",
            "Control granular de la Matriz RBAC y candado anti-autoeliminación comprobados."
        ),
        (
            "SAT-023",
            "[SuperAdmin] Cargo: Administrador de Base de Datos / Soporte Técnico (Rol: Super Administrador). Generar un respaldo completo (.sql.gz) en /gestor-mantenimiento, ejecutar la verificación de integridad (Dry-Run) y optimizar la BD.",
            "Crea el archivo comprimido en storage/backups/, certifica su validez sintáctica GZIP/DDL antes de cualquier restauración y ejecuta VACUUM ANALYZE en PostgreSQL.",
            "Pasa",
            "Respaldo físico .sql.gz verificado en disco y validación preventiva Dry-Run aprobada."
        ),
        (
            "SAT-024",
            "[SuperAdmin] Cargo: Auditor Interno / Coordinador del CIIDI (Rol: Super Administrador). Inspeccionar el Visor de Logs (/visor-logs) tras las acciones del día y ejecutar la verificación de la cadena criptográfica de auditoría.",
            "Lista cronológicamente quién, desde qué IP, fecha/hora y módulo realizó cada operación, confirmando la integridad del 100% de los bloques HMAC-SHA256 y triggers de BD.",
            "Pasa",
            "Trazabilidad forense e inmutabilidad de registros certificadas sin bloques corruptos."
        ),
        (
            "SAT-025",
            "[SuperAdmin] Cargo: Coordinador del CIIDI (Rol: Super Administrador). Actualizar parámetros globales en /configuracion-sistema, probar el apagado/encendido de un módulo en /gestor-modulos y exportar reportes en PDF, CSV y JSON.",
            "Guarda las credenciales SMTP cifradas con AES-256-CBC, conmuta el estado operativo de los módulos y genera los reportes institucionales neutralizando cualquier fórmula maliciosa en CSV.",
            "Pasa",
            "Exportación de reportes estadísticos (PDF/CSV/JSON) y gestión modular aprobadas."
        )
    ]

    tabla_sat = doc.add_table(rows=len(datos_sat), cols=5)
    anchos_sat = [Cm(2.1), Cm(5.0), Cm(4.3), Cm(2.4), Cm(3.2)]
    poblar_tabla_arial12(tabla_sat, datos_sat, anchos_sat, centrar_columnas=(0, 3))

    # =========================================================================
    # 6. ACTA DE CONFORMIDAD Y FIRMAS EN SITIO POR CARGO / ROL
    # =========================================================================
    p_sec5 = doc.add_paragraph()
    formato_arial12_simple(p_sec5, alineacion=WD_ALIGN_PARAGRAPH.LEFT, espacio_antes=10, espacio_despues=4)
    agregar_texto(p_sec5, "5. Acta de Conformidad de Aceptación en Sitio (Representantes por Cargo y Rol)", negrita=True)

    p_cierre = doc.add_paragraph()
    formato_arial12_simple(p_cierre, alineacion=WD_ALIGN_PARAGRAPH.JUSTIFY, espacio_despues=6)
    agregar_texto(
        p_cierre,
        "Habiéndose ejecutado en las instalaciones de la Universidad Politécnica Territorial de Trujillo \"Mario Briceño "
        "Iragorry\" la totalidad de los veinticinco (25) casos de prueba de Aceptación en Sitio (SAT-001 a SAT-025), los "
        "representantes de cada cargo institucional y rol operativo certifican que el Sistema Integrado de Gestión Científica "
        "y Tecnológica (CIIDI) cumple satisfactoriamente con los requerimientos funcionales, de seguridad y de usabilidad:"
    )

    datos_firmas = [
        ("Rol en el Sistema", "Cargo Institucional Evaluador", "Nombre y Apellido / C.I.", "Conformidad y Firma"),
        ("Super Administrador\n(Nivel 0)", "Coordinador(a) de Soporte Técnico / Administrador(a) de Servidores", "", ""),
        ("Comité\n(Nivel 1)", "Coordinador(a) del CIIDI / Miembro del Comité Editorial PNFI", "", ""),
        ("Docentes\n(Nivel 2)", "Docente Investigador(a) / Tutor(a) Académico del PNFI", "", ""),
        ("Estudiantes\n(Nivel 5)", "Vocero(a) de Estudiantes Tesistas / Proyectistas del PNFI", "", "")
    ]

    tabla_firmas = doc.add_table(rows=len(datos_firmas), cols=4)
    anchos_firmas = [Cm(3.5), Cm(5.5), Cm(4.5), Cm(3.5)]
    poblar_tabla_arial12(tabla_firmas, datos_firmas, anchos_firmas, centrar_columnas=(0,))

    nombre_salida = "Rubrica_Pruebas_SAT_CIIDI.docx"
    doc.save(nombre_salida)
    print(f"Documento SAT generado exitosamente: {nombre_salida}")


if __name__ == "__main__":
    construir_rubrica_sat()
