# -*- coding: utf-8 -*-
"""
Script: generar_guia_backend.py
Propósito: Generar la "Guía de Backend del Sistema de Repositorio CIIDI" (La Biblia del Sistema).
Formato estricto solicitado por el usuario:
- Portada simple únicamente con el título: "Guía de Backend del Sistema de Repositorio CIIDI"
- Índice general después de la portada
- Desarrollo extenso, riguroso y con redacción humana, directa y clara de programador a programador
- Fuente única en TODO el documento: Arial 12 pt
- Color único en TODO el documento: Negro puro (#000000), sin letras de colores
- Formato APA: márgenes de 2.54 cm en los 4 lados, interlineado doble (2.0), sangría de 1.27 cm en primera línea
- Encabezado (Header): completamente vacío
- Pie de página (Footer): únicamente el número de la página en Arial 12 negro
- Incluye los 8 diagramas de flujo hechos en Mermaid (incrustados como imagen y explicados)
- Incluye las indicaciones exactas de línea de inicio a línea de fin para todas las capturas del Core
"""

import os
from docx import Document
from docx.shared import Pt, Cm, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.oxml import parse_xml
from docx.oxml.ns import nsdecls

NEGRO = RGBColor(0, 0, 0)
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
DIAGRAMAS_DIR = os.path.join(BASE_DIR, "docs", "diagramas_mermaid")
OUTPUT_DOCX = os.path.join(BASE_DIR, "docs", "Guia_Backend_Sistema_Repositorio_CIIDI.docx")


def configurar_fuente_run(run, bold=False, italic=False, underline=False):
    """Fuerza Arial 12 pt color negro puro en cualquier run de texto."""
    run.font.name = "Arial"
    run.font.size = Pt(12)
    run.font.bold = bold
    run.font.italic = italic
    run.font.underline = underline
    run.font.color.rgb = NEGRO


def agregar_numero_pagina_limpio(run):
    """Inserta únicamente el campo dinámico PAGE de Word sin texto extra."""
    fld1 = parse_xml(r'<w:fldChar %s w:fldCharType="begin"/>' % nsdecls('w'))
    instr = parse_xml(r'<w:instrText %s xml:space="preserve"> PAGE </w:instrText>' % nsdecls('w'))
    fld2 = parse_xml(r'<w:fldChar %s w:fldCharType="separate"/>' % nsdecls('w'))
    fld3 = parse_xml(r'<w:fldChar %s w:fldCharType="end"/>' % nsdecls('w'))
    run._r.append(fld1)
    run._r.append(instr)
    run._r.append(fld2)
    run._r.append(fld3)


def p_apa(doc, texto, sangria=True, alineacion=WD_ALIGN_PARAGRAPH.LEFT, bold=False, italic=False):
    """Agrega un párrafo con formato APA estricto: Arial 12, negro, interlineado doble (2.0), sangría 1.27 cm."""
    p = doc.add_paragraph()
    p.alignment = alineacion
    p.paragraph_format.line_spacing = 2.0
    p.paragraph_format.space_before = Pt(0)
    p.paragraph_format.space_after = Pt(0)
    p.paragraph_format.first_line_indent = Cm(1.27) if sangria else Cm(0)
    r = p.add_run(texto)
    configurar_fuente_run(r, bold=bold, italic=italic)
    return p


def p_mixto(doc, partes, sangria=True, alineacion=WD_ALIGN_PARAGRAPH.LEFT):
    """
    Agrega un párrafo APA donde 'partes' es una lista de tuplas:
    (texto, bold, italic) o simplemente strings.
    Todo en Arial 12 negro.
    """
    p = doc.add_paragraph()
    p.alignment = alineacion
    p.paragraph_format.line_spacing = 2.0
    p.paragraph_format.space_before = Pt(0)
    p.paragraph_format.space_after = Pt(0)
    p.paragraph_format.first_line_indent = Cm(1.27) if sangria else Cm(0)
    for item in partes:
        if isinstance(item, str):
            r = p.add_run(item)
            configurar_fuente_run(r, bold=False, italic=False)
        else:
            txt = item[0]
            b = item[1] if len(item) > 1 else False
            it = item[2] if len(item) > 2 else False
            r = p.add_run(txt)
            configurar_fuente_run(r, bold=b, italic=it)
    return p


def titulo_nivel_1(doc, texto):
    """Título Nivel 1 APA: Centrado, Negrita, Arial 12, Negro, interlineado doble."""
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p.paragraph_format.line_spacing = 2.0
    p.paragraph_format.space_before = Pt(12)
    p.paragraph_format.space_after = Pt(0)
    p.paragraph_format.first_line_indent = Cm(0)
    p.paragraph_format.keep_with_next = True
    r = p.add_run(texto)
    configurar_fuente_run(r, bold=True)
    return p


def titulo_nivel_2(doc, texto):
    """Título Nivel 2 APA: Alineado a la izquierda, Negrita, Arial 12, Negro, interlineado doble."""
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p.paragraph_format.line_spacing = 2.0
    p.paragraph_format.space_before = Pt(6)
    p.paragraph_format.space_after = Pt(0)
    p.paragraph_format.first_line_indent = Cm(0)
    p.paragraph_format.keep_with_next = True
    r = p.add_run(texto)
    configurar_fuente_run(r, bold=True)
    return p


def titulo_nivel_3(doc, texto):
    """Título Nivel 3 APA: Alineado a la izquierda, Negrita Cursiva, Arial 12, Negro, interlineado doble."""
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p.paragraph_format.line_spacing = 2.0
    p.paragraph_format.space_before = Pt(0)
    p.paragraph_format.space_after = Pt(0)
    p.paragraph_format.first_line_indent = Cm(0)
    p.paragraph_format.keep_with_next = True
    r = p.add_run(texto)
    configurar_fuente_run(r, bold=True, italic=True)
    return p


def item_lista(doc, titulo_item, descripcion):
    """Elemento de lista con sangría izquierda de 1.27 cm en Arial 12 negro e interlineado 2.0."""
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p.paragraph_format.line_spacing = 2.0
    p.paragraph_format.space_before = Pt(0)
    p.paragraph_format.space_after = Pt(0)
    p.paragraph_format.left_indent = Cm(1.27)
    p.paragraph_format.first_line_indent = Cm(0)
    r1 = p.add_run(f"• {titulo_item}: ")
    configurar_fuente_run(r1, bold=True)
    r2 = p.add_run(descripcion)
    configurar_fuente_run(r2, bold=False)
    return p


def indicador_captura(doc, archivo, linea_inicio, linea_fin, descripcion_breve=""):
    """
    Inserta la línea explícita que le indica al usuario de qué línea a qué línea
    tomar la captura de pantalla del archivo correspondiente.
    """
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p.paragraph_format.line_spacing = 2.0
    p.paragraph_format.space_before = Pt(6)
    p.paragraph_format.space_after = Pt(6)
    p.paragraph_format.first_line_indent = Cm(0)
    texto_ind = f"[TOMAR CAPTURA DE PANTALLA DEL ARCHIVO: {archivo} — DESDE LA LÍNEA {linea_inicio} HASTA LA LÍNEA {linea_fin}]"
    if descripcion_breve:
        texto_ind += f" ({descripcion_breve})"
    r = p.add_run(texto_ind)
    configurar_fuente_run(r, bold=True)
    return p


def bloque_codigo(doc, lineas_codigo):
    """
    Inserta un bloque de código manteniendo estrictamente la regla de Arial 12 negro
    (sin cambiar de fuente a Courier ni usar colores), con sangría izquierda para distinguirlo limpiamente.
    """
    for linea in lineas_codigo.strip("\n").split("\n"):
        p = doc.add_paragraph()
        p.alignment = WD_ALIGN_PARAGRAPH.LEFT
        p.paragraph_format.line_spacing = 1.5
        p.paragraph_format.space_before = Pt(0)
        p.paragraph_format.space_after = Pt(0)
        p.paragraph_format.left_indent = Cm(1.27)
        p.paragraph_format.first_line_indent = Cm(0)
        r = p.add_run(linea if linea else " ")
        configurar_fuente_run(r, bold=False)


def insertar_diagrama_mermaid(doc, numero_figura, titulo_figura, nombre_archivo_png, codigo_mermaid_respaldo):
    """
    Inserta el diagrama de flujo hecho en Mermaid como imagen centrada (estilo APA para figuras)
    en Arial 12 negro.
    """
    p_num = doc.add_paragraph()
    p_num.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p_num.paragraph_format.line_spacing = 2.0
    p_num.paragraph_format.space_before = Pt(6)
    p_num.paragraph_format.space_after = Pt(0)
    p_num.paragraph_format.first_line_indent = Cm(0)
    p_num.paragraph_format.keep_with_next = True
    r_num = p_num.add_run(f"Figura {numero_figura}")
    configurar_fuente_run(r_num, bold=True)

    p_tit = doc.add_paragraph()
    p_tit.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p_tit.paragraph_format.line_spacing = 2.0
    p_tit.paragraph_format.space_before = Pt(0)
    p_tit.paragraph_format.space_after = Pt(6)
    p_tit.paragraph_format.first_line_indent = Cm(0)
    p_tit.paragraph_format.keep_with_next = True
    r_tit = p_tit.add_run(titulo_figura)
    configurar_fuente_run(r_tit, bold=False, italic=True)

    ruta_img = os.path.join(DIAGRAMAS_DIR, nombre_archivo_png)
    if os.path.exists(ruta_img):
        p_img = doc.add_paragraph()
        p_img.alignment = WD_ALIGN_PARAGRAPH.CENTER
        p_img.paragraph_format.space_before = Pt(4)
        p_img.paragraph_format.space_after = Pt(6)
        p_img.paragraph_format.first_line_indent = Cm(0)
        run_img = p_img.add_run()
        run_img.add_picture(ruta_img, width=Cm(15.5))
    else:
        p_alt = p_apa(doc, f"[Diagrama Mermaid {numero_figura}: {titulo_figura}]", sangria=False, bold=True)


def crear_tabla_apa(doc, encabezados, filas):
    """
    Crea una tabla limpia estilo APA (solo bordes horizontales negros finos en cabecera y final,
    sin colores de fondo y todo el texto en Arial 12 negro).
    """
    table = doc.add_table(rows=1 + len(filas), cols=len(encabezados))
    table.alignment = WD_TABLE_ALIGNMENT.CENTER

    tblPr = table._tbl.tblPr
    borders = parse_xml(
        f'<w:tblBorders {nsdecls("w")}>'
        f'<w:top w:val="single" w:sz="8" w:space="0" w:color="000000"/>'
        f'<w:bottom w:val="single" w:sz="8" w:space="0" w:color="000000"/>'
        f'<w:insideH w:val="single" w:sz="4" w:space="0" w:color="000000"/>'
        f'<w:insideV w:val="none"/>'
        f'<w:left w:val="none"/>'
        f'<w:right w:val="none"/>'
        f'</w:tblBorders>'
    )
    tblPr.append(borders)

    hdr_cells = table.rows[0].cells
    for idx, texto_h in enumerate(encabezados):
        p = hdr_cells[idx].paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.LEFT
        p.paragraph_format.line_spacing = 1.5
        p.paragraph_format.space_before = Pt(2)
        p.paragraph_format.space_after = Pt(2)
        p.paragraph_format.first_line_indent = Cm(0)
        r = p.add_run(texto_h)
        configurar_fuente_run(r, bold=True)

    for r_idx, fila in enumerate(filas):
        row_cells = table.rows[r_idx + 1].cells
        for c_idx, valor in enumerate(fila):
            p = row_cells[c_idx].paragraphs[0]
            p.alignment = WD_ALIGN_PARAGRAPH.LEFT
            p.paragraph_format.line_spacing = 1.5
            p.paragraph_format.space_before = Pt(2)
            p.paragraph_format.space_after = Pt(2)
            p.paragraph_format.first_line_indent = Cm(0)
            r = p.add_run(str(valor))
            configurar_fuente_run(r, bold=False)

    p_esp = doc.add_paragraph()
    p_esp.paragraph_format.line_spacing = 2.0
    p_esp.paragraph_format.space_before = Pt(0)
    p_esp.paragraph_format.space_after = Pt(0)
    return table


def insertar_imagen_proporcional(doc, ruta_img, max_w_cm=15.5, max_h_cm=17.0):
    """Inserta una imagen ajustando ancho o alto para que nunca desborde la página APA."""
    from PIL import Image
    if not os.path.exists(ruta_img):
        return
    with Image.open(ruta_img) as im:
        w_px, h_px = im.size
    ratio = h_px / float(w_px)
    ancho_cm = max_w_cm
    alto_cm = ancho_cm * ratio
    if alto_cm > max_h_cm:
        alto_cm = max_h_cm
        ancho_cm = alto_cm / ratio
    p_img = doc.add_paragraph()
    p_img.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_img.paragraph_format.space_before = Pt(4)
    p_img.paragraph_format.space_after = Pt(6)
    p_img.paragraph_format.first_line_indent = Cm(0)
    run_img = p_img.add_run()
    run_img.add_picture(ruta_img, width=Cm(ancho_cm))


def agregar_portada_e_indice(doc):
    """
    Construye la portada minimalista solicitada por el usuario (solo el título)
    y el Índice General en Arial 12 negro con formato APA.
    """
    # =========================================================================
    # PORTADA: ÚNICAMENTE EL NOMBRE "guia de backend del sitema de repositorio ciidi"
    # =========================================================================
    p_portada = doc.add_paragraph()
    p_portada.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_portada.paragraph_format.line_spacing = 2.0
    p_portada.paragraph_format.space_before = Pt(240)
    p_portada.paragraph_format.space_after = Pt(0)
    p_portada.paragraph_format.first_line_indent = Cm(0)
    r_portada = p_portada.add_run("guia de backend del sitema de repositorio ciidi")
    configurar_fuente_run(r_portada, bold=True)

    doc.add_page_break()

    # =========================================================================
    # ÍNDICE GENERAL
    # =========================================================================
    titulo_nivel_1(doc, "Índice General")

    entradas_indice = [
        ("Parte 1. Visión General y Mapa Mental del Sistema", True),
        ("    1.1. ¿Qué es realmente el backend de CIIDI y cómo pensarlo como programador?", False),
        ("    1.2. Mapa de carpetas del proyecto: qué hace cada una y qué nunca debes tocar", False),
        ("    1.3. El viaje completo de una petición HTTP (de principio a fin)", False),
        ("Parte 2. La Arquitectura Dual del Core: Microkernel y MVC", True),
        ("    2.1. La arquitectura Microkernel (sistema de módulos tipo plugin)", False),
        ("    2.2. El contrato ModuleContract como enchufe universal", False),
        ("    2.3. La arquitectura MVC dentro de cada módulo (Controlador, Modelo, Vista y Servicios)", False),
        ("    2.4. Los tres tipos de rutas que programarás: Vista, Acción POST y API AJAX/JSON", False),
        ("Parte 3. Explicación Detallada de los Archivos del Core (Guía de Capturas por Líneas)", True),
        ("    3.1. Reescritura de URL y Punto de Entrada: public/.htaccess y public/index.php", False),
        ("    3.2. El Corazón del Sistema: core/System/Kernel.php", False),
        ("    3.3. El Contrato Obligatorio: core/Interfaces/ModuleContract.php", False),
        ("    3.4. El Cargador Dinámico de Módulos: core/System/ModuleLoader.php", False),
        ("    3.5. El Enrutador del Sistema: core/Http/Router.php", False),
        ("    3.6. El Despachador MVC y Manejo de Errores: core/Http/Dispatcher.php", False),
        ("    3.7. El Gestor de Recursos Estáticos y Vistas Globales: core/System/AssetResolver.php", False),
        ("    3.8. Las Plantillas Maestras del Core: master.php, header.php, sidebar.php y home_bienvenida.php", False),
        ("    3.9. Capa de Base de Datos: core/Database/Connection.php y core/Database/QueryBuilder.php", False),
        ("    3.10. Seguridad, Permisos, Auditoría y Variables: Auth.php, CSRF.php, AuditLogger.php, FeatureFlagMiddleware.php y Env.php", False),
        ("Parte 4. Guía Práctica Paso a Paso: Cómo Crear y Añadir un Nuevo Módulo desde Cero", True),
        ("    4.1. Paso 1: Crear la estructura de carpetas del nuevo módulo", False),
        ("    4.2. Paso 2: Crear el archivo index.php e implementar ModuleContract", False),
        ("    4.3. Paso 3: Crear la tabla en PostgreSQL y programar el Modelo en models/", False),
        ("    4.4. Paso 4: Programar el Controlador en controllers/ (Vistas, POST y JSON)", False),
        ("    4.5. Paso 5: Crear las Vistas en views/ y los archivos CSS y JS en assets/", False),
        ("    4.6. Paso 6: Verificación automática, permisos RBAC y ajustes en SuperAdmin", False),
        ("Parte 5. Cómo Modificar o Extender un Módulo Existente sin Romper el Sistema", True),
        ("    5.1. Cómo añadir una nueva pantalla (vista) a un módulo existente", False),
        ("    5.2. Cómo añadir un nuevo formulario POST o acción de guardado/eliminación", False),
        ("    5.3. Cómo añadir un endpoint AJAX (JSON) para comunicarte con JavaScript", False),
        ("    5.4. Cómo agregar un subítem al menú lateral y configurar sus activadores", False),
        ("    5.5. Cómo manejar configuraciones JSON propias y subida de archivos o imágenes", False),
        ("Parte 6. Solución de Problemas Frecuentes (Guía Rápida de Diagnóstico para el Programador)", True),
        ("    6.1. Problemas con rutas, errores 404 y módulos que no aparecen", False),
        ("    6.2. Problemas con vistas, estilos CSS, scripts JS y respuestas AJAX", False),
        ("    6.3. Problemas con base de datos, QueryBuilder, permisos 403 y tokens CSRF", False),
    ]

    for texto_idx, es_parte in entradas_indice:
        p = doc.add_paragraph()
        p.alignment = WD_ALIGN_PARAGRAPH.LEFT
        p.paragraph_format.line_spacing = 2.0
        p.paragraph_format.space_before = Pt(0)
        p.paragraph_format.space_after = Pt(0)
        p.paragraph_format.first_line_indent = Cm(0)
        r = p.add_run(texto_idx)
        configurar_fuente_run(r, bold=es_parte)

    doc.add_page_break()


