-- ============================================================================
-- MIGRACIÓN 005: Sector Productivo - Formulario configurable, archivo y reversión
-- Archivo: migrations/005_vinculacion_formulario_archivo.sql
--
-- Unifica:
-- 1. public.propuestas_empresa.campos_extra (JSONB): respuestas de los campos
--    personalizados del formulario "Postula tu Requerimiento Tecnológico".
--    Formato: arreglo ordenado [{"clave","etiqueta","tipo","valor"}] para que
--    la propuesta sea autodescriptiva aunque luego se renombre/elimine el campo.
-- 2. Archivo lógico (soft-delete) de propuestas: eliminado_en, motivo_archivo,
--    archivado_por. Las archivadas se ocultan de listados, cartelera y carrusel.
-- 3. Trazabilidad de reversión de propuestas aceptadas a 'pendiente':
--    revertido_en, motivo_reversion.
-- 4. public.postulaciones_estudiantes.motivo_rechazo: motivo registrado cuando
--    una postulación es rechazada/cancelada (p. ej. al revertir la propuesta).
--
-- Nota: al revertir una propuesta, la oferta vinculada en
-- public.investigaciones_ofertadas pasa al estado existente 'Cerrada'
-- (no se modifica el CHECK de esa tabla).
-- ============================================================================

BEGIN;

-- 1. RESPUESTAS DE CAMPOS PERSONALIZADOS
ALTER TABLE public.propuestas_empresa
    ADD COLUMN IF NOT EXISTS campos_extra JSONB NOT NULL DEFAULT '[]'::jsonb;

COMMENT ON COLUMN public.propuestas_empresa.campos_extra IS 'Respuestas de campos personalizados del formulario público: [{"clave","etiqueta","tipo","valor"}].';

-- 2. ARCHIVO LÓGICO (SOFT-DELETE)
ALTER TABLE public.propuestas_empresa
    ADD COLUMN IF NOT EXISTS eliminado_en TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS motivo_archivo TEXT DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS archivado_por INTEGER DEFAULT NULL;

DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_propuestas_archivado_por') THEN
        ALTER TABLE public.propuestas_empresa
            ADD CONSTRAINT fk_propuestas_archivado_por FOREIGN KEY (archivado_por) REFERENCES public.usuarios(id) ON DELETE SET NULL;
    END IF;
END $$;

CREATE INDEX IF NOT EXISTS idx_propuestas_empresa_eliminado_en ON public.propuestas_empresa(eliminado_en);

COMMENT ON COLUMN public.propuestas_empresa.eliminado_en IS 'NULL = activa. Fecha de archivo lógico (soft-delete) de la propuesta.';
COMMENT ON COLUMN public.propuestas_empresa.motivo_archivo IS 'Motivo indicado por el Comité al archivar la propuesta.';
COMMENT ON COLUMN public.propuestas_empresa.archivado_por IS 'Usuario que archivó la propuesta.';

-- 3. REVERSIÓN DE PROPUESTAS ACEPTADAS
ALTER TABLE public.propuestas_empresa
    ADD COLUMN IF NOT EXISTS revertido_en TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS motivo_reversion TEXT DEFAULT NULL;

COMMENT ON COLUMN public.propuestas_empresa.revertido_en IS 'Última fecha en que una propuesta aceptada fue revertida a pendiente.';
COMMENT ON COLUMN public.propuestas_empresa.motivo_reversion IS 'Motivo de la última reversión a pendiente.';

-- 4. MOTIVO DE RECHAZO/CANCELACIÓN DE POSTULACIONES
ALTER TABLE public.postulaciones_estudiantes
    ADD COLUMN IF NOT EXISTS motivo_rechazo TEXT DEFAULT NULL;

COMMENT ON COLUMN public.postulaciones_estudiantes.motivo_rechazo IS 'Motivo del rechazo o cancelación de la postulación (p. ej. propuesta empresarial revertida o archivada).';

COMMIT;
