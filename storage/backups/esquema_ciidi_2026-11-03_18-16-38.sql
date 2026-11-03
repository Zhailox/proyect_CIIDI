--
-- PostgreSQL database dump
--



-- Dumped from database version 18.4
-- Dumped by pg_dump version 18.4

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;

SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

ALTER TABLE IF EXISTS ONLY public.usuarios DROP CONSTRAINT IF EXISTS usuarios_id_rol_fkey;
ALTER TABLE IF EXISTS ONLY public.trayectos DROP CONSTRAINT IF EXISTS trayectos_id_carrera_fkey;
ALTER TABLE IF EXISTS ONLY public.solicitudes_docentes_pst DROP CONSTRAINT IF EXISTS solicitudes_docentes_pst_id_revisor_fkey;
ALTER TABLE IF EXISTS ONLY public.solicitudes_docentes_pst DROP CONSTRAINT IF EXISTS solicitudes_docentes_pst_id_profesor_fkey;
ALTER TABLE IF EXISTS ONLY public.registro_actividad DROP CONSTRAINT IF EXISTS registro_actividad_id_visitante_fkey;
ALTER TABLE IF EXISTS ONLY public.registro_actividad DROP CONSTRAINT IF EXISTS registro_actividad_id_usuario_fkey;
ALTER TABLE IF EXISTS ONLY public.recursos DROP CONSTRAINT IF EXISTS recursos_id_tipo_recurso_fkey;
ALTER TABLE IF EXISTS ONLY public.recurso_categorias DROP CONSTRAINT IF EXISTS recurso_categorias_id_recurso_fkey;
ALTER TABLE IF EXISTS ONLY public.recurso_categorias DROP CONSTRAINT IF EXISTS recurso_categorias_id_categoria_fkey;
ALTER TABLE IF EXISTS ONLY public.recurso_autores DROP CONSTRAINT IF EXISTS recurso_autores_id_recurso_fkey;
ALTER TABLE IF EXISTS ONLY public.recurso_autores DROP CONSTRAINT IF EXISTS recurso_autores_id_autor_fkey;
ALTER TABLE IF EXISTS ONLY public.proyecto_tutores DROP CONSTRAINT IF EXISTS proyecto_tutores_tipo_tutor_id_fkey;
ALTER TABLE IF EXISTS ONLY public.proyecto_tutores DROP CONSTRAINT IF EXISTS proyecto_tutores_id_tutor_fkey;
ALTER TABLE IF EXISTS ONLY public.proyecto_tutores DROP CONSTRAINT IF EXISTS proyecto_tutores_id_recurso_fkey;
ALTER TABLE IF EXISTS ONLY public.profesor_dimension DROP CONSTRAINT IF EXISTS profesor_dimension_id_usuario_fkey;
ALTER TABLE IF EXISTS ONLY public.profesor_dimension DROP CONSTRAINT IF EXISTS profesor_dimension_id_linea_fkey;
ALTER TABLE IF EXISTS ONLY public.profesor_dimension DROP CONSTRAINT IF EXISTS profesor_dimension_id_dimension_fkey;
ALTER TABLE IF EXISTS ONLY public.preferencias_usuario DROP CONSTRAINT IF EXISTS preferencias_usuario_id_usuario_fkey;
ALTER TABLE IF EXISTS ONLY public.notificaciones DROP CONSTRAINT IF EXISTS notificaciones_id_usuario_fkey;
ALTER TABLE IF EXISTS ONLY public.lineas_investigacion DROP CONSTRAINT IF EXISTS lineas_investigacion_id_carrera_fkey;
ALTER TABLE IF EXISTS ONLY public.investigaciones_lineas DROP CONSTRAINT IF EXISTS investigaciones_lineas_id_linea_fkey;
ALTER TABLE IF EXISTS ONLY public.investigaciones_lineas DROP CONSTRAINT IF EXISTS investigaciones_lineas_id_investigacion_fkey;
ALTER TABLE IF EXISTS ONLY public.investigaciones_dimensiones DROP CONSTRAINT IF EXISTS investigaciones_dimensiones_id_investigacion_fkey;
ALTER TABLE IF EXISTS ONLY public.investigaciones_dimensiones DROP CONSTRAINT IF EXISTS investigaciones_dimensiones_id_dimension_fkey;
ALTER TABLE IF EXISTS ONLY public.historico_versiones_pst DROP CONSTRAINT IF EXISTS fk_version_recurso;
ALTER TABLE IF EXISTS ONLY public.recurso_investigadores DROP CONSTRAINT IF EXISTS fk_recurso_inv;
ALTER TABLE IF EXISTS ONLY public.recurso_etiquetas DROP CONSTRAINT IF EXISTS fk_recurso_etiqueta;
ALTER TABLE IF EXISTS ONLY public.recurso_clasificaciones DROP CONSTRAINT IF EXISTS fk_recurso;
ALTER TABLE IF EXISTS ONLY public.propuestas_empresa DROP CONSTRAINT IF EXISTS fk_propuestas_archivado_por;
ALTER TABLE IF EXISTS ONLY public.profesores_pst DROP CONSTRAINT IF EXISTS fk_profesores_pst_usuario;
ALTER TABLE IF EXISTS ONLY public.postulaciones_estudiantes DROP CONSTRAINT IF EXISTS fk_postulacion_profesor_pst;
ALTER TABLE IF EXISTS ONLY public.postulaciones_estudiantes DROP CONSTRAINT IF EXISTS fk_postulacion_inv;
ALTER TABLE IF EXISTS ONLY public.postulaciones_estudiantes DROP CONSTRAINT IF EXISTS fk_postulacion_estudiante;
ALTER TABLE IF EXISTS ONLY public.profesor_linea DROP CONSTRAINT IF EXISTS fk_pl_usuario;
ALTER TABLE IF EXISTS ONLY public.profesor_linea DROP CONSTRAINT IF EXISTS fk_pl_linea;
ALTER TABLE IF EXISTS ONLY public.proyecto_carreras_vinculadas DROP CONSTRAINT IF EXISTS fk_pcv_recurso;
ALTER TABLE IF EXISTS ONLY public.proyecto_carreras_vinculadas DROP CONSTRAINT IF EXISTS fk_pcv_carrera;
ALTER TABLE IF EXISTS ONLY public.recurso_clasificaciones DROP CONSTRAINT IF EXISTS fk_linea_investigacion;
ALTER TABLE IF EXISTS ONLY public.recurso_investigadores DROP CONSTRAINT IF EXISTS fk_investigador;
ALTER TABLE IF EXISTS ONLY public.investigaciones_ofertadas DROP CONSTRAINT IF EXISTS fk_inv_profesor;
ALTER TABLE IF EXISTS ONLY public.investigaciones_ofertadas DROP CONSTRAINT IF EXISTS fk_inv_linea;
ALTER TABLE IF EXISTS ONLY public.investigaciones_ofertadas DROP CONSTRAINT IF EXISTS fk_inv_dimension;
ALTER TABLE IF EXISTS ONLY public.recurso_etiquetas DROP CONSTRAINT IF EXISTS fk_etiqueta_recurso;
ALTER TABLE IF EXISTS ONLY public.recurso_clasificaciones DROP CONSTRAINT IF EXISTS fk_dimension_operativa;
ALTER TABLE IF EXISTS ONLY public.dimensiones_operativas DROP CONSTRAINT IF EXISTS fk_dimension_linea;
ALTER TABLE IF EXISTS ONLY public.detalles_investigaciones DROP CONSTRAINT IF EXISTS fk_detalles_investigaciones_recurso;
ALTER TABLE IF EXISTS ONLY public.detalles_investigaciones DROP CONSTRAINT IF EXISTS fk_detalles_investigaciones_ofertada;
ALTER TABLE IF EXISTS ONLY public.detalles_articulos DROP CONSTRAINT IF EXISTS detalles_revistas_id_recurso_fkey;
ALTER TABLE IF EXISTS ONLY public.detalles_articulos DROP CONSTRAINT IF EXISTS detalles_revistas_id_editorial_fkey;
ALTER TABLE IF EXISTS ONLY public.detalles_proyectos DROP CONSTRAINT IF EXISTS detalles_proyectos_id_trayecto_fkey;
ALTER TABLE IF EXISTS ONLY public.detalles_proyectos DROP CONSTRAINT IF EXISTS detalles_proyectos_id_recurso_fkey;
ALTER TABLE IF EXISTS ONLY public.detalles_proyectos DROP CONSTRAINT IF EXISTS detalles_proyectos_id_investigacion_padre_fkey;
ALTER TABLE IF EXISTS ONLY public.detalles_proyectos DROP CONSTRAINT IF EXISTS detalles_proyectos_id_carrera_fkey;
ALTER TABLE IF EXISTS ONLY public.cursos_preinscripciones DROP CONSTRAINT IF EXISTS cursos_preinscripciones_id_curso_fkey;
ALTER TABLE IF EXISTS ONLY public.cursos_lineas DROP CONSTRAINT IF EXISTS cursos_lineas_id_linea_fkey;
ALTER TABLE IF EXISTS ONLY public.cursos_lineas DROP CONSTRAINT IF EXISTS cursos_lineas_id_curso_fkey;
ALTER TABLE IF EXISTS ONLY public.cursos DROP CONSTRAINT IF EXISTS cursos_id_docente_fkey;
ALTER TABLE IF EXISTS ONLY public.cursos_dimensiones DROP CONSTRAINT IF EXISTS cursos_dimensiones_id_dimension_fkey;
ALTER TABLE IF EXISTS ONLY public.cursos_dimensiones DROP CONSTRAINT IF EXISTS cursos_dimensiones_id_curso_fkey;
ALTER TABLE IF EXISTS ONLY public.auditoria DROP CONSTRAINT IF EXISTS auditoria_usuario_responsable_fkey;
ALTER TABLE IF EXISTS ONLY public.accesos_recursos DROP CONSTRAINT IF EXISTS accesos_recursos_id_registro_actividad_fkey;
ALTER TABLE IF EXISTS ONLY public.accesos_recursos DROP CONSTRAINT IF EXISTS accesos_recursos_id_recurso_fkey;
DROP TRIGGER IF EXISTS tg_auditoria_usuarios_update ON public.usuarios;
DROP TRIGGER IF EXISTS tg_auditoria_usuarios_insert ON public.usuarios;
DROP TRIGGER IF EXISTS tg_auditoria_usuarios_delete ON public.usuarios;
DROP TRIGGER IF EXISTS tg_auditoria_recursos_insert ON public.recursos;
DROP TRIGGER IF EXISTS tg_auditoria_recursos_delete ON public.recursos;
DROP INDEX IF EXISTS public.idx_trayectos_carrera;
DROP INDEX IF EXISTS public.idx_solicitudes_pst_prof;
DROP INDEX IF EXISTS public.idx_solicitudes_pst_estado;
DROP INDEX IF EXISTS public.idx_seg_lineas_logs_usuario;
DROP INDEX IF EXISTS public.idx_seg_lineas_logs_ip_res;
DROP INDEX IF EXISTS public.idx_seg_lineas_logs_fecha;
DROP INDEX IF EXISTS public.idx_seg_lineas_exc_usuario;
DROP INDEX IF EXISTS public.idx_seg_lineas_bloq_ip;
DROP INDEX IF EXISTS public.idx_seg_lineas_alertas_estado;
DROP INDEX IF EXISTS public.idx_recurso_clasif_linea;
DROP INDEX IF EXISTS public.idx_recurso_clasif_dimension;
DROP INDEX IF EXISTS public.idx_propuestas_empresa_eliminado_en;
DROP INDEX IF EXISTS public.idx_profesores_pst_tt;
DROP INDEX IF EXISTS public.idx_profesor_linea_linea;
DROP INDEX IF EXISTS public.idx_prof_dim_usuario;
DROP INDEX IF EXISTS public.idx_prof_dim_linea;
DROP INDEX IF EXISTS public.idx_postulaciones_profesor_pst;
DROP INDEX IF EXISTS public.idx_pcv_recurso;
DROP INDEX IF EXISTS public.idx_pcv_carrera;
DROP INDEX IF EXISTS public.idx_inv_lineas_lin;
DROP INDEX IF EXISTS public.idx_inv_lineas_inv;
DROP INDEX IF EXISTS public.idx_inv_dim_inv;
DROP INDEX IF EXISTS public.idx_inv_dim_dim;
DROP INDEX IF EXISTS public.idx_detalles_vector_null;
DROP INDEX IF EXISTS public.idx_detalles_vector_hnsw;
DROP INDEX IF EXISTS public.idx_detalles_proyectos_trayecto;
DROP INDEX IF EXISTS public.idx_detalles_inv_ofertada;
DROP INDEX IF EXISTS public.idx_cursos_preinscripciones_curso;
DROP INDEX IF EXISTS public.idx_cursos_lineas_linea;
DROP INDEX IF EXISTS public.idx_cursos_lineas_curso;
DROP INDEX IF EXISTS public.idx_cursos_dimensiones_dimension;
DROP INDEX IF EXISTS public.idx_cursos_dimensiones_curso;
DROP INDEX IF EXISTS public.idx_analitica_snapshots_fecha;
DROP INDEX IF EXISTS public.autores_cedula_key;
ALTER TABLE IF EXISTS ONLY public.waf_rate_limiter DROP CONSTRAINT IF EXISTS waf_rate_limiter_pkey;
ALTER TABLE IF EXISTS ONLY public.visitantes DROP CONSTRAINT IF EXISTS visitantes_pkey;
ALTER TABLE IF EXISTS ONLY public.usuarios DROP CONSTRAINT IF EXISTS usuarios_pkey;
ALTER TABLE IF EXISTS ONLY public.usuarios DROP CONSTRAINT IF EXISTS usuarios_email_key;
ALTER TABLE IF EXISTS ONLY public.usuarios DROP CONSTRAINT IF EXISTS usuarios_cedula_key;
ALTER TABLE IF EXISTS ONLY public.profesores_pst DROP CONSTRAINT IF EXISTS uq_profesores_pst;
ALTER TABLE IF EXISTS ONLY public.trayectos DROP CONSTRAINT IF EXISTS uq_carrera_trayecto;
ALTER TABLE IF EXISTS ONLY public.postulaciones_estudiantes DROP CONSTRAINT IF EXISTS unique_postulacion;
ALTER TABLE IF EXISTS ONLY public.privilegios DROP CONSTRAINT IF EXISTS unique_nivel_privilegio;
ALTER TABLE IF EXISTS ONLY public.tutores DROP CONSTRAINT IF EXISTS tutores_pkey;
ALTER TABLE IF EXISTS ONLY public.tutores DROP CONSTRAINT IF EXISTS tutores_cedula_key;
ALTER TABLE IF EXISTS ONLY public.trayectos DROP CONSTRAINT IF EXISTS trayectos_pkey;
ALTER TABLE IF EXISTS ONLY public.tipo_tutor DROP CONSTRAINT IF EXISTS tipo_tutor_pkey;
ALTER TABLE IF EXISTS ONLY public.tipo_tutor DROP CONSTRAINT IF EXISTS tipo_tutor_nombre_key;
ALTER TABLE IF EXISTS ONLY public.tipo_recurso DROP CONSTRAINT IF EXISTS tipo_recurso_pkey;
ALTER TABLE IF EXISTS ONLY public.tipo_recurso DROP CONSTRAINT IF EXISTS tipo_recurso_nombre_key;
ALTER TABLE IF EXISTS ONLY public.telemetria_cache DROP CONSTRAINT IF EXISTS telemetria_cache_pkey;
ALTER TABLE IF EXISTS ONLY public.system_audit_log DROP CONSTRAINT IF EXISTS system_audit_log_pkey;
ALTER TABLE IF EXISTS ONLY public.solicitudes_docentes_pst DROP CONSTRAINT IF EXISTS solicitudes_docentes_pst_pkey;
ALTER TABLE IF EXISTS ONLY public.seg_lineas_rutas DROP CONSTRAINT IF EXISTS seg_lineas_rutas_pkey;
ALTER TABLE IF EXISTS ONLY public.seg_lineas_politicas DROP CONSTRAINT IF EXISTS seg_lineas_politicas_pkey;
ALTER TABLE IF EXISTS ONLY public.seg_lineas_permisos DROP CONSTRAINT IF EXISTS seg_lineas_permisos_pkey;
ALTER TABLE IF EXISTS ONLY public.seg_lineas_logs DROP CONSTRAINT IF EXISTS seg_lineas_logs_pkey;
ALTER TABLE IF EXISTS ONLY public.seg_lineas_excepciones DROP CONSTRAINT IF EXISTS seg_lineas_excepciones_pkey;
ALTER TABLE IF EXISTS ONLY public.seg_lineas_bloqueos DROP CONSTRAINT IF EXISTS seg_lineas_bloqueos_pkey;
ALTER TABLE IF EXISTS ONLY public.seg_lineas_alertas DROP CONSTRAINT IF EXISTS seg_lineas_alertas_pkey;
ALTER TABLE IF EXISTS ONLY public.roles DROP CONSTRAINT IF EXISTS roles_pkey;
ALTER TABLE IF EXISTS ONLY public.roles DROP CONSTRAINT IF EXISTS roles_nombre_key;
ALTER TABLE IF EXISTS ONLY public.registro_actividad DROP CONSTRAINT IF EXISTS registro_actividad_pkey;
ALTER TABLE IF EXISTS ONLY public.recursos DROP CONSTRAINT IF EXISTS recursos_pkey;
ALTER TABLE IF EXISTS ONLY public.recurso_investigadores DROP CONSTRAINT IF EXISTS recurso_investigadores_pkey;
ALTER TABLE IF EXISTS ONLY public.recurso_etiquetas DROP CONSTRAINT IF EXISTS recurso_etiquetas_pkey;
ALTER TABLE IF EXISTS ONLY public.recurso_clasificaciones DROP CONSTRAINT IF EXISTS recurso_clasificaciones_pkey;
ALTER TABLE IF EXISTS ONLY public.recurso_categorias DROP CONSTRAINT IF EXISTS recurso_categorias_pkey;
ALTER TABLE IF EXISTS ONLY public.recurso_autores DROP CONSTRAINT IF EXISTS recurso_autores_pkey;
ALTER TABLE IF EXISTS ONLY public.proyecto_tutores DROP CONSTRAINT IF EXISTS proyecto_tutores_pkey;
ALTER TABLE IF EXISTS ONLY public.propuestas_empresa DROP CONSTRAINT IF EXISTS propuestas_empresa_pkey;
ALTER TABLE IF EXISTS ONLY public.propuestas_empresa DROP CONSTRAINT IF EXISTS propuestas_empresa_codigo_seguimiento_key;
ALTER TABLE IF EXISTS ONLY public.profesores_pst DROP CONSTRAINT IF EXISTS profesores_pst_pkey;
ALTER TABLE IF EXISTS ONLY public.profesor_dimension DROP CONSTRAINT IF EXISTS profesor_dimension_pkey;
ALTER TABLE IF EXISTS ONLY public.preferencias_usuario DROP CONSTRAINT IF EXISTS preferencias_usuario_pkey;
ALTER TABLE IF EXISTS ONLY public.postulaciones_estudiantes DROP CONSTRAINT IF EXISTS postulaciones_estudiantes_pkey;
ALTER TABLE IF EXISTS ONLY public.proyecto_carreras_vinculadas DROP CONSTRAINT IF EXISTS pk_proyecto_carreras_vinculadas;
ALTER TABLE IF EXISTS ONLY public.profesor_linea DROP CONSTRAINT IF EXISTS pk_profesor_linea;
ALTER TABLE IF EXISTS ONLY public.investigaciones_lineas DROP CONSTRAINT IF EXISTS pk_inv_lin;
ALTER TABLE IF EXISTS ONLY public.investigaciones_dimensiones DROP CONSTRAINT IF EXISTS pk_inv_dim;
ALTER TABLE IF EXISTS ONLY public.password_resets DROP CONSTRAINT IF EXISTS password_resets_pkey;
ALTER TABLE IF EXISTS ONLY public.notificaciones DROP CONSTRAINT IF EXISTS notificaciones_pkey;
ALTER TABLE IF EXISTS ONLY public.niveles_academicos DROP CONSTRAINT IF EXISTS niveles_academicos_pkey;
ALTER TABLE IF EXISTS ONLY public.niveles_academicos DROP CONSTRAINT IF EXISTS niveles_academicos_codigo_key;
ALTER TABLE IF EXISTS ONLY public.matriz_rbac DROP CONSTRAINT IF EXISTS matriz_rbac_pkey;
ALTER TABLE IF EXISTS ONLY public.lineas_investigacion DROP CONSTRAINT IF EXISTS lineas_investigacion_pkey;
ALTER TABLE IF EXISTS ONLY public.investigadores DROP CONSTRAINT IF EXISTS investigadores_pkey;
ALTER TABLE IF EXISTS ONLY public.investigadores DROP CONSTRAINT IF EXISTS investigadores_orcid_key;
ALTER TABLE IF EXISTS ONLY public.investigaciones_ofertadas DROP CONSTRAINT IF EXISTS investigaciones_ofertadas_pkey;
ALTER TABLE IF EXISTS ONLY public.historico_versiones_pst DROP CONSTRAINT IF EXISTS historico_versiones_pst_pkey;
ALTER TABLE IF EXISTS ONLY public.etiquetas DROP CONSTRAINT IF EXISTS etiquetas_pkey;
ALTER TABLE IF EXISTS ONLY public.etiquetas DROP CONSTRAINT IF EXISTS etiquetas_nombre_key;
ALTER TABLE IF EXISTS ONLY public.editoriales DROP CONSTRAINT IF EXISTS editoriales_pkey;
ALTER TABLE IF EXISTS ONLY public.editoriales DROP CONSTRAINT IF EXISTS editoriales_nombre_key;
ALTER TABLE IF EXISTS ONLY public.dimensiones_operativas DROP CONSTRAINT IF EXISTS dimensiones_operativas_pkey;
ALTER TABLE IF EXISTS ONLY public.detalles_articulos DROP CONSTRAINT IF EXISTS detalles_revistas_pkey;
ALTER TABLE IF EXISTS ONLY public.detalles_proyectos DROP CONSTRAINT IF EXISTS detalles_proyectos_pkey;
ALTER TABLE IF EXISTS ONLY public.detalles_investigaciones DROP CONSTRAINT IF EXISTS detalles_investigaciones_pkey;
ALTER TABLE IF EXISTS ONLY public.cursos DROP CONSTRAINT IF EXISTS cursos_slug_key;
ALTER TABLE IF EXISTS ONLY public.cursos_preinscripciones DROP CONSTRAINT IF EXISTS cursos_preinscripciones_pkey;
ALTER TABLE IF EXISTS ONLY public.cursos DROP CONSTRAINT IF EXISTS cursos_pkey;
ALTER TABLE IF EXISTS ONLY public.cursos_lineas DROP CONSTRAINT IF EXISTS cursos_lineas_pkey;
ALTER TABLE IF EXISTS ONLY public.cursos_dimensiones DROP CONSTRAINT IF EXISTS cursos_dimensiones_pkey;
ALTER TABLE IF EXISTS ONLY public.categorias DROP CONSTRAINT IF EXISTS categorias_pkey;
ALTER TABLE IF EXISTS ONLY public.categorias DROP CONSTRAINT IF EXISTS categorias_nombre_key;
ALTER TABLE IF EXISTS ONLY public.carreras DROP CONSTRAINT IF EXISTS carreras_pkey;
ALTER TABLE IF EXISTS ONLY public.carreras DROP CONSTRAINT IF EXISTS carreras_nombre_key;
ALTER TABLE IF EXISTS ONLY public.autores DROP CONSTRAINT IF EXISTS autores_pkey;
ALTER TABLE IF EXISTS ONLY public.auditoria DROP CONSTRAINT IF EXISTS auditoria_pkey;
ALTER TABLE IF EXISTS ONLY public.analitica_snapshots DROP CONSTRAINT IF EXISTS analitica_snapshots_pkey;
ALTER TABLE IF EXISTS ONLY public.accesos_recursos DROP CONSTRAINT IF EXISTS accesos_recursos_pkey;
ALTER TABLE IF EXISTS public.visitantes ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.usuarios ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.tutores ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.trayectos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.tipo_tutor ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.tipo_recurso ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.solicitudes_docentes_pst ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.seg_lineas_logs ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.seg_lineas_excepciones ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.seg_lineas_bloqueos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.seg_lineas_alertas ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.roles ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.registro_actividad ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.recursos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.propuestas_empresa ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.profesores_pst ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.privilegios ALTER COLUMN privilegio_id DROP DEFAULT;
ALTER TABLE IF EXISTS public.postulaciones_estudiantes ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.password_resets ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.notificaciones ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.niveles_academicos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.lineas_investigacion ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.investigadores ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.investigaciones_ofertadas ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.historico_versiones_pst ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.etiquetas ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.editoriales ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.dimensiones_operativas ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.cursos_preinscripciones ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.cursos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.categorias ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.carreras ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.autores ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.auditoria ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.analitica_snapshots ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.accesos_recursos ALTER COLUMN id DROP DEFAULT;
DROP TABLE IF EXISTS public.waf_rate_limiter;
DROP SEQUENCE IF EXISTS public.visitantes_id_seq;
DROP TABLE IF EXISTS public.visitantes;
DROP SEQUENCE IF EXISTS public.usuarios_id_seq;
DROP TABLE IF EXISTS public.usuarios;
DROP SEQUENCE IF EXISTS public.tutores_id_seq;
DROP TABLE IF EXISTS public.tutores;
DROP SEQUENCE IF EXISTS public.trayectos_id_seq;
DROP TABLE IF EXISTS public.trayectos;
DROP SEQUENCE IF EXISTS public.tipo_tutor_id_seq;
DROP TABLE IF EXISTS public.tipo_tutor;
DROP SEQUENCE IF EXISTS public.tipo_recurso_id_seq;
DROP TABLE IF EXISTS public.tipo_recurso;
DROP TABLE IF EXISTS public.telemetria_cache;
DROP TABLE IF EXISTS public.system_audit_log;
DROP SEQUENCE IF EXISTS public.solicitudes_docentes_pst_id_seq;
DROP TABLE IF EXISTS public.solicitudes_docentes_pst;
DROP TABLE IF EXISTS public.seg_lineas_rutas;
DROP TABLE IF EXISTS public.seg_lineas_politicas;
DROP TABLE IF EXISTS public.seg_lineas_permisos;
DROP SEQUENCE IF EXISTS public.seg_lineas_logs_id_seq;
DROP TABLE IF EXISTS public.seg_lineas_logs;
DROP SEQUENCE IF EXISTS public.seg_lineas_excepciones_id_seq;
DROP TABLE IF EXISTS public.seg_lineas_excepciones;
DROP SEQUENCE IF EXISTS public.seg_lineas_bloqueos_id_seq;
DROP TABLE IF EXISTS public.seg_lineas_bloqueos;
DROP SEQUENCE IF EXISTS public.seg_lineas_alertas_id_seq;
DROP TABLE IF EXISTS public.seg_lineas_alertas;
DROP SEQUENCE IF EXISTS public.roles_id_seq;
DROP TABLE IF EXISTS public.roles;
DROP SEQUENCE IF EXISTS public.registro_actividad_id_seq;
DROP TABLE IF EXISTS public.registro_actividad;
DROP SEQUENCE IF EXISTS public.recursos_id_seq;
DROP TABLE IF EXISTS public.recursos;
DROP TABLE IF EXISTS public.recurso_investigadores;
DROP TABLE IF EXISTS public.recurso_etiquetas;
DROP TABLE IF EXISTS public.recurso_clasificaciones;
DROP TABLE IF EXISTS public.recurso_categorias;
DROP TABLE IF EXISTS public.recurso_autores;
DROP TABLE IF EXISTS public.proyecto_tutores;
DROP TABLE IF EXISTS public.proyecto_carreras_vinculadas;
DROP SEQUENCE IF EXISTS public.propuestas_empresa_id_seq;
DROP TABLE IF EXISTS public.propuestas_empresa;
DROP SEQUENCE IF EXISTS public.profesores_pst_id_seq;
DROP TABLE IF EXISTS public.profesores_pst;
DROP TABLE IF EXISTS public.profesor_linea;
DROP TABLE IF EXISTS public.profesor_dimension;
DROP SEQUENCE IF EXISTS public.privilegios_privilegio_id_seq;
DROP TABLE IF EXISTS public.privilegios;
DROP TABLE IF EXISTS public.preferencias_usuario;
DROP SEQUENCE IF EXISTS public.postulaciones_estudiantes_id_seq;
DROP TABLE IF EXISTS public.postulaciones_estudiantes;
DROP SEQUENCE IF EXISTS public.password_resets_id_seq;
DROP TABLE IF EXISTS public.password_resets;
DROP SEQUENCE IF EXISTS public.notificaciones_id_seq;
DROP TABLE IF EXISTS public.notificaciones;
DROP SEQUENCE IF EXISTS public.niveles_academicos_id_seq;
DROP TABLE IF EXISTS public.niveles_academicos;
DROP TABLE IF EXISTS public.matriz_rbac;
DROP SEQUENCE IF EXISTS public.lineas_investigacion_id_seq;
DROP TABLE IF EXISTS public.lineas_investigacion;
DROP SEQUENCE IF EXISTS public.investigadores_id_seq;
DROP TABLE IF EXISTS public.investigadores;
DROP SEQUENCE IF EXISTS public.investigaciones_ofertadas_id_seq;
DROP TABLE IF EXISTS public.investigaciones_ofertadas;
DROP TABLE IF EXISTS public.investigaciones_lineas;
DROP TABLE IF EXISTS public.investigaciones_dimensiones;
DROP SEQUENCE IF EXISTS public.historico_versiones_pst_id_seq;
DROP TABLE IF EXISTS public.historico_versiones_pst;
DROP SEQUENCE IF EXISTS public.etiquetas_id_seq;
DROP TABLE IF EXISTS public.etiquetas;
DROP SEQUENCE IF EXISTS public.editoriales_id_seq;
DROP TABLE IF EXISTS public.editoriales;
DROP SEQUENCE IF EXISTS public.dimensiones_operativas_id_seq;
DROP TABLE IF EXISTS public.dimensiones_operativas;
DROP TABLE IF EXISTS public.detalles_proyectos;
DROP TABLE IF EXISTS public.detalles_investigaciones;
DROP TABLE IF EXISTS public.detalles_articulos;
DROP SEQUENCE IF EXISTS public.cursos_preinscripciones_id_seq;
DROP TABLE IF EXISTS public.cursos_preinscripciones;
DROP TABLE IF EXISTS public.cursos_lineas;
DROP SEQUENCE IF EXISTS public.cursos_id_seq;
DROP TABLE IF EXISTS public.cursos_dimensiones;
DROP TABLE IF EXISTS public.cursos;
DROP SEQUENCE IF EXISTS public.categorias_id_seq;
DROP TABLE IF EXISTS public.categorias;
DROP SEQUENCE IF EXISTS public.carreras_id_seq;
DROP TABLE IF EXISTS public.carreras;
DROP SEQUENCE IF EXISTS public.autores_id_seq;
DROP TABLE IF EXISTS public.autores;
DROP SEQUENCE IF EXISTS public.auditoria_id_seq;
DROP TABLE IF EXISTS public.auditoria;
DROP SEQUENCE IF EXISTS public.analitica_snapshots_id_seq;
DROP TABLE IF EXISTS public.analitica_snapshots;
DROP SEQUENCE IF EXISTS public.accesos_recursos_id_seq;
DROP TABLE IF EXISTS public.accesos_recursos;
DROP FUNCTION IF EXISTS public.normalizar_busqueda_pst(input_text text);
DROP PROCEDURE IF EXISTS public.insertarproyectoaleatorio(IN fecha_creada timestamp without time zone);
DROP FUNCTION IF EXISTS public.fn_auditoria_usuarios();
DROP FUNCTION IF EXISTS public.fn_auditoria_recursos();
DROP TYPE IF EXISTS public.tipo_pregunta_enum;
DROP TYPE IF EXISTS public.tipo_interaccion_usuario_enum;
DROP TYPE IF EXISTS public.tipo_interaccion_enum;
DROP TYPE IF EXISTS public.nivel_academico_enum;
DROP TYPE IF EXISTS public.estado_propuesta_enum;
DROP TYPE IF EXISTS public.estado_curso_enum;
DROP TYPE IF EXISTS public.accion_auditoria_enum;
DROP TYPE IF EXISTS public.accion_acceso_enum;