def agregar_parte_1_y_2(doc):
    """Construye la Parte 1 (Visión General) y la Parte 2 (Arquitecturas Microkernel y MVC)."""
    # =========================================================================
    # PARTE 1: VISIÓN GENERAL Y MAPA MENTAL DEL SISTEMA
    # =========================================================================
    titulo_nivel_1(doc, "Parte 1. Visión General y Mapa Mental del Sistema")

    titulo_nivel_2(doc, "1.1. ¿Qué es realmente el backend de CIIDI y cómo pensarlo como programador?")
    p_apa(doc, (
        "Cuando entras por primera vez al código del Sistema de Repositorio CIIDI, la forma más sencilla y exacta de entenderlo "
        "es imaginar una consola de videojuegos o una regleta eléctrica inteligente. En un sistema web tradicional hecho a la antigua, "
        "todas las pantallas, todas las rutas y todos los menús están mezclados dentro de los mismos archivos centrales; si quieres "
        "agregar una sección nueva, tienes que abrir cinco archivos del sistema principal, meter mano en el menú general y rezar para no "
        "romper el trabajo de otro programador. En CIIDI eso no ocurre porque el sistema fue construido bajo una regla de oro: "
        "separar por completo el motor central (llamado Core) de las funcionalidades de negocio (llamadas Módulos)."
    ))
    p_apa(doc, (
        "El Core (ubicado en la carpeta core/) es un motor ciego pero muy bien organizado. Decimos que es ciego porque el Core "
        "no sabe qué es un artículo científico, no sabe qué es un curso, no sabe qué es una línea de investigación ni qué es un Proyecto "
        "Socio-Tecnológico (PST). Lo único que el Core sabe hacer es recibir una petición web, verificar que el visitante no sea un atacante, "
        "preguntarle a los módulos instalados qué rutas y qué botones de menú ofrece cada uno, ejecutar el controlador que corresponda y "
        "dibujar la plantilla visual maestra alrededor del resultado."
    ))
    p_apa(doc, (
        "Por su parte, cada Módulo (ubicado dentro de su propia subcarpeta en modules/) es una mini-aplicación completa y autónoma. "
        "Un módulo trae adentro sus propios controladores, sus propios modelos de base de datos, sus propias vistas HTML/PHP, sus propias hojas "
        "de estilo CSS, sus propios scripts JavaScript y un archivo de presentación llamado index.php. Gracias a este diseño, cuando tú como "
        "programador necesitas crear un módulo nuevo o modificar uno existente, el 99% de tu trabajo ocurre únicamente dentro de la carpeta "
        "modules/TuModulo/, sin necesidad de modificar ni una sola línea del Core."
    ))

    titulo_nivel_2(doc, "1.2. Mapa de carpetas del proyecto: qué hace cada una y qué nunca debes tocar")
    p_apa(doc, (
        "Antes de leer una sola línea de código PHP, necesitas ubicarte en las carpetas principales que componen la raíz del proyecto "
        "(C:/wamp64/www/proyect_CIIDI/). Cada directorio tiene una responsabilidad estricta:"
    ))
    item_lista(doc, "public/", (
        "Es la única puerta de entrada pública al servidor web. Aquí viven el archivo .htaccess que atrapa las URLs amigables, "
        "el archivo index.php que enciende todo el sistema, los recursos gráficos globales (public/assets/) y las carpetas públicas "
        "donde se guardan portadas o imágenes subidas por los usuarios (public/uploads/)."
    ))
    item_lista(doc, "core/", (
        "Es el corazón intocable del framework propio de CIIDI. Contiene la lógica del Microkernel (core/System/), el enrutador y "
        "despachador HTTP (core/Http/), la conexión a PostgreSQL y el constructor de consultas (core/Database/), los escudos de seguridad, "
        "sesiones, permisos RBAC y auditoría (core/Security/), el instalador autónomo (core/Installer/), el contrato de módulos "
        "(core/Interfaces/) y las vistas maestras que arman el encabezado, menú lateral y pie de página (core/Views/). Como regla general, "
        "cuando desarrollas un módulo nuevo, no debes modificar los archivos de core/, sino consumir sus clases y herramientas."
    ))
    item_lista(doc, "modules/", (
        "Es tu área de trabajo principal como programador. Cada carpeta aquí adentro (Articulos, Autenticacion, Cursos, Investigaciones, "
        "LineasInvestigacion, RepositorioPST, SuperAdmin, VinculacionEmpresarial) es un módulo independiente que se conecta automáticamente "
        "al sistema al arrancar."
    ))
    item_lista(doc, "storage/", (
        "Es la memoria en disco del sistema. Aquí se guardan archivos JSON de configuración en tiempo real (como config_system.json que "
        "indica qué módulos y rutas están encendidos o apagados, maintenance.json para las ventanas de mantenimiento, db_config.json para "
        "las credenciales de base de datos y system_config.json), el archivo candado installed.lock que confirma que el sistema ya fue "
        "instalado, y la carpeta storage/logs/ donde se escriben los registros de errores y respaldos de auditoría."
    ))
    item_lista(doc, "vendor/", (
        "Almacena las librerías externas instaladas mediante Composer. Se carga automáticamente al inicio de cada petición."
    ))

    p_apa(doc, (
        "Para visualizar cómo se relacionan estas cuatro grandes capas entre sí, observa la Figura 1, donde se muestra el mapa general "
        "del ecosistema CIIDI y cómo los módulos se conectan al Core y a la base de datos PostgreSQL."
    ))

    p_num = doc.add_paragraph()
    p_num.paragraph_format.line_spacing = 2.0
    p_num.paragraph_format.space_before = Pt(6)
    p_num.paragraph_format.space_after = Pt(0)
    r_n = p_num.add_run("Figura 1")
    configurar_fuente_run(r_n, bold=True)
    p_t = doc.add_paragraph()
    p_t.paragraph_format.line_spacing = 2.0
    p_t.paragraph_format.space_before = Pt(0)
    p_t.paragraph_format.space_after = Pt(4)
    r_t = p_t.add_run("Mapa general de capas del ecosistema CIIDI (Separación entre Entrada, Core, Módulos y Persistencia)")
    configurar_fuente_run(r_t, italic=True)
    insertar_imagen_proporcional(doc, os.path.join(DIAGRAMAS_DIR, "diagrama_1_capas_ecosistema.png"))

    titulo_nivel_2(doc, "1.3. El viaje completo de una petición HTTP (de principio a fin)")
    p_apa(doc, (
        "Para entender cómo se comunica todo a nivel de backend, sigamos el recorrido exacto que hace el sistema cuando un usuario "
        "escribe en su navegador una dirección como http://localhost/proyect_CIIDI/articulos o hace clic en cualquier opción del menú lateral:"
    ))
    item_lista(doc, "Paso 1 (Intercepción de la URL en Apache)", (
        "El servidor Apache recibe la petición. El archivo .htaccess verifica si el navegador está pidiendo un archivo físico real "
        "(como una imagen .webp o un archivo .css). Como 'articulos' no es un archivo físico, .htaccess reescribe internamente la petición "
        "y la envía a public/index.php?ruta=articulos."
    ))
    item_lista(doc, "Paso 2 (Preparación del entorno en public/index.php)", (
        "Se ejecuta public/index.php. Este archivo define las constantes de rutas absolutas (BASE_PATH, CORE_PATH, MODULES_PATH, "
        "STORAGE_PATH), carga las variables del archivo .env mediante la clase Env, configura si los errores de PHP se muestran u ocultan "
        "según APP_DEBUG, e instancia la clase principal del sistema: $app = new Kernel(); seguido de $app->run();."
    ))
    item_lista(doc, "Paso 3 (Descubrimiento de módulos en el constructor del Kernel)", (
        "Al ejecutarse new Kernel(), el Kernel crea las instancias de sus ayudantes (SessionManager, ModuleLoader, AssetResolver, Router, "
        "Dispatcher, etc.) y revisa si existe el archivo storage/installed.lock. Si existe, llama inmediatamente a "
        "$this->moduleLoader->loadModules(). En ese instante, el ModuleLoader recorre todas las carpetas dentro de modules/, carga el "
        "archivo index.php de cada módulo activo y junta todas sus rutas en un gran diccionario llamado $rutasGlobales y todos sus botones "
        "de navegación en $menuGlobal."
    ))
    item_lista(doc, "Paso 4 (Filtros de seguridad e interceptores en Kernel::run)", (
        "Cuando se ejecuta $app->run(), antes de buscar la ruta 'articulos', el Kernel envía las cabeceras de seguridad web, inicia la "
        "sesión PHP y pasa la petición por una fila de guardias: el RateLimiter (que bloquea IPs en lista negra o ataques de inyección SQL "
        "y XSS), el InstallerHook (que redirige al instalador si el sistema no está instalado), el MaintenanceService (que muestra la "
        "pantalla 503 si hay mantenimiento activo) y el FeatureFlagMiddleware (que verifica si el SuperAdmin apagó o puso en modo solo "
        "lectura esa ruta específica)."
    ))
    item_lista(doc, "Paso 5 (Búsqueda de la ruta en el Router)", (
        "Si la petición supera todos los controles anteriores, el Kernel le pasa el nombre de la ruta ('articulos') y el diccionario "
        "$rutasGlobales al método $this->router->resolve(). El Router busca si 'articulos' existe como clave en $rutasGlobales. Al "
        "encontrarla, devuelve un paquete de información ($match) que indica qué controlador, qué método, qué vista, qué título y qué "
        "archivos CSS o JS pertenecen a esa ruta."
    ))
    item_lista(doc, "Paso 6 (Ejecución del Controlador y Modelo en el Dispatcher)", (
        "El Kernel le entrega ese $match al método $this->dispatcher->dispatch(). El Dispatcher incluye el archivo del controlador del "
        "módulo (por ejemplo, modules/Articulos/controllers/ArticulosController.php), crea el objeto con new ArticulosController() y "
        "ejecuta el método configurado (por ejemplo, index()). Adentro de ese método, el controlador verifica permisos con Auth, le pide "
        "los datos al Modelo (el cual consulta PostgreSQL usando QueryBuilder o Connection::getInstance()) y devuelve un arreglo asociativo "
        "con los datos listos para mostrar."
    ))
    item_lista(doc, "Paso 7 (Desempaquetado de variables y renderizado en master.php)", (
        "El Dispatcher toma el arreglo que devolvió el controlador y le aplica la función nativa extract($datosVista), convirtiendo cada "
        "posición del arreglo en una variable suelta lista para usarse en la vista. Luego le pide a AssetResolver las rutas de los archivos "
        "CSS y JS del módulo y finalmente incluye core/Views/master.php. Esta plantilla maestra dibuja el encabezado superior (header.php), "
        "el menú lateral (sidebar.php), mete en el centro la vista específica del módulo ($vista_modulo_path) y cierra con el pie de "
        "página (footer.php)."
    ))

    p_num2 = doc.add_paragraph()
    p_num2.paragraph_format.line_spacing = 2.0
    p_num2.paragraph_format.space_before = Pt(6)
    p_num2.paragraph_format.space_after = Pt(0)
    r_n2 = p_num2.add_run("Figura 2")
    configurar_fuente_run(r_n2, bold=True)
    p_t2 = doc.add_paragraph()
    p_t2.paragraph_format.line_spacing = 2.0
    p_t2.paragraph_format.space_before = Pt(0)
    p_t2.paragraph_format.space_after = Pt(4)
    r_t2 = p_t2.add_run("Diagrama de secuencia del viaje completo de una petición HTTP desde el navegador hasta PostgreSQL y la vista maestra")
    configurar_fuente_run(r_t2, italic=True)
    insertar_imagen_proporcional(doc, os.path.join(DIAGRAMAS_DIR, "diagrama_2_viaje_peticion_http.png"))

    # =========================================================================
    # PARTE 2: LA ARQUITECTURA DUAL DEL CORE: MICROKERNEL Y MVC
    # =========================================================================
    titulo_nivel_1(doc, "Parte 2. La Arquitectura Dual del Core: Microkernel y MVC")

    titulo_nivel_2(doc, "2.1. La arquitectura Microkernel (sistema de módulos tipo plugin)")
    p_apa(doc, (
        "El sistema CIIDI combina dos arquitecturas de software que trabajan en equipo, pero en momentos distintos de la petición: "
        "la arquitectura Microkernel (a nivel macro del sistema) y la arquitectura MVC o Modelo-Vista-Controlador (a nivel micro dentro de "
        "cada módulo). Entender dónde termina el Microkernel y dónde empieza el MVC es la clave para dominar el backend."
    ))
    p_apa(doc, (
        "¿Qué es exactamente la arquitectura Microkernel en CIIDI? Consiste en reducir el núcleo del sistema a las funciones mínimas "
        "indispensables (carga de módulos, enrutamiento, seguridad transversal, conexión a base de datos y plantilla visual base) y "
        "delegar toda la lógica de negocio a módulos independientes que se conectan como plugins. En CIIDI, el Microkernel está formado "
        "principalmente por la dupla entre Kernel.php y ModuleLoader.php, apoyados por AssetResolver.php."
    ))
    p_apa(doc, (
        "Lo fascinante de este diseño es cómo el Core descubre los módulos sin que nadie tenga que registrarlos manualmente en una lista "
        "fija del código. Cada vez que entra una petición, ModuleLoader utiliza la función scandir() de PHP para mirar qué carpetas existen "
        "adentro de modules/. Si tú creas hoy una carpeta llamada modules/Laboratorios/ y le pones adentro un archivo index.php válido, en la "
        "siguiente recarga del navegador el Microkernel ya sabe que ese módulo existe, lee sus rutas, agrega su botón en el menú lateral "
        "izquierdo, coloca su tarjeta de presentación en la pantalla de bienvenida y lo muestra en el panel de control del SuperAdmin para "
        "que pueda encenderse o apagarse en caliente."
    ))
    p_apa(doc, (
        "Además, el Microkernel respeta el estado operativo de cada módulo guardado en el archivo storage/config_system.json. Si en ese "
        "archivo un módulo tiene el estado 'offline' (porque el administrador lo desactivó desde el gestor de módulos), el ModuleLoader "
        "simplemente no agrega las rutas de ese módulo al arreglo $rutasGlobales, haciendo que el módulo entero quede apagado al instante "
        "sin borrar ni un solo archivo. La única excepción a esta regla son los dos módulos vitales del sistema: Autenticacion y SuperAdmin, "
        "los cuales están marcados en el código como módulos Core ($esCore = true) para impedir que un administrador se deje a sí mismo "
        "fuera del sistema por accidente."
    ))

    p_num3 = doc.add_paragraph()
    p_num3.paragraph_format.line_spacing = 2.0
    p_num3.paragraph_format.space_before = Pt(6)
    p_num3.paragraph_format.space_after = Pt(0)
    r_n3 = p_num3.add_run("Figura 3")
    configurar_fuente_run(r_n3, bold=True)
    p_t3 = doc.add_paragraph()
    p_t3.paragraph_format.line_spacing = 2.0
    p_t3.paragraph_format.space_before = Pt(0)
    p_t3.paragraph_format.space_after = Pt(4)
    r_t3 = p_t3.add_run("Flujo interno de la arquitectura Microkernel durante el escaneo y registro de módulos en ModuleLoader")
    configurar_fuente_run(r_t3, italic=True)
    insertar_imagen_proporcional(doc, os.path.join(DIAGRAMAS_DIR, "diagrama_3_microkernel_moduleloader.png"))

    titulo_nivel_2(doc, "2.2. El contrato ModuleContract como enchufe universal")
    p_apa(doc, (
        "Para que el Microkernel pueda conectarse con cualquier módulo sin conocer su interior, necesita que todos los módulos tengan "
        "exactamente la misma forma de enchufe. En programación orientada a objetos en PHP, ese enchufe estándar se logra mediante una "
        "Interfaz (interface). En CIIDI, esa interfaz se llama ModuleContract y vive en core/Interfaces/ModuleContract.php."
    ))
    p_apa(doc, (
        "Cuando el ModuleLoader abre el archivo modules/TuModulo/index.php, comprueba mediante la condición "
        "if ($modulo instanceof ModuleContract) si tu clase cumple con el contrato. Si tu clase no implementa ModuleContract o le falta "
        "alguno de los 7 métodos exigidos por la interfaz, el sistema la rechaza o PHP detiene la ejecución avisándote qué método falta. "
        "Gracias a esta regla tan simple, el Core tiene la garantía absoluta de que a cualquier módulo instalado le puede hacer siempre "
        "las mismas siete preguntas: cómo te llamas (getNombre), qué rutas manejas (getRutas), qué botones quieres en el menú lateral "
        "(getMenuConfig), de qué tratas (getDescripcion), de qué otros módulos dependes (getDependencias), qué quieres mostrar en la página "
        "de inicio (getHomeConfig) y qué controles quieres agregar en la barra superior (getHeaderConfig)."
    ))

    titulo_nivel_2(doc, "2.3. La arquitectura MVC dentro de cada módulo (Controlador, Modelo, Vista y Servicios)")
    p_apa(doc, (
        "Una vez que el Microkernel hizo su trabajo de encontrar a qué módulo pertenece la URL solicitada, entra en acción la segunda "
        "arquitectura del sistema: el patrón MVC (Modelo-Vista-Controlador). Cada módulo dentro de modules/ organiza su código interno en "
        "tres capas principales (más una capa opcional de servicios):"
    ))
    item_lista(doc, "El Controlador (carpeta controllers/)", (
        "Es el director de orquesta de la petición. Cuando el Dispatcher del Core ejecuta el método del controlador asociado a la ruta, "
        "el controlador se encarga de cuatro tareas concretas: primero, verificar que el usuario tenga permiso para estar ahí usando "
        "Auth::requierePrivilegioMinimo(); segundo, recibir y limpiar los parámetros que vienen por $_GET o $_POST (y validar el token "
        "CSRF si es un formulario); tercero, llamar a los métodos del Modelo para buscar o guardar información; y cuarto, devolver un "
        "arreglo con los datos hacia la vista o redirigir al usuario."
    ))
    item_lista(doc, "El Modelo (carpeta models/)", (
        "Es el único autorizado para hablar con la base de datos PostgreSQL. Ni las vistas ni el archivo index.php del módulo deben "
        "hacer consultas SQL. El modelo instancia el QueryBuilder del Core (new QueryBuilder()) o pide la conexión PDO activa "
        "(Connection::getInstance()) para ejecutar consultas SELECT, INSERT, UPDATE o DELETE y devolverle al controlador arreglos limpios "
        "de PHP."
    ))
    item_lista(doc, "La Vista (carpeta views/)", (
        "Es el archivo PHP que contiene la estructura HTML de la pantalla que verá el usuario. Recibe ya listas las variables que el "
        "controlador retornó (gracias al extract() que hace el Dispatcher) y se limita a recorrerlas con foreach, mostrar textos "
        "escapados con htmlspecialchars() y pintar formularios o tablas. La vista no se preocupa por abrir etiquetas <html>, <head> o "
        "<body> ni por incluir el menú lateral, porque el Dispatcher mete automáticamente tu vista adentro del contenedor "
        "<div class='main-content'> de la plantilla maestra core/Views/master.php."
    ))
    item_lista(doc, "Los Servicios (carpeta opcional services/)", (
        "Cuando un módulo tiene tareas auxiliares que no son consultas de base de datos puras pero tampoco deben ensuciar el controlador "
        "(por ejemplo, procesar y convertir una imagen subida a formato .webp, leer un archivo JSON de configuración propio del módulo o "
        "comunicarse con una red neuronal o API externa), esa lógica se separa en una clase dentro de la carpeta services/ del módulo."
    ))

    titulo_nivel_2(doc, "2.4. Los tres tipos de rutas que programarás: Vista, Acción POST y API AJAX/JSON")
    p_apa(doc, (
        "Un punto que suele confundir a quien empieza a programar en el backend de CIIDI es pensar que todas las rutas declaradas en "
        "getRutas() funcionan igual. En realidad, dependiendo de cómo configures una ruta en tu archivo index.php y de lo que haga el "
        "método de tu controlador, existen tres tipos de rutas muy bien diferenciados:"
    ))
    item_lista(doc, "Tipo 1: Ruta de Vista Completa (GET)", (
        "Es la ruta típica para mostrar una pantalla (por ejemplo, 'cursos' o 'gestor-articulos'). En getRutas() defines "
        "'controlador_path', 'controlador', 'metodo', 'vista', 'titulo' y opcionalmente 'css' y 'js'. El método de tu controlador busca "
        "los datos en el modelo y al final hace return ['variable' => $valor];. El Dispatcher toma ese arreglo, hace extract(), resuelve "
        "los CSS/JS y carga master.php para dibujar toda la interfaz completa con encabezado, menú lateral, tu vista en el centro y pie de página."
    ))
    item_lista(doc, "Tipo 2: Ruta de Acción o Procesamiento de Formulario (POST / Redirect)", (
        "Es una ruta invisible pensada para recibir el envío de un formulario (por ejemplo, 'procesar-articulo' o 'cursos-eliminar'). "
        "En getRutas() solo necesitas declarar 'controlador_path', 'controlador' y 'metodo' (sin clave 'vista'). El método del controlador "
        "valida el token CSRF, manda a guardar o borrar en el modelo, guarda un mensaje de éxito o error en $_SESSION y termina haciendo "
        "header('Location: ruta-destino'); exit;. Como el método ejecuta exit; después de enviar la cabecera de redirección, el Dispatcher "
        "se detiene ahí mismo y el navegador salta limpiamente a la pantalla de destino."
    ))
    item_lista(doc, "Tipo 3: Ruta de API o Endpoint AJAX (JSON)", (
        "Es una ruta diseñada para ser llamada desde JavaScript mediante fetch() sin recargar la página (por ejemplo, 'api-catalogos-art' "
        "o 'toggle-estado-articulo'). Al igual que en las rutas POST, en getRutas() solo declaras 'controlador_path', 'controlador' y "
        "'metodo' (sin 'vista'). En el método del controlador colocas header('Content-Type: application/json');, imprimes la respuesta con "
        "echo json_encode(['ok' => true, 'datos' => $resultado]); y obligatoriamente colocas exit; (o return false;) en la última línea. "
        "Ese exit; o return false; es vital porque evita que el Dispatcher continúe hacia abajo e imprima el HTML de master.php detrás de tu JSON."
    ))

    p_num5 = doc.add_paragraph()
    p_num5.paragraph_format.line_spacing = 2.0
    p_num5.paragraph_format.space_before = Pt(6)
    p_num5.paragraph_format.space_after = Pt(0)
    r_n5 = p_num5.add_run("Figura 4")
    configurar_fuente_run(r_n5, bold=True)
    p_t5 = doc.add_paragraph()
    p_t5.paragraph_format.line_spacing = 2.0
    p_t5.paragraph_format.space_before = Pt(0)
    p_t5.paragraph_format.space_after = Pt(4)
    r_t5 = p_t5.add_run("Los tres caminos de ejecución en el Dispatcher MVC según el tipo de ruta (Vista completa, Acción POST y API JSON)")
    configurar_fuente_run(r_t5, italic=True)
    insertar_imagen_proporcional(doc, os.path.join(DIAGRAMAS_DIR, "diagrama_5_caminos_dispatcher_mvc.png"))


