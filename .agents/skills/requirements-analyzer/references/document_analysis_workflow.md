# Workflow: Análisis de Documentos de Requerimientos y Tickets

Este workflow se enfoca en descomponer documentos de especificación (PRDs, tickets de Jira, especificaciones de funcionalidades o user stories) y detectar inconsistencias o vacíos antes de la fase de implementación y pruebas.

## Pasos de Ejecución

1. **Lectura y Mapeo Estructural**:
   - Extraer metadatos del documento: ID del ticket/módulo, título, prioridad, versión y componentes afectados.
   - Leer comentarios o discusiones asociadas, ya que con frecuencia contienen decisiones técnicas o cambios de alcance no reflejados en el cuerpo principal.

2. **Desglose de Criterios de Aceptación (Acceptance Criteria)**:
   - Traducir cada criterio a un enunciado verificable de prueba.
   - Separar comportamientos por defecto vs. comportamientos condicionales u opcionales.

3. **Cruce con Mockups y Diseño**:
   - Comparar el texto del requerimiento con las capturas o mockups (Figma, imágenes).
   - Identificar discrepancias: etiquetas discordantes, campos presentes en la UI que no figuran en el texto, o botones sin acción definida.

4. **Identificación de Ambigüedades (AMB-XX)**:
   - Detectar cláusulas vagas ("el sistema responderá rápidamente", "validar según corresponda", "etc.").
   - Identificar reglas de negocio no especificadas: ¿Qué ocurre si la sesión expira a mitad del formulario? ¿Qué longitud máxima admite el campo?

5. **Matriz de Riesgos y Recomendaciones (RISK-XX)**:
   - Formular preguntas concretas para el Product Owner / Business Analyst.
   - Resumir recomendaciones para el diseño de pruebas automatizadas y manuales.
