-- Migración 013: Campos de Perfil Académico e Investigador (Foto, ORCID, Sitio Personal y Grado Académico)
ALTER TABLE public.usuarios ADD COLUMN IF NOT EXISTS foto_perfil VARCHAR(255) DEFAULT NULL;
ALTER TABLE public.usuarios ADD COLUMN IF NOT EXISTS orcid VARCHAR(100) DEFAULT NULL;
ALTER TABLE public.usuarios ADD COLUMN IF NOT EXISTS sitio_web VARCHAR(255) DEFAULT NULL;
ALTER TABLE public.usuarios ADD COLUMN IF NOT EXISTS grado_academico VARCHAR(180) DEFAULT NULL;

-- Datos oficiales del Prof. Dr. Ricardo Dos Santos (https://ricardojdsg.github.io/) en el usuario docente id=10
UPDATE public.usuarios
SET nombre_completo = 'Dr. Ricardo Dos Santos',
    email = 'ricardojdsg@upttmbi.edu.ve',
    foto_perfil = 'public/uploads/perfiles/ricardo_dos_santos.jpg',
    orcid = '0000-0001-8752-8550',
    sitio_web = 'https://ricardojdsg.github.io/',
    grado_academico = 'Ingeniero de Sistemas · Magister Scientiae en Computación · Doctor en Ciencias Aplicadas',
    es_investigador = true
WHERE id = 10;