DROP EXTENSION IF EXISTS unaccent;
DROP EXTENSION IF EXISTS pg_trgm;
--
-- Name: pg_trgm; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS pg_trgm WITH SCHEMA public;


--
-- Name: EXTENSION pg_trgm; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION pg_trgm IS 'text similarity measurement and index searching based on trigrams';


--
-- Name: unaccent; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS unaccent WITH SCHEMA public;


--
-- Name: EXTENSION unaccent; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION unaccent IS 'text search dictionary that removes accents';


--
-- Name: vector; Type: EXTENSION; Schema: -; Owner: -
--

DO $$ BEGIN CREATE EXTENSION IF NOT EXISTS vector WITH SCHEMA public; EXCEPTION WHEN OTHERS THEN NULL; END $$;


--
-- Name: EXTENSION vector; Type: COMMENT; Schema: -; Owner: -
--




--
-- Name: accion_acceso_enum; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.accion_acceso_enum AS ENUM (
    'visualizacion',
    'descarga'
);


--
-- Name: accion_auditoria_enum; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.accion_auditoria_enum AS ENUM (
    'INSERT',
    'UPDATE',
    'DELETE'
);


--
-- Name: estado_curso_enum; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.estado_curso_enum AS ENUM (
    'borrador',
    'publicado',
    'archivado'
);


