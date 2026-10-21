-- ============================================================================
-- MIGRACIÓN 004: Profesores PST y Postulaciones con Trayecto/Trimestre/Tutor
-- Archivo: migrations/004_profesores_pst_postulaciones.sql
--
-- Unifica:
-- 1. Auto-registro en línea de docentes con aprobación (usuarios.estado_registro)
--    y marca de Profesor PST (usuarios.es_profesor_pst).
-- 2. Pivote public.profesores_pst: trayecto(s)/trimestre(s) que dicta cada
--    profesor PST. El trayecto se guarda por su número (1..4), reutilizando el
--    concepto del catálogo public.trayectos (columna "numero").
-- 3. Nuevas columnas en public.postulaciones_estudiantes: trayecto, trimestre,
--    profesor PST elegido y validación del profesor (Pendiente/Aprobado/Devuelto),
--    previa o paralela a la asignación final del Comité (columna "estado").
-- ============================================================================

BEGIN;

-- 1. USUARIOS: ESTADO DE REGISTRO DOCENTE EN LÍNEA + MARCA PST
ALTER TABLE public.usuarios
    ADD COLUMN IF NOT EXISTS estado_registro VARCHAR(20) DEFAULT NULL;

ALTER TABLE public.usuarios
    ADD COLUMN IF NOT EXISTS es_profesor_pst BOOLEAN NOT NULL DEFAULT false;

DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'chk_usuarios_estado_registro') THEN
        ALTER TABLE public.usuarios
            ADD CONSTRAINT chk_usuarios_estado_registro
            CHECK (estado_registro IS NULL OR estado_registro IN ('pendiente', 'aprobado', 'rechazado'));
    END IF;
END $$;

COMMENT ON COLUMN public.usuarios.estado_registro IS 'NULL = flujo normal. pendiente/aprobado/rechazado = auto-registro docente en línea sujeto a aprobación del Comité/Super Administrador.';
COMMENT ON COLUMN public.usuarios.es_profesor_pst IS 'Indica si el docente imparte/tutoriza Proyecto Sociotecnológico (PST).';

-- 2. PIVOTE PROFESOR PST <-> TRAYECTO/TRIMESTRE
CREATE TABLE IF NOT EXISTS public.profesores_pst (
    id SERIAL PRIMARY KEY,
    id_profesor INTEGER NOT NULL,
    trayecto SMALLINT NOT NULL,
    trimestre SMALLINT NOT NULL,
    created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_profesores_pst_usuario FOREIGN KEY (id_profesor) REFERENCES public.usuarios(id) ON DELETE CASCADE,
    CONSTRAINT chk_profesores_pst_trayecto CHECK (trayecto BETWEEN 1 AND 4),
    CONSTRAINT chk_profesores_pst_trimestre CHECK (trimestre BETWEEN 1 AND 3),
    CONSTRAINT uq_profesores_pst UNIQUE (id_profesor, trayecto, trimestre)
);

CREATE INDEX IF NOT EXISTS idx_profesores_pst_tt ON public.profesores_pst(trayecto, trimestre);

COMMENT ON TABLE public.profesores_pst IS 'Trayecto(s) y trimestre(s) en los que cada profesor PST dicta/tutoriza el Proyecto Sociotecnológico.';

-- 3. POSTULACIONES DE ESTUDIANTES: TRAYECTO, TRIMESTRE Y PROFESOR PST
ALTER TABLE public.postulaciones_estudiantes
    ADD COLUMN IF NOT EXISTS trayecto SMALLINT,
    ADD COLUMN IF NOT EXISTS trimestre SMALLINT,
    ADD COLUMN IF NOT EXISTS id_profesor_pst INTEGER,
    ADD COLUMN IF NOT EXISTS aprobacion_profesor VARCHAR(20) NOT NULL DEFAULT 'Pendiente',
    ADD COLUMN IF NOT EXISTS motivo_profesor TEXT,
    ADD COLUMN IF NOT EXISTS fecha_revision_profesor TIMESTAMP WITHOUT TIME ZONE;

DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_postulacion_profesor_pst') THEN
        ALTER TABLE public.postulaciones_estudiantes
            ADD CONSTRAINT fk_postulacion_profesor_pst FOREIGN KEY (id_profesor_pst) REFERENCES public.usuarios(id) ON DELETE SET NULL;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'chk_postulacion_trayecto') THEN
        ALTER TABLE public.postulaciones_estudiantes
            ADD CONSTRAINT chk_postulacion_trayecto CHECK (trayecto IS NULL OR trayecto BETWEEN 1 AND 4);
    END IF;
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'chk_postulacion_trimestre') THEN
        ALTER TABLE public.postulaciones_estudiantes
            ADD CONSTRAINT chk_postulacion_trimestre CHECK (trimestre IS NULL OR trimestre BETWEEN 1 AND 3);
    END IF;
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'chk_postulacion_aprobacion_profesor') THEN
        ALTER TABLE public.postulaciones_estudiantes
            ADD CONSTRAINT chk_postulacion_aprobacion_profesor CHECK (aprobacion_profesor IN ('Pendiente', 'Aprobado', 'Devuelto'));
    END IF;
END $$;

CREATE INDEX IF NOT EXISTS idx_postulaciones_profesor_pst ON public.postulaciones_estudiantes(id_profesor_pst);

COMMENT ON COLUMN public.postulaciones_estudiantes.aprobacion_profesor IS 'Validación del profesor PST elegido: Pendiente, Aprobado o Devuelto (con motivo). La asignación final la realiza el Comité (columna estado).';

COMMIT;
