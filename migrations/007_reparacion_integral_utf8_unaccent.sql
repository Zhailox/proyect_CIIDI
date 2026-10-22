-- ============================================================================
-- MIGRACIÓN 007: Reparación Integral de Codificación UTF-8 y Extensión UNACCENT
-- Archivo: migrations/007_reparacion_integral_utf8_unaccent.sql
--
-- Objetivo:
-- 1. Garantizar client_encoding = 'UTF8' durante toda la sesión.
-- 2. Activar las extensiones nativas 'unaccent' y 'pg_trgm' en PostgreSQL para
--    búsquedas insensibles a tildes y diacríticos de alto rendimiento.
-- 3. Crear función SQL public.normalizar_busqueda_pst(text) basada en unaccent.
-- ============================================================================

SET client_encoding = 'UTF8';

BEGIN;

CREATE EXTENSION IF NOT EXISTS unaccent WITH SCHEMA public;
CREATE EXTENSION IF NOT EXISTS pg_trgm WITH SCHEMA public;

CREATE OR REPLACE FUNCTION public.normalizar_busqueda_pst(input_text TEXT)
RETURNS TEXT
LANGUAGE sql
IMMUTABLE
AS $$
    SELECT LOWER(public.unaccent(COALESCE(input_text, '')));
$$;

-- Corrección de corrupciones históricas CP850 ('á' por 'í' y duplicidad 'ónn') en catálogos base
UPDATE public.dimensiones_operativas SET nombre = 'Guías de estudio web' WHERE id = 11;
UPDATE public.dimensiones_operativas SET descripcion = REPLACE(descripcion, 'especáfica', 'específica') WHERE descripcion LIKE '%especáfica%';
UPDATE public.dimensiones_operativas SET descripcion = REPLACE(descripcion, 'fásicamente', 'físicamente') WHERE descripcion LIKE '%fásicamente%';
UPDATE public.dimensiones_operativas SET descripcion = REPLACE(REPLACE(descripcion, 'asá como', 'así como'), 'lámites', 'límites') WHERE id = 15;
UPDATE public.dimensiones_operativas SET descripcion = REPLACE(descripcion, 'tecnologáas', 'tecnologías') WHERE descripcion LIKE '%tecnologáas%';
UPDATE public.dimensiones_operativas SET descripcion = REPLACE(descripcion, 'entre si dentro', 'entre sí dentro') WHERE id = 5;

UPDATE public.lineas_investigacion SET nombre = 'SISTEMAS DE INFORMACIÓN Y MODELADO DE DATOS' WHERE id = 7;
UPDATE public.lineas_investigacion SET nombre = 'EDUMÁTICA', descripcion = REPLACE(descripcion, 'asá contribuir', 'así contribuir') WHERE id = 8;

UPDATE public.cursos SET descripcion = REPLACE(descripcion, 'investigaciónn', 'investigación') WHERE descripcion LIKE '%investigaciónn%';
UPDATE public.niveles_academicos SET descripcion = REPLACE(descripcion, 'investigaciónn', 'investigación') WHERE descripcion LIKE '%investigaciónn%';
UPDATE public.tipo_recurso SET descripcion = REPLACE(descripcion, 'investigaciónn', 'investigación') WHERE descripcion LIKE '%investigaciónn%';
UPDATE public.detalles_proyectos SET resumen = REPLACE(resumen, 'investigaciónn', 'investigación') WHERE resumen LIKE '%investigaciónn%';
UPDATE public.detalles_articulos SET resumen = REPLACE(resumen, 'investigaciónn', 'investigación') WHERE resumen LIKE '%investigaciónn%';

COMMIT;
