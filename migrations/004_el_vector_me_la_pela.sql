ALTER TABLE autores ADD COLUMN biografia TEXT NULL;
-- 1. Elimina todos los autores, sus vínculos con artículos, y reinicia el ID (serial) a 1
TRUNCATE TABLE autores RESTART IDENTITY CASCADE;

-- 2. Eliminar la cédula
ALTER TABLE autores DROP COLUMN IF EXISTS cedula;

-- 3. Añadir la página web
ALTER TABLE autores ADD COLUMN IF NOT EXISTS pagina_web VARCHAR(255) DEFAULT NULL;

-- 4. Hacer el ORCID único y obligatorio (ahora sí funcionará porque la tabla está vacía)
ALTER TABLE autores ADD CONSTRAINT autores_orcid_key UNIQUE (orcid);
ALTER TABLE autores ALTER COLUMN orcid SET NOT NULL;