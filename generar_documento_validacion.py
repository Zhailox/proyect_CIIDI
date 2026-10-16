# -*- coding: utf-8 -*-
"""
Script: generar_documento_validacion.py
Propósito: Generar formalmente el documento Word de Capítulo 4 para Proyecto de Grado UPTTMBI:
           Sección 4.3.2 (Validación Técnica), 4.3.2.1 (Pruebas de Caja Blanca - SAST e Integridad de BD),
           4.3.2.2 (Pruebas de Caja Negra - DAST y Manejo de Excepciones HTTP),
           Tabla 15 (Pruebas de Seguridad, Excepciones HTTP y Normativas por Módulo) y
           Tabla 16 (Matriz Integral de Pruebas de Caja Blanca y Caja Negra por Módulo:
           Core, Autenticación, Artículos, RepositorioPST y SuperAdmin), además de la Sección 4.3.3.
Salida: Validacion_Tecnica_y_Usabilidad_CIIDI.docx
"""

import os
from docx import Document
from docx.shared import Pt, Cm, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.oxml import parse_xml
from docx.oxml.ns import nsdecls

# Constantes de diseño institucional UPTTMBI
COLOR_AZUL_PRINCIPAL = RGBColor(31, 78, 121)   # #1F4E79 (Azul Institucional)
COLOR_AZUL_SECUNDARIO = RGBColor(46, 91, 136)  # #2E5B88
COLOR_TEXTO_COMUN = RGBColor(38, 38, 38)       # #262626 (Gris oscuro legible)
HEX_AZUL_CABECERA = "1F4E79"
HEX_GRIS_ZEBRA = "F4F7FB"
HEX_BORDE_TABLA = "CCCCCC"


def aplicar_estilos_parrafo(p, espacio_despues=6, interlineado=1.5, alineacion=WD_ALIGN_PARAGRAPH.JUSTIFY):
    """Aplica formato académico estándar a los párrafos de texto corrido."""
    p.alignment = alineacion
    p.paragraph_format.line_spacing = interlineado
    p.paragraph_format.space_after = Pt(espacio_despues)
    p.paragraph_format.space_before = Pt(0)


def configurar_celda(cell, bg_color=None, top_padding=100, bottom_padding=100, left_padding=120, right_padding=120):
    """Configura el color de fondo y el espaciado interno (padding) de una celda de tabla."""
    tcPr = cell._tc.get_or_add_tcPr()
    if bg_color:
        shd = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{bg_color}"/>')
        tcPr.append(shd)
    tcMar = parse_xml(
        f'<w:tcMar {nsdecls("w")}>'
        f'<w:top w:w="{top_padding}" w:type="dxa"/>'
        f'<w:bottom w:w="{bottom_padding}" w:type="dxa"/>'
        f'<w:left w:w="{left_padding}" w:type="dxa"/>'
        f'<w:right w:w="{right_padding}" w:type="dxa"/>'
        f'</w:tcMar>'
    )
    tcPr.append(tcMar)


def aplicar_bordes_tabla(table, hex_color=HEX_BORDE_TABLA):
    """Aplica bordes finos horizontales limpios y profesionales a la tabla."""
    tblPr = table._tbl.tblPr
    borders = parse_xml(
        f'<w:tblBorders {nsdecls("w")}>'
        f'<w:top w:val="single" w:sz="6" w:space="0" w:color="{hex_color}"/>'
        f'<w:bottom w:val="single" w:sz="8" w:space="0" w:color="{hex_color}"/>'
        f'<w:insideH w:val="single" w:sz="4" w:space="0" w:color="{hex_color}"/>'
        f'<w:insideV w:val="none"/>'
        f'<w:left w:val="none"/>'
        f'<w:right w:val="none"/>'
        f'</w:tblBorders>'
    )
    tblPr.append(borders)


def agregar_numero_pagina(run):
    """Inserta el campo dinámico de número de página en Word."""
    fld1 = parse_xml(r'<w:fldChar %s w:fldCharType="begin"/>' % nsdecls('w'))
    instr = parse_xml(r'<w:instrText %s xml:space="preserve"> PAGE </w:instrText>' % nsdecls('w'))
    fld2 = parse_xml(r'<w:fldChar %s w:fldCharType="separate"/>' % nsdecls('w'))
    fld3 = parse_xml(r'<w:fldChar %s w:fldCharType="end"/>' % nsdecls('w'))
    run._r.append(fld1)
    run._r.append(instr)
    run._r.append(fld2)
    run._r.append(fld3)


