---
name: requirements-analyzer
description: >-
  Skill para analizar páginas web, pantallas de interfaz (UI/DOM), módulos del sistema o documentos de requerimientos
  (tickets Jira, historias de usuario, mockups, especificaciones funcionales) y generar documentos de requerimientos estándar,
  historias de usuario con criterios de aceptación (Gherkin/AC), tablas de especificación de campos y matrices de riesgos o ambigüedades.
  Activar cuando el usuario pida analizar requisitos, extraer especificaciones de una vista o formulario, o estructurar requerimientos de software.
---

# Kỹ năng Phân tích Yêu cầu / Requirements Analyzer Skill

Esta skill proviene del kit de pruebas de **Anh Tester** (`antigravity-testing-kit`) adaptada y estructurada para Antigravity. Proporciona directrices precisas para que el agente examine la interfaz visual, el DOM HTML, el código fuente o documentos de requisitos (Jira, PRD, User Stories) y genere documentación de requisitos rigurosa, clara y lista para QA, Testers y Desarrolladores.

---

## 1. Objetivos Principales

1. **Fidelidad al sistema real**: Extraer requerimientos basados estrictamente en la interfaz real, el DOM y la lógica existente, sin asumir comportamientos ausentes.
2. **Cobertura exhaustiva**: Considerar tanto el flujo principal (*Happy Path*) como casos borde (*Edge Cases*), estados vacíos, mensajes de error y validaciones.
3. **Estructura estandarizada**: Producir entregables en formato Markdown o **Artifacts** estructurados con tablas detalladas de especificación de campos y criterios de aceptación.

---

## 2. Procedimiento de Análisis y Extracción

### A. Si se analiza una Pantalla / Módulo Web (UI & DOM)
1. **Análisis de Disposición (Layout)**: Identificar header, barra lateral, área principal, pie de página, migas de pan y contenedores.
2. **Formularios e Inputs**:
   - Inspeccionar cada campo interactivo (`input`, `select`, `textarea`, checkboxes, radio buttons).
   - Registrar atributos técnicos: `type`, `name`, `id`, `required`, `pattern`, `maxlength`, `minlength`, `disabled`, `readonly`, `placeholder`.
3. **Botones y Acciones de Interacción**:
   - Determinar función de cada botón o enlace (Crear, Guardar, Editar, Eliminar, Cancelar, Exportar, Filtrar).
   - Estados de los botones (activo, inactivo, loading).
   - Notificaciones y alertas (toasts, modales de confirmación, banners).
4. **Flujos de Trabajo y Dependencias (Workflows)**:
   - Dependencias entre campos (ej. selectores en cascada, campos condicionales).
   - Acciones que habilitan o deshabilitan otros componentes.

### B. Si se analiza un Documento de Requisitos / Ticket Jira
1. **Recolección de Metadatos**: Identificador, tipo, prioridad, componentes afectados, versión y descripción original.
2. **Desglose de Historias de Usuario**: Mapear el formato *"Como [rol], quiero [acción], para [beneficio]"*.
3. **Criterios de Aceptación (AC)**: Descomponer cada criterio en reglas verificables.
4. **Detección de Ambigüedades y Vacíos**:
   - Palabras ambiguas ("donde aplique", "según corresponda", "similar a", "etc.").
   - Reglas de validación faltantes (formatos, límites, caracteres especiales).
   - Inconsistencias entre el documento descriptivo y el mockup o la interfaz real.

---

## 3. Estructura Estándar del Documento de Salida (Output Format)

El resultado se debe estructurar de la siguiente forma (preferiblemente como un **Artifact** Markdown):

### 3.1. Resumen General (Overview)
- Propósito del módulo, vista o funcionalidad.
- Alcance (*Scope*) y componentes impactados.

### 3.2. Historias de Usuario y Criterios de Aceptación (Functional Requirements)
- **ID / Título de la funcionalidad**.
- **User Story**: "Como [rol], quiero [funcionalidad] para que [valor/objetivo]".
- **Criterios de Aceptación (Acceptance Criteria)** en formato lista o Given-When-Then.

### 3.3. Especificación Detallada de Campos (Field Specifications)
Tabla Markdown con el detalle técnico para automatización y pruebas:

| Etiqueta / Campo | Tipo UI / Elemento | Obligatorio | Validación / Restricciones | Valor por Defecto | Notas / Comportamiento |
| :--- | :--- | :--- | :--- | :--- | :--- |
| Correo Electrónico | Input (email) | Sí | Formato email estándar, max 100 caracteres | Vacío | Debe ser único en BD |
| Contraseña | Input (password) | Sí | Min 8 chars, 1 mayúscula, 1 número | Vacío | Oculto por defecto con toggle de visualización |

### 3.4. Reglas de Negocio y Validaciones (Business Rules)
- Reglas de interacción entre componentes.
- Textos exactos de los mensajes de validación y alerta esperados ante datos incorrectos o fallos.

### 3.5. Puntos Ambiguos y Riesgos de Calidad (Ambiguities & Quality Risks)
- **AMB-XX**: Dudas o especificaciones no concluyentes detectadas en la UI/código que deben aclararse con el equipo.
- **RISK-XX**: Riesgos para pruebas o desarrollo (ej. ausencia de límite de subida, falta de protección CSRF, etc.).

---

## 4. Reglas Estrictas

1. **Idioma**: Responder en el idioma utilizado por el usuario (español por defecto).
2. **Evitar supuestos infundados**: Si un comportamiento o regla de validación no es deducible del código o de la UI, documentarlo explícitamente en la sección de *Ambigüedades/Preguntas a clarificar*.
3. **Claridad para QA y Devs**: Las especificaciones deben ser lo suficientemente precisas como para que un QA cree casos de prueba directos o scripts de automatización (Selenium/Playwright/Cypress).

---

## 5. Referencias y Workflows

- [Workflow: Generar Requerimientos desde Sitio Web](./references/web_requirements_workflow.md)
- [Workflow: Análisis de Documentos de Requerimientos y Jira](./references/document_analysis_workflow.md)
- [Documentación Original (Anh Tester)](./references/original_vietnamese.md)
