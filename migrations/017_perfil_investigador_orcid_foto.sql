-- Migración 017: Campos de Perfil Académico e Investigador (Foto, ORCID, Sitio Personal, Grado Académico y Biografía)
ALTER TABLE public.usuarios ADD COLUMN IF NOT EXISTS foto_perfil VARCHAR(255) DEFAULT NULL;
ALTER TABLE public.usuarios ADD COLUMN IF NOT EXISTS orcid VARCHAR(150) DEFAULT NULL;
ALTER TABLE public.usuarios ADD COLUMN IF NOT EXISTS sitio_web VARCHAR(255) DEFAULT NULL;
ALTER TABLE public.usuarios ADD COLUMN IF NOT EXISTS grado_academico VARCHAR(255) DEFAULT NULL;
ALTER TABLE public.usuarios ADD COLUMN IF NOT EXISTS biografia TEXT DEFAULT NULL;

