-- 1. Crear la nueva tabla exclusiva para artículos (investigadores)
CREATE TABLE public.investigadores (
    id SERIAL PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL,
    orcid VARCHAR(50) UNIQUE NOT NULL,
    biografia TEXT NULL,
    pagina_web VARCHAR(255) NULL
);

-- 2. Crear la nueva tabla pivote (recurso_investigadores)
CREATE TABLE public.recurso_investigadores (
    id_recurso INTEGER NOT NULL,
    id_investigador INTEGER NOT NULL,
    PRIMARY KEY (id_recurso, id_investigador),
    CONSTRAINT fk_recurso_inv FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE,
    CONSTRAINT fk_investigador FOREIGN KEY (id_investigador) REFERENCES public.investigadores(id) ON DELETE CASCADE
);

-- 3. Eliminar los campos sobrantes que ya no pertenecen a esta tabla
ALTER TABLE public.autores DROP COLUMN IF EXISTS orcid CASCADE;
ALTER TABLE public.autores DROP COLUMN IF EXISTS biografia CASCADE;
ALTER TABLE public.autores DROP COLUMN IF EXISTS pagina_web CASCADE;