def agregar_parte_3(doc):
    """
    Construye la Parte 3: Radiografía completa de los archivos más importantes del Core,
    con las líneas exactas para tomar capturas de pantalla y explicación detallada de cada bloque.
    """
    titulo_nivel_1(doc, "Parte 3. Explicación Detallada de los Archivos del Core (Guía de Capturas por Líneas)")
    p_apa(doc, (
        "En esta sección vamos a abrir y destripar uno por uno los archivos más importantes del Core. Son exactamente los archivos "
        "que necesitas comprender para saber qué ocurre por debajo cuando creas o modificas un módulo. En cada archivo encontrarás "
        "una línea indicadora entre corchetes que te dice exactamente qué archivo abrir y desde qué número de línea hasta qué número de "
        "línea debes tomar la captura de pantalla para acompañar la explicación."
    ))

    # -------------------------------------------------------------------------
    # 3.1. public/.htaccess y public/index.php
    # -------------------------------------------------------------------------
    titulo_nivel_2(doc, "3.1. Reescritura de URL y Punto de Entrada: public/.htaccess y public/index.php")
    p_apa(doc, (
        "Todo comienza cuando una petición llega a la carpeta public/. Aquí trabajan en conjunto dos archivos pequeños pero "
        "fundamentales: public/.htaccess y public/index.php."
    ))

    indicador_captura(doc, "public/.htaccess", 1, 8, "Regla de reescritura hacia index.php?ruta=$1")
    p_apa(doc, (
        "En las líneas 1 a 8 de public/.htaccess se enciende el motor de reescritura de Apache (RewriteEngine On). Las dos directivas "
        "RewriteCond (líneas 4 y 5) hacen una comprobación muy inteligente: le preguntan al servidor si la ruta solicitada NO es un "
        "archivo físico existente (!-f) y NO es una carpeta física existente (!-d). Gracias a esto, cuando el navegador pide una hoja "
        "de estilo como assets/css/style.css o una foto en uploads/, Apache la entrega directamente sin pasar por PHP. En cambio, cuando "
        "el usuario pide una ruta lógica como /articulos o /cursos-crear, entra en acción la línea 8: RewriteRule ^(.*)$ index.php?ruta=$1 [QSA,L]. "
        "Esta línea toma el texto de la URL y lo mete automáticamente dentro de la variable $_GET['ruta'], manteniendo además cualquier "
        "otro parámetro GET adicional gracias a la bandera QSA (Query String Append)."
    ))

    indicador_captura(doc, "public/index.php", 4, 11, "Definición de constantes globales de rutas del sistema")
    p_apa(doc, (
        "Una vez que Apache redirige la solicitud hacia public/index.php, las líneas 4 a 11 definen seis constantes globales que estarán "
        "disponibles en absolutamente todos tus módulos, controladores, modelos y vistas:"
    ))
    item_lista(doc, "BASE_PATH y ROOT_PATH (líneas 5 y 6)", (
        "Apuntan a la raíz del proyecto (C:/wamp64/www/proyect_CIIDI) usando dirname(__DIR__)."
    ))
    item_lista(doc, "CORE_PATH (línea 7)", (
        "Apunta directamente a la carpeta core/ con barra final incluida. La usarás siempre que necesites importar herramientas del "
        "núcleo desde tu módulo, por ejemplo: require_once CORE_PATH . 'Security/Auth.php';."
    ))
    item_lista(doc, "CORE_VIEWS (línea 8)", (
        "Apunta a core/Views/, donde residen master.php, header.php, sidebar.php, footer.php y las pantallas de error 403, 404, 500 y 503."
    ))
    item_lista(doc, "MODULES_PATH (línea 9)", (
        "Apunta a la carpeta modules/, utilizada por el ModuleLoader para escanear todos los módulos."
    ))
    item_lista(doc, "STORAGE_PATH (línea 10)", (
        "Apunta a la carpeta storage/, donde se guardan los archivos .json de configuración, bloqueos y logs."
    ))

    indicador_captura(doc, "public/index.php", 12, 31, "Carga de Composer, variables de entorno .env y escudo APP_DEBUG")
    p_apa(doc, (
        "En las líneas 12 a 31 de public/index.php el sistema prepara el entorno de ejecución. Primero (líneas 13 a 15) verifica si "
        "existe vendor/autoload.php para cargar automáticamente las librerías de Composer. Luego (líneas 18 y 19) importa core/System/Env.php "
        "y ejecuta Env::load(BASE_PATH . '/.env') para leer las variables de entorno del proyecto. Inmediatamente después (líneas 22 a 31), "
        "consulta el valor de APP_DEBUG: si estás desarrollando en local y APP_DEBUG es true, activa display_errors y error_reporting(E_ALL) "
        "para que veas cualquier error en pantalla; si el sistema está en producción y APP_DEBUG es false, oculta los errores crudos de PHP "
        "para no exponer rutas ni detalles técnicos ante los usuarios."
    ))

    indicador_captura(doc, "public/index.php", 33, 49, "Ajuste dinámico de memoria e instanciación del Kernel")
    p_apa(doc, (
        "Finalmente, entre las líneas 33 y 42 se ajustan en tiempo de ejecución los límites de memoria (memory_limit) y tiempo de "
        "ejecución (max_execution_time) leyendo la configuración documental para soportar subidas pesadas sin colapsar PHP. Y en las "
        "líneas 45 a 49 ocurre el encendido formal del motor: se importa core/System/Kernel.php, se crea el objeto $app = new Kernel(); "
        "y se invoca $app->run();."
    ))

    # -------------------------------------------------------------------------
    # 3.2. core/System/Kernel.php
    # -------------------------------------------------------------------------
    titulo_nivel_2(doc, "3.2. El Corazón del Sistema: core/System/Kernel.php")
    p_apa(doc, (
        "El archivo core/System/Kernel.php es el director general de todo el backend. Coordina la seguridad, la carga de módulos, "
        "los interceptores de estado y el despacho hacia el patrón MVC."
    ))

    indicador_captura(doc, "core/System/Kernel.php", 1, 18, "Zona horaria e importación de piezas nucleares del Core")
    p_apa(doc, (
        "En las líneas 1 a 18, el Kernel fija primero la zona horaria oficial en America/Caracas (línea 3) para que todas las marcas "
        "de tiempo de auditoría, sesiones y tareas programadas coincidan con la hora local. Luego importa con require_once todas las "
        "piezas que conforman el núcleo: ModuleContract, SessionManager, EmergencyRescueService, AuditLogger, InstallerHook, "
        "MaintenanceService, ModuleLoader, AssetResolver, RateLimiter, CSRF, FeatureFlagMiddleware, Router y Dispatcher."
    ))

    indicador_captura(doc, "core/System/Kernel.php", 19, 43, "Propiedades y constructor del Kernel con carga de módulos")
    p_apa(doc, (
        "En las líneas 19 a 43 se declaran las propiedades privadas tipadas del Kernel y su constructor __construct(). Observa que al "
        "hacerse new Kernel() (líneas 29 a 37), se instancian todos los subsistemas y en la línea 37 se le pasa $this al Dispatcher "
        "(new Dispatcher($this)) para que las vistas puedan pedirle al Kernel las tarjetas de inicio y los controles del header. "
        "Presta especial atención a las líneas 39 a 42: el Kernel busca el archivo storage/installed.lock. Solo si ese archivo existe "
        "(es decir, si el sistema ya fue instalado), ejecuta $this->moduleLoader->loadModules(). Esto evita que el sistema intente "
        "cargar módulos o consultar tablas cuando la base de datos aún no ha sido creada."
    ))

    indicador_captura(doc, "core/System/Kernel.php", 45, 94, "Inicio de run(), cabeceras OWASP, sesión y escudo WAF RateLimiter")
    p_apa(doc, (
        "En las líneas 45 a 94 arranca el método run(). Lo primero que hace (líneas 47 a 53) es enviar cabeceras HTTP de seguridad "
        "(X-Content-Type-Options: nosniff, X-Frame-Options: SAMEORIGIN, X-XSS-Protection, Referrer-Policy y Permissions-Policy). "
        "En la línea 55 inicia la sesión PHP mediante $this->sessionManager->start(). Inmediatamente después, entre las líneas 58 y 94, "
        "ejecuta dos controles de seguridad perimetral a través de RateLimiter: primero pregunta con RateLimiter::estaBloqueada() si la "
        "dirección IP del visitante está en lista negra o bloqueada por exceso de intentos fallidos (líneas 58 a 75), y segundo "
        "inspecciona todos los datos de la petición con RateLimiter::inspeccionarPayloadsSeguridad() (líneas 77 a 94) buscando patrones "
        "de inyección SQL o XSS. Si cualquiera de los dos detecta un problema, responde con código HTTP 403, muestra la vista "
        "core/Views/seguridad_bloqueo.php y detiene todo con exit."
    ))

    indicador_captura(doc, "core/System/Kernel.php", 96, 124, "Captura de ruta e interceptores de instalación, mantenimiento, feature flags y scheduler")
    p_apa(doc, (
        "En la línea 96 el Kernel lee qué ruta pidió el usuario: $ruta = $_GET['ruta'] ?? 'inicio';. Si el usuario entró a la raíz sin "
        "especificar ruta, asume por defecto 'inicio'. A continuación (líneas 98 a 124) pasa esa variable $ruta por tres interceptores "
        "y un pulso automático:"
    ))
    item_lista(doc, "1. InstallerHook (líneas 99 a 101)", (
        "Si el sistema no está instalado, intercepta la petición y muestra el asistente de instalación wizard.php."
    ))
    item_lista(doc, "2. MaintenanceService (líneas 104 a 106)", (
        "Verifica en storage/maintenance.json si hay una ventana de mantenimiento activa. Si la hay y el usuario no es administrador, "
        "muestra la pantalla 503 de mantenimiento y detiene el flujo."
    ))
    item_lista(doc, "3. FeatureFlagMiddleware (líneas 109 a 111)", (
        "Revisa en storage/config_system.json si esa ruta puntual fue puesta en estado 'offline' o 'solo_lectura' por el SuperAdmin."
    ))
    item_lista(doc, "3.5. Pulso de SchedulerService (líneas 114 a 124)", (
        "Dispara de forma silenciosa cada 60 segundos la revisión de tareas programadas (como respaldos automáticos de base de datos) "
        "sin interrumpir la navegación del usuario."
    ))

    indicador_captura(doc, "core/System/Kernel.php", 126, 160, "Enrutamiento, despacho MVC y métodos delegados para vistas")
    p_apa(doc, (
        "Las líneas 126 a 137 son la culminación del trabajo del Kernel: le pide a ModuleLoader todas las rutas compiladas "
        "($rutasGlobales = $this->moduleLoader->getRutasGlobales()), le pide al Router que busque la ruta actual "
        "($match = $this->router->resolve($ruta, $rutasGlobales)) y finalmente le entrega todo al Dispatcher mediante "
        "$this->dispatcher->dispatch(...). Por último, en las líneas 141 a 159 encontrarás cinco métodos puente "
        "(getInfoModulosAdmin, getTarjetasInicio, getControlesHeader, getGlobalCss y getHomeCss) que delegan las consultas hacia "
        "ModuleLoader y AssetResolver para que las vistas globales puedan invocarlos fácilmente."
    ))

    p_num4 = doc.add_paragraph()
    p_num4.paragraph_format.line_spacing = 2.0
    p_num4.paragraph_format.space_before = Pt(6)
    p_num4.paragraph_format.space_after = Pt(0)
    r_n4 = p_num4.add_run("Figura 5")
    configurar_fuente_run(r_n4, bold=True)
    p_t4 = doc.add_paragraph()
    p_t4.paragraph_format.line_spacing = 2.0
    p_t4.paragraph_format.space_before = Pt(0)
    p_t4.paragraph_format.space_after = Pt(4)
    r_t4 = p_t4.add_run("Tubería de seguridad e interceptores de ciclo de vida dentro de Kernel::run()")
    configurar_fuente_run(r_t4, italic=True)
    insertar_imagen_proporcional(doc, os.path.join(DIAGRAMAS_DIR, "diagrama_4_tuberia_seguridad_kernel.png"))

    # -------------------------------------------------------------------------
    # 3.3. core/Interfaces/ModuleContract.php
    # -------------------------------------------------------------------------
    titulo_nivel_2(doc, "3.3. El Contrato Obligatorio: core/Interfaces/ModuleContract.php")
    p_apa(doc, (
        "Si vas a crear un módulo nuevo, este es el archivo más importante que debes conocer de memoria. Es una interfaz de solo "
        "26 líneas, pero define las reglas de juego de todo módulo del sistema."
    ))

    indicador_captura(doc, "core/Interfaces/ModuleContract.php", 1, 26, "Interfaz completa ModuleContract con sus 7 métodos obligatorios")
    p_apa(doc, (
        "En las líneas 1 a 26 de core/Interfaces/ModuleContract.php se declaran los 7 métodos públicos que toda clase principal de "
        "módulo (ubicada en modules/NombreModulo/index.php) está obligada a implementar:"
    ))
    item_lista(doc, "getNombre(): string (línea 7)", (
        "Devuelve el nombre humano y legible de tu módulo (por ejemplo, 'Módulo de Formación y Cursos'). Se usa en el panel del SuperAdmin."
    ))
    item_lista(doc, "getRutas(): array (línea 10)", (
        "Devuelve un arreglo asociativo donde cada clave es el nombre de la URL (sin barra inicial, por ejemplo 'cursos' o 'nuevo-curso') "
        "y su valor es un sub-arreglo con la configuración de esa ruta: 'controlador_path', 'controlador', 'metodo', 'vista', 'titulo', "
        "'css', 'js' y opcionalmente 'layout'."
    ))
    item_lista(doc, "getMenuConfig(): array (línea 13)", (
        "Devuelve un arreglo con los botones o menús desplegables que tu módulo quiere inyectar en la barra lateral izquierda (sidebar.php)."
    ))
    item_lista(doc, "getDescripcion(): string (línea 16)", (
        "Devuelve un párrafo breve explicando qué hace el módulo. Esta descripción se muestra en las tarjetas del Gestor de Módulos del SuperAdmin."
    ))
    item_lista(doc, "getDependencias(): array (línea 19)", (
        "Devuelve un arreglo con los nombres de las carpetas de otros módulos de los que depende el tuyo (por ejemplo, ['Autenticacion']). "
        "Si no depende de ninguno en particular, simplemente retorna un arreglo vacío []."
    ))
    item_lista(doc, "getHomeConfig(): array (línea 22)", (
        "Define la sección o tarjeta promocional que tu módulo quiere mostrar en la página de bienvenida del sistema (ruta 'inicio'). "
        "Si tu módulo es puramente administrativo o interno y no debe aparecer en la portada pública, retorna []."
    ))
    item_lista(doc, "getHeaderConfig(): array (línea 25)", (
        "Permite que tu módulo inyecte buscadores, botones o vistas personalizadas en la esquina superior derecha del encabezado global "
        "(header.php). Si no necesitas agregar nada en la barra superior, retorna []."
    ))

    # -------------------------------------------------------------------------
    # 3.4. core/System/ModuleLoader.php
    # -------------------------------------------------------------------------
    titulo_nivel_2(doc, "3.4. El Cargador Dinámico de Módulos: core/System/ModuleLoader.php")
    p_apa(doc, (
        "El archivo core/System/ModuleLoader.php es el motor que hace realidad la arquitectura Microkernel. Su trabajo es recorrer "
        "la carpeta modules/, validar cada módulo contra ModuleContract y unificar sus rutas y menús."
    ))

    indicador_captura(doc, "core/System/ModuleLoader.php", 6, 19, "Propiedades internas y constructor de ModuleLoader")
    p_apa(doc, (
        "En las líneas 6 a 19 se definen las tres listas maestras donde el cargador guardará todo lo que encuentre: "
        "$modulosInstalados (guarda las instancias de cada módulo activo), $rutasGlobales (guarda la unión de todas las rutas de todos "
        "los módulos online) y $menuGlobal (guarda todas las opciones del menú lateral). En el constructor (líneas 15 a 19) se define que "
        "la ruta de lectura de estados es storage/config_system.json."
    ))

    indicador_captura(doc, "core/System/ModuleLoader.php", 24, 56, "Escaneo de carpetas, instanciación de index.php y fusión de rutasGlobales")
    p_apa(doc, (
        "Las líneas 24 a 56 contienen la primera mitad del método loadModules(). Analicemos con lupa lo que hace porque aquí hay dos "
        "reglas que todo programador debe conocer:"
    ))
    item_lista(doc, "Lectura de carpetas y estado (líneas 29 a 35)", (
        "Con scandir($this->modulesPath) obtiene los nombres de todas las carpetas dentro de modules/. Para cada carpeta consulta su "
        "estado en storage/config_system.json (si el módulo es nuevo y aún no aparece en el JSON, asume por defecto 'online'). Además, "
        "en la línea 35 marca como $esCore = true únicamente a 'Autenticacion' y 'SuperAdmin'."
    ))
    item_lista(doc, "Cómo carga tu archivo index.php (líneas 37 a 48)", (
        "Busca el archivo modules/NombreCarpeta/index.php. Nota que soporta dos formas de obtener la instancia de tu módulo: "
        "la forma recomendada (línea 44), donde tu archivo index.php termina con la instrucción return new NombreCarpetaModule();, "
        "o la forma por convención de nombre (líneas 40 y 45), donde busca una clase llamada exactamente igual que la carpeta seguida "
        "de la palabra Module (por ejemplo, si la carpeta se llama Cursos, busca la clase CursosModule)."
    ))
    item_lista(doc, "Verificación de contrato y registro de rutas (líneas 50 a 55)", (
        "En la línea 50 comprueba if ($modulo instanceof ModuleContract). Si cumple el contrato y además el módulo no está 'offline' "
        "(línea 52), guarda la instancia en $this->modulosInstalados[$carpeta] y fusiona sus rutas en el diccionario global con "
        "$this->rutasGlobales = array_merge($this->rutasGlobales, $modulo->getRutas());."
    ))

    indicador_captura(doc, "core/System/ModuleLoader.php", 57, 84, "Compilación del menú lateral global y asignación de prioridades")
    p_apa(doc, (
        "En las líneas 57 a 84 se procesa el menú lateral llamando a $modulo->getMenuConfig(). A cada botón del menú el ModuleLoader "
        "le inyecta automáticamente dos etiquetas internas muy útiles: 'modulo_origen' (el nombre de la carpeta del módulo) y "
        "'modulo_estado' ('online' u 'offline'). Además, si en tu módulo no definiste la clave 'orden' dentro del ítem de menú, "
        "las líneas 74 a 76 le asignan una prioridad por defecto (100 para cualquier módulo nuevo) y lo unen a $this->menuGlobal."
    ))

    indicador_captura(doc, "core/System/ModuleLoader.php", 86, 147, "Lectura de JSON de estados, ordenamiento del menú y metadatos para SuperAdmin")
    p_apa(doc, (
        "Para cerrar este archivo, entre las líneas 86 y 102 el método privado obtenerEstadosModulos() lee el archivo "
        "storage/config_system.json. En las líneas 112 a 120, getMenuGlobal() ordena todos los botones del menú lateral de menor a mayor "
        "según su clave 'orden' usando el operador nave espacial (<=>). Finalmente, en las líneas 122 a 147, getInfoModulosAdmin() "
        "construye el resumen de cada módulo instalado (id, nombre, icono, descripción, si es core y sus dependencias) para mostrarlo en "
        "el panel de administración del SuperAdmin."
    ))

    # -------------------------------------------------------------------------
    # 3.5. core/Http/Router.php
    # -------------------------------------------------------------------------
    titulo_nivel_2(doc, "3.5. El Enrutador del Sistema: core/Http/Router.php")
    p_apa(doc, (
        "El archivo core/Http/Router.php es sumamente limpio y directo (37 líneas en total). Su única misión es recibir el nombre "
        "de la ruta que pidió el usuario y buscarla en el arreglo $rutasGlobales que armó el ModuleLoader."
    ))

    indicador_captura(doc, "core/Http/Router.php", 1, 37, "Clase Router completa y los 3 tipos de coincidencia en resolve()")
    p_apa(doc, (
        "Al observar las líneas 1 a 37 de core/Http/Router.php, verás que el método resolve(string $ruta, array $rutasGlobales) "
        "evalúa tres escenarios posibles y siempre devuelve un arreglo estandarizado:"
    ))
    item_lista(doc, "Escenario 1: La ruta es 'inicio' (líneas 10 a 17)", (
        "Devuelve 'type' => 'native_home' y apunta directamente a la vista nativa CORE_VIEWS . 'home_bienvenida.php'."
    ))
    item_lista(doc, "Escenario 2: La ruta existe en $rutasGlobales (líneas 19 a 28)", (
        "Comprueba con array_key_exists($ruta, $rutasGlobales). Si la encuentra, devuelve 'type' => 'module_route', guarda toda la "
        "configuración declarada por el módulo dentro de la clave 'config', extrae la 'vista' (o null si es una ruta POST/API), "
        "el 'titulo' y el 'layout' (que por defecto activa header, sidebar y footer en true)."
    ))
    item_lista(doc, "Escenario 3: La ruta no existe en ningún módulo activo (líneas 30 a 35)", (
        "Devuelve 'type' => '404' y apunta a la vista CORE_VIEWS . '404.php'."
    ))

    # -------------------------------------------------------------------------
    # 3.6. core/Http/Dispatcher.php
    # -------------------------------------------------------------------------
    titulo_nivel_2(doc, "3.6. El Despachador MVC y Manejo de Errores: core/Http/Dispatcher.php")
    p_apa(doc, (
        "Si el Router es quien busca la dirección en el mapa, el Dispatcher (core/Http/Dispatcher.php) es quien conduce hasta allí, "
        "ejecuta tu controlador, pasa las variables a tu vista y envuelve todo en la plantilla maestra."
    ))

    indicador_captura(doc, "core/Http/Dispatcher.php", 34, 68, "Ejecución dinámica del controlador, método y extract() de datos para la vista")
    p_apa(doc, (
        "Las líneas 34 a 68 de core/Http/Dispatcher.php son el puente exacto entre el Core y el código MVC de tu módulo. "
        "Cuando $type es 'module_route' (línea 52), el Dispatcher toma la configuración de la ruta ($configRuta) y en la línea 55 "
        "verifica si definiste 'controlador' y 'controlador_path'. Si están presentes, ocurren cuatro pasos mágicos en las líneas 56 a 67:"
    ))
    item_lista(doc, "1. Carga e instanciación (líneas 56 a 60)", (
        "Hace require_once $configRuta['controlador_path'];, toma el nombre de la clase en $claseControlador y crea el objeto "
        "con $instancia = new $claseControlador();."
    ))
    item_lista(doc, "2. Llamada al método (línea 61)", (
        "Ejecuta dinámicamente el método configurado: $datosVista = $instancia->$metodo();."
    ))
    item_lista(doc, "3. Desempaquetado de variables con extract (líneas 63 y 64)", (
        "Si el método de tu controlador retornó un arreglo (por ejemplo, ['titulo_curso' => 'PHP', 'total' => 15]), la línea 64 "
        "ejecuta extract($datosVista). Esta función nativa de PHP crea en ese mismo ámbito las variables $titulo_curso y $total. "
        "Como unas líneas más abajo se hace include de master.php y de la vista de tu módulo dentro de este mismo método dispatch(), "
        "tu vista tiene acceso directo a $titulo_curso y $total como variables normales."
    ))
    item_lista(doc, "4. Corte limpio para endpoints con return false (líneas 65 y 66)", (
        "Si el método de tu controlador retorna explícitamente false (elseif ($datosVista === false) { exit; }), el Dispatcher "
        "detiene la ejecución de inmediato sin cargar master.php."
    ))

    indicador_captura(doc, "core/Http/Dispatcher.php", 70, 93, "Resolución de CSS/JS del módulo e inclusión de master.php")
    p_apa(doc, (
        "En las líneas 70 y 71, el Dispatcher llama a $assetResolver->resolveRouteCss(...) y $assetResolver->resolveRouteJs(...) "
        "para obtener las rutas hacia los archivos CSS y JS propios de esa pantalla, guardándolos en $css_modulo y $js_modulo. "
        "En la línea 76 guarda el menú lateral en $menu_dinamico = $menuGlobal; y en las líneas 78 y 79 verifica que exista "
        "CORE_VIEWS . 'master.php' y lo incluye con include CORE_VIEWS . 'master.php';."
    ))

    indicador_captura(doc, "core/Http/Dispatcher.php", 98, 173, "Manejo centralizado de errores de Base de Datos y excepciones HTTP 500")
    p_apa(doc, (
        "Todo el bloque del método dispatch() está protegido por un try / catch que desemboca en las líneas 98 a 173. "
        "Si falla la conexión a PostgreSQL, se activa handleDatabaseException() (líneas 98 a 128); si ocurre cualquier otro error "
        "fatal o excepción en un controlador o modelo, se activa handleGeneralException() (líneas 133 a 173). Ambos métodos son muy "
        "inteligentes: registran el fallo en el log de errores y en AuditLogger, detectan automáticamente si la petición venía por "
        "AJAX (mirando las cabeceras HTTP_X_REQUESTED_WITH y HTTP_ACCEPT) para responder un JSON limpio con código 500, o si era una "
        "navegación normal para mostrar la vista amigable core/Views/500.php."
    ))

    # -------------------------------------------------------------------------
    # 3.7. core/System/AssetResolver.php
    # -------------------------------------------------------------------------
    titulo_nivel_2(doc, "3.7. El Gestor de Recursos Estáticos y Vistas Globales: core/System/AssetResolver.php")
    p_apa(doc, (
        "Cuando programas un módulo, tú guardas tus hojas de estilo en modules/TuModulo/assets/css/ y tus scripts en "
        "modules/TuModulo/assets/js/, pero en tu archivo index.php solo escribes 'css' => ['mi_estilo.css']. ¿Cómo sabe el sistema "
        "encontrar la carpeta exacta de tu módulo? Esa es la tarea de core/System/AssetResolver.php."
    ))

    indicador_captura(doc, "core/System/AssetResolver.php", 9, 38, "Métodos resolveRouteCss() y resolveRouteJs()")
    p_apa(doc, (
        "En las líneas 9 a 38 están los métodos resolveRouteCss() y resolveRouteJs(). Ambos leen las claves 'css' y 'js' que "
        "declaraste en la ruta, averiguan el nombre de la carpeta del módulo llamando a $this->deducirCarpetaModulo(...) y construyen "
        "la ruta relativa exacta: '../modules/' . $nombreCarpeta . '/assets/css/' . $archivoCss (y lo mismo para '/assets/js/')."
    ))

    indicador_captura(doc, "core/System/AssetResolver.php", 43, 128, "Recolección de CSS del Home, controles del Header y tarjetas de inicio")
    p_apa(doc, (
        "Entre las líneas 43 y 128, AssetResolver recorre todos los módulos activos ($modulosInstalados) para recolectar lo que cada "
        "uno quiere aportar a las pantallas globales: getHomeCss() (líneas 43 a 54) junta los estilos de la portada; "
        "getControlesHeader() (líneas 59 a 85) junta los widgets de la barra superior y los ordena por su clave 'orden'; "
        "getGlobalCss() (líneas 89 a 110) junta los CSS requeridos por esos widgets del header; y getTarjetasInicio() (líneas 115 a 128) "
        "junta las secciones de módulos para la página de bienvenida."
    ))

    indicador_captura(doc, "core/System/AssetResolver.php", 129, 148, "Método privado deducirCarpetaModulo()")
    p_apa(doc, (
        "En las líneas 129 a 148 está el método deducirCarpetaModulo(). Usa dos estrategias en orden: primero intenta extraer el nombre "
        "de la carpeta leyendo la ruta de 'controlador_path' con la expresión regular #/modules/([^/]+)/#i (líneas 131 a 136); y si "
        "la ruta no tenía controlador (por ejemplo, una ruta que solo muestra una vista estática), busca en todos los módulos instalados "
        "cuál de ellos tiene registrada esa clave en su método getRutas() (líneas 139 a 145)."
    ))

    # -------------------------------------------------------------------------
    # 3.8. Vistas Maestras: master.php, header.php, sidebar.php y home_bienvenida.php
    # -------------------------------------------------------------------------
    titulo_nivel_2(doc, "3.8. Las Plantillas Maestras del Core: master.php, header.php, sidebar.php y home_bienvenida.php")
    p_apa(doc, (
        "Dentro de core/Views/ viven las plantillas que envuelven visualmente a todos los módulos para que el sistema tenga una "
        "apariencia institucional unificada."
    ))

    indicador_captura(doc, "core/Views/master.php", 1, 65, "Plantilla maestra completa: inyección de CSS, Header, Sidebar, Vista del Módulo y JS")
    p_apa(doc, (
        "Al revisar las líneas 1 a 65 de core/Views/master.php verás cómo encajan todas las piezas que preparó el Dispatcher:"
    ))
    item_lista(doc, "En el <head> (líneas 3 a 29)", (
        "Coloca el título de la pestaña con $titulo_pagina (línea 6), carga la fuente Inter y la librería de iconos Phosphor Icons "
        "(líneas 11 y 14), carga la hoja de estilos base assets/css/style.css (línea 16), inyecta los CSS globales del header "
        "(líneas 19 a 22) y recorre el arreglo $css_modulo para crear una etiqueta <link rel='stylesheet'> por cada archivo CSS que "
        "hayas pedido en tu ruta (líneas 24 a 28)."
    ))
    item_lista(doc, "En el <body> (líneas 30 a 64)", (
        "Si $layout_config['header'] es true, incluye header.php (líneas 32 a 34). Luego abre <main class='app-container'>: si "
        "$layout_config['sidebar'] es true, incluye sidebar.php a la izquierda (líneas 38 a 40), y a la derecha abre "
        "<div class='main-content'> (línea 42), donde incluye el archivo de vista de tu módulo ($vista_modulo_path) en las líneas 44 y 45. "
        "Finalmente, incluye footer.php (líneas 54 a 56) e inyecta al final del body todas las etiquetas <script> del arreglo $js_modulo "
        "(líneas 58 a 62). Nota importante: si en alguna ruta especial de tu módulo quieres ocultar el header, el sidebar o el footer "
        "(como en una pantalla de impresión o transición), basta con que agregues en la ruta de tu index.php la clave "
        "'layout' => ['header' => false, 'sidebar' => false, 'footer' => false]."
    ))

    indicador_captura(doc, "core/Views/header.php", 1, 42, "Renderizado dinámico de controles en la barra superior")
    p_apa(doc, (
        "En las líneas 1 a 42 de core/Views/header.php se dibuja la barra superior. A la izquierda muestra el logo de CIIDI (líneas 7 a 12) "
        "y a la derecha (líneas 14 a 41) recorre los controles devueltos por getHeaderConfig() de cada módulo, soportando tres formatos: "
        "'tipo' => 'search' (dibuja un formulario buscador GET), 'tipo' => 'button' (dibuja un botón de enlace con icono) y "
        "'tipo' => 'custom_view' (incluye un archivo de vista personalizado desde la ruta indicada en 'ruta_vista', como el widget de "
        "perfil del usuario logueado)."
    ))

    indicador_captura(doc, "core/Views/sidebar.php", 1, 61, "Obtención del usuario activo y filtrado de permisos del menú lateral")
    p_apa(doc, (
        "En las líneas 1 a 61 de core/Views/sidebar.php el menú lateral comprueba quién es el usuario actual mediante Auth::check() y "
        "Auth::usuario() (líneas 4 a 6). Si el visitante no ha iniciado sesión, su $nivelUsuario se asume como 999 (visitante público). "
        "Luego, al recorrer cada opción de $menu_dinamico (línea 29), aplica las reglas de visibilidad: oculta el módulo SuperAdmin del "
        "menú lateral común (líneas 38 a 40), oculta los módulos en estado 'offline' para usuarios normales (líneas 44 a 46) y en las "
        "líneas 50 a 54 verifica si el usuario cumple con el 'privilegio_minimo' o con el permiso específico de la matriz RBAC "
        "('permiso_rbac' y 'modulo_rbac'). Si el usuario no tiene el nivel requerido, ejecuta continue; y el botón ni siquiera se dibuja en el HTML."
    ))

    indicador_captura(doc, "core/Views/sidebar.php", 63, 134, "Dibujo de ítems tipo link, menús desplegables tipo parent y subitems")
    p_apa(doc, (
        "En las líneas 63 a 134 de core/Views/sidebar.php se dibujan los dos tipos de botones que puedes configurar en getMenuConfig(): "
        "cuando 'tipo' es 'link' (líneas 63 a 70), crea un enlace directo simple; cuando 'tipo' es 'parent' (líneas 72 a 114), crea un "
        "contenedor desplegable. Observa la línea 74: $is_active_parent = in_array($ruta, $item['activadores']);. Si la ruta actual en la "
        "que está navegando el usuario aparece dentro del arreglo 'activadores' que tú declaraste en tu módulo, el menú padre se pinta con "
        "la clase CSS 'open' y 'active', manteniendo el submenú desplegado automáticamente."
    ))

    indicador_captura(doc, "core/Views/home_bienvenida.php", 118, 156, "Renderizado de secciones y tarjetas de módulos en la pantalla de inicio")
    p_apa(doc, (
        "En las líneas 118 a 156 de core/Views/home_bienvenida.php se recorre el arreglo $seccionesHome (que proviene del método "
        "getHomeConfig() de cada módulo). Si tu módulo devuelve 'tipo' => 'custom_view', incluye la vista personalizada indicada en "
        "'ruta_vista' (líneas 123 a 128); de lo contrario, dibuja automáticamente una franja institucional intercalando fondo claro y "
        "blanco con el icono, título, descripción y botón de acción de tu módulo (líneas 130 a 150)."
    ))

    p_num6 = doc.add_paragraph()
    p_num6.paragraph_format.line_spacing = 2.0
    p_num6.paragraph_format.space_before = Pt(6)
    p_num6.paragraph_format.space_after = Pt(0)
    r_n6 = p_num6.add_run("Figura 6")
    configurar_fuente_run(r_n6, bold=True)
    p_t6 = doc.add_paragraph()
    p_t6.paragraph_format.line_spacing = 2.0
    p_t6.paragraph_format.space_before = Pt(0)
    p_t6.paragraph_format.space_after = Pt(4)
    r_t6 = p_t6.add_run("Mapa de inyección desde los métodos de index.php del módulo hacia las zonas de la plantilla maestra master.php")
    configurar_fuente_run(r_t6, italic=True)
    insertar_imagen_proporcional(doc, os.path.join(DIAGRAMAS_DIR, "diagrama_6_inyeccion_master_layout.png"))

    # -------------------------------------------------------------------------
    # 3.9. Capa de Base de Datos: Connection.php y QueryBuilder.php
    # -------------------------------------------------------------------------
    titulo_nivel_2(doc, "3.9. Capa de Base de Datos: core/Database/Connection.php y core/Database/QueryBuilder.php")
    p_apa(doc, (
        "Para hablar con PostgreSQL desde los modelos de tu módulo, el Core te entrega dos clases ubicadas en core/Database/: "
        "Connection.php (que administra la conexión PDO única) y QueryBuilder.php (que te permite construir consultas SQL seguras sin "
        "escribir SQL repetitivo a mano)."
    ))

    indicador_captura(doc, "core/Database/Connection.php", 6, 42, "Patrón Singleton y carga de credenciales desde .env y db_config.json")
    p_apa(doc, (
        "En las líneas 6 a 42 de core/Database/Connection.php se estructura el patrón Singleton mediante la propiedad estática "
        "private static $instance = null;. El método privado cargarCredenciales() (líneas 24 a 42) lee primero las variables DB_HOST, "
        "DB_PORT, DB_NAME, DB_USER y DB_PASS desde la clase Env (.env) y las complementa o respalda con el archivo storage/db_config.json."
    ))

    indicador_captura(doc, "core/Database/Connection.php", 134, 189, "Constructor PDO PostgreSQL y método Connection::getInstance()")
    p_apa(doc, (
        "En las líneas 134 a 166 el constructor privado abre la conexión PDO contra PostgreSQL usando codificación UTF8, modo de errores "
        "por excepciones (PDO::ERRMODE_EXCEPTION), retorno de datos como arreglos asociativos por defecto (PDO::FETCH_ASSOC) y sentencias "
        "preparadas nativas (PDO::ATTR_EMULATE_PREPARES => false). Luego, en las líneas 170 a 189 se encuentra el método estático "
        "Connection::getInstance(). Este método garantiza que, sin importar cuántos modelos instancies durante una petición, solo se abra "
        "una única conexión a PostgreSQL. Además, observa las líneas 176 a 187: si hay un usuario logueado en $_SESSION['usuario_id'], "
        "ejecuta automáticamente SELECT set_config('app.usuario_actual', ?, false) en PostgreSQL para que los disparadores (triggers) "
        "internos de la base de datos sepan qué usuario realizó cada inserción, edición o borrado."
    ))

    indicador_captura(doc, "core/Database/QueryBuilder.php", 5, 68, "Inicialización del QueryBuilder y métodos reset(), tabla(), select() y where()")
    p_apa(doc, (
        "En las líneas 5 a 68 de core/Database/QueryBuilder.php vemos cómo se inicializa el constructor de consultas pidiendo "
        "$this->db = Connection::getInstance(); en su constructor (línea 19). El método tabla(string $nombre_tabla) (líneas 41 a 45) "
        "limpia automáticamente cualquier consulta anterior llamando a $this->reset() y sanitiza el nombre de la tabla. El método "
        "where($columna, $operador, $valor) (líneas 58 a 68) valida que el operador sea seguro (=, !=, <, >, LIKE, ILIKE, IN, etc.), "
        "limpia el nombre de la columna y guarda el valor por separado en el arreglo $this->parametros para enviarlo mediante signos "
        "de interrogación (?) en una sentencia preparada, blindando tu módulo contra cualquier intento de inyección SQL."
    ))

    indicador_captura(doc, "core/Database/QueryBuilder.php", 70, 176, "Métodos whereRaw(), orderBy(), limit(), offset(), join(), get(), first() y count()")
    p_apa(doc, (
        "En las líneas 70 a 115 están los modificadores encadenables para condiciones compuestas (whereRaw), ordenamiento (orderBy), "
        "paginación (limit y offset), agrupación (groupBy) y uniones de tablas (join). Entre las líneas 117 y 176 están los tres métodos "
        "de lectura que cierran la cadena: get() (líneas 117 a 150, que ensambla el SELECT completo, ejecuta el prepare/execute y retorna "
        "un arreglo con todas las filas encontradas), first() (líneas 152 a 156, que aplica limit(1) y devuelve solo la primera fila o "
        "null si no existe) y count() (líneas 158 a 176, que devuelve el entero con el total de registros que cumplen las condiciones)."
    ))

    indicador_captura(doc, "core/Database/QueryBuilder.php", 178, 231, "Métodos de escritura insert(), update() y delete() con protección WHERE")
    p_apa(doc, (
        "En las líneas 178 a 231 se encuentran los tres métodos de escritura en base de datos:"
    ))
    item_lista(doc, "insert(array $datos) (líneas 178 a 193)", (
        "Recibe un arreglo asociativo ['columna' => $valor], arma el INSERT INTO ... VALUES (...) RETURNING id de PostgreSQL y te "
        "devuelve directamente el id recién creado en la tabla (o false si falló)."
    ))
    item_lista(doc, "update(array $datos) (líneas 195 a 218)", (
        "Actualiza las columnas pasadas en el arreglo $datos. Atención a las líneas 198 a 200: si intentas llamar a update() sin haber "
        "llamado antes a ->where(...), lanza una excepción de seguridad para impedir que sobrescribas toda la tabla por accidente."
    ))
    item_lista(doc, "delete() (líneas 220 a 231)", (
        "Borra los registros que coincidan con las condiciones. Al igual que update(), en las líneas 223 a 225 exige obligatoriamente "
        "que exista al menos una condición WHERE antes de ejecutar el DELETE."
    ))

    p_num7 = doc.add_paragraph()
    p_num7.paragraph_format.line_spacing = 2.0
    p_num7.paragraph_format.space_before = Pt(6)
    p_num7.paragraph_format.space_after = Pt(0)
    r_n7 = p_num7.add_run("Figura 7")
    configurar_fuente_run(r_n7, bold=True)
    p_t7 = doc.add_paragraph()
    p_t7.paragraph_format.line_spacing = 2.0
    p_t7.paragraph_format.space_before = Pt(0)
    p_t7.paragraph_format.space_after = Pt(4)
    r_t7 = p_t7.add_run("Flujo de persistencia desde el Controlador y el Modelo hacia PostgreSQL usando QueryBuilder o PDO directo")
    configurar_fuente_run(r_t7, italic=True)
    insertar_imagen_proporcional(doc, os.path.join(DIAGRAMAS_DIR, "diagrama_7_capa_datos_querybuilder_pdo.png"))

    # -------------------------------------------------------------------------
    # 3.10. Seguridad, Permisos, Auditoría y Variables
    # -------------------------------------------------------------------------
    titulo_nivel_2(doc, "3.10. Seguridad, Permisos, Auditoría y Variables: Auth.php, CSRF.php, AuditLogger.php y FeatureFlagMiddleware.php")
    p_apa(doc, (
        "Para cerrar el recorrido por el Core, veamos las cuatro clases de seguridad y control que invocarás constantemente dentro de "
        "los controladores y vistas de tus módulos."
    ))

    indicador_captura(doc, "core/Security/Auth.php", 8, 89, "Método Auth::check() con control de sesión revocada y expiración por inactividad")
    p_apa(doc, (
        "En las líneas 8 a 89 de core/Security/Auth.php, el método estático Auth::check() verifica no solo si existe "
        "$_SESSION['usuario_id'], sino también dos mecanismos de seguridad en tiempo real: primero (líneas 17 a 62), revisa en "
        "storage/revoked_sessions.json si el SuperAdmin forzó el cierre de sesión de ese usuario (Kill Session); y segundo (líneas 65 a 87), "
        "calcula cuántos segundos lleva inactivo el usuario comparando time() contra $_SESSION['ultimo_acceso'] y lo expulsa al login si "
        "superó el tiempo máximo configurado."
    ))

    indicador_captura(doc, "core/Security/Auth.php", 94, 146, "Método Auth::requierePrivilegioMinimo() y validación contra matriz_rbac")
    p_apa(doc, (
        "En las líneas 94 a 146 se encuentra el guardián que pondrás en la primera línea de tus métodos administrativos: "
        "Auth::requierePrivilegioMinimo(int $nivelExigido, ?string $permisoRuta = null, ?string $moduloRuta = null, bool $redirect403 = true). "
        "En CIIDI, mientras más pequeño es el número de nivel_privilegio, mayor es la jerarquía del usuario: el nivel 0 es el nivel Dios "
        "(SuperAdmin, que pasa siempre en la línea 103); niveles como 1 o 2 corresponden a administradores o docentes; y 999 representa "
        "al público general. Si además pasas los parámetros $permisoRuta (por ejemplo, 'auditar', 'crear', 'editar' o 'eliminar') y "
        "$moduloRuta (por ejemplo, 'Articulos'), las líneas 107 a 136 consultan la tabla matriz_rbac en PostgreSQL para verificar si "
        "ese nivel de usuario tiene encendido ese permiso puntual en su columna JSON. Si no tiene permiso y $redirect403 es true, "
        "renderiza automáticamente la vista 403 de acceso denegado y detiene el controlador."
    ))

    indicador_captura(doc, "core/Security/Auth.php", 173, 196, "Método Auth::usuario() para obtener los datos del usuario logueado")
    p_apa(doc, (
        "En las líneas 173 a 196 de core/Security/Auth.php, el método Auth::usuario() te devuelve un arreglo limpio con las claves "
        "'id', 'nombre', 'cedula', 'email', 'nivel' y 'rol' del usuario que tiene la sesión iniciada, o null si es un visitante anónimo."
    ))

    indicador_captura(doc, "core/Security/CSRF.php", 1, 29, "Clase CSRF completa: generarToken(), validarToken() y campoOculto()")
    p_apa(doc, (
        "En las líneas 1 a 29 de core/Security/CSRF.php tienes la protección contra falsificación de peticiones en sitios cruzados (CSRF). "
        "Su uso en tus módulos es de solo dos pasos: en el formulario HTML de tu vista imprimes <?= CSRF::campoOculto() ?> (líneas 25 a 28), "
        "lo cual genera un <input type='hidden' name='csrf_token'> con un token aleatorio de 64 caracteres hexadecimales; y en el método "
        "del controlador que recibe el $_POST compruebas if (!CSRF::validarToken($_POST['csrf_token'] ?? null)) (líneas 15 a 23), que "
        "compara de forma segura con hash_equals() si el token enviado coincide con el de la sesión."
    ))

    indicador_captura(doc, "core/Security/AuditLogger.php", 15, 115, "Método AuditLogger::registrar() con encadenamiento hash HMAC SHA-256")
    p_apa(doc, (
        "En las líneas 15 a 115 de core/Security/AuditLogger.php se encuentra el método estático AuditLogger::registrar($nivel, $modulo, "
        "$accion, $detalles). Cada vez que en tu módulo un usuario crea, edita, elimina o cambia la configuración de un registro, debes "
        "llamar a este método. Él detecta automáticamente el nombre y el ID del usuario en sesión, su dirección IP, consulta el hash del "
        "último evento en la tabla system_audit_log, calcula una firma criptográfica encadenada con hash_hmac('sha256', ...) y guarda "
        "el registro en PostgreSQL (o en el archivo de contingencia storage/logs/audit_fallback.jsonl si la base de datos no responde)."
    ))

    indicador_captura(doc, "core/Http/FeatureFlagMiddleware.php", 1, 54, "Intercepción de rutas en modo offline o solo_lectura")
    p_apa(doc, (
        "Finalmente, en las líneas 1 a 54 de core/Http/FeatureFlagMiddleware.php puedes ver cómo el Core permite controlar cada ruta "
        "individual de tu módulo desde el panel del SuperAdmin sin tocar tu código. Cuando una ruta de tu módulo se marca en "
        "storage/config_system.json como 'offline' (líneas 36 a 40), el middleware detiene la petición y muestra la vista "
        "core/Views/deshabilitado.php. Y si se marca como 'solo_lectura' (líneas 42 a 49), deja pasar todas las consultas GET para que "
        "los usuarios puedan seguir viendo y leyendo las pantallas, pero bloquea cualquier petición POST, PUT o DELETE que intente "
        "modificar datos."
    ))


