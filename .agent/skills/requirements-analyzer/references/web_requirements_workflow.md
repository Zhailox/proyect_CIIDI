# Workflow: Generar Requerimientos desde Sitio Web / Módulo UI

Este workflow guía el proceso de análisis exhaustivo de una vista, pantalla o módulo web activo para extraer una especificación formal de requisitos.

## Pasos de Ejecución

1. **Recolección de Información y Contexto**:
   - Identificar la URL, archivo de vista (`.php`, `.html`, `.vue`, etc.) o componente a evaluar.
   - Reconocer permisos y roles asociados (ej. usuario público, autenticado, administrador).

2. **Exploración de la Interfaz y DOM**:
   - Inspeccionar la estructura del árbol de elementos: formularios, tablas de datos, modales emergentes y menús.
   - Registrar nombres de campos (`name`, `id`, `data-*`), tipos de entrada y atributos de validación nativa (`required`, `min`, `max`, `pattern`).
   - Identificar llamadas asíncronas (`fetch`, `axios`, endpoints AJAX) desencadenadas por botones o eventos `onchange`.

3. **Mapeo de Flujos de Interacción**:
   - Identificar el camino feliz (*Happy Path*): acciones necesarias para completar la tarea exitosamente.
   - Identificar caminos alternos y de excepción (*Error Paths*): respuestas del sistema ante campos vacíos, valores inválidos o fallos de red.

4. **Elaboración del Documento de Especificación**:
   - Estructurar el resultado en secciones claras: Resumen, Casos de Uso/User Stories, Tabla de Campos y Criterios de Aceptación.
   - Incluir los mensajes de retroalimentación esperados (éxito, advertencia, peligro).