--
-- Name: estado_propuesta_enum; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.estado_propuesta_enum AS ENUM (
    'pendiente',
    'aceptada',
    'rechazada'
);


--
-- Name: nivel_academico_enum; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.nivel_academico_enum AS ENUM (
    'Pregrado',
    'Especializacion',
    'Maestria',
    'Doctorado'
);


--
-- Name: tipo_interaccion_enum; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.tipo_interaccion_enum AS ENUM (
    'like',
    'bookmark'
);


--
-- Name: tipo_interaccion_usuario_enum; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.tipo_interaccion_usuario_enum AS ENUM (
    'like',
    'guardado'
);


--
-- Name: tipo_pregunta_enum; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.tipo_pregunta_enum AS ENUM (
    'multiple',
    'v_f',
    'corta'
);


--
-- Name: fn_auditoria_recursos(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.fn_auditoria_recursos() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    v_usuario_actual INT := NULLIF(current_setting('app.usuario_actual', true), '')::INT; 
BEGIN
    IF (TG_OP = 'DELETE') THEN
        INSERT INTO auditoria (tabla_afectada, id_registro, accion, usuario_responsable, datos_anteriores, datos_nuevos, fecha_hora)
        VALUES ('recursos', OLD.id, 'DELETE', v_usuario_actual, jsonb_build_object('titulo', OLD.titulo, 'id_tipo_recurso', OLD.id_tipo_recurso), NULL, CURRENT_TIMESTAMP);
        RETURN OLD;
        
    ELSIF (TG_OP = 'INSERT') THEN
        -- AquÃ­ se removiÃ³ la referencia a NEW.ejemplares_totales que hacÃ­a explotar la BD
        INSERT INTO auditoria (tabla_afectada, id_registro, accion, usuario_responsable, datos_anteriores, datos_nuevos, fecha_hora)
        VALUES ('recursos', NEW.id, 'INSERT', v_usuario_actual, NULL, jsonb_build_object('titulo', NEW.titulo, 'id_tipo_recurso', NEW.id_tipo_recurso), CURRENT_TIMESTAMP);
        RETURN NEW;
    END IF;
    
    RETURN NULL;
END;
$$;


--
-- Name: fn_auditoria_usuarios(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.fn_auditoria_usuarios() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    v_usuario_actual INT := NULLIF(current_setting('app.usuario_actual', true), '')::INT;
BEGIN
    IF (TG_OP = 'DELETE') THEN
        INSERT INTO auditoria (tabla_afectada, id_registro, accion, usuario_responsable, datos_anteriores, datos_nuevos, fecha_hora)
        VALUES ('usuarios', OLD.id, 'DELETE', v_usuario_actual, jsonb_build_object('nombre', OLD.nombre_completo, 'email', OLD.email, 'id_rol', OLD.id_rol), NULL, CURRENT_TIMESTAMP);
        RETURN OLD;
        
    ELSIF (TG_OP = 'INSERT') THEN
        INSERT INTO auditoria (tabla_afectada, id_registro, accion, usuario_responsable, datos_anteriores, datos_nuevos, fecha_hora)
        VALUES ('usuarios', NEW.id, 'INSERT', v_usuario_actual, NULL, jsonb_build_object('nombre', NEW.nombre_completo, 'email', NEW.email, 'id_rol', NEW.id_rol), CURRENT_TIMESTAMP);
        RETURN NEW;
        
    ELSIF (TG_OP = 'UPDATE') THEN
        INSERT INTO auditoria (tabla_afectada, id_registro, accion, usuario_responsable, datos_anteriores, datos_nuevos, fecha_hora)
        VALUES ('usuarios', OLD.id, 'UPDATE', v_usuario_actual, 
                jsonb_build_object('nombre', OLD.nombre_completo, 'id_rol', OLD.id_rol, 'activo', OLD.activo), 
                jsonb_build_object('nombre', NEW.nombre_completo, 'id_rol', NEW.id_rol, 'activo', NEW.activo), 
                CURRENT_TIMESTAMP);
        RETURN NEW;
    END IF;
    
    RETURN NULL;
END;
$$;


--
-- Name: insertarproyectoaleatorio(timestamp without time zone); Type: PROCEDURE; Schema: public; Owner: -
--

CREATE PROCEDURE public.insertarproyectoaleatorio(IN fecha_creada timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
DECLARE
    nuevo_id INT;
    nivel_academico_aleatorio nivel_academico_enum;
    carrera_aleatoria INT;
    titulo_base VARCHAR(255);
    palabras_clave_gen TEXT;
    resumen_texto TEXT;
    mes_actual INT;
    
    -- Arrays para simular el ELT de MySQL
    arr_niveles1 VARCHAR[] := ARRAY['TSU', 'Pregrado', 'Especializacion', 'Maestria'];
    arr_niveles2 VARCHAR[] := ARRAY['Especializacion', 'Maestria', 'Doctorado'];
    arr_niveles3 VARCHAR[] := ARRAY['TSU', 'Pregrado', 'Especializacion', 'Maestria', 'Doctorado'];
    
    arr_tit_acc VARCHAR[] := ARRAY['Sistema', 'AplicaciÃ³n', 'Plataforma', 'Prototipo', 'AnÃ¡lisis', 'DiseÃ±o', 'ImplementaciÃ³n', 'OptimizaciÃ³n', 'AutomatizaciÃ³n', 'EvaluaciÃ³n', 'Desarrollo', 'Modelo'];
    arr_tit_obj VARCHAR[] := ARRAY['GestiÃ³n', 'Monitoreo', 'Control', 'PredicciÃ³n', 'Seguridad', 'Reconocimiento', 'ClasificaciÃ³n', 'Procesamiento', 'VisualizaciÃ³n', 'ComunicaciÃ³n', 'Bajo Costo', 'Alto Rendimiento'];
    arr_tit_dest VARCHAR[] := ARRAY['la Comunidad', 'UPTTMBI', 'el Sector Agroalimentario', 'Zonas Rurales', 'Instituciones Educativas', 'PequeÃ±as Empresas', 'el Ãrea de Salud', 'el Transporte PÃºblico', 'la Industria 4.0', 'la TransformaciÃ³n Digital'];
    
    arr_carreras VARCHAR[] := ARRAY['InformÃ¡tica', 'Electricidad', 'AdministraciÃ³n', 'AgroalimentaciÃ³n', 'ConstrucciÃ³n Civil'];
BEGIN
    mes_actual := EXTRACT(MONTH FROM fecha_creada);

    -- Asignar nivel acadÃ©mico
    IF mes_actual IN (4,8) THEN
        nivel_academico_aleatorio := arr_niveles1[floor(random() * 4 + 1)::int]::nivel_academico_enum;
    ELSIF mes_actual = 12 THEN
        nivel_academico_aleatorio := arr_niveles2[floor(random() * 3 + 1)::int]::nivel_academico_enum;
    ELSE
        nivel_academico_aleatorio := arr_niveles3[floor(random() * 5 + 1)::int]::nivel_academico_enum;
    END IF;

    -- Ajustar pesos especÃ­ficos
    IF random() < 0.4 THEN 
        nivel_academico_aleatorio := 'Pregrado';
    ELSIF random() < 0.25 THEN 
        nivel_academico_aleatorio := 'TSU';
    END IF;

    -- Carrera aleatoria
    carrera_aleatoria := floor(random() * 5 + 1)::int;

    -- TÃ­tulo dinÃ¡mico
    titulo_base := arr_tit_acc[floor(random() * 12 + 1)::int] || ' de ' || 
                   arr_tit_obj[floor(random() * 12 + 1)::int] || ' para ' || 
                   arr_tit_dest[floor(random() * 10 + 1)::int];

    -- Resumen
    resumen_texto := 'Proyecto desarrollado en ' || arr_carreras[carrera_aleatoria] || '. Aborda problemÃ¡ticas reales con enfoque prÃ¡ctico.';

    -- Insertar recurso y capturar el ID generado (RETURNING id)
    INSERT INTO recursos (titulo, id_tipo_recurso, anio_publicacion, ejemplares_totales, ejemplares_disponibles)
    VALUES (titulo_base, 1, EXTRACT(YEAR FROM fecha_creada), 1, 1)
    RETURNING id INTO nuevo_id;

    -- Insertar detalle proyecto
    INSERT INTO detalles_proyectos (id_recurso, fecha_defensa, nivel_academico, resumen, id_carrera, comunidad_beneficiada, palabras_clave, created_at) 
    VALUES (nuevo_id, (fecha_creada + (floor(random() * 90)::int || ' days')::interval)::date, nivel_academico_aleatorio, resumen_texto, carrera_aleatoria, 'Comunidad Generica', 'tecnologia, innovacion', fecha_creada);

    -- Relacionar con un autor aleatorio o el autor por defecto 1
    INSERT INTO recurso_autores (id_recurso, id_autor)
    SELECT nuevo_id, id FROM autores WHERE id BETWEEN 14 AND 29 ORDER BY random() LIMIT 1;
    
    -- Si no insertÃ³ (por no hallar autor en ese rango), fuerza el autor 1
    IF NOT FOUND THEN
        INSERT INTO recurso_autores (id_recurso, id_autor) VALUES (nuevo_id, 1);
    END IF;
END;
$$;


--
-- Name: normalizar_busqueda_pst(text); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.normalizar_busqueda_pst(input_text text) RETURNS text
    LANGUAGE sql IMMUTABLE
    AS $$
                    SELECT LOWER(public.unaccent(COALESCE(input_text, '')));
                $$;


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: accesos_recursos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.accesos_recursos (
    id integer NOT NULL,
    id_registro_actividad integer NOT NULL,
    id_recurso integer NOT NULL,
    accion public.accion_acceso_enum DEFAULT 'visualizacion'::public.accion_acceso_enum,
    fecha_acceso timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- Name: accesos_recursos_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.accesos_recursos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: accesos_recursos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.accesos_recursos_id_seq OWNED BY public.accesos_recursos.id;


--
-- Name: analitica_snapshots; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.analitica_snapshots (
    id integer NOT NULL,
    fecha_snapshot timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    usuario_nombre character varying(150) DEFAULT 'Sistema CIIDI'::character varying,
    id_carrera integer,
    carrera_nombre character varying(150) DEFAULT 'Todas las Carreras / PNF'::character varying,
    modelo_usado character varying(80) NOT NULL,
    modelo_label character varying(120) NOT NULL,
    fuente_datos character varying(30) DEFAULT 'pst'::character varying,
    ventana_historial character varying(30) DEFAULT '2y'::character varying,
    horizonte_trimestres integer DEFAULT 4 NOT NULL,
    trimestre_base character varying(20),
    trimestre_meta character varying(20),
    rmse_global numeric(10,4) DEFAULT 0,
    mae_global numeric(10,4) DEFAULT 0,
    confianza_pct numeric(5,2) DEFAULT 0,
    total_historico integer DEFAULT 0,
    total_proyectado integer DEFAULT 0,
    estacionalidad_activa boolean DEFAULT true,
    resumen_json jsonb DEFAULT '[]'::jsonb NOT NULL
);


--
-- Name: analitica_snapshots_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.analitica_snapshots_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: analitica_snapshots_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.analitica_snapshots_id_seq OWNED BY public.analitica_snapshots.id;


--
-- Name: auditoria; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.auditoria (
    id integer NOT NULL,
    tabla_afectada character varying(50) NOT NULL,
    id_registro integer NOT NULL,
    accion public.accion_auditoria_enum NOT NULL,
    usuario_responsable integer,
    ip_origen character varying(45),
    datos_anteriores jsonb,
    datos_nuevos jsonb,
    fecha_hora timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- Name: auditoria_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.auditoria_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: auditoria_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.auditoria_id_seq OWNED BY public.auditoria.id;


--
-- Name: autores; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.autores (
    id integer NOT NULL,
    nombre_completo character varying(150) NOT NULL,
    cedula character varying(20) DEFAULT NULL::character varying
);


--
-- Name: autores_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.autores_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: autores_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.autores_id_seq OWNED BY public.autores.id;


--
-- Name: carreras; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.carreras (
    id integer NOT NULL,
    nombre character varying(100) NOT NULL,
    descripcion text
);


--
-- Name: carreras_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.carreras_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: carreras_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.carreras_id_seq OWNED BY public.carreras.id;


--
-- Name: categorias; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.categorias (
    id integer NOT NULL,
    nombre character varying(150) NOT NULL
);


--
-- Name: categorias_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.categorias_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: categorias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.categorias_id_seq OWNED BY public.categorias.id;


--
-- Name: cursos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cursos (
    id integer NOT NULL,
    id_docente integer NOT NULL,
    titulo character varying(255) NOT NULL,
    descripcion text,
    imagen_portada text,
    estado public.estado_curso_enum DEFAULT 'borrador'::public.estado_curso_enum NOT NULL,
    nota_minima_aprobacion numeric(5,2) DEFAULT 70.00 NOT NULL,
    fecha_creacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    slug character varying(255),
    url_moodle text,
    modalidad character varying(50) DEFAULT 'Virtual'::character varying,
    nivel character varying(50) DEFAULT 'BÂ sico'::character varying,
    duracion character varying(80),
    cupo_maximo integer,
    fecha_inicio date,
    fecha_fin date,
    url_video_preview text,
    estado_inscripcion character varying(50) DEFAULT 'Abierta'::character varying,
    url_formulario_google text
);


--
-- Name: cursos_dimensiones; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cursos_dimensiones (
    id_curso integer NOT NULL,
    id_dimension integer NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- Name: cursos_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cursos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cursos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cursos_id_seq OWNED BY public.cursos.id;


--
-- Name: cursos_lineas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cursos_lineas (
    id_curso integer NOT NULL,
    id_linea integer NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- Name: cursos_preinscripciones; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cursos_preinscripciones (
    id integer NOT NULL,
    id_curso integer NOT NULL,
    nombre_completo character varying(255) NOT NULL,
    correo_electronico character varying(255) NOT NULL,
    telefono character varying(50),
    horario_deseado character varying(255) NOT NULL,
    mensaje text,
    fecha_preinscripcion timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- Name: cursos_preinscripciones_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cursos_preinscripciones_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cursos_preinscripciones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cursos_preinscripciones_id_seq OWNED BY public.cursos_preinscripciones.id;


--
-- Name: detalles_articulos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.detalles_articulos (
    id_recurso integer NOT NULL,
    id_editorial integer,
    volumen character varying(50),
    numero character varying(50),
    issn character varying(20),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    imagen_portada text DEFAULT 'default_article.jpg'::character varying,
    resumen text,
    activo boolean DEFAULT true,
    id_categoria integer
);


--
-- Name: detalles_investigaciones; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.detalles_investigaciones (
    id_recurso integer NOT NULL,
    planteamiento_problema text NOT NULL,
    objetivo_general text NOT NULL,
    id_investigacion_ofertada integer,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- Name: detalles_proyectos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.detalles_proyectos (
    id_recurso integer NOT NULL,
    fecha_defensa date,
    nivel_academico character varying(50) DEFAULT 'Pregrado'::character varying,
    resumen text,
    id_carrera integer,
    comunidad_beneficiada text,
    palabras_clave text,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    id_investigacion_padre integer,
    url_repositorio text,
    obj_general text,
    activo boolean DEFAULT true,
    vector_semantico text,
    id_trayecto integer,
    vistas integer DEFAULT 0 NOT NULL
);


--
-- Name: COLUMN detalles_proyectos.vistas; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.detalles_proyectos.vistas IS 'Contador de visualizaciones de la ficha tÃ©cnica del proyecto PST';


--
-- Name: dimensiones_operativas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.dimensiones_operativas (
    id integer NOT NULL,
    id_linea integer NOT NULL,
    nombre character varying(150) NOT NULL,
    descripcion text,
    activo boolean DEFAULT true
);


--
-- Name: dimensiones_operativas_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.dimensiones_operativas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: dimensiones_operativas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.dimensiones_operativas_id_seq OWNED BY public.dimensiones_operativas.id;


--
-- Name: editoriales; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.editoriales (
    id integer NOT NULL,
    nombre character varying(150) NOT NULL
);


--
-- Name: editoriales_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.editoriales_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: editoriales_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.editoriales_id_seq OWNED BY public.editoriales.id;


--
-- Name: etiquetas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.etiquetas (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL,
    color_hex character varying(7) DEFAULT '#0ea5e9'::character varying
);


--
-- Name: etiquetas_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.etiquetas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: etiquetas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.etiquetas_id_seq OWNED BY public.etiquetas.id;


--
-- Name: historico_versiones_pst; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.historico_versiones_pst (
    id integer NOT NULL,
    id_recurso integer NOT NULL,
    archivo_pdf character varying(500) NOT NULL,
    usuario_id integer,
    motivo character varying(255) DEFAULT 'ActualizaciÂ¢n'::character varying,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- Name: historico_versiones_pst_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.historico_versiones_pst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: historico_versiones_pst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.historico_versiones_pst_id_seq OWNED BY public.historico_versiones_pst.id;


--
-- Name: investigaciones_dimensiones; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.investigaciones_dimensiones (
    id_investigacion integer NOT NULL,
    id_dimension integer NOT NULL
);


--
-- Name: investigaciones_lineas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.investigaciones_lineas (
    id_investigacion integer NOT NULL,
    id_linea integer NOT NULL
);


--
-- Name: investigaciones_ofertadas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.investigaciones_ofertadas (
    id integer NOT NULL,
    id_profesor integer NOT NULL,
    titulo character varying(255) NOT NULL,
    planteamiento_problema text NOT NULL,
    objetivo_general text NOT NULL,
    id_linea integer NOT NULL,
    id_dimension integer,
    cupos_disponibles integer DEFAULT 3,
    estado character varying(20) DEFAULT 'Abierta'::character varying,
    fecha_creacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    id_propuesta_empresa integer,
    CONSTRAINT investigaciones_ofertadas_estado_check CHECK (((estado)::text = ANY (ARRAY[('Abierta'::character varying)::text, ('Cerrada'::character varying)::text, ('En Desarrollo'::character varying)::text, ('Finalizada'::character varying)::text])))
);


--
-- Name: investigaciones_ofertadas_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.investigaciones_ofertadas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: investigaciones_ofertadas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.investigaciones_ofertadas_id_seq OWNED BY public.investigaciones_ofertadas.id;


--
-- Name: investigadores; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.investigadores (
    id integer NOT NULL,
    nombre_completo character varying(150) NOT NULL,
    orcid character varying(50) NOT NULL,
    biografia text,
    pagina_web character varying(255)
);


--
-- Name: investigadores_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.investigadores_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: investigadores_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.investigadores_id_seq OWNED BY public.investigadores.id;


--
-- Name: lineas_investigacion; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.lineas_investigacion (
    id integer NOT NULL,
    nombre character varying(255) NOT NULL,
    id_carrera integer NOT NULL,
    descripcion text,
    activo boolean DEFAULT true
);


--
-- Name: lineas_investigacion_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.lineas_investigacion_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: lineas_investigacion_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.lineas_investigacion_id_seq OWNED BY public.lineas_investigacion.id;


--
-- Name: matriz_rbac; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.matriz_rbac (
    nivel_privilegio integer NOT NULL,
    modulo character varying(100) NOT NULL,
    permisos jsonb
);


--
-- Name: niveles_academicos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.niveles_academicos (
    id integer NOT NULL,
    codigo character varying(50) NOT NULL,
    nombre character varying(100) NOT NULL,
    descripcion text,
    requiere_trayecto boolean DEFAULT false,
    orden integer DEFAULT 0,
    activo boolean DEFAULT true,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- Name: TABLE niveles_academicos; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE public.niveles_academicos IS 'CatÃ¡logo administrativo dinÃ¡mico de niveles acadÃ©micos para proyectos del repositorio';


--
-- Name: niveles_academicos_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.niveles_academicos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: niveles_academicos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.niveles_academicos_id_seq OWNED BY public.niveles_academicos.id;


--
-- Name: notificaciones; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.notificaciones (
    id integer NOT NULL,
    id_usuario integer,
    titulo character varying(255),
    mensaje text,
    leido boolean DEFAULT false,
    fecha_hora timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- Name: notificaciones_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.notificaciones_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: notificaciones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.notificaciones_id_seq OWNED BY public.notificaciones.id;


--
-- Name: password_resets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_resets (
    id integer NOT NULL,
    email character varying(100) NOT NULL,
    token_hash character varying(255) NOT NULL,
    expiracion timestamp without time zone NOT NULL,
    utilizado boolean DEFAULT false,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- Name: password_resets_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.password_resets_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: password_resets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.password_resets_id_seq OWNED BY public.password_resets.id;


--
-- Name: postulaciones_estudiantes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.postulaciones_estudiantes (
    id integer NOT NULL,
    id_investigacion integer NOT NULL,
    id_estudiante integer NOT NULL,
    mensaje_motivacion text,
    estado character varying(20) DEFAULT 'Pendiente'::character varying,
    fecha_postulacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_respuesta timestamp without time zone,
    equipo_extra json,
    trayecto smallint,
    trimestre smallint,
    id_profesor_pst integer,
    aprobacion_profesor character varying(20) DEFAULT 'Pendiente'::character varying NOT NULL,
    motivo_profesor text,
    fecha_revision_profesor timestamp without time zone,
    motivo_rechazo text,
    CONSTRAINT chk_postulacion_aprobacion_profesor CHECK (((aprobacion_profesor)::text = ANY ((ARRAY['Pendiente'::character varying, 'Aprobado'::character varying, 'Devuelto'::character varying])::text[]))),
    CONSTRAINT chk_postulacion_trayecto CHECK (((trayecto IS NULL) OR ((trayecto >= 1) AND (trayecto <= 99)))),
    CONSTRAINT chk_postulacion_trimestre CHECK (((trimestre IS NULL) OR ((trimestre >= 1) AND (trimestre <= 3)))),
    CONSTRAINT postulaciones_estudiantes_estado_check CHECK (((estado)::text = ANY (ARRAY[('Pendiente'::character varying)::text, ('Aceptado'::character varying)::text, ('Rechazado'::character varying)::text])))
);


--
-- Name: COLUMN postulaciones_estudiantes.aprobacion_profesor; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.postulaciones_estudiantes.aprobacion_profesor IS 'Validación del profesor PST elegido: Pendiente, Aprobado o Devuelto (con motivo). La asignación final la realiza el Comité (columna estado).';


--
-- Name: COLUMN postulaciones_estudiantes.motivo_rechazo; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.postulaciones_estudiantes.motivo_rechazo IS 'Motivo del rechazo o cancelación de la postulación (p. ej. propuesta empresarial revertida o archivada).';


--
-- Name: postulaciones_estudiantes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.postulaciones_estudiantes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: postulaciones_estudiantes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.postulaciones_estudiantes_id_seq OWNED BY public.postulaciones_estudiantes.id;


--
-- Name: preferencias_usuario; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.preferencias_usuario (
    id_usuario integer NOT NULL,
    tema character varying(50) DEFAULT 'light'::character varying,
    notificaciones_sistema boolean DEFAULT true
);


--
-- Name: privilegios; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.privilegios (
    privilegio_id integer NOT NULL,
    nivel_privilegio integer DEFAULT 0 NOT NULL
);


--
-- Name: privilegios_privilegio_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.privilegios_privilegio_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: privilegios_privilegio_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.privilegios_privilegio_id_seq OWNED BY public.privilegios.privilegio_id;


--
-- Name: profesor_dimension; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.profesor_dimension (
    id_usuario integer NOT NULL,
    id_dimension integer NOT NULL,
    id_linea integer NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- Name: profesor_linea; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.profesor_linea (
    id_usuario integer NOT NULL,
    id_linea integer NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- Name: TABLE profesor_linea; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE public.profesor_linea IS 'Relación N:M de afiliación explícita de profesores (usuarios) a líneas de investigación.';


--
-- Name: profesores_pst; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.profesores_pst (
    id integer NOT NULL,
    id_profesor integer NOT NULL,
    trayecto smallint NOT NULL,
    trimestre smallint DEFAULT 1,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_profesores_pst_trayecto CHECK (((trayecto >= 1) AND (trayecto <= 99))),
    CONSTRAINT chk_profesores_pst_trimestre_null CHECK (((trimestre IS NULL) OR ((trimestre >= 1) AND (trimestre <= 3))))
);


--
-- Name: TABLE profesores_pst; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE public.profesores_pst IS 'Trayecto(s) y trimestre(s) en los que cada profesor PST dicta/tutoriza el Proyecto Sociotecnológico.';


--
-- Name: profesores_pst_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.profesores_pst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: profesores_pst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.profesores_pst_id_seq OWNED BY public.profesores_pst.id;


--
-- Name: propuestas_empresa; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.propuestas_empresa (
    id integer NOT NULL,
    nombre_empresa character varying(255) NOT NULL,
    rif_empresa character varying(50),
    persona_contacto character varying(150) NOT NULL,
    telefono_contacto character varying(50) NOT NULL,
    correo_contacto character varying(150) NOT NULL,
    area_afectada character varying(100) NOT NULL,
    descripcion_problema text NOT NULL,
    estado public.estado_propuesta_enum DEFAULT 'pendiente'::public.estado_propuesta_enum,
    fecha_creacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    nivel_trayecto character varying(50),
    codigo_seguimiento character varying(20),
    motivo_rechazo text,
    campos_extra jsonb DEFAULT '[]'::jsonb NOT NULL,
    eliminado_en timestamp without time zone,
    motivo_archivo text,
    archivado_por integer,
    revertido_en timestamp without time zone,
    motivo_reversion text
);


--
-- Name: COLUMN propuestas_empresa.campos_extra; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.propuestas_empresa.campos_extra IS 'Respuestas de campos personalizados del formulario público: [{"clave","etiqueta","tipo","valor"}].';


--
-- Name: COLUMN propuestas_empresa.eliminado_en; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.propuestas_empresa.eliminado_en IS 'NULL = activa. Fecha de archivo lógico (soft-delete) de la propuesta.';


--
-- Name: COLUMN propuestas_empresa.motivo_archivo; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.propuestas_empresa.motivo_archivo IS 'Motivo indicado por el Comité al archivar la propuesta.';


--
-- Name: COLUMN propuestas_empresa.archivado_por; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.propuestas_empresa.archivado_por IS 'Usuario que archivó la propuesta.';


--
-- Name: COLUMN propuestas_empresa.revertido_en; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.propuestas_empresa.revertido_en IS 'Última fecha en que una propuesta aceptada fue revertida a pendiente.';


--
-- Name: COLUMN propuestas_empresa.motivo_reversion; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.propuestas_empresa.motivo_reversion IS 'Motivo de la última reversión a pendiente.';


--
-- Name: propuestas_empresa_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.propuestas_empresa_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: propuestas_empresa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.propuestas_empresa_id_seq OWNED BY public.propuestas_empresa.id;


--
-- Name: proyecto_carreras_vinculadas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.proyecto_carreras_vinculadas (
    id_recurso integer NOT NULL,
    id_carrera integer NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- Name: TABLE proyecto_carreras_vinculadas; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE public.proyecto_carreras_vinculadas IS 'RelaciÃ³n N:M para vincular proyectos PST con carreras secundarias adicionales a su carrera base.';


--
-- Name: proyecto_tutores; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.proyecto_tutores (
    id_recurso integer NOT NULL,
    id_tutor integer NOT NULL,
    tipo_tutor_id integer
);


--
-- Name: recurso_autores; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.recurso_autores (
    id_recurso integer NOT NULL,
    id_autor integer NOT NULL
);


--
-- Name: recurso_categorias; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.recurso_categorias (
    id_recurso integer NOT NULL,
    id_categoria integer NOT NULL
);


--
-- Name: recurso_clasificaciones; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.recurso_clasificaciones (
    id_recurso integer NOT NULL,
    id_linea_investigacion integer NOT NULL,
    id_dimension_operativa integer
);


--
-- Name: recurso_etiquetas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.recurso_etiquetas (
    id_recurso integer NOT NULL,
    id_etiqueta integer NOT NULL
);


--
-- Name: recurso_investigadores; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.recurso_investigadores (
    id_recurso integer NOT NULL,
    id_investigador integer NOT NULL
);


--
-- Name: recursos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.recursos (
    id integer NOT NULL,
    titulo character varying(255) NOT NULL,
    id_tipo_recurso integer NOT NULL,
    anio_publicacion integer,
    archivo_pdf character varying(255)
);


--
-- Name: recursos_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.recursos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: recursos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.recursos_id_seq OWNED BY public.recursos.id;


--
-- Name: registro_actividad; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.registro_actividad (
    id integer NOT NULL,
    id_usuario integer,
    id_visitante integer,
    fecha_inicial timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    ultima_actividad timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    conteo_accesos integer DEFAULT 1
);


--
-- Name: registro_actividad_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.registro_actividad_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: registro_actividad_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.registro_actividad_id_seq OWNED BY public.registro_actividad.id;


--
-- Name: roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.roles (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL,
    privilegio_id integer DEFAULT 1 NOT NULL
);


--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.roles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: seg_lineas_alertas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.seg_lineas_alertas (
    id integer NOT NULL,
    fecha timestamp(0) without time zone NOT NULL,
    tipo character varying(40) NOT NULL,
    severidad character varying(10) NOT NULL,
    titulo character varying(200) NOT NULL,
    detalle text DEFAULT ''::text NOT NULL,
    ip character varying(64) DEFAULT ''::character varying NOT NULL,
    usuario_id integer,
    estado character varying(10) DEFAULT 'abierta'::character varying NOT NULL,
    atendida_por character varying(160),
    atendida_en timestamp(0) without time zone
);


--
-- Name: seg_lineas_alertas_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.seg_lineas_alertas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: seg_lineas_alertas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.seg_lineas_alertas_id_seq OWNED BY public.seg_lineas_alertas.id;


--
-- Name: seg_lineas_bloqueos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.seg_lineas_bloqueos (
    id integer NOT NULL,
    ip character varying(64) NOT NULL,
    motivo text DEFAULT ''::text NOT NULL,
    origen character varying(10) DEFAULT 'manual'::character varying NOT NULL,
    creado_en timestamp(0) without time zone NOT NULL,
    expira_en timestamp(0) without time zone,
    creado_por character varying(160) DEFAULT ''::character varying NOT NULL,
    activo boolean DEFAULT true NOT NULL,
    levantado_por character varying(160),
    levantado_en timestamp(0) without time zone
);


--
-- Name: seg_lineas_bloqueos_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.seg_lineas_bloqueos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: seg_lineas_bloqueos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.seg_lineas_bloqueos_id_seq OWNED BY public.seg_lineas_bloqueos.id;


--
-- Name: seg_lineas_excepciones; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.seg_lineas_excepciones (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    submodulo character varying(80) NOT NULL,
    accion character varying(30) NOT NULL,
    efecto character varying(10) NOT NULL,
    motivo text DEFAULT ''::text NOT NULL,
    expira_en timestamp(0) without time zone,
    creado_por character varying(160) DEFAULT ''::character varying NOT NULL,
    creado_en timestamp(0) without time zone NOT NULL,
    CONSTRAINT seg_lineas_excepciones_efecto_check CHECK (((efecto)::text = ANY ((ARRAY['permitir'::character varying, 'denegar'::character varying])::text[])))
);


--
-- Name: seg_lineas_excepciones_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.seg_lineas_excepciones_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: seg_lineas_excepciones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.seg_lineas_excepciones_id_seq OWNED BY public.seg_lineas_excepciones.id;


--
-- Name: seg_lineas_logs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.seg_lineas_logs (
    id bigint NOT NULL,
    fecha timestamp(0) without time zone NOT NULL,
    severidad character varying(10) DEFAULT 'INFO'::character varying NOT NULL,
    submodulo character varying(80) DEFAULT ''::character varying NOT NULL,
    accion character varying(30) DEFAULT ''::character varying NOT NULL,
    ruta character varying(160) DEFAULT ''::character varying NOT NULL,
    resultado character varying(12) DEFAULT 'PERMITIDO'::character varying NOT NULL,
    usuario_id integer,
    usuario_nombre character varying(160) DEFAULT ''::character varying NOT NULL,
    nivel integer DEFAULT 999 NOT NULL,
    ip character varying(64) DEFAULT ''::character varying NOT NULL,
    metodo character varying(10) DEFAULT 'GET'::character varying NOT NULL,
    user_agent character varying(255) DEFAULT ''::character varying NOT NULL,
    detalles text DEFAULT ''::text NOT NULL,
    hash_anterior character varying(64) NOT NULL,
    hash character varying(64) NOT NULL
);


--
-- Name: seg_lineas_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.seg_lineas_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: seg_lineas_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.seg_lineas_logs_id_seq OWNED BY public.seg_lineas_logs.id;


--
-- Name: seg_lineas_permisos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.seg_lineas_permisos (
    nivel integer NOT NULL,
    submodulo character varying(80) NOT NULL,
    accion character varying(30) NOT NULL,
    permitido boolean NOT NULL,
    actualizado_por character varying(160) DEFAULT ''::character varying NOT NULL,
    actualizado_en timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: seg_lineas_politicas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.seg_lineas_politicas (
    clave character varying(60) NOT NULL,
    valor jsonb NOT NULL,
    actualizado_por character varying(160) DEFAULT ''::character varying NOT NULL,
    actualizado_en timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: seg_lineas_rutas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.seg_lineas_rutas (
    ruta character varying(160) NOT NULL,
    accion character varying(30) NOT NULL,
    actualizado_por character varying(160) DEFAULT ''::character varying NOT NULL,
    actualizado_en timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: solicitudes_docentes_pst; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.solicitudes_docentes_pst (
    id integer NOT NULL,
    id_profesor integer NOT NULL,
    es_profesor_pst boolean DEFAULT true NOT NULL,
    trayectos jsonb DEFAULT '[]'::jsonb NOT NULL,
    estado character varying(20) DEFAULT 'pendiente'::character varying NOT NULL,
    motivo text,
    fecha_solicitud timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_resolucion timestamp without time zone,
    id_revisor integer,
    CONSTRAINT chk_solicitud_pst_estado CHECK (((estado)::text = ANY ((ARRAY['pendiente'::character varying, 'aprobado'::character varying, 'rechazado'::character varying])::text[])))
);


--
-- Name: solicitudes_docentes_pst_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.solicitudes_docentes_pst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: solicitudes_docentes_pst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.solicitudes_docentes_pst_id_seq OWNED BY public.solicitudes_docentes_pst.id;


--
-- Name: system_audit_log; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.system_audit_log (
    id character varying(50) NOT NULL,
    fecha_hora timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    nivel character varying(20),
    modulo character varying(100),
    accion character varying(100),
    detalles text,
    responsable character varying(150),
    ip character varying(45),
    hash_anterior character varying(64),
    hash_integridad character varying(64)
);


--
-- Name: telemetria_cache; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.telemetria_cache (
    id integer DEFAULT 1 NOT NULL,
    datos jsonb
);


--
-- Name: tipo_recurso; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tipo_recurso (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL,
    descripcion text
);


--
-- Name: tipo_recurso_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tipo_recurso_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tipo_recurso_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.tipo_recurso_id_seq OWNED BY public.tipo_recurso.id;


--
-- Name: tipo_tutor; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tipo_tutor (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL,
    descripcion text
);


--
-- Name: tipo_tutor_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tipo_tutor_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tipo_tutor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.tipo_tutor_id_seq OWNED BY public.tipo_tutor.id;


--
-- Name: trayectos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.trayectos (
    id integer NOT NULL,
    id_carrera integer NOT NULL,
    nombre character varying(50) NOT NULL,
    numero integer NOT NULL,
    descripcion text,
    activo boolean DEFAULT true,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- Name: trayectos_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.trayectos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: trayectos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.trayectos_id_seq OWNED BY public.trayectos.id;


--
-- Name: tutores; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tutores (
    id integer NOT NULL,
    nombre_completo character varying(150) NOT NULL,
    cedula character varying(20)
);


--
-- Name: tutores_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tutores_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tutores_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.tutores_id_seq OWNED BY public.tutores.id;


--
-- Name: usuarios; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.usuarios (
    id integer NOT NULL,
    nombre_completo character varying(150) NOT NULL,
    email character varying(100),
    cedula character varying(20),
    contrasena character varying(255),
    id_rol integer,
    activo boolean DEFAULT true,
    reset_token character varying(255) DEFAULT NULL::character varying,
    reset_expires timestamp without time zone,
    telefono character varying(50) DEFAULT NULL::character varying,
    email_verified boolean DEFAULT false,
    activation_token character varying(255) DEFAULT NULL::character varying,
    estado_registro character varying(20) DEFAULT NULL::character varying,
    es_profesor_pst boolean DEFAULT false NOT NULL,
    es_investigador boolean DEFAULT false NOT NULL,
    foto_perfil character varying(255) DEFAULT NULL::character varying,
    orcid character varying(150) DEFAULT NULL::character varying,
    sitio_web character varying(255) DEFAULT NULL::character varying,
    grado_academico character varying(255) DEFAULT NULL::character varying,
    biografia text,
    CONSTRAINT chk_usuarios_estado_registro CHECK (((estado_registro IS NULL) OR ((estado_registro)::text = ANY ((ARRAY['pendiente'::character varying, 'aprobado'::character varying, 'rechazado'::character varying])::text[]))))
);


--
-- Name: COLUMN usuarios.estado_registro; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.usuarios.estado_registro IS 'NULL = flujo normal. pendiente/aprobado/rechazado = auto-registro docente en línea sujeto a aprobación del Comité/Super Administrador.';


--
-- Name: COLUMN usuarios.es_profesor_pst; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.usuarios.es_profesor_pst IS 'Indica si el docente imparte/tutoriza Proyecto Sociotecnológico (PST).';


--
-- Name: usuarios_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.usuarios_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: usuarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.usuarios_id_seq OWNED BY public.usuarios.id;


--
-- Name: visitantes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.visitantes (
    id integer NOT NULL,
    ip_address character varying(45) NOT NULL,
    user_agent text,
    pagina_origen character varying(255)
);


--
-- Name: visitantes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.visitantes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: visitantes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.visitantes_id_seq OWNED BY public.visitantes.id;


--
-- Name: waf_rate_limiter; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.waf_rate_limiter (
    ip character varying(45) NOT NULL,
    tipo character varying(20) NOT NULL,
    intentos integer DEFAULT 0,
    primer_intento timestamp without time zone,
    ultimo_intento timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    bloqueado_hasta timestamp without time zone,
    razon text,
    datos_adicionales jsonb,
    creado_el timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- Name: accesos_recursos id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.accesos_recursos ALTER COLUMN id SET DEFAULT nextval('public.accesos_recursos_id_seq'::regclass);


--
-- Name: analitica_snapshots id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.analitica_snapshots ALTER COLUMN id SET DEFAULT nextval('public.analitica_snapshots_id_seq'::regclass);


--
-- Name: auditoria id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auditoria ALTER COLUMN id SET DEFAULT nextval('public.auditoria_id_seq'::regclass);


--
-- Name: autores id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.autores ALTER COLUMN id SET DEFAULT nextval('public.autores_id_seq'::regclass);


--
-- Name: carreras id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.carreras ALTER COLUMN id SET DEFAULT nextval('public.carreras_id_seq'::regclass);


--
-- Name: categorias id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categorias ALTER COLUMN id SET DEFAULT nextval('public.categorias_id_seq'::regclass);


--
-- Name: cursos id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cursos ALTER COLUMN id SET DEFAULT nextval('public.cursos_id_seq'::regclass);


--
-- Name: cursos_preinscripciones id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cursos_preinscripciones ALTER COLUMN id SET DEFAULT nextval('public.cursos_preinscripciones_id_seq'::regclass);


--
-- Name: dimensiones_operativas id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.dimensiones_operativas ALTER COLUMN id SET DEFAULT nextval('public.dimensiones_operativas_id_seq'::regclass);


--
-- Name: editoriales id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.editoriales ALTER COLUMN id SET DEFAULT nextval('public.editoriales_id_seq'::regclass);


--
-- Name: etiquetas id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.etiquetas ALTER COLUMN id SET DEFAULT nextval('public.etiquetas_id_seq'::regclass);


--
-- Name: historico_versiones_pst id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.historico_versiones_pst ALTER COLUMN id SET DEFAULT nextval('public.historico_versiones_pst_id_seq'::regclass);


--
-- Name: investigaciones_ofertadas id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.investigaciones_ofertadas ALTER COLUMN id SET DEFAULT nextval('public.investigaciones_ofertadas_id_seq'::regclass);


--
-- Name: investigadores id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.investigadores ALTER COLUMN id SET DEFAULT nextval('public.investigadores_id_seq'::regclass);


--
-- Name: lineas_investigacion id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.lineas_investigacion ALTER COLUMN id SET DEFAULT nextval('public.lineas_investigacion_id_seq'::regclass);


--
-- Name: niveles_academicos id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.niveles_academicos ALTER COLUMN id SET DEFAULT nextval('public.niveles_academicos_id_seq'::regclass);


--
-- Name: notificaciones id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.notificaciones ALTER COLUMN id SET DEFAULT nextval('public.notificaciones_id_seq'::regclass);


--
-- Name: password_resets id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.password_resets ALTER COLUMN id SET DEFAULT nextval('public.password_resets_id_seq'::regclass);


--
-- Name: postulaciones_estudiantes id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.postulaciones_estudiantes ALTER COLUMN id SET DEFAULT nextval('public.postulaciones_estudiantes_id_seq'::regclass);


--
-- Name: privilegios privilegio_id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.privilegios ALTER COLUMN privilegio_id SET DEFAULT nextval('public.privilegios_privilegio_id_seq'::regclass);


--
-- Name: profesores_pst id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profesores_pst ALTER COLUMN id SET DEFAULT nextval('public.profesores_pst_id_seq'::regclass);


--
-- Name: propuestas_empresa id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.propuestas_empresa ALTER COLUMN id SET DEFAULT nextval('public.propuestas_empresa_id_seq'::regclass);


--
-- Name: recursos id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.recursos ALTER COLUMN id SET DEFAULT nextval('public.recursos_id_seq'::regclass);


--
-- Name: registro_actividad id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.registro_actividad ALTER COLUMN id SET DEFAULT nextval('public.registro_actividad_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: seg_lineas_alertas id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.seg_lineas_alertas ALTER COLUMN id SET DEFAULT nextval('public.seg_lineas_alertas_id_seq'::regclass);


--
-- Name: seg_lineas_bloqueos id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.seg_lineas_bloqueos ALTER COLUMN id SET DEFAULT nextval('public.seg_lineas_bloqueos_id_seq'::regclass);


--
-- Name: seg_lineas_excepciones id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.seg_lineas_excepciones ALTER COLUMN id SET DEFAULT nextval('public.seg_lineas_excepciones_id_seq'::regclass);


--
-- Name: seg_lineas_logs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.seg_lineas_logs ALTER COLUMN id SET DEFAULT nextval('public.seg_lineas_logs_id_seq'::regclass);


--
-- Name: solicitudes_docentes_pst id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.solicitudes_docentes_pst ALTER COLUMN id SET DEFAULT nextval('public.solicitudes_docentes_pst_id_seq'::regclass);


--
-- Name: tipo_recurso id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tipo_recurso ALTER COLUMN id SET DEFAULT nextval('public.tipo_recurso_id_seq'::regclass);


--
-- Name: tipo_tutor id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tipo_tutor ALTER COLUMN id SET DEFAULT nextval('public.tipo_tutor_id_seq'::regclass);


--
-- Name: trayectos id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.trayectos ALTER COLUMN id SET DEFAULT nextval('public.trayectos_id_seq'::regclass);


--
-- Name: tutores id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tutores ALTER COLUMN id SET DEFAULT nextval('public.tutores_id_seq'::regclass);


--
-- Name: usuarios id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuarios ALTER COLUMN id SET DEFAULT nextval('public.usuarios_id_seq'::regclass);


--
-- Name: visitantes id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.visitantes ALTER COLUMN id SET DEFAULT nextval('public.visitantes_id_seq'::regclass);


--
-- Name: accesos_recursos accesos_recursos_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.accesos_recursos
    ADD CONSTRAINT accesos_recursos_pkey PRIMARY KEY (id);


--
-- Name: analitica_snapshots analitica_snapshots_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.analitica_snapshots
    ADD CONSTRAINT analitica_snapshots_pkey PRIMARY KEY (id);


--
-- Name: auditoria auditoria_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auditoria
    ADD CONSTRAINT auditoria_pkey PRIMARY KEY (id);


--
-- Name: autores autores_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.autores
    ADD CONSTRAINT autores_pkey PRIMARY KEY (id);


--
-- Name: carreras carreras_nombre_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.carreras
    ADD CONSTRAINT carreras_nombre_key UNIQUE (nombre);


--
-- Name: carreras carreras_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.carreras
    ADD CONSTRAINT carreras_pkey PRIMARY KEY (id);


--
-- Name: categorias categorias_nombre_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categorias
    ADD CONSTRAINT categorias_nombre_key UNIQUE (nombre);


--
-- Name: categorias categorias_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categorias
    ADD CONSTRAINT categorias_pkey PRIMARY KEY (id);


--
-- Name: cursos_dimensiones cursos_dimensiones_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cursos_dimensiones
    ADD CONSTRAINT cursos_dimensiones_pkey PRIMARY KEY (id_curso, id_dimension);


--
-- Name: cursos_lineas cursos_lineas_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cursos_lineas
    ADD CONSTRAINT cursos_lineas_pkey PRIMARY KEY (id_curso, id_linea);


--
-- Name: cursos cursos_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cursos
    ADD CONSTRAINT cursos_pkey PRIMARY KEY (id);


--
-- Name: cursos_preinscripciones cursos_preinscripciones_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cursos_preinscripciones
    ADD CONSTRAINT cursos_preinscripciones_pkey PRIMARY KEY (id);


--
-- Name: cursos cursos_slug_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cursos
    ADD CONSTRAINT cursos_slug_key UNIQUE (slug);


--
-- Name: detalles_investigaciones detalles_investigaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalles_investigaciones
    ADD CONSTRAINT detalles_investigaciones_pkey PRIMARY KEY (id_recurso);


--
-- Name: detalles_proyectos detalles_proyectos_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalles_proyectos
    ADD CONSTRAINT detalles_proyectos_pkey PRIMARY KEY (id_recurso);


--
-- Name: detalles_articulos detalles_revistas_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalles_articulos
    ADD CONSTRAINT detalles_revistas_pkey PRIMARY KEY (id_recurso);


--
-- Name: dimensiones_operativas dimensiones_operativas_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.dimensiones_operativas
    ADD CONSTRAINT dimensiones_operativas_pkey PRIMARY KEY (id);


--
-- Name: editoriales editoriales_nombre_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.editoriales
    ADD CONSTRAINT editoriales_nombre_key UNIQUE (nombre);


--
-- Name: editoriales editoriales_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.editoriales
    ADD CONSTRAINT editoriales_pkey PRIMARY KEY (id);


--
-- Name: etiquetas etiquetas_nombre_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.etiquetas
    ADD CONSTRAINT etiquetas_nombre_key UNIQUE (nombre);


--
-- Name: etiquetas etiquetas_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.etiquetas
    ADD CONSTRAINT etiquetas_pkey PRIMARY KEY (id);


--
-- Name: historico_versiones_pst historico_versiones_pst_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.historico_versiones_pst
    ADD CONSTRAINT historico_versiones_pst_pkey PRIMARY KEY (id);


--
-- Name: investigaciones_ofertadas investigaciones_ofertadas_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.investigaciones_ofertadas
    ADD CONSTRAINT investigaciones_ofertadas_pkey PRIMARY KEY (id);


--
-- Name: investigadores investigadores_orcid_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.investigadores
    ADD CONSTRAINT investigadores_orcid_key UNIQUE (orcid);


--
-- Name: investigadores investigadores_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.investigadores
    ADD CONSTRAINT investigadores_pkey PRIMARY KEY (id);


--
-- Name: lineas_investigacion lineas_investigacion_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.lineas_investigacion
    ADD CONSTRAINT lineas_investigacion_pkey PRIMARY KEY (id);


--
-- Name: matriz_rbac matriz_rbac_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.matriz_rbac
    ADD CONSTRAINT matriz_rbac_pkey PRIMARY KEY (nivel_privilegio, modulo);


--
-- Name: niveles_academicos niveles_academicos_codigo_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.niveles_academicos
    ADD CONSTRAINT niveles_academicos_codigo_key UNIQUE (codigo);


--
-- Name: niveles_academicos niveles_academicos_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.niveles_academicos
    ADD CONSTRAINT niveles_academicos_pkey PRIMARY KEY (id);


--
-- Name: notificaciones notificaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.notificaciones
    ADD CONSTRAINT notificaciones_pkey PRIMARY KEY (id);


--
-- Name: password_resets password_resets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.password_resets
    ADD CONSTRAINT password_resets_pkey PRIMARY KEY (id);


--
-- Name: investigaciones_dimensiones pk_inv_dim; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.investigaciones_dimensiones
    ADD CONSTRAINT pk_inv_dim PRIMARY KEY (id_investigacion, id_dimension);


--
-- Name: investigaciones_lineas pk_inv_lin; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.investigaciones_lineas
    ADD CONSTRAINT pk_inv_lin PRIMARY KEY (id_investigacion, id_linea);


--
-- Name: profesor_linea pk_profesor_linea; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profesor_linea
    ADD CONSTRAINT pk_profesor_linea PRIMARY KEY (id_usuario, id_linea);


--
-- Name: proyecto_carreras_vinculadas pk_proyecto_carreras_vinculadas; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.proyecto_carreras_vinculadas
    ADD CONSTRAINT pk_proyecto_carreras_vinculadas PRIMARY KEY (id_recurso, id_carrera);


--
-- Name: postulaciones_estudiantes postulaciones_estudiantes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.postulaciones_estudiantes
    ADD CONSTRAINT postulaciones_estudiantes_pkey PRIMARY KEY (id);


--
-- Name: preferencias_usuario preferencias_usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.preferencias_usuario
    ADD CONSTRAINT preferencias_usuario_pkey PRIMARY KEY (id_usuario);


--
-- Name: profesor_dimension profesor_dimension_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profesor_dimension
    ADD CONSTRAINT profesor_dimension_pkey PRIMARY KEY (id_usuario, id_dimension);


--
-- Name: profesores_pst profesores_pst_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profesores_pst
    ADD CONSTRAINT profesores_pst_pkey PRIMARY KEY (id);


--
-- Name: propuestas_empresa propuestas_empresa_codigo_seguimiento_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.propuestas_empresa
    ADD CONSTRAINT propuestas_empresa_codigo_seguimiento_key UNIQUE (codigo_seguimiento);


--
-- Name: propuestas_empresa propuestas_empresa_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.propuestas_empresa
    ADD CONSTRAINT propuestas_empresa_pkey PRIMARY KEY (id);


--
-- Name: proyecto_tutores proyecto_tutores_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.proyecto_tutores
    ADD CONSTRAINT proyecto_tutores_pkey PRIMARY KEY (id_recurso, id_tutor);


--
-- Name: recurso_autores recurso_autores_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.recurso_autores
    ADD CONSTRAINT recurso_autores_pkey PRIMARY KEY (id_recurso, id_autor);


--
-- Name: recurso_categorias recurso_categorias_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.recurso_categorias
    ADD CONSTRAINT recurso_categorias_pkey PRIMARY KEY (id_recurso, id_categoria);


--
-- Name: recurso_clasificaciones recurso_clasificaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.recurso_clasificaciones
    ADD CONSTRAINT recurso_clasificaciones_pkey PRIMARY KEY (id_recurso, id_linea_investigacion);


--
-- Name: recurso_etiquetas recurso_etiquetas_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.recurso_etiquetas
    ADD CONSTRAINT recurso_etiquetas_pkey PRIMARY KEY (id_recurso, id_etiqueta);


--
-- Name: recurso_investigadores recurso_investigadores_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.recurso_investigadores
    ADD CONSTRAINT recurso_investigadores_pkey PRIMARY KEY (id_recurso, id_investigador);


--
-- Name: recursos recursos_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.recursos
    ADD CONSTRAINT recursos_pkey PRIMARY KEY (id);


--
-- Name: registro_actividad registro_actividad_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.registro_actividad
    ADD CONSTRAINT registro_actividad_pkey PRIMARY KEY (id);


--
-- Name: roles roles_nombre_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_nombre_key UNIQUE (nombre);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: seg_lineas_alertas seg_lineas_alertas_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.seg_lineas_alertas
    ADD CONSTRAINT seg_lineas_alertas_pkey PRIMARY KEY (id);


--
-- Name: seg_lineas_bloqueos seg_lineas_bloqueos_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.seg_lineas_bloqueos
    ADD CONSTRAINT seg_lineas_bloqueos_pkey PRIMARY KEY (id);


--
-- Name: seg_lineas_excepciones seg_lineas_excepciones_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.seg_lineas_excepciones
    ADD CONSTRAINT seg_lineas_excepciones_pkey PRIMARY KEY (id);


--
-- Name: seg_lineas_logs seg_lineas_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.seg_lineas_logs
    ADD CONSTRAINT seg_lineas_logs_pkey PRIMARY KEY (id);


--
-- Name: seg_lineas_permisos seg_lineas_permisos_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.seg_lineas_permisos
    ADD CONSTRAINT seg_lineas_permisos_pkey PRIMARY KEY (nivel, submodulo, accion);


--
-- Name: seg_lineas_politicas seg_lineas_politicas_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.seg_lineas_politicas
    ADD CONSTRAINT seg_lineas_politicas_pkey PRIMARY KEY (clave);


--
-- Name: seg_lineas_rutas seg_lineas_rutas_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.seg_lineas_rutas
    ADD CONSTRAINT seg_lineas_rutas_pkey PRIMARY KEY (ruta);


--
-- Name: solicitudes_docentes_pst solicitudes_docentes_pst_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.solicitudes_docentes_pst
    ADD CONSTRAINT solicitudes_docentes_pst_pkey PRIMARY KEY (id);


--
-- Name: system_audit_log system_audit_log_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.system_audit_log
    ADD CONSTRAINT system_audit_log_pkey PRIMARY KEY (id);


--
-- Name: telemetria_cache telemetria_cache_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.telemetria_cache
    ADD CONSTRAINT telemetria_cache_pkey PRIMARY KEY (id);


--
-- Name: tipo_recurso tipo_recurso_nombre_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tipo_recurso
    ADD CONSTRAINT tipo_recurso_nombre_key UNIQUE (nombre);


--
-- Name: tipo_recurso tipo_recurso_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tipo_recurso
    ADD CONSTRAINT tipo_recurso_pkey PRIMARY KEY (id);


--
-- Name: tipo_tutor tipo_tutor_nombre_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tipo_tutor
    ADD CONSTRAINT tipo_tutor_nombre_key UNIQUE (nombre);


--
-- Name: tipo_tutor tipo_tutor_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tipo_tutor
    ADD CONSTRAINT tipo_tutor_pkey PRIMARY KEY (id);


--
-- Name: trayectos trayectos_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.trayectos
    ADD CONSTRAINT trayectos_pkey PRIMARY KEY (id);


--
-- Name: tutores tutores_cedula_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tutores
    ADD CONSTRAINT tutores_cedula_key UNIQUE (cedula);


--
-- Name: tutores tutores_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tutores
    ADD CONSTRAINT tutores_pkey PRIMARY KEY (id);


--
-- Name: privilegios unique_nivel_privilegio; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.privilegios
    ADD CONSTRAINT unique_nivel_privilegio UNIQUE (nivel_privilegio);


--
-- Name: postulaciones_estudiantes unique_postulacion; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.postulaciones_estudiantes
    ADD CONSTRAINT unique_postulacion UNIQUE (id_investigacion, id_estudiante);


--
-- Name: trayectos uq_carrera_trayecto; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.trayectos
    ADD CONSTRAINT uq_carrera_trayecto UNIQUE (id_carrera, numero);


--
-- Name: profesores_pst uq_profesores_pst; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profesores_pst
    ADD CONSTRAINT uq_profesores_pst UNIQUE (id_profesor, trayecto, trimestre);


--
-- Name: usuarios usuarios_cedula_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_cedula_key UNIQUE (cedula);


--
-- Name: usuarios usuarios_email_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_email_key UNIQUE (email);


--
-- Name: usuarios usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id);


--
-- Name: visitantes visitantes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.visitantes
    ADD CONSTRAINT visitantes_pkey PRIMARY KEY (id);


--
-- Name: waf_rate_limiter waf_rate_limiter_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.waf_rate_limiter
    ADD CONSTRAINT waf_rate_limiter_pkey PRIMARY KEY (ip, tipo);


--
-- Name: autores_cedula_key; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX autores_cedula_key ON public.autores USING btree (cedula);


--
-- Name: idx_analitica_snapshots_fecha; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_analitica_snapshots_fecha ON public.analitica_snapshots USING btree (fecha_snapshot DESC);


--
-- Name: idx_cursos_dimensiones_curso; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_cursos_dimensiones_curso ON public.cursos_dimensiones USING btree (id_curso);


--
-- Name: idx_cursos_dimensiones_dimension; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_cursos_dimensiones_dimension ON public.cursos_dimensiones USING btree (id_dimension);


--
-- Name: idx_cursos_lineas_curso; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_cursos_lineas_curso ON public.cursos_lineas USING btree (id_curso);


--
-- Name: idx_cursos_lineas_linea; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_cursos_lineas_linea ON public.cursos_lineas USING btree (id_linea);


--
-- Name: idx_cursos_preinscripciones_curso; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_cursos_preinscripciones_curso ON public.cursos_preinscripciones USING btree (id_curso);


--
-- Name: idx_detalles_inv_ofertada; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_detalles_inv_ofertada ON public.detalles_investigaciones USING btree (id_investigacion_ofertada);


--
-- Name: idx_detalles_proyectos_trayecto; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_detalles_proyectos_trayecto ON public.detalles_proyectos USING btree (id_trayecto);


--
-- Name: idx_detalles_vector_hnsw; Type: INDEX; Schema: public; Owner: -
--

DO $$ BEGIN
    IF EXISTS (SELECT 1 FROM pg_type WHERE typname = 'vector') THEN
        ALTER TABLE public.detalles_proyectos ALTER COLUMN vector_semantico TYPE public.vector(384) USING vector_semantico::public.vector;
        CREATE INDEX IF NOT EXISTS idx_detalles_vector_hnsw ON public.detalles_proyectos USING hnsw (vector_semantico public.vector_cosine_ops) WITH (m='16', ef_construction='64');
    END IF;
EXCEPTION WHEN OTHERS THEN NULL;
END $$;


--
-- Name: idx_detalles_vector_null; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_detalles_vector_null ON public.detalles_proyectos USING btree (id_recurso) WHERE (vector_semantico IS NULL);


--
-- Name: idx_inv_dim_dim; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_inv_dim_dim ON public.investigaciones_dimensiones USING btree (id_dimension);


--
-- Name: idx_inv_dim_inv; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_inv_dim_inv ON public.investigaciones_dimensiones USING btree (id_investigacion);


--
-- Name: idx_inv_lineas_inv; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_inv_lineas_inv ON public.investigaciones_lineas USING btree (id_investigacion);


--
-- Name: idx_inv_lineas_lin; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_inv_lineas_lin ON public.investigaciones_lineas USING btree (id_linea);


--
-- Name: idx_pcv_carrera; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_pcv_carrera ON public.proyecto_carreras_vinculadas USING btree (id_carrera);


--
-- Name: idx_pcv_recurso; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_pcv_recurso ON public.proyecto_carreras_vinculadas USING btree (id_recurso);


--
-- Name: idx_postulaciones_profesor_pst; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_postulaciones_profesor_pst ON public.postulaciones_estudiantes USING btree (id_profesor_pst);


--
-- Name: idx_prof_dim_linea; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_prof_dim_linea ON public.profesor_dimension USING btree (id_linea);


--
-- Name: idx_prof_dim_usuario; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_prof_dim_usuario ON public.profesor_dimension USING btree (id_usuario);


--
-- Name: idx_profesor_linea_linea; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_profesor_linea_linea ON public.profesor_linea USING btree (id_linea);


--
-- Name: idx_profesores_pst_tt; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_profesores_pst_tt ON public.profesores_pst USING btree (trayecto, trimestre);


--
-- Name: idx_propuestas_empresa_eliminado_en; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_propuestas_empresa_eliminado_en ON public.propuestas_empresa USING btree (eliminado_en);


--
-- Name: idx_recurso_clasif_dimension; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_recurso_clasif_dimension ON public.recurso_clasificaciones USING btree (id_dimension_operativa);


--
-- Name: idx_recurso_clasif_linea; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_recurso_clasif_linea ON public.recurso_clasificaciones USING btree (id_linea_investigacion);


--
-- Name: idx_seg_lineas_alertas_estado; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_seg_lineas_alertas_estado ON public.seg_lineas_alertas USING btree (estado, fecha);


--
-- Name: idx_seg_lineas_bloq_ip; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_seg_lineas_bloq_ip ON public.seg_lineas_bloqueos USING btree (ip, activo);


--
-- Name: idx_seg_lineas_exc_usuario; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_seg_lineas_exc_usuario ON public.seg_lineas_excepciones USING btree (usuario_id);


--
-- Name: idx_seg_lineas_logs_fecha; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_seg_lineas_logs_fecha ON public.seg_lineas_logs USING btree (fecha);


--
-- Name: idx_seg_lineas_logs_ip_res; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_seg_lineas_logs_ip_res ON public.seg_lineas_logs USING btree (ip, resultado, fecha);


--
-- Name: idx_seg_lineas_logs_usuario; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_seg_lineas_logs_usuario ON public.seg_lineas_logs USING btree (usuario_id, fecha);


--
-- Name: idx_solicitudes_pst_estado; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_solicitudes_pst_estado ON public.solicitudes_docentes_pst USING btree (estado);


--
-- Name: idx_solicitudes_pst_prof; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_solicitudes_pst_prof ON public.solicitudes_docentes_pst USING btree (id_profesor);


--
-- Name: idx_trayectos_carrera; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_trayectos_carrera ON public.trayectos USING btree (id_carrera);


--
-- Name: recursos tg_auditoria_recursos_delete; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER tg_auditoria_recursos_delete BEFORE DELETE ON public.recursos FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_recursos();


--
-- Name: recursos tg_auditoria_recursos_insert; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER tg_auditoria_recursos_insert AFTER INSERT ON public.recursos FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_recursos();


--
-- Name: usuarios tg_auditoria_usuarios_delete; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER tg_auditoria_usuarios_delete BEFORE DELETE ON public.usuarios FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_usuarios();


--
-- Name: usuarios tg_auditoria_usuarios_insert; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER tg_auditoria_usuarios_insert AFTER INSERT ON public.usuarios FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_usuarios();


--
-- Name: usuarios tg_auditoria_usuarios_update; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER tg_auditoria_usuarios_update AFTER UPDATE ON public.usuarios FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_usuarios();


--
-- Name: accesos_recursos accesos_recursos_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.accesos_recursos
    ADD CONSTRAINT accesos_recursos_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: accesos_recursos accesos_recursos_id_registro_actividad_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.accesos_recursos
    ADD CONSTRAINT accesos_recursos_id_registro_actividad_fkey FOREIGN KEY (id_registro_actividad) REFERENCES public.registro_actividad(id) ON DELETE CASCADE;


--
-- Name: auditoria auditoria_usuario_responsable_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auditoria
    ADD CONSTRAINT auditoria_usuario_responsable_fkey FOREIGN KEY (usuario_responsable) REFERENCES public.usuarios(id) ON DELETE SET NULL;


--
-- Name: cursos_dimensiones cursos_dimensiones_id_curso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cursos_dimensiones
    ADD CONSTRAINT cursos_dimensiones_id_curso_fkey FOREIGN KEY (id_curso) REFERENCES public.cursos(id) ON DELETE CASCADE;


--
-- Name: cursos_dimensiones cursos_dimensiones_id_dimension_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cursos_dimensiones
    ADD CONSTRAINT cursos_dimensiones_id_dimension_fkey FOREIGN KEY (id_dimension) REFERENCES public.dimensiones_operativas(id) ON DELETE CASCADE;


--
-- Name: cursos cursos_id_docente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cursos
    ADD CONSTRAINT cursos_id_docente_fkey FOREIGN KEY (id_docente) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- Name: cursos_lineas cursos_lineas_id_curso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cursos_lineas
    ADD CONSTRAINT cursos_lineas_id_curso_fkey FOREIGN KEY (id_curso) REFERENCES public.cursos(id) ON DELETE CASCADE;


--
-- Name: cursos_lineas cursos_lineas_id_linea_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cursos_lineas
    ADD CONSTRAINT cursos_lineas_id_linea_fkey FOREIGN KEY (id_linea) REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE;


--
-- Name: cursos_preinscripciones cursos_preinscripciones_id_curso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cursos_preinscripciones
    ADD CONSTRAINT cursos_preinscripciones_id_curso_fkey FOREIGN KEY (id_curso) REFERENCES public.cursos(id) ON DELETE CASCADE;


--
-- Name: detalles_proyectos detalles_proyectos_id_carrera_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalles_proyectos
    ADD CONSTRAINT detalles_proyectos_id_carrera_fkey FOREIGN KEY (id_carrera) REFERENCES public.carreras(id) ON DELETE SET NULL;


--
-- Name: detalles_proyectos detalles_proyectos_id_investigacion_padre_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalles_proyectos
    ADD CONSTRAINT detalles_proyectos_id_investigacion_padre_fkey FOREIGN KEY (id_investigacion_padre) REFERENCES public.recursos(id) ON DELETE SET NULL;


--
-- Name: detalles_proyectos detalles_proyectos_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalles_proyectos
    ADD CONSTRAINT detalles_proyectos_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: detalles_proyectos detalles_proyectos_id_trayecto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalles_proyectos
    ADD CONSTRAINT detalles_proyectos_id_trayecto_fkey FOREIGN KEY (id_trayecto) REFERENCES public.trayectos(id) ON DELETE SET NULL;


--
-- Name: detalles_articulos detalles_revistas_id_editorial_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalles_articulos
    ADD CONSTRAINT detalles_revistas_id_editorial_fkey FOREIGN KEY (id_editorial) REFERENCES public.editoriales(id) ON DELETE SET NULL;


--
-- Name: detalles_articulos detalles_revistas_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalles_articulos
    ADD CONSTRAINT detalles_revistas_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: detalles_investigaciones fk_detalles_investigaciones_ofertada; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalles_investigaciones
    ADD CONSTRAINT fk_detalles_investigaciones_ofertada FOREIGN KEY (id_investigacion_ofertada) REFERENCES public.investigaciones_ofertadas(id) ON DELETE SET NULL;


--
-- Name: detalles_investigaciones fk_detalles_investigaciones_recurso; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalles_investigaciones
    ADD CONSTRAINT fk_detalles_investigaciones_recurso FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: dimensiones_operativas fk_dimension_linea; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.dimensiones_operativas
    ADD CONSTRAINT fk_dimension_linea FOREIGN KEY (id_linea) REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE;


--
-- Name: recurso_clasificaciones fk_dimension_operativa; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.recurso_clasificaciones
    ADD CONSTRAINT fk_dimension_operativa FOREIGN KEY (id_dimension_operativa) REFERENCES public.dimensiones_operativas(id) ON DELETE SET NULL;


--
-- Name: recurso_etiquetas fk_etiqueta_recurso; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.recurso_etiquetas
    ADD CONSTRAINT fk_etiqueta_recurso FOREIGN KEY (id_etiqueta) REFERENCES public.etiquetas(id) ON DELETE CASCADE;


--
-- Name: investigaciones_ofertadas fk_inv_dimension; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.investigaciones_ofertadas
    ADD CONSTRAINT fk_inv_dimension FOREIGN KEY (id_dimension) REFERENCES public.dimensiones_operativas(id) ON DELETE SET NULL;


--
-- Name: investigaciones_ofertadas fk_inv_linea; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.investigaciones_ofertadas
    ADD CONSTRAINT fk_inv_linea FOREIGN KEY (id_linea) REFERENCES public.lineas_investigacion(id) ON DELETE RESTRICT;


--
-- Name: investigaciones_ofertadas fk_inv_profesor; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.investigaciones_ofertadas
    ADD CONSTRAINT fk_inv_profesor FOREIGN KEY (id_profesor) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- Name: recurso_investigadores fk_investigador; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.recurso_investigadores
    ADD CONSTRAINT fk_investigador FOREIGN KEY (id_investigador) REFERENCES public.investigadores(id) ON DELETE CASCADE;


--
-- Name: recurso_clasificaciones fk_linea_investigacion; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.recurso_clasificaciones
    ADD CONSTRAINT fk_linea_investigacion FOREIGN KEY (id_linea_investigacion) REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE;


--
-- Name: proyecto_carreras_vinculadas fk_pcv_carrera; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.proyecto_carreras_vinculadas
    ADD CONSTRAINT fk_pcv_carrera FOREIGN KEY (id_carrera) REFERENCES public.carreras(id) ON DELETE CASCADE;


--
-- Name: proyecto_carreras_vinculadas fk_pcv_recurso; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.proyecto_carreras_vinculadas
    ADD CONSTRAINT fk_pcv_recurso FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: profesor_linea fk_pl_linea; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profesor_linea
    ADD CONSTRAINT fk_pl_linea FOREIGN KEY (id_linea) REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE;


--
-- Name: profesor_linea fk_pl_usuario; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profesor_linea
    ADD CONSTRAINT fk_pl_usuario FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- Name: postulaciones_estudiantes fk_postulacion_estudiante; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.postulaciones_estudiantes
    ADD CONSTRAINT fk_postulacion_estudiante FOREIGN KEY (id_estudiante) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- Name: postulaciones_estudiantes fk_postulacion_inv; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.postulaciones_estudiantes
    ADD CONSTRAINT fk_postulacion_inv FOREIGN KEY (id_investigacion) REFERENCES public.investigaciones_ofertadas(id) ON DELETE CASCADE;


--
-- Name: postulaciones_estudiantes fk_postulacion_profesor_pst; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.postulaciones_estudiantes
    ADD CONSTRAINT fk_postulacion_profesor_pst FOREIGN KEY (id_profesor_pst) REFERENCES public.usuarios(id) ON DELETE SET NULL;


--
-- Name: profesores_pst fk_profesores_pst_usuario; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profesores_pst
    ADD CONSTRAINT fk_profesores_pst_usuario FOREIGN KEY (id_profesor) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- Name: propuestas_empresa fk_propuestas_archivado_por; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.propuestas_empresa
    ADD CONSTRAINT fk_propuestas_archivado_por FOREIGN KEY (archivado_por) REFERENCES public.usuarios(id) ON DELETE SET NULL;


--
-- Name: recurso_clasificaciones fk_recurso; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.recurso_clasificaciones
    ADD CONSTRAINT fk_recurso FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: recurso_etiquetas fk_recurso_etiqueta; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.recurso_etiquetas
    ADD CONSTRAINT fk_recurso_etiqueta FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: recurso_investigadores fk_recurso_inv; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.recurso_investigadores
    ADD CONSTRAINT fk_recurso_inv FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: historico_versiones_pst fk_version_recurso; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.historico_versiones_pst
    ADD CONSTRAINT fk_version_recurso FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: investigaciones_dimensiones investigaciones_dimensiones_id_dimension_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.investigaciones_dimensiones
    ADD CONSTRAINT investigaciones_dimensiones_id_dimension_fkey FOREIGN KEY (id_dimension) REFERENCES public.dimensiones_operativas(id) ON DELETE CASCADE;


--
-- Name: investigaciones_dimensiones investigaciones_dimensiones_id_investigacion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.investigaciones_dimensiones
    ADD CONSTRAINT investigaciones_dimensiones_id_investigacion_fkey FOREIGN KEY (id_investigacion) REFERENCES public.investigaciones_ofertadas(id) ON DELETE CASCADE;


--
-- Name: investigaciones_lineas investigaciones_lineas_id_investigacion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.investigaciones_lineas
    ADD CONSTRAINT investigaciones_lineas_id_investigacion_fkey FOREIGN KEY (id_investigacion) REFERENCES public.investigaciones_ofertadas(id) ON DELETE CASCADE;


--
-- Name: investigaciones_lineas investigaciones_lineas_id_linea_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.investigaciones_lineas
    ADD CONSTRAINT investigaciones_lineas_id_linea_fkey FOREIGN KEY (id_linea) REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE;


--
-- Name: lineas_investigacion lineas_investigacion_id_carrera_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.lineas_investigacion
    ADD CONSTRAINT lineas_investigacion_id_carrera_fkey FOREIGN KEY (id_carrera) REFERENCES public.carreras(id) ON DELETE CASCADE;


--
-- Name: notificaciones notificaciones_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.notificaciones
    ADD CONSTRAINT notificaciones_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- Name: preferencias_usuario preferencias_usuario_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.preferencias_usuario
    ADD CONSTRAINT preferencias_usuario_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- Name: profesor_dimension profesor_dimension_id_dimension_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profesor_dimension
    ADD CONSTRAINT profesor_dimension_id_dimension_fkey FOREIGN KEY (id_dimension) REFERENCES public.dimensiones_operativas(id) ON DELETE CASCADE;


--
-- Name: profesor_dimension profesor_dimension_id_linea_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profesor_dimension
    ADD CONSTRAINT profesor_dimension_id_linea_fkey FOREIGN KEY (id_linea) REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE;


--
-- Name: profesor_dimension profesor_dimension_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profesor_dimension
    ADD CONSTRAINT profesor_dimension_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- Name: proyecto_tutores proyecto_tutores_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.proyecto_tutores
    ADD CONSTRAINT proyecto_tutores_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.detalles_proyectos(id_recurso) ON DELETE CASCADE;


--
-- Name: proyecto_tutores proyecto_tutores_id_tutor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.proyecto_tutores
    ADD CONSTRAINT proyecto_tutores_id_tutor_fkey FOREIGN KEY (id_tutor) REFERENCES public.tutores(id) ON DELETE CASCADE;


--
-- Name: proyecto_tutores proyecto_tutores_tipo_tutor_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.proyecto_tutores
    ADD CONSTRAINT proyecto_tutores_tipo_tutor_id_fkey FOREIGN KEY (tipo_tutor_id) REFERENCES public.tipo_tutor(id) ON DELETE SET NULL;


--
-- Name: recurso_autores recurso_autores_id_autor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.recurso_autores
    ADD CONSTRAINT recurso_autores_id_autor_fkey FOREIGN KEY (id_autor) REFERENCES public.autores(id) ON DELETE CASCADE;


--
-- Name: recurso_autores recurso_autores_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.recurso_autores
    ADD CONSTRAINT recurso_autores_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: recurso_categorias recurso_categorias_id_categoria_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.recurso_categorias
    ADD CONSTRAINT recurso_categorias_id_categoria_fkey FOREIGN KEY (id_categoria) REFERENCES public.categorias(id) ON DELETE CASCADE;


--
-- Name: recurso_categorias recurso_categorias_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.recurso_categorias
    ADD CONSTRAINT recurso_categorias_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: recursos recursos_id_tipo_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.recursos
    ADD CONSTRAINT recursos_id_tipo_recurso_fkey FOREIGN KEY (id_tipo_recurso) REFERENCES public.tipo_recurso(id) ON DELETE RESTRICT;


--
-- Name: registro_actividad registro_actividad_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.registro_actividad
    ADD CONSTRAINT registro_actividad_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id) ON DELETE SET NULL;


--
-- Name: registro_actividad registro_actividad_id_visitante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.registro_actividad
    ADD CONSTRAINT registro_actividad_id_visitante_fkey FOREIGN KEY (id_visitante) REFERENCES public.visitantes(id) ON DELETE SET NULL;


--
-- Name: solicitudes_docentes_pst solicitudes_docentes_pst_id_profesor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.solicitudes_docentes_pst
    ADD CONSTRAINT solicitudes_docentes_pst_id_profesor_fkey FOREIGN KEY (id_profesor) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- Name: solicitudes_docentes_pst solicitudes_docentes_pst_id_revisor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.solicitudes_docentes_pst
    ADD CONSTRAINT solicitudes_docentes_pst_id_revisor_fkey FOREIGN KEY (id_revisor) REFERENCES public.usuarios(id) ON DELETE SET NULL;


--
-- Name: trayectos trayectos_id_carrera_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.trayectos
    ADD CONSTRAINT trayectos_id_carrera_fkey FOREIGN KEY (id_carrera) REFERENCES public.carreras(id) ON DELETE CASCADE;


--
-- Name: usuarios usuarios_id_rol_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_id_rol_fkey FOREIGN KEY (id_rol) REFERENCES public.roles(id) ON DELETE RESTRICT;


--
-- PostgreSQL database dump complete
--



