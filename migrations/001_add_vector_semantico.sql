-- ============================================================================
-- MIGRACIÓN: Habilitar búsqueda semántica con pgvector
-- Archivo: migrations/001_add_vector_semantico.sql
-- 
-- Prerequisito: La extensión pgvector debe estar instalada en PostgreSQL.
--   En Debian/Ubuntu: sudo apt install postgresql-16-pgvector
--   En RHEL/CentOS:  sudo dnf install pgvector_16
--
-- Ejecutar como superusuario PostgreSQL o usuario con permisos CREATE EXTENSION.
-- ============================================================================

-- 1. Habilitar la extensión pgvector en la base de datos
CREATE EXTENSION IF NOT EXISTS vector;

-- 2. Agregar la columna de vector semántico a detalles_proyectos.
--    Dimensión 384 corresponde al modelo all-MiniLM-L6-v2.
--    ⚠ AJUSTAR el número si tu modelo ONNX produce otra dimensión (768, 1024, etc.)
--    El DEFAULT es NULL: los proyectos existentes quedarán pendientes de vectorización.
ALTER TABLE public.detalles_proyectos 
ADD COLUMN IF NOT EXISTS vector_semantico vector(384);

-- 3. Índice HNSW para búsqueda por similitud coseno.
--    HNSW es superior a IVFFlat para catálogos < 100.000 registros:
--    no requiere entrenamiento previo y tiene mejor recall.
CREATE INDEX IF NOT EXISTS idx_detalles_vector_hnsw
ON public.detalles_proyectos 
USING hnsw (vector_semantico vector_cosine_ops)
WITH (m = 16, ef_construction = 64);

-- 4. Índice parcial para que el worker encuentre rápido los pendientes
CREATE INDEX IF NOT EXISTS idx_detalles_vector_null
ON public.detalles_proyectos (id_recurso)
WHERE vector_semantico IS NULL;

-- 5. Verificación: esta consulta debe retornar la columna recién creada
SELECT column_name, udt_name
FROM information_schema.columns 
WHERE table_schema = 'public' 
  AND table_name = 'detalles_proyectos' 
  AND column_name = 'vector_semantico';
