-- ============================================================================
-- MIGRACIÓN 002: Actualizaciones del Repositorio Institucional PST
-- Archivo: migrations/002_actualizaciones_repositorio_pst.sql
-- 
-- Unifica:
-- 1. Vinculación N:M de Carreras Secundarias (Interdisciplinarias) a Proyectos PST.
-- 2. Contador dinámico de visualizaciones de fichas PST.
-- 3. Depuración del nivel 'TSU' y conversión de columna a VARCHAR(50).
-- 4. Creación y población del catálogo dinámico de niveles académicos (public.niveles_academicos).
-- ============================================================================

BEGIN;

-- 1. CARRERAS SECUNDARIAS / INTERDISCIPLINARIAS VINCULADAS A PST
CREATE TABLE IF NOT EXISTS public.proyecto_carreras_vinculadas (
    id_recurso INTEGER NOT NULL,
    id_carrera INTEGER NOT NULL,
    created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_proyecto_carreras_vinculadas PRIMARY KEY (id_recurso, id_carrera),
    CONSTRAINT fk_pcv_recurso FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE,
    CONSTRAINT fk_pcv_carrera FOREIGN KEY (id_carrera) REFERENCES public.carreras(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_pcv_recurso ON public.proyecto_carreras_vinculadas(id_recurso);
CREATE INDEX IF NOT EXISTS idx_pcv_carrera ON public.proyecto_carreras_vinculadas(id_carrera);

COMMENT ON TABLE public.proyecto_carreras_vinculadas IS 'Relación N:M para vincular proyectos PST con carreras secundarias adicionales a su carrera base.';

-- 2. CONTADOR DE VISITAS DE FICHA TÉCNICA PST
ALTER TABLE public.detalles_proyectos 
ADD COLUMN IF NOT EXISTS vistas INTEGER NOT NULL DEFAULT 0;

COMMENT ON COLUMN public.detalles_proyectos.vistas IS 'Contador de visualizaciones de la ficha técnica del proyecto PST';

-- 3. TABLA DEL CATÁLOGO DINÁMICO DE NIVELES ACADÉMICOS
CREATE TABLE IF NOT EXISTS public.niveles_academicos (
    id SERIAL PRIMARY KEY,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    requiere_trayecto BOOLEAN DEFAULT FALSE,
    orden INT DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

COMMENT ON TABLE public.niveles_academicos IS 'Catálogo administrativo dinámico de niveles académicos para proyectos del repositorio';

-- 4. POBLAR NIVELES ACADÉMICOS BASE (Sin TSU, Pregrado con Trayectos, Postgrados sin Trayectos)
INSERT INTO public.niveles_academicos (codigo, nombre, descripcion, requiere_trayecto, orden, activo)
VALUES 
    ('Pregrado', 'Pregrado', 'Estudios conducentes a título universitario de pregrado o ingeniería', true, 1, true),
    ('Especializacion', 'Especialización', 'Estudios de postgrado para especialización técnica', false, 2, true),
    ('Maestria', 'Maestría', 'Estudios de postgrado de investigación científica y magíster', false, 3, true),
    ('Doctorado', 'Doctorado', 'Máximo grado académico de investigación epistémica', false, 4, true)
ON CONFLICT (codigo) DO UPDATE 
SET nombre = EXCLUDED.nombre, requiere_trayecto = EXCLUDED.requiere_trayecto, orden = EXCLUDED.orden, activo = EXCLUDED.activo;

-- 5. MIGRAR COLUMNA nivel_academico EN detalles_proyectos A VARCHAR(50) Y REEMPLAZAR CUALQUIER 'TSU' RESIDUAL POR 'Pregrado'
ALTER TABLE public.detalles_proyectos ALTER COLUMN nivel_academico DROP DEFAULT;

-- Si la columna era de tipo enum o texto, convertir asegurando que cualquier valor 'TSU' pase a 'Pregrado'
ALTER TABLE public.detalles_proyectos 
    ALTER COLUMN nivel_academico TYPE VARCHAR(50) 
    USING (CASE WHEN nivel_academico::text = 'TSU' THEN 'Pregrado' ELSE nivel_academico::text END);

ALTER TABLE public.detalles_proyectos ALTER COLUMN nivel_academico SET DEFAULT 'Pregrado';

-- Eliminar tipo enum antiguo si aún existe
DROP TYPE IF EXISTS public.nivel_academico_enum;
DROP TYPE IF EXISTS public.nivel_academico_enum_new;

COMMIT;
