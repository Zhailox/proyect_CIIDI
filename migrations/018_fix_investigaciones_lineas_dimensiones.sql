-- ============================================================================
-- MIGRACIÓN 018: Tablas Pivote de Investigaciones (Líneas y Dimensiones) + Preinscripciones
-- Archivo: migrations/018_fix_investigaciones_lineas_dimensiones.sql
--
-- Nota técnica: Estas tablas fueron creadas por el Equipo 2 directamente en su
-- volcado local (migrations/ciidi.sql y respaldo_bd_nosotros_fusionado.sql),
-- pero fueron omitidas por error en su archivo 008_cursos_investigaciones_lineas_google.sql.
-- ============================================================================

BEGIN;

-- 1. TABLA PIVOTE: INVESTIGACIONES <-> LÍNEAS DE INVESTIGACIÓN
CREATE TABLE IF NOT EXISTS public.investigaciones_lineas (
    id_investigacion INTEGER NOT NULL REFERENCES public.investigaciones_ofertadas(id) ON DELETE CASCADE,
    id_linea INTEGER NOT NULL REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE,
    CONSTRAINT pk_inv_lin PRIMARY KEY (id_investigacion, id_linea)
);

CREATE INDEX IF NOT EXISTS idx_inv_lineas_inv ON public.investigaciones_lineas(id_investigacion);
CREATE INDEX IF NOT EXISTS idx_inv_lineas_lin ON public.investigaciones_lineas(id_linea);

-- Sincronizar con las investigaciones ya existentes en investigaciones_ofertadas
INSERT INTO public.investigaciones_lineas (id_investigacion, id_linea)
SELECT id, id_linea FROM public.investigaciones_ofertadas WHERE id_linea IS NOT NULL
ON CONFLICT (id_investigacion, id_linea) DO NOTHING;

-- 2. TABLA PIVOTE: INVESTIGACIONES <-> DIMENSIONES OPERATIVAS
CREATE TABLE IF NOT EXISTS public.investigaciones_dimensiones (
    id_investigacion INTEGER NOT NULL REFERENCES public.investigaciones_ofertadas(id) ON DELETE CASCADE,
    id_dimension INTEGER NOT NULL REFERENCES public.dimensiones_operativas(id) ON DELETE CASCADE,
    CONSTRAINT pk_inv_dim PRIMARY KEY (id_investigacion, id_dimension)
);

CREATE INDEX IF NOT EXISTS idx_inv_dim_inv ON public.investigaciones_dimensiones(id_investigacion);
CREATE INDEX IF NOT EXISTS idx_inv_dim_dim ON public.investigaciones_dimensiones(id_dimension);

-- Sincronizar con las investigaciones ya existentes en investigaciones_ofertadas
INSERT INTO public.investigaciones_dimensiones (id_investigacion, id_dimension)
SELECT id, id_dimension FROM public.investigaciones_ofertadas WHERE id_dimension IS NOT NULL
ON CONFLICT (id_investigacion, id_dimension) DO NOTHING;

-- 3. TABLA DE PREINSCRIPCIONES DE CURSOS (omitida también en las migraciones numeradas del Equipo 2)
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

COMMIT;