def agregar_parte_4_5_y_6(doc):
    """
    Construye la Parte 4 (Tutorial paso a paso para crear un nuevo módulo desde cero),
    la Parte 5 (Cómo modificar o extender un módulo existente) y la Parte 6 (Troubleshooting).
    """
    # =========================================================================
    # PARTE 4: GUÍA PRÁCTICA PASO A PASO — CÓMO CREAR Y AÑADIR UN NUEVO MÓDULO
    # =========================================================================
    titulo_nivel_1(doc, "Parte 4. Guía Práctica Paso a Paso: Cómo Crear y Añadir un Nuevo Módulo desde Cero")
    p_apa(doc, (
        "Ahora que ya conoces cómo funciona el Core por dentro y qué hace cada uno de sus archivos, vamos a la práctica pura. "
        "En esta sección construiremos desde cero un módulo nuevo completo llamado Eventos (para gestionar jornadas académicas y "
        "congresos del CIIDI). Si sigues estos seis pasos en orden, podrás crear cualquier módulo nuevo en el sistema sin tocar "
        "ni una sola línea del Core."
    ))

    p_num8 = doc.add_paragraph()
    p_num8.paragraph_format.line_spacing = 2.0
    p_num8.paragraph_format.space_before = Pt(6)
    p_num8.paragraph_format.space_after = Pt(0)
    r_n8 = p_num8.add_run("Figura 8")
    configurar_fuente_run(r_n8, bold=True)
    p_t8 = doc.add_paragraph()
    p_t8.paragraph_format.line_spacing = 2.0
    p_t8.paragraph_format.space_before = Pt(0)
    p_t8.paragraph_format.space_after = Pt(4)
    r_t8 = p_t8.add_run("Hoja de ruta de los 6 pasos para crear y enchufar un nuevo módulo en CIIDI")
    configurar_fuente_run(r_t8, italic=True)
    insertar_imagen_proporcional(doc, os.path.join(DIAGRAMAS_DIR, "diagrama_8_pasos_crear_modulo.png"))

    # -------------------------------------------------------------------------
    # Paso 1: Estructura de carpetas
    # -------------------------------------------------------------------------
    titulo_nivel_2(doc, "4.1. Paso 1: Crear la estructura de carpetas del nuevo módulo")
    p_apa(doc, (
        "Lo primero que debes hacer es entrar a la carpeta modules/ y crear una carpeta con el nombre de tu módulo en formato "
        "PascalCase (la primera letra de cada palabra en mayúscula, sin espacios, sin tildes y sin caracteres especiales). "
        "Para nuestro ejemplo crearemos modules/Eventos/. Adentro de ella debes crear la siguiente estructura estándar de directorios:"
    ))
    bloque_codigo(doc, """
modules/
└── Eventos/
    ├── index.php
    ├── controllers/
    │   └── EventosController.php
    ├── models/
    │   └── EventoModel.php
    ├── views/
    │   ├── catalogo_eventos.php
    │   └── gestor_eventos.php
    ├── services/
    └── assets/
        ├── css/
        │   └── eventos.css
        ├── js/
        │   └── eventos.js
        └── img/
""")
    p_apa(doc, (
        "Respeta siempre los nombres exactos de las subcarpetas assets/css/ y assets/js/, porque como vimos en la Parte 3.7, la clase "
        "AssetResolver del Core busca tus hojas de estilo y scripts exactamente en '../modules/NombreCarpeta/assets/css/' y "
        "'../modules/NombreCarpeta/assets/js/'."
    ))

    # -------------------------------------------------------------------------
    # Paso 2: Crear index.php e implementar ModuleContract
    # -------------------------------------------------------------------------
    titulo_nivel_2(doc, "4.2. Paso 2: Crear el archivo index.php e implementar ModuleContract")
    p_apa(doc, (
        "El archivo modules/Eventos/index.php es el manifiesto de tu módulo. Cuando el ModuleLoader escanea la carpeta modules/Eventos/, "
        "este es el único archivo que abre directamente. Para que funcione a la primera, debes seguir tres reglas obligatorias:"
    ))
    item_lista(doc, "Regla 1 (Nombre de la clase)", (
        "La clase debe llamarse exactamente igual que la carpeta seguida de la palabra Module (en este caso, EventosModule) "
        "y debe declarar implements ModuleContract."
    ))
    item_lista(doc, "Regla 2 (Nombres de rutas sin barra inicial)", (
        "Las claves del arreglo que retorna getRutas() nunca llevan barra inclinada '/' al principio. Escribe 'eventos' o "
        "'eventos-guardar', nunca '/eventos'."
    ))
    item_lista(doc, "Regla 3 (Retornar la instancia al final del archivo)", (
        "La última línea de tu archivo index.php siempre debe ser return new EventosModule();."
    ))
    p_apa(doc, (
        "A continuación tienes la plantilla completa y comentada para modules/Eventos/index.php incluyendo una ruta de vista pública, "
        "una ruta de vista administrativa, una ruta de acción POST y una ruta de API AJAX JSON:"
    ))
    bloque_codigo(doc, """
<?php
// modules/Eventos/index.php

require_once CORE_PATH . 'Interfaces/ModuleContract.php';
require_once __DIR__ . '/../SuperAdmin/services/SystemConfigService.php';

class EventosModule implements ModuleContract {

    public function getNombre(): string {
        return 'Módulo de Eventos y Jornadas Científicas';
    }

    public function getRutas(): array {
        $ctrl = __DIR__ . '/controllers/EventosController.php';

        return [
            // 1. Ruta de Vista Pública (GET)
            'eventos' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'EventosController',
                'metodo'           => 'catalogoPublico',
                'vista'            => __DIR__ . '/views/catalogo_eventos.php',
                'titulo'           => 'Eventos Académicos - CIIDI UPTTMBI',
                'css'              => ['eventos.css'],
                'js'               => ['eventos.js']
            ],

            // 2. Ruta de Vista Administrativa (GET)
            'eventos-gestion' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'EventosController',
                'metodo'           => 'gestorAdmin',
                'vista'            => __DIR__ . '/views/gestor_eventos.php',
                'titulo'           => 'Gestión de Eventos - Administración',
                'css'              => ['eventos.css'],
                'js'               => ['eventos.js']
            ],

            // 3. Ruta de Acción para Procesar Formulario (POST + Redirect)
            // Nota que NO lleva 'vista' porque el controlador redirige al terminar
            'eventos-guardar' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'EventosController',
                'metodo'           => 'procesarGuardar'
            ],

            // 4. Ruta de Acción para Eliminar (POST + Redirect)
            'eventos-eliminar' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'EventosController',
                'metodo'           => 'procesarEliminar'
            ],

            // 5. Ruta de API / AJAX (Devuelve JSON puro a fetch de JS)
            'api-eventos-detalle' => [
                'controlador_path' => $ctrl,
                'controlador'      => 'EventosController',
                'metodo'           => 'apiObtenerDetalle'
            ]
        ];
    }

    public function getMenuConfig(): array {
        // Obtenemos los niveles de privilegio desde la configuración global
        // Por defecto: 999 (cualquier visitante ve el catálogo) y 1 (solo admin/docente gestiona)
        $nivelPublico = SystemConfigService::get('accesos_modulos.eventos.publico', 999);
        $nivelAdmin   = SystemConfigService::get('accesos_modulos.eventos.admin', 1);

        return [
            [
                'tipo'              => 'parent',
                'titulo'            => 'Eventos',
                'icono'             => 'ph-fill ph-calendar-check',
                'orden'             => 70,
                'enlace'            => 'eventos',
                'privilegio_minimo' => $nivelPublico,
                // IMPORTANTE: En 'activadores' pon todas las rutas de tu módulo para que
                // el menú lateral se mantenga abierto cuando el usuario navegue por ellas
                'activadores'       => ['eventos', 'eventos-gestion'],
                'subitems'          => [
                    [
                        'ruta'              => 'eventos',
                        'titulo'            => 'Cartelera de Eventos',
                        'privilegio_minimo' => $nivelPublico
                    ],
                    [
                        'ruta'              => 'eventos-gestion',
                        'titulo'            => 'Gestor de Eventos',
                        'privilegio_minimo' => $nivelAdmin
                    ]
                ]
            ]
        ];
    }

    public function getDescripcion(): string {
        return 'Gestión y cartelera pública de congresos, ponencias y jornadas académicas del CIIDI.';
    }

    public function getDependencias(): array {
        return ['Autenticacion'];
    }

    public function getHomeConfig(): array {
        return [
            'tipo'        => 'standard',
            'icono'       => 'ph-fill ph-calendar-check',
            'titulo'      => 'Jornadas y Eventos Científicos',
            'descripcion' => 'Consulta el calendario de congresos, defensas públicas y simposios tecnológicos.',
            'enlace'      => 'eventos',
            'texto_boton' => 'VER CALENDARIO'
        ];
    }

    public function getHeaderConfig(): array {
        return [];
    }
}

return new EventosModule();
""")

    # -------------------------------------------------------------------------
    # Paso 3: Base de Datos y Modelo
    # -------------------------------------------------------------------------
    titulo_nivel_2(doc, "4.3. Paso 3: Crear la tabla en PostgreSQL y programar el Modelo en models/")
    p_apa(doc, (
        "Primero creamos la tabla correspondiente en la base de datos PostgreSQL (ciidi). Por ejemplo, una tabla llamada public.eventos:"
    ))
    bloque_codigo(doc, """
CREATE TABLE IF NOT EXISTS public.eventos (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT NOT NULL,
    fecha_evento DATE NOT NULL,
    lugar VARCHAR(150) NOT NULL,
    estado VARCHAR(30) DEFAULT 'publicado',
    id_creador INTEGER REFERENCES public.usuarios(id) ON DELETE SET NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
""")
    p_apa(doc, (
        "Ahora creamos el archivo modules/Eventos/models/EventoModel.php. En este modelo te muestro cómo usar tanto el QueryBuilder "
        "(para inserciones, búsquedas por ID, actualizaciones y borrados rápidos) como Connection::getInstance() (para cuando necesites "
        "un SELECT con LEFT JOIN y filtros dinámicos):"
    ))
    bloque_codigo(doc, """
<?php
// modules/Eventos/models/EventoModel.php

require_once CORE_PATH . 'Database/QueryBuilder.php';

class EventoModel {

    private QueryBuilder $qb;
    private ?PDO $db;

    public function __construct() {
        $this->qb = new QueryBuilder();
        $this->db = Connection::getInstance();
    }

    /**
     * Ejemplo usando QueryBuilder para obtener todos los eventos publicados.
     */
    public function listarPublicados(): array {
        return $this->qb
            ->tabla('eventos')
            ->select('*')
            ->where('estado', '=', 'publicado')
            ->orderBy('fecha_evento', 'ASC')
            ->get();
    }

    /**
     * Ejemplo usando PDO directo (Connection::getInstance) cuando necesitamos
     * unir la tabla eventos con la tabla usuarios para traer el nombre del creador.
     */
    public function listarTodosConCreador(): array {
        if (!$this->db) return [];

        $sql = "
            SELECT e.*, u.nombre_completo AS nombre_creador
            FROM public.eventos e
            LEFT JOIN public.usuarios u ON e.id_creador = u.id
            ORDER BY e.fecha_creacion DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Buscar un evento específico por su ID usando QueryBuilder.
     */
    public function obtenerPorId(int $id): ?array {
        return $this->qb
            ->tabla('eventos')
            ->where('id', '=', $id)
            ->first();
    }

    /**
     * Insertar un nuevo evento y devolver el ID recién generado.
     */
    public function crear(array $datos) {
        return $this->qb
            ->tabla('eventos')
            ->insert([
                'titulo'       => $datos['titulo'],
                'descripcion'  => $datos['descripcion'],
                'fecha_evento' => $datos['fecha_evento'],
                'lugar'        => $datos['lugar'],
                'estado'       => $datos['estado'] ?? 'publicado',
                'id_creador'   => $datos['id_creador'] ?? null
            ]);
    }

    /**
     * Actualizar un evento existente (recuerda siempre poner ->where antes de ->update).
     */
    public function actualizar(int $id, array $datos): bool {
        return $this->qb
            ->tabla('eventos')
            ->where('id', '=', $id)
            ->update($datos);
    }

    /**
     * Eliminar un evento (recuerda siempre poner ->where antes de ->delete).
     */
    public function eliminar(int $id): bool {
        return $this->qb
            ->tabla('eventos')
            ->where('id', '=', $id)
            ->delete();
    }
}
""")

    # -------------------------------------------------------------------------
    # Paso 4: Crear el Controlador
    # -------------------------------------------------------------------------
    titulo_nivel_2(doc, "4.4. Paso 4: Programar el Controlador en controllers/ (Vistas, POST y JSON)")
    p_apa(doc, (
        "Ahora creamos modules/Eventos/controllers/EventosController.php. Observa con atención cómo se programan los tres tipos de "
        "métodos: los que devuelven un arreglo asociativo para que el Dispatcher pinte la vista (catalogoPublico y gestorAdmin), "
        "los que procesan un formulario POST validando CSRF, registrando en AuditLogger y redirigiendo con header('Location: ...') "
        "(procesarGuardar y procesarEliminar), y el que responde JSON puro para llamadas AJAX (apiObtenerDetalle):"
    ))
    bloque_codigo(doc, """
<?php
// modules/Eventos/controllers/EventosController.php

require_once __DIR__ . '/../models/EventoModel.php';
require_once CORE_PATH . 'Security/Auth.php';
require_once CORE_PATH . 'Security/CSRF.php';
require_once CORE_PATH . 'Security/AuditLogger.php';
require_once __DIR__ . '/../../SuperAdmin/services/SystemConfigService.php';

class EventosController {

    private EventoModel $model;
    private int $nivelAdmin;

    public function __construct() {
        $this->model      = new EventoModel();
        $this->nivelAdmin = (int) SystemConfigService::get('accesos_modulos.eventos.admin', 1);
    }

    /**
     * 1. Método de Vista Pública: retorna un array asociativo que el Dispatcher
     * convertirá en variables ($eventos, $usuario_actual) para catalogo_eventos.php
     */
    public function catalogoPublico(): array {
        $eventos        = $this->model->listarPublicados();
        $usuario_actual = Auth::usuario();

        return [
            'eventos'        => $eventos,
            'usuario_actual' => $usuario_actual
        ];
    }

    /**
     * 2. Método de Vista Administrativa: exige privilegio mínimo antes de consultar datos.
     */
    public function gestorAdmin(): array {
        Auth::requierePrivilegioMinimo($this->nivelAdmin);

        $eventos       = $this->model->listarTodosConCreador();
        $mensaje_exito = $_SESSION['evt_exito'] ?? null;
        $mensaje_error = $_SESSION['evt_error'] ?? null;
        unset($_SESSION['evt_exito'], $_SESSION['evt_error']);

        return compact('eventos', 'mensaje_exito', 'mensaje_error');
    }

    /**
     * 3. Método de Acción POST: valida sesión, valida CSRF, guarda en BD,
     * registra en AuditLogger y redirige con exit.
     */
    public function procesarGuardar(): void {
        Auth::requierePrivilegioMinimo($this->nivelAdmin);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: eventos-gestion');
            exit;
        }

        if (!CSRF::validarToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['evt_error'] = 'Token de seguridad CSRF inválido. Intente nuevamente.';
            header('Location: eventos-gestion');
            exit;
        }

        $titulo      = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $fecha       = trim($_POST['fecha_evento'] ?? '');
        $lugar       = trim($_POST['lugar'] ?? '');

        if ($titulo === '' || $descripcion === '' || $fecha === '' || $lugar === '') {
            $_SESSION['evt_error'] = 'Todos los campos son obligatorios.';
            header('Location: eventos-gestion');
            exit;
        }

        $usuario = Auth::usuario();
        $nuevoId = $this->model->crear([
            'titulo'       => mb_substr($titulo, 0, 200),
            'descripcion'  => mb_substr($descripcion, 0, 5000),
            'fecha_evento' => $fecha,
            'lugar'        => mb_substr($lugar, 0, 150),
            'estado'       => 'publicado',
            'id_creador'   => $usuario['id'] ?? null
        ]);

        if ($nuevoId) {
            AuditLogger::registrar('INFO', 'Eventos', 'Creación de Evento', "Se creó el evento ID #{$nuevoId}: {$titulo}");
            $_SESSION['evt_exito'] = 'El evento fue registrado correctamente.';
        } else {
            $_SESSION['evt_error'] = 'No se pudo guardar el evento.';
        }

        header('Location: eventos-gestion');
        exit;
    }

    /**
     * 4. Método de Acción POST para eliminar un registro.
     */
    public function procesarEliminar(): void {
        Auth::requierePrivilegioMinimo($this->nivelAdmin);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !CSRF::validarToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['evt_error'] = 'Solicitud no autorizada.';
            header('Location: eventos-gestion');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0 && $this->model->eliminar($id)) {
            AuditLogger::registrar('WARNING', 'Eventos', 'Eliminación de Evento', "Se eliminó el evento ID #{$id}");
            $_SESSION['evt_exito'] = 'Evento eliminado correctamente.';
        } else {
            $_SESSION['evt_error'] = 'No se pudo eliminar el evento solicitado.';
        }

        header('Location: eventos-gestion');
        exit;
    }

    /**
     * 5. Método de API AJAX (JSON): responde JSON puro y corta la ejecución con exit
     * para que el Dispatcher no agregue el HTML de master.php.
     */
    public function apiObtenerDetalle(): void {
        header('Content-Type: application/json; charset=utf-8');

        $id = (int)($_GET['id'] ?? 0);
        $evento = $id > 0 ? $this->model->obtenerPorId($id) : null;

        if (!$evento) {
            http_response_code(404);
            echo json_encode(['error' => true, 'mensaje' => 'Evento no encontrado']);
            exit;
        }

        echo json_encode(['error' => false, 'datos' => $evento]);
        exit;
    }
}
""")

    # -------------------------------------------------------------------------
    # Paso 5: Crear las Vistas y los Assets
    # -------------------------------------------------------------------------
    titulo_nivel_2(doc, "4.5. Paso 5: Crear las Vistas en views/ y los archivos CSS y JS en assets/")
    p_apa(doc, (
        "Las vistas que coloques dentro de modules/Eventos/views/ se inyectarán automáticamente adentro de <div class='main-content'> "
        "en master.php. Por lo tanto, no debes escribir etiquetas <html>, <head> ni <body> en tus vistas. Recuerda dos buenas prácticas "
        "obligatorias en CIIDI: siempre escapar cualquier variable que venga de la base de datos con htmlspecialchars($valor, ENT_QUOTES, 'UTF-8') "
        "para prevenir ataques XSS, y siempre incluir <?= CSRF::campoOculto() ?> dentro de todo formulario <form method='POST'>."
    ))
    p_apa(doc, (
        "Veamos un ejemplo limpio para la vista administrativa modules/Eventos/views/gestor_eventos.php:"
    ))
    bloque_codigo(doc, """
<?php
// modules/Eventos/views/gestor_eventos.php
require_once CORE_PATH . 'Security/CSRF.php';
?>
<div class="eventos-contenedor">
    <h1>Gestor de Eventos Académicos</h1>

    <?php if (!empty($mensaje_exito)): ?>
        <div class="alerta-exito"><?= htmlspecialchars($mensaje_exito, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (!empty($mensaje_error)): ?>
        <div class="alerta-error"><?= htmlspecialchars($mensaje_error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <!-- Formulario para crear un nuevo evento -->
    <form action="eventos-guardar" method="POST" class="form-evento">
        <?= CSRF::campoOculto() ?>

        <label>Título del Evento:</label>
        <input type="text" name="titulo" required maxlength="200">

        <label>Fecha del Evento:</label>
        <input type="date" name="fecha_evento" required>

        <label>Lugar / Auditorio:</label>
        <input type="text" name="lugar" required maxlength="150">

        <label>Descripción:</label>
        <textarea name="descripcion" rows="4" required></textarea>

        <button type="submit" class="btn">Guardar Evento</button>
    </form>

    <!-- Tabla de eventos registrados -->
    <table class="tabla-eventos">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Fecha</th>
                <th>Lugar</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($eventos as $ev): ?>
                <tr>
                    <td><?= (int)$ev['id'] ?></td>
                    <td><?= htmlspecialchars($ev['titulo'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($ev['fecha_evento'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($ev['lugar'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <form action="eventos-eliminar" method="POST" style="display:inline;">
                            <?= CSRF::campoOculto() ?>
                            <input type="hidden" name="id" value="<?= (int)$ev['id'] ?>">
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
""")
    p_apa(doc, (
        "Por su parte, los archivos modules/Eventos/assets/css/eventos.css y modules/Eventos/assets/js/eventos.js se cargarán solos "
        "porque ya los declaraste en las claves 'css' => ['eventos.css'] y 'js' => ['eventos.js'] de tu index.php."
    ))

    # -------------------------------------------------------------------------
    # Paso 6: Verificación automática y permisos RBAC
    # -------------------------------------------------------------------------
    titulo_nivel_2(doc, "4.6. Paso 6: Verificación automática, permisos RBAC y ajustes en SuperAdmin")
    p_apa(doc, (
        "En el mismo instante en que guardas los archivos de los pasos anteriores y recargas el navegador, el ModuleLoader detecta "
        "automáticamente la carpeta modules/Eventos/. No tienes que ejecutar ningún comando de consola ni registrar el módulo en ningún "
        "archivo del Core. Verás de inmediato tres resultados:"
    ))
    item_lista(doc, "1. En el menú lateral (Sidebar)", (
        "Aparecerá el menú desplegable 'Eventos' con sus subítems, respetando la posición indicada en 'orden' => 70."
    ))
    item_lista(doc, "2. En la página de bienvenida (Inicio)", (
        "Aparecerá automáticamente la franja promocional configurada en getHomeConfig() con el botón 'VER CALENDARIO' apuntando a la ruta 'eventos'."
    ))
    item_lista(doc, "3. En el Gestor de Módulos del SuperAdmin", (
        "Cuando entres con una cuenta de nivel 0 a la ruta 'gestor-modulos', verás la tarjeta de tu nuevo módulo Eventos con un interruptor "
        "para encenderlo o apagarlo en caliente, y al entrar a su detalle verás la lista de sus 5 rutas ('eventos', 'eventos-gestion', "
        "'eventos-guardar', 'eventos-eliminar' y 'api-eventos-detalle') para poder poner cualquiera de ellas en modo activo, solo lectura "
        "o deshabilitado."
    ))

    # =========================================================================
    # PARTE 5: CÓMO MODIFICAR O EXTENDER UN MÓDULO EXISTENTE
    # =========================================================================
    titulo_nivel_1(doc, "Parte 5. Cómo Modificar o Extender un Módulo Existente sin Romper el Sistema")
    p_apa(doc, (
        "En el día a día como programador de CIIDI, muchas veces no necesitarás crear un módulo desde cero, sino modificar uno de los "
        "módulos que ya existen (como Articulos, Cursos, Investigaciones, LineasInvestigacion, RepositorioPST o VinculacionEmpresarial). "
        "A continuación tienes las recetas exactas para realizar las cinco modificaciones más comunes sin romper nada."
    ))

    titulo_nivel_2(doc, "5.1. Cómo añadir una nueva pantalla (vista) a un módulo existente")
    p_apa(doc, (
        "Si te piden agregar una pantalla nueva a un módulo que ya existe (por ejemplo, una pantalla de estadísticas en el módulo Cursos), "
        "solo debes tocar tres archivos dentro de ese módulo:"
    ))
    item_lista(doc, "1. En modules/NombreModulo/views/", (
        "Crea tu nuevo archivo de vista PHP (por ejemplo, estadisticas_cursos.php) con el HTML de tu pantalla."
    ))
    item_lista(doc, "2. En modules/NombreModulo/controllers/", (
        "Abre el controlador del módulo y agrega un nuevo método público (por ejemplo, public function mostrarEstadisticas(): array) "
        "que consulte los datos en el modelo y los retorne en un arreglo asociativo."
    ))
    item_lista(doc, "3. En modules/NombreModulo/index.php", (
        "Dentro del arreglo de getRutas(), agrega una nueva clave con el nombre de la ruta (por ejemplo, 'cursos-estadisticas') "
        "apuntando al controlador, al nuevo método 'mostrarEstadisticas' y a la nueva vista '__DIR__ . /views/estadisticas_cursos.php'."
    ))

    titulo_nivel_2(doc, "5.2. Cómo añadir un nuevo formulario POST o acción de guardado/eliminación")
    p_apa(doc, (
        "Para agregar un nuevo botón que envíe datos o modifique la base de datos:"
    ))
    item_lista(doc, "1. En la vista (.php)", (
        "Crea tu etiqueta <form action='nombre-ruta-post' method='POST'> y nunca olvides colocar adentro <?= CSRF::campoOculto() ?>."
    ))
    item_lista(doc, "2. En index.php del módulo", (
        "Registra 'nombre-ruta-post' en getRutas() indicando únicamente 'controlador_path', 'controlador' y 'metodo' (sin poner 'vista')."
    ))
    item_lista(doc, "3. En el controlador y modelo", (
        "En el método del controlador verifica permisos con Auth::requierePrivilegioMinimo(), verifica el token con "
        "CSRF::validarToken($_POST['csrf_token'] ?? null), llama al método del modelo que hace el insert/update/delete, registra la "
        "acción con AuditLogger::registrar() y cierra siempre con header('Location: ruta-vista'); exit;."
    ))

    titulo_nivel_2(doc, "5.3. Cómo añadir un endpoint AJAX (JSON) para comunicarte con JavaScript")
    p_apa(doc, (
        "Cuando necesites que un script JavaScript de tu vista consulte o envíe datos al servidor usando fetch() sin recargar la página:"
    ))
    item_lista(doc, "1. Registra la ruta en getRutas()", (
        "Agrégala en el index.php de tu módulo sin clave 'vista' (por ejemplo, 'api-filtrar-datos')."
    ))
    item_lista(doc, "2. Programa el método en el controlador", (
        "Coloca en la primera línea header('Content-Type: application/json; charset=utf-8');, procesa la consulta, imprime la respuesta "
        "con echo json_encode(['ok' => true, 'data' => $resultados]); y coloca obligatoriamente exit; al final del método."
    ))
    item_lista(doc, "3. Llámalo desde tu archivo JS en assets/js/", (
        "Desde JavaScript simplemente haz fetch('api-filtrar-datos?param=valor') o por método POST enviando FormData."
    ))

    titulo_nivel_2(doc, "5.4. Cómo agregar un subítem al menú lateral y configurar sus activadores")
    p_apa(doc, (
        "Cuando agregues una nueva pantalla a un módulo y quieras que aparezca como una opción más dentro del menú desplegable de ese "
        "módulo en el sidebar izquierdo, abre modules/NombreModulo/index.php y dirígete al método getMenuConfig(). Allí debes hacer "
        "dos cosas en simultáneo:"
    ))
    item_lista(doc, "1. Agregar el subítem en 'subitems'", (
        "Añade un elemento al sub-arreglo 'subitems' con las claves 'ruta', 'titulo' y 'privilegio_minimo'."
    ))
    item_lista(doc, "2. Agregar el nombre de la ruta en 'activadores'", (
        "Justo arriba de 'subitems' verás un arreglo llamado 'activadores'. Agrega ahí también el nombre de tu nueva ruta (y el de "
        "cualquier sub-ruta interna como formularios de edición o detalle). Si olvidas poner tu ruta en 'activadores', cuando el "
        "usuario haga clic en tu nueva opción del menú, el acordeón del menú lateral se verá cerrado en lugar de mantenerse desplegado y resaltado."
    ))

    titulo_nivel_2(doc, "5.5. Cómo manejar configuraciones JSON propias y subida de archivos o imágenes")
    p_apa(doc, (
        "Muchos módulos de CIIDI (como Cursos, Articulos, RepositorioPST o VinculacionEmpresarial) tienen su propia pantalla de "
        "configuración para ajustar límites de paginación, tamaños máximos de archivo o textos dinámicos sin usar una tabla extra en "
        "PostgreSQL. El estándar en CIIDI es guardar un archivo JSON dentro de la carpeta del módulo (por ejemplo, "
        "modules/Cursos/config_cursos.json) o usar una pequeña clase ConfigService dentro de la carpeta services/ del módulo que lea "
        "el JSON con json_decode(file_get_contents(...), true) y lo combine con valores por defecto usando array_replace_recursive()."
    ))
    p_apa(doc, (
        "En cuanto a la subida de archivos (como portadas de cursos o imágenes de investigaciones), las imágenes que deben ser visibles "
        "públicamente en etiquetas <img> se guardan dentro de public/uploads/nombre_modulo/ (usando la constante BASE_PATH . '/public/uploads/...'), "
        "validando siempre antes la extensión real, el tipo MIME con finfo_file() y el peso en megabytes antes de mover el archivo con "
        "move_uploaded_file()."
    ))

    # =========================================================================
    # PARTE 6: SOLUCIÓN DE PROBLEMAS FRECUENTES (TROUBLESHOOTING)
    # =========================================================================
    titulo_nivel_1(doc, "Parte 6. Solución de Problemas Frecuentes (Guía Rápida de Diagnóstico para el Programador)")
    p_apa(doc, (
        "Para cerrar esta guía, aquí tienes el diccionario de diagnóstico rápido con los problemas más comunes que pueden ocurrirte "
        "al programar o modificar un módulo en CIIDI, por qué ocurren exactamente dentro del Core y cómo solucionarlos en segundos."
    ))

    titulo_nivel_2(doc, "6.1. Problemas con rutas, errores 404 y módulos que no aparecen")
    crear_tabla_apa(
        doc,
        ["Síntoma o Problema", "Causa en el Backend (Core)", "Solución Exacta"],
        [
            [
                "Creé mi carpeta en modules/ pero el sistema la ignora por completo (ni rutas ni menú).",
                "ModuleLoader::loadModules() (líneas 39-50) busca index.php y exige que devuelva una instancia que implemente ModuleContract.",
                "Verifica que exista modules/TuModulo/index.php, que la clase tenga implements ModuleContract y que la última línea sea return new TuModuloModule();"
            ],
            [
                "Entro a mi nueva ruta en el navegador y me sale la pantalla de Error 404.",
                "Router::resolve() (línea 19) busca la clave exacta en $rutasGlobales, o el módulo figura 'offline' en storage/config_system.json.",
                "1. Quita cualquier '/' inicial en la clave de getRutas() (usa 'mi-ruta', no '/mi-ruta'). 2. Revisa en SuperAdmin > Gestor de Módulos que el módulo esté Online."
            ],
            [
                "El botón de mi módulo no aparece en el menú lateral izquierdo (sidebar).",
                "sidebar.php (líneas 50-54) oculta el botón si el nivel del usuario actual es mayor al privilegio_minimo configurado.",
                "Si el botón debe ser visible para visitantes sin sesión iniciada, pon 'privilegio_minimo' => 999 en getMenuConfig()."
            ],
            [
                "Cuando entro a una vista de mi módulo, el menú desplegable del sidebar se cierra solo.",
                "sidebar.php (línea 74) comprueba in_array($ruta, $item['activadores']) para mantener la clase CSS 'open'.",
                "Agrega el nombre exacto de esa ruta dentro del arreglo 'activadores' en getMenuConfig() de tu index.php."
            ]
        ]
    )

    titulo_nivel_2(doc, "6.2. Problemas con vistas, estilos CSS, scripts JS y respuestas AJAX")
    crear_tabla_apa(
        doc,
        ["Síntoma o Problema", "Causa en el Backend (Core)", "Solución Exacta"],
        [
            [
                "Mi pantalla carga el encabezado y el sidebar, pero en el centro dice: 'Error: La vista no existe'.",
                "master.php (línea 44) hace file_exists($vista_modulo_path) y no encontró el archivo físico.",
                "Usa siempre __DIR__ . '/views/archivo.php' en la clave 'vista' de getRutas() y verifica que el nombre del archivo coincida en mayúsculas y minúsculas."
            ],
            [
                "Declaré mi archivo CSS o JS en getRutas(), pero los estilos o scripts no se aplican.",
                "AssetResolver (líneas 18 y 35) busca siempre dentro de ../modules/Carpeta/assets/css/ y ../modules/Carpeta/assets/js/.",
                "Asegúrate de haber colocado el archivo dentro de assets/css/ (o assets/js/) y en getRutas() escribe solo el nombre del archivo: 'css' => ['estilo.css']."
            ],
            [
                "Hago una petición fetch() AJAX a mi ruta API y la respuesta JSON falla porque viene pegada con todo el HTML de master.php.",
                "Dispatcher::dispatch() (líneas 78-79) siempre incluye master.php al terminar el método del controlador, a menos que detengas el flujo.",
                "Después de hacer echo json_encode(...); en el método de tu controlador, coloca siempre exit; (o return false;) en la línea siguiente."
            ],
            [
                "En mi vista PHP me sale 'Undefined variable' al intentar usar un dato que calculé en el controlador.",
                "Dispatcher::dispatch() (línea 64) usa extract($datosVista) sobre el arreglo retornado por el controlador.",
                "Asegúrate de que el método de tu controlador haga return ['nombre_variable' => $valor];. El nombre de la clave del array será el nombre de la variable $nombre_variable en la vista."
            ]
        ]
    )

    titulo_nivel_2(doc, "6.3. Problemas con base de datos, QueryBuilder, permisos 403 y tokens CSRF")
    crear_tabla_apa(
        doc,
        ["Síntoma o Problema", "Causa en el Backend (Core)", "Solución Exacta"],
        [
            [
                "Al ejecutar update() o delete() en mi modelo salta Error 500 diciendo 'Advertencia de Seguridad: Intentando hacer UPDATE/DELETE sin condiciones'.",
                "QueryBuilder (líneas 198 y 223) bloquea cualquier actualización o borrado si el arreglo $this->condiciones está vacío.",
                "Encadena siempre al menos un ->where('id', '=', $id) antes de llamar a ->update($datos) o ->delete()."
            ],
            [
                "Hago dos consultas seguidas con QueryBuilder en el mismo modelo y la segunda consulta sale con datos o filtros mezclados de la primera.",
                "No llamaste a ->tabla('nombre') al iniciar la segunda consulta y quedaron condiciones guardadas en la instancia.",
                "Inicia siempre cada consulta llamando primero a $this->qb->tabla('nombre_tabla'), ya que el método tabla() ejecuta automáticamente reset()."
            ],
            [
                "Mi formulario POST me rechaza siempre diciendo que el token CSRF es inválido.",
                "CSRF::validarToken() (línea 19) compara $_POST['csrf_token'] contra $_SESSION['csrf_token'].",
                "Coloca <?= CSRF::campoOculto() ?> dentro de las etiquetas <form>...</form> en tu vista y verifica que el método del formulario sea POST."
            ],
            [
                "Entro a una ruta administrativa con un usuario docente o administrador y me lanza directo a la pantalla 403 Acceso Denegado.",
                "Auth::requierePrivilegioMinimo() (líneas 107-143) comparó el nivel del usuario o buscó un permiso en la tabla matriz_rbac.",
                "Revisa qué nivel de privilegio pasaste al método y, si usaste los argumentos $permisoRuta y $moduloRuta, verifica en SuperAdmin que ese rol tenga activada la casilla en la matriz RBAC."
            ]
        ]
    )


