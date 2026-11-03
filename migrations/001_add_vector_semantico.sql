-- ============================================================================
-- MIGRACIÓN: Habilitar búsqueda semántica con pgvector (Tolerante a fallos en Debian/Linux)
-- Archivo: migrations/001_add_vector_semantico.sql
-- ============================================================================

DO $$
BEGIN
    CREATE EXTENSION IF NOT EXISTS vector WITH SCHEMA public;
EXCEPTION WHEN OTHERS THEN
    RAISE NOTICE 'Extensión pgvector no instalada en el sistema operativo; se usará columna TEXT compatible.';
END $$;

ALTER TABLE public.detalles_proyectos 
ADD COLUMN IF NOT EXISTS vector_semantico TEXT;

DO $$
BEGIN
    IF EXISTS (SELECT 1 FROM pg_type WHERE typname = 'vector') THEN
        ALTER TABLE public.detalles_proyectos 
            ALTER COLUMN vector_semantico TYPE public.vector(384) 
            USING vector_semantico::public.vector;

        CREATE INDEX IF NOT EXISTS idx_detalles_vector_hnsw
        ON public.detalles_proyectos 
        USING hnsw (vector_semantico public.vector_cosine_ops)
        WITH (m = 16, ef_construction = 64);
    END IF;
EXCEPTION WHEN OTHERS THEN
    NULL;
END $$;

CREATE INDEX IF NOT EXISTS idx_detalles_vector_null
ON public.detalles_proyectos (id_recurso)
WHERE vector_semantico IS NULL;
