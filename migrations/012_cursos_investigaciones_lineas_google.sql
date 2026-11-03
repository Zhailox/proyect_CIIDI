-- ============================================================================
-- MIGRACIÓN 008: Cursos Vinculados a Múltiples Líneas/Dimensiones, Google Form e Investigadores
-- Archivo: migrations/008_cursos_investigaciones_lineas_google.sql
-- ============================================================================

BEGIN;

-- 1. TABLA PIVOTE: CURSOS <-> LÍNEAS DE INVESTIGACIÓN (Múltiples líneas por curso)
CREATE TABLE IF NOT EXISTS public.cursos_lineas (
    id_curso INTEGER NOT NULL REFERENCES public.cursos(id) ON DELETE CASCADE,
    id_linea INTEGER NOT NULL REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE,
    created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_curso, id_linea)
);

CREATE INDEX IF NOT EXISTS idx_cursos_lineas_curso ON public.cursos_lineas(id_curso);
CREATE INDEX IF NOT EXISTS idx_cursos_lineas_linea ON public.cursos_lineas(id_linea);

-- 2. TABLA PIVOTE: CURSOS <-> DIMENSIONES OPERATIVAS (Múltiples dimensiones por curso)
CREATE TABLE IF NOT EXISTS public.cursos_dimensiones (
    id_curso INTEGER NOT NULL REFERENCES public.cursos(id) ON DELETE CASCADE,
    id_dimension INTEGER NOT NULL REFERENCES public.dimensiones_operativas(id) ON DELETE CASCADE,
    created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_curso, id_dimension)
);

CREATE INDEX IF NOT EXISTS idx_cursos_dimensiones_curso ON public.cursos_dimensiones(id_curso);
CREATE INDEX IF NOT EXISTS idx_cursos_dimensiones_dimension ON public.cursos_dimensiones(id_dimension);

-- 2.1 TABLA PIVOTE: INVESTIGACIONES <-> LÍNEAS DE INVESTIGACIÓN (Múltiples líneas por investigación)
CREATE TABLE IF NOT EXISTS public.investigaciones_lineas (
    id_investigacion INTEGER NOT NULL REFERENCES public.investigaciones_ofertadas(id) ON DELETE CASCADE,
    id_linea INTEGER NOT NULL REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE,
    CONSTRAINT pk_inv_lin PRIMARY KEY (id_investigacion, id_linea)
);

CREATE INDEX IF NOT EXISTS idx_inv_lineas_inv ON public.investigaciones_lineas(id_investigacion);
CREATE INDEX IF NOT EXISTS idx_inv_lineas_lin ON public.investigaciones_lineas(id_linea);

-- Poblar relación inicial desde investigaciones_ofertadas.id_linea existente
INSERT INTO public.investigaciones_lineas (id_investigacion, id_linea)
SELECT id, id_linea FROM public.investigaciones_ofertadas WHERE id_linea IS NOT NULL
ON CONFLICT (id_investigacion, id_linea) DO NOTHING;

-- 2.2 TABLA PIVOTE: INVESTIGACIONES <-> DIMENSIONES OPERATIVAS (Múltiples dimensiones por investigación)
CREATE TABLE IF NOT EXISTS public.investigaciones_dimensiones (
    id_investigacion INTEGER NOT NULL REFERENCES public.investigaciones_ofertadas(id) ON DELETE CASCADE,
    id_dimension INTEGER NOT NULL REFERENCES public.dimensiones_operativas(id) ON DELETE CASCADE,
    CONSTRAINT pk_inv_dim PRIMARY KEY (id_investigacion, id_dimension)
);

CREATE INDEX IF NOT EXISTS idx_inv_dim_inv ON public.investigaciones_dimensiones(id_investigacion);
CREATE INDEX IF NOT EXISTS idx_inv_dim_dim ON public.investigaciones_dimensiones(id_dimension);

-- Poblar relación inicial desde investigaciones_ofertadas.id_dimension existente
INSERT INTO public.investigaciones_dimensiones (id_investigacion, id_dimension)
SELECT id, id_dimension FROM public.investigaciones_ofertadas WHERE id_dimension IS NOT NULL
ON CONFLICT (id_investigacion, id_dimension) DO NOTHING;

-- 2.3 TABLA DE PREINSCRIPCIONES DE CURSOS (Fallback local cuando no se usa Google Form)
CREATE TABLE IF NOT EXISTS public.cursos_preinscripciones (
    id SERIAL PRIMARY KEY,
    id_curso INTEGER NOT NULL REFERENCES public.cursos(id) ON DELETE CASCADE,
    nombre_completo VARCHAR(255) NOT NULL,
    correo_electronico VARCHAR(255) NOT NULL,
    telefono VARCHAR(50),
    horario_deseado VARCHAR(255) NOT NULL,
    mensaje TEXT,
    fecha_preinscripcion TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_cursos_preinscripciones_curso ON public.cursos_preinscripciones(id_curso);

-- 3. COLUMNA URL FORMULARIO GOOGLE ESPECÍFICO POR CURSO
ALTER TABLE public.cursos
    ADD COLUMN IF NOT EXISTS url_formulario_google TEXT DEFAULT NULL;

-- 4. COLUMNA ES_INVESTIGADOR EN USUARIOS (Para habilitar que cualquier docente/investigador forme parte de líneas y sea responsable)
ALTER TABLE public.usuarios
    ADD COLUMN IF NOT EXISTS es_investigador BOOLEAN NOT NULL DEFAULT false;

-- Sincronizar docentes y usuarios con investigaciones o líneas activas como investigadores
UPDATE public.usuarios 
SET es_investigador = true 
WHERE id_rol IN (1, 2, 4) 
   OR id IN (SELECT DISTINCT id_profesor FROM public.investigaciones_ofertadas WHERE id_profesor IS NOT NULL)
   OR id IN (SELECT DISTINCT id_usuario FROM public.profesor_linea WHERE id_usuario IS NOT NULL);

COMMIT;