def construir_documento():
    os.makedirs(os.path.dirname(OUTPUT_DOCX), exist_ok=True)
    doc = Document()

    # 1. Configurar estilo Normal base en Arial 12 pt negro
    estilo_normal = doc.styles["Normal"]
    estilo_normal.font.name = "Arial"
    estilo_normal.font.size = Pt(12)
    estilo_normal.font.color.rgb = NEGRO
    estilo_normal.paragraph_format.line_spacing = 2.0
    estilo_normal.paragraph_format.space_before = Pt(0)
    estilo_normal.paragraph_format.space_after = Pt(0)

    # 2. Configurar márgenes APA (2.54 cm en los 4 lados), encabezado vacío y pie de página solo con el número
    for seccion in doc.sections:
        seccion.top_margin = Cm(2.54)
        seccion.bottom_margin = Cm(2.54)
        seccion.left_margin = Cm(2.54)
        seccion.right_margin = Cm(2.54)
        seccion.different_first_page_header_footer = False

        # Encabezado completamente vacío (sin texto ni cosas raras)
        header = seccion.header
        for p_h in header.paragraphs:
            p_h.text = ""

        # Pie de página: únicamente el número de la página en Arial 12 negro
        footer = seccion.footer
        p_footer = footer.paragraphs[0]
        p_footer.text = ""
        p_footer.alignment = WD_ALIGN_PARAGRAPH.RIGHT
        p_footer.paragraph_format.line_spacing = 1.0
        p_footer.paragraph_format.space_before = Pt(0)
        p_footer.paragraph_format.space_after = Pt(0)
        p_footer.paragraph_format.first_line_indent = Cm(0)
        run_page = p_footer.add_run()
        configurar_fuente_run(run_page, bold=False)
        agregar_numero_pagina_limpio(run_page)

    # 3. Construir todas las partes del documento
    agregar_portada_e_indice(doc)
    agregar_parte_1_y_2(doc)
    agregar_parte_3(doc)
    agregar_parte_4_5_y_6(doc)

    # 4. Guardar archivo Word final
    doc.save(OUTPUT_DOCX)
    print(f"Documento generado exitosamente en: {OUTPUT_DOCX}")


if __name__ == "__main__":
    construir_documento()
