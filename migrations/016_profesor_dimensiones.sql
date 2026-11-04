-- Migración 012: Vinculación de Docentes/Investigadores con Dimensiones Operativas por Línea de Investigación
CREATE TABLE IF NOT EXISTS public.profesor_dimension (
    id_usuario INT NOT NULL REFERENCES public.usuarios(id) ON DELETE CASCADE,
    id_dimension INT NOT NULL REFERENCES public.dimensiones_operativas(id) ON DELETE CASCADE,
    id_linea INT NOT NULL REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_usuario, id_dimension)
);

CREATE INDEX IF NOT EXISTS idx_prof_dim_usuario ON public.profesor_dimension(id_usuario);
CREATE INDEX IF NOT EXISTS idx_prof_dim_linea ON public.profesor_dimension(id_linea);
