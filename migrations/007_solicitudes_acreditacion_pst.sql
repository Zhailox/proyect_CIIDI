-- ============================================================================
-- MIGRACIÓN 007: Solicitudes de Acreditación Docente PST y Ajuste de Trayectos
-- ============================================================================

BEGIN;

-- 1. Permitir que trimestre en profesores_pst sea nullable o default 1 (ahora se gestiona por Trayecto)
ALTER TABLE public.profesores_pst ALTER COLUMN trimestre DROP NOT NULL;
ALTER TABLE public.profesores_pst ALTER COLUMN trimestre SET DEFAULT 1;

DO $$
BEGIN
    IF EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'chk_profesores_pst_trimestre') THEN
        ALTER TABLE public.profesores_pst DROP CONSTRAINT chk_profesores_pst_trimestre;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'chk_profesores_pst_trimestre_null') THEN
        ALTER TABLE public.profesores_pst ADD CONSTRAINT chk_profesores_pst_trimestre_null CHECK (trimestre IS NULL OR (trimestre BETWEEN 1 AND 3));
    END IF;
END $$;

-- 2. Tabla de solicitudes de acreditación docente PST desde el perfil del profesor
CREATE TABLE IF NOT EXISTS public.solicitudes_docentes_pst (
    id SERIAL PRIMARY KEY,
    id_profesor INTEGER NOT NULL REFERENCES public.usuarios(id) ON DELETE CASCADE,
    es_profesor_pst BOOLEAN NOT NULL DEFAULT true,
    trayectos JSONB NOT NULL DEFAULT '[]'::jsonb,
    estado VARCHAR(20) NOT NULL DEFAULT 'pendiente',
    motivo TEXT,
    fecha_solicitud TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    fecha_resolucion TIMESTAMP WITHOUT TIME ZONE,
    id_revisor INTEGER REFERENCES public.usuarios(id) ON DELETE SET NULL,
    CONSTRAINT chk_solicitud_pst_estado CHECK (estado IN ('pendiente', 'aprobado', 'rechazado'))
);

CREATE INDEX IF NOT EXISTS idx_solicitudes_pst_prof ON public.solicitudes_docentes_pst(id_profesor);
CREATE INDEX IF NOT EXISTS idx_solicitudes_pst_estado ON public.solicitudes_docentes_pst(estado);

COMMIT;
