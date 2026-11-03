-- Migración 010: Ampliar el rango de trayectos/niveles académicos permitidos (1..99)
-- para soportar nuevos trayectos (Trayecto V) y niveles de postgrado (Especialidad, Maestría, Doctorado)
-- configurables dinámicamente desde el panel de Configuración.

ALTER TABLE public.profesores_pst
    DROP CONSTRAINT IF EXISTS chk_profesores_pst_trayecto;

ALTER TABLE public.profesores_pst
    DROP CONSTRAINT IF EXISTS chk_prof_pst_trayecto;

ALTER TABLE public.profesores_pst
    ADD CONSTRAINT chk_profesores_pst_trayecto CHECK (trayecto BETWEEN 1 AND 99);

ALTER TABLE public.postulaciones_estudiantes
    DROP CONSTRAINT IF EXISTS chk_postulacion_trayecto;

ALTER TABLE public.postulaciones_estudiantes
    ADD CONSTRAINT chk_postulacion_trayecto CHECK (trayecto IS NULL OR (trayecto BETWEEN 1 AND 99));
