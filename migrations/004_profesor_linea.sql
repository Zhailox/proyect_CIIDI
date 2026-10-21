-- ============================================================================
-- MIGRACIÓN 004: Afiliación de Profesores a Líneas de Investigación
-- Archivo: migrations/004_profesor_linea.sql
--
-- Crea la tabla pivote N:M public.profesor_linea que permite a los
-- administradores afiliar explícitamente docentes (usuarios) a una o varias
-- líneas de investigación. La vista pública "detalle-linea" combina esta
-- afiliación explícita con la afiliación derivada de proyectos
-- (investigaciones_ofertadas.id_profesor y proyecto_tutores).
--
-- Idempotente: puede ejecutarse varias veces sin efectos secundarios.
-- ============================================================================

BEGIN;

CREATE TABLE IF NOT EXISTS public.profesor_linea (
    id_usuario INTEGER NOT NULL,
    id_linea   INTEGER NOT NULL,
    created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_profesor_linea PRIMARY KEY (id_usuario, id_linea),
    CONSTRAINT fk_pl_usuario FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_pl_linea   FOREIGN KEY (id_linea)   REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_profesor_linea_linea ON public.profesor_linea(id_linea);

COMMENT ON TABLE public.profesor_linea IS 'Relación N:M de afiliación explícita de profesores (usuarios) a líneas de investigación.';

COMMIT;
