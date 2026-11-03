-- ============================================================================
-- MIGRACIÓN 007: Reparación Integral de Codificación UTF-8 y Extensión UNACCENT
-- Archivo: migrations/007_reparacion_integral_utf8_unaccent.sql
-- ============================================================================

SET client_encoding = 'UTF8';

DO $$
BEGIN
    CREATE EXTENSION IF NOT EXISTS unaccent WITH SCHEMA public;
EXCEPTION WHEN OTHERS THEN
    NULL;
END $$;

DO $$
BEGIN
    CREATE EXTENSION IF NOT EXISTS pg_trgm WITH SCHEMA public;
EXCEPTION WHEN OTHERS THEN
    NULL;
END $$;

DO $$
BEGIN
    PERFORM public.unaccent('prueba');
EXCEPTION WHEN undefined_function THEN
    CREATE OR REPLACE FUNCTION public.unaccent(text)
    RETURNS text
    LANGUAGE sql
    IMMUTABLE STRICT
    AS $func$
        SELECT translate(
            $1,
            'áàäâãåéèëêíìïîóòöôõúùüûñçÁÀÄÂÃÅÉÈËÊÍÌÏÎÓÒÖÔÕÚÙÜÛÑÇ',
            'aaaaaaeeeeiiiiooooouuuuncAAAAAAEEEEIIIIOOOOOUUUUNC'
        );
    $func$;
END $$;

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