def construir_documento():
    doc = Document()

    # 1. Configuración de márgenes estándar (2.5 cm en los cuatro lados)
    for seccion in doc.sections:
        seccion.top_margin = Cm(2.5)
        seccion.bottom_margin = Cm(2.5)
        seccion.left_margin = Cm(2.5)
        seccion.right_margin = Cm(2.5)
        seccion.different_first_page_header_footer = True

        # Pie de página institucional en páginas subsiguientes
        footer = seccion.footer
        p_footer = footer.paragraphs[0]
        p_footer.alignment = WD_ALIGN_PARAGRAPH.RIGHT
        p_footer.paragraph_format.line_spacing = 1.0
        p_footer.paragraph_format.space_after = Pt(0)
        run_footer_text = p_footer.add_run("CIIDI | Capítulo IV: Validación Técnica (Core, Autenticación, Artículos, RepositorioPST y SuperAdmin) — Pág. ")
        run_footer_text.font.name = "Calibri"
        run_footer_text.font.size = Pt(9)
        run_footer_text.font.color.rgb = RGBColor(120, 120, 120)
        run_page = p_footer.add_run()
        run_page.font.name = "Calibri"
        run_page.font.size = Pt(9)
        run_page.font.color.rgb = RGBColor(120, 120, 120)
        agregar_numero_pagina(run_page)

    # 2. Configuración de estilos base (Calibri 11 pt, 1.5 líneas)
    estilo_normal = doc.styles['Normal']
    estilo_normal.font.name = 'Calibri'
    estilo_normal.font.size = Pt(11)
    estilo_normal.font.color.rgb = COLOR_TEXTO_COMUN

    # =========================================================================
    # PORTADA INSTITUCIONAL SIMPLE
    # =========================================================================
    p_inst = doc.add_paragraph()
    p_inst.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_inst.paragraph_format.space_before = Pt(20)
    p_inst.paragraph_format.space_after = Pt(4)
    run_inst = p_inst.add_run("REPÚBLICA BOLIVARIANA DE VENEZUELA\n"
                              "MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA\n"
                              "UNIVERSIDAD POLITÉCNICA TERRITORIAL DE TRUJILLO\n"
                              "\"MARIO BRICEÑO IRAGORRY\"\n"
                              "PROGRAMA NACIONAL DE FORMACIÓN EN INFORMÁTICA")
    run_inst.font.name = 'Calibri'
    run_inst.font.size = Pt(10.5)
    run_inst.font.bold = True
    run_inst.font.color.rgb = COLOR_AZUL_PRINCIPAL

    p_div = doc.add_paragraph()
    p_div.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_div.paragraph_format.space_after = Pt(40)
    r_div = p_div.add_run("____________________________________________________")
    r_div.font.color.rgb = COLOR_AZUL_PRINCIPAL

    p_tit = doc.add_paragraph()
    p_tit.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_tit.paragraph_format.line_spacing = 1.3
    p_tit.paragraph_format.space_after = Pt(14)
    run_tit = p_tit.add_run("SISTEMA INTEGRADO DE GESTIÓN CIENTÍFICA Y TECNOLÓGICA (CIIDI)")
    run_tit.font.name = 'Calibri'
    run_tit.font.size = Pt(17)
    run_tit.font.bold = True
    run_tit.font.color.rgb = COLOR_AZUL_PRINCIPAL

    p_sub = doc.add_paragraph()
    p_sub.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_sub.paragraph_format.space_after = Pt(50)
    run_sub = p_sub.add_run("CAPÍTULO IV: VALIDACIÓN TÉCNICA Y DE USABILIDAD DEL SOFTWARE\n"
                            "PRUEBAS DE CAJA BLANCA (SAST), CAJA NEGRA (DAST), INTEGRIDAD DE BASE DE DATOS, MANEJO DE EXCEPCIONES HTTP Y NORMATIVAS\n"
                            "ALCANCE: CORE, AUTENTICACIÓN, ARTÍCULOS, REPOSITORIOPST Y SUPERADMIN")
    run_sub.font.name = 'Calibri'
    run_sub.font.size = Pt(11.5)
    run_sub.font.bold = True
    run_sub.font.color.rgb = COLOR_AZUL_SECUNDARIO

    p_meta = doc.add_paragraph()
    p_meta.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_meta.paragraph_format.space_before = Pt(80)
    p_meta.paragraph_format.space_after = Pt(0)
    r_meta = p_meta.add_run("Núcleo San Luis & Beatriz, Trujillo, Venezuela\n"
                            "Año Académico 2026")
    r_meta.font.size = Pt(10)
    r_meta.font.italic = True
    r_meta.font.color.rgb = RGBColor(100, 100, 100)

    doc.add_page_break()

    # =========================================================================
    # SECCIÓN 4.3.2 — VALIDACIÓN TÉCNICA
    # =========================================================================
    h1_tec = doc.add_heading(level=1)
    h1_tec.paragraph_format.space_before = Pt(12)
    h1_tec.paragraph_format.space_after = Pt(8)
    h1_tec.paragraph_format.keep_with_next = True
    r_h1 = h1_tec.add_run("4.3.2. Validación Técnica")
    r_h1.font.name = 'Calibri'
    r_h1.font.size = Pt(15)
    r_h1.font.bold = True
    r_h1.font.color.rgb = COLOR_AZUL_PRINCIPAL

    # Párrafo 1: Integración del requerimiento metodológico de Arias (2012) y Ramírez (2023)
    p_intro_1 = doc.add_paragraph()
    aplicar_estilos_parrafo(p_intro_1)
    p_intro_1.add_run(
        "La validación técnica de una solución informática constituye un proceso metodológico, sistemático y riguroso "
        "destinado a certificar la estabilidad funcional, la coherencia transaccional y la protección de la arquitectura "
        "del software ante diversas condiciones operativas y posibles amenazas. Para ello, se ejecutan pruebas sistemáticas "
        "de Caja Blanca y Caja Negra orientadas a asegurar tanto la integridad de los datos como el comportamiento predecible "
        "de la plataforma. En este sentido, Arias (2012) menciona que la recolección de datos técnicos debe ser “objetiva y "
        "verificable” (p. 67), razón por la cual se aplicaron protocolos de verificación empírica sobre el núcleo arquitectónico "
        "(Core) y los cuatro (4) módulos desarrollados por el equipo de trabajo: Autenticación, Artículos, RepositorioPST y "
        "SuperAdmin. De igual forma, Ramírez (2023) expone que “la aplicación sistemática de pruebas de seguridad, alineadas "
        "a estándares internacionales, es el único mecanismo objetivo para garantizar la invulnerabilidad de los datos en "
        "plataformas académicas e institucionales”."
    )

    # Párrafo 2: Integridad de Base de Datos (Triggers y Procedimientos Almacenados), SAST, DAST y Excepciones HTTP
    p_intro_2 = doc.add_paragraph()
    aplicar_estilos_parrafo(p_intro_2)
    p_intro_2.add_run(
        "Bajo esta postura metodológica, el protocolo de validación abarcó dos planos convergentes. En primer lugar, se "
        "auditó la integridad del motor relacional PostgreSQL, comprobando que los disparadores (triggers) de auditoría "
        "automática (tg_auditoria_recursos_insert, tg_auditoria_recursos_delete, tg_auditoria_usuarios_insert, "
        "tg_auditoria_usuarios_update y tg_auditoria_usuarios_delete), las funciones PL/pgSQL asociadas (fn_auditoria_recursos "
        "y fn_auditoria_usuarios) y el procedimiento almacenado transaccional (insertarproyectoaleatorio), en conjunto con "
        "las restricciones de integridad referencial (ON DELETE CASCADE y ON DELETE RESTRICT), preservan de forma inalterable "
        "la coherencia de la información académica. En segundo lugar, se integraron metodologías automatizadas de seguridad: "
        "el Análisis Estático de Seguridad de Aplicaciones (SAST, por sus siglas en inglés Static Application Security Testing), "
        "que permitió inspeccionar el código fuente desde el interior (caja blanca) para detectar y corregir funciones inseguras "
        "o fallas lógicas; y el Análisis Dinámico de Seguridad de Aplicaciones (DAST, por sus siglas en inglés Dynamic Application "
        "Security Testing), el cual evaluó el sistema en tiempo de ejecución simulando ataques externos (caja negra) para "
        "verificar la robustez de las sesiones, el cifrado de credenciales, la protección frente a Inyecciones SQL y Cross-Site "
        "Scripting (XSS), así como la gestión estandarizada de excepciones HTTP (400, 403, 404, 500 y 503) gobernada por el Core."
    )

    p_intro_3 = doc.add_paragraph()
    aplicar_estilos_parrafo(p_intro_3)
    p_intro_3.add_run(
        "A continuación, en la Tabla 15 se detalla la Matriz de Pruebas de Seguridad, Excepciones HTTP y Normativas, "
        "especificando los módulos evaluados, el conjunto de controles y ataques simulados, el marco normativo internacional "
        "y nacional aplicable (ISO/IEC 27001:2022, OWASP Top 10:2021, Ley Especial Contra los Delitos Informáticos y Ley de "
        "Infogobierno), y la eficacia de las mitigaciones verificadas en el código fuente."
    )

    # =========================================================================
    # TABLA 15: PRUEBAS DE SEGURIDAD, EXCEPCIONES HTTP Y NORMATIVAS
    # =========================================================================
    p_t15_titulo = doc.add_paragraph()
    aplicar_estilos_parrafo(p_t15_titulo, espacio_despues=2, alineacion=WD_ALIGN_PARAGRAPH.LEFT)
    r_t15_num = p_t15_titulo.add_run("Tabla 15\n")
    r_t15_num.font.bold = True
    r_t15_num.font.size = Pt(10.5)
    r_t15_num.font.color.rgb = COLOR_AZUL_PRINCIPAL
    r_t15_nom = p_t15_titulo.add_run("Pruebas de Seguridad, Manejo de Excepciones HTTP y Normativas\n")
    r_t15_nom.font.italic = True
    r_t15_nom.font.bold = True
    r_t15_nom.font.size = Pt(10.5)
    r_t15_resp = p_t15_titulo.add_run("Responsables: Equipo de desarrollo (Core, Autenticación, Artículos, RepositorioPST y SuperAdmin).")
    r_t15_resp.font.size = Pt(9.5)

    datos_tabla_15 = [
        ("N°", "Criterio de Seguridad / Excepción", "Módulos Evaluados", "Normativas Aplicables", "Mitigación Técnica Verificada"),
        (
            "01",
            "Inyección SQL (SQLi de 1er y 2do Orden)",
            "Core, Autenticación, Artículos, RepositorioPST y SuperAdmin",
            "ISO/IEC 27001:2022: Anexo A.8.28 (Codificación Segura).\nOWASP: A03:2021 (Injection).\nNacional: Ley Contra Delitos Informáticos (Art. 6: Acceso Indebido).",
            "Inhabilitación nativa de emulación PDO (PDO::ATTR_EMULATE_PREPARES = false) en Connection.php, sanitización regex de identificadores SQL en QueryBuilder.php, listas blancas de catálogos en ArticuloModel.php y bloqueo perimetral WAF en Kernel.php."
        ),
        (
            "02",
            "Cross-Site Scripting (XSS Reflejado y Almacenado)",
            "Core, Autenticación, Artículos, RepositorioPST y SuperAdmin",
            "ISO/IEC 27001:2022: Anexo A.8.28 y A.8.20.\nOWASP: A03:2021 / A07:2021.\nNacional: Ley Contra Delitos Informáticos (Art. 7: Sabotaje o Daño a Sistemas).",
            "Escape contextual obligatorio (htmlspecialchars con ENT_QUOTES | ENT_HTML5) en vistas de los 4 módulos, cabeceras HTTP (X-XSS-Protection: 1; mode=block, X-Content-Type-Options: nosniff, X-Frame-Options: SAMEORIGIN) y filtro de etiquetas <script> en WAF."
        ),
        (
            "03",
            "Falsificación de Peticiones en Sitios Cruzados (CSRF)",
            "Core, Autenticación, Artículos, RepositorioPST y SuperAdmin",
            "ISO/IEC 27001:2022: Anexo A.8.24 y A.5.15.\nOWASP: A01:2021 (Broken Access Control).\nNacional: Ley de Infogobierno (Arts. 26 y 34: Seguridad en la Gestión Pública).",
            "Configuración de cookies SameSite=Lax y HttpOnly en Kernel.php; generación criptográfica de tokens únicos (random_bytes(32)) y validación en tiempo constante (hash_equals) en CSRF.php para peticiones POST/PUT/DELETE y restablecimiento de clave."
        ),
        (
            "04",
            "Subida de Archivos Maliciosos, Zip Bomb y Entidades XML (XXE)",
            "Artículos, RepositorioPST, Autenticación y SuperAdmin",
            "ISO/IEC 27001:2022: Anexo A.8.7 (Protección contra Malware).\nOWASP: A04:2021 (Insecure Design) y A05:2021.\nNacional: Ley Contra Delitos Informáticos (Art. 7: Daño a Sistemas).",
            "Inspección binaria profunda de Magic Bytes mediante finfo_file() en DetallePSTController y ArticulosController; reprocesamiento de imágenes GD en PerfilController; límite de descompresión XML (15 MB) y bandera LIBXML_NONET en ExtractorPST.php."
        ),
        (
            "05",
            "Integridad Relacional, Triggers y Procedimientos Almacenados",
            "Core (Esquema PostgreSQL), Autenticación, RepositorioPST y SuperAdmin",
            "ISO/IEC 27001:2022: Anexo A.8.15 (Registros de Auditoría) y A.8.16.\nOWASP: A08:2021 (Software and Data Integrity Failures).\nNacional: Ley Contra Delitos Informáticos (Art. 9) y Ley de Infogobierno.",
            "Sincronización automática de variable de sesión PostgreSQL (app.usuario_actual) en Connection.php; ejecución de triggers PL/pgSQL (fn_auditoria_recursos y fn_auditoria_usuarios) con respaldo JSONB en tabla auditoria; procedimiento transaccional insertarproyectoaleatorio y cadena SHA-256 en AuditLogger."
        ),
        (
            "06",
            "Robustez de Sesiones, Control de Acceso (RBAC) y Fuerza Bruta",
            "Core, Autenticación, SuperAdmin, Artículos y RepositorioPST",
            "ISO/IEC 27001:2022: Anexo A.5.15 (Control de Acceso) y A.8.5 (Autenticación Segura).\nOWASP: A07:2021 (Identification & Auth Failures).\nNacional: Ley Contra Delitos Informáticos (Art. 6 y Art. 11).",
            "Regeneración de ID de sesión tras login, huella digital SHA-256 (User-Agent + subred IP /24) en SessionManager.php, revocación remota instantánea (revoked_sessions.json), matriz jerárquica matriz_rbac (Auth.php) y bloqueo progresivo de IP/cuenta en RateLimiter.php."
        ),
        (
            "07",
            "Cifrado de Credenciales, Secretos y Tokens de Recuperación",
            "Core, Autenticación y SuperAdmin",
            "ISO/IEC 27001:2022: Anexo A.8.24 (Uso de la Criptografía) y A.5.17.\nOWASP: A02:2021 (Cryptographic Failures).\nNacional: Constitución RBV (Art. 60) y Ley Contra Delitos Informáticos (Art. 13).",
            "Hashing adaptativo Bcrypt (PASSWORD_BCRYPT) para claves de usuario y cuenta Break-Glass; cifrado simétrico AES-256-CBC con vector IV aleatorio (Crypto.php) para credenciales SMTP y BD; hashing SHA-256 para tokens de recuperación e invitación docente."
        ),
        (
            "08",
            "Path Traversal (LFI), SSRF e Inyección de Fórmulas CSV",
            "Core, Artículos, RepositorioPST y SuperAdmin",
            "ISO/IEC 27001:2022: Anexo A.8.12 (Prevención de Fuga de Datos) y A.8.28.\nOWASP: A01:2021, A10:2021 (SSRF) y A03:2021.\nNacional: Ley Contra Delitos Informáticos (Arts. 6 y 7).",
            "Saneamiento basename() y validación de prefijos de directorio en DetallePSTController y ModulosController; validación de esquema http/https y bloqueo de loopback (localhost/127.0.0.1/::1) en ArticulosController; escape de prefijos (=, +, -, @) en exportaciones CSV de LogsController y ReportesController."
        ),
        (
            "09",
            "Manejo de Excepciones HTTP (400, 403, 404, 500, 503) y Resiliencia",
            "Core, Autenticación, Artículos, RepositorioPST y SuperAdmin",
            "ISO/IEC 27001:2022: Anexo A.8.12, A.5.29 y A.5.30 (Continuidad TIC).\nOWASP: A05:2021 (Security Misconfiguration) y A09:2021.\nNacional: Ley de Infogobierno (Art. 18: Continuidad del Servicio).",
            "Gestión centralizada en Kernel, Router, Dispatcher, Auth, FeatureFlagMiddleware y MaintenanceService: emisión de códigos HTTP exactos (400, 403, 404, 500, 503), supresión de stack traces en producción (APP_DEBUG=false) y activación de consola de emergencia Break-Glass ante fallos de BD."
        )
    ]

    tabla_15 = doc.add_table(rows=len(datos_tabla_15), cols=5)
    tabla_15.alignment = WD_TABLE_ALIGNMENT.CENTER
    aplicar_bordes_tabla(tabla_15)

    anchos_t15 = [Cm(0.9), Cm(3.1), Cm(2.7), Cm(4.1), Cm(5.2)]

    for row_idx, fila in enumerate(datos_tabla_15):
        tr = tabla_15.rows[row_idx]
        tr._tr.get_or_add_trPr().append(parse_xml(f'<w:cantSplit {nsdecls("w")}/>'))

        for col_idx, texto in enumerate(fila):
            celda = tr.cells[col_idx]
            celda.width = anchos_t15[col_idx]
            p_celda = celda.paragraphs[0]
            p_celda.paragraph_format.line_spacing = 1.15
            p_celda.paragraph_format.space_before = Pt(2)
            p_celda.paragraph_format.space_after = Pt(2)

            if row_idx == 0:
                p_celda.alignment = WD_ALIGN_PARAGRAPH.CENTER
                configurar_celda(celda, bg_color=HEX_AZUL_CABECERA, top_padding=110, bottom_padding=110)
                r_txt = p_celda.add_run(texto)
                r_txt.font.bold = True
                r_txt.font.size = Pt(9)
                r_txt.font.color.rgb = RGBColor(255, 255, 255)
            else:
                bg = HEX_GRIS_ZEBRA if row_idx % 2 == 1 else "FFFFFF"
                configurar_celda(celda, bg_color=bg, top_padding=90, bottom_padding=90)
                if col_idx == 0:
                    p_celda.alignment = WD_ALIGN_PARAGRAPH.CENTER
                    r_txt = p_celda.add_run(texto)
                    r_txt.font.bold = True
                    r_txt.font.size = Pt(8.5)
                elif col_idx in (1, 2):
                    p_celda.alignment = WD_ALIGN_PARAGRAPH.LEFT
                    r_txt = p_celda.add_run(texto)
                    r_txt.font.bold = (col_idx == 1)
                    r_txt.font.size = Pt(8.5)
                else:
                    p_celda.alignment = WD_ALIGN_PARAGRAPH.LEFT
                    r_txt = p_celda.add_run(texto)
                    r_txt.font.size = Pt(8.5)

    p_t15_fuente = doc.add_paragraph()
    aplicar_estilos_parrafo(p_t15_fuente, espacio_despues=8, alineacion=WD_ALIGN_PARAGRAPH.LEFT)
    r_f15 = p_t15_fuente.add_run("Fuentes: Los autores (2026).")
    r_f15.font.size = Pt(9.5)
    r_f15.font.italic = True

    # Párrafos de análisis post-Tabla 15 con cita a Suárez y Pineda (2024)
    p_post_t15_1 = doc.add_paragraph()
    aplicar_estilos_parrafo(p_post_t15_1)
    p_post_t15_1.add_run(
        "A partir de los resultados consolidados en la Tabla 15, se comprueba empíricamente que el sistema es capaz de "
        "repeler de forma exitosa ataques críticos y gestionar de manera resiliente condiciones de borde a lo largo del "
        "Core y los módulos de Autenticación, Artículos, RepositorioPST y SuperAdmin. La articulación entre el Cortafuegos "
        "de Aplicación Web (WAF), las sentencias preparadas nativas en PostgreSQL, los disparadores (triggers) de auditoría "
        "con trazabilidad de sesión, el cifrado simétrico AES-256-CBC y el manejo controlado de excepciones HTTP (400, 403, "
        "404, 500 y 503) demuestra la solidez estructural del código fuente."
    )

    p_post_t15_2 = doc.add_paragraph()
    aplicar_estilos_parrafo(p_post_t15_2)
    p_post_t15_2.add_run(
        "De esta misma manera, Suárez y Pineda (2024) afirman que “la integración de esquemas de defensa en profundidad "
        "en proyectos sociotecnológicos no solo salvaguarda el ecosistema digital, sino que legitima la confiabilidad de la "
        "herramienta frente a los usuarios finales y la comunidad”. Por consiguiente, la superación de estas pruebas técnicas "
        "verifica que el producto entregado a la Universidad Politécnica Territorial de Trujillo “Mario Briceño Iragorry” "
        "representa un entorno seguro y tolerante a fallos, garantizando la confidencialidad, disponibilidad e integridad "
        "del acervo científico e investigativo de la institución."
    )

    # =========================================================================
    # SUBSECCIÓN 4.3.2.1 — PRUEBAS DE CAJA BLANCA (SAST E INTEGRIDAD DE BD)
    # =========================================================================
    h2_sast = doc.add_heading(level=2)
    h2_sast.paragraph_format.space_before = Pt(12)
    h2_sast.paragraph_format.space_after = Pt(6)
    h2_sast.paragraph_format.keep_with_next = True
    r_sast = h2_sast.add_run("4.3.2.1. Pruebas de Caja Blanca")
    r_sast.font.name = 'Calibri'
    r_sast.font.size = Pt(13)
    r_sast.font.bold = True
    r_sast.font.color.rgb = COLOR_AZUL_PRINCIPAL

    p_sast_intro = doc.add_paragraph()
    aplicar_estilos_parrafo(p_sast_intro)
    p_sast_intro.add_run(
        "Las pruebas de Caja Blanca se fundamentaron en la inspección estática del código fuente (Static Application "
        "Security Testing, o por sus siglas en inglés SAST) y en la auditoría estructural del esquema relacional PostgreSQL, "
        "focalizándose de manera iterativa sobre el microkernel del sistema (core/) y los cuatro (4) módulos desarrollados "
        "por el equipo: Autenticacion, Articulos, RepositorioPST y SuperAdmin. El objetivo principal consistió en verificar "
        "desde el interior del software que las estructuras de control, las abstracciones de persistencia, los disparadores "
        "de base de datos y las funciones criptográficas mitigan de raíz cualquier vulnerabilidad lógica o de construcción.\n\n"
        "Durante la revisión estática e iterativa del código fuente y la base de datos, se verificaron, corrigieron y "
        "certificaron los siguientes aspectos técnicos:"
    )

    items_sast = [
        ("Capa de Persistencia, Sentencias Preparadas Nativas y Blindaje de Identificadores: ",
         "Se auditó el componente core/Database/Connection.php y el constructor fluido core/Database/QueryBuilder.php, certificando que implementan de forma obligatoria la directiva PDO::ATTR_EMULATE_PREPARES = false junto con PDO::ATTR_ERRMODE = PDO::ERRMODE_EXCEPTION. Esta configuración garantiza una separación física en el motor PostgreSQL entre las instrucciones SQL compiladas y los parámetros suministrados por el usuario. Adicionalmente, durante la auditoría SAST se reforzó QueryBuilder.php mediante expresiones regulares estrictas (/^[a-zA-Z0-9_\\.]+$/) sobre nombres de tablas y columnas en operaciones select, insert y update, y se incorporó una lista blanca explícita de catálogos (categorias, etiquetas y editoriales) en ArticuloModel::obtenerCatalogoPaginado(), erradicando cualquier vector de inyección SQL de segundo orden."),
        ("Integridad de Base de Datos, Triggers PL/pgSQL y Procedimientos Almacenados: ",
         "Siguiendo los criterios de verificabilidad técnica de Arias (2012), se inspeccionó el esquema relacional (core/Installer/schema.sql y lote de migraciones 001 a 006). Se verificó la operatividad de las funciones disparadoras public.fn_auditoria_recursos() y public.fn_auditoria_usuarios(), vinculadas a los triggers tg_auditoria_recursos_insert, tg_auditoria_recursos_delete, tg_auditoria_usuarios_insert, tg_auditoria_usuarios_update y tg_auditoria_usuarios_delete, los cuales capturan automáticamente instantáneas en formato JSONB (datos_anteriores y datos_nuevos) ante cualquier mutación en las tablas críticas. Como hallazgo de caja blanca, se sincronizó en Connection::getInstance() la inyección automática de la variable de entorno de sesión PostgreSQL (SELECT set_config('app.usuario_actual', ?, false)), garantizando que los triggers de base de datos registren de forma exacta el identificador del usuario autenticado. Asimismo, se verificó la atomicidad del procedimiento almacenado public.insertarproyectoaleatorio(timestamp) y la coherencia de las claves foráneas con reglas ON DELETE CASCADE (en detalles_proyectos, detalles_articulos y tablas pivote) y ON DELETE RESTRICT (en catálogos maestros como roles y tipo_recurso)."),
        ("Validación Criptográfica y Binaria de Archivos (Magic Bytes, Anti-ZipBomb y Anti-XXE): ",
         "En los controladores encargados de la ingesta documental (DetallePSTController.php, ArticulosController.php y PerfilController.php), se comprobó el uso obligatorio de la extensión nativa fileinfo (finfo_file(FILEINFO_MIME_TYPE)) para inspeccionar los primeros bytes hexadecimales (Magic Bytes) de cada archivo cargado en el flujo temporal, impidiendo el camuflaje de scripts ejecutables (.php, .phtml, .sh) bajo extensiones aparentemente legítimas (.pdf, .docx, .png). En el servicio ExtractorPST.php, se verificó la protección contra bombas de descompresión (Zip Bomb) limitando el tamaño del flujo word/document.xml descomprimido a 15 MB, la deshabilitación de entidades externas XML mediante la bandera LIBXML_NONET (prevención de ataques XXE) y el acotamiento de lectura a las primeras 15 páginas PDF para evitar desbordamientos de memoria RAM."),
        ("Cifrado de Credenciales, Robustez de Sesiones y Protección Anti-CSRF: ",
         "Se inspeccionó el módulo Autenticacion (LoginController.php, PerfilController.php) y los servicios del Core (Crypto.php, CSRF.php, SessionManager.php, AuditLogger.php). Se certificó que las contraseñas de usuarios y de la cuenta de rescate local (Break-Glass) se almacenan exclusivamente mediante hashes adaptativos Bcrypt (PASSWORD_BCRYPT), mientras que las credenciales sensibles de conexión SMTP y PostgreSQL se resguardan en disco bajo cifrado simétrico AES-256-CBC con vectores de inicialización (IV) aleatorios de 16 bytes derivados de una clave maestra en .env. En la auditoría de caja blanca se completó el blindaje del flujo LoginController::procesarRestablecerClave() y su vista restablecer_clave.php integrando validación estricta de token CSRF (CSRF::validarToken con comparación criptográfica hash_equals), y se corrigió la retroalimentación visual de estado en recuperar_cuenta.php."),
        ("Saneamiento de Rutas (Anti-LFI / Anti-SSRF), Escape Contextual XSS y Neutralización CSV: ",
         "En el motor de renderizado de vistas y en los controladores de Articulos, RepositorioPST y SuperAdmin, se auditó la codificación de salida mediante htmlspecialchars(..., ENT_QUOTES | ENT_HTML5, 'UTF-8'). Durante la inspección estática se homologó la validación anti-SSRF en ArticulosController::actualizar() (restringiendo esquemas a http/https y bloqueando referencias de loopback localhost, 127.0.0.1 y ::1), se blindó DetallePSTController (métodos crear y crear_ajax) verificando prefijos de directorio permitidos (storage/documentos/tmp/ y storage/documentos/pst/) sin secuencias '..', se aseguró ModulosController.php resolviendo en el servidor las rutas de configuración JSON y validando identificadores de módulo con expresiones regulares, y se estandarizó la neutralización de fórmulas ejecutables (=, +, -, @) en la exportación de reportes CSV dentro de LogsController.php y ReportesController.php.")
    ]

    for negrita, texto in items_sast:
        p_item = doc.add_paragraph(style='List Bullet')
        aplicar_estilos_parrafo(p_item, espacio_despues=4)
        r_bold = p_item.add_run(negrita)
        r_bold.font.bold = True
        p_item.add_run(texto)

    p_sast_cierre = doc.add_paragraph()
    aplicar_estilos_parrafo(p_sast_cierre)
    p_sast_cierre.add_run(
        "Esta revisión estática de caja blanca, validada sintácticamente mediante el analizador del intérprete CLI de PHP "
        "(php -l con cero errores en la totalidad de los archivos intervenidos) y contrastada contra el catálogo de metadatos "
        "pg_trigger y pg_proc de PostgreSQL, permitió certificar que la arquitectura interna cumple estrictamente con los "
        "principios de codificación segura antes de someter la plataforma a pruebas dinámicas."
    )

    # =========================================================================
    # SUBSECCIÓN 4.3.2.2 — PRUEBAS DE CAJA NEGRA (DAST Y EXCEPCIONES HTTP)
    # =========================================================================
    h2_dast = doc.add_heading(level=2)
    h2_dast.paragraph_format.space_before = Pt(12)
    h2_dast.paragraph_format.space_after = Pt(6)
    h2_dast.paragraph_format.keep_with_next = True
    r_dast = h2_dast.add_run("4.3.2.2. Pruebas de Caja Negra")
    r_dast.font.name = 'Calibri'
    r_dast.font.size = Pt(13)
    r_dast.font.bold = True
    r_dast.font.color.rgb = COLOR_AZUL_PRINCIPAL

    p_dast_1 = doc.add_paragraph()
    aplicar_estilos_parrafo(p_dast_1)
    p_dast_1.add_run(
        "Para complementar la revisión estática del código, se ejecutaron las Pruebas Dinámicas de Seguridad de Aplicaciones "
        "(Dynamic Application Security Testing, o DAST por sus siglas en inglés) junto con pruebas funcionales de partición "
        "de equivalencia y análisis de valores límite, enmarcadas dentro de la metodología de Pruebas de Caja Negra. Estas "
        "pruebas se llevaron a cabo en tiempo de ejecución asumiendo la postura de un agente externo o usuario sin conocimiento "
        "de la estructura interna, evaluando todos los puntos de entrada HTTP de los módulos Autenticación, Artículos, "
        "RepositorioPST y SuperAdmin, así como las respuestas generadas por el microkernel (Core).\n\n"
        "Durante la ejecución de los ensayos dinámicos y simulaciones de ataque, el comportamiento verificado del sistema "
        "se estructuró en los siguientes mecanismos de defensa y respuesta:"
    )

    items_dast = [
        ("Intercepción Perimetral WAF y Mitigación de Fuerza Bruta (RateLimiter): ",
         "El núcleo de intercepción implementado en Kernel::run() y RateLimiter inspeccionó metódicamente cada solicitud entrante (GET, POST, cabeceras HTTP y cookies). Ante la inyección simulada de vectores tautológicos SQL, consultas UNION SELECT, secuencias de escape de directorios (../), etiquetas <script> y comandos de sistema, el WAF abortó de inmediato el procesamiento, registró el incidente con firma HMAC-SHA256 en AuditLogger y bloqueó preventivamente la dirección IP infractora tras alcanzar el umbral configurado de intentos fallidos."),
        ("Verificación de Robustez de Sesiones, RBAC y Cifrado en Tiempo de Ejecución: ",
         "Se sometió el módulo de Autenticación y el panel SuperAdmin a pruebas de secuestro de sesión (modificación deliberada del encabezado User-Agent), reutilización de sesiones revocadas remotamente (revoked_sessions.json) y acceso forzado a rutas administrativas con cuentas de privilegio inferior. En todos los escenarios, SessionManager destruyó las sesiones anómalas redirigiendo al flujo de acceso con código ?error=sesion_expirada, mientras que el motor RBAC (Auth::requierePrivilegioMinimo y Auth::tienePermiso) bloqueó cualquier escalada vertical u horizontal de privilegios."),
        ("Gestión Centralizada de Excepciones HTTP en el Core (Códigos 400, 403, 404, 500 y 503): ",
         "Se validó dinámicamente la respuesta del enrutador (Router.php), el despachador (Dispatcher.php) y los controladores modulares frente a peticiones anómalas o estados de contingencia, certificando la emisión exacta de los códigos de estado del protocolo HTTP sin filtrar rutas físicas ni trazas de código (stack traces):\n"
         "  • HTTP 400 (Bad Request): Emitido por DetallePSTController::verPdf() cuando se omiten o corrompen los parámetros de identificación documental.\n"
         "  • HTTP 403 (Forbidden): Emitido por el WAF perimetral y RateLimiter (renderizando core/Views/seguridad_bloqueo.php), por el motor de autorización Auth::render403() ante violaciones de nivel de privilegio o permisos granulares RBAC (renderizando core/Views/403.php), y ante intentos de descarga directa de manuscritos PDF con política de descarga restringida.\n"
         "  • HTTP 404 (Not Found): Emitido por Router::resolve() y Dispatcher::dispatch() ante rutas inexistentes en la tabla de enrutamiento (renderizando core/Views/404.php), así como por ArticulosController::leer() y DetallePSTController::ver() cuando se consultan identificadores de artículos o proyectos PST inexistentes o inactivos.\n"
         "  • HTTP 500 (Internal Server Error): Emitido de forma controlada por Dispatcher::handleDatabaseException() y Dispatcher::handleGeneralException() ante caídas del servicio PostgreSQL o excepciones críticas en tiempo de ejecución (renderizando core/Views/500.php o respuesta JSON estructurada en peticiones AJAX), registrando el evento en storage/logs/db_errors.log y habilitando el acceso de rescate local (Break-Glass) para el SuperAdmin.\n"
         "  • HTTP 503 (Service Unavailable): Emitido por MaintenanceService::intercept() durante ventanas de mantenimiento global activas o programadas (renderizando core/Views/mantenimiento.php con expulsión automática de sesiones no administrativas) y por FeatureFlagMiddleware::intercept() cuando un módulo o ruta específica es conmutado en caliente a estado offline o solo_lectura (renderizando core/Views/deshabilitado.php).")
    ]

    for negrita, texto in items_dast:
        p_item = doc.add_paragraph(style='List Bullet')
        aplicar_estilos_parrafo(p_item, espacio_despues=4)
        r_bold = p_item.add_run(negrita)
        r_bold.font.bold = True
        p_item.add_run(texto)

    p_dast_2 = doc.add_paragraph()
    aplicar_estilos_parrafo(p_dast_2)
    p_dast_2.add_run(
        "Con el propósito de evidenciar de forma desglosada y verificable los protocolos ejecutados sobre cada componente "
        "arquitectónico intervenido por el equipo de desarrollo, en la Tabla 16 se presenta la Matriz Integral de Pruebas "
        "de Caja Blanca (SAST) y Caja Negra (DAST) por Módulo."
    )

    # =========================================================================
    # TABLA 16: MATRIZ DE PRUEBAS DE CAJA BLANCA Y CAJA NEGRA POR MÓDULO
    # =========================================================================
    p_t16_titulo = doc.add_paragraph()
    aplicar_estilos_parrafo(p_t16_titulo, espacio_despues=2, alineacion=WD_ALIGN_PARAGRAPH.LEFT)
    r_t16_num = p_t16_titulo.add_run("Tabla 16\n")
    r_t16_num.font.bold = True
    r_t16_num.font.size = Pt(10.5)
    r_t16_num.font.color.rgb = COLOR_AZUL_PRINCIPAL
    r_t16_nom = p_t16_titulo.add_run("Matriz de Pruebas de Caja Blanca (SAST) y Caja Negra (DAST) por Módulo del Sistema\n")
    r_t16_nom.font.italic = True
    r_t16_nom.font.bold = True
    r_t16_nom.font.size = Pt(10.5)
    r_t16_resp = p_t16_titulo.add_run("Responsables: Equipo de desarrollo (Core, Autenticación, Artículos, RepositorioPST y SuperAdmin).")
    r_t16_resp.font.size = Pt(9.5)

    datos_tabla_16 = [
        ("Módulo / Alcance", "Metodología / Tipo", "Componente / Caso de Prueba", "Protocolo de Prueba y Mejora Aplicada", "Resultado y Estado"),
        # 1. CORE & BASE DE DATOS
        (
            "Core\n(Microkernel y BD PostgreSQL)",
            "Caja Blanca\n(SAST + BD)",
            "Connection.php, QueryBuilder.php y Triggers PL/pgSQL (schema.sql)",
            "Inspección de PDO::ATTR_EMULATE_PREPARES = false; sanitización regex de identificadores SQL en QueryBuilder; sincronización de variable de sesión PostgreSQL app.usuario_actual para los triggers fn_auditoria_recursos() y fn_auditoria_usuarios(); verificación del procedimiento insertarproyectoaleatorio.",
            "Superado (100%). Separación total código/datos; triggers registran JSONB y usuario responsable en tabla auditoria."
        ),
        (
            "Core\n(Microkernel y Enrutamiento)",
            "Caja Blanca\n(SAST)",
            "Dispatcher.php, Crypto.php, CSRF.php y AuditLogger.php",
            "Auditoría de cifrado AES-256-CBC con IV aleatorio de 16 bytes; tokens CSRF con random_bytes(32) y hash_equals; encadenamiento criptográfico SHA-256 en AuditLogger; incorporación de captura global catch(Throwable) en Dispatcher::handleGeneralException().",
            "Superado (100%). Cadena de logs verificada íntegra; excepciones generales capturadas sin filtrar stack traces."
        ),
        (
            "Core\n(WAF y Excepciones HTTP)",
            "Caja Negra\n(DAST)",
            "Kernel.php, Router.php, Auth.php, FeatureFlagMiddleware y MaintenanceService",
            "Simulación de payloads SQLi/XSS/LFI contra WAF; invocación de rutas inexistentes, accesos sin privilegio RBAC, desconexión simulada de BD y activación de modo mantenimiento / ruta offline para validar códigos HTTP 403, 404, 500 y 503.",
            "Superado (100%). Respuestas HTTP 403, 404, 500 (con modo Break-Glass) y 503 emitidas con vistas institucionales seguras."
        ),
        # 2. AUTENTICACIÓN
        (
            "Autenticación\n(modules/Autenticacion)",
            "Caja Blanca\n(SAST)",
            "LoginController.php, PerfilController.php, UsuarioModel.php y Vistas",
            "Verificación de hashing Bcrypt (password_hash / password_verify), regeneración de sesión, expiración de tokens SHA-256 (1h recuperación / 48h invitación) y reprocesamiento GD de avatares. Se añadió validación CSRF en procesarRestablecerClave() y alerta visual de éxito en recuperar_cuenta.php.",
            "Superado (100%). Credenciales y formularios de restablecimiento blindados contra CSRF y manipulación de archivos."
        ),
        (
            "Autenticación\n(modules/Autenticacion)",
            "Caja Negra\n(DAST)",
            "Endpoints /login, /recuperar-cuenta, /restablecer-clave y /perfil",
            "Ensayo de fuerza bruta (>5 intentos fallidos), envío de formulario POST de cambio de clave sin token CSRF, alteración de User-Agent en sesión activa y subida de script renombrado como imagen de perfil (.jpg).",
            "Superado (100%). Bloqueo temporal de IP en RateLimiter (HTTP 403), rechazo de petición sin CSRF, expulsión de sesión anómala y bloqueo de falso avatar."
        ),
        # 3. ARTÍCULOS
        (
            "Artículos\n(modules/Articulos)",
            "Caja Blanca\n(SAST)",
            "ArticulosController.php, ConfiguracionController.php y ArticuloModel.php",
            "Inspección de transacciones PDO en publicación/edición de artículos y escape XSS en leer_articulo.php. Se incorporó lista blanca estricta de tablas en ArticuloModel::obtenerCatalogoPaginado(), validación anti-SSRF en actualizar() y código HTTP 404 en leer().",
            "Superado (100%). Consultas parametrizadas, catálogos restringidos por whitelist y prevención de SSRF / Path Traversal."
        ),
        (
            "Artículos\n(modules/Articulos)",
            "Caja Negra\n(DAST)",
            "Consulta pública (/leer-articulo), carga multiparte y enlaces externos",
            "Petición GET con ID de artículo inexistente (?id=999999), intento de inyección de URL interna (http://127.0.0.1/admin) como enlace externo y subida de archivo ejecutable camuflado con extensión .pdf.",
            "Superado (100%). Emisión de estado HTTP 404 con vista de error, rechazo de URL local (anti-SSRF) y bloqueo por inspección binaria finfo_file()."
        ),
        # 4. REPOSITORIOPST
        (
            "RepositorioPST\n(modules/RepositorioPST)",
            "Caja Blanca\n(SAST)",
            "DetallePSTController.php, BusquedaGlobalController, ExtractorPST y EmbeddingService",
            "Auditoría de extracción PDF/DOCX (protección Zip Bomb 15 MB y LIBXML_NONET anti-XXE) e inferencia vectorial ONNX (384-d normalizado L2 con índice HNSW en pgvector). Se añadió validación finfo_file(), control anti-traversal de rutas temporales y código HTTP 404 en ver().",
            "Superado (100%). Extracción documental acotada en memoria (<3.8 MB por hilo), búsqueda semántica precisa y rutas aseguradas."
        ),
        (
            "RepositorioPST\n(modules/RepositorioPST)",
            "Caja Negra\n(DAST)",
            "Visor documental (/ver-pdf), ingesta AJAX (crear_ajax) y buscador IA",
            "Invocación de /ver-pdf sin parámetros (HTTP 400), intento de descarga con política restringida (HTTP 403), consulta de PST inexistente (HTTP 404), manipulación de ruta_archivo_temporal con '../' y búsqueda semántica con sinónimos conceptuales.",
            "Superado (100%). Códigos HTTP 400/403/404 verificados, rechazo de traversal en archivo temporal y recuperación vectorial con similitud >0.84."
        ),
        # 5. SUPERADMIN
        (
            "SuperAdmin\n(modules/SuperAdmin)",
            "Caja Blanca\n(SAST)",
            "AdminController, GestorUsuarios, ModulosController, Logs, Reportes y BackupService",
            "Revisión de control de privilegio 0 (Auth::requierePrivilegioMinimo(0)), re-autenticación de clave en cambios críticos de BD/Break-Glass y validación dry-run de dumps SQL/GZIP. Se blindó ModulosController contra escritura arbitraria de rutas JSON y se añadió sanitización anti-CSV Injection en ReportesController.",
            "Superado (100%). Operaciones administrativas protegidas por privilegio nivel 0, rutas de módulos validadas por regex y exportaciones CSV seguras."
        ),
        (
            "SuperAdmin\n(modules/SuperAdmin)",
            "Caja Negra\n(DAST)",
            "Panel administrativo, Matriz RBAC, Visor WAF, Backups y Exportación CSV/PDF",
            "Intento de acceso a /panel-admin con sesión de nivel estudiante/docente (HTTP 403), intento de auto-eliminación o auto-revocación de sesión del propio SuperAdmin, restauración de archivo .sql corrupto y exportación de registros con prefijos '=CMD|'.",
            "Superado (100%). Acceso denegado con HTTP 403, bloqueo de auto-sabotaje de cuenta activa, cancelación preventiva en dry-run de backup y neutralización de fórmulas CSV."
        )
    ]

    tabla_16 = doc.add_table(rows=len(datos_tabla_16), cols=5)
    tabla_16.alignment = WD_TABLE_ALIGNMENT.CENTER
    aplicar_bordes_tabla(tabla_16)

    anchos_t16 = [Cm(2.6), Cm(2.1), Cm(3.2), Cm(4.6), Cm(3.5)]

    for row_idx, fila in enumerate(datos_tabla_16):
        tr = tabla_16.rows[row_idx]
        tr._tr.get_or_add_trPr().append(parse_xml(f'<w:cantSplit {nsdecls("w")}/>'))

        for col_idx, texto in enumerate(fila):
            celda = tr.cells[col_idx]
            celda.width = anchos_t16[col_idx]
            p_celda = celda.paragraphs[0]
            p_celda.paragraph_format.line_spacing = 1.15
            p_celda.paragraph_format.space_before = Pt(2)
            p_celda.paragraph_format.space_after = Pt(2)

            if row_idx == 0:
                p_celda.alignment = WD_ALIGN_PARAGRAPH.CENTER
                configurar_celda(celda, bg_color=HEX_AZUL_CABECERA, top_padding=110, bottom_padding=110)
                r_txt = p_celda.add_run(texto)
                r_txt.font.bold = True
                r_txt.font.size = Pt(8.5)
                r_txt.font.color.rgb = RGBColor(255, 255, 255)
            else:
                bg = HEX_GRIS_ZEBRA if row_idx % 2 == 1 else "FFFFFF"
                configurar_celda(celda, bg_color=bg, top_padding=85, bottom_padding=85)
                if col_idx == 0:
                    p_celda.alignment = WD_ALIGN_PARAGRAPH.LEFT
                    r_txt = p_celda.add_run(texto)
                    r_txt.font.bold = True
                    r_txt.font.size = Pt(8.5)
                    r_txt.font.color.rgb = COLOR_AZUL_PRINCIPAL
                elif col_idx == 1:
                    p_celda.alignment = WD_ALIGN_PARAGRAPH.CENTER
                    r_txt = p_celda.add_run(texto)
                    r_txt.font.bold = True
                    r_txt.font.size = Pt(8.5)
                elif col_idx == 4:
                    p_celda.alignment = WD_ALIGN_PARAGRAPH.LEFT
                    r_txt = p_celda.add_run(texto)
                    r_txt.font.bold = True
                    r_txt.font.size = Pt(8.5)
                    r_txt.font.color.rgb = RGBColor(4, 120, 87)
                else:
                    p_celda.alignment = WD_ALIGN_PARAGRAPH.LEFT
                    r_txt = p_celda.add_run(texto)
                    r_txt.font.size = Pt(8.5)

    p_t16_fuente = doc.add_paragraph()
    aplicar_estilos_parrafo(p_t16_fuente, espacio_despues=8, alineacion=WD_ALIGN_PARAGRAPH.LEFT)
    r_f16 = p_t16_fuente.add_run("Fuentes: Los autores (2026).")
    r_f16.font.size = Pt(9.5)
    r_f16.font.italic = True

    p_dast_cierre = doc.add_paragraph()
    aplicar_estilos_parrafo(p_dast_cierre)
    p_dast_cierre.add_run(
        "Como resultado concluyente de este proceso de evaluación dual (SAST y DAST), el sistema superó exitosamente "
        "el 100% de las pruebas técnicas planificadas e iteradas a lo largo del Core y los módulos de Autenticación, "
        "Artículos, RepositorioPST y SuperAdmin, consolidando una plataforma blindada, auditable y alineada con los "
        "más altos estándares académicos e institucionales."
    )

    # =========================================================================
    # SECCIÓN 4.3.3 — VALIDACIÓN DE USABILIDAD
    # =========================================================================
    h1_usa = doc.add_heading(level=1)
    h1_usa.paragraph_format.space_before = Pt(14)
    h1_usa.paragraph_format.space_after = Pt(8)
    h1_usa.paragraph_format.keep_with_next = True
    r_h1_usa = h1_usa.add_run("4.3.3. Validación de Usabilidad")
    r_h1_usa.font.name = 'Calibri'
    r_h1_usa.font.size = Pt(15)
    r_h1_usa.font.bold = True
    r_h1_usa.font.color.rgb = COLOR_AZUL_PRINCIPAL

    p_intro_usa_1 = doc.add_paragraph()
    aplicar_estilos_parrafo(p_intro_usa_1)
    p_intro_usa_1.add_run(
        "La validación de usabilidad se realizó con la participación directa de los usuarios finales y el equipo de "
        "desarrollo, evaluando la facilidad de aprendizaje, la eficiencia operativa y la satisfacción general frente a "
        "la interfaz del sistema. Siguiendo los principios del desarrollo ágil propuestos por Beck et al. (2001), la "
        "colaboración continua con el cliente y la validación constante a partir de incrementos funcionales son "
        "fundamentales para asegurar que el producto responda a sus necesidades reales. A través de un instrumento de "
        "evaluación y pruebas controladas de ejecución, se determinó que el sistema presenta una mejora significativa en "
        "los tiempos de respuesta en comparación con el proceso manual previo —reduciendo la catalogación de proyectos "
        "socio-tecnológicos (PST) y artículos científicos de varios minutos a pocos segundos mediante extracción heurística "
        "automatizada y búsqueda vectorial—, validando así la pertinencia social y funcional de la propuesta tecnológica."
    )

    p_intro_usa_2 = doc.add_paragraph()
    aplicar_estilos_parrafo(p_intro_usa_2)
    p_intro_usa_2.add_run(
        "Para estructurar este proceso de verificación bajo estándares internacionales de ingeniería de software y "
        "computación, las pruebas de aceptación se dividieron formalmente en dos etapas secuenciales y complementarias: "
        "las Pruebas de Aceptación en Fábrica (FAT, por sus siglas en inglés Factory Acceptance Testing), ejecutadas en "
        "el entorno controlado de desarrollo, y las Pruebas de Aceptación en Sitio (SAT, por sus siglas en inglés Site "
        "Acceptance Testing), orientadas a la validación operativa en el entorno institucional."
    )

    # =========================================================================
    # SUBSECCIÓN 4.3.3.1 — PRUEBAS FAT (FACTORY ACCEPTANCE TESTING)
    # =========================================================================
    h2_fat = doc.add_heading(level=2)
    h2_fat.paragraph_format.space_before = Pt(12)
    h2_fat.paragraph_format.space_after = Pt(6)
    h2_fat.paragraph_format.keep_with_next = True
    r_h2_fat = h2_fat.add_run("4.3.3.1. Pruebas FAT (Factory Acceptance Testing)")
    r_h2_fat.font.name = 'Calibri'
    r_h2_fat.font.size = Pt(13)
    r_h2_fat.font.bold = True
    r_h2_fat.font.color.rgb = COLOR_AZUL_PRINCIPAL

    p_fat_1 = doc.add_paragraph()
    aplicar_estilos_parrafo(p_fat_1)
    p_fat_1.add_run(
        "Las Pruebas de Aceptación en Fábrica (FAT) constituyen el conjunto de evaluaciones integrales que el equipo "
        "desarrollador ejecuta dentro de su propio entorno local de trabajo (servidor WampServer sobre Windows, intérprete "
        "PHP 8.5 y motor relacional PostgreSQL 18 con extensión vectorial pgvector) de manera previa al despliegue definitivo "
        "en los servidores de la universidad. El objetivo primordial de este protocolo consiste en certificar que el código "
        "fuente cumple al 100% con los Requerimientos Funcionales comprometidos en los capítulos precedentes, verificando "
        "la ausencia de errores de lógica, bloqueos transaccionales o excepciones no controladas (“pantallas de la muerte”)."
    )

    p_fat_2 = doc.add_paragraph()
    aplicar_estilos_parrafo(p_fat_2)
    p_fat_2.add_run(
        "Para garantizar el rigor metodológico y la trazabilidad empírica, el protocolo de ejecución de las pruebas FAT "
        "se llevó a cabo siguiendo cuatro (4) fases sistemáticas:\n"
        "  1. Aislamiento del Entorno Local: Se levantó el servidor local de pruebas (Apache 2.4 / PHP 8.5.0) conectado a "
        "la instancia PostgreSQL 18 (base de datos ciidi con las 43 tablas del esquema público y el lote completo de "
        "migraciones 001 a 006 sincronizado en core/Installer/schema.sql).\n"
        "  2. Trazabilidad Funcional por Módulo: Se transformó cada requerimiento funcional del núcleo (Core / Instalador) "
        "y de los cuatro (4) módulos desarrollados por el equipo (Autenticación, RepositorioPST, Artículos y SuperAdmin) "
        "en casos de prueba verificables (identificados desde FAT-001 hasta FAT-025).\n"
        "  3. Ejecución de Casos de Uso (Flujo Feliz y Pruebas de Ruptura): Se ejecutó cada flujo operativo primero con "
        "datos válidos (comportamiento esperado) y posteriormente introduciendo datos inválidos, archivos manipulados o "
        "condiciones de borde para comprobar la resiliencia del sistema y depurar en caliente cualquier anomalía.\n"
        "  4. Corrección Iterativa y Documentación en Matriz FAT: Durante la corrida de pruebas en fábrica se detectaron "
        "y corrigieron proactivamente tres hallazgos técnicos: (a) la aplicación e integración de la migración "
        "006_fix_del_fix_de_autores.sql (tablas public.investigadores y public.recurso_investigadores con identificador "
        "ORCID único) que ocasionaba un error HTTP 500 en el catálogo de artículos; (b) la corrección del desfase de zona "
        "horaria UTC en RateLimiter.php al persistir marcas de tiempo en PostgreSQL (waf_rate_limiter), asegurando el "
        "bloqueo efectivo de IP por fuerza bruta; y (c) la compatibilidad con PHP 8.5 en ArticulosController.php respecto "
        "a la liberación automática de descriptores finfo."
    )

    p_fat_3 = doc.add_paragraph()
    aplicar_estilos_parrafo(p_fat_3)
    p_fat_3.add_run(
        "El mapa de evaluación en fábrica abarcó cinco (5) bloques arquitectónicos con cinco (5) casos de prueba por cada "
        "uno, sumando un total de veinticinco (25) pruebas FAT verificadas:"
    )

    bloques_fat = [
        ("Bloque 1 — Core e Instalador Autónomo (FAT-001 a FAT-005): ",
         "Se verificó la detección preventiva de dependencias críticas en PHP (pdo_pgsql, fileinfo, mbstring, openssl, gd) y permisos de escritura en InstallerService::checkSystemEnvironment(); el manejo controlado de credenciales erróneas de PostgreSQL sin colgar el proceso; la exigencia de contraseñas robustas para el SuperAdmin y la generación del archivo candado storage/installed.lock; el despacho exacto de códigos HTTP 400 y 404 junto con la sincronización de la variable de sesión app.usuario_actual en PostgreSQL; y la activación del bloqueo automático en RateLimiter e intercepción WAF."),
        ("Bloque 2 — Módulo de Autenticación y Seguridad RBAC (FAT-006 a FAT-010): ",
         "Se evaluó la respuesta genérica anti-enumeración ante usuarios inexistentes o claves inválidas en LoginController; el ciclo de vida de la sesión (directivas HttpOnly, SameSite=Lax, session.use_strict_mode=1, expiración por inactividad y revocación remota en Auth::check()); el control de acceso basado en roles (RBAC) denegando con HTTP 403 el ingreso de roles no administrativos a vistas protegidas; la validación criptográfica de tokens CSRF (hash_equals) y tokens de recuperación SHA-256 con caducidad de 1 hora; y el filtro binario de avatares en PerfilController antes del reprocesamiento gráfico GD."),
        ("Bloque 3 — Módulo RepositorioPST e Inteligencia Artificial (FAT-011 a FAT-015): ",
         "Se comprobó el rechazo de ejecutables o scripts camuflados como .pdf mediante lectura de Magic Bytes (finfo_file); la extracción heurística automática de metadatos (título, autores, tutor, año, resumen y objetivo general) en ExtractorPST manteniendo el consumo de memoria RAM por debajo de 5 MB y protegiendo contra Zip Bomb / XXE; la búsqueda semántica vectorial en PostgreSQL (pgvector con operador de distancia coseno <=> y embeddings ONNX de 384 dimensiones) recuperando proyectos por similitud conceptual; la visualización de fichas técnicas PST y emisión de HTTP 404 ante IDs inexistentes; y el bloqueo de secuencias Path Traversal (../) en rutas de documentos temporales y definitivos."),
        ("Bloque 4 — Módulo de Gestión de Artículos Científicos (FAT-016 a FAT-020): ",
         "Se validó la publicación y actualización transaccional de artículos científicos verificando el filtro Anti-SSRF sobre enlaces externos (bloqueo de localhost y 127.0.0.1); el registro y vinculación de investigadores con validación de formato y unicidad de código ORCID (0000-000X-XXXX-XXXX) en public.investigadores; la consulta multicriterio paginada por palabra clave, autor/ORCID, año, categoría y etiqueta; la conmutación de visibilidad (Activo/Oculto) en detalles_articulos y respuesta HTTP 404 ante artículos inexistentes; y el CRUD dinámico de catálogos (categorias, etiquetas, editoriales) protegido por lista blanca e invalidación de caché JSON."),
        ("Bloque 5 — Módulo Panel SuperAdmin y Continuidad Operativa (FAT-021 a FAT-025): ",
         "Se certificó el diagnóstico integral de salud del Core (testearCore) y la telemetría de 43 tablas en PostgreSQL; la generación física de respaldos comprimidos (.sql.gz) en storage/backups/ mediante BackupService::crearBackup() y la validación sintáctica preventiva (dry-run) antes de restaurar; la inmutabilidad de la cadena de bloques de auditoría (HMAC-SHA256 con 258 bloques íntegros en AuditLogger) en conjunto con los 5 triggers PL/pgSQL activos en base de datos; la gestión de la matriz RBAC y el candado anti-autoeliminación de la cuenta SuperAdmin en sesión; y el cifrado AES-256-CBC de credenciales SMTP junto con la neutralización de fórmulas maliciosas (=CMD|) en la exportación de reportes CSV.")
    ]

    for negrita, texto in bloques_fat:
        p_b = doc.add_paragraph(style='List Bullet')
        aplicar_estilos_parrafo(p_b, espacio_despues=4)
        r_b = p_b.add_run(negrita)
        r_b.font.bold = True
        p_b.add_run(texto)

    p_fat_intro_tabla = doc.add_paragraph()
    aplicar_estilos_parrafo(p_fat_intro_tabla)
    p_fat_intro_tabla.add_run(
        "A continuación, en la Tabla 17 se documenta la Matriz de Pruebas de Aceptación en Fábrica (FAT), registrando "
        "el identificador de cada caso de prueba, el módulo evaluado, la descripción de la acción simulada, el resultado "
        "esperado según la especificación funcional, el resultado empírico obtenido en el entorno local y su estatus final."
    )

    # =========================================================================
    # TABLA 17: MATRIZ DE PRUEBAS DE ACEPTACIÓN EN FÁBRICA (FAT)
    # =========================================================================
    p_t17_titulo = doc.add_paragraph()
    aplicar_estilos_parrafo(p_t17_titulo, espacio_despues=2, alineacion=WD_ALIGN_PARAGRAPH.LEFT)
    r_t17_num = p_t17_titulo.add_run("Tabla 17\n")
    r_t17_num.font.bold = True
    r_t17_num.font.size = Pt(10.5)
    r_t17_num.font.color.rgb = COLOR_AZUL_PRINCIPAL
    r_t17_nom = p_t17_titulo.add_run("Matriz de Pruebas de Aceptación en Fábrica (FAT)\n")
    r_t17_nom.font.italic = True
    r_t17_nom.font.bold = True
    r_t17_nom.font.size = Pt(10.5)
    r_t17_resp = p_t17_titulo.add_run("Responsables: Equipo de desarrollo (Core, Autenticación, RepositorioPST, Artículos y SuperAdmin).")
    r_t17_resp.font.size = Pt(9.5)

    datos_tabla_17 = [
        ("ID", "Módulo", "Descripción / Acción Simulada", "Resultado Esperado", "Resultado Obtenido", "Estatus"),
        # BLOQUE 1: CORE / INSTALADOR (FAT-001 a FAT-005)
        (
            "FAT-001",
            "Core /\nInstalador",
            "Verificación de requisitos del entorno (InstallerService::checkSystemEnvironment): comprobar versión de PHP, extensiones críticas (pdo_pgsql, fileinfo, mbstring, openssl, gd) y permisos de escritura en config/ y storage/.",
            "El instalador valida el entorno y bloquea el avance (ready = false) si falta alguna extensión crítica o permiso de escritura.",
            "Entorno validado en PHP 8.5.0 con todas las extensiones activas y permisos de escritura verificados; el diagnóstico bloquea el avance ante ausencias.",
            "Aprobado"
        ),
        (
            "FAT-002",
            "Core /\nInstalador",
            "Prueba de conexión a PostgreSQL (InstallerService::testDbConnection): ingresar credenciales inválidas y posteriormente credenciales válidas para inspeccionar el esquema.",
            "Muestra mensaje controlado sin colgar la aplicación ante credenciales erróneas y detecta las tablas existentes al conectar con éxito.",
            "Rechazó credenciales inválidas (exito = false) sin volcado fatal y conectó con credenciales válidas detectando las 43 tablas del esquema public.",
            "Aprobado"
        ),
        (
            "FAT-003",
            "Core /\nInstalador",
            "Creación del SuperAdmin y bloqueo post-instalación: intentar registrar una clave débil (< 8 caracteres) y verificar la protección del archivo candado storage/installed.lock.",
            "Rechaza contraseñas débiles exigiendo mínimo 8 caracteres con hash Bcrypt y bloquea cualquier reinstalación si existe installed.lock.",
            "Rechazó clave de 5 caracteres con alerta de seguridad, aplicó Bcrypt a claves válidas y redirigió fuera del instalador al detectar storage/installed.lock.",
            "Aprobado"
        ),
        (
            "FAT-004",
            "Core /\nEnrutador y BD",
            "Enrutamiento de excepciones HTTP y trazabilidad de sesión en BD: invocar ruta inexistente (/ruta-inexistente-fat), parámetro faltante (/ver-pdf-pst) y verificar set_config('app.usuario_actual').",
            "Emite códigos exactos HTTP 404 y HTTP 400 con vistas institucionales y sincroniza el ID del usuario activo en la sesión de PostgreSQL.",
            "Emitió HTTP 404 y HTTP 400 sin exponer rutas físicas; PDO::ATTR_EMULATE_PREPARES = false activo y app.usuario_actual = 1 sincronizado.",
            "Aprobado"
        ),
        (
            "FAT-005",
            "Core /\nWAF y RateLimiter",
            "Mitigación de fuerza bruta e inyección perimetral: simular 6 intentos fallidos consecutivos desde una IP externa y enviar un vector XSS (<script>alert(1)</script>) por GET.",
            "RateLimiter bloquea temporalmente la IP por 15 minutos al superar 5 intentos y el WAF intercepta el payload XSS registrando alerta CRITICAL.",
            "IP bloqueada al 6to intento (corregida conversión UTC en waf_rate_limiter) y payload XSS interceptado y registrado en AuditLogger.",
            "Aprobado"
        ),
        # BLOQUE 2: AUTENTICACIÓN (FAT-006 a FAT-010)
        (
            "FAT-006",
            "Autenticación",
            "Validación de credenciales anti-enumeración: ingresar en /login una cédula o correo inexistente y luego una contraseña incorrecta para una cuenta registrada.",
            "El sistema responde con un mensaje genérico unificado ('Credenciales inválidas') sin revelar si el usuario existe en la base de datos.",
            "UsuarioModel::intentarAutenticacion() retornó falso sin filtrar estructura y LoginController emitió el mensaje genérico unificado.",
            "Aprobado"
        ),
        (
            "FAT-007",
            "Autenticación",
            "Ciclo de vida y caducidad de sesión (SessionManager y Auth::check): iniciar sesión, verificar directivas de cookie (HttpOnly, SameSite=Lax) y simular superación del tiempo límite de inactividad.",
            "El sistema destruye la sesión inactiva o revocada remotamente, regenera el ID de sesión y expulsa al usuario a la vista de Login.",
            "Directivas HttpOnly, SameSite=Lax y use_strict_mode=1 activas; Auth::check() invalidó la sesión expirada y redirigió a /login con aviso.",
            "Aprobado"
        ),
        (
            "FAT-008",
            "Autenticación /\nRBAC",
            "Control de Acceso Basado en Roles (RBAC): autenticar una sesión con nivel de privilegio no administrativo (Nivel 2) e intentar acceder por URL a rutas de SuperAdmin (/sudoadmin, /visor-logs).",
            "El motor de autorización (Auth::requierePrivilegioMinimo) deniega el acceso y responde con código HTTP 403 Forbidden.",
            "El sistema bloqueó el acceso vertical no autorizado y renderizó la vista institucional 403.php con código de estado HTTP 403.",
            "Aprobado"
        ),
        (
            "FAT-009",
            "Autenticación",
            "Protección Anti-CSRF y restablecimiento de clave: enviar petición POST a procesarRestablecerClave() con token CSRF manipulado y verificar hash SHA-256 de tokens de recuperación.",
            "Rechaza la petición POST con token CSRF inválido mediante comparación en tiempo constante (hash_equals) y valida caducidad de 1 hora del token.",
            "CSRF::validarToken() aprobó el token legítimo de 64 caracteres hexadecimales y rechazó inmediatamente el token manipulado.",
            "Aprobado"
        ),
        (
            "FAT-010",
            "Autenticación /\nPerfil",
            "Filtro binario de avatar en PerfilController: intentar subir un script PHP malicioso (<?php echo 'webshell'; ?>) renombrado con extensión de imagen (.png).",
            "El sistema inspecciona los Magic Bytes reales con finfo_file(), detecta el tipo MIME text/x-php y rechaza el archivo antes de procesarlo.",
            "finfo_file() detectó el MIME real 'text/x-php' y bloqueó la carga antes de invocar las funciones de reprocesamiento GD.",
            "Aprobado"
        ),
        # BLOQUE 3: REPOSITORIOPST (FAT-011 a FAT-015)
        (
            "FAT-011",
            "RepositorioPST",
            "Filtro Magic Bytes en ingesta documental: intentar cargar un archivo ejecutable binario (cabecera MZ) al que se le cambió la extensión a .pdf en el formulario de PST.",
            "El controlador lee la firma binaria interna del archivo, detecta que no es un PDF legítimo y rechaza la subida.",
            "Detectado MIME real 'application/x-dosexec' mediante finfo_file(); archivo rechazado de inmediato sin almacenarse en storage/.",
            "Aprobado"
        ),
        (
            "FAT-012",
            "RepositorioPST",
            "Extracción heurística de metadatos y control de memoria RAM (ExtractorPST): procesar documento de prueba para extraer título, autores, tutor, año, resumen y objetivo general.",
            "Extrae automáticamente los campos académicos sin saturar la memoria RAM (límite de 15 páginas PDF y tope de 15 MB anti-ZipBomb en DOCX).",
            "Metadatos extraídos con éxito (Título, Autores con C.I., Tutor, Resumen y Objetivo General) con incremento de RAM < 0.1 MB.",
            "Aprobado"
        ),
        (
            "FAT-013",
            "RepositorioPST /\nMotor IA",
            "Búsqueda Semántica Vectorial (pgvector + ONNX): buscar proyectos usando términos conceptuales ('automatización y gestión tecnológica comunitaria') sin coincidencia literal exacta.",
            "El servicio EmbeddingService genera el vector de 384 dimensiones y PostgreSQL (pgvector <=>) devuelve proyectos afines ordenados por similitud.",
            "Extensión pgvector activa; búsqueda vectorial ONNX ejecutada con éxito recuperando 6 proyectos relevantes ordenados por distancia coseno.",
            "Aprobado"
        ),
        (
            "FAT-014",
            "RepositorioPST",
            "Consulta de Ficha Técnica PST y manejo de recursos inexistentes: consultar un proyecto activo (ID #201) y posteriormente solicitar un ID inexistente (/detalle-pst?id=999999).",
            "Renderiza la ficha completa con visor PDF e incremento de vistas para el ID válido, y emite código HTTP 404 para el ID inexistente.",
            "Ficha técnica ID #201 cargada (HTTP 200) y consulta de ID #999999 retornó null emitiendo respuesta controlada HTTP 404.",
            "Aprobado"
        ),
        (
            "FAT-015",
            "RepositorioPST",
            "Protección contra Path Traversal en ingesta diferida y visor PDF: inyectar la ruta '../../config/database.php' en el parámetro ruta_archivo_temporal.",
            "El controlador valida el prefijo canónico (storage/documentos/tmp/ o pst/) y bloquea cualquier secuencia de escape '..'.",
            "Secuencia '../../config/database.php' interceptada y bloqueada por la validación de prefijos en DetallePSTController.",
            "Aprobado"
        ),
        # BLOQUE 4: ARTÍCULOS CIENTÍFICOS (FAT-016 a FAT-020)
        (
            "FAT-016",
            "Artículos",
            "Publicación/edición de artículos y filtro Anti-SSRF: intentar registrar una URL interna ('http://127.0.0.1/admin') o esquema 'javascript:alert(1)' como enlace externo del artículo.",
            "El controlador bloquea referencias de loopback (localhost, 127.0.0.1, ::1) y esquemas no HTTP/HTTPS, permitiendo solo URLs académicas válidas.",
            "Bloqueados 'http://127.0.0.1/admin' y 'javascript:alert(1)'; permitida URL académica externa 'https://doi.org/10.1000/xyz123'.",
            "Aprobado"
        ),
        (
            "FAT-017",
            "Artículos",
            "Gestión de investigadores y validación de identificador ORCID: verificar operatividad de las tablas public.investigadores y public.recurso_investigadores con restricción UNIQUE en ORCID.",
            "Permite vincular múltiples investigadores a un artículo validando el formato estándar ORCID (0000-000X-XXXX-XXXX) sin duplicados.",
            "Tablas public.investigadores y public.recurso_investigadores operativas (migración 006 aplicada); vinculación y restricción UNIQUE verificadas.",
            "Aprobado"
        ),
        (
            "FAT-018",
            "Artículos",
            "Búsqueda multicriterio y paginación del catálogo público (/articulos): filtrar artículos combinando palabras clave, nombre u ORCID de autor, año, categoría y etiqueta.",
            "Ejecuta la consulta parametrizada sin errores SQL y devuelve los artículos activos paginados junto con el conteo total de registros.",
            "Endpoint /articulos respondió HTTP 200; obtenerArticulosPaginados() y contarArticulos() recuperaron los 11 artículos activos sin errores.",
            "Aprobado"
        ),
        (
            "FAT-019",
            "Artículos",
            "Trazabilidad de visibilidad (Activo/Oculto) y manejo de inexistentes: alternar visibilidad de un artículo y solicitar en el visor público un ID inexistente (/detalle-articulo?id=999999).",
            "Los artículos ocultos dejan de listarse en el portal público y la solicitud de un ID inexistente responde con código HTTP 404.",
            "Conmutación de estado activo verificada en detalles_articulos y petición a /detalle-articulo?id=999999 respondió con código HTTP 404.",
            "Aprobado"
        ),
        (
            "FAT-020",
            "Artículos",
            "Seguridad en CRUD de catálogos editoriales (ConfiguracionController): listar el catálogo 'categorias' e intentar inyectar el nombre de tabla 'usuarios' en obtenerCatalogoPaginado().",
            "Lista normalmente los catálogos autorizados (categorias, etiquetas, editoriales) y lanza InvalidArgumentException ante tablas no permitidas.",
            "Catálogo 'categorias' consultado correctamente e intento de lectura sobre tabla 'usuarios' bloqueado por lista blanca estricta.",
            "Aprobado"
        ),
        # BLOQUE 5: SUPERADMIN (FAT-021 a FAT-025)
        (
            "FAT-021",
            "SuperAdmin",
            "Diagnóstico de salud del Core y telemetría de base de datos (AdminController::testearCore y AdminDashboardModel): ejecutar el chequeo integral de salud y monitoreo de tablas.",
            "Verifica latencia de PostgreSQL, estado del Kernel, espacio libre en storage/ y métricas de todas las tablas del esquema público.",
            "Diagnóstico completado al 100% con estado SALUDABLE; telemetría reportó conexión PostgreSQL Online y 43 tablas monitoreadas.",
            "Aprobado"
        ),
        (
            "FAT-022",
            "SuperAdmin /\nBackups",
            "Generación de respaldo PostgreSQL (.sql.gz) y validación Dry-Run (BackupService): crear un respaldo comprimido en storage/backups/ y someter un archivo corrupto a verificarIntegridad().",
            "El archivo .sql.gz se crea físicamente con cabecera GZIP y firmas DDL válidas, mientras que el archivo corrupto es rechazado antes de restaurar.",
            "Respaldo .sql.gz generado y validado exitosamente en storage/backups/ (esGzip = true); archivo corrupto rechazado (valido = false).",
            "Aprobado"
        ),
        (
            "FAT-023",
            "SuperAdmin /\nAuditoría",
            "Auditoría forense encadenada (AuditLogger) y triggers PL/pgSQL: registrar acciones en el sistema y ejecutar verificarIntegridadCadena() junto con revisión de triggers en pg_trigger.",
            "Confirma quién, a qué hora, desde qué IP y qué acción se realizó, certificando 0 bloques corruptos en la cadena SHA-256 y triggers activos.",
            "Cadena criptográfica HMAC-SHA256 íntegra (258 bloques verificados, 0 corruptos) y 5 triggers PL/pgSQL de auditoría activos en PostgreSQL.",
            "Aprobado"
        ),
        (
            "FAT-024",
            "SuperAdmin /\nRBAC y Usuarios",
            "Gestión de matriz RBAC y protección anti-autoeliminación: cargar jerarquía de roles/privilegios e intentar suspender, eliminar o revocar la sesión del propio SuperAdmin activo.",
            "Carga los roles y niveles jerárquicos correctamente e impide por candado lógico que el administrador en sesión elimine o bloquee su propia cuenta.",
            "Cargados 4 roles y 7 niveles de privilegio; candado anti-autoeliminación ($id === $_SESSION['usuario_id']) verificado y activo.",
            "Aprobado"
        ),
        (
            "FAT-025",
            "SuperAdmin /\nCifrado y Reportes",
            "Cifrado de credenciales SMTP (Crypto) y exportación segura de reportes CSV: cifrar/descifrar clave con AES-256-CBC y exportar celda con payload de inyección de fórmulas ('=CMD|...').",
            "Cifra con IV aleatorio recuperando el texto plano exacto al descifrar, y antepone comilla simple (') a celdas CSV que inicien con =, +, -, @.",
            "Ciclo AES-256-CBC verificado sin pérdida de datos y fórmula maliciosa neutralizada a \"'=CMD|' /C calc'!A0\" en el reporte CSV.",
            "Aprobado"
        )
    ]

    tabla_17 = doc.add_table(rows=len(datos_tabla_17), cols=6)
    tabla_17.alignment = WD_TABLE_ALIGNMENT.CENTER
    aplicar_bordes_tabla(tabla_17)

    anchos_t17 = [Cm(1.5), Cm(2.2), Cm(3.6), Cm(3.3), Cm(3.6), Cm(1.8)]

    for row_idx, fila in enumerate(datos_tabla_17):
        tr = tabla_17.rows[row_idx]
        tr._tr.get_or_add_trPr().append(parse_xml(f'<w:cantSplit {nsdecls("w")}/>'))

        for col_idx, texto in enumerate(fila):
            celda = tr.cells[col_idx]
            celda.width = anchos_t17[col_idx]
            p_celda = celda.paragraphs[0]
            p_celda.paragraph_format.line_spacing = 1.15
            p_celda.paragraph_format.space_before = Pt(2)
            p_celda.paragraph_format.space_after = Pt(2)

            if row_idx == 0:
                p_celda.alignment = WD_ALIGN_PARAGRAPH.CENTER
                configurar_celda(celda, bg_color=HEX_AZUL_CABECERA, top_padding=110, bottom_padding=110)
                r_txt = p_celda.add_run(texto)
                r_txt.font.bold = True
                r_txt.font.size = Pt(8.5)
                r_txt.font.color.rgb = RGBColor(255, 255, 255)
            else:
                bg = HEX_GRIS_ZEBRA if row_idx % 2 == 1 else "FFFFFF"
                configurar_celda(celda, bg_color=bg, top_padding=80, bottom_padding=80)
                if col_idx == 0:
                    p_celda.alignment = WD_ALIGN_PARAGRAPH.CENTER
                    r_txt = p_celda.add_run(texto)
                    r_txt.font.bold = True
                    r_txt.font.size = Pt(8)
                    r_txt.font.color.rgb = COLOR_AZUL_PRINCIPAL
                elif col_idx == 1:
                    p_celda.alignment = WD_ALIGN_PARAGRAPH.LEFT
                    r_txt = p_celda.add_run(texto)
                    r_txt.font.bold = True
                    r_txt.font.size = Pt(8)
                elif col_idx == 5:
                    p_celda.alignment = WD_ALIGN_PARAGRAPH.CENTER
                    r_txt = p_celda.add_run(texto)
                    r_txt.font.bold = True
                    r_txt.font.size = Pt(8)
                    r_txt.font.color.rgb = RGBColor(4, 120, 87)
                else:
                    p_celda.alignment = WD_ALIGN_PARAGRAPH.LEFT
                    r_txt = p_celda.add_run(texto)
                    r_txt.font.size = Pt(8)

    p_t17_fuente = doc.add_paragraph()
    aplicar_estilos_parrafo(p_t17_fuente, espacio_despues=8, alineacion=WD_ALIGN_PARAGRAPH.LEFT)
    r_f17 = p_t17_fuente.add_run("Fuentes: Los autores (2026).")
    r_f17.font.size = Pt(9.5)
    r_f17.font.italic = True

    p_fat_cierre = doc.add_paragraph()
    aplicar_estilos_parrafo(p_fat_cierre)
    p_fat_cierre.add_run(
        "Como se desprende de los resultados registrados en la Tabla 17, la totalidad de los veinticinco (25) casos de "
        "prueba ejecutados en fábrica (FAT-001 a FAT-025) alcanzaron el estatus de Aprobado (100% de conformidad funcional "
        "y técnica). Esta validación exhaustiva en el entorno local de desarrollo garantiza que tanto el microkernel "
        "(Core e Instalador Autónomo) como los módulos de Autenticación, RepositorioPST, Artículos y SuperAdmin operan "
        "de manera cohesionada, segura y libre de excepciones fatales, dejando el sistema plenamente certificado para "
        "su despliegue y validación en el entorno institucional."
    )

    # 3. Guardar el archivo en la raíz del proyecto
    nombre_fat = "Validacion_Tecnica_y_Usabilidad_FAT_CIIDI.docx"
    doc.save(nombre_fat)
    print(f"Documento generado exitosamente: {nombre_fat}")

    nombre_archivo = "Validacion_Tecnica_y_Usabilidad_CIIDI.docx"
    try:
        doc.save(nombre_archivo)
        print(f"Documento actualizado exitosamente: {nombre_archivo}")
    except PermissionError:
        print(f"Aviso: '{nombre_archivo}' está abierto en Word; la versión actualizada con FAT se guardó en '{nombre_fat}'.")


if __name__ == "__main__":
    construir_documento()

