-- Migración 005: Compatibilidad de autores entre RepositorioPST y Artículos
-- Restaura la columna cédula para autores estudiantiles de PST
ALTER TABLE public.autores ADD COLUMN IF NOT EXISTS cedula VARCHAR(20) DEFAULT NULL;

-- Permite que el ORCID sea opcional (los estudiantes de PST no poseen obligatoriamente ORCID)
ALTER TABLE public.autores ALTER COLUMN orcid DROP NOT NULL;
